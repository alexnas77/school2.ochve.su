<?PHP

require_once('Widget.class.php');

class Headline extends Widget
{
  function __construct(&$parent)
  {
		parent::__construct($parent);
  }
  
  function fetch()
  {
    $section_id = $this->param('section');
    $this->db->query("SELECT * FROM sections");
    $sections = $this->db->results();
    $this->smarty->assign('Sections', $sections);
    $this->body = $this->smarty->fetch('menu.tpl');
  }

}