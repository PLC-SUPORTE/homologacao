@extends('painel.Layout.header')
<?php $search = 2 ?>
@section('title') Notas Sócios @endsection
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
<link rel="stylesheet" href="{{ asset('/public/materialize/css/dropify.min.css') }}">
<link rel="stylesheet" href="{{ asset('/public/materialize/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('/public/materialize/css/select2-materialize.css') }}">
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
Notas Sócios
@endsection
@section('submenu')
<li class="breadcrumb-item"><a href="{{ route('Painel.Gestao.Controlador.index') }}">Dashboard</a>
</li>
<li class="breadcrumb-item active" style="color: black;">Notas Sócios
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
                    <h1 style="text-align: center;">Aguarde, estamos carregando as notas...&hellip;</h1>
                </div>
            </center>


            <div class="row" id="paginadiv">
                <div class="content-wrapper-before blue-grey lighten-5"></div>
                <div class="col s12">
                    <div class="container">

                        <section class="invoice-list-wrapper section">

                            <div class="invoice-filter-action mr-4">

                                <a href="#modal"
                                    class="btn-floating btn-mini waves-effect waves-light tooltipped modal-trigger"
                                    data-position="left" data-tooltip="Clique aqui para adicionar uma nova nota sócio."
                                    style="margin-left: 5px;background-color: gray;"><i
                                        class="material-icons">add</i></a>
                                <a href="{{ route('Painel.Gestao.Controlador.NotasAdvogado.historico') }}"
                                    class="btn-floating btn-mini waves-effect waves-light tooltipped"
                                    data-position="left"
                                    data-tooltip="Clique aqui para visualizar as notas dos sócios referente ao ano de apuração."
                                    style="margin-left: 5px;background-color: gray;"><i
                                        class="material-icons">assignment</i></a>
                                <a href="{{ route('Painel.Gestao.Controlador.NotasAdvogado.exportarnotasmes') }}"
                                    class="btn-floating btn-mini waves-effect waves-light buttons-right tooltipped"
                                    data-position="left"
                                    data-tooltip="Clique aqui para exportar em Excel as notas sócio no ano de apuração."
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
                                            <th style="font-size: 12px">Objetivo</th>
                                            <th style="font-size: 12px">Nota</th>
                                            <th style="font-size: 12px"></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($datas as $data)
                                        <tr>


                                            <!--Abre modal perguntando se deseja mesmo deletar a nota -->

                                            <div id="modal{{$data->id}}" class="modal">
                                                <div class="modal-content">
                                                    <button type="button"
                                                        class="btn waves-effect mr-sm-1 mr-2 modal-close red"
                                                        style="margin-left: 633px;margin-top: -30px;">
                                                        <i class="material-icons">close</i>
                                                    </button>
                                                    <h4>Deletar nota</h4>
                                                    <p>Você deseja deletar a nota do usuário:
                                                        <strong>{{mb_convert_case($data->usuario_nome, MB_CASE_TITLE, "UTF-8")}}</strong>,
                                                        referente ao mes: <strong>{{$data->mes}}</strong>, do objetivo:
                                                        <strong>{{$data->objetivo}}</strong> com a pontuação de:
                                                        <strong>{{$data->nota}}</strong>.</p>
                                                    <p>Ao deletar a nota favor atualizar as procedoure de nota
                                                        consolidada e média score para novo cálculo. </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <a href="{{route('Painel.Gestao.Controlador.NotasAdvogado.deletarnota', $data->id)}}"
                                                        class="modal-action modal-close waves-effect waves-green btn-flat"
                                                        style="background-color: green;color: white"><i
                                                            class="material-icons left">check</i>SIM</a>
                                                </div>
                                            </div>

                                            <!--Fim Modal -->


                                            <td style="font-size: 11px"></td>
                                            <td style="font-size: 11px"></td>
                                            <td style="font-size: 11px">
                                                {{mb_convert_case($data->usuario_nome, MB_CASE_TITLE, "UTF-8")}}</td>
                                            <td style="font-size: 11px">{{$data->unidade}}</td>
                                            <td style="font-size: 11px">{{$data->setor}}</td>
                                            <td style="font-size: 11px">{{$data->mes}}</td>
                                            <td style="font-size: 11px">{{$data->nivel}}</td>
                                            <td style="font-size: 11px">{{$data->objetivo}}</td>
                                            <td style="font-size: 11px">{{$data->nota}}</td>

                                            <td style="font-size: 11px">

                                                <div class="invoice-action">
                                                    <a href="{{route('Painel.Gestao.Controlador.NotasAdvogado.editar', $data->id)}}"
                                                        class="invoice-action-view mr-4"><i
                                                            class="material-icons">edit</i></a>
                                                    <a href="#modal{{$data->id}}"
                                                        class="invoice-action-view mr-4 modal-trigger"><i
                                                            class="material-icons">close</i></a>

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


        <div id="modal" class="modal" style="width: 980px;">
            <form id="form" role="form" action="{{ route('Painel.Gestao.Controlador.NotasAdvogado.gravar') }}"
                method="POST" role="create" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input required type="hidden" name="opcao" id="opcao" value="">
                <div class="modal-content">
                    <center>
                        <div id="loadingdiv" style="display:none">
                            <div class="wrapper">
                                <div class="circle circle-1"></div>
                                <div class="circle circle-1a"></div>
                                <div class="circle circle-2"></div>
                                <div class="circle circle-3"></div>
                            </div>
                            <h1 style="text-align: center;">Gravando registro(s)...&hellip;</h1>
                        </div>
                    </center>

                    <div id="corpodiv">
                        <a class="waves-effect modal-close waves-light btn red right align"
                            style="margin-top: -32px; margin-right: -20px;"><i
                                style="margin-left: 15px; font-size: 20px;" class="material-icons left">close</i></a>
                        <h5>Nova nota</h5>
                        <p>Deseja adicionar o informativo RV manualmente ou via importação Excel ?
                            <a href="#modalInfo" style="color: gray;" class="modal-trigger"><i
                                    class="material-icons">info</i></a>
                        </p>
                        <!-- Modal Structure -->
                        <div id="modalInfo" class="modal" style="overflow: hidden;">
                            <div class="modal-content" style="overflow: hidden;">
                                <a class="waves-effect modal-close waves-light btn red right align"
                                    style="margin-top: -20px; margin-right: -25px;"><i style="margin-left: 15px;"
                                        class="material-icons left">close</i></a>
                                <h6>Informações</h6>
                                <p>Para importação de notas em massa, utilize a planilha com o layout fixo, no qual o
                                    formato das colunas deverá ser texto.</p>
                            </div>
                        </div>
                        <a id="btnmanualmente" onClick="manualmente();"
                            class="modal-action waves-effect waves-green btn-flat"
                            style="background-color: gray;color:white"><i
                                class="material-icons left">source</i>Manualmente</a>
                        <a id="btnimportacao" onClick="importacao();" class="modal-action  waves-effect btn-flat"
                            style="background-color: gray;color:white;"><i
                                class="material-icons left">import_export</i>Importação Excel</a>
                        <a href="{{ route('Painel.Gestao.anexo', 'CartaRV.xlsx') }}"
                            class="waves-effect waves-green btn-flat" style="background-color: green;color:white;"><i
                                class="material-icons left">text_snippet</i>Baixar modelo importação</a>
                        <br>
                        <!--Div Manualmente -->
                        <div id="manualmentediv" style="margin-top: 20px;">
                            <div class="row">


                                <div class="input-field col s4" style="margin-top: 12px;">
                                    <select class="select2 browser-default" id="icon_prefix" name="usuario">
                                        @foreach($usuarios as $usuario)
                                        <option value="{{$usuario->id}}" style="font-size: 8px;">{{$usuario->name}}
                                        </option>
                                        @endforeach
                                    </select>
                                    <label>Usuário</label>
                                </div>

                                <div class="input-field col s2" style="margin-top: 12px;">
                                    <select class="select2 browser-default" id="mes" name="mes">
                                        <option value="01">Janeiro</option>
                                        <option value="02">Fevereiro</option>
                                        <option value="03">Março</option>
                                        <option value="04">Abril</option>
                                        <option value="05">Maio</option>
                                        <option value="06">Junho</option>
                                        <option value="07">Julho</option>
                                        <option value="08">Agosto</option>
                                        <option value="09">Setembro</option>
                                        <option value="10">Outubro</option>
                                        <option value="11">Novembro</option>
                                        <option value="12">Dezembro</option>
                                    </select>
                                    <label>Mês</label>
                                </div>

                                <div class="input-field col s2" style="margin-top: 12px;">
                                    <select class="select2 browser-default" id="ano" name="ano">
                                        <option value="{{$ano}}">{{$ano}}</option>
                                    </select>
                                    <label>Ano</label>
                                </div>

                                <div class="input-field col s3" style="margin-top: 12px;">
                                    <select class="select2 browser-default" id="objetivo" name="objetivo">
                                        @foreach($objetivos as $objetivo)
                                        <option value="{{$objetivo->id}}">{{$objetivo->objetivo}}</option>
                                        @endforeach
                                    </select>
                                    <label>Objetivo</label>
                                </div>


                                <div class="input-field col s3" style="margin-top: 12px;">
                                    <select class="select2 browser-default" id="nivel" name="nivel">
                                        <option value="Advogado">Advogado</option>
                                        <option value="Advogado Controladoria">Advogado Controladoria</option>
                                        <option value="Advogado ControladoriaSP">Advogado ControladoriaSP</option>
                                        <option value="COO">COO</option>
                                        <option value="Coordenador">Coordenador</option>
                                        <option value="Coordenador Controladoria">Coordenador Controladoria</option>
                                        <option value="Coordenador ControladoriaSP">Coordenador ControladoriaSP</option>
                                        <option value="Gerente">Gerente</option>
                                        <option value="Gerente Equipe Passiva">Gerente Equipe Passiva</option>
                                        <option value="Superintendente">Superintendente</option>
                                        <option value="Subcoordenador 1">Subcoordenador 1</option>
                                        <option value="Subcoordenador 2">Subcoordenador 2</option>
                                    </select>
                                    <label>Nível</label>
                                </div>

                                <div class="input-field col s2">
                                    <input id="icon_telephone" type="text" name="nota" class="validate"
                                        placeholder="Informe a nota.." style="margin-top: -2px;">
                                    <label for="icon_telephone">Nota</label>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!--Fim div manualmente -->
                    <!--Div Importação -->
                    <div id="importacaodiv">
                        <div class="col s12 m8 l9">
                            <br>
                            <h6>Clique no botão abaixo e escolha um arquivo.</h6>
                            <input type="file" id="input-file-now" name="select_file" accept=".xls,.xlsx,.csv" />
                        </div>
                    </div>
                    <!--Fim div importação -->
                </div>
                <div class="modal-footer" style="margin-top: -30px;">
                    <a type="button" id="btnsubmit" onClick="envia();"
                        class="modal-action waves-effect waves-green btn-flat"
                        style="background-color: green;color:white; margin-right: -3px; margin-top: 10px;"><i
                            class="material-icons left">save_alt</i>Salvar</a>
                </div>
        </div>
        <!--Fim Modal -->

