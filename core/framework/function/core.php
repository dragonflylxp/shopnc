<?php
/**
 * 公共方法
 *
 * 公共方法
 *
 * @package    function
 * @copyright  Copyright (c) 2007-2016 shopec Inc. (http://www.shopec.net)
 * @license    http://www.shopec.net/
 * @link       http://www.shopec.net/
 * @author     shopec Team
 * @since      File available since Release v1.1
 */
use shopec\Tpl;


defined('Inshopec') or exit('Access Invalid!');

/**
 * 产生验证码
 *
 * @param string $nchash 哈希数
 * @return string
 */
function makeSeccode($nchash){
    $seccode = random(6, 1);
    $seccodeunits = '';

    $s = sprintf('%04s', base_convert($seccode, 10, 23));
    $seccodeunits = 'ABCEFGHJKMPRTVXY2346789';
    if($seccodeunits) {
        $seccode = '';
        for($i = 0; $i < 4; $i++) {
            $unit = ord($s{$i});
            $seccode .= ($unit >= 0x30 && $unit <= 0x39) ? $seccodeunits[$unit - 0x30] : $seccodeunits[$unit - 0x57];
        }
    }
    setNcCookie('seccode', encrypt(strtoupper($seccode)."\t".time(),MD5_KEY),3600);
    return $seccode;
}


function unescape($str)
{
	$ret = '';
	$len = strlen($str);

	for ($i = 0; $i < $len; $i++) {
		if (($str[$i] == '%') && ($str[$i + 1] == 'u')) {
			$val = hexdec(substr($str, $i + 2, 4));

			if ($val < 127) {
				$ret .= chr($val);
			}
			else if ($val < 2048) {
				$ret .= chr(192 | ($val >> 6)) . chr(128 | ($val & 63));
			}
			else {
				$ret .= chr(224 | ($val >> 12)) . chr(128 | (($val >> 6) & 63)) . chr(128 | ($val & 63));
			}

			$i += 5;
		}
		else if ($str[$i] == '%') {
			$ret .= urldecode(substr($str, $i, 3));
			$i += 2;
		}
		else {
			$ret .= $str[$i];
		}
	}

	return $ret;
}

/**
 * by:511613932
 */
function create_html($out = '', $cache_id = 0, $cachename = '', $suffix = '', $topic_type = 0)
{
	$smarty = new cls_template();
	$smarty->cache_lifetime = $_CFG['cache_time'];

	if ($topic_type == 1) {
		$smarty->cache_dir = BASE_ROOT_PATH .DS. 'data/topic';
		$seller_tem = 'topic_' . $cache_id;
	}
	else if ($topic_type == 2) {
		$smarty->cache_dir = BASE_ROOT_PATH .DS. 'data';
		$seller_tem = '';
	}
	else if ($topic_type == 3) {
		$smarty->cache_dir = BASE_ROOT_PATH .DS. 'data/home_Templates';
		$seller_tem = $GLOBALS['_CFG']['template'];
	}
	else {
		$smarty->cache_dir = BASE_ROOT_PATH .DS. 'data/seller_templates';

		if (0 < $cache_id) {
			$seller_tem = 'seller_tem_' . $cache_id;
		}
		else {
			$seller_tem = 'seller_tem';
		}
	}

	if ($topic_type != 2) {
		$suffix = $suffix . '/temp';
	}

	$back = '';

	if ($out) {
		$out = str_replace("\r", '', $out);

		while (strpos($out, "\n\n") !== false) {
			$out = str_replace("\n\n", "\n", $out);
		}

		$hash_dir = $smarty->cache_dir . '/' . $seller_tem . '/' . $suffix;

		if (!is_dir($hash_dir)) {
			mkdir($hash_dir, 511, true);
		}

		if ($cachename) {
			$files = explode('.', $cachename);
			$files_count = count($files) - count($files) - 1;
			$suffix_name = $files[$files_count];

			if (2 < count($files)) {
				$path = count($files) - 1;
				$name = '';

				if ($files[$path]) {
					foreach ($files[$path] as $row) {
						$name .= $row . '.';
					}

					$name = substr($name, 0, -1);
				}

				$file_path = explode('/', $name);

				if (2 < $file_path) {
					$path = count($file_path) - 1;
					$cachename = $file_path[$path];
				}
				else {
					$cachename = $file_path[0];
				}
			}
			else {
				$cachename = $files[0];
			}

			$file_put = write_static_file_cache($cachename, $out, $suffix_name, $hash_dir . '/');
			
		}
		else {
			$file_put = false;
		}

		if ($file_put === false) {
			trigger_error('can\'t write:' . $hash_dir . '/' . $cachename);
			$back = '';
		}
		else {
			$back = $cachename;
		}

		$smarty->template = array();
	}
	else {
		$back = '';
	}

	return $back;
}
/**
 * by:511613932
 */
function setRewrite($initUrl = '', $params = '', $append = '', $page = 0, $keywords = '', $size = 0)
{
	$url = false;
	$rewrite = intval($GLOBALS['_CFG']['rewrite']);
	$baseUrl = basename($initUrl);
	$urlArr = explode('?', $baseUrl);
	if ($rewrite && !empty($urlArr[0]) && strpos($urlArr[0], '.php')) {
		$app = str_replace('.php', '', $urlArr[0]);
		@parse_str($urlArr[1], $queryArr);

		if (isset($queryArr['id'])) {
			$id = intval($queryArr['id']);
		}

		if (!empty($id)) {
			switch ($app) {
			case 'history_list':
				$idType = array('cid' => $id);
				break;

			case 'category':
				$idType = array('cid' => $id);
				break;

			case 'goods':
				$idType = array('gid' => $id);
				break;

			case 'presale':
				$idType = array('presaleid' => $id);
				break;

			case 'brand':
				$idType = array('bid' => $id);
				break;

			case 'brandn':
				$idType = array('bid' => $id);
				break;

			case 'article_cat':
				$idType = array('acid' => $id);
				break;

			case 'article':
				$idType = array('aid' => $id);
				break;

			case 'merchants':
				$idType = array('mid' => $id);
				break;

			case 'merchants_index':
				$idType = array('urid' => $id);
				break;

			case 'group_buy':
				$idType = array('gbid' => $id);
				break;

			case 'seckill':
				$idType = array('secid' => $id);
				break;

			case 'auction':
				$idType = array('gbid' => $id);
				break;

			case 'snatch':
				$idType = array('sid' => $id);
				break;

			case 'exchange':
				$idType = array('cid' => $id);
				break;

			case 'exchange_goods':
				$idType = array('gid' => $id);
				break;

			case 'gift_gard':
				$idType = array('cid' => $id);
				break;

			default:
				$idType = array('id' => '');
				break;
			}
		}
		else {
			switch ($app) {
			case 'index':
				$idType = NULL;
				break;

			case 'brand':
				$idType = NULL;
				break;

			case 'brandn':
				$idType = NULL;
				break;

			case 'group_buy':
				$idType = NULL;
				break;

			case 'seckill':
				$idType = NULL;
				break;

			case 'auction':
				$idType = NULL;
				break;

			case 'package':
				$idType = NULL;
				break;

			case 'activity':
				$idType = NULL;
				break;

			case 'snatch':
				$idType = NULL;
				break;

			case 'exchange':
				$idType = NULL;
				break;

			case 'store_street':
				$idType = NULL;
				break;

			case 'presale':
				$idType = NULL;
				break;

			case 'categoryall':
				$idType = NULL;
				break;

			case 'merchants':
				$idType = NULL;
				break;

			case 'merchants_index':
				$idType = NULL;
				break;

			case 'message':
				$idType = NULL;
				break;

			case 'wholesale':
				$idType = NULL;
				break;

			case 'gift_gard':
				$idType = NULL;
				break;

			case 'history_list':
				$idType = NULL;
				break;

			case 'merchants_steps':
				$idType = NULL;
				break;

			case 'merchants_steps_site':
				$idType = NULL;
				break;

			default:
				$idType = array('id' => '');
				break;
			}
		}

		if ($idType == NULL) {
			$url = $GLOBALS['_CFG']['site_domain'] . $app . '.html';
		}
		else {
			$params = (empty($params) ? $idType : $params);
			$url = build_uri($app, $params, $append, $page, $keywords, $size);
		}
	}

	if ($url) {
		return $url;
	}
	else {
		if ((strpos($initUrl, 'http://') === false) && (strpos($initUrl, 'https://') === false)) {
			return $GLOBALS['_CFG']['site_domain'] . $initUrl;
		}
		else {
			return $initUrl;
		}
	}
}
 
/*获取模板by:511613932*/
function get_seller_templates($ru_id = 0, $type = 0, $tem = '', $pre_type = 0) {
	if ($type == 0) {
		$seller_templates = 'pc_page';
	}
	else {
		$seller_templates = 'pc_html';
	}

	$arr = '';

	if ($tem == '') {
		$store_visual_model = Model('visual');
		$arr['tem'] = $store_visual_model->seller_templates($ru_id);
	
		$dir = BASE_DATA_PATH . '/seller_templates/seller_tem_' . $ru_id . '/store_tpl_1';
		if (($arr['tem'] == '') || !file_exists($dir . '/pc_page.php')) {
			$file_html = BASE_DATA_PATH . '/seller_templates/seller_tem/store_tpl_1';

			if (!is_dir($dir)) {
				mkdir($dir, 511, true);
			}

			recurse_copy($file_html, $dir);
			$condition = array();
    	    $condition['store_id'] = $ru_id;
    	    $update['seller_templates']='store_tpl_1';
			$store_visual_model->getleft_up($condition,$update);
			$arr['tem'] = 'store_tpl_1';
		}
	}
	else {
		$arr['tem'] = $tem;
	}

	$arr['is_temp'] = 0;
	$filename = BASE_DATA_PATH . '/seller_templates' . '/seller_tem_' . $ru_id . '/' . $arr['tem'] . '/' . $seller_templates . '.php';

	if ($pre_type == 1) {
		$pre_file = BASE_DATA_PATH . '/seller_templates' . '/seller_tem_' . $ru_id . '/' . $arr['tem'] . '/temp';

		if (is_dir($pre_file)) {
			$filename = $pre_file . '/' . $seller_templates . '.php';
			$arr['is_temp'] = 1;
		}
	}

	$arr['out'] = get_html_file($filename);
	return $arr;
}
/**
 * by:511613932
 */
function seller_page($list, $nowpage, $show = '10')
{
	$arr = array();

	if ($list['page_count'] < $show) {
		$show = $list['page_count'];
	}

	if (($show % 2) == 0) {
		$begin = $nowpage - ceil($show / 2);
		$end = $nowpage + floor($show / 2);
	}
	else {
		$begin = $nowpage - floor($show / 2);
		$end = $nowpage + ceil($show / 2);
	}

	if (1 < $show) {
		if (((ceil($show / 2) + 1) < $nowpage) && ($nowpage <= $list['page_count'] - ceil($show / 2))) {
			for ($i = $begin; $i < $end; $i++) {
				$arr[$i] = $i;
			}
		}
		else {
			if (((ceil($show / 2) + 1) < $nowpage) && (($list['page_count'] - $show - 1) < $nowpage)) {
				for ($i = $list['page_count'] - $show - 1; $i <= $list['page_count']; $i++) {
					$arr[$i] = $i;
				}
			}
			else {
				for ($i = 1; $i <= $show; $i++) {
					$arr[$i] = $i;
				}
			}
		}
	}
	else {
		$arr[1] = 1;
	}

	return $arr;
}
/**
 * by:511613932
 */
function is_admin_seller_path()
{
	$self = explode('/', substr(PHP_SELF, 1));
	$count = count($self);
	$return = 3;
	$admin_id = 0;

	if (1 < $count) {
		$real_path = $self[$count - 2];

		if ($real_path == ADMIN_PATH) {
			$return = 1;
		}
		else if ($real_path == SELLER_PATH) {
			$return = 2;
		}
		else if ($real_path == STORES_PATH) {
			$return = 0;
		}
	}

	return $return;
}
/**
 * by:511613932
 */

/**
 * by:511613932
 */
function page_and_size($filter, $type = 0)
{
	if ($type == 1) {
		$filter['page_size'] = 10;
	}
	else if ($type == 2) {
		$filter['page_size'] = 14;
	}
	else if ($type == 3) {
		$filter['page_size'] = 21;
	}
	else if ($type == 4) {
		$filter['page_size'] = 18;
	}
	else {
		if (isset($_REQUEST['page_size']) && (0 < intval($_REQUEST['page_size']))) {
			$filter['page_size'] = intval($_REQUEST['page_size']);
		}
		else {
			if (isset($_COOKIE['ECSCP']['page_size']) && (0 < intval($_COOKIE['ECSCP']['page_size']))) {
				$filter['page_size'] = intval($_COOKIE['ECSCP']['page_size']);
			}
			else {
				$filter['page_size'] = 15;
			}
		}
	}

	$filter['page'] = empty($_REQUEST['page']) || (intval($_REQUEST['page']) <= 0) ? 1 : intval($_REQUEST['page']);
	$filter['page_count'] = !empty($filter['record_count']) && (0 < $filter['record_count']) ? ceil($filter['record_count'] / $filter['page_size']) : 1;

	if ($filter['page_count'] < $filter['page']) {
		$filter['page'] = $filter['page_count'];
	}

	$filter['start'] = ($filter['page'] - 1) * $filter['page_size'];
	return $filter;
}

/**
 * by:511613932
 */
function gmtime()
{
	return time() - date('Z');
}
/**
 * by:511613932
 */
function local_date($format, $time = NULL)
{
	$timezone = '8';

	if ($time === NULL) {
		$time = gmtime();
	}
	else if ($time <= 0) {
		return '';
	}

	$time += $timezone * 3600;
	return date($format, $time);
}

/**
 * by:511613932
 */
function object_to_array($obj)
{
	$_arr = (is_object($obj) ? get_object_vars($obj) : $obj);

	if ($_arr) {
		foreach ($_arr as $key => $val) {
			$val = (is_array($val) || is_object($val) ? object_to_array($val) : $val);
			$arr[$key] = $val;
		}
	}
	else {
		$arr = array();
	}

	return $arr;
}
/**
 * by:511613932
 */
function addslashes_deep_obj($obj)
{
	if (is_object($obj) == true) {
		foreach ($obj as $key => $val) {
			$obj->$key = addslashes_deep($val);
		}
	}
	else {
		$obj = addslashes_deep($obj);
	}

	return $obj;
}
/**
 * by:511613932
 */
function move_upload_file($file_name, $target_name = '')
{
	if (function_exists('move_uploaded_file')) {
		if (move_uploaded_file($file_name, $target_name)) {
			@chmod($target_name, 493);
			return true;
		}
		else if (copy($file_name, $target_name)) {
			@chmod($target_name, 493);
			return true;
		}
	}
	else if (copy($file_name, $target_name)) {
		@chmod($target_name, 493);
		return true;
	}

	return false;
}
/**
 * by:511613932
 */
function make_dir($folder)
{
	$reval = false;

	if (!file_exists($folder)) {
		@umask(0);
		preg_match_all('/([^\\/]*)\\/?/i', $folder, $atmp);
		$base = ($atmp[0][0] == '/' ? '/' : '');

		foreach ($atmp[1] as $val) {
			if ('' != $val) {
				$base .= $val;
				if (('..' == $val) || ('.' == $val)) {
					$base .= '/';
					continue;
				}
			}
			else {
				continue;
			}

			$base .= '/';

			if (!file_exists($base)) {
				if (@mkdir(rtrim($base, '/'), 511)) {
					@chmod($base, 511);
					$reval = true;
				}
			}
		}
	}
	else {
		$reval = is_dir($folder);
	}

	clearstatcache();
	return $reval;
}
/**
 * by:511613932
 */
function check_file_type($filename, $realname = '', $limit_ext_types = '')
{
	if ($realname) {
		$extname = strtolower(substr($realname, strrpos($realname, '.') + 1));
	}
	else {
		$extname = strtolower(substr($filename, strrpos($filename, '.') + 1));
	}

	if ($limit_ext_types && (stristr($limit_ext_types, '|' . $extname . '|') === false)) {
		return '';
	}

	$str = $format = '';
	$file = @fopen($filename, 'rb');

	if ($file) {
		$str = @fread($file, 1024);
		@fclose($file);
	}
	else if (stristr($filename, BASE_ROOT_PATH) === false) {
		if (($extname == 'jpg') || ($extname == 'jpeg') || ($extname == 'gif') || ($extname == 'png') || ($extname == 'doc') || ($extname == 'xls') || ($extname == 'txt') || ($extname == 'zip') || ($extname == 'rar') || ($extname == 'ppt') || ($extname == 'pdf') || ($extname == 'rm') || ($extname == 'mid') || ($extname == 'wav') || ($extname == 'bmp') || ($extname == 'swf') || ($extname == 'chm') || ($extname == 'sql') || ($extname == 'cert') || ($extname == 'pptx') || ($extname == 'xlsx') || ($extname == 'docx')) {
			$format = $extname;
		}
	}
	else {
		return '';
	}

	if (($format == '') && (2 <= strlen($str))) {
		if ((substr($str, 0, 4) == 'MThd') && ($extname != 'txt')) {
			$format = 'mid';
		}
		else {
			if ((substr($str, 0, 4) == 'RIFF') && ($extname == 'wav')) {
				$format = 'wav';
			}
			else if (substr($str, 0, 3) == "\xff\xd8\xff") {
				$format = 'jpg';
			}
			else {
				if ((substr($str, 0, 4) == 'GIF8') && ($extname != 'txt')) {
					$format = 'gif';
				}
				else if (substr($str, 0, 8) == "�PNG\r\n\x1a\n") {
					$format = 'png';
				}
				else {
					if ((substr($str, 0, 2) == 'BM') && ($extname != 'txt')) {
						$format = 'bmp';
					}
					else {
						if (((substr($str, 0, 3) == 'CWS') || (substr($str, 0, 3) == 'FWS')) && ($extname != 'txt')) {
							$format = 'swf';
						}
						else if (substr($str, 0, 4) == "\xd0\xcf\x11\xe0") {
							if ((substr($str, 512, 4) == "\xec\xa5\xc1\x00") || ($extname == 'doc')) {
								$format = 'doc';
							}
							else {
								if ((substr($str, 512, 2) == "\t\x08") || ($extname == 'xls')) {
									$format = 'xls';
								}
								else {
									if ((substr($str, 512, 4) == "\xfd\xff\xff\xff") || ($extname == 'ppt')) {
										$format = 'ppt';
									}
								}
							}
						}
						else if (substr($str, 0, 4) == "PK\x03\x04") {
							if ((substr($str, 512, 4) == "\xec\xa5\xc1\x00") || ($extname == 'docx')) {
								$format = 'docx';
							}
							else {
								if ((substr($str, 512, 2) == "\t\x08") || ($extname == 'xlsx')) {
									$format = 'xlsx';
								}
								else {
									if ((substr($str, 512, 4) == "\xfd\xff\xff\xff") || ($extname == 'pptx')) {
										$format = 'pptx';
									}
									else {
										$format = 'zip';
									}
								}
							}
						}
						else {
							if ((substr($str, 0, 4) == 'Rar!') && ($extname != 'txt')) {
								$format = 'rar';
							}
							else if (substr($str, 0, 4) == '%PDF') {
								$format = 'pdf';
							}
							else if (substr($str, 0, 3) == "0\x82\n") {
								$format = 'cert';
							}
							else {
								if ((substr($str, 0, 4) == 'ITSF') && ($extname != 'txt')) {
									$format = 'chm';
								}
								else if (substr($str, 0, 4) == '.RMF') {
									$format = 'rm';
								}
								else if ($extname == 'sql') {
									$format = 'sql';
								}
								else if ($extname == 'txt') {
									$format = 'txt';
								}
							}
						}
					}
				}
			}
		}
	}

	if ($limit_ext_types && (stristr($limit_ext_types, '|' . $format . '|') === false)) {
		$format = '';
	}

	return $format;
}
/**
 * by:511613932
 */
