<form name="go" method="post" {literal}onsubmit="if(document.go.mode.value==-1){window.location.href='/login'} else {window.location.href='/'+(document.go.mode.value==1?'p':'')+'catalog/'+document.go.class.value+'/'+document.go.date.value;} return false;"{/literal}>
    {if isset($smarty.session.user_login) && !empty($smarty.session.user_login)}<a href="/logout" style="position: absolute; top: 80px; left: 61%;">Выйти</a>{else}<a href="/login" style="position: absolute; top: 80px; left: 61%;">Войти</a>{/if}
    <table align="center" cellpadding="5" cellspacing="5" style="width:400px">
        <tr>
            <td colspan="2"><div align="left" style="font-size:16px;background-color: #919191;color: White;padding: 10px 20px;"><b>Выберите класс и дату</b></div></td>
        </tr>
        {if $Categories}
        <tr>
            <td align="left">Класс</td>
                <td align="left"><select size="1" name="class" style="width:265px">
                    {foreach name=classes item=class from=$Categories}
                        <option value="{$class->category_id}">{$class->name}</option>
                    {/foreach}
                </select></td>
        </tr>
        {/if}
        <tr>
            <td align="left">Дата</td>
            <td align="left"><input id="date" name="date" type="text" style="width:260px" value="{if strtotime(date("d.m.Y"))<strtotime($Settings->start)}{$Settings->start}{elseif !empty($smarty.session.date)}{$smarty.session.date}{else}{$smarty.now|date_format:"%d.%m.%Y"}{/if}" {*onfocus="showCalendar('',this,this,'','holder',5,5,1)"*}><img style='position:relative;left:-23px;top:0' border=0 src=calendar/calendar.gif></td>
        </tr>
        <tr>
            <td align="center" colspan="2"><a href="javascript:void(0);" onclick="document.go.date.value='{$smarty.now|date_format:"%d.%m.%Y"}'">Сегодня</a></td>
        </tr>
        <tr>
            <td align="left">Режим</td>
            <td align="left">
                <select size="1" name="mode" style="width:265px">
                    {if isset($smarty.session.user_login) && !empty($smarty.session.user_login)}<option value="0">редактирование</option><option value="1">печать</option>{else}<option value="1">печать</option><option value="-1">Авторизоваться для редактирования</option>{/if}
                </select></td>
        </tr>
        <tr>
            <td align="left"><input type="submit" value="Перейти"></td>
            <td align="left"><input type="reset" value="Сбросить"></td>
        </tr>
    </table>
</form><br /><br />
<form id="report" name="report" method="get" action="index.php" onsubmit="$('#ajax').remove();{*if(document.report.name.value) $('#report2').remove();*}">
    <input type="hidden" name="section" value="4">
    <input type="hidden" id="report2" name="report" value="1">    
    <input type="hidden" id="ajax" name="ajax" value="1">
    <table align="center" cellpadding="5" cellspacing="5" style="width:400px">
        <tr>
            <td colspan="2"><div align="left" style="font-size:16px;background-color: #919191;color: White;padding: 10px 20px;"><b>Выберите класс и период для отчета</b></div></td>
        </tr>
        <tr>
            <td align="left">Фамилия</td>
            <td align="left"><input id="name" name="name" type="text" style="width:260px" value=""></td>
        </tr>        
        {if $Categories}
            <tr>
                <td align="left">Класс</td>
                <td align="left"><select size="1" id="category" name="category" style="width:265px">
                        <option value="0">Все классы</option>
                        {foreach name=classes item=class from=$Categories}
                            <option value="{$class->category_id}">{$class->name}</option>
                        {/foreach}
                    </select></td>
            </tr>
        {/if}
        <tr>
            <td align="left">Начало</td>
            <td align="left"><input id="start_date" name="start_date" type="text" style="width:260px" value="{if strtotime(date("d.m.Y"))<strtotime($Settings->start)}{$Settings->start}{else}{$smarty.now|date_format:"01.%m.%Y"}{/if}" {*onfocus="showCalendar('',this,this,'','holder',5,5,1)"*}><img style='position:relative;left:-23px;top:0' border=0 src=calendar/calendar.gif></td>
        </tr>
        <tr>
            <td align="left">Окончание</td>
            <td align="left"><input id="end_date" name="end_date" type="text" style="width:260px" value="{if strtotime(date("d.m.Y"))<strtotime($Settings->start)}{$Settings->start}{else}{$smarty.now|date_format:"%d.%m.%Y"}{/if}" {*onfocus="showCalendar('',this,this,'','holder',5,5,1)"*}><img style='position:relative;left:-23px;top:0' border=0 src=calendar/calendar.gif></td>
        </tr>        
        <tr>
            <td align="left"><input type="submit" id="submit" value="Перейти"></td>
            <td align="left"><input type="reset" id="reset" value="Сбросить"></td>
        </tr>
        <tr>
            <td align="left" colspan="2" id="found">&nbsp;</td>
        </tr>  
    </table>
</form>
{literal}<script type="text/javascript">
    var options = { dateFormat: "dd.mm.yy", 
                    monthNames: [ "Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь" ], 
                    dayNamesMin: [ "Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб" ], 
                    firstDay: 1 };
$( "#date" ).datepicker(options);
$( "#start_date" ).datepicker(options);
$( "#end_date" ).datepicker(options);
function request(){
    $.ajax({
    type: "GET",
    url: "index.php",
    data: $("#report").serialize(),
    dataType: "html"
    })
    .success(function(data){$("#found").html(data);})
    .error(function(message){alert('Ошибка '+message);});
    return false;
}
$("#name").keyup(request);
$("#category").change(request);
$( "#start_date" ).change(request);
$( "#end_date" ).change(request);
$("#submit").click(function(){$("#found").html("");});
$("#reset").click(function(){$("#found").html("");});
</script>{/literal}