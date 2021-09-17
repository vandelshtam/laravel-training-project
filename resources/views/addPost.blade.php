@extends('layout') 

@section('title')
    Add Post list
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
            <i class='subheader-icon fal fa-user'></i> Добавление нового поста
        </h1>
    </div>
    <div class="row ">
    <form action="/addPost/{{ Auth::id() }}" method="POST" enctype="multipart/form-data" class="col-lg-12 col-xl-12 m-auto">
        {{ csrf_field() }}
      <div class="col-lg-12 col-xl-12 m-auto">
            <!-- пост -->
            <div class="card mb-g rounded-top">
                <div class="row no-gutters row-grid">    
                    <div class="col-12">    
                        <div class="d-flex flex-column align-items-center justify-content-center p-4">    
                            <!-- аватар поста -->    
                            <h2 align="center">Аватар поста</h2>
                            <div class="panel-container col-lg-12 col-xl-12 m-auto" >       
                                <div class="panel-content" >       
                                    <div class="form-group">
                                        <label class="form-label" for="example-fileinput">Выберите аватар</label>
                                        <input type="file" id="example-fileinput" class="form-control-file" name="avatar_post">
                                    </div>   
                                </div>
                            </div>
                            <br>
                            <hr> 

                            <!-- фотографии поста -->
                            <div class="container">
                                <h2 align="center">My galery</h2>    
                            </div>
                            <div class="panel-container col-lg-12 col-xl-12 m-auto" >
                                <div class="panel-content" >   
                                    <div class="form-group">
                                        <label class="form-label" for="example-fileinput">Выберите фотографию</label>
                                        <input type="file" id="example-fileinput" class="form-control-file" name="image_post">
                                    </div>   
                                </div>
                            </div> 

                            <!-- Название поста -->
                            <h5 class="col-md-12 text-center mt-3">   
                                <div class="form-group ">
                                    <label class="form-label" for="simpleinput">Введите название поста</label>
                                    <input type="text" id="simpleinput" class="form-control" name="name_post" value="{{old('name_post')}}">
                                </div>

                            <!-- заголовок поста -->
                            <h5 class="col-md-12 text-center mt-3">    
                                <div class="form-group ">
                                    <label class="form-label" for="simpleinput">Введите заголовок поста</label>
                                    <input type="text" id="simpleinput" class="form-control" name="title_post" value="{{old('title_post')}}">
                                </div>

                            <!-- текст поста -->
                            <div class="form-group">
                                <label class="form-label" for="simpleinput">Введите текст поста</label>
                                <input type="text" id="simpleinput" class="form-control" name="text" value="{{old('text')}}" style="height: 100px">
                            </div>       
                            </h5>    
                        </div>
                    </div>    
                </div>
            </div>
       </div>

       <!-- конопка добавления поста -->
       <div class="col-md-12 mt-3 d-flex flex-row-reverse">
        <button class="btn btn-info" type="submit" name="submit">Сохранить и добавить пост</button>
    </div>
    </form>
    </div>
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
        