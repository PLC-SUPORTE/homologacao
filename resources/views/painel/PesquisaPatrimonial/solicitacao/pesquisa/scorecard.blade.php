
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
          <li class="breadcrumb-item"><a href="{{ route('Painel.PesquisaPatrimonial.solicitacao.index') }}">Dashboard</a>
          </li>
          <li class="breadcrumb-item active" style="color: black;">Aba scorecard
          </li>
        </ol>
    </div>

              <ul class="navbar-list right" style="margin-top: -80px;">
              <li class="hide-on-med-and-down"><a class="waves-effect waves-block waves-light" href="{{ route('Painel.PesquisaPatrimonial.solicitacao.relatoriopesquisa', $codigo) }}"><i class="material-icons">insert_drive_file</i></a></li>
              <li class="hide-on-large-only search-input-wrapper"><a class="waves-effect waves-block waves-light search-button" href="javascript:void(0);"><i class="material-icons">search</i></a></li>
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
            <li><a href="{{ route('Painel.PesquisaPatrimonial.verpesquisa', $codigo)}}" style="font-size: 10px;"><i class="material-icons">content_paste</i><span><span class="dropdown-title" data-i18n="Dashboard">Status</span></a></li>
            <li><a href="{{ route('Painel.PesquisaPatrimonial.solicitacao.verpesquisaimovel', $codigo)}}" style="font-size: 10px;"><i class="material-icons">home</i><span><span class="dropdown-title" data-i18n="Imovel">Imóvel</span></a></li>
              <li><a href="{{ route('Painel.PesquisaPatrimonial.solicitacao.verpesquisaveiculo', $codigo)}}"  style="font-size: 10px;"><i class="material-icons">commute</i><span><span class="dropdown-title" data-i18n="Veiculo">Veículo</span></a></li>
              <li><a href="{{ route('Painel.PesquisaPatrimonial.solicitacao.verpesquisaempresa', $codigo)}}"  style="font-size: 10px;"><i class="material-icons">work</i><span><span class="dropdown-title" data-i18n="Empresa">Empresa</span></a></li>
              <li><a href="{{ route('Painel.PesquisaPatrimonial.solicitacao.verpesquisainfojud', $codigo)}}" style="font-size: 10px;"><i class="material-icons">content_paste</i><span><span class="dropdown-title" data-i18n="Infojud">Infojud</span></a></li>
              <li><a href="{{ route('Painel.PesquisaPatrimonial.solicitacao.verpesquisabacenjud', $codigo)}}" style="font-size: 10px;"><i class="material-icons">content_paste</i><span><span class="dropdown-title" data-i18n="Bacenjud">Bacenjud</span></a></li>
              <li><a href="{{ route('Painel.PesquisaPatrimonial.solicitacao.verpesquisaprotestos', $codigo)}}" style="font-size: 10px;"><i class="material-icons">sticky_note_2</i><span><span class="dropdown-title" data-i18n="Protestos">Protesto</span></a></li>
              <li><a href="{{ route('Painel.PesquisaPatrimonial.solicitacao.verpesquisaredessociais', $codigo)}}"  style="font-size: 10px;"><i class="material-icons">mood</i><span><span class="dropdown-title" data-i18n="Redes Sociais">Rede Social</span></a></li>
              <li><a href="{{ route('Painel.PesquisaPatrimonial.solicitacao.verpesquisaprocessosjudiciais', $codigo)}}"  style="font-size: 10px;"><i class="material-icons">folder_special</i><span><span class="dropdown-title" data-i18n="Processos Judiciais">Processos Judiciais</span></a></li>
              <li><a href="{{ route('Painel.PesquisaPatrimonial.solicitacao.verpesquisapesquisacadastral', $codigo)}}" style="font-size: 10px;"><i class="material-icons">content_paste</i><span><span class="dropdown-title" data-i18n="Pesquisa Cadastral">Pesquisa Cadastral</span></a></li>
              <li><a href="{{ route('Painel.PesquisaPatrimonial.solicitacao.verpesquisadossiecomercial', $codigo)}}"  style="font-size: 10px;"><i class="material-icons">content_paste</i><span><span class="dropdown-title" data-i18n="Dossie Comercial">Dossiê Comercial</span></a></li>
              <li><a href="{{ route('Painel.PesquisaPatrimonial.solicitacao.verpesquisadados', $codigo)}}"  style="font-size: 10px;"><i class="material-icons">content_paste</i><span><span class="dropdown-title" data-i18n="Dados">Dados</span></a></li>
              <li><a href="{{ route('Painel.PesquisaPatrimonial.solicitacao.verpesquisadiversos', $codigo)}}"  style="font-size: 10px;"><i class="material-icons">content_paste</i><span><span class="dropdown-title" data-i18n="Diversos">Diversos</span></a></li>
              <li><a href="{{ route('Painel.PesquisaPatrimonial.solicitacao.verpesquisamoeda', $codigo)}}"  style="font-size: 10px;"><i class="material-icons">content_paste</i><span><span class="dropdown-title" data-i18n="Moeda">Moeda</span></a></li>
              <li><a href="{{ route('Painel.PesquisaPatrimonial.solicitacao.verpesquisajoias', $codigo)}}"  style="font-size: 10px;"><i class="material-icons">content_paste</i><span><span class="dropdown-title" data-i18n="Joias">Joiais</span></a></li>
              <li><a href="{{ route('Painel.PesquisaPatrimonial.solicitacao.verpesquisascorecard', $codigo)}}"  style="font-size: 10px;"><i class="material-icons">preview</i><span><span class="dropdown-title" data-i18n="ScoreCard">Score</span></a></li>

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
               <div class="current-balance-container">
                   <center>
                   <canvas id="score" style="width: 350px; height: 140px" class="current-balance-shadow"></canvas>
                   </center>
               </div>
               <p class="center-align" style="font-size: 30px;font-weight: bold;">{{$score}}%</p>
               <p class="center-align" style="font-size: 15px;">de 100%</p>

               <div id="work-collections" class="seaction">
               <div class="row">

  
      <!--Card Motivo Diminui-->
      <div class="col s12 m12 xl6">
      <a href="#modaldiminui" class="modal-trigger">
         <ul id="projects-collection" class="collection z-depth-1">
            <li class="collection-item avatar">
            <i class="material-icons accent-2 circle" style="background-color: #dc3545">arrow_downward</i>
            <h6 class="collection-header m-0">Motivos que <b>Diminiuem</b> </h6>
            </li>
            <li class="collection-item">
               <div class="row">
                  <div class="col s9">
                     <div class="progress">
                     <div class="determinate" style="width: {{$scorefaltante}}%;background-color:#dc3545;color:#dc3545;"></div>
                     </div>
                  </div>
                  <div class="col s2"><span class="task-cat accent-2" style="color: black;font-size:15px;font-weight: bold;">{{$scorefaltante}}%</span></div>
               </div>
            </li>
         </ul>
         </a>
      </div>
      <!--Fim Card Motivo Diminui --> 

      <div class="col s12 m12 xl6">
        <a href="#modalaumenta" class="modal-trigger">
         <ul id="issues-collection" class="collection z-depth-1">
            <li class="collection-item avatar">
               <i class="material-icons accent-2 circle" style="background-color: #28a745">arrow_upward</i>
               <h6 class="collection-header m-0">Motivos que <b>Aumentam</b> </h6>
            </li>
            <li class="collection-item">
               <div class="row">
                  <div class="col s9">
                     <div class="progress">
                     @if($score == "100")
                    <div class="determinate" style="width: 100%;background-color:#28a745;color:#28a745;"></div>
                     @else 
                     <div class="determinate" style="width: {{$score}}%;background-color:#28a745;color:#28a745;"></div>
                     @endif
                     </div>
                  </div>
                  @if($score == "100")
                  <div class="col s2"><span class="task-cat accent-2" style="color: black;font-size:15px;font-weight: bold;">100%</span></div>
                  @else 
                  <div class="col s2"><span class="task-cat accent-2" style="color: black;font-size:15px;font-weight: bold;">{{$score}}%</span></div>
                  @endif
               </div>
            </li>
         </ul>
         </a>

      </div>
   </div>

    </div>

            </div>
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


     <!--Modal Diminuir Score -->
     <div id="modaldiminui" class="modal" style="width: 1200px;">
      <div class="modal-content">
      <button type="button" class="btn waves-effect mr-sm-1 mr-2 modal-close red" style="margin-left: 90%;">
              <i class="material-icons">close</i>
     </button>
      <h5>Fatores de influência</h5>
      <div class="row">
      <div class="col s12">

      <p style="color: black;">Esses fatores estão fazendo o score diminuir. Cuide da saúde financeira para aumentar a pontuação.</p>


      <!--Se o Score for total (100%) --> 
      @if($score == "100")
      <div class="card">
        <div class="card-content">
            <p class="caption mb-0">
                Todas as informações estão completas em nosso banco de dados.
            </p>
        </div>
      </div>

      <div class="card">
        <div class="card-content">
            <p class="caption mb-0">
                O valor do bem disponível é maior que o valor das dívidas.
            </p>
        </div>
      </div>
      @else
      <!--Fim Score menor que 100%-->

      <!--Se o Score for diferente que 100% --> 
      <div class="card">
        <div class="card-content">
            <p class="caption mb-0">
                Suas informações podem não estar completas. O score depende das informações do seu cadastro. 
            </p>
        </div>
      </div>


      @endif
      <!--Fim Score menor que 100%-->


      </div>
  </div>
      </div>
   </div>
   <!-- Fim Modal Diminuir Score-->    


   <!--Modal Aumenta Score -->
   <div id="modalaumenta" class="modal" style="width: 1200px;">
      <div class="modal-content">
      <button type="button" class="btn waves-effect mr-sm-1 mr-2 modal-close red" style="margin-left: 90%;">
              <i class="material-icons">close</i>
     </button>
      <h5>Fatores de influência</h5>
      <div class="row">
      <div class="col s12">

      <p style="color: black;">Esses fatores estão fazendo o score aumentar. Cuide da saúde financeira para manter os pontos positivos.</p>

      <!--Se o Score for 100% --> 
      @if($score == "100")
      <div class="card">
        <div class="card-content">
            <p class="caption mb-0">
                O score está perfeito ! Todas as informações preenchidas e com o bem disponível maior que o valor da alienação.
            </p>
        </div>
     </div>

      @else 

      <div class="card">
        <div class="card-content">
            <p class="caption mb-0">
               Todas as informações foram preenchidas.
            </p>
        </div>
     </div>

      @endif
      <!--Fim Score 100%-->


      </div>
  </div>
      </div>
   </div>
   <!-- Fim Modal Aumenta Score-->    






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


  </body>
</html>
