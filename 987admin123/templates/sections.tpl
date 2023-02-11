{config_load file=premium.conf}
<FORM METHOD=POST NAME=sections>
<TABLE WIDTH=100% BORDER=0 CELLPADDING=5 CELLSPACING=0>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=subheader colspan=3>
      <a href='index.php'>{$Settings->site_name}</a> / {$Menu->name}
    </TD>
  </TR>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=header>
       <img src='images/menu_icon.gif' border=0 align=absmiddle> {$Menu->name}
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
          {if $Fixed == 'N'}
          <TD ALIGN=RIGHT>
            <a href='index.php?section=NewSection&menu={$Menu->menu_id}'>{$Lang->NEW_SECTION}</a>
          </TD>
          {/if}
        </TR>
      </TABLE>
    </TD>
  </TR>
  <TR>
    <TD ALIGN=CENTER VALIGN=TOP CLASS=body>

<TABLE WIDTH=100% CLASS=list CELLPADDING=4 CELLSPACING=1>
  <TR>
    <TD class=listheader>
      {$Lang->NAME}
    </TD>
    <TD class=listheader WIDTH=150>
      {$Lang->SECTION_TYPE}
    </TD>
    <TD class=listheader WIDTH=150>
      {$Lang->PAGE_URL}
    </TD>
    {if $Fixed == 'N'}
    <TD class=listheader WIDTH=45 align=center>
    </TD>
    {/if}
    <TD class=listheader WIDTH=25 align=center>
    </TD>
    {if $Fixed == 'N'}
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
    {/if}
  </TR>
  {if $Sections}
     {foreach item=section from=$Sections}
  <TR>
    <TD class=list>
      {$section->name|escape}
    </TD>
    <TD class=list>
      {$section->service|escape}
    </TD>
    <TD class=list>
     <font size=-2> http://{php}print $_SERVER['HTTP_HOST'];{/php}/{$section->url}.html  </font>
    </TD>
    {if $Fixed == 'N'}
    <TD class=list align=center>
      {html_image file='images/arrow_up.gif' href=index.php`$section->move_up_get` alt=$Lang->MOVE_UP title=$Lang->MOVE_UP border=0}
      {html_image file='images/arrow_down.gif' href=index.php`$section->move_down_get` alt=$Lang->MOVE_DOWN title=$Lang->MOVE_DOWN border=0}
    </TD>
    {/if}
    <TD class=list align=center>
      {html_image file='images/edit.gif' href=index.php`$section->edit_get` alt=$Lang->EDIT title=$Lang->EDIT border=0}
    </TD>
    {if $Fixed == 'N'}
    <TD class=list align=center>
       <input name=items[] value='{$section->section_id}' type=checkbox>
    </TD>
    {/if}
  </TR>
  {/foreach}
  {else}
  <TR>
      <TD class=list align=center colspan=6>
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
          {if $Fixed == 'N'}
          <TD ALIGN=RIGHT>
            <INPUT TYPE=SUBMIT VALUE='{$Lang->DELETE_SELECTED}' onclick='if(!confirm("{$Lang->ARE_YOU_SURE_TO_DELETE}")) return false;'>
          </TD>
          {/if}
        </TR>
      </TABLE>
    </TD>
  </TR>
</TABLE>
</FORM>