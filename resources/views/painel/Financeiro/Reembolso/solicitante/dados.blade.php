
<!DOCTYPE html>

<html class="loading" lang="en" data-textdirection="ltr">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="author" content="PL&C">
    <title>Nova solicitação de reembolso | Portal PL&C</title>
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
  padding: 3em 1em;
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

  <body class="vertical-layout vertical-menu-collapsible page-header-dark vertical-modern-menu preload-transitions 2-columns   " data-open="click" data-menu="vertical-modern-menu" data-col="2-columns">

    <header class="page-topbar" id="header">
      <div class="navbar navbar-fixed"> 
        <nav class="navbar-main navbar-color nav-collapsible navbar-dark no-shadow" style="background-color: gray">


          <div class="breadcrumbs-inline pt-3 pb-1" id="breadcrumbs-wrapper" style="margin-top: -40px;">
          <h5 class="breadcrumbs-title mt-0 mb-0 display-inline hide-on-small-and-down"><span style="color: white;">Solicitação de Reembolso</span></h5>
            <ol class="breadcrumbs mb-0">
              <li class="breadcrumb-item"><a href="{{ route('Home.Principal.Show') }}">Home</a>
              </li>
              <li class="breadcrumb-item active" style="color: black;">Nova solicitação
              </li>
            </ol>
        </div>


          <ul class="navbar-list right" style="margin-top: -80px;">
              
          <li class="hide-on-large-only search-input-wrapper"><a class="waves-effect waves-block waves-light search-button" href="javascript:void(0);" style="margin-top: 10px;"><i class="material-icons" style="margin-top: 10px;">search</i></a></li>
              <li><a class="waves-block waves-light notification-button" href="javascript:void(0);" data-target="notifications-dropdown"style="margin-top: 10px;" ><i style="margin-top: 10px;"class="material-icons">notifications_none<small class="notification-badge">{{$totalNotificacaoAbertas}}</small></i></a></li>
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
             <li><a href="{{ route('Painel.PesquisaPatrimonial.financeiro.solicitacoesadiantamento') }}"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Dashboard">Solicitações adiantamento</span></a></li>
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
       <li class="bold"><a class="collapsible-header waves-effect waves-cyan " href="JavaScript:void(0)"><i class="material-icons">person</i><span class="menu-title" data-i18n="Dashboard">Gestão</span></a>
         <div class="collapsible-body">
           <ul class="collapsible collapsible-sub" data-collapsible="accordion">
           
           @can('gestao')
           <li><a href="{{ route('Painel.Gestao.Meritocracia.index') }}" style="font-size: 11px;"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Modern">Meritocracia</span></a></li>
           @endcan

           @can('controlador_gestao')
           <li><a href="{{ route('Painel.Gestao.Controlador.index') }}" style="font-size: 11px;"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Modern">Controle gestão</span></a></li>
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
        <div class="col s12">
          <div class="container">
			
<section class="invoice-edit-wrapper section">

