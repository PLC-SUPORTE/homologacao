
@extends('painel.Layout.header')
<?php $search = 2 ?>
@section('title') Revisar solicitação de prestação de conta @endsection <!-- Titulo da pagina -->

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
<li class="breadcrumb-item"><a href="{{ route('Painel.Financeiro.AdiantamentoPrestacao.Financeiro.index') }}">Saldo por usuário</a>
</li>
<li class="breadcrumb-item active" style="color: black;">Revisão de solicitação de prestação de conta
</li>
@endsection

@section('body')
   <div>

  <div id="loadingdiv" style="display:none;margin-top: 200px; margin-left: 570px;">
  <img style="width: 30px; margin-top: -100px;" src="{{URL::asset('/public/imgs/loading.gif')}}"/>
  <h6 style="font-size: 20px;margin-left:-170px;">Aguarde, estamos atualizando a prestação de conta...</h6>
  </div>

  <div class="row" id="div_all">

        <div class="content-wrapper-before blue-grey lighten-5"></div>
        <div class="col s12">
          <div class="container">
			
<section class="invoice-edit-wrapper section">


<form role="form" id="form" action="{{ route('Painel.Financeiro.AdiantamentoPrestacao.Financeiro.revisarprestacaodeconta_store') }}" method="POST" role="search" enctype="multipart/form-data" >
  {{ csrf_field() }}

  <input type="hidden" name="solicitanteemail" id="solicitanteemail" value="{{$datas->solicitante_email}}">
  <input type="hidden" name="solicitantecpf" id="solicitantecpf" value="{{$datas->solicitante_cpf}}">
  <input type="hidden" name="solicitanteid" id="solicitanteid" value="{{$datas->solicitante_id}}">
  <input type="hidden" name="tiposervicodescricao" id="tiposervicodescricao" value="">
  <input type="hidden" name="id_matrix" id="id" value="{{$datas->id}}">
  <input type="hidden" name="valor_pendente" id="valor_pendente" value="<?php echo number_format($datas->valor_atual,2,",",".") ?>">
  <input type="hidden" name="numdocor" id="numdocor" value="{{$datas->Numdoc}}">
  <input type="hidden" name="usuario_cpf" id="usuario_cpf" value="{{$datas->usuario_cpf}}">
  <input type="hidden" name="valor_prestado" id="valor_prestado" value="<?php echo number_format($datas->valor_prestado,2,",",".") ?>">
  <input type="hidden" name="comprovantedevolucao" id="comprovantedevolucao" value="{{$datas->anexo_comprovantedevolucao}}">
  
    <!--Inicio Modal Anexos --> 
<div id="anexos{{$datas->id}}" class="modal modal-fixed-footer" style="width: 100%;height:100%;overflow:hidden;">

<button type="button" class="btn waves-effect mr-sm-1 mr-2 modal-close red" style="margin-left: 1255px; margin-top: 5px;">
  <i class="material-icons">close</i> 
</button>

<iframe style=" position:absolute;
top:60;
left:0;
width:100%;
height:100%;" src="{{ route('Painel.Financeiro.AdiantamentoPrestacao.anexos', $datas->id) }}"></iframe>

