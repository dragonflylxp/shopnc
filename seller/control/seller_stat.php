<?php

/**

 * 商家销售统计

 *

 */





use shopec\Tpl;

defined('Inshopec') or exit('Access Invalid!');



class seller_statControl extends mobileSellerControl {



    private $search_arr;//处理后的参数



    public function __construct(){

        parent::__construct();

        import('function.datehelper');

        import('function.statistics');

        $model_stat = Model('stat');

        $this->search_arr = $_POST;

       



        $searchtime_arr = $model_stat->getStarttimeAndEndtime($this->search_arr);

        $this->search_arr['stime'] = $searchtime_arr[0];

        $this->search_arr['etime'] = $searchtime_arr[1];

        //存储参数

        $this->search_arr = $_REQUEST;

        //处理搜索时间

        $this->search_arr = $model_stat->dealwithSearchTime($this->search_arr);

        //获得系统年份

        $year_arr = getSystemYearArr();

        //获得系统月份

        $month_arr = getSystemMonthArr();

        //获得本月的周时间段

        $week_arr = getMonthWeekArr($this->search_arr['week']['current_year'], $this->search_arr['week']['current_month']);

        $gccache_arr = Model('goods_class')->getGoodsclassCache($this->choose_gcid,3);

        $this->gc_arr = $gccache_arr['showclass'];

        Tpl::output('gc_json',json_encode($gccache_arr['showclass']));

        Tpl::output('gc_choose_json',json_encode($gccache_arr['choose_gcid']));

        Tpl::output('year_arr', $year_arr);

        Tpl::output('month_arr', $month_arr);

        Tpl::output('week_arr', $week_arr);

        Tpl::output('search_arr', $this->search_arr);



    }

    /*

    *商家订单首页

    */

