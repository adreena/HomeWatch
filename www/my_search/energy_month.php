<!DOCTYPE html >
<head>
    <title>D3 Sandbox</title>
</head>
<body>

    <script type="text/javascript" src="http://d3js.org/d3.v3.min.js"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script type="text/javascript" src="js/d3_calendar.js" ></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script type="text/javascript" src="http://code.highcharts.com/highcharts.js"></script>
    <script type="text/javascript" src="http://code.highcharts.com/modules/exporting.js"></script>
    <script src="js/test-pie.js"></script>
        <h3> D3 Calendar</h3>
    <div>
        <button id="back" name="back" ><</button>
        <button id="forward" name="forward" >></button>
        <label id="currentMonth">May 2013</label>
    </div>

    <div id="chart"></div>
    <script type="text/javascript"> draw_clanedar();</script>
    <script> 
                var results= <?php  include('my_process.php'); ?>;
                var info={"title":"Heatpump Comparison"};
                draw_pie(info,results); 
                console.log( "*******");
    </script>
    


</body>
</html>