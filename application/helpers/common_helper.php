<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


if (!function_exists('mySort')) {
    function mySort($arrays,$sort_key,$sort_order=SORT_ASC,$sort_type=SORT_NUMERIC ){
        if (empty($arrays)){
            return array();
        }
        if(is_array($arrays)){   
            foreach ($arrays as $array){   
                if(is_array($array)){   
                    $key_arrays[] = $array[$sort_key];   
                }else{   
                    return false;   
                }   
            }   
        }else{   
            return false;   
        }  
        array_multisort($key_arrays,$sort_order,$sort_type,$arrays);   
        return $arrays;   
    } 
}

if (!function_exists('requestGet')) {

    /**
     * 发送get请求
     * @param string $url
     * @return bool|mixed
 */
    function requestGet($url = '') {
        if (empty($url)) {
            return false;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

}
    
if (!function_exists('selectInput')) {

    /**
     * select 复选框
     * @param type $data 数据
     * @param type $selectedId 默认选项
     * @param type $valueField value 字段
     * @param type $textField text text 字段
     * @param type $params 其他参数
     * @return string
     * selectInput(array('id'=>1))
     */
    function selectInput($data = null, $selectedId = null, $valueField = null, $textField = null, $params = array()) {
        if (!is_array($data) || !is_array($params)) {
            return;
        }
        //选择框参数
        $selectParams = null;
        foreach ($params as $k => $v) {
            $selectParams .= "{$k}='{$v}' ";
        }
        $ret_data = "<select  {$selectParams}><option value=''>--请选择--</option>";
        $selecteded = null;
        foreach ($data as $v) {
            if ($selectedId == $v[$valueField]) {
                $selecteded = "selected";
            } else {
                $selecteded = null;
            }
            $ret_data .= "<option value='{$v[$valueField]}' {$selecteded} >{$v[$textField]}</option>";
        }
        $ret_data .="</select>";
//    echo $ret_data;die();
        return $ret_data;
    }

}

if (!function_exists('setVal')) {
    /**
 * 获取post 数据
 * @param type $field
 * @return type
 */
function setVal($field = null) {
    if (empty($field) || !isset($_POST[$field])) {
        return;
    }
    return $_POST[$field];
}

}

if (!function_exists('percent')) {
    /**
     * 百分比处理
     * @param type $val
     * @return type
     */
    function percent($val,$position = 2) {
        return round($val*100,$position).'%';
    }
}

if (!function_exists('number_handle')) {
    /**
     * 数字处理
     * @param type $val
     * @param type $position
     * @return type
     */
    function number_handle($val,$position =2) {
        return round($val,$position);
    }
}

if (!function_exists('real_ip')) {

    /**
     * 获得用户的真实IP地址
     * @return  string
     */
    function real_ip() {
        static $realip = NULL;
        if (!is_null($realip))
            return $realip;
        if (isset($_SERVER)) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

                /* 取X-Forwarded-For中第一个非unknown的有效IP字符串 */
                foreach ($arr AS $ip) {
                    $ip = trim($ip);

                    if ($ip != 'unknown') {
                        $realip = $ip;

                        break;
                    }
                }
            } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $realip = $_SERVER['HTTP_CLIENT_IP'];
            } else {
                if (isset($_SERVER['REMOTE_ADDR'])) {
                    $realip = $_SERVER['REMOTE_ADDR'];
                } else {
                    $realip = '0.0.0.0';
                }
            }
        } else {
            if (getenv('HTTP_X_FORWARDED_FOR')) {
                $realip = getenv('HTTP_X_FORWARDED_FOR');
            } elseif (getenv('HTTP_CLIENT_IP')) {
                $realip = getenv('HTTP_CLIENT_IP');
            } else {
                $realip = getenv('REMOTE_ADDR');
            }
        }

        preg_match("/[\d\.]{7,15}/", $realip, $onlineip);
        $realip = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';

        return $realip;
    }

}

/**
 * 编译密码函数
 * @param   array   $cfg 包含参数为 $password, $md5password, $salt, $type
 * @return void
 */
