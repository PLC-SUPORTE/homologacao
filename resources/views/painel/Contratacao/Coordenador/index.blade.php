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

.hori-timeline .events {
    border-top: 3px solid #e9ecef;
}
.hori-timeline .events .event-list {
    display: block;
    position: relative;
    text-align: center;
    padding-top: 70px;
    margin-right: 0;
}
.hori-timeline .events .event-list:before {
    content: "";
    position: absolute;
    height: 36px;
    border-right: 2px dashed #dee2e6;
    top: 0;
}
.hori-timeline .events .event-list .event-date {
    position: absolute;
    top: 38px;
    left: 0;
    right: 0;
    width: 75px;
    margin: 0 auto;
    border-radius: 4px;
    padding: 2px 4px;
}
@media (min-width: 1140px) {
    .hori-timeline .events .event-list {
        display: inline-block;
        width: 24%;
        padding-top: 45px;
    }
    .hori-timeline .events .event-list .event-date {
        top: -12px;
    }
}
.bg-soft-primary {
    background-color: rgba(64,144,203,.3)!important;
}
.bg-soft-success {
    background-color: rgba(71,189,154,.3)!important;
}
.bg-soft-danger {
    background-color: rgba(231,76,94,.3)!important;
}
.bg-soft-warning {
    background-color: rgba(249,213,112,.3)!important;
}
.card {
    border: none;
    margin-bottom: 24px;
    -webkit-box-shadow: 0 0 13px 0 rgba(236,236,241,.44);
    box-shadow: 0 0 13px 0 rgba(236,236,241,.44);
}


