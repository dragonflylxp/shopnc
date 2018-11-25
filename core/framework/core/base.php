<?php
/**
 * 核心文件
 *
 * 核心初始化类，不允许继承
 *
 */
namespace shopec;

use \Language as Language;

class Core
{

    /**
     * 授权域名列表
     *
     * 多个域名以半角逗号分割
     */
    const DOMAIN_LIST = "zbengbu.com";

    /**
     * 初始化应用
     */
    public static function init()
    {
        global $setting_config;
        require_once BASE_CORE_PATH . "/framework/function/core.php";
        require_once BASE_CORE_PATH . "/shopec/db/db.php";
        require_once BASE_CORE_PATH . "/framework/libraries/cache.php";
        require_once BASE_CORE_PATH . "/framework/libraries/language.php";
        require_once BASE_CORE_PATH . "/framework/libraries/model.php";
        require_once BASE_CORE_PATH . "/framework/function/goods.php";

        if (function_exists('spl_autoload_register')) {
            spl_autoload_register(array('\shopec\Core', 'autoload'));
        } else {
            function __autoload($class)
            {
                return Core::autoload($class);
            }
        }

        Core::parseConf($setting_config);

        define('MD5_KEY', md5($setting_config['md5_key']));

        if (function_exists('date_default_timezone_set')) {
            if (is_numeric($setting_config['time_zone'])) {
                @date_default_timezone_set('Asia/Shanghai');
            } else {
                @date_default_timezone_set($setting_config['time_zone']);
            }
        }

        //session start
        Core::sessionStart();

        //output to the template
        Tpl::output('setting_config', $setting_config);

        //read language
        Language::read('core_lang_index');
    }

    public static function initializeApplication($config)
    {
        //self::checkCopyRight();

        global $config;

        // APP
        define('Inshopec', true);
        // 分隔符
        define('DS', '/');
        // 时间戳
        define('StartTime', microtime(true));
        define('TIMESTAMP', time());
        // 默认平台店铺
        define('DEFAULT_PLATFORM_STORE_ID', $config['default_store_id']);
        // 伪静态
        define('URL_MODEL', $config['url_model']);
        // 子域名
        define('SUBDOMAIN_SUFFIX', $config['subdomain_suffix']);
        define('BASE_SITE_URL', 'http://' . $_SERVER['HTTP_HOST'] . ($_SERVER['SERVER_PORT'] != '80' ? ':' . $_SERVER['SERVER_PORT'] : ''));
        define('SHOP_SITE_URL', $config['shop_site_url']);
        define('CMS_SITE_URL', $config['cms_site_url']);
        define('DATA_SITE_URL', $config['data_site_url']);//BY:511613932
        define('CIRCLE_SITE_URL', $config['circle_site_url']);
        define('MICROSHOP_SITE_URL', $config['microshop_site_url']);
        define('ADMIN_SITE_URL', $config['admin_site_url']);
        define('MOBILE_SITE_URL', $config['mobile_site_url']);
        define('WAP_SITE_URL', $config['wap_site_url']);
        define('UPLOAD_SITE_URL', $config['upload_site_url']);
        define('UPLOAD_SITE_URL_HTTPS', $config['upload_site_url']);
        define('RESOURCE_SITE_URL', $config['resource_site_url']);
        define('RESOURCE_SITE_URL_HTTPS', $config['resource_site_url']);
        define('DELIVERY_SITE_URL', $config['delivery_site_url']);
        define('LOGIN_SITE_URL', $config['member_site_url']);
        define('MEMBER_SITE_URL', $config['member_site_url']);
        define('CHAT_SITE_URL', $config['chat_site_url']);
        define('NODE_SITE_URL', $config['node_site_url']);
        define('CHAIN_SITE_URL', $config['chain_site_url']);
        define('SHOP_TEMPLATES_URL', SHOP_SITE_URL . '/templates/default');
        define('BASE_DATA_PATH', BASE_ROOT_PATH . '/data');
        define('BASE_UPLOAD_PATH',BASE_ROOT_PATH . "/public/data/upload");
        define('BASE_RESOURCE_PATH', BASE_ROOT_PATH . "/public/data/resource");
        define('CHARSET', $config['db']['master']['dbcharset']);
        define('DBDRIVER', $config['dbdriver']);
        define('SESSION_EXPIRE', $config['session_expire']);
        define('LANG_TYPE', $config['lang_type']);
        define('COOKIE_PRE', $config['cookie_pre']);
        define('DBPRE', $config['tablepre']);
        define('DBNAME', $config['db']['master']['dbname']);

        Core::init();
    }

    public static function runApplication($path = null)
    {
        if (!empty($path)) {
            $control = $_GET['con'];
            $file = "/control/" . $control . ".php";
            $includeFile = $path . $file;
            if (!@include($includeFile)) {
                if (C('debug')) {
                    throw_exception("Base Error: file [{$file}] isn't exists!");
                } else {
                    showMessage('抱歉！您访问的页面不存在', '', 'exception', 'error');
                }
            }
        } elseif (defined("APP_ID")) {
            $file = "/" . APP_ID . "/control/control.php";
            $includeFile = BASE_ROOT_PATH . $file;
            if (!@include($includeFile)) {
                if (C('debug')) {
                    throw_exception("Base Error: file [{$file}] isn't exists!");
                } else {
                    showMessage('抱歉！您访问的页面不存在', '', 'exception', 'error');
                }
            }
        }

        Core::initApp($path);
    }

    public static function getConfig($key)
    {
        return $GLOBALS['setting_config'][$key];
    }

    public static function getConfigs()
    {
        return $GLOBALS['setting_config'];
    }

