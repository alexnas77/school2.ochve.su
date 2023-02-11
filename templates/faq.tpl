<h1>{$Page_title}</h1>
{if $Faqs}
<table width="100%" align="center" cellpadding="10" cellspacing="0" border="0" style="position:relative;left:0px;">
{foreach item=item from=$Faqs}
<td width="15%" align="center" style="padding:10px 10px;background-color: #F6F6F6;border-right:2px #AEAEAE solid;">
<b style="color: #000000;">Вопрос:</b>
  </td>
<td align="left" style="padding:10px 10px;background-color: #F6F6F6;">
<b style="color: #000000;">{$item->message} ({$item->name}, г.{$item->city})</b>
  </td>
  </tr>
<tr>
<td width="15%" align="center" style="padding:10px 10px;background-color: White;border-right:2px #AEAEAE solid;border-bottom:2px #AEAEAE solid;">
<b>Ответ:</b>
  </td>
<td align="left" style="padding:10px 10px;background-color: White;border-bottom:2px #AEAEAE solid;">
{$item->answer}
  </td>
  </tr>
{/foreach}
</table>
{/if}
<div align="center">
<table align="center" cellpadding="5" cellspacing="5" border="0">
<tr>
<td style="padding:10px 0px;">
  <form name="send" method="post">
  <b>{$result}</b>
  </td>
  </tr>
  <tr>
  <td align="center" style="padding:5px 0px;">
  <input name="name" type="text" value="{if $name}{$name|escape}{else}Ваше имя{/if}" style="width:500px;color:#999999;" onFocus="if(this.value=='Ваше имя')this.value='';" onBlur="if( !this.value)this.value='Ваше имя';">&nbsp;<sup style="color:#661D25;font-size:14px;">*</sup>
  </td>
  </tr>
  {if $smarty.get.mode=='contact'}<tr>
  <td align="center" style="padding:5px 0px;">
  <input name="mail" type="text" value="{if $mail}{$mail|escape}{else}E-mail{/if}" style="width:500px;color:#999999;" onFocus="if(this.value=='E-mail')this.value='';" onBlur="if( !this.value)this.value='E-mail';">&nbsp;<sup style="color:#661D25;font-size:14px;">*</sup>
  </td>
  </tr>
      <tr>
      <td align="center" style="padding:5px 0px;">
          <input name="subject" type="text" value="{if $subject}{$subject|escape}{else}Тема{/if}" style="width:500px;color:#999999;" onFocus="if(this.value=='Тема')this.value='';" onBlur="if( !this.value)this.value='Тема';">&nbsp;&nbsp;&nbsp;
      </td>
      </tr>{/if}
  {if $smarty.get.mode=='zakazat-zvonok'}<tr>
  <td align="center" style="padding:5px 0px;">
  <input name="phone" type="text" value="{if $phone}{$phone|escape}{else}Телефон{/if}" style="width:500px;color:#999999;" onFocus="if(this.value=='Телефон')this.value='';" onBlur="if( !this.value)this.value='Телефон';">&nbsp;<sup style="color:#661D25;font-size:14px;">*</sup>
  </td>
  </tr>
      <tr>
      <td align="center" style="padding:5px 0px;">
          <input name="time" type="text" value="{if $time}{$time|escape}{else}Удобное время для связи{/if}" style="width:500px;color:#999999;" onFocus="if(this.value=='Удобное время для связи')this.value='';" onBlur="if( !this.value)this.value='Удобное время для связи';">&nbsp;&nbsp;&nbsp;
      </td>
      </tr>{/if}
  <tr>
  <td align="center" style="padding:5px 0px;">
  <div align="left" style="color:Black;font-size:12px;margin-left:0px;padding:3px 0px;">Сообщение:&nbsp;<sup style="color:#661D25;font-size:14px;">*</sup></div><br />
  <textarea name="message" rows="10" cols="60" style="width:500px;">{if $message}{$message}{/if}</textarea>&nbsp;&nbsp;&nbsp;
  </td>
  </tr>
  <tr>
  <td align="center" style="padding:5px 0px;">
  <div align="left" style="color:Black;font-size:12px;margin-left:0px;padding:3px 0px;">Введите код:</div><br />
  <img src="http://{php} echo $_SERVER['HTTP_HOST'];; $dir=dirname($_SERVER["PHP_SELF"]); if($dir!='/' && $dir!='\\') print $dir.'/';  {/php}/protect/index.php?{php} echo(session_name()."=".session_id()); {/php}">
  </td>
  </tr>
  <tr>
  <td align="center" style="padding:5px 0px;">
  <input type="text" name="keystring" value="" style="width:500px;color:#999999;">&nbsp;<sup style="color:#661D25;font-size:14px;">*</sup>
  </td>
  </tr>
  <tr>
  <td align="right" style="padding:5px 0px;">
  <input name="sendmessage" type="submit" value="Отправить" style="color:#AEAEAE;font-size:12px;border:0px;background-color:White;margin-right:75px;">
  </td>
  </tr>
  <tr>
  <td style="padding:20x 0px;">
  &nbsp;<sup style="color:#661D25;font-size:14px;">*</sup><span style="color:#661D25;font-size:14px;"> - Обязательные параметры</span>
  </form>
  </td>
</tr>
</table>
</div>