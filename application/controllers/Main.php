<?php
/**
 * 首页
 */
class Main extends TOP_Controller{
    function __construct() {
        parent::__construct();
    }
    
    function index() {
        $this->load->view('h5editor/index');
    }
}
