<?php
/**
 * 视频类别模型
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

class video_categoryModel extends Model
{

    public function __construct() {
        parent::__construct('video_category');
    }



    /**
     * 类别列表
     *
     * @param  array   $condition  检索条件
     * @return array   返回二位数组
     */
    public function getVideoCategoryList($condition, $page=0,$field = '*') {
        $result = $this->table('video_category')->field($field)->where($condition)->page($page)->order('cate_parent_id asc,cate_sort asc,cate_id asc')->limit(false)->select();
        return $result;
    }


    /**
     * 类别列表 单条
     *
     * @param  array   $condition  检索条件
     * @return array   返回二位数组
     */
    public function getVideoCategoryInfoById($cate_id,$field = '*') {
        $condition['cate_id'] = $cate_id;
        $result = $this->table('video_category')->field($field)->where($condition)->order('cate_parent_id asc,cate_sort asc,cate_id asc')->limit(false)->find();
        return $result;
    }


    /**
     * 类别列表 单条
     *
     * @param  array   $condition  检索条件
     * @return array   返回二位数组
     */
    public function getVideoCategoryInfo($condition,$field = '*') {
        $result = $this->table('video_category')->field($field)->where($condition)->order('cate_parent_id asc,cate_sort asc,cate_id asc')->limit(false)->find();
        return $result;
    }




    /**
     * 更新信息
     * @param unknown $data
     * @param unknown $condition
     */
    public function editVideoCategory($data = array(), $condition = array()) {
        return $this->where($condition)->update($data);
    }


    /**
     * 删除视频分类
     * @param unknown $condition
     * @return boolean
     */
    public function delVideoCategory($condition) {
        return $this->where($condition)->delete();
    }

    /**
     * 删除视频分类
     *
     * @param array $gcids
     * @return boolean
     */
    public function delVideoCategoryByGcIdString($cids) {
        $cids = explode(',',$cids);
        if (empty($cids)) {
            return false;
        }

        // 删除视频分类
        $this->delVideoCategory(array('cate_id' => array('in' , $cids)));

        // 删除资讯
        Model('mb_video')->delMbVideoItem(array('cate_id' => array('in' , $cids)));

        return true;
    }


    /**
     * 新增视频分类
     * @param array $insert
     * @return boolean
     */
    public function addVideoCategory($insert) {
        return $this->insert($insert);
    }

    /**
     * 统计视频分类
     */
    public function getVideoCategoryCount($condition){
        return $this->where($condition)->count();
    }

    /**
     * 统计推荐的视频分类
     */
    public function getRecommendCount($condition){
        $condition['is_recommend'] = 1;
        $cate_count = $this->getVideoCategoryCount($condition);
        return $cate_count;
    }
}
