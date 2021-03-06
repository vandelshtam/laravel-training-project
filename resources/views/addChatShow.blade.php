@extends('layout') 

@section('title')
    Add Chat list Show
@endsection

@section('meta')
    <meta name="description" content="Chartist.html">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, minimal-ui">
@endsection

@section('style')
    <link id="vendorsbundle" rel="stylesheet" media="screen, print" href="css/vendors.bundle.css">
    <link id="appbundle" rel="stylesheet" media="screen, print" href="css/app.bundle.css">
    <link id="myskin" rel="stylesheet" media="screen, print" href="css/skins/skin-master.css">
    <link rel="stylesheet" media="screen, print" href="css/fa-solid.css">
    <link rel="stylesheet" media="screen, print" href="css/fa-brands.css">
    <link rel="stylesheet" media="screen, print" href="css/fa-regular.css"> 
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">    
@endsection


@section('navchat')
<nav class="navbar navbar-expand-lg navbar-dark bg-danger bg-primary-gradient fixed-top">
    <a class="navbar-brand d-flex align-items-center fw-500" href="users.html"><img alt="logo" class="d-inline-block align-top mr-2" src="img/message.png" style="width: 35px;">Страница чатов</a> <button aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler" data-target="#navbarColor02" data-toggle="collapse" type="button"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="navbarColor02">
        <ul class="navbar-nav md-3">
            <li class="nav-item active">
                <a class="nav-link" href="/">Главная <span class="sr-only">(current)</span></a>
            </li>
        </ul>
        @if($navigate['chatsAll']==1)
        <ul class="navbar-nav md-3">
            <li class="nav-item active">
                <a class="nav-link  text-danger" href="/chats" >Все чаты <span class="sr-only">(current)</span></a>
            </li>
        </ul>
        @else
        <ul class="navbar-nav md-3">
            <li class="nav-item active">
                <a class="nav-link" href="/chats">Все чаты <span class="sr-only">(current)</span></a>
            </li>
        </ul>
        @endif
        @if ($navigate['myChats']==1)
        <ul class="navbar-nav md-3">
            <li class="nav-item active">
                <a class="nav-link  text-danger" href="/chatsMy" >Мои чаты <span class="sr-only">(current)</span></a>
            </li>
        </ul>    
        @else
        <ul class="navbar-nav md-3">
            <li class="nav-item active">
                <a class="nav-link" href="/chatsMy">Мои чаты <span class="sr-only">(current)</span></a>
            </li>
        </ul>
        @endif
        @if ($navigate['favorites']==1)
        <ul class="navbar-nav md-3">
            <li class="nav-item active">
                <a class="nav-link text-danger" href="/chatsFavorites" >Избранные чаты <span class="sr-only">(current)</span></a>
            </li>
        </ul> 
        @else
        <ul class="navbar-nav md-3">
            <li class="nav-item active">
                <a class="nav-link" href="/chatsFavorites">Избранные чаты<span class="sr-only">(current)</span></a>
            </li>
        </ul>   
        @endif
        @if ($navigate['searchChats']==1)
        <ul class="navbar-nav md-3">
            <li class="nav-item active">
                <a class="nav-link text-danger" href="#" >Вы в режиме поиска <span class="sr-only">(current)</span></a>
            </li>
        </ul> 
        @endif
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

            <!-- флеш сообщения -->
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
            <!-- флеш сообщения -->

@endsection

