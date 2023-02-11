<H1>Поиск {$Keyword|escape}</H1>
{if $Products}
<TABLE cellpadding=0 cellspacing=10 width=100%>
  {foreach name=products item=product from=$Products}
  <tr height=120>
    <TD align=center valign=top width=120 height=120>
      <table width=120 height=120 cellpadding=0 cellspacing=0  ALIGN=center valign=center>
        <tr>
          <td LIGN=center valign=center style='height:120px; border: 1px solid lightgray; padding:10'>
            <A TITLE='{$product->brand|escape}{$product->model|escape}' HREF='catalog/{$product->category_id}/{$product->brand|escape}/{$product->product_id}.html' CLASS=subheader><IMG WIDTH='150' ALT='{$product->brand|escape} {$product->model|escape}' border=0 src='{if $product->filename}{*foto/storefront/{$product->filename}*}image.php?src=storefront/{$product->filename}&w=150{else}images2/no_foto.gif{/if}'></A>
          </td>
        </tr>
      </table>

    </TD>
    <TD ALIGN=LEFT valign=top rowspan=2>
    <table cellpadding=5 cellspacing=0 width=100%>
      <tr>
        <td colspan=2>
          <A TITLE='{$product->brand|escape} {$product->model}' HREF='catalog/{$product->category_id}/{$product->brand|escape}/{$product->product_id}.html' CLASS=product_name>{$product->category|escape} {$product->brand|escape} {$product->model|escape}</a><br>
        </td>
      </tr>
      <tr>
        <td class=price>
         <nobr><span class=price>{$product->discount_price|string_format:"%.2f"} {$Currency->sign}</span></nobr>
        </td>
        <td width=80% align=left>
         {if $product->quantity>0}
         <table>
         <tr>
         <td>
           <A TITLE='{$product->brand|escape} {$product->model}' HREF='catalog/{$product->category_id}/{$product->brand|escape}/{$product->product_id}.html'  CLASS=details><span>Подробнее</span></a>
         </td>
         <td>
           <a href='index.php?section=7&product_id={$product->product_id}' class=order_link><span>В корзину</span></a>
         </td>
         </tr>
         </table>
         {/if}
        </td>
      </tr>
      <tr>
        <td colspan=2>
          {$product->description}
        </td>
      </tr>
    </table>
    </TD>
    </tr>
    <tr>
      <td>
      </td>
    </tr>
  {/foreach}
</TABLE>
{else}
  По запросу &laquo;{$Keyword|escape}&raquo; ничего не найдено
{/if}

