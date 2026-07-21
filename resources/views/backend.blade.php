<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Amber</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">

        <link rel="icon" type="image/png" href="/backend/favicon-96x96.png" sizes="96x96" />
        <link rel="icon" type="image/svg+xml" href="/backend/favicon.svg" />
        <link rel="shortcut icon" href="/backend/favicon.ico" />
        <link rel="apple-touch-icon" sizes="180x180" href="/backend/apple-touch-icon.png" />
        <link rel="manifest" href="/backend/site.webmanifest" />
        <script src="/libs/tinymce/tinymce.min.js"></script>
        @vite(['packages/webx/main.ts'])
    </head>
    <body>
    <div id="app"></div>
    </body>
</html>
