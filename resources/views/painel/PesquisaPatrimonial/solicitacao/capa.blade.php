<!DOCTYPE html>

<html class="loading" lang="pt-br" data-textdirection="ltr">
  <head>
    <meta http-equiv="Content-Language" content="pt-br">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="author" content="Portal PL&C">
    <title>Capa | Portal PL&C</title>
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

    <link rel="stylesheet" href="{{ asset('/public/materialize/css/dashboard-modern.css') }}"> 
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/chartist.min.css') }}"> 
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/chartist-plugin-tooltip.css') }}"> 


  </head>

  <body class="vertical-layout vertical-menu-collapsible page-header-dark vertical-modern-menu preload-transitions 2-columns   " data-open="click" data-menu="vertical-modern-menu" data-col="2-columns">

    <header class="page-topbar" id="header">
      <div class="navbar navbar-fixed"> 
        <nav class="navbar-main navbar-color nav-collapsible navbar-dark no-shadow" style="background-color: gray">
          <div class="nav-wrapper">

              <div class="breadcrumbs-inline pt-3 pb-1" id="breadcrumbs-wrapper" style="margin-top: -40px;">
                <h5 class="breadcrumbs-title mt-0 mb-0 display-inline hide-on-small-and-down"><span style="color: white;">Pesquisa patrimonial</span></h5>
                <ol class="breadcrumbs mb-0">
                  <li class="breadcrumb-item"><a href="{{ route('Home.Principal.Show') }}">Home</a>
                  </li>
                  <li class="breadcrumb-item"><a href="{{ route('Painel.PesquisaPatrimonial.solicitacao.index') }}">Dashboard</a>
                  </li>
                  <li class="breadcrumb-item active" style="color: black;">Capa
                  </li>
                </ol>
            </div>

            <ul class="navbar-list right" style="margin-top: -80px;">
              
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
     </ul>
     <div class="navigation-background"></div><a class="sidenav-trigger btn-sidenav-toggle btn-floating btn-medium waves-effect waves-light hide-on-large-only" href="#" data-target="slide-out"><i class="material-icons">menu</i></a>
   </aside>

    <div id="main">


