<?php
include 'db.php';
checkAuth();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$msg = ""; $error = "";

// جلب بيانات المعدة الحالية
$stmt = mysqli_prepare($conn, "SELECT * FROM equipment WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$equipment = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$equipment) {
    header("Location: fleet.php");
    exit();
}

// معالجة تحديث البيانات
if (isset($_POST['update_equipment'])) {
    $name = trim($_POST['eq_name']);
    $desc = trim($_POST['eq_desc']);
    $price = floatval($_POST['eq_price']);
    $gps = trim($_POST['eq_gps']);
    
    // التحقق هل تم رفع صورة جديدة أم لا
    $img = $equipment['image'];
    if (isset($_FILES['eq_image']) && $_FILES['eq_image']['error'] == 0) {
        $new_img = uploadFile('eq_image', ['jpg', 'jpeg', 'png', 'gif']);
        if ($new_img) {
            // حذف القديمة إن وجدت
            if(!empty($equipment['image']) && file_exists("uploads/" . $equipment['image'])) {
                unlink("uploads/" . $equipment['image']);
            }
            $img = $new_img;
        }
    }

    if (!empty($name) && $price > 0) {
        $stmt_up = mysqli_prepare($conn, "UPDATE equipment SET eq_name = ?, description = ?, daily_price = ?, image = ?, gps_link = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt_up, "ssdssi", $name, $desc, $price, $img, $gps, $id);
        if(mysqli_stmt_execute($stmt_up)){
            $msg = "تم تحديث بيانات المعدة بنجاح!";
            // تحديث البيانات المعروضة في النموذج
            $equipment['eq_name'] = $name;
            $equipment['description'] = $desc;
            $equipment['daily_price'] = $price;
            $equipment['gps_link'] = $gps;
            $equipment['image'] = $img;
        } else {
            $error = "حدث خطأ أثناء التحديث (ربما اسم المعدة موجود مسبقاً).";
        }
        mysqli_stmt_close($stmt_up);
    } else {
        $error = "الرجاء إدخال بيانات صحيحة.";
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تعديل بيانات المعدة - شريان العرب</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
<style>
        body { 
            /* ضع اسم صورتك هنا بدل 'اسم_الصورة.jpg' وتأكد أنها بنفس المجلد */
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
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark px-4 mb-4">
        <a class="navbar-brand text-info fw-bold" href="fleet.php">← عودة لصفحة الأسطول</a>
        <div class="navbar-nav ms-auto">
            <a class="nav-link text-danger fw-bold" href="index.php?logout=1">تسجيل الخروج</a>
        </div>
    </nav>

    <div class="container py-4" style="max-width: 600px;">
        <?php if($msg): ?><div class="alert alert-success text-center"><?php echo htmlspecialchars($msg); ?></div><?php endif; ?>
        <?php if($error): ?><div class="alert alert-danger text-center"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>

        <div class="glass-panel">
            <h4 class="text-warning mb-4 text-center">تعديل بيانات المعدة: <?php echo htmlspecialchars($equipment['eq_name']); ?></h4>
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">اسم المعدة:</label>
                    <input type="text" name="eq_name" value="<?php echo htmlspecialchars($equipment['eq_name']); ?>" class="form-control bg-transparent text-white border-secondary" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">تفاصيل المعدة:</label>
                    <textarea name="eq_desc" class="form-control bg-transparent text-white border-secondary" rows="3" required><?php echo htmlspecialchars($equipment['description']); ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">السعر اليومي:</label>
                    <input type="number" step="0.01" name="eq_price" value="<?php echo htmlspecialchars($equipment['daily_price']); ?>" class="form-control bg-transparent text-white border-secondary" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">رابط تتبع الـ GPS:</label>
                    <input type="url" name="eq_gps" value="<?php echo htmlspecialchars($equipment['gps_link']); ?>" class="form-control bg-transparent text-white border-secondary" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">صورة المعدة الجديدة (اتركها فارغة إن لم ترد تغييرها):</label>
                    <input type="file" name="eq_image" class="form-control bg-transparent text-white border-secondary" accept="image/*">
                </div>
                <button type="submit" name="update_equipment" class="btn btn-warning w-100 fw-bold">حفظ التعديلات</button>
            </form>
        </div>
    </div>
</body>
</html>