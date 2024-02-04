<!DOCTYPE HTML>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>タスク管理アプリ</title>
        <!-- cssをインポート -->
        @vite(['resources/css/app.css'])
        <!-- デフォルトのスタイルシート -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <!-- ブルーテーマの追加スタイルシート -->
        <link rel="stylesheet" href="https://npmcdn.com/flatpickr/dist/themes/material_blue.css">
        <!-- flatpickrスクリプト -->
        <script src="https://npmcdn.com/flatpickr/dist/flatpickr.min.js"></script>
        <!-- 日本語化のための追加スクリプト -->
        <script src="https://npmcdn.com/flatpickr/dist/l10n/ja.js"></script>
        <link href="/css/app.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">  
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>    
    </head>
    <body>
        <!-- parts/header.blade.phpをインポートしている-->
        @include('parts.header')
        @yield('content')
        @vite(['resources/js/app.js','resources/js/task.js'])
        @section('contents')
    </body>
</html>
