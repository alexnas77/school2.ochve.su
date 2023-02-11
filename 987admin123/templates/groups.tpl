{foreach item=category from=$Categories}
  <TR>
    <TD class=list style='padding-left:{$level*20+10}px;'>
      {$category->name|escape}
    </TD>
    <TD class=list>
      {if $category->enabled eq 'true'}<font color=green>{$Lang->CAT_ENABLED}</font>{else}<font color=red>{$Lang->CAT_DISABED}</font>{/if}
    </TD>
    <TD class=list align=center>
      {html_image file='images/arrow_up.gif' href=index.php`$category->move_up_get` alt=$Lang->MOVE_UP title=$Lang->MOVE_UP border=0}
      {html_image file='images/arrow_down.gif' href=index.php`$category->move_down_get` alt=$Lang->MOVE_DOWN title=$Lang->MOVE_DOWN border=0}
    </TD>
    <TD class=list align=center>
      {html_image file='images/edit.gif' href=index.php`$category->edit_get` alt=$Lang->EDIT title=$Lang->EDIT border=0}
    </TD>
    <TD class=list align=center>
       <input name=items[] value='{$category->category_id}' type=checkbox>
    </TD>
  </TR>
  {/foreach}