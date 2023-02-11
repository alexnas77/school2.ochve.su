<?PHP

//require_once('Widget.class.php');

class NewsLine extends Widget
{
  function __construct(&$parent)
  {
    parent::__construct($parent);
    $this->add_param('news_id');
    $this->add_param('page');
  }

  function fetch()
  {
    $news_id = $this->param('news_id');
    if(!empty($news_id) && is_numeric($news_id))
      $this->fetch_item($news_id);
    else
      $this->fetch_list();
  }

  function fetch_list()
  {
    $this->db->query("SELECT *, DATE_FORMAT(date, '%d.%m.%Y') as dt FROM news WHERE domain='$this->domain' ORDER BY date DESC");
    $news = $this->db->results();

    $section_id = $this->param('section');
    $this->db->query("SELECT * FROM sections WHERE section_id = '$section_id'");
    $section = $this->db->result();

    $this->title = $section->name;
    $this->keywords = $section->name;
    $this->description = $section->name;

    $this->smarty->assign('News', $news);
    $this->smarty->assign('Section', $section);
    $this->body = $this->smarty->fetch('news.tpl');
  }

  function fetch_item($id)
  {

    $this->db->query("SELECT *, DATE_FORMAT(date, '%d.%m.%Y') as dt FROM news WHERE news_id = $id");
    $item = $this->db->result();
    $this->title = $item->title;
    $this->keywords = $item->keywords;
    $this->description = $item->description;
    $this->smarty->assign('NewsItem', $item);
    $this->body = $this->smarty->fetch('news_item.tpl');
  }

}