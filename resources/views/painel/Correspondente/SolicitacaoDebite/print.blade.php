
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>PL&C Advogados - Relatório</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="{{ asset('/public/AdminLTE/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('/public/dminLTE/bower_components/font-awesome/css/font-awesome.min.css') }}">
  <link rel="stylesheet" href="{{ asset('/public/AdminLTE/bower_components/Ionicons/css/ionicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('/public/AdminLTE/dist/css/AdminLTE.min.css') }}">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <link rel="shortcut icon" href="{{ asset('/public/assets/imgs/casinha.png')}}" />

</head>

<body onload="window.print();">
    
<div class="wrapper">
    
  <section class="invoice">
    <div class="row">
      <div class="col-xs-12">
        <h2 class="page-header">
        <img src="{{url('/public/imgs/logo.png')}}" alt="Logo PLC" height="156" width="276">
          <small class="pull-right">Data: {{ date('d/m/Y H:i:s', strtotime($carbon)) }}</small>
        </h2>
      </div>
    </div>
    <div class="row invoice-info">
      <div class="col-sm-4 invoice-col">
        Correspondente:
        <address>
          <strong>{{$numeroprocesso->Nome}}</strong><br>
          Endereço não identificado<br>
          S/N<br>
          Telefone: Não Identificado<br>
          Email: {{$numeroprocesso->email}}
        </address>
      </div>
      <div class="col-sm-4 invoice-col">
        Solicitante:
        <address>
          <strong>{{$numeroprocesso->Advogado}}</strong><br>
          {{$numeroprocesso->Unidade}} <br>
          {{$numeroprocesso->UnidadeEndereco}}<br>
          {{$numeroprocesso->UnidadeComplemento}}, {{$numeroprocesso->UnidadeBairro}}, {{$numeroprocesso->UnidadeCidade}}, {{$numeroprocesso->UnidadeCEP}} , {{$numeroprocesso->UnidadeUF}} <br>
          Telefone: {{$numeroprocesso->UnidadeTelefone}}<br>
          Email: {{$numeroprocesso->AdvogadoEmail}}
        </address>
      </div>
        
      <div class="col-sm-4 invoice-col">
        <b>Número Debite:</b> {{$numeroprocesso->NumeroDebite}}<br>
        <b>Pagamento Agendado:</b> <br>
        <b>Banco:</b> {{$numeroprocesso->Banco}} <br>
        <b>Agência:</b> {{$numeroprocesso->Agencia}} <br>
        <b>Conta:</b> {{$numeroprocesso->Conta}}

      </div>
    </div>

    <!-- Table row -->
    <div class="row">
      <div class="col-xs-12 table-responsive">
        <table class="table table-striped">
          <thead>
          <tr>
            <th>#</th>
            <th>Pasta</th>
            <th>Número Processo</th>
            <th>Correspondente</th>
            <th>Tipo Serviço</th>
            <th>Observação</th>
            <th>Valor (R$)</th>
            <th>Data Serviço</th>
            <th>Data Solicitação</th>
          </tr>
          </thead>
          <tbody>
          <tr>
            <td>{{$numeroprocesso->NumeroDebite}}</td>
            <td>{{$numeroprocesso->Pasta}}</td>
            <td>{{$numeroprocesso->NumPrc1_Sonumeros}}</td>
            <td>{{$numeroprocesso->Nome}}</td>
            <td>{{$numeroprocesso->TipoDebite}}</td>
            <td>{{$numeroprocesso->Obs}}</td>
            <td>R$ {{$numeroprocesso->ValorTotal}}</td>
            <td>{{ date('d/m/Y', strtotime($numeroprocesso->DataServico)) }}</td>
            <td>{{ date('d/m/Y', strtotime($numeroprocesso->DataSolicitacao)) }}</td>

          </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="row">
      <div class="col-xs-6">
                  <!--

        <p class="lead">Forma Pagamento:</p>
        <img src="{{url('/imgs/boleto.png')}}" width="51" height="32" alt="Boleto">
        <img src="{{url('/imgs/visa.png')}}" width="51" height="32" alt="Visa">
        <img src="{{url('/imgs/mastercard.png')}}" width="51" height="32" alt="MasterCard">
        <img src="{{url('/imgs/american.png')}}" width="51" height="32" alt="American">
        <img src="{{url('/imgs/paypal.png')}}" width="51" height="32" alt="PayPal">
        -->

        <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
* Recibos enviados na primeira quinzena do mês serão pagos até o dia 30 do mesmo mês.
<br>
* Recibos enviados a partir da segunda quinzena serão pagos até o dia 15 do mês seguinte.
<br>
* Os recibos que não atenderem ao padrão especificado neste documento, não serão
processados e o advogado correspondente será notificado para que possa reenviar.     
        </p>
      </div>
      <div class="col-xs-6">

        <div class="table-responsive">
          <table class="table">
            <tr>
              <th style="width:50%">Subtotal:</th>
              <td>R$ {{$numeroprocesso->ValorTotal}}</td>
            </tr>
            <tr>
              <th>Total:</th>
             <td>R$ {{$numeroprocesso->ValorTotal}}</td>
            </tr>
          </table>
        </div>
      </div>
        
            </div>
    </div>
  </section>
</div>
</body>
</html>
