<H1>�������</H1>
<FORM METHOD=POST>
<TABLE cellpadding=0 cellspacing=10 >
  {foreach name=products item=product from=$Products}
  <tr height=120>
    <TD align=center valign=top width=120 height=120>
      <table width=120 height=120 cellpadding=0 cellspacing=0  ALIGN=center valign=center>
        <tr>
          <td ALIGN=center valign=center style='height:120px; border: 1px solid lightgray; padding:10'>
            <A TITLE='{$product->brand|escape}{$product->model|escape}' HREF='catalog/{$product->category_id}/{$product->brand|escape}/{$product->product_id}.html' CLASS=subheader><IMG width='150px' ALT='{$product->brand|escape} {$product->model|escape}' border=0 src='{if $product->filename}foto/storefront/{$product->filename}{else}images2/no_foto.gif{/if}'></A>
          </td>
        </tr>
      </table>
    </TD>
    <TD ALIGN=LEFT valign=top style='border-bottom: 1px dotted lightgray;'>
    <table cellpadding=5 cellspacing=0 width=100%>
      <tr>
        <td colspan=4>
          <A TITLE='{$product->brand|escape} {$product->model}' HREF='catalog/{$product->category_id}/{$product->brand|escape}/{$product->product_id}.html' CLASS=product_name>{$product->category} {$product->brand} {$product->model}</a><br>
        </td>
      </tr>
      <tr>
        <td>
          &nbsp;
        </td>
        <td align=right width=150>
            <span class=price>{$product->discount_price/$product->currency_rate*$Currency->rate|string_format:"%.2f"} {$Currency->sign}</span>
        </td>
        <td align=right width=150>
            <b><input name=quantities[{$product->product_id}] type=text size=4 value='{$product->quantity}'>&nbsp;��.</b><BR>
        </td>
        <td width=100>
            <a href="index.php?section=7&delete_product_id={$product->product_id}">�������</a>
        </td>
      </tr>
    </table>
    </TD>
    </tr>
  {/foreach}
  <tr>
    <TD align=center valign=top width=120 height=50>
    </TD>
    <TD ALIGN=LEFT valign=top style='border-bottom: 1px dotted lightgray;'>
    <table cellpadding=5 cellspacing=0 width=100%>
      <tr>
        <td colspan=4>
        </td>
      </tr>
      <tr>
        <td align=right valign=bottom style='font-family:tahoma; font-size:16pt; font-weight:normal;'>
          �����:
        </td>
        <td valign=bottom align=right>
            <span class=price>{$Price*$Currency->rate|string_format:"%.2f"} {$Currency->sign}</span>
        </td>
        <td colspan=2 valign=bottom align=right width=250>
           <input type=submit value='�����������'>
        </td>
      </tr>
    </table>
    </TD>
    </tr>
</TABLE>
</FORM>

<form name=order method=post style="width:600px">

<TABLE width='600px' BORDER='0' style='position: relative; left:20;'>
  <TR>
    <TD colspan=2>
      <h1>�����</h1>
    </TD>
  </TR>
  <TR>
    <TD colspan=2>
     <b style="color:Red">{$Error}</b>
     {*if !$User}<div align="center"><a href='register' style="color:#6C2B33; text-decoration: underline;"><b><big>�����������</big></b></a></div><br /><br />{/if*}
    </TD>
  </TR>
