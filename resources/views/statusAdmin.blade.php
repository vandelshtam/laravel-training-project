@extends('layout')

    @section('title')
    Установить роль пользователя
    @endsection

    @section('meta')
    <meta name="description" content="Chartist.html">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, minimal-ui">
    @endsection

    @section('style')
    <link id="vendorsbundle" rel="stylesheet" media="screen, print" href="{{ asset('css/vendors.bundle.css') }}">
    <link id="appbundle" rel="stylesheet" media="screen, print" href="{{ asset('css/app.bundle.css') }}">
    <link id="myskin" rel="stylesheet" media="screen, print" href="{{ asset('css/skins/skin-master.css') }}">
    <link rel="stylesheet" media="screen, print" href="{{ asset('css/fa-solid.css') }}">
    <link rel="stylesheet" media="screen, print" href="{{ asset('css/fa-brands.css') }}">
    @endsection


    @section('nav')
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger bg-primary-gradient">
        <a class="navbar-brand d-flex align-items-center fw-500" href="users.html"><img alt="logo" class="d-inline-block align-top mr-2" src="/laravel-training-project/public/img/message.png" style="width: 35px;"> Book of friends</a> <button aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler" data-target="#navbarColor02" data-toggle="collapse" type="button"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navbarColor02">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="/">Главная <span class="sr-only">(current)</span></a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                @if(Auth::check())
                <li class="nav-item">
                    <a class="nav-link">Вы вошли как {{ Auth::user()->name }}</a>
                </li>
                @endif
            </ul>
            <ul class="navbar-nav md-3">
                @if(Auth::check() && Auth::user()->admin)
                <li class="nav-item">
                    <a class="nav-link">Вы администратор</a>
                </li>
                @endif
            </ul>
            <ul class="navbar-nav md-3">
                @if(Auth::check())
                <li class="nav-item">
                    <a class="nav-link" href="/logout">Выйти</a>
                </li>
                @else
                <li class="nav-item">
                    <a class="nav-link" href="/login">Войти</a>
                </li>
                @endif
            </ul>
        </div>
    </nav>
    @endsection

    @section('content')
    <main id="js-page-content" role="main" class="page-content mt-3">
        <div class="subheader">
            <h1 class="subheader-title">
                <i class='subheader-icon fal fa-sun'></i> Установить статус
            </h1>

        </div>
        <form action="/statusAdmin/{{ $user->id }}" method="POST">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-xl-6">
                    <div id="panel-1" class="panel">
                        <div class="panel-container">
                            <div class="panel-hdr">
                                <h2>Установка роли пользователя</h2>
                            </div>
                            <div class="panel-content">
                                <div class="row">
                                    <div class="col-md-4">
                                        <!-- status -->
                                        <div class="form-group">
                                            <label class="form-label" for="example-select">Выберите роль пользователя</label>
                                            <select class="form-control" id="example-select" name="admin_status">
                                                @foreach ($statuses_admin as $key => $status_admin)
                                                    @if ($key == $user->admin)
                                                        <option selected>{{ $statuses_admin[$key] }}</option>
                                                    @else
                                                        <option>{{ $status_admin }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-3 d-flex flex-row-reverse">
                                        <button class="btn btn-warning" type="submit" name="submit">Set Status</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </form>
    </main>
    @endsection

    @section('script')
    <script src="{{ asset('js/vendors.bundle.js') }}"></script>
    <script src="{{ asset('js/app.bundle.js') }}"></script>
    
    @endsection