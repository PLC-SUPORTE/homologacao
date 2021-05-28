<!DOCTYPE html>
<html class="loading" lang="pt-br" data-textdirection="ltr">
   <head>
      <meta http-equiv="Content-Language" content="pt-br">
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
      <meta name="author" content="Portal PL&C">
      <title>Nova solicitação de pesquisa patrimonial | Portal PL&C</title>
      <link rel="apple-touch-icon" href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/images/favicon/apple-touch-icon-152x152.png">
      <link rel="shortcut icon" href="{{ asset('/public/assets/imgs/casinha.png')}}" />
      <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
      <link rel="stylesheet" href="{{ asset('/public/materialize/css/vendors.min.css') }}">
      <link rel="stylesheet" href="{{ asset('/public/materialize/css/materialize.min.css') }}">
      <link rel="stylesheet" href="{{ asset('/public/materialize/css/style.min.css') }}">
      <link rel="stylesheet" href="{{ asset('/public/materialize/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('/public/materialize/css/select2-materialize.css') }}">

      <style>
     .span{
        font-weight: bold;
     }
/* HTML5 display-role reset for older browsers */
article, aside, details, figcaption, figure, 
footer, header, hgroup, menu, nav, section, main {
	display: block;
}
ol, ul {
	list-style: none;
}
blockquote, q {
	quotes: none;
}
blockquote:before, blockquote:after,
q:before, q:after {
	content: '';
	content: none;
}
table {
	border-collapse: collapse;
	border-spacing: 0;
}

.img-replace {
  /* replace text with an image */
  display: inline-block;
  overflow: hidden;
  text-indent: 100%;
  color: transparent;
  white-space: nowrap;
}


.cd-nugget-info {
  text-align: center;
  position: absolute;
  width: 100%;
  height: 50px;
  line-height: 50px;
  bottom: 0;
  left: 0;
}
.cd-nugget-info a {
  position: relative;
  font-size: 14px;
  color: #5e6e8d;
  -webkit-transition: all 0.2s;
  -moz-transition: all 0.2s;
  transition: all 0.2s;
}
.no-touch .cd-nugget-info a:hover {
  opacity: .8;
}
.cd-nugget-info span {
  vertical-align: middle;
  display: inline-block;
}
.cd-nugget-info span svg {
  display: block;
}
.cd-nugget-info .cd-nugget-info-arrow {
  fill: #5e6e8d;
}


.cd-popup-trigger {
  display: block;
  width: 170px;
  height: 50px;
  line-height: 50px;
  margin: 3em auto;
  text-align: center;
  color: #FFF;
  font-size: 14px;
  font-size: 0.875rem;
  font-weight: bold;
  text-transform: uppercase;
  border-radius: 50em;
  background: #35a785;
  box-shadow: 0 3px 0 rgba(0, 0, 0, 0.07);
}
@media only screen and (min-width: 1170px) {
  .cd-popup-trigger {
    margin: 6em auto;
  }
}

.cd-popup {
  position: fixed;
  left: 0;
  top: 0;
  height: 100%;
  width: 100%;
  opacity: 0;
  visibility: hidden;
  -webkit-transition: opacity 0.3s 0s, visibility 0s 0.3s;
  -moz-transition: opacity 0.3s 0s, visibility 0s 0.3s;
  transition: opacity 0.3s 0s, visibility 0s 0.3s;
}
.cd-popup.is-visible {
  opacity: 1;
  visibility: visible;
  -webkit-transition: opacity 0.3s 0s, visibility 0s 0s;
  -moz-transition: opacity 0.3s 0s, visibility 0s 0s;
  transition: opacity 0.3s 0s, visibility 0s 0s;
}

