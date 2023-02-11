{config_load file=premium.conf}

<TABLE WIDTH=100% BORDER=0 CELLPADDING=5 CELLSPACING=0>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=subheader colspan=3>
      <a href='index.php'>{$Settings->site_name}</a> / <a href='index.php?section=PriceImport'>{$Lang->PRICELIST_IMPORT}</a> / {$Lang->IMPORT_RESULTS}
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
       <TABLE  WIDTH=100% CLASS=list CELLPADDING=4 CELLSPACING=1>
         <TR>
           <TD CLASS=listheader>
             {$Lang->MODEL_CODE}
           </TD>
           <TD CLASS=listheader>
             {$Lang->MODEL}
           </TD>
           <TD CLASS=listheader>
             {$Lang->OLD_PRICE}
           </TD>
           <TD CLASS=listheader>
             {$Lang->NEW_PRICE}
           </TD>
           <TD CLASS=listheader>
             {$Lang->STATUS}
           </TD>
         </TR>
       {if $Products}
       {foreach name=products item=product from=$Products}
         <TR>
           <TD CLASS=list>
            {$product->code}
           </TD>
           <TD CLASS=list>
            {$product->model}
           </TD>
           <TD CLASS=list>
            {$product->old_price} {$product->old_currency}
           </TD>
           <TD CLASS=list>
            {$product->price} {$product->currency_id}
           </TD>
           <TD CLASS=list>
            {$product->message}
           </TD>
         </TR>
       {/foreach}
       {else}
         <TR>
           <TD colspan=5 CLASS=list>
             {$Lang->NO_PRICES_IMPORTED}
           </TD>
         </TR>
       {/if}
       </TABLE>
     </TD>
  </TR>
  <TR>
      <TD>
       <INPUT TYPE=BUTTON VALUE='{$Lang->BACK}' onclick="window.document.location='index.php?section=PriceImport';">
       <INPUT TYPE=BUTTON VALUE='{$Lang->GO_TO_MAIN_PAGE}' onclick="window.document.location='index.php';">
      </TD>
   </TR>
 </TABLE>