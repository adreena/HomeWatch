/**
 * GraphControl. Manages interface and controls of one graph.
 */
define([
    'jquery',
    'underscore',
    'search/defines',
    './Graph',
    'utils/TemplateManager'],

function ($, _, D, Graph, TemplateManager) {
    "use strict";

    var tman = new TemplateManager(),

        /* Create the graph-group element and bind all of its events. */
        makeGraphGroup,

        /* Element renderers. */
        renderOptGroups,
        renderControlAxes,
        renderControlDateTime,
        renderControlApartments,
        renderControlDisplayType,

        /* Element fetchers. */
        fetchAxes,
        fetchApartments,
        fetchDateTime,

        /* Binds events for the elements. */
        bindWeirdDateEvents,
        bindSelectAlls;


    /** Creates a new GraphControl. A GraphController has data. */
    function GraphControl(graphManager, data) {
        var id, element, graph, self = this;

        id = _.uniqueId('graph');
        element = $(makeGraphGroup(id, data));

        /** The GraphManager. */
        this.id = id;
        this.manager = graphManager;
        this.data = data;

        /** The following are shortcuts to jQuery elements. */
        this.el = {};
        this.element = element;
        /** The control panel. */
        this.el.controls = element.find(D.sel.graphControl);
        /** The graph panel. */
        this.el.graph = element.find(D.sel.flotGraph);

        /* This should actually put a placeholder there until the
         * data is valid. */
        this.graph = new Graph(this.el.graph, function (newRequest) {
            self.onGranularityChange(newRequest);
        });

        this._bindOnChange();

    }



    /*
     * Parsing and retrieving data.
     */

    /**
     * Given a graph HTML thing (ID? Element?) will
     * parse its HTML controller and return the
     * data need to pass to process.php.
     */
    GraphControl.prototype.getQuery = function () {
        var query = {}, fetches;

        fetches = [
            fetchAxes(this.element, this.data.values), // Get the axes info.
            fetchApartments(this.element),
            fetchDateTime(this.element),
        ];

        _.each(fetches, function (partial) {
            _(query).extend(partial);
        });

        //console.log("Asserting whether the query is valid: ",
        //        GraphControl.validateGraphRequest(query));
        //console.log(query);

        return query;
    };

    /**
     * Gets the graph type from the graph controls.
     */
    GraphControl.prototype.getGraphType = function () {
        var checkedRadio = this.el.controls.find('input[type=radio]:checked');

        return checkedRadio.val();
    };



    /*
     * Updating the view.
     */

    /**
     * This is to be called when the graph thinks its parameters has changed
     * (e.g., when it has been clicked).
     */
    GraphControl.prototype.onGranularityChange = function (newRequest) {
        var requestFromPicker, fullRequest;

        requestFromPicker = this.getQuery();
        fullRequest = _(requestFromPicker).extend(newRequest);

        this.makeRequest(fullRequest);
    };

    /** Binds the update handlers. */
    GraphControl.prototype._bindOnChange = function () {
        var controls = this.el.controls, self = this;

        controls.find('input, select').change(function () {
            var updatedData = self.getQuery();

            self.makeRequest(updatedData);

        });
    };

    /** Politely asks the manager to fetch new data for us. */
    GraphControl.prototype.makeRequest = function (newData) {
        this.manager.makeRequest(this, newData);
    };

    /**
     * Should be called (probably by the GraphManager) when new
     * plottable data arrives.
     */
    GraphControl.prototype.onNewData = function  (newData) {
        /* Delegate this to update the data on the graph. */
        newData.graphType = this.getGraphType();

        this.graph.update(newData.values);
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
                content:  renderControlAxes(data.x, data.y)
            },
            {
                header: 'Date/Time',
                content:  renderControlDateTime()
            },
            {
                header: 'Apartments',
                content:  renderControlApartments(data.apartments)
            },
            {
                header: 'Graph Type',
                content:  renderControlDisplayType()
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
    renderOptGroups = function (categories) {
        return tman.render('graph-optgroup', {categories : categories });
    };

    /**
     * Creates the content for the axes graph controls thing.
     *
     * Needs data to make the axes optgroups.
     */
    renderControlAxes = function (x, y) {
        return tman.render('graph-control-axes', {
            /* The content is just the two optgroups appended. */
            xAxis: renderOptGroups(x),
            yAxis: renderOptGroups(y)
        });
    };

    /** Creates the content for the date time controller thing. */
    renderControlDateTime = function () {
        /* This one takes no parameters... for now. */
        return tman.render('graph-control-datetime', {});
    };

    /** Creates the content for the appartment picker. */
    renderControlApartments = function (apartments) {
        return tman.render('graph-control-apartments', {
            apartments: apartments
        });
    };

    /** Creates the content for the graph type picker. */
    renderControlDisplayType = function () {
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

    /* Fetchers. Note that these are ridiculously hard-coded for
     * the template given. */

    /**
     * Fetches the partial query for X and Y information from the
     * '.graph-control-axes-{x,y}' elements contained within the given
     * subelement.
     */
    fetchAxes = function (controlElement, values) {
        var partialQuery =  {};

        // The  "v" is for "variable variable"! 
        _.each(['x', 'y'], function (v) {
            var select = controlElement.find('select[name=' + v + 'axis]'),
                valueID,
                valueTuple;

            /* Assumes a single select. */
            valueID = select.val();
            valueTuple = values[valueID];

            /* Set the values in the partial tuple. */
            partialQuery[v + 'type'] = valueTuple.type;
            partialQuery[v] = (v == 'x') ? valueTuple.values[0] : valueTuple.values ;
            partialQuery[v + 'axis'] = valueTuple.values

        });
        
        return partialQuery;
    };

    fetchApartments = function (controlElement) {
        var checkboxList, apartments;

        checkboxList = controlElement.find("[data-name=apts] input:checked");
        apartments = $.map(checkboxList, function (el) {
            return $(el).val();
        });

        return {
            apartments: apartments.length ? apartments : []
        };
    };

    fetchDateTime = function (controlElement) {
        var granularity, subfetchers, chosenControls, range;
        
        /* Get the granularity/period value. */
        granularity = controlElement.find('select[name=granularity]').val();
        /* ...and get the applicable control. */
        chosenControls = controlElement.find('.graph-controls-' +
                granularity.toLowerCase());

        /* TODO: Make these subfetchers less terrible. */
        subfetchers = {
            Hourly: function () {
                var theONLYDate = chosenControls.find('input[name=start]').val();
                return {
                    start: theONLYDate,
                    end: theONLYDate
                };
            },
            Daily: function () {
                /* This is the only one for which these controls are actually
                 * applicable for... */
                return {
                    start: chosenControls.find('input[name=start]').val(),
                    end: chosenControls.find('input[name=end]').val(),
                };
            },
            Weekly: function () {
                return {
                    start: chosenControls.find('input[name=start]').val(),
                    end: chosenControls.find('input[name=end]').val(),
                };
            },
            Monthly: function () {
                return {
                    start: chosenControls.find('input[name=start]').val(),
                    end: chosenControls.find('input[name=end]').val(),
                };
            }
        };

        range = subfetchers[granularity]();

        return {
            startdate: range.start,
            enddate: range.end,
            period: granularity
        };
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
