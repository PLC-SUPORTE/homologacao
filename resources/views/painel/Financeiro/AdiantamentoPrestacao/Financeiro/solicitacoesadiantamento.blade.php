@extends('painel.Layout.header')

@section('title') Solicitações de adiantamento @endsection <!-- Titulo da pagina -->

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
Adiantamento/Prestação de conta
@endsection

@section('submenu')
<li class="breadcrumb-item"><a href="{{ route('Painel.Financeiro.AdiantamentoPrestacao.Financeiro.index') }}">Saldo por usuário</a>
</li>
<li class="breadcrumb-item active" style="color: black;">Solicitações de adiantamento
</li>
@endsection

@section('body')
   <div>
      <div class="row">
        <div class="content-wrapper-before blue-grey lighten-5"></div>
        <div class="col s12">
          <div class="container">

<section class="invoice-list-wrapper section">

<div class="invoice-filter-action mr-3">
   <a href="{{ route('Painel.Financeiro.AdiantamentoPrestacao.Financeiro.index') }}" class="waves-light btn" style="color: white; background-color: gray; font-size: 11px; border-radius: 50px;">
   <i class="material-icons left">keyboard_backspace</i>Voltar</a>
</div>

<div class="invoice-filter-action mr-3">
   <a href="{{ route('Painel.Financeiro.AdiantamentoPrestacao.Financeiro.historico', $usuario_cpf) }}" class="waves-light btn" style="color: white; background-color: gray; font-size: 11px; border-radius: 50px;">
   <i class="material-icons left">list</i>Histórico</a>
</div>


  <div class="responsive-table">
    <table class="table invoice-data-table white border-radius-4 pt-1">
      <thead>
        <tr>
          <th style="font-size: 11px"></th>
          <th style="font-size: 11px">Solicitante</th>
          <th style="font-size: 11px">CPR</th>
          <th style="font-size: 11px">Pasta</th>
          <th style="font-size: 11px">Motivo</th>
          <th style="font-size: 11px">Data da solicitação</th>
          <th style="font-size: 11px">Valor</th>
          <th style="font-size: 11px">Status</th>
          <th style="font-size: 11px"></th>


        </tr>
      </thead>

      <tbody>

      @foreach($datas as $data)
        <tr>
        <td style="font-size: 10px"></td>
        <td style="font-size: 10px"><?php echo mb_convert_case($data->solicitante_nome, MB_CASE_TITLE, "UTF-8")?></td>
        <td style="font-size: 10px">{{$data->CPR}}</td>
        <td style="font-size: 10px">{{$data->Pasta}}</td>
        <td style="font-size: 10px">{{$data->motivo}}</td>
        <td style="font-size: 10px">{{ date('d/m/Y', strtotime($data->data)) }}</td>
        <td style="font-size: 10px">R$ <?php echo number_format($data->valor_original,2,",",".") ?> </td>
        <td style="font-size: 10px"><span class="bullet yellow"></span>{{$data->status}}</td>
        <td style="font-size: 10px">

        <div class="invoice-action">
        @if($data->StatusID == 4)
        <a href="{{ route('Painel.Financeiro.AdiantamentoPrestacao.Financeiro.revisarsolicitacao', $data->id) }}" class="tooltipped invoice-action-view mr-4" data-position="bottom" data-tooltip="Clique aqui para realizar a revisão desta solicitação de adiantamento."><i class="material-icons">remove_red_eye</i></a>
        @elseif($data->StatusID == 5)
        <a href="{{route('Painel.Financeiro.AdiantamentoPrestacao.Financeiro.revisarprestacaodeconta', ['cpf' => $usuario_cpf, 'id' => $data->id])}}" class="tooltipped invoice-action-view mr-4" data-position="bottom" data-tooltip="Clique aqui para realizar a revisão do lançamento de prestação de conta."><i class="material-icons">remove_red_eye</i></a>
        @elseif($data->StatusID == 7)
        <a href="{{ route('Painel.Financeiro.AdiantamentoPrestacao.Financeiro.transferencia', $data->id) }}" class="tooltipped invoice-action-view mr-4" data-position="bottom" data-tooltip="Clique aqui para realizar a transferência desta solicitação de adiantamento."><i class="material-icons">remove_red_eye</i></a>
        @elseif($data->StatusID == 8)
        <a href="{{route('Painel.Financeiro.AdiantamentoPrestacao.Financeiro.baixa', ['cpf' => $usuario_cpf, 'id' => $data->id])}}" class="tooltipped invoice-action-view mr-4" data-position="bottom" data-tooltip="Clique aqui para realizar a baixa do lançamento de prestação de conta."><i class="material-icons">remove_red_eye</i></a>
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


<script>
document.addEventListener("DOMContentLoaded", function () {
   $('.modal').modal();
});

</script>

@endsection