@extends('layout') 

@section('title')
    Image Post Show list
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
    <link rel="stylesheet" media="screen, print" href="{{ asset('css/fa-regular.css') }}"> 
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">  
    
@endsection

@section('navchat')
<nav class="navbar navbar-expand-lg navbar-dark bg-danger bg-primary-gradient fixed-top">
    <a class="navbar-brand d-flex align-items-center fw-500" href="users.html"><img alt="logo" class="d-inline-block align-top mr-2" src="/laravel-training-project/public/img/message.png" style="width: 35px;"> Book of friends</a> <button aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler" data-target="#navbarColor02" data-toggle="collapse" type="button"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="navbarColor02">
        <ul class="navbar-nav md-3">
            <li class="nav-item active">
                <a class="nav-link" href="/">Главная <span class="sr-only">(current)</span></a>
            </li>
        </ul>
        <ul class="navbar-nav md-3">
            <li class="nav-item active">
                <a class="nav-link" href="/posts">Все посты <span class="sr-only">(current)</span></a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            @if(Auth::check() && Auth::user()->admin)
            <li class="nav-item">
                <a class="nav-link">Вы администратор</a>
            </li>
            @endif
        </ul>
        <ul class="navbar-nav md-3">
            @if(Auth::check())
            <li class="nav-item">
                <a class="nav-link">Вы вошли как {{ Auth::user()->name }}</a>
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

            <!-- флеш сообщения,  начало блока-->
            @if ($flash_message_success)
            <div class="alert alert-success">
                {{ $flash_message_success }}   
            </div>    
            @endif
            @if ($flash_message_danger)
            <div class="alert alert-danger">
                {{ $flash_message_danger }}   
            </div>    
            @endif
            <!-- флеш сообщения, окончание блока  -->  
@endsection

@section('posts')
<main id="js-page-content" role="main" class="page-content mt-3">
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-user'></i> Фотография из {{ $image->imageable->name_post }}
        </h1>
    </div> 
    <div class="row">
      <div class="col-lg-10 col-xl-10 m-auto">
            <!-- profile summary -->
            <div class="card mb-g rounded-top">
                <div class="row no-gutters row-grid">
                    <div class="col-12">
                        <div class="d-flex flex-column align-items-center justify-content-center p-4">
                            <!-- menu edit -->
                                <a class="dropdown-item" href="/post/{{ $image->imageable->id }}">
                                    <i class="fa fa-edit btn btn-info"></i>
                                Закрыть фотографию</a>
                                @if ( auth()->user()->admin || auth()->user()->id == $image->imageable->user_id)
                                <!-- повторное подтверждение пароля для безопасности -->
                                <a class="dropdown-item" onclick="return confirm('are your sure?')" href="/delete_image/{{ $image->id }}/{{ $image->imageable->id }}" >
                                    <i class="fa fa-window-close btn btn-info"></i>
                                Удалить фотографию</a>    
                                @endif
                                
                            <div class="container">
                                <h2 align="center">Галерея</h2>
                                
                                <div class="row">    
                                    <div class="coll-ml-auto ">        
                                        <img  src="{{ asset($image->image) }}" alt="" class="img-fluid img-thumbnail gallery-image" >
                                    </div>    
                                </div>    
                            </div>          
                    </div>    
                </div>
            </div>
       </div>   
    </div>
    <br>
    <br>     
</main>

@section('script')

<script src="{{ asset('js/vendors.bundle.js') }}"></script>
      <script src="{{ asset('js/app.bundle.js') }}"></script>
@endsection
        