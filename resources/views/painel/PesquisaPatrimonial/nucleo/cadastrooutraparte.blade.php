@extends('painel.Layout.header')
@section('title') Cadastro outra parte @endsection <!-- Titulo da pagina -->

@section('header') 
    <link rel="apple-touch-icon" href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/images/favicon/apple-touch-icon-152x152.png">
    <link rel="shortcut icon" href="{{ asset('/public/assets/imgs/casinha.png')}}" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('/public/materialize/css/materialize.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/all.min.css') }}">
@endsection
@section('header_title')
Pesquisa patrimonial
@endsection
@section('submenu')
<li class="breadcrumb-item"><a href="{{ route('Painel.PesquisaPatrimonial.nucleo.index')}}">Dashboard</a>
</li>
<li class="breadcrumb-item active" style="color: black;">Cadastro outra parte
</li>
@endsection
@section('body')
    <div>
        <form role="form" onsubmit="btnsubmit.disabled = true; return true;" action="{{ route('Painel.PesquisaPatrimonial.storedadosoutraparte') }}" method="POST" role="search"  enctype="multipart/form-data">
          {{ csrf_field() }}
            <div class="container">
                <div class="section">

                <input name="codigo" id="codigo" type="hidden" value="{{$codigo}}" required="required" class="form-control">
    
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
                <form class="formValidate0" id="formValidate0" method="get">
                  <div class="row">
                    
                    <div class="input-field col s3">
                      <label for="nome" style="font-size: 11px;">Nome/Razão:</label>
                      <input style="font-size: 10px;" class="validate" placeholder="Informe o nome completo.." required="" name="nome" id="nome" type="text">
                    </div>

                    <div class="input-field col s3">
                        <select style="font-size: 10px;" id="sexo" name="sexo">
                        <option selected="selected" value=""></option>
                        <option value="M">Masculino</option>
                        <option value="F">Feminino</option>
                        </select>
                        <label style="font-size: 11px;" for="sexo">Sexo</label>
                      </div>

                      <div class="input-field col s3">
                          <input style="font-size: 10px;" class="validate" type="date" max="{{$datahoje}}" name="datanascimento" id="datanascimento">
                          <label style="font-size: 11px;">Data de Nascimento</label>
                      </div>

                      <div class="input-field col s3">
                        <label style="font-size: 11px;" for="identidade">Identidade</label>
                        <input style="font-size: 10px;" class="validate"  placeholder="Informe a identidade..." name="identidade" id="identidade" type="text">
                      </div>

                      <div class="input-field col s3">
                        <label style="font-size: 11px;" for="orgaoemissor">Orgão emissor</label>
                        <input style="font-size: 10px;" class="validate"  placeholder="Informe o orgão emissor" name="orgaoemissor" id="orgaoemissor" type="text">
                      </div>

                      <div class="input-field col s3">
                        <input style="font-size: 10px;" class="validate"  name="dataexpedicao" id="dataexpedicao" min="1940-01-01" type="date" max="{{$datahoje}}">
                        <label style="font-size: 11px;">Data de expedição</label>
                    </div>

                    <div class="input-field col s3">
                        <label style="font-size: 11px;" for="cep">CEP</label>
                        <input style="font-size: 10px;" class="validate" required="" placeholder="Informe o CEP..." name="cep" id="cep" value="" type="text" size="10" maxlength="9" onblur="pesquisacep();">
                    </div>

                    <div class="input-field col s3">
                        <label style="font-size: 11px;" for="rua">Rua</label>
                        <input style="font-size: 10px;" class="validate" placeholder="Informe a rua..." required="" name="rua" id="rua" type="text">
                    </div>

                    <div class="input-field col s3">
                        <label style="font-size: 11px;" for="bairro">Bairro</label>
                        <input style="font-size: 10px;" class="validate" placeholder="Informe o bairro..." required="" id="bairro" name="bairro" type="text">
                    </div>

                    <div class="input-field col s3">
                        <label style="font-size: 11px;" for="cidade">Cidade</label>
                        <input style="font-size: 10px;" class="validate" placeholder="Informe a cidade..." required="" name="cidade" id="cidade" type="text">
                    </div>

                    <div class="input-field col s3">
                        <label style="font-size: 11px;" for="uf">UF</label>
                        <input style="font-size: 10px;" class="validate" placeholder="Informe a UF..." name="uf" id="uf" required=""  type="text">
                    </div>

                    <div class="input-field col s3">
                        <select style="font-size: 10px;" id="estadocivil" name="estadocivil">
                            <option selected="selected" value=""></option>
                            <option value="1">Solteiro(a)</option>
                            <option value="2">Casado(a)</option>
                            <option value="3">Separado(a)/desquitado(a)/divorciado(a)</option>
                            <option value="4">Viúvo(a)</option>
                            <option value="5">Outro</option>
                        </select>
                        <label style="font-size: 11px;">Estado Civil</label>
                    </div>
                    
                     <div class="input-field col s3">
                        <label style="font-size: 11px;" for="profissao">Profissão</label>
                        <input style="font-size: 10px;" class="validate" placeholder="Profissão..."  name="profissao" id="profissao" type="text">
                    </div>

                    <div class="input-field col s3">
                        <label style="font-size: 11px;" for="valor">Rendimento Bruto</label>
                        <input style="font-size: 10px;"  class="validate" placeholder="Informe o rendimento bruto..."  pattern="(?:\.|,|[0-9])*" onKeyPress="return(moeda2(this,'.',',',event))" name="valor" id="valor" type="text">
                    </div>

                    <div class="input-field col s3">
                        <label style="font-size: 11px;" for="telefone">Telefone</label>
                        <input style="font-size: 10px;" class="validate" placeholder="Informe o telefone..." required="" id="telefone" name="telefone" type="text">
                    </div>

                    <div class="input-field col s3">
                        <select style="font-size: 10px;" id="tiporesidencia" name="tiporesidencia">
                            <option selected="selected" value=""></option>
                            <option value="1">Propria</option>
                            <option value="2">Alugada</option>
                            <option value="3">Financiada</option>
                        </select>
                        <label style="font-size: 11px;">Tipo de Residência</label>
                    </div>

                    <div class="input-field col s3">
                        <label style="font-size: 11px;" for="email">E-mail</label>
                        <input style="font-size: 10px;" class="validate" placeholder="Informe o e-mail..." name="email" id="email" type="email" required>
                    </div>

                    <div class="input-field col s3">
                        <label style="font-size: 11px;" for="pai">Nome do Pai</label>
                        <input style="font-size: 10px;" class="validate" placeholder="Informe o nome do pai completo..."  name="pai" id="pai" type="text">
                    </div>

                    <div class="input-field col s3">
                        <label style="font-size: 11px;" for="mae">Nome da Mãe</label>
                        <input style="font-size: 10px;" class="validate" placeholder="Informe o nome da mãe completo..."  name="mae" id="mae" type="text">
                    </div>
                      
               
                  <div class="input-field col s12">
                      <button class="btn waves-effect waves-light right" id="btnsubmit" style="background-color: gray;" type="submit" name="action">Gravar
                        <i class="material-icons left">save</i>
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
@endsection
    <script src="{{ asset('/public/materialize/js/vendors.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/all.min.js') }}"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.min.js"></script>



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
                    element.mask("(99) 99999-999?9");  
                } else {  
                    element.mask("(99) 9999-9999?9");  
                }  
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
    function moeda2(a, e, r, t) {
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

<script>
$(document).ready(function(){

 $('#search2').keyup(function(){ 
        var query = $(this).val();
        if(query != '')
        {
         var _token = $('input[name="_token"]').val();
         $.ajax({
          url:"{{ route('Painel.PesquisaPatrimonial.solicitacao.buscapesquisado') }}",
          method:"POST",
          data:{query:query, _token:_token},
          success:function(data){
           $('#countryList').fadeIn();  
           $('#countryList').html(data);
          }
         });
        }
    });

    $(document).on('click', 'li', function(){  
        $('#search2').val($(this).text());  
        $('#countryList').fadeOut();  
    });  

});
</script>
