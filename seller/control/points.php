<?php

/**

 * 默认展示页面

 *

 *

 *

 */





defined('Inshopec') or exit('Access Invalid!');

class pointsControl extends mobileHomeControl{

    public function indexOp(){

     

      if($_SESSION['is_login']){

        $model_member = Model('member');

       $member_info = $model_member->getMemberInfoByID($_SESSION['member_id']);

       $member['member_avatar'] =  getMemberAvatarForID($_SESSION['member_id']);

       $member['member_points'] = $member_info['member_points'];

       $member['member_name'] = $member_info['member_name'];

       $level_name = $model_member->getOneMemberGrade( $member_info['member_exppoints'],'true');

        //获得会员升级进度

       

       if ($level_name['less_exppoints'] == 0){

            $member['tipinfo'] ="已达到最高会员级别，继续加油保持这份荣誉哦！";

            

         } else {

            $member['tipinfo'] ="再累积{$level_name['less_exppoints']}经验值可升级{$level_name['upgrade_name']}";

       } 

        $member['exppoints_rate'] = $level_name['exppoints_rate'];

        $member['level_name'] = $level_name['level_name'];

       

        //查询已兑换并可以使用的代金券数量

        $model_voucher = Model('voucher');

        $vouchercount = $model_voucher->getCurrentAvailableVoucherCount($_SESSION['member_id']);

        $member['vouchercount'] = $vouchercount;

       //查询已兑换商品数(未取消订单)

        $pointordercount = Model('pointorder')->getMemberPointsOrderGoodsCount($_SESSION['member_id']);

        $member['pointordercount'] = $pointordercount;

        Tpl::output('member',$member);

      }

       



      //开启代金券功能后查询推荐的热门代金券列表

        if (C('voucher_allow') == 1){

            $recommend_voucher = Model('voucher')->getRecommendTemplate(6);

            foreach($recommend_voucher as &$vt){

                $vt['voucher_jd'] = sprintf('%.2f', ($vt['voucher_t_giveout']/$vt['voucher_t_total'])*100);

            }

         

            Tpl::output('recommend_voucher',$recommend_voucher);



        }

        //开启积分兑换功能后查询推荐的热门兑换商品列表

        if (C('pointprod_isuse') == 1){

            //热门积分兑换商品

            $recommend_pointsprod = Model('pointprod')->getRecommendPointProd(10);

            Tpl::output('recommend_pointsprod',$recommend_pointsprod);

            

        }

      

        

        $web_seo = C('site_name')."-积分中心";

        Tpl::output('web_seo',$web_seo); 

      

        Tpl::showpage('points_index');

    }

    //积分礼品

     public function giftsOp(){

        $web_seo = C('site_name')."-兑换礼品列表";

        Tpl::output('web_seo',$web_seo); 

        Tpl::showpage('points_gift');

    }

    //获取积分礼品列表

    public function get_giftsOp(){

       $page = new Page();

       $model_pointprod = Model('pointprod');



        //展示状态

        $pgoodsshowstate_arr = $model_pointprod->getPgoodsShowState();

        //开启状态

        $pgoodsopenstate_arr = $model_pointprod->getPgoodsOpenState();



        $model_member = Model('member');

       



        //查询兑换商品列表

        $where = array();

        $where['pgoods_show'] = $pgoodsshowstate_arr['show'][0];

        $where['pgoods_state'] = $pgoodsopenstate_arr['open'][0];

        //会员级别

        $level_filter = array();

        if (isset($_GET['level'])){

            $level_filter['search'] = intval($_GET['level']);

        }

        if (intval($_GET['isable']) == 1){

            $level_filter['isable'] = intval($member_info['level']);

        }

        if (count($level_filter) > 0){

            if (isset($level_filter['search']) && isset($level_filter['isable'])){

                $where['pgoods_limitmgrade'] = array(array('eq',$level_filter['search']),array('elt',$level_filter['isable']),'and');

            } elseif (isset($level_filter['search'])){

                $where['pgoods_limitmgrade'] = $level_filter['search'];

            } elseif (isset($level_filter['isable'])){

                $where['pgoods_limitmgrade'] = array('elt',$level_filter['isable']);

            }

        }





        //查询仅我能兑换和所需积分

        $points_filter = array();

        if (intval($_GET['isable']) == 1){

            $points_filter['isable'] = $member_info['member_points'];

        }

        if (intval($_GET['points_min']) > 0){

            $points_filter['min'] = intval($_GET['points_min']);

        }

        if (intval($_GET['points_max']) > 0){

            $points_filter['max'] = intval($_GET['points_max']);

        }

        if (count($points_filter) > 0){

            asort($points_filter);

            if (count($points_filter) > 1){

                $points_filter = array_values($points_filter);

                $where['pgoods_points'] = array('between',array($points_filter[0],$points_filter[1]));

            } else {

                if ($points_filter['min']){

                    $where['pgoods_points'] = array('egt',$points_filter['min']);

                } elseif ($points_filter['max']) {

                    $where['pgoods_points'] = array('elt',$points_filter['max']);

                } elseif ($points_filter['isable']) {

                    $where['pgoods_points'] = array('elt',$points_filter['isable']);

                }

            }

        }

        //排序

        switch ($_GET['order']){

            case '2':

                $orderby = 'pgoods_starttime desc,';

                break;

         

            case '1':

                $orderby = 'pgoods_points desc,';

                break;

         

        }

        $orderby .= 'pgoods_sort asc,pgoods_id desc';

        $pointprod_list = array();

        $pointprod_list = $model_pointprod->getPointProdList($where, '*', $orderby,'',$page);

        $page_count = $model_pointprod->gettotalpage();

        output_data(array('pointprod_list' => $pointprod_list), mobile_page($page_count));



       

    }