<form role="form" id="form" action="{{ route('Painel.Financeiro.Reembolso.Solicitante.store') }}" method="POST" role="search" enctype="multipart/form-data" >
  {{ csrf_field() }}

  <input type="hidden" name="pasta" id="pasta" value="{{$datas->Codigo_Comp}}">
  <input type="hidden" name="numeroprocesso" id="numeroprocesso" value="{{$datas->NumeroProcesso}}">
  <input type="hidden" name="setor" id="setor" value="{{$datas->SetorCodigo}}">
  <input type="hidden" name="unidade" id="unidade" value="{{$datas->UnidadeCodigo}}">
  <input type="hidden" name="cliente" id="cliente" value="{{$datas->ClienteCodigo}}">
  <input type="hidden" name="prconta" id="prconta" value="{{$datas->PRConta}}">
  <input type="hidden" name="contratocodigo" id="contratocodigo" value="{{$datas->ContratoCodigo}}">
  <input type="hidden" name="statuscontrato" id="statuscontrato" value="">
  <input type="hidden" name="valorpermitido" id="valorpermitido" value="">
  <input type="hidden" name="tipocontrato" id="tipocontrato" value="{{$despesas}}">



  <div id="loadingdiv2" style="display:none;margin-top: 300px; margin-left: 570px;">
    <img style="width: 30px; margin-top: -100px;" src="{{URL::asset('/public/imgs/loading.gif')}}"/>
    <h6 style="font-size: 20px;margin-left:-140px;">Gravando sua solicitação aguarde...</h6>
  </div>

  <div class="row" id="div_all">

    <div class="col xl9 m8 s12">
      <div class="card">
        <div class="card-content px-36">

          <div class="col m4 s4">
            <div class="divider show-on-small hide-on-med-and-up mb-3"></div>
            <h6 class="invoice-to">Dados da pasta</h6>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">Código da pasta:  {{$datas->Codigo_Comp}}</p>
            </div>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">Descrição da pasta: <?php echo mb_convert_case($datas->Descricao, MB_CASE_TITLE, "UTF-8")?></p>
            </div>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">Número do processo: {{$datas->NumeroProcesso}}</p>
            </div>

            <div class="invoice-address" style="font-size: 10px;"> 
            <p style="font-weight: bold;color:black;">Unidade: {{$datas->UnidadeDescricao}}</p>
            </div>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">Setor de custo: {{$datas->SetorDescricao}}</p>
            </div>

            <div class="invoice-address" style="font-size: 10px;"> 
            <p style="font-weight: bold;color:black;">Contrato: {{$datas->ContratoCodigo}}</p>
            </div>

          </div>

          <div class="col m4 s4">
            <div class="divider show-on-small hide-on-med-and-up mb-3"></div>
            <h6 class="invoice-to">Dados do cliente</h6>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">Código: {{$datas->ClienteCodigo}}</p>
            </div>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">Razão Social: <?php echo mb_convert_case($datas->ClienteRazao, MB_CASE_TITLE, "UTF-8")?></p>
            </div>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">Grupo Cliente: <?php echo mb_convert_case($datas->Grupo, MB_CASE_TITLE, "UTF-8")?></p>
            </div>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">Conta Identificadora: </p>
            </div>

            <div class="invoice-address" style="font-size: 10px;">
            <p style="font-weight: bold;color:black;">Moeda: R$ </p>
            </div>

          </div>

           <div class="row invoice-info">
            <div class="col m4 s4">
              <h6 class="invoice-from">Dados do solicitante</h6>
              <div class="invoice-address" style="font-size: 10px;">
              <p style="font-weight: bold;color:black;">Nome: <?php echo mb_convert_case(Auth::user()->name, MB_CASE_TITLE, "UTF-8")?></p>
              </div>

              <div class="invoice-address" style="font-size: 10px;">
              <p style="font-weight: bold;color:black;">E-mail: {{Auth::user()->email}}</p>
              </div>

              <div class="invoice-address" style="font-size: 10px;">
              <p style="font-weight: bold;color:black;">CPF: {{Auth::user()->cpf}}</p>
              </div>

              <!-- <div class="invoice-address" style="font-size: 10px;">
              <p style="font-weight: bold;color:black;">Saldo disponível do adiantamento: R$ <?php echo number_format($valor_disponivel,2,",",".") ?></p>
              </div> -->

            </div>
          </div>
          

    

          <div class="divider mb-3 mt-3"></div>

          <div class="invoice-item display-flex mb-1">
            <div class="invoice-item-filed row pt-1">

            <div class="col m2 s12 input-field">
            <span style="font-size: 11px;">Data da diligência:</span>
            <input style="font-size: 10px;"  name="data" id="data" value="{{$datahoje}}" type="date" min="{{$dataminimo}}" max="{{$datahoje}}" class="form-control" data-toggle="tooltip" data-placement="top" title="Selecione a data do documento." required="required">
            </div>

            <div class="col s12 m3 input-field">
            <span style="font-size: 11px;">Tipo de debite:</span>
              <select class="invoice-item-select browser-default" style="font-size: 10px;" name="tiposervico" id="tiposervico" required>
                  <option value="" selected>Selecione abaixo</option>
                @foreach($tiposservico as $tiposervico)   
                  <option value="{{$tiposervico->Codigo}}">{{$tiposervico->Descricao}}</option>
                @endforeach
              </select>
            </div>


            <div class="col m2 s12 input-field" id="quantidadediv" style="display: none;">
              <span style="font-size: 11px;">Km rodados:</span>
              <input type="text" value="1" id="quantidade" name="quantidade" placeholder="Quantidade KM rodado" required style="font-size:10px;">
            </div>

            <div class="col m2 s12 input-field">
              <span id="labelvalor" name="labelvalor" style="font-size: 11px;">Valor:</span>
              <input type="text" value="00,00" id="valor_unitario" name="valor_unitario" placeholder="Valor únitario..." onKeyPress="return(moeda2(this,'.',',',event))" pattern="(?:\.|,|[0-9])*" style="font-size:10px;"required >
            </div>

            <div class="col m2 s12 input-field">
              <span style="font-size: 11px;">Valor total:</span>
              <input type="text" readonly value="00,00" name="valor_total" id="valor_total" style="font-size:10px;"required >
            </div>

            <div class="row" id="deslocamentodiv" style="display:none;">
            <div class="col m3 s12 input-field">
              <span style="font-size: 11px;">Comarca origem:</span>
              <select class="invoice-item-select browser-default"  id="comarcaorigem" name="comarcaorigem" style="font-size:10px;">
              <option value="" selected>Selecione abaixo</option>
              @foreach($comarcas as $comarca)
              <option value="{{$comarca->municipio}}">{{$comarca->municipio}}</option>
              @endforeach
              </select>
            </div>

            <div class="col m3 s12 input-field">
              <span style="font-size: 11px;">Comarca destino:</span>
              <select class="invoice-item-select browser-default"  id="comarcadestino" name="comarcadestino" style="font-size:10px;">
              @foreach($comarcas as $comarca)
              <option value="{{$comarca->municipio}}">{{$comarca->municipio}}</option>
              @endforeach
              </select>
            </div>
            </div>

            </div>
                
            </div>			  

          <div class="divider mt-3 mb-3"></div>

          <div class="invoice-subtotal">

          <div class="row">
          <div class="input-field col s12" style="margin-top: -15px;">
          <div class="form-group">
          <div class="form-group">
          <label class="control-label" style="font-size: 11px;">Informações:</label>
          <textarea id="observacao" readonly rows="3" type="text" name="observacao" class="form-control" placeholder="Insira a observação abaixo." style="height: 3rem;text-align:left; overflow:auto;font-size: 10px;"></textarea>
          </div>
          </div>
          </div>   
          </div>  

          <div class="row">
          <div class="input-field col s12" style="margin-top: -15px;">
          <div class="form-group">
          <div class="form-group">
          <label class="control-label" style="font-size: 11px;">Informações adicionais:</label>
          <textarea id="observacaoadicionais"  rows="3" type="text" name="observacaoadicionais" class="form-control" placeholder="Insira a observação abaixo." style="height: 3rem;text-align:left; overflow:auto;font-size: 10px;"></textarea>
          </div>
          </div>
          </div>   
          </div>  

          <div class="row">
          <div class="col s12 m12 l12">
            <span style="font-size: 10px;">Selecione o arquivo ou arquivos que você deseja anexar:</span>
            <br><br>
             <input style="font-size: 10px;"  type="file" id="select_file"  name="select_file[]" multiple accept=".jpg,.png,.xls,.xlsx,.pdf,.doc,.docx,.rtf,.jpeg" required class="dropify" data-default-file="" />
          </div>
          </div>


          </div>

        </div>
      </div>
    </div>
    <!-- invoice action  -->


    <div class="col xl3 m4 s12">
      <div class="card invoice-action-wrapper mb-10">
        <div class="card-content">

          <div class="invoice-action-btn">
            <a  name="btnsubmit" id="btnsubmit" onClick="abremodalconfirmacao();" style="background-color: gray;color:white;font-size:11px;" class="btn waves-effect waves-light display-flex align-items-center justify-content-center">
              <i class="material-icons mr-4">save</i>
              <span class="responsive-text">Gravar</span>
            </a>
          </div>

        </div>
      </div>
  </div>

  <div class="row">
      
    <div class="col xl3 m4 s12" style="margin-top: -20px;">
        <div class="card invoice-action-wrapper mb-10">
          <div class="card-content">

            <h6 style="font-size: 14px;" class="card-title">Responsáveis do setor:</h6>
                <ul>
                  @foreach($responsaveis as $responsavel)
                  <li class="display-flex justify-content-between">
                    <span class="invoice-subtotal-title" style="font-size: 10px;color:black;">* <?php echo mb_convert_case($responsavel->name, MB_CASE_TITLE, "UTF-8")?></p>
                  </li>
                  @endforeach
                </ul>
  
          </div>
        </div>
    </div>
      
    <div class="col xl3 m4 s12">

