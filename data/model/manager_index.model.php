<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * by:suijiaolong
 * Date: 2016/11/28/028
 * Time: 9:34
 */
defined('Inshopec') or exit('Access Invalid!');
class manager_indexModel extends Model {

    public function __construct(){
        parent::__construct('manager');
    }
    /**
     * 检查区域管理员的登录状态
     *
     */
    public function checkloginMember() {
        if($_SESSION['is_manager_login'] == '1') {
           @redirect(urlMember('manager_index','index'));//如果已经登录跳转到首页
            exit();
        }
    }
    /**
     *管理员登录
     */
    public function login($login_info) {
        if (process::islock('login')) {
            return array('error' => '您的操作过于频繁，请稍后再试');
        }
        process::addprocess('login');
        $user_name = $login_info['user_name'];
        $password = $login_info['password'];
        $obj_validate = new Validate();
        $obj_validate->validateparam = array(
            array(
                "input" => $user_name,
                "require" => "true",
                "message" => "请填写用户名"
            ),
            array(
                "input" => $user_name,
                "validator" => "username",
                "message" => "请填写字母、数字、中文、_"
            ),
            array(
                "input" => $user_name,
                "max" => "20",
                "min" => "3",
                "validator" => "length",
                "message" => "用户名长度要在6~20个字符"
            ),
            array(
                "input" => $password,
                "require" => "true",
                "message" => "密码不能为空"
            )
        );
        $error = $obj_validate->validate();
        if ($error != ''){
            return array('error' => $error);
        }
        $condition = array();
        $condition['member_name'] = $user_name;
        $condition['member_passwd'] = md5($password);
        $condition['member_type'] = 1;

        $member_info = $this->getManagerMember($condition);
        if (!empty($member_info)) {
            /*if(!$member_info['member_state']){
                return array('error' => '账号被停用');
            }*/
            process::clear('login');
           $update_info = array(
                'member_login_num'=> ($member_info['member_login_num']+1),
                'member_login_time'=> TIMESTAMP,
                'member_old_login_time'=> $member_info['member_login_time'],
                'member_login_ip'=> getIp(),
                'member_old_login_ip'=> $member_info['member_login_ip']
            );
            $this->editManagerMember(array('member_id'=>$member_info['member_id']),$update_info);
            return $member_info;
        } else {
            return array('error' => '登录失败');
        }
    }

    /**
     * 管理员登录
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getMemberInfo($condition, $field = '*', $master = false) {
        return $this->table('manager')->field($field)->where($condition)->master($master)->find();
    }
    /**
     * 管理员详细信息（查库）
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getManagerMember($condition, $field = '*', $master = false) {
        return $this->table('member')->field($field)->where($condition)->master($master)->find();
    }

    /**
     * 编辑区域管理人登录信息
     * @param array $condition
     * @param array $data
     */
    public function editManagerMember($condition, $data) {
        $update = $this->table('member')->where($condition)->update($data);
        return $update;
    }

    /**
     * 编辑区域管理人信息
     * @param array $condition
     * @param array $data
     */
    public function editmanager($condition, $data) {
        $update = $this->table('manager')->where($condition)->update($data);
        return $update;
    }
    /**
     * 登录时创建会话SESSION
     *
     * @param array $member_info 管理员信息
     */
    public function createSession($member_info = array(),$reg = false) {
        if (empty($member_info) || !is_array($member_info)) return ;
        //存入会员id
        $_SESSION['is_manager_login']   = '1';
        $_SESSION['manager_id']  = $member_info['member_id'];

        //存入管理员公司和联系方式
        $condition['member_id'] = $member_info['member_id'];
        $manager_info = $this->table('manager')->field('complete_company_name','company_name','contacts_email')->where($condition)->master($reg)->find();
        $_SESSION['company_name']= $manager_info['complete_company_name']?$manager_info['complete_company_name']:$manager_info['company_name'];
        $_SESSION['contacts_email']= $manager_info['contacts_email'];
    }

    /**
     * 取单条信息  管理员验证码信息
     * @param unknown $condition
     * @param string $fields
     */
    public function getManagerCommonInfo($condition = array(), $fields = '*') {
        return $this->table('manager_common')->where($condition)->field($fields)->find();
    }

    /**
     * 编辑管理员扩展表
     * @param unknown $data
     * @param unknown $condition
     * @return Ambigous <mixed, boolean, number, unknown, resource>
     */
    public function editManagerCommon($data,$condition) {
        return $this->table('manager_common')->where($condition)->update($data);
    }
}