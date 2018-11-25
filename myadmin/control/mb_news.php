<?php
/**
 * 手机端 - 资讯
 *
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net
 * @link       http://www.shopec.net
 * @since      File available since Release v1.1
 */

use shopec\Tpl;

defined('Inshopec') or exit('Access Invalid!');

class mb_newsControl{

    /**
     * 验证商品是否重复
     */
    public function check_nameOp() {
        $condition = array();
        $condition['video_identity'] = 'news';
        $condition['news_name'] = $_GET['news_name'];
        $condition['video_id'] = array('neq',intval($_GET['video_id']));
        $model_video = Model('mb_video');
        $news = $model_video->getMbVideoInfo($condition);
        if (empty($news)) {
            echo 'true';exit;
        } else {
            echo 'false';exit;
        }
    }

    /**
     * 上传图片
     */
    public function image_uploadOp() {
        if ($_FILES['news_image']['name'] != ''){
            $upload = new UploadFile();
            $upload->set('default_dir',ATTACH_MOBILE.'/news');
            $upload->set('thumb_ext', GOODS_IMAGES_EXT);
            $upload->set('allow_type', array('gif', 'jpg', 'jpeg', 'png'));
            $result = $upload->upfile('news_image');
            if ($result){
                $img_path = $upload->file_name;
                $data = array();
                $data ['thumb_name'] = getMbNewsImageUrl($img_path);
                $data ['name']      = $img_path;
                echo json_encode($data);die;
            }else {
                echo json_encode(array('error' => $upload->error));die;
            }
        }
    }
    /**
     * 手机端上传图片
     */
    public function mobile_image_uploadOp() {
        $store_id = 0;
        // 判断图片数量是否超限
        $model_album = Model('album');
        if ($album_limit > 0) {
            $album_count = $model_album->getCount(array('store_id' => $store_id));
            if ($album_count >= $album_limit) {
                echo json_encode(array('error' => '您上传图片数达到上限，请升级您的店铺或跟管理员联系'));die;
            }
        }

        $class_info = $model_album->getOne(array('store_id' => $store_id, 'is_default' => 1), 'album_class');
        // 上传图片
        $upload = new UploadFile();
        $upload->set('default_dir', ATTACH_GOODS . DS . $store_id . DS . $upload->getSysSetPath());
        $upload->set('max_size', C('image_max_filesize'));

        $upload->set('thumb_width', GOODS_IMAGES_WIDTH);
        $upload->set('thumb_height', GOODS_IMAGES_HEIGHT);
        $upload->set('thumb_ext', GOODS_IMAGES_EXT);
        $upload->set('fprefix', $store_id);
        $upload->set('allow_type', array('gif', 'jpg', 'jpeg', 'png'));
        $result = $upload->upfile('add_album',true);
        if (!$result) {
            echo json_encode(array('error' => $upload->error));die;
        }

        $img_path = $upload->getSysSetPath() . $upload->file_name;

        // 取得图像大小
        if (!C('oss.open')) {
            list($width, $height, $type, $attr) = getimagesize(BASE_UPLOAD_PATH . '/' . ATTACH_GOODS . '/' . $store_id . DS . $img_path);
        } else {
            list($width, $height, $type, $attr) = getimagesize(C('oss.img_url') . '/' . ATTACH_GOODS . '/' . $store_id . DS . $img_path);
        }

        // 存入相册
        $image = explode('.', $_FILES['add_album']["name"]);
        $insert_array = array();
        $insert_array['apic_name'] = $image['0'];
        $insert_array['apic_tag'] = '';
        $insert_array['aclass_id'] = $class_info['aclass_id'];
        $insert_array['apic_cover'] = $img_path;
        $insert_array['apic_size'] = intval($_FILES['add_album']['size']);
        $insert_array['apic_spec'] = $width . 'x' . $height;
        $insert_array['upload_time'] = TIMESTAMP;
        $insert_array['store_id'] = $store_id;
        $model_album->addPic($insert_array);

        $data = array ();
        $data ['thumb_name'] = cthumb($img_path, 240, $store_id);
        $data ['name']      = $img_path;
        echo json_encode($data);die;

    }
    /**
     * 手机端上传视频
     */
    public function mobile_video_uploadOp() {
        $store_id = 0;
        // 判断视频数量是否超限
        $model_album = Model('video_album');
        if ($album_limit > 0) {
            $album_count = $model_album->getCount(array('store_id' => $store_id));
            if ($album_count >= $album_limit) {
                echo json_encode(array('error' => '您上传视频数达到上限，请升级您的店铺或跟管理员联系'));die;
            }
        }

        $class_info = $model_album->getOne(array('store_id' => $store_id, 'is_default' => 1), 'video_album_class');
        // 上传视频
        $upload = new UploadVideoFile();
        $default_dir = ATTACH_GOODS . DS . $store_id . DS . 'goods_video' . DS . $upload->getSysSetPath();
        $upload->set('default_dir', $default_dir);
        $upload->set('fprefix', $store_id);
        $upload->set('max_size', 20480);
        $upload->set('allow_type', array('mp4'));
        $result = $upload->upfile('add_video_album',true);
        if (!$result) {
            echo json_encode(array('error' => $upload->error));die;
        }

        $video_path = $upload->getSysSetPath() . $upload->file_name;

        // 存入专辑
        $video = explode('.', $_FILES['add_video_album']["name"]);
        $insert_array = array();
        $insert_array['video_name'] = $video['0'];
        $insert_array['video_tag'] = '';
        $insert_array['video_class_id'] = $class_info['video_class_id'];
        $insert_array['video_cover'] = $video_path;
        $insert_array['video_size'] = intval($_FILES['add_video_album']['size']);
        $insert_array['upload_time'] = TIMESTAMP;
        $insert_array['store_id'] = $store_id;
        $model_album->addVideo($insert_array);

        $data = array ();
        $data ['thumb_name'] = goodsVideoPath($video_path, $store_id);
        $data ['name']      = $video_path;
        echo json_encode($data);die;
    }
    /**
     * 图片列表
     */
    public function pic_listOp(){

        /**
         * 分页类
         */
        $page   = new Page();
        $page->setEachNum(14);
        $page->setStyle('admin');
        /**
         * 实例化相册类
         */
        $model_album = Model('album');
        /**
         * 图片列表
         */
        $param = array();
        $param['album_pic.store_id']    = '0';
        $pic_list = $model_album->getPicList($param,$page);
        Tpl::output('pic_list',$pic_list);
        Tpl::output('show_page',$page->show());
        switch($_GET['item']) {
            case 'mobile':
                Tpl::output('type', $_GET['type']);
                Tpl::showpage('mb_news.mobile_image', 'null_layout');
                break;
        }
    }

    /**
     * 视频列表
     */
    public function video_listOp(){

        /**
         * 分页类
         */
        $page   = new Page();
        $page->setEachNum(14);
        $page->setStyle('admin');
        /**
         * 实例化专辑类
         */
        $model_video_album = Model('video_album');
        /**
         * 视频列表
         */
        $param = array();
        $param['album_video.store_id']    = '0';
        $video_list = $model_video_album->getVideoList($param,$page);
        Tpl::output('video_list',$video_list);
        Tpl::output('show_page',$page->show());
        switch($_GET['item']) {
            case 'mobile':
                Tpl::output('type', $_GET['type']);
                Tpl::showpage('mb_news.mobile_video', 'null_layout');
                break;
        }
    }
}
