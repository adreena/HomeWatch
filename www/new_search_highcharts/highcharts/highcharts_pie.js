
/*
** draw_pie: This function is called from calendar-pie or BAS screen pies.
** if widgetID== cal-pie , it draws pie inside the calendar grid
** else: draws pies related to BAS screen
** variables:
** data: contains data for pies
** widgetID: widgetID of container
** measure: measurement such as kWh
** extraInfo: information about threshold boundaries for coloring purposes 
*/
Highcharts.setOptions({
     colors: ['#93C572','#FF4040', '#434348', '#F4A460', '#8085e9', '#f15c80', '#e4d354', '#8085e8', '#33CC33', '#8d4653', '#91e8e1', '#0066FF']
 });
function draw_pie(data,widgetID,measure,extraInfo){

	var energyColors={"Solar":'#8bbc21',"DWHR":'#0d233a',"Geothermal + DWHR":'#FF9933', "Boiler 1":'#910000', "Boiler 2":'#1aadce'};
	var elecColors={"HP1":'#80699B',"HP2":'#0d233a',"HP3":'#FF9933',"HP4":'#910000',"P11":'#1aadce',"P12":'#492970'};
	var aptColors={"Apt.1":'#93C572',"Apt.2":'#FF4040', "Apt.3":'#434348', "Apt.4":'#F4A460', "Apt.5":'#8085e9', "Apt.6":'#f15c80', "Apt.7":'#e4d354', "Apt.8":'#8085e8', "Apt.9":'#33CC33', "Apt.10":'#8d4653', "Apt.11":'#91e8e1', "Apt.12":'#0066FF'};

	var pie;
	if(widgetID==='cal-pie'){
		for(var item in data){
		 	widgetID="#"+item+"-pie";
		 	var dObj=[];
		 	d=data[item];

		 	for(val in d){
		 		obj=new Object();
		 		obj.name=d[val][0];
		 		obj.y=d[val][1];
		 		if(extraInfo != 'no_threshold'){
		 			var temp=extraInfo.split(",");
		 		 	var min=parseFloat(temp[0]);
		 		 	var max=parseFloat(temp[1]);
		 		 	console.log("***",min,max);
		 		 	if(min<=max && min>=0 && max>0){
				 		if(obj.y < min || obj.y > max)
				 			obj.color='#B8CFE6';
				 		else{
				 			obj.color=aptColors[obj.name];
				 			console.log("**",obj.name,obj.color);
				 			}
				 		}
				}
		 		dObj.push(obj);
		 	}
		 	pie=draw_helper(widgetID,dObj,measure,extraInfo);   
		}
	}
	else{	
			var dObj=[];
			if(extraInfo === "BAS-elec"){
				var dObj=[];
				for(var item in data){
					obj=new Object();
				 	obj.name=data[item][0];
				 	obj.y=data[item][1];
			 		obj.color=elecColors[obj.name];
			 		dObj.push(obj);
				 	}
			 	pie=draw_helper("#"+widgetID,dObj,measure,extraInfo);

		 	}
		 	else if(extraInfo === "BAS-energy"){
				var dObj=[];
				for(var item in data){
					obj=new Object();
				 	obj.name=data[item][0];
				 	obj.y=data[item][1];
			 		obj.color=energyColors[obj.name];
			 		dObj.push(obj);
				 	}
			 	pie=draw_helper("#"+widgetID,dObj,measure,extraInfo);

		 	}
		 	else
		 		pie=draw_helper("#"+widgetID,data,measure,extraInfo);
	 }
}

/*
** called by draw_pie
** sets options of pie based on input arguments
** returns pie chart
*/

function draw_helper(widgetID,data,measure,extraInfo){
	widgetID=widgetID.split("#")[1];
	
	var pie=new Highcharts.Chart({
			    	title: {
					   text: ''
					},
					credits: {
					      enabled: false
					  },
			        chart: {
			        	//making the chart transparent
			            renderTo: widgetID,
			            defaultSeriesType: 'pie',
	             		backgroundColor:'rgba(255, 255, 255, 0.1)',
	    
			            plotBorderWidth: null,
			            plotShadow: false
			        },
			        tooltip: {
			    	    pointFormat: '{point.y:.2f} '+measure//point.y
			        },
			        plotOptions: {
			            pie: {
			                
			                dataLabels: { //removed the lanels
						                enabled: false,
						            },
			            },
			        series:{
				        allowPointSelect: true,
		                cursor:'pointer',
		                point:{ 
				            	/*
				            	events: {
				                    click: function(event) {
				                        alert(this.name+" "+this.y+ '\n');
				                    }
				                }*/
				            }
				        }
			        },
			        exporting: { enabled: false },
			        series: [{
			            type: 'pie',
			            data: data
			        }]
			    
			});
	return pie;
			}