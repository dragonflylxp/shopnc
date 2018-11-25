<?php

use shopec\Tpl;

class ajax_contentControl{
    public function cat_store_listOp(){
	    $smarty = new cls_template();
	    $json = new JSON();
	    $result = array('error' => 0, 'message' => '', 'content' => '');
	    $is_jsonp = (isset($_REQUEST['is_jsonp']) && !empty($_REQUEST['is_jsonp']) ? intval($_REQUEST['is_jsonp']) : 0);
	    $result = array('error' => 0, 'message' => '');
		$smarty->template_dir = BASE_ROOT_PATH.DS.DIR_SHOP.'/templates/'.TPL_NAME;
		$smarty->compile_dir = BASE_ROOT_PATH.DS.DIR_SHOP.'/templates/'.TPL_NAME;	
		$merchant_id = (isset($_REQUEST['merchant_id']) ? intval($_REQUEST['merchant_id']) : 0); 
		$goodsclass_model = Model('store_goods_class');
        $goods_class_list = $goodsclass_model->getShowTreeList($merchant_id);   
        $cat_list = array();
        if(is_array($goods_class_list)){
        	foreach($goods_class_list as $key=>$val){
        		$cat_list[$key]['cat_id']=$val['stc_id'];
        		$cat_list[$key]['cat_name']=$val['stc_name'];
        		$cat_list[$key]['cat_alias_name']=$val['stc_parent_id'];
        		$cat_list[$key]['user_id']=$val['store_id'];
        		$cat_list[$key]['level']= 0;
        		$cat_list[$key]['select']= '';
        		$cat_list[$key]['url']=urlShop('show_store','goods_all',array('store_id'=>$val['store_id'],'stc_id'=>$val['stc_id']));
        		if(is_array($val['children'])){
        			foreach($val['children'] as $k =>$v){
        				$cat_list[$key]['child_tree'][$v['stc_id']]['id']= $v['stc_id'];
        				$cat_list[$key]['child_tree'][$v['stc_id']]['name']= $v['stc_name'];
        				$cat_list[$key]['child_tree'][$v['stc_id']]['url']= urlShop('show_store','goods_all',array('store_id'=>$v['store_id'],'stc_id'=>$v['stc_id']));
        				$cat_list[$key]['child_tree'][$v['stc_id']]['ru_id']= $v['store_id'];
        				$cat_list[$key]['child_tree'][$v['stc_id']]['seller_name']= '';
        				$cat_list[$key]['child_tree'][$v['stc_id']]['level']= 1;
        				$cat_list[$key]['child_tree'][$v['stc_id']]['select']= '&nbsp;&nbsp;&nbsp;&nbsp;';
        				$cat_list[$key]['child_tree'][$v['stc_id']]['cat_id']= array();
        			}
        		}
        	}
        }
        

		$smarty->assign('cat_store_list', $cat_list);
		$result['content'] = $smarty->fetch('library/cat_store_list.lbi');
		echo $_GET['jsoncallback'] . '(' . $json->encode($result) . ')';
	
    }
    
public function floor_cat_contentOp(){
    $adminru['ru_id']= $_SESSION['store_id'];	
    $tablePre = C('tablepre');
	$smarty = new cls_template();
    $model = Model('visual');
	$smarty->template_dir = BASE_ROOT_PATH.DS.DIR_SHOP.'/templates/'.TPL_NAME;
	$smarty->compile_dir = BASE_ROOT_PATH.DS.DIR_SHOP.'/templates/'.TPL_NAME;	
	$result = array('error' => 0, 'content' => '');
	$json = new JSON();

	$goods_ids = (empty($_REQUEST['goods_ids']) ? 0 : addslashes($_REQUEST['goods_ids']));
	$cat_id = (empty($_REQUEST['cat_id']) ? 0 : intval($_REQUEST['cat_id']));
	$floor_num = (empty($_REQUEST['floor_num']) ? 0 : intval($_REQUEST['floor_num']));
	$warehouse_id = (empty($_REQUEST['warehouse_id']) ? 0 : intval($_REQUEST['warehouse_id']));
	$area_id = (empty($_REQUEST['area_id']) ? 0 : intval($_REQUEST['area_id']));
	$seller_id = (empty($_REQUEST['seller_id']) ? 0 : intval($_REQUEST['seller_id']));
	$floorcat = (empty($_REQUEST['floorcat']) ? 0 : intval($_REQUEST['floorcat']));
	$result['cat_id'] = $cat_id;
    $goods_id = array();
    if($goods_ids){
        if(stripos($goods_ids,",")!== false){
            $goods_id = explode(",",$goods_ids);
        }else{
        	$goods_id[0] = $goods_ids;
        }
    }
  
    $mode = Model('goods');
    if(is_array($goods_id)){
    	foreach($goods_id as $vb=>$kb){  
            $ruts = $mode->getGoodsOnlineList(array('goods_id'=>$kb));
    		$goods[] = $ruts[0];
    		
    	}
    	
    }

    $goods_list = array();
    if(is_array($goods)){
    	foreach($goods as $vc=>$kc){
    		$goods_list[$vc]['url']=urlShop('goods','index', array('goods_id' => $kc['goods_id']));
    		$goods_list[$vc]['goods_thumb']=cthumb($kc['goods_image'], 240, $seller_id);
    		$goods_list[$vc]['goods_name']=$kc['goods_name'];
    		$goods_list[$vc]['promote_price']=ncPriceFormat($kc['goods_promotion_price']);    
    		$goods_list[$vc]['shop_price']=ncPriceFormat($kc['goods_price']);
    		$goods_list[$vc]['market_price']=ncPriceFormat($kc['goods_marketprice']);
    	}
    }

	$smarty->assign('goods_list', $goods_list);
	$temp = 'floor_temp';

	if ($floorcat == 1) {
		$temp = 'floor_temp_expand';
	}

	$smarty->assign('temp', $temp);

	if ($floorcat == 2) {
		$result['content'] = $goods_list;
	}else{
		$defaultnumber = $floor_num - count($goods_list);
		$defaultgoods = array();

		if (0 < $defaultnumber) {
			$defaultgoods = range(1, $defaultnumber);
		}

		$smarty->assign('defaultgoods', $defaultgoods);
		$result['content'] = $smarty->fetch('library/floor_cat_content.lbi');
	}

	exit($json->encode($result));						

}	



}
