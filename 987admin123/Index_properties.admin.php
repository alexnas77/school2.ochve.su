<?PHP

require_once('Widget.admin.php');
require_once('PagesNavigation.admin.php');
require_once('placeholder.php');

############################################
# Class NewsLine displays news
############################################
class Index_properties extends Widget
{
  var $pages_navigation;
  var $items_per_page = 100;
  var $uploaddir = '../foto/storefront/';
  function __construct(&$parent)
  {
	parent::__construct($parent);
    $this->add_param('page');
    $this->add_param('category');
    $this->add_param('subcategory');
    $this->pages_navigation = new PagesNavigation($this);
    $this->prepare();
  }


  function get_categories($parent=0)
  {
      $query = sql_placeholder("SELECT * FROM categories WHERE parent=? ORDER BY order_num", $parent);
      $this->db->query($query);
      $categories = $this->db->results();
      foreach($categories as $k=>$category)
      {
        $categories[$k]->subcategories = $this->get_categories($category->category_id);
        $categories[$k]->move_up_get = $this->form_get(array('action'=>'move_up','item_id'=>$category->category_id));
        $categories[$k]->move_down_get = $this->form_get(array('action'=>'move_down','item_id'=>$category->category_id));
        $categories[$k]->edit_get = $this->form_get(array('section'=>'EditProductCategory','item_id'=>$category->category_id));

      }
      return $categories;
  }

   function get_group_properties ()
  {
      $query = "SELECT * FROM group_properties ORDER BY sort";
      $this->db->query($query);
      $group_properties = $this->db->results();
      foreach($group_properties as $k=>$category)
      {
        $group_properties[$k]->move_up_get = $this->form_get(array('action'=>'move_up','item_id'=>$category->category_id));
        $group_properties[$k]->move_down_get = $this->form_get(array('action'=>'move_down','item_id'=>$category->category_id));
        $group_properties[$k]->edit_get = $this->form_get(array('section'=>'EditPropertiesCategory','item_id'=>$category->category_id));

      }
      return $group_properties;
  }

