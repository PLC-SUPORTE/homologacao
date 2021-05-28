@extends('painel.Layout.header')
<?php $search = 2 ?>
@section('title') Advogados hierarquia @endsection
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
Advogados Hierarquia
@endsection
@section('submenu')
<li class="breadcrumb-item"><a href="{{ route('Painel.Gestao.Controlador.index') }}">Dashboard</a>
</li>
<li class="breadcrumb-item"><a href="{{ route('Painel.Gestao.Controlador.Hierarquia.index') }}">Responsáveis
        Hierarquia</a>
</li>
<li class="breadcrumb-item" style="color: black;">Advogados Hierarquia</a>
</li>
@endsection
@section('body')
    <div>
        <div class="row">
            <div class="content-wrapper-before blue-grey lighten-5"></div>
            <div class="col s12">
                <div class="container">

                    <section class="invoice-list-wrapper section">


                        <div class="invoice-filter-action mr-4">
                            <a href="#modal"
                                class="btn-floating btn-mini waves-effect waves-light tooltipped modal-trigger"
                                data-position="left"
                                data-tooltip="Clique aqui para adicionar um novo sócio a está hierarquia."
                                style="margin-left: 5px;background-color: gray;"><i class="material-icons">add</i></a>
                            <a href="{{ route('Painel.Gestao.Controlador.Hierarquia.exportaradvogados', $responsavel_cpf) }}"
                                class="btn-floating btn-mini waves-effect waves-light buttons-right tooltipped"
                                data-position="left"
                                data-tooltip="Clique aqui para exportar em Excel os sócios desta hierarquia."
                                style="background-color: gray;"><img
                                    style="margin-top: 8px; width: 20px;margin-left:8px;"
                                    src="{{URL::asset('/public/imgs/icon.png')}}" /></a>
                        </div>


                        <div class="responsive-table">
                            <table class="table invoice-data-table white border-radius-4 pt-1">
                                <thead>
                                    <tr>
                                        <th style="font-size: 12px"></th>
                                        <th style="font-size: 12px">#</th>
                                        <th style="font-size: 12px">Usuário</th>
                                        <th style="font-size: 12px">Responsável</th>
                                        <th style="font-size: 12px">Unidade</th>
                                        <th style="font-size: 12px">Setor</th>
                                        <th style="font-size: 12px">Ativo</th>
                                        <th style="font-size: 12px">Data Início</th>
                                        <th style="font-size: 12px">Data Fim</th>
                                        <th style="font-size: 12px"></th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($datas as $data)
                                    <tr>
                                        <td style="font-size: 11px"></td>
                                        <td style="font-size: 11px"></td>
                                        <td style="font-size: 11px">{{$data->advogado_nome}}</td>
                                        <td style="font-size: 11px">{{$responsavel_nome}}</td>
                                        <td style="font-size: 11px">{{$data->unidade}}</td>
                                        <td style="font-size: 11px">{{$data->setor}}</td>
                                        @if($data->ativo == "S")
                                        <td style="font-size: 11px"><span class="bullet green"></span> SIM</td>
                                        @else
                                        <td style="font-size: 11px"><span class="bullet red"></span> NÃO</td>
                                        @endif
                                        @if($data->datainicio != null)
                                        <td style="font-size: 11px">{{ date('d/m/Y', strtotime($data->datainicio)) }}
                                        </td>
                                        @else
                                        <td style="font-size: 11px"></td>
                                        @endif
                                        @if($data->datafim != null)
                                        <td style="font-size: 11px">{{ date('d/m/Y', strtotime($data->datafim)) }}</td>
                                        @else
                                        <td style="font-size: 11px"></td>
                                        @endif
                                        <td style="font-size: 11px">

                                            <div class="invoice-action">
                                                <a href="{{route('Painel.Gestao.Controlador.Hierarquia.editar', $data->id)}}"
                                                    class="invoice-action-view mr-4"><i
                                                        class="material-icons">edit</i></a>
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
    <div id="modal" class="modal" style="width: 1200px;height: 300px;">
        <form id="form" role="form" action="{{ route('Painel.Gestao.Controlador.Hierarquia.gravar') }}" method="POST"
            role="create">
            {{ csrf_field() }}

            <input type="hidden" name="responsavel_cpf" id="responsavel_cpf" value="{{$responsavel_cpf}}">


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
                        style="margin-top: -20px; margin-right: -25px;"><i
                            class="material-icons left">close</i>Fechar</a>
                    <h5>Nova hierarquia</h5>

                    <br>

                    <div class="row">


                        <div class="input-field col s3" style="margin-top: 12px;">
                            <select class="select2 browser-default" id="advogado" name="usuario">
                                @foreach($usuarios as $usuario)
                                <option value="{{$usuario->id}}">{{$usuario->name}}</option>
                                @endforeach
                            </select>
                            <label>Advogado</label>
                        </div>

                        <div class="input-field col s3" style="margin-top: 12px;">
                            <select class="select2 browser-default" id="responsavel" name="responsavel" required
                                readonly>
                                <option value="{{$responsavel_id}}" selected>{{$responsavel_nome}}</option>
                            </select>
                            <label>Responsável</label>
                        </div>

                        <div class="input-field col s2" style="margin-top: 12px;">
                            <select class="select2 browser-default" id="ativo" name="ativo">
                                <option value="S" selected>SIM</option>
                                <option value="N">NÃO</option>
                            </select>
                            <label>Ativo</label>
                        </div>

                        <div class="input-field col s2">
                            <input id="icon_telephone" type="date" value="{{$datahoje}}" name="data" class="validate"
                                required>
                            <label for="icon_telephone">Data Início</label>
                        </div>


                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <a type="button" id="btnsubmit" onClick="envia();"
                    class="modal-action waves-effect waves-green btn-flat"
                    style="background-color: green;color:white;"><i class="material-icons left">save_alt</i>Salvar</a>
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
    <script src="{{ asset('/public/materialize/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/form-select2.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/vendors.min.js') }}"></script>


    <script>
    $(document).ready(function() {
        $('.modal').modal();
        $("#manualmentediv").hide();
        $("#importacaodiv").hide();
    });
    </script>


    <script>
    function manualmente() {

        $("#manualmentediv").show();
        $("#importacaodiv").hide();
        $("#opcao").val("manualmente");


    }
    </script>

    <script>
    function importacao() {

        $("#manualmentediv").hide();
        $("#importacaodiv").show();
        $("#opcao").val("importacao");
    }
    </script>

    <script>
    function envia() {

        document.getElementById("loadingdiv").style.display = "";
        document.getElementById("corpodiv").style.display = "none";
        document.getElementById("form").submit();
    }
    </script>
@endsection