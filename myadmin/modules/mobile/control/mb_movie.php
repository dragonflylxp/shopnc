<?php
/**
 * 直播管理
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
class mb_movieControl extends SystemControl
{
    /**
     * 直播导航
     */
    private $links = array(
        array('url'=>'con=mb_movie&fun=movie_list','text'=>'播主列表'),
        array('url'=>'con=mb_movie&fun=movie_verify_list','text'=>'直播申请'),
        array('url'=>'con=mb_movie&fun=movie_set','text'=>'直播设置'),
    );

    public function __construct()
    {
        parent::__construct();
    }

    public function indexOp(){
        $this->movie_listOp();
    }

    /**
     * 直播设置
     */
    public function movie_setOp(){

        if (chksubmit()){
            $update_array = array();
            $update_array['movie_verify'] = $_POST['movie_verify'];
            $result = Model('setting')->updateSetting($update_array);
            if ($result === true){
                $this->log('编辑直播设置',1);
                showMessage('保存成功');
            }else {
                $this->log('编辑直播设置',0);
                showMessage('保存失败');
            }
        }
        $list_setting = Model('setting')->getListSetting();
        Tpl::output('list_setting',$list_setting);

        Tpl::output('top_link',$this->sublink($this->links,'movie_set'));
        Tpl::showpage('mb_movie.setting');

    }

    /**
     * 直播审核
     */
    public function movie_verify_listOp(){
        Tpl::output('top_link',$this->sublink($this->links,'movie_verify_list'));
        Tpl::showpage('mb_movie.index');
    }

    public function get_xmlOp(){
        $model_movie = Model('member_movie');
        $condition = array();
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        $page = $_POST['rp'];
        $movie_list = $model_movie->getMemberMovieList($condition,$page);

        $data = array();
        $data['now_page'] = $model_movie->shownowpage();
        $data['total_num'] = $model_movie->gettotalnum();
        foreach($movie_list as $k => $v){
            $param = array();
            
            if($v['movie_verify'] == 0) { 
                $operation = "<a class='btn red' href='javascript:void(0);' onclick=\"fg_verify(".$v['movie_id'].")\"><i class='fa fa-cog fa-fw'></i>审核</a>";
            }else{
                $operation = '--';
            }

            $param['operation'] = $operation;
            $param['member_id'] = $v['member_id'];
            $member_info = Model('member')->getMemberInfo(array('member_id' => $v['member_id']));
            $param['member_name'] = $member_info['member_name'];
            $param['true_name'] = $v['true_name'];
            $param['card_number'] = $v['card_number'];
            $param['before_image_url'] = "<a href='javascript:void(0);' class='pic-thumb-tip' onMouseOut='toolTip()' onMouseOver='toolTip(\"<img src=".getMbMoiveImageUrl($v['card_before_image'],$v['member_id']).">\")'><i class='fa fa-picture-o'></i></a>";
            $param['behind_image_url'] = "<a href='javascript:void(0);' class='pic-thumb-tip' onMouseOut='toolTip()' onMouseOver='toolTip(\"<img src=".getMbMoiveImageUrl($v['card_behind_image'],$v['member_id']).">\")'><i class='fa fa-picture-o'></i></a>";
            if($v['movie_verify'] == 1){
                $verify_state = '审核已通过';
            }elseif($v['movie_verify'] == 2){
                $verify_state = '审核未通过';
            }
            $param['verify_state'] = $verify_state;

            $data['list'][$v['movie_id']] = $param;
        }

        echo Tpl::flexigridXML($data);exit();
    }

    /**
     * 申请直播删除
     */
    public function movie_delOp(){
        if ($_GET['id'] != ''){
            //删除直播
            Model('member_movie')->delMemberMovieByID($_GET['id']);
            $this->log('删除直播申请' . '[ID:' . $_GET['id'] . ']',1);
            exit(json_encode(array('state'=>true,'msg'=>'删除成功')));
        }else {
            exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
        }
    }

    /**
     * 审核直播
     */
    public function movie_verifyOp(){
        if (chksubmit()) {
            $movie_id = intval($_POST['movie_id']);
            if ($movie_id <= 0) {
                showDialog('参数错误', 'reload');
            }
            $update = array();
            if(intval($_POST['verify_state']) == 0){
                $verify_state = 2;
            }elseif(intval($_POST['verify_state']) == 1){
                $verify_state = 1;
            }
            $update['movie_verify'] = $verify_state;
            $update['verify_time'] = time();
            $update['verify_reason'] = $_POST['verify_reason'];

            $condition = array();
            $condition['movie_id'] = $movie_id;
            $result = Model('member_movie')->editMemberMovie($update, $condition);
            if($result){
                showDialog('保存成功', 'reload', 'succ', 'CUR_DIALOG.close();');
            }
        }

        Tpl::output('movie_id',intval($_GET['id']));
        Tpl::showpage('mb_movie.verify_remark', 'null_layout');
    }

    /**
     * 正在直播列表
     */
    public function movie_listOp(){
        Tpl::output('top_link',$this->sublink($this->links,'movie_list'));
        Tpl::showpage('mb_movie.list');
    }

    public function get_movie_xmlOp(){
        $model_video = Model('mb_video');
        $condition = array();
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        $condition['video_identity'] = 'movie';
        $condition['video_identity_type'] = 3;
        $page = $_POST['rp'];
        $movie_list = $model_video->getMbVideoList($condition,$page);

        $data = array();
        $data['now_page'] = $model_video->shownowpage();
        $data['total_num'] = $model_video->gettotalnum();
        foreach($movie_list as $k => $v){


            $param = array();

            if($v['movie_state'] == 1) {
                $operation = "<a class='btn red' href='javascript:void(0);' onclick=\"fg_state(".$v['video_id'].")\"><i class='fa fa-ban'></i>关闭</a>";
                $operation .= "<a class='btn red' href='javascript:void(0);' onclick=\"fg_see_movie(".$v['video_id'].")\"><i class='fa fa-eye'></i>预览</a>";
            }else{
                $operation = '--';
            }

            $param['operation'] = $operation;
            $param['member_id'] = $v['member_id'];
            $param['member_name'] = $v['member_name'];
            $cate_info = Model('video_category')->getVideoCategoryInfo(array( 'cate_id' => $v['cate_id']));
            $param['cate_name'] = $cate_info['cate_name'];
            $param['movie_title'] = $v['movie_title'];
            $param['movie_cover_img'] = "<a href='javascript:void(0);' class='pic-thumb-tip' onMouseOut='toolTip()' onMouseOver='toolTip(\"<img src=".getMbMoiveImageUrl($v['movie_cover_img'],$v['member_id']).">\")'><i class='fa fa-picture-o'></i></a>";
            $param['add_time'] = date('Y-m-d H:i:s',$v['add_time']);
            $param['state_desc'] = ($v['movie_state'] == 1) ? '' : '已关闭';


            $data['list'][$v['video_id']] = $param;
        }

        echo Tpl::flexigridXML($data);exit();
    }

    //关闭直播
    public function movie_offOp(){
        if ($_GET['id'] != ''){
            $video_info = Model('mb_video')->getMbVideoInfo(array('video_id' => $_GET['id']));
            //关闭直播
            Model('mb_video')->editMbVideo(array('movie_state' => 0),intval($_GET['id']));
            $this->log('关闭直播' . '[ID:' . $_GET['id'] . ']',1);
            //添加到直播日志表
            $model_movie_log = Model('member_movie_log');
            $param = array();
            $param['content'] = '关闭直播'.'[ID:'.$_GET['id'].']';
            $param['movie_off_time'] = TIMESTAMP;
            $param['member_id'] = $video_info['member_id'];
            $param['member_name'] = $video_info['member_name'];
            $param['movie_state'] = 0;
            $model_movie_log->addMemberMovieLog($param);
            exit(json_encode(array('state'=>true,'msg'=>'关闭成功')));
        }else {
            exit(json_encode(array('state'=>false,'msg'=>'关闭失败')));
        }
    }

    //预览直播
    public function see_movieOp(){
        if($_GET['id'] == ''){
            showMessage('参数非法');
        }
        $model_video = Model('mb_video');
        $movie_info = $model_video->getMbVideoInfo(array('video_id' => $_GET['id']));
        $movie_info['play_url'] = getMbMoiveUrl($movie_info['movie_rand'],'m3u8');
        $movie_info['cover_img_url'] = getMbMoiveImageUrl($movie_info['movie_cover_img'],$movie_info['member_id']);
        Tpl::output('movie_info' , $movie_info);
        Tpl::showpage('mb_movie.see','null_layout');
    }

}