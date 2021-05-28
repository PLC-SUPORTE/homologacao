
<!DOCTYPE html>

<html class="loading" lang="pt-br" data-textdirection="ltr">
  <head>
    <meta http-equiv="Content-Language" content="pt-br">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="author" content="Portal PL&C">
    <title>Abas da pesquisa prévia | Portal PL&C</title>
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
          <li class="breadcrumb-item"><a href="{{route('Painel.PesquisaPatrimonial.solicitacao.solicitacoes')}}">Solicitações em andamento</a>
          </li>
          <li class="breadcrumb-item active" style="color: black;">Abas da pesquisa prévia
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
        <!-- BEGIN: Horizontal nav start-->
        <nav class="white hide-on-med-and-down" id="horizontal-nav">
          <div class="nav-wrapper">
            <ul class="left hide-on-med-and-down" id="ul-horizontal-nav" data-menu="menu-navigation" style="overflow: hidden">
             
             @foreach($abas as $aba)
             <li><a href="{{ route('Painel.PesquisaPatrimonial.solicitacao.pesquisaprevia.abas.aba', ['id' => $id, 'tiposervico' => $aba->id])}}" style="font-size: 10px;"><i class="material-icons">content_paste</i><span><span class="dropdown-title" data-i18n="Dashboard">{{$aba->descricao}}</span></a></li>
             @endforeach
             </ul>
          </div>
          <!-- END: Horizontal nav start-->
        </nav>
      </div>
    </header>

  
    <!-- BEGIN: Page Main-->
    <div id="main">

      <div class="row" id="div_all">
        <div class="col s12">
  
        <div class="col s12">
          <div class="container">

<section class="users-list-wrapper section">

  <div class="users-list-table">
    <div class="card">
      <div class="card-content">

        <div class="responsive-table">
          <table id="users-list-datatable" class="table">
            <thead>
            <tr>
                <th style="font-size: 11px;"></th>
                <th style="font-size: 11px;">Matrícula</th>
                <th style="font-size: 11px;">UF</th>
                <th style="font-size: 11px;">Tipo Imóvel</th>
                <th style="font-size: 11px;">Valor total</th>
                <th style="font-size: 11px;">Valor alienação</th>
                <th style="font-size: 11px;">Valor disponível</th>
                <th style="font-size: 11px;">Aver.Penhora?</th>
                <th style="font-size: 11px;">Status</th>
                <th style="font-size: 11px;">Há restrição?</th>
                <th style="font-size: 11px;">Visualizar</th>
                <th style="font-size: 11px;">Anexo</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach($datas as $data):?>
              <tr>
