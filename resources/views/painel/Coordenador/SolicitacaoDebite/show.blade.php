<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PL&C Advogados - Workflow solicitação pagamento</title>

    <link rel="stylesheet" href="https://e6t7a8v2.stackpathcdn.com/tutorial/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('/public/assets/css/bootstrap-grid.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/assets/css/common-1.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/assets/css/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('/public/assets/imgs/casinha.png')}}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
    <body style="margin-left: -100px;overflow: hidden;">
    <script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <div class="Timeline" style="margin-top: -60px;">


  <svg id="firstLine" height="5" width="200"><line x1="0" y1="0" x2="0" y2="0" style="stroke:white;stroke-width:5" /></svg>

  
<!-- Solicitação de pagamento -->
  <div class="event1" data-placement="left" title="Sua solicitação foi criada com sucesso.">
    <div class="event1Bubble">
      <div class="eventTime">
      </div>
      <div id="eventTitle1">Solicitação pagamento</div>
    </div>
    <div style="margin-top: -60px;">
        <i class="fa fa-chevron-right" style="color: green;"></i>
    </div>
  </div>
<!-- Solicitação de pagamento -->

  <svg style="margin-top: -99px;margin-left: 98px;"height="5">
 <line x1="0" y1="0" x2="415" y2="0" style="stroke:green;stroke-width:5" />
  </svg>

  
  <!-- Revisão Técnica -->
  <!--Verifico se está neste processo atual -->
  @if($statusAtual == "6")
  <div class="event2" data-placement="left" title="Sua solicitação está neste processo.">
      <div class="event2Bubble">
          <div class="eventTime">
        </div>
        <div id="eventTitle3">Revisão Técnica</div>
    </div>
    <div style="margin-top: -60px;">
        <i class="fa fa-chevron-down" style="color:#F8C602"></i>
    </div>
</div>
<svg id="svg-vertical" height="5" width="150"><line x1="0" y1="0" x2="120" y2="0" style="stroke:#F8C602;stroke-width:5" /></svg>
<svg id="svg-vertical100" height="5"><line x1="0" y1="0" x2="450" y2="0" style="stroke:#bbbbbb60;stroke-width:5" /></svg>

@elseif($statusAtual == "7")
<div class="event2" data-placement="left" title="Sua solicitação foi aprovada pela equipe da revisão técnica.">
    <div class="event2Bubble">
        <div class="eventTime">
        </div>
        <div id="eventTitle3">Revisão Técnica</div>
    </div>
    <div style="margin-top: -60px;">
        <i class="fa fa-chevron-down" style="color:green"></i>
    </div>
</div>
<svg id="svg-vertical" height="5" width="150"><line x1="0" y1="0" x2="120" y2="0" style="stroke:green;stroke-width:5" /></svg>
<svg id="svg-vertical100" height="5"><line x1="0" y1="0" x2="450" y2="0" style="stroke:#bbbbbb60;stroke-width:5" /></svg>
@elseif($statusAtual == "8")
<div class="event2" data-placement="left" title="Sua solicitação foi reprovada pela equipe da revisão técnica.">
    <div class="event2Bubble">
        <div class="eventTime">
        </div>
        <div id="eventTitle3">Revisão Técnica</div>
    </div>
    <div style="margin-top: -60px;">
        <i class="fa fa-chevron-down" style="color:green"></i>
    </div>
</div>
<svg id="svg-vertical" height="5" width="150"><line x1="0" y1="0" x2="120" y2="0" style="stroke:green;stroke-width:5" /></svg>
<svg id="svg-vertical100" height="5"><line x1="0" y1="0" x2="450" y2="0" style="stroke:#bbbbbb60;stroke-width:5" /></svg>
@elseif($statusAtual == "9")
<div class="event2" data-toggle="tooltip" data-placement="left" title="Sua solicitação já foi aprovada pela equipe da revisão técnica.">
    <div class="event2Bubble">
        <div class="eventTime">
        </div>
        <div id="eventTitle3">Revisão Técnica</div>
    </div>
    <div style="margin-top: -60px;">
        <i class="fa fa-chevron-down" style="color:green"></i>
    </div>
</div>
<svg id="svg-vertical" height="5" width="150"><line x1="0" y1="0" x2="120" y2="0" style="stroke:green;stroke-width:5" /></svg>
<svg id="svg-vertical100" height="5"><line x1="0" y1="0" x2="450" y2="0" style="stroke:#bbbbbb60;stroke-width:5" /></svg>
@elseif($statusAtual == "10")
<div class="event2" data-placement="left" title="Sua solicitação foi reprovada pela equipe do financeiro.">
    <div class="event2Bubble">
        <div class="eventTime">
        </div>
        <div id="eventTitle3">Revisão Técnica</div>
    </div>
    <div style="margin-top: -60px;">
        <i class="fa fa-chevron-down" style="color:green"></i>
    </div>
</div>
<svg id="svg-vertical" height="5" width="150"><line x1="0" y1="0" x2="120" y2="0" style="stroke:green;stroke-width:5" /></svg>
<svg id="svg-vertical100" height="5"><line x1="0" y1="0" x2="450" y2="0" style="stroke:green;stroke-width:5" /></svg>

@elseif($statusAtual == "11")
<div class="event2" data-placement="left" title="Sua solicitação já foi aprovada pela equipe da revisão técnica.">
    <div class="event2Bubble">
        <div class="eventTime">
        </div>
        <div id="eventTitle3">Revisão Técnica</div>
    </div>
    <div style="margin-top: -60px;">
        <i class="fa fa-chevron-down" style="color:green"></i>
    </div>
</div>
<svg id="svg-vertical" height="5" width="150"><line x1="0" y1="0" x2="120" y2="0" style="stroke:green;stroke-width:5" /></svg>
<svg id="svg-vertical100" height="5"><line x1="0" y1="0" x2="450" y2="0" style="stroke:#bbbbbb60;stroke-width:5" /></svg>

@elseif($statusAtual == "12")
<div class="event2" data-placement="left" title="Sua solicitação já foi aprovada pela equipe da revisão técnica.">
    <div class="event2Bubble">
        <div class="eventTime">
        </div>
        <div id="eventTitle3">Revisão Técnica</div>
    </div>
    <div style="margin-top: -60px;">
        <i class="fa fa-chevron-down" style="color:green"></i>
    </div>
</div>
<svg id="svg-vertical" height="5" width="150"><line x1="0" y1="0" x2="120" y2="0" style="stroke:green;stroke-width:5" /></svg>
<svg id="svg-vertical100" height="5"><line x1="0" y1="0" x2="450" y2="0" style="stroke:#bbbbbb60;stroke-width:5" /></svg>

@elseif($statusAtual == "13")
<div class="event2" data-placement="left" title="Sua solicitação foi cancelada.">
    <div class="event2Bubble">
        <div class="eventTime">
        </div>
        <div id="eventTitle3">Revisão Técnica</div>
    </div>
    <div style="margin-top: -60px;">
        <i class="fa fa-chevron-down" style="color:green"></i>
    </div>
