<h1>{$Section->name}</h1>
<table cellpadding=10 cellspacing=1 width=100%>
{foreach name=mainbody item=order from=$Orders}
  <tr>
    <td valign=top bgcolor='#F0F0F0' style='font-size:10px;' width=200>
      <b>{$order->date}    {if $order->status == 0}Новый{/if}{if $order->status == 1}Принят{/if}{if $order->status == 2}Выполнен{/if}
  </b><br>
      Адрес: {$order->address}<br>
      Телефон: {$order->phone}<br>
      Дополнительно: {$order->comment}<br><br>
      {assign var=key value=$order->pay}
      Метод оплаты: <b>{$Pays.$key}</b><br><br>
      {if $order->text}
      <a style="color:#6C2B33; text-decoration: underline;" href="{$order->bill_url}">Показать счет</a>
      {/if}
    </td>
    <td valign=top style='border: 1px dotted lightgray;'>
      <table width=100%>
      {foreach name=products from=$order->products item=product}
        <tr>
          <td>
            {$product->product_name}
          </td>
          <td width=100>
            {$product->price*$Currency->rate|string_format:"%.2f"} {$Currency->sign}
          </td>
          <td width=100>
            {$product->quantity} шт.
          </td>
        </tr>
      {/foreach}
      </table>
    </td>
  </tr>
{/foreach}
</table>