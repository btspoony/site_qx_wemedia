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
            'status_use_yes' => self::STATUS_USE_YES,
        );
    }

    /**
     * 剩余cdk数量
     * @param type $data
     * @return boolean
     */
    function get_cdk_data_count($data = null) {
        if (empty($data) && !is_array($data)) {
            return $data;
        }
        $ret_data = array();
        $typeids = array();
        foreach ($data as $v) {
            $typeids[] = $v['type_id'];
            $ret_data[$v['type_id']] = $v;
        }

        //类型配置
        $sql = "select count(*) as count, type_id from " . $this->table_name(self::$_table) . " where type_id in('" . implode("','", $typeids) . "') group by type_id";
        $count_data = $this->db->query($sql)->result_array();
        if (empty($count_data)) {
            return $data;
        }

        $sql = "select count(*) as count, type_id from " . $this->table_name(self::$_table) . " where cdk_status=" . self::STATUS_USE_YES . " and type_id in('" . implode("','", $typeids) . "') group by type_id";
        $use_data = $this->db->query($sql)->result_array();

        
        $count_datas = array(); //总数
        $use_datas = array(); //未使用
        foreach ($count_data as $v) {
            $count_datas[$v['type_id']] = $v;
        }
        
        foreach ($use_data as $v) {
            $use_datas[$v['type_id']] = $v;
        }

        foreach ($count_datas as $type_id => $v) {
            //总数
            $ret_data[$v['type_id']]['total'] = $v['count'];
            //已领取
            $ret_data[$v['type_id']]['use_total'] = isset($use_no_data[$v['type_id']]) ? $use_no_data[$v['type_id']]['count'] : 0;
            //未领取
            $ret_data[$v['type_id']]['use_no_total'] = $ret_data[$v['type_id']]['total'] - $ret_data[$v['type_id']]['use_total'];
        }
        return $ret_data;
    }

    /**
     * 获取优惠卷卷码
     * @param type $ip
     * @return type
     */
    function get_cdk_by_openid($openid = null, $type_id = null) {
        $params = array(
            'openid' => $openid,
            'type_id' => $type_id
        );
        return $this->db->where($params)->get(self::$_table)->row_array();
    }

    function count_all($type_id = null) {
        $type_id = !empty($type_id) ? ' and type_id = ' . $type_id : null;
        $sql = "select count(*) as count from " . $this->table_name(self::$_table) . " where 1=1 " . $type_id;
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
        $sql = "select * from " . $this->table_name(self::$_table) . " where 1 =1 " . $type_id . ' order by cdk_receive_time desc ' . $limit;
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
            'openid' => '',
            'cdk_status' => self::STATUS_USE_NO
        );
        return $this->db->where($params)->get(self::$_table)->row_array();
    }
    
    /**
     * 修改
     * @param type $data
     */
    function modify_cdk($openid = null, $cdk_id = null ) {
        $params = array(
            'cdk_receive_ip' => real_ip(),
            'openid' => $openid,
            'cdk_receive_time' => date('Y-m-d H:i:s'),
            'cdk_status' => self::STATUS_USE_YES,
        );
        return $this->db->update(self::$_table, $params, compact('cdk_id'));
    }

    /**
     * 添加code码
     * @param type $params
     */
    function add_cdk($params, $type_id) {
        $ret_data = array('success' => 0, 'exist' => 0);
        $in_code = "'" . implode("','", $params) . "'";
        $sql = "SELECT * FROM " . $this->table_name(self::$_table) . ' WHERE cdk_code in(' . $in_code . ') and type_id = ' . $type_id;
        $already_exist = $this->db->query($sql)->result_array();
        //获取重复的code
        $exist_data = array();
        if (!empty($already_exist)) {
            foreach ($already_exist as $v) {
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
