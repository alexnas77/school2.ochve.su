<?php
set_time_limit(0);
require_once('Database.class.php');
require_once('Database.remote2.php');
require_once('placeholder.php');
require_once('Config.class.php');

  $config=new Config();

  $tunnel = "http://drel.su/tunnel_mysql2.php";
  $dbname = "db_drel2";
  $dbhost = "localhost";
  $dbuser = 'drel';
  $dbpass = 'WUMf2dGMvQPppfaJ';

  $dbname2 = $config->dbname;
  $dbhost2 = $config->dbhost;
  $dbuser2 = $config->dbuser;
  $dbpass2 = $config->dbpass;

  $db=new Database_remote($tunnel,$dbname,$dbhost,$dbuser,$dbpass);
  $db->connect();
  $db->query("SET NAMES cp1251");

  $db2=new Database($dbname2,$dbhost2,$dbuser2,$dbpass2);
  $db2->connect();
  $db2->query("SET NAMES cp1251");

  echo "<h1>Перенос категорий</h1>\n";

    function get_categories($parent=0)
    {
      global $db;
      //$brand = isset($_SESSION['brand']) ? $_SESSION['brand'] : '';
      //$brand = 'Dewalt';
      $catlist = "155";
      //if($parent)
      //$brand_filter = strlen($brand) ? "AND categories.brands LIKE '%::".strtolower($brand)."::%'" : "";
      $brand_filter = "AND categories.category_id IN(".$catlist.")";
      $query = sql_placeholder("SELECT categories.* FROM categories WHERE categories.parent=? AND categories.enabled $brand_filter ORDER BY categories.name", $parent);
      //echo "$query\n";
      $db->query($query);
      $categories = $db->results();
      if($categories)
      foreach($categories as $k=>$category)
      {
        //$brands = explode("::",$category->brands);

        $categories[$k]->subcategories = get_categories($category->category_id);
        //if(!in_array(strtolower($brand),$brands) && empty($categories[$k]->subcategories)) unset($categories[$k]);
      }
      return $categories;
    }

   $categories = get_categories();

  /*$query = sql_placeholder("SELECT * FROM categories ORDER BY category_id");
  $db->query($query);
  $categories = $db->results();*/

  if($_GET['go']=='true')
  {

  $query = sql_placeholder("UPDATE categories SET enabled = '0'");
  //echo "<br />$query<br />\n";
  $db2->query($query);
  	echo "<ol>\n";

  	foreach($categories as $id=>$category)
  	{  		$query = sql_placeholder("SELECT * FROM categories WHERE category_id = ?",
  							 $category->category_id);
  		$db2->query($query);
  		//echo "<br />$query<br />\n";
  		$category2 = $db2->result();
  	   if($db2->num_rows()==0)
  	    {  		echo "<li>Переносится Категория ID = ".$category->category_id." <b>".$category->name."</b><br />\n";
  		echo "в целевую БД \n";
  		$query = sql_placeholder("REPLACE INTO categories (category_id, parent, name, alies, order_num, enabled) VALUES (?, ?, ?, ?, ?, ?)",
  							 $category->category_id,
  							 $category->parent,
  							 $category->name,
  							 $category->alies,
  							 $category->order_num,
  							 $category->enabled);
  		$db2->query($query);
  	   	if($db2->affected_rows()>0)
  	   	echo "<span style='color:Green'> успешно</span><br />\n";
  	   	else
  	   	echo "<span style='color:Red'> неудачно</span><br />\n";
  		//echo "<br />$query<br />\n";
  		}
  		else
  		{  		echo "<li>Обновляется Категория ID = ".$category->category_id." <b>".$category->name."</b><br />\n";
  		echo "в целевой БД \n";
  		$query = sql_placeholder("UPDATE categories SET enabled=? WHERE category_id=?",
  							 $category->enabled,
  							 $category->category_id);
  		$db2->query($query);
  	   	if($db2->affected_rows()>0)
  	   	echo "<span style='color:Green'> успешно</span><br />\n";
  	   	else
  	   	echo "<span style='color:Red'> неудачно</span><br />\n";
  		//echo "<br />$query<br />\n";
   		}
      if($category->subcategories)
  	  foreach($category->subcategories as $id=>$subcategory)
  	  {
  		$query = sql_placeholder("SELECT * FROM categories WHERE category_id = ?",
  							 $subcategory->category_id);
  		$db2->query($query);
  		//echo "<br />$query<br />\n";
  		$category2 = $db2->result();
  	   if($db2->num_rows()==0)
  	    {  		echo "<li>Переносится Категория ID = ".$subcategory->category_id." <b>".$subcategory->name."</b><br />\n";
  		echo "в целевую БД \n";
  		$query = sql_placeholder("REPLACE INTO categories (category_id, parent, name, alies, order_num, enabled) VALUES (?, ?, ?, ?, ?, ?)",
  							 $subcategory->category_id,
  							 $subcategory->parent,
  							 $subcategory->name,
  							 $subcategory->alies,
  							 $subcategory->order_num,
  							 $subcategory->enabled);
  		$db2->query($query);
  	   	if($db2->affected_rows()>0)
  	   	echo "<span style='color:Green'> успешно</span><br />\n";
  	   	else
  	   	echo "<span style='color:Red'> неудачно</span><br />\n";
  		//echo "<br />$query<br />\n";
  		}
  		else
  		{  		echo "<li>Обновляется Категория ID = ".$subcategory->category_id." <b>".$subcategory->name."</b><br />\n";
  		echo "в целевой БД \n";
  		$query = sql_placeholder("UPDATE categories SET enabled=? WHERE category_id=?",
  							 $subcategory->enabled,
  							 $subcategory->category_id);
  		$db2->query($query);
  	   	if($db2->affected_rows()>0)
  	   	echo "<span style='color:Green'> успешно</span><br />\n";
  	   	else
  	   	echo "<span style='color:Red'> неудачно</span><br />\n";
  		//echo "<br />$query<br />\n";
  		}

  	  }
  	}

  	echo "</ol>\n";

  }
  else
  {
  	echo "<ol>\n";

  	foreach($categories as $id=>$category)
  	{  		$query = sql_placeholder("SELECT * FROM categories WHERE category_id = ?",
  							 $category->category_id);
  		$db2->query($query);
  	   if($db2->num_rows()==0)
  	    {
  		echo "<li>Переносится Категория ID = ".$category->category_id." <b>".$category->name."</b><br />\n";
  		echo "в целевую БД<br />\n";

  		}
  		else
  		{
  		echo "<li><span style='color:Red'>Категория ID = ".$category->category_id." <b>".$category->name."</b>  в целевой БД сущетвует</span></li>\n";
  		}
      if($category->subcategories)
   	  foreach($category->subcategories as $id=>$subcategory)
  	  {  		$query = sql_placeholder("SELECT * FROM categories WHERE parent = ? AND name = ? AND alies = ? AND enabled = ?",
  							 $subcategory->parent,
  							 $subcategory->name,
  							 $subcategory->alies,
  							 $subcategory->enabled);
  		$db2->query($query);
  	   if($db2->num_rows()==0)
  	    {
  		echo "<li>Переносится Категория ID = ".$subcategory->category_id." <b>".$subcategory->name."</b><br />\n";
  		echo "в целевую БД<br />\n";

  		}
  		else
  		{
  		echo "<li><span style='color:Red'>Категория ID = ".$subcategory->category_id." <b>".$subcategory->name."</b>  в целевой БД сущетвует</span></li>\n";
  		}

  	  }
  	}

  	echo "</ol>\n";
   echo "<br /><br /><a href=\"?go=true\">Желаете продолжить?</a>\n";  }
?>