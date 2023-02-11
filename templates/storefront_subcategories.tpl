<TABLE WIDTH=100% BORDER=0 CELLPADDING=5 CELLSPACING=0>
          {if $Subcategories}
  <TR>
    <TD ALIGN=LEFT>
      <H1 style="margin:10px 30px;">{$Category_name}</H1>
    </TD>
  </TR>
  <TR>
          <TD valign=top>
            <TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>
            <tr>
      		{foreach name=subcategories item=subcategory_name from=$Subcategories}
      		<td width="33%" align=center valign=top>
			<br /><br /><a href={$subcategory_name.url} title='{$subcategory_name.name|strip_tags}'>{$subcategory_name.name|wordwrap:30:"<br />"}<br /><br /><img src="{if $subcategory_name.filename}{$subcategory_name.filename}{else}images2/no_foto.gif{/if}" {if $subcategory_name.height}height="{$subcategory_name.height}px"{elseif $subcategory_name.width}width="{$subcategory_name.width}px"{else}height="150px"{/if} alt="{$subcategory_name.name|strip_tags}" border="0"></a>
			</td>
        		{if $smarty.foreach.subcategories.iteration%3==0}
          		</tr><tr>
        		{/if}
      		{/foreach}
      		<tr>
      		</TABLE>
      	  </TD>
      	</TR>
      		{/if}
</TABLE>