@section('posts')
<main id="js-page-content" role="main" class="page-content mt-3">
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-user'></i> Добавление нового чата
        </h1>
    </div>

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

    <div class="row ">
    <form action="/addChat/{{ Auth::id() }}" method="POST" enctype="multipart/form-data" class="col-lg-12 col-xl-12 m-auto">
        {{ csrf_field() }}
      <div class="col-lg-12 col-xl-12 m-auto">
            <!-- новый чат -->
            <div class="card mb-g rounded-top">
                <div class="row no-gutters row-grid">
                    <div class="col-12">
                        <div class="d-flex flex-column align-items-center justify-content-center p-4">  
                            <!-- аватар чата -->    
                            <h2 align="center">Аватар чата</h2>
                            <div class="panel-container col-lg-12 col-xl-12 m-auto" >    
                                <div class="panel-content" >        
                                    <div class="form-group">
                                        <label class="form-label" for="example-fileinput">Выберите аватар</label>
                                        <input type="file" id="example-fileinput" class="form-control-file" name="avatar_chat">
                                    </div>   
                                </div>
                            </div>
                            <br>
                            <hr> 

                            <!-- Название чата -->
                            <h5 class="col-md-12 text-center mt-3">
                                <div class="form-group ">
                                    <label class="form-label" for="simpleinput">Введите название чата</label>
                                    <input type="text" id="simpleinput" class="form-control" name="name_chat" value="{{old('name_chat')}}">
                                </div>
   
                            <!--  выбор пользователя для добавления в чат -->
                            <h4><span class="text-truncate text-truncate-xl">Выберите и добавьте пользователя  в чат</span></h4>
                            <div class="row p-2" id="js-contacts">
                                @if(auth()->user()->admin || Auth::check())
                                @foreach ($users as $user)
                                
                                <div class="col-xl-4 m-auto">
                                    <div id="c_1" class="card border shadow-0 mb-g shadow-sm-hover" data-filter-tags="">
                                        <div class="card-body border-faded border-top-0 border-left-0 border-right-0 rounded-top">
                                            <div class="d-flex flex-row align-items-center">
                                                    <span class="rounded-circle profile-image d-block" style="background-image:url('{{ $user->info->avatar}}'); background-size: cover;"></span>
                                                </span>
                                                <div class="info-card-text flex-1 md-1">
                                                    <a href="javascript:void(0);" class="fs-xl text-truncate text-truncate-lg text-info" data-toggle="dropdown" aria-expanded="false">
                                                        {{ $user->name }}  
                                                    </a>
                
                                                    <!--подменю-->
                                                    @if (Auth::check() || Auth::user()->admin)
                                                        
                                                        <a class="dropdown-item col-md-4" href="/profile/{{ $user->id }}">
                                                            <i class="fa fa-edit md-1"></i>
                                                        Открыть  профиль</a>
                                                    @endif      
                                                      
                                                </div>
                                                <button class="js-expand-btn btn btn-sm btn-default d-none" data-toggle="collapse" data-target="#c_1 > .card-body + .card-body" aria-expanded="false">
                                                    <span class="collapsed-hidden">+</span>
                                                    <span class="collapsed-reveal">-</span>
                                                </button>
                                                
                                                    
                                                    
                                            </div>
                                                <div class="form-group text-left ml-6">
                                                        <div class="custom-control custom-checkbox ml-5">
                                                            <input type="text" class="custom-control-input" name="user_{{ $user->id }}"   value="{{ $user -> id }}" hidden>
                                                            <input type="checkbox" class="custom-control-input" name="rememberme_{{ $user->id }}" id="rememberme_{{ $user->id }}">
                                                            <label class="custom-control-label" for="rememberme_{{ $user->id }}">Добавить пользователя</label>
                                                        </div>
                                                </div>     
                                        </div>    
                                    </div>
                                </div> 
                                @endforeach 
                                @endif 
                            </div>   
                    </div> 
                      <!-- конопка добавления чата -->
                    <div class="col-md-12 mb-3  d-flex flex-row-reverse">
                    <button class="btn btn-danger" type="submit" name="submit">Создать новый чат</button>
                    </div>
                </div>        
            </div>
       </div>   
    </form>
    </div>
</main>

@endsection

@section('script')
<script src="js/vendors.bundle.js"></script>
    <script src="js/app.bundle.js"></script>
    <script>

        $(document).ready(function()
        {

        });

    </script>
@endsection
        