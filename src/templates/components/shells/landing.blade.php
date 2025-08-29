<!DOCTYPE html>
<html>
    <head>
        {{-- Encoding --}}
        <meta charset="utf-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        {{-- SEO --}}
        @include("components.layout.metatags", [
            "title" => $title ?? null,
            "hideFromSeo" => $hideFromSeo ?? false
        ])

        {{-- CSS --}}
        <link rel="stylesheet" href="{{ Router->staticFilePath("css/style.css") }}">

        {{-- JavaScript --}}
        @if(!Config->isProduction())
            <script src="{{ Router->staticFilePath("js/lib/LiveUpdate.js") }}"></script>
        @endif
    </head>
    <body class="bg-surface-100 text-surface-900 overflow-x-hidden">
        <script type="module">
            import { init } from "{{ Router->staticFilePath("js/Translator.js") }}";
            init("{{ Router->generate("translations-api") }}");
        </script>

        @include("components.layout.headers.landing")

        <div class="px-4">
            <main class="max-w-screen-xl m-auto min-h-[90vh]">
                @include("components.layout.infomessagelist")

                {!! $slot !!}
            </main>
        </div>

        @include("components.layout.footers.landing")

        <script type="module">
            import { init } from "{{ Router->staticFilePath("js/DateFormatter.js") }}";
            init();
        </script>
    </body>
</html>
