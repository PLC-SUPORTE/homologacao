@extends('Painel.Correspondente.Layouts.index')


@section('content')

 <div class="content-wrapper">
        <section class="content-header">
            <h1>
                [Solicitação Pagamento] -
                <small>[Etapa 4 - Responda o questionário abaixo]</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('Painel.Correspondente.principal') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Etapa 4</li>
            </ol>
        </section>


        <section class="content">
           @include('flash::message')
            <div class="row">

           

<script src="../../../../public/dist/css/stepbystep.css"></script>
<script src="../../../../public/dist/js/stepbystep.js"></script>
<link href="http://netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-star-rating/4.0.2/css/star-rating.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-star-rating/4.0.2/js/star-rating.min.js"></script> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>


<style>.wizard {
    margin: 20px auto;
    background: #ecf0f5;
}

    .wizard .nav-tabs {
        position: relative;
        margin: 40px auto;
        margin-bottom: 0;
        border-bottom-color: #e0e0e0;
    }

    .wizard > div.wizard-inner {
        position: relative;
    }

.connecting-line {
    height: 2px;
    background: #e0e0e0;
    position: absolute;
    width: 80%;
    margin: 0 auto;
    left: 0;
    right: 0;
    top: 50%;
    z-index: 1;
}

.wizard .nav-tabs > li.active > a, .wizard .nav-tabs > li.active > a:hover, .wizard .nav-tabs > li.active > a:focus {
    color: #555555;
    cursor: default;
    border: 0;
    border-bottom-color: transparent;
}

span.round-tab {
    width: 70px;
    height: 70px;
    line-height: 70px;
    display: inline-block;
    border-radius: 100px;
    background: #fff;
    border: 2px solid #e0e0e0;
    z-index: 2;
    position: absolute;
    left: 0;
    text-align: center;
    font-size: 25px;
}
span.round-tab i{
    color:#555555;
}
.wizard li.active span.round-tab {
    background: #fff;
    border: 2px solid #965A2C;
    
}
.wizard li.active span.round-tab i{
    color: #965A2C;
}

span.round-tab:hover {
    color: #333;
    border: 2px solid #333;
}

.wizard .nav-tabs > li {
    width: 25%;
}

.wizard li:after {
    content: " ";
    position: absolute;
    left: 46%;
    opacity: 0;
    margin: 0 auto;
    bottom: 0px;
    border: 5px solid transparent;
    border-bottom-color: #965A2C;
    transition: 0.1s ease-in-out;
}

.wizard li.active:after {
    content: " ";
    position: absolute;
    left: 46%;
    opacity: 1;
    margin: 0 auto;
    bottom: 0px;
    border: 10px solid transparent;
    border-bottom-color: #965A2C;
}

.wizard .nav-tabs > li a {
    width: 70px;
    height: 70px;
    margin: 20px auto;
    border-radius: 100%;
    padding: 0;
}

    .wizard .nav-tabs > li a:hover {
        background: transparent;
    }

.wizard .tab-pane {
    position: relative;
    padding-top: 50px;
}

.wizard h3 {
    margin-top: 0;
}

@media( max-width : 585px ) {

    .wizard {
        width: 90%;
        height: auto !important;
    }

    span.round-tab {
        font-size: 16px;
        width: 50px;
        height: 50px;
        line-height: 50px;
    }

    .wizard .nav-tabs > li a {
        width: 50px;
        height: 50px;
        line-height: 50px;
    }

    .wizard li.active:after {
        content: " ";
        position: absolute;
        left: 35%;
    }
}
.wrapper2 {
      height: 50px;
      margin-top: calc(50vh - 150px);
      margin-left: calc(50vw - 600px);
      width: 180px;
    }

    .circle {
      border-radius: 50%;
      border: 3px #0a0a0a solid;
      float: left;
      height: 50px;
      margin: 0 5px;
      width: 50px;
    }
      .circle-1 {
        animation: move 2s ease-in-out infinite;
      }
      .circle-1a {
        animation: fade 2s ease-in-out infinite;
      }
      @keyframes fade {
        0% {opacity: 0;}
        100% {opacity: 1;}
      }
      .circle-2 {
        animation: move 1s ease-in-out infinite;
      }
      @keyframes move {
        0% {transform: translateX(0);}
        100% {transform: translateX(60px);}
      }
      .circle-1a {
        margin-left: -120px;
        opacity: 0;
      }
      .circle-3 {
        animation: circle-3 1s ease-in-out infinite;
        opacity: 1;
      }
      @keyframes circle-3 {
        0% {opacity: 1;}
        100% {opacity: 0;}
      }

    h1 {
      color: #222;
      font-size: 15px;
      font-weight: 400;
      letter-spacing: 0.05em;
      margin: 40px auto;
      text-transform: uppercase;
    }
