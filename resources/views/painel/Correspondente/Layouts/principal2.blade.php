@extends('painel.Layout.header')
@section('title') Dashboard Correspondente @endsection <!-- Titulo da pagina -->

@section('header')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<link rel="stylesheet" href="{{ asset('/public/materialize/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('/public/materialize/css/select2-materialize.css') }}">
<link rel="stylesheet" href="{{ asset('/public/plugins/fontawesome-free/css/fontawesome.css') }}">

<style>
  .div-menus{
    border-radius: 9px;
    border: 1px solid gray;
    padding: 2px 0 !important;
    margin: 5px 0.5%;
    width: 15.66%;
    text-align: center;
  }

  .select2-container--default .select2-selection--single .select2-selection__arrow b{
    border-color: #888 transparent transparent transparent !important;
    border-style: solid !important;
    border-width: 5px 4px 0 4px !important;
    height: 0 !important;
    left: 50% !important;
    margin-left: -4px !important;
    margin-top: -2px !important;
    position: absolute !important;
    top: 50% !important;
    width: 0 !important;
  }

  .div-menus.no-border{
    border: none;
    margin-top: 5px;
  }

  .div-menus p{
    margin: 0;
    margin-top: 2px;
    font-size: 15px;
    font-weight: bold;
  }

  #div-table{
    display: inline-block;
    overflow-y: scroll;
    max-height: 450px;
    width: 100%;
    padding: 15px;
  }

  body::-webkit-scrollbar-track {
    background-color: #F4F4F4;
  }
  body::-webkit-scrollbar {
    width: 6px;
    background: #F4F4F4;
  }
  body::-webkit-scrollbar-thumb {
      background: #dad7d7;
  }

  #div-table::-webkit-scrollbar-track {
    background-color: #F4F4F4;
  }

  #div-table::-webkit-scrollbar {
    width: 6px;
    background: #F4F4F4;
  }

  #div-table::-webkit-scrollbar-thumb {
    background: #dad7d7;
  }

  .modal::-webkit-scrollbar-track {
    background-color: #F4F4F4;
  }

  .modal::-webkit-scrollbar {
    width: 6px;
    background: #F4F4F4;
  }

  .modal::-webkit-scrollbar-thumb {
    background: #dad7d7;
  }

  .div-menus label{
    font-size: 11px;
  }

  table {
    text-align: left;
    position: relative;
    border-collapse: collapse; 
    border: 1px solid #c3c5cd;
    padding: 5px;
    border-radius: 8px;
  }

  th, td {
    padding: 0.25rem;
  }
  tr.red th {
    background: red;
    color: white;
  }
  tr.green th {
    background: green;
    color: white;
  }
  tr.purple th {
    background: purple;
    color: white;
  }

  th {
    background: white;
    position: sticky;
    padding-top: 3px;
    padding-bottom: 3px;
    top: -15px; /* Don't forget this, required for the stickiness */
    box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
  }

  .select-wrapper input.select-dropdown{
    color: #adadad;
    font-size: 0.93rem;
  }

  .dropdown-content li>a, .dropdown-content li>span{
    color: #353535
  }

  .select-wrapper input.select-dropdown{
    border-bottom: none;
  }

  .select2 + label{
    top: -34px;
    font-size: 11px !important;
  }

  .div-icon{
    display: flex;
    float: right !important;
    border-left: 1px solid #c7c7c7;
    height: 60px;
    cursor: pointer;
  }

  .modal-left{
    margin: 0;
    margin-left: auto;
    height: 100% !important;
    max-height: 100% !important;
    width: 350px !important;
    top: 0px !important;
  }

  .dropdown-content{
    width: max-content !important;
    height:auto !important;
  }

  .div-correspondente{
    padding: 1rem 1.3rem !important;
    width: 24%;
    border-radius: 9px;
    box-shadow: 2px 2px 5px 2px rgb(0 0 0 / 5%);
    background-color: white;
    margin: 0.5%;
  }

  .div-correspondente .icon-config{
    position: relative;
    top: -10px;
    right: -12px;
    cursor: pointer;
    float: right;
    margin: 0;
  }

  .s-custom{
    width: 20% !important;
  }

  .div-correspondente .icon-config i{
    font-size: 18px;
    color: grey;
  }

  .div-correspondente .header{
    display: flex;
  }

  .div-correspondente .header .rating{
    font-size: 12px;
    margin-right: 5px;
    /* padding-top: 2px; */
  }

  .div-correspondente .header .icon-rating{
    font-size: 16px;
    margin-left: -2px;
  }

  .div-correspondente .header .name{
    font-size: 12px;
    color: #4c4c4c;
    letter-spacing: 0.4px;
    font-weight: bold;
  }

  .div-correspondente .body{
    display: grid;
  }

  .div-correspondente .body .service,
  .div-correspondente .body .value,
  .div-correspondente .body .location{
    font-size: 12px;
    color: gray;
    letter-spacing: 0.6px;
  }

  .div-correspondente .body .service b,
  .div-correspondente .body .value b,
  .div-correspondente .body .location b{
    color: #4c4c4c;
  }

  .div-correspondente .body .location{
    font-size: 12px;
    color: #4c4c4c;
  }

  .ellipsed{
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }

  .dropdown-content{
    max-height: 400px !important;
  }

  .dropdown-content::-webkit-scrollbar-track {
    background-color: #F4F4F4;
  }

  .dropdown-content::-webkit-scrollbar {
    width: 6px;
    background: #F4F4F4;
  }

  .dropdown-content::-webkit-scrollbar-thumb {
    background: #dad7d7;
  }

  .div-correspondente .body .location span{
    font-size: 11px;
    color: gray;
  }

  .div-icon i{
    margin: auto;
    font-size: 25px !important;
  }

  .rating-star{
    font-size: 23px;
    cursor: pointer;
  }

  .rating-star.active{
    color: #f9f928;
  }

  .inputfile {
    width: 0.1px;
    height: 0.1px;
    opacity: 0;
    overflow: hidden;
    position: absolute;
    z-index: -1;
  }

  .inputfile + label {
    font-size: 13px;
    font-weight: 700;
    padding: 10px 15px;
    color: white;
    background-color: #505050;
    display: inline-block;
    cursor: pointer;
    margin-top: 37px;
    text-align: center;
    display: flex;
  }

  .inputfile + label i{
    margin-top: auto;
    margin-bottom: auto;
    /* margin-right: auto; */
    font-size: 21px;
  }

  .inputfile + label strong{
   position: relative;
   /* top: 3px; */
  }

  .inputfile:focus + label {
    outline: 1px dotted #000;
    outline: -webkit-focus-ring-color auto 5px;
  }

  .inputfile + label * {
    pointer-events: none;
  }

  .inputfile:focus + label,
  .inputfile + label:hover {
    background-color: gray;
    box-shadow: 2px 2px 5px 1px rgb(0 0 0 / 3%);
  }

  .input-field label{
    font-size: 12px;
    padding-left: 5px;
  }

  .input-field label.active{
    font-size: 11px !important;
    transform: translateY(-18px) scale(.8) !important;
    color: #9e9e9e;
  }

  .input-field input,
  .input-field textarea{
    background-color: white !important;
  }

  .apexcharts-legend-marker{
    height: 10px !important;
    width: 10px !important;
  }

  .apexcharts-legend::-webkit-scrollbar{
    height: 6px;
  }

  .apexcharts-legend-text{
    font-size: 11px !important;
  }

  .apexcharts-canvas text{
    font-size: 12px;
  }

  .btn-custom{
    font-size: 13px;
    border: 1px solid gray;
    padding: 0 15px;
    text-align: center;
  }

  .btn-custom i{
    font-size: 18px;
    margin-right: 5px;
  }

  .select2-selection.select2-selection--single{
    height: 41px !important; 
  }

  .select2-container--default .select2-selection--single .select2-selection__rendered{
    line-height: 41px;
    padding-left: 4px;
  }

  .select2-container--default .select2-selection--multiple, 
  .select2-container--default .select2-selection--single, 
  .select2-container--default.select2-container--focus .select2-selection--multiple{
    border: 1px solid #9e9e9e;
    border-radius: 5px;
  }

  input:not([type]), input[type=date]:not(.browser-default), 
  input[type=datetime-local]:not(.browser-default), 
  input[type=datetime]:not(.browser-default), 
  input[type=email]:not(.browser-default),
  input[type=number]:not(.browser-default), 
  input[type=password]:not(.browser-default), 
  input[type=search]:not(.browser-default), 
  input[type=tel]:not(.browser-default), 
  input[type=text]:not(.browser-default), 
  input[type=time]:not(.browser-default), 
  input[type=url]:not(.browser-default), 
  textarea.materialize-textarea{
    border: 1px solid #9e9e9e;
    border-radius: 5px;
    height: 41px;
  }

  .select2-search__field{
    border-radius: 0px !important;
  }

  .graph-scroll{
    min-height: 250px !important;
    max-height: 250px;
    overflow-y: auto;
    overflow-x: hidden;
  }

  .graph-scroll::-webkit-scrollbar-track {
    background-color: #F4F4F4;
  }

  .graph-scroll::-webkit-scrollbar {
    width: 6px;
    background: #F4F4F4;
  }

  .graph-scroll::-webkit-scrollbar-thumb {
    background: #dad7d7;
  }

  .title-graph{
    font-size: 12px;
    line-height: 1.5;
    color: #6b6f82;
    font-family: Helvetica, Arial, sans-serif;
    /* font-weight: bold; */
    text-anchor: start;
    dominant-baseline: auto;
    margin-left: 25px;
    font-weight: 900;
    fill: rgb(55, 61, 63);
  }

  .div-loading{
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1200;
    display: block;
    background-color: #0606065e;
    height: 100%;
    width: 100%;
}
  }

  .div-loading .image{
    width: 80px;
    position: relative;
    top: 40%;
    left: 50%;
  }

  .div-loading h6{
    font-size: 20px;
    margin-left: 60px;
    position: relative;
    top: 40%;
    left: 0;
    text-align: center;
    color: black;
    font-weight: bold;
  }

  .div-group{
    position: sticky;
    top: 64px;
    margin-top: -5px;
    z-index: 1000;
  }

  .btn-group .btn {
    background-color: #ffffffee;
    font-size: 13px;
    border: 1px solid gray;
    padding: 0 15px;
    cursor: pointer;
    float: left;
    border-radius: 0;
    border-top: none;
  }

  .btn-group .btn.active{
    background-color: #808080ee;
    color: white;
  }

  .btn-group .btn:not(:last-child) {
    border-right: none;
  }

  .btn-group .btn:first-child{
    border-bottom-left-radius: 5px;
    /* border-top-left-radius: 5px; */
  }

  .btn-group .btn:last-child{
    border-bottom-right-radius: 5px;
    /* border-top-right-radius: 5px; */
  }

  .btn-group:after {
    content: "";
    clear: both;
    display: table;
  }

  .btn-group .btn:hover {
    background-color: #808080ee;
    color: white;
  }

  .btn-group-fixed{
    position: fixed;
    bottom: 15px;
    right: 15px;
    z-index: 1000;
  }

  .btn-group-fixed .btn {
    background-color: #ffffffee;
    font-size: 13px;
    border: 1px solid gray;
    padding: 0;
    cursor: pointer;
    float: left;
    border-radius: 0;
  }

  .btn-group-fixed .btn i{
    padding: 0 15px;
  }

  .btn-group-fixed .btn.active{
    background-color: #808080ee;
    color: white;
  }

  .btn-group-fixed .btn:not(:last-child) {
    border-right: none;
  }

  .btn-group-fixed .btn:first-child{
    border-bottom-left-radius: 5px;
    border-top-left-radius: 5px;
  }

  .btn-group-fixed .btn:last-child{
    border-bottom-right-radius: 5px;
    border-top-right-radius: 5px;
  }

  .btn-group-fixed:after {
    content: "";
    clear: both;
    display: table;
  }

  .btn-group-fixed .btn:hover {
    background-color: #808080ee;
    color: white;
  }

  @keyframes animate-360{
    0% {transform: rotate(0deg);}
    25% {transform: rotate(90deg);}
    50% {transform: rotate(180deg);}
    75% {transform: rotate(270deg);}
    100% {transform: rotate(360deg);}
  }

  @keyframes animate-up{
    0% {transform: scale(1.05);}
    50% {transform: scale(1.2);}
    100% {transform: scale(1.05);}
  }

  .animate-up:hover{
    animation-name: animate-up;
    animation-duration: 2s;
    animation-iteration-count: infinite;
  }

  .animate-360:hover{
    animation-name: animate-360;
    animation-duration: 2.6s;
    animation-iteration-count: infinite;
  }

