<?PHP

function _autoload($name)
{
	$map = array(
		'Announcement' => 'Announcement.class.php',
		'Articles' => 'Articles.class.php',
		'Basket' => 'Basket.class.php',
		'Categories' => 'Categories.php',
		'Config' => 'Config.class.php',
		'Contacts' => 'Contacts.class.php',
		'Database' => 'Database.class.php',
		//'' => 'Database.remote.php',
		//'' => 'Database.remote2.php',
		'Deliveries' => 'Deliveries.class.php',
		'Documents' => 'Documents.class.php',
		'ExternalLink' => 'ExternalLink.class.php',
		'Faq' => 'Faq.class.php',
		'Gallery' => 'Gallery.class.php',
		'Index' => 'Index.class.php',
		'Index_Search' => 'Index_Search.class.php',
		'Links' => 'Links.class.php',
		'Login' => 'Login.class.php',
		'MainPage' => 'MainPage.class.php',
		'Mapsite' => 'Mapsite.class.php',
		'NewsLine' => 'NewsLine.class.php',
		'Orders' => 'Orders.class.php',
		'Page' => 'Page.class.php',
		'Poll' => 'Poll.class.php',
		'Portfolio' => 'Portfolio.class.php',
		'Prise_List' => 'Prise_List.class.php',
		'ProductFoto' => 'ProductFoto.class.php',
		'KCAPTCHA' => 'protect/kcaptcha.php',
		'Registration' => 'Registration.class.php',
		'Search' => 'Search.class.php',
		'Sections' => 'Sections.class.php',
		'StaticPage' => 'StaticPage.class.php',
		'Storefront' => 'Storefront.class.php',
		'Widget' => 'Widget.class.php'
	);
	
	//echo "name = ".$name."<br />".PHP_EOL;
	//echo "map[$name] = ".$map[$name]."<br />".PHP_EOL;
	//echo "file = ".(__DIR__ . "/" . $map[$name])."<br />".PHP_EOL;

	if (isset($map[$name]) && is_file(__DIR__ . "/" . $map[$name]))
	{
		require_once __DIR__ . "/" . $map[$name];
	}
}

spl_autoload_register('_autoload');