<?php
/**
 * F码管理
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

class p_fcodeModel extends Model {
    const STATE1 = 1;       // 开启
    const STATE0 = 0;       // 关闭

    public function __construct() {
        parent::__construct('p_fcode_quota');
    }

    /**
     * F码套餐列表
     *
     * @param array $condition
     * @param string $field
     * @param int $page
     * @param string $order
     * @return array
     */
    public function getFCodeQuotaList($condition, $field = '*', $page = null, $order = 'fcq_id desc') {
        return $this->field($field)->where($condition)->order($order)->page($page)->select();
    }

    /**
     * F码套餐详细信息
     *
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getFCodeQuotaInfo($condition, $field = '*') {
        return $this->field($field)->where($condition)->find();
    }

    /**
     * 保存F码套餐
     *
     * @param array $insert
     * @param boolean $replace
     * @return boolean
     */
    public function addFCodeQuota($insert, $replace = false) {
        return $this->pk(array('fcq_id'))->insert($insert, $replace);
    }

    /**
     * 编辑F码套餐
     * @param array $update
     * @param array $condition
     * @return array
     */
    public function editFCodeQuota($update, $condition) {
        return $this->where($condition)->update($update);
    }
    
    /**
     * F码商品列表
     * @param unknown $condition
     * @param string $field
     */
    public function getFCodeGoodsList($condition, $field = '*', $page = 10, $order = 'goods_id desc') {
        $condition['is_fcode'] = 1;
        return Model('goods')->getGoodsList($condition, $field, '', $order, 0, $page);
    }
    
    /**
     * 删除F码商品活动
     * @param int $goods_id
     */
    public function delFCodeGoodsByGoodsId($goods_id) {
        $update = array();
        $update['is_fcode'] = 0;
        $result = Model('goods')->editGoodsById($update, $goods_id);
        if ($result) {
            // 删除商品F码
            Model('goods_fcode')->delGoodsFCode(array('goods_id' => $goods_id));
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * 添加F码商品活动
     * @param int $goods_id
     * @return boolean
     */
    public function addFCodeGoodsByGoodsId($goods_id) {
        $update = array();
        $update['is_fcode'] = 1;
        $result = Model('goods')->editGoodsById($update, $goods_id);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}
