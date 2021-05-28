@extends('painel.Layout.header')
<?php $search = 2 ?>
@section('title') Editar meta @endsection
<!-- Titulo da pagina -->

@section('header')
<link rel="apple-touch-icon"
    href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/images/favicon/apple-touch-icon-152x152.png">
<link rel="shortcut icon" href="{{ asset('/public/assets/imgs/casinha.png')}}" />
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<link rel="stylesheet" href="{{ asset('/public/materialize/css/materialize.min.css') }}">
<link rel="stylesheet" href="{{ asset('/public/materialize/css/style.min.css') }}">
<link rel="stylesheet" href="{{ asset('/public/materialize/css/custom.css') }}">
<link rel="stylesheet" href="{{ asset('/public/materialize/css/app-invoice.min.css') }}">
<link rel="stylesheet" href="{{ asset('/public/materialize/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('/public/materialize/css/select2-materialize.css') }}">
<link rel="stylesheet" href="{{ asset('/public/materialize/css/form-select2.min.css') }}">


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
Editar meta
@endsection
@section('submenu')
<li class="breadcrumb-item"><a href="{{ route('Painel.Gestao.Controlador.index') }}">Dashboard</a>
</li>
<li class="breadcrumb-item"><a href="{{ route('Painel.Gestao.Controlador.Metas.index') }}">Metas</a>
</li>
<li class="breadcrumb-item" style="color: black;">Editar meta</a>
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
                            <h1 style="text-align: center;">Aguarde, estamos atualizando o registro...&hellip;</h1>
                        </div>
                    </center>

                    <div id="Form-advance" class="card card card-default scrollspy">
                        <div class="card-content">
                            <form id="form" role="form" action="{{ route('Painel.Gestao.Controlador.Metas.editado') }}"
                                method="POST" role="create">
                                {{ csrf_field() }}

                                <input type="hidden" name="id" id="id" value="{{$datas->id}}">

                                <div class="row">

                                    <div class="input-field col m2 s12">
                                        <span>Objetivo</span>
                                        <select class="select2 browser-default" id="objetivo" name="objetivo"
                                            data-toggle="tooltip" data-placement="top" title="Selecione o objetivo."
                                            required="required">
                                            <option value="{{$datas->objetivo_id}}">{{$datas->objetivo}}</option>
                                            @foreach($objetivos as $objetivo)
                                            <option value="{{$objetivo->id}}">{{$objetivo->objetivo}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="input-field col m2 s12">
                                        <span>UOM</span>
                                        <select class="select2 browser-default" style="width: 100%;  max-height: 200px;"
                                            id="uom" name="uom" data-toggle="tooltip" data-placement="top"
                                            title="Selecione o tipo de UOM." required="required">
                                            <option value="{{$datas->uom}}">{{$datas->uom}}</option>
                                            <option value="R$ M">R$ M</option>
                                            <option value="Hora">Hora</option>
                                            <option value="%">%</option>
                                            <option value="Pts">Pts</option>
                                        </select>
                                    </div>

                                    <div class="input-field col m2 s12">
                                        <span>Nível</span>
                                        <select class="select2 browser-default" style="width: 100%;  max-height: 200px;"
                                            id="nivel" name="nivel" data-toggle="tooltip" data-placement="top"
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

                                    <div class="input-field col m2 s12">
                                        <span>Peso</span>
                                        <input value="{{$datas->peso}}" id="peso" name="peso" class="form-control"
                                            type="text" required>
                                    </div>

                                    <div class="input-field col m2 s12">
                                        <span>Score90</span>
                                        <input value="{{$datas->score90}}" id="score90" name="score90"
                                            class="form-control" type="text" required>
                                    </div>

                                    <div class="input-field col m2 s12">
                                        <span>Meta</span>
                                        <input value="{{$datas->meta}}" id="meta" name="meta" class="form-control"
                                            type="text" required>
                                    </div>

                                    <div class="input-field col m2 s12">
                                        <span>Score120</span>
                                        <input value="{{$datas->score120}}" id="score120" name="score120"
                                            class="form-control" type="text" required>
                                    </div>

                                    <div class="input-field col m2 s12">
                                        <span>Data prazo</span>
                                        <input value="{{$datas->prazo}}" type="date" id="prazo" name="prazo"
                                            class="form-control" required>
                                    </div>
                                </div>

                                <div class="right-align">
                                    <a class="btn red" href="{{route('Painel.Gestao.Controlador.Metas.index')}}"><i
                                            class="material-icons left">close</i>Cancelar</a>
                                    <button type="submit" id="btnsubmit" class="btn green"><i
                                            class="material-icons left">save</i>Salvar</button>
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
    <script src="{{ asset('/public/materialize/js/app-invoice.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/form-select2.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/vendors.min.js') }}"></script>

