@extends('painel.Layout.header')
@section('title') Acompanhamento de cobrança @endsection <!-- Titulo da pagina -->

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
<li class="breadcrumb-item"><a href="{{ route('Painel.PesquisaPatrimonial.nucleo.index') }}">Dashboard</a>
</li>
<li class="breadcrumb-item"><a href="{{ route('Painel.PesquisaPatrimonial.nucleo.boleto.index') }}">Liberar cobrança ao cliente</a>
</li>
<li class="breadcrumb-item active" style="color: black;">Acompanhamento de cobrança
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
          <th style="font-size: 11px">Cliente</th>
          <th style="font-size: 11px">CPF/CNPJ</th>
          <th style="font-size: 11px">Nosso número</th>
          <th style="font-size: 11px">Nº do documento</th>
          <th style="font-size: 11px">CPR</th>
          <th style="font-size: 11px">Data de vencimento</th>
          <th style="font-size: 11px">Data da baixa</th>
          <th style="font-size: 11px">Valor</th>
          <th style="font-size: 11px">Remessa</th>
          <th style="font-size: 11px"></th>
        </tr>
      </thead>

      <tbody>
      @foreach($datas as $data)  
        <tr>
          <td style="font-size: 10px"></td>
          <td style="font-size: 10px">{{$data->ClienteRazao}}</td>
          <td style="font-size: 10px">{{$data->ClienteCodigo}}</td>
          <td style="font-size: 10px">{{$data->NossoNumero}}</td>
          <td style="font-size: 10px">{{$data->NumeroDocumento}}</td>
          <td style="font-size: 10px">{{$data->CPR}}</td>
          <td style="font-size: 10px">{{ date('d/m/Y', strtotime($data->DataVencimento)) }}</td>
          @if($data->DataBaixa == null)         
          <td style="font-size: 10px"></td>
          @else 
          <td style="font-size: 10px">{{ date('d/m/Y', strtotime($data->DataBaixa)) }}</td>
          @endif
          <td style="font-size: 10px">R$ <?php echo number_format($data->Valor,2,",",".") ?></td>
          @if($data->Remessa != null)
          <td style="font-size: 10px;color:green;"><span class="bullet green"></span>Remessa enviada</td>
          @else 
          <td style="font-size: 10px;color:red;"><span class="bullet red"></span>Remessa não enviada</td>
          @endif

          <td style="font-size: 10px">
      
          <div class="invoice-action">
          <a href="{{route('Painel.PesquisaPatrimonial.nucleo.boleto.baixarboleto', $data->CPR)}}" class="invoice-action-view mr-4 tooltipped modal-trigger" data-position="bottom" data-tooltip="Clique aqui para visualizar o anexo desta solicitação de pesquisa patrimonial."><i class="material-icons">attach_file</i></a>


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

