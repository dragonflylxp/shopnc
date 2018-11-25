<?php
/**
 * 分销商品列表
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net
 * @link       http://www.shopec.net
 * @since      File available since Release v1.1
 */
use shopec\Tpl;
use shopec\Log;

defined('Inshopec') or exit('Access Invalid!');

class searchControl extends BaseDistributeControl
{


    //每页显示商品数
    const PAGESIZE = 30;

    //模型对象
    private $_model_search;

    public function indexOp()
    {
        Language::read('home_goods_class_index');
        $this->_model_search = Model('distri_search');
        //默认分类，从而显示相应的属性和品牌
        $default_classid = intval($_GET['cate_id']);

        if ($_GET['keyword'] != '') {
            if (cookie('his_sh') == '') {
                $his_sh_list = array();
            } else {
                $his_sh_list = explode('~', cookie('his_sh'));
            }
            if (strlen($_GET['keyword']) <= 30 && !in_array($_GET['keyword'], $his_sh_list)) {
                if (array_unshift($his_sh_list, $_GET['keyword']) > 8) {
                    array_pop($his_sh_list);
                }
            }
            setNcCookie('his_sh', implode('~', $his_sh_list), 2592000);
            //从TAG中查找分类
            $goods_class_array = $this->_model_search->getTagCategory($_GET['keyword']);
            //取出第一个分类作为默认分类，从而显示相应的属性和品牌
            $default_classid = $goods_class_array[0];
        }

        //商品分类筛选
        $model_goods_class = Model('goods_class');
        $all_gc_list = $model_goods_class->getGoodsClassForCacheModel();
        $f_gc_list = $model_goods_class->getGoodsClassListByParentId(0);
        Tpl::output('f_gc_list',$f_gc_list);

        $s_gc_list = array();
        $s_parent_id = 0;
        $t_gc_list = array();
        $t_parent_id = 0;
        if($default_classid > 0){
            $gc_info = $all_gc_list[$default_classid];
            if($gc_info['gc_parent_id'] > 0 && isset($gc_info['child']) && $gc_info['depth'] == 2){
                $s_gc_list = $model_goods_class->getGoodsClassListByParentId($gc_info['gc_parent_id']);
                $s_parent_id = $gc_info['gc_parent_id'];

                $t_gc_list = $model_goods_class->getGoodsClassListByParentId($default_classid);
                $t_parent_id = $default_classid;
            }elseif($gc_info['gc_parent_id'] > 0 && !isset($gc_info['child']) && $gc_info['depth'] == 2){
                $s_gc_list = $model_goods_class->getGoodsClassListByParentId($gc_info['gc_parent_id']);
                $s_parent_id = $gc_info['gc_parent_id'];

                $t_gc_list = $model_goods_class->getGoodsClassListByParentId($default_classid);
                $t_parent_id = $default_classid;
            }elseif($gc_info['gc_parent_id'] > 0 && !isset($gc_info['child']) && $gc_info['depth'] == 3){
                $t_gc_list = $model_goods_class->getGoodsClassListByParentId($gc_info['gc_parent_id']);
                $t_parent_id = $gc_info['gc_parent_id'];
                $s_parent_id = $all_gc_list[$t_parent_id]['gc_parent_id'];
                $s_gc_list = $model_goods_class->getGoodsClassListByParentId($s_parent_id);
            }elseif($gc_info['gc_parent_id'] == 0){
                $s_gc_list = $model_goods_class->getGoodsClassListByParentId($default_classid);
                $s_parent_id = $default_classid;
            }
        }
        Tpl::output('s_gc_list',$s_gc_list);
        Tpl::output('s_parent_id',$s_parent_id);
        Tpl::output('t_gc_list',$t_gc_list);
        Tpl::output('t_parent_id',$t_parent_id);

        Tpl::output('default_classid', $default_classid);

        //获得经过属性过滤的商品信息
        list($goods_param, $brand_array, $initial_array, $attr_array, $checked_brand, $checked_attr) = $this->_model_search->getAttr($_GET, $default_classid);

        Tpl::output('brand_array', $brand_array);
        Tpl::output('initial_array', $initial_array);
        Tpl::output('attr_array', $attr_array);
        Tpl::output('checked_brand', $checked_brand);
        Tpl::output('checked_attr', $checked_attr);

        $model_goods = Model('goods');

        //查库搜索

        //处理排序
        $order = 'is_own_shop desc,goods_commonid desc';
        if (in_array($_GET['key'], array('1', '2', '3'))) {
            $sequence = $_GET['order'] == '1' ? 'asc' : 'desc';
            $order = str_replace(array('1', '2', '3'), array('sale_count', 'click_count', 'goods_price'), $_GET['key']);
            $order .= ' ' . $sequence;
        }

        // 字段
        $fields = "goods_commonid,goods_name,goods_jingle,gc_id,store_id,store_name,goods_price,goods_image,sale_count,click_count,gc_id_3,gc_id_1,gc_id_2,goods_verify,goods_state,is_own_shop,areaid_1,dis_commis_rate";

        $condition = array();
        if (isset($goods_param['class'])) {
            $condition['gc_id_' . $goods_param['class']['depth']] = $goods_param['class']['gc_id'];
        }
        if (intval($_GET['b_id']) > 0) {
            $condition['brand_id'] = intval($_GET['b_id']);
        }
        if ($_GET['keyword'] != '') {
            $condition['goods_name|goods_jingle'] = array('like', '%' . $_GET['keyword'] . '%');
        }
        if (intval($_GET['area_id']) > 0) {
            $condition['areaid_1'] = intval($_GET['area_id']);
        }

        $condition['is_dis'] = 1;

        if (isset($goods_param['goodsid_array'])) {
            $condition['goods_commonid'] = array('in', $goods_param['goodsid_array']);
        }
        if (C('dbdriver') == 'oracle') {
            $oracle_fields = array();
            $fields = explode(',', $fields);
            foreach ($fields as $val) {
                $oracle_fields[] = 'min(' . $val . ') ' . $val;
            }
            $fields = implode(',', $oracle_fields);
        }

        $goods_list = $model_goods->getGoodsCommonOnlineList($condition, $fields, self::PAGESIZE, $order);

        Tpl::output('show_page', $model_goods->showpage(7));

        if (!empty($goods_list)) {
            //查库搜索
            $commonid_array = array(); // 商品公共id数组
            $storeid_array = array();       // 店铺id数组
            foreach ($goods_list as $value) {
                $commonid_array[] = $value['goods_commonid'];
                $storeid_array[] = $value['store_id'];
            }
            $commonid_array = array_unique($commonid_array);
            $storeid_array = array_unique($storeid_array);
            // 商品多图
            $goodsimage_more = $model_goods->getGoodsImageList(array('goods_commonid' => array('in', $commonid_array)), '*', 'is_default desc,goods_image_id asc');
            // 店铺
            $store_list = Model('store')->getStoreMemberIDList($storeid_array);

            //搜索的关键字
            $search_keyword = $_GET['keyword'];
            foreach ($goods_list as $key => $value) {
                foreach ($goodsimage_more as $v) {
                    if ($value['goods_commonid'] == $v['goods_commonid'] && $value['store_id'] == $v['store_id'] && $v['is_default'] == 1) {
                        $goods_list[$key]['image'][] = $v['goods_image'];
                    }
                }
                // 店铺的开店会员编号
                $store_id = $value['store_id'];
                $goods_list[$key]['member_id'] = $store_list[$store_id]['member_id'];
                $goods_list[$key]['store_domain'] = $store_list[$store_id]['store_domain'];

                //将关键字置红
                if ($search_keyword) {
                    $goods_list[$key]['goods_name_highlight'] = str_replace($search_keyword, '<font style="color:#f00;">' . $search_keyword . '</font>', $value['goods_name']);
                } else {
                    $goods_list[$key]['goods_name_highlight'] = $value['goods_name'];
                }
            }
        }
        Tpl::output('goods_list', $goods_list);
        if ($_GET['keyword'] != '') {
            Tpl::output('show_keyword', $_GET['keyword']);
        } else {
            Tpl::output('show_keyword', $goods_param['class']['gc_name']);
        }

        // SEO
        if ($_GET['keyword'] == '') {
            $seo_class_name = $goods_param['class']['gc_name'];
            if (is_numeric($_GET['cate_id']) && empty($_GET['keyword'])) {
                $seo_info = $model_goods_class->getKeyWords(intval($_GET['cate_id']));
                if (empty($seo_info[1])) {
                    $seo_info[1] = C('site_name') . ' - ' . $seo_class_name;
                }
                Model('seo')->type($seo_info)->param(array('name' => $seo_class_name))->show();
            }
        } elseif ($_GET['keyword'] != '') {
            Tpl::output('html_title', (empty($_GET['keyword']) ? '' : $_GET['keyword'] . ' - ') . C('site_name') . L('nc_common_search'));
        }

        // 当前位置导航
        $nav_link_list = $model_goods_class->getGoodsClassNav(intval($_GET['cate_id']));
        Tpl::output('nav_link_list', $nav_link_list);

        // 得到自定义导航信息
        $nav_id = intval($_GET['nav_id']) ? intval($_GET['nav_id']) : 0;
        Tpl::output('index_sign', $nav_id);

        // 地区
        $province_array = Model('area')->getTopLevelAreas();
        Tpl::output('province_array', $province_array);

        loadfunc('search');

        Tpl::showpage('search');
    }

