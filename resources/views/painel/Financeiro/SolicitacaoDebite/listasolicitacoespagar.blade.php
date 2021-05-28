<!DOCTYPE html>

<html class="loading" lang="pt-br" data-textdirection="ltr">
  <head>
    <meta http-equiv="Content-Language" content="pt-br">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="author" content="Portal PL&C">
    <title>Realizar conciliação bancária | Portal PL&C</title>
    <link rel="apple-touch-icon" href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/images/favicon/apple-touch-icon-152x152.png">
    <link rel="shortcut icon" href="{{ asset('/public/assets/imgs/casinha.png')}}" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  
    <!-- BEGIN: VENDOR CSS-->
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/vendors.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/responsive.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/materialize.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/data-tables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/select2-materialize.css') }}">

 
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
  <!-- END: Head-->
  <body class="vertical-layout vertical-menu-collapsible page-header-dark vertical-modern-menu preload-transitions 2-columns   " data-open="click" data-menu="vertical-modern-menu" data-col="2-columns">

<header class="page-topbar" id="header">
  <div class="navbar navbar-fixed"> 
    <nav class="navbar-main navbar-color nav-collapsible navbar-dark no-shadow" style="background-color: gray">
  
<div class="breadcrumbs-inline pt-3 pb-1" id="breadcrumbs-wrapper" style="margin-top: -45px;">
<h5 class="breadcrumbs-title mt-0 mb-0 display-inline hide-on-small-and-down"><span style="color: white;">Correspondente</span></h5>
<ol class="breadcrumbs mb-0">
<li class="breadcrumb-item"><a href="{{ route('Home.Principal.Show') }}">Home</a>
</li>
<li class="breadcrumb-item active" style="color: black;">Realizar conciliação bancária
</li>
</ol>
</div>

<ul class="navbar-list right" style="margin-top: -80px;">
              
              <li><a style="margin-top: 20px;" class="waves-block waves-light notification-button" href="javascript:void(0);" data-target="notifications-dropdown" ><i class="material-icons">notifications_none<small class="notification-badge">{{$totalNotificacaoAbertas}}</small></i></a></li>
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


<center>
<div id="loadingdiv" style="display:none">
   <div class="wrapper">
   <div class="circle circle-1"></div>
   <div class="circle circle-1a"></div>
   <div class="circle circle-2"></div>
   <div class="circle circle-3"></div>
  </div>
  <h1 style="text-align: center;">Aguarde, estamos realizando a baixa das solicitações informadas...&hellip;</h1>
  </div>
