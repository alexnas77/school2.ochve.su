<?PHP

//require_once('Widget.class.php');
//require_once('placeholder.php');

class Search extends Widget
{
  function __construct(&$parent)
  {
    parent::__construct($parent);
    $this->add_param('keyword');
    $this->section_id = 6;
  }

  function fetch()
  {
    $keyword = $this->param('keyword');
  	$keys = preg_split('/\s/', $keyword);
  	$s = "";
  	foreach($keys as $key)
  	{
  	  if(strlen($key)>2)
  	  {
  	    $key = mysql_real_escape_string($key);
  	    $s.= " AND (categories.name LIKE '%$key%' OR products.brand LIKE '%$key%' OR products.model LIKE  '%$key%' OR products.description LIKE '%$key%')";
  	  }
  	}
  	if(!empty($s))
  	{
  	  //$query = "SELECT products.*, categories.name as category FROM products, categories WHERE products.category_id = categories.category_id $s ORDER BY brand, model";

      $query = "SELECT products.*, products_fotos.filename as filename, categories.name as category, products.price*(100-".($this->user->discount?$this->user->discount:0).")/100 as discount_price  FROM categories, products LEFT JOIN products_fotos ON products_fotos.product_id=products.product_id WHERE categories.category_id = products.category_id $s GROUP BY products.product_id ORDER BY products.brand, products.model, products_fotos.foto_id";

  	  $this->db->query($query);
      $products = $this->db->results();
  	}else
  	{
  	  $products = array();
  	}

    $this->smarty->assign('Products', $products);
    $this->smarty->assign('Keyword', $keyword);
    $this->body = $this->smarty->fetch('search.tpl');
  }




}