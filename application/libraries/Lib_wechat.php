<?php

/**
 * JS SDK 操作
 * @author shulinfei
 * @time 2016/05/25
 */
class Lib_wechat extends Library {

    /**
     * 获取授权码 snsapi_base
     * @author winter
     */
    public function get_oauth_code($appid) {
        if (!isset($_GET['code'])) {
            $self_url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
            $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $appid . '&redirect_uri=' . urlencode($self_url) . '&response_type=code&scope=snsapi_base#wechat_redirect';
            Header('Location:' . $url);
            exit;
        }
    }

    /**
     * 获取微信code snsapi_userinfo
     * @author winter
     */
    public function get_auth_code_userinfo($appid) {
//        if (isset($_GET['code'])){
        $self_url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $appid . '&redirect_uri=' . urlencode($self_url) . '&response_type=code&scope=snsapi_userinfo#wechat_redirect';
        Header('Location:' . $url);
        exit;
//        }
    }

    /**
     * 使用授权码换取公众号的授权信息
     * @author winter
     * @version 2015年6月22日 下午5:05:51
     * @return openid  或   false
     */
    public function get_open_id($appid, $appsecret) {

        if (!isset($_GET['code'])) {
            $this->get_oauth_code($appid);
        }

        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $appid . '&secret=' . $appsecret . '&code=' . $_GET['code'] . '&grant_type=authorization_code';
        $data_return = json_decode(file_get_contents($url), true);
        return $data_return;
    }

    /**
     * 
     * @param type $appid
     * @param type $data_return
     * @return boolean
     */
//    function get_userinfo($appid, $appsecret) {
//        if (!isset($_GET['code'])) {
//            $this->get_oauth_code($appid);
//        }
//        $usersObj = new \SDK\Lib\Users\Users();
//        
//        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $appid . '&secret=' . $appsecret . '&code=' . $_GET['code'] . '&grant_type=authorization_code';
//        $data_return = json_decode(file_get_contents($url), true);
//       
//        $users = $usersObj->getUserWeixin($data_return['openid']);
//         
//        $userinfo_redirect = session('userinfo_redirect');
//        if ($userinfo_redirect == true) {
//            //第二次接口
//            $url = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $data_return['access_token'] . '&openid=' . $data_return['openid'] . '&lang=zh_CN';
//            $data_return = json_decode(file_get_contents($url), true);
//            if (empty($users)) {
//	   
//                $usersObj->registerUser($data_return, $data_return['openid']);
//            }
//            session('openid',$data_return['openid']);
//            session('userinfo_redirect',false);
//            return;
//        } else {
//            if (!empty($users) && !empty($users['users_mobile'])) {
//                session('users', $users);
//                session('openid',$data_return['openid']);
//                $usersObj->updateLoginTime($users['users_mobile'], $data_return['openid']);
//                return ;
//            } else {
//                session('userinfo_redirect',true);
//            }
//            $this->get_auth_code_userinfo($appid);
//        }  
//    }

    /**
     * 使用授权码换取公众号的授权信息
     * @author winter
     * @version 2015年6月22日 下午5:05:51
     * @return openid  或   false
     */
    public function get_auth_userinfo($appid, $appsecret) {

        if (!isset($_GET['code'])) {
            $this->get_auth_code_userinfo($appid);
        }


        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $appid . '&secret=' . $appsecret . '&code=' . $_GET['code'] . '&grant_type=authorization_code';
        $data_return = json_decode(file_get_contents($url), true);

        if ($data_return['openid']) {
            $data_return['access_token'];
            $url = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $data_return['access_token'] . '&openid=' . $data_return['openid'] . '&lang=zh_CN';
            $data_return = json_decode(file_get_contents($url), true);
            return $data_return;
        } else {
            return false;
        }
    }

}
