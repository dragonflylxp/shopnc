<?php
/**
 * 默认展示页面
 *
 *
 *
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net
 * @link       http://www.shopec.net
 * @since      File available since Release v1.1
 */
use shopec\Tpl;

defined('Inshopec') or exit('Access Invalid!');

class indexControl extends BaseDistributeControl{
    function indexOp(){
        //板块信息
        $model_web_config = Model('dis_web_config');
        $web_html = $model_web_config->getWebHtml('index');
        Tpl::output('web_html',$web_html);
        Tpl::showpage('index');
    }

    //json输出商品分类
    public function josn_classOp() {
        /**
         * 实例化商品分类模型
         */
        $model_class        = Model('goods_class');
        $goods_class        = $model_class->getGoodsClassListByParentId(intval($_GET['gc_id']));
        $array              = array();
        if(is_array($goods_class) and count($goods_class)>0) {
            foreach ($goods_class as $val) {
                $array[$val['gc_id']] = array('gc_id'=>$val['gc_id'],'gc_name'=>htmlspecialchars($val['gc_name']),'gc_parent_id'=>$val['gc_parent_id'],'commis_rate'=>$val['commis_rate'],'gc_sort'=>$val['gc_sort']);
            }
        }
        /**
         * 转码
         */
        if (strtoupper(CHARSET) == 'GBK'){
            $array = Language::getUTF8(array_values($array));//网站GBK使用编码时,转换为UTF-8,防止json输出汉字问题
        } else {
            $array = array_values($array);
        }
        echo $_GET['callback'].'('.json_encode($array).')';
    }
}