{foreach item=category from=$Categories}
  <TR>
    <TD class=list style='padding-left:{$level*20+10}px;'>
      {$category->name[0]|escape}
    </TD>    <TD class=list>
    {if $category->alies}
    <ol>
      <li>{$category->alies|regex_replace:"/\;\;/":"<br /><li>"}
    </ol>
    {/if}
    </TD>
    <TD class=list>
       {if $category->filename}<a href='../foto/icons/{$category->filename}' target=_blank><img id=image_{$category->foto_id} src='../foto/icons/{$category->filename}' height=100 border=1 align=middle></a>
       {else}<img id=image_{$category->foto_id} src='images/no_foto.gif' width=60 height=40 border=1 align=middle>{/if}
    </TD>
    <TD class=list>
    <a href='{$category->delete_get}'><img src='images/delete.gif' alt="Удалить иконку" border=0 width=16 height=16 align=middle></a>
    </TD>
    <TD class=list>
      {$category->title|escape|wordwrap:30:"<br />"}
    </TD>
    {*<TD class=list style='width:100;'>
      {$category->description|escape|wordwrap:30:"<br />":true}
    </TD>*}
    <TD class=list>
      {if $category->enabled}<font color=green>{$Lang->CAT_ENABLED}</font>{else}<font color=red>{$Lang->CAT_DISABED}</font>{/if}
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
  {include file=cat.tpl Categories=$category->subcategories level=$level+1}
  {/foreach}