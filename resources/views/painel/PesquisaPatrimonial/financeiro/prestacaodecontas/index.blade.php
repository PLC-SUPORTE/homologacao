@extends('painel.Layout.header')
@section('title') Prestação de contas index @endsection <!-- Titulo da pagina -->

@section('header') 
	<link rel="shortcut icon" href="{{ asset('/public/assets/imgs/casinha.png')}}" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('/public/materialize/css/materialize.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/page-contact.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/select2-materialize.css') }}">


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
@endsection
@section('header_title')
Prestação de contas
@endsection
@section('submenu')

@endsection
@section('body')
    <div>
      <div class="row">
        <div class="content-wrapper-before blue-grey lighten-5"></div>
        <div class="col s12">
          <div class="container">


<div id="contact-us" class="section">
  <div class="app-wrapper">
    

    <div id="sidebar-list" class="row contact-sidenav ml-0 mr-0">
     
      <div class="col s12 m12 l12 contact-form margin-top-contact">
        <div class="row">

        <form class="col s12" role="form" id="form" action="{{ route('Painel.PesquisaPatrimonial.financeiro.prestacaodecontas') }}" method="POST" role="search" enctype="multipart/form-data" >
       {{ csrf_field() }}

       <div id="loadingdiv" style="display:none;margin-top: 100px; margin-left: 570px;">
       <img style="width: 30px; margin-top: -100px;" src="{{URL::asset('/public/imgs/loading.gif')}}"/>
       <h6 style="font-size: 20px;margin-left:-140px;">Aguarde, estamos montando o relatório..</h6>
       </div>

           <div id="div_all">
            <div class="row">

              <div class="input-field col m3 s12">
                <span style="font-size: 11px;">Selecione o grupo cliente:</span>
                <select class="select2 browser-default" id="grupocliente" name="grupocliente" style="font-size:10px;">
                <option value="" selected>Selecione abaixo</option>
                @foreach($gruposcliente as $data) 
                <option value="{{ $data->id_referencia }}">{{ $data->descricao }}</option>
                @endforeach
                </select>
              </div>

              <div class="input-field col m4 s12">
              <span style="font-size: 11px;">Selecione o cliente:</span>
                <select class="select2 browser-default" id="cliente" name="cliente" style="font-size:10px;">
                <option value="" selected>Selecione abaixo</option>
                </select>
              </div>

              <div class="input-field col m2 s12">
              <span style="font-size: 11px;">Informe a data ínicio:</span>
                <input style="font-size: 10px;" type="date" required name="datainicio" id="datainicio" value="{{$datahoje}}" class="validate" max="{{$datahoje}}">
              </div>

              <div class="input-field col m2 s12">
              <span style="font-size: 11px;">Informe a data fim:</span>
                <input style="font-size: 10px;" type="date" required name="datafim" id="datafim" value="{{$datahoje}}" class="validate" max="{{$datahoje}}">
              </div>

            </div>
  
          <div class="row">
          <div class="input-field col s12 width-100" >
          <label class="control-label" style="font-size: 11px;">Observação:</label>
          <textarea id="observacao" rows="3" type="text" name="observacao" class="form-control" placeholder="Insira a observação abaixo." style="height: 4rem;text-align:left; overflow:auto;font-size: 10px;"></textarea>
          <br>          <br>

          <a onClick="confirmainformacoes();" class="waves-effect waves-light  btn border-round" style="background-color: gray;font-size:11px;"><i class="material-icons left">search</i> Montar relatório</a>
          </div>   
          </div>  

            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="alertacamposfaltantes" name="alertacamposfaltantes" class="cd-popup" role="alert">
	<div class="cd-popup-container">
		<p style="font-weight: bold;">Selecione o grupo e cliente para busca das informações.</p>
		<ul class="cd-buttons" style="width: 800px">
			<li><a href="#" onClick="nao();" style="font-weight: bold;background-color:#b6bece">OK</a></li>
		</ul>
		<a onClick="nao();" class="cd-popup-close img-replace">Close</a>
	</div> 
</div> 

<div id="alertaconfirmacao" name="alertaconfirmacao" class="cd-popup" role="alert">
	<div class="cd-popup-container">
		<p style="font-weight: bold;">Deseja montar o relatório de prestação de conta para este cliente?</p>
		<ul class="cd-buttons">
			<li><a href="#" onClick="nao();" style="font-weight: bold;">Não</a></li>
			<li><a href="#" onClick="sim();" style="font-weight: bold;">Sim</a></li>
		</ul>
		<a onClick="nao();" class="cd-popup-close img-replace">Close</a>
	</div> 
</div>

@endsection
@section('scripts')
    
    <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/select2.full.min.js') }}"></script>

<script>
$(".select2").select2({
    dropdownAutoWidth: true,
    width: '100%'
});
</script>

<script>
$('#grupocliente').on('change', function() {

  var grupocliente = $("#grupocliente").val();
  var _token = $('input[name="_token"]').val();


    $.ajax({
      type: 'POST',
      url:"{{ route('Painel.PesquisaPatrimonial.financeiro.prestacaodecontas.buscacliente') }}",
      data:{grupocliente:grupocliente,_token:_token,},
      dataType: 'json',
      cache: false,
      success: function(response) {

        $('#cliente option:not(:selected)').remove();

        var selectbox = $('#cliente');
          $.each(response, function (i, d) {
              selectbox.append('<option value="' + d.Codigo+ '">' + d.Razao + '</option>');
        });


    }

  });

});
</script>

<script>
function confirmainformacoes() {

  var grupocliente = $('#grupocliente').val();
  var cliente = $('#cliente').val();

  if (grupocliente != "" || cliente != ""){
      $('#alertaconfirmacao').addClass('is-visible');
  } else {
    $('#alertacamposfaltantes').addClass('is-visible');
   }
}
</script>


<script>
  function sim() {
    document.getElementById("loadingdiv").style.display = "";
    document.getElementById("div_all").style.display = "none";
    $('.cd-popup').removeClass('is-visible');
    document.getElementById("form").submit();
  }    
</script>

<script>
 function nao() {
  $('.cd-popup').removeClass('is-visible');
 }
</script>
@endsection