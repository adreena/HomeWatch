require([
    'jquery',                   // Using the require+jquery combo
    'underscore',               // Underscore
    'jSearch/defines',          // Definitions for use with jSearch
    'flot/jquery.flot',         // Flot charts
    'flot/jquery.flot.time',    // Flot time plugin
    'flot-axislabels/jquery.flot.axislabels', // Extra flot plugins
    'flot-orderbars/jquery.flot.orderBars'],

    // needed additions
    //flot/jquery.flot.navigate.js
    // flot-axislabels/jquery.flot.axislabels this needs a patch

    /*
     * jSearch.js
     *
     * Sets up stuff on the mangineer search page.
     *
     * TODO: Consider stuffing some of these things in modules,
     * and compiling stuff with RequireJS.
     *
     */

    function ($, _, defs) {

        // For some stupid reason, JSLint requires
        // all var statements appear at the top of the file.
        // See: http://www.jslint.com/lint.html#scope
        var searchMod,

            /* Global Variables. */
            searchCache = {}, // Cache for requests. We don't have to make more requests than necessary.
            searchURI = defs.uri.controller,

            /* Then some local functions. */
            onLoad,
            bindMenus,
            setupDateStuff,
            bindSearchForm,
            onSearch, // TODO: should probably use _.debounce to do delayed updates (after input).
            //onAjaxSuccess, // TODO: this needs to do some crazy closure magic.

            updateDisplay,
            displayText,

            // Heruuuguuhhghhghu
            showHelpfulError,
            printSadMessage;

        // Uncomment this to use the mockdata instead of the actual contoller.
        searchURI = defs.uri.mockdata;


        // This is called when everything is done loading.
        onLoad = function () {
            setupDateStuff();
            bindMenus();
            bindSearchForm();
        };


        // Binds the search category menus.
        bindMenus = function () {
            // Sad, sad day.
        };


        // Binds the datepicker inside the search form.
        // Eddie's going to do some crazy stuff in here:
        setupDateStuff = function () {

            // TODO: Context sensitive date controls.

            // Here too...

        };

        // Binds stuff in the search form, including the form itself.
        bindSearchForm = function () {

            // Eddie: consider using a universal check-all'er
            // Binds the check-all stuff.
            $('.checkAllSensors').change(function () {
                if (this.checked) {
                    $('.sensor-group1 :checkbox, sensor-group2 :checkbox').prop(
                        'checked', this.checked);
                    $('.sensor-group2 :checkbox').prop('checked', this.checked);
                } else {
                    $('.sensor-group1 :checkbox').prop('checked', false);
                    $('.sensor-group2 :checkbox').prop('checked', this.checked);
                }
            });

            // Binds the check all in the appartment group...
            $('.allApts').change(function () {
                if (this.checked) {
                    $('.apartment-group :checkbox').prop('checked', this.checked);
                } else {
                    $('.apartment-group :checkbox').prop('checked', false);
                }
            });

            // TODO: Gah, code duplication! --^

            $("#submitbutton").click(function (evt) {
                // TODO: Make this selector more robust (e.g., it will
                // break if we add another form to the page).
                var data = $(defs.sel.searchBox).serialize();

                onSearch(data);

                return false;
            }); // end submit on click

        };

        // onSearch takes in data from some form and sends it
        // to process.php; it is then displayed by updateDisplay().
        //
        // If data is not explicitly provided or is undefined, this
        // serializes the form on the page.
        //
        // If the form data is in a cache, onSearch does not bother
        // making an HTTP request, instead displaying the values from
        // the cache.
        onSearch = function (data, forceAJAX) {
            var cachedResult;

            // If data was not specified, get it from a form.
            if (data === undefined) {
                data = $(SEL_SEARCH).serialize();
            }

            // If forceAJAX was not provided, assume it is false.
            if (forceAJAX === undefined) {
                forceAJAX = false;
            }

            // Find the request in the cache first,
            if (!forceAJAX && searchCache[data] !== undefined) {

                cachedResult = searchCache[data];
                // setTimeout to run this function outside of the
                // current call stack to emulate how jQuery's success
                // callback would be called.
                setTimeout(function () {
                    updateDisplay(cachedResult);
                }, 0);

                // DEBUG:
                console.log({
                    brief: 'Request loaded from cache',
                    request: data,
                    inCache: cachedResult
                });

                return;
            }

            // Else, do the AJAX request to fetch the information.

            $.ajax({
                url: searchURI,
                type: 'GET',
                data: data,
                cache: false,
                dataType: 'json',
                success: function (result) {
                    // This request was successful; add it to the cache.
                    searchCache[data] = result;

                    // Debug print.
                    console.log({
                        brief: 'Caching request result',
                        request: data,
                        cache: searchCache
                    });

                    updateDisplay(result);
                },
                error: showHelpfulError
            });
        };

        showHelpfulError = function () {
            printSadMessage('The server is not responding in a decent way. '
                + 'Perhaps it\'s a lack of waffles. Sorry. :/');
        };

        printSadMessage = function (message) {
            // Empty the results div and put a sad little message specifying
            // the state of the server.
            $(defs.sel.resultBox)
                .empty()
                .append(
                    $('<h1>').text("Search error")
                )
                .append(
                    $('<p>').text(message)
                );
        };

        /**
         * Gets called when the display must be updated with new data.
         * Handles text views and plots.
         */
        updateDisplay = function (result) {

            // The model places stuff in 'message' when bad stuff happens.
            if (result.message !== undefined) {
                printSadMessage(result.message);
                return;
            }

            var selectedValue = "",
                // Get the selected display type.
                selected = $("#graphs input[type='radio']:checked");

            // Try to get the display type from the form, or
            // use text view as the default.
            if (selected.length > 0) {
                selectedValue = selected.val();
            } else {
                selectedValue = 'plainText';
            }

            if (selectedValue === "plainText") {
                displayText(result);
            } else {
                render_graph(selectedValue, result);
            }

        };


        /*
         * Displays the result stuff as a table.
         */
        displayText = function (result) {
            var display_text = "",
                apartments = result.data;

            // For each apartment...
            _.each(apartments, function (readings, apartment) {
                var granularity = result.query.granularity,
                    period = {
                        Hourly: 'Hour',
                        Daily: 'Day',
                        Weekly: 'Week',
                        Monthly: 'Month',
                        Yearly: 'Year',
                    }[granularity];

                display_text += "<h2><i>Apartment " + apartment + ": </i></h2>";

                // For each date...
                _.each(readings, function (sensor_data, date) {
                    display_text += "<h4>" + date + "</h4>";


                    // For each sensor, display each actual value.
                    _.each(sensor_data, function (data, sensor) {
                        if (_.isArray(data)) {
                            display_text += sensor + ": <br />";
                            _.each(data, function (sensorValue, i) {
                                display_text += period + " " + i + ": " + sensorValue +
                                    "<br />";
                            });
                            display_text += "<br />";
                        } else {
                            display_text += sensor + ": " + data +
                                "<br /> <br />";
                        }
                    });
                });
            });

            // Clear the results div and add the text display to it.
            $('<div>')
                .attr('class', 'text-display')
                .html(display_text)
                .appendTo(
                    $("#results").empty()
                );
        };

        var render_graph = function (graphtype, result, element, callback) {
         console.log("granularity is " + result.granularity);

        // test for graphtypes
	if(graphtype === "plainText") {
	    displayText(result)
	} else {
	    var meta_data = create_metadata_object(graphtype, result);
	    var results = result["values"]
            var data_and_opts = format_data(meta_data, results);
	    var data = data_and_opts["data"];
	    var options = data_and_opts["options"];

	    // element needs to be substituted here
	    $.plot($(".graph1"), data, options);

	    if(meta_data.granularity !== "Hourly") {
	        bind_plotclick(meta_data.granularity);
	    }
        }
    };

    var create_metadata_object = function (graphtype, result) {
	return 	{
                graphtype: graphtype,
                granularity: result.granularity,
		xtype: result["x-axis"],
		ytype: result["y-axis"],
		millisecond_hour: 3600000,
		millisecond_day: 86400000,
            	}
    };

    	/*
         * Parses the data retrieved from the server, into something 
         * usable by Flot.
         */
    var format_data = function (meta_data, result) {
	var sensor_data = [];
	var series_data = [];
	var data_and_options = [];
	var graphname = [];
	var apartments = [];
	var graphname_flag = "false";
	var min_x, max_x;
	var apartment, sensor, timestamp;
	
        $.each(result, function (key, value) {
            apartment = key;
            apartments.push(apartment);
            console.assert(sensor_data[apartment] === undefined);
            sensor_data[apartment] = [];

            $.each(value, function (key, value) {
		// key = date stamp                   
		time_stamp = DateToUTC(key);

                if (graphname.length !== 0) {
                    graphname_flag = "true";
                }

                $.each(value, function (key, value) {
                    // key = sensor names
                    sensor = key;

                    if (graphname_flag === "false" && sensor !== "time") {
			graphname.push(sensor);			  
                    }

                    if (sensor_data[apartment][sensor] === undefined) {
                        sensor_data[apartment][sensor] = [];
                    }

                    if (series_data.length === 0) {
                        tuple = [];
                    } else {
                        tuple.length = 0;
                    }

                    if(meta_data.xtype === "time") {
		    	if(min_x === undefined) {
			    min_x = time_stamp;
			    max_x = min_x;
		        }
				
		        if(time_stamp >= max_x) {
                            if(meta_data.granularity === "Hourly") {
		                max_x = time_stamp + meta_data.millisecond_day;
		            } else {
			        max_x = time_stamp;
			    }
		        }

                        var tick_size = time_stamp;
		    } else {
			if(value["x"]) {
			    var tick_size = parseFloat(value["x"]);

			    if(min_x === undefined || min_x > tick_size) {
				min_x = tick_size;
			    }

			    if(max_x === undefined || max_x < tick_size) {
				max_x = tick_size;
			    }

			} else {
                            console.log({
                                msg: "Got null value in sensor reading!",
                                apt: apartment,
                                date: time_stamp,
                                sensor: sensor
                            });
			}
		    }                              

                    tuple[0] = tick_size;
                    tuple[1] = value["y"];
                    sensor_data[apartment][sensor].push(tuple);
                });
            });
        });

	meta_data["min_x"] = min_x;
	meta_data["max_x"] = max_x;

        for(var i = 0; i < apartments.length; ++i) {
	    for(var j = 0; j < graphname.length; ++j) {
		var label = "Apartment " + apartments[i] + " " + graphname[j];
		series_length = series_data.length;
		if(series_length === 0) {
		    series_data[0] = create_series_object(label, sensor_data[apartments[i]][graphname[j]]);
			
		} else {
		    series_data[series_length] = create_series_object(label, sensor_data[apartments[i]][graphname[j]]);
		}
	    }
	}


        var options = set_all_options(meta_data);
	data_and_options["data"] = series_data;
	data_and_options["options"] = options;
	return data_and_options;
    };

    var set_all_options = function (meta_data) {
	var x_axis = get_x_axis(meta_data);
	var y_axis = get_y_axis(meta_data);
	var grid = get_grid();
	var series_opts = get_series_options(meta_data);
	var legend = get_legend();

	if(meta_data.granularity === "Hourly") {
	    var zoom = get_zoom_options();	
	} else {
	    var zoom = {};
	}

	var options = $.extend({}, x_axis, y_axis, grid, series_opts, legend, zoom);
	return options;
    };

    var get_x_axis = function(meta_data) {
	var granularity = meta_data.granularity;
	var xtype = meta_data.xtype;
	var min_x = meta_data.min_x;
	var max_x = meta_data.max_x;
	var min_date = new Date(min_x);
	var max_date = new Date(max_x);
        var base_x = {
	    xaxis: 	
		{ 
		  axisLabelUseCanvas: true, axisLabelFontSizePixels: 12,
                  axisLabelFontFamily: 'Verdana, Arial, Helvetica, Tahoma, sans-serif', axisLabelPadding: 5,
		  autoscaleMargin: .50
		}		 
	};

	base_x.xaxis["min"] = min_x;
	base_x.xaxis["max"] = max_x;

	if(xtype === "time") {
	    base_x.xaxis["mode"] = "time";

            if(granularity === "Hourly") {
	        base_x.xaxis["tickSize"] = [1, "hour"];
	        var label = min_date.getUTCDay();
	        base_x.xaxis["axisLabel"] = label;
            } else if(granularity === "Daily") {
		base_x.xaxis["timeformat"] = "%a %d";
		base_x.xaxis["tickSize"] = [1, "day"];
		base_x.xaxis["dayNames"] = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];
		base_x.xaxis["axisLabel"] = min_date.getUTCMonth() + ' ' + min_date.getUTCDay() + "-" + 
						max_date.getUTCMonth() + ' ' + max_date.getUTCDay();
            } else if (granularity === "Weekly") {
		base_x.xaxis["tickSize"] = [1, "week"];
		base_x.xaxis["weekNames"] = ["1", "2", "3", "4", "5"];
            } else if(granularity === "Monthly") {
		base_x.xaxis["timeformat"] = "%b";
		base_x.xaxis["tickSize"] = [1, "month"];
		base_x.xaxis["monthNames"] = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
		var label = min_date.getUTCFullYear(); 
		base_x.xaxis["axisLabel"] = 'Year: ' + label;
	    }
	} else {
	     base_x.xaxis["axisLabel"] = xtype;
	}

	if(granularity === "Hourly") {
	    base_x.xaxis["zoomRange"] = [0.1, 3600000];
	    var pan_range = max_x * 1.5;
	    base_x.xaxis["panRange"] = [-100, pan_range];
	}

	return base_x;	    
    };

    var get_y_axis = function (meta_data) {
        var base_y = {
            yaxis: 
		{
                  axisLabelUseCanvas: true,
                  axisLabelFontSizePixels: 12,
                  axisLabelFontFamily: 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
                  axisLabelPadding: 5
                }
            };

        base_y.yaxis["axisLabel"] = meta_data.ytype;

	if(meta_data.granularity === "Hourly") {
	    base_y.yaxis["zoomRange"] = [0.1, 3600000];
	    base_y.yaxis["panRange"] = [-100, 1000];
	}

        return base_y;
    };

    var get_grid = function () {
	return base_grid = {grid: {hoverable: true, clickable: true, borderWidth: 3, labelMargin: 3}};     
    };

    var get_series_options = function (meta_data, order) {
	var graphtype = meta_data.graphtype;

	var line = {series: {lines: {show: true}, points: {radius: 3, show: true, fill: true }}};
	var bars = {series: {bars: { show: true, barWidth: 1000*60*60*0.25, fill: true, lineWidth: 1, clickable: true,
    			hoverable: true, order: order}}};

	if(graphtype === "line") {
	    return line;
	} else if(graphtype === "histo") {
	    return bars;
	}
    };

    var get_legend = function () {
        return {
	    legend: 
		{
    		  show: true,
		  labelBoxBorderColor: "rgb(51, 204, 204)",
		  backgroundColor: "rgb(255, 255, 204)",
    		  margin: [10, 300],
    		  backgroundOpacity: .75
  		}
        }
    };

    var get_zoom_options = function () {
        return {
            zoom: 
		{
                interactive: true
            	},

            pan: 
		{
                interactive: true
            	}
	}
    };

    var create_series_object = function (label, data) {
        return {
                 label: label,
                 data: data,
               }
        };

    var bind_plotclick = function (granularity) {
        var drill_granularity;
        var date_from;
        var date_to;
        var data = $(defs.sel.searchForm).serialize();

        $("#graph1").bind("plotclick", function (event, pos, item) {
            if (item) {
                //var offset = (new Date(item.datapoint[0])).getTimezoneOffset() * 60 * 1000;
                var data_pointUTC = item.datapoint[0] + offset;
                var date = new Date(data_pointUTC);
                date_from = format_date(date, "true");

                if (granularity === "Hourly") {
                    // cannot drill down further
                    return;
                } else if (granularity === "Daily") {
                    drill_granularity = "Hourly";
                    date_to = date_from;
                } else if (granularity === "Weekly") {
                    drill_granularity = "Daily";
                    date_to = get_date_to(data_pointUTC, drill_granularity);
                } else if (granularity === "Monthly") {
                    drill_granularity = "Weekly";
                    date_to = get_date_to(data_pointUTC, drill_granularity);
                }

                data = data.replace(/(granularity=)([a-zA-Z]+)/, '$1' + drill_granularity);
                data = data.replace(/(from=)([0-9][0-9]%2F[0-9][0-9]%2F[0-9][0-9][0-9][0-9])/, '$1' + encodeURIComponent(date_from));
                data = data.replace(/(to=)([0-9][0-9]%2F[0-9][0-9]%2F[0-9][0-9][0-9][0-9])/, '$1' + encodeURIComponent(date_to));

                onSearch(data, true);
            } // if statement
        }); // end plotclick
    };

    var get_days_in_month = function (month, year) {
        month = parseInt(month);
        year = parseInt(year);
        return (32 - new Date(year, month, 32).getDate());
    };

    var get_date_to = function (date, drill_granularity) {
        var millisecond_day = 86400000;
        var millisecond_week = 6 * millisecond_day;

        if (drill_granularity === "Daily") {
            var date_to = date + millisecond_week;
            date_to = new Date(date_to);
            return date_to = format_date(date_to, true);
        }

        if (drill_granularity === "Weekly") {
            var temp_date = new Date(date);
            var month = temp_date.getUTCMonth();
            var year = temp_date.getUTCFullYear();
            var num_days = get_days_in_month(month, year);
            date_to = date + (num_days - 1) * millisecond_day;
            date_to = new Date(date_to);
            return date_to = format_date(date_to, true);
        }
    };

    var format_date = function (date, bool) {
        if (bool === "false") {
            return (date.getUTCMonth() + 1) + '/' + date.getUTCFullYear();
        } else {
            return add_leading_zero(date.getUTCMonth() + 1) + '/' + add_leading_zero(date.getUTCDate()) + '/' + date.getUTCFullYear();
        }
    };

    var add_leading_zero = function (date) {
        return date < 10 ? '0' + date : '' + date;
    };


        /*
         * UTILITIES!
         */

        /* Converts a date in YYYY-MM-DD:hh format into milliseconds since the
         * UNIX epoch. Assumes everything is using the same timezone.  If the
         * input cannot be parsed, returns undefined.
         */
        DateToUTC = function (dateString) {
            var dateRegex = /(\d+)-(\d+)-(\d+):(\d+)/,
                m, // m for match
                UTCTime;

            m = dateString.match(dateRegex);

            // Return undefined if we could not match the regex.
            if (!m) {
                return;
            }

            UTCTime = Date.UTC(
                    m[1],     // Year
                    m[2] - 1, // Month (WHY IS THIS ZERO-INDEXED?!)
                    m[3],     // Day
                    m[4]);    // Hour

            return UTCTime;
        };

        /* Map, but on the keys of an object. Uses Underscore. */
        mapKeys = function (obj, keyFunc) {
            var func = function (value, key) {
                return [keyFunc(key), value];
            };

            return _
                .chain(obj)
                .map(func)
                .object()
                .value();
        };

        // TODO: Export this into its own underscore module?
        /* Uselessly creates an underscore extension to map something
         * to an object's keys. */
        _.mixin({ mapKeys: mapKeys });

        /* Preprocesses the data from process.php.
         *
         * For example:
         *  - converts Devin's date format (ISO 8601 with concatenated
         *    hour) to UTC time in milliseconds.
         *  - That's it...
         *
         * Returns the cleaned object.
         */
        preprocessData = function (data) {

            /* Assumes date is nested within apartment. */
            return _
                .chain(data)
                .map(function (apartment, number) {
                    var newApartment =
                        _.mapKeys(apartment, devinDateToUTC);

                    return [number, newApartment];
                })
                .object()
                .value();
        };



        /*
         * Things to happen on document ready.
         */



        // Setup the jQuery on document ready
        // to bind everything.
        $(onLoad);

        // If this were a module, we'd put the exports here:
        // return { export1: somefunc, ... };

    }
); // require
