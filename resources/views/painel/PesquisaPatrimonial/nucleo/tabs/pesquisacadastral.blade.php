
<!DOCTYPE html>

<html class="loading" lang="pt-br" data-textdirection="ltr">
  <head>
    <meta http-equiv="Content-Language" content="pt-br">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="author" content="Portal PL&C">
    <title>Aba pesquisa cadastral | Portal PL&C</title>
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
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/style-horizontal.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/dashboard.min.css') }}">

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
          <li class="breadcrumb-item active" style="color: black;">Aba pesquisa cadastral
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
            <!-- users list start -->
<section class="users-list-wrapper section">
  <div class="users-list-filter">
    <div class="card-panel">
      <div class="row">
      <form role="form" id="form" action="{{ route('Painel.PesquisaPatrimonial.nucleo.finalizarpesquisacadastral') }}" method="POST" role="search"  enctype="multipart/form-data">
      {{ csrf_field() }}

       <input type="hidden" name="codigo" id="codigo" value="{{$codigo}}">
       <input type="hidden" name="numero" numero="numero" value="{{$numero}}">

        <div class="col s12 m6 l3 tooltipped" data-position="top" data-tooltip="Data da solicitação." >
        <label style="font-size: 11px;"  for="users-list-verified">Data solicitação:</label>
            <div class="input-field">
            @if($statuspesquisa != "Conclúida")
            <input type="date" name="data" id="data" value="{{$datapesquisa}}" required style="font-size: 10px;">
            @else 
            <input style="font-size: 10px;"  name="data" id="data" type="text" value="<?=date('d/m/Y', strtotime($datapesquisa))?>" readonly>
            @endif
            </div>
          </div>

          <div class="col s12 m6 l3 tooltipped" data-position="top" data-tooltip="Resultado da pesquisa." >
          <label style="font-size: 11px;"  for="users-list-role">Resultado da Pesquisa:</label>
             <div class="input-field">
             @if($statuspesquisa != "Conclúida")
              <select class="form-control" required="required"  id="resultado" name="resultado" style="font-size: 10px;">
               <option value="{{$resultadopesquisa}}">{{$resultadopesquisa}}</option>
               <option value="Positiva">Positiva</option>
               <option value="Negativa">Negativa</option>
               <option value="N/A">N/A</option>
              </select>
            @else 
            <input style="font-size: 10px;"  name="resultadopesquisa" id="resultadopesquisa" type="text" value="{{$resultadopesquisa}}" readonly>
            @endif
            </div>
          </div>

          <div class="col s12 m6 l3 tooltipped" data-position="top" data-tooltip="Status da pesquisa" >
          <label style="font-size: 11px;"  for="users-list-status">Status</label>
            <div class="input-field">
            @if($statuspesquisa != "Conclúida")
            <select class="form-control" id="status" name="status" required style="font-size: 10px;">
              <option value="{{$statuspesquisa}}">{{$statuspesquisa}}</option>
              <option value="Aguardando pagamento das custas">Aguardando pagamento das custas</option>
              <option value="Em andamento">Em andamento</option>
              <option value="Aguardando resposta">Aguardando resposta</option>
              <option value="Conclúida">Concluída</option>
             <option value="Suspensa">Suspensa</option>
             </select>
            @else 
            <input style="font-size: 10px;"  name="status" id="status" type="text" value="{{$statuspesquisa}}" readonly>
            @endif
            </div>
          </div>

          <div class="col s12 m6 l3 display-flex align-items-center show-btn">
          @if($statuspesquisa != "Conclúida")
          <a class="btn btn-block waves-effect waves-light tooltipped" data-position="top" data-tooltip="Clique aqui para cadastrar uma nova informação para esta pesquisa." style="background-color: gray"  href="{{ route('Painel.PesquisaPatrimonial.pesquisa', ['codigo' => $codigo, 'numero' => $numero])}}"><i class="material-icons">add</i></a>
          &nbsp;
          <a class="btn btn-block waves-effect waves-light tooltipped" data-position="top" data-tooltip="Clique aqui para salvar os filtros."  style="background-color: gray"  href="javascript:{}" onclick="document.getElementById('form').submit();"><i class="material-icons">save</i></a>
          @endif
          &nbsp;
          <a class="btn btn-block waves-effect waves-light tooltipped" data-position="top" data-tooltip="Clique aqui para gerar relatório em Excel desta pesquisa." style="background-color: gray" href="{{route('Painel.PesquisaPatrimonial.gerarexcelpesquisa', $codigo)}}"><img style="margin-top: 8px; width: 20px;margin-left:8px;" src="{{URL::asset('/public/imgs/icon.png')}}"/></a>
          &nbsp;
          </div>

        </form>
      </div>
    </div>
  </div>

  <div class="users-list-table">
    <div class="card">
      <div class="card-content">

        <div class="responsive-table">
          <table id="users-list-datatable" class="table">
            <thead>
              <tr>
                <th style="font-size: 11px;"></th>
                <th style="font-size: 11px;">Data</th>
                <th style="font-size: 11px;">Descrição</th>
                <th style="font-size: 11px;">Anexo</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach($datas as $data):?>
              <tr>
                <td style="font-size: 10px;"></td>
                <td style="font-size: 10px;"><?=date('d/m/Y', strtotime($data->data))?></td>
                <td style="font-size: 10px;"><?=$data->descricao?></td>
                <td style="font-size: 10px;"><a href="#modalanexos{{$codigo}}" class="tooltipped modal-trigger" data-position="bottom" data-tooltip="Clique aqui para visualizar os anexos deste pesquisado..."><i class="material-icons">attach_file</i></a></td>
              </tr>
             <?php endforeach;?>

            </tbody>
          </table>
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


 <!--Inicio Modal Anexos --> 
  <div id="modalanexos{{$codigo}}" class="modal modal-fixed-footer" style="width: 1200px;height:100%;overflow:hidden;">

<button type="button" class="btn waves-effect mr-sm-1 mr-2 modal-close red" style="margin-left: 1092px; margin-top: 5px;">
  <i class="material-icons">close</i> 
</button>

<iframe style=" position:absolute;
top:60;
left:0;
width:100%;
height:100%;" src="{{ route('Painel.PesquisaPatrimonial.veranexo', $codigo) }}"></iframe>

</div>
<!--Fim Modal Anexos --> 

<div id="alertastatusconcluida" name="alertastatusconcluida" class="cd-popup" role="alert" style="margin-top:130px;">
	<div class="cd-popup-container">
		<p style="font-weight: bold;">Ao selecionar a opção [Concluída], a pesquisa de [Pesquisa Cadastral] será finalizada e enviado um e-mail e notificação ao solicitante.</p>
		<ul class="cd-buttons" style="width: 800px">
			<li><a href="#" onClick="nao();" style="font-weight: bold;background-color:#b6bece">Fechar</a></li>
		</ul>
		<a onClick="nao();" class="cd-popup-close img-replace">Close</a>
	</div> 
</div> 

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
    <script src="{{ asset('/public/materialize/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/data-tables.min.js') }}"></script>
    <!-- <script src="{{ asset('/public/materialize/js/dashboard-ecommerce.min.js') }}"></script> -->

  
    <script>
document.addEventListener("DOMContentLoaded", function () {
   $('.modal').modal();
});
</script>

<script>
$('#status').on('change', function() {

  var status = $("#status").val();

  //Se for concluida, alerta
  if(status == "Conclúida") {

    $('#alertastatusconcluida').addClass('is-visible');

  }

});
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





