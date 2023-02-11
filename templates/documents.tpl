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
  {foreach name=mainbody item=item from=$Documents}
      <li><B><A TITLE='{$item->title}' HREF='index.php?section=18&id={$item->page_id}' CLASS=subheader>{$item->title}</A></B><br /><br />
  {/foreach}
    </ol>
   </TD>
  </TR>
</TABLE>