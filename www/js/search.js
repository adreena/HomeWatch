/** Graph manager. */
require(['jquery',
        'underscore',
        'search/defines',
        'utils/TemplateManager',
        'spiffy/spiffy.min',
        'vendor/json2'],

function ($, _, D, TemplateManager) {
    var makeCategories,

        parseGraphControls,
        validateGraphRequest,
        parseGraphType,

        addGraph,
        bindWeirdDateEvents,

        /* Element renderers. */
        graphControlAxes,
        graphCreateOptGroups,
        graphControlDateTime,
        graphControlApartments,
        graphControlDisplayType,

        tman = new TemplateManager();

    /*
     * GRAPH CONTROLLER STUFF
     */

    /**
     * Takes that category array and converts it into things
     * that can be converted into optgroups for x and y.
     *
     * Also, creates an object that maps arbitrary value IDs to a
     * metadata object that contains the information needed to
     * send to process.php.
     *
     * Returns {
     *      x: { 'group1' : { "Display Name": "1" },
     *      y: { 'group1' : { "Display Name": "1" },
     *      values: { "1": {type: 'sensorarray', values: ["One value"]} }
     * }
     *
     */
    makeCategories = function (categories) {
        var x = {}, y = {}, values = {};


        /* Parse the category names. */
        _.each(categories, function (elements, catName) {
            var valueType = D.categoryNameToType[catName];

            /* Initialize the category. */
            x[catName] = {};
            y[catName] = {};

            _.each(elements, function (info, name) {
                var displayName, value, valueID, forX, forY;

                /* Assume the value is applicable for both axes. */
                forX = true;
                forY = true;

                if (_.isString(info)) {
                    /* Use the defaults with this as the display name. */
                    displayName = info;
                    value = [name];

                } else {
                    /* It's a big scary object. */

                    displayName = info.displayName;

                    /* Check if it has multiple values, else just use the
                     * name as the value. */
                    value = (info.hasOwnProperty('multiple'))
                        ? info.multiple
                        : [name];

                    if (info.hasOwnProperty('applicableAxes')) {
                        forX = /x/.test(info.applicableAxes);
                        forY =  /y/.test(info.applicableAxes);
                    }
                }

                /* Create a value ID for the value and insert it into
                 * the value array thing. */
                valueID = _.uniqueId();
                values[valueID] = {
                    type: valueType,
                    values: value
                };

                /* Make sure we add to the applicable axes! */
                if (forX) {
                    x[catName][displayName] = valueID;
                }

                if (forY) {
                    y[catName][displayName] = valueID;
                }

            });

        });

        return { x: x, y: y, values: values };

    };

    /**
     * Given a graph HTML thing (ID? Element?) will
     * parse its HTML controller and return the
     * data need to pass to process.php.
     */
    parseGraphControls = function (graph) {
        return D.exampleProcessParameters;
    };

    /**
     * Gets the graph type from the graph controls.
     */
    parseGraphType = function (graph) {
        // TODO: parse the graph type!
    };

    /**
     * This one is mostly for debug: returns
     * where the graph request contains all the keys it
     * needs in order to make process.php happy.
     */
    validateGraphRequest = function (graphObject) {
        var requiredKeys = [
            "startdate", "enddate", "xaxis", "x", "xtype", "yaxis", "y",
            "ytype", "period", "apartments"
        ];

        return _.all(requiredKeys, function (key) {
            return graphObject.hasOwnProperty(key);
        });
    };


    /*
     * MY STUFF THAT RENDERS TEMPLATES
     */

    /**
     * Given a categories object, returns an HTML string that makes
     * <option>/<optgroup> elements out of it.
     */
    graphCreateOptGroups = function (categories) {
        return tman.render('graph-optgroup', {categories : categories });
    };

    /**
     * Creates the content for the axes graph controls thing.
     *
     * Needs data to make the axes optgroups.
     */
    graphControlAxes = function (x, y) {
        return tman.render('graph-control-axes', {
            /* The content is just the two optgroups appended. */
            xAxis: graphCreateOptGroups(x),
            yAxis: graphCreateOptGroups(y)
        });
    };

    /** Creates the content for the date time controller thing. */
    graphControlDateTime = function () {
        /* This one takes no parameters... for now. */
        return tman.render('graph-control-datetime', {});
    };

    graphControlApartments = function () {
        return tman.render('graph-control-apartments', {});
    };

    graphControlDisplayType = function () {
        return tman.render('graph-control-types', {});
    };



    /**
     * Adds a graph to the page. Where?
     * It's appended to 'place' (jQuery element).
     * Data is { axes: {}, apartments: {} }.
     *
     * Returns the graph ID.
     */
    addGraph = function (place, data) {
        var elements,
            rendered,
            graphID = _.uniqueId('graph'),
            renderedElements,
            cats,
            placed;

        /* DEBUG! */
        cats = makeCategories(D.exampleCategories);

        elements = {
            'Axes': graphControlAxes(cats.x, cats.y),
            'Date/Time': graphControlDateTime(),
            'Apartments': graphControlApartments(),
            'Graph Type': graphControlDisplayType()
        };

        renderedElements = _.map(elements, function (content, title) {
            return tman.render('graph-control-li', {
                header: title,
                content: content
            });
        }).join('');

        rendered = tman.render('graph-group', {
            graphID: graphID,
            graphControls: renderedElements
        });

        /* HACK! Create a temporary div to conver the
         * text element into a jQuery element. */
        rendered = $('<div>').html(rendered).children().first();

        bindWeirdDateEvents(rendered);

        place.append(rendered);

        return graphID;

    };

    /**
     * Graph controller is a jQuery which we can bind events to.
     * Yay!
     */
    bindWeirdDateEvents = function (graphController) {
        var dateThing, granularityChooser, hideAll, onChange;

        /* Get the date div and the drop down that will find the proper
         * granularity. */
        dateThing = graphController .find('.graph-controls-datetime').first();
        granularityChooser = dateThing.find('[name=granularity]');

        /* Hides all of the date/time category things. */
        hideAll = function () {
            dateThing.children('div').hide();
        };

        /* This will show only the proper granularity selector thing. */
        onChange = function () {
            var granularity = granularityChooser.val().toLowerCase();
            hideAll();
            dateThing.children('.graph-controls-' + granularity).show();
        };

        granularityChooser.change(onChange);

        /* Pretend it change for the first time. */
        onChange();

    };



    /**
     * ON DOCUMENT LOAD
     */


    $(function () {
        /* THIS IS ALL DEBUG! */


        var grrid = addGraph($(D.sel.graphList), undefined),
            theOneGraph = $('#' + grrid),
            dump = theOneGraph.find('.debug-results');

        theOneGraph.find('[href=#DEBUG]').click(function () {
            var query;

            query = {
                /* Disable process.php's test thing... stuff. */
                notest: true,
                /* Serialize the example 'cause I ain't got notin' else. */
                graph: JSON.stringify(parseGraphControls(grrid))
            };

            $.ajax({
                url: D.uri.process,
                type: 'GET',
                data: query,

                success: function (graphInfo) {
                    var asText = JSON.stringify(graphInfo, null, true);
                    dump.text(asText);
                },

                error: function () {
                    dump.text('Something went wrong while contacting process.php')
                }

            });

            return false;

        });

        /* TEMPORARY: Trying out spiffy because why not? */
        $('.graph-controls').spiffy();
        $('.graph-controls > li > h3').addClass('spiffy-header');

    });

});
