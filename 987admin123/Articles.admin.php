<?PHP

require_once('Widget.admin.php');
require_once('PagesNavigation.admin.php');


############################################
# Class NewsLine displays news
############################################
class Articles extends Widget
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
  	if(isset($_POST['items']) && $_POST['act']=='delete')
  	{
  		$items = $_POST['items'];
  		if(is_array($items))
  		  $items_sql = implode("', '", $items);
  		else
  		  $items_sql = $items;
  		$query = "DELETE FROM articles
 		          WHERE articles.article_id IN ('$items_sql')";
 		echo "$query<br />\n";
  		$this->db->query($query);
  		$get = $this->form_get(array());
 		header("Location: index.php$get");
 	}
  	if($_POST['act']=='active')
  	{
  		$enabled = $_POST['enabled'];
  		$main = $_POST['main'];
  		$ids = $_POST['ids'];
        foreach ($ids as $ik=>$id)
        {        if(in_array($ik,$enabled))
        {
  		$query = "UPDATE articles SET enabled='1' WHERE article_id = '$ik'";
  		//echo "$query<br />\n";
  		$this->db->query($query);
  		}
  		else
  		{
  		$query = "UPDATE articles SET enabled='0' WHERE article_id = '$ik'";
  		//echo "$query<br />\n";
  		$this->db->query($query);
  		}

        if(in_array($ik,$main))
        {
  		$query = "UPDATE articles SET main='1' WHERE article_id = '$ik'";
  		//echo "$query<br />\n";
  		$this->db->query($query);
  		}
  		else
  		{
  		$query = "UPDATE articles SET main='0' WHERE article_id = '$ik'";
  		//echo "$query<br />\n";
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
  		                  FROM articles a1, articles a2
  		                  WHERE a1.order_num>a2.order_num
  		                  AND a2.article_id = '$item_id'
  		                  AND a1.domain='$this->domain'
  		                  ORDER BY a1.order_num ASC
  		                  LIMIT 1");
  		$this->db->query("UPDATE articles a1, articles a2
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
  		                  FROM articles a1, articles a2
  		                  WHERE a1.order_num<a2.order_num
  		                  AND a2.article_id = '$item_id'
  		                  AND a1.domain='$this->domain'
  		                  ORDER BY a1.order_num DESC
  		                  LIMIT 1");
  		$this->db->query("UPDATE articles a1, articles a2
  		                  SET a1.order_num = (@a:=a1.order_num)*0+a2.order_num, a2.order_num = @a
  		                  WHERE a1.article_id = '$item_id'
  		                  AND a2.article_id = @id");
  		$get = $this->form_get(array());
  		header("Location: index.php$get");
  	}

  }

  function fetch()
  {
    $this->title = '”правление стать€ми';
  	$current_page = $this->param('page');
  	$start_item = $current_page*$this->items_per_page;
    $this->db->query("SELECT SQL_CALC_FOUND_ROWS *
    				  FROM articles WHERE domain='$this->domain'
    				  ORDER BY order_num DESC
    				  LIMIT $start_item ,$this->items_per_page");
  	$items = $this->db->results();

    $this->db->query("SELECT FOUND_ROWS() as count");
    $pages_num = $this->db->result();
    $pages_num = $pages_num->count/$this->items_per_page;

    foreach($items as $key=>$item)
    {
       $items[$key]->move_up_get = $this->form_get(array('action'=>'move_up','item_id'=>$item->article_id));
       $items[$key]->move_down_get = $this->form_get(array('action'=>'move_down','item_id'=>$item->article_id));
       $items[$key]->edit_get = $this->form_get(array('section'=>'EditArticle','item_id'=>$item->article_id));
    }

  	$this->pages_navigation->pages_num = $pages_num;
  	$this->pages_navigation->fetch();
 	$this->smarty->assign('Items', $items);
  	$this->smarty->assign('PagesNavigation', $this->pages_navigation->body);
  	$this->smarty->assign('Lang', $this->lang);
 	$this->body = $this->smarty->fetch('articles.tpl');
  }
}

############################################
# Class EditServiceSection - edit the static section
############################################
class EditArticle extends Widget
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
  	if(
  	   isset($_POST['title']) &&
  	   isset($_POST['keywords']) &&
  	   isset($_POST['description']) &&
  	   isset($_POST['annotation']) &&
  	   isset($_POST['body']))
  	{
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
            {
  				$query = sql_placeholder('INSERT INTO articles(article_id, title, keywords, description, annotation, body, domain) VALUES(NULL, ?, ?, ?, ?, ?, ?)',
  			                          $this->item->title,
  			                          $this->item->keywords,
  			                          $this->item->description,
  			                          $this->item->annotation,
  			                          $this->item->body,
  			                          $this->domain);
                $this->db->query($query);
	  			$inserted_id = $this->db->insert_id();

  				$query = sql_placeholder('UPDATE articles SET order_num=article_id WHERE article_id=? AND domain=?',
  			                          $inserted_id,$this->domain);
  				$this->db->query($query);

            }
  			else
            {
  				$query = sql_placeholder('UPDATE articles SET title=?, keywords=?, description=?, annotation=?, body=? WHERE article_id=? AND domain=?',
  			                          $this->item->title,
  			                          $this->item->keywords,
  			                          $this->item->description,
  			                          $this->item->annotation,
  			                          $this->item->body,
  			                          $item_id,
  			                          $this->domain);
                $this->db->query($query);
            }
 			$get = $this->form_get(array('section'=>'Articles'));
  		    header("Location: index.php$get");
  		}
  	}

  	elseif (!empty($item_id))
  	{
  	  $query = sql_placeholder('SELECT * FROM articles WHERE article_id=?', $item_id);
  	  $this->db->query($query);
  	  $this->item = $this->db->result();
  	}
  }

  function fetch()
  {
  	  if(empty($this->item->article_id))
  	    $this->title = $this->lang->NEW_ARTICLE;
  	  else
  	    $this->title = $this->lang->EDIT_ARTICLE;

 	  $this->smarty->assign('Item', $this->item);
 	  $this->smarty->assign('ErrorMSG', $this->error_msg);
      $this->smarty->assign('Lang', $this->lang);
 	  $this->body = $this->smarty->fetch('article.tpl');
  }
}