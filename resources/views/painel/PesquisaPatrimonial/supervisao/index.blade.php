<!DOCTYPE html>

<html class="loading" lang="pt-br" data-textdirection="ltr">
  <head>
    <meta http-equiv="Content-Language" content="pt-br">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="author" content="Portal PL&C">
    <title>Dashboard supervisão | Portal PL&C</title>
    <link rel="apple-touch-icon" href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/images/favicon/apple-touch-icon-152x152.png">
    <link rel="shortcut icon" href="{{ asset('/public/assets/imgs/casinha.png')}}" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/vendors.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/materialize.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/jquery-ui.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/chartist.min.css') }}">

    <style>
  .ui-autocomplete {
    max-height: 180px;
    overflow-y: auto;
    overflow-x: hidden;
  }
  * html .ui-autocomplete {
    height: 180px;
  }
    .ui-autocomplete-loading {
    background: white url("https://jqueryui.com/resources/demos/autocomplete/images/ui-anim_basic_16x16.gif") right center no-repeat;
  }
  </style>

  </head>

  <body class="vertical-layout vertical-menu-collapsible page-header-dark vertical-modern-menu preload-transitions 2-columns   " data-open="click" data-menu="vertical-modern-menu" data-col="2-columns">

    <header class="page-topbar" id="header">
      <div class="navbar navbar-fixed"> 
        <nav class="navbar-main navbar-color nav-collapsible navbar-dark no-shadow" style="background-color: gray">
          <div class="nav-wrapper">

          <div class="header-search-wrapper hide-on-med-and-down" style="margin-left: 320px;width: 820px;"><i class="material-icons">search</i>
          <form id="form" role="form" id="form" action="{{ route('Painel.PesquisaPatrimonial.buscapesquisado') }}" method="POST" role="search">
          {{ csrf_field() }}
          <input class="header-search-input z-depth-2" type="text" name="tags" id="tags" style="overflow: auto;" placeholder="Buscar pesquisado..." onkeypress="return event.keyCode != 13;">
          </form>
          </div>
          
          
          <div class="breadcrumbs-inline pt-3 pb-1" id="breadcrumbs-wrapper" style="margin-top: -100px;">
            <h5 class="breadcrumbs-title mt-0 mb-0 display-inline hide-on-small-and-down"><span style="color: white;">Pesquisa patrimonial</span></h5>
            <ol class="breadcrumbs mb-0">
              <li class="breadcrumb-item"><a href="{{route('Home.Principal.Show')}}">Home</a>
                </li>
              </ol>
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


 
     </ul>
     <div class="navigation-background"></div><a class="sidenav-trigger btn-sidenav-toggle btn-floating btn-medium waves-effect waves-light hide-on-large-only" href="#" data-target="slide-out"><i class="material-icons">menu</i></a>
   </aside>
   <!-- END: SideNav-->

    <!-- BEGIN: Page Main-->
  
    <div id="main">


        <div class="row">
            <div class="content-wrapper-before"></div>
            <div class="col s12">

              <div class="container">


          <div id="card-stats" class="row">

          <div class="col s6 m4 l2 x4">
          <a href="{{route('Painel.PesquisaPatrimonial.supervisao.solicitacoes')}}">
          <div class="card tooltipped"  data-position="top" data-tooltip="Total solicitações de pesquisa patrimonial efetuadas pelo seu usuário." >
               <div class="card-content cyan white-text">
               <p class="card-stats-title" style="font-size: 12px">Total solicitações</p>
               <h4 class="card-stats-number white-text" style="font-size: 16px;">{{$QuantidadeSolicitacoesCriadas}}</h4>
                  <p class="card-stats-compare">
                  </p>
               </div>
            </div>
          </a>  
         </div>

          <div class="col s6 m4 l2 x4">
          <a href="{{route('Painel.PesquisaPatrimonial.supervisao.solicitacoes')}}">
          <div class="card tooltipped"  data-position="top" data-tooltip="Total solicitações em andamento pelo núcleo pesquisa patrimonial." >
               <div class="card-content cyan white-text">
               <p class="card-stats-title" style="font-size: 10px">Solicitações em andamento</p>
               <h4 class="card-stats-number white-text" style="font-size: 16px;">{{$QuantidadeSolicitacoesEmAndamento}}</h4>
                  <p class="card-stats-compare">
                  </p>
               </div>
            </div>
          </a>  
         </div>

         <div class="col s6 m4 l2 x4">
         <a href="{{route('Painel.PesquisaPatrimonial.supervisao.solicitacoes')}}">
         <div class="card tooltipped"  data-position="top" data-tooltip="Total solicitações de pesquisa patrimonial finalizadas pelo núcleo pesquisa patrimonial." >
               <div class="card-content cyan white-text">
                  <p class="card-stats-title" style="font-size: 12px">Solicitações finalizadas</p>
                  <h4 class="card-stats-number white-text" style="font-size: 16px;">{{$QuantidadeSolicitacoesFinalizadas}}</h4>
                  <p class="card-stats-compare">
                  </p>
               </div>
            </div>
            </a>  
         </div>

         <div class="col s6 m4 l2 x4">
         <a href="{{route('Painel.PesquisaPatrimonial.supervisao.solicitacoes')}}">
            <div class="card tooltipped"  data-position="top" data-tooltip="Valor a receber das solicitações de pesquisas patrimoniais realizadas pelo seu usuário." >
               <div class="card-content cyan white-text">
               <p class="card-stats-title" style="font-size: 12px">Valor a receber</p>
               <h4 class="card-stats-number white-text" style="font-size: 16px;">R$ <?php echo number_format($ValorReceber, 2,",",".") ?></h4>
                  <p class="card-stats-compare">
                  </p>
               </div>
            </div>
            </a>  
         </div>

         <div class="col s6 m4 l2 x4">
         <a href="{{route('Painel.PesquisaPatrimonial.supervisao.solicitacoes')}}">
          <div class="card tooltipped"  data-position="top" data-tooltip="Valores pagos referente as solicitações de pesquisa patrimonial." >
                <div class="card-content cyan white-text">
                <p class="card-stats-title" style="font-size: 12px">Valor pago</p>
                <h4 class="card-stats-number white-text" style="font-size: 16px;">R$ <?php echo number_format($ValorRecebido, 2,",",".") ?></h4>
                   <p class="card-stats-compare">
                   </p>
                </div>
             </div>
             </a>  
          </div>
          
        </div>
      </div>
  </div>
  </div>

        <div class="row">
            <div class="col s12">

                <div class="card" style="height: 250px;">
                <div class="card-content">
                    <h4 class="card-title">Dashboard gerencial pesquisa patrimonial</h4>
               
                    <div id="work-collections" class="seaction">
                    <div class="row">

                    <div class="col s12 m3 l3">
                     <div id="doughnut-chart-wrapper">
                        <canvas id="doughnut-chart" height="150"></canvas>
                     </div>
                  </div>

                  <div class="col s12 m2 l3">
                     <ul class="doughnut-chart-legend">
                        <a href="{{route('Painel.PesquisaPatrimonial.supervisao.solicitacoes')}}"><li class="status1 ultra-small"><span class="legend-color"></span>Aguardando montagem ficha financeira</li>
                        <li class="status2 ultra-small"><span class="legend-color"></span> Aguardando pagamento do cliente</li>
                        <li class="status3 ultra-small"><span class="legend-color"></span> Aguardando avaliação do supervisor</li>
                        <li class="status4 ultra-small"><span class="legend-color"></span>Aguardando avaliação do financeiro</li>
                        <li class="status5 ultra-small"><span class="legend-color"></span> Solicitações não cobrável do cliente</li>
                        <li class="status6 ultra-small"><span class="legend-color"></span> Pesquisas em andamento</li> </a>
                     </ul>
                  </div>
                  
                  <div class="col s12 m2 l6" style="margin-top: -40px;">
                  <center><h4 class="card-title">Total solicitações no mês</h4></center>
                    <div class="col s12">
                    <div class="sample-chart-wrapper">
                    <canvas id="bar-chart" height="200"></canvas></div>
                  </div>
                  </div>

                  </div>
                  </div>
                  </div>
                  </div>
                  </div>


            <div class="row">
    <div class="col s12">
    <div class="ct-chart card z-depth-2 border-radius-6" style="overflow: auto; max-height: 400px;margin-top: -10px;">
        <div class="card-content">
          <div class="row">
            <div class="col s12">
              <table id="page-length-option" class="display" style="font-size: 11px;">
                <thead>
                <tr>
          <th style="font-size: 11px">Código</th>
          <th style="font-size: 11px">Pesquisado</th>
          <th style="font-size: 11px">Cliente</th>
          <th style="font-size: 11px">Pasta</th>
          <th style="font-size: 11px">Cobrável</th>
          <th style="font-size: 11px">Valor</th>
          <th style="font-size: 11px">Data</th>
          <th style="font-size: 11px">Status</th>
          <th style="font-size: 11px">Ação</th>
         </tr>
                </thead>
                <tbody>
                @foreach($datas as $data)  
                  <tr>
                    <td style="font-size: 10px">{{ $data->CPF }}</td>
                    <td style="font-size: 10px">{{ $data->OutraParte}}</td>
                    <td style="font-size: 10px">{{ $data->Cliente}}</td>
                    <td style="font-size: 10px">{{ $data->Pasta}}</td>
                    <td style="font-size: 10px">{{ $data->Cobravel}}</td>
                    <td style="font-size: 10px">R$ <?php echo number_format($data->Valor,2,",",".") ?></td>
                    <td style="font-size: 10px">{{ date('d/m/Y', strtotime($data->DataSolicitacao)) }}</td>
                    <td style="font-size: 10px">{{ $data->Status}}</td>
                    <td style="font-size: 10px">

                    @if($data->StatusID == 1)
                    <a style="color: gray;"  href="{{route('Painel.PesquisaPatrimonial.anexararquivos', $data->Id)}}" class="invoice-action-view mr-4"><i class="material-icons">launch</i></a>
                    <a style="color: gray;" href="{{route('Painel.PesquisaPatrimonial.supervisao.alterarcobranca', $data->Id)}}" class="invoice-action-view mr-4"><i class="material-icons">lock_open</i></a>
                    @elseif($data->StatusID == 9)
                    <a style="color: gray;"  href="{{route('Painel.PesquisaPatrimonial.supervisao.avaliar', $data->Id)}}" class="invoice-action-view mr-4"><i class="material-icons">assignment_late</i></a>
                    @endif

                   @if(!empty($data->CadastroAdvwin))                                     
                   <a style="color: gray;" href="{{route('Painel.PesquisaPatrimonial.nucleo.capa', ['codigo' => $data->CPF, 'numero' => $data->Id])}}" class="invoice-action-view mr-4"><i class="material-icons">remove_red_eye</i></a>
                   @else 
                   <a style="color: gray;"  href="{{route('Painel.PesquisaPatrimonial.cadastrar', $data->CPF)}}" class="invoice-action-view mr-4"><i class="material-icons">add</i></a>
                   @endif

                   @if(!empty($data->anexo))
                   <a style="color: gray;"  href="{{route('Painel.PesquisaPatrimonial.anexo', $data->anexo)}}" class="invoice-action-edit"><i class="material-icons">attach_file</i></a>
                   @endif

                    </td>
                  </tr>
                @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>

    <!-- END: Page Main-->


    <script src="{{ asset('/public/materialize/js/vendors.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/chart.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>


