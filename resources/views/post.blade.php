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
    <link id="vendorsbundle" rel="stylesheet" media="screen, print" href="{{ asset('css/vendors.bundle.css') }}">
    <link id="appbundle" rel="stylesheet" media="screen, print" href="{{ asset('css/app.bundle.css') }}">
    <link id="myskin" rel="stylesheet" media="screen, print" href="{{ asset('css/skins/skin-master.css') }}">
    <link rel="stylesheet" media="screen, print" href="{{ asset('css/fa-solid.css') }}">
    <link rel="stylesheet" media="screen, print" href="{{ asset('css/fa-brands.css') }}">
    <link rel="stylesheet" media="screen, print" href="{{ asset('css/fa-regular.css') }}"> 
    
    
@endsection

@section('navchat')
<nav class="navbar navbar-expand-lg navbar-dark bg-info bg-info-gradient">
    <a class="navbar-brand d-flex align-items-center fw-500" href="users.html"><img alt="logo" class="d-inline-block align-top mr-2" src="{{ asset('img/logo.png') }}">Страница постов</a> <button aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler" data-target="#navbarColor02" data-toggle="collapse" type="button"><span class="navbar-toggler-icon"></span></button>
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
                                @if ( auth()->user()->admin || auth()->user()->id == $post->user_id)
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
                            <img src="{{ asset($post->avatar_post) }}" class="rounded-circle shadow-2 img-thumbnail bt btn-warning" alt="logo" style="width: 80%">
                            @elseif ($post->banned==1) 
                            <img src="{{ asset('img/demo/avatars/avatar-admin-lg.png') }}" class="rounded-circle shadow-2 img-thumbnail" alt="" style="width: 80%">  
                            @else
                            <img src="{{ asset($post->avatar_post) }}" class="rounded-circle shadow-2 img-thumbnail" alt="" style="width: 80%">
                            @endif
                            <br>
                            <hr> 

                            <!-- вывод галереи заблокированного поста для админа-->
                            @if(auth()->user()->admin && $post->banned==1)           
                            <div class="container">
                                <h2 align="center">Галерея заблокированного поста</h2>
                                <div class="row">
                                    @foreach ($post->images as $image)
                                        <div class="col-md-3 galery-item">
                                            <div>
                                                <img src="{{ asset($image->image) }}" alt="" class="img-fluid img-thumbnail" ">
                                            </div>
                                        <input type="file" id="example-fileinput" class="form-control-file" name="delete_image" hidden>    
                                        <a href="/imagePostShow/{{ $image->id }}" onclick="return confirm('are your sure?')" class="btn btn-info my-button">Open image</a>
                                        </div>
                                    @endforeach   
                                </div>
                            </div>
                            @endif
                            @if($post->banned==0)
                            <!-- галерея  не заблокированного поста -->
                            <div class="container">
                                <h2 align="center">Галерея поста</h2>
                                <div class="row">
                                    @foreach ($post->images as $image)
                                        <div class="col-md-3 galery-item">
                                            <div>
                                                <img src="{{ asset($image->image) }}" alt="" class="img-fluid img-thumbnail" ">
                                            </div>
                                           
                                        <a href="/imagePostShow/{{ $image->id }}"  class="btn btn-info my-button">Open image</a>
                                        </div>
                                    @endforeach   
                                </div>
                            </div>
                            @endif
                            @if( Auth::user()->admin && $post->banned==1)    
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
                            <h5 class="mb-0 fw-700 text-center mt-3 bt btn-danger">
                                Пост заблокирован из-за нарушения правил изспользования веб сайта
                                <small class="text-muted mb-0 bt btn-danger">Пост заблокирован из-за нарушения правил изспользования веб сайта
                                    </small>
                                    <hr>
                            </h5>
                            @else 
                            <h5 class="mb-0 fw-700 text-center mt-3">
                                {{ $post->title_post }} 
                                <small class="text-muted mb-0">{{ $post->text }}
                                    </small>
                                    <hr>
                            </h5>
                            @endif
                            <h5 class="mb-0 fw-700 text-center mt-3">
                                {{ $post->user->name }}
                                <small class="text-muted mb-0">{{ $post->info->location }}</small>
                            </h5>
                            <div class="mt-4 text-center demo">
                                <a href="javascript:void(0);" class="fs-xl" style="color:#C13584">
                                    <i class="fab fa-instagram">{{ $post->social->instagram }}</i>
                                </a>
                                <a href="javascript:void(0);" class="fs-xl" style="color:#4680C2">
                                    <i class="fab fa-vk">{{ $post->social->vk }}</i>
                                </a>
                                <a href="javascript:void(0);" class="fs-xl" style="color:#0088cc">
                                    <i class="fab fa-telegram">{{ $post->social->telegram }}</i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="p-3 text-center">
                            <a href="tel:+13174562564" class="mt-1 d-block fs-sm fw-400 text-dark">
                                <i class="fas fa-mobile-alt text-muted mr-2"></i>{{$post->info->phone}}</a>
                            <a href="mailto:oliver.kopyov@marlin.ru" class="mt-1 d-block fs-sm fw-400 text-dark">
                                <i class="fas fa-mouse-pointer text-muted mr-2"></i> {{ $post->user->email }}</a>
                            <address class="fs-sm fw-400 mt-4 text-muted">
                                <i class="fas fa-map-pin mr-2"></i> {{ $post->info->occupation }}
                            </address>
                        </div>
                    </div>
                </div>
            </div>
       </div>
       <!-- форма ввода коментария -->
       <form action="/addNewComment/{{ $post->id }}" method="POST" enctype="multipart/form-data" class="col-lg-10 col-xl-10 m-auto">
        {{ csrf_field() }}
        <div class="col-lg-12 col-xl-12 m-auto">
                <!-- текст коментария -->
                    <div class="form-group">
                        <label class="form-label" for="simpleinput">Введите текст комментария</label>
                        <input type="text" id="simpleinput" class="form-control" name="comment" value="{{old('comment')}}">
                        <input type="text" id="simpleinput" class="form-control" name="user_id" value="{{ Auth::id()}}"  hidden>
                    </div>                                     
        </div>       
         <div class="col-md-12 mt-3 d-flex flex-row-reverse">
          <button class="btn btn-info" type="submit" name="submit">Отправить комментарий</button>
        </div>
      </form>   
    </div>
    <br>
    <br>
    
      <script src="{{ asset('js/vendors.bundle.js') }}"></script>
      <script src="{{ asset('js/app.bundle.js') }}"></script>
      <script>
  
          $(document).ready(function()
          {
  
          });
  
      </script>      
  @endsection      
