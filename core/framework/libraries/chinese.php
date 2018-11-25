<?php
//源码由旺旺:ecshop2012所有 未经允许禁止倒卖 一经发现停止任何服务
class Chinese
{
	/**
     * 存放 GB <-> UNICODE 对照表的内容
     * @变量类型
     * @访问      内部
     */
	public $unicode_table = array();
	/**
     * 访问中文繁简互换表的文件指针
     *
     * @变量类型  对象
     * @访问      内部
     */
	public $ctf;
	/**
     * 等待转换的字符串
     * @变量类型
     * @访问      内部
     */
	public $SourceText = '';
	/**
     * Chinese 的运行配置
     *
     * @变量类型  数组
     * @访问      公开
     */
	public $config = array('codetable_dir' => '', 'source_lang' => '', 'target_lang' => '', 'GBtoBIG5_table' => 'gb-big5.table', 'BIG5toGB_table' => 'big5-gb.table', 'GBtoUTF8_table' => 'gb_utf8.php', 'BIG5toUTF8_table' => 'big5_utf8.php');
	public $iconv_enabled = false;
	public $mbstring_enabled = false;

	public function Chinese($dir = './')
	{
		$this->config['codetable_dir'] = $dir . 'includes/codetable/';

		if (function_exists('iconv')) {
			$this->iconv_enabled = true;
		}

		if (('5.0' <= PHP_VERSION) && function_exists('mb_convert_encoding') && function_exists('mb_list_encodings')) {
			$encodings = mb_list_encodings();
			if ((in_array('UTF-8', $encodings) == true) && (in_array('BIG-5', $encodings) == true) && (in_array('CP936', $encodings) == true)) {
				$this->mbstring_enabled = true;
			}
		}
	}

	public function Convert($source_lang, $target_lang, $source_string = '')
	{
		if (($source_string == '') || (preg_match("/[\x80-\xff]+/", $source_string) == 0)) {
			return $source_string;
		}

		if ($source_lang) {
			$this->config['source_lang'] = $this->_lang($source_lang);
		}

		if ($target_lang) {
			$this->config['target_lang'] = $this->_lang($target_lang);
		}

		if ($this->config['source_lang'] == $this->config['target_lang']) {
			return $source_string;
		}

		$this->SourceText = $source_string;
		if (($this->iconv_enabled || $this->mbstring_enabled) && !(($this->config['source_lang'] == 'GBK') && ($this->config['target_lang'] == 'BIG-5'))) {
			if ($this->config['target_lang'] != 'UNICODE') {
				$string = $this->_convert_iconv_mbstring($this->SourceText, $this->config['target_lang'], $this->config['source_lang']);

				if ($string) {
					return $string;
				}
			}
			else {
				$string = '';
				$text = $SourceText;

				while ($text) {
					if (127 < ord(substr($text, 0, 1))) {
						if ($this->config['source_lang'] != 'UTF-8') {
							$char = $this->_convert_iconv_mbstring(substr($text, 0, 2), 'UTF-8', $this->config['source_lang']);
						}
						else {
							$char = substr($text, 0, 3);
						}

						if ($char == '') {
							$string = '';
							break;
						}

						switch (strlen($char)) {
						case 1:
							$uchar = ord($char);
							break;

						case 2:
							$uchar = (ord($char[0]) & 63) << 6;
							$uchar += ord($char[1]) & 63;
							break;

						case 3:
							$uchar = (ord($char[0]) & 31) << 12;
							$uchar += (ord($char[1]) & 63) << 6;
							$uchar += ord($char[2]) & 63;
							break;

						case 4:
							$uchar = (ord($char[0]) & 15) << 18;
							$uchar += (ord($char[1]) & 63) << 12;
							$uchar += (ord($char[2]) & 63) << 6;
							$uchar += ord($char[3]) & 63;
							break;
						}

						$string .= '&#x' . dechex($uchar) . ';';

						if ($this->config['source_lang'] != 'UTF-8') {
							$text = substr($text, 2);
						}
						else {
							$text = substr($text, 3);
						}
					}
					else {
						$string .= substr($text, 0, 1);
						$text = substr($text, 1);
					}
				}

				if ($string) {
					return $string;
				}
			}
		}

		$this->OpenTable();
		if ((($this->config['source_lang'] == 'GBK') || ($this->config['source_lang'] == 'BIG-5')) && (($this->config['target_lang'] == 'GBK') || ($this->config['target_lang'] == 'BIG-5'))) {
			return $this->GBtoBIG5();
		}

		if ((($this->config['source_lang'] == 'GBK') || ($this->config['source_lang'] == 'BIG-5') || ($this->config['source_lang'] == 'UTF-8')) && (($this->config['target_lang'] == 'UTF-8') || ($this->config['target_lang'] == 'GBK') || ($this->config['target_lang'] == 'BIG-5'))) {
			return $this->CHStoUTF8();
		}

		if ((($this->config['source_lang'] == 'GBK') || ($this->config['source_lang'] == 'BIG-5')) && ($this->config['target_lang'] == 'UNICODE')) {
			return $this->CHStoUNICODE();
		}
	}

