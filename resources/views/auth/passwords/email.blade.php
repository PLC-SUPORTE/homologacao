
<!DOCTYPE html>

<html class="loading" lang="en" data-textdirection="ltr">
  <!-- BEGIN: Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Recuperar senha | Portal PL&C</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('/public/materialize/css/vendors.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/materialize.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/forgot.min.css') }}">
    <link rel="shortcut icon" href="{{ asset('/public/assets/imgs/casinha.png')}}" />



    <style>
     .span{
        font-weight: bold;
     }
/* HTML5 display-role reset for older browsers */
article, aside, details, figcaption, figure, 
footer, header, hgroup, menu, nav, section, main {
	display: block;
}
ol, ul {
	list-style: none;
}
blockquote, q {
	quotes: none;
}
blockquote:before, blockquote:after,
q:before, q:after {
	content: '';
	content: none;
}
table {
	border-collapse: collapse;
	border-spacing: 0;
}

.img-replace {
  /* replace text with an image */
  display: inline-block;
  overflow: hidden;
  text-indent: 100%;
  color: transparent;
  white-space: nowrap;
}


.cd-nugget-info {
  text-align: center;
  position: absolute;
  width: 100%;
  height: 50px;
  line-height: 50px;
  bottom: 0;
  left: 0;
}
.cd-nugget-info a {
  position: relative;
  font-size: 14px;
  color: #5e6e8d;
  -webkit-transition: all 0.2s;
  -moz-transition: all 0.2s;
  transition: all 0.2s;
}
.no-touch .cd-nugget-info a:hover {
  opacity: .8;
}
.cd-nugget-info span {
  vertical-align: middle;
  display: inline-block;
}
.cd-nugget-info span svg {
  display: block;
}
.cd-nugget-info .cd-nugget-info-arrow {
  fill: #5e6e8d;
}


.cd-popup-trigger {
  display: block;
  width: 170px;
  height: 50px;
  line-height: 50px;
  margin: 3em auto;
  text-align: center;
  color: #FFF;
  font-size: 14px;
  font-size: 0.875rem;
  font-weight: bold;
  text-transform: uppercase;
  border-radius: 50em;
  background: #35a785;
  box-shadow: 0 3px 0 rgba(0, 0, 0, 0.07);
}
@media only screen and (min-width: 1170px) {
  .cd-popup-trigger {
    margin: 6em auto;
  }
}

.cd-popup {
  position: fixed;
  left: 0;
  top: 0;
  height: 100%;
  width: 100%;
  opacity: 0;
  visibility: hidden;
  -webkit-transition: opacity 0.3s 0s, visibility 0s 0.3s;
  -moz-transition: opacity 0.3s 0s, visibility 0s 0.3s;
  transition: opacity 0.3s 0s, visibility 0s 0.3s;
}
.cd-popup.is-visible {
  opacity: 1;
  visibility: visible;
  -webkit-transition: opacity 0.3s 0s, visibility 0s 0s;
  -moz-transition: opacity 0.3s 0s, visibility 0s 0s;
  transition: opacity 0.3s 0s, visibility 0s 0s;
}