</div>
<!--Fim Modal Anexos --> 


  <div class="row"  style="padding: 5px;">

    <div class="col xl9 m8 s12">
      <div class="card">
      <a href="#anexos{{$datas->id}}" class="btn-floating btn-mini waves-effect waves-light tooltipped modal-trigger"data-position="left" data-tooltip="Clique aqui para visualizar os anexos."  style="margin-left: 885px;margin-top:-10px;background-color: gray;"><i class="material-icons">attach_file</i></a>

        <div class="card-content px-36">

        <div class="col m4 s12">
            <div class="divider show-on-small hide-on-med-and-up mb-3"></div>
            <h6 class="invoice-to">Dados da solicitação</h6>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">Solicitante:  <?php echo mb_convert_case($datas->solicitante_nome, MB_CASE_TITLE, "UTF-8")?></p>
            </div>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">Data da solicitação: {{ date('d/m/Y', strtotime($datas->DataSolicitacao)) }}</p>
            </div>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">Data da transferência: {{ date('d/m/Y', strtotime($datas->DataPagamento)) }}</p>
            </div>

            <div class="invoice-address" style="font-size: 10px;"> 
            <p style="font-weight: bold;color:black;">Valor transferido: R$ <?php echo number_format($datas->valor_original,2,",",".") ?></p>
            </div>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">Valor já prestado conta: R$ <?php echo number_format($datas->valor_prestado,2,",",".") ?></p>
            </div>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">Valor pendente prestação de conta: R$ <?php echo number_format($datas->valor_atual,2,",",".") ?></p>
            </div>

          </div>


          <div class="col m4 s12">
            <div class="divider show-on-small hide-on-med-and-up mb-3"></div>
            <h6 class="invoice-to">Dados bancários da PL&C</h6>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">Código: {{$datas->CodigoBanco}}</p>
            </div>

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
            <p style="font-weight: bold;color:black;">Saldo atual: R$ <?php echo number_format($saldoplc,2,",",".") ?> </p>
            </div>

          </div>

          
          <div class="col m4 s12">
            <div class="divider show-on-small hide-on-med-and-up mb-3"></div>
            <h6 class="invoice-to">Dados bancários do solicitante</h6>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">CPF: {{$dadosusuario->CodigoBanco}}</p>
            </div>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">Banco: {{$dadosusuario->BancoDescricao}}</p>
            </div>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">Agência: {{$dadosusuario->Agencia}}</p>
            </div>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">Conta: {{$dadosusuario->Conta}} </p>
            </div>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">Moeda: {{$dadosusuario->Moeda}}</p>
            </div>

            <div class="invoice-address" style="font-size: 10px;">
            @if($saldo > 0)
            <p style="font-weight: bold;color:green;">Saldo atual: R$ <?php echo number_format($saldo,2,",",".") ?> </p>
            @else 
            <p style="font-weight: bold;color:black;">Saldo atual: R$ <?php echo number_format($saldo,2,",",".") ?> </p>
            @endif
            </div>

            <br>

          </div>


          <div class="divider mb-3 mt-3"></div>


          @foreach($debites as $debite)
          <div class="invoice-item display-flex mb-1">
            <div class="invoice-item-filed row pt-1">

            <div class="row">

            <input readonly type="hidden" name="numeroprocesso[]" id="numeroprocesso" value="{{$debite->NumeroProcesso}}">
            <input readonly name="unidade[]" id="unidade" value="{{$debite->UnidadeCodigo}}" type="hidden" class="form-control"  required="required">

            <div class="col m2 s12 input-field">
            <span style="font-size: 11px;">Debite:</span>
            <input style="font-size: 10px;"  readonly name="numerodebite[]" id="numerodebite" value="{{$debite->NumeroDebite}}" type="text" class="form-control"  required="required">
            </div>

            <div class="col m2 s12 input-field">
            <span style="font-size: 11px;">Pasta:</span>
            <input style="font-size: 10px;"  readonly name="pasta[]" id="pasta" value="{{$debite->Pasta}}" type="text" class="form-control"  required="required">
            </div>

            <div class="col m2 s12 input-field">
            <span style="font-size: 11px;">Setor:</span>
            <input style="font-size: 10px;"  readonly value="{{$debite->SetorDescricao}}" type="text" class="form-control"  required="required">
            <input style="font-size: 10px;"  readonly name="setor[]" id="setor"  value="{{$debite->SetorCodigo}}" type="hidden" class="form-control"  required="required">
            </div>


            <div class="col m2 s12 input-field">
            <span style="font-size: 11px;">Data do debite:</span>
            <input style="font-size: 10px;"  readonly value="{{ date('d/m/Y', strtotime($debite->Data)) }}" type="text" class="form-control"  required="required">
            <input style="font-size: 10px;"  name="data[]" id="data" value="{{$debite->Data}}" type="hidden" class="form-control"  required="required">
            </div>

            <div class="col m3 s12 input-field">
            <span style="font-size: 11px;">Selecione se é reembolsável ou não:</span>
            <select class="invoice-item-select browser-default" required style="font-size: 10px;" name="statusreembolso[]" id="statusreembolso">
            <option value="" selected>Selecione abaixo</option>
            @if($debite->status_cobravel == "Sim")
            <option value="Sim" selected>Sim</option>
            <option value="Não">Não</option>
            @else 
            <option value="Sim">Sim</option>
            <option value="Não" selected>Não</option>
            @endif
            </select>
            </div>

            </div>

            <div class="row">

            <div class="col s12 m3 input-field">
            <span style="font-size: 11px;">Tipo de debite:</span>
            <input style="font-size: 10px;"  readonly name="tipodebite[]" id="tipodebite" value="{{$debite->TipoDebite}}" type="text" class="form-control"  required="required">
            </div>

            <div class="col m2 s12 input-field">
              <span style="font-size: 11px;">Valor total:</span>
              <input type="text" readonly value="<?php echo number_format($debite->Valor,2,",",".") ?>" name="valor[]" id="valor" style="font-size:10px;"required >
            </div>

            </div>

            <div class="row">
          <div class="input-field col s12" style="margin-top: -15px;">
          <div class="form-group">
          <div class="form-group">
          <label class="control-label" style="font-size: 11px;">Descrição do debite:</label>
          <textarea readonly rows="3" type="text" name="observacao[]" class="form-control" placeholder="Insira a observação abaixo." style="height: 5rem;text-align:left; overflow:auto;font-size: 10px;">{{$debite->Observacao}}</textarea>
          </div>
          </div>
          </div>   
          </div>  

            </div>
            </div>			


            <div class="input-field col s12" style="display: none">
                    <textarea id="hist" rows="4" type="text" name="hist[]" readonly="" class="form-control" style="text-align: left;margin: 0;" placeholder="Hist debite">
{{$debite->Hist}}
Número da solicitação: {{$debite->NumeroDebite}}. Prestação de conta no valor de: R$ <?php echo number_format($debite->Valor,2,",",".") ?> e  revisada pelo(a): {{Auth::user()->name}} pela equipe do financeiro - {{$dataehora}}</textarea>
              </div> 

              
              <div class="input-field col s12" style="display: none">
                    <textarea id="histreprovada" rows="4" type="text" name="histreprovada[]" readonly="" class="form-control" style="text-align: left;margin: 0;" placeholder="Hist debite">
{{$debite->Hist}}
Número da solicitação: {{$debite->NumeroDebite}}. Prestação de conta no valor de: R$ <?php echo number_format($debite->Valor,2,",",".") ?> foi reprovada pelo(a): {{Auth::user()->name}} pela equipe do financeiro - {{$dataehora}} com o motivo: </textarea>
              </div> 

              
              <div class="input-field col s12" style="display: none">
                    <textarea id="histcancelada" rows="4" type="text" name="histcancelada[]" readonly="" class="form-control" style="text-align: left;margin: 0;" placeholder="Hist debite">
{{$debite->Hist}}
Número da solicitação: {{$debite->NumeroDebite}}. Prestação de conta no valor de: R$ <?php echo number_format($debite->Valor,2,",",".") ?>  cancelada pelo(a): {{Auth::user()->name}} pela equipe do financeiro - {{$dataehora}} com o motivo: </textarea>
              </div> 

              <div class="input-field col s12" style="display: none">
                    <textarea id="observacao" rows="4" type="text" name="observacao[]" readonly="" class="form-control" style="text-align: left;margin: 0;" placeholder="Hist debite">{{$debite->Observacao}}</textarea>
              </div> 



            <br><br>
            @endforeach  

          <div class="divider mt-3 mb-3"></div>

          <div class="invoice-subtotal">

          <div class="row">
          <div class="input-field col s12" style="margin-top: -15px;">
          <div class="form-group">
          <div class="form-group">
          <label class="control-label" style="font-size: 11px;">Informações da solicitação:</label>
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

        
        @if($datas->valor_original < $datas->valor_prestado)
        <div id="valoramaisdiv">
        <p style="font-size: 11px">O usuário prestou conta de um valor maior que o transferido pela PL&C. Confirma o envio do valor faltante?</p>
        <p>
        <label>
        <input style="font-size: 11px;" name="retornarvalor" class="with-gap" value="SIM" type="radio"/>
        <span style="font-size: 10px;">Sim</span>
        </label> 
        </p>

        <p>
        <label>
        <input style="font-size: 11px;" name="retornarvalor" class="with-gap" value="NAO" type="radio" checked/>
        <span style="font-size: 10px;">Não</span>
        </label> 
        </p>

        <span style="font-size: 11px">Anexe o comprovante da transferência</span>
        <input type="file" name="comprovantetransferencia" id="comprovantetransferencia" style="font-size: 10px">

        </div>

        <br>
        <hr>
        @endif


        <p>
        <label>
        <input style="font-size: 11px;" name="statusescolhido" class="with-gap" value="aprovar" type="radio" checked onClick="fecharjustificativa();"/>
        <span style="font-size: 10px;">Aprovar prestação de conta</span>
        </label> 
        </p>

        <p>
        <label>
        <input style="font-size: 11px;" name="statusescolhido" class="with-gap" value="reprovar" type="radio" onClick="abrirjustificativa();"/>
        <span style="font-size: 10px;">Reprovar prestação de conta</span>
        </label>
        </p>

        <p>
        <label>
        <input style="font-size: 11px;" class="with-gap"  name="statusescolhido" value="cancelar" type="radio" onClick="abrirjustificativa();" />
        <span style="font-size: 10px;">Cancelar prestação de conta</span>
        </label>
        </p>


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

        <br>

        <div class="invoice-action-btn">
            <a onClick="abreconfirmacao();" style="background-color: gray;color:white;font-size:11px;" class="btn waves-effect waves-light display-flex align-items-center justify-content-center">
              <i class="material-icons mr-3">save</i>
              <span class="responsive-text" id="btngravar">Aprovar prestação</span>
            </a>
        </div> 

        </div>

        </div>
      </div>

      </div>



  <div id="aprovar" class="cd-popup" role="alert">
	<div class="cd-popup-container">
		<p style="font-weight: bold;">Deseja aprovar a solicitação de prestação de conta?</p>
		<ul class="cd-buttons">
			<li><a href="#" onClick="nao();" style="font-weight: bold;">Não</a></li>
			<li><a href="#" onClick="sim();" style="font-weight: bold;">Sim</a></li>
		</ul>
		<a onClick="nao();" class="cd-popup-close img-replace">Close</a>
	</div> 
