@extends('painel.Layout.header')
<?php $search = 2 ?>
@section('title') Faturamento semanal @endsection <!-- Titulo da pagina -->

@section('header') 
    <link rel="apple-touch-icon" href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/images/favicon/apple-touch-icon-152x152.png">
    <link rel="shortcut icon" href="{{ asset('/public/assets/imgs/casinha.png')}}" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/responsive.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/materialize.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/data-tables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/app-invoice.min.css') }}">
@endsection
@section('header_title')
Faturamento semanal
@endsection
@section('submenu')
<li class="breadcrumb-item active" style="color: black;">Faturamento semanal
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
    <a  href="{{route('Painel.Gestao.Faturamentosemanal.detalhado')}}" class="btn-floating btn-mini waves-effect waves-light buttons-right tooltipped" data-position="left" data-tooltip="Clique aqui para visualizar o detalhamento do faturamento." style="background-color: gray;"><i class="material-icons">list</i></a>
  </div>


  <div class="responsive-table">
    <table class="table invoice-data-table white border-radius-4 pt-1">
      <thead>
        <tr>
          <th style="font-size: 11px"></th>
          <th style="font-size: 11px">Cod. Setor</th>
          <th style="font-size: 11px">Setor</th>
          <th style="font-size: 11px">Valor Orçado Mês (VOM)</th>
          <th style="font-size: 11px">Valor Emitido Mês (VEM)</th>
          <th style="font-size: 11px">Desvio 1 (VOM-VEM)</th>
          <th style="font-size: 11px">Total Recebido (TR=RM+RA)</th>
          <th style="font-size: 11px">Recebidos do Mês (RM)</th>
          <th style="font-size: 11px">Recebido de meses anteriores (RA)</th>
          <th style="font-size: 11px">A Vencer do Mês (VAR=VEM-RM-IN)</th>
          <th style="font-size: 11px">A Vencer dos meses posteriores</th>
          <th style="font-size: 11px">Programado de outros meses</th>
          <th style="font-size: 11px">Desvio 2 Previsto (VOM-TR-VAR-VPOM)</th>
          <th style="font-size: 11px">Inadimplência Acumulada (2018, 2019 e 2020)</th>
          <th style="font-size: 11px">Inadimplência (IN=VEM-RM-VAR)</th>

        </tr>
      </thead>

      <tbody>
      @foreach($faturamentoSemanal as $faturamento)  
        <tr>
          <td style="font-size: 11px"></td>
          <td style="font-size: 11px">{{ $faturamento->cod_setor }}</td>
          <td style="font-size: 11px">{{ $faturamento->setor }}</td>
          <td style="font-size: 11px">R$ <?php echo number_format( $faturamento->valor_orcado_mes,2,",",".") ?></td>
          <td style="font-size: 11px">R$ <?php echo number_format( $faturamento->valor_emitido_mes,2,",",".") ?></td>
          @if($faturamento->desvio1 < 0)
          <td style="font-size: 11px;color:red">R$ <?php echo number_format( $faturamento->desvio1,2,",",".") ?></td>
          @else 
          <td style="font-size: 11px">R$ <?php echo number_format( $faturamento->desvio1,2,",",".") ?></td>
          @endif
          <td style="font-size: 11px">R$ <?php echo number_format( $faturamento->total_recebido,2,",",".") ?></td>
          <td style="font-size: 11px">R$ <?php echo number_format( $faturamento->recebido_do_mes,2,",",".") ?></td>
          <td style="font-size: 11px">R$ <?php echo number_format( $faturamento->recebido_de_meses_anteriores,2,",",".") ?></td>
          <td style="font-size: 11px">R$ <?php echo number_format( $faturamento->a_vencer_do_mes,2,",",".") ?></td>
          <td style="font-size: 11px">R$ <?php echo number_format( $faturamento->a_vencer_proximos_meses,2,",",".") ?></td>
          <td style="font-size: 11px">R$ <?php echo number_format( $faturamento->programado_de_outros_meses,2,",",".") ?></td>
          @if($faturamento->desvio2_previsto < 0)
          <td style="font-size: 11px;color:red;">R$ <?php echo number_format($faturamento->desvio2_previsto,2,",",".") ?></td>
          @else 
          <td style="font-size: 11px">R$ <?php echo number_format($faturamento->desvio2_previsto,2,",",".") ?></td>
          @endif
          <td style="font-size: 11px">R$ <?php echo number_format($faturamento->inadimplencia_acumulada,2,",",".") ?></td>
          @if($faturamento->inadimplencia < 0)
          <td style="font-size: 11px;color:red;">R$ <?php echo number_format($faturamento->inadimplencia,2,",",".") ?></td>
          @else 
          <td style="font-size: 11px">R$ <?php echo number_format($faturamento->inadimplencia,2,",",".") ?></td>
          @endif
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
    <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/app-invoice.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/vendors.min.js') }}"></script>

