<?PHP

//require_once('Widget.class.php');

class Categories extends Widget
{
  function __construct(&$parent)
  {
    parent::__construct($parent);
  }


  function fetch()
  {
    # Current category
    $category_id = intval($this->param('category'));
    $this->db->query("SELECT * FROM categories WHERE category_id='$category_id'");
    $category = $this->db->result();


    # All categories
    $this->db->query("SELECT cats.* FROM goods, categories cats LEFT JOIN categories subcats
                      ON cats.category_id = subcats.parent
                      WHERE (cats.category_id = goods.category_id OR subcats.category_id = goods.category_id)
                      AND cats.parent=0
                      GROUP BY cats.category_id ORDER BY cats.order_num DESC");
    $categories = $this->db->results();

    #subcategories
    $this->db->query("SELECT * FROM categories WHERE parent='$category->category_id' OR (parent='$category->parent' AND parent > 0) ORDER BY order_num DESC");
    $subcategories = $this->db->results();

    $this->smarty->assign('Category', $category);
    $this->smarty->assign('Categories', $categories);
    $this->smarty->assign('Subcategories', $subcategories);
    $this->body = $this->smarty->fetch('categories.tpl');

  }

}