<?php
/**
 * 店铺扩展模型
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
class store_extendModel extends Model {
    public function __construct(){
        parent::__construct('store_extend');
    }

    /**
     * 查询店铺扩展信息
     *
     * @param int $store_id 店铺编号
     * @param string $field 查询字段
     * @return array
     */
    public function getStoreExtendInfo($condition, $field = '*') {
        return $this->field($field)->where($condition)->find();
    }

    /*
     * 编辑店铺扩展信息
     *
     * @param array $update 更新信息
     * @param array $condition 条件
     * @return bool
     */
    public function editStoreExtend($update, $condition){
        return $this->where($condition)->update($update);
    }

    /*
     * 删除店铺扩展信息
     */
    public function delStoreExtend($condition)
    {
        return $this->where($condition)->delete();
    }

    /**
     * 新增
     * @param unknown $data
     * @return Ambigous <mixed, boolean>
     */
    public function addStoreExtend($data) {
        return $this->insert($data);
    }

    /**
     * 列表查询
     * @param unknown $condition
     */
    public function getStoreExendList($condition = array(), $limit = '') {
        return $this->where($condition)->limit($limit)->select();
    }

    /**
     * 取数量
     * @param unknown $condition
     */
    public function getStoreExtendCount($condition = array()) {
        return $this->where($condition)->count();
    }

}
