<?php
/**
 * 团购地区模型
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
class groupbuy_classModel extends Model {

    const TABLE_NAME = 'groupbuy_class';
    const PK = 'class_id';

    /**
     * 构造检索条件
     *
     * @param array $condition 检索条件
     * @return string
     */
    private function getCondition($condition){
        $condition_str = '';
        if (!empty($condition['class_id'])){
        $condition_str .= "and class_id = '".$condition['class_id']."'";
        }
        if (!empty($condition['in_class_id'])){
        $condition_str .= "and class_id in (".$condition['in_class_id'].")";
        }
        if (isset($condition['class_parent_id'])){
        $condition_str .= "and class_parent_id = '".$condition['class_parent_id']."'";
        }
        if (!empty($condition['deep'])){
        $condition_str .= "and deep <= '".$condition['deep']."'";
        }
        return $condition_str;
    }

    /**
     * 读取列表
     *
     */
    public function getList($condition = '',$page = ''){

        $param = array() ;
        $param['table'] = self::TABLE_NAME ;
        $param['where'] = $this->getCondition($condition);
        $param['order'] = isset($condition['order']) ? $condition['order']: ' '.self::PK.' desc ';
        return $this->select1($param,$page);
    }

    /**
     * 读取列表
     *
     */
    public function getTreeList($condition='',$page='',$max_deep=1){

        $class_list = $this->getList($condition,$page);
        $tree_list = array();
        if(is_array($class_list)) {
            $tree_list = $this->_getTreeList($class_list,0,0,$max_deep);
        }
        return $tree_list;
    }

    //按照顺序显示树形结构
    private function _getTreeList($list,$parent_id,$deep=0,$max_deep) {

        $result = array();
        foreach($list as $node) {

            if($node['class_parent_id'] == $parent_id) {

                if($deep <= $max_deep) {
                    $temp = $this->_getTreeList($list,$node['class_id'],$deep+1,$max_deep);
                    if(!empty($temp)) {
                        $node['have_child'] = 1;
                    }
                    else {
                        $node['have_child'] = 0;
                    }
                    //标记是否为叶子节点
                    if($deep == $max_deep) {
                        $node['node'] = 1;
                    }
                    else {
                        $node['node'] = 0;
                    }

                    $node['deep'] = $deep;
                    $result[] = $node;
                    if(!empty($temp)) {
                        $result = array_merge($result,$temp);
                    }

                    unset($temp);
                }
            }
        }
        return $result;
    }

    /**
     * 根据编号获取所有下级编号的数组
     *
     * @param array
     * @return array 数组类型的返回结果
     */
    public function getAllClassId($class_id_array) {

        $all_class_id_array = array();
        $class_list = $this->getList();
        foreach($class_id_array as $class_id) {
            $all_class_id_array[] = $class_id;
            foreach($class_list as $class) {
                if($class['class_parent_id'] == $class_id) {
                    $all_class_id_array[] = $class['class_id'];
                }
            }
        }
        return $all_class_id_array;
    }


    /**
     * 根据编号获取单个内容
     *
     * @param int $groupbuy_area_id 地区ID
     * @return array 数组类型的返回结果
     */
    public function getOne($id){
        if (intval($id) > 0){
            $param = array();
            $param['table'] = self::TABLE_NAME;
            $param['field'] = self::PK;
            $param['value'] = intval($id);
            $result = $this->getRow1($param);
            return $result;
        }else {
            return false;
        }
    }

    /*
     *  判断是否存在
     *  @param array $condition
     *  @param obj $page    //分页对象
     *  @return array
     */
    public function isExist($condition='') {

        $param = array() ;
        $param['table'] = self::TABLE_NAME ;
        $param['where'] = $this->getCondition($condition);
        $list = $this->select1($param);
        if(empty($list)) {
            return false;
        }
        else {
            return true;
        }
    }

    /*
     * 增加
     * @param array $param
     * @return bool
     */
    public function save($param){

        return $this->insert1(self::TABLE_NAME,$param) ;

    }

    /*
     * 更新
     * @param array $update_array
     * @param array $where_array
     * @return bool
     */
    public function updates($update_array, $where_array){

        $where = $this->getCondition($where_array) ;
        return $this->update1(self::TABLE_NAME,$update_array,$where) ;

    }

    /*
     * 删除
     * @param array $param
     * @return bool
     */
    public function drop($param){

        $where = $this->getCondition($param) ;
        return $this->delete1(self::TABLE_NAME, $where) ;
    }

}
