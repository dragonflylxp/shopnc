<?php
/**
 * 商家商品
 *
 */
use shopec\Tpl;
defined('Inshopec') or exit('Access Invalid!');
class seller_goodsControl extends mobileSellerControl
{
    public function __construct()
    {
        parent::__construct();
        
    }
    public function indexOp()
    {
        Tpl::output('web_seo', C('site_name') . ' - ' . '商品列表');
        Tpl::showpage('seller_goods');
    }
    /**
     * ajax获取商品分类的子级数据
     */
    public function ajax_goods_classOp()
    {
        $gc_id = intval($_GET['gc_id']);
        $deep = intval($_GET['deep']);
        if ($gc_id < 0 || $deep <= 0 || $deep >= 4) {
            exit;
        }
        if($gc_id > 0){
            $deep = $deep+1;
        }
        $model_goodsclass = Model('goods_class');
        $list = $model_goodsclass->getGoodsClass($_SESSION['store_id'], $gc_id, $deep, $_SESSION['seller_group_id'], $_SESSION['seller_gc_limits']);
        if (empty($list)) {
            exit;
        }
        /**
         * 转码
         */
        if (strtoupper(CHARSET) == 'GBK') {
            $list = Language::getUTF8($list);
        }
        output_data(array('class_list' => $list));
    }
   
