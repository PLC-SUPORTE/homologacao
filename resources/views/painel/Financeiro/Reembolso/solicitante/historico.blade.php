@extends('painel.Layout.header')
@section('title') Solicitação Reembolso histórico @endsection
<!-- Titulo da pagina -->

@section('header')

<head>
    <meta http-equiv="Content-Language" content="pt-br">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description"
        content="Cadastro das informações coletadas na pesquisa patrimonial do sistema Portal PLC.">
    <meta name="keywords"
        content="pesquisapatrimonial, pesquisa, patrimonial, cadastro, plc, portal, portela lima labato colen, bh, belo horizonte">
    <meta name="author" content="Portal PL&C">
    <title>Solicitação Reembolso histórico | Portal PL&C</title>
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
    Solicitação de Reembolso
    @endsection

    @section('submenu')
    <li class="breadcrumb-item"><a href="{{ route('Painel.Financeiro.Reembolso.Solicitante.index') }}">Minhas
            solicitações de reembolso</a>
    </li>
    <li class="breadcrumb-item active" style="color: black;">Minhas solicitações finalizadas e/ou canceladas
    </li>
    @endsection

    @section('body')

    <div>
        <div class="row">
            <div class="content-wrapper-before blue-grey lighten-5"></div>

            <div class="col s12" id="corpodiv">

                <div class="container">
                    <div class="section">

                        <section class="invoice-list-wrapper section">

                            <div class="invoice-filter-action mr-3">
                                <a href="{{ route('Painel.Financeiro.Reembolso.Solicitante.index') }}"
                                    class="waves-light btn tooltipped"
                                    style="color: white; background-color: gray; font-size: 11px; border-radius: 50px;"
                                    data-position="left"
                                    data-tooltip="Clique aqui para visualizar suas solicitações em andamento."><i
                                        class="material-icons left">arrow_back</i>Voltar</a>
                                <a href="#modal" class="waves-light btn modal-trigger"
                                    style="color: white; background-color: gray; font-size: 11px; border-radius: 50px;"><i
                                        class="material-icons left">add</i>Nova solicitação</a>
                            </div>

                            <div class="responsive-table">
                                <table class="table invoice-data-table white border-radius-4 pt-1">
                                    <thead>
                                        <tr>
                                            <th style="font-size: 11px"></th>
                                            <th style="font-size: 11px">Número do debite</th>
                                            <th style="font-size: 11px">Código pasta</th>
                                            <th style="font-size: 11px">Setor do PL&C</th>
                                            <th style="font-size: 11px">Unidade</th>
                                            <th style="font-size: 11px">Tipo debite</th>
                                            <th style="font-size: 11px">Valor</th>
                                            <th style="font-size: 11px">Data solicitação</th>
                                            <th style="font-size: 11px">Data serviço</th>
                                            <th style="font-size: 11px">Status</th>
                                            <th style="font-size: 11px"></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($datas as $data)
                                        <tr>


                                            <!--Inicio Modal Cancelar -->

                                            <div id="cancelar{{$data->NumeroDebite}}" class="modal"
                                                style="width: 1207px;">
                                                <form id="form2" role="form"
                                                    action="{{ route('Painel.Financeiro.Reembolso.cancelar') }}"
                                                    method="POST" role="create">
                                                    {{ csrf_field() }}

                                                    <div class="modal-content">

                                                        <center>
                                                            <div id="loading3" style="display:none">
                                                                <div class="wrapper">
                                                                    <div class="circle circle-1"></div>
                                                                    <div class="circle circle-1a"></div>
                                                                    <div class="circle circle-2"></div>
                                                                    <div class="circle circle-3"></div>
                                                                </div>
                                                                <h1 style="text-align: center;">Aguarde, estamos
                                                                    cancelando o agendamento a solicitação de
                                                                    reembolso...&hellip;</h1>
                                                            </div>
                                                        </center>

                                                        <button type="button"
                                                            class="btn waves-effect mr-sm-1 mr-2 modal-close red"
                                                            style="margin-top: -26px; margin-left: 1080px;"><i
                                                                class="material-icons">close</i></button>

                                                        <div id="corpodiv3">
                                                            <h5>Cancelar solicitação</h5>
                                                            <p>Deseja cancelar a solicitação de reembolso abaixo ?

                                                                <br>

                                                            <div class="input-field col s1">
                                                                <input readonly id="numerodebite" type="text"
                                                                    name="numerodebite"
                                                                    value="{{ $data->NumeroDebite }}" class="validate">
                                                                <label for="numerodebite">N. Debite:</label>
                                                            </div>

                                                            <div class="input-field col s2">
                                                                <input readonly id="pasta" type="text" name="pasta"
                                                                    value="{{ $data->Pasta }}" class="validate">
                                                                <label for="pasta">Código da pasta:</label>
                                                            </div>

                                                            <div class="input-field col s2">
                                                                <input id="setor" readonly type="text" name="setor"
                                                                    value="{{ $data->Setor }}" class="validate">
                                                                <label for="setor">Setor:</label>
                                                            </div>

                                                            <div class="input-field col s2">
                                                                <input id="icon_telephone" readonly type="text"
                                                                    value="{{ date('d/m/Y', strtotime($data->DataServico)) }}"
                                                                    name="data" class="validate">
                                                                <label for="icon_telephone">Data:</label>
                                                            </div>

                                                            <div class="input-field col s2">
                                                                <input id="icon_telephone" readonly type="text"
                                                                    value="R$ <?php echo number_format($data->Valor,2,",",".") ?>"
                                                                    name="valor" class="validate">
                                                                <label for="icon_telephone">Valor total:</label>
                                                            </div>

                                                            <div class="input-field col s3">
                                                                <input id="icon_telephone" readonly type="text"
                                                                    value="{{ $data->TipoDebite }}" name="tiposervico"
                                                                    class="validate">
                                                                <label for="icon_telephone">Tipo serviço:</label>
                                                            </div>

                                                            <div class="input-field col s12" style="display: none">
                                                                <div class="form-group">
                                                                    <div class="form-group">
                                                                        <label class="control-label">Histórico
                                                                            preview:</label>
                                                                        <textarea id="hist" rows="4" type="text"
                                                                            name="hist" readonly="" class="form-control"
                                                                            style="text-align: left;margin: 0;"
                                                                            placeholder="Hist debite">
{{$data->Hist}}
Solicitação de reembolso cancelada pelo(a): {{Auth::user()->name}} - {{$dataehora}}
                    </textarea>
                                                                    </div>
                                                                </div>
                                                            </div>


                                                        </div>

                                                        <div class="modal-footer" style="margin-top: 90px;">
                                                            <button type="button" id="btnsubmit" onClick="cancelar();"
                                                                class="modal-action waves-effect btn-flat"
                                                                style="background-color: gray;color:white;"><i
                                                                    class="material-icons left">close</i>Cancelar</button>
                                                        </div>

                                                    </div>
                                                </form>
                                            </div>
                                            <!--Fim Modal Cancelar solicitação -->


                                            <!--Modal editar -->

                                            <div id="editar{{$data->NumeroDebite}}" class="modal"
                                                style="width: 1207px;height: 460px;">
                                                <form id="form3" role="form"
                                                    action="{{ route('Painel.Financeiro.Reembolso.Solicitante.editado') }}"
                                                    method="POST" role="create" enctype="multipart/form-data">
                                                    {{ csrf_field() }}

                                                    <div class="modal-content">

                                                        <center>
                                                            <div id="loading4" style="display:none">
                                                                <div class="wrapper">
                                                                    <div class="circle circle-1"></div>
                                                                    <div class="circle circle-1a"></div>
                                                                    <div class="circle circle-2"></div>
                                                                    <div class="circle circle-3"></div>
                                                                </div>
                                                                <h1 style="text-align: center;">Aguarde, estamos
                                                                    salvando a solicitação de reembolso...&hellip;</h1>
                                                            </div>
                                                        </center>

                                                        <button type="button"
                                                            class="btn waves-effect mr-sm-1 mr-2 modal-close red"
                                                            style="margin-top: -26px; margin-left: 1080px;"><i
                                                                class="material-icons">close</i></button>

                                                        <div id="corpodiv4">
                                                            <h5>Editar solicitação</h5>
                                                            <p>A solicitação {{$data->NumeroDebite}} foi reprovada pelo
                                                                motivo: <strong>{{$data->Motivo}}</strong>. Favor
                                                                realizar a correção abaixo para nova revisão.

                                                                <br>

                                                            <div class="input-field col s1">
                                                                <input readonly id="numerodebite" type="text"
                                                                    name="numerodebite"
                                                                    value="{{ $data->NumeroDebite }}" class="validate">
                                                                <label for="numerodebite">N. Debite:</label>
                                                            </div>

                                                            <div class="input-field col s2">
                                                                <input readonly id="pasta" type="text" name="pasta"
                                                                    value="{{ $data->Pasta }}" class="validate">
                                                                <label for="pasta">Código da pasta:</label>
                                                            </div>

                                                            <div class="input-field col s2">
                                                                <input id="setor" readonly type="text" name="setor"
                                                                    value="{{ $data->Setor }}" class="validate">
                                                                <label for="setor">Setor:</label>
                                                            </div>

                                                            <div class="input-field col s2">
                                                                <input id="icon_telephone" readonly type="text"
                                                                    value="{{ date('d/m/Y', strtotime($data->DataServico)) }}"
                                                                    name="data" class="validate">
                                                                <label for="icon_telephone">Data:</label>
                                                            </div>

                                                            <div class="input-field col s2">
                                                                <input id="quantidade" type="number"
                                                                    value="{{$data->Quantidade}}" name="quantidade"
                                                                    class="validate">
                                                                <label for="quantidade">Quantidade:</label>
                                                            </div>

                                                            <div class="input-field col s2">
                                                                <input name="valor_unitario" id="valor_unitario"
                                                                    type="text" maxlength="8"
                                                                    value="<?php echo number_format($data->ValorUnitario,2,",",".") ?>"
                                                                    pattern="(?:\.|,|[0-9])*" class="form-control"
                                                                    placeholder="Valor(R$)"
                                                                    onKeyPress="return(moeda2(this,'.',',',event))"
                                                                    data-toggle="tooltip" data-placement="top"
                                                                    title="Preencha o valor unitário..."
                                                                    required="required">
                                                                <label for="valor_unitario">Valor unitário:</label>
                                                            </div>

                                                            <div class="input-field col s2">
                                                                <input type="text" readonly
                                                                    value="<?php echo number_format($data->Valor,2,",",".") ?>"
                                                                    pattern="(?:\.|,|[0-9])*" name="valor_total"
                                                                    id="valor_total" class="validate">
                                                                <label for="valor_total">Valor total:</label>
                                                            </div>

                                                            <div class="input-field col s2">
                                                                <input id="icon_telephone" readonly type="text"
                                                                    value="{{ $data->TipoDebite }}" name="tiposervico"
                                                                    class="validate">
                                                                <label for="icon_telephone">Tipo serviço:</label>
                                                            </div>

                                                            <div class="input-field col s12">
                                                                <label class="control-label">Observação:</label>
                                                                <textarea id="observacao" rows="6" type="text"
                                                                    name="observacao" class="form-control"
                                                                    placeholder="Insira a observação abaixo."
                                                                    style="text-align:left; overflow:auto;">{{$data->Observacao}}</textarea>
                                                            </div>

                                                            <div class="input-field col s12" style="display: none">
                                                                <div class="form-group">
                                                                    <div class="form-group">
                                                                        <label class="control-label">Histórico
                                                                            preview:</label>
                                                                        <textarea id="hist" rows="4" type="text"
                                                                            name="hist" readonly="" class="form-control"
                                                                            style="text-align: left;margin: 0;"
                                                                            placeholder="Hist debite">
{{$data->Hist}}
Solicitação de reembolso editada pelo(a): {{Auth::user()->name}} - {{$dataehora}}
                    </textarea>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col s12 m12 l12">
                                                                <input type="file" id="input-file-now"
                                                                    name="select_file"
                                                                    accept=".jpg,.png,.xls,.xlsx,.pdf,.doc,.docx,.rtf,.jpeg"
                                                                    required class="dropify" data-default-file="" />
                                                            </div>


                                                        </div>
                                                    </div>

                                                    <div class="modal-footer" style="margin-top: 100px;">
                                                        <button type="button" id="btnsubmit" onClick="cancelar();"
                                                            class="modal-action waves-effect btn-flat"
                                                            style="background-color: gray;color:white;"><i
                                                                class="material-icons left">refresh</i>Salvar</button>
                                                    </div>

                                            </div>
                                            </form>
                            </div>
                            <!--Fim Modal Editar -->

                            <!--Inicio Modal Anexos -->
                            <div id="anexos{{$data->NumeroDebite}}" class="modal modal-fixed-footer"
                                style="width: 100%;height:100%;overflow:hidden;">

                                <button type="button" class="btn waves-effect mr-sm-1 mr-2 modal-close red"
                                    style="margin-left: 1255px; margin-top: 5px;">
                                    <i class="material-icons">close</i>
                                </button>

                                <iframe style=" position:absolute;
