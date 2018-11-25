<?php
//源码由旺旺:ecshop2012所有 未经允许禁止倒卖 一经发现停止任何服务
class Uploader
{
	private $fileField;
	private $file;
	private $config;
	private $oriName;
	private $fileName;
	private $fullName;
	private $fileSize;
	private $fileType;
	private $stateInfo;
	private $stateMap = array(0 => 'SUCCESS', 1 => '\\u6587\\u4ef6\\u5927\\u5c0f\\u8d85\\u51fa\\u9650\\u5236', 2 => '\\u6587\\u4ef6\\u5927\\u5c0f\\u8d85\\u51fa\\u9650\\u5236', 3 => '\\u6587\\u4ef6\\u672a\\u88ab\\u5b8c\\u6574\\u4e0a\\u4f20', 4 => '\\u6ca1\\u6709\\u6587\\u4ef6\\u88ab\\u4e0a\\u4f20', 5 => '\\u4e0a\\u4f20\\u6587\\u4ef6\\u4e3a\\u7a7a', 'POST' => '\\u6587\\u4ef6\\u5927\\u5c0f\\u8d85\\u51fa\\u9650\\u5236', 'SIZE' => '\\u6587\\u4ef6\\u5927\\u5c0f\\u8d85\\u51fa\\u7f51\\u7ad9\\u9650\\u5236', 'TYPE' => '\\u4e0d\\u5141\\u8bb8\\u7684\\u6587\\u4ef6\\u7c7b\\u578b', 'DIR' => '\\u76ee\\u5f55\\u521b\\u5efa\\u5931\\u8d25', 'IO' => '\\u8f93\\u5165\\u8f93\\u51fa\\u9519\\u8bef', 'UNKNOWN' => '\\u672a\\u77e5\\u9519\\u8bef', 'MOVE' => '\\u6587\\u4ef6\\u4fdd\\u5b58\\u65f6\\u51fa\\u9519');

	public function __construct($fileField, $config, $base64 = false)
	{
		$this->fileField = $fileField;
		$this->config = $config;
		$this->stateInfo = $this->stateMap[0];
		$this->upFile($base64);
	}

	private function upFile($base64)
	{
		if ('base64' == $base64) {
			$content = $_POST[$this->fileField];
			$this->base64ToImage($content);
			return NULL;
		}

		$file = $this->file = $_FILES[$this->fileField];

		if (!$file) {
			$this->stateInfo = $this->getStateInfo('POST');
			return NULL;
		}

		if ($this->file['error']) {
			$this->stateInfo = $this->getStateInfo($file['error']);
			return NULL;
		}

		if (!is_uploaded_file($file['tmp_name'])) {
			$this->stateInfo = $this->getStateInfo('UNKNOWN');
			return NULL;
		}

		$this->oriName = $file['name'];
		$this->fileSize = $file['size'];
		$this->fileType = $this->getFileExt();

		if (!$this->checkSize()) {
			$this->stateInfo = $this->getStateInfo('SIZE');
			return NULL;
		}

		if (!$this->checkType()) {
			$this->stateInfo = $this->getStateInfo('TYPE');
			return NULL;
		}

		$this->fullName = $this->getFolder() . '/' . $this->getName();

		if ($this->stateInfo == $this->stateMap[0]) {
			if (!move_uploaded_file($file['tmp_name'], $this->fullName)) {
				$this->stateInfo = $this->getStateInfo('MOVE');
			}
		}
	}

	private function base64ToImage($base64Data)
	{
		$img = base64_decode($base64Data);
		$this->fileName = time() . rand(1, 10000) . '.png';
		$this->fullName = $this->getFolder() . '/' . $this->fileName;

		if (!file_put_contents($this->fullName, $img)) {
			$this->stateInfo = $this->getStateInfo('IO');
			return NULL;
		}

		$this->oriName = '';
		$this->fileSize = strlen($img);
		$this->fileType = '.png';
	}

	public function getFileInfo()
	{
		return array('originalName' => $this->oriName, 'name' => $this->fileName, 'url' => $this->fullName, 'size' => $this->fileSize, 'type' => $this->fileType, 'state' => $this->stateInfo);
	}

	private function getStateInfo($errCode)
	{
		return !$this->stateMap[$errCode] ? $this->stateMap['UNKNOWN'] : $this->stateMap[$errCode];
	}

	private function getName()
	{
		return $this->fileName = time() . rand(1, 10000) . $this->getFileExt();
	}

	private function checkType()
	{
		return in_array($this->getFileExt(), $this->config['allowFiles']);
	}

	private function checkSize()
	{
		return $this->fileSize <= $this->config['maxSize'] * 1024;
	}

	private function getFileExt()
	{
		return strtolower(strrchr($this->file['name'], '.'));
	}

	private function getFolder()
	{
		$pathStr = $this->config['savePath'];

		if (strrchr($pathStr, '/') != '/') {
			$pathStr .= '/';
		}

		$pathStr .= date('Ymd');

		if (!file_exists($pathStr)) {
			if (!mkdir($pathStr, 511, true)) {
				return false;
			}
		}

		return $pathStr;
	}
}


?>
