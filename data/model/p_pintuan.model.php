<?php
/**
 * 拼团模型
 *
 *
 *
 *
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net
 * @link       http://www.shopec.net
 * @since      File available since Release v1.1
 */
defined('Inshopec') or exit('Access Invalid!');
use shopec\Tpl;
class p_pintuanModel extends Model{
    public function __construct(){
        parent::__construct();
    }

    /**
     * 读取列表
     * @param array $condition 查询条件
     * @param int $page 分页数
     * @param string $order 排序
     * @param string $field 所需字段
     * @return array 列表
     *
     */
    public function getList($condition, $page=null, $order='pintuan_id desc', $field='*', $limit=0) {
        $list = $this->table('p_pintuan')->field($field)->where($condition)->limit($limit)->page($page)->order($order)->select();
        return $list;
    }

    /**
     * 根据条件读取信息
     * @param array $condition 查询条件
     * @return array 信息
     *
     */
    public function getInfo($condition) {
        $_info = $this->table('p_pintuan')->where($condition)->find();
        return $_info;
    }

    /**
     * 状态数组
     *
     */
    public function getStateArray() {
        $state_array = array(
            '0' => '取消',
            '1' => '正常'
            );
        Tpl::output('state_array', $state_array);
        return $state_array;
    }

    /*
     * 增加
     * @param array $param
     * @return bool
     *
     */
    public function add($param){
        return $this->table('p_pintuan')->insert($param);
    }

    /*
     * 更新
     * @param array $update
     * @param array $condition
     * @return bool
     *
     */
    public function edit($update, $condition){
        return $this->table('p_pintuan')->where($condition)->update($update);
    }

    /*
     * 删除活动，同时删除商品
     * @param array $condition
     * @return bool
     *
     */
    public function del($condition){
        $this->delGoods($condition);
        return $this->table('p_pintuan')->where($condition)->delete();
    }

    /*
     * 取消活动，同时取消商品
     * @param array $condition
     * @return bool
     *
     */
    public function cancel($condition){
        $update = array();
        $update['state'] = 0;
        $this->editGoods($update, $condition);
        return $this->edit($update, $condition);
    }
    public function getGoodsList($condition, $page=null, $order='pintuan_goods_id desc', $field='*', $limit = 0) {
        return $this->table('p_pintuan_goods')->field($field)->where($condition)->page($page)->order($order)->limit($limit)->select();
    }
    public function getGoodsInfo($condition) {
        return $this->table('p_pintuan_goods')->where($condition)->find();
    }
    public function addGoods($goods_info){
        $goods_id = $this->table('p_pintuan_goods')->insert($goods_info);
        return $goods_id;
    }
    public function editGoods($update, $condition){
        $goods_id = $this->table('p_pintuan_goods')->where($condition)->update($update);
        return $goods_id;
    }
    public function delGoods($condition) {
        return $this->table('p_pintuan_goods')->where($condition)->delete();
    }
    public function getQuotaCurrent($store_id) {
        $condition = array();
        $condition['store_id'] = $store_id;
        $condition['end_time'] = array('gt', TIMESTAMP);
        return $this->table('p_pintuan_quota')->where($condition)->find();
    }
    public function getQuotaInfo($condition) {
        return $this->table('p_pintuan_quota')->where($condition)->find();
    }
    public function addQuota($param){
        return $this->table('p_pintuan_quota')->insert($param);
    }
    public function editQuota($update, $condition){
        return $this->table('p_pintuan_quota')->where($condition)->update($update);
    }
    public function getQuotaList($condition, $page=null, $order='quota_id desc', $field='*', $limit = 0) {
        return $this->table('p_pintuan_quota')->field($field)->where($condition)->page($page)->order($order)->limit($limit)->select();
    }
    public function addOrder($param){
        return $this->table('order_pintuan')->insert($param);
    }
    public function getOrderInfo($condition) {
        return $this->table('order_pintuan')->where($condition)->find();
    }
    public function getOrderList($condition, $page=null, $order='log_id desc', $field='*', $limit = 0) {
        return $this->table('order_pintuan')->field($field)->where($condition)->page($page)->order($order)->limit($limit)->select();
    }
    public function payOrder($order){//支付完成修改参团记录
        if (!empty($order) && is_array($order)) {
            $order_id = $order['order_id'];
            $condition = array('order_id'=> $order_id);
            $this->table('order_pintuan')->where($condition)->update(array('pay_time'=> TIMESTAMP));
            $_info = $this->getOrderInfo($condition);
            $buyer_type = $_info['buyer_type'];
            if ($buyer_type > 0) {//参团类型:0为团长,其它为参团
                $condition = array();
                $condition['buyer_type'] = $buyer_type;
                $condition['pay_time'] = array('gt',0);
                $_n = $this->table('order_pintuan')->where($condition)->count();//参团总人数
                $min_num = $_info['min_num'];
                if ($_n >= ($min_num-1)) {
                    $this->table('order_pintuan')->where($condition)->update(array('lock_state'=> 0));
                    $this->table('order_pintuan')->where(array('log_id'=> $buyer_type))->update(array('lock_state'=> 0));
                }
            }
        }
    }
    public function orderRefund() {//拼团退款
        $model_refund = Model('refund_return');
        $condition = array();
        $condition['lock_state'] = 1;//锁定状态:0是正常,1是锁定
        $condition['cancel_time'] = 0;
        $condition['end_time'] = array('lt',TIMESTAMP);
        $list = $this->table('order_pintuan')->where($condition)->limit(false)->select();
        if(!empty($list) && is_array($list)) {
            foreach($list as $k => $v) {
                if ($v['pay_time']) {
                    $order_id = $v['order_id'];
                    $order = $this->table('orders')->where(array('order_id'=> $order_id))->find();
                    $order_amount = $order['order_amount'];//订单金额
                    if ($order['order_state'] == ORDER_STATE_PAY) {
                        $refund_array = array();
                        $refund_array['refund_type'] = '1';//类型:1为退款,2为退货
                        $refund_array['seller_state'] = '2';//状态:1为待审核,2为同意,3为不同意
                        $refund_array['refund_state'] = '2';//状态:1为处理中,2为待管理员处理,3为已完成
                        $refund_array['order_lock'] = '2';//锁定类型:1为不用锁定,2为需要锁定
                        $refund_array['goods_id'] = '0';
                        $refund_array['order_goods_id'] = '0';
                        $refund_array['reason_id'] = '0';
                        $refund_array['refund_amount'] = ncPriceFormat($order_amount);
                        $refund_array['goods_name'] = '订单商品全部退款';
                        $refund_array['reason_info'] = '拼团失败，全部退款';
                        $refund_array['buyer_message'] = '拼团失败,待管理员确认退款';
                        $refund_array['seller_message'] = '拼团失败,待管理员确认退款';
                        $refund_array['add_time'] = TIMESTAMP;
                        $refund_array['seller_time'] = TIMESTAMP;
                        $model_refund->addRefundReturn($refund_array,$order);
                        $model_refund->editOrderLock($order_id);
                    }
                }
            }
            $this->table('order_pintuan')->where($condition)->update(array('cancel_time'=> TIMESTAMP,'lock_state'=> 0));
        }
    }

    /**
     * 刷新限时折扣商品 
     *
     */
    public function updatePintuanGoods(){
        $pintuan_goods_list = $this->getGoodsList(array(), null, '', 'goods_id');
        if (!empty($pintuan_goods_list)) {
            foreach ($pintuan_goods_list as $val) {
                // 插入对列 更新促销价格
                QueueClient::push('updateGoodsPromotionPriceByGoodsId', $val['goods_id']);
            }
        }
    }
}
