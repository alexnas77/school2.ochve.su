<?PHP

require_once('Widget.admin.php');
require_once('PagesNavigation.admin.php');


############################################
# Class NewsLine displays news
############################################
class Links extends Widget
{
  var $pages_navigation;
  var $items_per_page = 10;
  var $uploaddir = '../foto/links/';
  function __construct(&$parent)
  {
    parent::__construct($parent);
    $this->add_param('page');
    $this->add_param('keyword');
    $this->pages_navigation = new PagesNavigation($this);
    $this->prepare();
  }

  function get_categories($parent=0)
  {
      $query = sql_placeholder("SELECT * FROM links_categories WHERE parent=? AND enabled='1' ORDER BY order_num", $parent);
      $this->db->query($query);
      $categories = $this->db->results();
      foreach($categories as $k=>$category)
      {
        $categories[$k]->move_up_get = $this->form_get(array('action'=>'move_up','item_id'=>$category->category_id));
        $categories[$k]->move_down_get = $this->form_get(array('action'=>'move_down','item_id'=>$category->category_id));
        $categories[$k]->edit_get = $this->form_get(array('section'=>'EditLinksCategories','item_id'=>$category->category_id));
        $categories[$k]->subcategories = $this->get_categories($category->category_id);
      }
      return $categories;
  }

  function delete_fotos($category_id)
  {
     //print($_POST['delete_fotos']);
          //echo "456\n";
          //echo "$foto_id<br>";
             $this->db->query("SELECT * FROM links WHERE article_id = '".$category_id."'");
             $foto = $this->db->result();

                $query = "UPDATE links SET image = '' WHERE article_id = '$category_id'";
                $this->db->query($query);
             $file = $this->uploaddir.$foto->image;
  			 if(is_file($file))
  			   unlink($file);
  }

