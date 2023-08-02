<?php
$servername='localhost';
$username='root';
$password='';
$dbname="crud";
$conn=mysqli_connect($servername,$username,$password,"$dbname");
if(!$conn){
    die('Couldnt connect to MySqli' .mysql_error());
}
?>