<?php
/**
 * 视频管理
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

class goods_video_albumControl extends SystemControl{
    public function __construct(){
        parent::__construct();
        $this->video_album = Model("video_album");
    }

    public function indexOp() {
        $this->listOp();
    }

    /**
     * 视频列表
     */
    public function listOp(){
        Tpl::showpage('goods_video_album.index');
    }

    /**
     * 输出XML数据
     */
    public function get_xmlOp() {
        
        $model = Model();
        
        // 设置页码参数名称
        $condition = array();
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        $order = '';
        $param = array('video_class_id', 'video_class_name', 'store_id', 'store_name', 'video_count', 'video_class_des');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
                $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }

        $page = $_POST['rp'];

        //店铺列表
        $album_list = $model->table('video_album_class')->where($condition)->order($order)->page($page)->select();

        $storeid_array = array();
        $classid_array = array();
        foreach ($album_list as $val) {
            $storeid_array[] = $val['store_id'];
            $classid_array[] = $val['video_class_id'];
        }

        // 店铺名称
        $store_list = Model('store')->getStoreList(array('store_id' => array('in', $storeid_array)));
        $store_array = array();
        foreach ($store_list as $val) {
            $store_array[$val['store_id']] = $val['store_name'];
        }

        // 视频数量
        $count_list = $model->cls()->table('video_album')->field('count(*) as count, video_class_id')->where(array('video_class_id' => array('in', $classid_array)))->group('video_class_id')->select();
        $count_array = array();
        foreach ($count_list as $val) {
            $count_array[$val['video_class_id']] = $val['count'];
        }

        $data = array();
        $data['now_page'] = $model->shownowpage();
        $data['total_num'] = $model->gettotalnum();
        foreach ($album_list as $value) {
            $param = array();
            $operation = "<a class='btn green' href='index.php?con=goods_video_album&fun=video_list&video_class_id=".$value['video_class_id']."'><i class='fa fa-list-alt'></i>查看</a>";
            if ($value['store_id']) $operation = "<a class='btn red' href='javascript:void(0);' onclick='fg_del(". $value['video_class_id'] .")'><i class='fa fa-trash-o'></i>删除</a><a class='btn green' href='index.php?con=goods_video_album&fun=video_list&video_class_id=".$value['video_class_id']."'><i class='fa fa-list-alt'></i>查看</a>";
            $param['operation'] = $operation;
            $param['video_class_id'] = $value['video_class_id'];
            $param['video_class_name'] = $value['video_class_name'];
            $param['store_id'] = $value['store_id'];
            $param['store_name'] = "<a href='". urlShop('show_store', 'index', array('store_id' => $value['store_id'])) ."' target='blank'>". $store_array[$value['store_id']] . "<i class='fa fa-external-link ' title='新窗口打开'></i></a>";
            $param['video_count'] = intval($count_array[$value['video_class_id']]);
            $param['video_class_des'] = $value['video_class_des'];
            $data['list'][$value['video_class_id']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }

    /**
     * 视频列表
     */
    public function video_listOp(){
        $model = Model();
        $condition = array();
        $title = '查看全部视频';
        if (is_numeric($_GET['video_class_id'])){
            $condition['video_class_id'] = $_GET['video_class_id'];
            $video_class_info = $model->table('video_album_class')->where($condition)->find();
            $store_info = Model('store')->getStoreInfoByID($video_class_info['store_id']);
            $title = '查看“'. $store_info['store_name'] .'--'. $video_class_info['video_class_name'] .'”的视频';
        }
        $list = $model->table('video_album')->where($condition)->order('video_id desc')->page(36)->select();
        $show_page = $model->showpage();
        Tpl::output('page',$show_page);
        Tpl::output('list',$list);
        Tpl::output('title',$title);
        Tpl::showpage('goods_video_album.video_list');
    }

    /**
     * 删除视频
     */
    public function video_class_delOp(){
        $video_class_id = intval($_GET['id']);
        if (!is_numeric($video_class_id)){
            exit(json_encode(array('state'=>false,'msg'=>'参数错误')));
        }
        $model = Model();
        $video = $model->table('video_album')->field('video_cover')->where(array('video_class_id'=>$video_class_id))->select();
        if (is_array($video)){
            foreach ($video as $v) {
                $this->del_file($v['video_cover']);
            }
        }
        $model->table('video_album')->where(array('video_class_id'=>$video_class_id))->delete();
        $model->table('video_album_class')->where(array('video_class_id'=>$video_class_id))->delete();
        $this->log('删除视频集'.'[ID:'.intval($_GET['video_class_id']).']',1);
        exit(json_encode(array('state'=>true,'msg'=>'删除成功')));
    }

    /**
     * 删除一张视频及其对应记录
     *
     */
    public function del_album_videoOp(){
        list($video_id,$filename) = @explode('|',$_GET['key']);
        if (!is_numeric($video_id) || empty($filename)) exit('0');
        $this->del_file($filename);
        Model()->table('video_album')->where(array('video_id'=>$video_id))->delete();
        $this->log('删除视频'.'[ID:'.$video_id.']',1);
        exit('1');
    }

    /**
     * 删除多张视频
     *
     */
    public function del_more_videoOp(){
        $model= Model();
        $list = $model->table('video_album')->where(array('video_id'=>array('in',$_POST['delbox'])))->select();
        if (is_array($list)){
            foreach ($list as $v) {
                $this->del_file($v['video_cover']);
            }
        }
        $model->table('video_album')->where(array('video_id'=>array('in',$_POST['delbox'])))->delete();
        $this->log('删除视频'.'[ID:'.implode(',',$_POST['delbox']).']',1);
        redirect();
    }

    /**
     * 删除视频文件
     *
     */
    private function del_file($filename){
        //取店铺ID
        if (preg_match('/^(\d+_)/',$filename)){
            $store_id = substr($filename,0,strpos($filename,'_'));
        }else{
            $store_id = Model()->cls()->table('video_album')->getfby_video_cover($filename,'store_id');
        }
        if (C('oss.open')) {
            if ($filename != '') {
                oss::del(array(ATTACH_GOODS.DS.$store_id.DS.'goods_video'.DS.$filename));
            }
        } else {
            $path = BASE_UPLOAD_PATH.'/'.ATTACH_GOODS.'/'.$store_id.'/'.'goods_video'.'/'.$filename;
            
            $ext = strrchr($path, '.');
            $type = explode(',', GOODS_IMAGES_EXT);
            foreach ($type as $v) {
                if (is_file($fpath = str_replace('.', $v.'.', $path))){
                    @unlink($fpath);
                }
            }
            if (is_file($path)) @unlink($path);            
        }

    }
}
