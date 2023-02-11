{config_load file=premium.conf}
<TABLE WIDTH=100% BORDER=0 CELLPADDING=5 CELLSPACING=0>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=subheader colspan=3>
      <a href='index.php'>{$Settings->site_name}</a> / <a href='index.php?section=Sections&menu={$Menu->menu_id}'>{$Menu->name}</a> / {$Lang->NEW_SECTION}
    </TD>
  </TR>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=header>
      <img src='images/menu_icon.gif' border=0 align=absmiddle> {$Lang->NEW_SECTION}
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
              {$Lang->SECTION_TYPE}:
            </TD>
            <TD>
              <SELECT name=service_id>
              {foreach name=service_type key=key item=item from=$Services}
                {if $Section->service_id EQ $item->service_id}
                  <OPTION VALUE='{$item->service_id}' SELECTED>{$item->name|escape}</OPTION>
                {else}
                  <OPTION VALUE='{$item->service_id}'>{$item->name|escape}</OPTION>
                {/if}
              {/foreach}
              </SELECT>
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