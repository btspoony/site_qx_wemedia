<?php

/*
 * shulinfei
 * 15/09/24
 * 用户行为模型
 */

class Admin_action_model extends MY_Model {

    static $_table = 'admin_action';

    function __construct() {
        parent::__construct();
    }
    
    /**
     * 获取用户行为权限
     * @param type $name
     */
    function get_admin_action() {
        $this->db->where(array('isdel'=>NO_DEL));
        $this->db->order_by('orderid','asc');
        return $this->db->get(self::$_table)->result_array();
    }

}
