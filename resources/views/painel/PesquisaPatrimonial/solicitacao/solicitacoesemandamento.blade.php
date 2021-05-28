@extends('painel.Layout.header')
@section('title') Minhas solicitações em andamento @endsection <!-- Titulo da pagina -->

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
Pesquisa patrimonial
@endsection
@section('submenu')
<li class="breadcrumb-item"><a href="{{route('Painel.PesquisaPatrimonial.solicitacao.index')}}">Dashboard</a>
</li>
<li class="breadcrumb-item active" style="color: black">Minhas solicitações em andamento
</li>
@endsection
@section('body')
   <div>
      <div class="row">
        <div class="content-wrapper-before blue-grey lighten-5"></div>
        <div class="col s12">
          <div class="container">

<section class="invoice-list-wrapper section">


  <div class="invoice-create-btn">

    <a href="{{ route('Painel.PesquisaPatrimonial.solicitacao.create') }}" class="btn-floating btn-mini waves-effect waves-light tooltipped"data-position="left" data-tooltip="Clique aqui para criar uma nova solicitação de pesquisa patrimonial."  style="background-color: gray;"><i class="material-icons">add</i></a>
  </div>

  <div class="responsive-table">
    <table class="table invoice-data-table white border-radius-4 pt-1">
      <thead>
        <tr>
          <th style="font-size: 11px"></th>
          <th style="font-size: 11px">Pesquisado</th>
          <th style="font-size: 11px">Cliente</th>
          <th style="font-size: 11px">Pasta</th>
          <th style="font-size: 11px">Cobrável</th>
          <th style="font-size: 11px">Solicitação</th>
          <th style="font-size: 11px">Valor</th>
          <th style="font-size: 11px">Data</th>
          <th style="font-size: 11px">Status</th>
          <th style="font-size: 11px"></th>
        </tr>
      </thead>

      <tbody>
      @foreach($datas as $data)  
        <tr>

<!-- Inicio Modal Anexos --> 
<div id="modalanexos{{$data->CPF}}" class="modal modal-fixed-footer" style="width: 1200px;height:100%;overflow:hidden;">

<button type="button" class="btn waves-effect mr-sm-1 mr-2 modal-close red" style="margin-left: 1092px; margin-top: 5px;">
  <i class="material-icons">close</i> 
</button>

<iframe style=" position:absolute;
top:60;
left:0;
width:100%;
height:100%;" src="{{ route('Painel.PesquisaPatrimonial.veranexo', $data->CPF) }}"></iframe>

</div>
<!--Fim Modal Anexos  -->


          <td style="font-size: 10px"></td>
          <td style="font-size: 10px">{{ $data->OutraParte}}</td>
          <td style="font-size: 10px">{{ $data->Cliente}}</td>
          <td style="font-size: 10px">{{ $data->Pasta}}</td>
          <td style="font-size: 10px">{{ $data->Cobravel}}</td>
          <td style="font-size: 10px">{{ $data->TipoSolicitacao}}</td>
          <td style="font-size: 10px">R$ <?php echo number_format($data->Valor,2,",",".") ?></td>
          <td style="font-size: 10px">{{ date('d/m/Y', strtotime($data->DataSolicitacao)) }}</td>
          <td style="font-size: 10px"><span class="bullet yellow"></span>{{$data->Status}}</td>
          <td style="font-size: 10px">
      
          <div class="invoice-action">
          @if($data->StatusID != 1 && $data->TipoServico == "Pesquisa prévia")
          <a style="color: gray;"  href="{{route('Painel.PesquisaPatrimonial.solicitacao.pesquisaprevia.visualizarpesquisa', $data->Id)}}" class="invoice-action-view mr-4"><i class="material-icons">list</i></a>
          @endif

          @if(!empty($data->CadastroAdvwin))                       
          <a style="color: gray;"  href="{{route('Painel.PesquisaPatrimonial.solicitacao.buscacapa', $data->CPF)}}" class="invoice-action-view mr-4"><i class="material-icons">remove_red_eye</i></a>
          @endif   
          
          <a style="color: gray;"  href="#modalanexos{{$data->CPF}}" class="invoice-action-edit modal-trigger"><i class="material-icons">attach_file</i></a>
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
    <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/app-invoice.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/vendors.min.js') }}"></script>


 <script>
document.addEventListener("DOMContentLoaded", function () {
   $('.modal').modal();
});
</script>
