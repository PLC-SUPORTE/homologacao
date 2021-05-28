
<!DOCTYPE html>

<html class="loading" lang="en" data-textdirection="ltr">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Materialize is a Material Design Admin Template,It's modern, responsive and based on Material Design by Google.">
    <meta name="keywords" content="materialize, admin template, dashboard template, flat admin template, responsive admin template, eCommerce dashboard, analytic dashboard">
    <meta name="author" content="ThemeSelect">
    <title>App Invoice | Materialize - Material Design Admin Template</title>
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

    <div class="col xl9 m8 s12">
      <div class="card">
        <div class="card-content invoice-print-area">

          <div class="row invoice-date-number">

            <div class="col xl4 s12">
              <!-- <span class="invoice-number mr-1">Invoice#</span>
              <span>000756</span> -->
            </div>

            <div class="col xl8 s12">
              <div class="invoice-date display-flex align-items-center flex-wrap">
                <div class="mr-3">
                  <small>Data ínicio:</small>
                  <span>{{ date('d/m/Y', strtotime($datainicio)) }}</span>
                </div>
                <div>
                  <small>Date fim:</small>
                  <span>{{ date('d/m/Y', strtotime($datafim)) }}</span>
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

          <div class="divider mb-3 mt-3"></div>

          <div class="row invoice-info">
            <div class="col m12 s12">

            <p>PL&C Advogados, {{ date('d', strtotime($carbon)) }} de {{ date('M', strtotime($carbon)) }} de {{ date('Y', strtotime($carbon)) }}.
            <br><br>

                     
            <strong>
            <p>Á</p>
            <p>{{$datas->ClienteRazao}} </p>
            <p>CNPJ: {{$datas->ClienteCodigo}} </p>
                     
            <br><br>
            <u><p>REF: SOLICITAÇÃO DE PESQUISA PATRIMONIAL </p></u>
            </strong>

            <br><br>
            <p>Para transparência e vosso controle, V.Sa, antecipou ao nosso escritório o valor de <strong> R$ 6.375,38 (seis mil trezentos e setenta e cinco reais e trinta e oito centavos) </strong> para realização de trabalhos, empenhamos até o momento o valor de <strong> R$ 6.324,45 (seis mil trezentos e vinte e quatro reais e quarenta e cinco centavos) </strong> cujas notas de despesas seguem em anexo. Como podemos verificar há um saldo de <strong> R$ 50,93 (cinquenta reais e noventa e três centavos). </strong> 
            </p>

            <br>

            <p>Sem mais para o momento colocamo-nos à disposição para quaisquer esclarecimentos.</p>

            <br>

            <p>Atenciosamente,</p>

            <br>

            <p>PORTELA, LIMA, LOBATO & COLEN SOCIEDADE DE ADVOGADOS</p>

            </div>
           
          </div>
          <div class="divider mb-3 mt-3"></div>

          <div class="invoice-product-details">
            <table class="striped responsive-table">
              <thead>
                <tr>
                <th style="font-size: 10px">Número</th>
                   <th style="font-size: 10px">Cliente</th>
                   <th style="font-size: 10px">Data</th>
                   <th style="font-size: 10px">Tipo de debite</th>
                   <th style="font-size: 10px">Valor</th>
                   <th style="font-size: 10px">Cliente código</th>
                   <th style="font-size: 10px">Advogado nome</th>
                   <th style="font-size: 10px">Nº Pasta</th>
               <th style="font-size: 10px">Usuário</th>
                </tr>
              </thead>
              <tbody>
              @foreach($debites as $debite)
                   <tr>
                   <td style="font-size: 9px">{{$debite->Numero}} </td>
                   <td style="font-size: 9px"><?php echo mb_convert_case($debite->ClienteRazao, MB_CASE_TITLE, "UTF-8")?> </td>
                   <td style="font-size: 9px">{{ date('d/m/Y', strtotime($debite->Data)) }}</td>
                   <td style="font-size: 9px">{{$debite->TipoDebite}} </td>
                   <td style="font-size: 9px">R$ <?php echo number_format($debite->Valor,2,",",".") ?> </td>
                   <td style="font-size: 9px">{{$debite->ClienteCodigo}}</td>
                   <td style="font-size: 9px"><?php echo mb_convert_case($debite->AdvogadoNome, MB_CASE_TITLE, "UTF-8")?></td>
                   <td style="font-size: 9px">{{$debite->Pasta}}</td>
                   <td style="font-size: 9px">{{$debite->Usuario}}</td>
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
                    <span class="invoice-subtotal-title" style="font-size: 11px;">Adiantamento</span>
                    <h6 class="invoice-subtotal-value" style="font-size: 10px;">R$ </h6>
                  </li>

                  <li class="display-flex justify-content-between">
                    <span class="invoice-subtotal-title" style="font-size: 11px;">Despesas</span>
                    <h6 class="invoice-subtotal-value" style="font-size: 10px;">R$ </h6>
                  </li>

                  <li class="divider mt-2 mb-2"></li>
                  
                  <li class="display-flex justify-content-between">
                    <span class="invoice-subtotal-title" style="font-size: 11px;">Saldo</span>
                    <h6 class="invoice-subtotal-value" style="font-size: 10px;">R$ </h6>
                  </li>

                </ul>
              </div>
            </div>
          </div>

          <div class="divider mt-3 mb-3"></div>

          <!--Começa as Imagens dos boletos/nfs/comprovantes --> 

          <div class="masonry-gallery-wrapper">
      <div class="popup-gallery">
        <div class="gallery-sizer"></div>
        <div class="row">

          <div class="col s12 m6 l4 xl2">
            <div>
              <a href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/images/gallery/3.png" title="The Cleaner">
                <!-- <img src="https://pixinvent.com/materialize-material-design-admin-template/app-assets/images/gallery/3.png" class="responsive-img mb-10" alt=""> -->
                <img src="{{ url('storage/pesquisapatrimonial/imovel/202006241808325ef3c0d074901.jpg') }}" class="responsive-img mb-10" alt="" width="600" height="400" />
                <!-- <embed src="{{ url('storage/pesquisapatrimonial/imovel/202010261000455f96c87d233fd.pdf') }}" type="application/pdf"   height="700px" width="500"> -->

              </a>
            </div>
          </div>


          <div class="col s12 m6 l4 xl2">
            <div>
              <a href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/images/gallery/3.png" title="The Cleaner">
                <img src="https://pixinvent.com/materialize-material-design-admin-template/app-assets/images/gallery/3.png" class="responsive-img mb-10" alt="">
              </a>
            </div>
          </div>

          </div>
          </div>
          </div>

       <!--Começa as Imagens dos boletos/nfs/comprovantes --> 

       </div>
       </div>
       </div>

    <div class="col xl3 m4 s12">
      <div class="card invoice-action-wrapper">
        <div class="card-content">

          <div class="invoice-action-btn">
            <a href="#" class="btn-block btn waves-effect waves-light invoice-print" style="background-color:gray;font-size:11px;">
              <span>Imprimir</span>
            </a>
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
    <script src="https://pixinvent.com/materialize-material-design-admin-template/app-assets/vendors/magnific-popup/jquery.magnific-popup.min.js"></script>
    <script src="https://pixinvent.com/materialize-material-design-admin-template/app-assets/vendors/imagesloaded.pkgd.min.js"></script>
    <script src="https://pixinvent.com/materialize-material-design-admin-template/app-assets/js/scripts/media-gallery-page.min.js"></script>


  </body>
</html>