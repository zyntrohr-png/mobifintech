<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mobifintech staging</title>
    <style>
        * { box-sizing: border-box; }
        body {
            display: grid;
            min-height: 100vh;
            margin: 0;
            padding: 24px;
            place-items: center;
            background: #f3f6fb;
            color: #172033;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }
        main {
            width: min(100%, 560px);
            padding: 40px;
            border: 1px solid #e3e9f2;
            border-radius: 20px;
            background: #fff;
            box-shadow: 0 20px 60px rgb(26 45 80 / 9%);
        }
        .badge {
            display: inline-block;
            padding: 7px 11px;
            border-radius: 999px;
            background: #fff4dc;
            color: #82520b;
            font-size: 12px;
            font-weight: 700;
        }
        h1 { margin: 22px 0 12px; font-size: clamp(32px, 8vw, 46px); letter-spacing: -.04em; }
        p { color: #657087; line-height: 1.65; }
        a {
            display: inline-block;
            margin-top: 16px;
            padding: 13px 18px;
            border-radius: 10px;
            background: #253b80;
            color: #fff;
            font-weight: 700;
            text-decoration: none;
        }
        a:hover { background: #1b2e68; }
    </style>
</head>
<body>
    <main>
        <span class="badge">Staging · Test payments only</span>
        <h1>Mobifintech</h1>
        <p>This staging app uses Paytm’s sandbox checkout for a fixed ₹1.00 test order. Do not enter real payment details.</p>
        <a href="{{ route('paytm.checkout') }}">Open Paytm test checkout</a>
    </main>
</body>
</html>
