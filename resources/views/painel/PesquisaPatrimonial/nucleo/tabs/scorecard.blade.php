
<!DOCTYPE html>

<html class="loading" lang="pt-br" data-textdirection="ltr">
  <head>
    <meta http-equiv="Content-Language" content="pt-br">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="author" content="Portal PL&C">
    <title>Aba pesquisa - Pesquisa Patrimonial</title>
    <link rel="apple-touch-icon" href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/images/favicon/apple-touch-icon-152x152.png">
    <link rel="shortcut icon" href="{{ asset('/public/assets/imgs/casinha.png')}}" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/vendors.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/materialize.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/style-horizontal.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/dashboard-modern.css') }}"> 

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

  <body class="horizontal-layout page-header-light horizontal-menu preload-transitions 2-columns   " data-open="click" data-menu="horizontal-menu" data-col="2-columns">

    <!-- BEGIN: Header-->
    <header class="page-topbar" id="header">
      <div class="navbar navbar-fixed"> 
        <nav class="navbar-main navbar-color nav-collapsible sideNav-lock navbar-dark" style="background-color: gray">
           <div class="nav-wrapper">


          <div class="breadcrumbs-inline pt-3 pb-1" id="breadcrumbs-wrapper" style="margin-top: -40px;">
        <h5 class="breadcrumbs-title mt-0 mb-0 display-inline hide-on-small-and-down"><span style="color: white;">Pesquisa patrimonial</span></h5>
        <ol class="breadcrumbs mb-0">
          <li class="breadcrumb-item"><a href="{{ route('Home.Principal.Show') }}">Home</a>
          </li>
          <li class="breadcrumb-item"><a href="{{ route('Painel.PesquisaPatrimonial.nucleo.index') }}">Dashboard</a>
          </li>
          <li class="breadcrumb-item"><a href="{{route('Painel.PesquisaPatrimonial.nucleo.capa', ['codigo' => $codigo, 'numero' => $numero])}}">Capa</a>
          </li>
          <li class="breadcrumb-item active" style="color: black;">Aba score
          </li>
        </ol>
    </div>


                        <ul class="navbar-list right" style="margin-top: -80px;">
                        <li class="hide-on-med-and-down"><a class="waves-effect waves-block waves-light tooltipped" href="#" onClick="abreconfirmacao();" data-position="left" data-tooltip="Clique aqui para finalizar está pesquisa patrimonial."><i class="material-icons">done</i></a></li>
              <li class="hide-on-med-and-down"><a class="waves-effect waves-block waves-light" href="{{ route('Painel.PesquisaPatrimonial.solicitacao.relatoriopesquisa', $codigo) }}"><i class="material-icons">insert_drive_file</i></a></li>
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
        <!-- BEGIN: Horizontal nav start-->
        <nav class="white hide-on-med-and-down" id="horizontal-nav">
          <div class="nav-wrapper">
            <ul class="left hide-on-med-and-down" id="ul-horizontal-nav" data-menu="menu-navigation">
            <li><a href="{{ route('Painel.PesquisaPatrimonial.step1', ['codigo' => $codigo, 'id' => $numero])}}" style="font-size: 10px;"><i class="material-icons">content_paste</i><span><span class="dropdown-title" data-i18n="Dashboard">Status</span></a></li>
              <li><a href="{{ route('Painel.PesquisaPatrimonial.nucleo.tabimovel', ['codigo' => $codigo, 'numero' => $numero])}}" style="font-size: 10px;"><i class="material-icons">home</i><span><span class="dropdown-title" data-i18n="Imovel">Imóvel</span></a></li>
              <li><a href="{{ route('Painel.PesquisaPatrimonial.nucleo.tabveiculo', ['codigo' => $codigo, 'numero' => $numero])}}"  style="font-size: 10px;"><i class="material-icons">commute</i><span><span class="dropdown-title" data-i18n="Veiculo">Veículo</span></a></li>
              <li><a href="{{ route('Painel.PesquisaPatrimonial.nucleo.tabempresa', ['codigo' => $codigo, 'numero' => $numero])}}"  style="font-size: 10px;"><i class="material-icons">work</i><span><span class="dropdown-title" data-i18n="Empresa">Empresa</span></a></li>
              <li><a href="{{ route('Painel.PesquisaPatrimonial.nucleo.tabinfojud', ['codigo' => $codigo, 'numero' => $numero])}}" style="font-size: 10px;"><i class="material-icons">content_paste</i><span><span class="dropdown-title" data-i18n="Infojud">Infojud</span></a></li>
              <li><a href="{{ route('Painel.PesquisaPatrimonial.nucleo.tabbacenjud', ['codigo' => $codigo, 'numero' => $numero])}}" style="font-size: 10px;"><i class="material-icons">content_paste</i><span><span class="dropdown-title" data-i18n="Bacenjud">Bacenjud</span></a></li>
              <li><a href="{{ route('Painel.PesquisaPatrimonial.nucleo.tabprotestos', ['codigo' => $codigo, 'numero' => $numero])}}" style="font-size: 10px;"><i class="material-icons">sticky_note_2</i><span><span class="dropdown-title" data-i18n="Protestos">Protesto</span></a></li>
              <li><a href="{{ route('Painel.PesquisaPatrimonial.nucleo.tabredessociais', ['codigo' => $codigo, 'numero' => $numero])}}"  style="font-size: 10px;"><i class="material-icons">mood</i><span><span class="dropdown-title" data-i18n="Redes Sociais">Rede Social</span></a></li>
              <li><a href="{{ route('Painel.PesquisaPatrimonial.nucleo.tabprocessosjudiciais', ['codigo' => $codigo, 'numero' => $numero])}}"  style="font-size: 10px;"><i class="material-icons">folder_special</i><span><span class="dropdown-title" data-i18n="Processos Judiciais">Processos Judiciais</span></a></li>
              <li><a href="{{ route('Painel.PesquisaPatrimonial.nucleo.tabpesquisa', ['codigo' => $codigo, 'numero' => $numero])}}" style="font-size: 10px;"><i class="material-icons">content_paste</i><span><span class="dropdown-title" data-i18n="Pesquisa Cadastral">Pesquisa Cadastral</span></a></li>
              <li><a href="{{ route('Painel.PesquisaPatrimonial.nucleo.tabdossiecomercial', ['codigo' => $codigo, 'numero' => $numero])}}"  style="font-size: 10px;"><i class="material-icons">content_paste</i><span><span class="dropdown-title" data-i18n="Dossie Comercial">Dossiê Comercial</span></a></li>
              <li><a href="{{ route('Painel.PesquisaPatrimonial.nucleo.tabdados', ['codigo' => $codigo, 'numero' => $numero])}}"  style="font-size: 10px;"><i class="material-icons">content_paste</i><span><span class="dropdown-title" data-i18n="Dados">Dados</span></a></li>
              <li><a href="{{ route('Painel.PesquisaPatrimonial.nucleo.tabdiversos', ['codigo' => $codigo, 'numero' => $numero])}}"  style="font-size: 10px;"><i class="material-icons">content_paste</i><span><span class="dropdown-title" data-i18n="Diversos">Diversos</span></a></li>
              <li><a href="{{ route('Painel.PesquisaPatrimonial.nucleo.tabmoeda', ['codigo' => $codigo, 'numero' => $numero])}}"  style="font-size: 10px;"><i class="material-icons">content_paste</i><span><span class="dropdown-title" data-i18n="ScoreCard">Moeda</span></a></li>
              <li><a href="{{ route('Painel.PesquisaPatrimonial.nucleo.tabjoias', ['codigo' => $codigo, 'numero' => $numero])}}"  style="font-size: 10px;"><i class="material-icons">content_paste</i><span><span class="dropdown-title" data-i18n="ScoreCard">Joiais</span></a></li>
              <li><a href="{{ route('Painel.PesquisaPatrimonial.nucleo.tabscorecard', ['codigo' => $codigo, 'numero' => $numero])}}"  style="font-size: 10px;"><i class="material-icons">preview</i><span><span class="dropdown-title" data-i18n="ScoreCard">Score</span></a></li>
            </ul>
          </div>
          <!-- END: Horizontal nav start-->
        </nav>
      </div>
    </header>


    <!-- BEGIN: Page Main-->
    <div id="main">
      <div class="row">
        <div class="col s12">
  
        <div class="col s12">
          <div class="container">

