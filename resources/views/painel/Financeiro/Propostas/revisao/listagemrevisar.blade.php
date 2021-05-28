@extends('painel.Layout.header')
<?php $search = 2 ?>
@section('title') Visualizar Propostas para revisar @endsection
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
<link rel="stylesheet" href="{{ asset('/public/materialize/css/dataTables.checkboxes.css') }}">
<link rel="stylesheet" href="{{ asset('/public/materialize/css/app-invoice.min.css') }}">

<style>
* {
    box-sizing: border-box;
}

.wrapper {
    height: 50px;
    margin-top: calc(50vh - 150px);
    margin-left: calc(50vw - 600px);
    width: 180px;
}

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
Visualizar Propostas
@endsection

@section('submenu')

@endsection

@section('body')
<div>


    <center>
        <div id="loadingdiv">
            <div class="wrapper">
                <div class="circle circle-1"></div>
                <div class="circle circle-1a"></div>
                <div class="circle circle-2"></div>
                <div class="circle circle-3"></div>
            </div>
            <h1 style="text-align: center;">Aguarde, estamos carregando as propostas para sua revisão...&hellip;</h1>
        </div>
    </center>


    <div id="corpodiv" style="display: none">

        <div class="row">
            <div class="content-wrapper-before blue-grey lighten-5"></div>
            <div class="col s12">
                <div class="container">

                    <section class="invoice-list-wrapper section">

                        <div class="invoice-filter-action mr-3">
                            <a href="#" class="btn waves-effect waves-light invoice-export border-round z-depth-4"
                                style="background-color: gray">
                                <i class="material-icons">picture_as_pdf</i>
                                <span class="hide-on-small-only">Exportar</span>
                            </a>
                        </div>


                        <div class="responsive-table">
                            <table class="table invoice-data-table white border-radius-4 pt-1">
                                <thead>
                                    <tr>
                                        <th style="font-size: 11px"></th>
                                        <th style="font-size: 11px"><span>#</span></th>
                                        <th style="font-size: 11px">Número Proposta</th>
                                        <th style="font-size: 11px">Solicitante</th>
                                        <th style="font-size: 11px">Data Cadastro</th>
                                        <th style="font-size: 11px">Grupo</th>
                                        <th style="font-size: 11px">Cliente</th>
                                        <th style="font-size: 11px">Setor</th>
                                        <th style="font-size: 11px">Unidade</th>
                                        <th style="font-size: 11px">Valor</th>
                                        <th style="font-size: 11px">Status</th>
                                        <th style="font-size: 11px"></th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($datas as $data)
                                    <tr>
                                        <td style="font-size: 10px"></td>
                                        <td style="font-size: 10px">{{ $data->Id }}</td>
                                        <td style="font-size: 10px">{{ $data->NumeroProposta}}</td>
                                        <td style="font-size: 10px">{{ $data->Solicitante}}</td>
                                        <td style="font-size: 10px">{{ date('d/m/Y H:i:s' , strtotime($data->Data)) }}
                                        </td>
                                        <td style="font-size: 10px">{{ $data->Grupo}}</td>
                                        <td style="font-size: 10px">{{ $data->Cliente}}</td>
                                        <td style="font-size: 10px">{{ $data->Setor}}</td>
                                        <td style="font-size: 10px">{{ $data->Unidade}}</td>
                                        <td style="font-size: 10px">R$
                                            <?php echo number_format($data->Valor,2,",",".") ?></td>
                                        <td style="font-size: 10px"><span
                                                class="bullet yellow"></span>{{ $data->Status}} </td>
                                        <td style="font-size: 10px">

                                            <div class="invoice-action">
                                                @if($data->StatusID == 1)
                                                <a href="{{route('Painel.Proposta.revisao.revisarproposta', $data->Id)}}"
                                                    class="invoice-action-view mr-4"><i
                                                        class="material-icons">remove_red_eye</i></a>
                                                @endif
                                                <a href="{{route('Painel.Proposta.anexo', $data->Anexo)}}"
                                                    class="invoice-action-view mr-4"><i
                                                        class="material-icons">attachment</i></a>
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
    <script src="{{ asset('/public/materialize/js/datatables.checkboxes.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/app-invoice.min.js') }}"></script>


    <script>
    document.addEventListener("DOMContentLoaded", function() {

        document.getElementById("loadingdiv").style.display = "none";
        document.getElementById("corpodiv").style.display = "";
    });
    </script>


    @endsection