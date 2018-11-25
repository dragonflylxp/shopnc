<?php
/**
 * Created by PhpStorm.
 * User: suijiaolong
 * Date: 2016/11/24/024
 * Time: 15:40
 */
use shopec\Tpl;
defined('Inshopec') or exit('Access Invalid!');
class manager_indexControl extends ManagerControl{
    private $manager;
    private $model_member;
    private $manager_member;
    public function __construct()
    {
        if(!C('site_status')) halt(C('closed_reason'));

        Language::read('common,member_layout');

        //管理员登录验证
        $this->checkLoginManager();
        $this->model_member  = Model('manager_index');

        //查看管理员是否已经申请
        $condition['member_id'] = $_SESSION['manager_id'];
        $this->manager = $this->model_member->getMemberInfo($condition);
        $this->manager_member = $this->model_member->getManagerMember(array('member_id'=>$_SESSION['manager_id']));
        if($this->manager['manager_login_state'] != 1){
            redirect(urlShop('manager_register','index')); //跨模块跳转
        }

        //输出头部的公用信息
        Tpl::setLayout('manager_layout');

        // 左侧导航
        $menu_list = $this->_getMenuList();
        Tpl::output('menu_list', $menu_list);

        // 页面高亮
        Tpl::output('con', $_GET['con']);

        //管理人资料
        $condition_manager['uid'] = $_SESSION['manager_id'];
        $manager_member_model = Model('manager_member');
        $info =$manager_member_model->getManagerMemberInfo($condition_manager);
        $manager_info = array();

        switch($info['grade']){
            case 1:
                $manager_info['grade'] = '大区级';
                break;
            case 2:
                $manager_info['grade'] = '省级';
                break;
            case 3:
                $manager_info['grade'] = '市级';
                break;
            case 4:
                $manager_info['grade'] = '区县级';
                break;
        }
            $manager_info['area'] = '';
            $area_model = Model('manager_real_bill');

            if(!empty($info['area'])){
                $manager_info['area'] = $info['area'].'区';
            }
            if(!empty($info['province'])){
                $manager_v = $area_model->getAreaInfo($info['province']);

                $manager_info['area'].= "&nbsp".$manager_v['area_name'].'省';
            }
            if(!empty($info['city'])){
                $manager_v = $area_model->getAreaInfo($info['city']);
                $manager_info['area'].= "&nbsp".$manager_v['area_name'];
            }
            if(!empty($info['district'])){
                $manager_v = $area_model->getAreaInfo($info['district']);
                $manager_info['area'].= "&nbsp".$manager_v['area_name'];
            }
        $manager_info['company_name'] = $_SESSION['company_name'];
        //上次登录时间
        $manager_info['manager_old_login_time'] = $this->manager_member['member_old_login_time'] ;
        $manager_info['apply_state'] = $this->manager['apply_state'];
        Tpl::output('manager_info', $manager_info);

    }
    /**
     * 左侧导航
     * 菜单数组中child的下标要和其链接的act对应。否则面包屑不能正常显示
     * @return array
     */
    private function _getMenuList() {
        $menu_list = array(
            'manager_index' => array('name' => '账户资料', 'child' => array(
                'modify_pwd'=> array('name' => '密码修改', 'url'=>urlMember('manager_index', 'modify_pwd')),
                'edit_manager'=> array('name' => '管理人资料', 'url'=>urlMember('manager_index', 'edit_manager')),
            )),
            'property' => array('name' => '财产中心', 'child' => array(
                'index'           => array('name' => '实物结算单', 'url'=>urlMember('manager_index', 'index')),
                'vr_bill'        => array('name' => '虚拟结算单', 'url'=>urlMember('manager_index', 'vr_bill')),
            ))
        );
        return $menu_list;
    }

