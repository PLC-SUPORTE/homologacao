
<!DOCTYPE html>

<html class="loading" lang="en" data-textdirection="ltr">
<head>
    <meta http-equiv="Content-Language" content="pt-br">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="author" content="Portal PL&C">
    <title>Nova mesa | PL&C Advogados</title>
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

       <li class="bold"><a class="collapsible-header waves-effect waves-cyan " href="JavaScript:void(0)"><i class="material-icons">calendar_today</i><span class="menu-title" data-i18n="Escritorio">Escritório</span></a>
         <div class="collapsible-body">
           <ul class="collapsible collapsible-sub" data-collapsible="accordion">
           
           @can('users')
           <li><a href="{{ route('Painel.TI.agendamentomesa.index') }}" style="font-size: 11px;"><i class="material-icons">add</i><span data-i18n="Modern">Novo agendamento</span></a></li>
           <li><a href="{{ route('Painel.TI.agendamentomesa.restrito.index') }}" style="font-size: 11px;"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Modern">Dashboard</span></a></li>
           <li><a href="{{ route('Painel.TI.agendamentomesa.restrito.salas.index') }}" style="font-size: 11px;"><i class="material-icons">radio_button_unchecked</i><span data-i18n="Modern">Listagem</span></a></li>
           @endcan
           
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
          <!-- Search for small screen-->
          <div class="container">
            <div class="row">
              <div class="col s10 m6 l6 breadcrumbs-left">
                <h5 class="breadcrumbs-title mt-0 mb-0 display-inline hide-on-small-and-down"><span>Cadastrar nova sala</span></h5>
                <ol class="breadcrumbs mb-0">
                  <li class="breadcrumb-item"><a href="{{ route('Home.Principal.Show') }}">Home</a>
                  </li>
                  <li class="breadcrumb-item"><a href="{{ route('Painel.TI.agendamentomesa.restrito.salas.index') }}">Salas</a>
                  </li>
                  <li class="breadcrumb-item active">Cadastrar nova sala
                  </li>
                </ol>
              </div>

            </div>
          </div>
        </div>
        <div class="col s12">
          <div class="container">

