@extends('painel.Layout.header')
<?php $search = 2 ?>
@section('title') Usuários no Force 1 @endsection <!-- Titulo da pagina -->

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
      <link rel="stylesheet" href="{{ asset('/public/materialize/css/app-invoice.min.css') }}">
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
         h1 {
         color: #222;
         font-size: 15px;
         font-weight: 400;
         letter-spacing: 0.05em;
         margin: 40px auto;
         text-transform: uppercase;
         }
      </style>
@endsection
@section('header_title')
Informativo de RV
@endsection
@section('submenu')
<li class="breadcrumb-item active" style="color: black;">Usuários no Force 1
</li>
@endsection
@section('body')
      <div>
         <div class="row">

            <div class="row" id="corpodiv">
               <div class="content-wrapper-before blue-grey lighten-5"></div>
               <div class="col s12">
                  <div class="container">

                     <section class="invoice-list-wrapper section">

                     <div class="invoice-filter-action mr-4">
                       <a href="#modal" class="btn-floating btn-mini waves-effect waves-light tooltipped modal-trigger"data-position="left" data-tooltip="Clique aqui para importar"  style="margin-left: 5px;background-color: gray;"><i class="material-icons">add</i></a>
                       <a  href="" class="btn-floating btn-mini waves-effect waves-light buttons-right tooltipped" data-position="left" data-tooltip="Clique aqui para exportar em Excel." style="background-color: gray;"><img style="margin-top: 8px; width: 20px;margin-left:8px;" src="{{URL::asset('/public/imgs/icon.png')}}"/></a>
                       </div>

                        <div class="responsive-table">
                           <table class="table invoice-data-table white border-radius-4 pt-1" style="width: 100%;">
                              <thead>
                                 <tr>
                                    <th style="font-size: 12px"></th>
                                    <th style="font-size: 12px">Usuário</th>
                                    <th style="font-size: 12px">Usuário</th>
                                    <th style="font-size: 12px">Usuário</th>
                                    <th style="font-size: 12px">Usuário</th>
                                    <th style="font-size: 12px">Usuário</th>
                                    <th style="font-size: 11px">Usuário</th>
                                    <th style="font-size: 12px"></th>
                                 </tr>
                              </thead>
                              <tbody>

                                 <tr>
                                    <td style="font-size: 11px"></td>
                                    <td style="font-size: 11px"></td>
                                    <td style="font-size: 11px"></td>
                                    <td style="font-size: 11px"></td>
                                    <td style="font-size: 11px"></td>
                                    <td style="font-size: 11px"></td>
                                    <td style="font-size: 11px"></td>

                                    <td style="font-size: 11px">

                                       <div class="invoice-action">
                                          <a href="" class="invoice-action-view mr-4"><i class="material-icons">edit</i></a>
                                       </div>
                                    </td>

                                 </tr>
                                 
                              </tbody>
                           </table>
                        </div>
                     </section>
                  </div>
                  <div class="content-overlay"></div>
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
      <script src="{{ asset('/public/materialize/js/app-invoice.min.js') }}"></script>
      <script>

         
         document.addEventListener("DOMContentLoaded", function () {
            $('.modal').modal();
         });
      </script>
 
      <script>

         function envia() {
         
             document.getElementById("loadingdiv").style.display = "";
             document.getElementById("corpodiv").style.display = "none";
             document.getElementById("form").submit();
         }    
      </script>

   @endsection