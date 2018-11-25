<?php
/**
 * 商品列表
 *
 *
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net
 * @link       http://www.shopec.net
 * @since      File available since Release v1.1
 */
use shopec\Tpl;
use shopec\Log;

defined('Inshopec') or exit('Access Invalid!');

class searchControl extends BaseHomeControl {


    //每页显示商品数
    const PAGESIZE = 24;

    //模型对象
    private $_model_search;

    public function indexOp() {
        $extend = 'goods_common';        //gongbo   20161216
        Language::read('home_goods_class_index');
        $this->_model_search = Model('search');
        //显示左侧分类
        //默认分类，从而显示相应的属性和品牌
        $default_classid = intval($_GET['cate_id']);
        if (intval($_GET['cate_id']) > 0) {
            $goods_class_array = $this->_model_search->getLeftCategory(array($_GET['cate_id']));
        } elseif ($_GET['keyword'] != '') {
            if (cookie('his_sh') == '') {
                $his_sh_list = array();
            } else {
                $his_sh_list = explode('~', cookie('his_sh'));
            }
            if (strlen($_GET['keyword']) <= 30 && !in_array($_GET['keyword'],$his_sh_list)) {
                if (array_unshift($his_sh_list, $_GET['keyword']) > 8) {
                    array_pop($his_sh_list);
                }
            }
            setNcCookie('his_sh', implode('~', $his_sh_list),2592000);
            //从TAG中查找分类
            $goods_class_array = $this->_model_search->getTagCategory($_GET['keyword']);
            //取出第一个分类作为默认分类，从而显示相应的属性和品牌
            $default_classid = $goods_class_array[0];
            $goods_class_array = $this->_model_search->getLeftCategory($goods_class_array, 1);
        }
        Tpl::output('goods_class_array', $goods_class_array);
        Tpl::output('default_classid', $default_classid);

        //全文搜索搜索参数
        $indexer_searcharr = $_GET;

        //搜索消费者保障服务
        $search_ci_arr = array();
        $search_ci_str = '';
        if ($_GET['ci'] && $_GET['ci'] != 0 && is_string($_GET['ci'])) {
            //处理参数
            $search_ci= $_GET['ci'];
            $search_ci_arr = explode('_',$search_ci);
            $search_ci_str = $search_ci.'_';
            $indexer_searcharr['search_ci_arr'] = $search_ci_arr;
        }

        //优先从全文索引库里查找
        list($goods_list, $indexer_count,$indexer_ids,$indexer_name,$indexer_time,$indexer_related,$indexer_hot,$indexer_corrected,$indexer_total,$index_corrected) = $this->_model_search->indexerSearch($indexer_searcharr,self::PAGESIZE);
        
        //获得经过属性过滤的商品信息
        list($goods_param, $brand_array, $initial_array, $attr_array, $checked_brand, $checked_attr) = $this->_model_search->getAttr($_GET, $default_classid);
        Tpl::output('brand_array', $brand_array);
        Tpl::output('initial_array', $initial_array);
        Tpl::output('attr_array', $attr_array);
        Tpl::output('checked_brand', $checked_brand);
        Tpl::output('checked_attr', $checked_attr);
        
        Tpl::output('indexer_time', $indexer_time);
		Tpl::output('indexer_related', $indexer_related);
		Tpl::output('indexer_hot', $indexer_hot);
		Tpl::output('indexer_corrected', $indexer_corrected);
		Tpl::output('index_corrected', $index_corrected);
		Tpl::output('indexer_total', $indexer_total);
		Tpl::output('indexer_count', $indexer_count);
        //查询消费者保障服务
        $contract_item = array();
        if (C('contract_allow') == 1) {
            $contract_item = Model('contract')->getContractItemByCache();
        }
        Tpl::output('contract_item',$contract_item);

        $model_goods = Model('goods');
        if (!is_null($goods_list)) {
            //全文搜索 
            pagecmd('setEachNum',self::PAGESIZE);
            pagecmd('setTotalNum',$indexer_count);

        } else {
            //查库搜索

            //处理排序
            $order = 'is_own_shop desc,goods_id desc';
            if (in_array($_GET['key'],array('1','2','3'))) {
                $sequence = $_GET['order'] == '1' ? 'asc' : 'desc';
                //$order = str_replace(array('1','2','3'), array('goods_salenum','goods_click','goods_promotion_price'), $_GET['key']);
                $order = str_replace(array('1','2','3'), array('goods_common.sale_count','goods_click','goods_promotion_price'), $_GET['key']);
                $order .= ' '.$sequence;
            }

            // 字段
            $fields = "goods_id,goods_commonid,goods_name,goods_jingle,gc_id,store_id,store_name,goods_price,goods_promotion_price,goods_promotion_type,goods_marketprice,goods_storage,goods_image,goods_freight,goods_salenum,brand_id,color_id,gc_id_3,gc_id_1,gc_id_2,goods_verify,goods_state,is_own_shop,evaluation_good_star,evaluation_count,is_virtual,is_fcode,is_presell,is_book,book_down_time,have_gift,areaid_1";
            //构造消费者保障服务字段
            if ($contract_item) {
                foreach ($contract_item as $citem_key=>$citem_val) {
                    $fields .= ",contract_{$citem_key}";
                }
            }
            /*
             * 拼接表前缀   gongbo   20161226   -------------start-----------------
             */
            $field_arr = explode(',',$fields);
            foreach($field_arr as $f_k => $f_v){
                $field_arr[$f_k] = 'goods.'.$f_v;
            }
            $fields = implode(',',$field_arr);
            /*
            * 拼接表前缀   gongbo   20161226   -------------end-----------------
            */


            $goods_class = Model('goods_class')->getGoodsClassForCacheModel();
            $condition = array();
            if (isset($goods_param['class'])) {
                $condition['goods.gc_id_'.$goods_param['class']['depth']] = $goods_param['class']['gc_id'];
            }
            if (intval($_GET['b_id']) > 0) {
                $condition['goods.brand_id'] = intval($_GET['b_id']);
            }
            if ($_GET['keyword'] != '') {
                $condition['goods.goods_name|goods.goods_jingle'] = array('like', '%' . $_GET['keyword'] . '%');
            }
            if (intval($_GET['area_id']) > 0) {
                $condition['goods.areaid_1'] = intval($_GET['area_id']);
            }
            if ($_GET['type'] == 1) {
                $condition['goods.is_own_shop'] = 1;
            }
            if ($_GET['gift'] == 1) {
                $condition['goods.have_gift'] = 1;
            }
            //消费者保障服务
            if ($contract_item && $search_ci_arr) {
                foreach ($search_ci_arr as $ci_val) {
                    $condition["goods.contract_{$ci_val}"] = 1;
                }
            }

            if (isset($goods_param['goodsid_array'])){
                $condition['goods.goods_id'] = array('in', $goods_param['goodsid_array']);
            }

            if ($goods_class[$default_classid]['show_type'] == 1) {
                $goods_list = $model_goods->getGoodsListByColorDistinct($condition, $fields, $order, self::PAGESIZE,0,$extend);
            } else {
                if (C('dbdriver') == 'oracle') {
                    $oracle_fields = array();
                    $fields = explode(',', $fields);
                    foreach ($fields as $val) {
                        $oracle_fields[] = 'min('.$val.') '.$val;
                    }
                    $fields = implode(',', $oracle_fields);
                }
                $count = $model_goods->getGoodsOnlineCount($condition,"distinct goods.goods_commonid");
                $goods_list = $model_goods->getGoodsOnlineList($condition, $fields, self::PAGESIZE, $order, 0, 'goods.goods_commonid', false, $count,$extend);
            }
        }
        Tpl::output('search_ci_str', $search_ci_str);
        Tpl::output('search_ci_arr', $search_ci_arr);
        Tpl::output('show_page1', $model_goods->showpage(4));
        Tpl::output('show_page', $model_goods->showpage(5));

        if (!empty($goods_list)) {
            if (is_null($indexer_count)) {
                //查库搜索
                $commonid_array = array(); // 商品公共id数组
                $storeid_array = array();       // 店铺id数组
                foreach ($goods_list as $value) {
                    $commonid_array[] = $value['goods_commonid'];
                    $storeid_array[] = $value['store_id'];
                }
                $commonid_array = array_unique($commonid_array);
                $storeid_array = array_unique($storeid_array);
                // 商品多图
                $goodsimage_more = $model_goods->getGoodsImageList(array('goods_commonid' => array('in', $commonid_array)), '*', 'is_default desc,goods_image_id asc');
                // 店铺
                $store_list = Model('store')->getStoreMemberIDList($storeid_array);
                //处理商品消费者保障服务信息
                $goods_list = $model_goods->getGoodsContract($goods_list, $contract_item);
            }
           
            //搜索的关键字
            $search_keyword = $_GET['keyword'];
            
            foreach ($goods_list as $key => $value) {
                if (is_null($indexer_count)) {
                    // 商品多图
                    
                    if ($goods_class[$default_classid]['show_type'] == 1) {
                        foreach ($goodsimage_more as $v) {
                            if ($value['goods_commonid'] == $v['goods_commonid'] && $value['store_id'] == $v['store_id'] && $value['color_id'] == $v['color_id']) {
                                $goods_list[$key]['image'][] = $v['goods_image'];
                            }
                        }
                    } else {
                        foreach ($goodsimage_more as $v) {
                            if ($value['goods_commonid'] == $v['goods_commonid'] && $value['store_id'] == $v['store_id'] && $v['is_default'] == 1) {
                                $goods_list[$key]['image'][] = $v['goods_image'];
                            }
                        }
                    }
                    // 店铺的开店会员编号
                    $store_id = $value['store_id'];
                    $goods_list[$key]['member_id'] = $store_list[$store_id]['member_id'];
                    $goods_list[$key]['store_domain'] = $store_list[$store_id]['store_domain'];                    
                }
                if (!C('fullindexer.open')) {
                   //将关键字置红
                   if ($search_keyword){
                        $goods_list[$key]['goods_name_highlight'] = str_replace($search_keyword,'<font style="color:#f00;">'.$search_keyword.'</font>',$value['goods_name']);
                    } else {
                        $goods_list[$key]['goods_name_highlight'] = $value['goods_name'];
                    }                 	
                }else{
                	//将关键字置红xunsearch
                	//合并商品名+id
                	foreach ($indexer_ids as $ke => $va) {
                		
                		$xunsearch[$va] = $indexer_name[$ke];
                	}
                    $goods_list[$key]['goods_name_highlight'] = $xunsearch[$key]; 
	                	
              	
                }


                // 验证预定商品是否到期
                if ($value['is_book'] == 1) {
                    if ( $value['book_down_time'] < TIMESTAMP ) {
                        QueueClient::push('updateGoodsPromotionPriceByGoodsId', $value['goods_id']);
                        $goods_list[$key]['is_book'] = 0;
                    }
                }
            }
        }
        Tpl::output('goods_list', $goods_list);
        if ($_GET['keyword'] != ''){
            Tpl::output('show_keyword',  $_GET['keyword']);
        } else {
            Tpl::output('show_keyword',  $goods_param['class']['gc_name']);
        }

        $model_goods_class = Model('goods_class');

        // SEO
        if ($_GET['keyword'] == '') {
            $seo_class_name = $goods_param['class']['gc_name'];
            if (is_numeric($_GET['cate_id']) && empty($_GET['keyword'])) {
                $seo_info = $model_goods_class->getKeyWords(intval($_GET['cate_id']));
                if (empty($seo_info[1])) {
                    $seo_info[1] = C('site_name') . ' - ' . $seo_class_name;
                }
                Model('seo')->type($seo_info)->param(array('name' => $seo_class_name))->show();
            }
        } elseif ($_GET['keyword'] != '') {
            Tpl::output('html_title', (empty($_GET['keyword']) ? '' : $_GET['keyword'] . ' - ') . C('site_name') . L('nc_common_search'));
        }

        // 当前位置导航
        $nav_link_list = $model_goods_class->getGoodsClassNav(intval($_GET['cate_id']));
        Tpl::output('nav_link_list', $nav_link_list );

        // 得到自定义导航信息
        $nav_id = intval($_GET['nav_id']) ? intval($_GET['nav_id']) : 0;
        Tpl::output('index_sign', $nav_id);

        // 地区
        $province_array = Model('area')->getTopLevelAreas();
        Tpl::output('province_array', $province_array);

        loadfunc('search');

        // 浏览过的商品
        $viewed_goods = Model('goods_browse')->getViewedGoodsList($_SESSION['member_id'],20);
        Tpl::output('viewed_goods',$viewed_goods);
        Tpl::showpage('search');
    }

