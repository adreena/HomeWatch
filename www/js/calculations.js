/**
 * Calculations page!
 */

/*jslint browser: true, devel: true, white: true, indent: 4, maxlen: 120 */
/*global require */

/* JSLint unused variable report:
 * SCROLL_SPEED '[module]' 20,
 * SCROLL_OPTIONS * '[module]' 20,
 * button 'calculate' 26,
 * data 'always' 60,
 * selectedVal 'showhideEnergiesOnChange' 69
 */
require([
    'jquery',
    'vendor/jquery.jdpicker',
    'vendor/jquery.scrollTo-min'],

    function ($) {
        "use strict";

        var SCROLL_SPEED = 200,
            SCROLL_OPTIONS = {offset: -100};

    window.calculate = function (button) {
        var calcDD = $('#calculations').get(0),
            calcIndex = calcDD.selectedIndex,
            calculation = calcDD.options[calcIndex].value,
            calcName = calcDD.options[calcIndex].innerHTML,

            energyDD = $('#energies').get(0),
            energyIndex = energyDD.selectedIndex,
            energy = energyDD.options[energyIndex].value,
            energyName = energyDD.options[energyIndex].innerHTML,

            startdate = $('#startdate').val(),
            enddate = $('#enddate').val(),

            starthour = $('#starthour').val(),
            endhour = $('#endhour').val(),

            calculateButton = $('#calculateButton').get(0);

        calculateButton.disabled = true;
        calculateButton.innerHTML = "calculating...";

        $.post('/engineer/calculate.php', {
            name: calcName,
            energyname: energyName,
            calculation: calculation,
            energy: energy,
            startdate: startdate,
            enddate: enddate,
            starthour: starthour,
            endhour: endhour
        })
            .done(function(data) {
                var resultsdiv = $('#results').get(0);
                resultsdiv.innerHTML = "Result: " + data + '<br />';
            })
            .fail(function(data) {
                alert("Error Doing Calculations: " + data.statusText);
            })
            .always(function(data) {
                calculateButton.disabled = false;
                calculateButton.innerHTML = "Calculate";
            });


        return false;
    };

    window.showhideEnergiesOnChange = function (dropdown) {
        var index = dropdown.selectedIndex,
            selectedVal = dropdown[index].text,
            energiesDD = $('#energies').get(0);

        if (dropdown[index].value === "eq1") {
            energiesDD.disabled = false;
        } else {
            energiesDD.disabled = true;
        }
    };

    /* On document ready... */
    $(function () {
        var datePickers = $('#startdate, #enddate');

        /* jdPicker gives hidden type inputs a full calendar display. */
        datePickers.attr('type', 'hidden');

        /* Bind the date selectors with jdPicker. */
        datePickers.jdPicker({
            date_format: 'YYYY-mm-dd',
            start_of_week: 0
        });
    });

});
