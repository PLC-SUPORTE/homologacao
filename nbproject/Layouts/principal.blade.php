<!DOCTYPE html>
<html lang="{{ env('locale') }}">
    @includeIf('Painel.Correspondente.Layouts.head')
    <body id="body"  class="hold-transition skin-purple sidebar-collapse sidebar-mini">
        <div class="wrapper">
            @includeIf('Painel.Correspondente.Layouts.header')
            @includeIf('Painel.Correspondente.Layouts.sidebarLateral')
            @includeIf('Painel.Correspondente.Layouts.javascript')

            @yield('content')
            @section('content')
    <div class="content-wrapper">
        <section class="content">
          {{-- @include('flash::message') --}}
          @if (session('status'))
            <div style= 'background-color: #F7F7F7; 
            height: 60px; border-radius:12px;
            font-family: Arial, Helvetica, sans-serif;'>
                <p style="margin-left: 30px; padding-top: 17px; font-size: 16px;">{{ session('status') }}</p>
            </div><br>
          @endif
            <div class="row">
                <div class="col-lg-12 col-xs-12" style="padding:0;">

            <div class="col-md-4" style="background-color:white; height: 222.5px; overflow-y: scroll; margin-left: 30px;">
              <p class="text-center">
                <strong>Notas Operadores(as)</strong>
              </p>

              @foreach($notas as $categoria)
               <div class="progress-group">
                <span class="progress-text">{{$categoria->OperadorNome}}</span>
                
                @if ($categoria->Nota <= 0)
                  <span class="progress-number"><b>0</b><b>/10</b></span>
                @else
                <span class="progress-number"><b><?php echo number_format($categoria->Nota,2,",",".") ?></td></b><b>/10,00</b></span>
                @endif

                <div class="progress sm">
                  @if ($categoria->Nota == 10)
                    <div class="progress-bar progress-bar-success" style="width: 100%"></div>
                  @endif
                  @if ($categoria->Nota == 9)
                   <div class="progress-bar progress-bar-success" style="width: 90%"></div>
                  @endif
                  @if ($categoria->Nota == 8)
                    <div class="progress-bar progress-bar-success" style="width: 80%"></div>
                  @endif
                  @if ($categoria->Nota == 7)
                    <div class="progress-bar progress-bar-yellow" style="width: 70%"></div>
                  @endif
                  @if ($categoria->Nota == 6)
                    <div class="progress-bar progress-bar-yellow" style="width: 60%"></div>
                  @endif
                  @if ($categoria->Nota == 5)
                    <div class="progress-bar progress-bar-yellow" style="width: 50%"></div>
                  @endif
                  @if ($categoria->Nota == 4)
                    <div class="progress-bar progress-bar-danger" style="width: 40%"></div>
                  @endif
                  @if ($categoria->Nota == 3)
                    <div class="progress-bar progress-bar-danger" style="width: 30%"></div>
                  @endif
                  @if ($categoria->Nota == 2)
                    <div class="progress-bar progress-bar-danger" style="width: 20%"></div>
                  @endif
                  @if ($categoria->Nota == 1)
                    <div class="progress-bar progress-bar-danger" style="width: 10%"></div>
                  @endif
                  @if ($categoria->Nota <= 0)
                  <div class="progress-bar progress-bar-danger" style="width: 1%"></div>
                @endif
                </div>
              </div>
            @endforeach
            </div>
            <div class="box-body">
                <div class="col-xs-6 col-md-4 text-center" style="margin-left: 35%; margin-top: -14.5%;">
                <input id="knob2" data-width="10" data-height="10" data-displayInput=true  data-readonly="true" value="{{$avaliacoesSemana}}" id="correspondentes" data-toggle="tooltip" data-placement="left" title="Total de solicitações na semana.">
                 <strong><p id="correspondentes" data-toggle="tooltip" data-placement="left" title="Total de solicitações na semana.">Total de avaliações na semana</p></strong>
              </div>
                 <div class="col-xs-6 col-md-4 text-center" style="margin-left: 60%; margin-top: -14.5%; font-size:13.5px;">
                <input id="knob3" data-width="10" data-height="10" data-displayInput=true  data-readonly="true" value="{{$avaliacoesMes}}"id="correspondentes" data-toggle="tooltip" data-placement="left" title="Total de solicitações no mês.">
                <strong><p id="correspondentes" data-toggle="tooltip" data-placement="left" title="Total de solicitações no mês.">Total de avaliações no mês</p></strong>
              </div>
            </div>


        <section class="content">
          <div class="row">
            <div class="col-xs-12">


        <!-- Button trigger modal -->

      
        @if (count($notas) != 0)
          <button type="button" class="btn btn-primary"  data-toggle="modal" data-target="#exampleModalCenter"
          style="background-color:#4B4B4B;border-color:#4B4B4B; margin-left: 92.5%; margin-bottom: 2px;" >
            Mais opções
          </button>
      @endif

      <!-- Modal -->
     <!-- Modal -->
      <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header" style="background-color: #D7182A; height: 45px;">
              <h5 class="modal-title" id="exampleModalLongTitle" style="color: white; font-size: 15px;"><b>Opções</b></h5>
            </div>
            <div class="modal-body" style="margin-top: -20px;">
              
              <a href="{{route('Painel.Avaliacao.mediaConsolidadaSemanal')}}" target="_blank" style="color:black;" data-toggle="tooltip" data-placement="left" title="Clique para visualizar a média consolidada semanal."><br>
                <i class="fa fa-line-chart" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;
                Média consolidada Semanal 
              </a><br>
              <a href="{{route('Painel.Avaliacao.mediaConsolidadaMensal')}}" target="_blank" style="color:black;" data-toggle="tooltip" data-placement="left" title="Clique para visualizar a média consolidada mensal."><br>
                <i class="fa fa-line-chart" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;
                Média consolidada Mensal 
              </a><br>
              <a href="{{route('Painel.Avaliacao.visualizarTodas')}}" target="_blank" style="color:black;" data-toggle="tooltip" data-placement="left" title="Clique para visualizar todas as avaliações."><br>
                <i class="fa fa-list-ul" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;
                Visualizar todas as avaliações
              </a>
            </div>
            <div class="modal-footer" style="height: 55px;">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
          </div>
        </div>
      </div>

      <style>
       .modal-backdrop.in{
         display: none;
      }

      .modal-content{
          -webkit-box-shadow: 0 5px 15px rgba(0,0,0,0);
          -moz-box-shadow: 0 5px 15px rgba(0,0,0,0);
          -o-box-shadow: 0 5px 15px rgba(0,0,0,0);
          box-shadow: 0 5px 15px rgba(0,0,0,0);
      }

      </style>

  

              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Avaliação Operadores(as)</h3>
                </div>
                <div class="box-body">
                        <div class="table-responsive">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th style="font-size: 12px">Operador(a)</th>
                                    <th style="font-size: 12px">Data</th>
                                    <th style="font-size: 12px">Carteira</th>
                                    <th style="font-size: 12px">Nota</th>
                                    <th style="font-size:12px;">Relatório /  Download</th>
                                    <th style="font-size:12px;">Excluir Avaliação</th>
                                </tr>
                                </thead>
                                <tbody>
                                
                                @foreach($notas as $categoria)  
                                    <tr>
                                        <td id="myInputTextField" style="font-size: 12px">{{ $categoria->OperadorNome }}</td>                  
                                        <td style="font-size: 12px">{{ date('d/m/Y', strtotime($categoria->Data)) }}</td>
                                        <td style="font-size: 12px">{{ $categoria->Cliente}}</td>
                                        <!--Verifico a nota -->
                                        @if($categoria->Nota >=8)
                                        <td style="font-size: 12px;color: green"><?php echo number_format($categoria->Nota,2,",",".") ?></td>
                                        @elseif($categoria->Nota == 7)
                                        <td style="font-size: 12px;color: #F0B501;"><?php echo number_format($categoria->Nota,2,",",".") ?></td>
                                        @elseif($categoria->Nota == 6)
                                        <td style="font-size: 12px;color: #F0B501;"><?php echo number_format($categoria->Nota,2,",",".") ?></td>
                                        @elseif($categoria->Nota == 5)
                                        <td style="font-size: 12px;color: #F0B501;"><?php echo number_format($categoria->Nota,2,",",".") ?></td>
                                        @elseif($categoria->Nota == 4)
                                        <td style="font-size: 12px;color: red"><?php echo number_format($categoria->Nota,2,",",".") ?></td>
                                        @elseif($categoria->Nota == 3)
                                        <td style="font-size: 12px;color: red"><?php echo number_format($categoria->Nota,2,",",".") ?></td>
                                        @elseif($categoria->Nota == 2)
                                        <td style="font-size: 12px;color: red"><?php echo number_format($categoria->Nota,2,",",".") ?></td>
                                        @elseif($categoria->Nota == 1)
                                        <td style="font-size: 12px;color: red"><?php echo number_format($categoria->Nota,2,",",".") ?></td>
                                        @elseif($categoria->Nota <= 0)
                                        <td style="font-size: 12px;color: red">0</td>

                                        @endif
                                        <!-- Fim Verificação -->
                                      <td>
                                        <a href="{{route('Painel.Avaliacao.relatorio', $categoria->id_matrix)}}" target="_blank" ><span class="btn btn-primary fa fa-eye" data-toggle="tooltip" data-placement="left" title="Clique aqui para visualizar esta avaliação." style="background-color:#4B4B4B;border-color:#4B4B4B; margin-right: 6%;"></span></a>
                                        <a href="{{route('Painel.Correspondente.gerarExcel', $categoria->id_matrix)}}" target="_blank" ><span class="btn btn-primary fa fa-download" data-toggle="tooltip" data-placement="left" title="Clique aqui para gerar o excel." style="background-color:#4B4B4B;border-color:#4B4B4B;"></span></a>
                                      </td>
                                      <td>
                                        <a href="{{route('Painel.Avaliacao.excluir', $categoria->id_matrix)}}" target="_blank" ><span class="btn btn-primary fa fa-trash" data-toggle="tooltip" data-placement="left" title="Clique aqui para excluir esta avaliação." style="background-color:#4B4B4B;border-color:#4B4B4B; margin-left: 12%;"></span></a>
                                      </td>
                                    </tr>  

                                @endforeach  

                                </tbody>
                            </table>
                        </div>
                    </div>
            </div>
          </div>
        </section>
    </div>
    </div>
    </div>
            @includeIf('Painel.Correspondente.Layouts.footer')
        </div>
    </body>
