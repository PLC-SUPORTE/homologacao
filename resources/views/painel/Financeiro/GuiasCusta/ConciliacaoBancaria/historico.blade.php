@extends('painel.Layout.header')

@section('title') Histórico de solicitações de guia de custa @endsection <!-- Titulo da pagina -->

@section('header') 
      <meta http-equiv="Content-Language" content="pt-br">
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
          <meta name="author" content="Portal PL&C">
      <title>Histórico de solicitações de guia de custa | Portal PL&C</title>
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

@endsection

@section('header_title')
Solicitação de pagamento de guias de custas processuais
@endsection

@section('submenu')
<li class="breadcrumb-item"><a href="{{ route('Painel.Financeiro.GuiasCustas.ConciliacaoBancaria.index') }}">Solicitações em andamento</a>
</li>
<li class="breadcrumb-item active" style="color: black;">Histórico
</li>
@endsection

@section('body')
      <!-- BEGIN: Page Main-->

      <div>
         <div class="row">

            <div class="content-wrapper-before blue-grey lighten-5"></div>
            <div class="col s12" id="div_all">
               <div class="container">
                  <div class="section">
                     <section class="invoice-list-wrapper section">
                        <div class="invoice-filter-action mr-3">

                              <a href="{{ route('Painel.Financeiro.GuiasCustas.ConciliacaoBancaria.index') }}" class="waves-light btn" style="color: white; background-color: gray; font-size: 11px; border-radius: 50px;">
                              <i class="material-icons left">keyboard_backspace</i>Voltar</a>
                       
                        </div>
                        <div class="responsive-table">
                           <table class="table invoice-data-table white border-radius-4 pt-1" style="font-size: 11px;">
                              <thead>
                                 <tr>
                                    <th style="font-size: 11px"></th>
                                    <th style="font-size: 11px">Número do debite</th>
                                    <th style="font-size: 11px">Código pasta</th>
                                    <th style="font-size: 11px">Setor do PL&C</th>
                                    <th style="font-size: 11px">Unidade</th>
                                    <th style="font-size: 11px">Tipo debite</th>
                                    <th style="font-size: 11px">Valor</th>
                                    <th style="font-size: 11px">Data solicitação</th>
                                    <th style="font-size: 11px">Data serviço</th>
                                    <th style="font-size: 11px">Status</th>
                                    <th style="font-size: 11px"></th>
                                 </tr>
                              </thead>
                              <tbody>


                                 @foreach($datas as $data)  
                                 <tr>

                        <!--Inicio Modal Anexos --> 
                        <div id="anexos{{$data->NumeroDebite}}" class="modal modal-fixed-footer" style="width: 100%;height:100%;overflow:hidden;">
                        <button type="button" class="btn  mr-sm-1 mr-2 modal-close red" style="margin-left: 1255px; margin-top: 5px;">
                        <i class="material-icons">close</i> 
                        </button>
                        <iframe style=" position:absolute;
                           top:60;
                           left:0;
                           width:100%;
                           height:100%;" src="{{ route('Painel.Financeiro.GuiasCustas.anexos', $data->NumeroDebite) }}"></iframe>
                        </div>
                        <!--Fim Modal Anexos --> 

                        <td style="font-size: 10px"></td>
                        <td style="font-size: 10px">{{ $data->NumeroDebite }}</td>
                        <td style="font-size: 10px">{{ $data->Pasta }}</td>
                        <td style="font-size: 10px">{{ $data->Setor }}</td>
                        <td style="font-size: 10px">{{ $data->Unidade }}</td>
                        <td style="font-size: 10px">{{ $data->TipoDebite }}</td>
                        <td style="font-size: 10px">R$ <?php echo number_format($data->Valor,2,",",".") ?></td>
                        <td style="font-size: 10px">{{ date('d/m/Y', strtotime($data->DataSolicitacao)) }}</td>
                        <td style="font-size: 10px">{{ date('d/m/Y', strtotime($data->DataServico)) }}</td>
                        @if($data->StatusID == 4)
                        <td style="font-size: 10px"><span class="bullet green"></span>{{$data->Status}}</td>
                        @elseif($data->StatusID == 5)
                        <td style="font-size: 10px"><span class="bullet red"></span>{{$data->Status}}</td>
                        @endif
                        <td style="font-size: 10px">
                        <div class="invoice-action">
                        <a href="#anexos{{$data->NumeroDebite}}" class="invoice-action-view mr-4 tooltipped modal-trigger" data-position="bottom" data-tooltip="Clique aqui para visualizar os anexos desta solicitação de guia de custa."><i class="material-icons">attach_file</i></a>
                        </div>
                        </td>
                        </tr> 
                        @endforeach
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

      
@endsection