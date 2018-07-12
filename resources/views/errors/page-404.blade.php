<!DOCTYPE html>
<html lang="en">

    <!--================================================================================
            Item Name: Materialize - Material Design Admin Template
            Version: 3.1
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
        <title>404 Error Page | OSB SMB</title>

        <!-- Favicons-->
        <link rel="icon" href="images/login-logo.png" type="image/x-icon" />
        <!-- Favicons-->
        <!--        <link rel="apple-touch-icon-precomposed" href="images/favicon/apple-touch-icon-152x152.png">-->
        <!-- For iPhone -->
        <meta name="msapplication-TileColor" content="#00bcd4">
        <!--<meta name="msapplication-TileImage" content="images/favicon/mstile-144x144.png">-->
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

    </head>

    <body class="cyan">
        <!-- Start Page Loading -->
        <div id="loader-wrapper">
            <div id="loader"></div>        
            <div class="loader-section section-left"></div>
            <div class="loader-section section-right"></div>
        </div>
        <!-- End Page Loading -->



        <div id="error-page">

            <div class="row">
                <div class="col s12">
                    <div class="browser-window">
                        <div class="top-bar">
                            <div class="circles">
                                <div id="close-circle" class="circle"></div>
                                <div id="minimize-circle" class="circle"></div>
                                <div id="maximize-circle" class="circle"></div>
                            </div>
                        </div>
                        <div class="content">
                            <div class="row">
                                <div id="site-layout-example-top" class="col s12">
                                    <p class="flat-text-logo center white-text caption-uppercase">Lo Sentimos no encontramos la pagina que estas buscando :( </p>
                                </div>
                                <div id="site-layout-example-right" class="col s12 m12 l12">
                                    <div class="row center">
                                        <h1 class="text-long-shadow col s12">404</h1>
                                    </div>
                                    <div class="row center">
                                        <p class="center white-text col s12">It seems that this page doesn’t exist.</p>
                                        <p class="center s12"><button onclick="goBack()" class="btn waves-effect waves-light">Back</button> <a href="index.html" class="btn waves-effect waves-light">Homepage</a>
                                        <p>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- ================================================
          Scripts
          ================================================ -->

        <!-- jQuery Library -->
        {!!Html::script('js/jquery-2.1.4.min.js')!!}
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

        <script type="text/javascript">
                                            function goBack() {
                                                window.history.back();
                                            }
        </script>
    </body>

</html>