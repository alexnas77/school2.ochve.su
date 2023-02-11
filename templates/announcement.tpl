<h1>{$Title}</h1>
{if $smarty.get.category}
{if $Announcements}
<noindex>
<table width="80%" align="center" cellpadding="10" cellspacing="0" border="0">
{foreach item=product from=$Announcements}
  <tr>
<TD ALIGN=LEFT valign=top style="background-color: #DDDDDD; margin: 5px 0px; border-top: 2px solid #AEAEAE; border-bottom: 2px solid #AEAEAE; border-left: 2px solid #AEAEAE;">
    <table cellpadding=5 cellspacing=0 width="100%" height="100%">
      <tr>
        <td colspan=2>
          <b>{$product->date} : {$product->brand|escape} - {$product->model|escape}</b>
        </td>
      </tr>
      <tr>
        <td>
         Год выпуска : {$product->year}
        </td>
        <td>
         Объем двигателя : {$product->volume} куб.мм.
        </td>
      </tr>
      <tr>
        <td>
         Город : {$product->city}
        </td>
        <td>
         &nbsp;
        </td>
      </tr>
      <tr>
        <td colspan=2>
          {$product->body}
        </td>
      </tr>
      <tr>
        <td colspan=2 align="right">
           <nobr><span class=price>
            Цена: {$product->price|string_format:"%.2f"}
            {$Currency->sign}</span></nobr>
        </td>
      </tr>
      <tr>
        <td>
         Телефон : {$product->phone}
        </td>
        <td>
         E-mail : {if $product->mail}{mailto address=$product->mail encode="javascript"}{else}не указана{/if}
        </td>
      </tr>
      <tr>
        <td colspan=2>
         Контактное лицо : {$product->contact}
        </td>
      </tr>
    </table>
    </TD>

    <TD align=center valign=top width=120 style="background-color: #DDDDDD; margin: 5px 0px; border-top: 2px solid #AEAEAE; border-bottom: 2px solid #AEAEAE; border-right: 2px solid #AEAEAE;">
      <table width=120 cellpadding=0 cellspacing=0>
        <tr>
          <td ALIGN=center valign=top style='padding:10'>
           <IMG ALT='{$product->brand|escape} {$product->model|escape}' border=0 width=150 src='{if $product->file}foto/usr/{$product->file}{else}images/no_foto.gif{/if}'>
          </td>
        </tr>
      </table>
    </TD>
     </tr>
{/foreach}
</table>
</noindex>
{else}
<table width="80%" align="center" cellpadding="10" cellspacing="0" border="0">
<tr>
<td align="left" style="padding:10px 0px;background-color: White;">
Объявлений нет
  </td>
  </tr>
