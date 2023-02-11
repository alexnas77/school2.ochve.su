<?PHP

//require_once('Widget.class.php');

class Portfolio extends Widget
{
  var $files=array();
  var $sites=array();
  var $items_per_page = 4;
  var $big_height = 410;
  var $small_height = 97;
  var $section_id = 16;
  var $uploaddir = 'foto/portfolio/';
  function __construct(&$parent)
  {
    parent::__construct($parent);
    $this->add_param('item_id');
    $this->add_param('page');
  }

  function fetch()
  {
    $item_id = intval($this->param('item_id'));

    $this->title = 'Портфолио';

    if ($handle = opendir($this->uploaddir)) {

    /* Именно этот способ чтения элементов каталога является правильным. */
     while (false !== ($file = readdir($handle))) {

      if($file!='.' && $file!='..' )
      {
      $this->files[]=$file;
      $this->sites[]=preg_replace('/\.[0-9a-zA-Z]+$/is','',$file);
      }
     }

    sort($this->files);
    sort($this->sites);

    closedir($handle);
    }

    $current_page = intval($item_id/($this->items_per_page));

    #print_r($this->files);
    #echo "<br /><br />\n";
    #print_r($this->sites);

    $foto = $this->uploaddir.$this->files[$item_id];

    $start_item = ($current_page)*$this->items_per_page;
    $end_item = $start_item + $this->items_per_page;

    for($i=$start_item,$j=0;$i<$end_item;$i++,$j++)
    {    	$fotos[$j] = $this->uploaddir.$this->files[$i];
    	$url[$j] = 'index.php'.$this->form_get(array('item_id'=>$i, 'page'=>''));;    }

    if(isset($this->files[$item_id-1]))
    $url_left = 'index.php'.$this->form_get(array('item_id'=>$item_id-1, 'page'=>''));
    else
    $url_left = '';

    //else
    //$url_left = 'index.php'.$this->form_get(array('item_id'=>$foto_num[$id]));

    //if (array_key_exists($id+1,$foto_num))
    if(isset($this->files[$item_id+1]))
    $url_right = 'index.php'.$this->form_get(array('item_id'=>$item_id+1, 'page'=>''));
    else
    $url_right = '';

    $this->smarty->assign('big_height', $this->big_height);
    $this->smarty->assign('small_height', $this->small_height);
    $this->smarty->assign('Foto', $foto);
    $this->smarty->assign('Site', $this->sites[$item_id]);
    $this->smarty->assign('Fotos', $fotos);
    $this->smarty->assign('url', $url);
    $this->smarty->assign('url_left', $url_left);
    $this->smarty->assign('url_right', $url_right);
    $this->body = $this->smarty->fetch('portfolio_item.tpl');

  }

   /*
  function fetch_item($category=0,$id)
  {
     $current_page = intval($this->param('page'));




     $query = "SELECT * FROM portfolio_categories WHERE portfolio_categories.enabled='1' AND portfolio_categories.category_id='$category'";

  	$this->db->query($query);

    $category_n = $this->db->result();

    $this->title = 'Портфолио';//!empty($category_n->title) ? $category_n->title : $category_n->name;

      for($i=0;$i<$pages_num;$i++)
  	{
  		  $url[$i] = 'index.php'.$this->form_get(array('page'=>$i));

  	}



    $query = "SELECT SQL_CALC_FOUND_ROWS portfolio.* FROM portfolio_categories, portfolio
                              WHERE portfolio_categories.category_id = portfolio.category_id
                              AND portfolio.enable = 1 AND portfolio.category_id = portfolio.category_id";
    $this->db->query($query);
    $fotos = $this->db->results();

    $this->db->query("SELECT FOUND_ROWS() as count");
    $pages_num = $this->db->result();
    $pages_num = $pages_num->count/$this->items_per_page;

    $foto='';

    $foto_num=array();
    $key0='';

    foreach ($fotos as $key=>$foto){

       $fotos[$key]->url = $this->uploaddir.$foto->filename;
       $fotos[$key]->url2 = 'index.php'.$this->form_get(array('category'=>$category,'item_id'=>$foto->foto_id, 'page'=>''));
       $foto_num[$key]=$fotos[$key]->foto_id;
       if ($fotos[$key]->foto_id==$id){  $foto=$fotos[$key]; $key0=$key; }
    }



    if(isset($foto_num[$key0-1]))
    $url_left = 'index.php'.$this->form_get(array('category'=>$category,'item_id'=>$foto_num[$key0-1], 'page'=>''));
    else
    $url_left = '';

    //else
    //$url_left = 'index.php'.$this->form_get(array('item_id'=>$foto_num[$id]));

    //if (array_key_exists($id+1,$foto_num))
    if(isset($foto_num[$key0+1]))
    $url_right = 'index.php'.$this->form_get(array('category'=>$category,'item_id'=>$foto_num[$key0+1], 'page'=>''));
    else
    $url_right = '';


    $url = 'index.php'.$this->form_get(array('category'=>$category,'page'=>'','item_id'=>''));
    //else
    //$url_right = 'index.php'.$this->form_get(array('item_id'=>$foto_num[$id]));

    //print_r($foto_num);
    // print_r($fotos);

    $query = "SELECT SQL_CALC_FOUND_ROWS portfolio.* FROM portfolio_categories, portfolio
                              WHERE portfolio_categories.category_id = portfolio.category_id
                              AND portfolio.enable = 1 AND portfolio.foto_id = $id";
    $this->db->query($query);
    $foto = $this->db->result();

    $foto->url = $this->uploaddir.$foto->filename;
    $foto->url2 = 'index.php'.$this->form_get(array('category'=>$category,'item_id'=>$foto->foto_id, 'page'=>''));


    $this->smarty->assign('foto', $foto);
    $this->smarty->assign('Category', $category_n);
    $this->smarty->assign('url', $url);
    $this->smarty->assign('url_left', $url_left);
    $this->smarty->assign('url_right', $url_right);
    $this->body = $this->smarty->fetch('portfolio_item.tpl');

  }
   */
}

?>