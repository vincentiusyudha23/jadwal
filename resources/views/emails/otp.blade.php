<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP Anda</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            max-width: 100%;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .logo {
            width: 100px;
            margin-bottom: 10px;
        }
        .otp {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            padding: 10px;
            border-radius: 5px;
            background: #ecf0f1;
            display: inline-block;
        }
        .footer {
            font-size: 12px;
            color: #7f8c8d;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="{{ assets('img/logo-1.png') }}" alt="PT. WIRA GRIYA" class="logo">

        <h2>Kode OTP Anda</h2>
        <p>Gunakan kode berikut untuk memulihkan kata sandi anda:</p>
        <p class="otp">{{ $otp }}</p>
        <p>Kode ini berlaku selama <strong>5 menit</strong>. Jangan berikan kode ini kepada siapa pun.</p>
        <p>Jika Anda tidak meminta kode ini, abaikan email ini.</p>
        <p class="footer">© {{ date('Y') }} PT. WIRA GRIYA. Semua hak dilindungi.</p>
    </div>
</body>
</html>
