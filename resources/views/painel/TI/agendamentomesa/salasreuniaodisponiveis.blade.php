<!DOCTYPE html>

<html class="loading" lang="pt-br" data-textdirection="ltr">
  <head>
    <meta http-equiv="Content-Language" content="pt-br">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="author" content="Portal PL&C">
    <title>Salas de reuniões | Portal PL&C</title>
    <link rel="apple-touch-icon" href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/images/favicon/apple-touch-icon-152x152.png">
    <link rel="shortcut icon" href="{{ asset('/public/assets/imgs/casinha.png')}}" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('/public/materialize/css/vendors.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/responsive.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/materialize.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/app-sidebar.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/app-contacts.min.css') }}">


    <style>
    * {
      box-sizing: border-box;
    }
    .wrapper {
      height: 50px;
      margin-top: calc(50vh - 25px);
      margin-left: calc(50vw - 100px);
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
        animation: move 1s ease-in-out infinite;
      }
      .circle-1a {
        animation: fade 1s ease-in-out infinite;
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
        margin-left: -55px;
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
              <h5 class="breadcrumbs-title mt-0 mb-0 display-inline hide-on-small-and-down"><span style="color: white;">Agendamento</span></h5>
              <ol class="breadcrumbs mb-0">
                <li class="breadcrumb-item"><a href="{{ route('Home.Principal.Show') }}">Home</a>
                </li>
                <li class="breadcrumb-item"><a href="{{ route('Painel.TI.agendamentomesa.index') }}">Agendamento</a>
                </li>
                <li class="breadcrumb-item active" style="color: black;">Salas de reunião disponíveis
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

       <li class="bold"><a class="collapsible-header waves-effect waves-cyan " href="JavaScript:void(0)"><i class="material-icons">calendar_today</i><span class="menu-title" data-i18n="Escritorio">Escritório</span></a>
         <div class="collapsible-body">
           <ul class="collapsible collapsible-sub" data-collapsible="accordion">
           
           @can('users')
           <li><a href="{{ route('Painel.TI.agendamentomesa.index') }}" style="font-size: 11px;"><i class="material-icons">add</i><span data-i18n="Modern">Novo agendamento</span></a></li>
           <li><a href="{{ route('Painel.TI.agendamentomesa.restrito.index') }}" style="font-size: 11px;"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Modern">Dashboard</span></a></li>
           <li><a href="{{ route('Painel.TI.agendamentomesa.restrito.salas.index') }}" style="font-size: 11px;"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Modern">Listagem</span></a></li>
           @endcan
           
           </ul>
         </div>
       </li>

     </ul>
     <div class="navigation-background"></div><a class="sidenav-trigger btn-sidenav-toggle btn-floating btn-medium waves-effect waves-light hide-on-large-only" href="#" data-target="slide-out"><i class="material-icons">menu</i></a>
   </aside>

    <!-- BEGIN: Page Main-->
    <div id="main">
      <div class="row">

      <!-- <center>
 <div id="loading">
      <div class="wrapper">
      <div class="circle circle-1"></div>
      <div class="circle circle-1a"></div>
      <div class="circle circle-2"></div>
      <div class="circle circle-3"></div>
     </div>
     <h1 style="text-align: center;">Aguarde, enquanto buscamos as salas de reunião disponíveis..&hellip;</h1>
     </div> 
  </center>    -->

  <div id="loadingdiv" style="display:none">
      <div class="wrapper">
      <div class="circle circle-1"></div>
      <div class="circle circle-1a"></div>
      <div class="circle circle-2"></div>
      <div class="circle circle-3"></div>
     </div>
     <h1 style="text-align: center;">Aguarde, enquanto estamos reservando a sala de reunião...&hellip;</h1>
     </div>

        <div class="content-wrapper-before blue-grey lighten-5"></div>
        <div class="col s12" id="corpodiv">
          <div class="container">

          <div class="card">
        <div class="card-content">
            <p class="caption mb-0" style="font-size: 13px;">
            O quadro abaixo retrata as salas de reunião disponíveis de acordo com os parâmetros selecionados.
            </p>
        </div>
    </div>

<div class="contact-overlay"></div>

<!-- Sidebar Area Starts -->
<div class="sidebar-left sidebar-fixed" style="margin-top: -18px;">
  <div class="sidebar">
    <div class="sidebar-content">
      <div class="sidebar-header">
        <div class="card" style="width: 260px;">
          <div class="card-content">
            <div class="sidebar-details">
                <h5 class="card-title" style="color: black"><i class="material-icons app-header-icon text-top" style="font-size: 25px;">calendar_today</i> Agendamento</h5>
              <div class="mt-10 pt-2">
                <p class="m-0 subtitle font-weight-700" style="color: black">Data</p>
                <p class="m-0 text-muted" style="color: black">{{ date('d/m/Y', strtotime($startTime)) }}</p>
              </div>
              <div class="mt-10 pt-2">
                <p class="m-0 subtitle font-weight-700" style="color: black">Hora ínicio</p>
                <p class="m-0 text-muted" style="color: black">{{ date('H:i:s', strtotime($startTime)) }}</p>
              </div>
              <div class="mt-10 pt-2">
                <p class="m-0 subtitle font-weight-700" style="color: black">Hora fim</p>
                <p class="m-0 text-muted" style="color: black">{{ date('H:i:s', strtotime($endTime)) }}</p>
              </div>
              <div class="mt-10 pt-2">
                <p class="m-0 subtitle font-weight-700" style="color: black">Unidade</p>
                <p class="m-0 text-muted" style="color: black">{{$unidade}} - {{$unidade_descricao}}</p>
              </div>
            </div>
          </div>
        </div>
      </div>


      <a href="#" data-target="contact-sidenav" class="sidenav-trigger hide-on-large-only"><i
          class="material-icons">menu</i></a>
    </div>
  </div>
</div>
<!-- Sidebar Area Ends -->

<!-- Content Area Starts -->
<div class="content-area content-right">
  <div class="app-wrapper">
    <div class="datatable-search">
      <i class="material-icons mr-2 search-icon">search</i>
      <input type="text" placeholder="Buscar sala..." class="app-filter" id="global_filter">
    </div>

    <div id="button-trigger" class="card card card-default scrollspy border-radius-6 fixed-width">
      
      <div class="card-content p-0">
      <table id="data-table-contact" class="display" style="width:100%">
          <thead>
            <tr>
            <th style="font-size: 13px;">Sala</th>
            <th style="font-size: 13px;">Andar</th>
            <th style="font-size: 13px;">Capacidade de Participantes</th>
            <th><input type="hidden" onClick="toggle(this)" /></th>
            <th></th>
              <th></th>
              <th></th>
              
            </tr>
          </thead>
          <tbody>

          @foreach($datas as $data)
            @if($data->tipo == $tiposala_id && $data->unidade == $unidade)
            <tr>
            <td style="font-size: 12px;" data-sala="{{$data->sala}}">{{$data->sala}}</td>
            <td style="font-size: 12px;" data-andar="{{$data->andar}}">{{$data->andar}}</td>
            <td style="font-size: 12px;" data-quantidade="{{$data->quantidade}}">{{$data->quantidade}}</td>
            <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            @endif
          @endforeach  

          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

</div>
<!-- Content Area Ends -->

<!--  Contact sidebar -->
<div class="contact-compose-sidebar">
  <div class="card quill-wrapper">
    <div class="card-content pt-0">
      <div class="card-header display-flex pb-2" >
        <h3 class="card-title contact-title-label">Novo agendamento</h3>

      </div>
      <div class="divider"></div>

      <form role="form" id="form" class="edit-contact-item mb-5 mt-5" action="{{ route('Painel.TI.agendamentomesa.agendamentoreuniao') }}" method="POST" role="search">
      {{ csrf_field() }}
        <div class="row">

        <input type="hidden" name="unidade" id="unidade" value="{{$unidade}}">
        <input type="hidden" name="unidade_descricao" id="unidade_descricao" value="{{$unidade_descricao}}">
        <input type="hidden" name="horarioid_inicio" id="horarioid_inicio" value="{{$horarioid_inicio}}">
        <input type="hidden" name="horarioid_fim" id="horarioid_fim" value="{{$horarioid_fim}}">
        <input type="hidden" name="startTime" id="startTime" value="{{$startTime}}">
        <input type="hidden" name="endTime" id="endTime" value="{{$endTime}}">
        <input type="hidden" name="datainicio" id="datainicio" value="{{$datainicio}}">

          <div class="input-field col s11">
            <i class="material-icons prefix">view_array</i>
            <input id="sala" name="sala" type="text" readonly>
            <label for="sala" style="color: black">Sala</label>
          </div>

          <div class="input-field col s11">
            <i class="material-icons prefix">business</i>
            <input id="andar" name="andar" type="text" readonly>
            <label for="andar" style="color: black">Andar</label>
          </div>

          <div class="input-field col s11">
            <i class="material-icons prefix">format_list_numbered</i>
            <input id="quantidade" name="quantidade"  type="number" required placeholder="Informe o número de participantes...">
            <label for="quantidade" style="color: black">Número de participantes:</label>
          </div>

        </div>

        <div class="row">


<div class="card" style="background-color:#D3D3D3;color:black;">
        <div class="card-content">
            <p class="caption mb-0">
            Informe abaixo o nome e e-mail dos participantes. Os participantes receberão um convite no Outlook junto com as informações do local e data.
            </p>
        </div>
    </div>

      
          <div class="input-field col s11" style="max-height: 200px; overflow-x: hidden; width: 580px;">

            <a style="margin-top: 7px;" class="btn-floating add btn-mini waves-effect waves-light green right align tooltipped"
           data-position="top" data-tooltip="Adicionar novo participante"
            style="width: 35px; height: 35px;"><i style="margin-top: -1px;" class="material-icons">add</i></a>

            <table style="margin-left: 45px; width: 491px;" id="item_table">
              <thead id="header-fixed">
                <tr>
                  <th data-field="nome">Nome</th>
                  <th data-field="email">E-mail</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                <td><input id="nome_novoparticipante" placeholder="Informe o nome do participante." name="nome_novoparticipante[]" type="text"></td>
                <td><input id="email_novoparticipante" placeholder="Informe o e-mail do participante."name="email_novoparticipante[]" type="email"></td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="input-field col s11">
            <i class="material-icons prefix">person</i>
            <input id="cliente" name="cliente" placeholder="Informe caso necessário o nome/razão do cliente..." type="text">
            <label for="cliente" style="color: black">Nome / Razão Cliente:</label>
          </div>

          <div class="input-field col s11">
            <i class="material-icons prefix">list</i>
            <input id="assunto" name="assunto" required placeholder="Digite o assunto da reunião..." type="text">
            <label for="assunto" style="color: black">Assunto</label>
          </div>

          <div class="input-field col s11">
            <i class="material-icons prefix">list</i>
            <textarea id="textarea2" name="observacao" required placeholder="Informe observação caso necessário" class="materialize-textarea"></textarea>
            <label for="textarea2" style="color: black">Observação</label>
          </div>

          </div>

      <center>
      <div class="card-action pl-0 pr-0">
        <button type="button" id="btnsubmit" onClick="carrega();" class="btn-small waves-effect waves-light update-contact display-none green">
        <span> <i class="material-icons right">check</i> Registrar agendamento</span></button>
      </div>
      </center>

      </form>

    </div>
  </div>

</div>

</div>

    <script src="{{ asset('/public/materialize/js/vendors.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>


<script>
function carrega() {
    document.getElementById("loadingdiv").style.display = "";
    document.getElementById("corpodiv").style.display = "none";
    document.getElementById("form").submit();
}
</script>

<script>
// $(document).ready(function(){
//    $("#corpodiv").hide();
// });
</script>


<script>
$(document).ready(function(){

  function toggle(e) {

    checkboxes=document.getElementsByName("foo");
    for(var a=0, o=checkboxes.length; a<o; a++)checkboxes[a].checked=e.checked
}


function resizetable() {
    $(".app-page .dataTables_scrollBody").css( {
            maxHeight:$(window).height()-420+"px"
        }

    )
}

$(document).ready(function() {
        "use strict"; var a=$("#data-table-contact").DataTable( {
                scrollY:$(window).height()-380+"px", scrollCollapse: !0, scrollX: !1, paging: !0, responsive: !0, lengthMenu:[15], aoColumns:[ {
                    bSortable: !1
                }

                , {
                    bSortable: !1
                }

                , null, null, null, {
                    bSortable: !1
                }

                , {
                    bSortable: !1
                }

                ], fnInitComplete:function() {
                    new PerfectScrollbar(".dataTables_scrollBody")
                }

                , fnDrawCallback:function(e) {
                    new PerfectScrollbar(".dataTables_scrollBody")
                }
            }

        ); if($("input#global_filter").on("keyup click", function() {
                    a.search($("#global_filter").val(), $("#global_regex").prop("checked"), $("#global_smart").prop("checked")).draw()
                }

            ), $("input.column_filter").on("keyup click", function() {
                    var e; e=$(this).parents("tr").attr("data-column"), a.column(e).search($("#col"+e+"_filter").val(), $("#col"+e+"_regex").prop("checked"), $("#col"+e+"_smart").prop("checked")).draw()
                }

            ), 0<$("#sidebar-list").length)new PerfectScrollbar("#sidebar-list", {
                theme:"dark"
            }

        ); $(".app-page .favorite i").on("click", function(e) {
                e.preventDefault(), $(this).toggleClass("amber-text")
            }

        ), $("#contact-sidenav").sidenav( {
                onOpenStart:function() {
                    $("#sidebar-list").addClass("sidebar-show")
                }

                , onCloseEnd:function() {
                    $("#sidebar-list").removeClass("sidebar-show")
                }
            }

        ), $(document).on("click", ".app-page i.delete", function() {
                var e=$(this).closest("tr"); e.prev().hasClass("parent")&&e.prev().remove(), e.remove()
            }

        ), $("#contact-sidenav li").on("click", function() {
                var e=$(this); e.hasClass("sidebar-title")||($("li").removeClass("active"), e.addClass("active"))
            }

        ), $(".modal").modal(), 900<$(window).width()&&$("#contact-sidenav").removeClass("sidenav"); var e=$(".contact-overlay"), o=$(".update-contact"), t=$(".add-contact"), s=$(".contact-compose-sidebar"), n=$("label[for]"); if($(".contact-sidebar-trigger").on("click", function() {
                    e.addClass("show"), o.addClass("display-none"), t.removeClass("display-none"), s.addClass("show"), n.removeClass("active"), $(".contact-compose-sidebar input").val("")
                }

            ), $(".contact-compose-sidebar .update-contact, .contact-compose-sidebar .close-icon, .contact-compose-sidebar .add-contact, .contact-overlay").on("click", function() {
                    e.removeClass("show"), s.removeClass("show")
                }

              ), $(".dataTables_scrollBody tr").on("click", function() {

                var sala = $(this).closest('tr').find('td[data-sala]').data('sala');
                var andar = $(this).closest('tr').find('td[data-andar]').data('andar');
                var quantidade = $(this).closest('tr').find('td[data-quantidade]').data('quantidade');

                var input = document.getElementById("quantidade");
                
                    o.removeClass("display-none"), 
                    t.addClass("display-none"), 
                    e.addClass("show"), 
                    s.addClass("show"), 
                    $("#sala").val(sala), 
                    $("#andar").val(andar), 
                    $("#quantidade").val(quantidade),
                    input.setAttribute("max",quantidade);

                    n.addClass("active")
                }

            ).on("click", ".checkbox-label,.favorite,.delete", function(e) {
                    e.stopPropagation()
                }

            ), 0<s.length)new PerfectScrollbar(".contact-compose-sidebar", {
                theme:"dark", wheelPropagation: !1
            }

        ); 0<$("html[data-textdirection='rtl']").length&&$("#contact-sidenav").sidenav( {
                edge:"right", onOpenStart:function() {
                    $("#sidebar-list").addClass("sidebar-show")
                }

                , onCloseEnd:function() {
                    $("#sidebar-list").removeClass("sidebar-show")
                }
            }

        )
    }

),
$(".sidenav-trigger").on("click", function() {
        $(window).width()<960&&($(".sidenav").sidenav("close"), $(".app-sidebar").sidenav("close"))
    }

),
$(window).on("resize", function() {
        resizetable(), 899<$(window).width()&&$("#contact-sidenav").removeClass("sidenav"), $(window).width()<900&&$("#contact-sidenav").addClass("sidenav")
    }

),
resizetable(),
$(window).width()<900&&($(".sidebar-left.sidebar-fixed").removeClass("animate fadeUp animation-fast"), $(".sidebar-left.sidebar-fixed .sidebar").removeClass("animate fadeUp"));

});
</script>

<script>
  $(document).ready(function(){
         
          
       $(document).on('click', '.add', function(){
           var html = '';
           html += '<tr>';
           html += '<td><input id="nome_novoparticipante" placeholder="Informe o nome do participante." name="nome_novoparticipante[]" type="text"></td>';
           html += '<td><input id="email_novoparticipante" placeholder="Informe o e-mail do participante." name="email_novoparticipante[]" type="email"></td>'; 
           html += '<td><button type="button" name="remove" class="btn-floating btn-large waves-effect waves-light red remove tooltipped" data-position="top" data-tooltip="Clique aqui para deletar esta linha." style="width: 35px; height: 35px;"><i class="material-icons" style="margin-top: -30px;">remove</i></button></td></tr>';
           $('#item_table').append(html);
      });
          
      $(document).on('click', '.remove', function(){
           $(this).closest('tr').remove();
      });
          
          
          
  });
  </script>

  </body>
</html>