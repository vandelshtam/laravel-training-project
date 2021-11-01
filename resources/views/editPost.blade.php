@extends('layout') 

@section('title')
    Edit Post list
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
@endsection

@section('posts')
<main id="js-page-content" role="main" class="page-content mt-3">
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-user'></i> Название поста
        </h1>
    </div>
     
    <!--вывод информации поста -->
    <div class="row ">
    <form action="/changeAvatar/{{ $post->id }}" method="POST" enctype="multipart/form-data" class="col-lg-12 col-xl-12 m-auto">
        {{ csrf_field() }}
        <div class="col-lg-12 col-xl-12 m-auto">
            <!-- пост -->
            <div class="card mb-g rounded-top">
                <div class="row no-gutters row-grid">    
                    <div class="col-12">        
                        <div class="d-flex flex-column align-items-center justify-content-center p-4">    
                                @if ( auth()->user()->admin || auth()->user()->id == $user->id)
                                <a class="dropdown-item" onclick="return confirm('are your sure?')" href="/post/{{ $post->id }}">
                                    <i class="fa fa-window-close btn btn-info"></i>
                                 Вернуться к посту</a>        
                                <a class="dropdown-item" onclick="return confirm('are your sure?')" href="/deletePost/{{ $post->id }}">
                                    <i class="fa fa-window-close btn btn-info"></i>
                                Удалить пост</a>    
                                @endif   

                            <!-- аватар поста -->    
                            <h2 align="center">Аватар поста</h2>
                            <img src="{{ asset($post->avatar_post) }}" class="rounded-circle shadow-2 img-thumbnail col-lg-10 col-xl-10" alt="" >
                            <div class="panel-container col-lg-12 col-xl-12 m-auto" >
                                <div class="panel-hdr">
                                    <h2>Текущий аватар</h2>
                                </div>
                                <div class="panel-content" >   
                                    <div class="form-group">
                                        <label class="form-label" for="example-fileinput">Выберите аватар</label>
                                        <input type="file" id="example-fileinput" class="form-control-file" name="avatar_post">
                                    </div>    
                                    <div class="col-lg-11 col-xl-11 m-auto d-flex flex-row-reverse">   
                                        <button class="btn btn-warning" type="submit" name="submit">Загрузить аватар</button>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <hr> 
                        </form>
                            <!-- фотографии поста -->
                            <div class="container">
                                <h2 align="center">My galery</h2>
                                <div class="row">
                                    @foreach ($post->images as $image)
                                        <div class="col-md-3 galery-item">
                                            <div>
                                                <img src="{{ asset($image->image) }}" alt="" class="img-fluid img-thumbnail">
                                            </div>
                                        <input type="file" id="example-fileinput" class="form-control-file" name="delete_image" hidden>    
                                        <a href="/delete_image/{{ $image->id }}/{{ $post->id }}" onclick="return confirm('are your sure?')" class="btn btn-danger my-button">Delete image</a>
                                        </div>
                                    @endforeach   
                                </div>
                            </div>
                            <div class="panel-container col-lg-12 col-xl-12 m-auto" >
                                <div class="panel-hdr">
                                    <h2>Текущие фотографии в галерее</h2>
                                </div>
                                <div class="panel-content" >  
                                    <form action="/downloadImage/{{ $post->id }}/{{ auth()->user()->id }}" method="POST" enctype="multipart/form-data" class="col-lg-12 col-xl-12 m-auto"> 
                                        {{ csrf_field() }}  
                                    <div class="form-group">
                                        <label class="form-label" for="example-fileinput">Выберите фотографии</label>
                                        <input type="file" id="example-fileinput" class="form-control-file" name="image">
                                    </div>
                                    <div class="col-md-12 mt-3 d-flex flex-row-reverse">
                                        <button class="btn btn-warning" type="submit" name="submit_image">Загрузить фотографии</button>
                                    </div>
                                    </form>
                                </div>
                            </div> 

                            <form action="/editInsertPost/{{ $post->id }}" method="POST" enctype="multipart/form-data" class="col-lg-12 col-xl-12 m-auto">
                                {{ csrf_field() }}
                            <!-- Название поста -->
                            <h5 class="col-md-12 text-center mt-3">   
                                <div class="form-group ">
                                    <label class="form-label" for="simpleinput">Введите название поста</label>
                                    <input type="text" id="simpleinput" class="form-control" name="name_post" value="{{ $post->name_post}}">
                                </div>

                            <!-- заголовок поста -->
                            <h5 class="col-md-12 text-center mt-3">    
                                <div class="form-group ">
                                    <label class="form-label" for="simpleinput">Введите заголовок поста</label>
                                    <input type="text" id="simpleinput" class="form-control" name="title_post" value="{{ $post->title_post}}">
                                </div>

                            <!-- текст поста -->
                            <div class="form-group">
                                <label class="form-label" for="simpleinput">Введите текст поста</label>
                                <input type="text" id="simpleinput" class="form-control" name="text" value="{{ $post->text}}" style="height: 100px">
                            </div>       
                        </div>
                    </div>    
                </div>
                <div class="col-lg-11 col-xl-11 ml-auto mr-5 mb-4 d-flex flex-row-reverse ">
                <button class="btn btn-danger" type="submit" onclick="return confirm('are your sure?')" name="submit">Сохранить изменения</button>
                </div>    
            </div>  
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

        });

    </script>
@endsection
        