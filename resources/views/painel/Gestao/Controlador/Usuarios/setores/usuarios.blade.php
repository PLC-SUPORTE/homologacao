@extends('painel.Layout.header')
<?php $search = 2 ?>
@section('title') Usuários relacionados @endsection
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
<link rel="stylesheet" href="{{ asset('/public/materialize/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('/public/materialize/css/select2-materialize.css') }}">
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
<li class="breadcrumb-item"><a href="{{ route('Painel.Gestao.Controlador.Usuarios.Setores.index') }}">Setores</a>
</li>
<li class="breadcrumb-item active" style="color: black;">Usuários relacionados
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
                            <a href="#modal"
                                class="btn waves-effect waves-light invoice-export border-round z-depth-4 modal-trigger tooltipped"
                                data-position="bottom"
                                data-tooltip="Clique aqui para adicionar um novo sócio a este setor de custo."
                                style="background-color: gray">
                                <i class="material-icons">add</i>
                                <span class="hide-on-small-only">Novo relacionamento</span>
                            </a>
                        </div>


                        <div class="responsive-table">
                            <table class="table invoice-data-table white border-radius-4 pt-1">
                                <thead>
                                    <tr>
                                        <th style="font-size: 12px"></th>
                                        <th style="font-size: 12px">#</th>
                                        <th style="font-size: 12px">Usuário</th>
                                        <th style="font-size: 12px">CPF</th>
                                        <th style="font-size: 12px">E-mail</th>
                                        <th style="font-size: 12px">Setor</th>
                                        <th style="font-size: 12px">Setor descrição</th>
                                        <th style="font-size: 12px">Ativo</th>
                                        <th style="font-size: 12px"></th>
                                        <th style="font-size: 12px"></th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($datas as $data)
                                    <tr>

                                        <!-- modal -->
                                        <div id="modal{{$data->usuario_cpf}}" class="modal"
                                            style="width: 1047px;height: 290px;">
                                            <form id="form2" role="form"
                                                action="{{ route('Painel.Gestao.Controlador.Usuarios.Setores.relacionamentocancelado') }}"
                                                method="POST" role="create" enctype="multipart/form-data">
                                                {{ csrf_field() }}

                                                <div class="modal-content">
                                                    <div class="row">
                                                        <div class="col s12 m12">


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
                                                                    style="margin-left: 920px;">
                                                                    <i class="material-icons">close</i>
                                                                </button>

                                                                <div class="row">
                                                                    <div class="col s12">
                                                                        <h5 class="mb-2">Deseja desativar este
                                                                            relacionamento ?</h5>
                                                                    </div>

                                                                    <div class="col s4 input-field">
                                                                        <input class="validate"
                                                                            value="{{mb_convert_case($data->usuario_nome, MB_CASE_TITLE, 'UTF-8')}}"
                                                                            name="usuario_nome" readonly type="text">
                                                                        <label>Sócio:</label>
                                                                    </div>

                                                                    <div class="col s2 input-field">
                                                                        <input class="validate"
                                                                            value="{{$data->usuario_cpf}}"
                                                                            name="usuario_cpf" readonly type="text">
                                                                        <label>Sócio CPF:</label>
                                                                    </div>

                                                                    <div class="col s2 input-field">
                                                                        <input class="validate" value="{{$data->Setor}}"
                                                                            name="setor" readonly type="text">
                                                                        <label>Setor código:</label>
                                                                    </div>

                                                                    <div class="col s3 input-field">
                                                                        <input class="validate"
                                                                            value="{{$data->SetorDescricao}}" readonly
                                                                            type="text">
                                                                        <label>Setor descrição:</label>
                                                                    </div>

                                                                </div>

                                                                <div class="modal-footer">
                                                                    <button type="submit" id="btnsubmit2"
                                                                        onClick="envia2();"
                                                                        class="modal-action waves-effect waves-green btn-flat"
                                                                        style="background-color: gray;color:white;"><i
                                                                            class="material-icons left">save_alt</i>Atualizar</button>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>

                                            </form>
                                        </div>
                        </div>
                        <!-- modal -->


                        <!--Modal para adicionar novamente o relacionamento -->
                        <div id="ativar{{$data->usuario_cpf}}" class="modal" style="width: 1047px;height: 290px;">
                            <form id="form3" role="form"
                                action="{{ route('Painel.Gestao.Controlador.Usuarios.Setores.relacionamentoativo') }}"
                                method="POST" role="create" enctype="multipart/form-data">
                                {{ csrf_field() }}

                                <div class="modal-content">
                                    <div class="row">
                                        <div class="col s12 m12">


                                            <center>
                                                <div id="loadingenvia2" style="display:none">
                                                    <div class="wrapper">
                                                        <div class="circle circle-1"></div>
                                                        <div class="circle circle-1a"></div>
                                                        <div class="circle circle-2"></div>
                                                        <div class="circle circle-3"></div>
                                                    </div>
                                                    <h1 style="text-align: center;">Aguarde, estamos atualizando o
                                                        registro...&hellip;</h1>
                                                </div>
                                            </center>

                                            <div id="corpodiv3">

                                                <button type="button"
                                                    class="btn waves-effect mr-sm-1 mr-2 modal-close red"
                                                    style="margin-left: 920px;">
                                                    <i class="material-icons">close</i>
                                                </button>

                                                <div class="row">
                                                    <div class="col s12">
                                                        <h5 class="mb-2">Deseja ativar novamente este relacionamento ?
                                                        </h5>
                                                    </div>

                                                    <div class="col s4 input-field">
                                                        <input class="validate"
                                                            value="{{mb_convert_case($data->usuario_nome, MB_CASE_TITLE, 'UTF-8')}}"
                                                            name="usuario_nome" readonly type="text">
                                                        <label>Sócio:</label>
                                                    </div>

                                                    <div class="col s2 input-field">
                                                        <input class="validate" value="{{$data->usuario_cpf}}"
                                                            name="usuario_cpf" readonly type="text">
                                                        <label>Sócio CPF:</label>
                                                    </div>

                                                    <div class="col s2 input-field">
                                                        <input class="validate" value="{{$data->Setor}}" name="setor"
                                                            readonly type="text">
                                                        <label>Setor código:</label>
                                                    </div>

                                                    <div class="col s3 input-field">
                                                        <input class="validate" value="{{$data->SetorDescricao}}"
                                                            readonly type="text">
                                                        <label>Setor descrição:</label>
                                                    </div>

                                                </div>

                                                <div class="modal-footer">
                                                    <button type="submit" id="btnsubmit3" onClick="ativa();"
                                                        class="modal-action waves-effect waves-green btn-flat"
                                                        style="background-color: gray;color:white;"><i
                                                            class="material-icons left">save_alt</i>Atualizar</button>
                                                </div>

                                            </div>
                                        </div>

                                    </div>

                            </form>
                        </div>
                </div>
                <!--Fim do Modal -->


                <td style="font-size: 11px"></td>
                <td style="font-size: 11px"></td>
                <td style="font-size: 11px">{{$data->usuario_nome}}</td>
                <td style="font-size: 11px">{{$data->usuario_cpf}}</td>
                <td style="font-size: 11px">{{$data->usuario_email}}</td>
                <td style="font-size: 11px">{{$data->Setor}}</td>
                <td style="font-size: 11px">{{$data->SetorDescricao}}</td>

                @if($data->ativo == "S")
                <td style="font-size: 11px"><span class="bullet green"></span> Ativo</td>
                @else
                <td style="font-size: 11px"><span class="bullet red"></span> Ínativo</td>
                @endif

                <td style="font-size: 11px">

                    <div class="invoice-action">

                        @if($data->ativo == "S")
                        <a href="#modal{{$data->usuario_cpf}}" class="invoice-action-view mr-4 modal-trigger tooltipped"
                            data-position="bottom" data-tooltip="Clique aqui para desativar este relacionamento."><i
                                class="material-icons">lock</i></a>
                        @else
                        <a href="#ativar{{$data->usuario_cpf}}"
                            class="invoice-action-view mr-4 modal-trigger tooltipped" data-position="bottom"
                            data-tooltip="Clique aqui para ativar este relacionamento."><i
                                class="material-icons">lock_open</i></a>
                        @endif
                    </div>

                </td>
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


    <!--Modal perguntando -->
    <div id="modal" class="modal" style="width: 1200px;">
        <form id="form" role="form"
            action="{{ route('Painel.Gestao.Controlador.Usuarios.Setores.relacionamentocriado') }}" method="POST"
            role="create">
            {{ csrf_field() }}


            <div class="modal-content">

                <center>
                    <div id="loadingdiv" style="display:none">
                        <div class="wrapper">
                            <div class="circle circle-1"></div>
                            <div class="circle circle-1a"></div>
                            <div class="circle circle-2"></div>
                            <div class="circle circle-3"></div>
                        </div>
                        <h1 style="text-align: center;">Aguarde, estamos relacionando o usuário ao setor de
                            custo...&hellip;</h1>
                    </div>
                </center>

                <div id="corpodiv">
                    <a class="waves-effect modal-close waves-light btn red right align"
                        style="margin-top: -20px; margin-right: -20px;"><i style="margin-left: 15px; font-size: 20px;"
                            class="material-icons left">close</i></a>
                    <h4>Novo relacionamento</h4>

                    <br>

                    <div class="row">

                        <div class="input-field col s3">
                            <span>Setor de custo</span>
                            <input type="text" readonly value="{{$setor_codigo}} - {{$setor_descricao}}">
                            <input type="hidden" value="{{$setor_codigo}}" name="setor">
                        </div>

                        <div class="input-field col s4">
                            <span>Selecione o usuário</span>
                            <select class="select2 browser-default" name="usuario" required>
                                @foreach($usuarios as $usuario)
                                <option value="{{$usuario->id}}">{{$usuario->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="input-field col s3">
                            <span>Selecione o status</span>
                            <select class="select2 browser-default" name="ativo" required>
                                <option value="S" selected>SIM</option>
                                <option value="N">NÃO</option>
                            </select>
                        </div>


                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="submit" id="btnsubmit2" onClick="envia();"
                    class="modal-action waves-effect waves-green btn-flat"
                    style="background-color: gray;color:white;"><i
                        class="material-icons left">save_alt</i>Salvar</button>
            </div>


    </div>
    </form>
    </div>
    <!--Fim Modal -->


@endsection
@section('scripts')

    <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/app-invoice.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/form-select2.min.js') }}"></script>


    <script>
    function envia() {

        document.getElementById("loadingdiv").style.display = "";
        document.getElementById("corpodiv").style.display = "none";
        document.getElementById("form").submit();
    }
    </script>

    <script>
    $(document).ready(function() {
        $('.modal').modal();
    });
    </script>

    <script>
    function envia2() {

        document.getElementById("loadingenvia").style.display = "";
        document.getElementById("corpodiv2").style.display = "none";
        document.getElementById("form2").submit();
    }
    </script>

    <script>
    function envia3() {

        document.getElementById("loadingenvia2").style.display = "";
        document.getElementById("corpodiv3").style.display = "none";
        document.getElementById("form3").submit();
    }
    </script>

@endsection