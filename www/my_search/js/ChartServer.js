var chartserver=require('./keepChart');
var info= {
	name:"hi"
};
var csTest=chartserver(info);
console.log(csTest.getInfo());