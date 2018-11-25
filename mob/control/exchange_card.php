<?php
/**
 * 平台兑换券
 *
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/9/009
 * Time: 14:00
 */

use shopec\Tpl;

defined('Inshopec') or exit('Access Invalid!');

class exchange_cardControl extends mobileMemberControl
{

    //允许购买商品数量（仅限于400元兑换券方案
    private static $proNum_400 = 1;

    /**
     * 平台兑换券判断信息
     * @param $order_pay_info array 订单信息
     * @param $sn string 兑换券号码
     * @auth Chenli
     */
    public function checkOp($order_pay_info, $sn)
    {
        $db = Model("exchange_card");
        //判断SN是否存在
        $result = $db->checkSn($sn,$order_pay_info[0]['buyer_id']);
//        print_r($result);
        if (empty($result)) {
            output_error("对不起，请输入正确的兑换券号码！");
            exit();
        }
        //判断是否过期或者未到期
        if (time() < (int)$result['start_time']) {
            output_error("对不起，该兑换券未到使用日期！");
            exit();
        }
        //判断日期是否过期
        if (!empty((int)$result['end_time'])) {
            if (time() > (int)$result['end_time']) {
                output_error("对不起，该兑换券已过期！");
                exit();
            }
        }

        $funName = "_" . $result['func_name'];
        $orderInfo = $this->$funName($order_pay_info, $result);

        //成功后调用修改表数据的方法
        $rst = $db->changeData($orderInfo, $sn);
        if ($rst) {
            $data = array('message'=>'兑换商品成功');
            output_data($data);
        } else {
            output_error('数据提交失败');
        }
    }

    /**
     * 平台兑换券判断信息
     * @param $order_pay_info array 订单信息
     * @param $sn string 兑换券号码
     * @auth liming
     */
    public function check_vrOp($order_pay_info, $sn)
    {

        $db = Model("exchange_card");
        //判断SN是否存在
        $result = $db->checkSn($sn,$order_pay_info['buyer_id']);
        if (empty($result)) {
            output_error("对不起，请输入正确的兑换券号码！");
            exit();
        }
        //判断是否过期或者未到期
        if (time() < (int)$result['start_time']) {
            output_error("对不起，该兑换券未到使用日期！");
            exit();
        }
        //判断日期是否过期
        if (!empty((int)$result['end_time'])) {
            if (time() > (int)$result['end_time']) {
                output_error("对不起，该兑换券已过期！");
                exit();
            }
        }


        $funName = "vr_" . $result['func_name'];
        $orderInfo = $this->$funName($order_pay_info, $result);

        //成功后调用修改表数据的方法
        $rst = $db->changeVrData($orderInfo, $sn);
        if ($rst) {
            //发送兑换码到手机
            Model('vr_order')->addOrderCode($orderInfo);
            $data = array('message'=>'兑换商品成功');
            output_data($data);
        } else {
            output_error('数据提交失败');
        }
    }

    /*---------------------------兑换活动方案名称--------------------------------*/

    /**
     *  400元商品兑换券方案(实物兑换)
     * @param $order_pay_info array 订单数据
     * @param $exchange_arr array   活动详情
     */
    private function _dhq_400($order_pay_info, $exchange_arr)
    {
        //判断商品数量
        /*if (count($order_pay_info) > self::$proNum_400) {
            self::returnMsg("对不起，本券只允许购买一件商品");
        }*/

        //判断商品数量
        if (is_array($order_pay_info) && count($order_pay_info) > self::$proNum_400) {
            output_error("对不起，本券只允许购买一件商品");
        }else{
            $db = Model("order_goods");
            $map['order_id'] = $order_pay_info[0]['order_id'];
            $count = $db->where($map)->count();
            if ($count > self::$proNum_400) {
                output_error("对不起，本券只允许购买一件商品");
            }
        }

        //订单实物商品表
        $billArr = $db->where($map)->field("order_id,goods_num,goods_price,goods_pay_price")->find();
        //判断商品数量
        if ($billArr['goods_num'] > self::$proNum_400) {
            output_error("对不起，本券只允许购买一件商品！");
        }
        //判断商品价格是否大于兑换券发行价格
        if ($exchange_arr['denomination'] < $billArr['goods_pay_price']) {
            output_error("对不起，该商品大于本券面额！");
        }
        //返回订单
        return $order_pay_info[0];
    }

    /**
     *  400元商品兑换券方案(虚拟兑换)
     * @param $order_pay_info array 订单数据
     * @param $exchange_arr array   活动详情
     */
    private function vr_dhq_400($order_pay_info, $exchange_arr)
    {
        //判断商品数量(不能加入购物车,所以只判断当前商品的数量)
        if ($order_pay_info['goods_num'] > self::$proNum_400) {
            output_error("对不起，本券只允许购买一件商品");
        }

        //订单商品表
        $db = Model('goods');
        $vrInfo = $db->field('goods_price')->where(array('goods_id'=>$order_pay_info['goods_id']))->find();
        //判断商品价格是否大于兑换券发行价格
        if ($exchange_arr['denomination'] < $vrInfo['goods_price']) {
            output_error("对不起，该商品大于本券面额！");
        }
        //返回订单
        return $order_pay_info;
    }

    /**
     * 兑换卷 列表
     */
    public function dhq_listOp(){
        $db =Model('exchange_card');
        $dhq_list = $db->getExchangeCardByMemberId($this->member_info['member_id'],true);
        output_data($dhq_list);
    }
}

