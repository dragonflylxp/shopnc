<?php

/**
 * 视频空间操作
 *
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net
 * @link       http://www.shopec.net
 * @since      File available since Release v1.1
 */
use shopec\Tpl;

defined('Inshopec') or exit('Access Invalid!');

class store_videoControl extends BaseSellerControl {

    public function indexOp() {
        $this->album_video_listOp();
        exit;
    }

    public function __construct() {
        parent::__construct();
        $this->album = Model('video_album');
    }

    /**
     * 视频列表
     */
    public function album_video_listOp() {
        /**
         * 验证是否存在默认视频
         */
        $return = $this->album->checkAlbum(array('video_album_class.store_id' => $_SESSION['store_id'], 'is_default' => '1'));
        if (!$return) {
            $album_arr = array();
            $album_arr['video_class_name'] = '默认媒体库';
            $album_arr['store_id'] = $_SESSION['store_id'];
            $album_arr['video_class_des'] = '';
            $album_arr['video_class_sort'] = '255';
            $album_arr['upload_time'] = time();
            $album_arr['is_default'] = '1';
            $this->album->addClass($album_arr);
        }

        /**
         * 分页类
         */
        $page = new Page();
        $page->setEachNum(15);
        $page->setStyle('admin');

        /**
         * 实例化视频类
         */
        $param = array();
        $param['video_album.store_id'] = $_SESSION['store_id'];
        if ($_GET['sort'] != '') {
            switch ($_GET['sort']) {
                case '0':
                    $param['order'] = 'upload_time desc';
                    break;
                case '1':
                    $param['order'] = 'upload_time asc';
                    break;
                case '2':
                    $param['order'] = 'video_size desc';
                    break;
                case '3':
                    $param['order'] = 'video_size asc';
                    break;
                case '4':
                    $param['order'] = 'video_name desc';
                    break;
                case '5':
                    $param['order'] = 'video_name asc';
                    break;
            }
        }
        $video_list = $this->album->getVideoList($param, $page);
        Tpl::output('video_list', $video_list);
        Tpl::output('show_page', $page->show());

        
        /**
         * 视频信息
         */
        $param = array();
        $param['field'] = array('video_class_id', 'store_id');
        $param['value'] = array(intval($_GET['id']), $_SESSION['store_id']);
        $class_info = $this->album->getOneClass($param);
        Tpl::output('class_info', $class_info);

        Tpl::output('PHPSESSID', session_id());
        self::profile_menu('album_video', 'video_list');
        Tpl::showpage('store_video_album.video_list');
    }

    
    

    /**
     * 视频删除
     */
    public function album_video_delOp() {
        if (empty($_POST))
            $_POST = $_GET;
        if (empty($_POST['id'])) {
            showDialog('参数错误');
        }

        if (!empty($_POST['id']) && is_array($_POST['id'])) {
            $id = "'" . implode("','", $_POST['id']) . "'";
        } else {
            $id = intval($_POST['id']);
        }

        $return = $this->album->checkAlbum(array('video_album.store_id' => $_SESSION['store_id'], 'in_video_id' => $id));
        if (!$return) {
            showDialog('视频删除失败');
        }

        //删除视频
        $return = $this->album->delVideo($id, $_SESSION['store_id']);
        if ($return) {
            showDialog('视频删除成功', 'reload', 'succ');
        } else {
            showDialog('视频删除失败');
        }
    }

    
    

    /**
     * 用户中心右边，小导航
     *
     * @param string    $menu_type  导航类型
     * @param string    $menu_key   当前导航的menu_key
     * @return
     */
    private function profile_menu($menu_type, $menu_key = '') {
        $menu_array = array();
        switch ($menu_type) {
            case 'album_video':
                $menu_array = array(
                    3 => array('menu_key' => 'video_list', 'menu_name' => '视频列表', 'menu_url' => 'index.php?con=store_video&fun=album_video_list&id=' . intval($_GET['id']))
                );
                break;
        }
        if (C('oss.open')) {
            unset($menu_array[2]);
        }
        Tpl::output('member_menu', $menu_array);
        Tpl::output('menu_key', $menu_key);
    }

    

    

}
