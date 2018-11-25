$(function(){
	if (typeof console == "undefined") {this.console = { log: function (msg) {  } };}
    WEB_SOCKET_SWF_LOCATION = "http://118.31.17.175:55151/swf/WebSocketMain.swf";
    WEB_SOCKET_DEBUG = true;
    var ws, name=u_name, client_list={};
	wconnect();
	select_client_id = 'all';
	$("#client_list").change(function(){
		 select_client_id = $("#client_list option:selected").attr("value");
	});
	$("#face").click(function(){
		$('.face').removeClass('none');
	});
	$("#duihuan").click(function(){
		$('#duihuanjifen').removeClass('none');
		dosome("chaxun");
		});
	$("#duihuanjifen span.close").click(function(){
		$('#duihuanjifen').addClass('none');
	});
	$("#zslw_btn").click(function(){
		gid=$(".giftselected").attr("giftid");
		gnum=$("#sendGiftNum").val();
		reid=$("#receiverId").val();
		if(!gid){alert("请选择礼物");}
		else if(!u_id){
			alert("您还没有登录哦！请先登录");
			location.href = "/index.php?con=login&fun=index";
			}
		else{
			sendgift(gid,gnum,reid);
		}
	});
	$("#lebi").change(function(){
		moneyb=$("#lebi").val()/100;
  		$("#moneykou").text(moneyb);
	});
	$("#sureduihuan").click(function(){
		dosome("duihuan",$("#lebi").val());
		$('#duihuanjifen').addClass('none');
	});
});
    // 连接服务端
function wconnect() {
       // 创建websocket
       ws = new WebSocket("ws://118.31.17.175:7272");
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
	// 登录
	var login_data = '{"type":"login","client_name":"'+u_name+'","room_id":"'+roomid+'","uid":"'+u_id+'","leave":"'+u_leave+'","img":"'+u_img+'"}';
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
			//{"type":"login","client_id":"7f00000108ff00000002","u_name":"\u6e38\u5ba2","u_uid":"0","u_leave":"0","u_img":"http:\/\/img.cdibuy.com\/shop\/common\/default_user_portrait.gif","time":"2016-11-13 13:33:58","client_list":{"7f00000108ff00000002":"\u6e38\u5ba2"}}
			enter_say(data['client_leave'],data['client_name'],' 加入了聊天室', data['time']);
			if(data['client_list'])
                {
                    client_list = data['client_list'];
                }
                else
                {
                    client_list[data['client_id']] = data; 
                }
			flush_client_list();
			console.log(data['client_name']+"登录成功");
			break;
		// 发言
		case 'say':
			//{"type":"say","from_client_id":xxx,"to_client_id":"all/client_id","content":"xxx","time":"xxx"}
			if(data['to_client_id']!='all'){
				//私聊
				s_say(data['from_client_id'], data['from_client_leave'],data['from_client_name'], data['content'], data['time']);
				}
			else{
				//公屏聊天
				say(data['from_client_id'], data['from_client_leave'],data['from_client_name'], data['content'], data['time']);
				}
			break;
		// 礼物
		case 'giftsay':
			//{"type":"gift","from_client_id":xxx,"to_client_id":"all/client_id","content":"xxx","time":"xxx"}
				//公屏聊天
				giftsay(data['from_client_id'], data['from_client_leave'],data['from_client_name'], data['content'], data['time']);
			break;
		case 'toutiao':
			//{"type":"touiao","from_client_id":"7f00000108ff00000001","from_client_leave":"\u4e3b\u64ad","from_client_name":"admin","from_roomid":"1","toutiao":2,"gimg":"[\/s\/shop\/player\/zbgift\/img\/1\/17.png]","gnum":"1","time":"58:51"}
				toutiao(data['from_client_id'], data['from_client_name'], data['from_roomid'], data['toutiao'], data['gimg'],data['gnum'], data['time']);
			break;
		// 用户退出 更新用户列表
		case 'logout':
			//{"type":"logout","client_id":xxx,"time":"xxx"}
			//say(data['from_client_id'], data['from_client_name'],'退出了本房间', data['time']);
			delete client_list[data['from_client_id']];
			flush_client_list();
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
	//userlist_window.empty();
	client_list_slelect.empty();
	//userlist_window.append('<h4>在线用户</h4><ul>');
	client_list_slelect.append('<option value="all" id="cli_all">所有人</option>');
	var z=0;
	$("#VA").empty();$("#V10").empty();$("#V9").empty();$("#V8").empty();$("#V7").empty();$("#V6").empty();$("#V5").empty();$("#V4").empty();$("#V3").empty();$("#V2").empty();$("#V1").empty();$("#V0").empty();
	for(var p in client_list){
		z++;
		ulist=client_list[p];		
		if(ulist.client_leave=="主播"){
			$("#VA").append('<li id="'+p+'" class="userleave'+ulist.client_leave+'"><span class="img"><img src="'+ulist.client_img+'"></span><a href="javascript:void(0);" onclick="selectuser(\''+p+'\',\''+ulist.client_name+'\');">'+ulist.client_name+'</a><span class="leave">'+ulist.client_leave+'</span></li>');
			}
		else{
			$("#"+ulist.client_leave).append('<li id="'+p+'" class="userleave'+ulist.client_leave+'"><span class="img"><img src="'+ulist.client_img+'"></span><a href="javascript:void(0);" onclick="selectuser(\''+p+'\',\''+ulist.client_name+'\');">'+ulist.client_name+'</a><span class="leave">'+ulist.client_leave+'</span></li>');
			}
		//userlist_window.append('<li id="'+p+'"><span class="userleave">'+userleave+'</span>'+client_list[p]+'</li>');
		//client_list_slelect.append('<option value="'+p+'">'+client_list[p]+'</option>');
	}
	$("#online").html(z);
	$("#client_list").val(select_client_id);
	//userlist_window.append('</ul>');
}

    //发言