    public function indexOp() {

        $model = Model('stat');

        //统计的日期0点

        $stat_time = strtotime(date('Y-m-d',time())) - 86400;

        /*

         * 近7天

         */

        $stime = $stat_time - (86400*6);//7天前

        $etime = $stat_time + 86400 - 1;//昨天23:59



        $statnew_arr = array();



        //查询订单表下单量、下单金额、下单客户数

        $where = array();

        $where['order_isvalid'] = 1;//计入统计的有效订单

        $where['store_id'] = $_SESSION['store_id'];

        $where['order_add_time'] = array('between',array($stime,$etime));

        $field = ' COUNT(*) as ordernum, SUM(order_amount) as orderamount, COUNT(DISTINCT buyer_id) as ordermembernum, AVG(order_amount) as avgorderamount ';

        $stat_order = $model->getoneByStatorder($where, $field);

        $statnew_arr['ordernum'] = ($t = $stat_order['ordernum'])?$t:0;

        $statnew_arr['orderamount'] = ncPriceFormat(($t = $stat_order['orderamount'])?$t:(0));

        $statnew_arr['ordermembernum'] = ($t = $stat_order['ordermembernum']) > 0?$t:0;

        $statnew_arr['avgorderamount'] = ncPriceFormat(($t = $stat_order['avgorderamount'])?$t:(0));

        unset($stat_order);



        //下单高峰期

        $where = array();

        $where['order_isvalid'] = 1;//计入统计的有效订单

        $where['store_id'] = $_SESSION['store_id'];

        $where['order_add_time'] = array('between',array($stime,$etime));

        $field = ' HOUR(FROM_UNIXTIME(order_add_time)) as hourval,COUNT(*) as ordernum ';

        if (C('dbdriver') == 'mysqli') {

            $_group = 'hourval';

        } else {

            $_group = 'HOUR(FROM_UNIXTIME(order_add_time))';

        }

        $orderlist = $model->statByStatorder($where, $field, 0, 0, 'ordernum desc,hourval asc', $_group);



        foreach ((array)$orderlist as $k=>$v){

            if ($k < 2){//取前两个订单量高的时间段

                if (!$statnew_arr['hothour']){

                    $statnew_arr['hothour'] = ($v['hourval'].":00~".($v['hourval']+1).":00");

                } else {

                    $statnew_arr['hothour'] .= ("，".($v['hourval'].":00~".($v['hourval']+1).":00"));

                }

            }

        }

        unset($orderlist);



        //查询订单商品表下单商品数

        $where = array();

        $where['order_isvalid'] = 1;//计入统计的有效订单

        $where['store_id'] = $_SESSION['store_id'];

        $where['order_add_time'] = array('between',array($stime,$etime));

        $field = ' SUM(goods_num) as ordergoodsnum, AVG(goods_pay_price/goods_num) as avggoodsprice ';

        $stat_ordergoods = $model->getoneByStatordergoods($where, $field);

        $statnew_arr['ordergoodsnum'] = ($t = $stat_ordergoods['ordergoodsnum'])?$t:0;

        $statnew_arr['avggoodsprice'] = ncPriceFormat(($t = $stat_ordergoods['avggoodsprice'])?$t:(0));

        unset($stat_ordergoods);



        //商品总数、收藏量

        $goods_list = $model->statByGoods(array('store_id'=>$_SESSION['store_id'],'is_virtual'=>0),'COUNT(*) as goodsnum, SUM(goods_collect) as gcollectnum');

        $statnew_arr['goodsnum'] = ($t = $goods_list[0]['goodsnum']) > 0?$t:0;

        $statnew_arr['gcollectnum'] = ($t = $goods_list[0]['gcollectnum']) > 0?$t:0;



        //店铺收藏量

        $store_list = $model->getoneByStore(array('store_id'=>$_SESSION['store_id']),'store_collect');

        $statnew_arr['store_collect'] = ($t = $store_list['store_collect']) > 0?$t:0;



        /*

         * 销售走势

         */

        //构造横轴数据

        for($i=$stime; $i<$etime; $i+=86400){

            //当前数据的时间

            $timetext = date('n',$i).'-'.date('j',$i);

            //统计图数据

            $stat_list[$timetext] = 0;

            //横轴

            $stat_arr['xAxis']['categories'][] = $timetext;

        }

        $where = array();

        $where['order_isvalid'] = 1;//计入统计的有效订单

        $where['store_id'] = $_SESSION['store_id'];

        $where['order_add_time'] = array('between',array($stime,$etime));

        $field = ' min(order_add_time) as order_add_time,SUM(order_amount) as orderamount,MONTH(FROM_UNIXTIME(order_add_time)) as monthval,DAY(FROM_UNIXTIME(order_add_time)) as dayval ';

        if (C('dbdriver') == 'mysqli') {

            $_group = 'monthval,dayval';

        } else {

            $_group = 'MONTH(FROM_UNIXTIME(order_add_time)),DAY(FROM_UNIXTIME(order_add_time))';

        }

        $stat_order = $model->statByStatorder($where, $field, 0, 0, '',$_group);

        if($stat_order){

            foreach($stat_order as $k => $v){

                $stat_list[$v['monthval'].'-'.$v['dayval']] = floatval($v['orderamount']);

            }

        }

        $stat_arr['legend']['enabled'] = false;

        $stat_arr['series'][0]['name'] = '下单金额';

        $stat_arr['series'][0]['data'] = array_values($stat_list);

        //得到统计图数据

        $stat_arr['title'] = '最近7天销售走势';

        $stat_arr['yAxis'] = '下单金额';



        $stattoday_json = getStatData_LineLabels($stat_arr);

        unset($stat_arr);



        /*

         * 7日内商品销售TOP30

         */

        $stime = $stat_time - 86400*6;//7天前0点

        $etime = $stat_time + 86400 - 1;//今天24点

        $where = array();

        $where['order_isvalid'] = 1;//计入统计的有效订单

        $where['store_id'] = $_SESSION['store_id'];

        $where['order_add_time'] = array('between',array($stime,$etime));

        $field = ' sum(goods_num) as ordergoodsnum, goods_id, min(goods_name) as goods_name ';

        $goodstop30_arr = $model->statByStatordergoods($where, $field, 0, 30,'ordergoodsnum desc', 'goods_id');



        /**

         * 7日内同行热卖商品

         */

        $where = array();

        $where['order_isvalid'] = 1;//计入统计的有效订单

        $where['order_add_time'] = array('between',array($stime,$etime));

        $where['store_id'] = array('neq',$_SESSION['store_id']);

        if (!checkPlatformStore()) {//如果不是平台店铺，则查询店铺经营类目的同行数据

            //查询店铺经营类目

            $store_bindclass = Model('store_bind_class')->getStoreBindClassList(array('store_id'=>$_SESSION['store_id']));

            $goodsclassid_arr = array();

            foreach ((array)$store_bindclass as $k=>$v){

                if (intval($v['class_3']) > 0){

                    $goodsclassid_arr[3][] = intval($v['class_3']);

                } elseif (intval($v['class_2']) > 0){

                    $goodsclassid_arr[2][] = intval($v['class_2']);

                } elseif (intval($v['class_1']) > 0){

                    $goodsclassid_arr[1][] = intval($v['class_1']);

                }

            }

            //拼接商品分类条件

            if ($goodsclassid_arr){

                ksort($goodsclassid_arr);

                $gc_parentidwhere_keyarr = array();

                $gc_parentidwhere_arr = array();

                foreach ((array)$goodsclassid_arr as $k=>$v){

                    $gc_parentidwhere_keyarr[] = 'gc_parentid_'.$k;

                    $gc_parentidwhere_arr[] = array('in',$goodsclassid_arr[$k]);

                }

                if (count($gc_parentidwhere_keyarr) == 1){

                    $where[$gc_parentidwhere_keyarr[0]] = $gc_parentidwhere_arr[0];

                } else {

                    $gc_parentidwhere_arr['_multi'] = '1';

                    $where[implode('|',$gc_parentidwhere_keyarr)] = $gc_parentidwhere_arr;

                }

            }

        }

        $field = ' sum(goods_num) as ordergoodsnum, goods_id, min(goods_name) as goods_name ';

        $othergoodstop30_arr = $model->statByStatordergoods($where, $field, 0, 30,'ordergoodsnum desc', 'goods_id');



        Tpl::output('goodstop30_arr',$goodstop30_arr);

        Tpl::output('othergoodstop30_arr',$othergoodstop30_arr);

        Tpl::output('stattoday_json',$stattoday_json);

        Tpl::output('statnew_arr',$statnew_arr);

        Tpl::output('stat_time',$stat_time);

         Tpl::output('web_seo',C('site_name').' - '.'店铺概况');

         Tpl::showpage('seller_stat_index');

    }



