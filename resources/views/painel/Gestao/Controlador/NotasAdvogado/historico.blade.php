<!DOCTYPE html>

<html class="loading" lang="pt-br" data-textdirection="ltr">
  <head>
    <meta http-equiv="Content-Language" content="pt-br">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="author" content="Portal PL&C">
    <title>Histórico Notas Sócios | Portal PL&C</title>
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
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/dropify.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/select2-materialize.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/app-invoice.min.css') }}">


    <style>
    * {
      box-sizing: border-box;
    }
    .wrapper {
      height: 50px;
      margin-top: calc(50vh - 150px);
      margin-left: calc(50vw - 600px);
      width: 180px;
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

    h1 {
      color: #222;
      font-size: 15px;
      font-weight: 400;
      letter-spacing: 0.05em;
      margin: 40px auto;
      text-transform: uppercase;
    }
</style>



  </head>

  <body class="vertical-layout vertical-menu-collapsible page-header-dark vertical-modern-menu preload-transitions 2-columns   " data-open="click" data-menu="vertical-modern-menu" data-col="2-columns">

    <header class="page-topbar" id="header">
      <div class="navbar navbar-fixed"> 
        <nav class="navbar-main navbar-color nav-collapsible navbar-dark no-shadow" style="background-color: gray">
            <div class="nav-wrapper">
              <div class="breadcrumbs-inline pt-3 pb-1" id="breadcrumbs-wrapper" style="margin-top: -40px;">
                <h5 class="breadcrumbs-title mt-0 mb-0 display-inline hide-on-small-and-down"><span style="color: white;">Notas Sócios</span></h5>
                <ol class="breadcrumbs mb-0">
                  <li class="breadcrumb-item"><a href="{{ route('Home.Principal.Show') }}">Home</a>
                  </li>
                  <li class="breadcrumb-item"><a href="{{ route('Painel.Gestao.Controlador.index') }}">Dashboard</a>
                  </li>
                  <li class="breadcrumb-item"><a href="{{ route('Painel.Gestao.Controlador.NotasAdvogado.index') }}">Listagem mês</a>
                  </li>
                  <li class="breadcrumb-item active" style="color: black;">Histórico
                  </li>
                </ol>
            </div>
            <ul class="navbar-list right" style="margin-top: -80px;">
              
              <li class="hide-on-large-only search-input-wrapper"><a class="waves-effect waves-block waves-light search-button" href="javascript:void(0);"><i class="material-icons">search</i></a></li>
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
       <li class="bold"><a class="collapsible-header waves-effect waves-cyan " href="JavaScript:void(0)"><i class="material-icons">person</i><span class="menu-title" data-i18n="Dashboard">Gestão</span></a>
         <div class="collapsible-body">
           <ul class="collapsible collapsible-sub" data-collapsible="accordion">
           
           @can('gestao')
           <li><a href="{{ route('Painel.Gestao.Meritocracia.index') }}" style="font-size: 11px;"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Modern">Meritocracia</span></a></li>
           @endcan

           @can('controlador_gestao')
           <li><a href="{{ route('Painel.Gestao.Controlador.index') }}" style="font-size: 11px;"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Modern">Controle gestão</span></a></li>
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

        <center>
  <div id="loading">
      <div class="wrapper">
      <div class="circle circle-1"></div>
      <div class="circle circle-1a"></div>
      <div class="circle circle-2"></div>
      <div class="circle circle-3"></div>
     </div>
     <h1 style="text-align: center;">Aguarde, estamos carregando as notas...&hellip;</h1>
     </div>
  </center>   


        <div class="row" id="paginadiv">
        <div class="content-wrapper-before blue-grey lighten-5"></div>
        <div class="col s12">
          <div class="container">

<section class="invoice-list-wrapper section">


<div class="invoice-filter-action mr-4">
<a href="#modal" class="btn-floating btn-mini waves-effect waves-light tooltipped modal-trigger"data-position="left" data-tooltip="Clique aqui para adicionar uma nova nota sócio."  style="margin-left: 5px;background-color: gray;"><i class="material-icons">add</i></a>
<a  href="{{ route('Painel.Gestao.Controlador.NotasAdvogado.exportarnotasano') }}" class="btn-floating btn-mini waves-effect waves-light buttons-right tooltipped" data-position="left" data-tooltip="Clique aqui para exportar em Excel as notas sócio no ano de apuração." style="background-color: gray;"><img style="margin-top: 8px; width: 20px;margin-left:8px;" src="{{URL::asset('/public/imgs/icon.png')}}"/></a>
</div>



  <div class="responsive-table">
    <table class="table invoice-data-table white border-radius-4 pt-1" style="width: 100%;">
      <thead>
        <tr>
          <th style="font-size: 12px"></th>
          <th style="font-size: 12px">#</th>
          <th style="font-size: 12px">Usuário</th>
          <th style="font-size: 12px">Unidade</th>
          <th style="font-size: 12px">Setor</th>
          <th style="font-size: 12px">Mês</th>
          <th style="font-size: 12px">Nível</th>
          <th style="font-size: 12px">Objetivo</th>
          <th style="font-size: 12px">Nota</th>
          <th style="font-size: 12px"></th>
        </tr>
      </thead>

      <tbody>
      @foreach($datas as $data)  
        <tr>

<!--Abre modal perguntando se deseja mesmo deletar a nota -->

<div id="modal{{$data->id}}" class="modal">
    <div class="modal-content">
    <button type="button" class="btn waves-effect mr-sm-1 mr-2 modal-close red" style="margin-left: 633px;margin-top: -30px;">
                 <i class="material-icons">close</i>
    </button>
      <h4>Deletar nota</h4>
      <p>Você deseja deletar a nota do usuário: <strong>{{mb_convert_case($data->usuario_nome, MB_CASE_TITLE, "UTF-8")}}</strong>, referente ao mes: <strong>{{$data->mes}}</strong>, do objetivo: <strong>{{$data->objetivo}}</strong> com a pontuação de: <strong>{{$data->nota}}</strong>.</p>
      <p>Ao deletar a nota favor atualizar as procedoure de nota consolidada e média score para novo cálculo. </p>
    </div>
    <div class="modal-footer">
      <a href="{{route('Painel.Gestao.Controlador.NotasAdvogado.deletarnota', $data->id)}}" class="modal-action modal-close waves-effect waves-green btn-flat" style="background-color: green;color: white"><i class="material-icons left">check</i>SIM</a>
    </div>
  </div>

<!--Fim Modal -->

          <td style="font-size: 11px"></td>
          <td style="font-size: 11px"></td>
          <td style="font-size: 11px">{{mb_convert_case($data->usuario_nome, MB_CASE_TITLE, "UTF-8")}}</td>
          <td style="font-size: 11px">{{$data->unidade}}</td>
          <td style="font-size: 11px">{{$data->setor}}</td>
          <td style="font-size: 11px">{{$data->mes}}</td>
          <td style="font-size: 11px">{{$data->nivel}}</td>
          <td style="font-size: 11px">{{$data->objetivo}}</td>
          <td style="font-size: 11px">{{$data->nota}}</td>

          <td style="font-size: 11px">
      
          <div class="invoice-action">
          <a href="{{route('Painel.Gestao.Controlador.NotasAdvogado.editar', $data->id)}}" class="invoice-action-view mr-4"><i class="material-icons">edit</i></a>
          <a href="#modal{{$data->id}}" class="invoice-action-view mr-4 modal-trigger"><i class="material-icons">close</i></a>
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


    <div id="modal" class="modal" style="width: 980px;">
    <form id="form" role="form" action="{{ route('Painel.Gestao.Controlador.NotasAdvogado.gravar') }}" method="POST" role="create" enctype="multipart/form-data" >
       {{ csrf_field() }}
       <input required type="hidden" name="opcao" id="opcao" value="">
       <div class="modal-content">
          <center>
             <div id="loadingdiv" style="display:none">
                <div class="wrapper">
                   <div class="circle circle-1"></div>
                   <div class="circle circle-1a"></div>
                   <div class="circle circle-2"></div>
                   <div class="circle circle-3"></div>
                </div>
                <h1 style="text-align: center;">Gravando registro(s)...&hellip;</h1>
             </div>
          </center>

          <div id="corpodiv">
             <a class="waves-effect modal-close waves-light btn red right align" style="margin-top: -32px; margin-right: -20px;"><i style="margin-left: 15px; font-size: 20px;" class="material-icons left">close</i></a>
             <h5>Nova nota</h5>
             <p>Deseja adicionar o informativo RV manualmente ou via importação Excel ? 
                <a href="#modalInfo" style="color: gray;" class="modal-trigger"><i class="material-icons">info</i></a>
             </p>
             <!-- Modal Structure -->
             <div id="modalInfo" class="modal" style="overflow: hidden;">
                <div class="modal-content" style="overflow: hidden;">
                   <a class="waves-effect modal-close waves-light btn red right align" style="margin-top: -20px; margin-right: -25px;"><i style="margin-left: 15px;" class="material-icons left">close</i></a>
                   <h6>Informações</h6>
                   <p>Para importação de notas em massa, utilize a planilha com o layout fixo, no qual o formato das colunas deverá ser texto.</p>
                </div>
             </div>
             <a id="btnmanualmente" onClick="manualmente();" class="modal-action waves-effect waves-green btn-flat" style="background-color: gray;color:white"><i class="material-icons left">source</i>Manualmente</a>
             <a id="btnimportacao" onClick="importacao();" class="modal-action  waves-effect btn-flat" style="background-color: gray;color:white;"><i class="material-icons left">import_export</i>Importação Excel</a>
             <a href="{{ route('Painel.Gestao.anexo', 'CartaRV.xlsx') }}" class="waves-effect waves-green btn-flat" style="background-color: green;color:white;"><i class="material-icons left">text_snippet</i>Baixar modelo importação</a>
             <br>
             <!--Div Manualmente -->
           <div id="manualmentediv" style="margin-top: 20px;">
      <div class="row">


      <div class="input-field col s4" style="margin-top: 12px;">
            <select class="select2 browser-default" id="icon_prefix" name="usuario">
                @foreach($usuarios as $usuario)
                  <option value="{{$usuario->id}}" style="font-size: 8px;">{{$usuario->name}}</option>
                @endforeach
            </select>
            <label>Usuário</label>
      </div>

        <div class="input-field col s2" style="margin-top: 12px;">
          <select class="select2 browser-default" id="mes" name="mes">
            <option value="01">Janeiro</option>
            <option value="02">Fevereiro</option>
            <option value="03">Março</option>
            <option value="04">Abril</option>
            <option value="05">Maio</option>
            <option value="06">Junho</option>
            <option value="07">Julho</option>
            <option value="08">Agosto</option>
            <option value="09">Setembro</option>
            <option value="10">Outubro</option>
            <option value="11">Novembro</option>
            <option value="12">Dezembro</option>
          </select>
          <label>Mês</label>
      </div>

        <div class="input-field col s2" style="margin-top: 12px;">
          <select class="select2 browser-default" id="ano" name="ano">
            <option value="{{$ano}}">{{$ano}}</option>
          </select>
          <label>Ano</label>
      </div>

      <div class="input-field col s3" style="margin-top: 12px;">
          <select class="select2 browser-default"  id="objetivo" name="objetivo">
            @foreach($objetivos as $objetivo)
            <option value="{{$objetivo->id}}">{{$objetivo->objetivo}}</option>
            @endforeach
          </select>
          <label>Objetivo</label>
      </div>


        <div class="input-field col s3" style="margin-top: 12px;">
          <select class="select2 browser-default"  id="nivel" name="nivel">
          <option value="Advogado">Advogado</option>
          <option value="Advogado Controladoria">Advogado Controladoria</option>
          <option value="Advogado ControladoriaSP">Advogado ControladoriaSP</option>
          <option value="COO">COO</option>
          <option value="Coordenador">Coordenador</option>
          <option value="Coordenador Controladoria">Coordenador Controladoria</option>
          <option value="Coordenador ControladoriaSP">Coordenador ControladoriaSP</option>
          <option value="Gerente">Gerente</option>
          <option value="Gerente Equipe Passiva">Gerente Equipe Passiva</option>
          <option value="Superintendente">Superintendente</option>
          <option value="Subcoordenador 1">Subcoordenador 1</option>
          <option value="Subcoordenador 2">Subcoordenador 2</option>
          </select>
          <label>Nível</label>
      </div>

        <div class="input-field col s2">
          <input id="icon_telephone" type="text" name="nota" class="validate" placeholder="Informe a nota.." style="margin-top: -2px;">
          <label for="icon_telephone">Nota</label>
        </div>
      </div>
     </div>

          </div>
          <!--Fim div manualmente -->
          <!--Div Importação -->
          <div id="importacaodiv">
             <div class="col s12 m8 l9">
                <br>
                <h6>Clique no botão abaixo e escolha um arquivo.</h6>
                <input type="file" id="input-file-now" name="select_file" accept=".xls,.xlsx,.csv" />
             </div>
          </div>
          <!--Fim div importação -->
       </div>
       <div class="modal-footer" style="margin-top: -30px;">
          <a type="button" id="btnsubmit" onClick="envia();" class="modal-action waves-effect waves-green btn-flat" style="background-color: green;color:white; margin-right: -3px; margin-top: 10px;"><i class="material-icons left">save_alt</i>Salvar</a>
       </div>
 </div>
<!--Fim Modal -->







<script src="{{ asset('/public/materialize/js/vendors.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/dropify.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/app-invoice.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/select2.full.min.js') }}"></script>

    <script src="https://pixinvent.com/materialize-material-design-admin-template/app-assets/js/scripts/form-select2.min.js"></script>
    <script src="https://pixinvent.com/materialize-material-design-admin-template/app-assets/js/scripts/form-file-uploads.min.js"></script>
    <script type='text/javascript' src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>


<script>
document.addEventListener("DOMContentLoaded", function () {
   $('.modal').modal();
   $("#manualmentediv").hide();
   $("#importacaodiv").hide();
   $("#paginadiv").hide();
});
</script>

<script>
setTimeout(function() {
   $('#loading').fadeOut('fast');
   $("#paginadiv").show();
}, 4000);
</script>

<script>
function manualmente() {

    $("#manualmentediv").show();
    $("#importacaodiv").hide();
    $("#opcao").val("manualmente");

}
</script>

<script>
function importacao() {

    $("#manualmentediv").hide();
    $("#importacaodiv").show();
    $("#opcao").val("importacao"); 
}
</script>

<script>
function envia() {

    document.getElementById("loadingdiv").style.display = "";
    document.getElementById("corpodiv").style.display = "none";
    document.getElementById("form").submit();
}    
</script>


  </body>
</html>