<!DOCTYPE html>

<html class="loading" lang="pt-br" data-textdirection="ltr">
  <head>
    <meta http-equiv="Content-Language" content="pt-br">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="author" content="Portal PL&C">
    <title>Meus agendamentos | PL&C Advogados</title>
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


            <div class="breadcrumbs-inline pt-3 pb-1" id="breadcrumbs-wrapper" style="margin-top: -45px;">
              <h5 class="breadcrumbs-title mt-0 mb-0 display-inline hide-on-small-and-down"><span style="color: white;">Agendamento</span></h5>
              <ol class="breadcrumbs mb-0">
                <li class="breadcrumb-item"><a href="{{ route('Home.Principal.Show') }}">Home</a>
                </li>
                <li class="breadcrumb-item active" style="color: black;">Meus agendamentos
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
                <h6>NOTIFICA????ES<span class="new badge">{{$totalNotificacaoAbertas}}</span></h6>
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
             <li><a href="{{ route('Painel.PesquisaPatrimonial.supervisao.solicitacoes') }}"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Solicita????es pesquisa">Solicita????es pesquisa</span></a></li>
             @endcan

             @can('financeiro_pesquisapatrimonial')
             <li><a href="{{ route('Painel.PesquisaPatrimonial.financeiro.index') }}"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Dashboard">Dashboard</span></a></li>
             <li><a href="{{ route('Painel.PesquisaPatrimonial.financeiro.solicitacoes') }}"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Dashboard">Solicita????es pesquisa</span></a></li>
             <li><a href=""><i class="material-icons">radio_button_unchecked</i><span data-i18n="Dashboard">Movimenta????o bancaria</span></a></li>
             @endcan

             @can('users')
             <li><a href="{{ route('Painel.PesquisaPatrimonial.supervisao.equipe.index') }}"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Equipe pesquisa">Equipe</span></a></li>
             <li><a href="{{ route('Painel.PesquisaPatrimonial.tiposdeservico') }}"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Tipos servi??o">Tipos de servi??o</span></a></li>
             <li><a href="{{ route('Painel.PesquisaPatrimonial.supervisao.grupos.index') }}"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Grupos cliente">Grupos cliente</span></a></li>
             <li><a href="{{ route('Painel.PesquisaPatrimonial.supervisao.clientes.index') }}"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Clientes">Clientes</span></a></li>
             @endcan

           </ul>
         </div>
       </li>

     <li class="bold"><a class="collapsible-header waves-effect waves-cyan " href="JavaScript:void(0)"><i class="material-icons">preview</i><span class="menu-title" data-i18n="Dashboard">Controladoria</span></a>
         <div class="collapsible-body">
           <ul class="collapsible collapsible-sub" data-collapsible="accordion">

      <!--V??deos -->
           <li class="bold"><a class="collapsible-header waves-effect waves-cyan " href="JavaScript:void(0)"><i class="material-icons">preview</i><span class="menu-title" data-i18n="Dashboard">V??deos</span></a>
         <div class="collapsible-body">
           <ul class="collapsible collapsible-sub" data-collapsible="accordion">

             <li><a href="" style="font-size: 11px;"><i class="material-icons">movie</i><span data-i18n="Modern">Proc. para utilizar o formul??rio ficha tempo.</span></a></li>
             <li><a href="" style="font-size: 11px;"><i class="material-icons">movie</i><span data-i18n="Modern">Proc. para a movimenta????o e cumprimento de prazos.</span></a></li>

           </ul>
         </div>
          </li>
      <!--V??deos -->    

      <!--Manual --> 
      <li class="bold"><a class="collapsible-header waves-effect waves-cyan " href="JavaScript:void(0)"><i class="material-icons">library_books</i><span class="menu-title" data-i18n="Dashboard">Arquivos</span></a>
         <div class="collapsible-body">
           <ul class="collapsible collapsible-sub" data-collapsible="accordion">

             <li><a href="{{route('Home.Principal.treinamento', 'ApresentacaoSLA.pdf')}}" style="font-size: 11px;"><i class="material-icons">movie</i><span data-i18n="Modern">Apresenta????o Jur??dico Tenco.</span></a></li>
             <li><a href="{{route('Home.Principal.treinamento', 'Treinamento01.pdf')}}" style="font-size: 11px;"><i class="material-icons">movie</i><span data-i18n="Modern">Cartilha de provisionamento Alfresco.</span></a></li>
             <li><a href="{{route('Home.Principal.treinamento', 'Treinamento02.pdf')}}" style="font-size: 11px;"><i class="material-icons">movie</i><span data-i18n="Modern">Cartilha provisionamento PLC.</span></a></li>
             <li><a href="{{route('Home.Principal.treinamento', 'TreinamentoGPA.pdf')}}" style="font-size: 11px;"><i class="material-icons">movie</i><span data-i18n="Modern">Cartilha de provisionamento GPA.</span></a></li>
             <li><a href="{{route('Home.Principal.treinamento',  'Treinamento04.pdf')}}" style="font-size: 11px;"><i class="material-icons">movie</i><span data-i18n="Modern">Manual Cumprimento de prazos _2019</span></a></li>
             <li><a href="{{route('Home.Principal.treinamento',  'MANUAL-GED_2020.pdf')}}" style="font-size: 11px;"><i class="material-icons">movie</i><span data-i18n="Modern">Manual de indexa????o de arquivos no GED</span></a></li>

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
             <li><a href="{{ route('Painel.Coordenador.index') }}" style="font-size: 11px;"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Modern">Aprova????o de solicita????o de pagamento.</span></a></li>
             <li><a href="{{ route('Painel.Coordenador.acompanharSolicitacoes') }}" style="font-size: 11px;"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Modern">Fluxo de processo em curso.</span></a></li>
             @endcan

             @can('advogado')
             <li><a href="{{ route('Painel.Advogado.index') }}" style="font-size: 11px;"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Modern">Fluxo de processo em curso.</span></a></li>
             @endcan

             @can('financeiro')
             <li><a href="{{ route('Painel.Financeiro.index') }}" style="font-size: 11px;"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Modern">Aprova????o - Solicita????o de pagamento.</span></a></li>
             <li><a href="{{ route('Painel.Financeiro.aprovadas') }}" style="font-size: 11px;"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Modern">Fluxo de processo em curso.</span></a></li>
             @endcan 

             @can('financeiro contas a pagar')
             <li><a href="{{ route('Painel.Financeiro.programadas') }}" style="font-size: 11px;"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Modern">Realizar concilia????o banc??ria.</span></a></li>
             <li><a href="{{ route('Painel.Financeiro.realizarconciliacao') }}" style="font-size: 11px;"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Modern">Realizar concilia????o banc??ria por faixa.</span></a></li>
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
           <li><a href="{{ route('Painel.TI.users.index') }}" style="font-size: 11px;"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Modern">Gerenciar usu??rios.</span></a></li>
           <li><a href="{{url('/painel/perfis')}}" style="font-size: 11px;"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Modern">Gerenciar profiles.</span></a></li>
           <li><a href="{{url('/painel/setorcusto')}}" style="font-size: 11px;"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Modern">Gerenciar setor de custo.</span></a></li>
           <li><a href="{{url('/painel/permissoes')}}" style="font-size: 11px;"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Modern">Gerenciar permiss??es.</span></a></li>
           @endcan
           
           <li><a href="#"><i class="material-icons" style="font-size: 11px;">not_interested</i><span data-i18n="Modern" style="font-size: 11px;">Em desenvolvimento.</span></a></li>

           </ul>
         </div>
       </li>

       <li class="bold"><a class="collapsible-header waves-effect waves-cyan " href="JavaScript:void(0)"><i class="material-icons">calendar_today</i><span class="menu-title" data-i18n="Escritorio">Escrit??rio</span></a>
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
   <!-- END: SideNav-->

   <!-- BEGIN: Page Main-->
   <div id="main">
      <div class="row">
        <div class="content-wrapper-before blue-grey lighten-5"></div>
        <div class="col s12">
          <div class="container">

          <!-- <div class="card">
        <div class="card-content">
            <p class="caption mb-0">
            O PLC, atento ??s moderniza????es do mercado corporativo, ??s ferramentas tecnol??gicas, ??s novas formas de trabalho e relacionamento profissional, ?? experi??ncia exitosa que estamos vivenciando neste home office implementado pelo distanciamento social ocasionado pela COVID-19, aliado ao alto grau de responsabilidade e comprometimento de cada profissional do PLC, implantar?? uma estrutura de home office definitivo com rod??zio para as atividades presenciais (???Home Office Estrutural???) em todas as suas unidades.
            </p>
            <p>A implanta????o do Home Office Estrutural ocorrer?? de forma faseada, da seguinte forma:</p>
            <p>??? <strong>Fase 1</strong> - Retorno presencial apenas de parte dos profissionais, em escala de rod??zio em dias
