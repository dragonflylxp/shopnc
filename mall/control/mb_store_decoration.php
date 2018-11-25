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
class mb_store_decorationControl extends BaseSellerControl {
    public function __construct() {
        parent::__construct();
    }
const MAX_MB_SLIDERS = 4;	
const ARTICLE_STATE_PUBLISHED = 3;
    /**
     * 店铺装修设置
     */
    public function decoration_settingOp() {
        $model_store_decoration = Model('mb_store_decoration');

        $store_decoration_info = $model_store_decoration->getStoreDecorationInfo(array('store_id' => $_SESSION['store_id']));
        if(empty($store_decoration_info)) {
            //创建默认装修
            $param = array();
            $param['decoration_name'] = '默认装修';
            $param['store_id'] = $_SESSION['store_id'];
            $param['mb_store_navigation'] = '0';
            $param['mb_store_menu'] = '0';
            $decoration_id = $model_store_decoration->addStoreDecoration($param);
        } else {
            $decoration_id = $store_decoration_info['decoration_id'];
        }

        Tpl::output('mb_store_decoration_switch', $this->store_info['mb_store_decoration_switch']);

        Tpl::output('is_own_shop', $this->store_info['is_own_shop']);
        Tpl::output('left_bar_type', $this->store_info['left_bar_type']);
        Tpl::output('decoration_id', $decoration_id);

        $this->profile_menu('decoration_setting');
        Tpl::showpage('mb_store_decoration.setting');
    }

    /**
     * 店铺装修设置保存
     */
    public function decoration_setting_saveOp() {
        $model_store_decoration = Model('mb_store_decoration');
        $model_store = Model('store');

        $store_decoration_info = $model_store_decoration->getStoreDecorationInfo(array('store_id' => $_SESSION['store_id']));
        if(empty($store_decoration_info)) {
            showDialog('参数错误');
        }

        $update = array();
        if(empty($_POST['mb_store_decoration_switch'])) {
            $update['mb_store_decoration_switch'] = 0;
        } else {
              $update['mb_store_decoration_switch'] = intval($_POST['mb_store_decoration_switch']);
        }
      
        $result = $model_store->editStore($update, array('store_id' => $_SESSION['store_id']));
        if($result) {
            showDialog(L('nc_common_save_succ'), '', 'succ');
        } else {
            showDialog(L('nc_common_save_fail'));
        }
    }
    /**
     * 专题项目添加
     */
    public function special_item_addOp() {
        $model_mb_special = Model('mb_store_special');

        $param = array();
        $param['special_id'] = $_POST['special_id'];
        $param['item_type'] = $_POST['item_type'];
        $param['store_id'] =$_SESSION['store_id'];
        //广告只能添加一个
        if($param['item_type'] == 'adv_list') {
            $result = $model_mb_special->isMbSpecialItemExist($param);
            if($result) {
                echo json_encode(array('error' => '广告条板块只能添加一个'));die;
            }
        }

        $item_info = $model_mb_special->addMbSpecialItem($param);
        if($item_info) {
            echo json_encode($item_info);die;
        } else {
            echo json_encode(array('error' => '添加失败'));die;
        }
    }

    /**
     * 专题项目删除
     */
    public function special_item_delOp() {
        $model_mb_special = Model('mb_store_special');

        $condition = array();
        $condition['item_id'] = $_POST['item_id'];
        $condition['store_id'] =$_SESSION['store_id'];
        $result = $model_mb_special->delMbSpecialItem($condition, $_POST['special_id']);
        if($result) {
            echo json_encode(array('message' => '删除成功'));die;
        } else {
            echo json_encode(array('error' => '删除失败'));die;
        }
    }

