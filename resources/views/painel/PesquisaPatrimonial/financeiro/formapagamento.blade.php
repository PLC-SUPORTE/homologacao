@extends('painel.Layout.header')
<?php $search = 2 ?>
@section('title') Selecionar forma de pagamento @endsection
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
<li class="breadcrumb-item"><a href="{{route('Painel.PesquisaPatrimonial.financeiro.index')}}">Dashboard</a>
</li>
<li class="breadcrumb-item"><a href="{{route('Painel.PesquisaPatrimonial.financeiro.solicitacoes')}}">Solicitações</a>
</li>
<li class="breadcrumb-item active" style="color: black;">Selecionar forma de pagamento
</li>
@endsection
@section('body')
    <div>

        <div class="row">
            <div class="col s12">
                <div class="container" style="margin-top: -20px;">
                    <div class="section app-file-manager-wrapper">

                        <div class="app-file-overlay"></div>
                        <div class="sidebar-left" style="width: 35%;">
                            <div class="app-file-sidebar display-flex">
                                <div class="app-file-sidebar-left">
                                    <span class="app-file-sidebar-close hide-on-med-and-up"><i
                                            class="material-icons">close</i></span>
                                    <div class="input-field add-new-file mt-0">
                                        <h6>Dados da Solicitação</h6>
                                        <div class="getfileInput">
                                            <input type="file" id="getFile">
                                        </div>
                                    </div>

                                    <div class="app-file-sidebar-content ps">
                                        <div class="collection file-manager-drive mt-3">
                                            <p class="m-0" style="font-size: 13px;"><b
                                                    style="color:black;">CPF/CNPJ:</b> {{$datas->Codigo}}</p>
                                            <p class="m-0" style="font-size: 13px;"><b style="color:black;">Pesquisado:
                                                </b> {{$datas->Pesquisado}}</p>
                                            <p class="m-0" style="font-size: 13px;"><b style="color:black;">Data
                                                    Solicitação:
                                                </b>{{ date('d/m/Y H:i:s', strtotime($datas->DataSolicitacao)) }}</p>
                                            <p class="m-0" style="font-size: 13px;"><b style="color:black;">Tipo de
                                                    Solicitação:</b> {{$datas->TipoSolicitacao}}</p>
                                            <p class="m-0" style="font-size: 13px;"><b style="color:black;">E-mail
                                                    Cliente:</b> {{$datas->EmailCliente}}</p>
                                            <p class="m-0" style="font-size: 13px;"><b
                                                    style="color:black;">Solicitante:</b> {{$datas->SolicitanteNome}}
                                            </p>
                                            <p class="m-0" style="font-size: 13px;"><b style="color:black;">Cliente:</b>
                                                {{$datas->ClienteFantasia}}</p>
                                            <p class="m-0" style="font-size: 13px;"><b style="color:black;">Grupo:</b>
                                                {{$datas->GrupoCliente}}</p>
                                            <p class="m-0" style="font-size: 13px;"><b style="color:black;">Tipo
                                                    Serviço:</b> {{$datas->TipoServico}}</p>
                                            <p class="m-0" style="font-size: 13px;"><b style="color:black;">Código
                                                    Pasta:</b> {{$datas->Pasta}}</p>
                                            <p class="m-0" style="font-size: 13px;"><b style="color:black;">Setor do
                                                    PL&C:</b> {{$datas->Setor}}</p>
                                            <p class="m-0" style="font-size: 13px;"><b style="color:black;">Setor
                                                    Descrição:</b> {{$datas->SetorDescricao}}</p>
                                            <p class="m-0" style="font-size: 13px;"><b style="color:black;">Status
                                                    Atual:</b> {{$datas->Status}}</p>
                                            <p class="m-0" style="font-size: 13px;"><b
                                                    style="color:black;">Observações:</b> {{$datas->Observacao}}</p>
                                        </div>
                                        <h6>Dados pagamento</h6>
                                        <div class="collection file-manager-drive mt-3">
                                            <p class="m-0" style="font-size: 13px;"><b style="color:black;">Quantidade
                                                    pesquisas para este cliente:</b> </p>
                                            <p class="m-0" style="font-size: 13px;"><b style="color:black;">Data ultima
                                                    pesquisa</b> </p>
                                            <p class="m-0" style="font-size: 13px;"><b style="color:black;">Valor a
                                                    receber:</b> <a style="color: green;font-weight: bold">R$
                                                    <?php echo number_format($datas->Valor, 2); ?></a></p>
                                            <p class="m-0" style="font-size: 13px;"><b style="color:black;">Dias
                                                    vencimento boleto:</b> {{$datas->DiasVencimento}}</p>
                                            <p class="m-0" style="font-size: 13px;"><b style="color:black;">E-mail
                                                    cliente:</b> {{$datas->EmailCliente}}</p>

                                        </div>

                                        <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
                                            <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
                                        </div>
                                        <div class="ps__rail-y" style="top: 0px; right: 0px;">
                                            <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="content-right" style="width: 62%;">
                            <div class="app-file-area">
                                <div class="app-file-header">
                                    <!-- Header search bar starts -->
                                    <div class="sidebar-toggle show-on-medium-and-down mr-1 ml-1">
                                        <i class="material-icons">menu</i>
                                    </div>
                                </div>

                                <form role="form" onsubmit="btnsubmit.disabled = true; return true;"
                                    action="{{ route('Painel.PesquisaPatrimonial.financeiro.enviarformapagamento') }}"
                                    method="POST" role="search" enctype="multipart/form-data">
                                    {{ csrf_field() }}

                                    <input type="hidden" name="solicitanteid" value="{{$datas->SolicitanteID}}"
                                        id="solicitanteid">
                                    <input type="hidden" name="solicitantecpf" value="{{$datas->SolicitanteCPF}}"
                                        id="solicitantecpf">
                                    <input type="hidden" name="solicitanteemail" value="{{$datas->SolicitanteEmail}}"
                                        id="solicitanteemail">
                                    <input type="hidden" id="clienterazao" name="clienterazao"
                                        value="{{$datas->ClienteRazao}}">
                                    <input type="hidden" name="clienteendereco" id="clienteendereco"
                                        value="{{$datas->ClienteEndereco}}">
                                    <input type="hidden" name="clientecidade" id="clientecidade"
                                        value="{{$datas->ClienteCidade}}">
                                    <input type="hidden" name="clientebairro" id="clientebairro"
                                        value="{{$datas->ClienteBairro}}">
                                    <input type="hidden" name="clienteuf" id="clienteuf" value="{{$datas->ClienteUF}}">
                                    <input type="hidden" name="clientecep" id="clientecep"
                                        value="{{$datas->ClienteCEP}}">
                                    <input type="hidden" name="unidadecnpj" id="unidadecnpj"
                                        value="{{$datas->UnidadeCNPJ}}">
                                    <input type="hidden" name="unidaderazao" id="unidaderazao"
                                        value="{{$datas->UnidadeRazao}}">
                                    <input type="hidden" name="unidadecidade" id="unidadecidade"
                                        value="{{$datas->UnidadeCidade}}">
                                    <input type="hidden" name="unidadebairro" id="unidadebairro"
                                        value="{{$datas->UnidadeBairro}}">
                                    <input type="hidden" name="unidadeuf" id="unidadeuf" value="{{$datas->UnidadeUF}}">
                                    <input type="hidden" id="diasvencimento" value="{{$datas->DiasVencimento}}"
                                        name="diasvencimento" class="form-control">
                                    <input type="hidden" id="emailcliente" value="{{$datas->EmailCliente}}"
                                        name="emailcliente">
                                    <input type="hidden" id="setor" value="{{$datas->Setor}}" name="setor">
                                    <input type="hidden" id="unidade" value="{{$datas->Unidade}}" name="unidade">
                                    <input type="hidden" id="valortotal" value="{{$datas->Valor}}" name="valortotal">
                                    <input type="hidden" id="id_matrix" value="{{$datas->ID}}" name="id_matrix">

                                    <div class="app-file-content ps">

                                        <!--Comarcas -->
                                        <div class="app-file-content ps">

                                            <div class="app-file-content ps">
                                                <h6 class="font-weight-700 mb-3"
                                                    style="margin-top: -4%; margin-left: -2%;">Pagamento</h6>


                                                <div class="row">
                                                    <div class="input-field col s12">
                                                        <label for="nome"
                                                            style="margin-top: -27px; font-size: 12px;color: black;font-weight: bold">Selecione
                                                            abaixo a forma de pagamento:</label>
                                                        <p>
                                                            <label>
                                                                <input class="with-gap" type="radio" id="boleto"
                                                                    name="formapagamento" value="BOLETO" />
                                                                <span>Boleto</span>
                                                            </label>

                                                        </p>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="input-field" style="margin-left: 500px;">
                                                <button class="btn invoice-repeat-btn tooltipped" id="btnsubmit"
                                                    type="submit" style="background-color:gray;border-color:#4B4B4B;"
                                                    data-toggle="tooltip" data-placement="left"
                                                    title="Clique aqui para salvar a forma de pagamento desta solicitação de pesquisa patrimonial.">
                                                    <i
                                                        class="material-icons left">save</i><span>&nbsp;&nbsp;Salvar</span>
                                                </button>
                                            </div>


                                            <textarea id="observacao" rows="7" type="text" name="observacao"
                                                style="display:none;" required="required" class="form-control"
                                                placeholder="Digite a observação">
Pesquisa Patrimonial
Número da solicitação: {{$datas->ID}}  
Operação: {{$datas->Pesquisado}}
CPF/CNPJ: {{$datas->Codigo}}
Número Processo: {{$datas->NumeroProcesso}}
Nome Fantasia: {{$datas->ClienteFantasia}}
Data Solicitação: {{ date('d/m/Y H:i:s', strtotime($datas->DataSolicitacao)) }}              
Advogado solicitante: {{$datas->SolicitanteNome}}
Tipo Solicitação: {{$datas->TipoSolicitacao}} 
Tipo de serviço: {{$datas->TipoServico}}
 </textarea>

                                        </div>



                                </form>

                            </div>



                        </div>
                        <div class="content-overlay"></div>
                    </div>
                </div>

            </div>
        </div>
@endsection
@section('scripts')

        <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
        <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
        <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>
        <script src="{{ asset('/public/materialize/js/app-file-manager.min.js') }}"></script>
@endsection