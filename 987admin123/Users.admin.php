<?PHP

require_once('Widget.admin.php');
require_once('PagesNavigation.admin.php');


############################################
# Class NewsLine displays news
############################################
class Users extends Widget
{
  var $pages_navigation;
  var $items_per_page = 20;
  function __construct(&$parent)
  {
    parent::__construct($parent);
    $this->add_param('page');
    $this->add_param('name');
    $this->add_param('login');
    $this->add_param('category_id');

    $this->pages_navigation = new PagesNavigation($this);
    $this->prepare();
  }

  function prepare()
  {
    if(isset($_GET['enable']))
    {
        $login = $_GET['enable'];
  		$query = sql_placeholder('UPDATE users
 		          SET active=1
                  WHERE login=?', $login);
  		$this->db->query($query);
    }
    if(isset($_GET['disable']))
    {
        $login = $_GET['disable'];
  		$query = sql_placeholder('UPDATE users
 		          SET active=0
                  WHERE login=?', $login);
  		$this->db->query($query);
    }

  	if(isset($_POST['items']))
  	{
  		$items = $_POST['items'];
  		if(is_array($items))
  		  $items_sql = implode("', '", $items);
  		else
  		  $items_sql = $items;
  		$query = "DELETE FROM users
 		          WHERE users.login IN ('$items_sql')";
  		$this->db->query($query);
  		$get = $this->form_get(array());
 		header("Location: index.php$get");
 	}
  	if(isset($_POST['category']) && $_POST['SUBMIT'] == $this->lang->SAVE_CHANGES)
  	{
  		$categories = $_POST['category'];
  		foreach ($categories as $k=>$v)
  		{
  		$query = "UPDATE `users` SET `category_id`='$v' WHERE `login` = '$k'";
  		$this->db->query($query);
  		}
  		$get = $this->form_get(array());
 		header("Location: index.php$get");
 	}
  }

  function fetch()
  {
    $this->title = $this->lang->USERS;
  	$current_page = $this->param('page');
  	$start_item = $current_page*$this->items_per_page;

    $name = $this->param('name');
    $login = $this->param('login');
    $category_id = $this->param('category_id');
    $filter = '1';
    if(!empty($name))
      $filter .= " AND users.name LIKE '%$name%' ";
    if(!empty($login))
      $filter .= " AND users.login LIKE '%$login%' ";
    if(!empty($category_id))
      $filter .= " AND users.category_id = '$category_id' ";

    $this->db->query("SELECT SQL_CALC_FOUND_ROWS users.*,
                      ucategories.name as category,
                      COUNT(orders.order_id) as orders_num
    				  FROM users LEFT JOIN ucategories
                      ON ucategories.category_id = users.category_id
    				  LEFT JOIN orders
                      ON orders.login = users.login
                      WHERE $filter
                      GROUP BY users.login
    				  ORDER BY orders.date DESC
    				  LIMIT $start_item ,$this->items_per_page");
  	$items = $this->db->results();

    $this->db->query("SELECT FOUND_ROWS() as count");
    $pages_num = $this->db->result();
    $pages_num = $pages_num->count/$this->items_per_page;

    foreach($items as $key=>$item)
    {
       $items[$key]->edit_get = $this->form_get(array('section'=>'EditUser','login'=>$item->login));
       $items[$key]->enable_get = $this->form_get(array('enable'=>$item->login));
       $items[$key]->disable_get = $this->form_get(array('disable'=>$item->login));
    }

    $query = 'SELECT * FROM ucategories';
    $this->db->query($query);
    $categories = $this->db->results();


  	$this->pages_navigation->pages_num = $pages_num;
  	$this->pages_navigation->fetch();
	$this->smarty->assign('Items', $items);
	$this->smarty->assign('Categories', $categories);
  	$this->smarty->assign('PagesNavigation', $this->pages_navigation->body);
  	$this->smarty->assign('Lang', $this->lang);
 	$this->body = $this->smarty->fetch('users.tpl');
  }
}

############################################
# Class EditServiceSection - edit the static section
############################################
class EditUser extends Widget
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
  	$login = $this->param('login');
  	$query = sql_placeholder('SELECT *  FROM users WHERE login=?', $login);
    $this->db->query($query);
    $this->item = $this->db->result();
  	if(isset($_POST['name']) &&
  	   isset($_POST['category_id']))
  	{
  		$this->item->name = $_POST['name'];
  		if ($_POST['password'] == $_POST['password2'] and
  		$_POST['password'] != '')
  		$this->item->password = md5($_POST['password']);
  		$this->item->category_id = $_POST['category_id'];
  		$this->item->active = 0;
        if(isset($_POST['active']))
          $this->item->active = $_POST['active'];
        $this->item->mail = $_POST['mail'];

        if ($_POST['password'] != $_POST['password2'])
        $this->error_msg = 'Пароль не совпадает с подтверждением';

          //echo($_POST['password']);

  		$query = sql_placeholder('UPDATE users SET name=?, password=?, category_id=?, mail=?, active=? WHERE login=?',
  			                          $this->item->name,
  			                          $this->item->password,
  			                          $this->item->category_id,
  			                          $this->item->mail,
  			                          $this->item->active,
  			                          $login);

        if(empty($this->error_msg)){
  		$this->db->query($query);
 		$get = $this->form_get(array('section'=>'Users'));
  		header("Location: index.php$get");
        }
  	}


  }

  function fetch()
  {
      $this->title = $this->lang->EDIT_USER;

      $this->db->query("SELECT * FROM ucategories ORDER BY discount");
      $categories = $this->db->results();

      $this->smarty->assign('Categories', $categories);
      $this->smarty->assign('Item', $this->item);
 	  $this->smarty->assign('ErrorMSG', $this->error_msg);
 	  $this->smarty->assign('EditorAnnotation', $this->editor_annotation->body);
 	  $this->smarty->assign('EditorBody', $this->editor_body->body);
      $this->smarty->assign('Lang', $this->lang);
 	  $this->body = $this->smarty->fetch('user.tpl');
  }
}




############################################
# Class NewsLine displays news
############################################
class UsersCategories extends Widget
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
  		$query = "DELETE FROM ucategories
 		          WHERE ucategories.category_id IN ('$items_sql')";
  		$this->db->query($query);
  		$get = $this->form_get(array());
 		header("Location: index.php$get");
 	}
  }

  function fetch()
  {
    $this->title = $this->lang->USERS_CATEGORIES;
  	$current_page = $this->param('page');
  	$start_item = $current_page*$this->items_per_page;
    $this->db->query("SELECT SQL_CALC_FOUND_ROWS *
    				  FROM ucategories
    				  ORDER BY discount
    				  LIMIT $start_item ,$this->items_per_page");
  	$items = $this->db->results();

    $this->db->query("SELECT FOUND_ROWS() as count");
    $pages_num = $this->db->result();
    $pages_num = $pages_num->count/$this->items_per_page;
    if($items)
    foreach($items as $key=>$item)
    {
       $items[$key]->edit_get = $this->form_get(array('section'=>'EditUsersCategory','item_id'=>$item->category_id));
    }

  	$this->pages_navigation->pages_num = $pages_num;
  	$this->pages_navigation->fetch();
 	$this->smarty->assign('Items', $items);
  	$this->smarty->assign('PagesNavigation', $this->pages_navigation->body);
  	$this->smarty->assign('Lang', $this->lang);
 	$this->body = $this->smarty->fetch('users_categories.tpl');
  }
}

