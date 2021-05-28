@extends('painel.Layout.header')
<?php $search = 2 ?>
@section('title') Meritocracia @endsection <!-- Titulo da pagina -->

@section('header') 
    <link rel="apple-touch-icon" href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/images/favicon/apple-touch-icon-152x152.png">
    <link rel="shortcut icon" href="{{ asset('/public/assets/imgs/casinha.png')}}" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/css/pages/app-invoice.min.css">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/materialize.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/chartist.min.css') }}">

    <link rel="stylesheet" type="text/css" href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/vendors/chartist-js/chartist-plugin-tooltip.css">
@endsection
@section('header_title')
Programa de Distribuição de Resultado Variável ("RV")
@endsection
@section('submenu')
<li class="breadcrumb-item active" style="color: black;">Dashboard individual acumulado
</li>
@endsection
@section('body')
    <div>
      <div class="row">
        <div class="content-wrapper-before blue-grey lighten-5"></div>
        <div class="col s12">
          <div class="container">


<section class="invoice-view-wrapper section">
  <div class="row">

    <div class="col xl12 m12 s12">
      <div class="card">
        <div class="card-content invoice-print-area">

             <div class="panel panel-primary setup-content container" style="border-color:#965A2C;" style="width: 40px;">
              <div class="panel-heading" style="background-color:#fff;border-color:#E6E6E6; color:black; height: 80px;">
                <img src="{{url('./public/imgs/logomarca.png')}}" alt="Smiley face" height="120" width="190" style="margin-top: -20px;"> 
              <h3 style="margin-left: 30%; margin-top: -4.8%; font-size: 21px;" class="form-group">Programa de Distribuição de Resultado Variável ("RV")</h3>
              </div>

              <div class="input-field align right" style="margin-top: -8.5%;">

               <a  href="{{ route('Painel.Gestao.Meritocracia.CartasRV.historico') }}" class="btn-floating btn-mini waves-effect waves-light buttons-right tooltipped" 
                data-position="left" data-tooltip="Dashboard individual histórico" style="margin-left: 5px;background-color: gray; margin-top: 4px;"><i class="material-icons" style="margin-top: -2px;">dashboard</i></a>

                <a href="{{ route('Painel.Gestao.Meritocracia.minhasnotas') }}" class="btn-floating btn-mini waves-effect waves-light buttons-right tooltipped" 
                data-position="left" data-tooltip="Minhas Notas" style="margin-left: 5px;margin-top: 4px;background-color: gray;"><i class="material-icons" style="margin-top: -2px;">analytics</i></a>

                @if($nivel == "Superintendente" || $nivel == "Coordenador" || $nivel == "Subcoordenador 1" || $nivel == "Subcoordenador 2"  ||
                $nivel == "Coordenador Controladoria" || $nivel == "Coordenador ControladoriaSP" || $nivel == "Gerente" || $nivel == "Gerente Equipe Passiva" || $nivel == "COO")
                  <a  href="{{ route('Painel.Gestao.Meritocracia.Hierarquia.index') }}" 
                  class="btn-floating btn-mini waves-effect waves-light buttons-right tooltipped" 
                  data-position="left" data-tooltip="Dashboard meritocracia" style="margin-left: 5px;background-color: gray; margin-top: 4px;"><i class="material-icons" style="margin-top: -2px;">connect_without_contact</i></a>
                
                  <!-- <a  href="{{ route('Painel.Gestao.Meritocracia.Hierarquia.Setor.index') }}" 
                  class="btn-floating btn-mini waves-effect waves-light buttons-right tooltipped" 
                  data-position="left" data-tooltip="Dashboard equipe" style="margin-left: 5px;background-color: gray; margin-top: 4px;"><i class="material-icons" style="margin-top: -2px;">groups</i></a> -->
                
                @endif

                <a  href="{{ route('Painel.Gestao.Meritocracia.Prazos.index') }}" class="btn-floating btn-mini waves-effect waves-light buttons-right tooltipped" 
                data-position="left" data-tooltip="Volumetria de prazos vencidos meta" style="margin-left: 5px;background-color: gray; margin-top: 4px;"><i class="material-icons" style="margin-top: -2px;">hourglass_disabled</i></a>

                <a href="{{ route('Painel.Gestao.Meritocracia.Contrato.index') }}" 
                  class="btn-floating btn-mini waves-effect waves-light buttons-right tooltipped" 
                  data-position="left" data-tooltip="Meus contratos" style="margin-left: 5px;background-color: gray; margin-top: 4px;"><i class="material-icons" style="margin-top: -2px;">description</i></a>
              </div>

              <style>
                .buttons-right{
                  width: 35px;
                  height: 35px;
                }
              </style>

              <div class="row" style="margin-top: 6%;">
                <hr>
                <div class="col m4">
                    <div class="box box-widget widget-user-2">

                      <div class="box-footer no-padding">
                        <ul class="nav nav-stacked">
                          <li><span style="font-weight: bold;">Nome:</span> <?php echo mb_convert_case(Auth::user()->name, MB_CASE_TITLE, "UTF-8")?></li>
                          <li><span style="font-weight: bold;">Unidade:</span> {{$unidade_descricao}}</li>
                          <li><span style="font-weight: bold;">Área:</span> {{$setor_descricao}}</span></li>
                          <li><span style="font-weight: bold;">Mês de apuração:</span> 4 - Abril</span></li>
                          <!-- <li><span style="font-weight: bold;">Mês Apuração:</span> {{$mespassado}} - {{ucfirst($mesatual)}}</span></li> -->
                          <li><span style="font-weight: bold;">Período Base:</span> Janeiro a Março de 2021</span></li> 
                          <!-- <li><span style="font-weight: bold;">Período Base:</span> Janeiro a Dezembro de {{$ano}}</span></li> -->
                        </ul>
                      </div>
                    </div>
                  </div>

            <div class="col s6 m2">
          <div class="card gradient-shadow border-radius-3" style="background-color: gray">
            <div class="card-content center  tooltipped" data-position="top" data-tooltip="O RV Máximo indica o valor total que você poderá receber, a título de RV em relação ao Período Base, considerando os resultados do PLC, Unidade e Área e o seu Score Individual com nota máxima.">
            <p class="white-text lighten-4" style="font-size: 12px;">RV Máximo</p>
            <h5 class="white-text lighten-4" style="font-size: 11px;">R$ <?php echo number_format($rv_maximo,2,",",".") ?></h5>
            </div>
          </div>
        </div>

        <div class="col s6 m2">
        <div class="card gradient-shadow border-radius-3" style="background-color: gray">
            <div class="card-content center  tooltipped" data-position="top" data-tooltip="O RV Apurado indica o valor total que você poderá receber, a título de RV em relação ao Período Base, considerando os resultados do PLC, Unidade e Área e o seu Score Individual com a nota atual.">
            <p class="white-text lighten-4" style="font-size: 12px;">RV Apurado</p>
              <h5 class="white-text lighten-4" style="font-size: 11px;">R$ <?php echo number_format($rv_apurado,2,",",".") ?></h5>
            </div>
          </div>
        </div>

        <div class="col s6 m2">
        <div class="card gradient-shadow border-radius-3" style="background-color: gray">
            <div class="card-content center  tooltipped" data-position="top" data-tooltip="O RV Recebido indica o valor já recebido por você até o Mês de Apuração.">
            <p class="white-text lighten-4" style="font-size: 12px;">RV Recebido</p>
            <h5 class="white-text lighten-4" style="font-size: 11px;">R$ <?php echo number_format($rv_recebido,2,",",".") ?></h5>
            </div>
          </div>
        </div>

        <div class="col s6 m2">
        <div class="card gradient-shadow border-radius-3" style="background-color: gray">
            <div class="card-content center tooltipped" data-position="top" data-tooltip="RV Projetado correspondente a projeção do RV que você irá receber em relação ao Período Base de acordo com a fórmula: RV Apurado - RV Recebido = RV Projetado.">
            <p class="white-text lighten-4" style="font-size: 12px;">RV Projetado</p>
            <h5 class="white-text lighten-4" style="font-size: 11px;">R$ <?php echo number_format($rv_projetado,2,",",".") ?></h5>
            </div>
          </div>
        </div>


        <!--Graficos -->
        </div>
        </div>

        <div id="card-stats" class="row">

        <div class="col s6 m2 ">
        <center><h8 style="margin-left: 25px;">PLC</h8></center>
        <div class="current-balance-container tooltipped" data-position="top" data-tooltip="O gráfico apresenta o resultado (base caixa) acumulado do PLC em comparação com o resultado orçado para o período.">
        <canvas id="plc" style="width: 200px; height: 100px"></canvas>
        <center>       
               <p class="medium-small center-align" style="margin-left: 40px;color: black;font-size: 15px;font-weight: bold;">{{$plc_porcent}}%</p>
        </center>
        </div>
        </div>

        <div class="col s6 m2 ">
        <center><h8 style="margin-left: 25px;">Superintendência</h8></center>
        <div class="current-balance-container tooltipped" data-position="top" data-tooltip="O gráfico apresenta o resultado (base caixa) acumulado da Unidade (Sociedade) em comparação com o resultado orçado para o período. Esta condição não se aplicará aos Sócios Superintendentes.">
        <canvas id="unidade" style="width: 200px; height: 100px"></canvas>
               <br>
               <p class="medium-small center-align" style="margin-left: 40px;color: black;font-size: 15px;font-weight: bold;">{{$unidade_porcent}}%</p>
        </div>
        </div>

        <div class="col s6 m2 ">
        <center><h8 style="margin-left: 25px;">Gerência</h8></center>
        <div class="current-balance-container tooltipped" data-position="top" data-tooltip="O gráfico apresenta o resultado.">
        <canvas id="gerencia" style="width: 200px; height: 100px"></canvas>
               <br>
               <p class="medium-small center-align" style="margin-left: 40px;color: black;font-size: 15px;font-weight: bold;">{{$gerencia_porcent}}%</p>
        </div>
        </div>


        <div class="col s6 m2 ">
        <center><h8 style="margin-left: 25px;">Área</h8></center>
        <div class="current-balance-container tooltipped" data-position="top" data-tooltip="O gráfico apresenta o resultado (base caixa) acumulado da Área (Sociedade) em comparação com o resultado orçado para o período. Para os Superintendentes será considerado como Resultado Área a soma do resultado de todas as Áreas que integram sua Superintendência.">
        <canvas id="area" style="width: 200px; height: 100px"></canvas>
               <br>
               <p class="medium-small center-align" style="margin-left: 40px;color: black;font-size: 15px;font-weight: bold;s">{{$area_porcent}}%</p>
        </div>
        </div>

        <div class="col s6 m2 ">
        <center><h8 style="margin-left: 25px;">Score Individual</h8></center>
        <div class="current-balance-container tooltipped" data-position="top" data-tooltip="O gráfico apresenta o resultado individual acumulado (Resultado Sócio) nos indicadores de desempenho a que lhe são aplicáveis.">
        <canvas id="score" style="width: 200px; height: 100px"></canvas>
               <br>
               <p class="medium-small center-align" style="margin-left: 40px;color: black;font-size: 15px;font-weight: bold;s">{{$score_porcent}}</p>
        </div>
        </div>

        

            </div>
          </div>

        <div class="card-content">
        <h5>Observações</h5>
      <table class="table" cellspacing="0" cellpadding="0" style="border:none;">
                          <tr>
                            <td><b>1) </b> Os índices Corte e Real do PLC, da Superintendência, da Gerência e da Área retratam o Forecast do Período Base.</td>
                          </tr>
                          <tr>
                            <td><b>2) </b>Os índices Corte e Real do seu Score Individual refletem o resultado efetivamente realizado acumulado até o Mês de Apuração.</td>
                          </tr>
                          <tr>
                            <td><b>3) </b>O RV Máximo indica o valor total que você poderá receber, a título de RV em relação ao Período Base, considerando os resultados do PLC, da Superintendência, da Gerência e da Área e o seu Score Individual com nota máxima.</td>
                          </tr>
                          <tr>
                            <td><b>4) </b>O RV Apurado indica o valor total que você poderá receber, a título de RV em relação ao Período Base, considerando os resultados do PLC, da Superintendência, da Gerência e da Área e o seu Score Individual com a nota atual.</td>
                          </tr>
                          <tr>
                            <td><b>5) </b>O RV Recebido indica o valor já recebido por você até o Mês de Apuração.</td>
                          </tr>
                          <tr>
                            <td><b>6) </b>RV Projetado correspondente a projeção do RV que você irá receber em relação ao Período Base de acordo com a fórmula: RV Apurado - RV Recebido = RV Projetado. </td>
                          </tr>
                          <tr>
                            <td><b>7) </b>A expressão Forecast contempla os resultados reais dos meses encerrados + os resultados orçados dos meses futuros que compõem o Período Base. </td>
                          </tr>
                      </table>
        </div>
    </div>

    </div>
  </div>
