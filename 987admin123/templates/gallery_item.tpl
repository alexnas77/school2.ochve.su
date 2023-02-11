{config_load file=premium.conf}
<TABLE WIDTH=100% BORDER=0 CELLPADDING=5 CELLSPACING=0>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=subheader colspan=3>
      <a href='index.php'>{$Settings->site_name}</a> / <a href='index.php?section=Gallery'>{$Lang->GALLERY}</a> /
      {if $Item->article_id}
        {$Lang->EDIT_FOTO}
      {else}
        {$Lang->NEW_FOTO}
      {/if}
    </TD>
  </TR>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=header>
      <img src='images/articles_icon.png' border=0 align=absmiddle>
      {if $Item->article_id}
        {$Lang->EDIT_FOTO}
      {else}
        {$Lang->NEW_FOTO}
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
              {$Lang->PHOTO}:
            </TD>
            <TD>
              <INPUT NAME=foto TYPE=FILE SIZE=70>
            </TD>
          </TR>
          {if $Item->filename}
           <TR>
            <TD>
             <a href='{$Uploaddir}{$Item->filename}' target=_blank><img id=image src='{$Uploaddir}{$Item->filename}' width=60 border=1 align=middle></a>
            </TD>
            <TD>
              &nbsp;
            </TD>
          </TR>
          {/if}
          <TR>
            <TD align=right>
              {$Lang->ACTIVE}:
            </TD>
            <TD name=body>
              <input name="enabled" type="checkbox" value="ON" {if $Item->enabled}checked{/if}>
            </TD>
          </TR>
          <TR>
            <TD>
            </TD>
            <TD>
              <INPUT NAME=SUBMIT TYPE=SUBMIT VALUE='{$Lang->SAVE}'>
            </TD>
          </TR>
        </TABLE>
      </FORM>
    </TD>
  </TR>
</TABLE>