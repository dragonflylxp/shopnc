<?php
/**
 * 商户地址模型
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
class merchant_areaModel extends Model{

    public function __construct(){
        parent::__construct('merchant_area');
    }

    /**
     * 读取列表
     * @param array $condition
     *
     */
    public function getList($condition,$page='',$order='',$field='*'){
        $result = $this->table('merchant_area')->field($field)->where($condition)->page($page)->order($order)->select();
        return $result;
    }

    /**
     * 读取树形列表
     * @param array $condition
     *
     */
    public function getTreeList(){
        $result = $this->table('merchant_area')->field('*')->where(array())->page('3500')->order('')->select();

	//提取省、市、县
        $prov = array();
        $city = array();
        $area = array();
        $idx_prov = 0;
        $idx_city = 0;
        $idx_area = 0;
        foreach($result as $k=>$r){
            if($r['area_type'] == '省份'){ 
                $prov[$idx_prov++] = array($r['area_name'], $r['area_code'], $r['area_parent_code']); 
            }
            if($r['area_type'] == '城市'){ 
                $city[$idx_city++] = array($r['area_name'], $r['area_code'], $r['area_parent_code']); 
            }
            if($r['area_type'] == '区县'){ 
                $area[$idx_area++] = array($r['area_name'], $r['area_code'], $r['area_parent_code']); 
            }
        } 

        //组织树形结构
        $ret = array();
        $ret[0] = $prov;                 //国家node
        $idx = 1;
        foreach($ret[0] as $i=>$p){
            $tmp = array();
            $cnt = 0;
            foreach($city as $j=>$c){
                if($p['1'] == $c['2']){
                    $tmp[$cnt++] = $c;
                }
            }
            array_unshift($ret[0][$i], $idx);
            $ret[$idx++] = $tmp;         //省份node
        } 

        for($i=1; $i<=$idx_prov; $i++){
            foreach($ret[$i] as $j=>$c){
                $tmp = array();
                $cnt = 0;
                foreach($area as $k=>$a){
                    if($c['1'] == $a['2']){
                        $tmp[$cnt++] = $a;
                    }
                }
                array_unshift($ret[$i][$j], $idx);
                $ret[$idx++] = $tmp;     //城市node
            }
        }


        

        



        return $result;
    }
}
?>
