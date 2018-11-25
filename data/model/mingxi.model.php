<?php
/**
 * 佣金数据模型 20160906
 *
 * @User      noikiy
 * @File      mingxi.model.php
 * @Link      
 * @Copyright 2015 
 */

defined('Inshopec') or exit('Access Invalid!');

class mingxiModel extends Model
{

    public function __construct() {
        parent::__construct('mingxi');
    }

    public function getMingxiByMemberId($buyer_id) {
        $condition = array();
        $condition['buyer_id'] = intval($buyer_id);
        return $this->getMingxiList($condition);
    }

    public function getMingxiById($id) {
        $condition = array();
        $condition['id'] = intval($id);
        return $this->getMingxiList($condition);
    }
    
    public function getMingxiInfo($condition) { 
        return $this->where($condition)->find();
    }

    public function getMingxiList($condition = array(), $pagesize = '', $fields = '*', $order = '', $limit = ''){
        return $this->where($condition)->field($fields)->order($order)->limit($limit)->page($pagesize)->select();
    }
    
    public function getCommisionInfo($order_sn, $level, $goods_id) {
        $condition = array();
        $condition['order_sn']        = $order_sn;
        $condition['goods_id']        = $goods_id;
        $condition['commision_level'] = $level;

        $result = $this->getMingxiInfo($condition);
        if ($result) {
            return $result['username'] . '获得了' . $result['je'] . '元';
        } else {
            return '无';
        }
    }

    /**
     * 计算购买数量信息
     * 
     * @param int id
     * @return array 一维数组
     */
    public function countBuy($uid){
        $condition = array();
        $condition['buyer_id'] = intval($uid);
        $condition['order_state'] = ORDER_STATE_SUCCESS;

        return $this->where($condition)->count();
    }
    
    public function countTgyj($top_name, $down_name){
        $condition = array();
        $condition['username'] = $top_name;
        $condition['memo'] = array('like', '%' . $down_name . '%');

        return $this->where($condition)->sum('je');
    }
}