</style>

  </head>

  <body class="vertical-layout vertical-menu-collapsible page-header-dark vertical-modern-menu preload-transitions 2-columns   " data-open="click" data-menu="vertical-modern-menu" data-col="2-columns">

    <header class="page-topbar" id="header">
      <div class="navbar navbar-fixed"> 
        <nav class="navbar-main navbar-color nav-collapsible navbar-dark no-shadow" style="background-color: gray">
          <div class="nav-wrapper">


          <div class="breadcrumbs-inline pt-3 pb-1" id="breadcrumbs-wrapper" style="margin-top: -40px;">
              <h5 class="breadcrumbs-title mt-0 mb-0 display-inline hide-on-small-and-down"><span style="color: white;">Movimentação de pessoas</span></h5>
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
  <a  href="{{ route('Painel.Contratacao.Coordenador.historico') }}" class="waves-effect waves-light btn tooltipped border-round" data-position="left" data-tooltip="Clique aqui para visualizar as solicitações já finalizadas ou canceladas." style="background-color: gray;font-size:11px;"><i"><i class="material-icons">list</i>Histórico</a>
  </div>


  <div class="responsive-table">
    <table class="table invoice-data-table white border-radius-4 pt-1">
      <thead>
        <tr>
          <th style="font-size: 11px"></th>
          <th style="font-size: 11px">Nome candidato</th>
          <th style="font-size: 11px">Setor</th>
          <th style="font-size: 11px">Tipo da vaga</th>
          <th style="font-size: 11px">Função</th>
          <th style="font-size: 11px">Classificação</th>
          <th style="font-size: 11px">Data da solicitação</th>
          <th style="font-size: 11px">Ultima modificação</th>
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

  <div id="loadingdiv" style="display:none; margin-left: 600px; margin-top: -300px;">
    <img style="width: 100px; margin-top: -100px;" src="{{URL::asset('/public/imgs/loading.gif')}}"/>
  </div>

    <!--Inicio Modal Revisar --> 
    <div id="modalrevisar{{$data->id}}" class="modal" style="width: 1207px;">
    <form id="form2" role="form" onsubmit="btnsubmit2.disabled = true; return true;" onsubmit="btnsubmit3.disabled = true; return true;"  action="{{ route('Painel.Contratacao.Coordenador.solicitacaorevisada') }}" method="POST" role="create" enctype="multipart/form-data" >
    {{ csrf_field() }}

    <input type="hidden" name="id" id="id" value="{{$data->id}}">

    <div class="modal-content">



  <div id="corpodiv2">

  <button type="button" class="btn waves-effect mr-sm-1 mr-2 modal-close red" style="margin-top: -26px; margin-left: 1080px;position: fixed;"><i class="material-icons">close</i></button>

  <h6>[Workflow etapa 2 - Revisão de solicitação de nova contratação]</h6>


        <div class="card-panel border-radius-6 white-text gradient-45deg-deep-purple-gray card-animation-2">
        
        <div class="row">

        <div class="input-field col s2">
          <span style="font-size: 11px;color:black;">Nome do candidato:</span>
          <input style="font-size: 10px;" readonly placeholder="Informe o nome do candidato." id="plc_porcent" type="text" value="{{$data->candidatonome}}" name="candidatonome" class="validate">
        </div>

        <div class="input-field col s2">
          <span style="font-size: 11px;color:black;">E-mail do candidato:</span>
          <input style="font-size: 10px;" readonly placeholder="Informe o e-mail do candidato." id="plc_porcent" type="text" value="{{$data->candidatoemail}}" name="candidatoemail" class="validate">
        </div>

        @if($data->UsuarioNome != null)
        <div class="input-field col m2 s12">
            <span style="font-size: 11px;color:black;">Sócio de serviço/seletista:</span>
            <input style="font-size: 10px;color:black;" readonly id="usuario" type="text" value="{{$data->UsuarioNome}}" name="usuario" class="validate">
        </div>

        <div class="input-field col m3 s12">
            <span style="font-size: 11px;color:black;">Data previsão saída:</span>
            <input style="font-size: 10px;color:black;" readonly id="datasaida" type="text" value="{{ date('d/m/Y H:i:s', strtotime($data->DataSaida)) }}" name="datasaida" class="validate">
        </div>
        @endif

        <div class="input-field col m2 s12">
            <span style="font-size: 11px;color:black;">Setor de custo:</span>
            <input style="font-size: 10px;color:black;" readonly id="plc_porcent" type="text" value="{{$data->Setor}}" name="setordescricao" class="validate">
            <input type="hidden" name="setor" value="{{$data->SetorCodigo}}">
        </div>

        <div class="input-field col m2 s12">
            <span style="font-size: 11px;color:black;">Função:</span>
            <input style="font-size: 10px;color:black;" readonly id="plc_porcent" type="text" value="{{$data->TipoAdvogado}}" name="tipocargo" class="validate">
        </div>

        <div class="input-field col s2">
          <span style="font-size: 11px;color:black;">Distribuição mensal:</span>
          <input style="font-size: 10px;"  readonly name="salario" id="salario" type="text" value="<?php echo number_format($data->Salario,2,",",".") ?>" maxlength="8"  pattern="(?:\.|,|[0-9])*" class="form-control" placeholder="Valor(R$)" onKeyPress="return(moeda2(this,'.',',',event))" data-toggle="tooltip" data-placement="top" title="Preencha o salário." required="required">
        </div>

        <div class="input-field col s2">
          <span style="font-size: 11px;color:black;">Classificação:</span>
          <input style="font-size: 10px;"  readonly name="classificacao" id="classificacao" type="text" value="{{$data->Classificacao}}"  class="form-control" placeholder="Valor(R$)" data-toggle="tooltip" data-placement="top"  required="required">
        </div>


        </div>

        <div class="row">

        <div class="input-field col s12" style="margin-top: -15px;">
         <div class="form-group">
            <div class="form-group">
              <label class="control-label" style="font-size: 11px;color:black;">Observação:</label>
                <textarea id="observacao" rows="3" type="text" name="observacao" class="form-control" placeholder="Insira a observação abaixo." style="height: 3rem;text-align:left; overflow:auto;font-size: 10px;">{{$data->observacao}}</textarea>
             </div>
          </div>
        </div>   
       </div>  
    
    <div class="modal-footer" style="margin-top: 0px;">
      <button type="submit"   name="acao" value="2"  class="modal-action waves-effect waves-green btn-flat" style="background-color: gray;color:white;font-size: 11px;"><i class="material-icons left">close</i>Glosar solicitação</button>
      <button type="button"  name="acao"  onClick="abremodalconfirmando2();" value="1" class="modal-action waves-effect waves-green btn-flat" style="background-color: gray;color:white;font-size: 11px;"><i class="material-icons left">save</i>Aprovar solicitação</button>
    </div>

    </div>
    </form>
    </div>
    </div>
    </div>
