<?php
$data_product=array();
$reply_product_info = unknownlib_mysql_query('SELECT * FROM `information_extra_motherboard` WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
if($data_product_info = mysql_fetch_array($reply_product_info))
	$data_product=$data_product_info;
else
	unknownlib_mysql_query('INSERT INTO `information_extra_motherboard`(`unique_identifier_product`) VALUES (\''.$unique_identifier_product.'\')');
if(!isset($data_product['socket']) || $data_product['socket']=='')
	if(isset($item['socket']))
		unknownlib_mysql_query('UPDATE `information_extra_motherboard` SET `socket`=\''.addslashes($item['socket']).'\' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
if(!isset($data_product['memory_type']) || $data_product['memory_type']=='')
	if(isset($item['memory_type']))
		unknownlib_mysql_query('UPDATE `information_extra_motherboard` SET `memory_type`=\''.addslashes($item['memory_type']).'\' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
if(!isset($data_product['format']) || $data_product['format']=='')
	if(isset($item['format']))
		unknownlib_mysql_query('UPDATE `information_extra_motherboard` SET `format`=\''.addslashes($item['format']).'\' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
if(!isset($data_product['northbridge']) || $data_product['northbridge']=='')
	if(isset($item['northbridge']))
		unknownlib_mysql_query('UPDATE `information_extra_motherboard` SET `northbridge`=\''.addslashes($item['northbridge']).'\' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
if(!isset($data_product['port_memoire']) || $data_product['port_memoire']=='')
	if(isset($item['port_memoire']) && preg_match('#^[0-9]+$#',$item['port_memoire']))
		unknownlib_mysql_query('UPDATE `information_extra_motherboard` SET `port_memoire`='.$item['port_memoire'].' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
if(!isset($data_product['PCIe_x16']) || $data_product['PCIe_x16']==0)
	if(isset($item['PCIe_x16']) && preg_match('#^[0-9]+$#',$item['PCIe_x16']))
		unknownlib_mysql_query('UPDATE `information_extra_motherboard` SET `PCIe_x16`='.addslashes($item['PCIe_x16']).' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
if(!isset($data_product['PCIe_x4']) || $data_product['PCIe_x4']==0)
	if(isset($item['PCIe_x4']) && preg_match('#^[0-9]+$#',$item['PCIe_x4']))
		unknownlib_mysql_query('UPDATE `information_extra_motherboard` SET `PCIe_x4`='.addslashes($item['PCIe_x4']).' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
if(!isset($data_product['PCIe_x1']) || $data_product['PCIe_x1']==0)
	if(isset($item['PCIe_x1']) && preg_match('#^[0-9]+$#',$item['PCIe_x1']))
		unknownlib_mysql_query('UPDATE `information_extra_motherboard` SET `PCIe_x1`='.addslashes($item['PCIe_x1']).' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
if(!isset($data_product['PCI']) || $data_product['PCI']==0)
	if(isset($item['PCI']) && preg_match('#^[0-9]+$#',$item['PCI']))
		unknownlib_mysql_query('UPDATE `information_extra_motherboard` SET `PCI`='.addslashes($item['PCI']).' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
if(!isset($data_product['IDE']) || $data_product['IDE']=='')
	if(isset($item['IDE']) && preg_match('#^[0-9]+$#',$item['IDE']))
		unknownlib_mysql_query('UPDATE `information_extra_motherboard` SET `IDE`='.addslashes($item['IDE']).' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
if(!isset($data_product['SATA']) || $data_product['SATA']=='')
	if(isset($item['SATA']) && preg_match('#^[0-9]+$#',$item['SATA']))
		unknownlib_mysql_query('UPDATE `information_extra_motherboard` SET `SATA`='.addslashes($item['SATA']).' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
if(!isset($data_product['eSATA']) || $data_product['eSATA']=='')
	if(isset($item['eSATA']) && preg_match('#^[0-9]+$#',$item['eSATA']))
		unknownlib_mysql_query('UPDATE `information_extra_motherboard` SET `eSATA`='.addslashes($item['eSATA']).' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
if(!isset($data_product['SCSI']) || $data_product['SCSI']=='')
	if(isset($item['SCSI']) && preg_match('#^[0-9]+$#',$item['SCSI']))
		unknownlib_mysql_query('UPDATE `information_extra_motherboard` SET `SCSI`='.addslashes($item['SCSI']).' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
if(!isset($data_product['RAID']) || $data_product['RAID']=='')
	if(isset($item['RAID']) && preg_match('#^[0-9]+$#',$item['RAID']))
		unknownlib_mysql_query('UPDATE `information_extra_motherboard` SET `RAID`=\''.addslashes($item['RAID']).'\' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
if(!isset($data_product['USB']) || $data_product['USB']=='')
	if(isset($item['USB']) && preg_match('#^[0-9]+$#',$item['USB']))
		unknownlib_mysql_query('UPDATE `information_extra_motherboard` SET `USB`='.addslashes($item['USB']).' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
if(!isset($data_product['firewire']) || $data_product['firewire']=='')
	if(isset($item['firewire']))
		unknownlib_mysql_query('UPDATE `information_extra_motherboard` SET `firewire`='.addslashes($item['firewire']).' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
if(!isset($data_product['sound']) || $data_product['sound']=='')
	if(isset($item['sound']) && $item['sound']!='' && $item['sound']!='0')
		unknownlib_mysql_query('UPDATE `information_extra_motherboard` SET `sound`=\''.addslashes($item['sound']).'\' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
if(!isset($data_product['sound_codec']) || $data_product['sound_codec']=='')
	if(isset($item['sound_codec']))
		unknownlib_mysql_query('UPDATE `information_extra_motherboard` SET `sound_codec`=\''.addslashes($item['sound_codec']).'\' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
if(!isset($data_product['network']) || $data_product['network']=='')
	if(isset($item['network']))
		unknownlib_mysql_query('UPDATE `information_extra_motherboard` SET `network`=\''.addslashes($item['network']).'\' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
if(!isset($data_product['UEFI']) || $data_product['network']=='UEFI')
	if(isset($item['UEFI']))
		unknownlib_mysql_query('UPDATE `information_extra_motherboard` SET `UEFI`=\''.addslashes($item['UEFI']).'\' WHERE `unique_identifier_product`=\''.$unique_identifier_product.'\'');
