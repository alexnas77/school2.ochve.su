<?PHP

//require_once('Widget.class.php');

class Contacts extends Widget
{

  var $modes = array('ICQ'=>'ICQ', 'Tel'=>'Телефон', 'Email'=>'E-mail', 'Scype'=>'Scype');

  function __construct(&$parent)
  {
    parent::__construct($parent);
    $this->add_param('page');
    $this->section_id = 15;
  }


  function fetch()
  {

    $this->db->query("SELECT contacts.* FROM contacts
                     WHERE contacts.mode='ICQ'
                     ORDER BY contacts.order_num");
    $ICQs = $this->db->results();

    $this->db->query("SELECT contacts.* FROM contacts
                     WHERE contacts.mode='Tel'
                     ORDER BY contacts.order_num");
    $Tels = $this->db->results();

    $this->db->query("SELECT contacts.* FROM contacts
                     WHERE contacts.mode='Email'
                     ORDER BY contacts.order_num");
    $Emails = $this->db->results();

    $this->db->query("SELECT contacts.* FROM contacts
                     WHERE contacts.mode='Scype'
                     ORDER BY contacts.order_num");
    $Scypes = $this->db->results();
    $section_id = $this->param('section');
    $this->db->query("SELECT * FROM sections WHERE section_id = '$section_id'");
    $section = $this->db->result();

    $this->title = $section->name;
    $this->keywords = $section->name;
    $this->description = $section->name;

    //print_r($this->modes);

    $this->smarty->assign('ICQs', $ICQs);
    $this->smarty->assign('Tels', $Tels);
    $this->smarty->assign('Emails', $Emails);
    $this->smarty->assign('Scypes', $Scypes);
    $this->smarty->assign('Section', $section);
    $this->body = $this->smarty->fetch('contacts.tpl');
  }


}