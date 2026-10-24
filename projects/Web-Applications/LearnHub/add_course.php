<?php
require_once "auth.php";requireAdmin();require_once "db.php";$message="";
function uploadImage($file){
 if(($file["error"]??4)!==UPLOAD_ERR_OK||($file["size"]??0)>5242880)return false;
 $f=new finfo(FILEINFO_MIME_TYPE);$m=$f->file($file["tmp_name"]);$a=["image/jpeg"=>"jpg","image/png"=>"png","image/webp"=>"webp","image/gif"=>"gif"];
 if(!isset($a[$m]))return false;$d=__DIR__."/uploads/courses";if(!is_dir($d))mkdir($d,0755,true);$n=bin2hex(random_bytes(16)).".".$a[$m];
 return move_uploaded_file($file["tmp_name"],$d."/".$n)?"uploads/courses/".$n:false;
}
if($_SERVER["REQUEST_METHOD"]==="POST"){verify_csrf();$name=trim($_POST["course_name"]??"");$price=trim($_POST["price"]??"");
 if(mb_strlen($name)<2||mb_strlen($name)>150)$message="اسم الكورس يجب أن يكون بين 2 و150 حرفاً.";elseif(!preg_match('/^\d+(?:\.\d{1,2})?$/',$price)||((float)$price<0)||((float)$price>99999999.99))$message="السعر غير صحيح.";
 else{$img=uploadImage($_FILES["course_image"]??[]);if(!$img)$message="الصورة غير صحيحة أو أكبر من 5MB.";else{$p=(float)$price;$s=$conn->prepare("INSERT INTO course(course_name,price,course_image) VALUES(?,?,?)");$s->bind_param("sds",$name,$p,$img);if($s->execute()){header("Location:index.php");exit;}@unlink(__DIR__."/".$img);$message="حدث خطأ.";}}}
?>
<!doctype html><html lang="ar" dir="rtl"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>إضافة كورس</title><link rel="stylesheet" href="style.css"></head><body><nav><b>🎓 منصة الكورسات</b><a href="index.php">الرئيسية</a><a href="students.php">الطلاب</a></nav><div class="wrap"><div class="card"><h1>➕ إضافة كورس</h1><?php if($message):?><div class="msg err"><?=htmlspecialchars($message,ENT_QUOTES,"UTF-8")?></div><?php endif;?><form method="post" enctype="multipart/form-data"><input type="hidden" name="csrf_token" value="<?=htmlspecialchars(csrf_token(),ENT_QUOTES,"UTF-8")?>"><label>اسم الكورس</label><input name="course_name" maxlength="150" required><label>السعر</label><input type="number" name="price" min="0" step=".01" required><label>الصورة</label><input type="file" name="course_image" accept="image/jpeg,image/png,image/webp,image/gif" required><br><br><button>حفظ</button></form></div></div></body></html>