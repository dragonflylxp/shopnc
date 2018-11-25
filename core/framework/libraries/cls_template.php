<?php
/**
*by:511613932
 */

defined('Inshopec') or exit('Access Invalid!');
class cls_template
{
	public $template_dir = '';
	public $cache_dir = '';
	public $compile_dir = '';
	public $cache_lifetime = 3600;
	public $direct_output = false;
	public $caching = false;
	public $template = array();
	public $force_compile = false;
	public $_var = array();
	public $_echash = '554fcae493e564ee0dc75bdf2ebf94ca';
	public $_foreach = array();
	public $_current_file = '';
	public $_expires = 0;
	public $_errorlevel = 0;
	public $_nowtime;
	public $_checkfile = true;
	public $_foreachmark = '';
	public $_seterror = 0;
	public $_temp_key = array();
	public $_temp_val = array();

	public function __construct()
	{
		$this->cls_template();
	}

	public function cls_template()
	{
		$this->_errorlevel = error_reporting();
		$this->_nowtime = time();

		if (defined('EC_CHARSET')) {
			$charset = EC_CHARSET;
		}
		else {
			$charset = 'utf-8';
		}

		header('Content-type: text/html; charset=' . $charset);
	}

	public function assign($tpl_var, $value = '')
	{
		if (is_array($tpl_var)) {
			foreach ($tpl_var as $key => $val) {
				if ($key != '') {
					$this->_var[$key] = $val;
				}
			}
		}
		else if ($tpl_var != '') {
			$this->_var[$tpl_var] = $value;
		}
	}

	public function display($filename, $cache_id = '', $not_cache = 'yes')
	{
		$this->_seterror++;
		error_reporting(32767 ^ 8);
		$this->_checkfile = false;
		$out = $this->fetch($filename, $cache_id, $not_cache);

		if (strpos($out, $this->_echash) !== false) {
			$k = explode($this->_echash, $out);

			foreach ($k as $key => $val) {
				if (($key % 2) == 1) {
					$k[$key] = $this->insert_mod($val);
				}
			}

			$out = implode('', $k);
		}

		error_reporting($this->_errorlevel);
		$this->_seterror--;
		echo $out;
	}

	public function fetch($filename, $cache_id = '', $not_cache = 'yes')
	{
		if (!$this->_seterror) {
			error_reporting(32767 ^ 8);
		}

		$this->_seterror++;

		if (strncmp($filename, 'str:', 4) == 0) {
			$out = $this->_eval($this->fetch_str(substr($filename, 4)));
		}
		else {
			if ($this->_checkfile) {
				if (!file_exists($filename)) {
					$filename = $this->template_dir . '/' . $filename;
				}
			}
			else {
				$filename = $this->template_dir . '/' . $filename;
			}

			if ($this->direct_output) {
				$this->_current_file = $filename;
				$out = $this->_eval($this->fetch_str(file_get_contents($filename)));
			}
			else {
				if ($cache_id && $this->caching) {
					$out = $this->template_out;
				}
				else {
					if (!in_array($filename, $this->template)) {
						$this->template[] = $filename;
					}

					$out = $this->make_compiled($filename, $not_cache);

					if ($cache_id) {
						$cachename = basename($filename, strrchr($filename, '.')) . '_' . $cache_id;
						$data = serialize(array('template' => $this->template, 'expires' => $this->_nowtime + $this->cache_lifetime, 'maketime' => $this->_nowtime));
						$out = str_replace("\r", '', $out);

						while (strpos($out, "\n\n") !== false) {
							$out = str_replace("\n\n", "\n", $out);
						}

						if ($GLOBALS['_CFG']['open_memcached'] == 1) {
							$GLOBALS['cache']->set('caches_' . $cachename, '<?php exit;?>' . $data . $out);
						}
						else {
							$hash_dir = $this->cache_dir . '/' . substr(md5($cachename), 0, 1);

							if (!is_dir($hash_dir)) {
								mkdir($hash_dir);
							}

							if (file_put_contents($hash_dir . '/' . $cachename . '.php', '<?php exit;?>' . $data . $out, LOCK_EX) === false) {
								trigger_error('can\'t write:' . $hash_dir . '/' . $cachename . '.php');
							}
						}

						$this->template = array();
					}
				}
			}
		}

		$this->_seterror--;

		if (!$this->_seterror) {
			error_reporting($this->_errorlevel);
		}

		return $out;
	}

