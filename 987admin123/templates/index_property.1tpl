{config_load file=premium.conf}
<FORM METHOD=POST NAME=products>
<TABLE WIDTH=100% BORDER=0 CELLPADDING=5 CELLSPACING=0>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=subheader colspan=3>
      <a href='index.php'>{$Settings->site_name}</a> / {$Lang->PROPERTIES}
    </TD>
  </TR>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=header>
       <img src='images/storefront_icon.gif' border=0 align=absmiddle> {$Lang->PROPERTIES}
    </TD>
  </TR>
  <TR>
    <TD HEIGHT=10>
    </TD>
  </TR>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=body>
      <TABLE cellspacing=5>
        <TR>
          <TD valign=top>
           	{$Lang->CATEGORIES}:
          </TD>
          <TD valign=top>
          {if $CurrentCategory == ''}
              <b><nobr>{$Lang->ALL}</nobr></b> |
            {else}
              <a href=index.php?section=Index_properties><nobr>{$Lang->ALL}</nobr></a> |
            {/if}
      		{foreach name=categories item=category from=$Categories}
        		{if $category->category_id == $CurrentCategory->category_id}
        			<b><nobr>{$category->name}</nobr></b>
        		{else}
        			<a href=index.php{$category->url}><nobr>{$category->name}</nobr></a>
        		{/if}
        		{if not $smarty.foreach.categories.last}
          			|
        		{/if}
      		{/foreach}
      	  </TD>
        </TR>
        <TR>
          <TD valign=top>
          {if $Subcategories}
             {$Lang->SUBCATEGORIES}:
          </TD>
          <TD valign=top>
          {if $CurrentSubcategory == ''}
              <b><nobr>{$Lang->ALL}</nobr></b> |
            {else}
              <a href=index.php?section=Index_properties&category={$CurrentCategory->category_id}><nobr>{$Lang->ALL}</nobr></a> |
            {/if}
      		{foreach name=subcategories item=subcategory from=$Subcategories}
        		{if $subcategory->category_id == $CurrentSubcategory->category_id}
        			<b><nobr>{$subcategory->name}</nobr></b>
        		{else}
			        <a href=index.php{$subcategory->url}><nobr>{$subcategory->name}</nobr></a>
        		{/if}
        		{if not $smarty.foreach.subcategories.last}
          			|
        		{/if}
      		{/foreach}
      		{/if}
      	  </TD>
      	</TR>
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
            <a href='index.php{$CreatePropertyURL}'>{$Lang->NEW_PROPERTY}</a>
          </TD>
        </TR>
      </TABLE>
    </TD>
  </TR>
  <TR>
    <TD ALIGN=CENTER VALIGN=TOP CLASS=body>

<TABLE WIDTH=100% CLASS=list CELLPADDING=4 CELLSPACING=1>
  <TR>
    <TD class=listheader WIDTH=50>
      {$Lang->NAME}
    </TD>
    <TD class=listheader WIDTH=150 align=center>
      {$Lang->LABEL}
    </TD>
    <TD class=listheader WIDTH=50 align=center>
      {$Lang->TYPE}
    </TD>
    <TD class=listheader WIDTH=50 align=center>
      {$Lang->DEFAULT}
    </TD>
    <TD class=listheader WIDTH=50 align=center>
      {literal}
         <script>
         function check_enabled(checked)
         {
           var checkboxes = window.document.getElementsByName('enabled[]');
           var num = checkboxes.length;
           for(var i=0; i<num; i++)
           {
             checkboxes[i].checked = checked;
           }
         }
         </script>
      {/literal}
      {$Lang->ACTIVE}
      <input type=checkbox checked onclick='check_enabled(this.checked);'>

    </TD>
    <TD class=listheader WIDTH=45 align=center>
    </TD>
    <TD class=listheader WIDTH=30 align=center>
    </TD>
    <TD class=listheader WIDTH=50 align=center>
      {literal}
         <script>
         function check_delete(checked)
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
      {$Lang->DELETE}
      <input type=checkbox onclick='check_delete(this.checked);'>
    </TD>
  </TR>
  {if $Items}
     {foreach item=item from=$Items}
  <TR>
    <TD class=list>
      {$item->name|escape}
    </TD>
    <TD class=list>
      {$item->label|escape}
    </TD>
    <TD class=list>
      {$item->type|escape}
    </TD>
    <TD class=list align=center>
      {$item->default|escape}
    </TD>
    <TD class=list align=center>
      <input size=5 type=checkbox name=enabled[{$item->product_id}] {if $item->enabled}checked{/if} value='1'>
    </TD>
    <TD class=list align=center>
      {html_image file='images/arrow_up.gif' href=index.php`$item->move_up_get` alt=$Lang->MOVE_UP title=$Lang->MOVE_UP border=0}
      {html_image file='images/arrow_down.gif' href=index.php`$item->move_down_get` alt=$Lang->MOVE_DOWN title=$Lang->MOVE_DOWN border=0}
    </TD>
    <TD class=list align=center>
      {html_image file='images/edit.gif' href=index.php`$item->edit_get` alt=$Lang->EDIT title=$Lang->EDIT border=0}
    </TD>
    <TD class=list align=center>
       <input name=items[] value='{$item->product_id}' type=checkbox>
    </TD>
  </TR>
  {/foreach}
  {else}
  <TR>
      <TD class=list align=center colspan=10>
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
            <INPUT TYPE=SUBMIT VALUE='{$Lang->SAVE_CHANGES}'>
            <input type=hidden name=act value=''>
            <INPUT TYPE=button VALUE='{$Lang->DELETE_SELECTED}' onclick='if(!confirm("{$Lang->ARE_YOU_SURE_TO_DELETE}")) return false; window.document.products.act.value="delete"; window.document.products.submit();'>
          </TD>
        </TR>
      </TABLE>
    </TD>
  </TR>
</TABLE>
</FORM>