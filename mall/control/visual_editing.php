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
class visual_editingControl extends VisualSellerControl {
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
            $update['store_decoration_switch'] = 0;
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
		Tpl::output('ru_id', $adminru['ru_id']);
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
            1=>array('menu_key'=>'decoration_setting','menu_name'=>'店铺装修','menu_url'=>urlShop('store_decoration', 'decoration_setting')),
            2=>array('menu_key'=>'decoration_album','menu_name'=>'装修图库','menu_url'=>urlShop('store_decoration', 'decoration_album')),
        );
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
/**
* 轮播
* by:511613932
*/      
public function shop_bannerOp() {
	$json = new JSON();	
	$smarty = new cls_template();
	$smarty->template_dir = BASE_ROOT_PATH.DS.DIR_SHOP.'/templates/'.TPL_NAME;
	$smarty->compile_dir = BASE_ROOT_PATH.DS.DIR_SHOP.'/templates/'.TPL_NAME;
	
	$result = array('content' => '', 'sgs' => '', 'mode' => '');
	$smarty->assign('temp', 'shop_banner');
	$json = new JSON();
	$result = array('content' => '', 'mode' => '');
	$is_vis = (isset($_REQUEST['is_vis']) ? intval($_REQUEST['is_vis']) : 0);
	$inid = (isset($_REQUEST['inid']) ? trim($_REQUEST['inid']) : '');
	$image_type = (isset($_REQUEST['image_type']) ? intval($_REQUEST['image_type']) : 0);

	if ($is_vis == 0) {
		$uploadImage = (isset($_REQUEST['uploadImage']) ? intval($_REQUEST['uploadImage']) : 0);
		$titleup = (isset($_REQUEST['titleup']) ? intval($_REQUEST['titleup']) : 0);
		$result['mode'] = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';
		$_REQUEST['spec_attr'] = strip_tags(urldecode($_REQUEST['spec_attr']));
		$_REQUEST['spec_attr'] = json_str_iconv($_REQUEST['spec_attr']);
		$_REQUEST['spec_attr'] = !empty($_REQUEST['spec_attr']) ? stripslashes($_REQUEST['spec_attr']) : '';

		if (!empty($_REQUEST['spec_attr'])) {
			$spec_attr = $json->decode($_REQUEST['spec_attr']);
			$spec_attr = object_to_array($spec_attr);
		}

		$defualt = '';

		if ($result['mode'] == 'lunbo') {
			$defualt = 'shade';
		}
		else if ($result['mode'] == 'advImg1') {
			$defualt = 'yesSlide';
		}

		$spec_attr['slide_type'] = isset($spec_attr['slide_type']) ? $spec_attr['slide_type'] : $defualt;
		$spec_attr['target'] = isset($spec_attr['target']) ? addslashes($spec_attr['target']) : '_blank';
		$pic_src = (isset($spec_attr['pic_src']) && ($spec_attr['pic_src'] != ',') ? $spec_attr['pic_src'] : array());
		$link = (isset($spec_attr['link']) && ($spec_attr['link'] != ',') ? explode(',', $spec_attr['link']) : array());
		$sort = (isset($spec_attr['sort']) && ($spec_attr['sort'] != ',') ? $spec_attr['sort'] : array());
		$bg_color = (isset($spec_attr['bg_color']) ? $spec_attr['bg_color'] : array());
		$title = (!empty($spec_attr['title']) && ($spec_attr['title'] != ',') ? $spec_attr['title'] : array());
		$subtitle = (!empty($spec_attr['subtitle']) && ($spec_attr['subtitle'] != ',') ? $spec_attr['subtitle'] : array());
		$pic_number = (isset($_REQUEST['pic_number']) ? intval($_REQUEST['pic_number']) : 0);
		$result['diff'] = isset($_REQUEST['diff']) ? intval($_REQUEST['diff']) : 0;
		$count = COUNT($pic_src);
		$arr = array();

		for ($i = 0; $i < $count; $i++) {
			if ($pic_src[$i]) {
				$arr[$i + 1]['pic_src'] = get_image_path($i + 1, $pic_src[$i]);
				$arr[$i + 1]['link'] = $link[$i];
				$arr[$i + 1]['sort'] = $sort[$i];
				$arr[$i + 1]['bg_color'] = $bg_color[$i];
				$arr[$i + 1]['title'] = $title[$i];
				$arr[$i + 1]['subtitle'] = $subtitle[$i];
			}
		}

		$smarty->assign('banner_list', $arr);
	}
    $model = Model('visual');
    $adminru['ru_id']= $_SESSION['store_id'];
	$album_list = $model->get_goods_gallery_album(1, $adminru['ru_id'], array('album_id', 'album_mame'), 'ru_id');
	$smarty->assign('album_list', $album_list);

	if (!empty($album_list)) {
		$pic_list = $model->getAlbumList($album_list[0]['album_id']);
		$smarty->assign('pic_list', $pic_list['list']);
		$smarty->assign('filter', $pic_list['filter']);
		$smarty->assign('album_id', $album_list[0]['album_id']);
	}

	$smarty->assign('is_vis', $is_vis);

	if ($is_vis == 0) {
		$smarty->assign('pic_number', $pic_number);
		$smarty->assign('mode', $result['mode']);
		$smarty->assign('spec_attr', $spec_attr);
		$smarty->assign('uploadImage', $uploadImage);
		$smarty->assign('titleup', $titleup);
		$result['content'] = $smarty->fetch('library/shop_banner.lbi');//require_once library_template('library/shop_banner');
	}
	else {
		$smarty->assign('image_type', 0);
		$smarty->assign('log_type', 'image');
		$smarty->assign('image_type', $image_type);
		$smarty->assign('inid', $inid);
		$result['content'] = $smarty->fetch->fetch('library/album_dialog.lbi');
	}

	exit($json->encode($result));	
}  
public function add_albun_picOp() {
	$smarty = new cls_template();
	$adminru['ru_id']= $_SESSION['store_id'];
	$smarty->template_dir = BASE_ROOT_PATH.DS.DIR_SHOP.'/templates/'.TPL_NAME;
	$smarty->compile_dir = BASE_ROOT_PATH.DS.DIR_SHOP.'/templates/'.TPL_NAME;	
	$json = new JSON();
	if($_REQUEST['is_ajax'] == 1){
		$result = array('content' => '', 'pic_id' => '', 'old_album_id' => '');
		$temp = (!empty($_REQUEST['fun']) ? $_REQUEST['fun'] : '');
		$smarty->assign('temp', $temp);
		$result['content'] = $smarty->fetch('library/dialog.lbi');
		exit($json->encode($result));			
	}else{
		$result = array('error' => '', 'pic_id' => '', 'content' => '');
		//require_once ROOT_PATH . 'includes/cls_image.php';
		$model = Model('visual');
		$exc = $model->exchange('gallery_album', $db, 'album_id', 'album_mame');
		$allow_file_types = '|GIF|JPG|PNG|';
		$album_mame = (isset($_REQUEST['album_mame']) ? addslashes($_REQUEST['album_mame']) : '');
		$album_desc = (isset($_REQUEST['album_desc']) ? addslashes($_REQUEST['album_desc']) : '');
		$sort_order = (isset($_REQUEST['sort_order']) ? intval($_REQUEST['sort_order']) : 50);
		$adminru['ru_id']= $_SESSION['store_id'];
		$is_only = $model->is_only('album_mame', $album_mame, 0, 'ru_id = ' . $adminru['ru_id']);
	
		if (!$is_only) {
			$result['error'] = 0;
			$result['content'] = '相册’' . $album_mame . '‘存在';
			exit(json_encode($result));
		}

		$file_url = '';
		if ((isset($_FILES['album_cover']['error']) && ($_FILES['album_cover']['error'] == 0)) || (!isset($_FILES['album_cover']['error']) && isset($_FILES['album_cover']['tmp_name']) && ($_FILES['album_cover']['tmp_name'] != 'none'))) {
			if (!check_file_type($_FILES['album_cover']['tmp_name'], $_FILES['album_cover']['name'], $allow_file_types)) {
				$result['error'] = 0;
				$result['content'] = '相册封面格式必须为|GIF|JPG|PNG|格式。请重新上传';
				exit(json_encode($result));
			}

			$res = upload_article_file($_FILES['album_cover']);

			if ($res != false) {
				$file_url = $res;
			}
		}

		if ($file_url == '') {
			$file_url = $_POST['file_url'];
		}

		$time = gmtime();
		$tablePre = C('tablepre');
        $insert_arr = array();
        $file_url = 'noimg';
        $insert_arr['album_mame'] = $album_mame;
        $insert_arr['album_cover'] = $file_url;
        $insert_arr['album_desc'] = $album_desc;
        $insert_arr['sort_order'] = $sort_order;
        $insert_arr['add_time'] = $time;
        $insert_arr['ru_id'] = $adminru['ru_id'];
 
	    $cc = Model()->table('gallery_album')->insert($insert_arr);

		$result['error'] = 1;
		$result['pic_id'] = $cc;
		
		$album_list = $model->get_goods_gallery_album(1, $adminru['ru_id'], array('album_id', 'album_mame'), 'ru_id');
		$html = '<li><a href="javascript:;" data-value="0" class="ftx-01">请选择...</a></li>';

		if (!empty($album_list)) {
			foreach ($album_list as $v) {
				$html .= '<li><a href="javascript:;" data-value="' . $v['album_id'] . '" class="ftx-01">' . $v['album_mame'] . '</a></li>';
			}
		}

		$result['content'] = $html;
		exit(json_encode($result));		
		}

	}
	public function get_albun_picOp() {
		$result = array('error' => 0, 'message' => '', 'content' => '');
		$is_vis = (!empty($_REQUEST['is_vis']) ? intval($_REQUEST['is_vis']) : 0);
		$inid = (!empty($_REQUEST['inid']) ? trim($_REQUEST['inid']) : 0);
		$model = Model('visual');
		$pic_list = $model->getAlbumList();
		$smarty = new cls_template();
		$smarty->template_dir = BASE_ROOT_PATH.DS.DIR_SHOP.'/templates/'.TPL_NAME;
		$smarty->compile_dir = BASE_ROOT_PATH.DS.DIR_SHOP.'/templates/'.TPL_NAME;	

		$smarty->assign('pic_list', $pic_list['list']);
		$smarty->assign('filter', $pic_list['filter']);
		$smarty->assign('temp', 'ajaxPiclist');
		$smarty->assign('is_vis', $is_vis);
		$smarty->assign('inid', $inid);
		$result['content'] = $smarty->fetch('library/dialog.lbi');
		exit(json_encode($result));		
	}