	public function make_compiled($filename, $not_cache)
	{
		$name = $this->compile_dir . '/' . basename($filename) . '.php';

		if ($this->_expires) {
			$expires = $this->_expires - $this->cache_lifetime;
		}
		else {
			$filestat = @stat($name);
			$expires = $filestat['mtime'];
		}

		$filestat = @stat($filename);
		if (($filestat['mtime'] <= $expires) && !$this->force_compile) {
			if (file_exists($name)) {
				if ($GLOBALS['_CFG']['open_memcached'] == 1) {
					$name = str_replace(array(':', '/', '\\', '.'), '_', $name);
					$source = $GLOBALS['cache']->get('compiled_' . $name);
					$source = $this->_eval($source);
				}
				else {
					$source = $this->_require($name);
				}

				if ($source == '') {
					$expires = 0;
				}
			}
			else {
				$source = '';
				$expires = 0;
			}
		}

		if ($this->force_compile || ($expires < $filestat['mtime'])) {
			$this->_current_file = $filename;
			$source = $this->fetch_str(file_get_contents($filename));

			if ($not_cache != 'not') {
				if ($GLOBALS['_CFG']['open_memcached'] == 1) {
					$GLOBALS['cache']->set('compiled_' . $name, $source);
				}
				else if (file_put_contents($name, $source, LOCK_EX) === false) {
					trigger_error('can\'t write:' . $name);
				}
			}

			$source = $this->_eval($source);
		}

		return $source;
	}

	public function fetch_str($source)
	{
		if (!defined('ECS_ADMIN')) {
			$source = $this->smarty_prefilter_preCompile($source);
		}

		return preg_replace_callback("/{([^\\}\\{\n]*)}/", function($r) {
			return $this->select($r[1]);
		}, $source);
	}

	public function is_cached($filename, $cache_id = '')
	{
		$cachename = basename($filename, strrchr($filename, '.')) . '_' . $cache_id;
		if (($this->caching == true) && ($this->direct_output == false)) {
			if ($GLOBALS['_CFG']['open_memcached'] == 1) {
				$data = $GLOBALS['cache']->get('caches_' . $cachename);
			}
			else {
				$hash_dir = $this->cache_dir . '/' . substr(md5($cachename), 0, 1);
				$data = @file_get_contents($hash_dir . '/' . $cachename . '.php');
			}

			if ($data) {
				$data = substr($data, 13);
				$pos = strpos($data, '<');
				$paradata = substr($data, 0, $pos);
				$para = @unserialize($paradata);
				if (($para === false) || ($para['expires'] < $this->_nowtime)) {
					$this->caching = false;
					return false;
				}

				$this->_expires = $para['expires'];
				$this->template_out = substr($data, $pos);

				foreach ($para['template'] as $val) {
					$stat = @stat($val);

					if ($para['maketime'] < $stat['mtime']) {
						$this->caching = false;
						return false;
					}
				}
			}
			else {
				$this->caching = false;
				return false;
			}

			return true;
		}
		else {
			return false;
		}
	}

