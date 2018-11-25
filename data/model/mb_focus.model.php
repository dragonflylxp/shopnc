<?php
/**
 * 手机视频列表广告图模型
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
class mb_focusModel extends Model{

    public function __construct() {
        parent::__construct('mb_focus_item');
    }

    /**
     * 读取视频列表广告图列表
     * @param array $condition
     *
     */
    public function getMbFocusList($condition, $page='', $order='focus_id desc', $field='*') {
        $list = $this->table('mb_focus_item')->field($field)->where($condition)->page($page)->order($order)->select();
        return $list;
    }

    /**
     * 统计视频列表广告图列表
     * @param array $condition
     *
     */
    public function getMbFocusCount($condition) {
        $count = $this->table('mb_focus_item')->where($condition)->count();
        return $count;
    }

    /**
     * 读取视频列表广告图列表
     * @param array $condition
     *
     */
    public function getMbFocusInfoByID($focus_id) {
        $focus_id = intval($focus_id);
        if($focus_id <= 0) {
            return false;
        }

        $condition = array();
        $condition['focus_id'] = $focus_id;
        $info = $this->table('mb_focus_item')->where($condition)->find();
        return $info;
    }

    /*
     * 增加视频列表广告图
     * @param array $param
     * @return bool
     *
     */
    public function addMbFocus($param){
        return $this->table('mb_focus_item')->insert($param);
    }

    /*
     * 更新视频列表广告图
     * @param array $update
     * @param array $condition
     * @return bool
     *
     */
    public function editMbFocus($update, $focus_id) {
        $focus_id = intval($focus_id);
        if($focus_id <= 0) {
            return false;
        }

        $condition = array();
        $condition['focus_id'] = $focus_id;
        $result = $this->table('mb_focus_item')->where($condition)->update($update);
        if($result) {
            //删除缓存
            $this->_delMbFocusCache($focus_id);
            return $focus_id;
        } else {
            return false;
        }
    }

    /*
     * 删除视频列表广告图
     * @param int $focus_id
     * @return bool
     *
     */
    public function delMbFocusByID($focus_id) {
        $focus_id = intval($focus_id);
        if($focus_id <= 0) {
            return false;
        }

        $condition = array();
        $condition['focus_id'] = $focus_id;

        $this->delMbFocusItem($condition, $focus_id);

        return $this->table('mb_focus_item')->where($condition)->delete();
    }



    /**
     * 检查视频列表广告图项目是否存在
     * @param array $condition
     *
     */
    public function isMbFocusItemExist($condition) {
        $item_list = $this->table('mb_focus_item')->where($condition)->select();
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
    public function delMbFocusItem($condition, $focus_id) {
        //删除缓存
        $this->_delMbFocusCache($focus_id);

        return $this->table('mb_focus_item')->where($condition)->delete();
    }

    /**
     * 获取视频列表广告图URL地址
     * @param int $focus_id
     *
     */
    public function getMbFocusHtmlUrl($focus_id) {
        return UPLOAD_SITE_URL . DS . ATTACH_MOBILE . DS . 'focus_html' . DS . md5('focus' . $focus_id) . '.html';
    }

    /**
     * 获取视频列表广告图静态文件路径
     * @param int $focus_id
     *
     */
    public function getMbFocusHtmlPath($focus_id) {
        return BASE_UPLOAD_PATH . DS . ATTACH_MOBILE . DS . 'focus_html' . DS . md5('focus' . $focus_id) . '.html';
    }


    /**
     * 清理缓存
     */
    private function _delMbFocusCache($focus_id) {
        //清理缓存
        dcache($focus_id, 'mb_focus_setting');

        //删除静态文件
        $html_path = $this->getMbFocusHtmlPath($focus_id);
        if(is_file($html_path)) {
            @unlink($html_path);
        }
    }
}
