<?php









/**

 * 网银在线接口类

 *

 

 */

defined('Inshopec') or exit('Access Invalid!');



class jdpay{

	/**

	 * 网银在线网关

	 *

	 * @var string

	 */

/*	private $gateway   = 'https://Pay3.jdpay.com.cn/PayGate';*/

	private $gateway   = 'https://m.jdpay.com/wepay/web/pay';

	/**

	 * 支付接口标识

	 *

	 * @var string

	 */

    private $code      = 'jdpay';

    /**

	 * 支付接口配置信息

	 *

	 * @var array

	 */

    private $payment;

     /**

	 * 订单信息

	 *

	 * @var array

	 */

    private $order;

    /**

	 * 发送至网银在线的参数

	 *

	 * @var array

	 */

    private $parameter;

    /**

     * 支付状态

     * @var unknown

     */

    private $pay_result;

    



	/**

	 * 支付表单

	 *获取支付接口的请求地址

	 */

	public function submit($orderinfo){

	require_once ("class/signUtil.php");		

		$v_oid = $orderinfo['order_sn'];															//订单号

		$v_amount = $orderinfo['order_amount']*100;                  			//支付金额                 

        $v_moneytype = "CNY";                                           //币种

        $subject = $orderinfo['subject'];

		$v_mid = $orderinfo['jdpay_partnerid'];	// 商户号，这里为测试商户号1001，替换为自己的商户号(老版商户号为4位或5位,新版为8位)即可

		$v_url = MOBILE_SITE_URL."/api/payment/jdpay/return_url.php";	// 请填写返回url,地址应为绝对路径,带有http协议

		$n_url =  MOBILE_SITE_URL."/api/payment/jdpay/notify_url.php";

		$f_url =  MOBILE_SITE_URL."/api/payment/jdpay/fail_url.php";

		$key   = $orderinfo['jdpay_partnerkey'];			// 如果您还没有设置MD5密钥请登陆我们为您提供商户后台，地址：https://merchant3.jdpay.com.cn/



		$param = array();

		$param["currency"] = $v_moneytype;

		$param['failCallbackUrl'] = $f_url;

		$param["merchantNum"] = $v_mid;

		$param["merchantRemark"] = $subject;

		$param["notifyUrl"] = $n_url;

		$param["successCallbackUrl"] = $v_url;

	

		$param["tradeAmount"] = $v_amount;

		$param["tradeDescription"] = $subject;

		$param["tradeName"] = $subject;

		$param["tradeNum"] = $v_oid;

		$param["tradeTime"] = date('Y-m-d H:i:s', time());

		$param["version"] = '1.0';

		$param["token"] ='';



		$sign = SignUtil::sign($param);

		$param["merchantSign"] = $sign;

	

		$_SESSION['tradeAmount'] = $v_amount;

		$_SESSION['tradeName'] = $subject;

		$_SESSION['tradeInfo'] = $param;





		$html = '<html><meta charset="utf-8"/><head></head><body>';

		$html .= '<form method="post" name="E_FORM" action="https://m.jdpay.com/wepay/web/pay">';

		foreach ($param as $key => $val){

			$html .= "<input type='hidden' name='$key' value='$val' />";

		}

		$html .= '</form><script type="text/javascript">document.E_FORM.submit();</script>';

		$html .= '</body></html>';

		echo $html;

		exit;

	}



/*================================================================================*/







  /**

     * 获取return信息

     */

    public function getReturnInfo($payment_config) {

        $verify = $this->_verify('return', $payment_config);

		

        if($verify) {

            return array(

                //商户订单号

                'out_trade_no' => $_GET['out_trade_no'],

                //支付宝交易号

                'trade_no' => $_GET['trade_no'],

            );

        }



        return false;

    }





/**

     * 验证返回信息

     */

    private function _verify($type, $payment_config) {

	

        if(empty($payment_config)) {

            return false;

        }

          switch ($type) {

            case 'notify':

                $verify_result = $this->notify_verify();

                break;

            case 'return':



                $verify_result = $this->return_verify();

                break;

            default:

                $verify_result = false;

                break;

        }



		return $verify_result;

		

    }





