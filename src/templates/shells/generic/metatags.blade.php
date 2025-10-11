{{-- Browser tab --}}
<title>@if(!empty($title)){{ $title }} - @endif{{ Config->getAppName() }}</title>
<link rel="icon" href="{{ Router->staticFilePath("img/favicon/favicon.ico") }}" sizes="any">
<link rel="icon" href="{{ Router->staticFilePath("img/favicon/icon.svg") }}" type="image/svg+xml">
<link rel="apple-touch-icon" href="{{ Router->staticFilePath("img/favicon/apple-touch-icon.png") }}">
<link rel="manifest" href="{{ Router->staticFilePath("img/favicon/manifest.webmanifest") }}">

{{-- Basic SEO --}}
<meta name="description" content="{{ t("Real-time web service monitoring — Health status, alerts, and performance insights") }}">
{{--<meta name="keywords" content="">--}}
{{--<meta name="author" content="">--}}

{{-- OpenGraph SEO --}}
<meta property="og:title" content="@if(!empty($title)){{ $title }} - @endif{{ Config->getAppName() }}">
<meta property="og:description" content="{{ t("Real-time web service monitoring — Health status, alerts, and performance insights") }}">
<meta property="og:image" content="{{ Config->getappUrl() . Router->staticFilePath("img/opengraph/preview.png") }}">
<meta property="og:url" content="{{ Router->getCalledURL() }}">
{{--<meta property="og:site_name" content="">--}}
<meta property="og:type" content="website">

{{-- Twitter SEO --}}
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="@if(!empty($title)){{ $title }} - @endif{{ Config->getAppName() }}">
<meta name="twitter:description" content="{{ t("Real-time web service monitoring — Health status, alerts, and performance insights") }}">
<meta name="twitter:image" content="{{ Config->getappUrl() . Router->staticFilePath("img/opengraph/preview.png") }}">
<meta name="twitter:url" content="{{ Router->getCalledURL() }}">
{{--<meta name="twitter:site" content="">--}}
{{--<meta name="twitter:creator" content="">--}}

{{-- Indexing --}}
<meta name="robots" @if(isset($hideFromSeo) && !$hideFromSeo) content="index,follow" @else content="noindex,nofollow" @endif>
<meta name="revisit-after" content="7 days">
