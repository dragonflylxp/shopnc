<?php
/**
 * 商家中心-拼团
 *
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
class store_promotion_pintuanControl extends BaseSellerControl {

    const LINK_LIST = 'index.php?con=store_promotion_pintuan&fun=index';
    const LINK_MANAGE = 'index.php?con=store_promotion_pintuan&fun=pintuan_manage&pintuan_id=';

    public function __construct() {
        parent::__construct();
        $model_pintuan = Model('p_pintuan');
        $model_pintuan->getStateArray();
    }

    public function indexOp() {
        $model_pintuan = Model('p_pintuan');

        if (checkPlatformStore()) {
            Tpl::output('isOwnShop', true);
        } else {
            $current_quota = $model_pintuan->getQuotaCurrent($_SESSION['store_id']);
            Tpl::output('current_quota', $current_quota);
        }

        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        if(!empty($_GET['name'])) {
            $condition['pintuan_name'] = array('like', '%'.$_GET['name'].'%');
        }
        $pintuan_list = $model_pintuan->getList($condition, 10);
        Tpl::output('list', $pintuan_list);
        Tpl::output('show_page', $model_pintuan->showpage());

        self::profile_menu('pintuan_list');
        Tpl::showpage('store_promotion_pintuan.list');
    }

    /**
     * 添加活动
     **/
    public function pintuan_addOp() {
        if (checkPlatformStore()) {
            Tpl::output('isOwnShop', true);
        } else {
            $model_pintuan = Model('p_pintuan');
            $current_pintuan = $model_pintuan->getQuotaCurrent($_SESSION['store_id']);
            if(empty($current_pintuan)) {
                showMessage(Language::get('pintuan_current_error1'),'','','error');
            }
            Tpl::output('current_pintuan_quota',$current_pintuan);
        }

        //输出导航
        self::profile_menu('pintuan_add');
        Tpl::showpage('store_promotion_pintuan.add');

    }

    /**
     * 保存添加的活动
     **/
    public function pintuan_saveOp() {
        $model_pintuan = Model('p_pintuan');
        //验证输入
        $pintuan_name = trim($_POST['pintuan_name']);
        $start_time = strtotime($_POST['start_time']);
        $end_time = strtotime($_POST['end_time']);
        $min_num = intval($_POST['min_num']);
        if($min_num <= 1) {
            $min_num = 2;
        }
        if (!checkPlatformStore()) {
            $current_pintuan = $model_pintuan->getQuotaCurrent($_SESSION['store_id']);
            if(empty($current_pintuan)) {
                showDialog('没有可用套餐,请先购买套餐');
            }
            $quota_start_time = intval($current_pintuan['start_time']);
            $quota_end_time = intval($current_pintuan['end_time']);
            if($start_time < $quota_start_time) {
                showDialog(sprintf('开始时间不能为空且不能早于%s',date('Y-m-d',$current_pintuan['start_time'])));
            }
            if($end_time > $quota_end_time) {
                showDialog(sprintf('结束时间不能为空且不能晚于%s',date('Y-m-d',$current_pintuan['end_time'])));
            }
        }
        $param = array();
        $param['pintuan_name'] = $pintuan_name;
        $param['pintuan_title'] = $_POST['pintuan_title'];
        $param['pintuan_explain'] = $_POST['pintuan_explain'];
        $param['quota_id'] = $current_pintuan['quota_id'] ? $current_pintuan['quota_id'] : 0;
        $param['start_time'] = $start_time;
        $param['end_time'] = $end_time;
        $param['store_id'] = $_SESSION['store_id'];
        $param['store_name'] = $_SESSION['store_name'];
        $param['member_id'] = $_SESSION['member_id'];
        $param['member_name'] = $_SESSION['member_name'];
        $param['min_num'] = $min_num;
        $result = $model_pintuan->add($param);
        if($result) {
            $this->recordSellerLog('添加拼团活动，活动名称：'.$pintuan_name.'，活动编号：'.$result);
            showDialog(Language::get('nc_common_save_succ'),self::LINK_MANAGE.$result,'succ','',3);
        }else {
            showDialog(Language::get('nc_common_save_fail'));
        }
    }

    /**
     * 编辑活动
     **/
    public function pintuan_editOp() {
        $model_pintuan = Model('p_pintuan');
        $pintuan_id = intval($_GET['pintuan_id']);
        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        $condition['pintuan_id'] = $pintuan_id;
        $pintuan_info = $model_pintuan->getInfo($condition);
        if(empty($pintuan_info)) {
            showMessage('参数错误','','','error');
        }

        Tpl::output('pintuan_info', $pintuan_info);

        //输出导航
        self::profile_menu('pintuan_edit');
        Tpl::showpage('store_promotion_pintuan.add');
    }

    /**
     * 编辑保存活动
     **/
    public function pintuan_edit_saveOp() {
        $model_pintuan = Model('p_pintuan');
        $pintuan_id = intval($_POST['pintuan_id']);
        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        $condition['pintuan_id'] = $pintuan_id;
        $pintuan_info = $model_pintuan->getInfo($condition);
        if(empty($pintuan_info)) {
            showDialog(Language::get('param_error'));
        }

        //验证输入
        $pintuan_name = trim($_POST['pintuan_name']);
        $min_num = intval($_POST['min_num']);
        if($min_num <= 1) {
            $min_num = 2;
        }

        //生成活动
        $param = array();
        $param['pintuan_name'] = $pintuan_name;
        $param['pintuan_title'] = $_POST['pintuan_title'];
        $param['pintuan_explain'] = $_POST['pintuan_explain'];
        $param['min_num'] = $min_num;
        $result = $model_pintuan->edit($param, array('pintuan_id'=>$pintuan_id));
        if($result) {
            $model_pintuan->editGoods($param, array('pintuan_id'=>$pintuan_id));
            $this->recordSellerLog('编辑拼团活动，活动名称：'.$pintuan_name.'，活动编号：'.$pintuan_id);
            showDialog(Language::get('nc_common_op_succ'),self::LINK_LIST,'succ','',3);
        }else {
            showDialog(Language::get('nc_common_op_fail'));
        }
    }

    /**
     * 活动删除
     **/
    public function pintuan_delOp() {
        $pintuan_id = intval($_POST['pintuan_id']);

        $model_pintuan = Model('p_pintuan');

        $data = array();
        $data['result'] = true;

        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        $condition['pintuan_id'] = $pintuan_id;
        $pintuan_info = $model_pintuan->getInfo($condition);
        if(!$pintuan_info) {
            showDialog('参数错误');
        }

        $model_pintuan = Model('p_pintuan');
        $result = $model_pintuan->del($condition);
        if($result) {
            $this->recordSellerLog('删除拼团活动，活动名称：'.$pintuan_info['pintuan_name'].'活动编号：'.$pintuan_id);
            showDialog(L('nc_common_op_succ'), urlShop('store_promotion_pintuan', 'pintuan_list'), 'succ');
        } else {
            showDialog(L('nc_common_op_fail'));
        }
    }

    /**
     * 活动管理
     **/
    public function pintuan_manageOp() {
        $model_pintuan = Model('p_pintuan');

        $pintuan_id = intval($_GET['pintuan_id']);
        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        $condition['pintuan_id'] = $pintuan_id;
        $pintuan_info = $model_pintuan->getInfo($condition);
        Tpl::output('pintuan_info',$pintuan_info);

        //获取商品列表
        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        $condition['pintuan_id'] = $pintuan_id;
        $goods_list = $model_pintuan->getGoodsList($condition, 10);
        Tpl::output('show_page', $model_pintuan->showpage());
        Tpl::output('pintuan_goods_list', $goods_list);

        //输出导航
        self::profile_menu('pintuan_manage');
        Tpl::showpage('store_promotion_pintuan.manage');
    }


    /**
     * 套餐购买
     **/
    public function quota_addOp() {
        //输出导航
        self::profile_menu('pintuan_add');
        Tpl::showpage('store_promotion_pintuan_quota.add');
    }

    /**
     * 套餐购买保存
     **/
    public function quota_add_saveOp() {

        $pintuan_quantity = intval($_POST['pintuan_quantity']);

        if($pintuan_quantity <= 0 || $pintuan_quantity > 12) {
            $pintuan_quantity = 1;
        }

        //获取当前价格
        $current_price = intval(C('promotion_pintuan_price'));

        //获取该用户已有套餐
        $model_pintuan = Model('p_pintuan');
        $current_pintuan= $model_pintuan->getQuotaCurrent($_SESSION['store_id']);
        $add_time = 86400 *30 * $pintuan_quantity;
        if(empty($current_pintuan)) {
            //生成套餐
            $param = array();
            $param['member_id'] = $_SESSION['member_id'];
            $param['member_name'] = $_SESSION['member_name'];
            $param['store_id'] = $_SESSION['store_id'];
            $param['store_name'] = $_SESSION['store_name'];
            $param['start_time'] = TIMESTAMP;
            $param['end_time'] = TIMESTAMP + $add_time;
            $model_pintuan->addQuota($param);
        } else {
            $param = array();
            $param['end_time'] = array('exp', 'end_time + ' . $add_time);
            $model_pintuan->editQuota($param, array('quota_id' => $current_pintuan['quota_id']));
        }

        //记录店铺费用
        $this->recordStoreCost($current_price * $pintuan_quantity, '购买拼团');

        $this->recordSellerLog('购买'.$pintuan_quantity.'份拼团套餐，单价'.$current_price.$lang['nc_yuan']);

        showDialog(Language::get('nc_common_save_succ'),self::LINK_LIST,'succ');
    }

    /**
     * 选择活动商品
     **/
    public function goods_selectOp() {
        $model_goods = Model('goods');
        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        $condition['goods_name'] = array('like', '%'.$_GET['goods_name'].'%');
        $goods_list = $model_goods->getGeneralGoodsOnlineList($condition, '*', 10);

        Tpl::output('goods_list', $goods_list);
        Tpl::output('show_page', $model_goods->showpage());
        Tpl::showpage('store_promotion_pintuan.goods', 'null_layout');
    }

    /**
     * 商品添加
     **/
    public function pintuan_goods_addOp() {
        $goods_id = intval($_POST['goods_id']);
        $pintuan_id = intval($_POST['pintuan_id']);
        $pintuan_price = floatval($_POST['pintuan_price']);

        $model_goods = Model('goods');
        $model_pintuan = Model('p_pintuan');

        $data = array();
        $data['result'] = true;

        $goods_info = $model_goods->getGoodsInfoByID($goods_id);
        if(empty($goods_info) || $goods_info['store_id'] != $_SESSION['store_id']) {
            $data['result'] = false;
            $data['message'] = '参数错误';
            echo json_encode($data);die;
        }
        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        $condition['pintuan_id'] = $pintuan_id;
        $pintuan_info = $model_pintuan->getInfo($condition);
        if(!$pintuan_info) {
            $data['result'] = false;
            $data['message'] = '参数错误';
            echo json_encode($data);die;
        }

        //检查商品是否已经参加同时段活动
        $condition = array();
        $condition['end_time'] = array('gt', $pintuan_info['start_time']);
        $condition['goods_id'] = $goods_id;
        $pintuan_goods = $model_pintuan->getGoodsList($condition);
        if(!empty($pintuan_goods)) {
            $data['result'] = false;
            $data['message'] = '该商品已经参加了同时段活动';
            echo json_encode($data);die;
        }

        //添加到活动商品表
        $param = array();
        $param['pintuan_id'] = $pintuan_info['pintuan_id'];
        $param['pintuan_name'] = $pintuan_info['pintuan_name'];
        $param['pintuan_title'] = $pintuan_info['pintuan_title'];
        $param['pintuan_explain'] = $pintuan_info['pintuan_explain'];
        $param['goods_id'] = $goods_info['goods_id'];
        $param['store_id'] = $goods_info['store_id'];
        $param['goods_name'] = $goods_info['goods_name'];
        $param['goods_price'] = $goods_info['goods_price'];
        $param['pintuan_price'] = $pintuan_price;
        $param['goods_image'] = $goods_info['goods_image'];
        $param['start_time'] = $pintuan_info['start_time'];
        $param['end_time'] = $pintuan_info['end_time'];
        $param['min_num'] = $pintuan_info['min_num'];
        $result = $model_pintuan->addGoods($param);
        if($result) {
            $data['message'] = '添加成功';
            $param['pintuan_goods_id'] = $result;
            $param['goods_url'] =  urlShop('goods', 'index', array('goods_id' => $param['goods_id']));
            $param['image_url'] = thumb($param, 240);
            $data['pintuan_goods'] = $param;
            $this->recordSellerLog('添加拼团商品，活动名称：'.$pintuan_info['pintuan_name'].'，商品名称：'.$goods_info['goods_name']);
        } else {
            $data['result'] = false;
            $data['message'] = '参数错误';
        }
        echo json_encode($data);die;
    }

    /**
     * 商品价格修改
     **/
    public function pintuan_goods_price_editOp() {
        $pintuan_goods_id = intval($_POST['pintuan_goods_id']);
        $pintuan_price = floatval($_POST['pintuan_price']);

        $data = array();
        $data['result'] = true;

        $model_pintuan = Model('p_pintuan');
        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        $condition['pintuan_goods_id'] = $pintuan_goods_id;
        $pintuan_goods_info = $model_pintuan->getGoodsInfo($condition);
        if(!$pintuan_goods_info) {
            $data['result'] = false;
            $data['message'] = '参数错误';
            echo json_encode($data);die;
        }

        $update = array();
        $update['pintuan_price'] = $pintuan_price;
        $result = $model_pintuan->editGoods($update, $condition);

        if($result) {
            $pintuan_goods_info['pintuan_price'] = $pintuan_price;
            $data['pintuan_price'] = $pintuan_goods_info['pintuan_price'];

            $this->recordSellerLog('价格修改为：'.$pintuan_goods_info['pintuan_price'].'，商品名称：'.$pintuan_goods_info['goods_name']);
        } else {
            $data['result'] = false;
            $data['message'] = L('nc_common_op_succ');
        }
        echo json_encode($data);die;
    }

    /**
     * 商品删除
     **/
    public function pintuan_goods_deleteOp() {
        $model_pintuan = Model('p_pintuan');

        $data = array();
        $data['result'] = true;

        $pintuan_goods_id = intval($_POST['pintuan_goods_id']);
        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        $condition['pintuan_goods_id'] = $pintuan_goods_id;
        $pintuan_goods_info = $model_pintuan->getGoodsInfo($condition);
        if(!$pintuan_goods_info) {
            $data['result'] = false;
            $data['message'] = '参数错误';
            echo json_encode($data);die;
        }

        if(!$model_pintuan->delGoods($condition)) {
            $data['result'] = false;
            $data['message'] = '删除失败';
            echo json_encode($data);die;
        }

        $this->recordSellerLog('删除拼团商品，活动名称：'.$pintuan_info['pintuan_name'].'，商品名称：'.$pintuan_goods_info['goods_name']);
        echo json_encode($data);die;
    }

    /**
     * 小导航
     *
     * @param string    $menu_type  导航类型
     * @param string    $menu_key   当前导航的menu_key
     * @param array     $array      附加菜单
     * @return
     */
    private function profile_menu($menu_key='') {
        $menu_array = array(
            1=>array('menu_key'=>'pintuan_list','menu_name'=>'活动列表','menu_url'=>'index.php?con=store_promotion_pintuan&fun=pintuan_list'),
        );
        switch ($menu_key){
            case 'pintuan_add':
                $menu_array[] = array('menu_key'=>'pintuan_add','menu_name'=>'添加活动','menu_url'=>'index.php?con=store_promotion_pintuan&fun=pintuan_add');
                break;
            case 'pintuan_edit':
                $menu_array[] = array('menu_key'=>'pintuan_edit','menu_name'=>'编辑活动','menu_url'=>'javascript:;');
                break;
            case 'quota_add':
                $menu_array[] = array('menu_key'=>'pintuan_add','menu_name'=>'购买套餐','menu_url'=>'index.php?con=store_promotion_pintuan&fun=quota_add');
                break;
            case 'pintuan_manage':
                $menu_array[] = array('menu_key'=>'pintuan_manage','menu_name'=>'商品管理','menu_url'=>'index.php?con=store_promotion_pintuan&fun=pintuan_manage&pintuan_id='.$_GET['pintuan_id']);
                break;
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
}
