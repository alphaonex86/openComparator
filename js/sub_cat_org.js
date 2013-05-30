var orderBy='mark';
var price_unit='BTC';
var product=null;
var product_info=null;
var sub_cat=null;

var myImage = new Image();
myImage.src = "/images/ui/bg_diagonals-thick_20_666666_40x40.png";

function l(product_type,product_local,product_info_local)
{
	sub_cat=product_type;
	product=product_local;
	product_info=product_info_local;
	z();
	a();
}

function z()
{
	//filter the combobox
	var tempHtml='';
	var contentFound=false;
	var list_mark_html='<div class="block_filter"><select id="mark" onchange="a()"><option value="none" selected="selected" class="full">Marca</option>';
	var list_mark=new Array();
	$.each(product, function(i,item){
		var isFound=false;
		$.each(list_mark, function(j,itemB){
			if(typeof(item.mark) != "undefined")
			{
				if(itemB==item.mark)
					isFound=true;
			}
			else
			{
				if(itemB=="Genérico")
					isFound=true;
			}
		});
		if(!isFound)
			if(typeof(item.mark) != "undefined")
				list_mark.push(item.mark);
			else
				list_mark.push("Genérico");
	});
	list_mark.sort();
	$.each(list_mark, function(i,item){
		list_mark_html+='<option value="'+item+'">'+item+'</option>';
		contentFound=true;
	});
	list_mark_html+='</select></div>';
	if(contentFound)
	{
		tempHtml+=list_mark_html;
		list_mark_html='';
	}
	$.each(product_info, function(j,attr){
		if(typeof(attr.filter_on_interface_sort_by) != "undefined")
		{
			contentFound=false;
			list_mark_html='';
			list_mark_html+='<div class="block_filter">';
			if(typeof(attr.unit) != "undefined")
			{
				list_mark_html+='<select id="sign_'+j+'" class="num" onchange="a()">';
				list_mark_html+='<option value="=" selected="selected">=</option>';
				list_mark_html+='<option value="&lt;=">&lt;=</option>';
				list_mark_html+='<option value="&gt;=">&gt;=</option>';
				list_mark_html+='</select>';
				list_mark_html+='<select id="val_'+j+'" class="val" onchange="a()">';
			}
			else
				list_mark_html+='<select id="'+j+'" class="full" onchange="a()">';
			list_mark_html+='<option value="none" selected="selected">'+attr.title+'</option>';
			var list_entry=new Array();
			$.each(product, function(a,item_product){
				var isFound=false;
				$.each(list_entry, function(b,item_in_tab){
					if(typeof(item_product[j]) != "undefined")
						if(item_in_tab==item_product[j])
							isFound=true;
				});
				if(!isFound)
					list_entry.push(item_product[j]);
			});
			list_entry.sort();
			if(list_entry.length>1)
			{
				$.each(list_entry, function(i,item){
					if(typeof(item) != "undefined")
					{
						var text=item;
						if(text=='yes')
							text='Si';
						if(text=='no')
							text='No';
						list_mark_html+='<option value="'+item+'">'+text;
						if(typeof(attr.unit) != "undefined")
							list_mark_html+=' '+attr.unit;
						list_mark_html+='</option>';
						contentFound=true;
					}
				});
			}
			list_mark_html+='</select>';
			list_mark_html+='</div>';
			if(contentFound!='')
				tempHtml+=list_mark_html;
		}
	});
	$('#product_filter').html(tempHtml);
}

function sp(a, b)
{
	return a.price - b.price;
}

function sm(a, b)
{
	if((typeof(a.mark) == "undefined" && typeof(b.mark) == "undefined") || a.mark==b.mark)
	{
		if(a.title==b.title)
			return 0;
		var arr=new Array(a.title,b.title);
		arr.sort();
		if(a.title==arr[0])
			return -1;
		else
			return 1;
	}
	if(typeof(a.mark)== "undefined")
		return 1;
	else if(typeof(b.mark)== "undefined")
		return -1;
	var arr=new Array(a.mark,b.mark);
	arr.sort();
	if(a.mark==arr[0])
		return -1;
	else
		return 1;
}

