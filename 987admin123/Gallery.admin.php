<?PHP

require_once('Widget.admin.php');
require_once('PagesNavigation.admin.php');


############################################
# Class NewsLine displays news
############################################
class Gallery extends Widget
{
  var $pages_navigation;
  var $uploaddir = '../foto/gallery/';
  var $items_per_page = 30;
  function __construct(&$parent)
  {
    parent::__construct($parent);
    $this->add_param('page');
    $this->pages_navigation = new PagesNavigation($this);
    $this->prepare();
  }

  function prepare()
  {
  	if(isset($_POST['items']) && $_POST['act']=='delete')
  	{
  		$items = $_POST['items'];
  		if(is_array($items))
  		  $items_sql = implode("', '", $items);
  		else
  		  $items_sql = $items;
  		$query = "DELETE FROM gallery
 		          WHERE gallery.foto_id IN ('$items_sql')";
  		$this->db->query($query);
  		$get = $this->form_get(array());
 		header("Location: index.php$get");
 	}
  	if($_POST['act']=='active')
  	{
  		$enabled = $_POST['enabled'];
  		$ids = $_POST['id'];
      foreach($ids as $ck=>$id)
      {
		if(in_array($ck,$enabled))
		{
		$query = "UPDATE gallery SET enabled='1' WHERE foto_id = '$ck'";
		$this->db->query($query);
		}
		else
		{
		$query = "UPDATE gallery SET enabled='0' WHERE foto_id = '$ck'";
		$this->db->query($query);
		}
      }
  		$get = $this->form_get(array());
 		header("Location: index.php$get");
 	}
  	if(isset($_GET['action']) and $_GET['action']=='move_down')
  	{
  		$item_id = $_GET['item_id'];
  		$this->db->query("SELECT @id:=a1.foto_id
  		                  FROM gallery a1, gallery a2
  		                  WHERE a1.order_num>a2.order_num
  		                  AND a2.foto_id = '$item_id'
  		                  ORDER BY a1.order_num ASC
  		                  LIMIT 1");
  		$this->db->query("UPDATE gallery a1, gallery a2
  		                  SET a1.order_num = (@a:=a1.order_num)*0+a2.order_num, a2.order_num = @a
  		                  WHERE a1.foto_id = '$item_id'
  		                  AND a2.foto_id = @id");
  		$get = $this->form_get(array());
  		header("Location: index.php$get");
  	}
 	if(isset($_GET['action']) and $_GET['action']=='move_up')
  	{
   		$item_id = $_GET['item_id'];
  		$this->db->query("SELECT @id:=a1.foto_id
  		                  FROM gallery a1, gallery a2
  		                  WHERE a1.order_num<a2.order_num
  		                  AND a2.foto_id = '$item_id'
  		                  ORDER BY a1.order_num DESC
  		                  LIMIT 1");
  		$this->db->query("UPDATE gallery a1, gallery a2
  		                  SET a1.order_num = (@a:=a1.order_num)*0+a2.order_num, a2.order_num = @a
  		                  WHERE a1.foto_id = '$item_id'
  		                  AND a2.foto_id = @id");
  		$get = $this->form_get(array());
  		header("Location: index.php$get");
  	}
  }

  function fetch()
  {
    $this->title = 'Управление фотогалереи';
  	$current_page = $this->param('page');
  	$start_item = $current_page*$this->items_per_page;
    $this->db->query("SELECT SQL_CALC_FOUND_ROWS *
    				  FROM gallery
    				  ORDER BY order_num ASC
    				  LIMIT $start_item ,$this->items_per_page");
  	$items = $this->db->results();

    $this->db->query("SELECT FOUND_ROWS() as count");
    $pages_num = $this->db->result();
    $pages_num = $pages_num->count/$this->items_per_page;

    foreach($items as $key=>$item)
    {
       $items[$key]->move_up_get = $this->form_get(array('action'=>'move_up','item_id'=>$item->foto_id));
       $items[$key]->move_down_get = $this->form_get(array('action'=>'move_down','item_id'=>$item->foto_id));
       $items[$key]->edit_get = $this->form_get(array('section'=>'EditGallery','item_id'=>$item->foto_id));
    }

  	$this->pages_navigation->pages_num = $pages_num;
  	$this->pages_navigation->fetch();
 	$this->smarty->assign('Items', $items);
  	$this->smarty->assign('PagesNavigation', $this->pages_navigation->body);
    $this->smarty->assign('Uploaddir', $this->uploaddir);
  	$this->smarty->assign('Lang', $this->lang);
 	$this->body = $this->smarty->fetch('gallery.tpl');
  }
}

############################################
# Class EditServiceSection - edit the static section
############################################
class EditGallery extends Widget
{
  var $item;
  var $accepted_file_types = array('image/pjpeg', 'image/gif', 'image/jpeg', 'image/jpg', 'image/png');
  var $max_image_size = 1024000;
  var $uploaddir = '../foto/gallery/';
  function __construct(&$parent)
  {
    parent::__construct($parent);
    $this->add_param('page');
    $this->prepare();
  }

  function delete_fotos()
  {
     if(isset($_POST['delete_fotos']))
     {
       $delete_fotos = split(',', $_POST['delete_fotos']);
       foreach($delete_fotos as $foto_id)
       {
         if($foto_id!='')
         {
             $this->db->query("SELECT * FROM gallery WHERE foto_id = '$foto_id'");
             $foto = $this->db->result();
             $this->db->query("DELETE FROM gallery WHERE foto_id = '$foto_id'");
             $file = $this->uploaddir.$foto->filename;
  			 if(is_file($file))
  			   unlink($file);
         }
       }
     }
  }

  function add_fotos($item_id)
  {
     if(isset($_FILES['foto']))
     {

          if(!empty($_FILES['foto']['name']))
          {
            if(in_array($_FILES['foto']['type'],$this->accepted_file_types))
            {
             if($_FILES['foto']['size'] < $this->max_image_size)
             {
  			   	switch ($_FILES['foto']['type']) {
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
			 if (!move_uploaded_file($_FILES['foto']['tmp_name'], $this->uploaddir.$uploadfile))
		  		 $this->error_msg .= $this->lang->FILE_UPLOAD_ERROR." file  error: ".$_FILES['foto']['error']."<br />";
             $this->db->query("UPDATE gallery SET filename='$uploadfile' WHERE foto_id='$item_id'");
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
  	$item_id = intval($this->param('item_id'));
  	if(
  	   isset($_POST['SUBMIT']))
  	{
  		if(isset($_POST['enabled']))
  		$this->item->enabled = 1;
  		else
  		$this->item->enabled = 0;

  		/*if(empty($this->item->title))
  		  $this->error_msg = $this->lang->ENTER_TITLE;
        else
  		{*/
  			if(empty($item_id))
            {
  				$query = sql_placeholder('INSERT INTO gallery(foto_id, enabled) VALUES(NULL, ?)',
  			                          $this->item->enabled);
                $this->db->query($query);
	  			$item_id = $this->db->insert_id();

  				$query = sql_placeholder('UPDATE gallery SET order_num=foto_id WHERE foto_id=?',
  			                          $item_id);
  				$this->db->query($query);

            }
  			else
            {
  				$query = sql_placeholder('UPDATE gallery SET enabled=? WHERE foto_id=?',
  			                          $this->item->enabled,
  			                          $item_id);
                $this->db->query($query);
            }

            $this->delete_fotos();
            $this->add_fotos($item_id);

 			$get = $this->form_get(array('section'=>'Gallery'));
  		    header("Location: index.php$get");
  		//}
  	}
  	elseif (!empty($item_id))
  	{
  	  $query = sql_placeholder('SELECT * FROM gallery WHERE foto_id=?', $item_id);
  	  $this->db->query($query);
  	  $this->item = $this->db->result();
  	}
  }

  function fetch()
  {
  	  if(empty($this->item->foto_id))
  	    $this->title = $this->lang->NEW_FOTO;
  	  else
  	    $this->title = $this->lang->EDIT_FOTO;

 	  $this->smarty->assign('Item', $this->item);
 	  $this->smarty->assign('ErrorMSG', $this->error_msg);
 	  $this->smarty->assign('Uploaddir', $this->uploaddir);
      $this->smarty->assign('Lang', $this->lang);
 	  $this->body = $this->smarty->fetch('gallery_item.tpl');
  }
}