     /**

     * 店铺流量统计

     */

    public function goodslistOp() {

         $model = Model('stat');

        //统计的日期0点

        $stat_time = strtotime(date('Y-m-d',time())) - 86400;

        /*

         * 近7天

         */

        $stime = $stat_time - (86400*29);//7天前

        $etime = $stat_time + 86400 - 1;//昨天23:59

        //查询订单商品表下单商品数

        $where = array();

        $where['order_isvalid'] = 1;//计入统计的有效订单

        $where['store_id'] = $_SESSION['store_id'];

        $where['order_add_time'] = array('between',array($stime,$etime));

        if($this->choose_gcid > 0){

            $gc_depth = $this->gc_arr[$this->choose_gcid]['depth'];

            $where['gc_parentid_'.$gc_depth] = $this->choose_gcid;

        }

        if(trim($_GET['search_gname'])){

            $where['goods_name'] = array('like',"%".trim($_GET['search_gname'])."%");

        }

        //查询总条数

        $count_arr = $model->statByStatordergoods($where, 'count(DISTINCT goods_id) as countnum');

        $countnum = intval($count_arr[0]['countnum']);



        $field = ' min(goods_id) as goods_id,min(goods_name) as goods_name,min(goods_image) as goods_image,min(goods_price) as goods_price,SUM(goods_num) as ordergoodsnum,SUM(goods_pay_price) as ordergamount ';

        //排序

        $orderby_arr = array('ordergoodsnum asc','ordergoodsnum desc','ordergamount asc','ordergamount desc');

        if (!in_array(trim($this->search_arr['orderby']),$orderby_arr)){

            $this->search_arr['orderby'] = 'ordergoodsnum desc';

        }

        $orderby = trim($this->search_arr['orderby']).',goods_id';

        $stat_ordergoods = $model->statByStatordergoods($where, $field, array(5,$countnum), 0, $this->search_arr['orderby'], 'goods_id');

        Tpl::output('goodslist',$stat_ordergoods);

        Tpl::output('show_page',$model->showpage(1));

        Tpl::output('orderby',$this->search_arr['orderby']);

        Tpl::output('web_seo',C('site_name').' - '.'商品分析');

        Tpl::showpage('seller_stat_goodslist');

    }