alternados durante a semana. O rod??zio ser?? realizado em grupos fixos, evitando-se o contato
entre pessoas de grupos diferentes.</p>
            <p>??? <strong>Fase 2</strong> - Retorno presencial de todos os profissionais, ainda em escala de rod??zio, de acordo com a
escala definida para cada Unidade do PLC.</p>
            <p>??? <strong>Fase 3</strong> - Implanta????o do sistema definitivo de trabalho a partir das informa????es obtidas durante
as duas primeiras fases.</p>
        </div>
        <p><a href="{{route('Home.Principal.anexo', 'planohomeoffice.pdf')}}" class="btn-floating btn-mini waves-effect waves-light tooltipped"data-position="left" data-tooltip="Clique aqui para baixar o Plano Home Office Estruturado."  style="margin-left: 96%;margin-top: -50px;background-color: gray;"><i class="material-icons">text_snippet</i></a></p>
    </div> -->

<section class="invoice-list-wrapper section">


  <div class="invoice-create-btn">
    <a href="{{ route('Painel.TI.agendamentomesa.agenda') }}" class="btn waves-effect waves-light invoice-create border-round z-depth-4" style="background-color: gray">
      <i class="material-icons">today</i>
      <span class="hide-on-small-only tooltipped" data-position="left" data-tooltip="Clique aqui para vizualizar sua agenda.">Agenda</span>
    </a>
  </div>

  <div class="invoice-create-btn">
    <a href="#modal" class="btn waves-effect waves-light invoice-create border-round z-depth-4 btn modal-trigger tooltipped" data-position="left" data-tooltip="Clique aqui para realizar um nova agendamento de esta????o de trabalho ou sala de reuni??o." style="background-color: gray">
      <i class="material-icons">add</i>
      <span class="hide-on-small-only">Novo agendamento</span>
    </a>
  </div>


  <div class="responsive-table">
    <table class="table invoice-data-table white border-radius-4 pt-1">
      <thead>
        <tr>
          <th style="font-size: 11px">#</th>
          <th style="font-size: 11px"><span>#</span></th>
          <th style="font-size: 11px">Mesa</th>
          <th style="font-size: 11px">Andar</th>
          <th style="font-size: 11px">Unidade</th>
          <th style="font-size: 11px">Qtd. participantes</th>
          <th style="font-size: 11px">Data ??nicio</th>
          <th style="font-size: 11px">Data fim</th>
          <th style="font-size: 11px">Status</th>
          <th style="font-size: 11px"></th>
        </tr>
      </thead>

      <tbody>
      @foreach($datas as $data)  
        <tr>


<!--Modal perguntando -->
<div id="modalcancelar{{$data->id}}" class="modal" style="width: 1207px;">
    <form id="form2" onsubmit="btnsubmit.disabled = true; return true;" role="form" action="{{ route('Painel.TI.agendamentomesa.agendamentocancelado') }}" method="POST" role="create" enctype="multipart/form-data" >
    {{ csrf_field() }}

    <input type="hidden" name="id" id="id" value="{{$data->id}}">

    <div class="modal-content">

    <center>
  <div id="loadingenvia" style="display:none">
      <div class="wrapper">
      <div class="circle circle-1"></div>
      <div class="circle circle-1a"></div>
      <div class="circle circle-2"></div>
      <div class="circle circle-3"></div>
     </div>
     <h1 style="text-align: center;">Aguarde, estamos cancelando o agendamento...&hellip;</h1>
     </div>
  </center>   

  <div id="corpodiv2">

  <button type="button" class="btn waves-effect mr-sm-1 mr-2 modal-close red" style="margin-top: -26px; margin-left: 1080px;position: fixed;"><i class="material-icons">close</i></button>

      <h6>Cancelar agendamento</h6>
      <p style="font-size: 11px;">Deseja cancelar o agendamento abaixo ?

      <br>

        <div class="input-field col s3">
          <input style="font-size: 10px;" readonly id="plc_porcent" type="text" name="mesa_descricao" value="{{ $data->Descricao }}" class="validate">
          <label style="font-size: 11px;" for="plc_porcent">Local:</label>
        </div>


        @if($data->Tipo == 2)
        <div class="input-field col s2">
          <input style="font-size: 10px;" id="icon_telephone" readonly type="text" name="quantidadeparticipantes" value="{{$data->QuantidadeParticipantes}}" class="validate" >
          <label style="font-size: 11px;" for="icon_telephone">Qtd. participantes:</label>
        </div>
        @else 
        <div class="input-field col s2">
          <input style="font-size: 10px;" readonly id="plc_porcent" type="text" name="corredor" value="<?php echo substr($data->Corredor, 0, 1); ?>" class="validate">
          <label style="font-size: 11px;" for="plc_porcent">Corredor:</label>
        </div>
        @endif

        <div class="input-field col s1">
          <input style="font-size: 10px;" id="icon_telephone" readonly type="text" name="andar"  value="{{ $data->Andar}}" class="validate">
          <label style="font-size: 11px;" for="icon_telephone">Andar:</label>
        </div>

        <div class="input-field col s1">
          <input style="font-size: 10px;" id="icon_telephone" readonly type="text" name="unidade" value="{{$data->UnidadeDescricao}}" class="validate" >
          <label style="font-size: 11px;" for="icon_telephone">Unidade:</label>
        </div>

        <div class="input-field col s2">
          <input style="font-size: 10px;" id="icon_telephone" readonly type="text" value="{{ date('d/m/Y H:i', strtotime($data->DataInicio)) }}" name="datainicio" class="validate">
          <label style="font-size: 11px;" for="icon_telephone">Data ??nicio:</label>
        </div>

        <div class="input-field col s2">
          <input style="font-size: 10px;" id="icon_telephone" readonly type="text" value="{{ date('d/m/Y H:i', strtotime($data->DataFim)) }}" name="datafim" class="validate">
          <label style="font-size: 11px;" for="icon_telephone">Data fim:</label>
        </div>


      </div>

    <div class="modal-footer" style="margin-top: 90px;">
      <button type="submit" id="btnsubmit"  class="modal-action waves-effect waves-green btn-flat" style="background-color: gray;color:white;"><i class="material-icons left">close</i>Cancelar</button>
    </div>

    </div>
    </form>
    </div>
