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
    <link id="vendorsbundle" rel="stylesheet" media="screen, print" href="css/vendors.bundle.css">
    <link id="appbundle" rel="stylesheet" media="screen, print" href="css/app.bundle.css">
    <link id="myskin" rel="stylesheet" media="screen, print" href="css/skins/skin-master.css">
    <link rel="stylesheet" media="screen, print" href="css/fa-solid.css">
    <link rel="stylesheet" media="screen, print" href="css/fa-brands.css">
    <link rel="stylesheet" media="screen, print" href="css/fa-regular.css">   
@endsection



@section('navchat')
<nav class="navbar navbar-expand-lg navbar-dark bg-info bg-info-gradient">
    <a class="navbar-brand d-flex align-items-center fw-500" href="users.html"><img alt="logo" class="d-inline-block align-top mr-2" src="img/logo.png">Страница чатов</a> <button aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler" data-target="#navbarColor02" data-toggle="collapse" type="button"><span class="navbar-toggler-icon"></span></button>
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
                <a class="nav-link " href="/editChatShow/{{ $chat->id }}" >Редактировать чат <span class="sr-only">(current)</span></a>
            </li>
        </ul>    
        <ul class="navbar-nav md-3">
            <li class="nav-item active">
                <a class="nav-link " href="/deleteChat/{{ $chat->id }}" onclick="return confirm('are you sure?');">Удалить чат <span class="sr-only">(current)</span></a>
            </li>
        </ul> 
        @if (auth()->user()->admin)
            @if ($chat->banned == 1)
            <ul class="navbar-nav md-3">
                <li class="nav-item active">
                    <a class="nav-link " href="/offBannedChat/{{ $chat->id }}" onclick="return confirm('are you sure?');">Разблокировать чат <span class="sr-only">(current)</span></a>
                </li>
            </ul> 
            @else
            <ul class="navbar-nav md-3">
                <li class="nav-item active">
                    <a class="nav-link " href="/onBannedChat/{{ $chat->id }}" onclick="return confirm('are you sure?');">Разблокировать чат <span class="sr-only">(current)</span></a>
                </li>
            </ul> 
            @endif    
        @endif
        
        <!-- информации о чате в панели навигации -->
                    <div class="d-flex flex-row align-items-center ">   
                            <span class="rounded-circle profile-image d-block " style="background-image:url('{{ $chat->chat_avatar }}'); background-size: cover;"></span>
                        </span>
                        <div class="info-card-text flex-1">
                            <a href="javascript:void(0);" class="fs-xl text-truncate text-truncate-lg text-white" data-toggle="dropdown" aria-expanded="false">
                                {{ $chat->name_chat }}    
                            </a>    
                        </div>  
                        @if ($chat->banned==1)
                            <span class="text-truncate text-truncate-xl md-1 text-danger">Чат заблокирован</span>
                        @else
                            <span class="text-truncate text-truncate-xl md-1">Активный чат</span>
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
            <form action="/message/{{ $chat->id }}" method="GET">
                                {{ csrf_field() }}
                                <div class="border-faded bg-faded p-3 mb-g d-flex mt-3">
                                    <input type="text"  name="message"  placeholder="Ввести текст сообщения">
                                    <div class="btn-group btn-group-lg btn-group-toggle hidden-lg-down ml-3" data-toggle="buttons">
                                    </div>
                                    <button class="btn" type="submit" name="submit"><span class="rounded-circle profile-image d-block " style="background-image:url('/img/demo/avatars/type2.png'); background-size: cover;"></span></button>
                                </div>
            </form>    


            <!-- вывод сообщений чата -->
            <div class="row" id="js-contacts">

                <!-- вывод сообщений всех  участников чата, не авторизованного пользователя--> 
                
                @foreach ($messages as $message)
                @if(auth()->user()->id != $message->user_id)
                <div class="col-xl-4  ">
                    <div id="c_1" class="card border shadow-0 mb-g shadow-sm-hover " data-filter-tags="" style="width: 80%; margin-left: 245px;">
                        <div class="card-body border-faded border-top-0 border-left-0 border-right-0 rounded-top bg-warning bg-info-gradient" >
                            <div class="d-flex flex-row align-items-center ">
                                <!-- статус пользователя -->
                                @if ($message->info->status == 0)
                                    <span class="status status-success mr-3">
                                @endif
                                @if ($message->info->status == 1)
                                    <span class="status status-danger mr-3">
                                @endif
                                @if ($message->info->status == 2)
                                    <span class="status status-warning mr-3">
                                @endif
                                    <span class="rounded-circle profile-image d-block " style="background-image:url('{{ $message->info->avatar }}'); background-size: cover;"></span>
                                </span>
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
                                    <p class="text-truncate text-truncate-xl  md-5">сообщение:</p>
                                    <span class="text-truncate text-truncate-xl  md-5" style="width: 80%; height:50px; white-space: pre-wrap;">{{ $message -> message }}</span>
                                </div>
                                    <span class="text-truncate text-truncate-xl">{{ $message->created_at }}</span>
                            </div>
                        </div>    
                    </div>
                </div> 
                
                @endif 
            </div>


            <!-- вывод сообщений авторизованного пользователя ( свои сообщения) -->
            @if(auth()->user()->id == $message->user_id)
                
                <div class="col-xl-4  ">
                    <div id="c_1" class="card border shadow-0 mb-g shadow-sm-hover " data-filter-tags="" style="width: 80%;">
                        <div class="card-body border-faded border-top-0 border-left-0 border-right-0 rounded-top bg-blue bg-info-gradient" >
                            <div class="d-flex flex-row align-items-center ">
                                <!-- статус пользователя -->
                                @if ($message->info->status == 0)
                                    <span class="status status-success mr-3">
                                @endif
                                @if ($message->info->status == 1)
                                    <span class="status status-danger mr-3">
                                @endif
                                @if ($message->info->status == 2)
                                    <span class="status status-warning mr-3">
                                @endif
                                    <span class="rounded-circle profile-image d-block " style="background-image:url('{{ $message->info->avatar }}'); background-size: cover;"></span>
                                </span>
                                <div class="info-card-text flex-1">
                                    <a href="javascript:void(0);" class="fs-xl text-truncate text-truncate-lg text-info" data-toggle="dropdown" aria-expanded="false">
                                        {{ $message->user->name }}
                                        <i class="fal fas fa-cog fa-fw d-inline-block ml-1 fs-md"></i>
                                        <i class="fal fa-angle-down d-inline-block ml-1 fs-md"></i>
                                    </a>

                                    <!--выпадающее подменю-->
                                    @if ( Auth::user()->admin || auth()->user()->id == $message->user_id)
                                    <div class="dropdown-menu">
                                        
                                        <a href="/delete_message/{{ $message->id }}/{{ $message->user_id }}/{{ $chat->id }}" class="dropdown-item" onclick="return confirm('are you sure?');">
                                            <i class="fa fa-window-close"></i>
                                        Удалить сообщение
                                        </a>   
                                    </div>
                                    @endif
                                    <p class="text-truncate text-truncate-xl  md-5">сообщение:</p>
                                    <span class="text-truncate text-truncate-xl  md-5" style="width: 80%; height:50px; white-space: pre-wrap;">{{ $message -> message }}</span>
                                </div>
                                    <span class="text-truncate text-truncate-xl md-1">{{ $message->created_at }}</span>
                            </div>
                        </div>    
                    </div>
                </div> 
                @endif 
        <div> 
    @endforeach  
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
        