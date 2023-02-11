{config_load file=premium.conf}

<TABLE WIDTH=100% BORDER=0 CELLPADDING=5 CELLSPACING=0>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=subheader colspan=3>
      <a href='index.php'>{$Settings->site_name}</a> / {$Lang->STATISTICS}
    </TD>
  </TR>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=header colspan=3>
      <img src='images/stat_icon.gif' border=0 align=absmiddle> {$Lang->STATISTICS}
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

       {foreach name=categories item=category from=$Categories}
       {assign var='id' value=$category->category_id}
       {assign var='today' value=$TodayCategories.$id}
       {assign var='yesterday' value=$YesterdayCategories.$id}
       {assign var='week' value=$WeekCategories.$id}
       {assign var='month' value=$MonthCategories.$id}
       {assign var='year' value=$YearCategories.$id}
         <TR>
           <TD CLASS=list>
            <a href=index.php?section=Stat&category_id={$category->category_id}>{$category->name}</a>
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
        <form method=post>
          <INPUT TYPE=submit VALUE='{$Lang->CLEAR_STATISTICS}'>
          <select name=age>
            <option value='365'>{$Lang->OLDER_THAN_YEAR}</option>
            <option value='30'>{$Lang->OLDER_THAN_MONTH}</option>
            <option value='7'>{$Lang->OLDER_THAN_WEEK}</option>
            <option value='1'>{$Lang->OLDER_THAN_DAY}</option>
            <option value='0'>{$Lang->CLEAR_ALL}</option>
          </select>
        </form>
      </TD>
   </TR>
 </TABLE>