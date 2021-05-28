@extends('painel.Layout.header')
<?php $search = 2 ?>
@section('title') Detalhar nota objetivo @endsection
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
Nota consolidada detalhamento
@endsection
@section('submenu')
<li class="breadcrumb-item"><a href="{{ route('Painel.Gestao.Controlador.index') }}">Dashboard</a>
</li>
<li class="breadcrumb-item"><a href="{{ route('Painel.Gestao.Controlador.NotasConsolidada.index') }}">Notas
        Consolidadas</a>
</li>
<li class="breadcrumb-item active" style="color: black;">Nota consolidada detalhamento objetivo
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
                    <h1 style="text-align: center;">Aguarde, estamos carregando o detalhamento do objetivo...&hellip;
                    </h1>
                </div>
            </center>


            <div class="row" id="corpodiv">

                <div class="content-wrapper-before blue-grey lighten-5"></div>
                <div class="col s12">
                    <div class="container">


                        <section class="invoice-list-wrapper section">

                            <div class="invoice-filter-action mr-4">
                                <a href="{{ route('Painel.Gestao.Controlador.NotasConsolidada.exportardetalhamentomes', ['cpf' => $cpf, 'id' => $id_objetivo]) }}"
                                    class="btn-floating btn-mini waves-effect waves-light buttons-right tooltipped"
                                    data-position="left"
                                    data-tooltip="Clique aqui para exportar em Excel o grid abaixo."
                                    style="background-color: gray;"><img
                                        style="margin-top: 8px; width: 20px;margin-left:8px;"
                                        src="{{URL::asset('/public/imgs/icon.png')}}" /></a>
                            </div>

                            <div class="responsive-table">
                                <table class="table invoice-data-table white border-radius-4 pt-1">
                                    <thead>
                                        <tr>
                                            <th style="font-size: 12px"></th>
                                            <th style="font-size: 12px">Objetivo</th>
                                            <th style="font-size: 12px">Mês</th>
                                            <th style="font-size: 12px" class="tooltipped" data-position="top"
                                                data-tooltip="Nota mínima.">Score 90</th>
                                            <th style="font-size: 12px">Score 100</th>
                                            <th style="font-size: 12px" class="tooltipped" data-position="top"
                                                data-tooltip="Nota máxima.">Score 120</th>
                                            <th style="font-size: 12px">Realizado mês</th>
                                            <th style="font-size: 12px">Realizado acumulado</th>
                                            <th style="font-size: 12px">Nota mês</th>
                                            <th style="font-size: 12px">Nota mês (120)</th>
                                            <th style="font-size: 12px">Nota acumulada</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($notaDetalhadaPorObjetivo as $data)
                                        <tr>
                                            <td style="font-size: 11px"></td>
                                            <td style="font-size: 11px">{{$data->objetivo}}</td>
                                            <td style="font-size: 11px">{{$data->mes}}</td>
                                            <td style="font-size: 11px">{{$data->score90}}</td>
                                            <td style="font-size: 11px">{{$data->meta}}</td>
                                            <td style="font-size: 11px">{{$data->score120}}</td>
                                            <td style="font-size: 11px">{{$data->realizado}}</td>
                                            <td style="font-size: 11px">{{$data->nota_acumulada}}</td>
                                            <td style="font-size: 11px">{{$data->realizado}}</td>
                                            <td style="font-size: 11px">{{$data->nota}}</td>
                                            <td style="font-size: 11px">{{$data->nota_consolidada_acumulada}}</td>

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

        <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
        <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
        <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>
        <script src="{{ asset('/public/materialize/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('/public/materialize/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('/public/materialize/js/app-invoice.min.js') }}"></script>
        <script src="{{ asset('/public/materialize/js/vendors.min.js') }}"></script>

        <script>
        $(document).ready(function() {
            $("#corpodiv").hide();
        });
        </script>


        <script>
        setTimeout(function() {
            $('#loading').fadeOut('fast');
            $("#corpodiv").show();
        }, 6000);
        </script>
