<TABLE WIDTH=100% BORDER=0 CELLPADDING=10 CELLSPACING=0>
  <TR>
    <TD  ALIGN=LEFT VALIGN=TOP>
      <H1>�����������</H1>
    </TD>
  </TR>
{if $smarty.post.ok}
  <TR>
    <TD>
      {if $error}
        <font color=red>{$error}</font>
      {/if}
      <form name=register method=post>
      <input name="ok" type="hidden" value="1">
      <table>
        <tr>
          <td>
            ���
          </td>
          <td>
            <input size=30 type=text name=name value='{$name|escape}' class='ilogin' {literal}pattern='^.{3,255}$' notice='������� ��� (> 3)'{/literal}>
          </td>
        </tr>
        <tr>
          <td>
            �����
          </td>
          <td>
            <input size=30 type=text name=login value='{$login|escape}' class='ilogin' {literal}pattern='^.{4,255}$' notice='������� ����� (> 4)'{/literal}>
          </td>
        </tr>
        <tr>
          <td>
            ������
          </td>
          <td>
            <input size=30 type=password name=password class='ilogin' {literal}pattern='^.{7,255}$' notice='������� ������ (> 7)'{/literal}>
          </td>
        </tr>
        <tr>
          <td>
            ������������� ������
          </td>
          <td>
            <input size=30 type=password name=password2 class='ilogin' {literal}pattern='^.{7,255}$' notice='������� ������������� (> 7)'{/literal}>
          </td>
        </tr>
        <tr>
          <td>
            ����������� �����
          </td>
          <td>
            <INPUT TYPE=text name=mail size=30 class='ilogin' {literal}pattern='^[0-9a-zA-Z\-\_]+\@[0-9a-zA-Z\-\_]+\.[0-9a-zA-z]+$' notice='������� E-mail (xxx@xxx.xxx)'{/literal}>
          </td>
        </tr>
        <tr>
          <td>
            &nbsp;
          </td>
          <td colspan="2">
           <img src="http://{$Adress}/protect/index.php?{$S_name}={$S_id}">
          </td>
        </tr>
        <tr>
          <td>
            ������� ���
          </td>
          <td>
           <input type="text" name="keystring" value="" class='ilogin' {literal}pattern='^.{3,255}$' notice='������� ��� (> 3)'{/literal}>
          </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>
            <input type=submit value='������������������'>
          </td>
        </tr>
      </table>
      </form>
    </TD>
  </TR>
{else}
<TR>
    <TD>
      <div style="width:90%;margin:5px 5%;">
      <form name=register method=post {literal}onsubmit="if(!document.register.shown.value) {alert('�� ������ ����������� ����������');  return false;} if(!document.register.ok.checked) {alert('�� ������ ������� ����������');  return false;}"
      {/literal}>
      <input name="shown" type="hidden" value="">
      <div id="show" style="width:100%; height:auto;margin:5px 0px;padding:10px;background-color:White;overflow-y:visible;">
      <a href="javascript:void(0);" onclick="document.getElementById('show').innerHTML=document.getElementById('agreement').innerHTML; document.register.shown.value='1';">�������� ����������</a>
      </div>
      <div id="agreement" style="display:none;">
      {$Agreement}
      </div>
      <br /><br />
      <input name="ok" type="checkbox" value="ON">
      &nbsp;&nbsp;&nbsp;
      <input type=submit value='������� ����������'>
      &nbsp;&nbsp;&nbsp;
      <a href="?section=9&download=1">������� ����������</a>
      </form>
      </div>
    </TD>
</TR>
{/if}
</TABLE>