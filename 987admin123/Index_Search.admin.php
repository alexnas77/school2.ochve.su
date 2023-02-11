<?PHP

require_once('Widget.admin.php');
require_once('PagesNavigation.admin.php');
require_once('placeholder.php');

class Index_Search extends Widget
{

  var $pages_navigation;
  var $items_per_page = 30;
  var $types = array('Тип поиска', '<>=', 'Пределы', 'Значение');
  var $values = array('0', '1', '2', '3');

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
        $categories[$k]->subcategories = Storefront::get_categories($category->category_id);

      }
      return $categories;
  }

   function get_group_properties ()
  {
      $query = "SELECT * FROM group_properties ORDER BY sort";
      $this->db->query($query);
      $group_properties = $this->db->results();
      foreach($group_properties as $k=>$category)

      return $group_properties;
  }

  function prepare() {


     if(isset($_POST['act']) && $_POST['act']=='enable' && isset($_POST['SUBMIT'])){

     //print_r($_POST);

       $current_category_id = $this->param('category');

   		 if ($current_category_id != '')
  		  {$current_subcategory_id = $this->param('subcategory');
  	 	 }

       $search = $_POST['search'];

      // print_r($search);

  		if(is_array($search))
  		  {$items_sql = " IN ('".implode("', '", $search)."')";
  		   $items_sql2 = " NOT IN ('".implode("', '", $search)."')";}
  		else
  		  {$items_sql = " IN ('".$search."')";
  		  $items_sql2 = " NOT IN ('".$search."')";}

  		if(empty($search))
  		{$items_sql = "<0";
  		  $items_sql2 = ">0";}

  		if (!empty($current_category_id) and empty($current_subcategory_id)) {



          $subcategories = $this->get_categories($current_category_id);

     		$subcats = array();

    	 	foreach ($subcategories as $k=>$subcategory){

     		  $subcats[$k] = $subcategory->category_id;

     		}

     		$items_sq = " IN ('".implode("', '", $subcats)."')";

           $query = "UPDATE `category_properties` SET `search` = 'true' WHERE category_id$items_sq AND property_id$items_sql";
            $this->db->query($query);
           //  echo $query.";";
           //$query = "UPDATE `category_properties` SET `show` = 'false' WHERE category_id$items_sq AND property_id$items_sql2";
           // $this->db->query($query);
             //echo $query.";";



     	   $query = sql_placeholder("UPDATE `category_properties` SET `search` = 'true' WHERE `category_id` = ? AND property_id$items_sql",
  			                                     $current_category_id);
           $this->db->query($query);
          // echo $query.";";
           $query = sql_placeholder("UPDATE `category_properties` SET `search` = 'false' WHERE `category_id` = ? AND property_id$items_sql2",
  			                                     $current_category_id);
           $this->db->query($query);
           //echo $query.";";


        }
        elseif(!empty($current_subcategory_id)){



     		 $query = sql_placeholder("UPDATE `category_properties` SET `search` = 'true' WHERE `category_id` = ? AND property_id$items_sql",
                                                      $current_subcategory_id);
  			$this->db->query($query);
  			//echo $query.";";
  			$query = sql_placeholder("UPDATE `category_properties` SET `search` = 'false' WHERE `category_id` = ? AND property_id$items_sql2",
                                                      $current_subcategory_id);
     	   $this->db->query($query);
     	   //echo $query.";";




        }
        else {

      	 /* if(empty($search))
  			{$items_sql = "<0";
  		  $items_sql2 = ">0";} */

  		$query = "UPDATE `index_properties` SET `search` = 'true' WHERE property_id$items_sql";

  		$this->db->query($query);
  		//echo $query.";";
  		$query = "UPDATE `index_properties` SET `search` = 'false' WHERE property_id$items_sql2";
        $this->db->query($query);
        //echo $query.";<br>";
        }
  		$get = $this->form_get(array());
 		header("Location: index.php$get");
    }

     if(isset($_POST['mode']) && $_POST['act']=='enable' && isset($_POST['SUBMIT'])){
       if (!empty($current_category_id) and empty($current_subcategory_id)) {
    	 foreach ($_POST['mode'] as $k=>$mode){

     		$query = sql_placeholder("UPDATE `category_properties` SET `mode` = ? WHERE `category_id` = ? AND property_id = ?",
                                                     $mode, $current_category_id,  $k);
  			$this->db->query($query);
  			//echo $query.";<br>";

     	 }

       }
       elseif(!empty($current_subcategory_id)){
         foreach ($_POST['mode'] as $k=>$mode){

     		$query = sql_placeholder("UPDATE `category_properties` SET `mode` = ? WHERE `category_id` = ? AND property_id = ?",
                                                     $mode, $current_subcategory_id,  $k);
  			$this->db->query($query);
  			//echo $query.";<br>";

     	 }       }
       else {
        foreach ($_POST['mode'] as $k=>$mode){

     		$query = sql_placeholder("UPDATE `index_properties` SET `mode` = ? WHERE property_id = ?",
     												 $mode, $k);

  			$this->db->query($query);
  			//echo $query.";<br>";

     	}
       }
     }



  }


 function fetch()
  {
    $this->title = $this->lang->INDEX_SEARCH;
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
      $category_filter = "WHERE index_properties.enabled = 'true' AND index_properties.search = 'true'";
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
      if($group_properties)
      foreach ($group_properties as $id=>$property){
        $group_name[$property->category_id]=$property->name;
      }

  	 if (!empty($current_category_id) and empty($current_subcategory_id)) {

            $query = sql_placeholder("SELECT  *
    				  FROM `category_properties` WHERE category_id = ?",
  			                          $current_category_id);

          //  echo $query.";";
  			$this->db->query($query);

            $properties = $this->db->results();

            $props = array();

            foreach ($properties as $id=>$property){
            $props[] = $property->property_id;
            }



            $searched = array();    $modes = array();

            foreach ($properties as $id=>$property){
            $searched[$property->property_id] = $property->search;
            $modes[$property->property_id] = $property->mode;
            }

             foreach ($items as $id=>$item){

             if(in_array($items[$id]->property_id,$props))
             $items[$id]->hide = 'false';
             else
             $items[$id]->hide = 'true';

             if($searched[$item->property_id] == 'true')
             $items[$id]->search = 'true';
             else
             $items[$id]->search = 'false';

             $items[$id]->mode = $modes[$item->property_id];
             }


            //print_r($searched);
           // print_r($props);
     }
     elseif(!empty($current_subcategory_id)){


            $query = sql_placeholder("SELECT  *
    				  FROM `category_properties` WHERE category_id = ?",
  			                          $current_category_id);


  			$this->db->query($query);

            $properties = $this->db->results();

             $searched = array();

             $props = array();

            foreach ($properties as $id=>$property){
            $props[] = $property->property_id;
            $searched[$property->property_id] = $property->search;
            }

            foreach ($items as $id=>$item){

             if($searched[$item->property_id] == 'true')
             $items[$id]->hide = 'false';
             else
             $items[$id]->hide = 'true';
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



            $searched = array();    $modes = array();

            foreach ($properties as $id=>$property){
            $searched[$property->property_id] = $property->search;
            $modes[$property->property_id] = $property->mode;
            }

             foreach ($items as $id=>$item){

             if(in_array($items[$id]->property_id,$props))
             $items[$id]->hide = 'false';
             else
             $items[$id]->hide = 'true';

             if($searched[$item->property_id] == 'true')
             $items[$id]->search = 'true';
             else
             $items[$id]->search = 'false';

             $items[$id]->mode = $modes[$item->property_id];
             }

          //  print_r($searched);
           // print_r($props);

     }
  //   else{
    // print_r($shown);
         //   print_r($items);
   //  }

     //print_r($this->types);


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
 	$this->smarty->assign('Types', $this->types);
 	$this->smarty->assign('Values', $this->values);
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
	$this->body = $this->smarty->fetch('index_search.tpl');
  }





}