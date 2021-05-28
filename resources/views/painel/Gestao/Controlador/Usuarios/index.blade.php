@extends('painel.Layout.header')
<?php $search = 2 ?>
@section('title') Usuários ativos @endsection
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
Relação Usuários
@endsection
@section('submenu')
<li class="breadcrumb-item"><a href="{{ route('Painel.Gestao.Controlador.index') }}">Dashboard</a>
</li>
<li class="breadcrumb-item active" style="color: black;">Relação usuários
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
                    <h1 style="text-align: center;">Aguarde, estamos carregando os usuários ativos...&hellip;</h1>
                </div>
            </center>


            <div class="row" id="paginadiv">
                <div class="content-wrapper-before blue-grey lighten-5"></div>
                <div class="col s12">
                    <div class="container">

                        <section class="invoice-list-wrapper section">

                            <div class="invoice-filter-action mr-4">
                                <a href="{{ route('Painel.Gestao.Controlador.Usuarios.exportar') }}"
                                    class="btn waves-effect waves-light invoice-export border-round z-depth-4"
                                    style="background-color: gray">
                                    <i class="material-icons">import_export</i>
                                    <span class="hide-on-small-only">Exportar</span>
                                </a>
                            </div>


                            <div class="responsive-table">
                                <table class="table invoice-data-table white border-radius-4 pt-1">
                                    <thead>
                                        <tr>
                                            <th style="font-size: 12px"></th>
                                            <th style="font-size: 12px">#</th>
                                            <th style="font-size: 12px">Nome</th>
                                            <th style="font-size: 12px">E-mail</th>
                                            <th style="font-size: 12px">CPF</th>
                                            <th style="font-size: 12px">Unidade</th>
                                            <th style="font-size: 12px">Setor</th>
                                            <th style="font-size: 12px">Nível</th>
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
                                            <td style="font-size: 11px">
                                                {{mb_convert_case($data->usuario_nome, MB_CASE_TITLE, "UTF-8")}}</td>
                                            <td style="font-size: 11px">{{$data->usuario_email}}</td>
                                            <td style="font-size: 11px">{{$data->usuario_codigo}}</td>
                                            <td style="font-size: 11px">{{$data->unidade}}</td>
                                            <td style="font-size: 11px">{{$data->setor}}</td>
                                            <td style="font-size: 11px">{{$data->nivel}}</td>
                                            <td style="font-size: 12px">
                                                {{ date('d/m/Y', strtotime($data->datainicio)) }}</td>
                                            @if ($data->datafim == null)
                                            <td style="font-size: 11px; color: red;">Não possui</td>
                                            @else
                                            <td style="font-size: 12px">{{ date('d/m/Y', strtotime($data->datafim)) }}
                                            </td>
                                            @endif
                                            <td style="font-size: 11px"></td>
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
            $("#paginadiv").hide();
        });
        </script>


        <script>
        setTimeout(function() {
            $('#loading').fadeOut('fast');
            $("#paginadiv").show();
        }, 3000);
        </script>

        <script>
        function envia() {

            document.getElementById("loadingdiv").style.display = "";
            document.getElementById("corpodiv").style.display = "none";
            document.getElementById("form").submit();
        }
        </script>
