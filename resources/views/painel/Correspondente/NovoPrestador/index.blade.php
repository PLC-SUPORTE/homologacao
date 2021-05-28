<!DOCTYPE html>

<html class="loading" lang="pt-br" data-textdirection="ltr">
  <head>
    <meta http-equiv="Content-Language" content="pt-br">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="author" content="Portal PL&C">
    <title>Novo correspondente | Portal PL&C</title>
    <link rel="apple-touch-icon" href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/images/favicon/apple-touch-icon-152x152.png">
    <link rel="shortcut icon" href="{{ asset('/public/assets/imgs/casinha.png')}}" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('/public/materialize/css/vendors.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/materialize.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/select2-materialize.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/form-select2.min.css') }}">

    <?php
      $connect = new PDO("sqlsrv:Server=192.168.150.14,65014;Database=PLCFULL", "six", "89202");
      // $connect = new PDO("sqlsrv:Server=localhost;Database=PLCFULL", "six", "89202");
      function fill_unit_select_box($connect)
      { 
       $output = '';
       $query = "SELECT * FROM Jurid_Capitais Where Cidade NOT LIKE '%''%' ORDER BY Cidade ASC";
       $statement = $connect->prepare($query);
       $statement->execute();
       $result = $statement->fetchAll();
       foreach($result as $row)
       {
        $output .= '<option value="'.$row["id"].'">'.$row["Cidade"].'</option>';
       }
       return $output;
      }
      
      ?>


  </head>

  <body class="vertical-layout vertical-menu-collapsible page-header-dark vertical-modern-menu preload-transitions 2-columns   " data-open="click" data-menu="vertical-modern-menu" data-col="2-columns">

    <header class="page-topbar" id="header">
      <div class="navbar navbar-fixed"> 
        <nav class="navbar-main navbar-color nav-collapsible navbar-dark no-shadow" style="background-color: gray">
          <div class="nav-wrapper">

            
            <div class="breadcrumbs-inline pt-3 pb-1" id="breadcrumbs-wrapper" style="margin-top: -40px;">
              <h5 class="breadcrumbs-title mt-0 mb-0 display-inline hide-on-small-and-down"><span style="color: white;">Correspondentes</span></h5>
              <ol class="breadcrumbs mb-0">
                  <li class="breadcrumb-item active" style="color: black;">Formúlario de cadastro correspondente
                  </li>
                </ol>
                </ol>
              </div>


            <ul class="navbar-list right" style="margin-top: -80px;">
              <li class="hide-on-large-only search-input-wrapper"><a class="waves-effect waves-block waves-light search-button" href="javascript:void(0);"><i class="material-icons">search</i></a></li>
              <li><a class="waves-block waves-light notification-button" href="javascript:void(0);" data-target="notifications-dropdown"><i class="material-icons">notifications_none<small class="notification-badge">0</small></i></a></li>
            </ul>

            <!-- notifications-dropdown-->
            <ul class="dropdown-content" id="notifications-dropdown">

              <li class="divider"></li>

            </ul>

          </div>

        </nav>

      </div>
    </header>
    <!-- END: Header-->


    <div id="main">
        <form role="form" onsubmit="btnsubmit.disabled = true; return true;" action="{{ route('Painel.Correspondente.NovoPrestador.store') }}" method="POST" role="search"  enctype="multipart/form-data">
          {{ csrf_field() }}
            <div class="container">
                <div class="section">

                <input name="id" id="id" type="hidden" value="{{$token}}" required="required" class="form-control">
    
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
                    
                    <div class="input-field col s3">
                      <label style="font-size: 11px;"  for="nome">Nome Completo/Razão social:</label>
                      <input style="font-size: 10px;" class="validate" value="{{$datas->descricao}}" required="" placeholder="Informe seu nome ou razão social..." name="descricao" id="descricao" type="text">
                    </div>

                    <div class="input-field col s2">
                          <input style="font-size: 10px;" class="validate" type="date" max="{{$datahoje}}" name="datanascimento" id="datanascimento" required="required">
                          <label style="font-size: 11px;" >Data de Nascimento:</label>
                      </div>

                      <div class="input-field col s2">
                        <label style="font-size: 11px;"  for="cep">CEP:</label>
                        <input style="font-size: 10px;" class="validate" placeholder="Informe o cep.." required="" name="cep" id="cep" value="" type="text" size="10" maxlength="9" onblur="pesquisacep();">
                    </div>

                    <div class="input-field col s3">
                        <label style="font-size: 11px;"  for="rua">Rua:</label>
                        <input style="font-size: 10px;" class="validate" placeholder="Informe a rua..." required="" name="endereco" id="endereco" type="text">
                    </div>

                    <div class="input-field col s2">
                        <label style="font-size: 11px;"  for="bairro">Bairro:</label>
                        <input style="font-size: 10px;" class="validate" placeholder="Informe o bairro..." required="" id="bairro" name="bairro" type="text">
                    </div>

                    <div class="input-field col s3">
                        <label style="font-size: 11px;"  for="cidade">Cidade:</label>
                        <input style="font-size: 10px;" class="validate" value="{{$datas->cidade}}" placeholder="Informe a cidade..." required="" name="cidade" id="cidade" type="text">
                    </div>

                    <div class="input-field col s1">
                        <label style="font-size: 11px;"  for="uf">UF:</label>
                        <input style="font-size: 10px;" class="validate" placeholder="UF" name="uf" id="uf" required=""  type="text">
                    </div>

                    <div class="input-field col s2">
                        <label style="font-size: 11px;"  for="telefone">Telefone:</label>
                        <input style="font-size: 10px;" class="validate" placeholder="Informe o número do telefone..." id="telefone" name="telefone" type="text" value="">
                    </div>

                    <div class="input-field col s2">
                        <label style="font-size: 11px;"  for="celular">Celular:</label>
                        <input style="font-size: 10px;" class="validate" placeholder="Informe o número do celular..." required="" id="celular" name="celular" value="" type="text">
                    </div>

                    <div class="input-field col s3">
                        <label style="font-size: 11px;"  for="email">E-mail:</label>
                        <input style="font-size: 10px;" class="validate" readonly value="{{$datas->email}}" name="email" id="email" type="email">
                    </div>

                    <div class="input-field col s2">
                        <input style="font-size: 10px;" value="{{$datas->codigo}}" placeholder="CPF/CNPJ" name="cpf_cnpj" id="cpf_cnpj" required="required" class="cpf_cnpj" maxlength="17" type="text">
                        <label style="font-size: 11px;"  for="cpf_cnpj">CPF/CNPJ:</label>
                    </div>

                    <div class="input-field col s2">
                        <label style="font-size: 11px;"  for="profissao">Identidade:</label>
                        <input style="font-size: 10px;" class="validate" placeholder="Informe a identidade..." required="" name="identidade" id="identidade" type="text">
                    </div>

                    <div class="input-field col s3">
                        <select style="font-size: 10px;" id="banco" name="banco" class="select2 browser-default" required>
                            @foreach($bancos as $banco)
                            <option value="{{$banco->Codigo}}">{{$banco->Descricao}}</option>
                            @endforeach
                        </select>
                        <label style="font-size: 11px;" >Selecione o banco:</label>
                    </div>

                    <div class="input-field col s3">
                        <select style="font-size: 10px;" id="tipoconta" name="tipoconta" class="select2 browser-default" required>
                            @foreach($tiposconta as $tipoconta)
                            <option value="{{$tipoconta->codigo}}">{{$tipoconta->descricao_tipo}}</option>
                            @endforeach
                        </select>
                        <label style="font-size: 11px;" >Selecione o tipo de conta:</label>
                    </div>

                    <div class="input-field col s2">
                        <label style="font-size: 11px;"  for="profissao">Agência:</label>
                        <input style="font-size: 10px;" class="validate" placeholder="Informe a agência..." required="" name="agencia" id="agencia" type="text">
                    </div>

                    <div class="input-field col s2">
                        <label style="font-size: 11px;" for="profissao">Conta:</label>
                        <input style="font-size: 10px;" class="validate" placeholder="Informe a conta..." required="" name="conta" id="conta" type="text">
                    </div>

                    </div>

                    <div class="row">
                           <div class="input-field col s12">
                              <div class="table-repsonsive">
                                 <table class="table table-bordered" id="item_table" style="font-size: 10px;">
                                    <tr>
                                       <th style="width:20%">UF</th>
                                       <th style="width:30%">Cidade</th>
                                       <th style="width:20%">Valor audiência</th>
                                       <th style="width:20%">Valor diligência</th>
                                       <th><button
                                          type="button" name="add" style="width: 170px;background-color:gray;"
                                          class="waves-effect waves-light btn add tooltipped border-round"
                                           data-position="left" data-tooltip="Clique aqui para adicionar uma nova comarca.">
                                          <span class="glyphicon glyphicon-plus"></span><i class="material-icons left">add</i>Adicionar</button>
                                      </th>
                                    </tr>

                                    @foreach($comarcas as $comarca)
                                    <tr>

                                       <input type="hidden" name="comarca_id[]" value="{{$comarca->id}}">

                                       <td>
                                       <input  readonly style="font-size:10px; margin-top: 5px;" type="text" name="comarca_uf[]" id="comarca_uf" class="form-control"  value="{{$comarca->comarca_uf}}"></input>
                                       </td>

                                       <td>
                                       <input  readonly style="font-size:10px; margin-top: 5px;" type="text" name="comarca_cidade[]" id="comarca_cidade" class="form-control"  value="{{$comarca->comarca_descricao}}"></input>
                                       </td>

                                       <td>
                                       <input  style="font-size:10px; margin-top: 5px;" data-toggle="tooltip" data-placement="top" title="Preencha o valor da audiência." type="text" name="comarca_valoraudiencia[]" id="comarca_valoraudiencia" class="form-control valorcorrespondente"  onKeyPress="javascript:return(moeda2(this,event))"  value="<?php echo number_format($comarca->valor_audiencia,2,",",".") ?>"></input>
                                       </td>

                                       <td>
                                       <input  style="font-size:10px; margin-top: 5px;" data-toggle="tooltip" data-placement="top" title="Preencha o valor da diligência." type="text" name="comarca_valordiligencia[]" id="comarca_valordiligencia" class="form-control valorcorrespondente"  onKeyPress="javascript:return(moeda2(this,event))"  value="<?php echo number_format($comarca->valor_diligencia,2,",",".") ?>"></input>
                                       </td>

                                       <td></td>
                                    </tr>
                                    @endforeach

                                 </table>
                              </div>
                           </div>
                        </div>

                  <div class="row">
                  <div class="input-field col s12">
                      <button class="btn waves-effect waves-light right" style="background-color: gray;" id="btnsubmit" type="submit" name="action">Enviar informações
                        <i class="material-icons right">send</i>
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

    <div id="modal3" class="modal bottom-sheet">
                  <div class="modal-content">
                    <h6>Aviso</h6>
                    <p style="font-size: 11px;">Consulte a nossa <a style="font-weight: bold;" target="_blank" href="{{ route('Painel.Correspondente.PoliticaPrivacidade') }}">Política de Privacidade</a> para ficar a saber mais e para gerir as suas preferências pessoais em nosso Portal. Ao clicar em <span style="font-weight: bold;">"Concordo"</span> você confirma que está de acordo com nossas políticas e reiteramos que seus dados serão armazenados em nossos bancos de dados exclusivamente para solicitações de pagamento. Terceiros não tem acesso as suas informações</p>
                  </div>
                  <div class="modal-footer">
                  <a class="modal-action modal-close waves-effect waves-green btn-flat" style="background-color: green;color: white;font-size: 11px;"><i class="material-icons left">check</i>Concordo</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
   
    </div>
              </div>
        </form>
    </div>

    <script src="{{ asset('/public/materialize/js/vendors.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>
    <script type='text/javascript' src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
    <script src="{{ asset('/public/AdminLTE/dist/js/valida_cpf_cnpj.js') }}"></script>
    <script src="{{ asset('/public/AdminLTE/dist/js/exemplo_2.js') }}"></script>
    <script src="{{ asset('/public/AdminLTE/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('/public/AdminLTE/dist/js/form-select2.min.js') }}"></script>


