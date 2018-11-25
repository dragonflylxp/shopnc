<?php
/**
 * 分销-分销商管理
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net
 * @link       http://www.shopec.net
 * @since      File available since Release v1.1
 */

use shopec\Tpl;

defined('Inshopec') or exit('Access Invalid!');

class distri_memberControl extends SystemControl
{
    private $_links = array(array('url' => 'con=distri_member&fun=member', 'text' => '管理'), array('url' => 'con=distri_member&fun=auth_up', 'text' => '认证申请'));
    const EXPORT_SIZE = 2000;

    function __construct()
    {
        parent::__construct();
    }

    public function indexOp()
    {
        $this->memberOp();
    }

    public function memberOp()
    {
        //输出子菜单
        Tpl::output('top_link', $this->sublink($this->_links, 'member'));
        Tpl::output('mem_stat', 2);
        Tpl::showpage('member.index');
    }

    /**
     * 认证申请
     */
    public function auth_upOp()
    {
        //输出子菜单
        Tpl::output('top_link', $this->sublink($this->_links, 'auth_up'));
        Tpl::output('mem_stat', 1);
        Tpl::showpage('member.index');
    }

    /**
     * 认证详情
     */
    public function member_infoOp()
    {
        $member_id = intval($_REQUEST['member_id']);
        if ($member_id <= 0) {
            showMessage('会员不存在', 'index.php?con=distri_member');
        }
        $member_model = Model('member');
        $member_info = $member_model->getMemberInfoByID($member_id);

        //可提现金额
        $available_trad = $member_info['trad_amount'];

        //冻结金额
        $freeze_trad = floatval($member_info['freeze_trad']);
        if($member_info['distri_state'] == 2){
            if($member_info['trad_amount'] >= C('distribute_bill_limit')){
                $freeze_trad += C('distribute_bill_limit');
                $available_trad -= C('distribute_bill_limit');
            }else{
                $freeze_trad += $member_info['trad_amount'];
                $available_trad = 0;
            }
        }

        $member_info['available_distri_trad'] = $available_trad;
        $member_info['freeze_distri_trad'] = $freeze_trad;

        Tpl::output('member_info', $member_info);
        $sex_array = $this->get_sex();
        Tpl::output('sex_array', $sex_array);
        Tpl::showpage('member.info');
    }

    /**
     * 会员认证
     */
    public function authOp()
    {
        if (!empty($_POST)) {
            $param = array();
            $member_model = Model('member');
            $param['auth_message'] = trim($_POST['joinin_message']);
            $param['distri_state'] = $this->_get_stat($_POST['verify_type']);
            $param['distri_code'] = getUniqueCode(uniqid());

            if($_POST['verify_type'] == 'pass'){
                $param['distri_handle_time'] = time();
                $param['distri_show'] = 1;
            }

            $member_id = intval($_POST['member_id']);

            $stat = $member_model->editMember(array('member_id' => $member_id), $param);
            if ($stat) {
                showMessage('认证成功', 'index.php?con=distri_member');
            } else {
                showMessage('认证失败', 'index.php?con=distri_member&fun=member_info&member_id=' . $member_id);
            }
        } else {
            showMessage('非法请求', 'index.php?con=distri_member');
        }
    }

    /**
     * 清退分销商
     */
    public function member_cancleOp()
    {
        $member_id = intval($_GET['member_id']);
        $data = array();
        $data['state'] = false;
        if ($member_id <= 0) {
            $data['msg'] = '参数错误';
            exit(json_encode($data));
        }
        $member_model = Model('member');
        $param = array();
        $param['distri_state'] = 4;
        $param['distri_code'] = '';
        $param['quit_time'] = time();
        $param['distri_quit_times'] = array('exp','distri_quit_times+1');
        $condition = array();
        $condition['member_id'] = $member_id;
        $stat = $member_model->editMember($condition, $param);
        if ($stat) {
            $member_info = $member_model->getMemberInfo($condition);
            $dis_goods_model = Model('dis_goods');
            $dis_goods_model->delDistriGoods($condition);
            Model('dis_trad')->autoDistriTrad($member_info);
            $data['state'] = true;
            exit(json_encode($data));
        } else {
            $data['msg'] = '清退失败';
            exit(json_encode($data));
        }
    }

