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
<main id="js-page-content" role="main" class="page-content mt-6">

            <!-- флеш сообщения,  начало блока-->
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
            <!-- флеш сообщения, окончание блока  -->

            <!-- добавить пост -->
            <div class="row mt-5">       
                @if (Auth::check() && Auth::user()->admin)
                    <a class="btn btn-danger" href="/addPost">Добавить пост</a>
                @endif    
            </div>       
@endsection

@section('posts')
    <div class="subheader mt-3">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-user'></i> {{ $post->name_post }}
        </h1>
    </div> 
    <div class="row">
      <div class="col-lg-12 col-xl-12 m-auto">
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
                                        <i class="fa fa-unlock btn btn-warning"></i>Разблокировать пост</a>   
                                @elseif (Auth::user()->admin)
                                    <a class="dropdown-item"  href="/bannedPost/{{ $post->id }}">
                                        <i class="fa fa-lock btn btn-danger"></i>Заблокировать пост</a>
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
       
  @endsection 
  
  
@section('comments')
<!-- навигационная строка раздела комментариев -->
<nav class="col-lg-12 col-xl-12 m-auto navbar navbar-expand-lg navbar-dark bg-danger bg-primary-gradient sticky-top mt-5">
    <a class="navbar-brand d-flex align-items-center fw-500" href="users.html"><img alt="logo" class="d-inline-block align-top mr-2 sizeImageNav" src="/lesson-project-php-mvc/public/img/paper-airplane-5.png" >comments</a> <button aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler" data-target="#navbarColor02" data-toggle="collapse" type="button"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="navbarColor02">
        <ul class="navbar-nav md-3">
            <li class="nav-item active">
                <a class="nav-link" href="/">All <span class="sr-only">(current)</span></a>
            </li>
        </ul>
        <?php if(auth()->user()->id == $post->user_id):?>
        <ul class="navbar-nav md-3">
            <li class="nav-item active">
                <a class="nav-link text-info" href="/posts" >Все комментарии <span class="sr-only">(current)</span></a>
            </li>
        </ul>
        <?php else:?>
        <ul class="navbar-nav md-3">
            <li class="nav-item active">
                <a class="nav-link" href="/posts">Все комментарии <span class="sr-only">(current)</span></a>
            </li>
        </ul>
        <?php endif;?>
        <?php if (auth()->user()->id == $post->user_id):?>
        <ul class="navbar-nav md-3">
            <li class="nav-item active">
                <a class="nav-link text-info" href="/myPosts/" >Мои комментарии <span class="sr-only">(current)</span></a>
            </li>
        </ul>    
        <?php else:?>
        <ul class="navbar-nav md-3">
            <li class="nav-item active">
                <a class="nav-link" href="/myPosts/">Мои комментарии <span class="sr-only">(current)</span></a>
            </li>
        </ul>
        <?php endif;?>
        <ul class="navbar-nav ml-auto">
            <?php if(auth()->user()->id == $post->user_id):?>
            <li class="nav-item">
                <a class="nav-link" href="/logout">Old comments</a>
            </li>
            <?php else:?>
            <li class="nav-item">
                <a class="nav-link" href="/login">New comments</a>
            </li>
            <?php endif;?>
        </ul>
    </div>
</nav> 

<div class="col-lg-12 col-xl-12 m-auto sticky-top bg-white">
    
        <div class="card mb-g rounded-top backgroundcolorCardPosts mt-3">
       <!-- форма ввода коментария -->
       <form action="/addNewComment/{{ $post->id }}" method="POST" enctype="multipart/form-data" class="col-lg-12 col-xl-12 m-auto">
        {{ csrf_field() }}
        <div class="col-lg-12 col-xl-12 m-auto">
                <!-- текст коментария -->
                    <div class="form-group">
                        <label class="form-label" for="simpleinput">Введите текст комментария</label>
                        <input type="text" id="simpleinput" class="form-control" name="comment" value="{{old('comment')}}">
                        <input type="text" id="simpleinput" class="form-control" name="user_id" value="{{ Auth::id()}}"  hidden>
                    </div>                                     
        </div>       
         <div class="col-md-12 mt-3 mb-3 d-flex flex-row-reverse">
          <button class="btn btn-danger" type="submit" name="submit">Отправить комментарий</button>
        </div>
      </form> 
    </div>
 </div>


  <!-- comments -->
