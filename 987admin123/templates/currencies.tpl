{config_load file=premium.conf}
<FORM METHOD=POST NAME=products>
<TABLE WIDTH=100% BORDER=0 CELLPADDING=5 CELLSPACING=0>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=subheader colspan=3>
      <a href='index.php'>{$Settings->site_name}</a> / {$Lang->CURRENCIES}
    </TD>
  </TR>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=header>
       <img src='images/currency.gif' border=0 align=absmiddle> {$Lang->CURRENCIES}
    </TD>
  </TR>
  <TR>
    <TD HEIGHT=10>
    </TD>
  </TR>
  <TR>
    <TD ALIGN=CENTER VALIGN=TOP CLASS=body>

<TABLE WIDTH=100% CLASS=list CELLPADDING=4 CELLSPACING=1>
  <TR>
    <TD class=listheader WIDTH=70>
      {$Lang->MAIN_CURRENCY}
    </TD>
    <TD class=listheader WIDTH=150>
      {$Lang->NAME}
    </TD>
    <TD class=listheader WIDTH=100>
      {$Lang->SIGN}
    </TD>
    <TD class=listheader WIDTH=100>
      {$Lang->CODE}
    </TD>
    <TD class=listheader>
      {$Lang->RATE}
    </TD>
    <TD class=listheader WIDTH=50 align=center>
      {literal}
         <script>
         function check_delete(checked)
         {
           var checkboxes = window.document.getElementsByName('items[]');
           var num = checkboxes.length;
           for(var i=0; i<num; i++)
           {
             checkboxes[i].checked = checked;
           }
         }
         </script>
      {/literal}
      <input type=checkbox onclick='check_delete(this.checked);'>
    </TD>
  </TR>
  {if $Items}
     {foreach item=item from=$Items}
  <TR>
    <TD class=list>
      <input type=radio name=main {if $item->main}checked{/if} value={$item->currency_id}>
    </TD>
    <TD class=list>
      <input size=20 type=text name=names[{$item->currency_id}] value='{$item->name|escape}'>
    </TD>
    <TD class=list>
      <input size=10 type=text name=signs[{$item->currency_id}] value='{$item->sign|escape}'>
    </TD>
    <TD class=list>
      <input size=10 type=text name=codes[{$item->currency_id}] value='{$item->code|escape}'>
    </TD>
    <TD class=list>
      <input size=10 type=text name=rates[{$item->currency_id}] value='{$item->rate|escape}'>
    </TD>
    <TD class=list align=center>
       <input name=items[] value='{$item->currency_id}' type=checkbox>
    </TD>
  </TR>
  {/foreach}
  {else}
  <TR>
      <TD class=list align=center colspan=3>
         {$Lang->EMPTY_LIST}
      </TD>
  </TR>
  {/if}
</TABLE>

    </TD>
  </TR>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=body>
      <TABLE CELLPADDING=0 CELLSPACING=0 WIDTH=100%>
        <TR>
          <TD>

          </TD>
          <TD ALIGN=RIGHT>
            <INPUT TYPE=SUBMIT VALUE='{$Lang->SAVE}'>
            <input type=hidden name=act value=''>
            <INPUT TYPE=button VALUE='{$Lang->DELETE_SELECTED}' onclick='if(!confirm("{$Lang->ARE_YOU_SURE_TO_DELETE}")) return false; window.document.products.act.value="delete"; window.document.products.submit();'>
          </TD>
        </TR>
      </TABLE>
    </TD>
  </TR>
</TABLE>

<TABLE CLASS=list CELLPADDING=4 CELLSPACING=1>
  <TR>
    <TD class=listheader WIDTH=150>
      {$Lang->NAME}
    </TD>
    <TD class=listheader WIDTH=100>
      {$Lang->SIGN}
    </TD>
    <TD class=listheader WIDTH=100>
      {$Lang->CODE}
    </TD>
    <TD class=listheader>
      {$Lang->RATE}
    </TD>
    <TD class=listheader>
    </TD>
  </TR>
  <TR>
    <TD class=list>
      <input size=20 type=text name=name>
    </TD>
    <TD class=list>
      <input size=10 type=text name=sign>
    </TD>
    <TD class=list>
      <input size=10 type=text name=code>
    </TD>
    <TD class=list>
      <input size=10 type=text name=rate value='1.00'>
    </TD>
    <TD class=list>
      <INPUT TYPE=button VALUE='{$Lang->ADD_CURRENCY}' onclick=' window.document.products.act.value="add"; window.document.products.submit();'>
    </TD>
  </TR>
</TABLE>
</form>