</center>   

   <div class="row" id="corpodiv">

   {!! Form::open(['route' => ['Painel.Financeiro.solicitacoesbaixadas'], 'id' => 'form', 'files' => true ,'class' => 'form form-search form-ds']) !!}
  {{ csrf_field() }}
      
        <div class="col s12">
          <div class="container">
            <div class="section section-data-tables">

            <div class="row">
    <div class="col s12">
      <div class="card">
        <div class="card-content">

          <div class="row">
            <div class="col s12">
              <table id="scroll-dynamic" class="display">
                <thead>
                <tr>
                    <th>
                      <label>
                        <input type="checkbox" onClick="marcatodos();" id="selectAll"/>
                        <span></span>
                      </label>
                    </th>
                    <th style="font-size: 11px">Número debite</th>
                    <th style="font-size: 11px">CPR</th>
                    <th style="font-size: 11px">Cliente</th>
                    <th style="font-size: 11px">Tipo lançamento</th>
                    <th style="font-size: 11px">Setor</th>
                    <th style="font-size: 11px">Valor</th>
                    <th style="font-size: 11px">Data serviço</th>
                    <th style="font-size: 11px">Data programação</th>
                    <!-- <th style="font-size: 11px">Data vencimento</th> -->
                  </tr>
                </thead>

                <tbody>
                 @foreach($notas as $categoria)
                  <tr>
                    <td>
                      <label>
                      <input type="checkbox" class="check marcar" name="numerodebite[]" id="numerodebite[]" value="{{$categoria->NumeroDebite}}" />
                        <span></span>
                      </label>
                    </td>
                    {{ Form::hidden('fornecedor', $categoria->CodigoFornecedor, array('id' => 'invisible_id')) }}
                    <div class="col-md-12" hidden="">
                    <textarea id="hist" rows="4" type="text" name="hist" readonly="" class="form-control" placeholder="Hist debite">
{{$categoria->Hist}}
Baixa realizada pelo(a): {{Auth::user()->name}} - {{$dataHoraMinuto}} no portal.
                    </textarea>
              </div>     
                    <td style="font-size: 9px">{{ $categoria->NumeroDebite}}</td>
                    <td style="font-size: 9px">{{ $categoria->CPR}}</td>
                    <td style="font-size: 9px">{{ $categoria->NomeFornecedor}}</td>
                    <td style="font-size: 9px">{{ $categoria->TipoLancamentoCPR}}</td>
                    <td style="font-size: 9px">{{ $categoria->Setor}}</td>
                    <td style="font-size: 9px">R$ <?php echo number_format($categoria->ValorTotal,2,",",".") ?></td>
                    <td style="font-size: 9px">{{ date('d/m/Y' , strtotime($categoria->DataServico)) }}</td>
                    <td style="font-size: 9px">{{ date('d/m/Y' , strtotime($categoria->DataProgramacao)) }}</td>
                    <!-- <td style="font-size: 10px">{{ date('d/m/Y' , strtotime($categoria->DataVencimento)) }}</td> -->
                  </tr>
                @endforeach
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

        <div class="card" style="background-color: #e2e3e5; border-color: #d6d8db">
        <div class="card-content">
        <div class="row">
        <div class="col s12 m12 l12">

        <h4 class="card-title">Selecione os dados abaixo:</h4>

        <div class="input-field col s3">
        <select class="select2 browser-default" name="tipodoc" id="tipodoc" required>
        @foreach($tiposdoc as $tipodoc)   
        <option value="{{$tipodoc->Codigo}}">{{$tipodoc->Codigo}} - {{ $tipodoc->Descricao}}</option>
        @endforeach
        </select>
        <label>Selecione o tipo de documento:</label>
       </div>

       <div class="input-field col s3">
        <select class="select2 browser-default" name="portador" required>
        @foreach($bancos as $banco)   
        <option value="{{$banco->Codigo}}">{{ $banco->Descricao}}</option>
        @endforeach
        </select>
        <label>Selecione o banco:</label>
       </div>

       <div class="input-field col s2">
            <label class="control-label">Data Conciliação:</label>
            <input name="dataconciliacao" id="dataconciliacao" type="date" max="{{$datahoje}}" value="{{$datahoje}}" class="form-control" data-toggle="tooltip" data-placement="top" title="Selecione a data de conciliação." required="required">
        </div>  

          <div class="input-field col s2">
            <label class="control-label">Data baixa:</label>
            <input name="databaixa" id="databaixa" type="date" max="{{$datahoje}}" value="{{$datahoje}}" class="form-control" data-toggle="tooltip" data-placement="top" title="Selecione a data de baixa." required="required">
        </div>      

        <div class="input-field col s4">
            <p>
            <label>Anexar comprovante de pagamento?</label><br>
            <label>
            <input class="with-gap" name="comprovante" type="radio" value="SIM" id="test1" onClick="colocacomprovante();" checked />
            <span>Sim</span>
            </label>

            <label>
            <input class="with-gap" name="comprovante" type="radio" value="NAO" onClick="retiracomprovante();" id="test2" />
            <span>Não</span>
            </label>
            </p>
        </div>

        <div id="comprovantediv">
        <div class="col s12 m12 l12">
        <input type="file" name="select_file" id="input-file-now" class="dropify" accept=".pdf" data-default-file="" />
        </div>
        </div>

        <div class="input-field col s3" style="margin-left: 900px;">
                  <button id="btnsubmit" class="btn waves-effect right" style="background-color: gray;" onClick="envia();" type="button" name="action">Baixar
                    <i class="material-icons right">check</i>
                  </button>
        </div>

           
        </div>
    </div>

    </form>
      </div>
    </div>
  </div>
</div>



    <script src="{{ asset('/public/materialize/js/vendors.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/data-tables.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/select2.full.min.js') }}"></script>


<script>
function envia() {

    document.getElementById("loadingdiv").style.display = "";
    document.getElementById("corpodiv").style.display = "none";
    document.getElementById("form").submit();
}    
</script>



<script>
function marcatodos() {

  $('.marcar').each(function () {
   $(this).prop("checked", 'true');
});

}    
</script>

<script>
function colocacomprovante() {

    document.getElementById("comprovantediv").style.display = "";
}    
</script>

<script>
function retiracomprovante() {

    document.getElementById("comprovantediv").style.display = "none";
}    
</script>

  </body>
</html>