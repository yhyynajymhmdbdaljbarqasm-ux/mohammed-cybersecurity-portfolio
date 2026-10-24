<?php
include 'db.php';
checkAuth();

$rental_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// جلب تفاصيل العقد
$stmt = mysqli_prepare($conn, "SELECT * FROM rentals WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $rental_id);
mysqli_stmt_execute($stmt);
$rental = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$rental) {
    header("Location: rentals_list.php");
    exit();
}

$msg = "";
$latest_payment_id = 0; // لطباعة السند الأخير تلقائياً إذا رغبت

// إضافة دفعة مالية جديدة (واصل)
if (isset($_POST['add_payment'])) {
    $amount = floatval($_POST['amount_paid']);
    $date = $_POST['payment_date'];
    $notes = trim($_POST['notes']);

    if ($amount > 0) {
        $stmt_pay = mysqli_prepare($conn, "INSERT INTO rental_payments (rental_id, amount_paid, payment_date, notes) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt_pay, "idss", $rental_id, $amount, $date, $notes);
        mysqli_stmt_execute($stmt_pay);
        $latest_payment_id = mysqli_insert_id($conn); // معرف الدفعة المضافة حديثاً
        mysqli_stmt_close($stmt_pay);
        $msg = "تم تسجيل الدفعة بنجاح وتحديث الحساب تلقائياً!";
    }
}

// حذف دفعة سابقة
if (isset($_GET['delete_payment'])) {
    $pay_id = intval($_GET['delete_payment']);
    mysqli_query($conn, "DELETE FROM rental_payments WHERE id = $pay_id AND rental_id = $rental_id");
    $msg = "تم حذف الدفعة بنجاح.";
}

// حساب المبالغ تلقائياً
$sum_query = mysqli_query($conn, "SELECT SUM(amount_paid) as total_paid FROM rental_payments WHERE rental_id = $rental_id");
$sum_res = mysqli_fetch_assoc($sum_query);
$total_paid = $sum_res['total_paid'] ? $sum_res['total_paid'] : 0;
$total_price = $rental['total_price'];
$remaining_amount = $total_price - $total_paid;
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>حساب الواصل والمتبقي - شريان العرب</title>
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
        
        /* تنسيقات خاصة بسند القبض الرسمي عند الطباعة */
        .receipt-box {
            background: #fff;
            color: #000;
            border: 2px dashed #0d6efd;
            border-radius: 12px;
            padding: 30px;
            max-width: 700px;
            margin: 20px auto;
            display: none;
        }
        @media print {
            body * {
                visibility: hidden;
            }
            #printableReceipt, #printableReceipt * {
                visibility: visible;
            }
            #printableReceipt {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                display: block !important;
                background: #fff !important;
                color: #000 !important;
                border: none !important;
                box-shadow: none !important;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark px-4 mb-4 no-print">
        <a class="navbar-brand text-info fw-bold" href="rentals_list.php">← عودة لسجل المؤجرات</a>
        <div class="navbar-nav ms-auto">
            <a class="nav-link text-danger fw-bold" href="index.php?logout=1">تسجيل الخروج</a>
        </div>
    </nav>

    <div class="container py-4">
        <?php if($msg): ?><div class="alert alert-success text-center no-print"><?php echo htmlspecialchars($msg); ?></div><?php endif; ?>

        <div class="glass-panel mb-4 no-print">
            <h3 class="text-info mb-3">الحسابات المالية للعقد: <span class="text-white"><?php echo htmlspecialchars($rental['eq_name']); ?></span></h3>
            <p class="text-muted">الشركة المستأجرة: <strong class="text-white"><?php echo htmlspecialchars($rental['client_name']); ?></strong> | رقم القاعدة: <strong class="text-white"><?php echo htmlspecialchars($rental['base_number']); ?></strong></p>
            
            <div class="row g-3 mt-3">
                <div class="col-md-4">
                    <div class="p-3 bg-dark rounded border border-secondary">
                        <span class="text-muted d-block mb-1">إجمالي مبلغ العقد</span>
                        <h3 class="text-info fw-bold"><?php echo number_format($total_price, 2); ?> ر.ي</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 bg-dark rounded border border-secondary">
                        <span class="text-muted d-block mb-1">إجمالي الواصل (المستلم)</span>
                        <h3 class="text-success fw-bold"><?php echo number_format($total_paid, 2); ?> ر.ي</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 bg-dark rounded border border-secondary">
                        <span class="text-muted d-block mb-1">المبلغ المتبقي</span>
                        <h3 class="<?php echo $remaining_amount > 0 ? 'text-warning' : 'text-success'; ?> fw-bold">
                            <?php echo number_format($remaining_amount, 2); ?> ر.ي
                        </h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 no-print">
            <!-- نموذج تسجيل دفعة جديدة -->
            <div class="col-md-5">
                <div class="glass-panel">
                    <h4 class="text-warning mb-3">تسجيل دفعة نقدية جديدة (واصل)</h4>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">المبلغ المدفوع (الواصل):</label>
                            <input type="number" step="0.01" name="amount_paid" class="form-control bg-transparent text-white border-secondary" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">تاريخ الدفع:</label>
                            <input type="date" name="payment_date" value="<?php echo date('Y-m-d'); ?>" class="form-control bg-transparent text-white border-secondary" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">ملاحظات (مثلاً: سند قبض رقم...):</label>
                            <input type="text" name="notes" class="form-control bg-transparent text-white border-secondary">
                        </div>
                        <button type="submit" name="add_payment" class="btn btn-warning w-100 fw-bold">إضافة الدفعة وتحديث الحساب</button>
                    </form>
                </div>
            </div>

            <!-- جدول الدفعات السابقة -->
            <div class="col-md-7">
                <div class="glass-panel">
                    <h4 class="text-info mb-3">سجل الدفعات الواصلة السابقة</h4>
                    <div class="table-responsive">
                        <table class="table table-dark table-hover align-middle text-center">
                            <thead>
                                <tr>
                                    <th>المبلغ الواصل</th>
                                    <th>التاريخ</th>
                                    <th>ملاحظات</th>
                                    <th>إجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $pay_res = mysqli_query($conn, "SELECT * FROM rental_payments WHERE rental_id = $rental_id ORDER BY payment_date DESC");
                                if(mysqli_num_rows($pay_res) > 0){
                                    while($p_row = mysqli_fetch_assoc($pay_res)){
                                        echo "<tr>";
                                        echo "<td class='text-success fw-bold'>".htmlspecialchars($p_row['amount_paid'])." ر.ي</td>";
                                        echo "<td>".htmlspecialchars($p_row['payment_date'])."</td>";
                                        echo "<td class='small text-muted'>".htmlspecialchars($p_row['notes'])."</td>";
                                        echo "<td>
                                                <button type='button' class='btn btn-outline-info btn-sm mb-1' onclick='printReceipt(\"".htmlspecialchars($p_row['id']).'","'.htmlspecialchars($p_row['amount_paid']).'","'.htmlspecialchars($p_row['payment_date']).'","'.htmlspecialchars($rental['client_name']).'","'.htmlspecialchars($rental['eq_name']).'","'.htmlspecialchars($p_row['notes'])."\")'>🖨️ طباعة السند</button>
                                                <a href='?id={$rental_id}&delete_payment={$p_row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"حذف هذه الدفعة؟\");'>حذف</a>
                                              </td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='4' class='text-muted'>لم يتم تسجيل أي دفعات مالية بعد.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- قالب سند القبض المخفي للطباعة -->
    <div id="printableReceipt" class="receipt-box">
        <div class="text-center border-bottom pb-3 mb-3">
            <h3 class="fw-bold text-dark m-0">شركة شريان العرب لتأجير المعدات الثقيلة</h3>
            <p class="text-muted small m-0">سند قبض رسمي</p>
        </div>
        <div class="row mb-3">
            <div class="col-6">
                <strong>رقم السند:</strong> <span id="r_id">---</span>
            </div>
            <div class="col-6 text-start">
                <strong>التاريخ:</strong> <span id="r_date">---</span>
            </div>
        </div>
        <div class="mb-3 p-2 bg-light border rounded">
            <p class="mb-1"><strong>استلمنا من الأخ / الشركة:</strong> <span id="r_client" class="text-primary fw-bold">---</span></p>
            <p class="mb-1"><strong>مبلغ وقدره:</strong> <span id="r_amount" class="text-success fw-bold">---</span> ريالا سعودي</p>
            <p class="mb-0"><strong>وذلك مقابل أجور تأجير معدة:</strong> <span id="r_eq" class="fw-bold">---</span></p>
        </div>
        <div class="mb-4">
            <strong>ملاحظات:</strong> <span id="r_notes">---</span>
        </div>
        <div class="row mt-5 pt-4 border-top">
            <div class="col-6 text-center">
                <p class="mb-5"><strong>المحاسب / أمين الصندوق</strong></p>
                <p>التوقيع: ..........................</p>
            </div>
            <div class="col-6 text-center">
                <p class="mb-5"><strong>الختم الرسمي</strong></p>
                <p>..........................................</p>
            </div>
        </div>
    </div>

    <script>
        function printReceipt(id, amount, date, client, eq, notes) {
            document.getElementById('r_id').innerText = '#' + id;
            document.getElementById('r_date').innerText = date;
            document.getElementById('r_client').innerText = client;
            document.getElementById('r_amount').innerText = Number(amount).toLocaleString();
            document.getElementById('r_eq').innerText = eq;
            document.getElementById('r_notes').innerText = notes ? notes : 'لا توجد ملاحظات';
            
            window.print();
        }

        <?php if($latest_payment_id > 0): ?>
            // إذا تم إضافة دفعة جديدة بنجاح، يمكننا جلب تفاصيلها وطباعتها مباشرة أو تركها متاحة في الجدول
        <?php endif; ?>
    </script>
</body>
</html>