    public function editOp()
    {
        $common_id = $_GET['goods_id'];
        if ($common_id <= 0) {
            showMessage('参数错误', '', 'html', 'error');
        }
        $model_goods = Model('goods');
        $goodscommon_info = $model_goods->getGoodsCommonInfoByID($common_id);
        if (empty($goodscommon_info) || $goodscommon_info['store_id'] != $_SESSION['store_id'] || $goodscommon_info['goods_lock'] == 1) {
            showMessage('参数错误2', '', 'html', 'error');
        }
        //权限组对应分类权限判断
        if (!$_SESSION['seller_gc_limits'] && $_SESSION['seller_group_id']) {
            $gc_list = Model('seller_group_bclass')->getSellerGroupBclasList(array('group_id' => $_SESSION['seller_group_id']), '', '', 'gc_id', 'gc_id');
            if (!in_array($goodscommon_info['gc_id'], array_keys($gc_list))) {
                showMessage('您所在的组无权操作该分类下的商品', '', 'html', 'error');
            }
        }
        $where = array('goods_commonid' => $common_id, 'store_id' => $_SESSION['store_id']);
        $goodscommon_info['g_storage'] = $model_goods->getGoodsSum($where, 'goods_storage');
        // $goodscommon_info['spec_name'] = unserialize($goodscommon_info['spec_name']);
        // $goodscommon_info['goods_custom'] = unserialize($goodscommon_info['goods_custom']);
        if ($goodscommon_info['mobile_body'] != '') {
            $goodscommon_info['mb_body'] = unserialize($goodscommon_info['mobile_body']);
            if (is_array($goodscommon_info['mb_body'])) {
                $mobile_body = '[';
                foreach ($goodscommon_info['mb_body'] as $val) {
                    $mobile_body .= '{"type":"' . $val['type'] . '","value":"' . $val['value'] . '"},';
                }
                $mobile_body = rtrim($mobile_body, ',') . ']';
            }
            $goodscommon_info['mobile_body'] = $mobile_body;
        }
        //商品图片
       $image_list = $model_goods->getGoodsImageList(array('goods_commonid' => $common_id));
       $image_list = array_under_reset($image_list, 'color_id', 2);
       $img_array = $model_goods->getGoodsList(array('goods_commonid' => $common_id), 'color_id,min(goods_image) as goods_image', 'color_id');
       
        // 整理，更具id查询颜色名称
        if (!empty($img_array)) {
            foreach ($img_array as $val) {
                if (isset($image_list[$val['color_id']])) {
                    $image_array[$val['color_id']] = $image_list[$val['color_id']];
                } else {
                    $image_array[$val['color_id']][0]['goods_image'] = $val['goods_image'];
                    $image_array[$val['color_id']][0]['is_default'] = 1;
                }
                $colorid_array[] = $val['color_id'];
            }
        }
        
          //读取语言包
        Language::read('transport');

        $model_transport    = Model('transport');
        $list = $model_transport->getTransportList(array('store_id'=>$_SESSION['store_id']),4);
        if (!empty($list) && is_array($list)){
            $transport = array();
            foreach ($list as $v) {
                if (!array_key_exists($v['id'],$transport)){
                    $transport[$v['id']] = $v['title'];
                }
            }
            $extend = $model_transport->getExtendList(array('transport_id'=>array('in',array_keys($transport))));
            // 整理
            if (!empty($extend)) {
                $tmp_extend = array();
                foreach ($extend as $val) {
                    $tmp_extend[$val['transport_id']]['data'][] = $val;
                    if ($val['is_default'] == 1) {
                        $tmp_extend[$val['transport_id']]['price'] = $val['sprice'];
                    }
                }
                $extend = $tmp_extend;
            }
        }

     
        /**
         * 页面输出
         */
        Tpl::output('extend',$extend);
        Tpl::output('list',$list);
        // echo "<pre>";
        // print_r($goodscommon_info);
        // echo "</pre>";
        Tpl::output('img', $image_array[0]);
        Tpl::output('goods', $goodscommon_info);
        Tpl::output('web_seo', C('site_name') . ' - ' . '商品编辑');
        Tpl::showpage('seller_goods_edit');
    }
    /*
    *处理添加商品add
    */
    public function addOp(){
        
          //读取语言包
        Language::read('transport');

        $model_transport    = Model('transport');
        $list = $model_transport->getTransportList(array('store_id'=>$_SESSION['store_id']),4);
        if (!empty($list) && is_array($list)){
            $transport = array();
            foreach ($list as $v) {
                if (!array_key_exists($v['id'],$transport)){
                    $transport[$v['id']] = $v['title'];
                }
            }
            $extend = $model_transport->getExtendList(array('transport_id'=>array('in',array_keys($transport))));
            // 整理
            if (!empty($extend)) {
                $tmp_extend = array();
                foreach ($extend as $val) {
                    $tmp_extend[$val['transport_id']]['data'][] = $val;
                    if ($val['is_default'] == 1) {
                        $tmp_extend[$val['transport_id']]['price'] = $val['sprice'];
                    }
                }
                $extend = $tmp_extend;
            }
        }

     
        /**
         * 页面输出
         */
        Tpl::output('extend',$extend);
        Tpl::output('list',$list);
        Tpl::output('web_seo', C('site_name') . ' - ' . '商品添加');
        Tpl::showpage('seller_goods_add');
    }
    /*
    *处理添加商品goods_add
    */
    public function goods_addOp(){
  

        $_POST['image_path'] = $_POST['img'][0];
        if($_POST['g_state'] == 'on'){
            $_POST['g_state'] = 1;
        }else{
            $_POST['g_state'] = 0;
        }
        unset($_POST['file']);
        $imglist = $_POST['img'];
        unset($_POST['img']);
        $logic_goods = Logic('goods');
                // p($_POST);die;
        $result =  $logic_goods->saveGoods(
            $_POST,
            $_SESSION['store_id'], 
            $_SESSION['store_name'], 
            $this->store_info['store_state'], 
            $_SESSION['seller_id'], 
            $_SESSION['seller_name'],
            $_SESSION['bind_all_gc']
        );

        if(!$result['state']) {
            showMessage(L('error') . $result['msg'],'', 'html', 'error');
        }else{
            $common_id = $result['data'];
            $model_goods = Model('goods');
            // 保存
            $insert_array = array();
            foreach ($imglist as $key => $value) {
                
                    if($value){
                    // 商品默认主图
                    $update_array = array();        // 更新商品主图
                    $update_where = array();
                    $tmp_insert = array();
                    $update_array['goods_image']    = $value;
                    $update_where['goods_commonid'] = $common_id;
                    $update_where['color_id']       = 0;
                    $tmp_insert['is_default']       = 0; 
                    
                        if ($key == 0 ) {
                            $update_array['goods_image']    = $value;
                            $update_where['goods_commonid'] = $common_id;
                            $update_where['color_id']       = 0;
                             $tmp_insert['is_default']       = 1; 
                            // 更新商品主图
                            $model_goods->editGoods($update_array, $update_where);
                        }
                      
            
                        
                        $tmp_insert['goods_commonid']   = $common_id;
                        $tmp_insert['store_id']         = $_SESSION['store_id'];
                        $tmp_insert['color_id']         = 0;
                        $tmp_insert['goods_image']      = $value;
                        $tmp_insert['goods_image_sort'] = 0;
                        $insert_array[] = $tmp_insert;
                   }
            }
            $rs = $model_goods->addGoodsImagesAll($insert_array);
            if ($rs) {
                showMessage('添加成功!', urlMobile('seller_goods'), 'html', 'succ');
            } else {
                showMessage('添加图片时出错,请重试!');
            }
            
        }

       
    }
    /**
     * 出售中的商品列表
     */
    public function goods_listOp()
    {
        $keyword = $_POST['keyword'];
        $goods_type = $_POST['goods_type'];
        $search_type = $_POST['search_type'];
        $search_type = 0;
        $model_goods = Model('goods');
        $condition = array();
        $condition['store_id'] = $this->store_info['store_id'];
        if (trim($keyword) != '') {
            switch ($search_type) {
                case 0:
                    $condition['goods_name'] = array('like', '%' . trim($keyword) . '%');
                    break;
                case 1:
                    $condition['goods_serial'] = array('like', '%' . trim($keyword) . '%');
                    break;
                case 2:
                    $condition['goods_commonid'] = intval($keyword);
                    break;
            }
        }
        $fields = 'goods_commonid,goods_name,goods_price,goods_addtime,goods_jingle,goods_image,goods_state,goods_lock,goods_verify';
        switch ($goods_type) {
            case 'lockup':
                $goods_list = $model_goods->getGoodsCommonLockUpList($condition, $fields, $this->page);
                break;
            case 'offline':
                $goods_list = $model_goods->getGoodsCommonOfflineList($condition, $fields, $this->page);
                break;
            case 'online':
                $goods_list = $model_goods->getGoodsCommonOnlineList($condition, $fields, $this->page);
                break;
            default:
                $goods_list = $model_goods->getGoodsCommonList($condition, $fields, $this->page);
                break;
        }
        // 计算库存
        $storage_array = $model_goods->calculateStorage($goods_list);
        // 整理输出的数据格式
        foreach ($goods_list as $key => $value) {
            $goods_list[$key]['goods_storage_sum'] = $storage_array[$value['goods_commonid']]['sum'];
            $goods_list[$key]['goods_addtime'] = date('Y-m-d h:i:s', $goods_list[$key]['goods_addtime']);
            $goods_list[$key]['goods_name'] = str_cut($goods_list[$key]['goods_name'], 25);
            $goods_list[$key]['goods_jingle'] = str_cut($goods_list[$key]['goods_jingle'], 25);
            $goods_list[$key]['goods_image'] = cthumb($goods_list[$key]['goods_image'], '60');
            $goods_id = Model('goods')->where(array('goods_commonid'=>$value['goods_commonid']))->field('goods_id')->find();
            $goods_list[$key]['goods_id'] = $goods_id['goods_id'];
        }

        $page_count = $model_goods->gettotalpage();
        output_data(array('goods_list' => $goods_list), mobile_page($page_count));
    }
    /**
     * 商品详细信息
     */
    public function goods_infoOp()
    {
        $common_id = $_GET['goods_commonid'];
        $model_goods = Model('goods');
        $goodscommon_info = $model_goods->getGoodsCommonInfoByID($common_id);
        if (empty($goodscommon_info) || $goodscommon_info['store_id'] != $this->store_info['store_id'] || $goodscommon_info['goods_lock'] == 1) {
            output_error('参数错误');
        }
        $where = array('goods_commonid' => $common_id, 'store_id' => $this->store_info['store_id']);
        $goodscommon_info['g_storage'] = $model_goods->getGoodsSum($where, 'goods_storage');
        // $goodscommon_info['spec_name'] = unserialize($goodscommon_info['spec_name']);
        $goodscommon_info['goods_image_url'] = thumb($goodscommon_info);
        $where = array('goods_commonid' => $common_id, 'store_id' => $this->store_info['store_id']);
        // 取得商品规格的输入值
        $goods_array = $model_goods->getGoodsList($where, 'goods_id,goods_marketprice,goods_price,goods_storage,goods_serial,goods_storage_alarm,goods_spec,goods_barcode');
        $sp_value = array();
        $attr_checked = array();
        $spec_checked = array();
        if (is_array($goods_array) && !empty($goods_array)) {
            $model_type = Model('type');
            // 取得已选择了哪些商品的属性
            $attr_checked_l = $model_type->typeRelatedList('goods_attr_index', array('goods_id' => intval($goods_array[0]['goods_id'])), 'attr_id,attr_value_id');
            if (is_array($attr_checked_l) && !empty($attr_checked_l)) {
                foreach ($attr_checked_l as $val) {
                    $attr_checked[] = $val;
                }
            }
            foreach ($goods_array as $k => $v) {
                $a = unserialize($v['goods_spec']);
                if (!empty($a)) {
                    foreach ($a as $key => $val) {
                        $spec_checked[$key]['id'] = $key;
                        $spec_checked[$key]['name'] = $val;
                    }
                    $matchs = array_keys($a);
                    sort($matchs);
                    $array = array();
                    $array['spec_ids'] = implode(',', $matchs);
                    $array['marketprice'] = $v['goods_marketprice'];
                    $array['price'] = $v['goods_price'];
                    $array['id'] = $v['goods_id'];
                    $array['stock'] = $v['goods_storage'];
                    $array['alarm'] = $v['goods_storage_alarm'];
                    $array['sku'] = $v['goods_serial'];
                    $array['barcode'] = $v['goods_barcode'];
                    $sp_value[] = $array;
                }
            }
        }
        $goods_class = Model('goods_class')->getGoodsClassLineForTag($goodscommon_info['gc_id']);
        $model_type = Model('type');
        // 获取类型相关数据
        $typeinfo = $model_type->getAttr($goods_class['type_id'], $this->store_info['store_id'], $goodscommon_info['gc_id']);
        list($spec_json, $spec_list, $attr_list, $brand_list) = $typeinfo;
        // 自定义属性
        $custom_list = Model('type_custom')->getTypeCustomList(array('type_id' => $goods_class['type_id']));
        $custom_list = array_under_reset($custom_list, 'custom_id');
        output_data(array('goodscommon_info' => $goodscommon_info, 'sp_value' => $sp_value, 'attr_checked' => $attr_checked, 'spec_checked' => array_values($spec_checked), 'spec_json' => $spec_json, 'spec_list' => $spec_list, 'attr_list' => $attr_list));
    }
    /**
     * 商品详细信息
     */
    public function goods_image_infoOp()
    {
        $common_id = $_GET['goods_commonid'];
        $model_goods = Model('goods');
        $common_list = $model_goods->getGoodsCommonInfoByID($common_id, 'store_id,goods_lock,spec_value,is_virtual,is_fcode,is_presell');
        if ($common_list['store_id'] != $this->store_info['store_id'] || $common_list['goods_lock'] == 1) {
            output_error('参数错误');
        }
        $spec_value = unserialize($common_list['spec_value']);
        // 商品图片
        $image_list = $model_goods->getGoodsImageList(array('goods_commonid' => $common_id));
        $image_array = array();
        if (!empty($image_list)) {
            foreach ($image_list as $val) {
                $val['goods_image_url'] = cthumb($val['goods_image'], 240);
                $image_array[$val['color_id']]['color_id'] = $val['color_id'];
                $image_array[$val['color_id']]['spec_name'] = $spec_value['1'][$val['color_id']];
                $image_array[$val['color_id']]['images'][] = $val;
            }
        }
        output_data(array('image_list' => array_values($image_array)));
    }
    /**
     * 商品编辑保存
     */
    public function goods_editOp(){
        $imgarray = array();
    
        $commonid = intval($_POST['commonid']);
        $imglist = $_POST['imglist'];
        $_POST['image_path'] = $imglist[0];
        $_POST['goods_image'] ='';
        unset($_POST['imglist']);
        unset($_POST['file']);
          if($_POST['g_state'] == 'on'){
            $_POST['g_state'] = 1;
        }else{
            $_POST['g_state'] = 0;
        }
    
        
        foreach ($imglist as $key=>$val) {
            $imgarray[0][$key]['name'] = $val;
            if($key == 0){
                 $imgarray[0][$key]['default'] = 1;
            }else{
                 $imgarray[0][$key]['default'] = 0;
            }
            $imgarray[0][$key]['sort'] = 0;
        }
     
        $_POST['g_body'] = stripslashes($_POST['g_body']);
        $model_goods = Model('goods');
        $goodscommon_info = $model_goods->getGoodsCommonInfoByID($commonid);
        if(strlen($goodscommon_info['spec_name']) > 2){
            $_POST['spec'] = array();
           $_POST['sp_name'] = unserialize($goodscommon_info['spec_name']);
           $_POST['sp_val'] = unserialize($goodscommon_info['spec_value']);
        }
       
        
        $logic_goods = Logic('goods');
        $result =  $logic_goods->updateGoods(
            $_POST,
            $_SESSION['store_id'], 
            $_SESSION['store_name'], 
            $this->store_info['store_state'], 
            $_SESSION['seller_id'], 
            $_SESSION['seller_name'],
            $_SESSION['bind_all_gc']
        );
        unset($_POST);
        $_POST['img'] = $imgarray;
       
        
        if (!$result['state']) {
            showMessage($result['msg'], '', '', 'error');
        } else {
            $rs = Logic('goods')->editSaveImage($_POST['img'], $commonid, $_SESSION['store_id'], $_SESSION['seller_id'], $_SESSION['seller_name']);
            if(!$rs){
                showMessage('更新图片出错！', '', '', 'error');
            }
            showMessage('更新成功！', '', '', 'succ');
        }
    }
    /**
     * 商品图片保存
     */
    public function goods_edit_imageOp()
    {
        $common_id = intval($_POST['commonid']);
        $rs = Logic('goods')->editSaveImage($_POST['img'], $common_id, $this->store_info['store_id'], $this->seller_info['seller_id'], $this->seller_info['seller_name']);
        if (!$rs['state']) {
            output_error($rs['msg']);
        }
        output_data('1');
    }
    /**
     * 商品上架
     */
    public function goods_showOp()
    {
        if ($this->store_info['store_state'] != 1) {
            output_error('店铺正在审核中或已经关闭，不能上架商品');
        }
        $result = Logic('goods')->goodsShow($_POST['commonids'], $this->store_info['store_id'], $this->seller_info['seller_id'], $this->seller_info['seller_name']);
        if (!$result['state']) {
            output_error($result['msg']);
        }
        output_data('1');
    }
    /**
     * 商品下架
     */
    public function goods_unshowOp()
    {
        $result = Logic('goods')->goodsUnShow($_POST['commonids'], $this->store_info['store_id'], $this->seller_info['seller_id'], $this->seller_info['seller_name']);
        if (!$result['state']) {
            output_error($result['msg']);
        }
        output_data('1');
    }
    /**
     * 商品删除
     */
    public function goods_dropOp()
    {
        $result = Logic('goods')->goodsDrop($_POST['commonids'], $this->store_info['store_id'], $this->seller_info['seller_id'], $this->seller_info['seller_name']);
        if (!$result['state']) {
            output_error($result['msg']);
        }
        output_data('1');
    }

