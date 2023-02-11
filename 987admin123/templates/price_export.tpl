{config_load file=premium.conf}
<FORM METHOD=POST enctype='multipart/form-data'>
<TABLE WIDTH=100% BORDER=0 CELLPADDING=5 CELLSPACING=0>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=subheader colspan=3>
      <a href='index.php'>{$Settings->site_name}</a> / {$Lang->PRICELIST_EXPORT}
    </TD>
  </TR>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=header colspan=3>
      <img src='images/export_icon.jpg' border=0 align=absmiddle> {$Lang->PRICELIST_EXPORT_IN_VARIOUS_FORMATS}:
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
     <TD>

       <TABLE  WIDTH=100% CLASS=list CELLPADDING=4 CELLSPACING=1>
         <TR>
           <TD CLASS=listheader>
             {$Lang->FORMAT}
           </TD>
           <TD CLASS=listheader>
             {$Lang->PRICELIST_URL}
           </TD>
           <TD CLASS=listheader>
             {$Lang->DOWNLOAD}
           </TD>
         </TR>
         <TR>
           <TD CLASS=list>
             E-katalog (CSV)
           </TD>
           <TD CLASS=list>
             http://{php}print $_SERVER['HTTP_HOST'];{/php}/admin/?section=PriceExport&format=ekatalog
           </TD>
           <TD CLASS=list>
             <a href='http://{php}print $_SERVER['HTTP_HOST'];{/php}/admin/?section=PriceExport&format=ekatalog'>Скачать</a>
           </TD>
         </TR>
         <TR>
           <TD CLASS=list>
             Meta.ua (XML)
           </TD>
           <TD CLASS=list>
             http://{php}print $_SERVER['HTTP_HOST'];{/php}/admin/?section=PriceExport&format=meta
           </TD>
           <TD CLASS=list>
             <a href='http://{php}print $_SERVER['HTTP_HOST'];{/php}/admin/?section=PriceExport&format=meta'>Скачать</a>
           </TD>
         </TR>
         <TR>
           <TD CLASS=list>
             Yandex маркет (YML)
           </TD>
           <TD CLASS=list>
             http://{php}print $_SERVER['HTTP_HOST'];{/php}/admin/?section=PriceExport&format=yandex
           </TD>
           <TD CLASS=list>
             <a href='http://{php}print $_SERVER['HTTP_HOST'];{/php}/admin/?section=PriceExport&format=yandex'>{$Lang->DOWNLOAD} </a>
           </TD>
         </TR>
       </TABLE>

     </TD>
  </TR>
  </TABLE>
</FORM>