<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connecting to Paytm sandbox</title>
</head>
<body>
    <p>Connecting to Paytm sandbox. Please do not refresh this page.</p>
    <form method="post" action="{{ $transactionUrl }}" name="paytm-checkout">
        @foreach ($parameters as $name => $value)
            <input type="hidden" name="{{ $name }}" value="{{ $value }}">
        @endforeach
        <input type="hidden" name="CHECKSUMHASH" value="{{ $checksum }}">
        <button type="submit">Continue to Paytm</button>
    </form>
    <script>
        document.forms['paytm-checkout'].submit();
    </script>
</body>
</html>
