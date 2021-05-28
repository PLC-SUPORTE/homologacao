<!DOCTYPE html>
<html class="loading" lang="pt-br" data-textdirection="ltr">
   <head>
      <meta http-equiv="Content-Language" content="pt-br">
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
          <meta name="author" content="Portal PL&C">
      <title>Escritório - Montar formulário | Portal PL&C</title>
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

    
   </head>
   <body class="vertical-layout vertical-menu-collapsible page-header-dark vertical-modern-menu preload-transitions 2-columns   " data-open="click" data-menu="vertical-modern-menu" data-col="2-columns">
      <header class="page-topbar" id="header">
         <div class="navbar navbar-fixed">
            <nav class="navbar-main navbar-color nav-collapsible navbar-dark no-shadow" style="background-color: gray">
               <div class="nav-wrapper">
                  <div class="breadcrumbs-inline pt-3 pb-1" id="breadcrumbs-wrapper" style="margin-top: -40px;">
                     <h5 class="breadcrumbs-title mt-0 mb-0 display-inline hide-on-small-and-down"><span style="color: white;">Solicitações</span></h5>
                     <ol class="breadcrumbs mb-0">
                        <li class="breadcrumb-item"><a href="{{route('Home.Principal.Show')}}">Home</a>
                        </li>
                        <li class="breadcrumb-item active"><a href="{{route('Painel.Escritorio.Solicitacoes.Administrativo.index')}}">Solicitações em andamento</a>
                        </li>
                        <li class="breadcrumb-item active" style="color: black;">Montar formulário
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
                     <li><a class="black-text" href="#!" style="font-size: 11px;"><span class="material-icons icon-bg-circle deep-orange small">today</span>{{$notificacao->obs}}</a>
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
            <div class="card" style="height: 100%;" id="card">
                <div class="card-content">
                   <div class="container" style="border: 1px solid #BBBBBB; border-radius: 10px;">
                     <h5 class="card-title">Solicitante</h5>
                    
                     <form id="form" role="form" onsubmit="btnsubmit.disabled = true; return true;" action="{{ route('Painel.Escritorio.Solicitacoes.Administrativo.store') }}" method="POST" role="create"  enctype="multipart/form-data">
                         {{ csrf_field() }}

                         <input type="hidden" name="id" value="{{$id}}">
 
                         <div class="row">

                          <div class="col s2">
                               <span style="font-size: 11px;">Nome:</span>
                               <input style="font-size: 10px;" value="{{$solicitante->UsuarioNome}}" name="solicitante_nome" id="solicitante_nome" type="text" class="validate" readonly>
                           </div>

                           <div class="col s3">
                               <span style="font-size: 11px;">E-mail:</span>
                               <input style="font-size: 10px;" value="{{$solicitante->UsuarioEmail}}" name="solicitante_email" id="solicitante_email" type="email" class="validate" readonly>
                           </div>

                           <div class="col s2">
                               <span style="font-size: 11px;">Centro de custo:</span>
                               <input style="font-size: 10px;"  value="{{$solicitante->Setor}}" type="text" class="validate" readonly>
                           </div>

                           <div class="col s1">
                               <span style="font-size: 11px;">Unidade:</span>
                               <input style="font-size: 10px;" value="{{$solicitante->Unidade}}" type="text" class="validate" readonly>
                           </div>

                           <div class="col s2">
                               <span style="font-size: 11px;">Data da solicitação:</span>
                               <input style="font-size: 10px;" id="datasolicitacao" name="datasolicitacao" value="{{ date('d/m/Y H:i:s', strtotime($solicitante->DataSolicitacao)) }}" type="text" class="validate" readonly>
                           </div>

                           <div class="col s2">
                               <span style="font-size: 11px;">Data da ultima modificação:</span>
                               <input style="font-size: 10px;" id="dataedicao" name="dataedicao" value="{{ date('d/m/Y H:i:s', strtotime($solicitante->DataSolicitacao)) }}" type="text" class="validate" readonly>
                           </div>

                         </div>

                           <div class="row">

                           <div class="input-field col m12 s12">
                           <span style="font-size: 11px;">Observação:</span>
                           <textarea style="font-size: 10px; height: 45px;"  id="observacao" name="observacao" readonly >{{$solicitante->Observacao}}</textarea>
                           </div>

                           </div>
                   </div>

                   <br>

                   @foreach($datas as $data)
                   <div class="container" style="border: 1px solid #BBBBBB; border-radius: 10px;">
                     <h5 class="card-title" style="font-size: 11px;">Produto: {{$data->ProdutoDescricao}} - Quantidade: {{$data->ProdutoQuantidade}} - Área: {{$data->AreaDescricao}} - Setor: {{$data->SetorDescricao}}</h5>


                        <input type="hidden" name="produto_id[]" value="{{$data->ProdutoId}}">
                        <input type="hidden" name="produto_setor[]" value="{{$data->SetorCodigo}}">
                     
                  
                        <div class="row">


                        <!--Fornecedor 1--> 
                        <div class="container" style="border: 1px solid #BBBBBB; border-radius: 10px;">
                        <h5 class="card-title" style="font-size: 11px;">Fornecedor 1:</h5>

                        <div class="row">

                        <div class="col s3">
                              <span style="font-size: 11px;">Fornecedor:</span>
                              <input style="font-size: 10px;" placeholder="Razão sócial..." id="fornecedor_nome1"
                              name="fornecedor_nome[]" type="text" class="validate" required>
                        </div>
                         
                        <div class="col s2" style="font-size: 11px;">
                              <span style="font-size: 11px;">CPF/CNPJ:</span>
                              <input style="font-size: 10px;" id="cpf_cnpj1" name="fornecedor_cpf_cnpj[]" type="text" class="validate" required>
                        </div>

                        <div class="col s2" style="font-size: 11px;">
                              <span style="font-size: 11px;">Contato:</span>
                              <input style="font-size: 10px;" placeholder="(31) 5555-5555" id="fornecedor_contato1" name="fornecedor_contato[]" onkeypress="mask(this, mphone);" onblur="mask(this, mphone);" type="text" class="validate" required>
                        </div>

                        <div class="col s1" >
                              <span style="font-size: 11px;">Prazo (Dias):</span>
                              <input style="font-size: 10px;" id="fornecedor_prazo1" name="fornecedor_prazo[]" type="number" class="validate" required>
                        </div>

                          <div class="col s2">
                              <span style="font-size: 11px;">Forma de pagamento:</span>
                              <select class="browser-default" style="font-size: 10px;" required id="fornecedor_formapagamento1" name="fornecedor_formapagamento[]">
                                <option value="Boleto">Boleto</option>
                                <option value="Cartão">Cartão</option>
                                <option value="Transferência">Transferência</option>
                              </select>
                            </div>


                           <div class="col s1">
                              <span style="font-size: 11px;">Valor:</span>
                              <input style="font-size: 10px;" id="fornecedor_valor1" name="fornecedor_valornutario[]" pattern="(?:\.|,|[0-9])*" onKeyPress="return(moeda2(this,'.',',',event))" type="text" class="validate">
                           </div>

                           <div class="col s1" id="div_parcelas">
                              <span style="font-size: 11px;">Parcelas:</span>
                              <input style="font-size: 10px;" id="fornecedor_parcelas1" name="fornecedor_parcelas[]" type="number" class="validate">
                           </div>

                           </div>
                           <div class="row">

                           <div class="col s1" id="div_frete_cartao">
                              <span style="font-size: 11px;">Valor frete:</span>
                              <input style="font-size: 10px;" id="fornecedor_frete1" name="fornecedor_valorfrete[]" type="text" class="validate" pattern="(?:\.|,|[0-9])*" onKeyPress="return(moeda2(this,'.',',',event))">
                           </div>

                           <div class="col s1" id="div_total">
                              <span style="font-size: 11px;">Total:</span>
                              <input style="font-size: 10px;" id="fornecedor_valortotal1" name="fornecedor_valortotal[]"  type="text" pattern="(?:\.|,|[0-9])*" onKeyPress="return(moeda2(this,'.',',',event))" class="validate">
                           </div>

                           <div class="col s3" id="div_total">
                              <span style="font-size: 11px;">Quantidade de compras realizadas:</span>
                              <input style="font-size: 10px;" id="fornecedor_quantidadecompras1" readonly name="fornecedor_quantidadecompras[]"  type="text" pattern="(?:\.|,|[0-9])*" class="validate">
                        </div>

                        <div class="col s2" id="div_total">
                              <span style="font-size: 11px;">Data da última compra:</span>
                              <input style="font-size: 10px;" id="fornecedor_dataultimacompra1" readonly  name="fornecedor_dataultimacompra[]"  type="text"  class="validate">
                        </div>

                          <div class="input-field col s3">
                           <span style="font-size: 11px;">Inserir anexo:</span><br>
          
                           <input style="font-size: 10px;" required type="file" id="select_file" name="select_file[]" accept=".jpg,.png,.xls,.xlsx,.pdf,.doc,.docx,.rtf,.jpeg" class="dropify" data-default-file=""/>
                           </div>

                        </div>
                        </div>
                        <br>
                        <!--Fim Fornecedor 1 --> 


                        <!--Fornecedor 2 --> 
                        <div class="container" style="border: 1px solid #BBBBBB; border-radius: 10px;">
                        <h5 class="card-title" style="font-size: 11px;">Fornecedor 2:</h5>

                        <div class="row">

                        <div class="col s3">
                              <span style="font-size: 11px;">Fornecedor:</span>
                              <input style="font-size: 10px;" placeholder="Razão sócial..." id="fornecedor_nome2"
                              name="fornecedor_nome[]" type="text" class="validate" required>
                        </div>
                         
                        <div class="col s2" style="font-size: 11px;">
                              <span style="font-size: 11px;">CPF/CNPJ:</span>
                              <input style="font-size: 10px;" id="cpf_cnpj2" name="fornecedor_cpf_cnpj[]" type="text" class="validate" required>
                        </div>

                        <div class="col s2" style="font-size: 11px;">
                              <span style="font-size: 11px;">Contato:</span>
                              <input style="font-size: 10px;" placeholder="(31) 5555-5555" id="fornecedor_contato2" name="fornecedor_contato[]" onkeypress="mask(this, mphone);" onblur="mask(this, mphone);" type="text" class="validate" required>
                        </div>

                        <div class="col s1" >
                              <span style="font-size: 11px;">Prazo (Dias):</span>
                              <input style="font-size: 10px;" id="fornecedor_prazo2" name="fornecedor_prazo[]" type="number" class="validate" required>
                        </div>

                          <div class="col s2">
                              <span style="font-size: 11px;">Forma de pagamento:</span>
                              <select class="browser-default" style="font-size: 10px;" required id="fornecedor_prazopagamento2" name="fornecedor_formapagamento[]">
                                <option value="Boleto">Boleto</option>
                                <option value="Cartão">Cartão</option>
                                <option value="Transferência">Transferência</option>
                              </select>
                            </div>


                           <div class="col s1">
                              <span style="font-size: 11px;">Valor:</span>
                              <input style="font-size: 10px;" id="fornecedor_valor2" name="fornecedor_valornutario[]" pattern="(?:\.|,|[0-9])*" onKeyPress="return(moeda2(this,'.',',',event))" type="text" class="validate">
                           </div>

                           <div class="col s1" id="div_parcelas">
                              <span style="font-size: 11px;">Parcelas:</span>
                              <input style="font-size: 10px;" id="fornecedor_parcelas2" name="fornecedor_parcelas[]" type="number" class="validate">
                           </div>

                           </div>
                           <div class="row">

                           <div class="col s1" id="div_frete_cartao">
                              <span style="font-size: 11px;">Valor frete:</span>
                              <input style="font-size: 10px;" id="fornecedor_frete2" name="fornecedor_valorfrete[]" type="text" class="validate" pattern="(?:\.|,|[0-9])*" onKeyPress="return(moeda2(this,'.',',',event))">
                           </div>

                           <div class="col s1" id="div_total">
                              <span style="font-size: 11px;">Total:</span>
                              <input style="font-size: 10px;" id="fornecedor_valortotal2" name="fornecedor_valortotal[]"  type="text" pattern="(?:\.|,|[0-9])*" onKeyPress="return(moeda2(this,'.',',',event))" class="validate">
                           </div>

                           <div class="col s3" id="div_total">
                              <span style="font-size: 11px;">Quantidade de compras realizadas:</span>
                              <input style="font-size: 10px;" id="fornecedor_quantidadecompras2" readonly name="fornecedor_quantidadecompras[]"  type="text" pattern="(?:\.|,|[0-9])*" class="validate">
                        </div>

                        <div class="col s2" id="div_total">
                              <span style="font-size: 11px;">Data da última compra:</span>
                              <input style="font-size: 10px;" id="fornecedor_dataultimacompra2" readonly  name="fornecedor_dataultimacompra[]"  type="text"  class="validate">
                        </div>

                          <div class="input-field col s3">
                           <span style="font-size: 11px;">Inserir anexo:</span><br>
          
                           <input style="font-size: 10px;" required type="file" id="select_file" name="select_file[]" accept=".jpg,.png,.xls,.xlsx,.pdf,.doc,.docx,.rtf,.jpeg" class="dropify" data-default-file=""/>
                           </div>

                        </div>
                        </div>
                        <br>
                        <!--Fim Fornecedor 2 --> 


                        <!--Forncedor 3 --> 
                        <div class="container" style="border: 1px solid #BBBBBB; border-radius: 10px;">
                        <h5 class="card-title" style="font-size: 11px;">Fornecedor 3:</h5>

                        <div class="row">

                        <div class="col s3">
                              <span style="font-size: 11px;">Fornecedor:</span>
                              <input style="font-size: 10px;" placeholder="Razão sócial..." id="fornecedor_nome3"
                              name="fornecedor_nome[]" type="text" class="validate" required>
                        </div>
                         
                        <div class="col s2" style="font-size: 11px;">
                              <span style="font-size: 11px;">CPF/CNPJ:</span>
                              <input style="font-size: 10px;" id="cpf_cnpj3" name="fornecedor_cpf_cnpj[]" type="text" class="validate" required>
                        </div>

                        <div class="col s2" style="font-size: 11px;">
                              <span style="font-size: 11px;">Contato:</span>
                              <input style="font-size: 10px;" placeholder="(31) 5555-5555" id="fornecedor_contato3" name="fornecedor_contato[]" onkeypress="mask(this, mphone);" onblur="mask(this, mphone);" type="text" class="validate" required>
                        </div>

                        <div class="col s1" >
                              <span style="font-size: 11px;">Prazo (Dias):</span>
                              <input style="font-size: 10px;" id="fornecedor_prazo3" name="fornecedor_prazo[]" type="number" class="validate" required>
                        </div>

                          <div class="col s2">
                              <span style="font-size: 11px;">Forma de pagamento:</span>
                              <select class="browser-default" style="font-size: 10px;" required id="fornecedor_formapagamento3" name="fornecedor_formapagamento[]">
                                <option value="Boleto">Boleto</option>
                                <option value="Cartão">Cartão</option>
                                <option value="Transferência">Transferência</option>
                              </select>
                            </div>


                           <div class="col s1">
                              <span style="font-size: 11px;">Valor:</span>
                              <input style="font-size: 10px;" id="fornecedor_valor3" name="fornecedor_valornutario[]" pattern="(?:\.|,|[0-9])*" onKeyPress="return(moeda2(this,'.',',',event))" type="text" class="validate">
                           </div>

                           <div class="col s1" id="div_parcelas">
                              <span style="font-size: 11px;">Parcelas:</span>
                              <input style="font-size: 10px;" id="fornecedor_parcelas3" name="fornecedor_parcelas[]" type="number" class="validate">
                           </div>

                           </div>
                           <div class="row">

                           <div class="col s1" id="div_frete_cartao">
                              <span style="font-size: 11px;">Valor frete:</span>
                              <input style="font-size: 10px;" id="fornecedor_frete3" name="fornecedor_valorfrete[]" type="text" class="validate" pattern="(?:\.|,|[0-9])*" onKeyPress="return(moeda2(this,'.',',',event))">
                           </div>

                           <div class="col s1" id="div_total">
                              <span style="font-size: 11px;">Total:</span>
                              <input style="font-size: 10px;" id="fornecedor_valortotal3" name="fornecedor_valortotal[]"  type="text" pattern="(?:\.|,|[0-9])*" onKeyPress="return(moeda2(this,'.',',',event))" class="validate">
                           </div>

                           <div class="col s3" id="div_total">
                              <span style="font-size: 11px;">Quantidade de compras realizadas:</span>
                              <input style="font-size: 10px;" id="fornecedor_quantidadecompras3" readonly name="fornecedor_quantidadecompras[]"  type="text" pattern="(?:\.|,|[0-9])*" class="validate">
                        </div>

                        <div class="col s2" id="div_total">
                              <span style="font-size: 11px;">Data da última compra:</span>
                              <input style="font-size: 10px;" id="fornecedor_dataultimacompra3" readonly  name="fornecedor_dataultimacompra[]"  type="text"  class="validate">
                        </div>

                          <div class="input-field col s3">
                           <span style="font-size: 11px;">Inserir anexo:</span><br>
          
                           <input style="font-size: 10px;" required type="file" id="select_file" name="select_file[]" accept=".jpg,.png,.xls,.xlsx,.pdf,.doc,.docx,.rtf,.jpeg" class="dropify" data-default-file=""/>
                           </div>

                        </div>
                        </div>
                        <br>
                        <!--Fim Fornecedor 3 --> 

                        </div>
                        </div>

                        <br>

              
                        @endforeach
                        <br>

                        </div>



                     <button id="btnsubmit" class="btn waves-light right align" style="margin-top: -60px; margin-right: 25px;background-color: gray;font-size: 11px;" type="submit">Salvar dados
                        <i class="material-icons left">save</i>
                    </button>

                  </div>

                        <br>

                      
                        </div>
                    </form>
                </div>
            </div>
          </div>
      </div>


