<FORM METHOD=POST NAME=sitemap>
<TABLE WIDTH=100% BORDER=0 CELLPADDING=5 CELLSPACING=0>
  <TR>
    <TD  COLSPAN=2 ALIGN=LEFT VALIGN=TOP CLASS=subheader colspan=3>
      <a href='index.php'>{$Settings->site_name}</a> / {$Lang->GOOGLE_SITEMAP}
    </TD>
  </TR>
  <TR>
    <TD  COLSPAN=2 ALIGN=LEFT VALIGN=TOP CLASS=header>
       <img src='images/sitemap.gif' border=0 align=absmiddle> {$Lang->GOOGLE_SITEMAP}
    </TD>
  </TR>
  <TR>
    <TD COLSPAN=2 HEIGHT=10>
    </TD>
  </TR>
  <TR>
    <TD ALIGN=LEFT>
      <input type=button value='{$Lang->GENERATE}' onclick='window.document.sitemap.act.value="generate"; window.document.sitemap.submit();'>
      <input type=hidden name=act value=''>
    </TD>
    <TD ALIGN=right>
      <input type=submit value='{$Lang->SAVE}'>
    </TD>
  </TR>
  <TR>
    <TD COLSPAN=2 ALIGN=LEFT VALIGN=top CLASS=body>
        <textarea style='width:100%' rows=30 name=sitemap>{$Sitemap}</textarea>
    </TD>
  </TR>
</TABLE>
</FORM>