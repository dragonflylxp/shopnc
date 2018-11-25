<?php

/**

 * 会员评价

 */







defined('Inshopec') or exit('Access Invalid!');



class member_evaluateControl extends mobileMemberControl {



    public function __construct(){

        parent::__construct();

    }

    /*

    *评论视图

    */

    public function indexOp(){

        Tpl::output('web_seo',C('site_name').' - '.'我要评价');

        Tpl::showpage('member_evaluation');

        

    }

    /*

    *评论视图

    */

    public function again_indexOp(){

        Tpl::output('web_seo',C('site_name').' - '.'我要再次评价');

        Tpl::showpage('member_evaluation_again');

        

    }

    /*

    *评价列表视图

    */

    public function listOp(){

        Tpl::output('web_seo',C('site_name').' - '.'我的交易评价/晒单');

        Tpl::showpage('member_evaluation_list');

        

    }

    /**

     * 评论

     */

    public function get_pjOp() {

        $order_id = intval($_GET['order_id']);

        $return = Logic('member_evaluate')->validation($order_id, $this->member_info['member_id']);

        if (!$return['state']) {

            output_error($return['msg']);

        }

        extract($return['data']);

        $store = array();

        $store['store_id'] = $store_info['store_id'];

        $store['store_name'] = $store_info['store_name'];

        $store['is_own_shop'] = $store_info['is_own_shop'];

        output_data(array('store_info' => $store, 'order_goods' => $order_goods));

    }

    

    /**

     * 评论保存

     */

    public function saveOp() {

        $order_id = intval($_POST['order_id']);

        $return = Logic('member_evaluate')->validation($order_id, $this->member_info['member_id']);

        if (!$return['state']) {

            output_error($return['msg']);

        }

        extract($return['data']);

        $return = Logic('member_evaluate')->save($_POST, $order_info, $store_info, $order_goods, $this->member_info['member_id'], $this->member_info['member_name']);



        if(!$return['state']) {

            output_data($return['msg']);

        } else {

            output_data('1');

        }

    }

    

    /**

     * 追评

     */

    public function againOp() {

        $order_id = intval($_GET['order_id']);

        $return = Logic('member_evaluate')->validationAgain($order_id, $this->member_info['member_id']);

        if (!$return['state']) {

            output_error($return['msg']);

        }

        extract($return['data']);

        $store = array();

        $store['store_id'] = $store_info['store_id'];

        $store['store_name'] = $store_info['store_name'];

        $store['is_own_shop'] = $store_info['is_own_shop'];

        

        output_data(array('store_info' => $store, 'evaluate_goods' => $evaluate_goods));

    }



    /**

     * 追加评价保存

     */

    public function save_againOp() {

        $order_id = intval($_POST['order_id']);

        $return = Logic('member_evaluate')->validationAgain($order_id, $this->member_info['member_id']);

        if (!$return['state']) {

            output_error($return['msg']);

        }

        extract($return['data']);



        $return = Logic('member_evaluate')->saveAgain($_POST, $order_info, $evaluate_goods);

        if(!$return['state']) {

            output_data($return['msg']);

        } else {

            output_data('1');

        }

    }

    

    /**

     * 虚拟订单评价

     */

    public function vrOp() {

        $order_id = intval($_GET['order_id']);

        $return = Logic('member_evaluate')->validationVr($order_id, $this->member_info['member_id']);

        if (!$return['state']) {

            output_error($return['msg']);

        }

        extract($return['data']);

        output_data(array('order_info' => $order_info));

    }

    

    /**

     * 虚拟订单评价保存

     */

    public function save_vrOp() {

        $order_id = intval($_POST['order_id']);

        $return = Logic('member_evaluate')->validationVr($order_id, $this->member_info['member_id']);

        if (!$return['state']) {

            output_error($return['msg']);

        }

        extract($return['data']);



        $return = Logic('member_evaluate')->saveVr($_POST, $order_info, $store_info, $this->member_info['member_id'], $this->member_info['member_name']);

        if(!$return['state']) {

            output_data($return['msg']);

        } else {

            output_data('1');

        }

    }

    /**

     * 获取评价列表

     */

    public function get_listOp(){

        $model_evaluate_goods = Model('evaluate_goods');

        $page_count = $model_evaluate_goods->gettotalpage();

        $condition = array();

        $condition['geval_frommemberid'] = $this->member_info['member_id'];

        $goodsevallist = $model_evaluate_goods->getEvaluateGoodsList($condition, 20, 'geval_id desc');

        foreach ($goodsevallist as &$value) {

            $value['geval_goodsimage'] = cthumb($value['geval_goodsimage'], 60);

            $value['geval_goodsurl'] = urlMobile('goods', 'detail', array('goods_id' => $value['geval_goodsid']));

            $image_array = explode(',', $value['geval_image']);

            foreach($image_array as $k=>&$v){

                $data[$k]['small']= snsThumb($v);

                $data[$k]['big']= snsThumb($v, 1024);

            }

            $value['geval_snslist'] = $data;

            $image_array1 = explode(',', $value['geval_image_again']);

             foreach($image_array1 as $k1=>&$v1){

                $data1[$k1]['small']= snsThumb($v1);

                $data1[$k1]['big']= snsThumb($v1, 1024);

            }

            $value['geval_againsnslist'] = $data1;

             $d = floor($value['geval_addtime_again']/60/60/24) - floor($value['geval_addtime']/60/60/24);

             $vd = $d==0? '当' : $d;

            $value['again_info'] = "确认收货并评价后{$vd}天再次追加评价";

            $value['geval_addtime'] = date('Y-m-d H:i:s',$value['geval_addtime']);

        }

        

        output_data(array('goodsevallist'=>$goodsevallist),mobile_page($page_count));

    }

}

