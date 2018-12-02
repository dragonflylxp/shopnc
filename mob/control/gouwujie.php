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
        $categroies_map = array(
            "equipment" => 1406,
            "makeup"    => 486,
            "baby"      => 1403
        );

        $categroies = array();
        foreach($categroies_map as $categroy => $gc_id){
            $categroies[$categroy] = $this->get_categroy($gc_id);
        }
        output_data($categroies);
    }

    private function get_categroy($gc_id){
        $categroy_url = WAP_SITE_URL."/tmpl/product_list.html?gc_id=".$gc_id;
        $model_goods = Model('goods');
        $condition = array();
        $condition['goods.gc_id'] = $gc_id;
        $list = $model_goods->getGoodsList($condition);
        $categroy_list = array();
        foreach ($list as $k => $_info) {
             $categroy_list[$k]['goods_name'] = $_info['goods_name'];
             $categroy_list[$k]['goods_price'] = $_info['goods_price'];
             $categroy_list[$k]['goods_image'] = self::parse_image_url($_info['goods_image']);
             $categroy_list[$k]['product_detail'] = WAP_SITE_URL."/tmpl/product_detail.html?goods_id=".$_info['goods_id'];
        }
	return array("categroy_url"=>$categroy_url, "categroy_list"=>$categroy_list); 
    } 
    
    private static function parse_image_url($image_file){
        $split = explode('_', $image_file);
        return UPLOAD_SITE_URL."/mall/store/goods/".$split[0]."/".$image_file; 
    }
}
?>
