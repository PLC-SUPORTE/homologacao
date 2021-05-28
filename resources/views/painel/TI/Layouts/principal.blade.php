<!DOCTYPE html>
<html lang="{{ env('locale') }}">
    @includeIf('Painel.TI.Layouts.head')
    <body id="body" class="hold-transition skin-purple">
        <div class="wrapper">
            @includeIf('Painel.TI.Layouts.header')
            @includeIf('Painel.TI.Layouts.sidebarLateral')
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
                    <h5 class="title_painel">Home Page - [PL&C Advogados]</h5>
                @can('coordenador')
              <div class="box-body">
                <div class="col-xs-6 col-md-4 text-center">
                  <input id="knob" data-width="10" data-height="10" data-displayInput=true data-readonly="true" value="{{ $totalUser}}"id="correspondentes" data-toggle="tooltip" data-placement="left" title="Através deste botão é possível realizar a listagem de solicitações feitas através do portal.">
                  <strong><p id="correspondentes" data-toggle="tooltip" data-placement="left" title="Através deste botão é possível realizar a listagem de solicitações feitas através do portal.">Total Usuárioss</p></strong>
                </div>
                  <div class="col-xs-6 col-md-4 text-center">
                  <input id="knob2" data-width="10" data-height="10" data-displayInput=true  data-readonly="true" value="{{ $totalProfiles }}"id="correspondentes" data-toggle="tooltip" data-placement="left" title="Através deste botão é possível realizar a listagem de solicitações feitas através do portal.">
                   <strong><p id="correspondentes" data-toggle="tooltip" data-placement="left" title="Através deste botão é possível realizar a listagem de solicitações feitas através do portal.">Total Profiles</p></strong>
                </div>
                   <div class="col-xs-6 col-md-4 text-center">
                  <input id="knob3" data-width="10" data-height="10" data-displayInput=true  data-readonly="true" value="0"id="correspondentes" data-toggle="tooltip" data-placement="left" title="Através deste botão é possível realizar a listagem de solicitações feitas através do portal.">
                  <strong><p id="correspondentes" data-toggle="tooltip" data-placement="left" title="Através deste botão é possível realizar a listagem de solicitações feitas através do portal.">Total Solicitações Suporte</p></strong>
                </div>
              </div>
                  <hr style=" border-color:#aaa;box-sizing:border-box;width:100%;  "/>
               @endcan   
              </div>
                </div>
            </div>
        </section>
    </div>
            @includeIf('Painel.TI.Layouts.footer')
            @includeIf('Painel.TI.Layouts.javascript')
        </div>
    </body>
</html>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
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