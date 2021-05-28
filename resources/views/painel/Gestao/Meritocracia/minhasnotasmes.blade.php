@extends('painel.Layout.header')
<?php $search = 2 ?>
@section('title') Score consolidado @endsection <!-- Titulo da pagina -->

@section('header') 
    <link rel="apple-touch-icon" href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/images/favicon/apple-touch-icon-152x152.png">
    <link rel="shortcut icon" href="{{ asset('/public/assets/imgs/casinha.png')}}" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/materialize.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/custom.css') }}">  
    <script src="{{ asset('/public/materialize/js/vendors.min.js') }}"></script>

    <style>
    h1 {
      color: #222;
      font-size: 15px;
      font-weight: 400;
      letter-spacing: 0.05em;
      margin: 40px auto;
      text-transform: uppercase;
    }
</style>
@endsection
@section('header_title')
Programa de Distribuição de Resultado Variável ("RV")
@endsection
@section('submenu')
<li class="breadcrumb-item"><a href="{{ route('Painel.Gestao.Meritocracia.index') }}">Dashboard individual acumulado</a>
</li>
<li class="breadcrumb-item active" style="color: black;">Score consolidado
</li>
@endsection
@section('body')
    <div>
      <div class="row">

    <center>
  <div id="loading">
      <div class="wrapper">
      <div class="circle circle-1"></div>
      <div class="circle circle-1a"></div>
      <div class="circle circle-2"></div>
      <div class="circle circle-3"></div>
     </div>
     <h1 style="text-align: center;">Aguarde, estamos carregando o seu score consolidado...&hellip;</h1>
     </div>
  </center>   



        <div class="col s12" id="corpodiv">

          <div class="container">
            <div class="section">

            <div class="card animate fadeLeft">

            <div class="input-field col s4 align left" style="margin-top: -0px;margin-left:-25px;">
            <a href="{{ route('Painel.Gestao.Meritocracia.index') }}" class="btn-floating btn-mini waves-effect waves-light buttons-right tooltipped" data-position="top" data-tooltip="Dashboard individual acumulado" style="margin-top: -15px;background-color: gray;"><i class="material-icons">home</i></a>
            </div>

            <div class="input-field col s4 align right" style="margin-top: -12px;">
                <a href="#modaldetalhamentoindicador" class="btn-floating btn-mini waves-effect waves-light modal-trigger tooltipped" data-position="left" data-tooltip="Clique aqui para detalhar suas notas." style="margin-left: 105px;margin-top: -5px;background-color: gray;"><i class="material-icons">assignment</i></a>
                <a href="{{ route('Painel.Gestao.Meritocracia.minhasnotas') }}" class="btn-floating btn-mini waves-effect waves-light tooltipped"data-position="left" data-tooltip="Clique aqui para visualizar o seu score acumulado."  style="margin-left: 5px;margin-top:-5px;background-color: gray;"><i class="material-icons">analytics</i></a>
                <a  href="{{ route('Painel.Gestao.Meritocracia.CartasRV.historico') }}" class="btn-floating btn-mini waves-effect waves-light buttons-right tooltipped" data-position="left" data-tooltip="Dashboard individual histórico" style="margin-left: 5px;background-color: gray; margin-top: -5px;"><i class="material-icons">dashboard</i></a>
                @if($nivel == "Superintendente" || $nivel == "Coordenador" || $nivel == "Subcoordenador 1" || $nivel == "Subcoordenador 2"  ||
                $nivel == "Coordenador Controladoria" || $nivel == "Coordenador ControladoriaSP" || $nivel == "Gerente" || $nivel == "Gerente Equipe Passiva" || $nivel == "COO")
                <a href="{{ route('Painel.Gestao.Meritocracia.Hierarquia.index') }}" class="btn-floating btn-mini waves-effect waves-light buttons-right tooltipped" data-position="top" data-tooltip="Dashboard equipe" style="margin-left: 5px;margin-top: -5px;background-color: gray;"><i class="material-icons">groups</i></a>
                @endif
                <a href="{{ route('Painel.Gestao.Meritocracia.Prazos.index') }}" class="btn-floating btn-mini waves-effect waves-light buttons-right tooltipped" data-position="top" data-tooltip="Volumetria de prazos vencidos meta" style="margin-left: 5px;margin-top: -5px;background-color: gray;"><i class="material-icons">hourglass_disabled</i></a>
                <a href="{{ route('Painel.Gestao.Meritocracia.Contrato.index') }}" class="btn-floating btn-mini waves-effect waves-light buttons-right tooltipped" data-position="top" data-tooltip="Meus contratos" style="margin-left: 5px;margin-top: -5px;background-color: gray;"><i class="material-icons">description</i></a>
             </div>

            <div class="card-content">

              <div class="current-balance-container tooltipped" data-position="top" data-tooltip="O gráfico apresenta o desempenho pontual no mês em questão, com a consolidação das notas de seus respectivos indicadores.">
                <center>
                <canvas id="notaacumulada" style="width: 200px; height: 100px;margin-left:-70px"></canvas>
                </center>
                </div>
                <p class="medium-small center-align" style="margin-left: -90px;">{{$notaconsolidada}}</p>
                <p class="medium-small center-align" style="margin-left: -90px;">Score consolidado</p>

               <hr style="border: 1px dashed gray;" />


            <div id="card-stats" class="row">
            @foreach($datas as $data)
            <div class="col s6 m4 l2 x4">
            
            <div class="current-balance-container">
            @if($data->objetivo_id == 3)
            <canvas id="grafico{{$data->id}}" class="tooltipped" style="width: 200px; height: 100px;" data-position="top" data-tooltip="O gráfico apresenta o desempenho no indicador de Cumprimento de Prazo. Apuração: percentual de demandas entregues dentro do prazo. Para as equipes de Contencioso é considerado o cumprimento dos prazos no D-2. Para as equipes de Consultivo é considerado o cumprimento dos prazos de entrega das demandas dos clientes."></canvas>
            @elseif($data->objetivo_id == 6)
            <canvas id="grafico{{$data->id}}" class="tooltipped" style="width: 200px; height: 100px;" data-position="top" data-tooltip="O gráfico apresenta o desempenho no indicador de Horas Produzidas em Geral no ADVwin. Apuração: média de quantidade de horas lançadas diariamente pelos sócios, levando em conta os dias efetivamente trabalhados (dias de férias, recessos etc. não serão computados) em cada mês. A apuração é feita pelo ADVWin."></canvas>
            @elseif($data->objetivo_id == 7)
            <canvas id="grafico{{$data->id}}" class="tooltipped" style="width: 200px; height: 100px;" data-position="top" data-tooltip="O gráfico apresenta o desempenho no indicador de Horas Produzidas para Clientes no ADVwin. Apuração: média de quantidade de horas produzidas diariamente pelos sócios para os clientes, levando em conta os dias efetivamente trabalhados (dias de férias, recessos etc. não serão computados) em cada mês. A apuração é feita pelo ADVWin."></canvas>
            @elseif($data->objetivo_id == 10)
            <canvas id="grafico{{$data->id}}" class="tooltipped" style="width: 200px; height: 100px;" data-position="top" data-tooltip="O gráfico apresenta o desempenho no indicador de Data de lançamento de horas no ADVWin. Apuração: verificação da data de lançamento de horas no sistema ADVwin, conforme o grupo de áreas. Grupo 1 (Consultivo): deverão lançar até quarta-feira as horas que produziram da quinta-feira da semana anterior até a quarta-feira - data do lançamento. Assim, caso os sócios das Áreas do Grupo 1 produzam, mas não lancem no sistema até quarta-feira as horas que produziram da quinta-feira da semana anterior até a quarta-feira - data do lançamento - eles perderão o indicador. Grupo 2 (Contencioso): deverão lançar até quinta-feira as horas que produziram da sexta-feira da semana anterior até a quinta-feira - data do lançamento. Assim, caso os sócios das Áreas do Grupo 2 produzam, mas não lancem no sistema até quinta-feira as horas que produziram da sexta-feira da semana anterior até a quinta-feira - data do lançamento - eles perderão o indicador. Observação: Caso o sócio tenha trabalhado e não tenha lançado na semana correta, ainda assim deverá efetuar o lançamento na semana seguinte ou no máximo até o último dia do respectivo mês, para não ser também prejudicado no indicador de horas lançadas."></canvas>
            @elseif($data->objetivo_id == 13)
            <canvas id="grafico{{$data->id}}" class="tooltipped" style="width: 200px; height: 100px;" data-position="top" data-tooltip="O gráfico apresenta o desempenho no indicador de Media Nota Oper. Area."></canvas>
            @elseif($data->objetivo_id == 14)
            <canvas id="grafico{{$data->id}}" class="tooltipped" style="width: 200px; height: 100px;" data-position="top" data-tooltip="O gráfico apresenta o desempenho no indicador de Média do Score da Área. Apuração: soma dos scores dos Sócios sob gestão do Coordenador e/ou do Subcoordenador, dividido pelo número de integrantes da Área ou do Núcleo respectivamente, excluídos o próprio Coordenador e o Subcoordenador da Área ou do Núcleo."></canvas>
            @elseif($data->objetivo_id == 18)
            <canvas id="grafico{{$data->id}}" class="tooltipped" style="width: 200px; height: 100px;" data-position="top" data-tooltip="O gráfico apresenta o desempenho acumulado no indicador de Margem de Contribuição da área. Apuração: a partir do levantamento das receitas obtidas pelas Áreas, deduzidos todos os custos e despesas diretas correspondentes. O valor é disponibilizado nos resultados entregues mensalmente a todas as lideranças."></canvas>
            @elseif($data->objetivo_id == 17)
            <canvas id="grafico{{$data->id}}" class="tooltipped" style="width: 200px; height: 100px;" data-position="top" data-tooltip="O gráfico apresenta o desempenho acumulado no indicador de Margem de Contribuição da superintendência. Apuração: a partir do levantamento das receitas obtidas pelas Superintendências, deduzidos todos os custos e despesas diretas correspondentes. O valor é disponibilizado nos resultados entregues a todas as lideranças."></canvas>
            @elseif($data->objetivo_id == 16)
            <canvas id="grafico{{$data->id}}" class="tooltipped" style="width: 200px; height: 100px;" data-position="top" data-tooltip="O gráfico apresenta o desempenho acumulado no indicador de Resultado Sociedade.Apuração: a partir do levantamento dos resultados (Lucro Líquido) obtidos pela Sociedade (Unidade)."></canvas>
            @elseif($data->objetivo_id == 4)
            <canvas id="grafico{{$data->id}}" class="tooltipped" style="width: 200px; height: 100px;" data-position="top" data-tooltip="O gráfico apresenta o desempenho no indicador de Custo por Processo. Apuração: a partir do levantamento de todos os custos despendidos com a Controladoria dividido pelo número de pastas de processos ativos no sistema ADVWin."></canvas>
            @elseif($data->objetivo_id == 20)
            <canvas id="grafico{{$data->id}}" class="tooltipped" style="width: 200px; height: 100px;" data-position="top" data-tooltip="O gráfico apresenta o desempenho no indicador de Artigo Individual. Apuração: publicação de ao menos 1 (um) artigo científico, conforme as regras determinadas na Carta de RV."></canvas>
            @elseif($data->objetivo_id == 21)
            <canvas id="grafico{{$data->id}}" class="tooltipped" style="width: 200px; height: 100px;" data-position="top" data-tooltip="O gráfico apresenta o desempenho no indicador de Informativo Mensal. Apuração: publicação de ao menos 1 (um) informativo por mês, conforme as regras determinadas na Carta de RV."></canvas>
            @elseif($data->objetivo_id == 22)
            <canvas id="grafico{{$data->id}}" class="tooltipped" style="width: 200px; height: 100px;" data-position="top" data-tooltip="O gráfico apresenta o desempenho no indicador de Informativo Mensal, conforme as regras determinadas na Carta de RV."></canvas>
            @endif
            <br>
               <p class="medium-small center-align" style="margin-left: 25px;">{{$data->nota}}</p>
               <p class="medium-small center-align" style="margin-left: 25px;">{{$data->objetivo}}</p>
            </div>
            </div>
            @endforeach  
               
            </div>   

