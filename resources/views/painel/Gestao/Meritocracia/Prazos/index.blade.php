@extends('painel.Layout.header')
<?php $search = 2 ?>
@section('title') Volumetria de prazos vencidos meta @endsection <!-- Titulo da pagina -->

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
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/dataTables.checkboxes.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/app-invoice.min.css') }}">
@endsection
@section('header_title')
Programa de Distribuição de Resultado Variável ("RV")
@endsection
@section('submenu')
<li class="breadcrumb-item"><a href="{{ route('Painel.Gestao.Meritocracia.index') }}">Dashboard individual acumulado</a>
</li>
<li class="breadcrumb-item active" style="color: black;">Volumetria de prazos vencidos meta
</li>
@endsection
@section('body')
   <div>
      <div class="row">
        <div class="content-wrapper-before blue-grey lighten-5"></div>
        <div class="col s12">


        <div class="card">
        <div class="card-content" style="padding: 14px;">
            <p class="caption mb-0">
            O quadro abaixo retrata a volumetria de prazos vencidos meta no sistema Advwin do mês de apuração e mês atual.
            </p>
        </div>
    </div>

          <div class="container">

<section class="invoice-list-wrapper section">

<div class="invoice-filter-action mr-4">

<div class="invoice-filter-action mr-4">
  <a href="{{ route('Painel.Gestao.Meritocracia.index') }}" class="btn-floating btn-mini waves-effect waves-light buttons-right tooltipped" 
  data-position="top" data-tooltip="Voltar ao dashboard individual acumulado" style="background-color: gray;"><i class="material-icons">home</i></a>

  <a  href="{{ route('Painel.Gestao.Meritocracia.CartasRV.historico') }}" class="btn-floating btn-mini waves-effect waves-light buttons-right tooltipped" data-position="left" data-tooltip="Dashboard individual histórico" style="background-color: gray;"><i class="material-icons">dashboard</i></a>

  <a href="{{ route('Painel.Gestao.Meritocracia.minhasnotas') }}"class="btn-floating btn-mini waves-effect waves-light buttons-right tooltipped" 
  data-position="top" data-tooltip="Minhas notas" style="background-color: gray;"><i class="material-icons">analytics</i></a>

  @if($nivel == "Superintendente" || $nivel == "Coordenador" || $nivel == "Subcoordenador 1" || $nivel == "Subcoordenador 2"  ||
                $nivel == "Coordenador Controladoria" || $nivel == "Coordenador ControladoriaSP" || $nivel == "Gerente" || $nivel == "Gerente Equipe Passiva" || $nivel == "COO")
    <a  href="{{ route('Painel.Gestao.Meritocracia.Hierarquia.index') }}" 
    class="btn-floating btn-mini waves-effect waves-light buttons-right tooltipped" 
    data-position="left" data-tooltip="Dashboard meritocracia" style="margin-left: 5px;background-color: gray; margin-top: 4px;"><i class="material-icons" style="margin-top: -2px;">groups</i></a>
                
    <!-- <a  href="{{ route('Painel.Gestao.Meritocracia.Hierarquia.Setor.index') }}" 
    class="btn-floating btn-mini waves-effect waves-light buttons-right tooltipped" 
    data-position="left" data-tooltip="Dashboard equipe" style="margin-left: 5px;background-color: gray; margin-top: 4px;"><i class="material-icons" style="margin-top: -2px;">groups</i></a> -->
                
  @endif


  <a href="{{ route('Painel.Gestao.Meritocracia.Contrato.index') }}" 
    class="btn-floating btn-mini waves-effect waves-light buttons-right tooltipped" 
    data-position="top" data-tooltip="Meus contratos" style="background-color: gray; margin-top: 4px;"><i class="material-icons">description</i></a> 

</div>
</div>


  <div class="responsive-table">
    <table class="table invoice-data-table white border-radius-4 pt-1">
      <thead>
        <tr>
          <th style="font-size: 12px"></th>
          <th style="font-size: 12px">#</th>
          <th style="font-size: 12px">Ident</th>
          <th style="font-size: 12px">Código pasta</th>
          <th style="font-size: 12px">Número processo</th>
          <th style="font-size: 12px">Mov.</th>
          <th style="font-size: 12px">Unidade</th>
          <th style="font-size: 12px">Data criação</th>
          <th style="font-size: 12px">Data prazo</th>
          <th style="font-size: 12px">Data encerramento</th>
          <th style="font-size: 12px">Status</th>
          <th style="font-size: 12px"></th>
        </tr>
      </thead>

      <tbody>
      @foreach($datas as $data)  
        <tr>
          <td style="font-size: 11px"></td>
          <td style="font-size: 11px"></td>
          <td style="font-size: 11px">{{$data->Ident}}</td>
          <td style="font-size: 11px">{{$data->Pasta}}</td>
          <td style="font-size: 11px">{{$data->NumeroProcesso}}</td>
          <td style="font-size: 11px">{{$data->Mov}}</td>
          <td style="font-size: 11px">{{$data->Unidade}}</td>
          <td style="font-size: 12px">{{ date('d/m/Y', strtotime($data->Data)) }}</td>
          <td style="font-size: 12px">{{ date('d/m/Y', strtotime($data->DataPrazo)) }}</td>
          <td style="font-size: 12px">{{ date('d/m/Y', strtotime($data->DataFechamento)) }}</td>
          @if($data->Status == 0)
          <td style="font-size: 11px"><span class="bullet green"></span>Ativa </td>
          @else 
          <td style="font-size: 11px"><span class="bullet red"></span>ínativa </td>
          @endif

          <td style="font-size: 11px">
      
          <!-- <div class="invoice-action">
          <a href="{{route('Painel.Gestao.Meritocracia.Prazos.contestar', $data->Ident)}}" class="invoice-action-view mr-4"><i class="material-icons">info</i></a>
          </div> -->

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
@endsection