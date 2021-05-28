@extends('painel.Layout.header')
<?php $search = 2 ?>
@section('title') Dashboard individual histórico @endsection <!-- Titulo da pagina -->

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
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/dropify.min.css') }}">

    <style>
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
Programa de Distribuição de Resultado Variável ("RV")
@endsection
@section('submenu')
<li class="breadcrumb-item"><a href="{{ route('Painel.Gestao.Meritocracia.index') }}">Dashboard individual acumulado</a>
</li>
<li class="breadcrumb-item active" style="color: black;">Dashboard individual histórico
</li>
@endsection
@section('body')
   <div>
   <div class="row">
    

        <center>
  <div id="loading">
      <div class="wrapper">
      <div class="circle circle-1"></div>
      <div class="circle circle-1a"></div>
      <div class="circle circle-2"></div>
      <div class="circle circle-3"></div>
     </div>
     <h1 style="text-align: center;">Aguarde, enquanto estamos carregando o seu histórico deste ano...&hellip;</h1>
     </div>
  </center>   


        <div class="row" id="corpodiv">
        <div class="content-wrapper-before blue-grey lighten-5"></div>
        <div class="col s12">
          <div class="container">

          <div class="card">
        <div class="card-content" style="padding: 10px;">
            <p class="caption mb-0">
            O quadro abaixo retrata a evolução histórica da performance mês a mês e os números indicados em cada mês não devem ser somados, já que o resultado do último mês sempre refletirá o desempenho acumulado até então, considerando todos os meses anteriores.
            </p>
        </div>
    </div>