public function addmoduleOp() {
	$json = new json();
	$smarty = new cls_template();
	$smarty->template_dir = BASE_ROOT_PATH.DS.DIR_SHOP.'/templates/'.TPL_NAME;
	$smarty->compile_dir = BASE_ROOT_PATH.DS.DIR_SHOP.'/templates/'.TPL_NAME;	
	$result = array('error' => 0, 'message' => '', 'content' => '', 'mode' => '');
	$result['spec_attr'] = !empty($_REQUEST['spec_attr']) ? stripslashes($_REQUEST['spec_attr']) : '';

	if ($_REQUEST['spec_attr']) {
		$_REQUEST['spec_attr'] = strip_tags(urldecode($_REQUEST['spec_attr']));
		$_REQUEST['spec_attr'] = json_str_iconv($_REQUEST['spec_attr']);

		if (!empty($_REQUEST['spec_attr'])) {
			$spec_attr = $json->decode($_REQUEST['spec_attr']);
			$spec_attr = object_to_array($spec_attr);
		}
	}

	$pic_src = (isset($spec_attr['pic_src']) ? $spec_attr['pic_src'] : array());
	$bg_color = (isset($spec_attr['bg_color']) ? $spec_attr['bg_color'] : array());
	$link = (isset($spec_attr['link']) && ($spec_attr['link'] != ',') ? explode(',', $spec_attr['link']) : array());
	$sort = (isset($spec_attr['sort']) ? $spec_attr['sort'] : array());
	$result['mode'] = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';
	$is_li = (isset($spec_attr['is_li']) ? intval($spec_attr['is_li']) : 0);
	$result['slide_type'] = isset($spec_attr['slide_type']) ? addslashes($spec_attr['slide_type']) : '';
	$result['itemsLayout'] = isset($spec_attr['itemsLayout']) ? addslashes($spec_attr['itemsLayout']) : '';
	$result['diff'] = isset($_REQUEST['diff']) ? intval($_REQUEST['diff']) : 0;
	$count = COUNT($pic_src);
	$arr = array();
	$sort_vals = array();

	for ($i = 0; $i < $count; $i++) {
		$arr[$i]['pic_src'] = $pic_src[$i];

		if ($link[$i]) {
			$arr[$i]['link'] = setRewrite($link[$i]);
		}
		else {
			$arr[$i]['link'] = $link[$i];
		}

		$arr[$i]['bg_color'] = $bg_color[$i];
		$arr[$i]['sort'] = isset($sort[$i]) ? $sort[$i] : 0;
		$sort_vals[$i] = isset($sort[$i]) ? $sort[$i] : 0;
	}

	if (!empty($arr)) {
		array_multisort($sort_vals, SORT_ASC, $arr);
		$smarty->assign('img_list', $arr);
	}

	$smarty->assign('is_li', $is_li);
	$smarty->assign('temp', 'img_list');
	$smarty->assign('attr', $spec_attr);
	$smarty->assign('mode', $result['mode']);
	$result['content'] = $smarty->fetch('library/dialog.lbi');
	exit(json_encode($result));		
}
public function file_put_visualOp() {
    $adminru['ru_id']= $_SESSION['store_id'];
	$json = new JSON();
	$result = array('suffix' => '', 'error' => '');
	$topic_type = (isset($_REQUEST['topic_type']) ? addslashes($_REQUEST['topic_type']) : '');
	$content = (isset($_REQUEST['content']) ? unescape($_REQUEST['content']) : '');
	$content = (!empty($content) ? stripslashes($content) : '');
	$content_html = (isset($_REQUEST['content_html']) ? unescape($_REQUEST['content_html']) : '');
	$content_html = (!empty($content_html) ? stripslashes($content_html) : '');
	$head_html = (isset($_REQUEST['head_html']) ? unescape($_REQUEST['head_html']) : '');
	$head_html = (!empty($head_html) ? stripslashes($head_html) : '');
	$suffix = (isset($_REQUEST['suffix']) ? addslashes($_REQUEST['suffix']) : 'store_tpl_1');
	$pc_page_name = 'pc_page.php';
	$pc_html_name = 'pc_html.php';
	$type = 0;

	if ($topic_type == 'topic_type') {
		$nav_html = (isset($_REQUEST['nav_html']) ? unescape($_REQUEST['nav_html']) : '');
		$nav_html = (!empty($nav_html) ? stripslashes($nav_html) : '');
		$dir = BASE_ROOT_PATH . 'data/topic/topic_' . $adminru['ru_id'] . '/' . $suffix;
		$type = 1;
		$pc_nav_html = 'nav_html.php';
		$nav_html = create_html($nav_html, $adminru['ru_id'], $pc_nav_html, $suffix, 1);
	}
	else {
		$dir = BASE_ROOT_PATH . 'data/seller_templates/seller_tem_' . $adminru['ru_id'] . '/' . $suffix;
		$pc_head_name = 'pc_head.php';
		$create = create_html($head_html, $adminru['ru_id'], $pc_head_name, $suffix);
		
	}

	$create_html = create_html($content_html, $adminru['ru_id'], $pc_html_name, $suffix, $type);

	$create = create_html($content, $adminru['ru_id'], $pc_page_name, $suffix, $type);
	$result['error'] = 0;
	$result['suffix'] = $suffix;
	exit(json_encode($result));	
	
}	
    public function upload_picOp() {
    	$adminru['ru_id']= $_SESSION['store_id'];
			$image = new cls_image();
			//require_once ROOT_PATH . '/' . ADMIN_PATH . '/includes/lib_goods.php';
			$result = array('error' => 0, 'pic' => '', 'name' => '');
			$album_id = (isset($_REQUEST['album_id']) ? intval($_REQUEST['album_id']) : 0);
			$goods_img = '';
			$goods_thumb = '';
			$original_img = '';
			$old_original_img = '';
			$file_url = '';
			$pic_name = '';
			$pic_size = 0;
			$proc_thumb = (isset($GLOBALS['shop_id']) && (0 < $GLOBALS['shop_id']) ? false : true);
        
			if ((isset($_FILES['file']['error']) && ($_FILES['file']['error'] == 0)) || (!isset($_FILES['file']['error']) && isset($_FILES['file']['tmp_name']) && ($_FILES['file']['tmp_name'] != 'none'))) {
				if (!check_file_type($_FILES['file']['tmp_name'], $_FILES['file']['name'], $allow_file_types)) {
					//sys_msg($_LANG['invalid_file']);
					$result['error'] = '1';
				    $result['massege'] = '图片格式错误！';
				    exit(json_encode($result));
				}

				$image_name = explode('.', $_FILES['file']['name']);
				$pic_name = $image_name[0];
				$pic_size = intval($_FILES['file']['size']);
				$dir = 'gallery_album/' . $album_id . '/original_img';
				$original_img = $image->upload_image($_FILES['file'], $dir);
				
				$goods_img = $original_img;
				$images = $original_img;
				if ($proc_thumb && (0 < $image->gd_version()) && $image->check_img_function($_FILES['file']['type'])) {
					if (($_CFG['thumb_width'] != 0) || ($_CFG['thumb_height'] != 0)) {
						$goods_thumb = $image->make_thumb('../' . $original_img, $GLOBALS['_CFG']['thumb_width'], $GLOBALS['_CFG']['thumb_height'], '../data/gallery_album/' . $album_id . '/thumb_img/');
						$goods_thumb = str_replace('../', ' ', $goods_thumb, $i);

						if ($goods_thumb === false) {
							//sys_msg($image->error_msg(), 1, array(), false);
							$result['error'] = '1';
				            $result['massege'] = $image->error_msg();
				            exit(json_encode($result));
						}
					}
					else {
						$goods_thumb = $original_img;
					}

					if (($_CFG['image_width'] != 0) || ($_CFG['image_height'] != 0)) {
						$images = $image->make_thumb('../' . $original_img, $GLOBALS['_CFG']['image_width'], $GLOBALS['_CFG']['image_height'], '../data/gallery_album/' . $album_id . '/images/');
					}
					else {
						$images = $original_img;
					}

				}

				if ($images) {
					$images = str_replace('../', '', $images, $i);
				}

				$result['data'] = array('original_img' => $original_img, 'goods_thumb' => $goods_thumb);
				$result['pic'] = get_image_path(0, $original_img);
				list($width, $height, $type, $attr) = getimagesize('../' . $original_img);
				$pic_spec = $width . 'x' . $height;
				$add_time = gmtime();
				$model = Model('visual');
				$ru_id = $model->get_goods_gallery_album(0, $album_id, array('ru_id'));
				$arr_img = array($original_img, $goods_thumb, $images);
			    $insert_arr = array();        
                $insert_arr['ru_id'] = $ru_id;
                $insert_arr['album_id'] = $album_id;
                $insert_arr['pic_name'] = $pic_name;
                $insert_arr['pic_file'] = $original_img;
                $insert_arr['pic_size'] = $pic_size;
                $insert_arr['pic_spec'] = $pic_spec;
                $insert_arr['add_time'] = $add_time; 
                $insert_arr['pic_thumb'] = $goods_thumb; 
                $insert_arr['pic_image'] = $images;                                
	            $sql = Model()->table('pic_album')->insert($insert_arr);
				//get_oss_add_file($arr_img);

				if ($sql) {
					$result['error'] = '0';
				}
			}
			else {
				$result['error'] = '1';
				$result['massege'] = '上传有误，请检查服务器配置！';
			}

			exit(json_encode($result));
		}	
public function downloadModalOp() {
    $adminru['ru_id']= $_SESSION['store_id'];
	$json = new JSON();
	$result = array('error' => '', 'message' => '');
	$code = (isset($_REQUEST['suffix']) ? trim($_REQUEST['suffix']) : '');
	$topic_type = (isset($_REQUEST['topic_type']) ? trim($_REQUEST['topic_type']) : '');

	if ($topic_type == 'topic_type') {
		$dir = BASE_ROOT_PATH .DS. 'data/topic/topic_' . $adminru['ru_id'] . '/' . $code . '/temp';
		$file = BASE_ROOT_PATH .DS. 'data/topic/topic_' . $adminru['ru_id'] . '/' . $code;
	}
	else {
		$dir = BASE_ROOT_PATH .DS. 'data/seller_templates/seller_tem_' . $adminru['ru_id'] . '/' . $code . '/temp';
		$file = BASE_ROOT_PATH .DS. 'data/seller_templates/seller_tem_' . $adminru['ru_id'] . '/' . $code;
	}

	if (!empty($code)) {
		if (!is_dir($dir)) {
			mkdir($dir, 511, true);
		}

		recurse_copy($dir, $file, 1);
		del_DirAndFile($dir);
		$result['error'] = 0;
	}

	exit(json_encode($result));	
}    	
public function backmodalOp() {
    $adminru['ru_id']= $_SESSION['store_id'];
	$json = new JSON();
	$result = array('error' => '', 'message' => '');
	$code = (isset($_REQUEST['suffix']) ? trim($_REQUEST['suffix']) : '');
	$topic_type = (isset($_REQUEST['topic_type']) ? trim($_REQUEST['topic_type']) : '');

	if ($topic_type == 'topic_type') {
		$dir = BASE_ROOT_PATH .DS. 'data/topic/topic_' . $adminru['ru_id'] . '/' . $code . '/temp';
	}
	else {
		$dir = BASE_ROOT_PATH .DS. 'data/seller_templates/seller_tem_' . $adminru['ru_id'] . '/' . $code . '/temp';
	}

	if (!empty($code)) {
		del_DirAndFile($dir);
		$result['error'] = 0;
	}

	exit(json_encode($result));	
}	
	
