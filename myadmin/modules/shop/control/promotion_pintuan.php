<?php
/**
 * 拼团管理
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
class promotion_pintuanControl extends SystemControl{

    public function __construct(){
        parent::__construct();
        $model_pintuan = Model('p_pintuan');
        $model_pintuan->getStateArray();
    }

    /**
     * 默认
     */
    public function indexOp() {
        $this->pintuan_listOp();

    }

    /**
     * 活动列表
     */
    public function pintuan_listOp()
    {
        $this->show_menu('pintuan_list');
        Tpl::showpage('promotion_pintuan.list');
    }

    /**
     * 活动列表
     */
    public function pintuan_list_xmlOp()
    {
        $condition = array();
        if ($_REQUEST['advanced']) {
            if (strlen($q = trim((string) $_REQUEST['pintuan_name']))) {
                $condition['pintuan_name'] = array('like', '%' . $q . '%');
            }
            if (strlen($q = trim((string) $_REQUEST['store_name']))) {
                $condition['store_name'] = array('like', '%' . $q . '%');
            }
            if ($_REQUEST['state'] != '') {
                $condition['state'] = intval($_REQUEST['state']);
            }

            $pdates = array();
            if (strlen($q = trim((string) $_REQUEST['pdate1'])) && ($q = strtotime($q . ' 00:00:00'))) {
                $pdates[] = "end_time >= {$q}";
            }
            if (strlen($q = trim((string) $_REQUEST['pdate2'])) && ($q = strtotime($q . ' 00:00:00'))) {
                $pdates[] = "start_time <= {$q}";
            }
            if ($pdates) {
                $condition['pdates'] = array(
                    'exp',
                    implode(' or ', $pdates),
                );
            }

        } else {
            if (strlen($q = trim($_REQUEST['query']))) {
                switch ($_REQUEST['qtype']) {
                    case 'pintuan_name':
                        $condition['pintuan_name'] = array('like', '%'.$q.'%');
                        break;
                    case 'store_name':
                        $condition['store_name'] = array('like', '%'.$q.'%');
                        break;
                }
            }
        }

        $model_pintuan = Model('p_pintuan');
        $pintuan_list = (array) $model_pintuan->getList($condition, $_REQUEST['rp']);
        $state_array = $model_pintuan->getStateArray();

        $flippedOwnShopIds = array_flip(Model('store')->getOwnShopIds());

        $data = array();
        $data['now_page'] = $model_pintuan->shownowpage();
        $data['total_num'] = $model_pintuan->gettotalnum();

        foreach ($pintuan_list as $val) {
            $o  = '<a class="btn red confirm-on-click" href="javascript:;" data-href="' . urlAdminShop('promotion_pintuan', 'pintuan_del', array(
                'pintuan_id' => $val['pintuan_id'],
            )) . '"><i class="fa fa-trash-o"></i>删除</a>';

            $o .= '<span class="btn"><em><i class="fa fa-cog"></i>设置<i class="arrow"></i></em><ul>';

            if ($val['state']) {
                $o .= '<li><a class="confirm-on-click" href="javascript:;" data-href="' . urlAdminShop('promotion_pintuan', 'pintuan_cancel', array(
                    'pintuan_id' => $val['pintuan_id'],
                )) . '">取消活动</a></li>';
            }

            $o .= '<li><a class="confirm-on-click" href="' . urlAdminShop('promotion_pintuan', 'pintuan_detail', array(
                'pintuan_id' => $val['pintuan_id'],
            )) . '">活动详细</a></li>';

            $o .= '</ul></span>';

            $i = array();
            $i['operation'] = $o;
            $i['pintuan_name'] = $val['pintuan_name'];
            $i['store_name'] = '<a target="_blank" href="' . urlShop('show_store', 'index', array(
                'store_id'=>$val['store_id'],
            )) . '">' . $val['store_name'] . '</a>';

            if (isset($flippedOwnShopIds[$val['store_id']])) {
                $i['store_name'] .= '<span class="ownshop">[自营]</span>';
            }

            $i['start_time_text'] = date('Y-m-d H:i', $val['start_time']);
            $i['end_time_text'] = date('Y-m-d H:i', $val['end_time']);

            $i['min_num'] = $val['min_num'];
            $i['state_text'] = $val['end_time'] > TIMESTAMP ? $state_array[$val['state']]:'已结束';

            $data['list'][$val['pintuan_id']] = $i;
        }

        echo Tpl::flexigridXML($data);
        exit;
    }

    /**
     * 活动取消
     **/
    public function pintuan_cancelOp() {
        $pintuan_id = intval($_REQUEST['pintuan_id']);
        $model_pintuan = Model('p_pintuan');
        $result = $model_pintuan->cancel(array('pintuan_id' => $pintuan_id));
        if($result) {
            $this->log('取消拼团活动，活动编号'.$pintuan_id);
            $this->jsonOutput();
        } else {
            $this->jsonOutput('操作失败');
        }
    }

    /**
     * 活动删除
     **/
    public function pintuan_delOp() {
        $pintuan_id = intval($_REQUEST['pintuan_id']);
        $model_pintuan = Model('p_pintuan');
        $result = $model_pintuan->del(array('pintuan_id' => $pintuan_id));
        if($result) {
            $this->log('删除拼团活动，活动编号'.$pintuan_id);

            $this->jsonOutput();
        } else {
            $this->jsonOutput('操作失败');
        }
    }

    /**
     * 活动详细信息
     **/
    public function pintuan_detailOp() {
        $pintuan_id = intval($_GET['pintuan_id']);

        $model_pintuan = Model('p_pintuan');
        $condition = array();
        $condition['pintuan_id'] = $pintuan_id;
        $pintuan_info = $model_pintuan->getInfo($condition);
        Tpl::output('pintuan_info',$pintuan_info);

        //获取商品列表
        $pintuan_goods_list = $model_pintuan->getGoodsList($condition);
        Tpl::output('list',$pintuan_goods_list);
        Tpl::showpage('promotion_pintuan.detail');
    }

    /**
     * 套餐管理
     */
    public function pintuan_quotaOp()
    {
        $this->show_menu('pintuan_quota');
        Tpl::showpage('promotion_pintuan_quota.list');
    }

    /**
     * 套餐管理XML
     */
    public function pintuan_quota_xmlOp()
    {
        $condition = array();

        if (strlen($q = trim($_REQUEST['query']))) {
            switch ($_REQUEST['qtype']) {
                case 'store_name':
                    $condition['store_name'] = array('like', '%'.$q.'%');
                    break;
            }
        }

        $model_pintuan = Model('p_pintuan');
        $list = (array) $model_pintuan->getQuotaList($condition, $_REQUEST['rp']);

        $data = array();
        $data['now_page'] = $model_pintuan->shownowpage();
        $data['total_num'] = $model_pintuan->gettotalnum();

        foreach ($list as $val) {
            $i = array();
            $i['operation'] = '<span>--</span>';

            $i['store_name'] = '<a target="_blank" href="' . urlShop('show_store', 'index', array(
                'store_id' => $val['store_id'],
            )) . '">' . $val['store_name'] . '</a>';

            $i['start_time_text'] = date("Y-m-d", $val['start_time']);
            $i['end_time_text'] = date("Y-m-d", $val['end_time']);

            $data['list'][$val['quota_id']] = $i;
        }

        echo Tpl::flexigridXML($data);
        exit;
    }

    /**
     * 设置
     **/
    public function pintuan_settingOp() {
        $model_setting = Model('setting');
        $setting = $model_setting->GetListSetting();
        Tpl::output('setting',$setting);

        $this->show_menu('pintuan_setting');
        Tpl::showpage('promotion_pintuan.setting');
    }

    public function pintuan_setting_saveOp() {
        $promotion_pintuan_price = intval($_POST['promotion_pintuan_price']);
        if($promotion_pintuan_price < 0) {
            $promotion_pintuan_price = 20;
        }
        $model_setting = Model('setting');
        $update_array = array();
        $update_array['promotion_pintuan_price'] = $promotion_pintuan_price;
        $result = $model_setting->updateSetting($update_array);
        if ($result){
            $this->log('修改拼团价格为'.$promotion_pintuan_price.'元');
            showMessage(Language::get('nc_common_save_succ'),'');
        }else {
            showMessage(Language::get('nc_common_save_fail'),'');
        }
    }

    /**
     * 页面内导航菜单
     *
     * @param string    $menu_key   当前导航的menu_key
     * @param array     $array      附加菜单
     * @return
     */
    private function show_menu($menu_key) {
        $menu_array = array(
            'pintuan_list'=>array('menu_type'=>'link','menu_name'=>'活动列表','menu_url'=>'index.php?con=promotion_pintuan&fun=pintuan_list'),
            'pintuan_quota'=>array('menu_type'=>'link','menu_name'=>'套餐管理','menu_url'=>'index.php?con=promotion_pintuan&fun=pintuan_quota'),
            'pintuan_setting'=>array('menu_type'=>'link','menu_name'=>'设置','menu_url'=>'index.php?con=promotion_pintuan&fun=pintuan_setting'),
        );
        $menu_array[$menu_key]['menu_type'] = 'text';
        Tpl::output('menu',$menu_array);
    }
}
