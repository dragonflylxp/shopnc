<?php 
/**
 * 支付接口
 *
 */
defined('Inshopec') or exit('Access Invalid!');

require_once("ghtpay.config.php");
require_once("lib/ghtpay_submit.class.php");
require_once("lib/ghtpay_notify.class.php");

class ghtpay {
    /*
     * 高汇通现金支付
    */
    public function submit($param){
        $ghtpay_submit = new GhtPaySubmit($param);
        $ghtpay_submit->setParameter("busi_code","PAY");
        $gate_url = $ghtpay_submit->getGateURL();
        $request_params = $ghtpay_submit->getAllParameters();
        $html_text = $this->_sendRequest($gate_url, $request_params, 'get', '正在跳转支付页面...');
        return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                <html>
                      <head>
                            <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                            <title>高汇通即时到账交易接口</title>
                      </head>
                      <body>'
                            .$html_text.'
                      </body>
                </html>';
            
        }

    /**
    * 发送支付请求,form表单形式
    * @param $request_params 表单参数 
    * @param $method 提交方式。两个值可选：post、get
    * @param $button_name 确认按钮显示文字
    * @return 提交HTML文本
    */
    private function _sendRequest($gate_url, $request_params, $method, $button_name) {
        $sHtml = "<form id='ghtpaysubmit' name='ghtpaysubmit' action='".$gate_url."' method='".$method."'>";
        foreach($request_params as $key => $val) {
            $sHtml.= "<input type='hidden' name='".$key."' value='".$val."'/>";
        }
        $sHtml = $sHtml."<input type='submit' value='".$button_name."'></form>";
        $sHtml = $sHtml."<script>document.forms['ghtpaysubmit'].submit();</script>";
        return $sHtml;
    }

    /**
     * 获取return信息
     */
    public function getReturnInfo($payment_config) {
        $verify = $this->_verify('return', $payment_config);

        if($verify) {
            if ($_GET['pay_result'] == 1){
                return array(
                    //商户订单号
                    'out_trade_no' => $_GET['order_no'],
                    //高汇通交易号
                    'trade_no' => $_GET['pay_no'],
                );
            }
        }

        return false;
    }

    /**
     * 获取notify信息
     */
    public function getNotifyInfo($payment_config) {
        $verify = $this->_verify('notify', $payment_config);

        if($verify) {
            if ($_POST['pay_result'] == 1){
                return array(
                    //商户订单号
                    'out_trade_no' => $_POST['order_no'],
                    //高汇通交易号
                    'trade_no' => $_POST['pay_no'],
                );
            }
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

        $ghtpay_config = array(
            'key' => $payment_config['key'],
        );

        //计算得出通知验证结果
        $ghtpayNotify = new GhtpayNotify($ghtpay_config);

        switch ($type) {
            case 'notify':
                $verify_result = $ghtpayNotify->verifyNotify();
                break;
            case 'return':
                $verify_result = $ghtpayNotify->verifyReturn();
                break;
            default:
                $verify_result = false;
                break;
        }

        return $verify_result;
    }
}
?>
