
<!DOCTYPE html>
<html>
    <head>
            <meta http-equiv="content-type" content="text/html; charset=UTF-8">
            <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
            <script type="text/javascript" src="http://code.highcharts.com/highcharts.js"></script>
            <script type="text/javascript" src="http://code.highcharts.com/modules/exporting.js"></script>
            <script src="js/highcharts_pie.js"></script>
            <script src="js/highcharts_bar_column.js"></script>

    </head>
    <body>

            <div id="pie-container" style="width:100%; height:400px;"></div>
            <script> 
                var results= <?php  include('my_process.php'); ?>;
                var info={"title":"Heatpump Comparison"};
                draw_pie(info,results); 
                console.log( "");
            </script>

            <!--
            <div id="bar-container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
            <script> 
                draw_bar_column(); 
               
            </script>
            -->



        </body>
</html>