<script>
$(document).ready(function() {
    $('#page-length-option').DataTable( {
        "scrollY":        "200px",
        "scrollCollapse": true,
        "paging":         false,

        "language": {
                "lengthMenu": "Mostrando _MENU_ registros por página",
                "zeroRecords": "Nada encontrado",
                "info": "Mostrando página _PAGE_ de _PAGES_",
                "search": "Pesquisar&nbsp;:",
                "infoEmpty": "Nenhum registro disponível",
                "infoFiltered": "(filtrado de _MAX_ registros no total)",
            
         "oPaginate": {
           "sNext": "Próxima Página",
           "sPrevious": "Voltar Página", 
       }

   }

    } );
} );
</script>

<script>
  $( function() {
    var availableTags = [
      @foreach($usuarios as $usuario)
      "<?php echo mb_convert_case($usuario->Descricao, MB_CASE_TITLE, "UTF-8")?>",
      @endforeach
    ];
    function split( val ) {
      return val.split( /,\s*/ );
    }
    function extractLast( term ) {
      return split( term ).pop();
    }
 
    $( "#tags" )
      .on( "keydown", function( event ) {
        if ( event.keyCode === $.ui.keyCode.TAB &&
            $( this ).autocomplete( "instance" ).menu.active ) {
          event.preventDefault();
        }
      })
      .autocomplete({
        minLength: 3,
        source: function( request, response ) {
          response( $.ui.autocomplete.filter(
            availableTags, extractLast( request.term ) ) );
        },
        focus: function() {
          return false;
        },
        select: function( event, ui ) {
          var terms = split( this.value );
          terms.pop();
          terms.push( ui.item.value );
          terms.push( "" );
          this.value = terms.join( ", " );
          document.getElementById("form").submit()
        }
      });
  } );
  </script>

