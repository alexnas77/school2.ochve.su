<TABLE  ALIGN=center BORDER=0 CELLPADDING=10 CELLSPACING=0>
  <TR>
    <TD  ALIGN=LEFT VALIGN=TOP>
      <div align="left" style="font-size:16px;background-color: #919191;color: White;padding: 10px 20px;"><b>Вход</b></div>
    </TD>
  </TR>
  <TR>
    <TD>
      {if $login_error}
        <font color=red>{$login_error}</font>
      {/if}
      <form method="post">
          <input type="hidden" name="section" value="8">
          <input type="hidden" name="referrer" value="{$smarty.server.HTTP_REFERER}">
      <table>
        <tr>
          <td>
            Логин
          </td>
          <td>
            <input type=text name=login value='{$login}' class='ilogin'>
          </td>
        </tr>
        <tr>
          <td>
            Пароль
          </td>
          <td>
            <input type=password name=password class='ilogin'>
          </td>
        </tr>
        <tr>
          <td>
          </td>
          <td>
            <input type=submit value='Войти' class='button2'>{* &nbsp;&nbsp;&nbsp;<a href='register' style="color:#6C2B33; text-decoration: underline;">Регистрация</a>*}
          </td>
        </tr>
      </table>
      </form>
    </TD>
  </TR>
</TABLE>