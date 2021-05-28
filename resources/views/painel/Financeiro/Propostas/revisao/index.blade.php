@extends('painel.Layout.header')
<?php $search = 2 ?>
@section('title') Dashboard @endsection <!-- Titulo da pagina -->

@section('header') 
    <link rel="apple-touch-icon" href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/images/favicon/apple-touch-icon-152x152.png">
    <link rel="shortcut icon" href="{{ asset('/public/assets/imgs/casinha.png')}}" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('/public/materialize/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/responsive.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/materialize.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/data-tables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/custom.css') }}">
@endsection

@section('header_title')
Dashboard
@endsection

@section('submenu')
<li class="breadcrumb-item active" style="color: black;">Dashboard</li>
@endsection

@section('body')
    <div>

        <div class="row">
            <div class="content-wrapper-before"></div>
            <div class="col s12">

              <div class="container">

  
              <div id="card-stats" class="row">
              <div class="col s6 m4 l2 x4">
              <div class="card tooltipped"  data-position="top" data-tooltip="Total de propostas aguardando sua revisão." >
                   <div class="card-content cyan white-text">
                   <p class="card-stats-title" style="font-size: 12px">Aguardando revisão</p>
                      <h4 class="card-stats-number white-text">{{$QuantidadeAguardandoRevisao}}</h4>
                      <p class="card-stats-compare">
                      </p>
                   </div>
                </div>
             </div>
    
              <div class="col s6 m4 l2 x4">
              <div class="card tooltipped"  data-position="top" data-tooltip="Total de propostas com o status de aprovada." >
                   <div class="card-content cyan white-text">
                   <p class="card-stats-title" style="font-size: 10px">Propostas aprovadas</p>
                      <h4 class="card-stats-number white-text">{{$QuantidadePropostasAprovadas}}</h4>
                      <p class="card-stats-compare">
                      </p>
                   </div>
                </div>
             </div>
    
             <div class="col s6 m4 l2 x4">
             <div class="card tooltipped"  data-position="top" data-tooltip="Total de propostas com o status de reprovada." >
                   <div class="card-content cyan white-text">
                      <p class="card-stats-title" style="font-size: 12px">Propostas reprovadas</p>
                      <h4 class="card-stats-number white-text">{{$QuantidadePropostasReprovadas}}</h4>
                      <p class="card-stats-compare">
                      </p>
                   </div>
                </div>
             </div>
    
             <div class="col s6 m4 l2 x4">
                <div class="card tooltipped"  data-position="top" data-tooltip="Total de propostas com o status de cancelada." >
                   <div class="card-content cyan white-text">
                   <p class="card-stats-title" style="font-size: 12px">Propostas canceladas</p>
                      <h4 class="card-stats-number white-text">{{$QuantidadePropostasCanceladas}}</h4>
                      <p class="card-stats-compare">
                      </p>
                   </div>
                </div>
             </div>

             <div class="col s6 m4 l2 x4">
                <div class="card tooltipped"  data-position="top" data-tooltip="Total de propostas com o status de substituida." >
                   <div class="card-content cyan white-text">
                   <p class="card-stats-title" style="font-size: 12px">Propostas substituidas</p>
                      <h4 class="card-stats-number white-text">{{$QuantidadePropostasSubstituidas}}</h4>
                      <p class="card-stats-compare">
                      </p>
                   </div>
                </div>
             </div>
    
             <div class="col s6 m4 l2 x4">
              <div class="card tooltipped"  data-position="top" data-tooltip="Total de setores que o seu usuário está relacionado." >
                    <div class="card-content cyan white-text">
                    <p class="card-stats-title" style="font-size: 12px">Total setores relacionados</p>
                       <h4 class="card-stats-number white-text">{{$QuantidadeSetores}}</h4>
                       <p class="card-stats-compare">
                       </p>
                    </div>
                 </div>
              </div>
            </div>
          </div>
      </div>
      </div>


      

        <div class="row">
            <div class="col s12">

                <div class="card">
                <div class="card-content">
                    <h4 class="card-title">Dashboard gerencial propostas</h4>
               
                    <div id="work-collections" class="seaction">
                    <div class="row">
                    
                        <div class="col s12 m4 xl4">
                                <div id="pie-chart-sample" class="sample-chart-wrapper" style="margin-top: 2%;">
                                  <canvas id="pie-chart" style="display: inline-block; width: 130px; height: 130px; vertical-align: top;" width="130" height="130"></canvas>
                                  <p class="header center">SubStatus</p>
                                </div>
                         </div>
                        

                       <div class="col s12 m12 xl4">
                          <ul id="projects-collection" class="collection z-depth-1">
                            <li class="collection-item">
                               <div class="row">
                                  <div class="col s8">
                                     <p class="collections-title font-weight-600" style="font-size: 12px">Aguardando sua revisão ({{$QuantidadeAguardandoRevisao}})</p>
                                  </div>
                                  <div class="col s4" style="margin-top: 2.5%;"><a href="{{ route('Painel.Proposta.revisao.revisar') }}"><span class="task-cat green">Acessar</span></a></div>
                               </div>
                            </li>
                            <li class="collection-item">
                              <div class="row">
                                <div class="col s8">
                                   <p class="collections-title font-weight-600" style="font-size: 12px">Propostas aprovadas ({{$QuantidadePropostasAprovadas}})</p>
                                </div>
                                <div class="col s4" style="margin-top: 2.5%;"><a href="{{ route('Painel.Proposta.revisao.listagemgeral') }}"><span class="task-cat green">Acessar</span></a></div>
                             </div>
                           </li>
                           <li class="collection-item">
                            <div class="row">
                              <div class="col s8">
                                 <p class="collections-title font-weight-600" style="font-size: 12px">Propostas reprovadas ({{$QuantidadePropostasReprovadas}})</p>
                              </div>
                              <div class="col s4" style="margin-top: 2.5%;"><a href="{{ route('Painel.Proposta.revisao.listagemgeral') }}"><span class="task-cat green">Acessar</span></a></div>
                           </div>
                           </li>
                         </ul>
                       </div>
                       <div class="col s12 m12 xl4">
                        <ul id="projects-collection" class="collection z-depth-1">
                            <li class="collection-item">
                               <div class="row">
                                  <div class="col s8">
                                     <p class="collections-title font-weight-600" style="font-size: 12px">Propostas canceladas ({{$QuantidadePropostasCanceladas}})</p>
                                  </div>
                                  <div class="col s4" style="margin-top: 2.5%;"><a href="{{ route('Painel.Proposta.revisao.listagemgeral') }}"><span class="task-cat green">Acessar</span></a></div>
                               </div>
                            </li>
                            <li class="collection-item">
                              <div class="row">
                                <div class="col s8">
                                   <p class="collections-title font-weight-600" style="font-size: 12px">Propostas substituidas ({{$QuantidadePropostasSubstituidas}})</p>
                                </div>
                                <div class="col s4" style="margin-top: 2.5%;"><a href="{{ route('Painel.Proposta.revisao.listagemgeral') }}"><span class="task-cat green">Acessar</span></a></div>
                             </div>
                           </li>

                         </ul>
                       </div>
                    </div>
                 </div>
                </div>
                    
            </div>
                </div>
            </div>

            
            <div class="row">
    <div class="col s12">
    <div class="ct-chart card z-depth-2 border-radius-6" style="overflow: auto; max-height: 400px;">
        <div class="card-content">
          <div class="row">
            <div class="col s12">
              <table id="page-length-option" class="display" style="font-size: 11px;">
                <thead>
                <tr>
          <th style="font-size: 11px">Número Proposta</th>
          <th style="font-size: 11px">Solicitante</th>
          <th style="font-size: 11px">Data Cadastro</th>
          <th style="font-size: 11px">Grupo</th>
          <th style="font-size: 11px">Cliente</th>
          <th style="font-size: 11px">Setor</th>
          <th style="font-size: 11px">Unidade</th>
          <th style="font-size: 11px">Valor</th>
          <th style="font-size: 11px">Status</th>
          <th style="font-size: 12px">Ação</th>
         </tr>
                </thead>
                <tbody>
                @foreach($datas as $data)  
                  <tr>
          <td style="font-size: 10px">{{ $data->NumeroProposta}}</td>
          <td style="font-size: 10px">{{ $data->Solicitante}}</td>
          <td style="font-size: 10px">{{ date('d/m/Y' , strtotime($data->Data)) }}</td>
          <td style="font-size: 10px">{{ $data->Grupo}}</td>
          <td style="font-size: 10px">{{ $data->Cliente}}</td>
          <td style="font-size: 10px">{{ $data->Setor}}</td>
          <td style="font-size: 10px">{{ $data->Unidade}}</td>
          <td style="font-size: 10px">R$ <?php echo number_format($data->Valor,2,",",".") ?></td>
          <td style="font-size: 10px">{{ $data->Status}}</td>
                    <td>
        
          <a style="color: gray;"  href="{{ route('Painel.Proposta.revisao.revisarproposta', $data->Id) }}" class="invoice-action-view mr-4"><i class="material-icons">remove_red_eye</i></a>
                    </td>
                  </tr>
                @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
