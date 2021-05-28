@extends('painel.Layout.header')
<?php $search = 2 ?>
@section('title') Revisar solicitação de adiantamento @endsection
<!-- Titulo da pagina -->

@section('header')
<link rel="apple-touch-icon"
    href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/images/favicon/apple-touch-icon-152x152.png">
<link rel="shortcut icon" href="{{ asset('/public/assets/imgs/casinha.png')}}" />
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('/public/materialize/css/materialize.min.css') }}">
<link rel="stylesheet" href="{{ asset('/public/materialize/css/style.min.css') }}">
<link rel="stylesheet" href="{{ asset('/public/materialize/css/custom.css') }}">
<link rel="stylesheet" href="{{ asset('/public/materialize/css/app-file-manager.min.css') }}">
@endsection
@section('header_title')
Pesquisa patrimonial
@endsection
@section('submenu')
<li class="breadcrumb-item"><a href="{{ route('Painel.PesquisaPatrimonial.supervisao.index') }}">Dashboard</a>
</li>
<li class="breadcrumb-item active" style="color: black;">Revisão de solicitação de adiantamento
</li>
@endsection
@section('body')
        <div>
            <div class="row">
                <div class="col s12">
                    <div class="container" style="margin-top: -20px;">
                        <div class="section app-file-manager-wrapper">
                            <div class="app-file-overlay"></div>
                            <div class="col s12 m12 l12 animate fadeRight">
                                <div class="app-file-area">
                                    <div class="app-file-header">

                                        <div class="sidebar-toggle show-on-medium-and-down mr-1 ml-1">
                                            <i class="material-icons">menu</i>
                                        </div>
                                    </div>


                                    <form role="form" onsubmit="btnsubmit.disabled = true; return true;"
                                        action="{{ route('Painel.PesquisaPatrimonial.supervisao.avaliada') }}"
                                        method="POST" role="search" enctype="multipart/form-data"> {{ csrf_field() }}
                                        <input type="hidden" name="id_matrix" value="{{$datas->ID}}" id="id_matrix">
                                        <input type="hidden" name="solicitanteid" value="{{$datas->SolicitanteID}}"
                                            id="solicitanteid">
                                        <input type="hidden" name="solicitantecpf" value="{{$datas->SolicitanteCPF}}"
                                            id="solicitantecpf">
                                        <input type="hidden" name="solicitanteemail"
                                            value="{{$datas->SolicitanteEmail}}" id="solicitanteemail">
                                        <input type="hidden" id="clienterazao" name="clienterazao"
                                            value="{{$datas->ClienteRazao}}">
                                        <input type="hidden" name="clientecodigo" id="clientecodigo"
                                            value="{{$datas->CodigoCliente}}">
                                        <input type="hidden" id="clientecep" name="clientecep"
                                            value="{{$datas->ClienteCEP}}">
                                        <input type="hidden" id="clienteendereco" name="clienteendereco"
                                            value="{{$datas->ClienteEndereco}}">
                                        <input type="hidden" id="clientebairro" name="clientebairro"
                                            value="{{$datas->ClienteBairro}}">
                                        <input type="hidden" id="clienteuf" name="clienteuf"
                                            value="{{$datas->ClienteUF}}">
                                        <input type="hidden" id="clientecidade" name="clientecidade"
                                            value="{{$datas->ClienteCidade}}">
                                        <input type="hidden" id="unidaderazao" name="unidaderazao"
                                            value="{{$datas->UnidadeRazao}}">
                                        <input type="hidden" id="unidadecnpj" name="unidadecnpj"
                                            value="{{$datas->UnidadeCNPJ}}">
                                        <input type="hidden" id="unidadeendereco" name="unidadeendereco"
                                            value="{{$datas->UnidadeEndereco}}">
                                        <input type="hidden" id="unidadebairro" name="unidadebairro"
                                            value="{{$datas->UnidadeBairro}}">
                                        <input type="hidden" id="unidadecidade" name="unidadecidade"
                                            value="{{$datas->UnidadeCidade}}">
                                        <input type="hidden" id="unidadeuf" name="unidadeuf"
                                            value="{{$datas->UnidadeUF}}">
                                        <input type="hidden" id="unidadecep" name="unidadecep"
                                            value="{{$datas->UnidadeCEP}}">
                                        <input type="hidden" name="unidade" id="unidade"
                                            value="{{$datas->UnidadeDescricao}}" class="form-control">
                                        <input type="hidden" id="unidadetelefone" name="unidadetelefone"
                                            value="{{$datas->UnidadeTelefone}}">
                                        <input type="hidden" name="grupocliente_codigo" id="grupocliente_codigo"
                                            value="{{$datas->GrupoClienteCodigo}}">
                                        <input type="hidden" name="numeroprocesso" id="numeroprocesso"
                                            value="{{$datas->NumeroProcesso}}" class="form-control">
                                        <input type="hidden" name="unidadedescricao" id="unidadedescricao"
                                            value="{{$datas->UnidadeDescricao}}" class="form-control">
                                        <input ID="outraparte" name="outraparte" type="hidden"
                                            value="{{$datas->OutraParte}}" />
                                        <input ID="codigo" name="codigo" value="{{$datas->Codigo}}" readonly=""
                                            type="hidden" />
                                        <input readonly="" name="solicitantenome" id="solicitante"
                                            value="{{$datas->SolicitanteNome}}" type="hidden" />
                                        <input readonly="" name="cliente" id="cliente"
                                            value="{{$datas->ClienteFantasia}}" type="hidden" />
                                        <input readonly="" name="grupocliente" id="grupocliente"
                                            value="{{$datas->GrupoCliente}}" type="hidden" />
                                        <input type="hidden" readonly="" name="emailcliente" id="emailcliente"
                                            value="{{$datas->EmailCliente}}" />
                                        <input type="hidden" readonly="" name="tipo" id="tipo"
                                            value="{{$datas->Tipo}}" />
                                        <input type="hidden" readonly="" name="tiposolicitacao" id="tiposolicitacao"
                                            value="{{$datas->TipoSolicitacao}}" />
                                        <input type="hidden" readonly="" name="tiposervico" id="tiposervico"
                                            value="{{$datas->TipoServico}}" />
                                        <input type="hidden" readonly="" name="codigopasta" id="codigopasta"
                                            value="{{$datas->Pasta}}" />
                                        <input type="hidden" readonly="" name="setor" id="setor"
                                            value="{{$datas->Setor}}" />
                                        <input type="hidden" readonly="" name="contrato" id="contrato"
                                            value="{{$datas->Contrato}}" />
                                        <div class="app-file-content ps">

                                            <a class="btn-floating btn-mini waves-effect waves-light  right align tooltipped modal-trigger"
                                                href="#modalPagamentos" onclick="abreModalPagamentos();"
                                                data-position="left"
                                                data-tooltip="Clique aqui para visualizar os dados de pagamento desta solicitação de pesquisa patrimonial."
                                                style="background-color: gray;"><i
                                                    class="material-icons">attach_money</i></a>
                                            <a class="btn-floating btn-mini waves-effect waves-light  right align tooltipped modal-trigger"
                                                href="#modalDados" onclick="abreModalDados();" data-position="bottom"
                                                data-tooltip="Clique aqui para visualizar os dados cadastrais desta solicitação de pesquisa patrimonial."
                                                style="background-color: gray;"><i class="material-icons">search</i></a>



                                            <div class="app-file-content ps">
                                                <h6 class="font-weight-700 mb-3" style="margin-top: 2%;font-size:11px;">
                                                    Pagamento</h6>
                                                <div class="row">

                                                    <div class="input-field col s2">
                                                        <input style="font-size:10px;" ID="cobravel" name="cobravel"
                                                            required readonly="" value="{{$datas->Cobravel}}"
                                                            type="text">
                                                        <label for="cobravel"
                                                            style="color: black;font-weight: bold;font-size:11px;">Solicitação
                                                            é Cobrável?</label>
                                                    </div>

                                                    <div class="input-field col s2">
                                                        <input style="font-size: 10px;" type="text" readonly=""
                                                            name="valortotal" id="valortotal"
                                                            value="{{ $datas->Valor}}">
                                                        <label style="font-size: 11px;" for="valortotal"
                                                            style="color: black;font-weight: bold">Valor Total
                                                            Solicitação</label>
                                                    </div>

                                                    @if($datas->Cobravel == "SIM")
                                                    <div class="input-field col s2">
                                                        <label for="nome"
                                                            style="margin-top: -27px; font-size: 11px;color: black;font-weight: bold">Solicitar
                                                            Adiamento?</label>
                                                        <p>
                                                            <label>
                                                                <input style="font-size: 10px;" class="with-gap"
                                                                    type="radio" id="adiantamentosim"
                                                                    name="adiantamento" checked value="SIM" />
                                                                <span style="font-size: 10px;">Sim</span>
                                                            </label>
                                                            <label>
                                                                <input style="font-size: 10px;" class="with-gap"
                                                                    type="radio" name="adiantamento"
                                                                    id="adiantamentonao" value="NAO" />
                                                                <span style="font-size: 10px;">Não</span>
                                                            </label>
                                                        </p>
                                                    </div>
                                                    @else
                                                </div>
                                                @endif


                                            </div>
                                        </div>

                                        <div class="input-field" style="margin-left: 700px;">
                                            <button class="btn invoice-repeat-btn tooltipped" id="btnsubmit"
                                                type="submit"
                                                style="margin-left: 340px; background-color:gray;border-color:#4B4B4B;">
                                                <i class="material-icons left">save</i><span>&nbsp;&nbsp;Atualizar
                                                    ficha</span>
                                            </button>
                                        </div>

                                        <textarea id="observacao" rows="7" type="text" name="observacao"
                                            style="display:none;" required="required" class="form-control"
                                            placeholder="Digite a observação">
