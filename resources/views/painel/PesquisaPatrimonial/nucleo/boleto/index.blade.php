@extends('painel.Layout.header')
@section('title') Boletos da pesquisa patrimonial @endsection <!-- Titulo da pagina -->

@section('header')
    <link rel="apple-touch-icon" href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/images/favicon/apple-touch-icon-152x152.png">
    <link rel="shortcut icon" href="{{ asset('/public/assets/imgs/casinha.png')}}" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/responsive.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/materialize.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/data-tables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/select2-materialize.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/form-select2.min.css') }}">


@endsection
@section('header_title')
Pesquisa patrimonial
@endsection
@section('submenu')
<li class="breadcrumb-item"><a href="{{ route('Painel.PesquisaPatrimonial.nucleo.index') }}">Dashboard</a>
</li>
<li class="breadcrumb-item active" style="color: black;">Boletos gerados na pesquisa patrimonial
</li>
@endsection
@section('body')
    <div>


   <div class="row" id="div_all">

   {!! Form::open(['route' => ['Painel.PesquisaPatrimonial.nucleo.boleto.programado'], 'id' => 'form', 'files' => true ,'class' => 'form form-search form-ds']) !!}
  {{ csrf_field() }}

      
        <div class="col s12">
          <div class="container">
            <div class="section section-data-tables">

  <div class="row">
    <div class="col s12">
      <div class="card">
        <div class="card-content">
          <div class="row">
            <div class="col s12">
            <a href="{{route('Painel.PesquisaPatrimonial.nucleo.boleto.boletosemandamento')}}" class="btn-floating btn-mini waves-effect waves-light tooltipped"data-position="left" data-tooltip="Clique aqui para acompanhar os boletos já enviados ao cliente."  style="margin-left: 1200px;margin-top:-50px;background-color: gray;"><i class="material-icons">list</i></a>


              <table id="multi-select" class="display">
                <thead>
                  <tr>
                    <th>
                      <label>
                        <input type="checkbox" onClick="marcatodos();"/>
                        <span></span>
                      </label>
                    </th>
                    <th style="font-size: 11px">Cliente</th>
                    <th style="font-size: 11px">CPF/CNPJ</th>
                    <th style="font-size: 11px">Nosso número</th>
                    <th style="font-size: 11px">Nº do documento</th>
                    <th style="font-size: 11px">CPR</th>
                    <th style="font-size: 11px">Data de vencimento</th>
                    <th style="font-size: 11px">Valor</th>
                    <th style="font-size: 11px">Remessa</th>
                    <th style="font-size: 11px"></th>
                  </tr>
                </thead>
                <tbody>

                
                @foreach($datas as $data)
                <!--Inicio Modal Anexos --> 

                  <tr>
                    <td>
                      <label>
                      <input type="checkbox" class="check" name="codigo_boleto[]" id="codigo_boleto[]" value="{{$data->NumeroDocumento}}" />
                        <span></span>
                      </label>
                    </td>
                               
                    <td style="font-size: 10px">{{$data->ClienteRazao}}</td>
                    <td style="font-size: 10px">{{$data->ClienteCodigo}}</td>
                    <td style="font-size: 10px">{{$data->NossoNumero}}</td>
                    <td style="font-size: 10px">{{$data->NumeroDocumento}}</td>
                    <td style="font-size: 10px">{{$data->CPR}}</td>
                    <td style="font-size: 10px">{{ date('d/m/Y', strtotime($data->DataVencimento)) }}</td>
                    <td style="font-size: 10px">R$ <?php echo number_format($data->Valor,2,",",".") ?></td>
                    @if($data->Remessa != null)
                    <td style="font-size: 10px;color:green;"><span class="bullet green"></span>Remessa enviada</td>
                    @else 
                    <td style="font-size: 10px;color:red;"><span class="bullet red"></span>Remessa não enviada</td>
                    @endif
                    <td style="font-size: 10px"> 

                    <a href="{{route('Painel.PesquisaPatrimonial.nucleo.boleto.baixarboleto', $data->CPR)}}" class="invoice-action-view mr-4 tooltipped modal-trigger" data-position="bottom" data-tooltip="Clique aqui para visualizar o boleto desta solicitação de pesquisa patrimonial."><i class="material-icons">attach_file</i></a>
                    <a href="{{route('Painel.PesquisaPatrimonial.nucleo.boleto.baixarnotadebito', $data->CPR)}}" class="invoice-action-view mr-4 tooltipped modal-trigger" data-position="bottom" data-tooltip="Clique aqui para visualizar a nota de debito desta solicitação de pesquisa patrimonial."><i class="material-icons">file_present</i></a>
                    <a href="{{route('Painel.PesquisaPatrimonial.nucleo.boleto.informacoes', $data->NumeroDocumento)}}" class="invoice-action-view mr-4 tooltipped modal-trigger" data-position="bottom" data-tooltip="Clique aqui para visualizar os destinatários do e-mail de cobrança."><i class="material-icons">alternate_email</i></a>

                    </td>
                  </tr>

                  @endforeach

                </tbody>

              </table>
            </div>
          </div>
        </div>

        <div class="card" style="background-color: #e2e3e5; border-color: #d6d8db">
        <div class="card-content">
        <div class="row">
        <div class="col s12 m12 l12">

        <div class="input-field col s3" style="margin-left: 900px;">
                  <button id="btnsubmit" class="btn waves-effect right" style="background-color: gray;font-size:11px;" onClick="abreconfirmacao();" type="button" name="action">Programar solicitações
                    <i class="material-icons left">check</i>
                  </button>
        </div>


        </div>
        </div>
        </div>
        </div>


    </form>
      </div>
    </div>
  </div>
</div>


@endsection
    <script src="{{ asset('/public/materialize/js/vendors.min.js') }}"></script> 
    <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/form-select2.min.js') }}"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
   $('.modal').modal();
});
</script>


<!-- @if(Session::has('message'))
<script type="text/javascript">
$('#alertanovoboleto').addClass('is-visible');
</script>
@endif() -->



<script>
function marcatodos() {

     $.each($('.check'),function(){
      if($(this).is(':checked')){
        $(this).prop('checked',false);
      }else{
        $(this).prop('checked',true);
      }
    });
}    
</script>

<script>
function abreconfirmacao() {
  // alert('Teste');
  document.getElementById("form").submit();
  // $('#alerta').addClass('is-visible');
}
</script>
