
@extends('painel.Layout.header')
@section('title') Montagem da ficha financeira @endsection <!-- Titulo da pagina -->

@section('header') 
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="author" content="PL&C">
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
Pesquisa patrimonial
@endsection
@section('submenu')
<li class="breadcrumb-item"><a href="{{ route('Painel.PesquisaPatrimonial.nucleo.index') }}">Dashboard</a>
</li>
<li class="breadcrumb-item"><a href="{{ route('Painel.PesquisaPatrimonial.nucleo.solicitacoesaguardandoficha') }}">Solicitações aguardando montagem da ficha financeira</a>
</li>
<li class="breadcrumb-item active" style="color: black;">Montagem da ficha financeira
</li>
@endsection
@section('body')
    <div>
      <div class="row">
        <div class="content-wrapper-before blue-grey lighten-5"></div>
        <div class="col s12">
          <div class="container">
			
<section class="invoice-edit-wrapper section">

<form id="form" role="form" onsubmit="btngravar.disabled = true; return true;" action="{{ route('Painel.PesquisaPatrimonial.storeanexararquivos2') }}" method="POST" role="search"  enctype="multipart/form-data">
  {{ csrf_field() }}

  <input type="hidden" name="id_matrix" value="{{$datas->ID}}" id="id_matrix">
  <input type="hidden" name="solicitanteid" value="{{$datas->SolicitanteID}}" id="solicitanteid">
  <input type="hidden" name="solicitantecpf" value="{{$datas->SolicitanteCPF}}" id="solicitantecpf">
  <input type="hidden" name="solicitanteemail" value="{{$datas->SolicitanteEmail}}" id="solicitanteemail">
  <input type="hidden" id="clienterazao" name="clienterazao" value="{{$datas->ClienteRazao}}">
  <input type="hidden" name="clientecodigo" id="clientecodigo" value="{{$datas->CodigoCliente}}">  
  <input type="hidden" id="clientecep" name="clientecep" value="{{$datas->ClienteCEP}}">
  <input type="hidden" id="clienteendereco" name="clienteendereco" value="{{$datas->ClienteEndereco}}">
  <input type="hidden" id="clientebairro" name="clientebairro" value="{{$datas->ClienteBairro}}">
  <input type="hidden" id="clienteuf" name="clienteuf" value="{{$datas->ClienteUF}}">
  <input type="hidden" id="clientecidade" name="clientecidade" value="{{$datas->ClienteCidade}}">
  <input type="hidden" id="unidaderazao" name="unidaderazao" value="{{$datas->UnidadeRazao}}">
  <input type="hidden" id="unidadecnpj" name="unidadecnpj" value="{{$datas->UnidadeCNPJ}}">
  <input type="hidden" id="unidadeendereco" name="unidadeendereco" value="{{$datas->UnidadeEndereco}}">
  <input type="hidden" id="unidadebairro" name="unidadebairro" value="{{$datas->UnidadeBairro}}">
  <input type="hidden" id="unidadecidade" name="unidadecidade" value="{{$datas->UnidadeCidade}}">
  <input type="hidden" id="unidadeuf" name="unidadeuf" value="{{$datas->UnidadeUF}}">
  <input type="hidden" id="unidadecep" name="unidadecep" value="{{$datas->UnidadeCEP}}">
  <input type="hidden" name="unidade" id="unidade" value="{{$datas->UnidadeDescricao}}" class="form-control">  
  <input type="hidden" id="unidadetelefone" name="unidadetelefone" value="{{$datas->UnidadeTelefone}}">
  <input type="hidden" name="grupocliente_codigo" id="grupocliente_codigo" value="{{$datas->GrupoClienteCodigo}}">  
  <input type="hidden" name="numeroprocesso" id="numeroprocesso" value="{{$datas->NumeroProcesso}}" class="form-control">  
  <input type="hidden" name="unidadedescricao" id="unidadedescricao" value="{{$datas->UnidadeDescricao}}" class="form-control"> 
  <input ID="outraparte" name="outraparte" type="hidden" value="{{$datas->OutraParte}}"/>
  <input ID="codigo" name="codigo" value="{{$datas->Codigo}}" readonly="" type="hidden"/>
  <input readonly="" name="solicitantenome" id="solicitante" value="{{$datas->SolicitanteNome}}" type="hidden"/>
  <input readonly="" name="cliente" id="cliente" value="{{$datas->ClienteFantasia}}" type="hidden"/>
  <input readonly="" name="grupocliente" id="grupocliente" value="{{$datas->GrupoCliente}}" type="hidden"/>
  <input type="hidden" readonly="" name="emailcliente" id="emailcliente" value="{{$datas->EmailCliente}}"/>
  <input type="hidden" readonly="" name="tipo" id="tipo" value="{{$datas->Tipo}}"/>
  <input type="hidden" readonly="" name="tiposolicitacao" id="tiposolicitacao" value="{{$datas->Classificacao}}"/>
  <input type="hidden" readonly="" name="tiposervico" id="tiposervico" value="{{$datas->TipoServico}}"/>
  <input type="hidden" readonly="" name="codigopasta" id="codigopasta" value="{{$datas->Pasta}}"/>
  <input type="hidden" readonly="" name="setor" id="setor" value="{{$datas->Setor}}" />
  <input type="hidden" readonly="" name="contrato" id="contrato" value="{{$datas->Contrato}}"/> 
  <input name="saldocliente" id="saldocliente" type="hidden" value="<?php echo number_format($saldototal, 2); ?>" readonly="">
  <input type="hidden" value="" id="idtemp" name="idtemp">
  <input ID="cobravel" name="cobravel" value="{{$datas->Cobravel}}" type="hidden">

  <div id="loadingdiv2" style="display:none;margin-top: 300px; margin-left: 570px;">
    <img style="width: 30px; margin-top: -100px;" src="{{URL::asset('/public/imgs/loading.gif')}}"/>
    <h6 style="font-size: 20px;margin-left:-140px;">Aguarde, estamos atualizando a ficha financeira...</h6>
  </div>

  <div class="row" id="div_all">

    <div class="col xl9 m8 s12">
      <div class="card">

      @if(!empty($datas->anexo))
      <a href="{{route('Painel.PesquisaPatrimonial.anexo', $datas->anexo)}}" class="btn-floating btn-mini waves-effect waves-light tooltipped"data-position="left" data-tooltip="Clique aqui para visualiza o anexo."  style="margin-left: 885px;margin-top:-10px;background-color: gray;"><i class="material-icons">attach_file</i></a>
      @endif

        <div class="card-content px-36">

          <div class="col m4 s4">
            <div class="divider show-on-small hide-on-med-and-up mb-3"></div>
            <h6 class="invoice-to">Dados da solicitação</h6>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">Nome do pesquisado: <?php echo mb_convert_case($datas->OutraParte, MB_CASE_TITLE, "UTF-8")?></p>
            </div>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">CPF/CNPJ do pesquisado:  {{$datas->Codigo}}</p>
            </div>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">Código da pasta:  {{$datas->Pasta}}</p>
            </div>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">Descrição da pasta: <?php echo mb_convert_case($datas->PastaDescricao, MB_CASE_TITLE, "UTF-8")?></p>
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
            <p style="font-weight: bold;color:black;">Tipo de solicitação: {{$datas->Classificacao}}</p>
            </div>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">Status atual: {{$datas->Status}}</p>
            </div>


          </div>

          <div class="col m4 s4">
            <div class="divider show-on-small hide-on-med-and-up mb-3"></div>
            <h6 class="invoice-to">Dados do cliente</h6>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">CPF/CNPJ: {{$datas->CodigoCliente}}</p>
            </div>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">Razão Social: <?php echo mb_convert_case($datas->ClienteRazao, MB_CASE_TITLE, "UTF-8")?></p>
            </div>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">Grupo Cliente: <?php echo mb_convert_case($datas->GrupoCliente, MB_CASE_TITLE, "UTF-8")?></p>
            </div>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">Conta Identificadora: </p>
            </div>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">Moeda: R$ </p>
            </div>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">Data de vencimento do boleto: {{ date('d/m/Y', strtotime($datavencimento)) }}</p>
            </div>

            <div class="invoice-address" style="font-size: 10px;">
            @if($saldototal > 0)
            <p style="font-weight: bold;color:green;">Saldo disponível: R$ <?php echo number_format($saldototal,2,",",".") ?></p>
            @else 
            <p style="font-weight: bold;color:red;">Saldo disponível: R$ <?php echo number_format($saldototal,2,",",".") ?></p>
            @endif
            </div>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:green;">Saldo disponível da assertiva: R$ <?php echo number_format($saldototalassertiva,2,",",".") ?></p>
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
          <p style="font-size: 11px;color:black;font-weight: bold;">Comarcas</p>
          @foreach($comarcas as $comarca)
          <div class="invoice-item display-flex mb-1">
          <div class="invoice-item-filed row pt-1">

          <div class="col m2 s12 input-field">
            <span style="font-size: 11px;">Unidade federativa:</span>
            <input readonly type="text" value="{{$comarca->descricao}}" style="font-size: 10px;">

            </div>

            <div class="col m3 s12 input-field">
            <span style="font-size: 11px;">Comarca:</span>
            <input readonly type="text" value="{{$comarca->comarca}}" style="font-size: 10px;">
            </div>

            <div class="col m2 s12 input-field">
            <span style="font-size: 11px;">Região:</span>
            <input readonly type="text" value="{{$comarca->regiao}}" style="font-size: 10px;">
            </div>

            
            <div class="col m2 s12 input-field">
            <span style="font-size: 11px;">Porte:</span>
            <input readonly type="text" value="{{$comarca->porte}}" style="font-size: 10px;">
            </div>

            <div class="col m2 s12 input-field">
            <span style="font-size: 11px;">Capital:</span>
            <input readonly type="text" value="{{$comarca->capital}}" style="font-size: 10px;">
            </div>


          </div>
          </div>
          @endforeach

    

          <div class="divider mb-3 mt-3"></div>


          <p style="font-size: 11px;color:black;font-weight: bold;">Desmembramento de solicitações</p>
          <a href="#" onClick="adicionanovasolicitacao();" class="btn-floating btn-mini waves-effect waves-light tooltipped"data-position="right" data-tooltip="Clique aqui para adicionar uma nova solicitação de pesquisa patrimonial."  style="margin-left: 180px;margin-top:-40px;background-color: green;"><i class="material-icons">add</i></a>


          <div id="teste1">
          @foreach($solicitacoes as $solicitacao)
          <div class="invoice-item display-flex mb-1">
            
          <div class="invoice-item-filed row pt-1">

          <a href="#" onClick="removesolicitacao({{$solicitacao->id_tiposolicitacao}});" id="{{$solicitacao->id_tiposolicitacao}}" class="btn-floating btn-mini waves-effect waves-light tooltipped"data-position="left" data-tooltip="Clique aqui para excluir está solicitação de pesquisa patrimonial."  style="margin-left: 820px;margin-top:-20px;background-color: red;"><i class="material-icons">remove</i></a>
          <input type="hidden" id="{{$solicitacao->id_tiposolicitacao}}" name="tipossolicitacao[]" value="{{$solicitacao->id_tiposolicitacao}}">

          <div class="row">

            <div class="col m3 s12 input-field">
            <span style="font-size: 11px;">Solicitação:</span>
            <input type="text" readonly value="{{$solicitacao->descricao}}" style="font-size: 10px;" >
            </div>

            <div class="col s12 m3 input-field">
            <span style="font-size: 11px;">Comarca</span>
            <select id="comarcas" style="font-size: 10px;" name="comarcas[]" required="required" class="select2 browser-default">
            <option value="" selected>Selecione abaixo</option>
            @foreach($cidades as $cidade)
            <option value="{{$cidade->id}}">{{$cidade->municipio}}</option>
            @endforeach
            </select>
            </div>

            <div class="col m2 s12 input-field">
              <span id="labelvalor" name="labelvalor" style="font-size: 11px;">Valor:</span>
              <input type="text" onblur="findTotalservicos(this)" value="00,00" name="valor[]" id="valor"  placeholder="Valor únitario..." onKeyPress="javascript:return(moeda2(this,event))" pattern="(?:\.|,|[0-9])*" style="font-size:10px;"required >
            </div>

            <div class="col m3 s12 input-field">
              <span style="font-size: 11px;">Assertiva:</span>
              <select class="invoice-item-select browser-default" id="assertiva" name="assertiva[]" style="font-size:10px;">
              <option value="SIM" selected>Sim</option>
              <option value="Não">Não</option>
              </select>
            </div>


            </div>

            <div class="row">

            <div class="col m3 s12 input-field">
              <span style="font-size: 11px;">Possui Correspondente ?</span>
              <select class="invoice-item-select browser-default" name="possuicorrespondente[]" style="font-size:10px;">
              <option value="Não" selected>Não</option>
              <option value="Sim">Sim</option>
              </select>
            </div>

            <div class="col m3 s12 input-field">
              <span style="font-size: 11px;">Motivo:</span>
              <select class="invoice-item-select browser-default"  name="motivo[]" style="font-size:10px;">
              <option value="" selected></option>
              <option value="25">Pesquisa de bens</option>
              <option value="26">Retirada de matricula</option>
              </select>
            </div>

            <div class="col m3 s12 input-field">
              <span style="font-size: 11px;">Correspondente:</span>
              <select class="invoice-item-select browser-default" name="correspondente[]" style="font-size:10px;">
              <option value="" selected></option>
              @foreach($correspondentes as $correspondente)
              <option value="{{$correspondente->id}}">{{$correspondente->name}}</option>
              @endforeach
              </select>
            </div>

            <div class="col m2 s12 input-field">
              <span id="labelvalor" name="labelvalor" style="font-size: 11px;">Valor Correspondente:</span>
              <input type="text" onblur="findTotalcorrespondente(this)" value="00,00" id="valor_unitario" name="valorcorrespondente[]" placeholder="Valor únitario..." onKeyPress="javascript:return(moeda2(this,event))" pattern="(?:\.|,|[0-9])*" style="font-size:10px;"required >
            </div>

            </div>

            <div class="row">
          <div class="input-field col s12" style="margin-top: -15px;">
          <div class="form-group">
          <div class="form-group">
          <label class="control-label" style="font-size: 11px;">Informações adicionais:</label>
          <textarea rows="3" type="text" name="informacoesadicionais[]" class="form-control" placeholder="Campo livre." style="height: 5rem;text-align:left; overflow:auto;font-size: 10px;width:829px"></textarea>
          </div>
          </div>
          </div>   
          </div>  

            </div>
            </div>

            @endforeach
            <div class="divider mt-3 mb-3"></div>

            </div>





          <div class="divider mt-3 mb-3"></div>

          <div class="invoice-subtotal">

          <div class="row">
          <div class="input-field col s12" style="margin-top: -15px;">
          <div class="form-group">
          <div class="form-group">
          <label class="control-label" style="font-size: 11px;">Informações:</label>
          <textarea id="observacao" readonly rows="3" type="text" name="observacao" class="form-control" placeholder="Insira a observação abaixo." style="height: 3rem;text-align:left; overflow:auto;font-size: 10px;">{{$datas->Observacao}}</textarea>
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

        <div class="col m12 s12 input-field">
              <span id="labelvalor" name="labelvalor" style="font-size: 11px;">Valor total dos serviços:</span>
              <input type="text" readonly value="00,00" id="valortotalservicos" name="valortotalservicos" placeholder="0,00"  style="font-size:10px;"required >
        </div>

        <div class="col m12 s12 input-field">
              <span id="labelvalor" name="labelvalor" style="font-size: 11px;">Valor total dos correspondentes:</span>
              <input type="text" readonly value="00,00" id="valortotalcorrespondente" name="valortotalcorrespondente" placeholder="0,00"  style="font-size:10px;"required >
        </div>

        <div class="col m12 s12 input-field">
              <span id="labelvalor" name="labelvalor" style="font-size: 11px;">Valor total da solicitação:</span>
              <input type="text" readonly value="00,00" id="valortotal" name="valortotal" placeholder="0,00" style="font-size:10px;"required >
        </div>


        @if($datas->Cobravel == "SIM") 
        <p>
        <label>
        <input style="font-size: 11px;" name="statusescolhido" class="with-gap" value="gerarcobranca" type="radio" onClick="fecharjustificativa();"/>
        <span>Gerar cobrança ao cliente</span>
        </label> 
        </p>
        
        <p>
        <label>
        <input style="font-size: 11px;" name="statusescolhido" class="with-gap" value="solicitaradiantamento" type="radio" onClick="solicitaradiantamento();"/>
        <span>Solicitar adiantamento</span>
        </label> 
        </p>

        @else 

        <p>
        <label>
        <input style="font-size: 11px;" name="statusescolhido" class="with-gap" value="naocobravel" type="radio"/>
        <span>Enviar boletos ao financeiro</span>
        </label> 
        </p>
        @endif

        <p>
        <label>
        <input style="font-size: 11px;" class="with-gap"  name="statusescolhido" value="cancelar" type="radio" onClick="abrirjustificativa();" />
        <span>Cancelar solicitação</span>
        </label>
        </p>


        <div class="row" style="font-size:10px;display:none;" id="adiantamentodiv">
        <div class="input-field col m12 s12">
          <span style="font-size: 10px;">Informações do adiantamento:</span>
          <div class="form-group bmd-form-group is-filled">
          <textarea id="observacaoadiantamento" rows="3" type="text" name="observacaoadiantamento" class="form-control" placeholder="Insira a observação abaixo." style="height: 4rem;text-align:left; overflow:auto;font-size: 10px;"></textarea>
          </div>
        </div>

        <div class="input-field col m12 s12">
          <span style="font-size: 10px;">Anexar documento:</span>
          <div class="form-group bmd-form-group is-filled">
          <input type="file" name="anexoadiantamento" id="anexoadiantamento" style="font-size: 10px;" class="form-control" />
          </div>
        </div>

        </div>

        <div class="row" style="font-size:10px;display:none;" id="justificativadiv">
        <div class="input-field col m12 s12">
          <span style="font-size: 10px;">Observação:</span>
          <div class="form-group bmd-form-group is-filled">
          <textarea id="motivodescricao" rows="3" type="text" name="motivodescricao" class="form-control" placeholder="Insira a observação abaixo." style="height: 4rem;text-align:left; overflow:auto;font-size: 10px;"></textarea>
          </div>
        </div>
        </div>

          <div class="invoice-action-btn">
            <a  disabled name="btngravar" id="btngravar" onClick="abremodalconfirmacao();" style="background-color: gray;color:white;font-size:11px;" class="btn waves-effect waves-light display-flex align-items-center justify-content-center">
              <i class="material-icons mr-4">save</i>
              <span class="responsive-text" id="btngravar">Atualizar ficha</span>
            </a>
          </div>

        </div>
      </div>
  </div>

  <textarea id="observacao" rows="7" type="text" name="observacao" style="display:none;" required="required"  class="form-control" placeholder="Digite a observação">
