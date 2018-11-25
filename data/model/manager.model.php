<?php
/**
 * 管理人审核
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
class managerModel extends Model {




    public function __construct() {
        parent::__construct('manager');
    }


    /**
     * 查询管理人的数量
     *
     * @param array $condition 查询条件
     * @param int $page 分页数
     * @param string $order 排序
     * @param string $field 字段
     * @param string $limit 取多少条
     * @return array
     */
    public function getManagerInfoList($condition, $page = null, $order = '', $field = '*', $limit = '') {

        $result = $this->field($field)->where($condition)->order($order)->limit($limit)->page($page)->select();
        return $result;
    }
    /**
     * 读取管理人信息单条记录
     * @param array $condition
     *
     */
    public function getOne($condition){
        $result = $this->where($condition)->find();
        return $result;
    }
    /**
     * 读取管理人列表列表
     * @param array $condition
     *
     */
    public function getManagerList($condition,$page='',$order='',$field='*'){
        $result = $this->table('manager')->field($field)->where($condition)->page($page)->order($order)->select();
        return $result;
    }

    /**
     * 编辑管理人信息
     * @param array $condition
     * @param array $update
     * @return bool
     */
    public function editManagerInfo($condition, $update) {
        return $this->where($condition)->update($update);
    }
    /**
     * 编辑管理人密码
     * @param array $condition
     * @param array $update
     * @return bool
     */
    public function editManagerPasswprd($condition, $update) {
        return $this->table('member')->where($condition)->update($update);
    }





    /**
     * 管理人数量
     * @param array $condition
     * @return int
     */
    public function getStoreCount($condition) {
        return $this->where($condition)->count();
    }


    /**
     * 查询管理人信息
     *
     * @param array $condition 查询条件
     * @return array
     */
    public function getManagerInfo($condition) {
        $manager_info = $this->where($condition)->find();

        return $manager_info;
    }

    /**
     * 通过管理人ID查询管理人信息
     *
     * @param int $manager_id 管理人ID
     * @return array
     */
    public function getManagerInfoByID($member_id) {
/*        $prefix = 'manager_info';

        $manager_info = rcache($manager_id, $prefix);
        if(empty($manager_info)) {
            $manager_info = $this->getManagerInfo(array('manager_id' => $manager_id));
            $cache = array();
            $cache['manager_info'] = serialize($manager_info);
            wcache($manager_id, $cache, $prefix, 60 * 24);
        } else {
            $manager_info = unserialize($manager_info['manager_info']);
        }
        return $manager_info;*/
        $on = "manager.member_id=member.member_id";
        $fields = '*';
        $list =  $this->table('manager,member')->join('inner')->on($on)->where(array('manager.member_id' => $member_id))->field($fields)->find();
        return $list;
    }



}
