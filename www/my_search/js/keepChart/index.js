
//Example 1,2

var http= require('http');
var url = require('url'); //for parsing url
var fs  = require('fs');
var server=http.createServer(function(request,response){
	console.log('connection, server repsonding');
	response.writeHead(200,{"Contetn-Type":"text/html"});
	response.write("Hi are you trying to connect to me?");
	console.log(response);
	response.end();
});

server.listen(3000);
var io=require('socket.io').listen(server);
io.sockets.on('connection', function(socket){
var clientCounter=0;

	//socket.emit('testMethod',{'message':"hi"});
	socket.on('testMethod2',function(data){
		//console.log(data);
		clientCounter++;

		io.sockets.emit('testMethod',data);



	});
});


//Express Method
/*
var server= require('express').createServer();
var io	  = require('socket.io').listen(server);

server.get("/", function(req,res){

});

server.listen(3000);
io.sockets.on('connection',function(socket){
	socket.emit('testMethod2',{'message':"Hi Again"});
});*/