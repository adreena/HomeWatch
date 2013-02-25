<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="style.css" />
<title>SmartHome - Score Board</title>
</head>
 
<body>
<div id="container">
<div id="header">
<h1>SmartHome</h1>
<h2>Welcome user!</h2>
<h3>
<a href="#">preferences</a> | <a href="#">logout</a>
<h3>
</div>
<div id="menu">
<br /><br /><hr>
</div>
 
<div id="leftmenu">
 
<div id="leftmenu_top"></div>
 
<div id="leftmenu_main">
<ul>
<li><a href="#">Home</a></li>
<li><a href="./achievements.php">Achievements</a></li>
<li><a href="#">Scoreboard</a></li>
<li><a href="#">Advice</a></li>
<li><a href="#">History</a></li>
</ul>
</div>
<div id="leftmenu_bottom"></div>
</div>
<div id="content">
<div id="content_top"></div>
<div id="content_main">
<h2>Score Board</h2>
<p>&nbsp;</p>
<p>&nbsp;</p>

<div id="tableA"></div> 
<div id="tableB"></div> 
<div id="tableC"></div> 
<div id="tableD"></div> 

<script type="text/javascript"> 
var entryNames = ["ID","Heating","Water","Waffles"];
var currentScores = [1111, 10, 100, 0];
var entries = [["First",1000,0],["Second",900,100],["Third",800,200],["Fourth",700,300],["Fifth",600,400],["Sixth",500,500],["Seventh",400,600],["Eighth",300,700],["Ninth",200,800]];

displayTables()

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
	var str = '<table style="display: inline-block; border: 5px solid; float: left; width: 33%; ">'; 
    str += '<tr><th>Id</th><th>' + interestLabel + '</th></tr>';
    for ( var i=0; i< entries.length; i++){
        str += '<tr><td>' + entries[i][0] + '</td><td>' + entries[i][interestEntry] + '</td></tr>';
    }
    str += '</table>';
    return str;    
}

function displayTables() {
	fillEntries();

	var existingDiv = document.getElementById('content_main');
	existingDiv.innerHTML += CreateCurrentScoreTable();
	existingDiv.innerHTML += "<p>&nbsp;</p><p>&nbsp;</p>"
	SortEntries(1);
	existingDiv.innerHTML += CreateRankingTable(entryNames[1],1);
	SortEntries(2);
	existingDiv.innerHTML += CreateRankingTable(entryNames[2],2);
	SortEntries(3);
	existingDiv.innerHTML += CreateRankingTable(entryNames[3],1);
	
	//setTimeout("displayTables()",3000);
}
</script>
 
<p>&nbsp;</p>
</div>
<div id="content_bottom"></div>
</div>
</div>
</body>
</html>