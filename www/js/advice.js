/* Render scoreboards. */

/* This one requires A LOT of refactoring, so I didn't really bother. Just
 * changed a few things.
 * Might want to look at Underscore template system:
 *
 *    http://underscorejs.org/#template
 *
 */

/* Hard-coded data for testing. */
var entryNames = ["ID","Heating","Water","Waffles"];
var currentScores = [1111, 9, 100, 0];
var suggestions = [[1, 1, 1, 1],["blah", "megablah", "antiblah", "superantiblah"],["tu", "tutu", "wabble", "waaaaaabble"],["syrup", "sprinkles", "doing the makarena", "liposuction"]];

function getStats()
{
}

function fillStats()
{			
	var str = '<h2>Current Stats</h2><table><tr>'; 
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

function getSuggestions()
{
}

function fillSuggestions()
{
	var str = '<h2>Suggestions</h2></div><table><tr>';
	
	if(currentScores[1] > 100) str += '<tr><td>' + entryNames[1] + ' score is way too high! Try doing ' + suggestions[1][1] + '.' + '</td></tr>';
	else if(currentScores[1] > 75) str += '<tr><td>' + entryNames[1] + ' score is a bit too high. Try doing ' + suggestions[1][0] + '.' + '</td></tr>';
	else if (currentScores[1] < 25) str += '<tr><td>' + entryNames[1] + ' score is way too low. Try doing ' + suggestions[1][3] + '.' + '</td></tr>';
	else if (currentScores[1] < 50) str += '<tr><td>' + entryNames[1] + ' score is way too low. Try doing ' + suggestions[1][2] + '.' + '</td></tr>';
	else str += '<tr><td>' + entryNames[1] + ' score is right on the money. Good job!' + '</tr></td>';
	
	if(currentScores[2] > 100) str += '<tr><td>' + entryNames[2] + ' score is way too high! Try doing ' + suggestions[2][1] + '.' + '</td></tr>';
	else if(currentScores[2] > 75) str += '<tr><td>' + entryNames[2] + ' score is a bit too high. Try doing ' + suggestions[2][0] + '.' + '</td></tr>';
	else if (currentScores[2] < 25) str += '<tr><td>' + entryNames[2] + ' score is way too low. Try doing ' + suggestions[2][3] + '.' + '</td></tr>';
	else if (currentScores[2] < 50) str += '<tr><td>' + entryNames[2] + ' score is way too low. Try doing ' + suggestions[2][2] + '.' + '</td></tr>';
	else str += '<tr><td>' + entryNames[2] + ' score is right on the money. Good job!' + '</td></tr>';
	
	if(currentScores[3] > 100) str += '<tr><td>' + entryNames[3] + ' score is way too high! Try doing ' + suggestions[3][1] + '.' + '</td></tr>';
	else if(currentScores[3] > 75) str += '<tr><td>' + entryNames[3] + ' score is a bit too high. Try doing ' + suggestions[3][0] + '.' + '</td></tr>';
	else if (currentScores[3] < 25) str += '<tr><td>' + entryNames[3] + ' score is way too low. Try doing ' + suggestions[3][3] + '.' + '</td></tr>';
	else if (currentScores[3] < 50) str += '<tr><td>' + entryNames[3] + ' score is way too low. Try doing ' + suggestions[3][2] + '.' + '</td></tr>';
	else str += '<tr><td>' + entryNames[3] + ' score is right on the money. Good job!' + '</td>';
	
	str += '</tr></table>';
	return str;
}

function displayTables()
{
	getStats();
	getSuggestions();

	var existingDiv = document.getElementById('advice_tables');
	existingDiv.innerHTML += fillStats();
	existingDiv.innerHTML += fillSuggestions();
}

