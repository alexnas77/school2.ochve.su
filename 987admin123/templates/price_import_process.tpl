{config_load file=premium.conf}
<FORM METHOD=POST enctype='multipart/form-data'>
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
         {*<TR>
           <TD CLASS=listheader>
             {$Lang->BRAND}
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
         </TR>*}
         <TR>
         <TD CLASS=list align="center" colspan="{$Count}">
              {$Lang->MODEL_CODE}:
         </TD>
         </TR>
         <TR>
       {foreach name=td key=key item=item from=$Radios}
           <TD CLASS=list align="center">
            <input name="MODEL_CODE" type="radio" value="{$key}">
           </TD>
       {/foreach}
         </TR>
         {*<TR>
         <TD CLASS=list align="center" colspan="{$Count}">
              {$Lang->MODEL}:
         </TD>
         </TR>
         <TR>
       {foreach name=td key=key item=item from=$Radios}
           <TD CLASS=list align="center">
            <input name="MODEL" type="radio" value="{$key}">
           </TD>
       {/foreach}
         </TR>*}
         <TR>
         <TD CLASS=list align="center" colspan="{$Count}">
              {$Lang->PRICE}: {html_options name=currency_id values=$Codes output=$Codes selected=$MainCurrency->code}
         </TD>
         </TR>
         <TR>
       {foreach name=td key=key item=item from=$Radios}
           <TD CLASS=list align="center">
            <input name="PRICE" type="radio" value="{$key}">
           </TD>
       {/foreach}
         </TR>
       {if $Item}
       {foreach name=tr key=ktr item=tr from=$Item}
         <TR>
       {foreach name=td key=ktd item=td from=$tr}
           <TD CLASS=list>
            <input name="{$ktr}[]" type="text" value="{$td}" size="15">
           </TD>
       {/foreach}
         </TR>
       {/foreach}
       {/if}
       </TABLE>
     </TD>
  </TR>
  <TR>
      <TD>
       <input name="update" type="hidden" value="1">
       <INPUT name=go TYPE=SUBMIT VALUE='{$Lang->UPDATE_PRICES}'>
      </TD>
   </TR>
 </TABLE>
 </FORM>