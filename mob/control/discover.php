<?php
/**
 * 壹分体育发现页 
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
class discoverControl extends mobileHomeControl {
    public function __construct() {
        parent::__construct();
    }

    public function refreshOp() {
        //限时折扣
        $model_xianshi = Model('p_xianshi_goods');
        $model_xianshi->updateXianshiGoods();
        $model_pintuan = Model('p_pintuan');
        $model_pintuan->updatePintuanGoods();
        output_data("刷新数据成功!");
    }

    public function infoOp() {
        $now = time();
        //限时折扣
        $model_xianshi = Model('p_xianshi_goods');
        $condition = array();
        if (isset($_GET['xianshi_state'])) {
            $condition['state'] = intval($_GET['xianshi_state']);
        }
        $limit = 0;
        if (isset($_GET['xianshi_limit'])) {
            $limit = intval($_GET['xianshi_limit']);
        }
        $list = $model_xianshi->getXianshiGoodsList($condition, null, 'xianshi_goods_id desc', '*', $limit);
        $xianshi_list = array();
        $cnt = 0;
        foreach ($list as $k => $_info) {
            if ($now < $_info['start_time'] || $now >$_info['end_time']){
                continue;
            }
            $xianshi_list[$cnt]['xianshi_name'] = $_info['xianshi_name'];
            $xianshi_list[$cnt]['xianshi_price'] = $_info['xianshi_price'];
            $xianshi_list[$cnt]['goods_name'] = $_info['goods_name'];
            $xianshi_list[$cnt]['goods_price'] = $_info['goods_price'];
            $xianshi_list[$cnt]['goods_image'] = $_info['goods_image'];
            $xianshi_list[$cnt]['goods_image'] = self::parse_image_url($_info['goods_image']);
            $xianshi_list[$cnt]['start_time'] = $_info['start_time'];
            $xianshi_list[$cnt]['end_time'] = $_info['end_time'];
            $xianshi_list[$cnt]['lower_limit'] = $_info['lower_limit'];
            $xianshi_list[$cnt]['product_detail'] = WAP_SITE_URL."/tmpl/product_detail.html?goods_id=".$_info['goods_id'];
            $cnt = $cnt+1;
        }
        
        //拼团数据
        $model_pintuan = Model('p_pintuan');
        $condition = array();
        if (isset($_GET['pintua_state'])) {
            $condition['state'] = intval($_GET['pintuan_state']);
        }
        $limit = 0;
        if (isset($_GET['pintuan_limit'])) {
            $limit = intval($_GET['pintuan_limit']);
        }
        $list = $model_pintuan->getGoodsList($condition, null, 'pintuan_goods_id desc', '*',$limit);
        $pintuan_list = array();
        $cnt = 0;
        foreach ($list as $k => $_info) {
            if ($now < $_info['start_time'] || $now >$_info['end_time']){
                continue;
            }
             $pintuan_list[$cnt]['pintuan_name'] = $_info['pintuan_name'];
             $pintuan_list[$cnt]['pintuan_price'] = $_info['pintuan_price'];
             $pintuan_list[$cnt]['goods_name'] = $_info['goods_name'];
             $pintuan_list[$cnt]['goods_price'] = $_info['goods_price'];
             $pintuan_list[$cnt]['goods_image'] = self::parse_image_url($_info['goods_image']);
             $pintuan_list[$cnt]['min_num'] = $_info['min_num'];
             $pintuan_list[$cnt]['start_time'] = $_info['start_time'];
             $pintuan_list[$cnt]['end_time'] = $_info['end_time'];
             $pintuan_list[$cnt]['product_detail'] = WAP_SITE_URL."/tmpl/product_detail.html?goods_id=".$_info['goods_id'];
             $cnt = $cnt+1;
        }
        
        //栏目跳转
        $xianshi_url = WAP_SITE_URL."/tmpl/product_list.html?xianshi=1";
        $pintuan_url = WAP_SITE_URL."/tmpl/product_list.html?groupbuy=1";
        output_data(array('xianshi_url'=>$xianshi_url, 'pintuan_url'=>$pintuan_url,'xianshi_list'=> $xianshi_list,'pintuan_list'=> $pintuan_list));
    }
    
    private static function parse_image_url($image_file){
        $split = explode('_', $image_file);
        return UPLOAD_SITE_URL."/mall/store/goods/".$split[0]."/".$image_file; 
    }
}
?>