	public function select($tag)
	{
		$tag = stripslashes(trim($tag));

		if (empty($tag)) {
			return '{}';
		}
		else {
			if (($tag[0] == '*') && (substr($tag, -1) == '*')) {
				return '';
			}
			else if ($tag[0] == '$') {
				return '<?php echo ' . $this->get_val(substr($tag, 1)) . '; ?>';
			}
			else if ($tag[0] == '/') {
				switch (substr($tag, 1)) {
				case 'if':
					return '<?php endif; ?>';
					break;

				case 'foreach':
					if ($this->_foreachmark == 'foreachelse') {
						$output = '<?php endif; unset($_from); ?>';
					}
					else {
						array_pop($this->_patchstack);
						$output = '<?php endforeach; endif; unset($_from); ?>';
					}

					$output .= '<?php $this->pop_vars();; ?>';
					return $output;
					break;

				case 'literal':
					return '';
					break;

				default:
					return '{' . $tag . '}';
					break;
				}
			}
			else {
				$tag_arr = explode(' ', $tag);
				$tag_sel = array_shift($tag_arr);

				switch ($tag_sel) {
				case 'if':
					return $this->_compile_if_tag(substr($tag, 3));
					break;

				case 'else':
					return '<?php else: ?>';
					break;

				case 'elseif':
					return $this->_compile_if_tag(substr($tag, 7), true);
					break;

				case 'foreachelse':
					$this->_foreachmark = 'foreachelse';
					return '<?php endforeach; else: ?>';
					break;

				case 'foreach':
					$this->_foreachmark = 'foreach';

					if (!isset($this->_patchstack)) {
						$this->_patchstack = array();
					}

					return $this->_compile_foreach_start(substr($tag, 8));
					break;

				case 'assign':
					$t = $this->get_para(substr($tag, 7), 0);

					if ($t['value'][0] == '$') {
						$tmp = '$this->assign(\'' . $t['var'] . '\',' . $t['value'] . ');';
					}
					else {
						$tmp = '$this->assign(\'' . $t['var'] . '\',\'' . addcslashes($t['value'], '\'') . '\');';
					}

					return '<?php ' . $tmp . ' ?>';
					break;

				case 'include':
					$t = $this->get_para(substr($tag, 8), 0);
					return '<?php echo $this->fetch(' . '\'' . $t['file'] . '\'' . '); ?>';
					break;

				case 'insert_scripts':
					$t = $this->get_para(substr($tag, 15), 0);
					return '<?php echo $this->smarty_insert_scripts(' . $this->make_array($t) . '); ?>';
					break;

				case 'create_pages':
					$t = $this->get_para(substr($tag, 13), 0);
					return '<?php echo $this->smarty_create_pages(' . $this->make_array($t) . '); ?>';
					break;

				case 'insert':
					$t = $this->get_para(substr($tag, 7), false);
					$out = "<?php \n" . '$k = ' . preg_replace_callback('/(\\\'\\$[^,]+)/', function($r) {
						return stripslashes(trim($r[1], '\''));
					}, var_export($t, true)) . ";\n";
					$out .= 'echo $this->_echash . $k[\'name\'] . \'|\' . serialize($k) . $this->_echash;' . "\n?>";
					return $out;
					break;

				case 'literal':
					return '';
					break;

				case 'cycle':
					$t = $this->get_para(substr($tag, 6), 0);
					return '<?php echo $this->cycle(' . $this->make_array($t) . '); ?>';
					break;

				case 'html_options':
					$t = $this->get_para(substr($tag, 13), 0);
					return '<?php echo $this->html_options(' . $this->make_array($t) . '); ?>';
					break;

				case 'html_select_date':
					$t = $this->get_para(substr($tag, 17), 0);
					return '<?php echo $this->html_select_date(' . $this->make_array($t) . '); ?>';
					break;

				case 'html_radios':
					$t = $this->get_para(substr($tag, 12), 0);
					return '<?php echo $this->html_radios(' . $this->make_array($t) . '); ?>';
					break;

				case 'html_select_time':
					$t = $this->get_para(substr($tag, 12), 0);
					return '<?php echo $this->html_select_time(' . $this->make_array($t) . '); ?>';
					break;

				default:
					return '{' . $tag . '}';
					break;
				}
			}
		}
	}

	public function get_val($val)
	{
		if (strrpos($val, '[') !== false) {
			$val = preg_replace_callback('/\\[([^\\[\\]]*)\\]/', function($r) {
				return '.' . str_replace('$', '$', $r[1]);
			}, $val);
		}

		if (strrpos($val, '|') !== false) {
			$moddb = explode('|', $val);
			$val = array_shift($moddb);
		}

		if (empty($val)) {
			return '';
		}

		if (strpos($val, '.$') !== false) {
			$all = explode('.$', $val);

			foreach ($all as $key => $val) {
				$all[$key] = $key == 0 ? $this->make_var($val) : '[' . $this->make_var($val) . ']';
			}

			$p = implode('', $all);
		}
		else {
			$p = $this->make_var($val);
		}

		if (!empty($moddb)) {
			foreach ($moddb as $key => $mod) {
				$s = explode(':', $mod);

				switch ($s[0]) {
				case 'escape':
					$s[1] = trim($s[1], '"');

					if ($s[1] == 'html') {
						$p = 'htmlspecialchars(' . $p . ')';
					}
					else if ($s[1] == 'url') {
						$p = 'urlencode(' . $p . ')';
					}
					else if ($s[1] == 'decode_url') {
						$p = 'urldecode(' . $p . ')';
					}
					else if ($s[1] == 'quotes') {
						$p = 'addslashes(' . $p . ')';
					}
					else if ($s[1] == 'u8_url') {
						if (EC_CHARSET != 'utf-8') {
							$p = 'urlencode(ecs_iconv("' . EC_CHARSET . '", "utf-8",' . $p . '))';
						}
						else {
							$p = 'urlencode(' . $p . ')';
						}
					}
					else {
						$p = 'htmlspecialchars(' . $p . ')';
					}

					break;

				case 'nl2br':
					$p = 'nl2br(' . $p . ')';
					break;

				case 'default':
					$s[1] = $s[1][0] == '$' ? $this->get_val(substr($s[1], 1)) : '\'' . $s[1] . '\'';
					$p = 'empty(' . $p . ') ? ' . $s[1] . ' : ' . $p;
					break;

				case 'truncate':
					$p = 'sub_str(' . $p . ',' . $s[1] . ')';
					break;

				case 'strip_tags':
					$p = 'strip_tags(' . $p . ')';
					break;

				default:
					break;
				}
			}
		}

		return $p;
	}

