<?php
/**
 * 区域管理员申请
 *
 *by  suijiaolong
 */
use shopec\Tpl;


defined('Inshopec') or exit('Access Invalid!');

class manager_registerControl extends ManagerControl {
    /**
     * manager_registerControl constructor.
     */
    public function __construct() {
        parent::__construct();

        //布局模板 商家入驻申请等```````
        Tpl::setLayout('manager_joinin_layout');

        $this->checkLoginManager();//检查是否登录

        //平台联系电话
        $phone_array = explode(',',C('site_phone'));
        Tpl::output('phone_array',$phone_array);
        //查询状态  然后显示
        if($_GET['fun']!='next'&&$_GET['fun']!='manager_state'){
            $this->check_joinin_state();
        }

    }

    /**
     * 查看申请状态
     */
    private function check_joinin_state() {
        $model_manager = Model('manager_index');
        $state = $model_manager->getMemberInfo(array('member_id'=>$_SESSION['manager_id']),'apply_state,apply_message');
        if(!empty($state)) {

            switch (intval($state['apply_state'])) {
                case 20:
                    $this->show_join_message('申请已经提交，请等待核对', FALSE, '4');
                    break;
                case 30:
                    @redirect(urlMember('manager_index','index'));
                    break;
                case 40:
                    $this->show_join_message('审核失败:'.$state['apply_message'], SHOP_SITE_URL.DS.'index.php?con=manager_register&fun=step0');
                    break;
            }
        }
    }

    /**
     * 申请第一步显示
     */
    public function indexOp() {

        $this->step0Op();
    }

    /**
     * 申请第一步显示
     */
    public function step0Op() {
        self::getStoreInfo();//回显
        Tpl::output('step', '0');
        Tpl::output('sub_step', 'step0');
        Tpl::showpage('manager_register');
        exit;
    }

    /**
     * 第一步提交  第二步的显示
     */
    public function step1Op() {
        self::getStoreInfo();
        if(!empty($_POST)) {
            $param = array();
            $condition = array();
            $condition['member_id'] = $_SESSION['manager_id'];
            $param['complete_company_name'] = $_POST['company_name'];
            $param['company_province_id'] = intval($_POST['province_id']);
            $param['company_address'] = $_POST['company_address'];
            $param['company_address_detail'] = $_POST['company_address_detail'];
            $param['company_phone'] = $_POST['company_phone'];
            $param['company_employee_count'] = intval($_POST['company_employee_count']);
            $param['company_registered_capital'] = intval($_POST['company_registered_capital']);
            $param['contacts_name'] = $_POST['contacts_name'];
            $param['contacts_phone'] = $_POST['contacts_phone'];
            $param['contacts_email'] = $_POST['contacts_email'];
            $param['legal_person_name'] = $_POST['legal_person_name'];
            $param['id_number'] = _encrypt($_POST['id_number']);
            $param['identity_card_electronic'] = $_POST['identity_card_electronic1'];
            $param['business_licence_number'] = _encrypt($_POST['business_licence_number']);
            $param['business_licence_address'] = $_POST['business_licence_address'];
            $param['business_licence_start'] = $_POST['business_licence_start'];
            $param['business_licence_end'] = $_POST['business_licence_end'];
            $param['business_sphere'] = $_POST['business_sphere'];
            $param['business_licence_number_elc'] = $_POST['business_licence_number_elc1'];
            $param['organization_code'] = _encrypt($_POST['organization_code']);
            $param['organization_code_electronic'] = $_POST['organization_code_electronic1'];
            $param['general_taxpayer'] = $_POST['general_taxpayer1'];

            //$this->step1_save_valid($param);  后台验证
            $model_manager = Model('manager_index');
            $update = $model_manager->editmanager($condition,$param);
            if(!$update){
                showMessage('申请失败````','','','error');
            }
        }
        Tpl::output('step', '1');
        Tpl::output('sub_step', 'step1');
        Tpl::showpage('manager_register');
        exit;
    }


