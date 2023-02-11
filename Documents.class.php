<?PHP

//require_once('Widget.class.php');

class Documents extends Widget
{
  var $section_id=18;

  function __construct(&$parent)
  {
    parent::__construct($parent);
    $this->add_param('id');
    $this->add_param('page');
  }

  function fetch()
  {
    $document_id = $this->param('id');
    if(!empty($document_id) && is_numeric($document_id))
      $this->fetch_item($document_id);
    else
      $this->fetch_list();
  }

  function fetch_list()
  {
    # Документы
 	$query = "SELECT `pages`.* FROM `sections` LEFT JOIN `pages` ON `sections`.`material_id`=`pages`.`page_id` WHERE `sections`.`menu_id`='1' ORDER BY order_num";
    $this->db->query($query);
    $documents = $this->db->results();

    #echo "<pre>".print_r($documents,true)."</pre>";

    $section_id = $this->param('section');
    $this->db->query("SELECT * FROM sections WHERE section_id = ".$this->section_id."");
    $section = $this->db->result();

    $this->title = $section->name;
    $this->keywords = $section->name;
    $this->description = $section->name;

    $this->smarty->assign('Documents', $documents);
    $this->smarty->assign('Section', $section);
    $this->body = $this->smarty->fetch('documents.tpl');
  }

  function fetch_item($id)
  {
    $query = "SELECT `pages`.* FROM `sections` LEFT JOIN `pages` ON `sections`.`material_id`=`pages`.`page_id` WHERE `sections`.`menu_id`='1' AND `pages`.`page_id`=$id";
    $this->db->query($query);
    $item = $this->db->result();
    $this->title = $item->title;
    $this->keywords = $item->keywords;
    $this->description = $item->description;
    $this->smarty->assign('Document', $item);
    $this->body = $this->smarty->fetch('document.tpl');
  }

}