<div class="row">
    <div class="col s12 m3">
      <div class="card-panel">
          <div class="row mt-5">
              <div class="col s12" style="margin-top: -20px;">
                <!-- <i class="material-icons" style="margin-left: -3%;">help</i> -->
               <p style="font-size: 11px;">Informa????es</p> 
               @if($datas->Descricao != null)
               <p class="m-0" style="font-size: 10px;">Nome/Raz??o: {{$datas->Descricao}}</p> 
               <p class="m-0" style="font-size: 10px;">CPF/CNPJ: {{$datas->Codigo}}</p>
               <p class="m-0" style="font-size: 10px;">Data de Nascimento: Aguardando cadastro da outra parte</p> 
               <p class="m-0" style="font-size: 10px;">Qtd. pesquisas: {{$QuantidadePesquisa}}</p>
               @else
               <p class="m-0" style="font-size: 10px;">Nome/Raz??o: Aguardando cadastro da outra parte</p> 
               <p class="m-0" style="font-size: 10px;">CPF/CNPJ: {{$codigo}}</p>
               <p class="m-0" style="font-size: 10px;">Data de Nascimento: Aguardando cadastro da outra parte</p> 
               <p class="m-0" style="font-size: 10px;">Qtd. pesquisas: {{$QuantidadePesquisa}}</p>
               @endif 
            </div>
          </div>


          <hr>
          <div class="row mt-5">
          
            <div class="col s12" style="margin-top: -15px;">
              <!-- <i class="material-icons" style="margin-left: -3%;">attach_money</i> -->
              <p style="font-size: 11px;">Financeiro</p> 
              <p class="m-0" style="font-size: 10px;">Valor a pagar: R$ <?php echo number_format($saldodevedor, 2); ?></p>
            </div>
          </div>

          <hr>

          <div class="row mt-5">
          
          <div class="col s12" style="margin-top: -15px;">
              <!-- <i class="material-icons" style="margin-left: -3%;">attach_money</i> -->
              <p style="font-size: 11px;">Patrim??nio</p> 
              <p class="m-0" style="font-size: 10px;">Valor bruto: R$ <?php echo number_format($somabruto, 2); ?></p>
              <p class="m-0" style="font-size: 10px;">Participa????o Societ??ria: {{$QuantidadeEmpresa}}</p> 
            </div>
          </div>


          <hr>


          <div class="row mt-5">
            <div class="col s12" style="margin-top: -15px;">
            <br>

              <div class="input-field col s4" style="margin-left: 200px; margin-top: 70px;">

                <button class="btn-floating waves-effect waves-light left btn tooltipped gradient-45deg-light-blue-cyan" data-position="right" data-tooltip="Inserir Pesquisa" onclick="window.location='{{route('Painel.PesquisaPatrimonial.solicitacao.create')}}'" name="action">
                    <i class="material-icons right">add</i>
                </button>

                <button style="background-color: gray; margin-top: 5px;" class="btn-floating waves-effect waves-light left btn tooltipped  gradient-45deg-light-blue-cyan" data-position="right" data-tooltip="Acessar Pesquisa" onclick="window.location='{{route('Painel.PesquisaPatrimonial.verpesquisa', $codigo)}}'" name="action">
                  <i class="material-icons right md-2">list</i>
                </button>

                <button style="background-color: gray; margin-top: 5px;" class="btn-floating waves-effect waves-light left btn tooltipped  gradient-45deg-light-blue-cyan" data-position="right" data-tooltip="Acessar Relat??rio" onclick="window.location='{{route('Painel.PesquisaPatrimonial.solicitacao.relatoriopesquisa', $codigo)}}'" name="action">
                  <i class="material-icons right">insert_drive_file</i>
              </button>

              </div>

              <div class="row">
              <div class="col s12 m12 l12" style="margin-left: -20px; margin-top: -180px;">
              <center><p class="mb-0 mt- justify-content-between" style="font-size: 11px;">Score atual</h6>
               <div class="current-balance-container tooltipped"  data-position="bottom" data-tooltip="ScoreCard atual deste pesquisado.">
                  <div id="current-balance-donut-chart" class="current-balance-shadow" style="width: 130px;"></div>
               </div>
              </center>
               </div>
             </div>
            </div>
          </div>
        </span>
      </div>
      
    </div>

    <div class="col s12 l9">
        <div class="row">
            <div class="card-panel">
                <div id="card-stats" class="pt-0">
                    <div class="row">
                       <div class="col s12 m6 l6 xl3">
                         @if($QuantidadeStatus != 0)
                         <div class="card gradient-45deg-light-blue-cyan gradient-shadow min-height-100 white-text animate fadeLeft">
                         @else 
                         <div class="card gradient-shadow min-height-100 white-text animate fadeLeft" style="background-color: gray">
                         @endif
                             <div class="padding-4">
                                <div class="row">
                                   <div class="col s7 m7">
                                      <i class="material-icons background-round mt-5">folder_open</i>
                                      <p style="font-size: 10px;">Status</p>
                                   </div>
                                   <div class="col s5 m5 right-align">
                                       <h5 class="mb-0 white-text">{{$QuantidadeStatus}}</h5> 
                                       @if($QuantidadeStatus != 0)
                                       <a  class="waves-effect waves-light btn modal-trigger" 
                                       style="background-color: white; color:black; margin-top: 20px;" href="#modalStatus">
                                       <i class="material-icons">search</i>
                                      </a>
                                      @endif

                                 </div>
                                </div>
                             </div>
                          </div>
                       </div>

                       <div class="col s12 m6 l6 xl3">
                       @if($QuantidadeImovel != 0)
                         <div class="card gradient-45deg-light-blue-cyan gradient-shadow min-height-100 white-text animate fadeLeft">
                         @else 
                         <div class="card gradient-shadow min-height-100 white-text animate fadeLeft" style="background-color: gray">
                         @endif
                            <div class="padding-4">
                                <div class="row">
                                   <div class="col s7 m7">
                                      <i class="material-icons background-round mt-5">house</i>
                                      <p style="font-size: 10px;">Im??vel</p>
                                   </div>
                                   <div class="col s5 m5 right-align">
                                    <h5 class="mb-0 white-text">{{$QuantidadeImovel}}</h5> 
                                    @if($QuantidadeImovel != 0)
                                      <a  class="waves-effect waves-light btn modal-trigger" 
                                       style="background-color: white; color:black; margin-top: 20px;" href="#modalImovel">
                                       <i class="material-icons">search</i>
                                      </a>
                                    @endif  
                                   </div>
                                </div>
                             </div>
                          </div>
                       </div>
                       <div class="col s12 m6 l6 xl3">
                       @if($QuantidadeVeiculo != 0)
                         <div class="card gradient-45deg-light-blue-cyan gradient-shadow min-height-100 white-text animate fadeLeft">
                         @else 
                         <div class="card gradient-shadow min-height-100 white-text animate fadeLeft" style="background-color: gray">
                         @endif
                           <div class="padding-4">
                                <div class="row">
                                   <div class="col s7 m7">
                                      <i class="material-icons background-round mt-5">directions_car</i>
                                      <p style="font-size: 10px;">Ve??culo</p>
                                   </div>
                                   <div class="col s5 m5 right-align">
                                    <h5 class="mb-0 white-text">{{$QuantidadeVeiculo}}</h5> 
                                    @if($QuantidadeVeiculo != 0)
                                      <a  class="waves-effect waves-light btn modal-trigger" 
                                       style="background-color: white; color:black; margin-top: 20px;" href="#modalVeiculos">
                                       <i class="material-icons">search</i>
                                      </a>
                                    @endif  
                                   </div>
                                </div>
                             </div>
                          </div>
                       </div>
                       <div class="col s12 m6 l6 xl3">
                       @if($QuantidadeEmpresa != 0)
                         <div class="card gradient-45deg-light-blue-cyan gradient-shadow min-height-100 white-text animate fadeLeft">
                         @else 
                         <div class="card gradient-shadow min-height-100 white-text animate fadeLeft" style="background-color: gray">
                         @endif
                              <div class="padding-4">
                                <div class="row">
                                   <div class="col s7 m7">
                                      <i class="material-icons background-round mt-5">business_center</i>
                                      <p style="font-size: 10px;">Empresa</p>
                                   </div>
                                   <div class="col s5 m5 right-align">
                                    <h5 class="mb-0 white-text">{{$QuantidadeEmpresa}}</h5> 
                                    @if($QuantidadeEmpresa != 0)
                                      <a  class="waves-effect waves-light btn modal-trigger" 
                                       style="background-color: white; color:black; margin-top: 20px;" href="#modalEmpresa">
                                       <i class="material-icons">search</i>
                                      </a>
                                    @endif  
                                   </div>
                                </div>
                             </div>
                          </div>
                       </div>
                    </div>
                    <div id="card-stats" class="pt-0">
                        <div class="row">
                           <div class="col s12 m6 l6 xl3">
                           @if($QuantidadeInfojud != 0)
                         <div class="card gradient-45deg-light-blue-cyan gradient-shadow min-height-100 white-text animate fadeLeft">
                         @else 
                         <div class="card gradient-shadow min-height-100 white-text animate fadeLeft" style="background-color: gray">
                         @endif
                               <div class="padding-4">
                                    <div class="row">
                                       <div class="col s7 m7">
                                          <i class="material-icons background-round mt-5">folder_open</i>
                                          <p style="font-size: 10px;">InfoJud</p>
                                       </div>
                                       <div class="col s5 m5 right-align">
                                       <h5 class="mb-0 white-text">{{$QuantidadeInfojud}}</h5> 
                                       @if($QuantidadeInfojud != 0)
                                        <a  class="waves-effect waves-light btn modal-trigger" 
                                       style="background-color: white; color:black; margin-top: 20px;" href="#modalInfoJud">
                                       <i class="material-icons">search</i>
                                       </a>
                                       @endif

                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>

                           <div class="col s12 m6 l6 xl3">
                           @if($QuantidadeBacenjud != 0)
                         <div class="card gradient-45deg-light-blue-cyan gradient-shadow min-height-100 white-text animate fadeLeft">
                         @else 
                         <div class="card gradient-shadow min-height-100 white-text animate fadeLeft" style="background-color: gray">
                         @endif
                              <div class="padding-4">
                                    <div class="row">
                                       <div class="col s7 m7">
                                          <i class="material-icons background-round mt-5">folder_open</i>
                                          <p style="font-size: 10px;">BacenJud</p>
                                       </div>
                                       <div class="col s5 m5 right-align">
                                        <h5 class="mb-0 white-text">{{$QuantidadeBacenjud}}</h5>
                                        @if($QuantidadeBacenjud != 0) 
                                        <a  class="waves-effect waves-light btn modal-trigger" 
                                       style="background-color: white; color:black; margin-top: 20px;" href="#modalBacenJud">
                                       <i class="material-icons">search</i>
                                       </a>
                                       @endif

                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>

                           <div class="col s12 m6 l6 xl3">
                           @if($QuantidadeProtestos != 0)
                         <div class="card gradient-45deg-light-blue-cyan gradient-shadow min-height-100 white-text animate fadeLeft">
                         @else 
                         <div class="card gradient-shadow min-height-100 white-text animate fadeLeft" style="background-color: gray">
                         @endif
                                  <div class="padding-4">
                                    <div class="row">
                                       <div class="col s7 m7">
                                          <i class="material-icons background-round mt-5">file_copy</i>
                                          <p style="font-size: 10px;">Protestos</p>
                                       </div>
                                       <div class="col s5 m5 right-align">
                                        <h5 class="mb-0 white-text">{{$QuantidadeProtestos}}</h5> 
                                        @if($QuantidadeProtestos != 0)
                                        <a class="waves-effect waves-light btn modal-trigger" 
                                        style="background-color: white; color:black; margin-top: 20px;" href="#modalProtestos">
                                        <i class="material-icons">search</i>
                                       </a>
                                       @endif
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>

                           <div class="col s12 m6 l6 xl3">
                           @if($QuantidadeRedesSociais != 0)
                         <div class="card gradient-45deg-light-blue-cyan gradient-shadow min-height-100 white-text animate fadeLeft">
                         @else 
                         <div class="card gradient-shadow min-height-100 white-text animate fadeLeft" style="background-color: gray">
                         @endif
                                <div class="padding-4">
                                    <div class="row">
                                       <div class="col s7 m7">
                                          <i class="material-icons background-round mt-5">people</i>
                                          <p style="font-size: 10px;">Redes Sociais</p>
                                       </div>
                                       <div class="col s5 m5 right-align">
                                        <h5 class="mb-0 white-text">{{$QuantidadeRedesSociais}}</h5>
                                        @if($QuantidadeRedesSociais != 0) 
                                        <a  class="waves-effect waves-light btn modal-trigger" 
                                        style="background-color: white; color:black; margin-top: 20px;" href="#modalRedesSociais">
                                        <i class="material-icons">search</i>
                                        </a>
                                        @endif

                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div id="card-stats" class="pt-0">
                        <div class="row">
                           <div class="col s12 m6 l6 xl3">
                           @if($QuantidadeProcessosJudiciais != 0)
                         <div class="card gradient-45deg-light-blue-cyan gradient-shadow min-height-100 white-text animate fadeLeft">
                         @else 
                         <div class="card gradient-shadow min-height-100 white-text animate fadeLeft" style="background-color: gray">
                         @endif
                                <div class="padding-4">
                                    <div class="row">
                                       <div class="col s7 m7">
                                          <i class="material-icons background-round mt-5">text_snippet</i>
                                          <p style="font-size: 10px;">Processos Judiciais</p>
                                       </div>
                                       <div class="col s5 m5 right-align">
                                        <h5 class="mb-0 white-text">{{$QuantidadeProcessosJudiciais}}</h5> 
                                        @if($QuantidadeProcessosJudiciais != 0)
                                         <a class="waves-effect waves-light btn modal-trigger" 
                                        style="background-color: white; color:black; margin-top: 20px;" href="#modalProcessosJudiciais">
                                        <i class="material-icons">search</i>
                                       </a>
                                       @endif
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="col s12 m6 l6 xl3">
                           @if($QuantidadePesquisaCadastral != 0)
                         <div class="card gradient-45deg-light-blue-cyan gradient-shadow min-height-100 white-text animate fadeLeft">
                         @else 
                         <div class="card gradient-shadow min-height-100 white-text animate fadeLeft" style="background-color: gray">
                         @endif
                               <div class="padding-4">
                                    <div class="row">
                                       <div class="col s7 m7">
                                          <i class="material-icons background-round mt-5">folder_open</i>
                                          <p style="font-size: 10px;">Pesquisa Cadastral</p>
                                       </div>
                                       <div class="col s5 m5 right-align">
                                        <h5 class="mb-0 white-text">{{$QuantidadePesquisaCadastral}}</h5> 
                                        @if($QuantidadePesquisaCadastral != 0)
                                        <a class="waves-effect waves-light btn modal-trigger" 
                                        style="background-color: white; color:black; margin-top: 20px;" href="#modalPesquisaCadastral">
                                        <i class="material-icons">search</i>
                                        </a>
                                       @endif
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="col s12 m6 l6 xl3">
                           @if($QuantidadeDossieComercial != 0)
                         <div class="card gradient-45deg-light-blue-cyan gradient-shadow min-height-100 white-text animate fadeLeft">
                         @else 
                         <div class="card gradient-shadow min-height-100 white-text animate fadeLeft" style="background-color: gray">
                         @endif
                                 <div class="padding-4">
                                    <div class="row">
                                       <div class="col s7 m7">
                                          <i class="material-icons background-round mt-5">folder_open</i>
                                          <p style="font-size: 10px;">Dossi?? Comercial</p>
                                       </div>
                                       <div class="col s5 m5 right-align">
                                        <h5 class="mb-0 white-text">{{$QuantidadeDossieComercial}}</h5> 
                                        @if($QuantidadeDossieComercial != 0)
                                       <a class="waves-effect waves-light btn modal-trigger" 
                                        style="background-color: white; color:black; margin-top: 20px;" href="#modalDossie">
                                        <i class="material-icons">search</i>
                                       </a>
                                       @endif
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="col s12 m6 l6 xl3">
                           <div class="card gradient-45deg-light-blue-cyan gradient-shadow min-height-100 white-text animate fadeLeft">
                              <div class="padding-4">
                                  <div class="row">
                                     <div class="col s7 m7">
                                        <i class="material-icons background-round mt-5">folder_open</i>
                                        <p style="font-size: 10px;">Todos os Dados</p>
                                     </div>
                                     <div class="col s5 m5 right-align">
                                        <h5 class="mb-0 white-text">-</h5>
                                        
                                        <a  class="waves-effect waves-light btn modal-trigger" 
                                       style="background-color: white; color:black; margin-top: 20px;" href="#modalDados">
                                       <i class="material-icons">search</i>
                                      </a>
                                     </div>
                                  </div>
                               </div>
                            </div>
                         </div>
                        </div>
                     </div>
                 </div>
            </div>
        </div>
      </div>
  </div>

   <!-- In??cio Modal -->
   <div id="modalStatus" class="modal">
      <div class="modal-content">
      <button type="button" class="btn waves-effect mr-sm-1 mr-2 modal-close red" style="margin-left: 90%;">
              <i class="material-icons">close</i>
     </button>
      <h5>Status</h5>
       <div class="row">
            <div class="col s12">
              <table id="" class="display">
            <thead>
            <tr>
            <th style="font-size: 10px;">Data</th>
            <th style="font-size: 10px;">Cliente</th>
            <th style="font-size: 10px;">Classifica????o</th>
            <th style="font-size: 10px;">Status</th>
            <th style="font-size: 10px;">A????o</th>
            </tr>
            </thead>
            <tbody>

            @foreach($status as $data)
            <tr>
            <th style="font-size: 10px;">{{ date('d/m/Y', strtotime($data->Data)) }}</td>                                           
            <th style="font-size: 10px;">{{$data->Cliente}}</td>                                           
            <th style="font-size: 10px;">{{$data->Classificacao}}</td>                                           
            <th style="font-size: 10px;">{{$data->Status}}</td>          
            <th style="font-size: 10px;">
            <a href="{{route('Painel.PesquisaPatrimonial.verpesquisa', $codigo)}}" class="invoice-action-view mr-4 tooltipped" data-position="bottom" data-tooltip="Clique aqui para visualizar est?? pesquisa"><i class="material-icons">list</i></a>
            </td>                                                                         
            </tr>
            @endforeach

                </tfoot>
              </table>
      </div>
  </div>
      </div>
   </div>
   <!--Fim Modal Status -->

   <!--Modal Imovel -->
   <div id="modalImovel" class="modal" style="width: 1200px;">
      <div class="modal-content">
      <button type="button" class="btn waves-effect mr-sm-1 mr-2 modal-close red" style="margin-left: 90%;">
              <i class="material-icons">close</i>
     </button>
      <h5>Im??vel</h5>
      <div class="row">
            <div class="col s12">
              <table id="" class="display">
            <thead>
            <tr>
            <th style="font-size: 10px;">Matr??cula</th>
            <th style="font-size: 10px;">Dat.Matr??cula</th>
            <th style="font-size: 10px;">UF</th>
            <th style="font-size: 10px;">Tipo Im??vel</th>
            <th style="font-size: 10px;">Valor Penal</th>
            <th style="font-size: 10px;">Aver.Penhora?</th>
            <th style="font-size: 10px;">Dat.Requer.Im??vel</th>
            <th style="font-size: 10px;">Status</th>
            <th style="font-size: 10px;">H?? restri????o?</th>
            <th style="font-size: 10px;">A????o</th>
            </tr>
            </thead>

            @foreach($imoveis as $imovel)
            <tr>
            <th style="font-size: 10px;">{{$imovel->matricula}}</td>                                            
            <th style="font-size: 10px;">{{ date('d/m/Y', strtotime($imovel->datamatricula)) }}</td>                                      
            <th style="font-size: 10px;">{{$imovel->uf}}</td>                                            
            <th style="font-size: 10px;">{{$imovel->tipodescricao}}</td>   
            <th style="font-size: 10px;">R$ <?php echo number_format($imovel->valor, 2,",","."); ?></td>     
            <th style="font-size: 10px;">{{$imovel->penhora}}</td>                                            
            <th style="font-size: 10px;">{{ date('d/m/Y', strtotime($imovel->datarequerimento)) }}</td>                                      
            <th style="font-size: 10px;">{{$imovel->status}}</td>                                         
            <th style="font-size: 10px;">{{$imovel->restricao}}</td>   
            <td style="font-size: 12px">
            <th style="font-size: 10px;">
            <a href="{{route('Painel.PesquisaPatrimonial.solicitacao.verpesquisaimovel', $codigo)}}" class="invoice-action-view mr-4 tooltipped" data-position="bottom" data-tooltip="Clique aqui para visualizar est?? pesquisa"><i class="material-icons">list</i></a>
            </td>                                                                             
            </tr>
            @endforeach

                </tfoot>
              </table>
      </div>
  </div>
      </div>
   </div>
   <!--Fim Modal Imovel -->


   <!--Modal Veiculo -->
   <div id="modalVeiculos" class="modal" style="width: 1200px;">
      <div class="modal-content">
      <button type="button" class="btn waves-effect mr-sm-1 mr-2 modal-close red" style="margin-left: 90%;">
              <i class="material-icons">close</i>
     </button>
      <h5>Ve??culos</h5>
      <div class="row">
            <div class="col s12">
              <table id="" class="display">
            <thead>
            <tr>
            <th style="font-size: 10px;">Placa</th>
            <th style="font-size: 10px;">Modelo</th>
            <th style="font-size: 10px;">Tipo</th>
            <th style="font-size: 10px;">Desc.Ve??culo</th>
            <th style="font-size: 10px;">Ano Modelo</th>
            <th style="font-size: 10px;">Ano Fabrica????o</th>
            <th style="font-size: 10px;">H?? Impedimento?</th>
            <th style="font-size: 10px;">Valor Tabela Fipe</th>
            <th style="font-size: 10px;">Averba????o do Art.828</th>
            <th style="font-size: 10px;">A????o</th>
            </tr>
            </thead>

            @foreach($veiculos as $veiculo)
            <tr>
            <th style="font-size: 10px;">{{$veiculo->placa}}</td>                                            
            <th style="font-size: 10px;">{{$veiculo->modelo}}</td>                                            
            <th style="font-size: 10px;">{{$veiculo->tipoveiculo}}</td>                                            
            <th style="font-size: 10px;">{{$veiculo->descricaoveiculo}}</td>   
            <th style="font-size: 10px;">{{$veiculo->anomodelo}}</td>                                            
            <th style="font-size: 10px;">{{$veiculo->anofabricacao}}</td>                                            
            <th style="font-size: 10px;">{{$veiculo->impedimento}}</td>                                            
            <th style="font-size: 10px;">R$ {{$veiculo->valor }} </td>     
            <th style="font-size: 10px;">{{$veiculo->averbacao828}}</td>    
            <th style="font-size: 10px;">
            <a href="{{route('Painel.PesquisaPatrimonial.solicitacao.verpesquisaveiculo', $codigo)}}" class="invoice-action-view mr-4 tooltipped" data-position="bottom" data-tooltip="Clique aqui para visualizar est?? pesquisa"><i class="material-icons">list</i></a>
            </td>                                                                                  
            </tr>
            @endforeach                                                                                

                </tfoot>
              </table>
      </div>
  </div>
      </div>
   </div>
   <!--Fim Modal Veiculo -->


   <!--Modal Empresa -->
   <div id="modalEmpresa" class="modal" style="width: 1200px;">
      <div class="modal-content">
      <button type="button" class="btn waves-effect mr-sm-1 mr-2 modal-close red" style="margin-left: 90%;">
              <i class="material-icons">close</i>
     </button>
      <h5>Empresas</h5>
      <div class="row">
            <div class="col s12">
              <table id="" class="display">
            <thead>
            <tr>
            <th style="font-size: 10px;">C??digo</th>
            <th style="font-size: 10px;">Raz??o</th>
            <th style="font-size: 10px;">Capital Social</th>
            <th style="font-size: 10px;">Dat.Funda????o</th>
            <th style="font-size: 10px;">Recupera????o Judicial</th>
            <th style="font-size: 10px;">Recupera????o ExtraJudicial</th>
            <th style="font-size: 10px;">Fal??ncia</th>
            <th style="font-size: 10px;">A????o</th>
            </tr>
            </thead>

            @foreach($empresas as $empresa)
            <tr>
            <th style="font-size: 10px;">{{$empresa->codigo}}</td>                                            
            <th style="font-size: 10px;">{{$empresa->razao}}</td>                                            
            <th style="font-size: 10px;">R$ <?php echo number_format($empresa->capitalsocial, 2,",","."); ?></td>                                         
            <th style="font-size: 10px;">{{ date('d/m/Y', strtotime($empresa->datafundacao)) }}</td>
            <th style="font-size: 10px;">{{$empresa->recuperacaojudicial}}</td>                                            
            <th style="font-size: 10px;">{{$empresa->recuperacaoextrajudicial}}</td>                                            
            <th style="font-size: 10px;">{{$empresa->falencia}}</td>    
            <th style="font-size: 10px;">
            <a href="{{route('Painel.PesquisaPatrimonial.solicitacao.verpesquisaempresa', $codigo)}}" class="invoice-action-view mr-4 tooltipped" data-position="bottom" data-tooltip="Clique aqui para visualizar est?? pesquisa"><i class="material-icons">list</i></a>
            </td>                                            
            </tr>
            @endforeach   

                </tfoot>
              </table>
      </div>
  </div>
      </div>
   </div>
   <!--Fim Empresa -->


   <div id="modalInfoJud" class="modal">
      <div class="modal-content">
      <button type="button" class="btn waves-effect mr-sm-1 mr-2 modal-close red" style="margin-left: 90%;">
              <i class="material-icons">close</i>
     </button>
      <h5>InfoJud</h5>
      <div class="row">
            <div class="col s12">
              <table id="" class="display">
            <thead>
            <tr>
            <th style="font-size: 10px;">Data</th>
            <th style="font-size: 10px;">Descri????o</th>
            <th style="font-size: 10px;">A????o</th>
            </tr>
            </thead>

            @foreach($infojuds as $infojud)
            <tr>
            <th style="font-size: 10px;">{{ date('d/m/Y', strtotime($infojud->data)) }}</td>
            <th style="font-size: 10px;">{{$infojud->descricao}}</td>    
            <th style="font-size: 10px;">
            <a href="{{route('Painel.PesquisaPatrimonial.solicitacao.verpesquisainfojud', $codigo)}}" class="invoice-action-view mr-4 tooltipped" data-position="bottom" data-tooltip="Clique aqui para visualizar est?? pesquisa"><i class="material-icons">list</i></a>
            </td>                                           
            </tr>
            @endforeach

                </tfoot>
              </table>
      </div>
  </div>
      </div>
   </div>
   <!--Fim Infojud -->

   <!-- Bacenjud -->
   <div id="modalBacenJud" class="modal">
      <div class="modal-content">
      <button type="button" class="btn waves-effect mr-sm-1 mr-2 modal-close red" style="margin-left: 90%;">
              <i class="material-icons">close</i>
     </button>
      <h5>Bacenjud</h5>
      <div class="row">
            <div class="col s12">
            <table id="" class="display">
            <thead>
            <tr>
            <th style="font-size: 10px;">Data</th>
            <th style="font-size: 10px;">Descri????o</th>
            <th style="font-size: 10px;">A????o</th>
            </tr>
            </thead>

            @foreach($bacenjuds as $bacenjud)
            <tr>
            <th style="font-size: 10px;">{{ date('d/m/Y', strtotime($bacenjud->data)) }}</td>
            <th style="font-size: 10px;">{{$bacenjud->descricao}}</td>     
            <th style="font-size: 10px;">
            <a href="{{route('Painel.PesquisaPatrimonial.solicitacao.verpesquisabacenjud', $codigo)}}" class="invoice-action-view mr-4 tooltipped" data-position="bottom" data-tooltip="Clique aqui para visualizar est?? pesquisa"><i class="material-icons">list</i></a>
            </td>                                          
            </tr>
            @endforeach

                </tfoot>
              </table>
      </div>
  </div>
      </div>
   </div>
   <!--Fim Bacenjud -->

   <!--Protestos/Notas -->
   <div id="modalProtestos" class="modal">
      <div class="modal-content">
      <button type="button" class="btn waves-effect mr-sm-1 mr-2 modal-close red" style="margin-left: 90%;">
              <i class="material-icons">close</i>
     </button>
      <h5>Protestos</h5>
      <div class="row">
            <div class="col s12">
              <table id="" class="display">
            <thead>
            <tr>
            <th style="font-size: 10px;">Data</th>
            <th style="font-size: 10px;">Descri????o</th>
            <th style="font-size: 10px;">A????o</th>
            </tr>
            </thead>

            @foreach($protestos as $protesto)
            <tr>
            <th style="font-size: 10px;">{{ date('d/m/Y', strtotime($protesto->data)) }}</td>
            <th style="font-size: 10px;">{{$protesto->descricao}}</td>     
            <th style="font-size: 10px;">
            <a href="{{route('Painel.PesquisaPatrimonial.solicitacao.verpesquisaprotestos', $codigo)}}" class="invoice-action-view mr-4 tooltipped" data-position="bottom" data-tooltip="Clique aqui para visualizar est?? pesquisa"><i class="material-icons">list</i></a>
            </td>                                          
            </tr>
            @endforeach

                </tfoot>
              </table>
      </div>
  </div>
      </div>
   </div>
   <!--Fim Protestos/Notas -->

   <!-- Redes Sociais -->
   <div id="modalRedesSociais" class="modal">
      <div class="modal-content">
      <button type="button" class="btn waves-effect mr-sm-1 mr-2 modal-close red" style="margin-left: 90%;">
              <i class="material-icons">close</i>
     </button>
      <h5>Redes Sociais</h5>
      <div class="row">
            <div class="col s12">
              <table id="" class="display">
            <thead>
            <tr>
            <th style="font-size: 10px;">Data</th>
            <th style="font-size: 10px;">Rede social</th>
            <th style="font-size: 10px;">Status</th>
            <th style="font-size: 10px;">Descri????o</th>
            <th style="font-size: 10px;">A????o</th>
            </tr>
            </thead>

            @foreach($redessociais as $redesocial)
            <tr>
            <th style="font-size: 10px;">{{ date('d/m/Y', strtotime($redesocial->data)) }}</td>
            <th style="font-size: 10px;">{{$redesocial->redesocial}}</td>                                           
            <th style="font-size: 10px;">{{$redesocial->status}}</td>    
            <th style="font-size: 10px;">{{$redesocial->descricao}}</td>   
            <th style="font-size: 10px;">
            <a href="{{route('Painel.PesquisaPatrimonial.solicitacao.verpesquisaredessociais', $codigo)}}" class="invoice-action-view mr-4 tooltipped" data-position="bottom" data-tooltip="Clique aqui para visualizar est?? pesquisa"><i class="material-icons">list</i></a>
            </td>                                                                                   
            </tr>
            @endforeach

                </tfoot>
              </table>
      </div>
  </div>
      </div>
   </div>
   <!-- Fim Redes Sociais -->

   <!-- Processos Judiciais / Tribunal -->
   <div id="modalProcessosJudiciais" class="modal">
      <div class="modal-content">
      <button type="button" class="btn waves-effect mr-sm-1 mr-2 modal-close red" style="margin-left: 90%;">
              <i class="material-icons">close</i>
     </button>
      <h5>Processos Judiciais</h5>
      <div class="row">
            <div class="col s12">
              <table id="" class="display">
            <thead>
            <tr>
            <th style="font-size: 10px;">Data</th>
            <th style="font-size: 10px;">Descri????o</th>
            <th style="font-size: 10px;">A????o</th>
            </tr>
            </thead>

            @foreach($processosjudiciais as $processojudicial)
            <tr>
            <th style="font-size: 10px;">{{ date('d/m/Y', strtotime($processojudicial->data)) }}</td>
            <th style="font-size: 10px;">{{$processojudicial->descricao}}</td>      
            <th style="font-size: 10px;">
            <a href="{{route('Painel.PesquisaPatrimonial.solicitacao.verpesquisaprocessosjudiciais', $codigo)}}" class="invoice-action-view mr-4 tooltipped" data-position="bottom" data-tooltip="Clique aqui para visualizar est?? pesquisa"><i class="material-icons">list</i></a>
            </td>                                      
            </tr>
            @endforeach

                </tfoot>
              </table>
      </div>
  </div>
      </div>
   </div>
   <!-- Fim Processos Judiciais / Tribunal -->

  <!--Pesquisa Cadastral -->
   <div id="modalPesquisaCadastral" class="modal">
      <div class="modal-content">
      <button type="button" class="btn waves-effect mr-sm-1 mr-2 modal-close red" style="margin-left: 90%;">
              <i class="material-icons">close</i>
     </button>
      <h5>Pesquisa Cadastral</h5>
      <div class="row">
            <div class="col s12">
              <table id="" class="display">
            <thead>
            <tr>
            <th style="font-size: 10px;">Data</th>
            <th style="font-size: 10px;">Descri????o</th>
            <th style="font-size: 10px;">A????o</th>
            </tr>
            </thead>

            @foreach($pesquisascadastral as $pesquisacadastral)
            <tr>
            <th style="font-size: 10px;">{{ date('d/m/Y', strtotime($pesquisacadastral->data)) }}</td>
            <th style="font-size: 10px;">{{$pesquisacadastral->descricao}}</td>        
            <th style="font-size: 10px;">
            <a href="{{route('Painel.PesquisaPatrimonial.solicitacao.verpesquisapesquisacadastral', $codigo)}}" class="invoice-action-view mr-4 tooltipped" data-position="bottom" data-tooltip="Clique aqui para visualizar est?? pesquisa"><i class="material-icons">list</i></a>
            </td>                                     
            </tr>
            @endforeach

                </tfoot>
              </table>
      </div>
  </div>
      </div>
   </div>
   <!--Fim Pesquisa Cadastral -->

  <!--Dossie Comercial -->
   <div id="modalDossie" class="modal">
      <div class="modal-content">
      <button type="button" class="btn waves-effect mr-sm-1 mr-2 modal-close red" style="margin-left: 90%;">
              <i class="material-icons">close</i>
     </button>
      <h5>Dossi?? Comercial</h5>
      <div class="row">
            <div class="col s12">
              <table id="" class="display">
            <thead>
            <tr>
            <th style="font-size: 10px;">Data</th>
            <th style="font-size: 10px;">Descri????o</th>
            <th style="font-size: 10px;">A????o</th>
            </tr>
            </thead>

            @foreach($dossiecomercials as $dossiecomercial)
            <tr>
            <th style="font-size: 10px;">{{ date('d/m/Y', strtotime($dossiecomercial->data)) }}</td>
            <th style="font-size: 10px;">{{$dossiecomercial->descricao}}</td> 
            <th style="font-size: 10px;">
            <a href="{{route('Painel.PesquisaPatrimonial.solicitacao.verpesquisadossiecomercial', $codigo)}}" class="invoice-action-view mr-4 tooltipped" data-position="bottom" data-tooltip="Clique aqui para visualizar est?? pesquisa"><i class="material-icons">list</i></a>
            </td>                                             
            </tr>
            @endforeach

                </tfoot>
              </table>
      </div>
  </div>
      </div>
   </div>
   <!--Fim Dossie Comercial -->

   <!--Modal Dados -->
   <div id="modalDados" class="modal" style="width: 1200px;">
      <div class="modal-content">
      <button type="button" class="btn waves-effect mr-sm-1 mr-2 modal-close red" style="margin-left: 90%;">
              <i class="material-icons">close</i>
     </button>
      <h5>Dados</h5>
      <div class="row">
            <div class="col s12">
              <table id="" class="display">
            <thead>
            <tr>
            <th style="font-size: 10px;">CPF/CNPJ</th>
            <th style="font-size: 10px;">Outra parte</th>
            <th style="font-size: 10px;">Grupo econ??mico</th>
            <th style="font-size: 10px;">Grupo cliente</th>
            <th style="font-size: 10px;">N?? Processo</th>
            <th style="font-size: 10px;">N?? da Pasta</th>
            <th style="font-size: 10px;">Tipo Projeto</th>
            <th style="font-size: 10px;">Valor causa</th>
            <th style="font-size: 10px;">A????o</th>
            </tr>
            </thead>

            @foreach($dadosprocessos as $dados)
            <tr>
            <th style="font-size: 10px;">{{$dados->Codigo}}</td>                                           
            <th style="font-size: 10px;">{{$dados->OutraParte}}</td>                                           
            <th style="font-size: 10px;">{{$dados->GrupoFinanceiro}}</td>                                           
            <th style="font-size: 10px;">{{$dados->GrupoCliente}}</td>                                           
            <th style="font-size: 10px;">{{$dados->NumeroProcesso}}</td>                                           
            <th style="font-size: 10px;">{{$dados->NumeroPasta}}</td>                                           
            <th style="font-size: 10px;">{{$dados->TipoProjeto}}</td>                                           
            <th style="font-size: 10px;">{{$dados->ValorCausa}}</td>
            <th style="font-size: 10px;">
            <a href="{{route('Painel.PesquisaPatrimonial.solicitacao.verpesquisadados', $codigo)}}" class="invoice-action-view mr-4 tooltipped" data-position="bottom" data-tooltip="Clique aqui para visualizar est?? pesquisa"><i class="material-icons">list</i></a>
            </td>                                            
            </tr>
            @endforeach

                </tfoot>
              </table>
      </div>
  </div>
      </div>
   </div>
   <!-- Fim Dados-->
  