<ul class="collapsible collapsible-accordion">
     <li>
        <div class="collapsible-header" style="font-size: 11px;"><i class="material-icons">list</i> Regras do contrato</div>
        <div class="collapsible-body">
       <table id="scroll-dynamic" class="display">
           <thead>
           <tr>
           <th style="font-size: 11px;color:black">Tipo despesa</th>
           <th style="font-size: 11px;color:black">Status</th>
           </tr>
           </thead>

           <tbody>

           @foreach($contratos as $contrato)
           <tr>
           <td style="font-size: 10px;color:black">{{$contrato->Descricao}}</td>
           @if($contrato->Status == "Permitida")
           <td style="font-size: 10px;color:green">OK para cobrança</td>
           @elseif($contrato->Status == "Nao Cobrar")
           <td style="font-size: 10px;color:red">Bloqueado</td>
           @else 
           <td style="font-size: 10px;color:black;">{{$contrato->Status}}</td>
           @endif
           </tr>
           @endforeach
           </tbody>
           </table>

        </div>

        </div>
        </div>
        </li>
        </ul>
</div>
</div>
<!--Fim Regras do Contrato --> 


  </form>
</section>

    <script src="{{ asset('/public/materialize/js/vendors.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/app-invoice.min.js') }}"></script>
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

<div id="alertaconfirmacao" name="alertaconfirmacao" class="cd-popup" role="alert">
	<div class="cd-popup-container">
		<p style="font-weight: bold;">Deseja gravar a solicitação de reembolso?</p>
		<ul class="cd-buttons">
			<li><a href="#" onClick="nao();" style="font-weight: bold;">Não</a></li>
			<li><a href="#" onClick="sim();" style="font-weight: bold;">Sim</a></li>
		</ul>
		<a onClick="nao();" class="cd-popup-close img-replace">Close</a>
	</div> 
</div>

