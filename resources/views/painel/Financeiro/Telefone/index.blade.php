<!DOCTYPE html>

<html class="loading" lang="pt-br" data-textdirection="ltr">
  <head>
    <meta http-equiv="Content-Language" content="pt-br">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="author" content="Portal PL&C">
    <title>Telefones corporativos | Portal PL&C</title>
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
      
<div class="breadcrumbs-inline pt-3 pb-1" id="breadcrumbs-wrapper" style="margin-top: -45px;">
  <h5 class="breadcrumbs-title mt-0 mb-0 display-inline hide-on-small-and-down"><span style="color: white;">Telefones corporativos</span></h5>
  <ol class="breadcrumbs mb-0">
    <li class="breadcrumb-item"><a href="{{ route('Home.Principal.Show') }}">Home</a>
    </li>
    <li class="breadcrumb-item active" style="color: black;">Listagem números
    </li>
  </ol>
</div>

<ul class="navbar-list right" style="margin-top: -70px;">
              
              <li class="hide-on-large-only search-input-wrapper"><a class="waves-effect waves-block waves-light search-button" href="javascript:void(0);"><i class="material-icons">search</i></a></li>
              <li><a class="waves-block waves-light notification-button" style="margin-top: 10px;" href="javascript:void(0);" data-target="notifications-dropdown"><i class="material-icons" style="margin-top: 10px;">notifications_none<small class="notification-badge">{{$totalNotificacaoAbertas}}</small></i></a></li>
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
        <div class="content-wrapper-before blue-grey lighten-5"></div>


        <div class="col s12" id="corpodiv">

          <div class="container">
            <div class="section">

<section class="invoice-list-wrapper section">

  <div class="invoice-filter-action mr-3">

    <a href="{{ route('Painel.Financeiro.Telefones.gerarExcelTelefone')}}" class="btn waves-effect waves-light invoice-export border-round z-depth-4" style="background-color: gray">
      <i class="material-icons">picture_as_pdf</i>
      <span class="hide-on-small-only">Exportar</span>
    </a>

    <a href="#modalAdicionar" class="btn waves-effect waves-light invoice-export border-round z-depth-4 modal-trigger" style="background-color: gray">
      <i class="material-icons">add</i>
      <span class="hide-on-small-only">Adicionar</span>
    </a>

  </div>

  @foreach($datas as $data) 
  <form id="form" class="login-form" role="form" action="{{ route('Painel.Financeiro.Telefones.salvarNovoTelefone')}}" method="GET">
    {{ csrf_field() }}
  <div id="modalAdicionar" class="modal">
    <div class="modal-content">

    <h6>Inserir Telefone</h6>
    
      <li class="divider"></li>

      <br>
      
    <div class="row">
      <div class="input-field col s4">
      <span>Número:</span>
      <input id="numero" name="numero" type="text">
      </div>

      <div class="input-field col s4">
      <span>Selecione o responsável:</span>
      <select class="browser-default" name="responsavel" id="responsavel">
      <option value="{{$data->user_id}}" selected>{{$data->user_id}}</option>
      @foreach ($users as $user)
      <option value="{{$user->name}}">{{$user->name}}</option>
       @endforeach
      </select>
      </div>

      <div class="input-field col s4">
         <span>Operadora:</span>
        <input id="operadora" name="operadora" type="text">
      </div>
    </div>
    <div class="row">

      <div class="input-field col s4">
      <span>Selecione a unidade:</span>
      <select class="browser-default" name="unidade" id="unidade">
          @foreach ($unidades as $u)
            <option value="{{$u->codigo}}">{{$u->descricao}}</option>
          @endforeach
        </select>
      </div>

      <div class="input-field col s4">
        <span>Informe a conta:</span>
        <input id="conta" name="conta" type="text">
      </div>

      <div class="input-field col s4" style="margin-top: 12px;">
        <span>Status:</span>
        <p>
          <label>
            <input class="with-gap" name="ativoinativo" type="radio" id="ativo" value="Ativo"/>
            <span>Ativo</span>
          </label> 
          <label>
            <input class="with-gap" name="ativoinativo" type="radio" id="inativo" value="Inativo"/>
            <span>Inativo</span>
          </label>
        </p>
      </div>
    </div>

    <div class="row">
      <div class="input-field col s4">
      <span>Data criação:</span>
      <input id="datacriacao" name="datacriacao" type="date" max="{{$datahojeformato}}">
      </div>

      <div class="input-field col s4">
      <span>Modelo:</span>
      <input id="modelo" name="modelo" type="text">
      </div>

      <div class="input-field col s4">
        <span>Data entrega:</span>
        <input id="dataentrega" name="dataentrega" type="date" max="{{$datahojeformato}}">
      </div>
    </div>

    <div class="row">
      <div class="input-field col s12">
        <span>Observações:</span>
        <input id="observacoes" name="observacoes" type="text">
      </div>
    </div>
    </div>

    <div class="modal-footer">
      <button class="btn waves-effect waves-light green" style="color:white;" type="submit"><i class="material-icons right">done</i>Confirmar</button>
    </div>
  </div>
