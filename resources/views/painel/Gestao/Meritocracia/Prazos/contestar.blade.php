@extends('painel.Layout.header')
<?php $search = 2 ?>
@section('title') Contestação prazo @endsection
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
Contestar prazo
@endsection
@section('submenu')
<li class="breadcrumb-item"><a href="{{route('Painel.Gestao.Meritocracia.index')}}">Dashboard</a>
</li>
<li class="breadcrumb-item"><a href="{{route('Painel.Gestao.Meritocracia.Prazos.index')}}">Contestação de prazo</a>
</li>
<li class="breadcrumb-item active" style="color: black;">Contestar prazo
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
                            <h1 style="text-align: center;">Enviando contestação...&hellip;</h1>
                        </div>
                    </center>

                    <div id="Form-advance" class="card card card-default scrollspy">
                        <div class="card-content">
                            <h4 class="card-title">Contestar prazo</h4>
                            <form id="form" role="form"
                                action="{{ route('Painel.Gestao.Meritocracia.Prazos.contestado') }}" method="POST"
                                role="create">
                                {{ csrf_field() }}

                                <div class="row">

                                    <div class="input-field col m2 s12">
                                        <span>Ident</span>
                                        <div class="form-group bmd-form-group is-filled">
                                            <input value="{{$datas->Ident}}" id="ident" name="ident" readonly
                                                class="form-control" required>
                                        </div>
                                    </div>


                                    <div class="input-field col m2 s12">
                                        <span>Código pasta</span>
                                        <div class="form-group bmd-form-group is-filled">
                                            <input value="{{$datas->Pasta}}" id="pasta" name="pasta" readonly
                                                class="form-control">
                                        </div>
                                    </div>

                                    <div class="input-field col m2 s12">
                                        <span>Código MOV</span>
                                        <div class="form-group bmd-form-group is-filled">
                                            <input value="{{$datas->Mov}}" id="mov" name="mov" readonly
                                                class="form-control">
                                        </div>
                                    </div>

                                    <div class="input-field col m2 s12">
                                        <span>Unidade</span>
                                        <div class="form-group bmd-form-group is-filled">
                                            <input value="{{$datas->UnidadeCodigo}} - {{$datas->Unidade}}" id="unidade"
                                                name="unidade" readonly class="form-control">
                                        </div>
                                    </div>

                                    <div class="input-field col m2 s12">
                                        <span>Data criação</span>
                                        <div class="form-group bmd-form-group is-filled">
                                            <input value="{{ date('d/m/Y H:i:s', strtotime($datas->Data)) }}" id="data"
                                                name="data" readonly class="form-control">
                                        </div>
                                    </div>

                                    <div class="input-field col m2 s12">
                                        <span>Data prazo</span>
                                        <div class="form-group bmd-form-group is-filled">
                                            <input value="{{ date('d/m/Y H:i:s', strtotime($datas->DataPrazo)) }}"
                                                id="dataprazo" name="dataprazo" readonly class="form-control">
                                        </div>
                                    </div>

                                    <div class="input-field col m2 s12">
                                        <span>Data encerramento</span>
                                        <div class="form-group bmd-form-group is-filled">
                                            <input value="{{ date('d/m/Y H:i:s', strtotime($datas->DataFechamento)) }}"
                                                id="datafechamento" name="datafechamento" readonly class="form-control">
                                        </div>
                                    </div>

                                </div>

                                <div class="row">

                                    <div class="input-field col m12 s12">
                                        <span>Justificativa</span>
                                        <div class="form-group bmd-form-group is-filled">
                                            <textarea id="justificativa" name="justificativa" required
                                                class="materialize-textarea"></textarea>
                                        </div>
                                    </div>

                                </div>


                                <div class="right-align">
                                    <a class="btn red"
                                        href="{{route('Painel.Gestao.Controlador.NotasConsolidada.index')}}"><i
                                            class="material-icons left">close</i>Cancelar</a>
                                    <button type="button" id="btnsubmit" onClick="envia();" class="btn green"><i
                                            class="material-icons left">send</i>Enviar</button>
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