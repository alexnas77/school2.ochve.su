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
  {foreach name=mainbody item=item from=$Articles}
  <TR>
    <TD ALIGN=LEFT>
      <B><A TITLE='{$item->title}' HREF='articles/{$item->article_id}.html' CLASS=subheader style="text-decoration:underline;">{$item->title}</A></B>
    </TD>
  </TR>
  <TR>
    <TD ALIGN=LEFT VALIGN=top CLASS=body>
        {$item->annotation}
    </TD>
  </TR>
  {/foreach}
</TABLE>