    /**
     * 专题项目编辑
     */
    public function special_item_editOp() {
        $model_mb_special = Model('mb_store_special');
        $store_id = $_SESSION['store_id'];
        $item_info = $model_mb_special->getMbSpecialItemInfoByID($_GET['item_id'],$store_id);
        Tpl::output('item_info', $item_info);

//      if($item_info['special_id'] == 0) {
//          $this->profile_menu('decoration_setting');
//      } else {
//          $this->profile_menu('decoration_setting');
//      }
        Tpl::output('seller_layout_no_menu', true);
        Tpl::showpage('mb_store_special_item.edit');
    }

    /**
     * 专题项目保存
     */
    public function special_item_saveOp() {
        $model_mb_special = Model('mb_store_special');
    
        if($_POST['article_content']){
        	
        	$result = $model_mb_special->editMbSpecialItemByIDa(array('item_data' => $_POST['item_data']),array('article_content' => $_POST['article_content']), $_POST['item_id'], $_POST['special_id'],$_SESSION['store_id']);
        }else{
        	$result = $model_mb_special->editMbSpecialItemByID(array('item_data' => $_POST['item_data']), $_POST['item_id'], $_POST['special_id'],$_SESSION['store_id']);

        }


        if($result) {
 
               showMessage(L('nc_common_save_succ'), urlShop('mb_store_decoration', 'decoration_edit'));

        } else {
           showMessage(L('nc_common_save_succ'), '');
        }
    }

