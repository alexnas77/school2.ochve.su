<?PHP

//require_once('Widget.class.php');

class Mapsite extends Widget
{
  var $section_id=21;

  function __construct(&$parent)
  {
    parent::__construct($parent);
    $this->add_param('id');
    $this->add_param('page');
  }

  function fetch()
  {
    $mapsite_id = $this->param('id');
    if(!empty($mapsite_id) && is_numeric($mapsite_id))
      $this->fetch_item($mapsite_id);
    else
      $this->fetch_list();
  }

  function fetch_list()
  {
    # Документы
  	$query = "SELECT sections.*,
                      services.name as service
    				  FROM sections, services
    				  WHERE sections.service_id = services.service_id
    				  AND sections.inmap=1
    				  ORDER BY menu_id,order_num";
    $this->db->query($query);
    $mapsites = $this->db->results();

    #echo "<pre>".print_r($mapsites,true)."</pre>";

    $section_id = $this->param('section');
    $this->db->query("SELECT * FROM sections WHERE section_id = ".$this->section_id."");
    $section = $this->db->result();

    $this->title = $section->name;
    $this->keywords = $section->name;
    $this->description = $section->name;

    $this->smarty->assign('Mapsite', $mapsites);
    $this->smarty->assign('Section', $section);
    $this->body = $this->smarty->fetch('mapsite.tpl');
  }

 /* function fetch_item($id)
  {
    $query = "SELECT `pages`.* FROM `sections` LEFT JOIN `pages` ON `sections`.`material_id`=`pages`.`page_id` WHERE `sections`.`menu_id`='1' AND `pages`.`page_id`=$id";
    $this->db->query($query);
    $item = $this->db->result();
    $this->title = $item->title;
    $this->keywords = $item->keywords;
    $this->description = $item->description;
    $this->smarty->assign('Document', $item);
    $this->body = $this->smarty->fetch('mapsite.tpl');
  }*/

}