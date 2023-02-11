{config_load file=premium.conf}
{literal}
<style>
input{width:350px;}
select{width:350px;}
textarea{width:350px;}
</style>
{/literal}
<FORM METHOD=POST enctype='multipart/form-data'>
<TABLE WIDTH=100% BORDER=0 CELLPADDING=5 CELLSPACING=0>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=subheader colspan=3>
      <a href='index.php'>{$Settings->site_name}</a> / {$Lang->SETTINGS}
    </TD>
  </TR>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=header colspan=3>
      <img src='images/setup_icon.gif' border=0 align=absmiddle> {$Lang->SETTINGS}
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
  <TR>
    <TD>
        {$Lang->SITE_NAME}:
     </TD>
     <TD>
        <input type=text name=site_name value='{$Settings->site_name|escape}'>
     </TD>
     <TD>
       	{$Lang->SITE_NAME_HELP}
     </TD>
  </TR>
  <TR>
    <TD>
        {$Lang->COMPANY_NAME}:
     </TD>
     <TD>
        <input type=text name=company_name value='{$Settings->company_name|escape}'>
     </TD>
     <TD>
        {$Lang->COMPANY_NAME_HELP}
     </TD>
  </TR>
  <TR>
    <TD>
        {$Lang->ADMIN_EMAIL}:
     </TD>
     <TD>
        <input type=text name=admin_email value='{$Settings->admin_email|escape}'>
     </TD>
     <TD>
        {$Lang->ADMIN_EMAIL_HELP}
     </TD>
  </TR>
  <TR>
    <TD>
       	{$Lang->MAIN_PAGE}:
     </TD>
     <TD>
        <select name=main_section>
        {foreach name=sections item=section from=$Sections}
          <option value='{$section->url}' {if $Settings->main_section == $section->url}selected{/if}>{$section->name}</option>
        {/foreach}
        </select>
     </TD>
     <TD>
        {$Lang->MAIN_PAGE_HELP}
     </TD>
  </TR>
  <TR>
    <TD>
        {$Lang->META_TITLE}:
     </TD>
     <TD>
        <input type=text name=title value='{$Settings->title|escape}'>
     </TD>
     <TD>
        {$Lang->META_TITLE_HELP}
     </TD>
  </TR>
  <TR>
    <TD>
        {$Lang->META_KEYWORDS}:
     </TD>
     <TD>
        <input type=text name=keywords value='{$Settings->keywords|escape}'>
     </TD>
     <TD>
        {$Lang->META_KEYWORDS_HELP}
     </TD>
  </TR>
  <TR>
    <TD>
        {$Lang->META_DESCRIPTION}:
     </TD>
     <TD>
        <input type=text name=description value='{$Settings->description|escape}'>
     </TD>
     <TD>
        {$Lang->META_DESCRIPTION_HELP}
     </TD>
  </TR>
  <TR>
     <TD>
       	{$Lang->PHONES}:
     </TD>
     <TD>
        <input type=text name=phones value='{$Settings->phones|escape}'>
     </TD>
     <TD>
       	{$Lang->PHONES_HELP}
     </TD>
  </TR>
  <TR>
     <TD>
        {$Lang->COUNTERS_CODE}:
     </TD>
     <TD>
        <textarea cols=30 rows=5 name=counters>{$Settings->counters}</textarea>
     </TD>
     <TD>
        {$Lang->COUNTERS_CODE_HELP}
     </TD>
  </TR>

  <TR>
     <TD>
       	{$Lang->FOOTER_TEXT}:
     </TD>
     <TD>
        <input type=text name=footer_text value='{$Settings->footer_text|escape}'>
     </TD>
     <TD>
       	{$Lang->FOOTER_TEXT_HELP}
     </TD>
  </TR>
  <TR>
     <TD>
       	{$Lang->LINK_EMAIL_TEXT}:
     </TD>
     <TD>
        <input type=text name=link_email value='{$Settings->link_email|escape}'>
     </TD>
     <TD>
       	{$Lang->LINK_EMAIL_TEXT_HELP}
     </TD>
  </TR>
    <TR>
        <TD>
            Название резервной базы данных:
        </TD>
        <TD>
            <input type=text name=backup_db value='{$Settings->backup_db|escape}'>
        </TD>
        <TD>
            Название резервной базы данных
        </TD>
    </TR>
    <TR>
        <TD>
            Начало учебного года:
        </TD>
        <TD>
            <input type=text name=start id=start value='{$Settings->start|escape}' {*onfocus="showCalendar('',this,this,'','holder',5,5,1)"*}><img style='position:relative;right:23;top:0' border=0 src=calendar/calendar.gif>
        </TD>
        <TD>
            Дата начала учебного года
        </TD>
    </TR>
    <TR>
        <TD>
            Окончание учебного года:
        </TD>
        <TD>
            <input type=text name=end id=end value='{$Settings->end|escape}' {*onfocus="showCalendar('',this,this,'','holder',5,5,1)"*}><img style='position:relative;right:23;top:0' border=0 src=calendar/calendar.gif>
        </TD>
        <TD>
            Дата окончания учебного года
        </TD>
    </TR>
    <TR>
        <TD>
            Завтрак бесплатный:
        </TD>
        <TD>
            <input type=text name=breakfast_free value='{$Settings->breakfast_free|escape}'>
        </TD>
        <TD>
            Стоимость завтрака бесплатного, руб
        </TD>
    </TR>
    <TR>
        <TD>
            Завтрак:
        </TD>
        <TD>
            <input type=text name=breakfast value='{$Settings->breakfast|escape}'>
        </TD>
        <TD>
            Стоимость завтрака, руб
        </TD>
    </TR>
  <TR>
      <TD>
          Обед:
      </TD>
      <TD>
          <input type=text name=lunch value='{$Settings->lunch|escape}'>
      </TD>
      <TD>
          Стоимость обеда, руб
      </TD>
  </TR>
  <TR>
      <TD>
          Завтрак льготный:
      </TD>
      <TD>
          <input type=text name=lunch2 value='{$Settings->lunch2|escape}'>
      </TD>
      <TD>
          Стоимость завтрака льготного, руб
      </TD>
  </TR>
      <TD>
          Обед 2:
      </TD>
      <TD>
          <input type=text name=lunch3 value='{$Settings->lunch3|escape}'>
      </TD>
      <TD>
          Стоимость обеда 2, руб
      </TD>
  </TR>
  <TR>
      <TD>
          Полдник:
      </TD>
      <TD>
          <input type=text name=dinner value='{$Settings->dinner|escape}'>
      </TD>
      <TD>
          Стоимость полдника, руб
      </TD>
  </TR>
  <TR>
    <TD>
    </TD>
    <TD>
      <INPUT TYPE=SUBMIT VALUE='{$Lang->SAVE}'>
    </TD>
    <TD>
    </TD>
   </TR>
  </TABLE>
</FORM>
{literal}<script type="text/javascript">
$( "#start" ).datepicker({ dateFormat: "dd.mm.yy", monthNames: [ "Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь" ], dayNamesMin: [ "Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб" ], firstDay: 1 });
$( "#end" ).datepicker({ dateFormat: "dd.mm.yy", monthNames: [ "Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь" ], dayNamesMin: [ "Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб" ], firstDay: 1 });
</script>{/literal}