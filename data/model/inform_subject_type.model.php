<?php
/**
 * 举报类型模型
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
class inform_subject_typeModel extends Model {

    /*
     * 构造条件
     */
    private function getCondition($condition){
        $condition_str = '' ;
        if(!empty($condition['inform_type_state'])) {
            $condition_str.= "and  inform_type_state = '{$condition['inform_type_state']}'";
        }
        if(!empty($condition['in_inform_type_id'])) {
            $condition_str .= " and inform_type_id in (".$condition['in_inform_type_id'].')';
        }
        return $condition_str;
    }

    /*
     * 增加
     * @param array $param
     * @return bool
     */
    public function saveInformSubjectType($param){

        return $this->insert1('inform_subject_type',$param) ;

    }

    /*
     * 更新
     * @param array $update_array
     * @param array $where_array
     * @return bool
     */
    public function updateInformSubjectType($update_array, $where_array){

        $where = $this->getCondition($where_array) ;
        return $this->update1('inform_subject_type',$update_array,$where) ;

    }

    /*
     * 删除
     * @param array $param
     * @return bool
     */
    public function dropInformSubjectType($param){

        $where = $this->getCondition($param) ;
        return $this->delete1('inform_subject_type', $where) ;

    }

    /*
     *  获得举报类型列表
     *  @param array $condition
     *  @param obj $page    //分页对象
     *  @return array
     */
    public function getInformSubjectType($condition='',$page=''){

        $param = array() ;
        $param['table'] = 'inform_subject_type' ;
        $param['where'] = $this->getCondition($condition);
        $param['order'] = $condition['order'] ? $condition['order']: ' inform_type_id desc ';
        return $this->select1($param,$page) ;

    }

    /*
     *  获得有效举报类型列表
     *  @param array $condition
     *  @param obj $page    //分页对象
     *  @return array
     */
    public function getActiveInformSubjectType($page='') {

        //搜索条件
        $condition = array();
        $condition['order'] = 'inform_type_id asc';
        $condition['inform_type_state'] = 1;
        return $this->getInformSubjectType($condition,$page) ;

    }


}