	public function make_var($val)
	{
		if (strrpos($val, '.') === false) {
			if (isset($this->_var[$val]) && isset($this->_patchstack[$val])) {
				$val = $this->_patchstack[$val];
			}

			$p = '$this->_var[\'' . $val . '\']';
		}
		else {
			$t = explode('.', $val);
			$_var_name = array_shift($t);
			if (isset($this->_var[$_var_name]) && isset($this->_patchstack[$_var_name])) {
				$_var_name = $this->_patchstack[$_var_name];
			}

			if ($_var_name == 'smarty') {
				$p = $this->_compile_smarty_ref($t);
			}
			else {
				$p = '$this->_var[\'' . $_var_name . '\']';
			}

			foreach ($t as $val) {
				$p .= '[\'' . $val . '\']';
			}
		}

		return $p;
	}

	public function get_para($val, $type = 1)
	{
		$pa = $this->str_trim($val);

		foreach ($pa as $value) {
			if (strrpos($value, '=')) {
				list($a, $b) = explode('=', str_replace(array(' ', '"', '\'', '&quot;'), '', $value));

				if ($b[0] == '$') {
					if ($type) {
						eval ('$para[\'' . $a . '\']=' . $this->get_val(substr($b, 1)) . ';');
					}
					else {
						$para[$a] = $this->get_val(substr($b, 1));
					}
				}
				else {
					$para[$a] = $b;
				}
			}
		}

		return $para;
	}

	public function& get_template_vars($name = NULL)
	{
		if (empty($name)) {
			return $this->_var;
		}
		else if (!empty($this->_var[$name])) {
			return $this->_var[$name];
		}
		else {
			$_tmp = NULL;
			return $_tmp;
		}
	}

	public function _compile_if_tag($tag_args, $elseif = false)
	{
		preg_match_all('/\\-?\\d+[\\.\\d]+|\'[^\'|\\s]*\'|"[^"|\\s]*"|[\\$\\w\\.]+|!==|===|==|!=|<>|<<|>>|<=|>=|&&|\\|\\||\\(|\\)|,|\\!|\\^|=|&|<|>|~|\\||\\%|\\+|\\-|\\/|\\*|\\@|\\S/', $tag_args, $match);
		$tokens = $match[0];
		$token_count = array_count_values($tokens);
		if (!empty($token_count['(']) && ($token_count['('] != $token_count[')'])) {
		}

		$i = 0;

		for ($count = count($tokens); $i < $count; $i++) {
			$token = &$tokens[$i];

			switch (strtolower($token)) {
			case 'eq':
				$token = '==';
				break;

			case 'ne':
			case 'neq':
				$token = '!=';
				break;

			case 'lt':
				$token = '<';
				break;

			case 'le':
			case 'lte':
				$token = '<=';
				break;

			case 'gt':
				$token = '>';
				break;

			case 'ge':
			case 'gte':
				$token = '>=';
				break;

			case 'and':
				$token = '&&';
				break;

			case 'or':
				$token = '||';
				break;

			case 'not':
				$token = '!';
				break;

			case 'mod':
				$token = '%';
				break;

			default:
				if ($token[0] == '$') {
					$token = $this->get_val(substr($token, 1));
				}

				break;
			}
		}

		if ($elseif) {
			return '<?php elseif (' . implode(' ', $tokens) . '): ?>';
		}
		else {
			return '<?php if (' . implode(' ', $tokens) . '): ?>';
		}
	}

