<?php

/*
 * shulinfei
 * 15/09/24
 * 后台管理表
 */

class Admin_model extends MY_Model {

    static $_table = 'admin';
    
    const STATUS_NORMAL = 0;    //正常
    const STATUS_QUIT = 1;      //离职

    function __construct() {
        parent::__construct();
    }
    
    function _config() {
        return array(
            'status_normal' => self::STATUS_NORMAL,
            'status_quit'   => self::STATUS_QUIT,
        );
    }

    function count_all() {
        $sql = "select count(*) as count from ".  $this->table_name(self::$_table)." where isdel=".NO_DEL.' and powers is not null';
        $data = $this->db->query($sql)->row_array();
        return $data['count'];
    }
    
    /**
     * 获取所有数据
     * @param type $limit
     * @return type
     */
    function get_admin($limit = null) {
        $limit = !empty($limit) ? ' LIMIT ' . $limit : null;
        
        $sql = "select * from ".  $this->table_name(self::$_table)." where isdel=".NO_DEL.' and powers is not null order by create_time desc '.$limit;
        $params = array(
            'isdel' => NO_DEL,
            'powers' => "<> ''",
        );
        return $this->db->query($sql)->result_array();
    }

    /**
     * 修改密码
     * @param type $pwd
     * @param type $admin_id
     * @return type
     */
    function modify_pwd($pwd = null, $admin_id = null) {
        $params = array(
            'password' => $pwd,
        );
//        var_dump($admin_id);
//        p($params,1);
        return $this->db->update(self::$_table, $params, compact('admin_id'));
    }
    
    /**
     * 删除
     * @param type $admin_id
     * @return type
     */
    function modify_del($admin_id = null){
        $params = array(
            'isdel' => YES_DEL,
        );
        $params = $this->get_update_params($params);
        return $this->db->update(self::$_table, $params, compact('admin_id'));
    }
    
    /**
     * 修改
     * @param type $data
     */
    function modify_admin($data = null,$admin_id = null) {
        $params = array(
            'username' => $data['username'],
            'powers' => implode(',', $data['powers']),
        );
        return $this->db->update(self::$_table, $params, compact('admin_id'));
    }

    /**
     * 添加用户
     * @param type $params
     */
    function add_admin($params) {

        $arr = array(
            'username' => $params['username'],
            'password' => $params['password'],
            'powers' => implode(',', $params['powers'])
        );
        $arr = $this->get_create_params($arr);
        return $this->db->insert(self::$_table, $arr);
    }

    /**
     * 根据用户,获取用户详情
     * @param type $name
     */
    function get_result_by_name($username) {
        $params = array(
            'username' => $username,
        );
        return $this->db->where($params)->get(self::$_table)->row_array();
    }
    
    /**
     * 根据id,获取用户详情
     * @param type $name
     */
    function get_result_by_id($admin_id = null) {
        $params = array(
            'admin_id' => $admin_id,
            'isdel' => NO_DEL
        );
        return $this->db->where($params)->get(self::$_table)->row_array();
    }

    /**
     * 修改登录时间
     */
    function update_last_login_time($username) {

        $this->db->where(compact($username));
        $params = array(
            'last_login' => date('Y-m-d H:i:s'),
            'last_ip' => real_ip()
        );
        return $this->db->update(self::$_table, $params);
    }

}
