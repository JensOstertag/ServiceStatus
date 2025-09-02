<!DOCTYPE html>
<html lang="{{ Translator->getLocaleForHtmlLang() }}">
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
        <link rel="stylesheet" href="{{ Router->staticFilePath("css/lib/datatables.min.css") }}">
        <link rel="stylesheet" href="{{ Router->staticFilePath("css/style.css") }}">

        {{-- JavaScript --}}
        <script src="{{ Router->staticFilePath("js/lib/jquery.min.js") }}"></script>
        <script src="{{ Router->staticFilePath("js/lib/datatables.min.js") }}"></script>
        <script src="{{ Router->staticFilePath("js/lib/luxon.min.js") }}"></script>
        <script src="{{ Router->staticFilePath("js/lib/chart.umd.min.js") }}"></script>
        <script src="{{ Router->staticFilePath("js/lib/luxon.chartjs.min.js") }}"></script>
        <script src="{{ Router->staticFilePath("js/lib/popper.min.js") }}"></script>
        <script src="{{ Router->staticFilePath("js/lib/tippy.umd.min.js") }}"></script>
        @if(!Config->isProduction())
            <script src="{{ Router->staticFilePath("js/lib/LiveUpdate.js") }}"></script>
        @endif
    </head>
    <body class="bg-surface-100 text-surface-900 overflow-x-hidden">
        <script type="module">
            import { init } from "{{ Router->staticFilePath("js/Translator.js") }}";
            init("{{ Router->generate("translations-api") }}");
        </script>

        @include("components.layout.headers.console")

        <div class="pt-22 pb-6 px-6">
            <main class="md:ml-96 transition-all"
                  data-sidebar-active-classes="md:ml-96" data-sidebar-inactive-classes="md:ml-0">
                @include("components.layout.infomessagelist")

                {!! $slot !!}
            </main>
        </div>

        <script type="module">
            import { init } from "{{ Router->staticFilePath("js/DateFormatter.js") }}";
            init();
        </script>
    </body>
</html>