	public function _compile_foreach_start($tag_args)
	{
		$attrs = $this->get_para($tag_args, 0);
		$arg_list = array();
		$from = $attrs['from'];
		if (isset($this->_var[$attrs['item']]) && !isset($this->_patchstack[$attrs['item']])) {
			$this->_patchstack[$attrs['item']] = $attrs['item'] . '_' . str_replace(array(' ', '.'), '_', microtime());
			$attrs['item'] = $this->_patchstack[$attrs['item']];
		}
		else {
			$this->_patchstack[$attrs['item']] = $attrs['item'];
		}

		$item = $this->get_val($attrs['item']);

		if (!empty($attrs['key'])) {
			$key = $attrs['key'];
			$key_part = $this->get_val($key) . ' => ';
		}
		else {
			$key = NULL;
			$key_part = '';
		}

		if (!empty($attrs['name'])) {
			$name = $attrs['name'];
		}
		else {
			$name = NULL;
		}

		$output = '<?php ';
		$output .= '$_from = ' . $from . '; if (!is_array($_from) && !is_object($_from)) { settype($_from, \'array\'); }; $this->push_vars(\'' . $attrs['key'] . '\', \'' . $attrs['item'] . '\');';

		if (!empty($name)) {
			$foreach_props = '$this->_foreach[\'' . $name . '\']';
			$output .= $foreach_props . " = array('total' => count(\$_from), 'iteration' => 0);\n";
			$output .= 'if (' . $foreach_props . "['total'] > 0):\n";
			$output .= '    foreach ($_from AS ' . $key_part . $item . "):\n";
			$output .= '        ' . $foreach_props . "['iteration']++;\n";
		}
		else {
			$output .= "if (count(\$_from)):\n";
			$output .= '    foreach ($_from AS ' . $key_part . $item . "):\n";
		}

		return $output . '?>';
	}

	public function push_vars($key, $val)
	{
		if (!empty($key)) {
			array_push($this->_temp_key, '$this->_vars[\'' . $key . '\']=\'' . $this->_vars[$key] . '\';');
		}

		if (!empty($val)) {
			array_push($this->_temp_val, '$this->_vars[\'' . $val . '\']=\'' . $this->_vars[$val] . '\';');
		}
	}

	public function pop_vars()
	{
		$key = array_pop($this->_temp_key);
		$val = array_pop($this->_temp_val);

		if (!empty($key)) {
			eval ($key);
		}
	}

	public function _compile_smarty_ref(&$indexes)
	{
		$_ref = $indexes[0];

		switch ($_ref) {
		case 'now':
			$compiled_ref = 'time()';
			break;

		case 'foreach':
			array_shift($indexes);
			$_var = $indexes[0];
			$_propname = $indexes[1];

			switch ($_propname) {
			case 'index':
				array_shift($indexes);
				$compiled_ref = '($this->_foreach[\'' . $_var . '\'][\'iteration\'] - 1)';
				break;

			case 'first':
				array_shift($indexes);
				$compiled_ref = '($this->_foreach[\'' . $_var . '\'][\'iteration\'] <= 1)';
				break;

			case 'last':
				array_shift($indexes);
				$compiled_ref = '($this->_foreach[\'' . $_var . '\'][\'iteration\'] == $this->_foreach[\'' . $_var . '\'][\'total\'])';
				break;

			case 'show':
				array_shift($indexes);
				$compiled_ref = '($this->_foreach[\'' . $_var . '\'][\'total\'] > 0)';
				break;

			default:
				$compiled_ref = '$this->_foreach[\'' . $_var . '\']';
				break;
			}

			break;

		case 'get':
			$compiled_ref = '$_GET';
			break;

		case 'post':
			$compiled_ref = '$_POST';
			break;

		case 'cookies':
			$compiled_ref = '$_COOKIE';
			break;

		case 'env':
			$compiled_ref = '$_ENV';
			break;

		case 'server':
			$compiled_ref = '$_SERVER';
			break;

		case 'request':
			$compiled_ref = '$_REQUEST';
			break;

		case 'session':
			$compiled_ref = '$_SESSION';
			break;

		default:
			break;
		}

		array_shift($indexes);
		return $compiled_ref;
	}

