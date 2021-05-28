
<!DOCTYPE html>

<html class="loading" lang="en" data-textdirection="ltr">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="author" content="ThemeSelect">
    <title>Movimentação bancária | Portal PL&C</title>
    <link rel="apple-touch-icon" href="../../../app-assets/images/favicon/apple-touch-icon-152x152.png">
    <link rel="shortcut icon" type="image/x-icon" href="../../../app-assets/images/favicon/favicon-32x32.png">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/vendors.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/materialize.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/app-invoice.min.css') }}">
    <link rel="stylesheet" href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/vendors/magnific-popup/magnific-popup.css">

    

    <style>
    .html{
       color: black;
    }
    </style>

  </head>
  <!-- END: Head-->
  <body class="vertical-layout page-header-light vertical-menu-collapsible vertical-menu-nav-dark preload-transitions 2-columns  app-page " data-open="click" data-menu="vertical-menu-nav-dark" data-col="2-columns">


    <!-- BEGIN: Page Main-->
    <div id="main">
      <div class="row">
        <div class="content-wrapper-before blue-grey lighten-5"></div>
        <div class="col s12">
          <div class="container">

<section class="invoice-view-wrapper section">
  <div class="row">

    <div class="col xl12 m12 s12">
      <div class="card">
        <div class="card-content invoice-print-area">

          <div class="row invoice-date-number">

            <div class="col xl4 s12">
              <span class="invoice-number mr-1" style="font-size: 11px;">{{$banco_descricao}}</span>
            </div>

            <div class="col xl6 s12">
              <div class="invoice-date display-flex align-items-center flex-wrap">
                <div class="mr-3">
                  <small style="font-size: 11px;">Data ínicio:</small>
                  <span style="font-size: 11px;">{{ date('d/m/Y', strtotime($datainicio)) }}</span>
                </div>
                <div>
                  <small style="font-size: 11px;">Date fim:</small>
                  <span style="font-size: 11px;">{{ date('d/m/Y', strtotime($datafim)) }}</span>
                </div>

              </div>
            </div>
          </div>
          <!-- logo and title -->
          <div class="row mt-3 invoice-logo-title">

            <div class="col m6 s12 display-flex invoice-logo mt-1 push-m6">
            </div>

            <div class="col m6 s12 pull-m6">
            <img src="{{url('./public/imgs/logo.png')}}" alt="Logo PLC" height="116" width="200">
            </div>

          </div>

          <div class="invoice-product-details">
            <table class="striped responsive-table">
              <thead>
                <tr>
                <th style="font-size: 10px">Tipo</th>
                <th style="font-size: 10px">Número</th>
                <th style="font-size: 10px">Origem</th>
                <th style="font-size: 10px">Cliente/Fornecedor</th>
                <th style="font-size: 10px">Data</th>
                <th style="font-size: 10px">Moeda</th>
                <th style="font-size: 10px">Créditos</th>
                <th style="font-size: 10px">Débitos</th>
                <th style="font-size: 10px">Tipo</th>
                </tr>
              </thead>
              <tbody>
              @foreach($datas as $data)
                   <tr>
                   <td style="font-size: 9px">{{$data->Tipo}} </td>
				   <td style="font-size: 9px">{{$data->Numdoc}} </td>
				   <td style="font-size: 9px">{{$data->Origem}} </td>
				   <td style="font-size: 9px">{{$banco_descricao}} </td>
				   <td style="font-size: 9px">{{ date('d/m/Y', strtotime($data->Data)) }}</td>
				   <td style="font-size: 9px">{{$data->Moeda}} </td>
				   @if($data->Tipo == "R")
                   <td style="font-size: 9px">R$ 00,00 </td>
				   <td style="font-size: 9px">R$ <?php echo number_format($data->Valor,2,",",".") ?> </td>
                   @else 
				   <td style="font-size: 9px">R$ <?php echo number_format($data->Valor,2,",",".") ?> </td>
                   <td style="font-size: 9px">R$ 00,00 </td>
				   @endif
                   <td style="font-size: 9px">{{$data->Tipo}}</td>
                   </tr> 
                @endforeach
               
              </tbody>
            </table>
          </div>

          <div class="divider mt-3 mb-3"></div>

          <div class="invoice-subtotal">
            <div class="row">

             <div class="col m5 s12"> 
              </div>

              <div class="col xl4 m7 s12 offset-xl3">
               
                <ul>
                  
                  <li class="display-flex justify-content-between">
                    <span class="invoice-subtotal-title" style="font-size: 11px;">Saldo atual</span>
					@if($saldo < 0)
                    <h6 class="invoice-subtotal-value" style="font-size: 10px;color:red">R$ <?php echo number_format($saldo,2,",",".") ?></h6>
					@else 
					<h6 class="invoice-subtotal-value" style="font-size: 10px;color:green">R$ <?php echo number_format($saldo,2,",",".") ?></h6>
                    @endif
				  </li>

                </ul>
              </div>
            </div>
          </div>



       </div>
       </div>
       </div>

   
  </div>
</section>


    <script src="{{ asset('/public/materialize/js/vendors.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/app-invoice.min.js') }}"></script>


  </body>
</html>