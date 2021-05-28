
@extends('painel.Layout.header')
<?php $search = 2 ?>
@section('title') Revisar solicitação de guia de custa @endsection <!-- Titulo da pagina -->

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
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/app-file-manager.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/widget-timeline.min.css') }}">

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
Solicitação de pagamento de guias de custas processuais
@endsection

@section('submenu')
<li class="breadcrumb-item"><a href="{{ route('Painel.Financeiro.GuiasCustas.Revisao.index') }}">Solicitações em andamento</a>
</li>
<li class="breadcrumb-item active" style="color: black;">Revisão de solicitação
</li>
@endsection

@section('body')
   <div>

<div id="loadingdiv" style="display:none;margin-top: 200px; margin-left: 570px;">
<img style="width: 30px; margin-top: -100px;" src="{{URL::asset('/public/imgs/loading.gif')}}"/>
<h6 style="font-size: 20px;margin-left:-170px;">Aguarde, estamos atualizando a solicitação...</h6>
</div>

  <div class="row" id="div_all">

        <div class="content-wrapper-before blue-grey lighten-5"></div>
        <div class="col s12">
          <div class="container">
			
<section class="invoice-edit-wrapper section">


<form role="form" id="form" action="{{ route('Painel.Financeiro.GuiasCustas.Revisao.revisado') }}" method="POST" role="search" enctype="multipart/form-data" >
  {{ csrf_field() }}

  <input type="hidden" name="pasta" id="pasta" value="{{$datas->Pasta}}">
  <input type="hidden" name="numeroprocesso" id="numeroprocesso" value="{{$datas->NumeroProcesso}}">
  <input type="hidden" name="setor" id="setor" value="{{$datas->SetorCodigo}}">
  <input type="hidden" name="unidade" id="unidade" value="{{$datas->UnidadeCodigo}}">
  <input type="hidden" name="cliente" id="cliente" value="{{$datas->ClienteCodigo}}">
  <input type="hidden" name="numerodebite" id="numerodebite" value="{{$datas->NumeroDebite}}">
  <input type="hidden" name="solicitanteemail" id="solicitanteemail" value="{{$datas->SolicitanteEmail}}">
  <input type="hidden" name="solicitantecpf" id="solicitantecpf" value="{{$datas->SolicitanteCPF}}">
  <input type="hidden" name="solicitanteid" id="solicitanteid" value="{{$datas->SolicitanteID}}">
  <input type="hidden" name="contratocodigo" id="contratocodigo" value="{{$datas->ContratoCodigo}}">


    <!--Inicio Modal Anexos --> 
<div id="anexos{{$datas->NumeroDebite}}" class="modal modal-fixed-footer" style="width: 100%;height:100%;overflow:hidden;">

<button type="button" class="btn waves-effect mr-sm-1 mr-2 modal-close red" style="margin-left: 1255px; margin-top: 5px;">
  <i class="material-icons">close</i> 
</button>

<iframe style=" position:absolute;
top:60;
left:0;
width:100%;
height:100%;" src="{{ route('Painel.Financeiro.GuiasCustas.anexos', $datas->NumeroDebite) }}"></iframe>