Pesquisa Patrimonial
Número da solicitação: {{$datas->ID}}  
PRConta: {{$datas->PRConta}}
Nome do pesquisado: {{$datas->OutraParte}}
CPF/CNPJ: {{$datas->Codigo}}
Número do Processo: {{$datas->NumeroProcesso}}
Operação: {{$datas->ClienteFantasia}}
Nome Fantasia: 
Data da Solicitação: {{ date('d/m/Y H:i:s', strtotime($datas->DataSolicitacao)) }}              
Advogado solicitante: {{$datas->SolicitanteNome}}
Tipo de Solicitação: {{$datas->Classificacao}} 
Tipo de serviço: {{$datas->TipoServico}}</textarea>
      


  </form>
</section>

<div id="alertaconfirmacao" name="alertaconfirmacao" class="cd-popup" role="alert">
	<div class="cd-popup-container">
		<p style="font-weight: bold;">Deseja atualizar está solicitação de pesquisa patrimonial?</p>
		<ul class="cd-buttons">
			<li><a href="#" onClick="nao();" style="font-weight: bold;">Não</a></li>
			<li><a href="#" onClick="sim();" style="font-weight: bold;">Sim</a></li>
		</ul>
		<a onClick="nao();" class="cd-popup-close img-replace">Close</a>
	</div> 
