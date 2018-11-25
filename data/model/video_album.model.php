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
defined('Inshopec') or exit('Access Invalid!');
class video_albumModel extends Model{
    public function __construct(){
        parent::__construct();
    }
    /**
     * 计算数量
     *
     * @param array $condition 条件
     * $param string $table 表名
     * @return int
     */
    public function getAlbumVideoCount($condition) {
        $result = $this->table('video_album')->where($condition)->count();
        return $result;
    }
    /**
     * 计算数量
     *
     * @param array $condition 条件
     * $param string $table 表名
     * @return int
     */
    public function getCount($condition, $table = 'video_album') {
        $result = $this->table($table)->where($condition)->count();
        return $result;
    }
    /**
     * 获取单条数据
     *
     * @param array $condition 条件
     * @param string $table 表名
     * @return array 一维数组
     */
    public function getOne($condition, $table = 'video_album') {
        $resule = $this->table($table)->where($condition)->find();
        return $resule;
    }
    /**
     * 分类列表
     *
     * @param array $condition 查询条件
     * @param obj $page 分页对象
     * @return array 二维数组
     */
    public function getClassList($condition,$page=''){
        $param  = array();
        $param['table']         = 'video_album_class,video_album';
        $param['field']         = 'video_album_class.video_class_id,min(video_album_class.video_class_name) as video_class_name,min(video_album_class.store_id) as store_id,min(video_album_class.video_class_des) as video_class_des,min(video_album_class.video_class_sort) as video_class_sort,min(video_album_class.upload_time) as upload_time,min(video_album_class.is_default) as is_default,count(video_album.video_class_id) as count';
        $param['join_type']     = 'left join';
        $param['join_on']       = array('video_album_class.video_class_id = video_album.video_class_id');
        $param['where']         = $this->getCondition($condition);
        $param['order']         = $condition['order'] ? $condition['order'] : 'video_class_sort desc';
        $param['group']         = 'video_album_class.video_class_id';
        return $this->select1($param,$page);
    }
    /**
     * 计算分类数量
     *
     * @param int id
     * @return array 一维数组
     */
    public function countClass($id){
        $param  = array();
        $param['table']         = 'video_album_class';
        $param['field']         = 'count(*) as count';
        $param['where']         = " and store_id = '$id'";
        $return = $this->select1($param);
        return $return['0'];
    }
    /**
     * 验证视频
     *
     * @param array $param 参数内容
     * @return bool 布尔类型的返回结果
     */
    public function checkAlbum($condition) {
        /**
         * 验证是否为当前合作伙伴
         */
        $check_array = self::getClassList($condition,'');
        if (!empty($check_array)){
            unset($check_array);
            return true;
        }
        unset($check_array);
        return false;
    }
    /**
     * 视频列表
     *
     * @param array $condition 查询条件
     * @param obj $page 分页对象
     * @return array 二维数组
     */
    public function getVideoList($condition, $page='', $field='*'){
        $param  = array();
        $param['table']         = 'video_album';
        $param['where']         = $this->getCondition($condition);
        $param['order']         = $condition['order'] ? $condition['order'] : 'video_id desc';
        $param['field']         = $field;
        return $this->select1($param,$page);
    }
    /**
     * 添加视频分类
     *
     * @param array $input
     * @return bool
     */
    public function addClass($input){
        if(is_array($input) && !empty($input)){
            return $this->insert1('video_album_class',$input);
        }else{
            return false;
        }
    }
    /**
     * 添加视频
     *
     * @param array $input
     * @return bool
     */
    public function addVideo($input) {
        $result = $this->table('video_album')->insert($input);
        return $result;
    }
    /**
     * 更新视频分类
     *
     * @param array $input
     * @param int $id
     * @return bool
     */
    public function updateClass($input,$id){
        if(is_array($input) && !empty($input)){
            return $this->update1('video_album_class',$input," video_class_id='$id' ");
        }else{
            return false;
        }
    }
    /**
     * 更新视频
     *
     * @param array $input
     * @param int $id
     * @return bool
     */
    public function updateVideo($input,$condition){
        if(is_array($input) && !empty($input)){
            return $this->update1('video_album',$input,$this->getCondition($condition));
        }else{
            return false;
        }
    }
    /**
     * 删除分类
     *
     * @param string $id
     * @return bool
     */
    public function delClass($id){
        if(!empty($id)) {
            return $this->delete1('video_album_class'," video_class_id ='".$id."' ");
        }else{
            return false;
        }
    }
    
