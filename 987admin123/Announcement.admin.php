<?PHP

require_once('Widget.admin.php');
require_once('PagesNavigation.admin.php');


############################################
# Class NewsLine displays news
############################################
class Announcement extends Widget
{
  var $pages_navigation;
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
  	if(isset($_POST['items']))
  	{
  		$items = $_POST['items'];
  		if(is_array($items))
  		  $items_sql = implode("', '", $items);
  		else
  		  $items_sql = $items;
  		$query = "DELETE FROM announcements
 		          WHERE id IN ('$items_sql')";
  		$this->db->query($query);
  		$get = $this->form_get(array());
 		header("Location: index.php$get");
 	}
  	if(isset($_POST['delete']))
  	{
  		$enableds = $_POST['enableds'];
  		if(is_array($enableds))
  		  $items_sql = implode("', '", $enableds);
  		else
  		  $items_sql = $enableds;
  		if(!empty($enableds))
  		{
  		$query = "UPDATE `announcements` SET `enabled`='1'
 		          WHERE `id` IN ('$items_sql')";
  		$this->db->query($query);
  		$query2 = "UPDATE `announcements` SET `enabled`='0'
 		          WHERE `id` NOT IN ('$items_sql')";
  		$this->db->query($query2);
  		}
  		else
  		{
  		$query = "UPDATE `announcements` SET `enabled`='0'";
  		$this->db->query($query);
  		}
  		$get = $this->form_get(array());
 		header("Location: index.php$get");
 	}