<script>
$(document).ready(function(){
   $('.modal').modal();
   $(".modal").modal("open");

});
</script>


<script type="text/javascript">

        jQuery("#telefone")
            .mask("(99) 9999-9999?9")
            .focusout(function (event) {  
                var target, phone, element;  
                target = (event.currentTarget) ? event.currentTarget : event.srcElement;  
                phone = target.value.replace(/\D/g, '');
                element = $(target);  
                element.unmask();  
                if(phone.length > 10) {  
                    element.mask("(99) 9999-9999");  
                } else {  
                    element.mask("(99) 3999-9999");  
                }  
            });
</script>

<script type="text/javascript">

        jQuery("#celular")
            .mask("(99) 9999-9999?9")
            .focusout(function (event) {  
                var target, phone, element;  
                target = (event.currentTarget) ? event.currentTarget : event.srcElement;  
                phone = target.value.replace(/\D/g, '');
                element = $(target);  
                element.unmask();  
                if(phone.length > 10) {  
                    element.mask("(99) 99999-99999");  
                } else {  
                    element.mask("(99) 9999-99999");  
                }  
            });
</script>

<script language="javascript">   
    $(document).ready(function($){
  $("input[id*='cpf_cnpj']").inputmask({
  mask: ['999.999.999-99', '99.999.999/9999-99']
  });

    });
