<?php

class Client{
	
	/**
	 * 网关地址
	 */
	var $url;
	
	/**
	 * 企业ID 
	 */
	var $userid;
	
	/**
	 * 发送用户账户 
	 */
	var $account;

	/**
	 * 发送账户密码 
	 */
	var $password;
	
	/**
	 * @param string $url 		网关地址
	 * @param string $userid        企业ID 
	 * @param string $account       发送用户账户 
	 * @param string $password      发送账户密码	
	 */
	public function __construct($url, $userid, $account, $password)
	{
		$this->url = $url;
		$this->userid = $userid;
		$this->account = $account;
		$this->password = $password;
	}
	

	/**
	 * 短信发送 
	 * 
	 * @param array $mobiles	手机号数组  
	 * @param string $content	短信内容(UTF-8编码)
	 * @param string $sendTime	为空表示立即发送， 定时发送格式:2010-10-24 09:08:10
	 * @param string $extno         扩展子号，若不支持则为空 
	 * @return int 操作结果状态码
	 */
	public function sendSMS($mobiles, $content, $sendTime='', $extno='')
	{
		$post_data = array();
		$post_data['userid'] = $this->userid;
		$post_data['account'] = $this->account;
		$post_data['password'] = $this->password;
		$post_data['mobile'] = implode(',', $mobiles);
		$post_data['content'] = $content;
		$post_data['sendTime'] = $sendTime;
		$post_data['extno'] = $extno;

		$o = '';
		foreach ($post_data as $k=>$v)	
		{
			$o.="$k=".urlencode($v).'&';
		}
		$post_data=substr($o,0,-1);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_URL,$this->url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$xml = curl_exec($ch);

		// 转换字典
		$xml_obj = @simplexml_load_string( $xml );
		$xmljson = json_encode($xml_obj);
		$xmlarray=json_decode($xmljson,true);
		return $xmlarray;
	}
}
?>
