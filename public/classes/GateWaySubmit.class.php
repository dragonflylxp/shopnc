<?php
/**
 * Ö§¸¶ÇëÇóÌá½»Àà
 */
class GatewaySubmit {
	
	/** Íø¹ØurlµØÖ· */
	var $gateUrl;
	
	/** ÃÜÔ¿ */
	var $key;
	
	/** ÇëÇóµÄ²ÎÊý */
	var $parameters;
	
	/** µ÷ÊÔÐÅÏ¢ */
	var $debugMsg;
	
	function __construct() {
		$this->GatewaySubmit();
	}
	
	function GatewaySubmit() {
		$this->gateUrl = "https://www.epaylinks.cn/paycenter/v2.0/getoi.do";
		$this->key = "";
		$this->parameters = array();
		$this->debugMsg = "";
	}
	
	/**
	*»ñÈ¡Èë¿ÚµØÖ·,²»°üº¬²ÎÊýÖµ
	*/
	function getGateURL() {
		return $this->gateUrl;
	}
	
	/**
	*ÉèÖÃÈë¿ÚµØÖ·,²»°üº¬²ÎÊýÖµ
	*/
	function setGateURL($gateUrl) {
		$this->gateUrl = $gateUrl;
	}
	
	/**
	*»ñÈ¡ÃÜÔ¿
	*/
	function getKey() {
		return $this->key;
	}
	
	/**
	*ÉèÖÃÃÜÔ¿
	*/
	function setKey($key) {
		$this->key = $key;
	}
	
	/**
	*»ñÈ¡²ÎÊýÖµ
	*/
	function getParameter($parameter) {
		return $this->parameters[$parameter];
	}
	
	/**
	*ÉèÖÃ²ÎÊýÖµ
	*/
	function setParameter($parameter, $parameterValue) {
		$this->parameters[$parameter] = $parameterValue;
	}
	
	/**
	*»ñÈ¡ËùÓÐÇëÇóµÄ²ÎÊý
	*@return array
	*/
	function getAllParameters() {
		return $this->parameters;
	}
	
	/**
	*»ñÈ¡´ø²ÎÊýµÄÇëÇóURL
	*/
	function getRequestURL() {
	
		$this->buildRequestSign();
		
		$reqPar = "";
		ksort($this->parameters);
		foreach($this->parameters as $k => $v) {
			$reqPar .= $k . "=" . urlencode($v) . "&";
		}
		
		//È¥µô×îºóÒ»¸ö&
		$reqPar = substr($reqPar, 0, strlen($reqPar)-1);
		
		$requestURL = $this->getGateURL() . "?" . $reqPar;
		
		return $requestURL;
		
	}
		
	/**
	*»ñÈ¡µ÷ÊÔÐÅÏ¢
	*/
	function getDebugMsg() {
		return $this->debugMsg;
	}
	
	/**
	*ÖØ¶¨Ïòµ½Ö§¸¶
	*/
	function doSend() {
		header("Location:" . $this->getRequestURL());
		exit;
	}
	
	/**
	*Éú³ÉSHA256ÕªÒª,¹æÔòÊÇ:°´ASCIIÂëË³ÐòÅÅÐò,Óöµ½¿ÕÖµµÄ²ÎÊý²»²Î¼ÓÇ©Ãû¡£
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
		
		//µ÷ÊÔÐÅÏ¢
		$this->_setDebugMsg($signOrigStr . " => sign:" . $sign);
		
	}	
	
	/**
	*ÉèÖÃµ÷ÊÔÐÅÏ¢
	*/
	function _setDebugMsg($debugMsg) {
		$this->debugMsg = $debugMsg;
	}

}

?>
