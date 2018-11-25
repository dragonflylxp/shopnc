<?php
/**
 * 管理人模型
 *
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net
 * @link       http://www.shopec.net
 * @since      File available since Release v1.1
 */
defined('Inshopec') or exit('Access Invalid!');

class manager_memberModel extends Model {
	/*
	 * 获取管理人申请条数
	 * */
	public function getManagerApplyCount(){
		$condition['apply_state'] = "20";
		return $this->table('manager')->where($condition)->count();
	}


	/*
	 * 修改管理人信息
	 *  */
	public function editManager($update,$condition){
		$result = $this->table('manager')->where($condition)->update($update);
		return $result;
	}
	public function editPs($update,$condition){
		$model = Model('sub_account');
		$result = $model->prefix('')->table('payment_sub_account')->where($condition)->update($update);
		return $result;
	}

	/*
	 * 获取管理人信息
	 * */
	public function getManagerInfo($condition=array(),$fields = 'manager.*,member.member_id,member.member_name,member.member_truename,member.member_email'){
		$on = "manager.member_id=member.member_id";
		$list =  $this->table('manager,member')->join('inner')->on($on)->field($fields)->where($condition)->find();
		return $list;
	}

	/*
	 * 添加管理人
	 * */
	public function addManager($insert='',$member_id){
		$result = $this->table('manager')->insert($insert);
		$cinsert['manager_id'] = $member_id;
		$this->table('manager_common')->insert($cinsert);
		return $result;
	}

	/*
	 * 判断管理人账号是否存在
	 *
	 * */
	public function IsAddManager($condition=''){
		$result = $this->table('manager')->where($condition)->select();
		if(count($result)){
			return true;
		}
		return false;
	}

	/*
	 * 获取管理人列表
	 * */
	public function getManagerList($condition = array(), $fields = 'manager.*,member.member_id,member.member_name,member.member_truename,member.member_time', $pagesize = null, $order = '', $limit = null){
		$on = "manager.member_id=member.member_id";
		$list =  $this->table('manager,member')->join('inner')->on($on)->where($condition)->field($fields)->order($order)->page($pagesize)->limit($limit)->select();
		return $list;
	}


	/*
	 * 获取地区管理人用户名
	 * */
	public function getManagerName($condition='') {
		$list =  $this->table('member')->where($condition)->find();
		if($list){
			return $list['member_name'];
		}
	}

	/*
	 * 获取地区管理人列表数量
	 *  */
	public function getManagerMemberCount($condition='') {
		return $this->table('manager_member')->where($condition)->count();
	}
	/* 
	 * 获取地区管理人列表
	 *  */
	public function getManagerMemberList($condition = array(), $fields = '*', $pagesize = null, $order = '', $limit = null){
		$on = "manager_member.uid=manager.member_id,manager.member_id=member.member_id";
		$list =  $this->table('manager_member,manager,member')->join('inner')->on($on)->where($condition)->field($fields)->order($order)->page($pagesize)->limit($limit)->select();
		return $list;
	}
	
	/* 
	 * 获取管理人信息
	 * 
	 *  */
	public function getManagerMemberInfo($condition=array()){
		$list =  $this->table('manager_member')->where($condition)->find();
		return $list;
	}
	
	/* 
	 * 根据地区ID获取地区名
	 *  */
	public function getAreaName($condition = array()){
		$list =  $this->table('area')->where($condition)->find();
		if($list){
			return $list['area_name'];
		}
	}
	
	/*
	 * 获取可绑定地区的管理人
	 *   */
	public function getIsManagerMemberList($condition = array(), $fields = '*', $pagesize = null, $order = '', $limit = null){
		$on = "member.member_id=manager.member_id";
        $list =  $this->table('manager,member')->join('inner')->on($on)->where($condition)->field($fields)->order($order)->page($pagesize)->limit($limit)->select();
        return $list;
	}
	
	/*
	 * 获取下级地区(省、市)
	 *   */
	public function getAreaChildren($condition = array(), $fields = '*', $pagesize = null, $order = '', $limit = null){
		$list =  $this->table('area')->where($condition)->field($fields)->order($order)->page($pagesize)->limit($limit)->select();
		return $list;
	}
	
	/*
	 * 获取下级地区(大区)
	*   */
	public function getRegionChildren($condition = array(), $fields = '*', $pagesize = null, $order = '', $limit = null){
		$list =  $this->table('area')->where($condition)->field($fields)->order($order)->page($pagesize)->limit($limit)->select();
		return $list;
	}
	
	/* 
	 * 管理员绑定地区
	 *  */
	public function addManagerMember($insert) {
		$result = $this->table('manager_member')->insert($insert);
		return $result;
	}
	
	/* 
	 * 修改管理员
	 *  */
	public function editManagerMember($update,$condition){
		$result = $this->table('manager_member')->where($condition)->update($update);
		return $result;
	}
	
	/*  
	 * 删除地区管理员
	 * */
	public function deleteManagerMember($condition){
		$result = $this->table('manager_member')->where($condition)->delete();
		return $result;
	}

