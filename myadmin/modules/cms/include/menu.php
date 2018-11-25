<?php
/**
 * 菜单
 *
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net
 * @link       http://www.shopec.net
 * @since      File available since Release v1.1
 */
defined('Inshopec') or exit('Access Invalid!');
$_menu['cms'] = array (
        'name' => $lang['nc_cms'],
        'child' => array(
                array(
                        'name' => $lang['nc_config'],
                        'child' => array(
                                'cms_manage' => $lang['nc_cms_manage'],
                                'cms_index' => $lang['nc_cms_index_manage'],
                                'cms_navigation' => $lang['nc_cms_navigation_manage'],
                                'cms_tag' => $lang['nc_cms_tag_manage'],
                                'cms_comment' => $lang['nc_cms_comment_manage']
                        )
                ),
                array(
                        'name' => '专题',
                        'child' => array(
                                'cms_special' => $lang['nc_cms_special_manage']
                        )
                ),
                array(
                        'name' => '文章',
                        'child' => array(
                                'cms_article_class' => '文章分类',
                                'cms_article' => '文章管理'
                        )
                ),
                array(
                        'name' => '画报',
                        'child' => array(
                                'cms_picture_class' => '画报分类',
                                'cms_picture' => '画报管理'
                        )
                )
));