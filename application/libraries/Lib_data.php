<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * 处理数据保存到数据库
 * @author slf
 * @time 16/9/6
 */
class Lib_data extends Library {

    public function __construct() {
        parent::__construct();
    }

    /**
     * 获取统计
     */
    public function get_handle_data() {
        $this->CI->load->model(array('ship_user_info_mdl', 'serveruser_mdl', 'ship_user_recharge_mdl'));

        $user2000 = $this->ship_user_info_mdl->get_register_user_fee('2000');
        $user2001 = $this->ship_user_info_mdl->get_register_user_fee('2001');
        $data['date'] = date('Y年m月d日', strtotime("-1 days"));
        //新注册用户付费数
        $data['z_register_user_fee_count'] = $user2000['count'] + $user2001['count'];
        //新增用户首日充值
        $data['z_register_user_fee'] = $user2000['rmb'] / TB_RATE_RMB + $user2001['count'] / TB_RATE_RMB;

        /*         * ***数据汇总start***** */
        //总注册用户
        $register_user_count = $this->serveruser_mdl->get_register_user_count();
        $data['z_register_user_2000_count'] = isset($register_user_count[0]) ? $register_user_count[0]['count'] : 0;
        $data['z_register_user_2001_count'] = isset($register_user_count[1]) ? $register_user_count[1]['count'] : 0;
        //总登录角色数
        $ship_user2000 = $this->ship_user_info_mdl->get_ship_user_count('2000');
        $ship_user2001 = $this->ship_user_info_mdl->get_ship_user_count('2001');
        $data['z_ship_user_2000_count'] = $ship_user2000['count'];
        $data['z_ship_user_2001_count'] = $ship_user2001['count'];
        //总付费角色数,总充值,总充值单数
        $ship_user_recharge2000 = $this->ship_user_recharge_mdl->get_ship_user_count('2000');
        $ship_user_recharge2001 = $this->ship_user_recharge_mdl->get_ship_user_count('2001');
        //总付费角色数
        $data['z_recharge_role_count_2000'] = $ship_user_recharge2000['role_count'];
        $data['z_recharge_role_count_2001'] = $ship_user_recharge2001['role_count'];
        //总充值
        $data['z_recharge_tb_2000'] = number_handle($ship_user_recharge2000['rmb']);
        $data['z_recharge_rmb_2000'] = number_handle($ship_user_recharge2000['rmb'] / TB_RATE_RMB,0);
        $data['z_recharge_tb_2001'] = number_handle($ship_user_recharge2001['rmb']);
        $data['z_recharge_rmb_2001'] = number_handle($ship_user_recharge2001['rmb'] / TB_RATE_RMB,0);
        //总充值单数
        $data['z_recharge_count_2000'] = $ship_user_recharge2000['recharge_count'];
        $data['z_recharge_count_2001'] = $ship_user_recharge2001['recharge_count'];
        //总IA
        $data['z_IA_2000'] = !empty($data['z_register_user_2000_count']) ? percent($data['z_ship_user_2000_count'] / $data['z_register_user_2000_count']) : '0.00%';
        $data['z_IA_2001'] = !empty($data['z_register_user_2001_count']) ? percent($data['z_ship_user_2001_count'] / $data['z_register_user_2001_count']) : '0.00%';
        //arppu
        $data['z_arppu_2000'] = !empty($data['z_recharge_role_count_2000']) ? number_handle($data['z_recharge_rmb_2000'] / $data['z_recharge_role_count_2000']) : 0;
        $data['z_arppu_2001'] = !empty($data['z_recharge_role_count_2001']) ? number_handle($data['z_recharge_rmb_2001'] / $data['z_recharge_role_count_2001']) : 0;
        //arpu
        $data['z_arpu_2000'] = !empty($data['z_ship_user_2000_count']) ? number_handle($data['z_recharge_rmb_2000'] / $data['z_ship_user_2000_count']) : 0;
        $data['z_arpu_2001'] = !empty($data['z_ship_user_2001_count']) ? number_handle($data['z_recharge_rmb_2001'] / $data['z_ship_user_2001_count']) : 0;
        //每付费用户充值单数
        $data['z_recharge_now_rate_2000'] = !empty($data['z_recharge_role_count_2000']) ? number_handle($data['z_recharge_count_2000'] / $data['z_recharge_role_count_2000']) : 0;
        $data['z_recharge_now_rate_2001'] = !empty($data['z_recharge_role_count_2001']) ? number_handle($data['z_recharge_count_2001'] / $data['z_recharge_role_count_2001']) : 0;
        //活跃付费比
        $data['z_active_rate_2000'] = !empty($data['z_ship_user_2000_count']) ? percent($data['z_recharge_role_count_2000'] / $data['z_ship_user_2000_count']) : '0.00%';
        $data['z_active_rate_2001'] = !empty($data['z_ship_user_2001_count']) ? percent($data['z_recharge_role_count_2001'] / $data['z_ship_user_2001_count']) : '0.00%';
        /*         * ***数据汇总start***** */

        /*         * ****服务器汇总start** */
        //新增注册数
        $register_now_user_count = $this->serveruser_mdl->get_register_now_user_count();
        $data['register_user_now_2000'] = isset($register_now_user_count[0]) ? $register_now_user_count[0]['count'] : 0;
        $data['register_user_now_2001'] = isset($register_now_user_count[1]) ? $register_now_user_count[1]['count'] : 0;
        //新增角色数
        $register_now_ship_user_2000_count = $this->ship_user_info_mdl->get_register_now_user_count("2000");
        $register_now_ship_user_2001_count = $this->ship_user_info_mdl->get_register_now_user_count("2001");
        $data['register_now_ship_user_2000'] = $register_now_ship_user_2000_count['count'];
        $data['register_now_ship_user_2001'] = $register_now_ship_user_2001_count['count'];
        //IA
        $data['IA_2000'] = !empty($data['register_user_now_2000']) ? percent($data['register_now_ship_user_2000'] / $data['register_user_now_2000']) : "0.00%";
        $data['IA_2001'] = !empty($data['register_now_ship_user_2001']) ? percent($data['register_now_ship_user_2001'] / $data['register_user_now_2001']) : "0.00%";
        //当天登入角色
        $ship_user_login2000 = $this->ship_user_info_mdl->get_login_user_count('2000');
        $ship_user_login2001 = $this->ship_user_info_mdl->get_login_user_count('2001');
        $data['ship_user_login_count_2000'] = $ship_user_login2000['count'];
        $data['ship_user_login_count_2001'] = $ship_user_login2001['count'];
        //当天付费角色数,当天充值,当天充值单数
        $ship_user_now_recharge2000 = $this->ship_user_recharge_mdl->get_ship_user_now_fee_count('2000');
        $ship_user_now_recharge2001 = $this->ship_user_recharge_mdl->get_ship_user_now_fee_count('2001');
        //当天付费角色数
        $data['recharge_role_now_count_2000'] = $ship_user_now_recharge2000['role_count'];
        $data['recharge_role_now_count_2001'] = $ship_user_now_recharge2001['role_count'];
        //当天充值
        $data['recharge_tb_now_2000'] = number_handle($ship_user_now_recharge2000['rmb']);
        $data['recharge_rmb_now_2000'] = number_handle($ship_user_now_recharge2000['rmb'] / TB_RATE_RMB,0);
        $data['recharge_tb_now_2001'] = number_handle($ship_user_now_recharge2001['rmb'], 0);
        $data['recharge_rmb_now_2001'] = number_handle($ship_user_now_recharge2001['rmb'] / TB_RATE_RMB,0);
        //活跃付费比
        $data['active_rate_2000'] = !empty($data['ship_user_login_count_2000']) ? percent($data['recharge_role_now_count_2000'] / $data['ship_user_login_count_2000']) : "0.00%";
        $data['active_rate_2001'] = !empty($data['ship_user_login_count_2001']) ? percent($data['recharge_role_now_count_2001'] / $data['ship_user_login_count_2001']) : "0.00%";
        //arppu
        $data['arppu_2000'] = !empty($data['recharge_role_now_count_2000']) ? number_handle($data['recharge_rmb_now_2000'] / $data['recharge_role_now_count_2000']) : 0;
        $data['arppu_2001'] = !empty($data['recharge_role_now_count_2001']) ? number_handle($data['recharge_rmb_now_2001'] / $data['recharge_role_now_count_2001']) : 0;
        //arpu
        $data['arpu_2000'] = !empty($data['ship_user_login_count_2000']) ? number_handle($data['recharge_rmb_now_2000'] / $data['ship_user_login_count_2000']) : 0;
        $data['arpu_2001'] = !empty($data['ship_user_login_count_2001']) ? number_handle($data['recharge_rmb_now_2001'] / $data['ship_user_login_count_2001']) : 0;
        //当天充值单数
        $data['recharge_now_count_2000'] = $ship_user_now_recharge2000['recharge_count'];
        $data['recharge_now_count_2001'] = $ship_user_now_recharge2001['recharge_count'];
        //每付费用户充值单数
        $data['recharge_now_rate_2000'] = !empty($data['recharge_role_now_count_2000']) ? number_handle($data['recharge_now_count_2000'] / $data['recharge_role_now_count_2000']) : 0;
        $data['recharge_now_rate_2001'] = !empty($data['recharge_role_now_count_2001']) ? number_handle($data['recharge_now_count_2001'] / $data['recharge_role_now_count_2001']) : 0;
        //新增付费角色数
        $ship_user_new_recharge2000 = $this->ship_user_recharge_mdl->get_ship_user_new_fee_count('2000');
        $ship_user_new_recharge2001 = $this->ship_user_recharge_mdl->get_ship_user_new_fee_count('2001');
        $data['recharge_role_new_count_2000'] = $ship_user_new_recharge2000['count'];
        $data['recharge_role_new_count_2001'] = $ship_user_new_recharge2001['count'];
        //留存
        $data['ship_user_retained_2000'] = $this->get_retained($this->ship_user_info_mdl->get_ship_user_retained('2000'));
        $data['ship_user_retained_2001'] = $this->get_retained($this->ship_user_info_mdl->get_ship_user_retained('2001'));
        /*         * ****服务器汇总end** */
        return $data;
    }

