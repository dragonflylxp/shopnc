<?php
/**
 * 分销-商品管理
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

class distri_goodsControl extends SystemControl{
    const EXPORT_SIZE = 2000;
    function __construct()
    {
        parent::__construct();
    }

    public function indexOp(){
        $this->goodsOp();
    }

    /**
     * 商品管理
     */
    public function goodsOp() {
        //父类列表，只取到第二级
        $gc_list = Model('goods_class')->getGoodsClassList(array('gc_parent_id' => 0));
        Tpl::output('gc_list', $gc_list);

        Tpl::showpage('distri_goods.index');
    }

    /**
     * 输出XML数据
     */
    public function get_xmlOp() {
        $model_goods = Model('goods');
        $condition = array();
        if ($_GET['goods_name'] != '') {
            $condition['goods_name'] = array('like', '%' . $_GET['goods_name'] . '%');
        }
        if ($_GET['store_name'] != '') {
            $condition['store_name'] = array('like', '%' . $_GET['store_name'] . '%');
        }
        if ($_GET['brand_name'] != '') {
            $condition['brand_name'] = array('like', '%' . $_GET['brand_name'] . '%');
        }
        if (intval($_GET['cate_id']) > 0) {
            $condition['gc_id'] = intval($_GET['cate_id']);
        }
        if ($_GET['goods_state'] != '') {
            $condition['goods_state'] = $_GET['goods_state'];
        }
        if ($_GET['goods_verify'] != '') {
            $condition['goods_verify'] = $_GET['goods_verify'];
        }
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        $condition['is_dis'] = 1;
        $order = '';
        $param = array('goods_commonid', 'goods_name', 'goods_price', 'goods_state', 'goods_verify', 'goods_image', 'gc_name', 'store_name', 'is_own_shop', 'brand_name','dis_add_time','dis_commis_rate','sale_count','click_count');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
        $page = $_POST['rp'];

        $goods_list = $model_goods->getGoodsCommonOnlineList($condition, '*', $page, $order);

        // 商品状态
        $goods_state = $this->getGoodsState();

        // 审核状态
        $verify_state = $this->getGoodsVerify();

        $data = array();
        $data['now_page'] = $model_goods->shownowpage();
        $data['total_num'] = $model_goods->gettotalnum();
        foreach ($goods_list as $value) {
            $param = array();
            $operation = '';
            $operation .= "<a class='btn red' href='javascript:void(0);' onclick=\"fg_del('" . $value['goods_commonid'] . "')\"><i class='fa fa-ban'></i>终止分销</a>";
            $operation .= "<a class='btn green' href='". urlShop('distri_goods', 'goods_detail', array('goods_id' => $value['goods_commonid'])) ."' target=\"_blank\"><i class='fa fa-list-alt'></i>查看</a>";
            $param['operation'] = $operation;
            $param['goods_commonid'] = $value['goods_commonid'];
            $param['goods_name'] = "<a href='". urlShop('distri_goods', 'goods_detail', array('goods_id' => $value['goods_commonid'])) ."' target=\"_blank\">".$value['goods_name']."</a>";
            $param['goods_price'] = ncPriceFormat($value['goods_price']);
            $param['goods_state'] = $goods_state[$value['goods_state']];
            $param['goods_verify'] = $verify_state[$value['goods_verify']];
            $param['goods_image'] = "<a href='javascript:void(0);' class='pic-thumb-tip' onMouseOut='toolTip()' onMouseOver='toolTip(\"<img src=".thumb($value,'60').">\")'><i class='fa fa-picture-o'></i></a>";
            $param['gc_name'] = $value['gc_name'];
            $param['store_name'] = $value['store_name'];
            $param['is_own_shop'] = $value['is_own_shop'] == 1 ? '平台自营' : '入驻商户';
            $param['brand_name'] = $value['brand_name'];
            $param['dis_add_time'] = date('Y-m-d', $value['dis_add_time']);
            $param['dis_commis_rate'] = $value['dis_commis_rate'].'%';
            $param['sale_count'] = $value['sale_count'];

            $data['list'][$value['goods_commonid']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }

    /**
     * 商品状态
     * @return multitype:string
     */
    private function getGoodsState() {
        return array('1' => '出售中', '0' => '仓库中', '10' => '违规下架');
    }

    private function getGoodsVerify() {
        return array('1' => '通过', '0' => '未通过', '10' => '等待审核');
    }

    /**
     * 分销详情
     */
    public function distri_infoOp(){}

    /**
     * 取消分销
     */
    public function del_distriOp(){
        $goods_commonid = intval($_GET['id']);
        $data = array();
        $data['state'] = false;
        if($goods_commonid <= 0){
            $data['msg'] = '参数错误';
            exit(json_encode($data));
        }
        $model_goods = Model('goods');
        $goods_info = $model_goods->getGoodsCommonInfoByID($goods_commonid);
        if(!empty($goods_info) && is_array($goods_info)){
            $model_dis_goods = Model('dis_goods');
            $stat = $model_dis_goods->delGoods(array('goods_commonid'=>$goods_commonid));
            if($stat){
                $data['state'] = true;
                exit(json_encode($data));
            }else{
                $data['msg'] = '取消失败';
                exit(json_encode($data));
            }
        }else{
            $data['msg'] = '商品不存在';
            exit(json_encode($data));
        }
    }

    /**
     * csv导出
     */
    public function export_csvOp() {
        $model_goods = Model('goods');
        $condition = array();
        $limit = false;
        if ($_GET['id'] != '') {
            $id_array = explode(',', $_GET['id']);
            $condition['goods_commonid'] = array('in', $id_array);
        }
        if ($_GET['goods_name'] != '') {
            $condition['goods_name'] = array('like', '%' . $_GET['goods_name'] . '%');
        }
        if ($_GET['goods_commonid'] != '') {
            $condition['goods_commonid'] = array('like', '%' . $_GET['goods_commonid'] . '%');
        }
        if ($_GET['store_name'] != '') {
            $condition['store_name'] = array('like', '%' . $_GET['store_name'] . '%');
        }
        if ($_GET['brand_name'] != '') {
            $condition['brand_name'] = array('like', '%' . $_GET['brand_name'] . '%');
        }
        if ($_GET['cate_id'] != '') {
            $condition['gc_id'] = $_GET['cate_id'];
        }
        if ($_GET['goods_state'] != '') {
            $condition['goods_state'] = $_GET['goods_state'];
        }
        if ($_GET['goods_verify'] != '') {
            $condition['goods_verify'] = $_GET['goods_verify'];
        }
        if ($_REQUEST['query'] != '') {
            $condition[$_REQUEST['qtype']] = array('like', '%' . $_REQUEST['query'] . '%');
        }
        $condition['is_dis'] = 1;
        $order = '';
        $param = array('goods_commonid', 'goods_name', 'goods_price', 'goods_state', 'goods_verify', 'goods_image', 'goods_jingle', 'gc_id'
        , 'gc_name', 'store_id', 'store_name', 'is_own_shop', 'brand_name','dis_add_time','dis_commis_rate','sale_count','click_count');

        if (in_array($_REQUEST['sortname'], $param) && in_array($_REQUEST['sortorder'], array('asc', 'desc'))) {
            $order = $_REQUEST['sortname'] . ' ' . $_REQUEST['sortorder'];
        }
        if (!is_numeric($_GET['curpage'])){
            $count = $model_goods->getGoodsCommonCount($condition);
            if ($count > self::EXPORT_SIZE ){   //显示下载链接
                $array = array();
                $page = ceil($count/self::EXPORT_SIZE);
                for ($i=1;$i<=$page;$i++){
                    $limit1 = ($i-1)*self::EXPORT_SIZE + 1;
                    $limit2 = $i*self::EXPORT_SIZE > $count ? $count : $i*self::EXPORT_SIZE;
                    $array[$i] = $limit1.' ~ '.$limit2 ;
                }
                Tpl::output('list',$array);
                Tpl::output('murl','index.php?con=distri_goods&fun=index');
                Tpl::showpage('export.excel');
                exit();
            }
        } else {
            $limit1 = ($_GET['curpage']-1) * self::EXPORT_SIZE;
            $limit2 = self::EXPORT_SIZE;
            $limit = $limit1 .','. $limit2;
        }

        $goods_list = $model_goods->getGoodsCommonOnlineList($condition, '*', null, $order, $limit);
        $this->createCsv($goods_list);
    }

    /**
     * 生成csv文件
     */
    private function createCsv($goods_list) {
        // 库存
        $storage_array = Model('goods')->calculateStorage($goods_list);

        // 商品状态
        $goods_state = $this->getGoodsState();

        // 审核状态
        $verify_state = $this->getGoodsVerify();
        $data = array();
        foreach ($goods_list as $value) {
            $param = array();
            $param['goods_commonid'] = $value['goods_commonid'];
            $param['goods_name'] = $value['goods_name'];
            $param['goods_price'] = ncPriceFormat($value['goods_price']);
            $param['goods_state'] = $goods_state[$value['goods_state']];
            $param['goods_verify'] = $verify_state[$value['goods_verify']];
            $param['goods_image'] = "<a href='javascript:void(0);' class='pic-thumb-tip' onMouseOut='toolTip()' onMouseOver='toolTip(\"<img src=".thumb($value,'60').">\")'><i class='fa fa-picture-o'></i></a>";
            $param['goods_jingle'] = $value['goods_jingle'];
            $param['gc_id'] = $value['gc_id'];
            $param['gc_name'] = $value['gc_name'];
            $param['store_id'] = $value['store_id'];
            $param['store_name'] = $value['store_name'];
            $param['is_own_shop'] = $value['is_own_shop'] == 1 ? '平台自营' : '入驻商户';
            $param['brand_name'] = $value['brand_name'];
            $param['dis_add_time'] = date('Y-m-d', $value['dis_add_time']);
            $param['dis_commis_rate'] = $value['dis_commis_rate'].'%';
            $param['sale_count'] = $value['sale_count'];

            $data[$value['goods_commonid']] = $param;
        }

        $header = array(
            'goods_commonid' => 'SPU',
            'goods_name' => '商品名称',
            'goods_price' => '商品价格(元)',
            'goods_state' => '商品状态',
            'goods_verify' => '审核状态',
            'goods_image' => '商品图片',
            'goods_jingle' => '广告词',
            'gc_id' => '分类ID',
            'gc_name' => '分类名称',
            'store_id' => '店铺ID',
            'store_name' => '店铺名称',
            'is_own_shop' => '店铺类型',
            'brand_name' => '品牌名称',
            'dis_add_time' => '分销发布时间',
            'dis_commis_rate' => '分销佣金比例',
            'sale_count' => '销量'
        );
        \shopec\Lib::exporter()->output('distri_goods_list' .$_GET['curpage'] . '-'.date('Y-m-d'), $data, $header);
    }
}