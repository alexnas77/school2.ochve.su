<?PHP

require_once('Widget.admin.php');

class MainPage extends Widget
{
  var $menu;
  function __construct(&$parent)
  {
		parent::__construct($parent);
  }

  function fetch()
  {
  	$this->db->query("SELECT * FROM menu WHERE menu_id > 0 order by menu_id");
  	$menu = $this->db->results();
  	$this->smarty->assign('MenuList', $menu);
  	$this->smarty->assign('Lang', $this->lang);
 	$this->body=$this->smarty->fetch('main_page.tpl');
  }
}