    protected static function checkCopyRight()
    {
        if (strpos(self::DOMAIN_LIST, ",") !== false) {
            $hostArray = explode(",", self::DOMAIN_LIST);
            foreach ($hostArray as $value) {
                $host = strtolower(stristr($_SERVER['HTTP_HOST'], $value));
                if ($host == strtolower($value)) {
                    return;
                } else {
                    continue;
                }
            }
            header("location: mailto:noikiy@qq.com");
            exit();
        } else {
            $host = strtolower(stristr($_SERVER["HTTP_HOST"], self::DOMAIN_LIST));
            if ($host !== strtolower(self::DOMAIN_LIST)) {
                header("location: mailto:noikiy@qq.com");
            }
        }
    }

    /**
     * 控制器调度
     *
     */
    private static function initApp($path = null)
    {

        //二级域名
        if ($GLOBALS['setting_config']['enabled_subdomain'] == '1' && $_GET['con'] == 'index' && $_GET['fun'] == 'index') {
            $store_id = subdomain();
            if ($store_id > 0) {
                $_GET['con'] = 'show_store';
            }
        }

        if (!empty($path)) {
            $actFile = realpath($path . '/control/' . $_GET['con'] . '.php');
			
            if (!file_exists($actFile)) {
                if (C('debug')) {
                    throw_exception("Base Error: access file isn't exists!");
                } else {
                    showMessage('抱歉！您访问的页面不存在', '', 'html', 'error');
                }
            }
        } else {
            $actFile = realpath(BASE_PATH . '/control/' . $_GET['con'] . '.php');
            if (!@include($actFile)) {
                if (C('debug')) {
                    throw_exception("Base Error: access file isn't exists!");
                } else {
                    showMessage('抱歉！您访问的页面不存在', '', 'html', 'error');
                }
            }
        }
        
        $class_name = $_GET['con'] . 'Control';

        if (class_exists($class_name)) {
        	
            $main = new $class_name();
        
            $function = $_GET['fun'] . 'Op';
			
            if (method_exists($main, $function)) {
                $main->$function();
            } elseif (method_exists($main, 'indexOp')) {
                $main->indexOp();
            } else {
                $error = "Base Error: function $function not in $class_name!";
                throw_exception($error);
            }
        } else {
            $error = "Base Error: class $class_name isn't exists!";
            throw_exception($error);
        }
    }

    private static function initAdminApp($admin)
    {

        //二级域名
        if ($GLOBALS['setting_config']['enabled_subdomain'] == '1' && $_GET['con'] == 'index' && $_GET['fun'] == 'index') {
            $store_id = subdomain();
            if ($store_id > 0) {
                $_GET['con'] = 'show_store';
            }
        }
        $actFile = realpath(BASE_PATH . '/modules/' . $admin . '/control/' . $_GET['con'] . '.php');

        $class_name = $_GET['con'] . 'Control';
        if (!@include($actFile)) {
            if (C('debug')) {
                throw_exception("Base Error: access file isn't exists!");
            } else {
                showMessage('抱歉！您访问的页面不存在', '', 'html', 'error');
            }
        }
        if (class_exists($class_name)) {
            $main = new $class_name();
            $function = $_GET['fun'] . 'fun';
            if (method_exists($main, $function)) {
                $main->$function();
            } elseif (method_exists($main, 'indexOp')) {
                $main->indexOp();
            } else {
                $error = "Base Error: function $function not in $class_name!";
                throw_exception($error);
            }
        } else {
            $error = "Base Error: class $class_name isn't exists!";
            throw_exception($error);
        }
    }

    private static function autoload($class)
    {
        $class = strtolower($class);
        if (ucwords(substr($class, -7)) == 'Control') {
            $tmpFile = '/control/control.php';
            if (!@include_once(BASE_PATH . $tmpFile)) {
                exit("Error: {$class}:{$tmpFile} isn't exists!@1");
            }
        } elseif (ucwords(substr($class, -5)) == 'Class') {
            $tmpFile = '/framework/libraries/' . substr($class, 0, -5) . '.class.php';
            if (!@include_once(BASE_PATH . $tmpFile)) {
                exit("Error: {$class}:{$tmpFile} isn't exists!@1");
            }
        } elseif (ucwords(substr($class, 0, 5)) == 'Cache' && $class != 'cache') {
            $tmpFile = '/framework/cache/' . substr($class, 0, 5) . '.' .substr($class, 5) . '.php';
            if (!@include_once(BASE_CORE_PATH . $tmpFile)) {
                exit("Error: {$class}:{$tmpFile} isn't exists!@2");
            }
        } elseif ($class == 'db') {
            $tmpFile = '/framework/db/' . strtolower(DBDRIVER) . '.php';
            if (!@include_once(BASE_CORE_PATH . $tmpFile)) {
                exit("Error: {$class}:{$tmpFile} isn't exists!@3");
            }
        } elseif (strstr($class, "shopec\db\driver")) {
            $tmpFile = '/shopec/db/driver/' . strtolower(DBDRIVER) . '.php';
            if (!@include_once(BASE_CORE_PATH . $tmpFile)) {
                exit("Error: {$class}:{$tmpFile} isn't exists!@4");
            }
        } else {
            $tmpFile = '/framework/libraries/' . $class . '.php';
            if ($class != 'seccode' && $class != 'shopec\lib\messager\smtpmailer' && $class != 'shopec\lib\messager\exception') {
                if (!@include_once(BASE_CORE_PATH . $tmpFile)) {
                    exit("Error: {$class}:{$tmpFile} isn't exists!@5");
                    /*
                    if (!defined('APP_ID') || !@include_once(BASE_PATH . '/control/' . $class . '.php')) {
                        exit("Error: {$class}:{$tmpFile} isn't exists!@5");
                    }
                    */
                }
            }
        }
    }