if (!function_exists('compile_password')) {

    function compile_password($cfg) {
        if (isset($cfg['password'])) {
            $CI = &get_instance();
            $CI->load->helper('mcrypt');
            $cfg['md5password'] = encrypt_password($cfg['password']);
        }
        if (empty($cfg['type'])) {
            $cfg['type'] = PWD_MCRYPT;
        }
        switch ($cfg ['type']) {
            case PWD_MCRYPT :
                return $cfg['md5password'];

            case PWD_MD5 :
                return $cfg['md5password'];

            case PWD_PRE_SALT :
                if (empty($cfg['salt'])) {
                    $cfg['salt'] = '';
                }

                return md5($cfg['salt'] . $cfg['md5password']);

            case PWD_SUF_SALT :
                if (empty($cfg['salt'])) {
                    $cfg['salt'] = '';
                }

                return md5($cfg['md5password'] . $cfg['salt']);

            default :
                return '';
        }
    }

}

if (!function_exists('p')) {

    /**
     * 格式化输出
     */
    function p($val, $die = FALSE) {
        echo "<pre>";
        print_r($val);
        echo "</pre>";
        if (!empty($die)) {
            die($die);
        }
    }

}


if (!function_exists('default_rules')) {

    /**
     * 表单验证
     * $file_name 文件名称
     */
    function default_rules($file_name = null) {
        if (file_exists(FORM_VALIdATION_DEFAULT . $file_name . '.php')) {
            include_once FORM_VALIdATION_DEFAULT . $file_name . '.php';
            return $form_rule;
        }
    }

}


if (!function_exists('show_message')) {

    function show_message($key) {
        static $lang;
        if (empty($lang)) {
            $CI = & get_instance();
            $lang = $CI->lang->load('message', null, true);
        }
        return $lang[$key];
    }

}

if (!function_exists("is_pwd")) {

    /**
     * 验证输入的手机号码是否合法
     * @param   string   $user_pwd  需要验证的密码
     * @return bool
     */
    function is_pwd($user_pwd) {
        $length = strlen($user_pwd);
        if ($length === 0) {
            return false;
        } else if ($length < 6) {
            return false;
        } else if ($length > 16) {
            return false;
        } else {
            if (preg_match("/^.*([\x81-\xfe][\x40-\xfe])+.*$/", $user_pwd))
                return false;
        }
        return true;
    }

}

if (!function_exists("is_uname")) {

    /**
     * 验证输入的手机号码是否合法
     * @param   string   $user_pwd  需要验证的密码
     * @return bool
     */
    function is_uname($user) {
        $length = strlen($user);
        if ($length === 0) {
            return false;
        } else if ($length < 6) {
            return false;
        } else if ($length > 16) {
            return false;
        } else {
            if (preg_match("/^.*([\x81-\xfe][\x40-\xfe])+.*$/", $user))
                return false;
        }
        return true;
    }

}

if (!function_exists('show_form_message')) {

    /**
     * 错误提示
     * @param type $error
     */
    function show_form_message($error_code = "add") {
        $CI = & get_instance();

        $post = $CI->input->post();
        if (empty($post)) {
            return;
        }
        $error = validation_errors();
        if (!empty($error)) {
            echo "<div class='form_error_text'>更新失败。。。</div>";
        } else {
            echo "<div class='form_success_text'>更新成功。。。</div>";
        }
    }

}


if (!function_exists('is_data')) {

    /**
     * 时间判断
     * @param type $time
     * @return boolean
     */
    function is_data($time) {
        $result = preg_match("/^[0-9]{4}(\-|\/)[0-9]{1,2}(\\1)[0-9]{1,2}(|\s+[0-9]{1,2}(|:[0-9]{1,2}(|:[0-9]{1,2})))$/", $time);
        if ($result) {
            return TRUE;
        }
        return FALSE;
    }

}

if (!function_exists('is_decimal')) {

    /**
     * 时间判断
     * @param type $num
     * @return boolean
     */
    function is_decimal($num) {
        $result = preg_match("/^[0-9]+\.{0,1}[0-9]+$/", $num);
        if ($result) {
            return TRUE;
        }
        return FALSE;
    }

}