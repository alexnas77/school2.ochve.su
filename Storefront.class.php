<?PHP

//require_once('Widget.class.php');

class Storefront extends Widget
{
  var $items_per_page = 50;
  var $section_id = 4;
  var $searches = array('больше','меньше','равно');
  var $sort_ids = array('','цене по возрастанию','цене по убыванию');
    var $storefront = 'foto/storefront/';
  function __construct(&$parent)
  {
    parent::__construct($parent);
    $this->add_param('item_id');
    $this->add_param('category');
    $this->add_param('subcategory');
    $this->add_param('brand');
    $this->add_param('page');
  }

  function fetch()
  {
    $item_id = $this->param('item_id');
    $category = $this->param('category');
    $brand = $this->param('brand');
    $report = $this->param('report');
    $ajax = $this->param('ajax'); 
    
    if(!empty($item_id) && is_numeric($item_id)){
       $this->fetch_item($item_id); 
    }      
    elseif(!empty($ajax)){
        $this->fetch_report_ajax();
    }       
    elseif(!empty($report) && is_numeric($report)){
        $this->fetch_report($category);
    }         
    elseif(strlen($category)>0){
       $this->fetch_items_list($category, $brand); 
    }      
    else{
       $this->fetch_categories(); 
    }      
  }

/*   function get_categories($parent=0)
  {
      $query = sql_placeholder("SELECT * FROM categories WHERE parent=? ORDER BY order_num", $parent);
      $this->db->query($query);
      $categories = $this->db->results();
      foreach($categories as $k=>$category)
      {
        $categories[$k]->subcategories = Storefront::get_categories($category->category_id);

      }
      return $categories;
  }*/

