<!doctype html>
<html lang="{{ App::getLocale() }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        {!! seo()->render() !!}

        <link rel="icon" href="/assets/ac-favicon.svg" type="image/svg+xml">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Archivo:wght@400;500;600&family=EB+Garamond:ital,wght@0,400;0,500;1,400&family=Inter:wght@400;500;600&family=Marcellus&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="/assets/app.css?v=98">
        <script type="module" src="/assets/app.js?v=98"></script>

        <meta name="token" content="{{ csrf_token() }}">

        {{ $head ?? null }}

        <style>
            .ac-section.ac-section--cream + .ac-section.ac-section--cream,
            .ac-section.ac-section--paper + .ac-section.ac-section--paper {
                padding-top: 1px;
            }
        </style>

        @stack('layout-head')
    </head>
    <body>
        @isset($header)
            {{ $header }}
        @else
            <x-amber::header />
        @endisset

        <main {{ $attributes->merge(['class' => 'main mb-auto']) }}>
            {{ $slot }}
        </main>


        @isset($footer)
            {{ $footer }}
        @else
            <x-amber::footer />
        @endisset
    </body>
</html>
