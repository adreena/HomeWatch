$(document).ready(function() {

    $(function() { 
        $("#datepicker").datepicker({dateFormat: 'yy-mm-dd'});
    });

 	
    $("#menu > li > a").click(function() {
        $(this).toggleClass("expanded").toggleClass("collapsed").parent().find('> ul').slideToggle("medium");
    });


    $('.checkAll').click(function() {
        $(this).parents('.check-group').children(':checkbox').not('.checkAll').prop('checked', this.checked);
    });

    $('.checkAll').parents('.check-group').children(':checkbox').not('.checkAll,:checked').click(function() {
        $(this).parents('.check-group').children('.checkAll').prop('checked', false);
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