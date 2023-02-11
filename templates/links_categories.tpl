<h1 style="clear:both;padding:10px 0px;">{$Section->name}</h1>
  {if $Link_text}
    <br /><br /><div align="left">{$Link_text->body}</div><br /><br />
  {/if}
<div align="left" style="clear:both;padding: 0px 0px 20px 0px">
<TABLE WIDTH=33% BORDER=0 CELLPADDING=5 CELLSPACING=0 style="float:left;">
  {foreach name=mainbody item=category from=$Categories}
  <TR>
    <TD ALIGN=LEFT colspan=6>
      <A class=h1 HREF='resourses/{$category->path}_p0' style="font-weight:bolder;color:black;">{$category->name} ({$category->links})</A>
    </TD>
  </TR>
  <TR>
          {if $category->subcategories_names}
          <TD valign=top>
                <a href=index.php?section=Index_properties&category={$CurrentCategory->category_id}><nobr>{$Lang->ALL}</nobr></a>

      		{foreach name=subcategories item=subcategory_name from=$category->subcategories_names}


			        <a href={$subcategory_name.url}><nobr>{$subcategory_name.name}</nobr></a>

        		{if not $smarty.foreach.subcategories.last}
          			/
        		{/if}
      		{/foreach}
      		{/if}
      	  </TD>
      	</TR>
  {if $smarty.foreach.mainbody.iteration%$Column==0}
  </TABLE>
<TABLE WIDTH=33% BORDER=0 CELLPADDING=5 CELLSPACING=0 style="float:left;">
  {/if}
  {/foreach}
</TABLE>
</div>
  {if $Links}
  <h1 style="clear:both;padding:10px 0px;">Последние ссылки</h1>
   <ol style="clear:both;list-style: none;">
  {foreach name=news item=item2 key=key2 from=$Links}
 <li style="clear:both;">
 <B><A target="_blank" TITLE='{$item2->title}' HREF='{$item2->path}' CLASS="subheader" style="font-weight:bolder;color:black;">{$item2->title}</A></B><br /><br />
 <table border=0 cellpadding=10 cellspacing=0>
 <tr>
 <td>{if $item2->image}<A target="_blank" TITLE='{$item2->title}' HREF='{$item2->path}' CLASS="subheader"><img align="left" src="{$Uploaddir}{$item2->image}" width="120" alt="{$item2->image}" border="0"></A>{/if}
 </td>
 <td valign="top">
{$item2->annotation}<br />
<table border=0 cellpadding=10 cellspacing=0>
<tr>
<td>Цитируемость: {$item2->cy}</td>
{if $item2->region}<td>Регион: {$item2->region}</td>{/if}
</tr>
</table>
</td>
 </tr>
 </table>
</li>
  {/foreach}
  </ol>
  {/if}
<br><br>
<div style="clear:both;"><input type="submit" value="Добавить ссылку" onclick="window.location.href='resourses/add'"></div>
<br><br>