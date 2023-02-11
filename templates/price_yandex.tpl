{*php}
$ctype="text/xml";
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Type: $ctype");
header('Content-disposition: attachment; filename="yandex.xml"');
header("Content-Transfer-Encoding: binary");
{/php*}
<?xml version="1.0" encoding="windows-1251"?>
<!DOCTYPE yml_catalog SYSTEM "shops.dtd">
<yml_catalog date="{$smarty.now|date_format:"%Y-%m-%d %H:%M"}">
<shop>
  <name>{$Settings->site_name}</name>
  <company>{$Settings->company_name}</company>
  <url>http://{php}print $_SERVER['HTTP_HOST'];{/php}/</url>
  <currencies>
    {foreach from=$Currencies item=currency}
    <currency id="{$currency->code}" rate="{$currency->rate|string_format:"%.2f"}"/>
    {/foreach}
  </currencies>
  <categories>
    {foreach from=$Categories item=category}
    <category id="{$category->category_id}" parentId="{$category->parent}">{$category->name}</category>
    {/foreach}
  </categories>
  <offers>
  {foreach from=$Products item=product}
    <offer id="{$product->product_id}" type="vendor.model" available="true" bid="25">
    <url>http://{php}print $_SERVER['HTTP_HOST'];{/php}/catalog/{$product->category_id}/{$product->urlbrand}/{$product->product_id}.html</url>
    <price>{$product->price}</price>
    <currencyId>{$product->currency_id}</currencyId>
    <categoryId>{$product->category_id}</categoryId>
    {if $product->filename}<picture>http://{php}print $_SERVER['HTTP_HOST'];{/php}/foto/storefront/{$product->filename}</picture>{/if}

    <vendor>{$product->brand|regex_replace:"/\&/":" and "}</vendor>
    <model>{$product->category} {$product->model}</model>
    <description>
      {$product->description|strip_tags|regex_replace:"/&\w*;/":" "|regex_replace:"/^\s*/":""|regex_replace:"/\s*$/":""|regex_replace:"/\s+/":" "}
    </description>
    </offer>
  {/foreach}
  </offers>
</shop>
</yml_catalog>