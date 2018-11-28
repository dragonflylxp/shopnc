var http = require('http');
var url = require('url');
var db = require('./lib/db');
var lib_user = require('./lib/users');
var _config = require('./config');
var hostname = _config.hostname;
var port = _config.port;
var c = console.log;
var connected_users = new Array();
var connected_stores = new Array();
var server = http.createServer(function (req, res) {
  var req_url = req.url;
  var parts = url.parse(req_url, true);
  if ( parts.pathname == '/store_msg/' ) {
    store_msg(parts, req, res);
  } else {
    res.writeHead(200, {'Content-Type': 'text/plain'});
    res.end('Access Invalid!\n');
  }
});
server.listen(port);

var opts = {
  'origins': '*:*',
  'path': '/socket.io',
  'transports': ['polling', 'websocket']
};

var io = require('socket.io').listen(server, opts);

if ( typeof io.emit === "function" ) {
    console.log('   info  - socket.io started');
}

io.use(function(socket, next) {
  var handshakeData = socket.request;
  var domain = hostname;
  var origin = handshakeData.headers.origin || handshakeData.headers.referer;
  var parts = url.parse(''+origin);
  var re = new RegExp(domain+"$","g");
  var arr = re.exec(parts.hostname);
  if ( domain === '' ) {
  	next();
  } else if ( arr !== null ) {
  	next();
  } else {
  	var dt = new Date();
	console.log(dateToString(dt)+' '+parts.hostname+' handshake unauthorized');
  }
});

io.on('connection', function (socket) {
  socket.on('update_user', function (user) {//连接成功后向客户端发未读消息
  	var u_id = user['u_id'];
  	if ( u_id > 0) {
  	update_user(user, function () {
	    var msg_list = {};
	    msg_list = lib_user.get_msg(u_id);
	  	socket.emit('get_msg', msg_list);
	  	lib_user.set_user_info(u_id,'connected',1);
	  	lib_user.set_user_info(u_id,'s_id',user['s_id']);
	  	lib_user.set_user_info(u_id,'s_name',user['s_name']);
	  	lib_user.set_user_info(u_id,'avatar',user['avatar']);
	  	u_id = socket.u_id;
	  	if ( typeof u_id === "undefined" ) {
	  	    u_id = user['u_id'];
	  	    socket.u_id = u_id;
	  	    socket.join('user_'+u_id);
    	  	if ( connected_users[u_id] > 0) {
    	  	    connected_users[u_id]++;
    	  	} else {
    	  	    connected_users[u_id] = 1;
    	  	}
    	  	var seller_id = user['seller_id'];
    	  	if ( seller_id > 0) {
    	  	    var s_id = user['s_id'];
    	  	    socket.s_id = s_id;
    	  	    socket.join('store_'+s_id);
        	  	if ( connected_stores[s_id] > 0) {
        	  	    connected_stores[s_id]++;
        	  	} else {
        	  	    connected_stores[s_id] = 1;
        	  	}
    	  	}
	  	}
  	});
  	}
  });
  socket.on('send_msg', function (msg) {//接收消息并向客户端发通知
  	var t_id = msg['t_id'];
  	var n = user_clients(t_id);
	u_id = socket.u_id;
	if ( u_id > 0) {
  		msg['user'] = lib_user.get_user(u_id);
	  	if ( n > 0) {//会员在线时发通知
	  		var m_id = msg['m_id'];
	  		var msg_list = {};
	  		msg['online'] = 1;
	  		msg_list[m_id] = msg;
	  		io.in('user_'+t_id).emit('get_msg', msg_list);
	  	}
	  	lib_user.set_msg(t_id,msg);
	}
  });
  socket.on('get_state', function (u_state) {//查询在线状态并返回客户端
  	var list = {};
  	var user_list = {};
  	for (var k in u_state){
  		var user_info = {};
  		user_info = lib_user.get_user(k);
  		user_info['online'] = 0;
	  	var n = user_clients(k);
	  	if ( n > 0) {//会员在线
	  		u_state[k] = 1;
	  		user_info['online'] = 1;
	  	}
	  	if ( user_info['u_id'] > 0) user_list[k] = user_info;
  	}
  	list['u_state'] = u_state;
  	list['user'] = user_list;
  	socket.emit('get_state', list);
  });
  socket.on('del_msg', function (msg) {//更新未读消息
  	var max_id = msg['max_id'];//最大的消息编号
  	var f_id = msg['f_id'];//消息发送人
	u_id = socket.u_id;
	if ( u_id > 0) {
    	var list = lib_user.get_msg(u_id);//未读消息列表
	  	if (typeof list[max_id] === "object") {//删除未读消息
		  	for (var k in list){
		  		var m_id = list[k]['m_id'];
		  		var f = list[k]['f_id'];
		  		if ( max_id > m_id && f_id == f) {
		  			lib_user.del_msg(u_id,m_id);
		  		}
		  	}
		  	var n = user_clients(u_id);
		  	if (n > 1) {//单个会员有两个或以上连接时发通知
		  		io.in('user_'+u_id).emit('del_msg', msg);
		  	}
	  	}
	  	lib_user.del_msg(u_id,max_id);//删除消息防止重复发通知
	  	db.del_msg(' t_id = '+u_id+' AND f_id = '+f_id+' AND m_id < '+max_id);
	  	var v = {};
	  	v['r_state'] = 1;//消息状态:1为已读,2为未读
	  	db.update_msg(' t_id = '+u_id+' AND f_id = '+f_id+' AND m_id = '+max_id,v);
    }
  });
  socket.on('disconnect', function () {
	u_id = socket.u_id;
	if ( u_id > 0) {
    	var dt = new Date();
    	var update_time = dt.getTime();
	    lib_user.set_user_info(u_id,'disconnect_time',update_time);
	    lib_user.set_user_info(u_id,'connected',0);
	    if ( connected_users[u_id] > 0) {
	        connected_users[u_id]--;
	    }
    }
	s_id = socket.s_id;
	if ( s_id > 0) {
	    if ( connected_stores[s_id] > 0) {
	        connected_stores[s_id]--;
	    }
    }
  });
});