</style>

<script>
    function pergunta2obs() {
     pergunta2_obs.setAttribute('type', 'text'); 
     pergunta2_obs.setAttribute('required', '');
     $("#pergunta3Principal").hide();
   }
</script>

<script>
    function pergunta2obsSim() {
     pergunta2_obs.setAttribute('type', 'hidden'); 
     $("#pergunta3Principal").show();
   }
</script>

<script>
    function pergunta3obsBaixaAvaliacao() {
     pergunta3_obs.setAttribute('type', 'text'); 
     pergunta3_obs.setAttribute('required', ''); 
   }
</script>

<script>
    function pergunta3obs() {
     pergunta3_obs.setAttribute('type', 'hidden'); 
   }
</script>

<script>
    function test3() {
    pergunta4_obs.setAttribute('type', 'text'); 
    pergunta4_obs.setAttribute('required', '');
    $("#pergunta4Principal").hide();
    $("#pergunta5Principal").hide();
    $("#pergunta6Principal").hide();
    $("#pergunta7Principal").hide();
    $("#pergunta8Principal").hide();
    $("#pergunta9Principal").hide();
    $("#pergunta10Principal").hide();
    $("#pergunta11Principal").hide();
   }
</script>

<script>
    function test2() {
    pergunta4_obs.setAttribute('type', 'hidden'); 
    $("#pergunta4Principal").show();
    $("#pergunta5Principal").show();
    $("#pergunta6Principal").show();
    $("#pergunta7Principal").show();
    $("#pergunta8Principal").show();
    $("#pergunta9Principal").show();
    $("#pergunta10Principal").show();
    $("#pergunta11Principal").show();
   }
</script>

<script>
    function prepostoNao() {
    pergunta5_obs.setAttribute('type', 'text'); 
    $("#pergunta6Principal").hide();
    $("#pergunta7Principal").hide();
    $("#pergunta8Principal").hide();
   }
</script>

<script>
    function prepostoSim() {
    pergunta5_obs.setAttribute('type', 'hidden'); 
    $("#pergunta6Principal").show();
    $("#pergunta7Principal").show();
    $("#pergunta8Principal").show();
   }
</script>

<script>
    function testemunhaNao() {
    pergunta9_obs.setAttribute('type', 'text'); 
    $("#pergunta10Principal").hide();
    $("#pergunta11Principal").hide();
   }
</script>

<script>
    function testemunhaSim() {
    pergunta9_obs.setAttribute('type', 'hidden'); 
    $("#pergunta10Principal").show();
    $("#pergunta11Principal").show();
   }
</script>



