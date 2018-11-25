<?php
/**
 * 点播管理
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
class mb_demandControl extends SystemControl{

    public function __construct(){
        parent::__construct();
    }

    public function indexOp() {
        $this->listOp();
    }

    /**
     * 点播列表
     */
    public function listOp(){
        Tpl::showpage('mb_demand.index');

    }

    public function get_xmlOp(){
        $model_video = Model('mb_video');

        $condition = array();
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        $condition['video_identity'] = 'demand';
        $condition['video_identity_type'] = 2;
        $page = $_POST['rp'];
        $demand_list = $model_video->getMbVideoList($condition,$page);

        $data = array();
        $data['now_page'] = $model_video->shownowpage();
        $data['total_num'] = $model_video->gettotalnum();
        foreach($demand_list as $k => $v){
            $param = array();

            $operation = "<a class='btn red' href='javascript:void(0);' onclick=\"fg_del(".$v['video_id'].")\"><i class='fa fa-trash-o'></i>删除</a>";
            

            $operation .= "<span class='btn'><em><i class='fa fa-cog'></i>设置 <i class='arrow'></i></em><ul>";
            $operation .= "<li><a href='index.php?con=mb_demand&fun=demand_edit&video_id=" . $v['video_id'] . "'>编辑点播</a></li>";
            if($v['recommend_goods'] == ''){
                $operation .= "<li><a href='index.php?con=mb_demand&fun=demand_recommend_goods&video_id=".$v['video_id']."&store_id=".$v['store_id']."&type=add'>推荐商品</a></li>";
            }else{
                $operation .= "<li><a href='index.php?con=mb_demand&fun=demand_recommend_goods&video_id=" . $v['video_id'] . "&store_id=".$v['store_id']."&type=edit'>推荐商品</a></li>";
            }
            $operation .= "</ul>";

            

            $param['operation'] = $operation;
            
            $cate_info = Model('video_category')->getVideoCategoryInfo(array( 'cate_id' => $v['cate_id']));
            $param['cate_name'] = $cate_info['cate_name'];
            $param['store_id'] = $v['store_id'];
            $store_info = Model('store')->getStoreInfoByID($v['store_id']);
            $param['store_name'] = $store_info['store_name'];
            $param['store_avatar'] = "<a href='javascript:void(0);' class='pic-thumb-tip' onMouseOut='toolTip()' onMouseOver='toolTip(\"<img src=".getStoreLogo($v['store_avatar']).">\")'><i class='fa fa-picture-o'></i></a>";
            $param['store_label'] = "<a href='javascript:void(0);' class='pic-thumb-tip' onMouseOut='toolTip()' onMouseOver='toolTip(\"<img src=".getStoreLogo($v['store_label'], 'store_logo').">\")'><i class='fa fa-picture-o'></i></a>";

            $data['list'][$v['video_id']] = $param;
        }

        echo Tpl::flexigridXML($data);exit();
    }

    /**
     * 点播添加
     */
    public function demand_addOp(){
        /**
         * 视频分类
         */
        $cate_where = array();
        $video_cate_list = Model('video_category')->getVideoCategoryList($cate_where);
        Tpl::output('video_cate_list', $video_cate_list);
        /**
         * 点播
         */
        if (chksubmit()){
            //店铺详细信息
            $store_info = Model('store')->getStoreInfoByID($_POST['store']);

            if (!empty($_FILES['promote_image']['name'])){
                $upload = new UploadFile();
                $upload->set('default_dir',ATTACH_MOBILE.'/demand/');
                $result = $upload->upfile('promote_image');
                if ($result){
                    $_POST['promote_image'] = $upload->file_name;
                }else {
                    showMessage($upload->error,'','','error');
                }
            }

            if (!empty($_FILES['promote_video']['name'])){
                $upload = new UploadVideoFile();
                $upload->set('default_dir',ATTACH_MOBILE.'/demand/');
                $upload->set('max_size', 5120);
                $result = $upload->upfile('promote_video',true);
                if ($result){
                    $_POST['promote_video'] = $upload->file_name;
                }else {
                    echo $upload->error;exit;
                    showMessage($upload->error,'','','error');
                }
            }

            if (!empty($_FILES['demand_video']['name'])){
                $upload = new UploadVideoFile();
                $upload->set('default_dir',ATTACH_MOBILE.'/demand/');
                $upload->set('max_size', 20480);
                $result = $upload->upfile('demand_video',true);
                if ($result){
                    $_POST['demand_video'] = $upload->file_name;
                }else {
                    showMessage($upload->error,'','','error');
                }
            }

            //增加点播
            $insert_array = array();                
            $insert_array['store_id']        = $_POST['store'];
            $insert_array['cate_id']        = $_POST['video_category'];
            $insert_array['add_time']        = time();
            $insert_array['promote_video']        = ($_POST['promote'] == '1') ? '' : $_POST['promote_video'];
            $insert_array['promote_text']        = ($_POST['promote'] == '1') ? '' : $_POST['promote_text'];
            $insert_array['demand_video']        = ($_POST['promote'] == '1') ? '' : $_POST['demand_video'];
            $insert_array['promote_image']    =  ($_POST['promote'] == '0') ? '' : $_POST['promote_image'];
            $insert_array['video_identity']     =   'demand';
            $insert_array['video_identity_type'] = 2;
            $result = Model('mb_video')->addMbVideo($insert_array);
            if ($result){
                $Url = 'index.php?con=mb_demand&fun=index';
                $this->log('新增点播信息，店铺名称:"'.$store_info['store_name'].'"',1);
                showDialog('新增点播保存成功', $Url, 'succ', '', 3);
            }else {
                $this->log('新增点播信息，店铺名称:"'.$store_info['store_name'].'"',0);
                showMessage('新增点播保存失败');
            }
                
        }

        Tpl::showpage('mb_demand.add');
    }

    /**
     * 编辑
     */
    public function demand_editOp(){
        /**
         * 点播信息
         */
        $demand_array = Model('mb_video')->getMbVideoInfoByID(intval($_GET['video_id']));
        if (empty($demand_array)){
            showMessage('参数非法');
        }

        if (chksubmit()){

            if (!empty($_FILES['promote_image']['name'])){
                $upload = new UploadFile();
                $upload->set('default_dir',ATTACH_MOBILE.'/demand/');
                $result = $upload->upfile('promote_image');
                if ($result){
                    $_POST['promote_image'] = $upload->file_name;
                }else {
                    showMessage($upload->error,'','','error');
                }
            }else{
                $_POST['promote_image'] = $demand_array['promote_image'];
            }

            if (!empty($_FILES['promote_video']['name'])){
                $upload = new UploadVideoFile();
                $upload->set('default_dir',ATTACH_MOBILE.'/demand/');
                $upload->set('max_size', 5120);
                $result = $upload->upfile('promote_video',true);
                if ($result){
                    $_POST['promote_video'] = $upload->file_name;
                }else {
                    showMessage($upload->error,'','','error');
                }
            }else{
                $_POST['promote_video'] = $demand_array['promote_video'];
            }

            if (!empty($_FILES['demand_video']['name'])){
                $upload = new UploadVideoFile();
                $upload->set('default_dir',ATTACH_MOBILE.'/demand/');
                $upload->set('max_size', 20480);
                $result = $upload->upfile('demand_video',true);
                if ($result){
                    $_POST['demand_video'] = $upload->file_name;
                }else {
                    showMessage($upload->error,'','','error');
                }
            }else{
                $_POST['demand_video'] = $demand_array['demand_video'];
            }

            // 更新点播信息
            $update_array = array();
            $update_array['store_id']        = $_POST['store'];
            $update_array['cate_id']        = $_POST['video_category'];
            $update_array['promote_video']        = ($_POST['promote'] == '1') ? '' : $_POST['promote_video'];
            $update_array['promote_text']        = ($_POST['promote'] == '1') ? '' : $_POST['promote_text'];
            $update_array['demand_video']        = ($_POST['promote'] == '1') ? '' : $_POST['demand_video'];
            $update_array['promote_image']    =  ($_POST['promote'] == '0') ? '' : $_POST['promote_image'];
            $update_array['video_identity']          =  'demand';
            $update_array['video_identity_type'] = 2;
            $result = Model('mb_video')->editMbVideo($update_array, intval($_GET['video_id']));
            if (!$result){
                $this->log('编辑点播信息',0);
                showMessage('编辑点播保存失败');
            }

            /**
             * 删除图片
             */
            if (!empty($_POST['textfield2']) && !empty($demand_array['promote_image'])){
                @unlink(BASE_ROOT_PATH.DS.DIR_UPLOAD.DS.ATTACH_MOBILE.'/demand/'.$demand_array['promote_image']);
            }

            /**
             * 删除视频
             */
            if (!empty($_POST['textfield1']) && !empty($demand_array['promote_video'])){
                @unlink(BASE_ROOT_PATH.DS.DIR_UPLOAD.DS.ATTACH_MOBILE.'/demand/'.$demand_array['promote_video']);
            }

            /**
             * 删除视频
             */
            if (!empty($_POST['textfield3']) && !empty($demand_array['demand_video'])){
                @unlink(BASE_ROOT_PATH.DS.DIR_UPLOAD.DS.ATTACH_MOBILE.'/demand/'.$demand_array['demand_video']);
            }

            $this->log('编辑点播信息',1);
            $Url = 'index.php?con=mb_demand&fun=index';
            showDialog('编辑点播保存成功', $Url, 'succ', '', 3);
        }

        /**
         * 店铺信息
         */
        $store_info = Model('store')->getStoreInfoByID($demand_array['store_id']);
        Tpl::output('store_info', $store_info);

        /**
         * 视频分类
         */
        $video_cate_list = Model('video_category')->getVideoCategoryList(array());
        Tpl::output('video_cate_list', $video_cate_list);


        Tpl::output('demand_array',$demand_array);
        Tpl::showpage('mb_demand.edit');
    }


    /**
     * 删除点播
     */
    public function demand_delOp(){
        if ($_GET['id'] != ''){
            //删除点播
            Model('mb_video')->delMbVideoByID($_GET['id']);
            $this->log('删除点播' . '[ID:' . $_GET['id'] . ']',1);
            exit(json_encode(array('state'=>true,'msg'=>'删除成功')));
        }else {
            exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
        }
    }

    /**
     * 推荐商品
     */
    public function demand_recommend_goodsOp(){
        if(!$_GET['store_id']){
            showMessage('先推荐店铺，才可以推荐商品');
        }

        if(!$_GET['video_id']){
            showMessage('参数错误');
        }

        $video_id = $_GET['video_id'];

        if (chksubmit()){

            $update_array = array();
            $update_array['recommend_goods']        = $this->_getRecommendGoods($_POST['goods']);
            $result = Model('mb_video')->editMbVideo($update_array,$video_id);
            if($result){
                $this->log('点播推荐商品',1);
                $url = 'index.php?con=mb_demand&fun=index';
                showMessage('推荐商品保存成功',$url);
            }else{
                $this->log('点播推荐商品',0);
                showMessage('推荐商品保存失败');
            }
        }

        $demand_array = Model('mb_video')->getMbVideoInfoByID($video_id);
        if(!$demand_array){
            showMessage('参数非法');
        }

        if($_GET['type'] == 'edit'){
            /**
             * 推荐商品编辑
             */
            if (!empty($demand_array['recommend_goods'])) {
                $recommend_goods = unserialize($demand_array['recommend_goods']);
                foreach($recommend_goods as $goods_commonid => $info){
                    $recommend_arr['goods_commonid'][] = $goods_commonid;
                }
                $goodscommonid_array = $recommend_arr['goods_commonid'];
                $goods_common_list = Model('goods')->getGoodsCommonList(array('goods_commonid' => array('in', $goodscommonid_array)), 'goods_commonid,goods_price,goods_image,goods_name');
                foreach($goods_common_list as $k => $v){
                    $goods_common_list[$k]['recommend_appoint'] = 1;
                }
                $recommend_goods_common_list = array_under_reset($goods_common_list, 'goods_commonid');
            }
        }elseif($_GET['type'] == 'add'){
            $recommend_goods_common_list = array();
        }
        Tpl::output('recommend_goods_common_list', $recommend_goods_common_list);


        Tpl::output('store_id',$_GET['store_id']);
        Tpl::showpage('mb_demand.recommend_goods');
    }

    /**
     * 添加推荐商品
     */
    public function recommend_add_goodsOp() {

        if(!$_GET['store_id']){
            showMessage('先推荐店铺，才可以推荐商品');
        }

        // where条件
        $where = array ();
        $where['store_id'] = $_GET['store_id'];
        if($_GET['keyword'] != '') {
            $where[$_GET['qtype']] = array('like', '%' . $_GET['keyword'] . '%');
        }

        $goods_common_list = Model('goods')->getGeneralGoodsCommonList($where, '*', 8);
        $storage_array = Model('goods')->calculateStorage($goods_common_list);
        foreach($goods_common_list as $k => $v){
            $goods_common_list[$k]['goods_storage'] = $storage_array[$v['goods_commonid']]['sum'];
        }
        Tpl::output('show_page', Model('goods')->showpage(2));
        Tpl::output('goods_common_list', $goods_common_list);

        Tpl::showpage('mb_demand.recommend_add_goods', 'null_layout');
    }

    /**
     * 选择推荐店铺
     */
    public function select_recommend_storeOp() {
        $condition = array();
        if ($_GET['store_name'] != '') {
            $condition['store_name'] = array('like', '%' . $_GET['store_name'] . '%');
        }
        if ($_GET['member_name'] != '') {
            $condition['member_name'] = array('like', '%' . $_GET['member_name'] . '%');
        }
        if ($_GET['seller_name'] != '') {
            $condition['seller_name'] = array('like', '%' . $_GET['seller_name'] . '%');
        }
        if ($_GET['search_keyword'] != '') {
            $condition[$_GET['qtype']] = array('like', '%' . $_GET['search_keyword'] . '%');
        }
        $store_list = Model('store')->getStoreOnlineList($condition, 10);

        Tpl::output('store_list', $store_list);
        Tpl::output('show_page', Model('store')->showpage());
        Tpl::showpage('mb_demand.recommend_store', 'null_layout');
    }

    /**
     * 序列化保存手机端商品推荐数据
     */
    private function _getRecommendGoods($goods){
        $list = array();
        if(!empty($goods)){
            foreach($goods as $k => $v){
                $list[$v['gid']]['goods_commonid'] = $v['gid'];
                $list[$v['gid']]['goods_name'] = $v['gname'];
                $list[$v['gid']]['goods_price'] = $v['gprice'];
                $list[$v['gid']]['goods_image'] = $v['image'];
            }
            return serialize($list);
        }else{
            return '';
        }
    }

}
