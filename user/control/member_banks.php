<?php
/**
 * 会员银行卡信息
 *
 *
 *
 * @author     gongbo
 * @date       20161109
 */
use shopec\Tpl;


defined('Inshopec') or exit('Access Invalid!');

class member_banksControl extends BaseMemberControl{

    /**
     * 会员银行卡信息
     *
     * @param
     * @return
     */
    public function banksOp() {

        //not check idracd
        $no_check_card =  array(
            "22643101040014374"
        );

        Language::read('member_banks');
        $lang   = Language::getLangContent();
        $member_banks = Model('member_banks');
        $memberInfo = Model('member')->getMemberInfo(array('member_id'=>$_SESSION['member_id']),'id_card,is_trust_name,member_truename');

        $member_info = $this->member_info;
        $member_info['security_level'] = Model('member')->getMemberSecurityLevel($member_info);

        $memberInfo = array_merge($member_info,$memberInfo);
        /*
         * 首页、编辑、添加均需要
         */
        Tpl::output('member_info',$memberInfo);

        /**
         * 判断页面类型
         */
        if (!empty($_GET['type'])){
            /**
             * 新增/编辑银行卡页面
             */

            if (intval($_GET['id']) > 0){
                /**
                 * 得到银行卡信息
                 */
                $bank_info = $member_banks->getMemberBankOne(array('ID'=>$_GET['id']));
                if ($bank_info['USER_ID'] != $_SESSION['member_id']){
                    showMessage($lang['member_banks_wrong_argument'],'index.php?con=member_banks&fun=banks','html','error');
                }
                /**
                 * 输出地址信息
                 */
                Tpl::output('banks_info',$bank_info);
            }
            /**
             * 增加/修改页面输出
             */
            Tpl::output('type',$_GET['type']);
            Tpl::output('banks_list',$this->banks_list());
            Tpl::showpage('member_banks.edit','null_layout');
            exit();
        }
        /**
         * 判断操作类型
         */
        if (chksubmit()){

            /*
             * 判断是否实名认证
             */
            if($memberInfo['is_trust_name'] != 1 || empty($memberInfo['id_card']) || empty($memberInfo['member_truename'])){
                showDialog($lang['member_please_trustname'],'index.php?con=member_trustname&fun=index','error');
            }

            /**
             * 验证表单信息
             */

            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["bank_card"],"require"=>"true","message"=>$lang['member_banks_card_input_receiver']),
                array("input"=>$_POST["bank_code"],"require"=>"true","message"=>$lang['member_banks_code_input_receiver']),
                array("input"=>$_POST["bank_name"],"require"=>"true","message"=>$lang['member_banks_code_input_receiver']),
                array("input"=>$_POST["bank_province_1"],"require"=>"true","message"=>$lang['member_banks_branch_input_receiver']),
                array("input"=>$_POST["bank_city_1"],"require"=>"true","message"=>$lang['member_banks_branch_input_receiver']),
                array("input"=>$_POST["bank_branch"],"require"=>"true","message"=>$lang['member_banks_branch_input_receiver']),
                array("input"=>$_POST["bank_info_id_1"],"require"=>"true","message"=>$lang['member_banks_branch_input_receiver'])
            );
            $error = $obj_validate->validate();
            if ($error != ''){
                showValidateError($error);
            }

            //银行卡验证
            if(!is_numeric($_POST["bank_card"])){
                showDialog($lang['member_banks_card_input_receiver'],'','error');
            }
            /*
             * 调用实名认证接口验证银行卡与身份证和姓名是否匹配
             */
            $data = array();
            $check_bank = $this->verifybankcard3(trim($_POST["bank_card"]),trim($_POST["bank_name"]));
            if(!$check_bank['flag']){
                if(!in_array($_POST['bank_card'],$no_check_card)){
                    showDialog($check_bank['msg'],'','error');
                }else{
                    $data['TYPE'] = $check_bank['msg']['type'];
                    $data['NATURE'] = $check_bank['msg']['nature'];
                    $data['KEFU'] = $check_bank['msg']['kefu'];
                    $data['LOGO_URL'] = $check_bank['msg']['logo'];
                }
                //showDialog($lang['member_id_name_nosame'],'','error');
            }else{
                $data['TYPE'] = $check_bank['msg']['type'];
                $data['NATURE'] = $check_bank['msg']['nature'];
                $data['KEFU'] = $check_bank['msg']['kefu'];
                $data['LOGO_URL'] = $check_bank['msg']['logo'];
            }


            $data['USER_ID']        = $_SESSION['member_id'];
            $data['BANK_CARD']      = _encrypt($_POST["bank_card"]);
            $data['USED']           = $_POST["is_default"]?1:0;
            $data['BANK_NAME']      = $_POST["bank_name"];
            $data['BANK_CODE']      = $_POST["bank_code"];
            $data['PROVINCE']       = $_POST["bank_province_1"];
            $data['CITY']           = $_POST["bank_city_1"];
            $data['BRANCH_NAME']    = $_POST["bank_branch"];
            $data['BANK_INFO_ID']   = $_POST["bank_info_id_1"];