    //获取会员等级

    public function get_leverOp(){

        $model_member = Model('member');

        //查询会员等级

        $membergrade_arr = $model_member->getMemberGradeArr();

        output_data(array('level_list'=>$membergrade_arr));

       

    }

    //查询代金券面额

    public function get_priceListOp(){

        $model_voucher = Model('voucher');

        $pricelist = $model_voucher->getVoucherPriceList();

         $store_class = rkcache('store_class', true);

         $sclass= array();

         foreach($store_class as &$sc){

            $sclass[] =$sc;

         }

        output_data(array('pricelist'=>$pricelist,'store_class'=>$sclass));

 

    }

       

     //积分兑换卷

     public function listOp(){

        $web_seo = C('site_name')."-代金券列表";

        Tpl::output('web_seo',$web_seo); 

        Tpl::showpage('points_list');

    }

    //获取积分代金券列表

    public function get_listOp(){

         $model_voucher = Model('voucher');

        //代金券模板状态

        $templatestate_arr = $model_voucher->getTemplateState();



        $model_member = Model('member');

        //查询会员信息

        $member_info = $model_member->getMemberInfoByID($_SESSION['member_id']);

                

        //查询代金券列表

        $where = array();

        $gettype_arr = $model_voucher->getVoucherGettypeArray();

        $where['voucher_t_gettype'] = $gettype_arr['points']['sign'];

        $where['voucher_t_state'] = $templatestate_arr['usable'][0];

        $where['voucher_t_end_date'] = array('gt',time());

        if (intval($_GET['sc_id']) > 0){

            $where['voucher_t_sc_id'] = intval($_GET['sc_id']);

        }

        if (intval($_GET['price']) > 0){

            $where['voucher_t_price'] = intval($_GET['price']);

        }

        $store_id = intval($_GET['store_id']);

        if ($store_id > 0) {

            $where['voucher_t_store_id'] = $store_id;

        }

        //查询仅我能兑换和所需积分

        $points_filter = array();

        if (intval($_GET['isable']) == 1){

            $points_filter['isable'] = $member_info['member_points'];

        }

        if (intval($_GET['points_min']) > 0){

            $points_filter['min'] = intval($_GET['points_min']);

        }

        if (intval($_GET['points_max']) > 0){

            $points_filter['max'] = intval($_GET['points_max']);

        }

                

        if (count($points_filter) > 0){

            asort($points_filter);

            if (count($points_filter) > 1){

                $points_filter = array_values($points_filter);

                $where['voucher_t_points'] = array('between',array($points_filter[0],$points_filter[1]));

            } else {

                if ($points_filter['min']){

                    $where['voucher_t_points'] = array('egt',$points_filter['min']);

                } elseif ($points_filter['max']) {

                    $where['voucher_t_points'] = array('elt',$points_filter['max']);

                } elseif (isset($points_filter['isable'])) {

                    $where['voucher_t_points'] = array('elt',$points_filter['isable']);

                }

            }

        }

        //仅我能兑换的会员级别

        if (intval($_GET['isable']) == 1){

            $member_currgrade = $model_member->getOneMemberGrade($member_info['member_exppoints']);

            $member_info['member_grade_level'] = $member_currgrade?$member_currgrade['level']:0;

            $where['voucher_t_mgradelimit'] = array('elt',$member_info['member_grade_level']);

        }

        

        //排序

        switch ($_GET['order']){

            case '1':

                $orderby = 'voucher_t_giveout desc,';

                break;

          

            case '2':

                $orderby = 'voucher_t_points desc,';

                break;

         

        }

        $orderby .= 'voucher_t_id desc';

        $page = new Page();

        $voucherlist = array();

        $voucherlist = $model_voucher->getVoucherTemplateList($where, '*', 0, $page, $orderby);

         foreach($voucherlist as &$vt){

                $vt['voucher_jd'] = sprintf('%.2f', ($vt['voucher_t_giveout']/$vt['voucher_t_total'])*100);

                $vt['voucher_t_end_date'] = date('Y-m-d H:i:s',$vt['voucher_t_end_date']);

                $vt['voucher_t_sy'] =$vt['voucher_t_total'] - $vt['voucher_t_giveout'];

        }

        $page_count = $model_voucher->gettotalpage();

        output_data(array('voucherlist' => $voucherlist), mobile_page($page_count));

        // if ($store_id <= 0) {

        //     //查询代金券面额

        //     $pricelist = $model_voucher->getVoucherPriceList();

        //     Tpl::output('pricelist',$pricelist);

    

        //     //查询店铺分类

        //     $store_class = rkcache('store_class', true);

        //     Tpl::output('store_class', $store_class);

        // }

     

    }