     /**
     *   //商品相册上传 上传图片
     *
     * @param
     * @return
     */
    public function file_upload_goodsOp()
    {
        /**
         * 读取语言包
         */
        Language::read('sns_home');
        $lang = Language::getLangContent();
       
        $upload = new UploadFile();
        $upload->set('default_dir', ATTACH_GOODS . DS . $store_id . DS . $upload->getSysSetPath());
        $upload->set('max_size', C('image_max_filesize'));
        $upload->set('thumb_width', GOODS_IMAGES_WIDTH);
        $upload->set('thumb_height', GOODS_IMAGES_HEIGHT);
        $upload->set('thumb_ext', GOODS_IMAGES_EXT);
        $upload->set('fprefix', $store_id);
        $upload->set('allow_type', array('gif', 'jpg', 'jpeg', 'png'));
        $result = $upload->upfile('file');
        if (!$result) {
            output_error($upload->error);
        }
        $img_path = $upload->getSysSetPath() . $upload->file_name;
        list($width, $height, $type, $attr) = getimagesize(BASE_UPLOAD_PATH . DS . ATTACH_GOODS . DS . $store_id . DS . $img_path);
        /**
         * 上传图片
         */
        $data = array();
        $data['file_id'] = $result;
        $data['file_name'] = $img_path;
        $data['origin_file_name'] = $_FILES["file"]["name"];
        $data['file_url'] = cthumb($img_path, 240);
        output_data($data);
    }

  
    public function ajax_update_imgOp(){
            //p($_POST);die;
            $store_id = $_SESSION['store_id'];
            
            $list_id = intval($_POST['list_id']);
            $img = $_POST['img'];
            $file['list']=BASE_UPLOAD_PATH.DS.ATTACH_GOODS. DS . $store_id. DS . $this->getSysSetPath();
            if (!file_exists($file['list'])) {
                mkdir($file['list'],0777,true);
            }
            if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $img, $result)){
              $type = $result[2];
              $ysname = $this->setFileName($store_id,'','');
              $filename = $file['list']. DS . $ysname."jpg";
              file_put_contents($filename, base64_decode(str_replace($result[1], '', $img)));
            }
            $trname = rtrim($ysname,'.');
            $imagea = new Image;
            $imagea->thumb($filename,'800','800',$this->setFileName('','_1280',$trname),BASE_UPLOAD_PATH.DS.ATTACH_GOODS. DS . $store_id);
            $imagea->thumb($filename,'360','360',$this->setFileName('','_360',$trname),BASE_UPLOAD_PATH.DS.ATTACH_GOODS. DS . $store_id);
            $imagea->thumb($filename,'240','240',$this->setFileName('','_240',$trname),BASE_UPLOAD_PATH.DS.ATTACH_GOODS. DS . $store_id);
            $imagea->thumb($filename,'60','60',$this->setFileName('','_60',$trname),BASE_UPLOAD_PATH.DS.ATTACH_GOODS. DS . $store_id);
            
          

            if($filename){
                echo json_encode(array('status'=>1,'list_id'=>$list_id,'img'=>$ysname."jpg",'url'=>UPLOAD_SITE_URL.DS.ATTACH_GOODS. DS . $store_id. DS . $this->getSysSetPath().DS.$ysname."jpg"));
            }else{
                echo json_encode(array('status'=>0));
            }   

    }

        /**
     * 设置文件名称 不包括 文件路径
     *
     * 生成(从2000-01-01 00:00:00 到现在的秒数+微秒+四位随机)
     */
    private function setFileName($fprefix='',$new_ext='',$filename=''){
        $tmp_name = sprintf('%010d',time() - 946656000)
                        . sprintf('%03d', microtime() * 1000)
                        . sprintf('%04d', mt_rand(0,9999));

        return (empty ( $fprefix ) ? '' : $fprefix . '_'). (empty($filename) ? $tmp_name:$filename)  . ($new_ext == '' ? '' : $new_ext). '.';
    }

    /**
     * 根据系统设置返回商品图片保存路径
     */
    private function getSysSetPath(){
        switch(C('image_dir_type')){
            case "1":
                //按文件类型存放,例如/a.jpg
                $subpath = "";
                break;
            case "2":
                //按上传年份存放,例如2011/a.jpg
                $subpath = date("Y",time()) . "/";
                break;
            case "3":
                //按上传年月存放,例如2011/04/a.jpg
                $subpath = date("Y",time()) . "/" . date("m",time()) . "/";
                break;
            case "4":
                //按上传年月日存放,例如2011/04/19/a.jpg
                $subpath = date("Y",time()) . "/" . date("m",time()) . "/" . date("d",time()) . "/";
        }
        return $subpath;
    }
    
}