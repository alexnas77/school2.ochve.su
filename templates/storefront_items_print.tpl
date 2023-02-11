{if isset($smarty.get.exel) && $smarty.get.exel==1}
    {literal}<style type="text/css">
        span.price{
        color: green;
        font-size: 14px;
        font-weight: normal;
        }
        span.price-warning{
        color: #fc0000;
        font-size: 14px;
        font-weight: bolder;
        }
        .products
        {
           border-collapse: collapse;
           border: 0.5pt solid black;
        }
        .products tr td
        {
        border: 0.5pt solid black;
        }
    {/literal}</style>
{php}
    error_reporting(0);
    //$ctype="text/html; charset=Windows-1251";
    $ctype="application/vnd.ms-excel";
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Type: $ctype");
    //header('Content-disposition: attachment; filename="bill.html"');
    header('Content-disposition: attachment; filename="bill.xls"');
    header("Content-Transfer-Encoding: binary");
{/php}
{else}
<div align="left" style="font-size:16px;background-color: #919191;color: White;padding: 10px 20px;"><b>{$Category->name} {$Brand}</b>
    <form name="active_change" action="{$smarty.server.REQUEST_URI}" style="display: inline-block;margin-left: 20px;">
        <select name="active">
            <option value="1"{if strpos($smarty.server.REQUEST_URI,'/1') !== false} selected{/if}>Только активные ученики</option>
            <option value="-1"{if strpos($smarty.server.REQUEST_URI,'/-1') !== false} selected{/if}>Только отключенные ученики</option>
        </select>
    </form>
</div>
{literal}
    <script type="text/javascript">
        (function() {
            $('form[name="active_change"] select[name="active"]').on('change', function() {
                var url = window.location.pathname.replace(/^(\/[^\/]+\/[^\/]+\/[^\/]+)\/[^\/]+/i,'$1') + (parseInt($('select[name="active"]').val()) !== 1 ? '/' +  $('select[name="active"]').val() : '');
                console.log(url);
                window.location.href = url;
            });
        })();
    </script>
{/literal}
<br />
{if $PagesNum>1}
{assign var=i value=0}
<TABLE CELLPADDING=0 CELLSPACING=0>
  <TR>
    <TD ALIGN=LEFT>
      Страницы:
    </TD>
    {section name=pages loop=$PagesNum}
      {if $smarty.section.pages.index!=$CurrentPage}
      {assign var=i value=$smarty.section.pages.index}
      <TD width=20 ALIGN=CENTER>
        <A style='color:black;' HREF='{$Url.$i}'>{$smarty.section.pages.index+1}</A>
      </TD>
      {else}
      <TD width=20 ALIGN=CENTER BGCOLOR=#E0E0E0>
        {$smarty.section.pages.index+1}
      </TD>
      {/if}
    {/section}
  </TR>
</TABLE>
{/if}
    <br />
    <nobr><a href="http://{php} print $_SERVER['HTTP_HOST']; $dir=dirname($_SERVER["PHP_SELF"]); if($dir!='/' && $dir!='\\') print $dir; {/php}/" class="brand">На Главную страницу</a></nobr>
    <br /><br />
