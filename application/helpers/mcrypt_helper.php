<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * @var array
 */
$mcrypt_config;
/**
 * @var Mcrypt
 */
$password_mcrypt;
/**
 * @var Mcrypt
 */
$voucher_sn_mcrypt;

if (!function_exists('encrypt_password')) {

    /**
     * 用户密码加密
     * @param string $password
     * @return string
     */
    function encrypt_password($password) {
        $mcrypt = get_password_mcrypt();
       
        return $mcrypt->encrypt_short(base64_encode($password));
    }

}
if (!function_exists('decrypt_password')) {

    /**
     * 用户密码解密
     * @param string $password
     * @return string
     */
    function decrypt_password($password) {
        $mcrypt = get_password_mcrypt();
        return base64_decode($mcrypt->decrypt_short($password));
    }

}
if (!function_exists('encrypt_voucher_sn')) {

    /**
     * 现金券加密
     * @param string $voucher_sn
     * @return string
     */
    function encrypt_voucher_sn($voucher_sn) {
        $mcrypt = get_voucher_sn_mcrypt();
        return $mcrypt->encrypt(base64_encode($voucher_sn));
    }

}
if (!function_exists('decrypt_voucher_sn')) {

    /**
     * 现金券解密
     * @param string $voucher_sn
     * @return string
     */
    function decrypt_voucher_sn($voucher_sn) {
        $mcrypt = get_voucher_sn_mcrypt();
        return base64_decode($mcrypt->decrypt($voucher_sn));
    }

}
if (!function_exists('get_mcrypt_config')) {

    /**
     * @global array $mcrypt_config
     * @return array
     */
    function get_mcrypt_config() {
        global $mcrypt_config;
        if (empty($mcrypt_config)) {
            $CI = & get_instance();
            $CI->config->load('mcrypt', TRUE, TRUE);
            $mcrypt_config = $CI->config->config['mcrypt'];
        }
        return $mcrypt_config;
    }

}

if (!function_exists('get_password_mcrypt')) {

    /**
     * @global Mcrypt $password_mcrypt
     * @return Mcrypt
     */
    function get_password_mcrypt() {
        global $password_mcrypt;
        if (empty($password_mcrypt)) {
            $mcrypt = get_mcrypt_config();
            $CI = & get_instance();
            $CI->load->library("lib_mcrypt", array(substr(md5($mcrypt['password_key']), 0, 0x8)), 'password_mcrypt');
            $password_mcrypt = $CI->password_mcrypt;
        } 
        return $password_mcrypt;
    }

}

if (!function_exists('get_voucher_sn_mcrypt')) {

    /**
     * @global Mcrypt $voucher_sn_mcrypt
     * @return Mcrypt
     */
    function get_voucher_sn_mcrypt() {
        global $voucher_sn_mcrypt;
        if (empty($voucher_sn_mcrypt)) {
            $mcrypt = get_mcrypt_config();
            $CI = & get_instance();
            $CI->load->library("lib_mcrypt", array(substr(md5($mcrypt['voucher_sn_key']), 0, 0x8)), 'voucher_sn_mcrypt');
            $voucher_sn_mcrypt = $CI->voucher_sn_mcrypt;
        }
        return $voucher_sn_mcrypt;
    }

}

if (!function_exists("url_encode")) {

    /**
     * url加密
     *
     * @return  str
     */
    function url_encode($string) {
        $mcrypt = get_mcrypt_config();
        $td = mcrypt_module_open(MCRYPT_DES, '', 'ecb', ''); //使用MCRYPT_DES算法,ecb模式
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        $ks = mcrypt_enc_get_key_size($td);
        $key = $mcrypt["url_key"]; //密钥
        $key = substr(md5($key), 0, $ks);
        $string = base64_encode($string);

        mcrypt_generic_init($td, $key, $iv); //初始处理
        //加密
        $encrypted = mcrypt_generic($td, $string);

        //结束处理
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        $code = base64_encode($encrypted);
        $code = str_replace('+', "!", $code); //把所用"+"替换成"!"
        $code = str_replace('/', "*", $code); //把所用"/"替换成"*"
        $code = str_replace('=', "", $code); //把所用"="删除掉
        return $code;
    }

}


if (!function_exists("url_decode")) {

    /**
     * mcrypt解密为了url
     *
     * @return  str
     */
    function url_decode($string) {
        $mcrypt = get_mcrypt_config();
        $string = str_replace("!", '+', $string); //把所用"+"替换成"!"
        $string = str_replace("*", '/', $string); //把所用"/"替换成"*"

        $td = mcrypt_module_open(MCRYPT_DES, '', 'ecb', ''); //使用MCRYPT_DES算法,ecb模式
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        $ks = mcrypt_enc_get_key_size($td);
        $key = $mcrypt["url_key"]; //密钥
        $key = substr(md5($key), 0, $ks);

        mcrypt_generic_init($td, $key, $iv); //初始处理
        //解密
        $decrypted = mdecrypt_generic($td, base64_decode($string));

        //结束处理
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        //解密后,可能会有后续的\0,需去掉
        return trim(base64_decode($decrypted));
    }

}

if (!function_exists("invoke_voucher_sn")) {
    /**
     * 
     * @param array $voucher
     */
    function invoke_voucher_sn(&$voucher) {
        if (isset($voucher["voucher_des"])) {
            $voucher["voucher_sn"] = decrypt_voucher_sn($voucher["voucher_des"]);
        }
    }
}

if (!function_exists("array_invoke_voucher_sn")) {
    /**
     * 
     * @param array $voucher_list
     */
    function array_invoke_voucher_sn(&$voucher_list) {
        array_walk($voucher_list, "invoke_voucher_sn");
    }
}

if (!function_exists("invoke_voucher_des")) {
    /**
     * 
     * @param array $voucher
     */
    function invoke_voucher_des(&$voucher) {
        if (isset($voucher["voucher_sn"])) {
            $voucher["voucher_des"] = encrypt_voucher_sn($voucher["voucher_sn"]);
        }
    }
}

if (!function_exists("array_invoke_voucher_des")) {
    /**
     * 
     * @param array $voucher_list
     */
    function array_invoke_voucher_des(&$voucher_list) {
        array_walk($voucher_list, "invoke_voucher_des");
    }
}