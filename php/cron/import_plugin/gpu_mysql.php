<?php
$data_product=array();
$reply_product_info = unknownlib_mysql_query('SELECT * FROM `information_extra_graphiccard` WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
if($data_product_info = mysql_fetch_array($reply_product_info))
	$data_product=$data_product_info;
else
	unknownlib_mysql_query('INSERT INTO `information_extra_graphiccard`(`unique_identifier_product`) VALUES (\''.$unique_identifier_product.'\')');
if(!isset($data_product['familly']) || $data_product['familly']=='')
	if(isset($item['familly']))
		unknownlib_mysql_query('UPDATE `information_extra_graphiccard` SET `familly`=\''.addslashes($item['familly']).'\' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
if(!isset($data_product['memory_type']) || $data_product['memory_type']=='')
	if(isset($item['memory_type']))
		unknownlib_mysql_query('UPDATE `information_extra_graphiccard` SET `memory_type`=\''.addslashes($item['memory_type']).'\' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
if(!isset($data_product['memory']) || $data_product['memory']==0)
	if(isset($item['memory']) && preg_match('#^[0-9]+$#i',$item['memory']))
		unknownlib_mysql_query('UPDATE `information_extra_graphiccard` SET `memory`='.addslashes($item['memory']).' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
if(!isset($data_product['bus']) || $data_product['bus']=='')
	if(isset($item['bus']))
		unknownlib_mysql_query('UPDATE `information_extra_graphiccard` SET `bus`=\''.addslashes($item['bus']).'\' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
if(!isset($data_product['cooling_type']) || $data_product['cooling_type']=='')
	if(isset($item['cooling_type']))
		unknownlib_mysql_query('UPDATE `information_extra_graphiccard` SET `cooling_type`=\''.addslashes($item['cooling_type']).'\' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
if(!isset($data_product['frequency_gpu']) || $data_product['frequency_gpu']==0)
	if(isset($item['frequency_gpu']) && preg_match('#^[0-9]{1,4}$#i',$item['frequency_gpu']))
		unknownlib_mysql_query('UPDATE `information_extra_graphiccard` SET `frequency_gpu`='.addslashes($item['frequency_gpu']).' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
if(!isset($data_product['frequency_memory']) || $data_product['frequency_memory']==0)
	if(isset($item['frequency_memory']) && preg_match('#^[0-9]{1,4}$#i',$item['frequency_memory']))
		unknownlib_mysql_query('UPDATE `information_extra_graphiccard` SET `frequency_memory`='.addslashes($item['frequency_memory']).' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
if(!isset($data_product['output_video']) || $data_product['output_video']=='')
	if(isset($item['output_video']))
		unknownlib_mysql_query('UPDATE `information_extra_graphiccard` SET `output_video`=\''.addslashes($item['output_video']).'\' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
if(!isset($data_product['DirectX']) || $data_product['DirectX']=='')
	if(isset($item['DirectX']) && preg_match('#^[0-9]{1,2}(\.[0-9]+)$#i',$item['DirectX']))
		unknownlib_mysql_query('UPDATE `information_extra_graphiccard` SET `DirectX`='.addslashes($item['DirectX']).' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
if(!isset($data_product['OpenGL']) || $data_product['OpenGL']=='')
	if(isset($item['OpenGL']) && preg_match('#^[0-9](\.[0-9])?$#i',$item['OpenGL']))
		unknownlib_mysql_query('UPDATE `information_extra_graphiccard` SET `OpenGL`='.addslashes($item['OpenGL']).' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');