$(document).ready(function() {

	$(function() { 
        	$("#datepicker").datepicker({dateFormat: 'yy-mm-dd'});
    	});

 	
    	$("#menu > li > a").click(function() {
        	$(this).toggleClass("expanded").toggleClass("collapsed").parent().find('> ul').slideToggle("medium");
    	});

     

    	$('.checkAllSensors').change(function() {
        	if(this.checked) {
            	$('.sensor-group1 :checkbox, sensor-group2 :checkbox').prop('checked', this.checked);
            	$('.sensor-group2 :checkbox').prop('checked', this.checked);
        	} else {
            	$('.sensor-group1 :checkbox').prop('checked', false);
            	$('.sensor-group2 :checkbox').prop('checked', this.checked);
        	}
    	});

    	$('.allApts').change(function() {
        	if(this.checked) {
            	$('.apartment-group :checkbox').prop('checked', this.checked);
        	} else {
            	$('.apartment-group :checkbox').prop('checked', false);
        	}
    	});

    	$("#submitbutton").click(function(){
        	var data = $("form").serialize();
    
        	$.ajax({
        	url: "process.php",
        	data: data,
        	cache: false,
        	dataType: 'json',
        	success: function(result) {

			var selectedValue = "";
        		var selected = $("#graphs input[type='radio']:checked");
			if (selected.length > 0) {
    	  			selectedValue = selected.val();
			} 

			if(selectedValue == "plainText") {
				displayText(result);
			} else {
				render_graph(selectedValue, result);
			}
           
        	} // end success callback
      		});  // end ajax call
    	}); // end submit on click


// you can run this as test data
/*var timestampdata = {"granularity":"Daily", "1":{"1330498800000":{"Temperature":["27.1396551724139", "3","4", "5", "6", "7"],"CO2":["115.7222", "44", "55", "66", "77", "88"]}}, "2":{"1330498800000":{"Temperature":["19.6032567049809", "55", "89", "43", "67", "87"], "CO2": ["96.6897", "56", "79", "86", "32", "42"]}}, "6":{"1330498800000":{"Temperature":["28.25", "45.01", "16", "22.6", "6", "15"], "CO2": ["90", "102", "136", "155", "102", "65"]}}};*/

	var displayText = function(result) {
		var display_text = "";
		
		$.each(result, function(key, value) {
			if(key === "granularity") {
				var granularity = value;
			  	return;
			}

            		display_text += "<h2><i>Apartment " + key + ": </i></h2>";
	    		
			$.each(value, function(key, value) {	
				display_text += "<h4>" + key + "</h4>";					
		 			
				$.each(value, function(key, value) {
					if($.isArray(value)) {
						display_text += key + ": <br />";
						$.each(value, function(i, value) {
							display_text += "Hour " + i + ": " + value + "<br />";
						});
						display_text += "<br />";
					} else {
						display_text += key + ": " + value + "<br /> <br />";
					}
                 		});
	    		});
		});

        	$(".graph1").html(display_text);
    	}

	var render_graph = function(selectedValue, result) {
		var data_and_opts = format_data(selectedValue, result);
		var data = data_and_opts["data"];
		var options = data_and_opts["options"];

		$.plot($(".graph1"), data, options);
	}

	var format_data = function(selectedValue, result) {
		var sensor_data = [];
		var series_data = [];
		var data_and_options = [];
		var graphname = [];
		var apartments = [];
		//var time_stamps = [];
		var millisecond_multiplier = 3600000;
		var GMT_offset = 25200000;
		var graphname_flag = "false";
		var granularity;

		$.each(result, function(key, value) {
			if(key === "granularity") {
				granularity = value;
			  	return;
			}

			var apartment = key;
			apartments.push(apartment);
			console.log(apartments);
			sensor_data[apartment] = [];

			$.each(value, function(key, value) {
				// key = date stamps
				//if(time_stamps.length === 0) {
					x_tick =  parseFloat(key-GMT_offset);
					//console.log(x_tick);				
				//}

				if(graphname.length !== 0) {
					graphname_flag = "true";
				}
		 			
				$.each(value, function(key, value) {					
					// key = sensor names
					var sensor = key;

					if(graphname_flag === "false") {
						graphname.push(sensor);
					}

					if(sensor_data[apartment][sensor] ===  undefined) {
						console.log(sensor);
						sensor_data[apartment][sensor] = [];
					}

					if($.isArray(value)) {
						$.each(value, function(i, value) {
							if(series_data.length === 0) {
								tuple = [];
							} else {
								tuple.length = 0;
							}

							if(i === 0) {
								var tick_size = x_tick;
							} else {
								var tick_size = x_tick + millisecond_multiplier*i;
							}
							tuple[0] = tick_size;							
							tuple[1] = value;
							sensor_data[apartment][sensor].push(tuple);
						});
					} else {
						if(series_data.length === 0) {
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

		for(var i = 0; i < apartments.length; ++i) {
			for(var j = 0; j < graphname.length; ++j) {
				var label = "Apartment " + apartments[i] + " " + graphname[j];
				series_length = series_data.length;
				console.log("series length is " + series_length);
				if(series_length === 0) {
					series_data[0] = create_series_object(label, sensor_data[apartments[i]][graphname[j]]);
				} else {
					series_data[series_length] = create_series_object(label, sensor_data[apartments[i]][graphname[j]]);
				}
			}

		}
	

	var options = set_all_options(selectedValue, graphname, granularity);
	data_and_options["data"] = series_data;
	data_and_options["options"] = options;
	return data_and_options;
	}

	var set_all_options = function(graphtype, graphname, granularity) {
		var x_axis = get_x_axis(granularity);
		var y_axis = get_y_axis(graphname);
		var grid = get_grid();
		var series_opts = get_series_options(graphtype);
		var options = $.extend({}, x_axis, y_axis, grid, series_opts);
		return options;
	}

	var get_x_axis = function(granularity) {
		var base_x = {
			xaxis: 	{ mode: "time", timezone: "local", axisLabelUseCanvas: true, axisLabelFontSizePixels: 12,
                	axisLabelFontFamily: 'Verdana, Arial, Helvetica, Tahoma, sans-serif', axisLabelPadding: 5,
			autoscaleMargin: .50
			}		 
		};

		if(granularity === "Daily") {
			

		} else if(granularity === "Weekly") {
			base_x.xaxis["timeformat"] = "%d";
			base_x.xaxis["tickSize"] = [1, "day"];
			base_x.xaxis["dayNames"] = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];
			base_x.xaxis["axisLabel"] = 'Week';

		} else if(granularity === "Monthly") {

		} else if(granularity === "Yearly") {
			base_x.xaxis["timeformat"] = "%b";
			base_x.xaxis["tickSize"] = [1, "month"];
			base_x.xaxis["monthNames"] = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
			base_x.xaxis["axisLabel"] = 'Month';
	
		}
		return base_x;		
	}

	var get_y_axis = function(graphname) {
		var base_y = {
			yaxis: 	{ 
			axisLabelUseCanvas: true, axisLabelFontSizePixels: 12, 
			axisLabelFontFamily: 'Verdana, Arial, Helvetica, Tahoma, sans-serif', axisLabelPadding: 5
				}
        	};

		for(var i = 0; i < graphname.length; ++i) {
			base_y.yaxis["axisLabel"] = graphname[i];
		}

		return base_y;
	}

	var get_grid = function() {
		return base_grid = {
			grid: 	{
            		hoverable: true,
            		clickable: true,
            		borderWidth: 3,
	    		labelMargin: 3
        			}
		};     
	}

	var get_series_options = function(graphtype) {
		var series = {
			series: 
			{
				lines: {show: true},
				points: { radius: 3, show: true, fill: true },
				bars: { show: true, barWidth: 1000*60*60*0.25,
                		fill: true, lineWidth: 1, clickable: true,
    				hoverable: true,
				}
			}
		};

		if(graphtype === "line") {
			delete series.series["bars"];
		} else if(graphtype === "histo") {
			delete series.series["lines"];
			delete series.series["points"];
		}

		return series;
	}

	var create_series_object = function (label, data){
    		return {
      			label: label,
			data: data
    			}
	}



  		
}); // document.ready end
