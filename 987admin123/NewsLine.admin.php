<?PHP

require_once('Widget.admin.php');
require_once('PagesNavigation.admin.php');


############################################
# Class NewsLine displays news
############################################
class NewsLine extends Widget
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
  		$query = "DELETE FROM news
 		          WHERE news.news_id IN ('$items_sql')";
  		$this->db->query($query);
  		$get = $this->form_get(array());
 		header("Location: index.php$get");
 	}
  }

  function fetch()
  {
    $this->title = $this->lang->NEWS;
  	$current_page = $this->param('page');
  	$start_item = $current_page*$this->items_per_page;
    $this->db->query("SELECT SQL_CALC_FOUND_ROWS *,
                      DATE_FORMAT(date, '%d.%m.%Y') as date2
    				  FROM news WHERE domain='$this->domain'
    				  ORDER BY date DESC
    				  LIMIT $start_item ,$this->items_per_page");
  	$items = $this->db->results();

    $this->db->query("SELECT FOUND_ROWS() as count");
    $pages_num = $this->db->result();
    $pages_num = $pages_num->count/$this->items_per_page;

    foreach($items as $key=>$item)
    {
       $items[$key]->edit_get = $this->form_get(array('section'=>'EditNewsItem','item_id'=>$item->news_id));
    }

  	$this->pages_navigation->pages_num = $pages_num;
  	$this->pages_navigation->fetch();
 	$this->smarty->assign('Items', $items);
  	$this->smarty->assign('PagesNavigation', $this->pages_navigation->body);
  	$this->smarty->assign('Lang', $this->lang);
 	$this->body = $this->smarty->fetch('news.tpl');
  }
}

############################################
# Class EditServiceSection - edit the static section
############################################
class EditNewsItem extends Widget
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
  	if(isset($_POST['date']) &&
  	   isset($_POST['title']) &&
  	   isset($_POST['keywords']) &&
  	   isset($_POST['description']) &&
  	   isset($_POST['annotation']) &&
  	   isset($_POST['body']))
  	{
  		$this->item->date = $_POST['date'];
  		$this->item->title = $_POST['title'];
  		$this->item->keywords = $_POST['keywords'];
  		$this->item->description = $_POST['description'];
  		$this->item->annotation = $_POST['annotation'];
  		$this->item->body = $_POST['body'];

  		if(empty($this->item->title))
  		  $this->error_msg = $this->lang->ENTER_TITLE;
        else
  		{
  			if(empty($item_id))
  			$query = sql_placeholder('INSERT INTO news(news_id, date, title, keywords, description, annotation, body, domain) VALUES(NULL, STR_TO_DATE(?, "%d.%m.%Y"), ?, ?, ?, ?, ?, ?)',
  			                          $this->item->date,
  			                          $this->item->title,
  			                          $this->item->keywords,
  			                          $this->item->description,
  			                          $this->item->annotation,
  			                          $this->item->body,
  			                          $this->domain);
  			else
  			$query = sql_placeholder('UPDATE news SET date=STR_TO_DATE(?, "%d.%m.%Y"), title=?, keywords=?, description=?, annotation=?, body=?, domain=? WHERE news_id=?',
  			                          $this->item->date,
  			                          $this->item->title,
  			                          $this->item->keywords,
  			                          $this->item->description,
  			                          $this->item->annotation,
  			                          $this->item->body,
  			                          $this->domain,
  			                          $item_id);

  			$this->db->query($query);
 			$get = $this->form_get(array('section'=>'NewsLine'));
  		    header("Location: index.php$get");
  		}
  	}

  	elseif (!empty($item_id))
  	{
  	  $query = sql_placeholder('SELECT *, DATE_FORMAT(date, "%d.%m.%Y") as date2 FROM news WHERE news_id=?', $item_id);
  	  $this->db->query($query);
  	  $this->item = $this->db->result();
  	}
  }

  function fetch()
  {
  	  if(empty($this->item->news_id))
  	    $this->title = $this->lang->NEW_NEWS_ITEM;
  	  else
  	    $this->title = $this->lang->EDIT_NEWS_ITEM;

 	  $this->smarty->assign('Item', $this->item);
 	  $this->smarty->assign('ErrorMSG', $this->error_msg);
      $this->smarty->assign('Lang', $this->lang);
 	  $this->body = $this->smarty->fetch('news_item.tpl');
  }
}