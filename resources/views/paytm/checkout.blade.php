<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Paytm sandbox checkout</title>
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
            width: min(100%, 480px);
            padding: 36px;
            border: 1px solid #e3e9f2;
            border-radius: 20px;
            background: #fff;
            box-shadow: 0 20px 60px rgb(26 45 80 / 9%);
        }
        h1 { margin: 0 0 10px; font-size: 30px; letter-spacing: -.035em; }
        p { color: #657087; line-height: 1.6; }
        .amount { margin: 26px 0; color: #172033; font-size: 34px; font-weight: 750; }
        .notice { padding: 13px 15px; border-radius: 10px; background: #fff4dc; color: #82520b; font-size: 14px; }
        .error { padding: 13px 15px; border-radius: 10px; background: #fff0f0; color: #9c2525; font-size: 14px; }
        button, .back {
            display: inline-block;
            border: 0;
            border-radius: 10px;
            padding: 13px 18px;
            background: #253b80;
            color: #fff;
            font: inherit;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
        }
        button:disabled { background: #9aa3b5; cursor: not-allowed; }
        .back { margin-left: 8px; background: #edf0f5; color: #25304a; }
    </style>
</head>
<body>
    <main>
        <h1>Paytm sandbox checkout</h1>
        <p>This test checkout creates a fixed-value sandbox order. It does not collect card details on this site.</p>
        <div class="amount">₹1.00</div>

        @if ($errors->has('paytm'))
            <p class="error">{{ $errors->first('paytm') }}</p>
        @endif

        @if (! $configured)
            <p class="notice">Sandbox credentials are not configured. Add the rotated test credentials to this staging app’s ignored <code>.env</code> file, then reload this page.</p>
        @else
            <p class="notice">Sandbox mode only. Use Paytm’s approved test flow; do not use real customer or payment data.</p>
        @endif

        <form method="post" action="{{ route('paytm.initiate') }}">
            @csrf
            <button type="submit" @disabled(! $configured)>Continue to Paytm test gateway</button>
            <a class="back" href="{{ route('home') }}">Back</a>
        </form>
    </main>
</body>
</html>
