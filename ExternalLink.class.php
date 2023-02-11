<?PHP

//require_once('Widget.class.php');

class ExternalLink extends Widget
{
  function __construct(&$parent)
  {
    parent::__construct($parent);
  }


  function fetch()
  {
    $section_id = $this->param('section');
    $this->db->query("SELECT external_links.* FROM sections, external_links WHERE sections.material_id = external_links.external_link_id AND sections.url = '$section_id'");
    $link = $this->db->result();
    header("Location: ".$link->url);
  }
}