  function prepare()
  {
  	if(isset($_POST['items']))
  	{
  		$items = $_POST['items'];
  		if(is_array($items))
  		  $items_sql = implode("', '", $items);
  		else
  		  $items_sql = $items;
  		$query = "DELETE FROM links
 		          WHERE links.article_id IN ('$items_sql')";
  		$this->db->query($query);
  		$get = $this->form_get(array());
 		header("Location: index.php$get");
 	}
	if($_POST['act']=='active')
	{
		$enabled = $_POST['enabled'];
        $main = $_POST['main'];
      $categories = $_POST['art_cat'];
      $pcategories = $_POST['prod_cat'];
      foreach($categories as $ck=>$category)
      {
		$pcategory = $pcategories[$ck];
		$query = "UPDATE links SET art_cat='$category' WHERE links.article_id = '$ck'";

		$this->db->query($query);
		if(@in_array($ck,$enabled))
		{
		$query = "UPDATE links SET enabled='1' WHERE article_id = '$ck'";
		$this->db->query($query);
		}
		else
		{
		$query = "UPDATE links SET enabled='0' WHERE article_id = '$ck'";
		$this->db->query($query);
		}
		if(@in_array($ck,$main))
		{
		$query = "UPDATE links SET main='1' WHERE article_id = '$ck'";
		$this->db->query($query);
		}
		else
		{
		$query = "UPDATE links SET main='0' WHERE article_id = '$ck'";
		$this->db->query($query);
		}
      }

		$get = $this->form_get(array());
	header("Location: index.php$get");
    }
  	if(isset($_GET['action']) and $_GET['action']=='move_up')
  	{
  		$item_id = $_GET['item_id'];
  		$this->db->query("SELECT @id:=a1.article_id
  		                  FROM links a1, links a2
  		                  WHERE a1.order_num>a2.order_num
  		                  AND a1.art_cat=a2.art_cat
  		                  AND a2.article_id = '$item_id'
  		                  ORDER BY a1.order_num ASC
  		                  LIMIT 1");
  		$this->db->query("UPDATE links a1, links a2
  		                  SET a1.order_num = (@a:=a1.order_num)*0+a2.order_num, a2.order_num = @a
  		                  WHERE a1.article_id = '$item_id'
  		                  AND a2.article_id = @id");
  		$get = $this->form_get(array());
  		header("Location: index.php$get");
  	}
 	if(isset($_GET['action']) and $_GET['action']=='move_down')
  	{
   		$item_id = $_GET['item_id'];
  		$this->db->query("SELECT @id:=a1.article_id
  		                  FROM links a1, links a2
  		                  WHERE a1.order_num<a2.order_num
  		                  AND a1.art_cat=a2.art_cat
  		                  AND a2.article_id = '$item_id'
  		                  ORDER BY a1.order_num DESC
  		                  LIMIT 1");
  		$this->db->query("UPDATE links a1, links a2
  		                  SET a1.order_num = (@a:=a1.order_num)*0+a2.order_num, a2.order_num = @a
  		                  WHERE a1.article_id = '$item_id'
  		                  AND a2.article_id = @id");
  		$get = $this->form_get(array());
  		header("Location: index.php$get");
  	}

    if(isset($_GET['action']) && $_GET['action']=='delete')
  	{
  	$category_id = $_GET['item_id'];
  	$this->delete_fotos($category_id);
  	$get = $this->form_get(array('section'=>'Links'));
  	header("Location: index.php$get");
  	}

  }

  function fetch()
  {
    $this->title = 'Управление статьями';
  	$current_page = $this->param('page');
  	$keyword = $this->param('keyword');
  	if(!$current_page) $current_page=0;
  	$start_item = $current_page*$this->items_per_page;
  	$filter = "";
    if(!empty($keyword))
    $filter = "WHERE path LIKE '%".$keyword."%'";
	$this->art_cats = $this->get_categories();

  	$query = "SELECT SQL_CALC_FOUND_ROWS *
    				  FROM links $filter
    				  ORDER BY art_cat ASC,order_num DESC
    				  LIMIT $start_item ,$this->items_per_page";
    //echo "$query<br />\n";
    $this->db->query($query);
  	$items = $this->db->results();

    $this->db->query("SELECT FOUND_ROWS() as count");
    $pages_num = $this->db->result();
    $pages_num = $pages_num->count/$this->items_per_page;
    //echo "count = ".$pages_num->count."<br />\n";
    //echo "pages_num = $pages_num<br />\n";
    foreach($items as $key=>$item)
    {
       $items[$key]->move_up_get = $this->form_get(array('action'=>'move_up','item_id'=>$item->article_id));
       $items[$key]->move_down_get = $this->form_get(array('action'=>'move_down','item_id'=>$item->article_id));
       $items[$key]->delete_get = $this->form_get(array('action'=>'delete','item_id'=>$item->article_id));
       $items[$key]->edit_get = $this->form_get(array('section'=>'EditLink','item_id'=>$item->article_id));
    }

  	$this->pages_navigation->pages_num = $pages_num;
  	$this->pages_navigation->fetch();
 	$this->smarty->assign('Items', $items);
  	$this->smarty->assign('PagesNavigation', $this->pages_navigation->body);
  	$this->smarty->assign('Lang', $this->lang);
  	$this->smarty->assign('Art_cats', $this->art_cats);
  	$this->smarty->assign('Uploaddir', $this->uploaddir);
 	$this->body = $this->smarty->fetch('links.tpl');
  }
}

############################################
# Class EditServiceSection - edit the static section
############################################
class EditLink extends Widget
{
  var $item;
  var $uploaddir = '../foto/links/';
  var $accepted_file_types = array('image/pjpeg', 'image/gif', 'image/jpeg', 'image/jpg', 'image/png', 'image/x-png');
  var $max_image_size = 1024000;

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
          //echo "$foto_id<br>";
             $this->db->query("SELECT * FROM links WHERE article_id = '".$category_id."'");
             $foto = $this->db->result();

                $query = "UPDATE links SET image = '' WHERE article_id = '$category_id'";
                $this->db->query($query);
             $file = $this->uploaddir.$foto->image;
  			 if(is_file($file))
  			   unlink($file);
  }


  function add_fotos($category)
  {
      //echo "<pre>\n"; print_r($_FILES['fotos']); echo "<pre>\n";
     if(isset($_FILES['fotos']))
     {
      //$category = $_GET[''];
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
        		case 'image/x-png':
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

			 if (!move_uploaded_file($_FILES['fotos']['tmp_name'],dirname(dirname(__FILE__)).'/tmp/'.$uploadfile) ||
			 !$this->resize(dirname(dirname(__FILE__)).'/tmp/'.$uploadfile, $this->uploaddir.$uploadfile,400,0))
		  		 $this->error_msg .= $this->lang->FILE_UPLOAD_ERROR." error: ".$_FILES['fotos']['error']."<br />";
		  		else
		  		{
                $query = "UPDATE links SET image = '$uploadfile' WHERE article_id = '$category'";
                //echo "$query<br />\n";
                $this->db->query($query);
                @unlink(dirname(dirname(__FILE__)).'/tmp/'.$uploadfile);
                }
             }
             else
             $this->error_msg .= "Превышен максимальный размер<br />";
            }
            else
            $this->error_msg .= "Неверный тип<br />";
          }
     else
     {
     $query = "SELECT * FROM links WHERE article_id = '$category'";
     $this->db->query($query);
     $link = $this->db->result();
     if(strpos($link->image,"screen_")===false)
     {
     $image = file_get_contents("http://images.thumbshots.com/image.aspx?cid=48yYStfTsv4%3d&v=1&w=150&url=".urlencode($this->item->path));
//echo "http://images.thumbshots.com/image.aspx?cid=48yYStfTsv4%3d&v=1&w=150&url=".urlencode($this->item->path);
     if($image)
     {
     $uploadfile = "screen_".$category.".jpg";
     file_put_contents($this->uploaddir.$uploadfile,$image);
     $query = "UPDATE links SET image = '$uploadfile' WHERE article_id = '$category'";
     $this->db->query($query);
     }
     }
     }

     }
  }

function rgbtostruct($color) {
 $color = hexdec($color);
 $rgb = array();
    // red
 $rgb['blue'] = $color & 255;
    // green
 $s = ($s << 8)-1;
 $rgb['green'] = ($color>>8) & 255;
    // blue
 $s = ($s << 8)-1;
 $rgb['red'] = ($color>>16) & 255;

 return $rgb;
}

