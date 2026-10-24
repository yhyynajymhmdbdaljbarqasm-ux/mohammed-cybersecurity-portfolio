<?php
require_once "auth.php";requireAdmin();require_once "db.php";
if($_SERVER["REQUEST_METHOD"]!=="POST")exit("طريقة الحذف غير مسموحة.");verify_csrf();$id=(int)($_POST["id"]??0);if($id<=0)exit("رقم غير صحيح.");
$s=$conn->prepare("SELECT course_image FROM course WHERE id=?");$s->bind_param("i",$id);$s->execute();$c=$s->get_result()->fetch_assoc();if(!$c)exit("الكورس غير موجود.");
$d=$conn->prepare("DELETE FROM course WHERE id=?");$d->bind_param("i",$id);if($d->execute()){if(!empty($c["course_image"]))@unlink(__DIR__."/".$c["course_image"]);header("Location:index.php");exit;}exit("حدث خطأ.");
?>