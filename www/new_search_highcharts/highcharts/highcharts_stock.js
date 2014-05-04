/* chartsInfo: is for keeping options and names of different charts as "references" for sending through socket io 
** extremes: for keeping boundaries of each highstock    
*/

var outputChart;
var extremes;
var chartsInfo={seriesNames:[],seriesOptions:[],drawingInfo:{},queryData:''}; 

/* I am setting the colors to keep each source/apartment with same color in all charts pies & stocks */
var energyColors={"Solar":'#8bbc21',"DWHR":'#0d233a',"Geothermal + DWHR":'#FF9933', "Boiler 1":'#910000', "Boiler 2":'#1aadce'};
var elecColors={"Total_P1":'#2f7ed8',"Total_HP":'#77a1e5',"HP1":'#80699B',"HP2":'#0d233a',"HP3":'#FF9933',"HP4":'#910000',"P11":'#1aadce',"P12":'#492970'};
var aptColors={"Apt.1":'#93C572',"Apt.2":'#FF4040', "Apt.3":'#434348', "Apt.4":'#F4A460', "Apt.5":'#8085e9', "Apt.6":'#f15c80', "Apt.7":'#e4d354', "Apt.8":'#8085e8', "Apt.9":'#33CC33', "Apt.10":'#8d4653', "Apt.11":'#91e8e1', "Apt.12":'#0066FF'};

