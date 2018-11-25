<?php
/**
 * wap城市类
 *
 */


use shopec\ Tpl;


defined('Inshopec') or exit('Access Invalid!');
class cityControl extends mobileHomeControl {
    public function __construct() {
        parent::__construct();
    }
    /**
     * 首页
     */
    public function indexOp() {
        $model_area = Model('area');
        $condition['area_deep'] = '2';
        $list = $model_area -> getList($condition, 'area_deep,area_sort asc');
        $condition = array('hot_area' => 1,'area_deep' => array('EGT', '2'));
        $hot_city = $model_area -> getList($condition, 'area_deep,area_sort asc');
        $letterArr = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'K', 'L', 'M', 'N', 'P', 'Q', 'R', 'S', 'T', 'W', 'X', 'Y', 'Z');
        if ($_COOKIE['area']) {
            $his_search_list = unserialize($_COOKIE['area']);
        }
        if (!$his_search_list || !is_array($his_search_list)) {
            $his_search_list = array();
        }
        if (!empty($list)) {
            output_data(array('list' => $list, 'letter' => $letterArr, 'hot' => $hot_city, 'his_city' => $his_search_list));
        } else {
            output_data(array('error' => '获取城市列表失败'));
        }
    }
    /**
     * 获取默认城市
     */
    public function defaultOp() {
        $setting = Model('setting');
        $list = $setting -> getRowSetting('default_area');
        if (!empty($list)) {
            output_data(array('list' => unserialize($list['value'])));
        } else {
            output_data(array('error' => '获取默认城市失败'));
        }


    }
    /**
     * 最近访问城市
     */
    public function addieOp() {
        $city_id = intval($_POST['area_id']);
        $city_name = intval($_POST['area_name']);
    	if(!empty($city_id)){
    		$condition['area_id']= $city_id;
    	}
    	if(!empty($city_name)){
    		$condition['area_name']= array('like', '%' . $city_name . '%');
    	}          
        $area_model = Model('area');

        $area = $area_model -> getAreaInfo($condition);
        
        if (!$area || !is_array($area)) {
           output_data(array('error' => '参数错误'));
        }
        //添加最近访问
        $key='area'; 
        $this->cookie_area($key,$area);
        if (!empty($area)) {
            output_data(array('list' => $area));
        } else {
            output_data(array('error' => '该城市不存在'));
        }	
    }

    /**
     * 存入cookie
     * $name cookie key名称
     * $area 存入数据
     */
    function cookie_area($name,$area){
    	//判断cookie类里面是否有浏览记录
        if ($_COOKIE[$name]) {
            $history = unserialize($_COOKIE[$name]);
            array_unshift($history, $area); //在浏览记录顶部加入
            /* 去除重复记录 */
            $rows = array();
            foreach($history as $v) {
                    if (in_array($v, $rows)) {
                        continue;
                    }
                    $rows[] = $v;
            }
                /* 如果记录数量多余5则去除 */
            while (count($rows) > 8) {
                array_pop($rows); //弹出
            }
            setcookie($name, serialize($rows), time() + 3600 * 24 * 30, '/');
        } else {
            $history = serialize(array($area));
            setcookie($name, $history, time() + 3600 * 24 * 30, '/');
        }
    }
}    