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

class managerControl extends SystemControl{
    const EXPORT_SIZE = 1000;

    private $_links = array(
        array('url'=>'con=manager&fun=manager','text'=>'地区管理人'),
        array('url'=>'con=manager&fun=manager_apply','text'=>'待审核管理人')
    );

    public function __construct(){
        parent::__construct();
        Language::read('store,store_grade');
    }

    public function indexOp() {
        $this->managerOp();
    }

    /**
     * 地区管理人列表
     */
    public function managerOp(){
        //输出子菜单
        Tpl::output('top_link',$this->sublink($this->_links,'manager'));

        Tpl::showpage('manager.index');
    }


    /**
     * 输出XML数据
     */
    public function get_xmlOp() {
        $model_manager = Model('manager');
        // 设置页码参数名称
        $condition = array();
        $condition['apply_state'] = array('eq',30);
        if ($_GET['complete_company_name'] != '') {
            $condition['complete_company_name'] = array('like', '%' . $_GET['complete_company_name'] . '%');
        }
        if ($_GET['legal_person_name'] != '') {
            $condition['legal_person_name'] = array('like', '%' . $_GET['legal_person_name'] . '%');
        }
        if ($_GET['member_id'] != '') {
            $condition['member_id'] = array('like', '%' . $_GET['member_id'] . '%');
        }
        if ($_GET['company_phone'] != '') {
            $condition['company_phone'] = array('like', '%' . $_GET['company_phone'] . '%');
        }
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        $order = '';
        $param = array('member_id','manager_account','complete_company_name','company_phone','legal_person_name','id_number','contacts_name','contacts_phone','contacts_email','area_info','company_address');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
                $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
        $page = $_POST['rp'];
        //管理人列表
        $store_list = $model_manager->getManagerInfoList($condition, $page, $order);
        $data = array();
        $data['now_page'] = $model_manager->shownowpage();
        $data['total_num'] = $model_manager->gettotalnum();
        foreach ($store_list as $value) {

            if($value['apply_state']==30){
                $param = array();
            $operation = "<a class='btn green' href='index.php?con=manager&fun=manager_detail&manager_id=".$value['manager_id']."'><i class='fa fa-list-alt'></i>查看</a><span class='btn'><em><i class='fa fa-cog'></i>" . L('nc_set') . " <i class='arrow'></i></em><ul><li><a href='index.php?con=manager&fun=manager_edit&member_id=" . $value['member_id'] . "'>编辑管理人信息</a></li>";
                $operation .= "</ul></span>";
            $param['operation'] = $operation;
            $param['member_id'] = $value['member_id'];

            $param['complete_company_name'] = $value['complete_company_name'];
            $param['company_phone'] = $value['company_phone'];
            $param['legal_person_name'] = $value['legal_person_name'];

            $param['id_number'] = _decrypt($value['id_number']);

            $param['contacts_name'] = $value['contacts_name'];
            $param['contacts_phone'] = $value['contacts_phone'];
            $param['contacts_email'] = $value['contacts_email'];
            $param['company_address'] = $value['company_address'];
            $param['company_address_detail'] = $value['company_address_detail'];
            $data['list'][$value['manager_id']] = $param;
        }
        }

        echo Tpl::flexigridXML($data);exit();
    }

    

