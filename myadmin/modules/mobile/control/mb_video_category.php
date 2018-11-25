<?php
/**
 * 视频分类管理
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
class mb_video_categoryControl extends SystemControl{

    private $video_category;
    
    public function __construct(){
        parent::__construct();
        $this->video_category = Model('video_category');
    }

    public function indexOp() {
        $this->video_categoryOp();
    }

    /**
     * 分类管理
     */
    public function video_categoryOp(){

        $condition = array();
        $class_list = $this->video_category->getVideoCategoryList($condition,10);
        foreach($class_list as $k => $v){
            if(!empty($v['cate_image'])) {
                $class_list[$k]['cate_image'] = UPLOAD_SITE_URL . '/' . ATTACH_MOBILE . '/video_category' . '/' . $v['cate_image'];
                $class_list[$k]['is_recommend'] = $v['is_recommend']?'是':'否';
            }
        }

        Tpl::output('class_list',$class_list);
        Tpl::showpage('mb_video_category.index');

    }

    /**
     * 视频分类添加
     */
    public function video_category_addOp(){
        
        if (chksubmit()){
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["cate_name"], "require"=>"true", "message"=>'分类名称不能为空'),
                array("input"=>$_POST["cate_description"], "require"=>"true", "message"=>'分类描述不能为空'),
                array("input"=>$_FILES['cate_image']['name'], "require"=>"true", "message"=>'分类图片不能为空'),
                array("input"=>$_POST["cate_sort"], "require"=>"true", 'validator'=>'Number', "message"=>'请填写正确的分类排序'),
            );
            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error);
            }else {
                /**
                 * 上传图片
                 */
                if ($_FILES['cate_image']['name'] != ''){
                    $upload = new UploadFile();
                    $upload->set('default_dir',ATTACH_MOBILE.'/video_category');

                    $result = $upload->upfile('cate_image');
                    if ($result){
                        $_POST['cate_image'] = $upload->file_name;
                    }else {
                        showMessage($upload->error);
                    }
                }
                if(intval($_POST['recommend']) == 1){
                    $is_cate = $this->video_category->getRecommendCount(array());
                    if($is_cate == 3) {
                        showMessage('只允许推荐3个');
                    }
                }

                $insert_array = array();
                $insert_array['cate_name']        = $_POST['cate_name'];
                $insert_array['cate_parent_id']        = 0;
                $insert_array['cate_description']        = $_POST['cate_description'];
                $insert_array['cate_image']        = $_POST['cate_image'];
                $insert_array['cate_sort']        = intval($_POST['cate_sort']);
                $insert_array['is_recommend']        = intval($_POST['recommend']);
                $result = $this->video_category->addVideoCategory($insert_array);
                if ($result){
                    $url = array(
                        array(
                            'url'=>'index.php?con=mb_video_category&fun=video_category_add',
                            'msg'=>'继续新增分类',
                        ),
                        array(
                            'url'=>'index.php?con=mb_video_category&fun=index',
                            'msg'=>'返回分类列表',
                        )
                    );
                    $this->log('新增视频分类'.'['.$_POST['cate_name'].']',1);
                    showMessage('视频分类保存成功',$url,'html','succ',1,5000);
                }else {
                    $this->log('新增视频分类'.'['.$_POST['cate_name'].']',0);
                    showMessage('视频分类保存失败');
                }
            }
        }

        Tpl::showpage('mb_video_category.add');
    }

    /**
     * 编辑
     */
    public function video_category_editOp(){

        if (chksubmit()){
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["cate_name"], "require"=>"true", "message"=>'分类名称不能为空'),
                array("input"=>$_POST["cate_description"], "require"=>"true", "message"=>'分类描述不能为空'),
                array("input"=>$_POST["cate_sort"], "require"=>"true", 'validator'=>'Number', "message"=>'请填写正确的分类排序'),
            );
            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error);
            }
            /**
             * 上传图片
             */
            if ($_FILES['cate_image']['name'] != ''){
                $upload = new UploadFile();
                $upload->set('default_dir',ATTACH_MOBILE.'/video_category');

                $result = $upload->upfile('cate_image');
                if ($result){
                    $_POST['cate_image'] = $upload->file_name;
                }else {
                    showMessage($upload->error);
                }
            }

            if(intval($_POST['recommend']) == 1){
                $condition['cate_id'] = array('neq',intval($_POST['cate_id']));
                $is_cate = $this->video_category->getRecommendCount($condition);
                if($is_cate == 3) {
                    showMessage('只允许推荐3个');
                }
            }

            // 更新分类信息
            $where = array('cate_id' => intval($_POST['cate_id']));
            $update_array = array();
            $update_array['cate_name']        = $_POST['cate_name'];
            $update_array['cate_parent_id']        = 0;
            $update_array['cate_description']        = $_POST['cate_description'];
            if($_POST['cate_image']){
                $update_array['cate_image']        = $_POST['cate_image'];
            }
            $update_array['cate_sort']        = intval($_POST['cate_sort']);
            $update_array['is_recommend']        = intval($_POST['recommend']);

            $result = $this->video_category->editVideoCategory($update_array, $where);
            if (!$result){
                $this->log('编辑视频分类'.'['.$_POST['cate_name'].']',0);
                showMessage('视频分类更新失败');
            }

            /**
             * 删除图片
             */
            if (!empty($_POST['cate_image']) && !empty($class_array['cate_image'])){
                @unlink(BASE_ROOT_PATH.DS.DIR_UPLOAD.DS.ATTACH_MOBILE.'/video_category/'.$class_array['cate_image']);
            }

            $url = array(
                array(
                    'url'=>'index.php?con=mb_video_category&fun=video_category_edit&cate_id='.intval($_POST['cate_id']),
                    'msg'=>'继续编辑分类',
                ),
                array(
                    'url'=>'index.php?con=mb_video_category&fun=index',
                    'msg'=>'返回分类列表',
                )
            );
            $this->log('编辑视频分类'.'['.$_POST['cate_name'].']',1);
            showMessage('视频分类更新成功',$url,'html','succ',1,5000);
        }

        $class_array = $this->video_category->getVideoCategoryInfoById(intval($_GET['cate_id']));
        if (empty($class_array)){
            showMessage('参数非法');
        }

        Tpl::output('class_array',$class_array);
        Tpl::showpage('mb_video_category.edit');
    }

    /**
     * 删除分类
     */
    public function video_category_delOp(){
        if ($_GET['id'] != ''){
            //删除分类
            $this->video_category->delVideoCategoryByGcIdString($_GET['id']);
            $this->log('删除视频分类' . '[ID:' . $_GET['id'] . ']',1);
            exit(json_encode(array('state'=>true,'msg'=>'删除成功')));
        }else {
            exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
        }
    }


    /**
     * ajax操作
     */
    public function ajaxOp(){
        switch ($_GET['branch']){

            /**
             * 添加、修改操作中 检测类别名称是否有重复
             */
            case 'check_cate_name':

                $condition['cate_name'] = trim($_GET['cate_name']);
                $condition['cate_id'] = array('neq', intval($_GET['cate_id']));
                $class_list = $this->video_category->getVideoCategoryList($condition);
                if (empty($class_list)){
                    echo 'true';exit;
                }else {
                    echo 'false';exit;
                }
                break;
        }
    }
}
