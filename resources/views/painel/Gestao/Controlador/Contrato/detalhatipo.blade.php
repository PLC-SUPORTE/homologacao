@extends('painel.Layout.header')
<?php $search = 2 ?>
@section('title') Listagem detalhada contrato @endsection
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
Contratos
@endsection
@section('submenu')
<li class="breadcrumb-item"><a href="{{ route('Painel.Gestao.Controlador.index') }}">Dashboard</a>
</li>
<li class="breadcrumb-item"><a href="{{ route('Painel.Gestao.Controlador.Contrato.index') }}">Tipos de contrato</a>
</li>
<li class="breadcrumb-item active" style="color: black;">Listagem detalhada contrato
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
                    <h1 style="text-align: center;">Aguarde, estamos carregando os contratos...&hellip;</h1>
                </div>
            </center>


            <div class="row" id="corpodiv">
                <div class="content-wrapper-before blue-grey lighten-5"></div>
                <div class="col s12">
                    <div class="container">

                        <section class="invoice-list-wrapper section">

                            <div class="invoice-filter-action mr-4">
                                <a href="#novocontrato"
                                    class="btn-floating btn-mini waves-effect waves-light tooltipped modal-trigger"
                                    data-position="left" data-tooltip="Clique aqui para anexar um novo contrato."
                                    style="margin-left: 5px;background-color: gray;"><i
                                        class="material-icons">add</i></a>
                                <a href="{{ route('Painel.Gestao.Controlador.Contrato.exportardetalhamento', $id) }}"
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
                                            <th style="font-size: 11px"></th>
                                            <th style="font-size: 11px"></th>
                                            <th style="font-size: 11px"></th>
                                            <th style="font-size: 11px">Sócio</th>
                                            <th style="font-size: 11px">Contrato</th>
                                            <th style="font-size: 11px">Ano exercicio</th>
                                            <th style="font-size: 11px">Status</th>
                                            <th style="font-size: 11px"></th>
                                            <th style="font-size: 11px"></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($datas as $data)
                                        <tr>


                                            <!--Modal perguntando -->
                                            <div id="modalperguntando{{$data->id}}" class="modal">
                                                <form id="form" role="form"
                                                    action="{{route('Painel.Gestao.Meritocracia.Contrato.baixar', $data->id)}}"
                                                    method="GET" role="create">
                                                    {{ csrf_field() }}


                                                    <div class="modal-content" style="overflow: hidden;">
                                                        <button type="button"
                                                            class="btn waves-effect mr-sm-1 mr-2 modal-close red"
                                                            style="margin-left: 645px; margin-top: -32px;">
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
                                                                <h1 style="text-align: center;">Aguarde, enquanto
                                                                    estamos buscando seu contrato...&hellip;</h1>
                                                            </div>
                                                        </center>

                                                        <div id="corpodiv2">
                                                            <h6>Contrato {{$data->tipo}}</h6>
                                                            <p>Deseja baixar o contrato em formato digital ?</p>

                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" id="btnsubmit" onClick="envia2();"
                                                                class="modal-action waves-effect waves-green btn-flat"
                                                                style="background-color: green;color:white; margin-top: 40px; margin-right: -28px;"><i
                                                                    class="material-icons left">check</i>Prosseguir</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <!--Fim Modal -->


                                            <td style="font-size: 10px"></td>
                                            <td style="font-size: 10px"></td>
                                            <td style="font-size: 10px"></td>
                                            <td style="font-size: 10px">
                                                <?php echo mb_convert_case($data->usuario, MB_CASE_TITLE, "UTF-8")?>
                                            </td>
                                            <td style="font-size: 10px">{{$data->tipo}}</td>
                                            <td style="font-size: 10px">{{$data->ano}}</td>
                                            @if($data->status_id = 1)
                                            <td style="font-size: 10px"><span
                                                    class="bullet yellow"></span>{{ $data->status}} </td>
                                            @else
                                            <td style="font-size: 10px"><span
                                                    class="bullet green"></span>{{ $data->status}} </td>
                                            @endif

                                            <td style="font-size: 10px">

                                                <div class="invoice-action">
                                                    <a href="#modalperguntando{{$data->id}}"
                                                        class="invoice-action-view mr-4 modal-trigger"><i
                                                            class="material-icons">attachment</i></a>
                                                </div>

                                            </td>
                                            <td></td>
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
        <div id="novocontrato" class="modal" style="width: 960px;">
            <form id="form" role="form" action="{{ route('Painel.Gestao.Controlador.Contrato.anexado') }}" method="POST"
                role="create" enctype="multipart/form-data">
                {{ csrf_field() }}

                <input type="hidden" name="tipo_id" value="{{$id}}">


                <div class="modal-content">

                    <center>
                        <div id="loadingdiv2" style="display:none">
                            <div class="wrapper">
                                <div class="circle circle-1"></div>
                                <div class="circle circle-1a"></div>
                                <div class="circle circle-2"></div>
                                <div class="circle circle-3"></div>
                            </div>
                            <h1 style="text-align: center;">Aguarde, estamos armazenando o contrato...&hellip;</h1>
                        </div>
                    </center>

                    <button type="button" class="btn waves-effect mr-sm-1 mr-2 modal-close red"
                        style="margin-top: -26px; margin-left: 835px;"><i class="material-icons">close</i></button>

                    <div id="corpodiv2">
                        <h5>Novo contrato</h5>
                        <p>Deseja anexar um novo contrato ?

                            <br>

                        <div class="row">

                            <div class="input-field col s4">
                                <span>Selecione o sócio</span>
                                <select class="select2-customize-result browser-default" name="usuario">
                                    @foreach($usuarios as $usuario)
                                    <option value="{{$usuario->id}}">{{$usuario->name}}</option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="input-field col s3">
                                <span>Selecione o ano exercicio</span>
                                <select class="select2-customize-result browser-default" name="ano">
                                    <option value="2020">2020</option>
                                </select>
                            </div>

                            <div class="input-field col s5" style="margin-top: 55px;">
                                <input type="file" id="input-file-now" name="select_file" accept=".pdf" class="dropify"
                                    data-default-file="" />
                            </div>

                        </div>

                    </div>
                    <div class="modal-footer" style="margin-top: -30px;">
                        <button type="button" id="btnsubmit" onClick="envia();"
                            class="modal-action waves-effect btn-flat" style="background-color: gray;color:white;"><i
                                class="material-icons left">save_alt</i>Salvar</button>
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
        <script src="{{ asset('/public/materialize/js/vendors.min.js') }}"></script>

        <script>
        $(document).ready(function() {
            $("#corpodiv").hide();
            $('.modal').modal();
        });
        </script>


        <script>
        setTimeout(function() {
            $('#loading').fadeOut('fast');
            $("#corpodiv").show();
        }, 3000);
        </script>

        <script>
        function envia() {

            document.getElementById("loadingdiv2").style.display = "";
            document.getElementById("corpodiv2").style.display = "none";
            document.getElementById("form").submit();
        }
        </script>

