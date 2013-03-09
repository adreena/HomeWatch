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
                
  //var data1 = [[(new Date("2000/01/01 00:00 UTC")).getTime(), 315.71], [(new Date("2000/01/01 01:00 UTC")), 317.45], [(new Date("2000/01/01 02:00 UTC")), 317.50],  [99968400000, 319.79]]; 

  //var data2 = [[(new Date("2000/01/01 00:00 UTC")).getTime(), 315.00], [(new Date("2000/01/01 01:00 UTC")), 320.45], [(new Date("2000/01/01 02:00 UTC")), 317.50],  [99968400000, 319.79]];

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
                fillColor:  "#AA4643"
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
                fillColor:  "#89A54E"
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
                fillColor:  "#003366"
            },
            
            color: "#003366"
        },
	{
            label: "Apartment 4",
            data: data4,
            bars: {
                show: true,
                barWidth: 1000*60*60*0.25,
                fill: true,
                lineWidth: 1,
                order: 4,
                fillColor:  "#990066"
            },
            
            color: "#990066"
        }
    ];


$.plot($("#results"), graphData, {
        yaxis: {
        },
        xaxis: { mode: "time", timezone: "local", tickSize: [1, "hour"],
                min: (new Date("2000/01/01 00:00 UTC")).getTime(),
                max: (new Date("2000/01/01 24:00 UTC")).getTime(),
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
        grid: {
            hoverable: true,
            clickable: true,
            borderWidth: 3,
	    labelMargin: 3
        },
        
        series: {
            shadowSize: 1,
        }

//bars: {show:true, barWidth: 1000*60*60*0.5},
        
        //clickable:true,hoverable: true
});


           
            
        }
        });
    });
	
});
