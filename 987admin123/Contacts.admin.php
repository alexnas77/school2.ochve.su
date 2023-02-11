<?PHP

require_once('Widget.admin.php');
require_once('PagesNavigation.admin.php');


############################################
# Class NewsLine displays news
############################################
class Contacts extends Widget
{
  var $pages_navigation;
  var $items_per_page = 30;
  var $modes = array('ICQ'=>'ICQ', 'Tel'=>'Телефон', 'Email'=>'E-mail');
  function __construct(&$parent)
  {
    parent::__construct($parent);
    $this->add_param('page');
    $this->add_param('mode');
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
  		$query = "DELETE FROM contacts
 		          WHERE contacts.contact_id IN ('$items_sql')";
  		$this->db->query($query);
  		$get = $this->form_get(array());
 		header("Location: index.php$get");
 	}
  	if(isset($_GET['action']) and $_GET['action']=='move_up')
  	{
  		$item_id = $_GET['item_id'];
  		$this->db->query("SELECT @id:=a1.contact_id
  		                  FROM contacts a1, contacts a2
  		                  WHERE a1.order_num>a2.order_num
  		                  AND a1.mode=a2.mode
  		                  AND a2.contact_id = '$item_id'
  		                  ORDER BY a1.order_num ASC
  		                  LIMIT 1");
  		$this->db->query("UPDATE contacts a1, contacts a2
  		                  SET a1.order_num = (@a:=a1.order_num)*0+a2.order_num, a2.order_num = @a
  		                  WHERE a1.contact_id = '$item_id'
  		                  AND a2.contact_id = @id");
  		$get = $this->form_get(array());
  		header("Location: index.php$get");
  	}
 	if(isset($_GET['action']) and $_GET['action']=='move_down')
  	{
   		$item_id = $_GET['item_id'];
  		$this->db->query("SELECT @id:=a1.contact_id
  		                  FROM contacts a1, contacts a2
  		                  WHERE a1.order_num<a2.order_num
  		                  AND a1.mode=a2.mode
  		                  AND a2.contact_id = '$item_id'
  		                  ORDER BY a1.order_num DESC
  		                  LIMIT 1");
  		$this->db->query("UPDATE contacts a1, contacts a2
  		                  SET a1.order_num = (@a:=a1.order_num)*0+a2.order_num, a2.order_num = @a
  		                  WHERE a1.contact_id = '$item_id'
  		                  AND a2.contact_id = @id");
  		$get = $this->form_get(array());
  		header("Location: index.php$get");
  	}

  }

  function fetch()
  {
    $this->title = 'Управление контактами';
  	$current_page = $this->param('page');
  	$start_item = $current_page*$this->items_per_page;
    $this->db->query("SELECT SQL_CALC_FOUND_ROWS contacts.*
    				  FROM contacts
    				  ORDER BY mode, order_num DESC
    				  LIMIT $start_item ,$this->items_per_page");
  	$items = $this->db->results();

    $this->db->query("SELECT FOUND_ROWS() as count");
    $pages_num = $this->db->result();
    $pages_num = $pages_num->count/$this->items_per_page;

    foreach($items as $key=>$item)
    {
       $items[$key]->move_up_get = $this->form_get(array('action'=>'move_up','item_id'=>$item->contact_id));
       $items[$key]->move_down_get = $this->form_get(array('action'=>'move_down','item_id'=>$item->contact_id));
       $items[$key]->edit_get = $this->form_get(array('section'=>'EditContact','item_id'=>$item->contact_id));
    }

  	$this->pages_navigation->pages_num = $pages_num;
  	$this->pages_navigation->fetch();
 	$this->smarty->assign('Items', $items);
    $this->smarty->assign('Modes', $this->modes);
  	$this->smarty->assign('PagesNavigation', $this->pages_navigation->body);
  	$this->smarty->assign('Lang', $this->lang);
 	$this->body = $this->smarty->fetch('contacts.tpl');
  }
}

############################################
# Class EditServiceSection - edit the static section
############################################
class EditContact extends Widget
{
  var $item;
  var $modes = array('ICQ'=>'ICQ', 'Tel'=>'Телефон', 'Email'=>'E-mail');
  function __construct(&$parent)
  {
    parent::__construct($parent);
    $this->add_param('page');
    $this->prepare();
  }

  function prepare()
  {
  	$item_id = intval($this->param('item_id'));
  	if(
  	   isset($_POST['name']) &&
  	   isset($_POST['number']) &&
  	   isset($_POST['mode']))
  	{
  		$this->item->name = $_POST['name'];
  		$this->item->number = $_POST['number'];
  		$this->item->mode = $_POST['mode'];

  		if(empty($this->item->name))
  		  $this->error_msg = $this->lang->ENTER_NAME;
        else
  		{
  			if(empty($item_id))
            {
  				$query = sql_placeholder('INSERT INTO contacts (name, number, mode) VALUES (?, ?, ?)',
  			                          $this->item->name,
  			                          $this->item->number,
  			                          $this->item->mode);
                $this->db->query($query);
                //echo $query.";";
	  			$inserted_id = $this->db->insert_id();

  				$query = sql_placeholder('UPDATE contacts SET order_num=contact_id WHERE contact_id=?',
  			                          $inserted_id);
  				$this->db->query($query);

            }
  			else
            {
  				$query = sql_placeholder('UPDATE contacts SET name=?, number=?, mode=? WHERE contact_id=?',
  			                          $this->item->name,
  			                          $this->item->number,
  			                          $this->item->mode,
  			                          $item_id);
                $this->db->query($query);
                //echo $query.";";
            }
 			$get = $this->form_get(array('section'=>'Contacts'));
  		    header("Location: index.php$get");
  		}
  	}

  	elseif (!empty($item_id))
  	{
  	  $query = sql_placeholder('SELECT * FROM contacts WHERE contact_id=?', $item_id);
  	  $this->db->query($query);
  	  $this->item = $this->db->result();
  	}
  }

  function fetch()
  {


  	  if(empty($this->item->contact_id))
  	    $this->title = $this->lang->NEW_CONTACT;
  	  else
  	    $this->title = $this->lang->EDIT_CONTACT."&laquo;".$this->item->contact_id."&raquo;";


      //print_r($this->item);
     // print_r($this->modes);

 	  $this->smarty->assign('Item', $this->item);
 	  $this->smarty->assign('Modes', $this->modes);
 	  $this->smarty->assign('ErrorMSG', $this->error_msg);
      $this->smarty->assign('Lang', $this->lang);
 	  $this->body = $this->smarty->fetch('contact.tpl');
  }
}