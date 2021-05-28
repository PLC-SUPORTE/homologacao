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
                        <li class="breadcrumb-item active"><a href="{{route('Painel.Escritorio.Solicitacoes.Financeiro.index')}}">Solicitações em andamento</a>
                        </li>
                        <li class="breadcrumb-item active" style="color: black;">Montar formulário
                        </li>
                     </ol>
                     </ol>
                  </div>
                  <ul class="navbar-list right" style="margin-top: -80px;">

                     <li class="hide-on-large-only search-input-wrapper"><a class="waves-effect waves-block waves-light search-button" href="javascript:void(0);"><i class="material-icons">search</i></a></li>
                     <li><a class="waves-effect waves-block waves-light notification-button" href="javascript:void(0);" data-target="notifications-dropdown"><i class="material-icons">notifications_none<small class="notification-badge">{{$totalNotificacaoAbertas}}</small></i></a></li>
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
                    
                     <form id="form" role="form" onsubmit="btnsubmit.disabled = true; return true;" action="{{ route('Painel.Escritorio.Solicitacoes.Financeiro.store') }}" method="POST" role="create"  enctype="multipart/form-data">
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

                   <div class="container" style="border: 1px solid #BBBBBB; border-radius: 10px;">
                     @foreach($datas as $data)
                     <h5 class="card-title" style="font-size: 11px;">Produto: {{$data->ProdutoDescricao}} - Quantidade: {{$data->ProdutoQuantidade}} - Área: {{$data->AreaDescricao}} - Setor: {{$data->SetorDescricao}}</h5>


                        <input type="hidden" name="produto_id[]" value="{{$data->ProdutoID}}">
                        <input type="hidden" name="produto_setor[]" value="{{$data->SetorCodigo}}">
                     
                  
                        <div class="row">


                        <!--Loop Fornecedor--> 

                        <div id="{{$data->FornecedorID}}" class="container" style="border: 1px solid #BBBBBB; border-radius: 10px;">

                        <div class="row">

                        <div style="margin-left: 1055px;">
                        <p>
                        <label>
                        <input type="checkbox" name="fornecedorescolhido[]" value="{{$data->FornecedorID}}" class="filled-in" />
                        <span style="font-size: 10px;">Selecionar fornecedor</span>
                        </label>
                        </p>
                        </div>

                        <div class="col s2">
                              <span style="font-size: 11px;">Fornecedor:</span>
                              <input style="font-size: 10px;" placeholder="Razão sócial..." id="fornecedor_nome"
                              name="fornecedor_nome[]" value="{{$data->FornecedorNome}}" type="text" class="validate" readonly required>
                        </div>
                         
                        <div class="col s2" style="font-size: 11px;">
                              <span style="font-size: 11px;">CPF/CNPJ:</span>
                              <input style="font-size: 10px;" id="cpf_cnpj" value="{{$data->FornecedorCodigo}}" name="fornecedor_cpf_cnpj[]" type="text" readonly class="validate" required>
                        </div>

                        <div class="col s2" style="font-size: 11px;">
                              <span style="font-size: 11px;">Contato:</span>
                              <input style="font-size: 10px;" value="{{$data->FornecedorContato}}" readonly id="contato" name="fornecedor_contato[]" onkeypress="mask(this, mphone);" onblur="mask(this, mphone);" type="text" class="validate" required>
                        </div>

                        <div class="col s1" >
                              <span style="font-size: 11px;">Prazo (Dias):</span>
                              <input style="font-size: 10px;" id="prazo" value="{{$data->FornecedorPrazo}}" readonly name="fornecedor_prazo[]" type="number" class="validate" required>
                        </div>

                        <div class="col s2">
                              <span style="font-size: 11px;">Forma de pagamento:</span>
                              <input style="font-size: 10px;" id="fornecedor_formapagamento" readonly value="{{$data->formapagamento}}" name="fornecedor_formapagamento[]" type="text" class="validate">
                        </div>


                        <div class="col s1">
                              <span style="font-size: 11px;">Valor:</span>
                              <input style="font-size: 10px;" id="valor" readonly value="R$ <?php echo number_format($data->ValorUnitario,2,",",".") ?>" name="fornecedor_valornutario[]" pattern="(?:\.|,|[0-9])*" onKeyPress="return(moeda2(this,'.',',',event))" type="text" class="validate">
                        </div>

                        <div class="col s1" id="div_parcelas">
                              <span style="font-size: 11px;">Parcelas:</span>
                              <input style="font-size: 10px;" id="parcelas" readonly value="{{$data->Parcelas}}" name="fornecedor_parcelas[]" type="number" class="validate">
                        </div>

                        </div>

                        <div class="row">

                        <div class="col s1" id="div_frete_cartao">
                              <span style="font-size: 11px;">Valor frete:</span>
                              <input style="font-size: 10px;" id="frete" readonly value="R$ <?php echo number_format($data->ValorFrete,2,",",".") ?>" name="fornecedor_valorfrete[]" type="text" class="validate" pattern="(?:\.|,|[0-9])*" onKeyPress="return(moeda2(this,'.',',',event))">
                        </div>

                        <div class="col s1" id="div_total">
                              <span style="font-size: 11px;">Total:</span>
                              <input style="font-size: 10px;" id="total" readonly value="R$ <?php echo number_format($data->ValorTotal,2,",",".") ?>" name="fornecedor_valortotal[]"  type="text" pattern="(?:\.|,|[0-9])*" onKeyPress="return(moeda2(this,'.',',',event))" class="validate">
                        </div>

                        <div class="col s3" id="div_total">
                              <span style="font-size: 11px;">Quantidade de compras realizadas:</span>
                              <input style="font-size: 10px;" id="fornecedor_quantidadecompras" readonly value="{{$data->QuantidadeCompras}}" name="fornecedor_quantidadecomprasl[]"  type="text" pattern="(?:\.|,|[0-9])*" class="validate">
                        </div>

                        <div class="col s2" id="div_total">
                              <span style="font-size: 11px;">Data da última compra:</span>
                              @if($data->DataUltimaCompra == null)
                              <input style="font-size: 10px;color:green" id="fornecedor_dataultimacompra" readonly value="Primeira compra"  type="text"  class="validate">
                              @else 
                              <input style="font-size: 10px;" id="fornecedor_dataultimacompra" readonly value="{{ date('d/m/Y H:i:s', strtotime($data->DataUltimaCompra)) }}" name="fornecedor_dataultimacompra[]"  type="text"  class="validate">
                              @endif
                        </div>

                        </div>
                        </div>
                        <br>
                        <!--Fim Fornecedor  --> 

                        </div>

                        @endforeach

                        </div>

                        <br>

              
                        <br>

                        </div>


                    <button id="button_enviar" class="btn waves-light right align" style="margin-top: -60px; margin-right: 235px;background-color: red;font-size: 11px;" name="acao" value="reprovar" type="submit">Reprovar solicitação
                        <i class="material-icons left">close</i>
                    </button>

                     <button id="btnsubmit"  disabled class="btn waves-light right align" style="margin-top: -60px; margin-right: 25px;background-color: green;font-size: 11px;" name="acao" value="aprovar" onClick="abremodalconfirmando();" type="button">Revisar formulário
                        <i class="material-icons left">check</i>
                    </button>

                  </div>

                        <br>

                      
                        </div>
                    </form>
                </div>
            </div>
          </div>
      </div>


      <div id="modalconfirmacao" class="modal" style="width: 500px;">

