<?php
/**
 * 会员实物订单
 * @author gongbo
 * @date 20161104
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

class member_real_billModel extends Model{

    public function __construct() {
        parent::__construct('member_real_bill');
    }

    /**
     * 读取会员实物订单列表
     * @param array $condition
     *
     */
    public function getMemberRealBillList($condition = array(), $fields = '*', $pagesize = null, $order = 'ob_end_date', $limit = null) {
        $on = "member_real_bill.member_store_ob_id=order_bill_month.ob_id";
        $list =  $this->table('member_real_bill,order_bill_month')->join('inner')->on($on)->where($condition)->field($fields)->order($order)->page($pagesize)->limit($limit)->select();
        return $list;
    }
    public function getMemberRealBillList2($condition = array(), $fields = '*', $pagesize = null, $order = 'ob_end_date', $limit = null) {
        $on = "member_real_bill.member_ob_member_id=member.member_id";
        $list =  $this->table('member_real_bill,member')->join('inner')->on($on)->where($condition)->field($fields)->order($order)->page($pagesize)->limit($limit)->select();
        return $list;
    }

    /**
     * 获取单条结算信息
     * @param string $fields
     */
    public function getMemberRealBillInfo($condition = array(), $fields = '*') {
      return  $this->table('member_real_bill')->where($condition)->field($fields)->find();
    }

    public function editMemberRealBill($data, $condition = array()) {
        return $this->table('member_real_bill')->where($condition)->update($data);
    }

    /**
     * 获取单条结算信息
     * @param string $fields
     */
    public function getOrderBillInfo($condition = array(), $fields = '*') {
        return $this->table('member_real_bill')->where($condition)->field($fields)->find();
    }

    /**
     * 添加一条结算信息
     * @param string $fields
     */
    public function addOrderBill($data) {
        return $this->table('member_real_bill')->insert($data);
    }

    /**
     * 编辑一条结算信息
     * @param string $fields
     */
    public function editOrderBill($condition = array(),$data) {
        return $this->table('member_real_bill')->where($condition)->update($data);
    }

    /**
     * 统计数量
     * @param string $fields
     */
    public function getOrderBillCount($condition) {
        return $this->table('member_real_bill')->where($condition)->count();
    }


}
