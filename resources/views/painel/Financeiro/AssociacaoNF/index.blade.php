@extends('painel.Layout.header')
<?php $search = 2 ?>
@section('title') Associação de NF @endsection <!-- Titulo da pagina -->

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
    <link rel="stylesheet" type="text/css" href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/vendors/dropify/css/dropify.min.css">

    <!-- <link rel="stylesheet" type="text/css" href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/vendors/flag-icon/css/flag-icon.min.css"> -->


    <style>
    * {
      box-sizing: border-box;
    }
    .wrapper {
      height: 50px;
      margin-top: calc(50vh - 150px);
      margin-left: calc(50vw - 600px);
      width: 180px;
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
Assoicação de NF
@endsection

@section('submenu')
<li class="breadcrumb-item active" style="color: black;">Associação de NF
</li>
@endsection

@section('body')
 <div>
   <div class="row">

        <center>
  <div id="loading">
      <div class="wrapper">
      <div class="circle circle-1"></div>
      <div class="circle circle-1a"></div>
      <div class="circle circle-2"></div>
      <div class="circle circle-3"></div>
     </div>
     <h1 style="text-align: center;">Aguarde, estamos carregando as notas fiscais...&hellip;</h1>
     </div>
  </center>   


        <div class="row" id="paginadiv">
        <div class="content-wrapper-before blue-grey lighten-5"></div>
        <div class="col s12">
          <div class="container">

<section class="invoice-list-wrapper section">

<div class="invoice-filter-action mr-4">
    <a href="{{ route('Painel.Financeiro.AssociacaoNF.gerarexcel') }}" class="btn waves-effect waves-light invoice-export border-round z-depth-4" style="background-color: gray">
      <i class="material-icons">import_export</i>
      <span class="hide-on-small-only">Exportar</span>
    </a>
  </div>


  <div class="responsive-table">
    <table class="table invoice-data-table white border-radius-4 pt-1">
      <thead>
        <tr>
          <th style="font-size: 11px"></th>
          <th style="font-size: 11px">#</th>
          <th style="font-size: 11px">CPR</th>
          <th style="font-size: 11px">Tipo DOC</th>
          <th style="font-size: 11px">Cliente</th>
          <th style="font-size: 11px">Cliente <></th>
          <th style="font-size: 11px">Pasta</th>
          <th style="font-size: 11px">Setor</th>
          <th style="font-size: 11px">Unidade</th>
          <th style="font-size: 11px">Número NF Advwin</th>
          <th style="font-size: 11px">Fatura</th>
          <th style="font-size: 11px">Data emissão</th>
          <th style="font-size: 11px">Data vencimento</th>
          <th style="font-size: 11px">Valor</th>
          <th style="font-size: 11px"></th>
        </tr>
      </thead>

      <tbody>
      @foreach($datas as $data)  
        <tr>
          <td style="font-size: 11px"></td>
          <td style="font-size: 11px"></td>
          <td style="font-size: 11px">{{$data->Numdoc}}</td>
          <td style="font-size: 11px">{{$data->Tipodoc}}</td>
          <td style="font-size: 11px">{{$data->Cliente}}</td>
          @if($data->ClienteCodigoCPR != $data->ClienteCodigoNF)
          <td style="font-size: 11px"><span class="bullet red"></span>SIM</td>
          @else
          <td style="font-size: 11px"><span class="bullet green"></span>NÃO</td>
          @endif
          <td style="font-size: 11px">{{$data->Pasta}}</td>
          <td style="font-size: 11px">{{$data->Setor}}</td>
          <td style="font-size: 11px">{{$data->Unidade}}</td>
          <td style="font-size: 11px">{{$data->NumeroNF}}</td>
          <td style="font-size: 11px">{{$data->Fatura}}</td>
          <td style="font-size: 11px">{{ date('d/m/Y', strtotime($data->DataEmissao)) }}</td>
          <td style="font-size: 11px">{{ date('d/m/Y', strtotime($data->DataVencimento)) }}</td>
          <td style="font-size: 11px"><?php echo number_format($data->Valor,2,",",".") ?></td>

          <td style="font-size: 11px">
      
          <div class="invoice-action">
          <a href="{{ route('Painel.Financeiro.AssociacaoNF.atualizar', $data->Numdoc) }}" class="invoice-action-view mr-4"><i class="material-icons">edit</i></a>
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

@endsection
@section('scripts')
    <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/app-invoice.min.js') }}"></script>

<script>
$(document).ready(function(){
   $('.modal').modal();
   $("#paginadiv").hide();
});
</script>


<script>
setTimeout(function() {
   $('#loading').fadeOut('fast');
   $("#paginadiv").show();
}, 5000);
</script>

@endsection