  function resize($src,$dst,$width=0,$height=0)
  {
  $doc_root = dirname(__FILE__);
  $dst = $doc_root.'/'.$dst;
  //echo "$src<br />\n";
  //echo  intval(is_file($src))."<br />";
  //echo "$dst<br />\n";
  if(is_file($src))
  {

  $src_info = getimagesize($src);

  //echo "<pre>\n"; print_r($src_info); echo "<pre>\n";

  if($src_info[2] == IMAGETYPE_JPEG)
  $src_im = imagecreatefromjpeg($src);
  elseif($src_info[2] == IMAGETYPE_GIF)
  $src_im = imagecreatefromgif($src);
  elseif($src_info[2] == IMAGETYPE_PNG)
  $src_im = imagecreatefrompng($src);
  else
  return false;

  $src_x = $src_info[0];
  $src_y = $src_info[1];

  if($height==0) $height =  intval($width*$src_y/$src_x);
  if($width==0) $width =  intval($height*$src_x/$src_y);
  if($height>$src_y)
  {
  $height = $src_y;
  $width =  intval($height*$src_x/$src_y);
  }
  if($width>$src_x)
  {
  $width = $src_x;
  $height =  intval($width*$src_y/$src_x);
  }

  $dst_im = imagecreatetruecolor($width, $height);
  imagesavealpha($dst_im,true);
  $bgcolor = 'FFFFFF';
  $bgcolors = $this->rgbtostruct($bgcolor);
  $bgcolorint = imagecolorallocate($dst_im,$bgcolors['red'],$bgcolors['green'],$bgcolors['blue']);
  imagefilledrectangle($dst_im,0,0,$width,$height,$bgcolorint);/**/

  //imagecopy($dst_im,$src_im,0,0,0,0,$src_x,$src_y);
  imagecopyresampled($dst_im,$src_im,0,0,0,0,$width,$height,$src_x,$src_y);


  switch ($src_info[2]) {

    case IMAGETYPE_GIF:
   imagegif($dst_im,$dst);
   //imagegif($dst_im);
      break;
    case IMAGETYPE_PNG:
   imagepng($dst_im,$dst);
   //imagepng($dst_im);
      break;
    case IMAGETYPE_JPEG:
   imagejpeg($dst_im,$dst,100);
   //imagejpeg($dst_im);
      break;
  }

  imagedestroy($dst_im);

  }
  else
  return false;

  return true;
  }


