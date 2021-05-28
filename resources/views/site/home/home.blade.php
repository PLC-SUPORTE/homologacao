<!DOCTYPE html>
<html class="loading" lang="pt-br" data-textdirection="ltr">
    <head>
        <meta http-equiv="Content-Language" content="pt-br">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <meta name="description" content="Cadastro das informações coletadas na pesquisa patrimonial do sistema Portal PLC.">
        <meta name="keywords" content="pesquisapatrimonial, pesquisa, patrimonial, cadastro, plc, portal, portela lima labato colen, bh, belo horizonte">
        <meta name="author" content="Portal PL&C">
        <title>Home Page | Portal PL&C</title>
        <link rel="apple-touch-icon" href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/images/favicon/apple-touch-icon-152x152.png">
        <link rel="shortcut icon" href="{{ asset('/public/assets/imgs/casinha.png')}}" />
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('/public/materialize/css/vendors.min.css') }}">
        <link rel="stylesheet" href="{{ asset('/public/materialize/css/jquery.dataTables.min.css') }}">
        <link rel="stylesheet" href="{{ asset('/public/materialize/css/responsive.dataTables.min.css') }}">
        <link rel="stylesheet" href="{{ asset('/public/materialize/css/materialize.min.css') }}">
        <link rel="stylesheet" href="{{ asset('/public/materialize/css/style.min.css') }}">
        <script src="//cdn.jsdelivr.net/npm/afterglowplayer@1.x"></script>
     </head>

   <style>
      .btn-close{
         margin-top: -17px;
         float: right;
         margin-right: 0 !important;
      }

      .custom-collapse .collapsible-body{
         padding: 0 !important;
      }

      .custom-collapse .collapsible-body .custom-collapse{
         margin: 0;
         box-shadow: none;
         border: none;
      }

      .custom-collapse .collapsible-body .custom-collapse h6{
         font-weight: bold;
      }

      .custom-collapse .collapsible-header{
         padding: 5px 10px;
         font-weight: bold;
         /* background-color: #eaebf3; */
      }

      .custom-collapse .collapsible-header h6{
         font-size: 13px !important;
         font-weight: bold;
      }

      .modal::-webkit-scrollbar-track {
         background-color: #F4F4F4;
      }
      .modal::-webkit-scrollbar {
         width: 6px;
         background: #F4F4F4;
      }
      .modal::-webkit-scrollbar-thumb {
         background: #dad7d7;
      }
   </style>

     <body class="vertical-layout vertical-menu-collapsible page-header-dark vertical-modern-menu preload-transitions 2-columns" onblur="pausevideo();"
      data-open="click" data-menu="vertical-modern-menu" data-col="2-columns" style="overflow: auto; background-image: url(./public/imgs/home.jpg);">

      <header class="page-topbar" id="header">
      <div class="navbar navbar-fixed"> 
        <nav class="navbar-main navbar-color nav-collapsible sideNav-lock navbar-dark gradient-shadow" style="background-color: gray;">
          <div class="nav-wrapper">

          <h5 class="breadcrumbs-title mt-0 mb-0 display-inline hide-on-small-and-down">
            <img src="{{url('./public/imgs/logo_branca.png')}}" alt="Smiley face" height="50" width="190">
         </h5>

            <ul class="navbar-list right">
              <li><a class="waves-effect waves-block waves-light notification-button" href="javascript:void(0);" data-target="notifications-dropdown"><i class="material-icons">notifications_none<small class="notification-badge">{{$totalNotificacaoAbertas}}</small></i></a></li>
              <li><a class="waves-effect waves-block waves-light profile-button" href="javascript:void(0);" data-target="profile-dropdown"><span class="avatar-status avatar-online"><img src="{{ asset('/public/AdminLTE/img/Avatar/img.png') }}" alt="avatar"><i></i></span></a></li>
            </ul>

            <!-- notifications-dropdown-->
            <ul class="dropdown-content" id="notifications-dropdown">
                     <li>
                        <h6>Notificações<span class="new badge">{{$totalNotificacaoAbertas}}</span></h6>
                     </li>
                     <li class="divider"></li>
                     @foreach($notificacoes as $notificacao)
                     <li><a class="black-text" href="#!" style="font-size: 10px;"><span class="material-icons icon-bg-circle deep-orange small">today</span>{{$notificacao->obs}}</a>
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



    <div class="col s12" style="margin-left: 40px;overflow:hidden;">
      <div class="row">
         <div class="col s6 m6 xl6">
            <center>
               <h4 class="card-title" style="margin-bottom: 0px; color: white;font-family: 'Gill Sans', 'Gill Sans MT', 'Calibri', 'Trebuchet MS', sans-serif;font-size: 14px; padding-right: 100px;">ESCOLHA O TIPO DE SERVIÇO ABAIXO:</h4>
            </center>
         </div>
         <div class="col s6 m6 xl6">
            <center>
               <p id="text-comunicados" style="margin-bottom: 0; color: white; font-family: 'Gill Sans', 'Gill Sans MT', 'Calibri', 'Trebuchet MS', sans-serif;font-size: 14px; text-align: end; margin-right: 50px;">COMUNICADOS INTERNOS PL&C: 
                  <a style="color: white; text-align: center;" href="{{ route('Painel.Marketing.index') }}"><span class="dot" id="dot-lido">{{$totalComunicadosLidos}}</span> </a>
                  <i style="font-size: 12px;">Lidos</i>
                  <a style="color: white; text-align: center;" href="{{ route('Painel.Marketing.index') }}"><span class="dot2">{{$totalComunicadosAbertos}}</span></a>
                  <i style="font-size: 12px;">Não lidos</i>
               </p>
            </center>
         </div>
      </div>

      <div class="col s12 m6 xl4">
      <div class="row">

        <div class="col s6 m6 xl3">
        <a class="modal-trigger" href="#modalControladoria">
        <div class="card gradient-shadow border-radius-3" style="width:80%;margin-top:5px;border-radius: 20px; background-color: rgba(255,255,255,.1);">
            <div class="card-content center" style="padding: 14px">
              <img src="{{url('/public/imgs/profiles/CONTROLADORIA.png')}}" alt="images" width="26%!important" />
              <br>
              <span style="color: white; font-family: 'Gill Sans', 'Gill Sans MT', 'Calibri', 'Trebuchet MS', sans-serif; font-size: 9px;">CONTROLADORIA</span>
            </div>
          </div>
        </a>  
        </div>

        <div class="col s6 m6 xl3">
        <a class="modal-trigger" href="#modalCorrespondente">
        <div class="card gradient-shadow border-radius-3" style="width:80%;margin-top:5px;margin-left:-50px;border-radius: 20px; background-color: rgba(255,255,255,.1);">
        <div class="card-content center" style="padding: 14px">
              <img src="{{url('/public/imgs/profiles/CORRESPONDENTE.png')}}" alt="images" width="26%!important" />
              <br>
              <span style="color: white; font-family: 'Gill Sans', 'Gill Sans MT', 'Calibri', 'Trebuchet MS', sans-serif; font-size: 9px;">CORRESPONDENTE</span>
            </div>
          </div>
        </a>  
        </div>

        </div>
        <div class="row">


        <div class="col s6 m6 xl3">
        <a href="#modalDPRH"  class="modal-trigger">
        <div class="card gradient-shadow border-radius-3" style="width:80%;margin-top:5px;border-radius: 20px; background-color: rgba(255,255,255,.1);">
        <div class="card-content center" style="padding: 14px">
            <img src="{{url('/public/imgs/profiles/DPRH.png')}}" alt="images" width="26%!important" />
            <br>
            <span style="color: white; font-family: 'Gill Sans', 'Gill Sans MT', 'Calibri', 'Trebuchet MS', sans-serif; font-size: 9px;">DP&RH</span>
            </div>
          </div>
        </a>  

          <!-- MODAL DP&RH -->
          <div id="modalDPRH" class="modal" style="width: 80% !important;height: 90% !important;">
            <div class="modal-content">
               <h6 style="padding: 0 1rem;"><b>Card - DP & RH</b>
               <button type="button" class="btn btn-close waves-effect mr-sm-1 mr-2 modal-close red">
                  <i class="material-icons">close</i>
               </button>
               </h6>
               
               <div class="col s12 m6 l6">
                  <ul class="collapsible custom-collapse">
                     <li class="active">
                        <div class="collapsible-header" name="dp_video" data-father="dp_video">
                           <h6 style="font-size: 11px; width: 100%;"><i class="material-icons" style="vertical-align: -6px;">video_library</i>&nbsp;
                              Vídeos - selecione uma opção abaixo:
                              <i class="material-icons" data-father="dp_video" data-type="mr" id="i-mr-dp_video" style="vertical-align: -6px; float: right; display: none;">expand_more</i>
                              <i class="material-icons" data-father="dp_video" data-type="ls" id="i-ls-dp_video" style="vertical-align: -6px; float: right;">expand_less</i>
                           </h6>
                        </div>
                        <div class="collapsible-body">
                           <ul class="collection">
                              <!-- <li class="collection-item"><a class="modal-trigger" href="#modalVideoMeritocracia" onclick="abreModalVideoMeritocracia();" style="font-size: 12px;color: black; margin-left: -5px;"><i class="material-icons" style="vertical-align: -6px; font-size: 20px;">videocam</i>&nbsp;&nbsp;Procedimento para acessar a meritocracia</a></li> -->
                           </ul>
                        </div>
                     </li>
                  </ul>
               </div>

               <div class="col s12 m6 l6">
                  <ul class="collapsible custom-collapse">
                     <li class="active">
                        <div class="collapsible-header" name="dp_links" data-father="dp_links">
                           <h6 style="font-size: 11px; width: 100%;"><i class="material-icons" style="vertical-align: -6px;">attach_file</i>&nbsp;
                              Links - selecione uma opção abaixo:
                              <i class="material-icons" data-father="dp_links" data-type="mr" id="i-mr-dp_links" style="vertical-align: -6px; float: right; display: none;">expand_more</i>
                              <i class="material-icons" data-father="dp_links" data-type="ls" id="i-ls-dp_links" style="vertical-align: -6px; float: right;">expand_less</i>
                           </h6>
                        </div>
                        <div class="collapsible-body">
                           <ul class="collection">
                              @can('users')
                              <li class="collection-item"><a style="color: black;font-size: 10px;" href="{{ route('Painel.DPRH.Ferias.Advogado.index') }}"><i class="material-icons"  style="vertical-align: -6px;">calendar_today</i>&nbsp;&nbsp;Agendamento de férias</a></li>
                              <li class="collection-item"><a style="color: black;font-size: 10px;" href="{{ route('Painel.DPRH.Licenca.Advogado.index') }}"><i class="material-icons"  style="vertical-align: -6px;">person_outline</i>&nbsp;&nbsp;Licença</a></li>
                              <li class="collection-item"><a style="color: black;font-size: 10px;" href="{{ route('Painel.DPRH.Progressao.Advogado.index') }}"><i class="material-icons"  style="vertical-align: -6px;">person_outline</i>&nbsp;&nbsp;Progressão</a></li>
                              @endcan
                              <!--Fim Advogado --> 

                              @can('subcoordenador_contratacao')
                              <li class="collection-item"><a style="color: black;font-size: 10px;" href="{{ route('Painel.Contratacao.SubCoordenador.index') }}"><i class="material-icons"  style="vertical-align: -6px;">person_outline</i>&nbsp;&nbsp;Nova vaga/Substituição</a></li>
                              <li class="collection-item"><a style="color: black;font-size: 10px;" href="{{ route('Painel.DPRH.Ferias.SubCoordenador.index') }}"><i class="material-icons"  style="vertical-align: -6px;">calendar_today</i>&nbsp;&nbsp;Agendamento de férias</a></li>
                              <li class="collection-item"><a style="color: black;font-size: 10px;" href="{{ route('Painel.DPRH.Desligamento.SubCoordenador.index') }}"><i class="material-icons"  style="vertical-align: -6px;">person_outline</i>&nbsp;&nbsp;Desligamento</a></li>
                              @endcan

                              @can('superintendente_contratacao')
                              <li class="collection-item"><a style="color: black;font-size: 10px;" href="{{ route('Painel.Contratacao.Superintendente.index') }}"><i class="material-icons"  style="vertical-align: -6px;">person_outline</i>&nbsp;&nbsp;Nova vaga/Substituição</a></li>
                              <li class="collection-item"><a style="color: black;font-size: 10px;" href="{{ route('Painel.DPRH.Ferias.Superintendente.index') }}"><i class="material-icons"  style="vertical-align: -6px;">calendar_today</i>&nbsp;&nbsp;Agendamento de férias</a></li>
                              @endcan

                              <!-- @can('gerente_contratacao')
                              <li class="collection-item"><a style="color: black;font-size: 10px;" href="{{ route('Painel.Contratacao.Gerente.index') }}"><i class="material-icons"  style="vertical-align: -6px;">person_outline</i>&nbsp;&nbsp;Nova vaga/Substituição</a></li>
                              @endcan -->

                              @can('coordenador_contratacao')
                              <li class="collection-item"><a style="color: black;font-size: 10px;" href="{{ route('Painel.Contratacao.Coordenador.index') }}"><i class="material-icons"  style="vertical-align: -6px;">person_outline</i>&nbsp;&nbsp;Nova vaga/Substituição</a></li>
                              <li class="collection-item"><a style="color: black;font-size: 10px;" href="{{ route('Painel.DPRH.Ferias.Coordenador.index') }}"><i class="material-icons"  style="vertical-align: -6px;">calendar_today</i>&nbsp;&nbsp;Férias</a></li>
                              @endcan

                              @can('rhdp')
                              <li class="collection-item"><a style="color: black;font-size: 10px;" href="{{ route('Painel.Contratacao.RH.index') }}"><i class="material-icons"  style="vertical-align: -6px;">person_outline</i>&nbsp;&nbsp;Nova vaga/Substituição</a></li>
                              <li class="collection-item"><a style="color: black;font-size: 10px;" href="{{ route('Painel.DPRH.Ferias.RH.index') }}"><i class="material-icons"  style="vertical-align: -6px;">calendar_today</i>&nbsp;&nbsp;Agendamento de férias</a></li>
                              @endcan
                              <!--Fim Projeto Contratação --> 
                           </ul>
                        </div>
                     </li>
                  </ul>
               </div>

          {{-- <ul class="collection" style="margin-left: 100px; width: 800px;">
            <center>
             <li class="collection-item" style="font-size: 10px; color: black;"><i class="material-icons"  style="vertical-align: -6px; color: red;">block</i>&nbsp;&nbsp;Módulo em desenvolvimento</li>
            </center>
         </ul> --}}

            </div>
         </div>

         <style>
            #modalDPRH{
            width: 90% !important;
            height: 90% !important;
            }
         </style>

        </div>


        <div class="col s6 m6 xl3">
        <a href="#modalFinanceiro" class="modal-trigger">
        <div class="card gradient-shadow border-radius-3" style="width:80%;margin-top:5px;margin-left:-50px;border-radius: 20px; background-color: rgba(255,255,255,.1);">
        <div class="card-content center" style="padding: 14px">
            <img src="{{url('/public/imgs/profiles/FINANCEIRO.png')}}" alt="images" width="26%!important" />
            <br>
            <span style="color: white; font-family: 'Gill Sans', 'Gill Sans MT', 'Calibri', 'Trebuchet MS', sans-serif; font-size: 9px;">FINANCEIRO</span>
            </div>
          </div>
        </a>  
        </div>

        </div>

        <div class="row">

        <div class="col s6 m6 xl3">
        <a href="#modalMarketing" class="modal-trigger">
        <div class="card gradient-shadow border-radius-3" style="width:80%;margin-top:5px;border-radius: 20px; background-color: rgba(255,255,255,.1);">
        <div class="card-content center" style="padding: 14px">
            <img src="{{url('/public/imgs/profiles/MARKETING.png')}}" alt="images" width="26%!important" />
            <br>
            <span style="color: white; font-family: 'Gill Sans', 'Gill Sans MT', 'Calibri', 'Trebuchet MS', sans-serif; font-size: 9px;">MARKETING</span>
            </div>
          </div>
        </a>  


        <div id="modalMarketing" class="modal" class="modal" style="width: 80% !important;height: 90% !important;">
         <div class="modal-content">

         <h6 style="padding: 0 1rem;"><b>Card - Marketing</b>
            <button type="button" class="btn btn-close waves-effect mr-sm-1 mr-2 modal-close red">
               <i class="material-icons">close</i>
            </button>
         </h6>

         <div class="container" style="padding: 0 1rem;">
            <ul class="collapsible custom-collapse">
               <li class="active">
                  <div class="collapsible-header" name="comunicados" data-father="comunicados">
                     <h6 style="font-size: 11px; width: 100%;"><i class="material-icons" style="vertical-align: -6px;">folder_open</i>&nbsp;
                        Comunicados:
                        <i class="material-icons" data-father="comunicados" data-type="mr" id="i-mr-comunicados" style="vertical-align: -6px; float: right; display: none;">expand_more</i>
                        <i class="material-icons" data-father="comunicados" data-type="ls" id="i-ls-comunicados" style="vertical-align: -6px; float: right;">expand_less</i>
                     </h6>
                  </div>
                  <div class="collapsible-body">
                     <ul class="collection">
                        <!-- MARKETING -->
                        @can('marketing')
                           <li class="collection-item"><a aria-hidden="true" style="font-size: 10px; color: black;" href="{{ route('Painel.Marketing.create') }}"><i class="material-icons" style="vertical-align: -6px;">add</i>&nbsp;&nbsp;Novo Comunicado</a></li>
                           <li class="collection-item"><a aria-hidden="true" style="font-size: 10px; color: black;" href="{{ route('Painel.Marketing.index') }}"><i class="material-icons" style="vertical-align: -6px;">folder_open</i>&nbsp;&nbsp;Comunicados</a></li>
                           <li class="collection-item"><a aria-hidden="true" style="font-size: 10px; color: black;" href="{{ route('Painel.Marketing.informativos') }}"><i class="material-icons" style="vertical-align: -6px;">info</i>&nbsp;&nbsp;Informativos</a></li>
                           <!-- <li class="collection-item"><a aria-hidden="true" style="font-size: 10px; color: black;" href="{{ route('Painel.Marketing.Sorteio.index') }}"><i class="material-icons" style="vertical-align: -6px;">info</i>&nbsp;&nbsp;Sorteios</a></li> -->
                        @endcan
                        <!-- FIM MARKETING -->

                        <!-- FIM LISTAGEM COMUNICADOS -->
                        @can('listagem_comunicados')
                           <li class="collection-item"><a aria-hidden="true" style="font-size: 10px; color: black;" href="{{ route('Painel.Marketing.index') }}"><i class="material-icons" style="vertical-align: -6px;">folder_open</i>&nbsp;&nbsp;Comunicados</a></li>
                           <li class="collection-item"><a aria-hidden="true" style="font-size: 10px; color: black;" href="{{ route('Painel.Marketing.informativos') }}"><i class="material-icons" style="vertical-align: -6px;">info</i>&nbsp;&nbsp;Informativos</a></li>
                        @endcan
                        <!-- FIM LISTAGEM COMUNICADOS -->
                     </ul>
                  </div>
               </li>
            </ul>
         </div>

         </div>
       </div>

       <script>
         function abreModalMarketing(){
            $('.modal').modal();
            $('#modalMarketing').modal('open');
         }
     </script>


        </div>

        <div class="col s6 m6 xl3">
        <a href="#modalTI" class="modal-trigger">
        <div class="card gradient-shadow border-radius-3" style="width:80%;margin-top:5px;margin-left:-50px;border-radius: 20px; background-color: rgba(255,255,255,.1);">
        <div class="card-content center" style="padding: 14px">
            <img src="{{url('/public/imgs/profiles/TI.png')}}" alt="images" width="26%!important" />
            <br>
            <span style="color: white; font-family: 'Gill Sans', 'Gill Sans MT', 'Calibri', 'Trebuchet MS', sans-serif; font-size: 9px;">T.I</span>
            </div>
          </div>
        </a>  


        <div id="modalTI" class="modal" style="width: 80% !important;height: 90% !important;">
               <div class="modal-content">
                  <h6 style="padding: 0 1rem;"><b>Card - T.I</b>
                     <button type="button" class="btn btn-close waves-effect mr-sm-1 mr-2 modal-close red">
                        <i class="material-icons">close</i>
                     </button>
                  </h6>
                  <div class="row">
                     <div class="col s12 m6 l6">
                        <!-- <ul class="collection with-header">
                           <li class="collection-header">
                              <h6 style="font-size: 11px;"><i class="material-icons" style="vertical-align: -6px;">video_library</i>&nbsp;Vídeos - selecione uma opção abaixo:</h6>
                           </li>
                           <li class="collection-item"><a class="modal-trigger" href="#modalVideoMeritocracia" onclick="abreModalVideoMeritocracia();" style="font-size: 10px;color: black; margin-left: -5px;"><i class="material-icons" style="vertical-align: -6px; font-size: 20px;">videocam</i>&nbsp;&nbsp;Procedimento para acessar a meritocracia</a></li>
                        </ul> -->

                        <ul class="collapsible custom-collapse">
                           <li class="active">
                              <div class="collapsible-header" name="videos" data-father="video">
                                 <h6 style="font-size: 11px; width: 100%;"><i class="material-icons" style="vertical-align: -6px;">video_library</i>&nbsp;
                                    Vídeos - selecione uma opção abaixo:
                                    <i class="material-icons" data-father="video" data-type="mr" id="i-mr-videos" style="vertical-align: -6px; float: right; display: none;">expand_more</i>
                                    <i class="material-icons" data-father="video" data-type="ls" id="i-ls-videos" style="vertical-align: -6px; float: right;">expand_less</i>
                                 </h6>
                              </div>
                              <div class="collapsible-body ">
                                 <ul class="collection">
                                    <li class="collection-item">
                                       <a class="modal-trigger" href="#modalVideoTI"  style="font-size: 10px;color: black; margin-left: -5px;">
                                          <i class="material-icons" style="vertical-align: -6px; font-size: 20px;">videocam</i>&nbsp;&nbsp;
                                          Ferramenta de indexação de documentos no GED Advwin
                                       </a>
                                    </li>
                                 </ul>
                              </div>
                           </li>
                        </ul>
                     </div>

                     <div class="col s12 m6 l6">
                        <ul class="collapsible custom-collapse">
                           <li class="active">
                              <div class="collapsible-header" name="links" data-father="financeiro">
                                 <h6 style="font-size: 11px; width: 100%;"><i class="material-icons" style="vertical-align: -6px;">attach_file</i>&nbsp;Links - selecione uma opção abaixo:
                                    <i class="material-icons" data-father="financeiro" data-type="mr" id="i-mr-links" style="vertical-align: -6px; float: right; display: none;">expand_more</i>
                                    <i class="material-icons" data-father="financeiro" data-type="ls" id="i-ls-links" style="vertical-align: -6px; float: right;">expand_less</i>
                                 </h6>
                              </div>
                              <div class="collapsible-body">
                                 <ul class="collapsible custom-collapse">

                                @can('users')
                           <li class="active">
                              <div class="collapsible-header" name="financeiro" data-father="links">
                                 <h6 style="font-size: 11px; width: 100%;"><i class="material-icons" style="vertical-align: -6px;">attach_money</i>&nbsp;
                                    T.I:
                                    <i class="material-icons" data-father="links" data-type="mr" id="i-mr-financeiro" style="vertical-align: -6px; float: right; display: none;">expand_more</i>
                                    <i class="material-icons" data-father="links" data-type="ls" id="i-ls-financeiro" style="vertical-align: -6px; float: right;">expand_less</i>
                                 </h6>
                              </div>
                              <div class="collapsible-body">
                                 <ul class="collection">
                                 <li class="collection-item"><a aria-hidden="true" style="font-size: 10px; color: black;" href="{{ route('Painel.TI.chat.index') }}"><i class="material-icons" style="vertical-align: -6px;">chat</i>&nbsp;&nbsp;Desenvolvimento chat</a></li>
                                 <li class="collection-item"><a aria-hidden="true" style="font-size: 10px; color: black;" href="{{ route('Painel.TI.users.index') }}"><i class="material-icons" style="vertical-align: -6px;">person</i>&nbsp;&nbsp;Gerenciar usuários</a></li>
                                 <li class="collection-item"><a aria-hidden="true" style="font-size: 10px; color: black;" href="{{url('/painel/perfis')}}"><i class="material-icons" style="vertical-align: -6px;">check</i>&nbsp;&nbsp;Gerenciar profiles</a></li>
                                 <li class="collection-item"><a aria-hidden="true" style="font-size: 10px; color: black;" href="{{url('/painel/setorcusto')}}"><i class="material-icons" style="vertical-align: -6px;">check</i>&nbsp;&nbsp;Gerenciar setor de custo</a></li>
                                 <li class="collection-item"><a aria-hidden="true" style="font-size: 10px; color: black;" href="{{url('/painel/permissoes')}}"><i class="material-icons" style="vertical-align: -6px;">check</i>&nbsp;&nbsp;Gerenciar permissões</a></li>
                                 <li class="collection-item"><a aria-hidden="true" style="font-size: 10px; color: black;" href="{{ route('Painel.TI.tarefasagendadas_index') }}"><i class="material-icons" style="vertical-align: -6px;">analytics</i>&nbsp;&nbsp;Tarefas automatizadas</a></li>
                                 <li class="collection-item"><a aria-hidden="true" style="font-size: 10px; color: black;" href="{{ route('Painel.Financeiro.telefones') }}"><i class="material-icons" style="vertical-align: -6px;">phone</i>&nbsp;&nbsp;Telefone Corporativos</a></li>
                                 </ul>
                              </div>
                           </li>
                                @endcan

                        
                           
                                 </ul>
                              </div>
                           </li>
                        </ul>
                     </div>
                  </div>

                  <div id="modalVideoTI" class="modal">
               <div class="modal-content">
                  <center>
                     <div class="video-container">
                        <video class="afterglow" id="videoti_indexacaoarquivos" width="560" height="315"  controls  onblur="pausevideo();">
                           <source src="{{ url('storage/treinamentos/videoti_indexacaoarquivos.mp4') }}" type="video/mp4" />
                        </video>
                     </div>
                  </center>
               </div>
            </div>

       <script>
         function abreModalTI(){
            $('.modal').modal();
            $('#modalTI').modal('open');
         }
     </script>



         </div>
       </div>

        </div>

        </div>

        <div class="row">

        <div class="col s6 m6 xl3">
        <a href="#modalPesquisaPatrimonial" class="modal-trigger">
        <div class="card gradient-shadow border-radius-3" style="width:80%;margin-top:5px;border-radius: 20px; background-color: rgba(255,255,255,.1);">
        <div class="card-content center" style="padding: 14px">
            <img src="{{url('/public/imgs/profiles/pesquisa.png')}}" alt="images" width="26%!important" />
            <br>
            <span style="color: white; font-family: 'Gill Sans', 'Gill Sans MT', 'Calibri', 'Trebuchet MS', sans-serif; font-size: 9px;">PESQUISA PATRIMONIAL</span>
            </div>
          </div>
        </a>


        <div id="modalPesquisaPatrimonial" class="modal" style="width: 80% !important;height: 90% !important;">
               <div class="modal-content">
                  <h6 style="padding: 0 1rem;"><b>Card - Pesquisa patrimonial</b>
                     <button type="button" class="btn btn-close waves-effect mr-sm-1 mr-2 modal-close red">
                        <i class="material-icons">close</i>
                     </button>
                  </h6>
                  <div class="row">
                     <div class="col s12 m6 l6">
                        <!-- <ul class="collection with-header">
                           <li class="collection-header">
                              <h6 style="font-size: 11px;"><i class="material-icons" style="vertical-align: -6px;">video_library</i>&nbsp;Vídeos - selecione uma opção abaixo:</h6>
                           </li>
                           <li class="collection-item"><a class="modal-trigger" href="#modalVideoMeritocracia" onclick="abreModalVideoMeritocracia();" style="font-size: 10px;color: black; margin-left: -5px;"><i class="material-icons" style="vertical-align: -6px; font-size: 20px;">videocam</i>&nbsp;&nbsp;Procedimento para acessar a meritocracia</a></li>
                        </ul> -->

                        <ul class="collapsible custom-collapse">
                           <li class="active">
                              <div class="collapsible-header" name="videos" data-father="video">
                                 <h6 style="font-size: 11px; width: 100%;"><i class="material-icons" style="vertical-align: -6px;">video_library</i>&nbsp;
                                    Vídeos - selecione uma opção abaixo:
                                    <i class="material-icons" data-father="video" data-type="mr" id="i-mr-videos" style="vertical-align: -6px; float: right; display: none;">expand_more</i>
                                    <i class="material-icons" data-father="video" data-type="ls" id="i-ls-videos" style="vertical-align: -6px; float: right;">expand_less</i>
                                 </h6>
                              </div>
                              <div class="collapsible-body ">
                                 <ul class="collection">
                                    <li class="collection-item">
                                       <a class="modal-trigger" href="#modalVideoPesquisaPatrimonial"  style="font-size: 10px;color: black; margin-left: -5px;">
                                          <i class="material-icons" style="vertical-align: -6px; font-size: 20px;">videocam</i>&nbsp;&nbsp;
                                          Procedimento para nova solicitação de pesquisa patrimonial
                                       </a>
                                    </li>
                                 </ul>
                              </div>
                           </li>
                        </ul>
                     </div>

                     <div class="col s12 m6 l6">
                        <ul class="collapsible custom-collapse">
                           <li class="active">
                              <div class="collapsible-header" name="links" data-father="financeiro">
                                 <h6 style="font-size: 11px; width: 100%;"><i class="material-icons" style="vertical-align: -6px;">attach_file</i>&nbsp;Links - selecione uma opção abaixo:
                                    <i class="material-icons" data-father="financeiro" data-type="mr" id="i-mr-links" style="vertical-align: -6px; float: right; display: none;">expand_more</i>
                                    <i class="material-icons" data-father="financeiro" data-type="ls" id="i-ls-links" style="vertical-align: -6px; float: right;">expand_less</i>
                                 </h6>
                              </div>
                              <div class="collapsible-body">
                                 <ul class="collapsible custom-collapse">

                                @can('financeiro_pesquisapatrimonial')
                           <li class="active">
                              <div class="collapsible-header" name="financeiro" data-father="links">
                                 <h6 style="font-size: 11px; width: 100%;"><i class="material-icons" style="vertical-align: -6px;">attach_money</i>&nbsp;
                                    Financeiro:
                                    <i class="material-icons" data-father="links" data-type="mr" id="i-mr-financeiro" style="vertical-align: -6px; float: right; display: none;">expand_more</i>
                                    <i class="material-icons" data-father="links" data-type="ls" id="i-ls-financeiro" style="vertical-align: -6px; float: right;">expand_less</i>
                                 </h6>
                              </div>
                              <div class="collapsible-body">
                                 <ul class="collection">
                                    <li class="collection-item"><a style="font-size: 10px; color: black;" href="{{ route('Painel.PesquisaPatrimonial.financeiro.index') }}" data-toggle="tooltip" data-placement="top" title="Clique aqui para acessar a área do financeiro da pesquisa patrimonial." ><i class="material-icons" style="vertical-align: -6px;">list</i>&nbsp;&nbsp;Financeiro pesquisa patrimonial</a></li>
                                 </ul>
                              </div>
                           </li>
                                @endcan

                                @can('advogado')
                           <li class="active">
                              <div class="collapsible-header" name="advogado" data-father="links">
                                 <h6 style="font-size: 11px; width: 100%;"><i class="material-icons" style="vertical-align: -6px;">assignment_ind</i>&nbsp;
                                    Advogado:
                                    <i class="material-icons" data-father="links" data-type="mr" id="i-mr-advogado" style="vertical-align: -6px; float: right; display: none;">expand_more</i>
                                    <i class="material-icons" data-father="links" data-type="ls" id="i-ls-advogado" style="vertical-align: -6px; float: right;">expand_less</i>
                                 </h6>
                              </div>
                              <div class="collapsible-body">
                                 <ul class="collection">
                                    <li class="collection-item"><a aria-hidden="true" style="font-size: 10px; color: black;" href="{{ route('Painel.PesquisaPatrimonial.solicitacao.index') }}"><i class="material-icons" style="vertical-align: -6px;">search</i>&nbsp;&nbsp;Solicitação de pesquisa patrimonial</a></li>
                                 </ul>
                              </div>
                           </li>
                           @endcan
                           @can('coordenador')
                           <li class="active">
                              <div class="collapsible-header" name="coordenador" data-father="links">
                                 <h6 style="font-size: 11px; width: 100%;"><i class="material-icons" style="vertical-align: -6px;">how_to_reg</i>&nbsp;
                                    Coordenador & Subcoordenador:
                                    <i class="material-icons" data-father="links" data-type="mr" id="i-mr-coordenador" style="vertical-align: -6px; float: right; display: none;">expand_more</i>
                                    <i class="material-icons" data-father="links" data-type="ls" id="i-ls-coordenador" style="vertical-align: -6px; float: right;">expand_less</i>
                                 </h6>
                              </div>
                              <div class="collapsible-body">
                                 <ul class="collection">
                                    <li class="collection-item"><a aria-hidden="true" style="font-size: 10px; color: black;" href="{{ route('Painel.PesquisaPatrimonial.solicitacao.index') }}"><i class="material-icons" style="vertical-align: -6px;">search</i>&nbsp;&nbsp;Solicitação de pesquisa patrimonial</a></li>
                                 </ul>
                              </div>
                           </li>
                           @endcan
                           @can('nucleo_pesquisa_patrimonial')
                           <li class="active">
                              <div class="collapsible-header" name="nucleo" data-father="links">
                                 <h6 style="font-size: 11px; width: 100%;"><i class="material-icons" style="vertical-align: -6px;">how_to_reg</i>&nbsp;
                                    Núcleo Pesquisa Patrimonial:
                                    <i class="material-icons" data-father="links" data-type="mr" id="i-mr-nucleo" style="vertical-align: -6px; float: right; display: none;">expand_more</i>
                                    <i class="material-icons" data-father="links" data-type="ls" id="i-ls-nucleo" style="vertical-align: -6px; float: right;">expand_less</i>
                                 </h6>
                              </div>
                              <div class="collapsible-body">
                                 <ul class="collection">
                                    <li class="collection-item"><a style="font-size: 10px; color: black;" href="{{ route('Painel.PesquisaPatrimonial.nucleo.index') }}"><i class="material-icons" style="vertical-align: -6px;">search</i>&nbsp;&nbsp;Solicitações de pesquisa patrimonial</a></li>
                                 </ul>
                              </div>
                           </li>
                           @endcan
                           @can('supervisao_pesquisapatrimonial')
                           <li class="active">
                              <div class="collapsible-header" name="supervisor" data-father="links">
                                 <h6 style="font-size: 11px; width: 100%;"><i class="material-icons" style="vertical-align: -6px;">how_to_reg</i>&nbsp;
                                    Supervisor da Pesquisa Patrimonial
                                    <i class="material-icons" data-father="links" data-type="mr" id="i-mr-supervisor" style="vertical-align: -6px; float: right; display: none;">expand_more</i>
                                    <i class="material-icons" data-father="links" data-type="ls" id="i-ls-supervisor" style="vertical-align: -6px; float: right;">expand_less</i>
                                 </h6>
                              </div>
                              <div class="collapsible-body">
                                 <ul class="collection">
                                    <li class="collection-item"><a style="font-size: 10px; color: black;" href="{{ route('Painel.PesquisaPatrimonial.supervisao.index') }}"><i class="material-icons" style="vertical-align: -6px;">search</i>&nbsp;&nbsp;Solicitações de pesquisa patrimonial</a></li>
                                 </ul>
                              </div>
                           </li>
                           @endcan


                           
                                 </ul>
                              </div>
                           </li>
                        </ul>
                     </div>
                  </div>

            <!-- <center>
       <ul class="collection" style="margin-left: 200px; width: 300px;">
         <center>
         <li class="collection-item" style="font-size: 10px; color: black;">
            <i class="material-icons"  style="vertical-align: -6px; color: red;">block</i>
            &nbsp;&nbsp;Módulo em desenvolvimento</li>
         </center>
       </ul>
      </center> -->

         </div>
       </div>

       <div id="modalVideoPesquisaPatrimonial" class="modal">
               <div class="modal-content">
                  <center>
                     <div class="video-container">
                        <video class="afterglow" id="video4" width="560" height="315"  controls  onblur="pausevideo();">
                           <source src="{{ url('storage/treinamentos/video4.mp4') }}" type="video/mp4" />
                        </video>
                     </div>
                  </center>
               </div>
            </div>

       <script>
         function abreModalPesquisaPatrimonial(){
            $('.modal').modal();
            $('#modalPesquisaPatrimonial').modal('open');
         }
     </script>


      </div>

        <!-- <div class="col s6 m6 xl3">
        <a target="_blank" href="https://datastudio.google.com/s/k-tgiu2ouuM">
        <div class="card gradient-shadow border-radius-3" style="width:80%;margin-top:5px;margin-left:-50px;border-radius: 20px; background-color: rgba(255,255,255,.1);">
        <div class="card-content center" style="padding: 14px">
            <img src="{{url('/public/imgs/profiles/pesquisa-homeoffice.png')}}" alt="images" width="26%!important" />
            <br>
            <span style="color: white; font-family: 'Gill Sans', 'Gill Sans MT', 'Calibri', 'Trebuchet MS', sans-serif; font-size: 9px;">PESQUISA HOME OFFICE</span>
            </div>
          </div>
        </a>  
        </div> -->

      

      <div class="col s6 m6 xl3">
         <a href="#modalEscritorio" class="modal-trigger">
            <div class="card gradient-shadow border-radius-3" style="width:80%;margin-top:5px;margin-left:-50px;border-radius: 20px; background-color: rgba(255,255,255,.1);">
               <div class="card-content center" style="padding: 14px">
                  <img src="{{url('/public/imgs/profiles/escritorio.png')}}" alt="images" width="26%!important" />
                  <br>
                  <span style="color: white; font-family: 'Gill Sans', 'Gill Sans MT', 'Calibri', 'Trebuchet MS', sans-serif; font-size: 9px;">ESCRITÓRIO</span>
               </div>
            </div>
         </a>  

         <div id="modalEscritorio" class="modal">
            <div class="modal-content">

               <h6 style="padding: 0 1rem;"><b>Card - Escritório</b>
                  <button type="button" class="btn btn-close waves-effect mr-sm-1 mr-2 modal-close red">
                     <i class="material-icons">close</i>
                  </button>
               </h6>

               <div class="container" style="padding: 0 1rem;">
                  <ul class="collapsible custom-collapse">
                     <li class="active">
                        <div class="collapsible-header" name="escritorio" data-father="escritorio">
                           <h6 style="font-size: 11px; width: 100%;"><i class="material-icons" style="vertical-align: -6px;">home</i>&nbsp;
                              Escritório:
                              <i class="material-icons" data-father="escritorio" data-type="mr" id="i-mr-escritorio" style="vertical-align: -6px; float: right; display: none;">expand_more</i>
                              <i class="material-icons" data-father="escritorio" data-type="ls" id="i-ls-escritorio" style="vertical-align: -6px; float: right;">expand_less</i>
                           </h6>
                        </div>
                        <div class="collapsible-body ">
                           <ul class="collection">
                              <li class="collection-item"><a style="font-size: 10px; color: black;" href="{{ route('Painel.TI.agendamentomesa.index') }}"><i class="material-icons"  style="vertical-align: -6px;">calendar_today</i>&nbsp;&nbsp;Agendamentos</a></li>
                              <li class="collection-item"><a style="font-size: 10px; color: black;" href="{{ route('Painel.Marketing.anexo', 'dresscode.pdf') }}"><i class="material-icons"  style="vertical-align: -6px;">person_outline</i>&nbsp;&nbsp;Dress Code</a></li>
                              <li>
                                 <ul class="collapsible custom-collapse">
                                    <li>
                                       <div class="collapsible-header" data-father="escritorio" name="programas_padroes">
                                          <h6 style="font-size: 11px; width: 100%;"><i class="material-icons" style="vertical-align: -6px;">dashboard</i>&nbsp;Programas Padrões
                                             <i class="material-icons" data-father="escritorio" data-type="mr" id="i-mr-programas_padroes" style="vertical-align: -6px; float: right;">expand_more</i>
                                             <i class="material-icons" data-father="escritorio" data-type="ls" id="i-ls-programas_padroes" style="vertical-align: -6px; float: right; display: none;">expand_less</i>
                                          </h6>
                                       </div>
                                       <div class="collapsible-body">
                                          <ul class="collection">
                                             <li class="collection-item"><a style="font-size: 10px;color: black; margin-left: -5px;" href="https://www.microsoft.com/pt-br/microsoft-teams/log-in" target="_blank"><i class="material-icons" style="vertical-align: -6px; font-size: 20px;">list</i>&nbsp;&nbsp; Microsoft Teams</a></li>
                                             <li class="collection-item"><a style="font-size: 10px; color: black;" href="{{ route('Painel.Marketing.anexo', 'fundodetela.jpg') }}"><i class="material-icons"  style="vertical-align: -6px;">picture_in_picture</i>&nbsp;&nbsp;Fundo de tela Microsoft Teams/Zoom</a></li>
                                          </ul>
                                       </div>
                                    <li>
                                 </ul>
                              </li>

                              <!-- @can('users')
                              <li class="collection-item"><a style="font-size: 10px; color: black;" href="{{ route('Painel.Escritorio.Solicitacoes.Advogado.index') }}"><i class="material-icons"  style="vertical-align: -6px;">shopping_cart</i>&nbsp;&nbsp;Solicitações</a></li>
                              @endcan -->

                              <!-- Projeto Compras -->
                              <!-- @can('solicitante_compra')
                              <li class="collection-item"><a style="font-size: 10px; color: black;" href="{{ route('Painel.Compras.Solicitante.index_solicitante') }}"><i class="material-icons"  style="vertical-align: -6px;">shopping_cart</i>&nbsp;&nbsp;Minhas solicitações de compra</a></li>
                              @endcan -->

                              <!-- @can('comite_compras')
                              <li class="collection-item"><a style="font-size: 10px; color: black;" href="{{ route('Painel.Compras.ComiteCompras.index_comite') }}"><i class="material-icons"  style="vertical-align: -6px;">shopping_cart</i>&nbsp;&nbsp;Compras</a></li>
                              @endcan -->

                              <!-- @can('comite_aprovacao')
                              <li class="collection-item"><a style="font-size: 10px; color: black;" href="{{ route('Painel.Compras.ComiteAprovacao.index_comite_aprovacao') }}"><i class="material-icons"  style="vertical-align: -6px;">check_circle_outline</i>&nbsp;&nbsp;Aprovar compras</a></li>
                              @endcan -->

                              <!-- FIm Projeto Compras -->
                           </ul>
                        </div>
                     </li>
                  </ul>
               </div>

               <ul class="collection" style="margin-left: 200px; width: 300px;">
                  <center>
                  <li class="collection-item" style="font-size: 10px; color: black;"><i class="material-icons"  style="vertical-align: -6px; color: red;">block</i>&nbsp;&nbsp;Módulo em desenvolvimento</li>
                  </center>
               </ul>
            </div>
         </div>
      </div>
   </div>
