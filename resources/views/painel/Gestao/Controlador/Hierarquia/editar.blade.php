@extends('painel.Layout.header')
<?php $search = 2 ?>
@section('title') Editar hierarquia - Pesquisa Patrimonial @endsection
<!-- Titulo da pagina -->

@section('header')
<link rel="apple-touch-icon"
    href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/images/favicon/apple-touch-icon-152x152.png">
<link rel="shortcut icon" href="{{ asset('/public/assets/imgs/casinha.png')}}" />
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<link rel="stylesheet" href="{{ asset('/public/materialize/css/materialize.min.css') }}">
<link rel="stylesheet" href="{{ asset('/public/materialize/css/style.min.css') }}">
<link rel="stylesheet" href="{{ asset('/public/materialize/css/custom.css') }}">

<link rel="stylesheet" type="text/css"
    href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/css/pages/app-file-manager.min.css">
<link rel="stylesheet" type="text/css"
    href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/css/pages/widget-timeline.min.css">
<link rel="stylesheet"
    href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/css/pages/app-invoice.min.css">


<style>
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
Editar lançamento de hierarquia
@endsection
@section('submenu')
<li class="breadcrumb-item"><a href="{{ route('Painel.Gestao.Controlador.index') }}">Dashboard</a>
</li>
<li class="breadcrumb-item"><a href="{{ route('Painel.Gestao.Controlador.Hierarquia.index') }}">Responsáveis
        Hierarquia</a>
</li>
<li class="breadcrumb-item" style="color: black;">Editar lançamento de hierarquia</a>
</li>
@endsection
@section('body')
    <div>
        <div class="row">
            <div class="container">

                <div class="col s12 m12 l12">

                    <center>
                        <div id="loadingdiv" style="display:none">
                            <div class="wrapper">
                                <div class="circle circle-1"></div>
                                <div class="circle circle-1a"></div>
                                <div class="circle circle-2"></div>
                                <div class="circle circle-3"></div>
                            </div>
                            <h1 style="text-align: center;">Editando registro...&hellip;</h1>
                        </div>
                    </center>

                    <div id="Form-advance" class="card card card-default scrollspy">
                        <div class="card-content">
                            <form id="form" role="form"
                                action="{{ route('Painel.Gestao.Controlador.Hierarquia.editado') }}" method="POST"
                                role="create">
                                {{ csrf_field() }}

                                <input type="hidden" name="id" id="id" value="{{$datas->id}}">
                                <input type="hidden" name="usuario_cpf" id="usuario_cpf"
                                    value="{{$datas->usuario_cpf}}">
                                <input type="hidden" name="responsavel_id" id="responsavel_id"
                                    value="{{$datas->responsavel_id}}">
                                <input type="hidden" name="responsavel_cpf" id="responsavel_cpf"
                                    value="{{$datas->responsavel_cpf}}">


                                <div class="row">

                                    <div class="input-field col m3 s12">
                                        <span>Usuários</span>
                                        <div class="form-group bmd-form-group is-filled">
                                            <input value="{{$datas->usuario_nome}}" id="usuario" name="usuario" readonly
                                                class="form-control" required>
                                        </div>
                                    </div>


                                    <div class="input-field col m3 s12">
                                        <span>Responsável</span>
                                        <div class="form-group bmd-form-group is-filled">
                                            <input value="{{$datas->responsavel_nome}}" id="responsavel"
                                                name="responsavel" readonly class="form-control">
                                        </div>
                                    </div>

                                    <div class="input-field col m3 s12">
                                        <span>Unidade</span>
                                        <div class="form-group bmd-form-group is-filled">
                                            <input value="{{$datas->unidade}}" id="unidade" name="unidade" readonly
                                                class="form-control">
                                        </div>
                                    </div>

                                    <div class="input-field col m3 s12">
                                        <span>Setor</span>
                                        <div class="form-group bmd-form-group is-filled">
                                            <input value="{{$datas->setor}}" id="setor" name="setor" readonly
                                                class="form-control">
                                        </div>
                                    </div>


                                    <div class="input-field col m3 s12">
                                        <span>Ativo</span>
                                        <select class="form-control" style="width: 100%;  max-height: 200px;" id="ativo"
                                            name="ativo" data-toggle="tooltip" data-placement="top"
                                            title="Selecione o status ativo(SIM/NÃO)." required="required">
                                            @if($datas->ativo == "S")
                                            <option value="S" selected>SIM</option>
                                            <option value="N">NÃO</option>
                                            @else
                                            <option value="S">SIM</option>
                                            <option value="N" selcted>NÃO</option>
                                            @endif
                                        </select>
                                    </div>

                                    <div class="input-field col m3 s12">
                                        <span>Data ínicio</span>
                                        @if($datas->datainicio != null)
                                        <input value="{{ date('d/m/Y', strtotime($datas->datainicio)) }}"
                                            id="datainicio" name="datainicio" readonly class="form-control" type="text"
                                            required>
                                        @else
                                        <input id="datainicio" name="datainicio" class="form-control" type="date"
                                            required>
                                        @endif
                                    </div>

                                    <div class="input-field col m3 s12">
                                        <span>Data fím</span>
                                        <input min="{{$datahoje}}" id="datafim" name="datafim" class="form-control"
                                            type="date">
                                    </div>


                                </div>

                                <div class="right-align">
                                    <a class="btn red" href="{{route('Painel.Gestao.Controlador.Hierarquia.index')}}"><i
                                            class="material-icons left">close</i>Cancelar</a>
                                    <button type="button" id="btnsubmit" onClick="envia();" class="btn green"><i
                                            class="material-icons left">save_alt</i>Salvar</button>
                                </div>

                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/vendors.min.js') }}"></script>

    <script
        src="https://pixinvent.com/materialize-material-design-admin-template/app-assets/js/scripts/app-file-manager.min.js">
    </script>


    <script>
    function envia() {

        document.getElementById("loadingdiv").style.display = "";
        document.getElementById("Form-advance").style.display = "none";
        document.getElementById("form").submit();
    }
    </script>
@endsection