<?php
/**
 * 店铺管理界面
 *
 *
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net
 * @link       http://www.shopec.net
 * @since      File available since Release v1.1
 */

use shopec\Tpl;

defined('Inshopec') or exit('Access Invalid!');

class storeControl extends SystemControl{
    const EXPORT_SIZE = 1000;

    private $_links = array(
        array('url'=>'con=store&fun=store','text'=>'管理'),
        array('url'=>'con=store&fun=store_joinin','text'=>'开店申请'),
        array('url'=>'con=store&fun=reopen_list','text'=>'续签申请'),
        array('url'=>'con=store&fun=store_bind_class_applay_list','text'=>'经营类目申请'),
        array('url'=>'con=store&fun=bill_cycle','text'=>'结算周期设置')
    );

    public function __construct(){
        parent::__construct();
        Language::read('store,store_grade');
    }

    public function indexOp() {
        $this->storeOp();
    }

    /**
     * 店铺
     */
    public function storeOp(){
        //店铺等级
        $model_grade = Model('store_grade');
        $grade_list = $model_grade->getGradeList(array());
        Tpl::output('grade_list', $grade_list);

        //输出子菜单
        Tpl::output('top_link',$this->sublink($this->_links,'store'));

        Tpl::showpage('store.index');
    }

    /**
     * 店铺结算周期
     */
    public function bill_cycleOp(){

        Tpl::output('top_link',$this->sublink($this->_links,'bill_cycle'));

        Tpl::showpage('store.bill_cycle');
    }

    /**
     * 输出XML数据
     */
    public function get_xmlOp() {
        $model_store = Model('store');
        // 设置页码参数名称
        $condition = array();
        $condition['is_own_shop'] = 0;
        if ($_GET['store_name'] != '') {
            $condition['store_name'] = array('like', '%' . $_GET['store_name'] . '%');
        }
        if ($_GET['member_name'] != '') {
            $condition['member_name'] = array('like', '%' . $_GET['member_name'] . '%');
        }
        if ($_GET['seller_name'] != '') {
            $condition['seller_name'] = array('like', '%' . $_GET['seller_name'] . '%');
        }
        if ($_GET['grade_id'] != '') {
            $condition['grade_id'] = $_GET['grade_id'];
        }
        if ($_GET['store_state'] != '') {
            $condition['store_state'] = $_GET['store_state'];
        }
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        $order = '';
        $param = array('store_id','store_name','member_name','seller_name','store_time','store_end_time','store_state','grade_id','sc_id');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
                $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }

        $page = $_POST['rp'];

        //店铺列表
        $store_list = $model_store->getStoreList($condition, $page, $order);

        //店铺等级
        $model_grade = Model('store_grade');
        $grade_list = $model_grade->getGradeList(array());
        $grade_array = array();
        if (!empty($grade_list)){
            foreach ($grade_list as $v){
                $grade_array[$v['sg_id']] = $v['sg_name'];
            }
        }

        //店铺分类
        $model_store_class = Model('store_class');
        $class_list = $model_store_class->getStoreClassList(array(),'',false);
        $class_array = array();
        if (!empty($class_list)) {
            foreach ($class_list as $v) {
                $class_array[$v['sc_id']] = $v['sc_name'];
            }
        }

