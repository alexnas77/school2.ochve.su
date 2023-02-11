{config_load file=premium.conf}
<FORM METHOD=POST enctype='multipart/form-data'>
<TABLE WIDTH=100% BORDER=0 CELLPADDING=5 CELLSPACING=0>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=subheader colspan=3>
      <a href='index.php'>{$Settings->site_name}</a> / {$Lang->PRICELIST_IMPORT}
    </TD>
  </TR>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=header colspan=3>
      <img src='images/import_icon.jpg' border=0 align=absmiddle> {$Lang->PRICELIST_IMPORT}
    </TD>
  </TR>
  <TR>
    <TD HEIGHT=10>
    </TD>
  </TR>
  {if $ErrorMSG}
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP class=error>
      {$ErrorMSG}
    </TD>
  </TR>
  {/if}
  <TR>
  <TR>
     <TD>
       	<img align=right src='images/price_example.gif'>
        {$Lang->PRICELIST_IMPORT_HELP}
     </TD>
  </TR>
  <TR>
     <TD>
       	<textarea name=price cols=100 rows=30></textarea>
     </TD>
  </TR>
   <TR>
      <TD>
       <INPUT name=go TYPE=SUBMIT VALUE='{$Lang->SAVE}'>
      </TD>
   </TR>
 </TABLE>
</FORM>