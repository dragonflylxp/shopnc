<?php

/**

 * 所有店铺首页

 */






use shopec\Tpl;
defined('Inshopec') or exit('Access Invalid!');





class shopControl extends mobileHomeControl {



    public function __construct(){

        parent::__construct();

    }

      /*

     * 首页显示

     */

       public function indexOp(){

            $web_seo = C('site_name')."-店铺街";

            Tpl::output('web_seo',$web_seo); 

            Tpl::showpage('shop');

        }



    /*

     * 首页显示

     */

    public function shop_listOp(){





        //店铺搜索

        $model = Model();

        $condition = array();

        $keyword = trim($_GET['keyword']);

        if(C('fullindexer.open') && !empty($keyword)){

            //全文搜索

            $condition = $this->full_search($keyword);

        }else{

            if ($keyword != ''){

                $condition['store_name|store_zy'] = array('like','%'.$keyword.'%');

            }

            if ($_GET['user_name'] != ''){

                $condition['member_name'] = trim($_GET['user_name']);

            }

        }

        if (!empty($_GET['area_info'])){

            $condition['area_info'] = array('like','%'.$_GET['area_info'].'%');

        }

        if ($_GET['sc_id'] > 0){

            $child = array_merge((array)$class_list[$_GET['sc_id']]['child'],array($_GET['sc_id']));

            $condition['sc_id'] = array('in',$child);

        }



        $condition['store_state'] = 1;



        if (!in_array($_GET['order'],array('desc','asc'))){

            unset($_GET['order']);

        }

        if (!in_array($_GET['key'],array('store_sales','store_credit'))){

            unset($_GET['key']);

        }



        $order = 'store_sort asc';



        if (isset($condition['store.store_id'])){

            $condition['store_id'] = $condition['store.store_id'];unset($condition['store.store_id']);

        }

        

        $model_store = Model('store');

        $store_list = $model_store->where($condition)->order($order)->page(10)->select();

        $page_count = $model_store->gettotalpage();

        //获取店铺商品数，推荐商品列表等信息

        $store_list = $model_store->getStoreSearchList($store_list,4);



        foreach ($store_list as &$val) {

            /**

             * 店铺等级

             */

             if($val['grade_id']==0){

                $val['grade_id'] ='自营店铺';

             }else{

                $grade_class = Model('store_grade');

                $grade = $grade_class->getOneGrade($val['grade_id']);

                $val['grade_id'] =$grade['sg_name'];

             }

             $val['store_avatar'] = getStoreLogo($val['store_avatar']);

           foreach ($val['search_list_goods'] as &$vals) {

                $vals['goods_image']= thumb($vals,'small');

                 $vals['goods_url']= urlMobile('goods','detail',array('goods_id'=>$vals['goods_id']));

                 

                



           }

        }

    

        //print_r($store_list);exit();

        //信用度排序

        if($_GET['key'] == 'store_credit') {

            if($_GET['order'] == 'desc') {

                $store_list = sortClass::sortArrayDesc($store_list, 'store_credit_average');

            }else {

                $store_list = sortClass::sortArrayAsc($store_list, 'store_credit_average');

            }

        }else if($_GET['key'] == 'store_sales') {//销量排行

            if($_GET['order'] == 'desc') {

                $store_list = sortClass::sortArrayDesc($store_list, 'num_sales_jq');

            }else {

                $store_list = sortClass::sortArrayAsc($store_list, 'num_sales_jq');

            }

        }

              

         output_data(array('store_list' => $store_list), mobile_page($page_count));



    }





    private  function  _get_Own_Store_List(){

        $model_store = Model('store');

        //查询条件

        $condition = array();

        $keyword = trim($_GET['keyword']);

        if(C('fullindexer.open') && !empty($keyword)){

            //全文搜索

            $condition = $this->full_search($keyword);

        }else{

            if ($keyword != ''){

                $condition['store_name|store_zy'] = array('like','%'.$keyword.'%');

            }

           

        }

        if (!empty($_GET['area_info'])){

            $condition['area_info'] = array('like','%'.$_GET['area_info'].'%');

        }



        if(!empty($_GET['sc_id']) && intval($_GET['sc_id']) > 0) {

            $condition['sc_id'] = $_GET['sc_id'];

        } 

        //所需字段

        $fields = "*";

        //排序方式

        $order = $this->_store_list_order($_GET['key'], $_GET['order']);

        $store_list = $model_store->where($condition)->order($order)->page(10)->select();

        $page_count = $model_store->gettotalpage();

        $own_store_list = $store_list;

        $simply_store_list = array();



        foreach ($own_store_list as $key => $value) {



            $simply_store_list[$key]['store_id'] = $own_store_list[$key]['store_id'];

            $simply_store_list[$key]['store_name'] = $own_store_list[$key]['store_name'];

            $simply_store_list[$key]['store_collect'] = $own_store_list[$key]['store_collect'];

            $simply_store_list[$key]['store_address'] = $own_store_list[$key]['store_address'];

            $simply_store_list[$key]['store_area_info'] = $own_store_list[$key]['area_info'];

            $simply_store_list[$key]['store_avatar'] =$own_store_list[$key]['store_avatar'];

            $simply_store_list[$key]['goods_count'] = $own_store_list[$key]['goods_count'];

            $simply_store_list[$key]['store_avatar_url'] = UPLOAD_SITE_URL.'/'.ATTACH_COMMON.DS.C('default_store_avatar');

            if($own_store_list[$key]['store_avatar']){

                $simply_store_list[$key]['store_avatar_url'] = UPLOAD_SITE_URL.'/shop/store/'.$own_store_list[$key]['store_avatar'];

            }

            



        }

        

         output_data(array('store_list' => $simply_store_list), mobile_page($page_count));

       

    }



     /**

     * 商品列表排序方式

     */

    private function _store_list_order($key, $order) {

        $result = 'store_id desc';

        if (!empty($key)) {



            $sequence = 'desc';

            if($order == 1) {

                $sequence = 'asc';

            }



            switch ($key) {

                //销量

                case '1' :

                    $result = 'store_id' . ' ' . $sequence;

                    break;

                //浏览量

                case '2' :

                    $result = 'store_name' . ' ' . $sequence;

                    break;

                //价格

                case '3' :

                    $result = 'store_name' . ' ' . $sequence;

                    break;

            }

        }

        return $result;

    }

    public function shopclassOp(){

        $web_seo = C('site_name')."-店铺分类";

        Tpl::output('web_seo',$web_seo);

        Tpl::showpage('shop_class');



    }





    public  function  get_shopclassOp(){



        //获取自营店列表

        $model_store_class = Model("store_class");

    //如果只想显示自营店铺，把下面的//去掉即可

        //$condition = array(

         //   'is_own_shop' => 1,

        //);



        $lst = $model_store_class->getStoreClassList($condition);

        $new_lst = array();

        foreach ($lst as $key => $value) {



            $new_lst[$key]['sc_id'] = $lst[$key]['sc_id'];

            $new_lst[$key]['sc_name'] = $lst[$key]['sc_name'];

            $new_lst[$key]['sc_bail'] = $lst[$key]['sc_bail'];

            $new_lst[$key]['sc_sort'] = $lst[$key]['sc_sort'];



        }



        output_data(array('class_list' => $new_lst));

    }

}

