<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Activity extends TOP_Controller{
    
    function __construct() {
        parent::__construct();
    }
    
    /**
     * 首页
     * @param type $viewname
     */
    function index($viewname = null) {
        $this->load->view('activity/'.$viewname);
    }
    
    
}

