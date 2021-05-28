
@extends('painel.Layout.header')
<?php $search = 2 ?>
@section('title') Novo adiantamento @endsection <!-- Titulo da pagina -->

@section('header') 
	<link rel="shortcut icon" href="{{ asset('/public/assets/imgs/casinha.png')}}" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/responsive.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/materialize.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/data-tables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/app-invoice.min.css') }}">
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
  Adiantamento/Prestação de conta
  @endsection

  @section('submenu')
  <li class="breadcrumb-item"><a href="{{ route('Painel.Financeiro.AdiantamentoPrestacao.Financeiro.index') }}">Solicitações em andamento</a>
  </li>
  <li class="breadcrumb-item active" style="color: black;">Nova solicitação
  </li>
  @endsection

  @section('body')
    <div>
      <div class="row">
        <div class="content-wrapper-before blue-grey lighten-5"></div>
        <div class="col s12">
          <div class="container">
			
<section class="invoice-edit-wrapper section">

<form role="form" id="form" action="{{ route('Painel.Financeiro.AdiantamentoPrestacao.Financeiro.novoadiantamento_store') }}" method="POST" role="search" enctype="multipart/form-data" >
  {{ csrf_field() }}

  <input type="hidden" name="setor" id="setor" value="{{$datas->SetorCodigo}}">
  <input type="hidden" name="unidade" id="unidade" value="{{$datas->UnidadeCodigo}}">
  <input type="hidden" name="usuario_cpf" id="usuario_cpf" value="{{$datas->usuario_cpf}}">
  <input type="hidden" name="moeda" id="moeda" value="{{$datas->Moeda}}">
  <input type="hidden" name="usuario_id" id="usuario_id" value="{{$datas->usuario_id}}">

  <div id="loadingdiv2" style="display:none;margin-top: 300px; margin-left: 570px;">
    <img style="width: 30px; margin-top: -100px;" src="{{URL::asset('/public/imgs/loading.gif')}}"/>
    <h6 style="font-size: 20px;margin-left:-160px;">Aguarde, estamos enviando este novo adiantamento...</h6>
  </div>

  <div class="row" id="div_all">

    <div class="col xl9 m8 s12">
      <div class="card">
        <div class="card-content px-36">

          <div class="col m6 s6">
            <div class="divider show-on-small hide-on-med-and-up mb-3"></div>
            <h6 class="invoice-to">Dados do solicitante</h6>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">Nome:  <?php echo mb_convert_case($datas->usuario_nome, MB_CASE_TITLE, "UTF-8")?></p>
            </div>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">E-mail: {{$datas->usuario_email}}</p>
            </div>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">CPF: {{$datas->usuario_cpf}}</p>
            </div>

            <div class="invoice-address" style="font-size: 10px;"> 
            <p style="font-weight: bold;color:black;">Unidade: {{$datas->UnidadeDescricao}}</p>
            </div>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">Setor de custo: {{$datas->SetorDescricao}}</p>
            </div>

          </div>

          <div class="col m6 s6">
            <div class="divider show-on-small hide-on-med-and-up mb-3"></div>
            <h6 class="invoice-to">Dados bancários</h6>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">Banco: {{$datas->BancoDescricao}}</p>
            </div>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">Agência: {{$datas->Agencia}}</p>
            </div>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">Conta: {{$datas->Conta}} </p>
            </div>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">Moeda: {{$datas->Moeda}}</p>
            </div>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">Saldo atual: R$ <?php echo number_format($saldo,2,",",".") ?> </p>
            </div>

          </div>

          <br><br><br><br>
          <div class="divider mt-3 mb-3"></div>

      <div class="row">
      <div class="input-field col s12">
        <span style="font-size: 11px">Informe o motivo do adiantamento:</span>
          <select class="select2 browser-default" style="font-size: 10px;" name="motivo" id="motivo" required>
            <option value="" selected>Selecione abaixo</option>
            @foreach($motivos as $motivo)   
            <option value="{{$motivo->id}}">{{$motivo->descricao}}</option>
            @endforeach
          </select>
      </div>


      <div class="input-field col s12" id="umclientediv">
      <span style="font-size:11px">Informe o valor:</span>
      <input type="text" value="00,00" id="valor" name="valor" placeholder="Valor da transferência..." onKeyPress="return(moeda2(this,'.',',',event))" pattern="(?:\.|,|[0-9])*" style="font-size:10px;"required >
      </div>

      <div class="input-field col s12">
        <span style="font-size: 11px">Selecione o banco de saída:</span>
        <select class="invoice-item-select browser-default"  id="portador" name="portador" style="font-size:10px;">
              <option value="" selected>Selecione abaixo</option>
              @foreach($bancos as $banco)   
              <option value="{{$banco->Codigo}}">{{$banco->Descricao}}</option>
              @endforeach
        </select>
      </div>


      </div>

      <div class="divider mt-3 mb-3"></div>

      <div class="invoice-subtotal">

          <div class="row">
          <div class="input-field col s12">
          <div class="form-group">
          <div class="form-group">
          <label class="control-label" style="font-size: 11px;">Informações:</label>
          <textarea id="observacao" readonly rows="3" type="text" name="observacao" class="form-control" placeholder="Insira a observação abaixo." style="height: 3rem;text-align:left; overflow:auto;font-size: 10px;"></textarea>
          </div>
          </div>
          </div>   
          </div>  

          <div class="row">
          <div class="input-field col s12" style="margin-top: -15px;">
          <div class="form-group">
          <div class="form-group">
          <label class="control-label" style="font-size: 11px;">Informações adicionais:</label>
          <textarea id="observacaoadicionais" rows="3" type="text" name="observacaoadicionais" class="form-control" placeholder="Insira uma informação adicional se necessário." style="height: 3rem;text-align:left; overflow:auto;font-size: 10px;"></textarea>
          </div>
          </div>
          </div>   
          </div>  

          <div class="row">
          <div class="col s12 m12 l12">
            <span style="font-size: 10px;">Selecione o arquivo ou arquivos que você deseja anexar:</span>
            <br><br>
             <input style="font-size: 10px;"  type="file" id="select_file"  name="select_file" accept=".jpg,.png,.xls,.xlsx,.pdf,.doc,.docx,.rtf,.jpeg"  class="dropify" data-default-file="" />
          </div>
          </div>


          </div>

        </div>
      </div>
    </div>
    <!-- invoice action  -->


    <div class="col xl3 m4 s12">
      <div class="card invoice-action-wrapper mb-10">
        <div class="card-content">

          <div class="invoice-action-btn">
            <a id="btnsubmit" onClick="abremodalconfirmacao();" style="background-color: gray;color:white;font-size:11px;" class="btn waves-effect waves-light display-flex align-items-center justify-content-center">
              <i class="material-icons mr-4">save</i>
              <span class="responsive-text">Enviar adiantamento</span>
            </a>
          </div>

        </div>
      </div>
  </div>

 

  </form>