function sys_msg($msg_detail, $msg_type = 0, $links = array(), $auto_redirect = true, $is_ajax = false)
{
	if (count($links) == 0) {
		$links[0]['text'] = '后退';
		$links[0]['href'] = 'javascript:history.go(-1)';
	}

	assign_query_info();
	$smarty = new cls_template();
	$smarty->template_dir = BASE_ROOT_PATH.DS.DIR_SHOP.'/templates/'.TPL_NAME;
	$smarty->compile_dir = BASE_ROOT_PATH.DS.DIR_SHOP.'/templates/'.TPL_NAME;
	$smarty->assign('ur_here', '系统信息');
	$smarty->assign('msg_detail', $msg_detail);
	$smarty->assign('msg_type', $msg_type);
	$smarty->assign('links', $links);
	$smarty->assign('default_url', $links[0]['href']);
	$smarty->assign('auto_redirect', $auto_redirect);
	$smarty->assign('is_ajax', $is_ajax);
	$smarty->display('message.dwt');
	exit();
}
/**
 * by:511613932
 */
function addslashes_deep($value)
{
	if (empty($value)) {
		return $value;
	}
	else {
		return is_array($value) ? array_map('addslashes_deep', $value) : addslashes($value);
	}
}
/**
 * by:511613932
 */
function json_str_iconv($str)
{
	if (EC_CHARSET != 'utf-8') {
		if (is_string($str)) {
			return addslashes(stripslashes(ecs_iconv('utf-8', EC_CHARSET, $str)));
		}
		else if (is_array($str)) {
			foreach ($str as $key => $value) {
				$str[$key] = json_str_iconv($value);
			}

			return $str;
		}
		else if (is_object($str)) {
			foreach ($str as $key => $value) {
				$str->$key = json_str_iconv($value);
			}

			return $str;
		}
		else {
			return $str;
		}
	}

	return $str;
}
/**
 * by:511613932
 */
function ecs_iconv($source_lang, $target_lang, $source_string = '')
{
	static $chs;
	if (($source_lang == $target_lang) || ($source_string == '') || (preg_match("/[\x80-\xff]+/", $source_string) == 0)) {
		return $source_string;
	}

	if ($chs === NULL) {

		$chs = new Chinese(BASE_ROOT_PATH);
	}

	return $chs->Convert($source_lang, $target_lang, $source_string);
}
/**
 * 生成模板
 * by：511613932
 */
function get_html_file($name)
{
	$smarty = new cls_template();

	if (file_exists($name)) {
		$smarty->_current_file = $name;
		$name = read_static_flie_cache($name);
		
		$source = $smarty->fetch_str($name);
	}
	else {
		$source = '';
	}

	return $source;
}
/**
 * 读取模板
 * by：511613932
 */
function read_static_flie_cache($cache_name = '', $suffix = '', $path = '')
{
	if (empty($suffix)) {
	}

	$data = '';

	if ((DEBUG_MODE & 2) == 2) {
		return false;
	}

	static $result = array();

	if (!empty($result[$cache_name])) {
		return $result[$cache_name];
	}


		if (empty($suffix)) {
			$cache_file_path = $cache_name;
		}
		else {
			$cache_file_path = $path . $cache_name . '.' . $suffix;
		}

		if (file_exists($cache_file_path)) {
			$get_data = file_get_contents($cache_file_path);


				return $get_data;
			
		}else{
			return '';
		}

}
/**
 * by:511613932
 */
function del_DirAndFile($dirName)
{
	if (is_dir($dirName)) {
		if ($handle = opendir($dirName)) {
			while (false !== ($item = readdir($handle))) {
				if (($item != '.') && ($item != '..')) {
					if (is_dir($dirName . '/' . $item)) {
						del_DirAndFile($dirName . '/' . $item);
					}
					else {
						unlink($dirName . '/' . $item);
					}
				}
			}

			closedir($handle);
			return rmdir($dirName);
		}
	}
	else {
		return true;
	}
}
/**
 * by：511613932
 */
function arr_foreach($multi)
{
	$arr = array();

	foreach ($multi as $key => $val) {
		if (is_array($val)) {
			$arr = array_merge($arr, arr_foreach($val));
		}
		else {
			$arr[] = $val;
		}
	}

	return $arr;
}
/**
 * by：511613932
 */
function get_select_category($cat_id = 0, $relation = 0, $self = true, $user_id = 0, $table = 'category')
{
	$tablePre = C('tablepre');
	static $cat_list = array();
	$cat_list[] = intval($cat_id);

	if ($relation == 0) {
		return $cat_list;
	}
	else if ($relation == 1) {
		$sql = 'SELECT parent_id FROM ' . $tablePre.$table . ' WHERE cat_id=\'' . $cat_id . '\' ';
		$parent_id = Model()->getOne($sql);

		if (!empty($parent_id)) {
			get_select_category($parent_id, $relation, $self);
		}

		if ($self == false) {
			unset($cat_list[0]);
		}

		$cat_list[] = 0;
		return array_reverse(array_unique($cat_list));
	}
	else if ($relation == 2) {
		$sql = 'SELECT cat_id FROM ' . $tablePre.$table . ' WHERE parent_id=\'' . $cat_id . '\' ';
		$child_id = Model()->getCol($sql);

		if (!empty($child_id)) {
			foreach ($child_id as $key => $val) {
				get_select_category($val, $relation, $self);
			}
		}

		if ($self == false) {
			unset($cat_list[0]);
		}

		return $cat_list;
	}
}
/**
 * by：511613932
 */
function get_array_category_info($arr = array(), $table = 'category')
{
	$tablePre = C('tablepre');
	if ($arr) {
		$arr = get_del_str_comma($arr);
		$sql = ' SELECT cat_id, cat_name FROM ' . $tablePre.$table . ' WHERE cat_id ' . db_create_in($arr);
		$category_list = Model()->getAll1($sql);

		foreach ($category_list as $key => $val) {
			$category_list[$key]['url'] = build_uri($table, array('cid' => $val['cat_id']), $val['cat_name']);
		}

		return $category_list;
	}
	else {
		return false;
	}
}
/**
 * by:511613932
 */
function insert_select_category($cat_id = 0, $child_cat_id = 0, $cat_level = 0, $select_jsId = 'cat_parent_id', $type = 0, $table = 'category', $seller_shop_cat = array())
{
	$smarty = new cls_template();
	$smarty->template_dir = BASE_ROOT_PATH.DS.DIR_SHOP.'/templates/'.TPL_NAME;
	$smarty->compile_dir = BASE_ROOT_PATH.DS.DIR_SHOP.'/templates/'.TPL_NAME;
	$cat_level = $cat_level + 1;
	$child_category = cat_list($cat_id, 0, 0, $table, $seller_shop_cat, $cat_level);
	$smarty->assign('child_category', $child_category);
	$smarty->assign('child_cat_id', $child_cat_id);
	$smarty->assign('cat_level', $cat_level);
	$smarty->assign('select_jsId', $select_jsId);
	$smarty->assign('type', $type);
	$html = $smarty->fetch('get_select_category.dwt');
	return $html;
}
/**
 * by:511613932
 */
function cat_list($cat_id = 0, $type = 0, $getrid = 0, $table = 'goods_class', $seller_shop_cat = array(), $cat_level = 0, $user_id = 0, $deep = 1)
{
	
	$tablePre = C('tablepre');
    if(intval($_REQUEST['deep'])){
    	$deep = intval($_REQUEST['deep']);
    }
    
    if ($cat_id <= 0 || $deep <= 0 || $deep >= 4) {
        return 0;
    }	
    
//	if ($getrid == 0) {
//		$select = ', stc_name';
//
//		if ($table == 'merchants_category') {
//			$select .= ', store_id';
//		}
//		else if ($table == 'store_goods_class') {
//			$select .= ', stc_state, stc_parent_id, store_id, stc_sort';
//		}
//	}
//	else {
//		$select = '';
//	}
//
//	$where = '';
//
//	if ($seller_shop_cat) {
//		if ($seller_shop_cat['parent'] && $seller_shop_cat['parent'] && ($cat_level < 3)) {
//			$seller_shop_cat['parent'] = get_del_str_comma($seller_shop_cat['parent']);
//			$where .= ' AND stc_id IN(' . $seller_shop_cat['parent'] . ')';
//		}
//	}
//
//	$user_id = $_SESSION['store_id'];
//	$where .= ' AND store_id = \'' . $user_id . '\'';
//
//
//	$sql = 'SELECT stc_id ' . $select . ' FROM ' . $tablePre.$table . ' WHERE stc_parent_id = \'' . $cat_id . '\' ' . $where . ' AND stc_state = 1 ORDER BY stc_sort ASC, stc_id ASC';
//	
//	$res = Model()->getAll1($sql);

    $model_goodsclass = Model('goods_class');
    $res = $model_goodsclass->getGoodsClass($_SESSION['store_id'], $cat_id, $deep,$_SESSION['seller_group_id'],$_SESSION['seller_gc_limits']);
	$arr = array();

	if ($res) {
		foreach ($res as $key => $row) {
			if ($getrid == 0) {
				$row['cat_name'] = htmlspecialchars(addslashes(str_replace("\r\n", '', $row['gc_name'])), ENT_QUOTES);
				$row['cat_id'] = $row['gc_id'];
				$row['level'] = 0;
				$row['select'] = '';//str_repeat('&nbsp;', $row['level'] * 4);
				$arr[$row['gc_id']] = $row;

				if ($table == 'merchants_category') {
					$build_uri = array('cid' => $row['cat_id'], 'urid' => $row['user_id'], 'append' => $row['cat_name']);
					$domain_url = get_seller_domain_url($row['user_id'], $build_uri);
					$arr[$row['cat_id']]['url'] = $domain_url['domain_name'];
				}
				else {
					$arr[$row['gc_id']]['url'] = urlShop('search', 'index', array('cate_id'=>$row['gc_id']));;//build_uri('category', array('cid' => $row['cat_id']), $row['cat_name']);
				}
			}
			else {
				$arr[$row['gc_id']]['gc_id'] = $row['gc_id'];
			}

			if ($type) {
				$arr[$row['cat_id']]['child_tree'] = get_child_tree_pro($row['cat_id'], 0, $table, $getrid, $user_id);
			}

			if (($getrid == 0) && ($table == 'goods_class')) {
				$arr[$row['gc_id']]['cat_icon'] = '';
				$arr[$row['gc_id']]['style_icon'] = '';
			}
		}
	}

	return $arr;
}
/**
 * by：511613932
 */
function get_cat_info($cat_id = 0, $select = array(), $table = 'goods_class')
{
	$tablePre = C('tablepre');
	if ($select) {
		$select = implode(',', $select);
	}
	else {
		$select = '*';
	}

	$sql = 'SELECT ' . $select . ' FROM ' . $tablePre.$table . ' WHERE gc_id = \'' . $cat_id . '\' LIMIT 1';

	$row = Model()->getRow($sql);
	return $row;
}
/**
 * by：511613932
 */
function getAdvNum($mode = '', $floorMode = 0)
{
	$arr = array();

	switch ($mode) {
	case 'homeFloor':
		$arr1 = array('leftBanner' => '3', 'leftAdv' => '2', 'rightAdv' => '5');
		$arr2 = array('leftBanner' => '3', 'leftAdv' => '2', 'rightAdv' => '5');
		$arr3 = array('leftAdv' => '2', 'rightAdv' => '5');
		$arr4 = array('leftBanner' => '3', 'leftAdv' => '2', 'rightAdv' => '5');

		if ($floorMode == 1) {
			$arr = $arr1;
		}
		else if ($floorMode == 2) {
			$arr = $arr2;
		}
		else if ($floorMode == 3) {
			$arr = $arr3;
		}
		else if ($floorMode == 4) {
			$arr = $arr4;
		}
		else {
			$arr[1] = $arr1;
			$arr[2] = $arr2;
			$arr[3] = $arr3;
			$arr[4] = $arr4;
		}

		break;

	case 'homeFloorModule':
		$arr1 = array('leftBanner' => '3', 'rightAdv' => '4');
		$arr2 = array('leftBanner' => '3', 'rightAdv' => '3');
		$arr3 = array('leftBanner' => '3', 'rightAdv' => '3');
		$arr4 = array('leftBanner' => '3', 'rightAdv' => '2');

		if ($floorMode == 1) {
			$arr = $arr1;
		}
		else if ($floorMode == 2) {
			$arr = $arr2;
		}
		else if ($floorMode == 3) {
			$arr = $arr3;
		}
		else if ($floorMode == 4) {
			$arr = $arr4;
		}
		else {
			$arr[1] = $arr1;
			$arr[2] = $arr2;
			$arr[3] = $arr3;
			$arr[4] = $arr4;
		}

		break;

	case 'homeFloorThree':
		$arr1 = array('leftAdv' => '5');
		$arr2 = array('leftBanner' => '3', 'leftAdv' => '1', 'rightAdv' => '6');
		$arr3 = array('leftBanner' => '3', 'rightAdv' => '8');
		$arr4 = array('leftAdv' => '2', 'rightAdv' => '8');

		if ($floorMode == 1) {
			$arr = $arr1;
		}
		else if ($floorMode == 2) {
			$arr = $arr2;
		}
		else if ($floorMode == 3) {
			$arr = $arr3;
		}
		else if ($floorMode == 4) {
			$arr = $arr4;
		}
		else {
			$arr[1] = $arr1;
			$arr[2] = $arr2;
			$arr[3] = $arr3;
			$arr[4] = $arr4;
		}

		break;

	case 'homeFloorFour':
		$arr1 = array('leftAdv' => '2');
		$arr2 = array('leftBanner' => '3');
		$arr3 = array('leftAdv' => '2');
		$arr4 = array();

		if ($floorMode == 1) {
			$arr = $arr1;
		}
		else if ($floorMode == 2) {
			$arr = $arr2;
		}
		else if ($floorMode == 3) {
			$arr = $arr3;
		}
		else if ($floorMode == 4) {
			$arr = $arr4;
		}
		else {
			$arr[1] = $arr1;
			$arr[2] = $arr2;
			$arr[3] = $arr3;
			$arr[4] = $arr4;
		}

		break;

	case 'homeFloorFive':
		$arr1 = array('leftBanner' => '3', 'leftAdv' => '3');
		$arr2 = array('leftBanner' => '3', 'leftAdv' => '3', 'rightAdv' => '3');
		$arr3 = array('leftBanner' => '3', 'leftAdv' => '3', 'rightAdv' => '2');
		$arr4 = array('leftBanner' => '3', 'leftAdv' => '3', 'rightAdv' => '1');
		$arr5 = array('leftBanner' => '3', 'leftAdv' => '3', 'rightAdv' => '2');

		if ($floorMode == 1) {
			$arr = $arr1;
		}
		else if ($floorMode == 2) {
			$arr = $arr2;
		}
		else if ($floorMode == 3) {
			$arr = $arr3;
		}
		else if ($floorMode == 4) {
			$arr = $arr4;
		}
		else if ($floorMode == 5) {
			$arr = $arr5;
		}
		else {
			$arr[1] = $arr1;
			$arr[2] = $arr2;
			$arr[3] = $arr3;
			$arr[4] = $arr4;
			$arr[5] = $arr5;
		}

		break;

	case 'homeFloorSix':
		$arr1 = array('leftBanner' => '3', 'leftAdv' => '4');
		$arr2 = array('leftBanner' => '3', 'leftAdv' => '2');
		$arr3 = array('leftBanner' => '3', 'leftAdv' => '1');
		$arr4 = array('leftBanner' => '3');

		if ($floorMode == 1) {
			$arr = $arr1;
		}
		else if ($floorMode == 2) {
			$arr = $arr2;
		}
		else if ($floorMode == 3) {
			$arr = $arr3;
		}
		else if ($floorMode == 4) {
			$arr = $arr4;
		}
		else {
			$arr[1] = $arr1;
			$arr[2] = $arr2;
			$arr[3] = $arr3;
			$arr[4] = $arr4;
		}

		break;

	case 'homeFloorSeven':
		$arr1 = array('leftBanner' => '3', 'leftAdv' => '1');
		$arr2 = array('leftBanner' => '3', 'leftAdv' => '1');
		$arr3 = array('leftBanner' => '3', 'leftAdv' => '1');
		$arr4 = array('leftBanner' => '3', 'leftAdv' => '1');
		$arr5 = array('leftBanner' => '3', 'leftAdv' => '1');

		if ($floorMode == 1) {
			$arr = $arr1;
		}
		else if ($floorMode == 2) {
			$arr = $arr2;
		}
		else if ($floorMode == 3) {
			$arr = $arr3;
		}
		else if ($floorMode == 4) {
			$arr = $arr4;
		}
		else if ($floorMode == 5) {
			$arr = $arr5;
		}
		else {
			$arr[1] = $arr1;
			$arr[2] = $arr2;
			$arr[3] = $arr3;
			$arr[4] = $arr4;
			$arr[5] = $arr5;
		}

		break;
	}

	return $arr;
}
/**
 * by：511613932
 */
