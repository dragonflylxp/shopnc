<?php

/**

 * 商家注销

 *

 */





use shopec\Tpl;

defined('Inshopec') or exit('Access Invalid!');



class seller_centerControl extends mobileSellerControl {



    public function __construct(){

        parent::__construct();

    }



    /**

     * 店铺信息

     */

    public function indexOp() {



        

        $store['seller_name'] = $_SESSION['seller_name'];

        $store['store_avatar'] = getStoreLogo($_SESSION['store_avatar']);

        // 店铺等级信息

        $store['grade_name'] = $this->store_grade['sg_name'];

        $store['grade_goodslimit'] = $this->store_grade['sg_goods_limit'];

        $store['grade_albumlimit'] = $this->store_grade['sg_album_limit'];

 

        Tpl::output('store_info',$store);

        Tpl::output('web_seo',C('site_name').' - '.'商家中心');

        Tpl::showpage('seller_center');

    }

    /**

     * 异步取得卖家统计类信息

     *

     */

    public function statisticsOp() {

        $add_time_to = strtotime(date("Y-m-d")+60*60*24);   //当前日期 ,从零点来时

        $add_time_from = strtotime(date("Y-m-d",(strtotime(date("Y-m-d"))-60*60*24*30)));   //30天前

        $goods_online = 0;      // 出售中商品

        $goods_waitverify = 0;  // 等待审核

        $goods_verifyfail = 0;  // 审核失败

        $goods_offline = 0;     // 仓库待上架商品

        $goods_lockup = 0;      // 违规下架商品

        $consult = 0;           // 待回复商品咨询

        $no_payment = 0;        // 待付款

        $no_delivery = 0;       // 待发货

        $no_receipt = 0;        // 待收货

        $refund_lock  = 0;      // 售前退款

        $refund = 0;            // 售后退款

        $return_lock  = 0;      // 售前退货

        $return = 0;            // 售后退货

        $complain = 0;          //进行中投诉



        $model_goods = Model('goods');

        // 全部商品数

        $goodscount = $model_goods->getGoodsCommonCount(array('store_id' => $_SESSION['store_id']));

        // 出售中的商品

        $goods_online = $model_goods->getGoodsCommonOnlineCount(array('store_id' => $_SESSION['store_id']));

        if (C('goods_verify')) {

            // 等待审核的商品

            $goods_waitverify = $model_goods->getGoodsCommonWaitVerifyCount(array('store_id' => $_SESSION['store_id']));

            // 审核失败的商品

            $goods_verifyfail = $model_goods->getGoodsCommonVerifyFailCount(array('store_id' => $_SESSION['store_id']));

        }

        // 仓库待上架的商品

        $goods_offline = $model_goods->getGoodsCommonOfflineCount(array('store_id' => $_SESSION['store_id']));

        // 违规下架的商品

        $goods_lockup = $model_goods->getGoodsCommonLockUpCount(array('store_id' => $_SESSION['store_id']));

        // 等待回复商品咨询

        if (C('dbdriver') == 'mysqli') {

            $consult = Model('consult')->getConsultCount(array('store_id' => $_SESSION['store_id'], 'consult_reply' => ''));

        } else {

            $consult = Model('consult')->getConsultCount(array('store_id' => $_SESSION['store_id'], 'consult_reply' => array('exp', 'consult_reply IS NULL')));

        }



        // 商品图片数量

        $imagecount = Model('album')->getAlbumPicCount(array('store_id' => $_SESSION['store_id']));



        $model_order = Model('order');

        // 交易中的订单

        $progressing = $model_order->getOrderCountByID('store',$_SESSION['store_id'],'TradeCount');

        // 待付款

        $no_payment = $model_order->getOrderCountByID('store',$_SESSION['store_id'],'NewCount');

         // 已发货

        $send = $model_order->getOrderCountByID('store',$_SESSION['store_id'],'SendCount');

        // 待发货

        $no_delivery = $model_order->getOrderCountByID('store',$_SESSION['store_id'],'PayCount');

        //带评价



        $evalCount =  $model_order->getOrderCountByID('store',$_SESSION['store_id'],'EvalCount');

        //已取消

        $cancelCount =  $model_order->getOrderCountByID('store',$_SESSION['store_id'],'CancelCount');

        



        $model_refund_return = Model('refund_return');

        // 售前退款

        $condition = array();

        $condition['store_id'] = $_SESSION['store_id'];

        $condition['refund_type'] = 1;

        $condition['order_lock'] = 2;

        $condition['refund_state'] = array('lt', 3);

        $refund_lock = $model_refund_return->getRefundReturnCount($condition);

        // 售后退款

        $condition = array();

        $condition['store_id'] = $_SESSION['store_id'];

        $condition['refund_type'] = 1;

        $condition['order_lock'] = 1;

        $condition['refund_state'] = array('lt', 3);

        $refund = $model_refund_return->getRefundReturnCount($condition);

        // 售前退货

        $condition = array();

        $condition['store_id'] = $_SESSION['store_id'];

        $condition['refund_type'] = 2;

        $condition['order_lock'] = 2;

        $condition['refund_state'] = array('lt', 3);

        $return_lock = $model_refund_return->getRefundReturnCount($condition);

        // 售后退货

        $condition = array();

        $condition['store_id'] = $_SESSION['store_id'];

        $condition['refund_type'] = 2;

        $condition['order_lock'] = 1;

        $condition['refund_state'] = array('lt', 3);

        $return = $model_refund_return->getRefundReturnCount($condition);



        $condition = array();

        $condition['accused_id'] = $_SESSION['store_id'];

        $condition['complain_state'] = array(array('gt',10),array('lt',90),'and');

        $complain = Model()->table('complain')->where($condition)->count();



        //待确认的结算账单

        $model_bill = Model('bill');

        $condition = array();

        $condition['ob_store_id'] = $_SESSION['store_id'];

        $condition['ob_state'] = BILL_STATE_CREATE;

        $bill_confirm_count = $model_bill->getOrderBillCount($condition);



        //统计数组

        $statistics = array(

            'goodscount' => $goodscount,

            'online' => $goods_online,

            'waitverify' => $goods_waitverify,

            'verifyfail' => $goods_verifyfail,

            'offline' => $goods_offline,

            'lockup' => $goods_lockup,

            'imagecount' => $imagecount,

            'consult' => $consult,

            'progressing' => $progressing,

            'payment' => $no_payment,

            'delivery' => $no_delivery,

            'refund_lock' => $refund_lock,

            'refund' => $refund,

            'cancel' =>$cancelCount,

            'evalcount' => $evalCount,

            'send' =>$send,

            'return_lock' => $return_lock,

            'return' => $return,

            'complain' => $complain,

            'bill_confirm' => $bill_confirm_count

        );

        exit(json_encode($statistics));

    }



