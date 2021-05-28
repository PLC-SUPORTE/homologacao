<!DOCTYPE html>
<html class="loading" lang="pt-br" data-textdirection="ltr">
   <head>
      <meta http-equiv="Content-Language" content="pt-br">
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
          <meta name="author" content="Portal PL&C">
      <title>Compras</title>
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
    <header class="page-topbar" id="header">
        <div class="navbar navbar-fixed"> 
          <nav class="navbar-main navbar-color nav-collapsible navbar-dark no-shadow" style="background-color: gray">
            <div class="nav-wrapper">
  
              
              <div class="breadcrumbs-inline pt-3 pb-1" id="breadcrumbs-wrapper" style="margin-top: -40px;">
                <h5 class="breadcrumbs-title mt-0 mb-0 display-inline hide-on-small-and-down"><span style="color: white;">Compras</span></h5>
                <ol class="breadcrumbs mb-0">
                  <li class="breadcrumb-item"><a href="{{route('Home.Principal.Show')}}">Home</a>
                    </li>
                    <li class="breadcrumb-item active" style="color: black;">Compras
                    </li>
                  </ol>
                  </ol>
                </div>
  
  
              <ul class="navbar-list right" style="margin-top: -80px;">
                <li class="hide-on-large-only search-input-wrapper"><a class="waves-effect waves-block waves-light search-button" href="javascript:void(0);"><i class="material-icons">search</i></a></li>
                <li><a class="waves-block waves-light notification-button" href="javascript:void(0);" data-target="notifications-dropdown"><i class="material-icons">notifications_none<small class="notification-badge">0</small></i></a></li>
                <li><a class="waves-effect waves-block waves-light profile-button" href="javascript:void(0);" data-target="profile-dropdown"><span class="avatar-status avatar-online"><img src="https://pixinvent.com/materialize-material-design-admin-template/app-assets/images/avatar/avatar-7.png" alt="avatar"><i></i></span></a></li>
              </ul>
  
              <!-- notifications-dropdown-->
              <ul class="dropdown-content" id="notifications-dropdown">
  
                <li class="divider"></li>
  
              </ul>
  
              <!-- profile-dropdown-->
              <ul class="dropdown-content" id="profile-dropdown">
                <li><a class="grey-text text-darken-1" href="#"><i class="material-icons">person_outline</i>Meu Perfil</a></li>
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
         <div class="content-wrapper-before blue-grey lighten-5"></div>
         {{-- <center>
            <div id="loading">
               <div class="wrapper">
                  <div class="circle circle-1"></div>
                  <div class="circle circle-1a"></div>
                  <div class="circle circle-2"></div>
                  <div class="circle circle-3"></div>
               </div>
               <h1 style="text-align: center;">Aguarde, estamos carregando as compras efetuadas&hellip;</h1>
            </div>
         </center> --}}
         <div class="col s12" id="corpodiv">
            <div class="container">
               <div class="section">
                  <section class="invoice-list-wrapper section">
                     <div class="invoice-filter-action mr-3">
                        <a href="#modalCadastro" class="btn waves-effect waves-light invoice-export border-round z-depth-4 tooltipped modal-trigger" data-position="top" data-tooltip="Cadastrar nova compra" style="background-color: gray">
                        <i class="material-icons">add</i>
                        <span class="hide-on-small-only">Cadastrar</span>
                        </a>
                     </div>
                     <div class="responsive-table">
                        <table class="table invoice-data-table white border-radius-4 pt-1">
                           <thead>
                              <tr>
                                 <th style="font-size: 11px"></th>
                                 <th style="font-size: 11px"><span>#</span></th>
                                 <th style="font-size: 11px">Comprador</th>
                                 <th style="font-size: 11px">Compra</th>
                                 <th style="font-size: 11px">Data</th>
                                 <th style="font-size: 11px">Valor</th>
                                 <th style="font-size: 11px">Quantidade</th>
                                 <th style="font-size: 11px">Setor</th>
                                 <th style="font-size: 11px">Usuário</th>
                                 <th style="font-size: 11px">Status</th>
                                 <th style="font-size: 11px">Ações</th>
                              </tr>
                           </thead>
                           <tbody>
                              @foreach ($compras as $c)
                              <tr>
                                 <td style="font-size: 10px"></td>
                                 <td style="font-size: 10px"></td>
                                 <td style="font-size: 10px">{{$c->comprador}}</td>
                                 <td style="font-size: 10px">{{$c->compra}}</td>
                                 <td style="font-size: 10px">{{date('d/m/Y', strtotime($c->data))}}</td>
                                 <td style="font-size: 10px">{{number_format($c->valor,2,",",".")}}</td>
                                 <td style="font-size: 10px">{{$c->quantidade}}</td>
                                 <td style="font-size: 10px">{{$c->setor}}</td>
                                 <td style="font-size: 10px">{{$c->usuario}}</td>
                                 @if ($c->status == 'Efetuada')
                                  <td style="font-size: 10px;"><span class="bullet green"></span>{{$c->status}}</td>
                                  @else
                                  <td style="font-size: 10px;"><span class="bullet yellow"></span>{{$c->status}}</td>
                                 @endif
                                 <td style="font-size: 10px">
                                    <a class="material-icons modal-trigger tooltipped" data-position="left" data-tooltip="Editar compra" href="#modalEdit{{$c->id}}" style="color: gray;">edit</a>
                                    {{-- <a style="color: gray;" class="material-icons modal-trigger" href="#modalExclui{{$c->id}}">close</a> --}}
                                    <a style="color: gray;" class="material-icons modal-trigger tooltipped" data-position="top" data-tooltip="Download anexo" href="{{route('Painel.Compras.downloadAnexo', $c->arquivo)}}">attach_file</a>
                                    </td>
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
         <div id="modalCadastro" class="modal" style="width: 1100px; height: 570px;">
            <div class="modal-content">
              <h6>Cadastrar compra</h6>
              <li class="divider"></li>
              <br>
              <div class="row">
                <form class="col s12" action="{{route('Painel.Compras.salvarNovaCompra')}}" method="POST" enctype="multipart/form-data">
                  {{ csrf_field() }}
                  <div class="row">
                    <div class="input-field col s6">
                     <span style="font-size: 12px;">Comprador</span>
                      <select class="browser-default" name="comprador" required>
                         @foreach ($users as $u)
                      <option style="font-size: 12px;" id="nome" value="{{$u->name}}" name="nome" required>{{$u->name}}</option>
                         @endforeach
                      </select>
                    </div>
                    <div class="input-field col s6">
                       <span style="font-size: 12px;">Compra</span>
                      <input id="compra" name="compra" type="text" class="validate" style="font-size: -2px;" required>
                    </div>
                  </div>
                  <div class="row">
                    <div class="input-field col s2">
                     <span style="font-size: 12px;">Data</span>
                      <input id="data" type="date"  name="data" class="validate" max="{{$datahoje}}" required>
                    </div>
                    <div class="input-field col s2">
                     <span style="font-size: 12px;">Valor</span>
                      <input id="valor" type="text" name="valor"  pattern="(?:\.|,|[0-9])*" onKeyPress="return(moeda2(this,'.',',',event))" required>
                    </div>
                    <div class="input-field col s2">
                       <span style="font-size: 12px;">Quantidade</span>
                        <input id="quantidade" type="number" name="quantidade" required onclick="buscaTotal();">
                      </div>
                      <div class="input-field col s2">
                        <span style="font-size: 12px;">Total</span>
                         <input id="total" type="text" name="total" required readonly>
                       </div>
                     
                      <div class="input-field col s4">
                        <span style="font-size: 12px;">Setor</span>
                        <select class="browser-default" name="setor" required>
                           @foreach ($setor as $s)
                        <option style="font-size: 12px;" value="{{$s->codigo}}">{{$s->codigo}} -- {{$s->descricao}}</option>
                           @endforeach
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="input-field col s6">
                     <span style="font-size: 12px;">Usuário</span>
                     <select class="browser-default" name="usuario" required>
                        @foreach ($users as $u)
                          <option style="font-size: 12px;">{{$u->name}}</option>
                        @endforeach
                     </select>
                    </div>
                    <div class="input-field col s3">
                     <span style="font-size: 12px;">Compra enviada pelos Correios?</span>
                     <p style="margin-top: -5px;">
                        <label>
                          <input class="with-gap" name="simenao" type="radio" id="sim" value="Sim"  />
                          <span>Sim</span>
                        </label>
                        <label>
                           <input class="with-gap" name="simenao" type="radio" id="nao" value="Não" />
                           <span>Não</span>
                         </label>
                      </p>
                  </div>

                  <div class="input-field col s3" >
                     <span style="font-size: 12px;">Status</span>
                     <select class="browser-default" name="status" required>
                          <option style="font-size: 12px;" value="Efetuada">Efetuada</option>
                          <option style="font-size: 12px;" value="Em andamento">Em andamento</option>
                     </select>
                  </div>
       
                  </div>

                  <div class="input-field col s6">
                     <span style="font-size: 12px;">Ordem de compra</span><br>
    
                     <input style="font-size: 13px;" type="file" id="input-file-now" name="select_file" accept=".jpg,.png,.xls,.xlsx,.pdf,.doc,.docx,.rtf,.jpeg" required class="dropify" data-default-file=""/>

                     {{-- <input type="file" id="select_file" name="select_file" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg" required> --}}
                  </div>

                  
                  <div class="input-field col s6">
                     <span style="font-size: 12px;">Observação</span>
                        <input id="observacao" name="obs" type="text" class="validate" required>
                      </div>
                 
               
            </div>
            <div class="modal-footer">
               <button type="submit" class="waves-effect waves-green btn-flat green" style="color: white; margin-top: 10px;">Inserir</a>
            </div>
         </form>
          </div>

          @foreach ($compras as $c)
            <div id="modalExclui{{$c->id}}" class="modal" style="width: 30%; height: 180px;">
               <div class="modal-content">
                  <form class="col s12" action="{{route('Painel.Compras.excluirCompra', $c->id)}}">
                     {{ csrf_field() }}
                  <center>
                     <h6>Excluir compra</h6>
                     <li class="divider"></li>
                     <br>
                     <p>A compra será <b>excluída</b>, deseja continuar?</p>
                  </center>
                     
               </div>
               <div class="modal-footer">
                  <button type="submit" class="modal-close waves-effect btn-flat red" style="color: white;">Excluir</a>
               </div>
            </form>
            </div>
          @endforeach
      

          @foreach ($compras as $c)
         <div id="modalEdit{{$c->id}}" class="modal" style="width: 1100px; height: 570px;">
            <div class="modal-content">
              <h6>Editar compra</h6>
              <li class="divider"></li>
              <br>
              <div class="row">
                <form class="col s12" action="{{route('Painel.Compras.salvarNovaCompra')}}">
                  {{ csrf_field() }}
                  <div class="row">
                     <div class="input-field col s6">
                      <span style="font-size: 12px;">Comprador</span>
                       <select class="browser-default" name="comprador" required>
                       <option value="{{$c->comprador}}" style="font-size: 12px;" selected>{{$c->comprador}}</option>
                          @foreach ($users as $u)
                       <option style="font-size: 12px;" id="nome" value="{{$c->comprador}}" name="nome" required>{{$u->name}}</option>
                          @endforeach
                       </select>
                     </div>
                     <div class="input-field col s6">
                        <span style="font-size: 12px;">Compra</span>
                     <input id="compra" name="compra" value="{{$c->compra}}" type="text" class="validate" style="font-size: -2px;" required>
                     </div>
                   </div>
                   <div class="row">
                     <div class="input-field col s2">
                      <span style="font-size: 12px;">Data</span>
                     <input id="data" type="date"  name="data" value="{{$c->data}}" class="validate" max="{{$datahoje}}" required>
                     </div>
                     <div class="input-field col s2">
                      <span style="font-size: 12px;">Valor</span>
                     <input id="valor" type="text" name="valor" value="{{$c->valor}}"  pattern="(?:\.|,|[0-9])*" onKeyPress="return(moeda2(this,'.',',',event))" required>
                     </div>
                     <div class="input-field col s2">
                        <span style="font-size: 12px;">Quantidade</span>
                        <input id="quantidade" type="number" value="{{$c->quantidade}}" name="quantidade" required onclick="buscaTotal();">
                       </div>
                       <div class="input-field col s2">
                         <span style="font-size: 12px;">Total</span>
                          <input id="total" type="text" name="total" value="{{$c->valor}}" required readonly>
                        </div>
                      
                       <div class="input-field col s4">
                         <span style="font-size: 12px;">Setor</span>
                         <select class="browser-default" name="setor" required>
                           <option style="font-size: 12px;" value="{{$c->setor}}" selected>{{$c->setor}} -- {{$s->descricao}}</option>
                            @foreach ($setor as $s)
                         <option style="font-size: 12px;" value="{{$s->codigo}}">{{$s->codigo}} -- {{$s->descricao}}</option>
                            @endforeach
                         </select>
                       </div>
                     </div>
                   </div>
                   <div class="row">
                     <div class="input-field col s6">
                      <span style="font-size: 12px;">Usuário</span>
                      <select class="browser-default" name="usuario" required>
                        <option value="{{$c->usuario}}" style="font-size: 12px;" selected>{{$c->usuario}}</option>
                         @foreach ($users as $u)
                           <option style="font-size: 12px;">{{$u->name}}</option>
                         @endforeach
                      </select>
                     </div>
                     <div class="input-field col s3">
                      <span style="font-size: 12px;">Compra enviada pelos Correios?</span>
                      <p style="margin-top: -5px;">
                        @if ($c->correios == 'Sim')
                        <label>
                           <input class="with-gap" name="simenao" type="radio" id="sim" value="Sim" checked />
                           <span>Sim</span>
                         </label>
                         <label>
                           <input class="with-gap" name="simenao" type="radio" id="nao" value="Não"/>
                           <span>Não</span>
                         </label>
                        @else
                        <label>
                           <input class="with-gap" name="simenao" type="radio" id="sim" value="Sim"/>
                           <span>Sim</span>
                         </label>
                         <label>
                           <input class="with-gap" name="simenao" type="radio" id="nao" value="Não" checked/>
                           <span>Não</span>
                         </label>
                        @endif
                       </p>
                   </div>
 
                   <div class="input-field col s3" >
                      <span style="font-size: 12px;">Status</span>
                      <select class="browser-default" name="status" required>
                         @if ($c->status == 'Efetuada')
                         <option style="font-size: 12px;" value="Efetuada" selected>Efetuada</option>
                         <option style="font-size: 12px;" value="Em andamento">Em andamento</option>
                         @else
                         <option style="font-size: 12px;" value="Efetuada">Efetuada</option>
                         <option style="font-size: 12px;" value="Em andamento" selected>Em andamento</option>
                         @endif
                      </select>
                   </div>
        
                   </div>
 
                   <div class="input-field col s6">
                      <span style="font-size: 12px;">Ordem de compra</span><br>
     
                      <input style="font-size: 13px;" type="file" id="input-file-now" name="select_file" accept=".jpg,.png,.xls,.xlsx,.pdf,.doc,.docx,.rtf,.jpeg" class="dropify" data-default-file=""/>
 
                      {{-- <input type="file" id="select_file" name="select_file" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg" required> --}}
                   </div>
 
                   
                   <div class="input-field col s6">
                      <span style="font-size: 12px;">Observação</span>
                        <input id="observacao" name="obs" value="{{$c->obs}}" type="text" class="validate" required>
                       </div>
               
            </div>
            <div class="modal-footer">
               <button type="submit" class="modal-close waves-effect waves-green btn-flat green" style="color: white;">Editar</a>
            </div>
         </form>
          </div>
          @endforeach
          
      </div>
      
      <script src="{{ asset('/public/materialize/js/vendors.min.js') }}"></script>
      <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
      <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
      <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>
      <script src="{{ asset('/public/materialize/js/jquery.dataTables.min.js') }}"></script>
      <script src="{{ asset('/public/materialize/js/dataTables.responsive.min.js') }}"></script>
      <script src="{{ asset('/public/materialize/js/datatables.checkboxes.min.js') }}"></script>
      <script src="{{ asset('/public/materialize/js/app-invoice.min.js') }}"></script>
      <script src="{{ asset('/public/materialize/js/extra-components-sweetalert.min.js') }}"></script>
      <script src="{{ asset('/public/materialize/js/sweetalert.min.js') }}"></script>
      <script>
         $(document).ready(function(){
         
            // $("#corpodiv").hide();
         });
      </script>
      <script>
         // setTimeout(function() {
         //    $('#loading').fadeOut('fast');
         //    $("#corpodiv").show();
         // }, 4000);

         $(document).ready(function(){
            $('.modal').modal();
        });
      </script>

      <script>
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
$('#quantidade').on('change', function() {

  var quantidade = $("#quantidade").val();
  var valor_unitario = parseFloat($("#valor").val().replace(',', '.'));
  var valor_total = quantidade * valor_unitario;

  $("#total").val(parseFloat(valor_total).toFixed(2));

});
</script>

   </body>
</html>