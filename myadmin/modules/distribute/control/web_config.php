<?php
/**
 * 分销-首页配置
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
class web_configControl extends SystemControl{
    function __construct()
    {
        parent::__construct();
        Language::read('web_config');
    }

    public function indexOp(){
        $this->focus_editOp();
    }

    /**
     * 头部切换图设置
     */
    public function web_configOp() {
        $model_web_config = Model('dis_web_config');
        $web_id = '1';
        $code_list = $model_web_config->getCodeList(array('web_id'=> $web_id));
        if(is_array($code_list) && !empty($code_list)) {
            foreach ($code_list as $key => $val) {//将变量输出到页面
                $var_name = $val['var_name'];
                $code_info = $val['code_info'];
                $code_type = $val['code_type'];
                $val['code_info'] = $model_web_config->get_array($code_info,$code_type);
                Tpl::output('code_'.$var_name,$val);
            }
        }
        $screen_adv_list = $model_web_config->getAdvList("screen");//焦点大图广告数据
        Tpl::output('screen_adv_list',$screen_adv_list);
        $focus_adv_list = $model_web_config->getAdvList("focus");//三张联动区广告数据
        Tpl::output('focus_adv_list',$focus_adv_list);

        Tpl::showpage('distri_web_config.edit');
    }

    /**
     * 右侧广告图图设置
     */
    public function adv_listOp() {
        $model_web_config = Model('dis_web_config');
        $web_id = '2';
        $code_list = $model_web_config->getCodeList(array('web_id'=> $web_id));
        if(is_array($code_list) && !empty($code_list)) {
            foreach ($code_list as $key => $val) {//将变量输出到页面
                $var_name = $val['var_name'];
                $code_info = $val['code_info'];
                $code_type = $val['code_type'];
                $val['code_info'] = $model_web_config->get_array($code_info,$code_type);
                Tpl::output('code_'.$var_name,$val);
            }
        }

        Tpl::showpage('distri_web_adv.edit');
    }

    /**
     * 联动焦点图设置
     */
    public function focus_editOp() {
        $model_web_config = Model('dis_web_config');
        $web_id = '101';
        $code_list = $model_web_config->getCodeList(array('web_id'=> $web_id));
        if(is_array($code_list) && !empty($code_list)) {
            foreach ($code_list as $key => $val) {//将变量输出到页面
                $var_name = $val['var_name'];
                $code_info = $val['code_info'];
                $code_type = $val['code_type'];
                $val['code_info'] = $model_web_config->get_array($code_info,$code_type);
                Tpl::output('code_'.$var_name,$val);
            }
        }

        Tpl::showpage('distri_web_focus.edit');
    }

    /**
     * 更新html内容
     */
    public function html_updateOp() {
        $model_web_config = Model('dis_web_config');
        $web_id = intval($_GET["web_id"]);
        $web_list = $model_web_config->getWebList(array('web_id'=> $web_id));
        $web_array = $web_list[0];
        if(!empty($web_array) && is_array($web_array)) {
            $model_web_config->updateWebHtml($web_id,$web_array);
            showMessage(Language::get('nc_common_op_succ'));
        } else {
            showMessage(Language::get('nc_common_op_fail'));
        }
    }

    /**
     * 保存焦点区切换大图
     */
    public function screen_picOp() {
        $code_id = intval($_POST['code_id']);
        $web_id = intval($_POST['web_id']);
        $model_web_config = Model('dis_web_config');
        $code = $model_web_config->getCodeRow($code_id,$web_id);
        if (!empty($code)) {
            $code_type = $code['code_type'];
            $var_name = $code['var_name'];
            $code_info = $_POST[$var_name];

            $key = intval($_POST['key']);
            $ap_pic_id = intval($_POST['ap_pic_id']);
            if ($ap_pic_id > 0 && $ap_pic_id == $key) {
                // $ap_color = $_POST['ap_color'];
                // $code_info[$ap_pic_id]['color'] = $ap_color;
                Tpl::output('ap_pic_id',$ap_pic_id);
                // Tpl::output('ap_color',$ap_color);
            }
            $pic_id = intval($_POST['screen_id']);
            if ($pic_id > 0 && $pic_id == $key) {
                $var_name = "screen_pic";
                $pic_info = $_POST[$var_name];
                $pic_info['pic_id'] = $pic_id;
                if (!empty($code_info[$pic_id]['pic_img'])) {//原图片
                    $pic_info['pic_img'] = $code_info[$pic_id]['pic_img'];
                }
                $file_name = 'dis_web-'.$web_id.'-'.$code_id.'-'.$pic_id;
                $pic_name = $this->_upload_pic($file_name);//上传图片
                if (!empty($pic_name)) {
                    $pic_info['pic_img'] = $pic_name;
                }

                $code_info[$pic_id] = $pic_info;
                Tpl::output('pic',$pic_info);
            }
            $code_info = $model_web_config->get_str($code_info,$code_type);
            $model_web_config->updateCode(array('code_id'=> $code_id),array('code_info'=> $code_info));

            Tpl::showpage('distri_web_upload_screen','null_layout');
        }
    }

    /**
     * 保存右侧广告图
     */
    public function adv_picOp() {
        $code_id = intval($_POST['code_id']);
        $web_id = intval($_POST['web_id']);
        $model_web_config = Model('dis_web_config');
        $code = $model_web_config->getCodeRow($code_id,$web_id);
        if (!empty($code)) {
            $code_type = $code['code_type'];
            $var_name = $code['var_name'];
            $code_info = $_POST[$var_name];

            $key = intval($_POST['key']);
            $pic_id = intval($_POST['adv_id']);
            if ($pic_id > 0 && $pic_id == $key) {
                $var_name = "adv_pic";
                $pic_info = $_POST[$var_name];
                $pic_info['pic_id'] = $pic_id;
                if (!empty($code_info[$pic_id]['pic_img'])) {//原图片
                    $pic_info['pic_img'] = $code_info[$pic_id]['pic_img'];
                }
                $file_name = 'dis_web-'.$web_id.'-'.$code_id.'-'.$pic_id;
                $pic_name = $this->_upload_pic($file_name);//上传图片
                if (!empty($pic_name)) {
                    $pic_info['pic_img'] = $pic_name;
                }

                $code_info[$pic_id] = $pic_info;
                Tpl::output('pic',$pic_info);
            }
            $code_info = $model_web_config->get_str($code_info,$code_type);
            $model_web_config->updateCode(array('code_id'=> $code_id),array('code_info'=> $code_info));

            Tpl::showpage('distri_web_upload_adv','null_layout');
        }
    }

    /**
     * 保存焦点区切换小图 3张广告图
     */
    public function focus_picOp() {
        $code_id = intval($_POST['code_id']);
        $web_id = intval($_POST['web_id']);
        $model_web_config = Model('dis_web_config');
        $code = $model_web_config->getCodeRow($code_id,$web_id);
        if (!empty($code)) {
            $code_type = $code['code_type'];
            $var_name = $code['var_name'];
            $code_info = $_POST[$var_name];

            $key = intval($_POST['key']);
            $slide_id = intval($_POST['slide_id']);
            $pic_id = intval($_POST['pic_id']);
            if ($pic_id > 0 && $slide_id == $key) {
                $var_name = "focus_pic";
                $pic_info = $_POST[$var_name];
                $pic_info['pic_id'] = $pic_id;
                if (!empty($code_info[$slide_id]['pic_list'][$pic_id]['pic_img'])) {//原图片
                    $pic_info['pic_img'] = $code_info[$slide_id]['pic_list'][$pic_id]['pic_img'];
                }
                $file_name = 'dis_web-'.$web_id.'-'.$code_id.'-'.$slide_id.'-'.$pic_id;
                $pic_name = $this->_upload_pic($file_name);//上传图片
                if (!empty($pic_name)) {
                    $pic_info['pic_img'] = $pic_name;
                }

                $code_info[$slide_id]['pic_list'][$pic_id] = $pic_info;
                Tpl::output('pic',$pic_info);
            }
            //更新组
            if($slide_id > 0 && $slide_id == $key){
                $var_name = "pic_group";
                $group_info = $_POST[$var_name];
                if (!empty($code_info[$slide_id]['group_list']['group_image'])) {//组图片
                    $group_info['group_image'] = $code_info[$slide_id]['group_list']['group_image'];
                }
                $group_file_name = 'dis_web-'.'group-'.$web_id.'-'.$code_id.'-'.$slide_id;
                //上传组logo
                $group_logo_name = $this->_upload_group_logo($group_file_name);
                if (!empty($group_logo_name)) {
                    $group_info['group_image'] = $group_logo_name;
                }

                $code_info[$slide_id]['group_list'] = $group_info;
                Tpl::output('group',$group_info);
            }
            $code_info = $model_web_config->get_str($code_info,$code_type);
            $model_web_config->updateCode(array('code_id'=> $code_id),array('code_info'=> $code_info));

            Tpl::showpage('distri_web_upload_focus','null_layout');
        }
    }

    /**
     * 保存焦点区切换小图 2张广告图
     */
    public function two_focus_picOp() {
        $code_id = intval($_POST['code_id']);
        $web_id = intval($_POST['web_id']);
        $model_web_config = Model('dis_web_config');
        $code = $model_web_config->getCodeRow($code_id,$web_id);
        if (!empty($code)) {
            $code_type = $code['code_type'];
            $var_name = $code['var_name'];
            $code_info = $_POST[$var_name];

            $key = intval($_POST['key']);
            $slide_id = intval($_POST['slide_id']);
            $pic_id = intval($_POST['pic_id']);
            if ($pic_id > 0 && $slide_id == $key) {
                $var_name = "focus_pic";
                $pic_info = $_POST[$var_name];
                $pic_info['pic_id'] = $pic_id;
                if (!empty($code_info[$slide_id]['pic_list'][$pic_id]['pic_img'])) {//原图片
                    $pic_info['pic_img'] = $code_info[$slide_id]['pic_list'][$pic_id]['pic_img'];
                }
                $file_name = 'dis_web-'.$web_id.'-'.$code_id.'-'.$slide_id.'-'.$pic_id;
                $pic_name = $this->_upload_pic($file_name);//上传图片
                if (!empty($pic_name)) {
                    $pic_info['pic_img'] = $pic_name;
                }

                $code_info[$slide_id]['pic_list'][$pic_id] = $pic_info;
                Tpl::output('pic',$pic_info);
            }
            //更新组
            if($slide_id > 0 && $slide_id == $key){
                $group_info = $_POST['pic_group'];
                if (!empty($code_info[$slide_id]['group_list']['group_name'])) {//原组图片
                    $group_info['group_name'] = $code_info[$slide_id]['group_list']['group_name'];
                }
                if (!empty($code_info[$slide_id]['group_list']['group_image'])) {//原组图片
                    $group_info['group_image'] = $code_info[$slide_id]['group_list']['group_image'];
                }
                $group_file_name = 'dis_web-'.'group-'.$web_id.'-'.$code_id.'-'.$slide_id;
                //上传组logo
                $group_logo_name = $this->_upload_group_logo($group_file_name);
                if (!empty($group_logo_name)) {
                    $group_info['group_image'] = $group_logo_name;
                }

                $code_info[$slide_id]['group_list'] = $group_info;
                Tpl::output('group',$group_info);
            }
            $code_info = $model_web_config->get_str($code_info,$code_type);
            $model_web_config->updateCode(array('code_id'=> $code_id),array('code_info'=> $code_info));

            Tpl::showpage('distri_web_upload_two_focus','null_layout');
        }
    }

    /**
     * 保存焦点区切换小图 8张广告图
     */
    public function eight_focus_picOp() {
        $code_id = intval($_POST['code_id']);
        $web_id = intval($_POST['web_id']);
        $model_web_config = Model('dis_web_config');
        $code = $model_web_config->getCodeRow($code_id,$web_id);
        if (!empty($code)) {
            $code_type = $code['code_type'];
            $var_name = $code['var_name'];
            $code_info = $_POST[$var_name];
            $key = intval($_POST['key']);
            $slide_id = intval($_POST['slide_id']);
            $pic_id = intval($_POST['pic_id']);
            if ($pic_id > 0 && $slide_id == $key) {
                $var_name = "focus_pic";
                $pic_info = $_POST[$var_name];
                $pic_info['pic_id'] = $pic_id;
                if (!empty($code_info[$slide_id]['pic_list'][$pic_id]['pic_img'])) {//原图片
                    $pic_info['pic_img'] = $code_info[$slide_id]['pic_list'][$pic_id]['pic_img'];
                }
                $file_name = 'dis_web-'.$web_id.'-'.$code_id.'-'.$slide_id.'-'.$pic_id;
                $pic_name = $this->_upload_pic($file_name);//上传图片
                if (!empty($pic_name)) {
                    $pic_info['pic_img'] = $pic_name;
                }

                $code_info[$slide_id]['pic_list'][$pic_id] = $pic_info;
                Tpl::output('pic',$pic_info);
            }
            //更新组
            if($slide_id > 0 && $slide_id == $key){
                $group_info = $_POST['pic_group'];
                if (!empty($code_info[$slide_id]['group_list']['group_name'])) {//原组图片
                    $group_info['group_name'] = $code_info[$slide_id]['group_list']['group_name'];
                }
                if (!empty($code_info[$slide_id]['group_list']['group_image'])) {//原组图片
                    $group_info['group_image'] = $code_info[$slide_id]['group_list']['group_image'];
                }
                $group_file_name = 'dis_web-'.'group-'.$web_id.'-'.$code_id.'-'.$slide_id;
                //上传组logo
                $group_logo_name = $this->_upload_group_logo($group_file_name);
                if (!empty($group_logo_name)) {
                    $group_info['group_image'] = $group_logo_name;
                }

                $code_info[$slide_id]['group_list'] = $group_info;
                Tpl::output('group',$group_info);
            }
            $code_info = $model_web_config->get_str($code_info,$code_type);
            $model_web_config->updateCode(array('code_id'=> $code_id),array('code_info'=> $code_info));

            Tpl::showpage('distri_web_upload_eight_focus','null_layout');
        }
    }

    /**
     * 上传图片
     */
    private function _upload_pic($file_name) {
        $pic_name = '';
        if (!empty($file_name)) {
            if (!empty($_FILES['pic']['name'])) {//上传图片
                $upload = new UploadFile();
                $filename_tmparr = explode('.', $_FILES['pic']['name']);
                $ext = end($filename_tmparr);
                $upload->set('default_dir',ATTACH_EDITOR);
                $upload->set('file_name',$file_name.".".$ext);
                $result = $upload->upfile('pic');
                if ($result) {
                    $pic_name = ATTACH_EDITOR."/".$upload->file_name.'?'.mt_rand(100,999);//加随机数防止浏览器缓存图片
                }
            }
        }
        return $pic_name;
    }

    /**
     * 上传组logo
     */
    private function _upload_group_logo($file_name) {
        $group_logo_name = '';
        if (!empty($file_name)) {
            if (!empty($_FILES['group_image']['name'])) {//上传图片
                $upload = new UploadFile();
                $filename_tmparr = explode('.', $_FILES['group_image']['name']);
                $ext = end($filename_tmparr);
                $upload->set('default_dir',ATTACH_EDITOR);
                $upload->set('file_name',$file_name.".".$ext);
                $result = $upload->upfile('group_image');
                if ($result) {
                    $group_logo_name = ATTACH_EDITOR."/".$upload->file_name.'?'.mt_rand(100,999);//加随机数防止浏览器缓存图片
                }
            }
        }
        return $group_logo_name;
    }

}