</div>
<!--Fim Modal Anexos --> 


  <div class="row"  style="padding: 5px;">

    <div class="col xl9 m8 s12">
      <div class="card">
      <a href="#anexos{{$datas->NumeroDebite}}" class="btn-floating btn-mini waves-effect waves-light tooltipped modal-trigger"data-position="left" data-tooltip="Clique aqui para visualizar os anexos."  style="margin-left: 885px;margin-top:-10px;background-color: gray;"><i class="material-icons">attach_file</i></a>

        <div class="card-content px-36">

          <div class="col m4 s4">
            <div class="divider show-on-small hide-on-med-and-up mb-3"></div>
            <h6 class="invoice-to">Dados da pasta</h6>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">Código da pasta:  {{$datas->Pasta}}</p>
            </div>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">Descrição da pasta: <?php echo mb_convert_case($datas->Descricao, MB_CASE_TITLE, "UTF-8")?></p>
            </div>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">Número do processo: {{$datas->NumeroProcesso}}</p>
            </div>

            <div class="invoice-address" style="font-size: 10px;"> 
            <p style="font-weight: bold;color:black;">Unidade: {{$datas->UnidadeDescricao}}</p>
            </div>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">Setor de custo: {{$datas->SetorDescricao}}</p>
            </div>

            <div class="invoice-address" style="font-size: 10px;"> 
            <p style="font-weight: bold;color:black;">Contrato: {{$datas->ContratoCodigo}}</p>
            </div>

            <div class="invoice-address" style="font-size: 10px;"> 
              @if($datas->autorizadocliente == "Sim")
              <p style="font-weight: bold;color:green;">Autorizado pelo cliente: {{$datas->autorizadocliente}}</p>
              @else 
              <p style="font-weight: bold;color:red;">Autorizado pelo cliente: {{$datas->autorizadocliente}}</p>
              @endif
            </div>

            <div class="invoice-address" style="font-size: 10px;"> 
              @if($datas->status_cobravel == "Reembolsável pelo cliente")
              <p style="font-weight: bold;color:green;">Status do contrato: {{$datas->status_cobravel}}</p>
              @else 
              <p style="font-weight: bold;color:red;">Status do contrato: {{$datas->status_cobravel}}</p>
              @endif
            </div>
          </div>

          <div class="col m4 s4">
            <div class="divider show-on-small hide-on-med-and-up mb-3"></div>
            <h6 class="invoice-to">Dados do cliente</h6>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">Código: {{$datas->ClienteCodigo}}</p>
            </div>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">Razão Social: <?php echo mb_convert_case($datas->ClienteRazao, MB_CASE_TITLE, "UTF-8")?></p>
            </div>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">Grupo Cliente: <?php echo mb_convert_case($datas->Grupo, MB_CASE_TITLE, "UTF-8")?></p>
            </div>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">Conta Identificadora: </p>
            </div>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">Moeda: R$ </p>
            </div>

          </div>

           <div class="row invoice-info">
            <div class="col m4 s4">
              <h6 class="invoice-from">Dados do solicitante</h6>
              <div class="invoice-address" style="font-size: 10px;">
              <p style="font-weight: bold;color:black;">Nome: <?php echo mb_convert_case($datas->SolicitanteNome, MB_CASE_TITLE, "UTF-8")?></p>
              </div>

              <div class="invoice-address" style="font-size: 10px;">
              <p style="font-weight: bold;color:black;">E-mail: {{$datas->SolicitanteEmail}}</p>
              </div>

              <div class="invoice-address" style="font-size: 10px;">
              <p style="font-weight: bold;color:black;">CPF: {{$datas->SolicitanteCPF}}</p>
              </div>

            </div>
          </div>
          

    

          <div class="divider mb-3 mt-3"></div>

          <div class="invoice-item display-flex mb-1">
            <div class="invoice-item-filed row pt-1">

            <div class="col m3 s12 input-field">
            <span style="font-size: 11px;">Data:</span>
            <input style="font-size: 10px;"  readonly value="{{ date('d/m/Y', strtotime($datas->DataServico)) }}" type="text" class="form-control"  required="required">
            <input style="font-size: 10px;"  readonly name="data" id="data" value="{{$datas->DataServico}}" type="hidden" class="form-control"  required="required">
            </div>

            <div class="col s12 m4 input-field">
            <span style="font-size: 11px;">Tipo de debite:</span>
            <input style="font-size: 10px;"  readonly name="tiposervico" id="tiposervico" value="{{$datas->TipoDebite}}" type="text" class="form-control"  required="required">
            </div>

            <div class="col m3 s12 input-field">
              <span style="font-size: 11px;">Valor total:</span>
              <input type="text" readonly value="<?php echo number_format($datas->Valor,2,",",".") ?>" name="valor" id="valor" onKeyPress="return(moeda2(this,'.',',',event))" pattern="(?:\.|,|[0-9])*" style="font-size:10px;"required >
            </div>

            </div>
                
            </div>			  

          <div class="divider mt-3 mb-3"></div>

          <div class="invoice-subtotal">

          <div class="row">
          <div class="input-field col s12" style="margin-top: -15px;">
          <div class="form-group">
          <div class="form-group">
          <label class="control-label" style="font-size: 11px;">Observação:</label>
          <textarea readonly id="observacao" rows="3" type="text" name="observacao" class="form-control" placeholder="Insira a observação abaixo." style="height: 5rem;text-align:left; overflow:auto;font-size: 10px;">{{$datas->Observacao}}</textarea>
          </div>
          </div>
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

        <p>
        <label>
        <input style="font-size: 11px;" name="statusescolhido" class="with-gap" value="aprovar" checked type="radio" checked onClick="fecharjustificativa();"/>
        <span>Aprovar solicitação</span>
        </label> 
        </p>

        <p>
        <label>
        <input style="font-size: 11px;" class="with-gap"  name="statusescolhido" value="cancelar" type="radio" onClick="abrirjustificativa();" />
        <span>Cancelar solicitação</span>
        </label>
        </p>

        <p>
        <label>
        <input style="font-size: 11px;" name="statusescolhido" class="with-gap" value="reprovar" type="radio" onClick="abrirjustificativa();"/>
        <span>Reprovar solicitação</span>
        </label>
        </p>
    
        <!--Cancelar/Reprovar solicitação --> 
        <div class="row" style="font-size:10px;display:none;" id="justificativadiv">
          <div class="input-field col m12 s12">
          <span style="font-size: 10px;">Informe o motivo:</span>
          <div class="form-group bmd-form-group is-filled">
          <select class="browser-default" style="width: 100%;font-size:10px;" id="motivo" name="motivo"  data-toggle="tooltip" data-placement="top" title="Selecione o motivo da reprovação/cancelamento da solicitação de reembolso.">
          @foreach($motivos as $motivo)
          <option value="{{$motivo->id}}">{{$motivo->descricao}}</option>
          @endforeach
          </select>
          </div>
        </div>

        <div class="input-field col m12 s12">
        <span style="font-size: 10px;">Observação:</span>
          <div class="form-group bmd-form-group is-filled">
          <textarea id="motivodescricao" rows="3" type="text" name="motivodescricao" class="form-control" placeholder="Insira a observação abaixo." style="height: 4rem;text-align:left; overflow:auto;font-size: 10px;"></textarea>
          </div>
        </div>

        </div>
        <!--Fim Cancelar/Reprovar solicitação --> 


        <br>

        <div class="invoice-action-btn">
            <a onClick="abreconfirmacao();" style="background-color: gray;color:white;font-size:11px;" class="btn waves-effect waves-light display-flex align-items-center justify-content-center">
              <i class="material-icons mr-3">save</i>
              <span class="responsive-text" id="btngravar">Aprovar solicitação</span>
            </a>
        </div> 

        </div>
        </div>
        </div>

  

      </div>