    /**
     * 获得推荐商品
     */
    public function get_booth_goodsOp() {
        $gc_id = $_GET['cate_id'];
        if ($gc_id <= 0) {
            return false;
        }
        // 获取分类id及其所有子集分类id
        $goods_class = Model('goods_class')->getGoodsClassForCacheModel();
        if (empty($goods_class[$gc_id])) {
            return false;
        }
        $child = (!empty($goods_class[$gc_id]['child'])) ? explode(',', $goods_class[$gc_id]['child']) : array();
        $childchild = (!empty($goods_class[$gc_id]['childchild'])) ? explode(',', $goods_class[$gc_id]['childchild']) : array();
        $gcid_array = array_merge(array($gc_id), $child, $childchild);
        // 查询添加到推荐展位中的商品id
        $boothgoods_list = Model('p_booth')->getBoothGoodsList(array('gc_id' => array('in', $gcid_array)), 'goods_id', 0, 4, 'rand()');
        if (empty($boothgoods_list)) {
            return false;
        }

        $goodsid_array = array();
        foreach ($boothgoods_list as $val) {
            $goodsid_array[] = $val['goods_id'];
        }

        $fieldstr = "goods_id,goods_commonid,goods_name,goods_jingle,store_id,store_name,goods_price,goods_promotion_price,goods_promotion_type,goods_marketprice,goods_storage,goods_image,goods_freight,goods_salenum,color_id,evaluation_count";
        $goods_list = Model('goods')->getGoodsOnlineList(array('goods_id' => array('in', $goodsid_array)), $fieldstr);
        if (empty($goods_list)) {
            return false;
        }

        Tpl::output('goods_list', $goods_list);
        Tpl::showpage('goods.booth', 'null_layout');
    }

