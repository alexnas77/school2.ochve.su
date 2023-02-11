{config_load file=premium.conf}
{literal}
<style>
input.conf {width:350px;}
select.conf {width:350px;}
textarea.conf {width:350px;}
</style>
{/literal}
<FORM METHOD=POST enctype='multipart/form-data'>
<TABLE WIDTH=100% BORDER=0 CELLPADDING=5 CELLSPACING=0>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=subheader colspan=3>
      <a href='index.php'>{$Settings->site_name}</a> / {$Lang->PAYMENT}
    </TD>
  </TR>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=header colspan=3>
      <img src='images/currency.gif' border=0 align=absmiddle> {$Lang->PAYMENT}
    </TD>
  </TR>
  <TR>
    <TD HEIGHT=10 colspan=3>
    </TD>
  </TR>
  {if $ErrorMSG}
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP class=error colspan=3>
      {$ErrorMSG}
    </TD>
  </TR>
  {/if}
</TABLE>
  <FIELDSET>
<LEGEND><b>Реквизиты Магазина</b></LEGEND>
<TABLE WIDTH=100% BORDER=0 CELLPADDING=5 CELLSPACING=0>
  <TR>
    <TD WIDTH="150px">
        {$Lang->COMPANY_NAME}:
     </TD>
     <TD WIDTH="400px">
        <input type=text name=company_name class=conf value='{$Settings->company_name|escape}'>
     </TD>
     <TD>
        {$Lang->COMPANY_NAME_HELP_PAY}
     </TD>
  </TR>
  <TR>
    <TD>
        {$Lang->COMPANY_ADDRES}:
     </TD>
     <TD>
        <input type=text name=company_addres class=conf value='{$Settings->company_addres|escape}'>
     </TD>
     <TD>
       	{$Lang->COMPANY_ADDRES_HELP_PAY}
     </TD>
  </TR>
  <TR>
    <TD>
        {$Lang->INN}:
     </TD>
     <TD>
        <input type=text name=inn class=conf value='{$Settings->inn|escape}'>
     </TD>
     <TD>
       	{$Lang->INN_HELP_PAY}
     </TD>
  </TR>
  <TR>
    <TD>
        {$Lang->KPP}:
     </TD>
     <TD>
        <input type=text name=kpp class=conf value='{$Settings->kpp|escape}'>
     </TD>
     <TD>
       	{$Lang->KPP_HELP_PAY}
     </TD>
  </TR>
  <TR>
    <TD>
        {$Lang->RESIVER}:
     </TD>
     <TD>
        <input type=text name=resiver class=conf value='{$Settings->resiver|escape}'>
     </TD>
     <TD>
       	{$Lang->RESIVER_HELP_PAY}
     </TD>
  </TR>
  <TR>
    <TD>
        {$Lang->RESIVER_BANK}:
     </TD>
     <TD>
        <input type=text name=resiver_bank class=conf value='{$Settings->resiver_bank|escape}'>
     </TD>
     <TD>
       	{$Lang->RESIVER_BANK_HELP_PAY}
     </TD>
  </TR>
  <TR>
    <TD>
        {$Lang->RESIVER_ACCOUNT}:
     </TD>
     <TD>
        <input type=text name=resiver_account class=conf value='{$Settings->resiver_account|escape}'>
     </TD>
     <TD>
       	{$Lang->RESIVER_ACCOUNT_HELP_PAY}
     </TD>
  </TR>
  <TR>
    <TD>
        {$Lang->BIK}:
     </TD>
     <TD>
        <input type=text name=bik class=conf value='{$Settings->bik|escape}'>
     </TD>
     <TD>
       	{$Lang->BIK_HELP_PAY}
     </TD>
  </TR>
  <TR>
    <TD>
        {$Lang->RESIVER_BANK_ACCOUNT}:
     </TD>
     <TD>
        <input type=text name=resiver_bank_account class=conf value='{$Settings->resiver_bank_account|escape}'>
     </TD>
     <TD>
       	{$Lang->RESIVER_BANK_ACCOUNT_HELP_PAY}
     </TD>
  </TR>
  <TR>
    <TD>
        {$Lang->NDS}:
     </TD>
     <TD>
        <input type=text name=nds class=conf value='{$Settings->nds|escape}'>
     </TD>
     <TD>
       	{$Lang->NDS_HELP_PAY}
     </TD>
  </TR>
  <TR>
    <TD>
        {$Lang->DIRECTOR}:
     </TD>
     <TD>
        <input type=text name=director class=conf value='{$Settings->director|escape}'>
     </TD>
     <TD>
       	{$Lang->DIRECTOR_HELP_PAY}
     </TD>
  </TR>
  <TR>
    <TD>
        {$Lang->GLAVBUH}:
     </TD>
     <TD>
        <input type=text name=glavbuh class=conf value='{$Settings->glavbuh|escape}'>
     </TD>
     <TD>
       	{$Lang->GLAVBUH_HELP_PAY}
     </TD>
  </TR>
  <TR>
     <TD>
       	{$Lang->PHONES}:
     </TD>
     <TD>
        <input type=text name=phones class=conf value='{$Settings->phones|escape}'>
     </TD>
     <TD>
       	{$Lang->PHONES_HELP_PAY}
     </TD>
  </TR>
  <TR>
     <TD>
       	{$Lang->FAX}:
     </TD>
     <TD>
        <input type=text name=fax class=conf value='{$Settings->fax|escape}'>
     </TD>
     <TD>
       	{$Lang->FAX_HELP_PAY}
     </TD>
  </TR>
