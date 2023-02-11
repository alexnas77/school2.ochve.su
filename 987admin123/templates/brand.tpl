{config_load file=premium.conf}
<TABLE WIDTH=100% BORDER=0 CELLPADDING=5 CELLSPACING=0>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=subheader colspan=3>
      <a href='index.php'>{$Settings->site_name}</a> /
      <a href=index.php?section=Brands>{$Lang->BRAND}</a>
      {if $Item->brand_id}
      / {$Item->name}
      {else}
      /  {$Lang->NEW_CATEGORY}
      {/if}
    </TD>
  </TR>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=header>
      <img src='images/categories_icon.gif' border=0 align=absmiddle>
       {if $Item->brand_id}
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
              {$Lang->NAME}:
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
              <textarea name=description rows=5 cols=80>{$Item->description|escape}</textarea>
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