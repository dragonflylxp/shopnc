<?php
/**任务计划 - 月执行的任务
 * Created by PhpStorm.
 * User: suijiaolong
 * Date: 2016/11/16/016
 * Time: 18:31
 */

defined('Inshopec') or exit('Access Invalid!');

class monthControl extends BaseCronControl {

    /**
     * 该文件中所有任务执行频率，默认1天，单位：秒
     * @var int
     */
    const EXE_TIMES = 86400;

    /**
     * 优惠券即将到期提醒时间，单位：天
     * @var int
     */
    const VOUCHER_INTERVAL = 5;
    /**
     * 兑换码即将到期提醒时间，单位：天
     * @var int
     */
    const VR_CODE_INTERVAL = 5;
    /**
     * 订单结束后可评论时间，15天，60*60*24*15
     * @var int
     */
    const ORDER_EVALUATE_TIME = 1296000;
    /**
     * 订单结束后可追加评价时间，182.5， 60*60*24*182.5
     */
    const ORDER_EVALUATE_AGAIN_TIME = 15768000;

    /**
     * 每次到货通知消息数量
     * @var int
     */
    const ARRIVAL_NOTICE_NUM = 100;

    /**
     * 会员佣金提成比
     * @var int
     */
    private $member_commis = 2.7;

    private $_model_store;
    private $_model_store_ext;
    private $_model_bill;
    private $_model_order;
    private $_model_store_cost;
    private $_model_vr_bill;
    private $_model_vr_order;
    private $_model_member_real_bill;
    private $_model_member_vr_bill;
    private $_model_manager_bill;

    /**
     * 默认方法
     */
    public function indexOp() {
        //更新订单商品佣金值
        $this->_order_commis_rate_update();
        //生成结算
        $this->_create_bill();
        //生成管理员的月结算单[实物]
        $this->_create_manager_bill();
        //生成管理员的月结算单[虚拟]
        $this->_create_manager_vr_bill();

    }

    /**
     *生成管理员结算单
     */
    private function _create_manager_bill(){
        //取得管理员列表的条数
        $manager_member = Model('manager_member');
        $area = Model('area');
        $count = $manager_member->getManagerMemberCount();
        $step_num =50;
        //每次取出50条管理员的信息
        for($i = 0; $i <= $count; $i = $i + $step_num){
            $manager_list = $manager_member->getManagerMemberList(array(),'manager_member.*,manager.complete_company_name','','',"{$i},{$step_num}");
            if (is_array($manager_list) && $manager_list) {
                foreach ($manager_list as $kk => $manager_info) {
                    //判断管理员的添加时间 不可以为空  且要小于当前月份
                    if((strtotime(date('Y-m',$manager_info['add_time']))<(strtotime(date('Y-m',TIMESTAMP))))&&($manager_info['add_time'])){
                        switch($manager_info['grade']){
                            case $manager_info['grade']==1 :
                                switch($manager_info['area']){
                                    case $manager_info['area']=='华北':
                                        $province_id = [];
                                        $array = $area->getAreaList(array('area_region'=>'华北'),'area_id');
                                        foreach($array as $keys => $values){
                                            foreach($values as $k => $val){
                                                $province_id[] = $val;
                                            }
                                        }
                                        $condtion['province_id'] = array(in,$province_id);//用查询
                                        $condtion['is_own_shop'] = 0;//去除自营店铺
                                        $this->create_region($condtion,$manager_info);
                                        break;
                                    case $manager_info['area']=='东北':
                                        $province_id = [];
                                        $array = $area->getAreaList(array('area_region'=>'东北'),'area_id');
                                        foreach($array as $keys => $values){
                                            foreach($values as $k => $val){
                                                $province_id[] = $val;
                                            }
                                        }
                                        $condtion['province_id'] = array(in,$province_id);//用查询
                                        $condtion['is_own_shop'] = 0;//去除自营店铺
                                        $this->create_region($condtion,$manager_info);
                                        break;
                                    case $manager_info['area']=='华东':
                                        $province_id = [];
                                        $array = $area->getAreaList(array('area_region'=>'华东'),'area_id');
                                        foreach($array as $keys => $values){
                                            foreach($values as $k => $val){
                                                $province_id[] = $val;
                                            }
                                        }
                                        $condtion['province_id'] = array(in,$province_id);//用查询
                                        $condtion['is_own_shop'] = 0;//去除自营店铺
                                        $this->create_region($condtion,$manager_info);
                                        break;
                                    case $manager_info['area']=='华南':
                                        $province_id = [];
                                        $array = $area->getAreaList(array('area_region'=>'华南'),'area_id');
                                        foreach($array as $keys => $values){
                                            foreach($values as $k => $val){
                                                $province_id[] = $val;
                                            }
                                        }
                                        $condtion['province_id'] = array(in,$province_id);//用查询
                                        $condtion['is_own_shop'] = 0;//去除自营店铺
                                        $this->create_region($condtion,$manager_info);
                                        break;
                                    case $manager_info['area']=='华中':
                                        $province_id = [];
                                        $array = $area->getAreaList(array('area_region'=>'华中'),'area_id');
                                        foreach($array as $keys => $values){
                                            foreach($values as $k => $val){
                                                $province_id[] = $val;
                                            }
                                        }
                                        $condtion['province_id'] = array(in,$province_id);//用查询
                                        $condtion['is_own_shop'] = 0;//去除自营店铺
                                        $this->create_region($condtion,$manager_info);
                                        break;
                                    case $manager_info['area']=='西南':
                                        $province_id = [];
                                        $array = $area->getAreaList(array('area_region'=>'西南'),'area_id');
                                        foreach($array as $keys => $values){
                                            foreach($values as $k => $val){
                                                $province_id[] = $val;
                                            }
                                        }
                                        $condtion['province_id'] = array(in,$province_id);//用查询
                                        $condtion['is_own_shop'] = 0;//去除自营店铺
                                        $this->create_region($condtion,$manager_info);
                                        break;
                                    case $manager_info['area']=='西北':
                                        $province_id = [];
                                        $array = $area->getAreaList(array('area_region'=>'西北'),'area_id');
                                        foreach($array as $keys => $values){
                                            foreach($values as $k => $val){
                                                $province_id[] = $val;
                                            }
                                        }
                                        $condtion['province_id'] = array(in,$province_id);//用查询
                                        $condtion['is_own_shop'] = 0;//去除自营店铺
                                        $this->create_region($condtion,$manager_info);
                                        break;
                                    case $manager_info['area']=='港澳台':
                                        $province_id = [];
                                        $array = $area->getAreaList(array('area_region'=>'港澳台'),'area_id');
                                        foreach($array as $keys => $values){
                                            foreach($values as $k => $val){
                                                $province_id[] = $val;
                                            }
                                        }
                                        $condtion['province_id'] = array(in,$province_id);//用查询
                                        $condtion['is_own_shop'] = 0;//去除自营店铺
                                        $this->create_region($condtion,$manager_info);
                                        break;
                                    case $manager_info['area']=='海外':
                                        $province_id = [];
                                        $array = $area->getAreaList(array('area_region'=>'海外'),'area_id');
                                        foreach($array as $keys => $values){
                                            foreach($values as $k => $val){
                                                $province_id[] = $val;
                                            }
                                        }
                                        $condtion['province_id'] = array(in,$province_id);//用查询
                                        $condtion['is_own_shop'] = 0;//去除自营店铺
                                        $this->create_region($condtion,$manager_info);
                                        break;
                                }
                                break;
                            case $manager_info['grade']==2 :
                                $this->create_province($manager_info);
                                break;
                            case $manager_info['grade']==3 :
                                $this->create_city($manager_info);
                                break;
                            case $manager_info['grade']==4 :
                                $this->create_district($manager_info);
                                break;
                        }
                    }
                }
            }
        }
    }
    /**
     * 大区管理员的月结算
     * @param $condtion 用来查找该地区下的所有店铺
     * @param $manager_info 该大区的管理员信息
     * @throws Exception
     */
    private function create_region($condtion,$manager_info){
        $store = Model('store');///后期删除
        $manager_bill = Model('manager_bill');//后期删除
        $order_bill_month = Model('bill');//后期删除
        $store_id_array =[];
        //查找该大区下的所有的省的有效店铺的id
        $store_id=$store->getStoreOnlineList($condtion,'','','store_id');

        if(sizeof($store_id)>=1){
            for($i=0;$i<=count($store_id)-1;$i++){
                $store_id_array[] = $store_id["$i"]['store_id'];
            }

            //查出该大区下的店铺的月佣金总和
            $month_condtion['ob_store_id']=array(in,$store_id_array);
            //取得开始和结束日期
            $current_time = strtotime(date('Y-m-01 00:00:01',TIMESTAMP));//当前时间
            $time = strtotime('-1 month',$current_time);//上个月
            $month_condtion['ob_start_date'] = strtotime(date('Y-m-01 00:00:00', $time));//开始时间是上个月的1号
            $month_condtion['ob_end_date'] = strtotime(date('Y-m-01 23:59:59', $time)." +1 month -1 day");////该月最后一天最后一秒时unix时间戳


            $commis_totals =  $order_bill_month->getOrderBillByMonthList($month_condtion,'(ob_commis_totals-ob_commis_return_totals) as totals ');

            $totals=0;//月佣金总和
            foreach($commis_totals as $va){
                foreach($va as $key=>$value){
                    $totals +=$value;
                }
            }

            //查询是否有这个月的信息
            $manager_bill_condtion['grade'] = $manager_info['grade'];
            $manager_bill_condtion['area_region'] = $manager_info['area'];
            $manager_bill_condtion['start_time'] = $month_condtion['ob_start_date'];
            $manager_bill_condtion['end_time'] = $month_condtion['ob_end_date'];
            //如果不存在 则生成上个月的管理员结算账单

            if(!$manager_bill->getManagerBillInfo($manager_bill_condtion)){

                $data['start_time']= $month_condtion['ob_start_date'];//开始结算时间
                $data['end_time']= $month_condtion['ob_end_date'];//结算时间
                $data['total']= ncPriceFormat($totals*round($manager_info['point']/100,2));//本月管理员结算金额
                $data['uid']= $manager_info['uid'];//管理员的id
                $data['manager_name']= $manager_info['complete_company_name'];//管理人公司名
                $data['area_region']= $manager_info['area'];//管理员的管理区域
                $data['province_id']= $manager_info['province'];//省
                $data['city_id']= $manager_info['city'];//市
                $data['district_id']= $manager_info['district'];//管理地区
                $data['grade']= $manager_info['grade'];//管理员等级
                $update =$manager_bill->addManagerBill($data);
                if(!$update){
                    throw new Exception('更新账单失败');
                }

            };
        }

    }

