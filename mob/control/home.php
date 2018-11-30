<?php
/**
 * 壹分体育首页 
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
class homeControl extends mobileHomeControl {
    public function __construct() {
        parent::__construct();
    }

    public function infoOp() {
        //精品推荐
        $limit = 0;
        if (isset($_GET['recommend_limit'])) {
            $limit = intval($_GET['recommend_limit']);
        }
        $model_goods_recommend = Model('goods_recommend');
        $rec_list = Model('goods_recommend')->getGoodsRecommendList(array(),'','','*','','rec_goods_id');
        $goods_list = Model('goods')->getGoodsOnlineList(array('goods_id'=>array('in',array_keys($rec_list))), 'goods_id,goods_name,goods_price,goods_image',0,'goods_id desc', $limit);
        $recommend_goods_list = array();
        foreach($goods_list as $k => $_info ){
            $recommend_goods_list[$k]['goods_name'] = $_info['goods_name'];
            $recommend_goods_list[$k]['goods_price'] = $_info['goods_price'];
            $recommend_goods_list[$k]['goods_image'] = self::parse_image_url($_info['goods_image']);
            $recommend_goods_list[$k]['product_detail'] = WAP_SITE_URL."/tmpl/product_detail.html?goods_id=".$_info['goods_id'];
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
        $pintuan_url = WAP_SITE_URL."/tmpl/product_list.html?groupbuy=1";
        
        //栏目跳转
        output_data(array('recommend_url'=>'', 'recommend_goods_list'=> $recommend_goods_list, 'pintuan_url'=>$pintuan_url, 'pintuan_list'=> $pintuan_list));
    }
    
    private static function parse_image_url($image_file){
        $split = explode('_', $image_file);
        return UPLOAD_SITE_URL."/mall/store/goods/".$split[0]."/".$image_file; 
    }
}
?>
