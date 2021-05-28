@extends('painel.Layout.header')
<?php $search = 2 ?>
@section('title') Revisar proposta @endsection
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

.wrapper {
    height: 50px;
    margin-top: calc(50vh - 150px);
    margin-left: calc(50vw - 600px);
    width: 180px;
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
Revisar proposta
@endsection

@section('submenu')
<li class="breadcrumb-item"><a href="{{route('Painel.Proposta.revisao.index')}}">Dashboard</a></li>
<li class="breadcrumb-item active" style="color: black;">Revisar proposta</li>
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
                    <h1 style="text-align: center;">Aguarde, estamos atualizando a proposta...&hellip;</h1>
                </div>
            </center>


            <div id="corpodiv">
                <form id="form" role="form" action="{{ route('Painel.Proposta.revisao.update') }}" method="post"
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
                                                        <h4 class="card-title">Revisar proposta</h4>
                                                    </div>
                                                    <div class="col s12 m6 l2">
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="html-view-validations">
                                                <div class="row">

                                                    <input type="hidden" name="id" id="id" value="{{$data->Id}}">
                                                    <input type="hidden" name="setor" id="setor"
                                                        value="{{$data->Setor}}">
                                                    <input type="hidden" name="unidade" id="unidade"
                                                        value="{{$data->Unidade}}">

                                                    <div class="input-field col s2">
                                                        <span style="font-size: 12px;" class="control-label">Número da
                                                            proposta:</span>
                                                        <input style="font-size: 12px;" name="proposta" id="proposta"
                                                            value="{{$data->NumeroProposta}}" readonly type="text"
                                                            class="form-control" data-toggle="tooltip"
                                                            data-placement="top" title="Número da proposta gerado."
                                                            required="required">
                                                    </div>

                                                    <div class="input-field col s2">
                                                        <span style="font-size: 12px;">Selecione o segmento:</span>
                                                        <select style="font-size: 12px;" class="browser-default"
                                                            required="required" readonly style="width: 100%;"
                                                            id="segmento" name="segmento" data-toggle="tooltip"
                                                            data-placement="top" title="Selecione o segmento abaixo.">
                                                            <option selected="selected"
                                                                value="{{$data->segmento_codigo}}">{{$data->Segmento}}
                                                            </option>
                                                        </select>
                                                    </div>

                                                    <div class="input-field col s2">
                                                        <span style="font-size: 12px;">Selecione o grupo:</span>
                                                        <div class="form-group">
                                                            <select style="font-size: 12px;" class="browser-default"
                                                                required="required" readonly id="grupo" name="grupo"
                                                                data-toggle="tooltip" data-placement="top"
                                                                title="Selecione o grupo cliente abaixo.">
                                                                <option selected="selected"
                                                                    value="{{$data->GrupoCliente}}">
                                                                    {{$data->GrupoCliente}}</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="input-field col s6">
                                                        <span style="font-size: 12px;">Selecione o cliente:</span>
                                                        <div class="form-group">
                                                            <div class="form-group">
                                                                <select style="font-size: 12px;" class="browser-default"
                                                                    readonly name="cliente" id="cliente"
                                                                    required="required">
                                                                    <option value="{{$data->Cliente}}">
                                                                        {{$data->Cliente}}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="row">

                                                    <div class="input-field col s3">
                                                        <span style="font-size: 12px;"
                                                            class="control-label">Solicitante:</span>
                                                        <div class="form-group">
                                                            <div class="form-group">
                                                                <select style="font-size: 12px;" name="solicitante"
                                                                    id="solicitante" readonly readonly
                                                                    class="browser-default" required>
                                                                    <option value="{{$data->SolicitanteCPF}}">
                                                                        {{$data->SolicitanteNome}}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

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
                                                            cadastro:</span>
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
                                                        <span style="font-size: 12px;"
                                                            class="control-label">Referral:</span>
                                                        <div class="form-group">
                                                            <div class="form-group">
                                                                <select style="font-size: 12px;" name="referral"
                                                                    id="referral" readonly required="required"
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

                                                    <div class="input-field col s2">
                                                        <div class="form-group">
                                                            <div class="form-group">
                                                                <span style="font-size: 12px;"
                                                                    class="control-label">Valor Global/Anual:</span>
                                                                <input style="font-size: 12px;" name="valor" id="valor"
                                                                    type="text" readonly value="{{$data->Valor}}"
                                                                    maxlength="8" pattern="(?:\.|,|[0-9])*"
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
                                                                    <span style="font-size: 12px;"
                                                                        class="control-label">Escopo:</span>
                                                                    <textarea style="font-size: 12px;" id="escopo"
                                                                        required="required" readonly rows="4"
                                                                        type="text" name="escopo" class="form-control"
                                                                        placeholder="Insira o escopo abaixo."
                                                                        style="text-align:left; overflow:auto;">
                         {{$data->Escopo}}</textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="row">
                                                    <div class="input-field col s12">
                                                        <span style="font-size: 12px;">Selecione abaixo o novo status
                                                            para esta proposta:</span>

                                                        <p>
                                                            <label>
                                                                <input class="with-gap" name="status" id="3" value="3"
                                                                    type="radio" checked onClick="somecampo();" />
                                                                <span style="font-size: 12px;">Aprovada</span>
                                                            </label>
                                                        </p>

                                                        <p>
                                                            <label>
                                                                <input class="with-gap" name="status" id="4" value="4"
                                                                    type="radio" onClick="somecampo();" />
                                                                <span style="font-size: 12px;">Reprovada</span>
                                                            </label>
                                                        </p>

                                                        <p>
                                                            <label>
                                                                <input class="with-gap" value="5" name="status" id="5"
                                                                    type="radio" onClick="somecampo();" />
                                                                <span style="font-size: 12px;">Cancelada</span>
                                                            </label>
                                                        </p>

                                                        <p>
                                                            <label>
                                                                <input class="with-gap" name="status" value="6" id="6"
                                                                    type="radio" onClick="aparececampo();" />
                                                                <span style="font-size: 12px;">Substituida</span>
                                                            </label>
                                                        </p>

                                                    </div>
                                                </div>

                                                <div class="row" id="substituidadiv" style="display: none">
                                                    <span style="font-size: 12px;" class="control-label">Relacionar
                                                        número da proposta:</span>
                                                    <div class="input-field col s6">
                                                        <input style="font-size: 12px;" name="novaproposta"
                                                            id="novaproposta" type="text"
                                                            placeholder="Informe o número da proposta..."
                                                            class="form-control" data-toggle="tooltip"
                                                            data-placement="top"
                                                            title="Indique a proposta que será substituida."
                                                            required="required">
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="input-field col s12">
                                                        <span style="font-size: 12px;"
                                                            class="control-label">Observação:</span>
                                                        <div class="form-group">
                                                            <div class="form-group">
                                                                <textarea style="font-size: 12px;" id="observacao"
                                                                    rows="4" type="text" name="observacao"
                                                                    class="form-control"
                                                                    placeholder="Insira a observação abaixo."
                                                                    style="text-align:left; overflow:auto;">
                        {{$data->Observacao}} </textarea>
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


        <script>
        function envia() {

            document.getElementById("loadingdiv").style.display = "";
            document.getElementById("corpodiv").style.display = "none";
            document.getElementById("form").submit();
        }
        </script>

        <script>
        function aparececampo() {

            document.getElementById("substituidadiv").style.display = "";
        }
        </script>

        <script>
        function somecampo() {

            document.getElementById("substituidadiv").style.display = "none";
        }
        </script>

@endsection