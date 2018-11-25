<?php
/**
 * 分销推广
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net
 * @link       http://www.shopec.net
 * @since      File available since Release v1.1
 */
use shopec\Tpl;


defined('Inshopec') or exit('Access Invalid!');

class distri_goodsControl extends BaseHomeControl
{
    public function __construct()
    {
        parent::__construct();
    }

    public function indexOp()
    {
        $distri_id = intval($_GET['goods_id']);
        $model_dis_goods = Model('dis_goods');
        $condition = array();
        $condition['distri_id'] = $distri_id;
        $dis_goods = $model_dis_goods->getDistriGoodsInfo($condition);
        $goods_commonid = $dis_goods['goods_commonid'];
        $model_goods = Model('goods');
        $condition = array();
        $condition['goods_commonid'] = $goods_commonid;
        $goods = $model_goods->getGoodsInfo($condition);
        $goods_id = $goods['goods_id'];
        if ($goods_id && $dis_goods['distri_goods_state'] == 1) {
            $buyer_id = $_SESSION['member_id'];
            if (empty($buyer_id)) {
                $key = $_COOKIE['key'];
                if ($key) {
                    $model_mb_user_token = Model('mb_user_token');
                    $mb_user_token_info = $model_mb_user_token->getMbUserTokenInfoByToken($key);
                    if ($mb_user_token_info['member_id']) $buyer_id = $mb_user_token_info['member_id'];
                }
            }
            if ($goods['is_dis']) {
                $t = 3600*24;
                if ($buyer_id) {
                    $model_dis_goods->addDisCart($goods_commonid,$dis_goods['member_id'],$buyer_id,$t);
                }
                setNcCookie('dis_' . $goods_commonid, $dis_goods['member_id'],$t);
            }
            $mobilebrowser_list = 'mobile|nokia|iphone|ipad|android|samsung|htc|blackberry';
            if (preg_match("/$mobilebrowser_list/i", $_SERVER['HTTP_USER_AGENT'])) {
                @header('Location: ' . C('wap_site_url') . '/tmpl/product_detail.html?goods_id=' . $goods_id . '&dis_id=' . $distri_id);
                exit;
            } else {
                @header('Location: ' . urlShop('goods', 'index', array('goods_id' => $goods_id)));
                exit;
            }
        } else {
            $mobilebrowser_list = 'mobile|nokia|iphone|ipad|android|samsung|htc|blackberry';
            if (preg_match("/$mobilebrowser_list/i", $_SERVER['HTTP_USER_AGENT'])) {
                if ($goods_id) {
                    @header('Location: ' . C('wap_site_url') . '/tmpl/product_detail.html?goods_id=' . $goods_id);
                    exit;
                } else {
                    @header('Location: ' . C('wap_site_url'));
                    exit;
                }
            } else {
                if ($goods_id) {
                    @header('Location: ' . urlShop('goods', 'index', array('goods_id' => $goods_id)));
                    exit;
                } else {
                    @header('Location: ' . C('shop_site_url'));
                    exit;
                }
            }
        }
    }

    /**
     * 分销商品详情
     */
    public function goods_detailOp()
    {
        $goods_commonid = intval($_GET['goods_id']);
        $model_goods = Model('goods');
        $condition = array();
        $condition['goods_commonid'] = $goods_commonid;
        $goods = $model_goods->getGoodsInfo($condition);
        $goods_id = $goods['goods_id'];
        @header('Location: ' . urlShop('goods', 'index', array('goods_id' => $goods_id)));
        exit;
    }
}
