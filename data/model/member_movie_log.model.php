<?php
/**
 * 会员模型
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
class member_movie_logModel extends Model {

    public function __construct(){
        parent::__construct('member_movie_log');
    }

    

    /**
     * 添加直播日志
     *
     * @return  array 数组格式的返回结果
     */
    public function addMemberMovieLog($param) {
        if(empty($param)) {
            return false;
        }
        return $this->table('member_movie_log')->insert($param);
    }

    /**
     * 编辑直播日志
     *
     */
    public function editMemberMovieLog($update,$condition = array()){
        return $this->table('member_movie_log')->where($condition)->update($update);
    }

    
}