</div>
</div>
</div>


   <!--Modal detalhamento indicador --> 
   <div id="modaldetalhamentoindicador" class="modal modal-fixed-footer" style="width: 1200px;height:100%;overflow:hidden;">

    <button type="button" class="btn waves-effect mr-sm-1 mr-2 modal-close red" style="margin-left: 1092px; margin-top: 5px;">
        <i class="material-icons">close</i> 
      </button>

      <iframe style=" position:absolute;
    top:60;
    left:0;
    width:100%;
    height:100%;" src="{{ route('Painel.Gestao.Meritocracia.detalhamento') }}"></iframe>
     
  </div>
  <!-- Fim Modal detalhamento indicador -->

@endsection

@section('scripts')

    <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>
    <script src="https://bernii.github.io/gauge.js/dist/gauge.min.js"></script>
    <!-- <script src="{{ asset('/public/materialize/js/vendors.min.js') }}"></script> -->


<!--NOTA ACUMULADA -->
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
    {strokeStyle: "#dc3545", min: 0, max: 89.99}, 
    {strokeStyle: "#28a745", min: 90.00, max: 120.00}, 
    ],
    strokeColor: 'gray',  // to see which ones work best for you
		limitMax: true,     // Se false, valor maximo aumenta automaticamente se valor > valor maximo
		limitMin: false,     // Se true, o valor mínimo será fixo
		generateGradient: true,
		highDpiSupport: true,  
	};
	var target = document.getElementById('notaacumulada'); // Elemento onde o gauge deve ser criado
	var gauge = new Gauge(target).setOptions(opts); // Criar gauge
  $('#notaconsolidadainput').val({{$notaconsolidada}});
	gauge.maxValue = 120; // Valor maximo
	gauge.setMinValue(0);  // Valor minimo
	gauge.animationSpeed = 32; // Velocidade da animacao
	gauge.set({{$notaconsolidada}}); // Valor a ser exibido
	</script>