<!--Fim Modal Revisar solicitação --> 


          <td style="font-size: 10px"></td>
          <td style="font-size: 10px">{{ $data->candidatonome }}</td>
          <td style="font-size: 10px">{{ $data->Setor }}</td>
          <td style="font-size: 10px">{{ $data->TipoVaga }}</td>
          <td style="font-size: 10px">{{ $data->TipoAdvogado }}</td>
          <td style="font-size: 10px">{{ $data->Classificacao }}</td>
          <td style="font-size: 10px">{{ date('d/m/Y H:i:s', strtotime($data->DataSolicitacao)) }}</td>
          <td style="font-size: 10px">{{ date('d/m/Y H:i:s', strtotime($data->DataModificacao)) }}</td>
          <td style="font-size: 10px"><span class="bullet yellow"></span>{{$data->Status}}</td>

          <td style="font-size: 10px">
          <div class="invoice-action">

          <!--Se estiver aguardando revisão do Conselho pode estar editando --> 
           @if($data->StatusID == 1)
           <a href="#modalrevisar{{$data->id}}" class="invoice-action-view mr-4 tooltipped modal-trigger" data-position="bottom" data-tooltip="Clique aqui para revisar está solicitação."><i class="material-icons">remove_red_eye</i></a>
           @endif
           <a href="#anexos{{$data->id}}" class="invoice-action-view mr-4 tooltipped modal-trigger" data-position="left" data-tooltip="Clique aqui para visualizar os anexos desta solicitação de contratação."><i class="material-icons">attach_file</i></a> 
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

    <div id="loadingdiv4" style="display:none; margin-left: 600px; margin-top: -300px;">
      <img style="width: 100px; margin-top: -100px;" src="{{URL::asset('/public/imgs/loading.gif')}}"/>
</div>


    <div id="modalconfirmacao2" class="modal"  style="width: 30% !important;height: 30% !important;">


    <div id="corpodiv4">
    <div class="modal-content">
      <center><p style="font-size: 18px;">Deseja aprovar a solicitação de nova contratação?</p></center>
    </div>
    <div class="modal-footer">
      <a  class="modal-action  waves-effect waves-red btn-flat " style="background-color: red;color:white;font-size:11px;" onClick="confirmanao();"><i class="material-icons left">close</i>Não</a>
      <a  class="modal-action  waves-effect waves-green btn-flat " style="background-color: green;color:white;font-size:11px;" onClick="confirmasim();"><i class="material-icons left">check</i>Sim</a>
    </div>
  </div>
  </div>


      <!--Inicio Modal Nova solicitação --> 
<div id="modal" class="modal" style="width: 1207px;">
    <form id="form" role="form" onsubmit="btnsubmit.disabled = true; return true;"  action="{{ route('Painel.Contratacao.Coordenador.store') }}" method="POST" role="create" enctype="multipart/form-data" >
    {{ csrf_field() }}

    <div class="modal-content">

  <div id="corpodiv2">

  <button type="button" class="btn waves-effect mr-sm-1 mr-2 modal-close red" style="margin-top: -26px; margin-left: 1080px;position: fixed;"><i class="material-icons">close</i></button>

      <div id="corponovavagadiv">
      <h6>[Workflow etapa 1 - Cadastro de solicitação de nova vaga]</h6>
      <p style="font-size: 11px;">Favor informar os dados para cadastro de uma nova vaga.</p>
      </div>

      <div id="corposubstituicaodiv" style="display: none">
      <h6>[Workflow etapa 1 - Cadastro de solicitação de substituição]</h6>
      <p style="font-size: 11px;">Favor informar os dados para cadastro de uma solicitação para substituição.</p>
      </div>

      <br>

        <div class="row">

        <div class="input-field col m2 s12">
        <span style="font-size: 11px;">Selecione o tipo:</span>
            <select style="font-size: 10px;" readonly class="select2 browser-default" id="tipocontratacao" name="tipocontratacao"  data-toggle="tooltip" data-placement="top" title="Selecione o motivo da contratação." required="required">
                <option value="Nova vaga">Nova vaga</option>
                <option value="Substituição">Substituição</option>
            </select>
        </div>

        <div class="input-field col s2">
          <span style="font-size: 11px;">Nome do candidato:</span>
          <input style="font-size: 10px;" placeholder="Informe o nome do candidato." id="candidatonome" type="text" name="candidatonome" class="validate">
        </div>

        <div class="input-field col s2">
          <span style="font-size: 11px;">E-mail do candidato:</span>
          <input style="font-size: 10px;" placeholder="Informe o e-mail do candidato." id="candidatoemail" type="text" name="candidatoemail" class="validate">
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
            <span style="font-size: 11px;">Selecione a função:</span>
            <select style="font-size: 10px;" class="select2 browser-default" id="tipocargo" name="tipocargo"  data-toggle="tooltip" data-placement="top" title="Selecione o tipo de cargo." required="required">
                <option value="" selected></option>
                @foreach($tiposadvogado as $tipoadvogado)   
                <option value="{{$tipoadvogado->Codigo}}">{{$tipoadvogado->Descricao}}</option>
                @endforeach
            </select>
        </div>

        <div class="input-field col s2">
          <span style="font-size: 11px;">Distribuição mensal:</span>
          <input style="font-size: 10px;"  name="salario" id="salario" type="text" maxlength="8" value="00,00" pattern="(?:\.|,|[0-9])*" class="form-control" placeholder="Valor(R$)" onKeyPress="return(moeda2(this,'.',',',event))" data-toggle="tooltip" data-placement="top" title="Preencha o salário." required="required">
        </div>


        </div>

        <div class="row">

        <div class="input-field col m2 s12">
        <span style="font-size: 11px;">Selecione a classificação:</span>
            <select style="font-size: 10px;" class="select2 browser-default" id="classificacao" name="classificacao"  data-toggle="tooltip" data-placement="top" title="Selecione a classificação." required="required">
                <option value="" selected></option>
                <option value="Fluxo normal">Fluxo normal</option>
                <option value="Urgente">Urgente</option>
            </select>
        </div>

        <div class="input-field col m2 s12">
        <span style="font-size: 11px;">Data prevista para entrada:</span>
        <input style="font-size: 10px;"  min="{{$dataurgente}}" name="dataentrada" id="dataentrada" type="date"  class="form-control" data-toggle="tooltip" data-placement="top" title="Informe a data prevista para entrada." required="required">
        </div>

        <div  id="substituicaodiv" style="display: none">
        <div class="input-field col m3 s12">
            <span style="font-size: 11px;">Selecione o sócio de serviço/seletista:</span>
            <select style="font-size: 10px;" class="select2 browser-default" id="usuario" name="usuario"  data-toggle="tooltip" data-placement="top" title="Selecione o sócio de serviço/seletista.">
                @foreach($usuarios as $usuario)   
                <option value="{{$usuario->cpf}}">{{$usuario->name}}</option>
                @endforeach
            </select>
        </div>

        <div class="input-field col m2 s12">
        <span style="font-size: 11px;">Data prevista para saída:</span>
        <input style="font-size: 10px;"  name="datasaida" min="{{$dataurgente}}" id="datasaida" type="date"  class="form-control" data-toggle="tooltip" data-placement="top" title="Informe a data saída do sócio de serviço/seletista." >
        </div>

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

       <div class="row">
        <div class="col s12 input-field">
        <span style="font-size: 11px;">Currículo:</span>
        <input style="font-size: 10px;"  name="curriculo" id="curriculo" value="" type="file" required>
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

  <div id="loadingdiv3" style="display:none; margin-left: 600px; margin-top: -300px;">
      <img style="width: 100px; margin-top: -100px;" src="{{URL::asset('/public/imgs/loading.gif')}}"/>
