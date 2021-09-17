<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>
        @yield('title')
    </title>
    
    @yield('meta')

    @yield('style')    
</head>

    <body class="mod-bg-1 mod-nav-link">
        
        @yield('nav')
        @yield('navchat')
        
        @yield('content')
        @yield('posts')
        @yield('comments')
        @yield('script')
    </body>

</html>