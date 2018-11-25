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

class virtual_billModel extends Model {

    /**
     * 取得店铺月结算单列表
     * @param unknown $condition
     * @param string $fields
     * @param string $pagesize
     * @param string $order
     * @param string $limit
     */
    public function getOrderBillList($condition = array(), $fields = '*', $pagesize = null, $order = '', $limit = null) {
    $on = "member_vr_bill.member_store_id=store.store_id,member_vr_bill.member_ob_member_id=member.member_id,member_vr_bill.member_store_ob_id=vr_order_bill.ob_id";
    return $this->table('member_vr_bill,store,member,vr_order_bill')->field($fields)->join("inner,inner")->on($on)->where($condition)->order($order)->limit($limit)->page($pagesize)->select();
}

    /**
     * 查询会员和账单信息
     * @param array $condition
     * @param string $fields
     * @param null $pagesize
     * @param string $order
     * @param null $limit
     * @return mixed
     */
    public function getOrderBillList2($condition = array(), $fields = '*', $pagesize = null, $order = '', $limit = null) {
        $on = "member_vr_bill.member_ob_member_id=member.member_id";
        return $this->table('member_vr_bill,member')->field($fields)->join("inner,inner")->on($on)->where($condition)->order($order)->limit($limit)->page($pagesize)->select();
    }
    /**
     * 取得店铺月结算单单条
     * @param unknown $condition
     * @param string $fields
     */
    public function getOrderBillInfo($condition = array(), $fields = '*') {
    	$on = "member_vr_bill.member_store_id=store.store_id,member_vr_bill.member_ob_member_id=member.member_id,member_vr_bill.member_store_ob_id=vr_order_bill.ob_id";
        return $this->table('member_vr_bill,store,member,vr_order_bill')->field($fields)->join("inner,inner")->on($on)->where($condition)->find();
    }

    /**
     * 取得订单数量
     * @param unknown $condition
     */
    public function getOrderBillCount($condition) {
        return $this->table('member_vr_bill')->where($condition)->count();
    }

    public function addOrderStatis($data) {
        return $this->table('order_statis')->insert($data);
    }

    public function addOrderBill($data) {
        return $this->table('member_vr_bill')->insert($data);
    }

    public function editOrderBill($data, $condition = array()) {
        return $this->table('member_vr_bill')->where($condition)->update($data);
    }
}