</style>
@endsection

@section('header_title')
Dashboard Correspondentes
@endsection

@section('submenu')
<li class="active">Painel Principal</li>
@endsection

@section('body')
  <div class="row">
    <div class="col s12">
      <div class="card">
        <div class="row">
          <div class="col s12">
            <div class="col div-menus no-border">
              <p id="count-bh"></p>
              <label>Belo Horizonte</label>
            </div>
            <div class="col div-menus no-border">
              <p id="count-ft"></p>
              <label>Fortaleza</label>
            </div>
            <div class="col div-menus no-border">
              <p id="count-mn"></p>
              <label>Manaus</label>
            </div>
            <div class="col div-menus no-border">
              <p id="count-rj"></p>
              <label>Rio de Janeiro</label>
            </div>
            <div class="col div-menus no-border">
              <p id="count-sp"></p>
              <label>São Paulo</label>
            </div>
            <div class="col div-icon" onclick="openFilter();">
              <i class="material-icons">filter_alt</i>
            </div>
            <div class="col div-icon" onclick="$('#modal-correspondetes').modal('open');">
              <i class="material-icons">person_search</i>
            </div>
          </div>
        </div>
      </div>
      <div class="card" style="padding: 5px 0px;">
        <div class="row">
          <div class="col s12">
            <div class="col div-menus">
              <label>Valor Correspondente</label>
              <p id="valor_correspondente"></p>
            </div>
            <div class="col div-menus">
              <label>Total Correspondente</label>
              <p id="total_correspondente"></p>
            </div>
            <div class="col div-menus">
              <label>Solicitações de Pagamento</label>
              <p id="solicitacao_pagamento"></p>
            </div>
            <div class="col div-menus">
              <label>Solicitações Válidas</label> <!-- não faço ideia -->
              <p id="solicitacao_valida"></p>
            </div>
            <div class="col div-menus">
              <label>Consultivo</label>
              <p id="consultivo"></p>
            </div>
            <div class="col div-menus">
              <label>Contencioso</label>
              <p id="contencioso"></p>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col s12">
            <div class="col div-menus">
              <label>Processos/Projetos Ativos</label>
              <p id="projetos_ativos"></p>
            </div>
            <div class="col div-menus">
              <label>Processos Ativos</label>
              <p id="processos_ativos"></p>
            </div>
            <div class="col div-menus">
              <label>Processos Encerrados</label>
              <p id="processos_encerrados"></p>
            </div>
            <div class="col div-menus">
              <label>Processos Suspensos</label>
              <p id="processos_suspensos"></p>
            </div>
            <div class="col div-menus">
              <label>Total de abrangência UF</label>
              <p id="total_abrangencia"></p>
            </div>
            <div class="col div-menus">
              <label>Total de abrangência Comarca</label>
              <p id="abrangencia_comarca"></p>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col s12">
            <div class="col div-menus">
              <label>Valores Reembolsáveis</label>
              <p id="valores_reembolsaveis"></p>
            </div>
            <div class="col div-menus">
              <label>Valores Não Reembolsáveis</label>
              <p id="nao_reembolsaveis"></p>
            </div>
            <div class="col div-menus">
              <label>Total Equipe(s)</label>
              <p id="total_equipe"></p>
            </div>
            <div class="col div-menus">
              <label>Total Clientes</label>
              <p id="total_clientes"></p>
            </div>
            <div class="col div-menus">
              <label>Total Grupo Clientes</label>
              <p id="grupo_clientes"></p>
            </div>
            <div class="col div-menus">
              <label>Total Segmento</label>
              <p id="total_segmento"></p>
            </div>
          </div>
        </div>
      </div>
      <div class="card" style="padding: 5px 0px;"> <!-- Inicio chart -->
        <div class="btn-group-fixed">
          <a href="#!" style="" class="btn waves-effect btn-flat"><i style="font-size: 23px;" class="material-icons animate-up">arrow_upward</i></a>
          <a href="#!" style="" class="btn waves-effect btn-flat"><i style="font-size: 23px;" class="material-icons animate-360">autorenew</i></a>
        </div>
        <div class="row div-group" style="display: flex; margin-bottom: 20px;">
          <div class="col s3" style="margin: auto;">
            <div class="btn-group">
              <a href="#!" style="" class="btn active waves-effect btn-flat">Valor</a>
              <a href="#!" style="" class="btn waves-effect btn-flat">Unidade</a>
              <a href="#!" style="" class="btn waves-effect btn-flat">Valor p/ Unidade</a>
            </div>
          </div>
        </div>
        <div class="row" style="padding-top: 20px;">
          <div class="col s4">
            <label style="margin-left: 25px;">Reembolsável</label>
            <div class="graph" id="reembolsavel" style=""></div>
          </div>
          <div class="col s4">
            <label>Pasta/Projeto</label>
            <div class="graph" id="pasta" style=""></div>
          </div>
          <div class="col s4">
            <label>Solicitação por Unidade</label>
            <div class="graph" id="solicitacao_unidade" style=""></div>
          </div>
        </div>
        <div class="row" style="padding-top: 20px;">
          <div class="col s4">
            <label style="margin-left: 25px;">Status Processo</label>
            <div class="graph" id="status_processo" style=""></div>
          </div>
          <div class="col s4">
            <label>Esfera</label>
            <div class="graph" id="esfera" style=""></div>
          </div>
          <div class="col s4">
            <label>Rito</label>
            <div class="graph" id="rito" style=""></div>
          </div>
        </div>
        <div class="row">
          <div class="col s2">
          </div>
          <div class="col s4">
            <label style="margin-left: 25px;">Tipo de Serviço</label>
            <div class="graph" id="tipo_servico" style=""></div>
          </div>
          <div class="col s4">
            <label>Fase do Projeto/Processo</label>
            <div class="graph" id="fase_projeto" style=""></div>
          </div>
        </div>
        <div class="row" style="padding-top: 20px;">
          <div class="col s12">
            <div class="graph" id="valor_uf" style=""></div>
          </div>
        </div>
        <div class="row" style="padding-top: 20px;">
          <div class="col s12">
            <div class="graph" id="valor_data_solicitacao" style=""></div>
          </div>
        </div>
        <div class="row" style="padding-top: 20px;">
          <!-- <div class="col s4">
            <label style="margin-left: 25px;">Porcentagem por Pola Pasta</label>
            <div class="graph" id="porc_polo_pasta" style=""></div>
          </div> -->
          <div class="col s6">
            <label>Valor por Polo Pasta</label>
            <div class="graph" id="valor_polo_pasta" style=""></div>
          </div>
          <div class="col s6">
            <label>Total por Polo/Unidade</label>
            <div class="graph" id="total_polo" style=""></div>
          </div>
        </div>
        <div class="row" style="padding-top: 30px;">
          <div class="col s6">
            <label class="title-graph">Valor por cliente</label>
            <div class="graph graph-scroll" id="valor_cliente" style=""></div>
          </div>
          <div class="col s6">
            <label class="title-graph">Valor por Grupo Econômico</label>
            <div class="graph graph-scroll" id="valor_grupo" style=""></div>
          </div>
        </div>
        <div class="row" style="padding-top: 20px;">
          <div class="col s4">
            <label style="margin-left: 25px;">Percentual por Seguimento</label>
            <div class="graph" id="porc_segmento" style=""></div>
          </div>
          <div class="col s4">
            <label style="">Percentual Sobre o Valor da Causa por Seguimento</label>
            <div class="graph" id="porc_causa_segmento" style=""></div>
          </div>
          <div class="col s4">
              <label style="">Percentual Sobre o Valor da Causa por Unidade</label>
              <div class="graph" id="porc_causa_unidade" style=""></div>
          </div>
        </div>
        <div class="row" style="padding-top: 20px;">
          <div class="col s12">
            <div class="graph" id="total_valor_setor" style=""></div>
          </div>
        </div>
        <!-- <div class="row" style="padding-top: 20px;">
          <div class="col s12">
            <div class="graph" id="total_setor" style=""></div>
          </div>
        </div> -->
        <div class="row" style="padding-top: 20px;">
          <div class="col s6">
            <div class="graph" id="valor_esfera" style=""></div>
          </div>
          <div class="col s6">
            <div class="graph" id="valor_seguimento" style=""></div>
          </div>
        </div>
        <div class="row" style="padding-top: 20px;">
          <div class="col s12">
            <label class="title-graph">Correspondentes - Valores por Correspondente</label>
            <div class="graph-scroll graph" id="valor_p_correspondente" style=""></div>
          </div>
        </div>
        <div class="row" style="padding-top: 20px;">
          <div class="col s2">
          </div>
          <div class="col s4">
            <div class="graph" id="projetos_uf" style=""></div>
          </div>
          <div class="col s4">
            <div class="graph" id="correspondente_uf" style=""></div>
          </div>
        </div>
        <div class="row" style="padding-top: 20px;">
          <div class="col s2">
          </div>
          <div class="col s4">
            <div class="graph" id="projetos_comarca" style=""></div>
          </div>
          <div class="col s4">
            <div class="graph" id="correspondente_cidade" style=""></div>
          </div>
        </div> <!-- fim graficos -->
        <div class="row" style="padding-top: 20px;">
          <div class="col s12" style="padding: 0 1.8rem; padding-top: 20px;">
            <form id="form-tabela">
              <div class="col s1"></div>
              <div class="col s2 input-field">
                <select multiple name="uf_correspondente[]" class="select2 browser-default" value="" id="table-uf_correspondente" type="text">
                </select>
                <label>UF Correspondente</label>
              </div>
              <div class="col s2 input-field">
                <select multiple name="cidade_correspondente[]" class="select2 browser-default" value="" id="table-cidade_correspondente" type="text">
                </select>
                <label>Cidade Correspondente</label>
              </div>
              <div class="col s2 input-field">
                <select multiple name="uf_projeto[]" class="select2 browser-default" value="" id="table-uf_projeto" type="text">
                </select>
                <label>UF Projeto</label>
              </div>
              <div class="col s2 input-field">
                <select multiple name="comarca_projeto[]" class="select2 browser-default" value="" id="table-comarca_projeto" type="text">
                </select>
                <label>Comarca Projeto</label>
              </div>
              <div class="col s2">
                <button style="margin-top: 17px;" type="button" onclick="filtrarTabela();" class="waves-effect waves-light btn-flat btn-custom">Filtrar <i class="material-icons left">search</i></button>
              </div>
            </form>
          </div>
          <div class="col s12">
            <div id="div-table" class="responsive-table">
              <table id="table-correspondente" class="table white border-radius-4 pt-1 customize-table">
                <thead>
                  <tr>
                    <th style="font-size: 11px">Numero</th>
                    <th style="font-size: 11px">Correspondente</th>
                    <th style="font-size: 11px">UF Correspondente</th>
                    <th style="font-size: 11px">Cidade Correspondente</th>
                    <th style="font-size: 11px">Data de Solicitação</th>
                    <th style="font-size: 11px">Valor</th>
                    <th style="font-size: 11px">Tipo de Serviço prestado</th>
                    <th style="font-size: 11px">UF Projeto</th>
                    <th style="font-size: 11px">Comarca Projeto</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="modal-filtros" class="modal modal-left" style="width: 1200px;height: 100%;">
    <div class="modal-content">
      <div class="row">
        <div class="col s12 m12">
          <button type="button" style="line-height: 26px; height: 26px; padding: 0 1rem; margin-bottom: 10px; float: right; margin-right: 0 !important;" class="btn waves-effect mr-sm-1 mr-2 modal-close red" style="">
            <i class="material-icons">close</i>
          </button>
          <div class="row">
            <form id="form-filtro">
              <div class="col s12 input-field">
                <select multiple name="ano[]" class="select2 browser-default" id="form-ano">
                </select>
                <label>Ano</label>
              </div>
              <div class="col s12 input-field">
                <select multiple name="cliente[]" class="select2 browser-default" value="" id="form-cliente" type="text">
                </select>
                <label>Cliente</label>
              </div>
              <div class="col s12 input-field">
                <select multiple name="grupo_cliente[]" class="select2 browser-default" value="" id="form-grupo_cliente" type="text">
                </select>
                <label>Grupo Cliente</label>
              </div>
              <div class="col s12 input-field">
                <select multiple name="negocio[]" class="select2 browser-default" value="" id="form-negocio" type="text">
                </select>
                <label>Negocio</label>
              </div>
              <div class="col s12 input-field">
                <select multiple name="criacao[]" class="select2 browser-default" value="" id="form-criacao" type="text">
                </select>
                <label>Descrição Mês Criação</label>
              </div>
              <div class="col s12 input-field">
                <select multiple name="polo_pasta[]" class="select2 browser-default" value="" id="form-polo_pasta" type="text">
                </select>
                <label>Polo Pasta</label>
              </div>
              <div class="col s12 input-field">
                <select multiple name="alocacao[]" class="select2 browser-default" value="" id="form-alocacao" type="text">
                </select>
                <label>Unidade de Alocação sol</label>
              </div>
              <div class="col s12 input-field">
                <select multiple name="setor[]" class="select2 browser-default" value="" id="form-setor" type="text">
                </select>
                <label>Setor</label>
              </div>
              <div class="col s12 input-field">
                <select multiple name="tipo_pasta[]" class="select2 browser-default" value="" id="form-tipo_pasta" type="text">
                </select>
                <label>Tipo Pasta</label>
              </div>
              <div class="col s12 input-field">
                <select multiple name="status[]" class="select2 browser-default" value="" id="form-status" type="text">
                </select>
                <label>Status Processo</label>
              </div>
              <div class="col s12 input-field">
                <select multiple name="rito[]" class="select2 browser-default" value="" id="form-rito" type="text">
                </select>
                <label>Rito</label>
              </div>
              <div class="col s12 input-field">
                <select multiple name="reembolsavel[]" class="select2 browser-default" value="" id="form-reembolsavel" type="text">
                </select>
                <label>Reembolsavel-SN</label>
              </div>
              <div class="col s12">
                <button type="button" style="" onclick="filtrarDashboard();" class="waves-effect waves-light btn-flat btn-custom">Filtrar <i class="material-icons left">search</i></button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="modal-correspondetes" class="modal modal-left" style="width: 80% !important; height: 100%;">
    <div class="modal-content">
      <div class="row">
        <div class="col s12 m12">
          <button type="button" style="line-height: 26px; height: 26px; padding: 0 1rem; margin-bottom: 10px; float: right; margin-right: 0 !important;" class="btn waves-effect mr-sm-1 mr-2 modal-close red" style="">
            <i class="material-icons">close</i>
          </button>
          <label style="font-size: 16px;">Lista de Correspondentes</label>
          <form id="form-buscar_correspondentes">
            <div class="row" style="margin-top: 20px;">
              <div class="col s2">
                <div class="input-field">
                  <select multiple name="uf[]" data-placeholder="Select a state..." class="select2 browser-default" id="correspondente-uf">
                    @foreach($ufs as $uf)
                      <option value="{{$uf->UF}}">{{$uf->UF}}</option>
                    @endforeach
                  </select>
                  <label>UF</label>
                </div>
              </div>
              <div class="col s2 input-field">
                <select multiple name="comarca[]" class="select2 browser-default" value="" id="correspondente-comarca" type="text">
                  <!-- <option value="0" disabled>Selecione uma opção</option> -->
                </select>
                <label>Comarca</label>
              </div>
              <div class="col s2 input-field">
                <select name="valor" class="select2 browser-default" id="correspondente-valor" type="text">
                  <option value="">Selecione uma opção</option>
                  <option value="0-151">até R$150</option>
                  <option value="150-301">R$151 a R$300</option>
                  <option value="300-601">R$301 a R$600</option>
                  <option value="600-901">R$601 a R$900</option>
                  <option value="900-5001">R$901 a R$5000</option>
                </select>
                <label>Valor</label>
              </div>
              <div class="col s2 input-field">
                <select multiple name="classificacao[]" class="select2 browser-default" value="" id="correspondente-classificacao" type="text">
                  <option value="1">1 Estrela</option>
                  <option value="2">2 Estrelas</option>
                  <option value="3">3 Estrelas</option>
                  <option value="4">4 Estrelas</option>
                  <option value="5">5 Estrelas</option>
                </select>
                <label>Classificação</label>
              </div>
              <div class="col s2 input-field">
                <select multiple name="tipo_servico[]" class="select2 browser-default" value="" id="correspondente-tipo_servico" type="text">
                  @foreach($topservico_descricao as $servico)
                    <option value="{{$servico->descricao}}">{{$servico->descricao}}</option>
                  @endforeach
                </select>
                <label>Tipo de serviço</label>
              </div>
              <div class="col s2" style="padding-right: 0; padding-top: 10px;">
                <button data-tooltip="Filtrar Correspondentes" style="margin-top: 10px; line-height: 26px; height: 26px; padding: 0 1rem;" type="button" onclick="buscarCorrespondentes();" class="waves-effect waves-light btn grey tooltipped"><i class="material-icons" style="font-size: 15px;">search</i></button>
                <button data-tooltip="Limpar Filtro" style="margin-top: 10px; line-height: 26px; height: 26px; padding: 0 1rem;" type="button" onclick="limparFiltro();" class="waves-effect waves-light btn red tooltipped"><i class="material-icons" style="font-size: 15px;">backspace</i></button>
                <button data-tooltip="Novo Correspondente" style="margin-top: 10px; line-height: 26px; height: 26px; padding: 0 1rem;" type="button" onclick="openModalNovoCorrespondente();" class="waves-effect waves-light btn green tooltipped"><i class="material-icons" style="font-size: 15px;">person_add</i></button>
              </div>
            </div>
          </form>
          <div class="row" id="list-correspondentes" style="margin-top: 10px;">
            <h5 style="width: 100%; text-align: center; font-size: 18px; margin-top: 80px;">Favor utilize o filtro para obter resultados.</h5>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="modal-contratar_correspondente" class="modal">
    <div class="modal-content">
      <button type="button" style="line-height: 26px; height: 26px; padding: 0 1rem; margin-bottom: 10px; float: right;" class="btn waves-effect mr-sm-1 mr-2 modal-close red" style="">
        <i class="material-icons">close</i>
      </button>
      <h4 style="font-size: 21px; margin-bottom: 25px;">Contratação de Correspondente</h4>
      <form enctype="multipart/form-data"  id="form-contratar-correspondente">
        {{ csrf_field() }}
        <input type="hidden" id="contratar-codigo" name="codigo">
        
        <div class="row">
          <div class="col s4 input-field">
            <span for="contratar-pasta" style="font-size: 11px;">Pasta Projeto</span>
            <input id="contratar-pasta" name="pasta" type="text" class="validate" value="">
          </div>
          <div class="col s4 input-field">
            <span style="font-size: 11px;">Tipo de serviço</span>
            <select name="tipo_servico" class="select2 browser-default" value="" id="contratar-tipo_servico" type="text">
              <option value="" disabled>Selecione uma opção</option>
              @foreach($topservico_descricao as $servico)
                <option value="{{$servico->descricao}}">{{$servico->descricao}}</option>
              @endforeach
            </select>
          </div>
          <div class="col s4">
            <input class="inputfile" id="contratar-arquivo" name="arquivo[]" multiple="multiple" type="file" />
            <label id="label-contratar-arquivo" for="contratar-arquivo"><i class="material-icons left">file_upload</i><strong>Escolher Arquivo(s)</strong></label>
          </div>
          <div class="col s12 input-field">
            <span style="font-size: 11px;" for="contratar-descricao">Descrição</span>
            <textarea id="contratar-descricao" name="descricao" class="materialize-textarea"></textarea>
          </div>
        </div>
      </form>
    </div>
    <div class="modal-footer" style="padding: 4px 35px;">
      <a href="#!" style="" onclick="contratarCorrespondente();" class="btn-custom waves-effect waves-green btn-flat">Contratar</a>
      <a href="#!" style="" class="btn-custom modal-close waves-effect waves-green btn-flat">Cancelar</a>
    </div>
  </div>
  <div id="modal-avaliar_correspondente" class="modal">
    <div class="modal-content">
      <button type="button" style="line-height: 26px; height: 26px; padding: 0 1rem; margin-bottom: 10px; float: right;" class="btn waves-effect mr-sm-1 mr-2 modal-close red" style="">
        <i class="material-icons">close</i>
      </button>
      <h4 style="font-size: 21px; margin-bottom: 25px;">Avaliar Correspondente</h4>
      <form id="form-avaliar-correspondente">
        <div class="row" style="margin-top: 30px;">
          <p style="margin-bottom: 5px; font-size: 12px;">Avaliação de desempenho do correspondente:</p>
          
            {{ csrf_field() }}
            <input type="hidden" id="rating-value" value="0" name="rating">
            <input type="hidden" id="codigo-correspondente" name="codigo">
          
          
          <i class="rating-star material-icons">star_outline</i>
          <i class="rating-star material-icons">star_outline</i>
          <i class="rating-star material-icons">star_outline</i>
          <i class="rating-star material-icons">star_outline</i>
          <i class="rating-star material-icons">star_outline</i>
        </div>
        <div class="row">
          <div class="col s12 input-field" style="padding: 0;">
            <span style="font-size: 11px;" for="contratar-descricao">Descrição</span>
            <textarea id="avaliar-descricao" name="descricao" class="materialize-textarea"></textarea>
          </div>
        </div>
      </form>
    </div>
    <div class="modal-footer" style="padding: 4px 35px;">
      <a href="#!" style="" class="btn-custom waves-effect waves-green btn-flat" onclick="avaliarCorrespondente();">Avaliar</a>
      <a href="#!" style="" class="btn-custom modal-close waves-effect waves-red btn-flat">Cancelar</a>
    </div>
  </div>
  <div id="modal-novo_correspondente" class="modal">
    <div class="modal-content">
      <button type="button" style="line-height: 26px; height: 26px; padding: 0 1rem; margin-bottom: 10px; float: right;" class="btn waves-effect mr-sm-1 mr-2 modal-close red" style="">
        <i class="material-icons">close</i>
      </button>
      <h4 style="font-size: 21px; margin-bottom: 25px;">Adicionar novo correspondente</h4>
      <form enctype="multipart/form-data"  id="form-novo-correspondente">
        {{ csrf_field() }}
        <div class="row">
          <div class="col s12 input-field">
            <span style="font-size: 11px;" for="novo_correspondente-email">E-mail Correspondente</span>
            <input id="novo_correspondente-email" required name="email" type="text" class="validate" value="">
          </div>
          <div class="col s5 input-field">
            <span style="font-size: 11px;" for="novo_correspondente-pasta">Pasta Projeto</span>
            <input id="novo_correspondente-pasta" name="pasta" type="text" class="validate" value="">
          </div>
          <div class="col s4 input-field">
            <span style="font-size: 11px;">Tipo de serviço</span>
            <select name="tipo_servico" class="select2 browser-default" value="" id="novo_correspondente-tipo_servico" type="text">
              <option value="" disabled>Selecione uma opção</option>
              @foreach($topservico_descricao as $servico)
                <option value="{{$servico->descricao}}">{{$servico->descricao}}</option>
              @endforeach
            </select>
          </div>
        </div>
      </form>
    </div>
    <div class="modal-footer" style="padding: 4px 35px;">
      <a href="#!" style="" onclick="novoCorrespondente();" class="btn-custom waves-effect waves-green btn-flat">Enviar E-mail</a>
      <a href="#!" style="" class="btn-custom card-defaultmodal-close waves-effect waves-red btn-flat">Cancelar</a>
    </div>
  </div>
  <div id="loadingdiv2" class="div-loading" style="display:none;">
    <img style="width: 70px; position: relative; top: 40%; left: 50%;" class="image" src="{{URL::asset('/public/imgs/loading.gif')}}" />
    <h6>Carregando os dados...</h6>
  </div>
