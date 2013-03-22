<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>Search Page</title>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css" />
<link rel="stylesheet" href="jSearch.css" />

<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.4.4/underscore-min.js"></script>
<!--script src="/js/vendor/jquery-1.9.1.js"></script>
<script src="/js/vendor/underscore-min.js"></script-->
<script src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>
<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="flot/excanvas.min.js"></script><![endif]-->
<script src="/js/spiffy/spiffy.min.js"></script>
<script src="/js/flot/jquery.flot.js"></script>
<script src="/js/flot/jquery.flot.time.js"></script>
<script src="/js/flot-orderbars/jquery.flot.orderBars.js"></script><!--MIT licensed-->
<script src="/js/flot-axislabels/jquery.flot.axislabels.js"></script><!-- GNU -->
<script>
    // TEMPORARY: Here whilst we transition to requireJS.
    function require(_libraries, module) {
        module(jQuery, _);
    }
</script>
<script src="/js/jSearch.js"></script>
</head>

<body>
    <div id="wrapper">
        <div id="menu_content">
        <form class="sensor-search">
            <ul id="menu" class="submenus">
            <li><a class="collapsed">Sensors</a>
            <ul>
                <div id="sensors">
                <fieldset class="sensor-group1">
                    <legend>Air Data</legend>
                        <input type="checkbox" name="sensors[]" id="sensor1" value="CO2">
                <label for="sensor1">CO2</label>
                        <input type="checkbox" name="sensors[]" id="sensor2" value="Temp">
                <label for="sensor2">Temperature</label>
                <br>
                        <input type="checkbox" name="sensors[]" id="sensor3" value="RelHum">
                <label for="sensor3">Rel. Humidity</label>
                </fieldset>

                <fieldset class="sensor-group1">
                <legend>Water Data</legend>
                     <input type="checkbox" name="sensors[]" id="sensor4" value="HotWater">
                <label for="sensor4">Hot Water</label>
                        <input type="checkbox" name="sensors[]" id="sensor5" value="TotalWater">
                <label for="sensor5">Total Water</label>
                </fieldset>

                    <fieldset class="sensor-group2">
                <legend>Heat Data</legend>
                <input type="checkbox" name="sensors[]" id="sensor6" value="Insulation">
                <label for="sensor6">Insulation</label>
                        <input type="checkbox" name="sensors[]" id="sensor7" value="Stud">
                <label for="sensor7">Stud</label>
                <br>
                        <input type="checkbox" name="sensors[]" id="sensor8" value="CurrentFlow">
                <label for="sensor8">Current Flow</label>
                        <input type="checkbox" name="sensors[]" id="sensor9" value="CurrentTemp">
                <label for="sensor9">Current Temp.</label>
                <br>
                        <input type="checkbox" name="sensors[]" id="sensor10" value="TotalMass">
                <label for="sensor10">Total Mass</label>
                        <input type="checkbox" name="sensors[]" id="sensor11" value="TotalEnergy">
                <label for="sensor11">Total Energy</label>
                <br>
                        <input type="checkbox" name="sensors[]" id="sensor12" value="TotalVol">
                <label for="sensor12">Total Volume</label>
                <br>
                        <input type="checkbox" class="checkAllHeat" value="select-all id="AllHeat">All Heat Data
                </fieldset>

                <fieldset>
                <legend>All Data</legend>
                     <input type="checkbox" class="checkAllSensors" value="select-all id="allData">All Sensor Data
                </fieldset>
                </div>
            </ul>
            </li>

            <li><a class="collapsed">Apartments</a>
            <ul>
            <div id="apartments">
                <fieldset class="apartment-group">
                    <legend>Unit Numbers</legend>
                        <input type="checkbox" name="apartments[]" value="unit1">
                <label for="unit1">Unit 1</label>
                   <input type="checkbox" name="apartments[]" value="unit2">
                <label for="unit2">Unit 2</label>
                <br>
                   <input type="checkbox" name="apartments[]" value="unit3">
                <label for="unit3">Unit 3</label>
                       <input type="checkbox" name="apartments[]" value="unit4">
                <label for="unit4">Unit 4</label>
                <br>
                   <input type="checkbox" name="apartments[]" value="unit5">
                <label for="unit5">Unit 5</label>
                       <input type="checkbox" name="apartments[]" value="unit6">
                <label for="unit5">Unit 6</label>
                <br>
                   <input type="checkbox" class="allApts" value="select-all" id="select-all">All Apartments
                </fieldset>
                </div>
                </ul>
            </li>

            <li>
                <a class="collapsed">Dates</a>
                <div>
                    <input type="text" class="datepicker" name="datepicker" placeholder="Enter Date">
                </div>
            </li>

            <li>
                <a class="collapsed">Graphs</a>
                <fieldset>
                    <legend>Graph Data</legend>
                    <ul id="graphs">
                      <li><label><input type="radio" name="charts" value="plainText">
                        Plain Text</label></li>
                      <li><label><input type="radio" name="charts" value="tabular">
                        Table</label></li>
                      <li><label><input type="radio" name="charts" value="pie">
                        Pie Chart</label></li>
                      <li><label><input type="radio" name="charts" value="histo">
                        Histogram</label></li>
                      <li><label><input checked type="radio" name="charts" value="line">
                        Line Graph</label></li>
                    </ul>
                </fieldset>
            </li>
          </ul>
          <input type="hidden" name="notest" value="true">
        </form>
        </div>

        <div id="icons">
            <div class="images">
                <input type="image" id="submitbutton" value="GenerateReport" src="search.png" alt="Generate Report">
                <br>
                <a href="/"><img id="homebutton" src="home.png" alt="return home"></a>
            </div>
        </div>

        <div id="results">
          <!-- These should be added dynamically. -->
          <div id="graph1"></div>
          <div id="graph2"></div>
        </div>

    </div>
</body>
</html>
