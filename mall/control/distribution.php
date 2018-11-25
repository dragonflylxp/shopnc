<?php
/**
 * 分销功能控制模块
 */

use shopec\Tpl;


defined('Inshopec') or exit('Access Invalid!');
class distributionControl extends BaseSellerControl {
    public function __construct()
    {
        parent::__construct();
        Language::read('member_store_index');

        //加载分销语言文件
        Language::read ('distribution');
    }

    public function indexOp() {
        $this->commision_listOp();
    }

    /**
     * 佣金列表
     *
     */
    public function commision_listOp()
    {
        $store_info = $this->store_info;
        $model_mingxi = Model('mingxi');
        $model_goods = Model('goods');

        $condition = array();
		//$condition['store_id'] = $store_info['store_id'];
        if (trim($_POST['commision_level']) != '') {
            $condition['commision_level']   = $_POST['commision_level'];
            Tpl::output('commision_level', $_POST['commision_level']);
        }
        if (trim($_POST['order_sn']) != '') {
            $condition['order_sn']  = array('like', '%'.trim($_POST['order_sn']).'%');
            Tpl::output('order_sn', $_POST['order_sn']);
        }
        $mingxi_list = $model_mingxi->getMingxiList($condition);

        $commision_list = array();
        foreach ($mingxi_list as $key => $val) {
            if ($this->getStoreId($val['goods_id'])==$store_info['store_id']) {
                $commision_list[$key] = $val;
            }
        }
        foreach ($commision_list as $key => $val) {
            $goods_info = $model_goods->getGoodsInfo(array('goods_id'=>$val['goods_id']));
            $commision_list[$key]['goods_name'] = $goods_info['goods_name'];
        }
        
        Tpl::output('commision_list', $commision_list);
        Tpl::output('show_page', $model_mingxi->showpage());
        Tpl::showpage('distribution.commision_list');
    }

    /**
     * 佣金设置
     */
    public function commision_settingOp() {
        if (chksubmit()) {
            $model_goods = Model('goods');
            $goods_commonid = intval($_POST['goods_commonid']);

            $condition = array(
                'goods_commonid' => $goods_commonid,
                'store_id' => $this->store_info['store_id']
            );

            $update = array(
                'fencheng1' => ncPriceFormat(trim($_POST['fencheng1'])),
                'fencheng2' => ncPriceFormat(trim($_POST['fencheng2'])),
                'fencheng3' => ncPriceFormat(trim($_POST['fencheng3'])),
            );

            $result = $model_goods->editGoodsCommonById($update, $condition);
            if ($result) {
                //添加操作日志
                $this->recordSellerLog('设置商品佣金，SPU：' . $goods_commonid);
                showDialog(L('commision_setting_success'), 'reload', 'succ');
            } else {
                showDialog(L('commision_setting_failed'), '', 'error');
            }
        } else {
            $goods_commonid = intval($_GET['goods_commonid']);
            $model_goods = Model('goods');
            $goods_common_info = $model_goods->getGoodsCommonInfoByID($goods_commonid);
        
            Tpl::output('goods_commonid', $goods_commonid);
            Tpl::output('goods_common_info', $goods_common_info);
            Tpl::showpage('distribution.commision_setting', 'null_layout');
        }
    }

    /**
     * 获取店铺ID
     * @param  [int] $goods_id 商品ID
     * @return [int] $store_id 店铺ID
     */
    private function getStoreId($goods_id)
    {
        $model_goods = Model('goods');
        $goods_info = $model_goods->getGoodsInfo(array('goods_id'=>$goods_id));
        return $goods_info['store_id'];
    }
}
