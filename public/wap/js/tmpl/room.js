    var key = getCookie('key');
    var store_id = getQueryString("store_id");
	var roomid = store_id;
	var u_id="0";
	var u_leave='V0';
	var u_name='游客';
	var u_img='http://118.31.17.175/data/upload/shop/common/default_user_portrait.gif';
$(function(){
    if(!store_id){
        window.location.href = WapSiteUrl+'/index.html';
    }
    $("#goods_search").attr('href','store_search.html?store_id='+store_id);
    $("#store_categroy").attr('href','store_search.html?store_id='+store_id);
    $("#store_intro").attr('href','store_intro.html?store_id='+store_id);
    //加载店铺详情
    $.ajax({
        type: 'post',
        url: ApiUrl + "/index.php?con=room&fun=store_info",
        data: {key: key, store_id: store_id},
        dataType: 'json',
        success: function(result) {
            var data = result.datas;
            //显示页面title
            var title = data.store_info.store_name + '-购物直播-欢乐购';
            document.title = title;
			//播放器
			var player=template.render('player_tpl', data);
			$("#player").html(player);
			$(".gonggao").append(data.living.notice);
			var html = template.render('gtype_tpl', data);
            $(".gift_nav").html(html);
			html = template.render('gifts_tpl', data);
			$(".zb_gift").append(html);
			$("#receiverId").val(data.living.zbid);				
			$("#storelogo").html("<a href='/tmpl/store.html?store_id="+store_id+"'><img src='"+data.store_info.store_avatar+"' /></a>");
        }		
    });
	//获取会员信息
	if(key){
			$.ajax({
            type:'post',
            url:ApiUrl+"/index.php?con=member_index",
            data:{key:key},
            dataType:'json',
            //jsonp:'callback',
            success:function(result){
				u_id=result.datas.member_info.member_id;
				u_leave=result.datas.member_info.level_name;
				u_name=result.datas.member_info.user_name;
				u_img=result.datas.member_info.avatar;
				$("#giftaction").removeClass("none");
				$(".nologin").addClass("none");
				$(".islogin").removeClass("none");
				$("#userjifen").html(result.datas.member_info.point);
				$("#username").html(result.datas.member_info.user_name);
				$("#yuan").html(result.datas.member_info.money);
				}
			});			
	}
	if (typeof console == "undefined") {    this.console = { log: function (msg) {  } };}
    WEB_SOCKET_SWF_LOCATION = "http://118.31.17.175:55151/swf/WebSocketMain.swf";
    WEB_SOCKET_DEBUG = true;
    var ws, name,client_list={};
	wconnect();
	select_client_id = 'all';
	$("#client_list").change(function(){
		 select_client_id = $("#client_list option:selected").attr("value");
	});
	$("#face").click(function(){
		$('.face').removeClass('none');
	});
	$("#submit").click(function(){
		onSubmit();
	});
	 $('input:text:first').focus();
        var $inp = $('input:text');
        $inp.bind('keydown', function (e) {
            var key = e.which;
            if (key == 13) {
                onSubmit();
            }}
	);
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
		else if(!userid){
			alert("您还没有登录哦！请先登录");
			location.href = "/index.php?con=login&fun=index";
			}
		else{
			sendgift(gid,gnum,userid,reid);
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
			//{"type":"login","client_id":xxx,"client_name":"xxx","client_list":"[...]","time":"xxx"}
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
		// 用户退出 更新用户列表
		case 'logout':
			//{"type":"logout","client_id":xxx,"time":"xxx"}
			//enter_say(data['from_client_id'], data['from_client_name'],'退出了本房间', data['time']);
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
  input.blur();
  }
 
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
	$("#VA").empty();$("#V10").empty();$("#V9").empty();$("#V8").empty();$("#V7").empty();$("#l6").empty();$("#V5").empty();$("#V4").empty();$("#V3").empty();$("#V2").empty();$("#V1").empty();$("#V0").empty();
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
function sendgift(gid,gnum,sid){
	key = getCookie('key');
	$.ajax({
		type: "POST",                         
		url: ApiUrl+"/index.php?con=room&fun=gift_send&store_id=1",         
		dataType: "json",                                   
		data: {gid:gid,gnum:gnum,key:key,sid:sid},      
		success: function (datas) {
			data=datas.datas;
			if (data != null && data.s!= "") {       
				if(data.s == "1") { 
					ws.send('{"type":"giftsay","to_client_id":"all","to_client_name":"所有人","content":"赠送主播 [/s'+data.img+'] '+data.num+'个"}');
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
	key = getCookie('key');
	$.ajax({
		type: "POST",                         
		url: ApiUrl+"/index.php?con=room&fun="+op+"&store_id="+roomid,         
		dataType: "json",                                   
		data: {"change":change,key:key},      
		success: function (datas) {
			data=datas.datas;
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

function giftsend(){
	key = getCookie('key');
	sid = getQueryString("store_id");
	gid=$("#giftid").val();
	gnum=$("#sendGiftNum").val();
	if(!gid){alert("请选择礼物");}
	else if($("#username").text()==""){
		alert("您还没有登录哦！请先登录");
		location.href = "/tmpl/member/login.html";
		}
	else{
		sendgift(gid,gnum,sid);
	}
}
function giftselect(gid){
		$(".monday_gift li").removeClass("giftselected");
		$("#gift_n"+gid).addClass("giftselected");
		$("#giftid").val(gid);		
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
function enter_say(from_client_leave, from_client_name, content, time){
	if(from_client_leave=="主播"){
		$("#enter").removeClass("none");
		$("#enter").html('<span class="userleaveA">'+from_client_leave+'</span><span class="name">'+from_client_name+'</span>'+content);}
	else{
		$("#enter").removeClass("none");
		$("#enter").html('<span class="userleaveA">'+from_client_leave+'</span><span class="name">'+from_client_name+'</span>'+content);
		}
	//window.setTimeout(hidden(),10000); 
	}
//插入表情
function clickFace(face){
	face=face.replace('0','[/0');
	face=face+']';
	$('#textarea').val($('#textarea').val()+face);
	$('.face').addClass('none');
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
function duihuan(){
	$("#duihuanjifen").removeClass("none");	
	}
function hidden(){
	$("#enter").addClass("none")
	}