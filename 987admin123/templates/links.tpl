{config_load file=premium.conf}
<TABLE WIDTH=100% BORDER=0 CELLPADDING=5 CELLSPACING=0>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=subheader colspan=3>
      <a href='index.php'>{$Settings->site_name}</a> / {$Lang->LINKS}
    </TD>
  </TR>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=header>
       <img src='images/articles_icon.png' border=0 align=absmiddle> {$Lang->LINKS}
    </TD>
  </TR>
  <TR>
    <TD>
    <form name="search" action="index.php" method="get">
    <input name="section" type="hidden" value="{$smarty.get.section}">
    <p>Поиск  <input name="keyword" size="60" type="text" value="{$smarty.get.keyword|escape}">  <input type="submit" value="Найти"></p>
    </form>
    </TD>
  </TR>
  <TR>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=body>
<FORM METHOD=POST NAME=sections>
      <TABLE CELLPADDING=0 CELLSPACING=0 WIDTH=100%>
        <TR>
          <TD>
            {$PagesNavigation}
          </TD>
          <TD ALIGN=RIGHT>
            <a href='index.php?section=EditLink'>{$Lang->NEW_LINK}</a>
          </TD>
        </TR>
      </TABLE>
    </TD>
  </TR>
  <TR>
    <TD ALIGN=CENTER VALIGN=TOP CLASS=body>