	public function smarty_insert_scripts($args)
	{
		static $scripts = array();
		$arr = explode(',', str_replace(' ', '', $args['files']));
		$str = '';

		foreach ($arr as $val) {
			if (in_array($val, $scripts) == false) {
				$scripts[] = $val;

				if ($val[0] == '.') {
					$str .= '<script type="text/javascript" src="' . $val . '"></script>';
				}
				else {
					$str .= '<script type="text/javascript" src="js/' . $val . '"></script>';
				}
			}
		}

		return $str;
	}

	private function js_path()
	{
		$self = explode('/', substr(PHP_SELF, 1));
		$count = count($self);

		if (1 < $count) {
			$real_path = $self[$count - 2];
			if (($real_path == ADMIN_PATH) && $GLOBALS['_CFG']['site_domain']) {
				$path = $GLOBALS['_CFG']['site_domain'] . ADMIN_PATH . '/';
			}
			else {
				if (($real_path == SELLER_PATH) && $GLOBALS['_CFG']['site_domain']) {
					$path = $GLOBALS['_CFG']['site_domain'] . SELLER_PATH . '/';
				}
			}
		}
		else {
			$path = $GLOBALS['_CFG']['site_domain'];
		}

		return $path;
	}

	public function smarty_prefilter_preCompile($source)
	{
		$file_type = strtolower(strrchr($this->_current_file, '.'));
		$tmp_dir = 'themes/' . $GLOBALS['_CFG']['template'] . '/';

		if ($file_type == '.dwt') {
			$pattern = '/<!--\\s#BeginLibraryItem\\s\\"\\/(.*?)\\"\\s-->.*?<!--\\s#EndLibraryItem\\s-->/s';
			$source = preg_replace_callback($pattern, function($r) {
				return '{include file=' . strtolower($r[1]) . '}';
			}, $source);
			$dyna_libs = get_dyna_libs($GLOBALS['_CFG']['template'], $this->_current_file);

			if ($dyna_libs) {
				foreach ($dyna_libs as $region => $libs) {
					$pattern = '/<!--\\s*TemplateBeginEditable\\sname="' . $region . '"\\s*-->(.*?)<!--\\s*TemplateEndEditable\\s*-->/s';

					if (preg_match($pattern, $source, $reg_match)) {
						$reg_content = $reg_match[1];
						$keys = array_keys($libs);
						$lib_pattern = '';

						foreach ($keys as $lib) {
							$lib_pattern .= '|' . str_replace('/', '\\/', substr($lib, 1));
						}

						$lib_pattern = '/{include\\sfile=(' . substr($lib_pattern, 1) . ')}/';
						$GLOBALS['libs'] = $libs;
						$reg_content = preg_replace_callback($lib_pattern, 'dyna_libs_replace', $reg_content);
						$source = preg_replace($pattern, $reg_content, $source);
					}
				}
			}

			$source = preg_replace('/(<link\\shref=["|\'])(?:\\.\\/|\\.\\.\\/)?(css\\/)?([a-z0-9A-Z_]+\\.css["|\']\\srel=["|\']stylesheet["|\']\\stype=["|\']text\\/css["|\'])/i', '\\1' . $tmp_dir . '\\2\\3', $source);
			$source = preg_replace('/(<script\\s(?:type|language)=["|\']text\\/javascript["|\']\\ssrc=["|\'])(?:\\.\\/|\\.\\.\\/)?(js\\/[a-z0-9A-Z_\\-\\.]+\\.(?:js|vbs)["|\']><\\/script>)/', '\\1' . $tmp_dir . '\\2', $source);
			$source = preg_replace('/<meta\\shttp-equiv=["|\']Content-Type["|\']\\scontent=["|\']text\\/html;\\scharset=(?:.*?)["|\'][^>]*?>\\r?\\n?/i', '<meta http-equiv="Content-Type" content="text/html; charset=' . EC_CHARSET . '" />' . "\n", $source);
		}
		else if ($file_type == '.lbi') {
			$source = preg_replace('/<meta\\shttp-equiv=["|\']Content-Type["|\']\\scontent=["|\']text\\/html;\\scharset=(?:.*?)["|\']>\\r?\\n?/i', '', $source);
		}

		if (strpos($source, "\xef\xbb\xbf") !== false) {
			$source = str_replace("\xef\xbb\xbf", '', $source);
		}

		$pattern = array('/<!--[^>|\\n]*?({.+?})[^<|{|\\n]*?-->/', '/<!--[^<|>|{|\\n]*?-->/', '/(href=["|\'])\\.\\.\\/(.*?)(["|\'])/i', '/((?:background|src)\\s*=\\s*["|\'])(?:\\.\\/|\\.\\.\\/)?(images\\/.*?["|\'])/is', '/((?:background|background-image):\\s*?url\\()(?:\\.\\/|\\.\\.\\/)?(images\\/)/is', '/([\'|"])\\.\\.\\//is');
		$replace = array('\\1', '', '\\1\\2\\3', '\\1' . $tmp_dir . '\\2', '\\1' . $tmp_dir . '\\2', '\\1');
		return preg_replace($pattern, $replace, $source);
	}