<div id="modalfornecedores" class="modal"  style="width: 600px;">

    <div id="corpodiv3">
    <div class="modal-content">
      <center><p style="font-size: 18px;">Deseja visualizar os melhores fornecedores para cada produto?</p></center>
    </div>
    <div class="modal-footer">
      <a  class="modal-action modal-close  waves-effect waves-red btn-flat " style="background-color: red;color:white;font-size:11px;"><i class="material-icons left">close</i>Não</a>
      <a  class="modal-action  waves-effect waves-green btn-flat " style="background-color: green;color:white;font-size:11px;" onClick="buscafornecedores();"><i class="material-icons left">check</i>Sim</a>
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
      <script type='text/javascript' src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>
      
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

    function buscafornecedores() {


       //Busca via Ajax os 3 melhores fornecedores por produto
       var _token = $('input[name="_token"]').val();

       $.ajax({
                  url:"{{ route('Painel.Escritorio.Solicitacoes.Administrativo.buscafornecedores') }}",
                  type: 'POST',
                  data:{_token: _token},
                  dataType: "json",
                  success:function(response){
                  // $('#produtos').html(response);

                  //1 Fornecedor
                  document.getElementById('fornecedor_nome1').value=(response[0].descricao);
                  document.getElementById('cpf_cnpj1').value=(response[0].codigo);
                  document.getElementById('fornecedor_contato1').value=(response[0].contato);
                  document.getElementById('fornecedor_prazo1').value=(response[0].prazo);
                  document.getElementById('fornecedor_valor1').value=(response[0].valor_unitario);
                  document.getElementById('fornecedor_parcelas1').value=(response[0].parcelas);
                  document.getElementById('fornecedor_frete1').value=(response[0].valor_frete);
                  document.getElementById('fornecedor_valortotal1').value=(response[0].valor_total);
                  document.getElementById('fornecedor_quantidadecompras1').value=(response[0].quantidade_compras);
                  document.getElementById('fornecedor_dataultimacompra1').value=(response[0].data_ultimacompra);
                  //Fim 1 Fornecedor

                  //2 Fornecedor
                  document.getElementById('fornecedor_nome2').value=(response[1].descricao);
                  document.getElementById('cpf_cnpj2').value=(response[1].codigo);
                  document.getElementById('fornecedor_contato2').value=(response[1].contato);
                  document.getElementById('fornecedor_prazo2').value=(response[1].prazo);
                  document.getElementById('fornecedor_valor2').value=(response[1].valor_unitario);
                  document.getElementById('fornecedor_parcelas2').value=(response[1].parcelas);
                  document.getElementById('fornecedor_frete2').value=(response[1].valor_frete);
                  document.getElementById('fornecedor_valortotal2').value=(response[1].valor_total);
                  document.getElementById('fornecedor_quantidadecompras2').value=(response[1].quantidade_compras);
                  document.getElementById('fornecedor_dataultimacompra2').value=(response[1].data_ultimacompra);
                  //Fim 2 Fornecedor

                  //3 Fornecedor
                  document.getElementById('fornecedor_nome3').value=(response[2].descricao);
                  document.getElementById('cpf_cnpj3').value=(response[2].codigo);
                  document.getElementById('fornecedor_contato3').value=(response[2].contato);
                  document.getElementById('fornecedor_prazo3').value=(response[2].prazo);
                  document.getElementById('fornecedor_valor3').value=(response[2].valor_unitario);
                  document.getElementById('fornecedor_parcelas3').value=(response[2].parcelas);
                  document.getElementById('fornecedor_frete3').value=(response[2].valor_frete);
                  document.getElementById('fornecedor_valortotal3').value=(response[2].valor_total);
                  document.getElementById('fornecedor_quantidadecompras3').value=(response[2].quantidade_compras);
                  document.getElementById('fornecedor_dataultimacompra3').value=(response[2].data_ultimacompra);
                  //Fim 3 Fornecedor


                  // $.each(response, function(index, obj) {
                  //   alert(obj.descricao);
                  // });

                  
               }
      }); 


       //Após carregar fechar modal
       $('#modalfornecedores').modal('close');

    }

    </script>
   


   

   <!-- <script>
      $('#frete').on('blur', function() {

      var parcelas = $("#parcelas").val();
      var valor = parseFloat($("#valor").val().replace(',', '.'));
      var frete = parseFloat($("#frete").val().replace(',', '.'));
      
      if(parcelas == 0) {

         var valor_total = valor + frete;

      } else {
      var valor = parseFloat($("#valor").val().replace(',', '.'));
      var frete = parseFloat($("#frete").val().replace(',', '.'));
      // var valor_total = valor / parcelas + frete;
      var valor_total = valor + frete;

      }

      $("#total").val(parseFloat(valor_total).toFixed(2));

      
      });

   </script> -->

   <script language="javascript">   
      $(document).ready(function($){
      $("input[id*='cpf_cnpj']").inputmask({
      mask: ['999.999.999-99', '99.999.999/9999-99']
      });
         
      });
   </script>

<script>
      document.addEventListener("DOMContentLoaded", function () {
            $('.modal').modal();
            $('#modalfornecedores').modal('open');
      });
</script>


   </body>
</html>