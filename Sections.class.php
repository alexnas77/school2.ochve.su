<?PHP

//require_once('Widget.class.php');

class Sections extends Widget
{
  function __construct(&$parent)
  {
    parent::__construct($parent);
  }


  function fetch()
  {
    # Current section
    $section_id = $this->param('section');
    $this->db->query("SELECT * FROM sections WHERE section_id='$section_id'");
    $section = $this->db->result();


    # Sections

    $this->db->query("SELECT * FROM sections WHERE menu_id=1 ORDER BY order_num DESC");
    $sections = $this->db->results();



    $this->smarty->assign('Section', $section);
    $this->smarty->assign('Sections', $sections);
    $this->body = $this->smarty->fetch('sections.tpl');

  }

}