function a()
{
	if(product==null)
		return;
	if(product_info==null)
		return;
	if(sub_cat==null)
		return;
	product_listing=product;
	extra_attr=product_info;
	//finish the rest
	var last_mark='';
	var html='<input type=\"button\" onclick=\"c();\" value=\"Comparar los productos\" class=\"compb\" /><br style=\"clear:both\" />';
	var altern=true;
	var number_prod=0;
	var show_product=true;
	var str=$("#price_min").val();
	$("#price_min").val(str.replace(/[^0-9\.]+/i,""));
	str=$("#price_max").val();
	$("#price_max").val(str.replace(/[^0-9\.]+/i,""));
	orderBy=$("#orderBy").val();
	if(orderBy!='mark')
		product_listing.sort(sp);
	else
		product_listing.sort(sm);
	$.each(product_listing, function(i,item){
		if(typeof(item.mark) == "undefined")
			item.mark='Genérico';
		show_product=true;
		if($("#price_min").val()!='')
			if(parseFloat(item.price)<parseFloat($("#price_min").val()))
				show_product=false;
		if($("#price_max").val()!='')
			if(parseFloat(item.price)>parseFloat($("#price_max").val()))
				show_product=false;
		if($("#mark").val()!='none')
			if(item.mark!=$("#mark").val())
				show_product=false;
		$.each(extra_attr, function(j,attr){
			if(typeof(attr.unit) != "undefined")
			{
				if($("#val_"+j).val()!='none')
				{
					if($("#sign_"+j).val()=='=' && parseFloat(item[j])!=parseFloat($("#val_"+j).val()))
						show_product=false;
					if($("#sign_"+j).val()=='<=' && parseFloat(item[j])>parseFloat($("#val_"+j).val()))
						show_product=false;
					if($("#sign_"+j).val()=='>=' && parseFloat(item[j])<parseFloat($("#val_"+j).val()))
						show_product=false;
				}
			}
			else if(typeof($("#"+j).val()) != "undefined")
			{
				if($("#"+j).val()!='none' && item[j]!=$("#"+j).val())
					show_product=false;
			}
		});
		if(show_product==true)
		{
			if(last_mark!=item.mark && orderBy=='mark')
			{
				altern=true;
				last_mark=item.mark;
				html+="<div class=\"title_mark\">"+item.mark+"</div>";
			}
			html+="<div class=\"";
			if($("#showBy").val()!='image')
			{
				if(altern)
					html+="altern_true";
				else
					html+="altern_false";
			}
			html+=" product product_";
			html+=$("#showBy").val();
			html+="\">";
			if($("#showBy").val()=='list')
			{
				html+="<table style=\"width:675px\"><tr><td>";
				html+="<input type=\"checkbox\" id=\""+item.url_alias_for_seo+"\" style=\"float:left;margin:0 3px 0 0;\" /><a href=\"/"+sub_cat+"/"+item.url_alias_for_seo+".html\">";
				if(typeof(item['new']) != "undefined")
					html+="<img src=\"/images/new-mini.png\" alt=\"\" style=\"float:left;\">";
				else if(typeof(item.top) != "undefined")
					html+="<img src=\"/images/top-mini.png\" alt=\"\" style=\"float:left;\">";
				if(orderBy!='mark')
					html+=item.mark+' ';
				html+=d(item.title);
				html+="</a>";
				html+="</td><td class=\"product_price_text\">";
				if(typeof(item.note) != "undefined")
					html+=n(item.note,item.note_count);
				html+="</td><td style=\"text-align:right;width:60px\">";
				html+=item.price+price_unit;
				html+="</td></tr></table>";
			}
			else if($("#showBy").val()=='preview')
			{
				html+="<input type=\"checkbox\" id=\""+item.url_alias_for_seo+"\" style=\"float:left;margin:25px 1px;vertical-align:middle;\" /><a href=\"/"+sub_cat+"/"+item.url_alias_for_seo+".html\"><img src=\"";
				if(typeof(item.thumb_overwrite) != "undefined")
					html+="/"+sub_cat+"/thumb_overwrite/"+item.thumb_overwrite+"-mini.jpg";
				else if(typeof(item.thumb_mini) != "undefined")
					html+="/"+sub_cat+"/"+item.url_alias_for_seo+"-mini.jpg";
				else
					html+="/images/no-photo-mini.png";
				html+="\" alt=\"\" style=\"height:64px;width:64px;float:left;margin:4px;\" /></a><div style=\"width:460px;float:left;height:70px;\"><div class=\"product_text\"><a href=\"/"+sub_cat+"/"+item.url_alias_for_seo+".html\">";
				if(orderBy!='mark')
					html+=item.mark+' ';
				html+=d(item.title)+"</a>";
				if(typeof(item['new']) != "undefined")
					html+="<img src=\"/images/new-mini.png\" alt=\"\" style=\"float:left;\">";
				if(typeof(item.top) != "undefined")
					html+="<img src=\"/images/top-mini.png\" alt=\"\" style=\"float:left;\">";
				html+="</div>";
				var html_extra='';
				$.each(extra_attr, function(j,attr){
					if(typeof(item[j]) != "undefined")
					{
						if(html_extra!='')
							html_extra+=", ";
						html_extra+=attr.title+" "+item[j];
						if(typeof(attr.unit) != "undefined")
							html_extra+=attr.unit;
					}
				});
				html+="<div class=\"html_extra\">"+html_extra+"</div>";
				if(typeof(item.note) != "undefined")
				{
					html+=n(item.note,item.note_count);
					html+="&nbsp;"+item.note_count+" aviso";
					if(item.note_count>1)
						html+="s";
				}
				html+="</div><div class=\"block_right\"><div class=\"product_div product_price product_price2\">"+item.price+price_unit+"</div><div class=\"product_div product_boutique\">"+item.nbr_shop+" precios";
				if(item.nbr_shop>1)
					html+="s";
				html+="</div></div>";
			}
			else
			{
				html+="<a href=\"/"+sub_cat+"/"+item.url_alias_for_seo+".html\">";
				if(typeof(item.thumb_overwrite) != "undefined")
					html+="<img src=\"/"+sub_cat+"/thumb_overwrite/"+item.thumb_overwrite+".jpg\" alt=\"\" height=\"130px\" width=\"130px\" style=\"margin:4px;\" />";
				else if(typeof(item.thumb_normal) != "undefined")
					html+="<img src=\"/"+sub_cat+"/"+item.url_alias_for_seo+".jpg\" alt=\"\" height=\"130px\" width=\"130px\" style=\"margin:4px;\" />";
				else if(typeof(item.thumb_mini) != "undefined")
					html+="<img src=\"/"+sub_cat+"/"+item.url_alias_for_seo+"-mini.jpg\" alt=\"\" height=\"64px\" width=\"64px\" style=\"margin:39px;\" />";
				else
					html+="<img src=\"/images/no-photo.png\" alt=\"\" height=\"130px\" width=\"130px\" style=\"margin:4px;\" />";
				html+="<div style=\"margin:0 0 0 62px;\">";
				if(typeof(item.note) != "undefined")
					html+=n(item.note,item.note_count);
				html+="</div>";
				if(typeof(item['new']) != "undefined")
					html+="<img src=\"/images/new-mini.png\" alt=\"\">";
				if(typeof(item.top) != "undefined")
					html+="<img src=\"/images/top-mini.png\" alt=\"\">";			
				html+="<br /><div class=\"product_text2\">";
				if(orderBy!='mark')
					html+=item.mark+' ';
				html+=d(item.title)+"</div></a>";
				html+="<span class=\"price\">"+item.price+price_unit+"</span><br /><span class=\"price_list\">"+item.nbr_shop+" precios<br /></span><div style=\"margin-left:60px;width:90px;\"><input type=\"checkbox\" id=\""+item.url_alias_for_seo+"\" style=\"float:left;margin:0 3px 0 0;vertical-align:middle;\" /> Comparar</div>";

			}
			html+="</div>";
			altern=!altern;
			number_prod++;
		}
	});
	html+="<br style=\"clear:both;\" />";
	if(number_prod==0)
	{
		$("#numberItem").html("");
		html+="Ningún producto encontrado<br /><br />";
	}
	else
		$("#numberItem").html("("+number_prod+")");
	html+="<input type=\"button\" onclick=\"c();\" value=\"Comparar los productos\" class=\"compb\" />";
	$("#product_listing").html(html);
}