<section class="invoice-list-wrapper section">

  <div class="invoice-filter-action mr-4">
    <a href="{{ route('Painel.Gestao.Meritocracia.index') }}" class="btn-floating btn-mini waves-effect waves-light buttons-right tooltipped" 
    data-position="top" data-tooltip="Voltar ao dashboard individual acumulado" style="background-color: gray;"><i class="material-icons">home</i></a>

    <a href="{{ route('Painel.Gestao.Meritocracia.minhasnotas') }}"class="btn-floating btn-mini waves-effect waves-light buttons-right tooltipped" 
    data-position="top" data-tooltip="Minhas notas" style="background-color: gray;"><i class="material-icons">analytics</i></a>


    @if($nivel == "Superintendente" || $nivel == "Coordenador" || $nivel == "Subcoordenador 1" || $nivel == "Subcoordenador 2"  ||
                $nivel == "Coordenador Controladoria" || $nivel == "Coordenador ControladoriaSP" || $nivel == "Gerente" || $nivel == "Gerente Equipe Passiva" || $nivel == "COO")
    <a  href="{{ route('Painel.Gestao.Meritocracia.Hierarquia.index') }}" 
    class="btn-floating btn-mini waves-effect waves-light buttons-right tooltipped" 
    data-position="left" data-tooltip="Dashboard meritocracia" style="margin-left: 5px;background-color: gray; margin-top: 4px;"><i class="material-icons" style="margin-top: -2px;">connect_without_contact</i></a>
                
    <!-- <a  href="{{ route('Painel.Gestao.Meritocracia.Hierarquia.Setor.index') }}" 
    class="btn-floating btn-mini waves-effect waves-light buttons-right tooltipped" 
    data-position="left" data-tooltip="Dashboard equipe" style="margin-left: 5px;background-color: gray; margin-top: 4px;"><i class="material-icons" style="margin-top: -2px;">groups</i></a> -->
                
    @endif

    <a href="{{ route('Painel.Gestao.Meritocracia.Prazos.index') }}" 
      class="btn-floating btn-mini waves-effect waves-light buttons-right tooltipped" 
      data-position="top" data-tooltip="Volumetria de prazos vencidos meta" style="background-color: gray; margin-top: 4px;"><i class="material-icons">hourglass_disabled</i></a>

    <a href="{{ route('Painel.Gestao.Meritocracia.Contrato.index') }}" 
      class="btn-floating btn-mini waves-effect waves-light buttons-right tooltipped" 
      data-position="top" data-tooltip="Meus contratos" style="background-color: gray; margin-top: 4px;"><i class="material-icons">description</i></a>
  </div>

    

  <div class="responsive-table">
    <table class="table invoice-data-table white border-radius-4 pt-1">
      <thead>
        <tr>
          <th style="font-size: 12px"></th>
          <th style="font-size: 12px">Unidade</th>
          <th style="font-size: 12px">Setor</th>
          <th style="font-size: 12px">Mês</th>
          <th style="font-size: 12px">PLC %</th>
          <th style="font-size: 12px">Superintendência %</th>
          <th style="font-size: 12px">Gerência %</th>
          <th style="font-size: 12px">Área %</th>
          <th style="font-size: 12px">Score</th>
          <th style="font-size: 12px" class="tooltipped" data-position="bottom" data-tooltip="O RV Máximo indica o valor total que você poderá receber, a título de RV em relação ao Período Base, considerando os resultados do PLC, Unidade e Área e o seu Score Individual com nota máxima.">RV Máximo</th>
          <th style="font-size: 12px" class="tooltipped" data-position="bottom" data-tooltip="O RV Apurado indica o valor total que você poderá receber, a título de RV em relação ao Período Base, considerando os resultados do PLC, Unidade e Área e o seu Score Individual com a nota atual.">RV Apurado</th>
          <th style="font-size: 12px" class="tooltipped" data-position="bottom" data-tooltip="O RV Recebido indica o valor já recebido por você até o Mês de Apuração.">RV Recebido</th>
          <th style="font-size: 12px" class="tooltipped" data-position="bottom" data-tooltip="RV Projetado correspondente a projeção do RV que você irá receber em relação ao Período Base de acordo com a fórmula: RV Apurado - RV Recebido = RV Projetado.">RV Projetado</th>
        </tr>
      </thead>

      <tbody>
      @foreach($datas as $data)  
      @if($data->mes_id == $mesapuracao && $data->ano == $ano)
        <tr style="background-color: #D3D3D3">

          <td style="font-size: 11px;color: black"></td>
          <td style="font-size: 11px;color: black">{{$data->unidade}}</td>
          <td style="font-size: 11px;color: black">{{$data->setor}}</td>
          <td style="font-size: 11px;color: black">{{$data->mes}}</td>
          <td style="font-size: 11px;color: black">{{$data->plc_porcent}}%</td>
          <td style="font-size: 11px;color: black">{{$data->unidade_porcent}}%</td>
          <td style="font-size: 11px;color: black">{{$data->gerencia_porcent}}%</td>
          <td style="font-size: 11px;color: black">{{$data->area_porcent}}%</td>
          <td style="font-size: 11px;color: black">{{$data->score_porcent}}%</td>
          <td style="font-size: 11px;color: black">R$ <?php echo number_format($data->rv_maximo,2,",",".") ?></td>
          <td style="font-size: 11px;color: black">R$ <?php echo number_format($data->rv_apurado,2,",",".") ?></td>
          <td style="font-size: 11px;color: black">R$ <?php echo number_format($data->rv_recebido,2,",",".") ?></td>
          <td style="font-size: 11px;color: black">R$ <?php echo number_format($data->rv_projetado,2,",",".") ?></td>
        </tr>
      @else 
        <tr>
          <td style="font-size: 11px"></td>
          <td style="font-size: 11px">{{$data->unidade}}</td>
          <td style="font-size: 11px">{{$data->setor}}</td>
          <td style="font-size: 11px">{{$data->mes}}</td>
          <td style="font-size: 11px">{{$data->plc_porcent}}%</td>
          <td style="font-size: 11px">{{$data->unidade_porcent}}%</td>
          <td style="font-size: 11px">{{$data->gerencia_porcent}}%</td>
          <td style="font-size: 11px">{{$data->area_porcent}}%</td>
          <td style="font-size: 11px">{{$data->score_porcent}}%</td>
          <td style="font-size: 11px">R$ <?php echo number_format($data->rv_maximo,2,",",".") ?></td>
          <td style="font-size: 11px">R$ <?php echo number_format($data->rv_apurado,2,",",".") ?></td>
          <td style="font-size: 11px">R$ <?php echo number_format($data->rv_recebido,2,",",".") ?></td>
          <td style="font-size: 11px">R$ <?php echo number_format($data->rv_projetado,2,",",".") ?></td>
        </tr>
      @endif  
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
   $("#corpodiv").hide();
});
</script>


<script>
setTimeout(function() {
   $('#loading').fadeOut('fast');
   $("#corpodiv").show();
}, 4000);
</script>


@endsection