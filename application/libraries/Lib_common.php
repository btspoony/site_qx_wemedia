<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * 后台公共数据处理
 *
 * @author jasper
 */
class Lib_common extends Library {
    
    /**
     * 分页配置
     * @param type $page_config
     * @return type
     */
    public function page_init($page_config = array()) {
        $this->CI->config->load('default_page',true);
        $page_style = $this->CI->config->item('default_page');
        
        
        $config = array_merge($page_style,$page_config);
        
        $this->CI->load->library('pagination');
        $this->pagination->initialize($config);
        $page_link = $this->pagination->create_links(); 
//        p($config);
        $limit = $page_config['this_page'] . ',' . $config['per_page'];
        return array(
            'page_link' => $page_link,
            'limit' => $limit,
            'config' => $config,
            'order_num' => $config['this_page']
        );
    }
    
    /**
     * 添加数据跳转
     * @param type $url
     * @param type $flag
     * @param type $msg
     * @param type $time
     */
    public function redirect_add($url = null, $flag = null, $msg = null, $time = 3) {
        $msg = ($flag) ? "添加成功" : (!empty($msg) ? $msg : "添加失败");
        $params .= "url=" . get_url($url) . "&msg=" . $msg . "&time=" . $time;
        redirect('/admin/power/mate?' . $params);
    }

    /**
     * 修改数据跳转
     * @param type $url
     * @param type $flag
     * @param type $msg
     * @param type $time
     */
    public function redirect_modify($url = null, $flag = null, $msg = null, $time = 3) {
        $msg = ($flag) ? "修改成功" : (!empty($msg) ? $msg : "修改失败");
        $params .= "url=" . get_url($url) . "&msg=" . $msg . "&time=" . $time;
        redirect('/admin/power/mate?' . $params);
    }
    
    /**
     * 礼包码返回数据
     * @param type $code
     * @param type $data
     */
    public function returnCdk($code = null, $data = null) {
        $msg = $this->cdkMsg($code);
        echo json_encode(array('code' => $code,'msg'=> $msg, 'data' => $data));
        exit;
    }
    
    /**
     * 错误信息获取
     * @param type $key
     * @return type
     */
    public function cdkMsg($key = null) {
        $msg = array(
            200 => 'success',
            201 => 'empty ip',
            202 => 'empty type',
            203 => 'not to say efective type',
            204 => 'close to receive cdk',
            205 => 'already receive',
            206 => 'has been brought up',
            207 => 'system error',
            208 => 'empty sign',
            209 => 'sign error',
        );
        return !empty($msg[$key]) ? $msg[$key] : 'error';
    }
}
