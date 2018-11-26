<?php
use \GatewayWorker\Lib\Gateway;

class Events
{
   
   /**
    * 有消息时
    * @param int $client_id
    * @param mixed $message
    */
   public static function onMessage($client_id, $message)
   {
        // debug
        echo "client:{$_SERVER['REMOTE_ADDR']}:{$_SERVER['REMOTE_PORT']} gateway:{$_SERVER['GATEWAY_ADDR']}:{$_SERVER['GATEWAY_PORT']}  client_id:$client_id session:".json_encode($_SESSION)." onMessage:".$message."\n";
        
        // 客户端传递的是json数据
        $message_data = json_decode($message, true);
        if(!$message_data)
        {
            return ;
        }
        
        // 根据类型执行不同的业务
        switch($message_data['type'])
        {
            // 客户端回应服务端的心跳
            case 'pong':
                return;
            // 客户端登录 message格式: {type:login, name:xx, room_id:1,uid:userid,uleave:userleave} ，添加到客户端，广播给所有客户端xx进入聊天室
            case 'login':
                // 判断是否有房间号
                if(!isset($message_data['room_id']))
                {
                    throw new \Exception("\$message_data['room_id'] not set. client_ip:{$_SERVER['REMOTE_ADDR']} \$message:$message");
                }
                
                // 把房间号昵称放到session中
                $room_id = $message_data['room_id'];
                $client_name = htmlspecialchars($message_data['client_name']);
				$client_uid=$message_data['uid'];
				$client_leave=$message_data['leave'];
				$client_img=$message_data['img'];
                $_SESSION['room_id'] = $room_id;
                $_SESSION['client_name'] = $client_name;
				$_SESSION['client_uid'] = $client_uid;
				$_SESSION['client_leave'] = $client_leave;
				$_SESSION['client_img'] = $client_img;
              
                // 获取房间内所有用户列表 
                $clients_list = Gateway::getClientInfoByGroup($room_id);
                foreach($clients_list as $tmp_client_id=>$item)
                {
                    $clients_list[$tmp_client_id] =array("client_name"=>$item['client_name'],"client_leave"=>$item['client_leave'],"client_img"=>$item['client_img']);
                }
                $clients_list[$client_id] = array("client_name"=>$client_name,"client_leave"=>$client_leave,"client_img"=>$client_img);
                
                // 转播给当前房间的所有客户端，xx进入聊天室 message {type:login, client_id:xx, name:xx} 
                $new_message = array('type'=>$message_data['type'], 'client_id'=>$client_id, 'client_name'=>htmlspecialchars($client_name),'client_uid'=>$client_uid,'client_leave'=>$client_leave,'client_img'=>$client_img, 'time'=>date('Y-m-d H:i:s'));
                Gateway::sendToGroup($room_id, json_encode($new_message));
                Gateway::joinGroup($client_id, $room_id);
               
                // 给当前用户发送用户列表 
                $new_message['client_list'] = $clients_list;
                Gateway::sendToCurrentClient(json_encode($new_message));
                return;
                
            // 客户端发言 message: {type:say, to_client_id:xx, content:xx}
            case 'say':
                // 非法请求
                if(!isset($_SESSION['room_id']))
                {
                    throw new \Exception("\$_SESSION['room_id'] not set. client_ip:{$_SERVER['REMOTE_ADDR']}");
                }
                $room_id = $_SESSION['room_id'];
                $client_name = $_SESSION['client_name'];
				$client_leave=$_SESSION['client_leave'];
                
                // 私聊
                if($message_data['to_client_id'] != 'all')
                {
					$clients_list = Gateway::getClientInfoByGroup($_SESSION['room_id']);
					$toclient=$clients_list[$message_data['to_client_id']];
                    $new_message = array(
                        'type'=>'say',
                        'from_client_id'=>$client_id, 
						'from_client_leave' =>$client_leave,
                        'from_client_name' =>$client_name,
                        'to_client_id'=>$message_data['to_client_id'],
                        'content'=>"<b>对你说: </b>".nl2br(htmlspecialchars($message_data['content'])),
                        'time'=>date('i:s'),
                    );
					
                    Gateway::sendToClient($message_data['to_client_id'], json_encode($new_message));
                    $new_message['content'] = "<b>你对<span class='leave".$toclient['client_leave']."'>".$toclient['client_leave']."</span>".htmlspecialchars($message_data['to_client_name'])."说: </b>".nl2br(htmlspecialchars($message_data['content']));
                    return Gateway::sendToCurrentClient(json_encode($new_message));
                }
                
                $new_message = array(
                    'type'=>'say', 
                    'from_client_id'=>$client_id,
                    'from_client_name' =>$client_name,
					'from_client_leave'=>$client_leave,
                    'to_client_id'=>'all',
                    'content'=>nl2br(htmlspecialchars($message_data['content'])),
                    'time'=>date('i:s'),
                );
                return Gateway::sendToGroup($room_id ,json_encode($new_message));
			case 'giftsay':
                // 非法请求
                if(!isset($_SESSION['room_id']))
                {
                    throw new \Exception("\$_SESSION['room_id'] not set. client_ip:{$_SERVER['REMOTE_ADDR']}");
                }
                $room_id = $_SESSION['room_id'];
                $client_name = $_SESSION['client_name'];
                $client_leave=$_SESSION['client_leave'];            
                $new_message = array(
                    'type'=>'giftsay', 
                    'from_client_id'=>$client_id,
					'from_client_leave'=>$client_leave,
                    'from_client_name' =>$client_name,
                    'to_client_id'=>'all',
                    'content'=>nl2br(htmlspecialchars($message_data['content'])),
                    'time'=>date('i:s'),
                );
                return Gateway::sendToGroup($room_id ,json_encode($new_message));
			case 'toutiao':
                // 非法请求
                if(!isset($_SESSION['room_id']))
                {
                    throw new \Exception("\$_SESSION['room_id'] not set. client_ip:{$_SERVER['REMOTE_ADDR']}");
                }
                $room_id = $_SESSION['room_id'];
                $client_name = $_SESSION['client_name'];
                $client_leave=$_SESSION['client_leave'];            
                $new_message = array(
                    'type'=>'toutiao',
					'from_client_id'=>$client_id,
					'from_client_leave'=>$client_leave,
                    'from_client_name' =>$client_name, 
					'from_roomid' =>$room_id, 
                    'toutiao'=>$message_data['toutiao'],
					'gimg'=>$message_data['gimg'],
                    'gnum' =>$message_data['gnum'],
                    'time'=>date('i:s'),
                );
				for($i=1;$i<=50;$i++){
					Gateway::sendToGroup($i ,json_encode($new_message));
					}
                return Gateway::sendToGroup($room_id ,json_encode($new_message));
        }
   }
   
