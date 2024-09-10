<?PHP

require_once('Widget.admin.php');
require_once('PagesNavigation.admin.php');
require_once('placeholder.php');

############################################
# Class NewsLine displays news
############################################
class Storefront extends Widget
{
  var $pages_navigation;
  var $items_per_page = 30;
  var $uploaddir = '../foto/storefront/';
  function __construct(&$parent)
  {
	parent::__construct($parent);
    $this->add_param('page');
    $this->add_param('category');
    $this->add_param('brand');
    $this->pages_navigation = new PagesNavigation($this);
    $this->prepare();
  }


  function get_categories($parent=0)
  {
      $brand = isset($_SESSION['brand']) ? $_SESSION['brand'] : '';
      if($parent) {
          $brand_filter = strlen($brand) ? "AND categories.brands LIKE '%::".strtolower($brand)."::%'" : "";          
      } else {
          $brand_filter = "";
      }
      $cat_num = intval(array_search($this->domain,array_values($this->subbrands)));
      if($cat_num>0)
      $query = sql_placeholder("SELECT *, title$cat_num as title, description$cat_num as description FROM categories WHERE parent=? $brand_filter ORDER BY enabled DESC, order_num", $parent);
      else
      $query = sql_placeholder("SELECT * FROM categories WHERE parent=? $brand_filter ORDER BY enabled DESC, order_num", $parent);

      $this->db->query($query);

      $categories = $this->db->results();

      foreach($categories as $k=>$category)

      {

        $categories[$k]->subcategories = $this->get_categories($category->category_id);

        $categories[$k]->move_up_get = $this->form_get(array('action'=>'move_up','item_id'=>$category->category_id));

        $categories[$k]->move_down_get = $this->form_get(array('action'=>'move_down','item_id'=>$category->category_id));

        $categories[$k]->delete_get = $this->form_get(array('action'=>'delete','item_id'=>$category->category_id));

        $categories[$k]->edit_get = $this->form_get(array('section'=>'EditProductCategory','item_id'=>$category->category_id));

        $names[0] = $categories[$k]->name;
        $alies = explode(";;",$categories[$k]->alies);
        if(is_array($alies))
        {
        array_push($names,$alies);
        }
        $categories[$k]->name = $names;

        $query = sql_placeholder("SELECT * FROM category_icons WHERE category_id=? ", $category->category_id);

        $this->db->query($query);

        $foto = $this->db->result();



        //$categories[$k]->foto_id = $foto->foto_id;

        $categories[$k]->filename = !empty($foto->filename) ? $foto->filename : "";
        $brands = explode("::",$category->brands);

        if(!in_array(strtolower($brand),$brands) && empty($categories[$k]->subcategories)) unset($categories[$k]);
      }

      return $categories;

  }


