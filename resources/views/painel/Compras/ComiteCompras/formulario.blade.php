<!DOCTYPE html>
<html class="loading" lang="pt-br" data-textdirection="ltr">
   <head>
      <meta http-equiv="Content-Language" content="pt-br">
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
      <meta name="description" content="Cadastro das informações coletadas na pesquisa patrimonial do sistema Portal PLC.">
        <meta name="author" content="Portal PL&C">
      <title>Formulário</title>
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
                     <h5 class="breadcrumbs-title mt-0 mb-0 display-inline hide-on-small-and-down"><span style="color: white;">Formulário</span></h5>
                     <ol class="breadcrumbs mb-0">
                        <li class="breadcrumb-item"><a href="{{route('Home.Principal.Show')}}">Home</a>
                        </li>
                        <li class="breadcrumb-item active" style="color: black;"><a href="{{route('Painel.Compras.ComiteCompras.index_comite')}}">Solicitações em andamento</a>
                        </li>
                        <li class="breadcrumb-item active" style="color: black;">Formulário
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
            <div class="card" style="height: 695px;" id="card">
                <div class="card-content">
                   <div class="container" style="border: 1px solid #BBBBBB; border-radius: 10px;">
                     <h5 class="card-title">Solicitante</h5>
                    
                     <form id="form" role="form" action="{{ route('Painel.Compras.ComiteCompras.enviaParaAprovacao') }}" method="POST" role="create"  enctype="multipart/form-data">
                         {{ csrf_field() }}
 
                         <div class="row">
                            @foreach ($dados as $d)
                            <input style="font-size: 12px;" id="id_solicitante" name="id_solicitante" value="{{$d->id}}" type="hidden">
                            <div class="col s2">
                               <span style="font-size: 12px;">Unidade</span>
                               <input style="font-size: 12px;" id="unidade" name="unidade" value="{{$d->unidade}}" type="hidden" class="validate" readonly>
                               <input style="font-size: 12px;" value="{{$d->unidade}} - {{$d->descricao_unidade}}" type="text" class="validate" readonly>
                           </div>
                           <div class="col s3">
                               <span style="font-size: 12px;">Centro de custo</span>
                               <input style="font-size: 12px;" id="setor" name="setor" value="{{$d->setor}}" type="hidden" class="validate" readonly>
                           <input style="font-size: 12px;"  value="{{$d->setor}} - {{$d->descricao_setor}}" type="text" class="validate" readonly>
                           </div>
                           <div class="col s3">
                               <span style="font-size: 12px;">Solicitante:</span>
                               <input style="font-size: 12px;" id="solicitante" name="solicitante" value="{{$d->nomesolicitante}}" type="text" class="validate" readonly>
                           </div>
                           <div class="col s2">
                               <span style="font-size: 12px;">Data</span>
                               <input style="font-size: 12px;" id="datasolicitacao" name="datasolicitacao" value="{{date('d/m/Y', strtotime($d->datasolicitacao))}}" type="text" class="validate" readonly>
                               {{-- <input style="font-size: 12px;" id="data" type="date"  name="data" class="validate" max="{{$datahoje}}" required> --}}
                           </div>
                            @endforeach
                         </div>
                   </div>

                   <br>
                   <br>

                   <div class="container" style="border: 1px solid #BBBBBB; border-radius: 10px;">
                     <h5 class="card-title">Produto</h5>
                     
                    
                         <div class="row">
                           @foreach ($dados as $d)
                        <div class="row">
                            <div class="col s1">
                                <span style="font-size: 12px;">Quantidade</span>
                               <input style="font-size: 12px;" id="quantidade" value="{{$d->quantidade}}" name="quantidade" type="number" class="validate" readonly>
                            </div>

                            @if ($d->produto_outros != "")
                              <div class="col s2">
                                 <span style="font-size: 12px;">Produto:</span>
                                 <input style="font-size: 12px;" id="produto_outros" value="qweqweqwe" 
                                  name="produto_outros" type="text" class="validate" readonly>
                              </div>
                           @else
                           <div class="col s2">
                              <span style="font-size: 12px;">Produto:</span>
                              <input style="font-size: 12px;" id="produtos" value="{{$d->produtos}}" 
                              name="produtos" type="text" class="validate" readonly>
                           </div>
                            @endif
                         
                            <div class="col s4">
                                <span style="font-size: 12px;">Especificações:</span>
                                <input style="font-size: 12px;" id="especificacoes" name="especificacoes" placeholder="Adicione uma especificação" required>
                            </div>
                            <div class="col s5">
                              <span style="font-size: 12px;">Observações:</span>
                              @if ($d->observacao == '' || $d->observacao == null)
                                  <input style="font-size: 12px; resize: none;" readonly id="observacoes_produto" name="observacoes_produto" value="Não possui">
                              @else 
                                  <input style="font-size: 12px; resize: none;" readonly id="observacoes_produto" name="observacoes_produto" value="{{$d->observacao}}">
                              @endif
                          </div>
                        </div>
                        @endforeach

                        <div class="row">
                           <div class="col s1">
                              <span style="font-size: 12px;">Status:</span>
                              <select class="browser-default" style="font-size: 10px;" id="status" name="status">
                                <option value="Normal">Normal</option>
                                <option value="Urgente">Urgente</option>
                              </select>
                          </div>
                          <div class="input-field col s3">
                           <span style="font-size: 12px;">Inserir anexo</span><br>
          
                           <input style="font-size: 12px;" type="file" id="input-file-now" name="select_file" accept=".jpg,.png,.xls,.xlsx,.pdf,.doc,.docx,.rtf,.jpeg" class="dropify" data-default-file=""/>
      
                        </div>
                        </div>

                        <br>

              
                        </div>

                         </div>
                         <br>
                         <br>

                         <div class="container" style="border: 1px solid #BBBBBB; border-radius: 10px;">

                           <h5 class="card-title">Fornecedor 1</h5>
                        <a class="btn-floating btn-mini waves-effect waves-light right align" id="add_div" onclick="adicionaDiv();"
                        style="margin-right: 2px; margin-top: -50px; background-color: gray;" required><i class="material-icons">add</i></a>

                        <div class="row" id="idDiv" style="display: inline-block;">
                           <div class="col s2">
                              <span style="font-size: 12px;">Fornecedor</span>
                              <input style="font-size: 12px;" id="fornecedor" name="fornecedor" type="text" class="validate" required>
                          </div>
                          <div class="col s2">
                              <span style="font-size: 12px;">CPF/CNPJ</span>
                              <input style="font-size: 12px;" id="cpf_cnpj" name="cpf_cnpj" type="text" class="validate" required>
                          </div>
                          <div class="col s2">
                              <span style="font-size: 12px;">Contato:</span>
                              <input style="font-size: 12px;" id="contato" name="contato" onkeypress="mask(this, mphone);" onblur="mask(this, mphone);" type="text" class="validate" required>
                          </div>
                           <div class="col s1">
                              <span style="font-size: 12px;">Prazo:</span>
                              <input style="font-size: 12px;" id="prazo" name="prazo" type="number" class="validate" required>
                           </div>
                           <div class="col s5">
                              <span style="font-size: 12px;">Observações:</span>
                              <input style="font-size: 12px;" id="observacoes" name="observacoes" type="text" class="validate">
                           </div>
                           <div class="col s2">
                              <span style="font-size: 12px;">Forma de Pagamento:</span>
                              <p>
                                 <label>
                                   <input class="with-gap" checked onclick="removeCampoParcela();" name="boleto_cartao" id="boleto" value="boleto" type="radio" style="font-size: 12px;"  />
                                   <span style="font-size: 12px;">Boleto</span>
                                 </label>
                                 <label>
                                    <input class="with-gap" onclick="exibeCampoParcela();" name="boleto_cartao" id="cartao" value="cartao" type="radio" style="font-size: 12px;" />
                                    <span style="font-size:12px;">Cartão</span>
                                  </label>
                               </p>
                           </div>

                           <div class="col s1">
                              <span style="font-size: 12px;">Valor:</span>
                              <input style="font-size: 12px;" id="valor" name="valor" pattern="(?:\.|,|[0-9])*" onKeyPress="return(moeda2(this,'.',',',event))" type="text" class="validate">
                           </div>

                           <div class="col s1" id="div_parcelas">
                              <span style="font-size: 12px;">Parcelas:</span>
                              <input style="font-size: 12px;" id="parcelas" name="parcelas" type="number" class="validate">
                           </div>

                           <div class="col s1" id="div_frete_cartao">
                              <span style="font-size: 12px;">Frete:</span>
                              <input style="font-size: 12px;" id="frete" name="frete" type="text" class="validate" pattern="(?:\.|,|[0-9])*" onKeyPress="return(moeda2(this,'.',',',event))">
                           </div>

                           <div class="col s1" id="div_frete_boleto">
                              <span style="font-size: 12px;">Frete:</span>
                              <input style="font-size: 12px;" id="frete_boleto" name="frete_boleto" type="text" class="validate" pattern="(?:\.|,|[0-9])*" onKeyPress="return(moeda2(this,'.',',',event))">
                           </div>


                           <div class="col s1" id="div_total">
                              <span style="font-size: 12px;">Total:</span>
                              <input style="font-size: 12px;" id="total" name="total" readonly type="text" class="validate">
                           </div>

                           <div class="col s1" id="div_total_boleto">
                              <span style="font-size: 12px;">Total:</span>
                              <input style="font-size: 12px;" id="total_boleto" name="total_boleto" readonly type="text" class="validate">
                           </div>

                      
{{-- 
                              <button id="button_enviar" class="btn waves-light green right align" 
                              style="margin-top: 40px;" type="submit">Enviar
                                 <i class="material-icons right">save</i>
                           </button>
                           --}}
                         </div>
                   </div>

                   <br>

                   <div class="container" style="border: 1px solid #BBBBBB; border-radius: 10px;" id="fornecedor_2">

                     <div >
                        <h5 class="card-title">Fornecedor 2</h5>
                        <a class="btn-floating btn-mini waves-effect waves-light right align" id="add_div" onclick="adicionaDiv3();"
                        style="margin-right: 50px; margin-top: -50px; background-color: gray;" required><i class="material-icons">add</i></a>
                        
                        <a class="btn-floating btn-mini waves-effect waves-light right align" id="remove_div" onclick="removeDiv2();"
                        style="margin-right: 2px; margin-top: -50px; background-color: gray;" required><i class="material-icons">delete</i></a>
                     </div>

                     <div class="row" id="div_fornecedores_2" style="display: inline-block; margin-top: 20px;">
                          
                        <div class="col s2">
                           <span style="font-size: 12px;">Fornecedor</span>
                           <input style="font-size: 12px;" id="fornecedor_2" name="fornecedor_2" type="text" class="validate" >
                       </div>
                       <div class="col s2">
                           <span style="font-size: 12px;">CPF/CNPJ</span>
                           <input style="font-size: 12px;" id="cpf_cnpj_2" name="cpf_cnpj_2" type="text" class="validate" >
                       </div>
                       <div class="col s2">
                           <span style="font-size: 12px;">Contato:</span>
                           <input style="font-size: 12px;" id="contato_2" name="contato_2" onkeypress="mask(this, mphone);" onblur="mask(this, mphone);" type="text" class="validate" >
                       </div>
                        <div class="col s1">
                           <span style="font-size: 12px;">Prazo:</span>
                           <input style="font-size: 12px;" id="prazo_2" name="prazo_2" type="number" class="validate" >
                        </div>
                        {{-- <div class="col s2">
                           <span style="font-size: 12px;">Pagamento:</span>
                           <input style="font-size: 12px;" id="condicoes_pagamento" name="condicoes_pagamento[]" type="text" class="validate" required>
                        </div> --}}
                        <div class="col s5">
                           <span style="font-size: 12px;">Observações:</span>
                           <input style="font-size: 12px;" id="observacoes_2" name="observacoes_2" type="text" class="validate">
                        </div>
                        <div class="col s2">
                           <span style="font-size: 12px;">Forma de Pagamento:</span>
                           <p>
                              <label>
                                <input class="with-gap" checked onclick="removeCampoParcela2();" name="boleto_cartao_2" id="boleto" value="boleto" type="radio" style="font-size: 12px;"  />
                                <span style="font-size: 12px;">Boleto</span>
                              </label>
                              <label>
                                 <input class="with-gap" onclick="exibeCampoParcela2();" name="boleto_cartao_2" id="cartao" value="cartao" type="radio" style="font-size: 12px;" />
                                 <span style="font-size:12px;">Cartão</span>
                               </label>
                            </p>
                        </div>

                        <div class="col s1">
                        <span style="font-size: 12px;">Valor:</span>
                        <input style="font-size: 12px;" id="valor_2" name="valor_2"  pattern="(?:\.|,|[0-9])*" onKeyPress="return(moeda2(this,'.',',',event))" type="text" class="validate">
                        </div>

                        <div class="col s1" id="div_parcelas_2">
                           <span style="font-size: 12px;">Parcelas:</span>
                           <input style="font-size: 12px;" id="parcelas_2" name="parcelas_2" type="number" class="validate">
                        </div>

                        <div class="col s1" id="div_frete_cartao_2">
                           <span style="font-size: 12px;">Frete:</span>
                           <input style="font-size: 12px;" id="frete_2" name="frete_2" type="text" class="validate" pattern="(?:\.|,|[0-9])*" onKeyPress="return(moeda2(this,'.',',',event))">
                        </div>

                        <div class="col s1" id="div_frete_boleto_2">
                           <span style="font-size: 12px;">Frete:</span>
                           <input style="font-size: 12px;" id="frete_boleto_2" name="frete_boleto_2" type="text" class="validate" pattern="(?:\.|,|[0-9])*" onKeyPress="return(moeda2(this,'.',',',event))">
                        </div>


                        <div class="col s1" id="div_total_2">
                           <span style="font-size: 12px;">Total:</span>
                           <input style="font-size: 12px;" id="total_2" name="total_2" readonly type="text" class="validate">
                        </div>

                        
                        <div class="col s1" id="div_total_boleto_2">
                           <span style="font-size: 12px;">Total:</span>
                           <input style="font-size: 12px;" id="total_boleto_2" name="total_boleto_2" readonly type="text" class="validate">
                        </div>
                    

                     </div>
                     </div>

                     <br>

                     <div class="container" style="border: 1px solid #BBBBBB; border-radius: 10px;" id="fornecedor_3">
  
                        <div  class="card-title">
                           <h5 class="card-title">Fornecedor 3</h5>
                           <a class="btn-floating btn-mini waves-effect waves-light right align" id="add_div" onclick="removeDiv3();"
                        style="margin-right: 2px; margin-top: -50px; background-color: gray;" required><i style="margin-top: 5px;" class="material-icons">delete</i></a>
                        </div>

                        <div class="row" id="div_fornecedores_3" style="display: inline-block; margin-top: 20px;">
                           <div class="col s2">
                              <span style="font-size: 12px;">Fornecedor</span>
                              <input style="font-size: 12px;" id="fornecedor_3" name="fornecedor_3" type="text" class="validate" >
                          </div>
                          <div class="col s2">
                              <span style="font-size: 12px;">CPF/CNPJ</span>
                              <input style="font-size: 12px;" id="cpf_cnpj_3" name="cpf_cnpj_3" type="text" class="validate" >
                          </div>
                          <div class="col s2">
                              <span style="font-size: 12px;">Contato:</span>
                              <input style="font-size: 12px;" id="contato_3" name="contato_3" onkeypress="mask(this, mphone);" onblur="mask(this, mphone);" type="text" class="validate" >
                          </div>
                           <div class="col s1">
                              <span style="font-size: 12px;">Prazo:</span>
                              <input style="font-size: 12px;" id="prazo_3" name="prazo_3" type="number" class="validate" >
                           </div>
                           <div class="col s5">
                              <span style="font-size: 12px;">Observações:</span>
                              <input style="font-size: 12px;" id="observacoes_3" name="observacoes_3" type="text" class="validate">
                           </div>
                           <div class="col s2">
                              <span style="font-size: 12px;">Forma de Pagamento:</span>
                              <p>
                                 <label>
                                   <input class="with-gap" checked onclick="removeCampoParcela3();" name="boleto_cartao_3" id="boleto" value="boleto" type="radio" style="font-size: 12px;"  />
                                   <span style="font-size: 12px;">Boleto</span>
                                 </label>
                                 <label>
                                    <input class="with-gap" onclick="exibeCampoParcela3();" name="boleto_cartao_3" id="cartao" value="cartao" type="radio" style="font-size: 12px;" />
                                    <span style="font-size:12px;">Cartão</span>
                                  </label>
                               </p>
                           </div>

                           <div class="col s1">
                              <span style="font-size: 12px;">Valor:</span>
                              <input style="font-size: 12px;" id="valor_3" name="valor_3"  pattern="(?:\.|,|[0-9])*" onKeyPress="return(moeda2(this,'.',',',event))" type="text" class="validate">
                           </div>

                           <div class="col s1" id="div_parcelas_3">
                              <span style="font-size: 12px;">Parcelas:</span>
                              <input style="font-size: 12px;" id="parcelas_3" name="parcelas_3" type="number" class="validate">
                           </div>


                           <div class="col s1" id="div_frete_cartao_3">
                              <span style="font-size: 12px;">Frete:</span>
                              <input style="font-size: 12px;" id="frete_3" name="frete_3" type="text" class="validate" pattern="(?:\.|,|[0-9])*" onKeyPress="return(moeda2(this,'.',',',event))">
                           </div>

                           <div class="col s1" id="div_frete_boleto_3">
                              <span style="font-size: 12px;">Frete:</span>
                              <input style="font-size: 12px;" id="frete_boleto_3" name="frete_boleto_3" type="text" class="validate" pattern="(?:\.|,|[0-9])*" onKeyPress="return(moeda2(this,'.',',',event))">
                           </div>
   

                           <div class="col s1" id="div_total_3">
                              <span style="font-size: 12px;">Total:</span>
                              <input style="font-size: 12px;" id="total_3" name="total_3" readonly type="text" class="validate">
                           </div>

                                   
                        <div class="col s1" id="div_total_boleto_3">
                           <span style="font-size: 12px;">Total:</span>
                           <input style="font-size: 12px;" id="total_boleto_3" name="total_boleto_3" readonly type="text" class="validate">
                        </div>

                       

                        </div>
                       </div>
          
             </div>
           
                     <button id="button_enviar" class="btn waves-light green right align" style="margin-top: -60px; margin-right: 5px;" type="submit">Enviar
                        <i class="material-icons right">save</i>
                  </button>
                  </div>

                        <br>

                      
                        </div>
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
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
      <script type='text/javascript' src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>

      <script>
         var formID = document.getElementById("form");
         var send = $("#button_enviar");

         $(formID).submit(function(event){
         if (formID.checkValidity()) {
            send.attr('disabled', 'disabled');
         }
         });
      </script>
      
      <script>
         function mask(o, f) {
         setTimeout(function() {
            var v = mphone(o.value);
            if (v != o.value) {
               o.value = v;
            }
         }, 1);
         }

         function mphone(v) {
         var r = v.replace(/\D/g, "");
         r = r.replace(/^0/, "");
         if (r.length > 10) {
            r = r.replace(/^(\d\d)(\d{5})(\d{4}).*/, "($1) $2-$3");
         } else if (r.length > 5) {
            r = r.replace(/^(\d\d)(\d{4})(\d{0,4}).*/, "($1) $2-$3");
         } else if (r.length > 2) {
            r = r.replace(/^(\d\d)(\d{0,5})/, "($1) $2");
         } else {
            r = r.replace(/^(\d*)/, "($1");
         }
         return r;
         }
      </script>

      <script language="javascript">   
         $(document).ready(function($){
            $("input[name*='cpf_cnpj']").inputmask({
            mask: ['999.999.999-99', '99.999.999/9999-99']
            });
         });

         $(document).ready(function($){
            $("input[name*='cpf_cnpj_2']").inputmask({
            mask: ['999.999.999-99', '99.999.999/9999-99']
            });
         });

         $(document).ready(function($){
            $("input[name*='dpf_cnpj_3']").inputmask({
            mask: ['999.999.999-99', '99.999.999/9999-99']
            });
         });
      </script>

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
     
            $(document).ready(function() {
               document.getElementById('fornecedor_2').style.display = "none";
               document.getElementById('fornecedor_3').style.display = "none";
               document.getElementById('div_fornecedores_2').style.display = "none";
               document.getElementById('div_fornecedores_3').style.display = "none";
               document.getElementById('div_parcelas').style.display = "none";
               document.getElementById('div_parcelas_2').style.display = "none";
               document.getElementById('div_parcelas_3').style.display = "none";
               document.getElementById('div_total').style.display = "none";
               document.getElementById('div_total_2').style.display = "none";
               document.getElementById('div_total_3').style.display = "none";
               document.getElementById('div_frete_cartao').style.display = "none";
               document.getElementById('div_frete_cartao_2').style.display = "none";
               document.getElementById('div_frete_cartao_3').style.display = "none";
            });
      </script>
      
      <script>   
            function exibeCampoParcela(){
               document.getElementById('div_parcelas').style.display = "";
               document.getElementById('div_total').style.display = "";
               document.getElementById('div_total_boleto').style.display = "none";
               document.getElementById('div_frete_boleto').style.display = "none";
               document.getElementById('div_frete_cartao').style.display = "";
            }
            function exibeCampoParcela2(){
               document.getElementById('div_parcelas_2').style.display = "";
               document.getElementById('div_total_2').style.display = "";
               document.getElementById('div_total_boleto_2').style.display = "none";
               document.getElementById('div_frete_boleto_2').style.display = "none";
               document.getElementById('div_frete_cartao_2').style.display = "";
            }
            function exibeCampoParcela3(){
               document.getElementById('div_parcelas_3').style.display = "";
               document.getElementById('div_total_3').style.display = "";
               document.getElementById('div_total_boleto_3').style.display = "none";
               document.getElementById('div_frete_boleto_3').style.display = "none";
               document.getElementById('div_frete_cartao_3').style.display = "";
            }
            
            function removeCampoParcela(){
               document.getElementById('div_parcelas').style.display = "none";
               document.getElementById('div_total').style.display = "none";
               document.getElementById('div_total_boleto').style.display = "";
               document.getElementById('div_frete_boleto').style.display = "";
               document.getElementById('div_frete_cartao').style.display = "none";
            }
            function removeCampoParcela2(){
               document.getElementById('div_parcelas_2').style.display = "none";
               document.getElementById('div_total_2').style.display = "none";
               document.getElementById('div_total_boleto_2').style.display = "";
               document.getElementById('div_frete_boleto_2').style.display = "";
               document.getElementById('div_frete_cartao_2').style.display = "none";
            }
            function removeCampoParcela3(){
               document.getElementById('div_parcelas_3').style.display = "none";
               document.getElementById('div_total_3').style.display = "none";
               document.getElementById('div_total_boleto_3').style.display = "";
               document.getElementById('div_frete_boleto_3').style.display = "";
               document.getElementById('div_frete_cartao_3').style.display = "none";
            }

            function adicionaDiv(){
               document.getElementById('div_fornecedores_2').style.display = "";
               document.getElementById('fornecedor_2').style.display = "";
               $('#card').animate({"height" : "920"}, 200);
               $('#button_enviar').animate({"margin-right:" : "25px", "margin-top": "-40px"}, 200);
            }
            function adicionaDiv3(){
               document.getElementById('fornecedor_3').style.display = "";
               document.getElementById('div_fornecedores_3').style.display = "";
               $('#card').animate({"height" : "1145"}, 200);
               $('#button_enviar').animate({"margin-right:" : "25px", "margin-top": "-20px"}, 200);
            }

            function removeDiv3(){
               document.getElementById('fornecedor_3').style.display = "none";
               document.getElementById('div_fornecedores_3').style.display = "none";
               $('#card').animate({"height" : "920"}, 200);
               $('#button_enviar').animate({"margin-right:" : "25px", "margin-top": "-40px"}, 200);
            }
            function removeDiv2(){
               document.getElementById('div_fornecedores_2').style.display = "none";
               document.getElementById('fornecedor_2').style.display = "none";
               $('#card').animate({"height" : "695"}, 200);
               $('#button_enviar').animate({"margin-right:" : "5px", "margin-top": "-60px"}, 200);
            }
      </script>

   <script>
      $('#frete').on('blur', function() {

      var parcelas = $("#parcelas").val();
      var valor = parseFloat($("#valor").val().replace(',', '.'));
      var frete = parseFloat($("#frete").val().replace(',', '.'));
      var valor_total = valor / parcelas + frete;
      
      $("#total").val(parseFloat(valor_total).toFixed(2));
      
      });

      $('#frete_2').on('blur', function() {

         var parcelas = $("#parcelas_2").val();
         var valor = parseFloat($("#valor_2").val().replace(',', '.'));
         var frete2 = parseFloat($("#frete_2").val().replace(',', '.'));
         var valor_total = valor / parcelas + frete2;

         $("#total_2").val(parseFloat(valor_total).toFixed(2));

      });

      $('#frete_3').on('blur', function() {

      var parcelas = $("#parcelas_3").val();
      var valor = parseFloat($("#valor_3").val().replace(',', '.'));
      var frete3 = parseFloat($("#frete_3").val().replace(',', '.'));
      var valor_total = valor / parcelas  + frete3;

      $("#total_3").val(parseFloat(valor_total).toFixed(2));

      });

      $('#frete_boleto').on('blur', function() {

      var valor = parseFloat($("#valor").val().replace(',', '.'));
      var frete = parseFloat($("#frete_boleto").val().replace(',', '.'));
      var valor_total = valor + frete;

      $("#total_boleto").val(parseFloat(valor_total).toFixed(2));

      });


      $('#frete_boleto_2').on('blur', function() {

         var valor = parseFloat($("#valor_2").val().replace(',', '.'));
         var frete = parseFloat($("#frete_boleto_2").val().replace(',', '.'));
         var valor_total = valor + frete;

         $("#total_boleto_2").val(parseFloat(valor_total).toFixed(2));

         });

      $('#frete_boleto_3').on('blur', function() {

      var valor = parseFloat($("#valor_3").val().replace(',', '.'));
      var frete = parseFloat($("#frete_boleto_3").val().replace(',', '.'));
      var valor_total = valor + frete;

      $("#total_boleto_3").val(parseFloat(valor_total).toFixed(2));

      });
   </script>

   </body>
</html>