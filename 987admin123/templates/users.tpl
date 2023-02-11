{config_load file=premium.conf}
<TABLE WIDTH=100% BORDER=0 CELLPADDING=5 CELLSPACING=0>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=subheader colspan=3>
      <a href='index.php'>{$Settings->site_name}</a> /{$Lang->USERS}
    </TD>
  </TR>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=header>
       <img src='images/users_icon.jpg' border=0 align=absmiddle> {$Lang->USERS}
    </TD>
  </TR>
  <TR>
    <TD HEIGHT=10>
    </TD>
  </TR>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=body>

<TABLE  CELLPADDING=4 CELLSPACING=1 style='background-color:#F0F0F0; border: 1 solid #E0E0E0'>
<FORM METHOD=GET>
  <TR>
    <TD>{$Lang->NAME}
    </TD>
    <TD>{$Lang->LOGIN}
    </TD>
    <TD>{$Lang->USERCATEGORY}
    </TD>
    <TD>
    </TD>
  </TR>
  <TR>
    <TD>
      <input type=text name=name value='{$smarty.get.name}'>
    </TD>
    <TD>
      <input type=text name=login  value='{$smarty.get.login}'>
    </TD>
    <TD>
      <select name=category_id>
        <option value=''>{$Lang->ALL}
        <option value='NULL' {if $smarty.get.category_id=='NULL'}selected{/if}>{$Lang->UNDEFINED_CATEGORY}
        {foreach from=$Categories item=category}
        <option value='{$category->category_id}' {if $smarty.get.category_id==$category->category_id}selected{/if}>{$category->name}
        {/foreach}
      </select>
    </TD>
    <TD>
      <input type=hidden name=section value=Users>
      <input type=submit value='{$Lang->APPLY_FILTER}'>
    </TD>
  </TR>
  </FORM>
</TABLE>

    </TD>
  </TR>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=body>
      <TABLE CELLPADDING=0 CELLSPACING=0 WIDTH=100%>
        <TR>
          <TD>
            {$PagesNavigation}
          </TD>
        </TR>
      </TABLE>
    </TD>
  </TR>
  <TR>
    <TD ALIGN=CENTER VALIGN=TOP CLASS=body>
<FORM METHOD=POST NAME=sections>
<TABLE WIDTH=100% CLASS=list CELLPADDING=4 CELLSPACING=1>
  <TR>
    <TD class=listheader WIDTH=20>
    </TD>
    <TD class=listheader WIDTH=150>
      {$Lang->USERNAME}
    </TD>
    <TD class=listheader>
      {$Lang->LOGIN}
    </TD>
    <TD class=listheader>
      {$Lang->USER_CATEGORY}
    </TD>
    <TD class=listheader>
      {$Lang->ADDRESS}
    </TD>
    <TD class=listheader>
      {$Lang->ORDERS_NUMBER}
    </TD>
    <TD class=listheader>
      {$Lang->ORDERS_HISTORY}
    </TD>
    <TD class=listheader WIDTH=25 align=center>
    </TD>
    <TD class=listheader WIDTH=25 align=center>
      {literal}
         <script>
         function check(checked)
         {
           var checkboxes = window.document.getElementsByName('items[]');
           var num = checkboxes.length;
           for(var i=0; i<num; i++)
           {
             checkboxes[i].checked = checked;
           }
         }
         </script>
      {/literal}
      <input type=checkbox onclick='check(this.checked);'>
    </TD>
  </TR>
  {if $Items}
     {foreach item=item from=$Items}
  <TR>
    <TD class=list align=center>
      {if $item->active}
        {html_image file='images/enabled.gif' href=index.php`$item->disable_get` alt=$Lang->USER_DISABLE title=$Lang->USER_DISABLE border=0}
      {else}
        {html_image file='images/disabled.gif' href=index.php`$item->enable_get` alt=$Lang->USER_ENABLE title=$Lang->USER_ENABLE border=0}
      {/if}
    </TD>
    <TD class=list>
      {$item->name|escape}
    </TD>
    <TD class=list>
      {$item->login|escape}
    </TD>
    <TD class=list>
    {*if $item->category}
      {$item->category|escape}
    {else}
      {$Lang->UNDEFINED_CATEGORY}
    {/if*}
          <select name=category[{$item->login}]>
        <option value='0' {if $item->category_id=='0'}selected{/if}>{$Lang->UNDEFINED_CATEGORY}</option>
        {foreach from=$Categories item=category}
        <option value='{$category->category_id}' {if $item->category_id==$category->category_id}selected{/if}>{$category->name}</option>
        {/foreach}
      </select>
    </TD>
    <TD class=list>
       <a href="mailto:{$item->mail}">{$item->mail}</a>
    </TD>
    <TD class=list>
       {$item->orders_num}
    </TD>
    <TD class=list>
       <a href='index.php?section=Orders&login={$item->login}'>{$Lang->ORDERS_HISTORY}</a>
    </TD>
    <TD class=list align=center>
      {html_image file='images/edit.gif' href=index.php`$item->edit_get` alt=$Lang->EDIT title=$Lang->EDIT border=0}
    </TD>
    <TD class=list align=center>
       <input name=items[] value='{$item->login}' type=checkbox>
    </TD>
  </TR>
  {/foreach}
  {else}
  <TR>
      <TD class=list align=center colspan=6>
         {$Lang->EMPTY_LIST}
      </TD>
  </TR>
  {/if}
</TABLE>
    </TD>
  </TR>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=body>
      <TABLE CELLPADDING=0 CELLSPACING=0 WIDTH=100%>
        <TR>
          <TD>
            {$PagesNavigation}
          </TD>
          <TD ALIGN=RIGHT>
          <INPUT NAME=SUBMIT TYPE=SUBMIT VALUE='{$Lang->SAVE_CHANGES}'>
            <INPUT TYPE=SUBMIT VALUE='{$Lang->DELETE_SELECTED}' onclick='if(!confirm("{$Lang->ARE_YOU_SURE_TO_DELETE}")) return false;'>
          </TD>
        </TR>
      </TABLE>
</FORM>
    </TD>
  </TR>
</TABLE>