<h1 style="clear:both;padding:10px 0px;">{$Category}</h1>
<div align="left" style="clear:both;padding: 0px 0px 20px 0px">
<TABLE WIDTH=33% BORDER=0 CELLPADDING=5 CELLSPACING=0 style="float:left;">
  {foreach name=mainbody item=category from=$Categories}
  <TR>
    <TD ALIGN=LEFT colspan=6>
      <A class=h1 HREF='resourses/{$category->abc}_p0'{if $category->abc==$smarty.get.category} style="text-decoration:underline;"{/if}>{$category->name} ({$category->links})</A>
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
<form action="resourses/find" method=post style="clear:both;padding:10px 0px;">
		<input type="hidden" name="find_url" value="">
   URL: <input type="text" name="find_link" size=35 value="{$smarty.post.find_link|escape}"> <input type=submit value="найти" name="find">
   <br>
<font color=red>{$Message}</font>
</form>
<br><br>
<a href="resourses/add">Добавить ссылку >></a>
<br><br>
{if $PagesNum>1}
{assign var=i value=0}
<div align="center" style="clear:both;padding:10px 0px;">
<TABLE CELLPADDING=0 CELLSPACING=0>
  <TR>
    <TD ALIGN=LEFT style="color: #c8922e;">
      Страницы:&nbsp;
    </TD>
      <TD width=20 ALIGN=CENTER>
       {if $CurrentPage!=$IFirst_page}
        <A style='color: #c8922e;text-decoration: none;' HREF='{$First_page}' title="Первая">|&lt;</A>
        {else}
        &nbsp;
       {/if}
      </TD>
      <TD width=20 ALIGN=CENTER>
       {if $CurrentPage!=$IFirst_page}
        <A style='color: #c8922e;text-decoration: none;' HREF='{$Prev_page}' title="Предыдущая">&larr;</A>
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
        <A HREF='{$Url.$i}' style="color: #c8922e;">{$smarty.section.pages.index+1}</A>
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
        <A style='color: #c8922e;text-decoration: none;' HREF='{$Next_page}' title="Следующая">&rarr;</A>
         {else}
        &nbsp;
       {/if}
      </TD>
      <TD width=20 ALIGN=CENTER>
       {if $CurrentPage!=$ILast_page}
        <A style='color: #c8922e;text-decoration: none;' HREF='{$Last_page}' title="Последняя">&gt;|</A>
         {else}
        &nbsp;
       {/if}
      </TD>
  </TR>
</TABLE>
</div>
<br />
{/if}
{literal}
<style type="text/css">
#links tr td a
{
	text-decoration:underline;
}
#links tr td a:hover
{
	text-decoration:none;
}
</style>
{/literal}
<TABLE id="links" cellpadding=10 cellspacing=0 width=100% border="0" style="border-collapse:collapse;">
  {foreach name=links item=link from=$Links}
  <TR>
  <td width="120px" valign="top">{if $link->banner}{$link->banner}{else}<IMG width='100px' ALT='' border=0 src='images2/no_foto.gif'>{/if}</td><td valign="top">{$link->link}</td>
  </TR>
  {/foreach}
</TABLE>
{if $PagesNum>1}
<div align="center" style="clear:both;padding:10px 0px;">
<TABLE CELLPADDING=0 CELLSPACING=0>
  <TR>
    <TD ALIGN=LEFT style="color: #c8922e;">
      Страницы:&nbsp;
    </TD>
      <TD width=20 ALIGN=CENTER>
       {if $CurrentPage!=$IFirst_page}
        <A style='color: #c8922e;text-decoration: none;' HREF='{$First_page}' title="Первая">|&lt;</A>
        {else}
        &nbsp;
       {/if}
      </TD>
      <TD width=20 ALIGN=CENTER>
       {if $CurrentPage!=$IFirst_page}
        <A style='color: #c8922e;text-decoration: none;' HREF='{$Prev_page}' title="Предыдущая">&larr;</A>
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
        <A HREF='{$Url.$i}' style="color: #c8922e;">{$smarty.section.pages.index+1}</A>
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
        <A style='color: #c8922e;text-decoration: none;' HREF='{$Next_page}' title="Следующая">&rarr;</A>
         {else}
        &nbsp;
       {/if}
      </TD>
      <TD width=20 ALIGN=CENTER>
       {if $CurrentPage!=$ILast_page}
        <A style='color: #c8922e;text-decoration: none;' HREF='{$Last_page}' title="Последняя">&gt;|</A>
         {else}
        &nbsp;
       {/if}
      </TD>
  </TR>
</TABLE>
</div>
<br />
{/if}
<br><br>
<a href="resourses/add">Добавить ссылку >></a>
<br><br>