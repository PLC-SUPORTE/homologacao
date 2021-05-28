@extends('painel.Layout.header')
<?php $search = 2 ?>
@section('title') Meus contratos @endsection
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
Programa de Distribuição de Resultado Variável ("RV")
@endsection
@section('submenu')
<li class="breadcrumb-item"><a href="{{ route('Painel.Gestao.Meritocracia.index') }}">Dashboard individual acumulado</a>
</li>
<li class="breadcrumb-item active" style="color: black;">Meus contratos
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
                    <h1 style="text-align: center;">Aguarde, enquanto estamos carregando os seus contratos...&hellip;
                    </h1>
                </div>
            </center>


            <div class="row" id="corpodiv">
                <div class="content-wrapper-before blue-grey lighten-5"></div>
                <div class="col s12">
                    <div class="container">

                        <section class="invoice-list-wrapper section">

                            <div class="invoice-filter-action mr-4">

                                <div class="invoice-filter-action mr-4">

                                    <a href="{{ route('Painel.Gestao.Meritocracia.index') }}"
                                        class="btn-floating btn-mini waves-effect waves-light buttons-right tooltipped"
                                        data-position="top" data-tooltip="Voltar ao dashboard individual acumulado"
                                        style="background-color: gray;"><i class="material-icons">home</i></a>

                                    <a href="{{ route('Painel.Gestao.Meritocracia.CartasRV.historico') }}"
                                        class="btn-floating btn-mini waves-effect waves-light buttons-right tooltipped"
                                        data-position="left" data-tooltip="Dashboard individual histórico"
                                        style="background-color: gray;"><i class="material-icons">dashboard</i></a>

                                    <a href="{{ route('Painel.Gestao.Meritocracia.minhasnotas') }}"
                                        class="btn-floating btn-mini waves-effect waves-light buttons-right tooltipped"
                                        data-position="top" data-tooltip="Minhas notas"
                                        style="background-color: gray;"><i class="material-icons">analytics</i></a>

                                    @if($nivel == "Superintendente" || $nivel == "Coordenador" || $nivel ==
                                    "Subcoordenador 1" || $nivel == "Subcoordenador 2" ||
                                    $nivel == "Coordenador Controladoria" || $nivel == "Coordenador ControladoriaSP" ||
                                    $nivel == "Gerente" || $nivel == "Gerente Equipe Passiva" || $nivel == "COO")
                                    <a href="{{ route('Painel.Gestao.Meritocracia.Hierarquia.index') }}"
                                        class="btn-floating btn-mini waves-effect waves-light buttons-right tooltipped"
                                        data-position="left" data-tooltip="Dashboard meritocracia"
                                        style="margin-left: 5px;background-color: gray; margin-top: 4px;"><i
                                            class="material-icons"
                                            style="margin-top: -2px;">connect_without_contact</i></a>

                                    <!-- <a href="{{ route('Painel.Gestao.Meritocracia.Hierarquia.Setor.index') }}"
                                        class="btn-floating btn-mini waves-effect waves-light buttons-right tooltipped"
                                        data-position="left" data-tooltip="Dashboard equipe"
                                        style="margin-left: 5px;background-color: gray; margin-top: 4px;"><i
                                            class="material-icons" style="margin-top: -2px;">groups</i></a> -->

                                    @endif


                                    <a href="{{ route('Painel.Gestao.Meritocracia.Prazos.index') }}"
                                        class="btn-floating btn-mini waves-effect waves-light buttons-right tooltipped"
                                        data-position="top" data-tooltip="Volumetria de prazos vencidos meta"
                                        style="background-color: gray; margin-top: 4px;"><i
                                            class="material-icons">hourglass_disabled</i></a>

                                    <!-- <a href="{{ route('Painel.Gestao.Meritocracia.Contrato.index') }}" 
        class="btn-floating btn-mini waves-effect waves-light buttons-right tooltipped" 
        data-position="top" data-tooltip="Meus contratos" style="background-color: gray; margin-top: 4px;"><i class="material-icons">description</i></a> -->

                                </div>
                            </div>


                            <div class="responsive-table">
                                <table class="table invoice-data-table white border-radius-4 pt-1">
                                    <thead>
                                        <tr>
                                            <th style="font-size: 12px"></th>
                                            <th style="font-size: 12px"></th>
                                            <th style="font-size: 12px"></th>
                                            <th style="font-size: 12px">Contrato</th>
                                            <th style="font-size: 12px">Ano exercicio</th>
                                            <th style="font-size: 12px">Status</th>
                                            <th style="font-size: 12px"></th>
                                            <th style="font-size: 12px"></th>
                                            <th style="font-size: 12px"></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($datas as $data)
                                        <tr>

                                            <!--Modal perguntando -->
                                            <div id="modalperguntando{{$data->id}}" class="modal">
                                                <form id="form" role="form"
                                                    action="{{route('Painel.Gestao.Meritocracia.Contrato.baixar', $data->anexo)}}"
                                                    method="GET" role="create">
                                                    {{ csrf_field() }}

                                                    <button type="button"
                                                        class="btn waves-effect mr-sm-1 mr-2 modal-close red"
                                                        style="margin-left: 645px; margin-top: 5px;">
                                                        <i class="material-icons">close</i>
                                                    </button>


                                                    <center>
                                                        <div id="loadingdiv2" style="display:none">
                                                            <div class="wrapper">
                                                                <div class="circle circle-1"></div>
                                                                <div class="circle circle-1a"></div>
                                                                <div class="circle circle-2"></div>
                                                                <div class="circle circle-3"></div>
                                                            </div>
                                                            <h1 style="text-align: center;">Aguarde, enquanto estamos
                                                                buscando o anexo...&hellip;</h1>
                                                        </div>
                                                    </center>

                                                    <div id="corpodiv2">
                                                        <h6>Contrato {{$data->tipo}}</h6>
                                                        <p>Deseja baixar o contrato em formato digital ?</p>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <a type="button" id="btnsubmit" onClick="envia2();"
                                                            class="modal-action waves-effect waves-green btn-flat"
                                                            style="background-color: green;color:white; margin-top: 40px; margin-right: -28px;"><i
                                                                class="material-icons left">check</i>Baixar contrato</a>
                                                    </div>
                                                </form>
                                            </div>
                                            <!--Fim Modal -->



                                            <td style="font-size: 11px"></td>
                                            <td style="font-size: 11px"></td>
                                            <td style="font-size: 11px"></td>
                                            <td style="font-size: 11px">{{$data->tipo}}</td>
                                            <td style="font-size: 11px">{{$data->ano}}</td>
                                            @if($data->status_id = 1)
                                            <td style="font-size: 11px"><span
                                                    class="bullet yellow"></span>{{ $data->status}} </td>
                                            @else
                                            <td style="font-size: 11px"><span
                                                    class="bullet green"></span>{{ $data->status}} </td>
                                            @endif

                                            <td style="font-size: 11px">

                                                <div class="invoice-action">
                                                    <!-- <a href="{{route('Painel.Gestao.Meritocracia.Contrato.visualizar', $data->id)}}" class="invoice-action-view mr-4"><i class="material-icons">remove_red_eye</i></a> -->
                                                    <a href="#modalperguntando{{$data->id}}"
                                                        class="invoice-action-view mr-4 modal-trigger"><i
                                                            class="material-icons">attachment</i></a>

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
        $(document).ready(function() {
            $("#corpodiv").hide();
        });
        </script>


        <script>
        setTimeout(function() {
            $('#loading').fadeOut('fast');
            $("#corpodiv").show();
        }, 3000);
        </script>


        <script>
        function envia2() {

            document.getElementById("loadingdiv2").style.display = "";
            document.getElementById("corpodiv2").style.display = "none";
            document.getElementById("form").submit();
        }
        </script>


@endsection