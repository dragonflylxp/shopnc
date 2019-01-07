<?php 
/**
 * 一户一码商户入驻  
 *
 */

defined('Inshopec') or exit('Access Invalid!');

require_once(BASE_CORE_PATH."/common/key/config.api.php");
require_once(BASE_CORE_PATH."/common/lib/rsa.php");
require_once(BASE_CORE_PATH."/common/lib/aes.php");

class MerchantRegister{
    /* 版本号 */
    private $version = ""; 

    /* 用户所属机构号 */
    private $merchant_no = "";  

    /* 交易服务号 */
    private $basicInfotranCode = "";
    private $bankInfotranCode  = "";
    private $busiInfotranCode  = "";
    private $qryCardtranCode   = "";
    private $qryBusitranCode   = "";
    private $addImagetranCode  = "";

    /* 报文类型*/
    private $msgType= "";

    /* 接口 */
    private $basicInfoUrl = ""; 
    private $bankInfoUrl  = ""; 
    private $busiInfoUrl  = ""; 
    private $qryCardUrl   = ""; 
    private $qryBusiUrl   = ""; 
    private $addImageUrl  = ""; 

    /* aes cipher */
    public $aes_cipher = null;

    /* rsa cipher */
    public $rsa_cipher = null;


    public function __construct($config_api){
        $this->version           = $config_api['register']['version'];
        $this->merchant_no       = $config_api['register']['merchant_no'];
        $this->basicInfotranCode = $config_api['register']['basicInfotranCode'];
        $this->bankInfotranCode  = $config_api['register']['bankInfotranCode'];
        $this->busiInfotranCode  = $config_api['register']['busiInfotranCode'];
        $this->qryCardtranCode   = $config_api['register']['qryCardtranCode'];
        $this->qryBusitranCode   = $config_api['register']['qryBusitranCode'];
        $this->addImagetranCode  = $config_api['register']['addImagetranCode'];
        $this->msgType           = $config_api['register']['msgType'];
        $this->basicInfoUrl      = $config_api['register']['basicInfoUrl'];
        $this->bankInfoUrl       = $config_api['register']['bankInfoUrl'];
        $this->busiInfoUrl       = $config_api['register']['busiInfoUrl'];
        $this->qryCardUrl        = $config_api['register']['qryCardUrl'];
        $this->qryBusiUrl        = $config_api['register']['qryBusiUrl'];
        $this->addImageUrl       = $config_api['register']['addImageUrl'];
        $this->aes_cipher = new AESCrypt(); 
        $this->rsa_cipher = new RSACrypt();
    }

    /*
     * 商户基础信息登记
     */ 
    public function basicInfo($params){
	$xml = $this->_common_header($this->basicInfotranCode);
        $xml .= '<body>';
        $xml .= '<merchantName>'.$params['merchantName'].'</merchantName>';
        $xml .= '<shortName>'.$params['shortName'].'</shortName>';
        $xml .= '<city>'.$params['city'].'</city>';
        $xml .= '<merchantAddress>'.$params['merchantAddress'].'</merchantAddress>';
        $xml .= '<servicePhone>'.$params['servicePhone'].'</servicePhone>';
        $xml .= '<orgCode>'.$params['orgCode'].'</orgCode>';
        $xml .= '<merchantType>'.$params['merchantType'].'</merchantType>';
        $xml .= '<category>'.$params['category'].'</category>';
        $xml .= '<corpmanName>'.$params['corpmanName'].'</corpmanName>';
        $xml .= '<corpmanId>'.$params['corpmanId'].'</corpmanId>';
        $xml .= '<corpmanPhone>'.$params['corpmanPhone'].'</corpmanPhone>';
        $xml .= '<corpmanMobile>'.$params['corpmanMobile'].'</corpmanMobile>';
        $xml .= '<corpmanEmail>'.$params['corpmanEmail'].'</corpmanEmail>';
        $xml .= '<bankCode>'.$params['bankCode'].'</bankCode>';
        $xml .= '<bankName>'.$params['bankName'].'</bankName>';
        $xml .= '<bankaccountNo>'.$params['bankaccountNo'].'</bankaccountNo>';
        $xml .= '<bankaccountName>'.$params['bankaccountName'].'</bankaccountName>';
        $xml .= '<licenseNo>'.$params['licenseNo'].'</licenseNo>';
        $xml .= '<taxRegisterNo>'.$params['taxRegisterNo'].'</taxRegisterNo>';
        $xml .= '<autoCus>'.$params['autoCus'].'</autoCus>';
        $xml .= '<remark>'.$params['remark'].'</remark>';
        /*
         * 设置结算银行卡信息
         */
        $xml .= '<settingSettCard>'.$params['settingSettCard'].'</settingSettCard>';
        $xml .= '<bankaccProp>'.$params['bankaccProp'].'</bankaccProp>';
        $xml .= '<certCode>'.$params['certCode'].'</certCode>';
        $xml .= '<bankaccountType>'.$params['bankaccountType'].'</bankaccountType>';

        $xml .= '</body>';
        $xml .= '</merchant>';
	$resp = $this->_make_request($xml, $this->basicInfoUrl, $this->basicInfotranCode);
        return $this->_parse_response($resp);
        

    }