</section>
          </div>
          <div class="content-overlay"></div>
        </div>
      </div>
    </div>
    <!-- END: Page Main-->


@endsection
<!-- @section('scripts') -->
    <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>
    <!-- <script src="{{ asset('/public/materialize/js/gauge.min.js') }}"></script> -->
    <script src="https://pixinvent.com/materialize-material-design-admin-template/app-assets/js/scripts/app-invoice.min.js"></script>
    <script src="https://bernii.github.io/gauge.js/dist/gauge.min.js"></script>
    <script src="{{ asset('/public/materialize/js/vendors.min.js') }}"></script>


<!--PLC -->
<script type="text/javascript">
	var opts = {
		angle: 0, 
		lineWidth: 0.24,
		radiusScale: 1, // Raio relativo
		pointer: {
		  length: 0.6, // // Relativo ao raio do Gauge
		  strokeWidth: 0.035, // Largura do traço
		  color: '#000000' // Cor do ponteiro
		},
    staticZones: [
   {strokeStyle: "#dc3545", min: 0, max: 99.99}, // Red from 0 a 99.99
   {strokeStyle: "#28a745", min: 100.00, max: 500.00}, // Green
   ],
		limitMax: false,     // Se false, valor maximo aumenta automaticamente se valor > valor maximo
		limitMin: false,     // Se true, o valor mínimo será fixo
		generateGradient: true,
		highDpiSupport: true,  
	};
	var target = document.getElementById('plc'); // Elemento onde o gauge deve ser criado
	var gauge = new Gauge(target).setOptions(opts); // Criar gauge
	gauge.maxValue = 500; // Valor maximo
	gauge.setMinValue(0);  // Valor minimo
	gauge.animationSpeed = 32; // Velocidade da animacao
	gauge.set({{$plc_porcent}}); // Valor a ser exibido
	</script>
  <!--FIM PLC -->


  <!--Unidade --> 
  <script type="text/javascript">
	var opts = {
		angle: 0, 
		lineWidth: 0.24,
		radiusScale: 1, // Raio relativo
		pointer: {
		  length: 0.6, // // Relativo ao raio do Gauge
		  strokeWidth: 0.035, // Largura do traço
		  color: '#000000' // Cor do ponteiro
		},
    staticZones: [
   {strokeStyle: "#dc3545", min: 0, max: 99.99}, // Red from 0 a 99.99
   {strokeStyle: "#28a745", min: 100.00, max: 500.00}, // Green
   ],
		limitMax: false,     // Se false, valor maximo aumenta automaticamente se valor > valor maximo
		limitMin: false,     // Se true, o valor mínimo será fixo
		generateGradient: true,
		highDpiSupport: true,  
	};
	var target = document.getElementById('unidade'); // Elemento onde o gauge deve ser criado
	var gauge = new Gauge(target).setOptions(opts); // Criar gauge
	gauge.maxValue = 500; // Valor maximo
	gauge.setMinValue(0);  // Valor minimo
	gauge.animationSpeed = 32; // Velocidade da animacao
	gauge.set({{$unidade_porcent}}); // Valor a ser exibido
	</script>
  <!--Unidade -->

  <!--Gerência --> 
  <script type="text/javascript">
	var opts = {
		angle: 0, 
		lineWidth: 0.24,
		radiusScale: 1, // Raio relativo
		pointer: {
		  length: 0.6, // // Relativo ao raio do Gauge
		  strokeWidth: 0.035, // Largura do traço
		  color: '#000000' // Cor do ponteiro
		},
    staticZones: [
   {strokeStyle: "#dc3545", min: 0, max: 99.99}, // Red from 0 a 99.99
   {strokeStyle: "#28a745", min: 100.00, max: 500.00}, // Green
   ],
		limitMax: false,     // Se false, valor maximo aumenta automaticamente se valor > valor maximo
		limitMin: false,     // Se true, o valor mínimo será fixo
		generateGradient: true,
		highDpiSupport: true,  
	};
	var target = document.getElementById('gerencia'); // Elemento onde o gauge deve ser criado
	var gauge = new Gauge(target).setOptions(opts); // Criar gauge
	gauge.maxValue = 500; // Valor maximo
	gauge.setMinValue(0);  // Valor minimo
	gauge.animationSpeed = 32; // Velocidade da animacao
	gauge.set({{$gerencia_porcent}}); // Valor a ser exibido
	</script>
  <!--Fim -->

  <!--Area --> 
  <script type="text/javascript">
	var opts = {
		angle: 0, 
		lineWidth: 0.24,
		radiusScale: 1, // Raio relativo
		pointer: {
		  length: 0.6, // // Relativo ao raio do Gauge
		  strokeWidth: 0.035, // Largura do traço
		  color: '#000000' // Cor do ponteiro
		},
    staticZones: [
   {strokeStyle: "#dc3545", min: 0, max: 99.99}, // Red from 0 a 99.99
   {strokeStyle: "#28a745", min: 100.00, max: 500.00}, // Green
   ],
		limitMax: false,     // Se false, valor maximo aumenta automaticamente se valor > valor maximo
		limitMin: false,     // Se true, o valor mínimo será fixo
		generateGradient: true,
		highDpiSupport: true,  
	};
	var target = document.getElementById('area'); // Elemento onde o gauge deve ser criado
	var gauge = new Gauge(target).setOptions(opts); // Criar gauge
	gauge.maxValue = 500; // Valor maximo
	gauge.setMinValue(0);  // Valor minimo
	gauge.animationSpeed = 32; // Velocidade da animacao
	gauge.set({{$area_porcent}}); // Valor a ser exibido
	</script>
  <!--Area -->

  <!--Score Individual --> 
  <script type="text/javascript">
	var opts = {
		angle: 0, 
		lineWidth: 0.24,
		radiusScale: 1, // Raio relativo
		pointer: {
		  length: 0.6, // // Relativo ao raio do Gauge
		  strokeWidth: 0.035, // Largura do traço
		  color: '#000000' // Cor do ponteiro
		},
    staticZones: [
   {strokeStyle: "#dc3545", min: 0, max: 99.99}, // Red from 0 a 99.99
   {strokeStyle: "#28a745", min: 100.00, max: 500.00}, // Green
   ],
		limitMax: false,     // Se false, valor maximo aumenta automaticamente se valor > valor maximo
		limitMin: false,     // Se true, o valor mínimo será fixo
		generateGradient: true,
		highDpiSupport: true,  
	};
	var target = document.getElementById('score'); // Elemento onde o gauge deve ser criado
	var gauge = new Gauge(target).setOptions(opts); // Criar gauge
	gauge.maxValue = 500; // Valor maximo
	gauge.setMinValue(0);  // Valor minimo
	gauge.animationSpeed = 32; // Velocidade da animacao
	gauge.set({{$score_porcent}}); // Valor a ser exibido
	</script>
  <!--Score Individual -->
@endsection