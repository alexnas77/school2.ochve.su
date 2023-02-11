    {literal}<script type="text/javascript">
    $(document).ready(function() {
        $("a.photos").fancybox({
            'hideOnContentClick': true,
            'overlayShow':		  true,
            'overlayOpacity':	  0.8,
            'zoomSpeedIn'	:	  500,
            'zoomSpeedOut'	:	  500,
            'easingIn'		:	  'swing',
            'easingOut'		:	  'swing'
        });
    });
    </script>{/literal}
<br />
<div style="position:relative;z-index:5;">
<nobr><a href="http://{php} print $_SERVER['HTTP_HOST']; $dir=dirname($_SERVER["PHP_SELF"]); if($dir!='/' && $dir!='\\') print $dir; {/php}/" class="brand">Главная</a>{if $Category->parent} / <a href="catalog/{$Parent_category->category_id}" class="brand">{$Parent_category->name}</a>{/if} / <a href="catalog/{$Category->category_id}" class="brand">{$Category->name}</a> / <a href="index.php?section=4&category={$Category->category_id}&brand={$Product->urlbrand}" class="brand">{$Category->name} {$Product->brand}</a> /<br /><br /><a href="catalog/{$Category->category_id}/{$Product->urlbrand}/{$Product->product_id}.html" class="brand">{$Category->name} {$Product->brand} {$Product->model}</a></nobr>
</div>
<TABLE WIDTH=100% BORDER=0 CELLPADDING=0>
  <TR>
    <TD ALIGN=center valign=center width=320 height=120 style='height:120; border: 1px solid lightgray; padding:10'>
        {foreach name=fotos item=foto from=$Fotos}
            {if $smarty.foreach.fotos.first && $foto->filename}
                <A rel="group" TITLE='Фото {$foto->foto_id+1}{if $CountFotos>1} из {$CountFotos}{/if}' CLASS=photos href='image.php?src=../{$foto->filename}&w=100%' style="display: table;"><IMG TITLE='{$Category->name} : {$Product->brand} : {$Product->model}' width="{$foto->width}px" height="{$foto->height}px" ALT='{$Category->name} : {$Product->brand} : {$Product->model}' border=0 src='{if $foto->filename}image.php?src=../{$foto->filename}&w={$foto->width}&h={$foto->height}{else}images2/no_foto.gif{/if}'></A>
            {elseif $foto->filename}
                <A rel="group" TITLE='Фото {$foto->foto_id+1}{if $CountFotos>1} из {$CountFotos}{/if}' href='image.php?src=../{$foto->filename}&w=100%' CLASS=photos style="position:relative;display: table;"><IMG ALT='{$Category->name} : {$Product->brand} : {$Product->model}' border=0 src='images2/blank.gif'></A>
            {/if}
            {foreachelse}
            <IMG TITLE='{$Category->name} : {$Product->brand} : {$Product->model}' width="100px" ALT='{$Category->name} : {$Product->brand} : {$Product->model}' border=0 src='images2/no_foto.gif'>
        {/foreach}
    </TD>
    <TD ALIGN=LEFT valign=top>
    <table cellpadding=5 cellspacing=0 width=100%>
      <tr>
        <td>
          <H1>
          {$Category->name} {$Subcategory->name} {$Product->brand} {$Product->model|escape}
          {if $Product->point}{section name=p loop=5}{if $Product->point>$smarty.section.p.iteration-0.5}<img src='images2/star.gif'>{else}<img src='images2/star_null.gif'>{/if}{/section}{/if}
          </H1>
        </td>
      </tr>
      <tr>
        <td class=price>
         {if $Product->quantity>0}
         <nobr><span class=price>{*$Product->discount_price*$Currency->rate/$Product->currency_rate|string_format:"%.2f"*}
         {$Product->discount_price|string_format:"%.2f"}
         {$Currency->sign}</span></nobr>
         {else}
           Нет в наличии
         {/if}
        </td>
      </tr>
      <tr>
        <td>
        {if $Product->quantity>0}
          <a href='index.php?section=7&product_id={$Product->product_id}' class=order_link><span>В корзину</span></a>
        {/if}
        </td>
      </tr>

    </table>
    </TD>
    </tr>
    <tr>
      <td   valign=top align=left>
        <h1>Отзывы о {$Category->name} {$Subcategory->name} {$Product->brand|escape} {$Product->model|escape}</h1>
        <strong>Написать отзыв:</strong><br>
        <table cellspacing=5 bgcolor='#F0F0F0' width=100%>
          <form method=post>
          <tr>
            <td colspan="2" align="center">
              <b style="color:Red">{$Message}</b>
            </td>
          </tr>
          <tr>
            <td>
              Имя:
            </td>
            <td align=right>
              <input name=name type=text size=40 value='{$User->name}'>
            </td>
          </tr>
          <tr>
            <td colspan=2>
              <textarea style='width:100%' rows=3 name=comment></textarea>
            </td>
          </tr>
          <tr>
            <td>
              Оценка:
            </td>
            <td align=right>
              <input type=radio name=point value=1>1
              <input type=radio name=point value=2>2
              <input type=radio name=point value=3 checked>3
              <input type=radio name=point value=4>4
              <input type=radio name=point value=5>5
            </td>
          </tr>
        {if !$User}
        <tr>
          <td>
            &nbsp;
          </td>
          <td colspan="2">
           <img src="http://{php} print $_SERVER['HTTP_HOST']; $dir=dirname($_SERVER["PHP_SELF"]); if($dir!='/' && $dir!='\\') print $dir; {/php}/protect/index.php?{php}print session_name()."=".session_id();{/php}">
          </td>
        </tr>
        <tr>
          <td>
            Введите код
          </td>
          <td>
           <input type="text" name="keystring" value="" size=40 {literal}pattern='^.{3,255}$' notice='Введите код '{/literal}>
          </td>
        </tr>
        {/if}
          <tr>
            <td colspan=2 align=right>
              <input type=hidden name=s value='{$Secret}'>
              <input type=submit value='Отправить'>
            </td>
          </tr>
          </form>
        </table>
        {if !$Comments}
          Отзывов нет
        {else}
        <table width=100% cellspacing=5>
        {foreach from=$Comments item=comment}
          <tr>
            <td style='font-size:10px;'>
              {$comment->date} <b>{$comment->name}</b>
            </td>
            <td>
              {if $comment->point}
                {section name=p loop=5}{if $comment->point>$smarty.section.p.iteration-0.5}<img src='images2/star.gif'>{else}<img src='images2/star_null.gif'>{/if}{/section}
              {/if}
            </td>
          </tr>
          <tr>
            <td colspan=2 style='border-bottom: dashed 1px gray'>
              {$comment->comment|escape}&nbsp;
            </td>
          </tr>
         {/foreach}
         </table>
         {/if}
      </td>
      <td valign=top align=left style='border-left: 1px dotted lightgray;'>
      <table >
  <tr>
  <td valign=top align=left style='border-left: 1px dotted lightgray;'>
         {$Product->body}
      </td>
  </tr>
  {assign var="group" value=0}
     {foreach name=properties item=property key=key from=$Product->properties_label}
  {if $group neq $Product->properties_group.$key}
  {assign var="group" value=$Product->properties_group.$key}
  <TR>
  <TD class=list colspan="2">
  <div align="center">
    <b>{$group_name.$group}</b>
    </div>
    </TD>
  </TR>
   {/if}
       <tr style='background-color:white; border: 1px dotted lightgray;'>
        <td >
          {$Product->properties_label.$key}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </td>
         <td >
          {$Product->properties_value.$key}
        </td>
       </tr>
      {/foreach}
     </table>
      </td>
    </tr>
