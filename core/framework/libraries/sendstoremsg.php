<?php
/**
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
class sendStoreMsg {
    private $code = '';
    private $store_id = 0;

    /**
     * 设置
     *
     * @param mixed $key
     * @param mixed $value
     */
    public function set($key,$value){
        $this->$key = $value;
    }

    public function send($param = array()) {
        $msg_tpl = rkcache('store_msg_tpl', true);
        if (!isset($msg_tpl[$this->code]) || $this->store_id <= 0) {
            return false;
        }

        $tpl_info = $msg_tpl[$this->code];

        $setting_info = Model('store_msg_setting')->getStoreMsgSettingInfo(array('smt_code' => $this->code, 'store_id' => $this->store_id));
        // 发送站内信
        if ($tpl_info['smt_message_switch'] && ($tpl_info['smt_message_forced'] || $setting_info['sms_message_switch'])) {
            $message = ncReplaceText($tpl_info['smt_message_content'],$param);
            $this->sendMessage($message);
        }
        // 发送短消息
        if ($tpl_info['smt_short_switch'] && $setting_info['sms_short_number'] != '' && ($tpl_info['smt_short_forced'] || $setting_info['sms_short_switch'])) {

            
                	if(C('sms.smsNumber') == 2){       //511613932     
                		$param['site_name'] = C('site_name');
                		$message = ncReplaceText($tpl_info['smt_short_content'],$param);
                	}else{
                		$message = $tpl_info['smt_short_content'];
                		$param['template'] = $tpl_info['sms_code'];
                	}              
            $this->sendShort($setting_info['sms_short_number'], $message,$param);//511613932
        }
        // 发送邮件
        if ($tpl_info['smt_mail_switch'] && $setting_info['sms_mail_number'] != '' && ($tpl_info['smt_mail_forced'] || $setting_info['sms_mail_switch'])) {
            $param['site_name'] = C('site_name');
            $param['mail_send_time'] = date('Y-m-d H:i:s');
            $subject = ncReplaceText($tpl_info['smt_mail_subject'],$param);
            $message = ncReplaceText($tpl_info['smt_mail_content'],$param);
            $this->sendMail($setting_info['sms_mail_number'], $subject, $message);
        }
    }

    /**
     * 发送站内信
     * @param unknown $message
     */
    private function sendMessage($message) {
        $insert = array();
        $insert['smt_code'] = $this->code;
        $insert['store_id'] = $this->store_id;
        $insert['sm_content'] = $message;
        Model('store_msg')->addStoreMsg($insert);
    }

    /**
     * 发送短消息
     * @param unknown $number
     * @param unknown $message
     */
    private function sendShort($number, $message,$param){//511613932
        $sms = new Sms();
        $sms->send($number, $message,$param);//511613932
    }

    /**
     * 发送邮件
     * @param unknown $number
     * @param unknown $subject
     * @param unknown $message
     */
    private function sendMail($number, $subject, $message) {
        // 即时发动代码
        // \shopec\Lib::messager()->send($this->store_number['store_msg_mail'],$subject,$message);

        // 计划任务代码
        $insert = array();
        $insert['mail'] = $number;
        $insert['subject'] = $subject;
        $insert['contnet'] = $message;
        Model('mail_cron')->addMailCron($insert);
    }
}
