{config_load file=premium.conf}

<TABLE WIDTH=100% BORDER=0 CELLPADDING=5 CELLSPACING=0>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=subheader colspan=3>
      <a href='index.php'>{$Settings->site_name}</a> / <a href='index.php?section=Stat'>{$Lang->STATISTICS}</a>  /  {$Category->name}
    </TD>
  </TR>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=header colspan=3>
      <img src='images/stat_icon.gif' border=0 align=absmiddle>  {$Lang->STATISTICS_FOR} &laquo;{$Category->name}&raquo;
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
             {$Lang->PRODUCT}
           </TD>
           <TD CLASS=listheader>
             {$Lang->LAST_YEAR}
           </TD>
           <TD CLASS=listheader>
             {$Lang->LAST_MONTH}
           </TD>
           <TD CLASS=listheader>
             {$Lang->LAST_WEEK}
           </TD>
           <TD CLASS=listheader>
             {$Lang->YESTERDAY}
           </TD>
           <TD CLASS=listheader>
             {$Lang->TODAY}
           </TD>
         </TR>

       {foreach name=products item=product from=$Products}
       {assign var='id' value=$product->product_id}
       {assign var='today' value=$TodayProducts.$id}
       {assign var='yesterday' value=$YesterdayProducts.$id}
       {assign var='week' value=$WeekProducts.$id}
       {assign var='month' value=$MonthProducts.$id}
       {assign var='year' value=$YearProducts.$id}
         <TR>
           <TD CLASS=list>
            {$product->brand} {$product->model}
           </TD>
           <TD CLASS=list>
            {$year->hits+0}
           </TD>
           <TD CLASS=list>
            {$month->hits+0}
           </TD>
           <TD CLASS=list>
            {$week->hits+0}
           </TD>
           <TD CLASS=list>
            {$yesterday->hits+0}
           </TD>
           <TD CLASS=list>
            {$today->hits+0}
           </TD>
         </TR>
       {/foreach}
       </TABLE>
     </TD>
  </TR>
  <TR>
      <TD>
        <INPUT TYPE=BUTTON VALUE='{$Lang->GO_TO_MAIN_PAGE}' onclick="window.document.location='index.php';">
      </TD>
   </TR>
 </TABLE>