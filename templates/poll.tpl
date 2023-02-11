<h1>{$Poll->question}</h1>
<TABLE BORDER=0 CELLPADDING=5 CELLSPACING=0>
  <TR>
    <TD COLSPAN=2 CLASS=subheader>

    </TD>
  </TR>
  <TR>
    <TD CLASS=subheader>

    </TD>
    <TD CLASS=subheader>
      {$Message}
    </TD>
  </TR>
  {foreach name=mainbody item=answer from=$Poll->answers}
  <TR>
    <TD ALIGN=LEFT CLASS=subheader>
      {$answer->answer}
    </TD>
    <TD WIDTH=220 ALIGN=LEFT CLASS=subheader>
      <table cellpadding=0 cellspacing=0><tr><td height=10 width={$answer->points*200/$Poll->points|round} bgcolor=#B0B0B0></td></tr>
      </table>
    </TD>
    <TD ALIGN=LEFT CLASS=subheader>
      {$answer->points*100/$Poll->points|round} %
    </TD>
  </TR>
  <TR>
    <TD ALIGN=LEFT VALIGN=top CLASS=body>
        {$item->annotation}
    </TD>
  </TR>
  {/foreach}
  <TR>
    <TD CLASS=subheader>

    </TD>
    <TD CLASS=subheader>
      {$Poll->points} голосов
    </TD>
  </TR>

</TABLE>