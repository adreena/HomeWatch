
<!DOCTYPE html>
<html>
    <head>
            <meta http-equiv="content-type" content="text/html; charset=UTF-8">
            <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
            <script type="text/javascript" src="http://code.highcharts.com/highcharts.js"></script>
            <script type="text/javascript" src="http://code.highcharts.com/modules/exporting.js"></script>
            <script src="js/highcharts_bar.js"></script>

    </head>
    <body>

            <div id="container" style="width:100%; height:400px;"></div>
            <script> 
                //fetch data
                var results= <?php  include('my_process.php'); ?>;
                
                
                //display data
                //console.log(results);
                var bar_info={"title":"Relative Humidity", "xTitle":"Date", "yTitle":"value"};
                draw_bar(bar_info,results); 

            </script>
            
        </body>
</html>
