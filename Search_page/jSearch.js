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
            
            $("#results").html("CO2 values for Apt. 2: <br>");
            for(var key in result) {
                if(result.hasOwnProperty(key)) {
                    $("#results").append("hour: " + key + " value: " + result[key] + "<br>");
                }
            }
        }
        });
    });	
});