.cd-popup-container {
  position: relative;
  width: 90%;
  max-width: 400px;
  margin: 4em auto;
  background: #FFF;
  border-radius: .25em .25em .4em .4em;
  text-align: center;
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
  -webkit-transform: translateY(-40px);
  -moz-transform: translateY(-40px);
  -ms-transform: translateY(-40px);
  -o-transform: translateY(-40px);
  transform: translateY(-40px);
  /* Force Hardware Acceleration in WebKit */
  -webkit-backface-visibility: hidden;
  -webkit-transition-property: -webkit-transform;
  -moz-transition-property: -moz-transform;
  transition-property: transform;
  -webkit-transition-duration: 0.3s;
  -moz-transition-duration: 0.3s;
  transition-duration: 0.3s;
}
.cd-popup-container p {
  padding: 1em  1em;
}
.cd-popup-container .cd-buttons:after {
  content: "";
  display: table;
  clear: both;
}
.cd-popup-container .cd-buttons li {
  float: left;
  width: 50%;
  list-style: none;
}
.cd-popup-container .cd-buttons a {
  display: block;
  height: 60px;
  line-height: 60px;
  text-transform: uppercase;
  color: #FFF;
  -webkit-transition: background-color 0.2s;
  -moz-transition: background-color 0.2s;
  transition: background-color 0.2s;
}
.cd-popup-container .cd-buttons li:first-child a {
  background: #b6bece;
  border-radius: 0 0 0 .25em;
}
.no-touch .cd-popup-container .cd-buttons li:first-child a:hover {
  background-color: #fc8982;
}
.cd-popup-container .cd-buttons li:last-child a {
  background: #52ca52;
  border-radius: 0 0 .25em 0;
}
.no-touch .cd-popup-container .cd-buttons li:last-child a:hover {
  background-color: #c5ccd8;
}
.cd-popup-container .cd-popup-close {
  position: absolute;
  top: 8px;
  right: 8px;
  width: 30px;
  height: 30px;
}
.cd-popup-container .cd-popup-close::before, .cd-popup-container .cd-popup-close::after {
  content: '';
  position: absolute;
  top: 12px;
  width: 14px;
  height: 3px;
  background-color: #8f9cb5;
}
.cd-popup-container .cd-popup-close::before {
  -webkit-transform: rotate(45deg);
  -moz-transform: rotate(45deg);
  -ms-transform: rotate(45deg);
  -o-transform: rotate(45deg);
  transform: rotate(45deg);
  left: 8px;
}
.cd-popup-container .cd-popup-close::after {
  -webkit-transform: rotate(-45deg);
  -moz-transform: rotate(-45deg);
  -ms-transform: rotate(-45deg);
  -o-transform: rotate(-45deg);
  transform: rotate(-45deg);
  right: 8px;
}
.is-visible .cd-popup-container {
  -webkit-transform: translateY(0);
  -moz-transform: translateY(0);
  -ms-transform: translateY(0);
  -o-transform: translateY(0);
  transform: translateY(0);
}
@media only screen and (min-width: 1170px) {
  .cd-popup-container {
    margin: 8em auto;
  }
}
    </style>

   </head>
   
   <?php
      $connect = new PDO("sqlsrv:Server=192.168.150.14,65014;Database=Intranet", "six", "89202");
      // $connect = new PDO("sqlsrv:Server=localhost;Database=intranet", "sa", "Dalila08duque15");
      function fill_unit_select_box($connect)
      { 
       $output = '';
       $query = "SELECT * FROM PesquisaPatrimonial_Cidades ORDER BY uf_sigla ASC";
       $statement = $connect->prepare($query);
       $statement->execute();
       $result = $statement->fetchAll();
       foreach($result as $row)
       {
        $output .= '<option value="'.$row["municipio"].'">'.$row["municipio"].'</option>';
       }
       return $output;
      }
      
      ?>

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
                  <li class="breadcrumb-item active" style="color: black;">Nova solicitação de pesquisa patrimonial
                  </li>
                </ol>
            </div>

            <ul class="navbar-list right" style="margin-top: -80px;">

            
                     
                     <li><a class="waves-block waves-light notification-button" href="javascript:void(0);" data-target="notifications-dropdown"><i class="material-icons">notifications_none<small class="notification-badge">{{$totalNotificacaoAbertas}}</small></i></a></li>
                   <li><a class="waves-effect waves-block waves-light profile-button" href="javascript:void(0);" data-target="profile-dropdown"><span class="avatar-status avatar-online"><img src="{{ asset('/public/AdminLTE/img/Avatar/img.png') }}" class="img-circle" alt="User Image"><i></i></span></a></li>                  </ul>
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
      <div id="main">




      <div id="loadingdiv" style="display:none;margin-top: 200px; margin-left: 570px;">
      <img style="width: 30px; margin-top: -100px;" src="{{URL::asset('/public/imgs/loading.gif')}}"/>
      <h6 style="font-size: 20px;margin-left:-210px;">Aguarde, estamos criando a solicitação de pesquisa patrimonial...</h6>
      </div>

         <div class="row">
            <div class="col s12">
               <div class="card">
                  <div class="card-content" id="corpodiv">

                  <div class="card">
        <div class="card-content">
            <p class="caption mb-0" style="font-size: 11px;">
                Favor preencher os campos abaixo para o cadastro de uma nova solicitação de pesquisa patrimonial. Caso marque a opção [Judicial] será necessário preencher o número do processo ou código da pasta.
            </p>
        </div>
    </div>


                     <form role="form" id="form" onsubmit="btnsubmit.disabled = true; return true;" action="{{ route('Painel.PesquisaPatrimonial.solicitacao.store') }}" method="POST" role="search"  enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <input type="hidden" name="codigounidade" id="codigounidade">

                        <div class="input-field col s3">
                           <p>
                           <span style="font-size: 11px;">Indique a classificação</span><br>
                              <label>
                                 <input class="with-gap" name="classificacao" value="JUDICIAL" type="radio" checked onClick="judicial();"/>
                                 <span style="font-size: 10px;">Judicial</span>
                              </label>
                              <label>
                                 <input class="with-gap" name="classificacao" value="EXTRAJUDICIAL" type="radio" onClick="extrajudicial();"/>
                                 <span style="font-size: 10px;">Extrajudicial</span>
                              </label>
                           </p>
                        </div>

                        <div class="input-field col s3" id="inputnumeroprocesso">
                           <span style="font-size: 11px;">Número do processo ou código da pasta:</span>
                           <input style="font-size: 10px;" name="dados" id="dados" onBlur="buscaDados();" maxlength="25" type="text" placeholder="Preencha o número do processo ou código da pasta...">
                           <input name="id_pasta" id="id_pasta" type="hidden" maxlength="255" class="form-control">
                        </div>

                        <div class="input-field col s3">
                           <span style="font-size: 11px;">CPF/CNPJ do pesquisado:</span>
                           <input style="font-size: 10px;" placeholder="CPF/CNPJ" name="cpf_cnpj" id="cpf_cnpj" required="required" class="cpf_cnpj" maxlength="20" type="text">
                        </div>

                        <div class="input-field col s3">
                           <span style="font-size: 11px;">Nome do pesquisado:</span>
                           <input style="font-size: 10px;" placeholder="Nome Pesquisado" type="text" name="nome" id="nome" class="validate">
                        </div>

                        <div class="input-field col s3" id="grupojudicial">
                           <span style="font-size: 11px;">Grupo cliente:</span>
                           <input style="font-size: 10px;" placeholder="Grupo cliente" type="text" name="grupo" id="grupo" class="validate">
                           <input name="codigogrupo" id="codigogrupo" type="hidden">
                        </div>

                        <div class="input-field col s3" id="clientejudicial">
                           <span style="font-size: 11px;">Cliente:</span>
                           <input type="hidden" name="codigocliente" id="codigocliente" value="">
                           <input style="font-size: 10px;" placeholder="Cliente" type="text" name="cliente" id="cliente" class="validate">
                        </div>

                        <div class="input-field col s3" id="grupoextrajudicial">
                           <span style="font-size: 11px;">Selecione o grupo do cliente:</span>
                           <select style="font-size: 10px;" class="select2 browser-default" id="grupoclienteselected" name="grupoclienteselected">
                              <option value="" selected>Selecione abaixo</option>
                              @foreach($gruposcliente as $data) 
                              <option value="{{ $data->Codigo }}">{{ $data->Descricao }}</option>
                              @endforeach
                           </select>
                        </div>

                        <div class="input-field col s6" id="clienteextrajudicial">
                        <span style="font-size: 11px;">Selecione o cliente:</span>
                           <select style="font-size: 10px;" class="select2 browser-default" id="clienteselected" name="clienteselected">
                              <option value="" selected>Selecione abaixo</option>
                           </select>
                        </div>

                        <div class="input-field col s3">
                           <span style="font-size: 11px;">Selecione o setor de custo do PL&C:</span>
                           <select style="font-size: 10px;" class="browser-default" required="required" id="setor" name="setor" >
                              @foreach($setores as $data)   
                              <option value="{{ $data->Codigo }}"> {{$data->Codigo}} - {{ $data->Descricao }}</option>
                              @endforeach
                           </select>
                        </div>

                        <div class="input-field col s3">
                        <span style="font-size: 11px;">Selecione o tipo de serviço:</span>
                           <select class="browser-default" style="font-size: 10px;" required="required"  id="tiposervico" name="tiposervico">
                           <option value="" selected>Selecione abaixo</option>   
                           <option value="3">Atualização</option>
                           <option value="4">Nova pesquisa</option>
                           <option value="6">Pesquisa prévia</option>
                           </select>
                        </div>


                        <div class="input-field col s12">
                           <p>
                              <span style="font-size: 11px">Selecione o tipo da solicitação</span><br>
                              <label>
                              <input class="with-gap" name="tipo" id="rdn_completa" value="COMPLETA" type="radio" checked onClick="completa();"/>
                              <span style="font-size: 10px;">Completa</span>
                              </label>
                              <label>
                              <input class="with-gap" name="tipo" id="rdn_parcial" value="PARCIAL" type="radio" onClick="parcial();"/>
                              <span style="font-size: 10px;">Parcial</span>
                              </label>
                           </p>
                        </div>

                        <div class="row" id="tipodiv" style="display: none">
                           <div class="col s8">
                              <h6 style="font-size: 11px;">Tipos de pesquisa</h6>
                              <p style="font-size: 11px;">Selecione abaixo os tipos de pesquisa.</p>
                              <div class="input-field">
                                 <select style="font-size: 10px;" name="tipossolicitacao[]"  id='tipossolicitacao' class="select2 browser-default" multiple="multiple">
                                    <option value="2" selected>Imovel</option>
                                    <option value="4" selected>Empresa</option>
                                    <option value="7" selected>Protestos</option>
                                    <option value="5" selected>Infojud</option>
                                    <option value="3" selected>Veiculo</option>
                                    <option value="6" selected>Bacenjud</option>
                                    <option value="8" selected>Redes Sociais</option>
                                    <option value="9" selected>Processos Judiciais</option>
                                    <option value="10" selected>Pesquisa Cadastral</option>
                                    <option value="11" selected>Dossiê Comercial</option>
                                 </select>
                              </div>
                           </div>
                        </div>
                        
                        <div class="row">
                           <div class="input-field col s12">
                              <div class="table-repsonsive">
                                 <table class="table table-bordered" id="item_table" style="font-size: 10px;">
                                    <tr>
                                       <th style="width:30%">UF</th>
                                       <th style="width:70%">Cidade</th>
                                       <th><button
                                          type="button" name="add" style="width: 170px;background-color:gray;"
                                          class="waves-effect waves-light btn add tooltipped"
                                           data-position="left" data-tooltip="Informe as localidades onde serão feitas as pesquisas.">
                                          <span class="glyphicon glyphicon-plus"></span><i class="material-icons left">add</i>Adicionar</button></th>
                                    </tr>
                                    <tr>
                                       <td>
                                          <select style="font-size: 10px;" name="estado[]" required="required" class="select2 browser-default">
                                             <option value="12">Acre</option>
                                             <option value="27">Alagoas</option>
                                             <option value="16">Amapá</option>
                                             <option value="13">Amazonas</option>
                                             <option value="29">Bahia</option>
                                             <option value="23">Ceará</option>
                                             <option value="53">Distrito Federal</option>
                                             <option value="32">Espírito Santo</option>
                                             <option value="52">Goiás</option>
                                             <option value="21">Maranhão</option>
                                             <option value="51">Mato Grosso</option>
                                             <option value="50">Mato Grosso do Sul</option>
                                             <option value="31">Minas Gerais</option>
                                             <option value="15">Pará</option>
                                             <option value="25">Paraíba</option>
                                             <option value="41">Paraná</option>
                                             <option value="26">Pernambuco</option>
                                             <option value="22">Piauí</option>
                                             <option value="33">Rio de Janeiro</option>
                                             <option value="24">Rio Grande do Norte</option>
                                             <option value="43">Rio Grande do Sul</option>
                                             <option value="11">Rondônia</option>
                                             <option value="14">Roraima</option>
                                             <option value="42">Santa Catarina</option>
                                             <option value="35">São Paulo</option>
                                             <option value="35">Sergipe</option>
                                             <option value="17">Tocantins</option>
                                          </select>
                                       </td>
                                       <td>
                                          <select id="cidade" style="font-size: 10px;" name="cidade[]" required="required" class="select2 browser-default">
                                             <option value="">Selecione a comarca</option>
                                             <?php echo fill_unit_select_box($connect); ?>
                                          </select>
                                       </td>
                                       <td></td>
                                    </tr>
                                 </table>
                              </div>
                           </div>
                        </div>

                        <div class="row">
                           <div class="input-field col s3">
                           <p>
                              <span style="font-size: 11px">Esta pesquisa deverá ser cobrável ao cliente?<span><br>
                              <label>
                              <input class="with-gap" name="cobravel" type="radio" value="SIM" id="test1" onClick="cobrar();" checked />
                              <span style="font-size: 10px;">Sim</span>
                              </label>
                              <label>
                              <input class="with-gap" name="cobravel" type="radio" value="NAO" onClick="naocobravel();" id="test2" />
                              <span style="font-size: 10px;">Não</span>
                              </label>
                           </p>
                        </div>

                        <div class="input-field col s4" id="emailcliente">
                           <span style="font-size: 11px;">E-mail do cliente:</span>
                           <input style="font-size: 10px;" name="email" id="email" type="email" class="validate">
                        </div>

                        </div>

                        <div class="row">
                        <div class="input-field col s12">
                       <div class="form-group">
                        <div class="form-group">
                        <span style="font-size: 10px;" class="control-label">Observação:</span>
                        <textarea id="observacao" required rows="4" type="text" name="observacao" class="form-control" placeholder="Preencha abaixo o campo observação caso necessário." style="height: 5rem;text-align:left; overflow:auto;font-size:10px;"></textarea>
                      </div>
                    </div>
                     </div>   
                        </div>

                        <div class="row">
                        <div class="col s12 m12 l12">
                        <input type="file" style="font-size: 10px;" id="input-file-now" name="select_file" required class="dropify" data-default-file="" />
                        </div>
                        </div>

                        <div class="row" style="margin-left: 1000px;">
                           <button type="button" onClick="envia();" class="btn right-align" id="btnsubmit"
                            style="margin-top: 30px;background-color: gray;" type="submit"><i class="material-icons left">save</i>Criar solicitação</button>
                        </div>

                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>