</html>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript" src="https://adminlte.io/themes/AdminLTE/bower_components/jquery-knob/js/jquery.knob.js"></script>
 <script type="text/javascript">
            $(document).ready(function() {
                var knobWidthHeight,
                    windowObj;
                // store reference to div obj
                windowObj = $(window);
                // if the window is higher than it is wider
                if(windowObj.height() > windowObj.width()){
                    // use 75% width
                    knobWidthHeight = Math.round(windowObj.width()*0.18);
                } else {
                // else if the window is wider than it is higher
                    // use 75% height
                    knobWidthHeight = Math.round(windowObj.height()*0.18);
                }
                // change the data-width and data-height attributes of the input to either 75%
                // of the width or 75% of the height
                $('#knob').attr('data-width',knobWidthHeight).attr('data-height',knobWidthHeight);

                // draw the nob NOTE: must be called after the attributes are set.
                $(function() {

                    $("#knob").knob({
                  'fgColor': 'black',
                  'bgColor': '#D3D3D3'
                 });
                });
            });
        </script>
        
        
         <script type="text/javascript">
            $(document).ready(function() {
                var knobWidthHeight,
                    windowObj;
                // store reference to div obj
                windowObj = $(window);
                // if the window is higher than it is wider
                if(windowObj.height() > windowObj.width()){
                    // use 75% width
                    knobWidthHeight = Math.round(windowObj.width()*0.18);
                } else {
                // else if the window is wider than it is higher
                    // use 75% height
                    knobWidthHeight = Math.round(windowObj.height()*0.18);
                }
                // change the data-width and data-height attributes of the input to either 75%
                // of the width or 75% of the height
                $('#knob2').attr('data-width',knobWidthHeight).attr('data-height',knobWidthHeight);

                // draw the nob NOTE: must be called after the attributes are set.
                $(function() {

                    $("#knob2").knob({
                  'fgColor': 'black',
                  'bgColor': '#D3D3D3'
                 });

                });
            });
        </script>
        
         <script type="text/javascript">
            $(document).ready(function() {
                var knobWidthHeight,
                    windowObj;
                // store reference to div obj
                windowObj = $(window);
                // if the window is higher than it is wider
                if(windowObj.height() > windowObj.width()){
                    // use 75% width
                    knobWidthHeight = Math.round(windowObj.width()*0.18);
                } else {
                // else if the window is wider than it is higher
                    // use 75% height
                    knobWidthHeight = Math.round(windowObj.height()*0.18);
                }
                // change the data-width and data-height attributes of the input to either 75%
                // of the width or 75% of the height
                $('#knob3').attr('data-width',knobWidthHeight).attr('data-height',knobWidthHeight);

                // draw the nob NOTE: must be called after the attributes are set.
                $(function() {

                    $("#knob3").knob({
                  'fgColor': 'black',
                  'bgColor': '#D3D3D3'
                 });

                });
            });
        </script>