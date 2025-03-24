<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <style type="text/css" media="print">
        @page {
            size: A4;
            margin: 0;
        }
        table {
            width: 100%;
        }

        /* General styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: transparent;
        }

        .header, .footer {
            display: none; /* Hide header and footer by default */
            background: transparent;
        }

        .content {
            padding: 20px;
        }

        .break {
            page-break-before: always
        }

        /* Print-specific styles */
        @media print {
            .header, .footer {
                display: block;
                position: fixed;
                left: 0;
                right: 0;
                background-color: #ffffff;
                text-align: center;
                padding: 10px;
            }

            .header {
                top: 0;
            }

            .footer {
                bottom: 0;
            }

            .content {
                margin-top: 0; /* Adjust based on header height */
                margin-bottom: 0; /* Adjust based on footer height */
            }

            @page {
                margin: 0; /* Adjust based on header and footer height */
            }

            @if(\TomatoPHP\FilamentDocs\Facades\FilamentDocs::getCss())
              {!! \TomatoPHP\FilamentDocs\Facades\FilamentDocs::getCss() !!}
            @endif
        }
    </style>
</head>

<body>
@if(\TomatoPHP\FilamentDocs\Facades\FilamentDocs::getHeader())
<div class="header">
    {!! \TomatoPHP\FilamentDocs\Facades\FilamentDocs::getHeader() !!}
</div>
<br/>
@endif
<div class="content p-4">
    {{ $slot }}
</div>
@if(\TomatoPHP\FilamentDocs\Facades\FilamentDocs::getFooter())
<br/>
<div class="footer">
    {!! \TomatoPHP\FilamentDocs\Facades\FilamentDocs::getFooter() !!}
</div>
@endif

</body>

</html>