function say(from_client_id,from_client_leave, from_client_name, content, time){
		content=readface(content);
		if(from_client_leave=="主播"){
			$("#chatmsg").append('<div class="speech_item"><span class="time">'+time+'</span><span class="userleaveA">'+from_client_leave+'</span><span class="name">'+from_client_name+':</span>'+content+'</div>');}
		else{
    	$("#chatmsg").append('<div class="speech_item"><span class="time">'+time+'</span><span class="userleave'+from_client_leave+'">'+from_client_leave+'</span><span class="name">'+from_client_name+':</span>'+content+'</div>');}
		autoscoll()
    }
	// 礼物通知
function giftsay(from_client_id,from_client_leave, from_client_name, content, time){
		content=readgift(content);
		if(from_client_leave=="主播"){
			$("#chatmsg").append('<div class="speech_item giftsay"><span class="time">'+time+'</span><span class="userleaveA">'+from_client_leave+'</span><span class="name">'+from_client_name+':</span>'+content+'</div>');}
		else{
    	$("#chatmsg").append('<div class="speech_item giftsay"><span class="time">'+time+'</span><span class="userleave'+from_client_leave+'">'+from_client_leave+'</span><span class="name">'+from_client_name+':</span>'+content+'</div>');}
		autoscoll();
    }
//toutiao(data['from_client_id'], data['from_client_name'], data['from_roomid'], data['toutiao'], data['gimg'],data['gnum'], data['time']);
function toutiao(from_client_id, from_client_name,from_roomid, toutiao,gimg,gnum, time){
		gimg=readgift(gimg);
		if(toutiao=="1"){
			$("#scroll1").prepend('<li><em class="time">'+time+'</em><a target="_blank" href="/room-'+from_roomid+'.html"><i>'+from_client_name+'</i>送给<i>主播</i>'+gnum+'个'+gimg+'</a></li>');
			}
		else{
			$("#richScroll").html('<li><a target="_blank" href="/room-'+from_roomid+'.html" title=""><i>'+from_client_name+'</i>送给<i>主播</i>'+gnum+'个'+gimg+'</a></li>');
			}
    }
	//私聊