<div id="aprovar" class="cd-popup" role="alert">
	<div class="cd-popup-container">
		<p style="font-weight: bold;">Deseja aprovar a solicitação de pagamento de guia de custa?</p>
		<ul class="cd-buttons">
			<li><a href="#" onClick="nao();" style="font-weight: bold;">Não</a></li>
			<li><a href="#" onClick="sim();" style="font-weight: bold;">Sim</a></li>
		</ul>
		<a onClick="nao();" class="cd-popup-close img-replace">Close</a>
	</div> 
</div> 


<div id="reprovar" class="cd-popup" role="alert">
	<div class="cd-popup-container">
		<p style="font-weight: bold;">Deseja reprovar a solicitação de pagamento de guia de custa?</p>
		<ul class="cd-buttons">
    <li><a href="#" onClick="nao();" style="font-weight: bold;">Não</a></li>
			<li><a href="#" onClick="sim();" style="font-weight: bold;">Sim</a></li>
		</ul>
		<a onClick="nao();" class="cd-popup-close img-replace">Close</a>
	</div> 
</div> 

<div id="cancelar" class="cd-popup" role="alert">
	<div class="cd-popup-container">
		<p style="font-weight: bold;">Deseja cancelar a solicitação de pagamento de guia de custa?</p>
		<ul class="cd-buttons">
    <li><a href="#" onClick="nao();" style="font-weight: bold;">Não</a></li>
			<li><a href="#" onClick="sim();" style="font-weight: bold;">Sim</a></li>
		</ul>
		<a onClick="nao();" class="cd-popup-close img-replace">Close</a>
	</div> 