<section class="tabs-vertical mt-1 section">
  <div class="row">
    <div class="col l4 s12">

      <!-- tabs  -->
      <div class="card-panel">
        <ul class="tabs">

          <li class="tab">
            <a href="#general">
              <i class="material-icons">brightness_low</i>
              <span>General</span>
            </a>
          </li>

 
        </ul>
      </div>
    </div>

    <div class="col l8 s12">

      <!-- tabs content -->
      <form role="form" onsubmit="btnsubmit.disabled = true; return true;" action="{{ route('Painel.TI.agendamentomesa.restrito.salas.salacriada') }}" method="POST" role="search"  enctype="multipart/form-data">
     {{ csrf_field() }}    


      <div id="general">
        <div class="card-panel">
          <div class="display-flex">
            <div class="media">
              <img src="https://images-americanas.b2w.io/produtos/01/00/img/98122/8/98122886_1SZ.jpg" class="border-radius-4" alt="profile image"
                height="100" width="120">
            </div>

          </div>

          <div class="divider mb-1 mt-1"></div>

            <div class="row">

              <div class="col s12">
                <div class="input-field">
                  <label for="descricao">Descrição:</label>
                  <input type="text" id="descricao" name="descricao" placeholder="Informe a descrição..." required>
                </div>
              </div>

              <div class="col s12">
              <label>Selecione o tipo da sala:</label>
                <div class="input-field">
                <p>
               <label>
               <input name="tipo" id="tipo" value="1" type="radio" checked onClick="estacaotrabalho();"/>
               <span>Estação de trabalho</span>
               </label>
               </p>

               <p>
               <label>
               <input name="tipo" id="tipo2" value="2" type="radio" onClick="salareuniao();"/>
               <span>Sala de reunião</span>
               </label>
               </p>

                </div>
              </div>

              <div class="col s12">
              <label>Selecione o andar:</label>
                <div class="input-field">
                  <select class="select2-customize-result browser-default" id="andar" name="andar" required>                  
                        <option value="1ª Andar">1ª Andar</option>
                        <option value="2ª Andar">2ª Andar</option>
                        <option value="3ª Andar">3ª Andar</option>
                        <option value="4ª Andar">4ª Andar</option>
                        <option value="5ª Andar">5ª Andar</option>
                        <option value="6ª Andar">6ª Andar</option>
                        <option value="7ª Andar">7ª Andar</option>
                        <option value="8ª Andar">8ª Andar</option>
                        <option value="9ª Andar">9ª Andar</option>
                        <option value="10ª Andar">10ª Andar</option>
                        <option value="10ª Andar">11ª Andar</option>
                        <option value="10ª Andar">12ª Andar</option>
                        <option value="10ª Andar">13ª Andar</option>
                        <option value="10ª Andar">14ª Andar</option>
                        <option value="10ª Andar">15ª Andar</option>
                        <option value="10ª Andar">16ª Andar</option>
                        <option value="10ª Andar">17ª Andar</option>
                        <option value="10ª Andar">18ª Andar</option>
                        <option value="10ª Andar">19ª Andar</option>
                        <option value="10ª Andar">20ª Andar</option>
                        <option value="10ª Andar">21ª Andar</option>
                        <option value="10ª Andar">22ª Andar</option>
                        <option value="10ª Andar">23ª Andar</option>
                        <option value="10ª Andar">24ª Andar</option>
                        <option value="10ª Andar">25ª Andar</option>
                        <option value="10ª Andar">26ª Andar</option>
                        <option value="10ª Andar">27ª Andar</option>
                        <option value="10ª Andar">28ª Andar</option>
                        <option value="10ª Andar">29ª Andar</option>
                        <option value="10ª Andar">30ª Andar</option>
                        <option value="10ª Andar">31ª Andar</option>
                        <option value="10ª Andar">32ª Andar</option>
                    </select>
                </div>
              </div>

              <div class="col s12">
              <label>Selecione a unidade:</label>
                <div class="input-field">
                  <select class="select2-customize-result browser-default" id="unidade" name="unidade" required>                  
                        @foreach($unidades as $unidade)
                        <option value="{{$unidade->codigo}}">{{$unidade->descricao}}</option>
                        @endforeach
                    </select>
                </div>
              </div>

              <div id="salareuniao">
              <div class="col s12">
                <div class="input-field">
                  <label for="link">Link outlook:</label>
                  <input type="email" id="link" name="link" placeholder="Informe caso tenha o link do e-mail do outlook..">
                </div>
              </div>

              <div class="col s12">
                <div class="input-field">
                  <label for="link">Quantidade participantes:</label>
                  <input type="number" id="quantidade" name="quantidade" placeholder="Informe a quantidade de participantes..">
                </div>
              </div>

              </div>

              <div class="col s12">
              <label>Selecione o status:</label>
                <div class="input-field">
                  <select class="select2-customize-result browser-default" id="status" name="status" required>                  
                        <option value="A">Ativo</option>
                        <option value="I">Ínativo</option>
                    </select>
                </div>
              </div>

              <div class="col s12 display-flex justify-content-end form-action mt-2">
              <button type="submit" id="btnsubmit" class="btn waves-light waves-effect mr-sm-1 mr-2" style="background-color: gray">Cadastrar sala</button>
              <button type="button" class="btn btn-light-pink waves-light waves-effect">Cancelar</button>
            </div>


             
            </div>
        </div>
      </div>


      </form>

    </div>
  </div>
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
    <script src="https://pixinvent.com/materialize-material-design-admin-template/app-assets/vendors/select2/select2.full.min.js"></script>
    <script src="https://pixinvent.com/materialize-material-design-admin-template/app-assets/js/scripts/form-select2.min.js"></script>
    <script src="{{ asset('/public/materialize/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/page-account-settings.min.js') }}"></script>


    <script>
    function salareuniao() {

     $("#salareuniao").show();
     }
     </script>

    <script>
    function estacaotrabalho() {

     $("#salareuniao").hide();
     }
     </script>

<script>
$(document).ready(function(){
  $("#salareuniao").hide();


});
</script>



  </body>
</html>