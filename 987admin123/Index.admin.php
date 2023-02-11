<?PHP

//error_reporting(E_ALL ^E_NOTICE);

//error_reporting(0);

set_time_limit(0);

require_once('Widget.admin.php');
require_once('MainPage.admin.php');
require_once('Page.admin.php');

class Index extends Widget
{
	var $page;
	function __construct($parent)
	{
		parent::__construct($parent);
		$this->add_param('section');
		$section = $this->param('section');
		if (!$_SESSION['is_loged']){
		$section = 'Login';
  		}
		if(empty($section) || !class_exists($section))
		$this->page = new MainPage($this);
		else
	   	$this->page = new Page($this);
	}

	function fetch()
	{
		$this->page->fetch();
		$this->body = $this->page->body;
		$this->db->disconnect();
	}
}