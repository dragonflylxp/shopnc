<?php
/**
 * 运费模板
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

class transportModel extends Model {

    public function __construct(){
        parent::__construct();
    }

    /**
     * 增加运费模板
     *
     * @param unknown_type $data
     * @return unknown
     */
    public function addTransport($data){
        return $this->table('transport')->insert($data);
    }

    /**
     * 增加各地区详细运费设置
     *
     * @param unknown_type $data
     * @return unknown
     */
    public function addExtend($data){
        return $this->table('transport_extend')->insertAll($data);
    }

    /**
     * 取得一条运费模板信息
     *
     * @return unknown
     */
    public function getTransportInfo($condition){
        return $this->table('transport')->where($condition)->find();
    }

    /**
     * 取得一条运费模板扩展信息
     *
     * @return unknown
     */
    public function getExtendInfo($condition){
        return $this->table('transport_extend')->where($condition)->select();
    }

    /**
     * 删除运费模板
     *
     * @param unknown_type $id
     * @return unknown
     */
    public function delTansport($condition){
        try {
            $this->beginTransaction();
            $delete = $this->table('transport')->where($condition)->delete();
            if ($delete) {
                $delete = $this->table('transport_extend')->where(array('transport_id'=>$condition['id']))->delete();
            }
            if (!$delete) throw new Exception();
            $this->commit();
        }catch (Exception $e){
            $model->rollback();
            return false;
        }
        return true;
    }

    /**
     * 删除运费模板扩展信息
     *
     * @param unknown_type $transport_id
     * @return unknown
     */
    public function delExtend($transport_id){
        return $this->table('transport_extend')->where(array('transport_id'=>$transport_id))->delete();
    }

    /**
     * 取得运费模板列表
     *
     * @param unknown_type $condition
     * @param unknown_type $page
     * @param unknown_type $order
     * @return unknown
     */
    public function getTransportList($condition=array(), $pagesize = '', $order = 'id desc'){
        return $this->table('transport')->where($condition)->order($order)->page($pagesize)->select();
    }

    /**
     * 取得扩展信息列表
     *
     * @param unknown_type $condition
     * @param unknown_type $order
     * @return unknown
     */
    public function getExtendList($condition=array(), $order=''){
        return $this->table('transport_extend')->where($condition)->order($order)->select();
    }

    public function transUpdate($data,$condition = array()){
        return $this->table('transport')->where($condition)->update($data);
    }

    /**
     * 检测运费模板是否正在被使用
     *
     */
    public function isUsing($id){
        if (!is_numeric($id)) return false;
        $goods_info = $this->table('goods')->where(array('transport_id'=>$id))->field('goods_id')->find();
        return $goods_info ? true : false;
    }

    /**
     * 计算某地区某运费模板ID下的商品总运费
     *
     * @param int $transport_id
     * @param int $area_id
     * @return number/boolean
     */
    public function calc_transport($transport_id, $area_id,$goods_list = array()) {
        if (empty($transport_id) || empty($area_id)) return 0;
        $transport = $this->getTransportInfo(array('id'=> $transport_id));
        $extend_list = $this->getExtendList(array('transport_id'=> $transport_id));
        if (empty($transport) || empty($extend_list)) {
            return false;
        } else {
            $calc_total = false;
            $_array = array();
            foreach ($extend_list as $v) {
                if (strpos($v['area_id'],",".$area_id.",") !== false){
                    $_array = $v;
                    break;
                }
            }
            if (!empty($_array)) {
                $_type = $transport['goods_trans_type'];//计费规则:1是按件数2是按重量3是按体积
                $_array['xnum'] = floatval($_array['xnum']);
                $_m = 0;
                if ($transport['goods_fee_type']==1 && $_array['xnum']>0) {//运费承担:1买家承担2是商家承担
                    foreach ($goods_list as $goods_info) {
                        $_n = intval($goods_info['n']);//购买数量
                        $_v = $goods_info['v'];//商品(重量/体积)
                        if ($_type==1) $_v = 1;
                        $_m += $_n*$_v;
                    }
                    
                    if ($_m<=$_array['snum']) {//首(件、重、体积)
                        $calc_total = $_array['sprice'];
                    } else {
                        $calc_total = sprintf('%.2f',($_array['sprice'] + ceil(($_m-$_array['snum'])/$_array['xnum'])*$_array['xprice']));
                    }
                } else {
                    $calc_total = 0;
                }
            }
            return $calc_total;
        }
    }

    /**
     * 商品运费
     */
    public function goods_trans_calc($goods_info, $area_id) {
        $transport_id = $goods_info['transport_id'];
        
        if (empty($transport_id) || empty($area_id)) return false;
        $transport = $this->getTransportInfo(array('id'=> $transport_id));
        $extend_list = $this->getExtendList(array('transport_id'=> $transport_id));
        if (empty($transport) || empty($extend_list)) {
            return false;
        } else {
            $calc_total = false;
            $_n = intval($goods_info['goods_n']);//购买数量
            $_v = $goods_info['goods_trans_v'];//商品(重量/体积)
            $_type = $transport['goods_trans_type'];//计费规则:1是按件数2是按重量3是按体积
            
            if ($_n>0) {
                $_array = array();
                foreach ($extend_list as $v) {
                    if (strpos($v['area_id'],",".$area_id.",") !== false){
                        $_array = $v;
                        break;
                    }
                }
                if (!empty($_array)) {
                    $_array['xnum'] = floatval($_array['xnum']);
                    if ($transport['goods_fee_type']==1 && $_array['xnum']>0) {//运费承担:1买家承担2是商家承担
                        if ($_type==1) $_v = 1;
                        $_m = $_n*$_v;
                        if ($_m<=$_array['snum']) {//首(件、重、体积)
                            $calc_total = $_array['sprice'];
                        } else {
                            $calc_total = sprintf('%.2f',($_array['sprice'] + ceil(($_m-$_array['snum'])/$_array['xnum'])*$_array['xprice']));
                        }
                    } else {
                        $calc_total = 0;
                    }
                }
            }
            return $calc_total;
        }
    }
}
