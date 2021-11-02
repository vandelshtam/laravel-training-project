@extends('layout') 

@section('title')
    Chate list 
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
                <a class="nav-link text-info" href="/chats" >Все чаты <span class="sr-only">(current)</span></a>
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
                <a class="nav-link text-info" href="/chatsMy" >Мои чаты <span class="sr-only">(current)</span></a>
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
                <a class="nav-link text-info" href="/chatsFavorites" >Избранные чаты <span class="sr-only">(current)</span></a>
            </li>
        </ul> 
        @else
        <ul class="navbar-nav md-3">
            <li class="nav-item active">
                <a class="nav-link" href="/chatsFavorites">Избранные чаты<span class="sr-only">(current)</span></a>
            </li>
        </ul>   
        @endif
        <ul class="navbar-nav md-3">
            <li class="nav-item active">
                <a class="nav-link text-warning" href="/posts" >Перейти на страницу постов <span class="sr-only">(current)</span></a>
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
<main id="js-page-content" role="main" class="page-content mt-6">

            <!-- флеш сообщения - начало блока -->
            @if ($flash_message_success)
            <div class="alert alert-success mt-6">
                {{ $flash_message_success }}   
            </div>    
            @endif
            @if ($flash_message_danger)
            <div class="alert alert-danger mt-6">
                {{ $flash_message_danger }}   
            </div>    
            @endif
            <!-- флеш сообщения - окончание блока -->

            <div class="subheader mt-6">
                <h1 class="subheader-title">
                    <i class='subheader-icon fal fa-users'></i> Список чатов
                </h1>
            </div>
            <div class="row sticky-top bg-white mt-5">
                <!--  блоки: добавить чат и поиск чатов -->
                <div class="col-xl-12">
                    @if (App\Models\Userlist::where('user_id', auth()->user()->id)==true || Auth::user()->admin)
                    <a class="btn btn-danger" href="/addChatShow">Добавить чат</a>
                @endif
                <div class="border-faded bg-faded p-3 mb-g d-flex mt-3">
                    <input type="text" id="js-filter-contacts" name="filter-contacts" class="form-control shadow-inset-2 form-control-lg" placeholder="Найти чат">
                    <div class="btn-group btn-group-lg btn-group-toggle hidden-lg-down ml-3" data-toggle="buttons">
                        <label class="btn btn-default active">
                            <input type="radio" name="contactview" id="grid" checked="" value="grid"><i class="fas fa-table"></i>
                        </label>
                        <label class="btn btn-default">
                            <input type="radio" name="contactview" id="table" value="table"><i class="fas fa-th-list"></i>
                        </label>    
                    </div>
                </div>    
                </div>
            </div>

            <!-- вывод списка всех чатов  -->
            <div class="row" id="js-contacts">
                @if(Auth::check() || auth()->user()->admin)
                @foreach ($chats as $chat)
                
                <div class="col-xl-4">
                    <div id="{{ $chat->c }}" class="card border shadow-0 mb-g shadow-sm-hover" data-filter-tags="{{ $chat->search_chat }}">
                        <div class="card-body border-faded border-top-0 border-left-0 border-right-0 rounded-top">
                            <div class="d-flex flex-row align-items-center">
                                <!-- статус чата -->
                                @if ($chat -> banned == 0)
                                    <span class="status status-success mr-3">
                                @endif
                                @if ($chat -> banned == 1)
                                    <span class="status status-danger mr-3">
                                @endif
                                    <span class="rounded-circle profile-image d-block " style="background-image:url('{{ $chat->chat_avatar }}'); background-size: cover;"></span>
                                </span>
                                <div class="info-card-text flex-1">
                                    <a href="javascript:void(0);" class="fs-xl text-truncate text-truncate-lg text-info" data-toggle="dropdown" aria-expanded="false">
                                        {{ $chat->name }}
                                        <i class="fal fas fa-cog fa-fw d-inline-block ml-1 fs-md"></i>
                                        <i class="fal fa-angle-down d-inline-block ml-1 fs-md"></i>
                                        @if ($chat -> favorites == 1)
                                        <span class="star ml-3"></span>
                                        @endif
                                    </a>

                                    <!--выпадающее подменю-->
                                    @if (auth()->user()->admin  || (Auth::check() && $chat->banned !=1))
                                    <div class="dropdown-menu bg-danger-gradient">
                                        <a class="dropdown-item" href="/openChat/{{ $chat->id }}">
                                            <i class="fa fa-edit"></i>
                                        Открыть чат</a> 
                                        @if ($chat->favorites==1)
                                        <a class="dropdown-item" href="/offFavorites/{{ $chat->id }}">
                                            <i class="fa fa-lock"></i>
                                        Удалить из  избранного</a>    
                                        @else
                                        <a class="dropdown-item" href="/onFavorites/{{ $chat->id }}">
                                            <i class="fa fa-lock"></i>
                                        Добавить в избранные</a>
                                        @endif
                                        
                                        @if (auth()->user()->admin  
                                        || (auth()->user()->id == $chat->author_user_id && ($chat->banned !=1)) 
                                        || DB::table('userlists')->where('chat_id', $chat->id)->where('user_id', auth()->user()->id)->select('*')->first()->role == 'moderator')
                                        <a href="/editChatShow/{{ $chat->id }}" class="dropdown-item" >
                                            <i class="fa fa-window-close"></i>
                                        Редактировать чат
                                        </a>
                                        @endif
                                        @if (Auth()->user()->admin)
                                            @if ($chat->banned == 1)
                                            <a class="dropdown-item" href="/offBannedChat/{{ $chat->id }}">
                                                <i class="fa fa-lock"></i>
                                            Разблокировать чат</a>
                                            @else
                                            <a class="dropdown-item" href="/onBannedChat/{{ $chat->id }}">
                                                <i class="fa fa-lock"></i>
                                            Заблокировать чат</a>      
                                            @endif   
                                        @endif 
                                        @if (auth()->user()->admin
                                          || (auth()->user()->id == $chat->author_user_id && ($chat->banned !=1))
                                          || DB::table('userlists')->where('chat_id', $chat->id)->where('user_id', auth()->user()->id)->select('*')->first()->role == 'moderator')  
                                        <a href="/deleteChat/{{ $chat->id }}" class="dropdown-item" onclick="return confirm('are you sure?');">
                                            <i class="fa fa-window-close"></i>
                                        Удалить чат</a>
                                        @endif   
                                    </div>
                                    @endif
                                    @if ( Auth::user()->admin || (auth()->user()->id == $chat->author_user_id && ($chat->banned !=1)))
                                    <div class="dropdown-menu">  
                                    </div>
                                    @endif
                                    <span class="text-truncate text-truncate-xl">{{ $chat->location }}</span>
                                    <span class="text-truncate text-truncate-xl">{{ $chat->created_at }}</span>
                                </div>        
                                <button class="js-expand-btn btn btn-sm btn-default d-none" data-toggle="collapse" data-target="#c_1 > .card-body + .card-body" aria-expanded="false">
                                    <span class="collapsed-hidden">+</span>
                                    <span class="collapsed-reveal">-</span>
                                </button>
                            </div>
                        </div>    
                    </div>
                </div> 
                @endforeach 
                @endif 
            </div>
        <div>   
    </div> 
</main>
@endsection

@section('script')
<script src="js/vendors.bundle.js"></script>
    <script src="js/app.bundle.js"></script>
    <script>

        $(document).ready(function()
        {

            $('input[type=radio][name=contactview]').change(function()
                {
                    if (this.value == 'grid')
                    {
                        $('#js-contacts .card').removeClassPrefix('mb-').addClass('mb-g');
                        $('#js-contacts .col-xl-12').removeClassPrefix('col-xl-').addClass('col-xl-4');
                        $('#js-contacts .js-expand-btn').addClass('d-none');
                        $('#js-contacts .card-body + .card-body').addClass('show');

                    }
                    else if (this.value == 'table')
                    {
                        $('#js-contacts .card').removeClassPrefix('mb-').addClass('mb-1');
                        $('#js-contacts .col-xl-4').removeClassPrefix('col-xl-').addClass('col-xl-12');
                        $('#js-contacts .js-expand-btn').removeClass('d-none');
                        $('#js-contacts .card-body + .card-body').removeClass('show');
                    }

                });

                //initialize filter
                initApp.listFilter($('#js-contacts'), $('#js-filter-contacts'));
        });

    </script>
@endsection
        