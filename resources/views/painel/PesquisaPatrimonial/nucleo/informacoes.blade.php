@extends('painel.Layout.header')
@section('title') Informações da solicitação de pesquisa patrimonial @endsection
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
<li class="breadcrumb-item"><a href="{{ route('Painel.PesquisaPatrimonial.nucleo.index') }}">Dashboard</a></li>
<li class="breadcrumb-item active" style="color: black;">Informações da solicitação de pesquisa patrimonial</li>
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
            action="{{ route('Painel.PesquisaPatrimonial.Nucleo.storeinformacoes') }}" method="POST"
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
            <input type="hidden" name="clienterazao" id="clienterazao" value="{{$datas->ClienteRazao}}">
            <input type="hidden" name="prconta" id="prconta" value="{{$datas->PRConta}}">
            <input type="hidden" name="unidade" id="unidade" value="{{$datas->Unidade}}" class="form-control">
            <input type="hidden" name="nomepesquisado" value="{{$datas->OutraParte}}">
            <input type="hidden" name="codigopesquisado" value="{{$datas->Codigo}}">
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
            <input type="hidden" name="datasolicitacao" id="datasolicitacao" value="{{ date('d/m/Y H:i', strtotime($datas->DataSolicitacao)) }}">

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
                     @if(!empty($datas->anexo))
                     <a href="{{route('Painel.PesquisaPatrimonial.anexo', $datas->anexo)}}"
                        class="btn-floating btn-mini waves-effect waves-light tooltipped"
                        data-position="left" data-tooltip="Clique aqui para visualiza o anexo."
                        style="margin-left: 885px;margin-top:-10px;background-color: gray;"><i
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
                              <p style="font-weight: bold;color:green;">Saldo disponível: R$
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

                        <p style="font-size: 11px;color:black;font-weight: bold;">Desmembramento Solicitações
                        </p>

                        <a href="#" onClick="adicionanovasolicitacao();" class="btn-floating btn-mini waves-effect waves-light tooltipped"data-position="right" data-tooltip="Clique aqui para adicionar uma nova solicitação de pesquisa patrimonial."  style="margin-left: 180px;margin-top:-40px;background-color: green;"><i class="material-icons">add</i></a>

                        @foreach($solicitacoes as $solicitacao)

                        <div id="anexos{{$solicitacao->Anexo}}" class="modal modal-fixed-footer" style="width: 100%;height:100%;overflow:hidden;">
                           <button type="button" class="btn waves-effect mr-sm-1 mr-2 modal-close red" style="margin-left: 1255px; margin-top: 5px;">
                           <i class="material-icons">close</i>
                           </button>
                           <iframe style=" position:absolute;top:60;left:0;width:100%;height:100%;" src="https://docs.google.com/gview?url=https://portal.plcadvogados.com.br/portal/public/imgs/reembolso/{{$solicitacao->Anexo}}&embedded=true"></iframe>
                        </div>

                        <div class="invoice-item display-flex mb-1">
                           <div class="invoice-item-filed row pt-1">

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
                                    <input type="text" onblur="findTotalservicos(this)" value="<?php echo number_format($solicitacao->Valor, 2,",",".") ?>" name="valor[]" id="valor"  placeholder="Valor únitario..." onKeyPress="javascript:return(moeda2(this,event))" pattern="(?:\.|,|[0-9])*" style="font-size:10px;"required >
                              </div>

                              <div class="col m2 s12 input-field">
                                 <span style="font-size: 11px;">Assertiva:</span>
                                 <select class="invoice-item-select browser-default" id="assertiva" name="assertiva[]" style="font-size:10px;">
                                 @if($solicitacao->assertiva == "SIM")
                                 <option value="SIM" selected>Sim</option>
                                 <option value="Não">Não</option>
                                 @else
                                 <option value="SIM">Sim</option>
                                 <option value="Não" selected>Não</option>
                                 @endif
                                 </select>
                              </div>

                              <div class="col m2 s12 input-field">
                                 <span style="font-size: 11px;">Debite:</span>
                                 <input type="text" readonly
                                    value="{{$solicitacao->NumeroDebite}}" name="numerodebite[]"
                                    id="numerodebite" style="font-size:10px;" required>
                              </div>

                              <div class="col m3 s12 input-field">
                              <span id="labelvalor" name="labelvalor" style="font-size: 11px;">Anexar novo boleto:</span>
                              <input style="font-size: 10px;" type="file" id="select_file" name="select_file[]" accept=".pdf"  class="dropify" data-default-file="" />
                              </div>

                              
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
                        <div id="teste1">
                        </div>


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
                     </div>
                  </div>
               </div>

               <div class="col xl3 m4 s12">
      <div class="card invoice-action-wrapper mb-10">
         <div class="card-content">

         <div class="row" style="font-size:10px;">
         <div class="input-field col m12 s12">
          <span style="font-size: 10px;">Valor total:</span>
          <div class="form-group bmd-form-group is-filled">
          <input type="text" value="00,00" id="valortotal" name="valortotal" onKeyPress="javascript:return(moeda2(this,event))" pattern="(?:\.|,|[0-9])*" placeholder="0,00"  style="font-size:10px;"required >
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
@endsection