function seller_shop_cat($user_id = 0)
{
	$seller_shop_cat = '';
    $tablePre = C('tablepre');
	if ($user_id) {
		$sql = 'SELECT user_shopMain_category FROM ' . $tablePre.'merchants_shop_information' . ' WHERE user_id = \'' . $user_id . '\'';
		$seller_shop_cat = Model()->getOne($sql, true);
	}

	$arr = array();
	$arr['parent'] = '';

	if ($seller_shop_cat) {
		$seller_shop_cat = explode('-', $seller_shop_cat);

		foreach ($seller_shop_cat as $key => $row) {
			if ($row) {
				$cat = explode(':', $row);
				$arr[$key]['cat_id'] = $cat[0];
				$arr[$key]['cat_tree'] = $cat[1];
				$arr['parent'] .= $cat[0] . ',';

				if ($cat[1]) {
					$arr['parent'] .= $cat[1] . ',';
				}
			}
		}
	}

	$arr['parent'] = substr($arr['parent'], 0, -1);
	return $arr;
}
/**
 * by：511613932
 */
function create_ueditor_editor($input_name, $input_value = '', $input_height = 486, $type = 0)
{
	global $smarty;
	$FCKeditor = '<input type="hidden" id="' . $input_name . '" name="' . $input_name . '" value="' . htmlspecialchars($input_value) . '" /><iframe id="' . $input_name . '_frame" src="../plugins/seller_ueditor/ecmobanEditor.php?item=' . $input_name . '" width="100%" height="' . $input_height . '" frameborder="0" scrolling="no"></iframe>';

	if ($type == 1) {
		return $FCKeditor;
	}
	else {
		$smarty->assign('FCKeditor', $FCKeditor);
	}
}
/**
 * by：511613932
 */
function get_goods_desc_images_preg($endpoint = '', $text_desc = '', $str_file = 'goods_desc')
{
	if ($text_desc) {
		$preg = '/<img.*?src=[\\"|\']?(.*?)[\\"|\'].*?>/i';
		preg_match_all($preg, $text_desc, $desc_img);
	}
	else {
		$desc_img = '';
	}

	$arr = array();
	if ($desc_img && $endpoint) {
		foreach ($desc_img[1] as $key => $row) {
			$row = explode(IMAGE_DIR, $row);
			$arr[] = $endpoint . IMAGE_DIR . $row[1];
		}

		if ($desc_img[1]) {
			if (1 < count($desc_img[1])) {
				$desc_img[1] = array_unique($desc_img[1]);

				foreach ($desc_img[1] as $key => $row) {
					if ((strpos($row, 'http://') === false) && (strpos($row, 'https://') === false)) {
						$row_str = substr($row, 0, 1);
						$str = substr($endpoint, str_len($endpoint) - 1);
						if (($str == '/') && ($row_str == '/')) {
							$endpoint = substr($endpoint, 0, -1);
						}

						$text_desc = str_replace($row, $endpoint . $row, $text_desc);
					}
				}
			}
			else if (strpos($text_desc, $endpoint) === false) {
				$text_desc = str_replace('/' . IMAGE_DIR, $endpoint . IMAGE_DIR, $text_desc);
			}
		}
	}

	$res = array('images_list' => $arr, $str_file => $text_desc);
	return $res;
}
/**
 * by：511613932
 */
function get_floor_style($mode = '')
{
	$arr = array();

	switch ($mode) {
	case 'homeFloor':
		$arr = array('1,2,3', '1,2,3', '2,3', '1,2,3');
		break;

	case 'homeFloorModule':
		$arr = array('1,3', '1,3', '1,3', '1,3');
		break;

	case 'homeFloorThree':
		$arr = array('2', '1,2,3', '1,3', '2,3');
		break;

	case 'homeFloorFour':
		$arr = array('2', '1', '2', '');
		break;

	case 'homeFloorFive':
		$arr = array('1,2', '1,2,3', '1,2,3', '1,2,3', '1,2,3');
		break;

	case 'homeFloorSix':
		$arr = array('1,2', '1,2', '1,2', '1');
		break;

	case 'homeFloorSeven':
		$arr = array('1,2', '1,2', '1,2', '1,2', '1,2');
		break;
	}

	return $arr;
}
/**
 * by：511613932
 */
function set_default_filter($goods_id = 0, $cat_id = 0, $user_id = 0, $cat_type_show = 0, $table = 'category')
{
	$smarty = new cls_template();

	$smarty->template_dir = BASE_ROOT_PATH.DS.DIR_SHOP.'/templates/'.TPL_NAME;
	$smarty->compile_dir = BASE_ROOT_PATH.DS.DIR_SHOP.'/templates/'.TPL_NAME;	
	if ($cat_id) {
		$parent_cat_list = get_select_category($cat_id, 1, true, $user_id, $table);
		$filter_category_navigation = get_array_category_info($parent_cat_list, $table);
		$smarty->assign('filter_category_navigation', $filter_category_navigation);
	}

	if ($user_id) {
		$seller_shop_cat = seller_shop_cat($user_id);
	}
	else {
		$seller_shop_cat = array();
	}
//  $store_goods_class = Model('store_goods_class')->getClassTree(array('store_id' => $user_id, 'stc_state' => '1'));
//  print_r($store_goods_class);exit;
	$smarty->assign('filter_category_list', get_category_list($cat_id, 0, $seller_shop_cat, $user_id, 2, $table));
	$smarty->assign('filter_brand_list', search_brand_list($goods_id));
	$smarty->assign('cat_type_show', $cat_type_show);
	return true;
}
/**
 * by:511613932
 */
function get_every_category($cat_id = 0, $table = 'category')
{
	$parent_cat_list = get_category_array($cat_id, 1, true, $table);
	$filter_category_navigation = get_array_category_info($parent_cat_list, $table);
	$cat_nav = '';

	if ($filter_category_navigation) {
		foreach ($filter_category_navigation as $key => $val) {
			if ($key == 0) {
				$cat_nav .= $val['cat_name'];
			}
			else if (0 < $key) {
				$cat_nav .= ' > ' . $val['cat_name'];
			}
		}
	}

	return $cat_nav;
}
/**
 * by：511613932
 */
function get_extension_goods($cats)
{
	$tablePre = C('tablepre');
	$extension_goods_array = '';
	$sql = 'SELECT goods_id FROM ' . $tablePre.'goods' . ' AS g WHERE ' . $cats .' AND store_id=' . $_SESSION['store_id'];
	$extension_goods_array = Model()->getCol($sql);

	return db_create_in($extension_goods_array, 'g.goods_id');
}
/**
 * by：511613932
 */
function db_create_in($item_list, $field_name = '', $not = '')
{
	if (!empty($not)) {
		$not = ' ' . $not;
	}

	if (empty($item_list)) {
		return $field_name . $not . ' IN (\'\') ';
	}
	else {
		if (!is_array($item_list)) {
			$item_list = explode(',', $item_list);
		}

		$item_list = array_unique($item_list);
		$item_list_tmp = '';

		foreach ($item_list as $item) {
			if ($item !== '') {
				$item = addslashes($item);
				$item_list_tmp .= ($item_list_tmp ? ',\'' . $item . '\'' : '\'' . $item . '\'');
			}
		}

		if (empty($item_list_tmp)) {
			return $field_name . $not . ' IN (\'\') ';
		}
		else {
			return $field_name . $not . ' IN (' . $item_list_tmp . ') ';
		}
	}
}

/**
 * by：511613932
 */
function get_array_keys_cat($cat_id, $type = 0, $table = 'goods_class')
{
	$list = arr_foreach(cat_list($cat_id, 1, 1, $table));

	if ($type == 1) {
		if ($list) {
			$list = implode(',', $list);
			$list = get_del_str_comma($list);
		}
	}

	return $list;
}
/**
 * by：511613932
 */
function get_children($cat = 0, $type = 0, $child_three = 0, $table = 'goods_class', $type_cat = '')
{
	$cat_keys = get_array_keys_cat($cat, 0, $table);

	if ($type != 2) {
		if (empty($type_cat)) {
			if ($type == 1) {
				$type_cat = 'gc.gc_id ';
			}
			else if ($type == 3) {
				$type_cat = 'wc.gc_id ';
			}
			else if ($type == 4) {
				$type_cat = 'w.wholesale_cat_id ';
			}
			else {
				$type_cat = 'g.gc_id ';
			}
		}

		if ($child_three == 1) {
			if ($cat) {
				return $type_cat . db_create_in($cat);
			}
			else {
				return $type_cat . db_create_in('');
			}
		}
		else {
			$cat = array_unique(array_merge(array($cat), $cat_keys));

			if ($cat) {
				$cat = db_create_in($cat);
			}
			else {
				$cat = db_create_in('');
			}

			return $type_cat . $cat;
		}
	}
	else {
		$cat_keys = (!empty($cat_keys) ? implode(',', $cat_keys) : '');
		return $cat_keys;
	}
}
/**
 * by：511613932
 */
function strFilter($str)
{
	$str = str_replace('`', '', $str);
	$str = str_replace('·', '', $str);
	$str = str_replace('~', '', $str);
	$str = str_replace('!', '', $str);
	$str = str_replace('！', '', $str);
	$str = str_replace('@', '', $str);
	$str = str_replace('#', '', $str);
	$str = str_replace('$', '', $str);
	$str = str_replace('￥', '', $str);
	$str = str_replace('%', '', $str);
	$str = str_replace('^', '', $str);
	$str = str_replace('……', '', $str);
	$str = str_replace('&', '', $str);
	$str = str_replace('*', '', $str);
	$str = str_replace('(', '', $str);
	$str = str_replace(')', '', $str);
	$str = str_replace('（', '', $str);
	$str = str_replace('）', '', $str);
	$str = str_replace('-', '', $str);
	$str = str_replace('_', '', $str);
	$str = str_replace('——', '', $str);
	$str = str_replace('+', '', $str);
	$str = str_replace('=', '', $str);
	$str = str_replace('|', '', $str);
	$str = str_replace('\\', '', $str);
	$str = str_replace('[', '', $str);
	$str = str_replace(']', '', $str);
	$str = str_replace('【', '', $str);
	$str = str_replace('】', '', $str);
	$str = str_replace('{', '', $str);
	$str = str_replace('}', '', $str);
	$str = str_replace(';', '', $str);
	$str = str_replace('；', '', $str);
	$str = str_replace(':', '', $str);
	$str = str_replace('：', '', $str);
	$str = str_replace('\'', '', $str);
	$str = str_replace('"', '', $str);
	$str = str_replace('“', '', $str);
	$str = str_replace('”', '', $str);
	$str = str_replace(',', '', $str);
	$str = str_replace('，', '', $str);
	$str = str_replace('<', '', $str);
	$str = str_replace('>', '', $str);
	$str = str_replace('《', '', $str);
	$str = str_replace('》', '', $str);
	$str = str_replace('.', '', $str);
	$str = str_replace('。', '', $str);
	$str = str_replace('/', '', $str);
	$str = str_replace('、', '', $str);
	$str = str_replace('?', '', $str);
	$str = str_replace('？', '', $str);
	return trim($str);
}
/**
 * by：511613932
 */
function recurse_copy($src, $des, $type = 0)
{
	$dir = opendir($src);

	if (!is_dir($des)) {
		mkdir($des, 511, true);
	}

	while (false !== ($file = readdir($dir))) {
		if (($file != '.') && ($file != '..')) {
			if (is_dir($src . '/' . $file)) {
				recurse_copy($src . '/' . $file, $des . '/' . $file);
			}
			else if ($type == 0) {
				
				copy($src . '/' . $file, $des . '/' . $file);
			}
			else {
				$comtent = read_static_flie_cache($src . '/' . $file);
				$files = explode('.', $file);
				$files_count = count($files) - count($files) - 1;
				$suffix_name = $files[$files_count];

				if (2 < count($files)) {
					$path = count($files) - 1;
					$name = '';

					if ($files[$path]) {
						foreach ($files[$path] as $row) {
							$name .= $row . '.';
						}

						$name = substr($name, 0, -1);
					}

					$file_path = explode('/', $name);

					if (2 < $file_path) {
						$path = count($file_path) - 1;
						$cachename = $file_path[$path];
					}
					else {
						$cachename = $file_path[0];
					}
				}
				else {
					$cachename = $files[0];
				}

				write_static_file_cache($cachename, $comtent, $suffix_name, $des . '/');
			}
		}
	}

	closedir($dir);
}
/**
 * by：511613932
 */
function write_static_file_cache($cache_name = '', $caches = '', $suffix = '', $path = '',$a=false)
{
	if ((DEBUG_MODE & 2) == 2) {
		return false;
	}

		$suffix=empty($$suffix)?"php":$$suffix;
		$cache_file_path = $path . $cache_name . '.' . $suffix;
		
		$file_put = @file_put_contents($cache_file_path, $caches, LOCK_EX);
		
//		$cache_file_path = str_replace(BASE_ROOT_PATH, '', $cache_file_path);
//		$server_model = 0;
//
//		if (!isset($GLOBALS['_CFG']['open_oss'])) {
//			$sql = 'SELECT value FROM ' . $GLOBALS['ecs']->table('shop_config') . ' WHERE code = \'open_oss\'';
//			$is_oss = $GLOBALS['db']->getOne($sql, true);
//			$sql = 'SELECT value FROM ' . $GLOBALS['ecs']->table('shop_config') . ' WHERE code = \'server_model\'';
//			$server_model = $GLOBALS['db']->getOne($sql, true);
//		}
//		else {
//			$is_oss = $GLOBALS['_CFG']['open_oss'];
//		}
//
//		if (($is_oss == 1) && $server_model) {
//			get_oss_add_file(array($cache_file_path));
//		}

		return $file_put;

}
/**
 * by:511613932
 */

/**
 * 验证验证码
 *
 * @param string $nchash 哈希数
 * @param string $value 待验证值
 * @return boolean
 */
function checkSeccode($nchash,$value){
    list($checkvalue, $checktime) = explode("\t", decrypt(cookie('seccode'),MD5_KEY));
    $return = $checkvalue == strtoupper($value);
    if (!$return) setNcCookie('seccode','',-3600);
    return $return;
}

/**
 * 设置cookie
 *
 * @param string $name cookie 的名称
 * @param string $value cookie 的值
 * @param int $expire cookie 有效周期
 * @param string $path cookie 的服务器路径 默认为 /
 * @param string $domain cookie 的域名
 * @param string $secure 是否通过安全的 HTTPS 连接来传输 cookie,默认为false
 */
function setNcCookie($name, $value, $expire='3600', $path='', $domain='', $secure=false){
    if (empty($path)) $path = '/';
    if (empty($domain)) $domain = SUBDOMAIN_SUFFIX ? SUBDOMAIN_SUFFIX : '';
    $name = defined('COOKIE_PRE') ? COOKIE_PRE.$name : strtoupper(substr(md5(MD5_KEY),0,4)).'_'.$name;
    $expire = intval($expire)?intval($expire):(intval(SESSION_EXPIRE)?intval(SESSION_EXPIRE):3600);
    $result = setcookie($name, $value, time()+$expire, $path, $domain, $secure);
    $_COOKIE[$name] = $value;
}

/**
 * 取得COOKIE的值
 *
 * @param string $name
 * @return unknown
 */
function cookie($name= ''){
    $name = defined('COOKIE_PRE') ? COOKIE_PRE.$name : strtoupper(substr(md5(MD5_KEY),0,4)).'_'.$name;
    return $_COOKIE[$name];
}

/**
 * 当访问的act或op不存在时调用此函数并退出脚本
 *
 * @param string $act
 * @param string $op
 * @return void
 */
function requestNotFound($act = null, $op = null) {
    showMessage('您访问的页面不存在！', SHOP_SITE_URL, 'exception', 'error', 1, 3000);
    exit;
}

/**
 * 输出信息
 *
 * @param string $msg 输出信息
 * @param string/array $url 跳转地址 当$url为数组时，结构为 array('msg'=>'跳转连接文字','url'=>'跳转连接');
 * @param string $show_type 输出格式 默认为html
 * @param string $msg_type 信息类型 succ 为成功，error为失败/错误
 * @param string $is_show  是否显示跳转链接，默认是为1，显示
 * @param int $time 跳转时间，默认为2秒
 * @return string 字符串类型的返回结果
 */
