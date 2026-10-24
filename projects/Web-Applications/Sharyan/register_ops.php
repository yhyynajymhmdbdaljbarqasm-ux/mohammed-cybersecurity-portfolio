<?php
require_once 'db.php';
checkAuth('add_equipment'); // منع أي مستخدم ليس لديه صلاحية إضافة معدة من الدخول
$msg = ""; $error = "";

// إضافة معدة جديدة للأسطول
if (isset($_POST['add_equipment'])) {
    $name = trim($_POST['eq_name']);
    $desc = trim($_POST['eq_desc']);
    $price = floatval($_POST['eq_price']);
    $gps = trim($_POST['eq_gps']);
    $img = uploadFile('eq_image', ['jpg', 'jpeg', 'png', 'gif']);

    if (!empty($name) && $price > 0) {
        $stmt = mysqli_prepare($conn, "INSERT INTO equipment (eq_name, description, daily_price, image, gps_link) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssdss", $name, $desc, $price, $img, $gps);
        if(mysqli_stmt_execute($stmt)) { $msg = "تم تسجيل المعدة في الأسطول بنجاح!"; }
        else { $error = "اسم المعدة موجود مسبقاً."; }
        mysqli_stmt_close($stmt);
    } else {
        $error = "الرجاء إدخال بيانات صحيحة.";
    }
}

// تسجيل عقد تأجير معدة (مع دعم رفع ملف PDF العقد)
if (isset($_POST['add_rental'])) {
    $eq_name = trim($_POST['rental_eq_name']);
    $client = trim($_POST['client_name']);
    $base = trim($_POST['base_number']);
    $loc = trim($_POST['rental_location']);
    $start = $_POST['start_date'];
    $end = $_POST['end_date'];
    
    // رفع ملف الـ PDF الخاص بالعقد
    $pdf_file = uploadFile('contract_pdf', ['pdf']);

    $d1 = new DateTime($start);
    $d2 = new DateTime($end);
    $days = $d1->diff($d2)->days;
    if ($days <= 0) $days = 1;

    $stmt_p = mysqli_prepare($conn, "SELECT daily_price FROM equipment WHERE eq_name = ?");
    mysqli_stmt_bind_param($stmt_p, "s", $eq_name);
    mysqli_stmt_execute($stmt_p);
    $res_p = mysqli_stmt_get_result($stmt_p);
    
    if($row_p = mysqli_fetch_assoc($res_p)){
        $daily = $row_p['daily_price'];
        $total = $daily * $days;

        $stmt_ins = mysqli_prepare($conn, "INSERT INTO rentals (eq_name, client_name, base_number, rental_location, start_date, end_date, daily_price, total_price, contract_pdf) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt_ins, "ssssssdds", $eq_name, $client, $base, $loc, $start, $end, $daily, $total, $pdf_file);
        mysqli_stmt_execute($stmt_ins);
        mysqli_stmt_close($stmt_ins);
        $msg = "تم تسجيل عقد التأجير ورفع ملف PDF بنجاح! الإجمالي: $total ريال.";
    }
    mysqli_stmt_close($stmt_p);
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تسجيل المعدات والعقود - شريان العرب</title>
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
        <a class="navbar-brand text-info fw-bold" href="index.php"> نظام شريان العرب لتاجير المعدات   </a>
        <div class="navbar-nav ms-auto">
            <a class="nav-link" href="index.php"> الرئيسية </a>
            <a class="nav-link active" href="register_ops.php">تسجيل المعدات والعقود</a>
            <a class="nav-link" href="fleet.php">عرض الأسطول</a>
            <a class="nav-link" href="rentals_list.php">سجل المؤجرات</a>
            <a class="nav-link text-danger fw-bold" href="index.php?logout=1">تسجيل الخروج</a>
        </div>
    </nav>

    <div class="container py-4">
        <?php if($msg): ?><div class="alert alert-success text-center"><?php echo htmlspecialchars($msg); ?></div><?php endif; ?>
        <?php if($error): ?><div class="alert alert-danger text-center"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="glass-panel">
                    <h4 class="text-info mb-3">تسجيل معدة جديدة في الأسطول</h4>
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">اسم المعدة:</label>
                            <input type="text" name="eq_name" class="form-control bg-transparent text-white border-secondary" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">تفاصيل المعدة:</label>
                            <textarea name="eq_desc" class="form-control bg-transparent text-white border-secondary" rows="2" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">السعر اليومي:</label>
                            <input type="number" step="0.01" name="eq_price" class="form-control bg-transparent text-white border-secondary" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">رابط تتبع الـ GPS:</label>
                            <input type="url" name="eq_gps" class="form-control bg-transparent text-white border-secondary" placeholder="https://maps.google.com/..." required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">صورة المعدة:</label>
                            <input type="file" name="eq_image" class="form-control bg-transparent text-white border-secondary" accept="image/*">
                        </div>
                        <button type="submit" name="add_equipment" class="btn btn-info w-100 fw-bold">حفظ المعدة</button>
                    </form>
                </div>
            </div>

            <div class="col-md-6">
                <div class="glass-panel">
                    <h4 class="text-warning mb-3">تسجيل عقد تأجير ومعلومات الشركة المستأجرة</h4>
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">اختر المعدة المتوفرة:</label>
                            <select name="rental_eq_name" class="form-select bg-dark text-white border-secondary" required>
                                <option value="">-- اختر المعدة --</option>
                                <?php
                                $eq_drop = mysqli_query($conn, "SELECT eq_name FROM equipment");
                                while($e = mysqli_fetch_assoc($eq_drop)){
                                    echo "<option value='".htmlspecialchars($e['eq_name'])."'>".htmlspecialchars($e['eq_name'])."</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">اسم الشركة المستأجرة (العميل):</label>
                            <input type="text" name="client_name" class="form-control bg-transparent text-white border-secondary" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">رقم القاعدة:</label>
                            <input type="text" name="base_number" class="form-control bg-transparent text-white border-secondary" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">موقع التأجير:</label>
                            <input type="text" name="rental_location" class="form-control bg-transparent text-white border-secondary" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">تاريخ التأجير:</label>
                                <input type="date" name="start_date" class="form-control bg-transparent text-white border-secondary" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">تاريخ الانتهاء:</label>
                                <input type="date" name="end_date" class="form-control bg-transparent text-white border-secondary" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">ملف عقد التأجير (PDF للشركة المستأجرة):</label>
                            <input type="file" name="contract_pdf" class="form-control bg-transparent text-white border-secondary" accept="application/pdf" required>
                        </div>
                        <button type="submit" name="add_rental" class="btn btn-warning w-100 fw-bold">تسجيل العقد وحساب الإجمالي</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>