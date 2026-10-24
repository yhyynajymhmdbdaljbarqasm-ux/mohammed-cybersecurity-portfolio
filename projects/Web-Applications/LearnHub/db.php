<?php
$host="localhost";$user="root";$password="";$dbname="courses_db";
$conn=new mysqli($host,$user,$password,$dbname);
if($conn->connect_error) die("فشل الاتصال بقاعدة البيانات.");
$conn->set_charset("utf8mb4");
?>