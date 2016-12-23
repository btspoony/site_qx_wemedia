<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Activity extends TOP_Controller{
    
    function __construct() {
        parent::__construct();
                
        //微信验证
        $this->wx_wechat();
        $this->load->model('cdk_type_model');
    }
    
    /**
     * 首页
     * @param type $viewname
     */
    function index() {
        $viewname = !empty($_GET['view']) ? $_GET['view'] : 'default';
        $data = $this->cdk_type_model->get_result_by_type_code($viewname);
        $type = !empty($data) ? $data['type_id'] : null;
        $type_name = !empty($data) ? $data['type_name'] : null;
        $page_data = !empty($data) ? $data['type_page'] : null;
        $this->load->view('activity/index', compact('type','type_name','page_data'));
    }
    
    /**
     * 查看活动领取的code
     */
    function mycodes() {
        $data = $this->cdk_type_model->get_result_by_type_openid($this->openid);
        $this->load->view('activity/mycodes', compact('data'));
    }
    
    
    
    
}

