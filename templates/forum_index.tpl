<HTML>

<HEAD>
  <base href='http://{php} print $_SERVER['HTTP_HOST']; $dir=dirname($_SERVER["PHP_SELF"]); if($dir!='/' && $dir!='\\') print $dir.'/'; {/php}'>
  <TITLE>{$Title}</TITLE>
  <LINK REL='stylesheet' HREF='style.css'>
  <META HTTP-EQUIV='Content-Type' CONTENT='text/html; charset=Windows-1251'>
  <META NAME='description' content='{$Description}'>
  <META NAME='keywords' content='{$Keywords}'>

  <SCRIPT src="baloon/js/default.js" language="JavaScript" type="text/javascript"></SCRIPT>
  <SCRIPT src="baloon/js/validate.js" language="JavaScript" type="text/javascript"></SCRIPT>
  <SCRIPT src="baloon/js/baloon.js" language="JavaScript" type="text/javascript"></SCRIPT>
  {literal}
  <SCRIPT language="JavaScript">
  function startMenu() {

  //�������� ������� DOM
  if (document.getElementById){

  //�������� ������ id=menu
  nav=document.getElementById('menu');

  //�������� �� ���� �������� ���������
  for (i=0; i<nav.childNodes.length;i++){
  node=nav.childNodes[i];

  //���� �������� ������� LI, �� ���� ������
  if(node.nodeName == 'LI'){

  //��� ��������� ������� �� ����� ����, ����������� LI ����� over
  node.mouseover=function() {
  	this.className = 'over';
  }
  node.mouseout=function() {
  	this.className = '';
  }
  }
  }
  }
  }
  </SCRIPT>
  {/literal}
  <LINK href="baloon/css/baloon.css" rel="stylesheet" type="text/css">


</HEAD>

<BODY TOPMARGIN=0 BOTTOMMARGIN=0 LEFTMARGIN=0 RIGHTMARGIN=0>
<!-- ������� ����  -->
      <table width=150 height=20 cellpadding=2 cellspacing=0 style='position: relative;	left:900;'>
          <td width=50 align=center style='border-right: 1px solid Blue;'><a href="index.php"><img src="images2/home.jpg" width="13" height="13" alt="������� ��������" border="0"></a></td>
          <td width=50 align=center><a href="mailto:{$Settings->admin_email}"><img src="images2/mail.jpg" width="13" height="13" alt="�������� ������" border="0"></a></td>
          <td width=50 align=center style='border-left: 1px solid Blue;'><a href="15.html"><img src="images2/cross.jpg" width="13" height="13" alt="��������" border="0"></a></td>
      </table>

<table style='position: relative; top:10; left:600;'>
  <tr>
   {if $Section->url == '20'}
       <td class=hi_menu_sel>
        <b><nobr>� ��������</nobr></b>
       </td>
    		{else}
        <td class=hi_menu>
      	<a HREF='{$url[0][0]}' class=upmenu><nobr>� ��������</nobr></a>
      	</td>
      {/if}
   {if $Section->url == '21'}
       <td class=hi_menu_sel>
        <b><nobr>�������� � ������</nobr></b>
       </td>
    		{else}
        <td class=hi_menu>
      	<a HREF='{$url[0][1]}' class=upmenu ><nobr>�������� � ������</nobr></a>
      	</td>
      {/if}
   {*if $Section->url == '22'}
       <td class=hi_menu_sel>
        <b><nobr>�������� � ������</nobr></b>
       </td>
    		{else}
        <td class=hi_menu>
      	<a HREF='{$url[0][2]}' class=upmenu><nobr>�������� � ������</nobr></a>
      	</td>
      {/if*}
   {if $Section->url == '14'}
       <td class=hi_menu_sel>
        <b><nobr>�����-����</nobr></b>
       </td>
    		{else}
        <td class=hi_menu>
      	<a HREF='{$url[0][3]}' class=upmenu><nobr>�����-����</nobr></a>
      	</td>
      {/if}

        <td class=hi_menu>
      	<a HREF='forum.php' class=upmenu><nobr>�����</nobr></a>
      	</td>

  </tr>
    </table>


<table width=1006 height=233 cellpadding=0 cellspacing=0 align=center border=0 background='images2/hbgd.jpg'
style='background-repeat:no-repeat'>
  <tr>
    <td rowspan=2>
    <a href="index.php"><img src="images2/blank.gif" width="400" height="233" alt="" border="0"></a>
    </td>
     <td>
    &nbsp;
    </td>
   <form method=post>
    <td valign="top">
    <div align="right" style='color: White; padding-right:15; padding-top:15;'>
     ������:<br><br>
      <select name=currency_id>
        {foreach from=$Currencies item=currency}
          <option value='{$currency->currency_id}' {if $currency->currency_id==$Currency->currency_id}selected{/if}>{$currency->code}</option>
        {/foreach}
      </select>
      <input type=submit value='Ok'></div>
    </td>
   </form>
  </tr>
  <tr>
   {* <td align=left valign=top>
      <strong>���� �����:</strong><br>
      {foreach from=$Currencies item=currency}
        {if $MainCurrency->currency_id != $currency->currency_id}
          {$currency->name}: 1 {$MainCurrency->sign} = {$currency->rate*1} {$currency->sign}<br>
        {/if}
      {/foreach}
      </td>  *}

      <td width=250 height=30 valign=middle style='color: White;'>
    {*  ������:
      <select name=currency_id>
        {foreach from=$Currencies item=currency}
          <option value='{$currency->currency_id}' {if $currency->currency_id==$Currency->currency_id}selected{/if}>{$currency->code}</option>
        {/foreach}
      </select>
      <input type=submit value='Ok'>  *}
      &nbsp;
    </td>

    <td width=450 align=center valign=center>
     <form method=get action='index.php'>
     <input type=hidden name=section value=6>
      <table align=center valign=center>
          <tr>
            <td>
              <input size=50 type=text name=keyword value={$Keyword}>
              <input type=image src="images2/search.png" name="sub">
            </td>
          </tr>
      </table>
     </form>
    </td>
  </tr>
</table>





<table width=1006 align=center cellpadding=0 cellspacing=0 >
  <tr>
    <td>

   {php}
   include($_SERVER['DOCUMENT_ROOT'].dirname($_SERVER["PHP_SELF"])."/forum/index.php");
{/php}

    </td>
  </tr>
</table>
<table width=1006 height=50>
<tr>
  <td>
 <div align="right"><img src="images2/telephon.png" border="0"></div>
  </td>
  </tr>
</table>

<table width=1006 height=50 align=center>
  <tr>
    <td align=left style='font-size:9px;'>
      {$Settings->counters}
    </td>
    <td align=right style='font-size:9px;'>
      {*$Settings->footer_text*}
    </td>
  </tr>
</table>
</BODY>
</HTML>