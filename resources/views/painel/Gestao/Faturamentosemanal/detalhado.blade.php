@extends('painel.Layout.header')
<?php $search = 2 ?>
@section('title') Faturamento semanal detalhado @endsection <!-- Titulo da pagina -->

@section('header') 
    <!-- <link rel="apple-touch-icon" href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/images/favicon/apple-touch-icon-152x152.png">
    <link rel="shortcut icon" href="{{ asset('/public/assets/imgs/casinha.png')}}" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/responsive.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/materialize.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/data-tables.min.css') }}"> 
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/custom.css') }}"> 
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/app-invoice.min.css') }}">  -->

    <style>
      table.dataTable.customize-table thead th{
        border-radius: 0;
        border-bottom: 1px solid #e0e0e0;
      }

      table.dataTable.customize-table thead td{
        border-radius: 0;
        border-bottom: 1px solid #e0e0e0;
      }

      table.customize-table.dataTable.no-footer{
        border-bottom: 0px solid #e0e0e0;
      }

      #table-detalhado_info{
        display: none;
      }

      table.customize-table.dataTable tbody td{
        font-size: 10px;
        text-align: center;
        border-bottom: 1px solid #e0e0e0;
      }

      .input-field input:read-only + label{
        -webkit-transform: translateY(-14px) scale(.8);
        -ms-transform: translateY(-14px) scale(.8);
        transform: translateY(-14px) scale(.8);
        -webkit-transform-origin: 0 0;
        -ms-transform-origin: 0 0;
        transform-origin: 0 0;
      }

      body::-webkit-scrollbar-track {
        background-color: #F4F4F4;
      }
      body::-webkit-scrollbar {
          width: 6px;
          background: #F4F4F4;
      }
      body::-webkit-scrollbar-thumb {
          background: #dad7d7;
      }

      .dataTables_scrollBody::-webkit-scrollbar-track {
        background-color: #F4F4F4;
      }
      .dataTables_scrollBody::-webkit-scrollbar {
          height: 6px;
          background: #F4F4F4;
      }
      .dataTables_scrollBody::-webkit-scrollbar-thumb {
          background: #dad7d7;
      }
  /* 
      #table-detalhado_filter{
        float: left;
        margin-bottom: 15px;
        width: calc(100% - 160px);
        margin-top: -45px;
      }

      #table-detalhado_filter input{
        height: 3.2rem;
        margin: 0;
        padding-left: 1.5rem;
        border: 1px solid #9e9e9e!important;
        border-bottom: none;
        border-radius: 150px;
        background: #fff;
      }

      #table-detalhado_wrapper{
        margin-bottom: 30px;
      }

      #table-detalhado_wrapper .dataTables_paginate .paginate_button{
        box-sizing: border-box;
        display: inline-block;
        min-width: 1.5em;
        padding: .25em .65em;
        margin-left: 2px;
        text-align: center;
        text-decoration: none !important;
        cursor: pointer;
        *cursor: hand;
        color: #333 !important;
        border: 1px solid transparent;
        border-radius: 2px;
      }

      #table-detalhado_wrapper .dataTables_paginate .paginate_button.current{
        color: #fff!important;
        border: 1px solid #3f51b5;
        border-radius: 4px;
        background: #3f51b5;
        box-shadow: 0 0 8px 0 #3f51b5;
      }

      .dataTables_paginate {
        margin-top: 15px;
      } */
    </style>