function c()
{
	if(product==null)
		return;
	if(product_info==null)
		return;
	if(sub_cat==null)
		return;
	var extra_attr=new Array();
	var product_listing=new Array();
	$.each(product, function(i,item){
		product_listing.push(item);
	});
	extra_attr=product_info;
	var html='';
	var arr=new Array();
	$.each(product_listing, function(i,item){
	if($("#"+item.url_alias_for_seo).length == 0)
		delete product_listing[i];
	else if($("#"+item.url_alias_for_seo+":checked").length > 0)
		arr.push(item);
	});
	if(arr.length>0)
	{
		var extra_col='';
		html+='<table class="tabcomp" cellspacing="0">';
		html+='<tr><td></td>';
		$.each(arr, function(i,item){
			html+="<td class=\"tdcomp"+extra_col+"\">";
			if(extra_col=='')
				extra_col='B';
			else
				extra_col='';
			if(typeof(item.thumb_overwrite) != "undefined")
				html+="<img src=\"/"+sub_cat+"/thumb_overwrite/"+item.thumb_overwrite+".jpg\" alt=\"\" height=\"130px\" width=\"130px\" style=\"margin:4px;\" />";
			else if(typeof(item.thumb_normal) != "undefined")
				html+="<img src=\"/"+sub_cat+"/"+item.url_alias_for_seo+".jpg\" alt=\"\" height=\"130px\" width=\"130px\" style=\"margin:4px;\" />";
			else if(typeof(item.thumb_mini) != "undefined")
				html+="<img src=\"/"+sub_cat+"/"+item.url_alias_for_seo+"-mini.jpg\" alt=\"\" height=\"64px\" width=\"64px\" style=\"margin:39px;\" />";
			else
				html+="<img src=\"/images/no-photo.png\" alt=\"\" height=\"130px\" width=\"130px\" style=\"margin:4px;\" />";
			html+="</td>";
		});
		html+='</tr>';
		html+='<tr><td></td>';
		extra_col='';
		$.each(arr, function(i,item){
			html+="<td class=\"tdcomp"+extra_col+"\">"+item.title+"</td>";
			if(extra_col=='')
				extra_col='B';
			else
				extra_col='';
		});
		html+='</tr>';
		html+='<tr class="altern_true">';
		html+='<td>Marca</td>';
		extra_col='';
		$.each(arr, function(i,item){
			if(typeof(item.mark) == "undefined")
				item.mark='Genérico';
			html+="<td class=\"tdcomp"+extra_col+"\">"+item.mark+"</td>";
			if(extra_col=='')
				extra_col='B';
			else
				extra_col='';
		});
		html+='</tr>';
		var altern=false;
		$.each(extra_attr, function(j,attr){
			html+='<tr class="';
			if(altern)
				html+='altern_true';
			else
				html+='altern_false';
			html+='">';
			html+='<td>'+attr.title+'</td>';
			extra_col='';
			$.each(arr, function(i,item){
				html+="<td class=\"tdcomp"+extra_col+"\">";
				if(extra_col=='')
					extra_col='B';
				else
					extra_col='';
				if(typeof(item[j]) != "undefined")
				{
					html+=item[j];
					if(typeof(attr.unit) != "undefined")
						html+=attr.unit;
				}
				html+="</td>";
			});
			html+='</tr>';
			altern=!altern;
		});
		html+='<tr class="';
		if(altern)
			html+='altern_true';
		else
			html+='altern_false';
		html+='">';
		html+='<td>Precio</td>';
		extra_col='';
		$.each(arr, function(i,item){
			html+="<td class=\"tdcomp"+extra_col+"\">"+item.price+price_unit+"</td>";
			if(extra_col=='')
				extra_col='B';
			else
				extra_col='';
		});
		html+='</tr>';
		html+='</table>';
	}
	else
		html+='Ningún producto seleccionado.';
	$("#dialog-modal").html(html);
	$("#dialog-modal").dialog({
		modal: true,
		show: "explode",
		hide: "explode",
		width: 'auto'
	});
}

