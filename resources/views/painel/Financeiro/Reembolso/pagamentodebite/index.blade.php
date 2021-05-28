@extends('painel.Layout.header')
@section('title') Reembolso - Faturamento de debite @endsection <!-- Titulo da pagina -->

@section('header') 
    <meta http-equiv="Content-Language" content="pt-br">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="author" content="Portal PL&C">
    <title>Reembolso - Faturamento de debite | Portal PL&C</title>
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
Faturamento de debite
@endsection

@section('submenu')
<li class="breadcrumb-item active" style="color: black;">Clientes aguardando geração de CPR
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
<form role="form" id="form" action="" method="POST" role="search" enctype="multipart/form-data" >
  {{ csrf_field() }}

  <div class="responsive-table">
    <table class="table invoice-data-table white border-radius-4 pt-1">
      <thead>
        <tr>
          <th style="font-size: 11px"></th>
          <th style="font-size: 11px">CNPJ</th>
          <th style="font-size: 11px">Razão</th>
          <th style="font-size: 11px">Grupo cliente</th>
          <th style="font-size: 11px">Grupo empreendimento</th>
          <th style="font-size: 11px">Grupo financeiro</th>
          <th style="font-size: 11px">Qtd. solicitações aguardando geração de CPR</th>
          <th style="font-size: 11px">Valor total</th>
          <th style="font-size: 11px"></th>
          <th style="font-size: 11px"></th>
        </tr>
      </thead>

      <tbody>
      @foreach($datas as $data)  


        <tr>

          <td style="font-size: 10px"></td>
          <td style="font-size: 10px">{{ $data->ClienteCodigo }}</td>
          <td style="font-size: 10px">{{ $data->ClienteRazao }}</td>
          <td style="font-size: 10px">{{ $data->GrupoCliente }}</td>
          <td style="font-size: 10px">{{ $data->GrupoEmpreendimento }}</td>
          <td style="font-size: 10px">{{ $data->GrupoFinanceiro }}</td>
          <td style="font-size: 10px">{{ $data->QuantidadeSolicitacoes }}</td>
          <td style="font-size: 10px">R$ <?php echo number_format($data->Valor,2,",",".") ?></td>
          <td style="font-size: 10px"></td>

          <td style="font-size: 10px">
          <div class="invoice-action">

          <a href="{{route('Painel.Financeiro.Reembolso.PagamentoDebite.solicitacoes', $data->ClienteCodigo)}}" class="invoice-action-view mr-4 tooltipped" data-position="left" data-tooltip="Clique aqui para visualizar as solicitações aguardando a geração de CPR."><i class="material-icons">assignment</i></a>
          </div>
          </td>

        </tr>
        @endforeach
        
        
      </tbody>
    </table>
  </div>
  </form>
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
