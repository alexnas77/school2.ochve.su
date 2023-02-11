{config_load file=premium.conf}
<HTML>

<HEAD>
  <TITLE>{$Settings->site_name}</TITLE>
  <LINK REL='stylesheet' HREF='{#CSSFile#}'>
  <META HTTP-EQUIV='Content-Type' CONTENT='text/html; charset={#Charset#}'>
</HEAD>

<BODY BACKGROUND='images/bgd.gif' TOPMARGIN=0 BOTTOMMARGIN=0 LEFTMARGIN=0 RIGHTMARGIN=0 BGCOLOR='#D3D8E1'>

<TABLE WIDTH=100% HEIGHT=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>
  <TR>
    <TD>
      <!-- Left free space --> &nbsp;
    </TD>
    <TD WIDTH=7 BACKGROUND='images/leftline.gif' BGCOLOR='#BDC5D1'>
      <!-- Left side line -->
    </TD>
    <TD WIDTH=900 BGCOLOR=WHITE ALIGN=CENTER VALIGN=TOP BGCOLOR='#FFFFFF'>
      <!-- Main  width=729-->

<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>
  <TR>
    <TD HEIGHT=25 BGCOLOR='#FFFFFF'>
      &nbsp;
    </TD>
  </TR>
  <TR>
    <TD HEIGHT=23>

      <TABLE WIDTH=100% HEIGHT=23 BORDER=0 CELLPADDING=0 CELLSPACING=0>
       <TR HEIGHT=23>
         <TD WIDTH=142 ALIGN=CENTER BGCOLOR='#FFFFFF'>
           <A TITLE='{$Lang->HomeText}' HREF='{#Indexfile#}'><IMG SRC='images/home.gif' WIDTH=23 HEIGHT=23 BORDER=0 ALIGN=ABSMIDDLE></A>
           <A TITLE='{$Lang->HomeText}' HREF='{#Indexfile#}' class=homelink>{$Lang->HomeText}</A>
         </TD>
         <TD WIDTH=159 BACKGROUND='images/headergrid.gif' BGCOLOR='#F1F2F6'>
          &nbsp;
         </TD>
         <TD ALIGN=CENTER class=toplinks BGCOLOR='#FFFFFF'>
            Вы вошли как &laquo;{$User->name|escape}&raquo;
         </TD>
         <TD>
         <a href="index.php?section=Login&action=logout"><big>Выйти</big></a>
         </TD>
         <TD WIDTH=30 BACKGROUND='images/headergrid.gif' BGCOLOR='#F1F2F6'>
          &nbsp;
         </TD>
       </TR>
      </TABLE>

    </TD>
  </TR>
  <TR>
    <TD HEIGHT=25 BGCOLOR='#FFFFFF'>
      &nbsp;
    </TD>
  </TR>
  <TR>
    <TD HEIGHT=29>

      <TABLE WIDTH=100% HEIGHT=29 BORDER=0 CELLPADDING=0 CELLSPACING=0>
       <TR>
         <TD WIDTH=142 HEIGHT=29 ALIGN=CENTER BACKGROUND='images/headline.gif' BGCOLOR='#BDC5D1'>
           &nbsp;
         </TD>
         <TD WIDTH=159 BGCOLOR='#C3CAD8'>
           <A  HREF='{#Indexfile#}'><IMG SRC='images/premiumstyle.gif' WIDTH=159 HEIGHT=29 BORDER=0 ALIGN=ABSMIDDLE></A>
         </TD>
         <TD ALIGN=CENTER BACKGROUND='images/headline.gif' BGCOLOR='#BDC5D1'>
          &nbsp;
         </TD>
        </TR>
      </TABLE>

    </TD>
  </TR>
  <TR>
    <TD HEIGHT=25 BGCOLOR='#FFFFFF'>
      &nbsp;
    </TD>
  </TR>
  <TR>
    <TD align=center>