   /**

     * 商品详细

     */

    public function goodsinfoOp(){

        $templatesname = 'seller_stat_goodsinfo';

        $goods_id = intval($_GET['gid']);

        if ($goods_id <= 0){

            Tpl::output('stat_msg','参数错误');

            Tpl::showpage($templatesname,'null_layout');

        }

        //查询商品信息

        $goods_info = Model('goods')->getGoodsInfoByID($goods_id, 'goods_name');

        if (!$goods_info){

            Tpl::output('stat_msg','参数错误');

            Tpl::showpage($templatesname,'null_layout');

        }

        $model = Model('stat');

        //统计的日期0点

        $stat_time = strtotime(date('Y-m-d',time())) - 86400;

        /*

         * 近7天

         */

        $stime = $stat_time - (86400*6);//7天前

        $etime = $stat_time + 86400 - 1;//昨天23:59



        $stat_arr = array();

        for($i=$stime; $i<$etime; $i+=86400){

            //当前数据的时间

            $timetext = date('n',$i).'-'.date('j',$i);

            //统计图数据

            $stat_list['ordergoodsnum'][$timetext] = 0;

            $stat_list['ordergamount'][$timetext] = 0;

            $stat_list['ordernum'][$timetext] = 0;

            //横轴

            $stat_arr['ordergoodsnum']['xAxis']['categories'][] = $timetext;

            $stat_arr['ordergamount']['xAxis']['categories'][] = $timetext;

            $stat_arr['ordernum']['xAxis']['categories'][] = $timetext;

        }

        //查询订单商品表下单商品数

        $where = array();

        $where['goods_id'] = $goods_id;

        $where['order_isvalid'] = 1;//计入统计的有效订单

        $where['store_id'] = $_SESSION['store_id'];

        $where['order_add_time'] = array('between',array($stime,$etime));



        $field = ' min(goods_id) as goods_id,min(goods_name) as goods_name,COUNT(DISTINCT order_id) as ordernum,SUM(goods_num) as ordergoodsnum,SUM(goods_pay_price) as ordergamount,MONTH(FROM_UNIXTIME(order_add_time)) as monthval,DAY(FROM_UNIXTIME(order_add_time)) as dayval ';

        if (C('dbdriver') == 'mysqli') {

            $_group = 'monthval,dayval';

        } else {

            $_group = 'MONTH(FROM_UNIXTIME(order_add_time)),DAY(FROM_UNIXTIME(order_add_time))';

        }

        $stat_ordergoods = $model->statByStatordergoods($where, $field, 0, 0, '',$_group);



        $stat_count = array();

        if($stat_ordergoods){

            foreach($stat_ordergoods as $k => $v){

                $stat_list['ordergoodsnum'][$v['monthval'].'-'.$v['dayval']] = intval($v['ordergoodsnum']);

                $stat_list['ordergamount'][$v['monthval'].'-'.$v['dayval']] = floatval($v['ordergamount']);

                $stat_list['ordernum'][$v['monthval'].'-'.$v['dayval']] = intval($v['ordernum']);



                $stat_count['ordergoodsnum'] = intval($stat_count['ordergoodsnum']) + $v['ordergoodsnum'];

                $stat_count['ordergamount'] = floatval($stat_count['ordergamount']) + floatval($v['ordergamount']);

                $stat_count['ordernum'] = intval($stat_count['ordernum']) + $v['ordernum'];

            }

        }



        $stat_count['ordergamount'] = ncPriceFormat($stat_count['ordergamount']);



        $stat_arr['ordergoodsnum']['legend']['enabled'] = false;

        $stat_arr['ordergoodsnum']['series'][0]['name'] = '下单商品数';

        $stat_arr['ordergoodsnum']['series'][0]['data'] = array_values($stat_list['ordergoodsnum']);

        $stat_arr['ordergoodsnum']['title'] = '最近7天下单商品数走势';

        $stat_arr['ordergoodsnum']['yAxis'] = '下单商品数';

        $stat_json['ordergoodsnum'] = getStatData_LineLabels($stat_arr['ordergoodsnum']);



        $stat_arr['ordergamount']['legend']['enabled'] = false;

        $stat_arr['ordergamount']['series'][0]['name'] = '下单金额';

        $stat_arr['ordergamount']['series'][0]['data'] = array_values($stat_list['ordergamount']);

        $stat_arr['ordergamount']['title'] = '最近7天下单金额走势';

        $stat_arr['ordergamount']['yAxis'] = '下单金额';

        $stat_json['ordergamount'] = getStatData_LineLabels($stat_arr['ordergamount']);



        $stat_arr['ordernum']['legend']['enabled'] = false;

        $stat_arr['ordernum']['series'][0]['name'] = '下单量';

        $stat_arr['ordernum']['series'][0]['data'] = array_values($stat_list['ordernum']);

        $stat_arr['ordernum']['title'] = '最近7天下单量走势';

        $stat_arr['ordernum']['yAxis'] = '下单量';

        $stat_json['ordernum'] = getStatData_LineLabels($stat_arr['ordernum']);

        Tpl::output('stat_json',$stat_json);

        Tpl::output('stat_count',$stat_count);

        Tpl::output('goods_info',$goods_info);

        Tpl::showpage($templatesname,'null_layout');

    }

