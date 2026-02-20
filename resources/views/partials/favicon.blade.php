{{-- Dynamic Favicon from System Settings --}}
@if($settings?->favicon)
    <link rel="icon" type="image/x-icon" href="{{ Storage::url($settings->favicon) }}">
@endif