    /**
     * 留存数据整理
     * @param type $data
     * @return type
     */
    public function get_retained($data) {
      
        $ret_data = array();
        if (!empty($data)) {
            foreach ($data as $k => $v) {
                $data[$v['i_time']] = $v['count'];
            }
        } 
        $day1 = isset($data[date('Y-m-d', strtotime('-1 days'))]) ? $data[date('Y-m-d', strtotime('-1 days'))] : '';
        $day2 = isset($data[date('Y-m-d', strtotime('-2 days'))]) ? $data[date('Y-m-d', strtotime('-2 days'))] : '';
        $day4 = isset($data[date('Y-m-d', strtotime('-4 days'))]) ? $data[date('Y-m-d', strtotime('-4 days'))] : '';
        $day8 = isset($data[date('Y-m-d', strtotime('-8 days'))]) ? $data[date('Y-m-d', strtotime('-8 days'))] : '';
        $day16 = isset($data[date('Y-m-d', strtotime('-16 days'))]) ? $data[date('Y-m-d', strtotime('-16 days'))] : '';
        $day31 = isset($data[date('Y-m-d', strtotime('-31 days'))]) ? $data[date('Y-m-d', strtotime('-31 days'))] : '';
        $day46 = isset($data[date('Y-m-d', strtotime('-46 days'))]) ? $data[date('Y-m-d', strtotime('-46 days'))] : '';
        $day61 = isset($data[date('Y-m-d', strtotime('-61 days'))]) ? $data[date('Y-m-d', strtotime('-61 days'))] : '';
        $day76 = isset($data[date('Y-m-d', strtotime('-76 days'))]) ? $data[date('Y-m-d', strtotime('-76 days'))] : '';
        $day91 = isset($data[date('Y-m-d', strtotime('-91 days'))]) ? $data[date('Y-m-d', strtotime('-91 days'))] : '';
        $day121 = isset($data[date('Y-m-d', strtotime('-121 days'))]) ? $data[date('Y-m-d', strtotime('-121 days'))] : '';
        $ret_data = array(
            'number' => array($day1, $day2, $day4, $day8, $day16, $day31, $day46, $day61, $day76, $day91, $day121),
        );
        return $ret_data;
    }

