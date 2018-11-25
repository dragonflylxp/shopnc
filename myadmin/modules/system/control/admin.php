<?php
/**
 * 权限管理
 *
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

class adminControl extends SystemControl{
    private $links = array(
        array('url'=>'con=admin&fun=admin','lang'=>'limit_admin'),
        array('url'=>'con=admin&fun=gadmin','lang'=>'limit_gadmin'),
    );
    public function __construct(){
        parent::__construct();
        Language::read('admin');
    }

    public function indexOp() {
        $this->adminOp();
    }

    /**
     * 管理员列表
     */
    public function adminOp(){
        Tpl::output('top_link',$this->sublink($this->links,'admin'));
        Tpl::showpage('admin.index');
    }

    /**
     * 管理员列表
     */
    public function get_admin_xmlOp(){
        $page = intval($_POST['rp']);
        if ($page < 1) {
            $page = 15;
        }
        $model = Model();
        $list = $model->table('admin,gadmin')->join('left join')->on('gadmin.gid=admin.admin_gid')->page($page)->select();

        $out_list = array();
        if (!empty($list) && is_array($list)){
            $fields_array = array('admin_name','admin_login_time','admin_login_num','gname');
            foreach ($list as $k => $v){
                $out_array = getFlexigridArray(array(),$fields_array,$v);
                $out_array['admin_login_time'] = $v['admin_login_time'] ? date('Y-m-d H:i:s',$v['admin_login_time']) : $lang['admin_index_login_null'];
                $operation = '';
                if ($v['admin_is_super'] != 1) {
                    $operation .= '<a class="btn red" href="javascript:fg_operation_del('.$v['admin_id'].');"><i class="fa fa-trash-o"></i>删除</a>';
                    $operation .= '<a class="btn blue" href="index.php?con=admin&fun=admin_edit&admin_id='.$v['admin_id'].'"><i class="fa fa-pencil-square-o"></i>'.L('nc_edit').'</a>';
                }else {
                    $operation = '--';
                }
                $out_array['operation'] = $operation;
                $out_list[$v['admin_id']] = $out_array;
            }
        }

        $data = array();
        $data['now_page'] = $model->shownowpage();
        $data['total_num'] = $model->gettotalnum();
        $data['list'] = $out_list;
        echo Tpl::flexigridXML($data);exit();
    }

    /**
     * 管理员删除
     */
    public function admin_delOp(){
        if (!empty($_GET['admin_id'])){
            if ($_GET['admin_id'] == 1){
                exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
            }
            Model()->table('admin')->where(array('admin_id'=>intval($_GET['admin_id'])))->delete();
            $this->log(L('nc_delete,limit_admin').'[ID:'.intval($_GET['admin_id']).']',1);
            exit(json_encode(array('state'=>true,'msg'=>'删除成功')));
        }else {
            exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
        }
    }

    /**
     * 管理员添加
     */
    public function admin_addOp(){
        if (chksubmit()){
            $limit_str = '';
            $model_admin = Model('admin');
            $param['admin_name'] = $_POST['admin_name'];
            $param['admin_gid'] = $_POST['gid'];
            $param['admin_password'] = md5($_POST['admin_password']);
            $rs = $model_admin->addAdmin($param);
            if ($rs){
                $this->log(L('nc_add,limit_admin').'['.$_POST['admin_name'].']',1);
                showMessage(L('nc_common_save_succ'),'index.php?con=admin&fun=admin');
            }else {
                showMessage(L('nc_common_save_fail'));
            }
        }

        //得到权限组
        $gadmin = Model('gadmin')->field('gname,gid')->select();
        Tpl::output('gadmin',$gadmin);
        Tpl::output('top_link',$this->sublink($this->links,'admin_add'));
        Tpl::output('limit',$this->permission());
        Tpl::showpage('admin.add');
    }

    /**
     * ajax操作
     */
    public function ajaxOp(){
        switch ($_GET['branch']){
            //管理人员名称验证
            case 'check_admin_name':
                $model_admin = Model('admin');
                $condition['admin_name'] = $_GET['admin_name'];
                $list = $model_admin->infoAdmin($condition);
                if (!empty($list)){
                    exit('false');
                }else {
                    exit('true');
                }
                break;
            //权限组名称验证
            case 'check_gadmin_name':
                $condition = array();
                if (is_numeric($_GET['gid'])){
                    $condition['gid'] = array('neq',intval($_GET['gid']));
                }
                $condition['gname'] = $_GET['gname'];
                $info = Model('gadmin')->where($condition)->find();
                if (!empty($info)){
                    exit('false');
                }else {
                    exit('true');
                }
                break;
        }
    }

    /**
     * 设置管理员权限
     */
    public function admin_editOp(){
        if (chksubmit()){
            //没有更改密码
            if ($_POST['new_pw'] != ''){
                $data['admin_password'] = md5($_POST['new_pw']);
            }
            $data['admin_id'] = intval($_GET['admin_id']);
            $data['admin_gid'] = intval($_POST['gid']);
            //查询管理员信息
            $admin_model = Model('admin');
            $result = $admin_model->updateAdmin($data);
            if ($result){
                $this->log(L('nc_edit,limit_admin').'[ID:'.intval($_GET['admin_id']).']',1);
                showMessage(Language::get('admin_edit_success'),'index.php?con=admin&fun=admin');
            }else{
                showMessage(Language::get('admin_edit_fail'),'index.php?con=admin&fun=admin');
            }
        }else{
            //查询用户信息
            $admin_model = Model('admin');
            $admininfo = $admin_model->getOneAdmin(intval($_GET['admin_id']));
            if (!is_array($admininfo) || count($admininfo)<=0){
                showMessage(Language::get('admin_edit_admin_error'),'index.php?con=admin&fun=admin');
            }
            Tpl::output('admininfo',$admininfo);
            Tpl::output('top_link',$this->sublink($this->links,'admin'));

            //得到权限组
            $gadmin = Model('gadmin')->field('gname,gid')->select();
            Tpl::output('gadmin',$gadmin);
            Tpl::showpage('admin.edit');
        }
    }

    /**
     * 取得所有权限项
     *
     * @return array
     */
    private function permission() {
        return rkcache('admin_menu', true);
    }

    /**
     * 权限组
     */
    public function gadminOp(){
        $model = Model('gadmin');
        if (chksubmit()){
            if (@in_array(1,$_POST['del_id'])){
                showMessage(L('admin_index_not_allow_del'));
            }

            if (!empty($_POST['del_id'])){
                if (is_array($_POST['del_id'])){
                    foreach ($_POST['del_id'] as $k => $v){
                        $model->where(array('gid'=>intval($v)))->delete();
                    }
                }
                $this->log(L('nc_delete,limit_gadmin').'[ID:'.implode(',',$_POST['del_id']).']',1);
                showMessage(L('nc_common_del_succ'));
            }else {
                showMessage(L('nc_common_del_fail'));
            }
        }
        $list = $model->limit(100)->select();

        Tpl::output('list',$list);
        Tpl::output('page',$model->showpage());

        Tpl::output('top_link',$this->sublink($this->links,'gadmin'));
        Tpl::showpage('gadmin.index');
    }

    /**
     * 添加权限组
     */
    public function gadmin_addOp(){
        if (chksubmit()){
            $model = Model('gadmin');
            $data['limits'] = encrypt(serialize($_POST['permission']),MD5_KEY.md5($_POST['gname']));
            $data['gname'] = $_POST['gname'];
            if ($model->insert($data)){
                $this->log(L('nc_add,limit_gadmin').'['.$_POST['gname'].']',1);
                showMessage(L('nc_common_save_succ'),'index.php?con=admin&fun=gadmin');
            }else {
                showMessage(L('nc_common_save_fail'));
            }
        }
        Tpl::output('top_link',$this->sublink($this->links,'gadmin_add'));
        Tpl::output('limit',$this->permission());
        Tpl::showpage('gadmin.add');
    }

    /**
     * 设置权限组权限
     */
    public function gadmin_editOp(){
        $model = Model('gadmin');
        $gid = intval($_GET['gid']);

        $ginfo = $model->getby_gid($gid);
        if (empty($ginfo)){
            showMessage(L('admin_set_admin_not_exists'));
        }
        if (chksubmit()){
            $limit_str = '';
            $limit_str = encrypt(serialize($_POST['permission']),MD5_KEY.md5($_POST['gname']));
            $data['limits'] = $limit_str;
            $data['gname']  = $_POST['gname'];
            $update = $model->where(array('gid'=>$gid))->update($data);
            if ($update){
                $this->log(L('nc_edit,limit_gadmin').'['.$_POST['gname'].']',1);
                showMessage(L('nc_common_save_succ'),'index.php?con=admin&fun=gadmin');
            }else {
                showMessage(L('nc_common_save_fail'));
            }
        }

        //解析已有权限
        $hlimit = decrypt($ginfo['limits'],MD5_KEY.md5($ginfo['gname']));
        $ginfo['limits'] = unserialize($hlimit);
        Tpl::output('ginfo',$ginfo);
        Tpl::output('limit',$this->permission());
        Tpl::output('top_link',$this->sublink($this->links,'gadmin'));
        Tpl::showpage('gadmin.edit');
    }

    /**
     * 组删除
     */
    public function gadmin_delOp(){
        if (is_numeric($_GET['gid'])){
            Model('gadmin')->where(array('gid'=>intval($_GET['gid'])))->delete();
            $this->log(L('nc_delete,limit_gadmin').'[ID'.intval($_GET['gid']).']',1);
            showMessage(Language::get('nc_common_op_succ'),'index.php?con=admin&fun=gadmin');
        }else {
            showMessage(L('nc_common_op_fail'));
        }
    }
}
