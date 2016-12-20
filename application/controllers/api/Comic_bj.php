<?php

/* 
 * /index.php/api/comic_bj/getcdkcode
 * 优惠卷接口(北京)
 */
class Comic_bj extends DEFAULT_Controller {
    
    public function __construct() {
        parent::__construct(false);
    }
    
    /**
     * 获取优惠卷卷号
     * ip
     */
    public function getcdkcode() {
        $data = $this->input->post();
       
        $this->load->library('lib_common');

        //ip为空
        if (!isset($data['ip'])) {
            $this->lib_common->returnCdk(201);
        }
        
        //type 为空
        if (!isset($data['type'])) {
            $this->lib_common->returnCdk(202);
        }
        
        //加密数据
        if(!isset($data['sign'])) {
            $this->lib_common->returnCdk(208);
        }
        
        //加密方式不匹配
        if ($data['sign'] != md5($data['ip'].$data['type'])) {
            $this->lib_common->returnCdk(209);
        }
        
        $this->load->model('cdk_type_model');
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
        //ip地址当天已经领取过了
        $cdk = $this->cdk_model->get_cdk_by_ip($data['ip'],$data['type']);
        if (!empty($cdk) && date('Ymd', strtotime($cdk['cdk_receive_time'])) == date('Ymd')) {
            $this->lib_common->returnCdk(205);
        }
        
        //礼包卷已领完
        $cdk_data = $this->cdk_model->get_cdk_by_type($data['type']);
        if (empty($cdk_data)) {
            $this->lib_common->returnCdk(206);
        }
        
        //修改领取状态, 领取卷码
        $row = $this->cdk_model->modify_cdk($data['ip'], $cdk_data['cdk_id']);
        if (empty($row)) {
            $this->lib_common->returnCdk(207);
        }
        $this->lib_common->returnCdk(200,array('cdk_code'=>$cdk_data['cdk_code']));
    }
}

