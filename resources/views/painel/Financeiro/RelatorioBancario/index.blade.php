@extends('painel.Layout.header')
<?php $search = 2 ?>
@section('title') Relatório bancário @endsection
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
* {
    box-sizing: border-box;
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
Relatório bancário
@endsection

@section('submenu')
<li class="breadcrumb-item active" style="color: black;">Relatório bancário
</li>
@endsection

@section('body')
    <div>
        <div class="row">
            <div class="content-wrapper-before blue-grey lighten-5"></div>

            <center>
                <div id="loading">
                    <div class="wrapper">
                        <div class="circle circle-1"></div>
                        <div class="circle circle-1a"></div>
                        <div class="circle circle-2"></div>
                        <div class="circle circle-3"></div>
                    </div>
                    <h1 style="text-align: center;">Aguarde, estamos carregando o relatório bancário...&hellip;</h1>
                </div>
            </center>



            <div class="col s12" id="corpodiv">

                <div class="container">
                    <div class="section">

                        <section class="invoice-list-wrapper section">

                            <div class="invoice-filter-action mr-3">
                                <a href="{{route('Painel.Financeiro.RelatorioBancario.exportar')}}"
                                    class="btn waves-effect waves-light invoice-export border-round z-depth-4"
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
                                            <th style="font-size: 12px">Grupo</th>
                                            <th style="font-size: 12px">Descrição</th>
                                            <th style="font-size: 12px">Agência</th>
                                            <th style="font-size: 12px">Conta</th>
                                            <th style="font-size: 12px">Unidade</th>
                                            <th style="font-size: 12px">Entrada</th>
                                            <th style="font-size: 12px">Saída</th>
                                            <th style="font-size: 12px">Saldo</th>
                                            <th style="font-size: 12px"></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($datas as $data)
                                        <tr>
                                            <td style="font-size: 11px"></td>
                                            <td style="font-size: 11px"></td>
                                            <td style="font-size: 11px">{{ $data->codigo_banco }}</td>
                                            <td style="font-size: 11px">{{ $data->grupo }}</td>
                                            <td style="font-size: 11px">{{ $data->descricao_banco }}</td>
                                            <td style="font-size: 11px">{{ $data->agencia_banco }}</td>
                                            <td style="font-size: 11px">{{ $data->conta_banco }}</td>
                                            <td style="font-size: 11px">{{ $data->unidade }}</td>
                                            <td style="font-size: 11px"><span class="bullet green"></span>R$
                                                <?php echo number_format($data->entrada,2,",",".") ?></td>
                                            <td style="font-size: 11px"><span class="bullet red"></span>R$
                                                <?php echo number_format($data->saida,2,",",".") ?></td>
                                            @if($data->saldo > 0)
                                            <td style="font-size: 11px"><span class="bullet green"></span>R$
                                                <?php echo number_format($data->saldo,2,",",".") ?></td>
                                            @else
                                            <td style="font-size: 11px"><span class="bullet red"></span>R$
                                                <?php echo number_format($data->saldo,2,",",".") ?></td>
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
@section('scripts')

        <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
        <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
        <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>
        <script src="{{ asset('/public/materialize/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('/public/materialize/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('/public/materialize/js/app-invoice.min.js') }}"></script>


        <script>
        $(document).ready(function() {
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