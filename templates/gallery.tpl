<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>
  <TR>
    <TD  ALIGN=LEFT VALIGN=TOP>
      <H1>{$Section->name}</H1>
    </TD>
  </TR>
  <TR>
    <TD HEIGHT=10>
    &nbsp;
    </TD>
  </TR>
  <TR>
    <TD ALIGN=LEFT>
    {literal}
<script type="text/javascript">
$(document).ready(function() {
$("table#photos tr td a").fancybox({
'hideOnContentClick': true,
'overlayShow':		  true,
'overlayOpacity':	  0.8,
'zoomSpeedIn'	:	  500,
'zoomSpeedOut'	:	  500,
'easingIn'		:	  'swing',
'easingOut'		:	  'swing'
});
});</script>
    {/literal}
<table cellpadding=0 cellspacing=5 width="100%" id="photos">
<tr>
  {foreach name=mainbody item=item from=$Gallery}
{if ($smarty.foreach.mainbody.iteration-1)%4==0}</td></tr><tr><td>{/if}
  <td align=center valign=top style="border:1px solid black;padding:10px;">
	<a rel="group" title="Фото {$smarty.foreach.mainbody.iteration}" href='{$Uploaddir}{$item->filename}' target=_blank><img id=image src='{$Uploaddir}{$item->filename}' width=150 border=0 align=middle></a>
  </td>
  {/foreach}
  </TR>
</TABLE>
    </TD>
  </TR>
</TABLE>