    /**
     * 立即推广 & 获取二维码
     */
    public function distri_addOp()
    {
        $data = array();
        $data['stat'] = 'fail';
        if ($_SESSION['member_id'] > 0) {
            $member_info = Model('member')->getMemberInfoByID($_SESSION['member_id'], 'distri_state');
            if ($member_info['distri_state'] == 2) {
                $mode = Model('goods');
                $goods_commonid = intval($_GET['id']);
                $goods_common = $mode->getGoodsCommonInfoByID($goods_commonid);
                if (intval($goods_commonid) > 0 && !empty($goods_common)) {
                    $stat = false;
                    $model_dis = Model('dis_goods');
                    $dis_goods = $model_dis->getDistriGoodsInfo(array('member_id' => $_SESSION['member_id'], 'goods_commonid' => $goods_commonid,'distri_goods_state' => 1));

                    $param = array();
                    $distri_id = 0;
                    if (empty($dis_goods)) {
                        $param['goods_commonid'] = $goods_common['goods_commonid'];
                        $param['goods_name'] = $goods_common['goods_name'];
                        $param['goods_image'] = cthumb($goods_common['goods_image'], 240, $goods_common['store_id']);
                        $param['member_id'] = $_SESSION['member_id'];
                        $param['member_name'] = $_SESSION['member_name'];
                        $param['distri_time'] = time();
                        $param['store_id'] = $goods_common['store_id'];
                        $param['store_name'] = $goods_common['store_name'];
                        $param['distri_goods_state'] = 1;
                        $stat = $model_dis->addDistriGoods($param);
                        $distri_id = $stat;
                    } else {
                        $param['goods_name'] = $goods_common['goods_name'];
                        $param['goods_image'] = cthumb($goods_common['goods_image'], 240, $goods_common['store_id']);
                        $stat = $model_dis->updateDistriGoods(array('distri_id' => $dis_goods['distri_id']), $param);
                        $distri_id = $dis_goods['distri_id'];
                    }
                    $param['goods_price'] = $goods_common['goods_price'];
                    $param['distri_id'] = $distri_id;
                    if ($stat) {
                        $data['stat'] = 'succ';
                        $data['data'] = $param;
                    }
                } else {
                    $data['msg'] = "所选商品无效";
                    $data['url'] = '';
                }
            } else {
                $data['msg'] = '请先认证成为分销员';
                $data['url'] = urlDistribute('distri_join', 'index');
            }
        } else {
            $data['msg'] = "请先登录";
            $data['url'] = urlMember('login', 'index', array('ref_rul' => getReferer()));
        }
        echo json_encode($data);
    }

}