    /**
     * 开启session
     *
     */
    private static function sessionStart()
    {

        if (SUBDOMAIN_SUFFIX) {
            $subDomainSuffix = SUBDOMAIN_SUFFIX;
        } else {
            if (preg_match("/^[0-9.]+$/", $_SERVER['HTTP_HOST'])) {
                $subDomainSuffix = $_SERVER['HTTP_HOST'];
            } else {
                $splitUrl = explode('.', $_SERVER['HTTP_HOST']);
                if ($splitUrl[2] != '') {
                    unset($splitUrl[0]);
                }
                $subDomainSuffix = implode('.', $splitUrl);
            }
        }

        @ini_set('session.name', 'PHPSESSID');
        $subDomainSuffix = str_replace('http://', '', $subDomainSuffix);
        if ($subDomainSuffix !== 'localhost') {
            @ini_set('session.cookie_domain', $subDomainSuffix);
        }

        /**
         * 开启以下配置支持session信息存信memcache
         *
         * @ini_set("session.save_handler", "memcache");
         * @ini_set("session.save_path", C('memcache.1.host').':'.C('memcache.1.port'));
         * 这里默认以文件形式存储session信息
         */
        
        session_save_path(BASE_DATA_PATH . '/session');
        session_start();
    }

    private static function parseConf(&$setting_config)
    {				
        $coreConfig = $GLOBALS['config'];
        if (is_array($coreConfig['db']['slave']) && !empty($coreConfig['db']['slave'])) {
            $dbslave = $coreConfig['db']['slave'];
            $coreConfig['db']['slave'] = $dbslave;
        } else {
            $coreConfig['db']['slave'] = $coreConfig['db']['master'];
        }
		
        $setting_config = $coreConfig;
        $setting = ($setting = rkcache('setting')) ? $setting : rkcache('setting', true);
        
        $setting['shopec_version'] = '<span class="vol"><font class="b">laravel</font><font class="o">mall</font></span>';
        $setting_config = array_merge_recursive($setting, $coreConfig);
        
    }
}

/**
 * 模板类
 */

class Tpl
{
    /**
     * 单件对象
     */
    private static $instance = null;
    /**
     * 输出模板内容的数组，其他的变量不允许从程序中直接输出到模板
     */
    private static $output_value = array();
    /**
     * 模板路径设置
     */
    private static $tpl_dir = '';
    /**
     * 默认layout
     */
    private static $layout_file = 'layout';

    private function __construct()
    {
    }

    /**
     * 实例化
     *
     * @return obj
     */
    public static function getInstance()
    {
        if (self::$instance === null || !(self::$instance instanceof Tpl)) {
            self::$instance = new Tpl();
        }
        return self::$instance;
    }

    /**
     * 设置模板目录
     *
     * @param string $dir
     * @return bool
     */
    public static function setDir($dir)
    {
        self::$tpl_dir = $dir;
        return true;
    }

    /**
     * 设置布局
     *
     * @param string $layout
     * @return bool
     */
    public static function setLayout($layout)
    {
        self::$layout_file = $layout;
        return true;
    }

    /**
     * 抛出变量
     *
     * @param mixed $output
     * @param  void
     */
    public static function output($output, $input = '')
    {
        self::getInstance();

        self::$output_value[$output] = $input;
    }

    /**
     * 调用显示模板
     *
     * @param string $page_name
     * @param string $layout
     * @param int $time
     */
    public static function showpage($page_name = '', $layout = '', $time = 2000)
    {
        if (!defined('TPL_NAME')) {
            define('TPL_NAME', 'default');
        }
        self::getInstance();
        if (!empty(self::$tpl_dir)) {
            $tpl_dir = self::$tpl_dir . DS;
        }
        //默认是带有布局文件
        if (empty($layout)) {
            $layout = 'layout' . DS . self::$layout_file . '.php';
        } else {
            $layout = 'layout' . DS . $layout . '.php';
        }
        $layout_file = BASE_PATH . '/templates/' . TPL_NAME . DS . $layout;
        $tpl_file = BASE_PATH . '/templates/' . TPL_NAME . DS . $tpl_dir . $page_name . '.php';
        
        if (defined("MODULES_BASE_PATH") && $page_name !== "msg") {
            $tpl_file = MODULES_BASE_PATH . '/templates/' . TPL_NAME . DS . $tpl_dir . $page_name . '.php';
        }

        if (file_exists($tpl_file)) {
            //对模板变量进行赋值
            $output = self::$output_value;
			
            //页头
            $output['html_title'] = $output['html_title'] != '' ? $output['html_title'] : $GLOBALS['setting_config']['site_name'];
            $output['seo_keywords'] = $output['seo_keywords'] != '' ? $output['seo_keywords'] : $GLOBALS['setting_config']['site_name'];
            $output['seo_description'] = $output['seo_description'] != '' ? $output['seo_description'] : $GLOBALS['setting_config']['site_name'];
            $output['ref_url'] = getReferer();

            Language::read('common');
            $lang = Language::getLangContent();

            @header("Content-type: text/html; charset=" . CHARSET);
            //判断是否使用布局方式输出模板，如果是，那么包含布局文件，并且在布局文件中包含模板文件
            if ($layout != '') {
                if (file_exists($layout_file)) {
                    include_once($layout_file);
                } else {
                    $error = 'Tpl ERROR:' . 'templates' . DS . $layout . ' is not exists';
                    throw_exception($error);
                }
            } else {
                include_once($tpl_file);
            }
        } else {
            $error = 'Tpl ERROR:' . 'templates' . DS . $tpl_dir . $page_name . '.php' . ' is not exists';
            throw_exception($error);
        }
    }

