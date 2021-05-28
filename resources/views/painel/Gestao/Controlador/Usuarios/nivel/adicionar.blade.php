@extends('painel.Layout.header')
<?php $search = 2 ?>
@section('title') Adicionar novo nível @endsection
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
Novo relacionamento
@endsection
@section('submenu')
<li class="breadcrumb-item"><a href="{{ route('Painel.Gestao.Controlador.index') }}">Dashboard</a>
</li>
<li class="breadcrumb-item"><a href="{{ route('Painel.Gestao.Controlador.Usuarios.Nivel.index') }}">Relação de
        usuários</a>
</li>
<li class="breadcrumb-item active" style="color: black;">Novo relacionamento
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
                            <h1 style="text-align: center;">Aguarde, estamos salvando o registro...&hellip;</h1>
                        </div>
                    </center>

                    <div id="Form-advance" class="card card card-default scrollspy">
                        <div class="card-content">
                            <form id="form" role="form"
                                action="{{ route('Painel.Gestao.Controlador.Usuarios.Nivel.relacionado') }}"
                                method="POST" role="create">
                                {{ csrf_field() }}

                                <div class="row">

                                    <div class="input-field col m4 s12">
                                        <span>Selecione o sócio:</span>
                                        <select class="select2 browser-default" style="width: 100%;  max-height: 200px;"
                                            id="socio" name="socio" data-toggle="tooltip" data-placement="top"
                                            title="Selecione o sócio abaixo." required="required">
                                            @foreach($usuarios as $usuario)
                                            <option value="{{$usuario->cpf}}">{{$usuario->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>


                                    <div class="input-field col m4 s12">
                                        <span>Selecione o nível:</span>
                                        <select class="select2 browser-default" style="width: 100%;  max-height: 200px;"
                                            id="nivel" name="nivel" data-toggle="tooltip" data-placement="top"
                                            title="Selecione o nível abaixo." required="required">
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
                                        <span>Data ínicio:</span>
                                        <input id="datainicio" name="datainicio" class="form-control" type="date"
                                            required>
                                    </div>

                                </div>

                                <div class="right-align">
                                    <a class="btn red"
                                        href="{{route('Painel.Gestao.Controlador.Usuarios.Nivel.index')}}"><i
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
    <script src="{{ asset('/public/materialize/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/form-select2.min.js') }}"></script>


    <script>
    function envia() {

        document.getElementById("loadingdiv").style.display = "";
        document.getElementById("Form-advance").style.display = "none";
        document.getElementById("form").submit();
    }
    </script>


@endsection