    /**

     * 价格销量统计

     */

    public function priceOp(){

        if(!$this->search_arr['search_type']){

            $this->search_arr['search_type'] = 'day';

        }

        $model = Model('stat');

        //获得搜索的开始时间和结束时间

        $searchtime_arr = $model->getStarttimeAndEndtime($this->search_arr);

        $where = array();

        $where['store_id'] = $_SESSION['store_id'];

        $where['order_isvalid'] = 1;//计入统计的有效订单

        $where['order_add_time'] = array('between',$searchtime_arr);

        //商品分类

        if ($this->choose_gcid > 0){

            //获得分类深度

            $depth = $this->gc_arr[$this->choose_gcid]['depth'];

            $where['gc_parentid_'.$depth] = $this->choose_gcid;

        }

        $field = '1';

        $pricerange = Model('store_extend')->getfby_store_id($_SESSION['store_id'],'pricerange');

        $pricerange_arr = $pricerange?unserialize($pricerange):array();

        if ($pricerange_arr){

            $stat_arr['series'][0]['name'] = '下单量';

            //设置价格区间最后一项，最后一项只有开始值没有结束值

            $pricerange_count = count($pricerange_arr);

            if ($pricerange_arr[$pricerange_count-1]['e']){

                $pricerange_arr[$pricerange_count]['s'] = $pricerange_arr[$pricerange_count-1]['e'] + 1;

                $pricerange_arr[$pricerange_count]['e'] = '';

            }

            foreach ((array)$pricerange_arr as $k=>$v){

                $v['s'] = intval($v['s']);

                $v['e'] = intval($v['e']);

                //构造查询字段

                if (C('dbdriver') == 'mysqli') {

                    if ($v['e']){

                        $field .= " ,SUM(IF(goods_pay_price/goods_num > {$v['s']} and goods_pay_price/goods_num <= {$v['e']},goods_num,0)) as goodsnum_{$k}";

                    } else {

                        $field .= " ,SUM(IF(goods_pay_price/goods_num > {$v['s']},goods_num,0)) as goodsnum_{$k}";

                    }

                } elseif (C('dbdriver') == 'oracle') {

                    if ($v['e']){

                        $field .= " ,SUM((case when goods_pay_price/goods_num > {$v['s']} and goods_pay_price/goods_num <= {$v['e']} then goods_num else 0 end)) as goodsnum_{$k}";

                    } else {

                        $field .= " ,SUM((case when goods_pay_price/goods_num > {$v['s']} then goods_num else 0 end)) as goodsnum_{$k}";

                    }

                }



            }

            $ordergooods_list = $model->getoneByStatordergoods($where, $field);

            if($ordergooods_list){

                foreach ((array)$pricerange_arr as $k=>$v){

                    //横轴

                    if ($v['e']){

                        $stat_arr['xAxis']['categories'][] = $v['s'].'-'.$v['e'];

                    } else {

                        $stat_arr['xAxis']['categories'][] = $v['s'].'以上';

                    }

                    //统计图数据

                    if ($ordergooods_list['goodsnum_'.$k]){

                        $stat_arr['series'][0]['data'][] = intval($ordergooods_list['goodsnum_'.$k]);

                    } else {

                        $stat_arr['series'][0]['data'][] = 0;

                    }

                }

            }

            //得到统计图数据

            $stat_arr['title'] = '价格销量分布';

            $stat_arr['legend']['enabled'] = false;

            $stat_arr['yAxis'] = '销量';

            $pricerange_statjson = getStatData_LineLabels($stat_arr);

        } else {

            $pricerange_statjson = '';

        }



        Tpl::output('statjson',$pricerange_statjson);

        Tpl::output('web_seo',C('site_name').' - '.'商品分析');

        Tpl::showpage('seller_stat_price');

    }



