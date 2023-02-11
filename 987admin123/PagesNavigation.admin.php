<?PHP

require_once('Widget.admin.php');

class PagesNavigation extends Widget
{
	var $pages_num;
	function __construct(&$parent)
	{
		parent::__construct($parent);
	}

	function fetch()
	{
		if($this->pages_num > 1)
		{
			$current_page = $this->param('page');
			for($i = 0; $i<$this->pages_num; $i++)
			{
				$get = $this->form_get(array('page'=>$i));
				$pages[$i] = "index.php$get";
			}
			$this->smarty->assign('Lang', $this->lang);
			$this->smarty->assign('Pages', $pages);
			$this->smarty->assign('CurrentPage', $current_page);
			$this->body = $this->smarty->fetch('pages_navigation.tpl');
		}
		else
		$this->body = '';
	}

}