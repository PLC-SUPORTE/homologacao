@extends('painel.Layout.header')
<?php $search = true ?>

@section('title') Notificações @endsection <!-- Titulo da pagina -->

@section('header') 
    <!-- Header da pagina, para adicionar css ou links -->
    <link rel="apple-touch-icon" href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/images/favicon/apple-touch-icon-152x152.png">
    <link rel="shortcut icon" href="{{ asset('/public/assets/imgs/casinha.png')}}" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/responsive.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/materialize.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/data-tables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/app-invoice.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/chartist.min.css') }}">

    <style>
        .ui-autocomplete {
            max-height: 180px;
            overflow-y: auto;
            overflow-x: hidden;
        }

        * html .ui-autocomplete {
            height: 180px;
        }

        .ui-autocomplete-loading {
            background: white url("https://jqueryui.com/resources/demos/autocomplete/images/ui-anim_basic_16x16.gif") right center no-repeat;
        }

        .nav-custom {
            width: fit-content;
            background-color: white;
            margin-bottom: 10px;
        }

        .nav-custom .tabs.tabs-transparent .tab a.active{
            background-color: gray;
            color: white; 
        }
    
        .nav-custom  .tabs.tabs-transparent .tab a:hover,
        .nav-custom .tabs.tabs-transparent .tab a{
            color: gray;
        }

        .nav-custom .tabs.tabs-transparent .tab a.active{
            border-bottom: solid 1px gray;
        }

        .ct-chart.card::-webkit-scrollbar-track {
            background-color: #F4F4F4;
        }
        .ct-chart.card::-webkit-scrollbar {
            width: 6px;
            background: #F4F4F4;
        }
        .ct-chart.card::-webkit-scrollbar-thumb {
            background: #dad7d7;
        }
    </style>
@endsection

@section('header_title') Notificações @endsection <!-- Titulo do header da pagina -->

@section('body')
    <div class="row">
        <div class="col s12">
            <div class="ct-chart card z-depth-2 border-radius-6" style="overflow: auto; max-height: calc(100vh - 100px);">
                <div class="card-content">
                    <div class="row">
                        <div class="col s12">
                            <nav class="nav-extended nav-custom">
                                <div class="nav-content">
                                    <ul class="tabs tabs-transparent">
                                        <li class="tab"><a class="active" href="#tab_default_1">Recebidas</a></li>
                                        <li class="tab"><a class="" href="#tab_default_2">Enviadas</a></li>
                                    </ul>
                                </div>
                            </nav>
                            <div id="tab_default_1">
                                <div class="box ">
                                    <div class="box-header">
                                        <small>
                                            <a type="button"
                                                href="{{ route('Painel.Notificacao.gerarNotificacoesRecebidas') }}"
                                                class="waves-effect black waves-light btn" data-toggle="tooltip"
                                                data-placement="top"
                                                title="Exportar relação de notificações recebidas e enviadas.">
                                                <i class="material-icons left">file_download</i>Exportar</a>
                                        </small>
                                    </div>

                                    <div class="box-body">
                                        <table id="example1" class="table table-bordered table-striped"
                                            style="font-size: 12px;">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Tipo</th>
                                                    <th>Enviada Pelo</th>
                                                    <th>Data</th>
                                                    <th>Status</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($dataRecebidas as $data)
                                                <tr>
                                                    <td>{{$data->idNotificacao}}</td>
                                                    <td>{{$data->obs}}</td>
                                                    <td>{{$data->RecebidaPelo}}</td>
                                                    <td>{{ date('d/m/Y', strtotime($data->Data)) }}</td>
                                                    @if($data->Status == "A")
                                                    <td>Não Visualizado</td>
                                                    @elseif($data->Status == "V")
                                                    <td>Visualizado</td>
                                                    @endif
                                                    <td class="d-flex justify-content-center">
                                                        <a href="{{ route('Painel.Notificacao.update', ['id' => $data->idNotificacao, 'numerodebite' => $data->id_ref])}}"
                                                            class="delete">
                                                            <span class="waves-effect waves-light black btn">
                                                                <i class="material-icons left">check</i>&nbsp;&nbsp;
                                                                Marcar lido</span></a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div id="tab_default_2">
                                <div class="box ">
                                    <div class="box-header">
                                        <small>
                                            <a type="button"
                                                href="{{ route('Painel.Notificacao.gerarNotificacoesEnviadas') }}"
                                                class="waves-effect waves-light black btn" data-toggle="tooltip"
                                                data-placement="top"
                                                title="Exportar relação de notificações recebidas e enviadas.">
                                                <i class="material-icons left">file_download</i>Exportar</a>
                                        </small>
                                    </div>

                                    <div class="box-body">
                                        <table id="example1" class="table table-bordered table-striped"
                                            style="font-size: 12px;">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Tipo</th>
                                                    <th>Destino</th>
                                                    <th>Data</th>
                                                    <th>Status</th>
                                                    <!-- <th>Ação</th> -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($dataEnviadas as $data)
                                                <tr>
                                                    <td>{{$data->idNotificacao}}</td>
                                                    <td>{{$data->obs}}</td>
                                                    <td>{{$data->destino}}</td>
                                                    <td>{{ date('d/m/Y', strtotime($data->Data)) }}</td>
                                                    @if($data->Status == "A")
                                                    <td>Não visualizado</td>
                                                    @elseif($data->Status == "V")
                                                    <td>Visualizado</td>
                                                    @endif
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- section para colocar os js da pagina -->
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

    <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/app-invoice.min.js') }}"></script>
@endsection