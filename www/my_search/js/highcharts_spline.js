function draw_spline(id,queryData){

    var yTitle=queryData['yTitle'];
    var categories=queryData['categories'];
    var dataSeries=[]; 
   /* var dataS=[{
                        name: 'Tokyo',
                        data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 23.3, 18.3, 13.9, 9.6,2]
            
                    }, {
                        name: 'London',
                        data: [3.9, 4.2, 5.7, 8.5, 11.9, 15.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8]
                    }];*/
    
    
    
    for(var d in queryData['query']){
            console.log(d,queryData['query'][d]);
            var obj=new Object();
            obj.name=d;
            obj.data=queryData['query'][d];
            console.log(queryData['query'][d]);
            dataSeries.push(obj);
    }
    console.log("Dataseries\n",dataSeries);
           
    $(function () {
        $(id).highcharts({
                    credits: {
                         enabled: false
                     },
                    exporting: { 
                        enabled: false 
                    },
                    chart: {
                        type: 'spline'
                    },
                    title: {
                        text: queryData['source']
                    },
                    
                    xAxis: {
                        categories: categories
                    },
                    yAxis: {
                        title: {
                            text: yTitle
                        },
                        labels: {
                            formatter: function() {
                                return this.value
                            }
                        },
                        min: 0
                    },
                    tooltip: {
                        crosshairs: true,
                        shared: true
                    },
                    plotOptions: {
                        series: {
                                cursor: 'pointer',
                                point: {
                                    events: {
                                        click: function() {
                                            alert ('Apt: '+ this.category +', value: '+ this.y);
                                        }
                                    }
                                }
                            },
                        spline: {
                            marker: {
                                radius: 4,
                                lineColor: '#666666',
                                lineWidth: 1
                            }
                        }
                        
                    },
                    series: dataSeries
                });
        });   
}
function sumTwo(sumList,newList){
   
    for(var i =0;i<sumList.length; i++){
        var temp=newList[i];
        if(temp>=0){
            sumList[i]+=temp;
            var parts=(sumList[i].toString()).split(".");
            
            if(parts.length>1){
                var dec=parts[1].substr(0,2);
                
                var alltogether=parts[0]+"."+dec;
                
                sumList[i]=parseFloat(alltogether); //limiting the float to 2 decimal point
            }
        }
    }
    return sumList;
}
function calcAvg(sumList){
    for(var i =0;i<sumList.length; i++){
        sumList[i]=(sumList[i]/12);

        var parts=(sumList[i].toString()).split(".");
            
            if(parts.length>1){
                var dec=parts[1].substr(0,2);
                
                var alltogether=parts[0]+"."+dec;
                
                sumList[i]=parseFloat(alltogether); //limiting the float to 2 decimal point
            }
      }  
    return sumList;
}   