<H1>Корзина</H1>
<FORM NAME=PAY METHOD=GET ACTION="wmk:payto?Purse={$Purse}&Amount={$Amount|string_format:"%.2f"}&Desc={$Desc}&BringToFront=Y">
<TABLE width=50% cellpadding=0 cellspacing=10>
  <tr>
    <TD align=center valign=top>
    <H2>WebMoney</H2>
    </TD>
    </tr>
  <tr>
    <TD ALIGN=LEFT valign=top>
    <input type=submit value='{$url_name}'>
    {*<a href="wmk:payto?Purse=Z123456789123&Amount=100&Desc=Test&BringToFront=Y">Заплатить 100 WMZ</a>
    <a href="wmk:payto?Purse={$Purse}&Amount={$Amount|string_format:"%.2f"}&Desc={$Desc}&BringToFront=Y">{$url_name}</a>*}
    </TD>
    </tr>
</TABLE>
</FORM>
