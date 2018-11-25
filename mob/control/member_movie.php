<?php
/**
 * 我的直播
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
class member_movieControl extends mobileDistributeControl
{

    public function __construct(){
        parent::__construct();
    }

    /**
     * 直播申请
     */
    public function indexOp(){
        $model_movie = Model('member_movie');

        $condition = array();
        $condition['member_id'] = $this->member_info['member_id'];
        $member_movie_info = $model_movie->getMemberMovieInfo($condition);

        $movie_info = $this->_movie_valid();
        if(C('movie_verify') == 1){
            $movie_info['movie_verify'] = 0;
            $state_dialog = '请等待审核，审核需2个工作日';
        }else{
            $movie_info['movie_verify'] = 1;
            $state_dialog = '申请成功';
        }

        if(empty($member_movie_info)){
            $result = $model_movie->addMemberMovie($movie_info);
        }else{
            $condition = array();
            $condition['movie_id'] = $_POST['movie_id'];
            $condition['member_id'] = $this->member_info['member_id'];
            $result = $model_movie->editMemberMovie($movie_info,$condition);
        }

        if($result) {
            output_data(array('movie_id' => $result ,'state' => $state_dialog));
        } else {
            output_error('保存失败');
        }
    }

    /**
     * 验证直播
     */
    public function verify_movieOp(){
        $model_movie = Model('member_movie');
        $condition = array();
        $condition['member_id'] = $this->member_info['member_id'];
        $member_movie_info = $model_movie->getMemberMovieInfo($condition);
        if(!empty($member_movie_info)){
            if($member_movie_info['movie_verify'] == 1) {
                output_data("审核成功，可以开始直播了！");
            }elseif($member_movie_info['movie_verify'] == 2){
                $member_id = $this->member_info['member_id'];
                $extend_data['true_name'] = $member_movie_info['true_name'];
                $extend_data['card_number'] = $member_movie_info['card_number'];
                $extend_data['card_before_image'] = $member_movie_info['card_before_image'];
                $extend_data['card_behind_image'] = $member_movie_info['card_behind_image'];
                $extend_data['card_before_image_url'] = getMbMoiveImageUrl($member_movie_info['card_before_image'], $member_id);
                $extend_data['card_behind_image_url'] = getMbMoiveImageUrl($member_movie_info['card_behind_image'], $member_id);
                $extend_data['is_agree'] = 1;
                $extend_data['member_id'] = $member_id;
                $extend_data['movie_id'] = $member_movie_info['movie_id'];
                $extend_data['error'] = '未通过审核，未通过原因：'.$member_movie_info['verify_reason'].'请重新填写';
                if(!empty($extend_data)){
                    header("Access-Control-Allow-Origin:*");
                    echo json_encode(array('code' => 400 , 'datas' => $extend_data));die;
                }
                
            }elseif($member_movie_info['movie_verify'] == 0){
                output_error('请等待管理员审核');
            }
        }else{
            output_data("1");
        }
    }

    /**
     * 验证数据
     */
    private function _movie_valid() {
        $obj_validate = new Validate();
        $obj_validate->validateparam = array(
            array("input"=>$_POST["true_name"],"require"=>"true","message"=>'姓名不能为空'),
            array("input"=>$_POST["card_number"],"require"=>"true","message"=>'身份证号码不能为空'),
            array("input"=>$_POST["card_before_image"],"require"=>"true","message"=>'身份证正面照不能为空'),
            array("input"=>$_POST['card_behind_image'],'require'=>'true','message'=>'身份证反面照不能为空')
        );
        $error = $obj_validate->validate();
        if ($error != ''){
            output_error($error);
        }

        //验证身份证号码
        if(!preg_match('/^(\d{15}$|^\d{18}$|^\d{17}(\d|X|x))$/',$_POST['card_number'])){
            output_error('请填写正确的身份证号码');
        }

        if($_POST['is_agree'] != 1){
            output_error('请先阅读并同意《主播公约》');
        }

        $data = array();
        $data['member_id'] = $this->member_info['member_id'];
        $data['true_name'] = $_POST['true_name'];
        $data['card_number'] = $_POST['card_number'];
        $data['card_before_image'] = $_POST['card_before_image'];
        $data['card_behind_image'] = $_POST['card_behind_image'];
        $data['add_time'] = time();
        
        return $data;
    }

    /**
     * 上传图片
     *
     * @param
     * @return
     */
    public function file_uploadOp() {

        $member_id  = $this->member_info['member_id'];

        $model = Model();
        // 验证图片数量
        $count = $model->table('sns_albumpic')->where(array('member_id'=>$member_id))->count();
        if(C('malbum_max_sum') != 0 && $count >= C('malbum_max_sum')){
            output_error('已经超出允许上传图片数量，不能在上传图片！');
        }

        /**
         * 上传图片
         */
        $upload = new UploadFile();
        $upload_dir = ATTACH_MOBILE.DS.'movie'.DS.$member_id.DS;

        $upload->set('default_dir',$upload_dir);
        $thumb_width    = '240,1024';
        $thumb_height   = '2048,1024';

        $upload->set('max_size',C('image_max_filesize'));
        $upload->set('thumb_width', $thumb_width);
        $upload->set('thumb_height',$thumb_height);
        $upload->set('fprefix',$member_id);
        $upload->set('thumb_ext', '_240,_1024');
        $result = $upload->upfile('file');
        if (!$result){
            output_error($upload->error);
        }

        $img_path = $upload->file_name;
        list($width, $height, $type, $attr) = getimagesize(BASE_UPLOAD_PATH.DS.ATTACH_MOBILE.DS.'movie'.DS.$member_id.DS.$img_path);

        $image = explode('.', $_FILES["file"]["name"]);

        $model_sns_alumb = Model('sns_album');
        $ac_id = $model_sns_alumb->getSnsAlbumClassDefault($member_id);
        $insert = array();
        $insert['ap_name']      = $image['0'];
        $insert['ac_id']        = $ac_id;
        $insert['ap_cover']     = $img_path;
        $insert['ap_size']      = intval($_FILES['file']['size']);
        $insert['ap_spec']      = $width.'x'.$height;
        $insert['upload_time']  = TIMESTAMP;
        $insert['member_id']    = $member_id;
        $result = $model->table('sns_albumpic')->insert($insert);

        $data = array();
        $data['file_id'] = $result;
        $data['file_name'] = $img_path;
        $data['origin_file_name'] = $_FILES["file"]["name"];
        $data['file_url'] = getMbMoiveImageUrl($img_path, $member_id);
        output_data($data);
    }


    /**
     * 直播
     */
    public function movie_sendOp(){

        $member_id = $this->member_info['member_id'];
        $member_name = $this->member_info['member_name'];

        //生成随机数
        $arr=rand(1,99999);

        //生成直播随机名
        $movie_rand = $member_name.'*'.$_POST['cate_id'].'*'.TIMESTAMP.'*'.$arr;//播主名称*分类id*时间戳*随机数

        //是否通过管理员审核
        $model_movie = Model('member_movie');
        $movie_info = $model_movie->getMemberMovieInfo(array('member_id' => $member_id));
        if($movie_info['movie_verify'] == 0){
            output_error('请等待管理员审核');
        }

        //是否正在直播
        $model_video = Model('mb_video');
        $video_info = $model_video->getMbVideoInfo(array('member_id' => $member_id,'movie_state' => 1));
        if(!empty($video_info)){
            output_error('您正在直播中...');
        }
        

        //添加到视频列表
        $insert_array['movie_rand'] = $movie_rand;
        $insert_array['member_id'] = $member_id;
        $insert_array['member_name'] = $member_name;
        $insert_array['video_identity'] = 'movie';
        $insert_array['video_identity_type'] = 3;
        $insert_array['cate_id'] = $_POST['cate_id'];
        $insert_array['movie_state'] = 1;
        $insert_array['movie_title'] = $_POST['movie_title'];
        $insert_array['movie_cover_img'] = $_POST['movie_cover_img'];
        $insert_array['add_time'] = TIMESTAMP;
        $result = $model_video->addMbVideo($insert_array);
        if(!$result){
            output_error('保存视频列表失败！');
        }

        //添加到直播日志表
        $model_movie_log = Model('member_movie_log');
        $param = array();
        $param['content'] = '添加直播'.'[ID:'.$result.']';
        $param['member_id'] = $member_id;
        $param['member_name'] = $member_name;
        $param['movie_state'] = 1;
        $param['movie_addtime'] = TIMESTAMP;
        $param['cate_id'] = $_POST['cate_id'];
        $param['movie_title'] = $_POST['movie_title'];
        $param['movie_cover_img'] = $_POST['movie_cover_img'];
        $model_movie_log->addMemberMovieLog($param);

        if(!C('live.open')){
            output_error('直播未开启');
        }else{
            $movie_url = 'rtmp://'.C('live.liveUrl').'/'.C('live.AppName').'/'.$movie_rand.'?vhost='.C('live.accUrl');
            //直播推流地址和会员头像、会员信息、视频列表ID
            $data['movie_url'] = $movie_url;
            $data['member_avatar_url'] = getMemberAvatar($this->member_info['member_avatar']);
            $data['member_id'] = $member_id;
            $data['member_name'] = $member_name;
            $data['video_id'] = $result;

            output_data($data);
        }
        

    }


    /**
     * 退出直播
     */
    public function movie_logoutOp(){
        $member_id = $this->member_info['member_id'];
        $member_name = $this->member_info['member_name'];
        //编辑视频列表该条数据
        $model_video = Model('mb_video');
        $condition = array();
        $condition['video_id'] = $_GET['video_id'];
        $condition['member_id'] = $member_id;//播主ID
        $update = array();
        $update['movie_state'] = 0;//更新直播状态为0
        $model_video->editMbVideoList($update,$condition);
        //更新日志表
        $model_movie_log = Model('member_movie_log');
        $param = array();
        $param['content'] = '退出直播';
        $param['member_id'] = $member_id;
        $param['member_name'] = $member_name;
        $param['movie_state'] = 0;
        $param['movie_logout_time'] = TIMESTAMP;
        $model_movie_log->addMemberMovieLog($param);

        $model_live = Model('dis_msg');
        $live_id = $_GET['video_id'];
        $table_name = $model_live->getLiveTableName($live_id);
        $model_live->updateLiveLog($table_name,array('live_id'=>$live_id),array('member_state'=>0));
        output_data("1");
    }





}