</section>

@endsection

@section('scripts')
    <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/app-invoice.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>
    <script src="{{ asset('/public/materialize/js/select2.full.min.js') }}"></script>
      <script src="{{ asset('/public/materialize/js/form-select2.min.js') }}"></script>


<script language="javascript">   
function moeda2(a, e, r, t) {
    let n = ""
      , h = j = 0
      , u = tamanho2 = 0
      , l = ajd2 = ""
      , o = window.Event ? t.which : t.keyCode;
    if (13 == o || 8 == o)
        return !0;
    if (n = String.fromCharCode(o),
    -1 == "0123456789".indexOf(n))
        return !1;
    for (u = a.value.length,
    h = 0; h < u && ("0" == a.value.charAt(h) || a.value.charAt(h) == r); h++)
        ;
    for (l = ""; h < u; h++)
        -1 != "0123456789".indexOf(a.value.charAt(h)) && (l += a.value.charAt(h));
    if (l += n,
    0 == (u = l.length) && (a.value = ""),
    1 == u && (a.value = "0" + r + "0" + l),
    2 == u && (a.value = "0" + r + l),
    u > 2) {
        for (ajd2 = "",
        j = 0,
        h = u - 3; h >= 0; h--)
            3 == j && (ajd2 += e,
            j = 0),
            ajd2 += l.charAt(h),
            j++;
        for (a.value = "",
        tamanho2 = ajd2.length,
        h = tamanho2 - 1; h >= 0; h--)
            a.value += ajd2.charAt(h);
        a.value += r + l.substr(u - 2, u)
    }
    return !1
}
 </script>  

<div id="alertaconfirmacao" name="alertaconfirmacao" class="cd-popup" role="alert">
	<div class="cd-popup-container">
		<p style="font-weight: bold;">Deseja enviar um novo adiantamento?</p>
		<ul class="cd-buttons">
			<li><a href="#" onClick="nao();" style="font-weight: bold;">Não</a></li>
			<li><a href="#" onClick="sim();" style="font-weight: bold;">Sim</a></li>
		</ul>
		<a onClick="nao();" class="cd-popup-close img-replace">Close</a>
	</div> 
</div>


<div id="alertacampoinformacoesadicionais" name="alertacampoinformacoesadicionais" class="cd-popup" role="alert">
	<div class="cd-popup-container">
		<p style="font-weight: bold;">Favor informar no campo informações adicionais o motivo do adiantamento.</p>
		<ul class="cd-buttons" style="width: 800px">
			<li><a href="#" onClick="nao();" style="font-weight: bold;background-color:#b6bece">OK</a></li>
		</ul>
		<a onClick="nao();" class="cd-popup-close img-replace">Close</a>
	</div> 
</div> 


<script>
function abremodalconfirmacao() {
  $('#alertaconfirmacao').addClass('is-visible');
}
</script>



<script>
  function sim() {
    $('.modal').css('display', 'none');
    document.getElementById("loadingdiv2").style.display = "";
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


<script>
$('#valor').blur(function(){
    var valor = parseFloat($("#valor").val().replace('.', '').replace(',','.'));
    var tiposervicodescricao = $('#tiposervico option:selected').text();
    var motivo_descricao  = $('#motivo option:selected').text();
    var informacoesadicionais = $("#observacaoadicionais").val();

   document.getElementById('observacao').value=(' Solicitação de adiantamento realizada no portal pelo(a): ' + "{{$datas->usuario_nome}}" + ' com o motivo: ' + motivo_descricao + ' ' + informacoesadicionais +  ' no valor total de R$ ' + valor);
});
</script>

<script>
$("#motivo").change(function(){

    var motivo = $("#motivo").val();

    if(motivo == 1) {
      $('#alertacampoinformacoesadicionais').addClass('is-visible');
    }

  });
</script>


@endsection