    /**
     * 分销员查看
     */
    public function show_memberOp()
    {
        $member_id = intval($_GET['member_id']);
        if($member_id <= 0){
            showMessage('参数错误','index.php?con=distri_member&fun=show_member','html','error');
        }
        $this->_links[] = array('url' => 'con=distri_member&fun=show_member', 'text' => '分销订单');
        Tpl::output('top_link', $this->sublink($this->_links, 'show_member'));
        Tpl::output('member_id',$member_id);
        Tpl::showpage('member.show');
    }

    /**
     * 性别
     * @return multitype:string
     */
    private function get_sex() {
        $array = array();
        $array[1] = '男';
        $array[2] = '女';
        $array[3] = '保密';
        return $array;
    }

    /**
     * 获取审核状态值
     * @param $param
     * @return int
     */
    private function _get_stat($param)
    {
        $stat = 1;
        if ($param == 'pass') {
            $stat = 2;
        } elseif ($param == 'fail') {
            $stat = 3;
        }
        return $stat;
    }

    /**
     * 输出XML数据
     */
    public function get_xmlOp()
    {
        $model_member = Model('member');
        $condition = array();
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        $order = '';
        $param = array('member_id', 'member_name', 'member_avatar', 'member_email', 'member_mobile', 'member_sex', 'member_truename', 'member_time', 'member_login_time', 'member_login_ip', 'trad_amount', 'distri_state', 'freeze_trad');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
        $page = $_POST['rp'];
        $distri_stat = 1;
        if ($_REQUEST['mem_state'] == 2) {
            $condition['distri_state'] = array('in',array(2,4,5));
            $distri_stat = $_REQUEST['mem_state'];
        } else {
            $condition['distri_state'] = array('gt', 0);
        }

        $member_list_tmp = $model_member->getMemberList($condition, '*', $page, $order);

        $member_list = array();
        foreach ($member_list_tmp as $value) {
            $member_list[$value['member_id']] = $value;
        }

        $this->getMemberExtension($member_list);

        $data = array();
        $data['now_page'] = $model_member->shownowpage();
        $data['total_num'] = $model_member->gettotalnum();


        foreach ($member_list as $value) {
            $param = array();
            $operation = '';
            switch ($distri_stat) {
                case 1:
                    if (in_array($value['distri_state'], array('1', '3'))) {
                        $operation .= "<a class='btn orange' href=\"index.php?con=distri_member&fun=member_info&member_id=" . $value['member_id'] . "\"><i class=\"fa fa-check-circle-o\"></i>审核</a>";
                    }
                    $operation .= "<a class='btn green' href='index.php?con=distri_member&fun=member_info&member_id=" . $value['member_id'] . "'><i class='fa fa-list-alt'></i>查看</a>";
                    break;
                case 2:
                    if ($value['distri_state'] == 2) {
                        $operation .= "<a class='btn red' href='javascript:void(0);' onclick=\"fg_del('" . $value['member_id'] . "')\"><i class='fa fa-ban'></i>清退</a>";
                    }
                    $operation .= "<a class='btn green' href='index.php?con=distri_member&fun=show_member&member_id=" . $value['member_id'] . "'><i class='fa fa-list-alt'></i>查看</a>";
                    break;
            }

            $param['operation'] = $operation;
            $param['member_id'] = $value['member_id'];
            $param['member_name'] = "<img src=" . getMemberAvatarForID($value['member_id']) . " class='user-avatar' onMouseOut='toolTip()' onMouseOver='toolTip(\"<img src=" . getMemberAvatarForID($value['member_id']) . ">\")'>" . $value['member_name'];
            if ($distri_stat == 1) {
                $param['distri_stat'] = str_replace(array('1', '2', '3', '4', '5'), array('待审核', '已通过', '未通过', '清退', '退出'), $value['distri_state']);
                $param['member_mobile'] = $value['member_mobile'];
                $param['member_email'] = $value['member_email'];
                $param['distri_time'] = $value['distri_time'] ? date('Y-m-d', $value['distri_time']) : '';
                $param['distri_handle_time'] = ($value['distri_handle_time'] && $value['distri_state'] == 2) ? date('Y-m-d', $value['distri_handle_time']) : '';
            } else {
                $param['member_email'] = $value['member_email'];
                $param['member_mobile'] = $value['member_mobile'];
                $param['order_count'] = $value['order_count'] ? $value['order_count'] : 0;
                $param['had_pay_amount'] = ncPriceFormat($value['had_pay_amount']);
                $param['unpay_amount'] = ncPriceFormat($value['unpay_amount']);
                $param['distri_amount'] = ncPriceFormat($value['had_pay_amount'] + $value['unpay_amount']);
            }

            $data['list'][$value['member_id']] = $param;
        }
        echo Tpl::flexigridXML($data);
        exit();
    }

