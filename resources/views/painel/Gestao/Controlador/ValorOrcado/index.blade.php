@extends('painel.Layout.header')
<?php $search = 2 ?>
@section('title') Valor orçado anual @endsection
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
<link rel="stylesheet" href="{{ asset('/public/materialize/css/form-select2.min.css') }}">

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
Valor orçado anual
@endsection
@section('submenu')
<li class="breadcrumb-item"><a href="{{ route('Painel.Gestao.Controlador.index') }}">Dashboard</a>
</li>
<li class="breadcrumb-item active" style="color: black;">Valor orçado anual
</li>
@endsection
@section('body')
    <div>
        <div class="row">


            <div class="row">
                <div class="content-wrapper-before blue-grey lighten-5"></div>
                <div class="col s12">
                    <div class="container">


                        <section class="invoice-list-wrapper section">

                            <div class="invoice-filter-action mr-4">
                                <a href="#modal"
                                    class="btn-floating btn-mini waves-effect waves-light tooltipped modal-trigger"
                                    data-position="left"
                                    data-tooltip="Clique aqui para adicionar um novo lançamento de valor orçado anual."
                                    style="margin-left: 5px;background-color: gray;"><i
                                        class="material-icons">add</i></a>
                                <a href="{{ route('Painel.Gestao.Controlador.ValorOrcado.exportar') }}"
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
                                            <th style="font-size: 12px"></th>
                                            <th style="font-size: 12px"></th>
                                            <th style="font-size: 12px">#</th>
                                            <th style="font-size: 12px">Setor</th>
                                            <th style="font-size: 12px">Setor descrição</th>
                                            <th style="font-size: 12px">Unidade</th>
                                            <th style="font-size: 12px">Unidade descrição</th>
                                            <th style="font-size: 12px">Valor</th>
                                            <th style="font-size: 12px"></th>

                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($datas as $data)
                                        <tr>
                                            <td style="font-size: 11px"></td>
                                            <td style="font-size: 11px"></td>
                                            <td style="font-size: 11px"></td>
                                            <td style="font-size: 11px">{{$data->setor}}</td>
                                            <td style="font-size: 11px">{{$data->descricao}}</td>
                                            <td style="font-size: 11px">{{$data->unidade}}</td>
                                            <td style="font-size: 11px">{{$data->unidade_descricao}}</td>
                                            <td style="font-size: 11px">R$ <?php echo number_format($data->valor, 2); ?>
                                            </td>

                                            <td style="font-size: 11px">

                                                <div class="invoice-action">
                                                    <a href="{{route('Painel.Gestao.Controlador.ValorOrcado.editar', $data->setor)}}"
                                                        class="invoice-action-view mr-4"><i
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


        <!--Modal perguntando -->
        <div id="modal" class="modal" style="width: 1200px;">
            <form id="form" role="form" action="{{ route('Painel.Gestao.Controlador.ValorOrcado.gravarregistro') }}"
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
                            <h1 style="text-align: center;">Gravando registro(s)...&hellip;</h1>
                        </div>
                    </center>

                    <div id="corpodiv">
                        <a class="waves-effect modal-close waves-light btn red right align"
                            style="margin-top: -32px; margin-right: -20px;"><i
                                style="margin-left: 15px; font-size: 20px;" class="material-icons left">close</i></a>
                        {{-- <a class="waves-effect modal-close waves-light btn red right align" style="margin-top: -30px; margin-right: -20px;"><i class="material-icons left">close</i>Fechar</a> --}}
                        <h5>Novo valor orçado anual</h5>

                        <br>

                        <div class="row">

                            <div class="input-field col s3">
                                <select class="select2 browser-default" name="setor" required>
                                    @foreach($setores as $setor)
                                    <option value="{{$setor->Codigo}}">{{$setor->Codigo}} - {{$setor->Descricao}}
                                    </option>
                                    @endforeach
                                </select>
                                <label>Informe o setor de custo do PLC:</label>
                            </div>

                            <div class="input-field col s2">
                                <label>Informe o valor anual:</label>
                                <input value="00,00" id="valor" name="valor" class="form-control" type="text"
                                    maxlength="8" pattern="(?:\.|,|[0-9])*" placeholder="Valor(R$)"
                                    onKeyPress="return(moeda2(this,'.',',',event))">
                            </div>


                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <a type="button" id="btnsubmit" onClick="envia();"
                        class="modal-action waves-effect waves-green btn-flat"
                        style="background-color: green;color:white;"><i
                            class="material-icons left">save_alt</i>Salvar</a>
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
    $(document).ready(function() {
        $('.modal').modal();
        $("#manualmentediv").hide();
        $("#importacaodiv").hide();
    });
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>

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
@endsection