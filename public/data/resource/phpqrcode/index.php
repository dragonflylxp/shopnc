<?php    
/*
 * PHP QR Code encoder
 *
 * Exemplatory usage
 *
 * PHP QR Code is distributed under LGPL 3
 * Copyright (C) 2010 Dominik Dzienia <deltalab at poczta dot fm>
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 */

class PhpQRCode
{

	//processing form input
	//remember to sanitize user input in real-life solution !!!
//	private $errorCorrectionLevel = 'L';		// L M Q H
	private $errorCorrectionLevel = 'Q';        // L M Q H


	private $matrixPointSize = 3;                // 1 2 3 4 5 6 7 8 9 10


	private $date = 'shopec';


	private $pngTempDir = '';

	private $pngTempName = '';

	/**
	 * 设置
	 */
	public function set($key, $value)
	{
		$this->$key = $value;
	}

	public function __construct()
	{
		include "qrlib.php";
	}

	public function init()
	{
		//ofcourse we need rights to create temp dir
		if (!file_exists($this->pngTempDir))
			mkdir($this->pngTempDir);

		if ($this->date != 'shopec') {

			// user data
			if ($this->pngTempName != '') {
				$filename = $this->pngTempDir . $this->pngTempName;
			} else {
				$filename = $this->pngTempDir . 'test' . md5($this->date . '|' . $this->errorCorrectionLevel . '|' . $this->matrixPointSize) . '.png';
			}
			QRcode::png($this->date, $filename, $this->errorCorrectionLevel, $this->matrixPointSize, 2);

			//二维码中间添加logo
			$logo = BASE_UPLOAD_PATH . '/mall/store/goods/xy_logo.png';
			$QR = $filename;
			if ($logo !== FALSE) {
				$QR = imagecreatefromstring(file_get_contents($QR));
				$logo = imagecreatefromstring(file_get_contents($logo));
				$QR_width = imagesx($QR);
				$QR_height = imagesy($QR);
				$logo_width = imagesx($logo);
				$logo_height = imagesy($logo);
				$logo_qr_width = $QR_width / 3;
				$scale = $logo_width / $logo_qr_width;
				$logo_qr_height = $logo_height / $scale;
				$from_width = ($QR_width - $logo_qr_width) / 2;
				imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
			}
			imagepng($QR, $filename);
			imagedestroy($QR);
	        
	    } else {    
	    
	        //default data
	        QRcode::png('http://www.xinyuangongxiang.com', $filename, $this->errorCorrectionLevel, $this->matrixPointSize, 2);
	        
	    }    
	        
	    //display generated file
	    return basename($filename);
	    
	    QRtools::timeBenchmark();    
	}


	public function logo_init()
	{
		//ofcourse we need rights to create temp dir
		if (!file_exists($this->pngTempDir))
			mkdir($this->pngTempDir);

		if ($this->date != 'shopec') {

			// user data
			if ($this->pngTempName != '') {
				$filename = $this->pngTempDir . $this->pngTempName;
			} else {
				$filename = $this->pngTempDir . 'test' . md5($this->date . '|' . $this->errorCorrectionLevel . '|' . $this->matrixPointSize) . '.png';
			}
			QRcode::png($this->date, $filename, $this->errorCorrectionLevel, $this->matrixPointSize, 2);

			//二维码中间添加logo
			$logo = BASE_UPLOAD_PATH . '/mall/store/goods/xy_logo.png';
			$QR = $filename;
			if ($logo !== FALSE) {
				$QR = imagecreatefromstring(file_get_contents($QR));
				$logo = imagecreatefromstring(file_get_contents($logo));
				$QR_width = imagesx($QR);
				$QR_height = imagesy($QR);
				$logo_width = imagesx($logo);
				$logo_height = imagesy($logo);
				$logo_qr_width = $QR_width / 5;
				$scale = $logo_width / $logo_qr_width;
				$logo_qr_height = $logo_height / $scale;
				$from_width = ($QR_width - $logo_qr_width) / 2;
				imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
			}
			imagepng($QR, $filename);
			imagedestroy($QR);
		} else {

			//default data
			QRcode::png('http://www.xinyuangongxiang.com', $filename, $this->errorCorrectionLevel, $this->matrixPointSize, 2);

		}

		//display generated file
		return basename($filename);

		QRtools::timeBenchmark();
	}

	/**
	 * 生成对应的app扫描下载的二维码
	 * @param $logo_url string 		字符串类型
	 * author liming
	 */
	public function init_logo_android_apple($logo_url)
	{
		//ofcourse we need rights to create temp dir
		if (!file_exists($this->pngTempDir))
			mkdir($this->pngTempDir);

		if ($this->date != 'shopec') {

			// user data
			if ($this->pngTempName != '') {
				$filename = $this->pngTempDir . $this->pngTempName;
			} else {
				$filename = $this->pngTempDir . 'test' . md5($this->date . '|' . $this->errorCorrectionLevel . '|' . $this->matrixPointSize) . '.png';
			}
			QRcode::png($this->date, $filename, $this->errorCorrectionLevel, $this->matrixPointSize, 2);

			//二维码中间添加logo
//			$logo = BASE_UPLOAD_PATH.'/mall/store/goods/xy_logo.png';
			$logo = $logo_url;
			$QR = $filename;
			if ($logo !== FALSE) {
				$QR = imagecreatefromstring(file_get_contents($QR));
				$logo = imagecreatefromstring(file_get_contents($logo));
				$QR_width = imagesx($QR);
				$QR_height = imagesy($QR);
				$logo_width = imagesx($logo);
				$logo_height = imagesy($logo);
				$logo_qr_width = $QR_width / 3;
				$scale = $logo_width / $logo_qr_width;
				$logo_qr_height = $logo_height / $scale;
				$from_width = ($QR_width - $logo_qr_width) / 2;
				imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
			}
			imagepng($QR, $filename);
			imagedestroy($QR);

		} else {

			//default data
			QRcode::png('http://www.xinyuangongxiang.com', $filename, $this->errorCorrectionLevel, $this->matrixPointSize, 2);

		}
	}
}