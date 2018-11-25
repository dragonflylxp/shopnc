<?php
/**
 * 我的银行卡信息
 *
 *
 *  @author     gongbo
 * @date       20161111
 */
defined('Inshopec') or exit('Access Invalid!');
class member_banksModel extends Model {
    public function __construct() {
        parent::__construct('secs_bank_card');
    }

    /**
     * 取得会员银行卡信息列表
     *
     * @param array $condition
     */
    public function getMemberBankList($condition = array(), $field='*',$order = 'USED desc,ID desc') {
        return $this->prefix('')->field($field)->where($condition)->order($order)->select();
    }

    /**
     * 取得会员一条银行卡信息
     *
     * @param array $condition
     */
    public function getMemberBankOne($condition = array(),$field='*') {
        return $this->prefix('')->field($field)->where($condition)->find();
    }

    /**
     * 取得会员已添加的银行卡数量
     *
     * @param array $condition
     */
    public function getMemberBankCount($condition = array()) {
        return $this->prefix('')->where($condition)->count();
    }

    /**
     * 添加一条银行卡信息
     *
     * @param array $condition
     */
    public function addMemberBankOne($data) {
        return $this->prefix('')->insert($data);
    }

    /**
     * 修改银行卡信息
     *
     * @param array $condition
     */
    public function editMemberBankOne($condition = array(),$data) {
        return $this->prefix('')->where($condition)->update($data);
    }


    /**
     * 删除银行卡信息
     *
     * @param int $id 记录ID
     * @return bool 布尔类型的返回结果
     */
    public function delMemberBankOne($condition){
        return $this->prefix('')->where($condition)->delete();
    }

}
