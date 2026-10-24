<?php
require_once "auth.php";
requireAdmin();
require_once "db.php";

$cid=(int)($_GET["course_id"]??$_POST["course_id"]??0);
$s=$conn->prepare("SELECT id,course_name FROM course WHERE id=?");
$s->bind_param("i",$cid);
$s->execute();
$course=$s->get_result()->fetch_assoc();
if(!$course) exit("الكورس غير موجود.");
$message="";

function studentImage($file){
    if(($file["error"]??4)!==UPLOAD_ERR_OK || ($file["size"]??0)>5242880) return false;
    $f=new finfo(FILEINFO_MIME_TYPE);
    $m=$f->file($file["tmp_name"]);
    $a=["image/jpeg"=>"jpg","image/png"=>"png","image/webp"=>"webp","image/gif"=>"gif"];
    if(!isset($a[$m])) return false;
    $d=__DIR__."/uploads/students";
    if(!is_dir($d)) mkdir($d,0755,true);
    $n=bin2hex(random_bytes(16)).".".$a[$m];
    return move_uploaded_file($file["tmp_name"],$d."/".$n)?"uploads/students/".$n:false;
}

if($_SERVER["REQUEST_METHOD"]==="POST"){
    verify_csrf();
    $name=trim($_POST["student_name"]??"");
    $phone=trim($_POST["phone"]??"");

    if(mb_strlen($name)<2||mb_strlen($name)>150){
        $message="اسم الطالب يجب أن يكون بين 2 و150 حرفاً.";
    }elseif($phone!=="" && !preg_match('/^[0-9+\-\s]{6,20}$/',$phone)){
        $message="رقم الهاتف غير صحيح.";
    }else{
        $img=studentImage($_FILES["student_image"]??[]);
        if(!$img){
            $message="الصورة غير صحيحة أو أكبر من 5MB.";
        }else{
            $conn->begin_transaction();
            try{
                $a=$conn->prepare("INSERT INTO students(student_name,phone,student_image) VALUES(?,?,?)");
                $a->bind_param("sss",$name,$phone,$img);
                $a->execute();
                $sid=$conn->insert_id;

                $b=$conn->prepare("INSERT INTO course_student(course_id,student_id) VALUES(?,?)");
                $b->bind_param("ii",$cid,$sid);
                $b->execute();
                $conn->commit();
                $message="تم التسجيل بنجاح.";
            }catch(Throwable $e){
                $conn->rollback();
                @unlink(__DIR__."/".$img);
                $message="حدث خطأ أثناء التسجيل.";
            }
        }
    }
}
?>
<!doctype html><html lang="ar" dir="rtl"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>تسجيل طالب</title><link rel="stylesheet" href="style.css"></head><body>
<div class="wrap"><div class="card"><h1>📝 تسجيل طالب في <?=htmlspecialchars($course["course_name"],ENT_QUOTES,"UTF-8")?></h1>
<?php if($message):?><div class="msg"><?=htmlspecialchars($message,ENT_QUOTES,"UTF-8")?></div><?php endif;?>
<form method="post" enctype="multipart/form-data">
<input type="hidden" name="course_id" value="<?=$cid?>"><input type="hidden" name="csrf_token" value="<?=htmlspecialchars(csrf_token(),ENT_QUOTES,"UTF-8")?>">
<label>اسم الطالب</label><input name="student_name" required maxlength="150">
<label>الهاتف</label><input name="phone" maxlength="20">
<label>الصورة</label><input type="file" name="student_image" accept="image/jpeg,image/png,image/webp,image/gif" required><br><br>
<button>تسجيل الطالب</button></form><br><a href="index.php">العودة</a></div></div></body></html>