public function goods_infoOp() {
	$adminru['ru_id']= $_SESSION['store_id'];
	$json = new json();
	$smarty = new cls_template();
	$model = Model('visual');
	$smarty->template_dir = BASE_ROOT_PATH.DS.DIR_SHOP.'/templates/'.TPL_NAME;
	$smarty->compile_dir = BASE_ROOT_PATH.DS.DIR_SHOP.'/templates/'.TPL_NAME;		
	
	$result = array('content' => '', 'mode' => '');
	$search_type = (isset($_REQUEST['search_type']) ? trim($_REQUEST['search_type']) : '');
	$cat_id = (!empty($_REQUEST['cat_id']) ? intval($_REQUEST['cat_id']) : 0);
	$goods_type = (isset($_REQUEST['goods_type']) ? intval($_REQUEST['goods_type']) : 0);
	$good_number = (isset($_REQUEST['good_number']) ? intval($_REQUEST['good_number']) : 0);
	$_REQUEST['spec_attr'] = strip_tags(urldecode($_REQUEST['spec_attr']));
	$_REQUEST['spec_attr'] = json_str_iconv($_REQUEST['spec_attr']);
	$_REQUEST['spec_attr'] = !empty($_REQUEST['spec_attr']) ? stripslashes($_REQUEST['spec_attr']) : '';

	if (!empty($_REQUEST['spec_attr'])) {
		$spec_attr = $json->decode(stripslashes($_REQUEST['spec_attr']));
		$spec_attr = object_to_array($spec_attr);
	}

	$spec_attr['is_title'] = isset($spec_attr['is_title']) ? $spec_attr['is_title'] : 0;
	$spec_attr['itemsLayout'] = isset($spec_attr['itemsLayout']) ? $spec_attr['itemsLayout'] : 'row4';
	$result['mode'] = isset($_REQUEST['mode']) ? addslashes($_REQUEST['mode']) : '';
	$result['diff'] = isset($_REQUEST['diff']) ? intval($_REQUEST['diff']) : 0;
	$lift = (isset($_REQUEST['lift']) ? trim($_REQUEST['lift']) : '');
	$spec_attr['goods_ids'] = $model->resetBarnd($spec_attr['goods_ids']);

	if ($spec_attr['goods_ids']) {
		$goods_info = explode(',', $spec_attr['goods_ids']);

		foreach ($goods_info as $k => $v) {
			if (!$v) {
				unset($goods_info[$k]);
			}
		}

		if (!empty($goods_info)) {
			$where = ' WHERE g.goods_state=1 AND g.goods_verify=1 AND g.goods_id' . db_create_in($goods_info) . ' AND g.store_id = \'' . $adminru['ru_id'] . '\'';


			$sql = 'SELECT g.goods_name,g.goods_id,g.goods_image,g.goods_price FROM ' . C('tablepre').'goods' . ' AS g ' . $where;
			$goods_list = Model()->getAll1($sql);
			
//goods_thumb  original_img goods_price
			foreach ($goods_list as $k => $v) {
				$goods_list[$k]['shop_price'] = ncPriceFormat($v['goods_price']);//price_format($v['goods_price']);
				$goods_list[$k]['goods_thumb'] = cthumb($v['goods_image'], 240, $_SESSION['store_id']);//get_image_path($v['goods_id'], $v['goods_thumb']);
			    $goods_list[$k]['original_img'] = cthumb($v['goods_image'], 240, $_SESSION['store_id']);
			    
			}

			$smarty->assign('goods_list', $goods_list);
			$smarty->assign('goods_count', count($goods_list));
		}
	}

	//set_default_filter($cat_id, 0, $adminru['ru_id']);
	//$smarty->assign('parent_category', get_every_category($cat_id));
	$select_category_html = '';
	//$seller_shop_cat = seller_shop_cat($adminru['ru_id']);
	//$select_category_html = insert_select_category(0, 0, 0, 'cat_id', 0, 'category', $seller_shop_cat);
	$smarty->assign('select_category_html', $select_category_html);
	//$smarty->assign('brand_list', get_brand_list());
	$smarty->assign('arr', $spec_attr);
	$smarty->assign('temp', 'goods_info');
	$smarty->assign('goods_type', $goods_type);
	$smarty->assign('mode', $result['mode']);
	$smarty->assign('cat_id', $cat_id);
	$smarty->assign('lift', $lift);
	$smarty->assign('good_number', $good_number);
	$smarty->assign('search_type', $search_type);
	$result['content'] = $smarty->fetch('library/shop_banner.lbi');
	exit($json->encode($result));    	
}	
	public function changedgoodsOp() {
    $adminru['ru_id']= $_SESSION['store_id'];
	$json = new JSON();
	$smarty = new cls_template();
    $model = Model('visual');
	$smarty->template_dir = BASE_ROOT_PATH.DS.DIR_SHOP.'/templates/'.TPL_NAME;
	$smarty->compile_dir = BASE_ROOT_PATH.DS.DIR_SHOP.'/templates/'.TPL_NAME;		
	$result = array('error' => 0, 'message' => '', 'content' => '');
	$spec_attr = array();
	$search_type = (isset($_REQUEST['search_type']) ? trim($_REQUEST['search_type']) : '');
	$result['spec_attr'] = !empty($_REQUEST['spec_attr']) ? stripslashes($_REQUEST['spec_attr']) : '';

	if ($_REQUEST['spec_attr']) {
		$_REQUEST['spec_attr'] = strip_tags(urldecode($_REQUEST['spec_attr']));
		$_REQUEST['spec_attr'] = json_str_iconv($_REQUEST['spec_attr']);

		if (!empty($_REQUEST['spec_attr'])) {
			$spec_attr = $json->decode($_REQUEST['spec_attr']);
			$spec_attr = object_to_array($spec_attr);
		}
	}

	$sort_order = (isset($_REQUEST['sort_order']) ? $_REQUEST['sort_order'] : 0);
	$cat_id = (isset($_REQUEST['cat_id']) ? explode('_', $_REQUEST['cat_id']) : array());
	$brand_id = (isset($_REQUEST['brand_id']) ? intval($_REQUEST['brand_id']) : 0);
	$keyword = (isset($_REQUEST['keyword']) ? addslashes($_REQUEST['keyword']) : '');
	$goodsAttr = (isset($spec_attr['goods_ids']) ? explode(',', $spec_attr['goods_ids']) : '');
	$goods_ids = (isset($_REQUEST['goods_ids']) ? explode(',', $_REQUEST['goods_ids']) : '');
	$result['goods_ids'] = !empty($goodsAttr) ? $goodsAttr : $goods_ids;
	$spec_attr['goods_ids'] = $model->resetBarnd($spec_attr['goods_ids']);
	$result['cat_desc'] = isset($spec_attr['cat_desc']) ? addslashes($spec_attr['cat_desc']) : '';
	$result['cat_name'] = isset($spec_attr['cat_name']) ? addslashes($spec_attr['cat_name']) : '';
	$result['align'] = isset($spec_attr['align']) ? addslashes($spec_attr['align']) : '';
	$result['is_title'] = isset($spec_attr['is_title']) ? intval($spec_attr['is_title']) : 0;
	$result['itemsLayout'] = isset($spec_attr['itemsLayout']) ? addslashes($spec_attr['itemsLayout']) : '';
	$result['diff'] = isset($_REQUEST['diff']) ? intval($_REQUEST['diff']) : 0;
	$type = (isset($_REQUEST['type']) ? intval($_REQUEST['type']) : 0);
	$temp = (isset($_REQUEST['temp']) ? $_REQUEST['temp'] : 'goods_list');
	$result['mode'] = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';
	$resetRrl = (isset($_REQUEST['resetRrl']) ? intval($_REQUEST['resetRrl']) : 0);
	$smarty->assign('temp', $temp);
	$where = 'WHERE g.goods_state=1 AND g.store_id = \'' . $adminru['ru_id'] . '\'';

	if ($GLOBALS['_CFG']['review_goods'] == 1) {
		$where .= ' AND g.review_status > 2 ';
	}

	if ($search_type != 'goods') {
		$where .= ' AND g.goods_verify = 1 ';
	}
//
//	if ($cat_id) {
//		$where .= ' AND ' . get_children($cat_id[0]);
//	}

	if (0 < $brand_id) {
		$where .= ' AND g.brand_id = \'' . $brand_id . '\'';
	}

	if ($keyword) {
		$where .= ' AND g.goods_name  LIKE \'%' . $keyword . '%\'';
	}

	if ($result['goods_ids'] && ($type == '0')) {
		$where .= ' AND g.goods_id' . db_create_in($result['goods_ids']);
	}

	$sort = ' ORDER BY g.goods_id DESC';

	switch ($sort_order) {
	case '1':
		$sort = ' ORDER BY g.add_time ASC';
		break;

	case '2':
		$sort = ' ORDER BY g.add_time DESC';
		break;

	case '3':
		$sort = ' ORDER BY g.goods_id ASC';
		break;

	case '4':
		$sort = ' ORDER BY g.goods_id DESC';
		break;

	case '5':
		$sort = ' ORDER BY g.goods_name ASC';
		break;

	case '6':
		$sort = ' ORDER BY g.goods_name DESC';
		break;
	}

	if ($type == 1) {
		$list = $model->getGoodslist($where, $sort);
		
		$goods_list = $list['list'];
		$filter = $list['filter'];
		$filter['cat_id'] = $cat_id[0];
		$filter['sort_order'] = $sort_order;
		$filter['keyword'] = $keyword;
		$filter['search_type'] = $search_type;
		$smarty->assign('filter', $filter);
	}
	else {
		$sql = 'SELECT g.goods_promotion_price, g.goods_name, g.goods_id, g.goods_price, g.goods_marketprice, g.goods_image  FROM ' . C('tablepre').'goods' . ' AS g ' . $where . $sort;
		$goods_list = Model()->getAll1($sql);
	}

	if (!empty($goods_list)) {
		foreach ($goods_list as $k => $v) {
			$goods_list[$k]['goods_thumb'] =  cthumb($v['goods_image'], 240, $_SESSION['store_id']);
			$goods_list[$k]['original_img'] = cthumb($v['goods_image'], 240, $_SESSION['store_id']);
			$goods_list[$k]['url'] = urlShop('goods','index', array('goods_id' => $v['goods_id']));
			$goods_list[$k]['shop_price'] = ncPriceFormat($v['goods_price']);

			if (0 < $v['goods_promotion_price']) {
				$goods_list[$k]['promote_price'] = ncPriceFormat($v['goods_promotion_price']);//bargain_price($v['promote_price'], $v['promote_start_date'], $v['promote_end_date']);
			}
			else {
				$goods_list[$k]['promote_price'] = 0;
			}

			if ((0 < $v['goods_id']) && in_array($v['goods_id'], $result['goods_ids']) && !empty($result['goods_ids'])) {
				$goods_list[$k]['is_selected'] = 1;
			}
		}
	}

	$smarty->assign('is_title', $result['is_title']);
	$smarty->assign('goods_count', count($goods_list));
	$smarty->assign('goods_list', $goods_list);
	$smarty->assign('attr', $spec_attr);
	$result['goods_ids'] = implode(',', $result['goods_ids']);
	$result['content'] = $smarty->fetch('library/dialog.lbi');
	exit(json_encode($result));		
	}
	public function customOp() {
    $adminru['ru_id']= $_SESSION['store_id'];
	$json = new JSON();
	$smarty = new cls_template();
    $model = Model('visual');
	$smarty->template_dir = BASE_ROOT_PATH.DS.DIR_SHOP.'/templates/'.TPL_NAME;
	$smarty->compile_dir = BASE_ROOT_PATH.DS.DIR_SHOP.'/templates/'.TPL_NAME;	
	$result = array('content' => '', 'mode' => '');
	$custom_content = (isset($_REQUEST['custom_content']) ? unescape($_REQUEST['custom_content']) : '');
	$custom_content = (!empty($custom_content) ? stripslashes($custom_content) : '');
	$result['mode'] = isset($_REQUEST['mode']) ? addslashes($_REQUEST['mode']) : '';
	$result['diff'] = isset($_REQUEST['diff']) ? intval($_REQUEST['diff']) : 0;


	$endpoint = (!empty(SHOP_SITE_URL) ? SHOP_SITE_URL : '');
	

	if ($custom_content && $endpoint) {
		$desc_preg = get_goods_desc_images_preg($endpoint, $custom_content);
		$custom_content = $desc_preg['goods_desc'];
	}

	$FCKeditor = create_ueditor_editor('custom_content', $custom_content, 486, 1);
	$smarty->assign('FCKeditor', $FCKeditor);
	$smarty->assign('temp', $_REQUEST['act']);
	$result['content'] = $smarty->fetch('library/shop_banner.lbi');
	exit($json->encode($result));		
	}