    /*后台验证*/
    /*private function step1_save_valid($param) {
        $obj_validate = new Validate();
        $obj_validate->validateparam = array(
            array("input"=>$param['company_name'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>"公司名称不能为空且必须小于50个字"),
            array("input"=>$param['company_address'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>"公司地址不能为空且必须小于50个字"),
            array("input"=>$param['company_address_detail'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>"公司详细地址不能为空且必须小于50个字"),
            array("input"=>$param['company_phone'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"20","message"=>"公司电话不能为空"),
            array("input"=>$param['company_employee_count'], "require"=>"true","validator"=>"Number","员工总数不能为空且必须是数字"),
            array("input"=>$param['company_registered_capital'], "require"=>"true","validator"=>"Number","注册资金不能为空且必须是数字"),
            array("input"=>$param['contacts_name'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"20","message"=>"联系人姓名不能为空且必须小于20个字"),
            array("input"=>$param['contacts_phone'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"20","message"=>"联系人电话不能为空"),
            array("input"=>$param['contacts_email'], "require"=>"true","validator"=>"email","message"=>"电子邮箱不能为空"),

            array("input"=>$param['legal_person_name'], "require"=>"true","message"=>"公司法人姓名不能为空"),
            array("input"=>$param['id_number'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"18","message"=>"公司法人身份证号不能为空"),
            array("input"=>$param['identity_card_electronic'],"require"=>"true", "message"=>"公司法人身份证电子版不能为空"),
            array("input"=>$param['business_licence_number'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"20","message"=>"营业执照号不能为空且必须小于20个字"),
            array("input"=>$param['business_licence_address'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>"营业执照所在地不能为空且必须小于50个字"),
            array("input"=>$param['business_licence_start'], "require"=>"true","message"=>"营业执照有效期不能为空"),
            array("input"=>$param['business_licence_end'], "require"=>"true","message"=>"营业执照有效期不能为空"),
            array("input"=>$param['business_sphere'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"500","message"=>"法定经营范围不能为空且必须小于50个字"),
            array("input"=>$param['business_licence_number_elc'], "require"=>"true","message"=>"营业执照电子版不能为空"),
            array("input"=>$param['organization_code'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"20","message"=>"组织机构代码不能为空且必须小于20个字"),
            array("input"=>$param['organization_code_electronic'], "require"=>"true","message"=>"组织机构代码电子版不能为空"),
        );
       $error = $obj_validate->validate();
        if ($error != ''){
            showMessage($error);
        }
    }*/
    /**
     * 管理员申请 第二步
     * @throws Exception
     */
    public function step2Op() {
        self::getStoreInfo();
        if(!empty($_POST)) {
            $param = array();
            $condition = array();
            $condition['member_id'] = $_SESSION['manager_id'];
            $param['bank_account_name'] = $_POST['bank_account_name'];
            $param['bank_account_number'] = _encrypt($_POST['bank_account_number']);
            $param['bank_name'] = $_POST['bank_name'];
            $param['bank_code'] = _encrypt($_POST['bank_code']);
            $param['bank_address'] = $_POST['bank_address'];
            $param['bank_licence_electronic'] = $_POST['bank_licence_electronic1'];
            if(!empty($_POST['is_settlement_account'])) {
                $param['is_settlement_account'] = 1;
                $param['settlement_bank_account_name'] = $_POST['bank_account_name'];
                $param['settlement_bank_account_number'] = _encrypt($_POST['bank_account_number']);
                $param['settlement_bank_name'] = $_POST['bank_name'];
                $param['settlement_bank_code'] = _encrypt($_POST['bank_code']);
                $param['settlement_bank_address'] = $_POST['bank_address'];
            } else {
                $param['is_settlement_account'] = 2;
                $param['settlement_bank_account_name'] = $_POST['settlement_bank_account_name'];
                $param['settlement_bank_account_number'] = _encrypt($_POST['settlement_bank_account_number']);
                $param['settlement_bank_name'] = $_POST['settlement_bank_name'];
                $param['settlement_bank_code'] = _encrypt($_POST['settlement_bank_code']);
                $param['settlement_bank_address'] = $_POST['settlement_bank_address'];

            }

            $param['tax_registration_certificate'] = _encrypt($_POST['tax_registration_certificate']);
            $param['taxpayer_id'] = _encrypt($_POST['taxpayer_id']);
            $param['tax_registration_certif_elc'] = $_POST['tax_registration_certif_elc1'];
            $param['apply_state'] = 20;
            //$this->step2_save_valid($param); 后台验证
            $model_manager = Model('manager_index');
            $update = $model_manager->editmanager($condition,$param);
            if(!$update){
                showMessage('申请失败````','','','error');
            }
        }
        @header('location: index.php?con=manager_register');
        exit;
    }





