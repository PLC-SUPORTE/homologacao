@extends('painel.Layout.header')
<?php $search = 2 ?>
@section('title') Solicitações aguardando revisão - Pesquisa Patrimonial @endsection
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
Pesquisa patrimonial
@endsection
@section('submenu')
<li class="breadcrumb-item"><a href="{{ route('Painel.PesquisaPatrimonial.supervisao.index') }}">Dashboard</a>
</li>
<li class="breadcrumb-item active" style="color: black;">Solicitações aguardando revisão da supervisão de pesquisa
    patrimonial
</li>
@endsection
@section('body')
    <div>
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
                                        <th style="font-size: 12px"></th>
                                        <th style="font-size: 12px"><span>#</span></th>
                                        <th style="font-size: 12px">Código</th>
                                        <th style="font-size: 12px">Pesquisado</th>
                                        <th style="font-size: 12px">Cliente</th>
                                        <th style="font-size: 12px">Pasta</th>
                                        <th style="font-size: 12px">Cobrável</th>
                                        <th style="font-size: 12px">Classificação</th>
                                        <th style="font-size: 12px">Solicitação</th>
                                        <th style="font-size: 12px">Serviço</th>
                                        <th style="font-size: 12px">Valor</th>
                                        <th style="font-size: 12px">Data</th>
                                        <th style="font-size: 12px">Status</th>
                                        <th style="font-size: 12px"></th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($datas as $data)
                                    <tr>
                                        <td style="font-size: 11px"></td>
                                        <td style="font-size: 11px">{{ $data->Id }}</td>
                                        <td style="font-size: 11px">{{ $data->CPF }}</td>
                                        <td style="font-size: 11px">{{ $data->OutraParte}}</td>
                                        <td style="font-size: 11px">{{ $data->Cliente}}</td>
                                        <td style="font-size: 11px">{{ $data->Pasta}}</td>
                                        <td style="font-size: 11px">{{ $data->Cobravel}}</td>
                                        <td style="font-size: 11px">{{ $data->Classificacao}}</td>
                                        <td style="font-size: 11px">{{ $data->TipoSolicitacao}}</td>
                                        <td style="font-size: 11px">{{ $data->TipoServico}}</td>
                                        <td style="font-size: 10px">R$
                                            <?php echo number_format($data->Valor,2,",",".") ?></td>
                                        <td style="font-size: 11px">
                                            {{ date('d/m/Y', strtotime($data->DataSolicitacao)) }}</td>
                                        <td style="font-size: 11px"><span
                                                class="bullet yellow"></span>{{ $data->Status}} </td>
                                        <td style="font-size: 11px">

                                            <div class="invoice-action">
                                                @if($data->StatusID == 9)
                                                <a href="{{route('Painel.PesquisaPatrimonial.supervisao.avaliar', $data->Id)}}"
                                                    class="invoice-action-view mr-4"><i
                                                        class="material-icons">assignment_late</i></a>
                                                @endif

                                                @if(!empty($data->anexo))
                                                <a href="{{route('Painel.PesquisaPatrimonial.anexo', $data->anexo)}}"
                                                    class="invoice-action-edit"><i
                                                        class="material-icons">attach_file</i></a>
                                                @endif
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
    <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/app-invoice.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/vendors.min.js') }}"></script>

