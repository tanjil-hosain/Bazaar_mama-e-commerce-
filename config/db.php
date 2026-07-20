<?php
$host = "localhost"; 
$user = "u100779598_bazaarmama2";     
$pass = "586892isdbTanjil"; 
$dbname = "u100779598_bazaar_mama_2";   

$db = mysqli_connect($host, $user, $pass, $dbname);

if (!$db) {
    die("Database Connection Failed: " . mysqli_connect_error());
}


if (!defined('BASE_URL')) {
    define('BASE_URL', 'https://bazaarmama.com/'); 
}
?>