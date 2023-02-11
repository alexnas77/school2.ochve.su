<?PHP

//require_once('Widget.class.php');

class StaticPage extends Widget
{
  function __construct(&$parent)
  {
    parent::__construct($parent);
  }

  function fetch()
  {


    $section_id = $this->param('section');
    $section_id = mysql_escape_string($section_id);
    $query = "SELECT pages.*, sections.* FROM sections, pages WHERE sections.material_id = pages.page_id AND sections.domain='$this->domain' AND sections.url = '$section_id'";
    //echo "$query<br />\n";
    $this->db->query($query);
    if($this->db->num_rows() == 1)
    {

      $page = $this->db->result();

      $delivery = @file_get_contents("http://drel.su/web/delivery.txt");

      if($delivery)
      $page->body = str_replace("{delivery}",nl2br($delivery),$page->body);
      else
      $page->body = str_replace("{delivery}","",$page->body);

      $delivery_rus = @file_get_contents("http://drel.su/web/delivery_rus.txt");

      if($delivery_rus)
      $page->body = str_replace("{delivery_rus}",nl2br($delivery_rus),$page->body);
      else
      $page->body = str_replace("{delivery_rus}","",$page->body);

    	$this->title = $page->title;
    	$this->keywords = $page->keywords;
    	$this->description = $page->description;
    	$this->body = $page->body;

         ### Популярные товары

/*
    $query = sql_placeholder("SELECT SQL_CALC_FOUND_ROWS *,  products.*,  categories.name as category, products.price*(100-".($this->user->discount?$this->user->discount:0).")/100 as discount_price,
                              SUM(products_comments.point)/COUNT(products_comments.comment_id) as point
                              FROM categories, products LEFT JOIN products_fotos ON products_fotos.product_id=products.product_id
                              LEFT JOIN products_comments ON products.product_id = products_comments.product_id  AND products_comments.point>0
                              WHERE categories.category_id = products.category_id  AND products.hit AND products.enabled
                              GROUP BY products.product_id
                              ORDER BY products.brand, products.model, products_fotos.foto_id");
    $this->db->query($query);
    $products = $this->db->results();

    foreach ($products as $id=>$product){

       		$category_product_id = $product->category_id;

       	 $this->db->query("SELECT * FROM currencies ORDER BY currency_id");
 		   $currencies = $this->db->results();

  		  foreach($currencies as $k=>$currency)
		    {
 		   if ($currency->code == $product->currency_id)
  			  $product->currency_rate = $currency->rate;
  		  }
  	}

    $this->smarty->assign('Products', $products);

    $this->db->query("SELECT *, DATE_FORMAT(date, '%d.%m.%Y') as dt FROM news ORDER BY date DESC LIMIT 7");
    $news = $this->db->results();
    $this->smarty->assign('News', $news);
*/

    	$this->smarty->assign('Page', $page);
   		$this->body = $this->smarty->fetch('static_section.tpl');
    }
    else
    {
    	$this->error_msg="Ошибка загрузки статической страницы";
    }
  }

}