<div id="alertaduplicidade" name="alertaduplicidade" class="cd-popup" role="alert">
	<div class="cd-popup-container">
		<p style="font-weight: bold;">Encontramos uma solicitação já criada por seu usuário com o mesmo tipo de debite e valor nesta semana. Confirma a nova solicitação de reembolso?</p>
		<ul class="cd-buttons">
			<li><a href="#" onClick="nao();" style="font-weight: bold;">Não</a></li>
			<li><a href="#" onClick="sim();" style="font-weight: bold;">Sim</a></li>
		</ul>
		<a onClick="nao();" class="cd-popup-close img-replace">Close</a>
	</div> 
</div>


<div id="alertacontrato" name="alertacontrato" class="cd-popup" role="alert">
	<div class="cd-popup-container">
		<p style="font-weight: bold;">O tipo de debite selecionado está com o status bloqueado para este contrato. Favor entrar em contato com a equipe do financeiro.</p>
		<ul class="cd-buttons" style="width: 800px">
			<li><a href="#" onClick="nao();" style="font-weight: bold;background-color:#b6bece">Fechar</a></li>
		</ul>
		<a onClick="nao();" class="cd-popup-close img-replace">Close</a>
	</div> 
</div> 

<div id="alertavaloracima" name="alertacontrato" class="cd-popup" role="alert">
	<div class="cd-popup-container">
		<p style="font-weight: bold;">O valor informado deste tipo de serviço é maior que o permitido pelo contrato. Favor entrar em contato com a equipe do financeiro.</p>
		<ul class="cd-buttons" style="width: 800px">
			<li><a href="#" onClick="nao();" style="font-weight: bold;background-color:#b6bece">Fechar</a></li>
		</ul>
		<a onClick="nao();" class="cd-popup-close img-replace">Close</a>
	</div> 
</div> 

<div id="alertacamposfaltantes" name="alertacamposfaltantes" class="cd-popup" role="alert">
	<div class="cd-popup-container">
		<p style="font-weight: bold;">Preencha todos os campos e anexe ao menos um arquivo.</p>
		<ul class="cd-buttons" style="width: 800px">
			<li><a href="#" onClick="nao();" style="font-weight: bold;background-color:#b6bece">Fechar</a></li>
		</ul>
		<a onClick="nao();" class="cd-popup-close img-replace">Close</a>
	</div> 
</div> 


<script>
function abremodalconfirmacao() {

  var observacao = $('#observacao').val();
  var anexo = $('#select_file').val();
  var valor = parseFloat($("#valor_total").val().replace(',', '.'));
  var tiposervico = $("#tiposervico").val();
  var data = $("#data").val();
  var setor = $("#setor").val();
  var _token = $('input[name="_token"]').val();

  $("#btnsubmit").attr("disabled", "disabled");

  if ($('#select_file').val().length != 0){

     //Verifica se já possui uma mesma solicitação de tipo de serviço, valor e semana
     $.ajax({
      type: 'POST',
      url:"{{ route('Painel.Financeiro.Reembolso.Solicitante.buscaduplicidade') }}",
      data:{valor:valor,tiposervico:tiposervico,data:data,setor:setor,_token:_token,},
      dataType: 'json',
      cache: false,
      success: function(response) {

       //Se sim alerta 
       if(response != 0) {
        $('#alertaduplicidade').addClass('is-visible');
        $("#btnsubmit").prop("disabled",false);
       } else {
       //Se não direciona para tela de confirmação
       $('#alertaconfirmacao').addClass('is-visible');
       }
    }
  });
      
  } else {
    $('#alertacamposfaltantes').addClass('is-visible');
    $("#btnsubmit").removeAttr("disabled");
    }
}
</script>



<script>
  function sim() {
    $('.modal').css('display', 'none');
    document.getElementById("loadingdiv2").style.display = "";
    document.getElementById("div_all").style.display = "none";
    $('.cd-popup').removeClass('is-visible');
    document.getElementById("form").submit();
         
  }    
</script>

<script>

 function nao() {
  $('.cd-popup').removeClass('is-visible');
  $("#btnsubmit").removeAttr("disabled");
 }
</script>