    function get_categories($parent=0)
    {
      $brand = isset($_SESSION['brand']) ? $_SESSION['brand'] : '';
      if($parent)
      $brand_filter = strlen($brand) ? "AND categories.brands LIKE '%::".strtolower($brand)."::%'" : "";
      else {
        $brand_filter = "";    
      }
      $query = sql_placeholder("SELECT categories.* FROM categories WHERE categories.parent=? AND categories.enabled $brand_filter ORDER BY categories.order_num", $parent);
      //echo "$query\n";
      $this->db->query($query);
      $categories = $this->db->results();
      foreach($categories as $k=>$category)
      {
        $brands = explode("::",$category->brands);

        $categories[$k]->subcategories = $this->get_categories($category->category_id);
        if(!in_array(strtolower($brand),$brands) && empty($categories[$k]->subcategories)) unset($categories[$k]);
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


  function fetch_categories()
  {
    $categories = $this->get_categories();


    $categories_names = array();
    foreach($categories as $cat)
      $categories_names[] = $cat->name;
    $this->description = $this->title = $this->keywords = join(', ', $categories_names);
    $discount = (100-$this->user->discount)/100;
    foreach($categories as $k=>$cat)
    {
    	/*$query = "SELECT products.*, SUM(stat.hits) as hits, cats.name as category, products.price*$discount as discount_price
                  FROM products LEFT JOIN stat ON stat.product_id = products.product_id
                  LEFT JOIN categories cats ON products.category_id = cats.category_id
                  WHERE (date>=DATE_SUB(CURDATE(), INTERVAL 7 DAY) OR date is NULL) AND (cats.category_id = '$cat->category_id' OR cats.parent = '$cat->category_id') GROUP BY products.product_id ORDER BY hits DESC LIMIT 4";
    	$this->db->query($query);
    	$products = $this->db->results(); */

    	//$categories[$k]->products = $products;


    	 foreach($categories[$k]->subcategories as $sk=>$subcat){
    	 $categories[$k]->subcategories_names[$sk][name] = $subcat->name;
    	 $categories[$k]->subcategories_names[$sk][url] = "catalog/".$subcat->category_id;}
    }

    //print_r($categories);

    $section_id = $this->param('section');
    $this->db->query("SELECT * FROM sections WHERE section_id = '$section_id'");
    $section = $this->db->result();

    $this->smarty->assign('Categories', $categories);
    $this->body = $this->smarty->fetch('storefront_categories.tpl');
  }

    function fetch_report_ajax()
    {

        $category = intval($this->param('category'));
        $name = iconv("UTF-8","Windows-1251",strval($this->param('name')));
        
        //echo "name = ".$name."<br />";
            
            if (/*$interval>$this->config->delay || !empty($_POST)*/true)
            {
                $category_filter = $category ? sql_placeholder(" AND products.category_id = ?",$category) : "";
                $limit = $category ? "" : "LIMIT 20";
                $query = sql_placeholder("SELECT model, CONCAT(categories.name,' класс') as category FROM  products
                                          LEFT JOIN categories ON categories.category_id=products.category_id
                                          WHERE (model LIKE ? OR model LIKE ?) $category_filter AND products.enabled = 1
                                          ORDER BY categories.name,model $limit", $name."%", "% ".$name."%");
                $this->db->query($query);
                $products = $this->db->results();
                
                foreach ($products as $key => $value) {
                    $products[$key]->model_url = new stdClass();
                    $products[$key]->model_url = urlencode($value->model);
                }
                
                //echo $query.";<br>";
                //echo "<pre><br>"; print_r($products); echo "</pre><br>";

            }    

            //echo "<pre>\n";
            //print_r($products);
            //echo "</pre>\n";

            $this->smarty->assign('Products', $products);
            $this->smarty->display('storefront_report_ajax.tpl');
            $this->db->disconnect();
            exit();

    }

    function fetch_report($category_id)
    {

        $name = $this->param('name');
        
        $start_date = $this->param('start_date');
        $end_date = $this->param('end_date');

        $start_datetime = date("Y-m-d",strtotime($start_date));
        $start_datetime_1 = date("Y-m-d",(strtotime($start_date) - 3600*24));
        $end_datetime = date("Y-m-d",strtotime($end_date));

        $current_page = intval($this->param('page'));

        $active_only = !empty($this->param('active')) ? intval($this->param('active')) : 0;
        
        $debts = !empty($this->param('debts')) ? intval($this->param('debts')) : 0;
        if($active_only === 1) {
            $products_enabled = "AND products.enabled = 1";
        }
        elseif($active_only === -1) {
            $products_enabled = "AND products.enabled = 0";
        }
        else {
            $products_enabled = "";
        }

        //$products = new stdClass();

        if(!isset($current_page))
            $current_page=1;

        $cat_num = intval(array_search($this->domain,array_values($this->subbrands)));
        if($cat_num>0)
            $this->db->query("SELECT *, title$cat_num as title, description$cat_num as description FROM categories WHERE category_id = '$category_id'");
        else
            $this->db->query("SELECT * FROM categories WHERE category_id = '$category_id'");
        $category = $this->db->result();
        $category_filter = $category->category_id ? sql_placeholder(" AND products.category_id = ?",$category->category_id) : "";
        $query = sql_placeholder("SELECT
        products.product_id,
	SUM( stat.delta ) AS sum_delta
FROM
	products
	LEFT JOIN stat ON stat.product_id = products.product_id 
WHERE 
	products.enabled = 1 
        $category_filter
	AND stat.date >= ? 
	AND stat.date <= ?
GROUP BY
	products.product_id
HAVING sum_delta > 0", date("Y-m-d",strtotime($this->settings->start)), $end_datetime);
        
        $this->db->query($query);
        //echo $query.";<br>";
        
        $products_debts = $this->db->results();
        
        $products_debts_ids = [];
        
        foreach ($products_debts as $pdv) {
            $products_debts_ids[] = $pdv->product_id;
        }
        //echo "<pre>". print_r($products_debts_ids, true)."<pre>";
        
        if($debts > 0 ) {
            $products_enabled = "AND products.product_id IN (". implode(",", $products_debts_ids).")";
        }
        
        if(!empty($name)){
            $products[0] = new stdClass();
        $this->title = (!empty($category->name) ? $category->name." класс" : "Все классы")." с ".$start_date." по ".$end_date;

        if($category->keywords)
            $this->keywords = $category->keywords;

            if (/*$interval>$this->config->delay || !empty($_POST)*/true)
            {
                /*OR model LIKE ?*/
                $limit = $category->category_id ? "" : "LIMIT 10";
                $query = sql_placeholder("SELECT model, CONCAT(categories.name,' класс') as category FROM  products
                                          LEFT JOIN categories ON categories.category_id=products.category_id
                                          WHERE (model LIKE ? ) $category_filter $products_enabled
                                          ORDER BY categories.name,model $limit", $name."%"/*, "% ".$name."%"*/);
                //AND products.enabled = 1
                $this->db->query($query);
                $products = $this->db->results();
                foreach ($products as $pk=>$product) {
                $query = sql_placeholder("SELECT SUM(stat.breakfast_free) as breakfasts_free,
                                                 SUM(stat.breakfast) as breakfasts,
                                                 SUM(stat.lunch) as lunches,
                                                 SUM(stat.lunch2) as lunches2,
                                                 SUM(stat.lunch3) as lunches3,
                                                 SUM(stat.lunch_m) as lunches_m,
                                                 SUM(stat.dinner_m) as dinners_m,
                                                 SUM(stat.dinner) as dinners,
                                                 SUM(stat.breakfast_free*stat.new_breakfast_free) as sum_breakfast_free,
                                                 SUM(stat.breakfast*stat.new_breakfast) as sum_breakfast,
                                                 SUM(stat.lunch*stat.new_lunch) as sum_lunch,
                                                 SUM(stat.lunch2*stat.new_lunch2) as sum_lunch2,
                                                 SUM(stat.lunch3*stat.new_lunch3) as sum_lunch3,
                                                 SUM(stat.lunch_m*stat.new_lunch_m) as sum_lunch_m,
                                                 SUM(stat.dinner_m*stat.new_dinner_m) as sum_dinner_m,
                                                 SUM(stat.dinner*stat.new_dinner) as sum_dinner,
                                                 SUM(stat.cash) as sum_cash,
                                                 SUM(stat.card) as sum_card 
                                         FROM products
                                         LEFT JOIN stat ON stat.product_id=products.product_id
                                         WHERE products.model = ? $products_enabled
                                         AND stat.date BETWEEN ? AND ?", 
                                        $product->model, 
                                        $start_datetime, 
                                        $end_datetime);
                //AND products.enabled = 1
                $this->db->query($query);
                $sum = $this->db->result();
                //echo $query.";<br>";
                //echo "<pre><br>"; print_r($sum); echo "</pre><br>";
                //$price[$id] = $sum->sum;
                
                $query = sql_placeholder("SELECT SUM(stat.delta) as sum FROM  products
    LEFT JOIN stat ON stat.product_id=products.product_id
    WHERE products.model = ? AND stat.date BETWEEN ? AND ? $products_enabled", 
                $product->model, date("Y-m-d",strtotime($this->settings->start)), $start_datetime_1);
                //AND products.enabled = 1
                $this->db->query($query);
                $begin = $this->db->result();

                $query = sql_placeholder("SELECT SUM(stat.delta) as sum FROM  products
                LEFT JOIN stat ON stat.product_id=products.product_id
                WHERE (products.model = ?) AND stat.date BETWEEN ? AND ? $products_enabled", 
                        $product->model, 
                        date("Y-m-d",strtotime($this->settings->start)), 
                        $end_datetime);
                //AND products.enabled = 1
                $this->db->query($query);
                $total = $this->db->result();
                //echo $query.";<br>";
                $products[$pk]->sum = $sum;
                $products[$pk]->model = $product->model." ".$product->category;
                $products[$pk]->begin = floatval($begin->sum);  
                $products[$pk]->total = floatval($total->sum);                    
                }


            }      
        }
        elseif(!empty($category_id))
        {
        $products[0] = new stdClass();
        $this->title = (!empty($category->name) ? $category->name." класс" : "Все классы")." с ".$start_date." по ".$end_date;

        if($category->keywords)
            $this->keywords = $category->keywords;

            if (/*$interval>$this->config->delay || !empty($_POST)*/true)
            {
                $query = sql_placeholder("SELECT SUM(stat.breakfast_free) as breakfasts_free,
                                                 SUM(stat.breakfast) as breakfasts,
                                                 SUM(stat.lunch) as lunches,
                                                 SUM(stat.lunch2) as lunches2,
                                                 SUM(stat.lunch3) as lunches3,
                                                 SUM(stat.lunch_m) as lunches_m,
                                                 SUM(stat.dinner_m) as dinners_m,
                                                 SUM(stat.dinner) as dinners,
                                                 SUM(stat.breakfast_free*stat.new_breakfast_free) as sum_breakfast_free,
                                                 SUM(stat.breakfast*stat.new_breakfast) as sum_breakfast,
                                                 SUM(stat.lunch*stat.new_lunch) as sum_lunch,
                                                 SUM(stat.lunch2*stat.new_lunch2) as sum_lunch2,
                                                 SUM(stat.lunch3*stat.new_lunch3) as sum_lunch3,
                                                 SUM(stat.lunch_m*stat.new_lunch_m) as sum_lunch_m,
                                                 SUM(stat.dinner_m*stat.new_dinner_m) as sum_dinner_m,
                                                 SUM(stat.dinner*stat.new_dinner) as sum_dinner,
                                                 SUM(stat.cash) as sum_cash,
                                                 SUM(stat.card) as sum_card 
                                                 FROM  products
    LEFT JOIN stat ON stat.product_id=products.product_id
    WHERE (products.category_id = ?) $products_enabled
    AND stat.date BETWEEN ? AND ?", 
                $category->category_id, $start_datetime, $end_datetime);
                //AND products.enabled = 1
                $this->db->query($query);
                $sum = $this->db->result();
                //echo $query.";<br>";
                //echo "<pre><br>"; print_r($sum); echo "</pre><br>";
                //$price[$id] = $sum->sum;

                $query = sql_placeholder("SELECT SUM(stat.delta) as sum FROM  products
    LEFT JOIN stat ON stat.product_id=products.product_id
    WHERE (products.category_id = ?) AND stat.date BETWEEN ? AND ? $products_enabled", 
                $category->category_id, date("Y-m-d",strtotime($this->settings->start)), $end_datetime);
                //AND products.enabled = 1
                $this->db->query($query);
                $total = $this->db->result();
                //echo $query.";<br>";
                
                $query = sql_placeholder("SELECT SUM(stat.delta) as sum FROM  products
    LEFT JOIN stat ON stat.product_id=products.product_id
    WHERE (products.category_id = ?) AND stat.date BETWEEN ? AND ? $products_enabled", 
                $category->category_id, date("Y-m-d",strtotime($this->settings->start)), $start_datetime_1);
                //AND products.enabled = 1
                $this->db->query($query);
                $begin = $this->db->result();
                //echo $query.";<br>";

            }
            $products[0]->sum = $sum;
            $products[0]->model = $category->name;
            $products[0]->begin = floatval($begin->sum);
            $products[0]->total = floatval($total->sum);

        }
        else {

        $this->title = (!empty($category->name) ? $category->name." класс" : "Все классы")." с ".$start_date." по ".$end_date;

        $categories = $this->get_categories();

        foreach($categories as $ck=>$category1)
        {
            $products[$ck] = new stdClass();
            $products[$ck]->sum = new stdClass();
            
            if (/*$interval>$this->config->delay || !empty($_POST)*/true)
            {
                $query = sql_placeholder("SELECT SUM(stat.breakfast_free) as breakfasts_free,
                                                 SUM(stat.breakfast) as breakfasts,
                                                 SUM(stat.lunch) as lunches,
                                                 SUM(stat.lunch2) as lunches2,
                                                 SUM(stat.lunch3) as lunches3,
                                                 SUM(stat.lunch_m) as lunches_m,
                                                 SUM(stat.dinner_m) as dinners_m,
                                                 SUM(stat.dinner) as dinners,
                                                 SUM(stat.breakfast_free*stat.new_breakfast_free) as sum_breakfast_free,
                                                 SUM(stat.breakfast*stat.new_breakfast) as sum_breakfast,
                                                 SUM(stat.lunch*stat.new_lunch) as sum_lunch,
                                                 SUM(stat.lunch2*stat.new_lunch2) as sum_lunch2,
                                                 SUM(stat.lunch3*stat.new_lunch3) as sum_lunch3,
                                                 SUM(stat.lunch_m*stat.new_lunch_m) as sum_lunch_m,
                                                 SUM(stat.dinner_m*stat.new_dinner_m) as sum_dinner_m,
                                                 SUM(stat.dinner*stat.new_dinner) as sum_dinner,
                                                 SUM(stat.cash) as sum_cash,
                                                 SUM(stat.card) as sum_card 
                                                 FROM  products
    LEFT JOIN stat ON stat.product_id=products.product_id
    WHERE (products.category_id = ?) $products_enabled
    AND stat.date BETWEEN ? AND ?", 
                $category1->category_id, $start_datetime, $end_datetime);
                //AND products.enabled = 1
                $this->db->query($query);
                $sum = $this->db->result();
                //echo $query.";<br>";
                //echo "<pre><br>"; print_r($sum); echo "</pre><br>";
                //$price[$id] = $sum->sum;
                
                $query = sql_placeholder("SELECT SUM(stat.delta) as sum FROM  products
    LEFT JOIN stat ON stat.product_id=products.product_id
    WHERE (products.category_id = ?) AND stat.date BETWEEN ? AND ? $products_enabled", 
                $category1->category_id, date("Y-m-d",strtotime($this->settings->start)), $start_datetime_1);
                //AND products.enabled = 1
                $this->db->query($query);
                $begin = $this->db->result();
                //echo $query.";<br>";

                $query = sql_placeholder("SELECT SUM(stat.delta) as sum FROM  products
    LEFT JOIN stat ON stat.product_id=products.product_id
    WHERE (products.category_id = ?) AND stat.date BETWEEN ? AND ? $products_enabled", 
                $category1->category_id, date("Y-m-d",strtotime($this->settings->start)), $end_datetime);
                //AND products.enabled = 1
                $this->db->query($query);
                $total = $this->db->result();
                //echo $query.";<br>";

            }
            if($active_only === -1 && empty($begin->sum) && empty($total->sum)) {
                unset($products[$ck]);
                continue;
            }
            $products[$ck]->sum = $sum;
            $products[$ck]->model = $category1->name;
            $products[$ck]->begin = floatval($begin->sum);
            $products[$ck]->total = floatval($total->sum);
        }

        }



            //echo "<pre>\n";
            //print_r($products);
            //print_r($price);
            //print_r($category);
            //print_r($sum);
            //print_r($total);
            //echo "</pre>\n";



            $this->smarty->assign('Products', $products);
            $this->smarty->assign('Category', $category);
            //$this->smarty->assign('Parent_category', $parent_category);
            //$this->smarty->assign('Price', $price);
            //$this->smarty->assign('Brands', $brands);
            //$this->smarty->assign('Searches', $this->searches);
            //$this->smarty->assign('Filter_mask', $filter_mask);
            //$this->smarty->assign('Legend', $legend);
            //$this->smarty->assign('Sort_ids', $this->sort_ids);
            //$this->smarty->assign('Brand', $brand);
            //$this->smarty->assign('URLBrand', urlencode($brand));
            //$this->smarty->assign('PagesNum', $pages_num);
            //$this->smarty->assign('Url', $url);
            $this->smarty->assign('CurrentPage', $current_page);
            //$this->smarty->assign('Date', $date);
            $this->smarty->assign('StartDate', $start_date);
            $this->smarty->assign('EndDate', $end_date);
            $this->smarty->assign('StartTimeStamp', strtotime($start_date));
            $this->smarty->assign('EndTimeStamp', strtotime($end_date));
            $this->smarty->assign('Breakfast_free', $this->settings->breakfast_free);
            $this->smarty->assign('Breakfast', $this->settings->breakfast);
            $this->smarty->assign('Lunch', $this->settings->lunch);
            $this->smarty->assign('Lunch2', $this->settings->lunch2);
            $this->smarty->assign('Lunch3', $this->settings->lunch3);
            $this->smarty->assign('Dinner', $this->settings->dinner);
            $this->smarty->assign('Lunch_m', $this->settings->lunch_m);
            $this->smarty->assign('Dinner_m', $this->settings->dinner_m);
            $this->body = $this->smarty->fetch('storefront_report.tpl');

    }


    function fetch_items_list($category_id, $brand)
  {
    if(!isset($brand))
      $brand = isset($_SESSION['brand']) ? $_SESSION['brand'] : '';

    $print = $this->param('print');
    if(empty($print))
        $print = "";

      $exel = $this->param('exel');
      if(empty($exel))
          $exel = "";

    $date = $this->param('date');
      $_SESSION['date'] = $date;
    
    $name = $this->param('name');    

      if(empty($date) && strtotime(date("d.m.Y"))<strtotime($this->settings->start)){
          $date = $this->settings->start;
          $nextdate = date("d.m.Y",strtotime("+1 day",strtotime($this->settings->start)));
          $prevdate = date("d.m.Y",strtotime("-1 day",strtotime($this->settings->start)));
      }
      elseif(empty($date) && strtotime(date("d.m.Y"))>strtotime($this->settings->end)){
          $date = $this->settings->end;
          $nextdate = date("d.m.Y",strtotime("+1 day",strtotime($this->settings->end)));
          $prevdate = date("d.m.Y",strtotime("-1 day",strtotime($this->settings->end)));
      }
      elseif(!empty($date)){
          $nextdate = date("d.m.Y",strtotime("+1 day",strtotime($date)));
          $prevdate = date("d.m.Y",strtotime("-1 day",strtotime($date)));
      }
  else{
      $date = isset($_SESSION['date']) ? $_SESSION['date'] : date("d.m.Y");
      $nextdate = isset($_SESSION['nextdate']) ? $_SESSION['nextdate'] : date("d.m.Y",strtotime("+1 day"));
      $prevdate = isset($_SESSION['prevdate']) ? $_SESSION['prevdate'] : date("d.m.Y",strtotime("-1 day"));
      $_SESSION['nextdate'] = $nextdate;
      $_SESSION['prevdate'] = $prevdate;
      
  }
      //echo ($prevdate."<br />");
      //echo ($nextdate."<br />");

      $datetime = date("Y-m-d",strtotime($date));

      $current_page = intval($this->param('page'));

    if(!isset($current_page))
      $current_page=1;

        $active_only = !empty($this->param('active')) ? intval($this->param('active')) : 1;
        
        $debts = !empty($this->param('debts')) ? intval($this->param('debts')) : 0;
        //echo "debts = ".$debts.";<br>";
        if($active_only === 1) {
            $products_enabled = "AND products.enabled = 1";
        }
        elseif($active_only === -1) {
            $products_enabled = "AND products.enabled = 0";
        }
        else {
            $products_enabled = "";
        }

	$cat_num = intval(array_search($this->domain,array_values($this->subbrands)));
    if($cat_num>0)
    $this->db->query("SELECT *, title$cat_num as title, description$cat_num as description FROM categories WHERE category_id = '$category_id'");
    else
    $this->db->query("SELECT * FROM categories WHERE category_id = '$category_id'");
    $category = $this->db->result();

    $parent = $category->parent;

    $this->db->query("SELECT * FROM categories WHERE category_id = '$parent'");
    $parent_category = $this->db->result();

    $subcategories = $this->get_categories($category_id);

    $query = sql_placeholder("SELECT
        products.product_id,
	SUM( stat.delta ) AS sum_delta
FROM
	products
	LEFT JOIN stat ON stat.product_id = products.product_id 
WHERE
	( products.category_id = ? ) 
	AND products.enabled = 1 
	AND stat.date >= ? 
	AND stat.date <= ?
GROUP BY
	products.product_id
HAVING sum_delta > 0;", $category->category_id, date("Y-m-d",strtotime($this->settings->start)), $datetime);
        
        $this->db->query($query);
        //echo $query.";<br>";
        
        $products_debts = $this->db->results();
        
        $products_debts_ids = [];
        
        foreach ($products_debts as $pdv) {
            $products_debts_ids[] = $pdv->product_id;
        }
        //echo "<pre>". print_r($products_debts_ids, true)."<pre>";
        
        if($debts > 0 ) {
            $products_enabled = "AND products.product_id IN (". implode(",", $products_debts_ids).")";
        }

/*    $this->db->query("SELECT DISTINCT brand as name FROM products, categories WHERE categories.category_id = products.category_id AND (categories.category_id = '$category->category_id' OR categories.parent = '$category->category_id') AND products.enabled=1 ORDER BY brand");
    $brands = $this->db->results();
    $this->db->query("SELECT * FROM brands ORDER BY name");
    $brands_d = $this->db->results();

    foreach($brands as $k=>$brand1)
  	{
  		  $brands[$k]->url = $this->form_get(array('brand'=>$brand1->name, 'page'=>''));

    	if($brands_d){

  		 	foreach($brands_d as $ks=>$brand1){
  	 			  if($brands_d[$ks]->name == $brands[$k]->name){
         		 $brands[$k]->title = $brands_d[$ks]->title;
         	 	$brands[$k]->description = $brands_d[$ks]->description;}
  		 	}
  		}
  	 if($brand == $brands[$k]->name)
          $brand_discr = $brands[$k]->description;
  	}
*/
  	//print_r($brands);
  	//print_r($brands_d);

    $this->title = !empty($category->title) ? !empty($brand_discr) ? $brand_discr : $category->title : $category->name.' '.$brand ;

    if(isset($category->keywords) && !empty($category->keywords))
    $this->keywords = $category->keywords;

    /*if($subcategories)
    {
    $this->smarty->assign('Category', $category);
    $this->smarty->assign('Categories', $subcategories);
    $this->body = $this->smarty->fetch('storefront_categories.tpl');
    }
    else*/
    {
    $filter = '';

    $legend = '';

    /*if($brand != '')
    $sort = ' ORDER BY products.order_num, products_fotos.foto_id';
    else
    $sort = ' ORDER BY products.brand, products.category_id';
    $sort = '  ORDER BY discount_price ASC';
    $sort = '  ORDER BY products.order_num DESC';*/
        $sort = '  ORDER BY products.model';

    $query = sql_placeholder("SELECT stat.*
    FROM categories, products
    LEFT JOIN stat ON stat.product_id=products.product_id AND stat.date = ?
    WHERE categories.category_id = products.category_id
    AND (categories.category_id = ? OR categories.parent = ?)
    AND categories.enabled = 1 $products_enabled
    GROUP BY products.product_id LIMIT 1", $datetime, $category->category_id, $category->category_id);
        $this->db->query($query);
        $product1 = $this->db->result();
        $breakfast_free = !empty($product1->new_breakfast_free) ? $product1->new_breakfast_free : $this->settings->breakfast_free;
        $breakfast = !empty($product1->new_breakfast) ? $product1->new_breakfast : $this->settings->breakfast;
        $lunch = !empty($product1->new_lunch) ? $product1->new_lunch : $this->settings->lunch;
        $lunch2 = !empty($product1->new_lunch2) ? $product1->new_lunch2 : $this->settings->lunch2;
        $lunch3 = !empty($product1->new_lunch3) ? $product1->new_lunch3 : $this->settings->lunch3;
        $dinner = !empty($product1->new_dinner) ? $product1->new_dinner : $this->settings->dinner;
        $lunch_m = !empty($product1->new_lunch_m) ? $product1->new_lunch_m : $this->settings->lunch_m;
        $dinner_m = !empty($product1->new_dinner_m) ? $product1->new_dinner_m : $this->settings->dinner_m;

    //print_r($_POST['brands']);

    //echo $filter;

    $start_item = $current_page*$this->items_per_page;
    $end_item = $start_item + $this->items_per_page;

    //echo "<br>current_page = ".$current_page;
    //echo "<br>start_item = ".$start_item;
    $brand_filter='';

    if($brand != '')
    $brand_filter="AND (brand = '$brand')";

   	$params = $_GET;

	unset($params['page']);

	if(isset($_SESSION['brand'])) $params['brand'] = $brand;

	$filename="cache/".implode("_",$params);

	//echo "<br>filename = ".$filename;

	$ftime = intval(@filemtime($filename));

	//echo "<br>ftime = ".$ftime;

	clearstatcache();

	$interval = (time()-$ftime)/60;

	//echo "<br>interval = ".$interval;
    //echo "<br>delay = ".$this->config->delay;
        


        if(!empty($_POST)){

            //echo "<pre>"; print_r($_POST); echo "</pre>";
            
            //$query = ("REPLACE INTO `stat` (product_id,date,category_id,breakfast,lunch,lunch2,dinner,cash,card,new_breakfast,new_lunch,new_lunch2,new_dinner,delta) VALUES ".implode(",",$qstring));
            //$this->db->query($query);
            //echo $query.";<br>";              

            $query = sql_placeholder("SELECT stat.*, products.product_id, products.category_id,products.model, categories.name as category
    FROM categories, products
    LEFT JOIN stat ON stat.product_id=products.product_id AND stat.date = ?
    WHERE categories.category_id = products.category_id
    AND (categories.category_id = ? OR categories.parent = ?)
    $brand_filter
    AND categories.enabled = 1 $products_enabled $filter
    GROUP BY products.product_id
    $sort", $datetime, $category->category_id, $category->category_id);
            $this->db->query($query);
            $products = $this->db->results();
            
            $qstring = array();
            $price = array();
            
            foreach ($products as $id=>$product){
                
                $breakfast_free0 = !empty($product->new_breakfast_free) ? $product->new_breakfast_free : $this->settings->breakfast_free;
                $breakfast0 = !empty($product->new_breakfast) ? $product->new_breakfast : $this->settings->breakfast;
                $lunch0 = !empty($product->new_lunch) ? $product->new_lunch : $this->settings->lunch;
                $lunch20 = !empty($product->new_lunch2) ? $product->new_lunch2 : $this->settings->lunch2;
                $lunch30 = !empty($product->new_lunch3) ? $product->new_lunch3 : $this->settings->lunch3;
                $dinner0 = !empty($product->new_dinner) ? $product->new_dinner : $this->settings->dinner;
                $lunch_m0 = !empty($product->new_lunch_m) ? $product->new_lunch_m : $this->settings->lunch_m;
                $dinner_m0 = !empty($product->new_dinner_m) ? $product->new_dinner_m : $this->settings->dinner_m;
                
                $delta = (isset($_POST['abreakfast_free']) ? $_POST['abreakfast_free'][$product->product_id]*$breakfast_free0 : 0)
                        + $_POST['abreakfast'][$product->product_id]*$breakfast0
                        + $_POST['alunch'][$product->product_id]*$lunch0
                        + $_POST['alunch2'][$product->product_id]*$lunch20
                        + $_POST['alunch3'][$product->product_id]*$lunch30
                        + $_POST['adinner'][$product->product_id]*$dinner0
                         + $_POST['alunch_m'][$product->product_id]*$lunch_m0
                         + $_POST['adinner_m'][$product->product_id]*$dinner_m0
                        - floatval($_POST['cash'][$product->product_id])
                        - floatval($_POST['card'][$product->product_id]);
                //echo "delta2 = ".$delta."<br />";
                //echo "product[$id] = ".  print_r($product,1)."<br />";
                $query = sql_placeholder("SELECT SUM(stat.delta) as sum FROM  products
    LEFT JOIN stat ON stat.product_id=products.product_id
    WHERE products.product_id=? AND stat.date BETWEEN ? AND ?
    $brand_filter
    $products_enabled
    GROUP BY products.product_id
    ", $product->product_id, date("Y-m-d",strtotime($this->settings->start)), date("Y-m-d",strtotime("-1 day",strtotime($date))));
                $this->db->query($query);
                $sum = $this->db->result();
                //echo $query.";<br>";
                $price[$id] = $sum->sum;
                
                if(strtotime($date) < strtotime('2021-09-01 00:00:00')) {
                    if(isset($_POST['abreakfast_free'])) {
                        $qstring[] = sql_placeholder("("
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?)",
                            $product->product_id,
                            $datetime,
                            $product->category_id,
                            $_POST['abreakfast_free'][$product->product_id],
                            $_POST['abreakfast'][$product->product_id],
                            $_POST['alunch'][$product->product_id],
                            $_POST['alunch2'][$product->product_id],
                            $_POST['alunch3'][$product->product_id],
                            $_POST['adinner'][$product->product_id],
                            $_POST['alunch_m'][$product->product_id],
                            $_POST['adinner_m'][$product->product_id],
                            floatval($_POST['cash'][$product->product_id]),
                            floatval($_POST['card'][$product->product_id]),
                            floatval($_POST['breakfast_free']),
                            floatval($_POST['breakfast']),
                            floatval($_POST['lunch']),
                            floatval($_POST['lunch2']),
                            floatval($_POST['lunch3']),
                            floatval($_POST['dinner']),
                            floatval($_POST['lunch_m']),
                            floatval($_POST['dinner_m']),
                            $delta);                                            
                    }
                    else {
                        $qstring[] = sql_placeholder("("
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?)",
                            $product->product_id,
                            $datetime,
                            $product->category_id,
                            $_POST['abreakfast'][$product->product_id],
                            $_POST['alunch'][$product->product_id],
                            $_POST['alunch2'][$product->product_id],
                            $_POST['alunch3'][$product->product_id],
                            $_POST['adinner'][$product->product_id],
                            $_POST['alunch_m'][$product->product_id],
                            $_POST['adinner_m'][$product->product_id],
                            floatval($_POST['cash'][$product->product_id]),
                            floatval($_POST['card'][$product->product_id]),
                            floatval($_POST['breakfast']),
                            floatval($_POST['lunch']),
                            floatval($_POST['lunch2']),
                            floatval($_POST['lunch3']),
                            floatval($_POST['dinner']),
                            floatval($_POST['lunch_m']),
                            floatval($_POST['dinner_m']),
                            $delta);                        
                    }
                }
                else {
                    if(isset($_POST['abreakfast_free'])) {
                        $qstring[] = sql_placeholder("("
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?)",
                            $product->product_id,
                            $datetime,
                            $product->category_id,
                            $_POST['abreakfast_free'][$product->product_id],
                            $_POST['abreakfast'][$product->product_id],
                            $_POST['alunch'][$product->product_id],
                            $_POST['alunch2'][$product->product_id],
                            $_POST['adinner'][$product->product_id],
                            $_POST['alunch_m'][$product->product_id],
                            $_POST['adinner_m'][$product->product_id],
                            floatval($_POST['cash'][$product->product_id]),
                            floatval($_POST['card'][$product->product_id]),
                            floatval($_POST['breakfast_free']),
                            floatval($_POST['breakfast']),
                            floatval($_POST['lunch']),
                            floatval($_POST['lunch2']),
                            floatval($_POST['dinner']),
                            floatval($_POST['lunch_m']),
                            floatval($_POST['dinner_m']),
                            $delta);                                              
                    }
                    else {
                        $qstring[] = sql_placeholder("("
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?)",
                            $product->product_id,
                            $datetime,
                            $product->category_id,
                            $_POST['abreakfast'][$product->product_id],
                            $_POST['alunch'][$product->product_id],
                            $_POST['alunch2'][$product->product_id],
                            $_POST['adinner'][$product->product_id],
                            $_POST['alunch_m'][$product->product_id],
                            $_POST['adinner_m'][$product->product_id],
                            floatval($_POST['cash'][$product->product_id]),
                            floatval($_POST['card'][$product->product_id]),
                            floatval($_POST['breakfast']),
                            floatval($_POST['lunch']),
                            floatval($_POST['lunch2']),
                            floatval($_POST['dinner']),
                            floatval($_POST['lunch_m']),
                            floatval($_POST['dinner_m']),
                            $delta);                        
                    }
                }
                

            }
                if(strtotime($date) < strtotime('2021-09-01 00:00:00')) {
                    if(isset($_POST['abreakfast_free'])) {
                        $query = ("REPLACE INTO `stat` ("
                                . "product_id,"
                                . "date,"
                                . "category_id,"
                                . "breakfast_free,"
                                . "breakfast,"
                                . "lunch,"
                                . "lunch2,"
                                . "lunch3,"
                                . "dinner,"
                                . "lunch_m,"
                                . "dinner_m,"
                                . "cash,"
                                . "card,"
                                . "new_breakfast_free,"
                                . "new_breakfast,"
                                . "new_lunch,"
                                . "new_lunch2,"
                                . "new_lunch3,"
                                . "new_dinner,"
                                . "new_lunch_m,"
                                . "new_dinner_m,"
                                . "delta) VALUES ".implode(",",$qstring));                   
                        
                    }
                    else {
                        $query = ("REPLACE INTO `stat` ("
                                . "product_id,"
                                . "date,"
                                . "category_id,"
                                . "breakfast,"
                                . "lunch,"
                                . "lunch2,"
                                . "lunch3,"
                                . "dinner,"
                                . "lunch_m,"
                                . "dinner_m,"
                                . "cash,"
                                . "card,"
                                . "new_breakfast,"
                                . "new_lunch,"
                                . "new_lunch2,"
                                . "new_lunch3,"
                                . "new_dinner,"
                                . "new_lunch_m,"
                                . "new_dinner_m,"
                                . "delta) VALUES ".implode(",",$qstring));                           
                    }
                }
                else {
                    if(isset($_POST['abreakfast_free'])) {
                        $query = ("REPLACE INTO `stat` ("
                                . "product_id,"
                                . "date,"
                                . "category_id,"
                                . "breakfast_free,"
                                . "breakfast,"
                                . "lunch,"
                                . "lunch2,"
                                . "dinner,"
                                . "lunch_m,"
                                . "dinner_m,"
                                . "cash,"
                                . "card,"
                                . "new_breakfast_free,"
                                . "new_breakfast,"
                                . "new_lunch,"
                                . "new_lunch2,"
                                . "new_dinner,"
                                . "new_lunch_m,"
                                . "new_dinner_m,"
                                . "delta) VALUES ".implode(",",$qstring));                                             
                    }
                    else {
                        $query = ("REPLACE INTO `stat` ("
                                . "product_id,"
                                . "date,"
                                . "category_id,"
                                . "breakfast,"
                                . "lunch,"
                                . "lunch2,"
                                . "dinner,"
                                . "lunch_m,"
                                . "dinner_m,"
                                . "cash,"
                                . "card,"
                                . "new_breakfast,"
                                . "new_lunch,"
                                . "new_lunch2,"
                                . "new_dinner,"
                                . "new_lunch_m,"
                                . "new_dinner_m,"
                                . "delta) VALUES ".implode(",",$qstring));                        
                    }
                }
                $this->db->query($query); 

            //echo $query.";<br>";            
        }
        

    if (true/*$interval>$this->config->delay || !empty($_POST)*/)
	{
        //echo "<pre>"; print_r($_POST); echo "</pre>";
    /*$this->db->query('SELECT COUNT(comment_id) as count FROM products_comments');
    $res = $this->db->result();
    $sum = $res->count;*/
    $query = sql_placeholder("SELECT ".(empty($_POST) ? "SQL_NO_CACHE" : "")." stat.*, products.product_id, products.category_id,products.model, categories.name as category
    FROM categories, products
    LEFT JOIN stat ON stat.product_id=products.product_id AND stat.date = ?
    WHERE categories.category_id = products.category_id
    AND (categories.category_id = ? OR categories.parent = ?)
    $brand_filter
    AND categories.enabled = 1 $products_enabled $filter
    GROUP BY products.product_id
    $sort", $datetime, $category->category_id, $category->category_id);
    $this->db->query($query);
    $products = $this->db->results();
 
        if(!empty($_POST)){

            //echo "<pre>"; print_r($_POST); echo "</pre>";
            
            //$query = ("REPLACE INTO `stat` (product_id,date,category_id,breakfast,lunch,lunch2,dinner,cash,card,new_breakfast,new_lunch,new_lunch2,new_dinner,delta) VALUES ".implode(",",$qstring));
            //$this->db->query($query);
            //echo $query.";<br>";              

            $query = sql_placeholder("SELECT stat.*, products.product_id, products.category_id,products.model, categories.name as category
    FROM categories, products
    LEFT JOIN stat ON stat.product_id=products.product_id AND stat.date = ?
    WHERE categories.category_id = products.category_id
    AND (categories.category_id = ? OR categories.parent = ?)
    $brand_filter
    AND categories.enabled = 1 $products_enabled $filter
    GROUP BY products.product_id
    $sort", $datetime, $category->category_id, $category->category_id);
            $this->db->query($query);
            $products = $this->db->results();
            
            $qstring = array();
            $price = array();
            
            foreach ($products as $id=>$product){
                
                $breakfast_free0 = !empty($product->new_breakfast_free) ? $product->new_breakfast_free : $this->settings->breakfast_free;
                $breakfast0 = !empty($product->new_breakfast) ? $product->new_breakfast : $this->settings->breakfast;
                $lunch0 = !empty($product->new_lunch) ? $product->new_lunch : $this->settings->lunch;
                $lunch20 = !empty($product->new_lunch2) ? $product->new_lunch2 : $this->settings->lunch2;
                $lunch30 = !empty($product->new_lunch3) ? $product->new_lunch3 : $this->settings->lunch3;
                $dinner0 = !empty($product->new_dinner) ? $product->new_dinner : $this->settings->dinner;
                $lunch_m0 = !empty($product->new_lunch_m) ? $product->new_lunch_m : $this->settings->lunch_m;
                $dinner_m0 = !empty($product->new_dinner_m) ? $product->new_dinner_m : $this->settings->dinner_m;
                
                $delta = (isset($_POST['abreakfast_free']) ? $_POST['abreakfast_free'][$product->product_id]*$breakfast_free0 : 0)
                        + $_POST['abreakfast'][$product->product_id]*$breakfast0
                        + $_POST['alunch'][$product->product_id]*$lunch0
                        + $_POST['alunch2'][$product->product_id]*$lunch20
                        + $_POST['alunch3'][$product->product_id]*$lunch30
                        + $_POST['adinner'][$product->product_id]*$dinner0
                         + $_POST['alunch_m'][$product->product_id]*$lunch_m0
                         + $_POST['adinner_m'][$product->product_id]*$dinner_m0
                        - floatval($_POST['cash'][$product->product_id])
                        - floatval($_POST['card'][$product->product_id]);
                //echo "delta2 = ".$delta."<br />";
                //echo "product[$id] = ".  print_r($product,1)."<br />";
                $query = sql_placeholder("SELECT SUM(stat.delta) as sum FROM  products
    LEFT JOIN stat ON stat.product_id=products.product_id
    WHERE products.product_id=? AND stat.date BETWEEN ? AND ?
    $brand_filter
    $products_enabled
    GROUP BY products.product_id
    ", $product->product_id, date("Y-m-d",strtotime($this->settings->start)), date("Y-m-d",strtotime("-1 day",strtotime($date))));
                $this->db->query($query);
                $sum = $this->db->result();
                //echo $query.";<br>";
                $price[$id] = $sum->sum;
                
                if(strtotime($date) < strtotime('2021-09-01 00:00:00')) {
                    if(isset($_POST['abreakfast_free'])) {
                        $qstring[] = sql_placeholder("("
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?)",
                            $product->product_id,
                            $datetime,
                            $product->category_id,
                            $_POST['abreakfast_free'][$product->product_id],
                            $_POST['abreakfast'][$product->product_id],
                            $_POST['alunch'][$product->product_id],
                            $_POST['alunch2'][$product->product_id],
                            $_POST['alunch3'][$product->product_id],
                            $_POST['adinner'][$product->product_id],
                            $_POST['alunch_m'][$product->product_id],
                            $_POST['adinner_m'][$product->product_id],
                            floatval($_POST['cash'][$product->product_id]),
                            floatval($_POST['card'][$product->product_id]),
                            floatval($_POST['breakfast_free']),
                            floatval($_POST['breakfast']),
                            floatval($_POST['lunch']),
                            floatval($_POST['lunch2']),
                            floatval($_POST['lunch3']),
                            floatval($_POST['dinner']),
                            floatval($_POST['lunch_m']),
                            floatval($_POST['dinner_m']),
                            $delta);                                            
                    }
                    else {
                        $qstring[] = sql_placeholder("("
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?)",
                            $product->product_id,
                            $datetime,
                            $product->category_id,
                            $_POST['abreakfast'][$product->product_id],
                            $_POST['alunch'][$product->product_id],
                            $_POST['alunch2'][$product->product_id],
                            $_POST['alunch3'][$product->product_id],
                            $_POST['adinner'][$product->product_id],
                            $_POST['alunch_m'][$product->product_id],
                            $_POST['adinner_m'][$product->product_id],
                            floatval($_POST['cash'][$product->product_id]),
                            floatval($_POST['card'][$product->product_id]),
                            floatval($_POST['breakfast']),
                            floatval($_POST['lunch']),
                            floatval($_POST['lunch2']),
                            floatval($_POST['lunch3']),
                            floatval($_POST['dinner']),
                            floatval($_POST['lunch_m']),
                            floatval($_POST['dinner_m']),
                            $delta);                        
                    }
                }
                else {
                    if(isset($_POST['abreakfast_free'])) {
                        $qstring[] = sql_placeholder("("
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?)",
                            $product->product_id,
                            $datetime,
                            $product->category_id,
                            $_POST['abreakfast_free'][$product->product_id],
                            $_POST['abreakfast'][$product->product_id],
                            $_POST['alunch'][$product->product_id],
                            $_POST['alunch2'][$product->product_id],
                            $_POST['adinner'][$product->product_id],
                            $_POST['alunch_m'][$product->product_id],
                            $_POST['adinner_m'][$product->product_id],
                            floatval($_POST['cash'][$product->product_id]),
                            floatval($_POST['card'][$product->product_id]),
                            floatval($_POST['breakfast_free']),
                            floatval($_POST['breakfast']),
                            floatval($_POST['lunch']),
                            floatval($_POST['lunch2']),
                            floatval($_POST['dinner']),
                            floatval($_POST['lunch_m']),
                            floatval($_POST['dinner_m']),
                            $delta);                                              
                    }
                    else {
                        $qstring[] = sql_placeholder("("
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?,"
                            . "?)",
                            $product->product_id,
                            $datetime,
                            $product->category_id,
                            $_POST['abreakfast'][$product->product_id],
                            $_POST['alunch'][$product->product_id],
                            $_POST['alunch2'][$product->product_id],
                            $_POST['adinner'][$product->product_id],
                            $_POST['alunch_m'][$product->product_id],
                            $_POST['adinner_m'][$product->product_id],
                            floatval($_POST['cash'][$product->product_id]),
                            floatval($_POST['card'][$product->product_id]),
                            floatval($_POST['breakfast']),
                            floatval($_POST['lunch']),
                            floatval($_POST['lunch2']),
                            floatval($_POST['dinner']),
                            floatval($_POST['lunch_m']),
                            floatval($_POST['dinner_m']),
                            $delta);                        
                    }
                }
                

            }
                if(strtotime($date) < strtotime('2021-09-01 00:00:00')) {
                    if(isset($_POST['abreakfast_free'])) {
                        $query = ("REPLACE INTO `stat` ("
                                . "product_id,"
                                . "date,"
                                . "category_id,"
                                . "breakfast_free,"
                                . "breakfast,"
                                . "lunch,"
                                . "lunch2,"
                                . "lunch3,"
                                . "dinner,"
                                . "lunch_m,"
                                . "dinner_m,"
                                . "cash,"
                                . "card,"
                                . "new_breakfast_free,"
                                . "new_breakfast,"
                                . "new_lunch,"
                                . "new_lunch2,"
                                . "new_lunch3,"
                                . "new_dinner,"
                                . "new_lunch_m,"
                                . "new_dinner_m,"
                                . "delta) VALUES ".implode(",",$qstring));                   
                        
                    }
                    else {
                        $query = ("REPLACE INTO `stat` ("
                                . "product_id,"
                                . "date,"
                                . "category_id,"
                                . "breakfast,"
                                . "lunch,"
                                . "lunch2,"
                                . "lunch3,"
                                . "dinner,"
                                . "lunch_m,"
                                . "dinner_m,"
                                . "cash,"
                                . "card,"
                                . "new_breakfast,"
                                . "new_lunch,"
                                . "new_lunch2,"
                                . "new_lunch3,"
                                . "new_dinner,"
                                . "new_lunch_m,"
                                . "new_dinner_m,"
                                . "delta) VALUES ".implode(",",$qstring));                           
                    }
                }
                else {
                    if(isset($_POST['abreakfast_free'])) {
                        $query = ("REPLACE INTO `stat` ("
                                . "product_id,"
                                . "date,"
                                . "category_id,"
                                . "breakfast_free,"
                                . "breakfast,"
                                . "lunch,"
                                . "lunch2,"
                                . "dinner,"
                                . "lunch_m,"
                                . "dinner_m,"
                                . "cash,"
                                . "card,"
                                . "new_breakfast_free,"
                                . "new_breakfast,"
                                . "new_lunch,"
                                . "new_lunch2,"
                                . "new_dinner,"
                                . "new_lunch_m,"
                                . "new_dinner_m,"
                                . "delta) VALUES ".implode(",",$qstring));                                             
                    }
                    else {
                        $query = ("REPLACE INTO `stat` ("
                                . "product_id,"
                                . "date,"
                                . "category_id,"
                                . "breakfast,"
                                . "lunch,"
                                . "lunch2,"
                                . "dinner,"
                                . "lunch_m,"
                                . "dinner_m,"
                                . "cash,"
                                . "card,"
                                . "new_breakfast,"
                                . "new_lunch,"
                                . "new_lunch2,"
                                . "new_dinner,"
                                . "new_lunch_m,"
                                . "new_dinner_m,"
                                . "delta) VALUES ".implode(",",$qstring));                        
                    }
                }
                $this->db->query($query); 

            //echo $query.";<br>";            
        }
            /*
        	foreach ($products as $id=>$product){


             $query = sql_placeholder("SELECT SUM(stat.hits) as hits
                              FROM products
                              LEFT JOIN stat ON stat.product_id = products.product_id
                              WHERE products.product_id = ?", $product->product_id);
    			$this->db->query($query);
    			$product->hits = $this->db->result();

             $query = sql_placeholder("SELECT SUM(products_comments.point)/".$sum." as point
             FROM products
             LEFT JOIN products_comments ON products.product_id = products_comments.product_id  AND products_comments.point>0
             WHERE products.product_id = ?", $product->product_id);
    			$this->db->query($query);
    			$res2 = $this->db->result();
    			$product->point = $res2->point;
    		}
             */
    /*$this->db->query("SELECT FOUND_ROWS() as count");
    $pages_num0 = $this->db->result();
    $pages_num = ceil($pages_num0->count/$this->items_per_page);*/

    //echo $query.";<br />";
    //if(empty($_POST))
    //{
    /*for ($handle = fopen($filename,"w"),$i=0;$sd_statistic_info=get_object_vars($products[$i]);$i++)
		{
		$string = "";
		foreach($sd_statistic_info as $k=>$v)
		{
		$string .= "{elem}".$k."=>".$v;
		}
		$string .= "{eol}";
		fwrite($handle,$string);
		}
		fclose($handle);*/
    //}
    //file_put_contents($filename,serialize($products));
    }

    //if(empty($_POST))
    //{

    /*$products=array();

    $file_string = file_get_contents($filename);*/
    
    //$products_all = $products;

    /*$strings = explode("{eol}",$file_string);

    //print_r($strings);

    for($k=$start_item;$k<$end_item;$k++)
    {
    //echo "<br>k = ".$k;
    $row = explode("{elem}",$strings[$k]);
    foreach($row as $elem)
    {
    if(preg_match("/=>/",$elem))
    {$elems = explode("=>",$elem);$products[$k]->$elems[0]=$elems[1];}
    }
    }
    //}
    $pages_num0 = count($strings)-1;
    $pages_num = ceil($pages_num0/$this->items_per_page);*/

    //echo "<br>pages_num0 = ".$pages_num0;
    //echo "<br>items_per_page = ".$this->items_per_page;
    //echo "<br>pages_num = ".$pages_num;
    
    /*for($k=$start_item;$k<$end_item;$k++)
    {
      if(array_key_exists($k,$products_all))
      $products[] = $products_all[$k];
      else
      break;
    }
    $pages_num0 = count($products_all)-1;
    $pages_num = ceil($pages_num0/$this->items_per_page);
    unset($file_string);
    unset($products_all);*/    

    for($i=0;$i<$pages_num;$i++)
  	{
  		  $url[$i] = 'index.php'.$this->form_get(array('brand'=>$brand, 'page'=>$i));

  	}

    $qstring = array();
    $price = array();

    	foreach ($products as $id=>$product){
        
        //$delta = $_POST['abreakfast'][$product->product_id]*$product->new_breakfast+$_POST['alunch'][$product->product_id]*$product->new_lunch+$_POST['alunch2'][$product->product_id]*$product->new_lunch2+$_POST['adinner'][$product->product_id]*$product->new_dinner-floatval($_POST['cash'][$product->product_id])-floatval($_POST['card'][$product->product_id]);
        //echo "delta = ".$delta."<br />";
    $query = sql_placeholder("SELECT SUM(stat.delta) as sum FROM  products
    LEFT JOIN stat ON stat.product_id=products.product_id
    WHERE products.product_id=? AND stat.date BETWEEN ? AND ?
    $brand_filter
    $products_enabled
    GROUP BY products.product_id
    ", $product->product_id, date("Y-m-d",strtotime($this->settings->start)), date("Y-m-d",strtotime("-1 day",strtotime($date))));
    $this->db->query($query);
    $sum = $this->db->result();
            //echo $query.";<br>";
            if(!empty($sum->sum)){
              $price[$id] = $sum->sum;  
            }            
        else {
            $price[$id] = 0;  
        }

            //$qstring[] = sql_placeholder("(?,?,?,?,?,?,?,?,?,?,?,?,?,?)",$product->product_id,$datetime,$product->category_id,$_POST['abreakfast'][$product->product_id],$_POST['alunch'][$product->product_id],$_POST['alunch2'][$product->product_id],$_POST['adinner'][$product->product_id],floatval($_POST['cash'][$product->product_id]),floatval($_POST['card'][$product->product_id]),floatval($_POST['breakfast']),floatval($_POST['lunch']),floatval($_POST['lunch2']),floatval($_POST['dinner']),$delta);

       }
        
        $query = sql_placeholder("SELECT stat.*
    FROM categories, products
    LEFT JOIN stat ON stat.product_id=products.product_id AND stat.date = ?
    WHERE categories.category_id = products.category_id
    AND (categories.category_id = ? OR categories.parent = ?)
    AND categories.enabled = 1 $products_enabled
    GROUP BY products.product_id LIMIT 1", $datetime, $category->category_id, $category->category_id);
        $this->db->query($query);
        $product1 = $this->db->result();
        $breakfast_free = !empty($product1->new_breakfast_free) ? $product1->new_breakfast_free : $this->settings->breakfast_free;
        $breakfast = !empty($product1->new_breakfast) ? $product1->new_breakfast : $this->settings->breakfast;
        $lunch = !empty($product1->new_lunch) ? $product1->new_lunch : $this->settings->lunch;
        $lunch2 = !empty($product1->new_lunch2) ? $product1->new_lunch2 : $this->settings->lunch2;
        $lunch3 = !empty($product1->new_lunch3) ? $product1->new_lunch3 : $this->settings->lunch3;
        $dinner = !empty($product1->new_dinner) ? $product1->new_dinner : $this->settings->dinner;

    //echo "<pre>\n";
    //print_r($products);
    //print_r($price);
    //print_r($category);
    //print_r($url);
    //echo "</pre>\n";



    $this->smarty->assign('Products', $products);
    $this->smarty->assign('Category', $category);
    $this->smarty->assign('Parent_category', $parent_category);
    $this->smarty->assign('Price', $price);
    //$this->smarty->assign('Brands', $brands);
    //$this->smarty->assign('Searches', $this->searches);
    //$this->smarty->assign('Filter_mask', $filter_mask);
    $this->smarty->assign('Legend', $legend);
    //$this->smarty->assign('Sort_ids', $this->sort_ids);
    $this->smarty->assign('Brand', $brand);
    $this->smarty->assign('URLBrand', urlencode($brand));
    $this->smarty->assign('PagesNum', $pages_num);
    //$this->smarty->assign('Url', $url);
    $this->smarty->assign('active_only', $active_only);
    $this->smarty->assign('CurrentPage', $current_page);
    $this->smarty->assign('Date', $date);
    $this->smarty->assign('Timestamp', strtotime($date));
    $this->smarty->assign('NextDate', $nextdate);
    $this->smarty->assign('PrevDate', $prevdate);
    $this->smarty->assign('Breakfast_free', $breakfast_free);
    $this->smarty->assign('Breakfast', $breakfast);
    $this->smarty->assign('Lunch', $lunch);
    $this->smarty->assign('Lunch2', $lunch2);
    $this->smarty->assign('Lunch3', $lunch3);
    $this->smarty->assign('Dinner', $dinner);
    $this->smarty->assign('Lunch_m', $lunch_m);
    $this->smarty->assign('Dinner_m', $dinner_m);
    if((isset($print) && $print==1) || (isset($exel) && $exel==1))
    $this->body = $this->smarty->fetch('storefront_items_print.tpl');
    else
    $this->body = $this->smarty->fetch('storefront_items.tpl');
    }
  }




  function fetch_item($id)
  {
    $secret = md5(date('Hmd').$this->config->dbname);
    ## Принимает отзыв
    //echo $secret;
    if(isset($_POST['name']) && !empty($_POST['point']) && $_POST['s']==$secret)
    {
      $name = $_POST['name'];
      $comment = $_POST['comment'];
      $point = intval($_POST['point']%6);

      ## Проверим голосовали ли сегодня с этого айпишника
      $query = sql_placeholder("SELECT count(*) as count FROM products_comments WHERE product_id=? AND date=DATE(NOW()) AND ip=?",
               $id, $_SERVER['REMOTE_ADDR']);
      $this->db->query($query);
      $res = $this->db->result();
      if($res->count > 0)
        $point = 0;
        if(!$this->user && (!isset($_SESSION['captcha_keystring']) || $_SESSION['captcha_keystring'] != $_POST['keystring']))
	    $error = "Защитный код неверен";
      $query = sql_placeholder("SELECT * FROM products_comments WHERE product_id=? AND ip=? ORDER BY comment_id DESC LIMIT 1",
               $id, $_SERVER['REMOTE_ADDR'], $comment);
      $this->db->query($query);
      $res = $this->db->result();
      if($res->comment != $comment)
      {
        if(!$error)
        {
         $query = sql_placeholder("INSERT INTO products_comments (date, product_id, ip, name, comment, point) VALUES(NOW(), ?, ?, ?, ?, ?)",
               $id, $_SERVER['REMOTE_ADDR'], $name, $comment, $point);
         $this->db->query($query);
        }
        else
        $this->smarty->assign('Message', $error);
      }
    }
    ##

    $this->db->query("SELECT * from stat WHERE product_id = '$id' AND date = DATE(NOW())");
    if($this->db->num_rows() == 0)
      $this->db->query("INSERT INTO stat SET hits=1, product_id = '$id', date=DATE(NOW())");
    else
      $this->db->query("UPDATE stat SET hits=hits+1 WHERE product_id = '$id' AND date = DATE(NOW())");

    $query = sql_placeholder("SELECT products_fotos.*, products.*, products.price*(100-".($this->user->discount?$this->user->discount:0).")/100*".$this->currency->rate."/currencies.rate  as discount_price,
                              SUM(products_comments.point)/COUNT(products_comments.comment_id) as point
                              FROM products LEFT JOIN products_fotos ON products_fotos.product_id=products.product_id
                              LEFT JOIN products_comments ON products.product_id = products_comments.product_id  AND products_comments.point>0
                              LEFT JOIN currencies ON currencies.code = products.currency_id
                              WHERE products.product_id = $id
                              GROUP BY products.product_id");
    $this->db->query($query);
    $product = $this->db->result();

    $product->urlbrand = urlencode($product->brand);

      $this->db->query("SELECT * FROM products_fotos WHERE product_id = $id");
      $fotos = $this->db->results();

      foreach ($fotos as $fk=>$foto)
      {
          if($foto->filename)
          {
              $foto->filename = $this->storefront.trim($foto->filename);
              $info = getimagesize(dirname(__FILE__).'/'.$foto->filename);
              if($info[0]>$info[1])
              {
                  if($info[0]>350)
                  {
                      $foto->width = 350;
                      $foto->height = round(350*$info[1]/$info[0]);
                  }
                  else
                  {
                      $foto->width = $info[0];
                      $foto->height = $info[1];
                  }
              }
              else
              {
                  if($info[1]>350)
                  {
                      $foto->height = 350;
                      $foto->width = round(350*$info[0]/$info[1]);
                  }
                  else
                  {
                      $foto->width = $info[0];
                      $foto->height = $info[1];
                  }
              }
          }
          else
          {
              $foto->filename = "images/no_foto.gif";
              $foto->filename = $foto->filename;
              $info = getimagesize(dirname(__FILE__).'/'.$product->filename);
              if($info[0]>$info[1])
              {
                  if($info[0]>250)
                  {
                      $foto->width = 250;
                      $foto->height = round(250*$info[1]/$info[0]);
                  }
                  else
                  {
                      $foto->width = $info[0];
                      $foto->height = $info[1];
                  }
              }
              else
              {
                  if($info[1]>250)
                  {
                      $foto->height = 250;
                      $foto->width = round(250*$info[0]/$info[1]);
                  }
                  else
                  {
                      $foto->width = $info[0];
                      $foto->height = $info[1];
                  }
              }
          }

      }

    $this->db->query("SELECT * FROM categories WHERE category_id = '$product->category_id'");
    $cat = $this->db->result();

        $pnames = array();
        $pnames[0] = $cat->name;
        //print_r($pnames);echo "<br />\n";
        if($cat->alies)
        {
        $palies = explode(";;",$cat->alies);
        if(is_array($palies))
        {
        foreach ($palies as $akey=>$paly)
        {
        if($palies[$akey]=='') unset($palies[$akey]);
        array_push($pnames,$palies[$akey]);
        }

        }
        $cat->name = $pnames[$product->category_name];
        }
        //print_r($pnames);echo "<br />\n";

    $this->db->query("SELECT * FROM categories WHERE category_id = '$product->subcategory_id'");
    $subcat = $this->db->result();

    $parent = $cat->parent;

    $this->db->query("SELECT * FROM categories WHERE category_id = '$parent'");
    $parent_category = $this->db->result();

    $group_properties = $this->get_group_properties();

    $this->db->query("SELECT * FROM currencies ORDER BY currency_id");
    $currencies = $this->db->results();

    foreach($currencies as $k=>$currency)
    {
    if ($currency->code == $product->currency_id)
    $product->currency_rate = $currency->rate;
    }

 	  $group_name = array();

      foreach ($group_properties as $k=>$property){
        $group_name[$property->category_id] = $property->name;
      }


 	 	  $query = sql_placeholder("SELECT  property_id
    				  FROM `category_properties` WHERE category_id IN ( ?, ?)",
  			                          $product->category_id,
  			                          $cat->parent);


 	 	$this->db->query($query);

	    $properties_id = $this->db->results();




		    if($properties_id) {

 	 		  $props = array();

     		    foreach ($properties_id as $k=>$property){
     		     $props[] = $property->property_id;
     		    }

 	 		   $items_sql = " IN ('".implode("', '", $props)."')";

 		   }
 	 		else{
 	 	 	 $items_sql = "<0";
	 	   }


    	 $query = "SELECT * FROM index_properties
    				  WHERE enabled = 'true'
    				  AND property_id$items_sql
    				  ORDER BY category_id, sort";

 	 	  $this->db->query($query);

	  	$properties = $this->db->results();

         //echo $query.";<br>";

	$discount_price0 = $product->discount_price;

	$brand = isset($_SESSION['brand']) ? $_SESSION['brand'] : '';

    if($brand != '')
    $brand_filter="AND (brand = '$brand')";

    $query = "SELECT products.*, categories.name, products_fotos.filename, products.price*(100-".($this->user->discount?$this->user->discount:0).")/100*".$this->currency->rate."/currencies.rate*(100-products.discount)/100 as discount_price, ABS(".$discount_price0."-products.price*(100-".($this->user->discount?$this->user->discount:0).")/100*".$this->currency->rate."/currencies.rate*(100-products.discount)/100) as delta_price FROM categories,products LEFT JOIN currencies ON currencies.code = products.currency_id LEFT JOIN products_fotos ON products_fotos.product_id = products.product_id WHERE categories.category_id = products.category_id AND (categories.category_id = '".$product->category_id."' OR categories.category_id = '".$cat->parent."') AND products.enabled=1 AND products.product_id <> $id AND products.price<=".($discount_price0+0.1*$discount_price0)."/((100-".($this->user->discount?$this->user->discount:0).")/100*".$this->currency->rate."/currencies.rate*(100-products.discount)/100) AND products.price>=".($discount_price0-0.1*$discount_price0)."/((100-".($this->user->discount?$this->user->discount:0).")/100*".$this->currency->rate."/currencies.rate*(100-products.discount)/100) $brand_filter GROUP BY products.product_id ORDER BY products.brand,delta_price LIMIT 6";
    //echo "$query<br />\n";
    $this->db->query($query);
    $analogs = $this->db->results();

    $catmax = 6;
    $numcat = 1;
    $last = "";

    foreach ($analogs as $ak=>$analog)
    {
    	$analog->urlbrand = urlencode($analog->brand);

    	if($last == $analog->category_id && $numcat<$catmax)
    	{
    	$numcat++;
    	}
    	elseif($last != $analog->category_id)
    	{
    	$last = $analog->category_id;
    	$numcat = 1;
    	}
    	else
    	{
        unset($analogs[$ak]);
    	}

    }
    /*echo '<pre>';
    print_r ($analogs);
    echo '</pre>';*/

	  	//print_r($properties); echo "<br>";

         if($properties){

	  	  foreach ($properties as $pk=>$property) {

          $query = sql_placeholder('SELECT '.$property->name.' FROM products_properties  WHERE product_id=?', $product->product_id);
  		  //$this->db->query($query);
 		  //$product->properties[$k] = $this->db->result();
 		  $sql = mysql_query($query);
 		  $elem = @mysql_fetch_assoc($sql);
 		  $product->properties_label[$pk] = $property->label;
 		  $product->properties_value[$pk] = $elem[$property->name];
 		  $product->properties_group[$pk] = $property->category_id;
          //print_r($sql);
     	 }
        }

       //print_r($product);echo "<br>";
      // print_r($group_id);echo "<br>";
      // print_r($group_name);echo "<br>";



    $this->title = $cat->name.' '.$subcat->name.' '.$product->brand.' '.$product->model;


    $this->db->query("SELECT *, DATE_FORMAT(date, '%d.%m.%Y') as date FROM products_comments WHERE product_id = '$id' ORDER BY comment_id DESC");
    $comments = $this->db->results();

    //echo("<br>SELECT *, DATE_FORMAT(date, '%d.%m.%Y') as date FROM products_comments WHERE product_id = '$id' ORDER BY comment_id DESC");

    //print_r($comments);

    $this->smarty->assign('Product', $product);
    $this->smarty->assign('Properties', $properties);
    $this->smarty->assign('group_name', $group_name);
    $this->smarty->assign('Analogs', $analogs);
    $this->smarty->assign('Secret', $secret);
    $this->smarty->assign('Comments', $comments);
    $this->smarty->assign('Fotos', $fotos);
    $this->smarty->assign('CountFotos', @count($fotos));
    $this->smarty->assign('Category', $cat);
    $this->smarty->assign('Subcategory', $subcat);
    $this->smarty->assign('Parent_category', $parent_category);
    $this->body = $this->smarty->fetch('storefront_item.tpl');
  }
}