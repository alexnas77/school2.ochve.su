<head>
  <meta http-equiv='content-type' content='text/html; charset=windows-1251'>
{literal}
<style type="text/css">
pre
{	font-size:14px;}
</style>
{/literal}
</head>
��������� ������������� ����� {$Settings->site_name}, ���������� ��� � ����������� ������ ������:<br /><br />
<table>
<tr><td width=20%>��� ���������:</td><td>{$Name|escape};</td></tr>
<tr><td width=20%>����������� �����:</td><td>{$Mail|escape};</td></tr>
<tr><td width=20%>���������� �������:</td><td>{$Phone|escape};</td></tr>
<tr><td width=20%>�����:</td><td>{$City|escape};</td></tr>
<tr><td width=20%>�����:</td><td>{$Address|escape};</td></tr>
</table>
<pre>

�������������� ����������:		{$Comment|escape}.

���������:		{$Inn_payer}.

����� ������:	{$Pays.$Pay}

����� ��������:	{$Delivery.$Del}

��:	{$Tk}

������:

{foreach name=goods item=product from=$Products}
{$smarty.foreach.goods.iteration}) {$product->category} {$product->brand} {$product->model}
����:			{$product->price*$Currency->rate/$product->currency_rate|string_format:"%.2f"} {$Currency->sign};
����������:		{$product->quantity} ��.;
�������� ������:	http://{php}print $_SERVER['HTTP_HOST']; $dir=dirname($_SERVER["PHP_SELF"]); if($dir!='/' && $dir!='\\') print $dir; {/php}/catalog/{$product->category_id}/{$product->urlbrand}/{$product->product_id}.html;

{/foreach}

������� ������� �� ������ ���������� �� ������: http://{php}print $_SERVER['HTTP_HOST']; $dir=dirname($_SERVER["PHP_SELF"]); if($dir!='/' && $dir!='\\') print $dir; {/php}/987admin123/index.php?section=Orders

�������� ������ � Webox CMS!
</pre>