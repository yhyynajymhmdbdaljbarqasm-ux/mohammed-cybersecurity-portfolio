<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$conn = mysqli_connect("localhost", "root", "", "universitydb");
if (!$conn) {
    die("فشل الاتصال بقاعدة البيانات: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "utf8");

// تعديل جدول المستخدمين لإضافة عمود الصلاحيات (permissions) إذا لم يكن موجوداً
$check_user_table = mysqli_query($conn, "SHOW COLUMNS FROM users LIKE 'permissions'");
if (mysqli_num_rows($check_user_table) == 0) {
    mysqli_query($conn, "ALTER TABLE users ADD COLUMN permissions TEXT DEFAULT 'all'"); 
    // القيمة الافتراضية 'all' تعني صلاحية كاملة للمشرف الأساسي
}

// إنشاء جدول المستخدمين وإدراج مستخدم افتراضي آمن (بحسب الكود الأصلي)
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    permissions TEXT DEFAULT 'all'
)");

$check_user = mysqli_query($conn, "SELECT * FROM users LIMIT 1");
if (mysqli_num_rows($check_user) == 0) {
    $default_pass = password_hash("123456", PASSWORD_DEFAULT);
    $stmt_def = mysqli_prepare($conn, "INSERT INTO users (username, password, permissions) VALUES (?, ?, ?)");
    $adm = 'admin';
    $all_perm = 'all'; // المشرف الافتراضي لديه كل الصلاحيات
    mysqli_stmt_bind_param($stmt_def, "sss", $adm, $default_pass, $all_perm);
    mysqli_stmt_execute($stmt_def);
    mysqli_stmt_close($stmt_def);
}

// 🛡️ دالة الحماية والتحقق من الصلاحيات المطورة
function checkAuth($required_page = '') {
    // 1. التأكد هل المستخدم مسجل دخول أم لا
    if (!isset($_SESSION['admin'])) {
        header("Location: index.php");
        exit();
    }

    // 2. إذا كان المشرف الأساسي (أو صلاحياته all)، نسمح له بالدخول لكل شي
    if (isset($_SESSION['permissions']) && $_SESSION['permissions'] === 'all') {
        return;
    }

    // 3. إذا تطلب الأمر صفحة معينة، نتحقق هل هي موجودة ضمن صلاحيات المستخدم في الجلسة
    if (!empty($required_page)) {
        $allowed_pages = isset($_SESSION['permissions']) ? explode(',', $_SESSION['permissions']) : [];
        // تنظيف المسافات إن وجدت
        $allowed_pages = array_map('trim', $allowed_pages);

        if (!in_array($required_page, $allowed_pages)) {
            // إذا لم تكن الصفحة مسموحة له، نظهر رسالة خطأ أو نحوله لصفحة أخرى
            echo "<script>alert('عذراً، لا تمتلك الصلاحية للوصول إلى هذه الصفحة!'); window.location.href='index.php';</script>";
            exit();
        }
    }
}

// إنشاء جدول الأسطول (المعدات)
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS equipment (
    id INT AUTO_INCREMENT PRIMARY KEY,
    eq_name VARCHAR(150) NOT NULL UNIQUE,
    description TEXT NOT NULL,
    daily_price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    gps_link VARCHAR(255)
)");

// إنشاء جدول عقود التأجير
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS rentals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    eq_name VARCHAR(150) NOT NULL,
    client_name VARCHAR(150) NOT NULL,
    base_number VARCHAR(100) NOT NULL,
    rental_location VARCHAR(255) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    daily_price DECIMAL(10,2) NOT NULL,
    total_price DECIMAL(10,2) NOT NULL
)");

// إضافة عمود ملف العقد (PDF) تلقائياً إذا لم يكن موجوداً
$check_col = mysqli_query($conn, "SHOW COLUMNS FROM rentals LIKE 'contract_pdf'");
if (mysqli_num_rows($check_col) == 0) {
    mysqli_query($conn, "ALTER TABLE rentals ADD COLUMN contract_pdf VARCHAR(255)");
}

// إنشاء جدول مخصص لتسجيل الدفعات
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS rental_payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rental_id INT NOT NULL,
    amount_paid DECIMAL(10,2) NOT NULL,
    payment_date DATE NOT NULL,
    notes VARCHAR(255)
)");

// دالة رفع الملفات والصور بأمان
function uploadFile($file_input_name, $allowed_types = ['jpg', 'jpeg', 'png', 'gif']) {
    if (isset($_FILES[$file_input_name]) && $_FILES[$file_input_name]['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $file_name = time() . "_" . basename($_FILES[$file_input_name]['name']);
        $target_file = $target_dir . $file_name;
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        if (in_array($file_type, $allowed_types)) {
            if (move_uploaded_file($_FILES[$file_input_name]['tmp_name'], $target_file)) {
                return $file_name;
            }
        }
    }
    return null;
}
?>