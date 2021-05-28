
<!DOCTYPE html>

<html class="loading" lang="en" data-textdirection="ltr">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="author" content="ThemeSelect">
    <title>Nota de debito - {{$numdoc}}</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">



  </head>
  <!-- END: Head-->
  <body class="vertical-layout page-header-light vertical-menu-collapsible vertical-menu-nav-dark preload-transitions 2-columns  app-page " data-open="click" data-menu="vertical-menu-nav-dark" data-col="2-columns">


    <!-- BEGIN: Page Main-->
    <div id="main">

      <div class="row" id="corpodiv">


        <div class="content-wrapper-before blue-grey lighten-5"></div>
        <div class="col s12">
          <div class="container">

<section class="invoice-view-wrapper section">
  <div class="row">


    <div class="col xl9 m8 s12">
      <div class="card">
        <div class="card-content invoice-print-area">

          <div class="row invoice-date-number">

            <div class="col xl4 s12" style="margin-left: 82%;">
              <span class="invoice-number mr-1">Fatura#</span>
              <span>{{$numdoc}}</span>
            </div>

            <div class="col xl8 s12">
              <div class="invoice-date display-flex align-items-center flex-wrap">
  

              </div>
            </div>
          </div>

          <div class="row mt-3 invoice-logo-title">

            <div class="col m6 s12 display-flex invoice-logo mt-1 push-m6">
            </div>

            <div class="col m6 s12 pull-m6">
            <img src="https://portal.plcadvogados.com.br/portal/public/imgs/logo.jpg" alt="Logo PLC" height="160" width="210">
            </div>

          </div>

          <div class="divider mb-3 mt-3"></div>

          <div class="row invoice-info">
            <div class="col m12 s12">

            <p>Belo Horizonte, {{ date('d', strtotime($carbon)) }} de Março de {{ date('Y', strtotime($carbon)) }}.
            <br><br>

                     
            <strong>
            <p>Á</p>
            <p><?php echo mb_convert_case($cliente, MB_CASE_TITLE, "UTF-8")?> </p>
            <p>CNPJ: {{$clientecodigo}} </p>
                     
            <br>
            <u><p>REF: ADIANTAMENTO DE DESPESAS </p></u>
            </strong>

            <br><br>
            <p>Solicitamos o adiantamento do valor de <strong> R$ <?php echo number_format($valor,2,",",".") ?> ({{$extenso}}) </strong>  para as despesas referente a pesquisa patrimonial.</strong> 
            </p>

            <br>

            <div class="invoice-product-details">
            <table class="striped responsive-table">
              <thead>
                <tr>
                <th style="font-size: 10px">Número</th>
                <th style="font-size: 10px">Operação</th>
                <th style="font-size: 10px">Pesquisado</th>
                <th style="font-size: 10px">CPF/CNPJ</th>
                <th style="font-size: 10px">Número do processo</th>
                <th style="font-size: 10px">Nome fantasia</th>
                <th style="font-size: 10px">Data</th>
                <th style="font-size: 10px">Solicitante</th>
                <th style="font-size: 10px">Tipo da solicitação</th>
                <th style="font-size: 10px">Tipo de serviço</th>
                </tr>
              </thead>

              <tbody>
              @foreach($datas as $data)
                   <tr>
                   <td style="font-size: 9px">{{$data->id}} </td>
                   <td style="font-size: 9px"></td>
                   <td style="font-size: 9px"><?php echo mb_convert_case($data->PesquisadoNome, MB_CASE_TITLE, "UTF-8")?></td>
                   <td style="font-size: 9px">{{$data->Codigo}} </td>
                   <td style="font-size: 9px">{{$data->NumeroProcesso}} </td>
                   <td style="font-size: 9px"><?php echo mb_convert_case($data->ClienteRazao, MB_CASE_TITLE, "UTF-8")?></td>
                   <td style="font-size: 9px">{{ date('d/m/Y', strtotime($data->Data)) }}</td>
                   <td style="font-size: 9px"><?php echo mb_convert_case($data->SolicitanteNome, MB_CASE_TITLE, "UTF-8")?></td>
                   <td style="font-size: 9px">{{$data->TipoSolicitacao}}</td>
                   <td style="font-size: 9px">{{$data->TipoServico}}</td>
                   </tr> 
              @endforeach
              </tbody>

            </table>
          </div>

          <br><br>

          <p>Assim aguardamos o adiantamento das despesas a ser efetuada em <strong> Boleto – vencimento {{ date('d/m/Y', strtotime($datavencimento_string)) }} </strong>, de titularidade da Portela, Lima, Lobato & Colen Sociedade de Advogados, inscrita no CNPJ/MF sob o nº 07.928.834/0001-00</p>
          <p>Favor realizar o pagamento do boleto o quanto antes, permitindo o prosseguimento do fluxo da equipe de pesquisa patrimonial do PL&C.</p>
          <p>Sem mais para o momento colocamo-nos à disposição para quaisquer esclarecimentos.</p>
          <p>Atenciosamente,</p>
          <p>PORTELA, LIMA, LOBATO & COLEN SOCIEDADE DE ADVOGADOS</p>
          <br><br>



            </div>
           
          </div>

          </div>

          </div>

          </div>

    
    </div>
  </div>

</section>




  </body>
</html>