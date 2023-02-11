<?php
Header("Content-Type: text/html; charset=windows-1251");
set_time_limit(0);
require_once('Database.class.php');
//require_once('Database.remote2.php');
require_once('placeholder.php');
require_once('Config.class.php');

$config = new Config();


$dbname2 = $config->dbname;
$dbhost2 = $config->dbhost;
$dbuser2 = $config->dbuser;
$dbpass2 = $config->dbpass;

$db2 = new Database($dbname2, $dbhost2, $dbuser2, $dbpass2);
$db2->connect();
$db2->query("SET NAMES cp1251");

$settings = new stdClass();

$query = "SELECT * FROM settings WHERE domain='/'";
$db2->query($query);
$sts = $db2->results();
foreach ($sts as $s) {
    $name = $s->name;
    if (!empty($s->name) && !empty($s->value)) {
        $settings->$name = $s->value;
    }
}
$dbname0 = $settings->backup_db;
$dbhost0 = $config->dbhost;
$dbuser0 = $config->dbuser;
$dbpass0 = $config->dbpass;

$db0 = new Database($dbname0, $dbhost0, $dbuser0, $dbpass0);
$db0->connect();
$db0->query("SET NAMES cp1251");

echo "<h1>Пересчет остатков оплаты питания</h1>\n";

$query = sql_placeholder("SELECT products.product_id, products.category_id, products.model, categories.name as category_name 
      FROM products LEFT JOIN categories ON categories.category_id=products.category_id 
      WHERE products.enabled = '1' 
      ORDER BY categories.order_num, products.model");
//echo "<br />$query<br />\n";
$db2->query($query);
$products = $db2->results();

$datetime = date("Y-m-d");

if (isset($_GET['go']) && $_GET['go'] == 'true') {
    echo "<ol>\n";
    
    

    foreach ($products as $id => $product) {
        $query = sql_placeholder("SELECT SUM(stat.delta) as sum FROM  products
    LEFT JOIN stat ON stat.product_id=products.product_id
    WHERE products.product_id=? 
    AND products.enabled = '1'
    GROUP BY products.product_id", $product->product_id);
        $db0->query($query);
        //echo "<br />$query<br />\n";
        $res0 = $db0->result();
        $sum0 = $res0->sum;        
        
        $query = sql_placeholder("SELECT SUM(stat.delta) as sum FROM  products
    LEFT JOIN stat ON stat.product_id=products.product_id
    WHERE products.product_id=? 
    AND products.enabled = '1'
    GROUP BY products.product_id", $product->product_id);
        $db2->query($query);
        //echo "<br />$query<br />\n";
        $res = $db2->result();
        $sum = $res->sum;
        
        if($sum !== $sum0) {
            echo "<li>(Ошибка) Ученик " . $product->category_name . " класса <b>" . $product->model . "</b> итог " . $sum . " руб != итог по резервной ДБ ".$sum0." руб.<br />\n";
            continue;
        }

        $query = sql_placeholder("DELETE FROM stat WHERE product_id=?", $product->product_id);
        //echo "<br />$query<br />\n";
        $db2->query($query);

        echo "<li>Ученик " . $product->category_name . " класса <b>" . $product->model . "</b> итог " . $sum . " руб.<br />\n";
        $query = sql_placeholder("REPLACE INTO `stat` (product_id,date,category_id,breakfast,lunch,lunch2,dinner,cash,card,new_breakfast,new_lunch,new_lunch2,new_dinner,delta) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)", $product->product_id, $datetime, $product->category_id, 0, 0, 0, 0, $sum, 0, $settings->breakfast, $settings->lunch, $settings->lunch2, $settings->dinner, $sum);
        //echo "<br />$query<br />\n";
        $db2->query($query);
        if ($db2->affected_rows() > 0)
            echo "<span style='color:Green'> успешно</span><br />\n";
        else
            echo "<span style='color:Red'> неудачно</span><br />\n";
        //echo "<br />$query<br />\n";
    }

    echo "</ol>\n";
}
else {
    echo "Обнаружено " . intval(@count($products)) . " учеников.<br />\n";

    echo "<br /><br /><a href=\"?go=true\">Желаете продолжить?</a>\n";
}
