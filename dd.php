<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "db_students";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("فشل الاتصال بقاعدة البيانات: " . mysqli_connect_error());
}



?>