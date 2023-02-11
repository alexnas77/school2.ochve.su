<?PHP

//require_once('Widget.class.php');

class ProductFoto extends Widget
{
  function __construct(&$parent)
  {
    parent::__construct($parent);
    $this->add_param('product_id');
  }

  function fetch()
  {
    $product_id = $this->param('product_id');
    $this->db->query("SELECT * FROM products WHERE product_id = '$product_id'");
    $product = $this->db->result();
    $this->db->query("SELECT * FROM products_fotos WHERE product_id = '$product_id'");
    $fotos = $this->db->results();

    $this->title = "Фотографии ".$product->brand." ".$product->model;
    $this->keywords = "Фотографии ".$product->brand." ".$product->model;
    $this->description = "Фотографии ".$product->brand." ".$product->model;

    $this->smarty->assign('Product', $product);
    $this->smarty->assign('Fotos', $fotos);
    $this->body = $this->smarty->fetch('product_foto.tpl');
  }

}