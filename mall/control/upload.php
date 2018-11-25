<?php
//删除step3上传的证书图片

use shopec\Tpl;


defined('Inshopec') or exit('Access Invalid!');

class uploadControl extends BaseHomeControl{
    //删除图片的方法
    public function delateimgOp(){

        $message =array();
        $img_url = trim($_GET['img_url']);
        $field = trim($_GET['img_field']);
        $arr =parse_url($img_url);
        $path = $arr['path'];
        $new_arr = explode('/',$path);
        $img_name = $new_arr[count($new_arr)-1];
        $file_url = BASE_UPLOAD_PATH.'/shop/store_joinin/'.$img_name;
        //删除数据库图片名
        if($field != "" && $img_name != ""){
            $model_store_joinin = Model('store_joinin');
            $condition['member_id'] = $_SESSION['member_id'];
            $rs = $model_store_joinin ->where($condition)->field($field)->find();
            if($rs && !empty($rs[$field])){
                $img_arr = explode("|",$rs[$field]);
                foreach( $img_arr as $k=>$v) {
                    if($img_name == $v) unset($img_arr[$k]);
                }
                //$key=array_search($img_name ,$img_arr);
                //array_splice($img_arr,$key,1);
                $data[$field] = implode("|",$img_arr);
                if($model_store_joinin->where($condition)->update($data)){
                    //删除图片文件
                    @unlink($file_url);
                    $message['img_name'] = $img_name;
                    $message['status'] = 1;
                    echo json_encode($message);
                }else{
                    echo 0;
                }
            }else{
                @unlink($file_url);
                $message['img_name'] = $img_name;
                $message['status'] = 1;
                echo json_encode($message);
            }
        }else{
            @unlink($file_url);
            $message['img_name'] = $img_name;
            $message['status'] = 1;
            echo json_encode($message);
        }

    }


}