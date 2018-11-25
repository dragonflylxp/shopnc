<?php
/**
 * 分销佣金
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net
 * @link       http://www.shopec.net
 * @since      File available since Release v1.1
 */
defined('Inshopec') or exit('Access Invalid!');

class dis_tradModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取佣金变更日志
     */
    public function getDistriTradList($condition = array(), $field = '*', $page = 0, $order = 'lg_id desc', $limit = 0)
    {
        return $this->table('dis_trad_log')->field($field)->where($condition)->order($order)->page($page)->limit($limit)->select();
    }

    /**
     * 添加佣金变更日志
     */
    public function addDistriTrad($data)
    {
        return $this->table('dis_trad_log')->insert($data);
    }

    /**
     * 获取佣金提现记录
     */
    public function getDistriTradCashList($condition = array(), $field = '*', $page = 0, $order = 'tradc_id desc', $limit = 0)
    {
        return $this->table('dis_trad_cash')->field($field)->where($condition)->order($order)->page($page)->limit($limit)->select();
    }

    /**
     * 获取单条佣金提现记录
     */
    public function getDistriTradCashInfo($condition = array(), $field = '*', $order = 'tradc_id desc')
    {
        return $this->table('dis_trad_cash')->field($field)->where($condition)->order($order)->find();
    }

    public function getDistriCashCount($condition = array()){
        return $this->table('dis_trad_cash')->where($condition)->count();
    }

    /**
     * 添加佣金提现记录
     */
    public function addDistriTradCash($data)
    {
        return $this->table('dis_trad_cash')->insert($data);
    }

    /**
     * 更新佣金提现记录
     */
    public function updateDistriTradCash($data, $condition = array())
    {
        return $this->table('dis_trad_cash')->where($condition)->update($data);
    }

    /**
     * 删除佣金提现记录
     * @param $condition
     * @return mixed
     */
    public function delDistriTradCash($condition){
        return $this->table('dis_trad_cash')->where($condition)->delete();
    }

    /**
     * 生成提现编号
     * @return string
     */
    public function makeSn()
    {
        return mt_rand(10, 99) . sprintf('%010d', time() - 946656000) . sprintf('%03d', (float)microtime() * 1000) . sprintf('%03d', (int)$_SESSION['member_id'] % 1000);
    }

    /**
     * 变更预存款
     * @param unknown $change_type
     * @param unknown $data
     * @throws Exception
     * @return unknown
     */
    public function changeDirtriTrad($change_type, $data = array())
    {
        $data_log = array();
        $data_trad = array();
        $data_msg = array();

        $data_log['lg_member_id'] = $data['member_id'];
        $data_log['lg_member_name'] = $data['member_name'];
        $data_log['lg_add_time'] = TIMESTAMP;
        $data_log['lg_type'] = $change_type;

        $data_msg['time'] = date('Y-m-d H:i:s');
        $data_msg['trad_url'] = urlDistribute('cash', 'cash_list');
        switch ($change_type) {
            case 'cash_apply':
                $data_log['lg_av_amount'] = -$data['amount'];
                $data_log['lg_freeze_amount'] = $data['amount'];
                $data_log['lg_desc'] = '申请佣金提现，冻结佣金，提现单号: ' . $data['order_sn'];

                $data_trad['trad_amount'] = array('exp', 'trad_amount-' . $data['amount']);
                $data_trad['freeze_trad'] = array('exp', 'freeze_trad+' . $data['amount']);

                $data_msg['av_amount'] = -$data['amount'];
                $data_msg['freeze_amount'] = $data['amount'];
                $data_msg['desc'] = $data_log['lg_desc'];
                break;
            case 'cash_pay':
                $data_log['lg_freeze_amount'] = -$data['amount'];
                $data_log['lg_desc'] = '佣金提现成功，提现单号: ' . $data['order_sn'];
                $data_log['lg_admin_name'] = $data['admin_name'];

                $data_trad['freeze_trad'] = array('exp', 'freeze_trad-' . $data['amount']);

                $data_msg['av_amount'] = 0;
                $data_msg['freeze_amount'] = -$data['amount'];
                $data_msg['desc'] = $data_log['lg_desc'];
                break;
            case 'cash_del':
                $data_log['lg_av_amount'] = $data['amount'];
                $data_log['lg_freeze_amount'] = -$data['amount'];
                $data_log['lg_desc'] = '取消佣金提现申请，解冻佣金，提现单号: ' . $data['order_sn'];
                $data_log['lg_admin_name'] = $data['admin_name'];

                $data_trad['trad_amount'] = array('exp', 'trad_amount+' . $data['amount']);
                $data_trad['freeze_trad'] = array('exp', 'freeze_trad-' . $data['amount']);

                $data_msg['av_amount'] = $data['amount'];
                $data_msg['freeze_amount'] = -$data['amount'];
                $data_msg['desc'] = $data_log['lg_desc'];
                break;
            default:
                throw new Exception('参数错误');
                break;
        }

        $update = Model('member')->editMember(array('member_id' => $data['member_id']), $data_trad);

        if (!$update) {
            throw new Exception('操作失败');
        }
        $insert = $this->table('dis_trad_log')->insert($data_log);
        if (!$insert) {
            throw new Exception('操作失败');
        }

        // 支付成功发送买家消息
        $param = array();
        $param['code'] = 'trad_change';
        $param['member_id'] = $data['member_id'];
        $data_msg['av_amount'] = ncPriceFormat($data_msg['av_amount']);
        $data_msg['freeze_amount'] = ncPriceFormat($data_msg['freeze_amount']);
        $param['param'] = $data_msg;
        QueueClient::push('sendMemberMsg', $param);
        return $insert;
    }

    /**
     * 退出自动提现
     */
    public function autoDistriTrad($member_info)
    {
        try {
            $this->beginTransaction();
            $tradc_sn = $this->makeSn();
            $tradc_amount = $member_info['trad_amount'];
            $insert = 1;
            if($tradc_amount > 0){
                $data = array();
                $data['tradc_sn'] = $tradc_sn;
                $data['tradc_member_id'] = $member_info['member_id'];
                $data['tradc_member_name'] = $member_info['member_name'];
                $data['tradc_amount'] = $tradc_amount;
                $data['tradc_bank_name'] = $member_info['bill_bank_name'];
                $data['tradc_bank_no'] = $member_info['bill_type_number'];
                $data['tradc_bank_user'] = $member_info['bill_user_name'];
                $data['tradc_add_time'] = TIMESTAMP;
                $data['tradc_payment_state'] = 0;
                $insert = $this->addDistriTradCash($data);
                if (!$insert) {
                    throw new Exception();
                }

                //增加冻结分校佣金
                $data = array();
                $data['member_id'] = $member_info['member_id'];
                $data['member_name'] = $member_info['member_name'];
                $data['amount'] = $tradc_amount;
                $data['order_sn'] = $tradc_sn;
                $this->changeDirtriTrad('cash_apply', $data);
            }

            $this->commit();
            return $insert;
        } catch (Exception $e) {
            $this->rollback();
            return false;
        }
    }


}