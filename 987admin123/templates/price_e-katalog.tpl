{php}
$ctype="application/vnd.ms-excel";
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Type: $ctype");
header('Content-disposition: attachment; filename="e-katalog.xls"');
header("Content-Transfer-Encoding: binary");
{/php}
{foreach from=$Products item=product}
{$product->name}	{$product->brand}	{$product->model}	{$product->description|strip_tags|regex_replace:"/&\w*;/":" "|regex_replace:"/^\s*/":""|regex_replace:"/\s*$/":""|regex_replace:"/\s+/":" "}	{$product->price}	http://{php}print $_SERVER['HTTP_HOST'];{/php}/catalog/{$product->category_id}/{$product->brand|regex_replace:"/&/":"%26"|regex_replace:"/ÒÑÑ/":"%D2%D1%D1"}/{$product->product_id}.html	{if $product->filename}http://{php}print $_SERVER['HTTP_HOST'];{/php}/foto/storefront/{$product->filename}{/if}

{/foreach}