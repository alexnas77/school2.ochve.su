{config_load file=premium.conf}
<FORM METHOD=POST enctype='multipart/form-data'>
<TABLE WIDTH=100% BORDER=0 CELLPADDING=5 CELLSPACING=0>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=subheader colspan=3>
      <a href='index.php'>{$Settings->site_name}</a> / {$Lang->PRODUCTS_IMPORT}
    </TD>
  </TR>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=header colspan=3>
      <img src='images/import.jpg' border=0 align=absmiddle> {$Lang->PRODUCTS_IMPORT}
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
        <TABLE cellpadding=10 cellspacing=0>
          <TR>
            <TD valign=top>{$Lang->IMPORT_FROM}:<br></TD>
            <TD valign=top><input type=radio name=engine value='shopscript' checked> Shop Script
            <br>{$Lang->IMPORT_SHOPSCRIPT_HELP}
            </TD>
          </TR>
          <TR>
            <TD valign=top>{$Lang->IMPORT_FROM}:<br></TD>
            <TD valign=top><input type=radio name=engine value='webox' checked> Webox CSV
            <br>
            </TD>
          </TR>
          <TR>
            <TD valign=top>{$Lang->PRODUCTS_FILE}:</TD>
            <TD valign=top><input size=70 type=file name=f></TD>
          </TR>
          <TR>
            <TD>
            </TD>
            <TD><INPUT TYPE=SUBMIT VALUE='{$Lang->START_IMPORT}'></TD>
          </TR>
        </TABLE>



     </TD>
  </TR>
 </TABLE>
</FORM>