public function homefloorOp(){
    $adminru['ru_id']= $_SESSION['store_id'];
	$json = new JSON();
	$smarty = new cls_template();
    $model = Model('visual');
	$smarty->template_dir = BASE_ROOT_PATH.DS.DIR_SHOP.'/templates/'.TPL_NAME;
	$smarty->compile_dir = BASE_ROOT_PATH.DS.DIR_SHOP.'/templates/'.TPL_NAME;	
	$result = array('content' => '', 'mode' => '');
	$result['act'] = $_REQUEST['act'];
	$lift = (isset($_REQUEST['lift']) ? trim($_REQUEST['lift']) : '');
	$result['hierarchy'] = isset($_REQUEST['hierarchy']) ? trim($_REQUEST['hierarchy']) : '';
	$result['diff'] = isset($_REQUEST['diff']) ? intval($_REQUEST['diff']) : 0;
	$result['mode'] = isset($_REQUEST['mode']) ? trim($_REQUEST['mode']) : '';
	$_REQUEST['spec_attr'] = !empty($_REQUEST['spec_attr']) ? stripslashes($_REQUEST['spec_attr']) : '';
	$_REQUEST['spec_attr'] = urldecode($_REQUEST['spec_attr']);
	$_REQUEST['spec_attr'] = json_str_iconv($_REQUEST['spec_attr']);

	if (!empty($_REQUEST['spec_attr'])) {
		$spec_attr = json_decode($_REQUEST['spec_attr'], true);
	}

	$brand_ids = (!empty($spec_attr['brand_ids']) ? trim($spec_attr['brand_ids']) : '');
	$cat_id = (!empty($spec_attr['cat_id']) ? intval($spec_attr['cat_id']) : 0);
	$parent = '';
	$spec_attr['catChild'] = '';
	$spec_attr['Selected'] = '';

	if (0 < $cat_id) {
		$parent = get_cat_info($spec_attr['cat_id'], array('gc_parent_id'));
		$parent['parent_id'] = $parent['gc_parent_id'];
		
		if (0 < $parent['parent_id']) {
			$spec_attr['catChild'] = cat_list($parent['parent_id'],0,0,'goods_class','',0,0,2);
			
			$spec_attr['Selected'] = $parent['parent_id'];
		}
		else {
			$spec_attr['catChild'] = cat_list($spec_attr['cat_id']);
			$spec_attr['Selected'] = $cat_id;
		}

		$spec_attr['juniorCat'] = cat_list($cat_id,0,0,'goods_class','',0,0,3);
	}

	$arr = array();

	if ($spec_attr['cateValue']) {
		foreach ($spec_attr['cateValue'] as $k => $v) {
			$arr[$k]['cat_id'] = $v;
			$arr[$k]['cat_goods'] = $spec_attr['cat_goods'][$k];
		}
	}

	$spec_attr['catInfo'] = $arr;

	if ($spec_attr['rightAdvTitle']) {
		foreach ($spec_attr['rightAdvTitle'] as $k => $v) {
			if ($v) {
				$spec_attr['rightAdvTitle'][$k] = $v;
			}
		}
	}

	if ($spec_attr['rightAdvSubtitle']) {
		foreach ($spec_attr['rightAdvSubtitle'] as $k => $v) {
			if ($v) {
				$spec_attr['rightAdvSubtitle'][$k] = $v;
			}
		}
	}

	$floor_style = array();
	$floor_style = get_floor_style($result['mode']);
	//$seller_shop_cat = seller_shop_cat($adminru['ru_id']);
	//$cat_list = cat_list(0, 0, 0, 'category', $seller_shop_cat, 1);
	//店铺分类
	// 实例化商品分类模型
    $model_goodsclass = Model('goods_class');
    // 商品分类
    $goods_class_list = $model_goodsclass->getGoodsClass($_SESSION['store_id'],0,1,$_SESSION['seller_group_id'],$_SESSION['seller_gc_limits']);
   
    //$goodsclass_model = Model('store_goods_class');
    //$goods_class_list = $goodsclass_model->getTreeClassList(array('store_id'=>$adminru['ru_id'],'stc_state'=>1),1);
    $cat_list = array();
	if(is_array($goods_class_list)){
		foreach($goods_class_list as $ks=>$vs){
		    $cat_list[$vs['gc_id']]['cat_id'] = $vs['gc_id'];
		    $cat_list[$vs['gc_id']]['cat_name'] = $vs['gc_name'];
		    $cat_list[$vs['gc_id']]['cat_alias_name'] = $vs['type_name'];
		    $cat_list[$vs['gc_id']]['cat_icon'] = '';
		    $cat_list[$vs['gc_id']]['style_icon'] = 'digital';
		    $cat_list[$vs['gc_id']]['level'] = 0;
		    $cat_list[$vs['gc_id']]['select'] = '';
		    $cat_list[$vs['gc_id']]['url'] = urlShop('search', 'index', array('cate_id'=>$vs['gc_id']));;
	    }
	}

	
	
	$imgNumberArr = getAdvNum($result['mode']);
	$imgNumberArr = json_encode($imgNumberArr);
	$smarty->assign('cat_list', $cat_list);
	$smarty->assign('temp', $_REQUEST['act']);
	$smarty->assign('mode', $result['mode']);
	$smarty->assign('lift', $lift);
	$smarty->assign('spec_attr', $spec_attr);
	$smarty->assign('hierarchy', $result['hierarchy']);
	$smarty->assign('floor_style', $floor_style);
	$smarty->assign('imgNumberArr', $imgNumberArr);
	$result['content'] = $smarty->fetch('library/shop_banner.lbi');
	exit($json->encode($result));	
}	
public function homefloor1Op(){
    $adminru['ru_id']= $_SESSION['store_id'];
    $tablePre = C('tablepre');
	$json = new JSON();
	$smarty = new cls_template();
    $model = Model('visual');
	$smarty->template_dir = BASE_ROOT_PATH.DS.DIR_SHOP.'/templates/'.TPL_NAME;
	$smarty->compile_dir = BASE_ROOT_PATH.DS.DIR_SHOP.'/templates/'.TPL_NAME;	
	$result = array('content' => '');
	$spec_attr = '';
	$result['floor_title'] = !empty($_REQUEST['floor_title']) ? trim($_REQUEST['floor_title']) : '';
	$spec_attr['hierarchy'] = !empty($_REQUEST['hierarchy']) ? intval($_REQUEST['hierarchy']) : '';
	$result['cat_goods'] = !empty($_REQUEST['cat_goods']) ? $_REQUEST['cat_goods'] : '';
	$result['moded'] = !empty($_REQUEST['mode']) ? trim($_REQUEST['mode']) : '';
	$result['cat_id'] = !empty($_REQUEST['Floorcat_id']) ? intval($_REQUEST['Floorcat_id']) : 0;
	$result['cateValue'] = !empty($_REQUEST['cateValue']) ? $_REQUEST['cateValue'] : '';
	$result['typeColor'] = !empty($_REQUEST['typeColor']) ? trim($_REQUEST['typeColor']) : '';
	$result['floorMode'] = !empty($_REQUEST['floorMode']) ? intval($_REQUEST['floorMode']) : '';
	$result['brand_ids'] = !empty($_REQUEST['brand_ids']) ? trim($_REQUEST['brand_ids']) : '';
	$result['leftBanner'] = !empty($_REQUEST['leftBanner']) ? $_REQUEST['leftBanner'] : '';
	$result['leftBannerLink'] = !empty($_REQUEST['leftBannerLink']) ? $_REQUEST['leftBannerLink'] : '';
	$result['leftBannerSort'] = !empty($_REQUEST['leftBannerSort']) ? $_REQUEST['leftBannerSort'] : '';
	$result['leftAdv'] = !empty($_REQUEST['leftAdv']) ? $_REQUEST['leftAdv'] : '';
	$result['leftAdvLink'] = !empty($_REQUEST['leftAdvLink']) ? $_REQUEST['leftAdvLink'] : '';
	$result['leftAdvSort'] = !empty($_REQUEST['leftAdvSort']) ? $_REQUEST['leftAdvSort'] : '';
	$result['rightAdv'] = !empty($_REQUEST['rightAdv']) ? $_REQUEST['rightAdv'] : '';
	$result['rightAdvLink'] = !empty($_REQUEST['rightAdvLink']) ? $_REQUEST['rightAdvLink'] : '';
	$result['rightAdvSort'] = !empty($_REQUEST['rightAdvSort']) ? $_REQUEST['rightAdvSort'] : '';
	$result['rightAdvTitle'] = !empty($_REQUEST['rightAdvTitle']) ? $_REQUEST['rightAdvTitle'] : '';
	$result['rightAdvSubtitle'] = !empty($_REQUEST['rightAdvSubtitle']) ? $_REQUEST['rightAdvSubtitle'] : '';
	$result['top_goods'] = !empty($_REQUEST['top_goods']) ? trim($_REQUEST['top_goods']) : '';
	$spec_attr = $result;
	$result['lift'] = !empty($_REQUEST['lift']) ? trim($_REQUEST['lift']) : '';
	$AdvNum = getAdvNum($result['moded'], $result['floorMode']);
	$AdvBanner = '';

	if (0 < $AdvNum['leftBanner']) {
		if (!empty($result['leftBanner'])) {
			foreach ($result['leftBanner'] as $k => $v) {
				$AdvBanner[$k]['leftBanner'] = $result['leftBanner'][$k];
				$AdvBanner[$k]['leftBannerSort'] = $result['leftBannerSort'][$k];

				if ($result['leftBannerLink'][$k]) {
					$AdvBanner[$k]['leftBannerLink'] = setRewrite($result['leftBannerLink'][$k]);
				}
				else {
					$AdvBanner[$k]['leftBannerLink'] = $result['leftBannerLink'][$k];
				}

				$sort_vals[$k] = isset($result['leftBannerSort'][$k]) ? $result['leftBannerSort'][$k] : 0;
			}
		}
		else {
			$AdvNum['leftBanner'] = 1;

			for ($k = 0; $k < $AdvNum['leftBanner']; $k++) {
				$AdvBanner[$k]['leftBanner'] = $result['leftBanner'][$k];
				$AdvBanner[$k]['leftBannerSort'] = $result['leftBannerSort'][$k];

				if ($result['leftBannerLink'][$k]) {
					$AdvBanner[$k]['leftBannerLink'] = setRewrite($result['leftBannerLink'][$k]);
				}
				else {
					$AdvBanner[$k]['leftBannerLink'] = $result['leftBannerLink'][$k];
				}

				$sort_vals[$k] = isset($result['leftBannerSort'][$k]) ? $result['leftBannerSort'][$k] : 0;
			}
		}
	}

	if ($AdvBanner) {
		array_multisort($sort_vals, SORT_ASC, $AdvBanner);
	}

	$spec_attr['leftBanner'] = $AdvBanner;
	$AdvLeft = '';
	$sort_vals = '';

	if (0 < $AdvNum['leftAdv']) {
		for ($k = 0; $k < $AdvNum['leftAdv']; $k++) {
			$AdvLeft[$k]['leftAdv'] = $result['leftAdv'][$k];
			$AdvLeft[$k]['leftAdvSort'] = $result['leftAdvSort'][$k];

			if ($result['leftBannerLink'][$k]) {
				$AdvLeft[$k]['leftAdvLink'] = setRewrite($result['leftAdvLink'][$k]);
			}
			else {
				$AdvLeft[$k]['leftAdvLink'] = $result['leftAdvLink'][$k];
			}

			$sort_vals[$k] = isset($result['leftAdvSort'][$k]) ? $result['leftAdvSort'][$k] : 0;
		}
	}

	if ($AdvLeft) {
		array_multisort($sort_vals, SORT_ASC, $AdvLeft);
	}

	$spec_attr['leftAdv'] = $AdvLeft;
	$AdvRight = '';
	$sort_vals = '';

	if (0 < $AdvNum['rightAdv']) {
		for ($k = 0; $k < $AdvNum['rightAdv']; $k++) {
			$AdvRight[$k]['rightAdv'] = $result['rightAdv'][$k];
			$AdvRight[$k]['rightAdvSort'] = $result['rightAdvSort'][$k];

			if ($result['leftBannerLink'][$k]) {
				$AdvRight[$k]['rightAdvLink'] = setRewrite($result['rightAdvLink'][$k]);
			}
			else {
				$AdvRight[$k]['rightAdvLink'] = $result['rightAdvLink'][$k];
			}

			$AdvRight[$k]['rightAdvTitle'] = $result['rightAdvTitle'][$k];
			$AdvRight[$k]['rightAdvSubtitle'] = $result['rightAdvSubtitle'][$k];
			$sort_vals[$k] = isset($result['rightAdvSort'][$k]) ? $result['rightAdvSort'][$k] : 0;
		}
	}

	if ($AdvRight) {
		array_multisort($sort_vals, SORT_ASC, $AdvRight);
	}

	$spec_attr['rightAdv'] = $AdvRight;

	if (0 < $result['cat_id']) {
		$cat_info = get_cat_info($result['cat_id'], array('gc_id', 'gc_name', 'type_name'));//array('cat_id', 'cat_name', 'cat_alias_name', 'style_icon'));
        $cat_info['cat_id'] = $cat_info['gc_id'];
        $cat_info['cat_name'] = $cat_info['gc_name'];
        $cat_info['cat_alias_name'] = $cat_info['type_name'];
        $cat_info['style_icon'] = 'other';
		if ($cat_info['cat_alias_name']) { //$cat_info['cat_alias_name']
			$spec_attr['cat_alias_name'] = $cat_info['type_name'];
		}
		else {
			$spec_attr['cat_alias_name'] = $cat_info['gc_name'];
		}

		$spec_attr['cat_name'] = $cat_info['gc_name'];
		$spec_attr['style_icon'] = 'other';//$cat_info['style_icon'];
	}

	if (!empty($result['cateValue'])) {
		$cat_tow = '';

		foreach ($result['cateValue'] as $k => $v) {
			$arr = array();

			if (0 < $v) {
				$sql = 'SELECT gc_name,gc_id FROM ' . $tablePre.'goods_class' . ' WHERE gc_id = \'' . $v . '\'';
				$arr = Model()->getRow($sql);
				$arr['cat_name'] = $arr['gc_name'];
				$arr['cat_id'] = $arr['gc_id'];
				
				$arr['goods_id'] = $result['cat_goods'][$k];
				$cat_tow[] = $arr;
			}
		}

		$spec_attr['cateValue'] = $cat_tow;
	}

	$brand_list = '';

	if ($result['brand_ids']) {
		$where = ' WHERE 1';
		$brandId = $result['brand_ids'];
		$where .= ' AND brand_id in (' . $brandId . ')';//' AND be.store_id= ' . $adminru['ru_id'] . 
		//$sql = 'SELECT b.brand_id,b.brand_name,b.brand_pic FROM ' . C('tablepre').'brand' . ' as b left join ' . C('tablepre').'goods' . ' AS be on b.brand_id=be.brand_id ' . $where;
		$sql = 'SELECT brand_id,brand_name,brand_pic FROM ' . C('tablepre').'brand' . $where;
		$brand_list = Model()->getAll1($sql);

		if (!empty($brand_list)) {
			
			foreach ($brand_list as $key => $val) {
				$brand_list[$key]['brand_logo'] = $val['brand_pic'];
				$brand_list[$key]['site_url'] = '';
				if ($val['site_url'] && (8 < strlen($val['site_url']))) {
					$brand_list[$key]['url'] = $val['site_url'];
				}
				else {
					$brand_list[$key]['url'] = urlShop('brand', 'list', array('brand'=>$val['brand_id'],'store_id'=>$adminru['ru_id']));//build_uri('brandn', array('bid' => $val['brand_id']), $val['brand_name']);
				}

				$val['brand_logo'] = brandImage($val['brand_pic']);//DATA_DIR . '/brandlogo/' . $val['brand_logo'];
				$brand_list[$key]['brand_logo'] = brandImage($val['brand_pic']);//get_image_path($val['brand_id'], $val['brand_logo']);
			}
		}

		$smarty->assign('brand_list', $brand_list);
	}

	$advNumber = 6;

	if ($spec_attr['floorMode'] == '5') {
		$advNumber = 5;
	}
	else {
		if (($spec_attr['floorMode'] == '6') || ($spec_attr['floorMode'] == '7')) {
			$advNumber = 4;
		}
		else if ($spec_attr['floorMode'] == '8') {
			$advNumber = 3;
		}
	}

	if ($result['rightAdvTitle']) {
		foreach ($result['rightAdvTitle'] as $k => $v) {
			if ($v) {
				$result['rightAdvTitle'][$k] = strFilter($v);
			}
		}
	}

	if ($result['rightAdvSubtitle']) {
		foreach ($result['rightAdvSubtitle'] as $k => $v) {
			if ($v) {
				$result['rightAdvSubtitle'][$k] = strFilter($v);
			}
		}
	}

	if (($result['moded'] == 'homeFloorFour') || ($result['moded'] == 'homeFloorFive') || ($result['moded'] == 'homeFloorSeven') || (($result['moded'] == 'homeFloorSix') && ($result['floorMode'] != 1))) {
		
		$where_goods = 'WHERE 1 AND g.goods_state=1 AND g.store_id = \'' . $adminru['ru_id'] . '\'';

		if ($result['top_goods']) {
			$where_goods .= ' AND g.goods_id in (' . $result['top_goods'] . ')';
		}

//		if (0 < $result['cat_id']) {
//			//include_once ROOT_PATH . '/includes/lib_goods.php';
//			$children = get_children($result['cat_id']);			
//			$where_goods .= ' AND (' . $children . ' OR ' . get_extension_goods($children) . ')';
//			
//		}

		$limit = ' LIMIT 8';
		$goods_num = -1;

		if ($result['moded'] == 'homeFloorFour') {
			if ($result['floorMode'] == 1) {
				$goods_num = 3;
			}
			else if ($result['floorMode'] == 2) {
				$limit = ' LIMIT 10';
			}
			else if ($result['floorMode'] == 3) {
				$limit = ' LIMIT 10';
			}
			else if ($result['floorMode'] == 4) {
				$limit = ' LIMIT 12';
			}
		}
		else if ($result['moded'] == 'homeFloorSix') {
			if ($result['floorMode'] == 2) {
				$limit = ' LIMIT 4';
			}
			else if ($result['floorMode'] == 3) {
				$limit = ' LIMIT 6';
			}
		}
		else if ($result['moded'] == 'homeFloorSeven') {
			$limit = ' LIMIT 6';
		}
        
		$sql = 'SELECT g.goods_promotion_price, g.goods_name, g.goods_id, g.goods_price, g.goods_marketprice, g.goods_image  FROM ' . C('tablepre').'goods' . ' AS g ' . $where_goods . ' ORDER BY g.goods_id DESC ' . $limit;
		$goods_list = Model()->getAll1($sql);

		if (!empty($goods_list)) {
			foreach ($goods_list as $key => $val) {
				if (0 < $val['goods_promotion_price']) {
					$goods_list[$key]['promote_price'] = ncPriceFormat($val['goods_promotion_price']);//price_format(bargain_price($val['promote_price'], $val['promote_start_date'], $val['promote_end_date']));
				}
				else {
					$goods_list[$key]['promote_price'] = '';
				}

				$goods_list[$key]['shop_price'] = ncPriceFormat($val['goods_price']);//price_format($val['shop_price']);
				$goods_list[$key]['goods_thumb'] = cthumb($val['goods_image'], 240, $_SESSION['store_id']);//get_image_path($val['goods_id'], $val['goods_thumb']);
				$goods_list[$key]['original_img'] = cthumb($val['goods_image'], 240, $_SESSION['store_id']);//get_image_path($val['goods_id'], $val['original_img']);
				if ((strpos($goods_list[$key]['goods_thumb'], 'http://') === false) && (strpos($goods_list[$key]['goods_thumb'], 'https://') === false)) {
					$goods_list[$key]['goods_thumb'] = cthumb($val['goods_image'], 240, $_SESSION['store_id']);;
				}

				if ((strpos($goods_list[$key]['original_img'], 'http://') === false) && (strpos($goods_list[$key]['original_img'], 'https://') === false)) {
					$goods_list[$key]['original_img'] = cthumb($val['goods_image'], 240, $_SESSION['store_id']);//$ecs->url() . $goods_list[$key]['original_img'];
				}

				$goods_list[$key]['url'] = urlShop('goods','index', array('goods_id' => $val['goods_id']));//build_uri('goods', array('gid' => $val['goods_id']), $val['goods_name']);
			}
		}

		$smarty->assign('goods_list', $goods_list);
		$smarty->assign('goods_num', $goods_num);
	}

	$smarty->assign('advNumber', $advNumber);
	$smarty->assign('spec_attr', $spec_attr);
	$smarty->assign('temp', $result['moded']);
	$result['spec_attr'] = $result;
	$result['content'] = $smarty->fetch('library/dialog.lbi');
	exit(json_encode($result));	
}
public function getChildCatOp(){
    $adminru['ru_id']= $_SESSION['store_id'];
	$json = new JSON();

    $model = Model('visual');

	$result = array('content' => '');
	$cat_id = (!empty($_REQUEST['cat_id']) ? intval($_REQUEST['cat_id']) : 0);
    $deep = intval($_REQUEST['deep']);
	$cat_list = cat_list($cat_id);
	$html = '';
	$html_child = '';

	if ($cat_list) {
		$html = '<div class="imitate_select select_w220" id="cat_id1"><div class="cite">请选择...</div><ul>';

		foreach ($cat_list as $k => $v) {
			$html_child .= '<li><a href="javascript:void(0);" data-value="' . $v['cat_id'] . '">' . $v['cat_name'] . '</a></li>';
		}

		$html .= $html_child . '</ul><input type="hidden" value="" id="cat_id_val1"></div> ';
	}elseif($deep == 2){
		$html = '<div class="imitate_select select_w220" id="cat_id1"><div class="cite">暂无下级分类</div><ul>';
		$html .= $html_child . '</ul><input type="hidden" value="" id="cat_id_val1"></div> ';
	}else{
		$html = "";
		$html_child = '<div class="cite erji" ectype="tit">暂无下级分类</div>';
	}

	$result['content'] = $html;
	$result['contentChild'] = $html_child;
	exit(json_encode($result));	
}
public function header_bgOp(){
    $adminru['ru_id']= $_SESSION['store_id'];	
	$smarty = new cls_template();
	$tablePre = C('tablepre');
    $model = Model('visual');
	$smarty->template_dir = BASE_ROOT_PATH.DS.DIR_SHOP.'/templates/'.TPL_NAME;
	$smarty->compile_dir = BASE_ROOT_PATH.DS.DIR_SHOP.'/templates/'.TPL_NAME;	
	$json = new JSON();	
	$image = new cls_image();
	$result = array('error' => 0, 'prompt' => '', 'content' => '');
	$type = (isset($_REQUEST['type']) ? addslashes($_REQUEST['type']) : '');
	$name = (isset($_REQUEST['name']) ? addslashes($_REQUEST['name']) : '');
	$suffix = (isset($_REQUEST['suffix']) ? addslashes($_REQUEST['suffix']) : 'store_tpl_1');
	$topic_type = (isset($_REQUEST['topic_type']) ? addslashes($_REQUEST['topic_type']) : '');
	$allow_file_types = '|GIF|JPG|PNG|';

	if ($_FILES[$name]) {
		$file = $_FILES[$name];
		if ((isset($file['error']) && ($file['error'] == 0)) || (!isset($file['error']) && ($file['tmp_name'] != 'none'))) {
			if (!check_file_type($file['tmp_name'], $file['name'], $allow_file_types)) {
				$result['error'] = 1;
				$result['prompt'] = '请上传正确格式图片（' . $allow_file_types）;
			}
			else {
				if ($file['name']) {
					$ext = explode('.', $file['name']);
					$ext = array_pop($ext);
				}
				else {
					$ext = '';
				}

				$tem = '';

				if ($type == 'headerbg') {
					$tem = '/head';
				}
				else if ($type == 'contentbg') {
					$tem = '/content';
				}

				if ($topic_type == 'topic_type') {
					$file_dir = '../data/topic/topic_' . $adminru['ru_id'] . '/' . $suffix . '/images' . $tem;
				}
				else {
					$file_dir = '../data/seller_templates/seller_tem_' . $adminru['ru_id'] . '/' . $suffix . '/images' . $tem;
				}

				if (!is_dir($file_dir)) {
					mkdir($file_dir, 511, true);
				}

				$bgtype = '';

				if ($type == 'headerbg') {
					$bgtype = 'head';
					$file_name = $file_dir . '/hdfile_' . gmtime() . '.' . $ext;
					$back_name = '/hdfile_' . gmtime() . '.' . $ext;
				}
				else if ($type == 'contentbg') {
					$bgtype = 'content';
					$file_name = $file_dir . '/confile_' . gmtime() . '.' . $ext;
					$back_name = '/confile_' . gmtime() . '.' . $ext;
				}
				else {
					$file_name = $file_dir . '/slide_' . gmtime() . '.' . $ext;
					$back_name = '/slide_' . gmtime() . '.' . $ext;
				}

				if (move_upload_file($file['tmp_name'], $file_name)) {
					//$url = $GLOBALS['ecs']->seller_url();
					$content_file = $file_name;
					//$oss_img_url = str_replace('../', '', $content_file);
					//get_oss_add_file(array($oss_img_url));

					if ($bgtype) {
						$theme = '';
						$sql = 'SELECT id ,img_file FROM ' . $tablePre .'templates_left' . ' WHERE ru_id = \'' . $adminru['ru_id'] . '\' AND seller_templates = \'' . $suffix . '\' AND type = \'' . $bgtype . '\' AND theme = \'' . $theme . '\'';
						$templates_left = Model()->getRow($sql);

						if (0 < $templates_left['id']) {
							if ($templates_left['img_file'] != '') {
								@unlink($templates_left['img_file']);
							}

							$sql = 'UPDATE ' . $tablePre .'templates_left' . ' SET img_file = \'' . $content_file . '\' WHERE ru_id = \'' . $adminru['ru_id'] . '\' AND seller_templates = \'' . $suffix . '\' AND id=\'' . $templates_left['id'] . '\' AND type = \'' . $bgtype . '\' AND theme = \'' . $theme . '\'';
							
							Model()->query($sql);
						}
						else {
							$sql = 'INSERT INTO ' . $tablePre .'templates_left' . ' (`ru_id`,`seller_templates`,`img_file`,`type`) VALUES (\'' . $adminru['ru_id'] . '\',\'' . $suffix . '\',\'' . $content_file . '\',\'' . $bgtype . '\')';
						
							Model()->query($sql);
						}
					}

					$result['error'] = 2;
					$result['content'] = $content_file;
				}
				else {
					$result['error'] = 1;
					$result['prompt'] = '系统错误，请重新上传';
				}
			}
		}
	}
	else {
		$result['error'] = 1;
		$result['prompt'] = '请选择上传的图片';
	}

	exit(json_encode($result));	
}	
public function remove_imgOp(){
	$adminru['ru_id']= $_SESSION['store_id'];
	$tablePre = C('tablepre');
	$fileimg = (isset($_REQUEST['fileimg']) ? addslashes($_REQUEST['fileimg']) : '');
	$suffix = (isset($_REQUEST['suffix']) ? addslashes($_REQUEST['suffix']) : '');
	$type = (isset($_REQUEST['type']) ? addslashes($_REQUEST['type']) : '');

	if ($fileimg != '') {
		@unlink($fileimg);
	}

	$sql = 'UPDATE ' . $tablePre.'templates_left' . ' SET img_file = \'\' WHERE ru_id = \'' . $adminru['ru_id'] . '\' AND type = \'' . $type . '\' AND seller_templates = \'' . $suffix . '\' AND theme = \'\'';
	Model()->query($sql);	
}
public function generateOp(){
    $adminru['ru_id']= $_SESSION['store_id'];	
	$smarty = new cls_template();
	$tablePre = C('tablepre');
    $model = Model('visual');
	$smarty->template_dir = BASE_ROOT_PATH.DS.DIR_SHOP.'/templates/'.TPL_NAME;
	$smarty->compile_dir = BASE_ROOT_PATH.DS.DIR_SHOP.'/templates/'.TPL_NAME;	
	$json = new JSON();
	$result = array('error' => '', 'content' => '');
	$suffix = (isset($_REQUEST['suffix']) ? addslashes($_REQUEST['suffix']) : 'store_tpl_1');
	$bg_color = (isset($_REQUEST['bg_color']) ? stripslashes($_REQUEST['bg_color']) : '');
	$is_show = (isset($_REQUEST['is_show']) ? intval($_REQUEST['is_show']) : 0);
	$type = (isset($_REQUEST['type']) ? $_REQUEST['type'] : 'hrad');
	$bgshow = (isset($_REQUEST['bgshow']) ? addslashes($_REQUEST['bgshow']) : '');
	$bgalign = (isset($_REQUEST['bgalign']) ? addslashes($_REQUEST['bgalign']) : '');
	$theme = '';
	$sql = 'SELECT id  FROM ' . $tablePre . 'templates_left' . ' WHERE ru_id = \'' . $adminru['ru_id'] . '\' AND seller_templates = \'' . $suffix . '\' AND type=\'' . $type . '\' AND theme = \'' . $theme . '\'';
	$id = Model()->getOne($sql);

	if (0 < $id) {
		$sql = 'UPDATE ' . $tablePre . 'templates_left' . ' SET seller_templates = \'' . $suffix . '\',bg_color = \'' . $bg_color . '\' ,if_show = \'' . $is_show . '\',bgrepeat=\'' . $bgshow . '\',align= \'' . $bgalign . '\',type=\'' . $type . '\' WHERE ru_id = \'' . $adminru['ru_id'] . '\' AND seller_templates = \'' . $suffix . '\' AND id=\'' . $id . '\' AND type=\'' . $type . '\' AND theme = \'' . $theme . '\'';
	}
	else {
		$sql = 'INSERT INTO ' . $tablePre . 'templates_left' . ' (`ru_id`,`seller_templates`,`bg_color`,`if_show`,`bgrepeat`,`align`,`type`) VALUES (\'' . $adminru['ru_id'] . '\',\'' . $suffix . '\',\'' . $bg_color . '\',\'' . $is_show . '\',\'' . $bgshow . '\',\'' . $bgalign . '\',\'' . $type . '\')';
	}

	if (Model()->query($sql) == true) {
		$result['error'] = 1;
	}
	else {
		$result['error'] = 2;
		$result['content'] = '系统出错。请重试！！！';
	}

	exit(json_encode($result));		
}
public function filter_listOp(){
    $adminru['ru_id']= $_SESSION['store_id'];
	
	$smarty = new cls_template();
    $model = Model('visual');
	$smarty->template_dir = BASE_ROOT_PATH.DS.DIR_SHOP.'/templates/'.TPL_NAME;
	$smarty->compile_dir = BASE_ROOT_PATH.DS.DIR_SHOP.'/templates/'.TPL_NAME;	
	$json = new JSON();
	$search_type = (empty($_REQUEST['search_type']) ? '' : trim($_REQUEST['search_type']));
	$result = array('error' => 0, 'message' => '', 'content' => '');

	if ($search_type == 'goods') {
		$cat_id = (empty($_REQUEST['cat_id']) ? 0 : intval($_REQUEST['cat_id']));
		$brand_id = (empty($_REQUEST['brand_id']) ? 0 : intval($_REQUEST['brand_id']));
		$keyword = (empty($_REQUEST['keyword']) ? '' : trim($_REQUEST['keyword']));
		$filters['cat_id'] = $cat_id;
		$filters['brand_id'] = $brand_id;
		$filters['keyword'] = urlencode($keyword);
		$filters['sel_mode'] = 0;
		$filters['brand_keyword'] = '';
		$filters['exclude'] = '';
		$filters = $json->decode(urldecode(json_encode($filters)));
		$arr = get_goods_list($filters);
		$opt = array();

		foreach ($arr as $key => $val) {
			$opt[] = array('value' => $val['goods_id'], 'text' => $val['goods_name'], 'data' => $val['shop_price']);
		}

		$filter_list = $opt;
	}
	else if ($search_type == 'article') {
		$title = (empty($_REQUEST['keyword']) ? '' : trim($_REQUEST['keyword']));
		$where = ' WHERE cat_id > 0 ';

		if (!empty($title)) {
			$keyword = trim($filters['title']);
			$where .= ' AND title LIKE \'%' . mysql_like_quote($title) . '%\' ';
		}

		$sql = 'SELECT article_id, title FROM ' . $ecs->table('article') . $where . 'ORDER BY article_id DESC LIMIT 50';
		$res = $db->query($sql);
		$arr = array();

		while ($row = $db->fetchRow($res)) {
			$arr[] = array('value' => $row['article_id'], 'text' => $row['title'], 'data' => '');
		}

		$filter_list = $arr;
	}
	else if ($search_type == 'area') {
		$ra_id = (empty($_REQUEST['keyword']) ? 0 : intval($_REQUEST['keyword']));
		$arr = get_areaRegion_info_list($ra_id);
		$opt = array();

		foreach ($arr as $key => $val) {
			$opt[] = array('value' => $val['region_id'], 'text' => $val['region_name'], 'data' => 0);
		}

		$filter_list = $opt;
	}
	else if ($search_type == 'goods_type') {
		$cat_id = (empty($_REQUEST['keyword']) ? 0 : intval($_REQUEST['keyword']));
		$goods_fields = my_array_merge($_LANG['custom'], get_attributes($cat_id));
		$opt = array();

		foreach ($goods_fields as $key => $val) {
			$opt[] = array('value' => $key, 'text' => $val, 'data' => 0);
		}

		$filter_list = $opt;
	}
	else if ($search_type == 'get_content') {
		$cat_id = (empty($_REQUEST['cat_id']) ? 0 : intval($_REQUEST['cat_id']));
		$brand_id = (empty($_REQUEST['brand_id']) ? 0 : intval($_REQUEST['brand_id']));
		$keyword = (empty($_REQUEST['keyword']) ? '' : trim($_REQUEST['keyword']));
		$FloorBrand = (empty($_REQUEST['FloorBrand']) ? 0 : intval($_REQUEST['FloorBrand']));
		$brand_ids = (empty($_REQUEST['brand_ids']) ? '' : trim($_REQUEST['brand_ids']));
		$is_selected = (empty($_REQUEST['is_selected']) ? 0 : intval($_REQUEST['is_selected']));
		$filters['cat_id'] = $cat_id;
		$filters['brand_id'] = $brand_id;
		$filters['keyword'] = $keyword;
		$filters = $json->decode(json_encode($filters));
		$model_brand = Model('brand');
        if(intval($filters->brand_id)){
        	$condition['brand_id'] = intval($filters->brand_id);	
        }
        if(trim($filters->keyword)){
        	$condition['brand_name'] =rim($filters->keyword);
        }		
		$arr = array();
        if (intval($filters->cat_id) > 0){
            $condition1['gc_id'] = intval($filters->cat_id);
            $condition1['store_id'] = $adminru['ru_id'];
            $model_goods = Model('goods');
            $goods_id = $model_goods->getGoodsOnlineList($condition1);  
            
            if(is_array($goods_id)){
                foreach($goods_id as $ki=>$vi){
                	$cct = $model_brand->getBrandPassedList(array('brand_id'=>$vi['brand_id']));
                	   
                	if($cct){
                		foreach($cct as $kii=>$vii){
             	            $arr[$vii['brand_id']]['brand_id']= $vii['brand_id'];
            	            $arr[$vii['brand_id']]['brand_name']= $vii['brand_name'];
            	            $arr[$vii['brand_id']]['brand_logo']= $vii['brand_pic'];               			
                		}
                	}

                }              	
            }

               	
        }

	
		
		$opt = array();

		if ($FloorBrand == 1) {
			if (!empty($arr)) {
				$brand_ids = explode(',', $brand_ids);

				foreach ($arr as $key => $val) {
					//$val['brand_logo'] = brandImage($val['brand_logo']);//DATA_DIR . '/brandlogo/' . $val['brand_logo'];
					$arr[$key]['brand_logo'] = brandImage($val['brand_logo']);//get_image_path($val['brand_id'], $val['brand_logo']);
					if (!empty($brand_ids) && in_array($val['brand_id'], $brand_ids)) {
						$arr[$key]['selected'] = 1;
					}
					else if ($is_selected == 1) {
						unset($arr[$key]);
					}
				}
			}

			$smarty->assign('recommend_brands', $arr);
			$smarty->assign('temp', 'brand_query');
			$result['FloorBrand'] = $smarty->fetch('library/dialog.lbi');
		}
		else {
			foreach ($arr as $key => $val) {
				$opt[] = array('value' => $val['brand_id'], 'text' => $val['brand_name'], 'data' => $val['brand_id']);
			}
		}

		$filter_list = $opt;
	}

	$smarty->assign('filter_list', $filter_list);
	$result['content'] = $smarty->fetch('library/move_left.lbi');
	exit(json_encode($result));	
}
public function navigator1Op(){
    $adminru['ru_id']= $_SESSION['store_id'];	
    $tablePre = C('tablepre');
	$smarty = new cls_template();
    $model = Model('visual');
	$smarty->template_dir = BASE_ROOT_PATH.DS.DIR_SHOP.'/templates/'.TPL_NAME;
	$smarty->compile_dir = BASE_ROOT_PATH.DS.DIR_SHOP.'/templates/'.TPL_NAME;	
	$json = new JSON();
	$attr = array();
	$result = array('error' => 0, 'message' => '', 'content' => '');
	$result['mode'] = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';
	$result['diff'] = isset($_REQUEST['diff']) ? intval($_REQUEST['diff']) : 0;
	$result['spec_attr'] = !empty($_REQUEST['spec_attr']) ? stripslashes($_REQUEST['spec_attr']) : '';
	$_REQUEST['spec_attr'] = strip_tags(urldecode($_REQUEST['spec_attr']));
	$_REQUEST['spec_attr'] = json_str_iconv($_REQUEST['spec_attr']);

	if (!empty($_REQUEST['spec_attr'])) {
		$spec_attr = $json->decode($_REQUEST['spec_attr']);
		$spec_attr = object_to_array($spec_attr);
	}

	$result['navColor'] = $spec_attr['navColor'];
	$where = ' where ru_id = ' . $adminru['ru_id'] . ' AND ifshow = 1 ';
	$sql = 'SELECT name, url ' . ' FROM ' . $tablePre.'merchants_nav' . $where . ' ORDER by vieworder';
	$navigator = Model()->getAll1($sql);

	foreach ($navigator as $k => $v) {
		if ($v['url']) {
			$navigator[$k]['url'] = setRewrite($v['url']);
		}
	}

	$smarty->assign('navigator', $navigator);
	$index_url = '#';

	$index_url = urlShop('show_store','index',array('store_id'=>$adminru['ru_id'])); ;
	
	$smarty->assign('index_url', $index_url);
	$smarty->assign('temp', 'navigator');
	$smarty->assign('attr', $spec_attr);
	$result['content'] = $smarty->fetch('library/dialog.lbi');
	exit(json_encode($result));	
}	
public function navigatorOp(){
    $adminru['ru_id']= $_SESSION['store_id'];	
    $tablePre = C('tablepre');
	$smarty = new cls_template();
    $model = Model('visual');
	$smarty->template_dir = BASE_ROOT_PATH.DS.DIR_SHOP.'/templates/'.TPL_NAME;
	$smarty->compile_dir = BASE_ROOT_PATH.DS.DIR_SHOP.'/templates/'.TPL_NAME;	
	$json = new JSON();
	$result = array('content' => '', 'mode' => '');
	$topic_type = (isset($_REQUEST['topic_type']) ? trim($_REQUEST['topic_type']) : '');
	$spec_attr['target'] = '';
	$_REQUEST['spec_attr'] = !empty($_REQUEST['spec_attr']) ? stripslashes($_REQUEST['spec_attr']) : '';
	$_REQUEST['spec_attr'] = urldecode($_REQUEST['spec_attr']);
	$_REQUEST['spec_attr'] = json_str_iconv($_REQUEST['spec_attr']);

	if (!empty($_REQUEST['spec_attr'])) {
		$spec_attr = json_decode($_REQUEST['spec_attr'], true);
	}

	$result['diff'] = isset($_REQUEST['diff']) ? intval($_REQUEST['diff']) : 0;

	if ($topic_type == 'topic_type') {
		unset($spec_attr['target']);
		$navigator = $spec_attr;
	}
	else {
		$where = ' where ru_id = ' . $adminru['ru_id'];
		$sql = 'SELECT id, name,ifshow,  vieworder,  url ' . ' FROM ' . $tablePre.'merchants_nav' . $where . ' ORDER by vieworder';
		$navigator = Model()->getAll1($sql);
	}

	$spec_attr['target'] = isset($spec_attr['target']) ? $spec_attr['target'] : '_blank';
	$smarty->assign('navigator', $navigator);
	$smarty->assign('topic_type', $topic_type);
	$smarty->assign('temp', $_REQUEST['act']);
	$model_class = Model('store_goods_class');
    $goods_class = $model_class->getTreeClassList(array('store_id'=>$_SESSION['store_id']),2);
    $sysmain    = '';
    if(is_array($goods_class) and count($goods_class)>0) {
        foreach ($goods_class as $key => $val) {
        	$sysmain[$key]['cat_id'] = $val['stc_id'];
            $sysmain[$key]['cat_name'] = $val['stc_name'];
            $sysmain[$key]['url'] = urlShop('show_store','goods_all',array('store_id'=>$_SESSION['store_id'],'stc_id'=>$val['stc_id']));       
        }
        
    } else {
        $sysmain = '0';
    }	
	//$sysmain = get_sysnav();

	$smarty->assign('sysmain', $sysmain);
	$smarty->assign('attr', $spec_attr);
	$result['mode'] = isset($_REQUEST['mode']) ? addslashes($_REQUEST['mode']) : '';
	$result['content'] = $smarty->fetch('library/shop_banner.lbi');
	exit($json->encode($result));	
}
public function edit_navurlOp(){
    $adminru['ru_id']= $_SESSION['store_id'];	
    $tablePre = C('tablepre');
	$smarty = new cls_template();
    $model = Model('visual');
	$smarty->template_dir = BASE_ROOT_PATH.DS.DIR_SHOP.'/templates/'.TPL_NAME;
	$smarty->compile_dir = BASE_ROOT_PATH.DS.DIR_SHOP.'/templates/'.TPL_NAME;	
	$json = new JSON();
	$result = array('error' => '', 'pic_id' => '', 'content' => '');
	$url = (isset($_REQUEST['val']) ? addslashes($_REQUEST['val']) : '');
	$id = (isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0);

	if (0 < $id) {
		$sql = 'UPDATE ' . $tablePre.'merchants_nav' . ' SET url = \'' . $url . '\' WHERE id = \'' . $id . '\' AND ru_id = ' . $adminru['ru_id'];
		Model()->query($sql);
		$result['error'] = 1;
		$result['content'] = '编辑成功';
	}
	else {
		$result['error'] = 0;
		$result['content'] = '导航不存在';
	}

	exit(json_encode($result));	
}	
public function add_navOp(){
    $adminru['ru_id']= $_SESSION['store_id'];	
    $tablePre = C('tablepre');
	$smarty = new cls_template();
    $model = Model('visual');
	$smarty->template_dir = BASE_ROOT_PATH.DS.DIR_SHOP.'/templates/'.TPL_NAME;
	$smarty->compile_dir = BASE_ROOT_PATH.DS.DIR_SHOP.'/templates/'.TPL_NAME;	
	$json = new JSON();
	$result = array('error' => '', 'pic_id' => '', 'content' => '');
	$exc = $model->exchange('merchants_nav', $db, 'id', 'name');
	$link = (isset($_REQUEST['link']) ? addslashes($_REQUEST['link']) : '');
	$name = (isset($_REQUEST['nav_name']) ? addslashes($_REQUEST['nav_name']) : '');

	if (!empty($name)) {
		$is_only = $model->is_only('name', $name, 0, ' ru_id = ' . $adminru['ru_id']);

		if (!$is_only) {
			$result['error'] = 0;
			$result['content'] = '导航’' . $name . '‘已存在';
		}
		else {
			$sql = 'INSERT INTO ' . $tablePre.'merchants_nav' . '(`name`,`url`,`ifshow`,`type`,`ru_id`,`vieworder`) VALUES(\'' . $name . '\',\'' . $link . '\',1,\'middle\',\'' . $adminru['ru_id'] . '\',50)';
			
			$id = Model()->query($sql);
			$result['error'] = 1;
			$html_id = '\'' . $id . '\'';
			$html_act_name = '\'edit_navname\'';
			$html_act_url = '\'edit_navurl\'';
			$html_act_order = '\'edit_navvieworder\'';
			$html_act_if_show = '\'edit_ifshow\'';
			$html_act_type = '\'1\'';
			$html = '<tr><td><input type="text" onchange = "edit_nav(this.value ,' . $html_id . ',' . $html_act_name . ')" value="' . $name . '"></td>';
			$html .= '<td><input type="text" onchange = "edit_nav(this.value ,' . $html_id . ',' . $html_act_url . ')" value="' . $link . '"></td>';
			$html .= '<td class="center"><input type="text" onchange = "edit_nav(this.value ,' . $html_id . ',' . $html_act_order . ')" class="small" value="50"></td>';
			$html .= '<td class="center" id="nav_' . $id . '"><img onclick = "edit_nav(' . $html_act_type . ' ,' . $html_id . ',' . $html_act_if_show . ',' . $html_act_type . ')" src="images/yes.gif"/></td>';
			$html .= '<td class="center"><a href="javascript:void(0);" onclick="remove_nav(' . $html_id . ')" class="pic_del del">删除</a></td></tr>';
			$result['content'] = $html;
		}
	}
	else {
		$result['error'] = 0;
		$result['content'] = '导航名称不能为空';
	}

	exit(json_encode($result));		
}
public function headerOp(){
    $adminru['ru_id']= $_SESSION['store_id'];	
    $tablePre = C('tablepre');
	$smarty = new cls_template();
    $model = Model('visual');
	$smarty->template_dir = BASE_ROOT_PATH.DS.DIR_SHOP.'/templates/'.TPL_NAME;
	$smarty->compile_dir = BASE_ROOT_PATH.DS.DIR_SHOP.'/templates/'.TPL_NAME;	
	$json = new JSON();
	$result = array('content' => '', 'mode' => '');
	$arr = array();
	$smarty->assign('temp', $_REQUEST['act']);
	$_REQUEST['spec_attr'] = !empty($_REQUEST['spec_attr']) ? stripslashes($_REQUEST['spec_attr']) : '';
	$_REQUEST['spec_attr'] = urldecode($_REQUEST['spec_attr']);
	$_REQUEST['spec_attr'] = json_str_iconv($_REQUEST['spec_attr']);

	if (!empty($_REQUEST['spec_attr'])) {
		$spec_attr = json_decode($_REQUEST['spec_attr'], true);
	}

	$spec_attr['header_type'] = isset($spec_attr['header_type']) ? $spec_attr['header_type'] : 'defalt_type';
	$custom_content = (isset($spec_attr['custom_content']) ? unescape($spec_attr['custom_content']) : '');
	$custom_content = (!empty($custom_content) ? stripslashes($custom_content) : '');
	$result['mode'] = isset($_REQUEST['mode']) ? addslashes($_REQUEST['mode']) : '';
	$spec_attr['suffix'] = isset($_REQUEST['suffix']) ? addslashes($_REQUEST['suffix']) : '';
	$FCKeditor = create_ueditor_editor('custom_content', $custom_content, 486, 1);
	$smarty->assign('FCKeditor', $FCKeditor);
	$smarty->assign('content', $spec_attr);
	$result['content'] = $smarty->fetch('library/shop_banner.lbi');
	exit($json->encode($result));	
}	
public function cmsnewOp(){
    $adminru['ru_id']= $_SESSION['store_id'];	
    $tablePre = C('tablepre');
	$smarty = new cls_template();
    $model = Model('visual');
	$smarty->template_dir = BASE_ROOT_PATH.DS.DIR_SHOP.'/templates/'.TPL_NAME;
	$smarty->compile_dir = BASE_ROOT_PATH.DS.DIR_SHOP.'/templates/'.TPL_NAME;	
	$json = new JSON();
	$result = array('content' => '', 'mode' => '');
	$result['act'] = $_REQUEST['act'];
	$lift = (isset($_REQUEST['lift']) ? trim($_REQUEST['lift']) : '');
	$result['hierarchy'] = isset($_REQUEST['hierarchy']) ? trim($_REQUEST['hierarchy']) : '';
	$result['diff'] = isset($_REQUEST['diff']) ? intval($_REQUEST['diff']) : 0;
	$result['mode'] = isset($_REQUEST['mode']) ? trim($_REQUEST['mode']) : '';
	$_REQUEST['spec_attr'] = !empty($_REQUEST['spec_attr']) ? stripslashes($_REQUEST['spec_attr']) : '';
	$_REQUEST['spec_attr'] = urldecode($_REQUEST['spec_attr']);
	$_REQUEST['spec_attr'] = json_str_iconv($_REQUEST['spec_attr']);

	if (!empty($_REQUEST['spec_attr'])) {
		$spec_attr = json_decode($_REQUEST['spec_attr'], true);
	}
	
	
    $model_article_class = Model('cms_article_class');
    $article_class_list = $model_article_class->getList(TRUE, null, 'class_sort asc');
    $article_class_list = array_under_reset($article_class_list, 'class_id');
    $album_list =array();
	if(is_array($article_class_list)){
		foreach($article_class_list as $ki=>$vi){
			$album_list[$ki]['album_id']=$vi['class_id'];
			$album_list[$ki]['album_mame']=$vi['class_name'];
		}
	}

	
	$imgNumberArr = getAdvNum($result['mode']);
	$imgNumberArr = json_encode($imgNumberArr);
	$smarty->assign('r_banner_list', $spec_attr['r_list']);
	$smarty->assign('z_banner_list', $spec_attr['z_list']);
	$smarty->assign('banner_list', $spec_attr['list']);
	$smarty->assign('album_list', $album_list);
	$smarty->assign('z_album_list', $album_list);
	$smarty->assign('r_album_list', $album_list);
	$smarty->assign('cat_list', $cat_list);
	$smarty->assign('temp', $_REQUEST['act']);
	$smarty->assign('mode', $result['mode']);
	$smarty->assign('lift', $lift);
	$smarty->assign('spec_attr', $spec_attr);
	$smarty->assign('hierarchy', $result['hierarchy']);
	$smarty->assign('floor_style', $floor_style);
	$smarty->assign('imgNumberArr', $imgNumberArr);
	$result['content'] = $smarty->fetch('library/shop_banner.lbi');

	exit($json->encode($result));		
}
	public function get_newsOp() {				
		$smarty = new cls_template();
		$smarty->template_dir = BASE_ROOT_PATH.DS.DIR_SHOP.'/templates/'.TPL_NAME;
		$smarty->compile_dir = BASE_ROOT_PATH.DS.DIR_SHOP.'/templates/'.TPL_NAME;			
		$result = array('error' => 0, 'message' => '', 'content' => '');
		$is_vis = (!empty($_REQUEST['album_id']) ? intval($_REQUEST['album_id']) : '');
        $is_ajax = (!empty($_REQUEST['is_ajax']) ? intval($_REQUEST['is_ajax']) : '');
        $condition = array();
        if(!empty($is_vis)) {
            $condition['article_class_id'] = $is_vis;
        }
        $condition['article_state'] = 3;
        $condition['article_publisher_id'] = $_SESSION['member_id'];
        $model_article = Model('cms_article');
        $article_list = $model_article->getList($condition, '', 'article_sort asc, article_id desc');
        $model_article_class = Model('cms_article_class');
        
        $pic_list = array();
        if(is_array($article_list)){
        	foreach($article_list as $ko=>$vo){
        		$pic_list['list'][$ko]['pic_file']=getCMSArticleImageUrl($vo['article_attachment_path'], $vo['article_image']);
        		$pic_list['list'][$ko]['url_cms']=getCMSArticleUrl($vo['article_id']);
        		$pic_list['list'][$ko]['pic_title']=$vo['article_title'];
        		$pic_list['list'][$ko]['pic_spec']=$vo['article_abstract'];
        		$pic_list['list'][$ko]['pic_time']=date('Y-m-d',$vo['article_publish_time']);
        		$label = $model_article_class->getOne(array('class_id'=>$vo['article_class_id']));
        		$pic_list['list'][$ko]['label']= $label['class_name'];
        		$pic_list['list'][$ko]['article_id']=$vo['article_id'];
        		$pic_list['list'][$ko]['class_id']=$vo['article_class_id'];
        	}
        }
        $pic_list['filter']['album_id'] = $is_vis;//label	        
    if($is_ajax == 1){

		$smarty->assign('pic_list1', $pic_list['list']);
		$smarty->assign('filter1', $pic_list['filter']);
		$smarty->assign('temp', 'ajaxnews');
	}elseif($is_ajax == 2){

		$smarty->assign('z_pic_list1', $pic_list['list']);
		$smarty->assign('z_filter1', $pic_list['filter']);
		$smarty->assign('temp', 'z_ajaxnews');		
	}elseif($is_ajax == 3){

		$smarty->assign('r_pic_list1', $pic_list['list']);
		$smarty->assign('r_filter1', $pic_list['filter']);
		$smarty->assign('temp', 'r_ajaxnews');			
	}	
		$smarty->assign('inid', $inid);
		$result['content'] = $smarty->fetch('library/dialog.lbi');
		exit(json_encode($result));		
	}