</div>

      <div class="col s12 m12 x12" style="">

      <!-- <center>
         <p id="text-comunicados" style="color: white;font-family: 'Gill Sans', 'Gill Sans MT', 'Calibri', 'Trebuchet MS', sans-serif;font-size: 14px;margin-top:-60px; text-align: end; margin-right: 50px;">COMUNICADOS INTERNOS PL&C: 
            <a style="color: white; text-align: center;" href="{{ route('Painel.Marketing.index') }}"><span class="dot" id="dot-lido">{{$totalComunicadosLidos}}</span> </a>
            <i style="font-size: 12px;">Lidos</i>
            <a style="color: white; text-align: center;" href="{{ route('Painel.Marketing.index') }}"><span class="dot2">{{$totalComunicadosAbertos}}</span></a>
            <i style="font-size: 12px;">Não lidos</i>
         </p>
      </center> -->

         <ul class="collection" id="ul-comunicados" style="position: absolute; top: 120px; right: 50px;">
            @foreach ($comunicados as $c)

            <div id="modal{{$c->id}}" class="modal"  data-backdrop="static" data-keyboard="false">
                  <div class="modal-content" style="overflow: hidden;">
                     <a href="{{route('Home.Principal.updateTable', $c->id)}}" class="btn waves-effect mr-sm-1 mr-2 modal-close red" style="margin-left: 91%; margin-top: -30px;">
                        <i class="material-icons">close</i> 
                     </a>
                     <div class="modal-header">
                        <h6><b>{{$c->desc}}</b></h6>
                     </div>

                     <li class="divider"></li>

                     <div class="modal-body">
                        <h6><b>{{$c->titulo}}</b></h6>
                        <h6 style="font-size: 16px;">{{$c->descricao}}</h6>
                     </div>

                  </div>
                  <div class="modal-footer">
                     @if(!empty($c->anexo))
                     <a class="btn-floating btn-mini waves-effect waves-light green tooltipped"  href="{{route('Home.Principal.anexo', $c->anexo)}}"
                     data-position="left" data-tooltip="Fazer download do anexo">
                     <i class="material-icons">attach_file</i></a>
                     @endif


                  </div>
               </div>

               @if(!empty($c->lido))
               <li class="collection-item" style="margin-top: 8px;margin-right: 8px;width: 700px;" id="li-nova-2">
                  @else
                  <li class="collection-item c-lido" style="margin-top: 8px;margin-right: 8px;width: 700px;" id="li-nova-2">
                  @endif  
                  <div style="width: 77%; margin-top: -20px;">
                     <h1 style="font-size: 13px;">
                        <i><b style="color: white;">{{$c->titulo}}</b></i>
                        <small style="color:white; margin-left: 9px; font-size: 9px;"> {{ date('d/m/Y', strtotime($c->data)) }}</small>
                     </h1>
                  </div>
                  <p style="font-size: 11px; margin-top: -22px;">[{{$c->desc}}]</p>
                  <div>
                     <div style="width:80%; max-height: 30px;">
                        <p id="p_descricao" style="font-size: 10px;margin-right: 30px; 
                        max-width: 100ch;
                        overflow: hidden;
                        text-overflow: ellipsis;
                        white-space: nowrap;">{{$c->descricao}}</p>
                        <a class="modal-trigger" href="#modal{{$c->id}}" style="font-size: 10px;margin-right: 30px;text-decoration:underline; color: white;" id="linkAbreModal">
                           <b><p style="margin-top: -15px;">Leia mais</p></b>
                        </a>
                     </div>
                  </div>
               </li>
            @endforeach
         </ul>

         <style>
            #ul-comunicados{
            border:none; 
            width: 600px;
            margin-top:-16px;
            }
            .dot {
            height: 30px;
            width: 30px;
            background-color: #bbb;
            border-radius: 50%;
            display: inline-block;
            margin-left: 20px;
            }
            .dot2 {
            height: 30px;
            width: 30px;
            border-radius: 50%;
            background-color: red;
            display: inline-block;
            margin-left: 10px;
            }
            .dot3 {
            height: 20px;
            width: 20px;
            border-radius: 50%;
            background-color: red;
            display: inline-block;
            margin-left: 300px;
            } */
            #li-nova{
            background-color:rgba(255,255,255,.1);
            border:none;
            color:white;
            border-left: 5px solid red;
            max-height: 120px;
            } 
            #li-nova-2{
            background-color:rgba(255,255,255,.1);
            border:none;
            color:white;
            border-left: 5px solid gray;
            }

            #li-nova-2.c-lido{
               border-left: 5px solid red;
            }
         
            /* @media only screen and (max-width: 768px) {
               #ul-comunicados {
                 width: 1400px;
                 margin-left: 30px;
               }
             
            } */
         </style>
         </div>
    </div>


      </div>

         </div>
      </div>

     </div>

        </div>
     </div>



      </div>


      <!-- <div class="col s12 m6 l6">
      <center><h4 class="card-title" style="color: white;font-family: 'Gill Sans', 'Gill Sans MT', 'Calibri', 'Trebuchet MS', sans-serif;font-size: 14px;margin-left:-90px">COMUNICADOS INTERNOS PL&C:</h4></center>
               <div style="margin-top: -50px; margin-left: 135px;">
                  <center>
                        <a style="color: white;" href="{{ route('Painel.Marketing.index') }}"><span class="dot" id="dot-lido">{{$totalComunicadosLidos}}</span> </a>
                        <i style="font-size: 10px;">Lidos</i>
                        <a style="color: white;" href="{{ route('Painel.Marketing.index') }}"><span class="dot2">{{$totalComunicadosAbertos}}</span></a>
                        <i style="font-size: 10px;">Não lidos</i>
                     </p>
                  </center>
    </div>
         -->



         <!-- MODAL CONTROLADORIA / VÍDEOS-->
         <div id="modalControladoria" class="modal" style="width: 80% !important;height: 90% !important;">
               <div class="modal-content">
                  <h6 style="padding: 0 1rem;"><b>Card - Controladoria</b>
                     <button type="button" class="btn btn-close waves-effect mr-sm-1 mr-2 modal-close red">
                        <i class="material-icons">close</i>
                     </button>
                  </h6>

                  <div class="row">
                     <div class="col s12 m6 l6">
                        <ul class="collapsible custom-collapse">
                           <li class="active">
                              <div class="collapsible-header" name="videos_controladoria" data-father="videos_controladoria">
                                 <h6 style="font-size: 11px; width: 100%;"><i class="material-icons" style="vertical-align: -6px;">video_library</i>&nbsp;
                                    Vídeos - selecione uma opção abaixo:
                                    <i class="material-icons" data-father="videos_controladoria" data-type="mr" id="i-mr-videos_controladoria" style="vertical-align: -6px; float: right; display: none;">expand_more</i>
                                    <i class="material-icons" data-father="videos_controladoria" data-type="ls" id="i-ls-videos_controladoria" style="vertical-align: -6px; float: right;">expand_less</i>
                                 </h6>
                              </div>
                              <div class="collapsible-body">
                                 <ul class="collection">
                                    <li class="collection-item"><a class="modal-trigger" href="#modalVideo1" onclick="abreModalVideo1();" style="font-size: 10px;color: black; margin-left: -5px;"><i class="material-icons" style="vertical-align: -6px; font-size: 20px;">videocam</i>&nbsp;&nbsp;Procedimento para utilizar o formulário ficha tempo</a></li>
                                    <li class="collection-item"><a class="modal-trigger" href="#modalVideo2" onclick="abreModalVideo2();" style="font-size: 10px;color: black; margin-left: -5px;"><i class="material-icons" style="vertical-align: -6px; font-size: 20px;">videocam</i>&nbsp;&nbsp;Procedimento para a movimentação e cumprimento de prazos</a></li>
                                 </ul>
                              </div>
                           </li>
                        </ul>
                     </div>

                     <div class="col s12 m6 l6">
                        <ul class="collapsible custom-collapse">
                           <li class="active">
                              <div class="collapsible-header" name="links_controladoria" data-father="links_controladoria">
                                 <h6 style="font-size: 11px; width: 100%;"><i class="material-icons" style="vertical-align: -6px;">attach_file</i>&nbsp;
                                    Arquivos - selecione uma opção abaixo:
                                    <i class="material-icons" data-father="links_controladoria" data-type="mr" id="i-mr-links_controladoria" style="vertical-align: -6px; float: right; display: none;">expand_more</i>
                                    <i class="material-icons" data-father="links_controladoria" data-type="ls" id="i-ls-links_controladoria" style="vertical-align: -6px; float: right;">expand_less</i>
                                 </h6>
                              </div>
                              <div class="collapsible-body">
                                 <ul class="collection">
                                    <li class="collection-item"><a href="{{route('Home.Principal.treinamento', 'ApresentacaoSLA.pdf')}}"  style="font-size: 10px;color: black; margin-left: -5px;"><i class="material-icons" style="vertical-align: -6px; font-size: 20px;">file_copy</i>&nbsp;&nbsp;Apresentação - SLA - Jurídico Tenco</a></li>
                                    <li class="collection-item"><a href="{{route('Home.Principal.treinamento', 'Treinamento01.pdf')}}"  style="font-size: 10px;color: black; margin-left: -5px;"><i class="material-icons" style="vertical-align: -6px; font-size: 20px;">file_copy</i>&nbsp;&nbsp;Cartilha de provisionamento Alfresco 1607</a></li>
                                    <li class="collection-item"><a href="{{route('Home.Principal.treinamento', 'Treinamento02.pdf')}}"  style="font-size: 10px;color: black; margin-left: -5px;"><i class="material-icons" style="vertical-align: -6px; font-size: 20px;">file_copy</i>&nbsp;&nbsp;Cartilha provisionamento PLC</a></li>
                                    <li class="collection-item"><a href="{{route('Home.Principal.treinamento', 'TreinamentoGPA.pdf')}}" style="font-size: 10px;color: black; margin-left: -5px;"><i class="material-icons" style="vertical-align: -6px; font-size: 20px;">file_copy</i>&nbsp;&nbsp;Cartilha de provisionamento GPA</a></li>
                                    <li class="collection-item"><a href="{{route('Home.Principal.treinamento',  'Treinamento04.pdf')}}"  style="font-size: 10px;color: black; margin-left: -5px;"><i class="material-icons" style="vertical-align: -6px; font-size: 20px;">file_copy</i>&nbsp;&nbsp;Manual Cumprimento de prazos _2019 _V 4</a></li>
                                    <li class="collection-item"><a href="{{route('Home.Principal.treinamento',  'MANUAL-GED_2020.pdf')}}"  style="font-size: 10px;color: black; margin-left: -5px;"><i class="material-icons" style="vertical-align: -6px; font-size: 20px;">file_copy</i>&nbsp;&nbsp;Manual de indexação de arquivos no GED (Vinculados ao debite)</a></li>
                                 </ul>
                              </div>
                           </li>
                        </ul>
                     </div>
                  </div>
               </div>
            </div>
            <div id="modalVideo1" class="modal">
               <div class="modal-content">
                  <center>
                     <div class="video-container">
                        <video class="afterglow" id="video01" width="560" height="315"  controls  onblur="pausevideo();">
                           <source src="{{ url('storage/treinamentos/video01.mp4') }}" type="video/mp4" />
                        </video>
                     </div>
                  </center>
               </div>
            </div>
            <div id="modalVideo2" class="modal">
               <div class="modal-content">
                  <center>
                     <div class="video-container">
                        <video class="afterglow" id="video02" width="560" height="315"  controls  onblur="pausevideo();">
                           <source src="{{ url('storage/treinamentos/video2.mp4') }}" type="video/mp4" />
                        </video>
                     </div>
                  </center>
               </div>
            </div>

            <!-- MODAL CORRESPONDENTE  -->
            <div id="modalCorrespondente"  class="modal" style="width: 80% !important;height: 90% !important;">
               <div class="modal-content">
                  <h6 style="padding: 0 1rem;"><b>Card - Correspondente</b>
                     <button type="button" class="btn btn-close waves-effect mr-sm-1 mr-2 modal-close red">
                        <i class="material-icons">close</i>
                     </button>
                  </h6>
            
                  <div class="row">
                     <div class="col s12 m6 l6">
                        <ul class="collapsible custom-collapse">
                           <li class="active">
                              <div class="collapsible-header" name="correspondente_video" data-father="correspondente_video">
                                 <h6 style="font-size: 11px; width: 100%;"><i class="material-icons" style="vertical-align: -6px;">video_library</i>&nbsp;
                                    Vídeos - selecione uma opção abaixo:
                                    <i class="material-icons" data-father="correspondente_video" data-type="mr" id="i-mr-correspondente_video" style="vertical-align: -6px; float: right; display: none;">expand_more</i>
                                    <i class="material-icons" data-father="correspondente_video" data-type="ls" id="i-ls-correspondente_video" style="vertical-align: -6px; float: right;">expand_less</i>
                                 </h6>
                              </div>
                              <div class="collapsible-body">
                                 <ul class="collection">
                                    @can('coordenador')
                                    <li class="collection-item">
                                       <a aria-hidden="true" onclick="abreModalAprovar();" class="modal-trigger" 
                                       style="font-size: 10px;color: black;" href="#modalAprovar"><i class="material-icons" style="vertical-align: -6px;">video_library</i>&nbsp;&nbsp;Aprovar solicitação de pagamento correspondente</a></li>
                                    @endcan
                                    @can('financeiro')
                                    <li class="collection-item">
                                       <a aria-hidden="true" onclick="abreModalAprovar();" class="modal-trigger" 
                                       style="font-size: 10px;color: black;" href="#modalAprovar"><i class="material-icons" style="vertical-align: -6px;">video_library</i>&nbsp;&nbsp;Aprovar solicitação de pagamento correspondente</a></li>
                                    @endcan
                                    <li class="collection-item">
                                       <a aria-hidden="true" onclick="abreModalContratacao();" class="modal-trigger" 
                                       style="font-size: 10px;color: black;" href="#modalContratacao"><i class="material-icons" style="vertical-align: -6px;">video_library</i>&nbsp;&nbsp;Contratação de correspondente</a></li>
                                 </ul>
                              </div>
                           </li>
                        </ul>
                     </div>

                     <div class="col s12 m6 l6">
                        <ul class="collapsible custom-collapse">
                           <li class="active">
                              <div class="collapsible-header" name="correspondente_links" data-father="correspondente_links">
                                 <h6 style="font-size: 11px; width: 100%;"><i class="material-icons" style="vertical-align: -6px;">attach_file</i>&nbsp;
                                    Arquivos - selecione uma opção abaixo:
                                    <i class="material-icons" data-father="correspondente_links" data-type="mr" id="i-mr-correspondente_links" style="vertical-align: -6px; float: right; display: none;">expand_more</i>
                                    <i class="material-icons" data-father="correspondente_links" data-type="ls" id="i-ls-correspondente_links" style="vertical-align: -6px; float: right;">expand_less</i>
                                 </h6>
                              </div>
                              <div class="collapsible-body">
                                 <ul class="collapsible custom-collapse">
                                    @can('coordenador')
                                    <li class="active">
                                       <div class="collapsible-header" name="coordenador_subcoordenador" data-father="links">
                                          <h6 style="font-size: 11px; width: 100%;"><i class="material-icons" style="vertical-align: -6px;">how_to_reg</i>&nbsp;
                                             Coordenador e Subcoordenador
                                             <i class="material-icons" data-father="links" data-type="mr" id="i-mr-coordenador_subcoordenador" style="vertical-align: -6px; float: right; display: none;">expand_more</i>
                                             <i class="material-icons" data-father="links" data-type="ls" id="i-ls-coordenador_subcoordenador" style="vertical-align: -6px; float: right;">expand_less</i>
                                          </h6>
                                       </div>
                                       <div class="collapsible-body">
                                          <ul class="collection">
                                             <li class="collection-item"><a aria-hidden="true" style="font-size: 10px;color: black;" href="{{ route('Painel.Coordenador.index') }}"><i class="material-icons" style="vertical-align: -6px;">check</i>&nbsp;&nbsp;Aprovação de solicitação de pagamento correspondente</a></li>
                                             @can('users')
                                                <li class="collection-item"><a aria-hidden="true" style="font-size: 10px; color: black;" href="{{ route('Painel.Correspondente.principal2') }}"><i class="material-icons" style="vertical-align: -6px;">dashboard</i>&nbsp;&nbsp;Contratação / Dashboard Correspondente</a></li>
                                             @endcan
                                             <li class="collection-item"><a aria-hidden="true" style="font-size: 10px;color: black;" href="{{ route('Painel.Coordenador.acompanharSolicitacoes') }}"><i class="material-icons" style="vertical-align: -6px;">compare_arrows</i>&nbsp;&nbsp;Fluxo de processo em curso</a></li>
                                             <li class="collection-item"><a aria-hidden="true" style="font-size: 10px; color: black;" href="{{ route('Painel.Financeiro.pagas') }}"><i class="material-icons" style="vertical-align: -6px;">compare_arrows</i>&nbsp;&nbsp;Fluxo de processo finalizadas</a></li>
                                          </ul>
                                       </div>
                                    </li>
                                    @endcan
                                    @can('financeiro')
                                    <li class="active">
                                       <div class="collapsible-header" name="financeiro" data-father="links">
                                          <h6 style="font-size: 11px; width: 100%;"><i class="material-icons" style="vertical-align: -6px;">attach_money</i>&nbsp;
                                             Financeiro
                                             <i class="material-icons" data-father="links" data-type="mr" id="i-mr-financeiro" style="vertical-align: -6px; float: right; display: none;">expand_more</i>
                                             <i class="material-icons" data-father="links" data-type="ls" id="i-ls-financeiro" style="vertical-align: -6px; float: right;">expand_less</i>
                                          </h6>
                                       </div>
                                       <div class="collapsible-body">
                                          <ul class="collection">
                                             <li class="collection-item"><a aria-hidden="true" style="font-size: 10px; color: black;" href="{{ route('Painel.Financeiro.index') }}"><i class="material-icons" style="vertical-align: -6px;">add</i>&nbsp;&nbsp;Aprovar solicitação de pagamento correspondente</a></li>
                                             <!-- <li class="collection-item"><a aria-hidden="true" style="font-size: 10px; color: black;" href="{{ route('Painel.Correspondente.principal') }}"><i class="material-icons" style="vertical-align: -6px;">dashboard</i>&nbsp;&nbsp;Contratação / Dashboard Correspondente</a></li> -->
                                             <li class="collection-item"><a aria-hidden="true" style="font-size: 10px; color: black;" href="{{ route('Painel.TI.correspondente.index') }}"><i class="material-icons" style="vertical-align: -6px;">dashboard</i>&nbsp;&nbsp;Dashboard</a></li>
                                             <li class="collection-item"><a aria-hidden="true" style="font-size: 10px; color: black;" href="{{ route('Painel.Financeiro.programadas') }}"><i class="material-icons" style="vertical-align: -6px;">check</i>&nbsp;&nbsp;Realizar conciliação bancária</a></li>
                                             <li class="collection-item"><a aria-hidden="true" style="font-size: 10px; color: black;" href="{{ route('Painel.Financeiro.realizarconciliacao') }}"><i class="material-icons" style="vertical-align: -6px;">check</i>&nbsp;&nbsp;Realizar conciliação bancária por faixa</a></li>
                                          </ul>
                                       </div>
                                    </li>
                                    @endcan
                                    @can('advogado')
                                    <li class="active">
                                       <div class="collapsible-header" name="advogado" data-father="links">
                                          <h6 style="font-size: 11px; width: 100%;"><i class="material-icons" style="vertical-align: -6px;">assignment_ind</i>&nbsp;
                                             Advogado
                                             <i class="material-icons" data-father="links" data-type="mr" id="i-mr-advogado" style="vertical-align: -6px; float: right; display: none;">expand_more</i>
                                             <i class="material-icons" data-father="links" data-type="ls" id="i-ls-advogado" style="vertical-align: -6px; float: right;">expand_less</i>
                                          </h6>
                                       </div>
                                       <div class="collapsible-body">
                                          <ul class="collection">
                                             <!-- <li class="collection-item"><a aria-hidden="true" style="font-size: 10px; color: black;" href="{{ route('Painel.Correspondente.principal') }}"><i class="material-icons" style="vertical-align: -6px;">dashboard</i>&nbsp;&nbsp;Contratação / Dashboard Correspondente</a></li> -->
                                             <li class="collection-item"><a aria-hidden="true" style="font-size: 10px;color: black;" href="{{ route('Painel.Advogado.index') }}"><i class="material-icons" style="vertical-align: -6px;">compare_arrows</i>&nbsp;&nbsp;Fluxo de processo em curso</a></li>
                                          </ul>
                                       </div>
                                    </li>
                                    @endcan
                                 </ul>
                              </div>
                           </li>
                        </ul>
                     </div>
                  </div>


               </div>

            </div>

             <!--Fim Modal Correspondente --> 

             <div id="modalAprovar" class="modal">
               <div class="modal-content">
                  <center>
                     <div class="video-container">
                        <video class="afterglow" id="videoAprovar" width="560" height="315" controls  onblur="pausevideo();">
                           <source src="{{ url('storage/treinamentos/Aprovar.mp4') }}" type="video/mp4" />
                        </video>
                     </div>
                  </center>
               </div>
             </div>

             
             <div id="modalContratacao" class="modal">
               <div class="modal-content">
                  <center>
                     <div class="video-container">
                        <video class="afterglow" id="video3" width="560" height="315" controls  onblur="pausevideo();">
                           <source src="{{ url('storage/treinamentos/video3.mp4') }}" type="video/mp4" />
                        </video>
                     </div>
                  </center>
               </div>
             </div>


            <!-- MODAL FINANCEIRO-->
               <div id="modalFinanceiro" class="modal" style="width: 80% !important;height: 90% !important;">
               <div class="modal-content">
                  <h6 style="padding: 0 1rem;"><b>Card - Financeiro</b>
                     <button type="button" class="btn btn-close waves-effect mr-sm-1 mr-2 modal-close red">
                        <i class="material-icons">close</i>
                     </button>
                  </h6>
                  <div class="row">
                     <div class="col s12 m6 l6">
                        <!-- <ul class="collection with-header">
                           <li class="collection-header">
                              <h6 style="font-size: 11px;"><i class="material-icons" style="vertical-align: -6px;">video_library</i>&nbsp;Vídeos - selecione uma opção abaixo:</h6>
                           </li>
                           <li class="collection-item"><a class="modal-trigger" href="#modalVideoMeritocracia" onclick="abreModalVideoMeritocracia();" style="font-size: 10px;color: black; margin-left: -5px;"><i class="material-icons" style="vertical-align: -6px; font-size: 20px;">videocam</i>&nbsp;&nbsp;Procedimento para acessar a meritocracia</a></li>
                        </ul> -->

                        <ul class="collapsible custom-collapse">
                           <li class="active">
                              <div class="collapsible-header" name="videos" data-father="video">
                                 <h6 style="font-size: 11px; width: 100%;"><i class="material-icons" style="vertical-align: -6px;">video_library</i>&nbsp;
                                    Vídeos - selecione uma opção abaixo:
                                    <i class="material-icons" data-father="video" data-type="mr" id="i-mr-videos" style="vertical-align: -6px; float: right; display: none;">expand_more</i>
                                    <i class="material-icons" data-father="video" data-type="ls" id="i-ls-videos" style="vertical-align: -6px; float: right;">expand_less</i>
                                 </h6>
                              </div>
                              <div class="collapsible-body ">
                                 <ul class="collection">
                                    <li class="collection-item">
                                       <a class="modal-trigger" href="#modalVideoMeritocracia" onclick="abreModalVideoMeritocracia();" style="font-size: 10px;color: black; margin-left: -5px;">
                                          <i class="material-icons" style="vertical-align: -6px; font-size: 20px;">videocam</i>&nbsp;&nbsp;
                                          Procedimento para acessar a meritocracia
                                       </a>
                                    </li>
                                 </ul>
                              </div>
                           </li>
                        </ul>
                     </div>

                     <div class="col s12 m6 l6">
                        <ul class="collapsible custom-collapse">
                           <li class="active">
                              <div class="collapsible-header" name="links" data-father="financeiro">
                                 <h6 style="font-size: 11px; width: 100%;"><i class="material-icons" style="vertical-align: -6px;">attach_file</i>&nbsp;Links - selecione uma opção abaixo:
                                    <i class="material-icons" data-father="financeiro" data-type="mr" id="i-mr-links" style="vertical-align: -6px; float: right; display: none;">expand_more</i>
                                    <i class="material-icons" data-father="financeiro" data-type="ls" id="i-ls-links" style="vertical-align: -6px; float: right;">expand_less</i>
                                 </h6>
                              </div>
                              <div class="collapsible-body">
                                 <ul class="collapsible custom-collapse">
                                    <li> <!-- Menu Contas a Pagar -->
                                       <div class="collapsible-header" data-father="links" name="contas_pagar">
                                          <h6 style="font-size: 11px; width: 100%;"><i class="material-icons" style="vertical-align: -6px;">money_off</i>&nbsp;Contas a Pagar
                                             <i class="material-icons" data-father="links" data-type="mr" id="i-mr-contas_pagar" style="vertical-align: -6px; float: right;">expand_more</i>
                                             <i class="material-icons" data-father="links" data-type="ls" id="i-ls-contas_pagar" style="vertical-align: -6px; float: right; display: none;">expand_less</i>
                                          </h6>
                                       </div>
                                       <div class="collapsible-body">
                                          <ul class="collection"> <!-- Sub-menu -->
                                             
                                          </ul>
                                       </div>
                                    </li>
                                    <li> <!-- Menu Contas a Receber -->
                                       <div class="collapsible-header" data-father="links" name="contas_receber">
                                          <h6 style="font-size: 11px; width: 100%;"><i class="material-icons" style="vertical-align: -6px;">attach_money</i>&nbsp;Contas a Receber
                                             <i class="material-icons" data-father="links" data-type="mr" id="i-mr-contas_receber" style="vertical-align: -6px; float: right;">expand_more</i>
                                             <i class="material-icons" data-father="links" data-type="ls" id="i-ls-contas_receber" style="vertical-align: -6px; float: right; display: none;">expand_less</i>
                                          </h6>
                                       </div>
                                       <div class="collapsible-body">
                                          <ul class="collection"> <!-- Sub-menu -->
            
                                             @can('financeiro_faturamento') <!-- Associação de nota fiscal -->
                                                <li class="collection-item"><a href="{{route('Painel.Financeiro.AssociacaoNF.index')}}"  style="font-size: 10px;color: black; margin-left: -5px;"><i class="material-icons" style="vertical-align: -6px; font-size: 20px;">file_copy</i>&nbsp;&nbsp;Associação de nota fiscal</a></li>
                                             @endcan
                                             @can('financeiro contas a pagar') <!-- Faturamento debite &  Prestação de contas -->
                                                <li class="collection-item"><a href="{{route('Painel.Financeiro.Reembolso.PagamentoDebite.index')}}" style="font-size: 10px;color: black; margin-left: -5px;"><i class="material-icons" style="vertical-align: -6px; font-size: 20px;">list</i>&nbsp;&nbsp;Faturamento de debite</a></li>
                                                <li class="collection-item"><a href="{{route('Painel.Financeiro.Reembolso.PrestacaoConta.index')}}" style="font-size: 10px;color: black; margin-left: -5px;"><i class="material-icons" style="vertical-align: -6px; font-size: 20px;">list</i>&nbsp;&nbsp;Prestação de contas</a></li>   
                                             @endcan
                                             @can('financeiro') <!-- Faturamento debite &  Prestação de contas -->
                                                <li class="collection-item"><a href="{{route('Painel.Financeiro.Reembolso.PagamentoDebite.index')}}" style="font-size: 10px;color: black; margin-left: -5px;"><i class="material-icons" style="vertical-align: -6px; font-size: 20px;">list</i>&nbsp;&nbsp;Faturamento de debite</a></li>
                                                <li class="collection-item"><a href="{{route('Painel.Financeiro.Reembolso.PrestacaoConta.index')}}" style="font-size: 10px;color: black; margin-left: -5px;"><i class="material-icons" style="vertical-align: -6px; font-size: 20px;">list</i>&nbsp;&nbsp;Prestação de contas</a></li>   
                                             @endcan
                                             @can('financeiro_faturamento') <!-- Realizar faturamento de pastas e processos -->
                                                <li class="collection-item"><a href="{{route('Painel.Financeiro.faturamento')}}"  style="font-size: 10px;color: black; margin-left: -5px;"><i class="material-icons" style="vertical-align: -6px; font-size: 20px;">file_copy</i>&nbsp;&nbsp;Realizar faturamento de pastas e processos</a></li>
                                             @endcan
                                          </ul>
                                       </div>
                                    </li>
                                    <!--Menu Adiantamento/Reembolso --> 

                                    <li> 
                                       <div class="collapsible-header" data-father="links" name="guia_custos">
                                          <h6 style="font-size: 11px; width: 100%;"><i class="material-icons" style="vertical-align: -6px;">savings</i>&nbsp;Adiantamento/Prestação de conta/Reembolso
                                             <i class="material-icons" data-father="links" data-type="mr" id="i-mr-guia_custos" style="vertical-align: -6px; float: right;">expand_more</i>
                                             <i class="material-icons" data-father="links" data-type="ls" id="i-ls-guia_custos" style="vertical-align: -6px; float: right; display: none;">expand_less</i>
                                          </h6>
                                       </div>
                                       <div class="collapsible-body">
                                          <ul class="collection">
                                             @can('users') <!-- Associação de nota fiscal -->
                                                <li class="collection-item"><a href="{{route('Painel.Financeiro.AdiantamentoPrestacao.Solicitante.index')}}"  style="font-size: 10px;color: black; margin-left: -5px;"><i class="material-icons" style="vertical-align: -6px; font-size: 20px;">list</i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Adiantamento/Prestação de conta</a></li>
                                                <li class="collection-item"><a href="{{route('Painel.Financeiro.Reembolso.Solicitante.index')}}"  style="font-size: 10px;color: black; margin-left: -5px;"><i class="material-icons" style="vertical-align: -6px; font-size: 20px;">list</i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Solicitações de reembolso</a></li>
                                             @endcan
                                          </ul>
                                       </div>
                                    </li>

                                    <li> <!-- Menu Guia de Custos -->
                                       <div class="collapsible-header" data-father="links" name="guia_custos">
                                          <h6 style="font-size: 11px; width: 100%;"><i class="material-icons" style="vertical-align: -6px;">savings</i>&nbsp;Guia de Custos
                                             <i class="material-icons" data-father="links" data-type="mr" id="i-mr-guia_custos" style="vertical-align: -6px; float: right;">expand_more</i>
                                             <i class="material-icons" data-father="links" data-type="ls" id="i-ls-guia_custos" style="vertical-align: -6px; float: right; display: none;">expand_less</i>
                                          </h6>
                                       </div>
                                       <div class="collapsible-body">
                                          <ul class="collection">
                                                <li class="collection-item"><a href="{{route('Painel.Financeiro.GuiasCustas.Solicitante.index')}}"  style="font-size: 10px;color: black; margin-left: -5px;"><i class="material-icons" style="vertical-align: -6px; font-size: 20px;">list</i>&nbsp;&nbsp;Solicitação de pagamento de guias de custa</a></li>
                                             @can('users')
                                                <li class="collection-item"><a href="{{route('Painel.Financeiro.GuiasCustas.Solicitante.index')}}"  style="font-size: 10px;color: black; margin-left: -5px;"><i class="material-icons" style="vertical-align: -6px; font-size: 20px;">list</i>&nbsp;&nbsp;Solicitação de pagamento de guias de custa</a></li>
                                             @endcan
                                             @can('financeiro') 
                                             <li class="collection-item"><a href="{{route('Painel.Financeiro.GuiasCustas.ConciliacaoBancaria.index')}}"  style="font-size: 10px;color: black; margin-left: -5px;"><i class="material-icons" style="vertical-align: -6px; font-size: 20px;">list</i>&nbsp;&nbsp;Solicitação de pagamento de guias de custa</a></li>
                                             @endcan
                                          </ul>
                                       </div>
                                    </li>
                                    <li> <!-- Menu Meritocracia -->
                                       <div class="collapsible-header" data-father="links" name="guia_custos">
                                          <h6 style="font-size: 11px; width: 100%;"><i class="material-icons" style="vertical-align: -6px;">work</i>&nbsp;Meritocracia
                                             <i class="material-icons" data-father="links" data-type="mr" id="i-mr-guia_custos" style="vertical-align: -6px; float: right;">expand_more</i>
                                             <i class="material-icons" data-father="links" data-type="ls" id="i-ls-guia_custos" style="vertical-align: -6px; float: right; display: none;">expand_less</i>
                                          </h6>
                                       </div>

                                       <div class="collapsible-body">
                                          <ul class="collection">
                                             <li class="collection-item"><a id="meritocracia" href="#" onClick="verificameritocracia();"  style="font-size: 10px;color: black; margin-left: -5px;"><i class="material-icons" style="vertical-align: -6px; font-size: 20px;">file_copy</i>&nbsp;&nbsp;Meritocracia</a></li>
                                             @can('controlador_gestao')
                                             <li class="collection-item"><a id="meritocracia" href="{{route('Painel.Gestao.Controlador.index')}}"  style="font-size: 10px;color: black; margin-left: -5px;"><i class="material-icons" style="vertical-align: -6px; font-size: 20px;">file_copy</i>&nbsp;&nbsp;Controle gestão</a></li>
                                             @endcan
                                          </ul>
                                       </div>
                                    </li>


                                    <li> <!-- Menu Relatorios -->
                                       <div class="collapsible-header" data-father="links" name="relatorios">
                                          <h6 style="font-size: 11px; width: 100%;"><i class="material-icons" style="vertical-align: -6px;">timeline</i>&nbsp;Relatórios
                                             <i class="material-icons" data-father="links" data-type="mr" id="i-mr-relatorios" style="vertical-align: -6px; float: right;">expand_more</i>
                                             <i class="material-icons" data-father="links" data-type="ls" id="i-ls-relatorios" style="vertical-align: -6px; float: right; display: none;">expand_less</i>
                                          </h6>
                                       </div>
                                       <div class="collapsible-body">
                                          <ul class="collection">
                                             @can('financeiro_relatoriobancario') <!-- Relatorio bancario -->
                                                <li class="collection-item"><a href="{{route('Painel.Financeiro.RelatorioBancario.index')}}" style="font-size: 10px;color: black; margin-left: -5px;"><i class="material-icons" style="vertical-align: -6px; font-size: 20px;">file_copy</i>&nbsp;&nbsp;Relatório Bancário</a></li>
                                             @endcan
                                          </ul>
                                       </div>
                                    </li>
                                 </ul>
                              </div>
                           </li>
                        </ul>
                     </div>
                  </div>
               </div>
            </div>

            <div id="modalVideoMeritocracia" class="modal">
               <div class="modal-content">
                  <center>
                     <div class="video-container">
                        <video class="afterglow" id="videoMeritocracia" width="560" height="315"  controls  onblur="pausevideo();">
                           <source src="{{ url('storage/treinamentos/meritocracia.mp4') }}" type="video/mp4" />
                        </video>
                     </div>
                  </center>
               </div>
            </div>

            <!--Fim Modal Financeiro -->


            <!--Modal Alterar Senha Padrão --> 
            <div id="modalsenha" class="modal" data-backdrop="static" data-keyboard="false">
               <div class="modal-content">

               <h6>Alteração de senha</h6>
                <p style="font-size: 12px;">Para maior segurança dentro do nosso portal é necessário alterar sua senha de acesso abaixo. Lembramos que a senha <strong>não pode ser a mesma que a atual</strong></p>

      <form id="form1" role="form1" onsubmit="btnsubmit.disabled = true; return true;" action="{{ route('Painel.TI.HomePage.NovaSenha') }}" method="post">
        {{ csrf_field() }}

       <input id="senhaatual" name="senhaatual" value="{{$senhaatual}}" required readonly type="hidden" class="validate">
       <input type="hidden" name="cpf" id="cpf" value="{{$cpf_criptografado}}">
       <input type="hidden" name="email" id="email" value="{{$email_criptografado}}">

            <div class="row">
              <div class="input-field col s12">
                <i class="material-icons prefix">lock_outline</i>
                <input id="novasenha" name="novasenha" placeholder="Informe a nova senha..." onBlur="verificanovasenha();" pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$" required type="password" class="validate">
                <label for="novasenha">Informe a nova senha:</label>
              </div>
            </div>

            <div class="row">
              <div class="input-field col s12">
                <i class="material-icons prefix">lock_outline</i>
                <input id="confirmasenha" name="confirmasenha" placeholder="Confirme a nova senha..." pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$"  required type="password" class="validate">
                <label for="confirmasenha">Repita a senha:</label>
              </div>
            </div>

            <div class="row">
           <div class="input-field col s12">
           <p><i class="material-icons left">check_circle_outline</i>Pelo menos 8 caracteres</p>
           <p><i class="material-icons left">check_circle_outline</i>Pelo menos 1 letra maiúscula</p>
           <p><i class="material-icons left">check_circle_outline</i>Pelo menos 1 número</p>
           </div>
            </div>

              <div class="row">
                <div class="input-field col s12">
                  <button id="btnsubmit" class="btn waves-effect waves-light right" style="background-color: gray;color:white;" type="button" onClick="envia();">Alterar senha
                    <i class="material-icons left">refresh</i>
                  </button>
                </div>
              </div>

            </div>
          </form>



               </div>
             </div>
          <!--Fim Modal Alterar Senha Padrão -->   


          <div class="row">
         <div id="modalMeuPerfil" class="modal" style="width: 50%;">
            <div class="modal-content">

               <div class="modal-header"> 

                  <div class="col s4 m4">
                     <center>
                     <img src="{{ asset('/public/AdminLTE/img/Avatar/img.png') }}" style="width: 100px; height: 100px;">
                     <h6 class="mb-0">Meu Perfil</h6>
                     </center>
                  </div>
               

                  <div class="col s7 m7">
                     <form role="form" action="{{ route('Painel.Correspondente.update', auth()->user()->id )}}" method="post"  enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="row">
                          <div class="input-field col s12">
                           <input style="font-size: 10px;" name="name" id="name" type="text" value="{{ auth()->user()->name }}"  maxlength="255" class="form-control" placeholder="Nome completo" data-toggle="tooltip" data-placement="top" title="Preencha seu nome completo." required="required">  
                            <label for="first_name" style="font-size: 11px;">Nome</label>
                          </div>
                        </div>

                        <div class="row">
                           <div class="input-field col s12">
                              <input style="font-size: 10px;" name="email" id="email" type="email" value="{{ auth()->user()->email }}" class="form-control" placeholder="Digite seu email" data-toggle="tooltip" data-placement="top" title="Preencha seu email." required="required">
                              <label for="first_name" style="font-size: 11px;">E-mail</label>
                            </div>
                        </div>

                        <div class="row">
                          <div class="input-field col s12">
                            <input style="font-size: 10px;" disabled value="{{ auth()->user()->cpf }}" id="disabled" type="text" class="validate">
                            <label for="disabled" style="font-size: 11px;">CPF</label>
                          </div>
                        </div>
                        <div class="row">
                          <div class="input-field col s12">
                           <input style="font-size: 10px;" name="password" id="password" type="password"  class="form-control" placeholder="Digite sua senha" data-toggle="tooltip" data-placement="top" title="Preencha sua senha." required="required">
                            <label for="password" style="font-size: 11px;">Senha</label>
                          </div>
                        </div>
                        <div class="row">
                          <div class="input-field col s12">
                           <input style="font-size: 10px;" name="password_confirmation" id="password_confirmation" type="password" class="form-control" placeholder="Confirme sua senha" data-toggle="tooltip" data-placement="top" title="Confirmação de senha." required="required">
                            <label for="password_confirmation" style="font-size: 11px;">Confirmar Senha</label>
                          </div>
                        </div>
                        <div class="row">
                           <div class="input-field col s12">
                              <span style="font-size: 11px;" for="image" style="font-size: 11px;">Anexar Arquivo</span>
                              <input style="font-size: 10px;" id="image" name="image" type="file" class="form-control" accept=".jpg,.png,.jpeg">
                           </div>
                         </div>
                      </div>

                      <div class="col s1 md-1" style="position: relative; top: 420px;">
                        <a class="btn-floating btn-mini waves-effect waves-light green tooltipped"  
                        data-position="left" data-tooltip="Salvar Alterações" id="btn" name="btn" type="submit"><i class="material-icons">check</i></a>
                      </div>
               </div>
              </form>
                  </div>

               </div>







      <script src="{{ asset('/public/materialize/js/vendors.min.js') }}"></script>
      <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
      <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>