	public function insert_mod($name)
	{
		list($fun, $para) = explode('|', $name);
		$para = unserialize($para);
		$fun = 'insert_' . $fun;
		return $fun($para);
	}

	public function str_trim($str)
	{
		while (strpos($str, '= ') != 0) {
			$str = str_replace('= ', '=', $str);
		}

		while (strpos($str, ' =') != 0) {
			$str = str_replace(' =', '=', $str);
		}

		return explode(' ', trim($str));
	}

	public function _eval($content)
	{
		ob_start();
		eval ('?' . '>' . trim($content));
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}

	public function _require($filename)
	{
		ob_start();
		include $filename;
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}

	public function html_options($arr)
	{
		if (!isset($arr['selected'])) {
			$arr['selected'] = -1;
		}

		$selected = $arr['selected'];

		if ($arr['options']) {
			$options = (array) $arr['options'];
		}
		else if ($arr['output']) {
			if ($arr['values']) {
				foreach ($arr['output'] as $key => $val) {
					$options[$arr[values][$key]] = $val;
				}
			}
			else {
				$options = array_values((array) $arr['output']);
			}
		}

		if ($options) {
			foreach ($options as $key => $val) {
				if (0 <= $selected) {
					$selectedType = 'selected';
				}

				$out .= ($key == $selected ? '<option value="' . $key . '" ' . $selectedType . ' >' . $val . '</option>' : '<option value="' . $key . '">' . $val . '</option>');
			}
		}

		return $out;
	}

	public function html_select_date($arr)
	{
		$pre = $arr['prefix'];

		if (isset($arr['time'])) {
			if (10000 < intval($arr['time'])) {
				$arr['time'] = gmdate('Y-m-d', $arr['time'] + (8 * 3600));
			}

			$t = explode('-', $arr['time']);
			$year = strval($t[0]);
			$month = strval($t[1]);
			$day = strval($t[2]);
		}

		$now = gmdate('Y', $this->_nowtime);

		if (isset($arr['start_year'])) {
			if (abs($arr['start_year']) == $arr['start_year']) {
				$startyear = $arr['start_year'];
			}
			else {
				$startyear = $arr['start_year'] + $now;
			}
		}
		else {
			$startyear = $now - 3;
		}

		if (isset($arr['end_year'])) {
			if (strlen(abs($arr['end_year'])) == strlen($arr['end_year'])) {
				$endyear = $arr['end_year'];
			}
			else {
				$endyear = $arr['end_year'] + $now;
			}
		}
		else {
			$endyear = $now + 3;
		}

		$out = '<select name="' . $pre . 'Year" class="select">';

		for ($i = $startyear; $i <= $endyear; $i++) {
			$out .= ($i == $year ? '<option value="' . $i . '" selected>' . $i . '</option>' : '<option value="' . $i . '">' . $i . '</option>');
		}

		if ($arr['display_months'] != 'false') {
			$out .= '</select>&nbsp;<select name="' . $pre . 'Month" class="select">';

			for ($i = 1; $i <= 12; $i++) {
				$out .= ($i == $month ? '<option value="' . $i . '" selected>' . str_pad($i, 2, '0', STR_PAD_LEFT) . '</option>' : '<option value="' . $i . '">' . str_pad($i, 2, '0', STR_PAD_LEFT) . '</option>');
			}
		}

		if ($arr['display_days'] != 'false') {
			$out .= '</select>&nbsp;<select name="' . $pre . 'Day" class="select">';

			for ($i = 1; $i <= 31; $i++) {
				$out .= ($i == $day ? '<option value="' . $i . '" selected>' . str_pad($i, 2, '0', STR_PAD_LEFT) . '</option>' : '<option value="' . $i . '">' . str_pad($i, 2, '0', STR_PAD_LEFT) . '</option>');
			}
		}

		return $out . '</select>';
	}