<div class="col-lg-12 col-xl-12 m-auto">
  <div class="card mb-g rounded-top backgroundcolorCardCommentTop">   
  <div  class=" col-lg-10 col-xl-10 m-auto" >
    @foreach ($post->comments as $comment)
    <!-- комментарии авторизованного пользователя -->
    @if (Auth::id() == $comment->user_id )
    <div class="row mb-0" id="js-contacts">
        <div class="col-xl-4">
            <div  class="card border shadow-0 mb-g shadow-sm-hover mt-3 backgroundcolorCardCommentsAny"  >
                <div class="card-body border-faded border-top-0 border-left-0 border-right-0 rounded-top">
                    <div class="d-flex flex-row align-items-center">
                        <span class="status status-success mr-3">
                            <span class="rounded-circle profile-image d-block " style="background-image:url('{{ asset($comment->user->info->avatar) }}'); background-size: cover;"></span>
                        </span>
                        <div class="info-card-text flex-1">
                            <a href="javascript:void(0);" class="fs-xl text-truncate text-truncate-lg text-info" data-toggle="dropdown" aria-expanded="false">
                                {{ $comment->user->name }}
                                <i class="fal fas fa-cog fa-fw d-inline-block ml-1 fs-md"></i>
                                <i class="fal fa-angle-down d-inline-block ml-1 fs-md"></i>
                            </a>
                            <div class="dropdown-menu">
                                @if (Auth::user()->admin && $comment->banned==1)
                                <a class="dropdown-item text-warning"  href="/unBannedComment/{{ $comment->id }}/{{ $post->id }}">
                                    <i class="fa fa-unlock "> </i> Разблокировать</a>   
                                    @elseif (Auth::user()->admin)
                                <a class="dropdown-item text-danger"  href="/bannedComment/{{ $comment->id }}/{{ $post->id }}">
                                    <i class="fa fa-lock"> </i> Заблокировать</a>
                                    @endif 
                                <a class="dropdown-item" onclick="return confirm('are your sure?')" aria-label="Close"  href="/deleteComment/{{ $comment->id }}/{{ $post->id }}">
                                    <i class="fa fa-window-close"></i> Удалить комментарий</a> 
                            
                            </div>
                            <span class="text-truncate text-truncate-xl">{{ $comment->updated_at }}</span>
                            <span class="text-truncate text-truncate-xl">{{ $comment->user->name }}</span>
                        </div>
                        <button class="js-expand-btn btn btn-sm btn-default d-none" data-toggle="collapse" data-target="#c_1 > .card-body + .card-body" aria-expanded="false">
                            <span class="collapsed-hidden">+</span>
                            <span class="collapsed-reveal">-</span>
                        </button>
                    </div>    
                </div>
                @if (Auth::user()->admin && $comment->banned==1)
                    <h6  class="bg-danger bg-danger-gradient pt-3 pb-3 pl-3 text-white rounded-bottom mt-0"> Комментарий заблокирован из-за нарушения правил пользования веб сайтом : {{ $comment->comment }}</h6>    
                    @elseif ($comment->banned==1)
                    <h6  class="bg-secondary bg-secondary-gradient pt-3 pb-3 pl-3 text-white rounded-bottom mt-0">Комментарий заблокирован из-за нарушения правил пользования веб сайтом </h6>
                    @else
                    <h6  class=" pt-3 pb-3 pl-3  rounded-bottom mt-0 backgroundcolorCardCommentBottom">{{ $comment->comment }}</h6>
                @endif
            </div>
        </div>
    </div>

    <!-- коментарии других пользователей -->
    @else
    <div class="row mb-0" id="js-contacts">
        <div class="col-xl-4">
            <div  class="card border shadow-0 mb-g shadow-sm-hover mt-3 "  >
                <div class="card-body border-faded border-top-0 border-left-0 border-right-0 rounded-top">
                    <div class="d-flex flex-row align-items-center">
                        <span class="status status-success mr-3">
                            <span class="rounded-circle profile-image d-block " style="background-image:url('{{ asset($comment->user->info->avatar) }}'); background-size: cover;"></span>
                        </span>
                        <div class="info-card-text flex-1">
                            <a href="javascript:void(0);" class="fs-xl text-truncate text-truncate-lg text-info" data-toggle="dropdown" aria-expanded="false">
                                {{ $comment->user->name }}
                                <i class="fal fas fa-cog fa-fw d-inline-block ml-1 fs-md"></i>
                                <i class="fal fa-angle-down d-inline-block ml-1 fs-md"></i>
                            </a>
                            <div class="dropdown-menu">
                                @if (Auth::user()->admin && $comment->banned==1)
                                <a class="dropdown-item text-warning"  href="/unBannedComment/{{ $comment->id }}/{{ $post->id }}">
                                    <i class="fa fa-unlock "> </i> Разблокировать</a>   
                                @elseif (Auth::user()->admin)
                                <a class="dropdown-item text-danger"  href="/bannedComment/{{ $comment->id }}/{{ $post->id }}">
                                    <i class="fa fa-lock"> </i> Заблокировать</a>
                                @endif 
                                <a class="dropdown-item" onclick="return confirm('are your sure?')" aria-label="Close"  href="/deleteComment/{{ $comment->id }}/{{ $post->id }}">
                                    <i class="fa fa-window-close"></i> Удалить комментарий</a> 
                            
                            </div>
                            <span class="text-truncate text-truncate-xl">{{ $comment->updated_at }}</span>
                            <span class="text-truncate text-truncate-xl">{{ $comment->user->name }}</span>
                        </div>
                        <button class="js-expand-btn btn btn-sm btn-default d-none" data-toggle="collapse" data-target="#c_1 > .card-body + .card-body" aria-expanded="false">
                            <span class="collapsed-hidden">+</span>
                            <span class="collapsed-reveal">-</span>
                        </button>
                    </div>    
                </div>
                @if (Auth::user()->admin && $comment->banned==1)
                    <h6  class="bg-danger bg-danger-gradient pt-3 pb-3 pl-3 text-white rounded-bottom mt-0"> Комментарий заблокирован из-за нарушения правил пользования веб сайтом : {{ $comment->comment }}</h6>    
                    @elseif ($comment->banned==1)
                    <h6  class="bg-secondary bg-secondary-gradient pt-3 pb-3 pl-3 text-white rounded-bottom mt-0">Комментарий заблокирован из-за нарушения правил пользования веб сайтом </h6>
                    @else
                    <h6  class=" pt-3 pb-3 pl-3  rounded-bottom mt-0 backgroundcolorCardCommentBottom">{{ $comment->comment }}</h6>
                @endif
            </div>
        </div>
    </div>
    @endif
   @endforeach
  </div>
  </div>
</div> 
@endsection

@section('script')

<script src="{{ asset('js/vendors.bundle.js') }}"></script>
    <script src="{{ asset('js/app.bundle.js') }}"></script>

@endsection
        