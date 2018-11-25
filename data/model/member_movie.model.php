<?php
/**
 * 手机直播模型
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
class member_movieModel extends Model{

    public function __construct() {
        parent::__construct('member_movie');
    }

    /**
     * 读取直播列表
     * @param array $condition
     *
     */
    public function getMemberMovieList($condition, $page='', $order='movie_id desc', $field='*') {
        $list = $this->table('member_movie')->field($field)->where($condition)->page($page)->order($order)->select();
        return $list;
    }

    /**
     * 统计直播
     * @param array $condition
     *
     */
    public function getMemberMovieCount($condition) {
        $count = $this->table('member_movie')->where($condition)->count();
        return $count;
    }

    /**
     * 读取直播单条信息，根据ID读取
     * @param array $condition
     *
     */
    public function getMemberMovieInfoByID($movie_id) {
        $movie_id = intval($movie_id);
        if($movie_id <= 0) {
            return false;
        }

        $condition = array();
        $condition['movie_id'] = $movie_id;
        $info = $this->table('member_movie')->where($condition)->find();
        return $info;
    }

    /**
     * 读取直播信息
     * @param array $condition
     *
     */
    public function getMemberMovieInfo($condition) {
        $info = $this->table('member_movie')->where($condition)->find();
        return $info;
    }

    /*
     * 增加直播
     * @param array $param
     * @return bool
     *
     */
    public function addMemberMovie($param){
        return $this->table('member_movie')->insert($param);
    }

    /*
     * 更新直播
     * @param array $update
     * @param array $condition
     * @return bool
     *
     */
    public function editMemberMovie($update, $condition) {
        $result = $this->table('member_movie')->where($condition)->update($update);
        if($result) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * 删除直播
     * @param int $movie_id
     * @return bool
     *
     */
    public function delMemberMovieByID($movie_id) {
        $movie_id = intval($movie_id);
        if($movie_id <= 0) {
            return false;
        }

        $condition = array();
        $condition['movie_id'] = $movie_id;

        $this->delMemberMovieItem($condition, $movie_id);

        return $this->table('member_movie')->where($condition)->delete();
    }



    /**
     * 检查直播是否存在
     * @param array $condition
     *
     */
    public function isMemberMovieItemExist($condition) {
        $item_list = $this->table('member_movie')->where($condition)->select();
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
    public function delMemberMovieItem($condition) {

        return $this->table('member_movie')->where($condition)->delete();
    }


}
