<H1>Корзина</H1>
<FORM METHOD=POST>
<TABLE cellpadding=0 cellspacing=10 >
  {foreach name=products item=product from=$Products}
  <tr height=120>
    <TD align=center valign=top width=120 height=120>
      <table width=120 height=120 cellpadding=0 cellspacing=0  ALIGN=center valign=center>
        <tr>
          <td ALIGN=center valign=center style='height:120px; border: 1px solid lightgray; padding:10'>
            <A TITLE='{$product->brand|escape}{$product->model|escape}' HREF='catalog/{$product->category_id}/{$product->brand|escape}/{$product->product_id}.html' CLASS=subheader><IMG width='150px' ALT='{$product->brand|escape} {$product->model|escape}' border=0 src='{if $product->filename}foto/storefront/{$product->filename}{else}images2/no_foto.gif{/if}'></A>
          </td>
        </tr>
      </table>
    </TD>
    <TD ALIGN=LEFT valign=top style='border-bottom: 1px dotted lightgray;'>
    <table cellpadding=5 cellspacing=0 width=100%>
      <tr>
        <td colspan=4>
          <A TITLE='{$product->brand|escape} {$product->model}' HREF='catalog/{$product->category_id}/{$product->brand|escape}/{$product->product_id}.html' CLASS=product_name>{$product->category} {$product->brand} {$product->model}</a><br>
        </td>
      </tr>
      <tr>
        <td>
          &nbsp;
        </td>
        <td align=right width=150>
            <span class=price>{$product->discount_price/$product->currency_rate*$Currency->rate|string_format:"%.2f"} {$Currency->sign}</span>
        </td>
        <td align=right width=150>
            <b><input name=quantities[{$product->product_id}] type=text size=4 value='{$product->quantity}'>&nbsp;шт.</b><BR>
        </td>
        <td width=100>
            <a href="index.php?section=7&delete_product_id={$product->product_id}">Удалить</a>
        </td>
      </tr>
    </table>
    </TD>
    </tr>
  {/foreach}
  <tr>
    <TD align=center valign=top width=120 height=50>
    </TD>
    <TD ALIGN=LEFT valign=top style='border-bottom: 1px dotted lightgray;'>
    <table cellpadding=5 cellspacing=0 width=100%>
      <tr>
        <td colspan=4>
        </td>
      </tr>
      <tr>
        <td align=right valign=bottom style='font-family:tahoma; font-size:16pt; font-weight:normal;'>
          Итого:
        </td>
        <td valign=bottom align=right>
            <span class=price>{$Price*$Currency->rate|string_format:"%.2f"} {$Currency->sign}</span>
        </td>
        <td colspan=2 valign=bottom align=right width=250>
           <input type=submit value='Пересчитать'>
        </td>
      </tr>
    </table>
    </TD>
    </tr>
</TABLE>
</FORM>

<form name=order method=post style="width:600px">

<TABLE width='600px' BORDER='0' style='position: relative; left:20;'>
  <TR>
    <TD colspan=2>
      <h1>Заказ</h1>
    </TD>
  </TR>
  <TR>
    <TD colspan=2>
     <b style="color:Red">{$Error}</b>
     {*if !$User}<div align="center"><a href='register' style="color:#6C2B33; text-decoration: underline;"><b><big>Регистрация</big></b></a></div><br /><br />{/if*}
    </TD>
  </TR>