<!--Fim Modal -->




          <td style="font-size: 10px">#</td>
          <td style="font-size: 10px">{{ $data->id }}</td>
          <td style="font-size: 10px">{{ $data->Descricao }}</td>
          <td style="font-size: 10px">{{ $data->Andar}}</td>
          <td style="font-size: 10px">{{$data->UnidadeDescricao}}</td>
          <td style="font-size: 10px">{{$data->QuantidadeParticipantes}}</td>
          <td style="font-size: 10px">{{ date('d/m/Y H:i', strtotime($data->DataInicio)) }}</td>
          <td style="font-size: 10px">{{ date('d/m/Y H:i', strtotime($data->DataFim)) }}</td>
          @if($data->Status == 2)
          <td style="font-size: 10px"><span class="bullet yellow"></span>{{$data->StatusDescricao}}</td>
          @elseif($data->Status == 3)
          <td style="font-size: 10px"><span class="bullet red"></span>{{$data->StatusDescricao}}</td>
          @else
          <td style="font-size: 10px"><span class="bullet green"></span>{{$data->StatusDescricao}}</td>
          @endif
          <td style="font-size: 10px">
      
              <a href="#modalcancelar{{$data->id}}" class="invoice-action-view mr-4 modal-trigger tooltipped" data-position="top" data-tooltip="Clique aqui para cancelar este agendamento. "><i class="material-icons">close</i></a>


          </div> 
          </td>

        </tr>
        @endforeach
        
        
      </tbody>
    </table>
  </div>


   <!--Modal Dados -->
   <div id="modal" class="modal" style="width: 1200px; height: 270px; overflow: hidden;">
      <div class="modal-content">
      <div class="row">

      <center>
  <div id="loadingdiv" style="display:none; margin-top: -110px;">
  <div class="wrapper">
                  <img style="width: 50px;" src="{{URL::asset('/public/imgs/loading.gif')}}"/>
    </div>
     <h1 style="text-align: center;">Buscando locais dispon??veis...&hellip;</h1>
     </div>
  </center>   

     <div id="seleciona">

  <div class="col s12">
      <div class="card-content">
        <div class="card-title">
          <div class="row">

            <button type="button" class="btn waves-effect mr-sm-1 mr-2 modal-close red" style="margin-top: -26px; margin-left: 1095px;"><i class="material-icons">close</i></button>

            <div class="col s12 m6 l10" style="margin-left: -12px;">
              <h6 class="card-title"><b>Agendamento</b></h6>
            </div>
          </div>
        </div>
        <div id="view-icon-prefixes">
          <br>
          <div class="row"  style="margin-top: -15px;">
          <form role="form" id="form" action="{{ route('Painel.TI.agendamentomesa.mesasdisponiveis') }}" method="POST" role="search">
           {{ csrf_field() }}    

           <div class="row">

            <div class="col s2" style="border: 1px solid #7E7D7D; height: 160px; border-radius: 20px;">
              <div class="container" style="margin-top: 50px;">
                {{-- <label style="color: black;">Selecione o tipo da sala:</label><br> --}}
                <label>
                  <input class="with-gap"  type="radio" style="color: black;" checked id="estacaotrabalho" name="sala" value="1"/>
                  <span style="color: black; font-size: 12px;">Esta????o de trabalho</span>
                </label> 
                
                <br>
  
                <label>
                <input class="with-gap" type="radio" id="salareuniao" name="sala" value="2"/><span style="color: black; font-size: 12px;">Sala de reuni??o</span>
                </label>
              </div>
              </div>

              <div style="border: 1px solid #7E7D7D; height: 160px; border-radius: 20px; width: 950px; margin-left: 220px;">
                <div class="container">
                  <div class="col s3">
                    <div class="container">
                      <label style="color: black;">Agendar para data de hoje ?</label><br>
    
                      <label>
                        <input type="checkbox" class="filled-in" checked onClick="naoehoje();" name="verificadata" id="checkdata"/>
                        <span  style="color: black; font-size: 13px;">Sim</span>
                      </label>
                    </div>
                  </div>
    
                  <div class="col s2">
                    <label style="color: black;">Selecione a unidade:</label>
                    <div class="input-field">
                      <select class="browser-default" id="unidade" name="unidade" required style="font-size: 13px;">                  
                            @foreach($unidades as $unidade)
                            @if($unidade->codigo == 1.3)
                            <option value="{{$unidade->codigo}}" selected>{{$unidade->codigo}} - {{$unidade->descricao}}</option>
                            @else 
                            <option value="{{$unidade->codigo}}">{{$unidade->codigo}} - {{$unidade->descricao}}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                  </div>
    
                  <div class="col s3" id="datadiv">
                    <label for="datainicio" style="color: black;">Informe a data</label>
                    <input id="datainicio" name="datainicio" required min="{{$datahojeformato}}" value="{{$datahojeformato}}" type="date" class="datepicke" style="margin-top: 14px; font-size: 13px;">
                </div>
    
                 
                 <div class="col s2" id="horarioiniciodiv">
                  <label style="color: black;">Selecione o ??nicio:</label>
                    <div class="input-field">
                    <select class="browser-default" id="horarioiniciohoje" name="horarioiniciohoje" style="font-size: 13px;">
                    <option value="" selected></option>
                    @foreach($horarioshojeentrada as $horario)
                    <option value="{{$horario->id}}">{{$horario->descricao}}</option>
                    @endforeach
                    </select>
                    </div>
                  </div>  
    
                  <div class="col s2" id="horariofimdiv">
                  <label style="color: black;">Selecione o fim:</label>
                    <div class="input-field">
                    <select class="browser-default" id="horariofimhoje" name="horariofimhoje" style="font-size: 13px;">
                    </select>
                    </div>
                  </div>  
    
                  <div class="col s2" id="horarioiniciodiv2">
                  <label style="color: black;">Selecione o ??nicio:</label>
                    <div class="input-field">
                    <select class="browser-default" id="horarioinicio" name="horarioinicio" style="font-size: 13px;">
                    <option value="" selected></option>
                    @foreach($horariosentrada as $horario2)
                    <option value="{{$horario2->id}}">{{$horario2->descricao}}</option>
                    @endforeach
                    </select>
                    </div>
                  </div>  
    
                  <div class="col s2" id="horariofimdiv2">
                  <label style="color: black;">Selecione o fim:</label>
                    <div class="input-field">
                    <select class="browser-default" id="horariofim" name="horariofim" style="font-size: 13px;">
                    @foreach($horariossaida as $horariofim2)
                    <option value="{{$horariofim2->id}}">{{$horariofim2->descricao}}</option>
                    @endforeach
                    </select>
                    </div>
                  </div>  

                
      
                </div>

                </div>

                <button class="btn waves-effect waves-light" 
                type="button" id="btnsubmit" onClick="carrega();" 
                style="background-color: gray;color:white; margin-top: -75px; margin-left: 1000px; border-radius: 20px;">Avan??ar
                <i class="material-icons left">arrow_forward</i>
                </button>
               

            
            </div>


      
           
             </div> 
             
             <br>

            </form>
          </div>
        </div>
       
       </div>

    </div>
  </div>

  </div>
      </div>

   </div>



</section>

</div>
          <div class="content-overlay"></div>
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


    @if(Session::has('message'))
            <script type="text/javascript">

              alert('{{ Session::get('message')['msg'] }}');
              // M.toast({html: toastHTML});
            </script>
    @endif()


<script>

$('.with-gap').click(function() {
   if($('#estacaotrabalho').is(':checked')) {
     
     //Se for esta????o de trabalho retira unidade FOR, MAO E RJO
     $('#unidade').not(this).find('option[value="1.4"]').attr('disabled', true);
     $('#unidade').not(this).find('option[value="1.5"]').attr('disabled', true);
     $('#unidade').not(this).find('option[value="1.7"]').attr('disabled', true);
    } 
    else {
     $('#unidade').not(this).find('option[value="1.4"]').attr('disabled', false);
     $('#unidade').not(this).find('option[value="1.5"]').attr('disabled', false);
     $('#unidade').not(this).find('option[value="1.7"]').attr('disabled', false);
    }

});

</script>

<script>
function naoehoje() {

  //Se for a data de hoje
  if($('#checkdata').prop('checked')) {

    document.getElementById("horariofimdiv").style.display = "";
    document.getElementById("horarioiniciodiv").style.display = "";
    document.getElementById("datadiv").style.display = "none";
    document.getElementById("horariofimdiv2").style.display = "none";
    document.getElementById("horarioiniciodiv2").style.display = "none";
   
  } else {

    document.getElementById("horariofimdiv").style.display = "none";
    document.getElementById("horarioiniciodiv").style.display = "none";
    document.getElementById("datadiv").style.display = "";
    document.getElementById("horariofimdiv2").style.display = "";
    document.getElementById("horarioiniciodiv2").style.display = "";

}

}
</script>


