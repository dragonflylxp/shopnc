<?php
/**
 * 广告展示
 *
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
class advControl {
    /**
     *
     * 广告展示
     */
    public function advshowOp(){
        import('function.adv');
        $ap_id = intval($_GET['ap_id']);
        echo advshow($ap_id,'js');
    }
    /**
     * 异步调用广告
     *
     */
    public function get_adv_listOp(){
        $ap_ids = $_GET['ap_ids'];
        $list = array();
        if (!empty($ap_ids) && is_array($ap_ids)) {
            import('function.adv');
            foreach ($ap_ids as $key => $value) {
                $ap_id = intval($value);//广告位编号
                $adv_info = advshow($ap_id,'array');
                if (!empty($adv_info) && is_array($adv_info)) {
                    $adv_info['adv_url'] = htmlspecialchars_decode($adv_info['adv_url']);
                    $list[$ap_id] = $adv_info;
                }
            }
        }
        echo $_GET['callback'].'('.json_encode($list).')';
        exit;
    }
}
