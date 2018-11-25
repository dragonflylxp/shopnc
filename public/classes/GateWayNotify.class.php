<?php
/** 
 *  Ó¦´ð¸¨ÖúÀà    
 */
class GatewayNotify  {
	
	/** ÉÌ»§ÃÜÔ¿ */
	var $key;
	
	/** Ó¦´ðµÄ²ÎÊý */
	var $parameters;
	
	/** µ÷ÊÔÐÅÏ¢ */
	var $debugMsg;
	
	function __construct() {
		$this->GatewayNotify();
	}
	
	function GatewayNotify() {
		$this->key = "";
		$this->parameters = array();
		$this->debugMsg = "";
		
		foreach($_GET as $k => $v) {
			$this->setParameter($k, $v);
		}
		foreach($_POST as $k => $v) {
			$this->setParameter($k, $v);
		}
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
	*»ñÈ¡È«²¿²ÎÊý
	*@return array
	*/
	function getAllParameters() {
		return $this->parameters;
	}	
	
	/**
	*Ê¹ÓÃSHA256Ëã·¨ÑéÖ¤Ç©Ãû¡£¹æÔòÊÇ:°´²ÎÊýÃû³Æa-zÅÅÐò,Óöµ½¿ÕÖµµÄ²ÎÊý²»²Î¼ÓÇ©Ãû¡£
	*true:ÊÇ
	*false:·ñ
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
				
		//debugÐÅÏ¢
		$this->setDebugMsg($signPars . " => sign:" . $sign .
				" epaylinksSign:" . $this->getParameter("sign"));
		
		return $sign == $tenpaySign;
		
	}
	
	/**
	*  µ÷ÊÔÐÅÏ¢
	*/	
	function getDebugMsg() {
		return $this->debugMsg;
	}

	/**
	*  µ÷ÊÔÐÅÏ¢
	*/	
	function setDebugMsg($debugMsg) {
		$this->debugMsg = $debugMsg;
	}
	
}


?>