    /**

     * 热卖商品

     */

    public function hotgoodsOp(){

        $topnum = 7;



        if(!$this->search_arr['search_type']){

            $this->search_arr['search_type'] = 'day';

        }

        $model = Model('stat');

        //获得搜索的开始时间和结束时间

        $searchtime_arr = $model->getStarttimeAndEndtime($this->search_arr);

        $model = Model('stat');

        $where = array();

        $where['store_id'] = $_SESSION['store_id'];

        $where['order_isvalid'] = 1;//计入统计的有效订单

        $where['order_add_time'] = array('between',$searchtime_arr);



        //查询销量top

        //构造横轴数据

        for($i=1; $i<=$topnum; $i++){

            //数据

            $stat_arr['series'][0]['data'][] = array('name'=>'','y'=>0);

            //横轴

            $stat_arr['xAxis']['categories'][] = "$i";

        }

        $field = ' goods_id,min(goods_name) as goods_name,SUM(goods_num) as goodsnum ';

        $orderby = 'goodsnum desc,goods_id';

        $statlist = array();

        $statlist['goodsnum'] = $model->statByStatordergoods($where, $field, 0, $topnum, $orderby, 'goods_id');

        foreach ((array)$statlist['goodsnum'] as $k=>$v){

            $stat_arr['series'][0]['data'][$k] = array('name'=>strval($v['goods_name']),'y'=>intval($v['goodsnum']));

        }

        $stat_arr['series'][0]['name'] = '下单商品数';

        $stat_arr['legend']['enabled'] = false;

        //得到统计图数据

        $stat_arr['title'] = '热卖商品TOP'.$topnum;

        $stat_arr['yAxis'] = '下单商品数';

        $stat_json['goodsnum'] = getStatData_Column2D($stat_arr);

        unset($stat_arr);





        //查询下单金额top

        //构造横轴数据

        for($i=1; $i<=$topnum; $i++){

            //数据

            $stat_arr['series'][0]['data'][] = array('name'=>'','y'=>0);

            //横轴

            $stat_arr['xAxis']['categories'][] = "$i";

        }

        $field = ' goods_id,min(goods_name) as goods_name,SUM(goods_pay_price) as orderamount ';

        $orderby = 'orderamount desc,goods_id';

        $statlist['orderamount'] = $model->statByStatordergoods($where, $field, 0, $topnum, $orderby, 'goods_id');

        foreach ((array)$statlist['orderamount'] as $k=>$v){

            $stat_arr['series'][0]['data'][$k] = array('name'=>strval($v['goods_name']),'y'=>floatval($v['orderamount']));

        }

        $stat_arr['series'][0]['name'] = '下单金额';

        $stat_arr['legend']['enabled'] = false;

        //得到统计图数据

        $stat_arr['title'] = '热卖商品TOP'.$topnum;

        $stat_arr['yAxis'] = '下单金额';

        $stat_json['orderamount'] = getStatData_Column2D($stat_arr);

        Tpl::output('stat_json',$stat_json);

        Tpl::output('statlist',$statlist);

        Tpl::output('web_seo',C('site_name').' - '.'商品分析');

        Tpl::showpage('seller_stat_hotgoods');

    }

