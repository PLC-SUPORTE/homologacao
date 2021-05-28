@extends('painel.Layout.header')
@section('title') Reembolso - Solicitações aguardando CPR @endsection <!-- Titulo da pagina -->

@section('header') 
    <link rel="apple-touch-icon" href="https://pixinvent.com/materialize-material-design-admin-template/app-assets/images/favicon/apple-touch-icon-152x152.png">
    <link rel="shortcut icon" href="{{ asset('/public/assets/imgs/casinha.png')}}" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  
    <!-- BEGIN: VENDOR CSS-->
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/responsive.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/materialize.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/data-tables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/select2-materialize.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/form-select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/public/materialize/css/app-invoice.min.css') }}">

 
    <style>
     .span{
        font-weight: bold;
     }
/* HTML5 display-role reset for older browsers */
article, aside, details, figcaption, figure, 
footer, header, hgroup, menu, nav, section, main {
	display: block;
}
ol, ul {
	list-style: none;
}
blockquote, q {
	quotes: none;
}
blockquote:before, blockquote:after,
q:before, q:after {
	content: '';
	content: none;
}
table {
	border-collapse: collapse;
	border-spacing: 0;
}

.img-replace {
  /* replace text with an image */
  display: inline-block;
  overflow: hidden;
  text-indent: 100%;
  color: transparent;
  white-space: nowrap;
}


.cd-nugget-info {
  text-align: center;
  position: absolute;
  width: 100%;
  height: 50px;
  line-height: 50px;
  bottom: 0;
  left: 0;
}
.cd-nugget-info a {
  position: relative;
  font-size: 14px;
  color: #5e6e8d;
  -webkit-transition: all 0.2s;
  -moz-transition: all 0.2s;
  transition: all 0.2s;
}
.no-touch .cd-nugget-info a:hover {
  opacity: .8;
}
.cd-nugget-info span {
  vertical-align: middle;
  display: inline-block;
}
.cd-nugget-info span svg {
  display: block;
}
.cd-nugget-info .cd-nugget-info-arrow {
  fill: #5e6e8d;
}


.cd-popup-trigger {
  display: block;
  width: 170px;
  height: 50px;
  line-height: 50px;
  margin: 3em auto;
  text-align: center;
  color: #FFF;
  font-size: 14px;
  font-size: 0.875rem;
  font-weight: bold;
  text-transform: uppercase;
  border-radius: 50em;
  background: #35a785;
  box-shadow: 0 3px 0 rgba(0, 0, 0, 0.07);
}
@media only screen and (min-width: 1170px) {
  .cd-popup-trigger {
    margin: 6em auto;
  }
}

.cd-popup {
  position: fixed;
  left: 0;
  top: 0;
  height: 100%;
  width: 100%;
  opacity: 0;
  visibility: hidden;
  -webkit-transition: opacity 0.3s 0s, visibility 0s 0.3s;
  -moz-transition: opacity 0.3s 0s, visibility 0s 0.3s;
  transition: opacity 0.3s 0s, visibility 0s 0.3s;
}
.cd-popup.is-visible {
  opacity: 1;
  visibility: visible;
  -webkit-transition: opacity 0.3s 0s, visibility 0s 0s;
  -moz-transition: opacity 0.3s 0s, visibility 0s 0s;
  transition: opacity 0.3s 0s, visibility 0s 0s;
}