    /**
     * 显示页面Trace信息
     *
     * @return array
     */
    public static function showTrace()
    {
        $trace = array();
        //当前页面
        $trace[Language::get('nc_debug_current_page')] = $_SERVER['REQUEST_URI'] . '<br>';
        //请求时间
        $trace[Language::get('nc_debug_request_time')] = date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']) . '<br>';
        //系统运行时间
        $query_time = number_format((microtime(true) - StartTime), 3) . 's';
        $trace[Language::get('nc_debug_execution_time')] = $query_time . '<br>';
        //内存
        $trace[Language::get('nc_debug_memory_consumption')] = number_format(memory_get_usage() / 1024 / 1024, 2) . 'MB' . '<br>';
        //请求方法
        $trace[Language::get('nc_debug_request_method')] = $_SERVER['REQUEST_METHOD'] . '<br>';
        //通信协议
        $trace[Language::get('nc_debug_communication_protocol')] = $_SERVER['SERVER_PROTOCOL'] . '<br>';
        //用户代理
        $trace[Language::get('nc_debug_user_agent')] = $_SERVER['HTTP_USER_AGENT'] . '<br>';
        //会话ID
        $trace[Language::get('nc_debug_session_id')] = session_id() . '<br>';
        //执行日志
        $log = Log::read();
        $trace[Language::get('nc_debug_logging')] = count($log) ? count($log) . Language::get('nc_debug_logging_1') . '<br/>' . implode('<br/>', $log) : Language::get('nc_debug_logging_2');
        $trace[Language::get('nc_debug_logging')] = $trace[Language::get('nc_debug_logging')] . '<br>';
        //文件加载
        $files = get_included_files();
        $trace[Language::get('nc_debug_load_files')] = count($files) . str_replace("\n", '<br/>', substr(substr(print_r($files, true), 7), 0, -2)) . '<br>';
        return $trace;
    }

    /**
     * 生成flexigridXML数据
     * @param  array $param 待格式化数据
     * @return string xml格式的数据
     */
    public static function flexigridXML($param)
    {
        $page = $param['now_page'];
        $total = $param['total_num'];

        header("Expires: Mon, 26 Jul 1970 00:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
        header("Content-type: text/xml");

        $xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
        $xml .= "<rows>";
        $xml .= "<page>$page</page>";
        $xml .= "<total>$total</total>";
        if (empty($param['list'])) {
            $xml .= "<row id=''><cell></cell></row>";
        } else {
            foreach ($param['list'] as $k => $v) {
                $xml .= "<row id='".$k."'>";
                foreach ($v as $kk => $vv) {
                    $xml .= "<cell><![CDATA[".$v[$kk]."]]></cell>";
                }
                $xml .= "</row>";
            }
        }
        $xml .= "</rows>";
        echo $xml;
    }
}

/**
 * 日志类
 */
class Log
{

    /**
     * 数据库操作日志
     */
    const SQL = 'SQL';

    /**
     * 网站错误日志
     */
    const ERR = 'ERR';

    private static $log = array();

    /**
     * 记录日志
     * @param  string $message 日志内容
     * @param  string $level   日志等级
     * @return string          无
     */
    public static function record($message, $level = self::ERR)
    {   date_default_timezone_set("Asia/Shanghai");
        $now = date('Y-m-d H:i:s', time());
        switch ($level) {
            case self::SQL:
                self::$log[] = "[{$now}] {$level}: {$message}\r\n";
                break;

            case self::ERR:
                $log_file = BASE_DATA_PATH . '/log/' . date('Ymd', TIMESTAMP) . '.log';
                $url = $_SERVER['REQUEST_URI'] ? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF'];
                $url .= " ( con={$_GET['con']}&fun={$_GET['fun']} ) ";
                $content = "[{$now}] {$url}\r\n{$level}: {$message}\r\n";
                file_put_contents($log_file, $content, FILE_APPEND);
                break;
        }
    }

    /**
     * 读取日志
     * @return string 日志内容
     */
    public static function read()
    {
        return self::$log;
    }
}

/**
 * 图片类入口
 */
class Imager
{
    public function createImageFromPath($srcPath)
    {
        $imager = new Image($srcPath);
        
        return $imager;
    }

    public function createCaptcha($seccode, $width, $height)
    {
        $imager = new Captcha();
        return $imager->createCaptcha($seccode, $width, $height);
    }
}

class Image
{
    // 源资源
    private $srcResource;
    // 源图片路径
    private $srcPath;
    // 源图片宽度
    private $srcWidth;
    // 源图片高度
    private $srcHeight;
    // 源图片mime类型
    private $mimeType;
    // 目标资源
    private $destResource;
    // 目标图片路径
    private $destPath;
    // 目标图片宽度
    private $destWidth;
    // 目标图片高度
    private $destHeight;
    // 是否填充空白
    private $fill;
    // 缩放比例
    private $scale;
    // 系统配置
    private $config;

    // 构造函数
    public function __construct($srcPath)
    {

        $this->init($srcPath);
        
        $this->fill      = false;
        $this->srcPath   = $srcPath;
        $this->srcWidth  = round(imagesx($this->srcResource));
        $this->srcHeight = round(imagesy($this->srcResource));

        $this->config['imPath']       = C('thumb.imagemagickPath');
        $this->config['thumbType']    = C('thumb.type');
        $this->config['thumbQuality'] = 100;
    }