function user_clients (u_id) {
    if ( typeof connected_users[u_id] === "undefined" ) {
        return 0;
    } else {
        return connected_users[u_id];
    }
}
function store_clients (s_id) {
    if ( typeof connected_stores[s_id] === "undefined" ) {
        return 0;
    } else {
        return connected_stores[s_id];
    }
}
function store_msg(parts, req, res){//店铺消息
    var get = parts.query;
    res.writeHead(200, {'Content-Type': 'text/plain'});
    var sm_id = parseInt(get['id']);
    var sm_addtime = parseInt(get['time']);
    if ( sm_id > 0 && sm_addtime > 0) {
        var db_query = ' sm_id = '+sm_id+' AND sm_addtime ='+sm_addtime;
		db.get_store_msg(db_query,function (list) {
            if ( typeof list === "object" && typeof list[0] === "object" ) {
                var msg = list[0];
                var store_id = msg['store_id'];
                var n = store_clients(store_id);
                if (n > 0) {
                    msg['add_time'] = dateToString(msg['sm_addtime']*1000);
                    io.in('store_'+store_id).emit('store_msg', msg);
            	}
            }
        });
    }
    res.end(get['id']);
}
function update_user(user, cb){//更新会员的连接信息
	var u_id = user['u_id'];
	var user_info = {};
	var dt = new Date();
	var update_time = dt.getTime();
	user_info = lib_user.get_user(u_id);
	if ( typeof user_info['u_id'] === "undefined" ) {
		lib_user.set_user(user);
		lib_user.set_user_info(u_id,'update_time',update_time);//设置最后连接时间
		var db_query = ' t_id = '+u_id+' AND r_state = 2';
		db.get_msg_list(db_query,function (list) {//初始化未读消息
            for (var k in list){
            	list[k]['add_time'] = dateToString(list[k]['add_time']*1000);
            	lib_user.set_msg(u_id,list[k]);
            }
            cb();
        });
	} else {
		lib_user.set_user_info(u_id,'update_time',update_time);//设置最后连接时间
		cb();
	}
}

function dateToString(date) {
  var dt = new Date(date);
  var year   = dt.getFullYear();
  var month  = zeroPad(dt.getMonth() + 1);
  var day    = zeroPad(dt.getDate());
  var hour   = zeroPad(dt.getHours());
  var minute = zeroPad(dt.getMinutes());
  var second = zeroPad(dt.getSeconds());

  return year + '-' + month + '-' + day + ' ' + hour + ':' + minute + ':' + second;
}

function zeroPad(number) {
  return (number < 10) ? '0' + number : number;
}