<?php
/**
 * 实名认证
 * Created by PhpStorm.
 * User: suijiaolong
 * Date: 2016/11/14/014
 * Time: 10:16
 */
use shopec\Tpl;

defined('Inshopec') or exit('Access Invalid!');
class member_trustnameControl extends BaseMemberControl{

    public function __construct()
    {
        parent::__construct();
    }
    /**
     * 安全列表
     */
    public function indexOp() {
        self::profile_menu('modify_email','modify_email');
        $member_info = $this->member_info;
        $member_info['security_level'] = Model('member')->getMemberSecurityLevel($member_info);
        if($member_info['is_trust_name'] && $member_info['is_trust_name'] == 1){
            Tpl::showpage('member_security.trust_name_success');
        }else{
            Tpl::output('member_info',$member_info);
            Tpl::showpage('member_security.trust_name');
        }
    }

    /**
     * 调用接口验证身份证和姓名 及验证码的验证
     */
    public function authOp(){
        $model_member = Model('member');
        if (chksubmit(false,true)) {
            if (!in_array($_POST['type'],array('mobile','email'))) {
                showMessage("认证失败",'','html','error');
            }
            $member_common_info = $model_member->getMemberCommonInfo(array('member_id'=>$_SESSION['member_id']));
            if (empty($member_common_info) || !is_array($member_common_info)) {
                showMessage('认证失败','','html','error');
            }
            if (TIMESTAMP - $member_common_info['send_acode_time'] > 1800) {
                showMessage('验证码已失效，请重新获取验证码','','html','error');
            }
            if ($member_common_info['auth_code'] != $_POST['auth_code']) {
                showMessage('安全码不正确','','html','error');
            }

            //验证身份证和姓名是否相符
            $result = $this->confir_truename($_POST['member_name'],$_POST['id_card']);
            if($result){
                //如果相符的话 执行下面的代码  写入member表
                $member_data['member_truename'] = $_POST['member_name'];
                $member_data['id_card'] = _encrypt($_POST['id_card']);
                $member_data['is_trust_name'] = 1;
                $update_member = $model_member->editMember(array('member_id'=>$_SESSION['member_id']),$member_data);
                //清空安全验证码
                $data = array();
                $data['auth_code'] = '';
                $data['send_acode_time'] = 0;
                $update = $model_member->editMemberCommon($data,array('member_id'=>$_SESSION['member_id']));
                if (!$update) {
                    showMessage('系统发生错误，如有疑问请与管理员联系',SHOP_SITE_URL,'html','error');
                }
                setNcCookie('seccode'.$_POST['nchash'], '',-3600);//清空cookie
                $_SESSION['auth_'.$_POST['type']] = TIMESTAMP;
                if ($update_member) {
                    redirect('index.php?con=member_trustname&fun=index');//成功了跳转认证成功的页面
                } else {
                    showMessage('系统发生错误，如有疑问请与管理员联系',SHOP_SITE_URL,'html','error');//认证失败
                }
            }
        }
        else {
        }
    }