    /**省管理员月结算
     * @param $manager_info 该省份的管理员信息
     * @throws Exception
     */
    private function create_province($manager_info){
        $store = Model('store');///后期删除
        $manager_bill = Model('manager_bill');//后期删除
        $order_bill_month = Model('bill');//后期删除
        $condtion['province_id'] = $manager_info['province'];
        $condtion['is_own_shop'] = 0;//去除自营店铺
        $store_id_array =[];

        //查找该大区下的所有的省的有效店铺的id
        $store_id=$store->getStoreOnlineList($condtion,'','','store_id');
        if(sizeof($store_id)>=1){
            for($i=0;$i<=count($store_id)-1;$i++){
                $store_id_array[] = $store_id["$i"]['store_id'];
            }

            //查出该大区下的店铺的月佣金总和
            $month_condtion['ob_store_id']=array(in,$store_id_array);
            //取得开始和结束日期
            $current_time = strtotime(date('Y-m-01 00:00:01',TIMESTAMP));//当前时间
            $time = strtotime('-1 month',$current_time);//上个月
            $month_condtion['ob_start_date'] = strtotime(date('Y-m-01 00:00:00', $time));//开始时间是上个月的1号
            $month_condtion['ob_end_date'] = strtotime(date('Y-m-01 23:59:59', $time)." +1 month -1 day");////该月最后一天最后一秒时unix时间戳


            $commis_totals =  $order_bill_month->getOrderBillByMonthList($month_condtion,'(ob_commis_totals-ob_commis_return_totals) as totals ');
            $totals=0;//月佣金总和
            foreach($commis_totals as $va){
                foreach($va as $key=>$value){
                    $totals +=$value;
                }
            }
            //查询是否有这个月的信息
            $manager_bill_condtion['grade'] = $manager_info['grade'];
            $manager_bill_condtion['province_id'] = $manager_info['province'];
            $manager_bill_condtion['start_time'] = $month_condtion['ob_start_date'];
            $manager_bill_condtion['end_time'] = $month_condtion['ob_end_date'];
            //如果不存在 则生成上个月的管理员结算账单

            if(!$manager_bill->getManagerBillInfo($manager_bill_condtion)){

                $data['start_time']= $month_condtion['ob_start_date'];//开始结算时间
                $data['end_time']= $month_condtion['ob_end_date'];//结算时间
                $data['total']= ncPriceFormat($totals*round($manager_info['point']/100,2));//本月管理员结算金额
                $data['uid']= $manager_info['uid'];//管理员的id
                $data['manager_name']= $manager_info['complete_company_name'];//管理人公司名
                $data['area_region']= $manager_info['area'];//管理员的管理区域
                $data['province_id']= $manager_info['province'];//省
                $data['city_id']= $manager_info['city'];//市
                $data['district_id']= $manager_info['district'];//管理地区
                $data['grade']= $manager_info['grade'];//管理员等级
                $update =$manager_bill->addManagerBill($data);
                if(!$update){
                    throw new Exception('更新账单失败');
                }

            };
        }

    }

    /**市管理员月结算
     * @param $manager_info 该市的管理员信息
     * @throws Exception
     */
    private function create_city($manager_info){
        $store = Model('store');///后期删除
        $manager_bill = Model('manager_bill');//后期删除
        $order_bill_month = Model('bill');//后期删除
        $store_id_array =[];
        $condtion['city_id'] = $manager_info['city'];
        $condtion['is_own_shop'] = 0;//去除自营店铺
        //查找该大区下的所有的省的有效店铺的id
        $store_id=$store->getStoreOnlineList($condtion,'','','store_id');
        if(sizeof($store_id)>=1){
            for($i=0;$i<=count($store_id)-1;$i++){
                $store_id_array[] = $store_id["$i"]['store_id'];
            }

            //查出该大区下的店铺的月佣金总和
            $month_condtion['ob_store_id']=array(in,$store_id_array);
            //取得开始和结束日期
            $current_time = strtotime(date('Y-m-01 00:00:01',TIMESTAMP));//当前时间
            $time = strtotime('-1 month',$current_time);//上个月
            $month_condtion['ob_start_date'] = strtotime(date('Y-m-01 00:00:00', $time));//开始时间是上个月的1号
            $month_condtion['ob_end_date'] = strtotime(date('Y-m-01 23:59:59', $time)." +1 month -1 day");////该月最后一天最后一秒时unix时间戳


            $commis_totals =  $order_bill_month->getOrderBillByMonthList($month_condtion,'(ob_commis_totals-ob_commis_return_totals) as totals ');
            $totals=0;//月佣金总和
            foreach($commis_totals as $va){
                foreach($va as $key=>$value){
                    $totals +=$value;
                }
            }
            //查询是否有这个月的信息
            $manager_bill_condtion['grade'] = $manager_info['grade'];
            $manager_bill_condtion['city_id'] = $manager_info['city'];
            $manager_bill_condtion['start_time'] = $month_condtion['ob_start_date'];
            $manager_bill_condtion['end_time'] = $month_condtion['ob_end_date'];
            //如果不存在 则生成上个月的管理员结算账单

            if(!$manager_bill->getManagerBillInfo($manager_bill_condtion)){

                $data['start_time']= $month_condtion['ob_start_date'];//开始结算时间
                $data['end_time']= $month_condtion['ob_end_date'];//结算时间
                $data['total']= ncPriceFormat($totals*round($manager_info['point']/100,2));//本月管理员结算金额
                $data['uid']= $manager_info['uid'];//管理员的id
                $data['manager_name']= $manager_info['complete_company_name'];//管理人公司名
                $data['area_region']= $manager_info['area'];//管理员的管理区域
                $data['province_id']= $manager_info['province'];//省
                $data['city_id']= $manager_info['city'];//市
                $data['district_id']= $manager_info['district'];//管理地区
                $data['grade']= $manager_info['grade'];//管理员等级
                $update =$manager_bill->addManagerBill($data);
                if(!$update){
                    throw new Exception('更新账单失败');
                }

            };
        }

    }

