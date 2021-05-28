
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PL&C ADVOGADOS</title>

 <link rel="stylesheet" href="https://e6t7a8v2.stackpathcdn.com/tutorial/css/fontawesome-all.min.css">
 <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-grid.min.css') }}">
 <link rel="stylesheet" href="{{ asset('assets/css/common-1.css') }}">
 <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <!-- Compiled and minified CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

</head>
<body>

    <div class="demo" >
        <div class="container">
            <div class="row text-center">
                <h4 class="white">Acompanhamento da Solicitação Debite: {{$numeroprocesso->NumeroDebite}} </h4>
                <h5>Data Solicitação: {{ date('d/m/Y H:i:s', strtotime($numeroprocesso->DataFicha)) }}</h5>
                <h5>Data Última Alteração: </h5>
            </div>
            
            <br><br>

            <div class="row">
                <!--Solicitação de Pagamento -->
                <div class="main-timeline">
                    <div class="col-md-2 col-sm-6 timeline">
                        <div class="timeline-icon"><i class="fa fa-check" title="Fícha incluida no sistema em: {{ date('d/m/Y H:i:s', strtotime($numeroprocesso->DataFicha)) }}"></i></div>
                        <div class="timeline-content" title="Fícha incluida no sistema em: {{ date('d/m/Y H:i:s', strtotime($numeroprocesso->DataFicha)) }}">
                            <div class="post">Solicitação de Debite</div>
                            <p class="description">
                                <i class="fa fa-user"></i>&nbsp;&nbsp; {{$numeroprocesso->Nome}}
                            </p> 
                            <p class="description">
                                <i class="fa fa-calendar"></i>&nbsp;&nbsp; {{ date('d/m/Y H:i:s', strtotime($numeroprocesso->DataFicha)) }}
                            </p> 
                            <!--
                             <p class="description">
                                <i class="fa fa-bell">&nbsp;&nbsp;SLA: </i> 
                            </p> -->
                        </div>
                    </div>
                  <!--Fim Solicitação de Pagamento -->  
                  
                  <!--Solicitação Reprovada -->
                  <!-- Verifico se ja foi reprovada, para mostrar Verde, se não ira ficar cinza) -->
                  @if(!empty($dataReprovada->NumeroDebite))
                    <div class="col-md-2 col-sm-6 timeline">
                        <div class="timeline-icon"><i class="fa fa-check" title="Fícha reprovada pelo motivo: {{$dataReprovada->Motivo}} - {{$dataReprovada->ObservacaoMotivo}}"></i></div>
                        <div class="timeline-content" title="Fícha reprovada pelo motivo: {{$dataReprovada->Motivo}} - {{$dataReprovada->ObservacaoMotivo}}">
                            <div class="post">Solicitação Reprovada</div>
                                 <p class="description">
                                <i class="fa fa-user"></i>&nbsp;&nbsp; {{$dataReprovada->Nome}}
                            </p> 
                            <p class="description">
                                <i class="fa fa-calendar"></i>&nbsp;&nbsp;{{ date('d/m/Y', strtotime($dataReprovada->DataFicha)) }}
                            </p> 
                          <!--
                             <p class="description">
                                <i class="fa fa-bell">&nbsp;&nbsp;SLA: </i> 
                            </p> -->
                        </div>
                    </div>
                  @endif
                  <!--Fim Reprovada-->
                  
                  
                  <!--Se a solicitação for cancelada -->
                 @if(!empty($dataCancelada->NumeroDebite))
                    <div class="col-md-2 col-sm-6 timeline">
                        <div class="timeline-icon"><i class="fa fa-check" title="Fícha reprovada pelo motivo: {{$dataCancelada->Motivo}} - {{$dataCancelada->ObservacaoMotivo}}"></i></div>
                        <div class="timeline-content" title="Fícha reprovada pelo motivo: {{$dataCancelada->Motivo}} - {{$dataCancelada->ObservacaoMotivo}}">
                            <div class="post">Solicitação Cancelada</div>
                                 <p class="description">
                                <i class="fa fa-user"></i>&nbsp;&nbsp; {{$dataCancelada->Nome}}
                            </p> 
                            <p class="description">
                                <i class="fa fa-calendar">&nbsp;&nbsp;</i>{{ date('d/m/Y', strtotime($dataCancelada->DataFicha)) }}
                            </p> 
                             <!--
                             <p class="description">
                                <i class="fa fa-bell">&nbsp;&nbsp;SLA: </i> 
                            </p> -->
                        </div>
                    </div>
                  @else
                  <!--Se a solicitação não estiver cancelada mostrar os proximos processos -->
                  <!--Revisão Coordenador -->
                  <!-- Verifico se ja foi Aprovado, para mostrar Verde, se não ira ficar cinza) -->
                  @if( !empty($dataAprovada->NumeroDebite))
                    <div class="col-md-2 col-sm-6 timeline">
                        <div class="timeline-icon"><i class="fa fa-check"></i></div>
                        <div class="timeline-content">
                            <div class="post">Revisão Coordenador</div>
                                 <p class="description">
                                <i class="fa fa-user"></i>&nbsp;&nbsp; {{$dataAprovada->Nome}}
                            </p> 
                            <p class="description">
                                <i class="fa fa-calendar">&nbsp;&nbsp;</i>{{ date('d/m/Y', strtotime($dataAprovada->DataFichaAprovada)) }}
                            </p> 
                         <!--
                             <p class="description">
                                <i class="fa fa-bell">&nbsp;&nbsp;SLA: </i> 
                            </p> -->
                        </div>
                    </div>
                  @else
                    <div class="col-md-2 col-sm-6 timeline">
                        <div class="timeline-icon2"><i class="fa fa-check"></i></div>
                        <div class="timeline-content">
                            <div class="post">Revisão Coordenador</div>
                                 <p class="description">
                                <i class="fa fa-user">&nbsp;&nbsp;</i> 
                            </p> 
                            <p class="description">
                                <i class="fa fa-calendar">&nbsp;&nbsp;</i> 
                            </p> 
                      <!--
                             <p class="description">
                                <i class="fa fa-bell">&nbsp;&nbsp;SLA: </i> 
                            </p> -->
                        </div>
                    </div>
                  @endif
                  <!--Fim Revisão Coordenador -->
                  
                  <!--Revisão Financeiro -->
                  <!-- Verifico se ja foi Aprovado, para mostrar Verde, se não ira ficar cinza) -->
                  @if( !empty($dataAprovadaFinanceiro->NumeroDebite))
                    <div class="col-md-2 col-sm-6 timeline">
                        <div class="timeline-icon"><i class="fa fa-check"></i></div>
                        <div class="timeline-content">
                            <div class="post">Revisão Financeiro</div>
                                 <p class="description">
                                <i class="fa fa-user"></i>&nbsp;&nbsp; {{$dataAprovadaFinanceiro->Nome}}
                            </p> 
                            <p class="description">
                                <i class="fa fa-calendar"></i>&nbsp;&nbsp;{{ date('d/m/Y', strtotime($dataAprovadaFinanceiro->DataFicha)) }}
                            </p> 
                          <!--
                             <p class="description">
                                <i class="fa fa-bell">&nbsp;&nbsp;SLA: </i> 
                            </p> -->
                        </div>
                    </div>
                  @else
                    <div class="col-md-2 col-sm-6 timeline">
                        <div class="timeline-icon2"><i class="fa fa-check"></i></div>
                        <div class="timeline-content">
                            <div class="post">Revisão Financeiro</div>
                                 <p class="description">
                                <i class="fa fa-user">&nbsp;&nbsp;</i> 
                            </p> 
                            <p class="description">
                                <i class="fa fa-calendar">&nbsp;&nbsp;</i> 
                            </p> 
                           <!--
                             <p class="description">
                                <i class="fa fa-bell">&nbsp;&nbsp;SLA: </i> 
                            </p> -->
                        </div>
                    </div>
                  @endif
                  <!--Fim Revisão Financeiro -->
                  

                  <!--Programação Pagamento Financeiro -->
                  <!-- Verifico se ja foi feito Programação, para mostrar Verde, se não ira ficar cinza) -->
                  @if( !empty($dataProgramadaPagamento->NumeroDebite))
                    <div class="col-md-2 col-sm-6 timeline">
                        <div class="timeline-icon"><i class="fa fa-check"></i></div>
                        <div class="timeline-content">
                            <div class="post">Programação Pagamento</div>
                                 <p class="description">
                                <i class="fa fa-user"></i> &nbsp;&nbsp;{{$dataProgramadaPagamento->Nome}}
                            </p> 
                            <p class="description">
                                <i class="fa fa-calendar"></i>&nbsp;&nbsp;{{ date('d/m/Y', strtotime($dataProgramadaPagamento->DataFicha)) }}
                            </p> 
                         <!--
                             <p class="description">
                                <i class="fa fa-bell">&nbsp;&nbsp;SLA: </i> 
                            </p> -->
                        </div>
                    </div>
                  @else
                    <div class="col-md-2 col-sm-6 timeline">
                        <div class="timeline-icon2"><i class="fa fa-check"></i></div>
                        <div class="timeline-content">
                            <div class="post">Programação Pagamento</div>
                                 <p class="description">
                                <i class="fa fa-user">&nbsp;&nbsp;</i> 
                            </p> 
                            <p class="description">
                                <i class="fa fa-calendar">&nbsp;&nbsp;</i> 
                            </p> 
                          <!--
                             <p class="description">
                                <i class="fa fa-bell">&nbsp;&nbsp;SLA: </i> 
                            </p> -->
                        </div>
                    </div>
                  @endif
                  <!--Fim Programação Pagamento Financeiro-->
                  

                  <!--Pago Financeiro -->
                  <!-- Verifico se ja foi Pago, para mostrar Verde, se não ira ficar cinza) -->
                  @if( !empty($dataPagamento->NumeroDebite))
                    <div class="col-md-2 col-sm-6 timeline">
                        <div class="timeline-icon"><i class="fa fa-check"></i></div>
                        <div class="timeline-content">
                            <div class="post">Pagamento Efetuado</div>
                                 <p class="description">
                                <i class="fa fa-user"></i>&nbsp;&nbsp; {{$dataPagamento->Nome}}
                            </p> 
                            <p class="description">
                                <i class="fa fa-calendar"></i>&nbsp;&nbsp;{{ date('d/m/Y', strtotime($dataPagamento->DataFicha)) }}
                            </p> 
                          <!--
                             <p class="description">
                                <i class="fa fa-bell">&nbsp;&nbsp;SLA: </i> 
                            </p> -->
                        </div>
                    </div>
                  @else
                    <div class="col-md-2 col-sm-6 timeline">
                        <div class="timeline-icon2"><i class="fa fa-check"></i></div>
                        <div class="timeline-content">
                            <div class="post">Pagamento Efetuado</div>
                                 <p class="description">
                                <i class="fa fa-user">&nbsp;&nbsp;</i> 
                            </p> 
                            <p class="description">
                                <i class="fa fa-calendar">&nbsp;&nbsp;</i> 
                            </p> 
                         <!--
                             <p class="description">
                                <i class="fa fa-bell">&nbsp;&nbsp;SLA: </i> 
                            </p> -->
                        </div>
                    </div>
                  @endif
                  <!--Fim Pago -->
                  

                 <!--Inicio Comprovante Pagamento -->
                  <!-- Verifico se ja foi Aprovado, para mostrar Verde, se não ira ficar cinza) -->
                  @if( !empty($dataComprovante->NumeroDebite))
                    <div class="col-md-2 col-sm-6 timeline">
                        <div class="timeline-icon"><i class="fa fa-download"></i></div>
                        <div class="timeline-content">
                            <div class="post">Comprovante Pagamento</div>
                                 <p class="description">
                                <i class="fa fa-user"></i> &nbsp;&nbsp;{{$dataComprovante->Nome}}
                            </p> 
                            <p class="description">
                                <i class="fa fa-calendar"></i>&nbsp;&nbsp;{{ date('d/m/Y', strtotime($dataComprovante->DataFichaAprovada)) }}
                            </p> 
                         <!--
                             <p class="description">
                                <i class="fa fa-bell">&nbsp;&nbsp;SLA: </i> 
                            </p> -->
                        </div>
                    </div>
                  @else
                    <div class="col-md-2 col-sm-6 timeline">
                        <div class="timeline-icon2" ><i class="fa fa-download" ></i></div>
                        <div class="timeline-content">
                            <div class="post">Comprovante Pagamento</div>
                                 <p class="description">
                                <i class="fa fa-user">&nbsp;&nbsp;</i> 
                            </p> 
                            <p class="description">
                                <i class="fa fa-calendar">&nbsp;&nbsp;</i> 
                            </p> 
                         <!--
                             <p class="description">
                                <i class="fa fa-bell">&nbsp;&nbsp;SLA: </i> 
                            </p> -->
                        </div>
                    </div>
                  @endif
                  <!--Fim Comprovante Pagamento Anexado --> 
                  
                  
                  @endif
                  <!-- Fim Cancelamento -->
                  
                  
             
                  
                </div>
            </div>
        </div>
    </div>

<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.min.js"></script>



<script type="text/javascript" src="https://bestjquery.com/tutorial/js/script.js"></script>

</body>
</html>