    private function return_verify(){



    	$param = array();

		$param["token"]=$_GET["token"];

		$param["tradeNum"]=$_GET["tradeNum"];

		$param["tradeAmount"]=$_GET["tradeAmount"];

		$param["tradeCurrency"]=$_GET["tradeCurrency"];

		$param["tradeDate"]=$_GET["tradeDate"];

		$param["tradeTime"]=$_GET["tradeTime"];

		$param["tradeStatus"]=$_GET["tradeStatus"];



		include './class/SignUtil.php';	

		$sign  = $_GET["sign"];

		// echo "oldSign=".$sign."<br>";

		ksort($param);//拼装字符串前要先排序，SignUtil::signString没给排序

		$strSourceData=SignUtil::signString($param,array());

		$decryptStr=RSAUtils::decryptByPublicKey($sign);

		$sha256SourceSignString=hash("sha256", $strSourceData);

		$_SESSION ['queryDatas'] =null;

		

		if (strcasecmp($decryptStr,$sha256SourceSignString)==0){

			return true;

		}else{

			return false;

		}



    	

    }

/*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/



	/**

	 * 返回地址验证(异步)

	 * @return boolean

	 */

	public function notify_verify() {

	  	$resp =$_POST ( "resp" );

		$desKey = 'ta4E/aspLA3lgFGKmNDNRYU92RkZ4w2t';

		$md5Key ='test';

		// 获取通知原始信息

		// echo "异步通知原始数据:" . $resp . "\n";

		if (null == $resp) {

			return;

		}



		// 获取配置密钥

		// echo "desKey:" . $desKey . "\n";

		// echo "md5Key:" . $md5Key . "\n";

		// 解析XML

		$params = $this->xml_to_array ( base64_decode ( $resp ) );



		$ownSign = $this->generateSign ( $params, $md5Key );

		$params_json = json_encode ( $params );

		// echo "解析XML得到对象:" . $params_json . '\n';

		// echo "根据传输数据生成的签名:" . $ownSign . "\n";

		

		if ($params ['SIGN'] [0] == $ownSign) {

			// 验签不对

			echo "签名验证正确!" . "\n";

		} else {

			echo "签名验证错误!" . "\n";

			return;

		}

		include './class/DesUtils.php';

		// 验签成功，业务处理

		// 对Data数据进行解密

		$des = new DesUtils (); // （秘钥向量，混淆向量）

		$decryptArr = $des->decrypt ( $params ['DATA'] [0], $desKey ); // 加密字符串

		if($decryptArr['status']==0){

			return true;

		}else{

			return false;

		}

		 echo "对<DATA>进行解密得到的数据:" . $decryptArr . "\n";

		 $params ['data'] = $decryptArr;

		 echo "最终数据:" . json_encode ( $params ) . '\n';

		 echo "**********接收异步通知结束。**********";

		

		return;

    

	}



	/**

	 * 取得订单支付状态，成功或失败

	 *

	 * @param array $param

	 * @return array

	 */

	public function getPayResult($param){



	    return $param['tradeStatus']?false:true;

	}



	public function __get($name){

	    return $this->$name;

	}



	public function xml_to_array($xml) {

		$array = ( array ) (simplexml_load_string ( $xml ));

		foreach ( $array as $key => $item ) {

			$array [$key] = $this->struct_to_array ( ( array ) $item );

		}

		return $array;

	}

	public function struct_to_array($item) {

		if (! is_string ( $item )) {

			$item = ( array ) $item;

			foreach ( $item as $key => $val ) {

				$item [$key] = $this->struct_to_array ( $val );

			}

		}

		return $item;

	}

	

	/**

	 * 签名

	 */

	public function generateSign($data, $md5Key) {

		$sb = $data ['VERSION'] [0] . $data ['MERCHANT'] [0] . $data ['TERMINAL'] [0] . $data ['DATA'] [0] . $md5Key;

		

		return md5 ( $sb );

	}

}

