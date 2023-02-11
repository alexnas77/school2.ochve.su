{config_load file=premium.conf}
<TABLE WIDTH=100% BORDER=0 CELLPADDING=5 CELLSPACING=0>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=subheader colspan=3>
      <a href='index.php'>{$Settings->site_name}</a> / <a href='index.php?section=NewsLine'>{$Lang->NEWS}</a> /
      {if $Item->news_id}
        {$Lang->EDIT_NEWS_ITEM}
      {else}
        {$Lang->NEW_NEWS_ITEM}
      {/if}

    </TD>
  </TR>

  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=header>
      <img src='images/news_icon.gif' border=0 align=absmiddle>
      {if $Item->news_id}
        {$Lang->EDIT_NEWS_ITEM}
      {else}
        {$Lang->NEW_NEWS_ITEM}
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
              {$Lang->DATE}:
            </TD>
            <TD>
              <INPUT NAME=date TYPE=TEXT SIZE=80 MAXLENGTH=255 VALUE='{if $Item->date2}{$Item->date2|escape}{else}{$smarty.now|date_format:"%d.%m.%Y"}{/if}' pattern='^\d\d\.\d\d\.\d\d\d\d$' notice='{$Lang->ENTER_CORRECT_DATE}'style="width:150px;" onfocus="showCalendar('',this,this,'','holder',5,5,1)"><img style='position:relative;right:23;top:0' border=0 src=calendar/calendar.gif>&nbsp;&nbsp;</nobr>
            </TD>
          </TR>
          <TR>
            <TD>
              {$Lang->TITLE}:
            </TD>
            <TD>
              <INPUT NAME=title TYPE=TEXT SIZE=80 MAXLENGTH=255 VALUE='{$Item->title|escape}' pattern='string' notice='{$Lang->ENTER_TITLE}'>
            </TD>
          </TR>
          <TR>
            <TD>
              {$Lang->KEYWORDS}:
            </TD>
            <TD>
              <INPUT NAME=keywords TYPE=TEXT SIZE=80 MAXLENGTH=255 VALUE='{$Item->keywords|escape}'>
            </TD>
          </TR>
          <TR>
            <TD>
              {$Lang->DESCRIPTION}:
            </TD>
            <TD>
              <INPUT NAME=description TYPE=TEXT SIZE=80 MAXLENGTH=255 VALUE='{$Item->description|escape}'>
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
              {$Lang->NEWS_TEXT}:
            </TD>
            <TD name=body>
               <textarea id="editor" name="body" style="width: 100%; height: 400px;">{$Item->body}</textarea>
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