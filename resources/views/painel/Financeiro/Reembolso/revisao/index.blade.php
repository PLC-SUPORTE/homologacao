@extends('painel.Layout.header')
@section('title') Revisar solicitações reembolso @endsection <!-- Titulo da pagina -->

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


    <style>
    * {
      box-sizing: border-box;
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
Solicitação de Reembolso
@endsection

@section('submenu')
<li class="breadcrumb-item active" style="color: black;">Solicitações de reembolso
</li>
@endsection

@section('body')
   <div>
      <div class="row">
        <div class="content-wrapper-before blue-grey lighten-5"></div>

        <div class="col s12" id="corpodiv">

          <div class="container">
            <div class="section">

<section class="invoice-list-wrapper section">

  <div class="invoice-filter-action mr-3">
  <a href="{{ route('Painel.Financeiro.Reembolso.Revisao.historico') }}" class="waves-light btn" style="color: white; background-color: gray; font-size: 11px; border-radius: 50px;">
  <i class="material-icons left">list</i>Histórico</a>  
  </div>


  <div class="responsive-table">
    <table class="table invoice-data-table white border-radius-4 pt-1">
      <thead>
        <tr>
          <th style="font-size: 11px"></th>
          <th style="font-size: 11px"></th>
          <th style="font-size: 11px">Solicitante</th>
          <th style="font-size: 11px">E-mail</th>
          <th style="font-size: 11px">Código</th>
          <th style="font-size: 11px">Qtd. solicitações abertas</th>
          <th style="font-size: 11px">Valor total</th>
          <th style="font-size: 11px"></th>
          <th style="font-size: 11px"></th>
        </tr>
      </thead>

      <tbody>
      @foreach($datas as $data)  
        <tr>

          <td style="font-size: 10px"></td>
          <td style="font-size: 10px"></td>
          <td style="font-size: 10px">{{ $data->SolicitanteNome }}</td>
          <td style="font-size: 10px">{{ $data->SolicitanteEmail }}</td>
          <td style="font-size: 10px">{{ $data->SolicitanteCodigo }}</td>
          <td style="font-size: 10px">{{ $data->QuantidadeSolicitacoes }}</td>
          <td style="font-size: 10px">R$ <?php echo number_format($data->Valor,2,",",".") ?></td>
          <td style="font-size: 10px"></td>

          <td style="font-size: 10px">
          <div class="invoice-action">

          <a href="{{route('Painel.Financeiro.Reembolso.Revisao.solicitacoessocio', $data->SolicitanteCodigo)}}" class="invoice-action-view mr-4 tooltipped" data-position="left" data-tooltip="Clique aqui para visualizar as solicitações aguardando sua revisão deste sócio."><i class="material-icons">assignment</i></a>
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