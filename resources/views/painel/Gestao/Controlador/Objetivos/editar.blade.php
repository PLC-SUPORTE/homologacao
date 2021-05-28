@extends('painel.Layout.header')
<?php $search = 2 ?>
@section('title') Editar objetivo @endsection
<!-- Titulo da pagina -->

@section('header')
<link rel="apple-touch-icon"
    href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/images/favicon/apple-touch-icon-152x152.png">
<link rel="shortcut icon" href="{{ asset('/public/assets/imgs/casinha.png')}}" />
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<link rel="stylesheet" href="{{ asset('/public/materialize/css/materialize.min.css') }}">
<link rel="stylesheet" href="{{ asset('/public/materialize/css/style.min.css') }}">
<link rel="stylesheet" href="{{ asset('/public/materialize/css/custom.css') }}">


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
Editar objetivo
@nedsection
@section('submenu')
<li class="breadcrumb-item"><a href="{{ route('Painel.Gestao.Controlador.index') }}">Dashboard</a>
</li>
<li class="breadcrumb-item"><a href="{{ route('Painel.Gestao.Controlador.Objetivos.index') }}">Objetivos</a>
</li>
<li class="breadcrumb-item active" style="color: black;">Editar objetivo
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
                            <h4 class="card-title">Editar objetivo</h4>
                            <form id="form" role="form"
                                action="{{ route('Painel.Gestao.Controlador.Objetivos.editado') }}" method="POST"
                                role="create">
                                {{ csrf_field() }}

                                <input type="hidden" name="id" id="id" value="{{$datas->id}}">

                                <div class="row">

                                    <div class="input-field col m4 s12">
                                        <span>Objetivo</span>
                                        <input value="{{$datas->objetivo}}" id="objetivo" name="objetivo"
                                            class="form-control" type="text" required>
                                    </div>


                                    <div class="input-field col m2 s12">
                                        <span>Ativo</span>
                                        <select class="form-control" style="width: 100%;  max-height: 200px;" id="ativo"
                                            name="ativo" data-toggle="tooltip" data-placement="top"
                                            title="Selecione se o objetivo está ativo ou não." required="required">
                                            @if($datas->ativo == "S")
                                            <option value="S" selected>SIM</option>
                                            <option value="N">NÃO</option>
                                            @else
                                            <option value="S">SIM</option>
                                            <option value="N" selected>NÃO</option>
                                            @endif
                                        </select>
                                    </div>


                                </div>

                                <div class="right-align">
                                    <a class="btn red" href="{{route('Painel.Gestao.Controlador.Objetivos.index')}}"><i
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

    <script>
    function envia() {

        document.getElementById("loadingdiv").style.display = "";
        document.getElementById("Form-advance").style.display = "none";
        document.getElementById("form").submit();
    }
    </script>
