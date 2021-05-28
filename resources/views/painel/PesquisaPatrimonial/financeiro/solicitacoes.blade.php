@extends('painel.Layout.header')
<?php $search = 2 ?>
@section('title') Solicitações pesquisa patrimonial @endsection <!-- Titulo da pagina -->

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
<li class="breadcrumb-item"><a href="{{ route('Painel.PesquisaPatrimonial.financeiro.index') }}">Dashboard</a>
</li>
<li class="breadcrumb-item active" style="color: black;">Solicitações
</li>
@endsection
@section('body')
   <div>
      <div class="row">
        <div class="content-wrapper-before blue-grey lighten-5"></div>
        <div class="col s12">
          <div class="container">

<section class="invoice-list-wrapper section">

  <!-- <div class="invoice-filter-action mr-3">
    <a href="#" class="btn waves-effect waves-light invoice-export border-round z-depth-4" style="background-color: gray">
      <i class="material-icons left">picture_as_pdf</i>
      <span class="hide-on-small-only">Exportar</span>
    </a>
  </div> -->


  <div class="responsive-table">
    <table class="table invoice-data-table white border-radius-4 pt-1">
      <thead>
        <tr>
          <th></th>
          <th style="font-size: 11px">Número</th>
          <th style="font-size: 11px">Código</th>
          <th style="font-size: 11px">Pesquisado</th>
          <th style="font-size: 11px">Cliente</th>
          <th style="font-size: 11px">Pasta</th>
          <th style="font-size: 11px">Cobrável</th>
          <th style="font-size: 11px">Classificação</th>
          <th style="font-size: 11px">Valor</th>
          <th style="font-size: 11px">Data</th>
          <th style="font-size: 11px">Status</th>
          <th style="font-size: 11px"></th>
        </tr>
      </thead>

      <tbody>
      @foreach($datas as $data)  
        <tr>
          <th></th>
          <th style="font-size: 11px">{{ $data->Id}}</th>
          <td style="font-size: 10px">{{ $data->CPF }}</td>
          <td style="font-size: 10px">{{ $data->OutraParte}}</td>
          <td style="font-size: 10px">{{ $data->Cliente}}</td>
          <td style="font-size: 10px">{{ $data->Pasta}}</td>
          <td style="font-size: 10px">{{ $data->Cobravel}}</td>
          <td style="font-size: 10px">{{ $data->Classificacao}}</td>
          <td style="font-size: 10px">R$ <?php echo number_format($data->Valor,2,",",".") ?></td>
          <td style="font-size: 10px">{{ date('d/m/Y', strtotime($data->DataSolicitacao)) }}</td>
          @if($data->StatusID == 15 || $data->StatusID == 17)
          <td style="font-size: 10px"><span class="bullet yellow"></span>{{ $data->Status}} </td>
          @elseif($data->StatusID == 18)
          <td style="font-size: 10px"><span class="bullet red"></span>Solicitação cancelada</td>
          @else 
          <td style="font-size: 10px"><span class="bullet yellow"></span>{{$data->Status}}</td>
          @endif
          <td style="font-size: 10px">
      
          <div class="invoice-action">

          @if($data->StatusID == 1)
          <a style="color: gray;"  href="{{route('Painel.PesquisaPatrimonial.financeiro.alterarcobranca', $data->Id)}}" class="invoice-action-view mr-4 tooltipped" data-position="bottom" data-tooltip="Clique aqui para alterar o fluxo de cobrança desta solicitação de pesquisa patrimonial."><i class="material-icons">lock_open</i></a>
          @elseif($data->StatusID == 10)
          <a href="{{route('Painel.PesquisaPatrimonial.financeiro.avaliar', $data->Id)}}" class="invoice-action-view mr-4 tooltipped" data-position="bottom" data-tooltip="Clique aqui para revisar está solicitação de adiantamento."><i class="material-icons">assignment_late</i></a>
          @elseif($data->StatusID == 11)
          <a href="{{route('Painel.PesquisaPatrimonial.financeiro.formapagamento', $data->Id)}}" class="invoice-action-view mr-4 tooltipped" data-position="bottom" data-tooltip="Clique aqui para definir a forma de pagamento desta solicitação de adiantamento." ><i class="material-icons">view_list</i></a>
          @elseif($data->StatusID == 19 || $data->StatusID == 20)
          <a style="color: gray;" href="{{route('Painel.PesquisaPatrimonial.financeiro.ficha', $data->Id)}}" class="invoice-action-view mr-4 tooltipped" data-position="bottom" data-tooltip="Clique aqui para visualizar a ficha financeira."><i class="material-icons">open_in_new</i></a>
          @endif

          @if(!empty($data->CadastroAdvwin))                       
          <a href="{{route('Painel.PesquisaPatrimonial.solicitacao.capa', $data->CPF)}}" class="invoice-action-view mr-4 tooltipped" data-position="bottom" data-tooltip="Clique aqui para visualizar a capa desta pesquisado."><i class="material-icons">remove_red_eye</i></a>
          @endif
          @if(!empty($data->anexo))
          <a style="color: gray;"  href="{{route('Painel.PesquisaPatrimonial.anexo', $data->anexo)}}" class="invoice-action-edit tooltipped" data-position="bottom" data-tooltip="Clique aqui para baixar o anexo desta solicitação."><i class="material-icons">attach_file</i></a>
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

    @endsection

    @section('scripts')
    <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/app-invoice.min.js') }}"></script>
@endsection