</TABLE>
</FIELDSET>
  <FIELDSET>
<LEGEND><b>Безналичный расчёт</b></LEGEND>
<TABLE WIDTH=100% BORDER=0 CELLPADDING=5 CELLSPACING=0>
  <TR>
     <TD WIDTH="150px">
       	{$Lang->BILL_EXP}:
     </TD>
     <TD colspan="2">
        <input type=text name=bill_exp class=conf value='{$Settings->bill_exp|escape}'>
       	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
       	{$Lang->BILL_EXP_HELP_PAY}
     </TD>
  </TR>
   <TR>
     <TD WIDTH="150px" align="right" nowrap>
        {$Lang->BILL_TEMPLATE}:
     </TD>
     <TD name="body" colspan="2">
     <textarea {*id="editor"*} name="tranfer" style="width:100%; height: 400px;">{$Settings->tranfer|escape}</textarea>
     </TD>
  </TR>
</TABLE>
</FIELDSET>
  <FIELDSET>
<LEGEND><b>Сберегательный Банк</b></LEGEND>
<TABLE WIDTH=100% BORDER=0 CELLPADDING=5 CELLSPACING=0>
  <TR>
     <TD WIDTH="150px" align="right" nowrap>
        {$Lang->BILL_TEMPLATE}:
     </TD>
     <TD name="body">
     <textarea {*id="editor_s"*} name="sberbank" style="width:100%; height: 400px;">{$Settings->sberbank|escape}</textarea>
     </TD>
  </TR>
</TABLE>
</FIELDSET>
  <FIELDSET>
<LEGEND><b>WebMoney</b></LEGEND>
<TABLE WIDTH=100% BORDER=0 CELLPADDING=5 CELLSPACING=0>
  <TR>
    <TD WIDTH="150px">
        {$Lang->WM_SHOP_PURSE_WMR}:
     </TD>
     <TD WIDTH="400px">
        <input type=text name=wm_shop_purse_wmr class=conf value='{$Settings->wm_shop_purse_wmr|escape}' {*literal}pattern='^R\d{12}$'  notice='Введите R кошелек (R+12 цифр)'{/literal*}>
     </TD>
     <TD>
       	{$Lang->WM_SHOP_PURSE_WMR_HELP}
     </TD>
  </TR>
  <TR>
    <TD>
        {$Lang->WM_SHOP_PURSE_WMZ}:
     </TD>
     <TD>
        <input type=text name=wm_shop_purse_wmz class=conf value='{$Settings->wm_shop_purse_wmz|escape}' {*literal}pattern='^Z\d{12}$'  notice='Введите Z кошелек (Z+12 цифр)'{/literal*}>
     </TD>
     <TD>
       	{$Lang->WM_SHOP_PURSE_WMZ_HELP}
     </TD>
  </TR>
  <TR>
    <TD>
        {$Lang->WM_SHOP_PURSE_WME}:
     </TD>
     <TD>
        <input type=text name=wm_shop_purse_wme class=conf value='{$Settings->wm_shop_purse_wme|escape}' {*literal}pattern='^E\d{12}$'  notice='Введите E кошелек (E+12 цифр)'{/literal*}>
     </TD>
     <TD>
       	{$Lang->WM_SHOP_PURSE_WME_HELP}
     </TD>
  </TR>
  <TR>
    <TD>
        {$Lang->WM_SHOP_WMID}:
     </TD>
     <TD>
        <input type=text name=wm_shop_wmid class=conf value='{$Settings->wm_shop_wmid|escape}' {*literal}pattern='^\d{12}$'  notice='Введите WMID (12 цифр)'{/literal*}>
     </TD>
     <TD>
       	{$Lang->WM_SHOP_WMID_HELP}
     </TD>
  </TR>
  <TR>
    <TD>
        {$Lang->LMI_SECRET_KEY}:
     </TD>
     <TD>
        <input type=text name=lmi_secret_key class=conf value='{$Settings->lmi_secret_key|escape}'>
     </TD>
     <TD>
       	{$Lang->LMI_SECRET_KEY_HELP}
     </TD>
  </TR>
</TABLE>
</FIELDSET>
<TABLE WIDTH=100% BORDER=0 CELLPADDING=5 CELLSPACING=0>
  <TR>
    <TD WIDTH="150px">
    &nbsp;
    </TD>
    <TD WIDTH="400px">
      <INPUT TYPE=SUBMIT NAME=SUBMIT class=conf VALUE='{$Lang->SAVE}'>
    </TD>
    <TD>
    &nbsp;
    </TD>
   </TR>
  </TABLE>
</FORM>