<TABLE WIDTH=100% CLASS=list CELLPADDING=4 CELLSPACING=1>
  <TR>
    <TD class=listheader WIDTH=150>
      {$Lang->URL}
    </TD>
    <TD class=listheader WIDTH=150>
      {$Lang->TITLE}
    </TD>
    <TD class=listheader WIDTH=150>
      {$Lang->PHOTO}
    </TD>
     <TD class=listheader>
      &nbsp;
    </TD>
    <TD class=listheader WIDTH=150>
      {$Lang->CATEGORY}
    </TD>
    <TD class=listheader WIDTH=150>
      {$Lang->MAIL}
    </TD>
    <TD class=listheader WIDTH=150>
      {literal}
         <script>
         function check_notice(checked)
         {
           var checkboxes = window.document.getElementsByName('notice[]');
           var num = checkboxes.length;
           for(var i=0; i<num; i++)
           {
             checkboxes[i].checked = checked;
           }
         }
         </script>
      {/literal}
      Уведомление
      <input type=checkbox onclick='check_notice(this.checked);'>
    </TD>
    <TD class=listheader WIDTH=150>
      {$Lang->BACK_LINK}
    </TD>
    <TD class=listheader WIDTH=45 align=center>
    </TD>
    <TD class=listheader WIDTH=25 align=center>
    </TD>
    <TD class=listheader WIDTH=25 align=center>
      {literal}
         <script>
         function check_enabled(checked)
         {
           var checkboxes = window.document.getElementsByName('enabled[]');
           var num = checkboxes.length;
           for(var i=0; i<num; i++)
           {
             checkboxes[i].checked = checked;
           }
         }
         </script>
      {/literal}
      {$Lang->ACTIVE}
      <input type=checkbox onclick='check_enabled(this.checked);'>
    </TD>
    <TD class=listheader WIDTH=25 align=center>
      {literal}
         <script>
         function check_main(checked)
         {
           var checkboxes = window.document.getElementsByName('main[]');
           var num = checkboxes.length;
           for(var i=0; i<num; i++)
           {
             checkboxes[i].checked = checked;
           }
         }
         </script>
      {/literal}
      {$Lang->USE_BACK}
      <input type=checkbox onclick='check_main(this.checked);'>
    </TD>
    <TD class=listheader WIDTH=25 align=center>
      {literal}
         <script>
         function check(checked)
         {
           var checkboxes = window.document.getElementsByName('items[]');
           var num = checkboxes.length;
           for(var i=0; i<num; i++)
           {
             checkboxes[i].checked = checked;
           }
         }
         </script>
      {/literal}
      {$Lang->DELETE}
      <input type=checkbox onclick='check(this.checked);'>
    </TD>
  </TR>
  {if $Items}
     {foreach item=item from=$Items}
  <TR>
    <TD class=list nowrap>
      {$item->path|escape}
    </TD>
    <TD class=list>
      {$item->title|escape}
    </TD>
    <TD class=list>
    {if $item->image}
    <a target="_blank" href="{$Uploaddir}{$item->image}"><img src="{$Uploaddir}{$item->image}" width="150" title="" alt="{$item->image}" border="0"></a>
    {else}<img id=image_{$category->foto_id} src='images/no_foto.gif' width=60 height=40 border=1 align=middle>{/if}
    </TD>
    <TD class=list>
    <a href='{$item->delete_get}'><img src='images/delete.gif' alt="Удалить иконку" border=0 width=16 height=16 align=middle></a>
    </TD>
    <TD class=list>
    <select size="1" name="art_cat[{$item->article_id}]">
     {*html_options options=$Art_cats selected=$item->art_cat*}
              {foreach name=art_cats key=key item=art_cat from=$Art_cats}
                {if $art_cat->category_id EQ $item->art_cat}
                  <OPTION VALUE='{$art_cat->category_id}' SELECTED>{$art_cat->name|escape}</OPTION>
                {else}
                  <OPTION VALUE='{$art_cat->category_id}'>{$art_cat->name|escape}</OPTION>
                {/if}
                {foreach name=SubCategories key=key item=subart_cat from=$art_cat->subcategories}
                  {if $subart_cat->category_id EQ $item->art_cat}
                    <OPTION VALUE='{$subart_cat->category_id}' SELECTED>{$art_cat->name|escape} | {$subart_cat->name|escape}</OPTION>
                  {else}
                    <OPTION VALUE='{$subart_cat->category_id}'>{$art_cat->name|escape} | {$subart_cat->name|escape}</OPTION>
                  {/if}
                {/foreach}
              {/foreach}
	</select>
    </TD>
    <TD class=list nowrap>
    <a target="_blank" href='{$item->email}'>{$item->email}</a>
    </TD>
    <TD class=list nowrap>
    <input name=notice[] value='{$item->article_id}' type=checkbox>
    </TD>
    <TD class=list nowrap>
    <a target="_blank" href='{$item->backlink}'>{$item->backlink}</a>
    </TD>
    <TD class=list align=center nowrap>
      {html_image file='images/arrow_up.gif' href=index.php`$item->move_up_get` alt=$Lang->MOVE_UP title=$Lang->MOVE_UP border=0}
      {html_image file='images/arrow_down.gif' href=index.php`$item->move_down_get` alt=$Lang->MOVE_DOWN title=$Lang->MOVE_DOWN border=0}
    </TD>
    <TD class=list align=center>
      {html_image file='images/edit.gif' href=index.php`$item->edit_get` alt=$Lang->EDIT title=$Lang->EDIT border=0}
    </TD>
     <TD class=list align=center>
       <input name=enabled[] value='{$item->article_id}' type=checkbox {if $item->enabled}checked{/if}>
    </TD>
     <TD class=list align=center>
       <input name=main[] value='{$item->article_id}' type=checkbox {if $item->main}checked{/if}>
    </TD>
    <TD class=list align=center>
       <input name=items[] value='{$item->article_id}' type=checkbox>
    </TD>
  </TR>
  {/foreach}
  {else}
  <TR>
      <TD class=list align=center colspan=10>
         {$Lang->EMPTY_LIST}
      </TD>
  </TR>
  {/if}
</TABLE>

    </TD>
  </TR>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=body>
      <TABLE CELLPADDING=0 CELLSPACING=0 WIDTH=100%>
        <TR>
          <TD>
            {$PagesNavigation}
          </TD>
          <TD ALIGN=RIGHT>
			<INPUT TYPE='SUBMIT' VALUE='{$Lang->SAVE_CHANGES}' onclick='window.document.sections.act.value="active"; window.document.sections.submit();'>
            <input type='hidden' name='act' value=''>
            <INPUT TYPE=SUBMIT VALUE='{$Lang->DELETE_SELECTED}' onclick='if(!confirm("{$Lang->ARE_YOU_SURE_TO_DELETE}")) return false;'>
          </TD>
        </TR>
      </TABLE>
    </TD>
  </TR>
</TABLE>
</FORM>