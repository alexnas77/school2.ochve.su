<?
  class CUrl {
      var $scheme;
      var $host;
      var $path;
      var $query;

      function CUrl() {
          $this->scheme="";
          $this->host="";
          $this->path="";
          $this->query="";
      }
      public function init($s, $h, $p, $q) {
          $this->scheme = $s;
          $this->host = $h;
          $this->path = $p;
          $this->query = $q;
      }
      public function dump() {
          return $this->scheme."://".$this->host.$this->path.(($this->query!="") ? "?" : "").$this->query;
      }
      private function domains() {
          $out = array();
          $mask = "/^http\:\/\/([^\s\/\'\"\<\>]+)[\/]?/";

          preg_match($mask, $this->dump(), $match);

          if(!count($match))  return FALSE;

          $out = split('\.', $match[1]);

          if(!count($out))  return FALSE;
          $out = array_reverse($out);
          return $out;
      }
  }
  // класс ошибки
  class CError {
      var $str_error;
      var $edate;
      var $etime;

      function CError() {
         $this->str_error="";
      }
      function set($str) {
         $this->str_error = $str;
         $this->edate = date("d.m.y");
         $this->etime = date("H:i:s");
      }
      function appendlog() {
          if(!defined("ERRORLOGFILE")) {
             define("ERRORLOGFILE", "errorlog.txt");
          }
          if(!file_exists(ERRORLOGFILE)) { $fp=@fopen(ERRORLOGFILE, "w"); fwrite($fp, "", 0); }
          $content = @file_get_contents(ERRORLOGFILE);
          if($content) {
              $content .= $this->str_error."|".$this->edate."|".$this->etime."\n";
              file_put_contents($content);
          }
      }
      function fetch() {
          $msg = new Message($this->str_error, ERROR);
          return $msg->fetch();
      }
  }
  // класс веб-страницы
  class CWebPage {
      var $location;
      var $error;

      var $headers;
      var $post;
      var $cookie;
      var $get;

      var $httpver;
      var $document;

      var $links;
      var $timeout;
      var $method;
      var $user_agent;
      var $refferer;
      var $connection;
      var $content_type;

      function CWebPage() {
         $this->location = new CUrl();
         $this->error = new CError();

         if(func_num_args() > 0) $this->parseurl(func_get_arg(0));

         $this->document = '';
         $this->timeout = 50;
         $this->httpver = '1.0';
         $this->method = 'GET';
         $this->connection = 'close';
         $this->user_agent = 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; InfoPath.1; .NET CLR 2.0.50727; .NET CLR 1.1.4322)';
         $this->refferer = '';
         $this->content_type = '';

         settype($this->links, "array");
         settype($this->headers, "array");

         settype($this->post, "array");
         settype($this->get, "array");
         settype($this->cookie, "array");
      }
      public function parseurl($arg) {            if(is_string($arg)) {
               $link_ar = parse_url($arg);

               if(!isset($link_ar["scheme"])) return FALSE;
               if(!isset($link_ar["host"])) return FALSE;
               if(!isset($link_ar["path"])) return FALSE;
               if(!isset($link_ar["query"])) $link_ar["query"] = "";

               $this->location->init($link_ar["scheme"], $link_ar["host"], $link_ar["path"], $link_ar["query"]);
            } else {
                 if($arg->scheme == "") return FALSE;
                 if($arg->host == "") return FALSE;
                 if($arg->path == "") return FALSE;
                 if($arg->query == "") $arg->query = "";

                 $this->location = $arg;
              }
      }
      public function addpostvar($post_var, $value = null) {
        if (is_array($post_var)){
            foreach ($post_var as $key => $val) {
                if ($key != '') {
                    $this->post[$key] = $val;
                }
            }
        } else
            if ($post_var != '') $this->post[$post_var] = $value;
      }
      public function clear_posts($post_var='') {
        if ($post_var=='')
            $this->post = array();
        elseif(isset($this->post[$post_var]))
            unset($this->post[$post_var]);
      }
      public function addgetvar($get_var, $value = null) {
        if (is_array($get_var)){
            foreach ($get_var as $key => $val) {
                if ($key != '') {
                    $this->get[$key] = $val;
                }
            }
        } else
            if ($get_var != '') $this->get[$get_var] = $value;
      }
      public function clear_gets($get_var='') {
        if ($get_var=='')
            $this->get = array();
        elseif(isset($this->get[$post_var]))
            unset($this->get[$post_var]);
      }
      public function addcookievar($cookie_var, $value = null) {
        if (is_array($cookie_var)){
            foreach ($cookie_var as $key => $val) {
                if ($key != '') {
                    $this->cookie[$key] = $val;
                }
            }
        } else
            if ($cookie_var != '') $this->cookie[$cookie_var] = $value;
      }
      public function clear_cookie($cookie_var) {
        if (is_array($cookie_var))
            foreach ($cookie_var as $curr_var)
                unset($this->cookie[$curr_var]);
        else
            unset($this->cookie[$cookie_var]);
      }
      public function error() { return $this->error; }

      public function doQuery($encoding='') {
         $out = "";  // пошлем web - серверу
         $in = "";   // примем от web-сервера
         $port = ( $serv = @getservbyname($this->location->scheme)) ? $serv : 80;
         $socket = @fsockopen($this->location->host, $port, $errno, $error, $this->timeout);

         if(!$socket) {
             $this->error->set("Ошибка № ".$errno." : ".$error);
             //$this->error->appendlog();
             $this->headers["Status"] = 404;
             return $this->headers["Status"];
         }
         $this -> method = strtoupper($this -> method);
         $out .= $this->method." ".($this->location->path.(($this->location->query!="")?("?".$this->location->query):""));
         if($this -> method == 'GET' or $this -> method == 'HEAD' ) {
            if(count($this->get)) {
               $i = 0;
               $out .= ($this->location->query!="")?"&":"?";
	           foreach($this->get as $key=>$value) {
	               $out .= $key."=".urlencode($value).(($i++ != count($this->get)-1)?"&":"");
	           }
            }
         }

         $out .= " HTTP/".$this->httpver."\r\n";
         $out .= "Host: ".$this->location->host."\r\n";
         $this->readCookie();
         if(count($this->cookie)) {            $out .= "Cookie: ";
            $i = 0;
            foreach($this->cookie as $key=>$value)
                $out .= $key."=".urlencode($value).(($i++ != count($this->cookie)-1)?";":"");
            $out .= "\r\n";
         }
         if($this->user_agent != '')
            $out .= "User-Agent: ".$this->user_agent."\r\n";
         if($this->refferer != '')
            $out .= "Referer: ".$this->refferer."\r\n";
         $out .= "Connection: ".$this->connection."\r\n";
         if($this -> method == 'POST') {
            $out .= "Content-Type: ".$this->content_type."\r\n";
            $i = 0;		$poststring = '';            foreach($this->post as $key=>$value)
                $poststring .= $key."=".urlencode($value).(($i++ != count($this->post)-1)?"&":"");
            $out .= "Content-Length: ".strlen($poststring)."\r\n\r\n".$poststring;
         }  else $out .= "\r\n";

         if(!@fputs($socket, $out, strlen($out))) {
            $this->error->set("Не удалось отправить запрос серверу");
            //$this->error->appendlog();
            $this->headers["Status"] = 404;
            return $this->headers["Status"];
         }
         while($data = fread($socket, 1024)) $in .= $data;

         fclose($socket);

         $pos = strpos($in, chr(13).chr(10).chr(13).chr(10));
         $head = substr($in, 0, $pos);

         $head_ar = split(chr(13).chr(10), $head );
         list($s1, $s2) = split(" ", $head_ar[0]);
         $this->headers["Ver"] = $s1;
         $this->headers["Status"] = $s2;
         for($iCounter = 1; $iCounter < count($head_ar); $iCounter++)  {
              list($s1, $s2) = split(": ", $head_ar[$iCounter]);
              $this->headers[$s1] = $s2;
         }
         $this->saveCookie();
         $this->document = substr($in, $pos + 4);
         if($encoding != '') {            if(isset($this->headers['Content-Type']))
               if(preg_match("/^[a-z\/\-\_]+;\scharset\=([a-z0-9\/\-\_]+)/is",
                  $this->headers['Content-Type'],$match)&&($match[1]!=$encoding))
                     $this->document = iconv($match[1], $encoding, $this->document);
         }
         return $this->headers["Status"];
      }
      private function readCookie() {
      }
      private function saveCookie() {
         if(isset($this->headers["Set-Cookie"])) {

         }
      }
      private function getAllCookie() {         if($this->cookiefile != '') {             if(file_exists($_SERVER['DOCUMENT_ROOT'].$this->cookiefile)) {
             }
         }
      }
      public function escapelinks() {
         preg_match_all("'<\s*a\s.*?href\s*=\s*([\"\'])?(?(1) (.*?)\\1 | ([^\s\>]+))'isx",$this->document,$links);
         $match = array();
         while(list($key,$val) = each($links[2])) { if(!empty($val)) $match[] = $val;  }
         while(list($key,$val) = each($links[3])) { if(!empty($val)) $match[] = $val;  }

         if(count($match)) {
             for($iCounter = 0; $iCounter < count($match); $iCounter++)
                 if(!eregi("^[a-z0-9]+\:", $match[$iCounter]) && ($match[$iCounter] != "")) {
                     $link_ar = parse_url($match[$iCounter]);
                     $u = new CUrl();
                     if(isset($link_ar["scheme"])) {
                          if(!isset($link_ar["path"]))  $link_ar["path"] = "/";
                          if(!isset($link_ar["query"])) $link_ar["query"] = "";

                          $u->init($link_ar["scheme"],$link_ar["host"],$link_ar["path"],$link_ar["query"]);
                     }
                        else  {
                          $s = $this->location->scheme;
                          $h = $this->location->host;

                          if(isset($link_ar["path"])) {
                             if(strpos($link_ar["path"], "/") !== 0) {
                                if(strpos($this->location->path, "/") !== FALSE) {
                                  for($i=strlen($this->location->path)-1; (($this->location->path[$i] != "/") && ($i > 0)); $i--);
                                  $p = substr($this->location->path, 0, $i+1).$link_ar["path"];
                                } else $p = "/".$link_ar["path"];
                             }  else {
                                $p = $link_ar["path"];
                             }
                          $q = isset($link_ar["query"]) ? $link_ar["query"] : "";
                          } else {
                             $p = $this->location->path;
                             $q = $link_ar["query"];
                          }
                          $u->init($s, $h, $p, $q);
                     }
                     $this->links[] = $u;
                 }
         }
      }
  }
?>