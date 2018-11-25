<?php
/**
 * 店铺
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
class storeControl extends mobileHomeControl
{
    public function __construct()
    {
        parent::__construct();
    }
    //文章状态草稿箱
    const ARTICLE_STATE_DRAFT = 1;
    //文章状态待审核
    const ARTICLE_STATE_VERIFY = 2;
    //文章状态已发布
    const ARTICLE_STATE_PUBLISHED = 3;
    //文章状态回收站
    const ARTICLE_STATE_RECYCLE = 4;
    //推荐
    const COMMEND_FLAG_TRUE = 1;
    //文章评论类型
    const ARTICLE = 1;
    const PICTURE = 2;    
    /**
     * 处理文章信息
     */
    private function _cms_list_extend($cms_id) {
        $model_article = Model('cms_article');
        $article_detail = $model_article->getOne(array('article_id'=>$cms_id));
        return $article_detail;    	
    }
    /**
     * 店铺信息
     */
    public function store_infoOp()
    {
        $store_id = (int) $_REQUEST['store_id'];
        if ($store_id <= 0) {
            output_error('参数错误');
        }
        $store_online_info = Model('store')->getStoreOnlineInfoByID($store_id);

        if (empty($store_online_info)) {
            output_error('店铺不存在或未开启');
        }
   
        if($store_online_info['member_id']){
            setNcCookie('inviter_id', $store_online_info['member_id']);
        }

        $store_info = array();
        $store_info['store_id'] = $store_online_info['store_id'];
        $store_info['store_name'] = $store_online_info['store_name'];
        $store_info['member_id'] = $store_online_info['member_id'];
        $store_info['mb_store_decoration_switch'] = $store_online_info['mb_store_decoration_switch'];
        //是否开启装修
        if($store_info['mb_store_decoration_switch'] == 1){
        	$model_mb_special = Model('mb_store_special');
            $data = $model_mb_special->getMbSpecialIndex($store_online_info['store_id']);
  
            foreach($data as $kkk=>$vvv){
                if(is_array($vvv['store_announcement']['item'])){
                	
                	foreach($vvv['store_announcement']['item'] as $kk=>$vv){
                        $cmss = $this->_cms_list_extend($vv['article_id']);
                        $data[$kkk]['store_announcement']['item'][$kk]['article_publish_time'] = date("Y-m-d ", time($cmss['article_publish_time']));
                        $data[$kkk]['store_announcement']['item'][$kk]['article_click'] = $cmss['article_click'];
                		if(is_array($vv['article_image'])){
                			$data[$kkk]['store_announcement']['item'][$kk]['article_image'] = getCMSArticleImageUrl($vv['article_attachment_path'], $vv['article_image']['name']);
                		}else{
                			$data[$kkk]['store_announcement']['item'][$kk]['article_image'] = $vv['image'];
                		}
                	}	
                }
            		
            }
            
            
            
            
            

            $store_info['decoration'] = $data;
     
        }
        //店铺导航
            $model_store_decoration = Model('mb_store_decoration');
            $store_decoration_info = $model_store_decoration->getStoreDecorationInfo(array('store_id' => $store_id));
            $store_info['store_imgname']['on'] = $store_decoration_info['mb_store_navigation'];
			//
			$store_nav = @unserialize($store_decoration_info['decoration_nav']);
			if(is_array($store_nav)){
				foreach($store_nav as $kt => $vt){
					if($vt['img']){
						$cca[$kt]['img'] = UPLOAD_SITE_URL.DS.ATTACH_STORE.DS.$vt['img'];
						$cca[$kt]['name'] = $vt['name'];
						$cca[$kt]['links'] = $vt['links'];
					}else{
						$cca[$kt]['img'] = '';
						$cca[$kt]['name'] = $vt['name'];
						$cca[$kt]['links'] = $vt['links'];
					}
					
				}
			}

            $store_info['store_imgname']['data'] = 	   $cca;     
        //店铺底部菜单
        $store_info['mb_store_menu']['on']= $store_decoration_info['mb_store_menu'];
        $store_info['mb_store_menu']['data'] = @unserialize($store_decoration_info['decoration_banner']);
		$store_info['mb_store_menu']['nou'] = count($store_info['mb_store_menu']['data']);
        


        // 店铺头像
        $store_info['store_avatar'] = $store_online_info['store_avatar']
            ? UPLOAD_SITE_URL.'/'.ATTACH_STORE.'/'.$store_online_info['store_avatar']
            : UPLOAD_SITE_URL.'/'.ATTACH_COMMON.DS.C('default_store_avatar');

        // 商品数
        $store_info['goods_count'] = (int) $store_online_info['goods_count'];

        // 店铺被收藏次数
        $store_info['store_collect'] = (int) $store_online_info['store_collect'];

        // 如果已登录 判断该店铺是否已被收藏
        if ($memberId = $this->getMemberIdIfExists()) {
            $c = (int) Model('favorites')->getStoreFavoritesCountByStoreId($store_id, $memberId);
            $store_info['is_favorate'] = $c > 0;
        } else {
            $store_info['is_favorate'] = false;
        }

        // 是否官方店铺
        $store_info['is_own_shop'] = (bool) $store_online_info['is_own_shop'];

        // 动态评分
        if ($store_info['is_own_shop']) {
            $store_info['store_credit_text'] = '官方店铺';
        } else {
            $store_info['store_credit_text'] = sprintf(
                '描述: %0.1f, 服务: %0.1f, 物流: %0.1f',
                $store_online_info['store_credit']['store_desccredit']['credit'],
                $store_online_info['store_credit']['store_servicecredit']['credit'],
                $store_online_info['store_credit']['store_deliverycredit']['credit']
            );
        }

        // 页头背景图
        $store_info['mb_title_img'] = $store_online_info['mb_title_img']
            ? UPLOAD_SITE_URL.'/'.ATTACH_STORE.'/'.$store_online_info['mb_title_img']
            : '';

        // 轮播
        $store_info['mb_sliders'] = array();
        $mbSliders = @unserialize($store_online_info['mb_sliders']);
        if ($mbSliders) {
            foreach ((array) $mbSliders as $s) {
                if ($s['img']) {
                    $s['imgUrl'] = UPLOAD_SITE_URL.DS.ATTACH_STORE.DS.$s['img'];
                    $store_info['mb_sliders'][] = $s;
                }
            }
        }

        //店主推荐
        $where = array();
        $where['store_id'] = $store_id;
        $where['goods_commend'] = 1;
        $where['is_book'] = 0;// 默认不显示预订商品
        $model_goods = Model('goods');
        $goods_fields = $this->getGoodsFields();
        $goods_list = $model_goods->getGoodsListByColorDistinct($where, $goods_fields, 'goods_id desc', 0, 20);
        $goods_list = $this->_goods_list_extend($goods_list);
        $seo_param = array();
        $seo_param['shopname'] = $store_online_info['store_name'];
        $seo_param['key']  = $store_online_info['store_keywords'];
        $seo_param['description'] = $store_online_info['store_description'];

        $store_seo = Model('seo')->type('shop')->param($seo_param)->showwap();
    
        output_data(array(
            'store_info' => $store_info,
            'store_seo' => $store_seo,
            'rec_goods_list_count' => count($goods_list),
            'rec_goods_list' => $goods_list,
        ));
    }
    /**
     * 获取文章相关文章
     */
    protected function get_article_link_list($article_link) {
        $article_link_list = array();
        if(!empty($article_link)) {
            $model_article = Model('cms_article');
            $condition = array();
            $condition['article_id'] = array('in',$article_link);
            $condition['article_state'] = self::ARTICLE_STATE_PUBLISHED;
            $article_link_list = $model_article->getList($condition , NULL, 'article_id desc');
        }
        return $article_link_list;
    }    
    /**
     * 文章处理
     */
    public function store_detailOp()
    {
        $article_id = intval($_GET['article_id']);
//      if($article_id <= 0) {
//          showMessage(Language::get('wrong_argument'),'','','error');
//      }

        $model_article = Model('cms_article');
        $article_detail = $model_article->getOne(array('article_id'=>$article_id));
        $article_detail['article_publish_time']= date("Y-m-d ", time($article_detail['article_publish_time']));

        //相关文章 
        $article_link_list = $this->get_article_link_list($article_detail['article_link']);
        
        foreach($article_link_list as $ke =>$ve){
        	if($ve['article_image']){
        		$cc = unserialize($ve['article_image']);
        	}else{
        		$cc['article_image'] = '';
        	}
        	$article_link_list[$ke]['article_publish_time'] = date("Y-m-d ", time($ve['article_publish_time']));
            if($cc['article_image']){
                $article_link_list[$ke]['image'] = getCMSArticleImageUrl($ve['article_attachment_path'], $cc['article_image']['name']);
            }else{
                $article_link_list[$ke]['image'] = getCMSArticleImageUrl($ve['article_attachment_path'], $ve['article_image']);
            }        	

        }

        //相关商品 
        $article_goods_list = unserialize($article_detail['article_goods']);


        //计数加1
        $model_article->modify(array('article_click'=>array('exp','article_click+1')),array('article_id'=>$article_id));

//      //文章心情
//      $article_attitude_list = array();
//      $article_attitude_list[1] = Language::get('attitude1');
//      $article_attitude_list[2] = Language::get('attitude2');
//      $article_attitude_list[3] = Language::get('attitude3');
//      $article_attitude_list[4] = Language::get('attitude4');
//      $article_attitude_list[5] = Language::get('attitude5');
//      $article_attitude_list[6] = Language::get('attitude6');
//      Tpl::output('article_attitude_list', $article_attitude_list);

//      //分享
//      $this->get_share_app_list();
//
//      Tpl::output('article_detail', $article_detail);
//      Tpl::output('detail_object_id', $article_id);
//      $this->get_article_sidebar();
//
        $seo['html_title'] = $article_detail['article_title'].'-'.C('cms_seo_title').'-'.C('site_name');
        $seo['seo_keywords'] = C('cms_seo_keywords');
        $seo['seo_description'] = C('cms_seo_description');

        output_data(array(
            'article_detail' => $article_detail,
            'detail_object_id' => $article_id,
            'article_link_list' => $article_link_list,
            'article_goods_list' => $article_goods_list,
            'seo_title'=> $seo
            
        ));
    }
    /**
     * 店铺商品分类
     */
    public function store_goods_classOp()
    {
        $store_id = (int) $_REQUEST['store_id'];
        if ($store_id <= 0) {
            output_error('参数错误');
        }
        $store_online_info = Model('store')->getStoreOnlineInfoByID($store_id);
        if (empty($store_online_info)) {
            output_error('店铺不存在或未开启');
        }

        $store_info = array();
        $store_info['store_id'] = $store_online_info['store_id'];
        $store_info['store_name'] = $store_online_info['store_name'];

       $store_goods_class = Model('store_goods_class')->getStoreGoodsClassPlainList($store_id);

        output_data(array(
            'store_info' => $store_info,
            'store_goods_class' => $store_goods_class
        ));
    }

    /**
     * 店铺商品
     */
    public function store_goodsOp()
    {
        $param = $_REQUEST;

        $store_id = (int) $param['store_id'];
        if ($store_id <= 0) {
            output_error('参数错误');
        }
        $stc_id = (int) $param['stc_id'];
        $keyword = trim((string) $param['keyword']);

        $condition = array();
        $condition['store_id'] = $store_id;

        // 默认不显示预订商品
        $condition['is_book'] = 0;

        if ($stc_id > 0){
            $condition['goods_stcids'] = array('like', '%,' . $stc_id . ',%');
        }
        //促销类型
        if ($param['prom_type']) {
            switch($param['prom_type']){
                case 'xianshi':
                    $condition['goods_promotion_type'] = 2;
                    break;
                case 'groupbuy':
                    $condition['goods_promotion_type'] = 1;
                    break;
            }
        }
        if ($keyword != '') {
            $condition['goods_name'] = array('like', '%'.$keyword.'%');
        }
        $price_from = preg_match('/^[\d.]{1,20}$/',$param['price_from']) ? $param['price_from'] : null;
        $price_to = preg_match('/^[\d.]{1,20}$/',$param['price_to']) ? $param['price_to'] : null;
        if ($price_from && $price_from) {
            $condition['goods_promotion_price'] = array('between',"{$price_from},{$price_to}");
        } elseif ($price_from) {
            $condition['goods_promotion_price'] = array('egt',$price_from);
        } elseif ($price_to) {
            $condition['goods_promotion_price'] = array('elt',$price_to);
        }

        // 排序
        $order = (int) $param['order'] == 1 ? 'asc' : 'desc';
        switch (trim($param['key'])) {
            case '1':
                $order = 'goods_id '.$order;
                break;
            case '2':
                $order = 'goods_promotion_price '.$order;
                break;
            case '3':
                $order = 'goods_salenum '.$order;
                break;
            case '4':
                $order = 'goods_collect '.$order;
                break;
            case '5':
                $order = 'goods_click '.$order;
                break;
            default:
                $order = 'goods_id desc';
                break;
        }

        $model_goods = Model('goods');

        $goods_fields = $this->getGoodsFields();
        $goods_list = $model_goods->getGoodsListByColorDistinct($condition, $goods_fields, $order, $this->page);
        $page_count = $model_goods->gettotalpage();

        $goods_list = $this->_goods_list_extend($goods_list);

        output_data(array(
            'goods_list_count' => count($goods_list),
            'goods_list' => $goods_list,
        ), mobile_page($page_count));
    }

    private function getGoodsFields()
    {
        return implode(',', array(
            'goods_id',
            'goods_commonid',
            'store_id',
            'store_name',
            'goods_name',
            'goods_price',
            'goods_promotion_price',
            'goods_promotion_type',
            'goods_marketprice',
            'goods_image',
            'goods_salenum',
            'evaluation_good_star',
            'evaluation_count',
            'is_virtual',
            'is_presell',
            'is_fcode',
            'have_gift',
            'goods_addtime',
        ));
    }

    /**
     * 处理商品列表(团购、限时折扣、商品图片)
     */
    private function _goods_list_extend($goods_list) {
        //获取商品列表编号数组
        $goodsid_array = array();
        foreach($goods_list as $key => $value) {
            $goodsid_array[] = $value['goods_id'];
        }

        $sole_array = Model('p_sole')->getSoleGoodsList(array('goods_id' => array('in', $goodsid_array)));
        $sole_array = array_under_reset($sole_array, 'goods_id');

        foreach ($goods_list as $key => $value) {
            $goods_list[$key]['sole_flag']      = false;
            $goods_list[$key]['group_flag']     = false;
            $goods_list[$key]['xianshi_flag']   = false;
            if (!empty($sole_array[$value['goods_id']])) {
                $goods_list[$key]['goods_price'] = $sole_array[$value['goods_id']]['sole_price'];
                $goods_list[$key]['sole_flag'] = true;
            } else {
                $goods_list[$key]['goods_price'] = $value['goods_promotion_price'];
                switch ($value['goods_promotion_type']) {
                    case 1:
                        $goods_list[$key]['group_flag'] = true;
                        break;
                    case 2:
                        $goods_list[$key]['xianshi_flag'] = true;
                        break;
                }

            }

            //商品图片url
            $goods_list[$key]['goods_image_url'] = cthumb($value['goods_image'], 360, $value['store_id']);

            unset($goods_list[$key]['goods_promotion_type']);
            unset($goods_list[$key]['goods_promotion_price']);
            unset($goods_list[$key]['goods_commonid']);
            unset($goods_list[$key]['nc_distinct']);
        }

        return $goods_list;
    }

    /**
     * 商品评价
     */
    public function store_creditOp() {
        $store_id = intval($_GET['store_id']);
        if ($store_id <= 0) {
            output_error('参数错误');
        }
        $store_online_info = Model('store')->getStoreOnlineInfoByID($store_id);
        if (empty($store_online_info)) {
            output_error('店铺不存在或未开启');
        }

        output_data(array('store_credit' => $store_online_info['store_credit']));
    }
    /**
     * 店铺商品排行
     */
    public function store_goods_rankOp()
    {
        $store_id = (int) $_REQUEST['store_id'];
        if ($store_id <= 0) {
            output_data(array());
        }
        $ordertype = ($t = trim($_REQUEST['ordertype']))?$t:'salenumdesc';
        $show_num = ($t = intval($_REQUEST['num']))>0?$t:10;

        $where = array();
        $where['store_id'] = $store_id;
        // 默认不显示预订商品
        $where['is_book'] = 0;
        // 排序
        switch ($ordertype) {
            case 'salenumdesc':
                $order = 'goods_salenum desc';
                break;
            case 'salenumasc':
                $order = 'goods_salenum asc';
                break;
            case 'collectdesc':
                $order = 'goods_collect desc';
                break;
            case 'collectasc':
                $order = 'goods_collect asc';
                break;
            case 'clickdesc':
                $order = 'goods_click desc';
                break;
            case 'clickasc':
                $order = 'goods_click asc';
                break;
        }
        if ($order) {
            $order .= ',goods_id desc';
        }else{
            $order = 'goods_id desc';
        }
        $model_goods = Model('goods');
        $goods_fields = $this->getGoodsFields();
        $goods_list = $model_goods->getGoodsListByColorDistinct($where, $goods_fields, $order, 0, $show_num);
        $goods_list = $this->_goods_list_extend($goods_list);
        output_data(array('goods_list' => $goods_list));
    }
    /**
     * 店铺商品上新
     */
    public function store_new_goodsOp(){
        $store_id = (int) $_REQUEST['store_id'];
        if ($store_id <= 0) {
            output_data(array('goods_list'=>array()));
        }
        $show_day = ($t = intval($_REQUEST['show_day']))>0?$t:30;
        $where = array();
        $where['store_id'] = $store_id;
        $where['is_book'] = 0;//默认不显示预订商品
        $stime = strtotime(date('Y-m-d',time() - 86400*$show_day));
        $etime = $stime + 86400*($show_day+1);
        $where['goods_addtime'] = array('between',array($stime,$etime));
        $order = 'goods_addtime desc, goods_id desc';
        $model_goods = Model('goods');
        $goods_fields = $this->getGoodsFields();
        $goods_list = $model_goods->getGoodsListByColorDistinct($where, $goods_fields, $order, $this->page);
        $page_count = $model_goods->gettotalpage();
        if ($goods_list) {
            $goods_list = $this->_goods_list_extend($goods_list);
            foreach($goods_list as $k=>$v){
                $v['goods_addtime_text'] = $v['goods_addtime']?@date('Y年m月d日',$v['goods_addtime']):'';
                $goods_list[$k] = $v;
            }
        }
        output_data(array('goods_list' => $goods_list),mobile_page($page_count));
    }
    /**
     * 店铺简介
     */
    public function store_introOp()
    {
        $store_id = (int) $_REQUEST['store_id'];
        if ($store_id <= 0) {
            output_error('参数错误');
        }
        $store_online_info = Model('store')->getStoreOnlineInfoByID($store_id);
        if (empty($store_online_info)) {
            output_error('店铺不存在或未开启');
        }
        $store_info = $store_online_info;
        //开店时间
        $store_info['store_time_text'] = $store_info['store_time']?@date('Y-m-d',$store_info['store_time']):'';
        // 店铺头像
        $store_info['store_avatar'] = $store_online_info['store_avatar']
            ? UPLOAD_SITE_URL.'/'.ATTACH_STORE.'/'.$store_online_info['store_avatar']
            : UPLOAD_SITE_URL.'/'.ATTACH_COMMON.DS.C('default_store_avatar');
        //商品数
        $store_info['goods_count'] = (int) $store_online_info['goods_count'];
        //店铺被收藏次数
        $store_info['store_collect'] = (int) $store_online_info['store_collect'];
        //店铺所属分类
        $store_class = Model('store_class')->getStoreClassInfo(array('sc_id' => $store_info['sc_id']));
        $store_info['sc_name'] = $store_class['sc_name'];
        //如果已登录 判断该店铺是否已被收藏
        if ($member_id = $this->getMemberIdIfExists()) {
            $c = (int) Model('favorites')->getStoreFavoritesCountByStoreId($store_id, $member_id);
            $store_info['is_favorate'] = $c > 0?true:false;
        } else {
            $store_info['is_favorate'] = false;
        }
        // 是否官方店铺
        $store_info['is_own_shop'] = (bool) $store_online_info['is_own_shop'];
        // 页头背景图
        $store_info['mb_title_img'] = $store_online_info['mb_title_img'] ? UPLOAD_SITE_URL.'/'.ATTACH_STORE.'/'.$store_online_info['mb_title_img'] : '';
        // 轮播
        $store_info['mb_sliders'] = array();
        $mbSliders = @unserialize($store_online_info['mb_sliders']);
        if ($mbSliders) {
            foreach ((array) $mbSliders as $s) {
                if ($s['img']) {
                    $s['imgUrl'] = UPLOAD_SITE_URL.DS.ATTACH_STORE.DS.$s['img'];
                    $store_info['mb_sliders'][] = $s;
                }
            }
        }
        output_data(array('store_info' => $store_info));
    }
    /**
     * 店铺促销活动
     */
    public function store_promotionOp(){
        $param = $_REQUEST;
        $store_id = (int) $param['store_id'];
        if ($store_id <= 0) {
            output_error('参数错误');
        }
        $fields_arr = array('mansong','xianshi');
        $fields_str = trim($param['fields']);
        if ($fields_str) {
            $fields_arr = explode(',',$fields_str);
        }
        $promotion_arr = array();
        if (in_array('mansong',$fields_arr)) {
            //满就送
            $mansong_info = Model('p_mansong')->getMansongInfoByStoreID($store_id);
            if ($mansong_info) {
                $mansong_info['start_time_text'] = date('Y-m-d',$mansong_info['start_time']);
                $mansong_info['end_time_text'] = date('Y-m-d',$mansong_info['end_time']);
                foreach($mansong_info['rules'] as $rules_k=>$rules_v){
                    $rules_v['goods_image_url'] = cthumb($rules_v['goods_image'], 60);
                    $rules_v['price'] = ncPriceFormat($rules_v['price']);
                    if ($rules_v['discount']) {
                        $rules_v['discount'] = ncPriceFormat($rules_v['discount']);
                    }
                    $mansong_info['rules'][$rules_k] = $rules_v;
                }
                $promotion_arr['mansong'] = $mansong_info;
            }
        }
        if (in_array('xianshi',$fields_arr)) {
            //限时折扣
            $where = array();
            $where['store_id'] = $store_id;
            $where['state'] = 1;
            $where['start_time'] = array('elt', TIMESTAMP);
            $where['end_time'] = array('egt', TIMESTAMP);
            $xianshi_list = Model('p_xianshi')->getXianshiList($where, 0, 'xianshi_id asc', '*', 1);
            if ($xianshi_list) {
                $xianshi_info = $xianshi_list[0];
                $xianshi_info['start_time_text'] = date('Y-m-d',$xianshi_info['start_time']);
                $xianshi_info['end_time_text'] = date('Y-m-d',$xianshi_info['end_time']);
                $promotion_arr['xianshi'] = $xianshi_info;
            }
        }
        output_data(array('promotion' => $promotion_arr));
    }