    /**
     * 主页 实物单列表
     */
    public function indexOp(){
        //中心顶部列表
        self::profile_menu('points');
        Language::read('member_member_points,member_pointorder');
        //搜素start
       $where = array();
        $where['uid'] = $_SESSION['manager_id'];
        if ($_GET['stage']){
            $where['state'] = $_GET['stage'];
        }

        if (trim($_GET['stime'])) {
            $stime = strtotime($_GET['stime']);
            $where['start_time'] = array('egt', $stime);
        }
        if (trim($_GET['etime'])) {
            $etime = strtotime($_GET['etime']) + 86400;
            $where['end_time'] = array('elt', $etime);
        }
        $where['mb_id'] = array('like',"%{$_GET['mb_id']}%");

        //搜索end
        //查询管理人收益列表
        $where['uid'] = $_SESSION['manager_id'];
        $manager_bill_model = Model('manager_bill');
        $list_manager_bill = $manager_bill_model->getManagerBillList($where);
        //查询管理区域 拼接好 传递给前台
        $area = '';
        if(sizeof($list_manager_bill)>0){
            $area_model = Model('manager_real_bill');

            if(!empty($list_manager_bill[0]['area_region'])){
                $area = $list_manager_bill[0]['area_region'];
            }
            if(!empty($list_manager_bill[0]['province_id'])){
                $v = $area_model->getAreaInfo($list_manager_bill[0]['province_id']);

                $area.= "&nbsp".$v['area_name'];
            }
            if(!empty($list_manager_bill[0]['city_id'])){
                $v = $area_model->getAreaInfo($list_manager_bill[0]['city_id']);
                $area.= "&nbsp".$v['area_name'];
            }
            if(!empty($list_manager_bill[0]['district_id'])){
                $v = $area_model->getAreaInfo($list_manager_bill[0]['district_id']);
                $area.= "&nbsp".$v['area_name'];
            }
        }

        Tpl::output('show_page', $manager_bill_model->showpage());
        Tpl::output('list_manager_bill',$list_manager_bill);
        Tpl::output('area',$area);
        Tpl::showpage('manager_index');

    }

    /**
     *虚拟账单 列表
     */
    public function vr_billOp(){
        //中心顶部列表
        self::profile_vr_menu('points');
        Language::read('member_member_points,member_pointorder');
        //搜素start
        $where = array();
         $where['uid'] = $_SESSION['manager_id'];
         if ($_GET['stage']){
             $where['state'] = $_GET['stage'];
         }
        if (trim($_GET['stime'])) {
             $stime = strtotime($_GET['stime']);
             $where['start_time'] = array('egt', $stime);
         }
        if (trim($_GET['etime'])) {
             $etime = strtotime($_GET['etime']) + 86400;
             $where['end_time'] = array('elt', $etime);
         }
         $where['mb_id'] = array('like',"%{$_GET['mb_id']}%");
        //搜素end
        //查询管理人收益列表
        $where['uid'] = $_SESSION['manager_id'];
        $manager_bill_model = Model('manager_vr_bill');
        $list_manager_bill = $manager_bill_model->getManagerVrBillList($where);
        //查询管理区域 拼接好 传递给前台
        $area = '';
        if(sizeof($list_manager_bill)>0){
            $area_model = Model('manager_real_bill');

            if(!empty($list_manager_bill[0]['area_region'])){
                $area = $list_manager_bill[0]['area_region'];
            }
            if(!empty($list_manager_bill[0]['province_id'])){
                $v = $area_model->getAreaInfo($list_manager_bill[0]['province_id']);

                $area.= "&nbsp".$v['area_name'];
            }
            if(!empty($list_manager_bill[0]['city_id'])){
                $v = $area_model->getAreaInfo($list_manager_bill[0]['city_id']);
                $area.= "&nbsp".$v['area_name'];
            }
            if(!empty($list_manager_bill[0]['district_id'])){
                $v = $area_model->getAreaInfo($list_manager_bill[0]['district_id']);
                $area.= "&nbsp".$v['area_name'];
            }
        }

        Tpl::output('show_page', $manager_bill_model->showpage());
        Tpl::output('list_manager_bill',$list_manager_bill);
        Tpl::output('area',$area);
        Tpl::showpage('manager_vr_bill');
    }

