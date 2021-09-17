@extends('layout')
    
    @section('title')
        Войти
    @endsection

    @section('meta')
    <meta name="description" content="Login">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, minimal-ui">
    <!-- Call App Mode on ios devices -->
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <!-- Remove Tap Highlight on Windows Phone IE -->
    <meta name="msapplication-tap-highlight" content="no">
    <!-- base css -->
    @endsection

    @section('style')
    <link id="vendorsbundle" rel="stylesheet" media="screen, print" href="css/vendors.bundle.css">
    <link id="appbundle" rel="stylesheet" media="screen, print" href="css/app.bundle.css">
    <link id="mytheme" rel="stylesheet" media="screen, print" href="#">
    <link id="myskin" rel="stylesheet" media="screen, print" href="css/skins/skin-master.css">
    <!-- Place favicon.ico in the root directory -->
    <link rel="apple-touch-icon" sizes="180x180" href="/laravel-training-project/resources/img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="img/favicon/favicon-32x32.png">
    <link rel="mask-icon" href="img/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="stylesheet" media="screen, print" href="css/page-login-alt.css">
    @endsection

    @section('content')
    <div class="blankpage-form-field">
        <div class="page-logo m-0 w-100 align-items-center justify-content-center rounded border-bottom-left-radius-0 border-bottom-right-radius-0 px-4">
            <a href="javascript:void(0)" class="page-logo-link press-scale-down d-flex align-items-center">
                <img src="img/logo.png" alt="SmartAdmin WebApp" aria-roledescription="logo">
                <span class="page-logo-text mr-1">Учебный проект</span>
                <i class="fal fa-angle-down d-inline-block ml-1 fs-lg color-primary-300"></i>
            </a>
        </div>
        <div class="card p-4 border-top-left-radius-0 border-top-right-radius-0">
            <!--флеш сообщения-->
            @if($flash_message_success)
            <div class="alert alert-success">
            {{ $flash_message_success }}       
            </div>
            @endif
            <!--флеш сообщения-->

            <!-- сообщения об ошибках-->
            @if ($errors->any())
            <div class="alert alert-danger text-dark" role="alert">
                <strong>Уведомление!</strong> 
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
            </div>
            @endif
            <!-- сообщения об ошибках-->

            <form action="" method="POST">
                {{ csrf_field() }}
                <div class="form-group">
                    <label class="form-label" for="username">Email</label>
                    <input type="email" id="username" class="form-control" placeholder="Эл. адрес" name="email" value="">
                </div>
                <div class="form-group">
                    <label class="form-label" for="password">Пароль</label>
                    <input type="password" id="password" class="form-control" name="password" placeholder="" >
                </div>
                <div class="form-group text-left">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="rememberme" id="rememberme">
                        <label class="custom-control-label" for="rememberme">Запомнить меня</label>
                    </div>
                </div>
                <button type="submit" name="submit" class="btn btn-default float-right">Войти</button>
            </form>
        </div>
        <div class="blankpage-footer text-center">
            Нет аккаунта? <a href="/register"><strong>Зарегистрироваться</strong>
        </div>
    </div>
    @endsection
    
@section('script')
<video poster="img/backgrounds/clouds.png" id="bgvid" playsinline autoplay muted loop>
    <source src="media/video/cc.webm" type="video/webm">
    <source src="media/video/cc.mp4" type="video/mp4">
</video>
<script src="js/vendors.bundle.js"></script>
@endsection
