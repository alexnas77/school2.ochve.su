<TABLE WIDTH=100% BORDER=0 CELLPADDING=10 CELLSPACING=0>
  <TR>
    <TD  ALIGN=LEFT VALIGN=TOP>
      <H1>����</H1>
    </TD>
  </TR>
  <TR>
    <TD>
      {if $login_error}
        <font color=red>{$login_error}</font>
      {/if}
      <form method=post>
      <table>
        <tr>
          <td>
            �����
          </td>
          <td>
            <input type=text name=login value='{$login}'>
          </td>
        </tr>
        <tr>
          <td>
            ������
          </td>
          <td>
            <input type=password name=password>
          </td>
        </tr>
        <tr>
          <td>
          </td>
          <td>
            <input type=submit name=submit value='�����'> &nbsp;&nbsp;&nbsp;
          </td>
        </tr>
      </table>
      </form>
    </TD>
  </TR>
</TABLE>