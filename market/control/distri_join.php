<?php
/**
 * 分销认证
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

class distri_joinControl extends BaseDistributeControl{

    protected $member;
    function __construct(){
        parent::__construct();
        $this->checkLogin();
        Tpl::setLayout('distri_joinin_layout');
        $model_member = Model('member');
        $member_info = $model_member->getMemberInfoByID($_SESSION['member_id']);
        Tpl::output('member_info',$member_info);
        if(!empty($member_info) && $member_info['distri_state'] == 2) {
            @header('location: '.urlDistribute('distri_center','home'));
        }
        if(!empty($member_info) && in_array($member_info['distri_state'],array('1','3')) && $_GET['fun'] != 'step2') {
            if(!isset($_REQUEST['reup']) || $_REQUEST['reup'] != 'ok'){
                @header('location: '.urlDistribute('distri_join','step2'));
            }
        }
        $this->member = $member_info;
        $phone_array = explode(',',C('site_phone'));
        Tpl::output('phone_array',$phone_array);
    }

    public function indexOp(){
        $this->step0Op();
    }

    public function step0Op(){
        $model_document = Model('document');
        $document_info = $model_document->getOneByCode('distribute_auth');
        Tpl::output('agreement', $document_info['doc_content']);
        Tpl::output('step', '0');
        Tpl::output('sub_step', 'step0');
        Tpl::showpage('distri_join_apply');
    }

    public function step1Op(){
        Tpl::output('step', '1');
        Tpl::output('sub_step', 'step1');
        Tpl::showpage('distri_join_apply');
    }

    public function step2Op(){
        if(!empty($_POST)){
            $member_id = intval($_SESSION['member_id']);

            $param = array();
            $param['bill_user_name'] = trim($_POST['bill_user_name']);
            $param['bill_type_number'] = trim($_POST['bill_type_number']);
            $param['bill_type_code'] = trim($_POST['bill_type_code']);
            $param['distri_state'] = 2;
            $param['distri_time'] = time();
            $param['distri_apply_times'] = array('exp','distri_apply_times+1');
            if(C('distribute_check')){
                $param['distri_state'] = 1;
            }
            if($param['bill_type_code'] == 'bank'){
                $param['bill_bank_name'] = trim($_POST['bill_bank_name']);
            }else{
                $param['bill_bank_name'] = str_replace(array('alipay'),array('支付宝'),trim($_POST['bill_type_code']));
            }

            $this->step2_save_valid($param);

            $model_member = Model('member');
            $joinin_info = $model_member->editMember(array('member_id' => $member_id),$param);
            if(!$joinin_info){
                showMessage('提交失败','index.php?con=distri_join&fun=step1');
            }
            @header('location: '.urlDistribute('distri_join','step2'));
        }
        $msg = "申请已提交，请耐心等待平台审核";
        if($this->member['distri_state'] == 3){
            $msg = "您的申请未通过审核，请<a href=\"".urlDistribute('distri_join','index',array('reup'=>'ok'))."\">重新申请</a>";
        }
        Tpl::output('msg',$msg);
        Tpl::output('step', '2');
        Tpl::output('sub_step', 'step2');
        Tpl::showpage('distri_join_apply');
    }
    private function step2_save_valid($param) {
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
}