   /**
    * 当客户端断开连接时
    * @param integer $client_id 客户端id
    */
   public static function onClose($client_id)
   {
       // debug
       echo "client:{$_SERVER['REMOTE_ADDR']}:{$_SERVER['REMOTE_PORT']} gateway:{$_SERVER['GATEWAY_ADDR']}:{$_SERVER['GATEWAY_PORT']}  client_id:$client_id onClose:''\n";
       
       // 从房间的客户端列表中删除
       if(isset($_SESSION['room_id']))
       {
           $room_id = $_SESSION['room_id'];
		   if($_SESSION['client_leave']=="主播"){
			   $arrPostInfo  = array("change"=>"end");
				$url = 'http://www.cdibuy.com/index.php?act=show_zhibo&op=zhibodo&store_id='.$_SESSION['room_id'];//请求的url地址				
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $arrPostInfo);
				$response  = curl_exec($ch);
				curl_close($ch);
				$result = json_decode($response,true);
			   }
           $new_message = array('type'=>'logout', 'from_client_id'=>$client_id,'from_client_leave'=>$_SESSION['client_leave'], 'from_client_name'=>$_SESSION['client_name'], 'time'=>date('Y-m-d H:i:s'));
           Gateway::sendToGroup($room_id, json_encode($new_message));
       }
   }
  
}