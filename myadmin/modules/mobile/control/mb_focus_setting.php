<?php
/**
 * 广告图管理
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
class mb_focus_settingControl extends SystemControl{

    public function __construct(){
        parent::__construct();
    }

    public function indexOp() {
        $this->listOp();
    }

    /**
     * 广告图管理
     */
    public function listOp(){

        if (chksubmit()) {

            if($_POST['data_type'] == 'add'){
                $obj_validate = new Validate();
                $obj_validate->validateparam = array(
                    array("input"=>$_FILES['focus_image']['name'], "require"=>"true", "message"=>'图片不能为空'),
                );
                $error = $obj_validate->validate();
                if ($error != '') {
                    showMessage($error);
                }

                if (!empty($_FILES['focus_image']['name'])){
                    $upload = new UploadFile();
                    $upload->set('default_dir',ATTACH_MOBILE.'/focus/');
                    $upload->set('allow_type', array('gif', 'jpg', 'jpeg', 'png'));
                    $result = $upload->upfile('focus_image');
                    if ($result){
                        $focus_image = $upload->file_name;
                    }else {
                        showMessage($upload->error,'','','error');
                    }
                }
            }elseif($_POST['data_type'] == 'edit'){
                if (!empty($_FILES['focus_image']['name'])){
                    $upload = new UploadFile();
                    $upload->set('default_dir',ATTACH_MOBILE.'/focus/');
                    $upload->set('allow_type', array('gif', 'jpg', 'jpeg', 'png'));
                    $result = $upload->upfile('focus_image');
                    if ($result){
                        $focus_image = $upload->file_name;
                    }else {
                        showMessage($upload->error,'','','error');
                    }
                }else{
                   $focus_image = $_POST['focus']; 
                }
                
            }

            $array = array();
            $array['focus_url']        = $_POST['image_data'];
            $array['focus_image']        = $focus_image;
            $array['focus_sort']        = intval($_POST['focus_sort']);
            $array['focus_type']        = $_POST['image_type'];

            $url = 'index.php?con=mb_focus_setting&fun=index';
            if($_POST['data_type'] == 'add'){
                $result = Model('mb_focus')->addMbFocus($array);
                if ($result){
                    $this->log('新增视频广告图',1);
                    showMessage('新增广告图保存成功',$url);
                }else {
                    $this->log('新增视频广告图',0);
                    showMessage('新增广告图保存失败',$url);
                }
            }elseif($_POST['data_type'] == 'edit'){
                $focus_id = intval($_POST['focus_id']);
                $result = Model('mb_focus')->editMbFocus($array,$focus_id);
                if ($result){
                    $this->log('更新视频广告图',1);
                    showMessage('更新广告图保存成功',$url);
                }else {
                    $this->log('更新视频广告图',0);
                    showMessage('更新广告图保存失败',$url);
                }
            }

        }

        $condition = array();
        $focus_list = Model('mb_focus')->getMbFocusList($condition);
        foreach($focus_list as $k => $v){
            if(!empty($v['focus_image'])) {
                $focus_list[$k]['focus_image_url'] = getMbFocusImageUrl($v['focus_image']);
                $focus_list[$k]['item_info'] = unserialize($v['focus_data']);
            }
        }
        Tpl::output('focus_list',$focus_list);

        //总共有多少条广告图
        $count_focus = Model('mb_focus')->getMbFocusCount(array());
        Tpl::output('count_focus',$count_focus);

        Tpl::showpage('mb_focus.index');

    }

    /**
     * 删除广告图
     */
    public function focus_delOp(){
        if ($_GET['id'] != ''){
            //删除广告图
            Model('mb_focus')->delMbFocusByID($_GET['id']);
            $this->log('删除广告图' . '[ID:' . $_GET['id'] . ']',1);
            exit(json_encode(array('state'=>true,'msg'=>'删除成功')));
        }else {
            exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
        }
    }



}