  function prepare()
  {
  	$item_id = intval($this->param('item_id'));
    $action = $this->param('action');

    if(isset($action) && $action == 'delete'){
    	 $this->delete_fotos($item_id);
    	 $get = $this->form_get(array('section'=>'EditLink','item_id'=>$item_id));
         header("Location: index.php$get");
         $this->db->disconnect();
         exit();
    }
  	if(
  	   isset($_POST['SUBMIT']))
  	{
  		$this->item->art_cat = $_POST['art_cat'];
  		$this->item->title = trim($_POST['title']);
  		$this->item->path = trim($_POST['path']);
  		$this->item->annotation = trim($_POST['annotation']);
  		$this->item->backlink = trim($_POST['backlink']);
  		$this->item->my_link = trim($_POST['my_link']);
  		$this->item->email = trim($_POST['email']);
        if(isset($_POST['enabled']))
  		$this->item->enabled = 1;
  		else
  		$this->item->enabled = 0;
        if(isset($_POST['main']))
  		$this->item->main = 1;
  		else
  		$this->item->main = 0;

  		$query = "SELECT path FROM links_categories WHERE category_id='".$this->item->art_cat."'";
        $this->db->query($query);
        $res = $this->db->result();
        $cat_path = $res->path;

  		$query = "SELECT * FROM links WHERE path='".$this->item->path."'";
        $this->db->query($query);
        $otherpath = $this->db->result();

  		if(empty($this->item->title))
  		  $this->error_msg = $this->lang->ENTER_TITLE;
  		elseif(empty($this->item->path))
  		  $this->error_msg = $this->lang->ENTER_PATH;
  		elseif(empty($this->item->email) || !preg_match("/^[^@]+@[^\.]+\.[0-9a-zA-Z\.]+$/is",$this->item->email))
  		  $this->error_msg = "Неверный формат E-mail";
        else
  		{
  			if(empty($item_id))
            {
             if($otherpath)
  		  	 $this->error_msg = $this->lang->ENTER_OTHER_PATH;
  		  	 else
  		  	 {
  				$query = sql_placeholder('INSERT INTO links (article_id, art_cat, title, path, annotation, backlink, my_link, email, enabled, main) VALUES(NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
  			                          $this->item->art_cat,
  			                          $this->item->title,
  			                          $this->item->path,
  			                          $this->item->annotation,
  			                          $this->item->backlink,
  			                          $this->item->my_link,
  			                          $this->item->email,
  			                          $this->item->enabled,
  			                          $this->item->main);
                $this->db->query($query);
	  			$inserted_id = $this->db->insert_id();
                //echo "$query<br />\n";
  				$query = sql_placeholder('UPDATE links SET order_num=article_id, order_num2=article_id WHERE article_id=?',
  			                          $inserted_id);
  				$this->db->query($query);
                //echo "$query<br />\n";
  				$query = sql_placeholder('SELECT * FROM links WHERE art_cat=? ORDER BY order_num',
  			                          $this->item->art_cat);
  				$this->db->query($query);
	  			$res = $this->db->results();
	  			$count = intval(@count($res));
	  			//if($count>=6)
	  			//{
	  			$new_order_num = $count;
  				/*$query = sql_placeholder('UPDATE links SET order_num=? WHERE article_id=?',
  			                          $inserted_id,
  			                          $res[$new_order_num-1]->article_id);
  				$this->db->query($query);
  				$query = sql_placeholder('UPDATE links SET order_num=? WHERE article_id=?',
  			                          $res[$new_order_num-1]->order_num,
  			                          $inserted_id);*/
  				$this->db->query($query);
  				//}
                //echo "$query<br />\n";
                $letter="Здравствуйте\n\nВ каталог добавлена ссылка <a href=\"".$this->item->path."\">".$this->item->title."</a>\n размещена по адресу http://".$_SERVER['HTTP_HOST']."/resourses/".$cat_path."_p".floor($count/5);
                $subj = "LINKER - добавлена ссылка";
                @mail($this->item->email.',stroiseo@mail.ru',$subj,$letter,
              "From: <robot@".$_SERVER['HTTP_HOST'].">\n".
              "MIME-Version: 1.0\n".
              "Content-Type: text/plain; charset=\"windows-1251\"\n".
              "Content-Transfer-Encoding: 8bit\n".
              "X-Priority: 3\n".
              "X-Mailer: mailer_name");
             }
            }
  			else
            {
  			 if($otherpath && $item_id!=$otherpath->article_id)
  		  	 $this->error_msg = $this->lang->ENTER_OTHER_PATH;
  		  	 else
  		  	 {
  				$query = sql_placeholder('UPDATE links SET art_cat=?, title=?, path=?, annotation=?, backlink=?, my_link=?, email=?, enabled=?, main=? WHERE article_id=?',
  			                          $this->item->art_cat,
  			                          $this->item->title,
  			                          $this->item->path,
  			                          $this->item->annotation,
  			                          $this->item->backlink,
  			                          $this->item->my_link,
  			                          $this->item->email,
  			                          $this->item->enabled,
  			                          $this->item->main,
  			                          $item_id);
                $this->db->query($query);
             }
  		    }
  		  	if(!$item_id) $item_id = $inserted_id;
            $this->add_fotos($item_id);
                if(empty($this->error_msg))
                {
 			    $get = $this->form_get(array('section'=>'Links'));
  		        header("Location: index.php$get");
  		        }
  		}
  	}

  	elseif (!empty($item_id))
  	{
  	  $query = sql_placeholder('SELECT * FROM links WHERE article_id=?', $item_id);
  	  $this->db->query($query);
  	  $this->item = $this->db->result();
  	}
  }

