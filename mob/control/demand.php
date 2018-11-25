<?php
/**
 * 点播详情页
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
class demandControl extends mobileHomeControl{

    public function __construct(){
        parent::__construct();

    }

    public function indexOp(){
        $this->demand_infoOp();
    }

    public function demand_infoOp(){

        if(empty($_GET['video_id'])){
            output_error('参数错误');
        }

        $condition = array();
        $condition['video_id'] = $_GET['video_id'];
        $condition['video_identity'] = 'demand';
        $demand_info = Model('mb_video')->getMbVideoInfo($condition);

        //更新该资讯的浏览量
        $update_array['page_view'] = $demand_info['page_view'] + 1;
        $result = Model('mb_video')->editMbVideo($update_array,$_GET['video_id']);
        if(!$result){
            output_error('更新浏览量失败');
        }else{
            //整理资讯信息
            $demand_info['add_time'] = date('Y-m-d',$demand_info['add_time']);
            $demand_info['promote_image_url'] = getMbDemandUrl($demand_info['promote_image']);
            $demand_info['promote_video_url'] = getMbDemandUrl($demand_info['promote_video']);
            $demand_info['demand_video_url'] = getMbDemandUrl($demand_info['demand_video']);
            $store_info = Model('store')->getStoreInfoByID($demand_info['store_id']);
            $store_info['store_label'] = getStoreLogo($store_info['store_label'] , 'store_logo');
            $store_info['store_avatar'] = getStoreLogo($store_info['store_avatar']);
            $demand_info['store_info'] = $store_info;
            // 如果已登录 判断该店铺是否已被收藏
            if ($memberId = $this->getMemberIdIfExists()) {
                $c = (int) Model('favorites')->getStoreFavoritesCountByStoreId($demand_info['store_id'], $memberId);
                $demand_info['is_favorate'] = $c > 0;
            } else {
                $demand_info['is_favorate'] = false;
            }

            // ==== 推荐商品  ====
            if($demand_info['recommend_goods'] != ''){
                $recommend_goods = unserialize($demand_info['recommend_goods']);
                foreach($recommend_goods as $goods_commonid => $v){
                    $goods_datas['goods_commonid'][] = $v['goods_commonid'];
                }
            }

            // 推荐商品详情，含商品规格属性
            $common_list_arr = array();
            if(!empty($goods_datas['goods_commonid']) && is_array($goods_datas['goods_commonid'])){
                $goods_where['goods_commonid'] = array('in',$goods_datas['goods_commonid']);
                $goods_common_list_arr = Model('goods')->getGoodsCommonOnlineList($goods_where);
                foreach($goods_common_list_arr as $k => $v){
                    //获取sku的id
                    $goods_datas = Model('goods')->getGoodsInfo(array('goods_commonid' => $v['goods_commonid']) , 'goods_id');
                    $common_list_arr[$k]['goods_id'] = $goods_datas['goods_id'];
                    $common_list_arr[$k]['goods_commonid'] = $v['goods_commonid'];
                    $common_list_arr[$k]['goods_name'] = $v['goods_name'];
                    $common_list_arr[$k]['store_id'] = $v['store_id'];
                    $common_list_arr[$k]['store_name'] = $v['store_name'];
                    $common_list_arr[$k]['goods_price'] = $v['goods_price'];
                    $common_list_arr[$k]['goods_marketprice'] = $v['goods_marketprice'];
                    $common_list_arr[$k]['gc_id'] = $v['gc_id'];
                    $common_list_arr[$k]['gc_name'] = $v['gc_name'];
                    $common_list_arr[$k]['is_own_shop'] = $v['is_own_shop'];
                    $common_list_arr[$k]['goods_image'] = $v['goods_image'];
                    //商品图片url
                    $common_list_arr[$k]['goods_image_url'] = cthumb($v['goods_image'], 360, $v['store_id']);
                }
            }


            $data['demand_info'] = $demand_info;
            $data['recommend_goods_common_list'] = $common_list_arr;

            output_data($data);
        }

    }

    public function recommend_goods_detailOp(){
        if(!$_GET['goods_id']){
            output_error('参数非法');
        }
        $goods_id = $_GET['goods_id'];

        //商品详情
        $goods_detail = Model('goods')->getGoodsDetail($goods_id);

        if (empty($goods_detail)) {
            output_error('商品不存在');
        }

        // 默认预订商品不支持手机端显示
        if ($goods_detail['goods_info']['is_book']) {
            output_error('预订商品不支持手机端显示');
        }

        $goods_lists = Model('goods')->getGoodsContract(array(0=>$goods_detail['goods_info']));
        $goods_detail['goods_info'] = $goods_lists[0];

        $store_info = Model('store')->getStoreInfoByID($goods_detail['goods_info']['store_id']);

        $goods_detail['store_info']['store_id'] = $store_info['store_id'];
        $goods_detail['store_info']['store_name'] = $store_info['store_name'];
        $goods_detail['store_info']['member_id'] = $store_info['member_id'];
        $goods_detail['store_info']['member_name'] = $store_info['member_name'];
        $goods_detail['store_info']['is_own_shop'] = $store_info['is_own_shop'];

        $goods_detail['store_info']['goods_count'] = $store_info['goods_count'];

        $storeCredit = array();
        $percentClassTextMap = array(
            'equal' => '平',
            'high' => '高',
            'low' => '低',
        );
        foreach ((array) $store_info['store_credit'] as $k => $v) {
            $v['percent_text'] = $percentClassTextMap[$v['percent_class']];
            $storeCredit[$k] = $v;
        }
        $goods_detail['store_info']['store_credit'] = $storeCredit;

        //商品详细信息处理
        $goods_detail = $this->_goods_detail_extend($goods_detail);

        // 如果已登录 判断该商品是否已被收藏&&添加浏览记录
        $member_id = $this->getMemberIdIfExists();
        if ($member_id) {
            $c = (int) Model('favorites')->getGoodsFavoritesCountByGoodsId($goods_id, $member_id);
            $goods_detail['is_favorate'] = $c > 0;

            QueueClient::push('addViewedGoods', array('goods_id'=>$goods_id,'member_id'=>$member_id));

            if (!$goods_detail['goods_info']['is_virtual']) {
                // 店铺优惠券
                $condition = array();
                $condition['voucher_t_gettype'] = 3;
                $condition['voucher_t_state'] = 1;
                $condition['voucher_t_end_date'] = array('gt', time());
                $condition['voucher_t_store_id'] = array('in', $store_info['store_id']);
                $voucher_template = Model('voucher')->getVoucherTemplateList($condition);
                if (!empty($voucher_template)) {
                    foreach ($voucher_template as $val) {
                        $param = array();
                        $param['voucher_t_id'] = $val['voucher_t_id'];
                        $param['voucher_t_price'] = $val['voucher_t_price'];
                        $param['voucher_t_limit'] = $val['voucher_t_limit'];
                        $param['voucher_t_end_date'] = date('Y年m月d日', $val['voucher_t_end_date']);
                        $goods_detail['voucher'][] = $param;
                    }
                }
            }

        }
        $recommend_goods_detail['goods_detail'] = $goods_detail;

        output_data($recommend_goods_detail);
    }

    /**
     * 商品详细信息处理
     */
    private function _goods_detail_extend($goods_detail) {
        //整理商品规格
        unset($goods_detail['spec_list']);
        $goods_detail['spec_list'] = $goods_detail['spec_list_mobile'];
        unset($goods_detail['spec_list_mobile']);

        //整理商品图片
        unset($goods_detail['goods_image']);
        $goods_detail['goods_image'] = implode(',', $goods_detail['goods_image_mobile']);
        unset($goods_detail['goods_image_mobile']);

        //商品链接
        $goods_detail['goods_info']['goods_url'] = urlShop('goods', 'index', array('goods_id' => $goods_detail['goods_info']['goods_id']));

        //整理数据
        unset($goods_detail['goods_info']['goods_commonid']);
        unset($goods_detail['goods_info']['gc_id']);
        unset($goods_detail['goods_info']['gc_name']);
        unset($goods_detail['goods_info']['store_id']);
        unset($goods_detail['goods_info']['store_name']);
        unset($goods_detail['goods_info']['brand_id']);
        unset($goods_detail['goods_info']['brand_name']);
        unset($goods_detail['goods_info']['type_id']);
        unset($goods_detail['goods_info']['goods_image']);
        unset($goods_detail['goods_info']['goods_body']);
        unset($goods_detail['goods_info']['goods_state']);
        unset($goods_detail['goods_info']['goods_stateremark']);
        unset($goods_detail['goods_info']['goods_verify']);
        unset($goods_detail['goods_info']['goods_verifyremark']);
        unset($goods_detail['goods_info']['goods_lock']);
        unset($goods_detail['goods_info']['goods_addtime']);
        unset($goods_detail['goods_info']['goods_edittime']);
        unset($goods_detail['goods_info']['goods_selltime']);
        unset($goods_detail['goods_info']['goods_show']);
        unset($goods_detail['goods_info']['goods_commend']);
        unset($goods_detail['goods_info']['explain']);
        unset($goods_detail['goods_info']['buynow_text']);
        unset($goods_detail['groupbuy_info']);
        unset($goods_detail['xianshi_info']);

        return $goods_detail;
    }


}