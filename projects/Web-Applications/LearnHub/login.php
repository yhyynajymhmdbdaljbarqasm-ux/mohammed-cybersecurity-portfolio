<?php
session_start();require_once "db.php";
if(isset($_SESSION["admin_id"])){header("Location:index.php");exit;}
if(empty($_SESSION["csrf_token"]))$_SESSION["csrf_token"]=bin2hex(random_bytes(32));
$errors=[];$email="";
if($_SERVER["REQUEST_METHOD"]==="POST"){
 if(!hash_equals($_SESSION["csrf_token"],$_POST["csrf_token"]??""))$errors[]="طلب غير صالح.";
 $email=trim($_POST["email"]??"");$password=$_POST["password"]??"";
 if(!filter_var($email,FILTER_VALIDATE_EMAIL))$errors[]="البريد الإلكتروني غير صحيح.";
 if($password==="")$errors[]="كلمة المرور مطلوبة.";
 if(!$errors){
  $s=$conn->prepare("SELECT id,name,email,password,role,status FROM admins WHERE email=? LIMIT 1");
  $s->bind_param("s",$email);$s->execute();$a=$s->get_result()->fetch_assoc();
  if(!$a||!password_verify($password,$a["password"]))$errors[]="البريد أو كلمة المرور غير صحيحة.";
  elseif((int)$a["status"]!==1)$errors[]="الحساب غير مفعل.";
  else{
   session_regenerate_id(true);$_SESSION["admin_id"]=$a["id"];$_SESSION["admin_name"]=$a["name"];$_SESSION["admin_email"]=$a["email"];$_SESSION["admin_role"]=$a["role"];$_SESSION["csrf_token"]=bin2hex(random_bytes(32));
   $u=$conn->prepare("UPDATE admins SET last_login_at=NOW() WHERE id=?");$u->bind_param("i",$a["id"]);$u->execute();
   header("Location:index.php");exit;
  }
 }
}
?>
<!doctype html><html lang="ar" dir="rtl"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>تسجيل الدخول</title><link rel="stylesheet" href="style.css"></head><body>
<div class="wrap" style="max-width:500px;margin-top:80px"><div class="card"><h1>🎓 تسجيل الدخول</h1><p>الدخول إلى لوحة إدارة الكورسات والطلاب.</p>
<?php foreach($errors as $e):?><div class="msg err"><?=htmlspecialchars($e,ENT_QUOTES,"UTF-8")?></div><?php endforeach;?>
<form method="post"><input type="hidden" name="csrf_token" value="<?=htmlspecialchars($_SESSION["csrf_token"],ENT_QUOTES,"UTF-8")?>">
<label>البريد الإلكتروني</label><input type="email" name="email" value="<?=htmlspecialchars($email,ENT_QUOTES,"UTF-8")?>" required>
<label>كلمة المرور</label><input type="password" name="password" required>
<br><br><button>دخول</button></form></div></div></body></html>