<script>
$(document).ready(function() {
   //Verifico se ele já alterou a senha obrigatoria
   if({{$verificasenhaobrigatoria}} == 1) {
      $('#modalsenha').modal('open');
   }
});
</script>

<script type="text/javascript" >
 function verificameritocracia() {
         
   var cpf = '{{$cpf_criptografado}}';
   var email = '{{$email_criptografado}}';
   var cpf_local = window.localStorage.getItem('cpf');
   var email_local = window.localStorage.getItem('email');
      //Se for igual ele vai para Meritocracia se não retorna HomePage
      if(cpf != cpf_local && email != email_local) {
        alert('Favor redefinir sua senha de acesso no portal para acesso no modulo de meritocracia. Este problema custuma ocorrer quando o cache é limpado ou é trocado de navegador.')
      } else {

         //Se ele não possuir nenhum lançamento ele informa
         if('{{$verificalancamentos}}' == 0) {
            alert('Você não possui nenhuma nota cadastrada em nosso sistema. Favor entrar em contato com a equipe de GV.')
         } else {
            window.location.href = "{{URL::to('painel/gestao/meritocracia/index')}}"        
         }
      }   
 }   
</script>   


<script>
function envia() {
   //Salvo no localstorage o e-mail e senha 
   var cpf = '{{$cpf_criptografado}}';
   var email = '{{$email_criptografado}}';
    window.localStorage.setItem('email', email);
    window.localStorage.setItem('cpf', cpf);
    document.getElementById("form1").submit();

}    
</script>

