<?php
/**
 * 交易快照
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
class order_snapshotModel extends Model {

    public function __construct() {
        parent::__construct('order_snapshot');
    }

    /**
     * 由订单商品表主键取得交易快照信息
     * @param int $rec_id
     * @param int $goods_id
     * @return array
     */
    public function getSnapshotInfoByRecid($rec_id,$goods_id) {
        $info = $this->where(array('rec_id'=>$rec_id))->find();
        if (empty($info['file_dir'])) {
            return $this->createSphot($rec_id, $goods_id,$info);
        }
        return $info;
    }

    public function createSphot($rec_id,$goods_id,$snapshot_info = array()) {
        $model_goods = Model('goods');
        $goods_info = $model_goods->getGoodsInfo(array('goods_id'=>$goods_id),'goods_serial,goods_body,goods_commonid');
        $goods_common_info = $model_goods->getGoodsCommonInfo(array('goods_commonid'=>$goods_info['goods_commonid']),'brand_name,goods_attr,goods_custom,goods_body,plateid_top,plateid_bottom');
        $goods_common_info['goods_attr'] = unserialize($goods_common_info['goods_attr']);
        $goods_common_info['goods_custom'] = unserialize($goods_common_info['goods_custom']);
        $_attr = array();
        $_attr['货号'] = $goods_info['goods_serial'];
        $_attr['品牌'] = $goods_common_info['brand_name'];
        if (is_array($goods_common_info['goods_attr']) && !empty($goods_common_info['goods_attr'])) {
            foreach($goods_common_info['goods_attr'] as $v) {
                $_attr[$v['name']] = end($v);
            }            
        }
        if (is_array($goods_common_info['goods_custom']) && !empty($goods_common_info['goods_custom'])) {
            foreach($goods_common_info['goods_custom'] as $v) {
                $_attr[$v['name']] = $v['value'];
            }
        }

        $info = array();
        $info['rec_id'] = $rec_id;
        $info['goods_id'] = $goods_id;
        $info['create_time'] = time();
        $info['goods_attr'] = serialize($_attr);
        
        $dir = BASE_UPLOAD_PATH.DS.ATTACH_PATH.DS.'order_snapshot'.DS.date('Y-m-d').DS;
        if(!is_dir($dir)){
            mkdir($dir,0755,true);
        }
        $file_name = $goods_id.'-'.$rec_id.'-'.md5(rand(100,999)).'.php';
        
        $web_html ="<?php defined('Inshopec') or exit('Access Invalid!');?>";
        $model_plate = Model('store_plate');
        // 顶部关联版式
        if ($goods_common_info['plateid_top'] > 0) {
            $plate_top = $model_plate->getStorePlateInfoByID($goods_common_info['plateid_top']);
            $web_html .='<div class="top-template">'.$plate_top['plate_content'].'</div>';
        }
        $goods_body = $goods_info['goods_body'] == '' ? $goods_common_info['goods_body'] : $goods_info['goods_body'];
        $web_html .='<div class="default">'.$goods_body.'</div>';
        // 底部关联版式
        if ($goods_common_info['plateid_bottom'] > 0) {
            $plate_bottom = $model_plate->getStorePlateInfoByID($goods_common_info['plateid_bottom']);
            $web_html .='<div class="bottom-template">'.$plate_bottom['plate_content'].'</div>';
        }
        file_put_contents($dir.$file_name,$web_html);
        if (empty($snapshot_info)) {
            $info['file_dir'] = date('Y-m-d').DS.$file_name;
            $this->insert($info);
            return $info;
        } else {
            $file_dir = date('Y-m-d').DS.$file_name;
            $this->where(array('rec_id'=> $snapshot_info['rec_id']))->update(array('file_dir'=> $file_dir));
            $snapshot_info['file_dir'] = date('Y-m-d').DS.$file_name;
            return $snapshot_info;
        }
    }

}
