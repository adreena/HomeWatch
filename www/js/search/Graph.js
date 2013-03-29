/**
 * Graph class skeleton.
 *
 * Manages the rendering of data as graphs.
 */

requirejs.config({

    /* Have to explicitly specify dependencies of flot and its plugins. */
    shim: {
        'flot': { deps: ['jquery'] },
        'flot-orderbars': { deps: ['flot'] },
        'flot-axislabels': { deps: ['flot'] },
        'flot-time': { deps: ['flot'] },
        'flot-navigate':  { deps: ['flot'] },
        'flot-pie':  { deps: ['flot'] },
    },

    paths: {
        'flot': 'flot/jquery.flot',
        'flot-orderbars': 'flot-orderbars/jquery.flot.orderBars',
        'flot-axislabels': 'flot-axislabels/jquery.flot.axislabels',
        'flot-time': 'flot/jquery.flot.time',
        'flot-navigate': 'flot/jquery.flot.navigate',
        'flot-pie': 'flot/jquery.flot.pie'
   }

});

define([
    'jquery',
    'underscore',
    'utils/getInternetExplorerVersion',
    'flot',
    'flot-orderbars',
    'flot-axislabels',
    'flot-time',
    'flot-navigate',
    'flot-pie'],

function ($, _, getInternetExplorerVersion) {

    // Use Excanvas if on inferior browsers.
    if (getInternetExplorerVersion() <= 8.0) {
        require("flot/excanvas.min.js");
    }

    /**
     * Constructor for a graph.
     */
    function Graph(element, _clickCallback, initialData) {

	// object to keep graph state for a particular instance
	this.graphState =
	{
	    callback: _clickCallback,
     	    element: element,
	    startdate: null,
	    enddate: null,
	    min_x: null,
	    max_x: null,
            graphType: "bar",
            granularity: "Hourly",
            xtype: "time",
            ytype: null,
	};

        //this.clickCallback = _clickCallback;
        //this.element = element;
        //this.graphType = initialData.graphType;

        // Initial update of the graph, only if the intial data exists.
        if (initialData !== undefined) {

            // Add all of these to the graphState object
            $.extend(this.graphState, {
                graphType: initialData.graphType,
                granularity: initialData.granularity,
                xtype: initialData.xaxis,
                ytype: initialData.yaxis,
            });

            this.update(initialData.values);

        }
    }

    /** Update method. Provide new data to update the graph. */
    Graph.prototype.update = function (graphData) {
        // Actually graphs the data!

        // Note that graphData contains the plotable data
        // AND the graphType!

	var graphState = this.graphState;
        // Merge the new parameters to the current graph state.
        //$.extend(graphState, graphData);
        // Should manually merge things from the graphData object.


	var graphType = graphState.graphType;
	var element = graphState.element;
	var granularity = graphState.granularity;

	console.log("granularity is: " + granularity);
	console.log("graphtypes is: " + graphType);

        // test for graphtypes
	if(graphType === "plainText") {
	    displayText(graphState, graphData);
	} else {
            var data_and_opts = format_data(graphState, graphData);
	    var data = data_and_opts["data"];
	    var options = data_and_opts["options"];

	    $.plot($(element), data, options);

	    if(granularity !== "Hourly") {
	        this.bind_plotclick();
	    }

	    bind_plothover(element);
        }
    };

    var displayText = function (graphState, graphData) {
        var display_text = "";
	var xtype = graphState.xtype;
	var element = graphState.element;

	$.each(graphData, function(key, value) {
            display_text += "<h2><i>Apartment " + key + ": </i></h2>";

	    $.each(value, function(key, value) {
		display_text += "<h4>" + "Date: " + key + "</h4>";

		$.each(value, function(key, value) {
		    if(xtype === "time") {
			display_text += "Sensor " + key + ": " + value.y + "<br />";
		    } else {
			display_text += "Sensor " + key + " against " + xtype + ": " + value.y + " against " + value.x + "<br />";"<br />";
		    }
             	});
	    });
	});

        $(element).html(display_text);
    };

    /*
    * Parses the data retrieved from the server, into something
    * usable by Flot.
    */
    var format_data = function (graphState, graphData) {
	var sensor_data = [];
	var series_data = [];
	var data_and_options = [];
	var graphname = [];
	var apartments = [];
	var graphname_flag = "false";
	var min_x, max_x;
	var apartment, sensor, timestamp, tick_size;
	var startdate, enddate;

        $.each(graphData, function (key, value) {
            apartment = key;
            apartments.push(apartment);
            console.assert(sensor_data[apartment] === undefined);
            sensor_data[apartment] = [];

            $.each(value, function (key, value) {
		// key = date stamp
		time_stamp = DateToUTC(key);

		if(startdate === undefined) {
		    startdate = time_stamp;
		    enddate = time_stamp;
		}

		if(time_stamp > enddate) {
		    enddate = time_stamp;
		}

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

                    if(graphState.xtype === "time") {
			sensor_data[apartment][sensor].push([time_stamp, value.y]);
			//tuple[0] = time_stamp;
                    	//tuple[1] = value.y;

		    } else {
			if(value.x) {
			    tick_size = parseFloat(value.x);
			    sensor_data[apartment][sensor].push([tick_size, value.y]);
			
                    	    //tuple[1] = value.y;

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

		    //console.log("y value is: " + tuple[1]);
                    //sensor_data[apartment][sensor].push(tuple);
                });
            });
        });

	graphState.startdate = startdate;
	graphState.enddate = enddate;
	graphState.min_x = min_x;
	graphState.max_x = max_x;

	console.log("start date is: " + graphState.startdate);
	console.log("end date is: " + graphState.enddate);
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

        var options = set_all_options(graphState);
	data_and_options["data"] = series_data;
	data_and_options["options"] = options;
	return data_and_options;
    };

    var set_all_options = function (graphState) {
	var x_axis = get_x_axis(graphState);
	var y_axis = get_y_axis(graphState);
	var grid = get_grid();
	var series_opts = get_series_options(graphState);
	var legend = get_legend();

	if(graphState.granularity === "Hourly") {
	    var zoom = get_zoom_options();
	} else {
	    var zoom = {};
	}

	var options = $.extend({}, x_axis, y_axis, grid, series_opts, legend, zoom);
	return options;
    };

    var get_x_axis = function (graphState) {
	var granularity = graphState.granularity;
	var xtype = graphState.xtype;
	var startdate = graphState.startdate;
	var enddate = graphState.enddate;
	var min_x = graphState.min_x;
	var max_x = graphState.max_x;
	var min_date = new Date(startdate);

        var base_x = {
	    xaxis:
		{
		  axisLabelUseCanvas: true,
		  axisLabelFontSizePixels: 12,
                  axisLabelFontFamily: 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
		  axisLabelPadding: 5,
		  autoscaleMargin: .50
		}
	};

	
console.log("start date is : " + base_x.xaxis["min"]);

	if(xtype === "time") {
	    base_x.xaxis["mode"] = "time";
	     base_x.xaxis["min"] = startdate;

            if(granularity === "Hourly") {
		base_x.xaxis["max"] = startdate + get_millisecond_interval("day");
	        base_x.xaxis["tickSize"] = [1, "hour"];
	        var label = get_month_and_day(min_date);
	        base_x.xaxis["axisLabel"] = label;
            } else if(granularity === "Daily") {
		base_x.xaxis["max"] = startdate + get_millisecond_interval("week");
		console.log("max is : " + base_x.xaxis["max"]);
		base_x.xaxis["timeformat"] = "%a %d";
		base_x.xaxis["tickSize"] = [1, "day"];
		base_x.xaxis["dayNames"] = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];
		base_x.xaxis["axisLabel"] = get_month_and_day(min_date) + " - " + get_month_and_day(base_x.xaxis["max"]);
            } else if (granularity === "Weekly") {
		base_x.xaxis["tickSize"] = [1, "week"];
		base_x.xaxis["weekNames"] = ["1", "2", "3", "4", "5"];
            } else if(granularity === "Monthly") {
		base_x.xaxis["timeformat"] = "%b";
		base_x.xaxis["tickSize"] = [1, "month"];
		base_x.xaxis["monthNames"] = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
		var label = min_date.getUTCFullYear();
		base_x.xaxis["axisLabel"] = 'Year: ' + label;
		var year_end = label + "-12-31:0";
		base_x.xaxis["max"] = DateToUTC(year_end);
	    } else {
		// multiple years?
	    }
	} else {
	    base_x.xaxis["min"] = min_x;
	    base_x.xaxis["max"] = max_x;
	    base_x.xaxis["axisLabel"] = xtype;
	}

	if(granularity === "Hourly") {
	    base_x.xaxis["zoomRange"] = [0.1, 3600000];
	    var pan_range = max_x * 1.5;
	    base_x.xaxis["panRange"] = [-100, pan_range];
	}

	return base_x;
    };

    var get_y_axis = function (graphState) {
        var base_y = {
            yaxis:
		{
                  axisLabelUseCanvas: true,
                  axisLabelFontSizePixels: 12,
                  axisLabelFontFamily: 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
                  axisLabelPadding: 5
                }
            };

        base_y.yaxis["axisLabel"] = graphState.ytype;

	if(graphState.granularity === "Hourly") {
	    base_y.yaxis["zoomRange"] = [0.1, 3600000];
	    base_y.yaxis["panRange"] = [-100, 1000];
	}

        return base_y;
    };

    var get_grid = function () {
	return base_grid = {grid: {hoverable: true, clickable: true, borderWidth: 3, labelMargin: 3}};
    };

    var get_series_options = function (graphState, order) {
	var graphtype = graphState.graphtype;

	var line = {series: {lines: {show: true}, points: {radius: 3, show: true, fill: true }}};
	var bars = {series: {bars: { show: true, barWidth: 1000*60*60*0.25, fill: true, lineWidth: 1, clickable: true,
    			hoverable: true, order: order}}};
	var pie =  {series: {pie: {show: true, radius: 1}}};

	if(graphtype === "line") {
	    return line;
	} else if(graphtype === "histo") {
	    return bars;
	} else if(graphtype === "pie") {
	    return pie;
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

    var show_tool_tip = function (x, y, contents) {

        $('<div id="tooltip">' + contents + '</div>').css( {
            position: 'absolute',
            display: 'none',
            top: y + 20,
            left: x -25,
            border: '1px solid #fdd',
            padding: '2px',
            'background-color': '#fee',
            opacity: 0.80
        }).appendTo("body").fadeIn(200);
    };

    bind_plothover = function (element) {
        var previousPoint = null;

        $(element).bind("plothover", function (event, pos, item) {
            $("#x").text(pos.x.toFixed(2));
            $("#y").text(pos.y.toFixed(2));

            if (item) {
                if (previousPoint != item.dataIndex) {
                    previousPoint = item.dataIndex;

                    $("#tooltip").remove();
                        var x = new Date(item.datapoint[0]);
			x = get_month_and_day(x);
                        y = item.datapoint[1].toFixed(2);

                        show_tool_tip(item.pageX, item.pageY,
                                item.series.label + " on " + x + " is " + y);
                }
            } else {
                $("#tooltip").remove();
                previousPoint = null;
            }
        });
    };

    Graph.prototype.bind_plotclick = function() {
	var drill_granularity;
	var date_from;
	var date_to;
	var granularity = this.graphState.granularity;
        var handleChangedData = this.graphState.callback;

    	$(this.graphState.element).bind("plotclick", function (event, pos, item) {
            if (item) {
		console.log("granularity is : " + granularity);
		console.log("you clicked!");
	        var offset = (new Date(item.datapoint[0])).getTimezoneOffset()*60*1000;
	        var data_pointUTC = item.datapoint[0] + offset;
	        var date = new Date(data_pointUTC);
	        date_from = format_date(date, "true");

		if(granularity === "Hourly") {
		    // cannot drill down further
		    return;
		} else if (granularity === "Daily") {
		   drill_granularity = "Hourly";
		   date_to = date_from;
		} else if (granularity === "Weekly") {
		    drill_granularity = "Daily";
		    date_to = get_date_to(data_pointUTC, drill_granularity);
		} else if(granularity === "Monthly") {
		    drill_granularity = "Weekly";
		    date_to = get_date_to(data_pointUTC, drill_granularity);
		}

                /* Tell whatever handler we've got that there's new data. */
                handleChangedData({
                    startdate: date_from,
                    enddate: date_to,
                    period: drill_granularity
                });
       	    } // if statement
	}); // end plotclick
    };


    /*
     * UTILITIES!
     */

    /* Converts a date in YYYY-MM-DD:hh format into milliseconds since the
     * UNIX epoch. Assumes everything is using the same timezone.  If the
     * input cannot be parsed, returns undefined.
     */

    DateToUTC = function (dateString) {
        var dateRegex = /(\d+)-(\d+)-(\d+)(?::(\d+))?/,
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
           // Hour may not be present; use 0 in this case.
           m[4] ? m[4] : 0);

        return UTCTime;
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

    var get_month_and_day = function (date) {
        var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Nov", "Dec"];
	return months[date.getUTCMonth()] + ' ' + date.getUTCDate() + ' ' + date.getUTCFullYear();
    };

    var get_millisecond_interval = function (interval) {
	var base = 3600000;

	if(interval === "day") {
	    return base * 23;
	} else if(interval === "week") {
	    return base * 24 * 6;
	}
    };


    /* This module exports one public member -- the class itself. */
    return Graph;

});