    /**
     * 用户中心右边，小导航
     *
     * @param string    $menu_type  导航类型
     * @param string    $menu_key   当前导航的menu_key
     * @param array     $array      附加菜单
     * @return
     */
    private function profile_menu($menu_key='',$array=array()) {
        $menu_array = array(
            1=>array('menu_key'=>'points',  'menu_name'=>'实物结算单',    'menu_url'=>'index.php?con=manager_index&fun=vr_bill'),
          /*  2=>array('menu_key'=>'orderlist','menu_name'=>'历史结算单',    'menu_url'=>'index.php?con=member_pointorder&fun=orderlist')*/
        );
        if(!empty($array)) {
            $menu_array[] = $array;
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
    /**
     * 用户中心右边，小导航 [虚拟结算单]
     *
     * @param string    $menu_type  导航类型
     * @param string    $menu_key   当前导航的menu_key
     * @param array     $array      附加菜单
     * @return
     */
    private function profile_vr_menu($menu_key='',$array=array()) {
        $menu_array = array(
            1=>array('menu_key'=>'points',  'menu_name'=>'虚拟结算单',    'menu_url'=>'index.php?con=manager_index&fun=index'),
            /*  2=>array('menu_key'=>'orderlist','menu_name'=>'历史结算单',    'menu_url'=>'index.php?con=member_pointorder&fun=orderlist')*/
        );
        if(!empty($array)) {
            $menu_array[] = $array;
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }

    /**
     * 修改密码
     */
    public function modify_pwdOp(){
        //查看是否是POST提交
        if(chksubmit()){
            //查看旧密码是否匹配
           if($this->manager_member['member_passwd'] == md5($_POST['oldpassword'])){

               $obj_validate = new Validate();
               $obj_validate->validateparam = array(
                   array("input"=>$_POST["password"],      "require"=>"true",      "message"=>'请正确输入密码'),
                   array("input"=>$_POST["confirm_password"],  "require"=>"true",      "validator"=>"Compare","operator"=>"==","to"=>$_POST["password"],"message"=>'两次密码输入不一致'),
               );
               $error = $obj_validate->validate();
               if ($error != ''){
                   exit(json_encode(array('state'=>false,'info'=>$error)));
               }else{
                   $update['member_passwd'] =md5($_POST["password"]);
                   $re =$this->model_member->editManagerMember(array('member_id'=>$_SESSION['manager_id']),$update);
                   if($re){
                   exit(json_encode(array('state'=>true,'info'=>'修改成功')));
                   }
               }

           }else{
                   exit(json_encode(array('state'=>false,'info'=>'密码不正确')));
           }
        }else{
            Tpl::showpage('manager_password');
        }
    }

    /**
     * 查看管理员信息的验证
     */
    public function edit_managerOp(){
        $details = 'details'.$_SESSION['manager_id'];
        if(cookie('details') == md5(_encrypt($details))){
            redirect(urlMember('manager_index','manager_details'));
        }
        if (chksubmit(false,true)) {

            $member_common_info = $this->model_member->getManagerCommonInfo(array('manager_id'=>$_SESSION['manager_id']));
            if (empty($member_common_info) || !is_array($member_common_info)) {
                exit(json_encode(array('state'=>false,'info'=>'验证失败')));
            }
            if (TIMESTAMP - $member_common_info['send_acode_time'] > 1800) {

                exit(json_encode(array('state'=>false,'info'=>'验证码已失效，请重新获取验证码')));


            }
            if ($member_common_info['auth_code'] != $_POST['auth_code']) {
                exit(json_encode(array('state'=>false,'info'=>'验证失败')));

            }
            $data = array();
            $data['auth_code'] = '';
            $data['send_acode_time'] = 0;
            $update = $this->model_member->editManagerCommon($data,array('manager_id'=>$_SESSION['manager_id']));
            if (!$update) {
                exit(json_encode(array('state'=>false,'info'=>'系统发生错误，如有疑问请与管理员联系')));
            }
           $details = 'details'.$_SESSION['manager_id'];
            setNcCookie('details',md5(_encrypt($details)),2*3600); //设置管理人详情
            exit(json_encode(array('state'=>true,'info'=>'验证成功')));
        }else{
            $member_info = array();
            $member_info['member_mobile'] = $this->manager['contacts_phone'];
            $member_info['member_email'] = $this->manager['contacts_email'];
            Tpl::output('member_info',$member_info);
            Tpl::showpage('manager_verification');
        }
    }
    /**
     * 统一发送身份验证码  管理人扩展表
     */
    public function send_auth_codeOp() {
        if (!in_array($_GET['type'],array('email','mobile'))) exit();

        $member_info = array();
        $member_info['member_mobile'] = $this->manager['contacts_phone'];
        $member_info['member_email'] = $this->manager['contacts_email'];

        //发送频率验证
        $member_common_info = $this->model_member->getManagerCommonInfo(array('manager_id'=>$_SESSION['manager_id']));
        if (!empty($member_common_info['send_acode_time'])) {
            if (date('Ymd',$member_common_info['send_acode_time']) != date('Ymd',TIMESTAMP)) {
                $data = array();
                $data['send_acode_times'] = 0;
                $update = $this->model_member->editManagerCommon($data,array('manager_id'=>$_SESSION['manager_id']));
            } else {
                if (TIMESTAMP - $member_common_info['send_acode_time'] < 58) {
                    exit(json_encode(array('state'=>'false','msg'=>'请60秒以后再次发送短信')));
                } else {
                    if ($member_common_info['send_acode_times'] >= 15) {
                        exit(json_encode(array('state'=>'false','msg'=>'您今天发送验证信息已超过15条，今天将无法再次发送')));
                    }
                }
            }
        }

        $verify_code = rand(100,999).rand(100,999);
        $model_tpl = Model('mail_templates');
        $tpl_info = $model_tpl->getTplInfo(array('code'=>'authenticate'));

        $param = array();
        $param['send_time'] = date('Y-m-d H:i',TIMESTAMP);
        $param['verify_code'] = $verify_code;
        $param['site_name'] = C('site_name');
        $subject = ncReplaceText($tpl_info['title'],$param);
        $message = ncReplaceText($tpl_info['content'],$param);
        if ($_GET['type'] == 'email') {
            try {
                \shopec\Lib::messager()->send($member_info["member_email"],$subject,$message);
                $result = true;
            } catch (\shopec\Lib\Messager\Exception $ex) {
                $result = false;
            }
        } elseif ($_GET['type'] == 'mobile') {
        	//511613932
        	$paramdata = array();
        	if(C('sms.smsNumber') == 1){
        		$paramdata['sendtime']  = date('Y-m-d H:i',TIMESTAMP);
        		$paramdata['verifycode']  = $verify_code;
        		$paramdata['template']  = C('dysms.verify');
        	}
            $sms = new Sms();
            $result = $sms->send($member_info["member_mobile"],$message,$paramdata);
        }
        if ($result) {
            $data = array();
            $update_data['auth_code'] = $verify_code;
            $update_data['send_acode_time'] = TIMESTAMP;
            $update_data['send_acode_times'] = array('exp','send_acode_times+1');
            $update = $this->model_member->editManagerCommon($update_data,array('manager_id'=>$_SESSION['manager_id']));
            if (!$update) {
                exit(json_encode(array('state'=>'false','msg'=>'系统发生错误，如有疑问请与管理员联系')));
            }
            exit(json_encode(array('state'=>'true','msg'=>'验证码已发出，请注意查收')));
        } else {
            exit(json_encode(array('state'=>'false','msg'=>'验证码发送失败')));
        }
    }

    /**
     * 显示管理员详情
     */
    public function manager_detailsOp(){
        $details = 'details'.$_SESSION['manager_id'];
        if(cookie('details') == md5(_encrypt($details))){
            $joinin_detail=$this->manager;
            Tpl::output('pic_url',UPLOAD_SITE_URL.DS.ATTACH_PATH.DS.'store_joinin'.DS);
            Tpl::output('joinin_detail',$joinin_detail);
            Tpl::showpage('manager_info');
        }else{
            redirect(urlMember('manager_index','index'));
        }

    }

    /**
     * 修改申请状态 跳转到申请 管理员页面
     */
    public function edit_manager_detailsOp(){
        if(chksubmit()){
            $param = array();
            $param['complete_company_name'] = $_POST['complete_company_name'];
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
            $param['id_number'] = $_POST['id_number'];
            $param['identity_card_electronic'] = $_POST['identity_card_electronic1'];
            $param['business_licence_number'] = $_POST['business_licence_number'];
            $param['business_licence_address'] = $_POST['business_licence_address'];
            $param['business_licence_start'] = $_POST['business_licence_start'];
            $param['business_licence_end'] = $_POST['business_licence_end'];
            $param['business_sphere'] = $_POST['business_sphere'];
            $param['business_licence_number_elc'] = $_POST['business_licence_number_elc1'];
            $param['organization_code'] = $_POST['organization_code'];
            $param['organization_code_electronic'] = $_POST['organization_code_electronic1'];
            $param['general_taxpayer'] = $_POST['general_taxpayer1'];
            $param['bank_account_name'] = $_POST['bank_account_name'];
            $param['bank_account_number'] = $_POST['bank_account_number'];
            $param['bank_name'] = $_POST['bank_name'];
            $param['bank_code'] = $_POST['bank_code'];
            $param['bank_address'] = $_POST['bank_address'];
            $param['bank_licence_electronic'] = $_POST['bank_licence_electronic1'];
            $param['is_settlement_account'] = 2;
            $param['settlement_bank_account_name'] = $_POST['settlement_bank_account_name'];
            $param['settlement_bank_account_number'] = $_POST['settlement_bank_account_number'];
            $param['settlement_bank_name'] = $_POST['settlement_bank_name'];
            $param['settlement_bank_code'] = $_POST['settlement_bank_code'];
            $param['settlement_bank_address'] = $_POST['settlement_bank_address'];
            $param['tax_registration_certificate'] = $_POST['tax_registration_certificate'];
            $param['taxpayer_id'] = $_POST['taxpayer_id'];
            $param['tax_registration_certif_elc'] = $_POST['tax_registration_certif_elc1'];
            $param['apply_state'] = 20;
                $obj_validate = new Validate();
                $obj_validate->validateparam = array(
                    array("input"=>$param['complete_company_name'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>"公司名称不能为空且必须小于50个字"),
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
                $error = $obj_validate->validate();//后台验证
               if ($error != ''){
                    exit(json_encode(array('state'=>false,'info'=>$error)));
                }else{
                   $param['id_number'] = _encrypt($_POST['id_number']);
                   $param['business_licence_number'] = _encrypt($_POST['business_licence_number']);
                   $param['organization_code'] = _encrypt($_POST['organization_code']);
                   $param['bank_account_number'] = _encrypt($_POST['bank_account_number']);
                   $param['bank_code'] = _encrypt($_POST['bank_code']);
                   $param['settlement_bank_account_number'] = _encrypt($_POST['settlement_bank_account_number']);
                   $param['settlement_bank_code'] = _encrypt($_POST['settlement_bank_code']);
                   $param['tax_registration_certificate'] = _encrypt($_POST['tax_registration_certificate']);
                   $param['taxpayer_id'] = _encrypt($_POST['taxpayer_id']);
                    $re =$this->model_member->editmanager(array('member_id'=>$_SESSION['manager_id']),$param);
                    if($re){
                        exit(json_encode(array('state'=>true,'info'=>'修改成功')));
                    }else{
                        exit(json_encode(array('state'=>false,'info'=>'系统发生错误')));
                    }
                }

        }

    }

    /**
     * 申请打款
     */
    public function applyOp(){
        //先判断资格
        if($this->manager['apply_state'] == 30){
            //提交申请
            $length = count($_POST['id']);
            $model_manager_bill = Model('manager_bill');
            try{
                $model_manager_bill->beginTransaction();
                for($i=0;$i<$length;$i++){
                    if (!is_array($_POST['id'])) {
                        $condition['mb_id'] = $_POST['id'];
                    } else {
                        $condition['mb_id'] = $_POST['id'][$i];
                    }
                    $condition['uid'] = $_SESSION['manager_id'];
                    $condition['state'] = 1;
                    $re = $model_manager_bill->getManagerBillInfo($condition);
                    if ($re && is_array($re)) {
                        $date['state'] = 2;
                        $date['apply_date'] = TIMESTAMP;
                        $result = $model_manager_bill->editManagerBill($date, $condition);
                        if(!$result){
                            throw new Exception('系统出错,请与管理员联系');
                        }
                    }else{
                        throw new Exception('未找到');
                    }
                }
                $model_manager_bill->commit();
                exit(json_encode(array('state'=>'true','msg'=>'已提交申请')));
            } catch (Exception $e) {
                $model_manager_bill->rollback();
                exit(json_encode(array('state'=>'false','msg'=>$e->getMessage())));
            }
        }else{
            if($this->manager['apply_state'] == 10){
                exit(json_encode(array('state'=>'false','msg'=>'资料未提交,请提交资料')));
            }
            if($this->manager['apply_state'] == 20){
                exit(json_encode(array('state'=>'false','msg'=>'资料正在审核,请等待管理员审核')));
            }
            if($this->manager['apply_state'] == 40){
                exit(json_encode(array('state'=>'false','msg'=>'资料审核未通过,请重新提交资料')));
            }
        }
    }
    /**
     * 申请打款[虚拟]
     */
    public function apply_vrOp(){
        //先判断资格
        if($this->manager['apply_state'] == 30){
            //提交申请
            $length = count($_POST['id']);
            $model_manager_bill = Model('manager_vr_bill');
            try{
                $model_manager_bill->beginTransaction();
                for($i=0;$i<$length;$i++){
                    if (!is_array($_POST['id'])) {
                        $condition['mb_id'] = $_POST['id'];
                    } else {
                        $condition['mb_id'] = $_POST['id'][$i];
                    }
                    $condition['uid'] = $_SESSION['manager_id'];
                    $condition['state'] = 1;
                    $re = $model_manager_bill->getManagerBillInfo($condition);
                    if ($re && is_array($re)) {
                        $date['state'] = 2;
                        $date['apply_date'] = TIMESTAMP;
                        $result = $model_manager_bill->editManagerBill($date, $condition);
                        if(!$result){
                            throw new Exception('系统出错,请与管理员联系');
                        }
                    }else{
                        throw new Exception('未找到');
                    }
                }
                $model_manager_bill->commit();
                exit(json_encode(array('state'=>'true','msg'=>'已提交申请')));
            } catch (Exception $e) {
                $model_manager_bill->rollback();
                exit(json_encode(array('state'=>'false','msg'=>$e->getMessage())));
            }
        }else{
            if($this->manager['apply_state'] == 10){
                exit(json_encode(array('state'=>'false','msg'=>'资料未提交,请提交资料')));
            }
            if($this->manager['apply_state'] == 20){
                exit(json_encode(array('state'=>'false','msg'=>'资料正在审核,请等待管理员审核')));
            }
            if($this->manager['apply_state'] == 40){
                exit(json_encode(array('state'=>'false','msg'=>'资料审核未通过,请重新提交资料')));
            }
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
     * 删除图片
     */
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