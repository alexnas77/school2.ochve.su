<head>
  <meta http-equiv='content-type' content='text/html; charset=windows-1251'>
{literal}
<style type="text/css">
pre
{	font-size:14px;}
</style>
{/literal}
</head>
Уважаемый администратор сайта {$Settings->site_name}, уведомляем Вас о поступлении нового заказа:<br /><br />
<table>
<tr><td width=20%>Имя заказчика:</td><td>{$Name|escape};</td></tr>
<tr><td width=20%>Электронная почта:</td><td>{$Mail|escape};</td></tr>
<tr><td width=20%>Контактный телефон:</td><td>{$Phone|escape};</td></tr>
<tr><td width=20%>Город:</td><td>{$City|escape};</td></tr>
<tr><td width=20%>Улица:</td><td>{$Address|escape};</td></tr>
</table>
<pre>

Дополнительная информация:		{$Comment|escape}.

Реквизиты:		{$Inn_payer}.

Метод оплаты:	{$Pays.$Pay}

Метод доставки:	{$Delivery.$Del}

ТК:	{$Tk}

Товары:

{foreach name=goods item=product from=$Products}
{$smarty.foreach.goods.iteration}) {$product->category} {$product->brand} {$product->model}
Цена:			{$product->price*$Currency->rate/$product->currency_rate|string_format:"%.2f"} {$Currency->sign};
Количество:		{$product->quantity} шт.;
Страница товара:	http://{php}print $_SERVER['HTTP_HOST']; $dir=dirname($_SERVER["PHP_SELF"]); if($dir!='/' && $dir!='\\') print $dir; {/php}/catalog/{$product->category_id}/{$product->urlbrand}/{$product->product_id}.html;

{/foreach}

Историю заказов Вы можете посмотреть по ссылке: http://{php}print $_SERVER['HTTP_HOST']; $dir=dirname($_SERVER["PHP_SELF"]); if($dir!='/' && $dir!='\\') print $dir; {/php}/987admin123/index.php?section=Orders

Приятной работы с Webox CMS!
</pre>