public function cmsnewInsertOp(){
    $adminru['ru_id']= $_SESSION['store_id'];	
    $tablePre = C('tablepre');
	$smarty = new cls_template();
    $model = Model('visual');
	$smarty->template_dir = BASE_ROOT_PATH.DS.DIR_SHOP.'/templates/'.TPL_NAME;
	$smarty->compile_dir = BASE_ROOT_PATH.DS.DIR_SHOP.'/templates/'.TPL_NAME;	
	$json = new JSON();
	$result = array('error' => 0, 'message' => '', 'content' => '', 'mode' => '');

	if ($_REQUEST['spec_attr']) {
		$_REQUEST['spec_attr'] = strip_tags(urldecode($_REQUEST['spec_attr']));
		$_REQUEST['spec_attr'] = json_str_iconv($_REQUEST['spec_attr']);

		if (!empty($_REQUEST['spec_attr'])) {
			$spec_attr = $json->decode($_REQUEST['spec_attr']);
			$spec_attr = object_to_array($spec_attr);
		}
	}
	
	//滚动
    $lift = (isset($_REQUEST['lift']) ? trim($_REQUEST['lift']) : '');
    $floor_title = !empty($_REQUEST['floor_title']) ? trim($_REQUEST['floor_title']) : '';	
	$article_id_all = (isset($_REQUEST['article_id']) && ($_REQUEST['article_id'] != '') ? $_REQUEST['article_id'] : array());
	$link = (isset($_REQUEST['link']) && ($_REQUEST['link'] != '') ? $_REQUEST['link'] : array());
	$title = (!empty($_REQUEST['title']) && ($_REQUEST['title'] != '') ? $_REQUEST['title'] : array());
	$count = COUNT($article_id_all);
	$arr = array();
    $condition['article_state'] = 3;
    $cms_label = (!empty($_REQUEST['cms_label']) && ($_REQUEST['cms_label'] != '') ? $_REQUEST['cms_label'] : array());
    $model_article = Model('cms_article');
    $arr['count']= $count;
    //中间
	$z_article_id_all = (isset($_REQUEST['z_article_id']) && ($_REQUEST['z_article_id'] != '') ? $_REQUEST['z_article_id'] : array());
	$z_link = (isset($_REQUEST['z_link']) && ($_REQUEST['z_link'] != '') ? $_REQUEST['z_link'] : array());
	$z_title = (!empty($_REQUEST['z_title']) && ($_REQUEST['z_title'] != '') ? $_REQUEST['z_title'] : array());
	$z_count = COUNT($z_article_id_all);
    $z_cms_label = (!empty($_REQUEST['z_cms_label']) && ($_REQUEST['z_cms_label'] != '') ? $_REQUEST['z_cms_label'] : array());
    $arr['z_count']= $z_count;   
    
    //右部分
	$r_article_id_all = (isset($_REQUEST['r_article_id']) && ($_REQUEST['r_article_id'] != '') ? $_REQUEST['r_article_id'] : array());
	$r_link = (isset($_REQUEST['r_link']) && ($_REQUEST['r_link'] != '') ? $_REQUEST['r_link'] : array());
	$r_title = (!empty($_REQUEST['r_title']) && ($_REQUEST['r_title'] != '') ? $_REQUEST['r_title'] : array());
	$r_count = COUNT($r_article_id_all);
    $r_cms_label = (!empty($_REQUEST['r_cms_label']) && ($_REQUEST['r_cms_label'] != '') ? $_REQUEST['r_cms_label'] : array());
    $arr['r_count']= $r_count;       
          
	for ($i = 0; $i < $count; $i++) {
		if ($article_id_all[$i]) {			
			$condition['article_id'] = $article_id_all[$i];
			$article_list = $model_article->getOne($condition);						
			$arr['list'][$i + 1]['pic_src'] = getCMSArticleImageUrl($article_list['article_attachment_path'], $article_list['article_image']);
			$arr['list'][$i + 1]['link'] = $link[$i];
			$arr['list'][$i + 1]['title'] = $title[$i];
            $arr['list'][$i + 1]['article_abstract'] = $article_list['article_abstract'];
            $arr['list'][$i + 1]['cms_label'] = $cms_label[$i];
            $arr['list'][$i + 1]['article_id'] = $article_list['article_id'];
            $arr['list'][$i + 1]['class_id'] = $article_list['article_class_id'];
		}
	}
	$i = 0;	
	for ($i = 0; $i < $z_count; $i++) {
		if ($z_article_id_all[$i]) {			
			$condition['article_id'] = $z_article_id_all[$i];
			$article_list = $model_article->getOne($condition);	
							
			$arr['z_list'][$i + 1]['pic_src'] = getCMSArticleImageUrl($article_list['article_attachment_path'], $article_list['article_image']);
			$arr['z_list'][$i + 1]['link'] = $z_link[$i];
			$arr['z_list'][$i + 1]['title'] = $z_title[$i];
            $arr['z_list'][$i + 1]['article_abstract'] = $article_list['article_abstract'];
            $arr['z_list'][$i + 1]['cms_label'] = $z_cms_label[$i];
            $arr['z_list'][$i + 1]['article_id'] = $article_list['article_id'];
            $arr['z_list'][$i + 1]['class_id'] = $article_list['article_class_id'];
		}
	}	
	$i = 0;	
	for ($i = 0; $i < $r_count; $i++) {
		if ($r_article_id_all[$i]) {			
			$condition['article_id'] = $r_article_id_all[$i];
			$article_list = $model_article->getOne($condition);	
							
			$arr['r_list'][$i + 1]['pic_src'] = getCMSArticleImageUrl($article_list['article_attachment_path'], $article_list['article_image']);
			$arr['r_list'][$i + 1]['link'] = $r_link[$i];
			$arr['r_list'][$i + 1]['title'] = $r_title[$i];
            $arr['r_list'][$i + 1]['article_abstract'] = $article_list['article_abstract'];
            $arr['r_list'][$i + 1]['cms_label'] = $r_cms_label[$i];
            $arr['r_list'][$i + 1]['article_id'] = $article_list['article_id'];
            $arr['r_list'][$i + 1]['class_id'] = $article_list['article_class_id'];
		}
	}		
	$arr['lift'] = $lift;
	$arr['floor_title'] = $floor_title;	
	
	

	$smarty->assign('attrlift', $lift);	
    $smarty->assign('attrfloor_title', $floor_title);	
	$smarty->assign('temp', 'cnews');
	$smarty->assign('attrlist', $arr['list']);
	$smarty->assign('z_attrlist', $arr['z_list']);
	$smarty->assign('attrcount', $arr['count']);
	$smarty->assign('z_attrcount', $arr['z_count']);
	
	$smarty->assign('r_attrlist', $arr['r_list']);

	$smarty->assign('r_attrcount', $arr['r_count']);	
	
	$result['masterTitle'] = $_REQUEST['act'];
	$result['spec_attr'] = $arr;
	$result['content'] = $smarty->fetch('library/dialog.lbi');
	exit(json_encode($result));		
}
public function ylinksOp(){
    $adminru['ru_id']= $_SESSION['store_id'];	
    $tablePre = C('tablepre');
	$smarty = new cls_template();
    $model = Model('visual');
	$smarty->template_dir = BASE_ROOT_PATH.DS.DIR_SHOP.'/templates/'.TPL_NAME;
	$smarty->compile_dir = BASE_ROOT_PATH.DS.DIR_SHOP.'/templates/'.TPL_NAME;	
	$json = new JSON();
	$result = array('content' => '', 'mode' => '');
	$result['act'] = $_REQUEST['act'];
	$lift = (isset($_REQUEST['lift']) ? trim($_REQUEST['lift']) : '');
	$result['hierarchy'] = isset($_REQUEST['hierarchy']) ? trim($_REQUEST['hierarchy']) : '';
	$result['diff'] = isset($_REQUEST['diff']) ? intval($_REQUEST['diff']) : 0;
	$result['mode'] = isset($_REQUEST['mode']) ? trim($_REQUEST['mode']) : '';
	$_REQUEST['spec_attr'] = !empty($_REQUEST['spec_attr']) ? stripslashes($_REQUEST['spec_attr']) : '';
	$_REQUEST['spec_attr'] = urldecode($_REQUEST['spec_attr']);
	$_REQUEST['spec_attr'] = json_str_iconv($_REQUEST['spec_attr']);

	if (!empty($_REQUEST['spec_attr'])) {
		$spec_attr = json_decode($_REQUEST['spec_attr'], true);
	}
    


    $smarty->assign('is_show', $spec_attr['header_dis_links']);

	$smarty->assign('banner_list', $spec_attr['list']);
	$smarty->assign('temp', $_REQUEST['act']);
	$smarty->assign('mode', $result['mode']);
	$smarty->assign('lift', $spec_attr['lift']);
	$smarty->assign('spec_attr', $spec_attr);
	$smarty->assign('hierarchy', $result['hierarchy']);
	$smarty->assign('floor_style', $floor_style);
	$result['content'] = $smarty->fetch('library/shop_banner.lbi');

	exit($json->encode($result));	
}	

