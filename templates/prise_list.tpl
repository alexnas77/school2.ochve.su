<H1>{$Section->name} {$Brand}</H1>

{if $PagesNum>1}
<TABLE CELLPADDING=0 CELLSPACING=0>
  <TR>
    <TD ALIGN=LEFT>
      Страницы:
    </TD>
    {section name=pages loop=$PagesNum}
      {if $smarty.section.pages.index!=$CurrentPage}
      {assign var=i value=$smarty.section.pages.index}
      <TD width=20 ALIGN=CENTER>
        <A style='color:black;' HREF='{$Url.$i}'>{$smarty.section.pages.index+1}</A>
      </TD>
      {else}
      <TD width=20 ALIGN=CENTER BGCOLOR=#E0E0E0>
        {$smarty.section.pages.index+1}
      </TD>
      {/if}
    {/section}
  </TR>
</TABLE>
{/if}
<TABLE cellpadding=0 cellspacing=0 width=100%>

  {assign var="brand" value=""}
  {assign var="category" value=""}
  {foreach name=products key=key item=product from=$Products}
  {if $brand neq $product->brand}
  {assign var="brand" value=$product->brand}
  {assign var="category" value=""}
  <TR height=50>
  <td colspan="2">
  </td>
  </TR>
  <TR>
  <TD class=pr_list_brand colspan="2">
    {$brand|escape}
  </TD>
  </TR>
  {/if}
  {if $category neq $product->category}
  {assign var="category" value=$product->category}
  <TR>
  <TD class=pr_list_cat colspan=2>
    {$product->category}
   </TD>
  </TR>
  {/if}
  <tr
  {if $key is even}
  style='background-color:#00FF40; padding: 10 10 10 10;'
  {else}
  style='background-color:#FF8040; padding: 10 10 10 10;'
  {/if}>
    <TD ALIGN=LEFT valign=top>
          <A TITLE='{$product->brand|escape} {$product->model}' HREF='catalog/{$product->category_id}/{$product->brand|escape}/{$product->product_id}.html' CLASS=pr_list_model>{$product->model|escape}</a>
    </td>
    <TD ALIGN=RIGHT valign=top>
           <nobr><span class=price>{$product->discount_price|string_format:"%.2f"} {$Currency->sign}</span></nobr>

       </td>
      </tr>
  {/foreach}
</TABLE>
{if $PagesNum>1}
<TABLE CELLPADDING=0 CELLSPACING=0>
  <TR>
    <TD ALIGN=LEFT>
      Страницы:
    </TD>
    {section name=pages loop=$PagesNum}
      {if $smarty.section.pages.index!=$CurrentPage}
      <TD width=20 ALIGN=CENTER>
        <A style='color:black;' HREF='{$Url.$i}'>{$smarty.section.pages.index+1}</A>
      </TD>
      {else}
      <TD width=20 ALIGN=CENTER BGCOLOR=#E0E0E0>
        {$smarty.section.pages.index+1}
      </TD>
      {/if}
    {/section}
  </TR>
</TABLE>
{/if}