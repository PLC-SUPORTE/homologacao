@extends('painel.Layout.header')
<?php $search = 2 ?>
@section('title') Nova proposta @endsection
<!-- Titulo da pagina -->

@section('header')
<link rel="apple-touch-icon"
    href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/images/favicon/apple-touch-icon-152x152.png">
<link rel="shortcut icon" href="{{ asset('/public/assets/imgs/casinha.png')}}" />
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<link rel="stylesheet" href="{{ asset('/public/materialize/css/materialize.min.css') }}">
<link rel="stylesheet" href="{{ asset('/public/materialize/css/style.min.css') }}">
<link rel="stylesheet" href="{{ asset('/public/materialize/css/custom.css') }}">
<link rel="stylesheet" href="{{ asset('/public/materialize/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('/public/materialize/css/select2-materialize.css') }}">
<link rel="stylesheet" href="{{ asset('/public/materialize/css/form-select2.min.css') }}">

<style>
* {
    box-sizing: border-box;
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
@endsection

@section('header_title')
Nova Proposta
@endsection

@section('submenu')
<li class="breadcrumb-item"><a href="{{route('Painel.Proposta.solicitacao.index')}}">Propostas</a></li>
<li class="breadcrumb-item active" style="color: black;">Nova Proposta</li>
@endsection

@section('body')
    <div id="countryList">
        <div>
            <center>
                <div id="loadingdiv" style="display:none">
                    <div class="wrapper">
                        <div class="circle circle-1"></div>
                        <div class="circle circle-1a"></div>
                        <div class="circle circle-2"></div>
                        <div class="circle circle-3"></div>
                    </div>
                    <h1 style="text-align: center;">Gravando registro(s)...&hellip;</h1>
                </div>
            </center>


            <div id="corpodiv">
                <form id="form" role="form" action="{{ route('Painel.Proposta.solicitacao.store') }}" method="post"
                    enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="container">
                        <div class="section">

                            <div class="row">
                                <div class="col s12">
                                    <div id="html-validations" class="card card-tabs">
                                        <div class="card-content">
                                            <div class="card-title">
                                                <div class="row">
                                                    <div class="col s12 m6 l10">
                                                        <h4 class="card-title">Cadastrar nova proposta</h4>
                                                    </div>
                                                    <div class="col s12 m6 l2">
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="html-view-validations">

                                                <div class="row">
                                                    <div class="col s3" style="margin-top: -10px;">
                                                        <p>
                                                            <label>Cliente cadastrado?:</label><br>
                                                            <label>
                                                                <input class="with-gap" name="radio" value="SIM"
                                                                    type="radio" onClick="cadastrado();" checked />
                                                                <span>Sim</span>
                                                            </label>
                                                            <label>
                                                                <input class="with-gap" name="radio" value="NÃO"
                                                                    type="radio" onClick="semcadastro();" />
                                                                <span>Não</span>
                                                            </label>
                                                        </p>
                                                    </div>
                                                </div>

                                                <br>

                                                <div class="row">


                                                    <div class="input-field col s3" style="margin-top: -10px;">
                                                        <span style="font-size: 11px;">Selecione o segmento:</span>
                                                        <select style="font-size: 12px;" class="browser-default"
                                                            required="required" style="width: 100%;" id="segmento"
                                                            name="segmento">
                                                            <option selected="selected" value=""></option>
                                                            @foreach($segmentos as $segmento)
                                                            <option value="{{$segmento->codigo_grupofinanceiro}}">
                                                                {{$segmento->nome_grupofinanceiro}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="input-field col s2" id="grupodiv1"
                                                        style="margin-top: -10px;">
                                                        <div class="form-group">
                                                            <span style="font-size: 11px;">Selecione o grupo:</span>
                                                            <select style="font-size: 12px;" class="browser-default"
                                                                required="required" name="grupo" data-toggle="tooltip"
                                                                data-placement="top"
                                                                title="Selecione o grupo cliente abaixo.">
                                                                <option selected="selected" value=""></option>
                                                                @foreach($grupos as $grupo)
                                                                <option value="{{$grupo->Codigo}}">{{$grupo->Descricao}}
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="input-field col s2" id="grupodiv2"
                                                        style="display:none; margin-top: -10px;">
                                                        <span style="font-size: 12px;">Informe o grupo:</span>
                                                        <div class="form-group">
                                                            <input style="font-size: 12px;" name="grupocadastrar"
                                                                type="text" maxlength="250" class="form-control"
                                                                placeholder="Informe o grupo..." data-toggle="tooltip"
                                                                data-placement="top"
                                                                title="Informe o grupo para cadastro..."
                                                                required="required">
                                                        </div>
                                                    </div>

                                                    <div class="input-field col s7" id="clientediv1"
                                                        style="margin-top: -10px;">
                                                        <span style="font-size: 11px;">Selecione o cliente:</span>
                                                        <div class="form-group">
                                                            <div class="form-group">
                                                                <select style="font-size: 12px;" class="browser-default"
                                                                    name="clientediv1" required="required">
                                                                    <option selected="selected" value=""></option>
                                                                    @foreach($clientes as $cliente)
                                                                    <option value="{{$cliente->Codigo}}">
                                                                        {{$cliente->Razao}}</option>
                                                                    @endforeach
                                                                </select>
                                                                {{-- <label class="control-label">Selecione o cliente:</label> --}}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="input-field col s7" id="clientediv2"
                                                        style="display:none; margin-top: -10px;">
                                                        <span style="font-size: 12px;" class="control-label">Informe o
                                                            cliente:</span>
                                                        <div class="form-group">
                                                            <input style="font-size: 12px;" name="clientecadastrar"
                                                                type="text" maxlength="250" class="form-control"
                                                                placeholder="Informe o cliente..." data-toggle="tooltip"
                                                                data-placement="top"
                                                                title="Informe o cliente para cadastro..."
                                                                required="required">
                                                        </div>
                                                    </div>

                                                    <div class="input-field col s5">
                                                        <span style="font-size: 12px;" class="control-label">Selecione o
                                                            sócio responsável:</span>
                                                        <div class="form-group">
                                                            <div class="form-group">
                                                                <select name="socio" id="socio" class="browser-default"
                                                                    required style="font-size: 12px;">
                                                                    <option value="{{Auth::user()->cpf}}">
                                                                        {{Auth::user()->name}}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="input-field col s2">
                                                        <span style="font-size: 12px;" class="control-label">Data de
                                                            cadastro:</span>
                                                        <div class="form-group">
                                                            <div class="form-group">
                                                                <input style="font-size: 12px;" name="data" id="data"
                                                                    type="date" max="{{$datahoje}}"
                                                                    value="{{$datahoje}}" class="form-control"
                                                                    data-toggle="tooltip" data-placement="top"
                                                                    title="Selecione a data da realização do serviço."
                                                                    required="required">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="input-field col s2">
                                                        <span style="font-size: 12px;"
                                                            class="control-label">Referral:</span>
                                                        <div class="form-group">
                                                            <div class="form-group">
                                                                <select name="referral" id="referral"
                                                                    required="required" class="browser-default"
                                                                    style="font-size: 12px;">
                                                                    <option value="S" selected>SIM</option>
                                                                    <option value="N">NÃO</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="input-field col s3">
                                                        <div class="form-group">
                                                            <div class="form-group">
                                                                <span style="font-size: 12px;"
                                                                    class="control-label">Valor Global/Anual:</span>
                                                                <input style="font-size: 12px;" name="valor" id="valor"
                                                                    type="text" maxlength="8" pattern="(?:\.|,|[0-9])*"
                                                                    class="form-control" placeholder="Valor(R$)"
                                                                    onKeyPress="return(moeda2(this,'.',',',event))"
                                                                    data-toggle="tooltip" data-placement="top"
                                                                    title="Preencha o valor(R$)." required="required">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="input-field col s12">
                                                            <div class="form-group">
                                                                <div class="form-group">
                                                                    <label class="control-label">Escopo:</label>
                                                                    <textarea style="font-size: 12px;" id="escopo"
                                                                        required="required" rows="4" type="text"
                                                                        name="escopo" class="form-control"
                                                                        placeholder="Insira o escopo abaixo."
                                                                        style="text-align:left; overflow:auto;">
                         </textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="input-field col s12">
                                                            <div class="form-group">
                                                                <div class="form-group">
                                                                    <label class="control-label">Observação:</label>
                                                                    <textarea style="font-size: 12px;" id="observacao"
                                                                        rows="4" type="text" name="observacao"
                                                                        class="form-control"
                                                                        placeholder="Insira a observação abaixo."
                                                                        style="text-align:left; overflow:auto;">
                        </textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="input-field col s12">
                                                            <div class="form-group">
                                                                <div class="form-group">
                                                                    <span style="font-size: 12px;"
                                                                        class="control-label">Anexar arquivo:</span><br>
                                                                    <input style="font-size: 12px;" id="select_file"
                                                                        name="select_file" type='file'
                                                                        class="form-control"
                                                                        accept=".jpg,.png,.xls,.xlsx,.pdf,.doc,.docx,.rtf,.jpeg"
                                                                        required />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="input-field col s12">
                                                        <button class="btn waves-effect waves-light right"
                                                            style="background-color: gray;color:white;" type="button"
                                                            id="btnsubmit" onClick="envia();" name="action">Cadastrar
                                                            <i class="material-icons left">save_alt</i>
                                                        </button>
                                                    </div>

                                                </div>
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

@section('scripts')
        <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
        <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
        <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>
        <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>
        <script src="{{ asset('/public/materialize/js/select2.full.min.js') }}"></script>
        <script src="{{ asset('/public/materialize/js/form-select2.min.js') }}"></script>

        <script type='text/javascript'
            src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>


        <script>
        function envia() {

            document.getElementById("loadingdiv").style.display = "";
            document.getElementById("corpodiv").style.display = "none";
            document.getElementById("form").submit();
        }
        </script>

        <script>
        function cadastrado() {

            document.getElementById("grupodiv1").style.display = "";
            document.getElementById("clientediv1").style.display = "";
            document.getElementById("grupodiv2").style.display = "none";
            document.getElementById("clientediv2").style.display = "none";
        }

        function semcadastro() {

            document.getElementById("grupodiv1").style.display = "none";
            document.getElementById("clientediv1").style.display = "none";
            document.getElementById("grupodiv2").style.display = "";
            document.getElementById("clientediv2").style.display = "";
        }
        </script>


        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>

        <script language="javascript">
        function moeda2(a, e, r, t) {
            let n = "",
                h = j = 0,
                u = tamanho2 = 0,
                l = ajd2 = "",
                o = window.Event ? t.which : t.keyCode;
            if (13 == o || 8 == o)
                return !0;
            if (n = String.fromCharCode(o),
                -1 == "0123456789".indexOf(n))
                return !1;
            for (u = a.value.length,
                h = 0; h < u && ("0" == a.value.charAt(h) || a.value.charAt(h) == r); h++)
            ;
            for (l = ""; h < u; h++)
                -
                1 != "0123456789".indexOf(a.value.charAt(h)) && (l += a.value.charAt(h));
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

@endsection