</TABLE>
<FIELDSET style='position: relative; left:20;'>
<LEGEND><b>Личные данные</b></LEGEND>
<TABLE width='600px' BORDER='0' {*if !$User}onclick="alert('Для оформления заказа необходимо Авторизоваться или Зарегистрироваться!'); return false;"{/if*}>
  <TR>
      <TD width="300px">
        Ваше имя:
      </TD>
      <TD>
        <INPUT TYPE=text name=name size=50 {literal}pattern='^.{3,255}$'  notice='Введите свое имя (мин 3)'{/literal} value='{if $Name}{$Name|escape}{else}{$User->name}{/if}' style="width:350px">
      </TD>
  </TR>
  <TR>
      <TD>
        Электронная почта:
      </TD>
      <TD>
        <INPUT TYPE=text name=mail size=50 {literal}pattern='^[0-9a-zA-Z\-\_\.]+\@[0-9a-zA-Z\-\_]+\.[0-9a-zA-z]+$' notice='Введите E-mail (xxx@xxx.xxx)'{/literal} value='{if $Mail}{$Mail|escape}{else}{$User->mail}{/if}' style="width:350px">
      </TD>
  </TR>
  <TR>
      <TD>
        Контактный телефон:
      </TD>
      <TD>
        <INPUT TYPE=text name=phone size=50 {literal}pattern='^.{7,255}$'  notice='Введите контактный телефон (мин 7)'{/literal} value='{$Phone|escape}' style="width:350px">
      </TD>
  </TR>
  <TR>
      <TD>
        Город:
      </TD>
      <TD>
        <INPUT TYPE=text name=city size=50 value='{$City|escape}' style="width:350px" {literal}pattern='^.{3,255}$'  notice='Введите информацию (мин 3)'{/literal}>
      </TD>
   </TR>
  {*<TR>
      <TD>
        Ближайшая станция метро:
      </TD>
      <TD>
<script type="text/javascript">

</script>
        <INPUT TYPE=text name=metro size=50 {literal}pattern='^.{2,255}$'  notice='Введите информацию (мин 2)'{/literal} value='{$Metro|escape}' style="width:350px">
      </TD>
  </TR>*}
  <TR>
      <TD>
        Улица:
      </TD>
      <TD>
        <INPUT TYPE=text name=address size=50 {literal}pattern='^.{7,255}$'  notice='Введите адрес доставки (мин 7)'{/literal} value='{$Address|escape}' style="width:350px">
      </TD>
  </TR>
  <TR>
      <TD>
        Дополнительная информация:
      </TD>
      <TD>
        <INPUT TYPE=text name=comment size=50 value='{$Comment|escape}' style="width:350px">
      </TD>
   </TR>
  {*<TR>
      <TD>
        Как Вы узнали о компании "Ягуар":
      </TD>
      <TD>
<select size="1" name="know" style="width:355px">
{html_options values=$Knows output=$Knows selected=$Know}
</select>
      </TD>
   </TR>
  <TR>
      <TD>
Выставочный зал:
      </TD>
      <TD>
<select size="1" name="place" style="width:355px">
{html_options values=$Places output=$Places selected=$Place}
</select>
      </TD>
   </TR>
  <TR>
      <TD colspan="2">
      <hr>
Если Вы были в одном из наших выставочных залов, пожалуйста, выберите его из списка. Наши консультанты будут Вам очень признательны. Спасибо.
      <hr>
      </TD>
   </TR>
  <TR>
      <TD>
        Желаю вызвать консультанта по вопросу:
      </TD>
      <TD>
        <input name="consult" type="radio" value="Установка стальных дверей" checked> Установка стальных дверей<br />
        <input name="consult" type="radio" value="Установка межкомнатных дверей"> Установка межкомнатных дверей<br />
      </TD>
   </TR>
  <TR>
      <TD colspan="2">
      <hr>
Если Вы желаете заказать сразу несколько разных категорий, отметьте это в примечаниях
      <hr>
      </TD>
   </TR>*}

  <TR>
      <TD>
        Метод оплаты:
      </TD>
      <TD>
    <select size="1" name="pay" style="width:355px" onchange="document.order.submit();">
    {html_options options=$Pays selected=$Pay}
	</select>
      </TD>
  </TR>
  {if $Pay!=4}
  <TR>
      <TD>
        Метод доставки:
      </TD>
      <TD>
    <select size="1" name="del" style="width:355px" onchange="document.order.submit();">
    {html_options options=$Delivery selected=$Del}
	</select>
      </TD>
  </TR>
  <TR>
      <TD>
        Укажите ТК:
      </TD>
      <TD>
      <INPUT TYPE=text name=tk size=50 value='{$Tk|escape}' style="width:350px">
      </TD>
  </TR>
  {/if}
  {if $Pay==1}
  <TR>
      <TD>
        Реквизиты плательщика:
      </TD>
      <TD>
	{*<INPUT TYPE=text name=inn_payer size=50 value='{$Inn_payer|escape}' style="width:350px">*}
	<textarea name="inn_payer" rows=10 wrap="off" style="width:350px">{$Inn_payer|escape}</textarea>
      </TD>
   </TR>
  {*<TR>
      <TD>
        Наименование плательщика:
      </TD>
      <TD>
	<INPUT TYPE=text name=name_payer size=50 value='{$Name_payer|escape}' style="width:350px">
      </TD>
   </TR>
  <TR>
      <TD>
        Юридический адрес плательщика:
      </TD>
      <TD>
	<INPUT TYPE=text name=ur_adress size=50 value='{$Ur_adress|escape}' style="width:350px">
      </TD>
   </TR>*}
  {/if}
  {if $Pay==2}
  <TR>
      <TD>
        Валюта:
      </TD>
      <TD>
    <select size="1" name="currency">
    {foreach item=item key=key from=$Selects}
    <option value="{$key}" {if $key==$Currency->code}selected{/if}>{$item} - {$Price*$Rates.$key|string_format:"%.2f"} {$Signs.$key}</option>
    {/foreach}
	</select>
      </TD>
   </TR>
  {/if}