</form>
@endforeach


  <div class="responsive-table">
    <table class="table invoice-data-table white border-radius-4 pt-1">
      <thead>
        <tr>
          <th style="font-size: 12px"></th>
          <th style="font-size: 12px"><span>#</span></th>
          <th style="font-size: 12px">Número</th>
          <th style="font-size: 12px">Responsável</th>
          <th style="font-size: 12px">Operadora</th>
          <th style="font-size: 12px">Unidade</th>
          <th style="font-size: 12px">Conta</th>
          <th style="font-size: 12px">Status</th>
          <th style="font-size: 12px">Data criação</th>
          <th style="font-size: 12px">Modelo</th>
          <th style="font-size: 12px">Observações</th>
          <th style="font-size: 12px">Data entrega</th>
          <th style="font-size: 12px"></th>
        </tr>
      </thead>

      <tbody>
      @foreach($datas as $data)  
        <tr>


<!--Modal desativar -->
<div id="modalcancelar{{$data->id}}" class="modal" style="width: 420px; height: 230px; overflow-x: hidden;overflow-y hidden;">
  <div class="modal-content">

    <form id="form" role="form" action="{{route('Painel.Financeiro.Telefones.desativarnumero', $data->id)}}" method="GET" role="create" >
    {{ csrf_field() }}

      <input type="hidden" name="id" id="id" value="{{$data->id}}">

      <button type="button" class="btn waves-effect mr-sm-1 mr-2 modal-close red" style="margin-left: 315px; margin-top: -32px;">
        <i class="material-icons">close</i> 
      </button>
  
    <center>
  <div id="loadingdiv2" style="display:none">
      <div class="wrapper">
      <div class="circle circle-1"></div>
      <div class="circle circle-1a"></div>
      <div class="circle circle-2"></div>
      <div class="circle circle-3"></div>
     </div>
     <h1 style="text-align: center;">Aguarde, enquanto desativamos este registro...&hellip;</h1>
     </div>
  </center>   

      <div id="corpodiv2" style="margin-top: 15px;">
        <center>
        <h6 style="font-size: 15px;">Você está desativando o número - <b>{{$data->telefone}}</b></h6>
          <p>Deseja continuar?</p>
        </center>
      </div>
    
    <div class="modal-footer">
      <button type="submit" id="btnsubmit" onClick="envia();" class="modal-action waves-effect waves-green btn-flat"
       style="background-color: green;color:white; margin-top: 55px; margin-right: -25px;"><i class="material-icons left">check</i>SIM</button>
    </div>

    </form>
  </div>
  </div>
