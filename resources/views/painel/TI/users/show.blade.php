
<!DOCTYPE html>

<html class="loading" lang="en" data-textdirection="ltr">
<head>
    <meta http-equiv="Content-Language" content="pt-br">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="author" content="Portal PL&C">
    <title>Configurações do usuário - T.I</title>
    <link rel="apple-touch-icon" href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/images/favicon/apple-touch-icon-152x152.png">
    <link rel="shortcut icon" href="{{ asset('/public/assets/imgs/casinha.png')}}" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">


    <link rel="stylesheet" href="{{ asset('/public/materialize/css/vendors.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/materialize.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/page-account-settings.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/select2-materialize.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/select2.min.css') }}">


    </head>

<body class="vertical-layout vertical-menu-collapsible page-header-dark vertical-modern-menu preload-transitions 2-columns   " data-open="click" data-menu="vertical-modern-menu" data-col="2-columns">

  <header class="page-topbar" id="header">
    <div class="navbar navbar-fixed"> 
      <nav class="navbar-main navbar-color nav-collapsible navbar-dark no-shadow" style="background-color: gray">
        <div class="nav-wrapper">
          <ul class="navbar-list right">
            
            <li class="hide-on-large-only search-input-wrapper"><a class="waves-effect waves-block waves-light search-button" href="javascript:void(0);"><i class="material-icons">search</i></a></li>
            <li><a class="waves-block waves-light notification-button" href="javascript:void(0);" data-target="notifications-dropdown"><i class="material-icons">notifications_none<small class="notification-badge">{{$totalNotificacaoAbertas}}</small></i></a></li>
            <li><a class="waves-effect waves-block waves-light profile-button" href="javascript:void(0);" data-target="profile-dropdown"><span class="avatar-status avatar-online"><img src="https://pixinvent.com/materialize-material-design-admin-template/app-assets/images/avatar/avatar-7.png" alt="avatar"><i></i></span></a></li>
          </ul>

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

           @can('coordenador')  
           <li><a href="{{ route('Painel.Financeiro.faturamentoSemanal') }}" style="font-size: 11px;"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Modern">Faturamento Semanal</span></a></li>
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
        <div class="breadcrumbs-inline pt-3 pb-1" id="breadcrumbs-wrapper">

          <div class="container">
            <div class="row">
              <div class="col s10 m6 l6 breadcrumbs-left">
                <h5 class="breadcrumbs-title mt-0 mb-0 display-inline hide-on-small-and-down"><span>Configurações do usuário</span></h5>
                <ol class="breadcrumbs mb-0">
                  <li class="breadcrumb-item"><a href="{{ route('Home.Principal.Show') }}">Home</a>
                  </li>
                  <li class="breadcrumb-item"><a href="{{ route('Painel.TI.users.index') }}">Listagem Usuários</a>
                  </li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <div class="col s12">
          <div class="container">

            <!-- Account settings -->
