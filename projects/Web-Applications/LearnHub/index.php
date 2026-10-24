<?php
require_once "auth.php";requireAdmin();require_once "db.php";
$q=trim($_GET["search"]??"");$like="%".$q."%";
if($q!==""){$s=$conn->prepare("SELECT id,course_name,price,course_image FROM course WHERE course_name LIKE ? ORDER BY id DESC");$s->bind_param("s",$like);$s->execute();$courses=$s->get_result();}
else $courses=$conn->query("SELECT id,course_name,price,course_image FROM course ORDER BY id DESC");
$totalC=(int)($conn->query("SELECT COUNT(*) n FROM course")->fetch_assoc()["n"]??0);
$totalS=(int)($conn->query("SELECT COUNT(*) n FROM students")->fetch_assoc()["n"]??0);
?>
<!doctype html><html lang="ar" dir="rtl"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Dashboard</title><link rel="stylesheet" href="style.css"></head><body>
<nav><b>🎓 منصة الكورسات</b><a href="index.php">Dashboard</a><a href="students.php">الطلاب</a><a href="add_course.php">إضافة كورس</a><a href="logout.php">خروج</a></nav>
<div class="wrap"><section class="hero"><h1>لوحة التحكم 🚀</h1><p>مرحباً <?=htmlspecialchars($currentUserName,ENT_QUOTES,"UTF-8")?>، إدارة كاملة للبيانات.</p><div class="stats"><div class="stat"><strong><?=$totalC?></strong>كورسات</div><div class="stat"><strong><?=$totalS?></strong>طلاب</div></div></section>
<div class="card"><form><input name="search" placeholder="ابحث عن كورس..." value="<?=htmlspecialchars($q,ENT_QUOTES,"UTF-8")?>"><br><br><button>بحث</button></form></div><br>
<div class="grid">
<?php if($courses&&$courses->num_rows):while($c=$courses->fetch_assoc()):?><article class="card">
<?php if($c["course_image"]&&is_file(__DIR__."/".$c["course_image"])):?><img class="course-img" src="<?=htmlspecialchars($c["course_image"],ENT_QUOTES,"UTF-8")?>" alt=""><?php endif;?>
<h2><?=htmlspecialchars($c["course_name"],ENT_QUOTES,"UTF-8")?></h2><h3><?=number_format((float)$c["price"],2)?> $</h3>
<div class="actions"><a class="btn" href="edit_course.php?id=<?=$c["id"]?>">تعديل</a><form method="post" action="delete_course.php" onsubmit="return confirm('حذف الكورس؟')"><input type="hidden" name="id" value="<?=$c["id"]?>"><input type="hidden" name="csrf_token" value="<?=htmlspecialchars(csrf_token(),ENT_QUOTES,"UTF-8")?>"><button class="btn-danger">حذف</button></form></div>
</article><?php endwhile;else:?><div class="card">لا توجد كورسات.</div><?php endif;?>
</div></div></body></html>