</TABLE>
<FIELDSET style='position: relative; left:20;'>
<LEGEND><b>������ ������</b></LEGEND>
<TABLE width='600px' BORDER='0' {*if !$User}onclick="alert('��� ���������� ������ ���������� �������������� ��� ������������������!'); return false;"{/if*}>
  <TR>
      <TD width="300px">
        ���� ���:
      </TD>
      <TD>
        <INPUT TYPE=text name=name size=50 {literal}pattern='^.{3,255}$'  notice='������� ���� ��� (��� 3)'{/literal} value='{if $Name}{$Name|escape}{else}{$User->name}{/if}' style="width:350px">
      </TD>
  </TR>
  <TR>
      <TD>
        ����������� �����:
      </TD>
      <TD>
        <INPUT TYPE=text name=mail size=50 {literal}pattern='^[0-9a-zA-Z\-\_\.]+\@[0-9a-zA-Z\-\_]+\.[0-9a-zA-z]+$' notice='������� E-mail (xxx@xxx.xxx)'{/literal} value='{if $Mail}{$Mail|escape}{else}{$User->mail}{/if}' style="width:350px">
      </TD>
  </TR>
  <TR>
      <TD>
        ���������� �������:
      </TD>
      <TD>
        <INPUT TYPE=text name=phone size=50 {literal}pattern='^.{7,255}$'  notice='������� ���������� ������� (��� 7)'{/literal} value='{$Phone|escape}' style="width:350px">
      </TD>
  </TR>
  <TR>
      <TD>
        �����:
      </TD>
      <TD>
        <INPUT TYPE=text name=city size=50 value='{$City|escape}' style="width:350px" {literal}pattern='^.{3,255}$'  notice='������� ���������� (��� 3)'{/literal}>
      </TD>
   </TR>
  {*<TR>
      <TD>
        ��������� ������� �����:
      </TD>
      <TD>
<script type="text/javascript">

</script>
        <INPUT TYPE=text name=metro size=50 {literal}pattern='^.{2,255}$'  notice='������� ���������� (��� 2)'{/literal} value='{$Metro|escape}' style="width:350px">
      </TD>
  </TR>*}
  <TR>
      <TD>
        �����:
      </TD>
      <TD>
        <INPUT TYPE=text name=address size=50 {literal}pattern='^.{7,255}$'  notice='������� ����� �������� (��� 7)'{/literal} value='{$Address|escape}' style="width:350px">
      </TD>
  </TR>
  <TR>
      <TD>
        �������������� ����������:
      </TD>
      <TD>
        <INPUT TYPE=text name=comment size=50 value='{$Comment|escape}' style="width:350px">
      </TD>
   </TR>
  {*<TR>
      <TD>
        ��� �� ������ � �������� "�����":
      </TD>
      <TD>
<select size="1" name="know" style="width:355px">
{html_options values=$Knows output=$Knows selected=$Know}
</select>
      </TD>
   </TR>
  <TR>
      <TD>
����������� ���:
      </TD>
      <TD>
<select size="1" name="place" style="width:355px">
{html_options values=$Places output=$Places selected=$Place}
</select>
      </TD>
   </TR>
  <TR>
      <TD colspan="2">
      <hr>
���� �� ���� � ����� �� ����� ����������� �����, ����������, �������� ��� �� ������. ���� ������������ ����� ��� ����� ������������. �������.
      <hr>
      </TD>
   </TR>
  <TR>
      <TD>
        ����� ������� ������������ �� �������:
      </TD>
      <TD>
        <input name="consult" type="radio" value="��������� �������� ������" checked> ��������� �������� ������<br />
        <input name="consult" type="radio" value="��������� ������������ ������"> ��������� ������������ ������<br />
      </TD>
   </TR>
  <TR>
      <TD colspan="2">
      <hr>
���� �� ������� �������� ����� ��������� ������ ���������, �������� ��� � �����������
      <hr>
      </TD>
   </TR>*}

  <TR>
      <TD>
        ����� ������:
      </TD>
      <TD>
    <select size="1" name="pay" style="width:355px" onchange="document.order.submit();">
    {html_options options=$Pays selected=$Pay}
	</select>
      </TD>
  </TR>
  {if $Pay!=4}
  <TR>
      <TD>
        ����� ��������:
      </TD>
      <TD>
    <select size="1" name="del" style="width:355px" onchange="document.order.submit();">
    {html_options options=$Delivery selected=$Del}
	</select>
      </TD>
  </TR>
  <TR>
      <TD>
        ������� ��:
      </TD>
      <TD>
      <INPUT TYPE=text name=tk size=50 value='{$Tk|escape}' style="width:350px">
      </TD>
  </TR>
  {/if}
  {if $Pay==1}
  <TR>
      <TD>
        ��������� �����������:
      </TD>
      <TD>
	{*<INPUT TYPE=text name=inn_payer size=50 value='{$Inn_payer|escape}' style="width:350px">*}
	<textarea name="inn_payer" rows=10 wrap="off" style="width:350px">{$Inn_payer|escape}</textarea>
      </TD>
   </TR>
  {*<TR>
      <TD>
        ������������ �����������:
      </TD>
      <TD>
	<INPUT TYPE=text name=name_payer size=50 value='{$Name_payer|escape}' style="width:350px">
      </TD>
   </TR>
  <TR>
      <TD>
        ����������� ����� �����������:
      </TD>
      <TD>
	<INPUT TYPE=text name=ur_adress size=50 value='{$Ur_adress|escape}' style="width:350px">
      </TD>
   </TR>*}
  {/if}
  {if $Pay==2}
  <TR>
      <TD>
        ������:
      </TD>
      <TD>
    <select size="1" name="currency">
    {foreach item=item key=key from=$Selects}
    <option value="{$key}" {if $key==$Currency->code}selected{/if}>{$item} - {$Price*$Rates.$key|string_format:"%.2f"} {$Signs.$key}</option>
    {/foreach}
	</select>
      </TD>
   </TR>
  {/if}
