<?php
/**
 * 首页
 */
class Main extends DEFAULT_Controller {

    public function index() {
        $this->load->view('/main/index');
    }
    
    public function content() {
        $this->load->view('/main/content');
    }

}