<script>
$('#valor_unitario').blur(function(){
  var quantidade = parseFloat($("#quantidade").val().replace(',', '.'));
    var valor_unitario = parseFloat($("#valor_unitario").val().replace('.', '.').replace(',','.'));
    var valor_total = quantidade * valor_unitario;
    var tiposervico = $("#tiposervico").val();
    var tiposervicodescricao = $('#tiposervico option:selected').text();
    var comarcaorigem = $('#comarcaorigem option:selected').text();
    var comarcadestino = $('#comarcadestino option:selected').text();
    var valor_total = valor_total.toFixed(2);

    var valorpermitido = $("#valorpermitido").val();

    //Verifico se o valor é permitido pelo contrato de acordo com o tipo de serviço marcado (Exceto Alimentação)
      // if(valor_total <= valorpermitido || tiposervico == 001) {

        $("#valor_total").val(parseFloat(valor_total).toFixed(2));
      // } else {
      //   $('#alertavaloracima').addClass('is-visible');
      //   $("#valor_total").val('0,00');
      //   $("#valor_unitario").val('0,00');
      // }



if(tiposervico == '011' || tiposervico == '025') {

  document.getElementById('observacao').value=('Outra Parte: ' + "{{$datas->OutraParte}}" + ' Proc.: ' + "{{$datas->NumeroProcesso}}" + ' Comarca/Tribunal/Vara: ' + "{{$datas->Comarca}}"+'/'+"{{$datas->Tribunal}}"+'/'+"{{$datas->Vara}}" + ' Conta identificadora:' + "{{$datas->PRConta}}" + ' Tarefa: ' + tiposervicodescricao + ' Identificador do Cliente: ' + "{{$datas->RefCliente}}" + ' Deslocamento origem: '  + comarcaorigem + ' para a comarca de destino: ' + comarcadestino + ' em km = Total ida e volta: ' + quantidade + ' - ' + quantidade + ' x ' + valor_unitario + ' = R$ ' + valor_total);
  document.getElementById("deslocamentodiv").style.display = "";
  document.getElementById("quantidadediv").style.display = "";
  $('#labelvalor').html('Valor únitario:');
} else {
  
  document.getElementById("deslocamentodiv").style.display = "none";
  document.getElementById("quantidadediv").style.display = "none";
  document.getElementById('observacao').value=('Cliente razão: ' + "{{$datas->ClienteRazao}}" + ' - Grupo cliente: ' + "{{$datas->Grupo}}" + ' - Conta identificadora: ' + "{{$datas->PRConta}}" + ' - Número do processo: ' + "{{$datas->NumeroProcesso}}" + ' - Outra parte: ' + "{{$datas->OutraParte}}" + ' - Solicitante: ' + "{{Auth::user()->name}}" + ' - Data da solicitação: ' +  "{{$dataehora}}" + ' - Tipo de debite: ' + tiposervicodescricao);
  $("#quantidade").val("1");
  $('#labelvalor').html('Valor:');
}
});
</script>

<script>
$('#quantidade').blur(function(){
    var quantidade = parseFloat($("#quantidade").val().replace(',', '.'));
    var valor_unitario = parseFloat($("#valor_unitario").val().replace('.', '.').replace(',','.'));
    var valor_total = quantidade * valor_unitario;
    var valor_total = valor_total.toFixed(2);
    var tiposervico = $("#tiposervico").val();
    var tiposervicodescricao = $('#tiposervico option:selected').text();
    var comarcaorigem = $('#comarcaorigem option:selected').text();
    var comarcadestino = $('#comarcadestino option:selected').text();

    var valorpermitido = $("#valorpermitido").val();

    //Verifico se o valor é permitido pelo contrato de acordo com o tipo de serviço marcado (Exceto Alimentação)
      // if(valor_total <= valorpermitido || tiposervico == 001) {

        $("#valor_total").val(parseFloat(valor_total).toFixed(2));
      // } else {
      //   $('#alertavaloracima').addClass('is-visible');
      //   $("#valor_total").val('0,00');
      //   $("#valor_unitario").val('0,00');
      // }



if(tiposervico == '011' || tiposervico == '025') {

  document.getElementById('observacao').value=('Outra Parte: ' + "{{$datas->OutraParte}}" + ' Proc.: ' + "{{$datas->NumeroProcesso}}" + ' Comarca/Tribunal/Vara: ' + "{{$datas->Comarca}}"+'/'+"{{$datas->Tribunal}}"+'/'+"{{$datas->Vara}}" + ' Conta identificadora:' + "{{$datas->PRConta}}" + ' Tarefa: ' + tiposervicodescricao + ' Identificador do Cliente: ' + "{{$datas->RefCliente}}" + ' Deslocamento origem: '  + comarcaorigem + ' para a comarca de destino: ' + comarcadestino + ' em km = Total ida e volta: ' + quantidade + ' - ' + quantidade + ' x ' + valor_unitario + ' = R$ ' + valor_total);
  document.getElementById("deslocamentodiv").style.display = "";
  document.getElementById("quantidadediv").style.display = "";
  $('#labelvalor').html('Valor únitario:');
} else {
  
  document.getElementById("deslocamentodiv").style.display = "none";
  document.getElementById("quantidadediv").style.display = "none";
  document.getElementById('observacao').value=('Cliente razão: ' + "{{$datas->ClienteRazao}}" + ' - Grupo cliente: ' + "{{$datas->Grupo}}" + ' - Conta identificadora: ' + "{{$datas->PRConta}}" + ' - Número do processo: ' + "{{$datas->NumeroProcesso}}" + ' - Outra parte: ' + "{{$datas->OutraParte}}" + ' - Solicitante: ' + "{{Auth::user()->name}}" + ' - Data da solicitação: ' +  "{{$dataehora}}" + ' - Tipo de debite: ' + tiposervicodescricao);
  $("#quantidade").val("1");
  $('#labelvalor').html('Valor:');
}
});
</script>


 <script>
  $(document).on("onChange", "#valor_unitario", function() {

    var quantidade = parseFloat($("#quantidade").val().replace(',', '.'));
    var valor_unitario = parseFloat($("#valor_unitario").val().replace('.', '.').replace(',','.'));
    var valor_total = quantidade * valor_unitario;
    var valorpermitido = $("#valorpermitido").val();
    var comarcaorigem = $('#comarcaorigem option:selected').text();
    var comarcadestino = $('#comarcadestino option:selected').text();
    var valor_total = valor_total.toFixed(2);


      //Verifico se o valor é permitido pelo contrato de acordo com o tipo de serviço marcado (Exceto Alimentação)
      // if(valor_total <= valorpermitido || tiposervico == 001) {

        $("#valor_total").val(parseFloat(valor_total).toFixed(2));
      // } else {
      //   $('#alertavaloracima').addClass('is-visible');
      //   $("#valor_total").val('0,00');
      //   $("#valor_unitario").val('0,00');
      // }

  if(tiposervico == '011' || tiposervico == '025') {

    document.getElementById('observacao').value=('Outra Parte: ' + "{{$datas->OutraParte}}" + ' Proc.: ' + "{{$datas->NumeroProcesso}}" + ' Comarca/Tribunal/Vara: ' + "{{$datas->Comarca}}"+'/'+"{{$datas->Tribunal}}"+'/'+"{{$datas->Vara}}" + ' Conta identificadora:' + "{{$datas->PRConta}}" + ' Tarefa: ' + tiposervicodescricao + ' Identificador do Cliente: ' + "{{$datas->RefCliente}}" + ' Deslocamento origem: '  + comarcaorigem + ' para a comarca de destino: ' + comarcadestino + ' em km = Total ida e volta: ' + quantidade + ' - ' + quantidade + ' x ' + valor_unitario + ' = R$ ' + valor_total);
    document.getElementById("deslocamentodiv").style.display = "";
    document.getElementById("quantidadediv").style.display = "";
    $('#labelvalor').html('Valor únitario:');
  } else {
    
    document.getElementById("deslocamentodiv").style.display = "none";
    document.getElementById("quantidadediv").style.display = "none";
    document.getElementById('observacao').value=('Cliente razão: ' + "{{$datas->ClienteRazao}}" + ' - Grupo cliente: ' + "{{$datas->Grupo}}" + ' - Conta identificadora: ' + "{{$datas->PRConta}}" + ' - Número do processo: ' + "{{$datas->NumeroProcesso}}" + ' - Outra parte: ' + "{{$datas->OutraParte}}" + ' - Solicitante: ' + "{{Auth::user()->name}}" + ' - Data da solicitação: ' +  "{{$dataehora}}" + ' - Tipo de debite: ' + tiposervicodescricao);
    $("#quantidade").val("1");
    $('#labelvalor').html('Valor:');
  }

  });
 </script>