<TABLE WIDTH=90% BORDER=0 CELLPADDING=5 CELLSPACING=0>
  <TR>
    <TD ALIGN=left colspan=3 VALIGN=TOP CLASS=header>
        {$Settings->site_name}
    </TD>
  </TR>
  <TR>
    <TD HEIGHT=10 COLSPAN=2>
    </TD>
  </TR>
  <TR>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=body VALIGN=CENTER width=33%>
      <TABLE>
    	  {foreach name=menulist key=key item=item from=$MenuList}
        <TR>
          <TD VALIGN=CENTER height=50>
            <a title="{$item->name}" href='index.php?section=Sections&menu={$item->menu_id}'><img border=0 alt='{$item->name}' src='images/menu_icon.gif'></a>
          </TD>
          <TD VALIGN=CENTER>
            <a title="{$item->name}" href='index.php?section=Sections&menu={$item->menu_id}' class=subheader>{$item->name}</A>
          </TD>

        </TR>
	      {/foreach}
	      {*{if $User->products}
	    <TR>
          <TD align=center valign=center height=50>
            <a title="{$Lang->BRANDS}" href='index.php?section=Brands'><img alt='{$Lang->BRANDS}' border=0 src='images/storefront_icon.gif' align=absmiddle></a>
          </TD>
          <TD align=left valign=center>
            <a title="{$Lang->BRANDS}" href='index.php?section=Brands'  class=subheader>{$Lang->BRANDS}</A>
          </TD>
        </TR>
        {/if}
        {if $User->news}
        <TR>
          <TD align=center valign=center  height=50>
            <a title="{$Lang->NEWS}" href='index.php?section=NewsLine'><img alt='{$Lang->NEWS}' border=0 src='images/news_icon.gif'></a>
          </TD>
          <TD align=left valign=center>
            <a title="{$Lang->NEWS}" href='index.php?section=NewsLine'  class=subheader>{$Lang->NEWS}</A>
          </TD>
        </TR>
        {/if}
        {if $User->articles}
        <TR>
          <TD align=center valign=center  height=50>
            <a title="{$Lang->ARTICLES}" href='index.php?section=Articles'><img alt='{$Lang->ARTICLES}' border=0 src='images/articles_icon.png'></a>
          </TD>
          <TD align=left valign=center>
            <a title="{$Lang->ARTICLES}" href='index.php?section=Articles'  class=subheader>{$Lang->ARTICLES}</A>
          </TD>
        </TR>
        {/if}
        <TR>
          <TD align=center valign=center  height=50>
            <a title="{$Lang->LINKS}" href='index.php?section=Links'><img alt='{$Lang->LINKS}' border=0 src='images/articles_icon.png'></a>
          </TD>
          <TD align=left valign=center>
            <a title="{$Lang->LINKS}" href='index.php?section=Links'  class=subheader>{$Lang->LINKS}</A>
          </TD>
        </TR>
        <TR>
          <TD align=center valign=center height=50>
            <a title="{$Lang->LINKS_CATEGORIES}" href='index.php?section=LinksCategories'><img border=0 alt='{$Lang->LINKS_CATEGORIES}' src='images/categories_icon.gif' align=absmiddle></a>
          </TD>
          <TD align=left valign=center>
            <a title="{$Lang->LINKS_CATEGORIES}" href='index.php?section=LinksCategories'  class=subheader>{$Lang->LINKS_CATEGORIES}</A>
          </TD>
        </TR>
        <TR>
          <TD align=center valign=center  height=50>
            <a title="{$Lang->OUR_LINKS}" href='index.php?section=OurLinks'><img alt='{$Lang->OUR_LINKS}' border=0 src='images/articles_icon.png'></a>
          </TD>
          <TD align=left valign=center>
            <a title="{$Lang->OUR_LINKS}" href='index.php?section=OurLinks'  class=subheader>{$Lang->OUR_LINKS}</A>
          </TD>
        </TR>*}
        {if $User->settings}
        <TR>
          <TD VALIGN=CENTER  height=50>
            <a title="{$Lang->SETTINGS}" href='index.php?section=Setup'><img border=0 alt='{$Lang->SETTINGS}' src='images/setup_icon.gif'></a>
          </TD>
          <TD VALIGN=CENTER>
            <a title="{$Lang->SETTINGS}" href='index.php?section=Setup' class=subheader>{$Lang->SETTINGS}</A>
          </TD>
        </TR>
        {/if}
        {*{if $User->contacts}
         <TR>
          <TD align=center valign=center  height=50>
            <a title="{$Lang->CONTACTS}" href='index.php?section=Contacts'><img alt='{$Lang->CONTACTS}' border=0 src='images/articles_icon.png'></a>
          </TD>
          <TD align=left valign=center>
            <a title="{$Lang->CONTACTS}" href='index.php?section=Contacts'  class=subheader>{$Lang->CONTACTS}</A>
          </TD>
        </TR>
        {/if}
        {if $User->currencies}
       <TR>
          <TD align=center valign=center height=50>
            <a title="{$Lang->CURRENCIES}" href='index.php?section=Currency'><img border=0 alt='{$Lang->CURRENCIES}' src='images/currency.gif' align=absmiddle></a>
          </TD>
          <TD align=left valign=center>
            <a title="{$Lang->CURRENCIES}" href='index.php?section=Currency' class=subheader>{$Lang->CURRENCIES}</A>
          </TD>
        </TR>
       <TR>
          <TD align=center valign=center height=50>
            <a title="{$Lang->PAYMENT}" href='index.php?section=Payment'><img border=0 alt='{$Lang->PAYMENT}' src='images/currency.gif' align=absmiddle></a>
          </TD>
          <TD align=left valign=center>
            <a title="{$Lang->PAYMENT}" href='index.php?section=Payment' class=subheader>{$Lang->PAYMENT}</A>
          </TD>
        </TR>
        {/if}*}
      </TABLE>
    </TD>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=body width=33%>
      <TABLE>
       {if $User->products}
        <TR>
          <TD align=center valign=center height=50>
            <a title="{$Lang->PRODUCTS}" href='index.php?section=Storefront'><img alt='{$Lang->PRODUCTS}' border=0 src='images/storefront_icon.gif' align=absmiddle></a>
          </TD>
          <TD align=left valign=center>
            <a title="{$Lang->PRODUCTS}" href='index.php?section=Storefront'  class=subheader>{$Lang->PRODUCTS}</A>
          </TD>
        </TR>
        <TR>
          <TD align=center valign=center height=50>
            <a title="{$Lang->PRODUCTS_CATEGORIES}" href='index.php?section=ProductCategories'><img border=0 alt='{$Lang->PRODUCTS_CATEGORIES}' src='images/categories_icon.gif' align=absmiddle></a>
          </TD>
          <TD align=left valign=center>
            <a title="{$Lang->PRODUCTS_CATEGORIES}" href='index.php?section=ProductCategories'  class=subheader>{$Lang->PRODUCTS_CATEGORIES}</A>
          </TD>
        </TR>
        {/if}
        {*{if $User->properties}
        <TR>
          <TD align=center valign=center height=50>
            <a title="{$Lang->PROPERTIES}" href='index.php?section=Index_properties'><img border=0 alt='{$Lang->PROPERTIES}' src='images/storefront_icon.gif' align=absmiddle></a>
          </TD>
          <TD align=left valign=center>
            <a title="{$Lang->PROPERTIES}" href='index.php?section=Index_properties'  class=subheader>{$Lang->PROPERTIES}</A>
          </TD>
        </TR>
        <TR>
          <TD align=center valign=center height=50>
            <a title="{$Lang->PROPERTIES_CATEGORIES}" href='index.php?section=Properties_Categories'><img border=0 alt='{$Lang->PROPERTIES_CATEGORIES}' src='images/categories_icon.gif' align=absmiddle></a>
          </TD>
          <TD align=left valign=center>
            <a title="{$Lang->PROPERTIES_CATEGORIES}" href='index.php?section=Properties_Categories'  class=subheader>{$Lang->PROPERTIES_CATEGORIES}</A>
          </TD>
        </TR>
        <TR>
          <TD align=center valign=center height=50>
            <a title="{$Lang->INDEX_SEARCH}" href='index.php?section=Index_Search'><img border=0 alt='{$Lang->INDEX_SEARCH}' src='images/categories_icon.gif' align=absmiddle></a>
          </TD>
          <TD align=left valign=center>
            <a title="{$Lang->INDEX_SEARCH}" href='index.php?section=Index_Search'  class=subheader>{$Lang->INDEX_SEARCH}</A>
          </TD>
        </TR>
        {/if}
        {if $User->orders}
        <TR>
          <TD align=center valign=center height=50>
            <a title="{$Lang->ORDERS}" href='index.php?section=Orders'><img border=0 alt='{$Lang->ORDERS}' src='images/orders_icon.gif' align=absmiddle></a>
          </TD>
          <TD align=left valign=center>
            <a title="{$Lang->ORDERS}" href='index.php?section=Orders' class=subheader>{$Lang->ORDERS}</A>
          </TD>
        </TR>
        {/if}
        {if $User->pricelist}
        <TR>
          <TD align=center valign=center height=50>
            <a title="{$Lang->PRICELIST_IMPORT}" href='index.php?section=PriceImport'><img border=0 alt='{$Lang->PRICELIST_IMPORT}' src='images/import_icon.jpg' align=absmiddle></a>
          </TD>
          <TD align=left valign=center>
            <a title="{$Lang->PRICELIST_IMPORT}" href='index.php?section=PriceImport'  class=subheader>{$Lang->PRICELIST_IMPORT}</A>
          </TD>
        </TR>
        <TR>
          <TD align=center valign=center height=50>
            <a title="{$Lang->PRICELIST_EXPORT}" href='index.php?section=PriceExport'><img border=0 alt='{$Lang->PRICELIST_EXPORT}' src='images/export_icon.jpg' align=absmiddle></a>
          </TD>
          <TD align=left valign=center>
            <a title="{$Lang->PRICELIST_EXPORT}" href='index.php?section=PriceExport' class=subheader>{$Lang->PRICELIST_EXPORT}</A>
          </TD>
        </TR>
        <TR>
          <TD align=center valign=center height=50>
            <a title="{$Lang->PRODUCTS_IMPORT}" href='index.php?section=Import'><img border=0 alt='{$Lang->PRODUCTS_IMPORT}' src='images/import.jpg' align=absmiddle></a>
          </TD>
          <TD align=left valign=center>
            <a title="{$Lang->PRODUCTS_IMPORT}" href='index.php?section=Import' class=subheader>{$Lang->PRODUCTS_IMPORT}</A>
          </TD>
        </TR>
        {/if}*}
      </TABLE>
    </TD>
    <TD ALIGN=LEFT VALIGN=TOP CLASS=body width=33%>
      <TABLE>
        {*if $User->statistics}
        <TR>
          <TD align=center valign=center height=50>
            <a title="{$Lang->STATISTICS}" href='index.php?section=Stat'><img border=0 alt='{$Lang->STATISTICS}' src='images/stat_icon.gif' align=absmiddle></a>
          </TD>
          <TD align=left valign=center>
            <a title="{$Lang->STATISTICS}" href='index.php?section=Stat' class=subheader>{$Lang->STATISTICS}</A>
          </TD>
        </TR>
        {/if*}
        {if $User->users}
        <TR>
          <TD align=center valign=center height=50>
            <a title="{$Lang->USERS}" href='index.php?section=Users'><img border=0 alt='{$Lang->USERS}' src='images/users_icon.jpg' align=absmiddle></a>
          </TD>
          <TD align=left valign=center>
            <a title="{$Lang->USERS}" href='index.php?section=Users' class=subheader>{$Lang->USERS}</A>
          </TD>
        </TR>
        <TR>
          <TD align=center valign=center height=50>
            <a title="{$Lang->USERS_CATEGORIES}" href='index.php?section=UsersCategories'><img border=0 alt='{$Lang->USERS_CATEGORIES}' src='images/users_categories_icon.jpg' align=absmiddle></a>
          </TD>
          <TD align=left valign=center>
            <a title="{$Lang->USERS_CATEGORIES}" href='index.php?section=UsersCategories' class=subheader>{$Lang->USERS_CATEGORIES}</A>
          </TD>
        </TR>
        {/if}
        {*{if 1}{$User->gallery=}
        <TR>
          <TD align=center valign=center height=50>
            <a title="{$Lang->GALLERY}" href='index.php?section=Gallery'><img alt='{$Lang->GALLERY}' border=0 src='images/storefront_icon.gif' align=absmiddle></a>
          </TD>
          <TD align=left valign=center>
            <a title="{$Lang->GALLERY}" href='index.php?section=Gallery'  class=subheader>{$Lang->GALLERY}</A>
          </TD>
        </TR>
        {/if}
        {if $User->usercomments}
        <TR>
          <TD align=center valign=center height=50>
            <a title="{$Lang->USERCOMMENTS}" href='index.php?section=Comments'><img border=0 alt='{$Lang->USERCOMMENTS}' src='images/comments.gif' align=absmiddle></a>
          </TD>
          <TD align=left valign=center>
            <a title="{$Lang->USERCOMMENTS}" href='index.php?section=Comments' class=subheader>{$Lang->USERCOMMENTS}</A>
          </TD>
        </TR>
        {/if}
        {if $User->polls}
        <TR>
          <TD align=center valign=center  height=50>
            <a title="{$Lang->POLLS}" href='index.php?section=Polls'><img alt='{$Lang->POLLS}' border=0 src='images/poll_icon.png'></a>
          </TD>
          <TD align=left valign=center>
            <a title="{$Lang->POLLS}" href='index.php?section=Polls'  class=subheader>{$Lang->POLLS}</A>
          </TD>
        </TR>
        {/if}
        {if $User->faq}
        <TR>
          <TD align=center valign=center  height=50>
            <a title="{$Lang->FAQ}" href='index.php?section=Faq'><img alt='{$Lang->FAQ}' border=0 src='images/poll_icon.png'></a>
          </TD>
          <TD align=left valign=center>
            <a title="{$Lang->FAQ}" href='index.php?section=Faq'  class=subheader>{$Lang->FAQ}</A>
          </TD>
        </TR>
        {/if}
        {if $User->announcement}
        <TR>
          <TD align=center valign=center  height=50>
            <a title="{$Lang->ANNOUNCEMENT}" href='index.php?section=Announcement'><img alt='{$Lang->ANNOUNCEMENT}' border=0 src='images/articles_icon.png'></a>
          </TD>
          <TD align=left valign=center>
            <a title="{$Lang->ANNOUNCEMENT}" href='index.php?section=Announcement'  class=subheader>{$Lang->ANNOUNCEMENT}</A>
          </TD>
        </TR>
        {/if}*}
        <TR>
          <TD align=center valign=center height=50>
            <a title="{$Lang->MAPSITE}" href='index.php?section=Mapsite'><img border=0 alt='{$Lang->MAPSITE}' src='images/sitemap.gif' align=absmiddle></a>
          </TD>
          <TD align=left valign=center>
            <a title="{$Lang->MAPSITE}" href='index.php?section=Mapsite' class=subheader>{$Lang->MAPSITE}</A>
          </TD>
        </TR>
      </TABLE>
    </TD>

  </TR>

</TABLE>



    </TD>
  </TR>
</TABLE>

      <!-- End Main -->
    </TD>
    <TD WIDTH=7 BACKGROUND='images/rightline.gif' BGCOLOR='#BDC5D1'>
      <!-- Right side line -->
    </TD>
    <TD>
      <!-- Right free space --> &nbsp;
    </TD>
  </TR>
</TABLE>

</BODY>

</HTML>