</div>

<div id="modalconfirmacao" class="modal"  style="width: 30% !important;height: 30% !important;">
    <div id="corpodiv3">
    <div class="modal-content">
      <center><p style="font-size: 18px;">Deseja confirmar a solicitação de nova contratação?</p></center>
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
      function envia() {
      
          document.getElementById("loadingdiv").style.display = "";
          document.getElementById("corpodiv2").style.display = "none";
          document.getElementById("form2").submit();
      }    
</script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>

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

<script>
  function abremodalconfirmando() {

    //Verifico se todos os campso estão preenchidos, se não mostro alert 
    var nomecandidato = $('#candidatonome').val();
    var emailcandidato = $('#candidatoemail').val();
    var funcao = $('#tipocargo').val();
    var classificacao = $('#classificacao').val();
    var curriculo = $('#curriculo').val();

    if ($('#curriculo').val().length != 0){

      $('.modal').modal();
      $('#modalconfirmacao').modal('open');

    } else {

      alert('Favor preencher todos os campos e anexar o currículo.')

    }

         
  }    
</script>


<script>
  function nao() {

    window.location.reload(true);
         
  }    
</script>

<script>
  function sim() {
    $('.modal').css('display', 'none');
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
  function confirmanao() {

    window.location.reload(true);
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
$('#classificacao').change(function(){

    var newdate = new Date();

    var classificacao = $('#classificacao option:selected').val()

    //Se for fluxo normal 12 dias
    if(classificacao == "Fluxo normal") {
      document.getElementById('dataentrada').value = "{{$dataurgente}}";

    } 
    //Se for Urgente 2 dias
    else {
      document.getElementById('dataentrada').value = "{{$datafluxopadrao}}";

    }


});
</script>


<script>
$('#tipocontratacao').change(function(){


    var classificacao = $('#tipocontratacao option:selected').val()

    //Se for fluxo normal 12 dias
    if(classificacao == "Nova vaga") {

      document.getElementById("corponovavagadiv").style.display = "";
      document.getElementById("corposubstituicaodiv").style.display = "none";
      document.getElementById("substituicaodiv").style.display = "none";
    } 
    //Se for Urgente 2 dias
    else {
      document.getElementById("corponovavagadiv").style.display = "none";
      document.getElementById("corposubstituicaodiv").style.display = "";
      document.getElementById("substituicaodiv").style.display = "";

    }


});
</script>


  </body>
</html>