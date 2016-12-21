<?php
/**
 * 首页
 */
class Main extends DEFAULT_Controller {

    public function index() {
        $this->load->view('/admin/main/index');
    }
    
    public function content() {
        $this->load->view('/admin/main/content');
    }

}
