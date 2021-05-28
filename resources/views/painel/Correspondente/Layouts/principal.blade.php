<!DOCTYPE html>
<html lang="{{ env('locale') }}">
    @includeIf('Painel.Correspondente.Layouts.head')
    <body id="body" class="hold-transition skin-purple">
        <div class="wrapper">
            @includeIf('Painel.Correspondente.Layouts.header')
            @includeIf('Painel.Correspondente.Layouts.sidebarLateral')
            @yield('content')
            @section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <ol class="breadcrumb">
            <li><a href="{{ route('Home.Principal.Show') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Painel Principal</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-lg-12 col-xs-12" style="padding:0;">
 
                    <h5 class="title_painel">[Solicitação de pagamento correspondente] - [PL&C Advogados]</h5>
                    @can('correspondentes')
                  <!--  <div class="col-lg-12 col-xs-12">
                        <div class="small-box" style="background-color: #D3D3D3	">
                            <div class="inner">
                                <h3>{{ $totalSolicitacoesAbertas }}</h3>
                            </div>
                            <div class="icon">
                               <i class="fa fa-arrow-circle-right"></i>
                            </div>
                                <a href="{{ route('Painel.Correspondente.index') }}" class="small-box-footer">
                         Clique Aqui <i class="fa fa-arrow-circle-right"></i>
                        </a>
                      </div>
                        <p>Solicitação Pagamento</p>
                        </div> -->
              <div class="box-body">
                <div class="col-xs-6 col-md-4 text-center">
                  <input id="knob" data-width="10" data-height="10" data-displayInput=true data-readonly="true" value="{{ $totalSolicitacoesAbertas }}"id="correspondentes" data-toggle="tooltip" data-placement="left" title="Total de solicitações 'Em Andamento'.">
                  <strong><p id="correspondentes" data-toggle="tooltip" data-placement="left" title="Total de solicitações em 'Andamento'.">Total Solicitações em andamento</p></strong>
                </div>
                  <div class="col-xs-6 col-md-4 text-center">
                  <input id="knob2" data-width="10" data-height="10" data-displayInput=true  data-readonly="true" value="{{ $totalSolicitacoesPagasCorrespondente }}"id="correspondentes" data-toggle="tooltip" data-placement="left" title="Total de solicitações 'Pagas'.">
                   <strong><p id="correspondentes" data-toggle="tooltip" data-placement="left" title="Total de solicitações 'Pagas'.">Total solicitações pagas</p></strong>
                </div>
                   <div class="col-xs-6 col-md-4 text-center">
                  <input id="knob3" data-width="10" data-height="10" data-displayInput=true  data-readonly="true" value="{{ $totalSolicitacoesCancelada }}"id="correspondentes" data-toggle="tooltip" data-placement="left" title="Total de solicitações 'Cancelada.">
                  <strong><p id="correspondentes" data-toggle="tooltip" data-placement="left" title="Total de solicitações 'Canceladas'.">Total solicitações canceladas</p></strong>
                </div>
              </div>
                  <hr style=" border-color:#aaa;box-sizing:border-box;width:100%;  "/>

          <!--         
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-brown"><i class="fa fa-certificate"></i></span>

        
            <div class="info-box-content">
              <span class="info-box-text">Ranking Geral</span>
              <span class="info-box-number" style="font-size: 12px">0º</span>
            </div>
          </div>
        </div>   -->
                  
        <!-- /.col -->
          <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-brown"><i class="fa fa-cogs"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">TOP 01 Serviços</span>
              <span class="info-box-number" style="font-size: 12px">{{$topservico_descricao}} - ({{$topservico_id}})</span>
            </div>
          </div>
        </div>
        <!-- /.col -->
        
        <!-- /.col -->
          <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-brown"><i class="fa fa-handshake-o"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Total Serviços</span>
              <span class="info-box-number" style="font-size: 12px">{{$totalSolicitacoes}}</span>
            </div>
          </div>
        </div>
        <!-- /.col -->
        
         <!-- /.col -->
          <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-brown"><i class="fa fa-times"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Cancelados</span>
              <span class="info-box-number" style="font-size: 12px">{{$totalSolicitacoesCancelada}}</span>
            </div>
          </div>
        </div>
        <!-- /.col -->
        @endcan   
        </div>
         </div>
        </div>
  </div>
  </div>
        </section>
    </div>
            @includeIf('Painel.Correspondente.Layouts.footer')
            @includeIf('Painel.Correspondente.Layouts.javascript')
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