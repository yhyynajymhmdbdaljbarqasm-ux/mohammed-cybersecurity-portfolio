النسخة النهائية لمشروع تطوير تطبيقات الويب

تم تطبيق المتطلبات الأساسية:
1- واجهة مستخدم حديثة ومتجاوبة.
2- تسجيل دخول.
3- إنشاء أول حساب مدير بشكل آمن.
4- Dashboard.
5- CRUD كامل للكورسات: إضافة/عرض/تعديل/حذف.
6- CRUD كامل للطلاب: تسجيل/عرض/تعديل/حذف.
7- Validation للمدخلات.
8- Password hashing و password_verify.
9- Prepared Statements لمنع SQL Injection.
10- htmlspecialchars عند عرض البيانات لمنع XSS.
11- صلاحيات admin.
12- CSRF على عمليات POST.
13- رفع الصور مع فحص MIME فعلي وحد 5MB.
14- Transaction عند ربط الطالب بالكورس وعند حذفه.
15- قاعدة البيانات والجداول موجودة في schema.sql.

التشغيل:
- استورد schema.sql في phpMyAdmin.
- تأكد من db.php.
- ضع المشروع داخل htdocs.
- افتح register_admin.php مرة واحدة لإنشاء المدير الأول.
- بعدها افتح login.php.
- بعد تسجيل الدخول تصبح index.php هي Dashboard.
