<!DOCTYPE html>

<html class="loading" lang="pt-br" data-textdirection="ltr">
  <head>
    <meta http-equiv="Content-Language" content="pt-br">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="author" content="Portal PL&C">
    <title>Movimentação de pessoas | Portal PL&C</title>
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
              <h5 class="breadcrumbs-title mt-0 mb-0 display-inline hide-on-small-and-down"><span style="color: white;">Movimentação de pesssoas</span></h5>
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
  <a  href="{{ route('Painel.Contratacao.RH.historico') }}" class="waves-effect waves-light btn tooltipped border-round" data-position="left" data-tooltip="Clique aqui para visualizar as solicitações já finalizadas ou canceladas." style="background-color: gray;font-size:11px;"><i"><i class="material-icons">list</i>Histórico</a>
  </div>


  <div class="responsive-table">
    <table class="table invoice-data-table white border-radius-4 pt-1">
      <thead>
        <tr>
          <th style="font-size: 11px"></th>
          <th style="font-size: 11px">Solicitante</th>
          <th style="font-size: 11px">Candidato</th>
          <th style="font-size: 11px">Setor</th>
          <th style="font-size: 11px">Tipo da vaga</th>
          <th style="font-size: 11px">Função</th>
          <th style="font-size: 11px">Classificação</th>
          <th style="font-size: 11px">Data da solicitação</th>
          <th style="font-size: 11px">Status</th>
          <th style="font-size: 11px"></th>
        </tr>
      </thead>

      <tbody>
      @foreach($datas as $data)  
        <tr>


<!--Inicio Modal Anexos --> 
<div id="anexos{{$data->id}}" class="modal modal-fixed-footer" style="width: 100%;height:100%;overflow:hidden;">

<button type="button" class="btn waves-effect mr-sm-1 mr-2 modal-close red" style="margin-left: 1255px; margin-top: 5px;">
  <i class="material-icons">close</i> 
</button>

<iframe style=" position:absolute;
top:60;
left:0;
width:100%;
height:100%;" src="{{ route('Painel.Contratacao.anexos', $data->id) }}"></iframe>

</div>
<!--Fim Modal Anexos --> 



