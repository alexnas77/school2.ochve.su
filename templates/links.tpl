<div id="defaultCont" style="padding-top:5px;" align=left>
    <div style="clear:both;"><div align=left style="font-size:14px; margin-top:25px; margin-left:15px;"><h1>{$Section->name}</h1></div><br></div>
    <div style="padding:0px 20px">
  {if $Link_text}
    <br />{$Link_text->body}<br /><br />
  {/if}
  {if $Section->description}
    <br /><div style="clear:both;">{$Section->description}</div><br /><br />
  {/if}
<TABLE WIDTH=100% BORDER=0 CELLPADDING=10 CELLSPACING=0>
  <TR>
    <TD ALIGN=LEFT>
 {foreach name=mainbody item=item key=key from=$Articles}
  {if $item->toparticles}
  {if $item->name}
    <br /><B style="clear:both;"><u><a href="resourses/{$item->path}_p0">{$item->name}</a></u></B><br /><br />
  {elseif $item->description}
    <br /><div style="clear:both;">{$item->description}</div><br /><br />
  {/if}
   <ol style="clear:both;list-style: none;">
  {foreach name=news item=item2 key=key2 from=$item->toparticles}
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
  {/foreach}
{if $PagesNum>1}
{assign var=i value=0}
<div align="center" style="clear:both;padding:10px 0px;">
<TABLE CELLPADDING=0 CELLSPACING=0>
  <TR>
    <TD ALIGN=LEFT style="">
      Страницы:&nbsp;
    </TD>
      <TD width=20 ALIGN=CENTER>
       {if $CurrentPage!=$IFirst_page}
        <A style='text-decoration: none;' HREF='{$First_page}' title="Первая">|&lt;</A>
        {else}
        &nbsp;
       {/if}
      </TD>
      <TD width=20 ALIGN=CENTER>
       {if $CurrentPage!=$IFirst_page}
        <A style='text-decoration: none;' HREF='{$Prev_page}' title="Предыдущая">&larr;</A>
        {else}
        &nbsp;
       {/if}
      </TD>
    {if $PagesNum lt $Pages_group}
    {assign var=End_page value=$Pages_group}
    {/if}
    {section name=pages start=$Start_page loop=$End_page}
      {if $smarty.section.pages.index<=$ILast_page}
      {if $smarty.section.pages.index!=$CurrentPage}
      {assign var=i value=$smarty.section.pages.index}
      <TD width=20 ALIGN=CENTER>
        <A HREF='{$Url.$i}' style="">{$smarty.section.pages.index+1}</A>
      </TD>
      {else}
      <TD width=20 ALIGN=CENTER BGCOLOR=#E0E0E0 style='color: black;'>
        {$smarty.section.pages.index+1}
      </TD>
      {/if}
      {/if}
    {/section}
      <TD width=20 ALIGN=CENTER>
       {if $CurrentPage!=$ILast_page}
        <A style='text-decoration: none;' HREF='{$Next_page}' title="Следующая">&rarr;</A>
         {else}
        &nbsp;
       {/if}
      </TD>
      <TD width=20 ALIGN=CENTER>
       {if $CurrentPage!=$ILast_page}
        <A style='text-decoration: none;' HREF='{$Last_page}' title="Последняя">&gt;|</A>
         {else}
        &nbsp;
       {/if}
      </TD>
  </TR>
</TABLE>
</div>
<br />
{/if}
   </TD>
  </TR>
  <TR>
    <TD align="center">
    <input type="submit" value="Добавить ссылку" onclick="window.location.href='resourses/add'">
    </TD>
  </TR>
</TABLE>
    </div>
</div>
