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
{
	header('Location: /login.html');
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Admin</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<link rel="stylesheet" type="text/css" href="css/style.css" media="all" />
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.16.custom.css" media="all" />
</head>
<body>
<div id="loader">
<img src="/images/loader.gif" alt="" height="24px" width="24px" />
Load the data...
</div>
<div id="content" style="display:none;">
Last product:<br />
<div id="last_product"></div>
<hr />
Product:<br />
<div id="product"><i><small><small>[NA]</small></small></i></div>
Specific informations:<br />
<div id="spec_informations"><i><small><small>[NA]</small></small></i></div>
Alternate informations:<br />
<div id="altern_informations"><i><small><small>[NA]</small></small></i></div>
Thumb: <br />
<div id="thumb"><i><small><small>[NA]</small></small></i></div>
</div>
<script type="text/javascript" src="js/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.16.custom.min.js"></script>
<script>
var content_fix_is_loaded=false;
var content_product_is_loaded=false;
var content_fix=null;
var content_product=null;
var operation_in_progress=false;
var need_new_product=0;
var is_getting_product=false;
var number_of_product_displayed=25;
var number_of_product_edited=0;
$.ajax({
url: 'content_fix.php',
dataType: 'json',
success: function(data) {
	content_fix=data;
	content_fix_is_loaded=true;
	update_display();
},
error: function() {
	alert('Data error');
}
});
$.ajax({
url: 'content_product.php?need_new_product='+number_of_product_displayed,
dataType: 'json',
success: function(data) {
	content_product=data;
	content_product_is_loaded=true;
	update_display();
},
error: function() {
	alert('Data error');
}
});
function update_display()
{
	if(content_fix_is_loaded && content_product_is_loaded)
	{
		$('#loader').attr('style','display:none');
		$('#content').removeAttr('style');
		display_product();
	}
	else
	{
		$('#loader').removeAttr('style');
		$('#content').attr('style','display:none');
	}
}
function display_product()
{
	$.each(content_product, function(index,content){
		content_product[index]['is_edited']=false;
		insert_product(index);
	});
}
function insert_product(index)
{
	var html='';
	html+='<a href="javascript:click_product('+index+')" id="product_code_'+index+'"><span style="border:1px solid #000;display:inline;">';
	if(typeof(content_product[index]['thumb_overwrite'])!='undefined')
		html+='<img src="/'+content_product[index]['table_product']+'/thumb_overwrite/'+content_product[index]['thumb_overwrite']+'-mini.jpg" alt="" height="64px" width="64px" />';
	else if(typeof(content_product[index]['thumb_mini'])!='undefined')
		html+='<img src="/'+content_product[index]['table_product']+'/'+content_product[index]['url_alias_for_seo']+'-mini.jpg" alt="" height="64px" width="64px" />';
	else
		html+='<img src="/images/no-photo-mini.png" alt="" height="64px" width="64px" />';
	html+='</span></a> ';
	$('#last_product').append(html);
	$('#product_code_'+index).show('pulsate',{times:2});
}
function click_product(index)
{
	if(check_operation_is_in_progress())
		return;
	var html='';
	html+='<ul>';
	html+='<li>Title: <input type="text" value="'+content_product[index]['title']+'" id="title" size="60" /><a href="javascript:info_save('+index+',\'title\')" id="title_link"><img src="images/document-save-as.png" alt="" height="16px" width="16px" /></a><img src="images/loader.gif" alt="" id="title_loader" style="display:none" height="16px" width="16px" /></li>';
	html+='<li>Boosted: '+content_product[index]['boosted']+'</li>';
	html+='<li>Ean: '+content_product[index]['ean']+'</li>';
	html+='<li>Mark: '+content_product[index]['mark']+'</li>';
	html+='<li>Product code: '+content_product[index]['product_code']+'</li>';
	html+='<li>Table product: '+content_product[index]['table_product']+'</li>';
	html+='<li>Unique identifier: '+content_product[index]['unique_identifier']+'</li>';
	html+='<li>Url alias for seo: <input type="text" value="'+content_product[index]['url_alias_for_seo']+'" id="url_alias_for_seo" size="60" /><a href="javascript:info_save('+index+',\'url_alias_for_seo\')" id="url_alias_for_seo_link"><img src="images/document-save-as.png" alt="" height="16px" width="16px" /></a><img src="images/loader.gif" alt="" id="url_alias_for_seo_loader" style="display:none" height="16px" width="16px" /></li>';
	html+='</ul>';
	html+='<input type="button" onclick="info_save('+index+',\'title\')" value="Is correct" /><br /><br />';
	$('#product').html(html);

	var html='';
	html+='<ul>';
	$.each(content_product[index]['spec'], function(i,content){
		html+='<li>'+i+': '+content_product[index]['spec'][i]+'</li>';
	});
	html+='</ul>';
	$('#spec_informations').html(html);

	var html='';
	html+='<ul>';
	$.each(content_product[index]['altern'], function(i,content){
		html+='<li>';
		$.each(content_fix, function(j,shop){
			if(shop['id']==content_product[index]['altern'][i]['shop_id'])
				html+=shop['name']+':<br />';
		});
		html+='<ul>';
		$.each(content_product[index]['altern'][i]['title'], function(j,title){
			html+='<li>'+title+'</li>';
		});
		html+='</ul>';
		html+='</li>';
	});
	html+='</ul>';
	$('#altern_informations').html(html);

	var html='';
	html+='<i><small><small>[NA]</small></small></i>';
	$('#thumb').html(html);
}
function info_save(index,type)
{
	if(check_operation_is_in_progress())
		return;
	if(!content_product[index]['is_edited'])
	{
		$('#product_code_'+index).fadeOut();
		content_product[index]['is_edited']=true;
		content_product[index]['is_edited_and_saved']=false;
	}
	$('#'+type+'_loader').removeAttr('style');
	$('#'+type+'_link').attr('style','display:none');
	$('#'+type).attr('disabled','disabled');
	operation_in_progress=true;
	$.ajax({
	url: 'register_'+type+'.php',
	type: 'POST',
	data: {"unique_identifier":content_product[index]['unique_identifier'],"value":$('#'+type).val()},
	dataType: 'html',
	success: function(data) {
		if(data=='OK')
			content_product[index][type]=$('#'+type).val();
		else
			alert(data);
		operation_in_progress=false;
		$('#'+type+'_loader').attr('style','display:none');
		$('#'+type+'_link').removeAttr('style');
		$('#'+type).removeAttr('disabled');
		if(!content_product[index]['is_edited_and_saved'])
		{
			content_product[index]['is_edited_and_saved']=true;
			need_new_product++;
			number_of_product_edited++;
			get_new_unedited_product();
		}
	},
	error: function() {
		operation_in_progress=false;
		$('#'+type+'_loader').attr('style','display:none');
		$('#'+type+'_link').removeAttr('style');
		$('#'+type).removeAttr('disabled');
	}
	});
}
function check_operation_is_in_progress()
{
	if(operation_in_progress)
		alert('Operation is in progress, wait the end');
	return operation_in_progress;
}
function get_new_unedited_product()
{
	if(need_new_product<=0)
		return;
	if(!is_getting_product)
	{
		var product_number_to_get=need_new_product;
		need_new_product=0;
		is_getting_product=true;
		$.ajax({
		url: 'content_product.php?need_new_product='+product_number_to_get+'&offset='+(number_of_product_displayed-product_number_to_get),
		dataType: 'json',
		success: function(data) {
			//append data
			content_product=content_product.concat(data);
			//append display
			insert_product(content_product.length-1);
			//try if remaning
			is_getting_product=false;
			get_new_unedited_product();
		},
		error: function() {
			is_getting_product=false;
			alert('Data error');
		}
		});
	}
}
function import_main()
{
	$('#dialog1').html('<iframe src="/php/cron/import_main.php" width="100%" height="100%"></iframe>');
	$('#dialog1').dialog({width:'90%',height:400});
}
function generate_html()
{
	$('#dialog2').html('<iframe src="/php/cron/generate-html.php" width="100%" height="100%"></iframe>');
	$('#dialog2').dialog({width:'90%',height:400});
}
</script>
<a href="javascript:import_main()">Import main</a>
<a href="javascript:generate_html()">Generate html</a>
<div id="dialog1" title="Import main"></div>
<div id="dialog2" title="Generate html"></div>
</body>
</html>