</TABLE>
</FIELDSET>
{*<FIELDSET>
<LEGEND><b>Информация для оформления счета<sup>*</sup></b></LEGEND>
<TABLE width='600px' BORDER='0' style='position: relative; left:147;' {if !$User}onclick="alert('Для оформления заказа необходимо Авторизоваться или Зарегистрироваться!'); return false;"{/if}>
   <TR>
      <TD colspan="2" align="left" style="border-bottom:1px solid black;">
       <b>Паспорт</b>
      </TD>
   </TR>
  <TR>
      <TD>
        Серия:
      </TD>
      <TD>
        <INPUT TYPE=text name=series size=50 {literal}pattern='^.{4}$'  notice='Введите информацию (4 символа)'{/literal} value='{$Series|escape}' style="width:350px">
      </TD>
  </TR>
  <TR>
      <TD>
        Номер:
      </TD>
      <TD>
        <INPUT TYPE=text name=number size=50 {literal}pattern='^.{6}$'  notice='Введите информацию (6 символов)'{/literal} value='{$Number|escape}' style="width:350px">
      </TD>
  </TR>
  <TR>
      <TD>
        Когда и кем выдан:
      </TD>
      <TD>
        <textarea name="given" rows=5 style="width:86%;overflow-y:auto;width:350px">{$Given|escape}</textarea>
      </TD>
  </TR>
  <TR>
      <TD width="250px">
        Адрес прописки/регистрации:
      </TD>
      <TD>
        <textarea name="registration" rows=5 style="width:86%;overflow-y:auto;width:350px">{$Registration|escape}</textarea>
      </TD>
  </TR>
   <TR>
      <TD colspan="2" align="left" style="border-bottom:1px solid black;">
       <b>О себе</b>
      </TD>
   </TR>
  <TR>
      <TD>
        Фамилия:
      </TD>
      <TD>
        <INPUT TYPE=text name=lastname size=50 value='{$Lastname|escape}' style="width:350px" {literal}pattern='^.{3,255}$'  notice='Введите информацию (мин 3)'{/literal}>
      </TD>
   </TR>
  <TR>
      <TD>
        Имя:
      </TD>
      <TD>
        <INPUT TYPE=text name=firstname size=50 value='{$Firstname|escape}' style="width:350px" {literal}pattern='^.{3,255}$'  notice='Введите информацию (мин 3)'{/literal}>
      </TD>
   </TR>
  <TR>
      <TD>
        Отчество:
      </TD>
      <TD>
        <INPUT TYPE=text name=middle size=50 value='{$Middle|escape}' style="width:350px" {literal}pattern='^.{3,255}$'  notice='Введите информацию (мин 3)'{/literal}>
      </TD>
   </TR>
  <TR>
      <TD>
        Город:
      </TD>
      <TD>
        <INPUT TYPE=text name=city size=50 value='{$City|escape}' style="width:350px" {literal}pattern='^.{3,255}$'  notice='Введите информацию (мин 3)'{/literal}>
      </TD>
   </TR>
  <TR>
      <TD>
        Индекс:
      </TD>
      <TD>
        <INPUT TYPE=text name=index size=50 value='{$Index|escape}' style="width:350px" {literal}pattern='^.{6}$' notice='Введите информацию (6 символов)'{/literal}>
      </TD>
   </TR>
   <TR>
      <TD colspan="2" align="left">
       <sup>*</sup> - эти данные необходимы для выставления счета и соблюдения юридических формальностей
      </TD>
   </TR>
</TABLE>
</FIELDSET>*}
<TABLE width='600px' BORDER='0' style='position: relative; left:147;'>
   <TR>
      <TD colspan="2" align="center">
       <INPUT TYPE=SUBMIT NAME=SUBMIT VALUE='Оформить заказ' {if !$Products}onclick="alert('Добавьте товары в Корзину'); return false;"{/if}
        {*if !$User}onclick="alert('Для оформления заказа необходимо Авторизоваться или Зарегистрироваться!'); return false;"{/if*}>
      </TD>
   </TR>
</TABLE>


</FORM>
