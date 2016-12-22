<?php

/*
 * api/cdk/getcdkcode
 * 优惠卷接口(王拓自媒体)
 */

class Cdk extends TOP_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('cdk_model');
    }

    /**
     * 获取优惠卷卷号
     * ip
     */
    public function getcdkcode() {
        $data = $this->input->post();

        $this->load->library('lib_common');
        //ip为空
//        if (!isset($data['ip'])) {
//            $this->lib_common->returnCdk(201);
//        }
        $openid = $this->session->userdata('openid');
        $data['openid'] = $openid;
        //openid为空
        if (empty($data['openid'])) {
            $this->lib_common->returnCdk(208);
        }
        //type 为空
        if (!isset($data['type'])) {
            $this->lib_common->returnCdk(202);
        }
        $type_config = $this->cdk_type_model->_config();
        //不是有效的type类型
        $cdkType = $this->cdk_type_model->get_cdk_type($data['type']);
        if (empty($cdkType)) {
            $this->lib_common->returnCdk(203);
        }
        //该礼包已经关闭领取
        if ($cdkType['type_status'] == $type_config['status_use_no']) {
            $this->lib_common->returnCdk(204);
        }
        $this->load->model('cdk_model');
        //活动已经领取过了
        $cdk = $this->cdk_model->get_cdk_by_openid($data['openid'], $data['type']);
        if (!empty($cdk)) {
            $this->lib_common->returnCdk(205);
        }
        //礼包卷已领完
        $cdk_data = $this->cdk_model->get_cdk_by_type($data['type']);
        if (empty($cdk_data)) {
            $this->lib_common->returnCdk(206);
        }
        //修改领取状态, 领取卷码
        $row = $this->cdk_model->modify_cdk($data['openid'], $cdk_data['cdk_id']);
        if (empty($row)) {
            $this->lib_common->returnCdk(207);
        }
        $this->lib_common->returnCdk(200, array('cdk_code' => $cdk_data['cdk_code']));
    }
    
    /**
     * 获取openid
     */
    function getOpenid() {
        $openid = $this->session->userdata('openid');
        if (!empty($openid)) {
            $this->lib_common->returnCdk(200, array('openid' => $openid));
        }
        $this->lib_common->returnCdk(208);
    }
    
    /**
     * 判断礼包卷时候已经领取完
     */
    function checkNoCdk() {
        $data = $this->input->post();
        //礼包卷已领完
        $cdk_data = $this->cdk_model->get_cdk_by_type($data['type']);
        if (empty($cdk_data)) {
            $this->lib_common->returnCdk(206);
        } 
        $this->lib_common->returnCdk(200);
    }
}
