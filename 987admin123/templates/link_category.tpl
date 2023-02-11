{config_load file=premium.conf}
<TABLE WIDTH=100% BORDER=0 CELLPADDING=5 CELLSPACING=0>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=subheader colspan=3>
      <a href='index.php'>{$Settings->site_name}</a> /
      <a href=index.php?section=LinksCategories>{$Lang->LINKS_CATEGORIES}</a>
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
              <INPUT NAME=name TYPE=TEXT SIZE=80 VALUE='{$Item->name|escape}' {literal}pattern='^.{1,255}$'{/literal}  notice='{$Lang->ENTER_NAME}'>
            </TD>
          </TR>
          <TR>
            <TD>
              {$Lang->TITLE}:
            </TD>
            <TD>
              <INPUT NAME=title TYPE=TEXT SIZE=80 VALUE='{$Item->title|escape}'>
            </TD>
          </TR>
          <TR>
            <TD>
              {$Lang->PATH}:
            </TD>
            <TD>
              <INPUT NAME=path TYPE=TEXT SIZE=80 VALUE='{$Item->path|escape}'>
            </TD>
          </TR>
          <TR>
            <TD>
              {$Lang->KEYWORDS}:
            </TD>
            <TD>
              <INPUT NAME=keywords TYPE=TEXT SIZE=80 VALUE='{$Item->keywords|escape}'>
            </TD>
          </TR>
          <TR>
            <TD>
              Description:
            </TD>
            <TD>
              <INPUT NAME=meta_description TYPE=TEXT SIZE=80 VALUE='{$Item->meta_description|escape}'>
            </TD>
          </TR>
          <TR>
            <TD align=right>
              {$Lang->DESCRIPTION}:
            </TD>
            <TD name=body>
              <textarea id="editor" name="description" style="width: 100%; height: 200px;">{$Item->description}</textarea>
            </TD>
          </TR>
          <TR>
            <TD>
              {$Lang->PARENT_CATEGORY}:
            </TD>
            <TD>
              <select name=parent>
                <option value=0>{$Lang->ROOT_CATEGORY}</option>
                {include file=link_option.tpl Categories=$Categories level=0}
              </select>
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
          <TR>
            <TD>
            </TD>
            <TD>
              <INPUT TYPE=SUBMIT VALUE='{$Lang->SAVE}'>
            </TD>
          </TR>
        </TABLE>
      </FORM>
    </TD>
  </TR>
</TABLE>