     /**

     * 店铺流量统计

     */

    public function storeflowOp() {

        $store_id = intval($_SESSION['store_id']);

        //确定统计分表名称

        $last_num = $store_id % 10; //获取店铺ID的末位数字

        $tablenum = ($t = intval(C('flowstat_tablenum'))) > 1 ? $t : 1; //处理流量统计记录表数量

        $flow_tablename = ($t = ($last_num % $tablenum)) > 0 ? "flowstat_$t" : 'flowstat';

        if(!$this->search_arr['search_type']){

            $this->search_arr['search_type'] = 'week';

        }

        $model = Model('stat');

        //获得搜索的开始时间和结束时间

        $searchtime_arr = $model->getStarttimeAndEndtime($this->search_arr);

        $where = array();

        $where['store_id'] = $store_id;

        $where['stattime'] = array('between',$searchtime_arr);

        $where['type'] = 'sum';



        $field = ' SUM(clicknum) as amount';

        if($this->search_arr['search_type'] == 'week'){

            //构造横轴数据

            for($i=1; $i<=7; $i++){

                $tmp_weekarr = getSystemWeekArr();

                //横轴

                $stat_arr['xAxis']['categories'][] = $tmp_weekarr[$i];

                unset($tmp_weekarr);

                $statlist[$i] = 0;

            }

            $field .= ' ,WEEKDAY(FROM_UNIXTIME(stattime))+1 as timeval ';

            if (C('dbdriver') == 'mysqli') {

                $_group = 'timeval';

            } else {

                $_group = 'WEEKDAY(FROM_UNIXTIME(stattime))+1';

            }

        }

        if($this->search_arr['search_type'] == 'month'){

            //计算横轴的最大量（由于每个月的天数不同）

            $dayofmonth = date('t',$searchtime_arr[0]);

            //构造横轴数据

            for($i=1; $i<=$dayofmonth; $i++){

                //横轴

                $stat_arr['xAxis']['categories'][] = $i;

                $statlist[$i] = 0;

            }

            $field .= ' ,day(FROM_UNIXTIME(stattime)) as timeval ';

            if (C('dbdriver') == 'mysqli') {

                $_group = 'timeval';

            } else {

                $_group = 'day(FROM_UNIXTIME(stattime))';

            }

        }

        $statlist_tmp = $model->statByFlowstat($flow_tablename, $where, $field, 0, 0, 'timeval asc', $_group);

        if ($statlist_tmp){

            foreach((array)$statlist_tmp as $k=>$v){

                $statlist[$v['timeval']] = floatval($v['amount']);

            }

        }

        //得到统计图数据

        $stat_arr['legend']['enabled'] = false;

        $stat_arr['series'][0]['name'] = '访问量';

        $stat_arr['series'][0]['data'] = array_values($statlist);

        $stat_arr['title'] = '店铺访问量统计';

        $stat_arr['yAxis'] = '访问次数';

        $stat_json = getStatData_LineLabels($stat_arr);

        Tpl::output('stat_json',$stat_json);

        Tpl::output('web_seo',C('site_name').' - '.'店铺流量统计');

        Tpl::showpage('seller_stat_storeflow');

    }



    