    /**县区管理员月结算
     * @param $manager_info 该县区的管理员信息
     * @throws Exception
     */
    private function create_district($manager_info){
        $store = Model('store');///后期删除
        $manager_bill = Model('manager_bill');//后期删除
        $order_bill_month = Model('bill');//后期删除
        $store_id_array =[];
        $condtion['district_id'] = $manager_info['district'];
        $condtion['is_own_shop'] = 0;//去除自营店铺
        //查找该大区下的所有的省的有效店铺的id
        $store_id=$store->getStoreOnlineList($condtion,'','','store_id');

        if(sizeof($store_id)>=1){

            for($i=0;$i<=count($store_id)-1;$i++){
                $store_id_array[] = $store_id["$i"]['store_id'];
            }
            //查出该大区下的店铺的月佣金总和
            $month_condtion['ob_store_id']=array(in,$store_id_array);
            //取得开始和结束日期
            $current_time = strtotime(date('Y-m-01 00:00:01',TIMESTAMP));//当前时间
            $time = strtotime('-1 month',$current_time);//上个月
            $month_condtion['ob_start_date'] = strtotime(date('Y-m-01 00:00:00', $time));//开始时间是上个月的1号
            $month_condtion['ob_end_date'] = strtotime(date('Y-m-01 23:59:59', $time)." +1 month -1 day");////该月最后一天最后一秒时unix时间戳


            $commis_totals =  $order_bill_month->getOrderBillByMonthList($month_condtion,'(ob_commis_totals-ob_commis_return_totals) as totals ');
            $totals=0;//月佣金总和
            foreach($commis_totals as $va){
                foreach($va as $key=>$value){
                    $totals +=$value;
                }
            }
            //查询是否有这个月的信息
            $manager_bill_condtion['grade'] = $manager_info['grade'];
            $manager_bill_condtion['district_id'] = $manager_info['district'];
            $manager_bill_condtion['start_time'] = $month_condtion['ob_start_date'];
            $manager_bill_condtion['end_time'] = $month_condtion['ob_end_date'];
            //如果不存在 则生成上个月的管理员结算账单

            if(!$manager_bill->getManagerBillInfo($manager_bill_condtion)){

                $data['start_time']= $month_condtion['ob_start_date'];//开始结算时间
                $data['end_time']= $month_condtion['ob_end_date'];//结算时间
                $data['total']= ncPriceFormat($totals*round($manager_info['point']/100,2));//本月管理员结算金额
                $data['uid']= $manager_info['uid'];//管理员的id
                $data['manager_name']= $manager_info['complete_company_name'];//管理人公司名
                $data['area_region']= $manager_info['area'];//管理员的管理区域
                $data['province_id']= $manager_info['province'];//省
                $data['city_id']= $manager_info['city'];//市
                $data['district_id']= $manager_info['district'];//管理地区
                $data['grade']= $manager_info['grade'];//管理员等级
                $update =$manager_bill->addManagerBill($data);
                if(!$update){
                    throw new Exception('更新账单失败');
                }

            }
        }

    }


    /**
     * 生成管理员的月结算[虚拟]
     */
    private function _create_manager_vr_bill(){
        //取得管理员列表的条数

        $manager_member = Model('manager_member');

        $area = Model('area');
        $count = $manager_member->getManagerMemberCount();
        $step_num =50;
        //每次取出50条管理员的信息
        for($i = 0; $i <= $count; $i = $i + $step_num){
            $manager_list = $manager_member->getManagerMemberList(array(),'manager_member.*,manager.complete_company_name','','',"{$i},{$step_num}");
            if (is_array($manager_list) && $manager_list) {
                foreach ($manager_list as $kk => $manager_info) {
                    //判断管理员的添加时间 不可以为空  且要小于当前月份
                    if((strtotime(date('Y-m',$manager_info['add_time']))<strtotime(date('Y-m',TIMESTAMP)))&&($manager_info['add_time'])){
                        switch($manager_info['grade']){
                            case $manager_info['grade']==1 :
                                switch($manager_info['area']){
                                    case $manager_info['area']=='华北':

                                        $province_id = [];
                                        $array = $area->getAreaList(array('area_region'=>'华北'),'area_id');
                                        foreach($array as $keys => $values){
                                            foreach($values as $k => $val){
                                                $province_id[] = $val;
                                            }
                                        }
                                        $condtion['province_id'] = array(in,$province_id);//用查询
                                        $this->create_region_vr($condtion,$manager_info);
                                        break;
                                    case $manager_info['area']=='东北':
                                        $province_id = [];
                                        $array = $area->getAreaList(array('area_region'=>'东北'),'area_id');
                                        foreach($array as $keys => $values){
                                            foreach($values as $k => $val){
                                                $province_id[] = $val;
                                            }
                                        }
                                        $condtion['province_id'] = array(in,$province_id);//用查询
                                        $this->create_region_vr($condtion,$manager_info);
                                        break;
                                    case $manager_info['area']=='华东':
                                        $province_id = [];
                                        $array = $area->getAreaList(array('area_region'=>'华东'),'area_id');
                                        foreach($array as $keys => $values){
                                            foreach($values as $k => $val){
                                                $province_id[] = $val;
                                            }
                                        }
                                        $condtion['province_id'] = array(in,$province_id);//用查询
                                        $this->create_region_vr($condtion,$manager_info);
                                        break;
                                    case $manager_info['area']=='华南':
                                        $province_id = [];
                                        $array = $area->getAreaList(array('area_region'=>'华南'),'area_id');
                                        foreach($array as $keys => $values){
                                            foreach($values as $k => $val){
                                                $province_id[] = $val;
                                            }
                                        }
                                        $condtion['province_id'] = array(in,$province_id);//用查询
                                        $this->create_region_vr($condtion,$manager_info);
                                        break;
                                    case $manager_info['area']=='华中':
                                        $province_id = [];
                                        $array = $area->getAreaList(array('area_region'=>'华中'),'area_id');
                                        foreach($array as $keys => $values){
                                            foreach($values as $k => $val){
                                                $province_id[] = $val;
                                            }
                                        }
                                        $condtion['province_id'] = array(in,$province_id);//用查询
                                        $this->create_region_vr($condtion,$manager_info);
                                        break;
                                    case $manager_info['area']=='西南':
                                        $province_id = [];
                                        $array = $area->getAreaList(array('area_region'=>'西南'),'area_id');
                                        foreach($array as $keys => $values){
                                            foreach($values as $k => $val){
                                                $province_id[] = $val;
                                            }
                                        }
                                        $condtion['province_id'] = array(in,$province_id);//用查询
                                        $this->create_region_vr($condtion,$manager_info);
                                        break;
                                    case $manager_info['area']=='西北':
                                        $province_id = [];
                                        $array = $area->getAreaList(array('area_region'=>'西北'),'area_id');
                                        foreach($array as $keys => $values){
                                            foreach($values as $k => $val){
                                                $province_id[] = $val;
                                            }
                                        }
                                        $condtion['province_id'] = array(in,$province_id);//用查询
                                        $this->create_region_vr($condtion,$manager_info);
                                        break;
                                    case $manager_info['area']=='港澳台':
                                        $province_id = [];
                                        $array = $area->getAreaList(array('area_region'=>'港澳台'),'area_id');
                                        foreach($array as $keys => $values){
                                            foreach($values as $k => $val){
                                                $province_id[] = $val;
                                            }
                                        }
                                        $condtion['province_id'] = array(in,$province_id);//用查询
                                        $this->create_region_vr($condtion,$manager_info);
                                        break;
                                    case $manager_info['area']=='海外':
                                        $province_id = [];
                                        $array = $area->getAreaList(array('area_region'=>'海外'),'area_id');
                                        foreach($array as $keys => $values){
                                            foreach($values as $k => $val){
                                                $province_id[] = $val;
                                            }
                                        }
                                        $condtion['province_id'] = array(in,$province_id);//用查询
                                        $this->create_region_vr($condtion,$manager_info);
                                        break;
                                }
                                break;
                            case $manager_info['grade']==2 :
                                $this->create_province_vr($manager_info);
                                break;
                            case $manager_info['grade']==3 :
                                $this->create_city_vr($manager_info);
                                break;
                            case $manager_info['grade']==4 :
                                $this->create_district_vr($manager_info);
                                break;
                        }
                    }
                }
            }
        }





    }

