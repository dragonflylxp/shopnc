<?php

/**
* AES-128-EBC 加密算法(不需要初始化向量iv)
*
 */
class AESCrypt {
    protected $cipher = MCRYPT_RIJNDAEL_128;
    protected $mode = MCRYPT_MODE_ECB;
    protected $secret_key = 'lOi0wype2lE9NwAb';

    public function get_aes_key()
    {
        return $this->secret_key;
    }

    public function set_cipher($cipher)
    {
        $this->cipher = $cipher;
    }

    public function set_mode($mode)
    {
        $this->mode = $mode;
    }

    public function set_key($key)
    {
        $this->secret_key = $key;
    }


    public function encrypt($data)
    {
        return mcrypt_encrypt($this->cipher,$this->secret_key, self::pad($data), $this->mode);
    }

    public function decrypt($data) {
        return mcrypt_decrypt($this->cipher, $this->secret_key, self::pad($data), $this->mode);
    }
    
    private static function pad($data, $blocksize = 16) {
        $pad = $blocksize - (strlen($data) % $blocksize);
        return $data . str_repeat(chr($pad), $pad);
    }  
}
?>
