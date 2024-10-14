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

    </style>
</head>

<body class="p-4">
{{ $slot }}
</body>

</html>