@endsection
@section('header_title')
Faturamento semanal
@endsection
@section('submenu')
<li class="breadcrumb-item"><a href="{{route('Painel.Gestao.FaturamentoSemanal.index')}}">Faturamento semanal</a>
</li>
<li class="breadcrumb-item active" style="color: black;">Faturamento semanal detalhado
</li>
@endsection
@section('body')
  <div>
    <div class="row">
      <div class="col s12">
        <div class="container">
          <section class="invoice-list-wrapper section">
            <div class="invoice-filter-action mr-4">
              <a style="background-color: gray;"  href="{{route('Painel.Gestao.Faturamentosemanal.detalhado.exportar')}}" class="btn-floating btn-mini waves-effect waves-light buttons-right tooltipped" data-position="left" data-tooltip="Clique aqui para exportar em Excel o faturamento semanal detalhado."><img style="margin-top: 10px; width: 20px;margin-left:8px;" src="{{URL::asset('/public/imgs/icon.png')}}"/></a>
              <a style="background-color: gray;"  href="{{route('Painel.Gestao.FaturamentoSemanal.index')}}" class="btn-floating btn-mini waves-effect waves-light buttons-right tooltipped" data-position="left" data-tooltip="Clique aqui para voltar e visualizar o faturamento semanal resumido."><i class="material-icons">arrow_back</i></a>
            </div>

            <div class="responsive-table">
              <table id="table-detalhado" class="table white border-radius-4 pt-1 customize-table">
                <thead>
                  <tr>
                    <th style="font-size: 11px">Visualizar</th>
                    <th style="font-size: 11px">Numdoc</th>
                    <th style="font-size: 11px">NumdocOr</th>
                    <th style="font-size: 11px">Código Cliente</th>
                    <th style="font-size: 11px">Segmento</th>
                    <th style="font-size: 11px">Grupo Econômico</th>
                    <th style="font-size: 11px">Negócio</th>
                    <th style="font-size: 11px">Cliente</th>
                    <th style="font-size: 11px">Conta Identificadora</th>
                    <th style="font-size: 11px">Código Setor</th>
                    <th style="font-size: 11px">Setor</th>
                    <th style="font-size: 11px">Data Vencimento</th>
                    <th style="font-size: 11px">Data Programação</th>
                    <th style="font-size: 11px">Data baixa</th>
                    <th style="font-size: 11px">Data Competência</th>
                    <th style="font-size: 11px">Tipo</th>
                    <th style="font-size: 11px">Status</th>
                    <th style="font-size: 11px">Valor</th>
                    <th style="font-size: 11px">Valor Bruto</th>
                    <th style="font-size: 11px">Valor Pago</th>
                    <th style="font-size: 11px">Tipo Lançamento</th>
                    <th style="font-size: 11px">Tabela</th>
                  </tr>
                </thead>
                <tbody>
                  
                </tbody>
              </table>
            </div>
          </section>
        </div>
      </div>
    </div>
  </div>

  <div id="modal-detalhado" class="modal" style="width: 1200px;height: 100%;">
    <div class="modal-content">
      <div class="row">
        <div class="col s12 m12">
          <button type="button" class="btn waves-effect mr-sm-1 mr-2 modal-close red"
              style="margin-left: 1020px;">
              <i class="material-icons">close</i>
          </button>

          <div class="row">
            <div class="col s12">
                <h6 class="mb-2">Dados do lançamento: <span id="text-numdoc"></span></h6>
            </div>

            <div class="col s2 input-field">
                <input class="" value="" id="numdoc" readonly type="text">
                <label>Numdoc:</label>
            </div>

            <div class="col s2 input-field">
                <input class="" value="" id="numdocor" readonly type="text">
                <label>NumdocOR:</label>
            </div>

            <div class="col s4 input-field">
                <input class="" value="" id="cliente" readonly type="text">
                <label>Cliente:</label>
            </div>

            <div class="col s2 input-field">
                <input class="" value="" id="segmento" readonly type="text">
                <label>Segmento:</label>
            </div>

            <div class="col s2 input-field">
                <input class="" value="" id="grupo_economico" readonly type="text">
                <label>Grupo Econômico:</label>
            </div>

            <div class="col s2 input-field">
                <input class="" value="" id="negocio" readonly type="text">
                <label>Negócio:</label>
            </div>

            <div class="col s2 input-field">
                <input class="" value="" id="conta_indentificadora" readonly type="text">
                <label>Conta Identificadora:</label>
            </div>

            <div class="col s2 input-field">
                <input class="" value="" id="cod_setor" readonly type="text">
                <label>Código setor:</label>
            </div>

            <div class="col s2 input-field">
                <input class="" value="" id="setor" readonly type="text">
                <label>Setor:</label>
            </div>

            <div class="col s2 input-field">
                <input class="" value="" id="data_vencimento" readonly type="text">
                <label>Data vencimento:</label>
            </div>

            <div class="col s2 input-field">
                <input class="" value="" id="data_programacao" readonly type="text">
                <label>Data programação:</label>
            </div>

            <div class="col s2 input-field">
                <input class="" value="" id="data_baixa" readonly type="text">
                <label>Data baixa:</label>
            </div>

            <div class="col s2 input-field">
                <input class="" value="" id="data_competencia" readonly type="text">
                <label>Data competência:</label>
            </div>

            <div class="col s2 input-field">
                <input class="" value="" id="tipo" readonly type="text">
                <label>Tipo:</label>
            </div>

            <div class="col s2 input-field">
                <input class="" value="" id="status" readonly type="text">
                <label>Status:</label>
            </div>

            <div class="col s2 input-field">
                <input class="" value="R$" id="valor" readonly type="text">
                <label>Valor:</label>
            </div>

            <div class="col s2 input-field">
                <input class="" id="valor_bruto" value="R$" readonly type="text">
                <label>Valor bruto:</label>
            </div>

            <div class="col s2 input-field">
                <input class="" id="valor_pago" value="R$" readonly type="text">
                <label>Valor pago:</label>
            </div>

            <div class="col s2 input-field">
                <input class="" id="tipo_de_lancamento" value="" readonly type="text">
                <label>Tipo de lançamento:</label>
            </div>

            <div class="col s2 input-field">
                <input class="" id="tabela" value="" readonly type="text">
                <label>Tabela:</label>
            </div>
          </div>  
        </div>
      </div>
    </div>
  </div>