<div class="row">
	<section>
        <div class="wizard">
            <div class="wizard-inner">
                <div class="connecting-line"></div>
                <ul class="nav nav-tabs" role="tablist">

                    <li role="presentation" class="disabled">
                       <a href="#" data-toggle="tab" aria-controls="step1" role="tab" title="Formulário para preenchimento
                           do tipo de serviço. Preencha também o número fornecido na fase de contratação.">
                            <span class="round-tab">
                                <i class="glyphicon glyphicon-list-alt"></i>
                            </span>
                        </a>
                    </li>

                    <li role="presentation" class="disabled">
                        <a href="#" data-toggle="tab" aria-controls="step2" disabled="disabled" role="tab" title="Formulário para 
                           preenchimento referente ao valor dos serviços prestados">
                            <span class="round-tab">
                                <i class="glyphicon glyphicon-usd"></i>
                            </span>
                        </a>
                    </li>
                    <li role="presentation" class="disabled">
                        <a href="#"data-toggle="tab" aria-controls="step3" disabled="disabled" role="tab" title="Formulário para anexar
                           o comprovante da fatura/nota fiscal.">
                            <span class="round-tab">
                                <i class="glyphicon glyphicon-paperclip"></i>
                            </span>
                        </a>
                    </li>
                    

                    <li role="presentation" class="active">
                        <a href="#step4" data-toggle="tab" aria-controls="complete" disabled="disabled" role="tab" title="Formulário para pesquisa 
                           de satisfação.">
                            <span class="round-tab">
                                <i class="glyphicon glyphicon-ok"></i>
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </section>
   </div>
</div>

