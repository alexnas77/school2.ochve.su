<?PHP

//require_once('Widget.class.php');

class Poll extends Widget
{
  function __construct(&$parent)
  {
    parent::__construct($parent);
  }

  function fetch()
  {
    # Опрос
    $query = sql_placeholder("SELECT * FROM polls WHERE active=1");
    $this->db->query($query);
    $poll = $this->db->result();


    # Принять голос
    if(isset($_POST['answer_id']))
    {
       $answer_id = $_POST['answer_id'];
       $ip = $_SERVER['REMOTE_ADDR'];
       ## Проверка ip

       $query = sql_placeholder("SELECT count(votes.vote_id) as count FROM votes, polls_answers WHERE votes.answer_id=polls_answers.answer_id AND polls_answers.poll_id=? AND vote_ip=?  ", $poll->poll_id, $ip);
       $this->db->query($query);
       $c = $this->db->result();
       if($c->count>0)
       {
         $message = 'Вы уже голосовали в этом опросе';
       }else
       {
         $message = 'Голос принят';
         $query = sql_placeholder("INSERT INTO votes (answer_id, vote_ip) VALUES(?, ?)", $answer_id, $ip);
         $this->db->query($query);
       }
    }



    # Опрос
    $query = sql_placeholder("SELECT polls.*, COUNT(votes.vote_id) as points FROM polls LEFT JOIN polls_answers ON polls_answers.poll_id = polls.poll_id LEFT JOIN votes ON votes.answer_id=polls_answers.answer_id WHERE active=1 GROUP BY polls.poll_id");
    $this->db->query($query);
    $poll = $this->db->result();
    $query = sql_placeholder("SELECT polls_answers.*, count(votes.vote_id) as points FROM polls_answers LEFT JOIN votes ON polls_answers.answer_id=votes.answer_id WHERE polls_answers.poll_id=? GROUP BY polls_answers.answer_id ORDER BY order_num DESC", $poll->poll_id);
    $this->db->query($query);
    $poll->answers = $this->db->results();


    $section_id = $this->param('section');
    $this->db->query("SELECT * FROM sections WHERE section_id = '$section_id'");
    $section = $this->db->result();

    $this->title = $section->name;
    $this->keywords = $section->name;
    $this->description = $section->name;

    $this->smarty->assign('Poll', $poll);
    $this->smarty->assign('Message', $message);
    $this->smarty->assign('Section', $section);
    $this->body = $this->smarty->fetch('poll.tpl');
  }

}