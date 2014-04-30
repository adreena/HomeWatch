
function draw_bar_column(bar_info,input,container_id){

                    var chartTitle=bar_info["title"];
                    var xTitle=bar_info["xTitle"];
                    var yTitle=bar_info["yTitle"];
                    var xCategories=[];
                    var data_series=[];
                    var drilldown_series=[];

                    for( var key in input){
                        var temp=new Object();
                        var name=key;
                        var values;
                        for(var d in input[key]){
                            if(xCategories.indexOf(d)==-1){ //not allowing duplicates
                                xCategories.push(d);
                            }
                            values=parseFloat(input[key][d]);
                            
                        }
                        temp.name="Apt. "+name;
                        temp.y=values;
                        temp.drilldown=name;
                        data_series.push(temp);
                        //console.log(values); 
                    }
                    
                   

                    //using the same input for now
                    for( var key in input){
                        var temp=new Object();
                        var name=key;
                        var values=[];
                        for(var d in input[key]){
                            if(xCategories.indexOf(d)==-1){ //not allowing duplicates
                                xCategories.push(d);
                            }
                            values.push(d);
                            values.push(parseFloat(input[key][d]));
                            
                        }
                        temp.id="1";
                        temp.data=values;
                        drilldown_series.push(temp);
                        //console.log(values); 
                    }

                    
                drilldown_series=[
                                  { id: '1',data: [ ['Heat Pump  1', 4],['Heat Pump3', 2] ]}, 
                                  { id: '3', data: [['Heat Pump 2', 4],['Heat Pump 3', 2]] }
                                  ];
                


                $(function () {
                    // setOptions colors added by me becuase there was a bug on reloading the page and the colors of objects
                    Highcharts.setOptions({ colors: ['#2f7ed8', '#0d233a','#8bbc21','#910000','#1aadce','#492970','#f28f43','#77a1e5','#c42525','#a6c96a']});
                        $(container_id).highcharts({
                            chart: {
                                type: 'column'
                            },
                            title: {
                                text: chartTitle
                            },
                            subtitle: {
                                text: ''
                            },
                            xAxis: {
                                categories:['March']
                                
                            },
                            yAxis: {
                                min: 0,
                                title: {
                                    text: "Energy (kJ)"
                                }
                            },
                            tooltip: {
                                pointFormat: '<b>{series.name}</b> <br /> value: <b>{point.y}</b> <br /></b>'
                            },
                            legend: {
                                    enabled: false
                                },

                            plotOptions: {
                                    series: {
                                        borderWidth: 0,
                                    }
                                },
                            series: [ {
                                      colorByPoint:true,
                                      data: data_series
                                     } ],
                            drilldown:{
                                series: drilldown_series
                            }
                        });
                    });
}