<?php
/**
 * 团购搜索价格区间模型
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
class groupbuy_price_rangeModel extends Model {

    //表名
    const TABLE_NAME = 'groupbuy_price_range';
    //主键
    const PK = 'range_id';

    /**
     * 构造检索条件
     *
     * @param array $condition 检索条件
     * @return string
     */
    private function getCondition($condition){
        $condition_str = '';
        if (!empty($condition['range_id'])){
            $condition_str .= " AND range_id = '".$condition['range_id']."'";
        }
        if (!empty($condition['in_range_id'])){
            $condition_str .= " AND range_id in (". $condition['in_range_id'] .")";
        }
        return $condition_str;
    }

    /**
     * 读取列表
     *
     */
    public function getList($condition = array(), $page = ''){

        $param = array() ;
        $param['table'] = self::TABLE_NAME ;
        $param['where'] = $this->getCondition($condition);
        $param['order'] = $condition['order'] ? $condition['order']: ' '.self::PK.' desc ';
        return $this->select1($param,$page);
    }


    /**
     * 根据编号获取单个内容
     *
     * @param int 主键编号
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