	/*
 	* 删除管理员
 	* */
	public function deleteManager($condition,$mcondition){
		$result = $this->table('manager')->where($condition)->delete();
		$this->table('manager_common')->where($condition)->delete();
		$this->table('member')->where($mcondition)->delete();
		$this->table('sns_albumclass')->where($mcondition)->delete();
		$model_graph   = Model('member_graph');
		$model_graph->prefix('')->table('secs_member_graph')->where($mcondition)->delete();
		$model = Model('sub_account');
		$scondition['USER_ID'] = $mcondition['member_id'];
		$model->prefix('')->table('payment_sub_account')->where($scondition)->delete();
		return $result;
	}

	
	/* 
	 * 检查该地区是否已绑定管理员
	 *  */
	public function checkAreaManagerMember($condition){
		$result = $this->table('manager_member')->where($condition)->select();
		if(count($result)){
			return true;
		}
		return false;
	}
	public function checkAreaChildManagerMember($condition){
		$result = $this->table('manager_member')->where($condition)->find();
		if($result['district']){
			return true;
		}
		return false;
	}

	/*
	 * 检查该管理是否已绑定其他地区
	 *   */
	public function checkIsManagerMember($condition){
		$result = $this->table('manager_member')->where($condition)->select();
		if(count($result)){
			return true;
		}
		return false;
		
	}
	/**
	 * 注册管理员
	 *
	 * @param   array $param 管理员信息
	 * @return  array 数组格式的返回结果
	 */
	public function addMember($param) {
		$model_member = Model('member');
		if(empty($param)) {
			return false;
		}
		try {
			$this->beginTransaction();
			$member_info    = array();
			$member_info['member_id']           = $param['member_id'];
			$member_info['member_name']         = $param['member_name'];
			$member_info['member_passwd']       = md5(trim($param['member_passwd']));
			$member_info['member_email']        = $param['member_email'];
			$member_info['member_time']         = TIMESTAMP;
			$member_info['member_login_time']   = TIMESTAMP;
			$member_info['member_old_login_time'] = TIMESTAMP;
			$member_info['member_login_ip']     = getIp();
			$member_info['member_old_login_ip'] = $member_info['member_login_ip'];
			$member_info['r_code'] = $param['referral_code'];
			$member_info['code'] = $this->getCode();
			$member_info['member_type']     = $param['member_type'];
			$member_info['member_truename']     = $param['member_truename'];
			$member_info['member_qq']           = $param['member_qq'];
			$member_info['member_sex']          = $param['member_sex'];
			$member_info['member_avatar']       = $param['member_avatar'];
			$member_info['member_qqopenid']     = $param['member_qqopenid'];
			$member_info['member_qqinfo']       = $param['member_qqinfo'];
			$member_info['member_sinaopenid']   = $param['member_sinaopenid'];
			$member_info['member_sinainfo'] = $param['member_sinainfo'];
			if ($param['member_mobile_bind']) {
				$member_info['member_mobile'] = $param['member_mobile'];
				$member_info['member_mobile_bind'] = $param['member_mobile_bind'];
			}
			if ($param['weixin_unionid']) {
				$member_info['weixin_unionid'] = $param['weixin_unionid'];
				$member_info['weixin_info'] = $param['weixin_info'];
			}
			$insert_id  = $model_member->table('member')->insert($member_info);
			if (!$insert_id) {
				throw new Exception();
			}

			$sub_account = array();
			$sub_account['USER_ID'] = $insert_id;
			$sub_account['SUB_ACCOUNT_TYPE'] = '0001';
			$sub_account['AMOUNT'] = 0;
			$sub_account['CASH_AMOUNT'] = 0;
			$sub_account['UN_CASH_AMOUNT'] = 0;
			$sub_account['SHARED_AMOUNT'] = 0;
			$sub_account['FREEZE_CASH_AMOUNT'] = 0;
			$sub_account['FREEZE_UN_CASH_AMOUNT'] = 0;
			$sub_account['PROPERTY'] = '0';
			$sub_account['STATE'] = '00';
			$sysdate = date("Y-m-d H:i:s",time());
			$sub_account['CREATE_TIME'] = $sysdate;
			$sub_account['LAST_UPDATE_TIME'] = $sysdate;
			$sub_account['USER_NAME'] = $param['member_truename'];

			$preStr = 'amount=0&cashAmount=0&freezeCashAmount=0&freezeUnCashAmount=0&sharedAmount=0&subAccountType=0001&unCashAmount=0&userId='.$insert_id;
			$checkCode = md5(trim($preStr));
			$sub_account['CHECK_CODE'] = $checkCode;

			$insert = $model_member->addPaymentSubAccount($sub_account);
			if (!$insert) {
				throw new Exception();
			}
			$insert = $model_member->addMemberGraph($insert_id,$param['member_name'],$param['referral_code']);
			if (!$insert) {
				throw new Exception();
			}

			// 添加默认相册
			$insert = array();
			$insert['ac_name']      = '买家秀';
			$insert['member_id']    = $insert_id;
			$insert['ac_des']       = '买家秀默认相册';
			$insert['ac_sort']      = 1;
			$insert['is_default']   = 1;
			$insert['upload_time']  = TIMESTAMP;
			$rs = $model_member->table('sns_albumclass')->insert($insert);
			$this->commit();
			return $insert_id;
		} catch (Exception $e) {
			$this->rollback();
			return false;
		}
	}

	private function getCode(){
		$randStr = str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890');
		$rand = substr($randStr,0,6);
		$count = $this->table('member')->where(array('code'=>$rand))->master(false)->count();
		if($count!=0){
			return getCode();
		}
		return $rand;
	}
	
}
