var socket=new WebSocket('ws://localhost:8000',"json");
var socketStatus=document.getElementById('status');
var messageList=document.getElementById('messages');

        /*
            var scokectio=require('socket.io');
            var io=socketio.listen(app);
            io.sockets.on('connection')*/
socket.onerror = function(error) {
     console.log('WebSocket Error: ' + error);
};
socket.onopen=function(event){
    socketStatus.innerHTML='Connection open';
    console.log("open");
    socketStatus.className='open';
};
socket.onmessage = function(event) {
    var message = event.data;
    messageList.innerHTML += '<li class="received"><span>Received:</span>' +
                               message + '</li>';
  };
            
    

