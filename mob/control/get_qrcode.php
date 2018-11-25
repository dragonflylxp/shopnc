<?php
/**
 * 获取二维码
 * Created by PhpStorm.
 * User: WhartonChan
 * Date: 2016/1/27
 * Time: 12:29
 */


use shopec\Tpl;

defined('Inshopec') or exit('Access Invalid!');
class get_qrcodeControl extends mobileMemberControl
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 我的商城
     */
    public function indexOp()
    {
        $member_info = array();
        $member_info['user_name'] = $this->member_info['member_name'];
        $member_info['avatar'] = getMemberAvatarForID($this->member_info['member_id']);
        $member_info['point'] = $this->member_info['member_points'];
        $member_info['predepoit'] = $this->member_info['available_predeposit'];

        /*$model_wxchqr = Model("wxch_qr");
        $wxch_qr = $model_wxchqr->where(array('scene_id'=>$this->member_info['member_id']))->find();*/


        /*  $name='share_'.$this->member_info['member_id'].'.png';

          if(file_exists(BASE_UPLOAD_PATH.DS.ATTACH_MALBUM.DS.'qrcode'.DS.$name)) //已经存在，直接获取
          {
              //$qrcode_img = $wxch_qr['qr_path'];
  //		    $qrcode_img = $this->get_domain().'/images/qrcode/share'.$this->member_info['member_id'].".png";
          }
          else //不存在,则生成
          {
              $qrcode_img = $this->qrcode();
              //$qrcode_img_msg = "请先在微信自定义菜单点击[我的二维码]生成二维码后方可在此查看";
          }*/
        $qrcode_img = $this->qrcode($this->member_info);
        output_data(array('member_info' => $member_info, 'qrcode_img' => $qrcode_img, 'qrcode_img_msg' => ''));
    }


    public function qrcode($member_info)
    {


        if ($_GET['id']) {
            $tmpl = Model('qrcode_tmpl')->where(array('id' => $_GET['id'], 'type' => '0'))->find();
        }
        if (empty($tmpl)) {
            $tmpl = Model('qrcode_tmpl')->where(array('type' => '0'))->find();
        }

        if (empty($tmpl)) {
            return '';
        }


        $bg_img = getWxShareImage($tmpl['image']);

        $pos = json_decode($tmpl['position'], true);


        $member_id = $member_info['member_id'];
        
        $name = 'qrcode_' . $member_id . '.png';
        $name2 = 'share_' . $member_id . '.png';
     
        if (false) {//file_exists(BASE_UPLOAD_PATH . DS . ATTACH_MALBUM  . DS . $name2)) {

        } else {
            require_once(BASE_RESOURCE_PATH . DS . 'phpqrcode' . DS . 'index.php');
            $url = WAP_SITE_URL . '/tmpl/member/register.html?inviterid=' . $member_id;
            $PhpQRCode = new PhpQRCode();
            $PhpQRCode->set('pngTempDir', BASE_UPLOAD_PATH . DS . ATTACH_MALBUM . DS);
            $PhpQRCode->set('date', $url);
            $PhpQRCode->set('pngTempName', $name);
            $PhpQRCode->init();

            //$imageDestination = @imagecreatefromjpeg(BASE_ROOT_PATH.'/images/qrcode/bg.jpg');


            $imageDestination = @imagecreatefromjpeg($bg_img);


            $w = imagesx($imageDestination);
            $h = imagesy($imageDestination);


            $imageSource = @imagecreatefrompng(BASE_UPLOAD_PATH . DS . ATTACH_MALBUM . DS . $name);

            $avatar = getMemberAvatarForID($member_info['member_id']);

            
            $member_v = @imagecreatefromjpeg($avatar);
//错误处理
if(!$member_v){
    $member_v  = imagecreatetruecolor(150, 30);
    $bg = imagecolorallocate($member_v, 255, 255, 255);
    $text_color  = imagecolorallocate($member_v, 0, 0, 255);
    //填充背景色
    imagefilledrectangle($member_v, 0, 0, 150, 30, $bg);
    //以图像方式输出错误信息
    imagestring($member_v, 3, 5, 5, "Error loading image", $text_color);
} else {
    //输出该图像
//  header("Content-Type:image/jpg");
//  imagejpeg($member_v);
//  imagedestroy($member_v);
}

            $out = imagecreatetruecolor($w, $h);

            imagecopy($out, $imageDestination, 0, 0, 0, 0, $w, $h);
            imagecopyresized($out, $imageSource, $w * $pos['x1'], $h * $pos['y1'], 0, 0, $w * $pos['width'], $w * $pos['width'], imagesx($imageSource), imagesy($imageSource));
            imagecopyresized($out, $member_v, $w * $pos['x2'], $h * $pos['y2'], 0, 0, $w * $pos['width2'], $w * $pos['width2'], imagesx($member_v), imagesy($member_v));


            $textcolor = imagecolorallocate($out, 156, 25, 56);
            $font = BASE_RESOURCE_PATH . "/font/simhei.ttf";
            imagettftext($out, 26, 0, $w * $pos['x3'], $h * $pos['y3'], $textcolor, $font, $member_info['member_name']);

            imagepng($out, BASE_UPLOAD_PATH . DS . ATTACH_MALBUM . DS . $name2);
            imagedestroy($imageDestination);
            imagedestroy($imageSource);
            imagedestroy($member_v);
        }
        return UPLOAD_SITE_URL. DS . ATTACH_MALBUM . DS . $name2;
    }


    public function storeOp()
    {
        if ($this->store_info) {
            output_data(array('qrcode_img' => $this->store_qrcode($this->member_info, $this->store_info)));
        } else {
            output_error('');
        }
    }


    public function store_qrcode($member_info, $store_info)
    {


        if ($_GET['id']) {
            $tmpl = Model('qrcode_tmpl')->where(array('id' => $_GET['id'], 'type' => '1'))->find();
        }
        if (empty($tmpl)) {
            $tmpl = Model('qrcode_tmpl')->where(array('type' => '1'))->find();
        }

        if (empty($tmpl)) {
            return '';
        }


        $bg_img = getWxShareImage($tmpl['image']);

        $pos = json_decode($tmpl['position'], true);


        $member_id = $member_info['member_id'];
        $name = 'qrcode_store_' . $store_info['store_id'] . '.png';
        $name2 = 'share_store_' . $store_info['store_id'] . '.png';
        if (false) {//file_exists(BASE_UPLOAD_PATH . DS . ATTACH_MALBUM  . DS . $name2)) {

        } else {
            require_once(BASE_RESOURCE_PATH . DS . 'phpqrcode' . DS . 'index.php');
            $url = WAP_SITE_URL . '/tmpl/member/register.html?inviterid=' . $member_id . '&ref=' . WAP_SITE_URL . '/tmpl/store.html?store_id=' . $store_info['store_id'];
            $PhpQRCode = new PhpQRCode();
            $PhpQRCode->set('pngTempDir', BASE_UPLOAD_PATH . DS . ATTACH_MALBUM . DS);
            $PhpQRCode->set('date', $url);
            $PhpQRCode->set('pngTempName', $name);
            $PhpQRCode->init();

            //$imageDestination = @imagecreatefromjpeg(BASE_ROOT_PATH.'/images/qrcode/bg.jpg');


            $imageDestination = @imagecreatefromjpeg($bg_img);


            $w = imagesx($imageDestination);
            $h = imagesy($imageDestination);


            $imageSource = @imagecreatefrompng(BASE_UPLOAD_PATH . DS . ATTACH_MALBUM . DS . $name);
            $avatar = getStoreLogo($store_info['store_label'], 'store_logo');


            //$member_v=@imagecreatefromjpeg($avatar);


            $out = imagecreatetruecolor($w, $h);

            imagecopy($out, $imageDestination, 0, 0, 0, 0, $w, $h);
            imagecopyresized($out, $imageSource, $w * $pos['x1'], $h * $pos['y1'], 0, 0, $w * $pos['width'], $w * $pos['width'], imagesx($imageSource), imagesy($imageSource));
            //imagecopyresized($out, $member_v, $w*$pos['x2'], $h*$pos['y2'], 0, 0,$w*$pos['width2'],$w*$pos['width2'], imagesx($member_v), imagesy($member_v));


            //$textcolor = imagecolorallocate($out,156,25,56);
            //$font = BASE_RESOURCE_PATH."/font/simhei.ttf";
            //imagettftext($out,26,0,$w*$pos['x3'],$h*$pos['y3'],$textcolor,$font,$store_info['store_name']);

            imagepng($out, BASE_UPLOAD_PATH . DS . ATTACH_MALBUM . DS . $name2);
            imagedestroy($imageDestination);
            imagedestroy($imageSource);
            //imagedestroy($member_v);
        }
        return DS . DIR_UPLOAD . DS . ATTACH_MALBUM . DS . $name2;
    }




    public function vip_cardOp(){
        $id=$_GET['vip_id'];
        $vip=Model('vip_card')->getVipCard(array('vip_id'=>$id));
        if(!empty($vip)){
            output_data($this->vip_qrcode($vip));
        }else {
            output_error('vip不存在');
        }
    }


    private function vip_qrcode($vip){
        $store_vip=Model('store')->getVipSetting($vip['store_id']);
        $pos=json_decode($store_vip['qr_tmpl'],1);
        $member_id=$vip['member_id'];
        $name = 'qrcode_vip_' . $vip['vip_id'] . '.png';
        $name2 = 'share_vip_' . $vip['vip_id'] . '.png';
        if(false){//file_exists(BASE_UPLOAD_PATH . DS . ATTACH_MALBUM  . DS .$member_id.DS .$name2)) {

        } else {
            require_once(BASE_RESOURCE_PATH . DS . 'phpqrcode' . DS . 'index.php');
            $ref=WAP_SITE_URL
                . '/tmpl/membership/memberbuy'.$vip['store_id'].'.html?store_id=' . $vip['store_id'].'&vip_inviter='.$member_id;
            $url = WAP_SITE_URL . '/tmpl/member/register.html?inviterid='  . $member_id.'&ref='.urlencode($ref);
            $PhpQRCode = new PhpQRCode();
            $PhpQRCode->set('pngTempDir', BASE_UPLOAD_PATH . DS . ATTACH_MALBUM.DS. $member_id . DS);
            $PhpQRCode->set('date', $url);
            $PhpQRCode->set('pngTempName', $name);
            $PhpQRCode->init();

            if(pathinfo($pos['file_name'],PATHINFO_EXTENSION)=='png'){
                $imageDestination = @imagecreatefrompng(BASE_UPLOAD_PATH.DS.ATTACH_STORE.DS.'qrcode'.DS.$pos['file_name']);
            }else{
                $imageDestination = @imagecreatefromjpeg(BASE_UPLOAD_PATH.DS.ATTACH_STORE.DS.'qrcode'.DS.$pos['file_name']);
            }



//            $imageDestination = @imagecreatefromjpeg($bg_img);

            $w=imagesx($imageDestination);
            $h=imagesy($imageDestination);




            $imageSource = @imagecreatefrompng(BASE_UPLOAD_PATH . DS . ATTACH_MALBUM . DS .$member_id . DS . $name);

            $member_v=@imagecreatefromjpeg($this->member_info['wx_avatar']);


            if(!$member_v) {
                $avatar = getMemberAvatarForID($vip['member_id']);

                $member_v = @imagecreatefromjpeg($avatar);
                if (!$member_v) {
                    $member_v = @imagecreatefromjpeg(BASE_ROOT_PATH . DS . 'images/qrcode/default_user_portrait.jpg');
                }
            }


            $out = imagecreatetruecolor($w,$h);

            imagecopy($out, $imageDestination, 0, 0, 0, 0, $w, $h);
            imagecopyresized($out, $imageSource, $w*$pos['x1'], $h*$pos['y1'], 0, 0,$w*$pos['width'],$w*$pos['width'], imagesx($imageSource), imagesy($imageSource));
            imagecopyresized($out, $member_v, $w*$pos['x2'], $h*$pos['y2'], 0, 0,$w*$pos['width2'],$w*$pos['width2'], imagesx($member_v), imagesy($member_v));
            $colors=$this->wpjam_hex2rgb($pos['color']);
            $textcolor = imagecolorallocate($out,$colors[0],$colors[1],$colors[2]);
            $font = BASE_RESOURCE_PATH."/font/simhei.ttf";
            imagettftext($out,26,0,$w*$pos['x3'],$h*$pos['y3'],$textcolor,$font,empty($this->member_info['nick_name'])?$vip['member_name']:$this->member_info['nick_name']);

            if(!file_exists(BASE_UPLOAD_PATH . DS . ATTACH_MALBUM  . DS .$member_id)){
                mkdir(BASE_UPLOAD_PATH . DS . ATTACH_MALBUM  . DS .$member_id,0755,true);
                chmod(BASE_UPLOAD_PATH . DS . ATTACH_MALBUM  . DS .$member_id,0755);
            }
            imagepng($out,BASE_UPLOAD_PATH . DS . ATTACH_MALBUM  . DS .$member_id.DS. $name2);
            imagedestroy($imageDestination);
            imagedestroy($imageSource);
            imagedestroy($member_v);
        }
        return DS . DIR_UPLOAD . DS . ATTACH_MALBUM  . DS .$member_id.DS. $name2;
    }


    function wpjam_hex2rgb($hex) {
        $hex = str_replace("#", "", $hex);

        if(strlen($hex) == 3) {
            $r = hexdec(substr($hex,0,1).substr($hex,0,1));
            $g = hexdec(substr($hex,1,1).substr($hex,1,1));
            $b = hexdec(substr($hex,2,1).substr($hex,2,1));
        } else {
            $r = hexdec(substr($hex,0,2));
            $g = hexdec(substr($hex,2,2));
            $b = hexdec(substr($hex,4,2));
        }

        return array($r, $g, $b);
}







    /**
     * 取得当前的域名
     *
     * @access  public
     *
     * @return  string      当前的域名
     */
    function get_domain()
    {
        /* 协议 */
        $protocol = $this->http();

        /* 域名或IP地址 */
        if (isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
            $host = $_SERVER['HTTP_X_FORWARDED_HOST'];
        } elseif (isset($_SERVER['HTTP_HOST'])) {
            $host = $_SERVER['HTTP_HOST'];
        } else {
            /* 端口 */
            if (isset($_SERVER['SERVER_PORT'])) {
                $port = ':' . $_SERVER['SERVER_PORT'];

                if ((':80' == $port && 'http://' == $protocol) || (':443' == $port && 'https://' == $protocol)) {
                    $port = '';
                }
            } else {
                $port = '';
            }

            if (isset($_SERVER['SERVER_NAME'])) {
                $host = $_SERVER['SERVER_NAME'] . $port;
            } elseif (isset($_SERVER['SERVER_ADDR'])) {
                $host = $_SERVER['SERVER_ADDR'] . $port;
            }
        }

        return $protocol . $host;
    }

    /**
     * 获得 ECSHOP 当前环境的 HTTP 协议方式
     *
     * @access  public
     *
     * @return  void
     */
    function http()
    {
        return (isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) != 'off')) ? 'https://' : 'http://';
    }


}