    /*
     * 商户银行卡信息登记 
     */ 
    public function bankInfo($params){
	$xml = $this->_common_header($this->bankInfotranCode);
        $xml .= '<body>';
        $xml .= '<merchantId>'.$params['merchantId'].'</merchantId>';
        $xml .= '<handleType>'.$params['handleType'].'</handleType>';
        $xml .= '<mobileNo>'.$params['mobileNo'].'</mobileNo>';
        $xml .= '<mobileNo2>'.$params['mobileNo2'].'</mobileNo2>';
        $xml .= '<bankCode>'.$params['bankCode'].'</bankCode>';
        $xml .= '<bankaccProp>'.$params['bankaccProp'].'</bankaccProp>';
        $xml .= '<name>'.$params['name'].'</name>';
        $xml .= '<bankaccountNo>'.$params['bankaccountNo'].'</bankaccountNo>';
        $xml .= '<bankaccountType>'.$params['bankaccountType'].'</bankaccountType>';
        $xml .= '<certCode>'.$params['certCode'].'</certCode>';
        $xml .= '<certNo>'.$params['certNo'].'</certNo>';
        $xml .= '<bankbranchNo>'.$params['bankbranchNo'].'</bankbranchNo>';
        $xml .= '<defaultAcc>'.$params['defaultAcc'].'</defaultAcc>';
        $xml .= '</body>';
        $xml .= '</merchant>';
	$resp = $this->_make_request($xml, $this->bankInfoUrl, $this->bankInfotranCode);
        return $this->_parse_response($resp);
    }

    /*
     * 开通支付平台业务 
     */ 
    public function busiInfo($params){
	$xml = $this->_common_header($this->busiInfotranCode);
        $xml .= '<body>';
        $xml .= '<merchantId>'.$params['merchantId'].'</merchantId>';
        $xml .= '<handleType>'.$params['handleType'].'</handleType>';
        $xml .= '<cycleValue>'.$params['cycleValue'].'</cycleValue>';
	foreach($params['busiList'] as $busiCode => $ratelist){
            $xml .= '<busiList>';
            $xml .= '<busiCode>'.$busiCode.'</busiCode>';
            foreach($ratelist as $k => $rate){
                //$xml .= '<rateList>';
                $xml .= '<futureRateType>'.$rate['futureRateType'].'</futureRateType>';
                $xml .= '<futureRateValue>'.$rate['futureRateValue'].'</futureRateValue>';
                //$xml .= '<attachRateType>'.$rate['attachRateType'].'</attachRateType>';
                //$xml .= '<attachRateValue>'.$rate['attachRateValue'].'</attachRateValue>';
            //    $xml .= '</rateList>';
            }
            $xml .= '</busiList>';
	}
        $xml .= '</body>';
        $xml .= '</merchant>';
	$resp = $this->_make_request($xml, $this->busiInfoUrl, $this->busiInfotranCode);
        return $this->_parse_response($resp);
    }

    /*
     * 商户银行卡信息查询 
     */ 
    public function qryCard($params){
	$xml = $this->_common_header($this->qryCardtranCode);
        $xml .= '<body>';
        $xml .= '<merchantId>'.$params['merchantId'].'</merchantId>';
        if(!empty($params['bankaccountNo'])){
            $xml .= '<bankaccountNo>'.$params['bankaccountNo'].'</bankaccountNo>';
        }
        $xml .= '</body>';
        $xml .= '</merchant>';
	$resp = $this->_make_request($xml, $this->qryCardUrl, $this->qryCardtranCode);
        return $this->_parse_response($resp);
    }