<script>
         document.addEventListener("DOMContentLoaded", function () {
         $('.modal').modal();
         afterglow.getPlayer('video01').pause();
         afterglow.getPlayer('video02').pause();
         afterglow.getPlayer('videoAprovar').pause();
         afterglow.getPlayer('videoMeritocracia').pause();
         afterglow.getPlayer('video3').pause();
         afterglow.getPlayer('video4').pause();
         afterglow.getPlayer('videoti_indexacaoarquivos').pause();
         });
</script>

<script>
function pausevideo() {
   afterglow.getPlayer('video01').pause();
   afterglow.getPlayer('video02').pause();
   afterglow.getPlayer('videoAprovar').pause();
   afterglow.getPlayer('videoMeritocracia').pause();
   afterglow.getPlayer('video3').pause();
   afterglow.getPlayer('video4').pause();
   afterglow.getPlayer('videoti_indexacaoarquivos').pause();

}
</script>

<script>
   // mudar os icons do collapse
   $(".collapsible-header").on('click', function(){
      $name = $(this).attr('name');
      $father = $(this).data('father');
      $active = $(this).parent()[0].classList.contains("active")
      console.log($father);
      $("i[data-father="+$father+"][data-type=ls]").hide();
      $("i[data-father="+$father+"][data-type=mr]").show();

      if($active){
         $("#i-ls-"+$name).hide();
         $("#i-mr-"+$name).show();
      }
      else{
         $("#i-ls-"+$name).show();
         $("#i-mr-"+$name).hide();
      }
      
   })
</script>


</body>
</html>