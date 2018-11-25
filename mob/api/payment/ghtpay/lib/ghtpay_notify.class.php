<?php
/** 
 * 高汇通回调接口 
 */
class GhtPayNotify  {

    /** 商户密钥KEY  */
    var $key;

    /** 回调参数 */
    var $parameters;

    function __construct($ghtpay_config) {
        $this->GhtPayNotify($ghtpay_config);
    }

    function GhtPayNotify($ghtpay_config) {
        $this->key = $ghtpay_config["key"]; 
        $this->parameters = array();
    }

    function getKey() {
        return $this->key;
    }

    function setKey($key) {
        $this->key = $key;
    }

    function getParameter($parameter) {
        return $this->parameters[$parameter];
    }
    
    function setParameter($parameter, $parameterValue) {
        $this->parameters[$parameter] = $parameterValue;
    }
    
    /**
    * 获取所有支付参数
    *@return array
    */
    function getAllParameters() {
        return $this->parameters;
    }
    
    /**
    *回调参数验证签名
    *true: 验证通过
    *false: 验证失败
    */
    function verifySign() {
        $signPars = "";
        ksort($this->parameters);
        foreach($this->parameters as $k => $v) {
            if("sign" != $k && "" != $v) {
                $signPars .= $k . "=" . $v . "&";
            }
        }
        $signPars .= "key=" . $this->getKey();
        $sign = strtolower(hash("sha256",$signPars)); 
        $tenpaySign = strtolower($this->getParameter("sign"));
        return $sign == $tenpaySign;
    }

    /**
     * 针对notify_url验证消息是否是支付宝发出的合法消息
     * @return 验证结果
     */
    function verifyNotify(){
        if(empty($_POST)) {//判断POST来的数组是否为空
            return false;
        }
        else {
            foreach($_POST as $k => $v) {
                $this->setParameter($k, $v);
            }
            return $this->verifySign(); 
        }
    }

    /**
     * 针对return_url验证消息是否是支付宝发出的合法消息
     * @return 验证结果
     */
    function verifyReturn(){
        if(empty($_GET)) {//判断GET来的数组是否为空
            return false;
        }
        else {
            foreach($_GET as $k => $v) {
                $this->setParameter($k, $v);
            }
            return $this->verifySign(); 
        }
    }
}
?>
