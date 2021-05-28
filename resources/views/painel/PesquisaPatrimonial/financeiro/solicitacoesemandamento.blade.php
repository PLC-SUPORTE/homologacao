@extends('painel.Layout.header')
@section('title') Solicitações em andamento @endsection <!-- Titulo da pagina -->

@section('header') 
    <title>Solicitações em andamento | Portal PL&C</title>
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
<li class="breadcrumb-item"><a href="{{ route('Painel.PesquisaPatrimonial.financeiro.index') }}">Dashboard</a>
</li>
<li class="breadcrumb-item active" style="color: black;">Solicitações em andamento
</li>
@endsection
@section('body')
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
          <th style="font-size: 11px"><span>#</span></th>
          <th style="font-size: 11px">Pesquisado</th>
          <th style="font-size: 11px">Cliente</th>
          <th style="font-size: 11px">Pasta</th>
          <th style="font-size: 11px">Cobrável</th>
          <th style="font-size: 11px">Valor</th>
          <th style="font-size: 11px">Data</th>
          <th style="font-size: 11px">Status</th>
          <th style="font-size: 11px"></th>
        </tr>
      </thead>

      <tbody>
      @foreach($datas as $data)  
        <tr>
          <td style="font-size: 10px"></td>
          <td style="font-size: 10px">{{ $data->Id }}</td>
          <td style="font-size: 10px">{{ $data->OutraParte}}</td>
          <td style="font-size: 10px">{{ $data->Cliente}}</td>
          <td style="font-size: 10px">{{ $data->Pasta}}</td>
          <td style="font-size: 10px">{{ $data->Cobravel}}</td>
          <td style="font-size: 10px">R$ <?php echo number_format($data->Valor,2,",",".") ?></td>
          <td style="font-size: 10px">{{ date('d/m/Y', strtotime($data->DataSolicitacao)) }}</td>
          <td style="font-size: 10px"><span class="bullet yellow"></span>{{$data->Status}}</td>
          <td style="font-size: 10px">
      
          <div class="invoice-action">
          
          <a href="{{route('Painel.PesquisaPatrimonial.nucleo.capa', ['codigo' => $data->CPF, 'numero' => $data->Id])}}" class="invoice-action-view mr-4 tooltipped" data-position="bottom" data-tooltip="Clique aqui para visualizar a capa deste pesquisado."><i class="material-icons">remove_red_eye</i></a>
                    
          @if(!empty($data->CadastroAdvwin))                       
          <a href="{{route('Painel.PesquisaPatrimonial.solicitacao.capa', $data->OutraParte)}}" class="invoice-action-view mr-4 tooltipped" data-position="left" data-tooltip="Clique aqui para visualizar a capa deste pesquisado."  ><i class="material-icons">remove_red_eye</i></a>
          @endif   
          
          @if(!empty($data->anexo))
          <a href="{{route('Painel.PesquisaPatrimonial.anexo', $data->anexo)}}" class="invoice-action-edit tooltipped" data-position="bottom" data-tooltip="Clique aqui para baixar o anexo desta solicitação de pesquisa patrimonial."><i class="material-icons">attach_file</i></a>
          @endif

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

    @section('scripts')
    <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/app-invoice.min.js') }}"></script>
@endsection