  	if(isset($_GET['action']) and $_GET['action']=='move_up')
  	{
  		$item_id = $_GET['item_id'];
  		$this->db->query("SELECT @id:=a1.id
  		                  FROM announcements a1, announcements a2
  		                  WHERE a1.order_num>a2.order_num
  		                  AND a2.id = '$item_id'
  		                  ORDER BY a1.order_num ASC
  		                  LIMIT 1");
  		$this->db->query("UPDATE announcements a1, announcements a2
  		                  SET a1.order_num = (@a:=a1.order_num)*0+a2.order_num, a2.order_num = @a
  		                  WHERE a1.id = '$item_id'
  		                  AND a2.id = @id");
  		$get = $this->form_get(array());
  		header("Location: index.php$get");
  	}
 	if(isset($_GET['action']) and $_GET['action']=='move_down')
  	{
   		$item_id = $_GET['item_id'];
  		$this->db->query("SELECT @id:=a1.id
  		                  FROM announcements a1, announcements a2
  		                  WHERE a1.order_num<a2.order_num
  		                  AND a2.id = '$item_id'
  		                  ORDER BY a1.order_num DESC
  		                  LIMIT 1");
  		$this->db->query("UPDATE announcements a1, announcements a2
  		                  SET a1.order_num = (@a:=a1.order_num)*0+a2.order_num, a2.order_num = @a
  		                  WHERE a1.id = '$item_id'
  		                  AND a2.id = @id");
  		$get = $this->form_get(array());
  		header("Location: index.php$get");
  	}

  }

  function fetch()
  {
    $this->title = 'Управление Объявлениями';
  	$current_page = $this->param('page');
  	$start_item = $current_page*$this->items_per_page;
    $this->db->query("SELECT SQL_CALC_FOUND_ROWS *
    				  FROM announcements
    				  ORDER BY order_num DESC
    				  LIMIT $start_item ,$this->items_per_page");
  	$items = $this->db->results();

    $this->db->query("SELECT FOUND_ROWS() as count");
    $pages_num = $this->db->result();
    $pages_num = $pages_num->count/$this->items_per_page;

    foreach($items as $key=>$item)
    {
  		switch ($items[$key]->sale) {
     	case 0:
    	$items[$key]->sale = "Покупка";
       	break;
     	case 1:
        $items[$key]->sale = "Продажа";
       	break;
   		}
  		switch ($items[$key]->category) {
     	case 1:
    	$items[$key]->category = "скутера";
       	break;
     	case 2:
        $items[$key]->category = "мотоцикла";
       	break;
     	case 3:
        $items[$key]->category = "запчастей";
       	break;
   		}
       $items[$key]->move_up_get = $this->form_get(array('action'=>'move_up','item_id'=>$item->id));
       $items[$key]->move_down_get = $this->form_get(array('action'=>'move_down','item_id'=>$item->id));
       $items[$key]->edit_get = $this->form_get(array('section'=>'EditAnnouncement','item_id'=>$item->id));
    }

  	$this->pages_navigation->pages_num = $pages_num;
  	$this->pages_navigation->fetch();
 	$this->smarty->assign('Items', $items);
  	$this->smarty->assign('PagesNavigation', $this->pages_navigation->body);
  	$this->smarty->assign('Lang', $this->lang);
 	$this->body = $this->smarty->fetch('announcements.tpl');
  }
}

############################################
# Class EditServiceSection - edit the static section
############################################
class EditAnnouncement extends Widget
{
  var $item;
  var $uploaddir = '../foto/usr/';
  var $accepted_file_types = array('image/gif', 'image/jpeg', 'image/jpg', 'image/png');
  var $max_image_size = 1024000;
  function __construct(&$parent)
  {
    parent::__construct($parent);
    $this->add_param('page');
    $this->prepare();
  }

  function delete_fotos($item_id)
  {
     if(isset($_POST['delete_fotos']))
     {
       $delete_fotos = split(',', $_POST['delete_fotos']);
       foreach($delete_fotos as $foto_id)
       {
         if($foto_id!='')
         {
             $query = "SELECT * FROM `announcements` WHERE `id` = '$item_id'";
             $this->db->query($query);
             $foto = $this->db->result();
             $query = "UPDATE `announcements` SET `file`='' WHERE `id`='$item_id'";
             $this->db->query($query);
             $file = $this->uploaddir.$foto->file;
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
                $uploadfile = $item_id.".gif";
          		break;
        		case 'image/png':
                $uploadfile = $item_id.".png";
          		break;
          		case 'image/pjpeg':
                $uploadfile = $item_id.".jpeg";
          		break;
          		case 'image/jpeg':
                $uploadfile = $item_id.".jpeg";
          		break;
          		case 'image/jpg':
                $uploadfile = $item_id.".jpg";
          		break;
      			}
			 if (!move_uploaded_file($_FILES['foto']['tmp_name'], $this->uploaddir.$uploadfile))
		  		 $this->error_msg .= $this->lang->FILE_UPLOAD_ERROR." error: ".$_FILES['foto']['error']."<br />";
             $query = "UPDATE `announcements` SET `file`='$uploadfile' WHERE `id`='$item_id'";
             $this->db->query($query);
             }
             else
             $this->error_msg .= "Превышен максимальный размер файла <br />";
            }
            else
            $this->error_msg .= "Неверный тип файла <br />";
          }
     }

  }

  function prepare()
  {
  	$item_id = intval($this->param('item_id'));
  	if(isset($_POST['save']))
  	{
  		$this->item->sale = $_POST['sale'];
  		$this->item->category = $_POST['category'];
  		$this->item->date = trim($_POST['date']);
  		$this->item->range = trim($_POST['range']);
  		$this->item->brand = trim($_POST['brand']);
        $this->item->model = trim($_POST['model']);
        $this->item->year = trim($_POST['year']);
        $this->item->volume = trim($_POST['volume']);
        $this->item->city = trim($_POST['city']);
        $this->item->price = trim($_POST['price']);
        $this->item->mail = trim($_POST['mail']);
        $this->item->phone = trim($_POST['phone']);
        $this->item->contact = trim($_POST['contact']);
        $this->item->body = $_POST['body'];
  		if(isset($_POST['enabled']))
  		  $this->item->enabled = 1;
        else
  		  $this->item->enabled = 0;

/*  	if(isset($_FILES['foto']))
     {

        $query = "SHOW TABLE STATUS LIKE 'announcements'";
		$this->db->query($query);
		$foto_id = $this->db->result();
		$fotoid = $foto_id->Auto_increment;
        if(!empty($_FILES['foto']['name']))
        {
          if(in_array($_FILES['foto']['type'],$this->accepted_file_types))
          {
  			 switch ($_FILES['foto']['type']) {
        		case 'image/gif':
                $uploadfile = $fotoid.".gif";
          		break;
        		case 'image/png':
                $uploadfile = $fotoid.".png";
          		break;
          		case 'image/jpeg':
                $uploadfile = $fotoid.".jpeg";
          		break;
          		case 'image/jpg':
                $uploadfile = $fotoid.".jpg";
          		break;
      			}

			 if (!move_uploaded_file($_FILES['foto']['tmp_name'], $this->uploaddir.$uploadfile))
		  		 $result="<span style='color:Red'>Ошибка загрузки файла</span>";
          }
          else
          $result="<span style='color:Red'>Неверный формат файла</span>";
        }
     }*/

  		/*if(isset($_POST['send']))
  		  $this->item->send = 1;
        else
  		  $this->item->send = 0;*/

  		/*if(empty($this->item->answer))
  		  $this->error_msg = $this->lang->ENTER_ANSWER;
        else*/
  		//{
  			/*if(empty($item_id))
            {
  				$query = sql_placeholder('INSERT INTO articles(article_id, title, keywords, description, annotation, body) VALUES(NULL, ?, ?, ?, ?, ?)',
  			                          $this->item->title,
  			                          $this->item->keywords,
  			                          $this->item->description,
  			                          $this->item->annotation,
  			                          $this->item->body);
                $this->db->query($query);
	  			$inserted_id = $this->db->insert_id();

  				$query = sql_placeholder('UPDATE articles SET order_num=article_id WHERE article_id=?',
  			                          $inserted_id);
  				$this->db->query($query);

            }
  			else*/
            //{
  				$query = sql_placeholder('UPDATE `announcements` SET `sale`=?, `category`=?, `date`=?, `range`=?, `brand`=?, `model`=?, `year`=?, `volume`=?, `city`=?, `price`=?, `mail`=?, `phone`=?, `contact`=?, `body`=?, `enabled`=? WHERE `id`=?',
  			                          	$this->item->sale,
  										$this->item->category,
  										$this->item->date,
	  									$this->item->range,
  										$this->item->brand,
        								$this->item->model,
        								$this->item->year,
        								$this->item->volume,
        								$this->item->city,
        								$this->item->price,
        								$this->item->mail,
        								$this->item->phone,
        								$this->item->contact,
        								$this->item->body,
  		  								$this->item->enabled,
  			                         	$item_id);
                $this->db->query($query);
            //echo "$query<br />\n";
            $this->delete_fotos($item_id);
            $this->add_fotos($item_id);
            //}
            /*if($this->item->send)
            {            	$headers = "MIME-Version: 1.0\r\n";
				$headers .= "Content-type: text/html; charset=windows-1251\r\n";
				$headers .= "From: robot@".$_SERVER['SERVER_NAME']."\r\n";
				$subject = "Ответ на cообщение пользователя ".$this->item->name;
                $text="<html>\r\n<head>\r\n<title>Ответ на cообщение пользователя ".$this->item->name."</title>\r\n</head>\r\n<body>\r\n<h1>Ответ на сообщение</h1>\r\n<br />>Имя: ".$this->item->name."<br />\r\n".">Город: ".$this->item->city."<br />\r\n".">E-mail: ".$this->item->mail."<br />\r\n>Текст сообщения:<br />\r\n<br />\r\n>".$this->item->message."<hr>Ответ на сообщение:<br />\r\n<br />\r\n".$this->item->answer."\r\n</body>\r\n</html>";
                @mail($this->item->mail,$subject,$text,$headers);
                //echo "mail(".$this->item->mail.",$subject,$text,$headers)<br />\n";            }*/
            if(empty($this->error_msg))
  			{
 			$get = $this->form_get(array('section'=>'Announcement'));
  		    header("Location: index.php$get");
  			}

  		//}
  	}

  	elseif (!empty($item_id))
  	{
  	  $query = sql_placeholder('SELECT * FROM `announcements` WHERE id=?', $item_id);
  	  $this->db->query($query);
  	  $this->item = $this->db->result();
  	}
  }

  function fetch()
  {
  	  $this->title = $this->lang->ANSWER." на вопрос &laquo;".$this->item->id."&raquo;";

 	  $this->smarty->assign('Item', $this->item);
 	  $this->smarty->assign('ErrorMSG', $this->error_msg);
      $this->smarty->assign('Lang', $this->lang);
 	  $this->body = $this->smarty->fetch('announcement.tpl');
  }
}