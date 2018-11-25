$(function(){
	if (typeof console == "undefined") {    this.console = { log: function (msg) {  } };}
    WEB_SOCKET_SWF_LOCATION = "http://wchat.cdibuy.com:55151/swf/WebSocketMain.swf";
    WEB_SOCKET_DEBUG = true;
    var ws, name, client_list={};
	wconnect();
	select_client_id = 'all';
	$("#client_list").change(function(){
		 select_client_id = $("#client_list option:selected").attr("value");
	});
	$("#face").click(function(){
		$('.face').removeClass('none');
	});
	
   
    // 连接服务端
    function wconnect() {
       // 创建websocket
       ws = new WebSocket("ws://wchat.cdibuy.com:7272");
       // 当socket连接打开时，输入用户名
       ws.onopen = onopen;
       // 当有消息时根据消息类型显示不同信息
       ws.onmessage = onmessage; 
       ws.onclose = function() {
    	  console.log("连接关闭，定时重连");
          wconnect();
       };
       ws.onerror = function() {
     	  console.log("出现错误");
       };
    }

    // 连接建立时发送登录信息
    function onopen()
    {
        name=username;
        // 登录
        var login_data = '{"type":"login","client_name":"'+name.replace(/"/g, '\\"')+'","room_id":"'+roomid+'"}';
        console.log("websocket握手成功，发送登录数据:"+login_data);
        ws.send(login_data);
    }

    // 服务端发来消息时
    function onmessage(e)
    {
        console.log(e.data);
        var data = eval("("+e.data+")");
        switch(data['type']){
            // 服务端ping客户端
            case 'ping':
                ws.send('{"type":"pong"}');
                break;;
            // 登录 更新用户列表
            case 'login':
                //{"type":"login","client_id":xxx,"client_name":"xxx","client_list":"[...]","time":"xxx"}
                say(data['client_id'], data['client_name'],' 加入了聊天室', data['time']);
				s_say(data['client_id'], data['client_name'],'欢迎您光临本店！', data['time']);
                if(data['client_list'])
                {
                    client_list = data['client_list'];
                }
                else
                {
                    client_list[data['client_id']] = data['client_name']; 
                }
                flush_client_list();
                console.log(data['client_name']+"登录成功");
                break;
            // 发言
            case 'say':
                //{"type":"say","from_client_id":xxx,"to_client_id":"all/client_id","content":"xxx","time":"xxx"}
                say(data['from_client_id'], data['from_client_name'], data['content'], data['time']);
                break;
            // 用户退出 更新用户列表
            case 'logout':
                //{"type":"logout","client_id":xxx,"time":"xxx"}
                say(data['from_client_id'], data['from_client_name'],'退出了本房间', data['time']);
                delete client_list[data['from_client_id']];
                flush_client_list();
        }
    }

    // 输入姓名
    function show_prompt(){  
        name = prompt('输入你的名字：', '');
        if(!name || name=='null'){  
            alert("输入名字为空或者为'null'，请重新输入！");  
            show_prompt();
        }
    }  

    // 提交对话
    function onSubmit() {
      var input = document.getElementById("textarea");
      var to_client_id = $("#client_list option:selected").attr("value");
      var to_client_name = $("#client_list option:selected").text();
	  var mscontent=input.value.replace(/"/g, '\\"').replace(/\n/g,'\\n').replace(/\r/g, '\\r').replace(/\[\//g, '').replace(/\]/g, '').replace(/\ /g, '');
	  if(mscontent){
      ws.send('{"type":"say","to_client_id":"'+to_client_id+'","to_client_name":"'+to_client_name+'","content":"'+mscontent+'"}');
      input.value = "";
      input.focus();}
    }

    // 刷新用户列表框
    function flush_client_list(){
    	var userlist_window = $("#userlist");
    	var client_list_slelect = $("#client_list");
    	userlist_window.empty();
    	client_list_slelect.empty();
    	userlist_window.append('<h4>在线用户</h4><ul>');
    	client_list_slelect.append('<option value="all" id="cli_all">所有人</option>');
    	for(var p in client_list){
            userlist_window.append('<li id="'+p+'"><span class="userleave">'+userleave+'</span>'+client_list[p]+'</li>');
            client_list_slelect.append('<option value="'+p+'">'+client_list[p]+'</option>');
        }
    	$("#client_list").val(select_client_id);
    	userlist_window.append('</ul>');
    }

    // 发言
    function say(from_client_id, from_client_name, content, time){
		time=time.substr(11,8);
		content=readface(content);
    	$("#dialog").append('<div class="speech_item"><span class="time">'+time+'</span><span class="userleave">'+userleave+'</span><span class="name">'+from_client_name+':</span>'+content+'</div>');
    }
	//私聊
    function s_say(from_client_id, from_client_name, content, time){
		time=time.substr(11,8);
		content=readface(content);
    	$("#sroom").append('<div class="speech_item"><span class="time">'+time+'</span><span class="userleave">'+userleave+'</span><span class="name">'+from_client_name+'</span>'+content+'</div>');
    }
	
	//插入表情
	function clickFace(name){
		name=name.replace('0','[/0');
		name=name+']';
		$('#textarea').val($('#textarea').val()+name);
		$('.face').addClass('none');
	}

});