<div id="alertaconfirmacao" name="alertaconfirmacao" class="cd-popup" role="alert">
	<div class="cd-popup-container">
		<p style="font-weight: bold;">Deseja gravar a solicitação de pesquisa patrimonial?</p>
		<ul class="cd-buttons">
			<li><a href="#" onClick="nao();" style="font-weight: bold;">Não</a></li>
			<li><a href="#" onClick="sim();" style="font-weight: bold;">Sim</a></li>
		</ul>
		<a onClick="nao();" class="cd-popup-close img-replace">Close</a>
	</div> 
</div>

<div id="alertatiposervico" name="alertatiposervico" class="cd-popup" role="alert">
	<div class="cd-popup-container">
		<p id="tagptiposervico" style="font-weight: bold;"></p>
		<ul class="cd-buttons" style="width: 800px">
			<li><a href="#" onClick="nao();" style="font-weight: bold;background-color:#52ca52">OK</a></li>
		</ul>
		<a onClick="nao();" class="cd-popup-close img-replace">Close</a>
	</div> 
</div>

<div id="alertaanexo1" name="alertaanexo1" class="cd-popup" role="alert">
	<div class="cd-popup-container">
      <p style="font-weight: bold;">Favor anexar o comprovante da pesquisa patrimonial.</p>
      <p style="font-weight: bold;">Ao marcar a opção que irá cobrar do cliente seguirá o workflow do núcleo e equipe do financeiro.</p>
		<ul class="cd-buttons" style="width: 800px">
			<li><a href="#" onClick="abrirficheiro();" style="font-weight: bold;background-color:#52ca52">OK</a></li>
		</ul>
		<a onClick="nao();" class="cd-popup-close img-replace">Close</a>
	</div> 
