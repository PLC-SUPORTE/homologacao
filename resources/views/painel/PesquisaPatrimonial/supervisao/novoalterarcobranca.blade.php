@extends('painel.Layout.header')
<?php $search = true ?>
@section('title') Alterar cobrança - Pesquisa Patrimonial @endsection
<!-- Titulo da pagina -->

@section('header')
<link rel="apple-touch-icon"
    href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/images/favicon/apple-touch-icon-152x152.png">
<link rel="shortcut icon" href="{{ asset('/public/assets/imgs/casinha.png')}}" />
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<link rel="stylesheet" href="{{ asset('/public/materialize/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('/public/materialize/css/responsive.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('/public/materialize/css/select.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('/public/materialize/css/materialize.min.css') }}">
<link rel="stylesheet" href="{{ asset('/public/materialize/css/style.min.css') }}">
<link rel="stylesheet" href="{{ asset('/public/materialize/css/data-tables.min.css') }}">
<link rel="stylesheet" href="{{ asset('/public/materialize/css/custom.css') }}">
<link rel="stylesheet" href="{{ asset('/public/materialize/css/all.min.css') }}">
<link rel="stylesheet" type="text/css"
    href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/css/pages/app-file-manager.min.css">
<link rel="stylesheet" type="text/css"
    href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/css/pages/widget-timeline.min.css">

<link rel="stylesheet"
    href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/css/pages/dashboard.min.css">

@endsection
@section('header_title')

@endsection
@section('submenu')

@endsection
@section('body')
    <ul class="display-none" id="page-search-title">
        <li class="auto-suggestion-title"><a class="collection-item" href="#">
                <h6 class="search-title">PESQUISADOS</h6>
            </a></li>
    </ul>
    <ul class="display-none" id="search-not-found">
        <li class="auto-suggestion"><a class="collection-item display-flex align-items-center" href="#"><span
                    class="material-icons">error_outline</span><span class="member-info">Nenhum registro
                    encontrado.</span></a></li>
    </ul>

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
                                    action="{{ route('Painel.PesquisaPatrimonial.supervisao.alteradostatuscobravel') }}"
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
                                    <input type="hidden" name="grupocliente_codigo" id="grupocliente_codigo"
                                        value="{{$datas->GrupoClienteCodigo}}">
                                    <input type="hidden" name="numeroprocesso" id="numeroprocesso"
                                        value="{{$datas->NumeroProcesso}}" class="form-control">
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
                                    <input name="saldocliente" id="saldocliente" type="hidden" value="222" readonly="">

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
                                                Pagamento</h6>

                                            <div class="row">
                                                <div class="input-field col s3">
                                                    <input ID="cobravel" name="cobravel" required readonly=""
                                                        value="{{$datas->Cobravel}}" type="text">
                                                    <label for="cobravel" style="color: black;font-weight: bold">Status
                                                        cobrável atual</label>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="input-field col s3">
                                                    <label for="nome"
                                                        style="margin-top: -27px; font-size: 12px;color: black;font-weight: bold">Solicitar
                                                        Adiamento?</label>
                                                    <p>
                                                        @if($datas->Cobravel == "SIM")
                                                        <label>
                                                            <input class="with-gap" type="radio" id="cobravelsim"
                                                                name="cobravel" value="SIM"
                                                                onClick="adiantamentoescolha();" />
                                                            <span>Sim</span>
                                                        </label>

                                                        <label>
                                                            <input class="with-gap" type="radio" name="cobravel"
                                                                id="adiantamentonao" onClick="pagamento();" value="NAO"
                                                                checked />
                                                            <span>Não</span>
                                                        </label>
                                                        @else
                                                        <label>
                                                            <input class="with-gap" type="radio" id="cobravelsim"
                                                                name="cobravel" value="SIM" checked />
                                                            <span>Sim</span>
                                                        </label>

                                                        <label>
                                                            <input class="with-gap" type="radio" name="cobravel"
                                                                id="adiantamentonao" value="NAO" />
                                                            <span>Não</span>
                                                        </label>
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="input-field" style="margin-left: 495px;">
                                            <button class="btn invoice-repeat-btn" id="btnsubmit" type="submit"
                                                style="background-color:#4B4B4B;border-color:#4B4B4B;"
                                                data-toggle="tooltip" data-placement="top"
                                                title="Clique aqui para alterar a cobrança.">
                                                <i
                                                    class="material-icons left">refresh</i><span>&nbsp;&nbsp;Alterar</span>
                                            </button>
                                        </div>


                                        <textarea id="observacao" rows="7" type="text" name="observacao"
                                            style="display:none;" required="required" class="form-control"
                                            placeholder="Digite a observação">
