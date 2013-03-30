
// TODO: use require js
// Requires:
// - jquery-ui
// - jquery.scrollto

var SCROLL_SPEED = 200;
var SCROLL_OPTIONS = {offset: -100}

$(window).load(function() {

}); // window load


function calculate(button) {
    var calcDD = document.getElementById('calculations');
    var calcIndex = calcDD.selectedIndex;
    var calculation = calcDD.options[calcIndex].value;

    var energyDD = document.getElementById('energies');
    var energyIndex = energyDD.selectedIndex;
    var energy = energyDD.options[energyIndex].value;

    var startdate = document.getElementById('startdate').value;
    var enddate = document.getElementById('enddate').value;

    $.post('/engineer/calculate.php',
        {
            calculation: calculation,
            energy: energy,
            startdate: startdate,
            enddate: enddate
        }
    )
    .done(function(data) {
        alert(data);
    })
    .fail(function(data) {
        alert("Error Doing Calculations: " + data.statusText);
    });

    return false;
}

function showhideEnergiesOnChange(dropdown) {
    var index = dropdown.selectedIndex;
    var selectedVal = dropdown[index].text;
    var energiesDD = document.getElementById('energies');

    if (dropdown[index].value == "eq1") {
        energiesDD.disabled = false;
    }
    else {
        energiesDD.disabled = true;
    }
}