    /*
     * 业务信息查询 
     */ 
    public function qryBusi($params){
	$xml = $this->_common_header($this->qryBusitranCode);
        $xml .= '<body>';
        $xml .= '<merchantId>'.$params['merchantId'].'</merchantId>';
        if(!empty($params['busiCode'])){
            $xml .= '<busiCode>'.$params['busiCode'].'</busiCode>';
        }
        $xml .= '</body>';
        $xml .= '</merchant>';
	$resp = $this->_make_request($xml, $this->qryBusiUrl, $this->qryBusitranCode);
        return $this->_parse_response($resp);
    }

    /*
     * 新增图片信息 
     */ 
    public function addImage($params){
	$xml = $this->_common_header($this->addImagetranCode);
        $xml .= '<body>';
        $xml .= '<merchantId>'.$params['merchantId'].'</merchantId>';
        $xml .= '<picType>'.$params['picType'].'</picType>';
        $xml .= '</body>';
        $xml .= '</merchant>';
	$resp = $this->_make_request($xml, $this->addImageUrl, $this->addImagetranCode, $streamFile=$params['imgFile']);
        return $this->_parse_response($resp);
    }

    /*
     * 公共报文头 
     */ 
    private function _common_header($tranCode){
        //请求时间(也作为请求唯一id)
        date_default_timezone_set('UTC');
        $req_date = date("YmdHis");
        
        // xml报文
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<merchant>';
        $xml .= '<head>';
        $xml .= '<version>'.$this->version.'</version>';
        $xml .= '<agencyId>'.$this->merchant_no.'</agencyId>';
        $xml .= '<msgType>'.$this->msgType.'</msgType>';
        $xml .= '<tranCode>'.$tranCode.'</tranCode>';
        $xml .= '<reqMsgId>'.$req_date.'</reqMsgId>';
        $xml .= '<reqDate>'.$req_date.'</reqDate>';
        $xml .= '</head>';
        return $xml;
    }


    /*
     * 执行请求 
     */ 
    private function _make_request($xml, $reqUrl, $tranCode, $streamFile=null){
        $encryptData = $this->aes_cipher->encrypt($xml);
        $signData = $this->rsa_cipher->sign($xml);
        $encryptKey = $this->rsa_cipher->encrypt($this->aes_cipher->get_aes_key());

        // 表单数据
        $post_data = array();
        $post_data['encryptData'] = base64_encode($encryptData);
        $post_data['signData'] = $signData;
        $post_data['encryptKey'] = $encryptKey;
        $post_data['agencyId'] = $this->merchant_no;
        $post_data['tranCode'] = $tranCode;
        if(isset($streamFile)){
            $ext = explode('.',$streamFile)[1];
            $post_data['file'] = curl_file_create($streamFile, "image/".$ext, "img_file");
        }
        $resp = curl_post($reqUrl, $post_data);
        return $resp; 
    }
    
    /*
     * 解析响应 
     */ 
    private function _parse_response($resp){
        $encode_content = $this->_prepare_response($resp);
        $aes_key = $this->rsa_cipher->decrypt($encode_content['encryptKey']);
        $this->aes_cipher->set_key($aes_key);
        $decode_content = $this->aes_cipher->decrypt(base64_decode($encode_content["encryptData"]));
        $xml_end = array('</ipay>', '</merchant>');
        $xml = '';
        foreach ($xml_end as $value){
            if(strstr($decode_content,$value)){
                $xml_arr = explode( $value, $decode_content);
                $xml = $xml_arr[0].$value;
                break;
            }
        }

        // 转换字典
        $xml_obj = @simplexml_load_string( $xml );
        $xmljson = json_encode($xml_obj);
        $xmlarray=json_decode($xmljson,true);
        return $xmlarray;
    }

    private function _prepare_response($resp){
        if(!is_array($resp)){
            $rs = explode('&',$resp);
            $rs = str_replace( array('encryptData=', 'encryptKey=', 'signData=', 'tranCode='), array('encryptData:','encryptKey:','signData:', 'tranCode:'), $rs);
            $arr = array();
            foreach($rs as $k => $v){
                $rst = explode(':',$v);
                $arr[$rst[0]] = $rst[1];
            }
        }else{
            $arr = $resp;
        }
        return $arr;
    }
}
?>
