
<!DOCTYPE html>

<html class="loading" lang="en" data-textdirection="ltr">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Materialize is a Material Design Admin Template,It's modern, responsive and based on Material Design by Google.">
    <meta name="keywords" content="materialize, admin template, dashboard template, flat admin template, responsive admin template, eCommerce dashboard, analytic dashboard">
    <meta name="author" content="ThemeSelect">
    <title>Meus números da sorte | Portal PL&C</title>
    <link rel="apple-touch-icon" href="../../../app-assets/images/favicon/apple-touch-icon-152x152.png">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('/public/materialize/css/vendors.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/materialize.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/style.min.css') }}">
    <link rel="shortcut icon" href="{{ asset('/public/assets/imgs/casinha.png')}}" />
  </head>

  <body id="body" style="background-image: url(public/imgs/sorteiofundo.jpg);">

   

    <!-- BEGIN: Page Main-->
    <div id="main">
      <div class="row">

        <div class="col s12">
          <div class="container">
            <div class="section">

  <!--Secondary content-->
  <div class="row">
    <div class="col s12">
      <div id="secondary-content" class="card card-tabs">
        <div class="card-content">
          <div class="card-title">
            <div class="row">
              <div class="col s12 m6 l10">
                <h4 class="card-title">Meus números da sorte</h4>
              </div>
 
            </div>
          </div>

          <div id="view-content">
            <div class="row">
              <div class="col s12">
                <ul class="collection with-header">

                @foreach($datas as $data)
                  <li class="collection-item">
                    <div>
                    Meu número: {{$data->Numero}}
                      <a href="#modal{{$data->id}}" class="modal-trigger secondary-content">
                        <i class="material-icons">search</i>
                      </a>
                    </div>
                  </li>
                 @endforeach 

                 @foreach ($datas as $data)
               <div id="modal{{$data->id}}" class="modal z-depth-4 offset-m4 card-panel border-radius-6 forgot-card bg-opacity-2" style="width: 40%;">
                  <button type="button" class="btn waves-effect mr-sm-1 mr-2 modal-close red" style="margin-top:1px;margin-left: 462px;position: fixed;"><i class="material-icons">close</i></button>
                  <div class="modal-content" >
                     <div class="container" style="border: 1px solid gray; border-radius: 25px; width: 80%;">
                        <p style="font-size: 13px;"><b>Meu número:</b> {{$data->Numero}}</p>
                        @if($data->Resultado != null)
                        <p style="font-size: 13px;"><b>Prêmio:</b> {{$data->Premio}}</p>
                        <p style="font-size: 13px;"><b>Número vencedor:</b> {{$data->Resultado}}</p>
                        <p style="font-size: 13px;"><b>Vencedor:</b> {{$data->VencedorNome}}</p>
                        <p style="font-size: 13px;"><b>Data do resultado:</b> {{ date('d/m/Y H:i:s', strtotime($data->DataResultado)) }}</p>
                        @else 
                        <p style="color: red;font-size:12px;"><b>Resultado não anunciado.</b></p>
                        @endif
                     </div>
                  </div>
               </div>
               @endforeach
                  
                </ul>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>

 

    <script src="{{ asset('public/materialize/js/vendors.min.js') }}"></script>
    <script src="{{ asset('public/materialize/js/plugins.min.js') }}"></script>
    <script src="{{ asset('public/materialize/js/custom-script.min.js') }}"></script>
    <script src="{{ asset('public/materialize/js/customizer.min.js') }}"></script>

    <script>
         document.addEventListener("DOMContentLoaded", function () {
           $('.modal').modal();
           var elems = document.querySelectorAll('.collapsible');
           $('.collapsible').collapsible('open', 0);

         });
         
      </script>


  </body>
</html>