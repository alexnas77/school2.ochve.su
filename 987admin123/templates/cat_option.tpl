{foreach from=$Categories item=category}
  {if $category->category_id != $Item->category_id}
    <option value='{$category->category_id}' {if $category->category_id == $Item->parent}selected{/if}>{section name=sp loop=$level}&nbsp;&nbsp;&nbsp;&nbsp;{/section}{$category->name[0]}</option>
    {if $level<$MaxLevel}
    {include file=cat_option.tpl Categories=$category->subcategories level=$level+1}
    {/if}
  {/if}
{/foreach}