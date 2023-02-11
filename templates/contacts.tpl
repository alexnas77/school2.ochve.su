<NOINDEX>
<H1>{$Section->name}<H1>
<TABLE WIDTH=100% CELLPADDING=2 CELLSPACING=6 BORDER=0>
  {if $ICQs}
  <TR>
    <TD class=body>
      <B><u>ICQ</u></B><BR><br>
      {foreach key=key item=ICQ from=$ICQs}
      <IMG ALT='ICQ' ALIGN=ABSMIDDLE WIDTH=18 HEIGHT=18 SRC=http://web.icq.com/whitepages/online?icq={$ICQ->number}&img=5> <a href="http://www.icq.com/people/&searched=1&f=TypeOfSearch&uin={$ICQ->number}&online_only=off" style="color:black;" target="_blank">{$ICQ->number}</a> &nbsp;-&nbsp;<nobr>{$ICQ->name}</nobr><BR><br>
      {/foreach}
    </TD>
  </TR>
  {/if}
  {if $Tels}
  <TR>
    <TD class=body>
      <B><u>Телефоны</u></B><BR><br>
      {foreach key=key item=Tel from=$Tels}
      <nobr>{$Tel->number}</nobr> &nbsp;-&nbsp;<nobr>{$Tel->name}<nobr><BR><br>
      {/foreach}
    </TD>
  </TR>
  {/if}
  {if $Emails}
  <TR>
    <TD class=body>
      <B><u>E-mail</u></B><BR><br>
      {foreach key=key item=Email from=$Emails}
        {mailto address=$Email->number encode="javascript"}&nbsp;-&nbsp; <nobr>{$Email->name}<nobr><BR><br>
      {/foreach}
    </TD>
  </TR>
  {/if}
  {if $Scypes}
  <TR>
    <TD class=body>
      <B><u>Scype</u></B><BR><br>
      {foreach key=key item=Scype from=$Scypes}
        {$Scype->number}&nbsp;-&nbsp; <nobr>{$Scype->name}<nobr><BR><br>
      {/foreach}
    </TD>
  </TR>
  {/if}
</TABLE>
</NOINDEX>