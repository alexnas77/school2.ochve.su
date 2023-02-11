{config_load file=premium.conf}
<FORM METHOD=POST NAME=products>
<TABLE WIDTH=100% BORDER=0 CELLPADDING=5 CELLSPACING=0>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=subheader colspan=3>
      <a href='index.php'>{$Settings->site_name}</a> / {$Lang->INDEX_SEARCH}
    </TD>
  </TR>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=header>
       <img src='images/storefront_icon.gif' border=0 align=absmiddle> {$Lang->INDEX_SEARCH}
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
              <a href=index.php?section=Index_Search><nobr>{$Lang->ALL}</nobr></a> |
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
              <a href=index.php?section=Index_Search&category={$CurrentCategory->category_id}><nobr>{$Lang->ALL}</nobr></a> |
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
         function check_search(checked)
         {
           var checkboxes = window.document.getElementsByName('search[]');
           var num = checkboxes.length;
           for(var i=0; i<num; i++)
           {
             checkboxes[i].checked = checked;
           }
         }
         </script>
      {/literal}
      {$Lang->SEARCH}
         <input type=checkbox onclick='check_search(this.checked);'>
    </TD>
   {if $smarty.request.category neq ''}
    <TD class=listheader WIDTH=50 align=center>
      {$Lang->SEARCH_TYPE}
    </TD>
   {/if}
     </TR>
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
      <input size=5 type=checkbox name=search[] {if $item->search eq 'true'}checked{/if} value='{$item->property_id}'>
      {/if}
    </TD>
    {if $smarty.request.category neq ''}
    <TD class=list align=center>
      {if $item->hide neq 'true'}
      {if $item->type eq 'set'}
      {assign var=m value=3}
      {else}
      {assign var=m value=$item->mode}
      {/if}
      {if $item->type eq 'text'}
      {assign var=m value=3}
      {/if}
      <SELECT name=mode[{$item->property_id}]>
      {html_options selected=$m options=$Types}
      </SELECT>
      {/if}
    </TD>
    {/if}
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
            <input type=hidden name=act value=''>
            <INPUT TYPE=SUBMIT NAME=SUBMIT VALUE='{$Lang->SAVE_CHANGES}' onclick='window.document.products.act.value="enable"; window.document.products.submit();'>

         </TD>
        </TR>
      </TABLE>
    </TD>
  </TR>
</TABLE>
</FORM>