</div>


    <script src="{{ asset('/public/materialize/js/vendors.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/data-tables.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/cards-extended.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/chart.min.js') }}"></script>

    <script src="https://pixinvent.com/materialize-material-design-admin-template/app-assets/vendors/chartist-js/chartist.min.js"></script>
    <script src="https://pixinvent.com/materialize-material-design-admin-template/app-assets/vendors/chartist-js/chartist-plugin-tooltip.js"></script>
    <script src="https://pixinvent.com/materialize-material-design-admin-template/app-assets/vendors/chartist-js/chartist-plugin-fill-donut.min.js"></script> 


    <script>
      $(document).ready(function(){
         $('.modal').modal();
      });

    </script>



<script>

(function (window, document, $) {

  var scorefaltante = 100 - {{$score}};
  var CurrentBalanceDonutChart = new Chartist.Pie(
    "#current-balance-donut-chart",
    {
      labels: [1, 2],
      series: [

        { meta: "Atual", value: {{$score}} },
        { meta: "Pendente", value: scorefaltante }

      ]
    },

    {
      donut: true,
      donutWidth: 5,
      showLabel: false,
      plugins: [
        Chartist.plugins.tooltip({
          class: "current-balance-tooltip",
          appendToBody: true
        }),
        Chartist.plugins.fillDonut({
          items: [
            {
              content:
              '<center><p class="small">SCORE</p><h5 class="mt-0 mb-0">{{$score}}</h5></center>'
            }
          ]
        })
      ]
    }
  )


})(window, document, jQuery)
</script>



  </body>
</html>