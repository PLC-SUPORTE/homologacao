@extends('painel.Layout.header')
<?php $search = 2 ?>
@section('title') Nível index @endsection
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
<li class="breadcrumb-item active" style="color: black;">Relacionar usuário ao nível
</li>
@endsection
@section('body')
    <div>
        <div class="row">

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

                                <a href="{{ route('Painel.Gestao.Controlador.Usuarios.Nivel.adicionar') }}"
                                    class="btn waves-effect waves-light invoice-export border-round z-depth-4"
                                    style="background-color: gray">
                                    <i class="material-icons">add</i>
                                    <span class="hide-on-small-only">Adicionar</span>
                                </a>

                            </div>


                            <div class="responsive-table">
                                <table class="table invoice-data-table white border-radius-4 pt-1">
                                    <thead>
                                        <tr>
                                            <th style="font-size: 12px"></th>
                                            <th style="font-size: 12px">Nome</th>
                                            <th style="font-size: 12px">E-mail</th>
                                            <th style="font-size: 12px">CPF</th>
                                            <th style="font-size: 12px">Unidade</th>
                                            <th style="font-size: 12px">Setor</th>
                                            <th style="font-size: 12px">Nível</th>
                                            <th style="font-size: 12px">Data Início</th>
                                            <th style="font-size: 12px"></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($datas as $data)
                                        <tr>


                                            <!--Modal Editar -->
                                            <div id="editar{{$data->id}}" class="modal" style="width: 1207px;">
                                                <form id="form2" role="form"
                                                    action="{{ route('Painel.Gestao.Controlador.Usuarios.Nivel.atualizaregistro') }}"
                                                    method="POST" role="create" enctype="multipart/form-data">
                                                    {{ csrf_field() }}

                                                    <input type="hidden" name="id" id="id" value="{{$data->id}}">

                                                    <div class="modal-content">

                                                        <center>
                                                            <div id="loadingenvia" style="display:none">
                                                                <div class="wrapper">
                                                                    <div class="circle circle-1"></div>
                                                                    <div class="circle circle-1a"></div>
                                                                    <div class="circle circle-2"></div>
                                                                    <div class="circle circle-3"></div>
                                                                </div>
                                                                <h1 style="text-align: center;">Aguarde, estamos
                                                                    atualizando o registro...&hellip;</h1>
                                                            </div>
                                                        </center>

                                                        <div id="corpodiv2">

                                                            <button type="button"
                                                                class="btn waves-effect mr-sm-1 mr-2 modal-close red"
                                                                style="margin-top: -26px; margin-left: 1080px;position: fixed;"><i
                                                                    class="material-icons">close</i></button>

                                                            <h5>Atualizar registro</h5>
                                                            <br>

                                                            <div class="input-field col s3">
                                                                <span>Sócio:</span>
                                                                <input readonly id="plc_porcent" type="text"
                                                                    name="socionome"
                                                                    value="{{mb_convert_case($data->usuario_nome, MB_CASE_TITLE, 'UTF-8')}}"
                                                                    class="validate">
                                                            </div>

                                                            <div class="input-field col s2">
                                                                <span>Setor:</span>
                                                                <input id="icon_telephone" readonly type="text"
                                                                    name="setor" value="{{$data->setor}}"
                                                                    class="validate">
                                                            </div>

                                                            <div class="input-field col s2">
                                                                <span>Data ínicio:</span>
                                                                <input id="icon_telephone" readonly type="text"
                                                                    value="{{ date('d/m/Y', strtotime($data->datainicio)) }}"
                                                                    name="datainicio" class="validate">
                                                            </div>

                                                            <div class="input-field col s2">
                                                                <span>Data fim:</span>
                                                                <input id="icon_telephone" type="date" name="datafim"
                                                                    class="validate">
                                                            </div>

                                                            <div class="input-field col s3">
                                                                <span>Nível:</span>
                                                                <select class="select2 browser-default" id="nivel"
                                                                    name="nivel">
                                                                    @if($data->nivel == "Advogado")
                                                                    <option value="{{$data->nivel}}" selected>
                                                                        {{$data->nivel}}</option>
                                                                    <option value="Advogado Controladoria">Advogado
                                                                        Controladoria</option>
                                                                    <option value="Advogado ControladoriaSP">Advogado
                                                                        ControladoriaSP</option>
                                                                    <option value="COO">COO</option>
                                                                    <option value="Coordenador">Coordenador</option>
                                                                    <option value="Coordenador Controladoria">
                                                                        Coordenador Controladoria</option>
                                                                    <option value="Coordenador ControladoriaSP">
                                                                        Coordenador ControladoriaSP</option>
                                                                    <option value="Gerente">Gerente</option>
                                                                    <option value="Gerente Equipe Passiva">Gerente
                                                                        Equipe Passiva</option>
                                                                    <option value="Superintendente">Superintendente
                                                                    </option>
                                                                    <option value="Subcoordenador 1">Subcoordenador 1
                                                                    </option>
                                                                    <option value="Subcoordenador 2">Subcoordenador 2
                                                                    </option>
                                                                    @else
                                                                    <option value="{{$data->nivel}}" selected>
                                                                        {{$data->nivel}}</option>
                                                                    <option value="Advogado">Advogado</option>
                                                                    <option value="Advogado Controladoria">Advogado
                                                                        Controladoria</option>
                                                                    <option value="Advogado ControladoriaSP">Advogado
                                                                        ControladoriaSP</option>
                                                                    <option value="COO">COO</option>
                                                                    <option value="Coordenador">Coordenador</option>
                                                                    <option value="Coordenador Controladoria">
                                                                        Coordenador Controladoria</option>
                                                                    <option value="Coordenador ControladoriaSP">
                                                                        Coordenador ControladoriaSP</option>
                                                                    <option value="Gerente">Gerente</option>
                                                                    <option value="Gerente Equipe Passiva">Gerente
                                                                        Equipe Passiva</option>
                                                                    <option value="Superintendente">Superintendente
                                                                    </option>
                                                                    <option value="Subcoordenador 1">Subcoordenador 1
                                                                    </option>
                                                                    <option value="Subcoordenador 2">Subcoordenador 2
                                                                    </option>
                                                                    @endif
                                                                </select>
                                                            </div>



                                                        </div>

                                                        <div class="modal-footer" style="margin-top: 90px;">
                                                            <button type="submit" id="btnsubmit" onClick="envia();"
                                                                class="modal-action waves-effect waves-green btn-flat"
                                                                style="background-color: gray;color:white;"><i
                                                                    class="material-icons left">save_alt</i>Atualizar</button>
                                                        </div>

                                                    </div>
                                                </form>
                                            </div>
                                            <!--Fim Modal Editar -->


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

                                            <td style="font-size: 11px">
                                                <div class="invoice-action">
                                                    <a href="#editar{{$data->id}}"
                                                        class="invoice-action-view mr-4 tooltipped modal-trigger"
                                                        data-position="bottom"
                                                        data-tooltip="Clique aqui para editar este registro."><i
                                                            class="material-icons">edit</i></a>
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
        <script src="{{ asset('/public/materialize/js/app-invoice.min.js') }}"></script>


        <script>
        function envia() {

            document.getElementById("loadingenvia").style.display = "";
            document.getElementById("corpodiv2").style.display = "none";
            document.getElementById("form2").submit();
        }
        </script>

        <script>
        $(document).ready(function() {
            $('.modal').modal();

        });
        </script>
@endsection