@extends('layout') 

@section('title')
    Edit Chat list
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
@endsection



@section('navchat')
<nav class="navbar navbar-expand-lg navbar-dark bg-info bg-info-gradient">
    <a class="navbar-brand d-flex align-items-center fw-500" href="users.html"><img alt="logo" class="d-inline-block align-top mr-2" src="{{ asset('img/logo.png') }}">Страница чатов</a> <button aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler" data-target="#navbarColor02" data-toggle="collapse" type="button"><span class="navbar-toggler-icon"></span></button>
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
                <a class="nav-link" href="#">{{ $chat->name }}<span class="sr-only">(current)</span></a>
            </li>
        </ul>
        <ul class="navbar-nav md-3">
            <li class="nav-item active">
                <a class="nav-link" href="/deleteChat" onclick="return confirm('are you sure?');">Удалить этот чат<span class="sr-only">(current)</span></a>
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
            <i class='subheader-icon fal fa-user'></i> Редактирование чата
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
    <form action="/editChat/{{ $chat->id }}" method="POST" enctype="multipart/form-data" class="col-lg-12 col-xl-12 m-auto">
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
                                    <div class="panel-hdr">
                                        <h2>Текущий аватар</h2>
                                    </div>
                                    <div class="panel-content">
                                        <div class="form-group">
                                            <img src="{{ asset($chat->chat_avatar) }}" alt="" class="img-responsive" width="200">
                                        </div>      
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
                                    <input type="text" id="simpleinput" class="form-control" name="name_chat" value="{{$chat->name_chat}}">
                                </div>
   
                            <!--  участники чата  -->
                            <h4><span class="text-truncate text-truncate-xl">Участники чата</span></h4>
                            <div class="row" id="js-contacts">
                                
                                @foreach ($chat->userlists as $userChat)
                                @if(auth()->user()->admin || $chat->author_user_id == auth()->user()->id)
                                <div class="col-xl-4">
                                    <div id="c_1" class="card border shadow-0 mb-g shadow-sm-hover" data-filter-tags="">
                                        <div class="card-body border-faded border-top-0 border-left-0 border-right-0 rounded-top">
                                            <div class="d-flex flex-row align-items-center">
                                                    <span class="rounded-circle profile-image d-block" style="background-image:url({{ asset($userChat->user->info->avatar) }}); background-size: cover;"></span>
                                                </span>
                                                <div class="info-card-text flex-1 md-1">
                                                    <a href="javascript:void(0);" class="fs-xl text-truncate text-truncate-lg text-info" data-toggle="dropdown" aria-expanded="false">
                                                        {{ $userChat->user->name }}  
                                                    </a>
                
                                                    <!--подменю-->
                                                    @if (Auth::check())
                                                        
                                                        <a class="dropdown-item md-1" href="/profile/{{ $userChat->user->id }}">
                                                            <i class="fa fa-edit md-1"></i>
                                                        Открыть  профиль</a>
                                                        
                                                            <p class="dropdown-item md-1" href="/roleParticipantShow/{{ $userChat->user->id }}/{{ $chat->id }}">
                                                                <i class="fa fa-sun md-1"></i>
                                                                
                                                            Текущая роль - {{ $userChat->role }}</p>
                                                        @if (auth()->user()->admin || $chat->author_user_id == auth()->user()->id)
                                                                @if($userChat->role == 'participant')
                                                                <a class="dropdown-item md-1" href="/roleModerator/{{ $userChat->user->id }}/{{ $chat->id }}">
                                                                    <i class="fa fa-lock md-1"></i>
                                                                Предостиавить роль модератора</a>
                                                            @elseif ($userChat->role == 'moderator')
                                                                <a class="dropdown-item md-1" href="/roleParticipant/{{ $userChat->user->id }}/{{ $chat->id }}">
                                                                    <i class="fa fa-lock md-1"></i>
                                                                Предостиавить роль пользователя</a>
                                                            @endif 
                                                        @endif
                                                        <a class="dropdown-item md-1" href="/deleteUsersIsChat/{{ $userChat->user->id }}/{{ $chat->id }}" onclick="return confirm('are you sure?');">
                                                            <i class="fa fa-window md-1"></i>
                                                        Удалить пользователя из чата</a>
                                                    @endif           
                                                </div>
                                                <button class="js-expand-btn btn btn-sm btn-default d-none" data-toggle="collapse" data-target="#c_1 > .card-body + .card-body" aria-expanded="false">
                                                    <span class="collapsed-hidden">+</span>
                                                    <span class="collapsed-reveal">-</span>
                                                </button>    
                                            </div>
                                        </div>    
                                    </div>
                                </div> 
                                @endif
                                @endforeach    
                            </div> 

                            <!--  выбор пользователя для добавления в чат -->
                            <h4><span class="text-truncate text-truncate-xl">Выберите и добавьте пользователя  в чат</span></h4>
                            <div class="row" id="js-contacts">
                                
                                @foreach ($usersNotChat as $user)
                                @if(auth()->user()->admin || auth()->user()->id == $chat->user_id)
                                <div class="col-xl-4">
                                    <div id="c_1" class="card border shadow-0 mb-g shadow-sm-hover" data-filter-tags="">
                                        <div class="card-body border-faded border-top-0 border-left-0 border-right-0 rounded-top">
                                            <div class="d-flex flex-row align-items-center">
                                                    <span class="rounded-circle profile-image d-block" style="background-image:url('{{ asset($user->info->avatar)}}'); background-size: cover;"></span>
                                                </span>
                                                <div class="info-card-text flex-1 md-1">
                                                    <a href="javascript:void(0);" class="fs-xl text-truncate text-truncate-lg text-info" data-toggle="dropdown" aria-expanded="false">
                                                        {{ $user->name }}  
                                                    </a>
                
                                                    <!--подменю-->
                                                    @if (Auth::check() && Auth::user()->admin)
                                                        
                                                        <a class="dropdown-item md-1" href="/profile/{{ $user->id }}">
                                                            <i class="fa fa-edit md-1"></i>
                                                        Открыть  профиль</a>
                                                    @endif      
                                                      
                                                </div>
                                                <button class="js-expand-btn btn btn-sm btn-default d-none" data-toggle="collapse" data-target="#c_1 > .card-body + .card-body" aria-expanded="false">
                                                    <span class="collapsed-hidden">+</span>
                                                    <span class="collapsed-reveal">-</span>
                                                </button>
                                                <span>
                                                       <div class="form-group text-left">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="text" class="custom-control-input" name="user_{{ $user->id }}"   value="{{ $user -> id }}" hidden>
                                                            <input type="checkbox" class="custom-control-input" name="rememberme_{{ $user->id }}" id="rememberme_{{ $user->id }}">
                                                            <label class="custom-control-label" for="rememberme_{{ $user->id }}">Добавить пользователя</label>
                                                        </div>
                                                    </div>     
                                                    </span>
                                            </div>
                                        </div>    
                                    </div>
                                </div> 
                                @endif 
                                @endforeach     
                            </div>     
                    </div>   
                </div>
            </div>
       </div>

       <!-- конопка добавления поста -->
       <div class="col-md-12 mt-3 d-flex flex-row-reverse">
        <button class="btn btn-info" type="submit" name="submit">Сохранить изменения в чате</button>
    </div>
    </form>
    </div>
</main>
<script src="{{ asset('js/vendors.bundle.js') }}"></script>
    <script src="{{ asset('js/app.bundle.js') }}"></script>
    <script>

        $(document).ready(function()
        {

        });

    </script>
@endsection

@section('script')
<script src="{{ asset('js/vendors.bundle.js') }}"></script>
    <script src="{{ asset('js/app.bundle.js') }}"></script>
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
        