<div id="loadingdiv3" style="display:none">
  <div style="height: 50px;margin-top: calc(50vh - 150px);margin-left: calc(50vw - 600px);width: 180px;">
              <img style="width: 100px;margin-left:120px;margin-top:-70px;" src="{{URL::asset('/public/imgs/loading.gif')}}"/>
</div>
</div>

    <div id="corpodiv3">
    <div class="modal-content">
      <center><p style="font-size: 18px;">Deseja confirmar a solicitação?</p></center>
    </div>
    <div class="modal-footer">
      <a  class="modal-action  waves-effect waves-red btn-flat " style="background-color: red;color:white;font-size:11px;" onClick="nao();"><i class="material-icons left">close</i>Não</a>
      <a  class="modal-action  waves-effect waves-green btn-flat " style="background-color: green;color:white;font-size:11px;" onClick="sim();"><i class="material-icons left">check</i>Sim</a>
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

$('input[type="checkbox"]').on('click',function () {

        var produto_id = $(this).data("id");
        var fornecedor_id = $(this).val();


        //Habilita botão
        $("#btnsubmit").prop("disabled",false);



        // Verifica se algum fornecedor deste produto já foi marcado, se sim desmarca 
        // if ($(':checkbox[data-id='+produto_id+']').prop("checked")) {
        // $("[id='"+fornecedor_id+"']").css("border-color","#28a745");
        // } else {
        // $("[id='"+fornecedor_id+"']").css("border-color","#BBBBBB");
        // }


})

      </script>


<script>
  function abremodalconfirmando() {

    $('.modal').modal();
    $('#modalconfirmacao').modal('open');
         
  }    
</script>

<script>
  function nao() {

    window.location.reload(true);
         
  }    
</script>

<script>
  function sim() {

    $('.modal').css('background-color', 'transparent');
    document.getElementById("loadingdiv3").style.display = "";
    document.getElementById("corpodiv3").style.display = "none";
    document.getElementById("form").submit();
         
  }    
</script>
      
   

   </body>
</html>