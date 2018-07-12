@extends('Layouts.Panel')
@section('breadcumbs')
<div id="breadcrumbs-wrapper">
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l12">
                <h5 class="breadcrumbs-title">Datos de la empresa</h5>
                <ol class="breadcrumbs">
                    <li><a href="/index">Dashboard</a></li>
                    <li class="active">Datos de la empresa</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
@section('Contenido')
@include('Modal.modalempresa')
<div class="container">
    <div id="profile-page" class="section">
        <!-- profile-page-header -->
        <div id="profile-page-header" class="card">
            <div class="card-image waves-effect waves-block waves-light">
                <img class="activator" src="images/user-bg.jpg" alt="user background">                    
            </div>
            <figure class="card-profile-image">
                <img id='logo' src="images/avatar.jpg" alt="empresa-logo" class="circle z-depth-2 responsive-img activator">
            </figure>
            <div class="card-content">
                <div class="row">                    
                    <div class="col s3 offset-s2">                        
                        <h4  class="card-title grey-text text-darken-4"><strong id="nombre-empresa"></strong></h4>                   
                    </div> 
                </div>
            </div>
        </div>
        <!--/ profile-page-header -->
    </div>
    <div id="profile-page-content" class="row">
        <div class="col s12 m6 l7">
            <!-- Profile About Details  -->
            <ul id="profile-page-about-details" class="collection z-depth-1">
                <li class="collection-item">
                    <div class="row">
                        <div class="col s5 grey-text darken-1"><i class="mdi-social-domain"></i> Propietario</div>
                        <div class="col s7 grey-text text-darken-4 right-align"><span id="propietario2"></span></div>
                    </div>
                </li>
                <li class="collection-item">
                    <div class="row">
                        <div class="col s5 grey-text darken-1"><i class="mdi-action-wallet-travel"></i> Sitio Web</div>
                        <div class="col s7 grey-text text-darken-4 right-align"><span id="web2"></span></div>
                    </div>
                </li>
                <li class="collection-item">
                    <div class="row">
                        <div class="col s5 grey-text darken-1"><i class="mdi-action-markunread-mailbox"></i> E-Mail</div>
                        <div class="col s7 grey-text text-darken-4 right-align"><span id="email2"></span></div>
                    </div>
                </li>

                <li class="collection-item">
                    <div class="row">
                        <div class="col s5 grey-text darken-1"><i class="mdi-editor-insert-chart"></i> Vender con Stock</div>
                        <div class="col s7 grey-text text-darken-4 right-align"><span id="stock2"></span></div>
                    </div>
                </li>
            </ul>
            <!--/ Profile About Details  -->
        </div>
        <!-- profile-page-sidebar-->
        <div id="profile-page-sidebar" class="col s12 m6 l5">
            <!-- Profile About  -->
            <div class="card">
                <div class="card-content">
                    <span class="card-title"><strong>Actividad</strong></span>
                    <p><span id="actividad2"></span></p>
                </div>                  
            </div>
            <div >
                <button id='botoneditar' OnClick='javascript:openmodal(this);' value="" class='waves-effect waves-light btn' href='#' title='Editar' style="width: 100%; margin: 0px 0px 10px 0px;">
                    editar datos de la empresa <i class='material-icons'>mode_edit</i>
                </button>           
            </div>
            <!-- Profile About  -->
        </div>
    </div>
</div>
<input type="hidden" name="_token" value="{{ csrf_token() }}" id="token"> 
</div>
@stop
@section('scripts')
{!! Html::script('js/addempresa.js') !!} 
@endsection