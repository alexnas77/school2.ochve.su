<?PHP

//require_once('Widget.class.php');

class Articles extends Widget
{
  function __construct(&$parent)
  {
    parent::__construct($parent);
    $this->add_param('article_id');
    $this->add_param('page');
  }

  function fetch()
  {
    $article_id = $this->param('article_id');
    if(!empty($article_id) && is_numeric($article_id))
      $this->fetch_item($article_id);
    else
      $this->fetch_list();
  }

  function fetch_list()
  {
    $this->db->query("SELECT * FROM articles WHERE domain='$this->domain' ORDER BY order_num DESC");
    $articles = $this->db->results();

    $section_id = $this->param('section');
    $this->db->query("SELECT * FROM sections WHERE section_id = '$section_id'");
    $section = $this->db->result();

    $this->title = $section->name;
    $this->keywords = $section->name;
    $this->description = $section->name;

    $this->smarty->assign('Articles', $articles);
    $this->smarty->assign('Section', $section);
    $this->body = $this->smarty->fetch('articles.tpl');
  }

  function fetch_item($id)
  {

    $this->db->query("SELECT * FROM articles WHERE article_id = $id");
    $item = $this->db->result();
    $this->title = $item->title;
    $this->keywords = $item->keywords;
    $this->description = $item->description;
    $this->smarty->assign('Article', $item);
    $this->body = $this->smarty->fetch('article.tpl');
  }

}