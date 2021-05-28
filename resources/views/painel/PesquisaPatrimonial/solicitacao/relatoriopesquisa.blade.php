
<!DOCTYPE html>
<html class="loading" lang="pt-br" data-textdirection="ltr">
<head>
<meta http-equiv="Content-Language" content="pt-br">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<link rel="shortcut icon" href="{{ asset('/public/assets/imgs/casinha.png')}}" />
<title>Relatório Pesquisa - Pesquisa Patrimonial</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="author" content="Portal PL&C">
<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900' type='text/css'>
<link rel="stylesheet" type="text/css" href="https://demo.harnishdesign.net/html/koice/vendor/bootstrap/css/bootstrap.min.css"/>
<link rel="stylesheet" type="text/css" href="https://demo.harnishdesign.net/html/koice/vendor/font-awesome/css/all.min.css"/>
<link rel="stylesheet" type="text/css" href="https://demo.harnishdesign.net/html/koice/css/stylesheet.css"/>


<style>
@media print {
    .graficoscore {
        -webkit-print-color-adjust: exact; 
    }
    .progress {
    position: relative;
    width: 360px;
    }
    .scorenumeros {
      margin-left: -600px;
    }
    body {margin-top: 10mm; margin-bottom: 10mm; 
           margin-left: 0mm; margin-right: 0mm}
    .cardscore {
      width: 460px;
    }       

}
 .score0 {
   margin-left: -355px;
 }
 .score30 {
   margin-left: -160px;
 }
 .score50 {
   margin-left: -10px;
 }
 .score70 {
   margin-left: 140px;
 }
 .score100 {
   margin-left: 340px;
 }


</style>




</head>

<body style="overflow-x: hidden;">

