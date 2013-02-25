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

function SortEntries(column) {
}

function CreateRankingTable(interestLabel, interestEntry) {
	var str = '<table class="scoreboard">'; 
    str += '<tr><th>Id</th><th>' + interestLabel + '</th></tr>';
    for ( var i=0; i< entries.length; i++){
        str += '<tr><td>' + entries[i][0] + '</td><td>' + entries[i][interestEntry] + '</td></tr>';
    }
    str += '</table>';
    return str;    
}

function displayTables() {
	fillEntries();

	var existingDiv = document.getElementById('scoreboards');
	existingDiv.innerHTML += CreateCurrentScoreTable();
	existingDiv.innerHTML += "<p>&nbsp;</p><p>&nbsp;</p>";
	SortEntries(1);
	existingDiv.innerHTML += CreateRankingTable(entryNames[1],1);
	SortEntries(2);
	existingDiv.innerHTML += CreateRankingTable(entryNames[2],2);
	SortEntries(3);
	existingDiv.innerHTML += CreateRankingTable(entryNames[3],1);
	
	//setTimeout("displayTables()",3000);
}