</div> 


<div id="reprovar" class="cd-popup" role="alert">
	<div class="cd-popup-container">
		<p style="font-weight: bold;">Deseja reprovar a solicitação de prestação de conta?</p>
		<ul class="cd-buttons">
    <li><a href="#" onClick="nao();" style="font-weight: bold;">Não</a></li>
			<li><a href="#" onClick="sim();" style="font-weight: bold;">Sim</a></li>
		</ul>
		<a onClick="nao();" class="cd-popup-close img-replace">Close</a>
	</div> 
</div> 

<div id="cancelar" class="cd-popup" role="alert">
	<div class="cd-popup-container">
		<p style="font-weight: bold;">Deseja cancelar a solicitação de prestação de conta?</p>
		<ul class="cd-buttons">
    <li><a href="#" onClick="nao();" style="font-weight: bold;">Não</a></li>
			<li><a href="#" onClick="sim();" style="font-weight: bold;">Sim</a></li>
		</ul>
		<a onClick="nao();" class="cd-popup-close img-replace">Close</a>
	</div> 
</div> 



</form>
</section>

@endsection

@section('scripts')
    <script src="{{ asset('/public/materialize/js/vendors.min.js') }}"></script>
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
function abrirjustificativa() {
    document.getElementById("justificativadiv").style.display = "";    

  }    
</script>

<script>
function fecharjustificativa() {
    document.getElementById("justificativadiv").style.display = "none";     
  }    
</script>

<script>
function abreconfirmacaodevolucao() {
  document.getElementById("confirmacaodevolucaodiv").style.display = "";     
}
</script>

<script>
function fechaconfirmacaodevolucao() {
  document.getElementById("confirmacaodevolucaodiv").style.display = "none";     
}
</script>

<script>
  $(document).on("change", "input[name=statusescolhido]", function() {

  var status = $(this).val();

  //Altera o label do botão conforme a opção marcada
  if(status == "aprovar") {
    $('#btngravar').html('Aprovar prestação');
  } 
  else if(status == "reprovar") {
    $('#btngravar').html('Reprovar prestação');
  } else {
    $('#btngravar').html('Cancelar prestação');
  }
  
});
</script>
@endsection