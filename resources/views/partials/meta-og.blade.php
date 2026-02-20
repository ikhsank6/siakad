<meta name="description" content="@yield('description', $aboutUs->description ?? '')">
<meta name="keywords" content="@yield('keywords', $settings->meta_keywords_string ?? '')">
<meta name="author" content="{{ $settings->meta_author ?? '' }}">

{{-- Open Graph Meta Tags for Social Media Sharing --}}
<meta property="og:title" content="@yield('og_title', $aboutUs->company_name ?? config('app.name'))">
<meta property="og:description" content="@yield('og_description', $aboutUs->description ?? '')">
<meta property="og:image"
    content="@yield('og_image', $aboutUs->logo ? asset('storage/' . $aboutUs->logo) : asset('images/default-og.png'))">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:type" content="@yield('og_type', 'website')">
<meta property="og:site_name" content="{{ $aboutUs->company_name ?? config('app.name') }}">
<meta property="og:locale" content="id_ID">

{{-- Twitter Card Meta Tags --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="@yield('og_title', $aboutUs->company_name ?? config('app.name'))">
<meta name="twitter:description" content="@yield('og_description', $aboutUs->description ?? '')">
<meta name="twitter:image"
    content="@yield('og_image', $aboutUs->logo ? asset('storage/' . $aboutUs->logo) : asset('images/default-og.png'))">