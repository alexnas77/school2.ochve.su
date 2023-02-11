{config_load file=premium.conf}
<TABLE WIDTH=100% BORDER=0 CELLPADDING=5 CELLSPACING=0>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=subheader colspan=3>
      <a href='index.php'>{$Settings->site_name}</a> / <a href='index.php?section=Contacts'>{$Lang->CONTACTS}</a> /
      {if $Item->contact_id}
        {$Lang->EDIT_CONTACT}&laquo;{$Item->contact_id}&raquo;
      {else}
        {$Lang->NEW_CONTACT}
      {/if}
    </TD>
  </TR>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=header>
      <img src='images/articles_icon.png' border=0 align=absmiddle>
      {if $Item->contact_id}
        {$Lang->EDIT_CONTACT}&laquo;{$Item->contact_id}&raquo;
      {else}
        {$Lang->NEW_CONTACT}
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
      <FORM id=qwe name=edit_news_item METHOD=POST>
        <TABLE BORDER=0 CELLPADDING=5 CELLSPACING=0>
          <TR>
            <TD>
              {$Lang->NAME}:
            </TD>
            <TD>
              <INPUT NAME=name TYPE=TEXT SIZE=80 MAXLENGTH=255 VALUE='{$Item->name|escape}'>
            </TD>
          </TR>
          <TR>
            <TD>
              {$Lang->NUMBER_ADRESS}:
            </TD>
            <TD>
              <INPUT NAME=number TYPE=TEXT SIZE=80 MAXLENGTH=255 VALUE='{$Item->number|escape}'>
            </TD>
          </TR>
          <TR>
            <TD>
              {$Lang->MODE}:
            </TD>
            <TD>
              <SELECT name=mode>
              {foreach name=Modes key=key item=mode from=$Modes}
                {if $key EQ $Item->mode}
                  <OPTION VALUE='{$key}' SELECTED>{$Modes.$key}</OPTION>
                {else}
                  <OPTION VALUE='{$key}'>{$Modes.$key}</OPTION>
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