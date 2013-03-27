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

    var parseGraphControls,
        validateGraphRequest,
        parseGraphType,

        makeGraphGroup,

        /* Element renderers. */
        graphCreateOptGroups,
        graphControlAxes,
        graphControlDateTime,
        graphControlApartments,
        graphControlDisplayType,

        /* Binds events for the elements. */
        bindWeirdDateEvents,
        bindSelectAlls,

        tman = new TemplateManager();

    /** Creates a new graph control. */
    function GraphControl(graphManager, data) {
        var id;
        id = _.uniqueId('graph');
        this.manager = graphManager;

        /* TEMPORARY: */
        data.apartments = [1,2,3,4,5,6];

        this.element = makeGraphGroup(id, data);
        this.id = id;

    }


    /*
     * Template Rendering and element preparation.
     */

    /**
     * Vaguely complete graph group creating function.
     * DATA: not sure what this will be yet. Right now, it's just a x category
     * and a y category, plus apartment numbers.
     *
     * Returns a graph group jQuery element.
     */
    makeGraphGroup = function (graphID, data) {
        var elements,
            rendered,
            renderedElements,
            placed;

        /* Make all of the elements. */
        elements = {
            'Axes': graphControlAxes(data.x, data.y),
            'Date/Time': graphControlDateTime(),
            'Apartments': graphControlApartments(data.apartments),
            'Graph Type': graphControlDisplayType()
        };

        /* Place 'em in the appropriate container. */
        renderedElements = _.map(elements, function (content, title) {
            return tman.render('graph-control-li', {
                header: title,
                content: content
            });
        }).join('');

        /* Render the ENTIRE graph group. */
        rendered = tman.render('graph-group', {
            graphID: graphID,
            graphControls: renderedElements
        });

        /* Create a temporary div to convert the text element into a jQuery
         * element. */
        rendered = $('<div>').html(rendered).children().first();

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

        /* Should find parent with checkboxes. */

        selectToggler.click(function (event) {
            event.preventDefault();
        });
    };


    /* Parsing and retrieving data. */


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




    /* Export the class. */
    return GraphControl;

});
