<?php ob_start();

// $db_host  = 'localhost';
// $db_user  = 'root';

$db['db_host'] = 'localhost';
$db['db_user'] = 'root';
$db['db_pass'] = '';
$db['db_name'] = 'cms';

foreach($db as $key => $value){
  if (!defined(strtoupper($key))) {
    define(strtoupper($key), $value);
  }
}

// host, username, password, database name
$connection = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);

$query = "SET NAMES utf8";
mysqli_query($connection, $query);

// if($connection){
//   echo "We're connected to db";
// } die();