</table>
      {if $Analogs}
         <h3 style="padding-bottom:10px;">Продукция, аналогичная по цене<br /><br />"{$Category->name} : {$Product->brand} : {$Product->model}"</h3>
         <ul style="list-style:none;">
         {foreach name=analogs item=Analog from=$Analogs}
          <li style="margin: 5px 0px 5px 10px; font-size:12px;">
          <a class="product_name" href="catalog/{$Analog->category_id}/{$Analog->urlbrand}/{$Analog->product_id}.html"><img src="{if $Analog->filename}{*foto/storefront/{$Analog->filename}*}image.php?src=storefront/{$Analog->filename}&w=50{else}images2/no_foto.gif{/if}" width="50" alt="{$Analog->filename}" style="border:1px solid Black; margin:0px 10px;">{$Analog->name} : {$Analog->brand} : {$Analog->model}   -   {$Analog->discount_price|string_format:"%.2f"}&nbsp;{$Currency->sign}</a></li>
         {/foreach}
         </ul>
     {/if}
<br />
<div style="position:relative;z-index:5;">
<nobr><a href="http://{php} print $_SERVER['HTTP_HOST']; $dir=dirname($_SERVER["PHP_SELF"]); if($dir!='/' && $dir!='\\') print $dir; {/php}/" class="brand">Главная</a>{if $Category->parent} / <a href="catalog/{$Parent_category->category_id}" class="brand">{$Parent_category->name}</a>{/if} / <a href="catalog/{$Category->category_id}" class="brand">{$Category->name}</a> / <a href="index.php?section=4&category={$Category->category_id}&brand={$Product->urlbrand}" class="brand">{$Category->name} {$Product->brand}</a> /<br /><br /><a href="catalog/{$Category->category_id}/{$Product->urlbrand}/{$Product->product_id}.html" class="brand">{$Category->name} {$Product->brand} {$Product->model}</a></nobr>
</div><br /><br />