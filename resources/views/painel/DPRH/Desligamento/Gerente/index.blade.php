<!DOCTYPE html>

<html class="loading" lang="pt-br" data-textdirection="ltr">
  <head>
    <meta http-equiv="Content-Language" content="pt-br">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="author" content="Portal PL&C">
    <title>DPRH - Desligamento | Portal PL&C</title>
    <link rel="apple-touch-icon" href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/images/favicon/apple-touch-icon-152x152.png">
    <link rel="shortcut icon" href="{{ asset('/public/assets/imgs/casinha.png')}}" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/vendors.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/responsive.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/materialize.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/data-tables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/app-invoice.min.css') }}">

    <style>
    * {
      box-sizing: border-box;
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

</style>

  </head>

  <body class="vertical-layout vertical-menu-collapsible page-header-dark vertical-modern-menu preload-transitions 2-columns   " data-open="click" data-menu="vertical-modern-menu" data-col="2-columns">

    <header class="page-topbar" id="header">
      <div class="navbar navbar-fixed"> 
        <nav class="navbar-main navbar-color nav-collapsible navbar-dark no-shadow" style="background-color: gray">
          <div class="nav-wrapper">


          <div class="breadcrumbs-inline pt-3 pb-1" id="breadcrumbs-wrapper" style="margin-top: -40px;">
              <h5 class="breadcrumbs-title mt-0 mb-0 display-inline hide-on-small-and-down"><span style="color: white;">Desligamento</span></h5>
              <ol class="breadcrumbs mb-0">
                <li class="breadcrumb-item"><a href="{{ route('Home.Principal.Show') }}">Home</a>
                </li>
                <li class="breadcrumb-item active" style="color: black;">Solicitações em andamento
                </li>
              </ol>
          </div>


          <ul class="navbar-list right" style="margin-top: -80px;">
              
          <li><a class="waves-block waves-light notification-button" href="javascript:void(0);" data-target="notifications-dropdown"><i class="material-icons">notifications_none<small class="notification-badge">{{$totalNotificacaoAbertas}}</small></i></a></li>
         <li><a class="waves-effect waves-block waves-light profile-button" href="javascript:void(0);" data-target="profile-dropdown"><span class="avatar-status avatar-online"><img src="{{ asset('/public/AdminLTE/img/Avatar/img.png') }}" class="img-circle" alt="User Image"><i></i></span></a></li>            </ul>

            <!-- notifications-dropdown-->
            <ul class="dropdown-content" id="notifications-dropdown">
              <li>
                <h6>NOTIFICAÇÕES<span class="new badge">{{$totalNotificacaoAbertas}}</span></h6>
              </li>
              <li class="divider"></li>

              @foreach($notificacoes as $notificacao)
              <li><a class="black-text" href="#!" style="font-size: 12px;"><span class="material-icons icon-bg-circle deep-orange small">today</span>{{$notificacao->obs}}</a>
                <time class="media-meta grey-text darken-2">{{$notificacao->name}} - {{ date('d/m/Y H:i:s', strtotime($notificacao->data)) }}</time>
              </li>
              @endforeach

            </ul>

            <!-- profile-dropdown-->
            <ul class="dropdown-content" id="profile-dropdown">
              <li><a class="grey-text text-darken-1" href="#"><i class="material-icons">person_outline</i>Meu Perfil</a></li>
              <li class="divider"></li>
              <li><a class="grey-text text-darken-1" href="{{ route('Home.Principal.Show') }}"><i class="material-icons">lock_outline</i>Home</a></li>
              <li><a class="grey-text text-darken-1" href="{{ route('logout') }}"><i class="material-icons">keyboard_tab</i>Sair</a></li>
            </ul>

          </div>

        </nav>

      </div>
    </header>
    <!-- END: Header-->

    <!-- BEGIN: SideNav-->
    <aside class="sidenav-main nav-collapsible sidenav-light sidenav-active-square nav-collapsed">
      <ul class="sidenav sidenav-collapsible leftside-navigation collapsible sidenav-fixed menu-shadow" id="slide-out" data-menu="menu-navigation" data-collapsible="menu-accordion">
       
      <li class="bold"><a class="collapsible-header waves-effect waves-cyan " href="JavaScript:void(0)"><i class="material-icons">settings_input_svideo</i><span class="menu-title" data-i18n="Dashboard">Pesquisa Patrimonial</span></a>
         <div class="collapsible-body">
           <ul class="collapsible collapsible-sub" data-collapsible="accordion">
             
             @can('advogado')
             <li><a href="{{ route('Painel.PesquisaPatrimonial.solicitacao.index') }}"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Dashboard">Dashboard</span></a></li>
             <li><a href="{{ route('Painel.PesquisaPatrimonial.solicitacao.create') }}"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Nova pesquisa">Nova pesquisa</span></a></li>
             @endcan

             @can('nucleo_pesquisa_patrimonial')
             <li><a href="{{ route('Painel.PesquisaPatrimonial.nucleo.index') }}"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Dashboard">Dashboard</span></a></li>
             <li><a href="{{ route('Painel.PesquisaPatrimonial.nucleo.solicitacoes') }}"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Solicitações pesquisa">Solicitações pesquisa</span></a></li>
             @endcan

             @can('supervisao_pesquisapatrimonial')
             <li><a href="{{ route('Painel.PesquisaPatrimonial.supervisao.index') }}"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Dashboard">Dashboard</span></a></li>
             <li><a href="{{ route('Painel.PesquisaPatrimonial.supervisao.solicitacoes') }}"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Solicitações pesquisa">Solicitações pesquisa</span></a></li>
             @endcan

             @can('financeiro_pesquisapatrimonial')
             <li><a href="{{ route('Painel.PesquisaPatrimonial.financeiro.index') }}"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Dashboard">Dashboard</span></a></li>
             <li><a href="{{ route('Painel.PesquisaPatrimonial.financeiro.solicitacoes') }}"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Dashboard">Solicitações pesquisa</span></a></li>
             <li><a href=""><i class="material-icons">radio_button_unchecked</i><span data-i18n="Dashboard">Movimentação bancaria</span></a></li>
             @endcan

             @can('users')
             <li><a href="{{ route('Painel.PesquisaPatrimonial.supervisao.equipe.index') }}"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Equipe pesquisa">Equipe</span></a></li>
             <li><a href="{{ route('Painel.PesquisaPatrimonial.tiposdeservico') }}"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Tipos serviço">Tipos de serviço</span></a></li>
             <li><a href="{{ route('Painel.PesquisaPatrimonial.supervisao.grupos.index') }}"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Grupos cliente">Grupos cliente</span></a></li>
             <li><a href="{{ route('Painel.PesquisaPatrimonial.supervisao.clientes.index') }}"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Clientes">Clientes</span></a></li>
             @endcan

           </ul>
         </div>
       </li>

     <li class="bold"><a class="collapsible-header waves-effect waves-cyan " href="JavaScript:void(0)"><i class="material-icons">preview</i><span class="menu-title" data-i18n="Dashboard">Controladoria</span></a>
         <div class="collapsible-body">
           <ul class="collapsible collapsible-sub" data-collapsible="accordion">

      <!--Vídeos -->
           <li class="bold"><a class="collapsible-header waves-effect waves-cyan " href="JavaScript:void(0)"><i class="material-icons">preview</i><span class="menu-title" data-i18n="Dashboard">Vídeos</span></a>
         <div class="collapsible-body">
           <ul class="collapsible collapsible-sub" data-collapsible="accordion">

             <li><a href="" style="font-size: 11px;"><i class="material-icons">movie</i><span data-i18n="Modern">Proc. para utilizar o formulário ficha tempo.</span></a></li>
             <li><a href="" style="font-size: 11px;"><i class="material-icons">movie</i><span data-i18n="Modern">Proc. para a movimentação e cumprimento de prazos.</span></a></li>

           </ul>
         </div>
          </li>
      <!--Vídeos -->    

      <!--Manual --> 
      <li class="bold"><a class="collapsible-header waves-effect waves-cyan " href="JavaScript:void(0)"><i class="material-icons">library_books</i><span class="menu-title" data-i18n="Dashboard">Arquivos</span></a>
         <div class="collapsible-body">
           <ul class="collapsible collapsible-sub" data-collapsible="accordion">

             <li><a href="{{route('Home.Principal.treinamento', 'ApresentacaoSLA.pdf')}}" style="font-size: 11px;"><i class="material-icons">movie</i><span data-i18n="Modern">Apresentação Jurídico Tenco.</span></a></li>
             <li><a href="{{route('Home.Principal.treinamento', 'Treinamento01.pdf')}}" style="font-size: 11px;"><i class="material-icons">movie</i><span data-i18n="Modern">Cartilha de provisionamento Alfresco.</span></a></li>
             <li><a href="{{route('Home.Principal.treinamento', 'Treinamento02.pdf')}}" style="font-size: 11px;"><i class="material-icons">movie</i><span data-i18n="Modern">Cartilha provisionamento PLC.</span></a></li>
             <li><a href="{{route('Home.Principal.treinamento', 'TreinamentoGPA.pdf')}}" style="font-size: 11px;"><i class="material-icons">movie</i><span data-i18n="Modern">Cartilha de provisionamento GPA.</span></a></li>
             <li><a href="{{route('Home.Principal.treinamento',  'Treinamento04.pdf')}}" style="font-size: 11px;"><i class="material-icons">movie</i><span data-i18n="Modern">Manual Cumprimento de prazos _2019</span></a></li>
             <li><a href="{{route('Home.Principal.treinamento',  'MANUAL-GED_2020.pdf')}}" style="font-size: 11px;"><i class="material-icons">movie</i><span data-i18n="Modern">Manual de indexação de arquivos no GED</span></a></li>

           </ul>
         </div>
          </li>
      <!--Manual -->

         
           </ul>
         </div>
       </li>

       <li class="bold"><a class="collapsible-header waves-effect waves-cyan " href="JavaScript:void(0)"><i class="material-icons">perm_identity</i><span class="menu-title" data-i18n="Dashboard">Correspondente</span></a>
         <div class="collapsible-body">
           <ul class="collapsible collapsible-sub" data-collapsible="accordion">
             
             @can('coordenador')
             <li><a href="{{ route('Painel.Coordenador.index') }}" style="font-size: 11px;"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Modern">Aprovação de solicitação de pagamento.</span></a></li>
             <li><a href="{{ route('Painel.Coordenador.acompanharSolicitacoes') }}" style="font-size: 11px;"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Modern">Fluxo de processo em curso.</span></a></li>
             @endcan

             @can('advogado')
             <li><a href="{{ route('Painel.Advogado.index') }}" style="font-size: 11px;"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Modern">Fluxo de processo em curso.</span></a></li>
             @endcan

             @can('financeiro')
             <li><a href="{{ route('Painel.Financeiro.index') }}" style="font-size: 11px;"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Modern">Aprovação - Solicitação de pagamento.</span></a></li>
             <li><a href="{{ route('Painel.Financeiro.aprovadas') }}" style="font-size: 11px;"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Modern">Fluxo de processo em curso.</span></a></li>
             @endcan 

             @can('financeiro contas a pagar')
             <li><a href="{{ route('Painel.Financeiro.programadas') }}" style="font-size: 11px;"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Modern">Realizar conciliação bancária.</span></a></li>
             <li><a href="{{ route('Painel.Financeiro.realizarconciliacao') }}" style="font-size: 11px;"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Modern">Realizar conciliação bancária por faixa.</span></a></li>
             <li><a href="{{ route('Painel.Financeiro.pagas') }}" style="font-size: 11px;"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Modern">Fluxo de processo finalizadas.</span></a></li>
             @endcan 

             @can('users')
             <li><a href="{{ route('Painel.TI.correspondente.index') }}" style="font-size: 11px;"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Modern">Dashboard.</span></a></li>
             @endcan 

           </ul>
         </div>
       </li>

       
       <li class="bold"><a class="collapsible-header waves-effect waves-cyan " href="JavaScript:void(0)"><i class="material-icons">perm_contact_calendar</i><span class="menu-title" data-i18n="Dashboard">DP & RH</span></a>
         <div class="collapsible-body">
           <ul class="collapsible collapsible-sub" data-collapsible="accordion">

            <li><a href="#"><i class="material-icons" style="font-size: 11px;">not_interested</i><span data-i18n="Modern" style="font-size: 11px;">Em desenvolvimento.</span></a></li>
         
           </ul>
         </div>
       </li>

       
       <li class="bold"><a class="collapsible-header waves-effect waves-cyan " href="JavaScript:void(0)"><i class="material-icons">calculate</i><span class="menu-title" data-i18n="Dashboard">Financeiro</span></a>
         <div class="collapsible-body">
           <ul class="collapsible collapsible-sub" data-collapsible="accordion">
         
           @can('financeiro_guias_custa')
           <li><a href="{{ route('Painel.Financeiro.guiascusta') }}" style="font-size: 11px;"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Modern">Fluxo de pagamento de guias de custa.</span></a></li>
           @endcan 

           @can('financeiro')
           <li><a href="{{ route('Painel.Financeiro.guiascusta') }}" style="font-size: 11px;"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Modern">Fluxo de pagamento de guias de custa.</span></a></li>
           @endcan 

           @can('financeiro_faturamento')
           <li><a href="{{ route('Painel.Financeiro.faturamento') }}" style="font-size: 11px;"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Modern">Realizar faturamento de pastas e processos.</span></a></li>
           @endcan 

           </ul>
         </div>
       </li>
       
       <li class="bold"><a class="collapsible-header waves-effect waves-cyan " href="JavaScript:void(0)"><i class="material-icons">mail</i><span class="menu-title" data-i18n="Dashboard">Marketing</span></a>
         <div class="collapsible-body">
           <ul class="collapsible collapsible-sub" data-collapsible="accordion">

           @can('marketing')
           <li><a href="{{ route('Painel.Marketing.create') }}" style="font-size: 11px;"><i class="material-icons">add</i><span data-i18n="Modern">Novo comunicado.</span></a></li>
           <li><a href="{{ route('Painel.Marketing.index') }}" style="font-size: 11px;"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Modern">Comunicados.</span></a></li>
           <li><a href="{{ route('Painel.Marketing.informativos') }}" style="font-size: 11px;"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Modern">Informativos.</span></a></li>
           @endcan 

           @can('listagem_comunicados')
           <li><a href="{{ route('Painel.Marketing.index') }}" style="font-size: 11px;"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Modern">Comunicados.</span></a></li>
           <li><a href="{{ route('Painel.Marketing.informativos') }}" style="font-size: 11px;"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Modern">Informativos.</span></a></li>
           @endcan 

           </ul>
         </div>
       </li>

       
       <li class="bold"><a class="collapsible-header waves-effect waves-cyan " href="JavaScript:void(0)"><i class="material-icons">stay_primary_portrait</i><span class="menu-title" data-i18n="Dashboard">T.I</span></a>
         <div class="collapsible-body">
           <ul class="collapsible collapsible-sub" data-collapsible="accordion">
           
           @can('users')
           <li><a href="{{ route('Painel.TI.chat.index') }}" style="font-size: 11px;"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Modern">Desenvolvimento CHAT</span></a></li>
           <li><a href="{{ route('Painel.TI.users.index') }}" style="font-size: 11px;"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Modern">Gerenciar usuários.</span></a></li>
           <li><a href="{{url('/painel/perfis')}}" style="font-size: 11px;"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Modern">Gerenciar profiles.</span></a></li>
           <li><a href="{{url('/painel/setorcusto')}}" style="font-size: 11px;"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Modern">Gerenciar setor de custo.</span></a></li>
           <li><a href="{{url('/painel/permissoes')}}" style="font-size: 11px;"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Modern">Gerenciar permissões.</span></a></li>
           @endcan
           
           <li><a href="#"><i class="material-icons" style="font-size: 11px;">not_interested</i><span data-i18n="Modern" style="font-size: 11px;">Em desenvolvimento.</span></a></li>

           </ul>
         </div>
       </li>
     </ul>
     <div class="navigation-background"></div><a class="sidenav-trigger btn-sidenav-toggle btn-floating btn-medium waves-effect waves-light hide-on-large-only" href="#" data-target="slide-out"><i class="material-icons">menu</i></a>
   </aside>
   <!-- END: SideNav-->

   <!-- BEGIN: Page Main-->
   <div id="main">
      <div class="row">
        <div class="content-wrapper-before blue-grey lighten-5"></div>

        <div class="col s12" id="corpodiv">

          <div class="container">
            <div class="section">

<section class="invoice-list-wrapper section">

  <div class="invoice-filter-action mr-3">
  <a href="#modal" class="waves-effect waves-light btn modal-trigger tooltipped border-round" data-position="left" data-tooltip="Clique aqui para cadastro de uma nova solicitação." style="background-color: gray;color:white;font-size:11px;"><i class="material-icons left">add</i> Nova solicitação</a>
  <a href="{{ route('Painel.DPRH.Desligamento.Gerente.historico') }}" class="waves-effect waves-light btn tooltipped border-round" data-position="left" data-tooltip="Clique aqui para visualizar as solicitações já finalizadas ou canceladas." style="background-color: gray;font-size:11px;"><i class="material-icons">list</i>Histórico</a>
  </div>


  <div class="responsive-table">
    <table class="table invoice-data-table white border-radius-4 pt-1">
      <thead>
        <tr>
          <th style="font-size: 11px"></th>
          <th style="font-size: 11px">Nome</th>
          <th style="font-size: 11px">Setor</th>
          <th style="font-size: 11px">Motivo</th>
          <th style="font-size: 11px">Data da solicitação</th>
          <th style="font-size: 11px">Data prevista saída</th>
          <th style="font-size: 11px">Ultima modificação</th>
          <th style="font-size: 11px">Status</th>
          <th style="font-size: 11px"></th>
        </tr>
      </thead>

      <tbody>
      @foreach($datas as $data)  
      <tr>


<!--Inicio Modal Glosar solicitação --> 
<div id="glosar{{$data->id}}" class="modal" style="width: 1207px;">
    <form id="form2" role="form" onsubmit="btnsubmit.disabled = true; return true;"  action="{{ route('Painel.DPRH.Desligamento.Gerente.solicitacaoglosada') }}" method="POST" role="create" enctype="multipart/form-data" >
    {{ csrf_field() }}

    <input type="hidden" name="id" id="id" value="{{$data->id}}">

    <div class="modal-content">

  <div id="corpodiv2">

  <button type="button" class="btn waves-effect mr-sm-1 mr-2 modal-close red" style="margin-top: -26px; margin-left: 1080px;position: fixed;"><i class="material-icons">close</i></button>

      <h6>[Workflow etapa 2 - Glosar solicitação de desligamento]</h6>
      <p style="font-size: 11px;">Favor conferir os dados da solicitação de desligamento abaixo.</p>

      <br>

    
        <div class="row">

        <div class="input-field col s2">
            <input style="font-size: 10px;" readonly id="usuario" type="text" value="{{$data->UsuarioNome}}" name="usuario" class="validate">
            <input type="hidden" name="usuario_cpf" id="usuario_cpf" value="{{$data->UsuarioCPF}}">
            <label style="font-size: 11px;" for="usuario">Usuário:</label>
        </div>

        <div class="input-field col s2">
            <input style="font-size: 10px;" readonly id="solicitante" type="text" value="{{$data->SolicitanteNome}}" name="solicitante" class="validate">
            <label style="font-size: 11px;" for="usuario">Solicitante:</label>
        </div>

        <div class="input-field col s2">
          <input style="font-size: 10px;" readonly id="setor" type="text" name="setor" value="{{ $data->Setor }}" class="validate">
          <input type="hidden" name="setor_codigo" id="setor_codigo" value="{{$data->SetorCodigo}}">
          <label style="font-size: 11px;" for="setor">Setor de custo:</label>
        </div>

        <div class="input-field col s2">
            <input style="font-size: 10px;" readonly  type="text" value="{{ date('d/m/Y', strtotime($data->DataSaida)) }}" class="validate">
            <input id="datasaida" type="hidden" value="{{$data->DataSaida}}" name="datasaida" class="validate">
            <label style="font-size: 11px;" for="datasaida">Data prevista para saída:</label>
        </div>

        <div class="input-field col s2">
            <input style="font-size: 10px;" readonly id="motivo" type="text" value="{{$data->Motivo}}" name="motivo" class="validate">
            <label style="font-size: 11px;" for="motivo">Motivo:</label>
        </div>

        <div class="input-field col s2">
            <input style="font-size: 10px;" readonly id="status" type="text" value="{{$data->Status}}" name="status" class="validate">
            <label style="font-size: 11px;" for="status">Status:</label>
        </div>

        <div class="input-field col s2">
        <input style="font-size: 10px;" readonly id="datasolicitacao" type="text" value="{{ date('d/m/Y H:i:s', strtotime($data->DataSolicitacao)) }}" name="datasolicitacao" class="validate">
            <label style="font-size: 11px;" for="datasolicitacao">Data da solicitação:</label>
        </div>

        <div class="input-field col s2">
        <input style="font-size: 10px;" readonly id="datamodificacao" type="text" value="{{ date('d/m/Y H:i:s', strtotime($data->DataModificacao)) }}" name="datamodificacao" class="validate">
            <label style="font-size: 11px;" for="datamodificacao">Data da ultima edição:</label>
        </div>


        </div>

        <div class="row">

        <div class="input-field col s12" style="margin-top: -15px;">
         <div class="form-group">
            <div class="form-group">
              <label class="control-label" style="font-size: 11px;">Observação:</label>
                        <textarea id="observacao" readonly rows="3" type="text" name="observacao" class="form-control" placeholder="Insira a observação abaixo." style="height: 3rem;text-align:left; overflow:auto;font-size: 10px;">{{$data->Observacao}}</textarea>
             </div>
          </div>
        </div>   
       </div>  


    <div class="modal-footer" style="margin-top: 0px;">
      <button type="button" id="btnsubmit" onClick="abremodalconfirmando2();"  class="modal-action waves-effect waves-green btn-flat" style="background-color: gray;color:white;font-size: 11px;"><i class="material-icons left">check</i>Glosar solicitação</button>
    </div>

    </div>
    </form>
    </div>
    </div>
<!--Fim Modal Glosar solicitação --> 


<!--Inicio Modal Liberar Solicitação --> 
<div id="liberar{{$data->id}}" class="modal" style="width: 1207px;">
    <form id="form3" role="form" onsubmit="btnsubmit.disabled = true; return true;"  action="{{ route('Painel.DPRH.Desligamento.SubCoordenador.solicitacaoliberada') }}" method="POST" role="create" enctype="multipart/form-data" >
    {{ csrf_field() }}

    <input type="hidden" name="id" id="id" value="{{$data->id}}">

    <div class="modal-content">

  <div id="corpodiv2">

  <button type="button" class="btn waves-effect mr-sm-1 mr-2 modal-close red" style="margin-top: -26px; margin-left: 1080px;position: fixed;"><i class="material-icons">close</i></button>

      <h6>[Workflow etapa 3 - Liberar solicitação de desligamento]</h6>
      <p style="font-size: 11px;">Favor conferir os dados da solicitação de desligamento abaixo.</p>

      <br>

    
        <div class="row">

        <div class="input-field col s2">
            <input style="font-size: 10px;" readonly id="usuario" type="text" value="{{$data->UsuarioNome}}" name="usuario" class="validate">
            <input type="hidden" name="usuario_cpf" id="usuario_cpf" value="{{$data->UsuarioCPF}}">
            <label style="font-size: 11px;" for="usuario">Usuário:</label>
        </div>

        <div class="input-field col s2">
            <input style="font-size: 10px;" readonly id="solicitante" type="text" value="{{$data->SolicitanteNome}}" name="solicitante" class="validate">
            <label style="font-size: 11px;" for="usuario">Solicitante:</label>
        </div>

        <div class="input-field col s2">
          <input style="font-size: 10px;" readonly id="setor" type="text" name="setor" value="{{ $data->Setor }}" class="validate">
          <input type="hidden" name="setor_codigo" id="setor_codigo" value="{{$data->SetorCodigo}}">
          <label style="font-size: 11px;" for="setor">Setor de custo:</label>
        </div>

        <div class="input-field col s2">
            <input style="font-size: 10px;" readonly  type="text" value="{{ date('d/m/Y', strtotime($data->DataSaida)) }}" class="validate">
            <input id="datasaida" type="hidden" value="{{$data->DataSaida}}" name="datasaida" class="validate">
            <label style="font-size: 11px;" for="datasaida">Data prevista para saída:</label>
        </div>

        <div class="input-field col s2">
            <input style="font-size: 10px;" readonly id="motivo" type="text" value="{{$data->Motivo}}" name="motivo" class="validate">
            <label style="font-size: 11px;" for="motivo">Motivo:</label>
        </div>

        <div class="input-field col s2">
            <input style="font-size: 10px;" readonly id="status" type="text" value="{{$data->Status}}" name="status" class="validate">
            <label style="font-size: 11px;" for="status">Status:</label>
        </div>

        <div class="input-field col s2">
        <input style="font-size: 10px;" readonly id="datasolicitacao" type="text" value="{{ date('d/m/Y H:i:s', strtotime($data->DataSolicitacao)) }}" name="datasolicitacao" class="validate">
            <label style="font-size: 11px;" for="datasolicitacao">Data da solicitação:</label>
        </div>

        <div class="input-field col s2">
        <input style="font-size: 10px;" readonly id="datamodificacao" type="text" value="{{ date('d/m/Y H:i:s', strtotime($data->DataModificacao)) }}" name="datamodificacao" class="validate">
            <label style="font-size: 11px;" for="datamodificacao">Data da ultima edição:</label>
        </div>


        </div>

        <div class="row">

        <div class="input-field col s12" style="margin-top: -15px;">
         <div class="form-group">
            <div class="form-group">
              <label class="control-label" style="font-size: 11px;">Observação:</label>
                        <textarea id="observacao" readonly rows="3" type="text" name="observacao" class="form-control" placeholder="Insira a observação abaixo." style="height: 3rem;text-align:left; overflow:auto;font-size: 10px;">{{$data->Observacao}}</textarea>
             </div>
          </div>
        </div>   
       </div>  


    <div class="modal-footer" style="margin-top: 0px;">
      <button type="button" id="btnsubmit" onClick="abremodalconfirmando3();"  class="modal-action waves-effect waves-green btn-flat" style="background-color: gray;color:white;font-size: 11px;"><i class="material-icons left">check</i>Liberar solicitação</button>
    </div>

    </div>
    </form>
    </div>
    </div>
<!--Fim Modal Liberar Solicitação --> 


          <td style="font-size: 10px"></td>
          <td style="font-size: 10px">{{ $data->UsuarioNome }}</td>
          <td style="font-size: 10px">{{ $data->Setor }}</td>
          <td style="font-size: 10px">{{ $data->Motivo }}</td>
          <td style="font-size: 10px">{{ date('d/m/Y H:i:s', strtotime($data->DataSolicitacao)) }}</td>
          <td style="font-size: 10px">{{ date('d/m/Y', strtotime($data->DataSaida)) }}</td>
          <td style="font-size: 10px">{{ date('d/m/Y H:i:s', strtotime($data->DataModificacao)) }}</td>
          <td style="font-size: 10px"><span class="bullet yellow"></span>{{$data->Status}}</td>

          <td style="font-size: 10px">
          <div class="invoice-action">

          <!--Se estiver com o status 1 e não for o usuario que criou --> 
          @if($data->StatusID == 1 && $data->UsuarioCPF != Auth::user()->cpf)
          <a href="#glosar{{$data->id}}" class="invoice-action-view mr-4 tooltipped modal-trigger" data-position="left" data-tooltip="Clique aqui caso deseje glosar está solicitação de desligamento."><i class="material-icons">touch_app</i></a> 
          @endif
          <!--Fim --> 

          </div>
          </td>

        </tr>
        @endforeach
        
        
      </tbody>
    </table>
  </div>
</section>

</div>
          <div class="content-overlay"></div>
        </div>
      </div>
    </div>





    <!--Inicio Modal Nova solicitação --> 
<div id="modal" class="modal" style="width: 1207px;">
    <form id="form" role="form" onsubmit="btnsubmit.disabled = true; return true;"  action="{{ route('Painel.DPRH.Desligamento.Gerente.store') }}" method="POST" role="create" enctype="multipart/form-data" >
    {{ csrf_field() }}

    <div class="modal-content">

  <div id="corpodiv2">

  <button type="button" class="btn waves-effect mr-sm-1 mr-2 modal-close red" style="margin-top: -26px; margin-left: 1080px;position: fixed;"><i class="material-icons">close</i></button>

      <h6>[Workflow etapa 1 - Cadastro de solicitação de desligamento]</h6>
      <p style="font-size: 11px;">Favor informar os dados para cadastro de um novo desligamento.</p>

      <br>

        <div class="row">

        <div class="input-field col m2 s12">
            <span style="font-size: 11px;">Selecione o usuário:</span>
            <select style="font-size: 10px;" class="select2 browser-default" id="usuario" name="usuario"  data-toggle="tooltip" data-placement="top" title="Selecione o usuário." required="required">
                @foreach($usuarios as $usuario)   
                <option value="{{$usuario->id}}">{{$usuario->name}}</option>
                @endforeach
            </select>
        </div>

        <div class="input-field col m2 s12">
            <span style="font-size: 11px;">Selecione o setor:</span>
            <select style="font-size: 10px;" class="select2 browser-default" id="setor" name="setor"  data-toggle="tooltip" data-placement="top" title="Selecione o setor de custo." required="required">
                @foreach($setores as $setor)   
                <option value="{{$setor->Codigo}}">{{$setor->Codigo}} - {{$setor->Descricao}}</option>
                @endforeach
            </select>
        </div>

        <div class="input-field col m2 s12">
            <span style="font-size: 11px;">Selecione o motivo:</span>
            <select style="font-size: 10px;" class="select2 browser-default" id="motivo" name="motivo"  data-toggle="tooltip" data-placement="top" title="Selecione o tipo de cargo." required="required">
                @foreach($motivos as $motivo)   
                <option value="{{$motivo->id}}">{{$motivo->descricao}}</option>
                @endforeach
            </select>
        </div>

        <div class="input-field col m3 s12" id="oportunidadediv" style="display: none">
            <span style="font-size: 11px;">Indique o local:</span>
            <input type="text" name="oportunidade" id="oportunidade" placeholder="Indique o local">
        </div>

        <div class="input-field col m3 s12" id="instatisfacaodiv" style="display: none">
        <span style="font-size: 11px;">Selecione o motivo da instatisfação:</span>
        <select style="font-size: 10px;" class="select2 browser-default" id="insatisfacao" name="insatisfacao"  data-toggle="tooltip" data-placement="top" title="Selecione o motivo da satisfação.">
                <option value="" selected></option>
                <option value="Cliente">Cliente</option>
                <option value="Escritório">Escritório</option>
                <option value="Equipe">Equipe</option>
        </select>
        </div>

        <div class="input-field col m2 s12">
        <span style="font-size: 11px;">Data prevista para saída:</span>
        <input style="font-size: 10px;"  min="{{$datahoje}}" name="datasaida" id="datasaida" type="date"  class="form-control" data-toggle="tooltip" data-placement="top" title="Informe a data prevista para saída." required="required">
        </div>

        </div>

        <div class="row">

        <div class="input-field col s12" style="margin-top: -15px;">
         <div class="form-group">
            <div class="form-group">
              <label class="control-label" style="font-size: 11px;">Observação:</label>
                        <textarea id="observacao" rows="3" type="text" name="observacao" class="form-control" placeholder="Insira a observação abaixo." style="height: 3rem;text-align:left; overflow:auto;font-size: 10px;"></textarea>
             </div>
          </div>
        </div>   
       </div>  


    <div class="modal-footer" style="margin-top: 0px;">
      <button type="button" id="btnsubmit" onClick="abremodalconfirmando();"  class="modal-action waves-effect waves-green btn-flat" style="background-color: gray;color:white;font-size: 11px;"><i class="material-icons left">save</i>Cadastrar nova solicitação</button>
    </div>

    </div>
    </form>
    </div>
    </div>
<!--Fim Modal Nova Solicitação --> 



<div id="modalconfirmacao" class="modal"  style="width: 30% !important;height: 30% !important;">

<div id="loadingdiv3" style="display:none">
  <div style="height: 50px;margin-top: calc(50vh - 150px);margin-left: calc(50vw - 600px);width: 180px;">
              <img style="width: 100px;margin-left:250px;margin-top:-70px;" src="{{URL::asset('/public/imgs/loading.gif')}}"/>
</div>
</div>

    <div id="corpodiv3">
    <div class="modal-content">
      <center><p style="font-size: 18px;">Deseja confirmar a solicitação de desligamento?</p></center>
    </div>
    <div class="modal-footer">
      <a  class="modal-action  waves-effect waves-red btn-flat " style="background-color: red;color:white;font-size:11px;" onClick="nao();"><i class="material-icons left">close</i>Não</a>
      <a  class="modal-action  waves-effect waves-green btn-flat " style="background-color: green;color:white;font-size:11px;" onClick="sim();"><i class="material-icons left">check</i>Sim</a>
    </div>
    </div>
</div>


<div id="modalconfirmacao2" class="modal"  style="width: 30% !important;height: 30% !important;">

<div id="loadingdiv4" style="display:none">
  <div style="height: 50px;margin-top: calc(50vh - 150px);margin-left: calc(50vw - 600px);width: 180px;">
              <img style="width: 100px;margin-left:250px;margin-top:-70px;" src="{{URL::asset('/public/imgs/loading.gif')}}"/>
</div>
</div>

    <div id="corpodiv4">
    <div class="modal-content">
      <center><p style="font-size: 18px;">Deseja confirmar o pendenciamento da solicitação de desligamento?</p></center>
      <center><p style="font-size: 12px;">Um e-mail de agendamento de reunião será enviado a toda equipe responsável deste setor de custo.</p></center>
    </div>
    <div class="modal-footer">
      <a  class="modal-action  waves-effect waves-red btn-flat " style="background-color: red;color:white;font-size:11px;" onClick="nao();"><i class="material-icons left">close</i>Não</a>
      <a  class="modal-action  waves-effect waves-green btn-flat " style="background-color: green;color:white;font-size:11px;" onClick="confirmasim();"><i class="material-icons left">check</i>Sim</a>
    </div>
    </div>
</div>


<div id="modalconfirmacao3" class="modal"  style="width: 30% !important;height: 30% !important;">

<div id="loadingdiv5" style="display:none">
  <div style="height: 50px;margin-top: calc(50vh - 150px);margin-left: calc(50vw - 600px);width: 180px;">
              <img style="width: 100px;margin-left:250px;margin-top:-70px;" src="{{URL::asset('/public/imgs/loading.gif')}}"/>
</div>
</div>

    <div id="corpodiv5">
    <div class="modal-content">
      <center><p style="font-size: 18px;">Deseja confirmar a liberação desta solicitação de desligamento?</p></center>
    </div>
    <div class="modal-footer">
      <a  class="modal-action  waves-effect waves-red btn-flat " style="background-color: red;color:white;font-size:11px;" onClick="nao();"><i class="material-icons left">close</i>Não</a>
      <a  class="modal-action  waves-effect waves-green btn-flat " style="background-color: green;color:white;font-size:11px;" onClick="confirmasim2();"><i class="material-icons left">check</i>Sim</a>
    </div>
    </div>
</div>




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
  function abremodalconfirmando() {
      $('.modal').modal();
      $('#modalconfirmacao').modal('open');
  }    
</script>


<script>
  function nao() {

    window.location.reload(true);
         
  }    
</script>

<script>
  function sim() {

    $('.modal').css('background-color', 'transparent');
    document.getElementById("loadingdiv3").style.display = "";
    document.getElementById("corpodiv3").style.display = "none";
    document.getElementById("form").submit();
         
  }    
</script>

<script>
  function abremodalconfirmando2() {
      $('.modal').modal();
      $('#modalconfirmacao2').modal('open');
  }    
</script>

<script>
  function confirmasim() {

    $('.modal').css('background-color', 'transparent');
    document.getElementById("loadingdiv4").style.display = "";
    document.getElementById("corpodiv4").style.display = "none";
    document.getElementById("form2").submit();
         
  }    
</script>

<script>
  function abremodalconfirmando3() {
      $('.modal').modal();
      $('#modalconfirmacao3').modal('open');
  }    
</script>

<script>
  function confirmasim2() {

    $('.modal').css('background-color', 'transparent');
    document.getElementById("loadingdiv5").style.display = "";
    document.getElementById("corpodiv5").style.display = "none";
    document.getElementById("form3").submit();
         
  }    
</script>



<script>
$('#motivo').change(function(){


    var motivo = $('#motivo option:selected').val()

    //Se for Oportunidade deve indicar o local
    if(motivo == 11) {
        document.getElementById("oportunidadediv").style.display = "";
        document.getElementById("instatisfacaodiv").style.display = "none";
    } 
    //Se for Insatisfação deve marcar com o que
    else if(motivo == 12){
        document.getElementById("oportunidadediv").style.display = "none";
        document.getElementById("instatisfacaodiv").style.display = "";
    } 
    else {
        document.getElementById("oportunidadediv").style.display = "none";
        document.getElementById("instatisfacaodiv").style.display = "none";
    }


});
</script>




  </body>
</html>