</div>

<div id="alertaboleto" name="alertaboleto" class="cd-popup" role="alert">
	<div class="cd-popup-container">
		<p style="font-weight: bold;">Encontramos um boleto em aberto para este cliente gerado na data de hoje. Confirma a cobrança unica? </p>
		<ul class="cd-buttons">
			<li><a href="#" onClick="nao();" style="font-weight: bold;">Não</a></li>
			<li><a href="#" onClick="sim();" style="font-weight: bold;">Sim</a></li>
		</ul>
		<a onClick="nao();" class="cd-popup-close img-replace">Close</a>
	</div> 
</div>

<div id="alertasaldoassertiva" name="alertasaldoassertiva" class="cd-popup" role="alert">
	<div class="cd-popup-container">
		<p style="font-weight: bold;">O saldo na conta bancária da assertiva é menor que o valor da solicitação. Favor entrar em contato com o financeiro. </p>
		<ul class="cd-buttons" style="width: 800px">
			<li><a href="#" onClick="nao();" style="font-weight: bold;background-color:#52ca52">OK</a></li>
		</ul>
		<a onClick="nao();" class="cd-popup-close img-replace">Close</a>
	</div> 
</div>



<div id="alertaremover" name="alertaremover" class="cd-popup" role="alert">
<div class="cd-popup-container">
		<p style="font-weight: bold;">Confirma a remoção desta solicitação de pesquisa?</p>
		<ul class="cd-buttons">
			<li><a href="#" onClick="nao();" style="font-weight: bold;">Não</a></li>
			<li><a href="#" onClick="remover();" style="font-weight: bold;">Sim</a></li>
		</ul>
		<a onClick="nao();" class="cd-popup-close img-replace">Close</a>
	</div> 
