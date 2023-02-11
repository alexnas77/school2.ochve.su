<?PHP

require_once('Widget.admin.php');


############################################
# Class NewsLine displays news
############################################
class Sitemap extends Widget
{

  function __construct(&$parent)
  {
    parent::__construct($parent);
    $this->prepare();
  }

  function prepare()
  {
  	if(isset($_POST['sitemap']) && empty($_POST['act']))
  	{
  		$sitemap = $_POST['sitemap'];
        file_put_contents('../sitemap.xml', $sitemap);

  		$get = $this->form_get(array());
 		header("Location: index.php$get");
 	}
  }

  function fetch()
  {
    $this->title = $this->lang->GOOGLE_SITEMAP;
    $sitemap = file_get_contents('../sitemap.xml');

    if(isset($_POST['act']) && $_POST['act'] == 'generate')
    {
      ## Генерация
      $sitemap = '<?xml version="1.0" encoding="UTF-8"?>'."\r\n";
      $sitemap .='<urlset xmlns="http://www.google.com/schemas/sitemap/0.84"'."\r\n";

      $sitemap .= '<url>'."\r\n";
      $sitemap .= '<loc>'.$_SERVER['HTTP_HOST']."/</loc>\r\n";
      $sitemap .= '<changefreq>daily</changefreq>'."\r\n";
      $sitemap .= '<priority>1</priority>'."\r\n";
      $sitemap .= '</url>'."\r\n";

      #### Простые разделы
      $query = "SELECT * FROM sections WHERE menu_id!=0 order by order_num";
      $this->db->query($query);
      $sections = $this->db->results();

      foreach($sections as $section)
      {
        $sitemap .= '<url>'."\r\n";
        $sitemap .= '<loc>'.$_SERVER['HTTP_HOST']."/$section->url.html</loc>\r\n";
        $sitemap .= '<changefreq>daily</changefreq>'."\r\n";
        $sitemap .= '<priority>0.5</priority>'."\r\n";
        $sitemap .= '</url>'."\r\n";
      }

      #### Статьи
      $query = "SELECT * FROM articles order by order_num";
      $this->db->query($query);
      $articles = $this->db->results();
      if(!empty($articles))
      {
        $sitemap .= '<url>'."\r\n";
        $sitemap .= '<loc>'.$_SERVER['HTTP_HOST']."/articles/</loc>\r\n";
        $sitemap .= '<changefreq>daily</changefreq>'."\r\n";
        $sitemap .= '<priority>0.9</priority>'."\r\n";
        $sitemap .= '</url>'."\r\n";

        foreach($articles as $article)
        {
          $sitemap .= '<url>'."\r\n";
          $sitemap .= '<loc>'.$_SERVER['HTTP_HOST']."/articles/$article->article_id.html</loc>\r\n";
          $sitemap .= '<changefreq>daily</changefreq>'."\r\n";
          $sitemap .= '<priority>0.5</priority>'."\r\n";
          $sitemap .= '</url>'."\r\n";
        }
      }

      #### Новости
      $query = "SELECT * FROM news order by date";
      $this->db->query($query);
      $news = $this->db->results();
      if(!empty($news))
      {
        $sitemap .= '<url>'."\r\n";
        $sitemap .= '<loc>'.$_SERVER['HTTP_HOST']."/news/</loc>\r\n";
        $sitemap .= '<changefreq>daily</changefreq>'."\r\n";
        $sitemap .= '<priority>0.9</priority>'."\r\n";
        $sitemap .= '</url>'."\r\n";

        foreach($news as $news)
        {
          $sitemap .= '<url>'."\r\n";
          $sitemap .= '<loc>'.$_SERVER['HTTP_HOST']."/news/$news->news_id.html</loc>\r\n";
          $sitemap .= '<changefreq>daily</changefreq>'."\r\n";
          $sitemap .= '<priority>0.5</priority>'."\r\n";
          $sitemap .= '</url>'."\r\n";
        }
      }

      #### Категории товаров и товары
      $query = "SELECT * FROM categories order by order_num";
      $this->db->query($query);
      $categories = $this->db->results();
      if(!empty($categories))
      {
        $sitemap .= '<url>'."\r\n";
        $sitemap .= '<loc>'.$_SERVER['HTTP_HOST']."/catalog/</loc>\r\n";
        $sitemap .= '<changefreq>daily</changefreq>'."\r\n";
        $sitemap .= '<priority>0.9</priority>'."\r\n";
        $sitemap .= '</url>'."\r\n";

        foreach($categories as $category)
        {
          $sitemap .= '<url>'."\r\n";
          $sitemap .= '<loc>'.$_SERVER['HTTP_HOST']."/catalog/$category->category_id/</loc>\r\n";
          $sitemap .= '<changefreq>daily</changefreq>'."\r\n";
          $sitemap .= '<priority>0.5</priority>'."\r\n";
          $sitemap .= '</url>'."\r\n";

          ##### Товары
          $query = "SELECT * FROM products where category_id='$category->category_id'";
          $this->db->query($query);
          $products = $this->db->results();
          foreach($products as $product)
          {
            $sitemap .= '<url>'."\r\n";
            $sitemap .= '<loc>'.$_SERVER['HTTP_HOST']."/catalog/$category->category_id/{$product->brand}/{$product->product_id}.html</loc>\r\n";
            $sitemap .= '<changefreq>daily</changefreq>'."\r\n";
            $sitemap .= '<priority>0.5</priority>'."\r\n";
            $sitemap .= '</url>'."\r\n";
          }

        }
      }


      $sitemap .= '</urlset>';

    }

  	$this->smarty->assign('Sitemap', $sitemap);
 	$this->body = $this->smarty->fetch('sitemap.tpl');
  }
}