    /**
     * 图片上传
     */
    public function special_image_uploadOp() {
        $data = array();
        if(!empty($_FILES['special_image']['name'])) {
            $prefix = 's' . $_SESSION['store_id'].$_POST['special_id'];
            $upload = new UploadFile();
            $upload->set('default_dir', ATTACH_MOBILE . DS . 'special' . DS . $prefix);
            $upload->set('fprefix', $prefix);
            $upload->set('allow_type', array('gif', 'jpg', 'jpeg', 'png'));

            $result = $upload->upfile('special_image');
            if(!$result) {
                $data['error'] = $upload->error;
            }
            $data['image_name'] = $upload->file_name;
            $data['image_url'] = getMbSpecialImageUrl($data['image_name']);
        }
        echo json_encode($data);
    }
    /**
     * 更新项目排序
     */
    public function update_item_sortOp() {
        $item_id_string = $_POST['item_id_string'];
        $special_id = $_POST['special_id'];
        if(!empty($item_id_string)) {
            $model_mb_special = Model('mb_store_special');
            $item_id_array = explode(',', $item_id_string);
            $index = 0;
            foreach ($item_id_array as $item_id) {
                $result = $model_mb_special->editMbSpecialItemByID(array('item_sort' => $index), $item_id, $special_id,$_SESSION['store_id']);
                $index++;
            }
        }
        $data = array();
        $data['message'] = '操作成功';
        echo json_encode($data);
    }
    /**
     * 更新项目启用状态
     */
    public function update_item_usableOp() {
        $model_mb_special = Model('mb_store_special');
        $result = $model_mb_special->editMbSpecialItemUsableByID($_POST['usable'], $_POST['item_id'], $_POST['special_id'],$_SESSION['store_id']);
        $data = array();
        if($result) {
            $data['message'] = '操作成功';
        } else {
            $data['error'] = '操作失败';
        }
        echo json_encode($data);
    }    
    /**
     * 导航装修
     */
    public function decoration_albumOp() {
        $model_store_decoration = Model('mb_store_decoration');
        $store_decoration_info = $model_store_decoration->getStoreDecorationInfo(array('store_id' => $_SESSION['store_id']));
        //print_r($store_decoration_info['decoration_nav']);exit;
        if($store_decoration_info['decoration_nav']){
        	$store_imgname = @unserialize($store_decoration_info['decoration_nav']);
	
        }else{
        	$store_imgname='';
        }
		
        if (chksubmit()){
        
            $skuToValid = array();
			
            foreach ((array) $_POST['mb_sliders_names'] as $k => $v) {
                if ($k < 1 || $k > self::MAX_MB_SLIDERS) {
                    showDialog('参数错误');
                } 
				foreach ((array) $_POST['mb_sliders_links'] as $kc => $vc) {
					if($k == $kc){
						$store_imgname[$k]['name']  =  $v;
						$store_imgname[$k]['links']  =  $vc;
					}
					
				}
				
				
				
				 	
			}

			
        	$param = array();
        	$condition= array();
            $condition['store_id'] = $_SESSION['store_id'];
            $param['mb_store_navigation'] = $_POST['mb_store_navigation'];
			$param['decoration_nav'] = serialize($store_imgname);
        	$decoration_id = $model_store_decoration->editStoreDecoration($param,$condition);
            if($decoration_id) {
                showDialog(L('nc_common_save_succ'), '', 'succ');
            } else {
                showDialog(L('nc_common_save_fail'));
            }        	
        }
		
		Tpl::output('mbSliders', $store_imgname);
		Tpl::output('max_mb_sliders', self::MAX_MB_SLIDERS);
        Tpl::output('mb_store_navigation', $store_decoration_info['mb_store_navigation']);
        $this->profile_menu('decoration_album');
        Tpl::showpage('mb_store_decoration.album');
    }
    public function store_mb_sliders_dropOp()
    {
        try {
            $id = (int) $_REQUEST['id'];
            if ($id < 1 || $id > self::MAX_MB_SLIDERS) {
                throw new Exception('参数错误');
            }

            $model_store_decoration = Model('mb_store_decoration');
            $store_decoration_info = $model_store_decoration->getStoreDecorationInfo(array('store_id' => $_SESSION['store_id']));
            if($store_decoration_info['decoration_nav']){
            	$store_imgname = @unserialize($store_decoration_info['decoration_nav']);	
				
            }else{
            	throw new Exception('没有可删数据除');
            }
			if(is_array($store_imgname[$id])){
				$mbSliders[$id]['img'] = '';

                if (!$this->setStoreMbSliders($mbSliders,$store_imgname,$id)) {
                    throw new Exception('更新失败');
                }				
			}else{
				throw new Exception('没有可删数据除1');
			}
            


            echo json_encode(array(
                'success' => true,
            ));

        } catch (\Exception $ex) {
            echo json_encode(array(
                'success' => false,
                'error' => $ex->getMessage(),
            ));
        }
    }
    protected function setStoreMbSliders($mbSliders,$store_imgname,$id)
    {
    	
    	$store_imgname[$id]['img'] = $mbSliders[$id]['img'];
		$condition =array();
		$condition['store_id']=$_SESSION['store_id'];
        return Model('mb_store_decoration')->editStoreDecoration(array('decoration_nav' => serialize($store_imgname)),$condition);
    }
    public function store_mb_slidersOp()
    {
        try {
            $fileName = (string) $_POST['id'];
            if (!preg_match('/^file_(\d+)$/', $fileName, $fileIndex) || empty($_FILES[$fileName]['name'])) {
                throw new Exception('参数错误');
            }

            $fileIndex = (int) $fileIndex[1];
            if ($fileIndex < 1 || $fileIndex > self::MAX_MB_SLIDERS) {
                throw new Exception('参数错误2');
            }
			
            $model_store_decoration = Model('mb_store_decoration');
            $store_decoration_info = $model_store_decoration->getStoreDecorationInfo(array('store_id' => $_SESSION['store_id']));

            $store_imgname = @unserialize($store_decoration_info['decoration_nav']);	
            $mbSliders = $store_imgname;

            $upload = new UploadFile();
            $upload->set('default_dir', ATTACH_STORE);
            $upload->set('thumb_ext', '');
            $upload->set('file_name', '');
            $upload->set('ifremove', false);
            $result = $upload->upfile($fileName);

            if (!$result) {
                throw new Exception($upload->error);
            }

            $oldImg = $mbSliders[$fileIndex]['img'];
            $newImg = $upload->file_name;

            $mbSliders[$fileIndex]['img'] = $newImg;
            
			
			
			
            if (!$this->setStoreMbSliders($mbSliders,$store_imgname,$fileIndex)) {
                throw new Exception('更新失败');
            }

            if ($oldImg && file_exists($oldImg)) {
                unlink($oldImg);
            }

            echo json_encode(array(
                'uploadedUrl' => UPLOAD_SITE_URL.DS.ATTACH_STORE.DS.$newImg,
            ));

        } catch (\Exception $ex) {
            echo json_encode(array(
                'error' => $ex->getMessage(),
            ));
        }
    }	
    /**
     * 底部菜单
     */
    public function decoration_menuOp() {
        $model_store_decoration = Model('mb_store_decoration');
        $store_decoration_info = $model_store_decoration->getStoreDecorationInfo(array('store_id' => $_SESSION['store_id']));
        $decoration_banner = @unserialize($store_decoration_info['decoration_banner']);
		$mb_store_menu = $store_decoration_info['mb_store_menu'];
		$posts_menu = @unserialize($store_decoration_info['decoration_banner']);
        if (chksubmit()){
        	$param = array();
        	$condition= array();
            $condition['store_id'] = $_SESSION['store_id'];
			$posts = array();
			if($_POST['name_0']){$posts[0]['name']=$_POST['name_0'];}
			if($_POST['links_0']){$posts[0]['links']=$_POST['links_0'];}
			if($_POST['name_1']){$posts[1]['name']=$_POST['name_1'];}
			if($_POST['links_1']){$posts[1]['links']=$_POST['links_1'];}
			if($_POST['name_2']){$posts[2]['name']=$_POST['name_2'];}
			if($_POST['links_2']){$posts[2]['links']=$_POST['links_2'];}
			if($_POST['name_3']){$posts[3]['name']=$_POST['name_3'];}
			if($_POST['links_3']){$posts[3]['links']=$_POST['links_3'];}
			if($_POST['name_4']){$posts[4]['name']=$_POST['name_4'];}
			if($_POST['links_4']){$posts[4]['links']=$_POST['links_4'];}

			
			
			
			
			$param['decoration_banner'] = serialize($posts);
	
			
			
            $param['mb_store_menu'] = $_POST['mb_store_menu'];
        	$decoration_id = $model_store_decoration->editStoreDecoration($param,$condition);
			
            if($decoration_id) {
                showDialog(L('nc_common_save_succ'), '', 'succ');
            } else {
                showDialog(L('nc_common_save_fail'));
            }        	
        }
        Tpl::output('mb_store_menu', $mb_store_menu);
        Tpl::output('decoration_banner', $decoration_banner);

        $this->profile_menu('decoration_menu');
        Tpl::showpage('mb_store_decoration.menu');
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

//  /**
//   * 店铺装修
//   */
//  public function decoration_editOp() {
//      $decoration_id = intval($_GET['decoration_id']);
//
//      $model_store_decoration = Model('mb_store_decoration');
//
//      $decoration_info = $model_store_decoration->getStoreDecorationInfoDetail($decoration_id, $_SESSION['store_id']);
//      if($decoration_info) {
//          $this->_output_decoration_info($decoration_info);
//      } else {
//          showMessage(L('param_error'), '', 'error');
//      }
//
//      //设定模板为完成宽度
//      Tpl::output('seller_layout_no_menu', true);
//      Tpl::showpage('mb_store_decoration.edit');
//  }
    /**
     * 编辑首页
     */
    public function decoration_editOp() {
        $model_mb_special = Model('mb_store_special');
        $store_id = $_SESSION['store_id'];

        $special_item_list = $model_mb_special->getMbSpecialItemListByID($model_mb_special::INDEX_SPECIAL_ID,$store_id);
        Tpl::output('list', $special_item_list);
        Tpl::output('page', $model_mb_special->showpage(2));

        Tpl::output('module_list', $model_mb_special->getMbSpecialModuleList());
        Tpl::output('special_id', $model_mb_special::INDEX_SPECIAL_ID);

        Tpl::output('seller_layout_no_menu', true);
        Tpl::showpage('mb_store_decoration.edit');
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
     * 保存店铺装修背景设置
     */
    public function decoration_background_setting_saveOp() {
        $decoration_id = intval($_POST['decoration_id']);

        //验证参数
        if($decoration_id <= 0) {
            $data['error'] = L('param_error');
            echo json_encode($data);die;
        }

        $setting = array();
        $setting['background_color'] = $_POST['background_color'];
        $setting['background_image'] = $_POST['background_image'];
        $setting['background_image_repeat'] = $_POST['background_image_repeat'];
        $setting['background_position_x'] = $_POST['background_position_x'];
        $setting['background_position_y'] = $_POST['background_position_y'];
        $setting['background_attachment'] = $_POST['background_attachment'];

        //背景设置保存验证
        $validate_setting = $this->_validate_background_setting_input($decoration_id, $setting);
        if(isset($validate_setting['error'])) {
            $data['error'] = $validate_setting['error'];
            echo json_encode($data);die;
        }

        $data = array();

        $model_store_decoration = Model('store_decoration');

        $condition = array();
        $condition['decoration_id'] = $decoration_id;
        $condition['store_id'] = $_SESSION['store_id'];

        $update = array();
        $update['decoration_setting'] = serialize($setting);

        $result = $model_store_decoration->editStoreDecoration($update, $condition);
        if($result) {
            $data['decoration_background_style'] = $model_store_decoration->getDecorationBackgroundStyle($validate_setting);
        } else {
            $data['error'] = '保存失败';
        }
        echo json_encode($data);die;
    }

    /**
     * 背景设置保存验证
     */
    private function _validate_background_setting_input($decoration_id, $setting) {
        //验证输入
        if($decoration_id <= 0) {
            return array('error', L('param_error'));
        }
        if(!empty($setting['background_color'])) {
            if(strlen($setting['background_color']) > 7) {
                return array('error', '请输入正确的背景颜色');
            }
        } else {
            $setting['background_color'] = '';
        }
        if(!empty($setting['background_image'])) {
            $setting['background_image_url'] = getStoreDecorationImageUrl($setting['background_image'], $_SESSION['store_id']);
            if($setting['background_image_url'] == '') {
                return array('error', '请选择正确的背景图片');
            }
        } else {
            $setting['background_image'] = '';
        }
        if(!in_array($setting['background_image_repeat'], array('no-repeat', 'repeat', 'repeat-x', 'repeat-y'))) {
            $setting['background_image_repeat'] = '';
        }
        if(strlen($setting['background_position_x']) > 8) {
            $setting['background_position_x'] = '';
        }
        if(strlen($setting['background_position_y']) > 8) {
            $setting['background_position_y'] = '';
        }
        if(strlen($setting['background_attachment']) > 8) {
            $setting['background_attachment'] = '';
        }
        return $setting;
    }

    /**
     * 装修导航保存
     */
    public function decoration_nav_saveOp() {
        $decoration_id = intval($_POST['decoration_id']);
        $nav = array();
        $nav['display'] = $_POST['nav_display'];
        $nav['style'] = $_POST['content'];

        $data = array();

        //验证参数
        if($decoration_id <= 0) {
            $data['error'] = L('param_error');
            echo json_encode($data);die;
        }

        $model_store_decoration = Model('store_decoration');

        $condition = array();
        $condition['decoration_id'] = $decoration_id;
        $condition['store_id'] = $_SESSION['store_id'];

        $update = array();
        $update['decoration_nav'] = serialize($nav);

        $result = $model_store_decoration->editStoreDecoration($update, $condition);
        if($result) {
            $data['message'] = '保存成功';
        } else {
            $data['error'] = '保存失败';
        }
        echo json_encode($data);die;
    }

    /**
     * 装修banner保存
     */
    public function decoration_banner_saveOp() {
        $decoration_id = intval($_POST['decoration_id']);
        $banner = array();
        $banner['display'] = $_POST['banner_display'];
        $banner['image'] = $_POST['content'];

        $data = array();

        //验证参数
        if($decoration_id <= 0) {
            $data['error'] = L('param_error');
            echo json_encode($data);die;
        }

        $model_store_decoration = Model('store_decoration');

        $condition = array();
        $condition['decoration_id'] = $decoration_id;
        $condition['store_id'] = $_SESSION['store_id'];

        $update = array();
        $update['decoration_banner'] = serialize($banner);

        $result = $model_store_decoration->editStoreDecoration($update, $condition);
        if($result) {
            $data['message'] = '保存成功';
            $data['image_url'] = getStoreDecorationImageUrl($banner['image'], $_SESSION['store_id']);
        } else {
            $data['error'] = '保存失败';
        }
        echo json_encode($data);die;
    }

    /**
     * 装修添加块
     */
    public function decoration_block_addOp() {
        $decoration_id = intval($_POST['decoration_id']);
        $block_layout = $_POST['block_layout'];

        $data = array();

        $model_store_decoration = Model('store_decoration');

        //验证装修编号
        $condition = array();
        $condition['decoration_id'] = $decoration_id;
        $decoration_info = $model_store_decoration->getStoreDecorationInfo($condition, $_SESSION['store_id']);
        if(!$decoration_info) {
            $data['error'] = L('param_error');
            echo json_encode($data);
        }

        //验证装修块布局
        $block_layout_array = $model_store_decoration->getStoreDecorationBlockLayoutArray();
        if(!in_array($block_layout, $block_layout_array)) {
            $data['error'] = L('param_error');
            echo json_encode($data);
        }

        $param = array();
        $param['decoration_id'] = $decoration_id;
        $param['store_id'] = $_SESSION['store_id'];
        $param['block_layout'] = $block_layout;
        $block_id = $model_store_decoration->addStoreDecorationBlock($param);

        if($block_id) {
            ob_start();
            Tpl::output('block', array('block_id' => $block_id));
            Tpl::showpage('store_decoration_block', 'null_layout');
            $temp = ob_get_contents();
            ob_end_clean();

            $data['html'] = $temp;
        } else {
            $data['error'] = '添加失败';
        }
        echo json_encode($data);die;
    }

    /**
     * 装修块删除
     */
    public function decoration_block_delOp() {
        $block_id = intval($_POST['block_id']);

        $data = array();

        $model_store_decoration = Model('store_decoration');

        $condition = array();
        $condition['block_id'] = $block_id;
        $condition['store_id'] = $_SESSION['store_id'];

        $result = $model_store_decoration->delStoreDecorationBlock($condition);

        if($result) {
            $data['message'] = '删除成功';
        } else {
            $data['error'] = '删除失败';
        }
        echo json_encode($data);die;

    }

    /**
     * 装修块保存
     */
    public function decoration_block_saveOp() {
        $block_id = intval($_POST['block_id']);
        $module_type = $_POST['module_type'];

        $data = array();

        $model_store_decoration = Model('store_decoration');

        //验证模块类型
        $block_type_array = $model_store_decoration->getStoreDecorationBlockTypeArray();
        if(!in_array($module_type, $block_type_array)) {
            $data['error'] = L('param_error');
            echo json_encode($data);
        }

        switch ($module_type) {
            case 'html':
                $content = htmlspecialchars($_POST['content']);
                break;
            default:
                $content = serialize($_POST['content']);
        }

        $condition = array();
        $condition['block_id'] = $block_id;
        $condition['store_id'] = $_SESSION['store_id'];

        $param = array();
        $param['block_content'] = $content;
        $param['block_full_width'] = intval($_POST['full_width']);
        $param['block_module_type'] = $module_type;
        $result = $model_store_decoration->editStoreDecorationBlock($param, $condition);

        if($result) {
            $data['message'] = '保存成功';
            $data['html'] = $this->_get_block_html($content, $module_type);
        } else {
            $data['error'] = '保存失败';
        }
        echo json_encode($data);die;
    }

    /**
     * 装修块排序
     */
    public function decoration_block_sortOp() {
        $sort_array = explode(',', rtrim($_POST['sort_string'], ','));

        $model_store_decoration = Model('store_decoration');

        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];

        $sort = 1;
        foreach ($sort_array as $value) {
            $condition['block_id'] = $value;
            $model_store_decoration->editStoreDecorationBlock(array('block_sort' => $sort), $condition);
            $sort = $sort + 1;
        }

        $data = array();
        $data['message'] = '保存成功';
        echo json_encode($data);die;
    }

    /**
     * 获取页面
     */
    private function _get_block_html($content, $module_type) {
        ob_start();
        Tpl::output('block_content', $content);
        Tpl::showpage('store_decoration_module.' . $module_type, 'null_layout');
        $temp = ob_get_contents();
        ob_end_clean();
        return $temp;
    }

    /**
     * 商品搜索
     */
    public function goods_searchOp() {
        $model_goods = Model('goods');
        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        $condition['goods_name'] = array('like', '%'.$_GET['keyword'].'%');
        $goods_list = $model_goods->getGoodsOnlineList($condition, '*', 10);

        Tpl::output('goods_list', $goods_list);
        Tpl::output('show_page', $model_goods->showpage());
        Tpl::showpage('store_decoration_module.goods', 'null_layout');
    }
    /**
     * 商品列表
     */
    public function goods_listOp() {
        $model_goods = Model('goods');

        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        $condition['goods_name'] = array('like', '%' . $_GET['keyword'] . '%');
        $goods_list = $model_goods->getGoodsOnlineList($condition, 'goods_id,goods_name,goods_promotion_price,goods_image', 10);
        Tpl::output('goods_list', $goods_list);
        Tpl::output('show_page', $model_goods->showpage());
        Tpl::showpage('mb_store_special_widget.goods', 'null_layout');
    }
    /**
     * 文章列表
     */
    public function gg_listOp() {
        $condition = array();

        $condition['article_publisher_id'] = $_SESSION['member_id'];
        $condition['article_title'] = array("like",'%'.trim($_GET['keyword']).'%');
        $condition['article_state'] = self::ARTICLE_STATE_PUBLISHED;

        $model_article = Model('cms_article');
        $goods_list = $model_article->getList($condition, 20, 'article_sort asc, article_id desc');
   
        Tpl::output('goods_list', $goods_list);
        
        Tpl::output('show_page', $model_article->showpage());
        Tpl::showpage('mb_store_special_widget.gg', 'null_layout');   
       
       
       
       
//      $model_goods = Model('goods');
//
//      $condition = array();
//      $condition['store_id'] = $_SESSION['store_id'];
//      $condition['goods_name'] = array('like', '%' . $_GET['keyword'] . '%');
//      $goods_list = $model_goods->getGoodsOnlineList($condition, 'goods_id,goods_name,goods_promotion_price,goods_image', 10);
//      Tpl::output('goods_list', $goods_list);
//      Tpl::output('show_page', $model_goods->showpage());
//      Tpl::showpage('mb_store_special_widget.goods', 'null_layout');
    }    
    /**
     * 更新商品模块的商品价格
     */
    private function _update_module_goods_info($decoration_id, $store_id) {
        $model_store_decoration = Model('store_decoration');

        $condition = array();
        $condition['decoration_id'] = $decoration_id;
        $condition['block_module_type'] = 'goods';
        $condition['store_id'] = $store_id;
        $block_list_goods = $model_store_decoration->getStoreDecorationBlockList($condition);

        if(!empty($block_list_goods) && is_array($block_list_goods)) {
            foreach ($block_list_goods as $block) {
                $goods_array = unserialize($block['block_content']);
                foreach ($goods_array as $goods_key => $goods_value) {

                    //商品信息
                    $goods_info = Model('goods')->getGoodsOnlineInfoByID($goods_value['goods_id']);
                    $new_goods_price = $goods_info['goods_price'];

                    //团购
                    if (C('groupbuy_allow')) {
                        $groupbuy_info = Model('groupbuy')->getGroupbuyInfoByGoodsCommonID($goods_info['goods_commonid']);
                        if (!empty($groupbuy_info)) {
                            $new_goods_price = $groupbuy_info['groupbuy_price'];
                        }
                    }

                    //限时折扣
                    if (C('promotion_allow') && empty($groupbuy_info)) {
                        $xianshi_info = Model('p_xianshi_goods')->getXianshiGoodsInfoByGoodsID($goods_value['goods_id']);
                        if (!empty($xianshi_info)) {
                           $new_goods_price = $xianshi_info['xianshi_price'];
                        }
                    }

                    $goods_array[$goods_key]['goods_price'] = $new_goods_price;
                }

                //更新块数据
                $update = array();
                $update['block_content'] = serialize($goods_array);
                $model_store_decoration->editStoreDecorationBlock($update, array('block_id' => $block['block_id']));
            }
        }
    }

    /**
     * 装修预览
     */
    public function decoration_previewOp() {
        $decoration_id = intval($_GET['decoration_id']);

        $model_store_decoration = Model('store_decoration');

        $decoration_info = $model_store_decoration->getStoreDecorationInfoDetail($decoration_id, $_SESSION['store_id']);
        if($decoration_info) {
            $this->_output_decoration_info($decoration_info);
        } else {
            showMessage(L('param_error'), '', 'error');
        }

        //店铺信息
        $model_store = Model('store');
        $store_info = $model_store->getStoreOnlineInfoByID($_SESSION['store_id']);
        Tpl::output('store_info', $store_info);

        Tpl::output('store_theme', 'default');
        Tpl::setLayout('store_layout');
        Tpl::showpage('store_decoration.preview');
    }

    /**
     * 装修静态文件生成
     */
    public function decoration_buildOp() {
        //静态文件路径
        $html_path = BASE_UPLOAD_PATH.DS.ATTACH_STORE.DS.'decoration'.DS.'html'.DS;
        if(!is_dir($html_path)){
            if (!@mkdir($html_path, 0755)){
                $data = array();
                $data['error'] = '页面生成失败';
                echo json_encode($data);die;
            }
        }

        $decoration_id = intval($_GET['decoration_id']);

        //更新商品数据
        $this->_update_module_goods_info($decoration_id, $_SESSION['store_id']);

        $model_store_decoration = Model('store_decoration');

        $decoration_info = $model_store_decoration->getStoreDecorationInfoDetail($decoration_id, $_SESSION['store_id']);
        if($decoration_info) {
            $this->_output_decoration_info($decoration_info);
        } else {
            showMessage(L('param_error'), '', 'error');
        }

        $file_name = md5($_SESSION['store_id']);

        ob_start();
        Tpl::showpage('store_decoration.preview', 'null_layout');
        $result = file_put_contents($html_path . $file_name . '.html', ob_get_clean());
        if($result) {
            $data['message'] = '页面生成成功';
        } else {
            $data['error'] = '页面生成失败';
        }
        echo json_encode($data);die;
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
            1=>array('menu_key'=>'decoration_setting','menu_name'=>'手机店铺装修','menu_url'=>urlShop('mb_store_decoration', 'decoration_setting')),
            2=>array('menu_key'=>'decoration_album','menu_name'=>'手机店铺首页导航设置','menu_url'=>urlShop('mb_store_decoration', 'decoration_album')),
            3=>array('menu_key'=>'decoration_menu','menu_name'=>'手机店铺底部菜单设置','menu_url'=>urlShop('mb_store_decoration', 'decoration_menu')),//menu
        );
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }

}
