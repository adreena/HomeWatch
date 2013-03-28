/**
 * GraphControl. Manages interface and controls of one graph.
 */
define([
    'jquery',
    'underscore',
    './Graph',
    'utils/TemplateManager'],

function ($, _, Graph, TemplateManager) {
    "use strict";

    // TODO: USE Graph!


    var tman = new TemplateManager(),

        /* Create the graph-group element and bind all of its events. */
        makeGraphGroup,

        /* Element renderers. */
        graphCreateOptGroups,
        graphControlAxes,
        graphControlDateTime,
        graphControlApartments,
        graphControlDisplayType,

        /* Binds events for the elements. */
        bindWeirdDateEvents,
        bindSelectAlls;


    /** Creates a new GraphControl. A GraphController has data. */
    function GraphControl(graphManager, data) {
        var id, element;

        id = _.uniqueId('graph');
        this.manager = graphManager;
        element = $(makeGraphGroup(id, data));

        /* The following are shortcuts to jQuery objects. */
        this.el = {};
        this.element = element;
        /* The control panel. */
        this.el.controls = element.find('.graph-controls');
        /* The graph panel. */
        this.el.graph = element.find('.graph-container');

        this.id = id;

    }



    /* Parsing and retrieving data. */

    /**
     * Given a graph HTML thing (ID? Element?) will
     * parse its HTML controller and return the
     * data need to pass to process.php.
     */
    GraphControl.prototype.getQuery = function () {
        // Look INSIDE the element
    };

    /**
     * Gets the graph type from the graph controls.
     */
    GraphControl.prototype.getGraphType = function () {
        var checkedRadio = this.el.controls.find('input[type=radio]:checked'),
            graphType = checkedRadio.val();

        return graphType;
    };


    /*
     * Template Rendering and element preparation.
     */

    /**
     * Vaguely complete graph group creating function.
     * DATA: not sure what this will be yet. Right now, it's just a x
     * category and a y category, plus apartment numbers.
     *
     * Returns a graph group jQuery element.
     */
    makeGraphGroup = function (graphID, data) {
        var elements,
            asText,
            renderedElements,
            rendered;

        /* Make all of the elements. */
        elements = [
            {
                header: 'Axes',
                content:  graphControlAxes(data.x, data.y)
            },
            {
                header: 'Date/Time',
                content:  graphControlDateTime()
            },
            {
                header: 'Apartments',
                content:  graphControlApartments(data.apartments)
            },
            {
                header: 'Graph Type',
                content:  graphControlDisplayType()
            }
        ];

        /* Place 'em in the appropriate container. */
        renderedElements = _.map(elements, function (params) {
            return tman.render('graph-control-li', params);
        }).join('');

        /* Render the ENTIRE graph group. */
        asText = tman.render('graph-group', {
            graphID: graphID,
            graphControls: renderedElements
        });

        /* Create a temporary div to convert the text element into a jQuery
         * element. */
        rendered = $('<div>').html(asText).children().first();

        bindWeirdDateEvents(rendered);
        bindSelectAlls(rendered);

        return rendered;

    };



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

    /** Creates the content for the appartment picker. */
    graphControlApartments = function (apartments) {
        return tman.render('graph-control-apartments', {
            apartments: apartments
        });
    };

    /** Creates the content for the graph type picker. */
    graphControlDisplayType = function () {
        return tman.render('graph-control-types', {});
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

    /* Binds select all. Doesn't really work yet. */
    bindSelectAlls = function (element) {
        var selectToggler = element.find('[data-select-all]'),
            parent = selectToggler.parent(),
            checkboxes = parent.children('input[type=checkbox]');

        // TODO: Should find parent with checkboxes.


        selectToggler.click(function (event) {
            event.preventDefault();
        });
    };




    /*
     * "Public static methods"
     * These functions are exported, but are useless on a single
     * instance. Regardless, they belong to this "class".
     */

    /**
     * Returns whether or not the graph request contains all the keys
     * it needs in order to make process.php happy. This is mostly for
     * debug and sanity checking.
     */
    GraphControl.validateGraphRequest = function (graphRequest) {
        var requiredKeys = [
            "startdate", "enddate", "xaxis", "x", "xtype", "yaxis", "y",
            "ytype", "period", "apartments"
        ];

        return _.all(requiredKeys, function (key) {
            return _(graphRequest).has(key);
        });
    };

    /* Export the class. */
    return GraphControl;

});
