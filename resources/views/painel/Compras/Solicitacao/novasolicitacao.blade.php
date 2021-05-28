<!DOCTYPE html>
<html class="loading" lang="pt-br" data-textdirection="ltr">
   <head>
      <meta http-equiv="Content-Language" content="pt-br">
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
          <meta name="author" content="Portal PL&C">
      <title>Workflow - Etapa 1 - Solicitação</title>
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
      <link rel="stylesheet" href="{{ asset('/public/materialize/css/dataTables.checkboxes.css') }}">
      <link rel="stylesheet" href="{{ asset('/public/materialize/css/app-invoice.min.css') }}">
      <link rel="stylesheet" href="{{ asset('/public/materialize/css/sweeralert.css') }}">
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
      <body class="vertical-layout vertical-menu-collapsible page-header-dark vertical-modern-menu preload-transitions 2-columns" onblur="pausa();"
      data-open="click" data-menu="vertical-modern-menu" data-col="2-columns" style="overflow: hidden;background-image: url(./public/imgs/home.jpg);">
      <header class="page-topbar" id="header">
         <div class="navbar navbar-fixed">
            <nav class="navbar-main navbar-color nav-collapsible navbar-dark no-shadow" style="background-color: gray">
               <div class="nav-wrapper">
                  <div class="breadcrumbs-inline pt-3 pb-1" id="breadcrumbs-wrapper" style="margin-top: -40px;">
                     <h5 class="breadcrumbs-title mt-0 mb-0 display-inline hide-on-small-and-down"><span style="color: white;">Workflow Etapa 1 - Solicitação</span></h5>
                     <ol class="breadcrumbs mb-0">
                        <li class="breadcrumb-item"><a href="{{route('Home.Principal.Show')}}">Home</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{route('Painel.Compras.Solicitante.index_solicitante')}}">Minhas solicitações</a>
                        </li>
                        <li class="breadcrumb-item active" style="color: black;">Workflow Etapa 1 - Solicitação
                        </li>
                     </ol>
                     </ol>
                  </div>
                  <ul class="navbar-list right" style="margin-top: -80px;">

                     <li class="hide-on-large-only search-input-wrapper"><a class="waves-effect waves-block waves-light search-button" href="javascript:void(0);"><i class="material-icons">search</i></a></li>
                     <li><a class="waves-block waves-light notification-button" href="javascript:void(0);" data-target="notifications-dropdown"><i class="material-icons">notifications_none<small class="notification-badge">{{$totalNotificacaoAbertas}}</small></i></a></li>
                     <li><a class="waves-effect waves-block waves-light profile-button" href="javascript:void(0);" data-target="profile-dropdown">
                        <span class="avatar-status avatar-online">
                           <img src="{{ asset('/public/AdminLTE/img/Avatar/img.png') }}" 
                           alt="avatar"><i></i></span></a></li>
                  </ul>


                  <!-- dropdown softwares -->
                  <ul class="dropdown-content" id="softwares-dropdown">
                     <li>
                        <h6>Softwares PLC</h6>
                     </li>
                     <li class="divider"></li>
                     <li class="footer"><a aria-hidden="true" style="color: black;font-size: 15px;" target="_blank" href="{{route('Home.Principal.softwares', 'advwininterno.rdp')}}" data-toggle="tooltip" data-placement="top" title="Software responsável do PL&C"><i class="material-icons left">cloud_download</i>&nbsp;&nbsp;Advwin interno BHZ</a></li>
                     <li class="footer"><a aria-hidden="true" style="color: black;font-size: 15px;" target="_blank" href="{{route('Home.Principal.softwares', 'advwinexternobhz.rdp')}}" data-toggle="tooltip" data-placement="top" title="Software responsável do PL&C"><i class="material-icons left">cloud_download</i>&nbsp;&nbsp;Advwin externo BHZ</a></li>
                     <li class="footer"><a aria-hidden="true" style="color: black;font-size: 15px;" target="_blank" href="{{route('Home.Principal.softwares', 'advwinexterno.rdp')}}" data-toggle="tooltip" data-placement="top" title="Software responsável do PL&C"><i class="material-icons left">cloud_download</i>&nbsp;&nbsp;Advwin externo (FOR, MAO, RJO, SPO)</a></li>
                     <li class="footer"><a aria-hidden="true" style="color: black;font-size: 15px;" target="_blank" href="http://gmail.com" data-toggle="tooltip" data-placement="top" title="Clique aqui para acesar seu email externo" ><i class="material-icons left">http</i>&nbsp;&nbsp;Email navegador</a></li>
                     <li class="footer"><a aria-hidden="true" style="color: black;font-size: 15px;" target="_blank" href="http://ged.plcadvogados.com.br:8080/share/page/user/admin/dashboard" data-toggle="tooltip" data-placement="top" title="Software responsável pelo gerencionamento dos arquivos do PL&C."><i class="material-icons left">cloud_download</i>&nbsp;&nbsp;Sistema Alfresco</a></li>
                     <li class="footer"><a aria-hidden="true" style="color: black;font-size: 15px;" target="_blank" href="http://suporte.supremanet.com.br/glpi/" data-toggle="tooltip" data-placement="top" title="Software responsável pela abertura de chamados."><i class="material-icons left">http</i>&nbsp;&nbsp;Abertura de ticket suporte GLPI</a></li>
                  </ul>
                  <!-- end dropdown softwares -->



                  <!-- notifications-dropdown-->
                  <ul class="dropdown-content" id="notifications-dropdown">
                     <li>
                        <h6>Notificações<span class="new badge">{{$totalNotificacaoAbertas}}</span></h6>
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
                     <li><a  href="#modalMeuPerfil" class="modal-trigger" onclick="abreModalMeuPerfil();" style="color: gray;"><i class="material-icons">person_outline</i>Meu Perfil</a></li>
                        
                     <li class="divider"></li>
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
            <li class="bold">
               <a class="collapsible-header waves-effect waves-cyan " href="JavaScript:void(0)"><i class="material-icons">settings_input_svideo</i><span class="menu-title" data-i18n="Dashboard">Pesquisa Patrimonial</span></a>
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
            <li class="bold">
               <a class="collapsible-header waves-effect waves-cyan " href="JavaScript:void(0)"><i class="material-icons">preview</i><span class="menu-title" data-i18n="Dashboard">Controladoria</span></a>
               <div class="collapsible-body">
                  <ul class="collapsible collapsible-sub" data-collapsible="accordion">
                     <!--Vídeos -->
                     <li class="bold">
                        <a class="collapsible-header waves-effect waves-cyan " href="JavaScript:void(0)"><i class="material-icons">preview</i><span class="menu-title" data-i18n="Dashboard">Vídeos</span></a>
                        <div class="collapsible-body">
                           <ul class="collapsible collapsible-sub" data-collapsible="accordion">
                              <li><a href="" style="font-size: 11px;"><i class="material-icons">movie</i><span data-i18n="Modern">Proc. para utilizar o formulário ficha tempo.</span></a></li>
                              <li><a href="" style="font-size: 11px;"><i class="material-icons">movie</i><span data-i18n="Modern">Proc. para a movimentação e cumprimento de prazos.</span></a></li>
                           </ul>
                        </div>
                     </li>
                     <!--Vídeos -->    
                     <!--Manual --> 
                     <li class="bold">
                        <a class="collapsible-header waves-effect waves-cyan " href="JavaScript:void(0)"><i class="material-icons">library_books</i><span class="menu-title" data-i18n="Dashboard">Arquivos</span></a>
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
            <li class="bold">
               <a class="collapsible-header waves-effect waves-cyan " href="JavaScript:void(0)"><i class="material-icons">perm_identity</i><span class="menu-title" data-i18n="Dashboard">Correspondente</span></a>
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
            <li class="bold">
               <a class="collapsible-header waves-effect waves-cyan " href="JavaScript:void(0)"><i class="material-icons">perm_contact_calendar</i><span class="menu-title" data-i18n="Dashboard">DP & RH</span></a>
               <div class="collapsible-body">
                  <ul class="collapsible collapsible-sub" data-collapsible="accordion">
                     <li><a href="#"><i class="material-icons" style="font-size: 11px;">not_interested</i><span data-i18n="Modern" style="font-size: 11px;">Em desenvolvimento.</span></a></li>
                  </ul>
               </div>
            </li>
            <li class="bold">
               <a class="collapsible-header waves-effect waves-cyan " href="JavaScript:void(0)"><i class="material-icons">calculate</i><span class="menu-title" data-i18n="Dashboard">Financeiro</span></a>
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
            <li class="bold">
               <a class="collapsible-header waves-effect waves-cyan " href="JavaScript:void(0)"><i class="material-icons">mail</i><span class="menu-title" data-i18n="Dashboard">Marketing</span></a>
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
            <li class="bold">
               <a class="collapsible-header waves-effect waves-cyan " href="JavaScript:void(0)"><i class="material-icons">stay_primary_portrait</i><span class="menu-title" data-i18n="Dashboard">T.I</span></a>
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
         <div class="navigation-background"></div>
         <a class="sidenav-trigger btn-sidenav-toggle btn-floating btn-medium waves-effect waves-light hide-on-large-only" href="#" data-target="slide-out"><i class="material-icons">menu</i></a>
      </aside>
      <!-- END: SideNav-->
      <!-- BEGIN: Page Main-->
      <div id="main">
      <div class="row">
          <div class="container">
            <div class="card" style="height: 390px;">
                <div class="card-content">
                    <h5 class="card-title">Nova solicitação</h5>
                    
                    <form id="form" role="form" action="{{ route('Painel.Compras.Solicitante.salvarNovaSolicitacao') }}" method="POST" role="create"  enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="row">
                            <div class="col s1">
                                <span style="font-size: 12px;">Unidade</span>
                                    @foreach($unidadeSolicitante as $u)   
                                     <input style="font-size: 12px;" id="unidade" type="hidden" value="{{$u->codigo}}"  name="unidade" readonly>
                                     <input style="font-size: 12px;" type="text" value="{{$u->codigo}} - {{$u->descricao}}"  readonly>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col s3">
                              <span style="font-size: 12px;">Setor</span>
                              @foreach($setorSolicitante as $s)   
                              <input style="font-size: 12px;" id="setor" type="hidden" value="{{$s->codigo}}"  name="setor" readonly>
                               <input style="font-size: 12px;" type="text" value="{{$s->codigo}} - {{$s->descricao}}" readonly>
                              @endforeach
                            </div>

                            <div class="col s2">
                                <span style="font-size: 12px;">Data</span>
                            <input style="font-size: 12px;" id="data" type="date"  name="data" class="validate" value="{{$datahoje}}" readonly>
                            </div>

                                            
                           <div class="col s1">
                              <span style="font-size: 12px;">Quantidade</span>
                              <input style="font-size: 12px;" id="quantidade" type="text"  name="quantidade" class="validate">
                          </div>

                            <div class="col s3">
                            <span style="font-size: 12px;">Selecione o setor</label>
                            <select class="browser-default" id="destino_selected">
                               <option></option>
                              @foreach ($setor_destino as $sd)
                                 <option value="{{$sd->id}}" id="{{$sd->id}}">{{$sd->descricao}}</option>
                              @endforeach
                            </select>
                           </div>

                           <div class="col s2">
                              <span style="font-size: 12px;">Produtos</label>
                                 <select class="browser-default" id="produtos" name="produtos">
                                 <option selected="selected" value=""></option>
                              </select>
                            </div>

                        </div>

                        <div class="row">
                           <div class="col s2" id="div_outros">
                              <span style="font-size: 12px;">Outros:</span> <br>
                              <input style="font-size: 12px;" id="input_outros" name="input_outros"/>
                           </div>
                           
                           <div class="col s10">
                              <span style="font-size: 12px;">Observação:</span>
                              <textarea style="font-size: 12px; height: 75px;"  id="observacao" name="observacao"></textarea>
                          </div>

                         </div>


                        <button id="button_save" class="btn waves-light green right align" style="margin-top: 105px; margin-left: 1120px; position: fixed;" type="submit" name="action">Salvar
                            <i class="material-icons right">save</i>
                        </button>

                    </form>
                </div>
            </div>
          </div>
      </div>
      <script src="{{ asset('/public/materialize/js/vendors.min.js') }}"></script>
      <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
      <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
      <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>
      <script src="{{ asset('/public/materialize/js/jquery.dataTables.min.js') }}"></script>
      <script src="{{ asset('/public/materialize/js/dataTables.responsive.min.js') }}"></script>
      <script src="{{ asset('/public/materialize/js/datatables.checkboxes.min.js') }}"></script>
      <script src="{{ asset('/public/materialize/js/app-invoice.min.js') }}"></script>

      <script>
      var formID = document.getElementById("form");
      var send = $("#button_save");

      $(formID).submit(function(event){
         if (formID.checkValidity()) {
            send.attr('disabled', 'disabled');
         }
      });
      </script>

      <script>

         $(document).ready(function() {

            $('#div_outros').hide();
            $('#div_outros_marketing').hide();
            $('#div_outros_adm_fin').hide();

            var _token = $('input[name="_token"]').val();

            $('#destino_selected').on('change', function() {
               var valor = $(this).val();
               $.ajax({
                  url:"{{ route('Painel.Compras.Solicitante.buscaProdutos') }}",
                  type: 'POST',
                  data:{_token: _token, id_destino: valor},
                  success:function(response){
                  $('#produtos').html(response);
               }
             }); 
            })

            $('#produtos').on('change', function() {
               var valor = $(this).val();
               if(valor == 'Outros'){
                  $('#div_outros').show();
               } else {
                  $('#div_outros').hide();
               }
            })

         });
      </script>

   </body>
</html>