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


  <div class="responsive-table">
  <table id="data-table-simple" class="display">
  <thead>
  <tr>
          <th style="font-size: 12px"></th>
          <th style="font-size: 12px">Indicador</th>
          <th style="font-size: 12px">Peso</th>
          <th style="font-size: 12px">UOM</th>
          <th style="font-size: 12px" class="tooltipped" data-position="top" data-tooltip="Nota mínima.">Score 90</th>
          <th style="font-size: 12px">Score 100</th>
          <th style="font-size: 12px" class="tooltipped" data-position="top" data-tooltip="Nota máxima.">Score 120</th>
          <th style="font-size: 12px">Realizado acumulado</th>
          <th style="font-size: 12px">Score acumulado</th>
          <th style="font-size: 12px"></th>
        </tr>
      </thead>

      <tbody>
      @foreach($indicadoresnota as $data)  
        <tr>


          <td style="font-size: 11px"></td>
          <td style="font-size: 11px">{{$data->objetivo}}</td>
          <td style="font-size: 11px">{{$data->peso}}</td>
          <td style="font-size: 11px">{{$data->uom}}</td>
          <td style="font-size: 11px">{{$data->score90}}</td>
          <td style="font-size: 11px">{{$data->meta}}</td>
          <td style="font-size: 11px">{{$data->score120}}</td>
          <td style="font-size: 11px">{{$data->realizado}}</td>
          <td style="font-size: 11px" class="tooltipped" data-position="top" data-tooltip="Se a nota for 0.00 não atingiu a nota mínima.">{{$data->nota}}</td>

          <td style="font-size: 11px">
      
          <div class="invoice-action">
          <a href="{{route('Painel.Gestao.Meritocracia.detalhamentoobjetivo', $data->id_objetivo)}}" class="invoice-action-view mr-4 modal-trigger tooltipped" data-position="top" data-tooltip="Clique aqui para visualizar o detalhamento do indicador mês a mês. "><i class="material-icons">list</i></a>
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




    <script src="{{ asset('/public/materialize/js/vendors.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/app-invoice.min.js') }}"></script>
    <!-- <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script>

$(document).ready(function() {
    $('#example').DataTable( {
        "paging":   false,
    } );
} );

    </script> -->




  </body>
</html>