</div>

<div id="alertatiposervico" name="alertatiposervico" class="cd-popup" role="alert">
	<div class="cd-popup-container">
		<p id="tagptiposervico" style="font-weight: bold;"></p>
		<ul class="cd-buttons" style="width: 800px">
			<li><a href="#" onClick="nao();" style="font-weight: bold;background-color:#52ca52">OK</a></li>
		</ul>
		<a onClick="nao();" class="cd-popup-close img-replace">Close</a>
	</div> 
</div>

<div id="alertaanexoadiantamento" name="alertaanexoadiantamento" class="cd-popup" role="alert">
	<div class="cd-popup-container">
  <p style="font-weight: bold;">Favor anexar um comprovante para está solicitação de adiantamento.</p>
		<p id="tagptiposervico" style="font-weight: bold;"></p>
		<ul class="cd-buttons" style="width: 800px">
			<li><a href="#" onClick="nao();" style="font-weight: bold;background-color:#52ca52">OK</a></li>
		</ul>
		<a onClick="nao();" class="cd-popup-close img-replace">Close</a>
	</div> 
</div>


@endsection

    <script src="{{ asset('/public/materialize/js/vendors.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/app-invoice.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>
    <script src="{{ asset('/public/materialize/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/form-select2.min.js') }}"></script>