function showMessage($msg,$url='',$show_type='html',$msg_type='succ',$is_show=1,$time=2000){
    if (!class_exists('Language')) import('libraries.language');
    Language::read('core_lang_index');
    $lang   = Language::getLangContent();
    /**
     * 如果默认为空，则跳转至上一步链接
     */
    $url = ($url!='' ? $url : getReferer());

    $msg_type = in_array($msg_type,array('succ','error')) ? $msg_type : 'error';

    /**
     * 输出类型
     */
    switch ($show_type){
        case 'json':
            $return = '{';
            $return .= '"msg":"'.$msg.'",';
            $return .= '"url":"'.$url.'"';
            $return .= '}';
            echo $return;
            break;
        case 'exception':
            echo '<!DOCTYPE html>';
            echo '<html>';
            echo '<head>';
            echo '<meta http-equiv="Content-Type" content="text/html; charset='.CHARSET.'" />';
            echo '<title></title>';
            echo '<style type="text/css">';
            echo 'body { font-family: "Verdana";padding: 0; margin: 0;}';
            echo 'h2 { font-size: 12px; line-height: 30px; border-bottom: 1px dashed #CCC; padding-bottom: 8px;width:800px; margin: 20px 0 0 150px;}';
            echo 'dl { float: left; display: inline; clear: both; padding: 0; margin: 10px 20px 20px 150px;}';
            echo 'dt { font-size: 14px; font-weight: bold; line-height: 40px; color: #333; padding: 0; margin: 0; border-width: 0px;}';
            echo 'dd { font-size: 12px; line-height: 40px; color: #333; padding: 0px; margin:0;}';
            echo '</style>';
            echo '</head>';
            echo '<body>';
            echo '<h2>'.$lang['error_info'].'</h2>';
            echo '<dl>';
            echo '<dd>'.$msg.'</dd>';
            echo '<dt><p /></dt>';
            echo '<dd>'.$lang['error_notice_operate'].'</dd>';
            echo '<dd><p /><p /><p /><p /></dd>';
            echo '</dl>';
            echo '</body>';
            echo '</html>';
            exit;
            break;
        case 'javascript':
            echo "<script>";
            echo "alert('". $msg ."');";
            echo "location.href='". $url ."'";
            echo "</script>";
            exit;
            break;
        case 'tenpay':
            echo "<html><head>";
            echo "<meta name=\"TENCENT_ONLINE_PAYMENT\" content=\"China TENCENT\">";
            echo "<script language=\"javascript\">";
            echo "window.location.href='" . $url . "';";
            echo "</script>";
            echo "</head><body></body></html>";
            exit;
            break;
        default:
            /**
             * 不显示右侧工具条
             */
            Tpl::output('hidden_nctoolbar', 1);
            if (is_array($url)){
                foreach ($url as $k => $v){
                    $url[$k]['url'] = $v['url']?$v['url']:getReferer();
                }
            }
            /**
             * 读取信息布局的语言包
             */
            Language::read("msg");
            /**
             * html输出形式
             * 指定为指定项目目录下的error模板文件
             */
            Tpl::setDir('');
            Tpl::output('html_title',Language::get('nc_html_title'));
            Tpl::output('msg',$msg);
            Tpl::output('url',$url);
            Tpl::output('msg_type',$msg_type);
            Tpl::output('is_show',$is_show);
            Tpl::showpage('msg','msg_layout',$time);
    }
    exit;
}

/**
 * 消息提示，主要适用于普通页面AJAX提交的情况
 *
 * @param string $message 消息内容
 * @param string $url 提示完后的URL去向
 * @param stting $alert_type 提示类型 error/succ/notice 分别为错误/成功/警示
 * @param string $extrajs 扩展JS
 * @param int $time 停留时间
 */
function showDialog($message = '', $url = '', $alert_type = 'error', $extrajs = '', $time = 2){
    if (empty($_GET['inajax'])){
        if ($url == 'reload') $url = '';
        showMessage($message.$extrajs,$url,'html',$alert_type,1,$time*1000);
    }
    $message = str_replace("'", "\\'", strip_tags($message));

    $paramjs = null;
    if ($url == 'reload'){
        $paramjs = 'window.location.reload()';
    }elseif ($url != ''){
        $paramjs = 'window.location.href =\''.$url.'\'';
    }
    if ($paramjs){
        $paramjs = 'function (){'.$paramjs.'}';
    }else{
        $paramjs = 'null';
    }
    $modes = array('error' => 'alert', 'succ' => 'succ', 'notice' => 'notice','js'=>'js');
    $cover = $alert_type == 'error' ? 1 : 0;
    $extra .= 'showDialog(\''.$message.'\', \''.$modes[$alert_type].'\', null, '.($paramjs ? $paramjs : 'null').', '.$cover.', null, null, null, null, '.(is_numeric($time) ? $time : 'null').', null);';
    $extra = $extra ? '<script type="text/javascript" reload="1">'.$extra.'</script>' : '';
    if ($extrajs != '' && substr(trim($extrajs),0,7) != '<script'){
        $extrajs = '<script type="text/javascript" reload="1">'.$extrajs.'</script>';
    }
    $extra .= $extrajs;
    ob_end_clean();
    @header("Expires: -1");
    @header("Cache-Control: no-store, private, post-check=0, pre-check=0, max-age=0", FALSE);
    @header("Pragma: no-cache");
    @header("Content-type: text/xml; charset=".CHARSET);

    $string =  '<?xml version="1.0" encoding="'.CHARSET.'"?>'."\r\n";
    $string .= '<root><![CDATA['.$message.$extra.']]></root>';
    echo $string;exit;
}

/**
 * 不显示信息直接跳转
 *
 * @param string $url
 */
function redirect($url = ''){
    if (empty($url)){
        if(!empty($_REQUEST['ref_url'])){
            $url = $_REQUEST['ref_url'];
        }else{
            $url = getReferer();
        }
    }
    header('Location: '.$url);exit();
}

/**
 * 取上一步来源地址
 *
 * @param
 * @return string 字符串类型的返回结果
 */
function getReferer(){
    return str_replace(array('\'','"', '<', '>'), '', $_SERVER['HTTP_REFERER']);
}

/**
 * 取验证码hash值
 *
 * @param
 * @return string 字符串类型的返回结果
 */
function getNchash($act = '', $op = ''){
    $act = $act ? $act : $_GET['con'];
    $op = $op ? $op : $_GET['fun'];
    if (C('captcha_status_login')){
        return substr(md5(SHOP_SITE_URL.$act.$op),0,8);
    } else {
        return '';
    }
}

/**
 * 加密函数
 *
 * @param string $txt 需要加密的字符串
 * @param string $key 密钥
 * @return string 返回加密结果
 */
function encrypt($txt, $key = ''){
    if (empty($txt)) return $txt;
    if (empty($key)) $key = md5(MD5_KEY);
    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_.";
    $ikey ="-x6g6ZWm2G9g_vr0Bo.pOq3kRIxsZ6rm";
    $nh1 = rand(0,64);
    $nh2 = rand(0,64);
    $nh3 = rand(0,64);
    $ch1 = $chars{$nh1};
    $ch2 = $chars{$nh2};
    $ch3 = $chars{$nh3};
    $nhnum = $nh1 + $nh2 + $nh3;
    $knum = 0;$i = 0;
    while(isset($key{$i})) $knum +=ord($key{$i++});
    $mdKey = substr(md5(md5(md5($key.$ch1).$ch2.$ikey).$ch3),$nhnum%8,$knum%8 + 16);
    $txt = base64_encode(time().'_'.$txt);
    $txt = str_replace(array('+','/','='),array('-','_','.'),$txt);
    $tmp = '';
    $j=0;$k = 0;
    $tlen = strlen($txt);
    $klen = strlen($mdKey);
    for ($i=0; $i<$tlen; $i++) {
        $k = $k == $klen ? 0 : $k;
        $j = ($nhnum+strpos($chars,$txt{$i})+ord($mdKey{$k++}))%64;
        $tmp .= $chars{$j};
    }
    $tmplen = strlen($tmp);
    $tmp = substr_replace($tmp,$ch3,$nh2 % ++$tmplen,0);
    $tmp = substr_replace($tmp,$ch2,$nh1 % ++$tmplen,0);
    $tmp = substr_replace($tmp,$ch1,$knum % ++$tmplen,0);
    return $tmp;
}

/**
 * 解密函数
 *
 * @param string $txt 需要解密的字符串
 * @param string $key 密匙
 * @return string 字符串类型的返回结果
 */
function decrypt($txt, $key = '', $ttl = 0){
    if (empty($txt)) return $txt;
    if (empty($key)) $key = md5(MD5_KEY);

    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_.";
    $ikey ="-x6g6ZWm2G9g_vr0Bo.pOq3kRIxsZ6rm";
    $knum = 0;$i = 0;
    $tlen = @strlen($txt);
    while(isset($key{$i})) $knum +=ord($key{$i++});
    $ch1 = @$txt{$knum % $tlen};
    $nh1 = strpos($chars,$ch1);
    $txt = @substr_replace($txt,'',$knum % $tlen--,1);
    $ch2 = @$txt{$nh1 % $tlen};
    $nh2 = @strpos($chars,$ch2);
    $txt = @substr_replace($txt,'',$nh1 % $tlen--,1);
    $ch3 = @$txt{$nh2 % $tlen};
    $nh3 = @strpos($chars,$ch3);
    $txt = @substr_replace($txt,'',$nh2 % $tlen--,1);
    $nhnum = $nh1 + $nh2 + $nh3;
    $mdKey = substr(md5(md5(md5($key.$ch1).$ch2.$ikey).$ch3),$nhnum % 8,$knum % 8 + 16);
    $tmp = '';
    $j=0; $k = 0;
    $tlen = @strlen($txt);
    $klen = @strlen($mdKey);
    for ($i=0; $i<$tlen; $i++) {
        $k = $k == $klen ? 0 : $k;
        $j = strpos($chars,$txt{$i})-$nhnum - ord($mdKey{$k++});
        while ($j<0) $j+=64;
        $tmp .= $chars{$j};
    }
    $tmp = str_replace(array('-','_','.'),array('+','/','='),$tmp);
    $tmp = trim(base64_decode($tmp));

    if (preg_match("/\d{10}_/s",substr($tmp,0,11))){
        if ($ttl > 0 && (time() - substr($tmp,0,11) > $ttl)){
            $tmp = null;
        }else{
            $tmp = substr($tmp,11);
        }
    }
    return $tmp;
}

/**
 * 取得IP
 *
 *
 * @return string 字符串类型的返回结果
 */
function getIp(){
    if (@$_SERVER['HTTP_CLIENT_IP'] && $_SERVER['HTTP_CLIENT_IP']!='unknown') {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (@$_SERVER['HTTP_X_FORWARDED_FOR'] && $_SERVER['HTTP_X_FORWARDED_FOR']!='unknown') {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return preg_match('/^\d[\d.]+\d$/', $ip) ? $ip : '';
}

/**
 * 数据库模型实例化入口
 *
 * @param string $model 模型名称
 * @return obj 对象形式的返回结果
 */
function Model($model = null){
    static $_cache = array();
    if (!is_null($model) && isset($_cache[$model])) return $_cache[$model];
    $file_name = BASE_DATA_PATH.'/model/'.$model.'.model.php';
    $class_name = $model.'Model';
    if (!file_exists($file_name)){
        return $_cache[$model] =  new Model($model);
    }else{
        require_once($file_name);
        if (!class_exists($class_name)){
            $error = 'Model Error:  Class '.$class_name.' is not exists!';
            throw_exception($error);
        }else{
            return $_cache[$model] = new $class_name();
        }
    }
}

/**
 * 行为模型实例
 *
 * @param string $model 模型名称
 * @return obj 对象形式的返回结果
 */
function Logic($model = null, $base_path = null){
    static $_cache = array();
    $cache_key = $model.'.'.$base_path;
    if (!is_null($model) && isset($_cache[$cache_key])) return $_cache[$cache_key];
    $base_path = $base_path == null ? BASE_DATA_PATH : $base_path;
    $file_name = $base_path.'/logic/'.$model.'.logic.php';
    $class_name = $model.'Logic';
    if (!file_exists($file_name)){
        return $_cache[$cache_key] =  new Model($model);
    }else{
        require_once($file_name);
        if (!class_exists($class_name)){
            $error = 'Logic Error:  Class '.$class_name.' is not exists!';
            throw_exception($error);
        }else{
            return $_cache[$cache_key] = new $class_name();
        }
    }
}

/**
 * 读取目录列表
 * 不包括 . .. 文件 三部分
 *
 * @param string $path 路径
 * @return array 数组格式的返回结果
 */
function readDirList($path){
    if (is_dir($path)) {
        $handle = @opendir($path);
        $dir_list = array();
        if ($handle){
            while (false !== ($dir = readdir($handle))){
                if ($dir != '.' && $dir != '..' && is_dir($path.DS.$dir)){
                    $dir_list[] = $dir;
                }
            }
            return $dir_list;
        }else {
            return false;
        }
    }else {
        return false;
    }
}

/**
 * 转换特殊字符
 *
 * @param string $string 要转换的字符串
 * @return string 字符串类型的返回结果
 */
function replaceSpecialChar($string){
    $str = str_replace("\r\n", "", $string);
    $str = str_replace("\t", "    ", $string);
    $str = str_replace("\n", "", $string);
    return $string;
}

/**
 * 编辑器内容
 *
 * @param int $id 编辑器id名称，与name同名
 * @param string $value 编辑器内容
 * @param string $width 宽 带px
 * @param string $height 高 带px
 * @param string $style 样式内容
 * @param string $upload_state 上传状态，默认是开启
 */
function showEditor($id, $value='', $width='700px', $height='300px', $style='visibility:hidden;',$upload_state="true", $media_open=false, $type='all'){
    //是否开启多媒体
    $media = '';
    if ($media_open){
        $media = ", 'flash', 'media'";
    }
    if (C('subdomain_suffix')){
        $upload_state = "false";
    }
    switch($type) {
    case 'basic':
        $items = "['source', '|', 'fullscreen', 'undo', 'redo', 'cut', 'copy', 'paste', '|', 'about']";
        break;
    case 'simple':
        $items = "['source', '|', 'fullscreen', 'undo', 'redo', 'cut', 'copy', 'paste', '|',
            'fontname', 'fontsize', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
            'removeformat', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
            'insertunorderedlist', '|', 'emoticons', 'image', 'link', '|', 'about']";
        break;
    case 'jj':
        $items = "['source', '|', 'fullscreen', 'undo', 'redo', 'cut', 'copy', 'paste', '|',
            'fontname', 'fontsize', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
            'removeformat', 'justifyleft', 'justifycenter', 'justifyright','|',  'link']";
        break;        
    default:
        $items = "['source', '|', 'fullscreen', 'undo', 'redo', 'print', 'cut', 'copy', 'paste',
            'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
            'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
            'superscript', '|', 'selectall', 'clearhtml','quickformat','|',
            'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
            'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image'".$media.", 'table', 'hr', 'emoticons', 'link', 'unlink', '|', 'about']";
        break;
    }
    //图片、Flash、视频、文件的本地上传都可开启。默认只有图片，要启用其它的需要修改resource\kindeditor\php下的upload_json.php的相关参数
    echo '<textarea id="'. $id .'" name="'. $id .'" style="width:'. $width .';height:'. $height .';'. $style .'">'.$value.'</textarea>';
    echo '
<script src="'. RESOURCE_SITE_URL .'/kindeditor/kindeditor-min.js" charset="utf-8"></script>
<script src="'. RESOURCE_SITE_URL .'/kindeditor/lang/zh_CN.js" charset="utf-8"></script>
<script>
    var KE;
  KindEditor.ready(function(K) {
        KE = K.create("textarea[name=\''.$id.'\']", {
                        items : '.$items.',
                        cssPath : "' . RESOURCE_SITE_URL . '/kindeditor/themes/default/default.css",
                        allowImageUpload : '.$upload_state.',
                        allowFlashUpload : false,
                        allowMediaUpload : false,
                        allowFileManager : false,
                        syncType:"form",
                        afterCreate : function() {
                            var self = this;
                            self.sync();
                        },
                        afterChange : function() {
                            var self = this;
                            self.sync();
                        },
                        afterBlur : function() {
                            var self = this;
                            self.sync();
                        }
        });
            KE.appendHtml = function(id,val) {
                this.html(this.html() + val);
                if (this.isCreated) {
                    var cmd = this.cmd;
                    cmd.range.selectNodeContents(cmd.doc.body).collapse(false);
                    cmd.select();
                }
                return this;
            }
    });
</script>
    ';
    return true;
}

/**
 * 获取目录大小
 *
 * @param string $path 目录
 * @param int $size 目录大小
 * @return int 整型类型的返回结果
 */
function getDirSize($path, $size=0){
    $dir = @dir($path);
    if (!empty($dir->path) && !empty($dir->handle)){
        while($filename = $dir->read()){
            if($filename != '.' && $filename != '..'){
                if (is_dir($path.DS.$filename)){
                    $size += getDirSize($path.DS.$filename);
                }else {
                    $size += filesize($path.DS.$filename);
                }
            }
        }
    }
    return $size ? $size : 0 ;
}

/**
 * 删除缓存目录下的文件或子目录文件
 *
 * @param string $dir 目录名或文件名
 * @return boolean
 */
function delCacheFile($dir){
    //防止删除cache以外的文件
    if (strpos($dir,'..') !== false) return false;
    $path = BASE_DATA_PATH.DS.'cache'.DS.$dir;
    if (is_dir($path)){
        $file_list = array();
        readFileList($path,$file_list);
        if (!empty($file_list)){
            foreach ($file_list as $v){
                if (basename($v) != 'index.html')@unlink($v);
            }
        }
    }else{
        if (basename($path) != 'index.html') @unlink($path);
    }
    return true;
}

/**
 * 获取文件列表(所有子目录文件)
 *
 * @param string $path 目录
 * @param array $file_list 存放所有子文件的数组
 * @param array $ignore_dir 需要忽略的目录或文件
 * @return array 数据格式的返回结果
 */
function readFileList($path,&$file_list,$ignore_dir=array()){
    $path = rtrim($path,'/');
    if (is_dir($path)) {
        $handle = @opendir($path);
        if ($handle){
            while (false !== ($dir = readdir($handle))){
                if ($dir != '.' && $dir != '..'){
                    if (!in_array($dir,$ignore_dir)){
                        if (is_file($path.DS.$dir)){
                            $file_list[] = $path.DS.$dir;
                        }elseif(is_dir($path.DS.$dir)){
                            readFileList($path.DS.$dir,$file_list,$ignore_dir);
                        }
                    }
                }
            }
            @closedir($handle);
//          return $file_list;
        }else {
            return false;
        }
    }else {
        return false;
    }
}

