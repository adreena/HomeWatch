
<!DOCTYPE html>
<html>
    <head>
            <meta http-equiv="content-type" content="text/html; charset=UTF-8">
            <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
            <script type="text/javascript" src="http://code.highcharts.com/highcharts.js"></script>
            <script type="text/javascript" src="http://code.highcharts.com/modules/exporting.js"></script>
            <script src="js/highcharts_pie.js"></script>
            <script src="js/highcharts_bar_column.js"></script>
            <script src="http://code.highcharts.com/modules/drilldown.js"></script>
            <?php  include('my_process.php'); ?>
    </head>
    <body>

            <div id="bar-container" style="width:100%; height:400px;"></div>
           
            <script> 
                
                var results=<?php proc("energy"); ?>; 
                var info={"title":"Energy Comparison"};
                draw_bar_column(info,results); 
                console.log(results);
            </script>
            



        </body>
</html>