    /**
     * 大区管理员的月结算[虚拟]
     * @param $condtion 用来查找该地区下的所有店铺
     * @param $manager_info 该大区的管理员信息
     * @throws Exception
     */
    private function create_region_vr($condtion,$manager_info){
        $store = Model('store');///后期删除
        $manager_vr_bill = Model('manager_vr_bill');//后期删除
        $order_vr_bill_month = Model('vr_bill');//后期删除
        $store_id_array =[];
        //查找该大区下的所有的省的有效店铺的id
        $store_id=$store->getStoreOnlineList($condtion,'','','store_id');

        if(sizeof($store_id)>=1){
            for($i=0;$i<=count($store_id)-1;$i++){
                $store_id_array[] = $store_id["$i"]['store_id'];
            }

            //查出该大区下的店铺的月佣金总和
            $month_condtion['ob_store_id']=array(in,$store_id_array);
            //取得开始和结束日期
            $current_time = strtotime(date('Y-m-01 00:00:01',TIMESTAMP));//当前时间
            $time = strtotime('-1 month',$current_time);//上个月
            $month_condtion['ob_start_date'] = strtotime(date('Y-m-01 00:00:00', $time));//开始时间是上个月的1号
            $month_condtion['ob_end_date'] = strtotime(date('Y-m-01 23:59:59', $time)." +1 month -1 day");////该月最后一天最后一秒时unix时间戳


            $commis_totals =  $order_vr_bill_month->getOrderBillByMonthList($month_condtion,'(ob_commis_totals) as totals ');
            $totals=0;//月佣金总和
            foreach($commis_totals as $va){
                foreach($va as $key=>$value){
                    $totals +=$value;
                }
            }
            //查询是否有这个月的信息
            $manager_bill_condtion['grade'] = $manager_info['grade'];
            $manager_bill_condtion['area_region'] = $manager_info['area'];
            $manager_bill_condtion['start_time'] = $month_condtion['ob_start_date'];
            $manager_bill_condtion['end_time'] = $month_condtion['ob_end_date'];
            //如果不存在 则生成上个月的管理员结算账单

            if(!$manager_vr_bill->getManagerBillInfo($manager_bill_condtion)){

                $data['start_time']= $month_condtion['ob_start_date'];//开始结算时间
                $data['end_time']= $month_condtion['ob_end_date'];//结算时间
                $data['total']= ncPriceFormat($totals*round($manager_info['point']/100,2));//本月管理员结算金额
                $data['uid']= $manager_info['uid'];//管理员的id
                $data['manager_name']= $manager_info['complete_company_name'];//管理人公司名
                $data['area_region']= $manager_info['area'];//管理员的管理区域
                $data['province_id']= $manager_info['province'];//省
                $data['city_id']= $manager_info['city'];//市
                $data['district_id']= $manager_info['district'];//管理地区
                $data['grade']= $manager_info['grade'];//管理员等级

                $update =$manager_vr_bill->addManagerBill($data);
                if(!$update){
                    throw new Exception('更新账单失败');
                }
            }
        }

    }
    /**省管理员月结算[虚拟]
     * @param $manager_info 该省份的管理员信息
     * @throws Exception
     */
    private function create_province_vr($manager_info){
        $store = Model('store');///后期删除
        $manager_vr_bill = Model('manager_vr_bill');//后期删除
        $order_vr_bill_month = Model('vr_bill');//后期删除
        $condtion['province_id'] = $manager_info['province'];
        $store_id_array =[];

        //查找该大区下的所有的省的有效店铺的id
        $store_id=$store->getStoreOnlineList($condtion,'','','store_id');
        if(sizeof($store_id)>=1){
            for($i=0;$i<=count($store_id)-1;$i++){
                $store_id_array[] = $store_id["$i"]['store_id'];
            }

            //查出该大区下的店铺的月佣金总和
            $month_condtion['ob_store_id']=array(in,$store_id_array);
            //取得开始和结束日期
            $current_time = strtotime(date('Y-m-01 00:00:01',TIMESTAMP));//当前时间
            $time = strtotime('-1 month',$current_time);//上个月
            $month_condtion['ob_start_date'] = strtotime(date('Y-m-01 00:00:00', $time));//开始时间是上个月的1号
            $month_condtion['ob_end_date'] = strtotime(date('Y-m-01 23:59:59', $time)." +1 month -1 day");////该月最后一天最后一秒时unix时间戳


            $commis_totals =  $order_vr_bill_month->getOrderBillByMonthList($month_condtion,'(ob_commis_totals) as totals ');
            $totals=0;//月佣金总和
            foreach($commis_totals as $va){
                foreach($va as $key=>$value){
                    $totals +=$value;
                }
            }
            //查询是否有这个月的信息
            $manager_bill_condtion['grade'] = $manager_info['grade'];
            $manager_bill_condtion['province_id'] = $manager_info['province'];
            $manager_bill_condtion['start_time'] = $month_condtion['ob_start_date'];
            $manager_bill_condtion['end_time'] = $month_condtion['ob_end_date'];
            //如果不存在 则生成上个月的管理员结算账单

            if(!$manager_vr_bill->getManagerBillInfo($manager_bill_condtion)){

                $data['start_time']= $month_condtion['ob_start_date'];//开始结算时间
                $data['end_time']= $month_condtion['ob_end_date'];//结算时间
                $data['total']= ncPriceFormat($totals*round($manager_info['point']/100,2));//本月管理员结算金额
                $data['uid']= $manager_info['uid'];//管理员的id
                $data['manager_name']= $manager_info['complete_company_name'];//管理人公司名
                $data['area_region']= $manager_info['area'];//管理员的管理区域
                $data['province_id']= $manager_info['province'];//省
                $data['city_id']= $manager_info['city'];//市
                $data['district_id']= $manager_info['district'];//管理地区
                $data['grade']= $manager_info['grade'];//管理员等级

                $update =$manager_vr_bill->addManagerBill($data);
                if(!$update){
                    throw new Exception('更新账单失败');
                }

            }
        }

    }
    /**市管理员月结算[虚拟]
     * @param $manager_info 该市的管理员信息
     * @throws Exception
     */
    private function create_city_vr($manager_info){
        $store = Model('store');///后期删除
        $manager_vr_bill = Model('manager_vr_bill');//后期删除
        $order_vr_bill_month = Model('vr_bill');//后期删除
        $store_id_array =[];
        $condtion['city_id'] = $manager_info['city'];
        //查找该大区下的所有的省的有效店铺的id
        $store_id=$store->getStoreOnlineList($condtion,'','','store_id');
        if(sizeof($store_id)>=1){
            for($i=0;$i<=count($store_id)-1;$i++){
                $store_id_array[] = $store_id["$i"]['store_id'];
            }

            //查出该大区下的店铺的月佣金总和
            $month_condtion['ob_store_id']=array(in,$store_id_array);
            //取得开始和结束日期
            $current_time = strtotime(date('Y-m-01 00:00:01',TIMESTAMP));//当前时间
            $time = strtotime('-1 month',$current_time);//上个月
            $month_condtion['ob_start_date'] = strtotime(date('Y-m-01 00:00:00', $time));//开始时间是上个月的1号
            $month_condtion['ob_end_date'] = strtotime(date('Y-m-01 23:59:59', $time)." +1 month -1 day");////该月最后一天最后一秒时unix时间戳


            $commis_totals =  $order_vr_bill_month->getOrderBillByMonthList($month_condtion,'(ob_commis_totals) as totals ');
            $totals=0;//月佣金总和
            foreach($commis_totals as $va){
                foreach($va as $key=>$value){
                    $totals +=$value;
                }
            }
            //查询是否有这个月的信息
            $manager_bill_condtion['grade'] = $manager_info['grade'];
            $manager_bill_condtion['city_id'] = $manager_info['city'];
            $manager_bill_condtion['start_time'] = $month_condtion['ob_start_date'];
            $manager_bill_condtion['end_time'] = $month_condtion['ob_end_date'];
            //如果不存在 则生成上个月的管理员结算账单

            if(!$manager_vr_bill->getManagerBillInfo($manager_bill_condtion)){

                $data['start_time']= $month_condtion['ob_start_date'];//开始结算时间
                $data['end_time']= $month_condtion['ob_end_date'];//结算时间
                $data['total']= ncPriceFormat($totals*round($manager_info['point']/100,2));//本月管理员结算金额
                $data['uid']= $manager_info['uid'];//管理员的id
                $data['manager_name']= $manager_info['complete_company_name'];//管理人公司名
                $data['area_region']= $manager_info['area'];//管理员的管理区域
                $data['province_id']= $manager_info['province'];//省
                $data['city_id']= $manager_info['city'];//市
                $data['district_id']= $manager_info['district'];//管理地区
                $data['grade']= $manager_info['grade'];//管理员等级
                $update =$manager_vr_bill->addManagerBill($data);
                if(!$update){
                    throw new Exception('更新账单失败');
                }

            }
        }

    }
    /**县区管理员月结算[虚拟]
     * @param $manager_info 该县区的管理员信息
     * @throws Exception
     */
    private function create_district_vr($manager_info){
        $store = Model('store');///后期删除
        $manager_vr_bill = Model('manager_vr_bill');//后期删除
        $order_vr_bill_month = Model('vr_bill');//后期删除
        $store_id_array =[];
        $condtion['district_id'] = $manager_info['district'];
        //查找该大区下的所有的省的有效店铺的id
        $store_id=$store->getStoreOnlineList($condtion,'','','store_id');

        if(sizeof($store_id)>=1){

            for($i=0;$i<=count($store_id)-1;$i++){
                $store_id_array[] = $store_id["$i"]['store_id'];
            }
            //查出该大区下的店铺的月佣金总和
            $month_condtion['ob_store_id']=array(in,$store_id_array);
            //取得开始和结束日期
            $current_time = strtotime(date('Y-m-01 00:00:01',TIMESTAMP));//当前时间
            $time = strtotime('-1 month',$current_time);//上个月
            $month_condtion['ob_start_date'] = strtotime(date('Y-m-01 00:00:00', $time));//开始时间是上个月的1号
            $month_condtion['ob_end_date'] = strtotime(date('Y-m-01 23:59:59', $time)." +1 month -1 day");////该月最后一天最后一秒时unix时间戳


            $commis_totals =  $order_vr_bill_month->getOrderBillByMonthList($month_condtion,'(ob_commis_totals) as totals ');
            $totals=0;//月佣金总和
            foreach($commis_totals as $va){
                foreach($va as $key=>$value){
                    $totals +=$value;
                }
            }
            //查询是否有这个月的信息
            $manager_bill_condtion['grade'] = $manager_info['grade'];
            $manager_bill_condtion['district_id'] = $manager_info['district'];
            $manager_bill_condtion['start_time'] = $month_condtion['ob_start_date'];
            $manager_bill_condtion['end_time'] = $month_condtion['ob_end_date'];
            //如果不存在 则生成上个月的管理员结算账单

            if(!$manager_vr_bill->getManagerBillInfo($manager_bill_condtion)){

                $data['start_time']= $month_condtion['ob_start_date'];//开始结算时间
                $data['end_time']= $month_condtion['ob_end_date'];//结算时间
                $data['total']= ncPriceFormat($totals*round($manager_info['point']/100,2));//本月管理员结算金额
                $data['uid']= $manager_info['uid'];//管理员的id
                $data['manager_name']= $manager_info['complete_company_name'];//管理人公司名
                $data['area_region']= $manager_info['area'];//管理员的管理区域
                $data['province_id']= $manager_info['province'];//省
                $data['city_id']= $manager_info['city'];//市
                $data['district_id']= $manager_info['district'];//管理地区
                $data['grade']= $manager_info['grade'];//管理员等级
                $update =$manager_vr_bill->addManagerBill($data);
                if(!$update){
                    throw new Exception('更新账单失败');
                }

            }
        }
    }
    private function _create_bill(){
        $this->_model_store = Model('store');
        $this->_model_store_ext = Model('store_extend');
        $this->_model_bill = Model('bill');
        $this->_model_order = Model('order');
        $this->_model_store_cost = Model('store_cost');
        $this->_model_vr_bill = Model('vr_bill');
        $this->_model_vr_order = Model('vr_order');
        $this->_model_member_vr_bill = Model('member_vr_bill');
        $this->_model_member_real_bill= Model('member_real_bill');

        //更新订单商品佣金值
        $this->_order_commis_rate_update();

        //实物订单结算
        $this->_real_order();

        //虚拟订单结算
        $this->_vr_order();

    }