    public function get_member_xmlOp()
    {
        $member_id = intval($_GET['member_id']);

        $condition = array();
        $condition['order_goods.is_dis'] = 1;
        $condition['order_goods.dis_member_id'] = $member_id;
        $field = 'orders.order_sn,orders.add_time,orders.order_amount,orders.order_state,sum(dis_pay.dis_pay_amount) as dis_pay_amount,dis_pay.dis_pay_time,dis_pay.log_state';
        $model = Model('dis_order');
        $page = $_POST['rp'];
        $member_list = $model->getMeberDistriOrderWithPayList($condition, $field, $page, 'order_goods.rec_id desc', '', 'orders.order_sn');

        $data = array();
        $data['now_page'] = $model->shownowpage();
        $data['total_num'] = $model->gettotalnum();

        foreach ($member_list as $value) {
            $param = array();
            $param['order_sn'] = $value['order_sn'];
            $param['add_time'] = date('Y-m-d', $value['add_time']);
            $param['order_amount'] = ncPriceFormat($value['order_amount']);
            $param['order_state'] = str_replace(array(10,20,30,40,0),array('待付款','待发货','已发货','已完成','已取消'),$value['order_state']);
            $param['dis_pay_amount'] = ncPriceFormat($value['dis_pay_amount']);
            $param['dis_pay_time'] = $value['dis_pay_time']?date('Y-m-d', $value['dis_pay_time']):'';
            $param['log_state'] = str_replace(array(1,0),array('已结算','未结算'),intval($value['log_state']));
            $data['list'][$value['order_sn']] = $param;
        }

        echo Tpl::flexigridXML($data);
        exit();

    }

    private function getMemberExtension(& $member_list)
    {
        if (empty($member_list))
            return;
        $member_ids = array_keys($member_list);

        $model_dis_pay = Model('dis_bill');

        $condition = array();
        $condition['dis_member_id'] = array('in', $member_ids);

        $order_count = $model_dis_pay->getDistriBillList($condition, 'count(*) as order_count,dis_member_id', '', 'log_id desc', '', 'dis_member_id');
        foreach ($order_count as $value) {
            $member_list[$value['dis_member_id']]['order_count'] = $value['order_count'];
        }

        $field = 'sum(dis_pay_amount) as dis_pay_amount,dis_member_id';
        //已结算
        $condition['log_state'] = 1;
        $had_pay = $model_dis_pay->getDistriBillList($condition, $field, '', 'log_id desc', '', 'dis_member_id');
        foreach ($had_pay as $value) {
            $member_list[$value['dis_member_id']]['had_pay_amount'] = $value['dis_pay_amount'];
        }

        //未结算
        $condition['log_state'] = 0;
        $unpay = $model_dis_pay->getDistriBillList($condition, $field, '', 'log_id desc', '', 'dis_member_id');
        foreach ($unpay as $value) {
            $member_list[$value['dis_member_id']]['unpay_amount'] = $value['dis_pay_amount'];
        }
    }

