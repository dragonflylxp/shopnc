<?php

/**

 * 验证码

 *

 */







defined('Inshopec') or exit('Access Invalid!');

class seccodeControl{

    public function __construct(){

    }

    

    /**

     * 产生验证码

     *

     */

    public function makecodekeyOp(){



        output_data(array('codekey' => getNchash()));

    }



    /**

     * 产生验证码

     *

     */

    public function makecodeOp(){

        $refererhost = parse_url($_SERVER['HTTP_REFERER']);

        $refererhost['host'] .= !empty($refererhost['port']) ? (':'.$refererhost['port']) : '';



        $seccode = makeSeccode($_GET['k']);



        @header("Expires: -1");

        @header("Cache-Control: no-store, private, post-check=0, pre-check=0, max-age=0", FALSE);

        @header("Pragma: no-cache");



        $code = new seccode();

        $code->code = $seccode;

        $code->width = 120;

        $code->height = 50;

        $code->background = 2;

        $code->adulterate = 30;

        $code->scatter = 0;

        $code->color = 5;

        $code->size = 2;

        $code->shadow = 1;

        $code->animator = 0;

        $code->datapath =  BASE_DATA_PATH.'/resource/seccode/';

        $code->display();

    }



    /**

     * AJAX验证

     *

     */

    public function checkOp(){

        if (checkSeccode($_GET['codekey'],$_GET['captcha'])){

            exit('true');

        }else{

            exit('false');

        }

    }

}