/**
* 价格格式化
*
* @param int    $price
* @return string    $price_format
*/
function ncPriceFormat($price) {
    return number_format($price,2,'.','');
}

/**
* 价格格式化
*
* @param int    $price
* @return string    $price_format
*/
function ncPriceFormatForList($price) {
    if ($price >= 10000) {
       return number_format(floor($price/100)/100,2,'.','').'万';
    } else {
     return '&yen;'.ncPriceFormat($price);
    }
}

/**
 * 二级域名解析
 * @return int 店铺id
 */
function subdomain(){
    $store_id = 0;
    /**
     * 获得系统配置,二级域名功能是否开启
     */
    if (C('enabled_subdomain')=='1'){//开启了二级域名
        $line = @explode(SUBDOMAIN_SUFFIX,$_SERVER['HTTP_HOST']);
        $line = trim($line[0],'.');
        if(empty($line) || strtolower($line) == 'www') return 0;

        $model_store = Model('store');
        $store_info = $model_store->getStoreInfo(array('store_domain'=>$line));
        //二级域名存在
        if ($store_info['store_id'] > 0){
            $store_id = $store_info['store_id'];
            $_GET['store_id'] = $store_info['store_id'];
        }
    }
    return $store_id;
}

/**
 * 通知邮件/通知消息 内容转换函数
 *
 * @param string $message 内容模板
 * @param array $param 内容参数数组
 * @return string 通知内容
 */
function ncReplaceText($message,$param){
    if(!is_array($param))return false;
    foreach ($param as $k=>$v){
        $message    = str_replace('{$'.$k.'}',$v,$message);
    }
    return $message;
}

/**
 * 字符串切割函数，一个字母算一个位置,一个字算2个位置
 *
 * @param string $string 待切割的字符串
 * @param int $length 切割长度
 * @param string $dot 尾缀
 */
function str_cut($string, $length, $dot = '')
{
    $string = str_replace(array('&nbsp;', '&amp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;'), array(' ', '&', '"', "'", '“', '”', '—', '<', '>', '·', '…'), $string);
    $strlen = strlen($string);
    if($strlen <= $length) return $string;
    $maxi = $length - strlen($dot);
    $strcut = '';
    if(strtolower(CHARSET) == 'utf-8')
    {
        $n = $tn = $noc = 0;
        while($n < $strlen)
        {
            $t = ord($string[$n]);
            if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                $tn = 1; $n++; $noc++;
            } elseif(194 <= $t && $t <= 223) {
                $tn = 2; $n += 2; $noc += 2;
            } elseif(224 <= $t && $t < 239) {
                $tn = 3; $n += 3; $noc += 2;
            } elseif(240 <= $t && $t <= 247) {
                $tn = 4; $n += 4; $noc += 2;
            } elseif(248 <= $t && $t <= 251) {
                $tn = 5; $n += 5; $noc += 2;
            } elseif($t == 252 || $t == 253) {
                $tn = 6; $n += 6; $noc += 2;
            } else {
                $n++;
            }
            if($noc >= $maxi) break;
        }
        if($noc > $maxi) $n -= $tn;
        $strcut = substr($string, 0, $n);
    }
    else
    {
        $dotlen = strlen($dot);
        $maxi = $length - $dotlen;
        for($i = 0; $i < $maxi; $i++)
        {
            $strcut .= ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
        }
    }
    $strcut = str_replace(array('&', '"', "'", '<', '>'), array('&amp;', '&quot;', '&#039;', '&lt;', '&gt;'), $strcut);
    return $strcut.$dot;
}

/**
 * unicode转为utf8
 * @param string $str 待转的字符串
 * @return string
 */
function unicodeToUtf8($str, $order = "little")
{
    $utf8string ="";
    $n=strlen($str);
    for ($i=0;$i<$n ;$i++ )
    {
        if ($order=="little")
        {
            $val = str_pad(dechex(ord($str[$i+1])), 2, 0, STR_PAD_LEFT) .
            str_pad(dechex(ord($str[$i])),      2, 0, STR_PAD_LEFT);
        }
        else
        {
            $val = str_pad(dechex(ord($str[$i])),      2, 0, STR_PAD_LEFT) .
            str_pad(dechex(ord($str[$i+1])), 2, 0, STR_PAD_LEFT);
        }
        $val = intval($val,16); // 由于上次的.连接，导致$val变为字符串，这里得转回来。
        $i++; // 两个字节表示一个unicode字符。
        $c = "";
        if($val < 0x7F)
        { // 0000-007F
            $c .= chr($val);
        }
        elseif($val < 0x800)
        { // 0080-07F0
            $c .= chr(0xC0 | ($val / 64));
            $c .= chr(0x80 | ($val % 64));
        }
        else
        { // 0800-FFFF
            $c .= chr(0xE0 | (($val / 64) / 64));
            $c .= chr(0x80 | (($val / 64) % 64));
            $c .= chr(0x80 | ($val % 64));
        }
        $utf8string .= $c;
    }
    /* 去除bom标记 才能使内置的iconv函数正确转换 */
    if (ord(substr($utf8string,0,1)) == 0xEF && ord(substr($utf8string,1,2)) == 0xBB && ord(substr($utf8string,2,1)) == 0xBF)
    {
        $utf8string = substr($utf8string,3);
    }
    return $utf8string;
}

/*
 * 重写$_SERVER['REQUREST_URI']
 */
function request_uri()
{
    if (isset($_SERVER['REQUEST_URI']))
    {
        $uri = $_SERVER['REQUEST_URI'];
    }
    else
    {
        if (isset($_SERVER['argv']))
        {
            $uri = $_SERVER['PHP_SELF'] .'?'. $_SERVER['argv'][0];
        }
        else
        {
            $uri = $_SERVER['PHP_SELF'] .'?'. $_SERVER['QUERY_STRING'];
        }
    }
    $uri = explode('/', $uri);
    $uri = end($uri);
    return APP_SITE_URL .'/'. $uri;
}

/*
 * 自定义memory_get_usage()
 *
 * @return 内存使用额度，如果该方法无效，返回0
 */
if(!function_exists('memory_get_usage')){
    function memory_get_usage(){//目前程序不兼容5以下的版本
        return 0;
    }
}

// 记录和统计时间（微秒）
function addUpTime($start,$end='',$dec=3) {
    static $_info = array();
    if(!empty($end)) { // 统计时间
        if(!isset($_info[$end])) {
            $_info[$end]   =  microtime(TRUE);
        }
        return number_format(($_info[$end]-$_info[$start]),$dec);
    }else{ // 记录时间
        $_info[$start]  =  microtime(TRUE);
    }
}

/**
 * 取得系统配置信息
 *
 * @param string $key 取得下标值
 * @return mixed
 */
function C($key){
    if (strpos($key,'.')){
        $key = explode('.',$key);
        $value = \shopec\Core::getConfig($key[0]);
        if (isset($key[2])){
            return $value[$key[1]][$key[2]];
        }else{
            return $value[$key[1]];
        }
    }else{
        return \shopec\Core::getConfig($key);
    }
}

/**
 * 取得商品默认大小图片
 *
 * @param string $key   图片大小 small tiny
 * @return string
 */
function defaultGoodsImage($key){
    $file = str_ireplace('.', '_' . $key . '.', C('default_goods_image'));
    return ATTACH_COMMON.DS.$file;
}
/**
 * 取得可视化装修模板图片
 */
function get_image_path($goods_id, $image = '', $thumb = false, $call = 'goods', $del = false, $retain = false)
{
	if (!empty($image) && (strpos($image, 'http://') === false) && (strpos($image, 'https://') === false)) {
		$image = DATA_SITE_URL.DS.$image;
	}

	if ($retain) {
		$url = $image;
	}
	else {
		$url = (empty($image) ? DATA_SITE_URL.DS.'gallery_album'.DS.'errorImg.png' : $image);
	}

	return $url;
}

/**
 * 取得用户头像图片
 *
 * @param string $member_avatar
 * @return string
 */
function getMemberAvatar($member_avatar){
    if (empty($member_avatar)) {
        return UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS.C('default_user_portrait');
    } else {
       if (file_exists(BASE_UPLOAD_PATH.DS.ATTACH_AVATAR.DS.$member_avatar)){
            return UPLOAD_SITE_URL.DS.ATTACH_AVATAR.DS.$member_avatar;
       } else {
           return UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS.C('default_user_portrait');
       }

    }
}
/**
 * 取得用户头像图片
 *
 * @param string $member_avatar
 * @return string
 */
function getMemberAvatarHttps($member_avatar){
    if (empty($member_avatar)) {
        return UPLOAD_SITE_URL_HTTPS.DS.ATTACH_COMMON.DS.C('default_user_portrait');
    } else {
       if (file_exists(BASE_UPLOAD_PATH.DS.ATTACH_AVATAR.DS.$member_avatar)){
            return UPLOAD_SITE_URL_HTTPS.DS.ATTACH_AVATAR.DS.$member_avatar;
       } else {
           return UPLOAD_SITE_URL_HTTPS.DS.ATTACH_COMMON.DS.C('default_user_portrait');
       }

    }
}
/**
 * 成员头像
  * @param string $member_id
 * @return string
 */
function getMemberAvatarForID($id){
    if(file_exists(BASE_UPLOAD_PATH.'/'.ATTACH_AVATAR.'/avatar_'.$id.'.jpg')){
        return UPLOAD_SITE_URL.'/'.ATTACH_AVATAR.'/avatar_'.$id.'.jpg';
    }else{
        return UPLOAD_SITE_URL.'/'.ATTACH_COMMON.DS.C('default_user_portrait');
    }
}
/**
 * 取得店铺标志
 *
 * @param string $img 图片名
 * @param string $type 查询类型 store_logo/store_avatar
 * @return string
 */
function getStoreLogo($img, $type = 'store_avatar'){
    if ($type == 'store_avatar') {
        if (empty($img)) {
            return UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS.C('default_store_avatar');
        } else {
            return UPLOAD_SITE_URL.DS.ATTACH_STORE.DS.$img;
        }
    }elseif ($type == 'store_logo') {
        if (empty($img)) {
            return UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS.C('default_store_logo');
        } else {
            return UPLOAD_SITE_URL.DS.ATTACH_STORE.DS.$img;
        }
    }
}

/**
 * 获取文章URL
 */
function getCMSArticleUrl($article_id) {
    if(URL_MODEL) {
        // 开启伪静态
        return CMS_SITE_URL.DS.'article-'.$article_id.'.html';
    } else {
        return CMS_SITE_URL.DS.'index.php?con=article&fun=article_detail&article_id='.$article_id;
    }
}

/**
 * 获取画报URL
 */
function getCMSPictureUrl($picture_id) {
    if(URL_MODEL) {
        // 开启伪静态
        return CMS_SITE_URL.DS.'picture-'.$picture_id.'.html';
    } else {
        return CMS_SITE_URL.DS.'index.php?con=picture&fun=picture_detail&picture_id='.$picture_id;
    }
}

/**
 * 获取文章图片URL
 */
function getCMSArticleImageUrl($image_path, $image_name, $type='list') {
    if(empty($image_name)) {
        return UPLOAD_SITE_URL.DS.ATTACH_CMS.DS.'no_cover.png';
    } else {
        $image_array = unserialize($image_name);
        if(!empty($image_array['name'])) {
            $image_name = $image_array['name'];
        }
        if(!empty($image_array['path'])) {
            $image_path = $image_array['path'];
        }
        $ext_array = array('list','max');
        $file_path = ATTACH_CMS.DS.'article'.DS.$image_path.DS.str_ireplace('.', '_'.$type.'.', $image_name);
        if(file_exists(BASE_UPLOAD_PATH.DS.$file_path)) {
            $image_name = UPLOAD_SITE_URL.DS.$file_path;
        } else {
            $image_name = UPLOAD_SITE_URL.DS.ATTACH_CMS.DS.'no_cover.png';
        }
        return $image_name;
    }
}

/**
 * 获取文章图片URL
 */
function getCMSImageName($image_name_string) {
    $image_array = unserialize($image_name_string);
    if(!empty($image_array['name'])) {
        $image_name = $image_array['name'];
    } else {
        $image_name = $image_name_string;
    }
    return $image_name;
}

/**
 * 获取CMS专题图片
 */
function getCMSSpecialImageUrl($image_name='') {
    return UPLOAD_SITE_URL.DS.ATTACH_CMS.DS.'special'.DS.$image_name;
}

/**
 * 获取CMS专题路径
 */
function getCMSSpecialImagePath($image_name='') {
    return BASE_UPLOAD_PATH.DS.ATTACH_CMS.DS.'special'.DS.$image_name;
}

/**
 * 获取CMS首页图片
 */
function getCMSIndexImageUrl($image_name='') {
    return UPLOAD_SITE_URL.DS.ATTACH_CMS.DS.'index'.DS.$image_name;
}

/**
 * 获取CMS首页图片路径
 */
function getCMSIndexImagePath($image_name='') {
    return BASE_UPLOAD_PATH.DS.ATTACH_CMS.DS.'index'.DS.$image_name;
}

/**
 * 获取CMS专题Url
 */
function getCMSSpecialUrl($special_id) {
    return CMS_SITE_URL.DS.'index.php?con=special&fun=special_detail&special_id='.$special_id;
}

/**
 * 获取商城专题Url
 */
function getShopSpecialUrl($special_id) {
    return SHOP_SITE_URL.DS.'index.php?con=special&fun=special_detail&special_id='.$special_id;
}


/**
 * 获取CMS专题静态文件
 */
function getCMSSpecialHtml($special_id) {
    $url = BASE_UPLOAD_PATH.DS.ATTACH_CMS.DS.'special_html'.DS.md5('special'.intval($special_id)).'.html';
    $special_file = file_get_contents($url);
    return $special_file;
}

/**
 * 获取微商城个人秀图片地址
 */
function getMicroshopPersonalImageUrl($personal_info,$type=''){
    $ext_array = array('list','tiny');
    $personal_image_array = array();
    $personal_image_list = explode(',',$personal_info['commend_image']);
    if(!empty($personal_image_list)){
        foreach ($personal_image_list as $value) {
            if(!empty($type) && in_array($type,$ext_array)) {
                $file_name = str_replace('.', '_'.$type.'.', $value);
            } else {
                $file_name = $value;
            }
            $file_path = $personal_info['commend_member_id'].DS.$file_name;
            if(is_file(BASE_UPLOAD_PATH.DS.ATTACH_MICROSHOP.DS.$file_path)) {
                $personal_image_array[] = UPLOAD_SITE_URL.DS.ATTACH_MICROSHOP.DS.$file_path;
            } else {
                $personal_image_array[] = getMicroshopDefaultImage();
            }
        }
    } else {
        $personal_image_array[] = getMicroshopDefaultImage();
    }
    return $personal_image_array;

}

function getMicroshopDefaultImage() {
    return UPLOAD_SITE_URL.'/'.defaultGoodsImage('240');
}

/**
 * 获取开店申请图片
 */
function getStoreJoininImageUrl($image_name='') {
    return UPLOAD_SITE_URL.DS.ATTACH_STORE_JOININ.DS.$image_name;
}

/**
 * 获取开店装修图片地址
 */
function getStoreDecorationImageUrl($image_name = '', $store_id = null) {
    if(empty($store_id)) {
        $image_name_array = explode('_', $image_name);
        $store_id = $image_name_array[0];
    }

    $image_path = DS . ATTACH_STORE_DECORATION . DS . $store_id . DS . $image_name;
    if(is_file(BASE_UPLOAD_PATH . $image_path)) {
        return UPLOAD_SITE_URL . $image_path;
    } else {
        return '';
    }
}

/**
 * 获取运单图片地址
 */
function getWaybillImageUrl($image_name = '') {
    $image_path = DS . ATTACH_WAYBILL . DS . $image_name;
    if(is_file(BASE_UPLOAD_PATH . $image_path)) {
        return UPLOAD_SITE_URL . $image_path;
    } else {
        return UPLOAD_SITE_URL.'/'.defaultGoodsImage('240');
    }
}

/**
 * 获取运单图片地址
 */
function getMbSpecialImageUrl($image_name = '') {
    $name_array = explode('_', $image_name);
    if(count($name_array) == 2) {
        $image_path = DS . ATTACH_MOBILE . DS . 'special' . DS . $name_array[0] . DS . $image_name;
    } else {
        $image_path = DS . ATTACH_MOBILE . DS . 'special' . DS . $image_name;
    }
    if(is_file(BASE_UPLOAD_PATH . $image_path)) {
        return UPLOAD_SITE_URL . $image_path;
    } else {
        return UPLOAD_SITE_URL.'/'.defaultGoodsImage('240');
    }
}

/**
 * 获取视频列表页广告图地址
 */
function getMbFocusImageUrl($image_name = '') {
    $image_path = DS . ATTACH_MOBILE . DS . 'focus' . DS . $image_name;
    if(is_file(BASE_UPLOAD_PATH . $image_path)) {
        return UPLOAD_SITE_URL . $image_path;
    } else {
        return UPLOAD_SITE_URL.'/'.defaultGoodsImage('240');
    }
}

/**
 * 获取资讯列表图片地址
 */
function getMbNewsImageUrl($image_name = '') {
    $image_path = DS . ATTACH_MOBILE . DS . 'news' . DS . $image_name;
    if(is_file(BASE_UPLOAD_PATH . $image_path)) {
        return UPLOAD_SITE_URL . $image_path;
    } else {
        return UPLOAD_SITE_URL.'/'.defaultGoodsImage('240');
    }
}

/**
 * 获取点播地址
 */
function getMbDemandUrl($video_name = '') {
    $video_path = DS . ATTACH_MOBILE . DS . 'demand' . DS . $video_name;
    if(is_file(BASE_UPLOAD_PATH . $video_path)) {
        return UPLOAD_SITE_URL . $video_path;
    } else {
        return '';
    }
}

