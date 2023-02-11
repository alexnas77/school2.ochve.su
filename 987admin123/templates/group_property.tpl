{config_load file=premium.conf}
<TABLE WIDTH=100% BORDER=0 CELLPADDING=5 CELLSPACING=0>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=subheader colspan=3>
      <a href='index.php'>{$Settings->site_name}</a> /
      <a href=index.php?section=Properties_Categories>{$Lang->CATEGORIES}</a>
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
      <FORM METHOD=POST>
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
              {$Lang->CAT_ENABLED}:
            </TD>
            <TD>
              <INPUT NAME=enabled TYPE=checkbox {if $Item->enabled eq 'true'}checked{/if} value='true'>
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