@extends('layout') 

@section('title')
    Posts list
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
    <a class="navbar-brand d-flex align-items-center fw-500" href="users.html"><img alt="logo" class="d-inline-block align-top mr-2" src="img/logo.png">Страница постов</a> <button aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler" data-target="#navbarColor02" data-toggle="collapse" type="button"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="navbarColor02">
        <ul class="navbar-nav md-3">
            <li class="nav-item active">
                <a class="nav-link" href="/">Главная <span class="sr-only">(current)</span></a>
            </li>
        </ul>
        @if($navigate['postsAll']==1)
        <ul class="navbar-nav md-3">
            <li class="nav-item active">
                <a class="nav-link text-danger" href="/posts" >Все посты <span class="sr-only">(current)</span></a>
            </li>
        </ul>
        @else
        <ul class="navbar-nav md-3">
            <li class="nav-item active">
                <a class="nav-link" href="/posts">Все посты <span class="sr-only">(current)</span></a>
            </li>
        </ul>
        @endif
        @if ($navigate['myPosts']==1)
        <ul class="navbar-nav md-3">
            <li class="nav-item active">
                <a class="nav-link text-danger" href="/myPosts" >Мои посты <span class="sr-only">(current)</span></a>
            </li>
        </ul>    
        @else
        <ul class="navbar-nav md-3">
            <li class="nav-item active">
                <a class="nav-link" href="/myPosts">Мои посты <span class="sr-only">(current)</span></a>
            </li>
        </ul>
        @endif
        @if ($navigate['favorites']==1)
        <ul class="navbar-nav md-3">
            <li class="nav-item active">
                <a class="nav-link text-danger" href="/favoritesPosts" >Избранные посты <span class="sr-only">(current)</span></a>
            </li>
        </ul> 
        @else
        <ul class="navbar-nav md-3">
            <li class="nav-item active">
                <a class="nav-link" href="/favoritesPosts">Избранные посты<span class="sr-only">(current)</span></a>
            </li>
        </ul>   
        @endif
        @if ($navigate['searchPosts']==1)
        <ul class="navbar-nav md-3">
            <li class="nav-item active">
                <a class="nav-link text-danger" href="#" >Вы в режиме поиска <span class="sr-only">(current)</span></a>
            </li>
        </ul> 
        @endif
        <ul class="navbar-nav md-3">
            <li class="nav-item active">
                <a class="nav-link text-warning" href="/chats" >Перейти на страницу чатов <span class="sr-only">(current)</span></a>
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

            <div class="subheader">
                @if ($navigate['searchPosts']==1)
                <h1 class="subheader-title">
                    <i class='subheader-icon fal fa-users'></i> Найденные посты
                </h1> 
                @else
                <h1 class="subheader-title">
                    <i class='subheader-icon fal fa-users'></i> Список постов
                </h1> 
                @endif   
            </div>

            <div class="row">   
                <div class="col-lg-10 col-xl-10 m-auto">
                @if (Auth::check() || Auth::user()->admin)
                    <a class="btn btn-info" href="/addPost">Добавить пост</a>
                @endif

                <!-- форма поиска постов -->
                    <form action="/searchPosts" method="GET">
                        {{ csrf_field() }}
                        <div class="border-faded bg-faded p-3 mb-g d-flex mt-3">
                            <input type="text" id="js-filter-contacts" name="filter_contacts" class="form-control shadow-inset-2 form-control-lg" placeholder="Найти пост">
                            <div class="btn-group btn-group-lg btn-group-toggle hidden-lg-down ml-3" data-toggle="buttons">
                                <label class="btn btn-default active">
                                    <input type="radio" name="contactview" id="grid" checked="" value="{{ old('filter_contacts') }}"><i class="fas fa-table"></i>
                                </label>
                                <label class="btn btn-default">
                                    <input type="radio" name="contactview" id="table" value="table"><i class="fas fa-th-list"></i>
                                </label>
                            </div>
                            <button class="btn btn-info" type="submit" name="submit">Поиск</button>
                        </div>
                    </form>        
                </div>
            </div>       
@endsection

@section('posts')
<main id="js-page-content" role="main" class="page-content mt-3">
    @foreach ($posts as $post)
    
    <!-- не заблокированные посты -->
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-user'></i> {{ $post->name_post }}
        </h1>
    </div> 
    <div class="row">
      <div class="col-lg-11 col-xl-11 m-auto">
            <!-- profile summary -->
            <div class="card mb-g rounded-top">
                <div class="row no-gutters row-grid">   
                    <div class="col-12">   
                        <div class="d-flex flex-column align-items-center justify-content-center p-4"> 
                            
                            @if(Auth::check())
                                @if($post->banned==0 || auth()->user()->admin)
                                <a class="dropdown-item"  href="/post/{{ $post->post_id }}">
                                    <i class="fa fa-sun btn btn-info"></i>
                                Открыть пост</a>
                                @endif
                                @if ($post->banned==0 && $post->favorites==1)
                                    <a class="dropdown-item"  href="/deleteFavorites/{{ $post->post_id }}">
                                        <i class="fa fa-sun btn btn-warning"></i>Удалить из избранного</a>    
                                @endif
                                @if($post->banned==0 && $post->favorites==0)
                                    <a class="dropdown-item"  href="/addFavorites/{{ $post->post_id}}">
                                        <i class="fa fa-sun btn btn-info"></i>Добавить в избранное</a>    
                                 @endif 
                                 @if ($post->banned==1 && auth()->user()->admin)
                                    <a class="dropdown-item"  href="/unBannedPost/{{ $post->post_id }}">
                                        <i class="fa fa-sun btn btn-warning"></i>Разблокировать пост</a>   
                                @endif
                                @if($post->banned==0 && auth()->user()->admin) 
                                    <a class="dropdown-item"  href="/bannedPost/{{ $post->post_id }}">
                                        <i class="fa fa-sun btn btn-danger"></i>Заблокировать пост</a>
                                @endif 
                            @endif   
                            <br>
                            <br>
                            <br> 
                            @if($post->banned == 1)
                            <img src="{{ asset('img/demo/avatars/type2.png') }}" class="rounded-circle shadow-2 img-thumbnail" alt=""">
                            @else
                            <img src="{{ $post->avatar_post }}" class="rounded-circle shadow-2 img-thumbnail" alt=""">
                            @endif
                            <h5 class="mb-0 fw-700 text-center mt-3">
                                @if($post->banned == 1)
                                <h5 class="mb-0 fw-700 text-center mt-3 btn btn-danger">
                                    Пост заблокирован из-за нарушения правил веб сайта о публикации материалов
                                    <small class="text-muted mb-0 btn btn-danger">Пост заблокирован из-за нарушения правил веб сайта о публикации материалов</small>
                                        <hr>
                                </h5>
                                @else
                                {{ $post->title_post }}
                                <small class="text-muted mb-0">{{ $post->text }}</small>
                                    <hr>
                                </h5>
                                <h5 class="mb-0 fw-700 text-center mt-3">
                                    {{ $post->user->name }}
                                    <small class="text-muted mb-0">{{ $post->user->info->location }}</small>
                            </h5> 
                                @endif   
                        </div>
                    </div>   
                </div>
            </div>
       </div>
    </div>
    
    @endforeach
</main>
<script src="js/vendors.bundle.js"></script>
    <script src="js/app.bundle.js"></script>
    <script>

        $(document).ready(function()
        {

        });

    </script>
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
        