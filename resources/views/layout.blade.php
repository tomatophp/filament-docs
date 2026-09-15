<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ __('filament-panels::layout.direction') }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <style id="filament-docs-print-css">
        {!! \TomatoPHP\FilamentDocs\Facades\FilamentDocs::getPrintCss() !!}
    </style>

    @if(\TomatoPHP\FilamentDocs\Facades\FilamentDocs::getCss())
        <style id="filament-docs-custom-css">
            {!! \TomatoPHP\FilamentDocs\Facades\FilamentDocs::getCss() !!}
        </style>
    @endif
</head>

<body>
@if(\TomatoPHP\FilamentDocs\Facades\FilamentDocs::getHeader())
<div class="header fd-header">
    {!! \TomatoPHP\FilamentDocs\Facades\FilamentDocs::getHeader() !!}
</div>
@endif
<div class="content fd-document">
    {{ $slot }}
</div>
@if(\TomatoPHP\FilamentDocs\Facades\FilamentDocs::getFooter())
<div class="footer fd-footer">
    {!! \TomatoPHP\FilamentDocs\Facades\FilamentDocs::getFooter() !!}
</div>
@endif

</body>

</html>
