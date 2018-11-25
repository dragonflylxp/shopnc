<?php
/**
 * 手机短信类
 *
 *
 *
 * @package    library
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net
 * @link       http://www.shopec.net
 * @author     shopec Team
 * @since      File available since Release v1.1
 */
defined('Inshopec') or exit('Access Invalid!');
ini_set("display_errors", "on");
define('SCRIPT_ROOT',  BASE_DATA_PATH.'/api/alidayu/api_sdk/vendor/');
require_once SCRIPT_ROOT.'autoload.php';

use Aliyun\Core\Config;
use Aliyun\Core\Profile\DefaultProfile;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
use Aliyun\Api\Sms\Request\V20170525\QuerySendDetailsRequest;
Config::load();
class Sms {
    static $acsClient = null;

    /**
     * 取得AcsClient
     *
     * @return DefaultAcsClient
     */
    public static function getAcsClient() {
        //产品名称:云通信流量服务API产品,开发者无需替换
        $product = "Dysmsapi";

        //产品域名,开发者无需替换
        $domain = "dysmsapi.aliyuncs.com";

        // TODO 此处需要替换成开发者自己的AK (https://ak-console.aliyun.com/)
        $accessKeyId = C('dysms.accessKeyId');// AccessKeyId

        $accessKeySecret = C('dysms.accessKeySecret'); // AccessKeySecret

        // 暂时不支持多Region
        $region = "cn-hangzhou";

        // 服务结点
        $endPointName = "cn-hangzhou";


        if(static::$acsClient == null) {

            //初始化acsClient,暂不支持region化
            $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);

            // 增加服务结点
            DefaultProfile::addEndpoint($endPointName, $region, $product, $domain);

            // 初始化AcsClient用于发起请求
            static::$acsClient = new DefaultAcsClient($profile);
        }
        return static::$acsClient;
    }
    /**
     * 发送手机短信
     * @param unknown $mobile 手机号
     * @param unknown $content 短信内容
     * @param unknown $data  模板变量
     */
    public function send($mobile,$content,$data='') {
   
    	$set_sms_type=C('sms.smsNumber');
    	if($set_sms_type == 1){
    		return $this->_sendDy($mobile,$content,$data);
    	}else{
    		return $this->_sendEmay($mobile,$content);
    	}
        
    }
    /**
     * 大鱼短信发送接口
     * @param unknown $mobile 手机号
     * @param unknown $content 短信内容
     */
    private function _sendDy($mobile,$content,$data) {
       
    	$mess =array();
    	if(is_array($data)){
    		foreach ($data as $k=>$v){
    			$cont = strpos($k,'_');
    			if($cont){
    				$aaaac= str_replace('_', '', $k);
    				
    				$mess[$aaaac] = $v;
    			}else{
//  				if($k !== 'template'){
     				$mess[$k] = $v;
			
    			}
                
            }
        if($mess['template']){unset($mess['template']);}    
        if($mess['sitename']){unset($mess['sitename']);}    
    
    	set_time_limit(0);
        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new SendSmsRequest();

        // 必填，设置短信接收号码
        $request->setPhoneNumbers($mobile);

        // 必填，设置签名名称，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
        $request->setSignName(C('dysms.signature'));

        // 必填，设置模板CODE，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
        $request->setTemplateCode($data['template']);

        // 可选，设置模板参数, 假如模板中存在变量需要替换则为必填项 // 短信模板中字段的值
        if(is_array($mess)){
        	$request->setTemplateParam(json_encode($mess, JSON_UNESCAPED_UNICODE));
        }
        

        // 可选，设置流水号
        //$request->setOutId("yourOutId");

        // 选填，上行短信扩展码（扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段）
        //$request->setSmsUpExtendCode("1234567");

        // 发起访问请求
        $acsResponse = static::getAcsClient()->getAcsResponse($request);
        
        if ($acsResponse!=null && $acsResponse->Code =="OK") {
            return true;
        } else {
            return false;
        }

        }else{
     	    return false;
        }


    	
    }
    /**
     * 亿美短信发送接口
     * @param unknown $mobile 手机号
     * @param unknown $content 短信内容
     */
    private function _sendEmay($mobile,$content) {
        set_time_limit(0);
        define('SCRIPT_ROOT',  BASE_DATA_PATH.'/api/emay/');
        require_once SCRIPT_ROOT.'include/Client.php';
        /**
         * 网关地址
         */
        $gwUrl = C('sms.gwUrl');
        /**
         * 序列号,请通过亿美销售人员获取
         */
        $serialNumber = C('sms.serialNumber');
        /**
         * 密码,请通过亿美销售人员获取
         */
        $password = C('sms.password');
        /**
         * 登录后所持有的SESSION KEY，即可通过login方法时创建
         */
        $sessionKey = C('sms.sessionKey');
        /**
         * 连接超时时间，单位为秒
         */
        $connectTimeOut = 2;
        /**
         * 远程信息读取超时时间，单位为秒
         */
        $readTimeOut = 10;
        /**
         $proxyhost     可选，代理服务器地址，默认为 false ,则不使用代理服务器
         $proxyport     可选，代理服务器端口，默认为 false
         $proxyusername 可选，代理服务器用户名，默认为 false
         $proxypassword 可选，代理服务器密码，默认为 false
         */
        $proxyhost = false;
        $proxyport = false;
        $proxyusername = false;
        $proxypassword = false;

        $client = new Client($gwUrl,$serialNumber,$password,$sessionKey,$proxyhost,$proxyport,$proxyusername,$proxypassword,$connectTimeOut,$readTimeOut);
        /**
         * 发送向服务端的编码，如果本页面的编码为GBK，请使用GBK
        */
        $client->setOutgoingEncoding("UTF-8");
        //$statusCode = $client->login();
        if ($statusCode!=null && $statusCode=="0") {
        } else {
            //登录失败处理
            //echo "登录失败,返回:".$statusCode;exit;
        }
        $statusCode = $client->sendSMS(array($mobile),$content);
        if ($statusCode!=null && $statusCode=="0") {
            return true;
        } else {
            return false;
//             print_R($statusCode);
//             echo "处理状态码:".$statusCode;
        }
    }
}
