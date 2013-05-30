var seo=null;
var sub_cat=null;
var base_ajax=null;
var list_note=null;
var username=null;

function s(sub_cat_local,seo_local,list_note_local)
{
	if(sub_cat_local=='boutiques')
		base_ajax='/ajax/comment_shop.php';
	else
		base_ajax='/ajax/comment_product.php';
	seo=seo_local;
	sub_cat=sub_cat_local;
	list_note=list_note_local;
	username=gC("username");
	s2();
}

function s2()
{
	if (username!=null && username!="")
	{
		var contentHtml='';
		contentHtml+='<div class="aviso2" style="clear:both;">';
		contentHtml+='<label style="float:left;width:50px;">Note:</label> <select id="nota_perso">';
		contentHtml+='<option value="0">0</option>';
		contentHtml+='<option value="1">1</option>';
		contentHtml+='<option value="2">2</option>';
		contentHtml+='<option value="3" selected="selected">3</option>';
		contentHtml+='<option value="4">4</option>';
		contentHtml+='<option value="5">5</option>';
		contentHtml+='</select><br />';
		contentHtml+='<label style="float:left;width:50px;">Avis:</label> <textarea rows="2" cols="50" style="width:605px;" id="text_perso"></textarea><br />';
		contentHtml+='<input type="button" value="Sauvegarder" id="save" onclick="c()" /> <input type="button" value="Supprimer" id="del" onclick="k()" style="display:none;" />';
		contentHtml+='</div>';
		$('.addNewAvis').html(contentHtml);
	}

	$('#save').removeAttr('disabled');
	$('#del').removeAttr('disabled');
	if(list_note.length>0)
	{
		var htmlUser='';
		if(username!=null && username!="")
		{
			$.each(list_note, function(i,comment){
				if(comment.login==username)
				{
					$('#text_perso').text(comment.comment);
					$('#nota_perso').val(comment.note);
					$('#save').val('Cambio');
					$('#del').css("display","inline");
					htmlUser+='<div class="acomment">'+n(comment.note)+'&nbsp;Comentaire de <b>'+d(comment.login)+'</b>, date: <b>'+d(comment.date)+'</b></div><div class="ycomm">'+m(d(comment.comment))+'</div>';
				}
			});
		}
		var note_tot=0;
		var htmlVar='';
		$.each(list_note, function(i,comment){
			note_tot+=parseInt(comment.note);
			if(username==null || username=="" || comment.login!=username)
			{
				htmlVar+='<div class="hcomment">'+n(comment.note)+'&nbsp;Comentaire de <b>'+d(comment.login)+'</b>, date: <b>'+d(comment.date)+'</b>';
				if(username!=null && username!="")
					htmlVar+=', <div style="float:right;" onclick="w(\''+d(z(comment.login))+'\')" class="fake_link"><img src="/images/status_unknown.png" alt="" style="vertical-align:middle;" /> Signaler</div>';
				htmlVar+='</div><div class="ycomm">'+m(d(comment.comment))+'</div>';
			}
		});
		htmlVar='<div class="aviso2 g" style="clear:both;">'+htmlUser+htmlVar+'</div>';
		$('#all_comment').html(htmlVar);
	}
	else
		$('#all_comment').html('');
}

function c()
{
	var new_list_note=new Array();
	var have_been_found=false;
	$.each(list_note, function(i,comment){
		if(comment.login==username)
		{
			have_been_found=true;
			comment.note=$('#nota_perso').val();
			comment.comment=$('#text_perso').val();
		}
		new_list_note.push(comment);
	});
	if(have_been_found!=true)
	{
		var d = new Date();
		var temp_item=new Array();
		temp_item.note=$('#nota_perso').val();
		temp_item.comment=$('#text_perso').val();
		temp_item.date=d.getDate()+'/'+(parseInt(d.getMonth())+1)+'/'+d.getFullYear();
		temp_item.login=username;
		new_list_note.push(temp_item);
		$.each(list_note, function(i,comment){
			new_list_note.push(comment);
		});
	}
	list_note=new_list_note;
	$('#save').attr('disabled', 'disabled');
	$('#del').attr('disabled', 'disabled');
	$.ajax({
	type: 'POST',
	url: base_ajax+'?action=new',
	data: {"note":$('#nota_perso').val(),"comment":$('#text_perso').val(),"seo":seo},
	dataType: 'html',
	success: function(data) {
		if(data!='OK')
			alert(data);
		$('#save').val('Changer');
		$('#del').css("display","inline");
		s2();
	}
	});
}

function k(comment_list)
{
	var new_list_note=new Array();
	$.each(list_note, function(i,comment){
		if(comment.login!=username)
			new_list_note.push(comment);
	});
	list_note=new_list_note;
	$('#save').attr('disabled', 'disabled');
	$('#del').attr('disabled', 'disabled');
	$.ajax({
	type: 'POST',
	url: base_ajax+'?action=del',
	dataType: 'html',
	data: {"seo":seo},
	success: function(data) {
		if(data!='OK')
			alert(data);
		$('#save').val('Sauvegarder');
		$('#del').css("display","none");
		s2();
	}
	});
}

function w(user_name)
{
	$.ajax({
	type: 'POST',
	url: base_ajax+'?action=warn',
	data: {"user_name":user_name,"seo":seo},
	dataType: 'html',
	success: function(data) {
		alert(data);
	}
	});
}

$('a.outlink').attr('target','_blank').click(function() {
	$.ajax({
	type: 'POST',
	url: '/ajax/out.php',
	data: {"url":$(this).attr("href"),"seo":seo}
	});
});