  function fetch()
  {
  	  $delete_get = $this->form_get(array('section'=>'EditLink','action'=>'delete','item_id'=>$this->item->article_id));
  	  if(empty($this->item->article_id))
  	    $this->title = $this->lang->NEW_ARTICLE;
  	  else
  	    $this->title = $this->lang->EDIT_ARTICLE;

	  $this->art_cats = (new Links($this))->get_categories();

 	  $this->smarty->assign('Item', $this->item);
 	  $this->smarty->assign('ErrorMSG', $this->error_msg);
      $this->smarty->assign('Lang', $this->lang);
	  $this->smarty->assign('Art_cats', $this->art_cats);
 	  $this->smarty->assign('Delete_get', $delete_get);
  	  $this->smarty->assign('Uploaddir', $this->uploaddir);
 	  $this->body = $this->smarty->fetch('link.tpl');
  }
}

############################################
# Class goodCategories displays a list of products categories
############################################
class LinksCategories extends Widget
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


  		$query = "SELECT cats.* FROM links_categories cats
  					LEFT JOIN links ON links.art_cat=cats.category_id
                    LEFT JOIN links_categories subcats   ON subcats.parent = cats.category_id
  					WHERE (links.article_id is not null OR subcats.category_id is not null)
  					AND (cats.category_id IN ('$items_sql')) GROUP BY cats.category_id";
  		//echo "$query<br />\n";
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

  		$query = "DELETE cats FROM links_categories cats
  					LEFT JOIN links ON links.art_cat=cats.category_id
                    LEFT JOIN links_categories subcats   ON subcats.parent = cats.category_id
  					WHERE links.article_id is null AND subcats.category_id is null
  					AND cats.category_id IN ('$items_sql')";
  		//echo "$query<br />\n";
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
  		                  FROM links_categories s1, links_categories s2
  		                  WHERE s1.parent=s2.parent AND s1.order_num>s2.order_num
  		                  AND s2.category_id = '$category_id'
  		                  ORDER BY s1.order_num ASC
  		                  LIMIT 1");
  		$this->db->query("UPDATE links_categories s1, links_categories s2
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
  		                  FROM links_categories s1, links_categories s2
  		                  WHERE s1.parent=s2.parent AND  s1.order_num<s2.order_num
  		                  AND s2.category_id = '$category_id'
  		                  ORDER BY s1.order_num DESC
  		                  LIMIT 1");
  		$this->db->query("UPDATE links_categories s1, links_categories s2
  		                  SET s1.order_num = (@a:=s1.order_num)*0+s2.order_num, s2.order_num = @a
  		                  WHERE s1.category_id = '$category_id'
  		                  AND s2.category_id = @id");
  		$get = $this->form_get(array());
  		header("Location: index.php$get");
  	}
  	print_r($_POST['delete_fotos']);

     if(isset($_GET['action']) && $_GET['action']=='delete')
  	{
  	$category_id = $_GET['item_id'];
  	$this->delete_fotos($category_id);
  	$get = $this->form_get(array('section'=>'LinksCategories'));
  	header("Location: index.php$get");
  	}
  }

  function fetch()
  {
  	$this->title = $this->lang->LINKS_CATEGORIES;

    $categories = (new Links($this))->get_categories();

 	$this->smarty->assign('Categories', $categories);
  	$this->smarty->assign('ErrorMSG', $this->error_msg);
    $this->smarty->assign('Lang', $this->lang);
 	$this->body = $this->smarty->fetch('links_categories.tpl');
  }
}

############################################
# Class EditGoodCategory - Edit the good gategory
############################################
class EditLinksCategories extends Widget
{
  var $category;
  var $error_msg;
  var $max_level = 1;
  var $uploaddir = '../foto/icons/';
  var $accepted_file_types = array('image/pjpeg', 'image/gif', 'image/jpeg', 'image/jpg', 'image/png', 'image/x-png');
  var $max_image_size = 1024000;

