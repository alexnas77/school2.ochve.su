{config_load file=premium.conf}
<TABLE WIDTH=100% BORDER=0 CELLPADDING=5 CELLSPACING=0>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=subheader colspan=3>
      <a href='index.php'>{$Settings->site_name}</a> / <a href='index.php?section=Faq'>{$Lang->FAQ}</a> /
        {$Lang->ANSWER} на вопрос &laquo;{$Item->id}&raquo;
    </TD>
  </TR>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=header>
      <img src='images/poll_icon.png' border=0 align=absmiddle>
       {$Lang->ANSWER} на вопрос &laquo;{$Item->id}&raquo;
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
              {$Lang->FNAME}:
            </TD>
            <TD>
              <INPUT NAME=name TYPE=TEXT SIZE=80 MAXLENGTH=255 VALUE='{$Item->name|escape}'>
            </TD>
          </TR>
          <TR>
            <TD>
              {$Lang->CITY}:
            </TD>
            <TD>
              <INPUT NAME=city TYPE=TEXT SIZE=80 MAXLENGTH=255 VALUE='{$Item->city|escape}'>
            </TD>
          </TR>
          <TR>
            <TD>
              {$Lang->MAIL}:
            </TD>
            <TD>
              <INPUT NAME=mail TYPE=TEXT SIZE=80 MAXLENGTH=255 VALUE='{$Item->mail|escape}'>
            </TD>
          </TR>
          <TR>
            <TD>
              {$Lang->PHONE}:
            </TD>
            <TD>
              <INPUT NAME=phone TYPE=TEXT SIZE=80 MAXLENGTH=255 VALUE='{$Item->phone|escape}'>
            </TD>
          </TR>
          <TR>
            <TD align=right>
              {$Lang->MESSAGE}:
            </TD>
            <TD name=body>
              <textarea name="message" style="width: 100%; height: 200px;">{$Item->message}</textarea>
            </TD>
          </TR>
          <TR>
            <TD align=right>
              {$Lang->ANSWER}:
            </TD>
            <TD name=body>
              <textarea id="editor" name="answer" style="width: 100%; height: 400px;">{$Item->answer}</textarea>
            </TD>
          </TR>
          <TR>
            <TD>
              {$Lang->FSHOW}:
            </TD>
            <TD>
              <input name=show value='1' type=checkbox {if $Item->show}checked{/if}>
            </TD>
          </TR>
          <TR>
            <TD>
              {$Lang->SEND}:
            </TD>
            <TD>
              <input name=send value='1' type=checkbox {if $Item->id}checked{/if}>
            </TD>
          </TR>
          <TR>
            <TD>
             <INPUT TYPE=RESET VALUE='—бросить'>
            </TD>
            <TD>
              <INPUT name=save TYPE=SUBMIT VALUE='{$Lang->SAVE}'>
            </TD>
          </TR>
        </TABLE>
      </FORM>
    </TD>
  </TR>
</TABLE>