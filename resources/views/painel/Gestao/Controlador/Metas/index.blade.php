@extends('painel.Layout.header')
<?php $search = 2 ?>
@section('title') Metas @endsection
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
Metas
@endsection
@section('submenu')
<li class="breadcrumb-item"><a href="{{ route('Painel.Gestao.Controlador.index') }}">Dashboard</a>
</li>
<li class="breadcrumb-item active" style="color: black;">Metas
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
                                    data-position="left" data-tooltip="Clique aqui para adicionar uma nova meta."
                                    style="margin-left: 5px;background-color: gray;"><i
                                        class="material-icons">add</i></a>
                                <a href="{{ route('Painel.Gestao.Controlador.Metas.exportar') }}"
                                    class="btn-floating btn-mini waves-effect waves-light buttons-right tooltipped"
                                    data-position="left" data-tooltip="Clique aqui para exportar em Excel as metas."
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
                                            <th style="font-size: 12px">Objetivo</th>
                                            <th style="font-size: 12px">Score 90</th>
                                            <th style="font-size: 12px">Meta</th>
                                            <th style="font-size: 12px">Score 120</th>
                                            <th style="font-size: 12px">Peso</th>
                                            <th style="font-size: 12px">Uom</th>
                                            <th style="font-size: 12px">Nível</th>
                                            <th style="font-size: 12px">Data prazo</th>
                                            <th style="font-size: 12px"></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($datas as $data)
                                        <tr>
                                            <td style="font-size: 11px"></td>
                                            <td style="font-size: 11px"></td>
                                            <td style="font-size: 11px">{{$data->objetivo}}</td>
                                            <td style="font-size: 11px">{{$data->score90}}</td>
                                            <td style="font-size: 11px">{{$data->meta}}</td>
                                            <td style="font-size: 11px">{{$data->score120}}</td>
                                            <td style="font-size: 11px">{{$data->peso}}</td>
                                            <td style="font-size: 11px">{{$data->uom}}</td>
                                            <td style="font-size: 11px">{{$data->nivel}}</td>
                                            <td style="font-size: 11px">{{ date('d/m/Y', strtotime($data->prazo)) }}
                                            </td>

                                            <td style="font-size: 11px">

                                                <div class="invoice-action">
                                                    <a href="{{route('Painel.Gestao.Controlador.Metas.editar', $data->id)}}"
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
        <div id="modal" class="modal" style="width: 1200px;">
            <form id="form" role="form" action="{{ route('Painel.Gestao.Controlador.Metas.gravarregistro') }}"
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
                    <a class="waves-effect modal-close waves-light btn red right align"
                        style="margin-top: -22px; margin-right: -20px;"><i style="margin-left: 15px; font-size: 20px;"
                            class="material-icons left">close</i></a>
                    <div id="corpodiv">

                        <h5>Nova meta</h5>

                        <br>

                        <div class="row">


                            <div class="input-field col s3">
                                <select class="select2 browser-default" name="objetivo" required>
                                    @foreach($objetivos as $objetivo)
                                    <option value="{{$objetivo->id}}">{{$objetivo->objetivo}}</option>
                                    @endforeach
                                </select>
                                <label>Selecione o objetivo</label>
                            </div>


                            <div class="input-field col s2">
                                <select class="select2 browser-default" name="uom" required>
                                    <option value="R$ M">R$ M</option>
                                    <option value="Hora">Hora</option>
                                    <option value="%">%</option>
                                    <option value="Pts">Pts</option>
                                </select>
                                <label>Selecione o UOM</label>
                            </div>

                            <div class="input-field col s2">
                                <select class="select2 browser-default" name="nivel" required>
                                    <option value="Advogado">Advogado</option>
                                    <option value="Advogado Controladoria">Advogado Controladoria</option>
                                    <option value="Advogado ControladoriaSP">Advogado ControladoriaSP</option>
                                    <option value="COO">COO</option>
                                    <option value="Coordenador">Coordenador</option>
                                    <option value="Coordenador Controladoria">Coordenador Controladoria</option>
                                    <option value="Coordenador ControladoriaSP">Coordenador ControladoriaSP</option>
                                    <option value="Gerente">Gerente</option>
                                    <option value="Gerente Equipe Passiva">Gerente Equipe Passiva</option>
                                    <option value="Superintendente">Superintendente</option>
                                    <option value="Subcoordenador 1">Subcoordenador 1</option>
                                    <option value="Subcoordenador 2">Subcoordenador 2</option>
                                </select>
                                <label>Selecione o nível</label>
                            </div>


                            <div class="input-field col s2">
                                <input type="text" name="peso" class="validate" pattern="(?:\.|,|[0-9])*"
                                    placeholder="Informe o peso..">
                                <label for="icon_telephone">Informe o peso</label>
                            </div>

                            <div class="input-field col s2">
                                <input type="text" name="score90" value="90" class="validate" pattern="(?:\.|,|[0-9])*"
                                    placeholder="Informe o score90..">
                                <label for="icon_telephone">Informe o score90</label>
                            </div>

                            <div class="input-field col s2">
                                <input type="text" name="score120" value="120" class="validate"
                                    pattern="(?:\.|,|[0-9])*" placeholder="Informe o score120..">
                                <label for="icon_telephone">Informe o score120</label>
                            </div>

                            <div class="input-field col s2">
                                <input type="text" name="meta" class="validate" value="100" pattern="(?:\.|,|[0-9])*"
                                    placeholder="Informe a meta..">
                                <label for="icon_telephone">Informe a meta</label>
                            </div>

                            <div class="input-field col s2">
                                <input type="date" name="prazo" min="{{$datahoje}}" value="{{$datahoje}}">
                                <label for="icon_telephone">Selecione a data prazo</label>
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

