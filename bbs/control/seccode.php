<?php
/**
 * 验证码
 *
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net
 * @link       http://www.shopec.net
 * @since      File available since Release v1.1
 */

use shopec\Tpl;

defined('Inshopec') or exit('Access Invalid!');

class seccodeControl{
    public function __construct(){
    }

    /**
     * 产生验证码
     *
     */
    public function makecodeOp(){
        $refererhost = parse_url($_SERVER['HTTP_REFERER']);
        $refererhost['host'] .= !empty($refererhost['port']) ? (':'.$refererhost['port']) : '';
        $seccode = makeSeccode($_GET['nchash']);

        @header("Expires: -1");
        @header("Cache-Control: no-store, private, post-check=0, pre-check=0, max-age=0", FALSE);
        @header("Pragma: no-cache");

        echo \shopec\Lib::imager()->createCaptcha($seccode, 90, 26);
    }

    /**
     * AJAX验证
     *
     */
    public function checkOp(){
        if (checkSeccode($_GET['nchash'],$_GET['captcha'])){
            exit('true');
        }else{
            exit('false');
        }
    }
}
