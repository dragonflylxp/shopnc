<?php /**
 * merchantId验证接口
 *
 */

defined('Inshopec') or exit('Access Invalid!');

require_once(__DIR__."/lib/rsa.php");
require_once(__DIR__."/lib/aes.php");

class MerchantIdVerify {
    /* 版本号 */
    private $version = "2.0.0"; 

    /* 用户所属机构号 */
    private $merchant_no = "549440179410002";  

    /* 交易服务号 */
    private $tranCode = "100022";

    /* 报文类型*/
    private $msgType= "01";

    /* 接口 */
    private $reqUrl = "https://epay.gaohuitong.com/person_interface/qryUser"; 

    /* aes cipher */
    public $aes_cipher = null;

    /* rsa cipher */
    public $rsa_cipher = null;


    public function __construct(){
        $this->aes_cipher = new AESCrypt(); 
        $this->rsa_cipher = new RSACrypt();
    }


    function do_verify($agencyid, $merchantid) {
        $post_data = $this->make_request($agencyid,$merchantid);
        $resp = curl_post($this->reqUrl, $post_data);
        return $this->verify_response($resp);
    }

    function make_request($agencyid,$merchantid){
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
        $xml .= '<tranCode>'.$this->tranCode.'</tranCode>';
        $xml .= '<reqMsgId>'.$req_date.'</reqMsgId>';
        $xml .= '<reqDate>'.$req_date.'</reqDate>';
        $xml .= '</head>';
        $xml .= '<body>';
        $xml .= '<qryAgencyId>'.$agencyid.'</qryAgencyId>';
        $xml .= '<qryMerchantNo>'.$merchantid.'</qryMerchantNo>';
        $xml .= '</body>';
        $xml .= '</merchant>';
        $encryptData = $this->aes_cipher->encrypt($xml);
        $signData = $this->rsa_cipher->sign($xml);
        $encryptKey = $this->rsa_cipher->encrypt($this->aes_cipher->get_aes_key());

        // 表单数据
        $post_data = array();
        $post_data['encryptData'] = base64_encode($encryptData);
        $post_data['signData'] = $signData;
        $post_data['encryptKey'] = $encryptKey;
        $post_data['agencyId'] = $this->merchant_no;
        $post_data['tranCode'] = $this->tranCode;
        return $post_data; 
    }
    
    function verify_response($data){
        $encode_content = $this->parse_response($data);
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
        $result = $this->make_result($xml);
        return $result["type"] == "S";
    }

    function parse_response($data){
        if(!is_array($data)){
            $rs = explode('&',$data);
            $rs = str_replace( array('encryptData=', 'encryptKey=', 'signData=', 'tranCode='), array('encryptData:','encryptKey:','signData:', 'tranCode:'), $rs);
            $arr = array();
            foreach($rs as $k => $v){
                $rst = explode(':',$v);
                $arr[$rst[0]] = $rst[1];
            }
        }else{
            $arr = $data;
        }
        return $arr;
    }
 
    function make_result($xml, $type=false){
        $xml_obj = @simplexml_load_string( $xml );
        $return_code = (array)$xml_obj->head->respCode;
        $msg_code       = (array)$xml_obj->body->authResult;
        $msg            = (array)$xml_obj->head->respMsg;
        $type           = (array)$xml_obj->head->respType;
        $reqMsgId = (array)$xml_obj->head->reqMsgId;
        if($type == 'code'){
            return array('status' => $msg_code[0], 'msg' => $msg[0]);
        }else{
            return array('status' => $return_code[0], 'msg' => $msg[0],
                'type' => $type[0],'reqMsgId' => $reqMsgId[0]);
        }
    }
}
?>