    /**
     * 身份证实名认证  真实姓名和身份证号码判断是否一致
     * @param $realname 用户填写的真实姓名
     * @param $idcard  用户填写的身份证号
     * @return bool
     */
    private function confir_truename($realname,$idcard){
        $appkey = C('turename.key')?C('turename.key'):'7cc5239122413cf34a027fb34df91288';
        $url = "http://op.juhe.cn/idcard/query";
        $params = array(
            "idcard" => $idcard,//身份证号码
            "realname" => $realname,//真实姓名
            "key" => $appkey,//应用APPKEY(应用详细页查询)
        );
        $paramstring =http_build_query($params);
        $content = $this->juhecurl($url,$paramstring);
        $result = json_decode($content,true);
        if($result){
            if($result['error_code']=='0'){
                if($result['result']['res'] == '1'){
                    return true;
                }else{
                    showMessage('身份证号码和真实姓名不一致','','html','error');
                }
            }else{
                $msg =$result['error_code'].":".$result['reason'];
                showMessage("$msg",'','html','error');
            }
        }else{
            showMessage('系统发生错误，如有疑问请与管理员联系',SHOP_SITE_URL,'html','error'); //请求失败
        }
    }
    /**
     * 请求接口返回内容
     * @param  string $url [请求的URL地址]
     * @param  string $params [请求的参数]
     * @param  int $ipost [是否采用POST形式]
     * @return  string
     */
    private function juhecurl($url,$params=false,$ispost=0){
        $httpInfo = array();
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1 );
        curl_setopt( $ch, CURLOPT_USERAGENT , 'JuheData' );
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 60 );
        curl_setopt( $ch, CURLOPT_TIMEOUT , 60);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        if( $ispost )
        {
            curl_setopt( $ch , CURLOPT_POST , true );
            curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
            curl_setopt( $ch , CURLOPT_URL , $url );
        }
        else
        {
            if($params){
                curl_setopt( $ch , CURLOPT_URL , $url.'?'.$params );
            }else{
                curl_setopt( $ch , CURLOPT_URL , $url);
            }
        }
        $response = curl_exec( $ch );
        if ($response === FALSE) {
            //echo "cURL Error: " . curl_error($ch);
            return false;
        }
        $httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
        $httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
        curl_close( $ch );
        return $response;
    }

    /**
     * 统一发送身份验证码
     */
    public function send_auth_codeOp() {
        if (!in_array($_GET['type'],array('email','mobile'))) exit();

        $model_member = Model('member');
        $member_info = $model_member->getMemberInfoByID($_SESSION['member_id'],'member_email,member_mobile');

        //发送频率验证
        $member_common_info = $model_member->getMemberCommonInfo(array('member_id'=>$_SESSION['member_id']));
        if (!empty($member_common_info['send_acode_time'])) {
            if (date('Ymd',$member_common_info['send_acode_time']) != date('Ymd',TIMESTAMP)) {
                $data = array();
                $data['send_acode_times'] = 0;
                $update = $model_member->editMemberCommon($data,array('member_id'=>$_SESSION['member_id']));
            } else {
                if (TIMESTAMP - $member_common_info['send_acode_time'] < 58) {
                    exit(json_encode(array('state'=>'false','msg'=>'请60秒以后再次发送短信')));
                } else {
                    if ($member_common_info['send_acode_times'] >= 15) {
                        exit(json_encode(array('state'=>'false','msg'=>'您今天发送验证信息已超过15条，今天将无法再次发送')));
                    }
                }
            }
        }

        $verify_code = rand(100,999).rand(100,999);
        $model_tpl = Model('mail_templates');
        $tpl_info = $model_tpl->getTplInfo(array('code'=>'authenticate'));
        $param = array();
        $param['send_time'] = date('Y-m-d H:i',TIMESTAMP);
        $param['verify_code'] = $verify_code;
        $param['site_name'] = C('site_name');
        $subject = ncReplaceText($tpl_info['title'],$param);
        $message = ncReplaceText($tpl_info['content'],$param);

        if ($_GET['type'] == 'email') {
            try {
                \shopec\Lib::messager()->send($member_info["member_email"],$subject,$message);
                $result = true;
            } catch (\shopec\Lib\Messager\Exception $ex) {
                $result = false;
            }
        } elseif ($_GET['type'] == 'mobile') {
        	//511613932
        	$paramdata = array();
        	if(C('sms.smsNumber') == 1){
        		$paramdata['sendtime']  = date('Y-m-d H:i',TIMESTAMP);
        		$paramdata['verifycode']  = $verify_code;
        		$paramdata['template']  = C('dysms.verify');
        	}        	        	
            $sms = new Sms();
            $result = $sms->send($member_info["member_mobile"],$message,$paramdata);
        }
        if ($result) {
            $data = array();
            $update_data['auth_code'] = $verify_code;
            $update_data['send_acode_time'] = TIMESTAMP;
            $update_data['send_acode_times'] = array('exp','send_acode_times+1');
            $update = $model_member->editMemberCommon($update_data,array('member_id'=>$_SESSION['member_id']));
            if (!$update) {
                exit(json_encode(array('state'=>'false','msg'=>'系统发生错误，如有疑问请与管理员联系')));
            }
            exit(json_encode(array('state'=>'true','msg'=>'验证码已发出，请注意查收')));
        } else {
            exit(json_encode(array('state'=>'false','msg'=>'验证码发送失败')));
        }
    }




    /**
     * 用户中心右边，小导航
     *
     * @param string    $menu_type  导航类型
     * @param string    $menu_key   当前导航的menu_key
     * @return
     */
    private function profile_menu($menu_type,$menu_key='') {
        $menu_array     = array();
        switch ($menu_type) {
            case 'modify_email':
                $menu_array = array(
                    array('menu_key' => 'modify_email', 'menu_name' => '实名认证', 'menu_url' => 'index.php?con=member_security&fun=auth&type=modify_email'),
                );
                break;
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
}