.cd-popup-container {
  position: relative;
  width: 90%;
  max-width: 400px;
  margin: 4em auto;
  background: #FFF;
  border-radius: .25em .25em .4em .4em;
  text-align: center;
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
  -webkit-transform: translateY(-40px);
  -moz-transform: translateY(-40px);
  -ms-transform: translateY(-40px);
  -o-transform: translateY(-40px);
  transform: translateY(-40px);
  /* Force Hardware Acceleration in WebKit */
  -webkit-backface-visibility: hidden;
  -webkit-transition-property: -webkit-transform;
  -moz-transition-property: -moz-transform;
  transition-property: transform;
  -webkit-transition-duration: 0.3s;
  -moz-transition-duration: 0.3s;
  transition-duration: 0.3s;
}
.cd-popup-container p {
  padding: 3em 1em;
}
.cd-popup-container .cd-buttons:after {
  content: "";
  display: table;
  clear: both;
}
.cd-popup-container .cd-buttons li {
  float: left;
  width: 50%;
  list-style: none;
}
.cd-popup-container .cd-buttons a {
  display: block;
  height: 60px;
  line-height: 60px;
  text-transform: uppercase;
  color: #FFF;
  -webkit-transition: background-color 0.2s;
  -moz-transition: background-color 0.2s;
  transition: background-color 0.2s;
}
.cd-popup-container .cd-buttons li:first-child a {
  background: #b6bece;
  border-radius: 0 0 0 .25em;
}
.no-touch .cd-popup-container .cd-buttons li:first-child a:hover {
  background-color: #fc8982;
}
.cd-popup-container .cd-buttons li:last-child a {
  background: #52ca52;
  border-radius: 0 0 .25em 0;
}
.no-touch .cd-popup-container .cd-buttons li:last-child a:hover {
  background-color: #c5ccd8;
}
.cd-popup-container .cd-popup-close {
  position: absolute;
  top: 8px;
  right: 8px;
  width: 30px;
  height: 30px;
}
.cd-popup-container .cd-popup-close::before, .cd-popup-container .cd-popup-close::after {
  content: '';
  position: absolute;
  top: 12px;
  width: 14px;
  height: 3px;
  background-color: #8f9cb5;
}
.cd-popup-container .cd-popup-close::before {
  -webkit-transform: rotate(45deg);
  -moz-transform: rotate(45deg);
  -ms-transform: rotate(45deg);
  -o-transform: rotate(45deg);
  transform: rotate(45deg);
  left: 8px;
}
.cd-popup-container .cd-popup-close::after {
  -webkit-transform: rotate(-45deg);
  -moz-transform: rotate(-45deg);
  -ms-transform: rotate(-45deg);
  -o-transform: rotate(-45deg);
  transform: rotate(-45deg);
  right: 8px;
}
.is-visible .cd-popup-container {
  -webkit-transform: translateY(0);
  -moz-transform: translateY(0);
  -ms-transform: translateY(0);
  -o-transform: translateY(0);
  transform: translateY(0);
}
@media only screen and (min-width: 1170px) {
  .cd-popup-container {
    margin: 8em auto;
  }
}
</style>


  </head>
  <!-- END: Head-->
  <body style="background-image: url(.././public/imgs/home.jpg);" class="vertical-layout page-header-light vertical-menu-collapsible vertical-menu-nav-dark preload-transitions 1-column forgot-bg   blank-page blank-page" data-open="click" data-menu="vertical-menu-nav-dark" data-col="1-column">
    <div class="row">
      <div class="col s12">
        <div class="container">
        
        <div id="forgot-password" class="row">

  <div class="col s12 m6 l4 z-depth-4 offset-m4 card-panel border-radius-6 forgot-card bg-opacity-8">

  <div id="loadingdiv" style="display:none;margin-top: 20px; margin-left: 190px;">
         <img style="width: 25px; margin-top: -100px;" src="{{URL::asset('/public/imgs/loading.gif')}}"/>
         <h6 style="font-size: 12px;margin-left:-140px;color:black;">Aguarde, estamos enviando um token para redefinição de senha...</h6>
  </div>


  <form id="form" class="login-form" role="form" action="{{ route('enviarsenha') }}" method="POST" role="create">
    {{ csrf_field() }}


     <div id="corpodiv">

      <div class="row">
        <div class="input-field col s12">
          <h5 class="ml-4">Recuperar senha</h5>
          <p class="ml-4">Digite o número do CPF ou E-mail relacionado a sua conta. Será encaminhado um e-mail para redefinição de senha.</p>
        </div>
      </div>

      <div class="row">

        <div class="input-field col s12">
          <span>Digite o número do CPF ou E-mail:</span>
          <input type="text" name="dado" id="dado" required placeholder="">
          <span style="color: red;" id="email_error"></span>
        </div>
      </div>

      <div class="row">
        <div class="input-field col s12">
          <button type="button" id="btnsubmit" onClick="envia();" class="btn waves-effect waves-light border-round col s12 mb-1" style="background-color: gray"><i class="material-icons left">arrow_right_alt</i>Recuperar senha</button>
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

    <div id="alerta" name="alerta" class="cd-popup" role="alert">
<div class="cd-popup-container">
<p style="font-weight: bold;">Não foi possível encontrar nenhum usuário com este e-mail ou código informados.</p>
<ul class="cd-buttons" style="width: 800px">
<li><a href="#" onClick="nao();" style="font-weight: bold;background-color:#b6bece">Fechar</a></li>
</ul>
<a onClick="nao();" class="cd-popup-close img-replace">Close</a>
</div> 
</div> 

<div id="alertacamposfaltantes" name="alertacamposfaltantes" class="cd-popup" role="alert">
	<div class="cd-popup-container">
		<p style="font-weight: bold;">Favor informar o e-mail ou CPF/CNPJ.</p>
		<ul class="cd-buttons" style="width: 800px">
			<li><a href="#" onClick="nao();" style="font-weight: bold;background-color:#b6bece">Fechar</a></li>
		</ul>
		<a onClick="nao();" class="cd-popup-close img-replace">Close</a>
	</div> 
</div> 


    <script src="{{ asset('/public/materialize/js/vendors.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>

      
@if(Session::has('message'))
  <script type="text/javascript">
    $('#alerta').addClass('is-visible');
  </script>
@endif()

<script>
function envia() {

  if ($('#dado').val().length != 0){
 
    //Deleta os localstorage atual
    window.localStorage.removeItem('cpf');
    window.localStorage.removeItem('email');

    $("#loadingdiv").show();
    $("#corpodiv").hide();
    document.getElementById("form").submit();
    $("#btnsubmit").prop("disabled",false);


  } else {
    $('#alertacamposfaltantes').addClass('is-visible');
    $("#btnsubmit").removeAttr("disabled");

  }



}    
</script>


<script>
 function nao() {
  $('.cd-popup').removeClass('is-visible');
 }
</script>

  </body>
</html>