<!--FIM NOTA ACUMULADA-->


<!--Loop buscando as notas --> 
<script type="text/javascript">

  @foreach($datas as $data)

  //Se o Objetivo For 3 
   if({{$data->objetivo_id}} == 3) {

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
    {strokeStyle: "#dc3545", min: 0, max: 89.99}, 
    {strokeStyle: "yellow", min: 90.00, max: 94.99}, 
    {strokeStyle: "#28a745", min: 95.00, max: 100}, 
    ],
    strokeColor: 'gray',  // to see which ones work best for you
    limitMax: true,     // Se false, valor maximo aumenta automaticamente se valor > valor maximo
    limitMin: true,     // Se true, o valor mínimo será fixo
    generateGradient: true,
    highDpiSupport: true,  
  };
  var target = document.getElementById('grafico{{$data->id}}'); // Elemento onde o gauge deve ser criado
  var gauge = new Gauge(target).setOptions(opts); // Criar gauge
  gauge.maxValue = 100; // Valor maximo
  gauge.setMinValue(0);  // Valor minimo
  gauge.animationSpeed = 32; // Velocidade da animacao
  gauge.set({{$data->nota}}); // Valor a ser exibido

   }
   //Se o objetivo for 4 e Advogado Controladoria
   else if({{$data->objetivo_id}} == 4 && "{{$nivel}}" == "Advogado Controladoria") {

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
    {strokeStyle: "#dc3545", min: 7.47, max: 8.00}, 
    {strokeStyle: "yellow", min: 7.10, max: 7.46}, 
    {strokeStyle: "#28a745", min: 0, max: 7.10}, 
    ],
    strokeColor: 'gray',  // to see which ones work best for you
    limitMax: true,     // Se false, valor maximo aumenta automaticamente se valor > valor maximo
    limitMin: true,     // Se true, o valor mínimo será fixo
    generateGradient: true,
    highDpiSupport: true,  
  };
  var target = document.getElementById('grafico{{$data->id}}'); // Elemento onde o gauge deve ser criado
  var gauge = new Gauge(target).setOptions(opts); // Criar gauge
  gauge.maxValue = 8.00; // Valor maximo
  gauge.setMinValue(0);  // Valor minimo
  gauge.animationSpeed = 32; // Velocidade da animacao
  gauge.set({{$data->nota}}); // Valor a ser exibido

   }
   //Se o objetivo for 4 e Advogado Controladoria SPO
   else if({{$data->objetivo_id}} == 4 && "{{$nivel}}" == "Advogado ControladoriaSP") {

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
    {strokeStyle: "#dc3545", min: 9.06, max: 10.00}, 
    {strokeStyle: "yellow", min: 8.79, max: 9.05}, 
    {strokeStyle: "#28a745", min: 0, max: 8.78}, 
    ],
    strokeColor: 'gray',  // to see which ones work best for you
    limitMax: true,     // Se false, valor maximo aumenta automaticamente se valor > valor maximo
    limitMin: true,     // Se true, o valor mínimo será fixo
    generateGradient: true,
    highDpiSupport: true,  
  };
  var target = document.getElementById('grafico{{$data->id}}'); // Elemento onde o gauge deve ser criado
  var gauge = new Gauge(target).setOptions(opts); // Criar gauge
  gauge.maxValue = 10.00; // Valor maximo
  gauge.setMinValue(0);  // Valor minimo
  gauge.animationSpeed = 32; // Velocidade da animacao
  gauge.set({{$data->nota}}); // Valor a ser exibido

   }
   //Se o objetivo for 4 e Coordenador Controladoria
   else if({{$data->objetivo_id}} == 4 && "{{$nivel}}" == "Coordenador Controladoria") {

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
    {strokeStyle: "#dc3545", min: 9.06, max: 10.00}, 
    {strokeStyle: "yellow", min: 8.79, max: 9.05}, 
    {strokeStyle: "#28a745", min: 0, max: 8.78}, 
    ],
    strokeColor: 'gray',  // to see which ones work best for you
    limitMax: true,     // Se false, valor maximo aumenta automaticamente se valor > valor maximo
    limitMin: true,     // Se true, o valor mínimo será fixo
    generateGradient: true,
    highDpiSupport: true,  
  };
  var target = document.getElementById('grafico{{$data->id}}'); // Elemento onde o gauge deve ser criado
  var gauge = new Gauge(target).setOptions(opts); // Criar gauge
  gauge.maxValue = 10.00; // Valor maximo
  gauge.setMinValue(0);  // Valor minimo
  gauge.animationSpeed = 32; // Velocidade da animacao
  gauge.set({{$data->nota}}); // Valor a ser exibido
   }

   //Se o objetivo for 4 e Coordenador ControladoriaSP
   else if({{$data->objetivo_id}} == 4 && "{{$nivel}}" == "Coordenador ControladoriaSP") {

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
    {strokeStyle: "#dc3545", min: 9.06, max: 10.00}, 
    {strokeStyle: "yellow", min: 8.79, max: 9.05}, 
    {strokeStyle: "#28a745", min: 0, max: 8.78}, 
    ],
    strokeColor: 'gray',  // to see which ones work best for you
    limitMax: true,     // Se false, valor maximo aumenta automaticamente se valor > valor maximo
    limitMin: true,     // Se true, o valor mínimo será fixo
    generateGradient: true,
    highDpiSupport: true,  
  };
  var target = document.getElementById('grafico{{$data->id}}'); // Elemento onde o gauge deve ser criado
  var gauge = new Gauge(target).setOptions(opts); // Criar gauge
  gauge.maxValue = 10.00; // Valor maximo
  gauge.setMinValue(0);  // Valor minimo
  gauge.animationSpeed = 32; // Velocidade da animacao
  gauge.set({{$data->nota}}); // Valor a ser exibido
   }

   //Se o objetivo for 6
   else if({{$data->objetivo_id}} == 6) {

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
    {strokeStyle: "#dc3545", min: 0, max: 6.99}, 
    {strokeStyle: "yellow", min: 7.00, max: 7.29}, 
    {strokeStyle: "#28a745", min: 7.30, max: 8.00}, 
    ],
    strokeColor: 'gray',  // to see which ones work best for you
    limitMax: true,     // Se false, valor maximo aumenta automaticamente se valor > valor maximo
    limitMin: true,     // Se true, o valor mínimo será fixo
    generateGradient: true,
    highDpiSupport: true,  
  };
  var target = document.getElementById('grafico{{$data->id}}'); // Elemento onde o gauge deve ser criado
  var gauge = new Gauge(target).setOptions(opts); // Criar gauge
  gauge.maxValue = 8.00; // Valor maximo
  gauge.setMinValue(0);  // Valor minimo
  gauge.animationSpeed = 32; // Velocidade da animacao
  gauge.set({{$data->nota}}); // Valor a ser exibido

   }
   //Se o objetivo for 7 e Advogado
   else if({{$data->objetivo_id}} == 7 && "{{$nivel}}" == "Advogado") {

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
    {strokeStyle: "#dc3545", min: 0, max: 5.99}, 
    {strokeStyle: "yellow", min: 6.00, max: 6.29}, 
    {strokeStyle: "#28a745", min: 6.30, max: 7.00}, 
    ],
    strokeColor: 'gray',  // to see which ones work best for you
    limitMax: true,     // Se false, valor maximo aumenta automaticamente se valor > valor maximo
    limitMin: true,     // Se true, o valor mínimo será fixo
    generateGradient: true,
    highDpiSupport: true,  
  };
  var target = document.getElementById('grafico{{$data->id}}'); // Elemento onde o gauge deve ser criado
  var gauge = new Gauge(target).setOptions(opts); // Criar gauge
  gauge.maxValue = 7.00; // Valor maximo
  gauge.setMinValue(0);  // Valor minimo
  gauge.animationSpeed = 32; // Velocidade da animacao
  gauge.set({{$data->nota}}); // Valor a ser exibido

   }
   //Se o objetivo for 7 e Superintendente
   else if({{$data->objetivo_id}} == 7 && "{{$nivel}}" == "Superintendente" && {{$data->nota}} >= 2) {

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
    {strokeStyle: "#dc3545", min: 0, max: 2.99}, 
    {strokeStyle: "#28a745", min: 2.00, max: {{$data->nota}}}, 
    ],
    strokeColor: 'gray',  // to see which ones work best for you
    limitMax: true,     // Se false, valor maximo aumenta automaticamente se valor > valor maximo
    limitMin: true,     // Se true, o valor mínimo será fixo
    generateGradient: true,
    highDpiSupport: true,  
  };
  var target = document.getElementById('grafico{{$data->id}}'); // Elemento onde o gauge deve ser criado
  var gauge = new Gauge(target).setOptions(opts); // Criar gauge
  gauge.maxValue = {{$data->nota}}; // Valor maximo
  gauge.setMinValue(0);  // Valor minimo
  gauge.animationSpeed = 32; // Velocidade da animacao
  gauge.set({{$data->nota}}); // Valor a ser exibido

   } 
    //Se o objetivo for 7 e Superintendente
    else if({{$data->objetivo_id}} == 7 && "{{$nivel}}" == "Superintendente" && {{$data->nota}} < 2) {

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
    {strokeStyle: "#dc3545", min: 0, max: 3.99}, 
    {strokeStyle: "yellow", min: 4.00, max: 4.29}, 
    {strokeStyle: "#28a745", min: 4.30, max: 5.00}, 
    ],
    strokeColor: 'gray',  // to see which ones work best for you
    limitMax: true,     // Se false, valor maximo aumenta automaticamente se valor > valor maximo
    limitMin: true,     // Se true, o valor mínimo será fixo
    generateGradient: true,
    highDpiSupport: true,  
  };
  var target = document.getElementById('grafico{{$data->id}}'); // Elemento onde o gauge deve ser criado
  var gauge = new Gauge(target).setOptions(opts); // Criar gauge
  gauge.maxValue = 4.00; // Valor maximo
  gauge.setMinValue(0);  // Valor minimo
  gauge.animationSpeed = 32; // Velocidade da animacao
  gauge.set({{$data->nota}}); // Valor a ser exibido

    }
   //Se o objetivo for 7 e Coordenador
   else if({{$data->objetivo_id}} == 7 && "{{$nivel}}" == "Coordenador") {

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
    {strokeStyle: "#dc3545", min: 0, max: 3.99}, 
    {strokeStyle: "yellow", min: 4.00, max: 4.29}, 
    {strokeStyle: "#28a745", min: 4.30, max: 5.00}, 
    ],
    strokeColor: 'gray',  // to see which ones work best for you
    limitMax: true,     // Se false, valor maximo aumenta automaticamente se valor > valor maximo
    limitMin: true,     // Se true, o valor mínimo será fixo
    generateGradient: true,
    highDpiSupport: true,  
  };
  var target = document.getElementById('grafico{{$data->id}}'); // Elemento onde o gauge deve ser criado
  var gauge = new Gauge(target).setOptions(opts); // Criar gauge
  gauge.maxValue = 5.00; // Valor maximo
  gauge.setMinValue(0);  // Valor minimo
  gauge.animationSpeed = 32; // Velocidade da animacao
  gauge.set({{$data->nota}}); // Valor a ser exibido

   }
   //Se o objetivo for 7 e Subcoordenador 
   else if({{$data->objetivo_id}} == 7 && "{{$nivel}}" == "Subcoordenador 1") {

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
    {strokeStyle: "#dc3545", min: 0, max: 3.99}, 
    {strokeStyle: "yellow", min: 4.00, max: 4.29}, 
    {strokeStyle: "#28a745", min: 4.30, max: 5.00}, 
    ],
    strokeColor: 'gray',  // to see which ones work best for you
    limitMax: true,     // Se false, valor maximo aumenta automaticamente se valor > valor maximo
    limitMin: true,     // Se true, o valor mínimo será fixo
    generateGradient: true,
    highDpiSupport: true,  
  };
  var target = document.getElementById('grafico{{$data->id}}'); // Elemento onde o gauge deve ser criado
  var gauge = new Gauge(target).setOptions(opts); // Criar gauge
  gauge.maxValue = 5.00; // Valor maximo
  gauge.setMinValue(0);  // Valor minimo
  gauge.animationSpeed = 32; // Velocidade da animacao
  gauge.set({{$data->nota}}); // Valor a ser exibido

   }
      //Se o objetivo for 7 e Subcoordenador 2
   else if({{$data->objetivo_id}} == 7 && "{{$nivel}}" == "Subcoordenador 2") {

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
    {strokeStyle: "#dc3545", min: 0, max: 3.99}, 
    {strokeStyle: "yellow", min: 4.00, max: 4.29}, 
    {strokeStyle: "#28a745", min: 4.30, max: 5.00}, 
    ],
    strokeColor: 'gray',  // to see which ones work best for you
    limitMax: true,     // Se false, valor maximo aumenta automaticamente se valor > valor maximo
    limitMin: true,     // Se true, o valor mínimo será fixo
    generateGradient: true,
    highDpiSupport: true,  
  };
  var target = document.getElementById('grafico{{$data->id}}'); // Elemento onde o gauge deve ser criado
  var gauge = new Gauge(target).setOptions(opts); // Criar gauge
  gauge.maxValue = 5.00; // Valor maximo
  gauge.setMinValue(0);  // Valor minimo
  gauge.animationSpeed = 32; // Velocidade da animacao
  gauge.set({{$data->nota}}); // Valor a ser exibido

   }
   //Se o objetivo for 7 e Gerente
   else if({{$data->objetivo_id}} == 7 && "{{$nivel}}" == "Gerente") {

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
    {strokeStyle: "#dc3545", min: 0, max: 1.29}, 
    {strokeStyle: "yellow", min: 1.30, max: 1.44}, 
    {strokeStyle: "#28a745", min: 1.45, max: 2.00}, 
    ],
    strokeColor: 'gray',  // to see which ones work best for you
    limitMax: true,     // Se false, valor maximo aumenta automaticamente se valor > valor maximo
    limitMin: true,     // Se true, o valor mínimo será fixo
    generateGradient: true,
    highDpiSupport: true,  
  };
  var target = document.getElementById('grafico{{$data->id}}'); // Elemento onde o gauge deve ser criado
  var gauge = new Gauge(target).setOptions(opts); // Criar gauge
  gauge.maxValue = 2.00; // Valor maximo
  gauge.setMinValue(0);  // Valor minimo
  gauge.animationSpeed = 32; // Velocidade da animacao
  gauge.set({{$data->nota}}); // Valor a ser exibido

   }
      //Se o objetivo for 7 e Gerente
   else if({{$data->objetivo_id}} == 7 && "{{$nivel}}" == "Gerente Equipe Passiva") {

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
    {strokeStyle: "#dc3545", min: 0, max: 1.29}, 
    {strokeStyle: "yellow", min: 1.30, max: 1.44}, 
    {strokeStyle: "#28a745", min: 1.45, max: 2.00}, 
    ],
    strokeColor: 'gray',  // to see which ones work best for you
    limitMax: true,     // Se false, valor maximo aumenta automaticamente se valor > valor maximo
    limitMin: true,     // Se true, o valor mínimo será fixo
    generateGradient: true,
    highDpiSupport: true,  
  };
  var target = document.getElementById('grafico{{$data->id}}'); // Elemento onde o gauge deve ser criado
  var gauge = new Gauge(target).setOptions(opts); // Criar gauge
  gauge.maxValue = 2.00; // Valor maximo
  gauge.setMinValue(0);  // Valor minimo
  gauge.animationSpeed = 32; // Velocidade da animacao
  gauge.set({{$data->nota}}); // Valor a ser exibido

   }
      //Se o objetivo for 7 e COO
   else if({{$data->objetivo_id}} == 7 && "{{$nivel}}" == "COO") {

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
    {strokeStyle: "#dc3545", min: 0, max: 1.29}, 
    {strokeStyle: "yellow", min: 1.30, max: 1.44}, 
    {strokeStyle: "#28a745", min: 1.45, max: 2.00}, 
    ],
    strokeColor: 'gray',  // to see which ones work best for you
    limitMax: true,     // Se false, valor maximo aumenta automaticamente se valor > valor maximo
    limitMin: true,     // Se true, o valor mínimo será fixo
    generateGradient: true,
    highDpiSupport: true,  
  };
  var target = document.getElementById('grafico{{$data->id}}'); // Elemento onde o gauge deve ser criado
  var gauge = new Gauge(target).setOptions(opts); // Criar gauge
  gauge.maxValue = 2.00; // Valor maximo
  gauge.setMinValue(0);  // Valor minimo
  gauge.animationSpeed = 32; // Velocidade da animacao
  gauge.set({{$data->nota}}); // Valor a ser exibido

   }
   //Se o objetivo for 10
   else if({{$data->objetivo_id}} == 10) {

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
    {strokeStyle: "#dc3545", min: 0, max: 89.99}, 
    {strokeStyle: "yellow", min: 90.00, max: 94.99}, 
    {strokeStyle: "#28a745", min: 95.00, max: 100.00}, 
    ],
    strokeColor: 'gray',  // to see which ones work best for you
    limitMax: true,     // Se false, valor maximo aumenta automaticamente se valor > valor maximo
    limitMin: true,     // Se true, o valor mínimo será fixo
    generateGradient: true,
    highDpiSupport: true,  
  };
  var target = document.getElementById('grafico{{$data->id}}'); // Elemento onde o gauge deve ser criado
  var gauge = new Gauge(target).setOptions(opts); // Criar gauge
  gauge.maxValue = 100.00; // Valor maximo
  gauge.setMinValue(0);  // Valor minimo
  gauge.animationSpeed = 32; // Velocidade da animacao
  gauge.set({{$data->nota}}); // Valor a ser exibido

   }

    else if({{$data->objetivo_id}} == 13) {


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
    {strokeStyle: "#dc3545", min: 0, max: 89.99}, 
    {strokeStyle: "yellow", min: 90.00, max: 94.99}, 
    {strokeStyle: "#28a745", min: 95.00, max: 100.00}, 
    ],
    strokeColor: 'gray',  // to see which ones work best for you
    limitMax: true,     // Se false, valor maximo aumenta automaticamente se valor > valor maximo
    limitMin: true,     // Se true, o valor mínimo será fixo
    generateGradient: true,
    highDpiSupport: true,  
  };
  var target = document.getElementById('grafico{{$data->id}}'); // Elemento onde o gauge deve ser criado
  var gauge = new Gauge(target).setOptions(opts); // Criar gauge
  gauge.maxValue = 100.00; // Valor maximo
  gauge.setMinValue(0);  // Valor minimo
  gauge.animationSpeed = 32; // Velocidade da animacao
  gauge.set({{$data->nota}}); // Valor a ser exibido


   }

   
  
   //Se o objetivo for 14
   else if({{$data->objetivo_id}} == 14) {

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
    {strokeStyle: "#dc3545", min: 0, max: 89.99}, 
    {strokeStyle: "yellow", min: 90.00, max: 99.99}, 
    {strokeStyle: "#28a745", min: 100.00, max: 120.00}, 
    ],
    strokeColor: 'gray',  // to see which ones work best for you
    limitMax: true,     // Se false, valor maximo aumenta automaticamente se valor > valor maximo
    limitMin: true,     // Se true, o valor mínimo será fixo
    generateGradient: true,
    highDpiSupport: true,  
  };
  var target = document.getElementById('grafico{{$data->id}}'); // Elemento onde o gauge deve ser criado
  var gauge = new Gauge(target).setOptions(opts); // Criar gauge
  gauge.maxValue = 120.00; // Valor maximo
  gauge.setMinValue(0);  // Valor minimo
  gauge.animationSpeed = 32; // Velocidade da animacao
  gauge.set({{$data->nota}}); // Valor a ser exibido

   }
   //Se o objetivo for 16
   else if({{$data->objetivo_id}} == 16) {

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
    {strokeStyle: "#dc3545", min: 0, max: 99.99}, 
    {strokeStyle: "#28a745", min: 100.00, max: 120.00}, 
    ],
    strokeColor: 'gray',  // to see which ones work best for you
    limitMax: true,     // Se false, valor maximo aumenta automaticamente se valor > valor maximo
    limitMin: true,     // Se true, o valor mínimo será fixo
    generateGradient: true,
    highDpiSupport: true,  
  };
  var target = document.getElementById('grafico{{$data->id}}'); // Elemento onde o gauge deve ser criado
  var gauge = new Gauge(target).setOptions(opts); // Criar gauge
  gauge.maxValue = 120.00; // Valor maximo
  gauge.setMinValue(0);  // Valor minimo
  gauge.animationSpeed = 32; // Velocidade da animacao
  gauge.set({{$data->nota}}); // Valor a ser exibido

   }
   //Se o objetivo for 17
   else if({{$data->objetivo_id}} == 17) {

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
    {strokeStyle: "#dc3545", min: 0, max: 89.99}, 
    {strokeStyle: "yellow", min: 90.00, max: 99.99}, 
    {strokeStyle: "#28a745", min: 100.00, max: 120.00}, 
    ],
    strokeColor: 'gray',  // to see which ones work best for you
    limitMax: true,     // Se false, valor maximo aumenta automaticamente se valor > valor maximo
    limitMin: true,     // Se true, o valor mínimo será fixo
    generateGradient: true,
    highDpiSupport: true,  
  };
  var target = document.getElementById('grafico{{$data->id}}'); // Elemento onde o gauge deve ser criado
  var gauge = new Gauge(target).setOptions(opts); // Criar gauge
  gauge.maxValue = 120.00; // Valor maximo
  gauge.setMinValue(0);  // Valor minimo
  gauge.animationSpeed = 32; // Velocidade da animacao
  gauge.set({{$data->nota}}); // Valor a ser exibido

   }
   //Se o objetivo for 18
   else if({{$data->objetivo_id}} == 18) {

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
    {strokeStyle: "#dc3545", min: 0, max: 89.99}, 
    {strokeStyle: "yellow", min: 90.00, max: 99.99}, 
    {strokeStyle: "#28a745", min: 100.00, max: 120.00}, 
    ],
    strokeColor: 'gray',  // to see which ones work best for you
    limitMax: true,     // Se false, valor maximo aumenta automaticamente se valor > valor maximo
    limitMin: true,     // Se true, o valor mínimo será fixo
    generateGradient: true,
    highDpiSupport: true,  
  };
  var target = document.getElementById('grafico{{$data->id}}'); // Elemento onde o gauge deve ser criado
  var gauge = new Gauge(target).setOptions(opts); // Criar gauge
  gauge.maxValue = 120.00; // Valor maximo
  gauge.setMinValue(0);  // Valor minimo
  gauge.animationSpeed = 32; // Velocidade da animacao
  gauge.set({{$data->nota}}); // Valor a ser exibido

   }
   //Se o objetivo for 20 
   else if({{$data->objetivo_id}} == 20) {

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
      {strokeStyle: "#dc3545", min: 0, max: 89.99}, 
    {strokeStyle: "yellow", min: 90.00, max: 99.99}, 
    {strokeStyle: "#28a745", min: 100.00, max: 120.00}, 
    ],
    strokeColor: 'gray',  // to see which ones work best for you
    limitMax: true,     // Se false, valor maximo aumenta automaticamente se valor > valor maximo
    limitMin: true,     // Se true, o valor mínimo será fixo
    generateGradient: true,
    highDpiSupport: true,  
  };
  var target = document.getElementById('grafico{{$data->id}}'); // Elemento onde o gauge deve ser criado
  var gauge = new Gauge(target).setOptions(opts); // Criar gauge
  gauge.maxValue = 1; // Valor maximo
  gauge.setMinValue(0);  // Valor minimo
  gauge.animationSpeed = 32; // Velocidade da animacao
  gauge.set({{$data->nota}}); // Valor a ser exibido

   } 
   //Se o objetivo for 21
   else if({{$data->objetivo_id}} == 21) {

     if({{$data->nota}} == 12.00) {
       var nota = 15.00;
     } else {
       var nota = {{$data->nota}};
     }

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
    {strokeStyle: "#dc3545", min: 0, max: 7.99}, 
    {strokeStyle: "yellow", min: 8.00, max: 11.99}, 
    {strokeStyle: "#28a745", min: 12.00, max: 15.00}, 
    ],
    strokeColor: 'gray',  // to see which ones work best for you
    limitMax: true,     // Se false, valor maximo aumenta automaticamente se valor > valor maximo
    limitMin: true,     // Se true, o valor mínimo será fixo
    generateGradient: true,
    highDpiSupport: true,  
  };
  var target = document.getElementById('grafico{{$data->id}}'); // Elemento onde o gauge deve ser criado
  var gauge = new Gauge(target).setOptions(opts); // Criar gauge
  gauge.maxValue = 15.00; // Valor maximo
  gauge.setMinValue(0);  // Valor minimo
  gauge.animationSpeed = 32; // Velocidade da animacao
  gauge.set(nota); // Valor a ser exibido

   }
   //Se o objetivo for 22
    else if({{$data->objetivo_id}} == 22) {

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
      {strokeStyle: "#dc3545", min: 0, max: 89.99}, 
    {strokeStyle: "yellow", min: 90.00, max: 99.99}, 
    {strokeStyle: "#28a745", min: 100.00, max: 120.00}, 
    ],
    strokeColor: 'gray',  // to see which ones work best for you
    limitMax: true,     // Se false, valor maximo aumenta automaticamente se valor > valor maximo
    limitMin: true,     // Se true, o valor mínimo será fixo
    generateGradient: true,
    highDpiSupport: true,  
  };
  var target = document.getElementById('grafico{{$data->id}}'); // Elemento onde o gauge deve ser criado
  var gauge = new Gauge(target).setOptions(opts); // Criar gauge
  gauge.maxValue = 120.00; // Valor maximo
  gauge.setMinValue(0);  // Valor minimo
  gauge.animationSpeed = 32; // Velocidade da animacao
  gauge.set({{$data->nota}}); // Valor a ser exibido

   }


  @endforeach
	</script>
<!--Fim Loop buscando as notas -->




<script>
$(document).ready(function(){
   $('.modal').modal();

});
</script>



<script>
$(document).ready(function(){
   $("#corpodiv").hide();
});
</script>

<script>
setTimeout(function() {
   $('#loading').fadeOut('fast');
   $("#corpodiv").show();
}, 2000);
</script>


@endsection