<section class="users-list-wrapper section">


<div class="row vertical-modern-dashboard">
      <div class="col s12 m12 l12">
         <!-- Current Balance -->

         <div class="card animate fadeLeft">
            <div class="card-content">

            <h6 class="header mt-0">SCORE
              <a href="#detalhamentoscore" class="waves-effect waves-light btn gradient-shadow right modal-trigger" style="background-color: gray;font-size: 10px;">
              <i class="material-icons left">list</i>
              Detalhamento do score</a>
            </h6>

               <div class="current-balance-container">
                   <center>
                   <canvas id="score" style="width: 350px; height: 140px" class="current-balance-shadow"></canvas>
                   </center>
               </div>
               <p class="center-align" style="font-size: 30px;font-weight: bold;">{{$score}}</p>
               <p class="center-align" style="font-size: 15px;">de 100</p>


         </div>
      </div>


  </div>
</section>

</div>


          </div>
          <div class="content-overlay"></div>
        </div>
      </div>
    </div>
    <!-- END: Page Main-->


     <!--Modal Detalhamentos core -->
     <div id="detalhamentoscore" class="modal" style="width: 1200px;">
      <div class="modal-content">
      <button type="button" class="btn waves-effect mr-sm-1 mr-2 modal-close red" style="margin-left: 90%;">
              <i class="material-icons">close</i>
     </button>

      <h6>Detalhamento score</h6>
      <div class="row">
      <div class="col s12">

      <p style="color: black;font-size: 11px;">O quadro abaixo detalha como o score foi calculado em nosso sistema.</p>

      <div class="responsive-table">
          <table id="users-list-datatable" class="table">
            <thead>
              <tr>
                <th style="font-size: 11px;">Tipo</th>
                <th style="font-size: 11px;">Valor de referência</th>
                <th style="font-size: 11px;">Deflator base</th>
                <th style="font-size: 11px;">Valor referência - Deflator Base</th>
                <th style="font-size: 11px;">Deflator gravame</th>
                <th style="font-size: 11px;">Avaliação PLC</th>

              </tr>
            </thead>
            <tbody>


                <!--Veiculos --> 
                @foreach($veiculos as $veiculo)
                <tr>
                <td style="font-size: 10px;">Veículo</td> 
                <td style="font-size: 10px;">R$ <?php echo number_format($veiculo->veiculo_valor, 2,',', '.'); ?></td>
                <td style="font-size: 10px;">50,00%</td>
                <td style="font-size: 10px;">R$ <?php echo number_format($veiculo->veiculo_valorbase, 2,',', '.'); ?></td>
                <td style="font-size: 10px;">80,00%</td>
                <td style="font-size: 10px;">R$ <?php echo number_format($veiculo->veiculo_valoravaliacaoplc, 2,',', '.'); ?></td>
                </tr>
                @endforeach
                <!--Fim Veiculos --> 

                <!--Imóvel --> 
                @foreach($imovels as $imovel)
                <tr>
                <td style="font-size: 10px;">Imóvel</td> 
                <td style="font-size: 10px;">R$ <?php echo number_format($imovel->imovel_valor, 2,',', '.'); ?></td>
                <td style="font-size: 10px;">0%</td>
                <td style="font-size: 10px;">R$ <?php echo number_format($imovel->imovel_valorbase, 2,',', '.'); ?></td>
                <td style="font-size: 10px;">50,00%</td>
                <td style="font-size: 10px;">R$ <?php echo number_format($imovel->imovel_valoravaliacaoplc, 2,',', '.'); ?></td>
                </tr>
                @endforeach
                <!--Fim Imóvel --> 

                <!--Diversos --> 
                @foreach($diversos as $diverso)
                <tr>
                <td style="font-size: 10px;">Diversos</td> 
                <td style="font-size: 10px;">R$ <?php echo number_format($diverso->diverso_valor, 2,',', '.'); ?></td>
                <td style="font-size: 10px;">0%</td>
                <td style="font-size: 10px;">R$ <?php echo number_format($diverso->diverso_valorbase, 2,',', '.'); ?></td>
                <td style="font-size: 10px;">80,00%</td>
                <td style="font-size: 10px;">R$ <?php echo number_format($diverso->diverso_valoravaliacaoplc, 2,',', '.'); ?></td>
                </tr>
                @endforeach
                <!--Fim diversos --> 

                <!--Empresas --> 
                @foreach($empresas as $empresa)
                <tr>
                <td style="font-size: 10px;">Empresa</td> 
                <td style="font-size: 10px;">R$ <?php echo number_format($empresa->empresa_valor, 2,',', '.'); ?></td>
                <td style="font-size: 10px;">80,00%</td>
                <td style="font-size: 10px;">R$ <?php echo number_format($empresa->empresa_valorbase, 2,',', '.'); ?></td>
                <td style="font-size: 10px;">80,00%</td>
                <td style="font-size: 10px;">R$ <?php echo number_format($empresa->empresa_valoravaliacaoplc, 2,',', '.'); ?></td>
                </tr>
                @endforeach
                <!--Fim Empresas --> 

                <!--Moeda --> 
                @foreach($moedas as $moeda)
                <tr>
                <td style="font-size: 10px;">Moeda</td> 
                <td style="font-size: 10px;">R$ <?php echo number_format($moeda->moeda_valor, 2,',', '.'); ?></td>
                <td style="font-size: 10px;">0%</td>
                <td style="font-size: 10px;">R$ <?php echo number_format($moeda->moeda_valorbase, 2,',', '.'); ?></td>
                <td style="font-size: 10px;">100,00%</td>
                <td style="font-size: 10px;">R$ <?php echo number_format($moeda->moeda_valoravaliacaoplc, 2,',', '.'); ?></td>
                </tr>
                @endforeach
                <!--Fim Moeda --> 

                <!--Joias --> 
                @foreach($joias as $joia)
                <tr>
                <td style="font-size: 10px;">Joias</td> 
                <td style="font-size: 10px;">R$ <?php echo number_format($joia->joias_valor, 2,',', '.'); ?></td>
                <td style="font-size: 10px;">0%</td>
                <td style="font-size: 10px;">R$ <?php echo number_format($joia->joias_valorbase, 2,',', '.'); ?></td>
                <td style="font-size: 10px;">50,00%</td>
                <td style="font-size: 10px;">R$ <?php echo number_format($joia->joias_valoravaliacaoplc, 2,',', '.'); ?></td>
                </tr>
                @endforeach
                <!--Fim Joias --> 

                <tr></tr>

                <tr>
                <td style="font-size: 10px;font-weight: bold;">Total</td> 
                <td style="font-size: 10px;font-weight: bold;">R$ <?php echo number_format($somabruto, 2,',', '.'); ?></td>
                <td style="font-size: 10px;font-weight: bold;"></td>
                <td style="font-size: 10px;font-weight: bold;">R$ <?php echo number_format($somavalorbase, 2,',', '.'); ?></td>
                <td style="font-size: 10px;font-weight: bold;"></td>
                <td style="font-size: 10px;font-weight: bold;">R$ <?php echo number_format($somaavaliacaoplc, 2,',', '.'); ?></td>
                </tr>





            </tbody>
          </table>
        </div>

     
      </div>

      </div>
  </div>
      </div>
   </div>
   <!-- Fim Modal Diminuir Score-->    

   <div id="alertaconfirmacao" name="alertaconfirmacao" class="cd-popup" role="alert" style="margin-top:130px;">
	<div class="cd-popup-container">
		<p style="font-weight: bold;">Ao finalizar está solicitação de pesquisa patrimonial, não será possível preencher novas informações. Deseja finalizar está pesquisa?</p>
		<ul class="cd-buttons">
			<li><a href="#" onClick="nao();" style="font-weight: bold;">Não</a></li>
			<li><a href="#" onClick="sim();" style="font-weight: bold;">Sim</a></li>
		</ul>
		<a onClick="nao();" class="cd-popup-close img-replace">Close</a>
	</div> 
