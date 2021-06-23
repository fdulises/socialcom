<?php

	namespace wecor;
	
	/*
		$imgresult = img::resize([
			'filename' => 'archivo',
			'picname' => 'avatar',
			'w' => 300,
			'h' => 300,
		]);

		Picture error
		1-Success
		2-Unsupported picture type
		3-Picture is too small!
	*/
	class img{
		public static $pic_error = 0;
		public static function image_resize($src, $dst, $width, $height, $crop=0){
			if(!list($w, $h) = getimagesize($src)) return 2;

			$type = strtolower(substr(strrchr($src,"."),1));
			if($type == 'jpeg') $type = 'jpg';
			switch($type){
				case 'bmp': $img = imagecreatefromwbmp($src); break;
				case 'gif': $img = imagecreatefromgif($src); break;
				case 'jpg': $img = imagecreatefromjpeg($src); break;
				case 'png': $img = imagecreatefrompng($src); break;
				default : return 2;
			}

			// resize
			if($crop){
				if($w < $width or $h < $height) return 3;
				$ratio = max($width/$w, $height/$h);
				$h = $height / $ratio;
				$x = ($w - $width / $ratio) / 2;
				$w = $width / $ratio;
			}else{
				if($w < $width and $h < $height) return 3;
				$ratio = min($width/$w, $height/$h);
				$width = $w * $ratio;
				$height = $h * $ratio;
				$x = 0;
			}

			$new = imagecreatetruecolor($width, $height);

			// preserve transparency
			if($type == "gif" or $type == "png"){
				imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
				imagealphablending($new, false);
				imagesavealpha($new, true);
			}

			imagecopyresampled($new, $img, 0, 0, $x, 0, $width, $height, $w, $h);

			switch($type){
				case 'bmp': imagewbmp($new, $dst); break;
				case 'gif': imagegif($new, $dst); break;
				case 'jpg': imagejpeg($new, $dst); break;
				case 'png': imagepng($new, $dst); break;
			}
			return 1;
		}
		public static function resize($datos){
			$pic_type = strtolower(strrchr($_FILES[$datos['filename']]['name'],"."));
			$pic_name = '';
			if( isset($datos['uri']) ) $pic_name .= "{$datos['uri']}/";
			if( isset($datos['picname']) ){
				$pic_name .= $datos['picname'];
				$pic_name .= $pic_type;
			}else $pic_name .= $_FILES[$datos['filename']]['name'];
			move_uploaded_file($_FILES[$datos['filename']]['tmp_name'], $pic_name);
			if( $_FILES[$datos['filename']]['error'] === 0 ){
				self::$pic_error = self::image_resize(
					$pic_name, $pic_name, $datos['w'], $datos['h'], 1
				);
				if( 1 !== self::$pic_error ) unlink($pic_name);
				else return $pic_name;
			}
			return 0;
		}
	}