    /**
     * 处理数据
     * @param type $fileName
     */
    public function handle_data($filePath = null, $tableName = null) {
        if (empty($tableName) || !file_exists($filePath)) {
            return false;
        }
        $flag = true;
        $handle = fopen($filePath, "r");
        $i = 0;
        try {
            if ($handle) {
                $this->db->trans_start();
                $this->db->query('TRUNCATE TABLE ' . $tableName);   //清空表原有的数据
                $params = array();                                  //处理数据参数
                $fieldArr = array();                                //处理的字段
                while (!feof($handle)) {
                    $data = explode('	', fgets($handle));     //读取数据
                    if ($i == 0) {
                        $fieldArr = $data;
                    } else {
                        if (count($fieldArr) != count($data)) {
                            break;
                        }
                        $params[] = array_combine($fieldArr, $this->get_params($data));  //合并数据
                        if ($i % 2000 == 0) {
                            $row = $this->db->insert_batch($tableName, $params); //批量添加数据
                            if (empty($row)) {
                                $flag = false;
                                $this->db->trans_rollback();
                            }
                            
                            $params = array();
                        }
                    }
                    $i++;
                }
                //处理有完成的数据
                if (!empty($params)) {
                    $row = $this->db->insert_batch($tableName, $params);
                    if (empty($row)) {
                        $flag = false;
                        $this->db->trans_rollback();
                    }
                    $params = array();
                }
                //修改时间
                if (strpos($tableName, 'ship_user_info') !== false) {
                    $this->db->query('UPDATE `' . $tableName . '` SET create_time = DATE_ADD(create_time,INTERVAL 8 HOUR)');
                }
                fclose($handle);
                $this->db->trans_complete();
            }
        } catch (Exception $exc) {
            $this->db->trans_rollback();
            $flag = false;
        }
        return $flag;
    }

    /**
     * 处理时间类型的数据
     * @param type $params
     * @return type
     */
    public function get_params($params = array()) {
        foreach ($params as $k => $v) {
            $is_date = strtotime($v) ? strtotime($v) : false;
            $data = explode('.', $v);
            if ($is_date !== false && count($data) > 1) {
                $params[$k] = $data[0];
            }
        }
        return $params;
    }

//    /**
//     * 获取表明
//     * @param type $fileName
//     */
//    public function get_table_name($fileName = null) {
//        $params = explode('.', $fileName);
//        if (count($params) < 3) {
//            return false;
//        }
//
//        //获取所在的服务器
//        $tableName = substr($params[0], 1) . '_';
//
//        //表名称
//        $tableName .= substr($params[1], 1, -1);
//        return $tableName;
//    }
}