    /**
     * 生成上月账单[实物订单]
     * 考虑到老版本，判断 一下有没有ID为1的店铺，如果没有，则向表中插入一条ID:1的记录。
     * 结算周期是每月的1号到月末，循环逐个生成每个店铺结算单。
     */
    private function _real_order() {
        //向前兼容
        $this->_model_store_ext = Model('store_extend');
        //查询店铺扩展信息
        if (!$this->_model_store_ext->getStoreExtendInfo(array('store_id'=>1))) {
            $this->_model_store_ext->addStoreExtend(array('store_id'=>1));
        }
        //取店铺数量
        $count = $this->_model_store_ext->getStoreExtendCount();

        $step_num = 100;
        for ($i = 0; $i <= $count; $i = $i + $step_num){
            //每次取出100个店铺信息
            $store_list = $this->_model_store_ext->getStoreExendList(array(),"{$i},{$step_num}");
            if (is_array($store_list) && $store_list) {
                foreach ($store_list as $kk => $store_info) {
                    //取得开始时间
                    $start_time = $this->_get_start_date($store_info['store_id']);
                    if ($start_time !== 0) {
                        //结算周期  按月结
                        $this->_create_bill_cycle_by_month($start_time,$store_info);
                    }
                }
            }
        }
    }

    /**
     * 结算周期为月结
     * @param unknown $start_time
     * @param unknown $store_info
     */
    private function _create_bill_cycle_by_month($start_unixtime,$store_info) {
        $i = 1;
        $start_unixtime = strtotime(date('Y-m-01 00:00:00', $start_unixtime));
        $current_time = strtotime(date('Y-m-01 00:00:01',TIMESTAMP));
        while (($time = strtotime('-'.$i.' month',$current_time)) >= $start_unixtime) {
            if (date('Ym',$start_unixtime) == date('Ym',$time)) {
                //如果两个月份相等检查库是里否存在
                $order_statis = Model()->cls()->table('bill_create_month')->where(array('os_month'=>date('Ym',$start_unixtime),'store_id'=>$store_info['store_id'],'os_type'=>0))->find();
                if ($order_statis) {
                    break;
                }
            }
            //该月第一天0时unix时间戳
            $first_day_unixtime = strtotime(date('Y-m-01 00:00:00', $time));
            //该月最后一天最后一秒时unix时间戳
            $last_day_unixtime = strtotime(date('Y-m-01 23:59:59', $time)." +1 month -1 day");
            $os_month = date('Ym',$first_day_unixtime);

            try {
                $this->_model_order->beginTransaction();
                //生成单个店铺月订单出账单
                $data = array();
                $data['ob_store_id'] = $store_info['store_id'];
                $data['ob_start_date'] = $first_day_unixtime;
                $data['ob_end_date'] = $last_day_unixtime;
                $this->_create_real_order_bill($data);

                $data = array();
                $data['os_month'] = $os_month;
                $data['os_type'] = 0;
                $data['store_id'] = $store_info['store_id'];
                Model()->cls()->table('bill_create_month')->insert($data);

                $this->_model_order->commit();
            } catch (Exception $e) {
                $this->log('实物账单:'.$e->getMessage());
                $this->_model_order->rollback();
            }
            $i++;
        }

    }

