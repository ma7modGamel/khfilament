<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تأكيد التسجيل</title>
    <style>
        body {
            font-family: 'DejaVu Sans', serif;
            direction: rtl;
            text-align: right;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 60%;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 24px;
            margin: 0;
            color: #333;
        }
        .content {
            font-size: 18px;
            line-height: 1.6;
            color: #555;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>أهلاً وسهلاً</h1>
        </div>
        <div class="content">
            <p>الموظف العزيز،</p>
            <p>نشكر لك تقديم طلب التسجيل الخاص بك. نود إعلامك بأننا قد استلمنا نموذج التسجيل الخاص بك وهو الآن قيد المراجعة من قبل الإدارة.</p>
            <p>سنقوم بإعلامك حالما يتم اتخاذ القرار النهائي بشأن طلبك.</p>
            <p>نقدر اهتمامك ونشكرك على صبرك.</p>
            <p>تمنياتنا لك بالتوفيق،</p>
            <p>فريق الإدارة</p>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} جميع الحقوق محفوظة.</p>
        </div>
    </div>
</body>
</html>
