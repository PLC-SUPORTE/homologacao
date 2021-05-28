
<!DOCTYPE html>

<html class="loading" lang="en" data-textdirection="ltr">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Materialize is a Material Design Admin Template,It's modern, responsive and based on Material Design by Google.">
    <meta name="keywords" content="materialize, admin template, dashboard template, flat admin template, responsive admin template, eCommerce dashboard, analytic dashboard">
    <meta name="author" content="ThemeSelect">
    <title>Revisar solicitação de contratação | Portal PL&C</title>
    <link rel="apple-touch-icon" href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/images/favicon/apple-touch-icon-152x152.png">
    <link rel="shortcut icon" href="{{ asset('/public/assets/imgs/casinha.png')}}" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('/public/materialize/css/vendors.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/materialize.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/app-file-manager.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/widget-timeline.min.css') }}">

  </head>
  <body class="vertical-layout vertical-menu-collapsible page-header-dark vertical-modern-menu preload-transitions 2-columns   " data-open="click" data-menu="vertical-modern-menu" data-col="2-columns">

<header class="page-topbar" id="header">
  <div class="navbar navbar-fixed"> 
    <nav class="navbar-main navbar-color nav-collapsible navbar-dark no-shadow" style="background-color: gray">
      <div class="nav-wrapper">


      <div class="breadcrumbs-inline pt-3 pb-1" id="breadcrumbs-wrapper" style="margin-top: -40px;">
          <h5 class="breadcrumbs-title mt-0 mb-0 display-inline hide-on-small-and-down"><span style="color: white;">Movimentação de pessoas</span></h5>
          <ol class="breadcrumbs mb-0">
            <li class="breadcrumb-item"><a href="{{ route('Home.Principal.Show') }}">Home</a>
            </li>
            <li class="breadcrumb-item"><a href="{{ route('Painel.Contratacao.CEOO.index') }}">Solicitações em andamento</a>
            </li>
            <li class="breadcrumb-item active" style="color: black;">Revisar solicitação
            </li>
          </ol>
      </div>


      <ul class="navbar-list right" style="margin-top: -80px;">
          
      <li><a class="waves-block waves-light notification-button" href="javascript:void(0);" data-target="notifications-dropdown"><i class="material-icons">notifications_none<small class="notification-badge">{{$totalNotificacaoAbertas}}</small></i></a></li>
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

      
            <div class="section app-file-manager-wrapper" style="height: 550px; margin-top: -2px; overflow: hidden;">

  <div class="app-file-overlay"></div>

  <div class="sidebar-left" style="display: none" id="menulateralesquerdodiv">
    <div class="app-file-sidebar display-flex">
      <div class="app-file-sidebar-left">
        <span class="app-file-sidebar-close hide-on-med-and-up"><i class="material-icons">close</i></span>


        <div class="app-file-sidebar-content">

          <span class="app-file-label">Workflow - Nova contratação</span>
          <div class="collection file-manager-drive mt-3">

          <div class="activity-tab" id="file-activity">
            <ul class="widget-timeline mb-0">

              @foreach($historicos as $historico)
              <li class="timeline-items timeline-icon-green active">
                <div class="timeline-time" style="font-size: 8px;">{{ date('d/m/Y', strtotime($historico->data)) }}</div>
                <h6 class="timeline-title" style="font-size: 10px;">{{$historico->name}}</h6>
                <p class="timeline-text" style="font-size: 10px;">{{$historico->status}}</p>
              </li>
              @endforeach

             
            </ul>
          </div>

        </div>
      </div>

          </div>
        </div>

  </div>


  <!-- content-right start -->
  <div class="content-right">
    <div class="app-file-area">

    <div id="loadingdivcarrega">
   <div style="height: 50px;margin-top: calc(50vh - 150px);margin-left: calc(50vw - 600px);width: 180px;">
              <img style="width: 100px;margin-left:250px;margin-top:-70px;" src="{{URL::asset('/public/imgs/loading.gif')}}"/>
    </div>
    </div>

  <div id="corpodivcarrega">

      <div class="app-file-content">
        <div class="row app-file-recent-access mb-3">

    <form id="form" role="form" onsubmit="btnsubmit2.disabled = true; return true;" onsubmit="btnsubmit3.disabled = true; return true;"  action="{{ route('Painel.Contratacao.CEOO.solicitacaorevisada') }}" method="POST" role="create" enctype="multipart/form-data" >
    {{ csrf_field() }}

    <input type="hidden" name="id" id="id" value="{{$data->id}}">

    <div id="loadingdiv" style="display:none; margin-left: 600px; margin-top: -300px;">
      <img style="width: 100px; margin-top: -100px;" src="{{URL::asset('/public/imgs/loading.gif')}}"/>
    </div>

    <div class="modal-content">

  <div id="corpodiv2">

        <div class="row">

        <div class="input-field col s3">
          <span style="font-size: 11px;color:black;">Nome do candidato:</span>
          <input style="font-size: 10px;" readonly placeholder="Informe o nome do candidato." id="plc_porcent" type="text" value="{{$data->candidatonome}}" name="candidatonome" class="validate">
        </div>

        <div class="input-field col s3">
          <span style="font-size: 11px;color:black;">E-mail do candidato:</span>
          <input style="font-size: 10px;" readonly placeholder="Informe o e-mail do candidato." id="plc_porcent" type="text" value="{{$data->candidatoemail}}" name="candidatoemail" class="validate">
        </div>

        <div class="input-field col m2 s12">
            <span style="font-size: 11px;color:black;">Setor de custo:</span>
            <input style="font-size: 10px;color:black;" readonly id="plc_porcent" type="text" value="{{$data->Setor}}" name="setordescricao" class="validate">
            <input type="hidden" name="setor" value="{{$data->SetorCodigo}}">
        </div>

        <div class="input-field col m2 s12">
            <span style="font-size: 11px;color:black;">Tipo da vaga:</span>
            <input style="font-size: 10px;color:black;" readonly id="tipovaga" type="text" value="{{$data->TipoVaga}}" name="tipovaga" class="validate">
        </div>

        <div class="input-field col m2 s12">
            <span style="font-size: 11px;color:black;">Função:</span>
            <input style="font-size: 10px;color:black;" readonly id="plc_porcent" type="text" value="{{$data->TipoAdvogado}}" name="tipocargo" class="validate">
        </div>

        <div class="input-field col s2">
          <span style="font-size: 11px;color:black;">Distribuição mensal:</span>
          <input style="font-size: 10px;"  readonly name="salario" id="salario" type="text" value="<?php echo number_format($data->Salario,2,",",".") ?>" maxlength="8"  pattern="(?:\.|,|[0-9])*" class="form-control" placeholder="Valor(R$)" onKeyPress="return(moeda2(this,'.',',',event))" data-toggle="tooltip" data-placement="top" title="Preencha o salário." required="required">
        </div>

        <div class="input-field col m2 s12">
            <span style="font-size: 11px;color:black;">Classificação:</span>
            <input style="font-size: 10px;color:black;" readonly id="classificacao" type="text" value="{{$data->Classificacao}}" name="classificacao" class="validate">
        </div>

        <div class="input-field col m3 s12">
            <span style="font-size: 11px;color:black;">Data prevista para entrada:</span>
            <input style="font-size: 10px;color:black;" readonly id="dataentrada" type="text" value="{{ date('d/m/Y', strtotime($data->DataEntrada)) }}" name="dataentrada" class="validate">
        </div>

        @if($data->UsuarioNome != null)
        <div class="input-field col m4 s12">
            <span style="font-size: 11px;color:black;">Sócio de serviço/seletista que será substituido:</span>
            <input style="font-size: 10px;color:black;" readonly id="usuario" type="text" value="{{$data->UsuarioNome}}" name="classificacao" class="validate">
        </div>

        <div class="input-field col m3 s12">
            <span style="font-size: 11px;color:black;">Data previsão saída:</span>
            <input style="font-size: 10px;color:black;" readonly id="datasaida" type="text" value="{{ date('d/m/Y', strtotime($data->DataSaida)) }}" name="datasaida" class="validate">
        </div>
        @endif

        </div>

        <div class="row">

        <div class="input-field col s12" style="margin-top: -15px;">
         <div class="form-group">
            <div class="form-group">
              <label class="control-label" style="font-size: 11px;color:black;">Observação:</label>
                <textarea id="observacao" rows="3" type="text" name="observacao" class="form-control" placeholder="Insira a observação abaixo." style="height: 3rem;text-align:left; overflow:auto;font-size: 10px;">{{$data->observacao}}</textarea>
             </div>
          </div>
        </div>   
       </div>  

       <div class="row" >
       <div class="input-field col s3" style="margin-top: -30px;">
          <p>
                           <label style="font-size:11px;">Precisa de aprovação do conselho?</label><br>
                              <label>
                                 <input class="with-gap" name="avalconselho" value="Não" type="radio" checked onClick="conselhonao();"/>
                                 <span style="font-size: 10px;">Não</span>
                              </label>
                              <label>
                                 <input class="with-gap" name="avalconselho" value="Sim" type="radio" onClick="conselhosim();"/>
                                 <span style="font-size: 10px;">Sim</span>
                              </label>
                           </p>
       </div>
       </div>

       <div class="row" style="display: none;" id="anexoconselhodiv">
       <div class="col s9 input-field" style="margin-top: -20px;">
        <span style="font-size: 11px;">Anexar documento:</span>
        <input style="font-size: 10px;" name="conselhoanexo" id="conselhoanexo" type="file" >
       </div>
       </div>


       <div class="modal-footer" style="margin-top: 0px;margin-left: 550px;">
       <button type="submit"  name="acao" value="2"  class="modal-action waves-effect waves-green btn-flat" style="background-color: gray;color:white;font-size: 11px;"><i class="material-icons left">close</i>Glosar solicitação</button>
       <button type="button" onClick="abremodalconfirmando();"  name="acao" value="1"  class="modal-action waves-effect waves-green btn-flat" style="background-color: gray;color:white;font-size: 11px;"><i class="material-icons left">check</i>Aprovar solicitação</button>    
          </div>
       </div>

    </div>
    </form>



      </div>
    </div>

  </div>
 