</div> 




<div class="input-field col s12" style="display: none">
                    <textarea id="hist" rows="4" type="text" name="hist" readonly="" class="form-control" style="text-align: left;margin: 0;" placeholder="Hist debite">
{{$datas->Hist}}
Número da solicitação: {{$datas->NumeroDebite}}. Solicitação de pagamento de guia de custa revisada pelo(a): {{Auth::user()->name}} pela revisão técnica - {{$dataehora}}</textarea>
              </div> 
              
              <div class="input-field col s12" style="display: none">
                    <textarea id="histreprovada" rows="4" type="text" name="histreprovada" readonly="" class="form-control" style="text-align: left;margin: 0;" placeholder="Hist debite">
{{$datas->Hist}}
Número da solicitação: {{$datas->NumeroDebite}}. Solicitação de pagamento de guia de custa reprovada pelo(a): {{Auth::user()->name}} pela revisão técnica - {{$dataehora}} com o motivo: </textarea>
              </div> 

              
              <div class="input-field col s12" style="display: none">
                    <textarea id="histcancelada" rows="4" type="text" name="histcancelada" readonly="" class="form-control" style="text-align: left;margin: 0;" placeholder="Hist debite">
{{$datas->Hist}}
Número da solicitação: {{$datas->NumeroDebite}}. Solicitação de pagamento de guia de custa cancelada pelo(a): {{Auth::user()->name}} pela revisão técnica  - {{$dataehora}} com o motivo: </textarea>
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

<script>
document.addEventListener("DOMContentLoaded", function () {
   $('.modal').modal();
});
</script>

 <script>
function abreconfirmacao() {

  var status = document.querySelector('input[name="statusescolhido"]:checked').value;
  var justificativa = $("#motivodescricao").val(); 

  if(status == "aprovar") {
    $('#aprovar').addClass('is-visible');
  }else if(status == "reprovar") {
    //Verifica se já preencheou a justificativa
    if(justificativa == '') {
      alert('Favor preencher o campo observação informando o motivo da reprovação.');
    } else {
      $('#reprovar').addClass('is-visible');
    }

  }else {
    //Verifica se já preencheou a justificativa
    if(justificativa == '') {
      alert('Favor preencher o campo observação informando o motivo do cancelamento.');
    } else {
      $('#cancelar').addClass('is-visible');
    }
  }
}
</script>

<script>
  function sim() {
    $('.modal').css('display', 'none');
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

<script>
function fecharjustificativa() {
    document.getElementById("justificativadiv").style.display = "none";         
  }    
</script>


<script>
function abrirjustificativa() {
    document.getElementById("justificativadiv").style.display = "";    
  }    
</script>

<script>
  $(document).on("change", "input[name=statusescolhido]", function() {

  var status = $(this).val();

  //Altera o label do botão conforme a opção marcada
   if(status == "aprovar") {
    $('#btngravar').html('Aprovar solicitação');
  }
    else if(status == "reprovar") {
    $('#btngravar').html('Reprovar solicitação');
  } else {
    $('#btngravar').html('Cancelar solicitação');
  }
  
});
</script>

@endsection