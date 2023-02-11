{config_load file=premium.conf}
<FORM METHOD=POST NAME=sections>
<TABLE WIDTH=100% BORDER=0 CELLPADDING=5 CELLSPACING=0>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=subheader colspan=3>
      <a href='index.php'>{$Settings->site_name}</a> /
      {$Lang->BRANDS}
    </TD>
  </TR>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=header>
       <img src='images/categories_icon.gif' border=0 align=absmiddle>
         {$Lang->BRANDS}
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
          <TD>

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
      {$Lang->NAME}
    </TD>
    <TD class=listheader>
      {$Lang->TITLE}
    </TD>
    <TD class=listheader width=100>
      {$Lang->DESCRIPTION}
    </TD>
    <TD class=listheader WIDTH=45 align=center>
    </TD>

  </TR>
  {include file=bran.tpl Brands=$Brands level=0}
</TABLE>

    </TD>
  </TR>
</TABLE>
</FORM>