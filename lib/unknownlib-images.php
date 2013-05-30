<?php
/// \note This file require unknownlib-function.php
/// declare the module in global unknownlib system management
$GLOBALS['unknownlib']['modules']['image']=true;

/** *************************** Remote image **************************
**********************************************************************/

/// \brief return error or empty if success
function unknownlib_image_download_and_crop_with_cache($url,$cache,$tmp_file,$archive,$path,$target_width,$target_height,$quality=95)
{
	if(!@copy($url,$tmp_file))
		return 'Unable to access at the url';
	elseif(file_exists($path) && file_exists($cache) && md5_file($cache)==md5_file($tmp_file)) //already parsed
		return '';
	elseif(!($image = imagecreatefromjpeg($tmp_file)))
		return 'The file is not jpeg image';
	elseif(imagesx($image)<$target_width || imagesy($image)<$target_height)
		return 'Your image is too small';
	elseif(imagesx($image)>($target_width*2) || imagesy($image)>($target_height*2))
		return 'Your image is too big';
	else
	{
		if(file_exists($cache))
			if(!unlink($cache))
				return 'Internal writing problem on remove';
		if(!rename($tmp_file,$cache))
			return 'Internal writing problem on rename';
		$dst = imagecreatetruecolor($target_width,$target_height);
		if(@imagecopyresized($dst,$image,0,0,((imagesx($image)-$target_width)/2),((imagesy($image)-$target_height)/2),$target_width,$target_height,$target_width,$target_height))
		{
			if(file_exists($path))
				rename($path,$archive);
			imagejpeg($dst,$path,$quality);
			if(file_exists($archive) && md5_file($path)==md5_file($archive))
				unlink($archive);
		}
	}
}

/// \brief return error or empty if success
function unknownlib_image_download_and_crop($url,$path_destination,$target_width,$target_height,$quality=95,$check_size=true)
{
	if(!($image = imagecreatefromjpeg($url)))
		return 'The file is not jpeg image';
	if($check_size)
	{
		if(imagesx($image)<$target_width || imagesy($image)<$target_height)
		{
			imagedestroy($image);
			return 'Your image is too small';
		}
		elseif(imagesx($image)>($target_width*2) || imagesy($image)>($target_height*2))
		{
			imagedestroy($image);
			return 'Your image is too big';
		}
	}
	$dst = imagecreatetruecolor($target_width,$target_height);
	if(@imagecopyresized($dst,$image,0,0,((imagesx($image)-$target_width)/2),((imagesy($image)-$target_height)/2),$target_width,$target_height,$target_width,$target_height))
	{
		if(file_exists($path_destination))
			if(!unlink($path_destination))
			{
				imagedestroy($image);
				return 'Internal writing problem on remove';
			}
		if(!imagejpeg($dst,$path_destination,$quality))
		{
			imagedestroy($image);
			return 'Internal writing problem on rename';
		}
	}
	return '';
}

/// \brief return error or empty if success
function unknownlib_image_download_scaled_and_cropped($url,$path_destination,$target_width,$target_height,$quality=95,$check_size=true)
{
	if(!($image = @imagecreatefromjpeg($url)))
		return 'The file is not jpeg image';
	elseif($check_size)
	{
		if(imagesx($image)<$target_width || imagesy($image)<$target_height)
		{
			imagedestroy($image);
			return 'Your image is too small';
		}
		elseif(imagesx($image)>($target_width*2) || imagesy($image)>($target_height*2))
		{
			imagedestroy($image);
			return 'Your image is too big';
		}
	}
	else
	{
		$dst = imagecreatetruecolor($target_width,$target_height);
		$dst=unknownlib_image_scaled_and_cropped($image,$target_width,$target_height);
		if(file_exists($path_destination))
			if(!unlink($path_destination))
			{
				imagedestroy($image);
				return 'Internal writing problem on remove';
			}
		if(!imagejpeg($dst,$path_destination,$quality))
		{
			imagedestroy($image);
			return 'Internal writing problem on create';
		}
	}
	imagedestroy($image);
	return '';
}

