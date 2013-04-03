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
    function Graph(element, _clickCallback, initialData, legend) {

        /*
	 * Object to keep state for a particular graph instance. Its meta-data
	 * includes its callback function (used for drilling down), its element (the div
	 * where the graph will be plotted), its graphtype (line, bar),
	 * its granularity (Hourly, Daily etc.) and its axes.
	*/
        this.graphState =
        {
            callback: _clickCallback,
            element: element,
	    legend: legend,
            startdate: null,
            enddate: null,
            min_x: null,
            max_x: null,
            graphType: null,
            granularity: null,
            xtype: null,
            ytype: null
        };
    };

    /** Update method. Provide new data to update the graph. */
    Graph.prototype.update = function (graphData) {

        /* 
         * Note that graphData contains the plotable data
         * AND the graphType! Add all of these to the 
         * graphState object
         */
            $.extend(this.graphState, {
                graphType: graphData.graphType,
                granularity: graphData.granularity,
                xtype: graphData.xaxis,
                ytype: graphData.yaxis + get_measurement_units(graphData.yaxis),
            });

        var graphState = this.graphState;
        var graphType = graphState.graphType;
        var element = graphState.element;
        var granularity = graphState.granularity;

	// send graph data to parser - expects data and graph options as return
        var data_and_opts = format_data(graphState, graphData.values);
	
        var data = data_and_opts["data"];
        var options = data_and_opts["options"];

	// Flot plot graph call
        $.plot($(element), data, options);

	// bind click event to non-hourly graphs to allow for drill down
        if(granularity !== "Hourly") {
            this.bind_plotclick();
        }
	
	// assign tool tip event to this graph instance
        this.bind_plothover();
    };

    /*
    * Parses the data retrieved from the server into an array of
    * [x,y] tuples for each discrete graph.
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
        var ticks = [];

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
                    ticks.push([time_stamp]);
                }

                if(time_stamp > enddate) {
                    enddate = time_stamp;
                    ticks.push([time_stamp]);
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

                    if((graphState.xtype).toLowerCase() === "time") {
                        sensor_data[apartment][sensor].push([time_stamp, value.y]);

                    } else {
                        if(value.x) {
                            tick_size = parseFloat(value.x);
                            sensor_data[apartment][sensor].push([tick_size, value.y]);

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
                });
            });
        });

	// ticks represents discrete time stamps encountered during parsing
	// and are used for setting x-axis ticks
        graphState.ticks = ticks;
        graphState.startdate = startdate;
        graphState.enddate = enddate;
        graphState.min_x = min_x;
        graphState.max_x = max_x;

	// graph series objects created here (label + data)
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

    /*
     * Umbrella function for setting up graphing parameters
     *
     */
    var set_all_options = function (graphState) {
        var x_axis = get_x_axis(graphState);
        var y_axis = get_y_axis(graphState);
        var grid = get_grid();
        var series_opts = get_series_options(graphState);
        var legend = get_legend(graphState);

	// only allow graph zooming (not to be confused with drill down)
	// on hourly correlational graphs
        if(graphState.granularity === "Hourly" && (graphState.xtype).toLowerCase() !== "time") {
            var zoom = get_zoom_options();
        } else {
            var zoom = {};
        }

        var options = $.extend({}, x_axis, y_axis, grid, series_opts, legend, zoom);
        return options;
    };

    /*
     * Creates x-axis ticks and label for graph, also sets zoom and 
     * pan range for this axis if appropriate
     */
    var get_x_axis = function (graphState) {
        var granularity = graphState.granularity;
        var xtype = graphState.xtype;
        var ticks_length = graphState.ticks_length;
        var ticks = graphState.ticks;
        var startdate = graphState.startdate;
        var enddate = graphState.enddate;
        var min_x = graphState.min_x;
        var max_x = graphState.max_x;
        var min_date = new Date(startdate);
        var max_date = new Date(enddate);
        var type = "axis_label";

        var base_x = {
            xaxis:
                {
                  axisLabelUseCanvas: true,
                  axisLabelFontSizePixels: 12,
                  axisLabelFontFamily: 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
                  axisLabelPadding: 25
                }
        };

        if(xtype.toLowerCase() === "time") {
            base_x.xaxis["mode"] = "time";
            base_x.xaxis["min"] = startdate;

            if(granularity === "Hourly") {
                base_x.xaxis["tickSize"] = [2, "hour"];
                console.log("start date is " + startdate);
                base_x.xaxis["axisLabel"] = get_month_day_year(startdate, type, granularity);
            } else if(granularity === "Daily") {
                base_x.xaxis["timeformat"] = "%a %d";
                base_x.xaxis["tickSize"] = [1, "day"];
                base_x.xaxis["dayNames"] = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
                base_x.xaxis["axisLabel"] = get_month_day_year(startdate, type, granularity, enddate);
            } else if (granularity === "Weekly") {
                base_x.xaxis["ticks"] = get_tick_labels(ticks, granularity);
                base_x.xaxis["axisLabel"] = get_month_day_year(startdate, type, granularity, enddate);
            } else if(granularity === "Monthly") {
                base_x.xaxis["timeformat"] = "%b";
                base_x.xaxis["tickSize"] = [1, "month"];
                base_x.xaxis["monthNames"] = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                base_x.xaxis["axisLabel"] = get_month_day_year(startdate, type, granularity, enddate);
            } else {
		// yearly granularity would slot in here
                base_x.xaxis["ticks"] = get_tick_labels(ticks, granularity);
            }
        } else {
	    // this is a correlational graph
            base_x.xaxis["min"] = min_x;
            base_x.xaxis["max"] = max_x;
            base_x.xaxis["axisLabel"] = xtype + get_measurement_units(xtype);
        }

	// set zoom and pan ranges
        if(granularity === "Hourly") {
            base_x.xaxis["zoomRange"] = [1, 10];
            var pan_range = max_x * 1.5;
            base_x.xaxis["panRange"] = [-100, pan_range];
        }

        return base_x;
    };

    /*
     * Creates y-axis ticks and label, also sets zoom and 
     * pan range for this axis if appropriate
     */
    var get_y_axis = function (graphState) {
        var base_y = {
            yaxis:
                {
                  axisLabelUseCanvas: true,
                  axisLabelFontSizePixels: 12,
                  axisLabelFontFamily: 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
                  axisLabelPadding: 25
                }
            };

        base_y.yaxis["axisLabel"] = graphState.ytype;

        if(graphState.granularity === "Hourly") {
            base_y.yaxis["zoomRange"] = [1, 10];
            base_y.yaxis["panRange"] = [-100, 2500];
        }

        return base_y;
    };

    /*
     * Set grid option for graph and make it interactive
     */
    var get_grid = function () {
        return base_grid = {grid: {hoverable: true, clickable: true, borderWidth: 3, labelMargin: 3}};
    };

    /*
     * Select the type of graph to be plotted
     */
    var get_series_options = function (graphState) {
        var graphType = graphState.graphType;
	var num_bars = graphState.numbars;
	var granularity = graphState.granularity;
	var order = [];

        var line = {series: {lines: {show: true}, points: {radius: 3, show: true, fill: true}}};
        var bars = {series: {bars: { show: true, fill: true, lineWidth: 1, clickable: true, hoverable: true}}};
        var pie =  {series: {pie: {show: true, radius: 1}}};

        if(graphType === "line") {
            return line;
        } else if(graphType === "bar") {
	    bars.series.bars.barWidth = get_bar_interval(granularity) * 0.75;
            return bars;
        } else if(graphType === "pie") {
            return pie;
        }
    };

    /*
     * Set options for graph legend
     */
    var get_legend = function (graphState) {
        return {
            legend:
            {
                show: true,
                labelBoxBorderColor: "rgb(51, 204, 204)",
                backgroundColor: "rgb(255, 255, 204)",
                //margin: [-150, 0],
                backgroundOpacity: .75,
		container: graphState.legend
            }
        }
    };

    /*
     * Activate zoom and pan options for graph
     */
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

    /*
     * Series object represents label and data package needed to render 
     * a discrete graph
     */
    var create_series_object = function (label, data) {
        return {
                 label: label,
                 data: data,
               }
    };

    /*
     * Helper function for plothover
     */
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

    /*
     * Binds mouse hover to a particular graph instance allowing
     * tool tips to be shown
     */
    Graph.prototype.bind_plothover = function () {
        var previousPoint = null;
        var element = this.graphState.element;
        var xtype = this.graphState.xtype;
        var self = this;
        var type = "tool_tip";
        var granularity, end_date, x, y;

        $(element).bind("plothover", function (event, pos, item) {
            $("#x").text(pos.x.toFixed(2));
            $("#y").text(pos.y.toFixed(2));

            if (item) {
                if (previousPoint != item.dataIndex) {
                    previousPoint = item.dataIndex;
    
                    granularity = self.graphState.granularity;

                    $("#tooltip").remove();
                        y = item.datapoint[1].toFixed(2);

                        if(xtype.toLowerCase() === "time") {
                            if(granularity !== "Hourly") {
                                end_date = item.datapoint[0];
                            }

                            x = get_month_day_year(item.datapoint[0], type, granularity, end_date);
                            show_tool_tip(item.pageX, item.pageY,
                                item.series.label + " for " + x + " is " + y);
                        } else {
			    x = item.datapoint[0].toFixed(2);
                            show_tool_tip(item.pageX, item.pageY,
                                item.series.label + ": " + y + " against " + xtype + ": " + x);
                        }
                }
            } else {
                $("#tooltip").remove();
                previousPoint = null;
            }
        });
    };

    /*
     * Binds mouseclick to graph instance data points and calculates
     * new granularity and date ranges so that a drill down event occurs.
     * This information is passed by callback to a graph manager who
     * requests and returns the new data from controller class
     */
    Graph.prototype.bind_plotclick = function() {
        var xtype = this.graphState.xtype;
        var startdate = this.graphState.startdate;
        var enddate = this.graphState.enddate;
        var handleChangedData = this.graphState.callback;
        var drill_granularity, date_from, date_to;
        var data_point, date_UTC;
        var self = this;

            $(this.graphState.element).bind("plotclick", function (event, pos, item) {
            if (item) {

                var granularity = self.graphState.granularity;

                var new_granularity = {
                    Hourly: null,
                    Daily: "Hourly",
                    Weekly: "Daily",
                    Monthly: "Weekly",
                    Yearly: "Monthly"
                };

                drill_granularity = new_granularity[granularity];
                  
                if(xtype.toLowerCase() === "time") {
                    data_point = item.datapoint[0];
                    date_UTC = (new Date(data_point));
                    date_from = format_date(date_UTC);
                } else {
                    data_point = item.dataIndex;
                }

                if(drill_granularity === null) {
                    // cannot drill down further
                    return;
                } else if (drill_granularity === "Hourly") {

                    if(xtype.toLowerCase() === "time") {
                        date_to = date_from;
                        date_from += " 00";
                        date_to += " 23";
                    } else {
                        date_from = format_date(new Date(map_index_to_time(data_point, startdate, drill_granularity)));
                        date_to = date_from;
                        date_from += " 00";
                        date_to += " 23";
                    }
                } else if (drill_granularity === "Daily") {
                    if(xtype.toLowerCase() === "time") {
                        date_to = get_date_to(data_point, drill_granularity);
                    } else {
                        var temp_date = map_index_to_time(data_point, startdate, drill_granularity);
                        date_from = format_date(new Date(temp_date));
                        date_to = date_from + get_millisecond_interval(drill_granularity);
                    }

                } else if(drill_granularity === "Weekly") {
                    if(xtype.toLowerCase() === "time") {
                        date_to = get_date_to(data_point, drill_granularity);
                    } else {
                        var temp_date = map_index_to_time(data_point, startdate, drill_granularity);
                        var date_string = (new Date(temp_date));
                        var year = date_string.getUTCFullYear();
                        var month = date_string.getUTCMonth();
                        date_from = year + '-' + month + '-01';
                        date_to = year + '-' + month + '-' + get_days_in_month(month, year);                        
                    }

                } else {
                    // then current granularity is years
                    if(xtype.toLowerCase() === "time") {
                        date_to = date_UTC.getUTCFullYear() + '-12-31';        
                    } else {
                        var temp_date = map_index_to_time(data_point, startdate, drill_granularity);
                        var date_string = (new Date(temp_date));
                        var year = date_string.getUTCFullYear();
                        date_from = year + '-01-01';
                        date_to = year + '-12-31';
                    }        
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
     * Correlational graphs do not carry their timestamp values in their x,y tuple.
     * This function calculates the target date range for drill down based on the 
     * location of the clicked data point in its array (eg. on an hourly graph, a 
     * data point at index 3 would indicate the 4th hour of that day
     */
    var map_index_to_time = function (data_point, startdate, granularity) {
        return startdate + ((data_point) * get_next_interval(granularity));
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

    /*
     *  Return date as string in following format ('2012-03-01')
     */
    var format_date = function (date) {
        return date.getUTCFullYear() + '-' + add_leading_zero(date.getUTCMonth() + 1) + '-' + add_leading_zero(date.getUTCDate());
    };

    /*
     * Calculates the number of days in a given month
     */
    var get_days_in_month = function (month, year) {
        month = parseInt(month);
        year = parseInt(year);
        return (32 - new Date(year, month, 32).getDate());
    };

    var get_date_to = function (date, drill_granularity) {
        var millisecond_day = 86400000;

        if (drill_granularity === "Daily") {
            var date_to = date + get_millisecond_interval(drill_granularity);
            return format_date(new Date(date_to));
        }

        if (drill_granularity === "Weekly") {
            var temp_date = new Date(date);
            var month = temp_date.getUTCMonth();
            var year = temp_date.getUTCFullYear();
            var num_days = get_days_in_month(month, year);
            date_to = date + (num_days - 1) * millisecond_day;
            return format_date(new Date(date_to));
        }
    };

    /*
     * Add zero padding to dates and hours
     */
    var add_leading_zero = function (date) {
        return date < 10 ? '0' + date : '' + date;
    };

    /*
     * Return the output for tool tips and axis labels
     *
     */
    var get_month_day_year = function (start, type, granularity, end) {
        var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        var date_string = new Date(start);        
        var day = date_string.getUTCDate();
        var month = months[date_string.getUTCMonth()];
        var year = date_string.getUTCFullYear();
	var hour = add_leading_zero(date_string.getUTCHours()) + ":00";
	var end_date_string, end_day, end_month, end_year, week_end, end_date;

        if(granularity === "Monthly") {
	    end_date = start + get_millisecond_interval(granularity);		    
	} else {
	    end_date = end;
	}
            var end_date_string = new Date(end_date);
            var end_day = end_date_string.getUTCDate();
            var end_month = months[end_date_string.getUTCMonth()];
            var end_year = end_date_string.getUTCFullYear();
            var week_end = (new Date(end_date + get_millisecond_interval("Daily"))).getUTCDate();

        if(type === "tool_tip") {
            return tool_tip = {
                Hourly: month + ' ' + day + ' ' + hour + ' ' + year,
                Daily: month + ' ' + day + ' ' + year,
                Weekly: month + ' ' + day + '-' + week_end + ' ' + year,
                Monthly: month + ' ' + year
            }[granularity];
        } else {
            return axis_label = {
                Hourly: month + ' ' + day + ' ' + year,
                Daily: month + ' ' + day + ' ' + year + ' - ' + end_month + ' ' + end_day + ' ' + end_year,
                Weekly: month + ' ' + day + ' ' + year + ' - ' + end_month + ' ' + week_end + ' ' + end_year,
                Monthly: month + ' ' + day + ' ' + year + ' - ' + end_month + ' ' + end_day + ' ' + end_year,
                Yearly: month + ' ' + day + ' ' + year + ' - ' + end_month + ' ' + end_day + ' ' + end_year
            }[granularity];
        } 

        return type[granularity];
    };

    var get_millisecond_interval = function (interval) {
            var base = 3600000;
            return milliseconds = {
                Hourly: base * 23,
                Daily: base * 24 * 6,
                Weekly: base * 24 * 7,
		Monthly: base * 24 * 30,
                Yearly: base * 24 * 365
            }[interval];
    };

    var get_next_interval = function (interval) {
        var base = 3600000;
        return milliseconds = {
            Hourly: base * 24,
            Daily: base * 24 * 7,
            Weekly: base * 24 * 31,
            Yearly: base * 24 * 366
        }[interval];
    };

    get_bar_interval = function (granularity) {
        var base = 3600000;
	return milliseconds = {
            Hourly: base,
            Daily: base * 24,
            Weekly: base * 24 * 7,
	    Monthly: base * 24 *31,
            Yearly: base * 24 * 366
        }[granularity];
    };

    get_tick_labels = function (ticks, granularity) {
        var label;

        for(i = 0; i < ticks.length; ++i) {
            if(granularity === "Weekly") {
                label = "Week " + (i + 1);
            } else {
                label = (new Date(ticks[i][0])).getUTCFullYear();
            }

            ticks[i].push(label);
        }
        return ticks;
    };

    get_measurement_units = function (sensor_type) {
	var unit = 
	{
            Temperature: "(°C)",
            Relative_Humidity: "(%)",
            CO2: "(PPM)",
            Hot_Water: "(gallons)",
            Total_Water: "(gallons)",
	    Stud: "(W/m²)",
	    Insulation: "(W/m²)",
	    Total_Energy: "(Wh)",
	    Total_Volume: "(L)",
	    Total_Mass: "(g)",
	    Current_Flow: "(L/s)",
	    Current_Temperature_1: "(°C)",
	    Current_Temperature_2: "(°C)",
        };

	var metric = unit[sensor_type];
	if(metric === undefined) {
	    metric = "(KJ)";
	}

        return metric;
	    
    };

    /* This module exports one public member -- the class itself. */
    return Graph;

});
