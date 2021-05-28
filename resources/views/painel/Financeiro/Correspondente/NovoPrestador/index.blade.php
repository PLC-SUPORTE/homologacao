@extends('painel.Layout.header')
<?php $search = 2 ?>
@section('title') Novos correspondentes @endsection <!-- Titulo da pagina -->

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
Correspondentes
@endsection

@section('submenu')
<li class="breadcrumb-item active" style="color: black;">Listagem de correspodendes
</li>
@endsection

@section('body')
   <!-- BEGIN: Page Main-->
   <div>
      <div class="row">
        <div class="content-wrapper-before blue-grey lighten-5"></div>
        <div class="col s12">
          <div class="container">

<section class="invoice-list-wrapper section">


  <div class="responsive-table">
    <table class="table invoice-data-table white border-radius-4 pt-1">
      <thead>
        <tr>
          <th style="font-size: 11px"></th>
          <th style="font-size: 11px"></th>
          <th style="font-size: 11px"><span>#</span></th>
          <th style="font-size: 11px">Correspondente nome</th>
          <th style="font-size: 11px">Adv. solicitante</th>
          <th style="font-size: 11px">Data da solicitação</th>
          <th style="font-size: 11px">Status</th>
          <th style="font-size: 11px"></th>
          <th style="font-size: 11px"></th>
        </tr>
      </thead>

      <tbody>
      @foreach($datas as $data)  
        <tr>
          <td style="font-size: 10px"></td>
          <td style="font-size: 10px"></td>
          <td style="font-size: 10px">{{ $data->id }}</td>
          <td style="font-size: 10px">{{ $data->Descricao }}</td>
          <td style="font-size: 10px">{{ $data->Advogado }}</td>
          <td style="font-size: 10px">{{ date('d/m/Y', strtotime($data->DataSolicitacao)) }}</td>
          <td style="font-size: 10px"><span class="bullet red"></span>Aguardando sua revisão </td>
          <th style="font-size: 10px"></th>
          <td style="font-size: 10px">
      
          <div class="invoice-action">
          <a href="{{route('Painel.Financeiro.NovoPrestador.revisar', $data->id)}}" class="invoice-action-view mr-4"><i class="material-icons">remove_red_eye</i></a>
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
    <script src="{{ asset('/public/materialize/js/datatables.checkboxes.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/app-invoice.min.js') }}"></script>


@endsection