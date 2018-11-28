var user_list = new Array();//会员的信息

var msg_list = new Array();//所有未读消息的二维数组['u_id']['m_id']
var c = console.log;

exports.set_user = set_user;
exports.set_user_info = set_user_info;
exports.get_user = get_user;

exports.set_msg = set_msg;
exports.get_msg = get_msg;
exports.del_msg = del_msg;

function set_user(user){//更新会员的连接信息
	var user_info = new Object();
	var u_id = user['u_id'];
	user_info = get_user(u_id);
	if ( typeof user_info['u_id'] === "undefined" ) {
		user_info['u_id'] = u_id;
		user_info['u_name'] = user['u_name'];
		user_info['s_id'] = user['s_id'];
		if ( typeof msg_list[u_id] === "undefined" ) msg_list[u_id] = new Object();
	}
	user_list[u_id] = user_info;
}
function set_user_info(u_id,k,v){//设置会员信息
	if ( typeof user_list[u_id] === "object" ) user_list[u_id][k] = v;
}
function get_user(u_id){//会员信息
	var user_info = new Object();
	if ( typeof user_list[u_id] === "object" ) {
		user_info = user_list[u_id];
	}
	return user_info;
}

function set_socket(u_id,socket){//设置会话
	socket.join('user_'+u_id);
	socket.set('u_id', u_id);
}

function set_msg(u_id,msg){//添加消息
	var m_id = msg['m_id'];
	if ( typeof msg_list[u_id] === "undefined" ) msg_list[u_id] = new Object();
	msg_list[u_id][m_id] = msg;
}
function get_msg(u_id){//会员的消息
	return msg_list[u_id];
}
function del_msg(u_id,m_id){//删除消息
	delete msg_list[u_id][m_id];
}