/// \brief return error or empty if success
function unknownlib_image_download_scaled_and_cropped_multi($url,$destination_list,$quality=95,$remove_if_exists=true)
{
	if(!($image = imagecreatefromjpeg($url)))
		return false;
	$have_error=false;
	foreach($destination_list as $destination)
	{
		if($remove_if_exists || !file_exists($destination['path_destination']))
		{
			if(imagesx($image)<$destination['target_width'] || imagesy($image)<$destination['target_height'])
				$have_error=true;
			else
			{
				$dst = imagecreatetruecolor($destination['target_width'],$destination['target_height']);
				$dst=unknownlib_image_scaled($image,$destination['target_width'],$destination['target_height']);
				if(file_exists($destination['path_destination']))
				{
					if(!unlink($destination['path_destination']))
						$have_error=true;
				}
				if(!imagejpeg($dst,$destination['path_destination'],$quality))
					$have_error=true;
			}
		}
	}
	imagedestroy($image);
	return !$have_error;
}

/** ************************ Image manipulation ***********************
**********************************************************************/

function unknownlib_image_scaled_and_cropped($image,$target_width,$target_height)
{
	if($target_width<=0 || $target_height<=0)
		unknownlib_die_perso('wrong image size');
	if(imagesx($image)<=0 || imagesy($image)<=0)
		unknownlib_die_perso('wrong image created size');
	$dst = imagecreatetruecolor($target_width,$target_height);
	$ratiox=(float)((float)imagesx($image)/(float)$target_width);
	$ratioy=(float)((float)imagesy($image)/(float)$target_height);
	if($ratiox<$ratioy)
		$min_ratio=$ratiox;
	else
		$min_ratio=$ratioy;
	$new_width=ceil(((float)imagesx($image))/$min_ratio);
	$new_height=ceil(((float)imagesy($image))/$min_ratio);
	$image_temp = imagecreatetruecolor($new_width,$new_height);
	//resize
	if(!@imagecopyresampled($image_temp,$image,0,0,0,0,$new_width,$new_height,imagesx($image),imagesy($image)))
		return $image;
	//crop
	if(!imagecopyresized($dst,$image_temp,0,0,ceil(((float)imagesx($image_temp)-(float)$target_width)/2),ceil(((float)imagesy($image_temp)-(float)$target_height)/2),$target_width,$target_height,$target_width,$target_height))
	{
		imagedestroy($dst);
		return $image_temp;
	}
	imagedestroy($image_temp);
	return $dst;
}

function unknownlib_image_cut_border($image,$border)
{
	if(imagesx($image)<=($border*2) || imagesy($image)<=($border*2))
		unknownlib_die_perso('wrong image created size');
	$dst = imagecreatetruecolor(imagesx($image)-($border*2),imagesy($image)-($border*2));
	if(!@imagecopyresampled($dst,$image,0,0,$border,$border,imagesx($dst),imagesy($dst),imagesx($image)-($border*2),imagesy($image)-($border*2)))
		return $image;
	return $dst;
}

function unknownlib_image_scaled($image,$target_width,$target_height,$background_color=array(255,255,255))
{
	if($target_width<=0 || $target_height<=0)
		unknownlib_die_perso('wrong image size');
	if(imagesx($image)<=0 || imagesy($image)<=0)
		unknownlib_die_perso('wrong image created size');
	$dst = imagecreatetruecolor($target_width,$target_height);
	$background = imagecolorallocate($dst, $background_color[0], $background_color[1], $background_color[2]);
	imagefill($dst, 0, 0, $background);
	$ratiox=(float)((float)imagesx($image)/(float)$target_width);
	$ratioy=(float)((float)imagesy($image)/(float)$target_height);
	if($ratiox<$ratioy)
		$min_ratio=$ratiox;
	else
		$min_ratio=$ratioy;
	$new_width=ceil(((float)imagesx($image))/$min_ratio);
	$new_height=ceil(((float)imagesy($image))/$min_ratio);
	$image_temp = imagecreatetruecolor($new_width,$new_height);
	//resize
	if(!@imagecopyresampled($image_temp,$image,0,0,0,0,$new_width,$new_height,imagesx($image),imagesy($image)))
		return $image;
	//crop
	if(!imagecopyresized($dst,$image_temp,ceil(((float)$target_width-(float)imagesx($image_temp))/2),ceil(((float)$target_height-(float)imagesy($image_temp))/2),0,0,imagesx($image_temp),imagesy($image_temp),imagesx($image_temp),imagesy($image_temp)))
	{
		imagedestroy($dst);
		return $image_temp;
	}
	imagedestroy($image_temp);
	return $dst;
}

?>