<script>

!function(o, e) {

    e("#task-card input:checkbox").change(function() {
            t(this)
        }

    );

    var a,
    r=e("#revenue-line-chart"),
    i= {

        labels:["Apple",
        "Samsung",
        "Sony",
        "Motorola",
        "Nokia",
        "Microsoft",
        "Xiaomi"],
        datasets:[ {
            label: "Today", data:[100, 50, 20, 40, 80, 50, 80], backgroundColor:"rgba(128, 222, 234, 0.5)", borderColor:"#d1faff", pointBorderColor:"#d1faff", pointBackgroundColor:"#00bcd4", pointHighlightFill:"#d1faff", pointHoverBackgroundColor:"#d1faff", borderWidth:2, pointBorderWidth:2, pointHoverBorderWidth:4, pointRadius:4
        }

        ,
            {
            label: "Second dataset", data:[60, 20, 90, 80, 50, 85, 40], borderDash:[15, 5], backgroundColor:"rgba(128, 222, 234, 0.2)", borderColor:"#80deea", pointBorderColor:"#80deea", pointBackgroundColor:"#00bcd4", pointHighlightFill:"#80deea", pointHoverBackgroundColor:"#80deea", borderWidth:2, pointBorderWidth:2, pointHoverBorderWidth:4, pointRadius:4
        }

        ]
    }

    ;

    setInterval(function() {
            var o=Math.round(Math.random()*(i.labels.length-1)); void 0 !==a&&(i.datasets[0].data[o]&&(i.datasets[0].data[o]=Math.round(100*Math.random())), i.datasets[1].data[o]&&(i.datasets[1].data[o]=Math.round(100*Math.random())), a.update())
        }

        , 2e3);

    var l,
    n= {

        type:"line",
        options: {

            responsive: !0,
            legend: {
                display:  !1
            }

            ,
            hover: {
                mode: "label"
            }

            ,
            scales: {
                xAxes:[ {

                    display: !0,
                    gridLines: {
                        display:  !1
                    }

                    ,
                    ticks: {
                        fontColor: "#fff"
                    }
                }

                ],
                yAxes:[ {

                    display: !0,
                    fontColor:"#fff",
                    gridLines: {
                        display:  !0, color:"rgba(255,255,255,0.3)"
                    }

                    ,
                    ticks: {
                        beginAtZero:  !1, fontColor:"#fff"
                    }
                }

                ]
            }
        }

        ,
        data:i
    }

    ,
    d=e("#doughnut-chart"),
    s= {

        type:"doughnut",
        options:b= {

            cutoutPercentage:70,
            legend: {
                display:  !1
            }
        }

        ,
        data: {

            labels:["Montagem ficha financeira",
            "Pagamento do cliente",
            "Avaliação supervisor pesquisa patrimonial",
            "Avaliação financeiro",
            "Solicitações não cobrável",
            "Pesquisas em andamento"],
            datasets:[ {
                label: "Sales", 
                data:[{{$QuantidadeSolicitacoesAguardandoFichaFinanceira}}, {{$QuantidadeSolicitacoesAguardandoPagamentoCliente}}, {{$QuantidadeSolicitacoesAguardandoRevisao}}, {{$QuantidadeSolicitacoesAguardandoRevisaoFinanceiro}}, {{$QuantidadeSolicitacoesNaoCobravel}}, {{$QuantidadeSolicitacoesEmAndamento}}], 
                backgroundColor:["#03a9f4", "#00bcd4", "#ffc107", "#e91e63", "#4caf50", "#708090"]
            }

            ]
        }
    }

    ,
    f=e("#trending-bar-chart"),
    p= {

        labels:["Jan",
        "Feb",
        "Mar",
        "Apr",
        "May",
        "June",
        "July",
        "Aug",
        "Sept"],
        datasets:[ {
            label: "Sales", data:[6, 9, 8, 4, 6, 7, 9, 4, 8], backgroundColor:"#46BFBD", hoverBackgroundColor:"#009688"
        }

        ]
    }

    ;

    setInterval(function() {
            if(void 0 !==l) {
                0; var o=Math.floor(10*Math.random())+1; p.datasets[0].data.shift(), p.datasets[0].data.push([o]), l.update()
            }
        }

        , 2e3);

    var h,
    c= {

        type:"bar",
        options: {

            responsive: !0,
            legend: {
                display:  !1
            }

            ,
            hover: {
                mode: "label"
            }

            ,
            scales: {
                xAxes:[ {

                    display: !0,
                    gridLines: {
                        display:  !1
                    }
                }

                ],
                yAxes:[ {

                    display: !0,
                    fontColor:"#fff",
                    gridLines: {
                        display:  !1
                    }

                    ,
                    ticks: {
                        beginAtZero:  !0
                    }
                }

                ]
            }

            ,
            tooltips: {

                titleFontSize:0,
                callbacks: {
                    label:function(o, e) {
                        return o.yLabel
                    }
                }
            }
        }

        ,
        data:p
    }

    ,
    g=e("#trending-radar-chart"),
    b= {

        responsive: !0,
        legend: {
            display:  !1
        }

        ,
        hover: {
            mode: "label"
        }

        ,
        scale: {
            angleLines: {
                color: "rgba(255,255,255,0.4)"
            }

            ,
            gridLines: {
                color: "rgba(255,255,255,0.2)"
            }

            ,
            ticks: {
                display:  !1
            }

            ,
            pointLabels: {
                fontColor: "#fff"
            }
        }
    }

    ,
    C= {

        labels:["Chrome",
        "Mozilla",
        "Safari",
        "IE10",
        "Opera"],
        datasets:[ {
            label: "Browser", data:[5, 6, 7, 8, 6], fillColor:"rgba(255,255,255,0.2)", borderColor:"#fff", pointBorderColor:"#fff", pointBackgroundColor:"#00bfa5", pointHighlightFill:"#fff", pointHoverBackgroundColor:"#fff", borderWidth:2, pointBorderWidth:2, pointHoverBorderWidth:4
        }

        ]
    }

    ;

    setInterval(function() {
            if(void 0 !==h) {
                0; var o=Math.floor(10*Math.random())+1; C.datasets[0].data.pop(), C.datasets[0].data.push([o]), h.update()
            }
        }

        , 2e3);

    var u= {
        type: "radar", options:b, data:C
    }

    ,
    y=e("#line-chart"),
    k= {

        type:"line",
        options: {

            responsive: !0,
            legend: {
                display:  !1
            }

            ,
            hover: {
                mode: "label"
            }

            ,
            scales: {
                xAxes:[ {

                    display: !0,
                    gridLines: {
                        display:  !1
                    }

                    ,
                    ticks: {
                        fontColor: "#fff"
                    }
                }

                ],
                yAxes:[ {

                    display: !0,
                    fontColor:"#fff",
                    gridLines: {
                        display:  !1
                    }

                    ,
                    ticks: {
                        beginAtZero:  !1, fontColor:"#fff"
                    }
                }

                ]
            }
        }

        ,
        data: {

            labels:["USA",
            "UK",
            "UAE",
            "AUS",
            "IN",
            "SA"],
            datasets:[ {
                label: "Sales", data:[65, 45, 50, 30, 63, 45], fill: !1, lineTension:0, borderColor:"#fff", pointBorderColor:"#fff", pointBackgroundColor:"#009688", pointHighlightFill:"#fff", pointHoverBackgroundColor:"#fff", borderWidth:2, pointBorderWidth:2, pointHoverBorderWidth:4, pointRadius:4
            }

            ]
        }
    }

    ;

    o.onload=function() {
        a=new Chart(r, n),
        l=new Chart(f, c);
        new Chart(d, s);
        h=new Chart(g, u);
        new Chart(y, k)
    }

    ,
    e(function() {
            e("#clients-bar").sparkline([70, 80, 65, 78, 58, 80, 78, 80, 70, 50, 75, 65, 80, 70, 65, 90, 65, 80, 70, 65, 90], {
                    type:"bar", height:"25", barWidth:7, barSpacing:4, barColor:"#b2ebf2", negBarColor:"#81d4fa", zeroColor:"#81d4fa"
                }

            ), e("#sales-compositebar").sparkline([4, 6, 7, 7, 4, 3, 2, 3, 1, 4, 6, 5, 9, 4, 6, 7, 7, 4, 6, 5, 9], {
                    type:"bar", barColor:"#F6CAFD", height:"25", width:"100%", barWidth:"7", barSpacing:4
                }

            ), e("#sales-compositebar").sparkline([4, 1, 5, 7, 9, 9, 8, 8, 4, 2, 5, 6, 7], {
                    composite: !0, type:"line", width:"100%", lineWidth:2, lineColor:"#fff3e0", fillColor:"rgba(255, 82, 82, 0.25)", highlightSpotColor:"#fff3e0", highlightLineColor:"#fff3e0", minSpotColor:"#00bcd4", maxSpotColor:"#00e676", spotColor:"#fff3e0", spotRadius:4
                }

            ), e("#profit-tristate").sparkline([2, 3, 0, 4, -5, -6, 7, -2, 3, 0, 2, 3, -1, 0, 2, 3, 3, -1, 0, 2, 3], {
                    type:"tristate", width:"100%", height:"25", posBarColor:"#ffecb3", negBarColor:"#fff8e1", barWidth:7, barSpacing:4, zeroAxis: !1
                }

            ), e("#invoice-line").sparkline([5, 6, 7, 9, 9, 5, 3, 2, 2, 4, 6, 7, 5, 6, 7, 9, 9, 5], {
                    type:"line", width:"100%", height:"25", lineWidth:2, lineColor:"#E1D0FF", fillColor:"rgba(255, 255, 255, 0.2)", highlightSpotColor:"#E1D0FF", highlightLineColor:"#E1D0FF", minSpotColor:"#00bcd4", maxSpotColor:"#4caf50", spotColor:"#E1D0FF", spotRadius:4
                }

            ), e("#project-line-1").sparkline([5, 6, 7, 9, 9, 5, 3, 2, 2, 4, 6, 7, 5, 6, 7, 9, 9, 5, 3, 2, 2, 4, 6, 7], {
                    type:"line", width:"100%", height:"30", lineWidth:2, lineColor:"#00bcd4", fillColor:"rgba(0, 188, 212, 0.2)"
                }

            ), e("#project-line-2").sparkline([6, 7, 5, 6, 7, 9, 9, 5, 3, 2, 2, 4, 6, 7, 5, 6, 7, 9, 9, 5, 3, 2, 2, 4], {
                    type:"line", width:"100%", height:"30", lineWidth:2, lineColor:"#00bcd4", fillColor:"rgba(0, 188, 212, 0.2)"
                }

            ), e("#project-line-3").sparkline([2, 4, 6, 7, 5, 6, 7, 9, 5, 6, 7, 9, 9, 5, 3, 2, 9, 5, 3, 2, 2, 4, 6, 7], {
                    type:"line", width:"100%", height:"30", lineWidth:2, lineColor:"#00bcd4", fillColor:"rgba(0, 188, 212, 0.2)"
                }

            ), e("#project-line-4").sparkline([9, 5, 3, 2, 2, 4, 6, 7, 5, 6, 7, 9, 5, 6, 7, 9, 9, 5, 3, 2, 2, 4, 6, 7], {
                    type:"line", width:"100%", height:"30", lineWidth:2, lineColor:"#00bcd4", fillColor:"rgba(0, 188, 212, 0.2)"
                }

            )
        }

    )
}