</div>



      <div id="alertacamposfaltantes" name="alertacamposfaltantes" class="cd-popup" role="alert">
	<div class="cd-popup-container">
		<p style="font-weight: bold;">Preencha todos os campos, comarca e anexe ao menos um arquivo.</p>
		<ul class="cd-buttons" style="width: 800px">
			<li><a href="#" onClick="nao();" style="font-weight: bold;background-color:#b6bece">Fechar</a></li>
		</ul>
		<a onClick="nao();" class="cd-popup-close img-replace">Close</a>
	</div> 
</div> 

<div id="chamaalerta" name="chamaalerta" class="cd-popup" role="alert">
	<div class="cd-popup-container">
		<p style="font-weight: bold;">Informe um CPF ou CNPJ válido!</p>
		<ul class="cd-buttons" style="width: 800px">
			<li><a href="#" onClick="nao();" style="font-weight: bold;background-color:#b6bece">Fechar</a></li>
		</ul>
		<a onClick="nao();" class="cd-popup-close img-replace">Close</a>
	</div> 
</div> 

<div id="alertacampoemail" name="alertacampoemail" class="cd-popup" role="alert">
	<div class="cd-popup-container">
		<p style="font-weight: bold;">Favor informar o e-mail do cliente para envio da cobrança!</p>
		<ul class="cd-buttons" style="width: 800px">
			<li><a href="#" onClick="nao();" style="font-weight: bold;background-color:#b6bece">Fechar</a></li>
		</ul>
		<a onClick="nao();" class="cd-popup-close img-replace">Close</a>
	</div> 