.cd-popup-container {
  position: relative;
  width: 90%;
  max-width: 400px;
  margin: 4em auto;
  background: #FFF;
  border-radius: .25em .25em .4em .4em;
  text-align: center;
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
  -webkit-transform: translateY(-40px);
  -moz-transform: translateY(-40px);
  -ms-transform: translateY(-40px);
  -o-transform: translateY(-40px);
  transform: translateY(-40px);
  /* Force Hardware Acceleration in WebKit */
  -webkit-backface-visibility: hidden;
  -webkit-transition-property: -webkit-transform;
  -moz-transition-property: -moz-transform;
  transition-property: transform;
  -webkit-transition-duration: 0.3s;
  -moz-transition-duration: 0.3s;
  transition-duration: 0.3s;
}
.cd-popup-container p {
  padding: 3em 1em;
}
.cd-popup-container .cd-buttons:after {
  content: "";
  display: table;
  clear: both;
}
.cd-popup-container .cd-buttons li {
  float: left;
  width: 50%;
  list-style: none;
}
.cd-popup-container .cd-buttons a {
  display: block;
  height: 60px;
  line-height: 60px;
  text-transform: uppercase;
  color: #FFF;
  -webkit-transition: background-color 0.2s;
  -moz-transition: background-color 0.2s;
  transition: background-color 0.2s;
}
.cd-popup-container .cd-buttons li:first-child a {
  background: #b6bece;
  border-radius: 0 0 0 .25em;
}
.no-touch .cd-popup-container .cd-buttons li:first-child a:hover {
  background-color: #fc8982;
}
.cd-popup-container .cd-buttons li:last-child a {
  background: #52ca52;
  border-radius: 0 0 .25em 0;
}
.no-touch .cd-popup-container .cd-buttons li:last-child a:hover {
  background-color: #c5ccd8;
}
.cd-popup-container .cd-popup-close {
  position: absolute;
  top: 8px;
  right: 8px;
  width: 30px;
  height: 30px;
}
.cd-popup-container .cd-popup-close::before, .cd-popup-container .cd-popup-close::after {
  content: '';
  position: absolute;
  top: 12px;
  width: 14px;
  height: 3px;
  background-color: #8f9cb5;
}
.cd-popup-container .cd-popup-close::before {
  -webkit-transform: rotate(45deg);
  -moz-transform: rotate(45deg);
  -ms-transform: rotate(45deg);
  -o-transform: rotate(45deg);
  transform: rotate(45deg);
  left: 8px;
}
.cd-popup-container .cd-popup-close::after {
  -webkit-transform: rotate(-45deg);
  -moz-transform: rotate(-45deg);
  -ms-transform: rotate(-45deg);
  -o-transform: rotate(-45deg);
  transform: rotate(-45deg);
  right: 8px;
}
.is-visible .cd-popup-container {
  -webkit-transform: translateY(0);
  -moz-transform: translateY(0);
  -ms-transform: translateY(0);
  -o-transform: translateY(0);
  transform: translateY(0);
}
@media only screen and (min-width: 1170px) {
  .cd-popup-container {
    margin: 8em auto;
  }
}
    </style>
  @endsection

  @section('header_title')
  Faturamento de debites
  @endsection

  @section('submenu')
  <li class="breadcrumb-item"><a href="{{ route('Painel.Financeiro.Reembolso.PagamentoDebite.index') }}">Clientes</a>
  </li>
  <li class="breadcrumb-item active" style="color: black;">Solicitações aguardando geração de CPR
  </li>
  @endsection

  @section('body')
   <div>
      <div class="row">
        <div class="content-wrapper-before blue-grey lighten-5"></div>



    <div id="loadingdiv" style="display:none;margin-top: 300px; margin-left: 570px;">
    <img style="width: 30px; margin-top: -100px;" src="{{URL::asset('/public/imgs/loading.gif')}}"/>
    <h6 style="font-size: 20px;margin-left:-210px;">Aguarde, estamos baixando as solicitações de reembolso selecionadas...</h6>
    </div>

        <div class="col s12" id="div_all">

          <div class="container">
            <div class="section">

<section class="invoice-list-wrapper section">
{!! Form::open(['route' => ['Painel.Financeiro.Reembolso.PagamentoDebite.baixado'], 'id' => 'form', 'files' => true ,'class' => 'form form-search form-ds']) !!}
  {{ csrf_field() }}

  <div class="responsive-table">
    <table id="multi-select"  class="table white border-radius-4 pt-1">
    <thead>
                  <tr>
                  <th style="font-size: 11px"></th>
                    <th>
                      <label>
                        <input type="checkbox" onClick="marcatodos();"/>
                        <span></span>
                      </label>
                    </th>
                    <th style="font-size: 11px">Debite</th>
                    <th style="font-size: 11px">Solicitante</th>
                    <th style="font-size: 11px">Cliente</th>
                    <th style="font-size: 11px">Tipo de debite</th>
                    <th style="font-size: 11px">Setor</th>
                    <th style="font-size: 11px">Número do processo</th>
                    <th style="font-size: 11px">Valor</th>
                    <th style="font-size: 11px">Data da solicitação</th>
                    <th style="font-size: 11px"></th>
                  </tr>
                </thead>
                <tbody>

                @foreach($datas as $categoria)

<!--Inicio Modal Anexos --> 
<div id="anexos{{$categoria->NumeroDebite}}" class="modal modal-fixed-footer" style="width: 100%;height:100%;overflow:hidden;">

<button type="button" class="btn waves-effect mr-sm-1 mr-2 modal-close red" style="margin-left: 1255px; margin-top: 5px;">
  <i class="material-icons">close</i> 
</button>

<iframe style=" position:absolute;
top:60;
left:0;
width:100%;
height:100%;" src="{{ route('Painel.Financeiro.Reembolso.anexos', $categoria->NumeroDebite) }}"></iframe>

