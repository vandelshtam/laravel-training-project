@extends('layout') 

@section('title')
    Post list
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous"> 
    
@endsection

@section('navchat')
<nav class="navbar navbar-expand-lg navbar-dark bg-info bg-info-gradient">
    <a class="navbar-brand d-flex align-items-center fw-500" href="users.html"><img alt="logo" class="d-inline-block align-top mr-2" src="">Страница постов</a> <button aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler" data-target="#navbarColor02" data-toggle="collapse" type="button"><span class="navbar-toggler-icon"></span></button>
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

            <!-- добавить пост -->
            <div class="row">   
                <div class="m-auto" style="width: 77%">
                    @if (Auth::check() && Auth::user()->admin)
                    <a class="btn btn-info" href="/addPost">Добавить пост</a>
                @endif
                </div>
            </div>       
@endsection

@section('posts')
<main id="js-page-content" role="main" class="page-content mt-3">
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-user'></i> {{ $post->name_post }}
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
                                <a class="dropdown-item" href="/editPost/{{ $post->id }}">
                                    <i class="fa fa-edit btn btn-info"></i>
                                Редактировать пост</a>
                                @if ($post->favorites==1)
                                    <a class="dropdown-item"  href="/deleteFavorites/{{ $post->id }}">
                                        <i class="fa fa-sun btn btn-warning"></i>Удалить из избранного</a>   
                                @else
                                    <a class="dropdown-item"  href="/addFavorites/{{ $post->id }}">
                                        <i class="fa fa-sun btn btn-info"></i>Добавить в избранное</a>
                                 @endif 
                                @if ( auth()->user()->admin || auth()->user()->id == $user->id)
                                <!-- повторное подтверждение пароля для безопасности -->
                                <a class="dropdown-item" onclick="return confirm('are your sure?')" href="/confirm-password/{{ $post->id }}/{{ 'deletePost' }}"">
                                    <i class="fa fa-window-close btn btn-info"></i>
                                Удалить пост</a>    
                                @endif
                                @if (Auth::user()->admin && $post->banned==1)
                                    <a class="dropdown-item"  href="/unBannedPost/{{ $post->id }}">
                                        <i class="fa fa-sun btn btn-warning"></i>Разблокировать пост</a>   
                                @elseif (Auth::user()->admin)
                                    <a class="dropdown-item"  href="/bannedPost/{{ $post->id }}">
                                        <i class="fa fa-sun btn btn-danger"></i>Заблокировать пост</a>
                                @endif     
                            <!-- show post -->    
                            @if (Auth::user()->admin && $post->banned==1)
                            <img src="{{ $post->avatar_post }}" class="rounded-circle shadow-2 img-thumbnail bt btn-warning" alt="" style="width: 80%">
                            @elseif ($post->banned==1) 
                            <img src="img/demo/avatars/avatar-admin-lg.png" class="rounded-circle shadow-2 img-thumbnail" alt="" style="width: 80%">  
                            @else
                            <img src="{{ $post->avatar_post }}" class="rounded-circle shadow-2 img-thumbnail" alt="" style="width: 80%">
                            @endif
                            <br>
                            <hr> 
                            <div class="container">
                                <h2 align="center">Галерея</h2>
                                @if(Auth::user()->admin && $post->banned==1)

                                @foreach ($post->images as $image)
                                <div class="row">    
                                    <div class="coll-md-12">        
                                        <img  src="{{ $image->image }}" alt="" class="img-fluid img-thumbnail gallery-image">
                                    </div>    
                                </div>
                                @endforeach
                            </div>   
                            <h5 class="mb-0 fw-700 text-center mt-3">
                                <small class="text-muted mb-0 bt btn-danger">Пост заблокирован из-за нарушения правил изспользования веб сайта
                                </small>
                                {{ $post->title_post }} 
                                <small class="text-muted mb-0">{{ $post->text }}
                                    </small>
                                    <hr>
                            </h5>
                            @elseif ($post->banned==1)
                                <div class="row">        
                                    <div class="coll-md-12">        
                                        <img  src="img/demo/avatars/avatar-admin-lg.png" alt="" class="img-fluid img-thumbnail gallery-image">
                                    </div>    
                                </div>    
                            </div>   
                            <h5 class="mb-0 fw-700 text-center mt-3 bt btn-danger">
                                Пост заблокирован из-за нарушения правил изспользования веб сайта
                                <small class="text-muted mb-0 bt btn-danger">Пост заблокирован из-за нарушения правил изспользования веб сайта
                                    </small>
                                    <hr>
                            </h5>
                            @else
                            @foreach ($post->images as $image)
                                <div class="row">       
                                    <div class="coll-md-12">        
                                        <img  src="{{ $image->image }}" alt="" class="img-fluid img-thumbnail gallery-image">
                                    </div>    
                                </div>
                                @endforeach
                            </div>   
                            <h5 class="mb-0 fw-700 text-center mt-3">
                                {{ $post->title_post }} 
                                <small class="text-muted mb-0">{{ $post->text }}
                                    </small>
                                    <hr>
                            </h5>
                            @endif
                            <h5 class="mb-0 fw-700 text-center mt-3">
                                {{ $user->name }}
                                <small class="text-muted mb-0">{{ $social->location }}</small>
                            </h5>
                            <div class="mt-4 text-center demo">
                                <a href="javascript:void(0);" class="fs-xl" style="color:#C13584">
                                    <i class="fab fa-instagram">{{ $social->instagram }}</i>
                                </a>
                                <a href="javascript:void(0);" class="fs-xl" style="color:#4680C2">
                                    <i class="fab fa-vk">{{ $social->vk }}</i>
                                </a>
                                <a href="javascript:void(0);" class="fs-xl" style="color:#0088cc">
                                    <i class="fab fa-telegram">{{ $social->telegram }}</i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="p-3 text-center">
                            <a href="tel:+13174562564" class="mt-1 d-block fs-sm fw-400 text-dark">
                                <i class="fas fa-mobile-alt text-muted mr-2"></i>{{$info->phone}}</a>
                            <a href="mailto:oliver.kopyov@marlin.ru" class="mt-1 d-block fs-sm fw-400 text-dark">
                                <i class="fas fa-mouse-pointer text-muted mr-2"></i> {{ $user->email }}</a>
                            <address class="fs-sm fw-400 mt-4 text-muted">
                                <i class="fas fa-map-pin mr-2"></i> {{ $info->occupation }}
                            </address>
                        </div>
                    </div>
                </div>
            </div>
       </div>
       <form action="#" method="POST" enctype="multipart/form-data" class="col-lg-10 col-xl-10 m-auto">
        {{ csrf_field() }}
        <div class="col-lg-12 col-xl-12 m-auto">
                <!-- текст поста -->
                    <div class="form-group">
                        <label class="form-label" for="simpleinput">Введите текст комментария</label>
                        <input type="text" id="simpleinput" class="form-control" name="comment" value="{{old('comment')}}" style="height: 100px">
                        <input type="text" id="simpleinput" class="form-control" name="user_id" value="{{ Auth::id()}}" style="height: 100px" hidden>
                    </div>                                     
        </div>       
         <div class="col-md-12 mt-3 d-flex flex-row-reverse">
          <button class="btn btn-info" type="submit" name="submit">Отправить комментарий</button>
        </div>
      </form>   
    </div>
    <br>
    <br>
       
      <script src="js/vendors.bundle.js"></script>
      <script src="js/app.bundle.js"></script>
      <script>
  
          $(document).ready(function()
          {
  
          });
  
      </script>      
  @endsection      
