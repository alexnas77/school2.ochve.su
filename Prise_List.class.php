<?PHP

//require_once('Widget.class.php');

class Prise_List extends Widget
{
  var $items_per_page = 50;
  var $section_id = 14;
  var $searches = array('больше','меньше','равно');
  var $sort_ids = array('','популярности','цене по возрастанию','цене по убыванию');
  function __construct(&$parent)
  {
    parent::__construct($parent);
    $this->add_param('category');
    $this->add_param('subcategory');
    $this->add_param('brand');
    $this->add_param('page');
  }

  function fetch()
  {
    $item_id = $this->param('item_id');
    $category = intval($this->param('category'));
    $brand = $this->param('brand');
    $action = $this->param('action');

    $categories = Prise_List::get_categories(0);
    //print_r($categories);
    foreach($categories as $k=>$category){
    $this->fetch_items_list($categories[$k]->category_id, $brand);
    }
  }

   function get_categories($parent=0)
  {
      $query = sql_placeholder("SELECT * FROM categories WHERE parent=? ORDER BY order_num", $parent);
      $this->db->query($query);
      $categories = $this->db->results();
      foreach($categories as $k=>$category)
      {
        $categories[$k]->subcategories = Storefront::get_categories($category->category_id);

      }
      return $categories;
  }

   function get_group_properties ()
  {
      $query = "SELECT * FROM group_properties ORDER BY sort";
      $this->db->query($query);
      $group_properties = $this->db->results();

      return $group_properties;
  }



  function fetch_items_list($category_id, $brand)
  {


    if(!isset($brand))
      $brand='';

    $current_page = intval($this->param('page'));

    $this->db->query("SELECT * FROM categories WHERE category_id = '$category_id'");
    $category = $this->db->result();

    $parent = $category->parent;

    $this->db->query("SELECT DISTINCT brand as name FROM products, categories WHERE categories.category_id = products.category_id AND (categories.category_id = '$category->category_id' OR categories.parent = '$category->category_id') ORDER BY brand");
    $brands = $this->db->results();


    $this->db->query("SELECT * FROM sections WHERE section_id = '".$this->section_id."'");
    $section = $this->db->result();

    $this->title = $section->name;
    $this->keywords = $section->name;
    $this->description = $section->name;


    $start_item = $current_page*$this->items_per_page;


    $query = sql_placeholder("SELECT SQL_CALC_FOUND_ROWS products.*,  categories.name as category, products.price*(100-".($this->user->discount?$this->user->discount:0).")/100*".$this->currency->rate."/currencies.rate*(100-products.discount)/100 as discount_price
                              FROM categories,products
                              LEFT JOIN currencies ON currencies.code = products.currency_id
                              WHERE categories.category_id = products.category_id
                              ORDER BY products.brand, products.category_id
                              LIMIT ?, ?", $start_item, $this->items_per_page);
    $this->db->query($query);
    $products = $this->db->results();

    $this->db->query("SELECT FOUND_ROWS() as count");
    $pages_num = $this->db->result();
    $pages_num = ceil($pages_num->count/$this->items_per_page);

    for($i=0;$i<$pages_num;$i++)
  	{
  		  $url[$i] = 'index.php'.$this->form_get(array('brand'=>$brand, 'page'=>$i));
  	}

    foreach ($products as $id=>$product){


       	 $this->db->query("SELECT * FROM currencies ORDER BY currency_id");
 		   $currencies = $this->db->results();

  		  foreach($currencies as $k=>$currency)
		    {
 		   if ($currency->code == $product->currency_id)
  			  $product->currency_rate = $currency->rate;
  		  }

    }
    //echo $query.";";





      // print_r($products);
    //print_r($category);
    //print_r($url);


    $this->smarty->assign('Products', $products);
    $this->smarty->assign('Category', $category);
    $this->smarty->assign('Brands', $brands);
    $this->smarty->assign('Searches', $this->searches);
    $this->smarty->assign('Filter_mask', $filter_mask);
    $this->smarty->assign('Legend', $legend);
    $this->smarty->assign('Section', $section);
    $this->smarty->assign('Sort_ids', $this->sort_ids);
    $this->smarty->assign('Brand', $brand);
    $this->smarty->assign('PagesNum', $pages_num);
    $this->smarty->assign('Url', $url);
    $this->smarty->assign('CurrentPage', $current_page);
    $this->body = $this->smarty->fetch('prise_list.tpl');
  }





}