    private function init($srcPath)
    {

        if (!file_exists($srcPath)) {
            showDialog('The file does not exist', '', 'error');
        }

        if (!function_exists('imagecreatefromstring')) {
            showDialog('The GD image library is not installed', '', 'error');
        }

        @ini_set('memory_limit', '256M');

        $imageResource = imagecreatefromstring(file_get_contents($srcPath));

        if (!is_resource($imageResource)) {
            showDialog('The file supplied was not an image', '', 'error');
        }

        $this->mimeType = $this->getMimeTypeAtPath($srcPath);

        $this->srcResource = null;

        switch ($this->mimeType) {
            case 'image/gif';
                $this->srcResource = imagecreatefromgif($srcPath);
                break;
            case 'image/png';
                $this->srcResource = imagecreatefrompng($srcPath);
                break;
            case 'image/jpeg';
                $this->srcResource = imagecreatefromjpeg($srcPath);
                break;
        }
    }

    // 生成缩略图
    public function thumbnail($width, $height)
    {
        $this->getScale($width, $height);

        return $this;
    }

    public function resize($width, $height)
    {
        $this->getScale($width, $height);

        return $this;
    }

    public function save($destPath)
    {
        switch ($this->mimeType) {
            case 'image/gif';
                imagegif($this->destResource, $destPath);
                break;
            case 'image/png';
                imagepng($this->destResource, $destPath);
                break;
            case 'image/jpeg';
                imagejpeg($this->destResource, $destPath, $this->config['thumbQuality']);
                break;
        }
    }

    private function getScale($width, $height)
    {
        $this->destWidth = $width;
        $this->destHeight = $height;

        // 是否按比例
        $imgscaleto = ($this->destWidth == $this->destHeight);

        if ($this->srcWidth < $this->destWidth) {
            $this->destWidth = $this->srcWidth;
        }
        if ($this->srcHeight < $this->destHeight) {
            $this->destHeight = $this->srcHeight;
        }

        // 缩略图宽高比例
        $dest_scale = $this->destWidth / $this->destHeight;

        // 源图宽高比例
        $src_scale = $this->srcWidth / $this->srcHeight;

        if ($dest_scale <= $src_scale) {
            $this->destHeight = round($this->destWidth * ($this->srcHeight / $this->srcWidth));
        } else {
            $this->destWidth = round($this->destHeight * ($this->srcWidth / $this->srcHeight));
        }

        // 重新获取缩略图宽高比例
        $dest_scale = $this->destWidth / $this->destHeight;

        if ($imgscaleto) {
            $this->scale = $src_scale > 1 ? $this->destWidth : $this->destHeight;
        } else {
            $this->scale = 0;
        }

        // 宽度为360的时候允许填充空白
        if ($this->scale == 360) {
            $this->fill = true;
        }

        if ($this->scale > 0 && $this->fill) {
            $wh = $this->scale;

            if ($src_scale > 1) {
                $dest_y = ($wh - $wh / $src_scale) / 2;
                $dest_x = 0;
            } else {
                $dest_x = ($wh - $wh * $src_scale) / 2;
                $dest_y = 0;
            }

            //等比缩图(宽高最大为$wh)
            if ($dest_scale > 1) {
                $s_width = $wh;
                $s_height = $wh / $dest_scale;
            } else {
                $s_height = $wh;
                $s_width = $wh * $dest_scale;
            }

            $snewimg = imagecreatetruecolor($s_width, $s_height);
            imagecopyresampled($snewimg, $this->srcResource, 0, 0, 0, 0, $s_width, $s_height, $this->srcWidth, $this->srcHeight);
            $newimg = imagecreatetruecolor($wh, $wh);
            $white = imagecolorallocate($newimg, 255, 255, 255);
            imagefill($newimg, 0, 0, $white);
            imagecopymerge($newimg, $snewimg, $dest_x, $dest_y, 0, 0, $s_width, $s_height, 100);
            $foreground_color = imagecolorallocate($newimg, 255, 0, 0);
            $this->destResource = $newimg;
        } else {
            /**
             * 不裁图
             */
            if ($src_scale > 1) {
                // 宽大高小
                $this->destHeight = $this->destWidth * ($this->srcHeight) / ($this->srcWidth);
            } else {
                $this->destWidth = $this->destHeight * ($this->srcWidth) / ($this->srcHeight);
            }
            $newimg = imagecreatetruecolor($this->destWidth, $this->destHeight);
            imagecopyresampled($newimg, $this->srcResource, 0, 0, 0, 0, $this->destWidth, $this->destHeight, $this->srcWidth, $this->srcHeight);
            $this->destResource = $newimg;
        }
    }

    private function resizeToHeight($height)
    {
        $ratio = $height / $this->getHeight();
        $width = $this->getWidth() * $ratio;
        $this->resize($width, $height);
    }
 
    private function resizeToWidth($width)
    {
        $ratio = $width / $this->getWidth();
        $height = $this->getHeight() * $ratio;
        $this->resize($width, $height);
    }

    private function getWidth()
    {
        return imagesx($this->srcResource);
    }
    
    private function getHeight()
    {
        return imagesy($this->srcResource);
    }

