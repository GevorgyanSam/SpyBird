@props(['title'])

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google" content="notranslate">
    <meta name="author" content="Samvel Gevorgyan, {{ env('MAIL_FROM_ADDRESS') }}">
    <meta name="description" content="Chat Application">
    <meta name="subject" content="{{ env('APP_NAME') }}">
    <meta name="keywords" content="Chat">
    <link rel="shortcut icon" href="{{ asset('assets/icon.png') }}">
    {{ $styles ?? null }}
    <title>{{ env('APP_NAME') }} | {{ $title }}</title>
</head>
<body>
    {{ $body ?? null }}
    {{ $scripts ?? null }}
</body>
</html>