</div>


          </div>
          <div class="content-overlay"></div>

    <!-- END: Page Main-->


    <div id="modalconfirmacao" class="modal"  style="width: 30% !important;height: 30% !important;">


    <div id="loadingdiv2" style="display:none">
  <div style="height: 50px;margin-top: calc(50vh - 150px);margin-left: calc(50vw - 600px);width: 180px;">
              <img style="width: 100px;margin-left:250px;margin-top:-70px;" src="{{URL::asset('/public/imgs/loading.gif')}}"/>
</div>
</div>

    <div id="corpodiv3">

    <div class="modal-content">
      <center><p style="font-size: 18px;">Deseja aprovar a solicitação de nova contratação?</p></center>
    </div>

    <div class="modal-footer">
      <a  class="modal-action  waves-effect waves-red btn-flat " style="background-color: red;color:white;font-size:11px;" onClick="nao();"><i class="material-icons left">close</i>Não</a>
      <a  class="modal-action  waves-effect waves-green btn-flat " style="background-color: green;color:white;font-size:11px;" onClick="sim();"><i class="material-icons left">check</i>Sim</a>
    </div>

  </div>


    <script src="{{ asset('/public/materialize/js/vendors.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>



<script>

  document.addEventListener("DOMContentLoaded", function () {
  document.getElementById("loadingdivcarrega").style.display = "";
  document.getElementById("corpodivcarrega").style.display = "none";
});
</script>

<script>
setTimeout(function() {
   $('#loadingdivcarrega').fadeOut('fast');
   $("#corpodivcarrega").show();
   document.getElementById("menulateralesquerdodiv").style.display = "";
}, 2300);
</script>


<script>
document.addEventListener("DOMContentLoaded", function () {
   $('.modal').modal();
});
</script>

<script>
  function conselhosim() {
         
    document.getElementById("anexoconselhodiv").style.display = "";
  }    
</script>

<script>
  function conselhonao() {
         
    document.getElementById("anexoconselhodiv").style.display = "none";
  }    
</script>

<script>
  function abremodalconfirmando() {

    $('.modal').modal();
    $('#modalconfirmacao').modal('open');
         
  }    
</script>


<script>
  function nao() {

    $('#modalconfirmacao').modal('close');
  }    
</script>

<script>
  function sim() {
    $('.modal').css('background-color', 'transparent');
    document.getElementById("loadingdiv2").style.display = "";
    document.getElementById("corpodiv3").style.display = "none";
    document.getElementById("form").submit();
  }    
</script>

  </body>
</html>