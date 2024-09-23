<?php
$localhost = "localhost"; 
$db_user = "root"; 
$db_pass = ""; 
$db_name = "univers"; 

$conn = mysqli_connect($localhost, $db_user, $db_pass, $db_name);

if (!$conn) {
    die("Ошибка подключения: " . mysqli_connect_error());
}
?>
