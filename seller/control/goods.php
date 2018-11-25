<?php

/**
 * 商品
 *
 *
 *
 */







defined('Inshopec') or exit('Access Invalid!');

class goodsControl extends mobileHomeControl{



    public function __construct() {

        parent::__construct();

    }

    public function listOp(){

        Tpl::output('web_seo',C('site_name').' - '.'商品列表');

        Tpl::showpage('product_list');

    }

    public function detailOp(){

        $goods_id = intval($_GET ['goods_id']);

        // 商品详细信息

        $model_goods = Model('goods');

        $goods_detail = $model_goods->getGoodsDetail($goods_id);



        Tpl::output('web_seo',C('site_name').' - '.$goods_detail['goods_info']['goods_name']);

        Tpl::showpage('product_detail');

    }

    //商品搜索

    public function searchOp(){

        Tpl::output('web_seo',C('site_name').' - '.'商品搜索');

        Tpl::showpage('search');

    }



    

    /**
     * 商品列表
     */

    public function goods_listOp() {

        $model_goods = Model('goods');

        $model_search = Model('search');

        $_GET['is_book'] = 0;



        //查询条件

        $condition = array();

        // ==== 暂时不显示定金预售商品，手机端未做。  ====

        $condition['is_book'] = 0;

        // ==== 暂时不显示定金预售商品，手机端未做。  ====

        if(!empty($_GET['gc_id']) && intval($_GET['gc_id']) > 0) {

            $condition['gc_id'] = $_GET['gc_id'];

        } elseif (!empty($_GET['keyword'])) {

            $condition['goods_name|goods_jingle'] = array('like', '%' . $_GET['keyword'] . '%');

			

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

            setNcCookie('his_sh', implode('~', $his_sh_list),2592000); //添加历史纪录

        } elseif (!empty($_GET['barcode'])) {

            $condition['goods_barcode'] = $_GET['barcode'];

        } elseif (!empty($_GET['b_id']) && intval($_GET['b_id'] > 0)) {

            $condition['brand_id'] = intval($_GET['b_id']);

        }

		//店铺服务

		if ($_GET['ci'] && $_GET['ci'] != 0) {

            //处理参数

            $search_ci= $_GET['ci'];

            $search_ci_arr = explode('_',$search_ci);

            $search_ci_str = $search_ci.'_';

            $indexer_searcharr['search_ci_arr'] = $search_ci_arr;

        }



		if (!empty($_GET['price_from']) && intval($_GET['price_from'] > 0)) {

            $condition['goods_price'][] = array('egt',intval($_GET['price_from']));

        }

		if (!empty($_GET['price_to']) && intval($_GET['price_to'] > 0)) {

            $condition['goods_price'][] = array('elt',intval($_GET['price_to']));

        }

		if (intval($_GET['area_id']) > 0) {

			$condition['areaid_1'] = intval($_GET['area_id']);

		}

		

		//赠品

		if ($_GET['gift'] == 1) {

			$condition['have_gift'] = 1;

		}

		//特卖

		if ($_GET['groupbuy'] == 1) {

			$condition['goods_promotion_type'][] = 1;

		}

		//限时折扣

		if ($_GET['xianshi'] == 1) {

			$condition['goods_promotion_type'][] = 2;

		}

		//虚拟

		if ($_GET['virtual'] == 1) {

			$condition['is_virtual'] = 2;

		}

        //推荐

        if ($_GET['tag']) {

            $condition['tag'] = intval($_GET['tag']);

        }



		

		



        //所需字段

        $fieldstr = "goods_id,goods_commonid,store_id,goods_name,goods_price,goods_promotion_price,goods_promotion_type,goods_marketprice,goods_image,goods_salenum,evaluation_good_star,evaluation_count";



        // 添加3个状态字段

       $fieldstr .= ',is_virtual,is_presell,is_fcode,have_gift,goods_jingle,store_id,store_name,is_own_shop';



        //排序方式

        $order = $this->_goods_list_order($_GET['key'], $_GET['order']);

		 //全文搜索搜索参数

        $indexer_searcharr = $_GET;

        //搜索消费者保障服务

        $search_ci_arr = array();

        $_GET['ci'] = trim($_GET['ci'],'_');

        if ($_GET['ci'] && $_GET['ci'] != 0 && is_string($_GET['ci'])) {

            //处理参数

            $search_ci= $_GET['ci'];

            $search_ci_arr = explode('_',$search_ci);

            $indexer_searcharr['search_ci_arr'] = $search_ci_arr;

        }

        if ($_GET['own_shop'] == 1) {

            $indexer_searcharr['type'] = 1;

        }

        $indexer_searcharr['price_from'] = $price_from;

        $indexer_searcharr['price_to'] = $price_to;



        //优先从全文索引库里查找

        list($goods_list,$indexer_count) = $model_search->indexerSearch($_GET,$this->page);

        if (!is_null($goods_list)) {

            $goods_list = array_values($goods_list);

            pagecmd('setEachNum',$this->page);

            pagecmd('setTotalNum',$indexer_count);

        } else {

            $goods_list = $model_goods->getGoodsListByColorDistinct($condition, $fieldstr, $order, $this->page);

        }

        $page_count = $model_goods->gettotalpage();

        //处理商品列表(特卖、限时折扣、商品图片)

        $goods_list = $this->_goods_list_extend($goods_list);



		

        output_data(array('goods_list' => $goods_list), mobile_page($page_count));

    }



