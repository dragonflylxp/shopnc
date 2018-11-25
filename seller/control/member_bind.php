<?php

/**

 * 我的商城

 *

 *

 *

 *

 */







defined('Inshopec') or exit('Access Invalid!');



class member_bindControl extends mobileMemberControl {



    public function __construct(){

        parent::__construct();

    }



    /**

     * 第三登录设置

     */

    public function indexOp() {

 

        Tpl::output('member_info',$this->member_info);

        Tpl::output('web_seo',C('site_name').' - '.'第三登录设置');

        Tpl::showpage('member_bind');

    }

    /**

     * qq解邦定

     */

    public function unbindqqOp() {

        if(!$this->member_info['member_qqopenid']){

             @header('location: index.php?con=member_bind');exit;

        }

        Tpl::output('member_qqinfoarr',unserialize($this->member_info['member_qqinfoarr']));

        Tpl::output('web_seo',C('site_name').' - '.'QQ登录解绑');

        Tpl::showpage('member_unbindqq');

    }

    /**

     * 新浪解

     */

    public function unbindsinaOp() {

         if(!$this->member_info['member_sinaopenid']){

             @header('location: index.php?con=member_bind');exit;

        }

        Tpl::output('member_sinainfo',unserialize($this->member_info['member_sinainfo']));

        Tpl::output('web_seo',C('site_name').' - '.'新浪微博解绑');

        Tpl::showpage('member_unbindsina');

    }

    /**

     * QQ解绑

     */

    public function qqunbindOp(){

        //修改密码

        $model_member   = Model('member');

        $update_arr = array();

        if ($_POST['is_editpw'] == 'yes'){

            /**

             * 填写密码信息验证

             */

            $obj_validate = new Validate();

            $obj_validate->validateparam = array(

                array("input"=>$_POST["new_password"],"require"=>"true","validator"=>"Length","min"=>6,"max"=>50,"message"=>'新密码最少6位!'),

                array("input"=>$_POST["confirm_password"],"require"=>"true","validator"=>"Compare","operator"=>"==","to"=>$_POST["new_password"],"message"=>'两次输入密码不一致!'),

            );

            $error = $obj_validate->validate();

            if ($error != ''){

                output_error($error);

            }

            $update_arr['member_passwd'] = md5(trim($_POST['new_password']));

        }

        $update_arr['member_qqopenid'] = '';

        $update_arr['member_qqinfo'] = '';

        $edit_state     = $model_member->editMember(array('member_id'=>$_SESSION['member_id']),$update_arr);



        if(!$edit_state) {

            output_error('解绑失败!');

        }



        session_unset();

        session_destroy();

        output_data('解绑成功!');

     

    }



    /**

     * 新浪解绑

     */

    public function sinaunbindOp(){

        //修改密码

        $model_member   = Model('member');

        $update_arr = array();

        if ($_POST['is_editpw'] == 'yes'){

            /**

             * 填写密码信息验证

             */

            $obj_validate = new Validate();

            $obj_validate->validateparam = array(

                array("input"=>$_POST["new_password"],"require"=>"true","validator"=>"Length","min"=>6,"max"=>50,"message"=>'新密码最少6位!'),

                array("input"=>$_POST["confirm_password"],"require"=>"true","validator"=>"Compare","operator"=>"==","to"=>$_POST["new_password"],"message"=>'两次输入密码不一致!'),

            );

            $error = $obj_validate->validate();

            if ($error != ''){

                showMessage($error,'','html','error');

            }

            $update_arr['member_passwd'] = md5(trim($_POST['new_password']));

        }

        $update_arr['member_sinaopenid'] = '';

        $update_arr['member_sinainfo'] = '';

        $edit_state = $model_member->editMember(array('member_id'=>$_SESSION['member_id']),$update_arr);



        if(!$edit_state) {

            output_error('解绑失败!');

        }

        session_unset();

        session_destroy();

        output_data('解绑成功!');

    }

    /**

     * 微信绑定

     */

    public function weixinbindOp(){

        //获得用户信息

        if (trim($this->member_info['weixin_info'])){

            $this->member_info['weixin_infoarr'] = unserialize($this->member_info['weixin_info']);

        }

        Tpl::output('member_info',$this->member_info);

        //信息输出

        self::profile_menu('weixin_bind');

        Tpl::showpage('member_bind.weixin');

    }

    /**

     * 微信解绑

     */

    public function weixinunbindOp(){

        //修改密码

        $model_member = Model('member');

        $update_arr = array();

        if ($_POST['is_editpw'] == 'yes'){

            /**

             * 填写密码信息验证

             */

            $obj_validate = new Validate();

            $obj_validate->validateparam = array(

                array("input"=>$_POST["new_password"],      "require"=>"true","validator"=>"Length","min"=>6,"max"=>20,"message"=>Language::get('member_sconnect_password_null')),

                array("input"=>$_POST["confirm_password"],  "require"=>"true","validator"=>"Compare","operator"=>"==","to"=>$_POST["new_password"],"message"=>Language::get('member_sconnect_input_two_password_again')),

            );

            $error = $obj_validate->validate();

            if ($error != ''){

                showMessage($error,'','html','error');

            }

            $update_arr['member_passwd'] = md5(trim($_POST['new_password']));

        }

        $update_arr['weixin_unionid'] = '';

        $update_arr['weixin_info'] = '';

        $edit_state = $model_member->editMember(array('member_id'=>$_SESSION['member_id']),$update_arr);



        if(!$edit_state) {

            showMessage('保存失败','','html','error');

        }

        session_unset();

        session_destroy();

        showMessage('微信解绑成功',urlLogin('login', 'index', array('ref_url' => urlMember('member_bind', 'weixinbind'))));

    }

    

	

	



}

