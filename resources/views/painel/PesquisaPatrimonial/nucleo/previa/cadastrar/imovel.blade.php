
<!DOCTYPE html>

<html class="loading" lang="pt-br" data-textdirection="ltr">
  <head>
    <meta http-equiv="Content-Language" content="pt-br">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="author" content="Portal PL&C">
    <title>Aba cadastro imóvel | Portal PL&C</title>
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
                  <li class="breadcrumb-item active" style="color: black;">Cadastro Imóvel
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


        <?= Form::open(['method'=>'POST', 'onsubmit' => "btnsubmit.disabled = true; return true;", 'files' => true, 'route' => "Painel.PesquisaPatrimonial.nucleo.pesquisaprevia.cadastrar.imovelstore"]) ?>
         <?php @csrf ?>

         <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
         <input type="hidden" value="<?=$id?>" class="mdl-textfield__input" id="id" name="id">


         <div class="row">
              <div class="col s12 m12">
                <div class="row">

                  <div class="col s2 input-field">
                    <span style="font-size: 11px">Número da matrícula</span>
                    <input style="font-size: 10px;" class="validate" name="numeromatricula" id="numeromatricula" type="text" required placeholder="Informe o número da matricula.">
                  </div>

                  <div class="col s2 input-field">
                    <span style="font-size: 11px;">Tipo de imóvel</span>
                    <select style="font-size: 10px;" id="tipoimovel" name="tipoimovel">
                    <option selected="selected" value=""></option>
                    <?php foreach($tipos as $data):?>  
                    <option value="<?=$data->Id?>"><?=$data->Descricao?></option>
                    <?php endforeach;?>
                    </select>
                  </div>

                 <div class="col s2 input-field">
                    <span style="font-size: 11px;">Data da matrícula</span>
                    <input style="font-size: 10px;" name="datamatricula" id="datamatricula"  type="date" max="{{$datahoje}}" class="validate">
                  </div>

                  <div class="col s2 input-field">
                  <span style="font-size: 11px;">Valor total</span>
                  <input style="font-size: 10px;" type="text" name="valor" id="valor" value="00,00" class="valor" onKeyPress="javascript:return(moeda2(this,event))"> 
                  </div>

                  <div class="col s2 input-field">
                  <span style="font-size: 11px;">Valor da alienação</span>
                  <input style="font-size: 10px;" type="text"  name="valor_alienacao" id="valor_alienacao" value="00,00" class="valor" onKeyPress="javascript:return(moeda2(this,event))">
                  </div>

                  <div class="col s2 input-field">
                  <span style="font-size: 11px">Valor disponível</span>
                  <input style="font-size: 10px;" required type="text" readonly="" name="valor_disponivel" id="valor_disponivel" value="00,00" class="valor">
                  </div>

                  <div class="col s2 input-field">
                    <span style="font-size: 11px">Data do requerimento</span>
                    <input  style="font-size: 10px;" class="validate" name="datarequerimento" id="datarequiremento" type="date" max="{{$datahoje}}">
                  </div>

                    <div class="col s2 input-field">
                      <span style="font-size: 11px">CEP</span>
                      <input style="font-size: 10px;" placeholder="Informe o CEP." name="cep" id="cep" value="" type="text"  size="10" maxlength="9" onblur="pesquisacep();"class="validate">
                    </div>
  
                    <div class="col s2 input-field">
                      <span style="font-size: 11px">Logradouro</span>
                      <input style="font-size: 10px;" placeholder="Informe o logradouro." name="rua" id="rua" value="" type="text" >
                    </div>
  
                    <div class="col s2 input-field">
                      <span style="font-size: 11px;">Bairro</span>
                      <input style="font-size: 10px;" placeholder="Informe o bairro." name="bairro" id="bairro" value="" type="text" >
                    </div>
  
                    <div class="col s2 input-field">
                      <span style="font-size: 11px;">Cidade</span>
                      <input style="font-size: 10px;" placeholder="Informe a cidade." name="cidade" id="cidade" value="" type="text" required>
                    </div>
  
                    <div class="col s1 input-field">
                      <span style="font-size: 11px">UF</span>
                      <input style="font-size: 10px;" placeholder="UF..." name="uf" id="uf" value="" type="text" required>
                    </div>

                    </div>

                    <div class="row">

                    <div class="col s2 input-field">
                      <span style="font-size: 11px;">Averbação Art. 828<span>
                      <select class="browser-default" style="font-size: 10px;"  id="averbaracao828" name="aberbacao828">
                      <option value="SIM">SIM</option>
                      <option value="NÃO">NÃO</option>
                      <option value="NA">NA</option>
                      </select>
                      </div>
        
                      <div class="col s2 input-field">
                        <span style="font-size: 11px">Houve Averb. da Penhora?</span>
                        <select class="browser-default" style="font-size: 10px;" id="averbacaopenhora" name="averbacaopenhora">
                        <option value="SIM">SIM</option>
                        <option value="NÃO">NÃO</option>
                        </select>
                      </div>
        
        
                      <div class="col s2 input-field">
                         <span style="font-size: 11px;">Carta foi enviada?</span>
                         <p>
                         <label>
                         <input class="with-gap" name="carta" type="radio" value="SIM" id="test4" onClick="datacartasim();" checked/>
                         <span style="font-size: 10px;">Sim</span>
                         </label>
        
                        <label>
                        <input class="with-gap" name="carta" type="radio" value="NAO" onClick="datacartanao();" id="test5" />
                        <span style="font-size: 10px;">Não</span>
                        </label style="font-size: 11px;">
                        </p>
                     </div>
        
                      <div class="col s2 input-field" id="datacartadiv">
                      <span style="font-size: 11px;">Data do envio da carta<span>
                      <input style="font-size: 10px;" class="validate" name="datacarta" id="datacarta" type="date" max="{{$datahoje}}">
                       </div>
        
                      <div class="col s2 input-field">
                      <span style="font-size: 11px;">Selecione o status</span>
                      <select class="browser-default" style="font-size: 10px;" required="required" id="status" name="status">
                      <?php foreach($status as $data):?>  
                      <option value="<?=$data->Id?>"><?=$data->Descricao?></option>
                      <?php endforeach;?>
                      </select>
                      </div>
        
                      <div class="col s2 input-field">
                      <span style="font-size: 11px;">Há Impedimento?</span>
                      <select class="browser-default" style="font-size: 10px;" id="impedimento" name="impedimento">
                      <option value="SIM">SIM</option>
                      <option value="NÃO">NÃO</option>
                      </select>
                      </div>

                      </div>

                      <div class="row">
        
                      <div class="col s2 input-field">
                            <input style="font-size: 10px;" id="select_file" name="select_file[]" type='file' multiple>
                      </div>
                    
                      <div class="col s12 display-flex justify-content-end mt-3">
                        <button type="submit" id="btnsubmit" class="btn" style="background-color: gray;">Salvar registro</button>
                      </div>

                </div>
              </div>

            <br>
       
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
$('#valor').blur(function(){
        
        var valor_alienacao = parseFloat($('#valor_alienacao').val().replace('.', ''));
        var valor = parseFloat($('#valor').val().replace('.', ''));

        var valor_disponivel = valor - valor_alienacao;

        document.getElementById('valor_disponivel').value=(valor_disponivel.toFixed(2));

    });