/*
** This function is called from outside to draw highStock in widget boxes
** ID : ID of widget box
** dataSeries : the first data series of an apartment/ or main-apartment-selector
** selectorID : Id of the apartment selector in widget's header
** newLine : name of the apartment
** queryID : query key to communicate with backend apartment.php/bas.php  
*/
function draw_stock(ID,dataSeries,selectorID,newLine,queryID){

	chartsInfo.seriesNames[selectorID]=[];
    chartsInfo.seriesOptions[selectorID]=[];

	var selectorName= $(selectorID).val();
	var dates = {'min': 0, 'max': 0};
	var minDate=0, maxDate=0,
        yAxisOptions = [],
        seriesCounter = 0,
        colors = Highcharts.getOptions().colors;
    var color;

    /* 1... Adding the first chart info & creating the chart ...*/
    if( chartsInfo.seriesNames[selectorID].indexOf(newLine)<0 && newLine !== null)
    	chartsInfo.seriesNames[selectorID].push(newLine);

    /* setting item color */
    if(energyColors[selectorName])
    	color=energyColors[selectorName];
    else if(elecColors[selectorName])
    	color=elecColors[selectorName];
    else
    	color=aptColors[selectorName];

    //console.log(selectorName,"\n***color***\n",color);
    chartsInfo.seriesOptions[selectorID].push( {
        name: newLine, 
        color: color,
        data: dataSeries
        });
    var firstdata=chartsInfo.seriesOptions[selectorID];
    var tempID=ID.split('#')[1];
    var options={
    	        chart: {
	            	renderTo: tempID,
                    zoomType: 'y'

                },
	            legend: {
			    	enabled: true,
			    	shadow: true
			    },
	           rangeSelector: {
	                selected: 0,
	            
		            buttons: [
		           		{
							type: 'week',
							count: 1,
							text: '1w'
						}, {
							type: 'month',
							count: 1,
							text: '1m'
						}, {
							type: 'month',
							count: 3,
							text: '3m'
						}, {
							type: 'year',
							count: 1,
							text: '1y'
						}, {
							type: 'all',
							text: 'All'
						}]
				},
	            yAxis: {
	                labels: {
	                    formatter: function() {
	                        return  this.value;
	                    }
	                },
	                plotLines: [{
	                    value: 0,
	                    width: 2,
	                    color: 'silver'
	                }]
	            },
	            
	            tooltip: {
	                pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> <br />',
	                valueDecimals: 2
	            },
	            
	            series: chartsInfo.seriesOptions[selectorID]
	            ,
	            credits: {
	                   enabled: false
	                },
	            exporting: { enabled: false },
			    xAxis: {
		          events: {
		    		setExtremes: function(e) {
		    			dates.min = e.min;
			    		dates.max = e.max;
		    			maxDate=Highcharts.dateFormat(null, e.max);
				    	minDate=Highcharts.dateFormat(null, e.min);
				    	//console.log("Min,Max : ",minDate,maxDate);
				    	
				    	

		    			if(queryID==="total-elec"){
				    			$('#hidden-elec-total').val('Start: '+ Highcharts.dateFormat(null, e.min) +
				    				' | End: '+ Highcharts.dateFormat(null, e.max));
				    			maxDate=Highcharts.dateFormat(null, e.max);
				    			minDate=Highcharts.dateFormat(null, e.min);
				    			var tempOutput=draw_stock_helper(ID,selectorID,queryID,minDate,maxDate,"",1,chartsInfo.seriesNames[selectorID]);
				    			
		    				}
		    			else if(queryID==="total-energy"){
				    			$('#hidden-energy-total').val('Start: '+ Highcharts.dateFormat(null, e.min) +
				    				' | End: '+ Highcharts.dateFormat(null, e.max));
				    			maxDate=Highcharts.dateFormat(null, e.max);
				    			minDate=Highcharts.dateFormat(null, e.min);
				    			var tempOutput=draw_stock_helper(ID,selectorID,queryID,minDate,maxDate,"",1,chartsInfo.seriesNames[selectorID]);

				    			//console.log("**MinMax**",maxDate,minDate);
		    				}
		    			
		    			}
	    			}
		        }
	            
	 };
	outputChart= new Highcharts.StockChart(options);
	/*..end 1..*/


	/* 2... This section handles PieCharts of BAS highStocks, Pie demonstrates values based on minDate and maxDate of highstock selector...*/
	minDate = $('input.highcharts-range-selector:eq(0)').val()+' 0:00:00';
	maxDate = $('input.highcharts-range-selector:eq(1)').val()+' 23:00:00';
	$('#hidden-elec-total').val('Start: '+ minDate+' | End: '+ maxDate);
	$('#hidden-energy-total').val('Start: '+ minDate+' | End: '+ maxDate);

	if(queryID==='total-elec'){
		var pieData=sendAjaxPie('pie-elec',minDate,maxDate);
		draw_pie(pieData['elec'],"elec-usage-pie",'kWh',"BAS-elec");
		draw_pie(pieData['cost'],"elec-cost-pie",'$',"BAS-elec");
		$('#total-cost-elec').text("Total Cost: "+pieData['totalCost'][0]+'$');
		$('#elec-total').text("Total Electricity: "+pieData['totalElec'][0]+'(kWh)');
	}else if(queryID==='total-energy'){
		var pieData=sendAjaxPie('pie-energy',minDate,maxDate);
		draw_pie(pieData['energy'],"energy-usage-pie",'kJ',"BAS-energy");
		draw_pie(pieData['cost'],"energy-cost-pie",'$',"BAS-energy");
		$('#total-cost-energy').text("Total Cost: "+pieData['totalCost'][0]+'$');
		$('#energy-total').text("Total Energy: "+pieData['totalEnergy'][0]+'(kJ)');
	}
	/*...end 2...*/
	
	//console.log(ID,dataSeries,selectorID,newLine,queryID);
	/* 3... for each widget, there is an apartment selector,
	 so by changing their selector a new apartment will be added to the highstock for comparison purposes
	 ...*/
	$(selectorID).off('change');
	$(selectorID).change( function(e){
        e.preventDefault();
		var boxTitle=$(this).data("query");		
		selectorName=$(selectorID).val();
		
		if(chartsInfo.seriesNames[selectorID].indexOf(selectorName)<0){
			var tChart=draw_stock_helper(ID,selectorID,queryID,minDate,maxDate,boxTitle,0,chartsInfo.seriesNames[selectorID]);			
			console.log("****",tChart);
			if(typeof(tChart.newLine) !== "undefined"){
    			chartsInfo.seriesOptions[selectorID].push(tChart.newLine);
    		}

    		//console.log("new seriesOptions:",chartsInfo.seriesOptions);
			//console.log("new seriesNames:",chartsInfo.seriesNames);
		}
	});			
   /*...end 3...*/
   	chartsInfo.queryData=$(selectorID).data("query");
   
	chartsInfo.drawingInfo={"queryID":queryID,"queryData":chartsInfo.queryData,"seriesNames":chartsInfo.seriesNames[selectorID]};
	dates = outputChart.xAxis[0].getExtremes();		
	return {'type':"StockChart", "options":options, "extremes":dates, "seriesOptions":chartsInfo.seriesOptions[selectorID], "drawingInfo": chartsInfo.drawingInfo };
}


