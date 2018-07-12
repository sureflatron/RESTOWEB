<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Reporte(OsB SMB)</title>
    {!!Html::style('css/materialize.css')!!} 
    {!!Html::style('css/style.css')!!}
</head>
<body>
    <!-- START HEADER -->
    <header id="header" class="page-topbar">
        <!-- start header nav-->
        <div class="navbar-fixed">
            <nav class="naranja">
                <div class="nav-wrapper">
                    <h1 class="logo-wrapper">
                    <a   class="brand-logo darken-1">
                        {!! HTML::image('images/materialize-logo.png', 'a picture')!!}  
                    </a>
                    <span class="logo-text"> </span></h1>
                    <ul class="right hide-on-med-and-down">
                        <li class="search-out">
                            <input type="text" class="search-out-text">
                        </li>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <!-- end header nav-->
    </header>
    <section id="content">
        <div class="container">
            @yield('Contenido')
        </div>
    </section>
{!!Html::script('js/jquery-1.11.2.min.js')!!}
@section('scripts')
@show
</body>
</html>