@extends('painel.Layout.header')
@section('title') Pagamento de boletos @endsection
<!-- Titulo da pagina -->
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
<link rel="stylesheet" href="{{ asset('/public/materialize/css/widget-timeline.min.css') }}">

<style>
   .span {
   font-weight: bold;
   }
   /* HTML5 display-role reset for older browsers */
   article,
   aside,
   details,
   figcaption,
   figure,
   footer,
   header,
   hgroup,
   menu,
   nav,
   section,
   main {
   display: block;
   }
   ol,
   ul {
   list-style: none;
   }
   blockquote,
   q {
   quotes: none;
   }
   blockquote:before,
   blockquote:after,
   q:before,
   q:after {
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
   .cd-popup-container .cd-popup-close::before,
   .cd-popup-container .cd-popup-close::after {
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
Pesquisa patrimonial
@endsection
@section('submenu')
<li class="breadcrumb-item"><a href="{{ route('Painel.PesquisaPatrimonial.financeiro.index') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('Painel.PesquisaPatrimonial.financeiro.solicitacoes') }}">Solicitações</a></li>
<li class="breadcrumb-item active" style="color: black;">Pagamento de debites</li>
@endsection
@section('body')
<!-- BEGIN: Page Main-->
<div>
<div class="row">
<div class="content-wrapper-before blue-grey lighten-5"></div>
<div class="col s12">
<div class="container">
<section class="invoice-edit-wrapper section">
   <form role="form" id="form"
      action="{{ route('Painel.PesquisaPatrimonial.financeiro.storeficha') }}" method="POST"
      role="search" enctype="multipart/form-data">
      {{ csrf_field() }}
      <input type="hidden" name="id_matrix" value="{{$datas->ID}}" id="id_matrix">
      <input type="hidden" name="solicitanteid" value="{{$datas->SolicitanteID}}"
         id="solicitanteid">
      <input type="hidden" name="solicitantecpf" value="{{$datas->SolicitanteCPF}}"
         id="solicitantecpf">
      <input type="hidden" name="solicitanteemail" value="{{$datas->SolicitanteEmail}}"
         id="solicitanteemail">
      <input type="hidden" name="clientecodigo" id="clientecodigo"
         value="{{$datas->CodigoCliente}}">
      <input type="hidden" name="unidade" id="unidade" value="{{$datas->Unidade}}"
         class="form-control">
      <input type="hidden" name="numeroprocesso" id="numeroprocesso"
         value="{{$datas->NumeroProcesso}}" class="form-control">
      <input type="hidden" name="unidade" id="unidade" value="{{$datas->Unidade}}"
         class="form-control">
      <input ID="codigo" name="codigo" value="{{$datas->Codigo}}" readonly="" type="hidden" />
      <input type="hidden" readonly="" name="tipo" id="tipo" value="{{$datas->Tipo}}" />
      <input type="hidden" readonly="" name="tiposervico" id="tiposervico"
         value="{{$datas->TipoServico}}" />
      <input type="hidden" readonly="" name="codigopasta" id="codigopasta"
         value="{{$datas->Pasta}}" />
      <input type="hidden" readonly="" name="setor" id="setor" value="{{$datas->Setor}}" />
      <input type="hidden" readonly="" name="contrato" id="contrato"
         value="{{$datas->Contrato}}" />
      <input name="saldocliente" id="saldocliente" type="hidden"
         value="<?php echo number_format($saldocliente, 2); ?>" readonly="">
      <input type="hidden" readonly="" name="cpr" id="cpr" value="{{$datas->CPR}}" />
      <input type="hidden" name="statusid" id="statusid" value="{{$datas->StatusID}}">
      <input type="hidden" name="cobravel" id="cobravel" value="{{$datas->Cobravel}}">



      <div id="loadingdiv2" style="display:none;margin-top: 300px; margin-left: 570px;">
         <img style="width: 30px; margin-top: -100px;"
            src="{{URL::asset('/public/imgs/loading.gif')}}" />
         <h6 style="font-size: 20px;margin-left:-140px;">Aguarde, estamos atualizando a ficha
            financeira...
         </h6>
      </div>

      <div class="row" id="div_all">
      <div class="col xl9 m8 s12">
         <div class="card">

            @if(!empty($datas->anexoadiantamento))
            <a href="{{route('Painel.PesquisaPatrimonial.anexo', $datas->anexoadiantamento)}}"
               class="btn-floating btn-mini waves-effect waves-light tooltipped"
               data-position="left" data-tooltip="Clique aqui para visualiza o anexo do adiantamento."
               style="margin-left: 885px;margin-top:-15px;background-color: gray;"><i
               class="material-icons">attach_file</i></a>
            @endif

            @if(!empty($datas->anexo))
            <a href="{{route('Painel.PesquisaPatrimonial.anexo', $datas->anexo)}}"
               class="btn-floating btn-mini waves-effect waves-light tooltipped"
               data-position="left" data-tooltip="Clique aqui para visualiza o anexo."
               style="margin-left: 835px;margin-top:-60px;background-color: gray;"><i
               class="material-icons">attach_file</i></a>
            @endif


            <div class="card-content px-36">
               <div class="col m4 s4">
                  <div class="divider show-on-small hide-on-med-and-up mb-3"></div>
                  <h6 class="invoice-to">Dados da solicitação</h6>
                  <div class="invoice-address" style="font-size: 10px;">
                     <p style="font-weight: bold;color:black;">Nome do pesquisado:
                        <?php echo mb_convert_case($datas->OutraParte, MB_CASE_TITLE, "UTF-8")?>
                     </p>
                  </div>
                  <div class="invoice-address" style="font-size: 10px;">
                     <p style="font-weight: bold;color:black;">CPF/CNPJ do pesquisado:
                        {{$datas->Codigo}}
                     </p>
                  </div>
                  <div class="invoice-address" style="font-size: 10px;">
                     <p style="font-weight: bold;color:black;">Código da pasta:
                        {{$datas->Pasta}}
                     </p>
                  </div>
                  <div class="invoice-address" style="font-size: 10px;">
                     <p style="font-weight: bold;color:black;">Descrição da pasta:
                        <?php echo mb_convert_case($datas->PastaDescricao, MB_CASE_TITLE, "UTF-8")?>
                     </p>
                  </div>
                  <div class="invoice-address" style="font-size: 10px;">
                     <p style="font-weight: bold;color:black;">Número do processo:
                        {{$datas->NumeroProcesso}}
                     </p>
                  </div>
                  <div class="invoice-address" style="font-size: 10px;">
                     <p style="font-weight: bold;color:black;">Unidade:
                        {{$datas->UnidadeDescricao}}
                     </p>
                  </div>
                  <div class="invoice-address" style="font-size: 10px;">
                     <p style="font-weight: bold;color:black;">Setor de custo:
                        {{$datas->SetorDescricao}}
                     </p>
                  </div>

                  <div class="invoice-address" style="font-size: 10px;">
                     <p style="font-weight: bold;color:black;">Tipo de solicitação:
                        {{$datas->TipoSolicitacao}}
                     </p>
                  </div>

                  <div class="invoice-address" style="font-size: 10px;">
                     <p style="font-weight: bold;color:black;">Status atual:
                        {{$datas->Status}}
                     </p>
                  </div>

                  <div class="invoice-address" style="font-size: 10px;">
                     <p style="font-weight: bold;color:black;">Cobrável:
                        {{$datas->Cobravel}}
                     </p>
                  </div>

               </div>

               <div class="col m4 s4">
                  <div class="divider show-on-small hide-on-med-and-up mb-3"></div>
                  <h6 class="invoice-to">Dados do cliente</h6>
                  <div class="invoice-address" style="font-size: 10px;">
                     <p style="font-weight: bold;color:black;">CPF/CNPJ:
                        {{$datas->CodigoCliente}}
                     </p>
                  </div>
                  <div class="invoice-address" style="font-size: 10px;">
                     <p style="font-weight: bold;color:black;">Razão Social:
                        <?php echo mb_convert_case($datas->ClienteRazao, MB_CASE_TITLE, "UTF-8")?>
                     </p>
                  </div>
                  <div class="invoice-address" style="font-size: 10px;">
                     <p style="font-weight: bold;color:black;">Grupo Cliente:
                        <?php echo mb_convert_case($datas->GrupoCliente, MB_CASE_TITLE, "UTF-8")?>
                     </p>
                  </div>
                  <div class="invoice-address" style="font-size: 10px;">
                     <p style="font-weight: bold;color:black;">Conta Identificadora: </p>
                  </div>
                  <div class="invoice-address" style="font-size: 10px;">
                     <p style="font-weight: bold;color:black;">Moeda: R$ </p>
                  </div>
                  <div class="invoice-address" style="font-size: 10px;">
                     <p style="font-weight: bold;color:green;">Saldo atual na conta do cliente(Pesquisa patrimonial): R$
                        <?php echo number_format($saldocliente,2,",",".") ?>
                     </p>
                  </div>
               </div>
               <div class="row invoice-info">
                  <div class="col m4 s4">
                     <h6 class="invoice-from">Dados do solicitante</h6>
                     <div class="invoice-address" style="font-size: 10px;">
                        <p style="font-weight: bold;color:black;">Nome:
                           <?php echo mb_convert_case($datas->SolicitanteNome, MB_CASE_TITLE, "UTF-8")?>
                        </p>
                     </div>
                     <div class="invoice-address" style="font-size: 10px;">
                        <p style="font-weight: bold;color:black;">E-mail:
                           {{$datas->SolicitanteEmail}}
                        </p>
                     </div>
                     <div class="invoice-address" style="font-size: 10px;">
                        <p style="font-weight: bold;color:black;">CPF:
                           {{$datas->SolicitanteCPF}}
                        </p>
                     </div>
                  </div>
               </div>
               <div class="divider mb-3 mt-3"></div>
               <p style="font-size: 11px;color:black;font-weight: bold;">Desmembramento
                  Solicitações
               </p>

               @foreach($solicitacoes as $solicitacao)


   <div id="anexos{{$solicitacao->Anexo}}" class="modal modal-fixed-footer" style="width: 100%;height:100%;overflow:hidden;">

     <button type="button" class="btn waves-effect mr-sm-1 mr-2 modal-close red" style="margin-left: 1255px; margin-top: 5px;">
      <i class="material-icons">close</i>
     </button>

     <iframe style=" position:absolute;top:60;left:0;width:100%;height:100%;" src="https://docs.google.com/gview?url=https://portal.plcadvogados.com.br/portal/public/imgs/reembolso/{{$solicitacao->Anexo}}&embedded=true"></iframe>

   </div>





               <div class="invoice-item display-flex mb-1">
                  <div class="invoice-item-filed row pt-1">

                  <input type="hidden" name="debpago[]" value="{{$solicitacao->DebPago}}">

                  @if($solicitacao->assertiva != "SIM")
                     <a href="#anexos{{$solicitacao->Anexo}}"
                        class="btn-floating btn-mini waves-effect waves-light tooltipped modal-trigger"
                        data-position="left"
                        data-tooltip="Clique aqui para visualizar o boleto deste tipo de serviço."
                        style="margin-left: 770px;margin-top:-27px;background-color: gray;"><i
                        class="material-icons">attach_file</i></a>
                  @endif

                     @if(!empty($solicitacao->Comprovante))
                     <a href="{{route('Painel.PesquisaPatrimonial.anexo', $solicitacao->Comprovante)}}"
                        class="btn-floating btn-mini waves-effect waves-light tooltipped"
                        data-position="left"
                        data-tooltip="Clique aqui para baixar o comprovante de pagamento deste tipo de serviço."
                        style="margin-left: 820px;margin-top:-70px;background-color: gray;"><i
                        class="material-icons">attach_file</i></a>
                     @endif
                     <input type="hidden" name="tipossolicitacao[]" id="tipossolicitacao"
                        value="{{$solicitacao->id}}">
                     <textarea id="hist" rows="7" type="text" name="hist[]"
                        style="display:none;" required="required" class="form-control"
                        placeholder="Digite a observação">
            {{$solicitacao->Hist}}
            Pagamento realizado pelo(a): {{Auth::user()->name}} no Portal PL&C ás {{ date('d/m/Y H:i:s', strtotime($carbon)) }}.</textarea>
                     <div class="col m3 s12 input-field">
                        <span style="font-size: 11px;">Solicitação:</span>
                        <input type="text" readonly value="{{$solicitacao->descricao}}"
                           style="font-size:10px;" required>
                     </div>
                     <div class="col s12 m3 input-field">
                        <span style="font-size: 11px;">Comarca:</span>
                        <input type="text" readonly value="{{$solicitacao->Cidade}}"
                           name="cidade[]" id="valor_total" style="font-size:10px;"
                           required>
                     </div>
                     <div class="col m2 s12 input-field">
                        <span id="labelvalor" name="labelvalor"
                           style="font-size: 11px;">Valor:</span>
                        <input type="text" readonly
                           value="<?php echo number_format($solicitacao->Valor, 2,",",".") ?>"
                           id="valor" name="valor[]" style="font-size:10px;" required>
                     </div>
                     <div class="col m3 s12 input-field">
                        <span style="font-size: 11px;">Assertiva:</span>
                        <input type="text" readonly
                           value="{{$solicitacao->assertiva}}"
                           name="assertiva[]" id="assertiva"
                           style="font-size:10px;">                                            
                     </div>
                     <div class="col m2 s12 input-field">
                        <span style="font-size: 11px;">Debite:</span>
                        <input type="text" readonly
                           value="{{$solicitacao->NumeroDebite}}" name="numerodebite[]"
                           id="numerodebite" style="font-size:10px;" required>
                     </div>

                     @if($solicitacao->DataPagamento == NULL)
                     <div class="col m3 s12 input-field">
                        <span style="font-size: 11px;">Banco de saída:</span>
                        <select class="invoice-item-select browser-default"
                           id="portador" name="portador[]" style="font-size:10px;">
                           <option value="">Selecione abaixo</option>
                           @if($solicitacao->assertiva == "SIM")
                           <option value="ASSERTIVA" selected>
                              Banco da assertiva
                           </option>
                           @endif

                           @foreach($bancos as $banco)
                           <option value="{{$banco->Codigo}}">
                              {{$banco->Descricao}}
                           </option>
                           @endforeach
                        </select>
                     </div>

                     <div class="col m3 s12 input-field">
                        <span style="font-size: 11px;">Data do pagamento:</span>
                        <input type="date" value="{{$datahoje}}" max="{{$datahoje}}"
                           id="valor_unitario" name="datapagamentodebite[]"
                           style="font-size:10px;">
                     </div>

                     @if($solicitacao->assertiva != "SIM")
                     <div class="col m3 s12 input-field">
                        <span id="labelvalor" name="labelvalor"
                           style="font-size: 11px;">Anexar comprovante de
                        pagamento:</span>
                        <input style="font-size: 10px;" type="file" id="comprovante"
                           name="comprovante[]"
                           accept=".jpg,.png,.xls,.xlsx,.pdf,.doc,.docx,.rtf,.jpeg"
                           required class="dropify" data-default-file="" />
                     </div>

                     @endif

                     @else
                     <div class="col m3 s12 input-field">
                        <span style="font-size: 11px;">Data do pagamento:</span>
                        <input type="date" name="datapagamentodebite[]"
                           value="{{$solicitacao->DataPagamento}}"
                           style="font-size:10px;">
                     </div>

                     <div class="col m3 s12 input-field">
                        <span style="font-size: 11px;">Banco de saída:</span>
                        <select class="invoice-item-select browser-default"
                           id="portador" name="portador[]" style="font-size:10px;">
                           <option value="">Selecione abaixo</option>

                           @if($solicitacao->assertiva == "SIM")
                           <option value="ASSERTIVA" selected>
                              Banco da assertiva
                           </option>
                           @endif
  
                           @foreach($bancos as $banco)
                           <option value="{{$banco->Codigo}}">
                              {{$banco->Descricao}}
                           </option>
                           @endforeach
                        </select>

                     </div>
                     @endif

                     <div class="row">
                     <div class="input-field col s12" style="margin-top: -15px;">
                     <div class="form-group">
                     <div class="form-group">
                     <label class="control-label" style="font-size: 11px;">Informações adicionais:</label>
                     <textarea rows="3" type="text" name="informacoesadicionais[]" class="form-control" placeholder="Campo livre." style="height: 5rem;text-align:left; overflow:auto;font-size: 10px;width:829px">{{$solicitacao->observacao}}</textarea>
                     </div>
                     </div>
                     </div>   
                      </div>  
                     
                  </div>
               </div>
               @endforeach
               <div class="divider mt-3 mb-3"></div>
               <br><br>

               <div class="invoice-subtotal">

                  <div class="row">
                     <div class="input-field col s12" style="margin-top: -15px;">
                        <div class="form-group">

                           <div class="form-group">
                              <label class="control-label"
                                 style="font-size: 11px;">Informações:</label>
                              <textarea id="observacao" readonly rows="3" type="text"
                                 name="observacao" class="form-control"
                                 placeholder="Insira a observação abaixo."
                                 style="height: 3rem;text-align:left; overflow:auto;font-size: 10px;">{{$datas->Observacao}}</textarea>
                           </div>

                        </div>

                     </div>
                  </div>
               </div>

               <div class="row">
                     <div class="input-field col s12" style="margin-top: -15px;">
                        <div class="form-group">

                           <div class="form-group">
                              <label class="control-label"
                                 style="font-size: 11px;">Informações do adiantamento:</label>
                              <textarea id="observacao" readonly rows="3" type="text"
                                 name="observacaoadiantamento" class="form-control"
                                 placeholder="Insira a observação abaixo."
                                 style="height: 3rem;text-align:left; overflow:auto;font-size: 10px;">{{$datas->observacaoadiantamento}}</textarea>
                           </div>

                        </div>

                     </div>
               </div>

            </div>
         </div>
      </div>
      <div class="col xl3 m4 s12">
      <div class="card invoice-action-wrapper mb-10">
         <div class="card-content">


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

        <div class="row" style="font-size:10px;" id="aprovadiv">

        <div class="input-field col m12 s12">
         <span style="font-size: 11px;">Sub Status atual:</span>
         <div class="form-group bmd-form-group is-filled">
         @if($datas->Cobravel == "NAO")
         <input style="font-size: 10px;" type="text"  readonly value="Despesa da área (Valor não reembolsável)" >
         @elseif($datas->StatusID == 19)
         <input style="font-size: 10px;" type="text"  readonly value="Valor adiantado pelo cliente" >
         @elseif($datas->StatusID == 20)
         <input style="font-size: 10px;" type="text"  readonly value="Valor Reembolsável pelo cliente" >
         @endif
         </div>
         </div>

        <div class="input-field col m12 s12">
        <span>Tipo de lançamento:</span>
        <div class="form-group bmd-form-group is-filled">
        <select class="browser-default" required style="width: 100%;font-size:10px;" id="tipolan" name="tipolan" data-toggle="tooltip" data-placement="top" title="Selecione o tipo de lançamento.">
        @if($datas->Cobravel == "NAO")
        <option value="08.06.01" selected>08.06.01 - TAXA CARTORIAIS D.</option>
        @elseif($datas->StatusID == 19)
        <option value="16.09" selected>16.09 - DESPESAS REEMBOLSAVEIS (VALORES ADIANTADOS)</option>
        @elseif($datas->StatusID == 20)
        <option value="16.01" selected>16.01 - DESPESAS REEMBOLSAVEIS</option>
        @endif

        @foreach($tiposlan as $tipolan)
        <option value="{{$tipolan->Codigo}}">{{$tipolan->Codigo}} {{$tipolan->Descricao}}</option>
        @endforeach
        </select>
        </div>
        </div>

        </div>


        <div class="row" style="font-size:10px;display:none;" id="justificativadiv">
          <div class="input-field col m12 s12">
          <span style="font-size: 10px;">Informe o motivo:</span>
          <div class="form-group bmd-form-group is-filled">
          <select class="browser-default" style="width: 100%;font-size:10px;" id="motivoedicao" name="motivoedicao"  data-toggle="tooltip" data-placement="top" title="Selecione o motivo da reprovação/cancelamento da solicitação de reembolso.">
          @foreach($motivos as $motivo)
          <option value="{{$motivo->id}}">{{$motivo->descricao}}</option>
          @endforeach
          </select>
          </div>
        </div>

        <div class="input-field col m12 s12">
        <span style="font-size: 10px;">Observação:</span>
          <div class="form-group bmd-form-group is-filled">
          <textarea id="observacaoedicao" rows="3" type="text" name="observacaoedicao" class="form-control" placeholder="Insira a observação abaixo." style="height: 4rem;text-align:left; overflow:auto;font-size: 10px;"></textarea>
          </div>
        </div>

        </div>


        <div class="invoice-action-btn">
            <a name="btnsubmit" id="btnsubmit" onClick="abremodalconfirmacao();"
                  style="background-color: gray;color:white;font-size:11px;"
                  class="btn waves-effect waves-light display-flex align-items-center justify-content-center">
            <i class="material-icons mr-4">save</i>
            <span class="responsive-text" id="btngravar">Atualizar ficha</span>
            </a>
         </div>



        </div>
        </div>
        </div>


               <!--WorkFlow -->
               <div class="col xl3 m4 s12">
                  <div class="card invoice-action-wrapper mb-10">
                     <ul class="collapsible collapsible-accordion">
                        <li>
                           <div class="collapsible-header" style="font-size: 11px;"><i
                              class="material-icons">timeline</i> Workflow - Pesquisa patrimonial
                           </div>
                           <div class="collapsible-body">
                              <ul class="widget-timeline mb-0">
 
                                 @foreach($historicos as $historico)
                                 <li class="timeline-items timeline-icon-green active">
                                    <div class="timeline-time"
                                       style="font-size: 8.5px;color:black;">
                                       {{ date('d/m/Y H:i', strtotime($historico->data)) }}
                                    </div>
                                    <h6 class="timeline-title"
                                       style="font-size: 9px;color:black;">
                                       <?php echo mb_convert_case($historico->nome, MB_CASE_TITLE, "UTF-8")?>
                                    </h6>
                                    <p class="timeline-text"
                                       style="font-size: 10px;color:black;">
                                       {{$historico->status}}
                                    </p>
                                 </li>
                                 @endforeach
                              </ul>
                           </div>
                        </li>
                     </ul>
                  </div>
               </div>
               <!--Fim WorkFlow -->
               
            </div>
         </div>
      </div>
   </form>
</section>

<div id="reprovar" class="cd-popup" role="alert">
	<div class="cd-popup-container">
		<p style="font-weight: bold;">Deseja enviar está ficha para correção para a equipe do núcleo?</p>
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
<script src="{{ asset('/public/materialize/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('/public/materialize/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('/public/materialize/js/app-invoice.min.js') }}"></script>
<script src="{{ asset('/public/materialize/js/vendors.min.js') }}"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
   $('.modal').modal();
});
</script>