<script>
function abremodalconfirmacao() {

  var clientecodigo = $('#clientecodigo').val();
  var _token = $('input[name="_token"]').val();
  var status = $('input[name=statusescolhido]:checked').val()
  var anexoadiantamento = $('#anexoadiantamento').val();


  $("#btngravar").attr("disabled", "disabled");

  if(status != "cancelar") {

     //Verifica se já possui um boleto gerado para este cliente na data de hoje
     $.ajax({
      type: 'POST',
      url:"{{ route('Painel.PesquisaPatrimonial.Nucleo.buscaboletogerado') }}",
      data:{clientecodigo:clientecodigo,_token:_token,},
      dataType: 'json',
      cache: false,
      success: function(response) {

       //Se sim alerta 
       if(response != 0) {
        $('#alertaboleto').addClass('is-visible');
       } else {
       $('#alertaconfirmacao').addClass('is-visible');
       }
    }
  });
      
  } 
  //Verifica se tem o anexo
  if(status == "solicitaradiantamento") {

    if ($('#anexoadiantamento').val().length != 0){

      $('#alertaconfirmacao').addClass('is-visible');

    } else {
      $('#alertaanexoadiantamento').addClass('is-visible');

    }

  }
  
  else {
    $('#alertaconfirmacao').addClass('is-visible');

  }

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

  html += '<div class="col m3 s12 input-field">';
  html += '<span style="font-size: 11px;">Solicitação:</span>';
  html += '<select class="invoice-item-select browser-default" name="tipossolicitacao[]" style="font-size:10px;">'
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

  html += '</div>';

  html += '<div class="row">';

  html += '<div class="col m3 s12 input-field">';
  html += '<span style="font-size: 11px;">Possui correspondente:</span>';
  html += '<select class="invoice-item-select browser-default" name="possuicorrespondente[]" style="font-size:10px;">'
  html += '<option value="Não" selected>Não</option><option value="Sim">Sim</option>';
  html += '</select>';
  html += '</div>';

  html += '<div class="col m3 s12 input-field">';
  html += '<span style="font-size: 11px;">Tipo de serviço:</span>';
  html += '<select class="invoice-item-select browser-default" name="motivo[]" style="font-size:10px;">'
  html += '<option value="" selected></option><option value="25">Pesquisa de bens</option><option value="26">Retirada de matricula</option>"';
  html += '</select>';
  html += '</div>';

  html += '<div class="col m3 s12 input-field">';
  html += '<span style="font-size: 11px;">Correspondente:</span>';
  html += '<select class="invoice-item-select browser-default" name="correspondente[]" style="font-size:10px;">'
  html += '<option value="" selected></option>';
  @foreach($correspondentes as $correspondente)
  html += '<option value="{{$correspondente->id}}">{{$correspondente->name}}</option>';
  @endforeach
  html += '</select>';
  html += '</div>';

  html += '<div class="col m3 s12 input-field">';
  html += '<span style="font-size: 11px;">Valor do correspondente:</span>';
  html += '<input type="text" onblur="findTotalcorrespondente(this)" value="00,00" name="valorcorrespondente[]" id="valor"  placeholder="Valor únitario..." onKeyPress="javascript:return(moeda2(this,event))" pattern="(?:\.|,|[0-9])*" style="font-size:10px;"required >'
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
  $("#btngravar").removeAttr("disabled");
 }