@endsection

@section('scripts')
 
    <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/data-tables.min.js') }}"></script>
    <script src="https://pixinvent.com/materialize-material-design-admin-template/app-assets/vendors/chartjs/chart.min.js"></script>



<script>

$(window).on("load", function() {
        var a=$("#line-chart"), e= {
            type:"line", options: {
                responsive: !0, maintainAspectRatio: !1, legend: {
                    position:"bottom"
                }

                , hover: {
                    mode:"label"
                }

                , scales: {
                    xAxes:[ {
                        display: !0, gridLines: {
                            color:"#f3f3f3", drawTicks: !1
                        }

                        , scaleLabel: {
                            display: !0, labelString:"Month"
                        }
                    }

                    ], yAxes:[ {
                        display: !0, gridLines: {
                            color:"#f3f3f3", drawTicks: !1
                        }

                        , scaleLabel: {
                            display: !0, labelString:"Value"
                        }
                    }

                    ]
                }

                , title: {
                    display: !0, text:"Line Chart - Legend"
                }
            }

            , data: {
                labels:["January", "February", "March", "April", "May", "June", "July"], datasets:[ {
                    label:"My First dataset", data:[65, 59, 80, 81, 56, 55, 40], fill: !1, borderColor:"#e91e63", pointBorderColor:"#e91e63", pointBackgroundColor:"#FFF", pointBorderWidth:2, pointHoverBorderWidth:2, pointRadius:4
                }

                , {
                    label:"My Second dataset", data:[28, 48, 40, 19, 86, 27, 90], fill: !1, borderColor:"#03a9f4", pointBorderColor:"#03a9f4", pointBackgroundColor:"#FFF", pointBorderWidth:2, pointHoverBorderWidth:2, pointRadius:4
                }

                , {
                    label:"My Third dataset - No bezier", data:[45, 25, 16, 36, 67, 18, 76], fill: !1, borderColor:"#ffc107", pointBorderColor:"#ffc107", pointBackgroundColor:"#FFF", pointBorderWidth:2, pointHoverBorderWidth:2, pointRadius:4
                }

                ]
            }
        }

        ; new Chart(a, e), a=$("#bar-chart"), e= {
            type:"horizontalBar", options: {
                elements: {
                    rectangle: {
                        borderWidth:2, borderColor:"rgb(0, 255, 0)", borderSkipped:"left"
                    }
                }

                , responsive: !0, maintainAspectRatio: !1, responsiveAnimationDuration:500, legend: {
                    position:"top"
                }

                , scales: {
                    xAxes:[ {
                        display: !0, gridLines: {
                            color:"#f3f3f3", drawTicks: !1
                        }

                        , scaleLabel: {
                            display: !0
                        }
                    }

                    ], yAxes:[ {
                        display: !0, gridLines: {
                            color:"#f3f3f3", drawTicks: !1
                        }

                        , scaleLabel: {
                            display: !0
                        }
                    }

                    ]
                }

                , title: {
                    display: !1, text:"Chart.js Horizontal Bar Chart"
                }
            }

            , data: {
                labels:["January", "February", "March", "April"], datasets:[ {
                    label:"My First dataset", data:[65, 59, 80, 81], backgroundColor:"#00bcd4", hoverBackgroundColor:"#00acc1", borderColor:"transparent"
                }

                , {
                    label:"My Second dataset", data:[28, 48, 40, 19], backgroundColor:"#ffeb3b", hoverBackgroundColor:"#fdd835", borderColor:"transparent"
                }

                ]
            }
        }

        , new Chart(a, e), a=$("#pie-chart"), e= {
            type:"pie", options: {
            }

            , data: {
                labels:["Aguardando sua revisão", "Propostas aprovadas", "Propostas reprovadas", "Propostas canceladas", "Propostas substituidas"], 
                datasets:[ {
                    label:"My First dataset", 
                    data:[{{$QuantidadeAguardandoRevisao}}, 
                         {{$QuantidadePropostasAprovadas}},
                         {{$QuantidadePropostasReprovadas}}, 
                         {{$QuantidadePropostasCanceladas}}, 
                         {{$QuantidadePropostasSubstituidas}}], 
                    backgroundColor:["#03a9f4", "#00bcd4", "#ffc107", "#e91e63", "#4caf50"]
                }
                ]
            }
        }

        , new Chart(a, e)
    }

);

</script>

@endsection