    /**
     * 取得结算开始时间
     * 从order_bill_month表中取该店铺结算单中最大的ob_end_date作为本次结算开始时间
     * 如果未找到结算单，则查询该店铺订单表(已经完成状态)和店铺费用表，把里面时间较早的那个作为本次结算开始时间
     * @param int $store_id
     */
    private function _get_start_date($store_id) {
        $bill_info = $this->_model_bill->getOrderBillByMonthInfo(array('ob_store_id'=>$store_id),'max(ob_end_date) as stime');
        $start_unixtime = 0;
        if ($bill_info['stime']){
            $start_unixtime = $bill_info['stime']+1;
        } else {
            $condition = array();
            $condition['order_state'] = ORDER_STATE_SUCCESS;
            $condition['store_id'] = $store_id;
            $condition['finnshed_time'] = array('gt',0);
            $order_info = $this->_model_order->getOrderInfo($condition,array(),'min(finnshed_time) as stime');
            $condition = array();
            $condition['cost_store_id'] = $store_id;
            $condition['cost_state'] = 0;
            $condition['cost_time'] = array('gt',0);
            $cost_info = $this->_model_store_cost->getStoreCostInfo($condition,'min(cost_time) as stime');
            if ($order_info['stime']) {
                if ($cost_info['stime']) {
                    $start_unixtime = $order_info['stime'] < $cost_info['stime'] ? $order_info['stime'] : $cost_info['stime'];
                } else {
                    $start_unixtime = $order_info['stime'];
                }
            } else {
                if ($cost_info['stime']) {
                    $start_unixtime = $cost_info['stime'];
                }
            }
            if ($start_unixtime) {
                $start_unixtime = strtotime(date('Y-m-d 00:00:00', $start_unixtime));
            }
        }
        return $start_unixtime;
    }

    /**
     * 取得结算开始时间
     * 从vr_order_bill_month表中取该店铺结算单中最大的ob_end_date作为本次结算开始时间
     * 如果未找到结算单，则查询该店铺订单表(已经完成状态)的订单最小时间作为本次结算开始时间
     * @param int $store_id
     */
    private function _get_vr_start_date($store_id) {
        $bill_info = $this->_model_vr_bill->getOrderBillByMonthInfo(array('ob_store_id'=>$store_id),'max(ob_end_date) as stime');
        $start_unixtime = 0;
        if ($bill_info['stime']){
            $start_unixtime = $bill_info['stime']+1;
        } else{
            $condition = array();
            $condition['order_state'] = array('in',array(ORDER_STATE_PAY,ORDER_STATE_SUCCESS));
            $condition['store_id'] = $store_id;
            //虚拟的时间生成是按照add_time
            $order_info = $this->_model_vr_order->getOrderInfo($condition,'min(add_time) as stime');
            if ($order_info['stime']){
                $start_unixtime = $order_info['stime'];
            }
            if ($start_unixtime) {
                $start_unixtime = strtotime(date('Y-m-d 00:00:00', $start_unixtime));
            }
        }
        return $start_unixtime;
    }

    /**
     *
     * 生成单个店铺 月结的订单出账单[实物订单]
     *
     * @param int $data
     */
    private function _create_real_order_bill($data){
        $data_bill['ob_start_date'] = $data['ob_start_date'];
        $data_bill['ob_end_date'] = $data['ob_end_date'];
        $data_bill['ob_state'] = 0;
        $data_bill['ob_store_id'] = $data['ob_store_id'];
        if (!$this->_model_bill->getOrderBillByMonthInfo(array('ob_store_id'=>$data['ob_store_id'],'ob_start_date'=>$data['ob_start_date']))){
            $insert = $this->_model_bill->addOrderBillByMonth($data_bill);
            if (!$insert) {
                throw new Exception('生成账单失败');
            }
            //对已生成空账单进行销量、退单、佣金统计
            $data_bill['ob_id'] = $insert;
            $update = $this->_calc_real_order_bill($data_bill);
            if (!$update){
                throw new Exception('更新账单失败');
            }
        }
    }