</div>
<svg id="svg-vertical" height="5" width="150"><line x1="0" y1="0" x2="120" y2="0" style="stroke:green;stroke-width:5" /></svg>
<svg id="svg-vertical100" height="5"><line x1="0" y1="0" x2="450" y2="0" style="stroke:#bbbbbb60;stroke-width:5" /></svg>
@elseif($statusAtual == "14")
<div class="event2" data-placement="left" title="Sua solicitação já foi aprovada pela equipe da revisão técnica.">
    <div class="event2Bubble">
        <div class="eventTime">
        </div>
        <div id="eventTitle3">Revisão Técnica</div>
    </div>
    <div style="margin-top: -60px;">
        <i class="fa fa-chevron-down" style="color:green"></i>
    </div>
</div>
<svg id="svg-vertical" height="5" width="150"><line x1="0" y1="0" x2="120" y2="0" style="stroke:green;stroke-width:5" /></svg>
<svg id="svg-vertical100" height="5"><line x1="0" y1="0" x2="450" y2="0" style="stroke:#bbbbbb60;stroke-width:5" /></svg>
@else 
<div class="event2">
        <div class="event2Bubble">
            <div class="eventTime">
            </div>
            <div id="eventTitle3">Revisão Técnica</div>
        </div>
        <div style="margin-top: -60px;">
            <i class="fa fa-chevron-down"></i>
        </div>
    </div>
    <svg id="svg-vertical" height="5" width="150"><line x1="0" y1="0" x2="120" y2="0" style="stroke:#bbbbbb60;stroke-width:5" /></svg>
    <svg id="svg-vertical100" height="5"><line x1="0" y1="0" x2="450" y2="0" style="stroke:#bbbbbb60;stroke-width:5" /></svg>
    @endif     
    <!-- Revisão Técnica -->
    
    
    <style>

    #svg-vertical100{
        margin-top: 310px;
        margin-left: -487px;
        width: 488px;
    }

    #firstLine{
        margin-top: -100px;
    }
    #svg-vertical{
        transform: rotate(90deg);
        margin-top: 387px;
        margin-left: -83px;
        margin-bottom: 330px;
    }

    #svg-horizontal{
        margin-top: 343px;
        margin-left: -340px;
    }

    #svg-vertical2{
        transform: rotate(90deg);
        margin-top: 890px;
        margin-left: -525px;
        margin-bottom: 330px;
    }

    #svg-vertical3{
        transform: rotate(90deg);
        margin-top: 595px;
        margin-left: -505px;
        margin-bottom: 330px;
        width: 355px;
    }

    #svg-vertical4{
        transform: rotate(90deg);
        margin-top: 530px;
        margin-left: -325px;
        margin-bottom: 330px;
    }

    #svg-vertical5{
        transform: rotate(90deg);
        margin-top: 550px;
        margin-left: 269px;
        margin-bottom: 330px;
    }

    #svg-vertical5Complemento{
        margin-top: 343px;
        margin-left: -230px;
    }

    #svg-vertical6{
        transform: rotate(90deg);
        margin-left: -525px;
        margin-top: 495px;
    }

    #svg-vertical7{
        transform: rotate(90deg);
        margin-left: -475px;
        margin-top: 495px;
    }

    #svg-vertical8{
        transform: rotate(90deg);
        margin-left: -625px;
        margin-top: 705px;
    }

    #svg-vertical9{
        transform: rotate(90deg);
        margin-left: -326px;
        margin-top: 545px;
    }

    #svg-vertical10{
        transform: rotate(90deg);
        margin-left: -320px;
        margin-top: 653px;
    }

    #svg-vertical13{
        transform: rotate(90deg);
        margin-left: -300px;
        margin-top: 825px;
    }
    #svg-vertical11{
        transform: rotate(90deg);
        margin-left: -300px;
        margin-top: 852px;
    }

    #svg-vertical12{
        transform: rotate(90deg);
        margin-left: -859px;
        margin-top: 559px;
    }
    
    #svg-horizontal2{
        margin-top: 800px;
        margin-left: -210px;
    }

    #svg-horizontal4{
        margin-top: 343px;
        margin-left: 441px;
    }

    #svg-horizontal5{
        margin-top: 300px;
        margin-left: -75px;
    }

    #svg-horizontal6{
        margin-top: 845px;
        margin-left: -797px;
    }

    #svg-horizontal7{
        margin-top: 250px;
        margin-left: -155px;
    }

    #svg-horizontal8{
        margin-top: 415px;
        margin-left: -152px;
    }

    #svg-horizontal9{
        margin-top: 310px;
        margin-left: -470px;
    }
/* 
    #svg-horizontal20{
        margin-top: 310px;
        margin-left: -475px;
    } */

    #svg-horizontal10{
        margin-top: 760px;
        margin-left: 10px;
    }

    #svg-horizontal11{
        margin-top: 400px;
        margin-left: 25px;
    }

    #svg-horizontal12{
        margin-top: 810px;
        margin-left: -208px;
    }
    
    #svg-horizontal13{
        margin-top: 760px;
        margin-left: -208px;
    }

    #event3{
        margin-top: 360px;
        margin-left: -129px;
    }

    #event4{
        margin-top: 770px;
        margin-left: -110px;
    }

    #event5{
        margin-left: -85px;
    }

    #event6{
        /* margin-left: -85px; */
        margin-top: -65px;
    }

    #svg-verticalRev{
        transform: rotate(90deg);
        margin-left: -300px;
        margin-top: 500px;
    }

    #svg-horizontalProgPagamento{
        margin-left: -265px;
        margin-top: 454px;
    }

    #svg-verticalComprovantePag{
        transform: rotate(90deg);
        margin-left: -300px;
        margin-top: 1010px;
    }

    #svg-horizontalInicio{
        margin-left: 40px;
        margin-top: 21px;
    }

    #svg-horizontalLegenda1{
        margin-top: 695px;
        margin-left: -70px;
    }

    #svg-horizontalLegenda2{
        margin-top: 777px;
        margin-left: -300px;
    }

    #svg-horizontalLegenda3{
        margin-top: 857px;
        margin-left: -300px;
    }
    </style>

    <!--Se passou pela Revisão Técnica -->
    @if($statusAtual == "6")
    <div class="event3" id="event3">
        <div class="event3Bubble" style="font-size: 11px;background-color: #F8C602"></div>
    </div>
    @else
    <div class="event3" id="event3">
        <div class="event3Bubble" style="font-size: 11px;background-color: green"></div>
    </div>
    @endif



    <!--Se foi reprovado para edição -->
    @if($statusAtual == "8")
    <svg id="svg-horizontal"><line x1="0" y1="0" x2="415" y2="0" style="stroke:green;stroke-width:5" /></svg>
    <svg id="svg-vertical2"><line x1="0" y1="0" x2="0" y2="0" style="stroke:green;stroke-width:5" /></svg>
    <svg id="svg-horizontal2"><line x1="0" y1="0" x2="0" y2="0" style="stroke:green;stroke-width:5" /></svg>
    <svg id="svg-vertical3"><line x1="0" y1="0" x2="120" y2="0" style="stroke:#F8C602;stroke-width:5" /></svg>
    @elseif($statusAtual == "10")
    <svg id="svg-horizontal"><line x1="0" y1="0" x2="415" y2="0" style="stroke:#bbbbbb60;stroke-width:5" /></svg>
    <svg id="svg-vertical2"><line x1="0" y1="0" x2="0" y2="0" style="stroke:#bbbbbb60;stroke-width:5" /></svg>
    <svg id="svg-horizontal2"><line x1="0" y1="0" x2="0" y2="0" style="stroke:#bbbbbb60;stroke-width:5" /></svg>
    <svg id="svg-vertical3"><line x1="0" y1="0" x2="120" y2="0" style="stroke:#F8C602;stroke-width:5" /></svg>
    @else
    <svg id="svg-horizontal"><line x1="0" y1="0" x2="415" y2="0" style="stroke:#bbbbbb60;stroke-width:5" /></svg>
    <svg id="svg-vertical2"><line x1="0" y1="0" x2="0" y2="0" style="stroke:#bbbbbb60;stroke-width:5" /></svg>
    <svg id="svg-horizontal2"><line x1="0" y1="0" x2="0" y2="0" style="stroke:#bbbbbb60;stroke-width:5" /></svg>
    <svg id="svg-vertical3"><line x1="0" y1="0" x2="120" y2="0" style="stroke:#bbbbbb60;stroke-width:5" /></svg>
    @endif