	public function _lang($lang)
	{
		$lang = strtoupper($lang);

		if (substr($lang, 0, 2) == 'GB') {
			return 'GBK';
		}
		else {
			switch (substr($lang, 0, 3)) {
			case 'BIG':
				return 'BIG-5';
			case 'UTF':
				return 'UTF-8';
			case 'UNI':
				return 'UNICODE';
			default:
				return '';
			}
		}
	}

	public function _convert_iconv_mbstring($string, $target_lang, $source_lang)
	{
		if ($this->iconv_enabled) {
			$return_string = @iconv($source_lang, $target_lang, $string);

			if ($return_string !== false) {
				return $return_string;
			}
		}

		if ($this->mbstring_enabled) {
			if ($source_lang == 'GBK') {
				$source_lang = 'CP936';
			}

			if ($target_lang == 'GBK') {
				$target_lang = 'CP936';
			}

			$return_string = @mb_convert_encoding($string, $target_lang, $source_lang);

			if ($return_string !== false) {
				return $return_string;
			}
			else {
				return false;
			}
		}
	}

	public function _hex2bin($hexdata)
	{
		$bindata = '';
		$i = 0;

		for ($count = strlen($hexdata); $i < $count; $i += 2) {
			$bindata .= chr(hexdec($hexdata[$i] . $hexdata[$i + 1]));
		}

		return $bindata;
	}

	public function OpenTable()
	{
		static $gb_utf8_table;
		static $gb_unicode_table;
		static $utf8_gb_table;
		static $big5_utf8_table;
		static $big5_unicode_table;
		static $utf8_big5_table;

		if ($this->config['source_lang'] == 'GBK') {
			if ($this->config['target_lang'] == 'BIG-5') {
				$this->ctf = @fopen($this->config['codetable_dir'] . $this->config['GBtoBIG5_table'], 'rb');

				if (is_null($this->ctf)) {
					echo '打开打开转换表文件失败！';
					exit();
				}
			}

			if ($this->config['target_lang'] == 'UTF-8') {
				if ($gb_utf8_table === NULL) {
					require_once $this->config['codetable_dir'] . $this->config['GBtoUTF8_table'];
				}

				$this->unicode_table = $gb_utf8_table;
			}

			if ($this->config['target_lang'] == 'UNICODE') {
				if ($gb_unicode_table === NULL) {
					if (isset($gb_utf8_table) === false) {
						require_once $this->config['codetable_dir'] . $this->config['GBtoUTF8_table'];
					}

					foreach ($gb_utf8_table as $key => $value) {
						$gb_unicode_table[$key] = substr($value, 2);
					}
				}

				$this->unicode_table = $gb_unicode_table;
			}
		}

		if ($this->config['source_lang'] == 'BIG-5') {
			if ($this->config['target_lang'] == 'GBK') {
				$this->ctf = @fopen($this->config['codetable_dir'] . $this->config['BIG5toGB_table'], 'rb');

				if (is_null($this->ctf)) {
					echo '打开打开转换表文件失败！';
					exit();
				}
			}

			if ($this->config['target_lang'] == 'UTF-8') {
				if ($big5_utf8_table === NULL) {
					require_once $this->config['codetable_dir'] . $this->config['BIG5toUTF8_table'];
				}

				$this->unicode_table = $big5_utf8_table;
			}

			if ($this->config['target_lang'] == 'UNICODE') {
				if ($big5_unicode_table === NULL) {
					if (isset($big5_utf8_table) === false) {
						require_once $this->config['codetable_dir'] . $this->config['BIG5toUTF8_table'];
					}

					foreach ($big5_utf8_table as $key => $value) {
						$big5_unicode_table[$key] = substr($value, 2);
					}
				}

				$this->unicode_table = $big5_unicode_table;
			}
		}

		if ($this->config['source_lang'] == 'UTF-8') {
			if ($this->config['target_lang'] == 'GBK') {
				if ($utf8_gb_table === NULL) {
					if (isset($gb_utf8_table) === false) {
						require_once $this->config['codetable_dir'] . $this->config['GBtoUTF8_table'];
					}

					foreach ($gb_utf8_table as $key => $value) {
						$utf8_gb_table[hexdec($value)] = '0x' . dechex($key);
					}
				}

				$this->unicode_table = $utf8_gb_table;
			}

			if ($this->config['target_lang'] == 'BIG-5') {
				if ($utf8_big5_table === NULL) {
					if (isset($big5_utf8_table) === false) {
						require_once $this->config['codetable_dir'] . $this->config['BIG5toUTF8_table'];
					}

					foreach ($big5_utf8_table as $key => $value) {
						$utf8_big5_table[hexdec($value)] = '0x' . dechex($key);
					}
				}

				$this->unicode_table = $utf8_big5_table;
			}
		}
	}

