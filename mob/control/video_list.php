<?php
/**
 * 视频列表
 *
 *
 *
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net
 * @link       http://www.shopec.net
 * @since      File available since Release v1.1
 */

use shopec\Tpl;

defined('Inshopec') or exit('Access Invideo_infoid!');
class video_listControl extends mobileHomeControl{

    public function __construct(){
        parent::__construct();
    }

    public function indexOp(){
        $this->listOp();
    }

    // 视频点播列表
    public function listOp(){

        //广告图
        $focus_list_arr = array();
        $focus_list = Model('mb_focus')->getMbFocusList(array(),'','focus_sort desc');
        if(!empty($focus_list) && is_array($focus_list)){
            foreach($focus_list as $key => $v){
                $focus_list_arr[$key]['focus_id'] = $v['focus_id'];
                $focus_list_arr[$key]['focus_image_url'] = getMbFocusImageUrl($v['focus_image']);
                $focus_list_arr[$key]['focus_data'] = $v['focus_url'];
                $focus_list_arr[$key]['focus_image'] = $v['focus_image'];
                $focus_list_arr[$key]['focus_type'] = $v['focus_type'];
            }
            $focus_list = $focus_list_arr;
        }else{
            $focus_list = array();
        }


        //按分类筛选
        $condition = array();
        if($_GET['cate_id']){
            $condition['cate_id'] = $_GET['cate_id'];
        }

        $condition['movie_state'] = 1;

        //视频点播列表
        $model_dis_msg = Model('dis_msg');
        $list_arr = array();
        $order = 'video_identity_type desc , add_time desc';
        $video_list = Model('mb_video')->getMbVideoList($condition , $this->page,$order);
        //整理数据
        foreach($video_list as $key => $video_info){
            $video_list[$key]['video_id'] = $video_info['video_id'];
            $video_list[$key]['add_time'] = date('Y-m-d',$video_info['add_time']);
            $video_list[$key]['identity'] = $video_info['video_identity'];
            $video_list[$key]['member_id'] = $video_info['member_id'];
            $video_list[$key]['member_name'] = $video_info['member_name'];
            $video_list[$key]['movie_play_url'] = getMbMoiveUrl($video_info['movie_rand'],'m3u8');
            $video_list[$key]['member_avater'] = getMemberAvatarForID($video_info['member_id']);
            $video_list[$key]['movie_title'] = $video_info['movie_title'];
            $video_list[$key]['movie_cover_img'] = getMbMoiveImageUrl($video_info['movie_cover_img'],$video_info['member_id']);

            $video_list[$key]['page_view'] = intval($video_info['page_view']);

            $video_list[$key]['store_id'] = $video_info['store_id'];
            $store_info = Model('store')->getStoreInfoByID($video_info['store_id']);
            $video_list[$key]['store_name'] = $store_info['store_name'];
            $video_list[$key]['store_name'] = $store_info['store_name'];
            $video_list[$key]['store_logo'] = getStoreLogo($store_info['store_label'], 'store_logo');
            $video_list[$key]['store_avatar'] = getStoreLogo($store_info['store_avatar'],'store_avatar');
            $video_list[$key]['promote_video'] = getMbDemandUrl($video_info['promote_video']);
            $video_list[$key]['demand_video'] = getMbDemandUrl($video_info['demand_video']);
            $video_list[$key]['promote_text'] = $video_info['promote_text'];
            $video_list[$key]['promote_image'] = getMbDemandUrl($video_info['promote_image']);

            if($video_info['recommend_goods']){
                //推荐商品
                $recommend_goods = unserialize($video_info['recommend_goods']);
                if(!empty($recommend_goods) && is_array($recommend_goods)){
                    $i = 0;
                    $recommend = array();
                    foreach($recommend_goods as $goods_commonid => $v){
                        //获取sku的id
                        $goods_datas = Model('goods')->getGoodsInfo(array('goods_commonid' => $goods_commonid) , 'goods_id');
                        $recommend[$i]['goods_id'] = $goods_datas['goods_id'];
                        $recommend[$i]['goods_name'] = $v['goods_name'];
                        $recommend[$i]['goods_price'] = $v['goods_price'];
                        $recommend[$i]['goods_image'] = thumb($v,240);
                        $i++;
                    }
                    $video_list[$key]['recommend_goods'] = $recommend;
                }
            }else{
                $video_list[$key]['recommend_goods'] = array();
            }
            

            // 如果已登录 判断该店铺是否已被收藏
            if ($memberId = $this->getMemberIdIfExists()) {
                $c = (int) Model('favorites')->getStoreFavoritesCountByStoreId($video_info['store_id'], $memberId);
                $video_list[$key]['is_favorate'] = $c > 0;
            } else {
                $video_list[$key]['is_favorate'] = false;
            }

            $video_list[$key]['news_name'] = $video_info['news_name'];
            $video_list[$key]['news_pic'] = getMbNewsImageUrl($video_info['news_image']);
            
        }

        $page_count = Model('mb_video')->gettotalpage();

        output_data(array('focus_list' => $focus_list ,'lists' => $video_list) , mobile_page($page_count));
    }

    //视频分类
    public function cate_listOp(){
        $cate_list = Model('video_category')->getVideoCategoryList(array());
        foreach($cate_list as $keyey => $video_infoue){
            $cate_list[$keyey]['cate_image'] = UPLOAD_SITE_URL.DS.ATTACH_MOBILE.'/video_category/'.$video_infoue['cate_image'];
        }
        output_data(array('cate_list' => $cate_list));
    }

}