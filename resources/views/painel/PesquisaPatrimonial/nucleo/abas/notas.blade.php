<!DOCTYPE html>

<html class="loading" lang="pt-br" data-textdirection="ltr">
  <head>
    <meta http-equiv="Content-Language" content="pt-br">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="author" content="Portal PL&C">
    <title>Aba cadastro protestos | Portal PL&C</title>
    <link rel="apple-touch-icon" href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/images/favicon/apple-touch-icon-152x152.png">
    <link rel="shortcut icon" href="{{ asset('/public/assets/imgs/casinha.png')}}" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/vendors.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/materialize.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/style-horizontal.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/dashboard.min.css') }}">
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
                <li class="breadcrumb-item"><a href="{{route('Home.Principal.Show')}}">Home</a>
                  </li>
                  <li class="breadcrumb-item"><a href="{{ route('Painel.PesquisaPatrimonial.step1', ['codigo' => $codigo, 'id' => $numero])}}">Pesquisa</a>
                  </li>
                  <li class="breadcrumb-item active" style="color: black;">Cadastro Protestos
                  </li>
                </ol>
              </div>
            
              <ul class="navbar-list right" style="margin-top: -80px;">
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
          <div class="container">

<div class="section users-edit">
  <div class="card">
    <div class="card-content">

      <div class="divider mb-3"></div>
      <div class="row">
        <div class="col s12">


        <?= Form::open(['method'=>'POST', 'onsubmit' => "btnsubmit.disabled = true; return true;", 'files' => true, 'route' => "Painel.PesquisaPatrimonial.storenotas"]) ?>
         <?php @csrf ?>

         <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
         <input type="hidden" value="<?=$numero?>" class="mdl-textfield__input" id="id_matrix" name="id_matrix">
         <input type="hidden" value="<?=$codigo?>" class="mdl-textfield__input" id="codigo" name="codigo">


         <div class="row">
              <div class="col s12 m6">
                <div class="row">

                <div class="input-field col s12">
                       <div class="form-group">
                        <div class="form-group">
                        <label class="control-label">Observação:</label>
                        <textarea id="descricao" rows="6" type="text" name="descricao" class="form-control" placeholder="Insira a observação abaixo." style="height: 6rem;text-align:left; overflow:auto;width:1225px;font-size:10px;"></textarea>  
                      </div>
                    </div>
                </div>    
                </div>

                <div class="row">
                <div class="input-field col s4">
                <input style="font-size: 10px;" type="text" name="valor" id="valor" value="00,00" class="valor" onKeyPress="javascript:return(moeda2(this,event))"> 
                <label for="valor" style="color: black;font-size:11px;">Valor total:</label>
                </div>

                <div class="input-field col s4">
                <input style="font-size: 10px;" type="text" readonly="" name="valor_disponivel" id="valor_disponivel" value="00,00" class="valor">
                <label for="valor_disponivel" style="color: black;font-size:11px;">Valor disponível:</label>
                </div>
                </div>

                 <div class="row">
                  <div class="col s12 input-field">
                  <input style="font-size: 10px;" id="select_file" name="select_file[]" type='file' multiple required>
                  </div>
                </div>
              </div>
            
              </div>
             
              <div class="col s12 display-flex justify-content-end mt-3">
                <button type="submit" id="btnsubmit" class="btn" style="background-color: gray"><i class="material-icons left">save</i><span>&nbsp;	Salvar registro</button>
              </div>
            </div>
          </form>
        </div>

   
      </div>
    </div>
  </div>
</div>


</div>

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
    <script src="{{ asset('/public/materialize/js/dashboard-ecommerce.min.js') }}"></script>



    <script language="javascript">   
$(document).on("change", ".valor", function() {
        
        var valor = parseFloat($('#valor').val().replace('.', ''));

        document.getElementById('valor_disponivel').value=(valor.toFixed(2));

    });
</script>


<script language="javascript">   
 function moeda2(a, t) {

    var e = '.';
    var r = ',';

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

 
 
  </body>
</html>





