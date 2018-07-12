<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="msapplication-tap-highlight" content="no">
        <meta name="description" content="Materialize is a Material Design Admin Template,It's modern, responsive and based on Material Design by Google. ">
        <meta name="keywords" content="materialize, admin template, dashboard template, flat admin template, responsive admin template,">
        <title>Cambiar Contraseña</title>
        <meta charset="utf-8">
        <!-- Favicons-->
        {!!Html::style('css/materialize.css')!!} 
        <link rel="icon" href="images/login-logo.png" type="image/x-icon" />
        <!-- For iPhone -->
        <meta name="msapplication-TileColor" content="#00bcd4">
        <meta name="msapplication-TileImage" content="images/favicon/mstile-144x144.png">
        <!-- For Windows Phone -->


        <!-- CORE CSS-->
        {!!Html::style('css/materialize.css')!!} 
        {!!Html::style('css/style.css')!!}
        <!-- Custome CSS-->    
        {!!Html::style('css/custom/custom.css')!!} 
        {!!Html::style('css/layouts/page-center.css')!!}

        <!-- INCLUDED PLUGIN CSS ON THIS PAGE -->
        {!!Html::style('js/plugins/prism/prism.css')!!} 
        {!!Html::style('js/plugins/perfect-scrollbar/perfect-scrollbar.css')!!}

        {!!Html::style('js/plugins/sweetalert/dist/sweetalert.css')!!}

    </head>

    <body class="naranja">
        <!-- Start Page Loading -->
        <div id="loader-wrapper">
            <div id="loader"></div>        
            <div class="loader-section section-left"></div>
            <div class="loader-section section-right"></div>
        </div>
        <!-- End Page Loading -->
        <div id="eti" class="row">
            <input type="hidden" value="{!! Session::get('idPerfil') !!}" id="iddelperfil">
            <input type="hidden" value="{!! Session::get('idpuntoventa') !!}" id="iddelpuntoventa">
            <input type="hidden" value="{!! Session::get('idempleado') !!}" id="iddelempleado">
            <input type="hidden"  value="{{ $idempleado }}" id="idempleado">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
            <div id="login-page" class="row">
                <div class="col s12 z-depth-4 card-panel">
                    <form class="login-form">
                        <div class="row">
                            <div class="input-field col s12 center">
                                <img src="{!! Session::get('imagen') !!}" alt="" class="circle responsive-img valign profile-image-login">
                                <h4 class="header">{{ $nombreempleado }}</h4> 
                                <p class="center login-form-text">Cambiar Contraseña de Usuario</p>
                            </div>
                        </div>
                        <div class="row margin">
                            <div class="input-field col s12">
                                <i class="mdi-action-lock-outline prefix"></i>
                                <input id="password1" type="password" placeholder="">
                                <label for="password">Contraseña actual</label>
                            </div>
                        </div>    
                        <div class="row margin">
                            <div class="input-field col s12">
                                <i class="mdi-action-label prefix"></i>
                                <input id="password2" type="password" placeholder="">
                                <label for="password">Contraseña nueva</label>
                            </div>
                        </div> 
                        <div class="row margin">
                            <div class="input-field col s12">
                                <i class="mdi-action-autorenew prefix"></i>
                                <input id="password3" type="password" placeholder="">
                                <label for="password">Repita contraseña nueva</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <a href="#" id="resetear" class="btn waves-effect waves-light col s12">
                                    <i class="mdi-content-send"></i>Cambiar</a>
                            </div>
                        </div>
                        <div class="row">          
                            <div class="input-field col s12">
                                <p class="margin medium-small"><a href="/index">IR A LA PAGINA PRINCIPAL</a></p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- ================================================
          Scripts
          ================================================ -->

        <!-- jQuery Library -->
        {!!Html::script('js/plugins/jquery-2.1.4.min.js')!!}
        <!--materialize js-->
        {!!Html::script('js/materialize.js')!!}
        <!--prism-->
        {!!Html::script('js/plugins/prism/prism.js')!!}
        <!--scrollbar-->
        {!!Html::script('js/plugins/perfect-scrollbar/perfect-scrollbar.min.js')!!}

        <!--plugins.js - Some Specific JS codes for Plugin Settings-->
        {!!Html::script('js/plugins.js')!!}
        <!--custom-script.js - Add your own theme custom JS-->
        {!!Html::script('js/custom-script.js')!!}
        {!! Html::script('js/addPassword.js') !!}
        {!!Html::script('js/plugins/sweetalert/dist/sweetalert.min.js')!!}
        {!!Html::script('js/addautorizacion.js')!!}

    </body>

</html>