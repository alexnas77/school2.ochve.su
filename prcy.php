<?

   define('GOOGLE_MAGIC', 0xE6359A60);
   class PRCY {	   	private static function zeroFill($a, $b) {
	     	$z = hexdec(80000000);
	     	if($z & $a) {
	       		$a = ($a>>1);
	       		$a &= (~$z);
	       		$a |= 0x40000000;
	       		$a = ($a>>($b-1));
	     	}
	     	else { $a = ($a>>$b); }
	     	return $a;
	   	}

	   	private static function mix($a,$b,$c) {
	     	$a -= $b; $a -= $c; $a ^= (self::zeroFill($c,13));
	     	$b -= $c; $b -= $a; $b ^= ($a<<8);
	     	$c -= $a; $c -= $b; $c ^= (self::zeroFill($b,13));
	     	$a -= $b; $a -= $c; $a ^= (self::zeroFill($c,12));
	     	$b -= $c; $b -= $a; $b ^= ($a<<16);
	     	$c -= $a; $c -= $b; $c ^= (self::zeroFill($b,5));
	     	$a -= $b; $a -= $c; $a ^= (self::zeroFill($c,3));
	     	$b -= $c; $b -= $a; $b ^= ($a<<10);
	     	$c -= $a; $c -= $b; $c ^= (self::zeroFill($b,15));
	     	return array($a,$b,$c);
	   	}

	   	private static function GoogleCH($urlpage, $length=null, $init=GOOGLE_MAGIC) {
	     	if(is_null($length)) { $length = sizeof($urlpage); }
	     	$a = $b = 0x9E3779B9;
	     	$c = $init;
	     	$k = 0;
	     	$len = $length;
	     	while($len >= 12) {
	       		$a += ($urlpage[$k+0] + ($urlpage[$k+1]<<8) + ($urlpage[$k+2]<<16) + ($urlpage[$k+3]<<24));
	       		$b += ($urlpage[$k+4] + ($urlpage[$k+5]<<8) + ($urlpage[$k+6]<<16) + ($urlpage[$k+7]<<24));
	       		$c += ($urlpage[$k+8] + ($urlpage[$k+9]<<8) + ($urlpage[$k+10]<<16)+ ($urlpage[$k+11]<<24));
	       		$mix = self::mix($a,$b,$c);
	       		$a = $mix[0]; $b = $mix[1]; $c = $mix[2];
	       		$k += 12;
	       		$len -= 12;
	     	}
	     	$c += $length;
	     	switch($len) {
		       	case 11: $c+=($urlpage[$k+10]<<24);
		       	case 10: $c+=($urlpage[$k+9]<<16);
		       	case 9 : $c+=($urlpage[$k+8]<<8);
		       	case 8 : $b+=($urlpage[$k+7]<<24);
		       	case 7 : $b+=($urlpage[$k+6]<<16);
		       	case 6 : $b+=($urlpage[$k+5]<<8);
		       	case 5 : $b+=($urlpage[$k+4]);
		       	case 4 : $a+=($urlpage[$k+3]<<24);
		       	case 3 : $a+=($urlpage[$k+2]<<16);
		       	case 2 : $a+=($urlpage[$k+1]<<8);
		       	case 1 : $a+=($urlpage[$k+0]);
	     	}
	     	$mix = self::mix($a,$b,$c);
	     	return $mix[2];
	   	}

	   	private static function strord($string) {
	     	for($i=0;$i<strlen($string);$i++) {
	        	$result[$i] = ord($string{$i});
	     	}
	     	return $result;
	   	}

		// Функция для определения PR Google
	   	public static function getPR($url) {
	     	$urlpage = 'info:'.$url;
	     	$ch = self::GoogleCH(self::strord($urlpage));
	     	$ch = "6$ch";
	     	$page = @file("http://www.google.com/search?client=navclient-auto&ch=$ch&features=Rank&q=info:".urlencode($url));
	     	$page = @implode("", $page);
	     	if(preg_match("/Rank_1:(.):(.+?)\n/is", $page, $res))  return $res[2];
	     	else return 0;
	   	}

		public static function getCY($url) {
	     	$getURL = sprintf("http://bar-navig.yandex.ru/u?ver=2&id=1340996&lang=1045&url=%s&show=1&thc=0", $url);
	     	$mask1   = '/\<tcy\s+rang=\"(\d+)\"\s+value=\"(\d+)\"\/\>/is';
	     	$mask2   = '/\<topic\s+title=\"([^\"]+)\"/is';
	     	$mask3   = '/\s+Регион:\s+([^\s]+)\s+/is';
	     	$wp = new CWebPage($getURL);
	     	$wp->refferer = 'http://www.google.com';
	     	$result = array('rang'=>0, 'cy'=>0, 'topic'=>'');
	     	if($wp->doQuery() == 200) {
	         	$result = array('rang'=>0, 'cy'=>0, 'topic'=>'');
	         	if(preg_match($mask1, $wp->document, $matches)) {
	            	$result['rang'] = (int)$matches[1];
	            	$result['cy'] = (int)$matches[2];
	         	}
	         	if(preg_match($mask2, $wp->document, $matches))
	            	$result['topic'] = substr($matches[1], 6);
	         	if(preg_match($mask3, $wp->document, $matches))
	            	$result['region'] = $matches[1];

	     	}
	     	return $result;
	 	}
   }

?>