</table>
{/if}
{else}
<table align="center" cellpadding="5" cellspacing="5" border="0">
<tr>
<td style="padding:10px 0px;">
  <form name="send" action="index.php?section=17" method="post" enctype='multipart/form-data'>
  <b>{$result}</b>
  </td>
  </tr>
  <tr>
  <td align="left" style="padding:5px 0px;">
  <b>Тип объявления:</b>
  </td>
  <td align="left" style="padding:5px 0px;">
  <select size="1" name="sale" style="width:300px;">
  <option value="0">Покупка</option>
  <option value="1">Продажа</option>
  </select>
  </td>
  </tr>
  <tr>
  <td align="left" style="padding:5px 0px;">
  <b>Категория:</b>
  </td>
  <td align="left" style="padding:5px 0px;">
  <select size="1" name="category" style="width:300px;">
  <option value="1">Скутер</option>
  <option value="2">Мотоцикл</option>
  <option value="3">Запчасти</option>
  </select>
  &nbsp;&nbsp;&nbsp;
  </td>
  </tr>
  <tr>
  <td align="left" style="padding:5px 0px;">
  <b>Срок размещения:</b>
  </td>
  <td align="left" style="padding:5px 0px;">
  <select size="1" name="range" style="width:300px;">
  <option value="30">1 месяц</option>
  <option value="7">1 неделя</option>
  <option value="1">1 день</option>
  </select>
  </td>
  </tr>
  <tr>
  <td align="left" style="padding:5px 0px;">
  <b>Производитель (Марка):</b>
  </td>
  <td align="left" style="padding:5px 0px;">
  <input type="text" name="brand" value="" style="width:300px;" {literal} pattern='^.{1,255}$' notice='Введите Производителя' {/literal}>&nbsp;<sup style="color:#661D25;font-size:14px;">*</sup>
  </td>
  </tr>
  <tr>
  <td align="left" style="padding:5px 0px;">
  <b>Модель:</b>
  </td>
  <td align="left" style="padding:5px 0px;">
  <input type="text" name="model" value="" style="width:300px;" {literal} pattern='^.{1,255}$' notice='Введите Модель' {/literal}>&nbsp;<sup style="color:#661D25;font-size:14px;">*</sup>
  </td>
  </tr>
  <tr>
  <td align="left" style="padding:5px 0px;">
  <b>Год выпуска:</b>
  </td>
  <td align="left" style="padding:5px 0px;">
  <input type="text" name="year" value="" style="width:300px;" {literal} pattern='^.{1,255}$' notice='Введите Год выпуска' {/literal}>&nbsp;<sup style="color:#661D25;font-size:14px;">*</sup>
  </td>
  </tr>
  <tr>
  <td align="left" style="padding:5px 0px;">
  <b>Объем двигателя, куб. см:</b>
  </td>
  <td align="left" style="padding:5px 0px;">
  <input type="text" name="volume" value="" style="width:300px;" {literal} pattern='^.{1,255}$' notice='Введите Объем двигателя' {/literal}>&nbsp;<sup style="color:#661D25;font-size:14px;">*</sup>
  </td>
  </tr>
  <tr>
  <td align="left" style="padding:5px 0px;">
  <b>Город:</b>
  </td>
  <td align="left" style="padding:5px 0px;">
  <input type="text" name="city" value="" style="width:300px;" {literal} pattern='^.{1,255}$' notice='Введите Город' {/literal}>&nbsp;<sup style="color:#661D25;font-size:14px;">*</sup>
  </td>
  </tr>
  <tr>
  <td align="left" style="padding:5px 0px;">
  <b>Цена:</b>
  </td>
  <td align="left" style="padding:5px 0px;">
  <input type="text" name="price" value="" style="width:300px;" {literal} pattern='^.{1,255}$' notice='Введите Цену' {/literal}>&nbsp;<sup style="color:#661D25;font-size:14px;">*</sup>
  </td>
  </tr>
  <tr>
  <td align="left" style="padding:5px 0px;">
  <b>Фотография:</b>
  </td>
  <td align="left" style="padding:5px 0px;">
  <input name="foto" type="file"  size="35" style="width:300px;">
  </td>
  </tr>
  <tr>
  <td align="left" style="padding:5px 0px;">
  <b>Дополнительная информация:</b>
  </td>
  <td align="center" style="padding:5px 0px;">
  <textarea name="body" rows="10" cols="34" style="width:300px;overflow-x:auto;overflow-y:scroll;"></textarea>&nbsp;&nbsp;&nbsp;
  </td>
  </tr>
  <tr>
  <td align="left" style="padding:5px 0px;">
  <b>Телефон:</b>
  </td>
  <td align="left" style="padding:5px 0px;">
  <input type="text" name="phone" value="" style="width:300px;" {literal} pattern='^.{1,255}$' notice='Введите Телефон' {/literal}>&nbsp;<sup style="color:#661D25;font-size:14px;">*</sup>
  </td>
  </tr>
  <tr>
  <td align="left" style="padding:5px 0px;">
  <b>E-mail:</b>
  </td>
  <td align="left" style="padding:5px 0px;">
  <input type="text" name="mail" value="" style="width:300px;" {literal} pattern='^[^\@\.]+\@[^\@\.]+\.[0-9a-zA-z]+$' notice='Введите E-mail (xxx@xxx.xxx)' {/literal}>&nbsp;<sup style="color:#661D25;font-size:14px;">*</sup>
  </td>
  </tr>
  <tr>
  <td align="left" style="padding:5px 0px;">
  <b>Контакное лицо:</b>
  </td>
  <td align="left" style="padding:5px 0px;">
  <input type="text" name="contact" value="" style="width:300px;" {literal} pattern='^.{1,255}$' notice='Введите Контакное лицо' {/literal}>&nbsp;<sup style="color:#661D25;font-size:14px;">*</sup>
  </td>
  </tr>
  <tr>
  <td align="center" style="padding:5px 0px;">
  <input type="reset" value="Сбросить" style="color:#AEAEAE;font-size:12px;border:0px;background-color:White;">
  </td>
  <td align="center" style="padding:5px 0px;">
  <input name="send" type="submit" value="Отправить" style="color:#AEAEAE;font-size:12px;border:0px;background-color:White;">
  </td>
  </tr>
  <tr>
  <td colspan="2" style="padding:20x 0px;">
  &nbsp;<sup style="color:#661D25;font-size:14px;">*</sup><span style="color:#661D25;font-size:14px;"> - Обязательные параметры</span>
  </form>
  </td>
</tr>
</table>
{/if}