    /**
     * 计算某月内，某店铺的销量，退单量，佣金[实物订单]
     *
     * @param array $data_bill
     */
    private function _calc_real_order_bill($data_bill){

        $order_condition = array();
        $order_condition['order_state'] = ORDER_STATE_SUCCESS;
        $order_condition['store_id'] = $data_bill['ob_store_id'];
        $order_condition['finnshed_time'] = array('between',"{$data_bill['ob_start_date']},{$data_bill['ob_end_date']}");

        $update = array();

        //订单金额
        $fields = 'sum(order_amount) as order_amount,sum(rpt_amount) as rpt_amount,sum(shipping_fee) as shipping_amount,min(store_name) as store_name';
        $order_info =  $this->_model_order->getOrderInfo($order_condition,array(),$fields);
        $update['ob_order_totals'] = floatval($order_info['order_amount']);

        //红包
        $update['ob_rpt_amount'] = floatval($order_info['rpt_amount']);

        //运费
        $update['ob_shipping_totals'] = floatval($order_info['shipping_amount']);
        //店铺名字
        $store_info = $this->_model_store->getStoreInfoByID($data_bill['ob_store_id']);
        $update['ob_store_name'] = $store_info['store_name'];

        //佣金金额
        $order_info =  $this->_model_order->getOrderInfo($order_condition,array(),'count(DISTINCT order_id) as count');
        $order_count = $order_info['count'];
        $commis_rate_totals_array = array();
        //分批计算佣金，最后取总和
        for ($i = 0; $i <= $order_count; $i = $i + 300){
            $order_list = $this->_model_order->getOrderList($order_condition,'','order_id','',"{$i},300");
            $order_id_array = array();
            foreach ($order_list as $order_info) {
                $order_id_array[] = $order_info['order_id'];
            }
            if (!empty($order_id_array)){
                $order_goods_condition = array();
                $order_goods_condition['order_id'] = array('in',$order_id_array);
                $field = 'SUM(ROUND(goods_pay_price*commis_rate/100,2)) as commis_amount';
                $order_goods_info = $this->_model_order->getOrderGoodsInfo($order_goods_condition,$field);
                $commis_rate_totals_array[] = $order_goods_info['commis_amount'];
            }else{
                $commis_rate_totals_array[] = 0;
            }
        }
        $update['ob_commis_totals'] = floatval(array_sum($commis_rate_totals_array));

        //退款总额
        $model_refund = Model('refund_return');
        $refund_condition = array();
        $refund_condition['seller_state'] = 2;
        $refund_condition['store_id'] = $data_bill['ob_store_id'];
        $refund_condition['goods_id'] = array('gt',0);
        $refund_condition['admin_time'] = array(array('egt',$data_bill['ob_start_date']),array('elt',$data_bill['ob_end_date']),'and');
        $refund_info = $model_refund->getRefundReturnInfo($refund_condition,'sum(refund_amount) as refund_amount,sum(rpt_amount) as rpt_amount');
        $update['ob_order_return_totals'] = floatval($refund_info['refund_amount']);

        //全部退款时的红包
        $update['ob_rf_rpt_amount'] = floatval($refund_info['rpt_amount']);

        //退款佣金
        $refund  =  $model_refund->getRefundReturnInfo($refund_condition,'sum(ROUND(refund_amount*commis_rate/100,2)) as amount');
        if ($refund) {
            $update['ob_commis_return_totals'] = floatval($refund['amount']);
        } else {
            $update['ob_commis_return_totals'] = 0;
        }

        //店铺活动费用
        $model_store_cost = Model('store_cost');
        $cost_condition = array();
        $cost_condition['cost_store_id'] = $data_bill['ob_store_id'];
        $cost_condition['cost_state'] = 0;
        $cost_condition['cost_time'] = array(array('egt',$data_bill['ob_start_date']),array('elt',$data_bill['ob_end_date']),'and');
        $cost_info = $model_store_cost->getStoreCostInfo($cost_condition,'sum(cost_price) as cost_amount');
        $update['ob_store_cost_totals'] = floatval($cost_info['cost_amount']);

        $condition = array();
        $condition['store_id'] = $data_bill['ob_store_id'];
        $condition['log_state'] = 1;
        $condition['dis_pay_time'] = array(array('egt',$data_bill['ob_start_date']),array('elt',$data_bill['ob_end_date']),'and');
        $dis_pay = $model_store_cost->table('dis_pay')->field('sum(dis_pay_amount) as amount')->where($condition)->find();//分销佣金
        $update['ob_dis_pay_amount'] = floatval($dis_pay['amount']);

        //已经被取消的预定订单但未退还定金金额
        $model_order_book = Model('order_book');
        $condition = array();
        $condition['book_store_id'] = $data_bill['ob_store_id'];
        $condition['book_cancel_time'] = array('between',"{$data_bill['ob_start_date']},{$data_bill['ob_end_date']}");
        $order_book_info = $model_order_book->getOrderBookInfo($condition,'sum(book_real_pay) as pay_amount');
        $update['ob_order_book_totals'] = floatval($order_book_info['pay_amount']);

        //本期应结
        $update['ob_result_totals'] = $update['ob_order_totals'] + $update['ob_rpt_amount'] + $update['ob_order_book_totals'] - $update['ob_order_return_totals'] -
            $update['ob_commis_totals'] + $update['ob_commis_return_totals']- $update['ob_rf_rpt_amount'] - $update['ob_store_cost_totals'] - $update['ob_dis_pay_amount'];
        $update['ob_store_cost_totals'] ;
        $update['ob_create_date'] = TIMESTAMP;
        $update['ob_state'] = 1;
        $update['os_month'] = date('Ym',$data_bill['ob_end_date']+1);
        //return $this->_model_bill->editOrderBill($update,array('ob_id'=>$data_bill['ob_id']));

        //----------by gongbo---start-------
        if($this->_model_bill->editOrderBillByMonth($update,array('ob_id'=>$data_bill['ob_id']))){
            $result_amount = $update['ob_commis_totals']-$update['ob_commis_return_totals'];
            if($result_amount>0){
                $m_data_bill = [
                    'member_ob_start_date'      => $data_bill['ob_start_date'],     //开始时间
                    'member_ob_end_date'        => $data_bill['ob_end_date'],       //结束时间
                    'member_ob_result_totals'   => round($result_amount*$this->member_commis/100,2),     //结算金额
                    'member_ob_create_date'     => TIMESTAMP,                       //订单生成时间
                    'member_os_month'           => $update['os_month'],             //出账单应结时间
                    'member_store_ob_id'        => $data_bill['ob_id'],              //商家结算表id
                    'member_store_id'           => $data_bill['ob_store_id'],        //店铺id
                    'member_ob_commis'          => $this->member_commis,
                    'member_ob_no'              => date("ymdHis").rand(100,999).$data_bill['ob_id']
                ];
                return $this->_create_member_real_order_bill($m_data_bill);           //生成会员实物结算记录
            }else{
                return true;
            }
        }else{
            return false;
        }
        //----------by gongbo---end-------
    }

    /**
     * 生成账单[虚拟订单]
     */
    private function _vr_order() {
        $count = $this->_model_store_ext->getStoreExtendCount();

        $step_num = 100;
        for ($i = 0; $i <= $count; $i = $i + $step_num){
            //每次取出100个店铺信息
            $store_list = $this->_model_store_ext->getStoreExendList(array(),"{$i},{$step_num}");

            if (is_array($store_list) && $store_list) {
                foreach ($store_list as $kk => $store_info) {
                    $start_time = $this->_get_vr_start_date($store_info['store_id']);

                    if ($start_time !== 0) {
                        //结算周期  按月结
                        $this->_create_vr_bill_cycle_by_month($start_time,$store_info);
                    }
                }
            }
        }
    }

    /**
     * 结算周期为月结[虚拟]
     * @param unknown $start_time
     * @param unknown $store_info
     */
    private function _create_vr_bill_cycle_by_month($start_unixtime,$store_info) {
        $i = 1;
        $start_unixtime = strtotime(date('Y-m-01 00:00:00', $start_unixtime));
        $current_time = strtotime(date('Y-m-01 00:00:01',TIMESTAMP));
        while (($time = strtotime('-'.$i.' month',$current_time)) >= $start_unixtime){
            if (date('Ym',$start_unixtime) == date('Ym',$time)) {
                //如果两个月份相等检查库是里否存在
                $order_statis = Model()->cls()->table('bill_create_month')->where(array('os_month'=>date('Ym',$start_unixtime),'store_id'=>$store_info['store_id'],'os_type'=>1))->find();
                if ($order_statis) {
                    break;
                }
            }
            //该月第一天0时unix时间戳
            $first_day_unixtime = strtotime(date('Y-m-01 00:00:00', $time));
            //该月最后一天最后一秒时unix时间戳
            $last_day_unixtime = strtotime(date('Y-m-01 23:59:59', $time)." +1 month -1 day");
            $os_month = date('Ym',$first_day_unixtime);

            try {
                $this->_model_vr_order->beginTransaction();
                //生成单个店铺月订单出账单
                $data = array();
                $data['ob_store_id'] = $store_info['store_id'];
                $data['ob_start_date'] = $first_day_unixtime;
                $data['ob_end_date'] = $last_day_unixtime;
                $this->_create_vr_order_bill($data);

                $data = array();
                $data['os_month'] = $os_month;
                $data['os_type'] = 1;
                $data['store_id'] = $store_info['store_id'];
                Model()->cls()->table('bill_create_month')->insert($data);

                $this->_model_vr_order->commit();
            } catch (Exception $e) {
                $this->log('虚拟账单:'.$e->getMessage());
                $this->_model_vr_order->rollback();
            }
            $i++;
        }
    }