    private function getMimeTypeAtPath($srcPath)
    {
    	
        $finfo = finfo_open(FILEINFO_MIME_TYPE); // 获取文件mime类型
       
        $mimeType = finfo_file($finfo, $srcPath);
        
        finfo_close($finfo);
        
        $this->mimeType = $mimeType;

        return $this->mimeType;
    }
}

/**
 * 验证码类
 */
class Captcha
{
    //图像对象
    private $ImgObj;
    //验证码字符数
    public $StringLength = 4;
    //基础字符串
    public $BaseString;
    //随机后的字符串
    public $RandString;
    //验证码图片宽度
    public $Width = 100;
    //验证码图片高度
    public $Height = 32;
    //验证码SESSION名称
    public $CaptchaSessionName = 'CaptchaCode';
    //是否加上干扰点
    public $IsDrawPIxel = true;
    //是否加上干扰线
    public $IsDrawLine = true;
    //干扰点个数
    public $PixelNumber = 150;
    //干扰线个数
    public $LineNumber = 4;
    //字体
    public $FontFace;
    //字体颜色
    public $FontColor;
    //字号
    public $FontSize = 20;
    //字符编码
    public $CharSet;
    //左侧留白长度
    public $LeftSpeace = 6;
    //字间距
    public $SpaceWidth = 3;

    public function __construct()
    {
        if (!function_exists("gd_info")) {
            exit('GD库未启用或不正常');
        } else {
            $this->ImgObj = @imagecreate($this->Width, $this->Height);
        }
    }

    //设定基础字串和启用SESSION
    public function setCaptchaStr()
    {
        if (!isset($_SESSION) && !ini_get('session.auto_start')) {
            session_start();
			print_r('222222');exit;
        }
        if (empty($this->BaseString)) {
            $this->BaseString = strtoupper(md5(time()));
        }
        $_SESSION[$this->CaptchaSessionName]=$this->RandString;
        $BackgroundColor = imagecolorallocate($this->ImgObj, 240, 240, 240);
    }

    //设置点状干扰
    public function setRandPixel()
    {
        for ($i=0; $i<$this->PixelNumber; $i++) {
            $PixelColor= imagecolorallocate($this->ImgObj, rand(0, 255), rand(0, 255), rand(0, 255));
            imagesetpixel($this->ImgObj, rand(0, $this->Width), rand(0, $this->Height), $PixelColor);
        }
    }

    //加入干扰线段
    public function setRandLine()
    {
        for ($i=0; $i<$this->LineNumber; $i++) {
            $LineColor = imagecolorallocate($this->ImgObj, rand(0, 255), rand(0, 255), rand(0, 255));
            imageline($this->ImgObj, rand(0, $this->Width), rand(0, $this->Height), rand(0, $this->Width), rand(0, $this->Height), $LineColor);
        }
    }

    //输出PNG图像
    public function createCaptcha($seccode, $width, $height)
    {
        $this->Width = $width;
        $this->Height = $height;
        $this->ImgObj = @imagecreate($this->Width, $this->Height);
        $this->FontFace = BASE_DATA_PATH . DS . "resource/font/arial.ttf";
        $this->BaseString = $seccode;
        $this->setCaptchaStr();
        $TotalLeter = strlen($this->BaseString);

        for ($i = 0; $i< $this->StringLength; $i++) {
            if (count($this->FontColor) < 3) {
                $RandFontColor = imagecolorallocate($this->ImgObj, rand(0, 255), rand(0, 128), rand(0, 255));
            } else {
                $RandFontColor = imagecolorallocate($this->ImgObj, $this->FontColor['0'], $this->FontColor['1'], $this->FontColor['2']);
            }

            if ($i == 0) {
                $FontX = $this->LeftSpeace;
            } else {
                $FontX += $this->FontSize+$this->SpaceWidth;
            }

            $SplitNum = ($this->CharSet == "CH") ? 3 : 1;

            $TmpChr = mb_strcut($this->BaseString, $i, 1, "utf-8");

            if (!empty($this->FontFace)) {
                $FontY = rand(23, 28);
                if ($this->Height > 26) {
                    $FontY = $this->FontSize + ($this->Height - $this->FontSize) / 2;
                }

                /*
                $TempChr = mb_strcut($this->BaseString, rand(0,$TotalLeter - $SplitNum), $SplitNum, 'utf-8');
                $this->RandString .= $TempChr;
                */

                imagettftext($this->ImgObj, $this->FontSize, rand(-8, 8), $FontX, $FontY, $RandFontColor, $this->FontFace, $TmpChr);
            } else {
                /*
                $TempChr = $this->BaseString{rand(0,$TotalLeter)};
                $this->RandString .= $TempChr;
                */

                imagestring($this->ImgObj, 9, $FontX, 2, $TmpChr, $RandFontColor);
            }
            
            //$_SESSION["$this->CaptchaSessionName"] = $this->RandString;
        }

        if ($this->IsDrawPIxel) {
            $this->setRandPixel();
        }

        if ($this->IsDrawLine) {
            $this->setRandLine();
        }
            
        $this->setRandLine();
        header("Content-type: image/png");
        imagepng($this->ImgObj);
        imagedestroy($this->ImgObj);
    }
}

class Lib
{

    public static function imager()
    {
        return new Imager();
    }

    public static function messager()
    {
        $email_host = trim(C('email_host'));
        $email_port = trim(C('email_port'));
        $email_addr = trim(C('email_addr'));
        $email_id   = trim(C('email_id'));
        $email_pass = trim(C('email_pass'));
        $site_title = trim(C('site_name'));

        $mailer = new \shopec\Lib\Messager\SmtpMailer(
            $email_host,
            $email_port,
            $email_id,
            $email_pass,
            $email_addr,
            $site_title
        );

        return $mailer;
    }
}

namespace shopec\Lib;

class StdArray
{

    public static function groupedValues($data, $cou_id, $sk_id)
    {
        foreach ($data as $k => $v) {
            $result[$v[$cou_id]][$v[$sk_id]]=$v[$sk_id];
        }
        return $result;
    }

    public static function indexedValues($data, $sku_id, $cou_id)
    {
        if (!empty($data)) {
            foreach ($data as $k => $v) {
                $result[$v[$sku_id]]=$v[$cou_id];
            }
        } else {
            $result = $data;
        }
        return $result;
    }