<!--Inicio Modal Revisar --> 
<div id="revisar{{$data->id}}" class="modal" style="width: 1207px;">
    <form id="form" role="form" action="{{ route('Painel.Contratacao.RH.revisado') }}" method="POST" role="create" enctype="multipart/form-data" >
    {{ csrf_field() }}

    <input type="hidden" name="id" id="id" value="{{$data->id}}">
    <input type="hidden" name="solicitanteemail" id="solicitanteemail" value="{{$data->SolicitanteEmail}}">
    <input type="hidden" name="setorcodigo" id="setorcodigo" value="{{$data->SetorCodigo}}">

    <div class="modal-content">

  <div id="corpodiv2">

  <button type="button" class="btn waves-effect mr-sm-1 mr-2 modal-close red" style="margin-top: -26px; margin-left: 1080px;position: fixed;"><i class="material-icons">close</i></button>

  <h6>[Workflow etapa 6 - Solicitação de documentação]</h6>


      <br>

        <div class="input-field col s2">
          <input style="font-size: 10px;" readonly id="plc_porcent" type="text" name="candidatonome" value="{{ $data->candidatonome }}" class="validate">
          <label style="font-size: 11px;" for="plc_porcent">Nome do candidato:</label>
        </div>

        <div class="input-field col s3">
          <input style="font-size: 10px;" readonly id="plc_porcent" type="text" name="candidatoemail" value="{{ $data->candidatoemail }}" class="validate">
          <label style="font-size: 11px;" for="plc_porcent">E-mail do candidato:</label>
        </div>

        <div class="input-field col s2">
          <input style="font-size: 10px;" readonly id="plc_porcent" type="text" name="solicitantenome" value="{{ $data->SolicitanteNome }}" class="validate">
          <label style="font-size: 11px;" for="plc_porcent">Solicitante:</label>
        </div>

        @if($data->UsuarioNome != null)
        <div class="input-field col s2">
            <input style="font-size: 10px;" readonly id="usuario" type="text" value="{{$data->UsuarioNome}}" name="usuario" class="validate">
            <label style="font-size: 11px;" for="plc_porcent">Sócio de serviço/seletista:</label>
        </div>

        <div class="input-field col s2">
            <input style="font-size: 10px;" readonly id="datasaida" type="text" value="{{ date('d/m/Y', strtotime($data->DataSaida)) }}" name="datasaida" class="validate">
            <label style="font-size: 11px;" for="plc_porcent">Data previsão saída:</label>
        </div>
        @endif

        <div class="input-field col s2">
          <input style="font-size: 10px;" readonly id="plc_porcent" type="text" name="setordescricao" value="{{ $data->Setor }}" class="validate">
          <label style="font-size: 11px;" for="plc_porcent">Setor de custo:</label>
        </div>

        <div class="input-field col s2">
          <input style="font-size: 10px;" readonly id="funcao" type="text" name="funcao" value="{{ $data->TipoAdvogado }}" class="validate">
          <input type="hidden" name="funcaoid" id="funcaoid" value="{{$data->TipoAdvogadoCodigo}}">
          <label style="font-size: 11px;" for="funcao">Função:</label>
        </div>

        <div class="input-field col s2">
          <input style="font-size: 10px;" readonly id="plc_porcent" type="text" name="salario" value="R$ <?php echo number_format($data->Salario,2,",",".") ?>" class="validate">
          <label style="font-size: 11px;" for="plc_porcent">Distribuição mensal:</label>
        </div>


        <div class="input-field col s2">
          <input style="font-size: 10px;" readonly id="plc_porcent" type="text" name="tipovaga" value="{{ $data->TipoVaga }}" class="validate">
          <label style="font-size: 11px;" for="plc_porcent">Tipo da vaga:</label>
        </div>

        <div class="input-field col s2">
          <input style="font-size: 10px;" readonly id="plc_porcent" type="text" name="classificacao" value="{{ $data->Classificacao }}" class="validate">
          <label style="font-size: 11px;" for="plc_porcent">Classificação</label>
        </div>

        <div class="input-field col s2">
          <input style="font-size: 10px;" readonly id="plc_porcent" type="text" name="datasolicitacao" value="{{ date('d/m/Y H:i:s', strtotime($data->DataSolicitacao)) }}" class="validate">
          <label style="font-size: 11px;" for="plc_porcent">Data da solicitação:</label>
        </div>


        <div class="row">

        <div class="input-field col s12">
         <div class="form-group">
            <div class="form-group">
              <label class="control-label" style="font-size: 11px;">Observação:</label>
              <textarea id="observacao" rows="3" type="text" name="observacao" class="form-control" placeholder="Insira a observação abaixo." style="height: 3rem;text-align:left; overflow:auto;font-size: 10px;">{{$data->observacao}}</textarea>
             </div>
          </div>
        </div>   

       </div>  


       
    <div class="modal-footer" style="margin-top: 30px;">
      <button type="button" id="btnsubmit" onClick="abremodalconfirmando();" class="modal-action waves-effect waves-green btn-flat" style="background-color: gray;color:white;"><i class="material-icons left">send</i>Enviar documentação para preenchimento</button>
    </div>

    </div>
    </form>
    </div>
    </div>
<!--Fim Modal Revisar --> 


<!--Inicio Modal Demissional --> 
<div id="rescisao{{$data->id}}" class="modal" style="width: 1207px;">
    <form id="form" role="form" action="{{ route('Painel.Contratacao.RH.anexadorecisao') }}" method="POST" role="create" enctype="multipart/form-data" >
    {{ csrf_field() }}

    <input type="hidden" name="id" id="id" value="{{$data->id}}">
    <input type="hidden" name="solicitanteemail" id="solicitanteemail" value="{{$data->SolicitanteEmail}}">
    <input type="hidden" name="setorcodigo" id="setorcodigo" value="{{$data->SetorCodigo}}">

    <div class="modal-content">

  <div id="corpodiv2">

  <button type="button" class="btn waves-effect mr-sm-1 mr-2 modal-close red" style="margin-top: -26px; margin-left: 1080px;position: fixed;"><i class="material-icons">close</i></button>

  <h6>[Workflow etapa 7 - Anexar documentação de rescisão]</h6>


      <br>

      <div class="row">

      <div class="input-field col s2">
            <input style="font-size: 10px;" readonly id="usuario" type="text" value="{{$data->UsuarioNome}}" name="usuario" class="validate">
            <input type="hidden" name="usuariocpf" id="usuariocpf" value="{{$data->UsuarioCPF}}">
            <label style="font-size: 11px;" for="plc_porcent">Sócio de serviço/seletista:</label>
        </div>

       <div class="input-field col s2">
            <input style="font-size: 10px;" readonly id="datasaida" type="text" value="{{ date('d/m/Y', strtotime($data->DataSaida)) }}" name="datasaida" class="validate">
            <label style="font-size: 11px;" for="plc_porcent">Data previsão saída:</label>
        </div>

        <div class="input-field col s2">
          <input style="font-size: 10px;" readonly id="plc_porcent" type="text" name="setordescricao" value="{{ $data->Setor }}" class="validate">
          <label style="font-size: 11px;" for="plc_porcent">Setor de custo:</label>
        </div>

        </div>

        <br>

        <div class="row">
       <div class="col s12 input-field">
        <span style="font-size: 11px;">Anexar termo de devolução equipamento:</span>
        <input style="font-size: 10px;" name="termodevolucaoequipamento" id="termodevolucaoequipamento" type="file" required >
       </div>
       </div>

       <div class="row">
       <div class="col s12 input-field">
        <span style="font-size: 11px;">Anexar rescisão/contrato social:</span>
        <input style="font-size: 10px;" name="rescisao" id="rescisao" type="file" required >
       </div>
       </div>




        <div class="row">

        <div class="input-field col s12">
         <div class="form-group">
            <div class="form-group">
              <label class="control-label" style="font-size: 11px;">Observação:</label>
              <textarea id="observacao" readonly rows="3" type="text" name="observacao" class="form-control" placeholder="Insira a observação abaixo." style="height: 3rem;text-align:left; overflow:auto;font-size: 10px;">{{$data->observacao}}</textarea>
             </div>
          </div>
        </div>   

       </div>  


       
    <div class="modal-footer" style="margin-top: 30px;">
      <button type="button" id="btnsubmit" onClick="abremodalconfirmando();" class="modal-action waves-effect waves-green btn-flat" style="background-color: gray;color:white;"><i class="material-icons left">save</i>Salvar documentação de rescisão</button>
    </div>

    </div>
    </form>
    </div>
