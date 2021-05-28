@extends('painel.Layout.header')
<?php $search = 2 ?>
@section('title') Hierarquia @endsection
<!-- Titulo da pagina -->

@section('header')
<link rel="apple-touch-icon"
    href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/images/favicon/apple-touch-icon-152x152.png">
<link rel="shortcut icon" href="{{ asset('/public/assets/imgs/casinha.png')}}" />
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('/public/materialize/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('/public/materialize/css/responsive.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('/public/materialize/css/materialize.min.css') }}">
<link rel="stylesheet" href="{{ asset('/public/materialize/css/style.min.css') }}">
<link rel="stylesheet" href="{{ asset('/public/materialize/css/data-tables.min.css') }}">
<link rel="stylesheet" href="{{ asset('/public/materialize/css/custom.css') }}">
<link rel="stylesheet" href="{{ asset('/public/materialize/css/app-invoice.min.css') }}">



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
Responsáveis hierarquia
@endsection
@section('submenu')
<li class="breadcrumb-item"><a href="{{ route('Painel.Gestao.Controlador.index') }}">Dashboard</a>
</li>
<li class="breadcrumb-item active" style="color: black;">Responsáveis hierarquia
</li>
@endsection
@section('body')
    <div>
        <div class="row">


            <div class="row">
                <div class="content-wrapper-before blue-grey lighten-5"></div>
                <div class="col s12">
                    <div class="container">

                        <section class="invoice-list-wrapper section">


                            <div class="invoice-filter-action mr-4">
                                <a href="#modal"
                                    class="btn-floating btn-mini waves-effect waves-light tooltipped modal-trigger"
                                    data-position="left" data-tooltip="Clique aqui para adicionar um novo responsável."
                                    style="margin-left: 5px;background-color: gray;"><i
                                        class="material-icons">add</i></a>
                                <a href="{{ route('Painel.Gestao.Controlador.Hierarquia.exportarresponsaveis') }}"
                                    class="btn-floating btn-mini waves-effect waves-light buttons-right tooltipped"
                                    data-position="left"
                                    data-tooltip="Clique aqui para exportar em Excel os responsáveis."
                                    style="background-color: gray;"><img
                                        style="margin-top: 8px; width: 20px;margin-left:8px;"
                                        src="{{URL::asset('/public/imgs/icon.png')}}" /></a>
                            </div>


                            <div class="responsive-table">
                                <table class="table invoice-data-table white border-radius-4 pt-1">
                                    <thead>
                                        <tr>
                                            <th style="font-size: 12px"></th>
                                            <th style="font-size: 12px"></th>
                                            <th style="font-size: 12px"></th>
                                            <th style="font-size: 12px"></th>
                                            <th style="font-size: 12px">Id</th>
                                            <th style="font-size: 12px">Código</th>
                                            <th style="font-size: 12px">Responsável</th>
                                            <th style="font-size: 12px">E-mail</th>
                                            <th style="font-size: 12px"></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($datas as $data)
                                        <tr>
                                            <td style="font-size: 11px"></td>
                                            <td style="font-size: 11px"></td>
                                            <td style="font-size: 11px"></td>
                                            <td style="font-size: 11px"></td>
                                            <td style="font-size: 11px">{{$data->id}}</td>
                                            <td style="font-size: 11px">{{$data->cpf}}</td>
                                            <td style="font-size: 11px">{{$data->Responsavel}}</td>
                                            <td style="font-size: 11px">{{$data->email}}</td>
                                            <td style="font-size: 11px">

                                                <div class="invoice-action">
                                                    <a href="{{route('Painel.Gestao.Controlador.Hierarquia.advogados', $data->cpf)}}"
                                                        class="invoice-action-view mr-4"><i
                                                            class="material-icons">person</i></a>
                                                </div>

                                            </td>

                                        </tr>
                                        @endforeach


                                    </tbody>
                                </table>
                            </div>
                        </section>

                    </div>
                    <div class="content-overlay"></div>
                </div>
            </div>
        </div>


        <!--Modal perguntando -->
        <div id="modal" class="modal " style="width: 1200px;">
            <form id="form" role="form" action="{{ route('Painel.Gestao.Controlador.Hierarquia.novoresponsavel') }}"
                method="POST" role="create">
                {{ csrf_field() }}

                <div class="modal-content">

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
                        <a class="waves-effect modal-close waves-light btn red right align"
                            style="margin-top: -32px; margin-right: -20px;"><i
                                style="margin-left: 15px; font-size: 20px;" class="material-icons left">close</i></a>
                        <h5>Novo responsável</h5>

                        <br>

                        <div class="row">


                            <div class="input-field col s4" style="margin-top: 12px;">
                                <span>Selecione o novo responsável:</span>
                                <select class="select2 browser-default" id="responsavel" name="responsavel">
                                    @foreach($responsaveis as $responsavel)
                                    <option value="{{$responsavel->id}}">{{$responsavel->name}}</option>
                                    @endforeach

                                    @foreach($usuarios as $usuario)
                                    <option value="{{$usuario->id}}">{{$usuario->name}}</option>
                                    @endforeach
                                    
                                </select>
                            </div>

                            <div class="input-field col s4">
                                <span>Selecione o advogado:</span>
                                <select class="select2 browser-default" id="advogado" name="advogado">
                                    @foreach($usuarios as $usuario)
                                    <option value="{{$usuario->id}}">{{$usuario->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="input-field col s2">
                                <span>Selecione o status:</span>
                                <select class="select2 browser-default" id="ativo" name="ativo">
                                    <option value="S" selected>SIM</option>
                                    <option value="N">NÃO</option>
                                </select>
                            </div>

                            <div class="input-field col s2">
                                <span>Informe a data ínicio:</span>
                                <input id="icon_telephone" type="date" value="{{$datahoje}}" name="data"
                                    class="validate" required>
                            </div>


                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <a type="button" id="btnsubmit" onClick="envia();"
                        class="modal-action waves-effect waves-green btn-flat"
                        style="background-color: green;color:white;"><i
                            class="material-icons left">save_alt</i>Salvar</a>
                </div>
        </div>
        </form>
    </div>
    <!--Fim Modal -->
@endsection
@section('scripts')
    <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/app-invoice.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/vendors.min.js') }}"></script>


    <script>
    $(document).ready(function() {
        $('.modal').modal();
        $("#manualmentediv").hide();
        $("#importacaodiv").hide();
    });
    </script>

    <script>
    function envia() {

        document.getElementById("loadingdiv").style.display = "";
        document.getElementById("corpodiv").style.display = "none";
        document.getElementById("form").submit();
    }
    </script>
@endsection