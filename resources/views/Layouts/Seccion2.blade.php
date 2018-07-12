<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Anabel Angus Moda</title>     
        {!!Html::style('css/materialize.css')!!} 
        {!!Html::style('css/style.css')!!}
    </head>
    <body>
        <div>
            @yield('hola')
        </div>
    </body>
    {!!Html::script('js/materialize.min.js')!!}  
    {!!Html::script('js/plugins.js')!!}
    @section('elscripts')
    @show
</html>