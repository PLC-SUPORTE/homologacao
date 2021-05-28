@extends('painel.Layout.header')
<?php $search = 2 ?>
@section('title') Dashboard equipe @endsection <!-- Titulo da pagina -->

@section('header') 
    <meta http-equiv="Content-Language" content="pt-br">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="author" content="Portal PL&C">
    <title>Dashboard equipe | Portal PL&C</title>
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
Programa de Distribuição de Resultado Variável ("RV")
@endsection
@section('submenu')
<li class="breadcrumb-item"><a href="{{ route('Painel.Gestao.Meritocracia.index') }}">Dashboard individual acumulado</a>
</li>
<li class="breadcrumb-item active" style="color: black;">Dashboard equipe
</li>
@endsection
@section('body')
    <div>
   <div class="row">
     
        <div class="row">
        <div class="content-wrapper-before blue-grey lighten-5"></div>
        <div class="col s12">
          <div class="container">

          <div class="card">
        <div class="card-content" style="padding: 14px;">
            <p class="caption mb-0">
            O quadro abaixo mostra as notas dos sócios em seus centros de custo referente ao último mês de apuração.
            </p>
        </div>
    </div>


<section class="invoice-list-wrapper section">


  <div class="invoice-filter-action mr-4">

     <div class="invoice-filter-action mr-4">

     <a href="{{ route('Painel.Gestao.Meritocracia.index') }}" class="btn-floating btn-mini waves-effect waves-light buttons-right tooltipped" 
    data-position="top" data-tooltip="Voltar ao dashboard individual acumulado" style="background-color: gray;margin-top: 4px;"><i class="material-icons">home</i></a>

     <a  href="{{ route('Painel.Gestao.Meritocracia.CartasRV.historico') }}" class="btn-floating btn-mini waves-effect waves-light buttons-right tooltipped" data-position="left" data-tooltip="Dashboard individual histórico" style="background-color: gray; margin-top: 4px;"><i class="material-icons">dashboard</i></a>

    <a href="{{ route('Painel.Gestao.Meritocracia.minhasnotas') }}"class="btn-floating btn-mini waves-effect waves-light buttons-right tooltipped" 
    data-position="top" data-tooltip="Minhas notas" style="background-color: gray;margin-top: 4px;"><i class="material-icons">analytics</i></a>

    @if($nivel == "Superintendente" || $nivel == "Coordenador" || $nivel == "Subcoordenador 1" || $nivel == "Subcoordenador 2"  ||
                $nivel == "Coordenador Controladoria" || $nivel == "Coordenador ControladoriaSP" || $nivel == "Gerente" || $nivel == "Gerente Equipe Passiva" || $nivel == "COO")
    <a  href="{{ route('Painel.Gestao.Meritocracia.Hierarquia.index') }}" 
    class="btn-floating btn-mini waves-effect waves-light buttons-right tooltipped" 
    data-position="left" data-tooltip="Dashboard meritocracia" style="margin-left: 5px;background-color: gray; margin-top: 4px;"><i class="material-icons" style="margin-top: -2px;">connect_without_contact</i></a>
    @endif

    <a href="{{ route('Painel.Gestao.Meritocracia.Prazos.index') }}" 
      class="btn-floating btn-mini waves-effect waves-light buttons-right tooltipped" 
      data-position="top" data-tooltip="Volumetria de prazos vencidos meta" style="background-color: gray; margin-top: 4px;"><i class="material-icons">hourglass_disabled</i></a>

    <a href="{{ route('Painel.Gestao.Meritocracia.Contrato.index') }}" 
      class="btn-floating btn-mini waves-effect waves-light buttons-right tooltipped" 
      data-position="top" data-tooltip="Meus contratos" style="background-color: gray; margin-top: 4px;"><i class="material-icons">description</i></a>
  </div>

  </div>



  <div class="responsive-table">
    <table class="table invoice-data-table white border-radius-4 pt-1" style="width: 100%;">
      <thead>
        <tr>
          <th style="font-size: 11px"></th>
          <th style="font-size: 11px">Usuário</th>
          <th style="font-size: 11px">Unidade</th>
          <th style="font-size: 11px">Setor</th>
          <th style="font-size: 11px">Mês</th>
          <th style="font-size: 11px">PLC %</th>
          <th style="font-size: 12px">Superintendência %</th>
          <th style="font-size: 12px">Gerência %</th>
          <th style="font-size: 11px">Área %</th>
          <th style="font-size: 11px" class="tooltipped" data-position="bottom" data-tooltip="O RV Máximo indica o valor total que você poderá receber, a título de RV em relação ao Período Base, considerando os resultados do PLC, Unidade e Área e o seu Score Individual com nota máxima.">RV Máximo</th>
          <th style="font-size: 11px" class="tooltipped" data-position="bottom" data-tooltip="O RV Apurado indica o valor total que você poderá receber, a título de RV em relação ao Período Base, considerando os resultados do PLC, Unidade e Área e o seu Score Individual com a nota atual.">RV Apurado</th>
          <th style="font-size: 11px" class="tooltipped" data-position="bottom" data-tooltip="O RV Recebido indica o valor já recebido por você até o Mês de Apuração.">RV Recebido</th>
          <th style="font-size: 11px" class="tooltipped" data-position="bottom" data-tooltip="RV Projetado correspondente a projeção do RV que você irá receber em relação ao Período Base de acordo com a fórmula: RV Apurado - RV Recebido = RV Projetado.">RV Projetado</th>
          <th style="font-size: 11px">Score mês</th>
          <th style="font-size: 11px">Score acumulada</th>
          <th style="font-size: 11px"></th>
        </tr>
      </thead>

      <tbody>
      @foreach($datas as $data)  
        <tr>

  <!--Modal mostrando nota Advogado --> 
  <div id="modal{{$data->usuario_codigo}}" class="modal modal-fixed-footer" style="heigth:100%;width: 1200px;overflow:hidden">

    <a href="#!" class="btn waves-effect mr-sm-1 mr-2 modal-close red align right"
     data-position="top" data-tooltip="Detalhamento" style="margin-left: -20px;margin-top: 10px;">
     <i class="material-icons">close</i></a>

    
      <iframe style=" position:absolute;
    top:60;
    left:0;
    width:100%;
    height:100%;" src="{{route('Painel.Gestao.Meritocracia.Hierarquia.NotasAdvogado.detalha', $data->usuario_codigo)}}"></iframe>


  </div>
  <!-- Fim Modal Nota Adv -->



          <td style="font-size: 10px"></td>
          <td style="font-size: 10px">{{mb_convert_case($data->usuario_nome, MB_CASE_TITLE, "UTF-8")}}</td>
          <td style="font-size: 10px">{{$data->unidade}}</td>
          <td style="font-size: 10px">{{$data->setor}}</td>
          <td style="font-size: 10px">{{$data->mes}}</td>
          <td style="font-size: 10px">{{$data->plc_porcent}}%</td>
          <td style="font-size: 10px">{{$data->unidade_porcent}}%</td>
          <td style="font-size: 10px">{{$data->gerencia_porcent}}%</td>
          <td style="font-size: 10px">{{$data->area_porcent}}%</td>
          <td style="font-size: 10px">R$ <?php echo number_format($data->rv_maximo,2,",",".") ?></td>
          <td style="font-size: 10px">R$ <?php echo number_format($data->rv_apurado,2,",",".") ?></td>
          <td style="font-size: 10px">R$ <?php echo number_format($data->rv_recebido,2,",",".") ?></td>
          <td style="font-size: 10px">R$ <?php echo number_format($data->rv_projetado,2,",",".") ?></td>  
          <td style="font-size: 10px">{{$data->notaconsolidada}}</td>
          <td style="font-size: 10px">{{$data->notaacumulada}}</td>

          <td style="font-size: 10px">
      
          <div class="invoice-action">
          <a href="#modal{{$data->usuario_codigo}}" class="invoice-action-view mr-4 modal-trigger tooltipped" data-position="bottom" data-tooltip="Clique aqui para ver o detalhamento deste sócio."><i class="material-icons">list</i></a>
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
$(document).ready(function(){
   $('.modal').modal();

});
</script>
@endsection