  function prepare()
  {
  	$current_brand = $this->param('brand');

  	if(isset($_POST['act']) && $_POST['act']=='delete' && isset($_POST['items']))
  	{
  		$items = $_POST['items'];
  		if(is_array($items))
  		  $items_sql = implode("', '", $items);
  		else
  		  $items_sql = $items;

  		$query = "SELECT * FROM products_fotos
 		          WHERE products_fotos.product_id IN ('$items_sql')";
  		$this->db->query($query);
  		$products_fotos = $this->db->results();
  		foreach($products_fotos as $foto)
  		{
  			$file = $this->uploaddir.$foto->filename;
  			if(is_file($file))
  			    unlink($file);
  		}

  		$query = "DELETE products, products_fotos, products_comments, products_properties  FROM products
                  LEFT JOIN products_fotos ON products.product_id = products_fotos.product_id
                  LEFT JOIN products_comments ON products_comments.product_id = products.product_id
                  LEFT JOIN products_properties ON products_properties.product_id = products.product_id
                  WHERE products.product_id IN ('$items_sql')";
  		$this->db->query($query);
  		$get = $this->form_get(array());
 		header("Location: index.php$get");
 	}
    if(isset($_POST['prices']))
    {
      $prices = $_POST['prices'];
      $currency_ids = $_POST['currency_id'];
      $discounts =  $_POST['discounts'];
      $category_names = $_POST['category_name'];
      $codes = $_POST['codes'];
      foreach($prices as $id=>$price)
      {
        $hit_num = intval(array_search($this->domain,array_values($this->subbrands)));
        $this->db->query("SELECT hit FROM products WHERE product_id='$id'");
        $res = $this->db->result();
        $quantity =  $_POST['quantities'][$id];
        $category_name = $category_names[$id];
        $enabled = 0;
        if(isset($_POST['enabled'][$id]))
          $enabled = 1;
        $hit = $res->hit;
        if(isset($_POST['hits'][$id]))
          $hit{$hit_num} = 1;
        else
          $hit{$hit_num} = 0;
        $currency_id = $currency_ids[$id];
        $discount = $discounts[$id];
        $code = $codes[$id];
        if(isset($category_name))
        $this->db->query("UPDATE products SET price='$price', `currency_id`='$currency_id', `category_name`='$category_name', quantity='$quantity', enabled='$enabled', hit='$hit', discount='$discount', code='$code' WHERE product_id='$id'");
        else
        $this->db->query("UPDATE products SET price='$price', `currency_id`='$currency_id', quantity='$quantity', enabled='$enabled', hit='$hit', discount='$discount', code='$code' WHERE product_id='$id'");

      }
    }
    //print_r($currency_ids);
  	if(isset($_GET['action']) and $_GET['action']=='move_down')
  	{
  		$product_id = $_GET['item_id'];
  		$this->db->query("SELECT @id:=s1.product_id
  		                  FROM products s1, products s2
  		                  WHERE s1.category_id=s2.category_id
  		                  AND s1.brand=s2.brand AND s1.order_num>s2.order_num
  		                  AND s2.product_id = '$product_id'
  		                  ORDER BY s1.order_num ASC
  		                  LIMIT 1");
  		$this->db->query("UPDATE products s1, products s2
  		                  SET s1.order_num = (@a:=s1.order_num)*0+s2.order_num, s2.order_num = @a
  		                  WHERE s1.product_id = '$product_id'
  		                  AND s2.product_id = @id");
  		$get = $this->form_get(array());
  		header("Location: index.php$get");
  	}
 	if(isset($_GET['action']) and $_GET['action']=='move_up')
  	{
  		$product_id = $_GET['item_id'];
  		$this->db->query("SELECT @id:=s1.product_id
  		                  FROM products s1, products s2
  		                  WHERE s1.category_id=s2.category_id
  		                  AND s1.brand=s2.brand AND  s1.order_num<s2.order_num
  		                  AND s2.product_id = '$product_id'
  		                  ORDER BY s1.order_num DESC
  		                  LIMIT 1");
  		$this->db->query("UPDATE products s1, products s2
  		                  SET s1.order_num = (@a:=s1.order_num)*0+s2.order_num, s2.order_num = @a
  		                  WHERE s1.product_id = '$product_id'
  		                  AND s2.product_id = @id");
  		$get = $this->form_get(array());
  		header("Location: index.php$get");
  	}

  }

  function fetch()
  {
    $this->title = $this->lang->PRODUCTS;
  	$current_page = $this->param('page');

  	$current_brand = isset($_SESSION['brand']) ? $_SESSION['brand'] : $this->param('brand');

    $this->db->query("SELECT * FROM currencies ORDER BY currency_id");
    $currencies = $this->db->results();
    $currency_names=array();
    foreach($currencies as $k=>$currency)
    {
    $currency_names[$currency->code] = $currency->name;
    }


      if($current_brand) {
          $brand_filter = strlen($current_brand) ? "AND categories.brands LIKE '%::".strtolower($current_brand)."::%'" : "";          
      } else {
          $brand_filter = "";
      }
    $this->db->query("SELECT * FROM categories WHERE parent=0 $brand_filter ORDER BY enabled DESC, order_num");
    $categories = $this->db->results();
  	$current_category_id = $this->param('category');
  	$start1 = 0;


        $this->db->query("SELECT * FROM categories WHERE category_id = '$current_category_id'");
        $current_category = $this->db->result();

  	foreach($categories as $k=>$category)
  	{
  		  $categories[$k]->url = $this->form_get(array('category'=>$category->category_id, 'brand'=>'', 'page'=>''));
  		$names[0] = $categories[$k]->name;
        $alies = explode(";;",$categories[$k]->alies);
        if(is_array($alies))
        {
        array_push($names,$alies);
        }
        $categories[$k]->name = $names;
        $brands = explode("::",$category->brands);

        if(!in_array(strtolower($current_brand),$brands) && empty($categories[$k]->subcategories)) unset($categories[$k]);
  	}

  	if(empty($current_category_id)) {
  	  $start1 = 1;
  	  $current_category_id = $categories[0]->category_id; }

    $this->db->query("SELECT DISTINCT brand FROM products, categories WHERE products.category_id = categories.category_id AND (categories.category_id='$current_category->category_id' or categories.parent='$current_category->category_id') GROUP BY brand ORDER BY brand");
    $brands = $this->db->results();
 	foreach($brands as $k=>$brand)
  	{
  		  $brands[$k]->url = $this->form_get(array('brand'=>$brand->brand, 'page'=>''));
          if($current_brand && $current_brand != $brand->brand) unset($brands[$k]);
  	}

    $brand_filter = '';
    if($current_brand)
      $brand_filter = "AND products.brand = '$current_brand'";
  	$start_item = $current_page*$this->items_per_page;
    $query = sql_placeholder("SELECT SQL_CALC_FOUND_ROWS products.*, categories.name as category, categories.alies as alies
    				  FROM products, categories categories
    				  WHERE
    				  products.category_id = categories.category_id
    				  AND (categories.category_id = ? OR categories.parent = ?)
                      $brand_filter
    				  ORDER BY category,products.model
    				  LIMIT ? ,?", $current_category->category_id, $current_category->category_id, $start_item, $this->items_per_page);

    $this->db->query($query);
  	$items = $this->db->results();


    $this->db->query("SELECT FOUND_ROWS() as count");
    $pages_num = $this->db->result();
    $pages_num = $pages_num->count/$this->items_per_page;
	$hit_num = intval(array_search($this->domain,array_values($this->subbrands)));
    if($items)
    foreach($items as $key=>$item)
    {
       $items[$key]->edit_get = $this->form_get(array('section'=>'EditProduct','item_id'=>$item->product_id));
       $items[$key]->move_up_get = $this->form_get(array('action'=>'move_up','item_id'=>$item->product_id));
       $items[$key]->move_down_get = $this->form_get(array('action'=>'move_down','item_id'=>$item->product_id));
       $items[$key]->hit = $item->hit{$hit_num};
        $pnames = array();
        $pnames[0] = $items[$key]->category;
        if($items[$key]->alies)
        {
        $palies = explode(";;",$items[$key]->alies);
        if(is_array($palies))
        {
        foreach ($palies as $akey=>$paly)
        {
        if($palies[$akey]=='') unset($palies[$akey]);
        array_push($pnames,$palies[$akey]);
        }

        }
        }
        $items[$key]->category = $pnames;
    }

    if($start1 == 1){
    $get = $this->form_get(array('category'=>$current_category_id, 'brand'=>'', 'page'=>''));
  	header("Location: index.php$get");}

    //echo "<pre>\n";  print_r($items);  echo "</pre>\n";

  	$this->pages_navigation->pages_num = $pages_num;
  	$this->pages_navigation->fetch();
 	$this->smarty->assign('Items', $items);
 	$this->smarty->assign('Brands', $brands);
 	$this->smarty->assign('Categories', $categories);
 	$this->smarty->assign('CurrentCategory', $current_category);
 	$this->smarty->assign('CurrentBrand', $current_brand);
 	$this->smarty->assign('Currency_names', $currency_names);
  	$this->smarty->assign('PagesNavigation', $this->pages_navigation->body);
  	$this->smarty->assign('CreateGoodURL', $this->form_get(array('section'=>'EditProduct', 'category'=>$current_category_id, 'brand'=>$current_brand)));
    $this->smarty->assign('Lang', $this->lang);
	$this->body = $this->smarty->fetch('products.tpl');
  }
}

############################################
# Class EditProduct - edit the static section
############################################
class EditProduct extends Widget
{
  var $item;
  var $accepted_file_types = array('image/pjpeg', 'image/gif', 'image/jpeg', 'image/jpg', 'image/png');
  var $max_image_size = 1024000;
  var $uploaddir = '../foto/storefront/';
  var $fotos_num = 9;
  function __construct(&$parent)
  {
    parent::__construct($parent);
    $this->add_param('page');
    $this->add_param('brand');
    $this->add_param('category');
    $this->item = new stdClass();
    $this->prepare();
  }

  function get_product($id)
  {
  	  $query = sql_placeholder('SELECT * FROM `products` WHERE `product_id`=?', $id);
  	  $this->db->query($query);
 	  $product = $this->db->result();
 	  $hit_num = intval(array_search($this->domain,array_values($this->subbrands)));
 	  $product->hit = $product->hit{$hit_num};
      $query="SELECT * FROM `products_fotos` WHERE `product_id` = '$id'";
      $this->db->query($query);
      $fotos = $this->db->results();
      $product->fotos = array();
      if(!empty($fotos))
      foreach($fotos as $key=>$foto)
      {
        $product->fotos[$foto->foto_id] = $foto->filename;
      }
      foreach ($this->properties as $k=>$property) {

          $query = sql_placeholder('SELECT `'.$property->name.'` FROM `products_properties`  WHERE `product_id`=?', $id);
  		  //$this->db->query($query);
 		  //$product->properties[$k] = $this->db->result();
 		  $sql = mysql_query($query);
 		  $elem = mysql_fetch_assoc($sql);
 		  $product->properties[$k] = $elem[$property->name];
          //print_r($sql);
      }


      return $product;
  }

  function delete_fotos($product_id)
  {
     if(isset($_POST['delete_fotos']))
     {
       $delete_fotos = explode(',', $_POST['delete_fotos']);
       foreach($delete_fotos as $foto_id)
       {
         if($foto_id!='')
         {
             $this->db->query("SELECT * FROM products_fotos WHERE product_id = '$product_id' AND foto_id = '$foto_id'");
             $foto = $this->db->result();
             $this->db->query("DELETE FROM products_fotos WHERE product_id = '$product_id' AND foto_id = '$foto_id'");
             $file = $this->uploaddir.$foto->filename;
  			 if(is_file($file))
  			   unlink($file);
         }
       }
     }
  }

  function add_fotos($product_id)
  {
     if(isset($_FILES['fotos']))
     {
        for($i=0; $i<$this->fotos_num; $i++)
        {
          if(!empty($_FILES['fotos']['name'][$i]))
          {
            if(in_array($_FILES['fotos']['type'][$i],$this->accepted_file_types))
            {
             if($_FILES['fotos']['size'][$i] < $this->max_image_size)
             {
  			   	switch ($_FILES['fotos']['type'][$i]) {
        		case 'image/gif':
                $uploadfile = number_format(10000*microtime(true),0,'.','').".gif";    //$product_id."_$i.gif";
          		break;
        		case 'image/png':
                $uploadfile = number_format(10000*microtime(true),0,'.','').".png";     //$product_id."_$i.png";
          		break;
          		case 'image/pjpeg':
                $uploadfile = number_format(10000*microtime(true),0,'.','').".jpeg";    //$product_id."_$i.jpeg";
          		break;
          		case 'image/jpeg':
                $uploadfile = number_format(10000*microtime(true),0,'.','').".jpeg";    //$product_id."_$i.jpeg";
          		break;
          		case 'image/jpg':
                $uploadfile = number_format(10000*microtime(true),0,'.','').".jpg";     //$product_id."_$i.jpg";
          		break;
      			}
			 if (!move_uploaded_file($_FILES['fotos']['tmp_name'][$i], $this->uploaddir.$uploadfile))
		  		 $this->error_msg .= $this->lang->FILE_UPLOAD_ERROR." file №".($i+1)." error: ".$_FILES['fotos']['error'][$i]."<br />";
             $this->db->query("REPLACE INTO products_fotos (product_id, foto_id, filename) VALUES ('$product_id', '$i', '$uploadfile')");
             }
             else
             $this->error_msg .= "Превышен максимальный размер file №".($i+1)."<br />";
            }
            else
            $this->error_msg .= "Неверный тип file №".($i+1)."<br />";
          }
        }
     }
  }

  function prepare()
  {
  	$this->item->product_id = intval($this->param('item_id'));
  	$query = sql_placeholder('SELECT * FROM `products` WHERE `product_id`=?', $this->item->product_id);
  	$this->db->query($query);
 	$product = $this->db->result();
 	if($product)
 	{
            $this->item->category_product_id = $product->category_id;
            $this->item->category_id = $this->param('category');
        }
 	elseif(isset($_POST['category_id']))
            $this->item->category_product_id = $this->item->category_id = $_POST['category_id'];
  	else
 	{
            $this->item->category_product_id = $this->param('category');
            $this->item->category_id = $this->param('category');
    }


    $this->item->brand = $this->param('brand');

    $query = sql_placeholder("SELECT  `property_id`
    				  FROM `category_properties` WHERE `category_id` IN ( ?, ?)",
  			                          $this->item->category_id,
  			                          $this->item->category_product_id);


  	$this->db->query($query);

    $properties_id = $this->db->results();

    if($properties_id) {

    $props = array();

         foreach ($properties_id as $id=>$property){
          $props[] = $property->property_id;
         }

     $items_sql = " IN ('".implode("', '", $props)."')";

    }
    else{
    $items_sql = "<0";
    }

     $query = "SELECT * FROM `index_properties`
    				  WHERE `enabled` = 'true'
    				  AND `property_id`$items_sql
    				  ORDER BY `category_id`, `sort`";

    $this->db->query($query);

  	$this->properties = $this->db->results();

    //echo $query;
  	//print_r($props);


     if (empty($this->item->product_id))
        {
        $this->item->category_id = !isset($_POST['category_id']) ? isset($_SESSION['last_added_product']->category_id) ?
                            $_SESSION['last_added_product']->category_id : '' : $_POST['category_id'];
  		$this->item->brand = !isset($_POST['brand']) ? isset($_SESSION['last_added_product']->brand) ?
                            $_SESSION['last_added_product']->brand : '' : trim($_POST['brand']);
  		$this->item->model = !isset($_POST['model']) ? isset($_SESSION['last_added_product']->model) ?
                            $_SESSION['last_added_product']->model : '' : trim($_POST['model']);
        $this->item->code = !isset($_POST['code']) ? isset($_SESSION['last_added_product']->code) ?
                            $_SESSION['last_added_product']->code : '' : trim($_POST['code']);
        $this->item->price = !isset($_POST['price']) ? isset($_SESSION['last_added_product']->price) ?
                            $_SESSION['last_added_product']->price : 0.00 : $_POST['price'];
        $this->item->currency_id = !isset($_POST['currency_id']) ? isset($_SESSION['last_added_product']->currency_id) ?
                            $_SESSION['last_added_product']->currency_id : $this->main_currency->code : $_POST['currency_id'];
        $this->item->discount = !isset($_POST['discount']) ? isset($_SESSION['last_added_product']->discount) ?
                            $_SESSION['last_added_product']->discount : 0 : $_POST['discount'];
        $this->item->guarantee = !isset($_POST['guarantee']) ? isset($_SESSION['last_added_product']->guarantee) ?
                            $_SESSION['last_added_product']->guarantee : 0 : $_POST['guarantee'];
        $this->item->delivery = !isset($_POST['delivery']) ? isset($_SESSION['last_added_product']->delivery) ?
                            $_SESSION['last_added_product']->delivery : 0 : $_POST['delivery'];
  		$this->item->description = !isset($_POST['description']) ? isset($_SESSION['last_added_product']->description) ?
                            $_SESSION['last_added_product']->description : '' : $_POST['description'];
        $this->item->body = !isset($_POST['body']) ? isset($_SESSION['last_added_product']->body) ?
                            $_SESSION['last_added_product']->body : '' : $_POST['body'];
        $this->item->defects = !isset($_POST['defects']) ? isset($_SESSION['last_added_product']->defects) ?
                            $_SESSION['last_added_product']->defects : '' : $_POST['defects'];
        $this->item->quantity = !isset($_POST['quantity']) ? isset($_SESSION['last_added_product']->quantity) ?
                            $_SESSION['last_added_product']->quantity : 100 : $_POST['quantity'];



       	 foreach ($this->properties as $k=>$property) {

      	  $this->item->properties[$k] = !isset($_POST[''.$property->name.'']) ? isset($_SESSION['last_added_product']->properties[$k]) ?
                            $_SESSION['last_added_product']->properties[$k] : $property->default : $_POST[''.$property->name.''];


          if ($property->type == 'set') {
          $options = preg_replace("/'/",'',explode(',',$property->value));
          $this->properties[$k]->options = $options;
          //print_r($options);
     	     //echo ($property->value);
          }

     	   }

  		### Save property to session
        $_SESSION['last_added_product'] = $this->item;
        ###

      	}
        else
        {

        $this->item->category_id = isset($_POST['category_id']) ? $_POST['category_id'] : $this->item->category_id;
        $this->item->brand = isset($_POST['brand']) ? trim($_POST['brand']) : $this->item->brand;
        $this->item->model = isset($_POST['model']) ? trim($_POST['model']) : (isset($this->item->model) ? $this->item->model : '');
        $this->item->code = isset($_POST['code']) ? trim($_POST['code']) : (isset($this->item->code) ? $this->item->code : '');
        $this->item->price = isset($_POST['price']) ? $_POST['price']: (isset($this->item->price) ? $this->item->price : 0);
        $this->item->currency_id = isset($_POST['currency_id']) ? $_POST['currency_id']: (isset($this->item->currency_id) ? $this->item->currency_id : $this->main_currency->currency_id);
        $this->item->discount = isset($_POST['discount']) ? $_POST['discount']: (isset($this->item->discount) ? $this->item->discount : 0);
        $this->item->guarantee = isset($_POST['guarantee']) ? $_POST['guarantee']: (isset($this->item->guarantee) ? $this->item->guarantee : '');
        $this->item->delivery = isset($_POST['delivery']) ? $_POST['delivery']: (isset($this->item->delivery) ? $this->item->delivery : 0);
        $this->item->description = isset($_POST['description']) ? $_POST['description'] : (isset($this->item->description) ? $this->item->description : '');
        $this->item->body = isset($_POST['body']) ? $_POST['body'] : (isset($this->item->body) ? $this->item->body : '');
        $this->item->defects = isset($_POST['defects']) ? $_POST['defects'] : (isset($this->item->defects) ? $this->item->defects : '');
        $this->item->quantity = isset($_POST['quantity']) ? $_POST['quantity'] : (isset($this->item->quantity) ? $this->item->quantity : 0);



    	    foreach ($this->properties as $k=>$property) {

   		     $this->item->properties[$k] = isset($_POST[''.$property->name.'']) ? $_POST[''.$property->name.''] : $this->item->properties[$k];


             if ($property->type == 'set') {
     	     $options = preg_replace("/'/",'',explode(',',$property->value));
     	     $this->properties[$k]->options = $options;

     	     //print_r($options);
     	     //echo ($property->value);
    	     }
    	    }
        }

        if(isset($_POST['enabled']))
  		  $this->item->enabled = 1;
        else
  		  $this->item->enabled = 0;

        $hit_num = intval(array_search($this->domain,array_values($this->subbrands)));
        $this->db->query("SELECT hit FROM products WHERE product_id='".$this->item->product_id."'");
        $res = $this->db->result();
        if(!empty($res) && !empty($res->hit)) $this->item->hit = $res->hit; else $this->item->hit = "0000000000";

        if(isset($_POST['hit']))
  		  $this->item->hit{$hit_num} = 1;
        else
  		  $this->item->hit{$hit_num} = 0;
        if(isset($_POST['used']))
  		  {$this->item->used = 1;}
        else
  		  {$this->item->used = 0;
  		   $this->item->defects = '';
  		  }

  	if(isset($_POST['SUBMIT']))	{


  		/*if(empty($this->item->model))
  		  $this->error_msg = $this->lang->ENTER_MODEL;
        else*/
  		{
  			if(empty($this->item->product_id))
  			{

  			    $query = sql_placeholder('INSERT INTO `products` (`category_id`,`model`, `description`, `body`, `enabled`) VALUES (?, ?, ?, ?, ?)',
  			                          $this->item->category_id,
  			                          $this->item->model,
  			                          $this->item->description,
  			                          $this->item->body,
                                      $this->item->enabled);
	  			$this->db->query($query);
	  			//echo $query;
	  			//print_r($this->item);
  			    $this->item->product_id = $this->db->insert_id();
  		    	$query = sql_placeholder('UPDATE `products` SET `order_num`=`product_id` WHERE `product_id`=?',
  			                          $this->item->product_id);
  			    $this->db->query($query);
  			    //echo $query;

  			      $query = sql_placeholder('INSERT INTO `products_properties` (`product_id`, `category_id`) VALUES (?, ?)',
  			                          $this->item->product_id,
  			                          $this->item->category_id);
	  				$this->db->query($query);
  			     foreach ($this->properties as $k=>$property) {

  			        $query = sql_placeholder('UPDATE `products_properties` SET `'.$property->name.'`=\''.$this->item->properties[$k].'\', `category_id`=?
  			                  WHERE `product_id`=?',
  			                          $this->item->category_id,
  			                          $this->item->product_id
  			                          );
  				    $this->db->query($query);

                    //echo ($query.";");
                    if (empty($this->db->error_msg)) $success=true;
  		            else $this->error_msg = $this->db->error_msg;
    	 		 }


  			}
  			else
  			{
 				if(empty($this->error_msg))
 				{
	  				$query = sql_placeholder('UPDATE `products` SET `category_id`=?, `model`=?, `description`=?, `body`=?, `enabled`=? WHERE `product_id`=?',
  					                          $this->item->category_id,
  					                          $this->item->model,
  			                		          $this->item->description,
  			                		          $this->item->body,
  			                        		  $this->item->enabled,
  			                          		  $this->item->product_id);
	  			    $this->db->query($query);
	  			    //echo $query."<br />\n";
	  			    //print_r($this->item);
	  			    foreach ($this->properties as $k=>$property) {

  			        $query = sql_placeholder('UPDATE `products_properties` SET `'.$property->name.'`=\''.$this->item->properties[$k].'\', `category_id`=?
  			                  WHERE `product_id`=?',
  			                          $this->item->category_id,
  			                          $this->item->product_id
  			                          );
  				    $this->db->query($query);

                    //echo $query."<br />\n";
                    if (empty($this->db->error_msg)) $success=true;
  		            else $this->error_msg = $this->db->error_msg;
    	 		 	}
 				}
  			}

            $this->delete_fotos($this->item->product_id);
            $this->add_fotos($this->item->product_id);

  			if(empty($this->error_msg))
  			{
  			    unset($_SESSION['last_added_product']);
  				$get = $this->form_get(array('section'=>'Storefront', 'page'=>'', 'category'=>$category_id, 'brand'=>$this->item->brand));
  		    	header("Location: index.php$get");
  			}
  		}
  	}


  }

  function fetch()
  {
    if ($this->item->product_id)
    {
      $this->item = $this->get_product($this->item->product_id);
      $this->title = $this->lang->EDIT_PRODUCT.' &laquo;'.$this->item->brand.' '.$this->item->model.'&raquo;';
  	}
    else
    {
      $this->title = $this->lang->NEW_PRODUCT;
    }

 	$this->db->query("SELECT * FROM `currencies` ORDER BY `currency_id`");
    $currencies = $this->db->results();
    $currency_names=array();
    foreach($currencies as $k=>$currency)
    {
    $currency_names[$currency->code] = $currency->name;
    }


 	  $this->db->query("SELECT * FROM `categories` WHERE `parent`=0 ORDER BY enabled DESC, `order_num`");
 	  $categories = $this->db->results();
 	  foreach($categories as $k=>$category)
 	  {
 	    $this->db->query("SELECT * FROM `categories` WHERE `parent`='$category->category_id' ORDER BY enabled DESC, `order_num`");
 	    $subcategories = $this->db->results();
 	    $categories[$k]->subcategories = $subcategories;
 	  }

      $group_properties = (new Index_properties($this))->get_group_properties();


      $group_name = array();

      foreach ($group_properties as $id=>$property){
        $group_name[$property->category_id]=$property->name;}
      //echo "<pre>\n";
      //print_r($this->properties);
      //print_r($this->item);
      //echo "</pre>\n";

 	  $this->smarty->assign('Categories', $categories);
 	  $this->smarty->assign('Properties', $this->properties);
 	  $this->smarty->assign('group_name', $group_name);
 	  $this->smarty->assign('Item', $this->item);
 	  $this->smarty->assign('ErrorMSG', $this->error_msg);
 	  $this->smarty->assign('Currency_names', $currency_names);
 	  $this->smarty->assign('MaxImageSize', $this->max_image_size);
      $this->smarty->assign('Lang', $this->lang);
      $this->smarty->assign('FotosNum', $this->fotos_num);
 	  $this->body = $this->smarty->fetch('product.tpl');
  }
}




############################################

# Class goodCategories displays a list of products categories

############################################

class ProductCategories extends Widget

{



  var $uploaddir = '../foto/icons/';



  function __construct(&$parent)

  {

    parent::__construct($parent);

    $this->add_param('page');

    $this->prepare();

  }



  function delete_fotos($category_id)



  {

     //print($_POST['delete_fotos']);





          //echo "456\n";

          //echo "$category_id<br>";



             $this->db->query("SELECT * FROM category_icons WHERE category_id = '".$category_id."'");



             $foto = $this->db->result();



             $this->db->query("DELETE FROM category_icons WHERE category_id = '".$category_id."'");



             $file = $this->uploaddir.$foto->filename;



  			 if(is_file($file))



  			   unlink($file);









  }





  function prepare()

  {

  	if(isset($_POST['items']))

  	{

  		$items = $_POST['items'];

  		$items_sql = implode("', '", $items);





  		$query = "SELECT cats.* FROM categories cats

  					LEFT JOIN products ON products.category_id=cats.category_id

                    LEFT JOIN categories subcats   ON subcats.parent = cats.category_id

  					WHERE (products.product_id is not null OR subcats.category_id is not null)

  					AND (cats.category_id IN ('$items_sql')) GROUP BY cats.category_id";

  		$this->db->query($query);

  		$noemptycats = $this->db->results();

  		if(!empty($noemptycats))

  		{

  		  $this->error_msg = "Следующие категории не могут быть удалены:<BR>";

  		  foreach($noemptycats as $cat)

  		  {

  		    	 $this->error_msg .= "$cat->name<BR>";

  		  }

          $this->error_msg .= "Категория содержит товары или подкатегории.";

  		}



  		$query = "DELETE cats FROM categories cats

  					LEFT JOIN products ON products.category_id=cats.category_id

                    LEFT JOIN categories subcats   ON subcats.parent = cats.category_id

  					WHERE products.product_id is null AND subcats.category_id is null

  					AND cats.category_id IN ('$items_sql')";

  		$this->db->query($query);



  		if(empty($this->error_msg))

  		{

  		  $get = $this->form_get(array());

 		  header("Location: index.php$get");

  		}

 	}

  	if(isset($_GET['action']) and $_GET['action']=='move_down')

  	{

  		$category_id = $_GET['item_id'];

  		$this->db->query("SELECT @id:=s1.category_id

  		                  FROM categories s1, categories s2

  		                  WHERE s1.parent=s2.parent AND s1.order_num>s2.order_num

  		                  AND s2.category_id = '$category_id'

  		                  ORDER BY s1.order_num ASC

  		                  LIMIT 1");

  		$this->db->query("UPDATE categories s1, categories s2

  		                  SET s1.order_num = (@a:=s1.order_num)*0+s2.order_num, s2.order_num = @a

  		                  WHERE s1.category_id = '$category_id'

  		                  AND s2.category_id = @id");

  		$get = $this->form_get(array());

  		header("Location: index.php$get");

  	}

 	if(isset($_GET['action']) and $_GET['action']=='move_up')

  	{

  		$category_id = $_GET['item_id'];

  		$this->db->query("SELECT @id:=s1.category_id

  		                  FROM categories s1, categories s2

  		                  WHERE s1.parent=s2.parent AND  s1.order_num<s2.order_num

  		                  AND s2.category_id = '$category_id'

  		                  ORDER BY s1.order_num DESC

  		                  LIMIT 1");

  		$this->db->query("UPDATE categories s1, categories s2

  		                  SET s1.order_num = (@a:=s1.order_num)*0+s2.order_num, s2.order_num = @a

  		                  WHERE s1.category_id = '$category_id'

  		                  AND s2.category_id = @id");

  		$get = $this->form_get(array());

  		header("Location: index.php$get");

  	}

    //print_r($_POST['delete_fotos']);

     if(isset($_GET['action']) && $_GET['action']=='delete')

  	{

  	$category_id = $_GET['item_id'];

  	$this->delete_fotos($category_id);

  	$get = $this->form_get(array('section'=>'ProductCategories'));

  	header("Location: index.php$get");

  	}

     if(isset($_GET['action']) && $_GET['action']=='id')
  	{

	$this->db->query("UPDATE categories SET order_num = category_id");

  	$get = $this->form_get(array('section'=>'ProductCategories'));

  	header("Location: index.php$get");

  	}

     if(isset($_GET['action']) && $_GET['action']=='name')
  	{
    $this->db->query("SELECT * FROM categories ORDER BY parent, name");

    $categories = $this->db->results();

    foreach ($categories as $cid=>$category)
    {
	$this->db->query("UPDATE categories SET order_num = ".($cid+1)." WHERE category_id = '$category->category_id'");
    }
  	$get = $this->form_get(array('section'=>'ProductCategories'));

  	header("Location: index.php$get");

  	}
  }



  function fetch()

  {

  	$this->title = $this->lang->PRODUCTS_CATEGORIES;



    $categories = (new Storefront($this))->get_categories();







    //print_r($categories);





 	$this->smarty->assign('Categories', $categories);

  	$this->smarty->assign('ErrorMSG', $this->error_msg);

    $this->smarty->assign('Lang', $this->lang);

 	$this->body = $this->smarty->fetch('categories.tpl');

  }

}



############################################

# Class EditGoodCategory - Edit the good gategory

############################################

class EditProductCategory extends Widget

{

  var $category;

  var $max_level = 1;

  var $uploaddir = '../foto/icons/';

  var $accepted_file_types = array('image/pjpeg', 'image/gif', 'image/jpeg', 'image/jpg', 'image/png');
  var $max_image_size = 1024000;

  function __construct(&$parent)

  {

    parent::__construct($parent);

    $this->add_param('parent');
    
    $this->category = new stdClass();

    $this->prepare();

  }



   function delete_fotos($category_id)



  {

     //print($_POST['delete_fotos']);





          //echo "456\n";

          //echo "$foto_id<br>";



             $this->db->query("SELECT * FROM category_icons WHERE category_id = '".$category_id."'");



             $foto = $this->db->result();



             $this->db->query("DELETE FROM category_icons WHERE category_id = '".$category_id."'");



             $file = $this->uploaddir.$foto->filename;



  			 if(is_file($file))



  			   unlink($file);









  }

  function add_fotos()
  {
     if(isset($_FILES['fotos']))
     {

      $category = $_GET['item_id'];
          if(!empty($_FILES['fotos']['name']))
          {
            if(in_array($_FILES['fotos']['type'],$this->accepted_file_types))
            {
             if($_FILES['fotos']['size'] < $this->max_image_size)
             {
  			   	switch ($_FILES['fotos']['type']) {
        		case 'image/gif':
                $uploadfile = number_format(10000*microtime(true),0,'.','').".gif";
          		break;
        		case 'image/png':
                $uploadfile = number_format(10000*microtime(true),0,'.','').".png";
          		break;
          		case 'image/pjpeg':
                $uploadfile = number_format(10000*microtime(true),0,'.','').".jpeg";
          		break;
          		case 'image/jpeg':
                $uploadfile = number_format(10000*microtime(true),0,'.','').".jpeg";
          		break;
          		case 'image/jpg':
                $uploadfile = number_format(10000*microtime(true),0,'.','').".jpg";
          		break;
      			}
			 if (!move_uploaded_file($_FILES['fotos']['tmp_name'], $this->uploaddir.$uploadfile))
		  		 $this->error_msg .= $this->lang->FILE_UPLOAD_ERROR." error: ".$_FILES['fotos']['error']."<br />";
             $query = "REPLACE INTO category_icons (category_id, filename ) VALUES ('$category','$uploadfile')";
             $this->db->query($query);
             }
             else
             $this->error_msg .= "Превышен максимальный размер<br />";
            }
            else
            $this->error_msg .= "Неверный тип<br />";
          }

     }
  }




  function prepare()

  {
      $cat_num = intval(array_search($this->domain,array_values($this->subbrands)));

    $this->category->category_id = $this->param('item_id');

    $action = $this->param('action');

    if(isset($action) && $action == 'delete'){

    	 $this->delete_fotos($this->category->category_id);

    	 $get = $this->form_get(array('section'=>'EditProductCategory','item_id'=>$this->category->category_id));

         header("Location: index.php$get");

    }

    if(isset($_POST['name']))

    {

  	    $this->category->name = $_POST['name'];

        $this->category->enabled = 0;

        $this->category->parent = $_POST['parent'];

        $this->category->title = $_POST['title'];

        $this->category->description = $_POST['description'];
        $this->category->aly = $_POST['aly'];
        if(is_array($this->category->aly))
        {
        foreach ($this->category->aly as $akey=>$elem)
        {
        if($this->category->aly[$akey]=='') unset($this->category->aly[$akey]);
        }
        $this->category->alies = implode(";;",$this->category->aly);
        }
        else
        $this->category->alies = $this->category->aly;
        if(isset($_POST['enabled']))

          $this->category->enabled = 1;



        //print_r($_POST['delete_fotos']);



       //print_r($_POST['act']);

          $this->add_fotos();

  		if(empty($this->category->name))

  		  $this->error_msg = $this->lang->ENTER_NAME;

  		elseif(!empty($this->category->category_id))

        {
          if($cat_num>0)
	      $query = sql_placeholder("UPDATE categories

  	                    		  SET name=?, enabled=?, parent=?, title$cat_num=?, description$cat_num=?, alies=?

  	                    		  WHERE category_id=?",

  	                    		  $this->category->name,

                                  $this->category->enabled,

                                  $this->category->parent,

                                  $this->category->title,

                                  $this->category->description,

                                  $this->category->alies,

  	                    		  $this->category->category_id);

          else
	      $query = sql_placeholder('UPDATE categories

  	                    		  SET name=?, enabled=?, parent=?, title=?, description=?, alies=?

  	                    		  WHERE category_id=?',

  	                    		  $this->category->name,

                                  $this->category->enabled,

                                  $this->category->parent,

                                  $this->category->title,

                                  $this->category->description,

                                  $this->category->alies,

  	                    		  $this->category->category_id);
  	      $this->db->query($query);

        }

        else

        {
          if($cat_num>0)
  			$query = sql_placeholder("INSERT INTO categories (parent, name, enabled, title$cat_num, description$cat_num, alies) VALUES(?, ?, ?, ?, ?, ?)",

  									  $this->category->parent,

  			                          $this->category->name,

                                      $this->category->enabled,

                                      $this->category->title,

                                      $this->category->description,

                                      $this->category->alies

  			                         );
            else
  			$query = sql_placeholder('INSERT INTO categories (parent, name, enabled, title, description, alies) VALUES(?, ?, ?, ?, ?, ?)',

  									  $this->category->parent,

  			                          $this->category->name,

                                      $this->category->enabled,

                                      $this->category->title,

                                      $this->category->description,

                                      $this->category->alies

  			                         );
  			$this->db->query($query);

  			$last_insert_id = $this->db->insert_id();

  			$query = sql_placeholder('UPDATE categories SET order_num=category_id WHERE category_id=?',

  			                          $last_insert_id

  			                         );

  			$this->db->query($query);

  		}



  	  $this->db->query($query);

  	  $get = $this->form_get(array('section'=>'ProductCategories'));





  	  header("Location: index.php$get");

  	}

    else

  	{
      if($cat_num>0)
      $query = sql_placeholder("SELECT *, title$cat_num as title, description$cat_num as description FROM categories WHERE category_id=?",

            		            $this->category->category_id);
      else
      $query = sql_placeholder('SELECT *

	                    		FROM categories

	                    		WHERE category_id=?',

            		            $this->category->category_id);



 	  $this->db->query($query);

  	  $this->category = $this->db->result();


          if(!empty($this->category)) {
              $query = sql_placeholder('SELECT *

                                            FROM category_icons

                                            WHERE category_id=?',

                                        $this->category->category_id);



              $this->db->query($query);

              $this->foto = $this->db->result();  

              //$this->category->foto_id = $this->foto->foto_id;

              $this->category->filename = !empty($this->foto) ? $this->foto->filename : '';

              if($this->category->alies)
              $this->category->aly = explode(";;",$this->category->alies);            
              
          } else {
              $this->foto = new stdClass();
          }

  	  //print_r($this->category);

  	}







  }



  function fetch()

  {

       //print_r($_POST['delete_fotos']);



       $delete_get = !empty($this->category) ? $this->form_get(array('section'=>'EditProductCategory','action'=>'delete','item_id'=>$this->category->category_id)) : '';



       //print_r($_POST['act']);



      $categories = (new Storefront($this))->get_categories();

      if(!empty($this->category) && !empty($this->category->category_id))

    	  $this->title = $this->lang->EDIT_CATEGORY.' &laquo;'.$this->category->name.'&raquo;';

      else

    	  $this->title = $this->lang->NEW_CATEGORY;



 	  $this->smarty->assign('Item', $this->category);

 	  $this->smarty->assign('Categories', $categories);

 	  //$this->smarty->assign('Parent', $parent);

 	  $this->smarty->assign('Delete_get', $delete_get);

 	  //$this->smarty->assign('MaxLevel', $max_level);

      $this->smarty->assign('Lang', $this->lang);

 	  $this->body = $this->smarty->fetch('category.tpl');

  }

}



############################################
# Class goodCategories displays a list of products categories
############################################
class Brands extends Widget
{

  function __construct(&$parent)
  {
    parent::__construct($parent);
    $this->add_param('page');
    $this->prepare();
  }

  function prepare()
  {

 	$this->db->query("SELECT DISTINCT brand FROM products GROUP BY brand ORDER BY brand");
    $brands = $this->db->results();
    //print_r($brands);echo"<br>";
 	foreach($brands as $k=>$brand)
  	{

  	$this->db->query("SELECT * FROM brands WHERE name='$brand->brand'");

    $res = $this->db->result();

    	if(empty($res)){

    	$this->db->query("INSERT INTO brands (name) VALUES ('$brand->brand')");


   		}

  	}
    $this->db->query("SELECT * FROM brands ORDER BY name");
    $brands = $this->db->results();

 	foreach($brands as $k=>$brand)
  	{

  	$this->db->query("SELECT DISTINCT brand FROM products WHERE brand='$brand->name'");

    $res = $this->db->result();

    	if(empty($res)){

    	$this->db->query("DELETE FROM brands WHERE name='$brand->name'");

   		}

  	}

  }

  function fetch()
  {
  	$this->title = $this->lang->BRANDS;

    $this->db->query("SELECT * FROM brands ORDER BY name");
    $brands = $this->db->results();

    foreach($brands as $k=>$brand)
  	{
  		$brands[$k]->edit_get = $this->form_get(array('section'=>'EditBrands','item_id'=>$brand->brand_id));
  	}

 	$this->smarty->assign('Brands', $brands);
  	$this->smarty->assign('ErrorMSG', $this->error_msg);
    $this->smarty->assign('Lang', $this->lang);
 	$this->body = $this->smarty->fetch('brands.tpl');
  }
}

############################################
# Class EditGoodCategory - Edit the good gategory
############################################
class EditBrands extends Widget
{
  var $brand;
  var $max_level = 1;
  function __construct(&$parent)
  {
    parent::__construct($parent);
    $this->add_param('parent');
    $this->prepare();
  }

  function prepare()
  {
    $this->item->brand_id = $this->param('item_id');

    $query = sql_placeholder('SELECT *
	                    		FROM brands
	                    		WHERE brand_id=?',
            		            $this->item->brand_id);
 	  $this->db->query($query);
  	  $this->item = $this->db->result();

    if(isset($_POST['SUBMIT']))
    {
  	    $this->item->name = $_POST['name'];
        $this->item->title = isset($_POST['title']) ? $_POST['title'] : '';
        $this->item->description = isset($_POST['description']) ? $_POST['description'] : '';


  		if(empty($this->item->name))
  		  $this->error_msg = $this->lang->ENTER_NAME;
  		elseif(!empty($this->item->brand_id))
        {
	      $query = sql_placeholder('SELECT * FROM brands
  	                    		  WHERE brand_id=?',
  	                    		  $this->item->brand_id);
  	      $this->db->query($query);

  	      $brand = $this->db->result();

  	      $old_name = $brand->name;

  	       $query = sql_placeholder('UPDATE brands
  	                    		  SET name=?, title=?, description=?
  	                    		  WHERE brand_id=?',
  	                    		  $this->item->name,
                                  $this->item->title,
                                  $this->item->description,
  	                    		  $this->item->brand_id);
  	      $this->db->query($query);

  	      if($old_name != $this->item->name){
  	       $query = sql_placeholder('UPDATE products
  	                    		  SET brand=?
  	                    		  WHERE brand=?',
  	                    		  $this->item->name,
  	                    		  $old_name);
  	      $this->db->query($query);
  	      }
        }


  	  $this->db->query($query);
  	  $get = $this->form_get(array('section'=>'Brands'));
  	  header("Location: index.php$get");
  	}

  }

  function fetch()
  {
      //$categories = Storefront::get_categories();
      if($this->item->brand_id)
    	  $this->title = $this->lang->EDIT_BRAND.' &laquo;'.$this->item->name.'&raquo;';


 	  $this->smarty->assign('Item', $this->item);
 	  $this->smarty->assign('MaxLevel', $max_level);
      $this->smarty->assign('Lang', $this->lang);
 	  $this->body = $this->smarty->fetch('brand.tpl');
  }
}