</script>

<script>
  $(document).on("change", "input[name=statusescolhido]", function() {

  $("#btngravar").removeAttr('disabled');

  var status =  $('input[name=statusescolhido]:checked').val()
  var gerarcobranca = "Prezado, ao selecionar a opção [Gerar cobrança ao cliente], será encaminhado ao cliente um boleto de cobrança, para que seja feito o pagamento.";
  var adiantamento = "Prezado, ao selecionar a opção [Solicitar adiantamento], será encaminhado para a revisão do supervisor de pesquisa patrimonial e equipe do financeiro. ";
  var naocobravel = "Prezado, ao selecionar a opção [Enviar ao financeiro], será encaminhado os boletos de serviço para pagamento pela equipe do financeiro. ";
  var cancelar = "Prezado, ao selecionar a opção [Cancelar solicitação], a solicitação de pesquisa patrimonial será cancelada e o solicitante receberá e-mail e notificação. Se for uma solicitação [Extrajuidicial] a pasta informada pelo solicitante será desativada."

  //Altera o label do botão conforme a opção marcada
  if(status == "gerarcobranca") {
    $('#btngravar').html('Gerar cobrança');
    $('#tagptiposervico').html(gerarcobranca);
    document.getElementById("adiantamentodiv").style.display = "none";         
    $('#alertatiposervico').addClass('is-visible');

  } else if(status == "solicitaradiantamento") {

      $('#btngravar').html('Solicitar adiantamento');
      $('#tagptiposervico').html(adiantamento);
      document.getElementById("adiantamentodiv").style.display = "";  
      $('#alertatiposervico').addClass('is-visible');

  } else if(status == "naocobravel") {
    $('#btngravar').html('Enviar ao financeiro');
    $('#tagptiposervico').html(naocobravel);
    document.getElementById("adiantamentodiv").style.display = "none";         
    $('#alertatiposervico').addClass('is-visible');

  } else {
    $('#btngravar').html('Cancelar solicitação');
    $('#tagptiposervico').html(cancelar);
    document.getElementById("adiantamentodiv").style.display = "none";         
    $('#alertatiposervico').addClass('is-visible');

  }

  
});
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
function removesolicitacao(id_solicitacao) {
   var id_matrix = {{$id_matrix}};
   document.getElementById('idtemp').value=(id_solicitacao);
   $('#alertaremover').addClass('is-visible');
}
</script>