function s_say(from_client_id,from_client_leave, from_client_name, content, time){
	content=readface(content);
	if(from_client_leave=="主播"){
		$("#sroom").append('<div class="speech_item"><span class="time">'+time+'</span><span class="userleaveA">'+from_client_leave+'</span><span class="name">'+from_client_name+'</span>'+content+'</div>');
		}
	else{
		$("#sroom").append('<div class="speech_item"><span class="time">'+time+'</span><span class="userleave'+from_client_leave+'">'+from_client_leave+'</span><span class="name">'+from_client_name+'</span>'+content+'</div>');
		}
	
}
//enter_say(data['u_id'],data['u_uid'],data['u_img'],data['u_name'],' 加入了聊天室', data['time']);
function enter_say(from_client_leave, from_client_name, content, time){
	if(from_client_leave=="主播"){
		$("#enter").removeClass("none");
		$("#enter").html('<span class="userleaveA">'+from_client_leave+'</span><span class="name">'+from_client_name+'</span>'+content);
		}
	else{
		$("#enter").removeClass("none");
		$("#enter").html('<span class="userleaveA">'+from_client_leave+'</span><span class="name">'+from_client_name+'</span>'+content);
		}
	}

//插入表情
function clickFace(face){
	face=face.replace('0','[/0');
	face=face+']';
	$('#textarea').val($('#textarea').val()+face);
	$('.face').addClass('none');
}
function sendgift(gid,gnum,reid){
	$.ajax({
		type: "POST",                         
		url: "./index.php?con=show_zhibo&fun=gift_send&store_id="+roomid,         
		dataType: "json",                                   
		data: {gid:gid,gnum:gnum,reid:reid},      
		success: function (data) {
			if (data != null && data.s!= "") {       
				if(data.s == "1") { 
					//say(from_client_id, from_client_name,from_client_name+"赠送主播"+data.gift+data.num+"个", time);
					ws.send('{"type":"giftsay","to_client_id":"all","to_client_name":"所有人","content":"赠送主播 [/s'+data.img+'] '+data.num+'个"}');
					 if(data.toutiao){ws.send('{"type":"toutiao","toutiao":'+data.toutiao+',"gimg":"[/s'+data.img+']","gnum":"'+data.num+'"}');}
					dosome("chaxun");
					alert("赠送主播【"+data.name+'】 X'+data.num+"个，"+data.msg);						
				}
				else{
					alert("您的积分不足，请先兑换");
				}
			}
			else {
				alert("接口繁忙，请稍后再试！");
			}
		}
	})
}
function dosome(op,change){
	$.ajax({
		type: "POST",                         
		url: "/index.php?con=show_zhibo&fun="+op+"&store_id="+roomid,         
		dataType: "json",                                   
		data: {"change":change},      
		success: function (data) {
			if (data != null && data.s!= "") {       
				if(data.s == "success") { 
					if(data.points){pointchage(data.points);}
					if(data.money){moneychange(data.money);}
				}
				else{
					alert(data.msg);
				}
			}
			else {
				alert("接口繁忙，请稍后再试！");
			}
		}
	});			
	}
function startzhibo(wdo){
	$.ajax({
		type: "POST",                         
		url: "/index.php?con=show_zhibo&fun=zhibodo&store_id="+roomid,         
		dataType: "json",                                   
		data: {"change":wdo},      
		success: function (data) {
		}
	});		
	}
function onlinechange(act){
		
	}
function moneychange(rmoney){
	$("#yuan").text(rmoney);
	}
function pointchage(rpoints){		
	$("#userjifen").text(rpoints);
	}
function selectuser(p,name){
	$('#client_list option').attr('selected','');
	$('#client_list').append('<option value="'+p+'">'+name+'</option>');
	$('#client_list option:last').attr('selected','selected');
	}
function autoscoll(){
	if($("#chatmsg").height()>$('#dialog').height()){
	$('#dialog').scrollTop($("#chatmsg").height()-$('#dialog').height());
	}
	}