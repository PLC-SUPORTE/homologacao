@extends('painel.Layout.header')

@section('title') Minhas guias de custa - Pesquisa Patrimonial @endsection <!-- Titulo da pagina -->

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
Guias de custa
@endsection

@section('submenu')
<li class="breadcrumb-item active" style="color: black;">Guias de custa
</li>
@endsection

@section('body')
     <div>
      <div class="row">
        <div class="content-wrapper-before blue-grey lighten-5"></div>

 <center>
   <div id="loading">
      <div class="wrapper">
      <div class="circle circle-1"></div>
      <div class="circle circle-1a"></div>
      <div class="circle circle-2"></div>
      <div class="circle circle-3"></div>
     </div>
     <h1 style="text-align: center;">Aguarde, estamos carregando suas guias de custa...&hellip;</h1>
     </div>
  </center>   


        <div class="col s12" id="corpodiv">

          <div class="container">
            <div class="section">

<section class="invoice-list-wrapper section">

  <div class="invoice-filter-action mr-3">
    <a href="{{route('Painel.Financeiro.RelatorioBancario.exportar')}}" class="btn waves-effect waves-light invoice-export border-round z-depth-4" style="background-color: gray">
      <i class="material-icons">picture_as_pdf</i>
      <span class="hide-on-small-only">Exportar</span>
    </a>
  </div>

  <div class="responsive-table">
    <table class="table invoice-data-table white border-radius-4 pt-1">
      <thead>
        <tr>
          <th style="font-size: 12px"></th>
          <th style="font-size: 12px"><span>#</span></th>
          <th style="font-size: 12px">Número debite</th>
          <th style="font-size: 12px">Cliente</th>
          <th style="font-size: 12px">Pasta</th>
          <th style="font-size: 12px">Setor do PL&C</th>
          <th style="font-size: 12px">Tipo debite</th>
          <th style="font-size: 12px">Identificação guia</th>
          <th style="font-size: 12px">Valor</th>
          <th style="font-size: 12px">Data</th>
          <th style="font-size: 12px">Status</th>
          <th style="font-size: 12px">Anexo</th>
        </tr>
      </thead>

      <tbody>
      @foreach($datas as $data)  
        <tr>
          <td style="font-size: 11px"></td>
          <td style="font-size: 11px"></td>
          <td style="font-size: 11px">{{ $data->NumeroDebite }}</td>
          <td style="font-size: 11px">{{ $data->ClienteRazao }}</td>
          <td style="font-size: 11px">{{ $data->Pasta }}</td>
          <td style="font-size: 11px">{{ $data->Setor }}</td>
          <td style="font-size: 11px">{{ $data->TipoDebite }}</td>
          <td style="font-size: 11px">{{ $data->Identificacao }}</td>
          <td style="font-size: 11px">R$ <?php echo number_format($data->Valor,2,",",".") ?></td>
          <td style="font-size: 11px">{{ date('d/m/Y', strtotime($data->Data)) }}</td>
          @if($data->Status == 4)
          <td style="font-size: 11px"><span class="bullet yellow"></span>Aguardando pagamento</td>
          @else 
          <td style="font-size: 11px"><span class="bullet green"></span>Guia paga</td>
          @endif
          <td style="font-size: 11px">

          @if(!empty($data->Arq_nick))
          <a href="{{route('Painel.Financeiro.gerarAnexoGuia', $data->Arq_nick)}}" class="invoice-action-edit"><i class="material-icons">attach_file</i></a>
          @endif
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
$(document).ready(function(){

   $("#corpodiv").hide();
});
</script>

<script>
setTimeout(function() {
   $('#loading').fadeOut('fast');
   $("#corpodiv").show();
}, 4000);
</script>
