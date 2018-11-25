<?php
/**
 * 结算模型
 *
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net
 * @link       http://www.shopec.net
 * @since      File available since Release v1.1
 */
defined('Inshopec') or exit('Access Invalid!');

//以下是定义结算单状态
//默认
define('BILL_STATE_CREATE',1);
//店铺已确认
define('BILL_STATE_STORE_COFIRM',2);
//平台已审核
define('BILL_STATE_SYSTEM_CHECK',3);
//结算完成
define('BILL_STATE_SUCCESS',4);

class vr_billModel extends Model {

    /**
     * 取得平台月结算单
     * @param unknown $condition
     * @param unknown $fields
     * @param unknown $pagesize
     * @param unknown $order
     * @param unknown $limit
     */
    public function getOrderStatisList($condition = array(), $fields = '*', $pagesize = null, $order = '', $limit = null) {
        return $this->table('vr_order_statis')->where($condition)->field($fields)->order($order)->page($pagesize)->limit($limit)->select();
    }

    /**
     * 取得平台月结算单条信息
     * @param unknown $condition
     * @param string $fields
     */
    public function getOrderStatisInfo($condition = array(), $fields = '*',$order = null) {
        return $this->table('vr_order_statis')->where($condition)->field($fields)->order($order)->find();
    }

    /**
     * 取得店铺月结算单列表
     * @param unknown $condition
     * @param string $fields
     * @param string $pagesize
     * @param string $order
     * @param string $limit
     */
    public function getOrderBillList($condition = array(), $fields = '*', $pagesize = null, $order = '', $limit = null) {
        return $this->table('vr_order_bill')->where($condition)->field($fields)->order($order)->limit($limit)->page($pagesize)->select();
    }

    /**
     * 取得店铺月结算单单条
     * @param unknown $condition
     * @param string $fields
     */
    public function getOrderBillInfo($condition = array(), $fields = '*') {
        return $this->table('vr_order_bill')->where($condition)->field($fields)->find();
    }
//----------by suijiailong---start---取得店铺的月结算单----
    /**
     * 取得店铺月结算单列表
     * @param unknown $condition
     * @param string $fields
     * @param string $pagesize
     * @param string $order
     * @param string $limit
     */
    public function getOrderBillByMonthList($condition = array(), $fields = '*', $pagesize = null, $order = '', $limit = null) {
        return $this->table('vr_order_bill_month')->where($condition)->field($fields)->order($order)->limit($limit)->page($pagesize)->select();
    }

    /**取得店铺月结算单单条记录
     * @param array $condition
     * @param string $fields
     * @return mixed
     */
    public function getOrderBillByMonthInfo($condition = array(), $fields = '*') {
        return $this->table('vr_order_bill_month')->where($condition)->field($fields)->find();
    }
//----------by suijiailong---end-------

    /**
     * 取得订单数量
     * @param unknown $condition
     */
    public function getOrderBillCount($condition) {
        return $this->table('vr_order_bill')->where($condition)->count();
    }

    public function addOrderStatis($data) {
        return $this->table('vr_order_statis')->insert($data);
    }

    public function addOrderBill($data) {
        return $this->table('vr_order_bill')->insert($data);
    }
    //----------by suijiailong---start---添加及修改店铺的月结算单----
    public function addOrderBillByMonth($data) {
        return $this->table('vr_order_bill_month')->insert($data);
    }
    public function editOrderBillByMonth($data, $condition = array()) {
        return $this->table('vr_order_bill_month')->where($condition)->update($data);
    }
    //----------by suijiailong---end-------

    public function editOrderBill($data, $condition = array()) {
        return $this->table('vr_order_bill')->where($condition)->update($data);
    }
}