    /**

     * 编辑店铺

     */

    public function store_editOp() {

        $upload = new UploadFile();

        /**

         * 上传店铺图片

        */

        if (!empty($_FILES['store_banner']['name'])){

            $upload->set('default_dir', ATTACH_STORE);

            $upload->set('thumb_ext',   '');

            $upload->set('file_name','');

            $upload->set('ifremove',false);

            $result = $upload->upfile('store_banner');

            if ($result){

                $_POST['store_banner'] = $upload->file_name;

            }else {

                showDialog($upload->error);

            }

        }

        

        //删除旧店铺图片

        if (!empty($_POST['store_banner']) && !empty($store_info['store_banner'])){

            @unlink(BASE_UPLOAD_PATH.DS.ATTACH_STORE.DS.$this->store_info['store_banner']);

        }

        /**

         * 更新入库

         */

        $param = array(

            'store_banner' => empty($_POST['store_banner']) ? $this->store_info['store_banner'] : $_POST['store_banner'],

            'store_qq' => $_POST['store_qq'],

            'store_ww' => $_POST['store_ww'],

            'store_phone' => $_POST['store_phone'],

            'store_zy' => $_POST['store_zy'],

            'store_keywords' => $_POST['seo_keywords'],

            'store_description' => $_POST['seo_description']

        );



        $result = Model('store')->editStore($param, array('store_id' => $this->store_info['store_id']));

        if(!$result) {

            output_error('编辑失败');

        }

        output_data('1');

    }



    /**

     * 店铺信息统计

     */

    public function store_statisticsOp() {

        $model_stat = Model('stat');

        $start_time = strtotime(date('Y-m',time()));        // 当月开始

        // 月销量 月订单

        $condition = array();

        $condition['order_add_time'] = array('gt',$start_time);

        $monthly_sales = $model_stat->getoneByStatorder($condition, 'COUNT(*) as ordernum,SUM(order_amount) as orderamount ');

        

        // 月访问量

        //确定统计分表名称

        $last_num = $this->store_info['store_id'] % 10; //获取店铺ID的末位数字

        $tablenum = ($t = intval(C('flowstat_tablenum'))) > 1 ? $t : 1; //处理流量统计记录表数量

        $flow_tablename = ($t = ($last_num % $tablenum)) > 0 ? "flowstat_$t" : 'flowstat';

        $condition = array();

        $condition['store_id'] = $this->store_info['store_id'];

        $condition['stattime'] = array('gt', $start_time);

        $condition['type'] = 'sum';

        $statlist_tmp = $model_stat->getoneByFlowstat($flow_tablename, $condition, 'SUM(clicknum) as amount');

        

        $output = array(

            'store_name'    => $this->store_info['store_name'],

            'store_banner'  => getStoreLogo($this->store_info['store_banner'], 'store_logo'),

            'order_amount'  => $monthly_sales['orderamount'],

            'order_num'     => $monthly_sales['ordernum'],

            'click_amount'  => $statlist_tmp['amount']

        );



        output_data(array('statistics' => $output));

    }

}

