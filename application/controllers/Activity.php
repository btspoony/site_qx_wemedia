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
        $this->wx_wechat();
    }
    
    /**
     * 微信验证
     */
    public function wx_wechat(){
        $this->load->library('lib_wechat');
        $data = $this->lib_wechat->get_open_id(APPID,APPSECRET);
        p($data,1);
    }
    
    /**
     * 首页
     * @param type $viewname
     */
    function index($viewname = null) {
        $this->load->view('activity/'.$viewname);
    }
    
    
}

