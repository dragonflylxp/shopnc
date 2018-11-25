<?php
/**
 * 高汇通支付接口 
 */
class GhtPaySubmit {

    /** 支付网关 */
    var $gateUrl;
    
    /** 商户密钥KEY  */
    var $key;
    
    /** 支付参数 */
    var $parameters;
    
    function __construct($ghtpay_config) {
        $this->GhtPaySubmit($ghtpay_config);
    }
    
    function GhtPaySubmit($ghtpay_config) {
        $this->gateUrl = $ghtpay_config["gate_url"];
        $this->key = $ghtpay_config["key"]; 
        $this->parameters = array();
        $this->parameters["version"] = $ghtpay_config["version"]; 
        $this->parameters["merchant_no"] = $ghtpay_config["merchant_no"]; 
        $this->parameters["child_merchant_no"] = $ghtpay_config["child_merchant_no"]; 
        $this->parameters["terminal_no"] = $ghtpay_config["terminal_no"]; 
        $this->parameters["sign_type"] = $ghtpay_config["sign_type"]; 
        $this->parameters["return_url"] = $ghtpay_config["return_url"]; 
        $this->parameters["notify_url"] = $ghtpay_config["notify_url"]; 
        $this->parameters["currency_type"] = $ghtpay_config["currency_type"]; 
        $this->parameters["sett_currency_type"] = $ghtpay_config["sett_currency_type"]; 
        $this->parameters["bank_code"] = $ghtpay_config["bank_code"]; 
   
        // 业务参数
        $this->parameters["order_no"] = $ghtpay_config["order_sn"]; 
        $this->parameters["amount"] = $ghtpay_config["order_amount"]; 
        $this->parameters["product_name"] = $ghtpay_config["product_name"]; 
    }
    
    /**
    * 获取支付网关
    */
    function getGateURL() {
        return $this->gateUrl;
    }
    
    /**
    * 设置支付网关
    */
    function setGateURL($gateUrl) {
        $this->gateUrl = $gateUrl;
    }
    
    /**
    * 获取密钥KEY
    */
    function getKey() {
        return $this->key;
    }
    
    /**
    * 设置密钥KEY
    */
    function setKey($key) {
        $this->key = $key;
    }
    
    /**
    * 获取支付参数
    */
    function getParameter($parameter) {
        return $this->parameters[$parameter];
    }
    
    /**
    * 设置支付参数
    */
    function setParameter($parameter, $parameterValue) {
        $this->parameters[$parameter] = $parameterValue;
    }
    
    /**
    * 获取所有支付参数
    *@return array
    */
    function getAllParameters() {
        $this->buildRequestSign();
        return $this->parameters;
    }
    
    /**
    * 构造支付请求
    */
    function getRequestURL() {
        $this->buildRequestSign();
    
        $reqPar = "";
        ksort($this->parameters);
        foreach($this->parameters as $k => $v) {
            $reqPar .= $k . "=" . urlencode($v) . "&";
        }
        
        $reqPar = substr($reqPar, 0, strlen($reqPar)-1);
        
        $requestURL = $this->getGateURL() . "?" . $reqPar;
        
        return $requestURL;
    }
    
    /**
     * 支付参数签名
    */
    function buildRequestSign() {
        $signOrigStr = "";
        ksort($this->parameters);
        foreach($this->parameters as $k => $v) {
            if("" != $v && "sign" != $k) {
                $signOrigStr .= $k . "=" . $v . "&";
            }
        }
        $signOrigStr .= "key=" . $this->getKey();
        $sign = strtolower(hash("sha256",$signOrigStr));
        $this->setParameter("sign", $sign);
    }	
}
?>