/**
 * 获取直播图片地址
 */
function getMbMoiveImageUrl($image_name = '', $member_id) {
    $image_path = DS.ATTACH_MOBILE . DS . 'movie' . DS . $member_id . DS . $image_name;
    if(is_file(BASE_UPLOAD_PATH . $image_path)) {
        return UPLOAD_SITE_URL . $image_path;
    } else {
        return UPLOAD_SITE_URL.'/'.defaultGoodsImage('240');
    }
}

/**
 * 获取直播播流地址
 */
function getMbMoiveUrl($movie_rand,$type='') {
    if(!$movie_rand){
        return '';
    }else{
        if(C('live.open')){
            if($type == 'flv'){
                return 'http://'.C('live.accUrl').'/'.C('live.AppName').'/'.$movie_rand.'.flv';
            } elseif($type == 'm3u8'){
                return 'http://'.C('live.accUrl').'/'.C('live.AppName').'/'.$movie_rand.'.m3u8';
            }elseif ($type == '') {
                return 'http://'.C('live.accUrl').'/'.C('live.AppName').'/'.$movie_rand.'.m3u8';
            }
        }else{
            return '';
        }
        
    }
}

/**
 * 加载文件
 *
 * 使用require_once函数，只适用于加载框架内类库文件
 * 如果文件名中包含"_"使用"#"代替
 *
 * @example import('cache'); //require_once(BASE_PATH.'/framework/libraries/cache.php');
 * @example import('libraries.cache');  //require_once(BASE_PATH.'/framework/libraries/cache.php');
 * @example import('function.core');    //require_once(BASE_PATH.'/framework/function/core.php');
 * @example import('.control.adv')  //require_once(BASE_PATH.'/control/adv.php');
 *
 * @param 要加载的文件 $libname
 * @param 文件扩展名 $file_ext
 */
function import($libname,$file_ext='.php'){
    //替换为目录符号/
    if (strstr($libname,'.')){
        $path = str_replace('.','/',$libname);
    }else{
        $path = 'libraries/'.$libname;
    }
    // 基准目录，如果是顶级目录
    if(substr($libname,0,1) == '.'){
        $base_dir = BASE_CORE_PATH.'/';
        $path = ltrim(str_replace('libraries/','',$path),'/');
    }else{
        $base_dir = BASE_CORE_PATH.'/framework/';
    }
    //如果文件名中含有.使用#代替
    if (strstr($path,'#')){
        $path = str_replace('#','.',$path);
    }
    //返回安全路径
    if(preg_match('/^[\w\d\/_.]+$/i', $path)){
        $file = realpath($base_dir.$path.$file_ext);
    }else{
        $file = false;
    }
    if (!$file){
        exit($path.$file_ext.' isn\'t exists!');
    }else{
        require_once($file);
    }

}

/**
 * 取得随机数
 *
 * @param int $length 生成随机数的长度
 * @param int $numeric 是否只产生数字随机数 1是0否
 * @return string
 */
function random($length, $numeric = 0) {
    $seed = base_convert(md5(microtime().$_SERVER['DOCUMENT_ROOT']), 16, $numeric ? 10 : 35);
    $seed = $numeric ? (str_replace('0', '', $seed).'012340567890') : ($seed.'zZ'.strtoupper($seed));
    $hash = '';
    $max = strlen($seed) - 1;
    for($i = 0; $i < $length; $i++) {
        $hash .= $seed{mt_rand(0, $max)};
    }
    return $hash;
}

/**
 * 返回模板文件所在完整目录
 *
 * @param str $tplpath
 * @return string
 */
function template($tplpath){
    if (strpos($tplpath,':') !== false){
        $tpltmp = explode(':',$tplpath);
        return BASE_DATA_PATH.'/'.$tpltmp[0].'/tpl/'.$tpltmp[1].'.php';
    }else{
        if (defined('MODULE_NAME')) {
            return MODULES_BASE_PATH.'/templates/'.TPL_NAME.'/'.$tplpath.'.php';
        } else {
            return BASE_PATH.'/templates/'.TPL_NAME.'/'.$tplpath.'.php';
        }
    }
}
/**
 * 返回lbi模板文件所在完整目录
 *by:511613932
 * @param str $tplpath
 * @return string
 */
function library_template($tplpath){
    if (strpos($tplpath,':') !== false){
        $tpltmp = explode(':',$tplpath);
        return BASE_DATA_PATH.'/'.$tpltmp[0].'/tpl/'.$tpltmp[1].'.lbi';
    }else{
        if (defined('MODULE_NAME')) {
            return MODULES_BASE_PATH.'/templates/'.TPL_NAME.'/'.$tplpath.'.lbi';
        } else {
            return BASE_PATH.'/templates/'.TPL_NAME.'/'.$tplpath.'.lbi';
        }
    }
}
/**
 * 检测FORM是否提交
 * @param  $check_token 是否验证token
 * @param  $check_captcha 是否验证验证码
 * @param  $return_type 'alert','num'
 * @return boolean
 */
function chksubmit($check_token = false, $check_captcha = false, $return_type = 'alert'){
    $submit = isset($_POST['form_submit']) ? $_POST['form_submit'] : $_GET['form_submit'];
    if ($submit != 'ok') return false;
    if ($check_token && !Security::checkToken()){
        if ($return_type == 'alert'){
            showDialog('Token error!');
        }else{
            return -11;
        }
    }
    if ($check_captcha){
        if (!checkSeccode($_POST['nchash'],$_POST['captcha'])){
            setNcCookie('seccode'.$_POST['nchash'],'',-3600);
            if ($return_type == 'alert'){
                showDialog('验证码错误!');
            }else{
                return -12;
            }
        }
        setNcCookie('seccode'.$_POST['nchash'],'',-3600);
    }
    return true;
}

/**
 * sns表情标示符替换为html
 */
function parsesmiles($message) {
    $smilescache_file = BASE_DATA_PATH.DS.'smilies'.DS.'smilies.php';
    if (file_exists($smilescache_file)){
        include $smilescache_file;
        if (strtoupper(CHARSET) == 'GBK') {
            $smilies_array = Language::getGBK($smilies_array);
        }
        if(!empty($smilies_array) && is_array($smilies_array)) {
            $imagesurl = RESOURCE_SITE_URL.DS.'js'.DS.'smilies'.DS.'images'.DS;
            $replace_arr = array();
            foreach($smilies_array['replacearray'] AS $key => $smiley) {
                $replace_arr[$key] = '<img src="'.$imagesurl.$smiley['imagename'].'" title="'.$smiley['desc'].'" border="0" alt="'.$imagesurl.$smiley['desc'].'" />';
            }

            $message = preg_replace($smilies_array['searcharray'], $replace_arr, $message);
        }
    }
    return $message;
}

/**
 * 输出validate的验证信息
 *
 * @param array/string $error
 */
function showValidateError($error){
    if (!empty($_GET['inajax'])){
        foreach (explode('<br/>',$error) as $v) {
            if (trim($v != '')){
                showDialog($v,'','error','',3);
            }
        }
    }else{
        showDialog($error,'','error','',3);
    }
}

/**
 * 延时加载分页功能，判断是否有更多连接和limitstart值和经过验证修改的$delay_eachnum值
 * @param int $delay_eachnum 延时分页每页显示的条数
 * @param int $delay_page 延时分页当前页数
 * @param int $count 总记录数
 * @param bool $ispage 是否在分页模式中实现延时分页(前台显示的两种不同效果)
 * @param int $page_nowpage 分页当前页数
 * @param int $page_eachnum 分页每页显示条数
 * @param int $page_limitstart 分页初始limit值
 * @return array array('hasmore'=>'是否显示更多连接','limitstart'=>'加载的limit开始值','delay_eachnum'=>'经过验证修改的$delay_eachnum值');
 */
function lazypage($delay_eachnum,$delay_page,$count,$ispage=false,$page_nowpage=1,$page_eachnum=1,$page_limitstart=1){
    //是否有多余
    $hasmore = true;
    $limitstart = 0;
    if ($ispage == true){
        if ($delay_eachnum < $page_eachnum){//当延时加载每页条数小于分页的每页条数时候实现延时加载，否则按照普通分页程序流程处理
            $page_totlepage = ceil($count/$page_eachnum);
            //计算limit的开始值
            $limitstart = $page_limitstart + ($delay_page-1)*$delay_eachnum;
            if ($page_totlepage > $page_nowpage){//当前不为最后一页
                if ($delay_page >= $page_eachnum/$delay_eachnum){
                    $hasmore = false;
                }
                //判断如果分页的每页条数与延时加载每页的条数不能整除的处理
                if ($hasmore == false && $page_eachnum%$delay_eachnum >0){
                    $delay_eachnum = $page_eachnum%$delay_eachnum;
                }
            }else {//当前最后一页
                $showcount = ($page_totlepage-1)*$page_eachnum+$delay_eachnum*$delay_page;//已经显示的记录总数
                if ($count <= $showcount){
                    $hasmore = false;
                }
            }
        }else {
            $hasmore = false;
        }
    }else {
        if ($count <= $delay_page*$delay_eachnum){
            $hasmore = false;
        }
        //计算limit的开始值
        $limitstart = ($delay_page-1)*$delay_eachnum;
    }

    return array('hasmore'=>$hasmore,'limitstart'=>$limitstart,'delay_eachnum'=>$delay_eachnum);
}

/**
 * 文件数据读取和保存 字符串、数组
 *
 * @param string $name 文件名称（不含扩展名）
 * @param mixed $value 待写入文件的内容
 * @param string $path 写入cache的目录
 * @param string $ext 文件扩展名
 * @return mixed
 */
function F($name, $value = null, $path = 'cache', $ext = '.php') {
    if (strtolower(substr($path,0,5)) == 'cache'){
        $path  = 'data/'.$path;
    }
    static $_cache = array();
    if (isset($_cache[$name.$path])) return $_cache[$name.$path];
    $filename = BASE_ROOT_PATH.'/'.$path.'/'.$name.$ext;
    if (!is_null($value)) {
        $dir = dirname($filename);
        if (!is_dir($dir)) mkdir($dir);
        return write_file($filename,$value);
    }

    if (is_file($filename)) {
        $_cache[$name.$path] = $value = include $filename;
    } else {
        $value = false;
    }
    return $value;
}

/**
 * 内容写入文件
 *
 * @param string $filepath 待写入内容的文件路径
 * @param string/array $data 待写入的内容
 * @param  string $mode 写入模式，如果是追加，可传入“append”
 * @return bool
 */
function write_file($filepath, $data, $mode = null)
{
    if (!is_array($data) && !is_scalar($data)) {
        return false;
    }

    $data = var_export($data, true);

    $data = "<?php defined('Inshopec') or exit('Access Invalid!'); return ".$data.";";
    $mode = $mode == 'append' ? FILE_APPEND : null;
    if (false === file_put_contents($filepath,($data),$mode)){
        return false;
    }else{
        return true;
    }
}

/**
 * 循环创建目录
 *
 * @param string $dir 待创建的目录
 * @param  $mode 权限
 * @return boolean
 */
function mk_dir($dir, $mode = '0777') {
    if (is_dir($dir) || @mkdir($dir, $mode))
        return true;
    if (!mk_dir(dirname($dir), $mode))
        return false;
    return @mkdir($dir, $mode);
}

/**
 * 封装分页操作到函数，方便调用
 *
 * @param string $cmd 命令类型
 * @param mixed $arg 参数
 * @return mixed
 */
function pagecmd($cmd ='', $arg = ''){
    if (!class_exists('page'))  import('page');
    static $page;
    if ($page == null){
        $page = new Page();
    }

    switch (strtolower($cmd)) {
        case 'seteachnum':      $page->setEachNum($arg);break;
        case 'settotalnum':     $page->setTotalNum($arg);break;
        case 'setstyle':        $page->setStyle($arg);break;
        case 'show':            return $page->show($arg);break;
        case 'obj':             return $page;break;
        case 'gettotalnum':     return $page->getTotalNum();break;
        case 'gettotalpage':    return $page->getTotalPage();break;
        case 'getnowpage':      return $page->getNowPage();break;
        case 'settotalpagebynum': return $page->setTotalPageByNum($arg);break;
        default:                break;
    }
}

/**
 * 抛出异常
 *
 * @param string $error 异常信息
 */
function throw_exception($error){
    if (!defined('IGNORE_EXCEPTION')){
        showMessage($error,'','exception');
    }else{
        exit();
    }
}

/**
 * 输出错误信息
 *
 * @param string $error 错误信息
 */
function halt($error){
    showMessage($error,'','exception');
}

/**
 * 去除代码中的空白和注释
 *
 * @param string $content 待压缩的内容
 * @return string
 */
    function compress_code($content) {
    $stripStr = '';
    //分析php源码
    $tokens = token_get_all($content);
    $last_space = false;
    for ($i = 0, $j = count($tokens); $i < $j; $i++) {
        if (is_string($tokens[$i])) {
            $last_space = false;
            $stripStr .= $tokens[$i];
        } else {
            switch ($tokens[$i][0]) {
                case T_COMMENT: //过滤各种PHP注释
                case T_DOC_COMMENT:
                    break;
                case T_WHITESPACE:  //过滤空格
                    if (!$last_space) {
                        $stripStr .= ' ';
                        $last_space = true;
                    }
                    break;
                default:
                    $last_space = false;
                    $stripStr .= $tokens[$i][1];
            }
        }
    }
    return $stripStr;
}

/**
 * 取得对象实例
 *
 * @param object $class
 * @param string $method
 * @param array $args
 * @return object
 */
function get_obj_instance($class, $method='', $args = array()){
    static $_cache = array();
    $key = $class.$method.(empty($args) ? null : md5(serialize($args)));
    if (isset($_cache[$key])){
        return $_cache[$key];
    }else{
        if (class_exists($class)){
            $obj = new $class;
            if (method_exists($obj,$method)){
                if (empty($args)){
                    $_cache[$key] = $obj->$method();
                }else{
                    $_cache[$key] = call_user_func_array(array(&$obj, $method), $args);
                }
            }else{
                $_cache[$key] = $obj;
            }
            return $_cache[$key];
        }else{
            throw_exception('Class '.$class.' isn\'t exists!');
        }
    }
}

/**
 * 返回以原数组某个值为下标的新数据
 *
 * @param array $array
 * @param string $key
 * @param int $type 1一维数组2二维数组
 * @return array
 */
function array_under_reset($array, $key, $type=1){
    if (is_array($array)){
        $tmp = array();
        foreach ($array as $v) {
            if ($type === 1){
                $tmp[$v[$key]] = $v;
            }elseif($type === 2){
                $tmp[$v[$key]][] = $v;
            }
        }
        return $tmp;
    }else{
        return $array;
    }
}

/**
 * KV缓存 读
 *
 * @param string $key 缓存名称
 * @param boolean $callback 缓存读取失败时是否使用回调 true代表使用cache.model中预定义的缓存项 默认不使用回调
 * @param callable $callback 传递非boolean值时 通过is_callable进行判断 失败抛出异常 成功则将$key作为参数进行回调
 * @return mixed
 */
function rkcache($key, $callback = false)
{
    if (C('cache_open')) {
        $cacher = \shopec\Core::$instances['cacheredis'];
    } else {
        $cacher = Cache::getInstance('file', null);
    }
    if (!$cacher) {
        throw new Exception('Cannot fetch cache object!');
    }

    $value = $cacher->get($key);

    if ($value === false && $callback !== false) {
        if ($callback === true) {
            $callback = array(Model('cache'), 'call');
        }

        if (!is_callable($callback)) {
            throw new Exception('Invalid rkcache callback!');
        }

        $value = call_user_func($callback, $key);
        wkcache($key, $value);
    }

    return $value;
}

/**
 * KV缓存 写
 *
 * @param string $key 缓存名称
 * @param mixed $value 缓存数据 若设为否 则下次读取该缓存时会触发回调（如果有）
 * @param int $expire 缓存时间 单位秒 null代表不过期
 * @return boolean
 */
function wkcache($key, $value, $expire = null)
{

    if (C('cache_open')) {
        $cacher = \shopec\Core::$instances['cacheredis'];
    } else {
        $cacher = Cache::getInstance('file', null);
    }
    if (!$cacher) {
        throw new Exception('Cannot fetch cache object!');
    }

    return $cacher->set($key, $value, null, $expire);
}

/**
 * KV缓存 删
 *
 * @param string $key 缓存名称
 * @return boolean
 */
function dkcache($key)
{
    if (C('cache_open')) {
        $cacher = \shopec\Core::$instances['cacheredis'];
    } else {
        $cacher = Cache::getInstance('file', null);
    }
    if (!$cacher) {
        throw new Exception('Cannot fetch cache object!');
    }

    return $cacher->rm($key);
}

/**
 * 读取缓存信息
 *
 * @param string $key 要取得缓存键
 * @param string $prefix 键值前缀
 * @param string $fields 所需要的字段
 * @return array/bool
 */
function rcache($key = null, $prefix = '', $fields = '*'){
    if ($key===null || !C('cache_open')) return array();
    /*if (!in_array($prefix,array('adv','all_categories','area','channel','class_tag','contractitem','gc_class','goods_class_seo','index/article','nav','own_shop_ids','seo','setting','setting_updates'))) {
       //redis-bug return true;
        return false;
    }*/
    if (!in_array($prefix,array('adv','all_categories','area','channel','class_tag','contractitem','gc_class','goods_class_seo','index/article','nav','own_shop_ids','seo','setting','setting_updates'))) {
        return array();
    }

    $ins = \shopec\Core::$instances['cacheredis'];
    $cache_info = $ins->hget($key,$prefix,$fields);

    if ($cache_info === false) {
        //取单个字段且未被缓存
        $data  = array();
    } elseif (is_array($cache_info)) {
        //如果有一个键值为false(即未缓存)，则整个函数返回空，让系统重新生成全部缓存
        $data = $cache_info;
        foreach ($cache_info as $k => $v) {
            if ($v === false) {
                $data = array();break;
            }
        }
    } else {
        //string 取单个字段且被缓存
        $data = array($fields => $cache_info);
    }
    // 验证缓存是否过期
    if (isset($data['cache_expiration_time']) && $data['cache_expiration_time'] < TIMESTAMP) {
        $data = array();
    }
    return $data;
}