/*
** draw_stock calls this function to apply changes on pies-BAS & add more apartment dataseries to a highstock.
** 
*/
function draw_stock_helper(ID,selectorID,queryID,minDate,maxDate,boxTitle,pieReload,seriesNames){
			var selectorName=$(selectorID).val();
			//console.log("here",ID,selectorID,queryID,minDate,maxDate,boxTitle,pieReload,seriesNames);
			// if pieReload==1 it means time-selector in BAS has changed, so pies must be updated
			if(pieReload===1){
				if(queryID==="total-elec"){
					var pieData=sendAjaxPie('pie-elec',minDate,maxDate);
					draw_pie(pieData['elec'],"elec-usage-pie",'kWh',"BAS-elec");
					draw_pie(pieData['cost'],"elec-cost-pie",'$',"BAS-elec");
					$('#total-cost-elec').text("Total Cost: "+pieData['totalCost'][0]+'$');
					$('#elec-total').text("Total Electricity: "+pieData['totalElec'][0]+'(kWh)');
				}
				else if(queryID==="total-energy"){
					var pieData=sendAjaxPie('pie-energy',minDate,maxDate);
					draw_pie(pieData['energy'],"energy-usage-pie",'kJ',"BAS-energy");
					draw_pie(pieData['cost'],"energy-cost-pie",'$',"BAS-energy");
					$('#total-cost-energy').text("Total Cost: "+pieData['totalCost'][0]+'$');
					$('#energy-total').text("Total Energy: "+pieData['totalEnergy'][0]+'(kJ)');
				}
			}

			// else pieReload==0 **************************************
			/* Adding new dataseries to current highstock */
			var chartRetrieved=$(ID).highcharts();
			var color;
			var energyMapping={"Energy1":"Solar","Energy2":"DWHR","Energy3":"Geothermal + DWHR","Energy4":"Solar + DWHR + Geothermal + Heat Pumps","Energy5":"Boiler 1","Energy6":"Boiler 2","Energy7":"Heating Consumption"};
			
			if(selectorName!==null && seriesNames.indexOf(selectorName)<0){
				if( selectorName!="Energy1" &&seriesNames.indexOf(energyMapping[selectorName])<0){
					dataSeries=sendAjaxStock(queryID,boxTitle,selectorName,queryID);
					//console.log("selectorName",selectorName);
					if(queryID==='total-elec'){
						dataSeries=dataSeries['result'][selectorName];
						color=elecColors[selectorName];
					    seriesNames.push(selectorName);
					}
					else if(queryID==='total-energy'){
						selectorName=energyMapping[selectorName];
						color=energyColors[selectorName];
						seriesNames.push(selectorName);
						dataSeries=dataSeries['result'][selectorName];
						
					}
					else {

					   color=aptColors[selectorName];
					   seriesNames.push(selectorName);
						}

					var newLine={
			            name: selectorName,
			         	color:color,
			            data: dataSeries
	            	};
					chartRetrieved.addSeries(newLine);
					
			   }
			}
		//console.log(newLine);
		return {"new":newLine};
}
		
function sendAjaxPie(consumption,startDate,endDate){

            var query=[];
            query[0]=consumption;
            tempMin=startDate.split(":")[0];
            tempMax=endDate.split(":")[0];
            query[1]=tempMin;
            query[2]=tempMax;
           // console.log("**MinMax**",tempMin,tempMax, minDate,maxDate);
           // elec_query[3]=source
           	var	tempURL="/HomeWatch/new_search_highcharts/bas.php";
           	
            var queryResult=$.parseJSON(
                $.ajax({
                 'async':false,
                 'global':false,
                  url: tempURL,
                  type:"get",
                  data:{id:query}
                 
                }).responseText);
            return queryResult;
}

function sendAjaxStock(consumption,source,apartment,queryID){

           var query=[];
           
           
           tempURL='';
           if(queryID=='apartment'){
           		tempURL="/HomeWatch/new_search_highcharts/electricity.php";
           		query[0]=consumption;
           		query[2]=apartment;
           		query[1]=source;
           		
           }else if(queryID==='total-elec' || queryID==='total-energy'){ // for BAS

           		tempURL="/HomeWatch/new_search_highcharts/bas.php";
           		query[0]=queryID;
           		query[1]=apartment;
           	}
            var queryResult=$.parseJSON(
                $.ajax({
                 'async':false,
                 'global':false,
                  url: tempURL,
                  type:"get",
                  data:{id:query}
                 
                }).responseText);
            return queryResult;
}
  