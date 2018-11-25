<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/10/010
 * Time: 11:56
 */

defined('Inshopec') or exit('Access Invalid!');

class exchange_cardModel extends Model
{

    public function __construct()
    {
        parent::__construct('exchange_card');
    }

    /**
     * 查询兑换卷是否存在
     * @param   string  $sn  卡号
     * @param   int member_id 会员ID
     * @author  liming
     * @return  array
     */
    public function checkSn($sn,$member_id='')
    {
        $member_id = !empty($member_id) ?  $member_id: $_SESSION['member_id'];
        return $this->where(array('sn' => $sn, 'state' => 0,'member_id'=>$member_id))->field("id,sn,func_name,start_time,end_time,state,denomination")->find();
    }

    /**
     * 修改平台兑换卷
     * @param  string $sn               卡号
     * @param  array $orderInfo         订单信息
     * @param int $exchangeType         兑换类型(1实物,0虚拟)
     * @author liming
     * @return bool
     */
    private function changeExData($orderInfo,$sn,$exchangeType)
    {
        $exData = array(
            'state' => '1',
            'tsused' => time(),
            'order_id'=>$orderInfo['order_id'],
        );
        //判断虚拟和实物兑换
        if($exchangeType==1){
            $exData['exchange_type'] = '1';
        }elseif($exchangeType==0){
            $exData['exchange_type'] = '0';
        }
        $exRst = Model('exchange_card')->where(array('sn' => $sn, 'state' => '0'))->update($exData);
        if (!$exRst) {
            $this->rollback();
            return false;
        }
    }

    /**
     * 修改实物订单数据
     * @param  array $orderInfo 订单信息
     * @author liming
     * @return bool
     */
    private function changeOData($orderInfo)
    {
        $time = time();
        $oData = array(
            'payment_code' => 'dhq',
            'payment_time' => $time,

            //------使用兑换卷  使实际支付的现金金额变为0------//
//            'order_amount' => '0',
            'order_state' => ORDER_STATE_PAY
        );
        $oRst = Model('orders')->where(array('order_id' => $orderInfo['order_id'], 'order_state' => ORDER_STATE_NEW))->update($oData);
        if (!$oRst) {
            $this->rollback();
            return false;
        }
    }

    /**
     * 修改虚拟订单数据
     * @param  array $orderInfo 订单信息
     * @author liming
     * @return bool
     */
    private function changeOVrData($orderInfo)
    {
        $time = time();
        $oData = array(
            'payment_code' => 'dhq',
            'payment_time' => $time,
//            'order_amount' => '0',
            'order_state' => ORDER_STATE_PAY
        );
        $oRst = Model('vr_order')->where(array('order_id' => $orderInfo['order_id'], 'order_state' => ORDER_STATE_NEW))->update($oData);
        if (!$oRst) {
            $this->rollback();
            return false;
        }
    }

    /**
     * 插入订单支付日志
     * @param $orderInfo
     * @return bool
     */
    private function changeLogData($orderInfo)
    {
        $member_name = Model('member')->getfby_member_id($_SESSION['member_id'], 'member_name');
        $logData = array(
            'order_id' => $orderInfo['order_id'],
            'log_msg' => '使用兑换卷支付成功',
            'log_time' => time(),
            'log_role' => '买家',
            'log_user' => $member_name,
            'log_orderstate' => ORDER_STATE_PAY
        );
        $logRst = Model('order_log')->insert($logData);
        if (!$logRst) {
            $this->rollback();
            return false;
        }
    }

    /**
     * 修改支付附加表
     * @return bool
     */
    private function changePData($orderInfo)
    {
        $pRst = Model('order_pay')->where(array('pay_sn' => $orderInfo['pay_sn'], 'api_pay_state' => 0))->update(array('api_pay_state' => 1));
        if (!$pRst) {
            $this->rollback();
            return false;
        }
    }

    /*-----------------------------修改支付时对应表数据--------------------*/

    /**
     * 编辑实物商品表数据
     * @param $orderInfo
     * @param $sn
     * @return bool
     */
    public function changeData($orderInfo, $sn)
    {
        $this->beginTransaction();
        //修改平台兑换券
        $this->changeExData($orderInfo,$sn,1);
        //修改订单表
        $this->changeOData($orderInfo);
        //修改日志表
        $this->changeLogData($orderInfo);
        //修改支付表
        $this->changePData($orderInfo);
        $this->commit();
        return true;
    }

    /**
     * 编辑虚拟商品表数据
     * @param $orderInfo
     * @param $sn
     * @return bool
     */
    public function changeVrData($orderInfo, $sn)
    {
        $this->beginTransaction();
        //修改平台兑换券
        $this->changeExData($orderInfo,$sn,0);
        //修改订单表
        $this->changeOVrData($orderInfo);
        //修改日志表
//        $this->changeLogData($orderInfo);
        //修改支付表
//        $this->changePData($orderInfo);
        $this->commit();
        return true;
    }

    /**

     * 获取充值卡列表
     *
     * @param array $condition 条件数组
     * @param int $pageSize 分页长度
     * @return array 充值卡列表
     */
    public function getRechargeCardList($condition, $pageSize = 20, $limit = null, $sort = 'id desc')
    {
        if ($condition) {
            $this->where($condition);
        }

        if ($sort) {
            $this->order($sort);
        }

        if ($limit) {
            $this->limit($limit);
        } else {
            $this->page($pageSize);
        }

        return $this->select();
    }

    /**
     * 通过卡号获取单条充值卡数据
     *
     * @param string $sn 卡号
     *
     * @return array|null 充值卡数据
     */
    public function getRechargeCardBySN($sn)
    {
        return $this->where(array(
            'sn' => (string) $sn,
        ))->find();
    }

    /**
     * 设置充值卡为已使用
     * @param int $id 表字增ID
     * @param int $memberId 会员ID
     * @param string $memberName 会员名称
     * @author RQS
     * @return boolean
     */
    public function setRechargeCardUsedById($id, $memberId, $memberName)
    {
        return $this->where(array(
            'id' => (string) $id,
        ))->update(array(
            'tsused' => time(),
            'state' => 1,
            'member_id' => $memberId,
            'member_name' => $memberName,
        ));
    }

    /**
     * 通过ID删除充值卡（自动添加未使用标记）
     *
     * @param int|array $id 表字增ID(s)
     *
     * @return boolean
     */
    public function delRechargeCardById($id)
    {
        return $this->where(array(
            'id' => array('in', (array) $id),
            'state' => 0,
        ))->delete();
    }

    /**
     * 通过给定的卡号数组过滤出来不能被新插入的卡号（卡号存在的）
     *
     * @param array $sns 卡号数组
     *
     * @return array
     */
    public function getOccupiedRechargeCardSNsBySNs(array $sns)
    {
        $array = $this->field('sn')->where(array(
            'sn' => array('in', $sns),
        ))->select();

        $data = array();

        foreach ((array) $array as $v) {
            $data[] = $v['sn'];
        }

        return $data;
    }

    public function getRechargeCardCount($condition) {
        return $this->where($condition)->count();
    }

    /**
     * 根据会员ID号获取卡号
     * @param $member_id string 会员id
     * @param $is_state bool 是否查询过滤已使用记录
     * @author Chenli
     * @return array
     */
    public function getExchangeCardByMemberId($member_id,$is_state = false){
        $map['member_id']=$member_id;
        if($is_state) $map['state'] = '0';

        return  $this->where($map)->field("sn,denomination,start_time,end_time,state,member_id,order_id")->select();
    }

}