</main>

@section('comments')


<!-- навигационная строка раздела комментариев -->
<nav id="navbar-example2" class="navbar navbar-light bg-info px-3 m-auto col-lg-10 col-xl-10 rounded">
    <a class="navbar-brand" href="#">Комментарии</a>
    <ul class="nav nav-pills">
      <li class="nav-item">
        <a class="nav-link text-white" href="#scrollspyHeading1">Сначала новые</a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white"" href="#scrollspyHeading2">Сначала старые</a>
      </li>
    </ul>
  </nav>

  <!-- comments -->
  <div  class=" col-lg-10 col-xl-10 m-auto" >
    @foreach ($post->comments as $comment)
    <!-- комментарии авторизованного пользователя -->
    @if (Auth::id() == $comment->user_id )
    <div class="col-lg-10 col-xl-10 mt-2 ml-auto" >
        <div class="toast-header bg-info md-3 rounded-top">
        <span class="rounded-circle profile-image d-block md-3" style="background-image:url('{{ asset($comment->user->info->avatar) }}'); background-size: cover;"> </span>
        <strong class="md-3">{{ $comment->user->name }}</strong>
        <small class="ml-auto"> {{ $comment->updated_at }} </small>
        <!-- заблокированные коментарии -->
        @if (Auth::user()->admin && $comment->banned==1)
            <a class="bt btn-warning ml-auto"  href="/unBannedComment/{{ $comment->id }}/{{ $post->id }}">
                <i class="fa fa-sun btn btn-warning"> </i>Разблокировать комментарий</a>   
        @elseif (Auth::user()->admin)
            <a class="bt text-danger ml-auto"  href="/bannedComment/{{ $comment->id }}/{{ $post->id }}">
                <i class="fa fa-sun btn btn-danger"> </i>Заблокировать комментарий</a>
        @endif     
        <a class=" btn-close ml-auto text-white" onclick="return confirm('are your sure?')" aria-label="Close"  href="/deleteComment/{{ $comment->id }}/{{ $post->id }}"> Удалить комментарий</a>
        </div>
        @if ( Auth::user()->admin && $comment->banned==1)
        <h6 id="scrollspyHeading1 btn-danger" class="bg-danger bg-danger-gradient pt-4 pb-4 pl-4 text-white rounded-bottom"> Комментарий заблокирован из-за нарушения правил пользования веб сайтом : {{ $comment->comment }}</h6>    
        @elseif ($comment->banned==1)
        <h6 id="scrollspyHeading1" class="bg-secondary bg-secondary-gradient pt-4 pb-4 pl-4 text-white rounded-bottom">Комментарий заблокирован из-за нарушения правил пользования веб сайтом </h6>
        @else
        <h6 id="scrollspyHeading1" class="bg-warning bg-waning-gradient pt-4 pb-4 pl-4 rounded-bottom">{{ $comment->comment }}</h6>
        @endif
    </div> 

    <!-- коментарии других пользователей -->
    @else
    <div class="col-lg-10 col-xl-10 mt-2"  >
        <div class="toast-header bg-info rounded-top">
        <span class="rounded-circle profile-image d-block " style="background-image:url('{{ asset($comment->user->info->avatar) }}'); background-size: cover;"></span>
        <strong class="md-3">{{ $comment->user->name }}</strong>
        <small class="ml-auto">{{ $comment->updated_at}}</small>
        <!-- заблокированные коментарии -->
        @if (Auth::user()->admin && $comment->banned==1)
            <a class="bt btn-warning ml-auto"  href="/unBannedComment/{{ $comment->id }}/{{ $post->id }}">
                <i class="fa fa-sun btn btn-warning"></i>Разблокировать комментарий</a>   
        @elseif (Auth::user()->admin && $comment->banned==0)
            <a class="text-danger ml-auto"  href="/bannedComment/{{ $comment->id }}/{{ $post->id }}">
                <i class="fa fa-sun btn btn-danger"></i>Заблокировать комментарий</a>
        @endif 
        <a class=" btn-close ml-auto text-white" onclick="return confirm('are your sure?')" aria-label="Close"  href="/deleteComment/{{ $comment->id }}/{{ $post->id }}">Удалить комментарий</a>    
        </div>
    @if (Auth::user()->admin && $comment->banned==1)
    <h6 id="scrollspyHeading1 btn-danger" class="bg-danger bg-danger-gradient pt-4 pb-4 pl-4 text-white rounded-bottom" > Комментарий заблокирован из-за нарушения правил пользования веб сайтом :  {{ $comment->comment }}</h6>    
    @elseif ($comment->banned==1)
    <h6 id="scrollspyHeading1 btn-danger" class="bg-secondary bg-secondary-gradient pt-4 pb-4 pl-4 text-white rounded-bottom" > Комментарий заблокирован из-за нарушения правил пользования веб сайтом</h6>
    @else
    <h6 id="scrollspyHeading1" class=" bg-info-gradient pt-4 pb-4 pl-4 rounded-bottom" >{{ $comment->comment }}</h6>
    @endif
    </div> 
   @endif 
   @endforeach
  </div>
  
  
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
        