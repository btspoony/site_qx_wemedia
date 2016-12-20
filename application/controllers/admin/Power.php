<?php

/**
 * 用户登陆
 */
class Power extends DEFAULT_Controller {

    function __construct() {
        $this->allow = array('mate', 'logout');
        parent::__construct(true);
    }

    public function login() {
        $post = $this->input->post();
        $error_message = null;
        if (!empty($post)) {
            //用户登录判断    
            if (!empty($post['username']) && !empty($post['password'])) {
                $this->load->model('admin_model');
                $admin_row = $this->admin_model->get_result_by_name(trim($post['username']));
                $pwd = compile_password(array('password' => trim($post['password'])));
                if ($admin_row['status'] == 0 && $admin_row['password'] == $pwd) {
//                    unset($admin_row['pwssword']);
                    $this->session->set_userdata($admin_row);
                    //修改登录时间
                    $this->admin_model->update_last_login_time($post['username']);
                    redirect('admin/main/index');
                } else {
                    $error_message = show_message('login');
                }
            }
        }
        $this->load->view('/power/login', compact('error_message'));
    }

    /**
     * 退出
     */
    public function logout() {
        $this->session->sess_destroy(); //注销所有session变量
        redirect('admin/power/login', 'refresh'); //这是退出到登陆页面
    }

    /**
     * 提示页面
     */
    public function mate() {
        //跳转url
        $url = $this->input->get('url');
        //跳转提示信息
        $msg = $this->input->get('msg');
        //跳转时间
        $time = $this->input->get('time');
        $this->load->view('/power/mate', compact('url', 'msg', 'time'));
    }
    
    /**
     * 用户权限
     */
    public function authority() {
        $this->load->view('/power/authority');
    }
    
    /**
     * 404
     */
    public function err404() {
        $this->load->view('/power/err404');
    }
}
