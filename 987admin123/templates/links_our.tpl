{config_load file=premium.conf}
<TABLE WIDTH=100% BORDER=0 CELLPADDING=5 CELLSPACING=0>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=subheader colspan=3>
      <a href='index.php'>{$Settings->site_name}</a> / {$Lang->OUR_LINKS}
    </TD>
  </TR>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=header>
       <img src='images/articles_icon.png' border=0 align=absmiddle> {$Lang->OUR_LINKS}
    </TD>
  </TR>
  <TR>
    <TD>
    <form name="search" action="index.php" method="get">
    <input name="section" type="hidden" value="{$smarty.get.section}">
    <p>Поиск  <input name="keyword" size="60" type="text" value="{$smarty.get.keyword|escape}">  <input type="submit" value="Найти"></p>
    </form>
    </TD>
  </TR>
  <TR>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=body>
<FORM METHOD=POST NAME=sections>
      <TABLE CELLPADDING=0 CELLSPACING=0 WIDTH=100%>
        <TR>
          <TD>
            {$PagesNavigation}
          </TD>
          <TD ALIGN=RIGHT>
            <a href='index.php?section=EditOurLink'>{$Lang->NEW_LINK}</a>
          </TD>
        </TR>
      </TABLE>
    </TD>
  </TR>
  <TR>
    <TD ALIGN=CENTER VALIGN=TOP CLASS=body>

<TABLE WIDTH=100% CLASS=list CELLPADDING=4 CELLSPACING=1>
  <TR>
    <TD class=listheader WIDTH=150>
      {$Lang->BACK_LINK} текст
    </TD>
    <TD class=listheader WIDTH=45 align=center>
    </TD>
    <TD class=listheader WIDTH=25 align=center>
    </TD>
    <TD class=listheader WIDTH=25 align=center>
      {literal}
         <script>
         function check_enabled(checked)
         {
           var checkboxes = window.document.getElementsByName('enabled[]');
           var num = checkboxes.length;
           for(var i=0; i<num; i++)
           {
             checkboxes[i].checked = checked;
           }
         }
         </script>
      {/literal}
      {$Lang->ACTIVE}
      <input type=checkbox onclick='check_enabled(this.checked);'>
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
      {$Lang->DELETE}
      <input type=checkbox onclick='check(this.checked);'>
    </TD>
  </TR>
  {if $Items}
     {foreach item=item from=$Items}
  <TR>
    <TD class=list nowrap>
      {$item->my_link|escape}
    </TD>
    <TD class=list align=center nowrap>
      {html_image file='images/arrow_up.gif' href=index.php`$item->move_up_get` alt=$Lang->MOVE_UP title=$Lang->MOVE_UP border=0}
      {html_image file='images/arrow_down.gif' href=index.php`$item->move_down_get` alt=$Lang->MOVE_DOWN title=$Lang->MOVE_DOWN border=0}
    </TD>
    <TD class=list align=center>
      {html_image file='images/edit.gif' href=index.php`$item->edit_get` alt=$Lang->EDIT title=$Lang->EDIT border=0}
    </TD>
     <TD class=list align=center>
       <input name=enabled[] value='{$item->article_id}' type=checkbox {if $item->enabled}checked{/if}>
       <input name="rows[{$item->article_id}]" type="hidden" value="{$item->article_id}">
    </TD>
    <TD class=list align=center>
       <input name=items[] value='{$item->article_id}' type=checkbox>
    </TD>
  </TR>
  {/foreach}
  {else}
  <TR>
      <TD class=list align=center colspan=10>
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
			<INPUT TYPE='SUBMIT' VALUE='{$Lang->SAVE_CHANGES}' onclick='window.document.sections.act.value="active"; window.document.sections.submit();'>
            <input type='hidden' name='act' value=''>
            <INPUT TYPE=SUBMIT VALUE='{$Lang->DELETE_SELECTED}' onclick='if(!confirm("{$Lang->ARE_YOU_SURE_TO_DELETE}")) return false;'>
          </TD>
        </TR>
      </TABLE>
    </TD>
  </TR>
</TABLE>
</FORM>