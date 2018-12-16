<?php //Ìá½»¶ÌÐÅ
$post_data = array();
$post_data['userid'] = '1289';
$post_data['account'] = 'yunlifangtz';
$post_data['password'] = 'yunlifangtz';
$post_data['content'] = '1234567'; //¶ÌÐÅÄÚÈÝ²»ÐèÒªurlencode±àÂë
$post_data['mobile'] = '13828788074';
$post_data['sendtime'] = ''; //²»¶¨Ê±·¢ËÍ£¬ÖµÎª0£¬¶¨Ê±·¢ËÍ£¬ÊäÈë¸ñÊ½YYYYMMDDHHmmssµÄÈÕÆÚÖµ
$url='http://www.uehyt.com/sms.aspx?action=send';
$o='';
foreach ($post_data as $k=>$v)
{
    $o.="$k=".urlencode($v).'&';
}
$post_data=substr($o,0,-1);
$ch = curl_init();
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Èç¹ûÐèÒª½«½á¹ûÖ±½Ó·µ»Øµ½±äÁ¿Àï£¬ÄÇ¼ÓÉÏÕâ¾ä¡£
$result = curl_exec($ch);
var_dump($result);
?>