<script>
  function selecionardia() {
      document.getElementById("horarioiniciodiv").style.display = "";
      document.getElementById("horariofimdiv").style.display = "";
  }
  </script>
  
  
  <script>
  function carrega() {
      document.getElementById("loadingdiv").style.display = "";
      document.getElementById("seleciona").style.display = "none";
      document.getElementById("form").submit();
  }
  </script>
  
  <script>
        function envia() {
        
            document.getElementById("loadingenvia").style.display = "";
            document.getElementById("corpodiv2").style.display = "none";
            document.getElementById("form2").submit();
        }    
  </script>
  
  
  <script>
  $(document).ready(function(){
     $('.modal').modal();
     document.getElementById("datadiv").style.display = "none";
     document.getElementById("horariofimdiv2").style.display = "none";
     document.getElementById("horarioiniciodiv2").style.display = "none";
     $('#unidade').not(this).find('option[value="1.4"]').attr('disabled', true);
     $('#unidade').not(this).find('option[value="1.5"]').attr('disabled', true);
     $('#unidade').not(this).find('option[value="1.7"]').attr('disabled', true);
  });
  </script>

    <!--Horarios de Hoje -->
    <script type="text/javascript" >
      $(document).ready(function(){
            $(document).on('change', '#horarioiniciodiv', function(){
              var html = '';
              var horarioescolhido = $('#horarioiniciohoje').val();
          
          if(horarioescolhido == 1) {
            
          <?php
          $connect = new PDO("sqlsrv:Server=192.168.150.14,65014;Database=Intranet", "six", "89202");
          //$connect = new PDO("sqlsrv:Server=localhost;Database=intranet", "six", "89202");
          function fill_unit_select_box1($connect)
          { 
           $output = '';
           $query = "SELECT * FROM TI_AgendamentoMesa_horarios Where id > 1 ORDER BY id ASC";
           $statement = $connect->prepare($query);
           $statement->execute();
           $result = $statement->fetchAll();
           foreach($result as $row)
           {
            $output .= '<option value="'.$row["id"].'">'.$row["descricao"].'</option>';
           }
           return $output;
          }
          
          ?>
          $('#horariofimhoje option').remove();
          html += '<?php echo fill_unit_select_box1($connect)?>'; 
              $('#horariofimhoje').append(html);
          
          }
          
           else if(horarioescolhido == 2) {
            <?php
          $connect = new PDO("sqlsrv:Server=192.168.150.14,65014;Database=Intranet", "six", "89202");
          //$connect = new PDO("sqlsrv:Server=localhost;Database=intranet", "six", "89202");
          function fill_unit_select_box2($connect)
          { 
           $output = '';
           $query = "SELECT * FROM TI_AgendamentoMesa_horarios Where id > 2 ORDER BY id ASC";
           $statement = $connect->prepare($query);
           $statement->execute();
           $result = $statement->fetchAll();
           foreach($result as $row)
           {
            $output .= '<option value="'.$row["id"].'">'.$row["descricao"].'</option>';
           }
           return $output;
          }
        
        
          ?>
          $('#horariofimhoje option').remove();
          html += '<?php echo fill_unit_select_box2($connect)?>'; 
              $('#horariofimhoje').append(html);
          } else if(horarioescolhido == 3) {
            <?php
          $connect = new PDO("sqlsrv:Server=192.168.150.14,65014;Database=Intranet", "six", "89202");
          //$connect = new PDO("sqlsrv:Server=localhost;Database=intranet", "six", "89202");
          function fill_unit_select_box3($connect)
          { 
           $output = '';
           $query = "SELECT * FROM TI_AgendamentoMesa_horarios Where id > 3 ORDER BY id ASC";
           $statement = $connect->prepare($query);
           $statement->execute();
           $result = $statement->fetchAll();
           foreach($result as $row)
           {
            $output .= '<option value="'.$row["id"].'">'.$row["descricao"].'</option>';
           }
           return $output;
          }
      
          ?>
          $('#horariofimhoje option').remove();
          html += '<?php echo fill_unit_select_box3($connect)?>'; 
              $('#horariofimhoje').append(html);
          } else if(horarioescolhido == 4) {
            <?php
          $connect = new PDO("sqlsrv:Server=192.168.150.14,65014;Database=Intranet", "six", "89202");
          //$connect = new PDO("sqlsrv:Server=localhost;Database=intranet", "six", "89202");
          function fill_unit_select_box4($connect)
          { 
           $output = '';
           $query = "SELECT * FROM TI_AgendamentoMesa_horarios Where id > 4 ORDER BY id ASC";
           $statement = $connect->prepare($query);
           $statement->execute();
           $result = $statement->fetchAll();
           foreach($result as $row)
           {
            $output .= '<option value="'.$row["id"].'">'.$row["descricao"].'</option>';
           }
           return $output;
          }
          ?>
          $('#horariofimhoje option').remove();
          html += '<?php echo fill_unit_select_box4($connect)?>'; 
              $('#horariofimhoje').append(html);
          } else if(horarioescolhido == 5) {
            <?php
          $connect = new PDO("sqlsrv:Server=192.168.150.14,65014;Database=Intranet", "six", "89202");
          //$connect = new PDO("sqlsrv:Server=localhost;Database=intranet", "six", "89202");
          function fill_unit_select_box5($connect)
          { 
           $output = '';
           $query = "SELECT * FROM TI_AgendamentoMesa_horarios Where id > 5 ORDER BY id ASC";
           $statement = $connect->prepare($query);
           $statement->execute();
           $result = $statement->fetchAll();
           foreach($result as $row)
           {
            $output .= '<option value="'.$row["id"].'">'.$row["descricao"].'</option>';
           }
           return $output;
          }
          ?>
          
          $('#horariofimhoje option').remove();
          html += '<?php echo fill_unit_select_box5($connect)?>'; 
              $('#horariofimhoje').append(html);
          } else if(horarioescolhido == 6) {
            <?php
          $connect = new PDO("sqlsrv:Server=192.168.150.14,65014;Database=Intranet", "six", "89202");
          //$connect = new PDO("sqlsrv:Server=localhost;Database=intranet", "six", "89202");
          function fill_unit_select_box6($connect)
          { 
           $output = '';
           $query = "SELECT * FROM TI_AgendamentoMesa_horarios Where id > 6 ORDER BY id ASC";
           $statement = $connect->prepare($query);
           $statement->execute();
           $result = $statement->fetchAll();
           foreach($result as $row)
           {
            $output .= '<option value="'.$row["id"].'">'.$row["descricao"].'</option>';
           }
           return $output;
          }
          ?>
          $('#horariofimhoje option').remove();
          html += '<?php echo fill_unit_select_box6($connect)?>'; 
              $('#horariofimhoje').append(html);
          } else if(horarioescolhido == 7) {
            <?php
          $connect = new PDO("sqlsrv:Server=192.168.150.14,65014;Database=Intranet", "six", "89202");
          //$connect = new PDO("sqlsrv:Server=localhost;Database=intranet", "six", "89202");
          function fill_unit_select_box7($connect)
          { 
           $output = '';
           $query = "SELECT * FROM TI_AgendamentoMesa_horarios Where id > 7 ORDER BY id ASC";
           $statement = $connect->prepare($query);
           $statement->execute();
           $result = $statement->fetchAll();
           foreach($result as $row)
           {
            $output .= '<option value="'.$row["id"].'">'.$row["descricao"].'</option>';
           }
           return $output;
          }
          ?>
          $('#horariofimhoje option').remove();
          html += '<?php echo fill_unit_select_box7($connect)?>'; 
              $('#horariofimhoje').append(html);
          } else if(horarioescolhido == 8) {
            <?php
          $connect = new PDO("sqlsrv:Server=192.168.150.14,65014;Database=Intranet", "six", "89202");
          //$connect = new PDO("sqlsrv:Server=localhost;Database=intranet", "six", "89202");
          function fill_unit_select_box8($connect)
          { 
           $output = '';
           $query = "SELECT * FROM TI_AgendamentoMesa_horarios Where id > 8 ORDER BY id ASC";
           $statement = $connect->prepare($query);
           $statement->execute();
           $result = $statement->fetchAll();
           foreach($result as $row)
           {
            $output .= '<option value="'.$row["id"].'">'.$row["descricao"].'</option>';
           }
           return $output;
          }
          ?>
          $('#horariofimhoje option').remove();
          html += '<?php echo fill_unit_select_box8($connect)?>'; 
              $('#horariofimhoje').append(html);
          } else if(horarioescolhido == 9) {
            <?php
          $connect = new PDO("sqlsrv:Server=192.168.150.14,65014;Database=Intranet", "six", "89202");
          //$connect = new PDO("sqlsrv:Server=localhost;Database=intranet", "six", "89202");
          function fill_unit_select_box9($connect)
          { 
           $output = '';
           $query = "SELECT * FROM TI_AgendamentoMesa_horarios Where id > 9 ORDER BY id ASC";
           $statement = $connect->prepare($query);
           $statement->execute();
           $result = $statement->fetchAll();
           foreach($result as $row)
           {
            $output .= '<option value="'.$row["id"].'">'.$row["descricao"].'</option>';
           }
           return $output;
          }
          ?>
          $('#horariofimhoje option').remove();
          html += '<?php echo fill_unit_select_box9($connect)?>'; 
              $('#horariofimhoje').append(html);
          } else if(horarioescolhido == 10) {
            <?php
          $connect = new PDO("sqlsrv:Server=192.168.150.14,65014;Database=Intranet", "six", "89202");
          //$connect = new PDO("sqlsrv:Server=localhost;Database=intranet", "six", "89202");
          function fill_unit_select_box10($connect)
          { 
           $output = '';
           $query = "SELECT * FROM TI_AgendamentoMesa_horarios Where id > 10 ORDER BY id ASC";
           $statement = $connect->prepare($query);
           $statement->execute();
           $result = $statement->fetchAll();
           foreach($result as $row)
           {
            $output .= '<option value="'.$row["id"].'">'.$row["descricao"].'</option>';
           }
           return $output;
          }
          ?>
          $('#horariofimhoje option').remove();
          html += '<?php echo fill_unit_select_box10($connect)?>'; 
              $('#horariofimhoje').append(html);
          } else if(horarioescolhido == 11) {
            <?php
          $connect = new PDO("sqlsrv:Server=192.168.150.14,65014;Database=Intranet", "six", "89202");
          //$connect = new PDO("sqlsrv:Server=localhost;Database=intranet", "six", "89202");
          function fill_unit_select_box11($connect)
          { 
           $output = '';
           $query = "SELECT * FROM TI_AgendamentoMesa_horarios Where id > 11 ORDER BY id ASC";
           $statement = $connect->prepare($query);
           $statement->execute();
           $result = $statement->fetchAll();
           foreach($result as $row)
           {
            $output .= '<option value="'.$row["id"].'">'.$row["descricao"].'</option>';
           }
           return $output;
          }
          ?>
          $('#horariofimhoje option').remove();
          html += '<?php echo fill_unit_select_box11($connect)?>'; 
              $('#horariofimhoje').append(html);
          } else if(horarioescolhido == 12) {
            <?php
          $connect = new PDO("sqlsrv:Server=192.168.150.14,65014;Database=Intranet", "six", "89202");
          //$connect = new PDO("sqlsrv:Server=localhost;Database=intranet", "six", "89202");
          function fill_unit_select_box12($connect)
          { 
           $output = '';
           $query = "SELECT * FROM TI_AgendamentoMesa_horarios Where id > 12 ORDER BY id ASC";
           $statement = $connect->prepare($query);
           $statement->execute();
           $result = $statement->fetchAll();
           foreach($result as $row)
           {
            $output .= '<option value="'.$row["id"].'">'.$row["descricao"].'</option>';
           }
           return $output;
          }
          ?>
          $('#horariofimhoje option').remove();
          html += '<?php echo fill_unit_select_box12($connect)?>'; 
              $('#horariofimhoje').append(html);
          } else if(horarioescolhido == 13) {
            <?php
          $connect = new PDO("sqlsrv:Server=192.168.150.14,65014;Database=Intranet", "six", "89202");
          //$connect = new PDO("sqlsrv:Server=localhost;Database=intranet", "six", "89202");
          function fill_unit_select_box13($connect)
          { 
           $output = '';
           $query = "SELECT * FROM TI_AgendamentoMesa_horarios Where id > 13 ORDER BY id ASC";
           $statement = $connect->prepare($query);
           $statement->execute();
           $result = $statement->fetchAll();
           foreach($result as $row)
           {
            $output .= '<option value="'.$row["id"].'">'.$row["descricao"].'</option>';
           }
           return $output;
          }
          ?>
          $('#horariofimhoje option').remove();
          html += '<?php echo fill_unit_select_box13($connect)?>'; 
              $('#horariofimhoje').append(html);
          } else if(horarioescolhido == 14) {
            <?php
          $connect = new PDO("sqlsrv:Server=192.168.150.14,65014;Database=Intranet", "six", "89202");
          //$connect = new PDO("sqlsrv:Server=localhost;Database=intranet", "six", "89202");
          function fill_unit_select_box14($connect)
          { 
           $output = '';
           $query = "SELECT * FROM TI_AgendamentoMesa_horarios Where id > 14 ORDER BY id ASC";
           $statement = $connect->prepare($query);
           $statement->execute();
           $result = $statement->fetchAll();
           foreach($result as $row)
           {
            $output .= '<option value="'.$row["id"].'">'.$row["descricao"].'</option>';
           }
           return $output;
          }
          ?>
          $('#horariofimhoje option').remove();
          html += '<?php echo fill_unit_select_box14($connect)?>'; 
              $('#horariofimhoje').append(html);
          } else if(horarioescolhido == 15) {
            <?php
          $connect = new PDO("sqlsrv:Server=192.168.150.14,65014;Database=Intranet", "six", "89202");
          //$connect = new PDO("sqlsrv:Server=localhost;Database=intranet", "six", "89202");
          function fill_unit_select_box15($connect)
          { 
           $output = '';
           $query = "SELECT * FROM TI_AgendamentoMesa_horarios Where id > 15 ORDER BY id ASC";
           $statement = $connect->prepare($query);
           $statement->execute();
           $result = $statement->fetchAll();
           foreach($result as $row)
           {
            $output .= '<option value="'.$row["id"].'">'.$row["descricao"].'</option>';
           }
           return $output;
          }
          ?>
          $('#horariofimhoje option').remove();
          html += '<?php echo fill_unit_select_box15($connect)?>'; 
              $('#horariofimhoje').append(html);
          } else if(horarioescolhido == 16) {
            <?php
          $connect = new PDO("sqlsrv:Server=192.168.150.14,65014;Database=Intranet", "six", "89202");
          //$connect = new PDO("sqlsrv:Server=localhost;Database=intranet", "six", "89202");
          function fill_unit_select_box16($connect)
          { 
           $output = '';
           $query = "SELECT * FROM TI_AgendamentoMesa_horarios Where id > 16 ORDER BY id ASC";
           $statement = $connect->prepare($query);
           $statement->execute();
           $result = $statement->fetchAll();
           foreach($result as $row)
           {
            $output .= '<option value="'.$row["id"].'">'.$row["descricao"].'</option>';
           }
           return $output;
          }
          ?>
          $('#horariofimhoje option').remove();
          html += '<?php echo fill_unit_select_box16($connect)?>'; 
              $('#horariofimhoje').append(html);
          } else if(horarioescolhido == 17) {
            <?php
          $connect = new PDO("sqlsrv:Server=192.168.150.14,65014;Database=Intranet", "six", "89202");
          //$connect = new PDO("sqlsrv:Server=localhost;Database=intranet", "six", "89202");
          function fill_unit_select_box17($connect)
          { 
           $output = '';
           $query = "SELECT * FROM TI_AgendamentoMesa_horarios Where id > 17 ORDER BY id ASC";
           $statement = $connect->prepare($query);
           $statement->execute();
           $result = $statement->fetchAll();
           foreach($result as $row)
           {
            $output .= '<option value="'.$row["id"].'">'.$row["descricao"].'</option>';
           }
           return $output;
          }
          ?>
          $('#horariofimhoje option').remove();
          html += '<?php echo fill_unit_select_box17($connect)?>'; 
              $('#horariofimhoje').append(html);
          } else if(horarioescolhido == 18) {
            <?php
          $connect = new PDO("sqlsrv:Server=192.168.150.14,65014;Database=Intranet", "six", "89202");
          //$connect = new PDO("sqlsrv:Server=localhost;Database=intranet", "six", "89202");
          function fill_unit_select_box18($connect)
          { 
           $output = '';
           $query = "SELECT * FROM TI_AgendamentoMesa_horarios Where id > 18 ORDER BY id ASC";
           $statement = $connect->prepare($query);
           $statement->execute();
           $result = $statement->fetchAll();
           foreach($result as $row)
           {
            $output .= '<option value="'.$row["id"].'">'.$row["descricao"].'</option>';
           }
           return $output;
          }
          ?>
          $('#horariofimhoje option').remove();
          html += '<?php echo fill_unit_select_box18($connect)?>'; 
              $('#horariofimhoje').append(html);
          } else if(horarioescolhido == 19) {
            <?php
          $connect = new PDO("sqlsrv:Server=192.168.150.14,65014;Database=Intranet", "six", "89202");
          //$connect = new PDO("sqlsrv:Server=localhost;Database=intranet", "six", "89202");
          function fill_unit_select_box19($connect)
          { 
           $output = '';
           $query = "SELECT * FROM TI_AgendamentoMesa_horarios Where id > 19 ORDER BY id ASC";
           $statement = $connect->prepare($query);
           $statement->execute();
           $result = $statement->fetchAll();
           foreach($result as $row)
           {
            $output .= '<option value="'.$row["id"].'">'.$row["descricao"].'</option>';
           }
           return $output;
          }
          ?>
          $('#horariofimhoje option').remove();
          html += '<?php echo fill_unit_select_box19($connect)?>'; 
              $('#horariofimhoje').append(html);
          } else if(horarioescolhido == 20) {
            <?php
          $connect = new PDO("sqlsrv:Server=192.168.150.14,65014;Database=Intranet", "six", "89202");
          //$connect = new PDO("sqlsrv:Server=localhost;Database=intranet", "six", "89202");
          function fill_unit_select_box20($connect)
          { 
           $output = '';
           $query = "SELECT * FROM TI_AgendamentoMesa_horarios Where id > 20 ORDER BY id ASC";
           $statement = $connect->prepare($query);
           $statement->execute();
           $result = $statement->fetchAll();
           foreach($result as $row)
           {
            $output .= '<option value="'.$row["id"].'">'.$row["descricao"].'</option>';
           }
           return $output;
          }
          ?>
          $('#horariofimhoje option').remove();
          html += '<?php echo fill_unit_select_box20($connect)?>'; 
              $('#horariofimhoje').append(html);
          } else if(horarioescolhido == 21) {
            <?php
          $connect = new PDO("sqlsrv:Server=192.168.150.14,65014;Database=Intranet", "six", "89202");
          //$connect = new PDO("sqlsrv:Server=localhost;Database=intranet", "six", "89202");
          function fill_unit_select_box21($connect)
          { 
           $output = '';
           $query = "SELECT * FROM TI_AgendamentoMesa_horarios Where id > 21 ORDER BY id ASC";
           $statement = $connect->prepare($query);
           $statement->execute();
           $result = $statement->fetchAll();
           foreach($result as $row)
           {
            $output .= '<option value="'.$row["id"].'">'.$row["descricao"].'</option>';
           }
           return $output;
          }
          ?>
          $('#horariofimhoje option').remove();
          html += '<?php echo fill_unit_select_box21($connect)?>'; 
              $('#horariofimhoje').append(html);
          } else if(horarioescolhido == 22) {
            <?php
          $connect = new PDO("sqlsrv:Server=192.168.150.14,65014;Database=Intranet", "six", "89202");
          //$connect = new PDO("sqlsrv:Server=localhost;Database=intranet", "six", "89202");
          function fill_unit_select_box22($connect)
          { 
           $output = '';
           $query = "SELECT * FROM TI_AgendamentoMesa_horarios Where id > 22 ORDER BY id ASC";
           $statement = $connect->prepare($query);
           $statement->execute();
           $result = $statement->fetchAll();
           foreach($result as $row)
           {
            $output .= '<option value="'.$row["id"].'">'.$row["descricao"].'</option>';
           }
           return $output;
          }
          ?>
          $('#horariofimhoje option').remove();
          html += '<?php echo fill_unit_select_box22($connect)?>'; 
              $('#horariofimhoje').append(html);
          } else if(horarioescolhido == 23) {
            <?php
          $connect = new PDO("sqlsrv:Server=192.168.150.14,65014;Database=Intranet", "six", "89202");
          //$connect = new PDO("sqlsrv:Server=localhost;Database=intranet", "six", "89202");
          function fill_unit_select_box23($connect)
          { 
           $output = '';
           $query = "SELECT * FROM TI_AgendamentoMesa_horarios Where id > 23 ORDER BY id ASC";
           $statement = $connect->prepare($query);
           $statement->execute();
           $result = $statement->fetchAll();
           foreach($result as $row)
           {
            $output .= '<option value="'.$row["id"].'">'.$row["descricao"].'</option>';
           }
           return $output;
          }
          ?>
          $('#horariofimhoje option').remove();
          html += '<?php echo fill_unit_select_box23($connect)?>'; 
              $('#horariofimhoje').append(html);
          } else if(horarioescolhido == 24) {
            <?php
          $connect = new PDO("sqlsrv:Server=192.168.150.14,65014;Database=Intranet", "six", "89202");
          //$connect = new PDO("sqlsrv:Server=localhost;Database=intranet", "six", "89202");
          function fill_unit_select_box24($connect)
          { 
           $output = '';
           $query = "SELECT * FROM TI_AgendamentoMesa_horarios Where id > 24 ORDER BY id ASC";
           $statement = $connect->prepare($query);
           $statement->execute();
           $result = $statement->fetchAll();
           foreach($result as $row)
           {
            $output .= '<option value="'.$row["id"].'">'.$row["descricao"].'</option>';
           }
           return $output;
          }
          ?>
          $('#horariofimhoje option').remove();
          html += '<?php echo fill_unit_select_box24($connect)?>'; 
              $('#horariofimhoje').append(html);
          } else if(horarioescolhido == 25) {
            <?php
          $connect = new PDO("sqlsrv:Server=192.168.150.14,65014;Database=Intranet", "six", "89202");
          //$connect = new PDO("sqlsrv:Server=localhost;Database=intranet", "six", "89202");
          function fill_unit_select_box25($connect)
          { 
           $output = '';
           $query = "SELECT * FROM TI_AgendamentoMesa_horarios Where id > 25 ORDER BY id ASC";
           $statement = $connect->prepare($query);
           $statement->execute();
           $result = $statement->fetchAll();
           foreach($result as $row)
           {
            $output .= '<option value="'.$row["id"].'">'.$row["descricao"].'</option>';
           }
           return $output;
          }
          ?>
          $('#horariofimhoje option').remove();
          html += '<?php echo fill_unit_select_box25($connect)?>'; 
              $('#horariofimhoje').append(html);
          } else if(horarioescolhido == 26) {
          <?php
          $connect = new PDO("sqlsrv:Server=192.168.150.14,65014;Database=Intranet", "six", "89202");
          //$connect = new PDO("sqlsrv:Server=localhost;Database=intranet", "six", "89202");
          function fill_unit_select_box26($connect)
          { 
           $output = '';
           $query = "SELECT * FROM TI_AgendamentoMesa_horarios Where id > 26 ORDER BY id ASC";
           $statement = $connect->prepare($query);
           $statement->execute();
           $result = $statement->fetchAll();
           foreach($result as $row)
           {
            $output .= '<option value="'.$row["id"].'">'.$row["descricao"].'</option>';
           }
           return $output;
          }
          ?>
          $('#horariofimhoje option').remove();
          html += '<?php echo fill_unit_select_box26($connect)?>'; 
              $('#horariofimhoje').append(html);
          }
  
          });
              
      });
    </script>
    <!--Fim Horarios Hoje --> 
    
    
    <!--Horarios Outra Dia --> 
    
    <script type="text/javascript" >
      $(document).ready(function(){
            $(document).on('change', '#horarioinicio', function(){
              var html = '';
              var horarioescolhido = $('#horarioinicio').val();
          
          if(horarioescolhido == 1) {
            
          <?php
         $connect = new PDO("sqlsrv:Server=192.168.150.14,65014;Database=Intranet", "six", "89202");
         //$connect = new PDO("sqlsrv:Server=localhost;Database=intranet", "six", "89202");
          function fill_unit_select_box01($connect)
          { 
           $output = '';
           $query = "SELECT * FROM TI_AgendamentoMesa_horarios Where id > 1 ORDER BY id ASC";
           $statement = $connect->prepare($query);
           $statement->execute();
           $result = $statement->fetchAll();
           foreach($result as $row)
           {
            $output .= '<option value="'.$row["id"].'">'.$row["descricao"].'</option>';
           }
           return $output;
          }
          
          ?>
          $('#horariofim option').remove();
          html += '<?php echo fill_unit_select_box01($connect)?>'; 
              $('#horariofim').append(html);
          
          }
          
           else if(horarioescolhido == 2) {
            <?php
         $connect = new PDO("sqlsrv:Server=192.168.150.14,65014;Database=Intranet", "six", "89202");
         //$connect = new PDO("sqlsrv:Server=localhost;Database=intranet", "six", "89202");
          function fill_unit_select_box02($connect)
          { 
           $output = '';
           $query = "SELECT * FROM TI_AgendamentoMesa_horarios Where id > 2 ORDER BY id ASC";
           $statement = $connect->prepare($query);
           $statement->execute();
           $result = $statement->fetchAll();
           foreach($result as $row)
           {
            $output .= '<option value="'.$row["id"].'">'.$row["descricao"].'</option>';
           }
           return $output;
          }
        
        
          ?>
          $('#horariofim option').remove();
          html += '<?php echo fill_unit_select_box02($connect)?>'; 
              $('#horariofim').append(html);
          } else if(horarioescolhido == 3) {
            <?php
          $connect = new PDO("sqlsrv:Server=192.168.150.14,65014;Database=Intranet", "six", "89202");
          //$connect = new PDO("sqlsrv:Server=localhost;Database=intranet", "six", "89202");
          function fill_unit_select_box03($connect)
          { 
           $output = '';
           $query = "SELECT * FROM TI_AgendamentoMesa_horarios Where id > 3 ORDER BY id ASC";
           $statement = $connect->prepare($query);
           $statement->execute();
           $result = $statement->fetchAll();
           foreach($result as $row)
           {
            $output .= '<option value="'.$row["id"].'">'.$row["descricao"].'</option>';
           }
           return $output;
          }
      
          ?>
          $('#horariofim option').remove();
          html += '<?php echo fill_unit_select_box03($connect)?>'; 
              $('#horariofim').append(html);
          } else if(horarioescolhido == 4) {
            <?php
          $connect = new PDO("sqlsrv:Server=192.168.150.14,65014;Database=Intranet", "six", "89202");
          //$connect = new PDO("sqlsrv:Server=localhost;Database=intranet", "six", "89202");
          function fill_unit_select_box04($connect)
          { 
           $output = '';
           $query = "SELECT * FROM TI_AgendamentoMesa_horarios Where id > 4 ORDER BY id ASC";
           $statement = $connect->prepare($query);
           $statement->execute();
           $result = $statement->fetchAll();
           foreach($result as $row)
           {
            $output .= '<option value="'.$row["id"].'">'.$row["descricao"].'</option>';
           }
           return $output;
          }
          ?>
          $('#horariofim option').remove();
          html += '<?php echo fill_unit_select_box04($connect)?>'; 
              $('#horariofim').append(html);
          } else if(horarioescolhido == 5) {
            <?php
          $connect = new PDO("sqlsrv:Server=192.168.150.14,65014;Database=Intranet", "six", "89202");
          //$connect = new PDO("sqlsrv:Server=localhost;Database=intranet", "six", "89202");
          function fill_unit_select_box05($connect)
          { 
           $output = '';
           $query = "SELECT * FROM TI_AgendamentoMesa_horarios Where id > 5 ORDER BY id ASC";
           $statement = $connect->prepare($query);
           $statement->execute();
           $result = $statement->fetchAll();
           foreach($result as $row)
           {
            $output .= '<option value="'.$row["id"].'">'.$row["descricao"].'</option>';
           }
           return $output;
          }
          ?>
          
          $('#horariofim option').remove();
          html += '<?php echo fill_unit_select_box05($connect)?>'; 
              $('#horariofim').append(html);
          } else if(horarioescolhido == 6) {
            <?php
          $connect = new PDO("sqlsrv:Server=192.168.150.14,65014;Database=Intranet", "six", "89202");
          //$connect = new PDO("sqlsrv:Server=localhost;Database=intranet", "six", "89202");
          function fill_unit_select_box06($connect)
          { 
           $output = '';
           $query = "SELECT * FROM TI_AgendamentoMesa_horarios Where id > 6 ORDER BY id ASC";
           $statement = $connect->prepare($query);
           $statement->execute();
           $result = $statement->fetchAll();
           foreach($result as $row)
           {
            $output .= '<option value="'.$row["id"].'">'.$row["descricao"].'</option>';
           }
           return $output;
          }
          ?>
          $('#horariofim option').remove();
          html += '<?php echo fill_unit_select_box06($connect)?>'; 
              $('#horariofim').append(html);
          } else if(horarioescolhido == 7) {
            <?php
          $connect = new PDO("sqlsrv:Server=192.168.150.14,65014;Database=Intranet", "six", "89202");
          //$connect = new PDO("sqlsrv:Server=localhost;Database=intranet", "six", "89202");
          function fill_unit_select_box07($connect)
          { 
           $output = '';
           $query = "SELECT * FROM TI_AgendamentoMesa_horarios Where id > 7 ORDER BY id ASC";
           $statement = $connect->prepare($query);
           $statement->execute();
           $result = $statement->fetchAll();
           foreach($result as $row)
           {
            $output .= '<option value="'.$row["id"].'">'.$row["descricao"].'</option>';
           }
           return $output;
          }
          ?>
          $('#horariofim option').remove();
          html += '<?php echo fill_unit_select_box07($connect)?>'; 
              $('#horariofim').append(html);
          } else if(horarioescolhido == 8) {
            <?php
          $connect = new PDO("sqlsrv:Server=192.168.150.14,65014;Database=Intranet", "six", "89202");
          //$connect = new PDO("sqlsrv:Server=localhost;Database=intranet", "six", "89202");
          function fill_unit_select_box08($connect)
          { 
           $output = '';
           $query = "SELECT * FROM TI_AgendamentoMesa_horarios Where id > 8 ORDER BY id ASC";
           $statement = $connect->prepare($query);
           $statement->execute();
           $result = $statement->fetchAll();
           foreach($result as $row)
           {
            $output .= '<option value="'.$row["id"].'">'.$row["descricao"].'</option>';
           }
           return $output;
          }
          ?>
          $('#horariofim option').remove();
          html += '<?php echo fill_unit_select_box08($connect)?>'; 
              $('#horariofim').append(html);
          } else if(horarioescolhido == 9) {
            <?php
          $connect = new PDO("sqlsrv:Server=192.168.150.14,65014;Database=Intranet", "six", "89202");
          //$connect = new PDO("sqlsrv:Server=localhost;Database=intranet", "six", "89202");
          function fill_unit_select_box09($connect)
          { 
           $output = '';
           $query = "SELECT * FROM TI_AgendamentoMesa_horarios Where id > 9 ORDER BY id ASC";
           $statement = $connect->prepare($query);
           $statement->execute();
           $result = $statement->fetchAll();
           foreach($result as $row)
           {
            $output .= '<option value="'.$row["id"].'">'.$row["descricao"].'</option>';
           }
           return $output;
          }
          ?>
          $('#horariofim option').remove();
          html += '<?php echo fill_unit_select_box09($connect)?>'; 
              $('#horariofim').append(html);
          } else if(horarioescolhido == 10) {
            <?php
          $connect = new PDO("sqlsrv:Server=192.168.150.14,65014;Database=Intranet", "six", "89202");
          //$connect = new PDO("sqlsrv:Server=localhost;Database=intranet", "six", "89202");
          function fill_unit_select_box010($connect)
          { 
           $output = '';
           $query = "SELECT * FROM TI_AgendamentoMesa_horarios Where id > 10 ORDER BY id ASC";
           $statement = $connect->prepare($query);
           $statement->execute();
           $result = $statement->fetchAll();
           foreach($result as $row)
           {
            $output .= '<option value="'.$row["id"].'">'.$row["descricao"].'</option>';
           }
           return $output;
          }
          ?>
          $('#horariofim option').remove();
          html += '<?php echo fill_unit_select_box010($connect)?>'; 
              $('#horariofim').append(html);
          } else if(horarioescolhido == 11) {
            <?php
          $connect = new PDO("sqlsrv:Server=192.168.150.14,65014;Database=Intranet", "six", "89202");
          //$connect = new PDO("sqlsrv:Server=localhost;Database=intranet", "six", "89202");
          function fill_unit_select_box011($connect)
          { 
           $output = '';
           $query = "SELECT * FROM TI_AgendamentoMesa_horarios Where id > 11 ORDER BY id ASC";
           $statement = $connect->prepare($query);
           $statement->execute();
           $result = $statement->fetchAll();
           foreach($result as $row)
           {
            $output .= '<option value="'.$row["id"].'">'.$row["descricao"].'</option>';
           }
           return $output;
          }
          ?>
          $('#horariofim option').remove();
          html += '<?php echo fill_unit_select_box011($connect)?>'; 
              $('#horariofim').append(html);
          } else if(horarioescolhido == 12) {
            <?php
          $connect = new PDO("sqlsrv:Server=192.168.150.14,65014;Database=Intranet", "six", "89202");
          //$connect = new PDO("sqlsrv:Server=localhost;Database=intranet", "six", "89202");
          function fill_unit_select_box012($connect)
          { 
           $output = '';
           $query = "SELECT * FROM TI_AgendamentoMesa_horarios Where id > 12 ORDER BY id ASC";
           $statement = $connect->prepare($query);
           $statement->execute();
           $result = $statement->fetchAll();
           foreach($result as $row)
           {
            $output .= '<option value="'.$row["id"].'">'.$row["descricao"].'</option>';
           }
           return $output;
          }
          ?>
          $('#horariofim option').remove();
          html += '<?php echo fill_unit_select_box012($connect)?>'; 
              $('#horariofim').append(html);
          } else if(horarioescolhido == 13) {
            <?php
          $connect = new PDO("sqlsrv:Server=192.168.150.14,65014;Database=Intranet", "six", "89202");
          //$connect = new PDO("sqlsrv:Server=localhost;Database=intranet", "six", "89202");
          function fill_unit_select_box013($connect)
          { 
           $output = '';
           $query = "SELECT * FROM TI_AgendamentoMesa_horarios Where id > 13 ORDER BY id ASC";
           $statement = $connect->prepare($query);
           $statement->execute();
           $result = $statement->fetchAll();
           foreach($result as $row)
           {
            $output .= '<option value="'.$row["id"].'">'.$row["descricao"].'</option>';
           }
           return $output;
          }
          ?>
          $('#horariofim option').remove();
          html += '<?php echo fill_unit_select_box013($connect)?>'; 
              $('#horariofim').append(html);
          } else if(horarioescolhido == 14) {
            <?php
          $connect = new PDO("sqlsrv:Server=192.168.150.14,65014;Database=Intranet", "six", "89202");
          //$connect = new PDO("sqlsrv:Server=localhost;Database=intranet", "six", "89202");
          function fill_unit_select_box014($connect)
          { 
           $output = '';
           $query = "SELECT * FROM TI_AgendamentoMesa_horarios Where id > 14 ORDER BY id ASC";
           $statement = $connect->prepare($query);
           $statement->execute();
           $result = $statement->fetchAll();
           foreach($result as $row)
           {
            $output .= '<option value="'.$row["id"].'">'.$row["descricao"].'</option>';
           }
           return $output;
          }
          ?>
          $('#horariofim option').remove();
          html += '<?php echo fill_unit_select_box014($connect)?>'; 
              $('#horariofim').append(html);
          } else if(horarioescolhido == 15) {
            <?php
          $connect = new PDO("sqlsrv:Server=192.168.150.14,65014;Database=Intranet", "six", "89202");
          //$connect = new PDO("sqlsrv:Server=localhost;Database=intranet", "six", "89202");
          function fill_unit_select_box015($connect)
          { 
           $output = '';
           $query = "SELECT * FROM TI_AgendamentoMesa_horarios Where id > 15 ORDER BY id ASC";
           $statement = $connect->prepare($query);
           $statement->execute();
           $result = $statement->fetchAll();
           foreach($result as $row)
           {
            $output .= '<option value="'.$row["id"].'">'.$row["descricao"].'</option>';
           }
           return $output;
          }
          ?>
          $('#horariofim option').remove();
          html += '<?php echo fill_unit_select_box015($connect)?>'; 
              $('#horariofim').append(html);
          } else if(horarioescolhido == 16) {
            <?php
          $connect = new PDO("sqlsrv:Server=192.168.150.14,65014;Database=Intranet", "six", "89202");
          //$connect = new PDO("sqlsrv:Server=localhost;Database=intranet", "six", "89202");
          function fill_unit_select_box016($connect)
          { 
           $output = '';
           $query = "SELECT * FROM TI_AgendamentoMesa_horarios Where id > 16 ORDER BY id ASC";
           $statement = $connect->prepare($query);
           $statement->execute();
           $result = $statement->fetchAll();
           foreach($result as $row)
           {
            $output .= '<option value="'.$row["id"].'">'.$row["descricao"].'</option>';
           }
           return $output;
          }
          ?>
          $('#horariofim option').remove();
          html += '<?php echo fill_unit_select_box016($connect)?>'; 
              $('#horariofim').append(html);
          } else if(horarioescolhido == 17) {
            <?php
          $connect = new PDO("sqlsrv:Server=192.168.150.14,65014;Database=Intranet", "six", "89202");
          //$connect = new PDO("sqlsrv:Server=localhost;Database=intranet", "six", "89202");
          function fill_unit_select_box017($connect)
          { 
           $output = '';
           $query = "SELECT * FROM TI_AgendamentoMesa_horarios Where id > 17 ORDER BY id ASC";
           $statement = $connect->prepare($query);
           $statement->execute();
           $result = $statement->fetchAll();
           foreach($result as $row)
           {
            $output .= '<option value="'.$row["id"].'">'.$row["descricao"].'</option>';
           }
           return $output;
          }
          ?>
          $('#horariofim option').remove();
          html += '<?php echo fill_unit_select_box017($connect)?>'; 
              $('#horariofim').append(html);
          } else if(horarioescolhido == 18) {
            <?php
          $connect = new PDO("sqlsrv:Server=192.168.150.14,65014;Database=Intranet", "six", "89202");
          //$connect = new PDO("sqlsrv:Server=localhost;Database=intranet", "six", "89202");
          function fill_unit_select_box018($connect)
          { 
           $output = '';
           $query = "SELECT * FROM TI_AgendamentoMesa_horarios Where id > 18 ORDER BY id ASC";
           $statement = $connect->prepare($query);
           $statement->execute();
           $result = $statement->fetchAll();
           foreach($result as $row)
           {
            $output .= '<option value="'.$row["id"].'">'.$row["descricao"].'</option>';
           }
           return $output;
          }
          ?>
          $('#horariofim option').remove();
          html += '<?php echo fill_unit_select_box018($connect)?>'; 
              $('#horariofim').append(html);
          } else if(horarioescolhido == 19) {
            <?php
          $connect = new PDO("sqlsrv:Server=192.168.150.14,65014;Database=Intranet", "six", "89202");
          //$connect = new PDO("sqlsrv:Server=localhost;Database=intranet", "six", "89202");
          function fill_unit_select_box019($connect)
          { 
           $output = '';
           $query = "SELECT * FROM TI_AgendamentoMesa_horarios Where id > 19 ORDER BY id ASC";
           $statement = $connect->prepare($query);
           $statement->execute();
           $result = $statement->fetchAll();
           foreach($result as $row)
           {
            $output .= '<option value="'.$row["id"].'">'.$row["descricao"].'</option>';
           }
           return $output;
          }
          ?>
          $('#horariofim option').remove();
          html += '<?php echo fill_unit_select_box019($connect)?>'; 
              $('#horariofim').append(html);
          } else if(horarioescolhido == 20) {
            <?php
          $connect = new PDO("sqlsrv:Server=192.168.150.14,65014;Database=Intranet", "six", "89202");
          //$connect = new PDO("sqlsrv:Server=localhost;Database=intranet", "six", "89202");
          function fill_unit_select_box020($connect)
          { 
           $output = '';
           $query = "SELECT * FROM TI_AgendamentoMesa_horarios Where id > 20 ORDER BY id ASC";
           $statement = $connect->prepare($query);
           $statement->execute();
           $result = $statement->fetchAll();
           foreach($result as $row)
           {
            $output .= '<option value="'.$row["id"].'">'.$row["descricao"].'</option>';
           }
           return $output;
          }
          ?>
          $('#horariofim option').remove();
          html += '<?php echo fill_unit_select_box020($connect)?>'; 
              $('#horariofim').append(html);
          } else if(horarioescolhido == 21) {
            <?php
          $connect = new PDO("sqlsrv:Server=192.168.150.14,65014;Database=Intranet", "six", "89202");
          //$connect = new PDO("sqlsrv:Server=localhost;Database=intranet", "six", "89202");
          function fill_unit_select_box021($connect)
          { 
           $output = '';
           $query = "SELECT * FROM TI_AgendamentoMesa_horarios Where id > 21 ORDER BY id ASC";
           $statement = $connect->prepare($query);
           $statement->execute();
           $result = $statement->fetchAll();
           foreach($result as $row)
           {
            $output .= '<option value="'.$row["id"].'">'.$row["descricao"].'</option>';
           }
           return $output;
          }
          ?>
          $('#horariofim option').remove();
          html += '<?php echo fill_unit_select_box021($connect)?>'; 
              $('#horariofim').append(html);
          } else if(horarioescolhido == 22) {
            <?php
          $connect = new PDO("sqlsrv:Server=192.168.150.14,65014;Database=Intranet", "six", "89202");
          //$connect = new PDO("sqlsrv:Server=localhost;Database=intranet", "six", "89202");
          function fill_unit_select_box022($connect)
          { 
           $output = '';
           $query = "SELECT * FROM TI_AgendamentoMesa_horarios Where id > 22 ORDER BY id ASC";
           $statement = $connect->prepare($query);
           $statement->execute();
           $result = $statement->fetchAll();
           foreach($result as $row)
           {
            $output .= '<option value="'.$row["id"].'">'.$row["descricao"].'</option>';
           }
           return $output;
          }
          ?>
          $('#horariofim option').remove();
          html += '<?php echo fill_unit_select_box022($connect)?>'; 
              $('#horariofim').append(html);
          } else if(horarioescolhido == 23) {
            <?php
          $connect = new PDO("sqlsrv:Server=192.168.150.14,65014;Database=Intranet", "six", "89202");
          //$connect = new PDO("sqlsrv:Server=localhost;Database=intranet", "six", "89202");
          function fill_unit_select_box023($connect)
          { 
           $output = '';
           $query = "SELECT * FROM TI_AgendamentoMesa_horarios Where id > 23 ORDER BY id ASC";
           $statement = $connect->prepare($query);
           $statement->execute();
           $result = $statement->fetchAll();
           foreach($result as $row)
           {
            $output .= '<option value="'.$row["id"].'">'.$row["descricao"].'</option>';
           }
           return $output;
          }
          ?>
          $('#horariofim option').remove();
          html += '<?php echo fill_unit_select_box023($connect)?>'; 
              $('#horariofim').append(html);
          } else if(horarioescolhido == 24) {
            <?php
          $connect = new PDO("sqlsrv:Server=192.168.150.14,65014;Database=Intranet", "six", "89202");
          //$connect = new PDO("sqlsrv:Server=localhost;Database=intranet", "six", "89202");
          function fill_unit_select_box024($connect)
          { 
           $output = '';
           $query = "SELECT * FROM TI_AgendamentoMesa_horarios Where id > 24 ORDER BY id ASC";
           $statement = $connect->prepare($query);
           $statement->execute();
           $result = $statement->fetchAll();
           foreach($result as $row)
           {
            $output .= '<option value="'.$row["id"].'">'.$row["descricao"].'</option>';
           }
           return $output;
          }
          ?>
          $('#horariofim option').remove();
          html += '<?php echo fill_unit_select_box024($connect)?>'; 
              $('#horariofim').append(html);
          } else if(horarioescolhido == 25) {
            <?php
          $connect = new PDO("sqlsrv:Server=192.168.150.14,65014;Database=Intranet", "six", "89202");
          //$connect = new PDO("sqlsrv:Server=localhost;Database=intranet", "six", "89202");
          function fill_unit_select_box025($connect)
          { 
           $output = '';
           $query = "SELECT * FROM TI_AgendamentoMesa_horarios Where id > 25 ORDER BY id ASC";
           $statement = $connect->prepare($query);
           $statement->execute();
           $result = $statement->fetchAll();
           foreach($result as $row)
           {
            $output .= '<option value="'.$row["id"].'">'.$row["descricao"].'</option>';
           }
           return $output;
          }
          ?>
          $('#horariofim option').remove();
          html += '<?php echo fill_unit_select_box025($connect)?>'; 
              $('#horariofim').append(html);
          } else if(horarioescolhido == 26) {
            <?php
          $connect = new PDO("sqlsrv:Server=192.168.150.14,65014;Database=Intranet", "six", "89202");
          //$connect = new PDO("sqlsrv:Server=localhost;Database=intranet", "six", "89202");
          function fill_unit_select_box026($connect)
          { 
           $output = '';
           $query = "SELECT * FROM TI_AgendamentoMesa_horarios Where id > 26 ORDER BY id ASC";
           $statement = $connect->prepare($query);
           $statement->execute();
           $result = $statement->fetchAll();
           foreach($result as $row)
           {
            $output .= '<option value="'.$row["id"].'">'.$row["descricao"].'</option>';
           }
           return $output;
          }
          ?>
          $('#horariofim option').remove();
          html += '<?php echo fill_unit_select_box026($connect)?>'; 
              $('#horariofim').append(html);
          }
          });
              
      });
    </script>
  
  

  
  
  
  

  </body>
</html>




