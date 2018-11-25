<?php
/**
 * 水印管理
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
class store_watermarkModel extends Model {
    /**
     * 根据店铺id获取水印
     *
     * @param array $param 参数内容
     * @return array $param 水印数组
     */
    public function getOneStoreWMByStoreId($store_id){
        $wm_arr = array();
        $store_id = intval($store_id);
        if ($store_id > 0){
            $param = array(
                'table'=>'store_watermark',
                'field'=>'store_id',
                'value'=>$store_id
            );
            $wm_arr = $this->getRow1($param);
        }
        return $wm_arr;
    }
    /**
     * 新增水印
     *
     * @param array $param 参数内容
     * @return bool 布尔类型的返回结果
     */
    public function addStoreWM($param){
        if (empty($param)){
            return false;
        }
        if (is_array($param)){
            $tmp = array();
            foreach ($param as $k => $v){
                $tmp[$k] = $v;
            }
            $result = $this->insert1('store_watermark',$tmp);
            return $result;
        }else {
            return false;
        }
    }

    /**
     * 更新水印
     *
     * @param array $param 更新数据
     * @return bool 布尔类型的返回结果
     */
    public function updateStoreWM($param){
        if (empty($param)){
            return false;
        }
        if (is_array($param)){
            $tmp = array();
            foreach ($param as $k => $v){
                $tmp[$k] = $v;
            }
            $where = " wm_id = '". $param['wm_id'] ."'";
            $result = $this->update1('store_watermark',$tmp,$where);
            return $result;
        }else {
            return false;
        }
    }

    /**
     * 删除水印
     *
     * @param int $id 记录ID
     * @return bool 布尔类型的返回结果
     */
    public function delStoreWM($id){
        if (intval($id) > 0){
            $where = " wm_id = '". intval($id) ."'";
            $result = $this->delete1('store_watermark',$where);
            return $result;
        }else {
            return false;
        }
    }
}
