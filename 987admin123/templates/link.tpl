{config_load file=premium.conf}
<TABLE WIDTH=100% BORDER=0 CELLPADDING=5 CELLSPACING=0>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=subheader colspan=3>
      <a href='index.php'>{$Settings->site_name}</a> / <a href='index.php?section=Links'>{$Lang->LINKS}</a> /
      {if $Item->article_id}
        {$Lang->EDIT_LINK}
      {else}
        {$Lang->NEW_LINK}
      {/if}
    </TD>
  </TR>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=header>
      <img src='images/articles_icon.png' border=0 align=absmiddle>
      {if $Item->article_id}
        {$Lang->EDIT_LINK}
      {else}
        {$Lang->NEW_LINK}
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
      <FORM id=qwe name=edit_news_item METHOD=POST enctype='multipart/form-data'>
        <TABLE BORDER=0 CELLPADDING=5 CELLSPACING=0>
          <TR>
            <TD>
              {$Lang->URL}:
            </TD>
            <TD>
              <INPUT NAME=path TYPE=TEXT SIZE=80 VALUE='{$Item->path|escape}'{* pattern='http\:\/\/[a-zA-Z0-9\-_]+\.[a-zA-Z0-9\-_]+\/?' notice='{$Lang->ENTER_OTHER_PATH}'*}>
            </TD>
          </TR>
          <TR>
            <TD>
              {$Lang->TITLE}:
            </TD>
            <TD>
              <INPUT NAME=title TYPE=TEXT SIZE=80 MAXLENGTH=255 VALUE='{$Item->title|escape}'{* {literal}pattern='.{1,255}'{/literal} notice='{$Lang->ENTER_TITLE}'*}>
            </TD>
          </TR>
          <TR>
            <TD align=right>
              {$Lang->ANNOTATION}:
            </TD>
            <TD name=body>
              <textarea id="editor_s" name="annotation" style="width: 100%; height: 200px;">{$Item->annotation}</textarea>
            </TD>
          </TR>
          <TR>
            <TD align=right>
              {$Lang->CATEGORY}:
            </TD>
            <TD name=body>
              {*html_options name=art_cat options=$Art_cats selected=$Item->art_cat*}
              <SELECT name="art_cat">
              {foreach name=art_cats key=key item=art_cat from=$Art_cats}
                {if $art_cat->category_id EQ $Item->art_cat}
                  <OPTION VALUE='{$art_cat->category_id}' SELECTED>{$art_cat->name|escape}</OPTION>
                {else}
                  <OPTION VALUE='{$art_cat->category_id}'>{$art_cat->name|escape}</OPTION>
                {/if}
                {foreach name=SubCategories key=key item=subart_cat from=$art_cat->subcategories}
                  {if $subart_cat->category_id EQ $Item->art_cat}
                    <OPTION VALUE='{$subart_cat->category_id}' SELECTED>{$art_cat->name|escape} | {$subart_cat->name|escape}</OPTION>
                  {else}
                    <OPTION VALUE='{$subart_cat->category_id}'>{$art_cat->name|escape} | {$subart_cat->name|escape}</OPTION>
                  {/if}
                {/foreach}
              {/foreach}
              </SELECT>
            </TD>
          </TR>
          <TR>
            <TD>
              {$Lang->BACK_LINK}:
            </TD>
            <TD>
              <INPUT NAME=backlink TYPE=TEXT SIZE=80 MAXLENGTH=255 VALUE='{$Item->backlink|escape}'{* {literal}pattern='.{1,255}'{/literal} notice='{$Lang->ENTER_TITLE}'*}>
            </TD>
          </TR>
          <TR>
            <TD>
              {$Lang->BACK_LINK} текст:
            </TD>
            <TD>
              <textarea NAME=my_link rows=5 cols=20 wrap="on" style="width: 100%; height: 200px;">{$Item->my_link|escape}</textarea>
            </TD>
          </TR>
          <TR>
            <TD>
              Email:
            </TD>
            <TD>
              <INPUT NAME=email TYPE=TEXT SIZE=80 MAXLENGTH=255 VALUE='{$Item->email|escape}'{* {literal}pattern='.{1,255}'{/literal} notice='{$Lang->ENTER_TITLE}'*}>
            </TD>
          </TR>
          <TR>
            <TD align=right>
              Изображение(Скриншот) ссылки:
            </TD>
            <TD name=body>
           {if $Item->image}
           <a target="_blank" href="{$Uploaddir}{$Item->image}"><img src="{$Uploaddir}{$Item->image}" width="150" title="" alt="{$Item->image}" border="0"></a>
           <a href='{$Delete_get}'><img src='images/delete.gif' alt="Удалить иконку" border=0 width=16 height=16 align=middle></a>
           <br />
           {/if}
           <input type=file SIZE=80 name=fotos>
            </TD>
          </TR>
          <TR>
            <TD align=right>
              {$Lang->ACTIVE}:
            </TD>
            <TD name=body>
              <input name="enabled" type="checkbox" value="ON" {if $Item->enabled || !$Item->article_id}checked{/if}>
            </TD>
          </TR>
          <TR>
            <TD align=right>
              {$Lang->USE_BACK}:
            </TD>
            <TD name=body>
              <input name="main" type="checkbox" value="ON" {if $Item->main}checked{/if} {if !$Item->main && !$Item->article_id}checked disabled{/if}>
            </TD>
          </TR>
          <TR>
            <TD>
            </TD>
            <TD>
              <INPUT TYPE=SUBMIT NAME=SUBMIT VALUE='{$Lang->SAVE}'>
            </TD>
          </TR>
        </TABLE>
      </FORM>
    </TD>
  </TR>
</TABLE>