	public function CHSUtoUTF8($c)
	{
		$str = '';

		if ($c < 128) {
			$str .= $c;
		}
		else if ($c < 2048) {
			$str .= 192 | ($c >> 6);
			$str .= 128 | ($c & 63);
		}
		else if ($c < 65536) {
			$str .= 224 | ($c >> 12);
			$str .= 128 | (($c >> 6) & 63);
			$str .= 128 | ($c & 63);
		}
		else if ($c < 2097152) {
			$str .= 240 | ($c >> 18);
			$str .= 128 | (($c >> 12) & 63);
			$str .= 128 | (($c >> 6) & 63);
			$str .= 128 | ($c & 63);
		}

		return $str;
	}

	public function CHStoUTF8()
	{
		if (($this->config['source_lang'] == 'BIG-5') || ($this->config['source_lang'] == 'GBK')) {
			$ret = '';

			while ($this->SourceText) {
				if (127 < ord($this->SourceText[0])) {
					if ($this->config['source_lang'] == 'BIG-5') {
						$utf8 = $this->CHSUtoUTF8(hexdec(@$this->unicode_table[hexdec(bin2hex($this->SourceText[0] . $this->SourceText[1]))]));
					}

					if ($this->config['source_lang'] == 'GBK') {
						$utf8 = $this->CHSUtoUTF8(hexdec(@$this->unicode_table[hexdec(bin2hex($this->SourceText[0] . $this->SourceText[1])) - 32896]));
					}

					$i = 0;

					for ($count = strlen($utf8); $i < $count; $i += 3) {
						$ret .= chr(substr($utf8, $i, 3));
					}

					$this->SourceText = substr($this->SourceText, 2, strlen($this->SourceText));
				}
				else {
					$ret .= $this->SourceText[0];
					$this->SourceText = substr($this->SourceText, 1, strlen($this->SourceText));
				}
			}

			$this->unicode_table = array();
			$this->SourceText = '';
			return $ret;
		}

		if ($this->config['source_lang'] == 'UTF-8') {
			$i = 0;
			$out = '';
			$len = strlen($this->SourceText);

			while ($i < $len) {
				$c = ord($this->SourceText[$i++]);

				switch ($c >> 4) {
				case 0:
				case 1:
				case 2:
				case 3:
				case 4:
				case 5:
				case 6:
				case 7:
					$out .= $this->SourceText[$i - 1];
					break;

				case 12:
				case 13:
					$char2 = ord($this->SourceText[$i++]);
					$char3 = @$this->unicode_table[(($c & 31) << 6) | ($char2 & 63)];

					if ($this->config['target_lang'] == 'GBK') {
						$out .= $this->_hex2bin(dechex($char3 + 32896));
					}
					else if ($this->config['target_lang'] == 'BIG-5') {
						$out .= $this->_hex2bin(dechex($char3 + 0));
					}

					break;

				case 14:
					$char2 = ord($this->SourceText[$i++]);
					$char3 = ord($this->SourceText[$i++]);
					$char4 = @$this->unicode_table[(($c & 15) << 12) | (($char2 & 63) << 6) | (($char3 & 63) << 0)];

					if ($this->config['target_lang'] == 'GBK') {
						$out .= $this->_hex2bin(dechex($char4 + 32896));
					}
					else if ($this->config['target_lang'] == 'BIG-5') {
						$out .= $this->_hex2bin(dechex($char4 + 0));
					}

					break;
				}
			}

			return $out;
		}
	}

	public function CHStoUNICODE()
	{
		$utf = '';

		while ($this->SourceText) {
			if (127 < ord($this->SourceText[0])) {
				if ($this->config['source_lang'] == 'GBK') {
					$utf .= '&#x' . $this->unicode_table[hexdec(bin2hex($this->SourceText[0] . $this->SourceText[1])) - 32896] . ';';
				}
				else if ($this->config['source_lang'] == 'BIG-5') {
					$utf .= '&#x' . $this->unicode_table[hexdec(bin2hex($this->SourceText[0] . $this->SourceText[1]))] . ';';
				}

				$this->SourceText = substr($this->SourceText, 2, strlen($this->SourceText));
			}
			else {
				$utf .= $this->SourceText[0];
				$this->SourceText = substr($this->SourceText, 1, strlen($this->SourceText));
			}
		}

		return $utf;
	}

	public function GBtoBIG5()
	{
		$max = strlen($this->SourceText) - 1;

		for ($i = 0; $i < $max; $i++) {
			$h = ord($this->SourceText[$i]);

			if (160 <= $h) {
				$l = ord($this->SourceText[$i + 1]);
				if (($h == 161) && ($l == 64)) {
					$gb = '  ';
				}
				else {
					fseek($this->ctf, (($h - 160) * 510) + (($l - 1) * 2));
					$gb = fread($this->ctf, 2);
				}

				$this->SourceText[$i] = $gb[0];
				$this->SourceText[$i + 1] = $gb[1];
				$i++;
			}
		}

		fclose($this->ctf);
		$result = $this->SourceText;
		$this->SourceText = '';
		return $result;
	}
}

if (!defined('Inshopec')) {
	exit('Hacking attempt');
}

?>
