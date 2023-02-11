{config_load file=premium.conf}
<TABLE WIDTH=100% BORDER=0 CELLPADDING=5 CELLSPACING=0>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=subheader colspan=3>
      <a href='index.php'>{$Settings->site_name}</a> / <a href='index.php?section=Storefront'>{$Lang->PRODUCTS}</a> /
      {if $Item->product_id}
        {$Lang->EDIT_PRODUCT} &laquo;{$Item->brand|escape} {$Item->model|escape}&raquo;
      {else}
        {$Lang->NEW_PRODUCT}
      {/if}
    </TD>
  </TR>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=header>
      <img src='images/storefront_icon.gif' border=0 align=absmiddle>
      {if $Item->product_id}
        {$Lang->EDIT_PRODUCT} &laquo;{$Item->brand|escape} {$Item->model|escape}&raquo;
      {else}
        {$Lang->NEW_PRODUCT}
      {/if}
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
    <TD ALIGN=LEFT VALIGN=TOP CLASS=body>
      <FORM name=product METHOD=POST enctype='multipart/form-data'>
        <TABLE BORDER=0 CELLPADDING=5 CELLSPACING=0>
          <TR>
            <TD>
              {$Lang->CATEGORY}:
            </TD>
            <TD>
              <SELECT name=category_id{* onchange="document.product.submit();"*}>
              {foreach name=Categories key=key item=category from=$Categories}
                {if $category->category_id EQ $Item->category_id}
                  <OPTION VALUE='{$category->category_id}' SELECTED>{$category->name|escape}</OPTION>
                {else}
                  <OPTION VALUE='{$category->category_id}'>{$category->name|escape}</OPTION>
                {/if}
                {foreach name=SubCategories key=key item=subcategory from=$category->subcategories}
                  {if $subcategory->category_id EQ $Item->category_id}
                    <OPTION VALUE='{$subcategory->category_id}' SELECTED>{$category->name|escape} | {$subcategory->name|escape}</OPTION>
                  {else}
                    <OPTION VALUE='{$subcategory->category_id}'>{$category->name|escape} | {$subcategory->name|escape}</OPTION>
                  {/if}
                {/foreach}
              {/foreach}
              </SELECT>
            </TD>
          </TR>
            <TR>
                <TD>
                    {$Lang->MODEL}:
                </TD>
                <TD>
                    <INPUT NAME=model TYPE=TEXT SIZE=80 MAXLENGTH=255 VALUE='{$Item->model|escape}'>
                </TD>
            </TR>
          {*<TR>
            <TD>
              {$Lang->MODEL_CODE}:
            </TD>
            <TD>
              <INPUT NAME=code TYPE=TEXT SIZE=80 MAXLENGTH=255 VALUE='{$Item->code|escape}'>
            </TD>
          </TR>
          <TR>
            <TD>
              {$Lang->PRICE}:
            </TD>
            <TD>
              <INPUT NAME=price TYPE=TEXT SIZE=80 MAXLENGTH=255 VALUE='{$Item->price|escape}'>
            </TD>
          </TR>
          <TR>
            <TD>
              {$Lang->CURRENCY}:
            </TD>
            <TD>
              <select name=currency_id>
    			{if $Item->currency_id == ''}
   				{html_options options=$Currency_names selected=$MainCurrency->code}
    			{else}
    			{html_options options=$Currency_names selected=$Item->currency_id}
    			{/if}
    		  </select>
            </TD>
          </TR>*}
          <TR>
            <TD>
              {$Lang->ACTIVE}:
            </TD>
            <TD>
              <INPUT NAME=enabled TYPE=checkbox {if $Item->enabled}checked{/if} value='1'>
            </TD>
          </TR>
          <TR>
            <TD align=right>
              {$Lang->SHORT_DESCRIPTION}:
            </TD>
            <TD name=body>
       <textarea id="editor_s" name="description" style="width: 100%; height: 400">{$Item->description}</textarea>
            </TD>
          </TR>
          <TR>
            <TD align=right>
              {$Lang->FULL_DESCRIPTION}:
            </TD>
            <TD name=body>
       <textarea id="editor" name="body" style="width: 100%; height: 400">{$Item->body}</textarea>
            </TD>
          </TR>

          {assign var="group" value=0}
     {foreach name=Properties key=key item=property from=$Properties}
  {if $group neq $property->category_id}
  {assign var="group" value=$property->category_id}
  <TR>
  <TD class=list colspan="10">
  <div align="center">
    <b>{$group_name.$group}</b>
    </div>
    </TD>
  </TR>
  {/if}
          <TR>

            <TD align=right>
              {$property->label}:
            </TD>
            <TD>
          {if $property->type eq 'int'}
          <input name="{$property->name}" type="text" SIZE=80 MAXLENGTH=255 value="{$Item->properties[$key]}">
          {/if}
          {if $property->type eq 'float'}
          <input name="{$property->name}" type="text" SIZE=80 MAXLENGTH=255 value="{$Item->properties[$key]}">
          {/if}
          {if $property->type eq 'set'}
          {html_options name=$property->name values=$property->options selected=$Item->properties[$key] output=$property->options}
          {/if}
          {if $property->type eq 'text'}
          <input name="{$property->name}" type="text" SIZE=80 MAXLENGTH=255 value="{$Item->properties[$key]}">
          {/if}
            </TD>

          </TR>
          {/foreach}
          <TR>
            <TD>
            </TD>
            <TD>
              <INPUT TYPE=SUBMIT  NAME=SUBMIT VALUE='{$Lang->SAVE}'>
              <INPUT TYPE=BUTTON VALUE='{$Lang->BACK}' onclick='window.history.back();'>
            </TD>
          </TR>

          <input type=hidden value='' name=delete_fotos>
          {section name=foto loop=$FotosNum start=0}
          {assign var="i" value=$smarty.section.foto.index}
          {assign var="fotos" value=$Item->fotos}
          {assign var="foto" value=$fotos[$i]}
          <TR>
            <TD>
              {$Lang->PHOTO} {$i+1}:
              {if $foto && $Item->product_id}<a href='../foto/storefront/{$foto}' target=_blank><img id=image_{$i} src='../foto/storefront/{$foto}' width=60 border=1 align=middle></a>
              {else}<img id=image_{$i} src='images/no_foto.gif' width=60 height=40 border=1 align=middle>{/if}
              <a href='javascript:;' onclick="javascript: window.document.getElementById('image_{$i}').src='images/no_foto.gif'; window.document.product.delete_fotos.value += '{$i},';"><img src='images/delete.gif' border=0 width=16 height=16 align=middle></a>
            </TD>
            <TD>
              <INPUT NAME=fotos[{$i}] TYPE=FILE SIZE=70>
            </TD>
          </TR>
          {/section}
          <TR>
            <TD>
            </TD>
            <TD>
              <INPUT TYPE=SUBMIT NAME=SUBMIT VALUE='{$Lang->SAVE}'>
              <INPUT TYPE=BUTTON VALUE='{$Lang->BACK}' onclick='window.history.back();'>
            </TD>
          </TR>


        </TABLE>
      </FORM>
    </TD>
  </TR>
</TABLE>