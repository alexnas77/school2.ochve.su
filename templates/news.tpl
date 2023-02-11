<h1>{$Section->name}</h1>
<TABLE WIDTH=100% BORDER=0 CELLPADDING=5 CELLSPACING=0>
  <TR>
    <TD COLSPAN=2 HEIGHT=10>
    </TD>
  </TR>
  {foreach name=mainbody item=item from=$News}
  <TR>
    <TD ALIGN=LEFT CLASS=subheader>
      <B CLASS=subheader><A TITLE='{$item->title}' HREF='news/{$item->news_id}.html' CLASS=subheader>{$item->date}, {$item->title}</A></B>
    </TD>
  </TR>
  <TR>
    <TD ALIGN=LEFT VALIGN=top CLASS=body>
        {$item->annotation}
    </TD>
  </TR>
  {/foreach}
</TABLE>