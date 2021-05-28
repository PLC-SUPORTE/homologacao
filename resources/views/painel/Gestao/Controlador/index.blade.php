@extends('painel.Layout.header')
<?php $search = 2 ?>
@section('title') Dashboard Gestão @endsection
<!-- Titulo da pagina -->

@section('header')
<link rel="apple-touch-icon"
    href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/images/favicon/apple-touch-icon-152x152.png">
<link rel="shortcut icon" href="{{ asset('/public/assets/imgs/casinha.png')}}" />
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('/public/materialize/css/materialize.min.css') }}">
<link rel="stylesheet" href="{{ asset('/public/materialize/css/style.min.css') }}">
<link rel="stylesheet" href="{{ asset('/public/materialize/css/custom.css') }}">

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
Dashboard
@endsection
@section('submenu')
<li class="breadcrumb-item active" style="color: black;">Dashboard
</li>
@endsection

@section('body')
    <div>
        <div class="row">

            <div class="row">
                <div class="content-wrapper-before"></div>
                <div class="col-s12">
                    <div class="container">
                        <!--Informativo RV -->
                        <div class="col s4 m3 l3 xl3">
                            <div class="card min-height-100 animate fadeLeft">
                                <div class="padding-4">
                                    <div class="row">
                                        <h6>&nbsp;&nbsp;Informativos de RV</h6>
                                        <div class="container" style="margin-left: 5px;">
                                            <li data-menu=""><a
                                                    href="{{ route('Painel.Gestao.Controlador.CartaRV.index') }}"><span
                                                        data-i18n="Mês de apuração">Mês de apuração</span></a></li>
                                            <li data-menu=""><a
                                                    href="{{ route('Painel.Gestao.Controlador.CartaRV.historico') }}"><span
                                                        data-i18n="Histórico">Histórico</span></a></li>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--Informativo RV -->

                        <!--Notas Socios -->
                        <div class="col s4 m3 l3 xl3">
                            <div class="card min-height-100 animate fadeLeft">
                                <div class="padding-4">
                                    <div class="row">
                                        <h6>&nbsp;&nbsp;Notas Sócios</h6>
                                        <div class="container" style="margin-left: 5px;">
                                            <li data-menu=""><a
                                                    href="{{ route('Painel.Gestao.Controlador.NotasAdvogado.index') }}"><span
                                                        data-i18n="Mês de apuração">Mês de apuração</span></a></li>
                                            <li data-menu=""><a
                                                    href="{{ route('Painel.Gestao.Controlador.NotasAdvogado.historico') }}"><span
                                                        data-i18n="Histórico">Histórico</span></a></li>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--Notas Socios -->


                        <!--Notas Consolidadas -->
                        <div class="col s4 m3 l3 xl3">
                            <div class="card min-height-100 animate fadeLeft">
                                <div class="padding-4">
                                    <div class="row">
                                        <h6>&nbsp;&nbsp;Notas Consolidadas</h6>
                                        <div class="container" style="margin-left: 5px;">
                                            <li data-menu=""><a
                                                    href="{{ route('Painel.Gestao.Controlador.NotasConsolidada.index') }}"><span
                                                        data-i18n="Mês de apuração">Mês de apuração</span></a></li>
                                            <li data-menu=""><a
                                                    href="{{ route('Painel.Gestao.Controlador.NotasConsolidada.historico') }}"><span
                                                        data-i18n="Histórico">Histórico</span></a></li>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--Notas Consolidadas -->

                        <!--Revisão Hierarquia -->
                        <div class="col s4 m3 l3 xl3">
                            <div class="card min-height-100 animate fadeLeft">
                                <div class="padding-4">
                                    <div class="row">
                                        <h6>&nbsp;&nbsp;Revisão Hierarquia</h6>
                                        <div class="container" style="margin-left: 5px;">
                                            <li data-menu=""><a
                                                    href="{{ route('Painel.Gestao.Controlador.Hierarquia.index') }}"><span
                                                        data-i18n="Mês de apuração">Responsáveis</span></a></li>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Contratos -->
                </div>
            </div>
        </div>




        <div class="row">
            <div class="content-wrapper-before"></div>
            <div class="col-s12">
                <div class="container">
                    <div class="col s4 m3 l3 xl3">
                        <div class="card min-height-100 animate fadeLeft">
                            <div class="padding-4">
                                <div class="row">
                                    <h6>&nbsp;&nbsp;Metas</h6>
                                    <div class="container" style="margin-left: 5px;">
                                        <li data-menu=""><a
                                                href="{{ route('Painel.Gestao.Controlador.Metas.index') }}"><span
                                                    data-i18n="Mês de apuração">Listagem de metas</span></a></li>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Metas -->


                    <!--valor orçado setor -->
                    <div class="col s4 m3 l3 xl3">
                        <div class="card min-height-100 animate fadeLeft">
                            <div class="padding-4">
                                <div class="row">
                                    <h6>&nbsp;&nbsp;Valor orçado setor</h6>
                                    <div class="container" style="margin-left: 5px;">
                                        <li data-menu=""><a
                                                href="{{ route('Painel.Gestao.Controlador.ValorOrcado.index') }}"><span
                                                    data-i18n="Valor anual">Valor anual</span></a></li>
                                        <li data-menu=""><a
                                                href="{{ route('Painel.Gestao.Controlador.ValorOrcado.Mensal.index') }}"><span
                                                    data-i18n="Valor anual">Valor mensal</span></a></li>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--valor orçado setor -->

                    <!--Objetivos -->
                    <div class="col s4 m3 l3 xl3">
                        <div class="card min-height-100 animate fadeLeft">
                            <div class="padding-4">
                                <div class="row">
                                    <h6>&nbsp;&nbsp;Objetivos</h6>
                                    <div class="container" style="margin-left: 5px;">
                                        <li data-menu=""><a
                                                href="{{ route('Painel.Gestao.Controlador.Objetivos.index') }}"><span
                                                    data-i18n="Valor anual">Listagem dos objetivos</span></a></li>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Objetivos -->


                    <!--Contratos -->
                    <div class="col s4 m3 l3 xl3">
                        <div class="card min-height-100 animate fadeLeft">
                            <div class="padding-4">
                                <div class="row">
                                    <h6>&nbsp;&nbsp;Contratos</h6>
                                    <div class="container" style="margin-left: 5px;">
                                        <li data-menu=""><a
                                                href="{{ route('Painel.Gestao.Controlador.Contrato.index') }}"><span
                                                    data-i18n="Listagem dos tipos de contrato">Listagem dos tipos de
                                                    contrato</span></a></li>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Contratos -->
                </div>
            </div>
        </div>

        <div class="row">
            <div class="content-wrapper-before"></div>
            <div class="col-s12">
                <div class="container">
                    <!--Usuários -->
                    <div class="col s4 m3 l3 xl3">
                        <div class="card min-height-100 animate fadeLeft">
                            <div class="padding-4">
                                <div class="row">
                                    <h6>&nbsp;&nbsp;Usuários</h6>
                                    <div class="container" style="margin-left: 5px;">
                                        <li data-menu=""><a
                                                href="{{ route('Painel.Gestao.Controlador.Usuarios.index') }}"><span
                                                    data-i18n="Listagem de usuarios">Listagem de usuários</span></a>
                                        </li>
                                        <li data-menu=""><a
                                                href="{{ route('Painel.Gestao.Controlador.Usuarios.Setores.index') }}"><span
                                                    data-i18n="Listagem de usuarios">Relacionar usuário ao setor de
                                                    custo</span></a></li>
                                        <li data-menu=""><a
                                                href="{{ route('Painel.Gestao.Controlador.Usuarios.Nivel.index') }}"><span
                                                    data-i18n="Relacionar usuário ao nível">Relacionar usuário ao
                                                    nível</span></a></li>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Usuários -->


                    <!--Comandos -->
                    <div class="col s4 m3 l3 xl3">
                        <div class="card min-height-100 animate fadeLeft">
                            <div class="padding-4">
                                <div class="row">
                                    <h6>&nbsp;&nbsp;Comandos</h6>
                                    <div class="container" style="margin-left: 5px;">
                                        <li data-menu=""><a class="modal-trigger" href="#modalnotaconsolidada"><span
                                                    data-i18n="Executar nota consolidada">Executar nota
                                                    consolidada</span></a></li>
                                        <li data-menu=""><a class="modal-trigger" href="#modalmediascore"><span
                                                    data-i18n="Executar media score">Executar media score</span></a>
                                        </li>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Comandos -->

                </div>
            </div>
        </div>


        <!--Revisão Hierarquia -->

        <!--Metas -->





    </div>



    </div>
    <div class="content-overlay"></div>
    </div>
    </div>
    </div>


    <!--Modal perguntando -->
    <div id="modalmediascore" class="modal">
        <form id="form" role="form" action="{{ route('Painel.Gestao.Controlador.Procedure.mediascore') }}" method="POST"
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
                        <h1 style="text-align: center;">Aguarde, estamos executando o comando...&hellip;</h1>
                    </div>
                </center>

                <div id="corpodiv">
                    <h4>Média score</h4>
                    <p>Deseja executar comando no banco de dados para o média score ?</p>

                </div>
                <div class="modal-footer">
                    <a type="button" id="btnsubmit" onClick="envia();"
                        class="modal-action waves-effect waves-green btn-flat"
                        style="background-color: green;color:white;"><i
                            class="material-icons left">check</i>Prosseguir</a>
                </div>
            </div>
        </form>
    </div>
    <!--Fim Modal -->

    <!--Modal perguntando -->
    <div id="modalnotaconsolidada" class="modal">
        <form id="form2" role="form" action="{{ route('Painel.Gestao.Controlador.Procedure.notaconsolidada') }}"
            method="POST" role="create">
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
                        <h1 style="text-align: center;">Aguarde, estamos executando o comando...&hellip;</h1>
                    </div>
                </center>

                <div id="corpodiv">
                    <h4>Nota consolidada</h4>
                    <p>Deseja executar comando no banco de dados para o nota consolidada ?</p>

                </div>
                <div class="modal-footer">
                    <a type="button" id="btnsubmit" onClick="envia2();"
                        class="modal-action waves-effect waves-green btn-flat"
                        style="background-color: green;color:white;"><i
                            class="material-icons left">check</i>Prosseguir</a>
                </div>
            </div>
        </form>
    </div>
    <!--Fim Modal -->

@endsection

    <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/vendors.min.js') }}"></script>


    <script>
    document.addEventListener("DOMContentLoaded", function() {
        $('.modal').modal();
    });
    </script>



    <script>
    function envia() {

        document.getElementById("loadingdiv").style.display = "";
        document.getElementById("corpodiv").style.display = "none";
        document.getElementById("form").submit();
    }
    </script>

    <script>
    function envia2() {

        document.getElementById("loadingdiv").style.display = "";
        document.getElementById("corpodiv").style.display = "none";
        document.getElementById("form2").submit();
    }
    </script>