@endsection
@section('scripts')

    <script src="{{ asset('/public/materialize/js/vendors.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/app-invoice.min.js') }}"></script>

    <script>
      $(document).ready(function(){
        $(".modal").modal();
      })

      $(function(){
        listFaturamentoSemanal();
        
        $("#table-detalhado").on('draw.dt', function(){
          var e=$(".invoice-filter-action"), t=$(".invoice-create-btn"), i=$(".filter-btn"); $(".action-btns").append(e, t), $(".dataTables_filter label").append(i)
        });
      });

      function abrirModal(id){
        $.ajax({
          method: 'GET',
          url: "/portal/painel/gestao/faturamentosemanal/detalhado/mostrarDados/"+id,
          async: true,
        }).done(function(data){
          $("#modal-detalhado").modal('open');
          data = data[0];
          $("#numdoc").val(data.numdoc);
          $("#numdocor").val(data.numdocor);
          $("#cliente").val(data.cliente);
          $("#segmento").val(data.segmento);
          $("#grupo_economico").val(data.grupo_economico);
          $("#negocio").val(data.negocio);
          $("#conta_identificadora").val(data.conta_identificadora);
          $("#cod_setor").val(data.cod_setor);
          $("#setor").val(data.setor);
          $("#data_vencimento").val(data.data_vencimento);
          $("#data_programacao").val(data.data_programacao);
          $("#data_baixa").val(data.data_baixa);
          $("#data_competencia").val(data.data_competencia);
          $("#tipo").val((data.tipo == "R" ? "Crédito" : "Débito"));
          $("#status").val(data.status);
          $("#valor").val(data.valor);
          $("#valor_bruto").val(data.valor_bruto);
          $("#valor_pago").val(data.valor_pago);
          $("#tipo_lancamento").val(data.tipo_lancamento);
          $("#tabela").val(data.tabela);
        });
      }

      function listFaturamentoSemanal(){
        $("#table-detalhado").DataTable({
          processing: false,
          serverSide: true,
          pageLength: 10,
          ajax: "{{route('Painel.Gestao.Faturamentosemanal.detalhado.pegar_dados')}}",
          dataSrc: '',
          columns: [
            { data: 'numdoc', render: function(data , type, row){
              return  '<div class="invoice-action">'
                        +'<a style="cursor:pointer;" onclick="abrirModal('+data+')" class="invoice-action-view mr-4 modal-trigger"><i class="material-icons">list</i></a>'
                      +'</div>'
            }},
            { data: 'numdoc', render: function(data , type, row){
              return data;
            }},
            { data: 'numdocor', render: function(data , type, row){
              return data;
            }},
            { data: 'codigo_cliente', render: function(data , type, row){
              return data
            }},
            { data: 'segmento', render: function(data , type, row){
              return data
            }},
            { data: 'grupo_economico', render: function(data , type, row){
              return data
            }},
            { data: 'negocio', render: function(data , type, row){
              return data
            }},
            { data: 'cliente', render: function(data , type, row){
              return data
            }},
            { data: 'conta_identificadora', render: function(data , type, row){
              return data
            }},
            { data: 'codigo_setor', render: function(data , type, row){
              return data
            }},
            { data: 'setor', render: function(data , type, row){
              return data
            }},
            { data: 'data_vencimento', render: function(data , type, row){
              return data
            }},
            { data: 'data_programacao', render: function(data , type, row){
              return  data
            }},
            { data: 'data_baixa', render: function(data , type, row){
              return  data
            }},
            { data: 'data_competencia', render: function(data , type, row){
              return  data
            }},
            { data: 'tipo', render: function(data , type, row){
              return  data
            }},
            { data: 'status', render: function(data , type, row){
              return  data
            }},
            { data: 'valor', render: function(data , type, row){
              return  'R$ '+data
            }},
            { data: 'valor_bruto', render: function(data , type, row){
              return  'R$ '+data
            }},
            { data: 'valor_pago', render: function(data , type, row){
              return  'R$ '+data
            }},
            { data: 'tipo_lancamento', render: function(data , type, row){
              return  data
            }},
            { data: 'tabela', render: function(data , type, row){
              return  data
            }},
          ],
          columnDefs:[ {
            targets:0, 
            className:"control"
          }, 
            {
              orderable: !0, targets:1, checkboxes: {
                selectRow: !0
              }
            }
            , {
              targets:[0, 1], orderable: !1
            }
            , {
              orderable: !1, targets:8
            }
          ], 
          order:[2, "asc"],
          dom:'<"top display-flex  mb-2"<"action-filters"f><"actions action-btns display-flex align-items-center">><"clear">rt<"bottom"p>', 
          language: {
              search:"", 
              searchPlaceholder:"Buscar registros..."
          }
          , select: {
            style:"multi", selector:"td:first-child>", items:"row"
          },
          "scrollX": true
        });
      }

        var n=1; $(".invoice-item-repeater").length&&$(".invoice-item-repeater").repeater( {
          show:function() {
              $(this).find(".dropdown-button").attr("data-target", "dropdown-discount"+n), $(this).find(".dropdown-content").attr("id", "dropdown-discount"+n), n++, $(this).slideDown()
          }

          , hide:function(e) {
              $(this).slideUp(e)
          }
        }

        ), $(document).on("click", ".invoice-apply-btn", function() {
          var e=$(this), t=e.closest(".dropdown-content").find("#discount").val(), i=e.closest(".dropdown-content").find("#Tax1 option:selected").val(), n=e.closest(".dropdown-content").find("#Tax2 option:selected").val(); e.parents().eq(4).find(".discount-value").html(t+"%"), e.parents().eq(4).find(".tax1").html(i), e.parents().eq(4).find(".tax2").html(n), $(".dropdown-button").dropdown("close")
        }

        ), $(document).on("click", ".invoice-cancel-btn", function() {
          $(".dropdown-button").dropdown("close")
        }

        ), $(document).on("change", ".invoice-item-select", function(e) {
                switch(this.options[e.target.selectedIndex].text) {
                    case"Frest Admin Template":$(e.target).closest(".invoice-item-filed").find(".invoice-item-desc").val("The most developer friendly & highly customisable HTML5 Admin"); break; case"Stack Admin Template":$(e.target).closest(".invoice-item-filed").find(".invoice-item-desc").val("Ultimate Bootstrap 4 Admin Template for Next Generation Applications."); break; case"Robust Admin Template":$(e.target).closest(".invoice-item-filed").find(".invoice-item-desc").val("Robust admin is super flexible, powerful, clean & modern responsive bootstrap admin template with unlimited possibilities"); break; case"Apex Admin Template":$(e.target).closest(".invoice-item-filed").find(".invoice-item-desc").val("Developer friendly and highly customizable Angular 7+ jQuery Free Bootstrap 4 gradient ui admin template. "); break; case"Modern Admin Template":$(e.target).closest(".invoice-item-filed").find(".invoice-item-desc").val("The most complete & feature packed bootstrap 4 admin template of 2019!")
                }
            }

        ), $(".dropdown-button").dropdown( {
                constrainWidth: !1, closeOnClick: !1
            }

        ), $(document).on("click", ".invoice-repeat-btn", function(e) {
                $(".dropdown-button").dropdown( {
                        constrainWidth: !1, closeOnClick: !1
                    }

                )
            }

        ), 0<$(".invoice-print").length&&$(".invoice-print").on("click", function() {
                window.print()
            }

        )
    </script>
@endsection