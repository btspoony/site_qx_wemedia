<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Mcrypt
 *
 * @author jasper
 */
class Lib_mcrypt extends Library {

    var $key;
    /**
     * new
     * @param string $key
     */
    function __construct($key) {        
        $this->key = $key[0];
    }
    /**
     * pkcs5加密
     * @param string $input
     * @return string
     */
    function encrypt($input) {
        $size = mcrypt_get_block_size('des', 'ecb');
        $input = $this->pkcs5_pad($input, $size);
        $key = $this->key;
        $td = mcrypt_module_open('des', '', 'ecb', '');
        $iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        @mcrypt_generic_init($td, $key, $iv);
        $data = mcrypt_generic($td, $input);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        $data = base64_encode($data);
        return $data;
    }
    /**
     * 非pkcs5加密
     * @param string $input
     * @return string
     */
    function encrypt_short($input) {
        $size = mcrypt_get_block_size('des', 'ecb');
        //$input = $this->pkcs5_pad($input, $size);        
        $key = $this->key;
        $td = mcrypt_module_open('des', '', 'ecb', '');
        $iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        @mcrypt_generic_init($td, $key, $iv);
        $data = mcrypt_generic($td, $input);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        $data = base64_encode($data);
        return $data;
    }
    /**
     * pkcs5解密
     * @param string $encrypted
     * @return string
     */
    function decrypt($encrypted) {
        $encrypted = base64_decode($encrypted);
        $key = $this->key;
        $td = mcrypt_module_open('des', '', 'ecb', '');
        //使用MCRYPT_DES算法,cbc模式                 
        $iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        $ks = mcrypt_enc_get_key_size($td);
        @mcrypt_generic_init($td, $key, $iv);
        //初始处理                 
        $decrypted = mdecrypt_generic($td, $encrypted);
        //解密               
        mcrypt_generic_deinit($td);
        //结束             
        mcrypt_module_close($td);
//        $y = $this->pkcs5_unpad($decrypted);
//        return $y;
        return $decrypted;
    }
    /**
     * 非pkcs5解密
     * @param string $encrypted
     * @return string
     */
    function decrypt_short($encrypted) {
        $encrypted = base64_decode($encrypted);
        $key = $this->key;
        $td = mcrypt_module_open('des', '', 'ecb', '');
        //使用MCRYPT_DES算法,cbc模式                 
        $iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        $ks = mcrypt_enc_get_key_size($td);
        @mcrypt_generic_init($td, $key, $iv);
        //初始处理                 
        $decrypted = mdecrypt_generic($td, $encrypted);
        //解密               
        mcrypt_generic_deinit($td);
        //结束             
        mcrypt_module_close($td);
        //$y=$this->pkcs5_unpad($decrypted);          
        return $decrypted;
    }

    private function pkcs5_pad($text, $blocksize) {
//        $pad = $blocksize - (strlen($text) % $blocksize);
        $pad = $blocksize - (strlen(@$pad) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    private function pkcs5_unpad($text) {
        $pad = ord($text{strlen($text) - 1});
        if ($pad > strlen($text))
            return $text;
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad)
            return $text;
        return substr($text, 0, -1 * $pad);
    }

}