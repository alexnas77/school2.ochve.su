{config_load file=premium.conf}
<TABLE CELLPADDING=1 CELLSPACING=1>
  <TR>
    <TD ALIGN=LEFT>
      {$Lang->PAGES}:
    </TD>
    {foreach key=index item=page from=$Pages}
      {if $index!=$CurrentPage}
      <TD width=20 ALIGN=CENTER>
        <A HREF='{$page}' style='color:black;'>{$index+1}</A>
      </TD>
      {else}
      <TD width=20 ALIGN=CENTER BGCOLOR=#E0E0E0>
        {$index+1}
      </TD>
      {/if}
    {/foreach}
  </TR>
</TABLE>