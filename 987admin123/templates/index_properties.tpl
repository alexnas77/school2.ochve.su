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
  {if $ErrorMSG}
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP class=error>
      {$ErrorMSG}
    </TD>
  </TR>
  {/if}
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=body>
      <TABLE cellspacing=5>
        <TR>
        {if $smarty.request.section neq 'Edit_Index_property'}
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
      	  {/if}
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
          {if $smarty.request.section neq 'Edit_Index_property'}
            <input name="raws" type="hidden" value="1" size="1">&nbsp;
            <a href='index.php{$CreatePropertyURL}'>{$Lang->NEW_PROPERTY}</a>
          {/if}
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
      {$Lang->VALUE}
    </TD>
    <TD class=listheader WIDTH=50 align=center>
      {$Lang->DEFAULT}
    </TD>
    <TD class=listheader WIDTH=50 align=center>
      {literal}
         <script>
         function check_show(checked)
         {
           var checkboxes = window.document.getElementsByName('show[]');
           var num = checkboxes.length;
           for(var i=0; i<num; i++)
           {
             checkboxes[i].checked = checked;
           }
         }
         </script>
      {/literal}
      {$Lang->SHOW}

         <input type=checkbox onclick='check_show(this.checked);'>


    </TD>
   {if $smarty.request.section neq 'Edit_Index_property'}
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

         <input type=checkbox onclick='check_enabled(this.checked);'>

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
      {/if}

    </TD>
  </TR>
  {if $smarty.request.section neq 'Edit_Index_property'}
  {if $Items}
  {assign var="group" value=0}
     {foreach item=item from=$Items}
  {if $group neq $item->category_id}
  {assign var="group" value=$item->category_id}
  <TR>
  <TD class=list colspan="10">
  <div align="center">
    <b>{$group_name.$group}</b>
    </div>
    </TD>
  </TR>
  {/if}
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
    <TD class=list>
      {$item->value|escape}
    </TD>
    <TD class=list align=center>
      {$item->default|escape}
    </TD>
    <TD class=list align=center>
      {if $item->hide neq 'true'}
      <input size=5 type=checkbox name=show[] {if $item->show eq 'true'}checked{/if} value='{$item->property_id}'>
      {/if}
    </TD>
    <TD class=list align=center>
      {if $item->hide neq 'true'}
      <input size=5 type=checkbox name=enabled[] {if $item->enabled eq 'true'}checked{/if} value='{$item->property_id}'>
      {/if}
    </TD>
    <TD class=list align=center>
      {html_image file='images/arrow_up.gif' href=index.php`$item->move_up_get` alt=$Lang->MOVE_UP title=$Lang->MOVE_UP border=0}
      {html_image file='images/arrow_down.gif' href=index.php`$item->move_down_get` alt=$Lang->MOVE_DOWN title=$Lang->MOVE_DOWN border=0}
    </TD>
    <TD class=list align=center>
      {html_image file='images/edit.gif' href=index.php`$item->edit_get` alt=$Lang->EDIT title=$Lang->EDIT border=0}
    </TD>
    <TD class=list align=center>
       <input name=items[] value='{$item->property_id}' type=checkbox>
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
  {/if}
  {if $smarty.request.section eq 'Edit_Index_property'}
  <TR>
    <TD class=list>
      <input name="name" type="text" value="{$item->name|escape}" size="45">
    </TD>
    <TD class=list>
      <input name="label" type="text" value="{$item->label|escape}">
    </TD>
    <TD class=list>
      <SELECT name="type">
              {foreach name=types key=key item=type from=$types}
                {if $type EQ $item->type}
                  <OPTION VALUE='{$type}' SELECTED>{$type}</OPTION>
                {else}
                  <OPTION VALUE='{$type}'>{$type}</OPTION>
                {/if}
              {/foreach}
              </SELECT>
    </TD>
    <TD class=list align=center>
      <input name="value" type="text" value="{$item->value|escape}">
    </TD>
    <TD class=list align=center>
      <input name="default" type="text" value="{$item->default|escape}">
    </TD>

  </TR>
  <TR>
  <TD class=list>
  {$Lang->PROPERTIES_CATEGORIES}
  {html_options name="category_id" values=$group_id selected=$item->category_id output=$group_name }
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

            {if $smarty.request.section eq 'Edit_Index_property'}
            <INPUT TYPE=SUBMIT NAME=SUBMIT VALUE='{$Lang->SAVE_CHANGES}' onclick='window.document.products.act.value="enable"; window.document.products.submit();'>
            <INPUT TYPE=BUTTON VALUE='{$Lang->BACK}' onclick='window.history.back();'>
            {else}
            <INPUT TYPE=SUBMIT NAME=SUBMIT VALUE='{$Lang->SAVE_CHANGES}' onclick='window.document.products.act.value="enable"; window.document.products.submit();'>
            <input type=hidden name=act value=''>
            <INPUT TYPE=button VALUE='{$Lang->DELETE_SELECTED}' onclick='if(!confirm("{$Lang->ARE_YOU_SURE_TO_DELETE}")) return false; window.document.products.act.value="delete"; window.document.products.submit();'>
            {/if}
         </TD>
        </TR>
      </TABLE>
    </TD>
  </TR>
</TABLE>
</FORM>