    /**
     * 生成所有店铺订单出账单[虚拟订单]
     *
     * @param int $data
     */
    private function _create_vr_order_bill($data){
        $data_bill['ob_start_date'] = $data['ob_start_date'];
        $data_bill['ob_end_date'] = $data['ob_end_date'];
        $data_bill['ob_state'] = 0;
        $data_bill['ob_store_id'] = $data['ob_store_id'];
        if (!$this->_model_vr_bill->getOrderBillByMonthInfo(array('ob_store_id'=>$data['ob_store_id'],'ob_start_date'=>$data['ob_start_date']))) {
            $insert = $this->_model_vr_bill->addOrderBillByMonth($data_bill);
            if (!$insert) {
                throw new Exception('生成账单失败');
            }
            //对已生成空账单进行销量、退单、佣金统计
            $data_bill['ob_id'] = $insert;
            $update = $this->_calc_vr_order_bill($data_bill);
            if (!$update){
                throw new Exception('更新账单失败');
            }

            /* // 发送店铺消息
             $param = array();
             $param['code'] = 'store_bill_affirm';
             $param['store_id'] = $data_bill['ob_store_id'];
             $param['param'] = array(
                 'state_time' => date('Y-m-d H:i:s', $data_bill['ob_start_date']),
                 'end_time' => date('Y-m-d H:i:s', $data_bill['ob_end_date']),
                 'bill_no' => $data_bill['ob_id']
             );
             QueueClient::push('sendStoreMsg', $param);*/
        }
    }

    /**
     * 计算某月内，某店铺的销量，佣金[虚拟的]
     *
     * @param array $data_bill
     */
    private function _calc_vr_order_bill($data_bill){

        //计算已使用兑换码
        $order_condition = array();
        $order_condition['vr_state'] = 1;
        $order_condition['store_id'] = $data_bill['ob_store_id'];
        $order_condition['vr_usetime'] = array('between',"{$data_bill['ob_start_date']},{$data_bill['ob_end_date']}");
        $update = array();
        //订单金额
        $fields = 'sum(pay_price) as order_amount,SUM(ROUND(pay_price*commis_rate/100,2)) as commis_amount';
        $order_info =  $this->_model_vr_order->getOrderCodeInfo($order_condition, $fields);
        $update['ob_order_totals'] = floatval($order_info['order_amount']);

        //佣金金额
        $update['ob_commis_totals'] = $order_info['commis_amount'];

        //计算已过期不退款兑换码
        $order_condition = array();
        $order_condition['vr_state'] = 0;
        $order_condition['store_id'] = $data_bill['ob_store_id'];
        $order_condition['vr_invalid_refund'] = 0;
        $order_condition['vr_indate'] = array('between',"{$data_bill['ob_start_date']},{$data_bill['ob_end_date']}");

        //订单金额
        $fields = 'sum(pay_price) as order_amount,SUM(ROUND(pay_price*commis_rate/100,2)) as commis_amount';
        $order_info = $this->_model_vr_order->getOrderCodeInfo($order_condition, $fields);
        $update['ob_order_totals'] += floatval($order_info['order_amount']);

        //佣金金额
        $update['ob_commis_totals'] += $order_info['commis_amount'];

        //店铺名
        $store_info = $this->_model_store->getStoreInfoByID($data_bill['ob_store_id']);
        $update['ob_store_name'] = $store_info['store_name'];

        //本期应结
        $update['ob_result_totals'] = $update['ob_order_totals'] - $update['ob_commis_totals'];
        $update['ob_create_date'] = TIMESTAMP;
        $update['ob_state'] = 1;
        $update['os_month'] = date('Ym',$data_bill['ob_end_date']+1);
        //return $this->_model_vr_bill->editOrderBill($update,array('ob_id'=>$data_bill['ob_id']));

        //----------by gongbo---start-------
        if($this->_model_vr_bill->editOrderBillByMonth($update,array('ob_id'=>$data_bill['ob_id']))){
            if($update['ob_result_totals']>0){
                $m_data_bill = [
                    'member_ob_start_date'      => $data_bill['ob_start_date'],     //开始时间
                    'member_ob_end_date'        => $data_bill['ob_end_date'],       //结束时间
                    'member_ob_result_totals'   => round($update['ob_commis_totals']*$this->member_commis/100,2),     //结算金额
                    'member_ob_create_date'     => TIMESTAMP,                       //订单生成时间
                    'member_os_month'           => $update['os_month'],             //出账单应结时间
                    'member_store_ob_id'        => $data_bill['ob_id'],              //商家结算表id
                    'member_store_id'           => $data_bill['ob_store_id'],        //店铺id
                    'member_ob_commis'          => $this->member_commis,
                    'member_ob_no'              => date("ymdHis").rand(100,999).$data_bill['ob_id']
                ];
                return $this->_create_member_vr_order_bill($m_data_bill);           //生成会员虚拟结算记录
            }else{
                return true;
            }
        }else{
            return false;
        }
        //----------by gongbo---end-------
    }

    //-----------------------------by gongbo start--------------
    /**
     * 生成会员（分享人）虚拟结算记录
     */
    private function _create_member_vr_order_bill($data){
        //判断该商家是否存在推荐人，若存在则生成推荐人结算记录，若不存在则不操作
        $refer_member_id = $this->_get_refer_member_id($data['member_store_id']);
        if($refer_member_id){           //存在并返回该推荐人member_id
            $condition= [
                'member_store_ob_id'    =>  $data['member_store_ob_id'],
                'member_ob_start_date'  =>  $data['member_ob_start_date']
            ];
            $data['member_ob_member_id'] = $refer_member_id;
            //查找该记录，如果不存在生成，如果存在更新
            $rs = $this->_model_member_vr_bill->getOrderBillInfo($condition);
            if(!$rs){
                return $this->_model_member_vr_bill->addOrderBill($data);
            }else{
                return $this->_model_member_vr_bill->editOrderBill(array('member_ob_id'=>$rs['member_ob_id']),$data);
            }
        }else{      //不存在，返回true,无操作
            return true;
        }
    }

    /**
     * 生成会员（分享人）实物结算记录
     */
    private function _create_member_real_order_bill($data){

        //判断该商家是否存在推荐人，若存在则生成推荐人结算记录，若不存在则不操作
        $refer_member_id = $this->_get_refer_member_id($data['member_store_id']);
        if($refer_member_id){           //存在并返回该推荐人member_id
            $condition= [
                'member_store_ob_id'    =>  $data['member_store_ob_id'],
                'member_ob_start_date'  =>  $data['member_ob_start_date']
            ];
            $data['member_ob_member_id'] = $refer_member_id;//该表不记录store_id
            //查找该记录，如果不存在生成，如果存在更新
            $rs = $this->_model_member_real_bill->getOrderBillInfo($condition);
            if(!$rs){
                return $this->_model_member_real_bill->addOrderBill($data);
            }else{
                return $this->_model_member_real_bill->editOrderBill(array('member_ob_id'=>$rs['member_ob_id']),$data);
            }
        }else{      //不存在，返回true,无操作
            return true;
        }
    }

    /**
     * 判断该商家是否存在推荐人，若存在返回该推荐人member_id，否则返回false;
     */
    private function _get_refer_member_id($store_id){
        $model  = Model();
        $field  = 'member.r_code';
        $on     = 'store.member_id=member.member_id';
        $model->table('member,store')->field($field);
        $result = $model->join('left')->on($on)->where(array('store.store_id'=>$store_id))->find();
        if($result && !empty($result['r_code'])){        //有推荐人
            $memberRs = Model('member')->where(array('code'=>$result['r_code'],'grade'=>'01'))->field('member_id')->find();
            if($memberRs){      //该推荐人存在
                return $memberRs['member_id'];
            }else{              //该推荐人不存在
                return false;
            }
        }else{                  //没有推荐人
            return false;
        }
    }
    //-----------------------------by gongbo end--------------
}