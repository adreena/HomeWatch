
function draw_multiax_bar(widgetID,queryData){

    var elecData=queryData['elec'];
    var energyData=queryData['energy'];
    console.log(elecData);
    console.log(energyData);
    
    var options={
                credits: {
                       enabled: false
                    },
                exporting: { enabled: false },
                chart: {
                    renderTo: widgetID,
                    zoomType: 'xy'
                },
                title: {
                    text: ''
                },
                subtitle: {
                    text: ''
                },
                xAxis: [
                {   title: {
                        text:'2013',
                        style: {
                            color: '#89A54E'
                        }
                    },
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                        'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],

                }],
                yAxis: [{ // Primary yAxis
                    labels: {
                        formatter: function() {
                            return this.value; ;
                        },
                        style: {
                            color: '#89A54E'
                        }
                    },
                    title: {
                        text: 'Electricity (kWh)',
                        style: {
                            color: '#89A54E'
                        }
                    },
                    opposite: true
        
                }, { // Secondary yAxis
                    gridLineWidth: 0,
                    title: {
                        text: 'Energy (kJ)',
                        style: {
                            color: '#4572A7'
                        }
                    },
                    labels: {
                        formatter: function() {
                            return this.value;
                        },
                        style: {
                            color: '#4572A7'
                        }
                    }
        
                }],
                tooltip: {
                    shared: true
                },
                legend: {
                    enabled: true,
                    shadow: true,
                    backgroundColor: '#FFFFFF'
                },
                series: [{
                    name: 'Energy',
                    color: '#4572A7',
                    type: 'column',
                    yAxis: 1,
                    data: energyData//[49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4],
                    
        
                },{
                    name: 'Electricity',
                    color: '#89A54E',
                    type: 'column',
                    data: elecData//[7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6],
                    
                },]
            };

    var outputChart= new Highcharts.Chart(options);    
}