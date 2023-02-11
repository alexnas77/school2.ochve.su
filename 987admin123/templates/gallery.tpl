{config_load file=premium.conf}
<FORM METHOD=POST NAME=sections>
<TABLE WIDTH=100% BORDER=0 CELLPADDING=5 CELLSPACING=0>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=subheader colspan=3>
      <a href='index.php'>{$Settings->site_name}</a> / {$Lang->GALLERY}
    </TD>
  </TR>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=header>
       <img src='images/storefront_icon.gif' border=0 align=absmiddle> {$Lang->GALLERY}
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
            <a href='index.php?section=EditGallery'>{$Lang->NEW_FOTO}</a>
          </TD>
        </TR>
      </TABLE>
    </TD>
  </TR>
  <TR>
    <TD ALIGN=CENTER VALIGN=TOP CLASS=body>

<TABLE WIDTH=100% CLASS=list CELLPADDING=4 CELLSPACING=1>
  <TR>
    <TD class=listheader WIDTH=25>
    ID
    </TD>
    <TD class=listheader align=center>
      {$Lang->PHOTO}
    </TD>
    <TD class=listheader WIDTH=25>
    </TD>
    <TD class=listheader WIDTH=25>
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
    <TD class=list>
      {$item->foto_id}.
    </TD>
    <TD class=list align=center>
	<a href='{$Uploaddir}{$item->filename}' target=_blank><img id=image src='{$Uploaddir}{$item->filename}' width=100 border=1 align=middle></a>
    </TD>
    <TD class=list align=center>
      {html_image file='images/arrow_up.gif' href=index.php`$item->move_up_get` alt=$Lang->MOVE_UP title=$Lang->MOVE_UP border=0}
      {html_image file='images/arrow_down.gif' href=index.php`$item->move_down_get` alt=$Lang->MOVE_DOWN title=$Lang->MOVE_DOWN border=0}
    </TD>
    <TD class=list align=center>
      {html_image file='images/edit.gif' href=index.php`$item->edit_get` alt=$Lang->EDIT title=$Lang->EDIT border=0}
    </TD>
     <TD class=list align=center>
       <input name=enabled[] value='{$item->foto_id}' type=checkbox {if $item->enabled}checked{/if}>
    </TD>
    <TD class=list align=center>
       <input name=items[] value='{$item->foto_id}' type=checkbox>
       <input name="id[{$item->foto_id}]" type="hidden" value="{$item->foto_id}">
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
            <INPUT TYPE='SUBMIT' VALUE='{$Lang->DELETE_SELECTED}' onclick='if(!confirm("{$Lang->ARE_YOU_SURE_TO_DELETE}")) return false; window.document.sections.act.value="delete"; window.document.sections.submit();'>
          </TD>
        </TR>
      </TABLE>
    </TD>
  </TR>
</TABLE>
</FORM>