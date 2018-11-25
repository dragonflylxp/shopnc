<?php

/**

 * 用户中心

 *

 *

 *

 *

 */



defined('Inshopec') or exit('Access Invalid!');



class member_accountControl extends mobileMemberControl {



    public function __construct(){

        parent::__construct();

    }

    /*

    *我的设置

    */

    public function indexOp(){

         

         $avator = getMemberAvatarForID($_SESSION['member_id']);

         Tpl::output('member_info',$this->member_info);

         Tpl::output('avator',$avator);

         Tpl::output('web_seo',C('site_name').' - '.'我的设置');

         Tpl::showpage('member_account_index');

    }

    /*

    *我的头像修改view

    */

    public function update_imgOp(){

         $avator = getMemberAvatarForID($_SESSION['member_id']);

         Tpl::output('avator',$avator);

         Tpl::output('web_seo',C('site_name').' - '.'头像设置');

         Tpl::showpage('update_img');

    }

    /*

    *我的头像次修改操作

    */

    public function ajax_update_imgOp(){

          

            $member_id = $_SESSION['member_id'];

            $img = $_POST['img'];

            $file['list']=BASE_UPLOAD_PATH.DS.ATTACH_AVATAR;

            $member_info = Model('member')->getMemberInfoByID($member_id,'member_avatar');

          

            if ($member_info['member_avatar']) {

                $src = BASE_UPLOAD_PATH.DS.ATTACH_AVATAR.DS.$member_info['member_avatar'];

                $clear = Model('member')->editMember(array('member_id'=>$member_id),array('member_avatar'=>''));

                @unlink($src);

            }

            if (!file_exists($file['list'])) {

                mkdir($file['list'],0777,true);

            }

            if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $img, $result)){

              $type = $result[2];

              $filename = $file['list']."/avatar_{$member_id}.jpg";

              file_put_contents($filename, base64_decode(str_replace($result[1], '', $img)));

            }

     

            $info = explode('/', $filename);

            $member_avatar = $info[count($info)-1];

            $up = Model('member')->editMember(array('member_id'=>$member_id),array('member_avatar'=>$member_avatar));
            if($up){
                echo json_encode(array('status'=>1,'info'=>'头像上传成功!','img'=>$member_avatar));
            }else{
                echo json_encode(array('status'=>0,'info'=>'头像上传失败!'));
            }

            

    }

    /**

    *设置真实姓名视图

    */

    public function member_truenameOp(){

       Tpl::output('web_seo',C('site_name').' - '.'设置真实姓名');

       Tpl::output('truename',$this->member_info['member_truename']);

       Tpl::showpage('member_truename');  

    }

    /**

    *设置真实姓名

    */

    public function update_truenameOp(){

        $truename = trim($_POST['truename']);

        $member_id = $_SESSION['member_id'];

        $model_member = Model('member');

        $update = $model_member->editMember(array('member_id'=>$member_id),array('member_truename'=>$truename));

        if($update){

            output_data("更新失败！");

        }else{

            output_error("更新成功！");

        }

    }

    /**

    *设置性别视图

    */

    public function member_sexOp(){

       Tpl::output('web_seo',C('site_name').' - '.'性别设置');

       Tpl::output('member_sex',$this->member_info['member_sex']);

       Tpl::showpage('member_sex');  

    }

    /**

    *设置性别

    */

    public function update_sexOp(){

        $sex = trim($_POST['sex']);

        $member_id = $_SESSION['member_id'];

        $model_member = Model('member');

        $update = $model_member->editMember(array('member_id'=>$member_id),array('member_sex'=>$sex));

        if($update){

            output_data("更新失败！");

        }else{

            output_error("更新成功！");

        }

    }

    /**

    *设置生日视图

    */

    public function member_birthdayOp(){

       Tpl::output('web_seo',C('site_name').' - '.'生日设置');

       $update_birthday = explode('-', $this->member_info['member_birthday']);

       Tpl::output('update_birthday',$update_birthday);

       Tpl::showpage('member_birthday');  

    }

    /**

    *设置生日

    */

    public function update_birthdayOp(){

        $birthday = trim($_POST['birthday']);

        $member_id = $_SESSION['member_id'];

        $model_member = Model('member');

        $update = $model_member->editMember(array('member_id'=>$member_id),array('member_birthday'=>$birthday));

        if($update){

            output_data("更新失败！");

        }else{

            output_error("更新成功！");

        }

    }

	/**

    *登录密码设置页

    */

    public function member_password_step1Op(){

        Tpl::output('web_seo',C('site_name').' - '.'登录密码设置');

        Tpl::showpage('member_password_step1');

    }

	/**

     * 我的钱

     */

    public function get_mobile_infoOp() {

		$data = array();

		$data['state'] = true;

		if($this->member_info['member_mobile_bind']==0){

			$data['state'] = false;

		}

        $data['member_mobile_bind'] = $this->member_info['member_mobile_bind'];

		$data['mobile'] = $this->member_info['member_mobile'];

		output_data($data);

	}



	



	public function get_paypwd_infoOp() {		

		$data['state'] = false;

		if($this->member_info['member_paypwd']){

			$data['state'] = true;

		}

		output_data($data);

	}

    /**

    *手机绑定页面

    */

    public function member_mobile_bindOp(){

         Tpl::output('web_seo',C('site_name').' - '.'手机验证');

         Tpl::output('member_mobile',$this->member_info['member_mobile']);

         Tpl::showpage('member_mobile_bind');

    }

    /**

    *邮箱绑定页面

    */

    public function member_email_bindOp(){

         Tpl::output('web_seo',C('site_name').' - '.'邮箱验证');

         Tpl::output('member_email',$this->member_info['member_email']);

         Tpl::showpage('member_email_bind');

    }

    /**

    *手机绑定第一步

    */

	public function bind_mobile_step1Op() {

		if(!$this->check()){

			output_error('验证码错误！');

		}

        $mobile = $_POST['mobile'];

        $infos = Model('member')->getMemberInfo(array('member_mobile'=>$mobile,'member_id'=>array('not in',$this->member_info['member_id'])));

        if(!$infos){

            if($this->member_info['member_mobile_bind']==1 && $this->member_info['member_mobile'] == $mobile){

                $datas['info'] = '你已经绑定过该手机号！';

                $datas['status'] = 1; 

                output_error($datas);   

            }else{

                $this->send_mobile($mobile);

            }

        }else{

            output_error('该手机号已被占用！');   

        }

		

	}

    /**

    *手机绑定第二步

    */

	public function bind_mobile_step2Op() {

		$auth_code = $_POST['auth_code'];

		$member_id = $this->member_info['member_id'];

        $mobile = $_POST['mobile'];

		$model_member = Model('member');

        $member_info = $model_member->getMemberInfoByID($member_id,'member_mobile_bind');

        if ($member_info) {

            $obj_validate = new Validate();

            $obj_validate->validateparam = array(

                array("input"=>$auth_code, "require"=>"true", 'validator'=>'number',"message"=>'请正确填写手机验证码'),

                array("input"=>$mobile, "require"=>"true", 'validator'=>'mobile',"message"=>'请正确填写手机号码')

            );

            $error = $obj_validate->validate();

            if ($error != ''){

                output_error($error);

            }



            $condition = array();

            $condition['member_id'] = $member_id;

            $condition['auth_code'] = intval($auth_code);

            $member_common_info = $model_member->getMemberCommonInfo($condition,'send_acode_time');

            if (!$member_common_info) {

                output_error('手机验证码错误，请重新输入');

            }

            if (TIMESTAMP - $member_common_info['send_acode_time'] > 1800) {

                output_error('手机验证码已过期，请重新获取验证码');

            }

			$update = $model_member->editMember(array('member_id'=>$member_id),array('member_mobile_bind'=>1,'member_mobile'=>$mobile));

            if (!$update) {

                output_error('系统发生错误，如有疑问请与管理员联系');

            }

			output_data('绑定成功');

		}

	

	}

    /**

    *邮箱绑定第一步

    */

    public function bind_email_step1Op() {

        if(!$this->check()){

            output_error('验证码错误！');

        }

        $email = $_POST['email'];

        $infos = Model('member')->getMemberInfo(array('member_email'=>$email,'member_id'=>array('not in',$this->member_info['member_id'])));

        if(!$infos){

            if($this->member_info['member_email_bind']==1 && $this->member_info['member_email'] == $email){

                $datas['info'] = '你已经绑定过该邮箱了！';

                $datas['status'] = 1; 

                output_error($datas);   

            }else{

                $this->send_email($email);

            }

        }else{

            output_error('该邮箱已被占用！');   

        }

        

        

    }

    /**

    *邮箱绑定第二步

    */

    public function bind_email_step2Op() {

        $auth_code = $_POST['auth_code'];

        $email = $_POST['email'];

        $member_id = $this->member_info['member_id'];

        $model_member = Model('member');

        $member_info = $model_member->getMemberInfoByID($member_id,'member_email_bind');

        if ($member_info) {

            $obj_validate = new Validate();

            $obj_validate->validateparam = array(

                array("input"=>$auth_code, "require"=>"true", 'validator'=>'number',"message"=>'请正确填写验证码'),

                array("input"=>$email, "require"=>"true", 'validator'=>'email',"message"=>'请正确填写邮箱')



            );

            $error = $obj_validate->validate();

            if ($error != ''){

                output_error($error);

            }



            $condition = array();

            $condition['member_id'] = $member_id;

            $condition['auth_code'] = intval($auth_code);

            $member_common_info = $model_member->getMemberCommonInfo($condition,'send_acode_time');

            if (!$member_common_info) {

                output_error('验证码错误，请重新输入');

            }

            if (TIMESTAMP - $member_common_info['send_acode_time'] > 1800) {

                output_error('验证码已过期，请重新获取验证码');

            }

            $update = $model_member->editMember(array('member_id'=>$member_id),array('member_email_bind'=>1,'member_email'=>$email));

            if (!$update) {

                output_error('系统发生错误，如有疑问请与管理员联系');

            }

            output_data('绑定成功');

        }

    

    }

    /*

    *解除手机绑定

    *

    */

    public function member_mobile_modifyOp(){

         Tpl::output('web_seo',C('site_name').' - '.'解除手机绑定');

         Tpl::output('member_mobile',$this->member_info['member_mobile']);

         Tpl::showpage('member_mobile_modify');

    }

    /*

    *解除邮箱绑定

    *

    */

    public function member_email_modifyOp(){

         Tpl::output('web_seo',C('site_name').' - '.'解除邮箱绑定');

         Tpl::output('member_email',$this->member_info['member_email']);

         Tpl::showpage('member_email_modify');

    }

    /*

    *解除手机绑定发送验证码

    */

	public function modify_mobile_step2Op() {

		if(!$this->check()){

			output_error('验证码错误！');

		}

		$this->send_mobile($this->member_info['member_mobile']);

	}

	/*

    *解除邮箱绑定发送验证码

    */

    public function modify_email_step2Op() {

        if(!$this->check()){

            output_error('验证码错误！');

        }

        $this->send_email($this->member_info['member_email']);

    }

	public function modify_mobile_step3Op() {

		$auth_code = $_POST['auth_code'];

		$member_id = $this->member_info['member_id'];

		$model_member = Model('member');

        $member_info = $model_member->getMemberInfoByID($member_id,'member_mobile_bind');

        if ($member_info) {

            $obj_validate = new Validate();

            $obj_validate->validateparam = array(

                array("input"=>$auth_code, "require"=>"true", 'validator'=>'number',"message"=>'请正确填写手机验证码')

            );

            $error = $obj_validate->validate();

            if ($error != ''){

                output_error($error);

            }



            $condition = array();

            $condition['member_id'] = $member_id;

            $condition['auth_code'] = intval($auth_code);

            $member_common_info = $model_member->getMemberCommonInfo($condition,'send_acode_time');

            if (!$member_common_info) {

                output_error('手机验证码错误，请重新输入');

            }

            if (TIMESTAMP - $member_common_info['send_acode_time'] > 1800) {

                output_error('手机验证码已过期，请重新获取验证码');

            }

			$update = $model_member->editMember(array('member_id'=>$member_id),array('member_mobile_bind'=>0));

            if (!$update) {

                output_error('系统发生错误，如有疑问请与管理员联系');

            }

			output_data('解绑成功');

		}



	}

    /*

    *解除邮箱绑定

    */

    public function modify_email_step3Op() {

        $auth_code = $_POST['auth_code'];

        $member_id = $this->member_info['member_id'];

        $model_member = Model('member');

        $member_info = $model_member->getMemberInfoByID($member_id,'member_email_bind');

        if ($member_info) {

            $obj_validate = new Validate();

            $obj_validate->validateparam = array(

                array("input"=>$auth_code, "require"=>"true", 'validator'=>'number',"message"=>'请正确填写验证码')

            );

            $error = $obj_validate->validate();

            if ($error != ''){

                output_error($error);

            }



            $condition = array();

            $condition['member_id'] = $member_id;

            $condition['auth_code'] = intval($auth_code);

            $member_common_info = $model_member->getMemberCommonInfo($condition,'send_acode_time');

            if (!$member_common_info) {

                output_error('验证码错误，请重新输入');

            }

            if (TIMESTAMP - $member_common_info['send_acode_time'] > 1800) {

                output_error('验证码已过期，请重新获取验证码');

            }

            $update = $model_member->editMember(array('member_id'=>$member_id),array('member_email_bind'=>0));

            if (!$update) {

                output_error('系统发生错误，如有疑问请与管理员联系');

            }

            output_data('解绑成功');

        }



    }



    /**

     * 更改密码 第二步 - 向已经绑定的手机发送验证码

     */

    public function modify_password_step1Op() {

        $this->send_mobile($this->member_info['member_mobile']);

    }

    public function modify_password_step2Op() {

        Tpl::output('web_seo',C('site_name').' - '.'密码设置');

        Tpl::showpage('member_password_step2');

    }

    public function modify_password_step3Op() {

        $this->_modify_pwd_check_vcode();

    }

    /**

     * 更改密码 第四步 - 检查是否有权修改密码

     */

    public function modify_password_step4Op() {

        $this->_modify_pwd_limit_check();

        output_data('1');

    }



    /**

     * 更改密码 第五步 - 保存新密码到数据库

     */

    public function modify_password_step5Op() {



        if (!$_POST['password'] || !$_POST['password1'] || $_POST['password'] != $_POST['password1']) {

            output_error('提交数据错误');

        }



        //身份验证后，需要在30分钟内完成修改密码操作

        $this->_modify_pwd_limit_check();



        $model_member = Model('member');



        $update = $model_member->editMember(array('member_id'=>$this->member_info['member_id']),array('member_passwd'=>md5($_POST['password'])));

        if (!$update) {

            output_error('密码修改失败');

        }



        $update = $model_member->editMemberCommon(array('send_mb_time'=>'0'),array('member_id'=>$this->member_info['member_id']));

        if (!$update) {

            output_error('系统发生错误');

        }else{

             $model_mb_user_token = Model('mb_user_token');

            if($this->member_info['member_name'] ==  $_SESSION['member_name']) {

                $condition = array();

                $condition['member_id'] = $this->member_info['member_id'];

                $condition['client_type'] ="wap";

                $model_mb_user_token->delMbUserToken($condition);

                setNcCookie('key', '', -3600);

                setNcCookie('cart_count', '', -3600);

                session_unset();

                session_destroy();

               

                output_data('1');

            }

        }

     

    }

    private function _modify_pwd_limit_check() {

        //身份验证后，需要在30分钟内完成修改密码操作

        $model_member = Model('member');

        $member_common_info = $model_member->getMemberCommonInfo(array('member_id'=>$this->member_info['member_id']));

        if (empty($member_common_info) || !is_array($member_common_info)) {

            output_error('验证失败');

        }

        if ($member_common_info['send_mb_time'] && TIMESTAMP - $member_common_info['send_mb_time'] > 1800) {

            output_error('操作超时，请重新获取短信验证码');

        }

    }   

       public function _modify_pwd_check_vcode() {

        if (!$_POST['auth_code'] || !preg_match('/^\d{6}$/',$_POST['auth_code'])) {

            output_error('请正确输入短信验证码');

        }

        $model_member = Model('member');

        $member_common_info = $model_member->getMemberCommonInfo(array('member_id'=>$this->member_info['member_id']));

        if (empty($member_common_info) || !is_array($member_common_info)) {

            output_error('验证失败');

        }

        if (TIMESTAMP - $member_common_info['send_acode_time'] > 1800) {

            output_error('验证码已失效，请重新获取');

        }

        if ($member_common_info['auth_code_check_times'] > 3) {

            output_error('输入错误次数过多，请重新获取');

        }

        if ($member_common_info['auth_code'] != $_POST['auth_code']) {

            $data = array();

            $update_data['auth_code_check_times'] = array('exp','auth_code_check_times+1');

            $update = $model_member->editMemberCommon($update_data,array('member_id'=>$this->member_info['member_id']));

            if (!$update) {

                output_error('系统发生错误a');

            }

            output_error('验证失败');

        }



        $data = array();

        $data['auth_code'] = '';

        $data['send_acode_time'] = 0;

        $data['auth_code_check_times'] = 0;

        $update = $model_member->editMemberCommon($data,array('member_id'=>$this->member_info['member_id']));

        if (!$update) {

            output_error('系统发生错误b');

        }



        //更改密码授权

        $update = $model_member->editMemberCommon(array('auth_modify_pwd_time'=>TIMESTAMP),array('member_id'=>$this->member_info['member_id']));

        if (!$update) {

            output_error('系统发生错误c');

        }



        output_data('1');

    }



	/**

    *member_paypwd_step1支付密码设置

    *

    */

    public function member_paypwd_step1Op(){

        Tpl::output('web_seo',C('site_name').' - '.'支付密码设置');

        if(!$this->member_info['member_mobile'] && !$this->member_info['member_mobile_bind']==1){

            @header('location: index.php?con=member_account&fun=member_mobile_bind');die;

            

        }

        Tpl::output('member_mobile',$this->member_info['member_mobile']);

        Tpl::showpage('member_paypwd_step1');

    }

    public function modify_paypwd_step2Op() {

		if(!$this->check()){

			output_error('验证码错误！');

		}

		$this->send_mobile($this->member_info['member_mobile']);

	}	



	public function modify_paypwd_step3Op() {

		$auth_code = $_POST['auth_code'];

		$member_id = $this->member_info['member_id'];

		$model_member = Model('member');

        $member_info = $model_member->getMemberInfoByID($member_id,'member_mobile_bind');

        if ($member_info) {

            $obj_validate = new Validate();

            $obj_validate->validateparam = array(

                array("input"=>$auth_code, "require"=>"true", 'validator'=>'number',"message"=>'请正确填写手机验证码')

            );

            $error = $obj_validate->validate();

            if ($error != ''){

                output_error($error);

            }



            $condition = array();

            $condition['member_id'] = $member_id;

            $condition['auth_code'] = intval($auth_code);

            $member_common_info = $model_member->getMemberCommonInfo($condition,'send_acode_time');

            if (!$member_common_info) {

                output_error('手机验证码错误，请重新输入');

            }

            if (TIMESTAMP - $member_common_info['send_acode_time'] > 1800) {

                output_error('手机验证码已过期，请重新获取验证码');

            }

            $data = array();

			$data['auth_code'] = intval($auth_code);

			$data['send_acode_time'] = TIMESTAMP;

            $update = $model_member->editMemberCommon($data,array('member_id'=>$member_id));

            if (!$update) {

                output_error('系统发生错误，如有疑问请与管理员联系');

            }

            $update = $model_member->editMember(array('member_id'=>$member_id),array('member_mobile_bind'=>1));

            if (!$update) {

                output_error('系统发生错误，如有疑问请与管理员联系');

            }

            output_data('手机号绑定成功');

        }

	}







	public function modify_paypwd_step4Op() {		

		Tpl::output('web_seo',C('site_name').' - '.'支付密码设置');

        $member_id = $this->member_info['member_id'];

        $model_member = Model('member');

        $member_common_info = $model_member->getMemberCommonInfo(array('member_id'=>$member_id));

        if (empty($member_common_info) || !is_array($member_common_info)) {

             showMessage('验证失败',urlMobile('member_account'),'html','error');

        }

        if (TIMESTAMP - $member_common_info['send_acode_time'] > 1800) {

            showMessage('验证码已被使用或超时，请重新获取验证码',urlMobile('member_account'),'html','error');

        }

        Tpl::showpage('member_paypwd_step2');

	}

	public function modify_paypwd_step5Op() {

		$member_id = $this->member_info['member_id'];

		$model_member = Model('member');

		$member_common_info = $model_member->getMemberCommonInfo(array('member_id'=>$member_id));	

		if (empty($member_common_info) || !is_array($member_common_info)) {

			output_error('验证失败');

		}

		if (TIMESTAMP - $member_common_info['send_acode_time'] > 1800) {

			output_error('验证码已被使用或超时，请重新获取验证码');

		}



        $obj_validate = new Validate();

        $obj_validate->validateparam = array(

                array("input"=>$_POST["password"],      "require"=>"true",      "message"=>'请正确输入密码'),

                array("input"=>$_POST["password1"],  "require"=>"true",      "validator"=>"Compare","operator"=>"==","to"=>$_POST["password"],"message"=>'两次密码输入不一致'),

        );

        $error = $obj_validate->validate();

        if ($error != ''){

            output_error($error);

        }

        $update = $model_member->editMember(array('member_id'=>$member_id),array('member_paypwd'=>md5($_POST['password'])));

        $message = $update ? '密码设置成功' : '密码设置失败';

        unset($_SESSION['auth_modify_paypwd']);

		output_data($message);	

	}





	



	/**

     * 发短信

     */

	private function send_mobile($mobile){

		$obj_validate = new Validate();

		//$mobile = $_GET["mobile"];

		$member_id = $this->member_info['member_id'];

        $obj_validate->validateparam = array(

            array("input"=>$mobile, "require"=>"true", 'validator'=>'mobile',"message"=>'请正确填写手机号码'),

        );

        $error = $obj_validate->validate();

        if ($error != ''){

			output_error($error);

        }



        $model_member = Model('member');



        //发送频率验证

        $member_common_info = $model_member->getMemberCommonInfo(array('member_id'=>$member_id));

        if (!empty($member_common_info['send_mb_time'])) {

            if (date('Ymd',$member_common_info['send_mb_time']) != date('Ymd',TIMESTAMP)) {

                $data = array();

                $data['send_mb_times'] = 0;

                $update = $model_member->editMemberCommon($data,array('member_id'=>$member_id));               

            } else {

                if (TIMESTAMP - $member_common_info['send_mb_time'] < 58) {

					output_error('请60秒以后再次发送短信');

                } else {

                    if ($member_common_info['send_mb_times'] >= 15) {

						output_error('您今天发送短信已超过15条，今天将无法再次发送');

                    }

                }                

            }

        }



    try {

        $verify_code = rand(100,999).rand(100,999);



        $model_tpl = Model('mail_templates');

        $tpl_info = $model_tpl->getTplInfo(array('code'=>'modify_mobile'));

        $param = array();

        $param['site_name'] = C('site_name');

        $param['send_time'] = date('Y-m-d H:i',TIMESTAMP);

        $param['verify_code'] = $verify_code;

        $message    = ncReplaceText($tpl_info['content'],$param);

        $sms = new Sms();

        $result = $sms->send($mobile,$message);

        $result =1;

            if ($result) {

                $update_data = array();

                $update_data['auth_code'] = $verify_code;

                $update_data['send_acode_time'] = TIMESTAMP;

                $update_data['send_mb_time'] = TIMESTAMP;

                

                $update_data['send_acode_times'] = array('exp','send_acode_times+1');



                $update = $model_member->editMemberCommon($update_data,array('member_id'=>$this->member_info['member_id']));

                if (!$update) {

    				output_error('系统发生错误，如有疑问请与管理员联系');

                }

    			$output['sms_time'] = 60;

    			output_data($output);

            }else{

    			output_error('发送失败');

            }

        } catch (Exception $e) {

            output_error($e->getMessage());

        }

	}

    /**

    *发送邮件

    */



    public function send_email($email){

        $obj_validate = new Validate();

        

        $member_id = $this->member_info['member_id'];

        $obj_validate->validateparam = array(

            array("input"=>$email, "require"=>"true", 'validator'=>'email',"message"=>'请正确填写邮箱')

        );

        $error = $obj_validate->validate();

        if ($error != ''){

            output_error($error);

        }



        $model_member = Model('member');



        //发送频率验证

        $member_common_info = $model_member->getMemberCommonInfo(array('member_id'=>$member_id));

        if (!empty($member_common_info['send_email_time'])) {

            if (date('Ymd',$member_common_info['send_email_time']) != date('Ymd',TIMESTAMP)) {

                $data = array();

                $data['send_email_times'] = 0;

                $update = $model_member->editMemberCommon($data,array('member_id'=>$member_id));               

            } else {

                if (TIMESTAMP - $member_common_info['send_email_time'] < 188) {

                    output_error('请60秒以后再次发送邮件');

                } else {

                    if ($member_common_info['send_email_times'] >= 15) {

                        output_error('您今天发送邮件已超过15条，今天将无法再次发送');

                    }

                }                

            }

        }



    try {



                

            $verify_code = rand(100,999).rand(100,999);

            $site_name = C('site_name');

            $subject    = "【{$site_name}】- 申请绑定邮箱";

            $times = date('Y-m-d H:i:s',TIMESTAMP);

            $message  = "尊敬的{$_SESSION['member_name']}用户,您于{$times}申请绑定邮箱，动态码：{$verify_code}。";

            $emails  = new Email();

            $result = $emails->send_sys_email($email,$subject,$message);

            if ($result) {

                $data = array();

                $data['auth_code'] = $seed;

                $data['send_acode_time'] = TIMESTAMP;

                $data['send_email_time'] = TIMESTAMP;

                $data['send_acode_times'] = array('exp','send_acode_times+1');

                $update = $model_member->editMemberCommon($data,array('member_id'=>$_SESSION['member_id']));

                if (!$update) {

                    output_error('系统发生错误，如有疑问请与管理员联系');

                }

            $output['sms_time'] = 118;

            output_data($output);

            }else{

                output_error('发送失败');

            }

        } catch (Exception $e) {

            output_error($e->getMessage());

        }



  



    }



	/**

     * AJAX验证

     *

     */

	protected function check(){

        if (checkSeccode($_POST['codekey'],$_POST['captcha'])){

            return true;

        }else{

            return false;

        }

    }



}