    public function auto_completeOp() {
        exit(json_encode(Model('search')->autoComplete($_GET)));
    }
  
    /**
     * 百度地图Suggestion
     */
    public function city_completeOp() {
        $get_city = $_GET['term'];
        $wap = $_GET['q'];
        $cityinfo = unserialize(stripslashes($_COOKIE['city']));
        $area_name = $cityinfo['area_name'];
        
 		$url = "http://api.map.baidu.com/place/v2/suggestion?query={$get_city}&region={$area_name}&output=json&ak=066fedaca9ec8ee99b70b7743b1a0b75"; 
 		$ch = curl_init(); 
 		curl_setopt ($ch, CURLOPT_URL, $url); 
 		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
 		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT,10); 
 		$content = curl_exec($ch); 

 		$suggestion = json_decode($content);
 		
 		$sug = $suggestion->result;
 		//整理推荐数据
 		if($suggestion->message == 'ok' && !empty($sug)){
 			$object =  json_decode( json_encode($sug),true);
 			foreach($object as $zk=>$zv){
 				if(!empty($zv['city'])){
 					if(stripos($zv['city'],'省')){
  					    $cond['area_name']= array('like', '%' . $zv['name'] . '%');   
		                $area_model = Model('area')->getAreaInfo($cond); 						
 					}else{
 					    $cond['area_name']= array('like', '%' . $zv['city'] . '%');   
		                $area_model = Model('area')->getAreaInfo($cond);   						
 					}
 
		            if(!empty($area_model)){
		             	$row['id'] = $area_model['area_id'];
		             	if($zv['name'] == $zv['city']){
		             		$row['label'] = $zv['name'];
		             	}else{
		             		$row['label'] = $zv['name'].','.$zv['city'];
		             	}                                              
                        $row['value'] = $zv['name'].','.$zv['city'];
                        $data[] = $row; 		
		            }
				
 				}

 			}
 			if($wap == '2'){
 				$wap_data =array();
 				$wap_data['code']='200';
 				$wap_data['datas']['list'] = $data;
                $data = $wap_data;
 			}
 			exit(json_encode($data));
 		}else{
 			if($wap == '2'){
 				$wap_data =array();
 				$wap_data['code']='200';
 				$wap_data['datas']['error'] = 1;
 				exit(json_encode($wap_data));
 			}else{
 				return array();
 			}
 			
 		}
  	