/**
 * 店铺列表
 */
 public function store_listOp(){
 	$model_store=Model('store');
 	$condition = array();
	 if(!empty($_GET['sc_id']) ) {
            $condition['sc_id'] = $_GET['sc_id'];
        } elseif (!empty($_GET['keyword'])) {
            $condition['store_name'] = array('like', '%' . $_GET['keyword'] . '%');}
    if (!empty($_GET['area_info'])){
			$condition['area_info'] = array('like','%'.$_GET['area_info'].'%');
		}
	if (!is_null($store_list)) {
            $store_list = array_values($store_list);
            pagecmd('setEachNum',$this->page);
            pagecmd('setTotalNum',$indexer_count);
        } else {
            $store_list = $model_store->getStoreList($condition, $fieldstr, $order, $this->page);
        }
        $page_count = $model_store->gettotalpage();
		foreach($store_list as $k=>$v){
		$storeid=$v['store_id'];
        $store_online_info = Model('store')->getStoreOnlineInfoByID($storeid);
        // 店铺头像
        $store_list[$k]['store_avatar'] = $store_online_info['store_avatar']
            ? UPLOAD_SITE_URL.'/'.ATTACH_STORE.'/'.$store_online_info['store_avatar']
            : UPLOAD_SITE_URL.'/'.ATTACH_COMMON.DS.C('default_store_avatar');

        // 商品数
        $store_list[$k]['goods_count'] = (int) $store_online_info['goods_count'];
		}
        output_data(array('store_list' => $store_list), mobile_page($page_count));
 }
 /**
  * 获取店铺分类
  */

  public function store_class_listOp(){
  	$condition = array();
  	$class_list = Model('store_class')->getStoreClassList($condition);
	output_data(array('class_list'=>$class_list));
  }     
}