/**
 * 写入缓存
 *
 * @param string $key 缓存键值
 * @param array $data 缓存数据
 * @param string $prefix 键值前缀
 * @param int $period 缓存周期  单位分，0为永久缓存
 * @return bool 返回值
 */
function wcache($key = null, $data = array(), $prefix, $period = 0){
    if ($key===null || !C('cache_open') || !is_array($data)) return;
   /* if (!in_array($prefix,array('adv','all_categories','area','channel','class_tag','contractitem','gc_class','goods_class_seo','index/article','nav','own_shop_ids','seo','setting','setting_updates'))) {
        return true;
    }*/
    if (!in_array($prefix,array('adv','all_categories','area','channel','class_tag','contractitem','gc_class','goods_class_seo','index/article','nav','own_shop_ids','seo','setting','setting_updates'))) {
        return array();
    }
    $period = intval($period);
    if ($period != 0) {
        $data['cache_expiration_time'] = TIMESTAMP + $period * 60;
    }
    $ins = \shopec\Core::$instances['cacheredis'];
    $ins->hset($key, $prefix, $data);
    $cache_info = $ins->hget($key,$prefix);
    return true;
}

/**
 * 删除缓存
 * @param string $key 缓存键值
 * @param string $prefix 键值前缀
 * @return boolean
 */
function dcache($key = null, $prefix = ''){
    if ($key===null || !C('cache_open')) return true;
   /* if (!in_array($prefix,array('adv','all_categories','area','channel','class_tag','contractitem','gc_class','goods_class_seo','index/article','nav','own_shop_ids','seo','setting','setting_updates'))) {
        return true;
    }*/
    if (!in_array($prefix,array('adv','all_categories','area','channel','class_tag','contractitem','gc_class','goods_class_seo','index/article','nav','own_shop_ids','seo','setting','setting_updates'))) {
        return array();
    }
    $ins = \shopec\Core::$instances['cacheredis'];
    return $ins->hdel($key, $prefix);
}

/**
 * 调用推荐位
 *
 * @param int $rec_id 推荐位ID
 * @return string 推荐位内容
 */
function rec($rec_id = null){
    import('function.rec_position');
    return rec_position($rec_id);
}

/**
 * 快速调用语言包
 *
 * @param string $key
 * @return string
 */
function L($key = ''){
    if (class_exists('Language')){
        if (strpos($key,',') !== false){
            $tmp = explode(',',$key);
            $str = Language::get($tmp[0]).Language::get($tmp[1]);
            return isset($tmp[2])? $str.Language::get($tmp[2]) : $str;
        }else{
            return Language::get($key);
        }
    }else{
        return null;
    }
}

/**
 * 加载完成业务方法的文件
 *
 * @param string $filename
 * @param string $file_ext
 */
function loadfunc($filename, $file_ext = '.php'){
    if(preg_match('/^[\w\d\/_.]+$/i', $filename.$file_ext)){
        $file = realpath(BASE_PATH.'/framework/function/'.$filename.$file_ext);
    }else{
        $file = false;
    }
    if (!$file){
        exit($filename.$file_ext.' isn\'t exists!');
    }else{
        require_once($file);
    }
}

/**
 * 实例化类
 *
 * @param string $model_name 模型名称
 * @return obj 对象形式的返回结果
 */
function nc_class($classname = null){
    static $_cache = array();
    if (!is_null($classname) && isset($_cache[$classname])) return $_cache[$classname];
    $file_name = BASE_PATH.'/framework/libraries/'.$classname.'.class.php';
    $newname = $classname.'Class';
    if (file_exists($file_name)){
        require_once($file_name);
        if (class_exists($newname)){
            return $_cache[$classname] = new $newname();
        }
    }
    throw_exception('Class Error:  Class '.$classname.' is not exists!');
}

/**
 * 加载广告
 *
 * @param  $ap_id 广告位ID
 * @param $type 广告返回类型 html,js
 */
function loadadv($ap_id = null, $type = 'html'){
    if (!is_numeric($ap_id)) return false;
    if (!function_exists('advshow')) import('function.adv');
    return advshow($ap_id,$type);
}
function getWxShareImage($image_name = '')
{

    $image_path = DS . ATTACH_MOBILE . DS . 'home' . DS . $image_name;

    if (C('oss.open')) {
        return C("oss.img_url") . $image_path;
    }
    if (is_file(BASE_UPLOAD_PATH . $image_path)) {
        return UPLOAD_SITE_URL . $image_path;
    } else {
        return UPLOAD_SITE_URL . '/' . defaultGoodsImage('240');
    }
}
/**
 * 格式化ubb标签
 *
 * @param string $theme_content/$reply_content 话题内容/回复内容
 * @return string
 */
function ubb($ubb){
    $ubb = str_replace(array(
            '[B]', '[/B]', '[I]', '[/I]', '[U]', '[/U]', '[IMG]', '[/IMG]', '[/FONT]', '[/FONT-SIZE]', '[/FONT-COLOR]'
    ), array(
            '<b>', '</b>', '<i>', '</i>', '<u>', '</u>', '<img class="pic" src="', '"/>', '</span>', '</span>', '</span>'
    ), preg_replace(array(
            "/\[URL=(.*)\](.*)\[\/URL\]/iU",
            "/\[FONT=([A-Za-z ]*)\]/iU",
            "/\[FONT-SIZE=([0-9]*)\]/iU",
            "/\[FONT-COLOR=([A-Za-z0-9]*)\]/iU",
            "/\[SMILIER=([A-Za-z_]*)\/\]/iU",
            "/\[FLASH\](.*)\[\/FLASH\]/iU",
            "/\\n/i"
    ), array(
            "<a href=\"$1\" target=\"_blank\">$2</a>",
            "<span style=\"font-family:$1\">",
            "<span style=\"font-size:$1px\">",
            "<span style=\"color:#$1\">",
            "<img src=\"".CIRCLE_SITE_URL.'/templates/'.TPL_CIRCLE_NAME."/images/smilier/$1.png\">",
            "<embed src=\"$1\" type=\"application/x-shockwave-flash\" allowscriptaccess=\"always\" allowfullscreen=\"true\" wmode=\"opaque\" width=\"480\" height=\"400\"></embed>",
            "<br />"
    ), $ubb));
    return $ubb;
}
/**
 * 去掉ubb标签
 *
 * @param string $theme_content/$reply_content 话题内容/回复内容
 * @return string
 */
function removeUBBTag($ubb){
    $ubb = str_replace(array(
            '[B]', '[/B]', '[I]', '[/I]', '[U]', '[/U]', '[/FONT]', '[/FONT-SIZE]', '[/FONT-COLOR]'
    ), array(
            '', '', '', '', '', '', '', '', ''
    ), preg_replace(array(
            "/\[URL=(.*)\](.*)\[\/URL\]/iU",
            "/\[FONT=([A-Za-z ]*)\]/iU",
            "/\[FONT-SIZE=([0-9]*)\]/iU",
            "/\[FONT-COLOR=([A-Za-z0-9]*)\]/iU",
            "/\[SMILIER=([A-Za-z_]*)\/\]/iU",
            "/\[IMG\](.*)\[\/IMG\]/iU",
            "/\[FLASH\](.*)\[\/FLASH\]/iU",
            "<img class='pi' src=\"$1\"/>",
    ), array(
            "$2",
            "",
            "",
            "",
            "",
            "",
            "",
            ""
    ), $ubb));
    return $ubb;
}

/**
 * 话题图片绝对路径
 *
 * @param $param string 文件名称
 * @return string
 */
function themeImagePath($param){
    return BASE_UPLOAD_PATH.'/'.ATTACH_CIRCLE.'/theme/'.$param;
}
/**
 * 话题图片url
 *
 * @param $param string
 * @return string
 */
function themeImageUrl($param){
    return UPLOAD_SITE_URL.'/'.ATTACH_CIRCLE.'/theme/'.$param;
}
/**
 * 圈子logo
 *
 * @param $param string 圈子id
 * @return string
 */
function circleLogo($id){
    if(file_exists(BASE_UPLOAD_PATH.'/'.ATTACH_CIRCLE.'/group/'.$id.'.jpg')){
        return UPLOAD_SITE_URL.'/'.ATTACH_CIRCLE.'/group/'.$id.'.jpg';
    }else{
        return UPLOAD_SITE_URL.'/'.ATTACH_CIRCLE.'/default_group_logo.gif';
    }
}
/**
 * sns 来自
 * @param $param string $trace_from
 * @return string
 */
function snsShareFrom($sign) {
    switch ($sign) {
        case '1' :
        case '2' :
            return L('sns_from') . '<a target="_black" href="' . SHOP_SITE_URL . '">' . L('sns_shop') . '</a>';
            break;
        case '3' :
            return L('sns_from') . '<a target="_black" href="' . MICROSHOP_SITE_URL . '">' . L('nc_modules_microshop') . '</a>';
            break;
        case '4' :
            return L('sns_from') . '<a target="_black" href="' . CMS_SITE_URL . '">CMS</a>';
            break;
        case '5' :
            return L('sns_from') . '<a target="_black" href="' . CIRCLE_SITE_URL . '">' . L('nc_circle') . '</a>';
            break;
    }
}

/**
 * 输出聊天信息
 *
 * @return string
 */
function getChat($layout){
    if (!C('node_chat') || !file_exists(BASE_CORE_PATH.'/framework/libraries/chat.php')) return '';
    if (!class_exists('Chat')) import('libraries.chat');
    return Chat::getChatHtml($layout);
}

/**
 * 拼接动态URL，参数需要小写
 *
 * 调用示例
 *
 * 若指向网站首页，可以传空:
 * url() => 表示act和op均为index，返回当前站点网址
 *
 * url('search,'index','array('cate_id'=>2)); 实际指向 index.php?con=search&fun=index&cate_id=2
 * 传递数组参数时，若act（或op）值为index,则可以省略
 * 上面示例等同于
 * url('search','',array('con'=>'search','cate_id'=>2));
 *
 * @param string $act control文件名
 * @param string $op op方法名
 * @param array $args URL其它参数
 * @param boolean $model 默认取当前系统配置
 * @param string $site_url 生成链接的网址，默认取当前网址
 * @return string
 */
function url($act = '', $op = '', $args = array(), $model = false, $site_url = ''){
    //伪静态文件扩展名
    $ext = '.html';
    //入口文件名
    $file = 'index.php';
//    $site_url = empty($site_url) ? SHOP_SITE_URL : $site_url;
    $act = trim($act);
    $op = trim($op);
    $args = !is_array($args) ? array() : $args;
    //定义变量存放返回url
    $url_string = '';
    if (empty($act) && empty($op) && empty($args)) {
        return $site_url;
    }
    $act = !empty($act) ? $act : 'index';
    $op = !empty($op) ? $op : 'index';

    $model = $model ? URL_MODEL : $model;

    if ($model) {
        //伪静态模式
        $url_perfix = "{$act}-{$op}";
        if (!empty($args)){
            $url_perfix .= '-';
        }
        $url_string = $url_perfix.http_build_query($args,'','-').$ext;
        $url_string = str_replace('=','-',$url_string);
    }else {
        //默认路由模式
        $url_perfix = "con={$act}&fun={$op}";
        if (!empty($args)){
            $url_perfix .= '&';
        }
        $url_string = $file.'?'.$url_perfix.http_build_query($args);
    }
    //将商品、店铺、分类、品牌、文章自动生成的伪静态URL使用短URL代替
    $reg_match_from = array(
        '/^category-index\.html$/',
        '/^channel-index-id-(\d+)\.html$/',
        '/^goods-index-goods_id-(\d+)\.html$/',
        '/^show_store-index-store_id-(\d+)\.html$/',
        '/^show_store-goods_all-store_id-(\d+)-stc_id-(\d+)-key-([0-5])-order-([0-2])-curpage-(\d+)\.html$/',
        '/^document-index-code-([a-z_]+)\.html$/',
        '/^search-index-cate_id-(\d+)-b_id-([0-9_]+)-a_id-([0-9_]+)-ci-([0-9_]+)-key-([0-3])-order-([0-2])-type-([0-1])-gift-([0-1])-area_id-(\d+)-curpage-(\d+)\.html$/',
        '/^brand-list-brand-(\d+)-ci-([0-9_]+)-key-([0-3])-order-([0-2])-type-([0-1])-gift-([0-1])-area_id-(\d+)-curpage-(\d+)\.html$/',
        '/^brand-index\.html$/',
        '/^promotion-index\.html$/',
        '/^promotion-index-gc_id-(\d+)\.html$/',

        '/^show_groupbuy-index\.html$/',
        '/^show_groupbuy-groupbuy_detail-group_id-(\d+)\.html$/',

        '/^show_groupbuy-groupbuy_list-class-(\d+)-s_class-(\d+)-groupbuy_price-(\d+)-groupbuy_order_key-(\d+)-groupbuy_order-(\d+)-curpage-(\d+)\.html$/',
        '/^show_groupbuy-groupbuy_soon-class-(\d+)-s_class-(\d+)-groupbuy_price-(\d+)-groupbuy_order_key-(\d+)-groupbuy_order-(\d+)-curpage-(\d+)\.html$/',
        '/^show_groupbuy-groupbuy_history-class-(\d+)-s_class-(\d+)-groupbuy_price-(\d+)-groupbuy_order_key-(\d+)-groupbuy_order-(\d+)-curpage-(\d+)\.html$/',

        '/^show_groupbuy-vr_groupbuy_list-vr_class-(\d+)-vr_s_class-(\d+)-vr_area-(\d+)-vr_mall-(\d+)-groupbuy_price-(\d+)-groupbuy_order_key-(\d+)-groupbuy_order-(\d+)-curpage-(\d+)\.html$/',
        '/^show_groupbuy-vr_groupbuy_soon-vr_class-(\d+)-vr_s_class-(\d+)-vr_area-(\d+)-vr_mall-(\d+)-groupbuy_price-(\d+)-groupbuy_order_key-(\d+)-groupbuy_order-(\d+)-curpage-(\d+)\.html$/',
        '/^show_groupbuy-vr_groupbuy_history-vr_class-(\d+)-vr_s_class-(\d+)-vr_area-(\d+)-vr_mall-(\d+)-groupbuy_price-(\d+)-groupbuy_order_key-(\d+)-groupbuy_order-(\d+)-curpage-(\d+)\.html$/',

        '/^pointshop-index.html$/',
        '/^pointprod-pinfo-id-(\d+).html$/',
        '/^pointvoucher-index.html$/',
        '/^pointgrade-index.html$/',
        '/^pointgrade-exppointlog-curpage-(\d+).html$/',
        '/^goods-comments_list-goods_id-(\d+)-type-([0-4])-curpage-(\d+).html$/'
        );
    $reg_match_to = array(
        'category.html',
        'channel-\\1.html',
        'item-\\1.html',
        'shop-\\1.html',
        'shop_view-\\1-\\2-\\3-\\4-\\5.html',
        'document-\\1.html',
        'cate-\\1-\\2-\\3-\\4-\\5-\\6-\\7-\\8-\\9-\\10.html',
        'brand-\\1-\\2-\\3-\\4-\\5-\\6-\\7-\\8.html',
        'brand.html',
        'promotion.html',
        'promotion-\\1.html',

        'groupbuy.html',
        'groupbuy_detail-\\1.html',

        'groupbuy_list-\\1-\\2-\\3-\\4-\\5-\\6.html',
        'groupbuy_soon-\\1-\\2-\\3-\\4-\\5-\\6.html',
        'groupbuy_history-\\1-\\2-\\3-\\4-\\5-\\6.html',

        'vr_groupbuy_list-\\1-\\2-\\3-\\4-\\5-\\6-\\7-\\8.html',
        'vr_groupbuy_soon-\\1-\\2-\\3-\\4-\\5-\\6-\\7-\\8.html',
        'vr_groupbuy_history-\\1-\\2-\\3-\\4-\\5-\\6-\\7-\\8.html',

        'integral.html',
        'integral_item-\\1.html',
        'voucher.html',
        'grade.html',
        'explog-\\1.html',
        'comments-\\1-\\2-\\3.html'
    );
    $url_string = preg_replace($reg_match_from,$reg_match_to,$url_string);
    return rtrim($site_url,'/').'/'.$url_string;
}

/**
 * 商城会员中心使用的URL链接函数，强制使用动态传参数模式
 *
 * @param string $act control文件名
 * @param string $op op方法名
 * @param array $args URL其它参数
 * @param string $store_domian 店铺二级域名
 * @return string
 */