        //exit(json_encode(Model('search')->cityComplete($_GET)));
    }
    /**
     * 获得猜你喜欢
     */
    public function get_guesslikeOp(){
        $goodslist = Model('goods_browse')->getGuessLikeGoods($_SESSION['member_id'], 20);
        if(!empty($goodslist)){
            Tpl::output('goodslist',$goodslist);
            Tpl::showpage('goods_guesslike','null_layout');
        }
    }
    public function goodssuggestionsOp() {
        exit(json_encode(Model('search')->autoComplete($_GET)));
    }

    /**
     * 商品分类推荐
     */
    public function get_gc_goods_recommendOp(){
        $rec_gc_id = intval($_GET['cate_id']);
        //只有最后一级才有推荐商品
        $class_info = Model('goods_class')->getGoodsClassListByParentId($rec_gc_id);
        if (!empty($class_info)) {
            return ;
        }
        $goods_list = array();
        if ($rec_gc_id > 0) {
            $rec_list = Model('goods_recommend')->getGoodsRecommendList(array('rec_gc_id'=>$rec_gc_id),'','','*','','rec_goods_id');
            if (!empty($rec_list)) {
                $goods_list = Model('goods')->getGoodsOnlineList(array('goods_id'=>array('in',array_keys($rec_list))));
                if (!empty($goods_list)) {
                    Tpl::output('goods_list',$goods_list);
                    Tpl::showpage('goods_recommend','null_layout');
                }
            }
        }
    }
}