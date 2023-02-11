{config_load file=premium.conf}
<TABLE WIDTH=100% BORDER=0 CELLPADDING=5 CELLSPACING=0>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=subheader colspan=3>
      <a href='index.php'>{$Settings->site_name}</a> / <a href='index.php?section=Users'>{$Lang->USERS}</a> /
      {$Lang->EDIT_USER} &laquo;{$Item->name}&raquo;
    </TD>
  </TR>

  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=header>
      <img src='images/users_categories_icon.jpg' border=0 align=absmiddle>
      {$Lang->EDIT_USER} &laquo;{$Item->name}&raquo;
    </TD>
  </TR>
  <TR>
    <TD HEIGHT=10>
    </TD>
  </TR>
  {if $ErrorMSG}
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP class=error>
      {$ErrorMSG}
    </TD>
  </TR>
  {/if}
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=body>
      <FORM name="user" METHOD="POST">
        <TABLE BORDER=0 CELLPADDING=5 CELLSPACING=0>
          <TR>
            <TD>
              {$Lang->USERNAME}:
            </TD>
            <TD>
              <INPUT NAME='name' TYPE='TEXT' SIZE='80' MAXLENGTH='255' VALUE='{$Item->name|escape}'>
            </TD>
          </TR>
          <TR>
            <TD>
              {$Lang->LOGIN}:
            </TD>
            <TD>
              <INPUT NAME=login TYPE=TEXT SIZE=80 MAXLENGTH=255 disabled VALUE='{$Item->login|escape}'>
            </TD>
          </TR>
          <TR>
            <TD>
              {$Lang->PASSWORD}:
            </TD>
            <TD>
              <INPUT NAME='password' TYPE='password' SIZE='80' MAXLENGTH='255' {literal}pattern='^.{6,255}$' notice='¬ведите ѕароль >6'{/literal}><br>
            </TD>
           </TR>
          <TR>
            <TD>
              {$Lang->PASSWORD}2:
            </TD>
            <TD>
              <INPUT NAME='password2' TYPE='password' SIZE='80' MAXLENGTH='255' {literal}pattern='^.{6,255}$' notice='¬ведите ѕароль >6'{/literal}><br>
            </TD>
          </TR>
          <TR>
            <TD>
              {$Lang->USER_CATEGORY}:
            </TD>
            <TD>
              <SELECT name=category_id>
                <OPTION value=0>{$Lang->UNDEFINED_CATEGORY}</OPTION>
                {foreach item=cat from=$Categories}
                  <OPTION value='{$cat->category_id}' {if $cat->category_id==$Item->category_id}selected{/if}>{$cat->name}</OPTION>
                {/foreach}
              </SELECT>
              &nbsp;<a href='index.php?section=Orders&login={$Item->login}'>{$Lang->ORDERS_HISTORY}</a>
            </TD>
          </TR>
          <TR>
            <TD>
              {$Lang->ADDRESS}:
            </TD>
            <TD>
              <INPUT NAME='mail' TYPE='text' SIZE='80' MAXLENGTH='255' VALUE='{$Item->mail|escape}' {literal}pattern='^[^\@\.]+\@[^\@\.]+\.[0-9a-zA-z]+$' notice='¬ведите E-mail (xxx@xxx.xxx)'{/literal}>
            </TD>
          </TR>
          <TR>
            <TD>
            </TD>
            <TD>
              <INPUT type=checkbox name=active value=1 {if $Item->active==1}checked{/if}> {$Lang->USER_ENABLED}
            </TD>
          </TR>
          <TR>
            <TD>
            </TD>
            <TD>
              <INPUT TYPE=SUBMIT VALUE='{$Lang->SAVE}'>
            </TD>
          </TR>
        </TABLE>
      </FORM>
    </TD>
  </TR>
</TABLE>