<!-- Edição solicitação de pagamento -->
    <div class="event4" id="event4" data-placement="left" title="Etapa onde a solicitação deve ser corrigida.">
        <div class="event4Bubble">
            <div id="eventTitle2">Editar solicitação</div>
        <!--Se a reprovação vier da revisão financeiro -->    
        @if($statusAtual == "10")
        <div style="margin-top: 4px;margin-left: 38px;">
        <i class="fa fa-chevron-up" style="color: green;"></i>
        </div>        
        @else
        <div style="margin-top: 4px;margin-left: 38px;">
        <i class="fa fa-chevron-up" style="color: #bbbbbb60;"></i>
        </div>  
        @endif
        <!--Se a reprovação vier da revisão tecnica-->
        @if($statusAtual == "8")
        <div style="margin-top: -50px;margin-left: 127px;">
        <i class="fa fa-chevron-left" style="color: green;"></i>
        </div>        
        @else
        <div style="margin-top: -50px;margin-left: 127px;">
        <i class="fa fa-chevron-left" style="color: #bbbbbb60;"></i>
        </div>  
        @endif
        </div>
    </div>


<!-- Se a solicitação foi aprovada pela revisão técnica -->
@if($statusAtual == "7")
    <svg id="svg-horizontal4"><line x1="0" y1="0" x2="154" y2="0" style="stroke:green;stroke-width:5" /></svg>
    <svg id="svg-horizontal5"><line x1="0" y1="0" x2="0" y2="0" style="stroke:white;stroke-width:5" /></svg>
<!-- Se já avançou mostrar de verde -->    
@elseif($statusAtual == "9")
<svg id="svg-horizontal4"><line x1="0" y1="0" x2="154" y2="0" style="stroke:green;stroke-width:5" /></svg>
   <svg id="svg-horizontal5"><line x1="0" y1="0" x2="0" y2="0" style="stroke:white;stroke-width:5" /></svg>
@elseif($statusAtual == "10")
<svg id="svg-horizontal4"><line x1="0" y1="0" x2="154" y2="0" style="stroke:green;stroke-width:5" /></svg>
   <svg id="svg-horizontal5"><line x1="0" y1="0" x2="0" y2="0" style="stroke:white;stroke-width:5" /></svg>
@elseif($statusAtual == "11")
<svg id="svg-horizontal4"><line x1="0" y1="0" x2="154" y2="0" style="stroke:green;stroke-width:5" /></svg>
   <svg id="svg-horizontal5"><line x1="0" y1="0" x2="0" y2="0" style="stroke:white;stroke-width:5" /></svg>
@elseif($statusAtual == "12")
<svg id="svg-horizontal4"><line x1="0" y1="0" x2="154" y2="0" style="stroke:green;stroke-width:5" /></svg>
    <svg id="svg-horizontal5"><line x1="0" y1="0" x2="0" y2="0" style="stroke:white;stroke-width:5" /></svg>
@elseif($statusAtual == "14")
<svg id="svg-horizontal4"><line x1="0" y1="0" x2="154" y2="0" style="stroke:green;stroke-width:5" /></svg>
    <svg id="svg-horizontal5"><line x1="0" y1="0" x2="0" y2="0" style="stroke:white;stroke-width:5" /></svg>
@else 
    <svg id="svg-horizontal4"><line x1="0" y1="0" x2="154" y2="0" style="stroke:#bbbbbb60;stroke-width:5" /></svg>
   <svg id="svg-horizontal5"><line x1="0" y1="0" x2="0" y2="0" style="stroke:white;stroke-width:5" /></svg>
@endif



<!-- Se a solicitação está no processo de aprovação financeiro -->
@if($statusAtual == "7" )
    <div class="event5" id="event5" data-toggle="tooltip" data-placement="left" title="Sua solicitação está neste processo.">
        <div class="event5Bubble">
        <div id="eventTitle5">Revisão financeiro</div>
        <div style="margin-top: -28px;margin-left: -10px;">
        <i class="fa fa-chevron-right" style="color: #bbbbbb60;"></i>
        </div>
        </div>
    </div>     
 @elseif($statusAtual == "9") 
 <div class="event5" id="event5"  data-toggle="tooltip" data-placement="left" title="Sua solicitação já foi aprovada pela equipe do financeiro.">
        <div class="event5Bubble">
        <div id="eventTitle5">Revisão financeiro</div>
        <div style="margin-top: -28px;margin-left: -10px;">
        <i class="fa fa-chevron-right" style="color: #bbbbbb60;"></i>
        </div>
        </div>
    </div>     
 @elseif($statusAtual == "10") 
 <div class="event5" id="event5"  data-toggle="tooltip" data-placement="left" title="Sua solicitação foi reprovada pela equipe do financeiro.">
        <div class="event5Bubble">
        <div id="eventTitle5">Revisão financeiro</div>
        <div style="margin-top: -28px;margin-left: -10px;">
        <i class="fa fa-chevron-right" style="color: #bbbbbb60;"></i>
        </div>
        </div>
    </div>      
 @elseif($statusAtual == "11") 
 <div class="event5" id="event5"  data-toggle="tooltip" data-placement="left" title="Sua solicitação já foi aprovada pela equipe do financeiro.">
        <div class="event5Bubble">
            <div id="eventTitle5">Revisão financeiro</div>
            <div style="margin-top: -28px;margin-left: -10px;">
        <i class="fa fa-chevron-right" style="color: green;"></i>
        </div>
        </div>
    </div>     
