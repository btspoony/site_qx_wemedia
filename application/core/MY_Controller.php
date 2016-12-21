<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class TOP_Controller extends CI_Controller{
    
    public function __construct() {
       
        parent::__construct();
        //指向mobile皮肤
        $this->load->home_themes_mobile_on();
    }
    
    public function json_error($message = '', $code = 1) {
        $ret_data = array(
            'code' => $code,
            'msg' => $message
        );
        echo json_encode($ret_data);
        exit;
    }

    public function json_success($data = null, $message = '', $code = 0) {
        $ret_data = array(
            'code' => $code,
            'data' => $data,
            'msg' => $message
        );
        echo json_encode($ret_data);
        exit;
    }

    public function ajax_json($flag = true, $data = null, $msg = null) {
        if ($flag) {
            $this->json_success($data, $msg, 0);
        }
        $this->json_error($msg, 1);
    }
}

class DEFAULT_Controller extends CI_Controller {

    public $admin_id = null;
    public $admin_info = null;
    
    //用户登陆权限开放
    public $allow = array();
    public $controller_name = null;
    public $action_name = null;

    public function __construct($vali_login = true) {
        parent::__construct();
        //用户登陆权限判断
        if ($vali_login) {
            $this->set_auth();
        }
        //用户行为判断
        if (!empty($this->admin_id)) {
            $this->action_auth();
        }
    }

    /**
     * 用户行为权限
     */
    public function action_auth() {
        $powers = $this->arrange_powers();
        $action_power = explode(',', $this->admin_info['powers']);
        
        //查找当前控制器有没有做权限控制
        $actionid = 0;
        $nav = array();
//        p($powers);
        foreach ($powers as $v) {
            if (empty($v['child'])) {
                continue;
            }
            foreach ($v['child'] as $v1) {
                if ($v['name'] == $this->controller_name && $v1['name'] == $this->action_name) {
                    $actionid = $v1['actionid'];
                }
                if ($v1['level'] > 1) {
                    continue;
                }
                if (in_array($v1['actionid'], $action_power) || empty($this->admin_info['powers'])) {
                    if (!isset($nav[$v['name']])) {
                        $nav[$v['name']] = array(
                            'name' => $v['name'],   
                            'description' => $v['description']
                        );
                    }
                    $nav[$v['name']]['child'][] = $v1;
                }
            }
        }
//        p($nav);
        //导航
        $this->load->vars(compact('powers', 'nav'));
        
        //没有做权限控制 || 最大权限 || 有访问权限
        if ($actionid == 0  || empty($this->admin_info['powers']) || in_array($actionid, $action_power)) {
            return;
        }
        redirect('/admin/power/authority');
    }
    
    /**
     * 整理权限数据
     * @return type
     */
    public function arrange_powers() {
        $this->load->model('admin_action_model');
        $actions = $this->admin_action_model->get_admin_action();
        $powers = array();
        foreach ($actions as $v) {
            //一级导航
            if ($v['parentid'] == 0) {
                $powers[$v['actionid']] = $v;
            } else {
                $powers[$v['parentid']]['child'][] = $v;
            }
        }
        return $powers;
    }
    
    /**
     * 登陆权限
     * @return type
     */
    public function set_auth() {
        $data = $this->session->userdata();
        $this->admin_info = $this->session->userdata();
        $this->admin_id = $this->session->userdata('admin_id');
        $url = $this->uri->rsegments;
        $this->controller_name = $url[1];
        $this->action_name = $url[2];

        //设置权限
        if (in_array($this->action_name, $this->allow)) {
            return;
        }
        if (!empty($this->admin_id)) {
            $this->load->vars(array('admin_info' => $this->admin_info));
            //登陆
            if ($this->controller_name == 'power' && $this->action_name == 'login') {
                redirect('/admin/main/index');
            } else {
                return;
            }
        }
        if ($this->controller_name == 'power') {
            return;
        }
        redirect('/admin/power/login');
    }

    public function json_error($message = '', $code = 1) {
        $ret_data = array(
            'code' => $code,
            'msg' => $message
        );
        echo json_encode($ret_data);
        exit;
    }

    public function json_success($data = null, $message = '', $code = 0) {
        $ret_data = array(
            'code' => $code,
            'data' => $data,
            'msg' => $message
        );
        echo json_encode($ret_data);
        exit;
    }

    public function ajax_json($flag = true, $data = null, $msg = null) {
        if ($flag) {
            $this->json_success($data, $msg, 0);
        }
        $this->json_error($msg, 1);
    }
    
    public function err404() {
        redirect('/admin/power/err404');
    }

}
