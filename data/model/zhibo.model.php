<?php
/**
 * 店铺模型管理
 *

 */
use shopec\Tpl;

defined('Inshopec') or exit('Access Invalid!');
class zhiboModel extends Model {

    /**
     * 自营店铺的ID
     *
     * array(
     *   '店铺ID(int)' => '是否绑定了全部商品类目(boolean)',
     *   // ..
     * )
     */
    protected $ownShopIds;

    public function __construct() {
        parent::__construct('zhibo');
    }
	//获取店铺直播设置
	public function zb_listop($id) {
		 $condition['type']=array('in','1,2,3');
		 $order="id ASC";		 
         $zblist= $this->table('zb_config')->field('*')->where($condition)->order($order)->select();
		 return $zblist;
	 }
	//获取店铺直播设置
	public function zb_config($id) {
    	 $condition['id'] = $id; 
		 $order="id ASC";
         $store_zhibo= $this->table('zb_config')->field('*')->where($condition)->find();
		 return $store_zhibo;
	 }
	//店铺直播IP设置
	public function zb_config_updata($sid,$data) {
    	 $condition['sid'] = $sid; 
		 return $this->table('zb_config')->where($condition)->update($data);
	 }
    /**
     * 获取礼物分类
     */
    public function getgtype() {
		 $condition['open'] = "yes";
		 $order="orderid ASC";
         $zbgtype = $this->table('zb_gtype')->field('*')->where($condition)->order($order)->select();
		 return $zbgtype;
	 }
	 public function getgifts(){
		$gtype=$this->getgtype();	
 		foreach($gtype as $val){		
			$condition['tid']=$val['id'];
			$order="id ASC";
			$gifts[$val['id']]= $this->table('zb_gifts')->field('*')->where($condition)->order($order)->select();
		}  
		 return $gifts;
	 }
	 public function getgiftsid($tid) {
		 $condition = array();
    	 $condition['tid'] = $tid;
		 $order="tid ASC";
         $zbgifts = $this->table('zb_gifts')->field('*')->where($condition)->select();
		 return $zbgifts;
	 }
	 //获取单个礼物信息
	 public function getgiftid($id) {
    	 $condition['id'] = $id; 
		 $order="id ASC";
         $gift = $this->table('zb_gifts')->field('*')->where($condition)->select();
		 return $gift[0];
	 }
	public function zbmoneychange($uid,$money,$des){
		$data_log['lg_av_amount'] = -$money;
		$data_log['lg_desc'] = $des;
		$data_pd['available_predeposit'] = array('exp','available_predeposit-'.$money);
		$data_msg['av_amount'] = -$money;
		$data_msg['freeze_amount'] = 0;
		$data_msg['desc'] = $des;
		
		$update = Model('member')->editMember(array('member_id'=>$uid,),$data_pd);
        if (!$update) {
            throw new Exception('操作失败');
        }
        $insert = $this->table('pd_log')->insert($data_log);
        if (!$insert) {
            throw new Exception('操作失败');
        }      
        return $update;		
		}
	public function zbpointchange($uid,$points,$des){
		$insertarr['pl_desc'] = $des;
		$insertarr['pl_points'] =$points;
		$userid=$uid;//用户ID
		$condition['member_id'] = $userid; 
		$user=Model('member')->getMemberInfo($condition);
		//新增日志
		$value_array = array();
		$value_array['pl_memberid'] =$user['member_id'];
		$value_array['pl_membername'] = $user['member_name'];
		$value_array['pl_points'] = $insertarr['pl_points'];
		$value_array['pl_addtime'] = time();
		$value_array['pl_desc'] =$des;
		$value_array['pl_stage'] = "zhibo";
		$result = false;
		if($value_array['pl_points'] != '0'){
			$result = Model('points')->addPointsLog($value_array);
		}
		if ($result){
			//更新member内容
			$upmember_array = array();
			$upmember_array['member_points'] = array('exp','member_points+'.$insertarr['pl_points']);
			Model('member')->editMember(array('member_id'=>$user['member_id']),$upmember_array);
			return true;
		}else {
			return false;
		}
		
		}
      public function addstorezhibo($param){
        return $this->insert($param);	
    }
	
	public function zhiboPointsLog($uidpay,$uidget,$gname,$num,$point){
		$condition['member_id'] = $uidpay;
		$memberpay=Model('member')->getMemberInfo($condition);
		$condition['member_id'] = $uidget;
		$memberget=Model('member')->getMemberInfo($condition);
		//赠送者积分操作
		$insertarr['pl_memberid']=$memberpay['member_id'];
		$insertarr['pl_membername']=$memberpay['member_name'];
		$insertarr['pl_points']="-".$point;
		$insertarr['pl_desc'] = '直播赠送主播【'.$memberget['member_name'].'】礼物消费'.$point.'积分';
		$value_array = array();
		$value_array['pl_memberid'] = $insertarr['pl_memberid'];
		$value_array['pl_membername'] = $insertarr['pl_membername'];
		$value_array['pl_points'] = $insertarr['pl_points'];
		$value_array['pl_addtime'] = time();
		$value_array['pl_desc'] = $insertarr['pl_desc'];
		$value_array['pl_stage'] = "zhibo";
		$result = false;
		
		if($value_array['pl_points'] != '0'){
			$result = Model('points')->addPointsLog($value_array);
		}
		
		if ($result){
			//更新member内容
			$obj_member = Model('member');
			$upmember_array = array();
			$upmember_array['member_points'] = array('exp','member_points-'.$point);
			$obj_member->editMember(array('member_id'=>$insertarr['pl_memberid']),$upmember_array);
			
		}else {
			return false;
		}
		//主播积分操作
		$ticheng=floatval(C('store_zb_yongjin'));
		if($ticheng > 0){
		$point=$point*$ticheng;//佣金
		}
		
		$insertarr['pl_memberid']=$memberget['member_id'];
		$insertarr['pl_membername']=$memberget['member_name'];
		$insertarr['pl_points']=$point;
		$insertarr['pl_desc'] = '直播用户【'.$memberpay['member_name'].'】赠送礼物'.$gname.'x'.$num.'获得'.$point.'积分';
		$value_array = array();
		$value_array['pl_memberid'] = $insertarr['pl_memberid'];
		$value_array['pl_membername'] = $insertarr['pl_membername'];
		$value_array['pl_points'] = $insertarr['pl_points'];
		$value_array['pl_addtime'] = time();
		$value_array['pl_desc'] = $insertarr['pl_desc'];
		$value_array['pl_stage'] = "zhibo";
		$result = false;
		if($value_array['pl_points'] != '0'){
			$result =Model('points')->addPointsLog($value_array);
		}
		if ($result){
			//更新member内容
			$obj_member = Model('member');
			$upmember_array = array();
			$upmember_array['member_points'] = array('exp','member_points+'.$point);
			$obj_member->editMember(array('member_id'=>$insertarr['pl_memberid']),$upmember_array);
			return true;
		}else {
			return false;
		}
	}
}
