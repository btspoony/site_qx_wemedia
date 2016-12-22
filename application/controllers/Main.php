<?php
/**
 * 首页
 */
class Main extends TOP_Controller{
    function __construct() {
        parent::__construct();
    }

    function h5editor(){
        $type = 1;
        $this->load->view('h5editor/index', compact('type'));
    }
    
    /**
     * 路由判断跳转
     */
    function routes() {
        $controller = $_GET['controller'];
        $action = $_GET['action'];
        $view = $_GET['view'];
        $code = $_GET['code'];
        redirect(base_url($controller.'/'.$action.'?view='.$view.'&code='.$code));
    }
}