    /**
     * csv导出
     */
    public function export_csvOp()
    {
        $model_member = Model('member');
        $condition = array();
        $limit = false;
        if ($_GET['id'] != '') {
            $id_array = explode(',', $_GET['id']);
            $condition['member_id'] = array('in', $id_array);
        }
        if ($_GET['query'] != '') {
            $condition[$_GET['qtype']] = array('like', '%' . $_GET['query'] . '%');
        }
        $order = '';
        $param = array('member_id', 'member_name', 'member_avatar', 'member_email', 'member_mobile', 'member_sex', 'member_truename', 'member_time', 'member_login_time', 'member_login_ip', 'trad_amount', 'distri_state', 'freeze_trad');
        if (in_array($_GET['sortname'], $param) && in_array($_GET['sortorder'], array('asc', 'desc'))) {
            $order = $_GET['sortname'] . ' ' . $_GET['sortorder'];
        }
        $distri_stat = 1;
        if ($_REQUEST['mem_state'] == 2) {
            $condition['distri_state'] = array('in',array(2,4,5));
            $distri_stat = $_REQUEST['mem_state'];
        } else {
            $condition['distri_state'] = array('gt', 0);
        }

        if (!is_numeric($_GET['curpage'])) {
            $count = $model_member->getMemberCount($condition);
            if ($count > self::EXPORT_SIZE) {   //显示下载链接
                $array = array();
                $page = ceil($count / self::EXPORT_SIZE);
                for ($i = 1; $i <= $page; $i++) {
                    $limit1 = ($i - 1) * self::EXPORT_SIZE + 1;
                    $limit2 = $i * self::EXPORT_SIZE > $count ? $count : $i * self::EXPORT_SIZE;
                    $array[$i] = $limit1 . ' ~ ' . $limit2;
                }
                Tpl::output('list', $array);
                Tpl::output('murl', 'index.php?con=member&fun=index');
                Tpl::showpage('export.excel');
                exit();
            }
        } else {
            $limit1 = ($_GET['curpage'] - 1) * self::EXPORT_SIZE;
            $limit2 = self::EXPORT_SIZE;
            $limit = $limit1 . ',' . $limit2;
        }

        $member_list_tmp = $model_member->getMemberList($condition, '*', null, $order, $limit);
        $member_list = array();
        foreach ($member_list_tmp as $value) {
            $member_list[$value['member_id']] = $value;
        }

        $this->getMemberExtension($member_list);

        $this->createCsv($member_list,$distri_stat);
    }

    /**
     * 生成csv文件
     */
    private function createCsv($member_list,$distri_stat)
    {
        $data = array();
        foreach ($member_list as $value) {
            $param = array();
            $param['member_id'] = $value['member_id'];
            $param['member_name'] = $value['member_name'];
            if ($distri_stat == 1) {
                $param['distri_stat'] = str_replace(array('1', '2', '3', '4', '5'), array('待审核', '已通过', '未通过', '清退', '退出'), $value['distri_state']);
                $param['member_mobile'] = $value['member_mobile'];
                $param['member_email'] = $value['member_email'];
                $param['distri_time'] = $value['distri_time'] ? date('Y-m-d', $value['distri_time']) : '';
                $param['distri_handle_time'] = ($value['distri_handle_time'] && $value['distri_state'] == 2) ? date('Y-m-d', $value['distri_handle_time']) : '';
            } else {
                $param['member_email'] = $value['member_email'];
                $param['member_mobile'] = $value['member_mobile'];
                $param['order_count'] = $value['order_count'] ? $value['order_count'] : 0;
                $param['had_pay_amount'] = ncPriceFormat($value['had_pay_amount']);
                $param['unpay_amount'] = ncPriceFormat($value['unpay_amount']);
                $param['distri_amount'] = ncPriceFormat($value['had_pay_amount'] + $value['unpay_amount']);
            }
            $data[$value['member_id']] = $param;
        }
        $header = array();
        if($distri_stat == 1){
            $header = array('member_id' => '会员ID', 'member_name' => '会员名称', 'distri_stat' => '申请状态', 'member_email' => '会员邮箱', 'member_mobile' => '会员手机', 'distri_time' => '申请时间', 'distri_handle_time' => '通过时间');
        }else{
            $header = array('member_id' => '会员ID', 'member_name' => '会员名称', 'member_email' => '会员邮箱', 'member_mobile' => '会员手机', 'order_count' => '分销单数', 'had_pay_amount' => '已结佣金(元)', 'unpay_amount' => '未结佣金(元)', 'distri_amount' => '分销总额(元)');
        }

        \shopec\Lib::exporter()->output('member_list' . $_GET['curpage'] . '-' . date('Y-m-d'), $data, $header);
    }
}