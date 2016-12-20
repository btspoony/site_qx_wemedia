<?php

/**
 * 个人中心管理
 */
class Admin extends DEFAULT_Controller {
    
    
    
    public function __construct() {
        parent::__construct(true);
        $this->load->model('admin_model');
    }
    
    

    /**
     * 用户信息
     */
    public function userinfo() {
        
    }
    
    /**
     * 管理员编辑
     */
    public function edit($admin_id = null) {
        $data = $this->input->post();
        if (!empty($data)) {
            $row = $this->admin_model->modify_admin($data,$admin_id);
            $this->lib_common->redirect_modify('/admin/see', !empty($row));
        }
        $admin_row = $this->admin_model->get_result_by_id($admin_id);
        if (empty($admin_row)) {
            $this->err404();
        }
        $_POST = $admin_row;
        $this->load->view('/admin/edit');
    }
    /**
     * 管理员删除
     * @param type $admin_id
     */
    public function delete($admin_id = null){
        $row = $this->admin_model->modify_del($admin_id);
        $this->lib_common->redirect_modify('/admin/see',!empty($row));
    }

    /**
     * 管理员查看
     */
    public function see($page = null) {
        $this->load->model('admin_model');
        $page_config = array(
            'base_url' => get_url('/admin/see'),
            'total_rows' => $this->admin_model->count_all(),
            'this_page' => !empty($page) ? $page : 0,            
        );
        $page = $this->lib_common->page_init($page_config);
        $data = $this->admin_model->get_admin($page['limit']);
        $model_config = $this->admin_model->_config();
        $this->load->view('/admin/see', compact('data', 'page','model_config'));
    }

    /**
     * 管理员添加
     */
    public function add() {
        $data = $this->input->post();
        if (!empty($data)) {
            $data['password'] = compile_password(array('password' => trim($data['password'])));
            $row = $this->admin_model->add_admin($data);
            $this->lib_common->redirect_modify('/admin/add', !empty($row));
        }
        $this->load->view('/admin/add');
    }

    /**
     * 修改密码
     */
    public function modify_pwd() {
        $data = $this->input->post();
        if (!empty($data)) {
            $flag = $this->admin_model->modify_pwd(compile_password(array('password' => trim($data['new_password']))), $this->admin_id);
            if ($flag) {
                $this->session->sess_destroy();
            }
            $this->lib_common->redirect_modify('/admin/modify_pwd', $flag);
        }
        $this->load->view('/admin/modify_pwd');
    }

    /**
     * 密码验证
     */
    public function check_pwd() {
        $pwd = $this->admin_info['password'];
        $data = $this->input->post();
        $this->ajax_json(!empty($data['check_password']) && compile_password(array('password' => trim($data['old_password']))) == $pwd);
    }

    /**
     * 用户名验证
     */
    public function check_username() {
        $data = $this->input->post();
        $admin_row = $this->admin_model->get_result_by_name(trim($data['username']));
        $this->ajax_json(empty($admin_row));
    }

}