<div class="container-fluid invoice-container" style="background-size: 1024px 1024px; background-image: url('{{ asset('/public/imgs/pesquisapatrimonial/background.png')}}'"> 
  <!-- Header -->
  <header style="background-color: white;">
   <div class="wave"> 
      <div class="row align-items-center" style="margin-top: -70px; width: 850px; margin-left: -70px; margin-bottom: 50px; background-color: white;" id="wave">
        <div class="col-sm-5 text-center text-sm-left mb-3 mb-sm-0"> <img id="logo" style="width: 200px;" src="{{url('/public/imgs/logomarca.png')}}"/>
        <h4 style="font-size:15px;font-weight:bold;color:#242663">Relatório de Investigação Patrimonial</h4>
         </div>
      
        <div class="col-sm-6 text-center text-sm-right" style="margin-left:60px;">
        <h6 class="card-subtitle mb-2 " style="text-align: left;;font-size: 10px;font-weight: bold;color: #242663;margin-top:20px;">INFORMAÇÃO PARTICULAR E CONFIDENCIAL</h6>
          <p class="mb-0" style="font-size: 9px;color: #242663;text-align: left;">O conteúdo deste documento está sujeito à revisão e 
            constitui informação confidencial, legalmente protegida e destinada exclusivamente ao Cliente.
             A utilização deste documento para fins diversos, sem prévia e expressa autorização do Cliente ou do PLC, 
             é absolutamente proibida. Se o leitor deste relatório não for o seu destinatário, ele deverá descarta-lo 
             de forma definitiva, ficando ciente que a divulgação, distribuição e/ou cópia deste documento são estritamente
            proibidas, estando tais atos sujeitos às cominações penais e cíveis previstas em lei.</p>
          <!-- <p class="mb-0">Pesquisa #666</p> -->
        </div>
      </div>
 </div> 
  </header>
  <!-- Main Content -->
  <main>

    <div class="row">
   
      <div class="col-4" style="height: 40px; margin-left: -55px;" >
        <div class="card" style="height:260px;width: 23rem; border-radius:20px; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);" >
          <div class="card-body">

            <h6 class="card-subtitle mb-2 " style="font-size: 12px;font-weight: bold;color: #242663">DADOS DO CLIENTE</h6>
  
              <b style="font-size: 11px;color: #242663">Razão Social: {{$datas->ClienteRazao}}</b><br>
              <b style="font-size: 11px;color: #242663">CPF/CNPJ: {{$datas->ClienteCodigo}}</b>
            <br><br>
            <h6 class="card-subtitle mb-2 " style="font-size: 12px;font-weight: bold;color: #242663">DADOS CADASTRAIS</h6>
              <b style="font-size: 11px;color: #242663">Pesquisado: {{$datas->Nome}}</b><br>
              <b style="font-size: 11px;color: #242663">CPF/CNPJ: {{$codigo}}</b><br>
              <b style="font-size: 11px;color: #242663">Base territorial: {{$datas->Cidade}}</b>
          </div>
        </div>
      </div>

    </div>


    <div class="row">


      <div class="col-6 graficoscore" style="margin-left: 320px; margin-top: -38px;">
        <div class="card cardscore" style="background-color: #242663; width: 440px; height: 260px;border-radius:20px; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
        <div class="card-body"> 
          <h5 class="card-subtitle mb-2 text-white" style="margin-left: -15px;">SCORECARD</h5>
          <div style="text-align: center;">
            <!--Calculo Score --> 
            <h1 style="color: white; font-size: 50px;">{{$score}}</h1>
            <h5 style="color: white; margin-top: -20px; margin-left: -10px;">de 100</h5>
            <!--Calculo Score -->
            <p style="color: white;">
              <div class="container">
                <div class="row">
                    <div class="col-md-12">
                    <img src="{{url('/public/imgs/pesquisapatrimonial/snow.png')}}"  style="float: left; width: 25px; margin-top: 22px; margin-left: -25px;">

                        <div class="progress">

                            <div class="progress-bar" style="width: {{$score}}%; background-color: white;">
                                <div class="progress-value">{{$score}}%</div>
                            </div>
                            
                        </div>

                         <img src="{{url('/public/imgs/pesquisapatrimonial/flame.png')}}"  style="float: left; width: 25px; margin-top: -48px; margin-left: 370px;">

                         <div class="scorenumeros">
                         <p class="score0" style="color: white;margin-top: 4px;">0</p>
                         <p class="score30" style="color: white;margin-top: -42px;">30</p>
                         <p class="score50" style="color: white;margin-top: -42px;">50</p>
                         <p class="score70" style="color: white;margin-top: -42px;">70</p>
                         <p class="score100" style="color: white;margin-top: -42px;">100</p>
                         </div>

                         <style>
                            #chart line {
                              stroke: white; 
                              stroke-width:1;
                              margin-top: -70px;
                            }
                          </style>

                          <!-- <svg id="chart" width="350" height="225" style="margin-top: -90px; margin-left: -45px;">
                            <line x1="20" y1="20" x2="20" y2="40"></line>
                          </svg>

                          <svg id="chart" width="350" height="225" style="margin-top: -246px; margin-left: 84px;">
                            <line x1="20" y1="20" x2="20" y2="40"></line>
                          </svg>

                          <svg id="chart" width="350" height="225" style="margin-top: -290px; margin-left: 158px;">
                            <line x1="20" y1="20" x2="20" y2="40"></line>
                          </svg>

                          <svg id="chart" width="350" height="225" style="margin-top: -330px; margin-left: 235px;">
                            <line x1="20" y1="20" x2="20" y2="40"></line>
                          </svg>

                          <svg id="chart" width="350" height="225" style="margin-top: -375px; margin-left: 332px;">
                            <line x1="20" y1="20" x2="20" y2="40"></line>
                          </svg> -->

                    </div>
                </div>
            </div>

          <style>
            .progress{
                height: 25px;
                line-height: 45px;
                overflow: visible;
                background: #f7f7f7;
                border-radius: 25px;
                margin: 20px 0;
                box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1) inset;
                /* background-image: linear-gradient(90deg, rgb(36, 38, 99) 15%, 
                rgb(36, 38, 99) 75%, rgb(36, 38, 99)  50%, 
                                  rgba(255, 255, 255, 0.15) 95%, rgba(255, 255, 255, 0.15) 75%, rgba(0, 0, 0, 0) 75%, rgba(0, 0, 0, 0)); */
            }

            .progress .progress-bar{
                position: relative;
                border-radius: 25px;
                background-image: url("https://portal.plcadvogados.com.br/portal/public/imgs/pesquisapatrimonial/calor.png");
                background-size: 100%;
                animation: animate-positive 2s;
            }


            .progress .progress-bar span i:after{
                content: "";
                width: 100%;
                border-top: 1px solid #fff;
                position: absolute;
                top: 22px;
                left: 2px;
            }
            .progress .progress-value{
                position: absolute;
                top: -13px;
                right: 0px;
                width: 1px;
                color: transparent;
                display: block;
                font-size: 0px;
                font-weight: bold;
                padding: 4px 4px;
                background: white;
                border-radius: 50px;
            }
          </style>
            </p>
        </div>
        </div>
      </div>
      </div>
    </div>

    <br>

    <div class="row">
      <div class="col-2"> 
        <div class="card" style="width: 23rem; margin-left: -55px;border-radius:20px;box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
          <div class="card-body">
            <h6 class="card-subtitle mb-2" style="font-size: 11px; color: #242663;">
              <b style="font-size: 10px;color: #242663">PATRIMÔNIO AVALIADO
                <span class="pull-right" style="font-size: 12px;font-weight: bold;color: #242663; margin-left: 100px;">R$ <?php echo number_format($ValorTotal,2,",",".") ?></span></b></h6>
              
              <script src="https://www.gstatic.com/charts/loader.js"></script>
            <script type="text/javascript">
              google.charts.load("current", {packages:["corechart"]});
              google.charts.setOnLoadCallback(drawChart);
              function drawChart() {
                var data = google.visualization.arrayToDataTable([
                  ["Element", "Density", { role: "style" } ],
                  ["Imóveis", {{$ValorImovel}}, "#FFA934"],
                  ["Veículos", {{$ValorVeiculo}}, "#FFA934"],
                  ["Empresas", {{$ValorEmpresa}}, "#FFA934"],
                ]);
          
                var view = new google.visualization.DataView(data);
                view.setColumns([0, 1,
                { calc: "stringify",
                  sourceColumn: 1,
                  type: "string",
                  role: "annotation" },
                2]);
          
                var options = {
                  width: 350,
                  height: 310,
                  bar: {groupWidth: "50%"},
                  legend: { position: "none" },
                };
                var chart = new google.visualization.BarChart(document.getElementById("barchart_values"));
                chart.draw(view, options);
            }
            </script>
          <div id="barchart_values" style="margin-left: -20px;"></div>
        </div>
      </div>
    </div>

    <div class="col-2"> 
      <div class="card" style="width: 15rem; margin-left: 198px; border-radius:20px;box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
      <div class="card-body" style="height: 365px;">
        <h6 class="card-subtitle mb-2" style="font-size: 12px; color: #242663;"><b>PATRIMÔNIO LOCALIZADO</b></h6>
            <img src="{{url('/public/imgs/pesquisapatrimonial/imoveis.png')}}"  style="float: left; width: 34px; margin-top: 10px; margin-left: 5px;">
            <p style="font-size: 11px; color: #242663; margin-top: 50px;"><b>IMÓVEIS</b></p>
            <h1 style="margin-top: -75px; font-size: 45px; margin-left: 150px;">{{$QuantidadeImovel}}</h1>
            <p style="font-size: 12px; margin-left: 110px; margin-top: -15px; color: #242663;"><b style="font-weight: bold;color: #242663">R$ <?php echo number_format($ValorImovel,2,",",".") ?></b></p>
            <hr style="color: #242663">
            <img src="{{url('/public/imgs/pesquisapatrimonial/veiculos.png')}}"  style="float: left; width: 34px; margin-top: 10px; margin-left: 5px;">
            <p style="font-size: 11px; color: #242663; margin-top: 55px;"><b>VEÍCULOS</b></p>
            <h1 style="margin-top: -75px; font-size: 45px; margin-left: 150px;">{{$QuantidadeVeiculo}}</h1>
            <p style="font-size: 12px; margin-left: 110px; margin-top: -15px; color: #242663;"><b style="font-weight: bold;color: #242663">R$ <?php echo number_format($ValorVeiculo,2,",",".") ?></b></p>
           <hr style="color: #242663">
           <img src="{{url('/public/imgs/pesquisapatrimonial/empresas.png')}}"  style="float: left; width: 34px; margin-top: 10px; margin-left: 5px;">
            <p style="font-size: 11px; color: #242663; margin-top: 65px;"><b>EMPRESAS</b></p>
            <h1 style="margin-top: -75px; font-size: 45px; margin-left: 150px;">{{$QuantidadeEmpresa}}</h1>
            <p style="font-size: 12px; margin-left: 110px; margin-top: -15px; color: #242663;"><b style="font-weight: bold;color: #242663">R$ <?php echo number_format($ValorEmpresa,2,",",".") ?></b></p>
           <hr style="color: #242663">
        
      </div>
    </div>
  </div>

    <div class="col-2" style="width: 50px;"> 
      <div class="card" style="width: 12rem; margin-left: 320px;border-radius:20px;box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
      <div class="card-body">
        <h6 class="card-subtitle mb-2 " style="font-size: 10px; color: #242663;"><b>PARTICIPAÇÕES SOCIETÁRIAS</b></h6>
         <img src="{{url('/public/imgs/pesquisapatrimonial/participacoessocietarias.png')}}"  style="float: left; width: 40px; margin-top: 14px; margin-right: 40px;">

         <h6 style="font-size: 12px; margin-top: 25px;font-weight: bold;color: #242663">Não possui</h6>
        
      </div>
    </div>
    <br>
    <div class="card" style="width: 12rem; margin-left: 320px;border-radius:20px;box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
      <div class="card-body">
        <h6 class="card-subtitle mb-2 " style="font-size: 10px; color: #242663;"><b>AÇÕES JUDICIAIS</b></h6>
        <img src="{{url('/public/imgs/pesquisapatrimonial/acoesjudiciais.png')}}"  style="float: left; width: 40px; margin-top: 14px; margin-right: 40px;">

        <h6 style="font-size: 12px; margin-top: 25px;font-weight: bold;color: #242663">Não possui</h6>
        
        
      </div>
    </div>
    <br>
    <div class="card" style="width: 12rem; margin-left: 320px;border-radius:20px;box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
      <div class="card-body">
        <h6 class="card-subtitle mb-2 " style="font-size: 10px; color: #242663;"><b>PROCESSOS COMO RÉU</b></h6>
        <img src="{{url('/public/imgs/pesquisapatrimonial/processos.png')}}"  style="float: left; width: 40px; margin-top: 9px; margin-right: 40px;">

        <h6 style="font-size: 12px; margin-top: 25px;font-weight: bold;color: #242663">Não possui</h6>
        
        
      </div>
    </div>
    <br> 
  </div>
    </div>
      <br>
      <div class="card" style="width: 820px; margin-left: -58px; border-radius:20px; margin-top: -12px;box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
          <div class="card-body">
            <h6 class="card-subtitle mb-2" style="font-size: 13px; color: #242663;"><b>ESCOPO, ABRANGÊNCIA E METODOLOGIA</b></h6>
            <img src="{{url('/public/imgs/pesquisapatrimonial/escopo.png')}}"  style="float: left; width: 45px; margin-right: 25px;">
                <ul style="color: #242663; font-size: 13px; margin-left: 30px;">
                  <li style="color: #242663">O Escopo deste trabalho consiste na atribuição de Scorecard e elaboração de Relatório de Investigação Patrimonial com fundamento na análise das informações obtidas a partir de fontes públicas ou fornecidas pelo Cliente.</li>
                    <li style="color: #242663">A pesquisa foi realizada nos locais mencionados ao longo do Relatório e não levou em consideração a existência de bens em outros locais nem de direitos e haveres cuja informação não seja pública ou não foi fornecida pelo Cliente.</li>
                    <li style="color: #242663">Na execução deste trabalho consideramos os status das informações até a data de emissão dos respectivos documentos, que se encontram em anexo, e os valores mencionados foram atualizados pelo índice indicado em cada caso.</li>
                      <li style="color: #242663">A metodologia empregada na execução deste Relatório Gerencial compreendeu o exame das informações fornecidas, das obtidas e das certidões aqui mencionadas expressamente.</li>
                <li style="color: #242663">Quando há mais de um Pesquisado relacionado a mesma operação, será atribuído um Scorecard e elaborado um Relatório de Investigação Patrimonial para cada pessoa pesquisada.</li>
                </ul>
            </div>

            <div class="card-body">
              <h6 class="card-subtitle mb-2" style="font-size: 13px; color: #242663;"><b>ENTENDA O SCORECARD PL&C</b></h6>
              <img src="{{url('/public/imgs/pesquisapatrimonial/score.png')}}"  style="float: left; width: 45px; margin-right: 25px;">
                  <ul style="color: #242663; font-size: 13px; margin-left: 30px;">
                  <li style="color: #242663">
                    O Scorecard foi definido em razão do comportamento do mercado financeiro e com bases as informações cadastradas do pesquisado.
                    </li>
                    <li style="color: #242663">
                    Quanto mais perto de 100% estiver o Scorecard maior a probabilidade de recuperabilidade do crédito.
                    </li>
                  </ul>

            </div>
          </div>
        </div>
    </div>
  </main>
  <br>

</div>
</body>
</html>