    //积分兑换卷

     public function detailOp(){

      $pid = intval($_GET['pgoods_id']);

        if (!$pid){

            showMessage('参数错误',urlMobile('points','gifts'),'error');

        }

        $model_pointprod = Model('pointprod');

        //查询兑换礼品详细

        $prodinfo = $model_pointprod->getOnlinePointProdInfo(array('pgoods_id'=>$pid));

        if (empty($prodinfo)){

            showMessage('记录信息错误',urlMobile('points','gifts'),'error');

        }



        Tpl::output('prodinfo',$prodinfo);



        //更新礼品浏览次数

        $tm_tm_visite_pgoods = cookie('tm_visite_pgoods');

        $tm_tm_visite_pgoods = $tm_tm_visite_pgoods?explode(',', $tm_tm_visite_pgoods):array();

        if (!in_array($pid, $tm_tm_visite_pgoods)){//如果已经浏览过该商品则不重复累计浏览次数

            $result = $model_pointprod->editPointProdViewnum($pid);

            if ($result['state'] == true){//累加成功则cookie中增加该商品ID

                $tm_tm_visite_pgoods[] = $pid;

                setNcCookie('tm_visite_pgoods',implode(',', $tm_tm_visite_pgoods));

            }

        }



        //热门积分兑换商品

        $recommend_pointsprod = $model_pointprod->getRecommendPointProd(5);

        Tpl::output('recommend_pointsprod',$recommend_pointsprod);



     

      

        $web_seo = C('site_name')."-详情";

        Tpl::output('web_seo',$web_seo); 

      

        Tpl::showpage('points_detail');

    }

      //积分兑换卷

     public function recordsOp(){

         header("Access-Control-Allow-Origin:*");

        $pgoods_id = intval($_GET ['pgoods_id']);

        $model_pointprod = Model('pointprod');

        $prodinfo = $model_pointprod->getOnlinePointProdInfo(array('pgoods_id'=>$pgoods_id));

        if (empty($prodinfo)){

            showMessage('参数错误!',urlMobile('points','gifts'),'error');

        }

        $web_seo = C('site_name')."-".$prodinfo['pgoods_name'].'兑换记录';

        Tpl::output('web_seo',$web_seo); 

        Tpl::output('pgoods_id',$pgoods_id); 

        Tpl::showpage('points_records');

    }

     //积分兑换卷

