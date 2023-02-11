{php}
$ctype="text/xml";
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Type: $ctype");
header('Content-disposition: attachment; filename="meta.xml"');
header("Content-Transfer-Encoding: binary");
{/php}
<?xml version="1.0" encoding="windows-1251"?>
<fullList>
{foreach name=products from=$Products item=product}
<item id="{$smarty.foreach.products.iteration}">
{if $product->filename}<img src="http://{php}print $_SERVER['HTTP_HOST'];{/php}/foto/storefront/{$product->filename}" />{/if}
<click href="http://{php}print $_SERVER['HTTP_HOST'];{/php}/catalog/{$product->category_id}/{$product->brand}/{$product->product_id}.html" />
<name>{$product->brand} {$product->model}</name>
<price>{$product->price}</price>
</item>
{/foreach}
</fullList>