</script>
    
    
        <!-- Adicionando Javascript -->
        <script type="text/javascript" >
    
        function meu_callback(conteudo) {
            if (!("erro" in conteudo)) {
                //Atualiza os campos com os valores.
                document.getElementById('endereco').value=(conteudo.logradouro);
                document.getElementById('bairro').value=(conteudo.bairro);
                document.getElementById('cidade').value=(conteudo.localidade);
                document.getElementById('uf').value=(conteudo.uf);
            } //end if.
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
                    document.getElementById('endereco').value="...";
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


<script>
            $(document).ready(function() {
            
             $(document).on('click', '.add', function(){
              var html = '';
              
              html += '<tr>';
              html += '<td><select style="font-size: 10px;" name="comarca_uf[]" required="required" class="select2 browser-default"><option value="AC">Acre</option><option value="AL">Alagoas</option><option value="AP">Amapá</option><option value="AM">Amazonas</option><option value="BA">Bahia</option><option value="CE">Ceará</option><option value="DF">Distrito Federal</option><option value="ES">Espírito Santo</option><option value="GO">Goiás</option><option value="MA">Maranhão</option><option value="MT">Mato Grosso</option><option value="MS">Mato Grosso do Sul</option><option value="MG">Minas Gerais</option><option value="PR">Pará</option><option value="PB">Paraíba</option><option value="PR">Paraná</option><option value="PE">Pernambuco</option><option value="PI">Piauí</option><option value="RJ">Rio de Janeiro</option><option value="RN">Rio Grande do Norte</option><option value="RS">Rio Grande do Sul</option><option value="RO">Rondônia</option><option value="RR">Roraima</option><option value="SC">Santa Catarina</option><option value="SP">São Paulo</option><option value="SE">Sergipe</option><option value="TOS">Tocantins</option></select></td>';
              html += '<td><select style="font-size: 10px;"name="comarca_cidade[]" required="required" class="select2 browser-default"><option value="">Selecione a comarca</option><?php echo fill_unit_select_box($connect); ?></select></td>'; 
              html += '<td><input style="font-size: 10px;" type="text" name="comarca_valoraudiencia[]" data-toggle="tooltip" data-placement="top" title="Preencha o valor da audiência." class="form-control valorcorrespodente"  onKeyPress="javascript:return(moeda2(this,event))"  value="00,00"</input></td>';
              html += '<td><input style="font-size: 10px;"type="text" name="comarca_valordiligencia[]" data-toggle="tooltip" data-placement="top" title="Preencha o valor da diligência." class="form-control valorcorrespodente"  onKeyPress="javascript:return(moeda2(this,event))"  value="00,00"</input></td>';
              html += '</tr>';
              $('#item_table').append(html);
             });
             
             $(document).on('click', '.remove', function(){
              $(this).closest('tr').remove();
             });
              
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