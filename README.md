# school2.ochve.su
School children eating management https://school2.ochve.su/

Config.class.php - Database connection class

  `var $dbname = "alex_school2";` - Name of DB
  
  `var $dbhost = "localhost";` - Host (port may be defined by "localhost:3310")
  
  `var $dbuser = 'alex';` - User
  
  `var $dbpass = '@di90HG18de73IO46es#';` - Password (MySQL 8 make password validation)
  
  `var $domain = "localhost";` - unused
  
  `var $key = "kieHWEeYutoIc";` - unused
  
  `ar $smarty_dir = "smarty/Smarty.class.php";` - path to Smarty
  
  `var $delay=0;` - cache lifetime (if used)
  
  Folders must have permission to write
  
  `cache`
  
  `templates_c`
  
  `web`
  
  `987admin123/templates_c`
  
 Database dump
  
  `backup/alex_school2_2023-02-11.sql.gz`
  
 Restore DB
 
 `zcat backup/alex_school2_2023-02-11.sql.gz | mysql -ualex -p@di90HG18de73IO46es# alex_school2`
