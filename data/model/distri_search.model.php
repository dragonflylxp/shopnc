<?php
/**
 * 搜索
 *
 *
 *
 *
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net
 * @link       http://www.shopec.net
 * @since      File available since Release v1.1
 */
use shopec\Log;

defined('Inshopec') or exit('Access Invalid!');

class distri_searchModel{

    /**
     * 取得商品分类详细信息
     *
     * @param array $search_param 需要的参数内容
     * @return array 数组类型的返回结果
     */
    public function getAttr($get, $default_classid){
        if(!empty($get['a_id'])){
            $attr_ids = explode('_', $get['a_id']);
        }
        $data = array();

        // 获取当前的分类内容
        $class_array = Model('goods_class')->getGoodsClassForCacheModel();
        $data['class'] = $class_array[$get['cate_id']];
        if (empty($data['class']['child']) && empty($data['class']['childchild'])) {
            // 根据属性查找商品
            if (is_array($attr_ids)) {
                // 商品id数组
                $goodsid_array = array();
                $data['sign'] = false;
                foreach ($attr_ids as $val) {
                    $where = array();
                    $where['attr_value_id'] = $val;
                    if ($data['sign']) {
                        $where['goods_commonid'] = array('in', $goodsid_array);
                    }
                    $goodsattrindex_list = Model('goods_attr_index')->getGoodsAttrIndexList($where, 'distinct goods_commonid');
                    if (!empty($goodsattrindex_list)) {
                        $data['sign'] = true;
                        $tpl_goodsid_array = array();
                        foreach ($goodsattrindex_list as $val) {
                            $tpl_goodsid_array[] = $val['goods_commonid'];
                        }
                        $goodsid_array = $tpl_goodsid_array;
                    } else {
                        $data['goodsid_array'] = $goodsid_array = array();
                        $data['sign'] = false;
                        break;
                    }
                }
                if ($data['sign']) {
                    $data['goodsid_array'] = $goodsid_array;
                }
            }
        }

        $class = $class_array[$default_classid];
        if (empty($class['child']) && empty($class['childchild'])) {

            //获得分类对应的类型ID
            $type_id = $class['type_id'];

            //品牌列表
            $typebrand_list = Model('type')->getTypeBrandList(array('type_id' => $type_id), 'brand_id');
            if (!empty($typebrand_list)) {
                $brandid_array = array();
                foreach ($typebrand_list as $val) {
                    $brandid_array[] = $val['brand_id'];
                }
                $brand_array = Model('brand')->getBrandPassedList(array('brand_id' => array('in', $brandid_array)), 'brand_id,brand_name,brand_initial,brand_pic,show_type', null, 'show_type asc,brand_recommend desc,brand_sort asc');
                if (!empty($brand_array)) {
                    $brand_list = array();
                    $initial_array = array();
                    foreach ($brand_array as $val) {
                        $brand_list[$val['brand_id']] = $val;
                        $initial_array[] = $val['brand_initial'];
                    }
                    $brand_array = $brand_list;
                    $initial_array = array_unique($initial_array);
                    sort($initial_array);
                }
            }
            // 被选中的品牌
            $brand_id = intval($get['b_id']);
            if ($brand_id > 0 && !empty($brand_array)){
                $checked_brand = array();
                if(isset($brand_array[$brand_id])){
                    $checked_brand[$brand_id]['brand_name'] = $brand_array[$brand_id]['brand_name'];
                }
            }

            //属性列表
            $model_attribute = Model('attribute');
            $attribute_list = $model_attribute->getAttributeShowList(array('type_id' => $type_id), 'attr_id,attr_name');
            $attributevalue_list = $model_attribute->getAttributeValueList(array('type_id' => $type_id), 'attr_value_id,attr_value_name,attr_id');
            $attributevalue_list = array_under_reset($attributevalue_list, 'attr_id', 2);
            $attr_array = array();
            if (!empty($attribute_list)) {
                foreach ($attribute_list as $val) {
                    $attr_array[$val['attr_id']]['name'] = $val['attr_name'];
                    $tpl_array = array_under_reset($attributevalue_list[$val['attr_id']], 'attr_value_id');
                    $attr_array[$val['attr_id']]['value'] = $tpl_array;
                }
            }
            // 被选中的属性
            if(is_array($attr_ids) && !empty($attr_array)){
                $checked_attr = array();
                foreach ($attr_ids as $s){
                    foreach ($attr_array as $k=>$d){
                        if(isset($d['value'][$s])){
                            $checked_attr[$k]['attr_name']      = $d['name'];
                            $checked_attr[$k]['attr_value_id']  = $s;
                            $checked_attr[$k]['attr_value_name']= $d['value'][$s]['attr_value_name'];
                        }
                    }
                }
            }
        }

        return array($data, $brand_array, $initial_array, $attr_array, $checked_brand, $checked_attr);
    }

    /**
     * 从TAG中查找分类
     */
    public function getTagCategory($keyword = '') {
        if ($keyword != '') {
            // 跟据class_tag缓存搜索出与keyword相关的分类
            $tag_list = rkcache('class_tag', true);
            if (!empty($tag_list) && is_array($tag_list)) {
                foreach($tag_list as $key => $val) {
                    $tag_value = str_replace(',', '==shopec==', $val['gc_tag_value']);
                    if (strpos($tag_value, $keyword) !== false) {
                        $data[] = $val['gc_id'];
                    }
                }
            }
        }
        return $data;
    }

}
