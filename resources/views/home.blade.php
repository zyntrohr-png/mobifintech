<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light">
    <title>{{ config('app.name', 'Mobifintech') }}</title>
    <style>
        :root {
            color-scheme: light;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: #172033;
            background: #f3f6fb;
            font-synthesis: none;
            text-rendering: optimizeLegibility;
        }

        * { box-sizing: border-box; }

        body {
            display: grid;
            min-height: 100vh;
            margin: 0;
            padding: 32px 20px;
            place-items: center;
        }

        main {
            width: min(100%, 760px);
            overflow: hidden;
            border: 1px solid #e3e9f2;
            border-radius: 24px;
            background: #fff;
            box-shadow: 0 24px 70px rgb(26 45 80 / 10%);
        }

        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 24px 32px;
            border-bottom: 1px solid #edf0f5;
        }

        .brand { font-size: 15px; font-weight: 750; letter-spacing: .04em; }
        .badge {
            padding: 7px 11px;
            border-radius: 999px;
            background: #eaf8f0;
            color: #187447;
            font-size: 12px;
            font-weight: 700;
        }

        section { padding: 56px 48px 48px; }
        .eyebrow {
            margin: 0 0 14px;
            color: #5b65d8;
            font-size: 12px;
            font-weight: 750;
            letter-spacing: .13em;
            text-transform: uppercase;
        }

        h1 { max-width: 560px; margin: 0; font-size: clamp(36px, 7vw, 58px); line-height: 1.05; letter-spacing: -.055em; }
        .description { max-width: 520px; margin: 20px 0 0; color: #657087; font-size: 17px; line-height: 1.7; }
        .details {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
            margin-top: 40px;
        }

        .detail { padding: 17px; border: 1px solid #e8edf5; border-radius: 14px; }
        .detail-label { display: block; margin-bottom: 7px; color: #788398; font-size: 12px; }
        .detail-value { font-size: 14px; font-weight: 700; }
        footer { padding: 18px 32px; border-top: 1px solid #edf0f5; color: #788398; font-size: 12px; }

        @media (max-width: 520px) {
            header { padding: 20px; }
            section { padding: 40px 24px 32px; }
            .details { grid-template-columns: 1fr; margin-top: 30px; }
            footer { padding: 18px 24px; }
        }
    </style>
</head>
<body>
    <main>
        <header>
            <div class="brand">{{ config('app.name', 'Mobifintech') }}</div>
            <span class="badge">{{ ucfirst(app()->environment()) }} environment</span>
        </header>
        <section>
            <p class="eyebrow">Laravel starter app</p>
            <h1>Your app is up and running.</h1>
            <p class="description">
                This is a simple starting point for building and testing your application.
                Push changes to staging first, then promote tested changes to production.
            </p>
            <div class="details">
                <div class="detail">
                    <span class="detail-label">Framework</span>
                    <span class="detail-value">Laravel {{ Illuminate\Foundation\Application::VERSION }}</span>
                </div>
                <div class="detail">
                    <span class="detail-label">Environment</span>
                    <span class="detail-value">{{ ucfirst(app()->environment()) }}</span>
                </div>
            </div>
        </section>
        <footer>Mobifintech · Application ready</footer>
    </main>
</body>
</html>
