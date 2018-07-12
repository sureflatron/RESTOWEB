<!DOCTYPE html>
<html lang="en">
    <!--================================================================================
      Item Name: Materialize - Material Design Admin Template
      Version: 1.0
      Author: GeeksLabs
      Author URL: http://www.themeforest.net/user/geekslabs
    ================================================================================ -->
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="msapplication-tap-highlight" content="no">
        <meta name="description" content="Materialize is a Material Design Admin Template,It's modern, responsive and based on Material Design by Google. ">
        <meta name="keywords" content="materialize, admin template, dashboard template, flat admin template, responsive admin template,">
        <title>Autenticacion| Inicio de session</title>
        <!-- Favicons-->
        <link rel="icon" href="images/login-logo.png" type="image/x-icon" />
        <!-- Favicons-->
        <link rel="apple-touch-icon-precomposed" href="images/favicon/apple-touch-icon-152x152.png">
        <!-- For iPhone -->
        <meta name="msapplication-TileColor" content="#00bcd4">
        <meta name="msapplication-TileImage" content="images/favicon/mstile-144x144.png">
        <!-- For Windows Phone -->
        <!-- CORE CSS-->
        <link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection">
        <link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection">
        <link href="css/page-center.css" type="text/css" rel="stylesheet" media="screen,projection">
        <!-- INCLUDED PLUGIN CSS ON THIS PAGE -->
    </head>
    <body class="naranja">
        <!-- Start Page Loading -->
        <div id="loader-wrapper">
            <div id="loader"></div>        
            <div class="loader-section section-left"></div>
            <div class="loader-section section-right"></div>
        </div>
        <!-- End Page Loading -->
        <div id="login-page" class="row">
            <div class="col s12 z-depth-4s card-panel">
                {!! Form::open(['route'=>'Login.index','method'=>'POST'])!!}
                <div class="row">
                    <div class="input-field col s12 center">
                        <img src="images/login-logo.png" alt="" class="circle responsive-img valign profile-image-login">
                        <p class="center login-form-text">
                            <strong>INICIAR SESION</strong>
                        </p>
                    </div>
                </div>
                <div class="row" style="text-align: center;">
                    @if(Session::has('message'))
                    <div class="chip red accent-4 center-align white-text">{{ Session::get('message') }}</div>
                    @endif
                </div>
                <div class="row margin">
                    <div class="input-field col s12">
                        <i class="mdi-social-person-outline prefix"></i>
                        {!! Form::text('username',null)!!} 
                        <label  class="center-align active">Usuario</label>
                    </div>
                </div>
                <div class="row margin">
                    <div class="input-field col s12">
                        <i class="mdi-action-lock-outline prefix"></i>
                        {!! Form::password('contra',null)!!} 
                        <label class="active" >Contraseña</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        {!! Form::submit('Iniciar',['class'=>'btn   waves-light col s12'])!!}
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s6 m6 l8">
                        <p class="margin medium-small">OSBolivia</p>
                    </div>
                    <div class="input-field col s6 m6 l4">
                        <p class="margin right-align medium-small">2017</p>
                    </div>          
                </div>
                {!! Form::close()!!}
            </div>
        </div>
        <!-- ================================================
          Scripts
          ================================================ -->
        <!-- jQuery Library -->
        <script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
        <!--materialize js-->
        <script type="text/javascript" src="js/materialize.js"></script>
        <!--prism-->
        <!--scrollbar-->
        <!--plugins.js - Some Specific JS codes for Plugin Settings  -->
        <script type="text/javascript" src="js/plugins.js"></script>
    </body>
</html>