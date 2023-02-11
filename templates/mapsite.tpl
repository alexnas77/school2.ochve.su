<TABLE WIDTH=100% BORDER=0 CELLPADDING=10 CELLSPACING=0>
  <TR>
    <TD  ALIGN=LEFT VALIGN=TOP>
      <H1>{$Section->name}</H1>
    </TD>
  </TR>
  <TR>
    <TD HEIGHT=10>
    </TD>
  </TR>
  <TR>
    <TD ALIGN=LEFT>
    <ol>
  {foreach name=mainbody item=item from=$Mapsite}
      <li><B><A TITLE='{$item->name}' HREF='{if $item->alturl}{$item->alturl}{else}{$item->url}{/if}{$item->ext}' CLASS=subheader>{$item->name}</A></B><br /><br />
  {/foreach}
    </ol>
   </TD>
  </TR>
</TABLE>