    /**
     * 删除视频
     *
     * @param string $id
     * @param int $store_id
     * @return bool
     */
    public function delVideo($id, $store_id){
        $video_list = $this->getVideoList(array('video_id'=>$id),'','video_cover');

        /**
         * 删除视频
         */
        if(!empty($video_list) && is_array($video_list)){
            foreach($video_list as $v){
                if (C('oss.open')) {
                    if ($v['video_cover'] != '') {
                        oss::del(array(ATTACH_GOODS.DS.$store_id.DS.'goods_video'.DS.$v['video_cover']));
                    }
                } else {
                    @unlink(BASE_UPLOAD_PATH.DS.ATTACH_GOODS.DS.$store_id.DS.'goods_video'.DS.$v['video_cover']);            
                }
            }
        }
        if(!empty($id)) {
            return $this->delete1('video_album','video_id in('.$id.')');
        }else{
            return false;
        }
    }
    /**
     * 查询单条分类信息
     *
     * @param int $id 活动id
     * @return array 一维数组
     */
    public function getOneClass($param){
        if(is_array($param) && !empty($param)) {
            return $this->getRow1(array_merge(array('table'=>'video_album_class'),$param));
        }else{
            return false;
        }
    }
    /**
     * 根据id查询一张视频
     *
     * @param int $id 活动id
     * @return array 一维数组
     */
    public function getOneVideoById($param){
        if(is_array($param) && !empty($param)) {
            return $this->getRow1(array_merge(array('table'=>'video_album'),$param));
        }else{
            return false;
        }
    }
    /**
     * 构造查询条件
     *
     * @param array $condition 条件数组
     * @return $condition_sql
     */
    private function getCondition($condition){
        $condition_sql  = '';
        if($condition['video_id'] != '') {
            $condition_sql .= " and video_id= '{$condition['video_id']}'";
        }
        if($condition['video_name'] != '') {
            $condition_sql .= " and video_name='".$condition['video_name']."'";
        }
        if($condition['video_tag'] != '') {
            $condition_sql .= " and video_tag like '%".$condition['video_tag']."%'";
        }
        if($condition['video_class_id'] != '') {
            $condition_sql .= " and video_class_id= '{$condition['video_class_id']}'";
        }
        if($condition['video_album_class.store_id'] != '') {
            $condition_sql .= " and video_album_class.store_id = '{$condition['video_album_class.store_id']}'";
        }
        if($condition['video_album_class.video_class_id'] != '') {
            $condition_sql .= " and video_album_class.video_class_id= '{$condition['video_album_class.video_class_id']}'";
        }
        if($condition['video_album.store_id'] != '') {
            $condition_sql .= " and video_album.store_id= '{$condition['video_album.store_id']}'";
        }
        if($condition['video_album.video_id'] != '') {
            $condition_sql .= " and video_album.video_id= '{$condition['video_album.video_id']}'";
        }
        if($condition['store_id'] != '') {
            $condition_sql .= " and store_id= '{$condition['store_id']}'";
        }
        if($condition['video_name'] != '') {
            $condition_sql .= " and video_name='".$condition['video_name']."'";
        }
        if($condition['video_cover'] != '') {
            $condition_sql .= " and video_cover like '%".$condition['video_cover']."%'";
        }
        if($condition['is_default'] != '') {
            $condition_sql .= " and is_default= '{$condition['is_default']}'";
        }
        if($condition['in_video_id'] != '') {
            $condition_sql .= " and video_id in (".$condition['in_video_id'].")";
        }
        if($condition['gt_video_id'] != '') {
            $condition_sql .= " and video_id > '{$condition['gt_video_id']}'";
        }
        if($condition['like_cover'] != '') {
            $condition_sql .= " and video_cover like '%".$condition['like_cover']."%'";
        }
        if($condition['video_album_class.un_video_class_id'] != '') {
            $condition_sql .= " and video_album_class.video_class_id <> '{$condition['video_album_class.un_video_class_id']}'";
        }
        return $condition_sql;
    }
}