<!--Fim Modal rescisao --> 




          <td style="font-size: 10px"></td>
          <td style="font-size: 10px">{{ $data->SolicitanteNome }}</td>
          <td style="font-size: 10px">{{ $data->candidatonome }}</td>
          <td style="font-size: 10px">{{ $data->Setor }}</td>
          <td style="font-size: 10px">{{ $data->TipoVaga }}</td>
          <td style="font-size: 10px">{{ $data->TipoAdvogado }}</td>
          <td style="font-size: 10px">{{ $data->Classificacao }}</td>
          <td style="font-size: 10px">{{ date('d/m/Y H:i:s', strtotime($data->DataSolicitacao)) }}</td>
          <td style="font-size: 10px"><span class="bullet yellow"></span>{{$data->Status}}</td>

          <td style="font-size: 10px">
          <div class="invoice-action">

           @if($data->StatusID == 5)
           <a href="#revisar{{$data->id}}" class="invoice-action-view mr-4 tooltipped modal-trigger" data-position="left" data-tooltip="Clique aqui para revisar os dados desta solicitação de contratação."><i class="material-icons">remove_red_eye</i></a> 
           @elseif($data->StatusID == 8)
           <a href="{{ route('Painel.Contratacao.RH.revisadados', $data->id) }}" class="invoice-action-view mr-4 tooltipped modal-trigger" data-position="left" data-tooltip="Clique aqui para revisar as informações inseridas pelo candidato."><i class="material-icons">list</i></a> 
           @endif

           @if($data->TipoVaga == "Substituição" && $data->status_substituicao == null)
           <a href="#rescisao{{$data->id}}" class="invoice-action-view mr-4 tooltipped modal-trigger" data-position="left" data-tooltip="Clique aqui para anexar os termos de rescisão."><i class="material-icons">person_add_disabled</i></a> 
           @endif


           <a href="#anexos{{$data->id}}" class="invoice-action-view mr-4 tooltipped modal-trigger" data-position="left" data-tooltip="Clique aqui para visualizar os anexos desta solicitação de contratação."><i class="material-icons">attach_file</i></a> 

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

    <div id="loadingdiv3" style="display:none; margin-left: 600px; margin-top: -300px;">
      <img style="width: 100px; margin-top: -100px;" src="{{URL::asset('/public/imgs/loading.gif')}}"/>
    </div>

    <div id="modalconfirmacao" class="modal"  style="width: 30% !important;height: 30% !important;">

    <div id="corpodiv3">
    <div class="modal-content">
      <center><p style="font-size: 18px;">Confirma o envio do e-mail ao candidato para preenchimento das informações?</p></center>
    </div>
    <div class="modal-footer">
      <a  class="modal-action  waves-effect waves-red btn-flat " style="background-color: red;color:white;font-size:11px;" onClick="nao();"><i class="material-icons left">close</i>Não</a>
      <a  class="modal-action  waves-effect waves-green btn-flat " style="background-color: green;color:white;font-size:11px;" onClick="sim();"><i class="material-icons left">check</i>Sim</a>
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




  </body>
</html>