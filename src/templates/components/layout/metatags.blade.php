{{-- Browser tab --}}
<title>@if(!empty($title)){{ $title }} - @endif{{ Config->getAppName() }}</title>
<link rel="icon" href="{{ Router->staticFilePath("img/logo.svg") }}" type="image/x-icon">

{{-- Basic SEO --}}
<meta name="description" content="TODO">
{{--<meta name="keywords" content="">--}}
{{--<meta name="author" content="">--}}

{{-- OpenGraph SEO --}}
<meta property="og:title" content="@if(!empty($title)){{ $title }} - @endif{{ Config->getAppName() }}">
{{--<meta property="og:description" content="">--}}
{{--<meta property="og:image" content="">--}}
<meta property="og:url" content="{{ Router->getCalledURL() }}">
{{--<meta property="og:site_name" content="">--}}
<meta property="og:type" content="website">

{{-- Twitter SEO --}}
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="@if(!empty($title)){{ $title }} - @endif{{ Config->getAppName() }}">
{{--<meta name="twitter:description" content="">--}}
{{--<meta name="twitter:image" content="">--}}
<meta name="twitter:url" content="{{ Router->getCalledURL() }}">
{{--<meta name="twitter:site" content="">--}}
{{--<meta name="twitter:creator" content="">--}}

{{-- Indexing --}}
<meta name="robots" @if($hideFromSeo) content="noindex,nofollow" @else content="index,follow" @endif>
<meta name="revisit-after" content="7 days">