	public function html_radios($arr)
	{
		$name = $arr['name'];
		$checked = $arr['checked'];
		$options = $arr['options'];
		$out = '';

		foreach ($options as $key => $val) {
			$out .= ($key == $checked ? '<label><input type="radio" class="checkbox" name="' . $name . '" value="' . $key . '" checked><span>' . $val . '</span></label>' : '<label><input type="radio" class="checkbox" name="' . $name . '" value="' . $key . '"><span>' . $val . '</span></label>');
		}

		return $out;
	}

	public function html_select_time($arr)
	{
		$pre = $arr['prefix'];

		if (isset($arr['time'])) {
			$arr['time'] = gmdate('H-i-s', $arr['time'] + (8 * 3600));
			$t = explode('-', $arr['time']);
			$hour = strval($t[0]);
			$minute = strval($t[1]);
			$second = strval($t[2]);
		}

		$out = '';

		if (!isset($arr['display_hours'])) {
			$out .= '<select name="' . $pre . 'Hour">';

			for ($i = 0; $i <= 23; $i++) {
				$out .= ($i == $hour ? '<option value="' . $i . '" selected>' . str_pad($i, 2, '0', STR_PAD_LEFT) . '</option>' : '<option value="' . $i . '">' . str_pad($i, 2, '0', STR_PAD_LEFT) . '</option>');
			}

			$out .= '</select>&nbsp;';
		}

		if (!isset($arr['display_minutes'])) {
			$out .= '<select name="' . $pre . 'Minute">';

			for ($i = 0; $i <= 59; $i++) {
				$out .= ($i == $minute ? '<option value="' . $i . '" selected>' . str_pad($i, 2, '0', STR_PAD_LEFT) . '</option>' : '<option value="' . $i . '">' . str_pad($i, 2, '0', STR_PAD_LEFT) . '</option>');
			}

			$out .= '</select>&nbsp;';
		}

		if (!isset($arr['display_seconds'])) {
			$out .= '<select name="' . $pre . 'Second">';

			for ($i = 0; $i <= 59; $i++) {
				$out .= ($i == $second ? '<option value="' . $i . '" selected>' . str_pad($i, 2, '0', STR_PAD_LEFT) . '</option>' : '<option value="' . $i . '">' . $i . '</option>');
			}

			$out .= '</select>&nbsp;';
		}

		return $out;
	}

	public function cycle($arr)
	{
		static $k;
		static $old;
		$value = explode(',', $arr['values']);

		if ($old != $value) {
			$old = $value;
			$k = 0;
		}
		else {
			$k++;

			if (!isset($old[$k])) {
				$k = 0;
			}
		}

		echo $old[$k];
	}

	public function make_array($arr)
	{
		$out = '';

		foreach ($arr as $key => $val) {
			if ($val[0] == '$') {
				$out .= ($out ? ',\'' . $key . '\'=>' . $val : 'array(\'' . $key . '\'=>' . $val);
			}
			else {
				$out .= ($out ? ',\'' . $key . '\'=>\'' . $val . '\'' : 'array(\'' . $key . '\'=>\'' . $val . '\'');
			}
		}

		return $out . ')';
	}

	public function smarty_create_pages($params)
	{
		extract($params);

		if (empty($page)) {
			$page = 1;
		}

		if (!empty($count)) {
			$str = '<option value=\'1\'>1</option>';
			$min = min($count - 1, $page + 3);

			for ($i = $page - 3; $i <= $min; $i++) {
				if ($i < 2) {
					continue;
				}

				$str .= '<option value=\'' . $i . '\'';
				$str .= ($page == $i ? ' selected=\'true\'' : '');
				$str .= '>' . $i . '</option>';
			}

			if (1 < $count) {
				$str .= '<option value=\'' . $count . '\'';
				$str .= ($page == $count ? ' selected=\'true\'' : '');
				$str .= '>' . $count . '</option>';
			}
		}
		else {
			$str = '';
		}

		return $str;
	}
}


?>