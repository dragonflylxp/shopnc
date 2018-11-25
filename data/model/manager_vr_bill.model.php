<?php
/**
 * Created by PhpStorm.
 * User: suijiaolong
 * Date: 2016/11/22/022
 * Time: 11:30
 */
defined('Inshopec') or exit('Access Invalid!');
class manager_vr_billModel extends Model {

    public function __construct()
    {
        parent::__construct('manager_vr_bill');
    }
    /** 获取管理人虚拟结算单
     * @param array $condition
     * @param string $fields
     * @param null $pagesize
     * @param string $order
     * @param null $limit
     * @return mixed
     */
    public function getManagerVrBillList($condition = array(), $fields = '*', $pagesize = null, $order = '', $limit = null){
        $list =  $this->table('manager_vr_bill')->where($condition)->field($fields)->order($order)->page($pagesize)->limit($limit)->select();
        return $list;
    }

    /**
     * 取得管理员月结算单条信息
     * @param unknown $condition
     * @param string $fields
     */
    public function getManagerBillInfo($condition = array(), $fields = '*',$order = null) {
        return $this->table('manager_vr_bill')->where($condition)->field($fields)->order($order)->find();
    }
    /**
     * 添加管理员月结算单条信息
     * @param unknown $condition
     * @param string $fields
     */
    public function addManagerBill($data) {
        return $this->table('manager_vr_bill')->insert($data);
    }
//    管理人视图start
    /**
     * 获取管理人列表
     *
     * @param array $condition 条件数组
     * @param int $pageSize 分页长度
     *
     * @return array 管理人列表
     */
    public function getManagerBillList($condition, $pageSize = 20, $limit = null, $sort = 'mb_id desc')
    {
        $on = "manager.member_id=manager_vr_bill.uid";
        $fields = '*';
        $list =  $this->table('manager,manager_vr_bill')->join('inner')->on($on)->where($condition)->field($fields)->order($sort)->page($pageSize)->limit($limit)->select();
        return $list;

    }
    /**
     * 获取管理人单条结算信息
     * @param string $fields
     * @author rqs
     */

    public function getManagerVrBillInfo($condition = array(), $fields = '*') {
        $on = "manager.member_id=manager_vr_bill.uid";
        $managerInfo = $this->table('manager,manager_vr_bill')->join('inner')->on($on)->where($condition)->field($fields)->find();
        $area = '';
        if(!empty($managerInfo['area_region'])){
            $area = $managerInfo['area_region'];
        }
        if(!empty($managerInfo['province_id'])){
            $v = $this->getAreaInfo($managerInfo['province_id']);

            $area.="&nbsp".$v['area_name'];
        }
        if(!empty($managerInfo['city_id'])){
            $v = $this->getAreaInfo($managerInfo['city_id']);
            $area.="&nbsp".$v['area_name'];
        }
        if(!empty($managerInfo['district_id'])) {
            $v = $this->getAreaInfo($managerInfo['district_id']);
            $area .= "&nbsp".$v['area_name'];
        }
        $managerInfo['area'] = $area;
        return $managerInfo;
    }

    /**
     * 获取地区信息
     * @param array $condition
     * @author rqs
     */
    public function getAreaInfo($condition ){
        $AreaInfo = $this->table('area')->where(array('area_id'=>$condition))->find();
        return $AreaInfo;
    }

    /**
     * 查询当前区域管理人信息
     * @param $id
     * @author rqs
     */
    public function getManagerShowList($uid,$condition, $pageSize = 20, $limit = null, $sort = 'mb_id desc'){
        $lists = $this->table('manager_vr_bill')->where(array('uid'=>$uid))->find();

        if(!empty($lists['area_region'])){
            $condition['area_region'] = $lists['area_region'];
        }
        if(!empty($lists['province_id'])){
            $condition['province_id'] = $lists['province_id'];
        }
        if(!empty($lists['city_id'])){
            $condition['city_id'] = $lists['city_id'];
        }
        if(!empty($lists['district_id'])){
            $condition['district_id'] = $lists['district_id'];

        }
        $condition['grade'] = $lists['grade'];
        $fields = '*';
//        $listsInfo = $this->table('manager_vr_bill')->where($condition)->select();
        $listsInfo = $this->table('manager_vr_bill')->where($condition)->field($fields)->order($sort)->page($pageSize)->limit($limit)->select();
        return $listsInfo;

    }


    /**
     * 通过ID删除管理员（自动添加未使用标记）
     *
     * @param int|array $id 表字增ID(s)
     *
     * @return boolean
     */
    public function delManagerById($id)
    {
        return $this->where(array(
            'mb_id' => array('in', (array) $id),
        ))->delete();
    }
    /**
     * 编辑管理人提现信息
     * @param array $condition
     * @param array $update
     * @return bool
     */
    public function editManagerInfo($condition, $update) {
        return $this->where($condition)->update($update);
    }

    /**
     * 获取管理人结算单的虚拟条数
     * @param $condition
     * @return mixed
     */
    public function getManagerBillCount($condition) {
        return $this->where($condition)->count();
    }
    /**
     * 获取管理人提现申请条数
     *
     */
    public function getMoneyApplyCount(){
        $condition['state'] = "2";
        return $this->where($condition)->count();
    }

    /**
     * 修改管理人月结算信息[虚拟]
     * @param $data
     * @param $condition
     * @return mixed
     */
    public function editManagerBill($data,$condition) {
        return $this->table('manager_vr_bill')->where($condition)->update($data);
    }

}