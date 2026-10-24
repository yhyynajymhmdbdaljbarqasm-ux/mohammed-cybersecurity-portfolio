<?php
include 'db.php';
$msg = ""; $error = "";

// تسجيل الدخول
if (isset($_POST['login'])) {
    $user = trim($_POST['login_username']);
    $pass = $_POST['login_password'];
    
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $user);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($pass, $row['password'])) {
            $_SESSION['admin'] = $row['username'];
            // تخزين الصلاحيات في الجلسة
            $_SESSION['permissions'] = $row['permissions'] ?? 'all';
            header("Location: index.php");
            exit();
        } else {
            $error = "كلمة المرور غير صحيحة!";
        }
    } else {
        $error = "اسم المستخدم غير موجود!";
    }
    mysqli_stmt_close($stmt);
}

// تسجيل الخروج
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

// إنشاء حساب جديد مع تحديد الصلاحيات
if (isset($_POST['add_user'])) {
    $user = trim($_POST['username']);
    $pass = $_POST['password'];
    
    // جمع الصلاحيات المحددة من نموذج مربعات الاختيار
    $perms_array = isset($_POST['permissions']) ? $_POST['permissions'] : [];
    $permissions_str = !empty($perms_array) ? implode(',', $perms_array) : '';
    
    if (!empty($user) && !empty($pass)) {
        $check_stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE username = ?");
        mysqli_stmt_bind_param($check_stmt, "s", $user);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);
        
        if (mysqli_stmt_num_rows($check_stmt) > 0) {
            $error = "اسم المستخدم موجود مسبقاً، يرجى اختيار اسم آخر.";
        } else {
            $hashed = password_hash($pass, PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($conn, "INSERT INTO users (username, password, permissions) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "sss", $user, $hashed, $permissions_str);
            if (mysqli_stmt_execute($stmt)) {
                $msg = "تم إنشاء المستخدم الجديد بنجاح مع الصلاحيات المحددة!";
            } else {
                $error = "حدث خطأ أثناء حفظ المستخدم.";
            }
            mysqli_stmt_close($stmt);
        }
        mysqli_stmt_close($check_stmt);
    } else {
        $error = "الرجاء إدخال اسم المستخدم وكلمة المرور.";
    }
}

// حذف مستخدم
if (isset($_GET['delete_user']) && isset($_SESSION['admin'])) {
    $id = intval($_GET['delete_user']);
    mysqli_query($conn, "DELETE FROM users WHERE id = $id");
    $msg = "تم حذف المستخدم بنجاح.";
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>نظام شريان العرب لتاجير المعدات</title>
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
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark px-4 mb-4">
        <a class="navbar-brand text-info fw-bold" href="index.php">نظام شريان العرب لتاجير المعدات</a>
        <?php if(isset($_SESSION['admin'])): ?>
        <div class="navbar-nav ms-auto align-items-center">
          <a class="nav-link" href="index.php">الرئيسية </a>
            <a class="nav-link" href="register_ops.php">تسجيل المعدات والعقود</a>
            <a class="nav-link" href="fleet.php">عرض الأسطول</a>
            <a class="nav-link active" href="rentals_list.php">سجل المؤجرات</a>
            <a class="nav-link text-danger fw-bold" href="index.php?logout=1">تسجيل الخروج</a>
            <!-- زر الإعدادات -->
            <div class="nav-item dropdown mx-2">
                <a class="nav-link dropdown-toggle fs-5 text-warning text-decoration-none" href="#" id="settingsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" title="الإعدادات">
                    ⚙️
                </a>
                <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end p-3 shadow-lg" aria-labelledby="settingsDropdown" style="width: 300px; border-radius: 12px; margin-top: 10px; right: 0; left: auto;">
                    <li><h6 class="dropdown-header text-info px-0 mb-2">إعدادات النظام وإضافة مستخدم</h6></li>
                    
                    <!-- نموذج تسجيل مستخدم جديد مع الصلاحيات -->
                    <li>
                        <form method="POST" class="px-0">
                            <div class="mb-2">
                                <label class="form-label small text-light">اسم المستخدم الجديد:</label>
                                <input type="text" name="username" class="form-control form-control-sm bg-transparent text-white border-secondary" required>
                            </div>
                            <div class="mb-2">
                                <label class="form-label small text-light">كلمة المرور:</label>
                                <input type="password" name="password" class="form-control form-control-sm bg-transparent text-white border-secondary" required>
                            </div>
                            
                            <!-- تحديد الصلاحيات -->
                            <div class="mb-3">
                                <label class="form-label small text-warning fw-bold">صلاحيات الصفحات:</label>
                                <div class="form-check text-start">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="index" id="perm_index" checked>
                                    <label class="form-check-label small text-light" for="perm_index">الصفحة الرئيسية / لوحة التحكم</label>
                                </div>
                                <div class="form-check text-start">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="register_ops" id="perm_add">
                                    <label class="form-check-label small text-light" for="perm_add">صفحة تسجيل المعدات والعقود</label>
                                </div>
                                <div class="form-check text-start">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="fleet" id="perm_fleet">
                                    <label class="form-check-label small text-light" for="perm_fleet">عرض الأسطول</label>
                                </div>
                                <div class="form-check text-start">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="rentals_list" id="perm_rentals">
                                    <label class="form-check-label small text-light" for="perm_rentals">سجل المؤجرات</label>
                                </div>
                            </div>

                            <button type="submit" name="add_user" class="btn btn-success btn-sm w-100 fw-bold">حفظ المستخدم</button>
                        </form>
                    </li>
                    <li><hr class="dropdown-divider border-secondary my-3"></li>
                    <li>
                        <button class="btn btn-outline-info btn-sm w-100 fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#usersTableCollapse" aria-expanded="false" aria-controls="usersTableCollapse">
                            سجل المستخدمين المسجلين 👥
                        </button>
                    </li>
                </ul>
            </div>

            <a class="nav-link text-danger fw-bold ms-2" href="?logout=1">تسجيل الخروج</a>
        </div>
        <?php endif; ?>
    </nav>

    <div class="container py-4">
        <?php if($msg): ?><div class="alert alert-success text-center"><?php echo $msg; ?></div><?php endif; ?>
        <?php if($error): ?><div class="alert alert-danger text-center"><?php echo $error; ?></div><?php endif; ?>

        <?php if (!isset($_SESSION['admin'])): ?>
            <!-- نموذج تسجيل الدخول -->
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <div class="glass-panel">
                        <h4 class="text-info mb-3 text-center">تسجيل الدخول للنظام</h4>
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">اسم المستخدم:</label>
                                <input type="text" name="login_username" class="form-control bg-transparent text-white border-secondary" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">كلمة المرور:</label>
                                <input type="password" name="login_password" class="form-control bg-transparent text-white border-secondary" required>
                            </div>
                            <button type="submit" name="login" class="btn btn-info w-100 fw-bold">دخول</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- لوحة الترحيب -->
            <div class="row justify-content-center">
                <div class="col-md-8 text-center">
                    <div class="glass-panel py-5">
                        <h2 class="text-info mb-3">مرحباً بك في نظام شريان العرب لتاجير المعدات</h2>
                        <p class="text-light">يمكنك إدارة الأسطول، وعقود التأجير، وإضافة مستخدمين جدد وتحديد صلاحياتهم عبر الضغط على أيقونة الإعدادات (⚙️) في الأعلى.</p>
                    </div>
                </div>
            </div>

            <!-- جدول المستخدمين المسجلين -->
            <div class="row justify-content-center mt-4">
                <div class="col-md-8">
                    <div class="collapse" id="usersTableCollapse">
                        <div class="glass-panel">
                            <h4 class="text-info mb-3 text-center">قائمة المستخدمين المسجلين</h4>
                            <div class="table-responsive">
                                <table class="table table-dark table-hover text-center align-middle">
                                    <thead><tr><th>#</th><th>اسم المستخدم</th><th>الصلاحيات</th><th>الإجراء</th></tr></thead>
                                    <tbody>
                                        <?php
                                        $res = mysqli_query($conn, "SELECT * FROM users");
                                        while($row = mysqli_fetch_assoc($res)){
                                            echo "<tr>";
                                            echo "<td>{$row['id']}</td>";
                                            echo "<td class='fw-bold'>".htmlspecialchars($row['username'])."</td>";
                                            echo "<td><small class='text-warning'>".htmlspecialchars($row['permissions'])."</small></td>";
                                            echo "<td><a href='?delete_user={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"حذف هذا المستخدم؟\");'>حذف</a></td>";
                                            echo "</tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>