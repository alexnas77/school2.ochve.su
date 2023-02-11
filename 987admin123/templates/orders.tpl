{config_load file=premium.conf}

<FORM METHOD=POST NAME=orders>
<TABLE WIDTH=100% BORDER=0 CELLPADDING=5 CELLSPACING=0>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=subheader colspan=3>
      <a href='index.php'>{$Settings->site_name}</a> / {$Lang->ORDERS}
    </TD>
  </TR>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=header>
       <img src='images/orders_icon.gif' border=0 align=absmiddle> {$Lang->ORDERS}
    </TD>
  </TR>
  <TR>
    <TD HEIGHT=10>
    </TD>
  </TR>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=body>
      <TABLE CELLPADDING=0 CELLSPACING=0 WIDTH=100%>
        <TR>
          <TD>
            {$PagesNavigation}
          </TD>
        </TR>
      </TABLE>
    </TD>
  </TR>
  <TR>
    <TD ALIGN=CENTER VALIGN=TOP CLASS=body>

<TABLE WIDTH=100% CLASS=list CELLPADDING=4 CELLSPACING=1>
  <TR>
    <TD class=listheader>
      №№
    </TD>
    <TD class=listheader>
      {$Lang->DATE}
    </TD>
    <TD class=listheader>
      {$Lang->PRODUCTS}
    </TD>
    <TD class=listheader>
      {$Lang->USERNAME}
    </TD>
    <TD class=listheader>
      {$Lang->CITY}
    </TD>
    <TD class=listheader>
      {$Lang->METRO}
    </TD>
    <TD class=listheader>
      {$Lang->ADDRESS2}
    </TD>
    <TD class=listheader>
      {$Lang->PHONE}
    </TD>
    <TD class=listheader nowrap>
      {$Lang->MAIL}
    </TD>
    <TD class=listheader>
      {$Lang->COMMENT}
    </TD>
    <TD class=listheader>
      {$Lang->PAY}
    </TD>
    <TD class=listheader>
      {$Lang->BILL}
    </TD>
    <TD class=listheader>
      {$Lang->STATUS}
    </TD>
    <TD class=listheader WIDTH=25 align=center>
      {literal}
         <script>
         function check(checked)
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
      <input type=checkbox onclick='check(this.checked);'>
    </TD>
  </TR>
  {if $Orders}
     {foreach item=order from=$Orders}
  <TR>
    <TD class=list>
      <nobr>{$order->order_id}</nobr>
    </TD>
    <TD class=list>
      <nobr>{$order->date_f}</nobr>
    </TD>
    <TD class=list>
      <TABLE>
      {foreach item=product from=$order->products}
        <TR>
          <TD valign=top>
            <a href='../index.php?section=4&item_id={$product->product_id}' target='_blank'>{$product->product_name}</a>
          </TD>
          <TD valign=top>
           {assign var="code" value=$product->currency_id}
             <nobr>{$product->price/$Currency_rates.$code|string_format:"%.2f"}&nbsp;{$MainCurrency->sign} x {$product->quantity}&nbsp;{$Lang->PCS}<br>
             <font {if $product->stock < $product->quantity}color=red{/if}>({if $product->stock}{$product->stock} {$Lang->PCS} {$Lang->IS_IN_STOCK}{else}{$Lang->NOT_IN_STOCK}{/if})</font>
             </nobr>
          </TD>
        </TR>
      {/foreach}
      </TABLE>
    </TD>
    <TD class=list>
      {if $order->login}
        <a href='index.php?section=EditUser&login={$order->login}'>{$order->name|escape}</a>
      {else}
        {$order->name|escape}
      {/if}
    </TD>
    <TD class=list>
      {$order->city|escape}
    </TD>
    <TD class=list>
      {$order->metro|escape}
    </TD>
    <TD class=list>
      {$order->address|escape|wordwrap:20:"<br />"}
    </TD>
    <TD class=list>
      {$order->phone|escape|wordwrap:20:"<br />"}
    </TD>
    <TD class=list>
      <a href="mailto:{$order->mail}">{$order->mail|escape}</a>
    </TD>
    <TD class=list>
      {$order->comment|escape}
    </TD>
    <TD class=list>
     {foreach item=pay key=pkey from=$Pays}
     {if $order->pay == $pkey}<a href={$order->change_pay_url} title="Отметить оплачено" onclick="if(!confirm('Заказ №{$order->order_id} действительно оплачен?')) return false;"><img src='images/refresh.gif' border=0 align="absmiddle" title="{$Lang->CHANGE_STATUS}">{$pay|wordwrap:15:"<br />"}</a>{elseif $order->pay == $pkey*10}<div align="center">{$pay|wordwrap:15:"<br />"}</div>{/if}
     {if $order->pay == $pkey*10 && $order->pay}<div align="center"><font color=green><b>{$Lang->PAID}</b></font></div>{/if}
     {/foreach}
      {*$order->know|escape|wordwrap:20:"<br />"*}
    </TD>
    <TD class=list>
     {if $order->text}
     <a href="{$order->bill_url}" title="{$Lang->SHOW_BILL}">{$Lang->SHOW_BILL}</a>
     {elseif $order->text2}
     <a target="_blank" href="{$order->bill_url}" title="{$Lang->SHOW_BILL}">{$Lang->SHOW_BILL}</a>
     {elseif $order->text3}
     <a href="{$order->bill_url}" title="{$Lang->SHOW_BILL}">{$Lang->SHOW_BILL}</a>
     {/if}
      {*$order->place|escape|wordwrap:20:"<br />"*}
    </TD>
    <TD class=list>
     {if $order->status == 0}
     <a href={$order->change_status_url}><img src='images/refresh.gif' border=0 align="absmiddle" title="{$Lang->CHANGE_STATUS}"></img></a>&nbsp;<a href={$order->change_status_url}><font color=green>{$Lang->NEW_STATUS}</font></a>{/if}
     {if $order->status == 1}
     <a href={$order->change_status_url}><img src='images/refresh.gif' border=0 align="absmiddle" title="{$Lang->CHANGE_STATUS}"></img></a>&nbsp;<a href={$order->change_status_url}><font color=blue>{$Lang->ACCEPTED_STATUS}</font></a>
     {/if}
     {if $order->status == 2}{$Lang->DONE_STATUS}{/if}
    </TD>
    <TD class=list align=center>
       <input name=items[] value='{$order->order_id}' type=checkbox>
    </TD>
  </TR>
  {/foreach}
  {else}
  <TR>
      <TD class=list align=center colspan=20>
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
            {$PagesNavigation}
          </TD>
          <TD ALIGN=RIGHT>
            <INPUT TYPE=SUBMIT VALUE='{$Lang->DELETE_SELECTED}' onclick='if(!confirm("{$Lang->ARE_YOU_SURE_TO_DELETE}")) return false;'>
          </TD>
        </TR>
      </TABLE>
    </TD>
  </TR>
</TABLE>
</FORM>