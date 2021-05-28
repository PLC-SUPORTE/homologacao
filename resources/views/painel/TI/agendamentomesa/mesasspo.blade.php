<!DOCTYPE html>
<html lang="pt-br">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
      <title>Agendar estação de trabalho {{$unidade_descricao}} | PL&C Advogados</title>
      <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900&display=swap" rel="stylesheet"/>
      <link rel="stylesheet" href="{{ asset('/public/materialize/css/vendors.min.css') }}">
      <link rel="stylesheet" href="{{ asset('/public/materialize/css/materialize.min.css') }}">
      <link rel="stylesheet" href="{{ asset('/public/materialize/css/style.min.css') }}">
      <link rel="stylesheet" href="{{ asset('/public/materialize/css/custom.css') }}">
      <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
      <link rel="shortcut icon" href="{{ asset('/public/assets/imgs/casinha.png')}}" />
      <link rel="stylesheet" type="text/css" href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/css/pages/app-sidebar.min.css">
      <style>
         * {
         box-sizing: border-box;
         }
         .wrapper {
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
         .iconSeatDir{
         font-size: 28px;
         transform: rotate(90deg);
         }
         .iconSeatEsq{
         font-size: 28px;
         transform: rotate(-90deg);
         }
         body {
         overflow-x: hidden;
         overflow-y: hidden !important;
         }
      </style>
   </head>
   <body>
      <header class="page-topbar" id="header">
         <div class="navbar navbar-fixed">
            <nav class="navbar-main navbar-color nav-collapsible navbar-dark no-shadow" style="background-color: gray">
               <div class="breadcrumbs-inline pt-3 pb-1" id="breadcrumbs-wrapper" style="margin-top: -40px;">
               <h5 class="breadcrumbs-title mt-0 mb-0 display-inline hide-on-small-and-down"><span style="color: white;">Agendamento</span></h5>
                  <ol class="breadcrumbs mb-0">
                     <li class="breadcrumb-item"><a href="{{ route('Home.Principal.Show') }}">Home</a>
                     </li>
                     <li class="breadcrumb-item"><a href="{{ route('Painel.TI.agendamentomesa.index') }}">Agendamento</a>
                     </li>
                     <li class="breadcrumb-item active" style="color: black;">Estações de trabalho da unidade: {{$unidade_descricao}}
                     </li>
                  </ol>
               </div>
            </nav>
         </div>
      </header>

      <div id="main">
      <div class="row">
      <!-- <center>
        <div id="loading">
            <div class="wrapper">
               <div class="circle circle-1"></div>
               <div class="circle circle-1a"></div>
               <div class="circle circle-2"></div>
               <div class="circle circle-3"></div>
            </div>
            <h1 style="text-align: center;">Foi encontrado  estações de trabalho disponíveis para o filtro selecionado...&hellip;</h1>
         </div> 
      </center> -->

      <center>
         <div id="loadingenvia" style="display: none">
            <div class="wrapper">
               <div class="circle circle-1"></div>
               <div class="circle circle-1a"></div>
               <div class="circle circle-2"></div>
               <div class="circle circle-3"></div>
            </div>
            <h1 style="text-align: center;">Aguarde, enquanto estamos reservando a estação de trabalho selecionada...&hellip;</h1>
         </div>
      </center>


      <div class="col s12" id="corpodiv">
      <div class="container">
      <div class="section">
      <form role="form" id="form" action="{{ route('Painel.TI.agendamentomesa.agendamesa') }}" method="POST" role="search">
         {{ csrf_field() }}
         <input type="hidden" name="mesa_id" id="mesa_id">
         <input type="hidden" name="mesa_descricao" id="mesa_descricao">
         <input type="hidden" name="sala" id="sala">
         <input type="hidden" name="andar" id="andar">
         <input type="hidden" name="unidade" id="unidade" value="{{$unidade}}">
         <input type="hidden" name="unidade_descricao" id="unidade_descricao" value="{{$unidade_descricao}}">
         <input type="hidden" name="horarioid_inicio" id="horarioid_inicio" value="{{$horarioid_inicio}}">
         <input type="hidden" name="horarioid_fim" id="horarioid_fim" value="{{$horarioid_fim}}">
         <input type="hidden" name="startTime" id="startTime" value="{{$startTime}}">
         <input type="hidden" name="endTime" id="endTime" value="{{$endTime}}">
         <input type="hidden" name="datainicio" id="datainicio" value="{{$datainicio}}">
         <div id="sales-chart">
         <div class="row">

            <div class="col s12 m5 l4" style="margin-left: -50px;">
               <div id="weekly-earning" class="card animate fadeUp">
                  <div class="card-content">
                     <h4 class="card-title">
                        <span class="valign-wrapper" style="font-size: 11px;"><i class="grey-text material-icons">info</i>&nbsp;&nbsp;Informações</span>
                     </h4>
                     <li class="divider"></li>
                     <ul class="collection">
                        <li class="collection-item" style="font-size: 11px;"><b>Data: </b> <label style="color: black;font-size: 11px;">{{ date('d/m/Y', strtotime($startTime)) }} </label>  <b> Horário: </b> <label style="color: black;font-size:11px;"> {{ date('H:i', strtotime($startTime)) }} - {{ date('H:i', strtotime($endTime)) }}</label></li>
                        <li class="collection-item" style="font-size: 11px;"><b>Unidade: </b> <label style="color: black">{{$unidade}} - {{$unidade_descricao}}</label></li>
                        <li class="collection-item" style="font-size: 11px;"><b><input type="text" id="textocorredor"  style="font-size: 10px;" name="textocorredor" value="Corredor: " readonly></b></li>
                        <li class="collection-item" style="font-size: 11px;"><b><input type="text" style="font-size: 10px;" id="textoestacao" name="textoestacao" value="Estação de trabalho: " readonly></b></li>
                     </ul>
                     <center>
                        <button id="btnsubmit" type="button" disabled onClick="envia();" class="waves-effect waves-light btn green"><i class="material-icons right">check</i>Reservar</button>
                     </center>
                  </div>
               </div>
            </div>


            <div class="col s12 m8 l8">
               <div class="card animate fadeUp" style="height: 565px; width: 105%;">
                  <div class="card-content">
                     <h6 class="card-title">
                        <span class="valign-wrapper" style="font-size: 11px;"><i class="grey-text material-icons">book_online</i>&nbsp;&nbsp;Selecione uma estação de trabalho disponível abaixo.</span>
                      

                        <a class="btn-floating btn-mini modal-trigger tooltipped" href="#modalSocios" 
                        data-position="left" data-tooltip="Clique aqui para visualizar os sócios que agenderam uma estação de trabalho para esta data e horário."
                           style="margin-left: 730px;margin-top: -10%;background-color: gray;">
                        <i class="material-icons" style="margin-top: 4px;">person</i>
                        </a>
                        <a class="btn-floating btn-mini modal-trigger tooltipped" href="#modalInfo" data-position="left" data-tooltip="Clique aqui para visualizar as informações de agendamento."
                           style="margin-left: 790px; margin-top: -17.8%; background-color: gray;">
                        <i class="material-icons" style="margin-top: 4px;">help</i>
                        </a>
                     </h6>
                     <li class="divider" style="margin-top: -60px;"></li>
                   
                     
                     <!-- INÍCIO PARTE CIMA -->
                        <div id="andar8" class="col s12">

                        <div class="row" style="margin-top: 40px;">

                        <!-- Fileira A -->
                        <!-- Cadeiras Da Esquerda --> 
                        <div class="col l1">
                            <p style="font-size: 16px; margin-top: -25px; margin-left: 55px; color: black;"><b>A</b></p>
                              @foreach($datas as $data)
                              @if($data->corredor == "A1E")
                              @if($data->agendamento == null && $data->identificacao == $cor)
                               <a onClick="pegadados({{$data->id}});" class="material-icons iconSeatEsq tooltipped" data-position="left" data-tooltip="{{$data->descricao}} disponível. " 
                                style="color:#28a745; background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;" id="{{$data->id}}" data-id="{{$data->descricao}}" 
                                data-andar="{{$data->andar}}" data-sala="{{$data->sala}}" data-corredor="A" name="mesa{{$data->id}}" value="{{$data->id}}">
                                event_seat
                              </a>
                              @elseif($data->agendamento == null && $data->identificacao != $cor)
                              <span class="material-icons iconSeatEsq tooltipped" data-position="left" data-tooltip="Estação de trabalho indisponível para agendamento na data de hoje. " style="color:#D4D4D4;background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;">
                                 event_seat
                              </span>
                              @elseif(date('Y-m-d', strtotime($data->dia)) != $datainicio && $data->identificacao == $cor)
                              <a onClick="pegadados({{$data->id}});" class="material-icons iconSeatEsq tooltipped" data-position="left" data-tooltip="{{$data->descricao}} disponível. " 
                                style="color:#28a745; background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;" id="{{$data->id}}" data-id="{{$data->descricao}}" 
                                data-andar="{{$data->andar}}" data-sala="{{$data->sala}}" data-corredor="A" name="mesa{{$data->id}}" value="{{$data->id}}">
                                event_seat
                              </a>
                              @elseif(date('Y-m-d', strtotime($data->dia)) == $datainicio)
                              <span class="material-icons iconSeatEsq tooltipped" data-position="left" data-tooltip="Estação de trabalho agendada para o(a) sócio(a): <?php echo mb_convert_case($data->SocioNome, MB_CASE_TITLE, "UTF-8")?>." style="color:#dc3545; background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;">
                              event_seat
                              </span>
                              @else 
                              <span class="material-icons iconSeatEsq tooltipped" data-position="left" data-tooltip="Estação de trabalho indisponível para agendamento na data de hoje. " style="color:#D4D4D4;background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;">
                              event_seat
                              </span>
                              @endif
                              @endif
                              @endforeach
                        </div> 

                        
                        <!--Cadeiras Da Direita --> 
                        <div class="col l1">
                        <img rel="shortcut icon" id="mesa8spo" src="{{ asset('/public/assets/imgs/mesacima.png')}}"/>
                        <div class="container" style="margin-left: 20px; margin-top: -143px;">
                           @foreach($datas as $data)
                              @if($data->corredor == "A1D")
                              @if($data->agendamento == null && $data->identificacao == $cor)
                               <a onClick="pegadados({{$data->id}});" class="material-icons iconSeatDir tooltipped" data-position="left" data-tooltip="{{$data->descricao}} disponível. " 
                                style="color:#28a745; background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;" id="{{$data->id}}" data-id="{{$data->descricao}}" 
                                data-andar="{{$data->andar}}" data-sala="{{$data->sala}}" data-corredor="A" name="mesa{{$data->id}}" value="{{$data->id}}">
                                event_seat
                              </a>
                              @elseif($data->agendamento == null && $data->identificacao != $cor)
                              <span class="material-icons iconSeatDir tooltipped" data-position="left" data-tooltip="Estação de trabalho indisponível para agendamento na data de hoje. " style="color:#D4D4D4;background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;">
                                 event_seat
                              </span>
                              @elseif(date('Y-m-d', strtotime($data->dia)) != $datainicio && $data->identificacao == $cor)
                              <a onClick="pegadados({{$data->id}});" class="material-icons iconSeatDir tooltipped" data-position="left" data-tooltip="{{$data->descricao}} disponível. " 
                                style="color:#28a745; background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;" id="{{$data->id}}" data-id="{{$data->descricao}}" 
                                data-andar="{{$data->andar}}" data-sala="{{$data->sala}}" data-corredor="A" name="mesa{{$data->id}}" value="{{$data->id}}">
                                event_seat
                              </a>
                              @elseif(date('Y-m-d', strtotime($data->dia)) == $datainicio)
                              <span class="material-icons iconSeatDir tooltipped" data-position="left" data-tooltip="Estação de trabalho agendada para o(a) sócio(a): <?php echo mb_convert_case($data->SocioNome, MB_CASE_TITLE, "UTF-8")?>." style="color:#dc3545; background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;">
                              event_seat
                              </span>
                              @else 
                              <span class="material-icons iconSeatDir tooltipped" data-position="left" data-tooltip="Estação de trabalho indisponível para agendamento na data de hoje. " style="color:#D4D4D4;background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;">
                              event_seat
                              </span>
                              @endif
                              @endif
                              @endforeach
                           </div>
                        </div> 
                        <!--Fim Fileira A --> 

                     
                        <!--Fileira B Superior --> 
                          <!--Cadeiras da Esquerda --> 
                           <div class="col l1">
                              <p style="font-size: 16px; margin-top: -25px; margin-left: 55px; color: black;"><b>B</b></p>
                            @foreach($datas as $data)
                              @if($data->corredor == "B1E")
                              @if($data->agendamento == null && $data->identificacao == $cor)
                               <a onClick="pegadados({{$data->id}});" class="material-icons iconSeatEsq tooltipped" data-position="left" data-tooltip="{{$data->descricao}} disponível. " 
                                style="color:#28a745; background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;" id="{{$data->id}}" data-id="{{$data->descricao}}" 
                                data-andar="{{$data->andar}}" data-sala="{{$data->sala}}" data-corredor="B" name="mesa{{$data->id}}" value="{{$data->id}}">
                                event_seat
                              </a>
                              @elseif($data->agendamento == null && $data->identificacao != $cor)
                              <span class="material-icons iconSeatEsq tooltipped" data-position="left" data-tooltip="Estação de trabalho indisponível para agendamento na data de hoje. " style="color:#D4D4D4;background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;">
                                 event_seat
                              </span>
                              @elseif(date('Y-m-d', strtotime($data->dia)) != $datainicio && $data->identificacao == $cor)
                              <a onClick="pegadados({{$data->id}});" class="material-icons iconSeatEsq tooltipped" data-position="left" data-tooltip="{{$data->descricao}} disponível. " 
                                style="color:#28a745; background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;" id="{{$data->id}}" data-id="{{$data->descricao}}" 
                                data-andar="{{$data->andar}}" data-sala="{{$data->sala}}" data-corredor="B" name="mesa{{$data->id}}" value="{{$data->id}}">
                                event_seat
                              </a>
                              @elseif(date('Y-m-d', strtotime($data->dia)) == $datainicio)
                              <span class="material-icons iconSeatEsq tooltipped" data-position="left" data-tooltip="Estação de trabalho agendada para o(a) sócio(a): <?php echo mb_convert_case($data->SocioNome, MB_CASE_TITLE, "UTF-8")?>." style="color:#dc3545; background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;">
                              event_seat
                              </span>
                              @else 
                              <span class="material-icons iconSeatEsq tooltipped" data-position="left" data-tooltip="Estação de trabalho indisponível para agendamento na data de hoje. " style="color:#D4D4D4;background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;">
                              event_seat
                              </span>
                              @endif
                              @endif
                              @endforeach
                        </div> 
                          <!--Fim Cadeiras da Esquerda --> 

                        
                        <!--Cadeiras da Direita --> 

                        <div class="col l1">
                        <img rel="shortcut icon" id="mesa8spo" src="{{ asset('/public/assets/imgs/mesacima.png')}}"/>
                        <div class="container" style="margin-left: 20px; margin-top: -143px;">
                           @foreach($datas as $data)
                              @if($data->corredor == "B1D")
                              @if($data->agendamento == null && $data->identificacao == $cor)
                               <a onClick="pegadados({{$data->id}});" class="material-icons iconSeatDir tooltipped" data-position="left" data-tooltip="{{$data->descricao}} disponível. " 
                                style="color:#28a745; background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;" id="{{$data->id}}" data-id="{{$data->descricao}}" 
                                data-andar="{{$data->andar}}" data-sala="{{$data->sala}}" data-corredor="B" name="mesa{{$data->id}}" value="{{$data->id}}">
                                event_seat
                              </a>
                              @elseif($data->agendamento == null && $data->identificacao != $cor)
                              <span class="material-icons iconSeatDir tooltipped" data-position="left" data-tooltip="Estação de trabalho indisponível para agendamento na data de hoje. " style="color:#D4D4D4;background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;">
                                 event_seat
                              </span>
                              @elseif(date('Y-m-d', strtotime($data->dia)) != $datainicio && $data->identificacao == $cor)
                              <a onClick="pegadados({{$data->id}});" class="material-icons iconSeatDir tooltipped" data-position="left" data-tooltip="{{$data->descricao}} disponível. " 
                                style="color:#28a745; background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;" id="{{$data->id}}" data-id="{{$data->descricao}}" 
                                data-andar="{{$data->andar}}" data-sala="{{$data->sala}}" data-corredor="B" name="mesa{{$data->id}}" value="{{$data->id}}">
                                event_seat
                              </a>
                              @elseif(date('Y-m-d', strtotime($data->dia)) == $datainicio)
                              <span class="material-icons iconSeatDir tooltipped" data-position="left" data-tooltip="Estação de trabalho agendada para o(a) sócio(a): <?php echo mb_convert_case($data->SocioNome, MB_CASE_TITLE, "UTF-8")?>." style="color:#dc3545; background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;">
                              event_seat
                              </span>
                              @else 
                              <span class="material-icons iconSeatDir tooltipped" data-position="left" data-tooltip="Estação de trabalho indisponível para agendamento na data de hoje. " style="color:#D4D4D4;background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;">
                              event_seat
                              </span>
                              @endif
                              @endif
                              @endforeach
                           </div>
                        </div> 
                        <!-- Fim Fileira D -->


                        <!--Fileira C --> 
                          <!--Cadeiras da Esquerda --> 
                          <div class="col l1">
                           <p style="font-size: 16px; margin-top: -25px; margin-left: 50px; color: black;"><b>C</b></p>
                             @foreach($datas as $data)
                              @if($data->corredor == "C1E")
                              @if($data->agendamento == null && $data->identificacao == $cor)
                               <a onClick="pegadados({{$data->id}});" class="material-icons iconSeatEsq tooltipped" data-position="left" data-tooltip="{{$data->descricao}} disponível. " 
                                style="color:#28a745; background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;" id="{{$data->id}}" data-id="{{$data->descricao}}" 
                                data-andar="{{$data->andar}}" data-sala="{{$data->sala}}" data-corredor="C" name="mesa{{$data->id}}" value="{{$data->id}}">
                                event_seat
                              </a>
                              @elseif($data->agendamento == null && $data->identificacao != $cor)
                              <span class="material-icons iconSeatEsq tooltipped" data-position="left" data-tooltip="Estação de trabalho indisponível para agendamento na data de hoje. " style="color:#D4D4D4;background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;">
                                 event_seat
                              </span>
                              @elseif(date('Y-m-d', strtotime($data->dia)) != $datainicio && $data->identificacao == $cor)
                              <a onClick="pegadados({{$data->id}});" class="material-icons iconSeatEsq tooltipped" data-position="left" data-tooltip="{{$data->descricao}} disponível. " 
                                style="color:#28a745; background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;" id="{{$data->id}}" data-id="{{$data->descricao}}" 
                                data-andar="{{$data->andar}}" data-sala="{{$data->sala}}" data-corredor="C" name="mesa{{$data->id}}" value="{{$data->id}}">
                                event_seat
                              </a>
                              @elseif(date('Y-m-d', strtotime($data->dia)) == $datainicio)
                              <span class="material-icons iconSeatEsq tooltipped" data-position="left" data-tooltip="Estação de trabalho agendada para o(a) sócio(a): <?php echo mb_convert_case($data->SocioNome, MB_CASE_TITLE, "UTF-8")?>." style="color:#dc3545; background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;">
                              event_seat
                              </span>
                              @else 
                              <span class="material-icons iconSeatEsq tooltipped" data-position="left" data-tooltip="Estação de trabalho indisponível para agendamento na data de hoje. " style="color:#D4D4D4;background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;">
                              event_seat
                              </span>
                              @endif
                              @endif
                              @endforeach
                        </div> 
                          <!--Fim Cadeiras da Esquerda --> 

                          <!--Cadeiras da Direita --> 
                          <div class="col l1">
                          <img rel="shortcut icon" style="margin-left: -40px; height: 110px; margin-top: -3px;" src="{{ asset('/public/assets/imgs/mesabaixo2.png')}}"/>
                          <div class="container" style="margin-left: 6px; margin-top: -114px;">
                          @foreach($datas as $data)
                              @if($data->corredor == "C1D")
                              @if($data->agendamento == null && $data->identificacao == $cor)
                               <a onClick="pegadados({{$data->id}});" class="material-icons iconSeatDir tooltipped" data-position="left" data-tooltip="{{$data->descricao}} disponível. " 
                                style="color:#28a745; background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;" id="{{$data->id}}" data-id="{{$data->descricao}}" 
                                data-andar="{{$data->andar}}" data-sala="{{$data->sala}}" data-corredor="C" name="mesa{{$data->id}}" value="{{$data->id}}">
                                event_seat
                              </a>
                              @elseif($data->agendamento == null && $data->identificacao != $cor)
                              <span class="material-icons iconSeatDir tooltipped" data-position="left" data-tooltip="Estação de trabalho indisponível para agendamento na data de hoje. " style="color:#D4D4D4;background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;">
                                 event_seat
                              </span>
                              @elseif(date('Y-m-d', strtotime($data->dia)) != $datainicio && $data->identificacao == $cor)
                              <a onClick="pegadados({{$data->id}});" class="material-icons iconSeatDir tooltipped" data-position="left" data-tooltip="{{$data->descricao}} disponível. " 
                                style="color:#28a745; background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;" id="{{$data->id}}" data-id="{{$data->descricao}}" 
                                data-andar="{{$data->andar}}" data-sala="{{$data->sala}}" data-corredor="C" name="mesa{{$data->id}}" value="{{$data->id}}">
                                event_seat
                              </a>
                              @elseif(date('Y-m-d', strtotime($data->dia)) == $datainicio)
                              <span class="material-icons iconSeatDir tooltipped" data-position="left" data-tooltip="Estação de trabalho agendada para o(a) sócio(a): <?php echo mb_convert_case($data->SocioNome, MB_CASE_TITLE, "UTF-8")?>." style="color:#dc3545; background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;">
                              event_seat
                              </span>
                              @else 
                              <span class="material-icons iconSeatDir tooltipped" data-position="left" data-tooltip="Estação de trabalho indisponível para agendamento na data de hoje. " style="color:#D4D4D4;background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;">
                              event_seat
                              </span>
                              @endif
                              @endif
                              @endforeach
                           </div>
                        </div> 
                          <!--Fim Cadeiras da Direita --> 
                        <!--Fim Fileira C --> 



                         <!--Fileira D --> 
                          <!--Cadeiras da Esquerda --> 
                          <div class="col l1">
                           <p style="font-size: 16px; margin-top: -25px; margin-left: 50px; color: black;"><b>D</b></p>
                            @foreach($datas as $data)
                              @if($data->corredor == "D1E")
                              @if($data->agendamento == null && $data->identificacao == $cor)
                               <a onClick="pegadados({{$data->id}});" class="material-icons iconSeatEsq tooltipped" data-position="left" data-tooltip="{{$data->descricao}} disponível. " 
                                style="color:#28a745; background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;" id="{{$data->id}}" data-id="{{$data->descricao}}" 
                                data-andar="{{$data->andar}}" data-sala="{{$data->sala}}" data-corredor="D" name="mesa{{$data->id}}" value="{{$data->id}}">
                                event_seat
                              </a>
                              @elseif($data->agendamento == null && $data->identificacao != $cor)
                              <span class="material-icons iconSeatEsq tooltipped" data-position="left" data-tooltip="Estação de trabalho indisponível para agendamento na data de hoje. " style="color:#D4D4D4;background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;">
                                 event_seat
                              </span>
                              @elseif(date('Y-m-d', strtotime($data->dia)) != $datainicio && $data->identificacao == $cor)
                              <a onClick="pegadados({{$data->id}});" class="material-icons iconSeatEsq tooltipped" data-position="left" data-tooltip="{{$data->descricao}} disponível. " 
                                style="color:#28a745; background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;" id="{{$data->id}}" data-id="{{$data->descricao}}" 
                                data-andar="{{$data->andar}}" data-sala="{{$data->sala}}" data-corredor="D" name="mesa{{$data->id}}" value="{{$data->id}}">
                                event_seat
                              </a>
                              @elseif(date('Y-m-d', strtotime($data->dia)) == $datainicio)
                              <span class="material-icons iconSeatEsq tooltipped" data-position="left" data-tooltip="Estação de trabalho agendada para o(a) sócio(a): <?php echo mb_convert_case($data->SocioNome, MB_CASE_TITLE, "UTF-8")?>." style="color:#dc3545; background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;">
                              event_seat
                              </span>
                              @else 
                              <span class="material-icons iconSeatEsq tooltipped" data-position="left" data-tooltip="Estação de trabalho indisponível para agendamento na data de hoje. " style="color:#D4D4D4;background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;">
                              event_seat
                              </span>
                              @endif
                              @endif
                              @endforeach
                        </div> 
                          <!--Fim Cadeiras da Esquerda --> 


                         <!--Cadeiras da Direita --> 
                         <div class="col l1">
                         <img rel="shortcut icon" id="mesaspo4" src="{{ asset('/public/assets/imgs/spo4.png')}}"/>
                         <div class="container" style="margin-left: 10px; margin-top: -65px;">
                          @foreach($datas as $data)
                              @if($data->corredor == "D1D")
                              @if($data->agendamento == null && $data->identificacao == $cor)
                               <a onClick="pegadados({{$data->id}});" class="material-icons iconSeatDir tooltipped" data-position="left" data-tooltip="{{$data->descricao}} disponível. " 
                                style="color:#28a745; background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;" id="{{$data->id}}" data-id="{{$data->descricao}}" 
                                data-andar="{{$data->andar}}" data-sala="{{$data->sala}}" data-corredor="D" name="mesa{{$data->id}}" value="{{$data->id}}">
                                event_seat
                              </a>
                              @elseif($data->agendamento == null && $data->identificacao != $cor)
                              <span class="material-icons iconSeatDir tooltipped" data-position="left" data-tooltip="Estação de trabalho indisponível para agendamento na data de hoje. " style="color:#D4D4D4;background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;">
                                 event_seat
                              </span>
                              @elseif(date('Y-m-d', strtotime($data->dia)) != $datainicio && $data->identificacao == $cor)
                              <a onClick="pegadados({{$data->id}});" class="material-icons iconSeatDir tooltipped" data-position="left" data-tooltip="{{$data->descricao}} disponível. " 
                                style="color:#28a745; background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;" id="{{$data->id}}" data-id="{{$data->descricao}}" 
                                data-andar="{{$data->andar}}" data-sala="{{$data->sala}}" data-corredor="D" name="mesa{{$data->id}}" value="{{$data->id}}">
                                event_seat
                              </a>
                              @elseif(date('Y-m-d', strtotime($data->dia)) == $datainicio)
                              <span class="material-icons iconSeatDir tooltipped" data-position="left" data-tooltip="Estação de trabalho agendada para o(a) sócio(a): <?php echo mb_convert_case($data->SocioNome, MB_CASE_TITLE, "UTF-8")?>." style="color:#dc3545; background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;">
                              event_seat
                              </span>
                              @else 
                              <span class="material-icons iconSeatDir tooltipped" data-position="left" data-tooltip="Estação de trabalho indisponível para agendamento na data de hoje. " style="color:#D4D4D4;background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;">
                              event_seat
                              </span>
                              @endif
                              @endif
                              @endforeach
                           </div>
                        </div> 
                          <!--Fim Cadeiras da Direita --> 
                        <!--Fim Fileira D  --> 

                     </div>

                     <br> 

                     <li class="divider"></li>

                     <div class="linha-vertical"  data-position="left"></div>

                     <p style="font-size: 11px; margin-left: 285px; margin-top: -250px;">Entrada</p>

                     <style>
                           .linha-vertical {
                           height: 250px;
                           border-left: 1px solid;
                           margin-left: 280px;
                        }
                     </style>

                     <div id="partebaixo" class="col s12">

                        <div class="row" style="margin-top: 15px;">


                          <!--Fileira A Inferior --> 
                           <div class="col l1" style="margin-left: -6px;">
                              <!-- <p style="font-size: 16px; color: black; margin-left: 148%;"><b>E</b></p> -->
                               @foreach($datas as $data)
                                @if($data->corredor == "A2E")
                                @if($data->agendamento == null && $data->identificacao == $cor)
                               <a onClick="pegadados({{$data->id}});" class="material-icons iconSeatEsq tooltipped" data-position="left" data-tooltip="{{$data->descricao}} disponível. " 
                                style="color:#28a745; background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;" id="{{$data->id}}" data-id="{{$data->descricao}}" 
                                data-andar="{{$data->andar}}" data-sala="{{$data->sala}}" data-corredor="D" name="mesa{{$data->id}}" value="{{$data->id}}">
                                event_seat
                              </a>
                              @elseif($data->agendamento == null && $data->identificacao != $cor)
                              <span class="material-icons iconSeatEsq tooltipped" data-position="left" data-tooltip="Estação de trabalho indisponível para agendamento na data de hoje. " style="color:#D4D4D4;background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;">
                                 event_seat
                              </span>
                              @elseif(date('Y-m-d', strtotime($data->dia)) != $datainicio && $data->identificacao == $cor)
                              <a onClick="pegadados({{$data->id}});" class="material-icons iconSeatEsq tooltipped" data-position="left" data-tooltip="{{$data->descricao}} disponível. " 
                                style="color:#28a745; background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;" id="{{$data->id}}" data-id="{{$data->descricao}}" 
                                data-andar="{{$data->andar}}" data-sala="{{$data->sala}}" data-corredor="D" name="mesa{{$data->id}}" value="{{$data->id}}">
                                event_seat
                              </a>
                              @elseif(date('Y-m-d', strtotime($data->dia)) == $datainicio)
                              <span class="material-icons iconSeatEsq tooltipped" data-position="left" data-tooltip="Estação de trabalho agendada para o(a) sócio(a): <?php echo mb_convert_case($data->SocioNome, MB_CASE_TITLE, "UTF-8")?>." style="color:#dc3545; background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;">
                              event_seat
                              </span>
                              @else 
                              <span class="material-icons iconSeatEsq tooltipped" data-position="left" data-tooltip="Estação de trabalho indisponível para agendamento na data de hoje. " style="color:#D4D4D4;background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;">
                              event_seat
                              </span>
                              @endif
                              @endif
                              @endforeach
                          </div> 

                          
                          <div class="col l1">
                           <img rel="shortcut icon" style="margin-left: -40px; height: 110px; margin-top: -3px;" src="{{ asset('/public/assets/imgs/mesabaixo2.png')}}"/>
                           <div class="container" style="margin-left: 6px; margin-top: -114px;">
                           @foreach($datas as $data)
                               @if($data->corredor == "A2D")
                               @if($data->agendamento == null && $data->identificacao == $cor)
                               <a onClick="pegadados({{$data->id}});" class="material-icons iconSeatDir tooltipped" data-position="left" data-tooltip="{{$data->descricao}} disponível. " 
                                style="color:#28a745; background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;" id="{{$data->id}}" data-id="{{$data->descricao}}" 
                                data-andar="{{$data->andar}}" data-sala="{{$data->sala}}" data-corredor="A" name="mesa{{$data->id}}" value="{{$data->id}}">
                                event_seat
                              </a>
                              @elseif($data->agendamento == null && $data->identificacao != $cor)
                              <span class="material-icons iconSeatDir tooltipped" data-position="left" data-tooltip="Estação de trabalho indisponível para agendamento na data de hoje. " style="color:#D4D4D4;background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;">
                                 event_seat
                              </span>
                              @elseif(date('Y-m-d', strtotime($data->dia)) != $datainicio && $data->identificacao == $cor)
                              <a onClick="pegadados({{$data->id}});" class="material-icons iconSeatDir tooltipped" data-position="left" data-tooltip="{{$data->descricao}} disponível. " 
                                style="color:#28a745; background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;" id="{{$data->id}}" data-id="{{$data->descricao}}" 
                                data-andar="{{$data->andar}}" data-sala="{{$data->sala}}" data-corredor="A" name="mesa{{$data->id}}" value="{{$data->id}}">
                                event_seat
                              </a>
                              @elseif(date('Y-m-d', strtotime($data->dia)) == $datainicio)
                              <span class="material-icons iconSeatDir tooltipped" data-position="left" data-tooltip="Estação de trabalho agendada para o(a) sócio(a): <?php echo mb_convert_case($data->SocioNome, MB_CASE_TITLE, "UTF-8")?>." style="color:#dc3545; background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;">
                              event_seat
                              </span>
                              @else 
                              <span class="material-icons iconSeatDir tooltipped" data-position="left" data-tooltip="Estação de trabalho indisponível para agendamento na data de hoje. " style="color:#D4D4D4;background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;">
                              event_seat
                              </span>
                              @endif
                              @endif
                              @endforeach
                            </div>
                         </div>  


                        <!--Fileira B Inferior --> 
                         <div class="col l1">
                           @foreach($datas as $data)
                             @if($data->corredor == "B2E")
                             @if($data->agendamento == null && $data->identificacao == $cor)
                               <a onClick="pegadados({{$data->id}});" class="material-icons iconSeatEsq tooltipped" data-position="left" data-tooltip="{{$data->descricao}} disponível. " 
                                style="color:#28a745; background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;" id="{{$data->id}}" data-id="{{$data->descricao}}" 
                                data-andar="{{$data->andar}}" data-sala="{{$data->sala}}" data-corredor="F" name="mesa{{$data->id}}" value="{{$data->id}}">
                                event_seat
                              </a>
                              @elseif($data->agendamento == null && $data->identificacao != $cor)
                              <span class="material-icons iconSeatEsq tooltipped" data-position="left" data-tooltip="Estação de trabalho indisponível para agendamento na data de hoje. " style="color:#D4D4D4;background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;">
                                 event_seat
                              </span>
                              @elseif(date('Y-m-d', strtotime($data->dia)) != $datainicio && $data->identificacao == $cor)
                              <a onClick="pegadados({{$data->id}});" class="material-icons iconSeatEsq tooltipped" data-position="left" data-tooltip="{{$data->descricao}} disponível. " 
                                style="color:#28a745; background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;" id="{{$data->id}}" data-id="{{$data->descricao}}" 
                                data-andar="{{$data->andar}}" data-sala="{{$data->sala}}" data-corredor="F" name="mesa{{$data->id}}" value="{{$data->id}}">
                                event_seat
                              </a>
                              @elseif(date('Y-m-d', strtotime($data->dia)) == $datainicio)
                              <span class="material-icons iconSeatEsq tooltipped" data-position="left" data-tooltip="Estação de trabalho agendada para o(a) sócio(a): <?php echo mb_convert_case($data->SocioNome, MB_CASE_TITLE, "UTF-8")?>." style="color:#dc3545; background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;">
                              event_seat
                              </span>
                              @else 
                              <span class="material-icons iconSeatEsq tooltipped" data-position="left" data-tooltip="Estação de trabalho indisponível para agendamento na data de hoje. " style="color:#D4D4D4;background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;">
                              event_seat
                              </span>
                              @endif
                              @endif
                              @endforeach
                       </div> 

                       <div class="col l1">
                        <img rel="shortcut icon" id="mesaspo4" src="{{ asset('/public/assets/imgs/spo4.png')}}"/>
                        <div class="container" style="margin-left: 10px; margin-top: -65px;">
                          @foreach($datas as $data)
                             @if($data->corredor == "B2D")
                             @if($data->agendamento == null && $data->identificacao == $cor)
                               <a onClick="pegadados({{$data->id}});" class="material-icons iconSeatDir tooltipped" data-position="left" data-tooltip="{{$data->descricao}} disponível. " 
                                style="color:#28a745; background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;" id="{{$data->id}}" data-id="{{$data->descricao}}" 
                                data-andar="{{$data->andar}}" data-sala="{{$data->sala}}" data-corredor="B" name="mesa{{$data->id}}" value="{{$data->id}}">
                                event_seat
                              </a>
                              @elseif($data->agendamento == null && $data->identificacao != $cor)
                              <span class="material-icons iconSeatDir tooltipped" data-position="left" data-tooltip="Estação de trabalho indisponível para agendamento na data de hoje. " style="color:#D4D4D4;background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;">
                                 event_seat
                              </span>
                              @elseif(date('Y-m-d', strtotime($data->dia)) != $datainicio && $data->identificacao == $cor)
                              <a onClick="pegadados({{$data->id}});" class="material-icons iconSeatDir tooltipped" data-position="left" data-tooltip="{{$data->descricao}} disponível. " 
                                style="color:#28a745; background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;" id="{{$data->id}}" data-id="{{$data->descricao}}" 
                                data-andar="{{$data->andar}}" data-sala="{{$data->sala}}" data-corredor="B" name="mesa{{$data->id}}" value="{{$data->id}}">
                                event_seat
                              </a>
                              @elseif(date('Y-m-d', strtotime($data->dia)) == $datainicio)
                              <span class="material-icons iconSeatDir tooltipped" data-position="left" data-tooltip="Estação de trabalho agendada para o(a) sócio(a): <?php echo mb_convert_case($data->SocioNome, MB_CASE_TITLE, "UTF-8")?>." style="color:#dc3545; background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;">
                              event_seat
                              </span>
                              @else 
                              <span class="material-icons iconSeatDir tooltipped" data-position="left" data-tooltip="Estação de trabalho indisponível para agendamento na data de hoje. " style="color:#D4D4D4;background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;">
                              event_seat
                              </span>
                              @endif
                              @endif
                              @endforeach
                          <!--Fim Fileira B Inferior -->  
                          </div>
                       </div> 


                        </div>

                     </div>


                     <style>

                        #mesaspo4{
                           margin-left: -40px;
                           width: 60px;
                           margin-top: 2px;
                        }

                        #mesa6spo{
                           margin-left: -55px;
                           height: 110px;
                           margin-top: -4px;
                        }

                        #mesa8spo{
                          margin-left: -40px;
                        }
                     </style>

                     <div class="container" style="margin-top: 150px;margin-left: 300px;">
                        <span class="material-icons" style="color: #28a745; font-size: 30px; margin-top: 50px; margin-left: -30px;">event_seat</span>
                        <p style="margin-top: -34px; margin-left: 2px;">Disponível</p>
   
                        <div style="margin-left: 130px; margin-top: -26px; margin-left: 100px;">
                           <span class="material-icons" style="color: gray; font-size: 30px;">event_seat</span>
                           <p style="margin-top: -34px; margin-left: 35px;">Indisponível</p>
                        </div>
   
                        <div style="margin-left: 270px;  margin-top: -26px; margin-left: 250px;">
                           <span class="material-icons" style="color: #dc3545; font-size: 30px;">event_seat</span>
                           <p style="margin-top: -34px; margin-left: 35px;">Ocupado</p>
                        </div>
                        
                        <div style="margin-left: 390px;   margin-top: -26px; margin-left: 380px;">
                           <span class="material-icons" style="color: #ffc107; font-size: 30px;">event_seat</span>
                           <p style="margin-top: -34px; margin-left: 35px;">Selecionado</p>
                        </div>
                     </div>
                   
                     <!--Fim Legenda -->
                     </div>

                  </div>
            

                  </div>
                  </div>
               </div>
            </div>
         </div>
      </form>


      <!-- MODAL INFORMAÇÕES -->
      <div id="modalInfo" class="modal">
         <div class="modal-content">
            <h6>Como agendar</h6>
            <a href="#!" class="modal-close btn-flat aling right red"
               style="color: white; margin-top: -58px; margin-right: -20px;"><i class="material-icons">close</i></a>
            <p style="font-size: 11px;">Para agendar uma estação de trabalho, siga os passos abaixo:
            <div class="container">
               <ul>
                  <li style="font-size: 10px;;"><b style="color: black;">1 - </b> Identifique o corredor através das letras presentes no cabeçalho das estações.</li>
                  <br>
                  <li style="font-size: 10px;;"><b style="color: black;">2 - </b> Selecione a estação de trabalho de acordo com a disponibilidade exibida na legenda.</li>
                  <br>
                  <li style="font-size: 10px;;"><b style="color: black;">3 - </b> Clique no botão "Reservar" localizado na parte inferior esquerda, para confirmar a sua reserva.</li>
                  <br>
               </ul>
            </div>
         </div>
      </div>
      <!-- MODAL INFORMAÇÕES -->

      <!-- MODAL SÓCIOS -->
      <div id="modalSocios" class="modal">
         <div class="modal-content">
         <p style="font-size: 11px;">Agendamentos de estações de trabalho no dia: {{ date('d/m/Y', strtotime($startTime)) }} entre {{ date('H:i:s', strtotime($startTime)) }} e {{ date('H:i:s', strtotime($endTime)) }}</p>
            <a href="#!" class="modal-close btn-flat aling right red"
               style="color: white; margin-top: -58px; margin-right: -20px;"><i class="material-icons">close</i></a>
            <li class="divider"></li>
            <p style="font-size: 11px;">A listagem abaixo representa os sócios que irão comparecer a unidade: {{$unidade_descricao}} nesta data e horário.
            <div class="container">
               <ul>
                  @foreach($socios as $socio)
                  <li style="font-size: 10px;"><b style="color: black;">{{$socio->Setor}} - {{$socio->Mesa}} -  </b> <?php echo mb_convert_case($socio->SocioNome, MB_CASE_TITLE, "UTF-8")?>.</li>
                  @endforeach
                  <br>
               </ul>
            </div>
         </div>
      </div>
      <!-- MODAL SÓCIOS -->

   </body>
   
   <style>
      *, *:before, *:after {
      box-sizing: border-box;
      }
      html {
      font-size: 16px;
      }
   </style>

   <script src="{{ asset('/public/materialize/js/vendors.min.js') }}"></script>
   <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
   <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
   <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>
 

   <script>
    var el = document.querySelector('.tabs');
    var instance = M.Tabs.init(el, {});
  </script>

   <script>
      $(document).ready(function(){
      
         // $("#corpodiv").hide();
         $('.tabs').tabs();

         $('.modal').modal();
      
         e.preventDefault();
      
      });
      
   </script>

   <!-- <script>
      setTimeout(function() {
      
         $('#loading').fadeOut('fast');
      
         $("#corpodiv").show();
      
      }, 2000);
      
   </script> -->
   
   <script>
      function envia() {
      
       
      
          document.getElementById("loadingenvia").style.display = "";
      
          document.getElementById("corpodiv").style.display = "none";
      
          document.getElementById("form").submit();
      
      }    
      
   </script>
   
   <script>
      function pegadados(mesa_id) {
      
       
      
        var verifica = $('#mesa_id').val();
      
      
          if(verifica != ""){
      
            $("#textoestacao").val('');
            $("#textocorredor").val('');
            // $("#mesa_id").val('');
            $("[name='mesa"+mesa_id+"']").css("color","#28a745");
            $("#btnsubmit").attr("disabled", true);
      
            //Desmarco todas as mesas que estiverem com a cor Amarela
            var mesaselecionada = $('#mesa_id').val();

            $("[name='mesa"+mesaselecionada+"']").css("color","#28a745");
            $("#mesa_id").val('');
            // alert('Favor selecionar apenas uma estação de trabalho !');
   
      
          } else {
      
       
      
             var mesa_descricao = $("[name='mesa"+mesa_id+"']").data("id");
             var sala = $("[name='mesa"+mesa_id+"']").data("sala");
             var andar = $("[name='mesa"+mesa_id+"']").data("andar");
             var corredor = $("[name='mesa"+mesa_id+"']").data("corredor");
      
             $("[name='mesa"+mesa_id+"']").css("color","#ffc107");
      
       
            $("#mesa_id").val(mesa_id);
            $("#mesa_descricao").val(mesa_descricao);
            $("#sala").val(sala); 
            $("#andar").val(andar); 
            $("#textoestacao").val(mesa_descricao);
            $("#textocorredor").val('Corredor: ' + corredor);
            $("#btnsubmit").attr("disabled", false);
      
          }
      
      }
   </script>


</html>