<script>
  $(document).on("onChange", "#quantidade", function() {

    var quantidade = parseFloat($("#quantidade").val().replace(',', '.'));
    var valor_unitario = parseFloat($("#valor_unitario").val().replace('.', '').replace(',','.'));
    var valor_total = quantidade * valor_unitario;
    var valorpermitido = $("#valorpermitido").val();
    var comarcaorigem = $('#comarcaorigem option:selected').text();
    var comarcadestino = $('#comarcadestino option:selected').text();
    var valor_total = valor_total.toFixed(2)

      //Verifico se o valor é permitido pelo contrato de acordo com o tipo de serviço marcado (Exceto Alimentação)
      // if(valor_total <= valorpermitido || tiposervico == 001) {

        $("#valor_total").val(parseFloat(valor_total).toFixed(2));
      // } else {
      //   $('#alertavaloracima').addClass('is-visible');
      //   $("#valor_total").val('0,00');
      //   $("#valor_unitario").val('0,00');
      // }

  if(tiposervico == '011' || tiposervico == '025') {

    document.getElementById('observacao').value=('Outra Parte: ' + "{{$datas->OutraParte}}" + ' Proc.: ' + "{{$datas->NumeroProcesso}}" + ' Comarca/Tribunal/Vara: ' + "{{$datas->Comarca}}"+'/'+"{{$datas->Tribunal}}"+'/'+"{{$datas->Vara}}" + ' Conta identificadora:' + "{{$datas->PRConta}}" + ' Tarefa: ' + tiposervicodescricao + ' Identificador do Cliente: ' + "{{$datas->RefCliente}}" + ' Deslocamento origem: '  + comarcaorigem + ' para a comarca de destino: ' + comarcadestino + ' em km = Total ida e volta: ' + quantidade + ' - ' + quantidade + ' x ' + valor_unitario + ' = R$ ' + valor_total);
    document.getElementById("deslocamentodiv").style.display = "";
    document.getElementById("quantidadediv").style.display = "";
    $('#labelvalor').html('Valor únitario:');
  } else {
    
    document.getElementById("deslocamentodiv").style.display = "none";
    document.getElementById("quantidadediv").style.display = "none";
    document.getElementById('observacao').value=('Cliente razão: ' + "{{$datas->ClienteRazao}}" + ' - Grupo cliente: ' + "{{$datas->Grupo}}" + ' - Conta identificadora: ' + "{{$datas->PRConta}}" + ' - Número do processo: ' + "{{$datas->NumeroProcesso}}" + ' - Outra parte: ' + "{{$datas->OutraParte}}" + ' - Solicitante: ' + "{{Auth::user()->name}}" + ' - Data da solicitação: ' +  "{{$dataehora}}" + ' - Tipo de debite: ' + tiposervicodescricao);
    $("#quantidade").val("1");
    $('#labelvalor').html('Valor:');
  }

  });
 </script>