<center>
  <div id="loadingenvia" style="display: none">
      <div class="wrapper2">
      <div class="circle circle-1"></div>
      <div class="circle circle-1a"></div>
      <div class="circle circle-2"></div>
      <div class="circle circle-3"></div>
     </div>
     <h1 style="text-align: center;">Aguarde, estamos gerando a solicitação de pagamento...&hellip;</h1>
     </div>
  </center>   

  <div id="corpodiv">  

    
           <div class="panel panel-primary setup-content" id="step-3" style="background-color:#965A2C;border-color:#965A2C;">
           <div class="panel-heading" style="background-color:#965A2C;border-color:#965A2C;">
                 <h3 class="panel-title">Preencha o questionário abaixo para finalizar sua solicitação de pagamento.</h3>
            </div>
          <div class="card card-default">
              

    <form id="form" role="form" onsubmit="btnsubmit.disabled = true; return true;" action="{{ route('Painel.Correspondente.gravarpesquisa') }}" method="POST" role="search" >
        {{ csrf_field() }}
        <div class="panel panel-primary setup-content" style="border-color:#965A2C;" id="step-4">
            <div class="panel-body">
              <div class="row">  
              <div class="col-md-3">
                   <div class="form-group">

                   
                    <input maxlength="120" value="{{$numero}}" type="hidden" id="numero" name="numero" readonly="" required="required" class="form-control" placeholder="Codigo Pasta">

                </div>  
           </div> 
              </div>  
                
                <!-- 1ªPergunta -->
                <div class="form-group">
                  <label>1- Qual a probabilidade de você recomendar a PL&C Advogados para um amigo ou colega?</label>
                  <input id="pergunta1" name="pergunta1" required=" "class="rating rating-loading" data-show-caption="false" data-min="0" data-max="5">            
                <!-- 1ªPergunta -->
                <hr>
                
                <!-- 2ªPergunta -->
                <div id="pergunta2Principal" class="form-group">
                  <label>2- Você recebeu a Orientação/Documentação completa e no prazo adequado?</label>
                <div class="form-check">
                 <label class="form-check-label">
                     <input type="radio" class="form-check-input" name="pergunta2" checked="" onclick="pergunta2obsSim()" value="1">Sim
                 </label>
                </div>
                 <div class="form-check">
                 <label class="form-check-label">
                     <input type="radio" class="form-check-input" name="pergunta2" onclick="pergunta2obs();" value="2">Não
                </label>
                  <input id="pergunta2_obs" type="hidden" class="form-control" name="pergunta2_obs" placeholder="Favor justificar o motivo.">
                </div>
                </div>
                <!-- 2ªPergunta -->
                <hr>
                
                <!-- 3ªPergunta -->
                 <div id="pergunta3Principal" class="form-group">
                 <label>3- Como você avalia a orientação prévia que recebeu?</label>
                 <div class="form-check">
                 <label id="pergunta3" class="form-check-label">
                     <input type="radio" class="form-check-input" id="pergunta3" name="pergunta3" onclick="pergunta3obs();" value="1" checked=""> Ótima
                 </label>
                 </div>
                  
                 <div class="form-check">
                 <label class="form-check-label">
                     <input type="radio" class="form-check-input" id="pergunta3" name="pergunta3" onclick="pergunta3obs();" value="2"> Boa
                </label>
                </div>
                  
                <div class="form-check">
                 <label class="form-check-label">
                     <input type="radio" class="form-check-input" name="pergunta3" onclick="pergunta3obsBaixaAvaliacao();" value="3"> Mediana
                </label>
                </div>
                  
                <div class="form-check">
                 <label class="form-check-label">
                     <input type="radio" class="form-check-input" name="pergunta3" onclick="pergunta3obsBaixaAvaliacao();" value="4"> Ruim
                </label>
                </div>
                 
                 <div class="form-check">
                 <label class="form-check-label">
                     <input type="radio" class="form-check-input" name="pergunta3" onclick="pergunta3obs();"  value="5"> Não Ocorreu
                </label>
                </div>
                <input id="pergunta3_obs" type="hidden" class="form-control" name="pergunta3_obs" placeholder="Favor justificar o motivo.">
                </div>
                 <!-- 3ªPergunta -->
 
                 <hr>
                <!-- 4ª Pergunta -->
                <label>4 - A audiência em questão foi realizada?</label>
                <div class="form-check">
                 <label class="form-check-label">
                 <input type="radio" class="form-check-input" name="pergunta4" checked="" onclick="test2();" value="1"> Sim
                 </label>
                 </div>
                
                  
                 <div class="form-check">
                 <label class="form-check-label">
                 <input type="radio" class="form-check-input" id="pergunta4" name="pergunta4" onclick="test3();" value="2"> Não (Indique o motivo)
                </label>
                </div>
                <input id="pergunta4_obs" type="hidden" class="form-control" name="pergunta4_obs" placeholder="Favor justificar o motivo.">
                 <!-- 4ªPergunta -->
                <hr>
                
                <!-- 5ªPergunta -->
                <div id="pergunta5Principal" class="form-group">
                <label>5 - A audiência em questão teve proposto?</label>
                <div class="form-check">
                 <label class="form-check-label">
                 <input type="radio" class="form-check-input" name="pergunta5" checked="" onclick="prepostoSim();" value="1"> Sim
                 </label>
                 </div>
                
                
                  
                 <div class="form-check">
                 <label class="form-check-label">
                 <input type="radio" class="form-check-input" id="pergunta5" name="pergunta5" onclick="prepostoNao();" value="2"> Não (Indique o motivo)
                </label>
                <input id="pergunta5_obs" type="hidden" class="form-control" name="pergunta5_obs" placeholder="Favor justificar o motivo.">
                </div>
                </div>
              <!-- 5ªPergunta -->
                <hr>  
                    
               <!-- 6 ªPergunta --> 
                <div id="pergunta6Principal" class="form-group">
                  <label>6 - Qual a antecedência com que o <strong> PREPOSTO </strong> chegou à audiência?</label>
                
                  <div class="form-check">
                 <label class="form-check-label">
                 <input type="radio" class="form-check-input" name="pergunta6" checked="" value="1"> 15 minutos ou menos
                </label>
                </div>

                <div class="form-check">
                 <label class="form-check-label">
                 <input type="radio" class="form-check-input" name="pergunta6" value="2"> Entre 16 e 30 minutos
                </label>
                </div>
                
                
                <div class="form-check">
                 <label class="form-check-label">
                     <input type="radio" class="form-check-input" name="pergunta6" value="3"> Mais de 30 minutos
                 </label>
                 </div>
            
                 
                <div class="form-check">
                 <label class="form-check-label">
                     <input type="radio" class="form-check-input" name="pergunta6" value="4"> Chegou atrasado
                </label>
                </div>
                </div>
               <!-- 6 ªPergunta --> 
                <hr>
                
               <!-- 7 ªPergunta --> 
                <div id="pergunta7Principal" class="form-group">
                  <label>7 - A apresentação pessoal do PREPOSTO estava adequada?</label>
                 <div class="form-check">
                 <label class="form-check-label">
                 <input type="radio" class="form-check-input" name="pergunta7" checked="" value="1"> Sim
                 </label>
                 </div>
                  
                 <div class="form-check">
                 <label class="form-check-label">
                 <input type="radio" class="form-check-input" name="pergunta7" value="2"> Não
                </label>
                </div>
                </div>
               <!-- 7 ªPergunta --> 
                <hr>
                
                <!-- 8 ªPergunta --> 
                <div id="pergunta8Principal" class="form-group">
                  <label>8 - Avalie o conhecimento do PREPOSTO sobre a ação:</label>
                  <input id="pergunta8" name="pergunta8"  class="rating rating-loading" data-show-caption="false" data-min="0" data-max="5" >      
                </div>
               <!-- 8 ªPergunta -->   
               <hr>
               
                <!-- 9 ªPergunta -->  
                 <div id="pergunta9Principal" class="form-group">
                <label>9 - A audiência em questão teve testemunha?</label>
                <div class="form-check">
                 <label class="form-check-label">
                 <input type="radio" class="form-check-input" name="pergunta9" checked=""  id="pergunta9" onclick="testemunhaSim();" value="1"> Sim
                 </label>
                 </div>
                
                 <div class="form-check">
                 <label class="form-check-label">
                 <input type="radio" class="form-check-input" id="pergunta9" name="pergunta9" onclick="testemunhaNao();" value="2"> Não (Indique o motivo)
                </label>
                 <input id="pergunta9_obs" type="hidden" class="form-control" name="pergunta9_obs" placeholder="Favor justificar o motivo.">
                </div>
                 </div>
                <!-- 9 ªPergunta -->   
                
                <hr>
                <!-- 10 ªPergunta -->   
                <div id="pergunta10Principal" class="form-group">
                  <label>10 - Qual a antecedência com que (a)s testemunha(s) chegou(ram) à audiência?</label>
                
                  <div class="form-check">
                 <label class="form-check-label">
                     <input type="radio" class="form-check-input" name="pergunta10" checked="" value="1"> 15 minutos ou menos
                </label>
                </div>
                
                 <div class="form-check">
                 <label class="form-check-label">
                 <input type="radio" class="form-check-input" name="pergunta10" value="2"> Entre 16 e 30 minutos
                </label>
                </div>

                                
                <div class="form-check">
                 <label class="form-check-label">
                 <input type="radio" class="form-check-input" name="pergunta10"  value="3"> Mais de 30 minutos
                 </label>
                 </div>

                <div class="form-check">
                 <label class="form-check-label">
                 <input type="radio" class="form-check-input" name="pergunta10" value="4"> Atrasado
                </label>
                </div>
                
                  <!--
               <div class="form-check">
                 <label class="form-check-label">
                 <input type="radio" class="form-check-input" name="pergunta10" value="4"> Não se Aplica
                </label>
                </div> 
                </div>
                10 ªPergunta -->   
               <hr>
                  
               <!-- 11 ªPergunta -->   
                <div id="pergunta11Principal" class="form-group">
                  <label>11- Se houve TESTEMUNHAS, avalie o conhecimento delas sobre a ação:</label>
                  <input id="pergunta11" name="pergunta11" class="rating rating-loading" data-show-caption="false" data-min="0" data-max="5">          
                </div>
              <!-- 11 ªPergunta -->   
               <hr>
                
                </div>
                </div>
                <button class="btn btn-primary nextBtn pull-right fa fa-arrow-right" type="button" id="btnsubmit" onClick="envia();" style="background-color:#4B4B4B;border-color:#4B4B4B;">&nbsp;&nbsp;Finalizar registro</button>
            </div>
        </div>
        </div>
    </form>
</div>
    </div>
        </section>

<script>
function envia() {

    document.getElementById("loadingenvia").style.display = "";
    document.getElementById("corpodiv").style.display = "none";
    document.getElementById("form").submit();
}    
</script>

@endsection


