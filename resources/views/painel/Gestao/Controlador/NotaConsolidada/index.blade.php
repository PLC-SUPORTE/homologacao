@extends('painel.Layout.header')
<?php $search = 2 ?>
@section('title') Notas Consolidadas @endsection
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
Nota consolidada
@endsection
@section('submenu')
<li class="breadcrumb-item"><a href="{{ route('Painel.Gestao.Controlador.index') }}">Dashboard</a>
</li>
<li class="breadcrumb-item active" style="color: black;">Nota consolidada
</li>
@endsection
@section('body')
    <div>
        <div class="row">


            <center>
                <div id="loading">
                    <div class="wrapper">
                        <div class="circle circle-1"></div>
                        <div class="circle circle-1a"></div>
                        <div class="circle circle-2"></div>
                        <div class="circle circle-3"></div>
                    </div>
                    <h1 style="text-align: center;">Aguarde, estamos carregando as notas consolidada do mês de
                        apuração...&hellip;</h1>
                </div>
            </center>


            <div class="row" id="corpodiv">

                <div class="content-wrapper-before blue-grey lighten-5"></div>
                <div class="col s12">
                    <div class="container">

                        <section class="invoice-list-wrapper section">


                            <div class="invoice-filter-action mr-4">

                                <a href="{{ route('Painel.Gestao.Controlador.NotasConsolidada.historico') }}"
                                    class="btn-floating btn-mini waves-effect waves-light tooltipped"
                                    data-position="left"
                                    data-tooltip="Clique aqui para visualizar as notas consolidada referente ao ano de apuração."
                                    style="margin-left: 5px;background-color: gray;"><i
                                        class="material-icons">assignment</i></a>
                                <a href="{{ route('Painel.Gestao.Controlador.NotasConsolidada.exportarnotasmes') }}"
                                    class="btn-floating btn-mini waves-effect waves-light buttons-right tooltipped"
                                    data-position="left"
                                    data-tooltip="Clique aqui para exportar em Excel as notas consolidada do mês de apuração."
                                    style="background-color: gray;"><img
                                        style="margin-top: 8px; width: 20px;margin-left:8px;"
                                        src="{{URL::asset('/public/imgs/icon.png')}}" /></a>

                            </div>

                            <div class="responsive-table">
                                <table class="table invoice-data-table white border-radius-4 pt-1" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th style="font-size: 12px"></th>
                                            <th style="font-size: 12px">#</th>
                                            <th style="font-size: 12px">Usuário</th>
                                            <th style="font-size: 12px">Unidade</th>
                                            <th style="font-size: 12px">Setor</th>
                                            <th style="font-size: 12px">Mês</th>
                                            <th style="font-size: 12px">Nível</th>
                                            <th style="font-size: 12px">Nota consolidada mês</th>
                                            <th style="font-size: 12px">Nota consolidada acumulada</th>
                                            <th style="font-size: 12px"></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($datas as $data)
                                        <tr>
                                            <td style="font-size: 11px"></td>
                                            <td style="font-size: 11px"></td>
                                            <td style="font-size: 11px">
                                                {{mb_convert_case($data->usuario_nome, MB_CASE_TITLE, "UTF-8")}}</td>
                                            <td style="font-size: 11px">{{$data->unidade}}</td>
                                            <td style="font-size: 11px">{{$data->setor}}</td>
                                            <td style="font-size: 11px">{{$data->mes}}</td>
                                            <td style="font-size: 11px">{{$data->nivel}}</td>
                                            <td style="font-size: 11px">{{$data->notaconsolidada}}</td>
                                            <td style="font-size: 11px">{{$data->notaacumulada}}</td>

                                            <td style="font-size: 11px">

                                                <div class="invoice-action">
                                                    <a href="{{route('Painel.Gestao.Controlador.NotasConsolidada.detalhar', $data->usuario_cpf)}}"
                                                        class="invoice-action-view mr-4"><i
                                                            class="material-icons">remove_red_eye</i></a>
                                                    <a href="{{route('Painel.Gestao.Controlador.NotasConsolidada.editar', $data->id)}}"
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

@endsection
@section('scripts')

        <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
        <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
        <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>
        <script src="{{ asset('/public/materialize/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('/public/materialize/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('/public/materialize/js/app-invoice.min.js') }}"></script>

        <script>
        document.addEventListener("DOMContentLoaded", function() {
            $("#corpodiv").hide();
        });
        </script>


        <script>
        setTimeout(function() {
            $('#loading').fadeOut('fast');
            $("#corpodiv").show();
        }, 4000);
        </script>

@endsection