Pesquisa Patrimonial
Número da solicitação: {{$datas->ID}}  
Operação: {{$datas->OutraParte}}
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
        <script src="{{ asset('/public/materialize/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('/public/materialize/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('/public/materialize/js/dataTables.select.min.js') }}"></script>
        <script src="{{ asset('/public/materialize/js/data-tables.min.js') }}"></script>
        <script src="{{ asset('/public/materialize/js/all.min.js') }}"></script>
        <script src="{{ asset('/public/materialize/js/dashboard-analytics.min.js') }}"></script>

        <script
            src="https://pixinvent.com/materialize-material-design-admin-template/app-assets/js/scripts/app-file-manager.min.js">
        </script>


        </form>

    </div>

    </div>
    </div>
    </div>

    <script>
    $(document).ready(function() {

        var searchListLi = $(".search-list li"),
            searchList = $(".search-list"),
            searchSm = $(".search-sm"),
            searchBoxSm = $(".search-input-sm .search-box-sm"),
            searchListSm = $(".search-list-sm");

        $(function() {
                    "use strict";
                    if ($(".header-search-input").focus(function() {
                                $(this).parent("div").addClass("header-search-wrapper-focus")
                            }

                        ).blur(function() {
                                $(this).parent("div").removeClass("header-search-wrapper-focus")
                            }

                        ), $(".search-button").click(function(e) {
                                searchSm.is(":hidden") ? (searchSm.show(), searchBoxSm.focus()) : (searchSm
                                    .hide(), searchBoxSm.val(""))
                            }

                        ), $(".search-input-sm").on("click", function() {
                                searchBoxSm.focus()
                            }

                        ), $(".search-sm-close").click(function(e) {
                                searchSm.hide(), searchBoxSm.val("")
                            }

                        ), 0 < $(".search-list").length) var e = new PerfectScrollbar(".search-list", {
                            wheelSpeed: 2,
                            wheelPropagation: !1,
                            minScrollbarLength: 20
                        }

                    );
                    if (0 < searchListSm.length) var s = new PerfectScrollbar(".search-list-sm", {
                            wheelSpeed: 2,
                            wheelPropagation: !1,
                            minScrollbarLength: 20
                        }

                    );
                    var a = $(".header-search-wrapper .header-search-input,.search-input-sm .search-box-sm")
                        .data("search");
                    $(".search-sm-close").on("click", function() {
                            searchBoxSm.val(""), searchBoxSm.blur(), searchListLi.remove(), searchList
                                .addClass("display-none"), contentOverlay.hasClass("show") && contentOverlay
                                .removeClass("show")
                        }

                    ), contentOverlay.on("click", function() {
                            searchListLi.remove(), contentOverlay.removeClass("show"), searchSm.hide(),
                                searchBoxSm.val(""), searchList.addClass("display-none"), $(
                                    ".search-input-sm .search-box-sm, .header-search-input").val("")
                        }

                    ), $(".header-search-wrapper .header-search-input, .search-input-sm .search-box-sm").on(
                        "keyup",
                        function(e) {
                            contentOverlay.addClass("show"), searchList.removeClass("display-none");
                            var s = $(this);
                            if (38 !== e.keyCode && 40 !== e.keyCode && 13 !== e.keyCode) {
                                27 == e.keyCode && (contentOverlay.removeClass("show"), s.val(""), s
                            .blur());
                                var t = $(this).val().toLowerCase();
                                if ($("ul.search-list li").remove(), "" != t) {
                                    var i = "",
                                        c = "",
                                        l = "",
                                        n = 0;
                                    $.getJSON("../../../public/" + a + ".json", function(e) {
                                            for (var s = 0; s < e.listItems.length; s++)(0 == e
                                                .listItems[s].name.toLowerCase().indexOf(t) && n <
                                                4 || 0 != e.listItems[s].name.toLowerCase().indexOf(
                                                    t) && -1 < e.listItems[s].name.toLowerCase()
                                                .indexOf(t) && n < 4) && (i +=
                                                '<li class="auto-suggestion ' + (0 === n ?
                                                    "current_item" : "") +
                                                '"><a class="collection-item" href="../solicitacao/' +
                                                e.listItems[s].url +
                                                '/capa"><div class="display-flex"><div class="display-flex align-item-center flex-grow-1"><span class="material-icons" data-icon="' +
                                                e.listItems[s].icon + '">' + e.listItems[s].icon +
                                                '</span><div class="member-info display-flex flex-column"><span class="black-text">' +
                                                e.listItems[s].name +
                                                '</span><small class="grey-text">' + e.listItems[s]
                                                .category + "</small></div></div></div></a></li>",
                                                n++);
                                            "" == i && "" == c && (c = $("#search-not-found").html());
                                            var a = $("#page-search-title").html(),
                                                r = $("#default-search-main").html();
                                            l = a.concat(i, c, r), $("ul.search-list").html(l)
                                        }

                                    )
                                } else contentOverlay.hasClass("show") && (contentOverlay.removeClass(
                                    "show"), searchList.addClass("display-none"))
                            }

                            $(".header-search-wrapper .current_item").length && (searchList.scrollTop(0),
                                    searchList.scrollTop($(".search-list .current_item:first").offset()
                                        .top - searchList.height())), $(".search-input-sm .current_item")
                                .length && (searchListSm.scrollTop(0), searchListSm.scrollTop($(
                                        ".search-list-sm .current_item:first").offset().top -
                                    searchListSm.height()))
                        }

                    ), $("#navbarForm").on("submit", function(e) {
                            e.preventDefault()
                        }

                    ), $(window).on("keydown", function(e) {
                            var s, a, r = $(".search-list li.current_item");
                            if (40 === e.keyCode ? (s = r.next(), r.removeClass("current_item"), r = s
                                    .addClass("current_item")) : 38 === e.keyCode && (a = r.prev(), r
                                    .removeClass("current_item"), r = a.addClass("current_item")), 13 === e
                                .keyCode && 0 < $(".search-list li.current_item").length) {
                                var t = $("li.current_item a");
                                window.location = $("li.current_item a").attr("href"), $(t).trigger("click")
                            }
                        }

                    ), searchList.mouseenter(function() {
                            0 < $(".search-list").length && e.update(), 0 < searchListSm.length && s
                            .update()
                        }

                    ), $(document).on("mouseenter", ".search-list li", function(e) {
                            $(this).siblings().removeClass("current_item"), $(this).addClass("current_item")
                        }

                    ), $(document).on("click", ".search-list li", function(e) {
                            e.stopPropagation()
                        }

                    )
                }

            ),
            $(window).on("resize", function() {
                    $(window).width() < 992 && ($(".header-search-input").val(""), $(".header-search-input")
                        .closest(".search-list li").remove()), 993 < $(window).width() && (searchSm.hide(),
                        searchBoxSm.val(""), $(".search-input-sm .search-box-sm").val(""))
                }

            );

    });
    </script>



@endsection