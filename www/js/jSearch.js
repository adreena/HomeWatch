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
                
	var data1 = [
        	[(new Date("2000/01/01 00:30 UTC")).getTime(), 954],
        	[(new Date("2000/01/01 02:00 UTC")).getTime(), 999],
        	[(new Date("2000/01/01 03:00 UTC")).getTime(), 1031],
       	 	[(new Date("2000/01/01 04:00 UTC")).getTime(), 1100],
        	[(new Date("2000/01/01 05:00 UTC")).getTime(), 1030],
		[(new Date("2000/01/01 06:00 UTC")).getTime(), 1100],
        	[(new Date("2000/01/01 07:00 UTC")).getTime(), 1030]
    	];

	var data2 = [
        	[(new Date("2000/01/01 00:30 UTC")).getTime(), 950],
        	[(new Date("2000/01/01 02:00 UTC")).getTime(), 1000],
        	[(new Date("2000/01/01 03:00 UTC")).getTime(), 1200],
        	[(new Date("2000/01/01 04:00 UTC")).getTime(), 1150],
		[(new Date("2000/01/01 05:00 UTC")).getTime(), 1100],
        	[(new Date("2000/01/01 06:00 UTC")).getTime(), 900],
		[(new Date("2000/01/01 07:00 UTC")).getTime(), 900],
		[(new Date("2000/01/01 23:00 UTC")).getTime(), 900],
		[(new Date("2000/01/01 24:00 UTC")).getTime(), 900]
    	];

	var data3 = [
        	[(new Date("2000/01/01 00:30 UTC")).getTime(), 950],
        	[(new Date("2000/01/01 02:00 UTC")).getTime(), 1000],
        	[(new Date("2000/01/01 03:00 UTC")).getTime(), 1200],
        	[(new Date("2000/01/01 04:00 UTC")).getTime(), 1150],
		[(new Date("2000/01/01 05:00 UTC")).getTime(), 1100],
        	[(new Date("2000/01/01 06:00 UTC")).getTime(), 900],
		[(new Date("2000/01/01 07:00 UTC")).getTime(), 900],
		[(new Date("2000/01/01 23:00 UTC")).getTime(), 900],
		[(new Date("2000/01/01 24:00 UTC")).getTime(), 900]
    	];

	var data4 = [
        	[(new Date("2000/01/01 00:30 UTC")).getTime(), 950],
        	[(new Date("2000/01/01 02:00 UTC")).getTime(), 1000],
        	[(new Date("2000/01/01 03:00 UTC")).getTime(), 1200],
        	[(new Date("2000/01/01 04:00 UTC")).getTime(), 1150],
		[(new Date("2000/01/01 05:00 UTC")).getTime(), 1100],
        	[(new Date("2000/01/01 06:00 UTC")).getTime(), 900],
		[(new Date("2000/01/01 07:00 UTC")).getTime(), 900],
		[(new Date("2000/01/01 23:00 UTC")).getTime(), 900],
		[(new Date("2000/01/01 24:00 UTC")).getTime(), 900]
    	];

	var graphData = [
        	{
            	label: "Apartment 1",
            	data: data1,
            	bars: {
                	show: true,
                	barWidth: 1000*60*60*0.25,
                	fill: true,
               	 	lineWidth: 1,
                	order: 1,
                	fillColor:  "#AA4643",
			clickable: true,
    			hoverable: true,
            	},
		          
            	color: "#AA4643"
        	},
        	{
            	label: "Apartment 2",
            	data: data2,
            	bars: {
                	show: true,
                	barWidth: 1000*60*60*0.25,
                	fill: true,
                	lineWidth: 1,
                	order: 2,
                	fillColor:  "#89A54E",
			clickable: true,
    			hoverable: true
            	},
            	
            	color: "#89A54E"
        	},
		{
            	label: "Apartment 3",
            	data: data3,
            	bars: {
                	show: true,
                	barWidth: 1000*60*60*0.25,
                	fill: true,
                	lineWidth: 1,
                	order: 3,
                	fillColor:  "#003366",
			clickable: true,
    			hoverable: true
            	},
            	
            	color: "#003366"
        	}
    	];


	$.plot($(".graph1"), graphData, {
       
        	xaxis: 	{ mode: "time", timezone: "local", tickSize: [1, "hour"],
                	min: (new Date("2000/01/01 00:00 UTC")).getTime(),
                	max: (new Date("2000/01/01 24:00 UTC")).getTime(),
			axisLabelUseCanvas: true,
                	axisLabelFontSizePixels: 12,
                	axisLabelFontFamily: 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
                	axisLabelPadding: 5,
			autoscaleMargin: .50		 
               		},

		yaxis: 	{
            		axisLabel: 'CO2',
            		axisLabelUseCanvas: true,
            		axisLabelFontSizePixels: 12,
            		axisLabelFontFamily: 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
            		axisLabelPadding: 5
        		},
        	grid: 	{
            		hoverable: true,
            		clickable: true,
            		borderWidth: 3,
	    		labelMargin: 3
        		},
        
        	series: {
			clickable: true,
    			hoverable: true, 
            		shadowSize: 1
        		}
	});


	var data5 = [
        	[1325376000000, 120],
        	[1328054400000, 70],
        	[1330560000000, 100],
        	[1333238400000, 60],
        	[1335830400000, 35],
		[1338508800000, 125],
		[1341161200000, 99],
		[1343815600000, 85],
		[1346444000000, 95],
		[1349122400000, 110],
		[1351700800000, 120],
		[1354379200000, 115]
	
	];

	var data6 = [
        	[1325376000000, 100],
        	[1328054400000, 80],
        	[1330560000000, 85],
        	[1333238400000, 79],
        	[1335830400000, 55],
		[1338508800000, 70],
		[1341161200000, 95],
		[1343815600000, 100],
		[1346444000000, 93],
		[1349122400000, 118],
		[1351700800000, 105],
		[1354379200000, 120]
	
	];

	var data7 = [
        	[1325376000000, 108],
        	[1328054400000, 72],
        	[1330560000000, 100],
        	[1333238400000, 65],
        	[1335830400000, 40],
		[1338508800000, 115],
		[1341161200000, 105],
		[1343815600000, 99],
		[1346444000000, 95],
		[1349122400000, 125],
		[1351700800000, 130],
		[1354379200000, 135]
	
	];

	var graphData1 = [
        	{
            	label: "Apartment 3",
            	data: data5,
        	},
        	{
            	label: "Apartment 7",
            	data: data6,
            	},
		{
            	label: "Apartment 10",
            	data: data7,
        	}
    	];


	$.plot($(".graph2"), graphData1, {        
        	xaxis: 	{ mode: "time", timezone: "local",
                	min: (new Date(2011, 11, 15)).getTime(),
                	max: (new Date(2012, 11, 15)).getTime(),
                	mode: "time",
                	timeformat: "%b",
                	tickSize: [1, "month"],
                	monthNames: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
			axisLabel: 'Month',
			axisLabelUseCanvas: true,
                	axisLabelFontSizePixels: 12,
               		axisLabelFontFamily: 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
                	axisLabelPadding: 5,
			autoscaleMargin: .50		 
			},

		yaxis: {
            		axisLabel: 'CO2',
            		axisLabelUseCanvas: true,
            		axisLabelFontSizePixels: 12,
            		axisLabelFontFamily: 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
            		axisLabelPadding: 5
        		},
        	grid: 	{
            		hoverable: true,
            		clickable: true,
            		borderWidth: 3,
	    		labelMargin: 3
        		},        
       		series: {
            		lines: { show: true },
            		points: {
                	radius: 3,
                	show: true,
                	fill: true
            			}
			}
	});


  
  $(".graph1").bind("plotclick", function (event, pos, item) {        
        if (item) {
          alert("You clicked a point!");
        }    
    });