</div> 

<div id="alertacontroladoria" name="alertacontroladoria" class="cd-popup" role="alert">
	<div class="cd-popup-container">
		<p style="font-weight: bold;">Você não pode informar uma pasta ou cliente de uso exclusivo da controladoria!</p>
		<ul class="cd-buttons" style="width: 800px">
			<li><a href="#" onClick="nao();" style="font-weight: bold;background-color:#b6bece">Fechar</a></li>
		</ul>
		<a onClick="nao();" class="cd-popup-close img-replace">Close</a>
	</div> 
</div> 




      <script src="{{ asset('/public/materialize/js/vendors.min.js') }}"></script>
      <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
      <script type='text/javascript' src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
      <script src="{{ asset('/public/AdminLTE/dist/js/valida_cpf_cnpj.js') }}"></script>
      <script src="{{ asset('/public/AdminLTE/dist/js/exemplo_2.js') }}"></script>
      <script src="{{ asset('/public/materialize/js/select2.full.min.js') }}"></script>
      <script src="{{ asset('/public/materialize/js/form-select2.min.js') }}"></script>


<script>
   $(document).ready(function(){
         
   $(document).on('click', '.add', function(){
   var html = '';
   html += '<tr>';
   html += '<td><select name="estado[]" required="required" class="select2 browser-default"><option value="12">Acre</option><option value="27">Alagoas</option><option value="16">Amapá</option><option value="13">Amazonas</option><option value="29">Bahia</option><option value="23">Ceará</option><option value="53">Distrito Federal</option><option value="32">Espírito Santo</option><option value="52">Goiás</option><option value="21">Maranhão</option><option value="51">Mato Grosso</option><option value="50">Mato Grosso do Sul</option><option value="31">Minas Gerais</option><option value="15">Pará</option><option value="25">Paraíba</option><option value="41">Paraná</option><option value="26">Pernambuco</option><option value="22">Piauí</option><option value="33">Rio de Janeiro</option><option value="24">Rio Grande do Norte</option><option value="43">Rio Grande do Sul</option><option value="11">Rondônia</option><option value="14">Roraima</option><option value="42">Santa Catarina</option><option value="35">São Paulo</option><option value="35">Sergipe</option><option value="17">Tocantins</option></select></td>';
   html += '<td><select id="cidade" style="font-size: 10px;" name="cidade[]" required="required" class="select2 browser-default"><option value="">Selecione a comarca</option><?php echo fill_unit_select_box($connect); ?></select></td>'; 
   html += '<td><button type="button" name="remove" class="btn-floating btn-large waves-effect waves-light red remove" style="width: 50px; height: 50px;"><i class="material-icons" style="margin-top: -5px;">remove</i></button></td></tr>';
   $('#item_table').append(html);
   });
          
   $(document).on('click', '.remove', function(){
      $(this).closest('tr').remove();
   });
          
   });