<a href="pcatalog/{$Category->category_id}/{$PrevDate|escape}">Предыдущий</a>
<a href="pcatalog/{$Category->category_id}/{$smarty.now|date_format:"%d.%m.%Y"}">Сегодня</a>
<a href="pcatalog/{$Category->category_id}/{$NextDate|escape}">Следующий</a><br /><br />
    {if $smarty.session.user_login}<a href="catalog/{$Category->category_id}/{$Date|escape}" style="position: relative; top: -32px; left: 350px;">Редактировать {$Date|escape}</a>{else}<a href="/login" style="position: relative; top: -32px; left: 350px;">Авторизоваться для редактирования</a>{/if}
    <a target="_blank" href="ecatalog/{$Category->category_id}/{$Date|escape}" style="position: relative; top: -32px; left: 400px;">Скачать в формате Exel  {$Date|escape}</a>
{/if}
    <TABLE class="products" cellpadding=5 cellspacing=0 width=100%>
        {assign var="before" value=0}
        {assign var="after" value=0}
        {assign var="breakfast_free" value=0}
        {assign var="breakfast" value=0}
        {assign var="lunch" value=0}
        {assign var="lunch2" value=0}
        {assign var="dinner" value=0}
        {assign var="lunch_m" value=0}
        {assign var="dinner_m" value=0}
        {assign var="nbreakfast_free" value=0}
        {assign var="nbreakfast" value=0}
        {assign var="nlunch" value=0}
        {assign var="nlunch2" value=0}
        {assign var="ndinner" value=0}
        {assign var="nlunch_m" value=0}
        {assign var="ndinner_m" value=0}
        {assign var="cash" value=0}
        {assign var="card" value=0}
        {if intval($Category->name) > 4}
        {assign var="colspan" value=8}
        {assign var="colspan2" value=6}
        {else}
        {assign var="colspan" value=9}
        {assign var="colspan2" value=7}
        {/if}
        {if $Timestamp < strtotime('2021-09-01 00:00:00')}
        {assign var="colspan" value=$colspan+1}
        {assign var="colspan2" value=$colspan2+1}        
        {/if}
        {foreach name=products key=key item=product from=$Products}
            {if $smarty.foreach.products.first}
                <tr class="first">
                    <TD rowspan="3"><strong>Фамилия</strong></td>
                    <td rowspan="3">
                        Задолж (+)<br />Переплата (-)<br />на {$Date|escape}
                    </td>
                    <td colspan="{$colspan}">{$Date|escape}</td>
                    <td rowspan="3">
                        Задолж (+)<br />Переплата (-)<br />на {$NextDate|escape}<br />
                        <label><input type="checkbox" id="toggle" value="1">&nbsp;Должники</label>
                        {literal}<script type="text/javascript">
                            $('input#toggle').on('change', function(){
                                if($(this).prop('checked')) {
                                $('tr.other').find('td:last-of-type').each(function () {
                                   if (parseInt($(this).text()) < 500) {
                                      $(this).parent().hide();
                                   }
                                });
                                } else {
                                $('tr.other').find('td:last-of-type').each(function () {
                                   if (parseInt($(this).text()) < 500) {
                                      $(this).parent().show();
                                   }
                                });
                                }
                            });  
                        </script>{/literal} 
                    </td>
                    </TD>
                </tr>
                <tr class="first">
                    <td colspan="{$colspan2}">
                        Реализовано
                    </td>
                    <td colspan="2">
                        Оплачено
                    </td>
                </tr>
                <tr class="first">
                    {if intval($Category->name) <= 4}<td>
                        Завтрак бесплатный<br /><br />
                        {$Breakfast_free} {$Currency->sign}
                    </td>{/if}
                    <td>
                        Завтрак<br /><br />
                        {$Breakfast} {$Currency->sign}
                    </td>
                    <td>
                        Обед<br /><br />
                        {$Lunch} {$Currency->sign}
                    </td>
                    {if $Timestamp < strtotime('2021-09-01 00:00:00')}<td>
                        Обед 2<br /><br />
                        {$Lunch3} {$Currency->sign}
                    </td>{/if}
                    <td>
                        Завтрак льготный<br /><br />
                        {$Lunch2} {$Currency->sign}
                    </td>
                    <td>
                        Завтрак М<br /><br />
                        {$Lunch_m} {$Currency->sign}
                    </td>
                    <td>
                        Обед М<br /><br />
                        {$Dinner_m} {$Currency->sign}
                    </td>
                    <td>
                        Полдник<br /><br />
                        {$Dinner} {$Currency->sign}
                    </td>
                    <td>
                        Нал
                    </td>
                    <td>
                        Безнал
                    </td>
                </tr>
            {/if}
            <tr class="other">
                <TD align=left valign=top style="width:12%; padding-left: 20px">
                    {$smarty.foreach.products.iteration}. <span {*TITLE='{$product->model|escape} {$product->brand|escape} {$product->model|escape}' HREF='catalog/{$product->category_id}/{$product->product_id}.html'*} CLASS=subheader style="white-space: nowrap">{$product->model|escape}</span>
                </td>
                <td class=price style="width:10%;">
                    <nobr><span class={if $Price.$key>0}price-warning{else}price{/if}>{$Price.$key|string_format:"%.2f"} {$Currency->sign}</span></nobr>{assign var="before" value=$before+$Price.$key}
                </td>
                {if intval($Category->name) <= 4}<td>
                    {if $product->breakfast_free==0} нет{elseif $product->breakfast_free==1} да{assign var="breakfast_free" value=$breakfast_free+$Breakfast_free}{assign var="nbreakfast_free" value=$nbreakfast_free+1}{/if}
                </td>{/if}
                <td>
                    {if $product->breakfast==0} нет{elseif $product->breakfast==1} да{assign var="breakfast" value=$breakfast+$Breakfast}{assign var="nbreakfast" value=$nbreakfast+1}{/if}
                </td>
                <td>
                    {if $product->lunch==0} нет{elseif $product->lunch==1} да{assign var="lunch" value=$lunch+$Lunch}{assign var="nlunch" value=$nlunch+1}{/if}
                </td>
                {if $Timestamp < strtotime('2021-09-01 00:00:00')}<td>
                    {if $product->lunch3==0} нет{elseif $product->lunch3==1} да{assign var="lunch3" value=$lunch3+$Lunch3}{assign var="nlunch3" value=$nlunch3+1}{/if}
                </td>{/if}                
                <td>
                    {if $product->lunch2==0} нет{elseif $product->lunch2==1} да{assign var="lunch2" value=$lunch2+$Lunch2}{assign var="nlunch2" value=$nlunch2+1}{/if}
                </td>
                <td>
                    {if $product->lunch_m==0} нет{elseif $product->lunch_m==1} да{assign var="lunch_m" value=$lunch_m+$Lunch_m}{assign var="nlunch_m" value=$nlunch_m+1}{/if}
                </td>
                <td>
                    {if $product->dinner_m==0} нет{elseif $product->dinner_m==1} да{assign var="dinner_m" value=$dinner_m+$Dinner_m}{assign var="ndinner_m" value=$ndinner_m+1}{/if}
                </td>
                <td>
                    {if $product->dinner==0} нет{elseif $product->dinner==1} 1 единица{assign var="dinner" value=$dinner+$Dinner}{assign var="ndinner" value=$ndinner+1}{elseif $product->dinner==2} 2 единицы{assign var="dinner" value=$dinner+$Dinner*2}{assign var="ndinner" value=$ndinner+2}{/if}
                </td>
                <td>
                    {if $product->cash}{$product->cash}{assign var="cash" value=$cash+$product->cash}{else}0{/if}
                </td>
                <td>
                    {if $product->card}{$product->card}{assign var="card" value=$card+$product->card}{else}0{/if}
                </td>
                <td class=price style="width:10%;">
                    <nobr><span class={if $Price.$key+$product->delta>0}price-warning{else}price{/if}>{$Price.$key+$product->delta|string_format:"%.2f"} {$Currency->sign}</span></nobr>{assign var="after" value=$after+$Price.$key+$product->delta}
                </td>
                </TD>
            </tr>
            {if $smarty.foreach.products.last}
                <tr class="first">
                    <TD><strong>Итого количество</strong></td>
                    <td>

                    </td>
                    {if intval($Category->name) <= 4}<td>
                        {$nbreakfast_free}
                    </td>{/if}
                    <td>
                        {$nbreakfast}
                    </td>
                    <td>
                        {$nlunch}
                    </td>
                    {if $Timestamp < strtotime('2021-09-01 00:00:00')}<td>
                        {$nlunch3}
                    </td>{/if}                    
                    <td>
                        {$nlunch2}
                    </td>
                    <td>
                        {$nlunch_m}
                    </td>
                    <td>
                        {$ndinner_m}
                    </td>
                    <td>
                        {$ndinner}
                    </td>
                    <td>

                    </td>
                    <td>

                    </td>
                    <td>

                    </TD>
                </tr>
                <tr class="first">
                    <TD><strong>Итого сумма, руб</strong></td>
                    <td>
                        {$before}
                    </td>
                    {if intval($Category->name) <= 4}<td>
                        {$breakfast_free}
                    </td>{/if}
                    <td>
                        {$breakfast}
                    </td>
                    <td>
                        {$lunch}
                    </td>
                    {if $Timestamp < strtotime('2021-09-01 00:00:00')}<td>
                        {$lunch3}
                    </td>{/if}                    
                    <td>
                        {$lunch2}
                    </td>
                    <td>
                        {$lunch_m}
                    </td>
                    <td>
                        {$dinner_m}
                    </td>
                    <td>
                        {$dinner}
                    </td>
                    <td>
                        -{$cash}
                    </td>
                    <td>
                        -{$card}
                    </td>
                    <td>
                        {$after}
                    </TD>
                </tr>
                <tr class="first">
                    <TD><strong>Итого за день, руб</strong></td>
                    <td>
                        {$before}
                    </td>
                    <td colspan="{$colspan2}">
{if $Timestamp < strtotime('2021-09-01 00:00:00')}                        
{$breakfast_free+$breakfast+$lunch+$lunch2+$lunch3+$dinner+$lunch_m+$dinner_m}
{else}           
{$breakfast_free+$breakfast+$lunch+$lunch2+$dinner+$lunch_m+$dinner_m}
{/if}
                    </td>
                    <td colspan="2">
-{$cash+$card}
                    </td>
                    <td>
                        {$after}
                    </TD>
                </tr>
            {/if}
        {/foreach}
    </TABLE>
{if isset($smarty.get.exel) && $smarty.get.exel==1}
    {php}
        exit();
    {/php}
{else}
<br />
<input type="submit" name="submit" value="Печать" style="margin: 10px 10px" onclick="window.print(); return false;">
<br />
{if $PagesNum>1}
{assign var=i value=0}
<TABLE CELLPADDING=0 CELLSPACING=0>
  <TR>
    <TD ALIGN=LEFT>
      Страницы:
    </TD>
    {section name=pages loop=$PagesNum}
      {if $smarty.section.pages.index!=$CurrentPage}
      {assign var=i value=$smarty.section.pages.index}
      <TD width=20 ALIGN=CENTER>
        <A style='color:black;' HREF='{$Url.$i}'>{$smarty.section.pages.index+1}</A>
      </TD>
      {else}
      <TD width=20 ALIGN=CENTER BGCOLOR=#E0E0E0>
        {$smarty.section.pages.index+1}
      </TD>
      {/if}
    {/section}
  </TR>
</TABLE>
{/if}
{/if}