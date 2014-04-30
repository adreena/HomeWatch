/* Render resident home. */

/* This one requires A LOT of refactoring, so I didn't really bother. Just
 * changed a few things.
 * Might want to look at Underscore template system:
 *
 *    http://underscorejs.org/#template
 *
 */

/* Hard-coded data for testing. */
var entryNames = ["ID","Heating","Water","Waffles"];
var currentScores = [1111, 10, 100, 0];
var entries = [["First",1000,0],["Second",900,100],["Third",800,200],["Fourth",700,300],["Fifth",600,400],["Sixth",500,500],["Seventh",400,600],["Eighth",300,700],["Ninth",200,800]];


function CreateCurrentScoreTable() {
	var str = '<table><tr>'; 
    for ( var i=0; i< entryNames.length; i++){
        str += '<th>' + entryNames[i] + '</th>';
    }
	str += "</tr><tr>"
	for ( var i=0; i< currentScores.length; i++){
        str += '<td>' + currentScores[i] + '</td>';
    }
    str += '</tr></table>';
    return str;
}

function fillEntries() {
}

function SortEntries(array) {
}

function CreateSimpleScoreboard()
{
	var totals = [];
	
	for(var i = 0; i < entries.length; i++)
	{
		totals.push([entries[i][0],entries[i][1]+entries[i][2]]);
	}
	
	SortEntries(totals);
	
	var str = '<table class="scoreboard">'; 
    str += '<tr><th>Id</th><th>' + "Overall Score" + '</th></tr>';
    for ( var i=0; i < totals.length; i++){
        str += '<tr><td>' + totals[i][0] + '</td><td>' + totals[i][1] + '</td></tr>';
    }
    str += '</table>';
    return str; 
}

function CreateAchievementList()
{
	var str = '<div>';
	
	for(var i = 0; i < 5; i++)
	{
		str += '<img src="http://findicons.com/files/icons/1197/agua/128/home_badge.png"/>';
	}
	
	str += '</div>';
		
	return str;
}

function displayAchievements()
{
	var existingDiv = document.getElementById('latest5Achievements');
	existingDiv.innerHTML += "<p>&nbsp;</p>";
	existingDiv.innerHTML += CreateAchievementList();
	existingDiv.innerHTML += "<p>&nbsp;</p>";
}

function displayScoreboard()
{
	var existingDiv = document.getElementById('partialScoreboard');
	existingDiv.innerHTML += "<p>&nbsp;</p>";
	existingDiv.innerHTML += CreateSimpleScoreboard();
	existingDiv.innerHTML += "<p>&nbsp;</p>";
}

function displayStats()
{
	var existingDiv = document.getElementById('quickStats');
	existingDiv.innerHTML += "<p>&nbsp;</p>";
	existingDiv.innerHTML += CreateCurrentScoreTable();
	existingDiv.innerHTML += "<p>&nbsp;</p>";
}

