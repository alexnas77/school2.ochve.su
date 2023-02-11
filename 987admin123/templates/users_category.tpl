{config_load file=premium.conf}
<TABLE WIDTH=100% BORDER=0 CELLPADDING=5 CELLSPACING=0>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=subheader colspan=3>
      <a href='index.php'>{$Settings->site_name}</a> / <a href='index.php?section=UsersCategories'>{$Lang->USERS_CATEGORIES}</a> /
      {if $Item->category_id}
        {$Lang->EDIT_USERS_CATEGORY}  &laquo;{$Item->name}&raquo;
      {else}
        {$Lang->NEW_USERS_CATEGORY}
      {/if}

    </TD>
  </TR>

  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=header>
      <img src='images/users_categories_icon.jpg' border=0 align=absmiddle>
      {if $Item->category_id}
        {$Lang->EDIT_USERS_CATEGORY} &laquo;{$Item->name}&raquo;
      {else}
        {$Lang->NEW_USERS_CATEGORY}
      {/if}
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
      <FORM id=qwe name=edit_news_item METHOD=POST>
        <TABLE BORDER=0 CELLPADDING=5 CELLSPACING=0>
          <TR>
            <TD width="150px">
              {$Lang->NAME}:
            </TD>
            <TD colspan="2">
              <INPUT NAME=name TYPE=TEXT SIZE=80 MAXLENGTH=255 VALUE='{$Item->name|escape}' {literal}pattern='^.{1,255}$'{/literal} notice='{$Lang->ENTER_NAME}'>
            </TD>
          </TR>
         <TR>
            <TD>
              {$Lang->DISCOUNT}:
            </TD>
            <TD colspan="2">
              <input name="discount" type="text" SIZE=80 value="{$Item->discount}">
            </TD>
          </TR>
         <TR>
            <TD>
              {$Lang->ISADMIN}:
            </TD>
            <TD>
              <input name="isadmin" {if $Item->isadmin}checked{/if} type="checkbox" value="1">
            </TD>
            <TD>
              {$Lang->ISADMIN_HELP}
            </TD>
          </TR>
        </TABLE>
        <TABLE BORDER=0 CELLPADDING=5 CELLSPACING=0>
          <TR>
            <TD width="150px">
              {$Lang->NEWS}:
            </TD>
            <TD>
              <input name="news" {if $Item->news}checked{/if} type="checkbox" value="1">
            </TD>
            <TD>
              {$Lang->NEWS_HELP}
            </TD>
          </TR>
          <TR>
            <TD>
              {$Lang->ARTICLES}:
            </TD>
            <TD>
              <input name="articles" {if $Item->articles}checked{/if} type="checkbox" value="1">
            </TD>
            <TD>
              {$Lang->ARTICLES_HELP}
            </TD>
          </TR>
          <TR>
            <TD>
              {$Lang->POLLS}:
            </TD>
            <TD>
              <input name="polls" {if $Item->polls}checked{/if} type="checkbox" value="1">
            </TD>
            <TD>
              {$Lang->POLLS_HELP}
            </TD>
          </TR>
          <TR>
            <TD>
              {$Lang->SETTINGS}:
            </TD>
            <TD>
              <input name="settings" {if $Item->settings}checked{/if} type="checkbox" value="1">
            </TD>
            <TD>
              {$Lang->SETTINGS_HELP}
            </TD>
          </TR>
          <TR>
            <TD>
              {$Lang->CONTACTS}:
            </TD>
            <TD>
              <input name="contacts" {if $Item->contacts}checked{/if} type="checkbox" value="1">
            </TD>
            <TD>
              {$Lang->CONTACTS_HELP}
            </TD>
          </TR>
          <TR>
            <TD>
              {$Lang->PRODUCTS}:
            </TD>
            <TD>
              <input name="products" {if $Item->products}checked{/if} type="checkbox" value="1">
            </TD>
            <TD>
              {$Lang->PRODUCTS_HELP} {$Lang->BRANDS}, {$Lang->PRODUCTS}, {$Lang->PRODUCTS_CATEGORIES}
            </TD>
          </TR>
          <TR>
            <TD>
              {$Lang->PROPERTIES}:
            </TD>
            <TD>
              <input name="properties" {if $Item->properties}checked{/if} type="checkbox" value="1">
            </TD>
            <TD>
              {$Lang->PRODUCTS_HELP} {$Lang->PROPERTIES}, {$Lang->PROPERTIES_CATEGORIES}, {$Lang->INDEX_SEARCH}
            </TD>
          </TR>
          <TR>
            <TD>
              {$Lang->ORDERS}:
            </TD>
            <TD>
              <input name="orders" {if $Item->orders}checked{/if} type="checkbox" value="1">
            </TD>
            <TD>
              {$Lang->ORDERS_HELP}
            </TD>
          </TR>
          <TR>
            <TD>
              {$Lang->PRICELIST}:
            </TD>
            <TD>
              <input name="pricelist" {if $Item->pricelist}checked{/if} type="checkbox" value="1">
            </TD>
            <TD>
              {$Lang->PRODUCTS_HELP} {$Lang->PRICELIST_IMPORT}, {$Lang->PRICELIST_EXPORT}, {$Lang->PRODUCTS_IMPORT}
            </TD>
          </TR>
          <TR>
            <TD>
              {$Lang->CURRENCIES}:
            </TD>
            <TD>
              <input name="currencies" {if $Item->currencies}checked{/if} type="checkbox" value="1">
            </TD>
            <TD>
              {$Lang->PRODUCTS_HELP} {$Lang->CURRENCIES}, {$Lang->PAYMENT}
            </TD>
          </TR>
          <TR>
            <TD>
              {$Lang->STATISTICS}:
            </TD>
            <TD>
              <input name="statistics" {if $Item->statistics}checked{/if} type="checkbox" value="1">
            </TD>
            <TD>
              {$Lang->STATISTICS_HELP}
            </TD>
          </TR>
          <TR>
            <TD>
              {$Lang->USERS}:
            </TD>
            <TD>
              <input name="users" {if $Item->users}checked{/if} type="checkbox" value="1">
            </TD>
            <TD>
              {$Lang->PRODUCTS_HELP} {$Lang->USERS}, {$Lang->USERS_CATEGORIES}
            </TD>
          </TR>
          <TR>
            <TD>
              {$Lang->USERCOMMENTS}:
            </TD>
            <TD>
              <input name="usercomments" {if $Item->usercomments}checked{/if} type="checkbox" value="1">
            </TD>
            <TD>
              {$Lang->USERCOMMENTS_HELP}
            </TD>
          </TR>
          <TR>
            <TD>
              {$Lang->FAQ}:
            </TD>
            <TD>
              <input name="faq" {if $Item->faq}checked{/if} type="checkbox" value="1">
            </TD>
            <TD>
              {$Lang->FAQ_HELP}
            </TD>
          </TR>
          <TR>
            <TD>
              {$Lang->ANNOUNCEMENT}:
            </TD>
            <TD>
              <input name="announcement" {if $Item->announcement}checked{/if} type="checkbox" value="1">
            </TD>
            <TD>
              {$Lang->ANNOUNCEMENT_HELP}
            </TD>
          </TR>
        </TABLE>
        <TABLE BORDER=0 CELLPADDING=5 CELLSPACING=0>
          <TR>
            <TD width="150px">
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