@elseif($statusAtual == "12") 
<div class="event5" id="event5"  data-toggle="tooltip" data-placement="left" title="Sua solicitação já foi aprovada pela equipe do financeiro.">
        <div class="event5Bubble">
            <div id="eventTitle5">Revisão financeiro</div>
            <div style="margin-top: -28px;margin-left: -10px;">
        <i class="fa fa-chevron-right" style="color: green;"></i>
        </div>
        </div>
    </div> 
 @elseif($statusAtual == "14") 
 <div class="event5" id="event5"  data-toggle="tooltip" data-placement="left" title="Sua solicitação já foi aprovada pela equipe do financeiro.">
        <div class="event5Bubble">
            <div id="eventTitle5">Revisão financeiro</div>
            <div style="margin-top: -28px;margin-left: -10px;">
        <i class="fa fa-chevron-right" style="color: green;"></i>
        </div>
        </div>
    </div>  
@else 
<div class="event5" id="event5"  data-toggle="tooltip" data-placement="left" title="Sua solicitação até o momento não foi aprovada pela equipe do financeiro.">
        <div class="event5Bubble">
            <div id="eventTitle5">Revisão financeiro</div>
            <div style="margin-top: -28px;margin-left: -10px;">
        <i class="fa fa-chevron-right" style="color: #bbbbbb60;"></i>
        </div>
        </div>
    </div>    
