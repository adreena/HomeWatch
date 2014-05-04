


function draw_stack_bar(widgetID,queryData,categories,yAx_label){
    //var dataSeries=[ {name: 'apartment',data: [5, 3, 4, 7, 2]} , {name: 'Jane',data: [2, 2, 3, 2, 1]}, {name: 'Joe',data: [3, 4, 4, 2, 5]}];

  // var categories=categories;//["<a href='#'>Apt.1*</a>","<a href='#'>Apt.2</a>","<a href='#'>Apt.3</a>","<a href='#'>Apt.4</a>","<a href='#'>Apt.5</a>","<a href='#'>Apt.6</a>","<a href='#'>Apt.7</a>","<a href='#'>Apt.8</a>","<a href='#'>Apt.9</a>","<a href='#'>Apt.10</a>","<a href='#'>Apt.11</a>","<a href='#'>Apt.12</a>"];//categories;

   var dataSeries =makeObject(queryData);
    console.log("stackBar data:",dataSeries);
    draw_stackbar_helper(widgetID,dataSeries,categories,yAx_label);
}


function makeObject(stacks){
    var finalStacks=[];
    for (var item in stacks){
        var obj=new Object();
        obj.name=item;
        obj.data=stacks[item];
        finalStacks.push(obj);

    }
    return finalStacks;
}



function draw_stackbar_helper(widgetID,dataSeries,categories,yAx_label){
    $(function () {
            $(widgetID).highcharts({
                credits: {
                   enabled: false
                },
                exporting: { enabled: false },
                chart: {
                    type: 'column',
                    renderTo: 'histogram', defaultSeriesType: 'bar',
                                backgroundColor:'rgba(255, 255, 255, 0.1)',
                
                                plotBorderWidth: null,
                                plotShadow: false
                },
                title: {
                    text: ''
                },
                xAxis: {

                    categories: categories ,
                    labels: {
                       // if(widgetID !='apt-dashboard'){
                            formatter: function () {
                                return '<a href="apartment.php?val=' + this.value + '">'+this.value+'</a>';
                            },
                            useHTML: true
                       // }
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: yAx_label
                    },
                    stackLabels: {
                        enabled: true,
                        style: {
                            fontWeight: 'bold',
                            color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                        }
                    }
                },
                tooltip: {
                    valueDecimals:2,
                    formatter: function() {
                        return  '<b>'+this.series.name +':</b><br> '+ this.y ;
                    }
                },
                plotOptions: {
                    column: {
                        stacking: 'normal',
                        dataLabels: {
                            enabled: false,
                            color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                            style: {
                                textShadow: '0 0 3px black, 0 0 3px black'
                            }
                        }
                    }
                },
                series: dataSeries
            });
        });
        
}