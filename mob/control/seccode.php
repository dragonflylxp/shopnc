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
    /**
     * 生成验证码标识
     */
    public function makecodekeyOp(){
        $key = $this->makeApiSeccodeKey();
        output_data(array('codekey' => $key));
    }

    /**
     * 产生验证码
     */
    public function makecodeOp(){
        $param = $_GET;
        $key = $param['k']?trim($param['k']):'';
        if (!$key) {
            die(false);
        }
        $seccode = $this->makeApiSeccode();
        $result = Model('apiseccode')->addApiSeccode($key,$seccode);
        if (!$result) {
            die(false);
        }
        @header("Expires: -1");
        @header("Cache-Control: no-store, private, post-check=0, pre-check=0, max-age=0", FALSE);
        @header("Pragma: no-cache");
        echo \shopec\Lib::imager()->createCaptcha($seccode, 90, 26);
        die;
    }
    /**
     * 产生验证码名称标识
     *
     * @param string $nchash 哈希数
     * @return string
     */
    private function makeApiSeccodeKey(){
        return md5(uniqid(mt_rand(), true));
    }
    /**
     * 产生验证码
     *
     * @param string $nchash 哈希数
     * @return string
     */
    private function makeApiSeccode(){
        $seccode = random(6, 1);
        $seccodeunits = '';

        $s = sprintf('%04s', base_convert($seccode, 10, 23));
        $seccodeunits = 'ABCEFGHJKMPRTVXY2346789';
        if($seccodeunits) {
            $seccode = '';
            for($i = 0; $i < 4; $i++) {
                $unit = ord($s{$i});
                $seccode .= ($unit >= 0x30 && $unit <= 0x39) ? $seccodeunits[$unit - 0x30] : $seccodeunits[$unit - 0x57];
            }
        }
        return $seccode;
    }
}
