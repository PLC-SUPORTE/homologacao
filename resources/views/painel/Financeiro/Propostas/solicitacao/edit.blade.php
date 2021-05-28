@extends('painel.Layout.header')
<?php $search = 2 ?>
@section('title') Editar proposta @endsection
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
<link rel="stylesheet" type="text/css"
    href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/css/pages/form-select2.min.css">

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
Editar Proposta
@endsection

@section('submenu')
<li class="breadcrumb-item"><a href="{{route('Painel.Proposta.solicitacao.index')}}">Propostas</a></li>
<li class="breadcrumb-item active" style="color: black;">Editar Propostas</li>
@endsection
    <div>


        <center>
            <div id="loadingdiv" style="display:none">
                <div class="wrapper">
                    <div class="circle circle-1"></div>
                    <div class="circle circle-1a"></div>
                    <div class="circle circle-2"></div>
                    <div class="circle circle-3"></div>
                </div>
                <h1 style="text-align: center;">Aguarde, estamos atualizando a proposta...&hellip;</h1>
            </div>
        </center>


        <div id="corpodiv">
            <form id="form" role="form" action="{{ route('Painel.Proposta.solicitacao.update') }}" method="post"
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
                                                    <h4 class="card-title">Editar proposta</h4>
                                                </div>
                                                <div class="col s12 m6 l2">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="html-view-validations">
                                            <div class="row">

                                                <input type="hidden" name="id" id="id" value="{{$data->Id}}">

                                                <div class="input-field col s2">
                                                    <span style="font-size: 12px;">Selecione o segmento:</span>
                                                    <input style="font-size: 12px;" name="proposta" id="proposta"
                                                        value="{{$data->NumeroProposta}}" readonly type="text"
                                                        class="form-control" data-toggle="tooltip" data-placement="top"
                                                        title="Número da proposta gerado." required="required">
                                                </div>

                                                <div class="input-field col s2">
                                                    <span style="font-size: 12px;">Selecione o segmento:</span>
                                                    <select style="font-size: 12px;" class="browser-default"
                                                        required="required" style="width: 100%;" id="segmento"
                                                        name="segmento" data-toggle="tooltip" data-placement="top"
                                                        title="Selecione o segmento abaixo.">
                                                        <option selected="selected" value="{{$data->segmento_codigo}}">
                                                            {{$data->Segmento}}</option>
                                                        @foreach($segmentos as $segmento)
                                                        <option value="{{$segmento->codigo_grupofinanceiro}}">
                                                            {{$segmento->nome_grupofinanceiro}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="input-field col s2">
                                                    <span style="font-size: 12px;">Selecione o grupo:</span>
                                                    <div class="form-group">
                                                        <select style="font-size: 12px;" class="browser-default"
                                                            required="required" id="grupo" name="grupo"
                                                            data-toggle="tooltip" data-placement="top"
                                                            title="Selecione o grupo cliente abaixo.">
                                                            <option selected="selected" value="{{$data->grupo_codigo}}">
                                                                {{$data->GrupoCliente}}</option>
                                                            @foreach($grupos as $grupo)
                                                            <option value="{{$grupo->Codigo}}">{{$grupo->Descricao}}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="input-field col s6">
                                                    <span style="font-size: 12px;" class="control-label">Selecione o
                                                        cliente:</span>
                                                    <div class="form-group">
                                                        <div class="form-group">
                                                            <select style="font-size: 12px;" class="browser-default"
                                                                name="cliente" id="cliente" required="required">
                                                                <option value="{{$data->CodigoCliente}}">
                                                                    {{$data->Cliente}}</option>
                                                                @foreach($clientes as $cliente)
                                                                <option value="{{$cliente->Codigo}}">{{$cliente->Razao}}
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="row">
                                                <div class="input-field col s3">
                                                    <span style="font-size: 12px;" class="control-label">Selecione o
                                                        sócio responsável:</span>
                                                    <div class="form-group">
                                                        <div class="form-group">
                                                            <select style="font-size: 12px;" name="socio" id="socio"
                                                                readonly class="browser-default" required>
                                                                <option value="{{Auth::user()->cpf}}">
                                                                    {{Auth::user()->name}}</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="input-field col s2">
                                                    <span style="font-size: 12px;" class="control-label">Data
                                                        Cadastro:</span>
                                                    <div class="form-group">
                                                        <div class="form-group">
                                                            <input style="font-size: 12px;" name="data" id="data"
                                                                readonly type="text"
                                                                value="{{ date('d/m/Y H:i:s' , strtotime($data->Data)) }}"
                                                                class="form-control" data-toggle="tooltip"
                                                                data-placement="top"
                                                                title="Selecione a data da realização do serviço."
                                                                required="required">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="input-field col s2">
                                                    <div class="form-group">
                                                        <div class="form-group">
                                                            <span style="font-size: 12px;"
                                                                class="control-label">Referral:</span>
                                                            <select style="font-size: 12px;" name="referral"
                                                                id="referral" required="required"
                                                                class="browser-default">
                                                                @if($data->Referral == "S")
                                                                <option value="{{$data->Referral}}" selected>SIM
                                                                </option>
                                                                <option value="N">NÃO</option>
                                                                @else
                                                                <option value="{{$data->Referral}}" selected>NÃO
                                                                </option>
                                                                <option value="S">SIM</option>
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="input-field col s3">
                                                    <div class="form-group">
                                                        <div class="form-group">
                                                            <label class="control-label">Valor Global/Anual:</label>
                                                            <input style="font-size: 12px;" name="valor" id="valor"
                                                                type="text" value="{{$data->Valor}}" maxlength="8"
                                                                pattern="(?:\.|,|[0-9])*" class="form-control"
                                                                placeholder="Valor(R$)"
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
                                                                <span style="font-size: 12px;"
                                                                    class="control-label">Escopo:</span>
                                                                <textarea style="font-size: 12px;" id="escopo"
                                                                    required="required" rows="4" type="text"
                                                                    name="escopo" class="form-control"
                                                                    placeholder="Insira o escopo abaixo."
                                                                    style="text-align:left; overflow:auto;">
                         {{$data->Escopo}}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="input-field col s12">
                                                        <div class="form-group">
                                                            <div class="form-group">
                                                                <span style="font-size: 12px;"
                                                                    class="control-label">Observação:</span>
                                                                <textarea style="font-size: 12px;" id="observacao"
                                                                    rows="4" type="text" name="observacao"
                                                                    class="form-control"
                                                                    placeholder="Insira a observação abaixo."
                                                                    style="text-align:left; overflow:auto;">
                        {{$data->Observacao}} </textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="input-field col s12">
                                                    <button class="btn waves-effect waves-light right green"
                                                        type="button" id="btnsubmit" onClick="envia();"
                                                        name="action">Atualizar proposta
                                                        <i class="material-icons right">refresh</i>
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
    <script
        src="https://pixinvent.com/materialize-material-design-admin-template/app-assets/js/scripts/form-select2.min.js">
    </script>
    <script type='text/javascript'
        src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>


    <script>
    function envia() {

        document.getElementById("loadingdiv").style.display = "";
        document.getElementById("corpodiv").style.display = "none";
        document.getElementById("form").submit();
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