function urlShop($act = '', $op = '', $args = array(), $store_domain = ''){

    // 如果是自营店则返回javascript:;
    /*
    if ($act == 'show_store' && $op != 'goods_all') {
        static $ownShopIds = null;
        if ($ownShopIds === null) {
            $ownShopIds = Model('store')->getOwnShopIds();
        }
        if (isset($args['store_id']) && in_array($args['store_id'], $ownShopIds)) {
            return 'javascript:;';
        }
    }
    */

    // 开启店铺二级域名
    if (intval(C('enabled_subdomain')) == 1 && !empty($store_domain)){
        return 'http://'.$store_domain.'.'.SUBDOMAIN_SUFFIX.'/';
    }
    if ($act == 'search' && $op == 'index' && $args['cate_id'] > 0 && empty($args['keyword'])) {//商品搜索列表页只有商品分类参数
        $id = intval($args['cate_id']);
        $channel_list  = rkcache('channel',true);
        if ($channel_list[$id] > 0) {//商品分类与频道的绑定
            $act = 'channel';
            $args = array();
            $args['id'] = $channel_list[$id];
        }
    }

    // 默认标志为不开启伪静态
    $rewrite_flag = false;

    // 如果平台开启伪静态开关，并且为伪静态模块，修改标志为开启伪静态
    $rewrite_item = array(
        'category:index',
        'channel:index',
        'goods:index',
        'goods:comments_list',
        'search:index',
        'show_store:index',
        'show_store:goods_all',
        'article:show',
        'article:article',
        'document:index',
        'brand:list',
        'brand:index',
        'promotion:index',
        'show_groupbuy:index',
        'show_groupbuy:groupbuy_detail',
        'show_groupbuy:groupbuy_list',
        'show_groupbuy:groupbuy_soon',
        'show_groupbuy:groupbuy_history',
        'show_groupbuy:vr_groupbuy_list',
        'show_groupbuy:vr_groupbuy_soon',
        'show_groupbuy:vr_groupbuy_history',
        'pointshop:index',
        'pointvoucher:index',
        'pointprod:pinfo',
        'pointprod:plist',
        'pointgrade:index',
        'pointgrade:exppointlog',
        'store_snshome:index',
    );
    if(URL_MODEL && in_array($act.':'.$op, $rewrite_item)) {
        $rewrite_flag = true;
        $tpl_args = array();        // url参数临时数组
        switch ($act.':'.$op) {
            case 'search:index':
                if (!empty($args['keyword'])) {
                    $rewrite_flag = false;
                    break;
                }
                $tpl_args['cate_id'] = empty($args['cate_id']) ? 0 : $args['cate_id'];
                $tpl_args['b_id'] = empty($args['b_id']) || intval($args['b_id']) == 0 ? 0 : $args['b_id'];
                $tpl_args['a_id'] = empty($args['a_id']) || intval($args['a_id']) == 0 ? 0 : $args['a_id'];
                $tpl_args['ci'] = empty($args['ci']) || intval($args['ci']) == 0 ? 0 : $args['ci'];
                $tpl_args['key'] = empty($args['key']) ? 0 : $args['key'];
                $tpl_args['order'] = empty($args['order']) ? 0 : $args['order'];
                $tpl_args['type'] = empty($args['type']) ? 0 : $args['type'];
                $tpl_args['gift'] = empty($args['gift']) ? 0 : $args['gift'];
                $tpl_args['area_id'] = empty($args['area_id']) ? 0 : $args['area_id'];
                $tpl_args['curpage'] = empty($args['curpage']) ? 0 : $args['curpage'];
                $args = $tpl_args;
                break;
            case 'show_store:goods_all':
                if (isset($args['inkeyword'])) {
                    $rewrite_flag = false;
                    break;
                }
                $tpl_args['store_id'] = empty($args['store_id']) ? 0 : $args['store_id'];
                $tpl_args['stc_id'] = empty($args['stc_id']) ? 0 : $args['stc_id'];
                $tpl_args['key'] = empty($args['key']) ? 0 : $args['key'];
                $tpl_args['order'] = empty($args['order']) ? 0 : $args['order'];
                $tpl_args['curpage'] = empty($args['curpage']) ? 0 : $args['curpage'];
                $args = $tpl_args;
                break;
            case 'brand:list':
                $tpl_args['brand'] = empty($args['brand']) ? 0 : $args['brand'];
                $tpl_args['ci'] = empty($args['ci']) || intval($args['ci']) == 0 ? 0 : $args['ci'];
                $tpl_args['key'] = empty($args['key']) ? 0 : $args['key'];
                $tpl_args['order'] = empty($args['order']) ? 0 : $args['order'];
                $tpl_args['type'] = empty($args['type']) ? 0 : $args['type'];
                $tpl_args['gift'] = empty($args['gift']) ? 0 : $args['gift'];
                $tpl_args['area_id'] = empty($args['area_id']) ? 0 : $args['area_id'];
                $tpl_args['curpage'] = empty($args['curpage']) ? 0 : $args['curpage'];
                $args = $tpl_args;
                break;

            case 'show_groupbuy:index':
            case 'show_groupbuy:groupbuy_detail':
                break;

            case 'show_groupbuy:groupbuy_list':
            case 'show_groupbuy:groupbuy_soon':
            case 'show_groupbuy:groupbuy_history':
                $tpl_args['class'] = empty($args['class']) ? 0 : $args['class'];
                $tpl_args['s_class'] = empty($args['s_class']) ? 0 : $args['s_class'];
                $tpl_args['groupbuy_price'] = empty($args['groupbuy_price']) ? 0 : $args['groupbuy_price'];
                $tpl_args['groupbuy_order_key'] = empty($args['groupbuy_order_key']) ? 0 : $args['groupbuy_order_key'];
                $tpl_args['groupbuy_order'] = empty($args['groupbuy_order']) ? 0 : $args['groupbuy_order'];
                $tpl_args['curpage'] = empty($args['curpage']) ? 0 : $args['curpage'];
                $args = $tpl_args;
                break;

            case 'show_groupbuy:vr_groupbuy_list':
            case 'show_groupbuy:vr_groupbuy_soon':
            case 'show_groupbuy:vr_groupbuy_history':
                $tpl_args['vr_class'] = empty($args['vr_class']) ? 0 : $args['vr_class'];
                $tpl_args['vr_s_class'] = empty($args['vr_s_class']) ? 0 : $args['vr_s_class'];
                $tpl_args['vr_area'] = empty($args['vr_area']) ? 0 : $args['vr_area'];
                $tpl_args['vr_mall'] = empty($args['vr_mall']) ? 0 : $args['vr_mall'];
                $tpl_args['groupbuy_price'] = empty($args['groupbuy_price']) ? 0 : $args['groupbuy_price'];
                $tpl_args['groupbuy_order_key'] = empty($args['groupbuy_order_key']) ? 0 : $args['groupbuy_order_key'];
                $tpl_args['groupbuy_order'] = empty($args['groupbuy_order']) ? 0 : $args['groupbuy_order'];
                $tpl_args['curpage'] = empty($args['curpage']) ? 0 : $args['curpage'];
                $args = $tpl_args;
                break;

            case 'goods:comments_list':
                $tpl_args['goods_id'] = empty($args['goods_id']) ? 0 : $args['goods_id'];
                $tpl_args['type'] = empty($args['type']) ? 0 : $args['type'];
                $tpl_args['curpage'] = empty($args['curpage']) ? 0 : $args['curpage'];
                $args = $tpl_args;
                break;

            case 'pointgrade:exppointlog':
                $tpl_args['curpage'] = empty($args['curpage']) ? 0 : $args['curpage'];
                $args = $tpl_args;
                break;
            case 'promotion:index':
                $args = empty($args['gc_id']) ? null : $args;
                break;
            default:
                break;
        }
    }

    return url($act, $op, $args, $rewrite_flag, SHOP_SITE_URL);
}
/**
 * 手机商家页url，强制使用动态传参数模式
 *
 * @param string $con control文件名
 * @param string $fun op方法名
 * @param array $args URL其它参数
 * @return string
 */
function urlMobile($con = '', $fun = '', $args = array()){
    return url($con, $fun, $args, false, SELLER_SITE_URL);
}
/**
 * 商城后台使用的URL链接函数，强制使用动态传参数模式
 *
 * @param string $act control文件名
 * @param string $op op方法名
 * @param array $args URL其它参数
 * @return string
 */
function urlAdmin($act = '', $op = '', $args = array()){
    return url($act, $op, $args, false, ADMIN_SITE_URL);
}
function urlAdminShop($act = '', $op = '', $args = array()){
    return url($act, $op, $args, false, ADMIN_SITE_URL.DS.ADMIN_MODULES_SHOP);
}
function urlAdminCms($act = '', $op = '', $args = array()){
    return url($act, $op, $args, false, ADMIN_SITE_URL.DS.ADMIN_MODULES_CMS);
}
function urlAdminMobile($act = '', $op = '', $args = array()){
    return url($act, $op, $args, false, ADMIN_SITE_URL.DS.ADMIN_MODULES_MOBILE);
}
function urlAdminCircle($act = '', $op = '', $args = array()){
    return url($act, $op, $args, false, ADMIN_SITE_URL.DS.ADMIN_MODULES_CIECLE);
}
function urlAdminDistribute($act = '', $op = '', $args = array()){
    return url($act, $op, $args, false, ADMIN_SITE_URL.DS.ADMIN_MODULES_DISTRIBUTE);
}
/**
 * CMS使用的URL链接函数，强制使用动态传参数模式
 *
 * @param string $act control文件名
 * @param string $op op方法名
 * @param array $args URL其它参数
 * @return string
 */
function urlCMS($act = '', $op = '', $args = array()){
    return url($act, $op, $args, false, CMS_SITE_URL);
}
/**
 * 圈子使用的URL链接函数，强制使用动态传参数模式
 *
 * @param string $act control文件名
 * @param string $op op方法名
 * @param array $args URL其它参数
 * @return string
 */
function urlCircle($act = '', $op = '', $args = array()){
    return url($act, $op, $args, false, CIRCLE_SITE_URL);
}
/**
 * 微商城使用的URL链接函数，强制使用动态传参数模式
 *
 * @param string $act control文件名
 * @param string $op op方法名
 * @param array $args URL其它参数
 * @return string
 */
function urlMicroshop($act = '', $op = '', $args = array()){
    return url($act, $op, $args, false, MICROSHOP_SITE_URL);
}
/**
 * 会员中心使用的URL链接函数，强制使用动态传参数模式
 * 
 * @param string $act control文件名
 * @param string $op op方法名
 * @param unknown $args URL其它参数
 * @return string
 */
function urlMember($act = '', $op = '', $args = array()) {
    // 默认标志为不开启伪静态
    $rewrite_flag = false;
    
    // 如果平台开启伪静态开关，并且为伪静态模块，修改标志为开启伪静态
    $rewrite_item = array(
            'article:show',
            'article:article'
    );
    if(URL_MODEL && in_array($act.':'.$op, $rewrite_item)) {
        $rewrite_flag = true;
    }
    return url($act, $op, $args, $rewrite_flag, MEMBER_SITE_URL);
}
/**
 * 会员登录使用的URL链接函数，强制使用动态传参数模式
 * @param string $act control文件名
 * @param string $op op方法名
 * @param unknown $args URL其它参数
 * @return string
 */
function urlLogin($act = '', $op = '', $args = array()) {
    return url($act, $op, $args, false, LOGIN_SITE_URL);
}
/**
 * 门店使用的URL链接函数，强制使用动态传参数模式
 * @param string $act control文件名
 * @param string $op op方法名
 * @param unknown $args URL其它参数
 * @return string
 */
function urlChain($act = '', $op = '', $args = array()){
    return url($act, $op, $args, false, CHAIN_SITE_URL);
}
/**
 * 分销使用的URL链接函数，强制使用动态传参数模式
 * @param string $act control文件名
 * @param string $op op方法名
 * @param unknown $args URL其它参数
 * @return string
 */
function urlDistribute($act = '', $op = '', $args = array()){
    return url($act, $op, $args, false, DISTRIBUTE_SITE_URL);
}
/**
 * 验证是否为平台店铺
 *
 * @return boolean
 */
function checkPlatformStore($store_id = 0){
    if (isset($_SESSION['is_own_shop'])) {
        return $_SESSION['is_own_shop'];
    } else {
        $own_shop_ids = Model('store')->getOwnShopIds(true);
        return in_array($store_id, $own_shop_ids);
    }
}

/**
 * 验证是否为平台店铺 并且绑定了全部商品类目
 *
 * @return boolean
 */
function checkPlatformStoreBindingAllGoodsClass($store_id = 0, $bind_all_gc = 0){
    if (isset($_SESSION['is_own_shop'])) {
        return checkPlatformStore() && $_SESSION['bind_all_gc'];
    } else {
        return $store_id && $bind_all_gc;
    }
}

/**
 * 将字符部分加密并输出
 * @param unknown $str
 * @param unknown $start 从第几个位置开始加密(从1开始)
 * @param unknown $length 连续加密多少位
 * @return string
 */
function encryptShow($str,$start,$length) {
    $end = $start - 1 + $length;
    $array = str_split($str);
    foreach ($array as $k => $v) {
        if ($k >= $start-1 && $k < $end) {
            $array[$k] = '*';
        }
    }
    return implode('',$array);
}

/**
 * 规范数据返回函数
 * @param unknown $state
 * @param unknown $msg
 * @param unknown $data
 * @return multitype:unknown
 */
function callback($state = true, $msg = '', $data = array()) {
    return array('state' => $state, 'msg' => $msg, 'data' => $data);
}

/**
 * flexigrid.js返回的数组
 * @param array $in_array 需要进行赋值的数据（提供给页面中JS使用）
 * @param array $fields_array 赋值下标的数组
 * @param array $data 从数据库读出的未处理数据
 * @param array $format_array 格式化价格下标的数组
 * @return array 处理后的数据
 */
function getFlexigridArray($in_array,$fields_array,$data,$format_array = array()) {
    $out_array = $in_array;
    if (empty($out_array['operation'])) {
        $out_array['operation'] = '--';
    }
    if (!empty($fields_array) && is_array($fields_array)) {
        foreach ($fields_array as $key => $value) {
            $k = '';
            if (is_int($key)) {
                $k = $value;
            } else {
                $k = $key;
            }
            if (is_array($data) && array_key_exists($k, $data)) {
                $out_array[$k] = $data[$k];
                if (!empty($format_array) && in_array($k,$format_array)) {
                    $out_array[$k] = ncPriceFormat($data[$k]);
                }
            } else {
                $out_array[$k] = '--';
            }
        }
    }
    return $out_array;
}

/**
 * flexigrid.js返回的数组列表
 * @param array $list 从数据库读出的未处理列表
 * @param array $fields_array 赋值下标的数组
 * @param array $format_array 格式化价格下标的数组
 * @return array 处理后的数据
 */
function getFlexigridList($list,$fields_array,$format_array = array()) {
    $out_list = array();
    if (!empty($list) && is_array($list)) {
        foreach ($list as $key => $value) {
            $out_list[] = getFlexigridArray(array(),$fields_array,$value,$format_array);
        }
    }
    return $out_list;
}

/**
 * 会员标签图片
 * @param unknown $img
 * @return string
 */
function getMemberTagimage($img) {
    return UPLOAD_SITE_URL.'/'.ATTACH_PATH.'/membertag/'.($img != ''?$img:'default_tag.gif');
}

/**
 * 门店图片
 * @param string $image
 * @param int $store_id
 * @return string
 */
function getChainImage($image, $store_id) {
    return UPLOAD_SITE_URL.DS.ATTACH_CHAIN.DS.$store_id.DS.$image;
}

/**
 * 分销提现方式
 * @param string $payment_code
 * @return string
 */
function getDistriBillName($payment_code) {
    return str_replace(
        array('bank','alipay'),
        array('银行账号','支付宝'),
        $payment_code);
}

/**
 * 获取唯一码
 * @param $key
 * @return string
 */
function getUniqueCode($key){
    static $guid = '';
    $uid = uniqid("", true);
    $data = $key;
    $data .= $_SERVER['REQUEST_TIME'];
    $data .= $_SERVER['HTTP_USER_AGENT'];
    $data .= $_SERVER['LOCAL_ADDR'];
    $data .= $_SERVER['LOCAL_PORT'];
    $data .= $_SERVER['REMOTE_ADDR'];
    $data .= $_SERVER['REMOTE_PORT'];
    $hash = strtoupper(hash('ripemd128', $uid . $guid . md5($data)));
    $guid = substr($hash,  0,  8) .
        '-' .
        substr($hash,  8,  4) .
        '-' .
        substr($hash, 12,  4) .
        '-' .
        substr($hash, 16,  4) .
        '-' .
        substr($hash, 20, 12);
    return $guid;
}

/*
 * Aes加密
 * @author  龚波
 * @date    20161121
 */

function _encrypt($str,$key=null){
    $Aes = new Aes();
    return $Aes->encrypt($str,$key);
}

/*
 * Aes解密
 * @author  龚波
 * @date    20161121
 */
function _decrypt($str,$key=null){
    $Aes = new Aes();
    return $Aes->decrypt($str,$key);
}

/**
 * AES PKCS加密
 * @param $url
 * @param null $data
 * @param null $header
 * @return mixed
 */
function pkcs5_encrypt($str,$key){
    $pkcs = new PKCS5($key);
    return $pkcs->generate_text ($str);
}


/*
 * curl_post
 * @author  龚波
 * @date    20161121
 */
function curl_post($url,$data = null,$header=null){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    if (!empty($data)){
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    if(!empty($header)){
        //curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt ( $curl, CURLOPT_HTTPHEADER, $header);
    }
    $output = curl_exec($curl);
    curl_close($curl);
    return $output;
}

/*
 * curl_get
 * @author  龚波
 * @date    20161123
 */
function curl_get($url,$data = null,$header=null){
    //初始化
    $curl = curl_init();
    if (!empty($data)){
        $url .="?".http_build_query($data);
    }
    if(!empty($header)){
        //curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    }
    curl_setopt($curl, CURLOPT_URL, $url);
    //设置头文件的信息作为数据流输出

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($curl);
    curl_close($curl);
    return $data;
}

/*
 * 银行卡验证方法
 * @author  龚波
 * @date    20161124
 */

function _check_bank_card($bank_card){
    $appkey = C('bankcard.key')?C('bankcard.key'):'30051ebaa0224eef9f626129de91a61c';
    //接口验证，待开发
    $url = 'http://detectionBankCard.api.juhe.cn/bankCard';
    $data= array(
        'key'       =>  $appkey,     //聚合数据平台验证key
        'cardid'    =>  $bank_card
    );
    $curl_rs = curl_get($url,$data);
    $curl_arr = _object_array((Array)json_decode($curl_rs));
    return $curl_arr;
}

/*
 * stdClass Object转array
 * @author  龚波
 * @date    20161124
 */
function _object_array($array) {
    if(is_object($array)) {
        $array = (array)$array;
    } if(is_array($array)) {
        foreach($array as $key=>$value){

            $array[$key] = _object_array($value);
        }
    }
    return $array;
}