@endsection

@section('scripts')

        <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
        <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
        <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>
        <script src="{{ asset('/public/materialize/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('/public/materialize/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('/public/materialize/js/dropify.min.js') }}"></script>
        <script src="{{ asset('/public/materialize/js/app-invoice.min.js') }}"></script>
        <script src="{{ asset('/public/materialize/js/select2.full.min.js') }}"></script>
        <script src="{{ asset('/public/materialize/js/form-select2.min.js') }}"></script>

        <script
            src="https://pixinvent.com/materialize-material-design-admin-template/app-assets/js/scripts/form-file-uploads.min.js">
        </script>
        <script type='text/javascript'
            src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>


        <script>
        document.addEventListener("DOMContentLoaded", function() {
            $('.modal').modal();
            $("#manualmentediv").hide();
            $("#importacaodiv").hide();
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
        function manualmente() {

            $("#manualmentediv").show();
            $("#importacaodiv").hide();
            $("#opcao").val("manualmente");


        }
        </script>

        <script>
        function importacao() {

            $("#manualmentediv").hide();
            $("#importacaodiv").show();
            $("#opcao").val("importacao");
        }
        </script>

        <script>
        function envia() {

            document.getElementById("loadingdiv").style.display = "";
            document.getElementById("corpodiv").style.display = "none";
            document.getElementById("form").submit();
        }
        </script>

@endsection