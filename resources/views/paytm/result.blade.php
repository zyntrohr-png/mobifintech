<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Test payment result</title>
    <style>
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
        main { width: min(100%, 480px); padding: 36px; border: 1px solid #e3e9f2; border-radius: 20px; background: #fff; }
        .status { font-size: 22px; font-weight: 750; }
        .success { color: #187447; }
        .failed { color: #9c2525; }
        .pending { color: #82520b; }
        p { color: #657087; line-height: 1.6; }
        a { color: #253b80; font-weight: 700; }
    </style>
</head>
<body>
    <main>
        <p class="status {{ $payment->status }}">Test payment {{ ucfirst($payment->status) }}</p>
        <p>Order: {{ $payment->order_id }}</p>
        <p>Amount: ₹{{ $payment->amount }}</p>
        <p>This is a sandbox result only. Do not treat it as a production payment confirmation.</p>
        <a href="{{ route('paytm.checkout') }}">Back to test checkout</a>
    </main>
</body>
</html>
