{config_load file=premium.conf}
<FORM NAME=CATEGORY METHOD=POST>
<TABLE WIDTH=100% BORDER=0 CELLPADDING=5 CELLSPACING=0>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=subheader colspan=3>
      <a href='index.php'>{$Settings->site_name}</a> /
      {$Lang->PRODUCTS_CATEGORIES}
    </TD>
  </TR>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=header>
       <img src='images/categories_icon.gif' border=0 align=absmiddle>
         {$Lang->PRODUCTS_CATEGORIES}
    </TD>
  </TR>
  {if $ErrorMSG}
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=error>
       <b><font color=red>{$ErrorMSG}</font></b>
    </TD>
  </TR>
  {/if}
  <TR>
    <TD HEIGHT=10>
    </TD>
  </TR>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=body>
      <TABLE CELLPADDING=0 CELLSPACING=0 WIDTH=100%>
        <TR>
          <TD width=100px>
           Сортировка:
          </TD>
          <TD width=100px ALIGN=left>
           <a href='index.php?section=ProductCategories&action=name'>По названию</a>
          </TD>
          <TD width=100px ALIGN=left>
           <a href='index.php?section=ProductCategories&action=id'>По ID</a>
          </TD>
          <TD>
            &nbsp;
          </TD>
          <TD ALIGN=RIGHT>
            <a href='index.php?section=EditProductCategory&parent={$Parent->category_id}'>{$Lang->NEW_CATEGORY}</a>
          </TD>
        </TR>
      </TABLE>
    </TD>
  </TR>
  <TR>
    <TD ALIGN=CENTER VALIGN=TOP CLASS=body>

<TABLE WIDTH=100% CLASS=list CELLPADDING=4 CELLSPACING=1>
  <TR>
    <TD class=listheader >
      {$Lang->CATEGORY}
    </TD>
    <TD class=listheader >
      {$Lang->ALIES}
    </TD>
    <TD class=listheader>
      {$Lang->PHOTO}
    </TD>
    <TD class=listheader>
      &nbsp;
    </TD>
    <TD class=listheader>
      {$Lang->TITLE}
    </TD>
    {*<TD class=listheader width=100>
      {$Lang->DESCRIPTION}
    </TD>*}
    <TD class=listheader width=100>
      {$Lang->CAT_ENABLED}
    </TD>
    <TD class=listheader WIDTH=45 align=center>
    </TD>
    <TD class=listheader WIDTH=25 align=center>
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
  <input type=hidden value='' name=delete_fotos>
  {include file=cat.tpl Categories=$Categories level=0}
</TABLE>

    </TD>
  </TR>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=body>
      <TABLE CELLPADDING=0 CELLSPACING=0 WIDTH=100%>
        <TR>
          <TD>
           <INPUT TYPE=SUBMIT NAME=SUBMIT VALUE='{$Lang->SAVE}' onclick='window.document.category.submit();'>
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