     public function get_recordsOp(){

        $pgoods_id = intval($_GET ['pgoods_id']);

        $model_pointprod = Model('pointprod');

        $prodinfo = $model_pointprod->getOnlinePointProdInfo(array('pgoods_id'=>$pgoods_id));

        if (empty($prodinfo)){

            showMessage('参数错误!',urlMobile('points','gifts'),'error');

        }



        //查询兑换信息

        $model_pointorder = Model('pointorder');

        $pointorderstate_arr = $model_pointorder->getPointOrderStateBySign();

        $where = array();

        $where['point_orderstate'] = array('neq',$pointorderstate_arr['canceled'][0]);

        $where['point_goodsid'] = $pgoods_id;

        $atotal = $model_pointorder->table('points_ordergoods,points_order')->field('*')->join('left')->on('points_ordergoods.point_orderid=points_order.point_orderid')->where($where)->order('points_ordergoods.point_recid desc')->count();

        $page = intval($_GET['curpage'])-1;

        $pageSize = intval($_GET['page']); //每页显示数 

        $totalPage = ceil($atotal/$pageSize); //总页数 

        $startPage = $page*$pageSize;

        $dzpage = intval($_GET['curpage'])*$pageSize;

        $orderprod_list = $model_pointorder->getPointOrderAndGoodsList($where, '*', 0, "{$startPage},{$pageSize}",'points_ordergoods.point_recid desc');

       

        if ($orderprod_list){

            $buyerid_arr = array();

            foreach ($orderprod_list as $k=>&$v){

                $buyerid_arr[] = $v['point_buyerid'];

                $v['point_shippingtime'] = date('Y-m-d H:i:s', $v['point_shippingtime']);

            }

            $memberlist_tmp = Model('member')->getMemberList(array('member_id'=>array('in',$buyerid_arr)),'member_id,member_avatar');

            $memberlist = array();

            if ($memberlist_tmp){

                foreach ($memberlist_tmp as $v){

                    $memberlist[$v['member_id']] = $v;

                }

            }

            foreach ($orderprod_list as $k=>$v){

                $v['member_avatar'] = ($t = $memberlist[$v['point_buyerid']]['member_avatar'])?UPLOAD_SITE_URL.DS.ATTACH_AVATAR.DS.$t : UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS.C('default_user_portrait');

                $orderprod_list[$k] = $v;

            }

        }

            $arrs= array(); 

            if($atotal > $dzpage){

                $arrs['hasmore'] = true;

            }else{

                $arrs['hasmore'] = false;

            }

            

           

            $arrs['page_total'] = $totalPage;

            $arrs['datas']['orderprod_list'] = $orderprod_list;

        if($orderprod_list){

             $arrs['status'] = 1;

            echo json_encode($arrs);



        }else{

            $arrs['status'] = 0;

            echo json_encode($arrs); 

        }

    

          

    }

    /**

     * 兑换代金券保存信息

     *

     */

    public function voucherexchange_saveOp(){



        if($_SESSION['is_login'] != '1'){

            $login_url = urlMobile('login');

             output_error(array('url'=>$login_url));

        }

        $vid = intval($_POST['vid']);

    

        if ($vid <= 0){

            output_error('参数错误!');

        }

        $model_voucher = Model('voucher');

        //验证是否可以兑换代金券

        $data = $model_voucher->getCanChangeTemplateInfo($vid,intval($_SESSION['member_id']),intval($_SESSION['store_id']));

        if ($data['state'] == false){

      

            output_error($data['msg']);

        }

        //添加代金券信息

        $data = $model_voucher->exchangeVoucher($data['info'],$_SESSION['member_id'],$_SESSION['member_name']);

        if ($data['state'] == true){

            output_data($data['msg']);

        } else {

            output_error($data['msg']);

        }

    }



    /**

     * 商品详细页

     */

    public function goods_bodyOp() {

        header("Access-Control-Allow-Origin:*");

        $pgoods_id = intval($_GET ['pgoods_id']);

        $model_pointprod = Model('pointprod');

        $prodinfo = $model_pointprod->getOnlinePointProdInfo(array('pgoods_id'=>$pgoods_id));

        if (empty($prodinfo)){

            showMessage('参数错误!',urlMobile('points','gifts'),'error');

        }

        $web_seo = C('site_name')."-".$prodinfo['pgoods_name'];

        Tpl::output('web_seo',$web_seo); 

        Tpl::output('pgoods_id',$pgoods_id); 

        Tpl::output('pgoods_body',$prodinfo['pgoods_body']); 

        Tpl::showpage('points_info');

    }



    



}

