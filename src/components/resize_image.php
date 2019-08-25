<?php

function resize_fit_width($fn, $name, $width_new = 250) {

	$size = getimagesize($fn);
	
	$width	= $size[0];
	$height	= $size[1];
	
	if($width > $width_new) {
        $ratio	= $height/$width;
		$width	= $width_new;
        $height	= round($width*$ratio);
	}

	$src = imagecreatefromstring(file_get_contents($fn));
	$dst = imagecreatetruecolor($width, $height);
	
	imagecopyresampled($dst, $src, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
	imagedestroy($src);
	imagejpeg($dst, $name);
	imagedestroy($dst);
}

?>