  function __construct(&$parent)
  {
    parent::__construct($parent);
    $this->add_param('parent');
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


  function add_fotos($category)
  {
     if(isset($_FILES['fotos']))
     {
      //echo $_FILES['fotos']['type']."<br />";
      //$category = $_GET[''];
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
        		case 'image/x-png':
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
             $query = "REPLACE INTO category_icons (category_id, filename) VALUES ('$category','$uploadfile')";
             //echo "$query<br />\n";
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
    $this->category->category_id = $this->param('item_id');
    $action = $this->param('action');

    if(isset($action) && $action == 'delete'){
    	 $this->delete_fotos($this->category->category_id);
    	 $get = $this->form_get(array('section'=>'EditLinksCategories','item_id'=>$this->category->category_id));
         header("Location: index.php$get");
    }

    if(isset($_POST['name']))
    {
  	    $this->category->name = trim($_POST['name']);
        $this->category->enabled = 0;
        $this->category->parent = $_POST['parent'];
        $this->category->title = trim($_POST['title']);
        $this->category->keywords = trim($_POST['keywords']);
        $this->category->path = trim($_POST['path']);
        $this->category->description = trim($_POST['description']);
        $this->category->meta_description = trim($_POST['meta_description']);
        if(isset($_POST['enabled']))
          $this->category->enabled = 1;

  		$query = "SELECT * FROM links_categories WHERE path='".$this->category->path."'";
        $this->db->query($query);
        $otherpath = $this->db->result();

        //$this->add_fotos($this->category->category_id);

  		if(empty($this->category->name))
  		  $this->error_msg = $this->lang->ENTER_NAME;
  		elseif(empty($this->category->path))
  		  $this->error_msg = $this->lang->ENTER_PATH;
  		elseif(!empty($this->category->category_id))
        {
  			 if($otherpath && $this->category->category_id!=$otherpath->category_id)
  		  	 $this->error_msg = $this->lang->ENTER_OTHER_PATH;
  		  	 else
  		  {
	      $query = sql_placeholder('UPDATE links_categories
  	                    		  SET name=?, enabled=?, parent=?, title=?, path=?, keywords=?, meta_description=?, description=?
  	                    		  WHERE category_id=?',
  	                    		  $this->category->name,
                                  $this->category->enabled,
                                  $this->category->parent,
                                  $this->category->title,
                                  $this->category->path,
                                  $this->category->keywords,
                                  $this->category->meta_description,
                                  $this->category->description,
  	                    		  $this->category->category_id);
  	      $this->db->query($query);
  	      }
        }
        else
        {
  			 if($otherpath)
  		  	 $this->error_msg = $this->lang->ENTER_OTHER_PATH;
  		  	 else
  		  {
  			$query = sql_placeholder('INSERT INTO links_categories (parent, name, enabled, title, path, keywords, meta_description, description) VALUES(?, ?, ?, ?, ?, ?, ?, ?)',
  									  $this->category->parent,
  			                          $this->category->name,
                                      $this->category->enabled,
                                      $this->category->title,
                                      $this->category->path,
                                      $this->category->keywords,
                                      $this->category->meta_description,
                                      $this->category->description
  			                         );
  			$this->db->query($query);
  			$last_insert_id = $this->db->insert_id();
  			$query = sql_placeholder('UPDATE links_categories SET order_num=category_id WHERE category_id=?',
  			                          $last_insert_id
  			                         );
  			$this->db->query($query);
  		  }
  		}

      if(empty($this->error_msg))
      {
  	  $get = $this->form_get(array('section'=>'LinksCategories'));
  	  header("Location: index.php$get");
  	  }
  	}
    else
  	{
      $query = sql_placeholder('SELECT *
	                    		FROM links_categories
	                    		WHERE category_id=?',
            		            $this->category->category_id);
 	  $this->db->query($query);
  	  $this->category = $this->db->result();

  	}
  }

  function fetch()
  {
      //print_r($_POST['delete_fotos']);
      $delete_get = $this->form_get(array('section'=>'EditLinksCategories','action'=>'delete','item_id'=>$this->category->category_id));
      $categories = (new Links($this))->get_categories();
      if($this->category->category_id)
    	  $this->title = $this->lang->EDIT_CATEGORY.' &laquo;'.$this->category->name.'&raquo;';
      else
    	  $this->title = $this->lang->NEW_CATEGORY;

 	  $this->smarty->assign('Item', $this->category);
 	  $this->smarty->assign('Categories', $categories);
 	  $this->smarty->assign('Parent', $parent);
 	  $this->smarty->assign('Delete_get', $delete_get);
 	  $this->smarty->assign('MaxLevel', $max_level);
      $this->smarty->assign('Lang', $this->lang);
	  $this->smarty->assign('ErrorMSG', $this->error_msg);
 	  $this->body = $this->smarty->fetch('link_category.tpl');
  }
}

############################################
# Class NewsLine displays news
############################################
class OurLinks extends Widget
{
  var $pages_navigation;
  var $items_per_page = 30;
  var $uploaddir = '../foto/links/';
  function __construct(&$parent)
  {
    parent::__construct($parent);
    $this->add_param('page');
    $this->add_param('keyword');
    $this->pages_navigation = new PagesNavigation($this);
    $this->prepare();
  }

  function prepare()
  {
  	if(isset($_POST['items']))
  	{
  		$items = $_POST['items'];
  		if(is_array($items))
  		  $items_sql = implode("', '", $items);
  		else
  		  $items_sql = $items;
  		$query = "DELETE FROM links_our
 		          WHERE article_id IN ('$items_sql')";
  		$this->db->query($query);
  		$get = $this->form_get(array());
 		header("Location: index.php$get");
 	}
	if($_POST['act']=='active')
	{
		$enabled = $_POST['enabled'];
      $rows = $_POST['rows'];
      foreach($rows as $ck=>$row)
      {

		$this->db->query($query);
		if(@in_array($ck,$enabled))
		{
		$query = "UPDATE links_our SET enabled='1' WHERE article_id = '$ck'";
		$this->db->query($query);
		}
		else
		{
		$query = "UPDATE links_our SET enabled='0' WHERE article_id = '$ck'";
		$this->db->query($query);
		}

      }

		$get = $this->form_get(array());
	header("Location: index.php$get");
    }
  	if(isset($_GET['action']) and $_GET['action']=='move_up')
  	{
  		$item_id = $_GET['item_id'];
  		$this->db->query("SELECT @id:=a1.article_id
  		                  FROM links_our a1, links_our a2
  		                  WHERE a1.order_num>a2.order_num
  		                  AND a2.article_id = '$item_id'
  		                  ORDER BY a1.order_num ASC
  		                  LIMIT 1");
  		$this->db->query("UPDATE links_our a1, links_our a2
  		                  SET a1.order_num = (@a:=a1.order_num)*0+a2.order_num, a2.order_num = @a
  		                  WHERE a1.article_id = '$item_id'
  		                  AND a2.article_id = @id");
  		$get = $this->form_get(array());
  		header("Location: index.php$get");
  	}
 	if(isset($_GET['action']) and $_GET['action']=='move_down')
  	{
   		$item_id = $_GET['item_id'];
  		$this->db->query("SELECT @id:=a1.article_id
  		                  FROM links_our a1, links_our a2
  		                  WHERE a1.order_num<a2.order_num
  		                  AND a2.article_id = '$item_id'
  		                  ORDER BY a1.order_num DESC
  		                  LIMIT 1");
  		$this->db->query("UPDATE links_our a1, links_our a2
  		                  SET a1.order_num = (@a:=a1.order_num)*0+a2.order_num, a2.order_num = @a
  		                  WHERE a1.article_id = '$item_id'
  		                  AND a2.article_id = @id");
  		$get = $this->form_get(array());
  		header("Location: index.php$get");
  	}

    if(isset($_GET['action']) && $_GET['action']=='delete')
  	{
  	$category_id = $_GET['item_id'];
  	$this->delete_fotos($category_id);
  	$get = $this->form_get(array('section'=>'Links'));
  	header("Location: index.php$get");
  	}

  }

  function fetch()
  {
    $this->title = 'Управление статьями';
  	$current_page = $this->param('page');
  	$keyword = $this->param('keyword');
  	if(!$current_page) $current_page=0;
  	$start_item = $current_page*$this->items_per_page;
  	$filter = "";
    if(!empty($keyword))
    $filter = "WHERE path LIKE '%".$keyword."%'";

  	$query = "SELECT SQL_CALC_FOUND_ROWS *
    				  FROM links_our ORDER BY order_num DESC
    				  LIMIT $start_item ,$this->items_per_page";
    //echo "$query<br />\n";
    $this->db->query($query);
  	$items = $this->db->results();

    $this->db->query("SELECT FOUND_ROWS() as count");
    $pages_num = $this->db->result();
    $pages_num = $pages_num->count/$this->items_per_page;
    //echo "count = ".$pages_num->count."<br />\n";
    //echo "pages_num = $pages_num<br />\n";
    foreach($items as $key=>$item)
    {
       $items[$key]->move_up_get = $this->form_get(array('action'=>'move_up','item_id'=>$item->article_id));
       $items[$key]->move_down_get = $this->form_get(array('action'=>'move_down','item_id'=>$item->article_id));
       $items[$key]->edit_get = $this->form_get(array('section'=>'EditOurLink','item_id'=>$item->article_id));
    }

  	$this->pages_navigation->pages_num = $pages_num;
  	$this->pages_navigation->fetch();
 	$this->smarty->assign('Items', $items);
  	$this->smarty->assign('PagesNavigation', $this->pages_navigation->body);
  	$this->smarty->assign('Lang', $this->lang);
  	$this->smarty->assign('Art_cats', $this->art_cats);
  	$this->smarty->assign('Uploaddir', $this->uploaddir);
 	$this->body = $this->smarty->fetch('links_our.tpl');
  }
}

############################################
# Class EditServiceSection - edit the static section
############################################
class EditOurLink extends Widget
{
  var $item;
  var $uploaddir = '../foto/links/';
  var $accepted_file_types = array('image/pjpeg', 'image/gif', 'image/jpeg', 'image/jpg', 'image/png', 'image/x-png');
  var $max_image_size = 1024000;

  function __construct(&$parent)
  {
    parent::__construct($parent);
    $this->add_param('page');
    $this->prepare();
  }

  function prepare()
  {
  	$item_id = intval($this->param('item_id'));
    $action = $this->param('action');

    if(isset($action) && $action == 'delete'){
    	 $this->delete_fotos($item_id);
    	 $get = $this->form_get(array('section'=>'EditOurLink','item_id'=>$item_id));
         header("Location: index.php$get");
         $this->db->disconnect();
         exit();
    }
  	if(
  	   isset($_POST['SUBMIT']))
  	{
  		$this->item->my_link = trim($_POST['my_link']);
        if(isset($_POST['enabled']))
  		$this->item->enabled = 1;
  		else
  		$this->item->enabled = 0;

  		if(empty($this->item->my_link))
  		  $this->error_msg = $this->lang->ENTER_TITLE;
        else
  		{
  			if(empty($item_id))
            {
             if($otherpath)
  		  	 $this->error_msg = $this->lang->ENTER_OTHER_PATH;
  		  	 else
  		  	 {
  				$query = sql_placeholder('INSERT INTO links_our (article_id, my_link, enabled) VALUES(NULL, ?, ?)',
  			                          $this->item->my_link,
  			                          $this->item->enabled);
                $this->db->query($query);
	  			$inserted_id = $this->db->insert_id();

  				$query = sql_placeholder('UPDATE links_our SET order_num=article_id WHERE article_id=?',
  			                          $inserted_id);
  				$this->db->query($query);
             }
            }
  			else
            {
  			 if($otherpath && $item_id!=$otherpath->article_id)
  		  	 $this->error_msg = $this->lang->ENTER_OTHER_PATH;
  		  	 else
  		  	 {
  				$query = sql_placeholder('UPDATE links_our SET my_link=?, enabled=? WHERE article_id=?',
  			                          $this->item->my_link,
  			                          $this->item->enabled,
  			                          $item_id);
                $this->db->query($query);
             }
  		    }
                if(empty($this->error_msg))
                {
 			    $get = $this->form_get(array('section'=>'OurLinks'));
  		        header("Location: index.php$get");
  		        }
  		}
  	}

  	elseif (!empty($item_id))
  	{
  	  $query = sql_placeholder('SELECT * FROM links_our WHERE article_id=?', $item_id);
  	  $this->db->query($query);
  	  $this->item = $this->db->result();
  	}
  }

  function fetch()
  {
  	  $delete_get = $this->form_get(array('section'=>'EditOurLink','action'=>'delete','item_id'=>$this->item->article_id));
  	  if(empty($this->item->article_id))
  	    $this->title = $this->lang->NEW_ARTICLE;
  	  else
  	    $this->title = $this->lang->EDIT_ARTICLE;

	  //$this->art_cats = Links::get_categories();

 	  $this->smarty->assign('Item', $this->item);
 	  $this->smarty->assign('ErrorMSG', $this->error_msg);
      $this->smarty->assign('Lang', $this->lang);
	  $this->smarty->assign('Art_cats', $this->art_cats);
 	  $this->smarty->assign('Delete_get', $delete_get);
  	  $this->smarty->assign('Uploaddir', $this->uploaddir);
 	  $this->body = $this->smarty->fetch('link_our.tpl');
  }
}
