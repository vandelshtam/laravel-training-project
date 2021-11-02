@extends('layout') 

@section('title')
    Open Chat
@endsection

@section('meta')
    <meta name="description" content="Chartist.html">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, minimal-ui">
@endsection

@section('style')
    <link id="vendorsbundle" rel="stylesheet" media="screen, print" href="{{ asset('css/vendors.bundle.css') }}">
    <link id="appbundle" rel="stylesheet" media="screen, print" href="{{ asset('css/app.bundle.css') }}">
    <link id="myskin" rel="stylesheet" media="screen, print" href="{{ asset('ss/skins/skin-master.css') }}c">
    <link rel="stylesheet" media="screen, print" href="{{ asset('css/fa-solid.css') }}">
    <link rel="stylesheet" media="screen, print" href="{{ asset('css/fa-brands.css') }}">
    <link rel="stylesheet" media="screen, print" href="{{ asset('css/fa-regular.css') }}">   
@endsection



@section('navchat')
<nav class="navbar navbar-expand-lg navbar-dark bg-danger bg-primary-gradient fixed-top">
    <a class="navbar-brand d-flex align-items-center fw-500" href="users.html"><img alt="logo" class="d-inline-block align-top mr-2" src="{{ asset('img/message.png') }}" style="width: 35px;">Страница чатов</a> <button aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler" data-target="#navbarColor02" data-toggle="collapse" type="button"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="navbarColor02">
        <ul class="navbar-nav md-3">
            <li class="nav-item active">
                <a class="nav-link" href="/">Главная <span class="sr-only">(current)</span></a>
            </li>
        </ul>
        <ul class="navbar-nav md-3">
            <li class="nav-item active">
                <a class="nav-link " href="/chats" >Все чаты <span class="sr-only">(current)</span></a>
            </li>
        </ul>
        <ul class="navbar-nav md-3">
            <li class="nav-item active">
                <a class="nav-link " href="/editChatShow/{{ $chat->id }}" >Редактировать <span class="sr-only">(current)</span></a>
            </li>
        </ul>    
        <ul class="navbar-nav md-3">
            <li class="nav-item active">
                <a class="nav-link " href="/deleteChat/{{ $chat->id }}" onclick="return confirm('are you sure?');">Удалить<span class="sr-only">(current)</span></a>
            </li>
        </ul> 
        @if (auth()->user()->admin)
            @if ($chat->banned == 0)
            <ul class="navbar-nav md-3">
                <li class="nav-item active">
                    <a class="nav-link " href="/onBannedChat/{{ $chat->id }}" onclick="return confirm('are you sure?');">Заблокировать<span class="sr-only">(current)</span></a>
                </li>
            </ul> 
            @endif
            @if($chat->banned == 1)
            <ul class="navbar-nav md-3">
                <li class="nav-item active">
                    <a class="nav-link " href="/offBannedChat/{{ $chat->id }}" onclick="return confirm('are you sure?');">Разблокировать<span class="sr-only">(current)</span></a>
                </li>
            </ul> 
            @endif    
        @endif
        
        <!-- информации о чате в панели навигации -->
                    <div class="d-flex flex-row align-items-center ">   
                            <span class="rounded-circle profile-image d-block md-3 " style="background-image:url('{{ asset($chat->chat_avatar) }}'); background-size: cover;"></span>   
                    </div>
                    <div class="d-flex flex-row align-items-center ml-2">      
                    @if ($chat->banned==1)
                        <span class="text-truncate text-truncate-xl  text-danger">Чат заблокирован</span>
                    @else
                        <span class="text-truncate text-truncate-xl ">Активный чат</span>
                    @endif   
                </div>
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

            <!-- флеш сообщения - начало блока -->
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
            <!-- флеш сообщения - окончание блока -->

            <!-- название чата  -->
            <div class="subheader">   
                <h1 class="subheader-title">
                    <i class='subheader-icon fal fa-users'></i> {{ $chat->name_chat }} 
                </h1>
            </div>

            <!-- отправка сообщения -->
            <form class="fixed-bottom mb-0 bg-white" action="/message/{{ $chat->id }}" method="GET">
                                {{ csrf_field() }}
                                <div class="border-faded bg-faded p-3 mb-g d-flex mt-3">
                                    <input type="text"  name="message"  placeholder="Ввести текст сообщения" style="width: 1100px;">
                                    <div class="btn-group btn-group-lg btn-group-toggle hidden-lg-down ml-3" data-toggle="buttons">
                                    </div>
                                    <button class="btn btn-info" type="submit" name="submit">Отправить</button>
                                </div>
            </form>    


            <!-- вывод сообщений чата -->
            <div class="row" id="js-contacts">

                <!-- вывод сообщений   участников чата --> 
                
                @foreach ($chat->messages as $message)
                @if(auth()->user()->id != $message->user_id)
                <div class="col-lg-8 col-xl-8 ml-auto">
                    <div id="c_1" class="card border shadow-0 mb-g shadow-sm-hover rounded">
                        <div class="card-body border-faded border-top-0 border-left-0 border-right-0 rounded-top bg-warning bg-info-gradient" >
                            <div class="d-flex flex-row align-items-center ">
                                
                                <!-- статус пользователя -->
                                @foreach ($message->user->infos as $info)
                                @if ($info->status == 0)
                                    <span class="status status-success mr-3">
                                @endif
                                @if ($info->status == 1)
                                    <span class="status status-danger mr-3">
                                @endif
                                @if ($info->status == 2)
                                    <span class="status status-warning mr-3">
                                @endif
                                    <span class="rounded-circle profile-image d-block " style="background-image:url('{{ asset($info->avatar) }}'); background-size: cover;"></span>
                                </span>
                                @endforeach
                                <div class="info-card-text flex-1">
                                    <a href="javascript:void(0);" class="fs-xl text-truncate text-truncate-lg text-info" data-toggle="dropdown" aria-expanded="false">
                                        {{ $message->user->name }}
                                        <i class="fal fas fa-cog fa-fw d-inline-block ml-1 fs-md"></i>
                                        <i class="fal fa-angle-down d-inline-block ml-1 fs-md"></i>
                                    </a>

                                    <!--выпадающее подменю-->
                                    @if ( Auth::user()->admin)
                                        <div class="dropdown-menu">
                                            
                                            <a href="/delete_message/{{ $message->id }}/{{ $message->user_id }}/{{ $chat->id }}" class="dropdown-item" onclick="return confirm('are you sure?');">
                                                <i class="fa fa-window-close"></i>
                                            Удалить сообщение
                                            </a>   
                                        </div>
                                    @endif
                                    <span class="text-truncate text-truncate-xl">{{ $message->created_at }}</span>
                                    <p class="text-truncate text-truncate-xl  md-5">сообщение:</p>
                                    <span class="text-truncate text-truncate-xl  md-5" style="white-space: pre-wrap;">{{ $message -> message }}</span>
                                </div>       
                            </div>
                        </div>    
                    </div>
                </div>  
                @endif 
        
            <!-- вывод сообщений  авторизованного участника чата ( свои сообщения) -->
                @if(auth()->user()->id == $message->user_id)    
                    <div class="col-lg-8 col-xl-8 mr-auto">
                        <div id="c_1" class="card border shadow-0 mb-g shadow-sm-hover rounded">
                            <div class="card-body border-faded border-top-0 border-left-0 border-right-0 rounded-top bg-blue bg-info-gradient" >
                                <div class="d-flex flex-row align-items-center ">
                                    <!-- статус пользователя -->
                                    @foreach ($message->user->infos as $info)
                                    @if ($info->status == 0)
                                        <span class="status status-success mr-3">
                                    @endif
                                    @if ($info->status == 1)
                                        <span class="status status-danger mr-3">
                                    @endif
                                    @if ($info->status == 2)
                                        <span class="status status-warning mr-3">
                                    @endif
                                        <span class="rounded-circle profile-image d-block " style="background-image:url('{{ asset($info->avatar) }}'); background-size: cover;"></span></span>
                                    @endforeach
                                        <div class="info-card-text flex-1">
                                            <a href="javascript:void(0);" class="fs-xl text-truncate text-truncate-lg text-info" data-toggle="dropdown" aria-expanded="false">
                                                {{ $message->user->name }}
                                                <i class="fal fas fa-cog fa-fw d-inline-block ml-1 fs-md"></i>
                                                <i class="fal fa-angle-down d-inline-block ml-1 fs-md"></i>
                                            </a>

                                            <!--выпадающее подменю-->
                                            @if ( Auth::user()->admin || auth()->user()->id == $message->user_id)
                                                <div class="dropdown-menu row">
                                                    
                                                    <a href="/delete_message/{{ $message->id }}/{{ $message->user_id }}/{{ $chat->id }}" class="dropdown-item" onclick="return confirm('are you sure?');">
                                                        <i class="fa fa-window-close"></i>
                                                    Удалить сообщение
                                                    </a>   
                                                </div>   
                                            @endif
                                            <span class="text-truncate text-truncate-xl mr-auto">{{ $message->created_at }}</span>
                                            <p class="text-truncate text-truncate-xl  md-5">сообщение:</p>
                                            <span class="text-truncate text-truncate-xl md-5" style="white-space: pre-wrap;">{{ $message -> message }}</span>
                                        </div>            
                                </div>
                            </div>    
                        </div>
                    </div> 
                @endif  
    @endforeach  
    </div> 
</main>
@endsection

@section('script')
<script src="{{ asset('js/vendors.bundle.js') }}"></script>
    <script src="{{ asset('js/app.bundle.js') }}"></script>
    
@endsection
        