<!-- modal -->
<div id="modal{{$data->matricula}}" class="modal" style="width: 1100px;">
<div class="modal-content">
    <div class="row">
     <div class="col s12 m12">

     <button type="button" class="btn waves-effect mr-sm-1 mr-2 modal-close red" style="margin-left: 920px;">
              <i class="material-icons">close</i>
     </button>

                <div class="row">
                  <div class="col s12">
                    <h6 class="mb-2">Dados Imóvel / Endereço: {{$data->matricula}}</h6>
                  </div>

                  <div class="col s2 input-field">
                    <input style="font-size: 10px;" class="validate" name="numeromatricula" readonly value="{{$data->matricula}}" id="numeromatricula" type="text">
                    <label style="font-size: 11px;" >Número da matricula:</label>
                  </div>

                  <div class="col s2 input-field">
                  <input  style="font-size: 10px;"class="validate" name="tipodescricao" readonly value="{{$data->tipodescricao}}" id="tipodescricao" type="text">
                  <label style="font-size: 11px;" >Tipo de imóvel:</label>
                  </div>

                 <div class="col s2 input-field">
                  <input style="font-size: 10px;" name="datamatricula" id="datamatricula" readonly type="text" value="<?=date('d/m/Y', strtotime($data->datamatricula))?>" class="validate">
                  <label style="font-size: 11px;"  for="datamatricula">Data da matricula:</label>
                  </div>

                  <div class="col s2 input-field">
                    <input style="font-size: 10px;" name="valor" id="valor" readonly value="R$ <?php echo number_format($data->valor, 2,',', '.'); ?>" type="text" placeholder="Valor(R$)" class="validate" type="text">
                    <label style="font-size: 11px;"  for="valor">Valor total:</label>
                  </div>

                  <div class="col s2 input-field">
                    <input style="font-size: 10px;" name="valor_alienacao" id="valor_alienacao" readonly value="R$ <?php echo number_format($data->valor_alienacao, 2,',', '.'); ?>" type="text" placeholder="Valor(R$)" class="validate" type="text">
                    <label style="font-size: 11px;"  for="valor_alienacao">Valor alienação:</label>
                  </div>

                  <div class="col s2 input-field">
                    <input style="font-size: 10px;" name="valor_disponivel" id="valor_disponivel" readonly value="R$ <?php echo number_format($data->valor_disponivel, 2,',', '.'); ?>" type="text" placeholder="Valor(R$)" class="validate" type="text">
                    <label style="font-size: 11px;"   for="valor_disponivel">Valor disponível:</label>
                  </div>

                  <div class="col s2 input-field">
                  @if($data->datarequerimento != null)
                    <input style="font-size: 10px;" class="validate" name="datarequerimento" readonly id="datarequiremento" type="text" value="<?=date('d/m/Y', strtotime($data->datarequerimento))?>" >
                  @else 
                  <input style="font-size: 10px;" class="validate" name="datarequerimento" readonly id="datarequiremento" type="text" value="" >
                  @endif
                    <label style="font-size: 11px;" >Data requerimento:</label>
                  </div>

                  <div class="col s2 input-field">
                  <input style="font-size: 10px;" class="validate" name="averbacao828" readonly value="{{$data->averbacao828}}" type="text">
                  <label style="font-size: 11px;" >Averbação Art. 828:</label>
                  </div>
  
                  <div class="col s2 input-field">
                  <input style="font-size: 10px;" class="validate" name="penhora" readonly value="{{$data->penhora}}" type="text">
                  <label style="font-size: 11px;" >Houve Averb. da Penhora?</label>
                  </div>
  
                  <div class="input-field col s2">
                  <input style="font-size: 10px;" class="validate" name="carta" readonly value="{{$data->carta}}" type="text">
                  <label style="font-size: 11px;" >Houve carta?</label>
                 </div>
                
                <div class="col s2 input-field" id="datacartadiv">
                @if($data->datacarta != null)
                  <input style="font-size: 10px;" class="validate" name="datacarta" id="datacarta" readonly type="text" value="<?=date('d/m/Y H:i:s', strtotime($data->datacarta))?>">
                @else 
                <input style="font-size: 10px;" class="validate" name="datacarta" id="datacarta" readonly type="text" value="">
                @endif
                  <label style="font-size: 11px;" >Data envio carta</label>
               </div>
  
                <div class="col s2 input-field">
                <input style="font-size: 10px;" class="validate" name="status" id="status" readonly type="text" value="{{$data->status}}">
                <label style="font-size: 11px;" >Status:</label>
                </div>
  
                <div class="col s2 input-field">
                <input style="font-size: 10px;" class="validate" name="impedimento" id="impedimento" readonly type="text" value="{{$data->restricao}}">
                <label style="font-size: 11px;" >Há Impedimento?</label>
                </div>
                
                  <div class="col s2 input-field">
                    <input style="font-size: 10px;" name="cep" id="cep" type="text" readonly value="{{$data->cep}}">
                    <label style="font-size: 11px;"  for="cep">CEP</label>
                  </div>

                  <div class="col s2 input-field">
                    <input style="font-size: 10px;" name="rua" id="rua" type="text" readonly value="{{$data->logradouro}}">
                    <label style="font-size: 11px;" for="rua">Logradouro</label>
                  </div>

                 <div class="col s2 input-field">
                  <input style="font-size: 10px;" name="bairro" id="bairro" type="text" readonly value="{{$data->bairro}}">
                  <label style="font-size: 11px;"  for="bairro">Bairro</label>
                  </div>

                  <div class="col s3 input-field">
                    <input style="font-size: 10px;" name="cidade" id="cidade" type="text" readonly value="{{$data->cidade}}">
                    <label style="font-size: 11px;"  for="cidade">Cidade</label>
                  </div>

                  <div class="col s1 input-field">
                    <input style="font-size: 10px;" name="uf" id="uf" type="text" readonly value="{{$data->uf}}">
                    <label style="font-size: 11px;" for="uf">UF</label>
                  </div>

                </div>
              </div>
    </div>
  </div>
<!-- modal -->         

                <td style="font-size: 10px;"></td>
                <td style="font-size: 10px;"><?=$data->matricula?></td>
                <td style="font-size: 10px;"><?=$data->uf?></td>
                <td style="font-size: 10px;"><?=$data->tipodescricao?></td>
                <td style="font-size: 10px;">R$ <?php echo number_format($data->valor, 2,',', '.'); ?></td>
                <td style="font-size: 10px;">R$ <?php echo number_format($data->valor_alienacao, 2,',', '.'); ?></td>
                <td style="font-size: 10px;">R$ <?php echo number_format($data->valor_disponivel, 2,',', '.'); ?></td>
                <td style="font-size: 10px;"><?=$data->penhora?></td>
                <td style="font-size: 10px;"><?=$data->status?></td>
                <td style="font-size: 10px;"><?=$data->restricao?></td>
                <td style="font-size: 10px;"><a class="waves-effect waves-light  modal-trigger" href="#modal{{$data->matricula}}"><i class="material-icons">remove_red_eye</i></a></td>
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
    
  
    <script src="{{ asset('/public/materialize/js/vendors.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/data-tables.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/dashboard-ecommerce.min.js') }}"></script>

  
 <script>


document.addEventListener("DOMContentLoaded", function () {
   $('.modal').modal();
});
</script>




  </body>
</html>





