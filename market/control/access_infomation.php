<?php
/**
 * 分销账号管理
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

class access_infomationControl extends MemberDistributeControl{
    /**
     * 账户设置
     */
    public function indexOp(){
        $this->memberOp();
    }

    /**
     * 账户设置
     */
    public function memberOp(){
        $member_model = Model('member');
        $field = 'member_id,member_name,member_truename,member_avatar,member_sex,member_email,member_email_bind,member_mobile,member_mobile_bind,bill_user_name,bill_type_code,bill_type_number,bill_bank_name,trad_amount';
        $member_info = $member_model->getMemberInfoByID($_SESSION['member_id'],$field);

        self::profile_menu('log','access_infomation');
        Tpl::output('member_info',$member_info);
        Tpl::showpage('access_infomation');
    }

    /**
     * 保存账户修改信息
     */
    public function save_memberOp(){
        if(chksubmit()){
            $param = array();
            $param['bill_user_name'] = trim($_POST['bill_user_name']);
            $param['bill_type_number'] = trim($_POST['bill_type_number']);
            $param['bill_type_code'] = trim($_POST['bill_type_code']);
            if($param['bill_type_code'] == 'bank'){
                $param['bill_bank_name'] = trim($_POST['bill_bank_name']);
            }
            $this->save_member_valid($param);

            $model_member = Model('member');
            $member_info = $model_member->editMember(array('member_id' => $_SESSION['member_id']),$param);
            if(!$member_info){
                showMessage('账户信息更新失败','index.php?con=access_infomation','','error');
            }else{
                showMessage('账户信息更新成功','index.php?con=access_infomation','','succ');
            }
        }
    }

    private function save_member_valid($param) {
        $obj_validate = new Validate();
        $obj_validate->validateparam = array(
            array("input"=>$param['bill_user_name'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>"收款人姓名不能为空且必须小于50个字"),
            array("input"=>$param['bill_type_number'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"20","message"=>"收款账号不能为空且必须小于20个字"),
        );
        if($param['bill_type_code'] == 'bank'){
            $obj_validate->validateparam[] = array("input"=>$param['bill_bank_name'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>"开户银行名称不能为空且必须小于50个字");
        }
        $error = $obj_validate->validate();
        if ($error != ''){
            showMessage($error);
        }
    }

    /**
     * 退出分销
     */
    public function distri_quitOp(){
        if($this->member_info['distri_state'] != 2){
            showMessage('您已退出分销或被清退','index.php?con=access_infomation');
        }
        self::profile_menu('distir_quit','distir_quit');
        Tpl::showpage('distri_quit');
    }

    /**
     * 保存退出分销
     */
    public function save_quitOp(){
        $member_model = Model('member');
        $param = array();
        $param['distri_state'] = 4;
        $param['distri_code'] = '';
        $param['quit_time'] = time();
        $param['distri_quit_times'] = array('exp','distri_quit_times+1');
        $condition = array();
        $condition['member_id'] = $_SESSION['member_id'];
        $stat = $member_model->editMember($condition, $param);
        if ($stat) {
            $member_info = $member_model->getMemberInfo($condition);
            $dis_goods_model = Model('dis_goods');
            $dis_goods_model->delDistriGoods($condition);
            Model('dis_trad')->autoDistriTrad($member_info);
            showMessage('退出成功','index.php?con=access_infomation','','succ');
        } else {
            showMessage('退出失败','index.php?con=access_infomation','','error');
        }
    }

    /**
     * 用户中心右边，小导航
     *
     * @param string    $menu_type  导航类型
     * @param string    $menu_key   当前导航的menu_key
     * @return
     */
    private function profile_menu($menu_type,$menu_key=''){
        $menu_array = array(
            array('menu_key'=>'access_infomation',        'menu_name'=>'账户设置',    'menu_url'=>'index.php?con=access_infomation&fun=member')
        );
        if($menu_type == 'distir_quit'){
            $menu_array[] = array('menu_key'=>'distir_quit',        'menu_name'=>'退出分销',    'menu_url'=>'index.php?con=access_infomation&fun=distir_quit');
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
}