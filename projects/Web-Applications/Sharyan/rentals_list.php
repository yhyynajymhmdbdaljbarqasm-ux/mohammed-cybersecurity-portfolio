<?php
include 'db.php';
checkAuth();
$msg = "";

if (isset($_GET['delete_rental'])) {
    $id = intval($_GET['delete_rental']);
    // حذف ملف الـ PDF المرتبط إن وجد
    $res_pdf = mysqli_query($conn, "SELECT contract_pdf FROM rentals WHERE id = $id");
    if($p = mysqli_fetch_assoc($res_pdf)){
        if(!empty($p['contract_pdf']) && file_exists("uploads/" . $p['contract_pdf'])) {
            unlink("uploads/" . $p['contract_pdf']);
        }
    }
    mysqli_query($conn, "DELETE FROM rental_payments WHERE rental_id = $id");
    mysqli_query($conn, "DELETE FROM rentals WHERE id = $id");
    $msg = "تم حذف سجل التأجير والمدفوعات بنجاح.";
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>سجل المعدات المؤجرة - شريان العرب</title>
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
        <a class="navbar-brand text-info fw-bold" href="index.php">شركة شريان العرب للمقاولات</a>
        <div class="navbar-nav ms-auto">
            <a class="nav-link" href="index.php">الرئيسية </a>
            <a class="nav-link" href="register_ops.php">تسجيل المعدات والعقود</a>
            <a class="nav-link" href="fleet.php">عرض الأسطول</a>
            <a class="nav-link active" href="rentals_list.php">سجل المؤجرات</a>
            <a class="nav-link text-danger fw-bold" href="index.php?logout=1">تسجيل الخروج</a>
        </div>
    </nav>

    <div class="container py-4">
        <?php if($msg): ?><div class="alert alert-success text-center"><?php echo htmlspecialchars($msg); ?></div><?php endif; ?>

        <div class="glass-panel">
            <h3 class="text-warning mb-4">سجل المعدات المؤجرة وتفاصيل عقود الشركات</h3>
            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle text-center">
                    <thead>
                        <tr>
                            <th>المعدة</th>
                            <th>الشركة المستأجرة</th>
                            <th>رقم القاعدة</th>
                            <th>الفترة</th>
                            <th>إجمالي السعر</th>
                            <th>ملف عقد PDF</th>
                            <th>حساب الواصل والمتبقي</th>
                            <th>الإجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $res = mysqli_query($conn, "SELECT * FROM rentals");
                        if(mysqli_num_rows($res) > 0){
                            while($row = mysqli_fetch_assoc($res)){
                                // حساب مجموع الواصل تلقائياً لهذا العقد
                                $r_id = $row['id'];
                                $sum_q = mysqli_query($conn, "SELECT SUM(amount_paid) as total_paid FROM rental_payments WHERE rental_id = $r_id");
                                $sum_r = mysqli_fetch_assoc($sum_q);
                                $total_paid = $sum_r['total_paid'] ? $sum_r['total_paid'] : 0;
                                $remaining = $row['total_price'] - $total_paid;

                                echo "<tr>";
                                echo "<td class='fw-bold text-info'>".htmlspecialchars($row['eq_name'])."</td>";
                                echo "<td>".htmlspecialchars($row['client_name'])."</td>";
                                echo "<td><span class='badge bg-secondary'>".htmlspecialchars($row['base_number'])."</span></td>";
                                echo "<td class='small'>من: ".htmlspecialchars($row['start_date'])."<br>إلى: ".htmlspecialchars($row['end_date'])."</td>";
                                echo "<td class='text-success fw-bold'>".htmlspecialchars($row['total_price'])." ر.ي</td>";
                                echo "<td>";
                                if(!empty($row['contract_pdf']) && file_exists("uploads/".$row['contract_pdf'])) {
                                    echo "<a href='uploads/".htmlspecialchars($row['contract_pdf'])."' target='_blank' class='badge-pdf'>عرض PDF 📄</a>";
                                } else {
                                    echo "<span class='text-muted small'>غير متوفر</span>";
                                }
                                echo "</td>";
                                echo "<td>
                                        <a href='rental_accounts.php?id={$row['id']}' class='btn btn-outline-info btn-sm fw-bold'>
                                            الواصل: {$total_paid} | المتبقي: <span class='text-warning'>{$remaining}</span> 🧮
                                        </a>
                                      </td>";
                                echo "<td><a href='?delete_rental={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"حذف هذا السجل مع حساباته؟\");'>حذف</a></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8' class='text-muted'>لا توجد عمليات تأجير مسجلة حالياً.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>