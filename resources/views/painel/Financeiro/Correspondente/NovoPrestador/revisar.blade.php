<!DOCTYPE html>

<html class="loading" lang="pt-br" data-textdirection="ltr">
  <head>
    <meta http-equiv="Content-Language" content="pt-br">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="author" content="Portal PL&C">
    <title>Revisar novo prestador | Portal PL&C</title>
    <link rel="apple-touch-icon" href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/images/favicon/apple-touch-icon-152x152.png">
    <link rel="shortcut icon" href="{{ asset('/public/assets/imgs/casinha.png')}}" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('/public/materialize/css/vendors.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/materialize.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/style.min.css') }}">



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

  <body class="vertical-layout vertical-menu-collapsible page-header-dark vertical-modern-menu preload-transitions 2-columns   " data-open="click" data-menu="vertical-modern-menu" data-col="2-columns">

    <header class="page-topbar" id="header">
      <div class="navbar navbar-fixed"> 
        <nav class="navbar-main navbar-color nav-collapsible navbar-dark no-shadow" style="background-color: gray">
          <div class="nav-wrapper">

            
            <div class="breadcrumbs-inline pt-3 pb-1" id="breadcrumbs-wrapper" style="margin-top: -40px;">
              <h5 class="breadcrumbs-title mt-0 mb-0 display-inline hide-on-small-and-down"><span style="color: white;">Cadastro novo prestador serviço</span></h5>
              <ol class="breadcrumbs mb-0">
                <li class="breadcrumb-item"><a href="{{route('Home.Principal.Show')}}">Sair</a>
                  </li>
                  <li class="breadcrumb-item active" style="color: black;">Cadastro novo prestador serviço
                  </li>
                </ol>
                </ol>
              </div>


            <ul class="navbar-list right" style="margin-top: -80px;">
              <li class="hide-on-large-only search-input-wrapper"><a class="waves-effect waves-block waves-light search-button" href="javascript:void(0);"><i class="material-icons">search</i></a></li>
              <li><a class="waves-block waves-light notification-button" href="javascript:void(0);" data-target="notifications-dropdown"><i class="material-icons">notifications_none<small class="notification-badge">0</small></i></a></li>
              <li><a class="waves-effect waves-block waves-light profile-button" href="javascript:void(0);" data-target="profile-dropdown"><span class="avatar-status avatar-online"><img src="{{ asset('/public/AdminLTE/img/Avatar/img.png') }}" class="img-circle" alt="User Image"><i></i></span></a></li>            </ul>

            <!-- notifications-dropdown-->
            <ul class="dropdown-content" id="notifications-dropdown">

              <li class="divider"></li>

            </ul>

            <!-- profile-dropdown-->
            <ul class="dropdown-content" id="profile-dropdown">
              <li><a class="grey-text text-darken-1" href="#"><i class="material-icons">person_outline</i>Meu Perfil</a></li>
              <li class="divider"></li>
              <li><a class="grey-text text-darken-1" href="{{ route('logout') }}"><i class="material-icons">keyboard_tab</i>Sair</a></li>

            </ul>

          </div>

        </nav>

      </div>
    </header>
    <!-- END: Header-->


    <div id="main">


    <div id="loadingdiv3" style="display:none;margin-top:400px;margin-left:550px;">
       <img style="width: 100px; margin-top: -100px;" src="{{URL::asset('/public/imgs/loading.gif')}}"/>
    </div>

 
        <div id="corpodiv">
        <form id="form" role="form" onsubmit="btnsubmit.disabled = true; return true;" action="{{ route('Painel.Financeiro.NovoPrestador.revisado') }}" method="POST" role="search">
          {{ csrf_field() }}
            <div class="container">
                <div class="section">

                <input name="id" id="id" type="hidden" value="{{$datas->id}}" required="required" class="form-control">
    
      <div class="row">
        <div class="col s12">
          <div id="html-validations" class="card card-tabs">
            <div class="card-content">
              <div class="card-title">
                <div class="row">
                  <div class="col s12 m6 l2">
                  </div>
                </div>
              </div>
              <div id="html-view-validations">
              <div class="row">
                    
                    <div class="input-field col s2">
                      <label for="nome" style="font-size: 11px;">Nome Completo/Razão social:</label>
                      <input style="font-size: 10px;" class="validate" value="{{$datas->Descricao}}" name="descricao" id="descricao" type="text">
                    </div>

                    <div class="input-field col s2">
                      <label for="nome" style="font-size: 11px;">Advogado solicitante:</label>
                      <input style="font-size: 10px;" class="validate" value="{{$datas->Advogado}}" name="advogado" id="advogado" type="text">
                      <input type="hidden" name="advogado_id" id="advogado_id" value="{{$datas->AdvogadoID}}">
                      <input type="hidden" name="advogado_email" id="advogado_email" value="{{$datas->AdvogadoEmail}}">
                    </div>

                    <div class="input-field col s2">
                          <input style="font-size: 10px;" class="validate" type="text" value="{{ date('d/m/Y', strtotime($datas->DataNascimento)) }}">
                          <input type="hidden" name="datanascimento" id="datanascimento" value="{{$datas->DataNascimento}}">
                          <label style="font-size: 11px;">Data de Nascimento:</label>
                      </div>

                    <div class="input-field col s2">
                        <label for="rua" style="font-size: 11px;">Endereço:</label>
                        <input style="font-size: 10px;" class="validate" value="{{$datas->Endereco}}" required="" name="endereco" id="endereco" type="text">
                    </div>

                    <div class="input-field col s2">
                        <label for="bairro" style="font-size: 11px;">Bairro:</label>
                        <input style="font-size: 10px;" class="validate" value="{{$datas->Bairro}}" required="" id="bairro" name="bairro" type="text">
                    </div>

                    <div class="input-field col s2">
                        <label for="cidade" style="font-size: 11px;">Cidade:</label>
                        <input style="font-size: 10px;" class="validate" value="{{$datas->Cidade}}" required="" name="cidade" id="cidade" type="text">
                    </div>

                    <div class="input-field col s2">
                        <label for="cidade" style="font-size: 11px;">CEP:</label>
                        <input style="font-size: 10px;" class="validate" value="{{$datas->Cep}}" required="" name="cep" id="cep" type="text">
                    </div>

                    <div class="input-field col s1">
                        <label for="uf" style="font-size: 11px;">UF:</label>
                        <input style="font-size: 10px;" class="validate" value="{{$datas->UF}}" name="uf" id="uf" required=""  type="text">
                    </div>

                    <div class="input-field col s2">
                        <label for="telefone" style="font-size: 11px;">Telefone:</label>
                        <input style="font-size: 10px;" class="validate" value="{{$datas->Telefone}}" id="telefone" name="telefone" type="text">
                    </div>

                    <div class="input-field col s2">
                        <label for="telefone" style="font-size: 11px;">Celular:</label>
                        <input style="font-size: 10px;" class="validate" value="{{$datas->Celular}}" required="" id="celular" name="celular" type="text">
                    </div>

                    <div class="input-field col s3">
                        <label for="identidade" style="font-size: 11px;">E-mail:</label>
                        <input style="font-size: 10px;" class="validate" required="" name="email" id="email" type="email" value="{{$datas->Email}}">
                    </div>

                    <div class="input-field col s2">
                        <input style="font-size: 10px;" placeholder="CPF/CNPJ" name="cpf_cnpj" id="cpf_cnpj" value="{{$datas->Codigo}}" required="required" class="cpf_cnpj" maxlength="17" type="text">
                        <label style="font-size: 10px;" for="cpf_cnpj" style="font-size: 11px;">CPF/CNPJ:</label>
                    </div>

                    <div class="input-field col s2">
                        <label for="profissao" style="font-size: 11px;">Identidade:</label>
                        <input style="font-size: 10px;" class="validate" value="{{$datas->Identidade}}" required="" name="identidade" id="identidade" type="text">
                    </div>

                    <div class="input-field col s2">
                        <label for="profissao" style="font-size: 11px;">Banco:</label>
                        <input style="font-size: 10px;" class="validate" value="{{$datas->Banco}}" required="" name="banco" id="banco" type="text">
                    </div>

                    <div class="input-field col s2">
                        <label for="profissao" style="font-size: 11px;">Tipo de conta:</label>
                        <input style="font-size: 10px;" class="validate" value="{{$datas->TipoConta}}" required="" name="tipoconta" id="tipoconta" type="text">
                    </div>

                    <div class="input-field col s2">
                        <label for="profissao" style="font-size: 11px;">Agência:</label>
                        <input style="font-size: 10px;" class="validate" value="{{$datas->Agencia}}" required="" name="agencia" id="agencia" type="text">
                    </div>

                    <div class="input-field col s2">
                        <label for="profissao" style="font-size: 11px;">Conta:</label>
                        <input style="font-size: 10px;" class="validate" value="{{$datas->Conta}}" required="" name="conta" id="conta" type="text">
                    </div>


                  
                  <div class="input-field col s12">
                      <button class="btn waves-effect waves-light right" style="background-color: gray;font-size:11px;" id="btnsubmit" type="button" onClick="abreconfirmacao();" name="action">Aprovar
                        <i class="material-icons left">check</i>
                      </button>
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
              </div>
        </form>
    </div>

    <div id="loadingdiv3" style="display:none; margin-left: 600px; margin-top: -700px;">
      <img style="width: 100px; margin-top: -100px;" src="{{URL::asset('/public/imgs/loading.gif')}}"/>
    </div>

<div id="modalconfirmacao" class="modal"  style="width: 50% !important;height: 30% !important;">
    <div id="corpodiv3">
    <div class="modal-content">
      <center><p style="font-size: 18px;">Deseja confirmar o cadastro de novo correspondente?</p></center>
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


<script>
function abreconfirmacao() {

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
    $('.modal').css('display', 'none');
    document.getElementById("loadingdiv3").style.display = "";
    document.getElementById("corpodiv3").style.display = "none";
    document.getElementById("corpodiv").style.display = "none";
    document.getElementById("form").submit();
         
  }    
</script>



  </body>
</html>