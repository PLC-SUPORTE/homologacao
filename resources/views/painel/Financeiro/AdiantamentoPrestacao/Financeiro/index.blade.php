@extends('painel.Layout.header')

@section('title') Relação de usuários com saldo @endsection <!-- Titulo da pagina -->

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
@endsection

@section('header_title')
Adiantamento/Prestação de conta
@endsection

@section('submenu')
<li class="breadcrumb-item active" style="color: black;">Saldo por usuário
</li>
@endsection

@section('body')
   <div>
      <div class="row">
        <div class="content-wrapper-before blue-grey lighten-5"></div>
        <div class="col s12">
          <div class="container">

<section class="invoice-list-wrapper section">


  <div class="responsive-table">
    <table class="table invoice-data-table white border-radius-4 pt-1">
      <thead>
        <tr>
          <th style="font-size: 11px"></th>
          <th style="font-size: 11px">Nome</th>
          <th style="font-size: 11px">CPF</th>
          <th style="font-size: 11px">Setor</th>
          <th style="font-size: 11px">Unidade</th>
          <th style="font-size: 11px">Valor aguardando revisão</th>
          <th style="font-size: 11px">Valor aguardando prestação de conta</th>
          <!-- <th style="font-size: 11px">Saldo atual</th> -->
          <th style="font-size: 11px"></th>
          <th style="font-size: 11px"></th>


        </tr>
      </thead>

      <tbody>

      @foreach($datas as $data)
        <tr>

    <div id="modalfiltro{{$data->cpf}}" class="modal"  style="40% !important;height: 30% !important;">
    <form role="form" id="form" onsubmit="btnsubmit.disabled = true; return true;" action="{{ route('Painel.Financeiro.AdiantamentoPrestacao.Financeiro.movimentacaobancaria') }}" method="POST" role="search" enctype="multipart/form-data" >
   {{ csrf_field() }}

   <input type="hidden" name="usuario_cpf" value="{{$data->cpf}}">

    <div class="modal-content">
    <p style="font-size: 11px;">Informe a data de ínicio e fim para montagem da movimentação bancária do(a): <?php echo mb_convert_case($data->name, MB_CASE_TITLE, "UTF-8")?></p>
      
    <div class="col m3 s12 input-field">
    <span style="font-size: 11px;">Data da transfêrencia:</span>
    <input style="font-size: 10px;"  name="datainicio" id="datainicio" value="{{$datahoje}}" type="date" class="form-control" data-toggle="tooltip" data-placement="top" title="Selecione a data ínicio." required="required">
    </div>

    <div class="col m3 s12 input-field">
    <span style="font-size: 11px;">Data da transfêrencia:</span>
    <input style="font-size: 10px;"  name="datafim" id="datafim" value="{{$datahoje}}" type="date" class="form-control" data-toggle="tooltip" data-placement="top" title="Selecione a data fim." required="required">
    </div>

    </div>
    
    <div class="modal-footer" style="margin-top: 10px;margin-left:-170px;">
      <button type="submit" id="btnsubmit" class="modal-action  waves-effect waves-green btn-flat " style="background-color: green;color:white;font-size:11px;"><i class="material-icons left">check</i>Montar relatório</button>
    </div>
    </form>
</div>



        <td style="font-size: 10px"></td>
        <td style="font-size: 10px"><?php echo mb_convert_case($data->name, MB_CASE_TITLE, "UTF-8")?></td>
        <td style="font-size: 10px">{{$data->cpf}}</td>
        <td style="font-size: 10px">{{$data->Setor}}</td>
        <td style="font-size: 10px">{{$data->Unidade}}</td>
        <td style="font-size: 10px">R$ <?php echo number_format($data->valor_aguardandorevisao,2,",",".") ?> </td>
        <td style="font-size: 10px">R$ <?php echo number_format($data->valor_aguardandoprestacao,2,",",".") ?> </td>
        <!-- <td style="font-size: 10px">R$ </td> -->
        <td style="font-size: 10px">

        <div class="invoice-action">
        <a href="{{ route('Painel.Financeiro.AdiantamentoPrestacao.Financeiro.novoadiantamento', $data->cpf) }}" class="tooltipped invoice-action-view mr-4" data-position="bottom" data-tooltip="Clique aqui para enviar um novo adiantamento."><i class="material-icons">add</i></a>
        <a href="{{ route('Painel.Financeiro.AdiantamentoPrestacao.Financeiro.solicitacoesadiantamento', $data->cpf) }}" class="tooltipped invoice-action-view mr-4" data-position="bottom" data-tooltip="Clique aqui para visualizar as solicitações de adiantamento deste usuário."><i class="material-icons">list</i></a>
        <a href="#modalfiltro{{$data->cpf}}" class="invoice-action-view mr-4 tooltipped modal-trigger" data-position="bottom" data-tooltip="Clique aqui para visualizar a movimentação bancária deste usuário."><i class="material-icons">find_in_page</i></a>
        </div>

        </td>
        <td style="font-size: 10px">

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