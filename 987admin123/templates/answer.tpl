{config_load file=premium.conf}
<TABLE WIDTH=100% BORDER=0 CELLPADDING=5 CELLSPACING=0>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=subheader colspan=3>
      <a href='index.php'>{$Settings->site_name}</a> / <a href='index.php?section=Polls'>{$Lang->POLLS}</a> / <a href='index.php?section=Polls&poll_id={$Poll->poll_id}'>{$Poll->question}</a> / {if $Answer}{$Answer->answer}{else}{$Lang->NEW_ANSWER}{/if}
    </TD>
  </TR>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=header>
       <img src='images/poll_icon.png' border=0 align=absmiddle> {if $Answer}{$Answer->answer}{else}{$Lang->NEW_ANSWER}{/if}
    </TD>
  </TR>
  <TR>
    <TD HEIGHT=10>
    </TD>
  </TR>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=body>
      <TABLE WIDTH=100% CLASS=list CELLPADDING=4 CELLSPACING=1>
        <TR>
          <TD class=list>
            <form method=post>
               {$Lang->ANSWER}: <input size=100 type=text name=answer value='{if $Answer}{$Answer->answer}{/if}'><input type=submit value='{$Lang->SAVE}'>
            </form>
          </TD>
        </TR>
      </TABLE>
    </TD>
  </TR>
</TABLE>