  function prepare() {


  	if(isset($_POST['act']) && $_POST['act']=='delete' && isset($_POST['items']))
  	{
  		$items = $_POST['items'];
  		if(is_array($items))
  		  $items_sql = implode("', '", $items);
  		else
  		  $items_sql = $items;

  		$query = "SELECT name FROM index_properties
                  WHERE index_properties.property_id IN ('$items_sql')";

  		$this->db->query($query);
  		$names = $this->db->results();

  		foreach ($names as $k=>$name){

        $query = "ALTER TABLE products_properties
                  DROP COLUMN `".$names[$k]->name."`";
  		$this->db->query($query);
  		}

  		$query = "DELETE FROM index_properties
                  WHERE index_properties.property_id IN ('$items_sql')";
  		$this->db->query($query);

  		$query = "DELETE FROM category_properties
                  WHERE property_id IN ('$items_sql')";
  		$this->db->query($query);

  		$get = $this->form_get(array());
 		header("Location: index.php$get");
 	}


    if(isset($_POST['act']) && $_POST['act']=='enable' && isset($_POST['SUBMIT']))
    {
  		$current_category_id = $this->param('category');

   		 if ($current_category_id != '')
  		  {$current_subcategory_id = $this->param('subcategory');
  	 	 }

      $enabled = $_POST['enabled'];
  		if(is_array($enabled))
  		  {$items_sql = " IN ('".implode("', '", $enabled)."')";
  		   $items_sql2 = " NOT IN ('".implode("', '", $enabled)."')";}
  		else
  		  {$items_sql = " IN ('".$enabled."')";
  		  $items_sql2 = " NOT IN ('".$enabled."')";}

  		if(empty($enabled))
  		{$items_sql = ">0";
  		  $items_sql2 = "<0";}

        if (!empty($current_category_id) and empty($current_subcategory_id)) {
          $query = "DELETE FROM category_properties
                  WHERE category_id = $current_category_id ";
            $this->db->query($query);
          //echo $query.";";

         // print_r($enabled);

         if($enabled){

     		$subcategories = $this->get_categories($current_category_id);

     		$subcats = array();

    	 	foreach ($subcategories as $k=>$subcategory){
     		  $subcats[$k] = $subcategory->category_id;
     		}

     		$items_sq = " IN ('".implode("', '", $subcats)."')";

           $query = "DELETE FROM category_properties
                  WHERE category_id$items_sq AND property_id$items_sql";
            $this->db->query($query);

           //echo $query.";";

           //print_r($subcategories);

          foreach ($enabled as $id=>$enable){
     	   $query = sql_placeholder("INSERT INTO `category_properties` (`category_id`, `property_id`) VALUES (?, ?)",
  			                          $current_category_id,
  			                          $enable);

  			$this->db->query($query);

           // echo $query.";";
     	  }
         }
        }
        elseif(!empty($current_subcategory_id)){

           $query = "DELETE FROM category_properties
                  WHERE category_id = $current_subcategory_id";
            $this->db->query($query);

           //  echo $query.";";

          if($enabled){

          foreach ($enabled as $id=>$enable){

     	   $query = sql_placeholder("INSERT INTO `category_properties` (`category_id`, `property_id`) VALUES (?, ?)",
  			                          $current_subcategory_id,
  			                          $enable);

  			$this->db->query($query);

            // echo $query.";";
     	  }

         }        }
        else {

        if(empty($enabled))
  		{$items_sql = "<0";
  		  $items_sql2 = ">0";}
        $query = "UPDATE index_properties SET enabled = 'true' WHERE property_id$items_sql";

  		$this->db->query($query);
  		$query = "UPDATE index_properties SET enabled = 'false' WHERE property_id$items_sql2";
        $this->db->query($query);
        }

     }

     if(isset($_POST['act']) && $_POST['act']=='enable' && isset($_POST['SUBMIT'])){
       $current_category_id = $this->param('category');

   		 if ($current_category_id != '')
  		  {$current_subcategory_id = $this->param('subcategory');
  	 	 }

       $show = $_POST['show'];

  		if(is_array($show))
  		  {$items_sql = " IN ('".implode("', '", $show)."')";
  		   $items_sql2 = " NOT IN ('".implode("', '", $show)."')";}
  		else
  		  {$items_sql = " IN ('".$show."')";
  		  $items_sql2 = " NOT IN ('".$show."')";}

  		if(empty($show))
  		{$items_sql = "<0";
  		  $items_sql2 = ">0";}

  		if (!empty($current_category_id) and empty($current_subcategory_id)) {



          $subcategories = $this->get_categories($current_category_id);

     		$subcats = array();

    	 	foreach ($subcategories as $k=>$subcategory){

     		  $subcats[$k] = $subcategory->category_id;

     		}

     		$items_sq = " IN ('".implode("', '", $subcats)."')";

           $query = "UPDATE `category_properties` SET `show` = 'true' WHERE category_id$items_sq AND property_id$items_sql";
            $this->db->query($query);
             //echo $query.";";
           //$query = "UPDATE `category_properties` SET `show` = 'false' WHERE category_id$items_sq AND property_id$items_sql2";
           // $this->db->query($query);
             //echo $query.";";



     	   $query = sql_placeholder("UPDATE `category_properties` SET `show` = 'true' WHERE `category_id` = ? AND property_id$items_sql",
  			                                     $current_category_id);
           $this->db->query($query);
           //echo $query.";";
           $query = sql_placeholder("UPDATE `category_properties` SET `show` = 'false' WHERE `category_id` = ? AND property_id$items_sql2",
  			                                     $current_category_id);
           $this->db->query($query);
           //echo $query.";";


        }
        elseif(!empty($current_subcategory_id)){



     		 $query = sql_placeholder("UPDATE `category_properties` SET `show` = 'true' WHERE `category_id` = ? AND property_id$items_sql",
                                                      $current_subcategory_id);
  			$this->db->query($query);
  		//	echo $query.";";
  			$query = sql_placeholder("UPDATE `category_properties` SET `show` = 'false' WHERE `category_id` = ? AND property_id$items_sql2",
                                                      $current_subcategory_id);
     	   $this->db->query($query);
     	   //echo $query.";";




        }
        else {
      	  if(empty($show))
  			{$items_sql = "<0";
  		  $items_sql2 = ">0";}

  		$query = "UPDATE `index_properties` SET `show` = 'true' WHERE property_id$items_sql";

  		$this->db->query($query);
  		//echo $query.";";
  		$query = "UPDATE `index_properties` SET `show` = 'false' WHERE property_id$items_sql2";
        $this->db->query($query);
        //echo $query.";";
        }
  		$get = $this->form_get(array());
 		header("Location: index.php$get");
    }
    if(isset($_GET['action']) and $_GET['action']=='move_down')
  	{
  		$property_id = $_GET['item_id'];
  		$this->db->query("SELECT @id:=s1.property_id
  		                  FROM index_properties s1, index_properties s2
  		                  WHERE s1.sort>s2.sort
  		                  AND s1.category_id = s2.category_id
  		                  AND s2.property_id = '$property_id'
  		                  ORDER BY s1.property_id ASC
  		                  LIMIT 1");
  		$this->db->query("UPDATE index_properties s1, index_properties s2
  		                  SET s1.sort = (@a:=s1.sort)*0+s2.sort, s2.sort = @a
  		                  WHERE s1.property_id = '$property_id'
  		                  AND s1.category_id = s2.category_id
  		                  AND s2.property_id = @id");
  		$get = $this->form_get(array());
  		header("Location: index.php$get");
  	}

 	if(isset($_GET['action']) and $_GET['action']=='move_up')
  	{
  		$property_id = $_GET['item_id'];
  		$this->db->query("SELECT @id:=s1.property_id
  		                  FROM index_properties s1, index_properties s2
  		                  WHERE s1.sort<s2.sort
  		                  AND s1.category_id = s2.category_id
  		                  AND s2.property_id = '$property_id'
  		                  ORDER BY s1.property_id DESC
  		                  LIMIT 1");
  		$this->db->query("UPDATE index_properties s1, index_properties s2
  		                  SET s1.sort = (@a:=s1.sort)*0+s2.sort, s2.sort = @a
  		                  WHERE s1.property_id = '$property_id'
  		                  AND s1.category_id = s2.category_id
  		                  AND s2.property_id = @id");
  		$get = $this->form_get(array());
  		header("Location: index.php$get");
  	}

  }

  function fetch()
  {
    $this->title = $this->lang->PROPERTIES;
  	$current_page = $this->param('page');

    $this->db->query("SELECT * FROM categories WHERE parent=0 ORDER BY order_num");
    $categories = $this->db->results();
  	$current_category_id = $this->param('category');

        $this->db->query("SELECT * FROM categories WHERE category_id = '$current_category_id'");
        $current_category = $this->db->result();


  	foreach($categories as $k=>$category)
  	{
  		  $categories[$k]->url = $this->form_get(array('category'=>$category->category_id, 'subcategory'=>'', 'page'=>''));
  	}

    $this->db->query("SELECT * FROM categories WHERE parent='$current_category_id' ORDER BY order_num");
    if ($current_category_id != '')
    { $subcategories = $this->db->results();
    	$current_subcategory_id = $this->param('subcategory');

        	$this->db->query("SELECT * FROM categories WHERE category_id = '$current_subcategory_id' ORDER BY order_num");
        	$CurrentSubcategory = $this->db->result();

 		foreach($subcategories as $k=>$subcategory)
  		{
  		  	$subcategories[$k]->url = $this->form_get(array('category'=>$current_category_id,'subcategory'=>$subcategory->category_id, 'page'=>''));
  		}
    }

     $category_filter = '';
    if($current_category_id)
      $category_filter = "WHERE index_properties.enabled = 'true'";
  	$start_item = $current_page*$this->items_per_page;
    $query = sql_placeholder("SELECT SQL_CALC_FOUND_ROWS *
    				  FROM index_properties
    				  $category_filter
    				  ORDER BY category_id, sort
    				  LIMIT ? ,?", $start_item, $this->items_per_page);

    $this->db->query($query);

  	$items = $this->db->results();

  	$this->db->query("SELECT FOUND_ROWS() as count");
    $pages_num = $this->db->result();
    $pages_num = $pages_num->count/$this->items_per_page;

  	$group_properties = $this->get_group_properties();


      $group_name = array();

      foreach ($group_properties as $id=>$property){
        $group_name[$property->category_id]=$property->name;
      }

  	 if (!empty($current_category_id) and empty($current_subcategory_id)) {
            $query = sql_placeholder("SELECT  *
    				  FROM `category_properties` WHERE category_id = ?",
  			                          $current_category_id);


  			$this->db->query($query);

            $properties = $this->db->results();

            $props = array();

            foreach ($properties as $id=>$property){
            $props[] = $property->property_id;
            }

            $shown = array();

            foreach ($properties as $id=>$property){
            $shown[$property->property_id] = $property->show;
            }

             foreach ($items as $id=>$item){

             if(in_array($items[$id]->property_id,$props))
             $items[$id]->enabled = 'true';
             else
             $items[$id]->enabled = 'false';

             if($shown[$item->property_id] == 'true')
             $items[$id]->show = 'true';
             else
             $items[$id]->show = 'false';
             }


           // print_r($shown);
           // print_r($items);
     }
     elseif(!empty($current_subcategory_id)){


            $query = sql_placeholder("SELECT  *
    				  FROM `category_properties` WHERE category_id = ?",
  			                          $current_category_id);


  			$this->db->query($query);

            $properties = $this->db->results();

            $props = array();   $shown = array();

            foreach ($properties as $id=>$property){
            $props[] = $property->property_id;
            $shown[] = $property->show;
            }

            foreach ($items as $id=>$item){

             if(in_array($items[$id]->property_id,$props))
             $items[$id]->hide = 'true';
             else
             $items[$id]->hide = 'false';
             if($shown[$item->property_id] == 'true')
             $items[$id]->hide2 = 'true';
             else
             $items[$id]->hide2 = 'false';
             }

              $query = sql_placeholder("SELECT  *
    				  FROM `category_properties` WHERE category_id = ?",
  			                          $current_subcategory_id);


  			$this->db->query($query);

            $properties = $this->db->results();

            $props = array();

            foreach ($properties as $id=>$property){
            $props[] = $property->property_id;
            }

            $shown = array();

            foreach ($properties as $id=>$property){
            $shown[$property->property_id] = $property->show;
            }

             foreach ($items as $id=>$item){

             if(in_array($items[$id]->property_id,$props))
             $items[$id]->enabled = 'true';
             else
             $items[$id]->enabled = 'false';

             if($shown[$item->property_id] == 'true')
             $items[$id]->show = 'true';
             else
             $items[$id]->show = 'false';
             }
          //  print_r($shown);
          //  print_r($items);

     }
  //   else{    // print_r($shown);
         //   print_r($items);   //  }



    if($items)
    foreach($items as $key=>$item)
    {
       $items[$key]->edit_get = $this->form_get(array('section'=>'Edit_Index_property','item_id'=>$item->property_id));
       $items[$key]->move_up_get = $this->form_get(array('action'=>'move_up','item_id'=>$item->property_id));
       $items[$key]->move_down_get = $this->form_get(array('action'=>'move_down','item_id'=>$item->property_id));
    }
    //print_r($group_name);

  	$this->pages_navigation->pages_num = $pages_num;
  	$this->pages_navigation->fetch();
 	$this->smarty->assign('Items', $items);
 	$this->smarty->assign('Subcategories', $subcategories);
 	$this->smarty->assign('Categories', $categories);
 	$this->smarty->assign('group_id', $group_id);
    $this->smarty->assign('group_name', $group_name);
 	$this->smarty->assign('ErrorMSG', $this->error_msg);
 	$this->smarty->assign('CurrentCategory', $current_category);
 	$this->smarty->assign('CurrentSubcategory', $CurrentSubcategory);
  	$this->smarty->assign('PagesNavigation', $this->pages_navigation->body);
  	$this->smarty->assign('CreatePropertyURL', $this->form_get(array('section'=>'Edit_Index_property')));
    $this->smarty->assign('Lang', $this->lang);
	$this->body = $this->smarty->fetch('index_properties.tpl');
  }
}

############################################
# Class EditProduct - edit the static section
############################################
class Edit_Index_property extends Widget
{
  var $item;
  var $pages_navigation;
  var $items_per_page = 30;
  var $types = array('int', 'float', 'set', 'text');
  function __construct(&$parent)
  {
    parent::__construct($parent);
    //$this->add_param('item_id');
    $this->add_param('page');
    $this->add_param('category');
    $this->add_param('subcategory');
    $this->pages_navigation = new PagesNavigation($this);
    $this->prepare();
  }

  function get_property($id)
  {
  	  $query = sql_placeholder('SELECT * FROM index_properties WHERE property_id=?', $id);
  	  $this->db->query($query);
 	  $property = $this->db->result();
      /*$query="SELECT * FROM category_properties WHERE property_id = '$id'";
      $this->db->query($query);
      $subcategories = $this->db->results();
      $property->$subcategories = array();
      if(!empty($subcategories))
      foreach($subcategories as $key=>$subcategory)
      {
        $property->$subcategories[$key] = $subcategories->category_id;
      }  */
      return $property;
  }


  function prepare()
  {
  	$this->item->property_id = $this->param('item_id');
    $this->item->category_id = $this->param('category');
    $this->item->subcategory_id = $this->param('subcategory');


    //$this->item->old_name = $_SESSION['last_property']->name;
    //echo("old_name = ".$this->item->old_name);

     if (empty($this->item->property_id))
        {
        $this->item->name = !isset($_POST['name']) ? isset($_SESSION['last_property']->name) ?
                            $_SESSION['last_property']->name : '' : trim($_POST['name']);
        $this->item->group_id = !isset($_POST['category_id']) ? isset($_SESSION['last_property']->group_id) ?
                            $_SESSION['last_property']->group_id : '' : trim($_POST['category_id']);
  		$this->item->label = !isset($_POST['label']) ? isset($_SESSION['last_property']->label) ?
                            $_SESSION['last_property']->label : '' : trim($_POST['label']);
  		$this->item->type = !isset($_POST['type']) ? isset($_SESSION['last_property']->type) ?
                            $_SESSION['last_property']->type : '' : trim($_POST['type']);
        $this->item->value = !isset($_POST['value']) ? isset($_SESSION['last_property']->value) ?
                            $_SESSION['last_property']->value : '' : trim($_POST['value']);
  		$this->item->default = !isset($_POST['default']) ? isset($_SESSION['last_property']->default) ?
                            $_SESSION['last_property']->default : '' : trim($_POST['default']);

  		### Save property to session
        $_SESSION['last_property'] = $this->item;
        ###

      	}
        else
        {

     	   if(isset($_POST['name'])){$this->item->name =trim($_POST['name']);}
        $this->item->group_id = isset($_POST['category_id']) ? $_POST['category_id'] : $this->item->group_id;
  		$this->item->label = isset($_POST['label']) ? trim($_POST['label']) : $this->item->label;
  		$this->item->type = isset($_POST['type']) ? $_POST['type'] : $this->item->type;
  		$this->item->value = isset($_POST['value']) ? $_POST['value']: $this->item->value;
  		$this->item->default = isset($_POST['default']) ? trim($_POST['default']) : $this->item->default;


        }



      //  echo"session = "; print_r($_SESSION['old_name']);echo"<br>";
       // echo"item = ";print_r($this->item);

  	if(isset($_POST['SUBMIT'])){


  		if(empty($this->item->name))
  		  $this->error_msg = $this->lang->ENTER_NAME;
  		elseif(empty($this->item->label))
  		  $this->error_msg = $this->lang->ENTER_LABEL;
        else
  		{
  		    if(empty($this->error_msg))
 			{
                   $success=false;

  				if(empty($this->item->property_id))
  				{
                      switch ($this->item->type) {
      				    case 'int':

     			        $this->item->value_out = ($this->item->value == '') ? '(11)' : "(".$this->item->value.")";
     			        $this->item->value = ($this->item->value == '') ? 11 : $this->item->value;

  					    $this->item->default = ($this->item->default == '') ? 0 : $this->item->default;
  						$default = 'DEFAULT ';

      			      break;
      				    case 'float':

       				    $this->item->value_out = ($this->item->value == '') ? '(8,2)' : "(".$this->item->value.")";
       				    $this->item->value = ($this->item->value == '') ? '8,2' : $this->item->value;

  						$this->item->default = ($this->item->default == '') ? '0.00' : $this->item->default;
                        $default = 'DEFAULT ';

      			      break;
      				    case 'set':

       				    $this->item->value_out = ($this->item->value == '' and $this->item->default == '') ? "('Да','Нет')" : "(".$this->item->value.")";
       				    if($this->item->value == '' and $this->item->default == ''){       				    	$this->item->value = "'Да','Нет'";
       				    	$this->item->default = "'Нет'";       				    }


                        $default = 'DEFAULT ';

      				      break;
      				    case 'text':

       				    $this->item->value_out = '';
       				    $this->item->value = '';

  						$this->item->default = '';
  						$default = '';

       				     break;
      				  }

                   $query = "ALTER TABLE products_properties
                   ADD COLUMN `".$this->item->name."` ".$this->item->type."".$this->item->value_out."
                   NULL ".$default."".$this->item->default."";
  		           $this->db->query($query);

  		           //echo $query."<br />\n";

  		            if(empty($this->db->error_msg))	$success=true;
  		            else $this->error_msg = $this->db->error_msg;

  		          if($success == true){

  			 	   $query = sql_placeholder("INSERT INTO `index_properties` (`name` ,`category_id` ,`label` ,`type` , `value`,`default` ) VALUES (?, ?, ?, ?, ?, ?)",
  			                          $this->item->name,
  			                          $this->item->group_id,
  			                          $this->item->label,
  			                          $this->item->type,
  			                          $this->item->value,
  			                          $this->item->default);

	  				$this->db->query($query);
                    $this->item->property_id = $this->db->insert_id();
  		    	    $query = sql_placeholder('UPDATE index_properties SET sort=property_id WHERE property_id=?',
  			                          $this->item->property_id);
  			        $this->db->query($query);
                  //echo $query."<br />\n";
                   }

                   if($success=true){

	  				unset($_SESSION['last_property']);
	  				$get = $this->form_get(array('section'=>'Index_properties', 'page'=>'', 'category'=>$this->item->category_id, 'subcategory'=>$this->item->subcategory_id));
  		    		header("Location: index.php$get");

                    }

  				}
  				else
  				{
                     switch ($this->item->type) {
      				    case 'int':

     			        $this->item->value_out = ($this->item->value == '') ? '(11)' : "(".$this->item->value.")";
     			        $this->item->value = ($this->item->value == '') ? 11 : $this->item->value;

  					    $this->item->default = ($this->item->default == '') ? 0 : $this->item->default;
  						$default = 'DEFAULT ';

      			      break;
      				    case 'float':

       				    $this->item->value_out = ($this->item->value == '') ? '(8,2)' : "(".$this->item->value.")";
       				    $this->item->value = ($this->item->value == '') ? '8,2' : $this->item->value;

  						$this->item->default = ($this->item->default == '') ? 0 : $this->item->default;
                        $default = 'DEFAULT ';

      			      break;
      				    case 'set':

       				    $this->item->value_out = ($this->item->value == '' and $this->item->default == '') ? "('Да','Нет')" : "(".$this->item->value.")";
  						if($this->item->value == '' and $this->item->default == ''){
       				    	$this->item->value = "'Да','Нет'";
       				    	$this->item->default = "'Нет'";
       				    }

                        $default = 'DEFAULT ';

      				      break;
      				    case 'text':

       				    $this->item->value_out = '';
       				    $this->item->value = '';

  						$this->item->default = '';
  						$default = '';

       				     break;
      				  }

                   $query = sql_placeholder("SELECT name FROM index_properties WHERE property_id=?",$this->item->property_id);
  				   $this->db->query($query);

  				   $ores = $this->db->result();
  				   $old_name = $ores->name;

	  			   $query = "ALTER TABLE products_properties
                   CHANGE COLUMN `".$old_name."` `".$this->item->name."` ".$this->item->type."".$this->item->value_out."
                   NULL ".$default."".$this->item->default."";
  		        //   echo($query."<br>");
  		           $this->db->query($query);

                   if(empty($this->db->error_msg))	$success=true;
  		            else $this->error_msg = $this->db->error_msg;

  		            if($success == true){

	  					$query = sql_placeholder('UPDATE index_properties SET `name`=?, `category_id`=?, `label`=?, `type`=?, `value`=?, `default`=?  WHERE property_id=?',
  					                  $this->item->name,
  					                  $this->item->group_id,
  			                          $this->item->label,
  			                          $this->item->type,
  			                          $this->item->value,
  			                          $this->item->default,
                                      $this->item->property_id);
	  			  		  $this->db->query($query);

	  			    }
                  //  echo $query;

  		           if(empty($this->db->error_msg))	$success=true;
  		            else $this->error_msg = $this->db->error_msg;

	  			    if($success=true){

                     unset($_SESSION['last_property']);
	  				 $get = $this->form_get(array('section'=>'Index_properties', 'page'=>'', 'category'=>$this->item->category_id, 'subcategory'=>$this->item->subcategory_id));
  		    		 header("Location: index.php$get");

	  				}

 				}



  			}


  		}
  	}


  }

  function fetch()
  {

    if ($this->item->property_id)
    {
      $this->item = $this->get_property($this->item->property_id);

      $this->title = $this->lang->EDIT_PROPERTY.' &laquo;'.$this->item->property_id.'&raquo;';
  	}
    else
    {
      $this->title = $this->lang->NEW_PROPERTY;
    }

 	  $this->db->query("SELECT * FROM categories WHERE parent=0 ORDER BY order_num");
 	  $categories = $this->db->results();
 	  foreach($categories as $k=>$category)
 	  {
 	    $this->db->query("SELECT * FROM categories WHERE parent='$category->category_id' ORDER BY order_num");
 	    $subcategories = $this->db->results();
 	    $categories[$k]->subcategories = $subcategories;
 	  }

 	  $group_properties = (new Index_properties($this))->get_group_properties();

 	  $group_id = array();

      foreach ($group_properties as $id=>$property){
        $group_id[] = $property->category_id;
      }

      $group_name = array();

      foreach ($group_properties as $id=>$property){
        $group_name[] = $property->name;
      }



     // print_r($this->item->category_id);
     // print_r($group_id);
     // echo("<br>".$this->item->property_id."<br>".$_GET['item_id']);


 	  $this->smarty->assign('types', $this->types);
      $this->smarty->assign('item', $this->item);
      $this->smarty->assign('group_id', $group_id);
      $this->smarty->assign('group_name', $group_name);
 	  $this->smarty->assign('ErrorMSG', $this->error_msg);
 	  $this->smarty->assign('MaxImageSize', $this->max_image_size*1024);
      $this->smarty->assign('Lang', $this->lang);
      $this->smarty->assign('FotosNum', $this->fotos_num);
 	  $this->body = $this->smarty->fetch('index_properties.tpl');
  }
}




############################################
# Class goodCategories displays a list of products categories
############################################
class Properties_Categories extends Widget
{

  function __construct(&$parent)
  {
    parent::__construct($parent);
    $this->add_param('page');
    $this->prepare();
  }


  function prepare()
  {
  	if(isset($_POST['items']))
  	{
  		$items = $_POST['items'];
  		$items_sql = implode("', '", $items);


  		$query = "SELECT cats.* FROM group_properties cats
  					LEFT JOIN index_properties ON index_properties.category_id=cats.category_id
  					WHERE (index_properties.property_id is not null OR cats.category_id is not null)
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

  		$query = "DELETE cats FROM group_properties cats
  					LEFT JOIN index_properties ON index_properties.category_id=cats.category_id
  					WHERE (index_properties.property_id is null OR cats.category_id is null)
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
  		                  FROM group_properties s1, group_properties s2
  		                  WHERE s1.sort>s2.sort
  		                  AND s2.category_id = '$category_id'
  		                  ORDER BY s1.sort ASC
  		                  LIMIT 1");
  		$this->db->query("UPDATE group_properties s1, group_properties s2
  		                  SET s1.sort = (@a:=s1.sort)*0+s2.sort, s2.sort = @a
  		                  WHERE s1.category_id = '$category_id'
  		                  AND s2.category_id = @id");
  		$get = $this->form_get(array());
  		header("Location: index.php$get");
  	}
 	if(isset($_GET['action']) and $_GET['action']=='move_up')
  	{
  		$category_id = $_GET['item_id'];
  		$this->db->query("SELECT @id:=s1.category_id
  		                  FROM group_properties s1, group_properties s2
  		                  WHERE s1.sort<s2.sort
  		                  AND s2.category_id = '$category_id'
  		                  ORDER BY s1.sort DESC
  		                  LIMIT 1");
  		$this->db->query("UPDATE group_properties s1, group_properties s2
  		                  SET s1.sort = (@a:=s1.sort)*0+s2.sort, s2.sort = @a
  		                  WHERE s1.category_id = '$category_id'
  		                  AND s2.category_id = @id");
  		$get = $this->form_get(array());
  		header("Location: index.php$get");
  	}
  }

  function fetch()
  {


  	$this->title = $this->lang->PROPERTIES_CATEGORIES;

    $group_properties = (new Index_properties($this))->get_group_properties();

    //print_r($group_properties);

 	$this->smarty->assign('Group_properties', $group_properties);
  	$this->smarty->assign('ErrorMSG', $this->error_msg);
    $this->smarty->assign('Lang', $this->lang);
 	$this->body = $this->smarty->fetch('group_properties.tpl');
  }
}

############################################
# Class EditGoodCategory - Edit the good gategory
############################################
class EditPropertiesCategory extends Widget
{
  var $category;
  var $max_level = 1;
  function __construct(&$parent)
  {
    parent::__construct($parent);
    $this->add_param('parent');
    $this->prepare();
  }

  function prepare()
  {
    $this->category->category_id = $this->param('item_id');
    if(isset($_POST['name']))
    {
  	    $this->category->name = $_POST['name'];
        $this->category->enabled = 'false';
        $this->category->parent = $_POST['parent'];
        if(isset($_POST['enabled']))
          $this->category->enabled = 'true';

  		if(empty($this->category->name))
  		  $this->error_msg = $this->lang->ENTER_NAME;
  		elseif(!empty($this->category->category_id))
        {
	      $query = sql_placeholder('UPDATE group_properties
  	                    		  SET name=?, enabled=?
  	                    		  WHERE category_id=?',
  	                    		  $this->category->name,
                                  $this->category->enabled,
  	                    		  $this->category->category_id);
  	      $this->db->query($query);
        }
        else
        {
  			$query = sql_placeholder('INSERT INTO group_properties (name, enabled) VALUES(?, ?)',
  			                          $this->category->name,
                                      $this->category->enabled
  			                         );
  			$this->db->query($query);
  			//echo $query;
  			$last_insert_id = $this->db->insert_id();
  			$query = sql_placeholder('UPDATE group_properties SET sort=category_id WHERE category_id=?',
  			                          $last_insert_id
  			                         );
  			$this->db->query($query);
  		}

  	  $this->db->query($query);
  	  $get = $this->form_get(array('section'=>'Properties_Categories'));
  	  header("Location: index.php$get");
  	}
    else
  	{
      $query = sql_placeholder('SELECT *
	                    		FROM group_properties
	                    		WHERE category_id=?',
            		            $this->category->category_id);
 	  $this->db->query($query);
  	  $this->category = $this->db->result();
  	}
  }

  function fetch()
  {
      $group_properties = (new Index_properties($this))->get_group_properties();
      if($this->category->category_id)
    	  $this->title = $this->lang->EDIT_CATEGORY.' &laquo;'.$this->category->name.'&raquo;';
      else
    	  $this->title = $this->lang->NEW_CATEGORY;

 	  $this->smarty->assign('Item', $this->category);
 	  $this->smarty->assign('Group_properties', $group_properties);
 	  $this->smarty->assign('MaxLevel', $max_level);
      $this->smarty->assign('Lang', $this->lang);
 	  $this->body = $this->smarty->fetch('group_property.tpl');
  }
}