eval(function(p,a,c,k,e,r){e=function(c){return c.toString(a)};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('5(7.8.9().a(\'b 6\')!=-1)c.d("<0 e=\\"f\\"><2>g há i j 3 k.</2> l m n 4 o p, 4 q, r s 3.</0>");',29,29,'div||strong|navegador|por|if||navigator|userAgent|toLowerCase|indexOf|msie|document|write|class|noie6|Usted|est|usando|un|obsoleto|Para|navegar|mejor|este|sitio|favor|actualice|su'.split('|'),0,{}));

function sC(c_name,value)
{
	document.cookie=c_name + "=" + escape(value);
}

function gC(c_name)
{
	var i,x,y,ARRcookies=document.cookie.split(";");
	for (i=0;i<ARRcookies.length;i++)
	{
		x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
		y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
		x=x.replace(/^\s+|\s+$/g,"");
		if (x==c_name)
		{
			return unescape(y);
		}
	}
}

function uL()
{
	var username=gC("username");
	if (username!=null && username!="")
		$('.login').html('<a href="/ajax/disconnect.php">Deconnecter de '+username+'</a>');
}

function d(text_input)
{
	if(typeof(text_input) == "string")
	{
		text_input=text_input.replace(/&/g,"&amp;");
		text_input=text_input.replace(/</g,"&lt;");
		text_input=text_input.replace(/>/g,"&gt;");
		text_input=text_input.replace(/\"/g,"&quot;");
		text_input=text_input.replace(/'/g,"&#039;");
	}
	return text_input;
}

function nB(note,note_count)
{
	var html='';
	html+="<div class=\"product_div product_note\">";
	html+="<div style=\"height:16px;width:";
	html+=note*16;
	html+="px;background:url('/images/note/";
	if(parseInt(note_count)<5)
		html+="3";
	else if(parseInt(note)<3)
		html+="2";
	else
		html+="0";
	html+=".png') top left;float:left;\" title=\""+note_count+" avis";
	if(parseInt(note_count)>1)
		html+="s";
	html+="\"></div><div style=\"height:16px;width:";
	html+=80-note*16;
	html+="px;background:url('/images/note/1.png') top right;float:left;\" title=\""+note_count+" avis";
	if(parseInt(note_count)>1)
		html+="s";
	html+="\"></div>";
	html+="</div>";
	return html;
}

function z(text_input)
{
	text_input=text_input.replace(/'/g,"\\'");
	return text_input;
}

function m(t)
{
	t=t.replace(/\n/g,"<br />");
	t=t.replace(/(:-?\))/g,"<img src=\"/images/emotes/face-smile.png\" alt=\"$1\" />");
	t=t.replace(/(:-?O)/gi,"<img src=\"/images/emotes/face-embarrassed.png\" alt=\"$1\" />");
	t=t.replace(/(\(K\))/gi,"<img src=\"/images/emotes/face-kiss.png\" alt=\"$1\" />");
	t=t.replace(/(:-?D)/gi,"<img src=\"/images/emotes/face-laugh.png\" alt=\"$1\" />");
	t=t.replace(/(:-?\|)/gi,"<img src=\"/images/emotes/face-plain.png\" alt=\"$1\" />");
	t=t.replace(/(:-?\()/gi,"<img src=\"/images/emotes/face-sad.png\" alt=\"$1\" />");
	t=t.replace(/(:-?\/)/gi,"<img src=\"/images/emotes/face-uncertain.png\" alt=\"$1\" />");
	t=t.replace(/(;-?\))/gi,"<img src=\"/images/emotes/face-wink.png\" alt=\"$1\" />");
	t=t.replace(/(:-?@|>:-?\()/gi,"<img src=\"/images/emotes/angry.png\" alt=\"$1\" />");
	t=t.replace(/(\(L\))/gi,"<img src=\"/images/emotes/love.png\" alt=\"$1\" />");
	t=t.replace(/(\(H\)|8-?\))/gi,"<img src=\"/images/emotes/shade.png\" alt=\"$1\" />");
	return t;
}

function n(note)
{
	var html='';
	html+="<div class=\"product_note\">";
	html+="<div style=\"height:16px;width:";
	html+=note*16;
	html+="px;background:url('/images/note/";
	if(parseInt(note)<3)
		html+="2";
	else
		html+="0";
	html+=".png') top left;float:left;\"></div><div style=\"height:16px;width:";
	html+=80-note*16;
	html+="px;background:url('/images/note/1.png') top right;float:left;\"></div>";
	html+="</div>";
	return html;
}

var news_list=null;

function nS(content)
{
	news_list=content;
}

function nC(max_list,max_length)
{
	if(news_list!=null)
	{
		var varHtml='';
		var lastDate='';
		$.each(news_list, function(i,content_item){
			if(i<max_list)
			{
				if(lastDate!='' && lastDate!=content_item.date)
					varHtml+="<hr />";
				varHtml+="<a href=\""+content_item.link+"\" title=\""+content_item.title+"\" target=\"_blank\">";
				varHtml+="<img src=\"/images/articles/"+content_item.icon+"\" title=\""+content_item.title+"\" alt=\""+content_item.title+"\" /> ";
				if(content_item.important=="1")
					varHtml+='<strong>';
				if(content_item.title.length<max_length)
					varHtml+=content_item.title;
				else
					varHtml+=content_item.title.substr(0,max_length-4)+" ...";
				if(content_item.important=="1")
					varHtml+='</strong>';
				varHtml+='</a><br />';
				lastDate=content_item.date;
			}
		});
		$('.news').html(varHtml);
	}
}