</div>
<!--Fim Modal Anexos --> 



                  <tr>
                    <td></td>
                    <td>
                      <label>
                      <input type="checkbox" class="check" name="numerodebite[]" id="numerodebite[]" value="{{$categoria->NumeroDebite}}" />
                        <span></span>
                      </label>
                    </td>
                    {{ Form::hidden('fornecedor[]', $categoria->CodigoFornecedor, array('id' => 'invisible_id')) }}
                   
                    <td style="font-size: 10px">{{ $categoria->NumeroDebite}}</td>
                    <td style="font-size: 10px">{{ $categoria->Solicitante}}</td>
                    <td style="font-size: 10px">{{ $categoria->NomeFornecedor}}</td>
                    <td style="font-size: 10px">{{ $categoria->TipoDebite}}</td>
                    <td style="font-size: 10px">{{ $categoria->SetorDescricao}}</td>
                    <td style="font-size: 10px">{{ $categoria->NumeroProcesso}}</td>
                    <td style="font-size: 10px">R$ <?php echo number_format($categoria->ValorTotal,2,",",".") ?></td>
                    <td style="font-size: 10px">{{ date('d/m/Y' , strtotime($categoria->DataSolicitacao)) }}</td>
                    <td style="font-size: 10px;">
                    <a href="#anexos{{$categoria->NumeroDebite}}" class="invoice-action-view mr-4 tooltipped modal-trigger" data-position="bottom" data-tooltip="Clique aqui para visualizar os anexos desta solicitação de reembolso."><i class="material-icons">attach_file</i></a>
                    </td>
                  </tr>
                @endforeach
                </tbody>

        
    </table>
  </div>

  <div class="card" style="background-color: #e2e3e5; border-color: #d6d8db">
        <div class="card-content">
        <div class="row">
        <div class="col s12 m12 l12">

        <h6 class="card-title" style="font-size:11px;">Selecione os dados abaixo:</h6>

        <div class="input-field col s3">
        <select class="select2 browser-default" name="tipodoc" id="tipodoc" required>
        @foreach($tiposdoc as $tipodoc)   
        <option value="{{$tipodoc->Codigo}}">{{$tipodoc->Codigo}} - {{ $tipodoc->Descricao}}</option>
        @endforeach
        </select>
        <label style="font-size: 11px;color:black;">Selecione o tipo de documento:</label>
       </div>

       <div class="input-field col s3">
        <select class="select2 browser-default" name="tipolan" required>
        @foreach($tiposlan as $tipolan)
        <option value="{{$tipolan->Codigo}}">{{$tipolan->Codigo}} - {{$tipolan->Descricao}}</option>
        @endforeach
        </select>
        <label style="font-size: 11px;color:black;">Selecione o tipo de lançamento:</label>
       </div>

       <div class="input-field col s2">
            <label class="control-label" style="font-size: 11px;color:black;">Data de vencimento:</label>
            <input style="font-size: 10px;" name="datavencimento" id="datavencimento" type="date" min="{{$datahoje}}" value="{{$datahoje}}" class="form-control" data-toggle="tooltip" data-placement="top" title="Selecione a data de vencimento." required="required">
        </div>  

        <div class="input-field col s3" style="margin-left: 900px;">
                  <button id="btnsubmit" class="btn waves-effect right" style="background-color: gray;font-size:11px;" onClick="abreconfirmacao();" type="button" name="action">Gerar CPR
                    <i class="material-icons left">check</i>
                  </button>
        </div>

           
        </div>
    </div>

    </form>

  </form>
</section>

</div>
          <div class="content-overlay"></div>
        </div>
      </div>
    </div>


<div id="alertarevisao" name="alertarevisao" class="cd-popup" role="alert">
	<div class="cd-popup-container">
		<p style="font-weight: bold;">Existem debites que não foram revisados no sistema Advwin. Ao clicar em [OK] você irá revisá-los para que possa proseguir com o faturamento. </p>
		<ul class="cd-buttons" style="width: 800px">
			<li><a href="{{route('Painel.Financeiro.Reembolso.PagamentoDebite.verificasolicitacoes', $codigo)}}"  style="font-weight: bold;background-color:#b6bece">OK</a></li>
		</ul>
		<!-- <a onClick="nao();" class="cd-popup-close img-replace">Close</a> -->
	</div> 
</div> 


<div class="cd-popup" role="alert" id="alerta" name="alerta">
	<div class="cd-popup-container">
		<p style="font-weight: bold;">Deseja gerar a CPR das solicitações selecionadas?</p>
		<ul class="cd-buttons">
			<li><a href="#" onClick="nao();" style="font-weight: bold;">Não</a></li>
			<li><a href="#" onClick="sim();" style="font-weight: bold;">Sim</a></li>
		</ul>
		<a onClick="nao();" class="cd-popup-close img-replace">Close</a>
	</div> 
</div> 

@endsection

@section('scripts')
    <script src="{{ asset('/public/materialize/js/plugins.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/custom-script.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/customizer.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/form-select2.min.js') }}"></script>
    <script src="{{ asset('/public/materialize/js/app-invoice.min.js') }}"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
   $('.modal').modal();
});
</script>

<script>
   @if($verificasolicitacoes != 0)
   $('#alertarevisao').addClass('is-visible');
   @endif
</script>

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
  $('#alerta').addClass('is-visible');
}
</script>

<script>
  function sim() {
    $('.modal').css('display', 'none');
    document.getElementById("loadingdiv").style.display = "";
    document.getElementById("div_all").style.display = "none";
    $('.cd-popup').removeClass('is-visible');
    document.getElementById("form").submit();
         
  }    
</script>

<script>

 function nao() {
  $('.cd-popup').removeClass('is-visible');
 }
</script>

@endsection
