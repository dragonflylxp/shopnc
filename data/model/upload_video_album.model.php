<?php
/**
 * 上传文件模型
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

class upload_video_albumModel extends Model {
    /**
     * 列表
     *
     * @param array $condition 检索条件
     * @return array 数组结构的返回结果
     */
    public function getUploadList($condition){

        $condition_str = $this->_condition($condition);
        $param = array();
        $param['table'] = 'video_album';
        $param['where'] = $condition_str;
        $result = $this->select1($param);
        return $result;
    }

    /**
     * 构造检索条件
     *
     * @param int $id 记录ID
     * @return string 字符串类型的返回结果
     */
    private function _condition($condition){
        $condition_str = '';

        if($condition['video_name'] != '') {
            $condition_str .= " and video_name='{$condition['pic_name']}'";
        }
        if($condition['video_tag'] != '') {
            $condition_str .= " and video_tag='{$condition['video_tag']}'";
        }
        if($condition['aclass_id'] != '') {
            $condition_str .= " and aclass_id='{$condition['aclass_id']}'";
        }
        if($condition['video_cover'] != '') {
            $condition_str .= " and video_cover='{$condition['video_cover']}'";
        }
        if($condition['video_size'] != '') {
            $condition_str .= " and video_size='{$condition['video_size']}'";
        }
        if($condition['store_id'] != '') {
            $condition_str .= " and store_id='{$condition['store_id']}'";
        }
        if($condition['upload_time'] != '') {
            $condition_str .= " and upload_time='{$condition['upload_time']}'";
        }
        return $condition_str;
    }

    /**
     * 取单个内容
     *
     * @param int $id 分类ID
     * @return array 数组类型的返回结果
     */
    public function getOneUpload($id){
        if (intval($id) > 0){
            $param = array();
            $param['table'] = 'video_album';
            $param['field'] = 'video_id';
            $param['value'] = intval($id);
            $result = $this->getRow1($param);
            return $result;
        }else {
            return false;
        }
    }

    /**
     * 新增
     *
     * @param array $param 参数内容
     * @return bool 布尔类型的返回结果
     */
    public function add($param){
        if (empty($param)){
            return false;
        }
        if (is_array($param)){
            $result = $this->insert1('video_album',$param);
            return $result;
        }else {
            return false;
        }
    }

    /**
     * 更新信息
     *
     * @param array $param 更新数据
     * @return bool 布尔类型的返回结果
     */
    public function updates($param){
        if (empty($param)){
            return false;
        }
        if (is_array($param)){
            $tmp = array();
            foreach ($param as $k => $v){
                $tmp[$k] = $v;
            }
            $where = " video_id = '{$param['video_id']}'";
            $result = $this->update1('video_album',$tmp,$where);
            return $result;
        }else {
            return false;
        }
    }
    /**
     * 更新信息
     *
     * @param array $param 更新数据
     * @param array $conditionarr 条件数组
     * @return bool 布尔类型的返回结果
     */
    public function updatebywhere($param,$conditionarr){
        if (empty($param)){
            return false;
        }
        if (is_array($param)){
            //条件
            $condition_str = $this->_condition($conditionarr);
            //更新信息
            $tmp = array();
            foreach ($param as $k => $v){
                $tmp[$k] = $v;
            }
            $result = $this->update1('video_album',$tmp,$condition_str);
            return $result;
        }else {
            return false;
        }
    }
    /**
     * 删除分类
     *
     * @param int $id 记录ID
     * @return bool 布尔类型的返回结果
     */
    public function del($id){
        if (intval($id) > 0){
            $where = " video_id = '". intval($id) ."'";
            $result = $this->delete1('video_album',$where);
            return $result;
        }else {
            return false;
        }
    }
    /**
     * 删除上传图片信息
     * @param   mixed $id 删除上传图片记录编号
     */
    public function dropUploadById($id){
        if(empty($id)) {
            return false;
        }
        $condition_str = ' 1=1 ';
        if (is_array($id) && count($id)>0){
            $idStr = implode(',',$id);
            $condition_str .= " and video_id in({$idStr}) ";
        }else {
            $condition_str .= " and video_id = {$id} ";
        }
        $result = $this->delete1('video_album',$condition_str);
        return $result;
    }
   
}