</script>

<script>
function envia() {

   var anexo = $('#input-file-now').val();
   var cidade = $('#cidade').val();
   var cobravel  = $('input[name="cobravel"]:checked').val();
   var emailcliente = $('#email').val();

   if(cobravel == "SIM") {
      if ($('#email').val().length == 0){
   $('#alertacampoemail').addClass('is-visible');
   }
   }


   if ($('#input-file-now').val().length != 0){
    $('#alertaconfirmacao').addClass('is-visible');
   }else if ($('#cidade').val().length != 0){
   $('#alertacamposfaltantes').addClass('is-visible');
   }else {
   $('#alertacamposfaltantes').addClass('is-visible');
  }
}
</script>

<script>
  function sim() {
    $('.modal').css('display', 'none');
    document.getElementById("loadingdiv").style.display = "";
    document.getElementById("corpodiv").style.display = "none";
    $('.cd-popup').removeClass('is-visible');
    document.getElementById("form").submit();
         
  }    
</script>

<script>
 function nao() {
  $('.cd-popup').removeClass('is-visible');
 }
</script>

<script>
function chamaalerta() {

   $('#chamaalerta').addClass('is-visible');
}
</script>

<script>
$("#tiposervico").change(function(){

    var valorpermitido = $("#tiposervico").val();
    var atualizacao = "Prezado solicitante, ao selecionar o tipo de solicitação [Atualização], será encaminhado a ficha ao núcleo de pesquisa patrimonial, para que seja feita a atualização de uma pesquisa já existente.";
    var pesquisaprevia = "Prezado solicitante, ao selecionar o tipo de solicitação [Pesquisa prévia], será encaminhado a ficha ao núcleo de pesquisa patrimonial, para que possa precificar possíveis valores a serem gastos em sua solicitação.";
    var novapesquisa = "Prezado solicitante, ao selecionar o tipo de solicitação [Nova pesquisa], será encaminhado a ficha ao núcleo de pesquisa patrimonial. Indique se a solicitação é cobrável ou não do cliente. O tipo de solicitação [Nova pesquisa], irá seguir o workflow do núcleo de pesquisa respeitando suas etapas para que possa ser concluída a investigação patrimonial. ";

    if(valorpermitido == 3) {
      $('#tagptiposervico').html(atualizacao);

      //Desbloquear o tipo de solicitação completa 
      $("#rdn_completa").prop("disabled",true);
      $("#rdn_parcial").prop("checked", true);
      parcial();
    } else if(valorpermitido == 4) {
      $('#tagptiposervico').html(novapesquisa);

      //Desbloquear o tipo de solicitação completa 
      $("#rdn_completa").prop("disabled",false);
      $("#rdn_completa").prop("checked",true);
      $("#rdn_parcial").prop("checked", false);
      completa();
    } else {
      $('#tagptiposervico').html(pesquisaprevia);

      //Bloquear o tipo de solicitação completa 
      $("#rdn_completa").prop("disabled",true);
      $("#rdn_parcial").prop("checked", true);
      parcial();

    }
    $('#alertatiposervico').addClass('is-visible');

  });
 </script>