</div> 



    <script src="{{ asset('/public/materialize/js/vendors.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/app-invoice.min.js') }}"></script>
    <script src="https://bernii.github.io/gauge.js/dist/gauge.min.js"></script>


  <script>

$(document).ready(function(){
   $('.modal').modal();
});

</script>  

  <script type="text/javascript">
	var opts = {
		angle: 0, 
		lineWidth: 0.24,
		radiusScale: 1, // Raio relativo
		pointer: {
		  length: 0.6, // // Relativo ao raio do Gauge
		  strokeWidth: 0.035, // Largura do traço
		  color: '#000000' // Cor do ponteiro
		},
    staticZones: [
   {strokeStyle: "#dc3545", min: 0, max: 29.99}, // 0 a 30 Vermelho
   {strokeStyle: "yellow", min: 30.00, max: 69.99}, //30 a 70 Amarelo
   {strokeStyle: "#28a745", min: 70.00, max: 100.00}, // Green

   ],
		limitMax: false,     // Se false, valor maximo aumenta automaticamente se valor > valor maximo
		limitMin: false,     // Se true, o valor mínimo será fixo
		generateGradient: true,
		highDpiSupport: true,  
	};
	var target = document.getElementById('score'); // Elemento onde o gauge deve ser criado
	var gauge = new Gauge(target).setOptions(opts); // Criar gauge
	gauge.maxValue = 100; // Valor maximo
	gauge.setMinValue(0);  // Valor minimo
	gauge.animationSpeed = 10; // Velocidade da animacao
	gauge.set({{$score}}); // Valor a ser exibido
	</script>

<script>
function abreconfirmacao() {

   $('#alertaconfirmacao').addClass('is-visible');
}
</script>

<script>
  function sim() {
    $('.modal').css('display', 'none');
    $('.cd-popup').removeClass('is-visible');

    window.location.href = "{{ route('Painel.PesquisaPatrimonial.nucleo.finalizarpesquisa', $numero)}}";

  }    
</script>

<script>
 function nao() {
  $('.cd-popup').removeClass('is-visible');
 }
</script>


  </body>
</html>