Pesquisa Patrimonial
Número da solicitação: {{$datas->ID}}  
Operação: {{$datas->PRConta}}
Nome do pesquisado: {{$datas->OutraParte}}
CPF/CNPJ: {{$datas->Codigo}}
Número Processo: {{$datas->NumeroProcesso}}
Nome Fantasia: {{$datas->ClienteFantasia}}
Data Solicitação: {{ date('d/m/Y H:i:s', strtotime($datas->DataSolicitacao)) }}              
Advogado solicitante: {{$datas->SolicitanteNome}}
Tipo Solicitação: {{$datas->TipoSolicitacao}} 
Tipo de serviço: {{$datas->TipoServico}}</textarea>
                                </div>
                                </form>
                            </div>
                        </div>
                        <div class="content-overlay"></div>
                    </div>
                </div>
                <div id="modalDados" class="modal">
                    <div class="modal-content">
                        <button type="button" class="btn  mr-sm-1 mr-2 modal-close red"
                            style="color: white;margin-left: 630px;"><i class="material-icons">close</i></button>

                        <h6>Dados da Solicitação</h6>
                        <p class="m-0" style="font-size: 13px;"><b style="color:black;">CPF/CNPJ:</b> {{$datas->Codigo}}
                        </p>
                        <p class="m-0" style="font-size: 13px;"><b style="color:black;">Pesquisado: </b>
                            {{$datas->OutraParte}}</p>
                        <p class="m-0" style="font-size: 13px;"><b style="color:black;">Data Solicitação:
                            </b>{{ date('d/m/Y H:i:s', strtotime($datas->DataSolicitacao)) }}</p>
                        <p class="m-0" style="font-size: 13px;"><b style="color:black;">Tipo de Solicitação:</b>
                            {{$datas->TipoSolicitacao}}</p>
                        <p class="m-0" style="font-size: 13px;"><b style="color:black;">E-mail Cliente:</b>
                            {{$datas->EmailCliente}}</p>
                        <p class="m-0" style="font-size: 13px;"><b style="color:black;">Solicitante:</b>
                            {{$datas->SolicitanteNome}}</p>
                        <p class="m-0" style="font-size: 13px;"><b style="color:black;">Cliente:</b>
                            {{$datas->ClienteFantasia}}</p>
                        <p class="m-0" style="font-size: 13px;"><b style="color:black;">Grupo:</b>
                            {{$datas->GrupoCliente}}</p>
                        <p class="m-0" style="font-size: 13px;"><b style="color:black;">Tipo Serviço:</b>
                            {{$datas->TipoServico}}</p>
                        <p class="m-0" style="font-size: 13px;"><b style="color:black;">Tipo:</b> {{$datas->Tipo}}</p>
                        <p class="m-0" style="font-size: 13px;"><b style="color:black;">Código Pasta:</b>
                            {{$datas->Pasta}}</p>
                        <p class="m-0" style="font-size: 13px;"><b style="color:black;">Setor do PL&C:</b>
                            {{$datas->Setor}}</p>
                        <p class="m-0" style="font-size: 13px;"><b style="color:black;">Setor Descrição:</b>
                            {{$datas->SetorDescricao}}</p>
                        <p class="m-0" style="font-size: 13px;"><b style="color:black;">Status Atual:</b>
                            {{$datas->Status}}</p>
                        <p class="m-0" style="font-size: 13px;"><b style="color:black;">Contrato:</b>
                            {{$datas->Contrato}}</p>
                        <p class="m-0" style="font-size: 13px;"><b style="color:black;">Contrato Descrição:</b>
                            {{$datas->ContratoDescricao}}</p>
                        <p class="m-0" style="font-size: 13px;"><b style="color:black;">Observações:</b>
                            {{$datas->Observacao}}</p>
                    </div>

                </div>
                <script>
                function abreModalDados() {
                    $('.modal').modal();
                    $('#modalDados').modal('open');
                }
                </script>
                <div id="modalPagamentos" class="modal">
                    <div class="modal-content">
                        <button type="button" class="btn  mr-sm-1 mr-2 modal-close red"
                            style="color: white;margin-left: 630px;"><i class="material-icons">close</i></button>

                        <h6>Dados da Pagamento</h6>
                        <p class="m-0" style="font-size: 13px;"><b style="color:black;">Quantidade pesquisas para este
                                cliente:</b> </p>
                        <p class="m-0" style="font-size: 13px;"><b style="color:black;">Data ultima pesquisa</b> </p>
                        <p class="m-0" style="font-size: 13px;"><b style="color:black;">Data vencimento boleto:</b>
                            {{ date('d/m/Y', strtotime($datavencimento)) }}</p>
                    </div>
                </div>
                <script>
                function abreModalPagamentos() {
                    $('.modal').modal();
                    $('#modalPagamentos').modal('open');
                }
                </script>
            </div>
        </div>

@endsection
        <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
        <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
        <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>
        <script src="{{ asset('/public/materialize/js/app-file-manager.min.js') }}"></script>
        <script src="{{ asset('/public/materialize/js/vendors.min.js') }}"></script>

