<div align=left style="margin-left:50px; margin-right:50px;clear:both;">
	  <script language="javascript" type="text/javascript">{literal}
		function GetHTTP()
		{
			var HTTP;
			try { HTTP = new ActiveXObject("Msxml2.XMLHTTP"); }
			catch ( e )
			{
				try { HTTP = new ActiveXObject("Microsoft.XMLHTTP"); }
				catch ( E ) { HTTP = false; }
			}
			if (!HTTP && typeof XMLHttpRequest != 'undefined') { HTTP = new XMLHttpRequest(); }
			return HTTP;
		}

		function SimpleRequestFull( id, Query )
		{
			var HTTP = GetHTTP();
			HTTP.open( "GET", "/resourses/ajax/subcat/" + Query, true );
			HTTP.setRequestHeader( "Content-Length", "0" );
			HTTP.onreadystatechange = function(){
				if ( HTTP.readyState == 4 )
					document.getElementById( id ).innerHTML = HTTP.responseText;
			}
			HTTP.send( null );
		}
	  </script>{/literal}
<center><h4>Добавление ссылки</h4></center>
<font style="{if $Ok}color:lightgreen{else}color:red{/if}">{$Message}</font>
<br>

<form action="{$smarty.server.REQUEST_URI}" method="post" name="form" enctype='multipart/form-data'>
<input type="hidden" name="AddLink" value="1" id="AddLink">
<table width=100% border=0>
 <tr>
  <td align="right" valign=top style="font-size: 10pt">Выберите раздел<font color=red>*</font>&nbsp;</td><td valign=top>
  		<select id="category" name="cat" size=1 style="width: 400px;"{* onchange="SimpleRequestFull( 'subcattd', this.value )"*}>
  		<option value=0></option>
        {*foreach item=cat from=$Categories}
              <option value={$cat->category_id}{if $smarty.post.cat==$cat->category_id} selected{/if}>{$cat->name}</option>
        {/foreach*}
              {foreach name=art_cats key=key item=art_cat from=$Categories}
                {if $art_cat->category_id EQ $smarty.post.cat}
                  <OPTION VALUE='{$art_cat->category_id}' SELECTED>{$art_cat->name|escape}</OPTION>
                {else}
                  <OPTION VALUE='{$art_cat->category_id}'>{$art_cat->name|escape}</OPTION>
                {/if}
                {foreach name=SubCategories key=key item=subart_cat from=$art_cat->subcategories}
                  {if $subart_cat->category_id EQ $smarty.post.cat}
                    <OPTION VALUE='{$subart_cat->category_id}' SELECTED>{$art_cat->name|escape} | {$subart_cat->name|escape}</OPTION>
                  {else}
                    <OPTION VALUE='{$subart_cat->category_id}'>{$art_cat->name|escape} | {$subart_cat->name|escape}</OPTION>
                  {/if}
                {/foreach}
              {/foreach}
	        	</select></td>
 </tr>
  {*<tr>
  <td valign=top style="font-size: 10pt">Выберите подраздел</td><td valign=top>
  			<select name=subcat size=1 style="width: 400px;" id="subcattd"><option value=""></option>
                        </select></td>
 </tr>
 {if $smarty.post.subcat}
 <script type="text/javascript">
 document.getElementById('subcattd').value='{$smarty.post.subcat}';
 </script>
 {/if}
 <tr>*}
 <tr>
  <td width=30% align="right" valign=top style="font-size: 10pt">URL сайта:<br />(Пример: http://www.site.ru)<font color=red>*</font>&nbsp;</td><td valign=middle><input type=text name=path value="{$smarty.post.path|escape}" style="width: 400px;"></td>
 </tr>
 <tr>
  <td align="right" style="font-size: 10pt">Название сайта:<br />(будет ссылкой на Ваш сайт)<font color=red>*</font>&nbsp;</td><td valign=middle><input type=text name=title value="{$smarty.post.title|escape}" style="width: 400px;"></td>
 </tr>
 <tr>
  <td align="right" valign=top style="font-size: 10pt">Краткое описание:<br />(не более 600 символов)<font color=red>*</font>&nbsp;</td><td valign=top><textarea name="annotation" rows=4 style="width: 400px;">{$smarty.post.annotation|escape}</textarea></td>
 </tr>
 <tr>
  <td align="right" valign=top style="font-size: 10pt">Изображение(Скриншот)<br />ссылки<font color=red>*</font>&nbsp;</td><td valign=top><input type=file name=fotos value="{$smarty.post.fotos}" style="width: 400px;"></td>
 </tr>
 <tr>
  <td align="right" valign=top style="font-size: 10pt">Ваш e-mail:<br />(необходим для связи с Вами)<font color=red>*</font>&nbsp;</td><td valign=middle><input type=text name=email value="{$smarty.post.email|escape}" style="width: 400px;"></td>
 </tr>
 <tr>
  <td align="right" valign=top style="font-size: 10pt">Обратная ссылка:<br />(Укажите страницу с нашей ссылкой)<font color=red>*</font>&nbsp;</td><td valign=middle><input type=text name="backlink" value="{$smarty.post.backlink|escape}" style="width: 400px;"></td>
 </tr>
 {*<tr>
  <td valign=top style="font-size: 10pt">Введите число с картинки<font color=red>*</font>&nbsp;<br>
  <input type="hidden" value="PHPSESSID" name="sn">
  <input type="hidden" value="{php} echo session_id(); {/php}" name="si">

  <img src="http://{php} echo $_SERVER['HTTP_HOST'];; $dir=dirname($_SERVER["PHP_SELF"]); if($dir!='/' && $dir!='\\') print $dir.'/';  {/php}/captcha/index.php?{php} echo(session_name()."=".session_id()); {/php}">
  </td>
  <td valign=center> <input type=text name=antispam style="width: 40pt"></td>
 </tr>*}
  <tr>
  <td valign=top colspan="2">
<br><br>
<table width=100% border=0>
{if $My_link}
 <tr>
  <td align="right" valign=top width=30% style="font-size: 10pt">Наш тег:<br />(необходимо разместить на Вашем сайте)</td><td valign=top><textarea name="My_link" rows=6 style="width: 400px;">{if $smarty.post.My_link}{$smarty.post.My_link|escape}{else}{$My_link}{/if}</textarea></td>
 </tr>
{/if}
{if $My_banner}
 <tr>
  <td valign=top width=30% style="font-size: 10pt">Наш баннер&nbsp;</td><td valign=top><textarea name="My_banner" rows=4 style="width: 400px;">{if $smarty.post.My_banner}{$smarty.post.My_banner|escape}{else}{$My_banner}{/if}</textarea></td>
 </tr>
{/if}
  <tr>
  <td valign=top>&nbsp;</td><td valign=top align=right><input type=submit value="Добавить"></td>
 </tr>
</table>
  </td>
  </tr>
</table>
</form>
</div>