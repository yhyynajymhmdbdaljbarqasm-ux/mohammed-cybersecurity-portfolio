<?php
require_once "auth.php";requireAdmin();require_once "db.php";
if($_SERVER["REQUEST_METHOD"]!=="POST")exit("طريقة الحذف غير مسموحة.");verify_csrf();$id=(int)($_POST["id"]??0);if($id<=0)exit("رقم غير صحيح.");
$s=$conn->prepare("SELECT student_image FROM students WHERE id=?");$s->bind_param("i",$id);$s->execute();$st=$s->get_result()->fetch_assoc();if(!$st)exit("الطالب غير موجود.");
$conn->begin_transaction();try{$a=$conn->prepare("DELETE FROM course_student WHERE student_id=?");$a->bind_param("i",$id);$a->execute();$b=$conn->prepare("DELETE FROM students WHERE id=?");$b->bind_param("i",$id);$b->execute();$conn->commit();if(!empty($st["student_image"]))@unlink(__DIR__."/".$st["student_image"]);header("Location:students.php");exit;}catch(Throwable $e){$conn->rollback();exit("حدث خطأ أثناء الحذف.");}
?>