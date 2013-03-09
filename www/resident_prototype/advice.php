<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="style.css" />
	<title>SmartHome - Score Board</title>
</head>

<body>

    <div id="container">

	<?php require_once 'sidebar.php'?>
	
	<div id="content_top"></div>
		<div id="content_main"></div>
	</div>
	
	<script type="text/javascript">
	
		var entryNames = ["ID","Heating","Water","Waffles"];
		var currentScores = [1111, 9, 100, 0];
		var suggestions = [[1, 1, 1, 1],["blah", "megablah", "antiblah", "superantiblah"],["tu", "tutu", "wabble", "waaaaaabble"],["syrup", "sprinkles", "doing the makarena", "liposuction"]];
		
		displayTables();
		
		function getStats()
		{
		}
		
		function fillStats()
		{			
			var str = '<h2>Current Stats</h2><p>&nbsp;</p><p>&nbsp;</p><table><tr>'; 
			for ( var i=0; i< entryNames.length; i++){
			 str += '<th>' + entryNames[i] + '</th>';
			}
			str += "</tr><tr>"
			for ( var i=0; i< currentScores.length; i++){
				str += '<td>' + currentScores[i] + '</td>';
			}
			str += '</tr></table><p>&nbsp;</p><p>&nbsp;</p>';
			return str;
		}
		
		function getSuggestions()
		{
		}
		
		function fillSuggestions()
		{
			var str = '<h2>Suggestions</h2></div><p>&nbsp;</p><p>&nbsp;</p><table><tr>';
			
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
			
			str += '</tr></table><p>&nbsp;</p><p>&nbsp;</p>';
			return str;
		}
		
		function displayTables()
		{
			getStats();
			getSuggestions();
		
			var existingDiv = document.getElementById('content_main');
			existingDiv.innerHTML += fillStats();
			existingDiv.innerHTML += fillSuggestions();
		}
			
	</script>

	<div id="content">
	</div>
</body>

</html>