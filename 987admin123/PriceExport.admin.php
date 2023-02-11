<?PHP

require_once('Widget.admin.php');
require_once('PagesNavigation.admin.php');


############################################
# Class Setup displays news
############################################
class PriceExport extends Widget
{
  function __construct(&$parent)
  {
    parent::__construct($parent);
    $this->prepare();
  }

  function prepare()
  {
  }

  function fetch()
  {

    $this->title = $this->lang->PRICELIST_EXPORT;
    if(isset($_GET['format']))
    {
      	$format = $_GET['format'];
    	switch($format)
    	{
    		case('ekatalog'):
    		{
    			$query = 'SELECT *  FROM  categories, products LEFT JOIN products_fotos ON products_fotos.product_id=products.product_id WHERE products.category_id = categories.category_id AND products.price>0 AND products.enabled AND categories.enabled GROUP BY products.product_id ORDER BY categories.name, products.brand, products.model, products_fotos.foto_id';
    			$this->db->query($query);
    			$products = $this->db->results();
    			$this->smarty->assign('Products', $products);
    			$this->smarty->display('price_e-katalog.tpl');
    			exit();
    		}
    		case('meta'):
    		{
    			$query = 'SELECT * FROM categories, products LEFT JOIN products_fotos ON products_fotos.product_id=products.product_id WHERE products.category_id = categories.category_id AND products.price>0  AND products.enabled AND categories.enabled GROUP BY products.product_id  ORDER BY categories.name, products.brand, products.model, products_fotos.foto_id';
    			$this->db->query($query);
    			$products = $this->db->results();
    			$this->smarty->assign('Products', $products);
    			$this->smarty->display('price_meta.tpl');
    			exit();
    		}
    		case('yandex'):
    		{
    			$query = 'SELECT products.*,products_fotos.filename as filename,categories.name as category FROM categories, products LEFT JOIN products_fotos ON products_fotos.product_id=products.product_id WHERE products.category_id = categories.category_id AND products.price>0  AND products.enabled AND categories.enabled GROUP BY products.product_id ORDER BY products.brand, products.model, products_fotos.foto_id';
    			$this->db->query($query);
    			$products = $this->db->results();
    			foreach ($products as $product){
                $product->urlbrand = urlencode($product->brand);}
    			$query = 'SELECT * FROM categories WHERE categories.enabled ORDER BY categories.name';
    			$this->db->query($query);
    			$categories = $this->db->results();
    			$query = 'SELECT * FROM currencies ORDER BY  main DESC, currency_id';
    			$this->db->query($query);
    			$currencies = $this->db->results();
    			foreach ($currencies as $currency){
                $currency->rate = 1/$currency->rate;}
    			$this->smarty->assign('Products', $products);
    			$this->smarty->assign('Categories', $categories);
    			$this->smarty->assign('Currencies', $currencies);
    			$this->smarty->assign('Settings', $this->settings);
    			$this->smarty->display('price_yandex.tpl');
    			exit();
    		}
    		default:
    			$this->body = $this->smarty->fetch('price_export.tpl');
    	}


    }
  	$this->smarty->assign('Lang', $this->lang);
 	$this->body = $this->smarty->fetch('price_export.tpl');
  }


}