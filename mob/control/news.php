<?php
/**
 * 资讯详情页
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
class newsControl extends mobileHomeControl{


    public function __construct(){
        parent::__construct();

    }

    public function indexOp(){
        $this->news_infoOp();
    }

    public function news_infoOp(){

        if(empty($_GET['video_id'])){
            output_error('参数错误');
        }

        $news_condition = array();
        $common_list = array();
        $news_condition['video_id'] = $_GET['video_id'];
        $news_condition['video_identity'] = 'news';
        $news_info = Model('mb_video')->getMbVideoInfo($news_condition);

        //更新该资讯的浏览量
        $update_array['page_view'] = $news_info['page_view'] + 1;
        $result = Model('mb_video')->editMbVideo($update_array,$_GET['video_id']);
        if(!$result){
            output_error('更新点击率失败');
        }else{
            // 手机商品描述
            if ($news_info['mobile_body'] != '') {
                $mobile_body_array = unserialize($news_info['mobile_body']);
                $news_info['mobile_body'] = $mobile_body_array;
            }

            //整理资讯信息
            $news_info['add_time'] = date('Y-m-d',$news_info['add_time']);
            $news_info['news_pic'] = getMbNewsImageUrl($news_info['news_image']);

            //推荐商品
            if($news_info['recommend_goods'] != ''){
                $recommend_goods = unserialize($news_info['recommend_goods']);
                $goods_condition['goods_commonid'] = array('in',$recommend_goods['goods_commonid']);
                $goods_common_list = Model('goods')->getGeneralGoodsCommonList($goods_condition);
                foreach($goods_common_list as $key => $common_info){
                    //获取sku的id
                    $goods_datas = Model('goods')->getGoodsInfo(array('goods_commonid' => $common_info['goods_commonid']) , 'goods_id');
                    $common_list[$key]['goods_id'] = $goods_datas['goods_id'];
                    $common_list[$key]['goods_commonid'] = $common_info['goods_commonid'];
                    $common_list[$key]['goods_name'] = $common_info['goods_name'];
                    $common_list[$key]['goods_price'] = $common_info['goods_price'];
                    $common_list[$key]['goods_image_url'] = thumb($common_info,240);
                }
            }

            $data['news_info'] = $news_info;
            $data['goods_common_list'] = $common_list;

            output_data($data);
        }



    }

}