    public static function groupIndexed($data, $cou_id, $sk_id)
    {
        foreach ($data as $k => $v) {
            if ($sk_id == 'sku_id') {
                $result[$v[$cou_id]][$v[$sk_id]]=array('price'=>$v['price']);
            } elseif ($sk_id == 'xlevel') {
                $result[$v[$cou_id]][$v[$sk_id]]=$v;
            }
        }
        return $result;
    }

    public static function indexed($indexed_data, $indexed_xlevel)
    {
        if ($indexed_xlevel == 'xlevel') {
            foreach ($indexed_data as $indexed_k => $indexed_v) {
                $data[$indexed_v[$indexed_xlevel]]=$indexed_v;
            }
        } elseif ($indexed_xlevel == 'goods_id') {
            foreach ($indexed_data as $index_k => $index_v) {
                $data[$index_v[$indexed_xlevel]]=$index_v;
            }
        }
        return $data;
    }

    public static function uniqueValues($data, $sku_id)
    {
        return $data;
    }
}

namespace shopec\Lib\Messager;

class SmtpMailer
{
    /**
     * 邮件服务器
     */
    private $email_server;
    /**
     * 端口
     */
    private $email_port = 25;
    /**
     * 账号
     */
    private $email_user;
    /**
     * 密码
     */
    private $email_password;
    /**
     * 发送邮箱
     */
    private $email_from;
    /**
     * 间隔符
     */
    private $email_delimiter = "\n";
    /**
     * 站点名称
     */
    private $site_name;

    public function __construct($email_host, $email_port, $email_id, $email_pass, $email_add, $site_title)
    {
        $this->email_server = $email_host;
        $this->email_port = !empty($email_port) ? $email_port : $this->email_port;
        $this->email_user = $email_id;
        $this->email_password = $email_pass;
        $this->email_from = $email_add;
        $this->site_name = $site_title;
    }

    public function get($key)
    {
        if (!empty($this->$key)) {
            return $this->$key;
        } else {
            return false;
        }
    }

    public function set($key, $value)
    {
        if (!isset($this->$key)) {
            $this->$key = $value;
            return true;
        } else {
            return false;
        }
    }

    /**
     * 发送邮件
     *
     * @param string $email_to 发送对象邮箱地址
     * @param string $subject 邮件标题
     * @param string $message 邮件内容
     * @param string $from 页头来源内容
     * @return bool 布尔形式的返回结果
     */
    public function send($email_to, $subject, $message, $from = '')
    {

        if (empty($email_to)) {
            throw new Exception(L('test_email_to_fail'));
            return false;
        }

        $message = base64_encode($this->html($subject, $message));
        $email_to = $this->to($email_to);
        $header = $this->header($from);

        /**
         * 发送
         */
        if (!$fp = @fsockopen($this->email_server, $this->email_port, $errno, $errstr, 30)) {
            throw new Exception($this->email_server . ':' . $this->email_port . " CONNECT - Unable to connect to the SMTP server");
            return false;
        }
        stream_set_blocking($fp, true);

        $lastmessage = fgets($fp, 512);
        if (substr($lastmessage, 0, 3) != '220') {
            throw new Exception($this->email_server . ':' . $this->email_port . $lastmessage);
            return false;
        }

        fputs($fp, 'EHLO' . " WORLD\r\n");
        $lastmessage = fgets($fp, 512);
        if (substr($lastmessage, 0, 3) != 220 && substr($lastmessage, 0, 3) != 250) {
            throw new Exception($this->email_server . ':' . $this->email_port . " HELO/EHLO - $lastmessage");
            return false;
        } elseif (substr($lastmessage, 0, 3) == 220) {
            $lastmessage = fgets($fp, 512);
            if (substr($lastmessage, 0, 3) != 250) {
                throw new Exception($this->email_server . ':' . $this->email_port . " HELO/EHLO - $lastmessage");
                return false;
            }
        }
        while (1) {
            if (substr($lastmessage, 3, 1) != '-' || empty($lastmessage)) {
                break;
            }
            $lastmessage = fgets($fp, 512);
        }

        fputs($fp, "AUTH LOGIN\r\n");
        $lastmessage = fgets($fp, 512);
        if (substr($lastmessage, 0, 3) != 334) {
            throw new Exception($this->email_server . ':' . $this->email_port . " AUTH LOGIN - $lastmessage");
            return false;
        }

        fputs($fp, base64_encode($this->email_user) . "\r\n");
        $lastmessage = fgets($fp, 512);
        if (substr($lastmessage, 0, 3) != 334) {
            throw new Exception($this->email_server . ':' . $this->email_port . " USERNAME - $lastmessage");
            return false;
        }

        fputs($fp, base64_encode($this->email_password) . "\r\n");
        $lastmessage = fgets($fp, 512);
        if (substr($lastmessage, 0, 3) != 235) {
            throw new Exception($this->email_server . ':' . $this->email_port . " PASSWORD - $lastmessage");
            return false;
        }

        fputs($fp, "MAIL FROM: <" . preg_replace("/.*\<(.+?)\>.*/", "\\1", $this->email_from) . ">\r\n");
        $lastmessage = fgets($fp, 512);
        if (substr($lastmessage, 0, 3) != 250) {
            fputs($fp, "MAIL FROM: <" . preg_replace("/.*\<(.+?)\>.*/", "\\1", $this->email_from) . ">\r\n");
            $lastmessage = fgets($fp, 512);
            if (substr($lastmessage, 0, 3) != 250) {
                throw new Exception($this->email_server . ':' . $this->email_port . " MAIL FROM - $lastmessage");
                return false;
            }
        }

        fputs($fp, "RCPT TO: <" . preg_replace("/.*\<(.+?)\>.*/", "\\1", $email_to) . ">\r\n");
        $lastmessage = fgets($fp, 512);
        if (substr($lastmessage, 0, 3) != 250) {
            fputs($fp, "RCPT TO: <" . preg_replace("/.*\<(.+?)\>.*/", "\\1", $email_to) . ">\r\n");
            $lastmessage = fgets($fp, 512);
            throw new Exception($this->email_server . ':' . $this->email_port . " RCPT TO - $lastmessage");
            return false;
        }

        fputs($fp, "DATA\r\n");
        $lastmessage = fgets($fp, 512);
        if (substr($lastmessage, 0, 3) != 354) {
            throw new Exception($this->email_server . ':' . $this->email_port . " DATA - $lastmessage");
            return false;
        }

        fputs($fp, "Date: " . gmdate('r') . "\r\n");
        fputs($fp, "To: " . $email_to . "\r\n");
        fputs($fp, "Subject: " . $subject . "\r\n");
        fputs($fp, $header . "\r\n");
        fputs($fp, "\r\n\r\n");
        fputs($fp, "$message\r\n.\r\n");
        $lastmessage = fgets($fp, 512);
        if (substr($lastmessage, 0, 3) != 250) {
            throw new Exception($this->email_server . ':' . $this->email_port . " END - $lastmessage");
        }
        fputs($fp, "QUIT\r\n");
        return true;
    }

