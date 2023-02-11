<?PHP

//require_once('Widget.class.php');

class MainPage extends Widget
{
  var $single = false;
  var $items_per_page = 10;
  var $storefront = 'foto/storefront/';
  function __construct(&$parent)
  {
    parent::__construct($parent);
  }

    function get_categories($parent=0)
    {
      $query = sql_placeholder("SELECT * FROM categories WHERE parent=? AND categories.enabled ORDER BY order_num", $parent);
      $this->db->query($query);
      $categories = $this->db->results();
      foreach($categories as $k=>$category)
      {
        $categories[$k]->subcategories = $this->get_categories($category->category_id);
      }
      return $categories;
    }

  function fetch()
  {
    $this->title = $this->settings->title;
    $this->keywords = $this->settings->keywords;
    $this->description = $this->settings->description;

    ### Меню
    $this->db->query("SELECT * FROM sections WHERE menu_id=1 ORDER BY order_num");
    $sections = $this->db->results();
    $this->smarty->assign('Sections', $sections);

	$this->smarty->assign("Title", $this->title);
	$this->smarty->assign("Keywords", $this->keywords);
	$this->smarty->assign("Description", $this->description);


    $categories = $this->get_categories();

    $this->smarty->assign('Categories', $categories);

    ### Популярные товары
     /*$brand = isset($_SESSION['brand']) ? $_SESSION['brand'] : '';
      //echo "<pre>\n"; print_r(array_values($this->subbrands));  echo "</pre>\n";
      $hit_num = intval(array_search($this->domain,array_values($this->subbrands)))+1;
      //echo "hit_num = ".intval($hit_num)."<br />\n";
      $brand_filter = strlen($brand) ? "AND products.brand = '$brand'" : "";

    $query = sql_placeholder("SELECT SQL_CALC_FOUND_ROWS *,  products.*,  categories.name as category, categories.alies as alies, products.price*(100-".($this->user->discount?$this->user->discount:0).")/100 as discount_price,
                              SUM(products_comments.point)/COUNT(products_comments.comment_id) as point
                              FROM categories, products LEFT JOIN products_fotos ON products_fotos.product_id=products.product_id
                              LEFT JOIN products_comments ON products.product_id = products_comments.product_id  AND products_comments.point>0
                              WHERE categories.category_id = products.category_id  AND SUBSTRING(products.hit,?,1)='1' AND products.enabled
                              $brand_filter
                              GROUP BY products.product_id
                              ORDER BY products.brand, products.model, products_fotos.foto_id",$hit_num);
    $this->db->query($query);
    //echo "$query<br />\n";
    $products = $this->db->results();

    $this->smarty->assign('Products', $products);

       	 $this->db->query("SELECT * FROM currencies ORDER BY currency_id");

 		   $currencies = $this->db->results();

    $iheight = array();

    foreach ($products as $id=>$product){

		$product->filename = trim($product->filename);
        $info = getimagesize(dirname(__FILE__).'/'.$this->storefront.$product->filename);
		$iheight[$product->product_id] = round(100*$info[1]/$info[0],0);
  		  foreach($currencies as $k=>$currency)
		  {
 		   if ($currency->code == $product->currency_id)
  			  $product->currency_rate = $currency->rate;
  		  }
        $pnames = array();
        $pnames[0] = $products[$id]->category;
        //print_r($pnames);echo "<br />\n";
        if($products[$id]->alies)
        {
        $palies = explode(";;",$products[$id]->alies);
        if(is_array($palies))
        {
        foreach ($palies as $akey=>$paly)
        {
        if($palies[$akey]=='') unset($palies[$akey]);
        array_push($pnames,$palies[$akey]);
        }

        }
        }
        //print_r($pnames);echo "<br />\n";
        $products[$id]->category = $pnames[$products[$id]->category_name];
	}*/
		# Текст на главной
		$query = "SELECT `pages`.`body` FROM `sections` LEFT JOIN `pages` ON `sections`.`material_id`=`pages`.`page_id` WHERE `sections`.`url`='maintext' AND `sections`.`domain`='$this->domain'";
        $this->db->query($query);
        $res = $this->db->result();
        $maintext = $res->body;
    	$this->smarty->assign('Maintext', $maintext);


		$query = "SELECT `pages`.`body` FROM `sections` LEFT JOIN `pages` ON `sections`.`material_id`=`pages`.`page_id` WHERE `sections`.`url`='bottomtext' AND `sections`.`domain`='$this->domain'";
        $this->db->query($query);
        $res = $this->db->result();
        $bottomtext = $res->body;
    	$this->smarty->assign('Bottomtext', $bottomtext);

      /*$current_page = intval($this->param('page'));

      if(!isset($current_page))
          $current_page=1;
      $start_item = $current_page*$this->items_per_page;
      $this->db->query(sql_placeholder("SELECT SQL_CALC_FOUND_ROWS sections.*,`pages`.`body` FROM sections LEFT JOIN `pages` ON `sections`.`material_id`=`pages`.`page_id`
                     WHERE sections.menu_id=5
                     ORDER BY sections.order_num ASC LIMIT ?,?",$start_item,$this->items_per_page));
      $articles = $this->db->results();
      //print_r($articles);

      $this->db->query("SELECT FOUND_ROWS() as count");
      $pages_num0 = $this->db->result();
      $pages_num = ceil($pages_num0->count/$this->items_per_page);

      for($i=0;$i<$pages_num;$i++)
      {
          if($i)
          $url[$i] = 'frontpage'.$i.'.html';
          else
          $url[$i] = '/';
      }

    $this->smarty->assign('Articles', $articles);
      $this->smarty->assign('PagesNum', $pages_num);
      $this->smarty->assign('Url', $url);
      $this->smarty->assign('CurrentPage', $current_page);*/
    //$this->smarty->assign('Iheight', $iheight);
    //$this->smarty->assign('Maxheight', max($iheight));
    $this->body = $this->smarty->fetch('main.tpl');

  }
}