<!DOCTYPE html>

<html class="loading" lang="pt-br" data-textdirection="ltr">
  <head>
    <meta http-equiv="Content-Language" content="pt-br">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="author" content="Portal PL&C">
    <title>Detalhamento nota indicador | Portal PL&C</title>
    <link rel="apple-touch-icon" href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/images/favicon/apple-touch-icon-152x152.png">
    <link rel="shortcut icon" href="{{ asset('/public/assets/imgs/casinha.png')}}" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/vendors.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/responsive.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/materialize.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/data-tables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/app-invoice.min.css') }}">


  </head>

  <body data-open="click" data-menu="vertical-modern-menu" data-col="2-columns">


   <!-- BEGIN: Page Main-->
   <div id="main" style="padding-left: 10px;padding-right: 10px;">

<section class="invoice-list-wrapper section">

<div class="card">
        <div class="card-content" style="padding: 10px;">
            <p class="caption mb-0">
            O quadro abaixo retrata o detalhamento do indicador mês a mês do sócio.
            </p>
        </div>
    </div>

    
    <a href="{{route('Painel.Gestao.Meritocracia.Hierarquia.NotasAdvogado.detalha', $advogado)}}"  style="background-color: gray; margin-top: -60px;margin-left: 1128px;" class="btn-floating btn-medium waves-effect waves-light tooltipped" data-position="left" data-tooltip="Clique aqui para voltar e visualizar o detalhamento desde sócio."><i class="material-icons">undo</i></a>
    {{-- <a href="{{route('Painel.Gestao.Meritocracia.Hierarquia.NotasAdvogado.detalha', $advogado)}}" style="background-color: gray; margin-top: -60px;margin-left: 1000px;" class="waves-effect waves-light  btn tooltipped" data-position="bottom" data-tooltip="Clique aqui para voltar e visualizar o detalhamento desde sócio."><i class="material-icons">undo</i></a> --}}



  <div class="responsive-table">
  <table id="data-table-simple" class="display">
  <thead>
        <tr>
          <th style="font-size: 13px"></th>
          <th style="font-size: 13px">Indicador</th>
          <th style="font-size: 13px">Mês</th>
          <th style="font-size: 13px" class="tooltipped" data-position="top" data-tooltip="Nota mínima.">Score 90</th>
          <th style="font-size: 13px">Score 100</th>
          <th style="font-size: 13px" class="tooltipped" data-position="top" data-tooltip="Nota máxima.">Score 120</th>
          <th style="font-size: 13px">Realizado mês</th>
          <th style="font-size: 13px">Realizado acumulado</th>
          <th style="font-size: 13px">Nota mês</th>
          <th style="font-size: 13px">Nota acumulada</th>
        </tr>
      </thead>

      <tbody>
      @foreach($notaDetalhadaPorObjetivo as $data)  
      @if($data->mes_id == $mesapuracao && $data->ano_referencia == $ano)
      <tr style="background-color: #D3D3D3">
          <td style="font-size: 12px;color:black;"></td>
          <td style="font-size: 12px;color:black;">{{$data->objetivo}}</td>
          <td style="font-size: 12px;color:black;">{{$data->mes}}</td>
          <td style="font-size: 12px;color:black;">{{$data->score90}}</td>
          <td style="font-size: 12px;color:black;">{{$data->meta}}</td>
          <td style="font-size: 12px;color:black;">{{$data->score120}}</td>
          <td style="font-size: 12px;color:black;">{{$data->realizado}}</td>
          <td style="font-size: 12px;color:black;">{{$data->nota_acumulada}}</td>
          <td style="font-size: 12px;color:black;">{{$data->consolidada_mes}}</td>
          <td style="font-size: 12px;color:black;">{{$data->nota_consolidada_acumulada}}</td>
        </tr>
       @else 
       <tr>
          <td style="font-size: 12px;"></td>
          <td style="font-size: 12px;">{{$data->objetivo}}</td>
          <td style="font-size: 12px;">{{$data->mes}}</td>
          <td style="font-size: 12px;">{{$data->score90}}</td>
          <td style="font-size: 12px;">{{$data->meta}}</td>
          <td style="font-size: 12px;">{{$data->score120}}</td>
          <td style="font-size: 12px;">{{$data->realizado}}</td>
          <td style="font-size: 12px;">{{$data->nota_acumulada}}</td>
          <td style="font-size: 12px;">{{$data->consolidada_mes}}</td>
          <td style="font-size: 12px;">{{$data->nota_consolidada_acumulada}}</td>
        </tr>
       @endif 

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




    <script src="{{ asset('/public/materialize/js/vendors.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/app-invoice.min.js') }}"></script>




  </body>
</html>