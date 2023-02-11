    <TABLE class="products" cellpadding=5 cellspacing=0 width=100%>
        {foreach name=products key=key item=product from=$Products}
            <tr class="other">
                <TD align=left valign=top style="width:12%; padding-left: 20px; white-space: nowrap">
                    {$smarty.foreach.products.iteration}. <a TITLE='{$product->model|escape}' HREF='index.php?section={$smarty.get.section}&report={$smarty.get.report}&name={$product->model_url}&category={$smarty.get.category}&start_date={$smarty.get.start_date}&end_date={$smarty.get.end_date}' CLASS=subheader style="text-decoration: underline; white-space: nowrap">{$product->model|escape}</a>
                </td>
                <td>
                    {$product->category|escape}
                </td>
            </tr>
        {foreachelse}
             <tr class="other">
                <TD align=center valign=top style="white-space: nowrap">
                    ничего не найдено
                </td>
            </tr>           
        {/foreach}
    </TABLE>