<script>
$(document).ready(function(){


    var quantidade = parseFloat($("#quantidade").val().replace(',', '.'));
    var valor_unitario = parseFloat($("#valor_unitario").val().replace('.', '.').replace(',','.'));
    var valor_total = quantidade * valor_unitario;
    var comarcaorigem = $('#comarcaorigem option:selected').text();
    var comarcadestino = $('#comarcadestino option:selected').text();
    var valor_total = valor_total.toFixed(2)

    $("#valor_total").val(parseFloat(valor_total).toFixed(2));

    var tiposervico = $("#tiposervico").val();

    var tiposervicodescricao = $('#tiposervico option:selected').text();

  if(tiposervico == '011' || tiposervico == '025') {

    document.getElementById('observacao').value=('Outra Parte: ' + "{{$datas->OutraParte}}" + ' Proc.: ' + "{{$datas->NumeroProcesso}}" + ' Comarca/Tribunal/Vara: ' + "{{$datas->Comarca}}"+'/'+"{{$datas->Tribunal}}"+'/'+"{{$datas->Vara}}" + ' Conta identificadora:' + "{{$datas->PRConta}}" + ' Tarefa: ' + tiposervicodescricao + ' Identificador do Cliente: ' + "{{$datas->RefCliente}}" + ' Deslocamento origem: '  + comarcaorigem + ' para a comarca de destino: ' + comarcadestino + ' em km = Total ida e volta: ' + quantidade + ' - ' + quantidade + ' x ' + valor_unitario + ' = R$ ' + valor_total);
    document.getElementById("deslocamentodiv").style.display = "";
    $('#labelvalor').html('Valor únitario:');
  } else {
    
    document.getElementById("deslocamentodiv").style.display = "none";
    document.getElementById('observacao').value=('Cliente razão: ' + "{{$datas->ClienteRazao}}" + ' - Grupo cliente: ' + "{{$datas->Grupo}}" + ' - Conta identificadora: ' + "{{$datas->PRConta}}" + ' - Número do processo: ' + "{{$datas->NumeroProcesso}}" + ' - Outra parte: ' + "{{$datas->OutraParte}}" + ' - Solicitante: ' + "{{Auth::user()->name}}" + ' - Data da solicitação: ' +  "{{$dataehora}}" + ' - Tipo de debite: ' + tiposervicodescricao);
    $("#quantidade").val("1");
    $('#labelvalor').html('Valor:');
  }

  });
 </script>

<script>
$('#comarcadestino').on('change', function() {

  
    var quantidade = parseFloat($("#quantidade").val().replace(',', '.'));
    var valor_unitario = parseFloat($("#valor_unitario").val().replace('.', '.').replace(',','.'));
    var valor_total = quantidade * valor_unitario;
    var comarcaorigem = $('#comarcaorigem option:selected').text();
    var comarcadestino = $('#comarcadestino option:selected').text();
    var valor_total = valor_total.toFixed(2)
    var tiposervicodescricao = $('#tiposervico option:selected').text();

    document.getElementById('observacao').value=('Outra Parte: ' + "{{$datas->OutraParte}}" + ' Proc.: ' + "{{$datas->NumeroProcesso}}" + ' Comarca/Tribunal/Vara: ' + "{{$datas->Comarca}}"+'/'+"{{$datas->Tribunal}}"+'/'+"{{$datas->Vara}}" + ' Conta identificadora:' + "{{$datas->PRConta}}" + ' Tarefa: ' + tiposervicodescricao + ' Identificador do Cliente: ' + "{{$datas->RefCliente}}" + ' Deslocamento origem: '  + comarcaorigem + ' para a comarca de destino: ' + comarcadestino + ' em km = Total ida e volta: ' + quantidade + ' - ' + quantidade + ' x ' + valor_unitario + ' = R$ ' + valor_total);

});

</script>