public function ylinksInsertOp(){
    $adminru['ru_id']= $_SESSION['store_id'];	
    $tablePre = C('tablepre');
	$smarty = new cls_template();
    $model = Model('visual');
	$smarty->template_dir = BASE_ROOT_PATH.DS.DIR_SHOP.'/templates/'.TPL_NAME;
	$smarty->compile_dir = BASE_ROOT_PATH.DS.DIR_SHOP.'/templates/'.TPL_NAME;	
	$json = new JSON();
	$result = array('error' => 0, 'message' => '', 'content' => '', 'mode' => '');
	$arr['act'] = $_REQUEST['act'];
	$lift = (isset($_REQUEST['lift']) ? trim($_REQUEST['lift']) : '');
	$arr['hierarchy'] = isset($_REQUEST['hierarchy']) ? trim($_REQUEST['hierarchy']) : '';
	$arr['diff'] = isset($_REQUEST['diff']) ? intval($_REQUEST['diff']) : 0;
	$arr['mode'] = isset($_REQUEST['mode']) ? trim($_REQUEST['mode']) : '';
    $arr['lift'] = $lift;
    $arr['tm-picker-trigger-links'] =  isset($_REQUEST['tm-picker-trigger-links']) ? trim($_REQUEST['tm-picker-trigger-links']) : '';

    $arr['header_dis_links'] = isset($_REQUEST['header_dis_links']) ? intval($_REQUEST['header_dis_links']) : 0;

	$links_title = (isset($_REQUEST['links_title']) && ($_REQUEST['links_title'] != '') ? $_REQUEST['links_title'] : array());
	$title = (!empty($_REQUEST['title']) && ($_REQUEST['title'] != '') ? $_REQUEST['title'] : array());
	$count = COUNT($links_title);

          
	for ($i = 0; $i < $count; $i++) {
		if ($links_title[$i]) {								
			$arr['list'][$i + 1]['links_title'] = $links_title[$i];
			$arr['list'][$i + 1]['title'] = $title[$i];
		}
	}

	$smarty->assign('linkslift', $lift);	
    $smarty->assign('header_dis_links', $arr['header_dis_links']);	
    $smarty->assign('tm-picker-trigger-links', $arr['tm-picker-trigger-links']);
	$smarty->assign('temp', 'ylinks');
	$smarty->assign('linkslist', $arr['list']);		
		
	$result['masterTitle'] = $_REQUEST['act'];
	
	$result['spec_attr'] = $arr;
	
	$result['content'] = $smarty->fetch('library/dialog.lbi');		
	exit($json->encode($result));
}

}