        $data = array();
        $data['now_page'] = $model_store->shownowpage();
        $data['total_num'] = $model_store->gettotalnum();
        foreach ($store_list as $value) {
            $param = array();
            $store_state = $this->getStoreState($value);
            $operation = "<a class='btn green' href='index.php?con=store&fun=store_joinin_detail&member_id=".$value['member_id']."'><i class='fa fa-list-alt'></i>查看</a><span class='btn'><em><i class='fa fa-cog'></i>" . L('nc_set') . " <i class='arrow'></i></em><ul><li><a href='index.php?con=store&fun=store_edit&store_id=" . $value['store_id'] . "'>编辑店铺信息</a></li><li><a href='index.php?con=store&fun=store_bind_class&store_id=" . $value['store_id'] . "'>修改经营类目</a></li><li><a href='index.php?con=store&fun=store_merchant_basicInfo&store_id=" . $value['store_id'] . "'>商户信息登记</a></li><li><a href='index.php?con=store&fun=store_merchant_bankInfo&store_id=" . $value['store_id'] . "'>绑定银行账户</a></li><li><a href='index.php?con=store&fun=store_merchant_busiInfo&store_id=" . $value['store_id'] . "'>开通支付业务</a></li>";
            if (str_cut($store_state, 6) == 'expire'  && cookie('remindRenewal'.$value['store_id']) == null) {
                $operation .= "<li><a class='expire' href=". urlAdminShop('store', 'remind_renewal', array('store_id'=>$value['store_id'])). ">提醒商家续费</a></li>";
            }
            $operation .= "</ul></span>";
            $param['operation'] = $operation;
            $param['store_id'] = $value['store_id'];
            $store_name = "<a class='" . $store_state . "' href='". urlShop('show_store', 'index', array('store_id' => $value['store_id'])) ."' target='blank'>";
            if ($store_state == 'expired') {
                $store_name .= "<i class='fa fa-clock-o' title='该店铺已过期，可从编辑菜单提醒续费'></i>";
            } else if ($store_state == 'expire') {
                $store_name .= "<i class='fa fa-bell-o' title='该店铺即将到期，可从编辑菜单提醒续费'></i>";
            }
            $store_name .= $value['store_name'] . "<i class='fa fa-external-link ' title='新窗口打开'></i></a>";
            $param['store_name'] = $store_name;
            $param['member_id'] = $value['member_name'];
            $param['seller_name'] = $value['seller_name'];
            $param['store_avatar'] = "<a href='javascript:void(0);' class='pic-thumb-tip' onMouseOut='toolTip()' onMouseOver='toolTip(\"<img src=".getStoreLogo($value['store_avatar']).">\")'><i class='fa fa-picture-o'></i></a>";
            $param['store_label'] = "<a href='javascript:void(0);' class='pic-thumb-tip' onMouseOut='toolTip()' onMouseOver='toolTip(\"<img src=".getStoreLogo($value['store_label'], 'store_logo').">\")'><i class='fa fa-picture-o'></i></a>";
            $param['grade_id'] = $grade_array[$value['grade_id']];
            $param['store_time'] = date('Y-m-d', $value['store_time']);
            $param['store_end_time'] = $value['store_end_time']?date('Y-m-d', $value['store_end_time']):L('no_limit');
            $param['store_state'] = $value['store_state']?L('open'):L('close');
            $param['sc_id'] = $class_array[$value['sc_id']];
            $param['area_info'] = $value['area_info'];
            $param['store_address'] = $value['store_address'];
            $param['store_qq'] = $value['store_qq'];
            $param['store_ww'] = $value['store_ww'];
            $param['store_phone'] = $value['store_phone'];
            $data['list'][$value['store_id']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }

    /**
     * 输出XML数据
     */
    public function get_bill_cycle_xmlOp() {
        $model_store = Model('store');
        $condition = array();
        if ($_GET['store_name'] != '') {
            $condition['store_name'] = array('like', '%' . $_GET['store_name'] . '%');
        }
        if ($_GET['member_name'] != '') {
            $condition['member_name'] = array('like', '%' . $_GET['member_name'] . '%');
        }
        if ($_GET['seller_name'] != '') {
            $condition['seller_name'] = array('like', '%' . $_GET['seller_name'] . '%');
        }
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        $order = '';
        $param = array('store_id','store_name','member_name','seller_name','store_time','store_end_time','store_state','grade_id','sc_id');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }

        $page = $_POST['rp'];
    
        //店铺列表
        $store_list = $model_store->getStoreList($condition, $page, $order);
    
        //店铺分类
        $model_store_class = Model('store_class');
        $class_list = $model_store_class->getStoreClassList(array(),'',false);
        $class_array = array();
        if (!empty($class_list)) {
            foreach ($class_list as $v) {
                $class_array[$v['sc_id']] = $v['sc_name'];
            }
        }

        //店铺结算周期
        $store_id_list = array();
        foreach ($store_list as $store_info) {
            $store_id_list[] = $store_info['store_id'];
        }
        $store_ext_list = Model('store_extend')->getStoreExendList(array('store_id'=>array('in',$store_id_list)));
        $store_bill_cycle = array();
        if ($store_ext_list) {
            foreach($store_ext_list as $v) {
                $store_bill_cycle[$v['store_id']] = $v['bill_cycle'] ? $v['bill_cycle'] : '';
            }
        }

        $data = array();
        $data['now_page'] = $model_store->shownowpage();
        $data['total_num'] = $model_store->gettotalnum();
        foreach ($store_list as $value) {
            $param = array();
            $store_state = $this->getStoreState($value);
            $operation = "<a class='btn blue' href='index.php?con=store&fun=bill_cycyle_edit&store_id=".$value['store_id']."'><i class='fa fa-pencil-square-o'></i>编辑</a>";
            $operation .= "</ul></span>";
            $param['operation'] = $operation;
            $param['store_id'] = $value['store_id'];
            $store_name = "<a class='" . $store_state . "' href='". urlShop('show_store', 'index', array('store_id' => $value['store_id'])) ."' target='blank'>";

            $store_name .= $value['store_name'] . "<i class='fa fa-external-link ' title='新窗口打开'></i></a>";
            $param['store_name'] = $store_name;
            $param['seller_name'] = $value['seller_name'];
            $param['bill_cycle'] = $store_bill_cycle[$value['store_id']];
            $param['sc_id'] = $class_array[$value['sc_id']];
            $param['store_phone'] = $value['store_phone'];
            $data['list'][$value['store_id']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }
    

    /**
     * csv导出
     */
    public function export_csvOp() {
        $model_store = Model('store');
        $condition = array();
        $limit = false;
        if ($_GET['id'] != '') {
            $id_array = explode(',', $_GET['id']);
            $condition['store_id'] = array('in', $id_array);
        }
        if ($_GET['store_name'] != '') {
            $condition['store_name'] = array('like', '%' . $_GET['store_name'] . '%');
        }
        if ($_GET['member_name'] != '') {
            $condition['member_name'] = array('like', '%' . $_GET['member_name'] . '%');
        }
        if ($_GET['seller_name'] != '') {
            $condition['seller_name'] = array('like', '%' . $_GET['seller_name'] . '%');
        }
        if ($_GET['grade_id'] != '') {
            $condition['grade_id'] = $_GET['grade_id'];
        }
        if ($_GET['store_state'] != '') {
            $condition['store_state'] = $_GET['store_state'];
        }
        if ($_REQUEST['query'] != '') {
            $condition[$_REQUEST['qtype']] = array('like', '%' . $_REQUEST['query'] . '%');
        }
        $order = '';
        $param = array('store_id','store_name','member_name','seller_name','store_time','store_end_time','store_state','grade_id','sc_id');
        if (in_array($_REQUEST['sortname'], $param) && in_array($_REQUEST['sortorder'], array('asc', 'desc'))) {
            $order = $_REQUEST['sortname'] . ' ' . $_REQUEST['sortorder'];
        }
        if (!is_numeric($_GET['curpage'])){
            $count = $model_store->getStoreCount($condition);
            if ($count > self::EXPORT_SIZE ){   //显示下载链接
                $array = array();
                $page = ceil($count/self::EXPORT_SIZE);
                for ($i=1;$i<=$page;$i++){
                    $limit1 = ($i-1)*self::EXPORT_SIZE + 1;
                    $limit2 = $i*self::EXPORT_SIZE > $count ? $count : $i*self::EXPORT_SIZE;
                    $array[$i] = $limit1.' ~ '.$limit2 ;
                }
                Tpl::output('list',$array);
                Tpl::output('murl','index.php?con=store&fun=index');
                Tpl::showpage('export.excel');
                exit();
            }
        } else {
            $limit1 = ($_GET['curpage']-1) * self::EXPORT_SIZE;
            $limit2 = self::EXPORT_SIZE;
            $limit = $limit1 .','. $limit2;
        }

        $store_list = $model_store->getStoreList($condition, null, 'store_id desc', '*', $limit);
        $this->createCsv($store_list);
    }
    /**
     * 生成csv文件
     */
    private function createCsv($store_list) {
        //店铺等级
        $model_grade = Model('store_grade');
        $grade_list = $model_grade->getGradeList(array());
        $grade_array = array();
        if (!empty($grade_list)){
            foreach ($grade_list as $v){
                $grade_array[$v['sg_id']] = $v['sg_name'];
            }
        }

        //店铺分类
        $model_store_class = Model('store_class');
        $class_list = $model_store_class->getStoreClassList(array(),'',false);
        $class_array = array();
        if (!empty($class_list)) {
            foreach ($class_list as $v) {
                $class_array[$v['sc_id']] = $v['sc_name'];
            }
        }

        $data = array();
        foreach ($store_list as $value) {
            $param = array();
            $param['store_id'] = $value['store_id'];
            $param['store_name'] = $value['store_name'];
            $param['member_name'] = $value['member_name'];
            $param['seller_name'] = $value['seller_name'];
            $param['store_avatar'] = getStoreLogo($value['store_avatar']);
            $param['store_label'] = getStoreLogo($value['store_label'], 'store_logo');
            $param['grade_id'] = $grade_array[$value['grade_id']];
            $param['store_time'] = date('Y-m-d', $value['store_time']);
            $param['store_end_time'] = $value['store_end_time']?date('Y-m-d', $value['store_end_time']):L('no_limit');
            $param['store_state'] = $value['store_state']?L('open'):L('close');
            $param['sc_id'] = $class_array[$value['sc_id']];
            $param['area_info'] = $value['area_info'];
            $param['store_address'] = $value['store_address'];
            $param['store_qq'] = $value['store_qq'];
            $param['store_ww'] = $value['store_ww'];
            $param['store_phone'] = $value['store_phone'];
            $data[$value['store_id']] = $param;
        }

        $header = array(
                'store_id' => '店铺ID',
                'store_name' => '店铺名称',
                'member_name' => '店主账号',
                'seller_name' => '商家账号',
                'store_avatar' => '店铺头像',
                'store_label' => '店铺LOGO',
                'grade_id' => '店铺等级',
                'store_time' => '开店时间',
                'store_end_time' => '到期时间',
                'store_state' => '当前状态',
                'sc_id' => '店铺分类',
                'area_info' => '所在地区',
                'store_address' => '详细地址',
                'store_qq' => 'QQ',
                'store_ww' => '旺旺',
                'store_phone' => '商家电话'
        );
        \shopec\Lib::exporter()->output('store_list' .$_GET['curpage'] . '-'.date('Y-m-d'), $data, $header);
    }

    /**
     * 获得店铺状态
     *  open\正常
     *  close\关闭
     *  expire\即将到期
     *  expired\过期
     */
    private function getStoreState($store_info) {
        $result = 'open';
        if (intval($store_info['store_state']) === 1) {
            $store_end_time = intval($store_info['store_end_time']);
            if ($store_end_time > 0) {
                if ($store_end_time < TIMESTAMP) {
                    $result = 'expired';
                } elseif (($store_end_time - 864000) < TIMESTAMP) {
                    //距离到期10天
                    $result = 'expire';
                }
            }
        } else {
            $result = 'close';
        }
        return $result;
    }

    /**
     * 商户信息登记页面 
     */
    public function store_merchant_basicInfoOp() {
        $store_id = intval($_GET['store_id']);
        $store_array = Model('store')->getStoreInfoByID($store_id);
        $joinin_detail = Model('store_joinin')->getOne(array('member_id'=>$store_array['member_id']));
        Tpl::output('store_array', $store_array);
        Tpl::output('joinin_detail', $joinin_detail);
        Tpl::showpage('store.merchant.info');
    }

    /**
     * 发起商户信息登记 
     */
    public function store_merchant_registerOp() {
        if (chksubmit()) {
            $inc_file = BASE_PATH.DS.'api'.DS.'merchant'.DS.'register.php'; 
            if(is_file($inc_file)) {
                require($inc_file);
                $register = new MerchantRegister($config_api);
		$basicInfoParams = array();
                $basicInfoParams['merchantName'] = $_POST['legal_person_name'];
                $basicInfoParams['shortName'] = $_POST['legal_person_name'];
                $basicInfoParams['city'] = $_POST['area_no'];
                $basicInfoParams['merchantAddress'] = $_POST['company_address_detail'];
                $basicInfoParams['servicePhone'] = $_POST['company_phone'];
                $basicInfoParams['orgCode'] = $_POST['organization_code'];
                $basicInfoParams['merchantType'] = $_POST['merchant_type'];
                $basicInfoParams['category'] = $_POST['gc_no'];
                $basicInfoParams['corpmanName'] = $_POST['legal_person_name'];
                $basicInfoParams['corpmanId'] = $_POST['legal_person_id']; 
                $basicInfoParams['corpmanPhone'] = $_POST['contacts_phone'];
                $basicInfoParams['corpmanMobile'] = $_POST['contacts_phone'];
                $basicInfoParams['corpmanEmail'] = $_POST['contacts_email'];
                $basicInfoParams['bankCode'] = $_POST['bank_no'];
                $basicInfoParams['bankName'] = $_POST['bank_no_name'];
                $basicInfoParams['bankaccountNo'] = $_POST['bank_account_number'];
                $basicInfoParams['bankaccountName'] = $_POST['bank_account_name'];
                $basicInfoParams['autoCus'] = $_POST['autocus'];
                $basicInfoParams['remark'] = $_POST['remark'];
                /*
                  设置结算银行卡信息
                */
                $basicInfoParams['settingSettCard'] = '1';
                $basicInfoParams['bankaccPro'] = '1';
                $basicInfoParams['certCode'] = '1';
                $basicInfoParams['bankaccountType'] = $_POST['bank_account_type'];

                $result = $register->basicInfo($basicInfoParams);
                if ($result['head'][respType] == 'S') {
                    //merchantId入库
                    $merchantId = $result['body']['merchantId']; 
                    $store_array = Model('store')->editStore(array("store_merchantno"=>$merchantId), array("store_id"=>$_POST['store_id']));
                      
                    showMessage(L('nc_common_op_succ'), 'index.php?con=store&fun=store');
                } else {
                    showMessage(L('nc_common_op_fail').":".$result['head']["respMsg"]);
                }
            }
        }
    }

    /**
     * 商户添加银行卡页面 
     */
    public function store_merchant_bankInfoOp() {
        $store_id = intval($_GET['store_id']);
        $store_array = Model('store')->getStoreInfoByID($store_id);
        $joinin_detail = Model('store_joinin')->getOne(array('member_id'=>$store_array['member_id']));
        $bank_list = Model('merchant_bank')->getList(array(), $page='100');

        //查询已添加的银行卡
        $bankaccounList = array();
        $inc_file = BASE_PATH.DS.'api'.DS.'merchant'.DS.'register.php'; 
        if(is_file($inc_file)) {
            require($inc_file);
            $register = new MerchantRegister($config_api);
            $qryCardParams = array();
            $qryCardParams['merchantId'] = $store_array['store_merchantno'];
            $result = $register->qryCard($qryCardParams);
            if ($result['head'][respType] == 'S') {
                $listNum = $result['body']['listNum'];
                $bankaccounList = $result['body']['bankaccounList'];
                if(intval($listNum) == 1){
                    $bankaccounList = array($bankaccounList);
                }
            }
        }
        Tpl::output('bankaccounList', $bankaccounList);
        Tpl::output('store_array', $store_array);
        Tpl::output('joinin_detail', $joinin_detail);
        Tpl::output('bank_list', $bank_list);
        Tpl::showpage('store.merchant.bank');
    }

    /**
     * 商户添加银行卡 
     */
    public function store_merchant_bankOp() {
        if (chksubmit()) {
            $inc_file = BASE_PATH.DS.'api'.DS.'merchant'.DS.'register.php'; 
            if(is_file($inc_file)) {
                require($inc_file);
                $register = new MerchantRegister($config_api);
		$bankInfoParams = array();
                $bankInfoParams['merchantId'] = $_POST['merchant_id'];
                $bankInfoParams['handleType'] = $_POST['handleType'];
                $bankInfoParams['mobileNo'] = $_POST['mobileNo'];
                $bankInfoParams['mobileNo2'] = $_POST['mobileNo'];
                $bankInfoParams['bankCode'] = $_POST['bankCode'];
                $bankInfoParams['bankaccProp'] = $_POST['bankaccProp'];
                $bankInfoParams['name'] = $_POST['name'];
                $bankInfoParams['bankaccountNo'] = $_POST['bankaccountNo'];
                $bankInfoParams['bankaccountType'] = $_POST['bankaccountType'];
                $bankInfoParams['certCode'] = $_POST['certCode'];
                $bankInfoParams['certNo'] = $_POST['certNo'];
                $bankInfoParams['bankbranchNo'] = $_POST['bankbranchNo'];
                $bankInfoParams['defaultAcc'] = $_POST['defaultAcc'];
                $result = $register->bankInfo($bankInfoParams);
                if ($result['head'][respType] == 'S') {
                    showMessage(L('nc_common_op_succ'), 'index.php?con=store&fun=store');
                } else {
                    showMessage(L('nc_common_op_fail').":".$result['head']["respMsg"]);
                }
            }
        }
    }

    /**
     * 开通支付业务页面 
     */
    public function store_merchant_busiInfoOp() {
        $store_id = intval($_GET['store_id']);
        $store_array = Model('store')->getStoreInfoByID($store_id);
        $joinin_detail = Model('store_joinin')->getOne(array('member_id'=>$store_array['member_id']));
        $busi_list = Model('merchant_busi')->getList();

        //查询已开通的业务
        $inc_file = BASE_PATH.DS.'api'.DS.'merchant'.DS.'register.php'; 
        if(is_file($inc_file)) {
            require($inc_file);
            $register = new MerchantRegister($config_api);
            $qryBusiParams = array();
            $qryBusiParams['merchantId'] = $store_array['store_merchantno'];
            $result = $register->qryBusi($qryBusiParams);
            if ($result['head'][respType] == 'S') {
                $listNum = $result['body']['listNum'];
                $busiList= $result['body']['busiList'];
                if(intval($listNum) == 1){
                    $busiList = array($busiList);
                }
            }
        }
        Tpl::output('busiList', $busiList);
        Tpl::output('store_array', $store_array);
        Tpl::output('joinin_detail', $joinin_detail);
        Tpl::output('busi_list', $busi_list);
        Tpl::showpage('store.merchant.pay');
    }

    /**
     * 发起开通支付业务
     */
    public function store_merchant_payOp() {
        if (chksubmit()) {
            $inc_file = BASE_PATH.DS.'api'.DS.'merchant'.DS.'register.php'; 
            if(is_file($inc_file)) {
                require($inc_file);
                $register = new MerchantRegister($config_api);
		$busiInfoParams = array();
                $busiInfoParams['merchantId'] = $_POST['merchant_id'];
                $busiInfoParams['handleType'] = $_POST['handleType'];
                $busiInfoParams['cycleValue'] = $_POST['cycleValue'];
                $busiListStr = $_POST['busiList'];
                $busiListArray = explode("#", $busiListStr);
                $busiList = array();
                foreach($busiListArray as $k => $busiListStr){
                    $busis = explode('@', $busiListStr);
                    $busiType = $busis[0];
                    $ratelist = explode('|', $busis[1]);
                    foreach($ratelist as $i => $rate){
                        $busi = explode('/', $rate);
                        $busiList[$busiType][$i] = array(/*"feeType"=>$busi[0],*/
                                                         "futureRateType"=>$busi[0], 
                                                         "futureRateValue"=>$busi[1]);
                    }
                }
                $busiInfoParams['busiList'] = $busiList;
                $result = $register->busiInfo($busiInfoParams);
                if ($result['head'][respType] == 'S') {
                    showMessage(L('nc_common_op_succ'), 'index.php?con=store&fun=store');
                } else {
                    showMessage(L('nc_common_op_fail').":".$result['head']["respMsg"]);
                }
            }
        }
    }

    /**
     * 店铺编辑
     */
    public function store_editOp(){
        $lang = Language::getLangContent();

        $model_store = Model('store');
        //保存
        if (chksubmit()){
            //取店铺等级的审核
            $model_grade = Model('store_grade');
            $grade_array = $model_grade->getOneGrade(intval($_POST['grade_id']));
            if (empty($grade_array)){
                showMessage($lang['please_input_store_level']);
            }
            //结束时间
            $time   = '';
            if(trim($_POST['end_time']) != ''){
                $time = strtotime($_POST['end_time']);
            }
            $update_array = array();
            //20171211
            $update_array['store_zhenren'] = trim($_POST['store_zhenren']); //真人客服代码
			$update_array['store_quanjing'] = trim($_POST['store_quanjing']); //720全景店铺SWF
			
            $update_array['store_name'] = trim($_POST['store_name']);
            $update_array['sc_id'] = intval($_POST['sc_id']);
            $update_array['grade_id'] = intval($_POST['grade_id']);
            $update_array['store_end_time'] = $time;
            $update_array['store_state'] = intval($_POST['store_state']);
            if ($update_array['store_state'] == 0){
                //根据店铺状态修改该店铺所有商品状态
                $model_goods = Model('goods');
                $model_goods->editProducesOffline(array('store_id' => $_POST['store_id']));
                $update_array['store_close_info'] = trim($_POST['store_close_info']);
                $update_array['store_recommend'] = 0;
            }else {
                //店铺开启后商品不在自动上架，需要手动操作
                $update_array['store_close_info'] = '';
                $update_array['store_recommend'] = intval($_POST['store_recommend']);
            }
            $province_id = intval($_POST['store_province_id']);
            if ($province_id > 0) {
                $update_array['province_id'] = $province_id;
            }
            $update_array['area_info']    = $_POST['store_region'];
            $update_array['store_company_name']    = $_POST['store_company_name'];
            $update_array['store_address']    = $_POST['store_address'];
            $result = $model_store->editStore($update_array, array('store_id' => $_POST['store_id']));
			//511613932
			$where=array();
				$where['sid']=$_POST['store_id'];
				$update=array();
				$update['type']=trim($_POST['zbtype_id']);			
			$zb_configa =Model('zhibo')->zb_config($_GET['store_id']);
			if(is_array($zb_configa)){

				$zbconfig=Model()->table('zb_config')->where($where)->update($update);     
 
			}else{
				$insert['sid']=$_POST['store_id'];
				$insert['type']=trim($_POST['zbtype_id']);
				Model()->table('zb_config')->insert($insert);
			}	 
					   
            if ($result){
                $url = array(
                array(
                'url'=>'index.php?con=store&fun=store',
                'msg'=>$lang['back_store_list'],
                ),
                array(
                'url'=>'index.php?con=store&fun=store_edit&store_id='.intval($_POST['store_id']),
                'msg'=>$lang['countinue_add_store'],
                ),
                );
                $this->log(L('nc_edit,store').'['.$_POST['store_name'].']',1);
                showMessage($lang['nc_common_save_succ'],$url);
            }else {
                $this->log(L('nc_edit,store').'['.$_POST['store_name'].']',1);
                showMessage($lang['nc_common_save_fail']);
            }
        }
        //取店铺信息
        $store_array = $model_store->getStoreInfoByID($_GET['store_id']);
        if (empty($store_array)){
            showMessage($lang['store_no_exist']);
        }
        //511613932
		$zb_config=Model('zhibo')->zb_config($_GET['store_id']);
		Tpl::output('zb_config',$zb_config);        

        //整理店铺内容
        $store_array['store_end_time'] = $store_array['store_end_time']?date('Y-m-d',$store_array['store_end_time']):'';
        //店铺分类
        $model_store_class = Model('store_class');
        $parent_list = $model_store_class->getStoreClassList(array(),'',false);
        //店铺等级
        $model_grade = Model('store_grade');
        $grade_list = $model_grade->getGradeList();
        Tpl::output('grade_list',$grade_list);
        Tpl::output('class_list',$parent_list);
        Tpl::output('store_array',$store_array);

        $joinin_detail = Model('store_joinin')->getOne(array('member_id'=>$store_array['member_id']));
        Tpl::output('joinin_detail', $joinin_detail);
 
        $bank_list = Model('merchant_bank')->getList(array(), $page='100');
        Tpl::output('bank_list', $bank_list);

        $gcno_list = Model('merchant_category')->getList(array(), $page='100');
        Tpl::output('gcno_list', $gcno_list);
        Tpl::showpage('store.edit');
    }

    /**
     * 店铺结算周期编辑
     */
    public function bill_cycyle_editOp(){
        $lang = Language::getLangContent();

        $model_store = Model('store');
        $model_store_ext = Model('store_extend');
        //保存
        if (chksubmit()){
            $result = $model_store_ext->editStoreExtend(array('bill_cycle'=>intval($_POST['bill_cycle'])), array('store_id' => $_POST['store_id']));
            if ($result){
                $this->log('设置店铺结算周期['.$_POST['store_name'].']',1);
                showMessage($lang['nc_common_save_succ'],'index.php?con=store&fun=bill_cycle');
            }else {
                $this->log('设置店铺结算周期['.$_POST['store_name'].']',1);
                showMessage($lang['nc_common_save_fail'],'index.php?con=store&fun=bill_cycle');
            }
        }

        //取店铺信息
        $store_array = $model_store->getStoreInfoByID($_GET['store_id']);
        if (empty($store_array)){
            showMessage($lang['store_no_exist']);
        }
        $store_ext = $model_store_ext->getStoreExtendInfo(array('store_id'=>$_GET['store_id']));
        if ($store_ext['bill_cycle']) {
            $store_array['bill_cycle'] = $store_ext['bill_cycle'];
        }

        Tpl::output('store_array',$store_array);
        Tpl::showpage('store.bill_cycle_edit');
    }

    /**
     * 编辑保存注册信息
     */
    public function edit_save_joininOp() {
        if (chksubmit()) {
            $member_id = $_POST['member_id'];
            if ($member_id <= 0) {
                showMessage(L('param_error'));
            }
            $param = array();
            $param['company_name'] = $_POST['company_name'];
            $province_id = intval($_POST['province_id']);
            if ($province_id > 0) {
                $param['company_province_id'] = $province_id;
            }
            $param['company_address'] = $_POST['company_address'];
            $param['company_address_detail'] = $_POST['company_address_detail'];
            $param['company_phone'] = $_POST['company_phone'];
            $param['company_employee_count'] = intval($_POST['company_employee_count']);
            $param['company_registered_capital'] = intval($_POST['company_registered_capital']);
            $param['contacts_name'] = $_POST['contacts_name'];
            $param['contacts_phone'] = $_POST['contacts_phone'];
            $param['contacts_email'] = $_POST['contacts_email'];
            $param['business_licence_number'] = $_POST['business_licence_number'];
            $param['business_licence_address'] = $_POST['business_licence_address'];
            $param['business_licence_start'] = $_POST['business_licence_start'];
            $param['business_licence_end'] = $_POST['business_licence_end'];
            $param['business_sphere'] = $_POST['business_sphere'];
            if ($_FILES['business_licence_number_elc']['name'] != '') {
                $param['business_licence_number_elc'] = $this->upload_image('business_licence_number_elc');
            }
            $param['organization_code'] = $_POST['organization_code'];
            if ($_FILES['organization_code_electronic']['name'] != '') {
                $param['organization_code_electronic'] = $this->upload_image('organization_code_electronic');
            }
            if ($_FILES['general_taxpayer']['name'] != '') {
                $param['general_taxpayer'] = $this->upload_image('general_taxpayer');
            }
            $param['bank_account_name'] = $_POST['bank_account_name'];
            $param['bank_account_number'] = $_POST['bank_account_number'];
            $param['bank_name'] = $_POST['bank_name'];
            $param['bank_code'] = $_POST['bank_code'];
            $param['bank_address'] = $_POST['bank_address'];
            $param['bank_account_type'] = $_POST['bank_account_type'];
            if ($_FILES['bank_licence_electronic']['name'] != '') {
                $param['bank_licence_electronic'] = $this->upload_image('bank_licence_electronic');
            }
            $param['settlement_bank_account_name'] = $_POST['settlement_bank_account_name'];
            $param['settlement_bank_account_number'] = $_POST['settlement_bank_account_number'];
            $param['settlement_bank_name'] = $_POST['settlement_bank_name'];
            $param['settlement_bank_code'] = $_POST['settlement_bank_code'];
            $param['settlement_bank_address'] = $_POST['settlement_bank_address'];
            $param['settlement_bank_account_type'] = $_POST['settlement_bank_account_type'];
            $param['tax_registration_certificate'] = $_POST['tax_registration_certificate'];
            $param['taxpayer_id'] = $_POST['taxpayer_id'];
            if ($_FILES['tax_registration_certif_elc']['name'] != '') {
                $param['tax_registration_certif_elc'] = $this->upload_image('tax_registration_certif_elc');
            }

            $param['gc_no'] = $_POST['gc_no'];
            $param['bank_no'] = $_POST['bank_no'];
            $param['settlement_bank_no'] = $_POST['settlement_bank_no'];
            $result = Model('store_joinin')->editStoreJoinin(array('member_id' => $member_id), $param);
            if ($result) {
                showMessage(L('nc_common_op_succ'), 'index.php?con=store&fun=store');
            } else {
                showMessage(L('nc_common_op_fail'));
            }
        }
    }

    private function upload_image($file) {
        $pic_name = '';
        $upload = new UploadFile();
        $uploaddir = ATTACH_PATH.DS.'store_joinin'.DS;
        $upload->set('default_dir',$uploaddir);
        $upload->set('allow_type',array('jpg','jpeg','gif','png'));
        if (!empty($_FILES[$file]['name'])){
            $result = $upload->upfile($file);
            if ($result){
                $pic_name = $upload->file_name;
                $upload->file_name = '';
            }
        }
        return $pic_name;
    }

    /**
     * 店铺经营类目管理
     */
    public function store_bind_classOp() {
        $store_id = intval($_GET['store_id']);

        $model_store = Model('store');
        $model_store_bind_class = Model('store_bind_class');
        $model_goods_class = Model('goods_class');

        $gc_list = $model_goods_class->getGoodsClassListByParentId(0);
        Tpl::output('gc_list',$gc_list);

        $store_info = $model_store->getStoreInfoByID($store_id);
        if(empty($store_info)) {
            showMessage(L('param_error'),'','','error');
        }
        Tpl::output('store_info', $store_info);

        $store_bind_class_list = $model_store_bind_class->getStoreBindClassList(array('store_id'=>$store_id,'state'=>array('in',array(1,2))), null);
        $goods_class = Model('goods_class')->getGoodsClassIndexedListAll();
        for($i = 0, $j = count($store_bind_class_list); $i < $j; $i++) {
            $store_bind_class_list[$i]['class_1_name'] = $goods_class[$store_bind_class_list[$i]['class_1']]['gc_name'];
            $store_bind_class_list[$i]['class_2_name'] = $goods_class[$store_bind_class_list[$i]['class_2']]['gc_name'];
            $store_bind_class_list[$i]['class_3_name'] = $goods_class[$store_bind_class_list[$i]['class_3']]['gc_name'];
        }
        Tpl::output('store_bind_class_list', $store_bind_class_list);

        Tpl::showpage('store.bind_class');
    }

    /**
     * 添加经营类目
     */
    public function store_bind_class_addOp() {
        $store_id = intval($_POST['store_id']);
//        $commis_rate = intval($_POST['commis_rate']);

        /***author liming 2016.12.01 start ***/

        $commis_rate = $_POST['commis_rate'];

        /***author liming 2016.12.01 end ***/

        if($commis_rate < 0 || $commis_rate > 100) {
            showMessage(L('param_error'), '');
        }
        list($class_1, $class_2, $class_3) = explode(',', $_POST['goods_class']);

        $model_store_bind_class = Model('store_bind_class');

        $param = array();
        $param['store_id'] = $store_id;
        $param['class_1'] = $class_1;
        $param['state'] = 1;
        if(!empty($class_2)) {
            $param['class_2'] = $class_2;
        }
        if(!empty($class_3)) {
            $param['class_3'] = $class_3;
        }

        // 检查类目是否已经存在
        $store_bind_class_info = $model_store_bind_class->getStoreBindClassInfo($param);
        if(!empty($store_bind_class_info)) {
            showMessage('该类目已经存在','','','error');
        }

        $param['commis_rate'] = $commis_rate;
        $result = $model_store_bind_class->addStoreBindClass($param);

        if($result) {
            $this->log('添加店铺经营类目，类目编号:'.$result.',店铺编号:'.$store_id);
            showMessage(L('nc_common_save_succ'), '');
        } else {
            showMessage(L('nc_common_save_fail'), '');
        }
    }

    /**
     * 删除经营类目
     */
    public function store_bind_class_delOp() {
        $bid = intval($_POST['bid']);

        $data = array();
        $data['result'] = true;

        $model_store_bind_class = Model('store_bind_class');
        $model_goods = Model('goods');

        $store_bind_class_info = $model_store_bind_class->getStoreBindClassInfo(array('bid' => $bid));
        if(empty($store_bind_class_info)) {
            $data['result'] = false;
            $data['message'] = '经营类目删除失败';
            echo json_encode($data);die;
        }

        // 商品下架
        $condition = array();
        $condition['store_id'] = $store_bind_class_info['store_id'];
        $gc_id = $store_bind_class_info['class_1'].','.$store_bind_class_info['class_2'].','.$store_bind_class_info['class_3'];
        $update = array();
        $update['goods_stateremark'] = '管理员删除经营类目';
        $condition['gc_id'] = array('in', rtrim($gc_id, ','));
        $model_goods->editProducesLockUp($update, $condition);

        $result = $model_store_bind_class->delStoreBindClass(array('bid'=>$bid));

        if(!$result) {
            $data['result'] = false;
            $data['message'] = '经营类目删除失败';
        }
        $this->log('删除店铺经营类目，类目编号:'.$bid.',店铺编号:'.$store_bind_class_info['store_id']);
        echo json_encode($data);die;
    }

    public function store_bind_class_updateOp() {
        $bid = intval($_GET['id']);
        if($bid <= 0) {
            echo json_encode(array('result'=>FALSE,'message'=>Language::get('param_error')));
            die;
        }
//        $new_commis_rate = intval($_GET['value']);
        /*----------start liming 取消分佣比例取证---------*/
        $new_commis_rate = $_GET['value'];
        /*----------end liming 取消分佣比例取证---------*/

        if ($new_commis_rate < 0 || $new_commis_rate >= 100) {
            echo json_encode(array('result'=>FALSE,'message'=>Language::get('param_error')));
            die;
        } else {
            $update = array('commis_rate' => $new_commis_rate);
            $condition = array('bid' => $bid);
            $model_store_bind_class = Model('store_bind_class');
            $result = $model_store_bind_class->editStoreBindClass($update, $condition);
            if($result) {
                $this->log('更新店铺经营类目，类目编号:'.$bid);
                echo json_encode(array('result'=>TRUE));
                die;
            } else {
                echo json_encode(array('result'=>FALSE,'message'=>L('nc_common_op_fail')));
                die;
            }
        }
    }


    /**
     * 店铺 待审核列表
     */
    public function store_joininOp(){

        //输出子菜单
        Tpl::output('top_link',$this->sublink($this->_links,'store_joinin'));

        Tpl::showpage('store_joinin');
    }

    /**
     * 输出XML数据
     */
    public function get_joinin_xmlOp() {
        $model_store_joinin = Model('store_joinin');
        // 设置页码参数名称
        $condition = array();
        $condition['joinin_state'] = array('gt',0);
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        $order = '';
        $param = array('member_id', 'member_name', 'sg_id', 'paying_amount', 'joinin_state', 'joinin_year', 'contacts_name', 'contacts_phone'
                ,'contacts_email', 'company_name', 'company_province_id', 'company_phone', 'company_employee_count', 'company_registered_capital'
        );
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }

        $page = $_POST['rp'];

        //店铺列表
        $store_list = $model_store_joinin->getList($condition, $page, $order);

        // 开店状态
        $joinin_state_array = $this->get_store_joinin_state();

        $data = array();
        $data['now_page'] = $model_store_joinin->shownowpage();
        $data['total_num'] = $model_store_joinin->gettotalnum();
        foreach ($store_list as $value) {
            $param = array();
            if(in_array(intval($value['joinin_state']), array(STORE_JOIN_STATE_NEW, STORE_JOIN_STATE_PAY))) {
                $operation = "<a class='btn orange' href=\"index.php?con=store&fun=store_joinin_detail&member_id=". $value['member_id'] ."\"><i class=\"fa fa-check-circle\"></i>审核</a>";
            } else {
                $operation = "<a class='btn green' href=\"index.php?con=store&fun=store_joinin_detail&member_id=". $value['member_id'] ."\"><i class=\"fa fa-list-alt\"></i>查看</a>";
            }
            $param['operation'] = $operation;
            $param['member_id'] = $value['member_id'];
            $param['member_name'] = $value['member_name'];
            $param['sg_id'] = $value['sg_name'];
            $param['paying_amount'] = ncPriceFormat($value['paying_amount']);
            $param['joinin_state'] = $joinin_state_array[$value['joinin_state']];
            $param['joinin_year'] = $value['joinin_year'];
            $param['contacts_name'] = $value['contacts_name'];
            $param['contacts_phone'] = $value['contacts_phone'];
            $param['contacts_email'] = $value['contacts_email'];
            $param['company_name'] = $value['company_name'];
            $param['company_province_id'] = $value['company_address'] . ' ' . $value['company_address_detail'];
            $param['company_phone'] = $value['company_phone'];
            $param['company_employee_count'] = $value['company_employee_count'];
            $param['company_registered_capital'] = $value['company_registered_capital'];
            $data['list'][$value['member_id']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }

    /**
     * 经营类目申请列表
     */
    public function store_bind_class_applay_listOp(){
        Tpl::output('top_link',$this->sublink($this->_links,'store_bind_class_applay_list'));
        Tpl::showpage('store.bind_class_applay_list');
    }

    /**
     * 输出XML数据
     */
    public function get_bind_class_applay_xmlOp() {
        $model_store_bind_class = Model('store_bind_class');
        // 设置页码参数名称
        $condition = array();

        $condition['state'] = array('in', array('0', '1'));
        if ($_GET['state'] != '') {
            $condition['state'] = $_GET['state'];
        }
        if ($_GET['store_id'] != '') {
            $condition['store_id'] = array('like', '%' . $_GET['store_id'] . '%');
        }

        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        $order = '';
        $param = array('bid', 'store_id', 'commis_rate', 'class_1', 'class_2', 'class_3', 'state');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }

        if ($order == "") {
            $order = "bid desc";
        } else {
            $order .= ",bid desc";
        }


        $page = $_POST['rp'];

        //店铺列表
        $store_bind_class_list = $model_store_bind_class->getStoreBindClassList($condition, $page, $order);
        $storeid_array = array();
        foreach ($store_bind_class_list as $value) {
            $storeid_array[] = $value['store_id'];
        }
        $store_list = Model('store')->getStoreList(array('store_id'=>array('in', $storeid_array)));
        $store_array = array();
        foreach ($store_list as $value) {
            $store_array[$value['store_id']]['store_name'] = $value['store_name'];
            $store_array[$value['store_id']]['seller_name'] = $value['seller_name'];
        }

        //商品分类
        $goods_class = Model('goods_class')->getGoodsClassIndexedListAll();

        // 申请类目状态
        $apply_state = $this->getClassApplyState();

        $data = array();
        $data['now_page'] = $model_store_bind_class->shownowpage();
        $data['total_num'] = $model_store_bind_class->gettotalnum();
        foreach ($store_bind_class_list as $value) {
            $param = array();
            if($value['state'] == '0') {
                
                $operation = "<a class='btn orange' href=\"javascript:if(confirm('确认审核吗？'))window.location = 'index.php?con=store&fun=store_bind_class_applay_check&bid=".$value['bid']."&store_id=".$value['store_id']."'\"><i class=\"fa fa-check-circle\"></i>审核</a>";
            } else {
                $operation = "<a class='btn red' href=\"javascript:if(confirm('".($value['state'] == '1' ? '该类目已经审核通过，删除它可能影响到商家的使用，' : null)."确认删除吗？'))window.location = 'index.php?con=store&fun=store_bind_class_applay_del&bid=".$value['bid']."&store_id=".$value['store_id']."'\"><i class=\"fa fa-trash-o\"></i>删除</a>";
            }
            $param['operation'] = $operation;
            $param['store_id'] = $value['store_id'];
            $param['store_name'] = $store_array[$value['store_id']]['store_name'];
            $param['seller_name'] = $store_array[$value['store_id']]['seller_name'];
            $param['commis_rate'] = $value['commis_rate'] . '%';
            $param['state'] = $apply_state[$value['state']];
            $param['class'] = $goods_class[$value['class_1']]['gc_name'] . '(ID:' . $value['class_1'] . ')';
            if ($value['class_2'] > 0) {
                $param['class'] .= '   > ' . $goods_class[$value['class_2']]['gc_name']. '(ID:' . $value['class_2'] . ')';
            }
            if ($value['class_3'] > 0) {
                $param['class'] .= '   > ' . $goods_class[$value['class_3']]['gc_name']. '(ID:' . $value['class_3'] . ')';
            }
            $data['list'][$value['bid']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }

    private function getClassApplyState() {
        return array('0' => '审核中', '1' => '已审核', '2' => '自营店','3'=>'审核不通过');
    }


    /**
     * 审核经营类目申请
     */
    public function store_bind_class_applay_checkOp() {
        $model_store_bind_class = Model('store_bind_class');
        if($_SERVER['REQUEST_METHOD'] == 'GET'){

            $condition['bid'] = intval($_GET['bid']);
            $condition['store_id'] = intval($_GET['store_id']);
            $storeInfo = $model_store_bind_class->getStoreList( $condition);
            //商品分类
            $goods_class = Model('goods_class')->getGoodsClassIndexedListAll();
            $storeInfo[0]['class'] = $goods_class[$storeInfo[0]['class_1']]['gc_name'] . '(ID:' . $storeInfo[0]['class_1'] . ')';
            if ($storeInfo[0]['class_2'] > 0) {
                $storeInfo[0]['class'] .= '   > ' . $goods_class[$storeInfo[0]['class_2']]['gc_name']. '(ID:' . $storeInfo[0]['class_2'] . ')';
            }
            if ($storeInfo[0]['class_3'] > 0) {
                $storeInfo[0]['class'] .= '   > ' . $goods_class[$storeInfo[0]['class_3']]['gc_name']. '(ID:' . $storeInfo[0]['class_3'] . ')';
            }
            //展示审核经营类目的页面
            Tpl::output('storeInfo',$storeInfo);
            Tpl::showpage('store_class.detail');
        }else{
            $condition = array();
            $condition['bid'] = intval($_POST['bid']);
            $condition['state'] = 0;
            $verify =$_POST['verify'];
            $checkinfo = $_POST['checkinfo'];
            if(empty($checkinfo)){
                $checkinfo = null;
            }
            if($verify == 1){
                $update = $model_store_bind_class->editStoreBindClass(array('state'=>1,'checkinfo'=>$checkinfo),$condition);
            }elseif($verify == 3){
                $update = $model_store_bind_class->editStoreBindClass(array('state'=>3,'checkinfo'=>$checkinfo),$condition);
            }

            if ($update) {
                $this->log('审核新经营类目申请，店铺ID：'.$_POST['store_id'],1);
                showMessage('提交成功',"index.php?con=store&fun=store_bind_class_applay_list",getReferer());
            } else {
                showMessage('提交失败',getReferer(),'html','error');
            }


        }
        /*   $condition = array();
           $condition['bid'] = intval($_GET['bid']);
           $condition['state'] = 0;
           $update = $model_store_bind_class->editStoreBindClass(array('state'=>1),$condition);
           if ($update) {
               $this->log('审核新经营类目申请，店铺ID：'.$_GET['store_id'],1);
               showMessage('审核成功',getReferer());
           } else {
               showMessage('审核失败',getReferer(),'html','error');
           }*/
    }

    /**
     * 删除经营类目申请
     */
    public function store_bind_class_applay_delOp() {
        $model_store_bind_class = Model('store_bind_class');
        $condition = array();
        $condition['bid'] = intval($_GET['bid']);
        $del = $model_store_bind_class->delStoreBindClass($condition);
        if ($del) {
            $this->log('删除经营类目，店铺ID：'.$_GET['store_id'],1);
            showMessage('删除成功',getReferer());
        } else {
            showMessage('删除失败',getReferer(),'html','error');
        }
    }

    private function get_store_joinin_state() {
        $joinin_state_array = array(
            STORE_JOIN_STATE_NEW => '新申请',
            STORE_JOIN_STATE_PAY => '已付款',
            STORE_JOIN_STATE_VERIFY_SUCCESS => '待付款',
            STORE_JOIN_STATE_VERIFY_FAIL => '审核失败',
            STORE_JOIN_STATE_PAY_FAIL => '付款审核失败',
            STORE_JOIN_STATE_FINAL => '开店成功',
        );
        return $joinin_state_array;
    }

    /**
     * 店铺续签申请列表
     */
    public function reopen_listOp(){
        Tpl::output('top_link',$this->sublink($this->_links,'reopen_list'));
        Tpl::showpage('store_reopen.list');
    }

    /**
     * 输出XML数据
     */
    public function get_reopen_xmlOp() {
        $model_store_reopen = Model('store_reopen');
        // 设置页码参数名称
        $condition = array();
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        $order = '';
        $param = array('re_id', 're_grade_id', 're_grade_price', 're_year', 're_pay_amount', 're_store_id', 're_store_name', 're_state'
                , 're_create_time', 're_start_time', 're_end_time', 're_pay_cert', 're_pay_cert_explain');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }

        $page = $_POST['rp'];

        //店铺列表
        $reopen_list = $model_store_reopen->getStoreReopenList($condition, $page, $order);

        // 续签状态
        $reopen_state_array = $this->getReopenState();

        $data = array();
        $data['now_page'] = $model_store_reopen->shownowpage();
        $data['total_num'] = $model_store_reopen->gettotalnum();
        foreach ($reopen_list as $value) {
            $param = array();
            $operation = '';
            if($value['re_state'] == 1) {
                $operation .= "<a class='btn orange' href=\"javascript:void(0);\" onclick=\"reopen_check('" . $value['re_id'] . "')\"><i class=\"fa fa-check-circle-o\"></i>审核</a>";
            }
            if ($value['re_state'] != 2) {
                $operation .= "<a class='btn green' href=\"javascript:void(0);\" onclick=\"reopen_del('" . $value['re_id'] . "', '" . $value['re_store_id'] . "')\"><i class=\"fa fa-list-alt\"></i>删除</a>";
            }
            if ($value['re_state'] == 2) {
                $operation .= "<span>--</span>";
            }
            $param['operation'] = $operation;
            $param['re_id'] = $value['re_id'];
            $param['re_grade_id'] = $value['re_grade_name'];
            $param['re_grade_price'] = ncPriceFormat($value['re_grade_price']);
            $param['re_year'] = $value['re_year'];
            $param['re_pay_amount'] = ncPriceFormat($value['re_pay_amount']);
            $param['re_store_id'] = $value['re_store_id'];
            $param['re_store_name'] = $value['re_store_name'];
            $param['re_state'] = $reopen_state_array[$value['re_state']];
            $param['re_create_time'] = date('Y-m-d', $value['re_create_time']);
            $param['re_pay_cert'] = "<a href='".getStoreJoininImageUrl($value['re_pay_cert'])."' target=\"blank\" class='pic-thumb-tip' onMouseOut='toolTip()' onMouseOver='toolTip(\"<img src=".getStoreJoininImageUrl($value['re_pay_cert']).">\")'><i class='fa fa-picture-o'></i></a>";
            $param['re_pay_cert_explain'] = $value['re_pay_cert_explain'];
            $param['re_start_time'] = $value['re_start_time'] != '' ? date('Y-m-d', $value['re_start_time']) : '';
            $param['re_end_time'] = $value['re_end_time'] != '' ? date('Y-m-d', $value['re_end_time']) : '';
            $data['list'][$value['re_id']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }

    private function getReopenState() {
        return array('0' => '待付款', '1' => '待审核', '2' => '通过审核');
    }

    /**
     * 审核店铺续签申请
     */
    public function reopen_checkOp() {
        $id = intval($_GET['id']);
        if ($id > 0) {
            $model_store_reopen = Model('store_reopen');
            $condition = array();
            $condition['re_id'] = $id;
            $condition['re_state'] = 1;
            //取当前申请信息
            $reopen_info = $model_store_reopen->getStoreReopenInfo($condition);

            //取目前店铺有效截止日期
            $store_info = Model('store')->getStoreInfoByID($reopen_info['re_store_id']);
            $data = array();
            $data['re_start_time'] = strtotime(date('Y-m-d 0:0:0',$store_info['store_end_time']))+24*3600;
            $data['re_end_time'] = strtotime(date('Y-m-d 23:59:59', $data['re_start_time'])." +".intval($reopen_info['re_year'])." year");
            $data['re_state'] = 2;
            $update = $model_store_reopen->editStoreReopen($data,$condition);
            if ($update) {
                //更新店铺有效期
                Model('store')->editStore(array('store_end_time'=>$data['re_end_time']),array('store_id'=>$reopen_info['re_store_id']));
                $msg = '审核通过店铺续签申请，店铺ID：'.$reopen_info['re_store_id'].'，续签时间段：'.date('Y-m-d',$data['re_start_time']).' - '.date('Y-m-d',$data['re_end_time']);
                $this->log($msg,1);
                exit(json_encode(array('state'=>true,'msg'=>'审核成功')));
            } else {
                exit(json_encode(array('state'=>false,'msg'=>'审核失败')));
            }
        } else {
            exit(json_encode(array('state'=>false,'msg'=>'审核失败')));
        }
    }

    /**
     * 删除店铺续签申请
     */
    public function reopen_delOp() {
        $id = intval($_GET['id']);
        if ($id > 0) {
            $model_store_reopen = Model('store_reopen');
            $condition = array();
            $condition['re_id'] = $id;
            $condition['re_state'] = array('in',array(0,1));

            //取当前申请信息
            $reopen_info = $model_store_reopen->getStoreReopenInfo($condition);
            $cert_file = BASE_UPLOAD_PATH.DS.ATTACH_STORE_JOININ.DS.$reopen_info['re_pay_cert'];
            $del = $model_store_reopen->delStoreReopen($condition);
            if ($del) {
                if (is_file($cert_file)) {
                    @unlink($cert_file);
                }
                $this->log('删除店铺续签目申请，店铺ID：'.$_GET['store_id'],1);
                exit(json_encode(array('state'=>true,'msg'=>'审核成功')));
            } else {
                exit(json_encode(array('state'=>false,'msg'=>'审核失败')));
            }
        } else {
            exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
        }
    }

    /**
     * 审核详细页
     */
    public function store_joinin_detailOp(){
        $model_store_joinin = Model('store_joinin');
        $joinin_detail = $model_store_joinin->getOne(array('member_id'=>$_GET['member_id']));
        $joinin_detail_title = '查看';
        if(in_array(intval($joinin_detail['joinin_state']), array(STORE_JOIN_STATE_NEW, STORE_JOIN_STATE_PAY))) {
            $joinin_detail_title = '审核';
        }
        if (!empty($joinin_detail['sg_info'])) {
            $store_grade_info = Model('store_grade')->getOneGrade($joinin_detail['sg_id']);
            $joinin_detail['sg_price'] = $store_grade_info['sg_price'];
        } else {
            $joinin_detail['sg_info'] = @unserialize($joinin_detail['sg_info']);
            if (is_array($joinin_detail['sg_info'])) {
                $joinin_detail['sg_price'] = $joinin_detail['sg_info']['sg_price'];
            }
        }
        $joinin_detail['quality_report'] = explode('|',$joinin_detail['quality_report']);
        $joinin_detail['trade_mark_license'] = explode('|',$joinin_detail['trade_mark_license']);
        $joinin_detail['authorization'] = explode('|',$joinin_detail['authorization']);
        $joinin_detail['other_qualifications'] = explode('|',$joinin_detail['other_qualifications']);
        $joinin_detail['identity_card_electronic'] = explode('|',$joinin_detail['identity_card_electronic']);
        Tpl::output('joinin_detail_title', $joinin_detail_title);
        Tpl::output('joinin_detail', $joinin_detail);
        Tpl::showpage('store_joinin.detail');
    }

    /**
     * 审核
     */
    public function store_joinin_verifyOp() {
        $model_store_joinin = Model('store_joinin');
        $joinin_detail = $model_store_joinin->getOne(array('member_id'=>$_POST['member_id']));
        switch (intval($joinin_detail['joinin_state'])) {
            case STORE_JOIN_STATE_NEW:
                $this->store_joinin_verify_pass($joinin_detail);
                break;
            case STORE_JOIN_STATE_PAY:
                $this->store_joinin_verify_open($joinin_detail,$joinin_detail['paying_amount']);
                break;
            default:
                showMessage('参数错误','');
                break;
        }
    }

    private function store_joinin_verify_pass($joinin_detail) {
        $param = array();
        $param['joinin_state'] = $_POST['verify_type'] === 'pass' ? STORE_JOIN_STATE_VERIFY_SUCCESS : STORE_JOIN_STATE_VERIFY_FAIL;
        $param['joinin_message'] = $_POST['joinin_message'];
        $param['paying_amount'] = abs(floatval($_POST['paying_amount']));
        $param['store_class_commis_rates'] = implode(',', $_POST['commis_rate']);
        $model_store_joinin = Model('store_joinin');
        $model_store_joinin->modify($param, array('member_id'=>$_POST['member_id']));
        if ($param['paying_amount'] > 0) {
            showMessage('店铺入驻申请审核完成','index.php?con=store&fun=store_joinin');
        } else {
            //如果开店支付费用为零，则审核通过后直接开通，无需再上传付款凭证
            $this->store_joinin_verify_open($joinin_detail,$param['paying_amount']);
        }
    }

    private function store_joinin_verify_open($joinin_detail,$paying_amount = 0) {
        $model_store_joinin = Model('store_joinin');
        $model_store    = Model('store');
        $model_seller = Model('seller');

        //验证商家用户名是否已经存在
        if($model_seller->isSellerExist(array('seller_name' => $joinin_detail['seller_name']))) {
            showMessage('商家用户名已存在','');
        }

        $param = array();
        $param['joinin_state'] = $_POST['verify_type'] === 'pass' ? STORE_JOIN_STATE_FINAL : ($paying_amount > 0 ? STORE_JOIN_STATE_PAY_FAIL : STORE_JOIN_STATE_VERIFY_FAIL);
        $param['joinin_message'] = $_POST['joinin_message'];
        $model_store_joinin->modify($param, array('member_id'=>$_POST['member_id']));
        if($_POST['verify_type'] === 'pass') {
            //开店
            $shop_array     = array();
            $shop_array['member_id']    = $joinin_detail['member_id'];
            $shop_array['member_name']  = $joinin_detail['member_name'];
            $shop_array['seller_name'] = $joinin_detail['seller_name'];
            $shop_array['grade_id']     = $joinin_detail['sg_id'];
            $shop_array['store_name']   = $joinin_detail['store_name'];
            $shop_array['sc_id']        = $joinin_detail['sc_id'];
            $shop_array['store_company_name'] = $joinin_detail['company_name'];
            $shop_array['province_id']  = $joinin_detail['company_province_id'];
            $shop_array['area_info']    = $joinin_detail['company_address'];
            $shop_array['store_address']= $joinin_detail['company_address_detail'];
            $shop_array['store_zip']    = '';
            $shop_array['store_zy']     = '';
            $shop_array['store_state']  = 1;
            $shop_array['store_time']   = time();
            $shop_array['store_end_time'] = strtotime(date('Y-m-d 23:59:59', strtotime('+1 day'))." +".intval($joinin_detail['joinin_year'])." year");

            /********************************** 加入市和区域id 龚波添加功能 20161121 start**********************************************/
            /*
             * @author   gongbo
             * @desc     数据库加上市和区域的id
             * @date     2016.11.21
             */
            $area_arr = @explode(' ',$joinin_detail['company_address']);
            if(!empty($area_arr[2])){     //查询市级id
                $my_condition = array('area_deep'=>2,'area_name'=>$area_arr[1],'area_parent_id'=>$joinin_detail['company_province_id']);
                $my_city_id = $this->_get_area_id($my_condition);
                if($my_city_id){
                    $shop_array['city_id'] = $my_city_id;
                    if(!empty($area_arr[2])){   //查询区域id
                        $my_condition = array('area_deep'=>3,'area_name'=>$area_arr[2],'area_parent_id'=>$my_city_id);
                        $my_discrit_id = $this->_get_area_id($my_condition);
                        if($my_discrit_id){
                            $shop_array['district_id'] = $my_discrit_id;
                        }
                    }
                }
            }
            /********************************** 加入市和区域id 龚波添加功能 20161121 end**********************************************/


            $store_id = $model_store->addStore($shop_array);

            if($store_id) {
                //写入商家账号
                $seller_array = array();
                $seller_array['seller_name'] = $joinin_detail['seller_name'];
                $seller_array['member_id'] = $joinin_detail['member_id'];
                $seller_array['seller_group_id'] = 0;
                $seller_array['store_id'] = $store_id;
                $seller_array['is_admin'] = 1;
                $state = $model_seller->addSeller($seller_array);
            }

            if($state) {
                // 添加相册默认
                $album_model = Model('album');
                $album_arr = array();
                $album_arr['aclass_name'] = Language::get('store_save_defaultalbumclass_name');
                $album_arr['store_id'] = $store_id;
                $album_arr['aclass_des'] = '';
                $album_arr['aclass_sort'] = '255';
                $album_arr['aclass_cover'] = '';
                $album_arr['upload_time'] = time();
                $album_arr['is_default'] = '1';
                $album_model->addClass($album_arr);

                $model = Model();
                //插入店铺扩展表
                $model->table('store_extend')->insert(array('store_id'=>$store_id));
                $msg = Language::get('store_save_create_success');

                //插入店铺绑定分类表
                $store_bind_class_array = array();
                $store_bind_class = unserialize($joinin_detail['store_class_ids']);
                $store_bind_commis_rates = explode(',', $joinin_detail['store_class_commis_rates']);
                for($i=0, $length=count($store_bind_class); $i<$length; $i++) {
                    list($class1, $class2, $class3) = explode(',', $store_bind_class[$i]);
                    $store_bind_class_array[] = array(
                        'store_id' => $store_id,
                        'commis_rate' => $store_bind_commis_rates[$i],
                        'class_1' => $class1,
                        'class_2' => $class2,
                        'class_3' => $class3,
                        'state' => 1
                    );
                }
                $model_store_bind_class = Model('store_bind_class');
                $model_store_bind_class->addStoreBindClassAll($store_bind_class_array);
                showMessage('店铺开店成功','index.php?con=store&fun=store_joinin');
            } else {
                showMessage('店铺开店失败','index.php?con=store&fun=store_joinin');
            }
        } else {
            showMessage('店铺开店拒绝','index.php?con=store&fun=store_joinin');
        }
    }

    /**
     * 提醒续费
     */
    public function remind_renewalOp() {
        $store_id = intval($_GET['store_id']);
        $store_info = Model('store')->getStoreInfoByID($store_id);
        if (!empty($store_info) && $store_info['store_end_time'] < (TIMESTAMP + 864000) && cookie('remindRenewal'.$store_id) == null) {
            // 发送商家消息
            $param = array();
            $param['code'] = 'store_expire';
            $param['store_id'] = intval($_GET['store_id']);
            $param['param'] = array();
            QueueClient::push('sendStoreMsg', $param);

            setNcCookie('remindRenewal'.$store_id, 1, 86400 * 10);  // 十天
            showMessage('消息发送成功');
        }
            showMessage('消息发送失败');
    }

    /**
     * 验证店铺名称是否存在
     */
    public function ckeck_store_nameOp() {
        /**
         * 实例化商家模型
         */
        $where = array();
        $where['store_name'] = $_GET['store_name'];
        $where['store_id'] = array('neq', $_GET['store_id']);
        $store_info = Model('store')->getStoreInfo($where);
        if(!empty($store_info['store_name'])) {
            echo 'false';
        } else {
            echo 'true';
        }
    }

    /********************************** 查询区域id 龚波添加功能 20161121 start**********************************************/
    /*
     * @deep 层级，1省，2市，3区域
     */
    private function _get_area_id($condition){
        $rs = Model('area')->where($condition)->field('area_id')->find();
        if($rs){
            return $rs['area_id'];
        }else{
            return false;
        }

    }


    /********************************** 查询区域id 龚波添加功能 20161121 end**********************************************/
}
