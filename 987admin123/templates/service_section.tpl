{config_load file=premium.conf}
<TABLE WIDTH=100% BORDER=0 CELLPADDING=5 CELLSPACING=0>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=subheader colspan=3>
      <a href='index.php'>{$Settings->site_name}</a> / <a href='index.php?section=Sections&menu={$Menu->menu_id}'>{$Menu->name}</a> / {$Lang->EDIT_SECTION} &laquo;{$Section->name|escape}&raquo;
    </TD>
  </TR>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=header>
      <img src='images/menu_icon.gif' border=0 align=absmiddle> {$Lang->SectionEdit} &laquo;{$Section->name|escape}&raquo;
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
            <TD>
              {$Lang->NAME}:
            </TD>
            <TD>
              <INPUT NAME=name TYPE=TEXT SIZE=80 MAXLENGTH=255 VALUE='{$Section->name|escape}' {literal}pattern='^.{1,255}$'{/literal}  notice='{$Lang->ENTER_SECTION_NAME}'>
            </TD>
          </TR>
          <TR>
            <TD>
              {$Lang->PAGE_URL}:
            </TD>
            <TD>
              http://{php}print $_SERVER['HTTP_HOST'];{/php}/ <INPUT NAME=url TYPE=TEXT SIZE=30 MAXLENGTH=255 VALUE='{$Section->url|escape}' {literal}pattern='^.{1,255}$'{/literal}  notice='{$Lang->ENTER_PAGE_URL}'> .html
            </TD>
          </TR>
          <TR>
            <TD>
            </TD>
            <TD>
              <INPUT NAME=id TYPE=HIDDEN VALUE='{$Section->id}'>
              <INPUT TYPE=SUBMIT VALUE='{$Lang->SAVE}'>
            </TD>
          </TR>
        </TABLE>
      </FORM>
    </TD>
  </TR>
</TABLE>