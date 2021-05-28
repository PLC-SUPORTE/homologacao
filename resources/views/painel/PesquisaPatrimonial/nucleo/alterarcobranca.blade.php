@extends('painel.Layout.header')
<?php $search = 2 ?>
@section('title') Alterar fluxo pagamento @endsection
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

<?php
$connect = new PDO("sqlsrv:Server=192.168.150.14,65014;Database=Intranet", "six", "89202");
// $connect = new PDO("sqlsrv:Server=localhost;Database=intranet", "six", "89202");
function buscasolicitacoes($connect, $id_matrix)
{ 
 $output = '';
$query = "SELECT * FROM PesquisaPatrimonial_Solicitacao_ServicosSolicitados INNER JOIN dbo.PesquisaPatrimonial_Servicos_UF on  dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_tiposolicitacao = dbo.PesquisaPatrimonial_Servicos_UF.id Where id_matrix = $id_matrix";
 $statement = $connect->prepare($query);
 $statement->execute();
 $result = $statement->fetchAll();
 foreach($result as $row)
 {
  $output .= '<option value="'.$row["id_tiposolicitacao"].'">'.$row["descricao"].'</option>';
 }
 return $output;
}

function buscacomarcas($connect)
{ 
 $output = '';
$query = "SELECT * FROM PesquisaPatrimonial_Cidades ORDER BY municipio ASC";
 $statement = $connect->prepare($query);
 $statement->execute();
 $result = $statement->fetchAll();
 foreach($result as $row)
 {
  $output .= '<option value="'.$row["id"].'">'.$row["municipio"].'</option>';
 }
 return $output;
}

function buscacorrespondentes($connect)
{ 
 $output = '';
$query = "SELECT dbo.users.id, dbo.users.name FROM dbo.users INNER JOIN dbo.profile_user on dbo.users.id = dbo.profile_user.user_id Where dbo.profile_user.profile_id = '1'";
 $statement = $connect->prepare($query);
 $statement->execute();
 $result = $statement->fetchAll();
 foreach($result as $row)
 {
  $output .= '<option value="'.$row["id"].'">'.$row["name"].'</option>';
 }
 return $output;
}

function buscamotivos($connect)
{ 
 $output = '';
$query = "SELECT * FROM dbo.Jurid_Nota_Tiposervico Where ativo = 'S'";
 $statement = $connect->prepare($query);
 $statement->execute();
 $result = $statement->fetchAll();
 foreach($result as $row)
 {
  $output .= '<option value="'.$row["id"].'">'.$row["descricao"].'</option>';
 }
 return $output;
}

?>

