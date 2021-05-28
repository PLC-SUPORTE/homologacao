<!DOCTYPE html>
<html class="loading" lang="pt-br" data-textdirection="ltr">
   <head>
      <meta http-equiv="Content-Language" content="pt-br">
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <meta name="keywords" content="pesquisapatrimonial, pesquisa, patrimonial, cadastro, plc, portal, portela lima labato colen, bh, belo horizonte">
      <meta name="author" content="Portal PL&C">
      <title>Aprovar Solicitações</title>
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
   <body style="overflow: hidden;" class="vertical-layout vertical-menu-collapsible page-header-dark vertical-modern-menu preload-transitions 2-columns   " data-open="click" data-menu="vertical-modern-menu" data-col="2-columns">
      <header class="page-topbar" id="header">
         <div class="navbar navbar-fixed">
            <nav class="navbar-main navbar-color nav-collapsible navbar-dark no-shadow" style="background-color: gray">
               <div class="nav-wrapper">
                  <div class="breadcrumbs-inline pt-3 pb-1" id="breadcrumbs-wrapper" style="margin-top: -40px;">
                     <h5 class="breadcrumbs-title mt-0 mb-0 display-inline hide-on-small-and-down"><span style="color: white;">Aprovar Solicitações</span></h5>
                     <ol class="breadcrumbs mb-0">
                        <li class="breadcrumb-item"><a href="{{route('Home.Principal.Show')}}">Home</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{route('Painel.Compras.ComiteAprovacao.index_comite_aprovacao')}}">Solicitações</a>
                        </li>
                        <li class="breadcrumb-item active" style="color: black;">Aprovar Solicitações
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
            <div class="card" style="height: 580px;">
                <div class="card-content">
                        
                  
                        <h5 class="card-title">Produto</h5>

                        @foreach ($unidade_setor as $us)
                             <input type="hidden" value="{{$us->setor}}" name="setor" id="setor">
                              <input type="hidden" value="{{$us->unidade}}" name="unidade" id="unidade">
                        @endforeach

                        @foreach ($dados as $d)
                              <input type="hidden" value="{{$d->id_solicitante}}" name="id_solicitante" id="id_solicitante">

                              <div class="row">
                                 <div class="col s1">
                                    <span style="font-size: 12px;">Quantidade</span>
                                 <input style="font-size: 12px;" value="{{$d->quantidade}}" id="quantidade" name="quantidade" type="number" class="validate" readonly>
                        
                                 </div>
                                 <div class="col s3">
                                    <span style="font-size: 12px;">Especificações:</span>
                                    <input style="font-size: 12px;" value="{{$d->especificacoes}}" id="especificacoes" name="especificacoes" class="materialize-textarea" readonly></input>
                                 </div>
                                 <div class="col s3">
                                 <span style="font-size: 12px;">Observações:</span>
                                 <input style="font-size: 12px;" value="{{$d->observacoes}}" id="observacoes_produto" name="observacoes_produto" class="materialize-textarea" readonly></input>
                              </div>
                              <div class="col s3">
                              <span style="font-size: 12px;">Status:</span>
                              @if ($d->status == "Normal")
                                 <input style="font-size: 12px; color: green" name="status" id="status" type="text" value="{{$d->status}}" readonly>
                              @else 
                                 <input style="font-size: 12px; color: red" name="status" id="status" type="text" value="{{$d->status}}" readonly>
                              @endif
                           </div>
                          
                           </div>
                        @endforeach

                        <br>
                   
                        <h5 class="card-title">Fornecedores</h5>

                        <div class="responsive-table">
                           <table class="table invoice-data-table white border-radius-4 pt-1">
                               <thead>
                                  <tr>
                                     <th style="font-size: 11px">#</th>
                                     <th style="font-size: 11px">Fornecedor</th>
                                     <th style="font-size: 11px">CPF/CNPJ</th>
                                     <th style="font-size: 11px">Contato</th>
                                     <th style="font-size: 11px">Prazo</th>
                                     <th style="font-size: 11px">Observações</th>
                                     <th style="font-size: 11px">Valor</th>
                                     <th style="font-size: 11px">Parcelas</th>
                                     <th style="font-size: 11px">Frete</th>
                                     <th style="font-size: 11px">Total</th>
                                     @if ($usuario_aprovacao == 0)
                                     <th style="font-size: 11px">Ações</th>
                                     @endif
                                  </tr>
                               </thead>
                               <tbody>
                                 
                                 @foreach ($fornecedores as $f)
                                      
                                       <tr>
                                          <td style="font-size: 10px;">{{$f->id}}</td>
                                          <td style="font-size: 10px;">{{$f->fornecedor}}</td>
                                          <td style="font-size: 10px;">{{$f->cpf_cnpj}}</td>
                                          <td style="font-size: 10px;">{{$f->contato}}</td>
                                          <td style="font-size: 10px;">{{$f->prazo}}</td>
                                          {{-- <td style="font-size: 10px;">{{$f->observacoes}}</td> --}}
                                          @if ($f->observacoes == '' || $f->observacoes == null)
                                             <td style="font-size: 10px; color: red;"><span class="bullet red"></span>Não possui</td>
                                          @else 
                                             <td style="font-size: 10px;">{{$f->observacoes}}</td>
                                          @endif
                                          <td style="font-size: 10px;">{{number_format($f->valor, 2, ',', ' ')}}</td>
                                          <td style="font-size: 10px;">{{$f->parcelas}}</td>
                                          <td style="font-size: 10px;">{{number_format($f->frete, 2, ',', ' ')}}</td>
                                          <td style="font-size: 10px;">{{number_format($f->total, 2, ',', ' ')}}</td>
                                          @if ($usuario_aprovacao == 0)
                                          <td>
                                             {{-- <a href="{{route('Painel.Compras.ComiteAprovacao.aprovarCompra', $f->id)}}" 
                                                class="material-icons modal-trigger" style="color: #28a745;">
                                                <i class="material-icons" id="icon_aprovar">check</i></a> --}}
                                               
                                                <a href="#modalAprovar{{$f->id}}" 
                                                   class="material-icons modal-trigger" style="color: #28a745;">
                                                   <i class="material-icons" id="icon_aprovar">check</i></a>
                                          </td>
                                          @endif
                                       </tr>

                                   <div id="modalAprovar{{$f->id}}" class="modal" style="width: 450px;">
                                    <form id="form" role="form" action="{{route('Painel.Compras.ComiteAprovacao.aprovarCompra', $f->id)}}" method="POST" role="create"  enctype="multipart/form-data">
                                       {{ csrf_field() }}
                                       <a href="#!" style="color: white; margin-top: 3px; margin-right: 3px;"
                                    class="modal-close btn-flat right align red"><i class="material-icons">close</i></a>

                                    <div class="modal-content">
                                       @foreach ($dados as $d)
                                       <input type="hidden" value="{{$d->id_solicitante}}" name="id_solicitante" id="id_solicitante">
                                       <input type="hidden" value="{{$d->status}}" name="status" id="status">
                                       @endforeach
                                       <input type="hidden" value="{{$f->id}}" name="id_fornecedor" id="id_fornecedor">
                                       <input type="hidden" value="{{$f->fornecedor}}" name="fornecedor" id="fornecedor">
                                       <input type="hidden" value="{{$f->cpf_cnpj}}" name="cpf_cnpj" id="cpf_cnpj">
                                       <input type="hidden" value="{{$f->contato}}" name="contato" id="contato">
                                       <input type="hidden" value="{{$f->prazo}}" name="prazo" id="prazo">
                                       <input type="hidden" value="{{$f->observacoes}}" name="observacoes" id="observacoes">
                                       <input type="hidden" value="{{$f->valor}}" name="valor" id="valor">
                                       <input type="hidden" value="{{$f->parcelas}}" name="parcelas" id="parcelas">
                                       <input type="hidden" value="{{$f->total}}" name="total" id="total">
                                       <input type="hidden" value="{{$f->fornecedor}}" name="fornecedor_descricao" id="fornecedor_descricao">
                                       <input type="hidden" value="{{$f->id_comite_compras}}" name="id_comite_compras" id="id_comite_compras">
                                       <input type="hidden" value="{{$f->frete}}" name="frete" id="frete">

                                       <center>
                                       <i class="material-icons" style="color: orange; margin-left: 65px; font-size: 100px;">
                                          info
                                       </i>
                                       <h6>Aprovar solicitação - <b>{{$f->fornecedor}}</b></h6>
                                       <p style="font-size: 14px;"><b>Deseja aprovar a solicitação com este fornecedor?</b></p>
                                       <p style="font-size: 12px;"><b>A ação não pode ser desfeita.</b></p>
                                       <br>
                                       <div class="col s4" style="margin-left: 55px;">
                                          <input style="font-size: 12px;" type="file" id="input-file-now" 
                                          name="select_file" accept=".jpg,.png,.xls,.xlsx,.pdf,.doc,.docx,.rtf,.jpeg" 
                                          class="dropify tooltipped" data-default-file="" required  data-position="top" data-tooltip="Inserir comprovante"/>
                                       </div>

                                    </center>

                                    </div>
                                    <div class="modal-footer" style="margin-top: 20px;">
                                       <button class="btn-flat green" type="submit" style="color: white;">Aprovar <i class="material-icons right">save</i></button>
                                    </div>
                                    </form>
                                 </div>


                                 @endforeach
                                 
                                 </tbody>
                            </table>
                        </div>


                    
                        {{-- @if (count($fornecedores) > 1 || count($fornecedores) == null)
                        <button class="btn waves-light green right align tooltipped" disabled style="margin-top: 15px; margin-right: -20px;" name="action">Aprovar
                           <i class="material-icons right">save</i>
                       </button>
                        @else
                        <button class="btn waves-light green right align" style="margin-top: 15px; margin-right: -20px;" type="submit" name="action">Aprovar
                           <i class="material-icons right">save</i>
                       </button>
                        @endif --}}

                        @foreach ($dados as $d)
                        @if ($usuario_aprovacao == 0)
                           <a class="btn waves-light red left align" href="{{route('Painel.Compras.ComiteAprovacao.reprovar', $d->id_solicitante)}}" 
                           style="margin-top: 15px; margin-right: 3px;" name="action">Reprovar  
                              <i class="material-icons right">close</i>
                           </a>
                        @endif
                        @endforeach

                    

                    {{-- </form> --}}
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
      <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

      <script>
         $(document).ready(function () {
         $('.modal').modal();
      });
      </script>

    
         

   </body>
</html>