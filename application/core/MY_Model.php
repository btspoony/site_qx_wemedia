<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MY_Model extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    
    /**
     * 获取更新数据的用户信息
     * @param type $params
     * @return type
     */
    function get_update_params($params) {
        $arr = array(
            'update_time' => date('Y-m-d H:i:s'),
        );
        $admin_id = $this->session->userdata('admin_id');
        if (!empty($admin_id)) {
            $arr = array_merge($arr,array('update_id' => $admin_id));
        }
        return array_merge($arr, $params);
    }
    
    /**
     * 获取修改数据的用户信息
     * @param type $params
     * @return type
     */
    function get_create_params($params = array()) {

        $arr = array(
            'create_time' => date('Y-m-d H:i:s'),
        );
        $admin_id = $this->session->userdata('admin_id');
        if (!empty($admin_id)) {
            $arr = array_merge($arr,array('create_id' => $admin_id));
        }
        return array_merge($arr, $params);
    }
    
    /**
     * 获取表名称
     * @param type $table_name
     * @return type
     */
    function table_name($table_name) {
        if (empty($table_name)) {
            return;
        }
        return $this->db->dbprefix.$table_name;
    }

}
