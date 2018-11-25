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
class gongboControl extends BaseHomeControl{
    public function indexOp(){
        $rs = Model('goods_class')->where('1=1')->update(array('commis_rate'=>'11.7'));
        if($rs){
            echo "goods_class:修改成功";
        }else{
            echo "goods_class:修改失败";
        }

        $rs = Model('store_bind_class')->where('1=1')->update(array('commis_rate'=>'11.7'));
        if($rs){
            echo "<br/>store_bind_class:修改成功";
        }else{
            echo "store_bind_class:修改失败";
        }
    }
}
