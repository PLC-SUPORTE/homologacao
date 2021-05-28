
<!DOCTYPE html>

<html class="loading" lang="en" data-textdirection="ltr">
  <!-- BEGIN: Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Redefinir senha | Portal PL&C</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('/public/materialize/css/vendors.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/materialize.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/forgot.min.css') }}">
    <link rel="shortcut icon" href="{{ asset('/public/assets/imgs/casinha.png')}}" />


<style>

::placeholder { 
  color: black;
  opacity: 1; 
}
* {
      box-sizing: border-box;
    }
    .wrapper {
      height: 50px;
      margin-top: calc(50vh - 150px);
      margin-left: calc(50vw - 600px);
      width: 180px;
    }
    .circle {
      border-radius: 50%;
      border: 3px #0a0a0a solid;
      float: left;
      height: 50px;
      margin: 0 5px;
      width: 50px;
    }
      .circle-1 {
        animation: move 2s ease-in-out infinite;
      }
      .circle-1a {
        animation: fade 2s ease-in-out infinite;
      }
      @keyframes fade {
        0% {opacity: 0;}
        100% {opacity: 1;}
      }
      .circle-2 {
        animation: move 1s ease-in-out infinite;
      }
      @keyframes move {
        0% {transform: translateX(0);}
        100% {transform: translateX(60px);}
      }
      .circle-1a {
        margin-left: -120px;
        opacity: 0;
      }
      .circle-3 {
        animation: circle-3 1s ease-in-out infinite;
        opacity: 1;
      }
      @keyframes circle-3 {
        0% {opacity: 1;}
        100% {opacity: 0;}
      }

    h1 {
      color: black;
      font-size: 12px;
      font-weight: 400;
      letter-spacing: 0.05em;
      margin: 40px auto;
      text-transform: uppercase;
    }

</style>

  </head>
  <!-- END: Head-->
  <body style="background-image: url(../../public/imgs/home.jpg);" class="vertical-layout page-header-light vertical-menu-collapsible vertical-menu-nav-dark preload-transitions 1-column forgot-bg   blank-page blank-page" data-open="click" data-menu="vertical-menu-nav-dark" data-col="1-column">
    <div class="row">
      <div class="col s12">
        <div class="container">
        
        <div id="forgot-password" class="row">

  <div class="col s12 m6 l4 z-depth-4 offset-m4 card-panel border-radius-6 forgot-card bg-opacity-8">

  <center>
  <div id="loadingdiv" style="display:none">
      <div class="wrapper">
      <div class="circle circle-1"></div>
      <div class="circle circle-1a"></div>
      <div class="circle circle-2"></div>
      <div class="circle circle-3"></div>
     </div>
     <h1 style="text-align: center;">Aguarde, enquanto estamos enviando um e-mail com suas credenciais de acesso..&hellip;</h1>
     </div>
  </center>   

    <form id="form" class="login-form" role="form" action="{{ route('reset') }}" method="POST" role="create">
    {{ csrf_field() }}

    <input type="hidden" name="token" id="token" value="{{$token}}">
    <input type="hidden" name="cpf" id="cpf" value="{{$cpf}}">
    <input type="hidden" name="email" id="email" value="{{$email}}">


     <div id="corpodiv">

      <div class="row">
        <div class="input-field col s12">
          <h5 class="ml-4">Redefinir senha</h5>
          <p class="ml-4">Digite uma nova senha de acesso. Será encaminhado um e-mail com suas novas credenciais de acesso.</p>
        </div>
      </div>

      <div class="row">

        <div class="input-field col s12">
          <span>Digite uma nova senha:</span>
          <input type="password" name="novasenha" pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$" required placeholder="">
        </div>
      </div>

         <div class="row">

        <div class="input-field col s12">
          <span>Confirme sua senha:</span>
          <input type="password" name="confirmasenha" pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$" required placeholder="">
        </div>
      </div>   

      <div class="row">
      <div class="input-field col s12">
      <p><i class="material-icons left">check_circle_outline</i>Pelo menos 8 caracteres.</p>
      <p><i class="material-icons left">check_circle_outline</i>Pelo menos 1 letra maiúscula.</p>
      <p><i class="material-icons left">check_circle_outline</i>Pelo menos 1 número.</p>
      </div>
      </div>


      <div class="row">
        <div class="input-field col s12">
          <button type="button" onClick="envia();" class="btn waves-effect waves-light border-round col s12 mb-1" style="background-color: gray"><i class="material-icons left">arrow_right_alt</i>Redefinir senha</button>
        </div>
      </div>

      <div class="row">

        <div class="input-field col s6 m6 l6">
        <a style="color: black;font-weight: bold;" href="{{route('login')}}">Fazer login</a>
        </div>

      </div>
    </form>
  </div>
</div>
        </div>
        <div class="content-overlay"></div>
      </div>
    </div>

    <script src="{{ asset('/public/materialize/js/vendors.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>


<script>
function envia() {

    //Salvo no localstorage o e-mail e senha 
    var email = '{{$email}}';
    var cpf = '{{$cpf}}';

    window.localStorage.setItem('email', email);
    window.localStorage.setItem('cpf', cpf);


    document.getElementById("loadingdiv").style.display = "";
    document.getElementById("corpodiv").style.display = "none";
    document.getElementById("form").submit();
}    
</script>

  </body>
</html>