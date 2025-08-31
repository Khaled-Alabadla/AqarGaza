<!DOCTYPE html>
<html dir="rtl" lang="ar">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: Arial, sans-serif;
            direction: rtl;
            text-align: right;
            color: #333;
            background-color: #f4f4f4;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3490dc;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }

        .button:hover {
            background-color: #2779bd;
        }

        h1 {
            font-size: 24px;
            color: #333;
        }

        p {
            font-size: 16px;
            line-height: 1.5;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>مرحبًا!</h1>
        <p>شكرًا لتسجيلك معنا. يرجى التحقق من عنوان بريدك الإلكتروني بالنقر على الزر أدناه.</p>
        <p>
            <a href="{{ $actionUrl }}" class="button">تحقق من بريدك الإلكتروني</a>
        </p>
        <p>إذا لم تقم بإنشاء حساب، يرجى تجاهل هذا البريد الإلكتروني.</p>
        <p>مع تحيات،<br>{{ config('app.name') }}</p>
    </div>
</body>

</html>
