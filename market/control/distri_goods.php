<?php
/**
 * 分销商品
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

class distri_goodsControl extends MemberDistributeControl{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * 分销商品列表页
     */
    public function indexOp(){
        $this->goods_listOp();
    }

    public function goods_listOp(){
        $model_goods = Model('dis_goods');
        $condition = array('member_id'=>$_SESSION['member_id']);
        $condition['dis_goods.distri_goods_state'] = 1;
        if(trim($_GET['goods_name']) != ''){
            $condition['goods_common.goods_name|goods_common.goods_jingle'] = array('like', '%' . $_GET['goods_name'] . '%');
        }
        $goods_list = $model_goods->getDistriGoodsCommonList($condition,'*',8);
        Tpl::output('goods_list',$goods_list);
        Tpl::output('show_page',$model_goods->showpage(2));
        Tpl::showpage('distri_goods.list');
    }

    /**
     * 删除分销商品
     */
    public function drop_goodsOp(){
        $distri_id = intval($_GET['distri_id']);
        if($distri_id <= 0){
            showMessage('参数错误');
        }
        $model_goods = Model('dis_goods');
        $condition = array('distri_id' => $distri_id);
        $condition['member_id'] = $_SESSION['member_id'];
        $stat = $model_goods->delDistriGoods($condition);
        if($stat){
            showDialog('删除成功','index.php?con=distri_goods','succ');
        }else{
            showDialog('删除失败','index.php?con=distri_goods','error');
        }
    }
}