    /*后台验证*/
    /*private function step2_save_valid($param) {
        $obj_validate = new Validate();
        $obj_validate->validateparam = array(
            array("input"=>$param['bank_account_name'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>"银行开户名不能为空且必须小于50个字"),
            array("input"=>$param['bank_account_number'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"40","message"=>"银行账号不能为空且必须小于40个字"),
            array("input"=>$param['bank_name'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>"开户银行支行名称不能为空且必须小于50个字"),
            array("input"=>$param['bank_code'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"20","message"=>"支行联行号不能为空且必须小于20个字"),
            array("input"=>$param['bank_address'], "require"=>"true","开户行所在地不能为空"),
            array("input"=>$param['bank_licence_electronic'], "require"=>"true","开户银行许可证电子版不能为空"),
            array("input"=>$param['settlement_bank_account_name'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>"银行开户名不能为空且必须小于50个字"),
            array("input"=>$param['settlement_bank_account_number'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"20","message"=>"银行账号不能为空且必须小于20个字"),
            array("input"=>$param['settlement_bank_name'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>"开户银行支行名称不能为空且必须小于50个字"),
            array("input"=>$param['settlement_bank_code'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"20","message"=>"支行联行号不能为空且必须小于20个字"),
            array("input"=>$param['settlement_bank_address'], "require"=>"true","开户行所在地不能为空"),
            array("input"=>$param['tax_registration_certificate'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"20","message"=>"税务登记证号不能为空且必须小于20个字"),
            array("input"=>$param['taxpayer_id'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"20","message"=>"纳税人识别号"),
            array("input"=>$param['tax_registration_certif_elc'], "require"=>"true","message"=>"税务登记证号电子版不能为空"),
        );
        $error = $obj_validate->validate();
        if ($error != ''){
            showMessage($error);
        }
    }*/


    /**
     * 显示提示信息
     * @param $message  提示信息
     * @param bool|FALSE $btn_next  审核失败  点击下一步的跳转地址
     * @param string $step  左侧和步骤的选择
     */
    private function show_join_message($message, $btn_next = FALSE, $step = '') {
        Tpl::output('joinin_message', $message);
        Tpl::output('btn_next', $btn_next);
        Tpl::output('step',4);
        Tpl::output('sub_step', 'step4');
        Tpl::showpage('manager_register');
        exit;
    }

    /**
     *审核失败后 点击下一步 按钮 ajax事件
     */
    public function  nextOp(){
        $model_manager = Model('manager_index');
        $param['apply_state'] = 10;
        $update = $model_manager->editmanager(array('member_id'=>$_SESSION['manager_id']),$param);
         if($update){
             exit(json_encode(array('state'=>true,'info'=>'修改成功')));
         }else{
             exit(json_encode(array('state'=>false,'info'=>'修改失败')));
         }

    }

    /**
     * 点击返回管理员页面 判断状态 [已经取消]
     */
    public function  manager_stateOp(){
        $model_manager = Model('manager_index');
        $update = $model_manager->getMemberInfo(array('member_id'=>$_SESSION['manager_id']),'manager_login_state');
        if($update['manager_login_state'] == 1){
            exit(json_encode(array('state'=>true)));
        }else{
            exit(json_encode(array('state'=>false,'info'=>'您还未通过首次审核')));
        }
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
     * 获取管理员申请信息
     * by gongbo
     * @param
     * @return
     */
	private function getStoreInfo(){
        $model_manager_joinin = Model('manager_index');
        $rs = $model_manager_joinin->getMemberInfo(array('member_id'=>$_SESSION['manager_id']));
        Tpl::output('pic_url',UPLOAD_SITE_URL.DS.ATTACH_PATH.DS.'store_joinin'.DS);
        Tpl::output('store_info', $rs);
    }

    //删除图片的方法
    public function delateimgOp(){

        $message =array();
        $img_url = trim($_GET['img_url']);
        $field = trim($_GET['img_field']);
        $arr =parse_url($img_url);
        $path = $arr['path'];
        $new_arr = explode('/',$path);
        $img_name = $new_arr[count($new_arr)-1];
        $file_url = BASE_UPLOAD_PATH.'/shop/store_joinin/'.$img_name;
        //删除数据库图片名
        if($field != "" && $img_name != ""){
            $model_store_joinin = Model('manager_register');
            $condition['member_id'] = $_SESSION['manager_id'];
            $rs = $model_store_joinin ->where($condition)->field($field)->find();
            if($rs && !empty($rs[$field])){
                $img_arr = explode("|",$rs[$field]);
                foreach( $img_arr as $k=>$v) {
                    if($img_name == $v) unset($img_arr[$k]);
                }
                //$key=array_search($img_name ,$img_arr);
                //array_splice($img_arr,$key,1);
                $data[$field] = implode("|",$img_arr);
                if($model_store_joinin->where($condition)->update($data)){
                    //删除图片文件
                    @unlink($file_url);
                    $message['img_name'] = $img_name;
                    $message['status'] = 1;
                    echo json_encode($message);
                }else{
                    echo 0;
                }
            }else{
                @unlink($file_url);
                $message['img_name'] = $img_name;
                $message['status'] = 1;
                echo json_encode($message);
            }
        }else{
            @unlink($file_url);
            $message['img_name'] = $img_name;
            $message['status'] = 1;
            echo json_encode($message);
        }

    }

}
