{config_load file=premium.conf}
<TABLE WIDTH=100% BORDER=0 CELLPADDING=5 CELLSPACING=0>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=subheader colspan=3>
      <a href='index.php'>{$Settings->site_name}</a> / <a href='index.php?section=Announcement'>{$Lang->ANNOUNCEMENT}</a> /
        {$Lang->ANNOUNCEMENT} &laquo;{$Item->id}&raquo;
    </TD>
  </TR>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=header>
      <img src='images/articles_icon.png' border=0 align=absmiddle>
       {$Lang->ANNOUNCEMENT} &laquo;{$Item->id}&raquo;
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
      <FORM name=edit METHOD=POST enctype='multipart/form-data'>
        <TABLE BORDER=0 CELLPADDING=5 CELLSPACING=0>
          <TR>
            <TD align=right>
              {$Lang->SALE_TYPE}:
            </TD>
            <TD>
                <select size="1" name="sale" style="width:500px;">
  				<option value="0" {if $Item->sale=='0'}selected{/if}>Покупка</option>
  				<option value="1" {if $Item->sale=='1'}selected{/if}>Продажа</option>
  				</select>
            </TD>
          </TR>
          <TR>
            <TD align=right>
              {$Lang->CATEGORY}:
            </TD>
            <TD>
                <select size="1" name="category" style="width:500px;">
  				<option value="1" {if $Item->category=='1'}selected{/if}>Скутер</option>
  				<option value="2" {if $Item->category=='2'}selected{/if}>Мотоцикл</option>
  				<option value="3" {if $Item->category=='3'}selected{/if}>Запчасти</option>
  				</select>
            </TD>
          </TR>
          <TR>
            <TD align=right>
              {$Lang->DATE}:
            </TD>
            <TD>
              <INPUT NAME=date TYPE=TEXT SIZE=80 MAXLENGTH=255 VALUE='{$Item->date|escape}'>
            </TD>
          </TR>
          <TR>
            <TD align=right>
              {$Lang->EXPIRES}:
            </TD>
            <TD>
              <INPUT NAME=range TYPE=TEXT SIZE=80 MAXLENGTH=255 VALUE='{$Item->range|escape}'>
            </TD>
          </TR>
          <TR>
            <TD align=right>
              {$Lang->BRAND}:
            </TD>
            <TD>
              <INPUT NAME=brand TYPE=TEXT SIZE=80 MAXLENGTH=255 VALUE='{$Item->brand|escape}'>
            </TD>
          </TR>
          <TR>
            <TD align=right>
              {$Lang->MODEL}:
            </TD>
            <TD>
              <INPUT NAME=model TYPE=TEXT SIZE=80 MAXLENGTH=255 VALUE='{$Item->model|escape}'>
            </TD>
          </TR>
          <TR>
            <TD align=right>
              {$Lang->YEAR}:
            </TD>
            <TD>
              <INPUT NAME=year TYPE=TEXT SIZE=80 MAXLENGTH=255 VALUE='{$Item->year|escape}'>
            </TD>
          </TR>
          <TR>
            <TD align=right>
              {$Lang->VOLUME}, куб.см :
            </TD>
            <TD>
              <INPUT NAME=volume TYPE=TEXT SIZE=80 MAXLENGTH=255 VALUE='{$Item->volume|escape}'>
            </TD>
          </TR>
          <TR>
            <TD align=right>
              {$Lang->CITY}:
            </TD>
            <TD>
              <INPUT NAME=city TYPE=TEXT SIZE=80 MAXLENGTH=255 VALUE='{$Item->city|escape}'>
            </TD>
          </TR>
          <TR>
            <TD align=right>
              {$Lang->PRICE}:
            </TD>
            <TD>
              <INPUT NAME=price TYPE=TEXT SIZE=80 MAXLENGTH=255 VALUE='{$Item->price}'>
            </TD>
          </TR>
          <TR>
            <TD align=right>
              {$Lang->MAIL}:
            </TD>
            <TD>
              <INPUT NAME=mail TYPE=TEXT SIZE=80 MAXLENGTH=255 VALUE='{$Item->mail|escape}'>
            </TD>
          </TR>
          <TR>
            <TD align=right>
              {$Lang->PHONE}:
            </TD>
            <TD name=body>
              <INPUT NAME=phone TYPE=TEXT SIZE=80 MAXLENGTH=255 VALUE='{$Item->phone|escape}'>
            </TD>
          </TR>
          <TR>
            <TD align=right>
              {$Lang->CONTACT}:
            </TD>
            <TD name=body>
              <INPUT NAME=contact TYPE=TEXT SIZE=80 MAXLENGTH=255 VALUE='{$Item->contact|escape}'>
            </TD>
          </TR>
          <TR>
            <TD align=right>
              {$Lang->SHORT_DESCRIPTION}:
            </TD>
            <TD name=body>
              <textarea id="editor" name="body" style="width: 100%; height: 400px;">{$Item->body}</textarea>
            </TD>
          </TR>
          <TR>
            <TD align=right>
              {$Lang->PHOTO}:
            </TD>
            <TD name=body>
            <input type=hidden value='' name=delete_fotos>
              <input name="foto" type="file" size=80><br />
              {if $Item->file}
              <a target="_blank" href="../foto/usr/{$Item->file}"><img id="image" src="../foto/usr/{$Item->file}" width="150" alt="{$Item->file}" border="0" style="margin:10px"></a>
              <a href='javascript:' onclick="javascript: window.document.getElementById('image').src='images/no_foto.gif'; window.document.edit.delete_fotos.value += '{$Item->id},';"><img src='images/delete.gif' border=0 width=16 height=16 align=middle></a>
              {/if}

            </TD>
          </TR>
          <TR>
            <TD>
              {$Lang->FSHOW}:
            </TD>
            <TD>
              <input name=enabled value='1' type=checkbox {if $Item->enabled}checked{/if}>
            </TD>
          </TR>
          {*<TR>
            <TD>
              {$Lang->SEND}:
            </TD>
            <TD>
              <input name=send value='1' type=checkbox checked>
            </TD>
          </TR>*}
          <TR>
            <TD>
             <INPUT TYPE=RESET VALUE='Сбросить'>
            </TD>
            <TD>
              <INPUT name=save TYPE=SUBMIT VALUE='{$Lang->SAVE}'>
            </TD>
          </TR>
        </TABLE>
      </FORM>
    </TD>
  </TR>
</TABLE>