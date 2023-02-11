{foreach item=brand from=$Brands}
  <TR>
    <TD class=list style='padding-left:{$level*20+10}px;'>
      {$brand->name|escape}
    </TD>
    <TD class=list style='padding-left:{$level*20+10}px;'>
      {$brand->title|escape|wordwrap:60:"<br>"}
    </TD>
    <TD class=list style='padding-left:{$level*20+10}px;'>
      {$brand->description|escape|wordwrap:60:"<br>"}
    </TD>

    <TD class=list align=center>
      {html_image file='images/edit.gif' href=index.php`$brand->edit_get` alt=$Lang->EDIT title=$Lang->EDIT border=0}
    </TD>

  </TR>
  {/foreach}