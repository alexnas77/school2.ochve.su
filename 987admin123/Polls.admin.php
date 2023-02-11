<?PHP

require_once('Widget.admin.php');
require_once('PagesNavigation.admin.php');


############################################
# Class Polls
############################################
class Polls extends Widget
{
  var $pages_navigation;
  var $items_per_page = 30;
  function __construct(&$parent)
  {
    parent::Widget($parent);
    $this->add_param('page');
    $this->pages_navigation = new PagesNavigation($this);
    $this->prepare();
  }

  function prepare()
  {
  	if(isset($_POST['items']))
  	{
  		$items = $_POST['items'];
  		if(is_array($items))
  		  $items_sql = implode("', '", $items);
  		else
  		  $items_sql = $items;
  		$query = "DELETE polls, polls_answers, votes FROM polls, polls_answers, votes
 		          WHERE polls.poll_id=polls_answers.poll_id AND polls_answers.answer_id=votes.answer_id AND polls.poll_id IN ('$items_sql')";
  		$this->db->query($query);
  		$get = $this->form_get(array());
 		header("Location: index.php$get");
 	}

    if(isset($_POST[active]))
    {
       $active_id = $_POST[active];
  	   $query = "UPDATE polls SET active=0";
       $this->db->query($query);
  	   $query = "UPDATE polls SET active=1 WHERE poll_id=$active_id";
       $this->db->query($query);

    }

  }

  function fetch()
  {
    $this->title = $this->lang->POLLS;
  	$current_page = $this->param('page');
  	$start_item = $current_page*$this->items_per_page;
    $query = "SELECT SQL_CALC_FOUND_ROWS *,
    				  COUNT(votes.vote_id) as points FROM polls LEFT JOIN polls_answers ON polls_answers.poll_id = polls.poll_id LEFT JOIN votes ON votes.answer_id=polls_answers.answer_id
                      GROUP BY polls.poll_id
    				  ORDER BY polls.poll_id DESC
    				  LIMIT $start_item ,$this->items_per_page";
    $this->db->query($query);
  	$items = $this->db->results();

    $this->db->query("SELECT FOUND_ROWS() as count");
    $pages_num = $this->db->result();
    $pages_num = $pages_num->count/$this->items_per_page;

    foreach($items as $key=>$item)
    {
       $items[$key]->edit_get = $this->form_get(array('section'=>'EditPoll','poll_id'=>$item->poll_id));
    }

  	$this->pages_navigation->pages_num = $pages_num;
  	$this->pages_navigation->fetch();
 	$this->smarty->assign('Items', $items);
  	$this->smarty->assign('PagesNavigation', $this->pages_navigation->body);
  	$this->smarty->assign('Lang', $this->lang);
 	$this->body = $this->smarty->fetch('polls.tpl');
  }
}


############################################
# Class EdiPoll
############################################
class EditPoll extends Widget
{
  var $pages_navigation;
  var $items_per_page = 30;
  function __construct(&$parent)
  {
    parent::__construct($parent);
    $this->add_param('page');
    $this->add_param('poll_id');
    $this->pages_navigation = new PagesNavigation($this);
    $this->prepare();
  }