@endsection
@section('header_title')
Pesquisa patrimonial
@endsection
@section('submenu')
<li class="breadcrumb-item"><a href="{{ route('Painel.PesquisaPatrimonial.nucleo.index') }}">Dashboard</a></li>
<li class="breadcrumb-item active" style="color: black;">Alterar fluxo pagamento</li>
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
                                                </b> {{$datas->OutraParte}}</p>
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
                                            <p class="m-0" style="font-size: 13px;"><b style="color:black;">Tipo:</b>
                                                {{$datas->Tipo}}</p>
                                            <p class="m-0" style="font-size: 13px;"><b style="color:black;">Código
                                                    Pasta:</b> {{$datas->Pasta}}</p>
                                            <p class="m-0" style="font-size: 13px;"><b style="color:black;">Setor do
                                                    PL&C:</b> {{$datas->Setor}}</p>
                                            <p class="m-0" style="font-size: 13px;"><b style="color:black;">Setor
                                                    Descrição:</b> {{$datas->SetorDescricao}}</p>
                                            <p class="m-0" style="font-size: 13px;"><b style="color:black;">Status
                                                    Atual:</b> {{$datas->Status}}</p>
                                            <p class="m-0" style="font-size: 13px;"><b
                                                    style="color:black;">Contrato:</b> {{$datas->Contrato}}</p>
                                            <p class="m-0" style="font-size: 13px;"><b style="color:black;">Contrato
                                                    Descrição:</b> {{$datas->ContratoDescricao}}</p>
                                            <p class="m-0" style="font-size: 13px;"><b
                                                    style="color:black;">Observações:</b> {{$datas->Observacao}}</p>
                                        </div>
                                        <h6>Dados pagamento</h6>
                                        <div class="collection file-manager-drive mt-3">
                                            <p class="m-0" style="font-size: 13px;"><b style="color:black;">Quantidade
                                                    pesquisas para este cliente:</b> </p>
                                            <p class="m-0" style="font-size: 13px;"><b style="color:black;">Data ultima
                                                    pesquisa</b> </p>
                                            <p class="m-0" style="font-size: 13px;"><b style="color:black;">Saldo
                                                    cliente:</b> <a style="color: green;font-weight: bold">R$
                                                    <?php echo number_format($saldototal, 2); ?></a></p>
                                            <p class="m-0" style="font-size: 13px;"><b style="color:black;">Data
                                                    vencimento boleto:</b>
                                                {{ date('d/m/Y', strtotime($datavencimento)) }}</p>
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
                                    action="{{ route('Painel.PesquisaPatrimonial.nucleo.alteradoforma') }}"
                                    method="POST" role="search" enctype="multipart/form-data">
                                    {{ csrf_field() }}

                                    <input type="hidden" name="id_matrix" value="{{$datas->ID}}" id="id_matrix">
                                    <input type="hidden" name="solicitanteid" value="{{$datas->SolicitanteID}}"
                                        id="solicitanteid">
                                    <input type="hidden" name="solicitantecpf" value="{{$datas->SolicitanteCPF}}"
                                        id="solicitantecpf">
                                    <input type="hidden" name="solicitanteemail" value="{{$datas->SolicitanteEmail}}"
                                        id="solicitanteemail">
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
                                    <input type="hidden" id="clienteuf" name="clienteuf" value="{{$datas->ClienteUF}}">
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
                                    <input type="hidden" id="unidadeuf" name="unidadeuf" value="{{$datas->UnidadeUF}}">
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
                                    <input readonly="" name="cliente" id="cliente" value="{{$datas->ClienteFantasia}}"
                                        type="hidden" />
                                    <input readonly="" name="grupocliente" id="grupocliente"
                                        value="{{$datas->GrupoCliente}}" type="hidden" />
                                    <input type="hidden" readonly="" name="emailcliente" id="emailcliente"
                                        value="{{$datas->EmailCliente}}" />
                                    <input type="hidden" readonly="" name="tipo" id="tipo" value="{{$datas->Tipo}}" />
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
                                    <input name="saldocliente" id="saldocliente" type="hidden"
                                        value="<?php echo number_format($saldototal, 2); ?>" readonly="">

                                    <div class="app-file-content ps">

                                        <!--Comarcas -->
                                        <div class="app-file-content ps">
                                            <h6 class="font-weight-700 mb-3" style="margin-top: -4%; margin-left: -2%;">
                                                Comarcas</h6>

                                            @foreach($comarcas as $comarca)
                                            <div class="row">
                                                <div class="input-field col s6">
                                                    <input type="text" readonly="" name="estado" id="estado"
                                                        value="{{$comarca->descricao}}" class="validate">
                                                    <label for="estado" style="color: black;font-weight: bold">Unidade
                                                        Federativa</label>
                                                </div>

                                                <div class="input-field col s6">
                                                    <input type="text" readonly="" name="comarca" id="comarca"
                                                        value="{{$comarca->comarca}}">
                                                    <label for="comarca"
                                                        style="color: black;font-weight: bold">Comarca</label>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        <!--Comarcas -->

                                        <div class="app-file-content ps">
                                            <h6 class="font-weight-700 mb-3" style="margin-top: -4%; margin-left: -2%;">
                                                Adiantamento</h6>

                                            <div class="row">
                                                <div class="input-field col s12">
                                                    <div class="form-group">
                                                        <div class="form-group">
                                                            <label class="control-label">Observação:</label>
                                                            <textarea id="observacaoadiantamento" rows="6" type="text"
                                                                name="observacaoadiantamento" class="form-control"
                                                                placeholder="Insira a observação abaixo."
                                                                style="height: 10rem;text-align:left; overflow:auto;"></textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <label style="color: black;font-weight: bold">Anexar evidência do
                                                        cliente</label>
                                                    <div class="input-field col s12">
                                                        <input type="file" name="anexoadiantamento"
                                                            id="anexoadiantamento" required />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="input-field" style="margin-left: 550px;">
                                            <button class="btn invoice-repeat-btn tooltipped" id="btnsubmit"
                                                type="submit" style="background-color:#4B4B4B;border-color:#4B4B4B;"
                                                data-toggle="tooltip" data-placement="top"
                                                title="Clique aqui para alterar forma de cobrança.">
                                                <i class="material-icons left">save</i><span>&nbsp;&nbsp;Salvar</span>
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

            </div>
        </div>

@endsection
@section('scripts')
        <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
        <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
        <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>
        <script src="{{ asset('/public/materialize/js/app-file-manager.min.js') }}"></script>
@endsection