{config_load file=premium.conf}

<TABLE WIDTH=100% BORDER=0 CELLPADDING=5 CELLSPACING=0>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=subheader colspan=3>
      <a href='index.php'>{$Settings->site_name}</a> / <a href='index.php?section=PriceImport'>{$Lang->PRODUCTS_IMPORT}</a> / {$Lang->PRODUCTS_IMPORT_RESULTS}
    </TD>
  </TR>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=header colspan=3>
      <img src='images/import_icon.jpg' border=0 align=absmiddle> {$Lang->PRODUCTS_IMPORT}
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
             {$Lang->CATEGORY}
           </TD>
           <TD CLASS=listheader>
             {$Lang->BRAND}
           </TD>
           <TD CLASS=listheader>
             {$Lang->MODEL}
           </TD>
           <TD CLASS=listheader>
             {$Lang->PRICE}
           </TD>
           <TD CLASS=listheader>
             {$Lang->CURRENCY}
           </TD>
           <TD CLASS=listheader>
             {$Lang->STATUS}
           </TD>
         </TR>

       {foreach name=products item=product from=$Products}
         <TR>
           <TD CLASS=list>
            <a href='index.php?section=Storefront&category={$product->category_id}'>{$product->category}</a>
           </TD>
           <TD CLASS=list>
            {$product->brand}
           </TD>
           <TD CLASS=list>
            <a href='index.php?section=EditProduct&category={$product->category_id}&item_id={$product->product_id}'>{$product->model}</a>
           </TD>
           <TD CLASS=list>
            {$product->price}
           </TD>
           <TD CLASS=list>
            {$product->currency_id}
           </TD>
           <TD CLASS=list>
            {$product->status}
           </TD>
         </TR>
       {/foreach}
       </TABLE>
     </TD>
  </TR>
  <TR>
      <TD>
       <INPUT TYPE=BUTTON VALUE='{$Lang->BACK}' onclick="window.document.location='index.php?section=Import';">
       <INPUT TYPE=BUTTON VALUE='{$Lang->GO_TO_MAIN_PAGE}' onclick="window.document.location='index.php';">
      </TD>
   </TR>
 </TABLE>