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
        $this->wx_wechat('controller=activity&action=index');
    }
    
    /**
     * 首页
     * @param type $viewname
     */
    function index() {
        $viewname = !empty($_GET['view']) ? $_GET['view'] : 'default';
        die('ok');
        $this->load->view('activity/'.$viewname);
    }
    
    
    
    
}

