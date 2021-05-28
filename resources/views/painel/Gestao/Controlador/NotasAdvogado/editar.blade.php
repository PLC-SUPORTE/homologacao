@extends('painel.Layout.header')
<?php $search = 2 ?>
@section('title') Editar nota sócio @endsection
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
Editar lançamento de nota
@endsection
@section('submenu')
<li class="breadcrumb-item"><a href="{{ route('Painel.Gestao.Controlador.index') }}">Dashboard</a>
</li>
<li class="breadcrumb-item active" style="color: black;">Editar lançamento de nota
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
                                action="{{ route('Painel.Gestao.Controlador.NotasAdvogado.editado') }}" method="POST"
                                role="create">
                                {{ csrf_field() }}

                                <input type="hidden" name="id" id="id" value="{{$datas->id}}">
                                <input type="hidden" name="usuario_id" id="usuario_id" value="{{$datas->usuario_id}}">

                                <div class="row">

                                    <div class="input-field col m4 s12">
                                        <span>Usuários</span>
                                        <div class="form-group bmd-form-group is-filled">
                                            <input value="{{$datas->usuario_nome}}" id="usuario" name="usuario" readonly
                                                class="form-control" required>
                                        </div>
                                    </div>


                                    <div class="input-field col m4 s12">
                                        <span>Unidade</span>
                                        <div class="form-group bmd-form-group is-filled">
                                            <input value="{{$datas->unidade}}" id="unidade" name="unidade" readonly
                                                class="form-control">
                                        </div>
                                    </div>

                                    <div class="input-field col m4 s12">
                                        <span>Setor</span>
                                        <div class="form-group bmd-form-group is-filled">
                                            <input value="{{$datas->setor}}" id="setor" name="setor" readonly
                                                class="form-control">
                                        </div>
                                    </div>

                                    <div class="input-field col m4 s12">
                                        <span>Mês referência</span>
                                        <select class="form-control" style="width: 100%;  max-height: 200px;" id="mes"
                                            name="mes" data-toggle="tooltip" data-placement="top"
                                            title="Selecione o mês de referência." required="required">
                                            <option value="{{$datas->mes_referencia}}" selected>
                                                {{$datas->mes_referencia}}</option>
                                            <option value="01">Janeiro</option>
                                            <option value="02">Fevereiro</option>
                                            <option value="03">Março</option>
                                            <option value="04">Abril</option>
                                            <option value="05">Maio</option>
                                            <option value="06">Junho</option>
                                            <option value="07">Julho</option>
                                            <option value="08">Agosto</option>
                                            <option value="09">Setembro</option>
                                            <option value="10">Outubro</option>
                                            <option value="11">Novembro</option>
                                            <option value="12">Dezembro</option>
                                        </select>
                                    </div>

                                    <div class="input-field col m4 s12">
                                        <span>Ano referência</span>
                                        <select class="form-control" style="width: 100%;  max-height: 200px;" id="ano"
                                            name="ano" data-toggle="tooltip" data-placement="top"
                                            title="Selecione o ano de referência." required="required">
                                            <option value="{{$datas->ano_referencia}}" selected>
                                                {{$datas->ano_referencia}}</option>
                                            <option value="2014">2014</option>
                                            <option value="2015">2015</option>
                                            <option value="2016">2016</option>
                                            <option value="2017">2017</option>
                                            <option value="2018">2018</option>
                                            <option value="2019">2019</option>
                                            <option value="2020">2020</option>
                                        </select>
                                    </div>

                                    <div class="input-field col m4 s12">
                                        <span>Nível</span>
                                        <select class="form-control" style="width: 100%;  max-height: 200px;" id="nivel"
                                            name="nivel" data-toggle="tooltip" data-placement="top"
                                            title="Selecione o nível." required="required">
                                            <option value="{{$datas->nivel}}" selected>{{$datas->nivel}}</option>
                                            <option value="Advogado">Advogado</option>
                                            <option value="Advogado Controladoria">Advogado Controladoria</option>
                                            <option value="Advogado ControladoriaSP">Advogado ControladoriaSP</option>
                                            <option value="COO">COO</option>
                                            <option value="Coordenador">Coordenador</option>
                                            <option value="Coordenador Controladoria">Coordenador Controladoria</option>
                                            <option value="Coordenador ControladoriaSP">Coordenador ControladoriaSP
                                            </option>
                                            <option value="Gerente">Gerente</option>
                                            <option value="Gerente Equipe Passiva">Gerente Equipe Passiva</option>
                                            <option value="Superintendente">Superintendente</option>
                                            <option value="Subcoordenador 1">Subcoordenador 1</option>
                                            <option value="Subcoordenador 2">Subcoordenador 2</option>
                                        </select>
                                    </div>

                                    <div class="input-field col m4 s12">
                                        <span>Objetivo</span>
                                        <select class="form-control" style="width: 100%;  max-height: 200px;"
                                            id="objetivo" name="objetivo" data-toggle="tooltip" data-placement="top"
                                            title="Selecione o objetivo." required="required">
                                            <option value="{{$datas->objetivo_id}}" selected>{{$datas->objetivo}}
                                            </option>
                                            @foreach($objetivos as $objetivo)
                                            <option value="{{$objetivo->id}}">{{$objetivo->objetivo}}</option>
                                            @endforeach
                                        </select>
                                    </div>


                                    <div class="input-field col m4 s12">
                                        <span>Nota</span>
                                        <input value="{{$datas->nota}}" id="nota" name="nota" class="form-control"
                                            type="text" required>
                                    </div>

                                </div>


                                <div class="right-align">
                                    <a class="btn red"
                                        href="{{route('Painel.Gestao.Controlador.NotasAdvogado.index')}}"><i
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