    public function send_sys_email($email_to, $subject, $message)
    {
        $this->set('email_server', C('email_host'));
        $this->set('email_port', C('email_port'));
        $this->set('email_user', C('email_id'));
        $this->set('email_password', C('email_pass'));
        $this->set('email_from', C('email_addr'));
        $this->set('site_name', C('site_name'));
        $result = $this->send($email_to, $subject, $message);
        return $result;
    }

    /**
     * 内容:邮件主体
     *
     * @param string $subject 邮件标题
     * @param string $message 邮件内容
     * @return string 字符串形式的返回结果
     */
    private function html($subject, $message)
    {
        $message = preg_replace("/href\=\"(?!http\:\/\/)(.+?)\"/i", 'href="' . SHOP_SITE_URL . '\\1"', $message);
        $tmp .= "<html><head>";
        $tmp .= '<meta http-equiv="Content-Type" content="text/html; charset=' . CHARSET . '">';
        $tmp .= "<title>" . $subject . "</title>";
        $tmp .= "</head><body>" . $message . "</body></html>";
        $message = $tmp;
        unset($tmp);
        return $message;
    }

    /**
     * 发送对象邮件地址
     *
     * @param string $email_to 发送地址
     * @return string 字符串形式的返回结果
     */
    private function to($email_to)
    {
        $email_to = preg_match('/^(.+?) \<(.+?)\>$/', $email_to, $mats) ? ($this->email_user ? '=?' . CHARSET . '?B?' . base64_encode($mats[1]) . "?= <$mats[2]>" : $mats[2]) : $email_to;
        return $email_to;
    }

    /**
     * 内容:邮件标题
     *
     * @param string $subject 邮件标题
     * @return string 字符串形式的返回结果
     */
    private function subject($subject)
    {
        $subject = '=?' . CHARSET . '?B?' . base64_encode(preg_replace("/[\r|\n]/", '', '[' . $this->site_name . '] ' . $subject)) . '?=';
        return $subject;
    }

    /**
     * 内容:邮件主体内容
     *
     * @param string $message 邮件主体内容
     * @return string 字符串形式的返回结果
     */
    private function message($message)
    {
        $message = chunk_split(base64_encode(str_replace("\n", "\r\n", str_replace("\r", "\n", str_replace("\r\n", "\n", str_replace("\n\r", "\r", $message))))));
        return $message;
    }

    /**
     * 内容:邮件页头
     *
     * @param string $from 邮件页头来源
     * @return array $rs_row 返回数组形式的查询结果
     */
    private function header($from = '')
    {
        if ($from == '') {
            $from = '=?' . CHARSET . '?B?' . base64_encode($this->site_name) . "?= <" . $this->email_from . ">";
        } else {
            $from = preg_match('/^(.+?) \<(.+?)\>$/', $from, $mats) ? '=?' . CHARSET . '?B?' . base64_encode($mats[1]) . "?= <$mats[2]>" : $from;
        }
        $header = "From: $from{$this->email_delimiter}";
        $header .= "X-Priority: 3{$this->email_delimiter}";
        $header .= "X-Mailer: WoooMall {$this->email_delimiter}";
        $header .= "MIME-Version: 1.0{$this->email_delimiter}";
        $header .= "Content-type: text/html; ";
        $header .= "charset=" . CHARSET . "{$this->email_delimiter}";
        $header .= "Content-Transfer-Encoding: base64{$this->email_delimiter}";
        $header .= 'Message-ID: <' . gmdate('YmdHs') . '.' . substr(md5($message . microtime()), 0, 6) . rand(100000, 999999) . '@' . $_SERVER['HTTP_HOST'] . ">{$this->email_delimiter}";
        return $header;
    }

    /**
     * 错误信息记录
     *
     * @param string $msg 错误信息
     * @return bool 布尔形式的返回结果
     */
    private function resultLog($msg)
    {
        if (DeBug === true) {
            $fp = fopen('txt.txt', 'a+');
            fwrite($fp, $msg);
            fclose($fp);
            return true;
        } else {
            return true;
        }
    }
}

class Exception extends \Exception
{

}
