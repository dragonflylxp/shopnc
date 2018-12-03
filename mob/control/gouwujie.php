<?php
/**
 * 购物节项目数据 
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
class gouwujieControl extends mobileHomeControl {
    public function __construct() {
        parent::__construct();
    }

    public function infoOp() {
        $categories_map = array(
            #"equipment" => array("gc_id"=>1406, "gc_name"=>"家用电器"),
            "makeup"    => array("gc_id"=>486, "gc_name"=>"护肤化妆"),
            #"baby"      => array("gc_id"=>1403, "gc_name"=>"母婴育儿"),
            #"mobile"    => array("gc_id"=>1407, "gc_name"=>"手机通讯")
            "baby"    => array("gc_id"=>486, "gc_name"=>"母婴育儿")
        );
       
        $list = $this->get_root_class();
        $categorylist = array();
        foreach($list as $k => $class){
	    $categorylist[$k]["gc_id"] = $class["gc_id"];
	    $categorylist[$k]["gc_name"] = $class["gc_name"];
	    $categorylist[$k]["text"] = $class["text"];
	    $categorylist[$k]["class_detail"] = WAP_SITE_URL."/tmpl/product_first_categroy.html?gc_id=".$class["gc_id"];
        }
        $categorygoods = array();
        foreach($categories_map as $category => $gc_info){
            $categorygoods[$category] = $this->get_category($gc_info['gc_id'], $gc_info['gc_name']);
        }
        output_data(array("categorylist"=>$categorylist, "categorygoods"=>$categorygoods));
    }

    /**
     * 返回三级分类的商品列表
     */
    private function get_category($gc_id, $gc_name){
        $category_url = WAP_SITE_URL."/tmpl/product_list.html?gc_id=".$gc_id;
        $model_goods = Model('goods');
        $condition = array();
        $condition['goods.gc_id'] = $gc_id;
        $list = $model_goods->getGoodsList($condition);
        $category_list = array();
        foreach ($list as $k => $_info) {
             $category_list[$k]['goods_name'] = $_info['goods_name'];
             $category_list[$k]['goods_price'] = $_info['goods_price'];
             $category_list[$k]['goods_image'] = self::parse_image_url($_info['goods_image']);
             $category_list[$k]['product_detail'] = WAP_SITE_URL."/tmpl/product_detail.html?goods_id=".$_info['goods_id'];
        }
	return array("category_name"=>$gc_name, "category_url"=>$category_url, "category_list"=>$category_list); 
    } 

    /**
     * 返回一级分类列表
     */
    private function get_root_class() {
        $model_goods_class = Model('goods_class');
        $model_mb_category = Model('mb_category');

        $goods_class_array = Model('goods_class')->getGoodsClassForCacheModel();

        $class_list = $model_goods_class->getGoodsClassListByParentId(0);
        $mb_category = $model_mb_category->getLinkList(array());
        $mb_category = array_under_reset($mb_category, 'gc_id');
        foreach ($class_list as $key => $value) {
            if(!empty($mb_category[$value['gc_id']])) {
                $class_list[$key]['image'] = UPLOAD_SITE_URL.DS.ATTACH_MOBILE.DS.'category'.DS.$mb_category[$value['gc_id']]['gc_thumb'];
            } else {
                $class_list[$key]['image'] = '';
            }

            $class_list[$key]['text'] = '';
            $child_class_string = $goods_class_array[$value['gc_id']]['child'];
            $child_class_array = explode(',', $child_class_string);
            foreach ($child_class_array as $child_class) {
                $class_list[$key]['text'] .= $goods_class_array[$child_class]['gc_name'] . '/';
            }
            $class_list[$key]['text'] = rtrim($class_list[$key]['text'], '/');
        }
        return $class_list;
    }
    
    private static function parse_image_url($image_file){
        $split = explode('_', $image_file);
        return UPLOAD_SITE_URL."/mall/store/goods/".$split[0]."/".$image_file; 
    }
}
?>
