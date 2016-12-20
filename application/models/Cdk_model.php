<?php

/*
 * shulinfei
 * 15/09/24
 * 后台管理表
 */

class Cdk_model extends MY_Model {

    static $_table = 'cdk';
    
    const STATUS_USE_NO = 1;    //未领取
    const STATUS_USE_YES = 2;   //已领取

    function __construct() {
        parent::__construct();
    }
    
    function _config() {
        return array(
            'status_use_no' => self::STATUS_USE_NO,
            'status_use_yes'   => self::STATUS_USE_YES,
        );
    }
    
    /**
     * 获取优惠卷卷码
     * @param type $ip
     * @return type
     */
    function get_cdk_by_ip($ip = null,$type_id = null) {
        $params = array(
            'cdk_receive_ip' => $ip,
            'type_id' => $type_id
        );
        $this->db->order_by('cdk_receive_time','desc');
        return $this->db->where($params)->get(self::$_table)->row_array();
    }

    function count_all($type_id = null) {
        $type_id = !empty($type_id) ? ' and type_id = ' . $type_id : null;
        $sql = "select count(*) as count from ".  $this->table_name(self::$_table)." where 1=1 ".$type_id;
        $data = $this->db->query($sql)->row_array();
        return $data['count'];
    }
    
    /**
     * 获取所有数据
     * @param type $limit
     * @return type
     */
    function get_cdk($limit = null, $type_id = null) {
        $type_id = !empty($type_id) ? ' and type_id = ' . $type_id : null;
        $limit = !empty($limit) ? ' LIMIT ' . $limit : null;
        $sql = "select * from ".  $this->table_name(self::$_table)." where 1 =1 ".$type_id.' order by create_time desc '.$limit;
        return $this->db->query($sql)->result_array();
    }
    
    /**
     * 获取cdk code
     * @param type $type_id
     * @return type
     */
    function get_cdk_by_type($type_id = null) {
        $params = array(
            'type_id' => $type_id,
            'cdk_receive_ip' => '',
            'cdk_status' => self::STATUS_USE_NO
        );
        return $this->db->where($params)->get(self::$_table)->row_array();
    }
    
    
    /**
     * 修改
     * @param type $data
     */
    function modify_cdk($ip = null,$cdk_id = null) {
        $params = array(
            'cdk_receive_ip' => $ip,
            'cdk_receive_time' => date('Y-m-d H:i:s'),
            'cdk_status' => self::STATUS_USE_YES,
        );
        return $this->db->update(self::$_table, $params, compact('cdk_id'));
    }
    
    /**
     * 添加code码
     * @param type $params
     */
    function add_cdk($params,$type_id) {
        $ret_data = array('success' => 0, 'exist' => 0);
        $in_code = "'" . implode("','", $params) . "'";
        $sql = "SELECT * FROM ".$this->table_name(self::$_table).' WHERE cdk_code in('.$in_code.') and type_id = '.$type_id;
        $already_exist = $this->db->query($sql)->result_array();
        //获取重复的code
        $exist_data = array();
        if (!empty($already_exist)) {
            foreach($already_exist as $v) {
                $exist_data[$v['cdk_code']] = $v['cdk_code'];
            }
            $ret_data['exist'] = count($exist_data);
        }
        
        $data = array();
        foreach ($params as $v) {
            //去除重复code
            if (isset($exist_data[$v])) {
                continue;
            }
            $data[] = array(
                'cdk_code' => $v,
                'cdk_status' => self::STATUS_USE_NO,
                'type_id' => $type_id,
                'create_time' => date('Y-m-d H:i:s'),
            );
        }
        if (!empty($data)) {
            $this->db->insert_batch(self::$_table, $data);
            $ret_data['success'] = count($data);
        }
        return $ret_data;
    }

}
