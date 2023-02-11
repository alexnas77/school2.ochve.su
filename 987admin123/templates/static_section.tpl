{config_load file=premium.conf}
<TABLE WIDTH=100% BORDER=0 CELLPADDING=5 CELLSPACING=0>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=subheader colspan=3>
      <a href='index.php'>{$Settings->site_name}</a> / <a href='index.php?section=Sections&menu={$Menu->menu_id}'>{$Menu->name}</a> / {$Lang->EDIT_SECTION} &laquo;{$Section->name|escape}&raquo;
    </TD>
  </TR>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=header>
      <img src='images/menu_icon.gif' border=0 align=absmiddle> {$Lang->EDIT_SECTION} &laquo;{$Section->name|escape}&raquo;
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
      <FORM METHOD=POST>
        <TABLE BORDER=0 CELLPADDING=5 CELLSPACING=0>
          <TR>
            <TD align=right>
              {$Lang->NAME}:
            </TD>
            <TD>
              <INPUT NAME=name TYPE=TEXT SIZE=80 MAXLENGTH=255 VALUE='{$Section->name|escape}' {literal}pattern='^.{1,255}$'{/literal}  notice='{$Lang->ENTER_SECTION_NAME}'>
            </TD>
          </TR>
          <TR>
            <TD align=right>
              {$Lang->PAGE_URL}:
            </TD>
            <TD>
              http://{php}print $_SERVER['HTTP_HOST'];{/php}/ <INPUT NAME=url TYPE=TEXT SIZE=30 MAXLENGTH=255 VALUE='{$Section->url|escape}' {literal}pattern='^.{1,255}$'{/literal}  notice='{$Lang->ENTER_PAGE_URL}'> .html
            </TD>
          </TR>
          <TR>
            <TD align=right>
              {$Lang->TITLE}:
            </TD>
            <TD>
              <INPUT name=title TYPE=TEXT SIZE=80 VALUE='{$Section->title|escape}'>
            </TD>
          </TR>
          <TR>
            <TD align=right>
              {$Lang->DESCRIPTION}:
            </TD>
            <TD>
              <INPUT name=description TYPE=TEXT SIZE=80 VALUE='{$Section->description|escape}'>
            </TD>
          </TR>
          <TR>
            <TD align=right>
              {$Lang->KEYWORDS}:
            </TD>
            <TD>
              <INPUT name=keywords TYPE=TEXT SIZE=80 VALUE='{$Section->keywords|escape}'>
            </TD>
          </TR>
          <TR>
            <TD align=right>
              {$Lang->PAGE_TEXT}:
            </TD>
            <TD name=body>

<textarea {if !$smarty.get.notiny}id="editor"{/if} name="body" style="{if !$smarty.get.notiny}width: 100%;{else}width: 800px; {/if} height: 400">{$Section->body}</textarea>

            </TD>
          </TR>
<TR>
            <TD>
            </TD>
            <TD nowrap>
            <div align="left" style="float:left">
              <INPUT NAME=id TYPE=HIDDEN VALUE='{$Section->id}'>
              <INPUT TYPE=SUBMIT VALUE='{$Lang->SAVE}'>
              </div>
              <div align="right" style="width:400px">
            {if !$smarty.get.notiny}
            <a href="{php} Widget::add_param('section');  Widget::add_param('page'); Widget::add_param('menu'); Widget::add_param('item_id'); $get = Widget::form_get(array("notiny"=>"1")); print "index.php$get";{/php}{*$smarty.server.REQUEST_URI&notiny=1*}">Отключить визуальный редактор TINY_MCE</a>
            {else}
            <a href="{php} Widget::add_param('section');  Widget::add_param('page'); Widget::add_param('menu'); Widget::add_param('item_id'); $get = Widget::form_get(array("notiny"=>"")); print "index.php$get";{/php}{*$smarty.server.REQUEST_URI&notiny=*}">Включить визуальный редактор TINY_MCE</a>
            {/if}
              </div>
            </TD>
          </TR>
        </TABLE>
      </FORM>
    </TD>
  </TR>
</TABLE>