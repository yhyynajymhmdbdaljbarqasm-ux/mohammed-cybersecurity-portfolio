<?php
require_once 'db.php';
checkAuth('fleet'); // التأكد هل يمتلك صلاحية دخول هذه الصفحة أم لا

$msg = "";

// حذف معدة من الأسطول
if (isset($_GET['delete_eq'])) {
    $id = intval($_GET['delete_eq']);
    // حذف الصورة المرتبطة إن وجدت
    $res_img = mysqli_query($conn, "SELECT image FROM equipment WHERE id = $id");
    if($img_row = mysqli_fetch_assoc($res_img)){
        if(!empty($img_row['image']) && file_exists("uploads/" . $img_row['image'])) {
            unlink("uploads/" . $img_row['image']);
        }
    }
    mysqli_query($conn, "DELETE FROM equipment WHERE id = $id");
    $msg = "تم حذف المعدة من الأسطول بنجاح.";
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>أسطول معدات شركة شريان العرب</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <style>
        body { 
            background: linear-gradient(rgba(20, 30, 48, 0.85), rgba(36, 59, 85, 0.85)), url('hh.PNG'); 
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: #fff; 
            min-height: 100vh; 
            font-family: 'Segoe UI', Tahoma, sans-serif; 
        }
        .glass-panel { 
            background: rgba(0, 0, 0, 0.5); 
            backdrop-filter: blur(14px); 
            border: 1px solid rgba(255, 255, 255, 0.1); 
            border-radius: 16px; 
            padding: 25px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.5); 
        }
        .navbar { 
            background: rgba(0,0,0,0.4) !important; 
            backdrop-filter: blur(10px); 
            border-bottom: 1px solid rgba(255,255,255,0.1); 
        }
        .dropdown-menu-dark { 
            background-color: #1a2639; 
            border: 1px solid rgba(255,255,255,0.15); 
        }
        /* تنسيق موحد وثابت لحجم الصور لتكون مربعة وصغيرة */
        .eq-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark px-4 mb-4">
        <a class="navbar-brand text-info fw-bold" href="index.php">شركة شريان العرب للمقاولات</a>
        <div class="navbar-nav ms-auto">
            <a class="nav-link" href="index.php">الرئيسية </a>
            <a class="nav-link" href="register_ops.php">تسجيل المعدات والعقود</a>
            <a class="nav-link active" href="fleet.php">عرض الأسطول</a>
            <a class="nav-link" href="rentals_list.php">سجل المؤجرات</a>
            <a class="nav-link text-danger fw-bold" href="index.php?logout=1">تسجيل الخروج</a>
        </div>
    </nav>

    <div class="container py-4">
        <?php if($msg): ?><div class="alert alert-success text-center"><?php echo htmlspecialchars($msg); ?></div><?php endif; ?>

        <div class="glass-panel">
            <h3 class="text-warning mb-4 text-center">أسطول معدات شركة شريان العرب</h3>
            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle text-center">
                    <thead>
                        <tr>
                            <th>الصورة</th>
                            <th>المعدة</th>
                            <th>التفاصيل</th>
                            <th>السعر اليومي</th>
                            <th>تتبع GPS</th>
                            <th>الإجراءات (تعديل / حذف)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $res = mysqli_query($conn, "SELECT * FROM equipment");
                        if(mysqli_num_rows($res) > 0){
                            while($row = mysqli_fetch_assoc($res)){
                                echo "<tr>";
                                echo "<td>";
                                if(!empty($row['image']) && file_exists("uploads/".$row['image'])) {
                                    echo "<img src='uploads/".htmlspecialchars($row['image'])."' class='eq-img' alt='معدة'>";
                                } else {
                                    echo "<span class='text-muted small'>بدون صورة</span>";
                                }
                                echo "</td>";
                                echo "<td class='fw-bold text-info'>".htmlspecialchars($row['eq_name'])."</td>";
                                echo "<td class='small text-muted'>".htmlspecialchars($row['description'])."</td>";
                                echo "<td class='text-success fw-bold'>".htmlspecialchars($row['daily_price'])." ر.ي</td>";
                                echo "<td><a href='".htmlspecialchars($row['gps_link'])."' target='_blank' class='btn btn-outline-info btn-sm'>تتبع GPS 📍</a></td>";
                                echo "<td>
                                        <a href='edit_equipment.php?id={$row['id']}' class='btn btn-warning btn-sm fw-bold me-1'>تعديل ✏️</a>
                                        <a href='?delete_eq={$row['id']}' class='btn btn-danger btn-sm fw-bold' onclick='return confirm(\"هل أنت متأكد من حذف هذه المعدة؟\");'>حذف 🗑️</a>
                                     </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-muted'>لا توجد معدات مسجلة في الأسطول حالياً.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>