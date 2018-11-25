<?php
/**
 * 店铺装修
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
class store_visualControl extends BaseSellerControl {
    public function __construct() {
        parent::__construct();
    }

    /**
     * 店铺装修设置
     */
    public function decoration_settingOp() {
        $model_store_decoration = Model('store_decoration');

        $store_decoration_info = $model_store_decoration->getStoreDecorationInfo(array('store_id' => $_SESSION['store_id']));
        if(empty($store_decoration_info)) {
            //创建默认装修
            $param = array();
            $param['decoration_name'] = '默认装修';
            $param['store_id'] = $_SESSION['store_id'];
            $decoration_id = $model_store_decoration->addStoreDecoration($param);
        } else {
            $decoration_id = $store_decoration_info['decoration_id'];
        }

        Tpl::output('store_visual_editing', $this->store_info['store_visual_editing']);
        Tpl::output('store_decoration_only', $this->store_info['store_decoration_only']);
        Tpl::output('is_own_shop', $this->store_info['is_own_shop']);
        Tpl::output('left_bar_type', $this->store_info['left_bar_type']);
        Tpl::output('decoration_id', $decoration_id);

        $this->profile_menu('decoration_setting');
        Tpl::showpage('store_visual_editing.setting');
    }

    /**
     * 店铺装修设置保存
     */
    public function decoration_setting_saveOp() {
        $model_store_decoration = Model('store_decoration');
        $model_store = Model('store');

        $store_decoration_info = $model_store_decoration->getStoreDecorationInfo(array('store_id' => $_SESSION['store_id']));
        if(empty($store_decoration_info)) {
            showDialog('参数错误');
        }

        $update = array();
        if(empty($_POST['store_visual_editing'])) {
            $update['store_visual_editing'] = 0;
        } else {
            $update['store_visual_editing'] = $store_decoration_info['decoration_id'];
        }
        $update['store_decoration_only'] = intval($_POST['store_decoration_only']);
        $update['left_bar_type'] = ($this->store_info['is_own_shop'] && $_POST['left_bar_type'] == 2) ? 2 : 1;
        
        $result = $model_store->editStore($update, array('store_id' => $_SESSION['store_id']));
        if($result) {
            showDialog(L('nc_common_save_succ'), '', 'succ');
        } else {
            showDialog(L('nc_common_save_fail'));
        }
    }
    /**
     * 店铺可视化装修
     */
    public function decoration_editOp() {
        $decoration_id = intval($_GET['decoration_id']);

        $model_store_decoration = Model('store_decoration');
        $visual = Model('visual');
        $decoration_info = $model_store_decoration->getStoreDecorationInfoDetail($decoration_id, $_SESSION['store_id']);
        if($decoration_info) {
            $this->_output_decoration_info($decoration_info);
        } else {
            showMessage(L('param_error'), '', 'error');
        }
        $adminru['ru_id']= $_SESSION['store_id'];
        $pc_page = get_seller_templates($adminru['ru_id'], 0, '', 1);
        $domain = SHOP_SITE_URL;
        
        $head = $visual->getleft_attr('head', $adminru['ru_id'], $pc_page['tem']);
     
		$content = $visual->getleft_attr('content', $adminru['ru_id'], $pc_page['tem']);
		$theme_extension = 1;
		
		Tpl::output('theme_extension', $theme_extension);
		Tpl::output('is_temp', $pc_page['is_temp']);
		Tpl::output('pc_page', $pc_page);
		Tpl::output('head', $head);
		Tpl::output('content', $content);
		Tpl::output('domain', $domain);
		Tpl::output('vis_section', 'vis_seller');
		Tpl::output('seller_layout_no_menu', true);
		Tpl::showpage('visual_editing.dwt');

    }
    

    /**
     * 图片上传
     */
    public function decoration_album_uploadOp() {
        $store_id = $_SESSION ['store_id'];

        $data = array();

        //判断装修相册数量限制，预设100
        if($this->store_info['store_decoration_image_count'] > 100) {
            $data['error'] = '相册已满，请首先删除无用图片';
            echo json_encode($data);die;
        }

        //上传图片
        $upload = new UploadFile();
        $upload->set('default_dir', ATTACH_STORE_DECORATION . DS . $store_id);
        $upload->set('max_size', C('image_max_filesize'));
        $upload->set('fprefix', $store_id);
        $result_file = $upload->upfile('file');
        if($result_file) {
            $image = $upload->file_name;
        } else {
            $error = $upload->error;
            $data['error'] = $error;
            echo json_encode($data);die;
        }

        //图片尺寸
        list($width, $height) = getimagesize(BASE_UPLOAD_PATH . DS . ATTACH_STORE_DECORATION . DS . $store_id . DS . $image);

        //图片原始名称
        $image_origin_name_array = explode('.', $_FILES["file"]["name"]);

        //插入相册表
        $param = array();
        $param['image_name'] = $image;
        $param['image_origin_name'] = $image_origin_name_array['0'];
        $param['image_width'] = $width;
        $param['image_height'] = $height;
        $param['image_size'] = intval($_FILES['file']['size']);
        $param['store_id'] = $store_id;
        $param['upload_time'] = TIMESTAMP;
        $result = Model('store_decoration_album')->addStoreDecorationAlbum($param);

        if($result) {
            //装修相册计数加1
            Model('store')->editStore(
                array('store_decoration_image_count' => array('exp', 'store_decoration_image_count+1')),
                array('store_id' => $_SESSION['store_id'])
            );

            $data['image_name'] = $image;
            $data['image_url'] = getStoreDecorationImageUrl($image, $store_id);
        } else {
            $data['error'] = '上传失败';
        }
        echo json_encode($data);die;
    }

    /**
     * 图片删除
     */
    public function decoration_album_delOp() {
        $image_id = intval($_POST['image_id']);

        $data = array();

        $model_store_decoration_album = Model('store_decoration_album');

        //验证图片权限
        $condition = array();
        $condition['image_id'] = $image_id;
        $condition['store_id'] = $_SESSION['store_id'];
        $result = $model_store_decoration_album->delStoreDecorationAlbum($condition);
        if($result) {
            //装修相册计数减1
            if($this->store_info['store_decoration_image_count'] > 0) {
                Model('store')->editStore(
                    array('store_decoration_image_count' => array('exp', 'store_decoration_image_count-1')),
                    array('store_id' => $_SESSION['store_id'])
                );
            }

            $data['message'] = '删除成功';
        } else {
            $data['error'] = '删除失败';
        }
        echo json_encode($data);die;
    }



    /**
     * 输出装修设置
     */
    private function _output_decoration_info($decoration_info) {
        $model_store_decoration = Model('store_decoration');
        $decoration_background_style = $model_store_decoration->getDecorationBackgroundStyle($decoration_info['decoration_setting']);
        Tpl::output('decoration_background_style', $decoration_background_style);
        Tpl::output('decoration_nav', $decoration_info['decoration_nav']);
        Tpl::output('decoration_banner', $decoration_info['decoration_banner']);
        Tpl::output('decoration_setting', $decoration_info['decoration_setting']);
        Tpl::output('block_list', $decoration_info['block_list']);
    }


    /**
     * 用户中心右边，小导航
     *
     * @param string    $menu_type  导航类型
     * @param string    $menu_key   当前导航的menu_key
     * @return
     */
    private function profile_menu($menu_key='') {
        $menu_array = array(
            1=>array('menu_key'=>'decoration_setting','menu_name'=>'店铺装修','menu_url'=>urlShop('store_decoration', 'decoration_setting'))

        );
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }


}