(window, (document, jQuery));

</script>

<script>
$(window).on("load", function() {
        var a=$("#line-chart"), e= {
            type:"line", options: {
                responsive: !0, maintainAspectRatio: !1, legend: {
                    position:"bottom"
                }

                , hover: {
                    mode:"label"
                }

                , scales: {
                    xAxes:[ {
                        display: !0, gridLines: {
                            color:"#f3f3f3", drawTicks: !1
                        }

                        , scaleLabel: {
                            display: !0, labelString:"Month"
                        }
                    }

                    ], yAxes:[ {
                        display: !0, gridLines: {
                            color:"#f3f3f3", drawTicks: !1
                        }

                        , scaleLabel: {
                            display: !0, labelString:"Value"
                        }
                    }

                    ]
                }

                , title: {
                    display: !0, text:"Line Chart - Legend"
                }
            }

            , data: {
                labels:["January", "February", "March", "April", "May", "June", "July"], datasets:[ {
                    label:"My First dataset", data:[65, 59, 80, 81, 56, 55, 40], fill: !1, borderColor:"#e91e63", pointBorderColor:"#e91e63", pointBackgroundColor:"#FFF", pointBorderWidth:2, pointHoverBorderWidth:2, pointRadius:4
                }

                , {
                    label:"My Second dataset", data:[28, 48, 40, 19, 86, 27, 90], fill: !1, borderColor:"#03a9f4", pointBorderColor:"#03a9f4", pointBackgroundColor:"#FFF", pointBorderWidth:2, pointHoverBorderWidth:2, pointRadius:4
                }

                , {
                    label:"My Third dataset - No bezier", data:[45, 25, 16, 36, 67, 18, 76], fill: !1, borderColor:"#ffc107", pointBorderColor:"#ffc107", pointBackgroundColor:"#FFF", pointBorderWidth:2, pointHoverBorderWidth:2, pointRadius:4
                }

                ]
            }
        }

        ; new Chart(a, e), a=$("#bar-chart"), e= {
            type:"horizontalBar", options: {
                elements: {
                    rectangle: {
                        borderWidth:2, borderColor:"rgb(0, 255, 0)", borderSkipped:"left"
                    }
                }

                , responsive: !0, maintainAspectRatio: !1, responsiveAnimationDuration:500, legend: {
                    position:"top"
                }

                , scales: {
                    xAxes:[ {
                        display: !0, gridLines: {
                            color:"#f3f3f3", drawTicks: !1
                        }

                        , scaleLabel: {
                            display: !0
                        }
                    }

                    ], yAxes:[ {
                        display: !0, gridLines: {
                            color:"#f3f3f3", drawTicks: !1
                        }

                        , scaleLabel: {
                            display: !0
                        }
                    }

                    ]
                }
            }

            , data: {
                labels:["Janeiro ", "Fevereiro ", "Março ", "Maio ", "Junho ", "Julho ", "Agosto ", "Setembro ", "Novembro ", "Dezembro "], 
                datasets:[ {
                    label:"Minhas solicitações no mês", 
                    data:[
                          {{$TotalSolicitacoesJaneiro}}, 
                          {{$TotalSolicitacoesFevereiro}},
                          {{$TotalSolicitacoesMarco}},
                          {{$TotalSolicitacoesAbril}},
                          {{$TotalSolicitacoesMaio}},
                          {{$TotalSolicitacoesJunho}},
                          {{$TotalSolicitacoesJulho}},
                          {{$TotalSolicitacoesAgosto}},
                          {{$TotalSolicitacoesSetembro}},
                          {{$TotalSolicitacoesOutubro}},
                          {{$TotalSolicitacoesNovembro}},
                          {{$TotalSolicitacoesDezembro}}],
                    backgroundColor:"#00bcd4", 
                    hoverBackgroundColor:"#00acc1", 
                    borderColor:"transparent"
                }

                ]
            }
        }

  
        , new Chart(a, e)
    }

);


</script>







  </body>
</html>