  function prepare()
  {
    $poll_id = $this->param('poll_id');

    if(isset($_POST['question']))
    {
       $question = $_POST['question'];

       if($poll_id)
       {
         $query = sql_placeholder("UPDATE polls SET question=? WHERE poll_id=?", $question, $poll_id);
         $this->db->query($query);
       }
       else
       {
         $query = sql_placeholder("INSERT INTO polls (question) VALUES (?)", $question);
         $this->db->query($query);
         $poll_id = $this->db->insert_id();
       }

    }
  	if(isset($_GET['action']) and $_GET['action']=='move_up')
  	{
  		$item_id = $_GET['item_id'];
  		$this->db->query("SELECT @id:=a1.answer_id
  		                  FROM polls_answers a1, polls_answers a2
  		                  WHERE a1.order_num>a2.order_num
  		                  AND a2.answer_id = '$item_id'
  		                  ORDER BY a1.order_num ASC
  		                  LIMIT 1");
  		$this->db->query("UPDATE polls_answers a1, polls_answers a2
  		                  SET a1.order_num = (@a:=a1.order_num)*0+a2.order_num, a2.order_num = @a
  		                  WHERE a1.answer_id = '$item_id'
  		                  AND a2.answer_id = @id");
  		$get = $this->form_get(array());
  		header("Location: index.php$get");
  	}
 	if(isset($_GET['action']) and $_GET['action']=='move_down')
  	{
   		$item_id = $_GET['item_id'];
  		$this->db->query("SELECT @id:=a1.answer_id
  		                  FROM polls_answers a1, polls_answers a2
  		                  WHERE a1.order_num<a2.order_num
  		                  AND a2.answer_id = '$item_id'
  		                  ORDER BY a1.order_num DESC
  		                  LIMIT 1");
  		$this->db->query("UPDATE polls_answers a1, polls_answers a2
  		                  SET a1.order_num = (@a:=a1.order_num)*0+a2.order_num, a2.order_num = @a
  		                  WHERE a1.answer_id = '$item_id'
  		                  AND a2.answer_id = @id");
  		$get = $this->form_get(array());
  		header("Location: index.php$get");
  	}


    $this->db->query("SELECT * FROM polls
    				  WHERE poll_id = $poll_id");
  	$this->poll = $this->db->result();

  	if(isset($_POST['items']))
  	{
  		$items = $_POST['items'];
  		if(is_array($items))
  		  $items_sql = implode("', '", $items);
  		else
  		  $items_sql = $items;
  		$query = "DELETE  polls_answers, votes FROM polls_answers, votes
 		          WHERE  polls_answers.answer_id=votes.answer_id AND polls_answers.answer_id IN ('$items_sql')";
  		$this->db->query($query);
  		$get = $this->form_get(array('section'=>EditPoll, 'poll_id'=>poll_id));
 		header("Location: index.php$get");
 	}
  }

  function fetch()
  {
    $this->title = $this->lang->POLL;

  	$current_page = $this->param('page');
  	$start_item = $current_page*$this->items_per_page;
    $this->db->query("SELECT SQL_CALC_FOUND_ROWS polls_answers.*,
                      count(votes.vote_id) as points FROM polls_answers LEFT JOIN votes ON polls_answers.answer_id=votes.answer_id
                      WHERE polls_answers.poll_id=".($this->poll->poll_id*1)."
                      GROUP BY polls_answers.answer_id
    				  ORDER BY polls_answers.order_num DESC
    				  LIMIT $start_item ,$this->items_per_page");
  	$items = $this->db->results();

    $this->db->query("SELECT FOUND_ROWS() as count");
    $pages_num = $this->db->result();
    $pages_num = $pages_num->count/$this->items_per_page;

    foreach($items as $key=>$item)
    {
       $items[$key]->move_up_get = $this->form_get(array('action'=>'move_up','item_id'=>$item->answer_id));
       $items[$key]->move_down_get = $this->form_get(array('action'=>'move_down','item_id'=>$item->answer_id));
       $items[$key]->edit_get = $this->form_get(array('section'=>'EditAnswer','answer_id'=>$item->answer_id, 'poll_id'=>$this->poll->poll_id));
    }

  	$this->pages_navigation->fetch($pages_num);
 	$this->smarty->assign('Poll', $this->poll);
 	$this->smarty->assign('Items', $items);
  	$this->smarty->assign('PagesNavigation', $this->pages_navigation->body);
  	$this->smarty->assign('Lang', $this->lang);
 	$this->body = $this->smarty->fetch('poll.tpl');
  }
}



############################################
# Class EdiVote
############################################
class EditAnswer extends Widget
{
  var $pages_navigation;

  function __construct(&$parent)
  {
    parent::__construct($parent);
    $this->prepare();
  }

  function prepare()
  {
    $answer_id = $this->param('answer_id');
    $poll_id = $this->param('poll_id');

    $this->db->query("SELECT * FROM polls
    				  WHERE poll_id = $poll_id");
  	$this->poll = $this->db->result();

    if(isset($_POST['answer']))
    {
       $answer = $_POST['answer'];
       if($answer_id)
       {
         $query = sql_placeholder("UPDATE polls_answers SET answer=? WHERE answer_id=?", $answer, $answer_id);
         $this->db->query($query);
       }else
       {
         $query = sql_placeholder("INSERT INTO polls_answers (answer, poll_id) VALUES (?, ?)", $answer, $poll_id);
         $this->db->query($query);
         $answer_id = $this->db->insert_id();
         $query = sql_placeholder("UPDATE polls_answers SET order_num=answer_id WHERE answer_id=?", $answer_id);
         $this->db->query($query);
       }

  	   $get = $this->form_get(array('section'=>EditPoll, 'poll_id'=>$poll_id, 'answer_id'=>$answer_id));
 	   header("Location: index.php$get");
    }

    $this->db->query("SELECT * FROM polls_answers
    				  WHERE answer_id = $answer_id");
  	$this->answer = $this->db->result();

  	if(isset($_POST['items']))
  	{
  		$items = $_POST['items'];
  		if(is_array($items))
  		  $items_sql = implode("', '", $items);
  		else
  		  $items_sql = $items;
  		$query = "DELETE FROM news
 		          WHERE news.news_id IN ('$items_sql')";
  		$this->db->query($query);
  		$get = $this->form_get(array());
 		header("Location: index.php$get");
 	}
  }

  function fetch()
  {
    $this->title = $this->lang->VOTE;

 	$this->smarty->assign('Poll', $this->poll);
 	$this->smarty->assign('Answer', $this->answer);
  	$this->smarty->assign('Lang', $this->lang);
 	$this->body = $this->smarty->fetch('answer.tpl');
  }
}