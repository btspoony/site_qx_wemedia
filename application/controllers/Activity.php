<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Activity extends TOP_Controller{
    
    function __construct() {
        parent::__construct();
        //危险严重
        $this->wx_wechat('&controller=activity&action=index');
    }
    
    /**
     * 首页
     * @param type $viewname
     */
    function index() {
        $viewname = !empty($_GET['view']) ? $_GET['view'] : 'default';
        $this->load->model('cdk_type_model');
        $data = $this->cdk_type_model->get_result_by_type_code($viewname);
        $type = !empty($data) ? $data['type_id'] : null;
        $this->load->view('activity/index', compact('type'));
    }
    
    
    
    
}

