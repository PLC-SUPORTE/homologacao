
<!DOCTYPE html>

<html class="loading" lang="en" data-textdirection="ltr">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Materialize is a Material Design Admin Template,It's modern, responsive and based on Material Design by Google.">
    <meta name="keywords" content="materialize, admin template, dashboard template, flat admin template, responsive admin template, eCommerce dashboard, analytic dashboard">
    <meta name="author" content="ThemeSelect">
    <title>Gerar número da sorte | Portal PL&C</title>
    <link rel="apple-touch-icon" href="../../../app-assets/images/favicon/apple-touch-icon-152x152.png">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('/public/materialize/css/vendors.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/materialize.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/style.min.css') }}">
    <link rel="shortcut icon" href="{{ asset('/public/assets/imgs/casinha.png')}}" />

  </head>

  <body style="background-image: url(../public/imgs/sorteiofundo.jpg);">

  

    <!-- BEGIN: Page Main-->
    <div id="main">
      <div class="row">
        <div class="col s12">
          <div class="container">
            <div class="seaction">

            <center>
            <div id="loadingdiv" style="display:none;margin-top: 25%">
              <img style="width: 100px;margin-left:50%." src="{{URL::asset('/public/imgs/loading.gif')}}"/>
            </center>   

            <div id="corpodiv">

  <div class="card" >
    <div class="card-content">
      <p class="caption mb-0">Digite o e-mail relacionado a sua conta. Será encaminhado um e-mail com o número do sorteio.</p>
    </div>
  </div>

  <!--Basic Form-->

  <!-- jQuery Plugin Initialization -->
  <div class="row">
   
  
    <!-- Form with icon prefixes -->
    <div class="col s12 m12 l12">
      <div id="prefixes" class="card card card-default scrollspy">
        <div class="card-content">

        <form id="form" class="login-form" role="form" action="{{ route('quintosorteiogerado') }}" method="POST" role="create">
      {{ csrf_field() }}

            <div class="row">
              <div class="input-field col s12">
                <i class="material-icons prefix">email</i>
                <input id="dado" name="dado" type="email" placeholder="Informe o seu e-mail" required>
                <label for="dado">E-mail</label>
              </div>
            </div>

            <div class="row">

            <div class="input-field col s12">
              <button class="btn waves-effect waves-light right" type="button" onClick="envia();" name="action" style="background-color: gray;width:100%">Gerar número
                    <i class="material-icons left">send</i>
              </button>
            </div>

            </div>
          </form>

        </div>
      </div>
    </div>

  
</div>


    <script src="{{ asset('public/materialize/js/vendors.min.js') }}"></script>
    <script src="{{ asset('public/materialize/js/plugins.min.js') }}"></script>
    <script src="{{ asset('public/materialize/js/custom-script.min.js') }}"></script>
    <script src="{{ asset('public/materialize/js/customizer.min.js') }}"></script>

<script>
function envia() {

    var dado = $('#dado').val();

    document.getElementById("loadingdiv").style.display = "";

    $('#corpodiv').hide();

    document.getElementById("form").submit();

}    
</script>


  </body>
</html>