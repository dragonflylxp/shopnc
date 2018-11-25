<?php
/**
 * 文件上传类
 *
 *
 *
 * @package    library
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net
 * @link       http://www.shopec.net
 * @author     shopec Team
 * @since      File available since Release v1.1
 */


defined('Inshopec') or exit('Access Invalid!');
class UploadFile{
    /**
     * 文件存储路径
     */
    private $save_path;
    /**
     * 允许上传的文件类型
     */
    private $allow_type=array('gif','jpg','jpeg','bmp','png','swf','tbi');
    /**
     * 允许的最大文件大小，单位为KB
     */
    private $max_size = '';
    /**
     * 改变后的图片宽度
     */
    private $thumb_width = 0;
    /**
     * 改变后的图片高度
     */
    private $thumb_height = 0;
    /**
     * 生成扩缩略图后缀
     */
    private $thumb_ext = false;
    /**
     * 允许的图片最大高度，单位为像素
     */
    private $upload_file;
    /**
     * 是否删除原图
     */
    private $ifremove = false;
    /**
     * 上传文件名
     */
    public $file_name;
    /**
     * 上传文件后缀名
     */
    private $ext;
    /**
     * 上传文件新后缀名
     */
    private $new_ext;
    /**
     * 默认文件存放文件夹
     */
    private $default_dir = ATTACH_PATH;
    /**
     * 错误信息
     */
    public $error = '';
    /**
     * 生成的缩略图，返回缩略图时用到
     */
    public $thumb_image;
    /**
     * 是否立即弹出错误提示
     */
    private $if_show_error = false;
    /**
     * 是否只显示最后一条错误
     */
    private $if_show_error_one = false;
    /**
     * 文件名前缀
     *
     * @var string
     */
    private $fprefix;

    /**
     * 是否允许填充空白，默认允许
     *
     * @var unknown_type
     */
    private $filling = true;

    private $config;
    /**
     * 初始化
     *
     *  $upload = new UploadFile();
     *  $upload->set('default_dir','upload');
     *  $upload->set('max_size',1024);
     *  //生成4张缩略图，宽高依次如下
     *  $thumb_width    = '300,600,800,100';
     *  $thumb_height   = '300,600,800,100';
     *  $upload->set('thumb_width', $thumb_width);
     *  $upload->set('thumb_height',$thumb_height);
     *  //4张缩略图名称扩展依次如下
     *  $upload->set('thumb_ext',   '_small,_mid,_max,_tiny');
     *  //生成新图的扩展名为.jpg
     *  $upload->set('new_ext','jpg');
     *  //开始上传
     *  $result = $upload->upfile('file');
     *  if (!$result){
     *      echo '上传成功';
     *  }
     *
     */
    function __construct(){
        //加载语言包
        Language::read('core_lang_index');
        $this->set('max_size', C('image_max_filesize'));
    }
    /**
     * 设置
     *
     * @param mixed $key
     * @param mixed $value
     */
    public function set($key,$value){
        $this->$key = $value;
    }
    /**
     * 读取
     */
    public function get($key){
        return $this->$key;
    }
    /**
     * 上传操作
     *
     * @param string $field 上传表单名
     * @param boolean $oss 是否云存储 目前只有商品图片上传为true，其它为false
     * @return bool
     */
    public function upfile($field,$oss = false){

        //上传文件
        $this->upload_file = $_FILES[$field];
        if ($this->upload_file['tmp_name'] == ""){
            $this->setError(Language::get('cant_find_temporary_files'));
            return false;
        }

        //对上传文件错误码进行验证
        $error = $this->fileInputError();
        if (!$error){
            return false;
        }
        //验证是否是合法的上传文件
        if(!is_uploaded_file($this->upload_file['tmp_name'])){
            $this->setError(Language::get('upload_file_attack'));
            return false;
        }

        //验证文件大小
        if ($this->upload_file['size']==0){
            $error = Language::get('upload_file_size_none');
            $this->setError($error);
            return false;
        }
        if($this->upload_file['size'] > $this->max_size*1024){
            $error = Language::get('upload_file_size_cant_over').$this->max_size.'KB';
            $this->setError($error);
            return false;
        }

        //文件后缀名
        $tmp_ext = explode(".", $this->upload_file['name']);
        $tmp_ext = $tmp_ext[count($tmp_ext) - 1];
        $this->ext = strtolower($tmp_ext);

        //验证文件格式是否为系统允许
        if(!in_array($this->ext,$this->allow_type)){
            $error = Language::get('image_allow_ext_is').implode(',',$this->allow_type);
            $this->setError($error);
            return false;
        }

        //检查是否为有效图片
        if(!$image_info = @getimagesize($this->upload_file['tmp_name'])) {
            $error = Language::get('upload_image_is_not_image');
            $this->setError($error);
            return false;
        }

        //设置文件名称
        if(empty($this->file_name)){
            $this->setFileName();
        }
        if ($oss === true && C('oss.open')) {
            if ($this->error != '') return false;
            $result = oss::upload($this->upload_file['tmp_name'],$this->default_dir.$this->file_name);
            if ($result == false) {
                $this->error = '上传失败'.(C('debug') ? '，详细信息请查看系统日志data/log/oss' : '');
            }
            return true;
        }

        //设置图片路径
        $this->save_path = $this->setPath();

        //是否需要生成缩略图
        $ifresize = false;
        if ($this->thumb_width && $this->thumb_height && $this->thumb_ext){
            $thumb_width    = explode(',',$this->thumb_width);
            $thumb_height   = explode(',',$this->thumb_height);
            $thumb_ext      = explode(',',$this->thumb_ext);
            if (count($thumb_width) == count($thumb_height) && count($thumb_height) == count($thumb_ext)) $ifresize = true;
        }

        //是否立即弹出错误
        if($this->if_show_error){
            echo "<script type='text/javascript'>alert('". ($this->if_show_error_one ? $error : $this->error) ."');history.back();</script>";
            die();
        }
        if ($this->error != '') return false;
        if(@move_uploaded_file($this->upload_file['tmp_name'],BASE_UPLOAD_PATH.DS.$this->save_path.DS.$this->file_name)){
            //产生缩略图
            if ($ifresize){
                $imager = \shopec\Lib::imager();
                $resizingCount = count($thumb_ext);

                for ($i = 0; $i < $resizingCount; $i++) {
                    $iExt = $thumb_ext[$i];
                    $iWidth = $thumb_width[$i];
                    $iHeight = $thumb_height[$i];

                    $iSrcPath = rtrim(BASE_UPLOAD_PATH.DS.$this->save_path,'/').DS.$this->file_name;
                    $iDestPath = preg_replace('/^(.+)(\.[^\.]+)$/', '$1' . $iExt . '$2', $iSrcPath);

                    try {
                        $imageObj = @$imager->createImageFromPath($iSrcPath);
                    } catch (Exception $ex) {
                        $this->setError('图片格式不能被解析');
                        return false;
                    }

                    try {
                        if ($iWidth == 360 && $iHeight = 360) {
                            $imageObj->thumbnail($iWidth, $iHeight)->save($iDestPath);
                        } else {
                            $imageObj->resize($iWidth, $iHeight)->save($iDestPath);
                        }
                    } catch (Exception $ex) {
                        $this->setError('图片处理失败');
                        return false;
                    }

                    if ($i == 0) {
                        $this->thumb_image = basename($iDestPath);
                    }
                }
            }

            //删除原图
            if ($this->ifremove && is_file(BASE_UPLOAD_PATH.DS.$this->save_path.DS.$this->file_name)) {
                @unlink(BASE_UPLOAD_PATH.DS.$this->save_path.DS.$this->file_name);
            }
            return true;
        }else {
            $this->setError(Language::get('upload_file_fail'));
            return false;
        }
//      $this->setErrorFileName($this->upload_file['tmp_name']);
        return $this->error;
    }

