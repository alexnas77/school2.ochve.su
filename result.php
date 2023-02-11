<?
error_reporting(0);
// $Id: result.php,v 1.11 2006/07/31 14:11:41 asor Exp $
require_once('Config.class.php');
require_once('merchant/wm_config.php');
require_once('merchant/wm_include.php');
$ctype="text/html; charset=Windows-1251";
header("Content-Type: $ctype");
unlink("backup/result.log");
    $link = @fopen("backup/result.log","a");
    @fwrite($link,print_r($_POST,true)."\r\n");
    @fclose($link);

   $config=new Config();

#Соеденяемсо с БД
$dbc=@mysql_connect($config->dbhost,$config->dbuser,$config->dbpass);

if(!$dbc)
{
//echo "Ошибка подключения к Базе Данных Сайта. ".@mysql_error($dbc);
    $link = @fopen("backup/result.log","a");
    @fwrite($link,"Ошибка подключения к Базе Данных Сайта. ".@mysql_error($dbc)."\r\n");
    @fclose($link);
}
if (!@mysql_select_db($config->dbname,$dbc))
{
//echo "База Данных Сайта не доступна. ".@mysql_error($dbc);
    $link = @fopen("backup/result.log","a");
    @fwrite($link,"База Данных Сайта не доступна. ".@mysql_error($dbc)."\r\n");
    @fclose($link);
}
#На всякий случай ставим нормальную кодировку
mysql_query("SET NAMES cp1251",$dbc);

# Auxiliary function, log entry and exit
# Parameters:
# $string - Error message
# $step   - Step where error occurred
function _error($string, $step) {
    error_log($string . ", step: $step, payment_no: ". $_POST['LMI_PAYMENT_NO']);
    $link = @fopen("backup/result.log","a");
    @fwrite($link,$string . ", step: $step, payment_no: ". $_POST['LMI_PAYMENT_NO']."\r\n");
    @fclose($link);
    die();
};

# Database query auxiliary function, causes _error in case of failure
function _query($query, $step) {

global $dbc;

    $result = mysql_query($query,$dbc);
    $link = @fopen("backup/result.log","a");
    @fwrite($link,$step." ".$query."\r\n");
    @fclose($link);
    if( !$result ) {
	_error("Query failed : " . mysql_error($dbc), $step);
    } else {
	return $result;
    };
};