############################################
# Class EditServiceSection - edit the static section
############################################
class EditUsersCategory extends Widget
{
  var $item;
  function __construct(&$parent)
  {
    parent::__construct($parent);
    $this->add_param('page');
    $this->add_param('item_id');
    $this->prepare();
  }

  function prepare()
  {
  	$item_id = intval($this->param('item_id'));
  	if(isset($_POST['name']))
  	{
  		$this->item->name = $_POST['name'];
  		$this->item->discount = intval($_POST['discount']);
  		$this->item->isadmin = intval($_POST['isadmin']);
  		if(isset($_POST['news']))
  		$this->item->news = $this->item->isadmin ? $_POST['news'] : 0;
  		else
  		$this->item->news = 0;
  		if(isset($_POST['articles']))
        $this->item->articles = $this->item->isadmin ? $_POST['articles'] : 0;
  		else
  		$this->item->articles = 0;
  		if(isset($_POST['polls']))
        $this->item->polls = $this->item->isadmin ? $_POST['polls'] : 0;
  		else
  		$this->item->polls = 0;
  		if(isset($_POST['settings']))
        $this->item->settings = $this->item->isadmin ? $_POST['settings'] : 0;
  		else
  		$this->item->settings = 0;
  		if(isset($_POST['contacts']))
        $this->item->contacts = $this->item->isadmin ? $_POST['contacts'] : 0;
  		else
  		$this->item->contacts = 0;
  		if(isset($_POST['products']))
        $this->item->products = $this->item->isadmin ? $_POST['products'] : 0;
  		else
  		$this->item->products = 0;
  		if(isset($_POST['properties']))
        $this->item->properties = $this->item->isadmin ? $_POST['properties'] : 0;
  		else
  		$this->item->properties = 0;
  		if(isset($_POST['orders']))
        $this->item->orders = $this->item->isadmin ? $_POST['orders'] : 0;
  		else
  		$this->item->orders = 0;
  		if(isset($_POST['pricelist']))
        $this->item->pricelist = $this->item->isadmin ? $_POST['pricelist'] : 0;
  		else
  		$this->item->pricelist = 0;
  		if(isset($_POST['currencies']))
        $this->item->currencies = $this->item->isadmin ? $_POST['currencies'] : 0;
  		else
  		$this->item->currencies = 0;
  		if(isset($_POST['statistics']))
        $this->item->statistics = $this->item->isadmin ? $_POST['statistics'] : 0;
  		else
  		$this->item->statistics = 0;
  		if(isset($_POST['users']))
        $this->item->users = $this->item->isadmin ? $_POST['users'] : 0;
  		else
  		$this->item->users = 0;
  		if(isset($_POST['usercomments']))
        $this->item->usercomments = $this->item->isadmin ? $_POST['usercomments'] : 0;
  		else
  		$this->item->usercomments = 0;
  		if(isset($_POST['faq']))
        $this->item->faq = $this->item->isadmin ? $_POST['faq'] : 0;
  		else
  		$this->item->faq = 0;
  		if(isset($_POST['announcement']))
        $this->item->announcement = $this->item->isadmin ? $_POST['announcement'] : 0;
  		else
  		$this->item->announcement = 0;

  		if(empty($this->item->name))
  		  $this->error_msg = $this->lang->ENTER_NAME;
        else
  		{
  			if(empty($item_id))
  			$query = sql_placeholder('INSERT INTO `ucategories` (`category_id`, `name`, `discount`, `isadmin`, `news`, `articles`, `polls`, `settings`, `contacts`, `products`, `properties`, `orders`, `pricelist`, `currencies`, `statistics`, `users`, `usercomments`, `faq`, `announcement`) VALUES (NULL,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
  			                          $this->item->name,
  			                          $this->item->discount,
  			                          $this->item->isadmin,
  			                          $this->item->news,
  			                          $this->item->articles,
  			                          $this->item->polls,
  			                          $this->item->settings,
  			                          $this->item->contacts,
  			                          $this->item->products,
  			                          $this->item->properties,
  			                          $this->item->orders,
  			                          $this->item->pricelist,
  			                          $this->item->currencies,
  			                          $this->item->statistics,
  			                          $this->item->users,
  			                          $this->item->usercomments,
  			                          $this->item->faq,
  			                          $this->item->announcement
  			                          );
  			else
  			$query = sql_placeholder('UPDATE `ucategories` SET `name`=?, `discount`=?, `isadmin`=?, `news`=?, `articles`=?, `polls`=?, `settings`=?, `contacts`=?, `products`=?, `properties`=?, `orders`=?, `pricelist`=?, `currencies`=?, `statistics`=?, `users`=?, `usercomments`=?, `faq`=?, `announcement`=? where `category_id`=?',
  			                          $this->item->name,
  			                          $this->item->discount,
  			                          $this->item->isadmin,
  			                          $this->item->news,
  			                          $this->item->articles,
  			                          $this->item->polls,
  			                          $this->item->settings,
  			                          $this->item->contacts,
  			                          $this->item->products,
  			                          $this->item->properties,
  			                          $this->item->orders,
  			                          $this->item->pricelist,
  			                          $this->item->currencies,
  			                          $this->item->statistics,
  			                          $this->item->users,
  			                          $this->item->usercomments,
  			                          $this->item->faq,
  			                          $this->item->announcement,
  			                          $item_id);

  			$this->db->query($query);
            //echo "$query<br />\n";
 			$get = $this->form_get(array('section'=>'UsersCategories'));
  		    header("Location: index.php$get");
  		}
  	}

  	elseif (!empty($item_id))
  	{
  	  $query = sql_placeholder('SELECT * FROM ucategories WHERE category_id=?', $item_id);
  	  $this->db->query($query);
  	  $this->item = $this->db->result();
  	}
  }

  function fetch()
  {
  	  if(empty($this->item->category_id))
  	    $this->title = $this->lang->NEW_USERS_CATEGORY;
  	  else
  	    $this->title = $this->lang->EDIT_USERS_CATEGORY;

 	  $this->smarty->assign('Item', $this->item);
 	  $this->smarty->assign('ErrorMSG', $this->error_msg);
      $this->smarty->assign('Lang', $this->lang);
 	  $this->body = $this->smarty->fetch('users_category.tpl');
  }
}