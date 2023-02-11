{config_load file=premium.conf}
<FORM METHOD=POST NAME=sections>
<TABLE WIDTH=100% BORDER=0 CELLPADDING=5 CELLSPACING=0>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=subheader colspan=3>
      <a href='index.php'>{$Settings->site_name}</a> / {$Lang->USERCOMMENTS}
    </TD>
  </TR>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=header>
       <img src='images/comments.gif' border=0 align=absmiddle> {$Lang->USERCOMMENTS}
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
      {$Lang->DATE}
    </TD>
    <TD class=listheader WIDTH=150>
      {$Lang->PRODUCT}
    </TD>
    <TD class=listheader>
      {$Lang->USERNAME}
    </TD>
    <TD class=listheader WIDTH=25 align=center>
      {$Lang->USERCOMMENT}
    </TD>
    <TD class=listheader WIDTH=25 align=center>
      {$Lang->POINTS}
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
  {if $Items}
     {foreach item=item from=$Items}
  <TR>
    <TD class=list>
      {$item->date|escape}
    </TD>
    <TD class=list>
      <a href={$item->product_get}>{$item->brand|escape} {$item->model|escape}</a>
    </TD>
    <TD class=list>
      <a href={$item->ip_get}>{$item->name|escape}</a>
    </TD>
    <TD class=list>
      {$item->comment|escape}
    </TD>
    <TD class=list>
      {$item->point}
    </TD>
    <TD class=list align=center>
       <input name=items[] value='{$item->comment_id}' type=checkbox>
    </TD>
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
          <TD ALIGN=RIGHT>
            <INPUT TYPE=SUBMIT VALUE='{$Lang->DELETE_SELECTED}' onclick='if(!confirm("{$Lang->ARE_YOU_SURE_TO_DELETE}")) return false;'>
          </TD>
        </TR>
      </TABLE>
    </TD>
  </TR>
</TABLE>
</FORM>