top:60;
left:0;
width:100%;
height:100%;" src="{{ route('Painel.Financeiro.Reembolso.anexos', $data->NumeroDebite) }}"></iframe>

                            </div>
                            <!--Fim Modal Anexos -->



                            <td style="font-size: 10px"></td>
                            <td style="font-size: 10px">{{ $data->NumeroDebite }}</td>
                            <td style="font-size: 10px">{{ $data->Pasta }}</td>
                            <td style="font-size: 10px">{{ $data->Setor }}</td>
                            <td style="font-size: 10px">{{ $data->Unidade }}</td>
                            <td style="font-size: 10px">{{ $data->TipoDebite }}</td>
                            <td style="font-size: 10px">R$ <?php echo number_format($data->Valor,2,",",".") ?></td>
                            <td style="font-size: 10px">{{ date('d/m/Y', strtotime($data->DataSolicitacao)) }}</td>
                            <td style="font-size: 10px">{{ date('d/m/Y', strtotime($data->DataServico)) }}</td>
                            @if($data->StatusID == 7)
                            <td style="font-size: 10px"><span class="bullet green"></span>{{$data->Status}}</td>
                            @else
                            <td style="font-size: 10px"><span class="bullet red"></span>{{$data->Status}}</td>
                            @endif


                            <td style="font-size: 10px">
                                <div class="invoice-action">

                                    <a href="#anexos{{$data->NumeroDebite}}"
                                        class="invoice-action-view mr-4 tooltipped modal-trigger" data-position="bottom"
                                        data-tooltip="Clique aqui para visualizar os anexos desta solicitação de reembolso."><i
                                            class="material-icons">attach_file</i></a>

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


    <div id="modal" class="modal" style="width: 90%;height: 70%;top:15%;">
        <div class="modal-content" style="overflow: hidden;height: 100%;border-radius: 0;">

            <a class="btn  mr-sm-1 mr-2 modal-close red" style="margin-left: 93.5%; margin-top: -30px;">
                <i class="material-icons">close</i>
            </a>

            <center>
                <div id="loadingdiv" style="display:none">
                    <div class="wrapper">
                        <img style="width: 60px;" src="{{URL::asset('/public/imgs/loading.gif')}}" />
                    </div>
                </div>
            </center>

            <div id="corpodiv2">

                <div class="row" style="font-size: 11px;">


                    <div class="input-field col s4" style="margin-top: 0px;width:26.33333%;">
                        <span style="margin-left: 11px;">Informe o número do processo ou código da pasta:</span>
                        <input
                            style="font-size: 10px;background-color: white;border: 1px solid gray;border-radius: 25px;padding-left: 30px;"
                            id="numeropasta" type="text" name="numeropasta" maxlenght="15"
                            onkeyup="this.value=this.value.replace(/[' ']/g,'')"
                            placeholder="Informe o número do processo ou código da pasta.">
                        <a onClick="buscardados();" class="waves-effect waves-light  btn border-round"
                            style="background-color: gray;color:white;font-size:11px;margin-left:320px;margin-top:-80px;width:140px;"><i
                                class="material-icons left">search</i> Buscar</a>
                    </div>

                </div>

                <!--Tabela com os dados encontrados -->
                <center>
                    <div id="loadingajaxdiv" style="display:none; margin-top: 30px;">
                        <img style="width: 30px;" src="{{URL::asset('/public/imgs/loading.gif')}}" />
                    </div>
                </center>

                <div class="row" style="margin-top: 7px;" id="divtable">
                    <div class="responsive-table" style="overflow: auto; max-height: 325px;">
                        <table class="table white border-radius-4 pt-1 display" id="page-length-option">
                            <thead>
                                <tr>
                                    <th style="font-size: 11px;">Número da pasta</th>
                                    <th style="font-size: 11px">Descrição</th>
                                    <th style="font-size: 11px">Cliente</th>
                                    <th style="font-size: 11px">Nª Processo</th>
                                    <th style="font-size: 11px">Setor</th>
                                    <th style="font-size: 11px">Unidade</th>
                                    <th style="font-size: 11px">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="font-size: 10px"></td>
                                    <td style="font-size: 10px"></td>
                                    <td style="font-size: 10px"></td>
                                    <td style="font-size: 10px"></td>
                                    <td style="font-size: 10px"></td>
                                    <td style="font-size: 10px"></td>
                                    <td style="font-size: 10px"></td>
                                    <td style="font-size: 10px"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!--Fim Tabela -->
            </div>
        </div>

    </div>
    </div>

  @endsection
    <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/app-invoice.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>
    <script src="{{ asset('/public/materialize/js/vendors.min.js') }}"></script>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        $('.modal').modal();
    });
    </script>


    <script>
    function envia() {

        document.getElementById("loadingdiv").style.display = "";
        document.getElementById("corpodiv2").style.display = "none";
        document.getElementById("form").submit();
    }
    </script>


    <script>
    function myFunction(id) {

        window.location = id + '/novasolicitacao';

    }
    </script>



    <script language="javascript">
    function moeda2(a, e, r, t) {
        let n = "",
            h = j = 0,
            u = tamanho2 = 0,
            l = ajd2 = "",
            o = window.Event ? t.which : t.keyCode;
        if (13 == o || 8 == o)
            return !0;
        if (n = String.fromCharCode(o),
            -1 == "0123456789".indexOf(n))
            return !1;
        for (u = a.value.length,
            h = 0; h < u && ("0" == a.value.charAt(h) || a.value.charAt(h) == r); h++)
        ;
        for (l = ""; h < u; h++)
            -
            1 != "0123456789".indexOf(a.value.charAt(h)) && (l += a.value.charAt(h));
        if (l += n,
            0 == (u = l.length) && (a.value = ""),
            1 == u && (a.value = "0" + r + "0" + l),
            2 == u && (a.value = "0" + r + l),
            u > 2) {
            for (ajd2 = "",
                j = 0,
                h = u - 3; h >= 0; h--)
                3 == j && (ajd2 += e,
                    j = 0),
                ajd2 += l.charAt(h),
                j++;
            for (a.value = "",
                tamanho2 = ajd2.length,
                h = tamanho2 - 1; h >= 0; h--)
                a.value += ajd2.charAt(h);
            a.value += r + l.substr(u - 2, u)
        }
        return !1
    }
    </script>

    <script>
    $('#valor_unitario').on('change', function() {

        var quantidade = $("#quantidade").val();
        var valor_unitario = parseFloat($("#valor_unitario").val().replace(',', '.'));
        var valor_total = quantidade * valor_unitario;

        $("#valor_total").val(parseFloat(valor_total).toFixed(2));

    });
    </script>

    <script>
    $(document).keypress(function(e) {

        if (document.getElementById("btnbuscapasta").style.display = "") {
            if (e.which == 13) buscardados();
        }
    });
    </script>

