<?php

/*
 * shulinfei
 * 15/09/24
 * 优惠卷类型
 */

class Cdk_type_model extends MY_Model {

    static $_table = 'cdk_type';
 
    const STATUS_USE_YES = 1;    //领取中
    const STATUS_USE_NO = 2;   //已关闭
    
    function _config() {
        return array(
            'status_use_yes' => self::STATUS_USE_YES,
            'status_use_no'   => self::STATUS_USE_NO,
        );
    }
    
    
    /**
     * 获取cdk类型
     * @param type $type_id
     * @return type
     */
    function get_cdk_type($type_id = null) {
      $params = array(
          'type_id' => $type_id,
          'is_del' => NO_DEL
      );
      return $this->db->where($params)->get(self::$_table)->row_array();
    }
    
    /**
     * 修改
     * @param type $data
     */
    function modify_cdk($params = null,$type_id = null) {
        if (empty($type_id)) {
            return false;
        }
        $data = array(
            'type_name' => $params['type_name'],
            'type_desc' => $params['type_desc'],
            'type_status' => $params['type_status'],
        );
        $data = $this->get_update_params($data);
        return $this->db->update(self::$_table, $data, compact('type_id'));
    }
    
    /**
     * 修改
     * @param type $data
     */
    function modify_del($type_id = null) {
        if (empty($type_id)) {
            return false;
        }
        $data = array(
            'is_del' => YES_DEL,
        );
        $data = $this->get_update_params($data);
        return $this->db->update(self::$_table, $data, compact('type_id'));
    }
    
    /**
     * 添加cdk
     * @param type $params
     * @param type $type_id
     */
    function add_cdk_type($params) {
        $data = array(
            'type_name'     => $params['type_name'],
            'type_desc'     => $params['type_desc'],
            'type_status'   => $params['type_status'],
            'is_del'        => NO_DEL
        );
        $data = $this->get_create_params($data);
        return $this->db->insert(self::$_table,$data);
    }
    
    function count_all() {
//        $status = !empty($status) ? ' and ckd_status = ' . $status : null;
        $sql = "select count(*) as count from ".  $this->table_name(self::$_table).'  where is_del='.NO_DEL;
        $data = $this->db->query($sql)->row_array();
        return $data['count'];
    }
    
    /**
     * 获取所有数据
     * @param type $limit
     * @return type
     */
    function get_cdk($limit = null) {
//        $status = !empty($status) ? ' and ckd_status = ' . $status : null;
        $limit = !empty($limit) ? ' LIMIT ' . $limit : null;
        $sql = "select * from ".  $this->table_name(self::$_table).' where is_del= '.NO_DEL.' order by create_time desc '.$limit;
        return $this->db->query($sql)->result_array();
    }

    /**
     * 获取一条数据
     * @param type $type_id
     * @return type
     */
    function get_code_type_by_id($type_id = null) {
        if (empty($type_id)) {
            return false;
        }
        $params = array(
            'is_del' => NO_DEL,
            'type_id' => $type_id,
        );
        return $this->db->where($params)->get(self::$_table)->row_array();
    }
  

}