<script>
$('#comarcaorigem').on('change', function() {

  
    var quantidade = parseFloat($("#quantidade").val().replace(',', '.'));
    var valor_unitario = parseFloat($("#valor_unitario").val().replace('.', '.').replace(',','.'));
    var valor_total = quantidade * valor_unitario;
    var comarcaorigem = $('#comarcaorigem option:selected').text();
    var comarcadestino = $('#comarcadestino option:selected').text();
    var valor_total = valor_total.toFixed(2)
    var tiposervicodescricao = $('#tiposervico option:selected').text();

    document.getElementById('observacao').value=('Outra Parte: ' + "{{$datas->OutraParte}}" + ' Proc.: ' + "{{$datas->NumeroProcesso}}" + ' Comarca/Tribunal/Vara: ' + "{{$datas->Comarca}}"+'/'+"{{$datas->Tribunal}}"+'/'+"{{$datas->Vara}}" + ' Conta identificadora:' + "{{$datas->PRConta}}" + ' Tarefa: ' + tiposervicodescricao + ' Identificador do Cliente: ' + "{{$datas->RefCliente}}" + ' Deslocamento origem: '  + comarcaorigem + ' para a comarca de destino: ' + comarcadestino + ' em km = Total ida e volta: ' + quantidade + ' - ' + quantidade + ' x ' + valor_unitario + ' = R$ ' + valor_total);

});

 </script>

<script>
$('#tiposervico').on('change', function() {

  var tiposervico = $("#tiposervico").val();
  var quantidade = $("#quantidade").val();
  var valorunitario = $("#valor_unitario").val();
  var valortotal = $("#valor_total").val();
  var codigocontrato = $("#contratocodigo").val();

  var tiposervicodescricao = $('#tiposervico option:selected').text();
  var _token = $('input[name="_token"]').val();
  var comarcaorigem = $('#comarcaorigem option:selected').text();
  var comarcadestino = $('#comarcadestino option:selected').text();


    //Se for despesas livres ele ignora o processo de verificação das regras do contrato
  if("{{$statuscontrato}}" != "Despesas livres") {

    $.ajax({
      type: 'POST',
      url:"{{ route('Painel.Financeiro.Reembolso.Solicitante.buscadadoscontrato') }}",
      data:{tiposervico:tiposervico,codigocontrato:codigocontrato,_token:_token,},
      dataType: 'json',
      cache: false,
      success: function(response) {

        var valorcliente = Number(response[0].Valor).toFixed(2);   
        var statuscontrato = response[0].Aut_Desp;

        if(statuscontrato == "Permitida") {

          $('#statuscontrato').val('Reembolsável pelo cliente')

          //Coloca o valor máximo permitido para este tipo de serviço de acordo com o contrato
          $('#valorpermitido').val(valorcliente)

          //Habilita o btn e o campo valor
          document.getElementById("btnsubmit").style.display = "";

        } else if(statuscontrato == "Bloqueada") {
          $('#alertacontrato').addClass('is-visible');

          //Desabilita o btn
          document.getElementById("btnsubmit").style.display = "none";

        } else {
          $('#statuscontrato').val('Não reembolsável');

          //Coloca o valor máximo permitido para este tipo de serviço de acordo com o contrato
          $('#valorpermitido').val(valorcliente);

          //Habilita o btn e o campo valor
          document.getElementById("btnsubmit").style.display = "";

        }

      }
  });

  }
  //Se for despesas livres
  else {

    $("#statuscontrato").val("Reembolsável pelo cliente");

    //Habilita o btn
    $("#btnsubmit").prop("disabled",false);

  }


  if(tiposervico == '011' || tiposervico == '025') {

    document.getElementById('observacao').value=('Outra Parte: ' + "{{$datas->OutraParte}}" + ' Proc.: ' + "{{$datas->NumeroProcesso}}" + ' Comarca/Tribunal/Vara: ' + "{{$datas->Comarca}}"+'/'+"{{$datas->Tribunal}}"+'/'+"{{$datas->Vara}}" + ' Conta identificadora:' + "{{$datas->PRConta}}" + ' Tarefa: ' + tiposervicodescricao + ' Identificador do Cliente: ' + "{{$datas->RefCliente}}" + ' Deslocamento origem: '  + comarcaorigem + ' para a comarca de destino: ' + comarcadestino + ' em km = Total ida e volta: ' + quantidade + ' - ' + quantidade + ' x ' + valor_unitario + ' = R$ ' + valor_total);
    document.getElementById("deslocamentodiv").style.display = "";
    document.getElementById("quantidadediv").style.display = "";


    $("#valor_unitario").val(parseFloat({{$valor_unitario}}).toFixed(2));

    $('#labelvalor').html('Valor únitario:');
  } else {
    
    document.getElementById("deslocamentodiv").style.display = "none";
    document.getElementById("quantidadediv").style.display = "none";
    document.getElementById('observacao').value=('Cliente razão: ' + "{{$datas->ClienteRazao}}" + ' - Grupo cliente: ' + "{{$datas->Grupo}}" + ' - Conta identificadora: ' + "{{$datas->PRConta}}" + ' - Número do processo: ' + "{{$datas->NumeroProcesso}}" + ' - Outra parte: ' + "{{$datas->OutraParte}}" + ' - Solicitante: ' + "{{Auth::user()->name}}" + ' - Data da solicitação: ' +  "{{$dataehora}}" + ' - Tipo de debite: ' + tiposervicodescricao);
    $("#quantidade").val("1");
    $('#labelvalor').html('Valor:');
  }

});
</script>



  </body>
</html>