<?php

/**
 * 优惠卷
 */
class Cdk extends DEFAULT_Controller {

    public function __construct() {
        parent::__construct(true);
        $this->load->model(array('cdk_model', 'cdk_type_model'));
    }

    /**
     * 优惠卷
     * @param type $page
     */
    public function index($page = null) {
        $page_config = array(
            'base_url' => base_url('/admin/cdk/index'),
            'total_rows' => $this->cdk_type_model->count_all(),
            'this_page' => !empty($page) ? $page : 0,
        );
        $page = $this->lib_common->page_init($page_config);
        $data = $this->cdk_type_model->get_cdk($page['limit']);

        //获取优惠卷数量
        $typeids = array();
        foreach ($data as $v) {
            $typeids[] = $v['type_id'];
        }
        $data = $this->cdk_model->get_cdk_data_count($data);

        $model_config = $this->cdk_type_model->_config();
        $this->load->view('/admin/cdk/index', compact('data', 'page', 'model_config'));
    }

    /**
        * 编辑
     */
    public function edit($type_id = null) {
        $data = $this->input->post();
        if (!empty($data)) {
            $data['type_url'] = 'http://' . $_SERVER['HTTP_HOST'] . '/activity/index?view=' . trim($data['type_code']);
            $row = $this->cdk_type_model->modify_cdk($data, $type_id);
            $this->lib_common->redirect_modify('/admin/cdk/index', !empty($row));
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
        $this->load->view('/admin/cdk/edit', compact('status'));
    }

    /**
     * cdk删除
     * @param type $type_id
     */
    public function delete($type_id = null) {
        $row = $this->cdk_type_model->modify_del($type_id);
        $this->lib_common->redirect_modify('/admin/cdk/index', !empty($row));
    }

    /**
     * cdk查看
     */
    public function see_code($type_id = null, $page = null) {
        $this->load->model('cdk_model');
        $page_config = array(
            'base_url' => base_url('/admin/cdk/see_code/' . $type_id),
            'total_rows' => $this->cdk_model->count_all($type_id),
            'this_page' => !empty($page) ? $page : 0,
        );
        $page = $this->lib_common->page_init($page_config);
        $data = $this->cdk_model->get_cdk($page['limit'], $type_id);
        $model_config = $this->cdk_model->_config();
        $this->load->view('/admin/cdk/see_code', compact('data', 'page', 'model_config'));
    }

    /**
     * 添加cdk卷码
     */
    public function add_code($type_id = null) {
        $data = $this->input->post();
        if (!empty($data)) {
            if (!file_exists(FCPATH . $data['cdk_path'])) {
                $this->lib_common->redirect_modify('/admin/cdk/add_code/' . $type_id, false, '上传的文件不存在, 请查看是否上传成功或者联系管理员');
            }
            $this->load->library('excel/lib_excel');
            $cdk_data = $this->lib_excel->getExeclData(FCPATH . $data['cdk_path'], 1);
            if (!empty($cdk_data)) {
                $cdk_codes = array();
                foreach ($cdk_data as $v) {
                    if (!empty($v[0])) {
                        $cdk_codes[] = $v[0];
                    }
                }
                $ret_data = $this->cdk_model->add_cdk($cdk_codes, $type_id);
                $this->lib_common->redirect_modify('/admin/cdk/see_code/' . $type_id, false, '数据导入成功:' . $ret_data['success'] . '; 卷号已存在:' . $ret_data['exist']);
            }
        }
        $this->load->view('/admin/cdk/add_code');
    }

    /**
     * cdk添加
     */
    public function add() {
        $data = $this->input->post();
        if (!empty($data)) {
            $data['type_url'] = 'http://' . $_SERVER['HTTP_HOST'] . '/activity/index?view=' . trim($data['type_code']);
            $type_id = $this->cdk_type_model->add_cdk_type($data);
            $this->lib_common->redirect_modify('/admin/cdk/index', !empty($type_id));
        }
        $cdk_status = $this->cdk_type_model->_config();
        $status = array(
            array('id' => $cdk_status['status_use_yes'], 'text' => '领取中'),
            array('id' => $cdk_status['status_use_no'], 'text' => '已关闭'),
        );
        $this->load->view('/admin/cdk/add', compact('status', 'cdk_status'));
    }

    public function check_type_code() {
        $data = $this->input->post();
        $row = $this->cdk_type_model->get_result_by_type_code(trim($data['type_code']));
        $this->ajax_json(empty($row));
    }

}