@section('scripts')
<script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
<script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
<script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>
<script src="{{ asset('/public/materialize/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('/public/materialize/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('/public/materialize/js/app-invoice.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>

<script language="javascript">   
 function moeda2(a, t) {

    var e = '.';
    var r = ',';

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
   
       $("#btnsubmit").attr("disabled", "disabled");
   
       //Se não direciona para tela de confirmação
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
       $("#btnsubmit").removeAttr("disabled");
   }
</script>


<script>
function adicionanovasolicitacao() {

  // $('#teste1').after('<div id="message">Message</div>');
  var html = '';
  html += '<div class=invoice-item display-flex mb-1>';
  html += '<div class="invoice-item-filed row pt-1">';
  html += '<a href="#" onClick="removesolicitacao(143434343);" id="143434343" class="btn-floating btn-mini waves-effect waves-light tooltipped"data-position="left" data-tooltip="Clique aqui para excluir está solicitação de pesquisa patrimonial."  style="margin-left: 820px;margin-top:-20px;background-color: red;"><i class="material-icons">remove</i></a>';
 
  html += '<div class="row">';

  html += '<input type="hidden" name="tipossolicitacao[]" value="">'; 

  html += '<div class="col m3 s12 input-field">';
  html += '<span style="font-size: 11px;">Solicitação:</span>';
  html += '<select class="invoice-item-select browser-default" name="tiposolicitacao_id[]" style="font-size:10px;">'
  html += '<option value="1" selected>Status</option><option value="2">Imóvel</option><option value="3">Veículo</option><option value="4" >Empresa</option><option value="5">Infojud</option><option value="6">Bacenjud</option><option value="7">Protestos</option><option value="8">Redes Sociais</option><option value="9">Processos Judiciais</option><option value="10" >Pesquisa Cadastral</option><option value="11" >Dossiê Comercial</option><option value="14">Diversos</option><option value="15">Joias</option><option value="16" >Moeda</option>';
  html += '</select>';
  html += '</div>';

  html += '<div class="col m3 s12 input-field">';
  html += '<span style="font-size: 11px;">Comarca:</span>';
  html += '<select class="select2 browser-default" id="comarcas" name="comarcas[]" style="font-size:10px;">';
  html += '<option value="" selected>Selecione abaixo</option>';
  @foreach($cidades as $cidade)
  html += '<option value="{{$cidade->id}}">{{$cidade->municipio}}</option>';
  @endforeach
  html += '</select>';
  html += '</div>';

  html += '<div class="col m2 s12 input-field">';
  html += '<span style="font-size: 11px;">Valor:</span>';
  html += '<input type="text" onblur="findTotalservicos(this)" value="00,00" name="valor[]" id="valor"  placeholder="Valor únitario..." onKeyPress="javascript:return(moeda2(this,event))" pattern="(?:\.|,|[0-9])*" style="font-size:10px;"required >'
  html += '</div>';

  html += '<div class="col m3 s12 input-field">';
  html += '<span style="font-size: 11px;">Assertiva:</span>';
  html += '<select class="invoice-item-select browser-default" id="assertiva" name="assertiva[]" style="font-size:10px;">';
  html += '<option value="SIM" selected>Sim</option>';
  html += '<option value="Não">Não</option>';
  html += '</select>';
  html += '</div>';

  html += '<div class="col m3 s12 input-field">';
  html += '<span style="font-size: 11px;">Anexar novo boleto:</span>';
  html += '<input style="font-size: 10px;" type="file" id="select_file" name="select_file[]" accept=".pdf"  class="dropify" data-default-file="" />';
  html += '</div>';

  html += '</div>';

 
  html += '<div class="row>';
  html += '<div class="input-field col s12" style="margin-top: -15px;"';
  html += '<div class="form-group>';
  html += '<div class="form-group>';
  html += '<label class="control-label" style="font-size: 11px;">Informações adicionais:</label>';
  html += '<textarea rows="3" type="text" name="informacoesadicionais[]" class="form-control" placeholder="Campo livre." style="height: 5rem;text-align:left; overflow:auto;font-size: 10px;width:829px;"></textarea>';
  html += '</div>';
  html += '</div>';
  html += '</div>';
  html += '</div>';

  html += '</div>';
  html += '</div>';


  $('#teste1').append(html);

}
</script>

<script type="text/javascript">
function findTotalservicos(teste){

   var valor_adicionado = teste.value;
   // var valortotal = parseFloat($("#valortotal").val().replace('.', ',').replace(',','.'));
   // var total = valortotal - valor_adicionado;
   
   // alert(total);

   // document.getElementById('valortotal').value=(total.toFixed(2));


   var valor = parseFloat($("#valortotal").val().replace('.', ',').replace(',','.'));
   valor += parseFloat(valor_adicionado.replace('.', '').replace(',','.'));

   document.getElementById('valortotal').value=(valor.toFixed(2));

}

 </script>


@endsection