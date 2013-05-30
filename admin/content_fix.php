<?php
include_once '../config/mysql.php';
include_once '../config/general.php';
include_once '../lib/unknownlib-function.php';
include_once '../lib/unknownlib-mysql.php';

unknownlib_mysql_connect_or_quit();

session_start();
if(isset($_SESSION['is_admin']))
{
	if($_SESSION['is_admin']!=1)
		unknownlib_tryhack('The user is not admin');
}
else
	unknownlib_tryhack('The user is not logged');

$shop_data=array();
$reply=unknownlib_mysql_query('SELECT * FROM `shop`');
while($data=mysql_fetch_array($reply))
	$shop_data[]=unknownlib_mysql_clean_data_return($data);

$sub_cat_data=array();
foreach($GLOBALS['unknownlib']['site']['categories'] as $componentes)
	foreach($componentes['sub_cat'] as $name => $sub_cat)
	{
		$file_list=array();
		//here thumb list
		$dir='../'.$name.'/thumb_overwrite/';
		if($dh = @opendir($dir))
		{
			while (false !== ($file = readdir($dh)))
			{
				if($file == '.' || $file == '..')
					continue;
				else
				{
					$final_name=str_replace('-mini.jpg','',$file);
					if(!in_array($final_name,$file_list))
						$file_list[]=$final_name;
				}
			}
			closedir($dh);
		}
		$sub_cat['file_list']=$file_list;
		$sub_cat_data[$name]=$sub_cat;
	}

echo unknownlib_array_to_json($shop_data,$sub_cat_data);