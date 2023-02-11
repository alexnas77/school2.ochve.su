{config_load file=premium.conf}
<FORM METHOD=POST NAME=sections>
<TABLE WIDTH=100% BORDER=0 CELLPADDING=5 CELLSPACING=0>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=subheader colspan=3>
      <a href='index.php'>{$Settings->site_name}</a> / {$Lang->FAQ}
    </TD>
  </TR>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=header>
       <img src='images/poll_icon.png' border=0 align=absmiddle> {$Lang->FAQ}
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
          <TD ALIGN=RIGHT>
            <a href='index.php?section=EditFaq'>{$Lang->NEW_FAQ}</a>
          </TD>
        </TR>
      </TABLE>
    </TD>
  </TR>
  <TR>
    <TD ALIGN=CENTER VALIGN=TOP CLASS=body>

<TABLE WIDTH=100% CLASS=list CELLPADDING=4 CELLSPACING=1>
  <TR>
    <TD class=listheader WIDTH=10%>
      {$Lang->FNAME}
    </TD>
    <TD class=listheader WIDTH=10% align=center>
      <nobr>{$Lang->MAIL}</nobr>
    </TD>
    <TD class=listheader WIDTH=10% align=center>
      <nobr>{$Lang->PHONE}</nobr>
    </TD>
      <TD class=listheader WIDTH=10% align=center>
          Тема
      </TD>
    <TD class=listheader WIDTH=10% align=center>
      Удобное время звонка
    </TD>
      <TD class=listheader WIDTH=10% align=center>
          {$Lang->MESSAGE}
      </TD>
    <TD class=listheader WIDTH=10% align=center>
      {$Lang->ANSWER}
    </TD>
    <TD class=listheader WIDTH=5% align=center>
      {$Lang->FSHOW}
    </TD>
    <TD class=listheader WIDTH=5% align=center>
    &nbsp;
    </TD>
    <TD class=listheader WIDTH=5% align=center>
    &nbsp;
    </TD>
    <TD class=listheader WIDTH=5% align=center>
     {$Lang->DELETE}
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
  {if $Items}
     {foreach item=item from=$Items}
  <TR>
    <TD class=list>
      {$item->name|escape}
    </TD>
    <TD class=list>
      <a href="mailto:{$item->mail}">{$item->mail}</a>
    </TD>
    <TD class=list>
      {$item->phone|escape}
    </TD>
      <TD class=list>
          {$item->subject|escape}
      </TD>
      <TD class=list>
          {$item->time|escape}
      </TD>
    <TD class=list>
      {$item->message|escape|wordwrap:25:"<br />"}
    </TD>
    <TD class=list>
      {$item->answer|escape|wordwrap:25:"<br />"}
    </TD>
    <TD class=list align=center>
       <input name=shows[] value='{$item->id}' type=checkbox {if $item->show}checked{/if}>
    </TD>
    <TD class=list align=center>
      {html_image file='images/arrow_up.gif' href=index.php`$item->move_up_get` alt=$Lang->MOVE_UP title=$Lang->MOVE_UP border=0}
      {html_image file='images/arrow_down.gif' href=index.php`$item->move_down_get` alt=$Lang->MOVE_DOWN title=$Lang->MOVE_DOWN border=0}
    </TD>
    <TD class=list align=center>
      {html_image file='images/edit.gif' href=index.php`$item->edit_get` alt=$Lang->EDIT title=$Lang->EDIT border=0}
    </TD>
    <TD class=list align=center>
       <input name=items[] value='{$item->id}' type=checkbox>
    </TD>
  </TR>
  {/foreach}
  {else}
  <TR>
      <TD class=list align=center colspan=9>
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
            <INPUT name=delete TYPE=SUBMIT VALUE='{$Lang->SAVE_CHANGES}'>
            <INPUT TYPE=SUBMIT VALUE='{$Lang->DELETE_SELECTED}' onclick='if(!confirm("{$Lang->ARE_YOU_SURE_TO_DELETE}")) return false;'>
          </TD>
        </TR>
      </TABLE>
    </TD>
  </TR>
</TABLE>
</FORM>