    /**

     * 商品流量TOP10

     */

    public function goodsflowOp(){

        $store_id = intval($_SESSION['store_id']);

        //确定统计分表名称

        $last_num = $store_id % 10; //获取店铺ID的末位数字

        $tablenum = ($t = intval(C('flowstat_tablenum'))) > 1 ? $t : 1; //处理流量统计记录表数量

        $flow_tablename = ($t = ($last_num % $tablenum)) > 0 ? "flowstat_$t" : 'flowstat';

        if(!$this->search_arr['search_type']){

            $this->search_arr['search_type'] = 'week';

        }

        $model = Model('stat');

        //获得搜索的开始时间和结束时间

        $searchtime_arr = $model->getStarttimeAndEndtime($this->search_arr);

        $where = array();

        $where['store_id'] = $store_id;

        $where['stattime'] = array('between',$searchtime_arr);

        $where['type'] = 'goods';



        $field = ' goods_id,SUM(clicknum) as amount';

        $stat_arr = array();

        //构造横轴数据

        for($i=1; $i<=10; $i++){

            //横轴

            $stat_arr['xAxis']['categories'][] = $i;

            $stat_arr['series'][0]['data'][] = array('name'=>'','y'=>0);

        }

        $statlist_tmp = $model->statByFlowstat($flow_tablename, $where, $field, 0, 10, 'amount desc,goods_id asc', 'goods_id');

        if ($statlist_tmp){

            $goodsid_arr = array();

            foreach((array)$statlist_tmp as $k=>$v){

                $goodsid_arr[] = $v['goods_id'];

            }

            //查询相应商品

            $goods_list_tmp = $model->statByGoods(array('goods_id'=>array('in',$goodsid_arr)), $field = 'goods_name,goods_id');

            foreach ((array)$goods_list_tmp as $k=>$v){

                $goods_list[$v['goods_id']] = $v;

            }

            foreach((array)$statlist_tmp as $k=>$v){

                $v['goods_name'] = $goods_list[$v['goods_id']];

                $v['amount'] = floatval($v['amount']);

                $statlist[] = $v;

                $stat_arr['series'][0]['data'][$k] = array('name'=>strval($goods_list[$v['goods_id']]['goods_name']),'y'=>floatval($v['amount']));

            }

        }

        //得到统计图数据

        $stat_arr['legend']['enabled'] = false;

        $stat_arr['series'][0]['name'] = '访问量';

        $stat_arr['title'] = '商品访问量TOP10';

        $stat_arr['yAxis'] = '访问次数';

        $stat_json = getStatData_Column2D($stat_arr);

        Tpl::output('stat_json',$stat_json);

        Tpl::output('web_seo',C('site_name').' - '.'商品流量分析');

        Tpl::showpage('seller_stat_goodsflow');

    }

      /**

     * 查询每月的周数组

     */

    public function getweekofmonthOp(){

        import('function.datehelper');

        $year = $_GET['y'];

        $month = $_GET['m'];

        $week_arr = getMonthWeekArr($year, $month);

        echo json_encode($week_arr);

        die;

    }



}

