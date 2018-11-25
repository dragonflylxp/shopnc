<?php
/**
* Created by PhpStorm.
* User: zhengce
* Date: 2016/11/25
*/
use shopec\Tpl;

defined('Inshopec') or exit('Access Invalid!');
class managerControl extends SystemControl
{
    const EXPORT_SIZE = 1000;

    public function __construct()
    {
        parent::__construct();
        Language::read('member');
    }

    //管理人列表
    public function indexOp(){
        Tpl::showpage('manager.index');
    }

    /**
     * 输出XML数据
     */
    public function get_xmlOp() {
        $manager_model = Model('manager_member');
        // 设置页码参数名称
        $condition = array();
        if ($_POST['query'] != '') {
            if($_POST['query'] == 'manager_id'){
                $condition[$_POST['qtype']] = $_POST['query'];
            }else{
                $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
            }
        }
        $order = '';
        $param = array('manager_id');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }

        $page = $_POST['rp'];
        $manager_list = $manager_model->getManagerList($condition,'*',$_POST['rp'],$order);
        $data = array();
        $data['now_page'] = $manager_model->shownowpage();
        $data['total_num'] = $manager_model->gettotalnum();
        foreach ($manager_list as $manager_info) {
            $list = array();
            $condition = array();
            $list['operation'] = "<a class=\"btn orange\" href=\"index.php?con=manager&fun=edit&manager_id={$manager_info['manager_id']}&member_id={$manager_info['member_id']}\"><i class=\"fa fa-gavel\"></i>编辑</a>".
                "<a class='btn red' href='javascript:void(0);' onclick=\"fg_del(".$manager_info['manager_id'].",".$manager_info['member_id'].")\"><i class='fa fa-trash-o'></i>删除</a>";
//            $list['manager_id'] = $manager_info['manager_id'];
            $list['member_id'] = $manager_info['member_id'];
            $list['member_name'] = $manager_info['member_name'];
            $list['company_name'] = $manager_info['company_name'];
            $list['add_time'] = date('Y-m-d H:i:s',$manager_info['add_time']);
            $condition['uid'] = $manager_info['member_id'];
            $list['is_bind'] = $manager_model->checkIsManagerMember($condition)? '<span class="yes"><i class="fa fa-check-circle"></i>是</span>' : '<span class="no"><i class="fa fa-ban"></i>否</span>';
            $list['apply_state'] = managerState($manager_info['apply_state']);
            $data['list'][$manager_info['manager_id']] = $list;
        }
        exit(Tpl::flexigridXML($data));
    }


    /**
     * 添加管理人
     */
    public function addOp(){
        $lang   = Language::getLangContent();
        $model_member = Model('member');
        $model_manager = Model('manager_member');
        $manager_index = Model('manager_index');
        /**
         * 保存
         */
        if (chksubmit()){
            /**
             * 验证
             */
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["member_name"], "require"=>"true", "message"=>$lang['member_add_name_null']),
                array("input"=>$_POST["member_passwd"], "require"=>"true", "message"=>'密码不能为空'),
                array("input"=>$_POST["company_name"], "require"=>"true", "message"=>'公司名不能为空'),
            );
            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error);
            }else {
                $insert_array = array();
                $insert_array['member_name']    = trim($_POST['member_name']);
                $insert_array['member_passwd']  = trim($_POST['member_passwd']);
                $insert_array['member_type']  = 1;
                $insert_array['member_email']   = trim($_POST['member_email']);
                $insert_array['member_truename']   =  trim($_POST['company_name']);
                $result = $model_manager->addMember($insert_array);
                if ($result){
                    $insert['member_id'] = $result;
                    $insert['apply_state'] = 10;
                    $insert['company_name'] = trim($_POST['company_name']);
                    $insert['complete_company_name'] = trim($_POST['company_name']);
                    $insert['add_time'] = time();
                    $model_manager->addManager($insert,$result);
                    $url = array(
                        array(
                            'url'=>'index.php?con=manager&fun=index',
                            'msg'=>'管理人列表',
                        ),
                        array(
                            'url'=>'index.php?con=manager&fun=add',
                            'msg'=>'继续添加',
                        ),
                    );
                    $this->log('添加管理人'.'[ '.$_POST['member_name'].']',1);
                    showMessage("管理人添加成功！",$url);
                }else {
                    showMessage("管理人添加失败！");
                }
            }
        }
        Tpl::showpage('manager.add');
    }



    /**
     * 管理人编辑
     */

    public function editOp(){
        $lang   = Language::getLangContent();
        $model_member = Model('member');
        $manager_model = Model('manager_member');
        /**
         * 保存
         */
        if (chksubmit()){
            /**
             * 验证
             */
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["member_email"], "require"=>"true", 'validator'=>'Email', "message"=>$lang['member_edit_valid_email']),
            );
            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error);
            }else {
                $update_array = array();
                $update_array['member_id']          = intval($_POST['member_id']);
                if (!empty($_POST['member_passwd'])){
                    $update_array['member_passwd'] = md5($_POST['member_passwd']);
                }
                $update_array['member_email']       = $_POST['member_email'];
                $update_array['member_truename']       = $_POST['company_name'];

                $result = $model_member->editMember(array('member_id'=>intval($_POST['member_id'])),$update_array);
                if ($result){
                    $update['company_name'] = trim($_POST['company_name']);
                    $update['complete_company_name'] = trim($_POST['company_name']);
                    $mcondition['manager_id'] = $_REQUEST['manager_id']?$_REQUEST['manager_id']:0;
                    $manager_model->editManager($update,$mcondition);
                    $ps_update['USER_NAME'] = trim($_POST['company_name']);
                    $pcondition['USER_ID'] = intval($_POST['member_id']);
                    $manager_model->editPs($ps_update,$pcondition);
                    $url = array(
                        array(
                            'url'=>'index.php?con=manager&fun=index',
                            'msg'=>'管理人列表',
                        ),
                        array(
                            'url'=>'index.php?con=manager&fun=add',
                            'msg'=>'继续添加',
                        ),
                    );
                    $condition = array();
                    $condition['member_id'] = $_POST['member_id'];
                    $manager_account = $manager_model->getManagerName($condition);
                    $this->log("修改管理人".$manager_account);
                    showMessage("编辑管理人成功",$url);
                }else {
                    showMessage($lang['member_edit_fail']);
                }
            }
        }
        //获取管理人信息
        $condition = array();
        $condition['manager_id'] = $_REQUEST['manager_id']?$_REQUEST['manager_id']:0;
        $manager_info = $manager_model->getManagerInfo($condition);
        Tpl::output('manager_info',$manager_info);
        Tpl::showpage('manager.edit');
    }


    //删除管理人
    public function deleteOp(){
        $lang   = Language::getLangContent();
        $manager_model = Model('manager_member');
        $condition = array();
        $mcondition = array();
        $condition['uid'] = $_REQUEST['member_id']?$_REQUEST['member_id']:0;
        if($manager_model->checkIsManagerMember($condition)){
            showMessage("管理人已绑定地区，不能删除");
        }else{
            $condition = array();
            $condition['manager_id'] = $_REQUEST['manager_id']?$_REQUEST['manager_id']:0;
            $mcondition['member_id'] = $_REQUEST['member_id']?$_REQUEST['member_id']:0;
            $manager_account = $manager_model->getManagerName($mcondition);
            $result = $manager_model->deleteManager($condition,$mcondition);
            if($result){
                $this->log("删除管理人".$manager_account);
                showMessage("删除管理人成功");
            }else{
                showMessage("删除管理人失败");
            }
        }
    }

}

/**
 * 取得管理人申请文字输出形式
 *
 * @param array $state
 * @return string 描述输出
 */
function managerState($state) {
    return str_replace(
        array('10','20','30','40'),
        array('未提交','待审核','已通过','未通过'),
        $state);
}