    /**
     * 获取上传文件的错误信息
     *
     * @param string $field 上传文件数组键值
     * @return string 返回字符串错误信息
     */
    private function fileInputError(){
        switch($this->upload_file['error']) {
            case 0:
                //文件上传成功
                return true;
                break;

            case 1:
                //上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值
                $this->setError(Language::get('upload_file_size_over'));
                return false;
                break;

            case 2:
                //上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值
                $this->setError(Language::get('upload_file_size_over'));
                return false;
                break;

            case 3:
                //文件只有部分被上传
                $this->setError(Language::get('upload_file_is_not_complete'));
                return false;
                break;

            case 4:
                //没有文件被上传
                $this->setError(Language::get('upload_file_is_not_uploaded'));
                return false;
                break;

            case 6:
                //找不到临时文件夹
                $this->setError(Language::get('upload_dir_chmod'));
                return false;
                break;

            case 7:
                //文件写入失败
                $this->setError(Language::get('upload_file_write_fail'));
                return false;
                break;

            default:
                return true;
        }
    }

    /**
     * 设置存储路径
     *
     * @return string 字符串形式的返回结果
     */
    public function setPath(){
        /**
         * 判断目录是否存在，如果不存在 则生成
         */
        if (!is_dir(BASE_UPLOAD_PATH.DS.$this->default_dir)){
            $dir = $this->default_dir;
            $dir_array = explode(DS,$dir);
            $tmp_base_path = BASE_UPLOAD_PATH;
            foreach ($dir_array as $k => $v){
                $tmp_base_path = $tmp_base_path.DS.$v;
                if(!is_dir($tmp_base_path)){
                    if (!@mkdir($tmp_base_path,0755,true)){
                        $this->setError('创建目录失败，请检查是否有写入权限');
                        return false;
                    }
                }
            }
            unset($dir,$dir_array,$tmp_base_path);
        }

        //设置权限
        @chmod(BASE_UPLOAD_PATH.DS.$this->default_dir,0755);

        //判断文件夹是否可写
        if(!is_writable(BASE_UPLOAD_PATH.DS.$this->default_dir)) {
            $this->setError(Language::get('upload_file_dir').$this->default_dir.Language::get('upload_file_dir_cant_touch_file'));
            return false;
        }
        return $this->default_dir;
    }

    /**
     * 设置文件名称 不包括 文件路径
     *
     * 生成(从2000-01-01 00:00:00 到现在的秒数+微秒+四位随机)
     */
    private function setFileName(){
        $tmp_name = sprintf('%010d',time() - 946656000)
                        . sprintf('%03d', microtime() * 1000)
                        . sprintf('%04d', mt_rand(0,9999));
        $this->file_name = (!isset ( $this->fprefix ) ? '' : $this->fprefix . '_')
                                 . $tmp_name . '.' . ($this->new_ext == '' ? $this->ext : $this->new_ext);
    }

    /**
     * 设置错误信息
     *
     * @param string $error 错误信息
     * @return bool 布尔类型的返回结果
     */
    private function setError($error){
        $this->error = $error;
    }

    /**
     * 根据系统设置返回商品图片保存路径
     */
    public function getSysSetPath(){
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
