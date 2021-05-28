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
                     <h4 class="card-title">
                     <span class="valign-wrapper" style="font-size: 11px;"><i class="grey-text material-icons">book_online</i>&nbsp;&nbsp;Selecione uma estação de trabalho disponível abaixo.</span>
                      
                        <div class="col s12" id="divtab">
                           <ul class="tabs tabs-fixed-width tab-demo z-depth-1">
                             <li  class="tab col m3"><a class="active" style="color: black;font-size: 11px;" href="#andar8">8º Andar</a></li>
                           </ul>
                         </div>

                        <a class="btn-floating btn-mini modal-trigger tooltipped" href="#modalSocios" data-position="left" data-tooltip="Clique aqui para visualizar os sócios que agenderam uma estação de trabalho para esta data e horário."
                           style="margin-left: 730px;margin-top: -23%;background-color: gray;">
                        <i class="material-icons" style="margin-top: 4px;">person</i>
                        </a>
                        <a class="btn-floating btn-mini modal-trigger tooltipped" href="#modalInfo" data-position="left" data-tooltip="Clique aqui para visualizar as informações de agendamento."
                           style="margin-left: 790px; margin-top: -30.8%; background-color: gray;">
                        <i class="material-icons" style="margin-top: 4px;">help</i>
                        </a>
                     </h4>
                     <li class="divider" style="margin-top: -60px;"></li>
                   
                     
                     <!-- INÍCIO PARTE CIMA -->
                     <div id="andar8" class="col s12">

                        <div class="row" style="margin-top: 40px;">
                         
                        <!--Mesa A --> 
                          <!--Cadeiras da Esquerda --> 
                        <div class="col l1" style="margin-top: 5px;">
                           <p style="font-size: 16px; color: black; margin-left: 148%;"><b>A</b></p>
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
                          <!--Fim Cadeiras da Esquerda --> 


                          <!--Cadeiras da Direita --> 
                        <div class="col l1" style="margin-left: 20px; margin-top: 23px;">
                           <img rel="shortcut icon" id="mesa" src="{{ asset('/public/assets/imgs/mesacima.png')}}"/>
                           <div class="container" style="margin-top: -140px; margin-left: -5px;">
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
                          <!--Fim Cadeiras da Direita --> 
                        <!--Fim Fileira A -->


                        <!-- Fileira B -->
                        <!--Cadeiras da Esquerda --> 
                        <div class="col l1" style="margin-top: 5px;">
                            <p style="font-size: 16px; color: black; margin-left: 148%;"><b>B</b></p>
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
                        
                        <div class="col l1" style="margin-left: 20px; margin-top: 23px;">
                           <img rel="shortcut icon" id="mesa" src="{{ asset('/public/assets/imgs/mesacima.png')}}"/>
                           <div class="container" style="margin-top: -140px; margin-left: -5px;">
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
                        <!--Fim Cadeiras da Direita --> 
                        <!--Fim Fileira B -->


                        <!-- Fileira C -->
                        <!-- Cadeiras Da Esquerda --> 
                        <div class="col l1" style="margin-top: 5px;">
                            <p style="font-size: 16px; color: black; margin-left: 148%;"><b>C</b></p>
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
                        
                        <!--Cadeiras Da Direita --> 
                        <div class="col l1" style="margin-left: 20px; margin-top: 23px;">
                           <img rel="shortcut icon" id="mesa" src="{{ asset('/public/assets/imgs/mesacima.png')}}"/>
                           <div class="container" style="margin-top: -140px; margin-left: -5px;">
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
                        <!--Fim Fileira C --> 


                        <!--Fileira D --> 
                          <!--Cadeiras da Esquerda --> 
                        <div class="col l1" style="margin-top: 5px;">
                            <p style="font-size: 16px; color: black; margin-left: 148%;"><b>D</b></p>
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
                        <div class="col l1" style="margin-left: 20px; margin-top: 23px;">
                           <img rel="shortcut icon" id="mesa" src="{{ asset('/public/assets/imgs/mesacima.png')}}"/>
                           <div class="container" style="margin-top: -140px; margin-left: -5px;">
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
                        <!-- Fim Fileira D -->

                        <!--Fileira E Superior --> 
                          <!--Cadeiras da Esquerda --> 
                        <div class="col l1" style="margin-top: 5px;">
                            <p style="font-size: 16px; color: black; margin-left: 148%;"><b>E</b></p>
                            @foreach($datas as $data)
                              @if($data->corredor == "E1E")
                              @if($data->agendamento == null && $data->identificacao == $cor)
                               <a onClick="pegadados({{$data->id}});" class="material-icons iconSeatEsq tooltipped" data-position="left" data-tooltip="{{$data->descricao}} disponível. " 
                                style="color:#28a745; background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;" id="{{$data->id}}" data-id="{{$data->descricao}}" 
                                data-andar="{{$data->andar}}" data-sala="{{$data->sala}}" data-corredor="E" name="mesa{{$data->id}}" value="{{$data->id}}">
                                event_seat
                              </a>
                              @elseif($data->agendamento == null && $data->identificacao != $cor)
                              <span class="material-icons iconSeatEsq tooltipped" data-position="left" data-tooltip="Estação de trabalho indisponível para agendamento na data de hoje. " style="color:#D4D4D4;background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;">
                                 event_seat
                              </span>
                              @elseif(date('Y-m-d', strtotime($data->dia)) != $datainicio && $data->identificacao == $cor)
                              <a onClick="pegadados({{$data->id}});" class="material-icons iconSeatEsq tooltipped" data-position="left" data-tooltip="{{$data->descricao}} disponível. " 
                                style="color:#28a745; background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;" id="{{$data->id}}" data-id="{{$data->descricao}}" 
                                data-andar="{{$data->andar}}" data-sala="{{$data->sala}}" data-corredor="E" name="mesa{{$data->id}}" value="{{$data->id}}">
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
                        <div class="col l1" style="margin-left: 20px; margin-top: 23px;">
                           <img rel="shortcut icon" id="mesa" src="{{ asset('/public/assets/imgs/mesacima.png')}}"/>
                           <div class="container" style="margin-top: -140px; margin-left: -5px;">
                           @foreach($datas as $data)
                              @if($data->corredor == "E1D")
                              @if($data->agendamento == null && $data->identificacao == $cor)
                               <a onClick="pegadados({{$data->id}});" class="material-icons iconSeatDir tooltipped" data-position="left" data-tooltip="{{$data->descricao}} disponível. " 
                                style="color:#28a745; background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;" id="{{$data->id}}" data-id="{{$data->descricao}}" 
                                data-andar="{{$data->andar}}" data-sala="{{$data->sala}}" data-corredor="E" name="mesa{{$data->id}}" value="{{$data->id}}">
                                event_seat
                              </a>
                              @elseif($data->agendamento == null && $data->identificacao != $cor)
                              <span class="material-icons iconSeatDir tooltipped" data-position="left" data-tooltip="Estação de trabalho indisponível para agendamento na data de hoje. " style="color:#D4D4D4;background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;">
                                 event_seat
                              </span>
                              @elseif(date('Y-m-d', strtotime($data->dia)) != $datainicio && $data->identificacao == $cor)
                              <a onClick="pegadados({{$data->id}});" class="material-icons iconSeatDir tooltipped" data-position="left" data-tooltip="{{$data->descricao}} disponível. " 
                                style="color:#28a745; background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;" id="{{$data->id}}" data-id="{{$data->descricao}}" 
                                data-andar="{{$data->andar}}" data-sala="{{$data->sala}}" data-corredor="E" name="mesa{{$data->id}}" value="{{$data->id}}">
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
                        <!--Fim Fileira E Superior --> 

                     </div>

                     <div class="container tooltipped" data-position="top" data-tooltip="Divisória de Vidro" style="margin-top: 30px;height: 30px;">
                        <svg height="150" width="680" style="margin-top: 5px; margin-left: 170px;">
                           <line x1="0" y1="0" x2="400" style="stroke:gray;stroke-width:2" />
                         </svg>
                     </div>

                     <div class="linha-vertical tooltipped"  data-position="left" data-tooltip="Sala sócia patrimonial."></div>
                     <div class="linha-vertical2 tooltipped"  data-position="left" data-tooltip="Sala de Reunião 06."></div>

                     <div class="container tooltipped" id="divJanela" data-position="top" data-tooltip="Janela" 
                     style="margin-top: 220px;height: 30px; border-top: 1px dashed green;">
                     </div>

                     <div class="linha-vertical3 tooltipped" data-position="left" data-tooltip="Janela"></div>

                     <style>
                        .linha-vertical {
                           height: 90px;
                           border-left: 1px solid;
                           margin-left: -20px;
                           margin-top: -250px;
                        }
                        .linha-vertical2{
                           height: 90px;
                           border-left: 1px solid;
                           margin-left: -20px;
                           margin-top: 20px;
                        }
                        .linha-vertical3{
                           height: 400px;
                           border-left: 1px solid;
                           margin-left: 785px;
                           margin-top: -430px;
                           border-left: 1px dashed green;
                        }
                     </style>

                     <div class="row" style="margin-top: -200px;">

                     <!--Fileira B Inferior --> 
                     <!--Cadeiras da Esquerda-->
                     <div class="col l1" style="margin-left: 154px; margin-top: 32px;">
                  
                        </div>
                     <!--Fim Fileira B Inferior --> 
                     <!--Fim Cadeiras da Esquerda-->
                     
                     <div class="col l1" style="margin-left: 20px; margin-top: 23px;">
                        <div class="container" style="margin-top: -116px; margin-left: -8px;">
         
                              </div>
                        </div>
                     <!--Fim Cadeiras Direita --> 
                     <!--Fim Fileira B Superior --> 

                     <div class="col l1" style="margin-top: 32px;">
                     @foreach($datas as $data)
                              @if($data->corredor == "C2E")
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

                     
                     <div class="col l1" style="margin-left: 20px; margin-top: 23px;">
                        <img rel="shortcut icon" id="mesabaixo" src="{{ asset('/public/assets/imgs/mesabaixo2.png')}}"/>
                         <div class="container" style="margin-top: -115px; margin-left: -8px;">
                        @foreach($datas as $data)
                              @if($data->corredor == "C2D")
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

                     <!--Fileira Inferior D --> 
                     <div class="col l1" style="margin-top: 32px;">
                     @foreach($datas as $data)
                              @if($data->corredor == "D2E")
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

                     
                     <div class="col l1" style="margin-left: 20px; margin-top: 23px;">
                        <img rel="shortcut icon" id="mesabaixo" src="{{ asset('/public/assets/imgs/mesabaixo2.png')}}"/>
                         <div class="container" style="margin-top: -115px; margin-left: -8px;">
                        @foreach($datas as $data)
                              @if($data->corredor == "D2D")
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
                     <!--Fim Fileira Inferior D --> 

                     <!--Fileira Inferior E --> 
                     <div class="col l1" style="margin-top: 32px;">
                     @foreach($datas as $data)
                              @if($data->corredor == "E2E")
                              @if($data->agendamento == null && $data->identificacao == $cor)
                               <a onClick="pegadados({{$data->id}});" class="material-icons iconSeatEsq tooltipped" data-position="left" data-tooltip="{{$data->descricao}} disponível. " 
                                style="color:#28a745; background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;" id="{{$data->id}}" data-id="{{$data->descricao}}" 
                                data-andar="{{$data->andar}}" data-sala="{{$data->sala}}" data-corredor="E" name="mesa{{$data->id}}" value="{{$data->id}}">
                                event_seat
                              </a>
                              @elseif($data->agendamento == null && $data->identificacao != $cor)
                              <span class="material-icons iconSeatEsq tooltipped" data-position="left" data-tooltip="Estação de trabalho indisponível para agendamento na data de hoje. " style="color:#D4D4D4;background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;">
                                 event_seat
                              </span>
                              @elseif(date('Y-m-d', strtotime($data->dia)) != $datainicio && $data->identificacao == $cor)
                              <a onClick="pegadados({{$data->id}});" class="material-icons iconSeatEsq tooltipped" data-position="left" data-tooltip="{{$data->descricao}} disponível. " 
                                style="color:#28a745; background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;" id="{{$data->id}}" data-id="{{$data->descricao}}" 
                                data-andar="{{$data->andar}}" data-sala="{{$data->sala}}" data-corredor="E" name="mesa{{$data->id}}" value="{{$data->id}}">
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

                     
                     <div class="col l1" style="margin-left: 20px; margin-top: 23px;">
                        <img rel="shortcut icon" id="mesabaixo" src="{{ asset('/public/assets/imgs/mesabaixo2.png')}}"/>
                         <div class="container" style="margin-top: -115px; margin-left: -8px;">
                        @foreach($datas as $data)
                              @if($data->corredor == "E2D")
                              @if($data->agendamento == null && $data->identificacao == $cor)
                               <a onClick="pegadados({{$data->id}});" class="material-icons iconSeatDir tooltipped" data-position="left" data-tooltip="{{$data->descricao}} disponível. " 
                                style="color:#28a745; background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;" id="{{$data->id}}" data-id="{{$data->descricao}}" 
                                data-andar="{{$data->andar}}" data-sala="{{$data->sala}}" data-corredor="E" name="mesa{{$data->id}}" value="{{$data->id}}">
                                event_seat
                              </a>
                              @elseif($data->agendamento == null && $data->identificacao != $cor)
                              <span class="material-icons iconSeatDir tooltipped" data-position="left" data-tooltip="Estação de trabalho indisponível para agendamento na data de hoje. " style="color:#D4D4D4;background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;">
                                 event_seat
                              </span>
                              @elseif(date('Y-m-d', strtotime($data->dia)) != $datainicio && $data->identificacao == $cor)
                              <a onClick="pegadados({{$data->id}});" class="material-icons iconSeatDir tooltipped" data-position="left" data-tooltip="{{$data->descricao}} disponível. " 
                                style="color:#28a745; background-color: #EFEDED; border-color: #EFEDED; border-radius: 10px;" id="{{$data->id}}" data-id="{{$data->descricao}}" 
                                data-andar="{{$data->andar}}" data-sala="{{$data->sala}}" data-corredor="E" name="mesa{{$data->id}}" value="{{$data->id}}">
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
                     <!--Fim Fileira Inferior E --> 

                  </div>
                     
                     <span class="material-icons" style="color: #28a745; font-size: 30px; margin-top: 20px; 
                     margin-left: -30px;">event_seat</span>
                     <p style="margin-top: -34px; margin-left: 2px;">Disponível</p>

                     <div class="container" style="margin-top: -13px;">
                        <span class="material-icons" style="color: #D4D4D4; font-size: 30px; margin-top: -500px; 
                        margin-left: 90px;">event_seat</span>
                        <p style="margin-top: -35px; margin-left: 120px;">Indisponível</p>
                     </div>

                     
                     <div class="container" style="margin-top: -13px; margin-left: 135px;">
                        <span class="material-icons" style="color: #dc3545; font-size: 30px; margin-top: -500px; 
                        margin-left: 90px;">event_seat</span>
                        <p style="margin-top: -35px; margin-left: 120px;">Ocupado</p>
                     </div>

                     
                     <div class="container" style="margin-top: -13px; margin-left: 270px">
                        <span class="material-icons" style="color: #ffc107; font-size: 30px; margin-top: -500px; 
                        margin-left: 90px;">event_seat</span>
                        <p style="margin-top: -35px; margin-left: 120px;">Selecionado</p>
                     </div>
                    
                  </div>
                 
                        <style>
                           #mesa{
                              width: 62px; 
                              height: 140px;
                              margin-left: -60px;
                              /* height: 100px; */
                              /* transform: rotate(90deg); */
                              /* margin-top: 17px;
                              margin-left: -105px; */
                           }
                           
                           #mesabaixo{
                              width: 58px; 
                              margin-left: -60px;
                           }

                           #mesa9{
                              width: 300px; 
                              height: 100px;
                              transform: rotate(180deg);
                              margin-top: 55px;
                              margin-left: -180px;
                           }
                        </style>
                        <!-- FIM PARTE CIMA -->

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
                  <li style="font-size: 11px;"><b style="color: black;">1 - </b> Identifique o corredor através das letras presentes no cabeçalho das estações.</li>
                  <br>
                  <li style="font-size: 11px;"><b style="color: black;">2 - </b> Selecione a estação de trabalho de acordo com a disponibilidade exibida na legenda.</li>
                  <br>
                  <li style="font-size: 11px;"><b style="color: black;">3 - </b> Clique no botão "Reservar" localizado na parte inferior esquerda, para confirmar a sua reserva.</li>
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
      
         $('.tabs').tabs();
         $('.modal').modal();
      
      e.preventDefault();
      
      });
      
   </script>
<!-- 
   <script>
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