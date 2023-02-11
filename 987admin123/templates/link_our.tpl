{config_load file=premium.conf}
<TABLE WIDTH=100% BORDER=0 CELLPADDING=5 CELLSPACING=0>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=subheader colspan=3>
      <a href='index.php'>{$Settings->site_name}</a> / <a href='index.php?section=OurLinks'>{$Lang->OUR_LINKS}</a> /
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
      <FORM id=qwe name=edit_news_item METHOD=POST>
        <TABLE BORDER=0 CELLPADDING=5 CELLSPACING=0 WIDTH="100%">
           <TR>
            <TD WIDTH="20%">
              {$Lang->BACK_LINK} текст:
            </TD>
            <TD>
              <textarea NAME=my_link rows=5 cols=20 wrap="on" style="width: 100%; height: 200px;">{$Item->my_link|escape}</textarea>
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