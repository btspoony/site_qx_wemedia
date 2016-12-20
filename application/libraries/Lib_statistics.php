<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * 数据统计处理
 * @author slf
 * @time 16/9/6
 */
class Lib_statistics extends Library {

    public function __construct() {
        parent::__construct();
    }

    /**
     * 留存数据梳理
     * @param type $data
     * @return array
     */
    public function retaineds($data = array()) {
        $tb = array(
            'dates' => array(),
            'new_retaineds_1' => array(),
            'new_retaineds_3' => array(),
            'new_retaineds_7' => array(),
            'new_retaineds_15' => array(),
            'new_retaineds_30' => array(),
            'new_retaineds_45' => array(),
            'new_retaineds_60' => array(),
            'new_retaineds_75' => array(),
            'new_retaineds_90' => array(),
            'new_retaineds_120' => array()
        );
        if (empty($data)) {
            return $tb;
        }

        foreach ($data as $v) {
            $tb['new_retaineds_1'][] = $this->retained($v['new_retained_1']);
            $tb['new_retaineds_3'][] = $this->retained($v['new_retained_3']);
            $tb['new_retaineds_7'][] = $this->retained($v['new_retained_7']);
            $tb['new_retaineds_15'][] = $this->retained($v['new_retained_15']);
            $tb['new_retaineds_30'][] = $this->retained($v['new_retained_30']);
            $tb['new_retaineds_45'][] = $this->retained($v['new_retained_45']);
            $tb['new_retaineds_60'][] = $this->retained($v['new_retained_60']);
            $tb['new_retaineds_75'][] = $this->retained($v['new_retained_75']);
            $tb['new_retaineds_90'][] = $this->retained($v['new_retained_90']);
            $tb['new_retaineds_120'][] = $this->retained($v['new_retained_120']);
            $tb['dates'][] = date('n-d', strtotime($v['date']));
        }
        return $tb;
    }

    public function retained($v) {
        if (empty($v)) {
            return 0;
        }
        return str_replace('%', '', $v);
    }

    /**
     * 登陆注册图表
     */
    function sameday($sameday = array()) {
        $tb = array('register_counts' => array(), 'login_role_counts' => array(), 'payment_moneys' => array(), 'dates' => array());
        if (empty($tb)) {
            return $tb;
        }
        foreach ($sameday as $v) {
            $tb['register_counts'][] = $v['register_count'];
            $tb['login_role_counts'][] = $v['login_role_count'];
            $tb['payment_moneys'][] = $v['payment_money'];
            $tb['dates'][] = date('n-d', strtotime($v['date']));
        }
        return $tb;
    }

    /**
     *  搜索
     * @param type $data
     * @return string
     */
    function searchWhere($data = null) {
        $where = ' 1= 1 ';
        if (!empty($data['start'])) {
            $where .= " AND date >='{$data['start']}'";
        }
        if (!empty($data['end'])) {
            $where .= " AND date <='{$data['end']}'";
        }
        return $where;
    }

    /**
     * 用户统计
     */
    function newUserTotal($data = array()){
        if(empty($data)) {
            return false; 
        }
        $retData = array('new_register_total' => 0,
                         'new_role_total' => 0,
                         'new_payment_total' => 0,
                         'new_register_paymen_total' => 0,
                         'new_register_paymen_money_total' => 0);
        foreach($data as $v) {
            //新增注册数平均
            $retData['new_register_total'] += $v['new_register_count'];
            //新增角色数平均
            $retData['new_role_total'] += $v['new_role_count'];
            //新增付费角色数
            $retData['new_payment_total'] += $v['new_payment_count'];
            //新增注册用户付费数
            $retData['new_register_paymen_total'] += $v['new_register_paymen_count'];
            //新增注册付费
            $retData['new_register_paymen_money_total'] += $v['new_register_paymen_money'];
        }
        $num = count($data);
        $retData['new_register_average'] = number_handle($retData['new_register_total']/$num,0);
        $retData['new_role_average'] = number_handle($retData['new_role_total']/$num,0);
        $retData['new_payment_average'] = number_handle($retData['new_payment_total']/$num,0);
        $retData['new_register_paymen_average'] = number_handle($retData['new_register_paymen_total']/$num,0);
        $retData['new_register_paymen_money_average'] = number_handle($retData['new_register_paymen_money_total']/$num,0);
        return $retData;
    }
    
    /**
     * 当日数据汇总
     * @param type $data
     * @return int
     */
    function daySummaryTotal($data = array()) {
        if(empty($data)) {
            return false; 
        }
        $retData = array('login_role_total' => 0,
                         'payment_money_total' => 0,
                         'payment_role_total' => 0);
        foreach($data as $v) {
            //总登陆角色数
            $retData['login_role_total'] += $v['login_role_count'];
            //总充值
            $retData['payment_money_total'] += $v['payment_money'];
            //总付费角色数
            $retData['payment_role_total'] += $v['payment_role_count'];
        }
        $num = count($data);
        $retData['login_role_average'] = number_handle($retData['login_role_total']/$num,0);
        $retData['payment_money_average'] = number_handle($retData['payment_money_total']/$num,0);
        $retData['payment_role_average'] = number_handle($retData['payment_role_total']/$num,0);
        return $retData;
    }
}
