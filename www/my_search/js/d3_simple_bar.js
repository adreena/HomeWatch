
//<?php include("my_process.php");?>
function drawChart(data){
		var x = d3.scale.linear();
		x.domain([0, d3.max(data)]);
		x.range([0, d3.max(data)*10]);


		var chart=d3.select(".chart")
					.selectAll("div")
						.data(data)
						.enter().append("div")
							.style("width", function(d) { return d*10 + "px"; })
							.text(function(d) { return d; })
							;

}