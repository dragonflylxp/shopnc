<?php
/**
 * 商品库管理
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

class lib_goodsControl extends SystemControl{
    public function __construct(){
        parent::__construct();
    }

    public function indexOp() {
        $this->listOp();
    }

    /**
     * 列表
     */
    public function listOp(){
        Tpl::showpage('lib_goods.index');
    }

    /**
     * 输出XML数据
     */
    public function get_xmlOp() {
        $model_goods = Model('lib_goods');
        $page = intval($_POST['rp']);
        if ($page < 1) {
            $page = 15;
        }
        $condition = array();
        if ($_POST['qtype']) {
            $condition[$_POST['qtype']] = array('like', '%' . trim($_POST['query']) . '%');
        }
        $order_type = array('goods_addtime','goods_id');
        $sort_type = array('asc','desc');
        $sortname = trim($_POST['sortname']);
        if (!in_array($sortname,$order_type)){
            $sortname = 'goods_addtime';
        }
        $sortorder = trim($_POST['sortorder']);
        if (!in_array($sortorder,$sort_type)){
            $sortorder = 'desc';
        }
        $order = $sortname.' '.$sortorder;
        $list = $model_goods->getGoodsList($condition,$page,'',$order);
        $out_list = array();
        if (!empty($list) && is_array($list)){
            $fields_array = array('goods_name','goods_image','goods_video','goods_jingle','gc_id','gc_name','brand_id','brand_name','goods_addtime');
            foreach ($list as $k => $v){
                $out_array = getFlexigridArray(array(),$fields_array,$v);
                $out_array['goods_addtime'] = date('Y-m-d H:i:s',$v['goods_addtime']);
                $out_array['goods_image'] = "<a href='javascript:void(0);' class='pic-thumb-tip' onMouseOut='toolTip()' onMouseOver='toolTip(\"<img src=".thumb($v,'60').">\")'><i class='fa fa-picture-o'></i></a>";
                if(empty($v['goods_video'])){
                    $out_array['goods_video'] = '';
                }else{
                    if(file_exists(BASE_UPLOAD_PATH . '/' . ATTACH_GOODS . '/0' .'/' . 'goods_video' . '/' . $v['goods_video'])){
                        $out_array['goods_video'] = "<a href='javascript:void(0);' onclick='fg_see_video(".$v['goods_id'].")'><i class='fa fa-file-video-o'></i></a>";
                    }else{
                        $out_array['goods_video'] = '';
                    }
                }
                $operation = '';
                $operation .= '<a class="btn purple" href="javascript:fg_operation_del('.$v['goods_id'].');"><i class="fa fa-trash-o"></i>删除</a>';
                $operation .= '<a class="btn orange" href="index.php?con=lib_goods&fun=goods_edit&goods_id='.$v['goods_id'].'"><i class="fa fa-pencil-square-o"></i>编辑</a>';
                $out_array['operation'] = $operation;
                $out_list[$v['goods_id']] = $out_array;
            }
        }

        $data = array();
        $data['now_page'] = $model_goods->shownowpage();
        $data['total_num'] = $model_goods->gettotalnum();
        $data['list'] = $out_list;
        echo Tpl::flexigridXML($data);exit();
    }
    
    /**
     * 选择分类
     */
    public function goods_classOp(){
        // 实例化商品分类模型
        $model_goodsclass = Model('goods_class');
        // 商品分类
        $goods_class = $model_goodsclass->getGoodsClassListByParentId(0);

        // 常用商品分类
        $model_staple = Model('goods_class_staple');
        $param_array = array();
        $param_array['member_id'] = 0;
        $staple_array = $model_staple->getStapleList($param_array);
        Tpl::output('staple_array', $staple_array);
        Tpl::output('goods_class', $goods_class);
        Tpl::output('goods_id', $_GET['goods_id']);
        Tpl::showpage('lib_goods_class');
    }
    
    /**
     * 新增商品
     */
    public function goods_addOp(){
        // 实例化商品分类模型
        $model_goodsclass = Model('goods_class');
        
        if (chksubmit()) {
            $model_goods = Model('lib_goods');
            $_array = array();
            $_array['goods_name'] = $_POST['g_name'];
            $_array['goods_jingle'] = $_POST['g_jingle'];
            $_array['gc_id'] = $_POST['cate_id'];
            $goods_class = $model_goodsclass->getGoodsClassLineForTag(intval($_array['gc_id']));
            $_array['gc_id_1'] = intval($goods_class['gc_id_1']);
            $_array['gc_id_2'] = intval($goods_class['gc_id_2']);
            $_array['gc_id_3'] = intval($goods_class['gc_id_3']);
            $_array['gc_name'] = $_POST['cate_name'];
            $_array['brand_id'] = $_POST['b_id'];
            $_array['brand_name'] = $_POST['b_name'];
            $_array['goods_barcode'] = $_POST['g_barcode'];
            $_array['goods_image'] = $_POST['image_path'];
            $_array['goods_attr'] = serialize($_POST['attr']);
            $_array['goods_custom'] = serialize($_POST['custom']);
            $_array['goods_img'] = serialize($_POST['img']);
            $_array['goods_body'] = $_POST['g_body'];
            $_array['mobile_body'] = $_POST['m_body'];
            $_array['goods_trans_kg'] = floatval($_POST['goods_trans_kg']);
            $_array['goods_trans_v'] = floatval($_POST['goods_trans_v']);
            $_array['goods_addtime'] = time();
            $_array['goods_edittime'] = time();
            $_array['goods_video'] = $_POST['video_path'];

            $state = $model_goods->addGoods($_array);
            if ($state) {
                $this->log('新增商品，编号'.$state);
                showMessage(Language::get('nc_common_save_succ'),'index.php?con=lib_goods');
            } else {
                showMessage(Language::get('nc_common_save_fail'));
            }
        }
        
        $gc_id = intval($_GET['class_id']);
        // 验证商品分类是否存在且商品分类是否为最后一级
        $data = Model('goods_class')->getGoodsClassForCacheModel();
        if (!isset($data[$gc_id]) || isset($data[$gc_id]['child']) || isset($data[$gc_id]['childchild'])) {
            showDialog('选择的分类不存在，或没有选择到最后一级，请重新选择分类。');
        }

        // 更新常用分类信息
        $goods_class = $model_goodsclass->getGoodsClassLineForTag($gc_id);
        Tpl::output('goods_class', $goods_class);
        Model('goods_class_staple')->autoIncrementStaple($goods_class, 0);
        // 获取类型相关数据
        $typeinfo = Model('type')->getAttr($goods_class['type_id'], 0, $gc_id);
        list($spec_json, $spec_list, $attr_list, $brand_list) = $typeinfo;
        Tpl::output('attr_list', $attr_list);
        Tpl::output('brand_list', $brand_list);
        // 自定义属性
        $custom_list = Model('type_custom')->getTypeCustomList(array('type_id' => $goods_class['type_id']));
        Tpl::output('custom_list', $custom_list);
        Tpl::showpage('lib_goods_add');
    }

    /**
     * 编辑
     */
    public function goods_editOp() {
        $model_goods = Model('lib_goods');
        $model_goodsclass = Model('goods_class');
        $condition = array();
        $condition['goods_id'] = intval($_GET['goods_id']);
        if (chksubmit()) {
            $_array = array();
            $_array['goods_name'] = $_POST['g_name'];
            $_array['goods_jingle'] = $_POST['g_jingle'];
            $_array['gc_id'] = $_POST['cate_id'];
            $goods_class = $model_goodsclass->getGoodsClassLineForTag(intval($_array['gc_id']));
            $_array['gc_id_1'] = intval($goods_class['gc_id_1']);
            $_array['gc_id_2'] = intval($goods_class['gc_id_2']);
            $_array['gc_id_3'] = intval($goods_class['gc_id_3']);
            $_array['gc_name'] = $_POST['cate_name'];
            $_array['brand_id'] = $_POST['b_id'];
            $_array['brand_name'] = $_POST['b_name'];
            $_array['goods_barcode'] = $_POST['g_barcode'];
            $_array['goods_image'] = $_POST['image_path'];
            $_array['goods_attr'] = serialize($_POST['attr']);
            $_array['goods_custom'] = serialize($_POST['custom']);
            $_array['goods_img'] = serialize($_POST['img']);
            $_array['goods_body'] = $_POST['g_body'];
            $_array['mobile_body'] = $_POST['m_body'];
            $_array['goods_trans_kg'] = floatval($_POST['goods_trans_kg']);
            $_array['goods_trans_v'] = floatval($_POST['goods_trans_v']);
            $_array['goods_edittime'] = time();
            $_array['goods_video'] = $_POST['video_path'];
            $state = $model_goods->editGoods($condition, $_array);
            if ($state) {
                $this->log('编辑商品，编号'.$condition['goods_id']);
                showMessage(Language::get('nc_common_save_succ'),'index.php?con=lib_goods');
            } else {
                showMessage(Language::get('nc_common_save_fail'));
            }
        }
        $goods = $model_goods->getGoodsInfo($condition);
        $gc_id = $goods['gc_id'];
        if (intval($_GET['class_id']) > 0) {
            $gc_id = intval($_GET['class_id']);
        }
        $goods_class = $model_goodsclass->getGoodsClassLineForTag($gc_id);
        Tpl::output('goods_class', $goods_class);
        // 获取类型相关数据
        $typeinfo = Model('type')->getAttr($goods_class['type_id'], 0, $gc_id);
        list($spec_json, $spec_list, $attr_list, $brand_list) = $typeinfo;
        Tpl::output('attr_list', $attr_list);
        Tpl::output('brand_list', $brand_list);
        $image_list = array();
        if (!empty($goods['goods_img'])) $image_list = unserialize($goods['goods_img']);
        Tpl::output('img', $image_list);
        // 自定义属性
        $custom_list = Model('type_custom')->getTypeCustomList(array('type_id' => $goods_class['type_id']));
        $custom_list = array_under_reset($custom_list, 'custom_id');
        Tpl::output('custom_list', $custom_list);
        $goods['goods_custom'] = unserialize($goods['goods_custom']);
        if ($goods['mobile_body'] != '') {
            $goods['mb_body'] = unserialize($goods['mobile_body']);
            if (is_array($goods['mb_body'])) {
                $mobile_body = '[';
                foreach ($goods['mb_body'] as $val ) {
                    $mobile_body .= '{"type":"' . $val['type'] . '","value":"' . $val['value'] . '"},';
                }
                $mobile_body = rtrim($mobile_body, ',') . ']';
            }
            $goods['mobile_body'] = $mobile_body;
        }
        Tpl::output('goods',$goods);
        Tpl::showpage('lib_goods_add');
    }

    /**
     * 删除商品
     *
     */
    public function del_goodsOp() {
        $id = intval($_GET['goods_id']);
        $model_goods = Model('lib_goods');
        $condition = array();
        $condition['goods_id'] = $id;
        $state = $model_goods->delGoods($condition);
        if ($state) {
            $this->log('删除商品，编号'.$id);
            exit(json_encode(array('state'=>true,'msg'=>'删除成功')));
        } else {
            exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
        }
    }

    /**
     * 预览视频
     */
    public function see_videoOp(){
        if(!$_GET['id']){
            showMessage('参数非法');
        }

        $model_goods = Model('lib_goods');
        $info = $model_goods->getGoodsInfo(array('goods_id' => $_GET['id']));
        Tpl::output('info' , $info );
        Tpl::showpage('lib_goods_see_video' , 'null_layout');
    }
}