<section class="tabs-vertical mt-1 section">
  <div class="row">
    <div class="col l4 s12">

      <div class="card-panel">
        <ul class="tabs">

          <li class="tab">
            <a href="#general">
              <i class="material-icons">brightness_low</i>
              <span>Geral</span>
            </a>
          </li>

          <li class="tab">
            <a href="#change-password">
              <i class="material-icons">lock_open</i>
              <span>Alterar senha</span>
            </a>
          </li>

          <li class="tab">
            <a href="#info">
              <i class="material-icons">error_outline</i>
              <span> Informações</span>
            </a>
          </li>

          <li class="tab">
            <a href="#social-link">
              <i class="material-icons">person</i>
              <span>Redes Sociais</span>
            </a>
          </li>

          <li class="tab">
            <a href="#connections">
              <i class="material-icons">link</i>
              <span>Perfis</span>
            </a>
          </li>
          
          <li class="tab">
            <a href="#notifications">
              <i class="material-icons">notifications_none</i>
              <span> Notificações</span>
            </a>
          </li>

        </ul>
      </div>
    </div>

    <div class="col l8 s12">
      <div id="general">
        <div class="card-panel">
          <div class="display-flex">
            <div class="media">
              <img src="{{ asset('/public/AdminLTE/img/Avatar/img.png') }}" class="border-radius-4" alt="profile image"
                height="64" width="64">
            </div>
            <div class="media-body">
              <div class="general-action-btn">
                <button id="select-files" class="btn indigo mr-2">
                  <span>Alterar foto</span>
                </button>
              </div>
              <small>Permitido apenas: JPG, GIF or PNG. Tamanho máximo 800kB</small>
              <div class="upfilewrapper">
                <input id="upfile" type="file" />
              </div>
            </div>
          </div>


          <div class="divider mb-1 mt-1"></div>
          <form role="form" id="form" class="formValidate" onsubmit="btnsubmit.disabled = true; return true;" action="{{ route('Painel.TI.usuario.alterarstatus') }}" method="POST" role="search"  enctype="multipart/form-data">
         {{ csrf_field() }}            
            <div class="row">

            <input type="hidden" id="id" name="id" value="{{$user->id}}">

              <div class="col s12">
                <div class="input-field">
                  <label for="nome">Nome:</label>
                  <input type="text" id="nome" name="nome" value="{{$user->name}}" readonly data-error=".errorTxt1">
                </div>
              </div>

              <div class="col s12">
                <div class="input-field">
                  <label for="email">E-mail:</label>
                  <input id="email" type="email" name="email" value="{{$user->email}}" readonly data-error=".errorTxt3">
                </div>
              </div>

              <div class="col s12">
                <div class="input-field">
                  <input id="codigo" name="codigo" value="{{$user->cpf}}" readonly required type="text">
                  <label for="codigo">CPF/CNPJ:</label>
                </div>
              </div>

              <div class="col s12">
                <div class="input-field">
                  <input id="codigo" name="data_criacao" value="{{$user->data_criacao}}"  max="{{$datahoje}}" type="date">
                  <label for="codigo">Data criação:</label>
                </div>
              </div>

              <div class="col s12">
                <div class="input-field">
                  <input id="codigo" name="data_desativacao" value="{{$user->data_desativacao}}" max="{{$datahoje}}"  type="date">
                  <label for="codigo">Data desativação:</label>
                </div>
              </div>

              <div class="input-field col s12">
              <select class="select2-customize-result browser-default" name="status">
              @if($user->status == "Ativo")
              <option value="Ativo" selected>Ativo</option>
              <option value="Inativo">Inativo</option>
              @else 
              <option value="Ativo">Ativo</option>
              <option value="Inativo" selected>Inativo</option>
              @endif
              </select>
              <label>Status atual:</label>
              </div>

              <div class="col s12 display-flex justify-content-end form-action">
                <button type="submit" id="btnsubmit" class="btn waves-effect waves-light mr-2" style="background-color: gray">
                  Salvar alterações
                </button>

                <button type="button" class="btn btn-light-pink waves-effect waves-light mb-1">Cancelar</button>
              </div>

            </div>
          </form>
        </div>
      </div>

      <div id="change-password">
        <div class="card-panel">
     <form role="form" id="form" class="formValidate" onsubmit="btnsubmit.disabled = true; return true;" action="{{ route('Painel.TI.usuario.alterarsenha') }}" method="POST" role="search"  enctype="multipart/form-data">
         {{ csrf_field() }}   

         <input type="hidden" id="id" name="id" value="{{$user->id}}">
         <input type="hidden" id="name" name="name" value="{{$user->name}}">
         <input type="hidden" id="email" name="email" value="{{$user->email}}">

           <div class="row">

              <div class="col s12">
                <div class="input-field">
                  <input id="senhaatual" name="senhaatual" type="password" value="{{$user->password}}" data-error=".errorTxt4">
                  <label for="senhaatual">Senha atual:</label>
                  <small class="errorTxt4"></small>
                </div>
              </div>

              <div class="col s12">
                <div class="input-field">
                  <input id="novasenha" name="novasenha" type="password" required data-error=".errorTxt5">
                  <label for="novasenha">Nova senha:</label>
                  <small class="errorTxt5"></small>
                </div>
              </div>

              <div class="col s12">
                <div class="input-field">
                  <input id="repswd" type="password" name="repswd" required data-error=".errorTxt6">
                  <label for="repswd">Repita a nova senha:</label>
                  <small class="errorTxt6"></small>
                </div>
              </div>

              <div class="col s12 display-flex justify-content-end form-action">
              <button type="submit" id="btnsubmit" class="btn indigo waves-effect waves-light mr-2">
                  Salvar alterações
                </button>
              <button type="reset" class="btn btn-light-pink waves-effect waves-light">Cancelar</button>
              </div>

            </div>
          </form>
        </div>
      </div>


      <!--Redes Sociais -->
      <!-- <div id="social-link">
        <div class="card-panel">
          <form>
            <div class="row">

              <div class="col s12">
                <div class="input-field">
                  <input id="twitter" name="twitter" type="text" class="validate" placeholder="Add link"
                    value="{{$user->twitter}}">
                  <label for="twitter">Twitter</label>
                </div>
              </div>

              <div class="col s12">
                <div class="input-field">
                  <input id="facebook" name="facebook" value="{{$user->facebook}}" type="text" class="validate" placeholder="Add link">
                  <label for="facebook">Facebook</label>
                </div>
              </div>

              <div class="col s12">
                <div class="input-field">
                  <input id="google+link" type="text" class="validate" disabled placeholder="Add link">
                  <label for="google+link">Google+</label>
                </div>
              </div>

              <div class="col s12">
                <div class="input-field">
                  <input id="linkedin" type="text" class="validate" disabled placeholder="Add link"
                    value="https://www.linkedin.com">
                  <label for="linkedin">LinkedIn</label>
                </div>
              </div>

              <div class="col s12">
                <div class="input-field">
                  <input id="instragram-link" type="text" disabled class="validate" placeholder="Add link">
                  <label for="instragram-link">Instagram</label>
                </div>
              </div>


              <div class="col s12 display-flex justify-content-end form-action">
                <button type="submit" class="btn indigo waves-effect waves-light mr-2">Salvar alterações</button>
                <button type="button" class="btn btn-light-pink waves-effect waves-light">Cancelar</button>
              </div>
            </div>
          </form>
        </div>
      </div> -->
      <!--Fim Redes Sociais -->

      <!--Perfis -->
      <!-- <div id="connections">
        <div class="card-panel">
          <div class="row">
            <div class="col s12 mt-1 mb-1">
              <a href="javascript: void(0);" class="btn cyan waves-effect waves-light">
                Connect to <strong>Twitter</strong>
              </a>
            </div>
            <div class="col s12 mt-1 mb-1">
              <button class="btn btn-small waves-effect waves-light btn-light-indigo float-right">edit</button>
              <h6>You are connected to facebook.</h6>
              <p>Johndoe@gmail.com</p>
            </div>
            <div class="col s12 mt-1 mb-1">
              <a href="javascript: void(0);" class="btn waves-effect waves-light">Connect to
                <strong>Google</strong>
              </a>
            </div>
            <div class="col s12 mt-1 mb-1">
              <button class="btn btn-small btn-light-indigo float-right waves-effect waves-light">edit</button>
              <h6>You are connected to Instagram.</h6>
              <p>Johndoe@gmail.com</p>
            </div>
            <div class="col s12 display-flex justify-content-end form-action">
              <button type="submit" class="btn indigo waves-effect waves-light mr-2">Save
                changes</button>
              <button type="button" class="btn btn-light-pink waves-effect waves-light">Cancel</button>
            </div>
          </div>
        </div>
      </div> -->
      <!--Fim Perfis -->

      <!-- <div id="notifications">
        <div class="card-panel">
          <div class="row">
            <h6 class="col s12 mb-2">Atividades</h6>

            <div class="col s12 mb-1">
              <div class="switch">
                <label>
                  <input type="checkbox" checked id="accountSwitch1">
                  <span class="lever"></span>
                </label>
                <span class="switch-label w-100">Receber notificações no modúlo Correspondente</span>
              </div>
            </div>

            <div class="col s12 mb-1">
              <div class="switch">
                <label>
                  <input type="checkbox" checked id="accountSwitch2">
                  <span class="lever"></span>
                </label>
                <span class="switch-label w-100">
                 Receber notificações no modúlo Marketing</span>
              </div>
            </div>

    
            <div class="col s12 display-flex justify-content-end form-action mt-2">
              <button type="submit" class="btn indigo waves-light waves-effect mr-sm-1 mr-2">Salvar alterações</button>
              <button type="button" class="btn btn-light-pink waves-light waves-effect">Cancelar</button>
            </div>

          </div> -->
        </div> -
      </div>
    </div>
  </div>
</section>


 
          </div>
          <div class="content-overlay"></div>
        </div>
      </div>
    </div>
    <!-- END: Page Main-->


    <script src="{{ asset('/public/materialize/js/vendors.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/page-account-settings.min.js') }}"></script>
    <script src="https://pixinvent.com/materialize-material-design-admin-template/app-assets/vendors/select2/select2.full.min.js"></script>
    <script src="https://pixinvent.com/materialize-material-design-admin-template/app-assets/js/scripts/form-select2.min.js"></script>
  </body>
</html>