</script>




  <!-- Adicionando Javascript -->
  <script type="text/javascript" >
  
    function meu_callback(conteudo) {
        if (!("erro" in conteudo)) {
            //Atualiza os campos com os valores.
            document.getElementById('rua').value=(conteudo.logradouro);
            document.getElementById('bairro').value=(conteudo.bairro);
            document.getElementById('cidade').value=(conteudo.localidade);
            document.getElementById('uf').value=(conteudo.uf);
        } 
        else {
            //CEP não Encontrado.
            alert("CEP não encontrado.");
        }
    }
        
    function pesquisacep() {

      var valor = document.getElementById("cep").value

        //Nova variável "cep" somente com dígitos.
        var cep = valor;

        //Verifica se campo cep possui valor informado.
        if (cep != "") {

                //Preenche os campos com "..." enquanto consulta webservice.
                document.getElementById('rua').value="...";
                document.getElementById('bairro').value="...";
                document.getElementById('cidade').value="...";
                document.getElementById('uf').value="...";

                //Cria um elemento javascript.
                var script = document.createElement('script');

                //Sincroniza com o callback.
                script.src = '//viacep.com.br/ws/'+ cep + '/json/?callback=meu_callback';

                //Insere script no documento e carrega o conteúdo.
                document.body.appendChild(script);

            } //end if.
            else {
                //cep é inválido.
                alert("Formato de CEP inválido.");
            }
    };

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