    /**
     * csv导出管理人
     */
    public function export_csvOp() {
        $model_store = Model('manager');
        $condition['apply_state'] = array('eq',30);
        $limit = false;
        if ($_GET['member_id'] != '') {
            $condition['member_id'] = array('like', '%' . $_GET['member_id'] . '%');
        }
        if ($_GET['complete_company_name'] != '') {
            $condition['complete_company_name'] = array('like', '%' . $_GET['complete_company_name'] . '%');
        }
        if ($_GET['company_phone'] != '') {
            $condition['company_phone'] = array('like', '%' . $_GET['company_phone'] . '%');
        }
        if ($_GET['legal_person_name'] != '') {
            $condition['legal_person_name'] = array('like', '%' . $_GET['legal_person_name'] . '%');
        }
        if ($_GET['id_number'] != '') {
            $condition['id_number'] = array('like', '%' . $_GET['id_number'] . '%');
        }
        if ($_REQUEST['query'] != '') {
            $condition[$_REQUEST['qtype']] = array('like', '%' . $_REQUEST['query'] . '%');
        }

        $order = '';
        $param = array('manager_id','member_id','complete_company_name','company_phone','legal_person_name','id_number','contacts_name','contacts_phone','contacts_email','area_info','company_address');
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
                Tpl::output('murl','index.php?con=manager&fun=index');
                Tpl::showpage('export.excel');
                exit();
            }
        } else {
            $limit1 = ($_GET['curpage']-1) * self::EXPORT_SIZE;
            $limit2 = self::EXPORT_SIZE;
            $limit = $limit1 .','. $limit2;
        }
        $manager_list = $model_store->getManagerList($condition, null, 'manager_id desc', '*', $limit);
        $this->createCsv($manager_list);
    }
    /**
     * 生成管理人csv文件
     */
    private function createCsv($manager_list) {

        $data = array();
        foreach ($manager_list as $value) {
            $param = array();
            $param['member_id'] = $value['member_id'];
            $param['complete_company_name'] = $value['complete_company_name'];
            $param['company_phone'] = $value['company_phone'];
            $param['legal_person_name'] = $value['legal_person_name'];
            $param['id_number'] = _decrypt($value['id_number']);
            $param['contacts_name'] = $value['contacts_name'];
            $param['contacts_phone'] = $value['contacts_phone'];
            $param['contacts_email'] = $value['contacts_email'];
            $param['company_address'] = $value['company_address'];
            $param['company_address_detail'] = $value['company_address_detail'];
            $data[$value['manager_id']] = $param;
       }

        $header = array(
                'member_id' => '管理人ID',
                'complete_company_name' => '公司名称',
                'company_phone' => '公司电话',
                'legal_person_name' => '法人姓名',
                'id_number' => '法人身份证号',
                'contacts_name' => '联系人姓名',
                'contacts_phone' => '联系人电话',
                'contacts_email' => '电子邮箱',
                'company_address' => '所在地区',
                'company_address_detail' => '详细地址'
        );
        \shopec\Lib::exporter()->output('地区管理人列表' .$_GET['curpage'] . '-'.date('Y-m-d'), $data, $header);
    }


    /**
     * 管理人申请信息编辑显示页
     */
    public function manager_editOp(){
        $lang = Language::getLangContent();

        $model_manager = Model('manager');
        //取管理人信息
        $manager_array = $model_manager->getManagerInfoByID($_GET['member_id']);
        //判断地区管理人是否存在
        if (empty($manager_array)){
            showMessage($lang['该地区管理人不存在']);
        }
        $manager_array['identity_card_electronic'] = explode('|',$manager_array['identity_card_electronic']);

        Tpl::output('manager_array',$manager_array);

        Tpl::showpage('manager_edit');
    }

    /**
     * 图片上传
     */
    public function ajax_upload_imageOp() {

        $pic_name = '';
        $upload = new UploadFile();
        $file = current($_FILES);
        $uploaddir = ATTACH_PATH.DS.'store_joinin'.DS;
        $upload->set('max_size',C('image_max_filesize'));
        $upload->set('default_dir',$uploaddir);
        $upload->set('allow_type',array('jpg','jpeg','gif','png'));
        if (!empty($file['tmp_name'])){
            $result = $upload->upfile(key($_FILES));
            if ($result){
                echo json_encode(array('state'=>true,'pic_name'=>$upload->file_name,'pic_url'=>UPLOAD_SITE_URL.DS.ATTACH_PATH.DS.'store_joinin'.DS.$upload->file_name));
            } else {
                echo json_encode(array('state'=>false,'message'=>$upload->error));
            }
        }
    }

    /**
     * 编辑保存管理人注册信息
     */
    public function edit_save_managerOp() {

        if (chksubmit()) {
            $member_id = $_POST['member_id'];
            if ($member_id <= 0) {
                showMessage(L('param_error'));
            }
            $param = array();
            if($_POST['form_mark']==1){
                $param['member_name'] = $_POST['member_name'];
                $param['member_passwd'] = $_POST['member_password'];
                $new_password= $_POST['new_password'];
                if($param['member_passwd']==null & $new_password ==null){
                        showMessage('未修改密码', 'index.php?con=manager&fun=index');

                }elseif($param['member_passwd']!=null || $new_password !=null){
                    if($param['member_passwd']=== $new_password){
                        $param['member_passwd'] = md5($_POST['member_password']);
                        $result = Model('manager')->editManagerPasswprd(array('member_id' => $member_id), $param);
                        if ($result) {
                            $this->log("管理人ID为 :". $member_id." 的地区管理人登录密码已重置");
                            showMessage(L('nc_common_op_succ'), 'index.php?con=manager&fun=index');
                        } else {
                            showMessage(L('nc_common_op_fail'));
                        }

                    }else{
                        showMessage(L('两次输入密码不一致'));
                    }

                }


            }

            if($_POST['form_mark']==2){
                $param['complete_company_name'] = $_POST['complete_company_name'];
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
                //公司法人信息
                $param['legal_person_name'] = $_POST['legal_person_name'];
                $param['id_number'] = _encrypt($_POST['id_number']);
                //判断上传图片是否更改
                if(empty($_POST['identity_card_electronic1'])){
                    $param['identity_card_electronic'] =$_POST['identity_card_electronic2'];
                }else{
                    $param['identity_card_electronic'] = $_POST['identity_card_electronic1'];
                }


                $param['business_licence_number'] = _encrypt($_POST['business_licence_number']);
                $param['business_licence_address'] = $_POST['business_licence_address'];
                $param['business_licence_start'] = $_POST['business_licence_start'];
                $param['business_licence_end'] = $_POST['business_licence_end'];
                $param['business_sphere'] = $_POST['business_sphere'];
                if ($_FILES['business_licence_number_elc']['name'] != '') {
                    $param['business_licence_number_elc'] = $this->upload_image('business_licence_number_elc');
                }
                $param['organization_code'] = _encrypt($_POST['organization_code']);
                if ($_FILES['organization_code_electronic']['name'] != '') {
                    $param['organization_code_electronic'] = $this->upload_image('organization_code_electronic');
                }
                if ($_FILES['general_taxpayer']['name'] != '') {
                    $param['general_taxpayer'] = $this->upload_image('general_taxpayer');
                }
                $param['bank_account_name'] = $_POST['bank_account_name'];
                $param['bank_account_number'] = _encrypt($_POST['bank_account_number']);
                $param['bank_name'] = $_POST['bank_name'];
                $param['bank_code'] = _encrypt($_POST['bank_code']);
                $param['bank_address'] = $_POST['bank_address'];
                if ($_FILES['bank_licence_electronic']['name'] != '') {
                    $param['bank_licence_electronic'] = $this->upload_image('bank_licence_electronic');
                }
                $param['settlement_bank_account_name'] = $_POST['settlement_bank_account_name'];
                $param['settlement_bank_account_number'] = _encrypt($_POST['settlement_bank_account_number']);
                $param['settlement_bank_name'] = $_POST['settlement_bank_name'];
                $param['settlement_bank_code'] = _encrypt($_POST['settlement_bank_code']);
                $param['settlement_bank_address'] = $_POST['settlement_bank_address'];
                $param['tax_registration_certificate'] = _encrypt($_POST['tax_registration_certificate']);
                $param['taxpayer_id'] =_encrypt($_POST['taxpayer_id']);
                if ($_FILES['tax_registration_certif_elc']['name'] != '') {
                    $param['tax_registration_certif_elc'] = $this->upload_image('tax_registration_certif_elc');
                }
                $result = Model('manager')->editManagerInfo(array('member_id' => $member_id), $param);
                if ($result) {
                    $this->log("管理人ID为 :". $member_id." 的地区管理人审核信息编辑成功");
                    showMessage(L('nc_common_op_succ'), 'index.php?con=manager&fun=manager');

                } else {
                    showMessage(L('nc_common_op_fail'));
                }

            }



        }
    }

    /**
     * 保存单文件上传
     * @param $file
     * @return string
     */
    private function upload_image($file) {
        $pic_name = '';
        $upload = new UploadFile();
        //文件上传保存路径
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
     * 管理人 待审核列表
     */
    public function manager_applyOp(){
        //输出子菜单
        Tpl::output('top_link',$this->sublink($this->_links,'manager_apply'));

        Tpl::showpage('manager_apply');
    }

    /**
     * 输出XML数据
     * 申请状态 10-已提交申请  20-审核成功 30-审核失败  40-审核通过
     */
    public function get_manager_xmlOp() {
        $model_manager_joinin = Model('manager');
        // 设置页码参数名称
        $condition = array();
        $condition['apply_state'] = array('not in',array(10,30));
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        $order = '';
        $param = array('manager_id','member_id','complete_company_name','company_phone','apply_state','legal_person_name','id_number','contacts_name','contacts_phone','contacts_email','area_info','company_address');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }

        $page = $_POST['rp'];

        //获取管理人列表
        $store_list = $model_manager_joinin->getManagerList($condition, $page, $order);
        $data = array();
        $data['now_page'] = $model_manager_joinin->shownowpage();
        $data['total_num'] = $model_manager_joinin->gettotalnum();
        foreach ($store_list as $value) {
            //判断是否审核通过
            if(in_array(intval($value['apply_state']), array(20,40))){
                $param = array();
                if(in_array(intval($value['apply_state']), array(20))) {
                    $operation = "<a class='btn orange' href=\"index.php?con=manager&fun=manager_detail&manager_id=". $value['manager_id'] ."\"><i class=\"fa fa-check-circle\"></i>审核</a>";
                } else {
                    $operation = "<a class='btn green' href=\"index.php?con=manager&fun=manager_detail&manager_id=". $value['manager_id'] ."\"><i class=\"fa fa-list-alt\"></i>查看</a>";
                }
                $param['operation'] = $operation;
                $param['member_id'] = $value['member_id'];
//                $param['manager_account'] = $value['manager_account'];;
                $param['complete_company_name'] = $value['complete_company_name'];
                $param['company_phone'] = $value['company_phone'];
                if($value['apply_state']==20){
                    $param['apply_state'] = '提交申请';
                }
                if($value['apply_state'] == 40){
                    $param['apply_state'] = '申请失败';
                }

                $param['company_phone'] = $value['company_phone'];
                $param['legal_person_name'] = $value['legal_person_name'];

                $param['id_number'] = _decrypt($value['id_number']);

                $param['contacts_name'] = $value['contacts_name'];
                $param['contacts_phone'] = $value['contacts_phone'];
                $param['contacts_email'] = $value['contacts_email'];
                $param['company_address'] = $value['company_address'];
                $param['company_address_detail'] = $value['company_address_detail'];
                $data['list'][$value['manager_id']] = $param;
            }

        }
        echo Tpl::flexigridXML($data);exit();
    }



    /**
     * 管理人审核显示页
     */
    public function manager_detailOp(){
        $model_manager = Model('manager');
        $manager_detail = $model_manager->getOne(array('manager_id'=>$_GET['manager_id']));
        $manager_detail_title = '查看';
        if(in_array(intval($manager_detail['apply_state']), array(20, 40))) {
            $manager_detail_title = '审核';
        }

        $manager_detail['identity_card_electronic'] = explode('|', $manager_detail['identity_card_electronic']);
        Tpl::output('joinin_detail_title', $manager_detail_title);
        Tpl::output('manager_detail',  $manager_detail);
        Tpl::showpage('manager.detail');
    }

    /**
     * 管理人审核
     */
    public function store_joinin_verifyOp() {
        $model_manager = Model('manager');
//        $manager_state = $model_manager->getOne(array('manager_id'=>$_POST['manager_id']));
        $manager_id = $_POST['manager_id'];
        $verify_type = $_POST['verify_type'];
        $param = array();
        if($verify_type == 30){
            $param['apply_state']=30;
            $param['apply_message'] = "";
            $param['manager_login_state'] = 1;
            $this->log("管理人ID为 :".$manager_id." 的地区管理人审核通过");
        }
        if($verify_type == 40){
            $param['apply_state']=40;
            $param['apply_message']=$_POST['apply_message'];
            $this->log("管理人ID为 :".$manager_id." 的地区管理人申请失败");
        }

        $result = $model_manager->editManagerInfo(array('manager_id' => $manager_id), $param);
        if ($result) {
            showMessage('提交成功', 'index.php?con=manager&fun=store_joinin');
        } else {
            showMessage('提交失败');
        }

    }



}