if( isset($_POST['LMI_PREREQUEST']) && $_POST['LMI_PREREQUEST'] == 1){ # Prerequest
    if( isset($_POST['LMI_PAYMENT_NO'])
	&& preg_match('/^\d+$/',$_POST['LMI_PAYMENT_NO']) == 1  # Payment inner id
        && isset($_POST['RND']) && preg_match('/^[A-Z0-9]{8}$/',$_POST['RND'],$match) == 1){ # step 3
	# Request from database re payment with such id
	$query = "SELECT p.id, p.item, i.price, i.unit FROM ".$DB_DBASE."_payment as p, ".$DB_DBASE."_items as i WHERE p.id ='".$_POST['LMI_PAYMENT_NO']."' AND p.item = i.id AND p.RND='".$_POST['RND']."';";
	$result = _query($query, 4);
	$rows = mysql_num_rows($result);
	if ( $rows != 1 ) {
	    _error('Item not found', 5);
	} else { # If no payment or items found
	    $pay = mysql_fetch_array($result);
	    mysql_free_result($result);
	    if( $_POST['LMI_PAYMENT_NO'] == $pay['id'] # Check if payment id, purse number and ammount correspond with each other
             && $_POST['LMI_PAYEE_PURSE'] == ${'WM_SHOP_PURSE_'.$pay['unit']}
             && $_POST['LMI_PAYMENT_AMOUNT'] == $pay['price']){ # step 5
                # reserve
		$query = "UPDATE ".$DB_DBASE."_items SET reserved=CURRENT_TIMESTAMP() WHERE id='".$pay['item']."';";
		$result = _query($query, 6);
		if(mysql_affected_rows() != 1){
		    _error('Item not reserved', 7);
		} else {
		    # Update payment  as _reserved_
		    $query = "UPDATE ".$DB_DBASE."_payment SET state='R', timestamp=CURRENT_TIMESTAMP() WHERE id='".$pay['id']."';";
		    $result = _query($query, 8);
		    if(mysql_affected_rows() != 1){
			_error('Payment not updated', 9);
                    } else {
			echo 'YES'/*iconv('WINDOWS-1251','UTF-8',)*/; # if everything is ok and items are reserved,  give ok to transaction
			$link = @fopen("backup/result.log","a");
            @fwrite($link,/*iconv('WINDOWS-1251','UTF-8','YES')*/'YES'."\r\n");
            @fclose($link);
		    };
		};
     } else { # step 5
        _error('Inconsistent parameters', 5);
     };
}
} else { # step 3
    _error('Inconsistent parameters', 3);
};
}else{ #  Payment notification
    if( isset($_POST['LMI_PAYMENT_NO']) # Check payment id
	&&  preg_match('/^\d+$/',$_POST['LMI_PAYMENT_NO']) == 1
	&& isset($_POST['RND']) && preg_match('/^[A-Z0-9]{8}$/',$_POST['RND'],$match) == 1){ # Check ticket, step 11
	# Query form database about payment with such id
	$query = "SELECT p.id, p.item, i.price, i.unit, p.email, i.description FROM ".$DB_DBASE."_payment as p, ".$DB_DBASE."_items as i WHERE ".
	"p.id ='".$_POST['LMI_PAYMENT_NO']."' AND p.item = i.id AND (p.state='I' OR p.state='R') AND i.state='Y' ".
	"AND p.RND = '".$_POST['RND']."' /*AND ((i.reserved IS NULL) OR (i.reserved + INTERVAL 2 MINUTE > NOW()))*/;";
	$result = _query($query, 12);
	$rows = mysql_num_rows($result);
	if ( $rows != 1 ) {
	        _error('Payment not found', 13);
	    } else { # If payment or items were not found,
	    $pay = mysql_fetch_array($result);
	    mysql_free_result($result);
	    # Create check string
    	    $chkstring =  ${'WM_SHOP_PURSE_'.$pay['unit']}.$pay['price'].$pay['id'].
		    $_POST['LMI_MODE'].$_POST['LMI_SYS_INVS_NO'].$_POST['LMI_SYS_TRANS_NO'].$_POST['LMI_SYS_TRANS_DATE'].
	            $LMI_SECRET_KEY.$_POST['LMI_PAYER_PURSE'].$_POST['LMI_PAYER_WM'];
	    if ( $LMI_HASH_METHOD == 'MD5' ) {
	    	$md5sum = strtoupper(md5($chkstring));
		$hash_check = ($_POST['LMI_HASH'] == $md5sum);
	    } elseif ( $LMI_HASH_METHOD == 'SIGN' ) {
		$PlanStr=$WM_SHOP_WMID.'967909998006'.$chkstring.$_POST['LMI_HASH'];
		error_log("PlanStr: $PlanStr");
		$SignStr=wm_GetSign($PlanStr);
		error_log("SignStr: $SignStr");
		if( strlen($SignStr) < 132){
		    _error("Error: WMSigner response: ".$SignStr, 24);
		};
		$req="/asp/classicauth.asp?WMID=$WM_SHOP_WMID&CWMID=967909998006&CPS=".urlencode($chkstring).
		"&CSS=".$_POST['LMI_HASH']."&SS=$SignStr";
		error_log("URL: $req");
		$resp=wm_HttpsReq($req);
		if($resp=='Yes'){
		    $hash_check = TRUE ;
		} else {
		    _error("Error: w3s.webmoney.ru response: ".$resp, 25);
		}
	    } else {
		_error('Config parameter LMI_HASH_METHOD incorrect!', 14);
	    };
	    if(    $_POST['LMI_PAYMENT_NO'] == $pay['id'] # Check if payment id, purse number and amount correspond
		&& $_POST['LMI_PAYEE_PURSE'] == ${'WM_SHOP_PURSE_'.$pay['unit']}
		&& $_POST['LMI_PAYMENT_AMOUNT'] == $pay['price']
		&& $_POST['LMI_MODE'] == $LMI_MODE
		&& $hash_check ) {  # checksum is correct, step 15
		    # if everything is ok, payment receives status: Paid, item receives status: Sold,
		    # enter payment and customer data into database
		    $query = "UPDATE ".$DB_DBASE."_payment SET state='S', timestamp=CURRENT_TIMESTAMP(), ".
			     "LMI_SYS_INVS_NO='".$_POST['LMI_SYS_INVS_NO']."', ".
			     "LMI_SYS_TRANS_NO='".$_POST['LMI_SYS_TRANS_NO']."', ".
			     "LMI_SYS_TRANS_DATE='".$_POST['LMI_SYS_TRANS_DATE']."', ".
			     "LMI_PAYER_PURSE='".$_POST['LMI_PAYER_PURSE']."', ".
			     "LMI_PAYER_WM='".$_POST['LMI_PAYER_WM']."' ".
			     "WHERE id='".$pay['id']."';";
		    $result = _query($query, 16);
		    if(mysql_affected_rows() == 1){
			/*$query = 'UPDATE '.$DB_DBASE.'_items SET state="N" WHERE id="'.$pay['item'].'";';
			$result = _query($query, 17);*/

			/*# send customer a link to receive purchased items
			$ouremail = "shop@".$_SERVER['SERVER_NAME'];
			$url = 'https://'.$_SERVER['SERVER_NAME'].
			substr($_SERVER['REQUEST_URI'],0,strlen($_SERVER['REQUEST_URI'])-strlen('result.php')).
			'giveout.php'.
			'?wmid='.$_POST['LMI_PAYER_WM'].'&id='.$_POST['LMI_PAYMENT_NO'].'&rnd='.$_POST['RND'];
			$message = "\nYou have bought items: <".$pay['description']."> in our shop.\n".
			"To receive it, go to: $url";
			$addheader ="From: $ouremail\r\nReply-To: $ouremail\r\nContent-Type: text/plain; charset='windows-1251'";
			mail($pay['email'], 'Purchase in our shop', $message, $addheader);*/

		    }
	    } else { # step 15
		_error('Inconsistent parameters', 15);
	    };
	}
    } else { # step 11
	_error('Inconsistent parameters', 11);
    };
}
?>