<!--Fim Modal -->




          <td style="font-size: 11px"></td>
          <td style="font-size: 11px">{{ $data->id }}</td>
          <td style="font-size: 11px">{{ $data->telefone }}</td>
          <td style="font-size: 11px">{{ $data->user_id }}</td>
          <td style="font-size: 11px">{{ $data->operadora}}</td>
          <td style="font-size: 11px">{{ $data->unidade_descricao}}</td>
          <td style="font-size: 11px">{{ $data->conta}}</td>
          @if($data->status == "Ativo")
          <td style="font-size: 11px"><span class="bullet green"></span> Ativo</td>
          @else 
          <td style="font-size: 11px"><span class="bullet red"></span>Inativo</td>
          @endif
          <td style="font-size: 11px">{{ date('d/m/Y', strtotime($data->data)) }}</td>

          <td style="font-size: 11px">{{$data->modelo}}</td>
          <td style="font-size: 11px">{{$data->observacoes}}</td>
          <td style="font-size: 11px">{{ date('d/m/Y', strtotime($data->dataentrega))}} </td>

          <td style="font-size: 11px">
          <div class="invoice-action">
          <a href="#modaleditar{{$data->id}}" class="invoice-action-view mr-4 modal-trigger tooltipped" data-position="top" data-tooltip="Clique aqui para editar este registro. "><i class="material-icons">edit</i></a>
          <a href="#modalcancelar{{$data->id}}" class="invoice-action-view mr-4 modal-trigger tooltipped" data-position="top" data-tooltip="Clique aqui para desativar este registro. "><i class="material-icons">close</i></a>
          </div>

          </td>

        </tr>
        @endforeach
        
        
      </tbody>
    </table>
  </div>

  @foreach($datas as $data) 
  <form id="form" class="login-form" role="form" action="{{ route('Painel.Financeiro.Telefones.salvarEdicaoTelefones')}}" method="GET">
    {{ csrf_field() }}
  <input type="hidden" value="{{$data->id}}" name="idTelefone">
  <div id="modaleditar{{$data->id}}" class="modal">
    <div class="modal-content">

    <h6>Editar Dados</h6>
    
      <li class="divider"></li>

      <br>
      
    <div class="row">
      <div class="input-field col s4">
      <span>Número:</span>
      <input id="numero" name="numero" type="text" value="{{$data->telefone}}">
      </div>

      <div class="input-field col s4">
      <span>Selecione o responsável:</span>
      <select class="browser-default" name="responsavel" id="responsavel">
      <option value="{{$data->user_id}}" selected>{{$data->user_id}}</option>
      @foreach ($users as $user)
      <option value="{{$user->name}}">{{$user->name}}</option>
       @endforeach
      </select>
      </div>

      <div class="input-field col s4">
         <span>Operadora:</span>
        <input id="operadora" name="operadora" type="text" value="{{$data->operadora}}">
      </div>
    </div>
    <div class="row">

      <div class="input-field col s4">
      <span>Selecione a unidade:</span>
      <select class="browser-default" name="unidade" id="unidade">
            <option value="{{$data->unidade_codigo}}" selected>{{$data->unidade_descricao}}</option>
          @foreach ($unidades as $u)
            <option value="{{$u->codigo}}">{{$u->descricao}}</option>
          @endforeach
        </select>
      </div>

      <div class="input-field col s4">
        <span>Informe a conta:</span>
        <input id="conta" name="conta" type="text" value="{{$data->conta}}">
      </div>

      <div class="input-field col s4" style="margin-top: 12px;">
        <span>Status:</span>
        <p>
          @if ($data->status == "Ativo")
            <label>
              <input class="with-gap" name="ativoinativo" type="radio" id="ativo" value="Ativo" checked/>
              <span>Ativo</span>
            </label>
            <label>
              <input class="with-gap" name="ativoinativo" type="radio" id="inativo" value="Inativo"/>
              <span>Inativo</span>
            </label>
          @else 
          <label>
            <input class="with-gap" name="ativoinativo" type="radio" id="ativo" value="Ativo"/>
            <span>Ativo</span>
          </label> 
          <label>
            <input class="with-gap" name="ativoinativo" type="radio" id="inativo" value="Inativo" checked/>
            <span>Inativo</span>
          </label>
          @endif
        </p>
      </div>
    </div>

    <div class="row">
      <div class="input-field col s4">
      <span>Data criação:</span>
      <input id="datacriacao" name="datacriacao" type="date" max="{{$datahojeformato}}" value="{{date('Y-m-d', strtotime($data->data))}}">
      </div>

      <div class="input-field col s4">
      <span>Modelo:</span>
      <input id="modelo" name="modelo" type="text" value="{{$data->modelo}}">
      </div>

      <div class="input-field col s4">
        <span>Data entrega:</span>
        <input id="dataentrega" name="dataentrega" type="date" max="{{$datahojeformato}}" value="{{$data->dataentrega}}">
      </div>
    </div>

    <div class="row">
      <div class="input-field col s12">
        <span>Observações:</span>
        <input id="observacoes" name="observacoes" type="text" value="{{$data->observacoes}}">
      </div>
    </div>
    </div>

    <div class="modal-footer">
      <button class="btn waves-effect waves-light green" style="color:white;" type="submit"><i class="material-icons right">done</i>Confirmar</button>
    </div>
  </div>

  </form>
  @endforeach

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


<script>
$(document).ready(function(){
   $('.modal').modal();
   
});
</script>


<script>
function envia() {

    document.getElementById("loadingdiv2").style.display = "";
    document.getElementById("corpodiv2").style.display = "none";
    document.getElementById("form").submit();
}    
</script>



  </body>
</html>