<?php
/**
 * 多城市控制类
 */
use shopec\Tpl;

defined('Inshopec') or exit('Access Invalid!');

class cityControl extends BaseHomeControl {
    public function __construct() {
        parent::__construct();
    }
	/**
	 * 选择城市首页
	 */
	public function cityOp(){
		$model_area = Model('area');
		$condition['area_deep'] = '2';
		$list	= $model_area->getList($condition,'area_deep,area_sort asc');
		$pinnew = new PinYin();
		$area= Model('area');
        foreach ($list as $key => $value) {
        	$rr = $pinnew->encode($value['area_name']);
        	$ee['area_id'] = $value['area_id'];
        	$rrr['first_letter'] = substr(strtoupper($rr),0,1);
        	$area->editArea($rrr,$ee);
        }
        
        
		Tpl::output('list',$list);
		$letterArr	=	array('A','B','C','D','E','F','G','H','J','K','L','M','N','P','Q','R','S','T','W','X','Y','Z');

		Tpl::output('letter',$letterArr);
		Tpl::showpage('city.select');
	}

	/**
	 * 选择城市
	 */
	public function select_cityOp(){
		if(intval($_GET['city_id'])){
			$area_id = intval($_GET['city_id']);
		}elseif(intval($_GET['parent_id'])){
			$area_id = intval($_GET['parent_id']);
		}
		
		$model_area = Model('area');
		$area_info = $model_area->getAreaInfo(array('area_id'=>$area_id));
		
		if(empty($area_info)){
			showMessage('选择的城市不存在');
		}
		
		$area_str = serialize($area_info);

		setcookie('city',$area_str,time()+3600*24*30);
		header('Location:'.SHOP_SITE_URL);
		exit;
	}	   	
}