<div id="alertaconfirmacao" name="alertaconfirmacao" class="cd-popup" role="alert">
   <div class="cd-popup-container">
      <p style="font-weight: bold;">Deseja atualizar a ficha financeira?</p>
      <ul class="cd-buttons">
         <li><a href="#" onClick="nao();" style="font-weight: bold;">Não</a></li>
         <li><a href="#" onClick="sim();" style="font-weight: bold;">Sim</a></li>
      </ul>
      <a onClick="nao();" class="cd-popup-close img-replace">Close</a>
   </div>
</div>


<script>
   function abremodalconfirmacao() {
   
      var status = document.querySelector('input[name="statusescolhido"]:checked').value;

       $("#btnsubmit").attr("disabled", "disabled");
       if(status == "aprovar") {

       //Se não direciona para tela de confirmação
       $('#alertaconfirmacao').addClass('is-visible');
       }
       else {
      $('#reprovar').addClass('is-visible');
       }
   
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
       $("#btnsubmit").removeAttr("disabled");
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
  $(document).on("change", "input[name=statusescolhido]", function() {

  var status = $(this).val();

  //Altera o label do botão conforme a opção marcada
  if(status == "aprovar") {
    $('#btngravar').html('Aprovar ficha');
  } 
  else if(status == "reprovar") {
    $('#btngravar').html('Reprovar ficha');
  }
  
});
</script>

@endsection