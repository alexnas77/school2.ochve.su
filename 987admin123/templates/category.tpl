{config_load file=premium.conf}
<TABLE WIDTH=100% BORDER=0 CELLPADDING=5 CELLSPACING=0>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=subheader colspan=3>
      <a href='index.php'>{$Settings->site_name}</a> /
      <a href=index.php?section=ProductCategories>{$Lang->CATEGORIES}</a>
      {if $Item->category_id}
      / {$Item->name}
      {else}
      /  {$Lang->NEW_CATEGORY}
      {/if}
    </TD>
  </TR>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=header>
      <img src='images/categories_icon.gif' border=0 align=absmiddle>
       {if $Item->category_id}
        {$Item->name}
      {else}
        {$Lang->NEW_CATEGORY}
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
      <FORM NAME=CATEGORY METHOD=POST enctype='multipart/form-data'>
        <TABLE BORDER=0 CELLPADDING=5 CELLSPACING=0>
          <TR>
            <TD>
              {$Lang->CATEGORY}:
            </TD>
            <TD>
              <INPUT NAME=name TYPE=TEXT SIZE=80 MAXLENGTH=255 VALUE='{$Item->name|escape}' {literal}pattern='^.{1,255}$'{/literal}  notice='{$Lang->ENTER_NAME}'>
            </TD>
          </TR>
          <TR>
            <TD>
              {$Lang->TITLE}:
            </TD>
            <TD>
              <INPUT NAME=title TYPE=TEXT SIZE=80 MAXLENGTH=255 VALUE='{$Item->title|escape}'>
            </TD>
          </TR>
          <TR>
            <TD>
              {$Lang->DESCRIPTION}:
            </TD>
            <TD>
              {*<INPUT NAME=description TYPE=TEXT SIZE=80 MAXLENGTH=255 VALUE='{$Item->description|escape}'>*}
              <textarea name=description rows=5 cols=80>{$Item->description|escape}</textarea>
            </TD>
          </TR>
          <TR>
            <TD>
              {$Lang->PARENT_CATEGORY}:
            </TD>
            <TD>
              <select name=parent>
                <option value=0>{$Lang->ROOT_CATEGORY}</option>
                {include file=cat_option.tpl Categories=$Categories level=0}
              </select>
            </TD>
          </TR>          <TR>
            <TD>
              {$Lang->ALIES}:
            </TD>
            <TD>
              {foreach from=$Item->aly item=aly key=key}
              <INPUT id={$key} NAME=aly[] TYPE=TEXT SIZE=80 MAXLENGTH=255 VALUE='{$aly}'>
              <a href='#' onclick="javascript: window.document.getElementById('{$key}').value='';"><img src='images/delete.gif' border=0 width=16 height=16 align=middle></a><br />
              {/foreach}
              <INPUT NAME=aly[] TYPE=TEXT SIZE=80 MAXLENGTH=255 VALUE=''><br />
              <INPUT NAME=aly[] TYPE=TEXT SIZE=80 MAXLENGTH=255 VALUE=''><br />
              <INPUT NAME=aly[] TYPE=TEXT SIZE=80 MAXLENGTH=255 VALUE=''><br />
              <INPUT NAME=aly[] TYPE=TEXT SIZE=80 MAXLENGTH=255 VALUE=''><br />
              <INPUT NAME=aly[] TYPE=TEXT SIZE=80 MAXLENGTH=255 VALUE=''><br />
            </TD>
          </TR>
          <TR>
            <TD>
              {$Lang->CAT_ENABLED}:
            </TD>
            <TD>
              <INPUT NAME=enabled TYPE=checkbox {if $Item->enabled}checked{/if} value='1'>
            </TD>
          </TR>
           {if $Item->filename}
           <TR>
            <TD>
              {$Lang->PHOTO} :
            </TD>
            <TD>
              <img src="../foto/icons/{$Item->filename}" height="100" alt="" border="0">
              <input type=hidden value='' name=delete_fotos>
            <a href='{$Delete_get}'><img src='images/delete.gif' alt="Удалить иконку" border=0 width=16 height=16 align=middle></a>
            </TD>
          </TR>
        {else}
           <TR>
            <TD>
              {$Lang->PHOTO} :
            </TD>
            <TD>
              <INPUT NAME=fotos TYPE=FILE SIZE=70>
            </TD>
          </TR>
        {/if}
          <TR>
            <TD>
            </TD>
            <TD>
              <INPUT TYPE=SUBMIT VALUE='{$Lang->SAVE}'>
              <INPUT TYPE=BUTTON VALUE='{$Lang->BACK}' onclick='window.history.back();'>
            </TD>
          </TR>
        </TABLE>
      </FORM>
    </TD>
  </TR>
</TABLE>