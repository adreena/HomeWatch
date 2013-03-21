require(
    ['jquery', 'underscore', // Main libraries
        'spiffy/spiffy', // Collabible menus
        'jquryui', // For date picker
        'flot/flot.jquery', // Flot charts
        'flot-axislabels/flot-axislabels', // Extra flot plugins
        'flot-orderbars'],

    /*
     * jSearch.
     *
     * Currently, the file that handles *ALL* JavaScript
     * stuff on the mangineer search page.
     *
     * TODO: Consider stuffing some of these things in modules,
     * and compiling stuff with RequireJS.
     *
     */

    function ($, _) {

        // For some stupid reason, JSLint requires
        // all var statements appear at the top of the file.
        // See: http://www.jslint.com/lint.html#scope
        var searchMod,

            /* First, some constants: */
            /* If we're using requireJS's optimization anyway, we might
             * as well stuff this in a module. */
            CONTROLLER_URL = '/search/process.php', // by the way, this is the model, not the controller...
            SEARCH_SELECTOR = 'form.search',

            /* Global Variables. */
            searchCache = {}, // Cache for requests. We don't have to make more requests than necessary.

            /* Then some local functions. */
            onLoad,
            bindMenus,
            bindDatepicker,
            bindSearchForm,
            onSearch,
            updateDisplay;


        // This is called when everything is done loading.
        onLoad = function () {
            bindDatepicker();
            bindMenus();
            bindSearchForm();
        };


        // Binds the search category menus.
        bindMenus = function () {

            // with Spiffy! ...except not yet.
            $("#menu > li > a").click(function () {
                $(this).toggleClass("expanded").toggleClass("collapsed").parent()
                    .find('> ul').slideToggle("medium");
            });

        };


        // Binds the datepicker inside the search form.
        // Eddie's going to do some crazy stuff in here:
        bindDatepicker = function () {

            // with jQueryUI
            $(".datepicker").datepicker({
                dateFormat: 'yy-mm-dd'
            });

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
                var data = $("form").serialize();

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
                data = $("form").serialize();
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
                url: "process.php",
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
                error: function () {
                    console.log("error accessing process.php");
                }
            });
        };

        updateDisplay = function (result) {

            var selectedValue = "";
            var selected = $("#graphs input[type='radio']:checked");
            if (selected.length > 0) {
                selectedValue = selected.val();
            }

            if (selectedValue === "plainText") {
                displayText(result);
            } else {
                render_graph(selectedValue, result);
            }

        };


        // The rest of the program!

        var displayText = function (result) {
            var display_text = "";

            $.each(result, function (key, value) {
                var granularity = result.query.granularity;

                display_text += "<h2><i>Apartment " + key + ": </i></h2>";

                $.each(value, function (key, value) {
                    display_text += "<h4>" + key + "</h4>";

                    $.each(value, function (key, value) {
                        if ($.isArray(value)) {
                            display_text += key + ": <br />";
                            $.each(value, function (i, value) {
                                display_text += "Hour " + i + ": " + value +
                                    "<br />";
                            });
                            display_text += "<br />";
                        } else {
                            display_text += key + ": " + value +
                                "<br /> <br />";
                        }
                    });
                });
            });

            $(".graph1").html(display_text);
        };

        var render_graph = function (selectedValue, result) {
            var granularity = result.query.granularity;
            var data_and_opts = format_data(selectedValue, result);
            var data = data_and_opts["data"];
            var options = data_and_opts["options"];

            $.plot($(".graph1"), data, options);
            bind_plotclick(granularity);
        };

        var bind_plotclick = function (granularity) {
            var drill_granularity;
            var date_from;
            var date_to;
            var data = $("form").serialize();

            $(".graph1").bind("plotclick", function (event, pos, item) {
                if (item) {
                    var offset = (new Date(item.datapoint[0])).getTimezoneOffset() * 60 * 1000;
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

        var format_data = function (selectedValue, result) {
            var sensor_data = [];
            var series_data = [];
            var data_and_options = [];
            var graphname = [];
            var apartments = [];
            var millisecond_multiplier = 3600000;
            var millisecond_day = 86400000;
            var graphname_flag = "false";
            var granularity;
            var min_date = 0;
            var max_date = 0;

            granularity = result.query.granularity

            $.each(result, function (key, value) {
                var apartment = key;
                apartments.push(apartment);
                sensor_data[apartment] = [];

                $.each(value, function (key, value) {
                    // key = date stamps
                    if (granularity === "Hourly") {
                        x_tick = parseInt(key);
                        var temp = (new Date(x_tick)).getTimezoneOffset() * 60 * 1000;
                        x_tick = x_tick - temp;
                    } else {
                        x_tick = parseInt(key);
                    }

                    if (min_date === 0) {
                        min_date = x_tick;
                    }

                    if (x_tick > max_date) {
                        if (granularity === "Hourly") {
                            max_date = x_tick + millisecond_day;
                        } else {
                            max_date = x_tick;
                        }
                    }

                    if (graphname.length !== 0) {
                        graphname_flag = "true";
                    }

                    $.each(value, function (key, value) {
                        // key = sensor names
                        var sensor = key;

                        if (graphname_flag === "false") {
                            graphname.push(sensor);
                        }

                        if (sensor_data[apartment][sensor] === undefined) {
                            console.log(sensor);
                            sensor_data[apartment][sensor] = [];
                        }

                        if ($.isArray(value)) {
                            $.each(value, function (i, value) {
                                if (series_data.length === 0) {
                                    tuple = [];
                                } else {
                                    tuple.length = 0;
                                }

                                if (i === 0) {
                                    var tick_size = x_tick;
                                } else {
                                    var tick_size = x_tick + millisecond_multiplier * i;
                                }

                                tuple[0] = tick_size;
                                tuple[1] = value;
                                sensor_data[apartment][sensor].push(tuple);
                            });
                        } else {
                            if (series_data.length === 0) {
                                tuple = [];
                                console.log(tuple);
                            } else {
                                tuple.length = 0;
                            }

                            tuple[0] = x_tick;
                            tuple[1] = value;
                            sensor_data[apartment][sensor].push(tuple);
                        }
                    });
                });
            });

            for (var i = 0; i < apartments.length; ++i) {
                for (var j = 0; j < graphname.length; ++j) {
                    var label = "Apartment " + apartments[i] + " " + graphname[j];
                    series_length = series_data.length;
                    if (series_length === 0) {
                        series_data[0] = create_series_object(label, sensor_data[apartments[i]][graphname[j]]);
                    } else {
                        series_data[series_length] = create_series_object(label, sensor_data[apartments[i]][graphname[j]]);
                    }
                }
            }


            var options = set_all_options(selectedValue, graphname, granularity, min_date, max_date);
            data_and_options["data"] = series_data;
            data_and_options["options"] = options;
            return data_and_options;
        };

        var set_all_options = function (graphtype, graphname, granularity, min_date, max_date) {
            var x_axis = get_x_axis(granularity, min_date, max_date);
            var y_axis = get_y_axis(graphname);
            var grid = get_grid();
            var series_opts = get_series_options(graphtype);
            var options = $.extend({}, x_axis, y_axis, grid, series_opts);
            return options;
        };

        var get_x_axis = function (granularity, min_date, max_date) {
            var base_x = {
                xaxis: {
                    mode: "time",
                    timezone: "local",
                    axisLabelUseCanvas: true,
                    axisLabelFontSizePixels: 12,
                    axisLabelFontFamily: 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
                    axisLabelPadding: 5,
                    autoscaleMargin: .50
                }
            };

            base_x.xaxis["min"] = min_date;
            base_x.xaxis["max"] = max_date;

            if (granularity === "Hourly") {
                base_x.xaxis["tickSize"] = [1, "hour"];
                var date = new Date(min_date);
                var label = date.getUTCDay();
                base_x.xaxis["axisLabel"] = label;

            } else if (granularity === "Daily") {
                base_x.xaxis["timeformat"] = "%m/%d/%y";
                base_x.xaxis["tickSize"] = [1, "day"];
                var date = new Date(min_date);
                var date_from = date.getUTCDay();
                var date = new Date(max_date);
                var date_to = date.getUTCDay();
                base_x.xaxis["axisLabel"] = date_from + " - " + date_to;

            } else if (granularity === "Weekly") {
                // TODO
            } else if (granularity === "Monthly") {
                // override min date so that January label shows on graph
                base_x.xaxis["min"] = min_date - 25200000;
                base_x.xaxis["timeformat"] = "%b";
                base_x.xaxis["tickSize"] = [1, "month"];
                base_x.xaxis["monthNames"] = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                var date = new Date(min_date);
                var label = date.getFullYear();
                base_x.xaxis["axisLabel"] = 'Year: ' + label;

            }
            return base_x;
        };

        var get_y_axis = function (graphname) {
            var base_y = {
                yaxis: {
                    axisLabelUseCanvas: true,
                    axisLabelFontSizePixels: 12,
                    axisLabelFontFamily: 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
                    axisLabelPadding: 5
                }
            };

            for (var i = 0; i < graphname.length; ++i) {
                base_y.yaxis["axisLabel"] = graphname[i];
            }

            return base_y;
        };

        var get_grid = function () {
            return base_grid = {
                grid: {
                    hoverable: true,
                    clickable: true,
                    borderWidth: 3,
                    labelMargin: 3
                }
            };
        };

        var get_series_options = function (graphtype) {
            var line = {
                series: {
                    lines: {
                        show: true
                    },
                    points: {
                        radius: 3,
                        show: true,
                        fill: true
                    }
                }
            };

            var bars = {
                series: {
                    bars: {
                        show: true,
                        barWidth: 1000 * 60 * 60 * 0.25,
                        fill: true,
                        lineWidth: 1,
                        clickable: true,
                        hoverable: true
                    }
                }
            };

            if (graphtype === "line") {
                return line;
            } else if (graphtype === "histo") {
                return bars;
            }
        };

        var create_series_object = function (label, data) {
            return {
                label: label,
                data: data
            }
        };




        // Setup the jQuery on document ready
        // to bind everything.
        $(onLoad);

        // If this were a module, we'd put the exports here:
        // return { export1: somefunc, ... };

    }
); // require
