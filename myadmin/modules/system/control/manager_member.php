<?php
/**
 * 管理人管理
 *
 *
 *
 *
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net
 * @link       http://www.shopec.net
 * @since      File available since Release v1.1
 */

use shopec\Tpl;

defined('Inshopec') or exit('Access Invalid!');
class manager_memberControl extends SystemControl{
    const EXPORT_SIZE = 1000;
    public function __construct(){
        parent::__construct();
    }

    public function indexOp() {
        $this->manager_memberOp();
    }

    /**
     * 地区管理员列表
     */
    public function manager_memberOp(){
        Tpl::showpage('manager_member.index');
    }

    /**
     * 输出XML数据
     */
    public function get_xmlOp() {
        $manager_model = Model('manager_member');
        // 设置页码参数名称
        $condition = array();
        if ($_POST['query'] != '') {
			if($_POST['query'] == 'uid'){
            	$condition[$_POST['qtype']] = $_POST['query'];
			}else{
				$condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
			}
        }
        $order = '';
        $param = array('mid', 'uid', 'area', 'province', 'city', 'district', 'point');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
                $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }

        $page = $_POST['rp'];
    	$manager_list = $manager_model->getManagerMemberList($condition,'manager.*,manager_member.*,member.member_id,member_name',$_POST['rp'],$order);
    	$data = array();
    	$data['now_page'] = $manager_model->shownowpage();
    	$data['total_num'] = $manager_model->gettotalnum();
    	foreach ($manager_list as $manager_info) {
    		$list = array();
    		$condition = array();
    		$condition['area_id'] = $manager_info['province'];
    		$province = $manager_model->getAreaName($condition);
    		$condition['area_id'] = $manager_info['city'];
    		$city =  $manager_model->getAreaName($condition);
    		$condition['area_id'] = $manager_info['district'];
    		$district = $manager_model->getAreaName($condition);
    		$region = $manager_info['area']." ".$province." ".$city." ".$district;
    		$list['operation'] = "<a class=\"btn orange\" href=\"index.php?con=manager_member&fun=manager_member_edit&mid={$manager_info['mid']}\"><i class=\"fa fa-gavel\"></i>编辑</a>".
    		"<a class='btn red' href='javascript:void(0);' onclick=\"fg_del(".$manager_info['mid'].")\"><i class='fa fa-trash-o'></i>删除</a>";
    		$list['mid'] = $manager_info['mid'];
    		$list['uid'] = $manager_info['uid'];
			$list['member_name'] = $manager_info['member_name'];
    		$list['company_name'] = $manager_info['company_name'];
    		$list['grade'] = managerGrade($manager_info['grade']);
    		$list['area'] = $region;
    		$list['point'] = $manager_info['point']."%";
			$list['add_time'] = date('Y-m-d H:i:s',$manager_info['add_time']);
			$list['update_time'] = $manager_info['update_time']?date('Y-m-d H:i:s',$manager_info['update_time']):"";
    		$data['list'][$manager_info['mid']] = $list;
    	}
    	exit(Tpl::flexigridXML($data));
    }

    /**
     * 绑定管理人
     */
    public function manager_member_addOp(){
        $lang   = Language::getLangContent();
        $manager_model = Model('manager_member');
        if (chksubmit()){
	   		$area = $_REQUEST['area']?$_REQUEST['area']:"";
    		$province = $_REQUEST['province']?$_REQUEST['province']:0;
    		$city = $_REQUEST['city']?$_REQUEST['city']:0;
    		$district = $_REQUEST['district']?$_REQUEST['district']:0;
    		$uid = $_REQUEST['uid']?$_REQUEST['uid']:0;
    		$point = $_REQUEST['point']?$_REQUEST['point']:0;
    		$grade = $_REQUEST['grade']?$_REQUEST['grade']:0;
    		
    		//判断该会员是否已绑定
    		$condition['uid'] = $uid;
    		if($manager_model->checkIsManagerMember($condition)){
    			showMessage('该管理人已绑定其他地区','','html','error');
    		}
    		//判断节点是否绑定
    		$condition = array();
            if($grade==1){
    			$condition['area'] = $area;
    			$condition['province'] = 0;
    			$condition['city'] = 0;
    			$condition['district'] = 0;
				if($manager_model->checkAreaManagerMember($condition)){
					showMessage('该地区已绑定其他管理人','','html','error');
				}
    		}else if($grade==2){
				$condition['area'] = $area;
				$condition['province'] = $province;
				$condition['city'] = 0;
				$condition['district'] = 0;
				if($manager_model->checkAreaManagerMember($condition)){
					showMessage('该地区已绑定其他管理人','','html','error');
				}
    		}else if($grade==3){
				$condition['area'] = $area;
				$condition['province'] = $province;
				$condition['city'] = $city;
				if($manager_model->checkAreaChildManagerMember($condition)){
					showMessage('该市下级地区已绑定管理人','','html','error');
				}
				$condition['district'] = 0;
				if($manager_model->checkAreaManagerMember($condition)){
					showMessage('该地区已绑定其他管理人','','html','error');
				}
    		}else if($grade==4){
				$condition['area'] = $area;
				$condition['province'] = $province;
				$condition['city'] = $city;
				$condition['district'] = 0;
				if($manager_model->checkAreaManagerMember($condition)){
					showMessage('该上级地区已绑定其他管理人','','html','error');
				}
				$condition['district'] = $district;
				if($manager_model->checkAreaManagerMember($condition)){
					showMessage('该地区已绑定其他管理人','','html','error');
				}
    		}
    		
    		$insert['uid'] = $uid;
    		$insert['area'] = $area;
    		$insert['grade'] = $grade;
    		$insert['point'] = $point;
    		$insert['province'] = 0;
    		$insert['city'] = 0;
    		$insert['district'] = 0;
    		if($grade==2){
    			$insert['province'] = $province;
    		}else if($grade>2){
    			$insert['province'] = $province;
    			$insert['city'] = $city;
    			$insert['district'] = $district;
    		}
    		$insert['add_time'] = TIMESTAMP;
    		$result = $manager_model->addManagerMember($insert);
    		if($result){
    			$condition = array();
    			$condition['area_id'] = $province;
    			$province_name = $manager_model->getAreaName($condition);
    			$condition['area_id'] = $city;
    			$city_name =  $manager_model->getAreaName($condition);
    			$condition['area_id'] = $district;
    			$district_name = $manager_model->getAreaName($condition);
    			$region = $area.$province_name.$city_name.$district_name;
				$condition = array();
				$condition['member_id'] = $uid;
				$manager_account = $manager_model->getManagerName($condition);
    			$this->log("绑定".$region."管理人为".$manager_account);
    			showMessage("绑定管理人成功",'index.php?con=manager_member&fun=manager_member');
    		}else{
    			showMessage("绑定管理人失败",'index.php?con=manager_member&fun=manager_member');
    		}
        }
		$condition['member_type'] = 1;
 		$manager_list = $manager_model->getIsManagerMemberList($condition);
 		Tpl::output('manager_list',$manager_list);
        Tpl::showpage('manager_member.add');
    }
	/*
	 * ajax获取管理人
	 * */
	public function ajaxGetManagerOp(){
		$manager_model = Model('manager_member');
		$keyword = $_REQUEST['keyword']?$_REQUEST['keyword']:"";
		$condition['company_name'] = array('like', '%' .$keyword. '%');
		$manager_list = $manager_model->getIsManagerMemberList($condition);
		$str = "";
		foreach ($manager_list as $k=>$v){
			$str .="<option value='{$v[member_id]}'>{$v['company_name']}</option>";
		}
		echo json_encode($str);
		return;
	}

	/*
	 * ajax判断是否绑定管理人地区
	 * */
	public function ajaxIsBindAreaOp(){
		$manager_model = Model('manager_member');
		$uid = $_REQUEST['uid']?$_REQUEST['uid']:0;
		$condition['uid'] = $uid;
		if($manager_model->checkIsManagerMember($condition)){
			echo json_encode(1);
		}
		return;
	}

    /* 
     * ajax获取地区
     *  
     *  */
    public function ajaxGetAreaOp(){
	   	$area_region = $_REQUEST['area_region']?$_REQUEST['area_region']:"";
    	$province = $_REQUEST['province']?$_REQUEST['province']:0;
    	$city = $_REQUEST['city']?$_REQUEST['city']:0;
    	$manager_model = Model('manager_member');
    	if($city){
    		$conditon['area_parent_id'] = $city;
    		$district_area = $manager_model->getAreaChildren($conditon);
			$str = "<option value='0'>请选择</option>";
    		foreach ($district_area as $k=>$v){
				$str .="<option value='{$v[area_id]}'>{$v['area_name']}</option>";		               
    		}
    		echo json_encode($str);
    		return;
    	}
    	if($province){
    		$conditon['area_parent_id'] = $province;
    		$city_area = $manager_model->getAreaChildren($conditon);
			$str = "<option value='0'>请选择</option>";
    		foreach ($city_area as $k=>$v){
				$str .="<option value='{$v[area_id]}'>{$v['area_name']}</option>";		               
    		}
    		echo json_encode($str);
    		return;
    	}
    	if($area_region){
    		$conditon['area_region'] = $area_region;
    		$provine_area = $manager_model->getRegionChildren($conditon);
    		$str = "<option value='0'>请选择</option>";
    		foreach ($provine_area as $k=>$v){
    			$str .="<option value='{$v[area_id]}'>{$v['area_name']}</option>";
    		}
    		echo json_encode($str);
    		return;
    	}
    }
    
    
    /**
     * 管理人编辑
     */
    public function manager_member_editOp(){
        $lang   = Language::getLangContent();
        $manager_model = Model('manager_member');
        //获取管理人信息
        $condition = array();
        $condition['mid'] = $_REQUEST['mid']?$_REQUEST['mid']:0;
        $manager_info = $manager_model->getManagerMemberInfo($condition);
        if (chksubmit()){
	   		$area = $_REQUEST['area']?$_REQUEST['area']:"";
    		$province = $_REQUEST['province']?$_REQUEST['province']:0;
    		$city = $_REQUEST['city']?$_REQUEST['city']:0;
    		$district = $_REQUEST['district']?$_REQUEST['district']:0;
    		$uid = $_REQUEST['uid']?$_REQUEST['uid']:$manager_info['uid'];
    		$point = $_REQUEST['point']?$_REQUEST['point']:$manager_info['point'];
    		$grade = $_REQUEST['grade']?$_REQUEST['grade']:$manager_info['grade'];
    		
    		//判断节点是否绑定
    		$condition = array();
    		$condition['mid'] =  $_REQUEST['mid']?$_REQUEST['mid']:0;
    		$update['uid'] = $uid;
    		$update['grade'] = $grade;
    		$update['point'] = $point;
    		if($area){
	    		$update['area'] = $area?$area:$manager_info['area'];
	    		$update['province'] = 0;
	    		$update['city'] = 0;
	    		$update['district'] = 0;
	    		if($grade==2){
	    			$update['province'] = $province?$province:$manager_info['province'];
	    		}else if($grade>2){
	    			$update['province'] = $province?$province:$manager_info['province'];
	    			$update['city'] = $city?$city:$manager_info['city'];
	    			$update['district'] = $district?$district:$manager_info['district'];
	    		}
    		}
    		$update['update_time'] = TIMESTAMP;
    		$result = $manager_model->editManagerMember($update,$condition);
    		if($result){
    			$condition = array();
    			$condition['area_id'] = $manager_info['province'];
    			$province_name = $manager_model->getAreaName($condition);
    			$condition['area_id'] = $manager_info['city'];
    			$city_name =  $manager_model->getAreaName($condition);
    			$condition['area_id'] = $manager_info['district'];
    			$district_name = $manager_model->getAreaName($condition);
    			$region = $manager_info['area'].$province_name.$city_name.$district_name;
				$condition = array();
				$condition['member_id'] = $uid;
				$manager_account = $manager_model->getManagerName($condition);
    			$this->log("修改".$region."管理人为".$manager_account);
    			showMessage("修改管理员成功",'index.php?con=manager_member&fun=manager_member');
    		}else{
    			showMessage("修改管理员失败",'index.php?con=manager_member&fun=manager_member');
    		}
        }
        $condition = array();
		$condition['member_type'] = 1;
 		$manager_list = $manager_model->getIsManagerMemberList($condition);
 		$condition = array();
 		$condition['area_id'] = $manager_info['province'];
 		$province = $manager_model->getAreaName($condition);
 		$condition['area_id'] = $manager_info['city'];
 		$city =  $manager_model->getAreaName($condition);
 		$condition['area_id'] = $manager_info['district'];
 		$district = $manager_model->getAreaName($condition);
 		$manager_info['region'] = $manager_info['area']." ".$province." ".$city." ".$district;
 		Tpl::output('manager_list',$manager_list);
 		Tpl::output('manager_info',$manager_info);
        Tpl::showpage('manager_member.edit');
    }
    
    //删除管理人
    public function manager_member_deleteOp(){
    	$lang   = Language::getLangContent();
    	$manager_model = Model('manager_member');
    	//获取管理人信息
    	$condition = array();
    	$condition['mid'] = $_REQUEST['mid']?$_REQUEST['mid']:0;
    	$manager_info = $manager_model->getManagerMemberInfo($condition);
    	$result = $manager_model->deleteManagerMember($condition);
    	if($result){
    		$condition = array();
    		$condition['area_id'] = $manager_info['province'];
    		$province_name = $manager_model->getAreaName($condition);
    		$condition['area_id'] = $manager_info['city'];
    		$city_name =  $manager_model->getAreaName($condition);
    		$condition['area_id'] = $manager_info['district'];
    		$district_name = $manager_model->getAreaName($condition);
    		$region = $manager_info['area'].$province_name.$city_name.$district_name;
			$condition = array();
			$condition['member_id'] = $manager_info['uid'];
			$manager_account = $manager_model->getManagerName($condition);
    		$this->log("解除".$region."管理人".$manager_account);
    		showMessage("解除管理员绑定地区成功");
    	}else{
    		showMessage("解除管理员绑定地区失败");
    	}
    	
    }


}

/**
 * 取得结算文字输出形式
 *
 * @param array $bill_state
 * @return string 描述输出
 */
function managerGrade($grade) {
	return str_replace(
			array('1','2','3','4'),
			array('大区级','省级','市级','县级'),
			$grade);
}
