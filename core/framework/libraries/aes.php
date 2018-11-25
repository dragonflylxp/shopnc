<?php
/**
 * Created by PhpStorm.
 * User: gongbo
 * Date: 2016/11/21/021
 * Time: 11:59
 * aes加密类
 */

defined('Inshopec') or exit('Access Invalid!');
class Aes{
    public $key = 'XMzDTG7D74CKm1Lx';
    public function encrypt($input,$key=null) {
        $key = $key?$key:$this->key;
        $size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
        $input = self::pkcs5_pad($input, $size);
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_ECB, '');
        $iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        mcrypt_generic_init($td, $key, $iv);
        $data = mcrypt_generic($td, $input);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        $data = base64_encode($data);
        return $data;
    }

    private function pkcs5_pad ($text, $blocksize) {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    public function decrypt($sStr,$key=null) {
        $key = $key?$key:$this->key;
        $decrypted= @mcrypt_decrypt(MCRYPT_RIJNDAEL_128,$key,base64_decode($sStr),MCRYPT_MODE_ECB);
        $dec_s = strlen($decrypted);
        $padding = ord($decrypted[$dec_s-1]);
        $decrypted = substr($decrypted, 0, -$padding);
        return $decrypted;
    }




}