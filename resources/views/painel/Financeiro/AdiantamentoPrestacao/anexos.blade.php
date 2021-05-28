<!DOCTYPE html>

<html class="loading" lang="pt-br" data-textdirection="ltr">
  <head>
    <meta http-equiv="Content-Language" content="pt-br">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="author" content="Portal PL&C">
    <title>Anexos | Portal PL&C</title>
    <link rel="apple-touch-icon" href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/images/favicon/apple-touch-icon-152x152.png">
    <link rel="shortcut icon" href="{{ asset('/public/assets/imgs/casinha.png')}}" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">


    <link rel="stylesheet" href="{{ asset('/public/materialize/css/vendors.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/materialize.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/app-file-manager.min.css') }}">



    </head>

<body data-open="click" data-menu="vertical-modern-menu" data-col="2-columns" style="overflow: hidden;">


 <!-- BEGIN: Page Main-->

  <div class="section app-file-manager-wrapper" style="height: 550px; margin-top: -2px; overflow: hidden;">

  <div class="app-file-overlay"></div>

  <div class="sidebar-left">
    <div class="app-file-sidebar display-flex">
      <div class="app-file-sidebar-left">
        <span class="app-file-sidebar-close hide-on-med-and-up"><i class="material-icons">close</i></span>

        <div class="app-file-sidebar-content">

          <span class="app-file-label">Pastas</span>
          <div class="collection file-manager-drive mt-3">

            <a href="#" class="collection-item file-item-action active">
              <div class="fonticon-wrap display-inline mr-3">
                <i class="material-icons">folder_open</i>
              </div>
              <span style="color: black;">Todos arquivos</span>
              <span class="chip red lighten-5 float-right red-text">{{$QuantidadeAnexos}}</span>
            </a>

            <a href="#modalimportaarquivo"  class="collection-item file-item-action modal-trigger" >
              <div class="fonticon-wrap display-inline mr-3">
                <i class="material-icons">add</i>
              </div>
              <span style="color: black;">Adicionar anexo</span>
            </a>

          </div>
        </div>
      </div>
    </div>

  </div>


  
                        <!--Inicio Modal Anexos --> 
                        <div id="modalimportaarquivo" class="modal modal-fixed-footer" style="width: 400px;height:250px;overflow:hidden;">

                        <form id="form" role="form" onsubmit="btnsubmit.disabled = true; return true;" action="{{ route('Painel.Financeiro.Reembolso.importaranexos') }}" method="POST" role="create" enctype="multipart/form-data" >
                        {{ csrf_field() }}

                        <input type="hidden" name="numerodebite" id="numerodebite" value="{{$numerodebite}}">

                        <div class="modal-content">

                          <button type="button" class="btn  mr-sm-1 mr-2 modal-close red" style="margin-top: -26px; margin-left: 280px;"><i class="material-icons">close</i></button>
                                            
                            <div id="corpodiv4">
                            <p style="font-size: 11px;">Selecione o arquivo ou arquivos que você deseja anexar:</p>
                            <br>

                            <div class="col s12 m12 l12">
                            <input style="font-size: 10px;"  type="file" id="select_file"  name="select_file[]" accept=".jpg,.png,.xls,.xlsx,.pdf,.doc,.docx,.rtf,.jpeg" required class="dropify" data-default-file="" />
                            </div>
                                      
                            </div>
                        </div>

                        <div class="modal-footer" style="margin-top: 100px;">
                           <button type="submit" id="btnsubmit" class="modal-action  btn-flat" style="background-color: gray;color:white;font-size:11px;"><i class="material-icons left">save</i>Importar anexo(s)</button>
                        </div>
                       
                        </div>
                        <!--Fim Modal Anexos --> 


  <!--/ sidebar left end -->
  <!-- content-right start -->
  <div class="content-right">
    <!-- file manager main content start -->
    <div class="app-file-area">
      <!-- File App Content Area -->

      <!-- App File Content Starts -->
      <div class="app-file-content">
        <h6 class="font-weight-700 mb-3">Todos arquivos</h6>

        <div class="row app-file-recent-access mb-3">

        <!--Loop Arquivos --> 
        @foreach($datas as $data)

  <!--Inicio Modal Visualizar Anexo --> 
  <div id="anexos{{$data->Arq_nick}}" class="modal modal-fixed-footer" style="width: 100%;height:100%;overflow:hidden;">

      <iframe src="{{ url('public/imgs/reembolso/'.$data->Arq_nick) }}" width="100%" height="100%" style="border: none;"></iframe>
     
  </div>
  <!--Fim Modal  Visualizar Anexos --> 



          <div class="col xl2 l3 m2 s6">
          <a href="#anexos{{$data->Arq_nick}}" class="modal-trigger">
              <div class="card box-shadow-none mb-1 app-file-info">



              <div class="card-content">
                <div class="app-file-content-logo grey lighten-4">
                  <img class="recent-file" src="https://pixinvent.com/materialize-material-design-admin-template/app-assets/images/icon/pdf.png" height="38" width="30"
                    alt="Card image cap">

                </div>

                <div class="app-file-recent-details">
                  <div class="app-file-name" style="font-size: 12px;color:#6b6f82;">{{$data->Nome}}</div>
                  <div class="app-file-size" style="font-size: 10px;color:#6b6f82;">Responsável: {{$data->Responsavel}} </div>
                  <div class="app-file-last-access" style="font-size: 10px;color:#6b6f82;">Data upload : {{ date('d/m/Y H:i:s', strtotime($data->Data)) }}</div>
                </div>

              </div>
            </div>
            </a>
          </div>

        @endforeach  
        <!--Fim Loop--> 



      </div>
    </div>

  </div>
 
</div>


          </div>
          <div class="content-overlay"></div>

    <!-- END: Page Main-->

 


    <script src="{{ asset('/public/materialize/js/vendors.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>


    
<script>
document.addEventListener("DOMContentLoaded", function () {
   $('.modal').modal();
});
</script>


  </body>
</html>