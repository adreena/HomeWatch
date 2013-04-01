/**
 * GraphControl. Manages the interface controls for one graph.
 */

/*jslint browser: true, nomen: true, white: true, indent: 4, maxlen: 120 */
/*global define */

define([
    'jquery',
    'underscore',
    'search/defines',
    './Graph',
    'utils/TemplateManager',
    'spiffy/spiffy.min',
    'vendor/jquery.jdpicker'],

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
        bindSelectEvents,
        prevDef,

        jdPickerDefaultsWith;


    /** Creates a new GraphControl. A GraphController has data. */
    function GraphControl(graphManager, data) {
        var id, element, self = this;

        id = _.uniqueId('graph');
        element = $(makeGraphGroup(id, data));

        /** The unique ID of this instance. */
        this.id = id;
        /** The GraphManager. */
        this.manager = graphManager;
        /** The values that are associated wit the drop-downs and such. */
        this.values = data.values;

        /** Set the current data to the empty object. */
        this.data = {};

        /** The root element. */
        this.element = element;
        /** The following are shortcuts to jQuery elements. */
        this.el = {};
        /** The control panel. */
        this.el.controls = element.find(D.sel.graphControls);
        /** The graph panel. */
        this.el.graph = element.find(D.sel.flotGraph);
        this.el.info = element.find(D.sel.graphContainer);
        /** The top visibility controls. */
        this.el.visControls = element.find(D.sel.graphVisibilityControls);
        /** The message box. */
        this.el.messageBox = element.find(D.sel.graphMessages);

        /* This should actually put a placeholder there until the
         * data is valid. */
        this.showMessage(D.messages.newGraph);

        this.graph = new Graph(this.el.graph, function (newRequest) {
            self.onGranularityChange(newRequest);
        });

        /* Currently unused. */
        this.hidden = false;
        this.minified = false;

        /* Bind events, like on control change. */
        this._bindOnChange();
        this._bindVisibilityControls();

        /* Have spiffy menus for graph controls. */
        this.el.controls.spiffy();
    }



    /*
     * Utility functions, used throughout the file.
     */

    /** Wrapper. Calls event.preventDefault() for the given event handler. */
    prevDef = function (wrappedFunction) {
        return function (event) {
            event.preventDefault();
            return wrappedFunction.apply(this, arguments);
        };
    };

    /**
     * Updates the built-in Date class with a method that returns the
     * date as an ISO "short" string -- that is, YYYY-mm-dd.
     */
    Date.prototype.toShortISOString = function () {
        return this.getFullYear() + '-' +
            (this.getMonth() + 1) + '-' +
            this.getDate();
    };

    Date.prototype.toModifiedISOString = function () {
        return this.toShortISOString() + ' ' +
            this.getHours();
    };



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
            fetchAxes(this.element, this.values), // Get the axes info.
            fetchApartments(this.element),
            fetchDateTime(this.element)
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
     * Common methods.
     */

    /**
     * Destroy this GraphControl. Notifies the manager that it has been
     * destroyed.
     */
    GraphControl.prototype.destroy = function () {
        /* Tell our manager to stop tracking us. */
        this.manager.untrack(this.id);

        this.element.remove();

        /* And now wait for the garbage collector to pick us up... */
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
        var self = this,
            processControls,
            internalControls;

        /* Get all of the control categories explicitly labled to send stuff to
         * process.php. */
        processControls = this.el.controls.find('[data-for-process]');
        internalControls = this.el.controls.find('[data-for-internal]');

        /* Bind all of these to invoke the sender. */
        processControls.find('input, select').change(function () {
            var updatedRequest = self.getQuery();

            // TODO: query validation before sending...

            self.makeRequest(updatedRequest);
            // Show a message indicating loading.
            self.showMessage(D.messages.graphLoading);

        });

        /* Internal controls should just use the current data and update the
         * graph. */
        internalControls.find('input, select').change(function () {
            self.onNewData();
        });

    };

    /** Politely asks the manager to fetch new data for us. */
    GraphControl.prototype.makeRequest = function (newData) {
        this.manager.makeRequest(this, newData);
    };

    /**
     * Should be called (probably by the GraphManager) when new
     * plotable data arrives.
     *
     * newData is an optional parameter: If given, will be used as the
     * new data value for the control; If left undefined, it will use
     * the currently tracked data.
     */
    GraphControl.prototype.onNewData = function  (newData) {

        if (newData === undefined) {
            /* Use the last saved data. */
            newData = _.clone(this.data);
        } else {
            /* OR save the given data. */
            this.data = newData;
        }

        newData.graphType = this.getGraphType();

        if (_(newData).has('values')) {
            /* Send the data to the graph, unchanged (with the exception of the
             * graph type!) */
            this.graph.update(newData);
            this.hideMessage();
        } else if ( _(newData).has('messages')) {
            /* Show them the message spit out by the process script. */
            this.showMessage(newData.messages.join(','));
        } else {
            /* Search got an unusable response. Notify the user about this. */
            this.showMessage(D.messages.unusableResponse);
        }

        /* There probably was some crazy loading status message -- remove it. */
    };


    /* Should be called when there's an error in fetching the data. */
    GraphControl.prototype.onFetchError = function () {
        /* Show a generic message. */
        this.showMessage(D.messages.errorFetchingInfo);
    };

    /*
     * Additional event bindings.
     */

    /** Binds the hide, show, destroy buttons to do the right thing. */
    GraphControl.prototype._bindVisibilityControls = function () {
        var visControls = this.el.visControls, self = this;

        visControls.find(D.sel.graphDestroyButton).click(prevDef(function () {
            self.destroy();
        }));

    };

    /*
     * Messaging.
     */

    /** Shows a message to the user. */
    GraphControl.prototype.showMessage = function (message) {
        var box = this.el.messageBox;

        box.text(message);
        box.show('fast');

    };

    GraphControl.prototype.hideMessage = function () {
        var box = this.el.messageBox;

        box.hide('fast');
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
        bindSelectEvents(rendered);


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

    /* Mini-module for date picker stuff. */
    jdPickerDefaultsWith = (function () {
        var defaults = {
            date_format: "YYYY-mm-dd",
            /* Start the week on Sunday. */
            start_of_week: 0,
            /* Set the max to the today. */
            date_max: (new Date()).toShortISOString()
        };

        return function (extras) {
            var newSettings = _.clone(defaults);
            return _.extend(newSettings, extras);
        };
    }());

    /**
     * Graph controller is a jQuery which we can bind events to.
     * Yay!
     */
    bindWeirdDateEvents = function (graphController) {
        var dateThing, granularityChooser, hideAll, onChange;

        /* Get the date div and the drop down that will find the proper
         * granularity. */
        dateThing = graphController.find('.graph-controls-datetime').first();
        granularityChooser = dateThing.find('[name=granularity]');

        /* Make all of the jdPicker things. */
        dateThing.find('.simple-day-picker').jdPicker(jdPickerDefaultsWith({}));
        dateThing.find('.week-picker').jdPicker(jdPickerDefaultsWith({
            select_week: true
        }));


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

    /** Binds select all. Doesn't really work yet. */
    bindSelectEvents = function (element) {
        var selectAll = element.find('.select-all'),
            selectNone = element.find('.select-none'),
            boundary,
            checkboxes,
            setter;

        /* Crawl up the tree to find the select boundary. */
        boundary = element.find('[data-select-boundary]');
        checkboxes = boundary.find('input[type=checkbox]');

        /* Works both for check all and check none. */
        setter = function (setting) {
            return prevDef(function () {
                checkboxes.prop('checked', setting).trigger('change');
            });
        };

        selectAll.click(setter(true));
        selectNone.click(setter(false));

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
            // process.php expects a single value, not an array.
            partialQuery[v] = (valueTuple.type === 'time')
                ? valueTuple.values[0]
                : valueTuple.values;
            partialQuery[v + 'axis'] = (valueTuple.type === 'sensorarray')
                ? valueTuple.values
                : valueTuple.values[0];

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
                var startTime = chosenControls.find('input[name=start]').val(),
                    endTime;

                /* For this one, we want the next 24 hours of data (one day). */
                endTime = new Date(startTime);
                endTime.setHours(endTime.getHours() + 24);

                return {
                    start: startTime,
                    end: endTime.toModifiedISOString()
                };
            },
            Daily: function () {
                /* This is the only one for which these controls are actually
                 * applicable for... */
                return {
                    start: chosenControls.find('input[name=start]').val(),
                    end: chosenControls.find('input[name=end]').val()
                };
            },
            Weekly: function () {
                return {
                    start: chosenControls.find('input[name=start]').val(),
                    end: chosenControls.find('input[name=end]').val()
                };
            },
            Monthly: function () {
                return {
                    start: chosenControls.find('input[name=start]').val(),
                    end: chosenControls.find('input[name=end]').val()
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
