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
<nobr><a href="http://{php} print $_SERVER['HTTP_HOST']; $dir=dirname($_SERVER["PHP_SELF"]); if($dir!='/' && $dir!='\\') print $dir; {/php}/" class="brand">Главная</a>{if $Category->parent} / <a href="catalog/{$Parent_category->category_id}" class="brand">{$Parent_category->name}</a>{/if} / <a href="catalog/{$Category->category_id}" class="brand">{$Category->name}</a>{if $Brand} / <a href="index.php?section=4&category={$Category->category_id}&brand={$URLBrand}" class="brand">{$Category->name} {$Brand}</a>{/if}</nobr>
<br /><br />
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
<a href="catalog/{$Category->category_id}/{$PrevDate|escape}">Предыдущий</a>
<a href="catalog/{$Category->category_id}/{$smarty.now|date_format:"%d.%m.%Y"}">Сегодня</a>
<a href="catalog/{$Category->category_id}/{$NextDate|escape}">Следующий</a><br /><br />
{if $smarty.session.user_login}<a href="pcatalog/{$Category->category_id}/{$Date|escape}" style="position: relative; top: -32px; left: 350px;">Версия для печати {$Date|escape}</a>{else}<a href="/login" style="position: relative; top: -32px; left: 350px;">Авторизоваться для редактирования</a>{/if}
<a target="_blank" href="ecatalog/{$Category->category_id}/{$Date|escape}" style="position: relative; top: -32px; left: 400px;">Скачать в формате Exel  {$Date|escape}</a>
{if $smarty.session.user_login}<a href="/logout" style="position: absolute; top: 60px; left: 90%;">Выйти</a>{else}<a href="/login" style="position: absolute; top: 60px; left: 90%;">Войти</a>{/if}
<form name="class" method="post">
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
                        {literal}<script type="text/javascript">
                            function every(name){
                            $('input[name^="'+name+'["]:even').each(function(){this.checked=false;}); 
                            $('input[name^="'+name+'["]:odd').each(function(){this.checked=true;});
                        }
                             function none(name){
                            $('input[name^="'+name+'["]:even').each(function(){this.checked=true;}); 
                            $('input[name^="'+name+'["]:odd').each(function(){this.checked=false;});
                        }   
                          
                        </script>{/literal}
                <tr class="first">
                    {if intval($Category->name) <= 4}<td>
                        Завтрак бесплатный<br />
                        <input type="text" name="breakfast_free" size="20" value="{$Breakfast_free}">
                        <a href="javascript:void(0);" onclick="every('abreakfast_free'); return false;">Все</a>
                        <a href="javascript:void(0);" onclick="none('abreakfast_free'); return false;">Никто</a>
                    </td>{/if}
                    <td>
                        Завтрак<br />
                        <input type="text" name="breakfast" size="20" value="{$Breakfast}">
                        <a href="javascript:void(0);" onclick="every('abreakfast'); return false;">Все</a>
                        <a href="javascript:void(0);" onclick="none('abreakfast'); return false;">Никто</a>
                    </td>
                    <td>
                        Обед<br />
                        <input type="text" name="lunch" size="20" value="{$Lunch}">
                        <a href="javascript:void(0);" onclick="every('alunch'); return false;">Все</a>
                        <a href="javascript:void(0);" onclick="none('alunch'); return false;">Никто</a>
                    </td>
                    {if $Timestamp < strtotime('2021-09-01 00:00:00')}<td>
                        Обед 2<br />
                        <input type="text" name="lunch3" size="20" value="{$Lunch3}">
                        <a href="javascript:void(0);" onclick="every('alunch3'); return false;">Все</a>
                        <a href="javascript:void(0);" onclick="none('alunch3'); return false;">Никто</a>
                    </td>{/if}                    
                    <td>
                        Завтрак льготный<br />
                        <input type="text" name="lunch2" size="20" value="{$Lunch2}">
                        <a href="javascript:void(0);" onclick="every('alunch2'); return false;">Все</a>
                        <a href="javascript:void(0);" onclick="none('alunch2'); return false;">Никто</a>
                    </td>
                    <td>
                        Завтрак М<br />
                        <input type="text" name="lunch_m" size="20" value="{$Lunch_m}">
                        <a href="javascript:void(0);" onclick="every('alunch_m'); return false;">Все</a>
                        <a href="javascript:void(0);" onclick="none('alunch_m'); return false;">Никто</a>
                    </td>
                    <td>
                        Обед М<br />
                        <input type="text" name="dinner_m" size="20" value="{$Dinner_m}">
                        <a href="javascript:void(0);" onclick="every('adinner_m'); return false;">Все</a>
                        <a href="javascript:void(0);" onclick="none('adinner_m'); return false;">Никто</a>
                    </td>
                    <td>
                        Полдник<br />
                        <input type="text" name="dinner" size="20" value="{$Dinner}">
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
                    <input type="radio" name="abreakfast_free[{$product->product_id}]" size="20" value="0"{if $product->breakfast_free==0} checked{/if}> нет<br />
                    <input type="radio" name="abreakfast_free[{$product->product_id}]" size="20" value="1"{if $product->breakfast_free==1} checked{assign var="breakfast_free" value=$breakfast_free+$Breakfast_free}{assign var="nbreakfast_free" value=$nbreakfast_free+1}{/if}> да&nbsp;
                </td>{/if}
                <td>
                    <input type="radio" name="abreakfast[{$product->product_id}]" size="20" value="0"{if $product->breakfast==0} checked{/if}> нет<br />
                    <input type="radio" name="abreakfast[{$product->product_id}]" size="20" value="1"{if $product->breakfast==1} checked{assign var="breakfast" value=$breakfast+$Breakfast}{assign var="nbreakfast" value=$nbreakfast+1}{/if}> да&nbsp;
                </td>
                <td>
                    <input type="radio" name="alunch[{$product->product_id}]" size="20" value="0"{if $product->lunch==0} checked{/if}> нет<br />
                    <input type="radio" name="alunch[{$product->product_id}]" size="20" value="1"{if $product->lunch==1} checked{assign var="lunch" value=$lunch+$Lunch}{assign var="nlunch" value=$nlunch+1}{/if}> да&nbsp;
                </td>
                {if $Timestamp < strtotime('2021-09-01 00:00:00')}<td>
                    <input type="radio" name="alunch3[{$product->product_id}]" size="20" value="0"{if $product->lunch3==0} checked{/if}> нет<br />
                    <input type="radio" name="alunch3[{$product->product_id}]" size="20" value="1"{if $product->lunch3==1} checked{assign var="lunch3" value=$lunch3+$Lunch3}{assign var="nlunch3" value=$nlunch3+1}{/if}> да&nbsp;
                </td>{/if}                
                <td>
                    <input type="radio" name="alunch2[{$product->product_id}]" size="20" value="0"{if $product->lunch2==0} checked{/if}> нет<br />
                    <input type="radio" name="alunch2[{$product->product_id}]" size="20" value="1"{if $product->lunch2==1} checked{assign var="lunch2" value=$lunch2+$Lunch2}{assign var="nlunch2" value=$nlunch2+1}{/if}> да&nbsp;
                </td>
                <td>
                    <input type="radio" name="alunch_m[{$product->product_id}]" size="20" value="0"{if $product->lunch_m==0} checked{/if}> нет<br />
                    <input type="radio" name="alunch_m[{$product->product_id}]" size="20" value="1"{if $product->lunch_m==1} checked{assign var="lunch_m" value=$lunch_m+$Lunch_m}{assign var="nlunch_m" value=$nlunch_m+1}{/if}> да&nbsp;
                </td>
                <td>
                    <input type="radio" name="adinner_m[{$product->product_id}]" size="20" value="0"{if $product->dinner_m==0} checked{/if}> нет<br />
                    <input type="radio" name="adinner_m[{$product->product_id}]" size="20" value="1"{if $product->dinner_m==1} checked{assign var="dinner_m" value=$dinner_m+$Dinner_m}{assign var="ndinner_m" value=$ndinner_m+1}{/if}> да&nbsp;
                </td>
                <td>
                    <input type="radio" name="adinner[{$product->product_id}]" size="20" value="0"{if $product->dinner==0} checked{/if}> нет<br />
                    <input type="radio" name="adinner[{$product->product_id}]" size="20" value="1"{if $product->dinner==1} checked{assign var="dinner" value=$dinner+$Dinner}{assign var="ndinner" value=$ndinner+1}{/if}> 1 единица<br />
                    <input type="radio" name="adinner[{$product->product_id}]" size="20" value="2"{if $product->dinner==2} checked{assign var="dinner" value=$dinner+$Dinner*2}{assign var="ndinner" value=$ndinner+2}{/if}> 2 единицы<br />
                </td>
                <td>
                    <input type="text" name="cash[{$product->product_id}]" size="20" value="{$product->cash}">{assign var="cash" value=$cash+$product->cash}
                </td>
                <td>
                    <input type="text" name="card[{$product->product_id}]" size="20" value="{$product->card}">{assign var="card" value=$card+$product->card}
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
                    {if $Date|@strtotime < strtotime('2021-09-01 00:00:00')}<td>
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
                    {if $Date|@strtotime < strtotime('2021-09-01 00:00:00')}<td>
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
{if $Date|@strtotime < strtotime('2021-09-01 00:00:00')}
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
    <input type="submit" name="submit" value="Сохранить" style="margin: 10px 10px"><input type="reset" value="Сбросить" style="margin: 10px 10px">
</form>
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
<nobr><a href="http://{php} print $_SERVER['HTTP_HOST']; $dir=dirname($_SERVER["PHP_SELF"]); if($dir!='/' && $dir!='\\') print $dir; {/php}/" class="brand">Главная</a>{if $Category->parent} / <a href="catalog/{$Parent_category->category_id}" class="brand">{$Parent_category->name}</a>{/if} / <a href="catalog/{$Category->category_id}" class="brand">{$Category->name}</a>{if $Brand} / <a href="index.php?section=4&category={$Category->category_id}&brand={$URLBrand}" class="brand">{$Category->name} {$Brand}</a>{/if}</nobr>
<br /><br />