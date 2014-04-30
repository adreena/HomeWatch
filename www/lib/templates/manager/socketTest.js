    window.onload=function(){
        console.log('$$$$$hi$$$$$');
        var socket=new WebSocket('ws://echo.websocket.org');
        var socketStatus=document.getElementById('status');
        var messageList=document.getElementById('messages');

        socket.onerror = function(error) {
             console.log('WebSocket Error: ' + error);
        };
        socket.onopen=function(event){
            socketStatus.innerHTML='Connection open';
            socketStatus.className='open';
            console.log('oepend');
        };
        socket.onmessage = function(event) {
            var message = event.data;
            messageList.innerHTML += '<li class="received"><span>Received:</span>' +
                                       message + '</li>';
          };
        $(".apt-checkbox").change( function(e){
            e.preventDefault();
            var message="Hi";
            socket.send(message);
            messageList.innerHTML+="<li class='sent'>"+message+"</li>";
            return false;
                /*var $tempdiv="";
            
                $content=$div.find('#charts-placeholder');
                console.log($content);
                $content.append('<p>There</p>');
                console.log($div);*/
                /*
                $.get('/HomeWatch/charts-folder/', function(data){
                    //console.log("**",$(this));
                    $(data).find("#charts-placeholder").append('<p>There</p>');
                    console.log($(data).find("#charts-placeholder"));

                });*/

            });
        };