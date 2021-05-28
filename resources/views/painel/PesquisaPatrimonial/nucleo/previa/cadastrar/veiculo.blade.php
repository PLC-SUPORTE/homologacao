
<!DOCTYPE html>

<html class="loading" lang="pt-br" data-textdirection="ltr">
  <head>
    <meta http-equiv="Content-Language" content="pt-br">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="author" content="Portal PL&C">
    <title>Aba cadastro veículo | Portal PL&C</title>
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
                  <li class="breadcrumb-item active" style="color: black;">Cadastro Veículo
                  </li>
                </ol>
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
            <ul class="left hide-on-med-and-down" id="ul-horizontal-nav" data-menu="menu-navigation">
            <li><a href="{{ route('Painel.PesquisaPatrimonial.nucleo.pesquisaprevia.abas', $id)}}" style="font-size: 10px;"><i class="material-icons">list</i><span><span class="dropdown-title" data-i18n="Dashboard">Index</span></a></li>
            @foreach($abas as $aba)
             <li><a href="{{ route('Painel.PesquisaPatrimonial.nucleo.pesquisaprevia.abas.aba', ['id' => $id, 'tiposervico' => $aba->id])}}" style="font-size: 10px;"><i class="material-icons">content_paste</i><span><span class="dropdown-title" data-i18n="Dashboard">{{$aba->descricao}}</span></a></li>
             @endforeach
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


        <?= Form::open(['method'=>'POST', 'onsubmit' => "btnsubmit.disabled = true; return true;", 'files' => true, 'route' => "Painel.PesquisaPatrimonial.nucleo.pesquisaprevia.cadastrar.veiculostore"]) ?>
         <?php @csrf ?>

         <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
         <input type="hidden" value="<?=$id?>" class="mdl-textfield__input" id="id" name="id">


         <div class="row">
              <div class="col s12 m12">
                <div class="row">

                  <div class="col s2 input-field">
                    <span style="font-size: 11px">Placa</span>
                    <input style="font-size: 10px;" class="validate" name="placa" id="placa" type="text" required placeholder="Informe a placa.">
                  </div>

                  <div class="col s2 input-field">
                  <span style="font-size: 11px">Tipo de veículo</span>
                  <select class="browser-default" style="font-size: 10px;" required="required" id="tipoveiculo" name="tipoveiculo">
                  <option selected="selected" value=""></option>
                  <?php foreach($tipos as $data):?>  
                  <option value="<?=$data->Id?>"><?=$data->Descricao?></option>
                  <?php endforeach;?>
                  </select>
                  </div>

                  <div class="col s2 input-field">
                  <span style="font-size: 11px">Fabricante</span>
                  <input style="font-size: 10px;" name="fabricantes" id="fabricantes" type="text" required placeholder="Informe a fabricante do veiculo.">
                  </div>

                  <div class="col s2 input-field">
                  <span style="font-size: 11px">Ano do modelo</span>
                    <input style="font-size: 10px;" name="anomodelos" id="anomodelos" type="text" required placeholder="Informe o ano modelo do veiculo.">
                  </div>
  
                  <div class="col s2 input-field">
                   <span style="font-size: 11px">Descrição do veículo</span>
                      <input style="font-size: 10px;" name="descricaoveiculo" id="descricaoveiculo" type="text" required class="validate" placeholder="Digite a descrição do veiculo.">
                  </div>
  
                    <div class="col s2 input-field">
                    <span style="font-size: 11px">Combustível</span>
                      <input style="font-size: 10px;" name="combustivel" id="combustivel" type="text" required class="validate" placeholder="Informe o combustível do veículo.">
                    </div>
  
                    <div class="col s2 input-field">
                    <span style="font-size: 11px">Modelo</span>
                    <input style="font-size: 10px;" name="modelos" id="modelos" type="text" required placeholder="Informe o modelo do veiculo.">
                    </div>
  
                    <div class="col s2 input-field">
                    <span style="font-size: 11px;">Ano de fabricação</span>
                      <input style="font-size: 10px;" name="anofabricacao" id="anofabricacao" value="" type="text" required class="validate" placeholder="Informe o ano de fabricação.">
                    </div>
  
                    <div class="col s2 input-field">
                    <span style="font-size: 11px">Valor do véiculo na tabela fipe<span>
                    <input style="font-size: 10px;" type="text" name="valor" id="valor" value="00,00" class="valor" onKeyPress="javascript:return(moeda2(this,event))"> 
                    </div>

                  <div class="col s2 input-field">
                    <span style="font-size:11px">Valor da alienação</span>
                  <input style="font-size: 10px;" type="text"  name="valor_alienacao" id="valor_alienacao" value="00,00" class="valor" onKeyPress="javascript:return(moeda2(this,event))">
                  </div>

                  <div class="col s2 input-field">
                  <span style="font-size: 11px;">Valor disponível</span>
                  <input style="font-size: 10px;" required type="text" readonly="" name="valor_disponivel" id="valor_disponivel" value="00,00" class="valor">
                  </div>
          
                  <div class="col s2 input-field">
                  <span style="font-size: 11px">Há impedimento?</span>
                  <select class="browser-default" style="font-size: 10px;" required="required" id="impedimento" name="impedimento">
                    <option value="SIM">SIM</option>
                    <option value="NÃO">NÃO</option>
                  </select>
                  </div>

                  </div>


                  <div class="row">
        
                    <div class="col s2 input-field">
                    <span style="font-size:11px">Avervação Art. 828</span>
                      <select class="browser-default" style="font-size: 10px;" required="required" id="averbaracao828" name="aberbacao828">
                      <option value="SIM">SIM</option>
                      <option value="NÃO">NÃO</option>
                      <option value="NA">NA</option>
                      </select>
                    </div>

                    <div class="input-field col s2">
                      <span style="font-size: 11px">Houve Averb. da Penhora?<span>
                      <p>
                      <label>
                      <input class="with-gap" name="averbacaopenhora" type="radio" value="SIM" id="test4" onClick="dataaverbacaosim();" checked/>
                      <span style="font-size: 10px;">Sim</span>
                      </label>
     
                     <label>
                     <input class="with-gap" name="averbacaopenhora" type="radio" value="NÃO" onClick="dataaverbacaonao();" id="test5" />
                     <span style="font-size: 10px;">Não</span>
                     </label>
                     </p>
                  </div>

                  <div class="col s2 input-field" id="dataaverbacaodiv">
                   <span style="font-size:11px">Data da solicitação da averbação</span>
                    <input style="font-size: 10px;" class="validate" name="dataaverbacao" id="dataaverbacao" type="date" max="{{$datahoje}}"">
                 </div>

                 <div class="input-field col s2">
                  <span style="font-size: 11px">Carta foi enviada?</span>
                  <p>
                  <label>
                  <input class="with-gap" name="carta" type="radio" value="SIM" id="test4" onClick="datacartasim();" checked/>
                  <span style="font-size: 10px;">Sim</span>
                  </label>
 
                 <label>
                 <input class="with-gap" name="carta" type="radio" value="NAO" onClick="datacartanao();" id="test5" />
                 <span style="font-size: 10px;">Não</span>
                 </label>
                 </p>
                </div>

                <div class="col s2 input-field" id="datacartadiv">
                <span style="font-size:11px">Data do envio da carta</span>
                  <input style="font-size: 10px;" class="validate" name="datacarta" id="datacarta" type="date" max="{{$datahoje}}">
               </div>


                <div class="col s2 input-field">
                <span style="font-size: 11px">Status</span>
                <select class="browser-default" style="font-size: 10px;" required="required" id="status" name="status">
                <?php foreach($status as $data):?>  
                <option value="<?=$data->Id?>"><?=$data->Descricao?></option>
                <?php endforeach;?>
                </select>
                </div>

                </div>

                <div class="row">
                <div class="col s3 input-field">
                <input style="font-size: 10px;" id="select_file" name="select_file[]" type='file' multiple required>
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
    <script type='text/javascript' src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>

    
<script type="text/javascript">
 function dataaverbacaonao() {

   $("#dataaverbacaodiv").hide();

};
</script>

<script type="text/javascript">
 function dataaverbacaosim() {

   $("#dataaverbacaodiv").show();

};
</script>



<script language="javascript">   
$('#valor').blur(function(){
        
        var valor_alienacao = parseFloat($('#valor_alienacao').val().replace('.', ''));
        var valor = parseFloat($('#valor').val().replace('.', ''));

        var valor_disponivel = valor - valor_alienacao;

        document.getElementById('valor_disponivel').value=(valor_disponivel.toFixed(2));

    });
</script>



<script type="text/javascript">
 function datacartasim() {

   $("#datacartadiv").show();

};
</script>

<script type="text/javascript">
 function datacartanao() {

   $("#datacartadiv").hide();

};
</script>

<script language="javascript">

$(document).ready(function(){
    $("#placa").inputmask({mask: ['AAA-9999','AAA9A99']});
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





