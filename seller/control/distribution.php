<?php

/**

 * 我的佣金 20160906

 */

use shopec\Tpl;
defined('Inshopec') or exit('Access Invalid!');

class distributionControl extends mobileMemberControl {
	public function __construct() {

		parent::__construct();

	}

	public function indexOp() {
		if (!empty($_GET['data-level'])) {
			Tpl::output('web_seo', C('site_name') . ' - ' . '分销中心');
			Tpl::showpage('distributionlist');
		} else {
			Tpl::output('web_seo', C('site_name') . ' - ' . '分销中心');
			Tpl::showpage('distribution');
		}

	}

	/**
	 * 获取分销佣金
	 */
	public function get_commisionOp() {
		$model_pd = Model('pd_log');

		$condition = array();
		$condition['lg_member_id'] = $this -> member_info['member_id'];
		$condition['lg_type'] = 'add_commision';
       
		$commision_amount = $model_pd -> where($condition) -> sum('lg_av_amount');
		
		$commision_list = $model_pd -> where($condition) -> select();
		foreach ($commision_list as $key => $value) {
			$commision_list[$key]['lg_add_time_text'] = date('Y-m-d H:i:s', $value['lg_add_time']);
		}

		$page_count = $model_pd -> gettotalpage();

		output_data(array('commision_list' => $commision_list, 'commision_amount' => $commision_amount), mobile_page($page_count));
	}

	/**
	 * 获取分销列表
	 */
	public function get_listOp() {
		$level_array = array('first', 'second', 'third');
		$level = $_POST['level'] ? trim($_POST['level']) : 'first';

		if (in_array($level, $level_array)) {
			$level = 'level_' . $level;
			switch ($level) {
				case 'level_first' :
					$this -> level_firstOp();
					break;

				case 'level_second' :
					$this -> level_secondOp();
					break;

				case 'level_third' :
					$this -> level_thirdOp();
					break;

				default :
					# code...
					break;
			}
		}
	}

	//一级推广
	public function level_firstOp() {
		$model_member = Model('member');
		$page = $_POST['page'];
		$condition = array();
		$condition['inviter_id'] = $this -> member_info['member_id'];
		$invite_list = $model_member -> getMemberList($condition, '*', $page);
		if (is_array($invite_list) && !empty($invite_list)) {
			//计算用户的累计返利金额
			foreach ($invite_list as $key => $value) {
				$invite_list[$key]['buy_count'] = $this -> getBuyCountByBuyerId($value['member_id']);
				$invite_list[$key]['refund_amount'] = $this -> getCommision($this -> member_info['member_name'], $value['member_name']);
			}
		}

		$page_count = $model_member -> gettotalpage();

		output_data(array('invite_list' => $invite_list, 'title' => '一级推广'), mobile_page($page_count));
	}

	//二级推广
	public function level_secondOp() {
		$page = $_POST['page'];
		$invite_relation_list = $this -> getInviteRelation($this -> member_info['member_id'], 2, 'member_id');
		$invite_list = $invite_relation_list[1];

		if (is_array($invite_list) && !empty($invite_list)) {
			foreach ($invite_list as $key => $value) {
				$member_id_array[] = $value['member_id'];
			}
			$member_id_str = implode(',', $member_id_array);
			$model_member = Model('member');
			$condition = array();
			$condition['member_id'] = array('in', $member_id_str);
			$invite_list = $model_member -> getMemberList($condition, '*', $page);

			//计算用户的累计返利金额
			foreach ($invite_list as $key => $value) {
				$invite_list[$key]['buy_count'] = $this -> getBuyCountByBuyerId($value['member_id']);
				$invite_list[$key]['refund_amount'] = $this -> getCommision($this -> member_info['member_name'], $value['member_name']);
			}

			$page_count = $model_member -> gettotalpage();

		} else {
			$invite_list = array();
			$page_count = array();
		}

		output_data(array('invite_list' => $invite_list, 'title' => '二级推广'), mobile_page($page_count));
	}

	//三级推广
	public function level_thirdOp() {
		$page = $_POST['page'];
		$invite_relation_list = $this -> getInviteRelation($this -> member_info['member_id'], 3, 'member_id');
		$invite_list = $invite_relation_list[2];

		if (is_array($invite_list) && !empty($invite_list)) {
			foreach ($invite_list as $key => $value) {
				$member_id_array[] = $value['member_id'];
			}
			$member_id_str = implode(',', $member_id_array);
			$model_member = Model('member');
			$condition = array();
			$condition['member_id'] = array('in', $member_id_str);
			$invite_list = $model_member -> getMemberList($condition, '*', $page);

			//计算用户的累计返利金额
			foreach ($invite_list as $key => $value) {
				$invite_list[$key]['buy_count'] = $this -> getBuyCountByBuyerId($value['member_id']);
				$invite_list[$key]['refund_amount'] = $this -> getCommision($this -> member_info['member_name'], $value['member_name']);
			}
			$page_count = $model_member -> gettotalpage();

		} else {
			$invite_list = array();
			$page_count = array();
		}
		output_data(array('invite_list' => $invite_list, 'title' => '三级推广'), mobile_page($page_count));
	}

	//获取推广用户购买次数
	private function getBuyCountByBuyerId($buyer_id) {
		$condition = array();
		$condition['buyer_id'] = $buyer_id;
		$condition['order_state'] = ORDER_STATE_SUCCESS;
		$result = Model() -> table('orders') -> where($condition) -> count();

		return $result ? $result : 0;
	}

	//获取推广用户的佣金
	private function getCommision($prev_member_name, $next_member_name) {
		$condition = array();
		$condition['username'] = $prev_member_name;
		$condition['memo'] = array('like', '交易人' . $next_member_name . '%');
		$model_mingxi = Model('mingxi');
		$result = $model_mingxi -> where($condition) -> sum('je');
		return $result ? ncPriceFormat($result) : ncPriceFormat(0);
	}

	/**
	 * 获取邀请关系
	 * @param  integer $deep 深度
	 * @return array         关系表
	 */
	private function getInviteRelation($member_id, $deep = 3, $fields = '*') {
		static $relation_list = array();
		for ($i = 0; $i < $deep; $i++) {
			if ($i == 0) {
				$relation = $this -> _getInviteRelation($member_id, $fields);
				for ($j = 0; $j < count($relation); $j++) {
					$relation_list[$i][] = $relation[$j];
				}
			} else {
				$k = $i - 1;
				$member_list = $relation_list[$k];

				foreach ($member_list as $key => $value) {
					$relation = $this -> _getInviteRelation($value['member_id'], $fields);
					for ($j = 0; $j < count($relation); $j++) {
						$relation_list[$i][] = $relation[$j];
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
		$result = $model_member -> getMemberList($condition, $fields);
		return $result;
	}

}