@endsection

@section('scripts')
  <script src="{{ asset('/public/materialize/js/vendors.min.js') }}"></script>
  <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
  <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
  <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>
  <script src="{{ asset('/public/materialize/js/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('/public/materialize/js/dataTables.responsive.min.js') }}"></script>
  <script src="{{ asset('/public/materialize/js/app-invoice.min.js') }}"></script>
  <script src="{{ asset('/public/plugins/select2/js/select2.full.min.js') }}"></script>
  <script src="{{ asset('/public/dashboard_correspondente/index.js') }}?ver={{ filemtime(public_path('/dashboard_correspondente/index.js')) }}"></script>
  <script src="{{ asset('/public/dashboard_correspondente/grafico_unidade.js') }}?ver={{ filemtime(public_path('/dashboard_correspondente/grafico_unidade.js')) }}"></script>
  <script src="{{ asset('/public/dashboard_correspondente/grafico_valor.js') }}?ver={{ filemtime(public_path('/dashboard_correspondente/grafico_valor.js')) }}"></script>
  <script src="{{ asset('/public/dashboard_correspondente/grafico_val_uni.js') }}?ver={{ filemtime(public_path('/dashboard_correspondente/grafico_val_uni.js')) }}"></script>
  <!-- <script src="{{ asset('/public/plugins/select2/js/select2.min.js') }}"></script> -->
  <!-- <script src="{{ asset('/public/materialize/js/select2.full.min.js') }}"></script> -->
  <!-- <script src="{{ asset('/public/materialize/js/form-select2.min.js') }}"></script> -->

  <script>
    
  </script>
@endsection