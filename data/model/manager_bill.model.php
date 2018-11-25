<?php
/**
 * 管理员月结账单
 * User: suijiaolong
 * Date: 2016/11/21/021
 * Time: 14:30
 */
defined('Inshopec') or exit('Access Invalid!');
class manager_billModel extends Model {
    /**
     * @param array $condition
     * @param string $fields
     * @param null $pagesize
     * @param string $order
     * @param null $limit
     * @return mixed
     */
    public function getManagerBillList($condition = array(), $fields = '*', $pagesize = null, $order = '', $limit = null){
        $list =  $this->table('manager_bill')->where($condition)->field($fields)->order($order)->page($pagesize)->limit($limit)->select();
        return $list;
    }
    /**
     * 取得管理员月结算单条信息
     * @param unknown $condition
     * @param string $fields
     */
    public function getManagerBillInfo($condition = array(), $fields = '*',$order = null) {
        return $this->table('manager_bill')->where($condition)->field($fields)->order($order)->find();
    }

    /**
     * 添加管理人月结算单信息
     * @param $data
     * @return mixed
     */
    public function addManagerBill($data) {
        return $this->table('manager_bill')->insert($data);
    }

    /**
     * 修改管理人月结算信息
     * @param $data
     * @param $condition
     * @return mixed
     */
    public function editManagerBill($data,$condition) {
        return $this->table('manager_bill')->where($condition)->update($data);
    }

}