var datanew = [
        [(new Date("2000/01/01 00:00 UTC")).getTime(), 954],
        [(new Date("2000/01/01 01:00 UTC")).getTime(), 999],
        [(new Date("2000/01/01 02:00 UTC")).getTime(), 1031],
        [(new Date("2000/01/01 03:00 UTC")).getTime(), 1050],
        [(new Date("2000/01/01 04:00 UTC")).getTime(), 1029],
	[(new Date("2000/01/01 05:00 UTC")).getTime(), 1015],
        [(new Date("2000/01/01 06:00 UTC")).getTime(), 1075],
	[(new Date("2000/01/01 07:00 UTC")).getTime(), 1080],
	[(new Date("2000/01/01 08:00 UTC")).getTime(), 1060],
	[(new Date("2000/01/01 09:00 UTC")).getTime(), 1065],
	[(new Date("2000/01/01 10:00 UTC")).getTime(), 1040],
	[(new Date("2000/01/01 11:00 UTC")).getTime(), 1059],
	[(new Date("2000/01/01 12:00 UTC")).getTime(), 1066],
	[(new Date("2000/01/01 13:00 UTC")).getTime(), 1089],
	[(new Date("2000/01/01 14:00 UTC")).getTime(), 1099],
	[(new Date("2000/01/01 15:00 UTC")).getTime(), 1100],
	[(new Date("2000/01/01 16:00 UTC")).getTime(), 1112],
	[(new Date("2000/01/01 17:00 UTC")).getTime(), 1100],
	[(new Date("2000/01/01 18:00 UTC")).getTime(), 1105],
	[(new Date("2000/01/01 19:00 UTC")).getTime(), 1115],
	[(new Date("2000/01/01 20:00 UTC")).getTime(), 1120],
	[(new Date("2000/01/01 21:00 UTC")).getTime(), 1122],
	[(new Date("2000/01/01 22:00 UTC")).getTime(), 1115],
	[(new Date("2000/01/01 23:00 UTC")).getTime(), 1117],
    ];

var graphDatanew = [
        {
            label: "Apartment 3",
            data: datanew,
		          
            	color: "#AA4643"
        
        }

	 ];

$(".graph2").bind("plotclick", function (event, pos, item) {
        if (item) {
             var point_value = item.datapoint[1];
	     alert("You clicked point " + item.dataIndex + " in " + item.series.label + " whose value is" + point_value);

	     $.plot($(".graph2"), graphDatanew, {
        
        		xaxis: 	{ mode: "time", timezone: "local", tickSize: [1, "hour"],
                		min: (new Date("2000/01/01 00:00 UTC")).getTime(),
                		max: (new Date("2000/01/01 24:00 UTC")).getTime(),  
				axisLabel: 'Day',
				axisLabelUseCanvas: true,
                		axisLabelFontSizePixels: 12,
                		axisLabelFontFamily: 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
                		axisLabelPadding: 5,
				autoscaleMargin: .50		 
				},

			yaxis: {
            			axisLabel: 'CO2',
            			axisLabelUseCanvas: true,
            			axisLabelFontSizePixels: 12,
            			axisLabelFontFamily: 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
            			axisLabelPadding: 5
        			},
        		grid: 	{
            			hoverable: true,
            			clickable: false,
            			borderWidth: 3,
	    			labelMargin: 3
        			},
        
       			series: {
            			lines: { show: true },
            			points: {
                		radius: 3,
                		show: true,
                		fill: true
            				}
				}
    		}); // end $.plot
       	    } // if statement
}); // end plotclick






           
            
        }
        });
    });
	
});
