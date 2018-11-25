<?php

/**
 * 管理人结算
 * @author rqs
 * @date 20161122
 */
defined('Inshopec') or exit('Access Invalid!');

class manager_real_billModel extends Model
{
    public function __construct()
    {
        parent::__construct('manager_bill');
    }

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
        $on = "manager.member_id=manager_bill.uid";
        $fields = '*';
        $list =  $this->table('manager,manager_bill')->join('inner')->on($on)->where($condition)->field($fields)->order($sort)->page($pageSize)->limit($limit)->select();
        return $list;

    }
    /**
     * 获取管理人单条结算信息
     * @param string $fields
     */

    public function getManagerRealBillInfo($condition = array(), $fields = '*') {
        $on = "manager.member_id=manager_bill.uid";
        $managerInfo = $this->table('manager,manager_bill')->join('inner')->on($on)->where($condition)->field($fields)->find();
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
           $lists = $this->table('manager_bill')->where(array('uid'=>$uid))->find();

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
//        $listsInfo = $this->table('manager_bill')->where($condition)->select();
        $listsInfo = $this->table('manager_bill')->where($condition)->field($fields)->order($sort)->page($pageSize)->limit($limit)->select();
        return $listsInfo;

    }



    /**
     * 通过ID管理员（自动添加未使用标记）
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
     * 获取列表显示的总条数
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
}