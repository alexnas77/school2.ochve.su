{*<div align="left" style="font-size:16px;background-color: #919191;color: White;padding: 10px 20px;"><b>{if $Category->name}{$Category->name|escape} класс{else}Все классы{/if} c {$StartDate|escape} по {$EndDate|escape}</b></div>*}
<div align="left" style="font-size:16px;background-color: #919191;color: White;padding: 10px 20px;">
    <b>{if $Category->name}{$Category->name|escape} класс{else}Все классы{/if} c {$StartDate|escape} по {$EndDate|escape}</b>
    <form name="active_change" action="/index.php" style="display: inline-block;margin-left: 20px;">
        <select name="active">
            <option value="0"{if $smarty.get.active == '0'} selected{/if}>Все ученики</option>
            <option value="1"{if $smarty.get.active == '1'} selected{/if}>Только активные ученики</option>
            <option value="-1"{if $smarty.get.active == '-1'} selected{/if}>Только отключенные ученики</option>
        </select>
    </form>
</div>
{literal}
    <script type="text/javascript">
        (function() {
            var url = new URL(window.location.href);
            url.searchParams.delete('active');
            for(let [name, value] of url.searchParams) {
              //console.log(`${name}=${value}`); // q=test me!, then tbs=qdr:y
              $input = $('<input>').attr({'type' : 'hidden', 'name' : name, 'value' : value});
              $('form').append($input);
            }
            $('form select').on('change', function() {
                $('form').trigger('submit');
            });
        })();
    </script>
{/literal}
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
        {assign var="total" value=0}
                <tr class="first">
                    <TD rowspan="2"><strong>Класс</strong></td>
                    <td rowspan="2">
                        Задолж (+)<br />Переплата (-)<br />на {$StartDate|escape}
                    </td>
                    <td colspan="{if $StartTimeStamp < strtotime('2021-09-01 00:00:00')}8{else}7{/if}">
                        Реализовано
                    </td>
                    <td colspan="2">
                        Оплачено
                    </td>
                    <td rowspan="2">
                        Задолж (+)<br />Переплата (-)<br />на {$EndDate|escape}
                    </td>
                    </TD>
                </tr>
                <tr class="first">
                    <td>
                        Завтрак бесплатный
                    </td>
                    <td>
                        Завтрак
                    </td>
                    <td>
                        Обед
                    </td>
                    {if $StartTimeStamp < strtotime('2021-09-01 00:00:00')}<td>
                        Обед 2
                    </td>{/if}
                    <td>
                        Завтрак льготный
                    </td>
                    <td>
                        Завтрак М
                    </td>
                    <td>
                        Обед М
                    </td>                    
                    <td>
                        Полдник
                    </td>
                    <td>
                        Нал
                    </td>
                    <td>
                        Безнал
                    </td>
                </tr>
        {foreach name=products key=key item=product from=$Products}
            <tr class="other">
                <TD align=left valign=top style="width:12%; padding-left: 20px; white-space: nowrap">
                    {$smarty.foreach.products.iteration}. <span {*TITLE='{$product->model|escape} {$product->brand|escape} {$product->model|escape}' HREF='catalog/{$product->category_id}/{$product->product_id}.html'*} CLASS=subheader style="white-space: nowrap">{$product->model|escape}</span>
                </td>
                <td class=price style="width:10%;">
                    {assign var="begin" value=$product->total+$product->sum->sum_breakfast_free+$product->sum->sum_breakfast+$product->sum->sum_lunch+$product->sum->sum_lunch2+$product->sum->sum_lunch3+$product->sum->sum_lunch_m+$product->sum->sum_dinner_m+$product->sum->sum_dinner-$product->sum->sum_cash-$product->sum->sum_card}
                    <span class={if $product->begin>0}price-warning{else}price{/if}>{$product->begin}</span>{assign var="before" value=$before+$product->begin}
                </td>
                <td>
                    {$product->sum->breakfasts_free|escape} ({$product->sum->sum_breakfast_free|escape} руб){assign var="breakfast_free" value=$breakfast_free+$product->sum->sum_breakfast_free}{assign var="nbreakfast_free" value=$nbreakfast_free+$product->sum->breakfasts_free}
                </td>
                <td>
                    {$product->sum->breakfasts|escape} ({$product->sum->sum_breakfast|escape} руб){assign var="breakfast" value=$breakfast+$product->sum->sum_breakfast}{assign var="nbreakfast" value=$nbreakfast+$product->sum->breakfasts}
                </td>
                <td>
                    {$product->sum->lunches|escape} ({$product->sum->sum_lunch|escape} руб){assign var="lunch" value=$lunch+$product->sum->sum_lunch}{assign var="nlunch" value=$nlunch+$product->sum->lunches}
                </td>
                {if $StartTimeStamp < strtotime('2021-09-01 00:00:00')}<td>
                    {$product->sum->lunches3|escape} ({$product->sum->sum_lunch3|escape} руб){assign var="lunch3" value=$lunch3+$product->sum->sum_lunch3}{assign var="nlunch3" value=$nlunch3+$product->sum->lunches3}
                </td>{/if}                
                <td>
                    {$product->sum->lunches2|escape} ({$product->sum->sum_lunch2|escape} руб){assign var="lunch2" value=$lunch2+$product->sum->sum_lunch2}{assign var="nlunch2" value=$nlunch2+$product->sum->lunches2}
                </td>
                <td>
                    {$product->sum->lunches_m|escape} ({$product->sum->sum_lunch_m|escape} руб){assign var="lunch_m" value=$lunch_m+$product->sum->sum_lunch_m}{assign var="nlunch_m" value=$nlunch_m+$product->sum->lunches_m}
                </td>
                <td>
                    {$product->sum->dinners_m|escape} ({$product->sum->sum_dinner_m|escape} руб){assign var="dinner_m" value=$dinner_m+$product->sum->sum_dinner_m}{assign var="ndinner_m" value=$ndinner_m+$product->sum->dinners_m}
                </td>
                <td>
                    {$product->sum->dinners|escape} ({$product->sum->sum_dinner|escape} руб){assign var="dinner" value=$dinner+$product->sum->sum_dinner}{assign var="ndinner" value=$ndinner+$product->sum->dinners}
                </td>
                <td>
                    {$product->sum->sum_cash|escape}{assign var="cash" value=$cash+$product->sum->sum_cash}
                </td>
                <td>
                    {$product->sum->sum_card|escape}{assign var="card" value=$card+$product->sum->sum_card}
                </td>
                <td class=price style="width:10%;">
                    <span class={if $product->total>0}price-warning{else}price{/if}>{$product->total}</span>{assign var="after" value=$after+$product->total}&nbsp;(&nbsp;
                    {$product->begin+$product->sum->sum_breakfast_free+$product->sum->sum_breakfast+$product->sum->sum_lunch+$product->sum->sum_lunch2+$product->sum->sum_lunch3+$product->sum->sum_lunch_m+$product->sum->sum_dinner_m+$product->sum->sum_dinner-$product->sum->sum_cash-$product->sum->sum_card-$product->total}&nbsp;
                    &nbsp;)
                </td>
                </TD>
            </tr>
            {if $smarty.foreach.products.last}
                <tr class="first">
                    <TD><strong>Итого количество</strong></td>
                    <td>

                    </td>
                    <td>
                        {$nbreakfast_free}
                    </td>
                    <td>
                        {$nbreakfast}
                    </td>
                    <td>
                        {$nlunch}
                    </td>
                    {if $StartTimeStamp < strtotime('2021-09-01 00:00:00')}<td>
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

                    </TD>
                </tr>
                <tr class="first">
                    <TD><strong>Итого сумма, руб</strong></td>
                    <td>
                        {$before}
                    </TD>
                    <td>
                        {$breakfast_free}
                    </td>
                    <td>
                        {$breakfast}
                    </td>
                    <td>
                        {$lunch}
                    </td>
                    {if $StartTimeStamp < strtotime('2021-09-01 00:00:00')}<td>
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
                        {if $StartTimeStamp < strtotime('2021-09-01 00:00:00')}
                        {assign var=plus value=$breakfast_free+$breakfast+$lunch+$lunch2+$lunch3+$dinner+$lunch_m+$dinner_m}
                        {else}
                        {assign var=plus value=$breakfast_free+$breakfast+$lunch+$lunch2+$dinner+$lunch_m+$dinner_m}    
                        {/if}
                        {$after}&nbsp;(&nbsp;
                        {math equation="a+b-c-d-e" a=$before b=$plus c=$cash d=$card e=$after format="%.2f"}  
                        &nbsp;)
                    </TD>
                </tr>
                <tr class="first">
                    <TD><strong>Итого, руб</strong></td>
                    <td>
                        {$before}
                    </TD>
                    <td colspan="{if $StartTimeStamp < strtotime('2021-09-01 00:00:00')}8{else}7{/if}">
                        {if $StartTimeStamp < strtotime('2021-09-01 00:00:00')}
                        {assign var=plus value=$breakfast_free+$breakfast+$lunch+$lunch2+$lunch3+$dinner+$lunch_m+$dinner_m}
                        {else}
                        {assign var=plus value=$breakfast_free+$breakfast+$lunch+$lunch2+$dinner+$lunch_m+$dinner_m}    
                        {/if}
                        {$plus}
                    </td>
                    <td colspan="2">
                        {math equation="-b-c" b=$cash c=$card format="%.2f"}
                    </td>
                    <td>
                        {$after}&nbsp;(&nbsp;
                        {math equation="a+b-c-d-e" a=$before b=$plus c=$cash d=$card e=$after format="%.2f"}                       
                        &nbsp;)
                    </TD>
                </tr>
            {/if}
        {/foreach}
    </TABLE>
<br />
<input type="submit" name="submit" value="Печать" style="margin: 10px 10px" onclick="window.print(); return false;">
<br />
<nobr><a href="http://{php} print $_SERVER['HTTP_HOST']; $dir=dirname($_SERVER["PHP_SELF"]); if($dir!='/' && $dir!='\\') print $dir; {/php}/" class="brand">На Главную страницу</a></nobr>
<br />
