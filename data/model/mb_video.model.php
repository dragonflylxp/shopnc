<?php
/**
 * 手机资讯列表模型
 *
 *
 *
 *
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net
 * @link       http://www.shopec.net
 * @since      File available since Release v1.1
 */
defined('Inshopec') or exit('Access Invalid!');
class mb_videoModel extends Model{

    public function __construct() {
        parent::__construct('mb_video');
    }

    /**
     * 读取资讯列表列表
     * @param array $condition
     *
     */
    public function getMbVideoList($condition, $page='', $order='video_id desc', $field='*') {
        $list = $this->table('mb_video')->field($field)->where($condition)->page($page)->order($order)->select();
        return $list;
    }

    /**
     * 统计资讯列表列表
     * @param array $condition
     *
     */
    public function getMbVideoCount($condition) {
        $count = $this->table('mb_video')->where($condition)->count();
        return $count;
    }

    /**
 * 读取资讯列表列表
 * @param array $condition
 *
 */
    public function getMbVideoInfoByID($video_id) {
        $video_id = intval($video_id);
        if($video_id <= 0) {
            return false;
        }

        $condition = array();
        $condition['video_id'] = $video_id;
        $info = $this->table('mb_video')->where($condition)->find();
        return $info;
    }

    /**
     * 读取资讯列表信息
     * @param array $condition
     *
     */
    public function getMbVideoInfo($condition) {
        $info = $this->table('mb_video')->where($condition)->find();
        return $info;
    }

    /*
     * 增加资讯列表
     * @param array $param
     * @return bool
     *
     */
    public function addMbVideo($param){
        return $this->table('mb_video')->insert($param);
    }

    /*
     * 更新资讯列表
     * @param array $update
     * @param array $video_id 视频ID
     * @return bool
     *
     */
    public function editMbVideo($update, $video_id) {
        $video_id = intval($video_id);
        if($video_id <= 0) {
            return false;
        }

        $condition = array();
        $condition['video_id'] = $video_id;
        $result = $this->table('mb_video')->where($condition)->update($update);
        if($result) {
            return true;
        } else {
            return false;
        }
    }


    /*
     * 更新资讯列表
     * @param array $update
     * @param array $video_id 视频ID
     * @return bool
     *
     */
    public function editMbVideoList($update, $condition) {
        return $this->table('mb_video')->where($condition)->update($update);
    }

    /*
     * 删除资讯列表
     * @param int $video_id
     * @return bool
     *
     */
    public function delMbVideoByID($video_id,$condition=array()) {
        $video_id = intval($video_id);
        if($video_id <= 0) {
            return false;
        }

        $condition = array();
        $condition['video_id'] = $video_id;

        $this->delMbVideoItem($condition, $video_id);

        return $this->table('mb_video')->where($condition)->delete();
    }



    /**
     * 检查资讯列表项目是否存在
     * @param array $condition
     *
     */
    public function isMbVideoItemExist($condition) {
        $item_list = $this->table('mb_video')->where($condition)->select();
        if($item_list) {
            return true;
        } else {
            return false;
        }
    }



    /*
     * 删除
     * @param array $condition
     * @return bool
     *
     */
    public function delMbVideoItem($condition) {

        return $this->table('mb_video')->where($condition)->delete();
    }


}
