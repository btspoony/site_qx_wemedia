<?php

/**
 * 优惠卷
 */
class Cdk extends DEFAULT_Controller {

    public function __construct() {
        parent::__construct(true);
        $this->load->model(array('cdk_model','cdk_type_model'));
    }

    /**
     * 优惠卷
     * @param type $page
     */
    public function index($page = null) {
        $page_config = array(
            'base_url' => get_url('/cdk/index'),
            'total_rows' => $this->cdk_type_model->count_all(),
            'this_page' => !empty($page) ? $page : 0,
        );
        $page = $this->lib_common->page_init($page_config);
        $data = $this->cdk_type_model->get_cdk($page['limit']);
        $model_config = $this->cdk_type_model->_config();
        $this->load->view('/cdk/index', compact('data', 'page', 'model_config'));
    }

    /**
     * 编辑
     */
    public function edit($type_id = null) {
        $data = $this->input->post();
        if (!empty($data)) {
            $row = $this->cdk_type_model->modify_cdk($data, $type_id);
            $this->lib_common->redirect_modify('/cdk/index', !empty($row));
        }
        $cdk_row = $this->cdk_type_model->get_code_type_by_id($type_id);
        if (empty($cdk_row)) {
            $this->err404();
        }
        $cdk_status = $this->cdk_type_model->_config();
        $status = array(
            array('id' => $cdk_status['status_use_yes'], 'text' => '领取中'),
            array('id' => $cdk_status['status_use_no'], 'text' => '已关闭'),
        );
        $_POST = $cdk_row;
        $this->load->view('/cdk/edit',  compact('status'));
    }

    /**
     * cdk删除
     * @param type $type_id
     */
    public function delete($type_id = null) {
        $row = $this->cdk_type_model->modify_del($type_id);
        $this->lib_common->redirect_modify('/cdk/index', !empty($row));
    }

    /**
     * cdk查看
     */
    public function see_code($type_id = null, $page = null) {
        $this->load->model('cdk_model');
        $page_config = array(
            'base_url' => get_url('/cdk/see_code/'.$type_id),
            'total_rows' => $this->cdk_model->count_all($type_id),
            'this_page' => !empty($page) ? $page : 0,
        );
        $page = $this->lib_common->page_init($page_config);
        $data = $this->cdk_model->get_cdk($page['limit'],$type_id);
        $model_config = $this->cdk_model->_config();
        $this->load->view('/cdk/see_code', compact('data', 'page', 'model_config'));
    }
    
    /**
     * 添加cdk卷码
     */
    public function add_code($type_id = null) {
        $data = $this->input->post();
        if (!empty($data)) {
            if (!file_exists(FCPATH . $data['cdk_path'])) {
                $this->lib_common->redirect_modify('/cdk/add_code/' . $type_id, false, '上传的文件不存在, 请查看是否上传成功或者联系管理员');
            }
            $this->load->library('excel/lib_excel');
            $cdk_data = $this->lib_excel->getExeclData(FCPATH . $data['cdk_path']);
            if (!empty($cdk_data)) {
                $cdk_codes = array();
                foreach ($cdk_data as $v) {
                    $cdk_codes[] = $v[0];
                }
                $ret_data = $this->cdk_model->add_cdk($cdk_codes, $type_id);
                $this->lib_common->redirect_modify('/cdk/see_code/' . $type_id, false, '数据导入成功:' . $ret_data['success'] . '; 卷号已存在:' . $ret_data['exist']);
            }
        }
        $this->load->view('cdk/add_code');
    }

    /**
     * cdk添加
     */
    public function add() {
        $data = $this->input->post();
        if (!empty($data)) {
            $type_id = $this->cdk_type_model->add_cdk_type($data);
            $this->lib_common->redirect_modify('/cdk/index', !empty($type_id));
        }
        $cdk_status = $this->cdk_type_model->_config();
        $status = array(
            array('id' => $cdk_status['status_use_yes'], 'text' => '领取中'),
            array('id' => $cdk_status['status_use_no'], 'text' => '已关闭'),
        );
        $this->load->view('/cdk/add', compact('status', 'cdk_status'));
    }

}
