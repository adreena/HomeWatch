var WebSocketServer = require('ws').Server
, wss = new WebSocketServer({port: 3000});

wss.broadcast = function(data) {
    for(var i in this.clients)
        this.clients[i].send(data);
};