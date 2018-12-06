<?php
/**
 * 店铺入驻模型
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
class store_joininModel extends Model{

    public function __construct(){
        parent::__construct('store_joinin');
    }

    /**
     * 读取列表
     * @param array $condition
     *
     */
    public function getList($condition,$page='',$order='',$field='*'){
        $result = $this->table('store_joinin')->field($field)->where($condition)->page($page)->order($order)->select();
        return $result;
    }

    /**
     * 店铺入驻数量
     * @param unknown $condition
     */
    public function getStoreJoininCount($condition) {
        return  $this->where($condition)->count();
    }

    /**
     * 读取单条记录
     * @param array $condition
     *
     */
    public function getOne($condition){
        $result = $this->where($condition)->find();
        return $result;
    }

    /*
     *  判断是否存在
     *  @param array $condition
     *
     */
    public function isExist($condition) {
        $result = $this->getOne($condition);
        if(empty($result)) {
            return FALSE;
        }
        else {
            return TRUE;
        }
    }

    /*
     * 增加
     * @param array $param
     * @return bool
     */
    public function save($param){
        return $this->insert($param);
    }

    /*
     * 增加
     * @param array $param
     * @return bool
     */
    public function saveAll($param){
        return $this->insertAll($param);
    }

    /*
     * 更新
     * @param array $update
     * @param array $condition
     * @return bool
     */
    public function modify($update, $condition){
        return $this->where($condition)->update($update);
    }

    /*
     * 删除
     * @param array $condition
     * @return bool
     */
    public function drop($condition){
        return $this->where($condition)->delete();
    }

    /**
     * 编辑
     * @param array $condition
     * @param array $update
     * @return bool
     */
    public function editStoreJoinin($condition, $update) {
        return $this->where($condition)->update($update);
    }

     /**
      * 银行列表
      *
      */
    public function getBankList($bank_no=""){
             $bank_list = array(array("bank_no"=> "413", "bank_name"=> "广州银行"),
                                array("bank_no"=> "102", "bank_name"=> "中国工商银行"),
                                array("bank_no"=> "103", "bank_name"=> "中国农业银行")); 

             if(!empty($bank_no)){
                 foreach($bank_list as $k => $bank){
                     if($bank["bank_no"] == $bank_no){
                         return $bank;
                     } 
                 }
                 return array("bank_no"=> $bank_no, "bank_name"=> "");
             }else{
                 return $bank_list;
             }
     }

     /**
      * 经营类目 
      *
      */
    public function getGcnoList($gc_no=""){
           $gc_list =array(array("gc_no"=> "4900", "gc_name"=> "公共事业(电力、煤气、自来水、清洁服务)"),
                           array("gc_no"=> "5813", "gc_name"=> "饮酒场所(酒吧、酒馆、夜总会、鸡尾酒大厅、迪斯科舞厅)"),
                           array("gc_no"=> "5812", "gc_name"=> "就餐场所和餐馆，营业面积>100平方米")); 
           if(!empty($gc_no)){
               foreach($gc_list as $k => $gc){
                   if($gc["gc_no"] == $gc_no){
                       return $gc;
                   } 
               }
               return array("gc_no"=> $gc_no, "gc_name"=> "");
           }else{
               return $gc_list;
           }
     }

     /**
      * 业务类型 
      *
      */
    public function getBusiList($busi_no=""){
           $busi_list =array(array("busi_no"=> "B00101", "busi_name"=> "网关-储蓄卡支付"),
                             array("busi_no"=> "B00102", "busi_name"=> "网关-信用卡支付"),
                             array("busi_no"=> "B00105", "busi_name"=> "快捷支付(借记卡)"),
                             array("busi_no"=> "B00107", "busi_name"=> "快捷支付(信用卡)"));
           if(!empty($busi_no)){
               foreach($busi_list as $k => $busi){
                   if($busi["busi_no"] == $busi_no){
                       return $busi;
                   } 
               }
               return array("busi_no"=> $busi_no, "busi_name"=> "");
           }else{
               return $busi_list;
           }
     }
}
?>
