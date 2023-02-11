<?PHP

require_once('Widget.admin.php');
require_once('PagesNavigation.admin.php');


############################################
# Class NewsLine displays news
############################################
class Faq extends Widget
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
  		$query = "DELETE FROM faq
 		          WHERE id IN ('$items_sql')";
  		$this->db->query($query);
  		$get = $this->form_get(array());
 		header("Location: index.php$get");
 	}
  	if(isset($_POST['delete']))
  	{
  		$shows = $_POST['shows'];
  		if(is_array($shows))
  		  $items_sql = implode("', '", $shows);
  		else
  		  $items_sql = $shows;
  		if(!empty($shows))
  		{
  		$query = "UPDATE `faq` SET `show`='1'
 		          WHERE `id` IN ('$items_sql')";
  		$this->db->query($query);
  		$query2 = "UPDATE `faq` SET `show`='0'
 		          WHERE `id` NOT IN ('$items_sql')";
  		$this->db->query($query2);
  		}
  		else
  		{
  		$query = "UPDATE `faq` SET `show`='0'";
  		$this->db->query($query);
  		}
  		$get = $this->form_get(array());
 		header("Location: index.php$get");
 	}

  	if(isset($_GET['action']) and $_GET['action']=='move_up')
  	{
  		$item_id = $_GET['item_id'];
  		$this->db->query("SELECT @id:=a1.id
  		                  FROM faq a1, faq a2
  		                  WHERE a1.order_num>a2.order_num
  		                  AND a2.id = '$item_id'
  		                  ORDER BY a1.order_num ASC
  		                  LIMIT 1");
  		$this->db->query("UPDATE faq a1, faq a2
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
  		                  FROM faq a1, faq a2
  		                  WHERE a1.order_num<a2.order_num
  		                  AND a2.id = '$item_id'
  		                  ORDER BY a1.order_num DESC
  		                  LIMIT 1");
  		$this->db->query("UPDATE faq a1, faq a2
  		                  SET a1.order_num = (@a:=a1.order_num)*0+a2.order_num, a2.order_num = @a
  		                  WHERE a1.id = '$item_id'
  		                  AND a2.id = @id");
  		$get = $this->form_get(array());
  		header("Location: index.php$get");
  	}

  }

  function fetch()
  {
    $this->title = 'Управление FAQ';
  	$current_page = $this->param('page');
  	$start_item = $current_page*$this->items_per_page;
    $this->db->query("SELECT SQL_CALC_FOUND_ROWS *
    				  FROM faq
    				  ORDER BY order_num DESC
    				  LIMIT $start_item ,$this->items_per_page");
  	$items = $this->db->results();

    $this->db->query("SELECT FOUND_ROWS() as count");
    $pages_num = $this->db->result();
    $pages_num = $pages_num->count/$this->items_per_page;

    foreach($items as $key=>$item)
    {
       $items[$key]->move_up_get = $this->form_get(array('action'=>'move_up','item_id'=>$item->id));
       $items[$key]->move_down_get = $this->form_get(array('action'=>'move_down','item_id'=>$item->id));
       $items[$key]->edit_get = $this->form_get(array('section'=>'EditFaq','item_id'=>$item->id));
    }

  	$this->pages_navigation->pages_num = $pages_num;
  	$this->pages_navigation->fetch();
 	$this->smarty->assign('Items', $items);
  	$this->smarty->assign('PagesNavigation', $this->pages_navigation->body);
  	$this->smarty->assign('Lang', $this->lang);
 	$this->body = $this->smarty->fetch('faqs.tpl');
  }
}

############################################
# Class EditServiceSection - edit the static section
############################################
class EditFaq extends Widget
{
  var $item;
  function __construct(&$parent)
  {
    parent::__construct($parent);
    $this->add_param('page');
    $this->prepare();
  }

  function prepare()
  {
  	$item_id = intval($this->param('item_id'));
  	if(isset($_POST['save']))
  	{
  		$this->item->name = $_POST['name'];
  		$this->item->city = $_POST['city'];
  		$this->item->mail = $_POST['mail'];
  		$this->item->phone = $_POST['phone'];
  		$this->item->message = $_POST['message'];
  		$this->item->answer = $_POST['answer'];
  		if(isset($_POST['show']))
  		  $this->item->show = 1;
        else
  		  $this->item->show = 0;
  		if(isset($_POST['send']))
  		  $this->item->send = 1;
        else
  		  $this->item->send = 0;

  		if(empty($this->item->answer))
  		  $this->error_msg = $this->lang->ENTER_ANSWER;
        else
  		{
  			if(empty($item_id))
            {
  				$query = sql_placeholder('INSERT INTO `faq` (`id`, `name`, `city`, `mail`, `phone`, `message`, `answer`, `show`) VALUES (NULL, ?, ?, ?, ?, ?, ?)',
  			                          $this->item->name,
  			                          $this->item->city,
  			                          $this->item->mail,
  			                          $this->item->phone,
  			                          $this->item->message,
  			                          $this->item->answer,
  			                          $this->item->show);
                $this->db->query($query);
	  			$inserted_id = $this->db->insert_id();

  				$query = sql_placeholder('UPDATE faq SET order_num=id WHERE id=?',
  			                          $inserted_id);
  				$this->db->query($query);

            }
  			else
            {
  				$query = sql_placeholder('UPDATE `faq` SET `name`=?, `city`=?, `mail`=?, `phone`=?, `message`=?, `answer`=?, `show`=? WHERE `id`=?',
  			                          $this->item->name,
  			                          $this->item->city,
  			                          $this->item->mail,
  			                          $this->item->phone,
  			                          $this->item->message,
  			                          $this->item->answer,
  			                          $this->item->show,
  			                          $item_id);
                $this->db->query($query);
            }
            if($this->item->send)
            {            	$headers = "MIME-Version: 1.0\r\n";
				$headers .= "Content-type: text/html; charset=windows-1251\r\n";
				$headers .= "From: robot@".$_SERVER['SERVER_NAME']."\r\n";
				$subject = "Ответ на cообщение пользователя ".$this->item->name;
                $text="<html>\r\n<head>\r\n<title>Ответ на cообщение пользователя ".$this->item->name."</title>\r\n</head>\r\n<body>\r\n<h1>Ответ на сообщение</h1>\r\n<br />>Имя: ".$this->item->name."<br />\r\n".">Город: ".$this->item->city."<br />\r\n".">E-mail: ".$this->item->mail.">Телефон: ".$this->item->phone."<br />\r\n>Текст сообщения:<br />\r\n<br />\r\n>".$this->item->message."<hr>Ответ на сообщение:<br />\r\n<br />\r\n".$this->item->answer."\r\n</body>\r\n</html>";
                @mail($this->item->mail,$subject,$text,$headers);
                //echo "mail(".$this->item->mail.",$subject,$text,$headers)<br />\n";            }
 			$get = $this->form_get(array('section'=>'Faq'));
  		    header("Location: index.php$get");
  		}
  	}

  	elseif (!empty($item_id))
  	{
  	  $query = sql_placeholder('SELECT * FROM `faq` WHERE id=?', $item_id);
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
 	  $this->body = $this->smarty->fetch('faq.tpl');
  }
}