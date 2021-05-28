@extends('painel.Layout.header')
<?php $search = 2 ?>
@section('title') Setores ativos @endsection
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
@endsection
@section('header_title')
Relação Usuários
@endsection
@section('submenu')
<li class="breadcrumb-item"><a href="{{ route('Painel.Gestao.Controlador.index') }}">Dashboard</a>
</li>
<li class="breadcrumb-item active" style="color: black;">Relação setores
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
                                        <th style="font-size: 12px">Setor</th>
                                        <th style="font-size: 12px">Setor descrição</th>
                                        <th style="font-size: 12px">Unidade</th>
                                        <th style="font-size: 12px">Unidade descrição</th>
                                        <th style="font-size: 12px"></th>
                                        <th style="font-size: 12px"></th>
                                        <th style="font-size: 12px"></th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($datas as $data)
                                    <tr>
                                        <td style="font-size: 11px"></td>
                                        <td style="font-size: 11px"></td>
                                        <td style="font-size: 11px">{{$data->Codigo}}</td>
                                        <td style="font-size: 11px">{{$data->Descricao}}</td>
                                        <td style="font-size: 11px">{{$data->Unidade_Codigo}}</td>
                                        <td style="font-size: 11px">{{$data->Unidade_Descricao}}</td>
                                        <td style="font-size: 11px">

                                            <div class="invoice-action">
                                                <a href="{{route('Painel.Gestao.Controlador.Usuarios.Setores.usuarios', $data->Codigo)}}"
                                                    class="invoice-action-view mr-4"><i
                                                        class="material-icons">person</i></a>
                                            </div>

                                        </td>
                                        <td style="font-size: 11px"></td>
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
@section('scripts')

    <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/app-invoice.min.js') }}"></script>


    <script>
    function envia() {

        document.getElementById("loadingdiv").style.display = "";
        document.getElementById("corpodiv").style.display = "none";
        document.getElementById("form").submit();
    }
    </script>

@endsection