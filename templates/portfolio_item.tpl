<img style="z-index: 3; margin: 580px 0px 0px 638px; position: absolute" src="images2/half.jpg" border="0" alt="" height="33" />
<table border="0" cellspacing="0" cellpadding="0" width="643">
	<tbody>
		<tr>
			<td colspan="3" style="background-position: center bottom; font-size: 1px; background-image: url('images2/centr_top.jpg'); width: 643px; background-repeat: no-repeat; height: 5px">&nbsp;</td>
		</tr>
		<tr>
			<td style="background-position: left 50%; background-image: url('images2/centr_left.jpg'); width: 4px; background-repeat: repeat-y">&nbsp;</td>
			<td align="center" style="padding-right: 0px; padding-left: 0px; padding-bottom: 10px; padding-top: 10px; background-color: #ffffff"><font face="verdana,geneva">
			<div class="marked" style="margin-left: 10px;padding-top: 10px;margin-bottom: 10px;padding-bottom: 10px;">
			<br />
			<strong>
			<font size="3">Портфолио </font>
			</strong>
			</div>
<TABLE align="center" BORDER=0 CELLPADDING=0>
  <TR>
    <TD>
    {if $url_left}
    <a href="{$url_left}#head" id="prev"><span>Влево</span></a>
    {else}
    <img src="images2/blank.gif" width="17" height="34" alt="" border="0">
    {/if}
    </TD>
    <TD style='vertical-align:middle'>
    <a target="_blank" href="http://www.{$Site}" title="www.{$Site}"><img src='image.php?src={$Foto}&h={$big_height}&type=jpg' height="{$big_height}"  style="border:1px solid Black;"></a>
    </TD>
    <TD>
    {if $url_right}
    <a href="{$url_right}#head" id="next"><span>Вправо</span></a>
    {else}
    <img src="images2/blank.gif" width="17" height="34" alt="" border="0">
    {/if}
    </TD>
    </tr>
     <tr>
  <td colspan=3 style='padding-top:5; padding-bottom:5;'>
  <div align="center"><a target="_blank" href="http://www.{$Site}" class="menu" title="www.{$Site}" style="color:#BD1B20; font-size:16px;">www.{$Site}</a></div>
  </td>
  </tr>
     <tr>
  <td colspan=3 style='padding-top:5; padding-bottom:5;'>
<div align="center">
<table width="100%">
<tr>
{foreach name=fotos key=key item=foto from=$Fotos}
    <td>
{if $smarty.foreach.fotos.first}
<div align="left">
<div class="shadow_l">
<a href="{if $foto != 'foto/portfolio/'}{$url.$key}#head{/if}" style="position:relative;"><img src="images2/blank.gif" alt="" border="0"></a>
</div>
</div>
{/if}
{if $smarty.foreach.fotos.last}
<div align="left">
<div class="shadow_r">
<a href="{if $foto != 'foto/portfolio/'}{$url.$key}#head{/if}" style="position:relative;"><img src="images2/blank.gif" alt="" border="0"></a>
</div>
</div>
{/if}
    <div align="center">
      <a href="{if $foto != 'foto/portfolio/'}{$url.$key}#head{/if}"><img src='{if $foto != 'foto/portfolio/'}image.php?src={$foto}&h={$small_height}&type=jpg{else}images2/blank.gif{/if}' height="{$small_height}" {if $foto != 'foto/portfolio/'}style="border:1px solid Black;"{else}style="border:0px;"{/if}></a>
      </div>
    </td>
{/foreach}
</tr>
</table>
</div>
  </td>
  </tr>
</table>

			</td>
			<td style="background-position: center center; background-image: url('images2/centr_right.jpg'); width: 5px; background-repeat: repeat-y">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="3" style="background-position: center bottom; font-size: 1px; background-image: url('images2/centr_down.jpg'); background-repeat: no-repeat; height: 12px"><font color="#000000">&nbsp;</font></td>
		</tr>
	</tbody>
</table>