</main>

@section('comments')


<!-- навигационная строка раздела комментариев -->
<nav id="navbar-example2" class="navbar navbar-light bg-info px-3 m-auto" style="width: 83%;">
    <a class="navbar-brand" href="#">Комментарии</a>
    <ul class="nav nav-pills">
      <li class="nav-item">
        <a class="nav-link" href="#scrollspyHeading1">Сначала новые</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#scrollspyHeading2">Сначала старые</a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Меню</a>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="#scrollspyHeading3">меню1</a></li>
          <li><a class="dropdown-item" href="#scrollspyHeading4">меню2</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item" href="#scrollspyHeading5">меню3</a></li>
        </ul>
      </li>
    </ul>
  </nav>

  <!-- comments -->
  <div data-bs-spy="scroll" data-bs-target="#navbar-example2" data-bs-offset="0" class="scrollspy-example" tabindex="0">
    @foreach ($post->comments as $comment)
    <!-- комментарии авторизованного пользователя -->
    @if (Auth::id() == $comment->user_id )
    <div style="width: 70%; margin:20px 0px 0px 285px;">
        <div class="toast-header bg-info">
        <img src="{{ $info->avatar }}" class="rounded me-2" alt="logo">
        <strong class="me-auto">{{ App\Models\Comment::find($comment->user_id)->user->name }}</strong>
        <small >{{ $comment->updated_at }}</small>
        <!-- заблокированные коментарии -->
        @if (Auth::user()->admin && $comment->banned==1)
            <a class="bt btn-warning "  href="/unBannedComment/{{ $comment->id }}/{{ $post->id }}">
                <i class="fa fa-sun btn btn-warning"></i>Разблокировать комментарий</a>   
        @elseif (Auth::user()->admin)
            <a class="bt btn-warning "  href="/bannedComment/{{ $comment->id }}/{{ $post->id }}">
                <i class="fa fa-sun btn btn-danger"></i>Заблокировать комментарий</a>
        @endif     
        <a class=" btn-close " onclick="return confirm('are your sure?')" aria-label="Close"  href="/deleteComment/{{ $comment->id }}/{{ $post->id }}"></a>
        </div>
        @if ( Auth::user()->admin && $comment->banned==1)
        <h6 id="scrollspyHeading1 btn-danger" style="background:rgb(245, 158, 118); padding:20px 0px 20px 20px;"> Комментарий заблокирован из-за нарушения правил пользования веб сайтом : {{ $comment->comment }}</h6>    
        @elseif ($comment->banned==1)
        <h6 id="scrollspyHeading1" style="background:rgb(247, 169, 150); padding:40px 0px 20px 40px;">Комментарий заблокирован из-за нарушения правил пользования веб сайтом </h6>
        @else
        <h6 id="scrollspyHeading1" style="background:rgb(247, 240, 150); padding:20px 0px 20px 20px;">{{ $comment->comment }}</h6>
        @endif
    </div> 

    <!-- коментарии других пользователей -->
    @else
    <div style="width: 70%; margin:20px 0px 0px 110px;">
        <div class="toast-header bg-info">
        <img src="{{ $info->avatar }}" class="rounded me-2" alt="logo">
        <strong class="me-auto">{{ App\Models\Comment::find($comment->user_id)->user->name }}</strong>
        <small>{{ $comment->updated_at}}</small>
        <!-- заблокированные коментарии -->
        @if (Auth::user()->admin && $comment->banned==1)
            <a class="bt btn-warning "  href="/unBannedComment/{{ $comment->id }}/{{ $post->id }}">
                <i class="fa fa-sun btn btn-warning"></i>Разблокировать комментарий</a>   
        @elseif (Auth::user()->admin)
            <a class="bt btn-warning "  href="/bannedComment/{{ $comment->id }}/{{ $post->id }}">
                <i class="fa fa-sun btn btn-danger"></i>Заблокировать комментарий</a>
        @endif 
        <a class=" btn-close " onclick="return confirm('are your sure?')" aria-label="Close"  href="/deleteComment/{{ $comment->id }}/{{ $post->id }}"></a>    
        </div>
    @if (Auth::user()->admin && $comment->banned==1)
    <h6 id="scrollspyHeading1 btn-danger" style="background:rgb(247, 163, 163); padding:20px 0px 20px 20px; "> Комментарий заблокирован из-за нарушения правил пользования веб сайтом :  {{ $comment->comment }}</h6>    
    @elseif ($comment->banned==1)
    <h6 id="scrollspyHeading1 btn-danger" style="background:rgb(247, 163, 167); padding:20px 0px 20px 20px; "> Комментарий заблокирован из-за нарушения правил пользования веб сайтом</h6>
    @else
    <h6 id="scrollspyHeading1" style="background:rgb(163, 216, 247); padding:20px 0px 20px 20px; ">{{ $comment->comment }}</h6>
    @endif
    </div> 
  </div>
  @endif
  @endforeach
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
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
        