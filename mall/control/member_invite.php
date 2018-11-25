<?php
/**
 * 邀请注册 20160906
 *
 * @User      noikiy
 * @File      member_invite.php
 * @Link      
 * @Copyright 2015
 */


use shopec\Tpl;


defined('Inshopec') or exit('Access Invalid!');
class member_inviteControl extends BaseMemberControl
{
    public function __construct() {
        parent::__construct();

        /**
         * 读取语言包
         */
        Language::read('member_member_points,member_pointorder,member_invite');

        /**
         * 判断系统是否开启积分功能
         */
        if (C('points_isuse') != 1){
            showMessage(Language::get('points_unavailable'),urlShop('member', 'home'),'html','error');
        }
    }

    public function indexOp() {
        $this->firstOp();
        exit;
    }

    //一级推广
    public function firstOp() {
        $model_invite = Model('member');

        $condition = array();
        $condition['inviter_id'] = $this->member_info['member_id'];

        $invite_list = $model_invite->getMemberList($condition, '*');

        if (is_array($invite_list) && !empty($invite_list)) {
            //计算用户的累计返利金额
            foreach ($invite_list as $key => $value) {
                $invite_list[$key]['buy_count'] = $this->getBuyCountByBuyerId($value['member_id']);
                $invite_list[$key]['refund_amount'] = $this->getCommision($this->member_info['member_name'], $value['member_name']);
            }
        }

        //信息输出
        self::profile_menu('first');
        Tpl::output('show_page', $model_invite->showpage());
        Tpl::output('invite_list', $invite_list);
        Tpl::showpage('member_invite.list');
    }

    //二级推广
    public function secondOp() {

        $invite_relation_list = $this->getInviteRelation($this->member_info['member_id'], 2, 'member_id');
        $invite_list = $invite_relation_list[1];

        if (is_array($invite_list) && !empty($invite_list)) {
            foreach ($invite_list as $key => $value) {
                $member_id_array[] = $value['member_id'];
            }
            $member_id_str = implode(',', $member_id_array);
            $model_invite = Model('member');
            $condition = array();
            $condition['member_id'] = array('in', $member_id_str);
            $invite_list = $model_invite->getMemberList($condition, '*');

            //计算用户的累计返利金额
            foreach ($invite_list as $key => $value) {
                $invite_list[$key]['buy_count'] = $this->getBuyCountByBuyerId($value['member_id']);
                $invite_list[$key]['refund_amount'] = $this->getCommision($this->member_info['member_name'], $value['member_name']);
            }

            Tpl::output('show_page', $model_invite->showpage());
        }

        //信息输出
        self::profile_menu('second');
        
        Tpl::output('invite_list', $invite_list);
        Tpl::showpage('member_invite.list');
    }

    //三级推广
    public function thirdOp() {

        $invite_relation_list = $this->getInviteRelation($this->member_info['member_id'], 3, 'member_id');
        $invite_list = $invite_relation_list[2];
        
        if (is_array($invite_list) && !empty($invite_list)) {
            foreach ($invite_list as $key => $value) {
                $member_id_array[] = $value['member_id'];
            }
            $member_id_str = implode(',', $member_id_array);
            $model_invite = Model('member');
            $condition = array();
            $condition['member_id'] = array('in', $member_id_str);
            $invite_list = $model_invite->getMemberList($condition, '*');

            //计算用户的累计返利金额
            foreach ($invite_list as $key => $value) {
                $invite_list[$key]['buy_count'] = $this->getBuyCountByBuyerId($value['member_id']);
                $invite_list[$key]['refund_amount'] = $this->getCommision($this->member_info['member_name'], $value['member_name']);
            }

            Tpl::output('show_page', $model_invite->showpage());
        }

        //信息输出
        self::profile_menu('third');
        
        Tpl::output('invite_list', $invite_list);
        Tpl::showpage('member_invite.list');
    }

    /**
     * 获取邀请关系
     * @param  integer $deep 深度
     * @return array         关系表
     */
    private function getInviteRelation($member_id, $deep = 3, $fields = '*') {
        static $relation_list =array();
        for ($i = 0; $i < $deep; $i++) { 
            if ($i == 0) {
                $relation = $this->_getInviteRelation($member_id, $fields);
                for ($j = 0; $j < count($relation); $j++) { 
                    $relation_list[$i][] = $relation[$j];
                }
            } else {
                $k = $i - 1;
                $member_list = $relation_list[$k];
				if($member_list){
                foreach ($member_list as $key => $value) {
                    $relation = $this->_getInviteRelation($value['member_id'], $fields);
                    for ($j = 0; $j < count($relation); $j++) { 
                        $relation_list[$i][] = $relation[$j];
                    }
                }
				}
            }
        }

        return $relation_list;
    }

    private function _getInviteRelation($member_id, $fields = '*') {
        $condition = array();
        $condition['inviter_id'] = $member_id;
        $model_member = Model('member');
        $result = $model_member->getMemberList($condition, $fields);
        return $result;
    }

    /**
     * 用户中心右边，小导航
     *
     * @param string    $menu_type  导航类型
     * @param string    $menu_key   当前导航的menu_key
     * @return
     */
    private function profile_menu($menu_key = ''){
        $menu_array = array(
            array('menu_key'=>'first', 'menu_name'=>'一级推广', 'menu_url'=>'index.php?con=member_invite&fun=first'),
            array('menu_key'=>'second', 'menu_name'=>'二级推广', 'menu_url'=>'index.php?con=member_invite&fun=second'),
            array('menu_key'=>'third', 'menu_name'=>'三级推广', 'menu_url'=>'index.php?con=member_invite&fun=third')
        );

        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }

    //获取推广用户购买次数
    private function getBuyCountByBuyerId($buyer_id) {
        $condition = array();
        $condition['buyer_id'] = $buyer_id;
        $condition['order_state'] = ORDER_STATE_SUCCESS;
        $result = Model('orders')->table('orders')->where($condition)->count();

        return $result ? $result : 0;
    }

    //获取推广用户的佣金
    private function getCommision($prev_member_name, $next_member_name) {
        $condition = array();
        $condition['username'] = $prev_member_name;
        $condition['memo'] = array('like', '交易人' . $next_member_name .'%');
        $model_mingxi = Model('mingxi');
        $result = $model_mingxi->where($condition)->sum('je');

        return $result ? ncPriceFormat($result) : ncPriceFormat(0);
    }
}