@endif               

    <!--Se o financeiro reprovou -->
    @if($statusAtual == "8")
    <svg id="svg-horizontal6"><line x1="0" y1="0" x2="0" y2="0" style="stroke:#bbbbbb60;stroke-width:5" /></svg>
    @elseif($statusAtual == "10")
    <svg id="svg-horizontal6"><line x1="0" y1="0" x2="0" y2="0" style="stroke:green;stroke-width:5" /></svg>
    @else
    <svg id="svg-horizontal6"><line x1="0" y1="0" x2="0" y2="0" style="stroke:#bbbbbb60;stroke-width:5" /></svg>
    @endif

    <!--Se esta aguardando o aprovado pelo financeiro -->
    @if($statusAtual == "7")
    <svg id="svg-vertical5"><line x1="0" y1="0" x2="0" y2="0" style="stroke:red;stroke-width:5" /></svg>
    <svg id="svg-vertical5Complemento"><line x1="0" y1="0" x2="118" y2="0" style="stroke:#F8C602;stroke-width:5" /></svg>
    {{-- <svg id="svg-verticalRev"><line x1="0" y1="0" x2="60" y2="0" style="stroke:blue;stroke-width:5" /></svg> --}}
    <svg id="svg-horizontal7"><line x1="0" y1="0" x2="0" y2="0" style="stroke:white;stroke-width:5" /></svg>
    <svg id="svg-vertical6"><line x1="0" y1="0" x2="0" y2="0" style="stroke:white;stroke-width:5" /></svg>
    @elseif($statusAtual == "9")
    <svg id="svg-vertical5"><line x1="0" y1="0" x2="0" y2="0" style="stroke:white;stroke-width:5" /></svg>
    <svg id="svg-vertical5Complemento"><line x1="0" y1="0" x2="118" y2="0" style="stroke:green;stroke-width:5" /></svg>
    {{-- <svg id="svg-verticalRev"><line x1="0" y1="0" x2="60" y2="0" style="stroke:blue;stroke-width:5" /></svg> --}}
    <svg id="svg-horizontal7"><line x1="0" y1="0" x2="0" y2="0" style="stroke:white;stroke-width:5" /></svg>
    <svg id="svg-vertical6"><line x1="0" y1="0" x2="0" y2="0" style="stroke:white;stroke-width:5" /></svg>
    @elseif($statusAtual == "10")
    <svg id="svg-vertical5"><line x1="0" y1="0" x2="0" y2="0" style="stroke:white;stroke-width:5" /></svg>
    <svg id="svg-vertical5Complemento"><line x1="0" y1="0" x2="118" y2="0" style="stroke:green;stroke-width:5" /></svg>
    {{-- <svg id="svg-verticalRev"><line x1="0" y1="0" x2="60" y2="0" style="stroke:blue;stroke-width:5" /></svg> --}}
    <svg id="svg-horizontal7"><line x1="0" y1="0" x2="0" y2="0" style="stroke:white;stroke-width:5" /></svg>
    <svg id="svg-vertical6"><line x1="0" y1="0" x2="0" y2="0" style="stroke:white;stroke-width:5" /></svg>
    @elseif($statusAtual == "11")
    <svg id="svg-vertical5"><line x1="0" y1="0" x2="0" y2="0" style="stroke:white;stroke-width:5" /></svg>
    <svg id="svg-vertical5Complemento"><line x1="0" y1="0" x2="118" y2="0" style="stroke:green;stroke-width:5" /></svg>
    {{-- <svg id="svg-verticalRev"><line x1="0" y1="0" x2="60" y2="0" style="stroke:blue;stroke-width:5" /></svg> --}}
    <svg id="svg-horizontal7"><line x1="0" y1="0" x2="0" y2="0" style="stroke:white;stroke-width:5" /></svg>
    <svg id="svg-vertical6"><line x1="0" y1="0" x2="0" y2="0" style="stroke:white;stroke-width:5" /></svg>
    @elseif($statusAtual == "12")
    <svg id="svg-vertical5"><line x1="0" y1="0" x2="0" y2="0" style="stroke:white;stroke-width:5" /></svg>
    <svg id="svg-vertical5Complemento"><line x1="0" y1="0" x2="118"y2="0" style="stroke:green;stroke-width:5" /></svg>
    {{-- <svg id="svg-verticalRev"><line x1="0" y1="0" x2="60" y2="0" style="stroke:blue;stroke-width:5" /></svg> --}}
    <svg id="svg-horizontal7"><line x1="0" y1="0" x2="0" y2="0" style="stroke:white;stroke-width:5" /></svg>
    <svg id="svg-vertical6"><line x1="0" y1="0" x2="0" y2="0" style="stroke:white;stroke-width:5" /></svg>
    @elseif($statusAtual == "14")
    <svg id="svg-vertical5"><line x1="0" y1="0" x2="0" y2="0" style="stroke:white;stroke-width:5" /></svg>
    <svg id="svg-vertical5Complemento"><line x1="0" y1="0" x2="118"y2="0" style="stroke:green;stroke-width:5" /></svg>
    {{-- <svg id="svg-verticalRev"><line x1="0" y1="0" x2="60" y2="0" style="stroke:blue;stroke-width:5" /></svg> --}}
    <svg id="svg-horizontal7"><line x1="0" y1="0" x2="0" y2="0" style="stroke:white;stroke-width:5" /></svg>
    <svg id="svg-vertical6"><line x1="0" y1="0" x2="0" y2="0" style="stroke:white;stroke-width:5" /></svg>
    @else
    <svg id="svg-vertical5"><line x1="0" y1="0" x2="0" y2="0" style="stroke:white;stroke-width:5" /></svg>
    <svg id="svg-vertical5Complemento"><line x1="0" y1="0" x2="118" y2="0" style="stroke:#bbbbbb60;stroke-width:5" /></svg>
    {{-- <svg id="svg-verticalRev"><line x1="0" y1="0" x2="60" y2="0" style="stroke:blue;stroke-width:5" /></svg> --}}
    <svg id="svg-horizontal7"><line x1="0" y1="0" x2="0" y2="0" style="stroke:white;stroke-width:5" /></svg>
    <svg id="svg-vertical6"><line x1="0" y1="0" x2="0" y2="0" style="stroke:white;stroke-width:5" /></svg>
    @endif


    <!-- Estrela Financeiro -->
    @if($statusAtual == "9")
    <div class="event6" id="event6">
        <div class="event6Bubble" style="background-color: green"></div>
    </div>
    @elseif($statusAtual == "10")
    <div class="event6" id="event6">
        <div class="event6Bubble" style="background-color: green"></div>
    </div>
    @elseif($statusAtual == "11")
    <div class="event6" id="event6">
        <div class="event6Bubble" style="background-color: green"></div>
    </div>
    @elseif($statusAtual == "12")
    <div class="event6" id="event6">
        <div class="event6Bubble" style="background-color: green"></div>
    </div>
    @elseif($statusAtual == "14")
    <div class="event6" id="event6">
        <div class="event6Bubble" style="background-color: green"></div>
    </div>
    @else
    <div class="event6" id="event6">
        <div class="event6Bubble" style="background-color: #bbbbbb60"></div>
    </div>
    @endif




    <!--Se foi reprovado para edição-->
    @if($statusAtual == "7")
    <svg id="svg-horizontal8">
        <line x1="0" y1="0" x2="50" y2="0" style="stroke:white;stroke-width:5"/>
    </svg>
    <svg id="svg-vertical7"><line x1="0" y1="0" x2="30" y2="0" style="stroke:#F8C602;stroke-width:5" /></svg>
    <svg height="5" id="svg-horizontal9" width="400"><line x1="0" y1="0" x2="372" y2="0" style="stroke:#bbbbbb60;stroke-width:5" /></svg>
    {{-- <svg height="5" id="svg-horizontal20"><line x1="0" y1="0" x2="100" y2="0" style="stroke:#bbbbbb60;stroke-width:5" /></svg> --}}
    <svg id="svg-vertical8"><line x1="0" y1="0" x2="0" y2="0" style="stroke:white;stroke-width:5" /></svg>
    <svg id="svg-vertical9">
        <line x1="0" y1="0" x2="83" y2="0" style="stroke:#bbbbbb60;stroke-width:5" />
    </svg>
    <svg id="svg-vertical13">
        <line x1="0" y1="0" x2="75" y2="0" style="stroke:#bbbbbb60;stroke-width:5" />
    </svg> 
    @elseif($statusAtual == "8")
    <svg id="svg-horizontal8">
        <line x1="0" y1="0" x2="50" y2="0" style="stroke:white;stroke-width:5"/>
    </svg>
    <svg id="svg-vertical7"><line x1="0" y1="0" x2="30" y2="0" style="stroke:#bbbbbb60;stroke-width:5" /></svg>
    <svg height="5" id="svg-horizontal9" width="400"><line x1="0" y1="0" x2="372" y2="0" style="stroke:#bbbbbb60;stroke-width:5" /></svg>
    {{-- <svg height="5" id="svg-horizontal20"><line x1="0" y1="0" x2="100" y2="0" style="stroke:#bbbbbb60;stroke-width:5" /></svg> --}}
    <svg id="svg-vertical8"><line x1="0" y1="0" x2="0" y2="0" style="stroke:white;stroke-width:5" /></svg>
    <svg id="svg-vertical9">
        <line x1="0" y1="0" x2="83" y2="0" style="stroke:#bbbbbb60;stroke-width:5" />
    </svg>
    <svg id="svg-vertical13">
        <line x1="0" y1="0" x2="75" y2="0" style="stroke:#bbbbbb60;stroke-width:5" />
    </svg> 
    @elseif($statusAtual == "9")
    <svg id="svg-horizontal8">
        <line x1="0" y1="0" x2="50" y2="0" style="stroke:white;stroke-width:5"/>
    </svg>
    <svg id="svg-vertical7"><line x1="0" y1="0" x2="33" y2="0" style="stroke:green;stroke-width:5" /></svg>
    <svg height="5" id="svg-horizontal9" width="400"><line x1="0" y1="0" x2="372" y2="0" style="stroke:#bbbbbb60;stroke-width:5" /></svg>
    {{-- <svg height="5" id="svg-horizontal20"><line x1="0" y1="0" x2="100" y2="0" style="stroke:#bbbbbb60;stroke-width:5" /></svg> --}}
    <svg id="svg-vertical8"><line x1="0" y1="0" x2="0" y2="0" style="stroke:white;stroke-width:5" /></svg>
    <svg id="svg-vertical9">
        <line x1="0" y1="0" x2="83" y2="0" style="stroke:#bbbbbb60;stroke-width:5" />
    </svg>
    <svg id="svg-vertical13">
        <line x1="0" y1="0" x2="75" y2="0" style="stroke:#bbbbbb60;stroke-width:5" />
    </svg> 
    @elseif($statusAtual == "10")
    <svg id="svg-horizontal8">
        <line x1="0" y1="0" x2="50" y2="0" style="stroke:white;stroke-width:5"/>
    </svg>
    <svg id="svg-vertical7"><line x1="0" y1="0" x2="33" y2="0" style="stroke:green;stroke-width:5" /></svg>
    <svg height="5" id="svg-horizontal9" width="400"><line x1="0" y1="0" x2="372" y2="0" style="stroke:green;stroke-width:5" /></svg>
    {{-- <svg height="5" id="svg-horizontal20"><line x1="0" y1="0" x2="100" y2="0" style="stroke:#bbbbbb60;stroke-width:5" /></svg> --}}
    <svg id="svg-vertical8"><line x1="0" y1="0" x2="0" y2="0" style="stroke:white;stroke-width:5" /></svg>
    <svg id="svg-vertical9">
        <line x1="0" y1="0" x2="83" y2="0" style="stroke:#bbbbbb60;stroke-width:5" />
    </svg>
    <svg id="svg-vertical13">
        <line x1="0" y1="0" x2="75" y2="0" style="stroke:#bbbbbb60;stroke-width:5" />
    </svg> 
    @elseif($statusAtual == "11")
    <svg id="svg-horizontal8">
        <line x1="0" y1="0" x2="50" y2="0" style="stroke:white;stroke-width:5"/>
    </svg>
    <svg id="svg-vertical7"><line x1="0" y1="0" x2="33" y2="0" style="stroke:green;stroke-width:5" /></svg>
    <svg height="5" id="svg-horizontal9" width="400"><line x1="0" y1="0" x2="372" y2="0" style="stroke:#bbbbbb60;stroke-width:5" /></svg>
    {{-- <svg height="5" id="svg-horizontal20"><line x1="0" y1="0" x2="100" y2="0" style="stroke:#bbbbbb60;stroke-width:5" /></svg> --}}
    <svg id="svg-vertical8"><line x1="0" y1="0" x2="0" y2="0" style="stroke:white;stroke-width:5" /></svg>
    <svg id="svg-vertical9">
        <line x1="0" y1="0" x2="83" y2="0" style="stroke:#bbbbbb60;stroke-width:5" />
    </svg>
    <svg id="svg-vertical13">
        <line x1="0" y1="0" x2="75" y2="0" style="stroke:#bbbbbb60;stroke-width:5" />
    </svg> 
    @elseif($statusAtual == "12")
    <svg id="svg-horizontal8">
        <line x1="0" y1="0" x2="50" y2="0" style="stroke:white;stroke-width:5"/>
    </svg>
    <svg id="svg-vertical7"><line x1="0" y1="0" x2="33" y2="0" style="stroke:green;stroke-width:5" /></svg>
    <svg height="5" id="svg-horizontal9" width="400"><line x1="0" y1="0" x2="372" y2="0" style="stroke:#bbbbbb60;stroke-width:5" /></svg>
    {{-- <svg height="5" id="svg-horizontal20"><line x1="0" y1="0" x2="100" y2="0" style="stroke:#bbbbbb60;stroke-width:5" /></svg> --}}
    <svg id="svg-vertical8"><line x1="0" y1="0" x2="0" y2="0" style="stroke:white;stroke-width:5" /></svg>
    <svg id="svg-vertical9">
        <line x1="0" y1="0" x2="83" y2="0" style="stroke:#bbbbbb60;stroke-width:5" />
    </svg>
    <svg id="svg-vertical13">
        <line x1="0" y1="0" x2="75" y2="0" style="stroke:#bbbbbb60;stroke-width:5" />
    </svg> 
    @elseif($statusAtual == "13")
    <svg id="svg-horizontal8">
        <line x1="0" y1="0" x2="50" y2="0" style="stroke:white;stroke-width:5"/>
    </svg>
    <svg id="svg-vertical7"><line x1="0" y1="0" x2="33" y2="0" style="stroke:#bbbbbb60;stroke-width:5" /></svg>
    <svg height="5" id="svg-horizontal9" width="400"><line x1="0" y1="0" x2="372" y2="0" style="stroke:#bbbbbb60;stroke-width:5" /></svg>
    {{-- <svg height="5" id="svg-horizontal20"><line x1="0" y1="0" x2="100" y2="0" style="stroke:#bbbbbb60;stroke-width:5" /></svg> --}}
    <svg id="svg-vertical8"><line x1="0" y1="0" x2="0" y2="0" style="stroke:white;stroke-width:5" /></svg>
    <svg id="svg-vertical9">
        <line x1="0" y1="0" x2="83" y2="0" style="stroke:green;stroke-width:5" />
    </svg>
    <svg id="svg-vertical13">
        <line x1="0" y1="0" x2="75" y2="0" style="stroke:green;stroke-width:5" />
    </svg> 
    @elseif($statusAtual == "14")
    <svg id="svg-horizontal8">
        <line x1="0" y1="0" x2="50" y2="0" style="stroke:white;stroke-width:5"/>
    </svg>
    <svg id="svg-vertical7"><line x1="0" y1="0" x2="33" y2="0" style="stroke:green;stroke-width:5" /></svg>
    <svg height="5" id="svg-horizontal9" width="400"><line x1="0" y1="0" x2="372" y2="0" style="stroke:#bbbbbb60;stroke-width:5" /></svg>
    {{-- <svg height="5" id="svg-horizontal20"><line x1="0" y1="0" x2="100" y2="0" style="stroke:#bbbbbb60;stroke-width:5" /></svg> --}}
    <svg id="svg-vertical8"><line x1="0" y1="0" x2="0" y2="0" style="stroke:white;stroke-width:5" /></svg>
    <svg id="svg-vertical9">
        <line x1="0" y1="0" x2="83" y2="0" style="stroke:#bbbbbb60;stroke-width:5" />
    </svg>
    <svg id="svg-vertical13">
        <line x1="0" y1="0" x2="75" y2="0" style="stroke:#bbbbbb60;stroke-width:5" />
    </svg> 
    @else
    <svg id="svg-horizontal8">
        <line x1="0" y1="0" x2="50" y2="0" style="stroke:white;stroke-width:5"/>
    </svg>
    <svg id="svg-vertical7"><line x1="0" y1="0" x2="30" y2="0" style="stroke:#bbbbbb60;stroke-width:5" /></svg>
    <svg height="5" id="svg-horizontal9" width="400"><line x1="0" y1="0" x2="372" y2="0" style="stroke:#bbbbbb60;stroke-width:5" /></svg>
    {{-- <svg height="5" id="svg-horizontal20"><line x1="0" y1="0" x2="100" y2="0" style="stroke:#bbbbbb60;stroke-width:5" /></svg> --}}
    <svg id="svg-vertical8"><line x1="0" y1="0" x2="0" y2="0" style="stroke:white;stroke-width:5" /></svg>
    <svg id="svg-vertical9">
        <line x1="0" y1="0" x2="83" y2="0" style="stroke:#bbbbbb60;stroke-width:5" />
    </svg>
    <svg id="svg-vertical13">
        <line x1="0" y1="0" x2="75" y2="0" style="stroke:#bbbbbb60;stroke-width:5" />
    </svg>  
    @endif

    <div class="event7" id="event7">
        <div class="event7Bubble">
            <div id="eventTitle4">&nbsp;&nbsp;&nbsp;&nbsp;CANCELAR</div>       
     <div style="margin-top: -48px;margin-left: 55px;">
     @if($statusAtual == "13")      
        <i class="fa fa-chevron-down" style="color: green;"></i>
     @else
     <i class="fa fa-chevron-down" style="color: #bbbbbb60;"></i>
     @endif
        </div>
        </div>
    </div>

    <!--Se foi cancelado -->
    @if($statusAtual == "13")
    <svg id="svg-horizontal10"><line x1="0" y1="0" x2="0" y2="0" style="stroke:white;stroke-width:5" /></svg>
    @else
    <svg id="svg-horizontal10"><line x1="0" y1="0" x2="0" y2="0" style="stroke:white;stroke-width:5" /></svg>
    @endif

    <!--Programação de pagamento -->
    @if($statusAtual == "12")
    <svg id="svg-horizontal11"><line x1="0" y1="0" x2="0" y2="0" style="stroke:white;stroke-width:5" /></svg>
    <!--Se já foi pago, mostrar que já foi feito a programação de pagamento -->
    @elseif($statusAtual == "9")
    <svg id="svg-horizontal11"><line x1="0" y1="0" x2="0" y2="0" style="stroke:white;stroke-width:5" /></svg>
    @elseif($statusAtual == "11")
    <svg id="svg-horizontal11"><line x1="0" y1="0" x2="0" y2="0" style="stroke:white;stroke-width:5" /></svg>
    @elseif($statusAtual == "14")
    <svg id="svg-horizontal11"><line x1="0" y1="0" x2="0" y2="0" style="stroke:white;stroke-width:5" /></svg>
    @else
    <svg id="svg-horizontal11"><line x1="0" y1="0" x2="0" y2="0" style="stroke:white;stroke-width:5" /></svg>
    @endif

    <!-- Se foi aprovado pelo financeiro -->
    @if($statusAtual == "9")
    <div class="event8" id="event8">
        <div class="event8Bubble">
            <div id="eventTitle6">Programação Pagamento</div>
            <div style="margin-top: -27px;margin-left: -10px;">
        <i class="fa fa-chevron-right" style="color: green;"></i>
        </div>
        <div style="margin-top: 43px;margin-left: 52px;">
        <i class="fa fa-chevron-down" style="color: green;"></i>
        </div>
        </div>
        <svg id="svg-horizontalProgPagamento">
            <line x1="0" y1="0" x2="139" y2="0" style="stroke:green;stroke-width:5;" /></svg>
    </div> 
    @elseif($statusAtual == "11")
    <div class="event8" id="event8">
        <div class="event8Bubble">
            <div id="eventTitle6">Programação Pagamento</div>
            <div style="margin-top: -27px;margin-left: -10px;">
        <i class="fa fa-chevron-right" style="color: green;"></i>
        </div>
        <div style="margin-top: 43px;margin-left: 52px;">
        <i class="fa fa-chevron-down" style="color: green;"></i>
        </div>
        </div>
        <svg id="svg-horizontalProgPagamento">
            <line x1="0" y1="0" x2="139" y2="0" style="stroke:green;stroke-width:5;" />
        </svg>
    </div> 

    @elseif($statusAtual == "12")
    <div class="event8" id="event8">
        <div class="event8Bubble">
            <div id="eventTitle6">Programação Pagamento</div>
            <div style="margin-top: -27px;margin-left: -10px;">
        <i class="fa fa-chevron-right" style="color: green;"></i>
        </div>
        <div style="margin-top: 43px;margin-left: 52px;">
        <i class="fa fa-chevron-down" style="color: green;"></i>
        </div>
        </div>
        <svg id="svg-horizontalProgPagamento"><line x1="0" y1="0" x2="139" y2="0" style="stroke:green;stroke-width:5;" /></svg>
    </div>
    @elseif($statusAtual == "14")
    <div class="event8" id="event8">
        <div class="event8Bubble">
            <div id="eventTitle6">Programação Pagamento</div>
            <div style="margin-top: -27px;margin-left: -10px;">
        <i class="fa fa-chevron-right" style="color: green;"></i>
        </div>
        <div style="margin-top: 43px;margin-left: 52px;">
        <i class="fa fa-chevron-down" style="color: #F8C602;"></i>
        </div>
        </div>
        <svg id="svg-horizontalProgPagamento"><line x1="0" y1="0" x2="139" y2="0" style="stroke:green;stroke-width:5;" /></svg>
    </div>
    @else
    <div class="event8" id="event8">
        <div class="event8Bubble">
            <div id="eventTitle6">Programação Pagamento</div>
            <div style="margin-top: -27px;margin-left: -10px;">
        <i class="fa fa-chevron-right" style="color: #bbbbbb60;"></i>
        </div>
        <div style="margin-top: 43px;margin-left: 52px;">
        <i class="fa fa-chevron-down" style="color: #bbbbbb60;"></i>
        </div>
        </div>
        <svg id="svg-horizontalProgPagamento"><line x1="0" y1="0" x2="139" y2="0" style="stroke:#bbbbbb60;stroke-width:5;" /></svg>
    </div>
    @endif

    <!--Se foi está aguardando pagamento  -->
    @if($statusAtual == "12")
    <svg id="svg-vertical10"><line x1="0" y1="0" x2="43" y2="0" style="stroke:green;stroke-width:5" /></svg>
    <!--Se já foi pago -->
    @elseif($statusAtual == "9")
    <svg id="svg-vertical10"><line x1="0" y1="0" x2="43" y2="0" style="stroke:green;stroke-width:5" /></svg>
    @elseif($statusAtual == "11")
    <svg id="svg-vertical10"><line x1="0" y1="0" x2="43" y2="0" style="stroke:green;stroke-width:5" /></svg>
    @elseif($statusAtual == "14")
    <svg id="svg-vertical10"><line x1="0" y1="0" x2="43" y2="0" style="stroke:#F8C602;stroke-width:5" /></svg>
    @else
    <svg id="svg-vertical10"><line x1="0" y1="0" x2="43" y2="0" style="stroke:#bbbbbb60;stroke-width:5"/></svg>
    @endif

    <!--Se já foi anexado o comprovante de pagamento -->
    @if($statusAtual == "11")
    <div class="event9" id="event9">
        <div class="event9Bubble">
        <div id="eventTitle7">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pago</div>
        <div style="margin-top: 30px;margin-left: 57px;">
        <i class="fa fa-chevron-down" style="color: green;"></i>
        </div>
     </div>
    </div>

    <div class="event11" id="event11"  data-toggle="tooltip" data-placement="left">
        <div class="event11Bubble" style="background-color: green">
        </div>
    </div>
    @else
    <div class="event9" id="event9">
        <div class="event9Bubble">
        <div id="eventTitle7">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pago</div>
        <div style="margin-top: 30px;margin-left: 57px;">
        <i class="fa fa-chevron-down" style="color: #bbbbbb60;"></i>
        </div>
     </div>
    </div>

    <div class="event11" id="event11"  data-toggle="tooltip" data-placement="left">
        <div class="event11Bubble">
        </div>
    </div>
    @endif

    
    <!--Aguardando o anexo do comprovante de pagamento-->
    @if($statusAtual == "9")
    <div class="event12" id="event12"  data-toggle="tooltip" data-placement="left">
        <div class="event12Bubble">
            <div id="eventTitle1">Comprovante de Pagamento</div>
        </div>
        <svg id="svg-verticalComprovantePag"><line x1="0" y1="0" x2="35" y2="0" style="stroke:#F8C602;stroke-width:5" /></svg>
    </div>
    @elseif($statusAtual == "11")
    <div class="event12" id="event12"  data-toggle="tooltip" data-placement="left">
        <div class="event12Bubble">
            <div id="eventTitle1">Comprovante de Pagamento</div>
        </div>
        <svg id="svg-verticalComprovantePag"><line x1="0" y1="0" x2="35" y2="0" style="stroke:green;stroke-width:5" /></svg>
    </div>
    @else
    <div class="event12" id="event12"  data-toggle="tooltip" data-placement="left">
        <div class="event12Bubble">
            <div id="eventTitle1">Comprovante de Pagamento</div>
        </div>
        <svg id="svg-verticalComprovantePag"><line x1="0" y1="0" x2="35" y2="0" style="stroke:#bbbbbb60;stroke-width:5" /></svg>
    </div>
    @endif

    <!--Solicitação paga -->
    @if($statusAtual == "9")
    <svg id="svg-vertical11"><line x1="0" y1="0" x2="26" y2="0" style="stroke:green;stroke-width:5" /></svg>

    <div class="event13" id="event13"  data-toggle="tooltip" data-placement="left">
        
    <div class="event14Bubble" style="margin-top: -5.2em; display:block;">
        <svg id="svg-horizontalInicio"><line x1="0" y1="0" x2="600" y2="0" style="stroke:green;stroke-width:5" /></svg>
    </div>

    </div>

    @elseif($statusAtual == "11")
    <svg id="svg-vertical11"><line x1="0" y1="0" x2="26" y2="0" style="stroke:green;stroke-width:5" /></svg>

    <div class="event13" id="event13"  data-toggle="tooltip" data-placement="left">
        
        <div class="event14Bubble" style="margin-top: -5.2em; display:block;">
        <svg id="svg-horizontalInicio"><line x1="0" y1="0" x2="600" y2="0" style="stroke:green;stroke-width:5" /></svg>
        </div>
    
        </div>
    @else
    <svg id="svg-vertical11"><line x1="0" y1="0" x2="26" y2="0" style="stroke:#bbbbbb60;stroke-width:5" /></svg>

    <div class="event13" id="event13"  data-toggle="tooltip" data-placement="left">
        
    <div class="event14Bubble" style="margin-top: -5.2em; display:block;">
        <svg id="svg-horizontalInicio"><line x1="0" y1="0" x2="600" y2="0" style="stroke:green;stroke-width:5" /></svg>
    </div>

    </div>
    @endif

    <!-- Se já foi cancelado mostrar fundo verde, se não cinza -->
    @if($statusAtual == "13")
    <div class="event10" id="event10"  data-toggle="tooltip" data-placement="left" title="Sua solicitação foi cancelada.">
        <div class="event10Bubble" style="background-color: green;">
        </div>
    </div>
    <svg id="svg-vertical12"><line x1="0" y1="0" x2="0" y2="0" style="stroke:white;stroke-width:5" /></svg>
    @else
    <div class="event10" id="event10"  data-toggle="tooltip" data-placement="left" title="Sua solicitação não está cancelada.">
        <div class="event10Bubble">
        </div>
    </div>
    <svg id="svg-vertical12"><line x1="0" y1="0" x2="0" y2="0" style="stroke:white;stroke-width:5" /></svg>
    @endif

    <!--Se a solicitação esta pendente -->
    @if($statusAtual == "8")
    <svg id="svg-horizontal12"><line x1="0" y1="0" x2="0" y2="0" style="stroke:white;stroke-width:5" /></svg>
    <svg id="svg-vertical12" style="margin-left:-895px;"><line x1="0" y1="0" x2="25" y2="0" style="stroke:#bbbbbb60;stroke-width:5" /></svg>
    <svg id="svg-horizontal13"><line x1="0" y1="0" x2="133" y2="0" style="stroke:white;stroke-width:5" /></svg>
    @elseif($statusAtual == "10")
    <svg id="svg-horizontal12"><line x1="0" y1="0" x2="0" y2="0" style="stroke:white;stroke-width:5" /></svg>
    <svg id="svg-vertical12" style="margin-left:-895px;"><line x1="0" y1="0" x2="25" y2="0" style="stroke:green;stroke-width:5" /></svg>
    <svg id="svg-horizontal13"><line x1="0" y1="0" x2="133" y2="0" style="stroke:white;stroke-width:5" /></svg>
    @else
    <svg id="svg-horizontal12"><line x1="0" y1="0" x2="0" y2="0" style="stroke:#bbbbbb60;stroke-width:5" /></svg>
    <svg id="svg-vertical12" style="margin-left:-895px;"><line x1="0" y1="0" x2="25" y2="0" style="stroke:#bbbbbb60;stroke-width:5" /></svg>
    <svg id="svg-horizontal13"><line x1="0" y1="0" x2="0" y2="0" style="stroke:#bbbbbb60;stroke-width:5" /></svg>
    @endif

   <p style="margin-left: 280px; margin-top: 190px;">Sim</p>

   <p style="margin-left: -123px; margin-top: 190px;">Não</p>   
   
   <p style="margin-left: 510px; margin-top: 300px;">Sim</p>

   <p style="margin-left: -130px; margin-top: 300px;">Não</p> 

   <p style="margin-left: -540px; margin-top: 70px;">Solicitação Validada ?</p>

   <p style="margin-left: 377px; margin-top: 380px;">Solicitação Aprovada ?</p>


    <!-- Legenda -->
    <p style="margin-left: -1030px; margin-top: 620px;font-size:14px">LEGENDA: <br><br>
   Processo já efetuada da solicitação de pagamento.      <br><br>
   Processo atual da solicitação de pagamento.<br><br>
   Processo ainda não efetuado da solicitação de pagamento.      <br><br>
   </p>
   <!--Fim Legenda -->

   <p style="margin-left: -365px; margin-top: -100px;">Início</p>

   <svg id="svg-horizontalLegenda3"><line x1="0" y1="0" x2="25" y2="0" style="stroke:#bbbbbb60;stroke-width:5" /></svg>

   <svg id="svg-horizontalLegenda1"><line x1="0" y1="0" x2="25" y2="0" style="stroke:green;stroke-width:5" /></svg>

   <svg id="svg-horizontalLegenda2"><line x1="0" y1="0" x2="25" y2="0" style="stroke:#F8C602;stroke-width:5" /></svg>



   <!--<p style="margin-left: -750px; margin-top: 20px;">Amarelo:</p>
   <p style="margin-left: -750px; margin-top: 640px;">Cinza:</p>
   <p style="margin-left: -750px; margin-top: 660px;">Verde:</p> -->

</div>
</body>
</html>
