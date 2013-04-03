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

	graphState.numbars = apartments.length * graphname.length;
        graphState.ticks = ticks;
        graphState.startdate = startdate;
        graphState.enddate = enddate;
        graphState.min_x = min_x;
        graphState.max_x = max_x;

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
        var legend = get_legend(graphState);

        if(graphState.granularity === "Hourly" && (graphState.xtype).toLowerCase() !== "time") {
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
                //base_x.xaxis["max"] = startdate + get_millisecond_interval(granularity);
                base_x.xaxis["timeformat"] = "%a %d";
                base_x.xaxis["tickSize"] = [1, "day"];
                base_x.xaxis["dayNames"] = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
                base_x.xaxis["axisLabel"] = get_month_day_year(startdate, type, granularity, enddate);
            } else if (granularity === "Weekly") {
                base_x.xaxis["ticks"] = get_tick_labels(ticks, granularity);
                //granularity = "Daily";
                base_x.xaxis["axisLabel"] = get_month_day_year(startdate, type, granularity, enddate);
                //console.log("max date is " + max_date);
                //base_x.xaxis["tickSize"] = [1, "week"];
            } else if(granularity === "Monthly") {
                base_x.xaxis["timeformat"] = "%b";
                base_x.xaxis["tickSize"] = [1, "month"];
                base_x.xaxis["monthNames"] = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                //var label = min_date.getUTCFullYear();
                base_x.xaxis["axisLabel"] = get_month_day_year(startdate, type, granularity, enddate);
                //var year_end = label + "-12-01:0";
                //base_x.xaxis["max"] = DateToUTC(year_end);
            } else {
                base_x.xaxis["ticks"] = get_tick_labels(ticks, granularity);
            }
        } else {
            base_x.xaxis["min"] = min_x;
            base_x.xaxis["max"] = max_x;
            base_x.xaxis["axisLabel"] = xtype + get_measurement_units(xtype);
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
                  axisLabelPadding: 25
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

    var get_series_options = function (graphState) {
        var graphType = graphState.graphType;
	var num_bars = graphState.numbars;
	var granularity = graphState.granularity;
	var order = [];

        var line = {series: {lines: {show: true}, points: {radius: 3, show: true, fill: true}}};
        var bars = {series: {bars: { show: true, fill: true, lineWidth: 1, clickable: true, hoverable: true}}};
        var pie =  {series: {pie: {show: true, radius: 1}}};

        if(graphType === "line") {
        //console.log("line opt is " + line.series.points.show);
            return line;
        } else if(graphType === "bar") {
	    bars.series.bars.barWidth = get_bar_interval(granularity) * 0.75;
            return bars;
        } else if(graphType === "pie") {
            return pie;
        }
    };

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

    Graph.prototype.bind_plothover = function () {
        var previousPoint = null;
        var element = this.graphState.element;
        var xtype = this.graphState.xtype;
        var self = this;
        var type = "tool_tip";
        var granularity, end_date;

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
                                var x = get_month_day_year(item.datapoint[0], type, granularity, end_date);
                            show_tool_tip(item.pageX, item.pageY,
                                item.series.label + " for " + x + " is " + y);
                        } else {
                            show_tool_tip(item.pageX, item.pageY,
                                item.series.label + ": " + y + " against " + xtype + ": " + y);
                        }
                }
            } else {
                $("#tooltip").remove();
                previousPoint = null;
            }
        });
    };

    Graph.prototype.bind_plotclick = function() {
        //var old_granularity = this.graphState.granularity;
       console.log("bound gran is " + this.graphState.granularity);
        var xtype = this.graphState.xtype;
        var startdate = this.graphState.startdate;
        var enddate = this.graphState.enddate;
        var handleChangedData = this.graphState.callback;
        var drill_granularity, date_from, date_to;
        var data_point, date_UTC;
        var self = this;

        //console.log("xtype is " + xtype);
        //console.log("granularity is " + granularity);

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
            //console.log("old granularity is " + old_granularity);
            console.log("drill granularity is " + drill_granularity);

            
               
                console.log("you clicked!");

                 //console.log("granularity is : " + granularity);


                if(xtype.toLowerCase() === "time") {
            console.log("xtype is time");
                    data_point = item.datapoint[0];
                    date_UTC = (new Date(data_point));
                    date_from = format_date(date_UTC);
                } else {
                    data_point = item.dataIndex;
                console.log("data index is " + item.dataIndex);
                }

                if(drill_granularity === null) {
                    // cannot drill down further
                    console.log("returning without drilling");
                    return;
                } else if (drill_granularity === "Hourly") {
            //console.log("drilling down to hourly");
                    //drill_granularity = "Hourly";

                    if(xtype.toLowerCase() === "time") {
            console.log("we got into hourly drill");
                        date_to = date_from;
                date_from += " 00";
                date_to += " 23";
            console.log("date from is " + date_from + " and date to is " + date_to);
                    } else {
                        console.log("entered correlational date");
                        date_from = format_date(new Date(map_index_to_time(data_point, startdate, drill_granularity)));
                        date_to = date_from;
                        date_from += " 00";
                        date_to += " 23";
                        console.log("date to is " + date_to);
                    }
                } else if (drill_granularity === "Daily") {
                    //drill_granularity = "Daily";

                    if(xtype.toLowerCase() === "time") {
                        date_to = get_date_to(data_point, drill_granularity);
                        console.log("date to is " + date_to);
                    } else {
                        var temp_date = map_index_to_time(data_point, startdate, drill_granularity);
                        date_from = format_date(new Date(temp_date));
                        date_to = date_from + get_millisecond_interval(drill_granularity);
                        console.log("date from is " + date_from + " date to is " + date_to);
                    }

                } else if(drill_granularity === "Weekly") {
                   //drill_granularity = "Weekly";

                    if(xtype.toLowerCase() === "time") {
                        date_to = get_date_to(data_point, drill_granularity);
                    } else {
                        var temp_date = map_index_to_time(data_point, startdate, drill_granularity);
                        var date_string = (new Date(temp_date));
                        var year = date_string.getUTCFullYear();
                        var month = date_string.getUTCMonth();
                        date_from = year + '-' + month + '-01';
                        date_to = year + '-' + month + '-' + get_days_in_month(month, year);
                        console.log("date from is " + date_from + " date to is " + date_to);                         
                    }

                } else {
                    // then current granularity is years
                    //drill_granularity = "Monthly";

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

        console.log("about to handle changed data");
            console.log("drill gran is " + drill_granularity);
                /* Tell whatever handler we've got that there's new data. */
                handleChangedData({
                    startdate: date_from,
                    enddate: date_to,
                    period: drill_granularity
                });
                   } // if statement
        }); // end plotclick
    };

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

    var format_date = function (date) {
            // return date as string in following format ('2012-03-01')
        return date.getUTCFullYear() + '-' + add_leading_zero(date.getUTCMonth() + 1) + '-' + add_leading_zero(date.getUTCDate());
    };

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

    var add_leading_zero = function (date) {
        return date < 10 ? '0' + date : '' + date;
    };

    var get_month_day_year = function (start_date, type, granularity, end_date) {
        var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        var date_string = new Date(start_date);        
        var day = date_string.getUTCDate();
        var month = months[date_string.getUTCMonth()];
        var year = date_string.getUTCFullYear();

        if(end_date !== undefined) {
	    if(start_date === end_date) {
		console.log("start = end");
	    }
            var end_date_string = new Date(end_date);
            var end_day = end_date_string.getUTCDate();
            var end_month = months[end_date_string.getUTCMonth()];
            var end_year = end_date_string.getUTCFullYear();
            var week_end = (new Date(end_date + get_millisecond_interval("Daily"))).getUTCDate();
        }

        if(type === "tool_tip") {
            return tool_tip = {
                Hourly: month + ' ' + day + ' ' + year,
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

        console.log("type gran is " + type[granularity]);
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
    }

     /*var get_week_labels = function (startdate, enddate, granularity) {
        var ticks = [];
        var milli_week = get_millisecond_interval(granularity);
        console.log("start is " + startdate);
        console.log("end is " + enddate);
        var max_date = enddate - startdate;
        console.log("max is " + max_date);
        var num_weeks = Math.ceil(max_date/get_millisecond_interval(granularity)) + 1;

        console.log("num weeks is " + num_weeks);
        console.log("new iteration");
        for(i = 0; i < num_weeks; ++i) {
            console.log("gran is " + granularity);
            console.log("milli week is " + milli_week);
            ticks.push([startdate + (milli_week * i), "Week " + (i + 1)]);
                console.log((startdate + (milli_week * i)) + "Week " + (i + 1));
        }
        console.log("milli week 1 is " + startdate);
        console.log("milli week 2 is " + (startdate + milli_week));
        console.log("milli week 3 is " + (startdate + (milli_week * 2)));
        console.log("milli week 4 is " + (startdate + (milli_week * 3)));
        console.log("milli week 5 is " + (startdate + (milli_week * 4)));

        console.log("ticks size is " + ticks.length);

        return ticks;
    };*/

    get_tick_labels = function (ticks, granularity) {
        var label;
        //var milli_year = get_next_interval(granularity);

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

console.log("sensor type is " + sensor_type);

	var metric = unit[sensor_type];
	if(metric === undefined) {
	    metric = "(KJ)";
	}

        return metric;
	    
    };

    /* This module exports one public member -- the class itself. */
    return Graph;

});
