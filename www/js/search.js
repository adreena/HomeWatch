/** Graph manager. */
define(['jquery', 'underscore'],/* 'search/defines'],*/ function ($, _, D) {

    /* Each of these things should go in their own modules,
     * probably. */
    var render,
        templateCache = {},
        compileTemplate,
        fetchTemplateText,

        parseCategories,

        parseGraphControls,
        parseGraphType,

        addGraph,
        graphControlAxes,
        graphCreateOptGroups,

        __temp_hardCodedCategories;


    /* This is an example of how the sensors will look. It's in simple JSON
     * so that we can move this to a simple file if need be.
     * If the value is a string, that is its display name.
     * Otherwise, the value is an object that must contain the
     * display name. It may also contain any axis constraints (by default, 'xy'), in
     * "applicableAxes".
     * Multiple values are specified as an array of value strings.
     */
    __temp_hardCodedCategories = {
        "Time": {
            "time": {
                "displayName": "Time",
                "applicableAxes": "x"
            }
        },

        "Sensors": {
            "CO2": "Carbon Dioxide (PPM)",
            "all_electricity": {
                "multiple": [
                    "Mains (Phase A)",
                    "Bedroom and hot water tank (Phase A)",
                    "Oven (Phase A) and range hood",
                    "Microwave and ERV controller",
                    "Electrical duct heating",
                    "Kitchen plugs (Phase A) and bathroom lighting",
                    "Energy recovery ventilation",
                    "Mains (Phase B)",
                    "Kitchen plugs (Phase B) and kitchen counter",
                    "Oven (Phase B)",
                    "Bathroom",
                    "Living room and balcony",
                    "Hot water tank (Phase B)",
                    "Refrigerator"],
                "applicableAxes": "y",
                "displayName": "All electricity"
            }
        },

        "Formulae": {
            "waffles": "Waffles"
        }
    };


    /*
     * GRAPH CONTROLLER STUFF
     */

    /* Takes that category array and converts it into things
     * that can be converted into optgroups for x and y.
     * Additionally, includes a value ID to values array thing.
     *
     * Returns {
     *      x: { 'group1' : { "Display Name": "v123" },
     *      y: { 'group1' : { "Display Name": "v123" },
     *      values: { "v123": ["One value"] }
     * }
     *
     * Might want to change this so that values is a key-value
     * array of unique ids for values that we can later
     * serialize.
     */
    parseCategories = function (categories) {
        var x = {}, y = {}, values = {};

        /* Parse the category names. */
        _.each(categories, function (elements, catName) {

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

                /* Create a value ID for the value. */
                valueID = _.uniqueId();
                values[valueID] = value;

                /* Place in the appropriate parsed value. */
                if (forX) {
                    x[catName][displayName] = valueID;
                }

                if (forY) {
                    y[catName][displayName] = valueID;
                }

            });

        });

        return {
            x: x,
            y: y
        };

    };

    /**
     * Given a graph HTML thing (ID? Element?) will
     * parse its HTML controller and return the
     * data need to pass to process.php.
     */
    parseGraphControls = function (graph) {
        // TODO: write this...

        // By the way, I don't need to return
        // the graph type.
    };

    /**
     * Gets the graph type from the graph controls.
     */
    parseGraphType = function (graph) {
        // TODO: parse the graph type!
    };



    /*
     * MY STUFF THAT RENDERS TEMPLATES
     */

    /**
     * Given a categories object, returns an HTML string that makes
     * <option>/<optgroup> elements out of it.
     */
    graphCreateOptGroups = function (categories) {
        return render('graph-optgroup', {categories : categories });
    };

    /**
     * Creates the content for the axes graph controls thing.
     *
     * Needs data to make the axes optgroups.
     */
    graphControlAxes = function (_unused) {
        var cats, content;

        /* DEBUG! */
        cats = parseCategories(__temp_hardCodedCategories);

        content = render('graph-control-axes', {
            /* The content is just the two optgroups appended. */
            xAxis: graphCreateOptGroups(cats.x),
            yAxis: graphCreateOptGroups(cats.y)
        });

        return render('graph-control-li', {
            header: 'Axes', /* Probably would want this to be i18n safe.. */
            content: content
        });
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
            renderedElements;

        elements = [graphControlAxes()];

        renderedElements = elements.join('');

        rendered = render('graph-group', {
            graphID: graphID,
            graphControls: renderedElements
        });

        place.append(rendered);

        return graphID;

    };



    /*
     * TEMPLATE MANAGMENT AND RENDERING STUFF.
     */


    /**
     * Gets template text from the page. Should be
     * a callback, but currently hard-coded.
     */
    fetchTemplateText = function (templateName) {
        return $('#_t-' + templateName).html();
    };

    /** Underscore template manager. */
    render = function (templateName, parameters, options) {
        var templateText, template;

        /* If the template has not been compiled yet, compile it
         * and add it to the cache. */
        if (!templateCache.hasOwnProperty(templateName)) {
            templateText = fetchTemplateText(templateName);
            templateCache[templateName] = _.template(templateText);
        }

        template = templateCache[templateName];

        return template(parameters, options);
    };


    /**
     * ON DOCUMENT LOAD
     */


    $(function () {
        addGraph($('ul#graphs'), undefined);
    });

    return {
        render: render
    };

});
