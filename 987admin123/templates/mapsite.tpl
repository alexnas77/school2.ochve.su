{config_load file=premium.conf}
<FORM METHOD=POST NAME=sections>
<TABLE WIDTH=100% BORDER=0 CELLPADDING=5 CELLSPACING=0>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=subheader colspan=3>
      <a href='index.php'>{$Settings->site_name}</a> / {$Lang->MAPSITE}
    </TD>
  </TR>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=header>
       <img src='images/menu_icon.gif' border=0 align=absmiddle> {$Lang->MAPSITE}
    </TD>
  </TR>
  <TR>
    <TD HEIGHT=10>
    </TD>
  </TR>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=body>
      <TABLE CELLPADDING=0 CELLSPACING=0 WIDTH=100%>
        <TR>
          <TD>
            {$PagesNavigation}
          </TD>
        </TR>
      </TABLE>
    </TD>
  </TR>
  <TR>
    <TD ALIGN=CENTER VALIGN=TOP CLASS=body>

<TABLE WIDTH=100% CLASS=list CELLPADDING=4 CELLSPACING=1>
  <TR>
    <TD class=listheader>
      {$Lang->NAME}
    </TD>
    <TD class=listheader WIDTH=150>
      {$Lang->SECTION_TYPE}
    </TD>
    <TD class=listheader WIDTH=150>
      {$Lang->PAGE_URL}
    </TD>
    <TD class=listheader WIDTH=150>
      {$Lang->ALTURL}
    </TD>
    <TD class=listheader WIDTH=150>
      {$Lang->EXT}
    </TD>
    <TD class=listheader WIDTH=45 align=center>
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
      {$Lang->IN_MAP}
      <input type=checkbox onclick='check(this.checked);'>
    </TD>
  </TR>
  {if $Mapsite}
     {foreach item=section from=$Mapsite}
  <TR>
    <TD class=list>
      {$section->name|escape}
    </TD>
    <TD class=list>
      {$section->service|escape}
    </TD>
    <TD class=list>
     <font size=-2> http://{php}print $_SERVER['HTTP_HOST'];{/php}/{$section->url}.html  </font>
    </TD>
    <TD class=list>
     <input name="alturl[{$section->section_id}]" type="text" value="{$section->alturl}">
    </TD>
    <TD class=list>
     {*$section->ext*}
     <select size="1" name="ext[{$section->section_id}]">
     {html_options values=$Exts output=$Exts selected=$section->ext}
    </select>
    </TD>
    <TD class=list align=center>
       <input name="items[]" value="{$section->section_id}" {if $section->inmap}checked{/if} type="checkbox">
       <input name="id[{$section->section_id}]" type="hidden" value="{$section->section_id}">
    </TD>
  </TR>
  {/foreach}
  {else}
  <TR>
      <TD class=list align=center colspan=15>
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
            <INPUT TYPE=SUBMIT VALUE='{$Lang->SAVE_CHANGES}'>
          </TD>
        </TR>
      </TABLE>
    </TD>
  </TR>
</TABLE>
</FORM>