            if ($_POST['is_default']) { //将原本默认的改为0
                $member_banks->editMemberBankOne(array('USER_ID'=>$_SESSION['member_id'],'USED'=>1),array('USED'=>0));
            }
            if (intval($_POST['id']) > 0){
                $cdn['ID']        =  array('neq',intval($_POST['id']));
                $cdn['USER_ID']   =  $_SESSION['member_id'];
                $cdn['BANK_CARD'] =  _encrypt($_POST["bank_card"]);
                if($member_banks->getMemberBankCount($cdn) >=1){
                    showDialog($lang['member_banks_isadd'],'','error');
                }
                $rs = $member_banks->editMemberBankOne(array('ID' => intval($_POST['id']),'USER_ID'=>$_SESSION['member_id']),$data);
                if (!$rs){
                    showDialog($lang['member_banks_modify_fail'],'','error');
                }
            }else {
                $count = $member_banks->getMemberBankCount(array('USER_ID'=>$_SESSION['member_id']));
                if ($count >= 10) {
                    showDialog($lang['member_banks_nums_max'],'','error');
                }
                $the_card_nums = $member_banks->getMemberBankCount(array('USER_ID'=>$_SESSION['member_id'],'BANK_CARD'=>$data['BANK_CARD']));
                if($the_card_nums >= 1){
                    showDialog($lang['member_banks_isadd'],'','error');
                }
                $rs = $member_banks->addMemberBankOne($data);
                if (!$rs){
                    showDialog($lang['member_banks_add_fail'],'','error');
                }
            }
            showDialog($lang['nc_common_op_succ'],'reload','js');
        }
        $del_id = isset($_GET['id']) ? intval(trim($_GET['id'])) : 0 ;
        if ($del_id > 0){
            $rs = $member_banks->delMemberBankOne(array('ID'=>$del_id,'USER_ID'=>$_SESSION['member_id']));
            if ($rs){
                showDialog(Language::get('member_banks_del_succ'),'index.php?con=member_banks&fun=banks','js');
            }else {
                showDialog(Language::get('member_banks_del_fail'),'','error');
            }
        }
        $banks_list = $member_banks->getMemberBankList(array('USER_ID'=>$_SESSION['member_id']));
        self::profile_menu('banks','banks');
        Tpl::output('banks_list',$banks_list);
        Tpl::showpage('member_banks.index');
    }

    /**
     * 用户中心右边，小导航
     *
     * @param string    $menu_type  导航类型
     * @param string    $menu_key   当前导航的menu_key
     * @return
     */
    private function profile_menu($menu_type,$menu_key='') {
        /**
         * 读取语言包
         */
        Language::read('member_layout');
        $menu_array = array();
        switch ($menu_type) {
            case 'banks':
                $menu_array = array(
                1=>array('menu_key'=>'banks','menu_name'=>'银行卡列表',   'menu_url'=>'index.php?con=member_banks&fun=banks'));
                break;
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }




    private function banks_list(){
        return Model('mstr_bank')->prefix('')->where(array('status'=>1))->field('bank_name,bank_code')->select();
    }
    /*
     * 获取开户行信息列表
     * deep         层级   1获取省列表 2获取市列表，3获取分行列表
     * bank_code    银行编号
     * province     省
     * city         市
     */
    public function bank_next_listOp(){
        $deep = intval($_GET['deep']);
        $bank_code = trim($_GET['bank_code']);
        $array['status']    = 201;
        $array['msg']       = '无该银行数据';
        if(empty($deep) || empty($bank_code)){
            exit(json_encode($array));
        }
        if($deep <0 || $deep>3){
            exit(json_encode($array));
        }
        $condition = array(
            'status'    =>  1,
            'bank_code' =>  $bank_code
        );

        switch($deep){
            case 1:                 //为第一次获取省列表
                $groupBy = 'province';
                $fields  = 'province';
                break;
            case 2:                 //为第一次获取市列表
                $groupBy = 'city';
                $fields  = 'city';
                $condition['province'] = trim($_GET['province']);
                if(empty(trim($_GET['province']))){
                    exit(json_encode($array));
                };
                break;
            case 3:                 //为第一次获取市列表
                $fields  = 'branch_name,id';
                $condition['city'] = trim($_GET['city']);
                if(empty(trim($_GET['city']))){
                    exit(json_encode($array));
                };
        }
        $rs = Model('mstr_bank_info')->prefix('')->where($condition)->field($fields)->group($groupBy)->select();
        if(!$rs){
            exit(json_encode($array));
        }
        $array = array(
            'status'   =>  200,
            'msg'      =>  '获取数据成功',
            'list'     =>   $rs
        );
        exit(json_encode($array));
    }

    /*
     * 身份证姓名银行卡接口验证
     * $id_card     身份证
     * $trustname   真实姓名
     * $bank_card   银行卡号
     * $bank_name   银行名字
     */
    private function verifybankcard3($bank_card,$bank_name){
        if(empty($bank_card)){
            $arr = array('flag'=>false,'msg'=>'银行卡号不能为空');
        }else{
            $curl_arr = _check_bank_card($bank_card);
            if($curl_arr['error_code'] == "0"){        //请求成功
                if($bank_name != $curl_arr['result']['bank']){
                    $arr = array('flag'=>false,'msg'=>'银行卡号与所选银行不匹配');
                }else{
                    $arr = array('flag'=>true,'msg'=>$curl_arr['result']);
                }
            }else{
                $arr = array('flag'=>false,'msg'=>$curl_arr['reason']);
            }
        }
        return $arr;
    }
}