        /**

     * 商品列表排序方式

     */

    private function _goods_list_order($key, $order) {

        $result = 'is_own_shop desc,goods_id desc';

        if (!empty($key)) {



            $sequence = 'desc';

            if($order == 1) {

                $sequence = 'asc';

            }



            switch ($key) {

                //销量

                case '1' :

                    $result = 'goods_salenum' . ' ' . $sequence;

                    break;

                //浏览量

                case '2' :

                    $result = 'goods_click' . ' ' . $sequence;

                    break;

                //价格

                case '3' :

                    $result = 'goods_price' . ' ' . $sequence;

                    break;

            }

        }

        return $result;

    }



    /**

     * 处理商品列表(特卖、限时折扣、商品图片)

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

     * 商品详细页

     */

    public function goods_detailOp() {

      $goods_id = intval($_GET ['goods_id']);



        // 商品详细信息

        $model_goods = Model('goods');

        $goods_detail = $model_goods->getGoodsDetail($goods_id);



        if (empty($goods_detail)) {

            output_error('商品不存在');

        }


        // 默认预订商品不支持手机端显示

        if ($goods_detail['is_book']) {

            output_error('预订商品不支持手机端显示');

        }



        //推荐商品

        $model_store = Model('store');

        $hot_sales = $model_store->getHotSalesList($goods_detail['goods_info']['store_id'], 8, true);

        $goodsid_array = array();

        foreach($hot_sales as $value) {

            $goodsid_array[] = $value['goods_id'];

        }

        $sole_array = Model('p_sole')->getSoleGoodsList(array('goods_id' => array('in', $goodsid_array)));



        $sole_array = array_under_reset($sole_array, 'goods_id');

        $goods_commend_list = array();

        foreach($hot_sales as $value) {

            $goods_commend = array();

            $goods_commend['goods_id'] = $value['goods_id'];

            $goods_commend['goods_name'] = $value['goods_name'];

            $goods_commend['goods_price'] = $value['goods_price'];

            $goods_commend['goods_promotion_price'] = $value['goods_promotion_price'];

            if (!empty($sole_array[$value['goods_id']])) {

                $goods_commend['goods_promotion_price'] = $sole_array[$value['goods_id']]['sole_price'];

            }

            $goods_commend['goods_image_url'] = cthumb($value['goods_image'], 240);

            $goods_commend_list[] = $goods_commend;

        }

        

        $goods_detail['goods_commend_list'] = $goods_commend_list;

        $store_info = $model_store->getStoreInfoByID($goods_detail['goods_info']['store_id']);

        $goods_detail['store_info']['store_id'] = $store_info['store_id'];

        $goods_detail['store_info']['store_name'] = $store_info['store_name'];

        $goods_detail['store_info']['member_id'] = $store_info['member_id'];

        $goods_detail['store_info']['member_name'] = $store_info['member_name'];

         $goods_detail['store_info']['store_qq'] = $store_info['store_qq'];

        $goods_detail['store_info']['avatar'] = getMemberAvatarForID($store_info['member_id']);

        $goods_detail['store_info']['goods_count'] = $store_info['goods_count'];

        if ($store_info['is_own_shop']) {

            $goods_detail['store_info']['store_credit'] = array(

                'store_desccredit' => array (

                    'text' => '描述',

                    'credit' => 5,

                    'percent' => '----',

                    'percent_class' => 'equal',

                    'percent_text' => '平',

                ),

                'store_servicecredit' => array (

                    'text' => '服务',

                    'credit' => 5,

                    'percent' => '----',

                    'percent_class' => 'equal',

                    'percent_text' => '平',

                ),

                'store_deliverycredit' => array (

                    'text' => '物流',

                    'credit' => 5,

                    'percent' => '----',

                    'percent_class' => 'equal',

                    'percent_text' => '平',

                ),

            );

        } else {

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

        }



        //商品详细信息处理

        $goods_detail = $this->_goods_detail_extend($goods_detail);



        // 如果已登录 判断该商品是否已被收藏

        if ($memberId = $this->getMemberIdIfExists()) {

            $c = (int) Model('favorites')->getGoodsFavoritesCountByGoodsId($goods_id, $memberId);

            $goods_detail['is_favorate'] = $c > 0;

            $goods_detail['cart_count'] = Model('cart')->countCartByMemberId($memberId);

        }

        $goods_detail['goods_hair_info'] = array('content'=>'免运费','if_store_cn'=>'有货','if_store'=>true,'area_name'=>'全国');

        $goods_detail['goods_evaluate_info'] = array('good'=>0,'normal'=>0,'bad'=>0,'all'=>0,'img'=>0,'good_percent'=>100,'normal_percent'=>0,'bad_percent'=>0,'good_star'=>5,'star_average'=>5);

        $goods_detail['goods_eval_list']='';



        if($goods_detail){

            $model_goods_browse = Model('goods_browse')->addViewedGoods($goods_id,$memberId); //加入浏览历史数据库

            output_data($goods_detail);

        }

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



    /**

     * 商品详细页

     */

    public function goods_bodyOp() {

        header("Access-Control-Allow-Origin:*");

        $goods_id = intval($_GET ['goods_id']);



        $model_goods = Model('goods');



        $goods_info = $model_goods->getGoodsInfoByID($goods_id, 'goods_commonid');

        $goods_common_info = $model_goods->getGoodsCommonInfoByID($goods_info['goods_commonid']);

        

        Tpl::output('goods_id', $goods_id);

        Tpl::output('goods_common_info', $goods_common_info);

        Tpl::showpage('product_info');

    }



	

	public function auto_completeOp() {

        p(cookie('his_sh'));die;

		if ($_GET['term'] == '' && cookie('his_sh') != '') {

            $corrected = explode('~', cookie('his_sh'));

            if ($corrected != '' && count($corrected) !== 0) {

                $data = array();

                foreach ($corrected as $word)

                {

                    $row['id'] = $word;

                    $row['label'] = $word;

                    $row['value'] = $word;

                    $data[] = $row;

                }

                output_data($data);

            }

            return;

        }

		

        if (!C('fullindexer.open')) return;

		//output_error('1000');

        try {

            require(BASE_DATA_PATH.'/api/xs/lib/XS.php');

            $obj_doc = new XSDocument();

            $obj_xs = new XS(C('fullindexer.appname'));

            $obj_index = $obj_xs->index;

            $obj_search = $obj_xs->search;

            $obj_search->setCharset(CHARSET);

            $corrected = $obj_search->getExpandedQuery($_GET['term']);

            if (count($corrected) !== 0) {

                $data = array();

                foreach ($corrected as $word)

                {

                    $row['id'] = $word;

                    $row['label'] = $word;

                    $row['value'] = $word;

                    $data[] = $row;

                }

                output_data($data);

            }

        } catch (XSException $e) {

            if (is_object($obj_index)) {

                $obj_index->flushIndex();

            }

			output_error($e->getMessage());

			//             Log::record('search\auto_complete'.$e->getMessage(),Log::RUN);

        }

		

		

	}

      /**

     * 商品详细页运费显示

     *

     * @return unknown

     */

    public function calcOp(){

        $area_id = intval($_GET['area_id']);

        $goods_id = intval($_GET['goods_id']);

        output_data($this->_calc($area_id, $goods_id));

    }



    public function _calc($area_id,$goods_id){

        $goods_info = Model('goods')->getGoodsInfo(array('goods_id'=>$goods_id),'transport_id,store_id,goods_freight');

        $store_info = Model('store')->getStoreInfoByID($goods_info['store_id']);

        if ($area_id <= 0) {

            if (strpos($store_info['deliver_region'],'|')) {

                $store_info['deliver_region'] = explode('|', $store_info['deliver_region']);

                $store_info['deliver_region_ids'] = explode(' ', $store_info['deliver_region'][0]);

            }

            $area_id = intval($store_info['deliver_region_ids'][1]);

            $area_name = $store_info['deliver_region'][1];

        }

        if ($goods_info['transport_id'] && $area_id > 0) {

            $freight_total = Model('transport')->calc_transport(intval($goods_info['transport_id']),$area_id);

            if ($freight_total > 0) {

                if ($store_info['store_free_price'] > 0) {

                    if ($freight_total >= $store_info['store_free_price']) {

                        $freight_total = '免运费';

                    } else {

                        $freight_total = '运费：'.$freight_total.' 元，店铺满 '.$store_info['store_free_price'].' 元 免运费';

                    }

                } else {

                    $freight_total = '运费：'.$freight_total.' 元';

                }

            } else {

                if ($freight_total === false) {

                    $if_store = false;

                }

                $freight_total = '免运费';

            }     

        } else {

            $freight_total = $goods_info['goods_freight'] > 0 ? '运费：'.$goods_info['goods_freight'].' 元' : '免运费';

        }



        return array('content'=>$freight_total,'if_store_cn'=>$if_store === false ? '无货' : '有货','if_store'=>$if_store === false ? false : true,'area_name'=>$area_name ? $area_name : '全国');

    }



	   /*分店地址*/

        public function store_o2o_addrOp(){

            $store_id = intval($_GET ['store_id']);

            $model_store_map = Model('store_map');

            $addr_list_source = $model_store_map->getStoreMapList($store_id);

            foreach ($addr_list_source as $k => $v) {

            	$addr_list_tmp = array();

            	$addr_list_tmp['key'] = $k;

            	$addr_list_tmp['map_id'] = $v['map_id'];

            	$addr_list_tmp['name_info'] = $v['name_info'];

            	$addr_list_tmp['address_info'] = $v['address_info'];

            	$addr_list_tmp['phone_info'] = $v['phone_info'];

            	$addr_list_tmp['bus_info'] = $v['bus_info'];

            	$addr_list_tmp['province'] = $v['baidu_province'];

            	$addr_list_tmp['city'] = $v['baidu_city'];

            	$addr_list_tmp['district'] = $v['baidu_district'];

            	$addr_list_tmp['street'] = $v['baidu_street'];

            	$addr_list_tmp['lng'] = $v['baidu_lng'];

            	$addr_list_tmp['lat'] = $v['baidu_lat'];

            	$addr_list[] = $addr_list_tmp;

            }

            output_data(array('addr_list'=>$addr_list));

        }





    /**

     * 商品评价视图

     */

    public function goods_evaluateOp(){

        Tpl::output('web_seo',C('site_name').' - '.'商品评价');

        Tpl::showpage('product_eval_list');

    }



	/**

     * 商品评价

     */

    public function get_goods_evaluateOp() {

		$goods_id = intval($_GET['goods_id']);

		if($goods_id <=0){

			output_error('产品不存在');

		}

		

		

        $goodsevallist = $this->_get_comments($goods_id, $_GET['type'], $this->page);	

		$model_evaluate_goods = Model("evaluate_goods");

		$page_count = $model_evaluate_goods->gettotalpage();		

		output_data(array('goods_eval_list'=>$goodsevallist),mobile_page($page_count));

	

	}



	private function _get_comments($goods_id, $type, $page) {

        $condition = array();

        $condition['geval_goodsid'] = $goods_id;

        switch ($type) {

            case '1':

                $condition['geval_scores'] = array('in', '5,4');

                Tpl::output('type', '1');

                break;

            case '2':

                $condition['geval_scores'] = array('in', '3,2');

                Tpl::output('type', '2');

                break;

            case '3':

                $condition['geval_scores'] = array('in', '1');

                Tpl::output('type', '3');

                break;

        }



       //查询商品评分信息

        $model_evaluate_goods = Model("evaluate_goods");

        $goods_eval_list = $model_evaluate_goods->getEvaluateGoodsList($condition, 10);

        $goods_eval_list = Logic('member_evaluate')->evaluateListDity($goods_eval_list);

        foreach($goods_eval_list as $key => $val){

            $goods_eval_list[$key]['geval_addtime_date'] = date('Y-m-d', $val['geval_addtime']);

            if($val['geval_isanonymous'] == 1){

                $goods_eval_list[$key]['geval_frommembername'] = str_cut($val['geval_frommembername'],2).'***';

            }

            $image_array = explode(',', $val['geval_image']);

            foreach($image_array as $k => $v){

                $goods_eval_list[$key]['geval_image_240'][$k] = snsThumb($v, 240);

                $goods_eval_list[$key]['geval_image_1024'][$k] = snsThumb($v, 1024);

            }

        }

		return $goods_eval_list;

		//Tpl::output('goodsevallist',$goodsevallist);

        //Tpl::output('show_page',$model_evaluate_goods->showpage('5'));

    }

}