</TABLE>
</FIELDSET>
{*<FIELDSET>
<LEGEND><b>���������� ��� ���������� �����<sup>*</sup></b></LEGEND>
<TABLE width='600px' BORDER='0' style='position: relative; left:147;' {if !$User}onclick="alert('��� ���������� ������ ���������� �������������� ��� ������������������!'); return false;"{/if}>
   <TR>
      <TD colspan="2" align="left" style="border-bottom:1px solid black;">
       <b>�������</b>
      </TD>
   </TR>
  <TR>
      <TD>
        �����:
      </TD>
      <TD>
        <INPUT TYPE=text name=series size=50 {literal}pattern='^.{4}$'  notice='������� ���������� (4 �������)'{/literal} value='{$Series|escape}' style="width:350px">
      </TD>
  </TR>
  <TR>
      <TD>
        �����:
      </TD>
      <TD>
        <INPUT TYPE=text name=number size=50 {literal}pattern='^.{6}$'  notice='������� ���������� (6 ��������)'{/literal} value='{$Number|escape}' style="width:350px">
      </TD>
  </TR>
  <TR>
      <TD>
        ����� � ��� �����:
      </TD>
      <TD>
        <textarea name="given" rows=5 style="width:86%;overflow-y:auto;width:350px">{$Given|escape}</textarea>
      </TD>
  </TR>
  <TR>
      <TD width="250px">
        ����� ��������/�����������:
      </TD>
      <TD>
        <textarea name="registration" rows=5 style="width:86%;overflow-y:auto;width:350px">{$Registration|escape}</textarea>
      </TD>
  </TR>
   <TR>
      <TD colspan="2" align="left" style="border-bottom:1px solid black;">
       <b>� ����</b>
      </TD>
   </TR>
  <TR>
      <TD>
        �������:
      </TD>
      <TD>
        <INPUT TYPE=text name=lastname size=50 value='{$Lastname|escape}' style="width:350px" {literal}pattern='^.{3,255}$'  notice='������� ���������� (��� 3)'{/literal}>
      </TD>
   </TR>
  <TR>
      <TD>
        ���:
      </TD>
      <TD>
        <INPUT TYPE=text name=firstname size=50 value='{$Firstname|escape}' style="width:350px" {literal}pattern='^.{3,255}$'  notice='������� ���������� (��� 3)'{/literal}>
      </TD>
   </TR>
  <TR>
      <TD>
        ��������:
      </TD>
      <TD>
        <INPUT TYPE=text name=middle size=50 value='{$Middle|escape}' style="width:350px" {literal}pattern='^.{3,255}$'  notice='������� ���������� (��� 3)'{/literal}>
      </TD>
   </TR>
  <TR>
      <TD>
        �����:
      </TD>
      <TD>
        <INPUT TYPE=text name=city size=50 value='{$City|escape}' style="width:350px" {literal}pattern='^.{3,255}$'  notice='������� ���������� (��� 3)'{/literal}>
      </TD>
   </TR>
  <TR>
      <TD>
        ������:
      </TD>
      <TD>
        <INPUT TYPE=text name=index size=50 value='{$Index|escape}' style="width:350px" {literal}pattern='^.{6}$' notice='������� ���������� (6 ��������)'{/literal}>
      </TD>
   </TR>
   <TR>
      <TD colspan="2" align="left">
       <sup>*</sup> - ��� ������ ���������� ��� ����������� ����� � ���������� ����������� �������������
      </TD>
   </TR>
</TABLE>
</FIELDSET>*}
<TABLE width='600px' BORDER='0' style='position: relative; left:147;'>
   <TR>
      <TD colspan="2" align="center">
       <INPUT TYPE=SUBMIT NAME=SUBMIT VALUE='�������� �����' {if !$Products}onclick="alert('�������� ������ � �������'); return false;"{/if}
        {*if !$User}onclick="alert('��� ���������� ������ ���������� �������������� ��� ������������������!'); return false;"{/if*}>
      </TD>
   </TR>
</TABLE>


</FORM>
