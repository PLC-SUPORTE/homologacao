<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Portal PL&C</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="{{ asset('/public/AdminLTE/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('/public/AdminLTE/bower_components/font-awesome/css/font-awesome.min.css') }}">
  <link rel="stylesheet" href="{{ asset('/public/AdminLTE/bower_components/Ionicons/css/ionicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('/public/AdminLTE/dist/css/AdminLTE.min.css') }}">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

<body onload="window.print();">
    
<div class="wrapper">
    
  <section class="invoice">
    <div class="row">
      <div class="col-xs-12">
        <h2 class="page-header">
          <img src="{{url('/public/imgs/logo.png')}}" alt="Logo PLC" height="156" width="276">
          <small class="pull-right">Data: {{ date('d/m/Y H:m:s', strtotime($carbon)) }}</small>
        </h2>
      </div>
    </div>
      
      <center><h3>Recibo de Despesas</h3></center>
      <br>
   
      <p>Despesas ...................................... {{$datas->ValorMoeda}} {{$datas->ValorTotal}}</p
      <br><br>
      <p>Recemos de <strong>{{$datas->Cliente}}</strong>, CNPJ: {{$datas->ClienteCNPJ}} a quantia supra 
      referida de <strong>{{$extenso}}</strong> referente a reembolso de despesas.</p>
      <br><br>
      <b>Belo Horizonte, _______de ______________________ de __________</b>
      <br><br><br><br>
      <center><b><h3>PORTELA, LIMA, LOBATO & COLEN SOCIEDADE DE ADVOGADOS</h3></b></center>
      <center><h5>CNPJ: 07.928.834/0001-00<h5></center>
      <br>
      <hr color="black" width="100%" height="10px" />
      <center><p>{{$datas->UnidadeEndereco}}, {{$datas->UnidadeComplemento}}, {{$datas->UnidadeBairro}} - {{$datas->UnidadeCEP}} - {{$datas->UnidadeCidade}} - {{$datas->UnidadeUF}} - Brasil - 
              Tel 55 {{$datas->UnidadeTelefone}} - <strong> www.plcadvogados.com.br</strong></p></center>
    </div>
  </section>
</div>
</body>
</html>