<script>
$("#email").change(function(){

    $('#alertaanexo1').addClass('is-visible');

  });
 </script>

 <script>
    function abrirficheiro(){
      $('#alertaanexo1').removeClass('is-visible');
      $('#input-file-now').trigger('click'); 

    }
</script>




<script>
$("#grupoclienteselected").change(function(){

    var grupocliente = $('#grupoclienteselected').val();

    var _token = $('input[name="_token"]').val();

    $.ajax({
      type: 'POST',
      url:"{{ route('Painel.PesquisaPatrimonial.solicitacao.buscaclientes') }}",
      data:{grupocliente:grupocliente,_token:_token,},
      dataType: 'json',
      cache: false,
      success: function(response) {

        $('#clienteselected option:not(:selected)').remove();

        var selectbox = $('#clienteselected');
          $.each(response, function (i, d) {
              selectbox.append('<option value="' + d.Codigo + '">' + d.Razao + '</option>');
        });


    }

  });


  });
 </script>


      <!--Começa com os campos do Judicial abertos e extrajudicial ocultos -->
      <script>
         $(document).ready(function(){
         
             $("#inputnumeroprocesso").show();
             $("#grupoextrajudicial").hide();
             $("#clienteextrajudicial").hide();
             $("#grupojudicial").show();
             $("#clientejudicial").show();
         
             $('#tipossolicitacao').val([2,3,4,7,8,9,10,11,12]).change();
             $('#tipossolicitacao option:not(:selected)').remove();
         });
      </script>   

      <script>
         function judicial() {
            $("#inputnumeroprocesso").show();
            $("#grupoextrajudicial").hide();
            $("#clienteextrajudicial").hide();
            $("#grupojudicial").show();
            $("#clientejudicial").show();
         
            $('#tipossolicitacao').val([2,3,4,5,6,7,8,9,10,11,12]).change();
            $('#tipossolicitacao option:not(:selected)').remove();
         
         }
      </script>
      <script>
         function extrajudicial() {
         
             $("#inputnumeroprocesso").hide();
             $("#grupoextrajudicial").show();
             $("#clienteextrajudicial").show();
             $("#grupojudicial").hide();
             $("#clientejudicial").hide();
         
             $('#tipossolicitacao').val([2,3,4,7,8,9,10,11,12]).change();
             $('#tipossolicitacao option:not(:selected)').remove();
         
         }
      </script>
      <script>
         function cobrar() {
             $("#emailcliente").show();
             email.setAttribute('required', 'required');
         }
      </script>

      <script>
         function naocobravel() {
             $("#emailcliente").hide();
         }
      </script>

      <script language="javascript">   
         $(document).on("change", "#cpf_cnpj", function() {
               
             var codigo = $(this).val().replace('.', '').replace('.', '').replace('-', '');
         
             var _token = $('input[name="_token"]').val();
         
         $.ajax({
         url:"{{ route('dynamicdependent.fetch6') }}",
         type: 'POST',
         data:{codigo:codigo,_token:_token,},
         success:function(response){
                  
            //  $('#tiposervico').html(response);
         
             $('#tipossolicitacao').val([2,3,4,5,6,7,8,9,10,11,12]).change();
         
             }
         
         
         });
         
             });
      </script>
      
      <script language="javascript">   
         $(document).ready(function($){
         $("input[id*='cpf_cnpj']").inputmask({
         mask: ['999.999.999-99', '99.999.999/9999-99']
         });
         
         });
      </script>

      <script>
         function completa() {

            $('#tipossolicitacao').val([2,3,4,5,6,7,8,9,10,11,12]).change();
           document.getElementById("tipodiv").style.display = "none";
         }
      </script>
      <script>
         function parcial() {

           $('#tipossolicitacao').val([2]).change();
           document.getElementById("tipodiv").style.display = "";

         }
      </script>
      <!--Busca dados da Pasta -->
      <script type="text/javascript" >
         function buscaDados() {
         
          var dado = $('#dados').val();
          var _token = $('input[name="_token"]').val();
      
         
                    $.ajax({
                    url:"{{ route('dynamicdependent.s') }}",
                    type: 'POST',
                    dataType: 'json',
                    data:{dado:dado,_token:_token,},
                    success:function(response){
         
                    if(response.length == 0) {
         
                    document.getElementById('dados').value=('');
                    document.getElementById('id_pasta').value=('');
                    document.getElementById('cpf_cnpj').value=('');
                    document.getElementById('nome').value=('');
                    document.getElementById('codigogrupo').value=('');
                    document.getElementById('grupo').value=('');
                    document.getElementById('cliente').value=('');
                    document.getElementById('clientecodigo').value=('');
                    document.getElementById('codigounidade').value=('');
                    document.getElementById('unidade').value=('');
                    alert('Não foi possível encontrar nenhuma pasta com este código ou número do processo');
         
                    } else {
         
                  $.each(response, function(i, item) {

                      var str2 = "USO EXCLUSIVO";
                      if(item.cliente.indexOf(str2) != -1){
                         $('#alertacontroladoria').addClass('is-visible');
                      } else if(item.pastadescricao.indexOf(str2) != -1){
                        $('#alertacontroladoria').addClass('is-visible');
                      } else {
                      document.getElementById('id_pasta').value=(item.id_pasta);
                      document.getElementById('dados').value=(item.pasta);
                      document.getElementById('cpf_cnpj').value=('');
                      document.getElementById('nome').value=('');
                      document.getElementById('codigogrupo').value=(item.codigogrupo);
                      document.getElementById('grupo').value=(item.grupo);
                      document.getElementById('cliente').value=(item.razao);
                      document.getElementById('clientecodigo').value=(item.clientecodigo);
                      document.getElementById('codigounidade').value=(item.codigounidade);
                     //  document.getElementById('unidade').value=(item.codigounidade + ' - ' + item.unidade);
                      }
         
                   
                  });
                    }
                    }
          
                }); 
         
         };
      </script>
   </body>
</html>
