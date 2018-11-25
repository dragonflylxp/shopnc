<?php
/**
 * 清理缓存
 *
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net
 * @link       http://www.shopec.net
 * @since      File available since Release v1.1
 */
use shopec\Tpl;

defined('Inshopec') or exit('Access Invalid!');

class cacheControl extends SystemControl
{
    protected $cacheItems = array(
        'setting',          // 基本缓存
        'seo',              // SEO缓存
        'groupbuy_price',   // 团购价格区间
        'nav',              // 底部导航缓存
        'express',          // 快递公司
        'store_class',      // 店铺分类
        'store_grade',      // 店铺等级
        'store_msg_tpl',    // 店铺消息
        'member_msg_tpl',   // 用户消息
        'consult_type',     // 咨询类型
        'circle_level',     // 圈子成员等级
        'admin_menu',       // 后台菜单
        'area',              // 地区
        'contractitem'      //消费者保障服务
    );

    public function __construct() {
        parent::__construct();
        Language::read('cache');
    }

    public function indexOp() {
        $this->clearOp();
    }

    /**
     * 清理缓存
     */
    public function clearOp() {
        if (!chksubmit()) {
            Tpl::showpage('cache.clear');
            return;
        }

        $lang = Language::getLangContent();

        // 清理所有缓存
        if ($_POST['cls_full'] == 1) {
            foreach ($this->cacheItems as $i) {
                dkcache($i);
            }

            // 商品分类
            dkcache('gc_class');
            dkcache('all_categories');
            dkcache('goods_class_seo');
            dkcache('class_tag');

            // 广告
            Model('adv')->makeApAllCache();

            // 首页及频道
            Model('web_config')->updateWeb(array('web_show'=>1),array('web_html'=>''));
            delCacheFile('index');
            dkcache('channel');

            if (C('cache_open')) {
                dkcache('index/article');
            }

            // 分销首页管理
            Model('dis_web_config')->updateWeb(array('web_show'=>1),array('web_html'=>''));
            delCacheFile('index');

        } else {
            $todo = (array) $_POST['cache'];

            foreach ($this->cacheItems as $i) {
                if (in_array($i, $todo)) {
                    dkcache($i);
                }
            }

            // 商品分类
            if (in_array('goodsclass', $todo)) {
                dkcache('gc_class');
                dkcache('all_categories');
                dkcache('goods_class_seo');
                dkcache('class_tag');
            }

            // 广告
            if (in_array('adv', $todo)) {
                Model('adv')->makeApAllCache();
            }

            // 首页及频道
            if (in_array('index', $todo)) {
                Model('web_config')->updateWeb(array('web_show'=>1),array('web_html'=>''));
                delCacheFile('index');
                dkcache('channel');

                if (C('cache_open')) {
                    dkcache('index/article');
                }
            }

            // 分销首页管理
            if(in_array('distri_index' , $todo)){
                Model('dis_web_config')->updateWeb(array('web_show'=>1),array('web_html'=>''));
                delCacheFile('index');
            }

        }

        $this->log(L('cache_cls_operate'));
        showMessage($lang['cache_cls_ok']);
    }
}