<script>
function remover() {
   $('.cd-popup').removeClass('is-visible');
   var id_solicitacao = $('#idtemp').val();
   var id_matrix = {{$id_matrix}};
   var _token = $('input[name="_token"]').val();

   $.ajax({
      type: 'POST',
      url:"{{ route('Painel.PesquisaPatrimonial.Nucleo.removesolicitacao') }}",
      data:{id_matrix:id_matrix,id_solicitacao:id_solicitacao,_token:_token,},
      // dataType: 'json',
      cache: false,
      success: function(response) {

         //Refresh na pagina 
         location.reload();
      }
   });
}
</script>

<script type="text/javascript">
function findTotalservicos(teste){
   var valor_adicionado = teste.value;
   var valor = parseFloat($("#valortotalservicos").val().replace('.', ',').replace(',','.'));
   valor += parseFloat(valor_adicionado.replace('.', '').replace(',','.'));

   var correspondente = parseFloat($('#valortotalcorrespondente').val().replace('.', ',').replace(',','.'));

   var valortotal = correspondente + valor;

   document.getElementById('valortotal').value=(valortotal.toFixed(2));
   document.getElementById('valortotalservicos').value=(valor.toFixed(2));



}

 </script>

<script type="text/javascript">
function findTotalcorrespondente(teste){
   var valor_adicionado = teste.value;
   var valor = parseFloat($("#valortotalcorrespondente").val().replace('.', ',').replace(',','.'));
   valor += parseFloat(valor_adicionado.replace('.', '').replace(',','.'));


   var servicos = parseFloat($('#valortotalservicos').val().replace('.', ',').replace(',','.'));

   var valortotal = servicos + valor;

   document.getElementById('valortotalcorrespondente').value=(valor.toFixed(2));
   document.getElementById('valortotal').value=(valortotal.toFixed(2));

}

 </script>

